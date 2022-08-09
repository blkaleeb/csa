<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\DataVoid;
use App\Models\Komisi;
use App\Models\Konsumen;
use App\Models\SalesInvoicePayment;
use App\Models\SalesOrderHeader;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PiutangController extends Controller
{
    protected $view = "piutang.";
    protected $menu_header = "Piutang";
    protected $menu_title = "Piutang";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date_start = Carbon::now();
        $date_end = Carbon::now();
        if ($request->date_start != "") {
            $date_start = Carbon::parse($request->date_start);
        }
        if ($request->date_end != "") {
            $date_end = Carbon::parse($request->date_end . " 23:59:59");
        }
        $d["menu_header"] = "Laporan Penjualan";
        $d["menu_title"] = "Laporan Penjualan";
        $d["date_start"] = $date_start;
        $d["date_end"] = $date_end;
        $data = SalesInvoicePayment::query();
        if ($request->has("customer")) {
            $d["customer_filter"] = $request->customer;
            $customer = Konsumen::where("name", "like", '%' . $request->customer . '%')->pluck("id")->toArray();
            $sales = SalesOrderHeader::wherein("customer_id", $customer)->pluck("id")->toArray();
            $data = $data->wherein("sales_order_header_id", $sales)
                ->whereBetween("createdOn", [$d["date_start"], $d["date_end"]]);
        }
        if ($request->has("invoice")) {
            $d["invoice_filter"] = $request->invoice;
            $sales = SalesOrderHeader::where("intnomorsales", "like", '%' . $request->invoice . '%')->pluck("id")->toArray();
            $data = $data->wherein("sales_order_header_id", $sales)
                ->whereBetween("createdOn", [$d["date_start"], $d["date_end"]]);
        }
        $data = $data->whereBetween("createdOn", [$d["date_start"], $d["date_end"]]);
        $d["data"] = $data->orderBy("createdOn", "desc")->paginate(25);
        return view($this->view . "index", $d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["is_edit"] = false;
        $d["sales"] = SalesOrderHeader::find($request->salesid);
        return view($this->view . "form", $d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //check
        $sales = SalesOrderHeader::find($request->sales_order_header_id);
        if ($sales->void_status == 0) {
            DB::beginTransaction();

            $invoice = new SalesInvoicePayment();
            $diskon = floor(($request->diskon) / 100 * $request->nilaibayar);
            $counter = Counter::where("table_name", "sales_invoice_payment")->first();
            $nomor = str_pad($counter->sequence_next_value, 4, 0, STR_PAD_LEFT);
            $intnomorsales = str_pad(date("mY") . $nomor, 12, 0, STR_PAD_LEFT);
            $invoice->diskon = $diskon;
            $invoice->giro = $request->giro;
            $invoice->payment_id = $request->payment_id;
            $invoice->jatuhtempo = $request->jatuhtempo;
            $invoice->note = $request->note;
            $invoice->payment_value = $request->nilaibayar - $diskon;
            $invoice->sales_invoice_payment_no = $intnomorsales;
            $invoice->sales_order_header_id = $request->sales_order_header_id;
            $invoice->save();
            $counter->sequence_next_value += 1;
            $counter->save();
            $header = SalesOrderHeader::find($request->sales_order_header_id);
            $header->payment_remain -= $request->nilaibayar;
            $header->total_paid += $request->nilaibayar;
            $header->save();

            $komisi = Komisi::where("sales_order_header_id", $header->id)->firstorfail();
            $komisi->amount = $header->komisi;
            $komisi->current_amount = 0;
            $komisi->customer_id = $header->customer->id;
            $komisi->sales_order_header_id = $header->id;
            $komisi->user_id = $header->customer->sales->id;
            $komisi->save();
            DB::commit();
            return redirect(route("penjualan.index"))->with("success", "Pembayaran diterima");
        } else {
            return redirect(route("penjualan.index"))->with("success", "Pembayaran ditolak, Faktur menunggu/sudah divoid");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["is_edit"] = true;
        $d["data"] = SalesInvoicePayment::find($id);
        $d["sales"] = SalesOrderHeader::find($d["data"]->sales_order_header_id);
        return view($this->view . "form", $d);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $invoice = SalesInvoicePayment::find($id);
        $header = SalesOrderHeader::find($invoice->sales_order_header_id);
        $header->payment_remain += $request->nilaibayar;
        $header->total_paid -= $request->nilaibayar;
        $header->save();
        $diskon = floor(($request->diskon) / 100 * $request->nilaibayar);
        $invoice->diskon = $diskon;
        $invoice->payment_id = $request->payment_id;
        $invoice->giro = $request->giro;
        $invoice->jatuhtempo = $request->jatuhtempo;
        $invoice->note = $request->note;
        $invoice->payment_value = $request->nilaibayar - $diskon;
        $invoice->save();
        $header->payment_remain -= $request->nilaibayar;
        $header->total_paid += $request->nilaibayar;
        $header->save();
        $komisi = Komisi::where("sales_order_header_id", $header->id)->firstorfail();
        $komisi->amount = $header->komisi;
        $komisi->current_amount = 0;
        $komisi->customer_id = $header->customer->id;
        $komisi->sales_order_header_id = $header->id;
        $komisi->user_id = $header->customer->sales->id;
        $komisi->save();
        DB::commit();
        return redirect()->back()->with("success", "Pembayaran diubah");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        //


        // $sales = SalesInvoicePayment::find($id);
        // $sales->void_status = 1;
        // $sales->save();
        $void = new DataVoid();

        $void->type = "SalesInvoicePayment";
        $void->model_id = $id;
        $void->reason = $request->reason;
        $void->status = 0;
        $void->save();


        return redirect()->back()->with("message", "data menunggu approval");
        return redirect()->back()->with("success", "Pembayaran dihapus");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Komisi;
use App\Models\SalesInvoicePayment;
use App\Models\SalesOrderHeader;
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
    public function index()
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["data"] = SalesInvoicePayment::orderBy("createdOn", "desc")->get();
        return view($this->view . "index", $d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["is_edit"] = false;
        $d["sales"] = SalesOrderHeader::where("payment_remain", ">", 0)->get();
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
        return redirect()->back()->with("success", "Pembayaran diterima");
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
    public function destroy($id)
    {
        //
        $inv = SalesInvoicePayment::find($id);
        $header = SalesOrderHeader::find($inv->sales_order_header_id);
        $header->payment_remain += $inv->payment_value + $inv->diskon;
        $header->save();
        $inv->delete();
        return redirect()->back()->with("success", "Pembayaran dihapus");
    }
}

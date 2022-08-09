<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\PoHeader;
use App\Models\PurchaseInvoiceHeader;
use App\Models\PurchaseInvoicePayment;
use App\Models\SalesInvoicePayment;
use App\Models\SalesOrderHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HutangController extends Controller
{
    protected $view = "hutang.";
    protected $menu_header = "Hutang";
    protected $menu_title = "Hutang";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["data"] = PurchaseInvoicePayment::orderBy("createdOn", "desc")->get();
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
        $d["sales"] = PurchaseInvoiceHeader::all();
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
        $invoice = new PurchaseInvoicePayment();
        $diskon = floor(($request->diskon) / 100 * $request->nilaibayar);
        $counter = Counter::where("table_name", "purchase_invoice_payment")->first();
        $nomor = str_pad($counter->sequence_next_value, 4, 0, STR_PAD_LEFT);
        $intnomorsales = str_pad(date("mY") . $nomor, 12, 0, STR_PAD_LEFT);
        $invoice->diskon = $diskon;
        $invoice->payment_id = $request->payment_id;
        $invoice->giro = $request->giro;
        $invoice->jatuhtempo = $request->jatuhtempo;
        $invoice->note = $request->note;
        $invoice->payment_value = $request->nilaibayar - $diskon;
        $invoice->invoice_payment_no = $intnomorsales;
        $invoice->purchase_invoice_header_id = $request->purchase_invoice_header_id;
        $invoice->save();
        $counter->sequence_next_value += 1;
        $counter->save();

        $header = PurchaseInvoiceHeader::find($request->purchase_invoice_header_id);
        $header->paid_total += $request->nilaibayar;
        $header->save();

        $poheader = PoHeader::find($header->poheader_id);
        $poheader->po_total_paid += $request->nilaibayar;
        $poheader->save();
        $invoice->po_header_id = $header->poheader_id;
        $invoice->save();

        DB::commit();
        return redirect()->back()->with("success","Pembayaran diterima");
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
        $d["data"] = PurchaseInvoicePayment::find($id);
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
        $invoice = PurchaseInvoicePayment::find($id);

        $header = PurchaseInvoiceHeader::find($invoice->purchase_invoice_header_id);
        $header->paid_total -= $request->nilaibayar;
        $header->save();

        $poheader = PoHeader::find($header->poheader_id);
        $poheader->po_total_paid -= $request->nilaibayar;
        $poheader->save();

        $diskon = floor(($request->diskon) / 100 * $request->nilaibayar);
        $invoice->diskon = $diskon;
        $invoice->payment_id = $request->payment_id;
        $invoice->giro = $request->giro;
        $invoice->jatuhtempo = $request->jatuhtempo;
        $invoice->note = $request->note;
        $invoice->payment_value = $request->nilaibayar - $diskon;
        $invoice->save();

        $header->paid_total += $request->nilaibayar;
        $header->save();

        $poheader->po_total_paid += $request->nilaibayar;
        $poheader->save();
        DB::commit();
        return redirect()->back()->with("success","Pembayaran diubah");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = PurchaseInvoicePayment::find($id);
        $header = PurchaseInvoiceHeader::find($invoice->purchase_invoice_header_id);
        $header->paid_total += $invoice->payment_value;
        $header->save();

        $poheader = PoHeader::find($header->poheader_id);
        $poheader->po_total_paid += $invoice->payment_value;
        $poheader->save();
        $invoice->delete();

        return redirect()->back()->with("message", "Data dihapus");
        //
    }
}

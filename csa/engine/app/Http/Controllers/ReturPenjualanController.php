<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\CustomerReturnHeader;
use App\Models\CustomerReturnLine;
use App\Models\Konsumen;
use App\Models\PoHeader;
use App\Models\SalesOrderHeader;
use App\Models\Stok;
use App\Models\Supplier;
use App\Models\SupplierReturnHeader;
use App\Models\SupplierReturnLine;
use Illuminate\Http\Request;

class ReturPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $view = "penjualan-retur.";
    protected $menu_header = "Retur Penjualan";
    protected $menu_title = "Retur Penjualan";

    public function index()
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["data"] = CustomerReturnHeader::all();
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
        $d["customers"] = Konsumen::with("salesorder")->get();
        $d["stock"] = Stok::all();
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
        $counter = Counter::find(8);
        $nomor = str_pad($counter->sequence_next_value, 4, 0, STR_PAD_LEFT);
        $no_invoice = "R" . str_pad(date("mY") . $nomor, 12, 0, STR_PAD_LEFT);
        $header = new CustomerReturnHeader();
        $header->no_invoice = $no_invoice;
        $header->status = "C";
        $header->customer_id = $request->customer_id;
        $header->sales_id = $request->sales_id;
        $header->save();

        //line
        $count = count($request->item_stock_id);
        for ($i = 0; $i < $count; $i++) {
            $line = new CustomerReturnLine();
            $line->qty = $request->quantity[$i];
            $line->returprice = $request->harga_satuan[$i];
            $line->item_stock_id = $request->item_stock_id[$i];
            $line->customer_return_header_id = $header->id;
            $line->save();

            //returing
            $stock = Stok::find($request->item_stock_id[$i]);
            $stock->qty += $request->quantity[$i];
            $stock->save();

            //kurangin
            $salesheader = SalesOrderHeader::find($request->sales_id);
            $salesheader->payment_remain -= $request->subtotal[$i];
            $salesheader->retur += $request->subtotal[$i];
            $salesheader->save();
        }
        $counter->sequence_next_value++;
        $counter->save();
        return redirect()->back()->with("message", "Data disimpan");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = CustomerReturnHeader::with("customer.sales", "detail.stock", "detail.stock.satuan")->find($id);
        return $this->responseBase($data);
        // $d["menu_header"] = $this->menu_header;
        // $d["menu_title"] = $this->menu_title;
        // return view($this->view . "show", $d);
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
        $d["data"] = CustomerReturnHeader::find($id);
        $d["customers"] = Konsumen::all();
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
        $merk = CustomerReturnHeader::find($id);
        foreach ($request->except("_token", "_method") as $key => $value) {
            $merk->$key = $value;
        }
        $merk->save();
        return redirect()->back()->with("message", "Data diubah");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $header = CustomerReturnHeader::find($id);
        $line = CustomerReturnLine::where("customer_return_header_id",$id)->get();
        foreach($line as $item){
            $stock = Stok::find($item->item_stock_id);
            $stock->qty -= $item->qty;
            $stock->save();
        }
        $header->delete();
        return redirect()->back()->with("message", "Data dihapus");
    }

    public function print($id)
    {
        $this->sendPrintReturPenjualan($id);
        return redirect()->back()->with("message", "Printing");
    }
}

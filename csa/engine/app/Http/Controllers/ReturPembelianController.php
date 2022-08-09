<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\PoHeader;
use App\Models\Stok;
use App\Models\Supplier;
use App\Models\SupplierReturnHeader;
use App\Models\SupplierReturnLine;
use Illuminate\Http\Request;

class ReturPembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $view = "pembelian-retur.";
    protected $menu_header = "Retur Pembelian";
    protected $menu_title = "Retur Pembelian";

    public function index()
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["data"] = SupplierReturnHeader::all();
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
        $d["suppliers"] = Supplier::with("poheader")->get();
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
        $counter = Counter::find(9);
        $nomor = str_pad($counter->sequence_next_value, 4, 0, STR_PAD_LEFT);
        $no_invoice = "R" . str_pad(date("mY") . $nomor, 12, 0, STR_PAD_LEFT);
        $header = new SupplierReturnHeader();
        $header->no_invoice = $no_invoice;
        $header->supplier_code = $request->supplier_id;
        $header->po_id = $request->po_id;
        $header->save();

        //line
        $count = count($request->item_stock_id);
        for ($i = 0; $i < $count; $i++) {
            $line = new SupplierReturnLine();
            $line->qty = $request->quantity[$i];
            $line->retur_price = $request->harga_satuan[$i];
            $line->item_stock_id = $request->item_stock_id[$i];
            $line->supplier_return_header_id = $header->id;
            $line->save();

            //returing
            $stock = Stok::find($request->item_stock_id[$i]);
            $stock->qty -= $request->quantity[$i];
            $stock->save();

            //kurangin
            $po = PoHeader::find($request->po_id);
            $po->po_total -= $request->subtotal[$i];
            $po->retur += $request->subtotal[$i];
            $po->save();
        }
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
        // $d["menu_header"] = $this->menu_header;
        // $d["menu_title"] = $this->menu_title;
        // return view($this->view . "show", $d);
        $data = SupplierReturnHeader::with("supplier", "detail.stock")->find($id);
        return $this->responseBase($data);
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
        $d["data"] = SupplierReturnHeader::find($id);
        $d["suppliers"] = Supplier::all();
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
        $merk = Merk::find($id);
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
        $merk = Merk::find($id);
        $merk->delete();
        return redirect()->back()->with("message", "Data dihapus");
    }

    public function print($id)
    {
        $this->sendPrintReturPembelian($id);
        return redirect()->back()->with("message", "Printing");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Konsumen;
use App\Models\SalesOrderHeader;
use App\Models\SalesOrderLine;
use App\Models\Stok;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanLineController extends Controller
{
    protected $view = "penjualan.line.";
    protected $menu_header = "Penjualan";
    protected $menu_title = "Penjualan";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        $d["data"] = SalesOrderLine::with("salesorderheader")->find($id);
        $d["stock"] = Stok::all();
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
        $line = SalesOrderLine::find($id);
        $header = SalesOrderHeader::find($line->sales_order_header_id);

        //kembaliin stock , kurangin total sales dulu
        $total_pengurangan = $line->price_per_satuan_id * $line->qty;
        $header->total_sales -= $total_pengurangan;
        $header->payment_remain -= $total_pengurangan;
        $header->modal -= $line->sales_per_satuan_id * $line->qty;
        $header->save();

        $stock = Stok::find($line->item_stock_id);
        $stock->qty += $line->qty;
        $stock->save();


        $modal = 0;
        if ($request->harga_satuan == 0) {
            $line->bonus = 1;
        } else {
            $line->bonus = 0;
        }
        $line->diskon = $request->discount;
        $line->price_per_satuan_id = $request->harga_satuan; //harga jual 1 an
        $line->sales_per_satuan_id = $request->harga_modal; //harga modal 1 an
        $line->qty = $request->quantity;
        $line->item_stock_id = $request->item_stock_id;
        $line->save();
        //minus Stock
        $stock = Stok::find($request->item_stock_id);
        $stock->qty -= $request->quantity;
        $stock->save();

        $header->modal += $request->harga_modal;
        $header->total_sales += $request->quantity* $request->harga_satuan;
        $header->payment_remain += $request->quantity* $request->harga_satuan;
        $header->save();


        DB::commit();
        if ($request->ajax()) {
            return response()->json("success", 200);
        }
        return redirect()->back()->with("success", "Data diubah");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = SalesOrderLine::find($id);
        $stockname = $data->stock->name;
        $header = $data->header;
        //get total uang
        $stock = Stok::find($data->item_stock_id);
        $stock->qty += $data->qty;
        $stock->save();

        $totalsales = $data->price_per_satuan_id * $data->qty;
        $totalmodal = $data->sales_per_satuan_id * $data->qty;
        $header->modal -= $totalmodal;
        $header->total_sales -= $totalsales;
        $header->payment_remain -= $totalsales;
        $header->save();
        $data->delete();
        return redirect()->back()->with("message", $stockname." dihapus");
    }

    public function getSalesHistory(Request $request)
    {
        $data = SalesOrderHeader::where("customer_id", $request->customer_id)->where("payment_remain", ">", 0)->get();
        return response()->json($data);
    }
    public function getSalesLineHistory(Request $request)
    {
        $header = SalesOrderHeader::where("customer_id", $request->customer_id)->get()->pluck("id")->toArray();
        $data = SalesOrderLine::with("stock")->whereIn("sales_order_header_id", $header)->where("item_stock_id", $request->item_stock_id)->get();
        return response()->json($data);
    }
}

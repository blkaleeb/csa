<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Konsumen;
use App\Models\PoLine;
use App\Models\SalesOrderHeader;
use App\Models\SalesOrderLine;
use App\Models\Stok;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoLineController extends Controller
{
    protected $view = "po.line.";
    protected $menu_header = "Purchase Order Line";
    protected $menu_title = "Purchase Order Line";
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
        $d["data"] = PoLine::with("header")->find($id);
        $d["stock"] = Stok::all();
        $d["warehouses"] = Warehouse::all();
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
        //return stock
        $line = PoLine::find($id);
        $line->item_stock_id = $request->item_stock_id;
        $line->qty = $request->quantity;
        $line->warehouse_id = $request->warehouse_id;
        $line->save();
        if ($line->penerimaan == 1) {
            $stock = Stok::find($line->item_stock_id);
            $stock->qty -= $request->quantity;
            $stock->save();

            $stock = Stok::find($request->item_stock_id);
            $stock->qty += $request->quantity;
            $stock->save();
        }

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
        //
    }
}

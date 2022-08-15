<?php

namespace App\Http\Controllers;

use App\Models\CustomerReturnHeader;
use App\Models\CustomerReturnLine;
use App\Models\Kategori;
use App\Models\Merk;
use App\Models\PoHeader;
use App\Models\PoLine;
use App\Models\PurchaseInvoiceLine;
use App\Models\PurchaseOrderLine;
use App\Models\SalesOrderHeader;
use App\Models\SalesOrderLine;
use App\Models\Satuan;
use App\Models\StockOpname;
use App\Models\Stok;
use App\Models\SupplierReturnLine;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LapOpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $view = "lap_opname.";
    protected $menu_header = "Laporan Opname";
    protected $menu_title = "Laporan Opname";

    public function index(Request $request)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;

        $d["data"] = StockOpname::
        select(DB::raw('id, DATE(createdOn) as date, SUM(qty) as totalqty'))
        ->groupBy('date')
        ->orderBy('id','desc')->paginate(10);
        $d["stocks"] = Stok::all();
        $d["category"] = Kategori::wherein("id", $d["stocks"]->pluck("category_id")->toArray())->get();
        $d["brand"] = Merk::whereIn("id", $d["stocks"]->pluck("brand_id")->toArray())->get();
        
       return view($this->view . "index", $d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request->item as $item_stock_id => $qty) {
            $itemstock = Stok::find($item_stock_id);
            $opname = new StockOpname();
            $opname->in_out = 2;
            $opname->notes = "Stock Opname";
            $opname->qty = $qty;
            $opname->qty_before = $itemstock->qty;
            $opname->item_stock_id = $item_stock_id;
            $opname->save();

            $itemstock->qty = $qty;
            $itemstock->save();
        }
        return redirect()->back()->with("message", "Data disimpan");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $d["data"] = StockOpname::select(DB::raw('item_stock.id,DATE_FORMAT(stock_opname.createdOn,"%d-%m-%Y %H:%i:%S") as date,item_stock.name,stock_opname.qty,stock_opname.qty_before'))->leftJoin('item_stock','item_stock.id','=','stock_opname.item_stock_id')->where(DB::raw("DATE(stock_opname.createdOn)"),'=',DB::raw("DATE('".$id."')"))->orderBy('stock_opname.id','desc')->get();

        return json_encode($d);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

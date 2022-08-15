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

class StokBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $view = "stok_barang.";
    protected $menu_header = "Daftar Stok Barang";
    protected $menu_title = "Daftar Stok Barang";

    public function index(Request $request)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;

        $d["merks"] = Merk::all();
        $d["kategoris"] = Kategori::all();
        $d["satuan"] = Satuan::all();
        $d["gudangs"] = Warehouse::all();

        $d["stocks"] = Stok::with("gudang")->paginate(10);
        // $stok = Stok::with("gudang")->get();

        $stok = Stok::join('warehouse','warehouse.id','=','item_stock.warehouse_id')
                            ->select(DB::raw('IFNULL(qty,0) as qty, threshold_bottom'))
                            ->get();

        $stokavail = 0;
        $stokhabis = 0;
        $stokbeli = 0; 
        foreach ($stok as $s) {
            if($s->qty > $s->threshold_bottom){
                $stokavail++;
            }elseif($s->qty == 0){
                $stokhabis++;
            }else{
                $stokbeli++;
            }
        }

        $d["stokavail"] = $stokavail;
        $d["stokhabis"] = $stokhabis;
        $d["stokbeli"] = $stokbeli;

        if ($request->ajax()) {
            if ($request->has("q")) {
                $d["stocks"] = Stok::with("gudang")->where("name", "like", "%" . $request->q . "%")->paginate(10);
            }
            return response()->json($d["stocks"]);
        } else {
            return view($this->view . "index", $d);
        }
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
        $stok = new Stok();
        foreach ($request->except("_token") as $key => $value) {
            $stok->$key = $value;
        }
        $stok->save();
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
        $date_start = Carbon::parse("2021-09-08");
        $date_end = Carbon::now();
        if ($request->date_start != "") {
            $date_start = Carbon::parse($request->date_start)->addDays(1);
        }
        if ($request->date_end != "") {
            $date_end = Carbon::parse($request->date_end)->addDays(1);
        }
        $stock = Stok::find($id);
        //Data Opname
        $d["opname"] = StockOpname::where("item_stock_id", $id)->get();
        // //Data Penjualan
        $d["penjualan"] = SalesOrderLine::with("header")->join('sales_order_header','sales_order_header.id','=','sales_order_line.sales_order_header_id')->join('customer','customer.id','=','sales_order_header.customer_id')->where("item_stock_id", $id)->whereBetween('sales_order_header.createdOn', [$date_start, $date_end])->get();
        //Data Pembelian
        $d["pembelian"] = PurchaseInvoiceLine::with(

            "header",
            "poline",
            "poheader"

        )->whereHas('poline', function (Builder $query) use ($id) {
            $query->where('item_stock_id', '=', $id);
        })->whereBetween("createdOn", [$date_start, $date_end])->get();
        //Data Retur Penjualan
        $d["retur_penjualan"] = CustomerReturnLine::with("header")->where("item_stock_id", $id)->whereBetween("createdOn", [$date_start, $date_end])->get();
        //Data Retur Pembelian
        $d["retur_pembelian"] = SupplierReturnLine::with("header")->where("item_stock_id", $id)->whereBetween("createdOn", [$date_start, $date_end])->get();

        $d["date_start"] = $date_start;
        $d["date_end"] = $date_end;
        $d["stock"] = $stock;

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

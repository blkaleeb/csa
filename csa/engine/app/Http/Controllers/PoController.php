<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Konsumen;
use App\Models\PoHeader;
use App\Models\PoLine;
use App\Models\SalesOrderHeader;
use App\Models\SalesOrderLine;
use App\Models\Stok;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoController extends Controller
{
    protected $view = "po.";
    protected $menu_header = "Purchase Order";
    protected $menu_title = "Purchase Order";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["data"] = PoHeader::orderBy("createdOn", "desc")->get();
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
        $d["suppliers"] = Supplier::all();
        $d["stock"] = Stok::with('satuan')->get();
        $d["warehouses"] = Warehouse::all();
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
        $counter = Counter::where("table_name", "po")->first();
        $nomor = str_pad($counter->sequence_next_value, 4, 0, STR_PAD_LEFT);

        $supplier = Supplier::find($request->supplier_id);
        $nomor = Counter::where("table_name", "=", "po")->first();
        $po_no = "PO/" . $supplier->suppliercode . "/" . date("m") . "/" . date("Y") . "/" . $nomor->sequence_next_value;

        DB::beginTransaction();
        $header = new PoHeader();
        $header->supplier_id = $request->supplier_id;
        $header->po_no = $po_no;
        $header->po_status = "C";
        $header->save();
        $counter->sequence_next_value += 1;
        $counter->save();
        //preprare line
        $count_line = count($request->item_stock_id);
        for ($i = 0; $i < $count_line; $i++) {
            $line = new PoLine();
            $line->item_stock_id = $request->item_stock_id[$i];
            $line->qty = $request->quantity[$i];
            $line->warehouse_id = $request->warehouse_id[$i];
            $line->bonus = $request->bonus[$i];
            $line->po_header_id = $header->id;
            $line->save();
        }
        DB::commit();
        return response()->json("success", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $d["menu_header"] = $this->menu_header;
        $d["is_edit"] = true;
        $d["data"] = PoHeader::with("supplier")->find($id);
        $d["menu_title"] = $d["data"]->po_no;

        $d["suppliers"] = Supplier::all();
        $d["stock"] = Stok::with('satuan')->get();
        $d["warehouses"] = Warehouse::all();

        return view($this->view . "show", $d);
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
        $d["data"] = PoHeader::with("supplier")->find($id);
        $d["suppliers"] = Supplier::all();
        $d["stock"] = Stok::with('satuan')->get();
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
        $header = PoHeader::find($id);

        if ($request->has("update_header")) {
            DB::beginTransaction();
            $counter = Counter::where("table_name", "po")->first();
            $nomor = str_pad($counter->sequence_next_value, 4, 0, STR_PAD_LEFT);
            $supplier = Supplier::find($request->supplier_id);
            $nomor = Counter::where("table_name", "=", "po")->first();
            $po_no = "PO/" . $supplier->suppliercode . "/" . date("m") . "/" . date("Y") . "/" . $nomor->sequence_next_value;
            $header->supplier_id = $request->supplier_id;
            $header->po_no = $po_no;
            $header->po_status = "C";
            $header->save();
            $counter->sequence_next_value += 1;
            $counter->save();
        }
        if ($request->has("update_line")) {
            //preprare line
            $count_line = count($request->item_stock_id);
            for ($i = 0; $i < $count_line; $i++) {
                $line = new PoLine();
                $line->item_stock_id = $request->item_stock_id[$i];
                $line->qty = $request->quantity[$i];
                $line->warehouse_id = $request->warehouse_id[$i];
                $line->bonus = $request->bonus[$i];
                $line->po_header_id = $header->id;
                $line->save();
            }
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

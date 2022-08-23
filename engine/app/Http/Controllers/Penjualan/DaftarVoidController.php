<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\CustomerReturnHeader;
use App\Models\CustomerReturnLine;
use App\Models\DataVoid;
use App\Models\Konsumen;
use App\Models\SalesInvoicePayment;
use App\Models\SalesOrderHeader;
use App\Models\SalesOrderLine;
use App\Models\Stok;
use Illuminate\Http\Request;

class DaftarVoidController extends Controller
{
    protected $menu_header = "Daftar Void";
    protected $menu_title = "Daftar Void";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $data = DataVoid::query();
        $d["data"] = $data->orderBy("createdOn", "desc")->where("status", 0)->paginate(25)->withQueryString();

        //paginate variable
        $d["limit"] = $request->limit ?? 25;
        return view("revamp.penjualan.daftar-void.index", $d);
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
        //
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

    public function approve($id)
    {
        $void = DataVoid::find($id);
        if ($void->type == "SalesOrderHeader") {
            $header = SalesOrderHeader::find($void->model_id);
            $lines = SalesOrderLine::where("sales_order_header_id", $void->model_id)->get();
            foreach ($lines as $data) {
                $stockname = $data->stock->name;
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
            }
            $header->delete();
        } else if ($void->type == "CustomerReturnHeader") {
            $header = CustomerReturnHeader::find($void->model_id);
            $line = CustomerReturnLine::where("customer_return_header_id", $void->model_id)->get();
            foreach ($line as $item) {
                $stock = Stok::find($item->item_stock_id);
                $stock->qty -= $item->qty;
                $stock->save();
            }
            $header->delete();
        } else if ($void->type == "SalesInvoicePayment") {
            $inv = SalesInvoicePayment::find($id);
            $header = SalesOrderHeader::find($inv->sales_order_header_id);
            $header->payment_remain += $inv->payment_value + $inv->diskon;
            $header->save();
            $inv->delete();
        }
        $void->status = 1;
        return redirect()->back()->with("message", "Data dihapus");
    }

    public function cancel($id)
    {
    }
}

<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Konsumen;
use App\Models\SalesInvoicePayment;
use App\Models\SalesOrderHeader;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date_start = Carbon::now()->startOfDay();
        $date_end = Carbon::now()->endOfDay();
        if ($request->date_start != "") {
            $date_start = Carbon::parse($request->date_start);
        }
        if ($request->date_end != "") {
            $date_end = Carbon::parse($request->date_end . " 23:59:59");
        }
        $d["menu_header"] = "Laporan Penjualan";
        $d["menu_title"] = "Laporan Penjualan";
        $d["date_start"] = $date_start;
        $d["date_end"] = $date_end;
        $data = SalesInvoicePayment::query();
        if ($request->has("customer")) {
            $d["customer_filter"] = $request->customer;
            $customer = Konsumen::where("name", "like", '%' . $request->customer . '%')->pluck("id")->toArray();
            $sales = SalesOrderHeader::wherein("customer_id", $customer)->pluck("id")->toArray();
            $data = $data->wherein("sales_order_header_id", $sales)
                ->whereBetween("createdOn", [$d["date_start"], $d["date_end"]]);
        }
        if ($request->has("invoice")) {
            $d["invoice_filter"] = $request->invoice;
            $sales = SalesOrderHeader::where("intnomorsales", "like", '%' . $request->invoice . '%')->pluck("id")->toArray();
            $data = $data->wherein("sales_order_header_id", $sales)
                ->whereBetween("createdOn", [$d["date_start"], $d["date_end"]]);
        }
        $data = $data->whereBetween("createdOn", [$d["date_start"], $d["date_end"]]);
        $d["data"] = $data->orderBy("createdOn", "desc")->paginate(25);
        return view("revamp.penjualan.laporan-penjualan.index", $d);
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
}

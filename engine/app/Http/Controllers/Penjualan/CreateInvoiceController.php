<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Konsumen;
use App\Models\SalesOrderHeader;
use App\Models\Stok;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CreateInvoiceController extends Controller
{
    protected $menu_header = "Buat Faktur";
    protected $menu_title = "Buat Faktur";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $d["mode"] = $request->mode;
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["is_edit"] = false;
        $d["customers"] = Konsumen::with("sales")->get();
        foreach ($d["customers"] as $customer) {
            $block = SalesOrderHeader::where("customer_id", $customer->id)->where("payment_remain", ">", 0)->get();
            if (count($block) > 3) {
                $customer->block = 1;
            } else {
                $customer->block = 0;
            }
        }
        $d["kenek"] = User::where("role_id", 5)->get();
        $d["supir"] = User::where("role_id", 4)->get();
        $d["stock"] = Stok::all();
        $d["jatuhtempo"] = Carbon::now()->addDays(30);
        $d["orderdate"] = Carbon::now();
        if ($request->mode == "popup") {
            return view("revamp.penjualan.create-invoice.form", $d)->render();
        } else {
            return view("revamp.penjualan.create-invoice.form", $d);
        }
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

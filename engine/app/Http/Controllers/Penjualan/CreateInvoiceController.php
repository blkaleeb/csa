<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Komisi;
use App\Models\Konsumen;
use App\Models\SalesOrderHeader;
use App\Models\SalesOrderLine;
use App\Models\Stok;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $today = new DateTime(date("Y-m-d"));
        foreach ($d["customers"] as $customer) {
            $block = SalesOrderHeader::where("customer_id", $customer->id)->where("payment_remain", ">", 0)->get();
            if (count($block) > 3) {
                $customer->block = 1;
            } else {
                $customer->block = 0;
            }
            $temp = SalesOrderHeader::where("customer_id", $customer->id)->where("payment_remain",">",0)->orderby("due_date")->first();
            if ($temp != null) {
                $due_date = new DateTime (date("Y-m-d", strtotime($temp->due_date)));
                $diff_m = $due_date->diff($today)->format('%m');
                $diff_y = $due_date->diff($today)->format('%y');
                // dd($diff_m < 3);
                if ($due_date < $today) {
                    if ($diff_y == 0 && $diff_m < 3) {
                            $customer->block = 0;
                    } else {
                        $customer->block = 1;
                    }
                } else {
                    $customer->block = 0;
                }
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
        $counter = Counter::find(5);
        $nomor = str_pad($counter->sequence_next_value, 4, 0, STR_PAD_LEFT);
        $intnomorsales = str_pad(date("mY") . $nomor, 12, 0, STR_PAD_LEFT);
        DB::beginTransaction();
        $total_sales= 0;
        //garagarapopup
        foreach($request->subtotal as $subtotal){
            $total_sales += $subtotal;
        }
        //endgaragarapopup

        $header = new SalesOrderHeader();
        $header->due_date = Carbon::parse($request->due_date)->format('Y-m-d');
        $header->intnomorsales = $intnomorsales;
        $header->order_date = Carbon::parse($request->order_date)->format('Y-m-d');
        $header->payment_remain = $total_sales;
        $header->retur = 0;
        $header->status = "C";
        $header->total_paid = 0;
        $header->total_sales = $total_sales;
        $header->customer_id = $request->customer_id;
        $header->supir = $request->supir;
        $header->kenek = $request->kenek;
        $header->save();
        $counter->sequence_next_value += 1;
        $counter->save();
        //preprare line
        $modal = 0;
        $count_line = count($request->item_stock_id);
        $komisipersen = 0.8;
        for ($i = 0; $i < $count_line; $i++) {
            $line = new SalesOrderLine();
            if ($request->harga_satuan[$i] == 0) {
                $line->bonus = 1;
            } else {
                $line->bonus = 0;
            }
            $stock = Stok::find($request->item_stock_id[$i]);

            $line->diskon = $request->discount[$i];
            $line->price_per_satuan_id = $request->harga_satuan[$i]; //harga jual 1 an
            $line->sales_per_satuan_id = $request->harga_modal[$i]; //harga modal 1 an
            $line->qty = $request->quantity[$i];
            $line->item_stock_id = $request->item_stock_id[$i];
            $line->sales_order_header_id = $header->id;
            $line->save();

            if ($line->stock->brand) {
                // echo $line->stock->brand->name . "<br>";
                $komisipersen = $line->stock->brand->komisi;
            }

            $modal += $stock->purchase_price * $line->qty;

            //minus Stock
            $stock->qty -= $request->quantity[$i];
            $stock->save();
        }

        $header->komisi = $total_sales * ($komisipersen / 100);
        $header->modal = $modal;
        $header->save();
        try {
            $komisi = Komisi::where("sales_order_header_id", $header->id)->firstorfail();
            $komisi->amount = $header->komisi;
            $komisi->current_amount = 0;
            $komisi->customer_id = $header->customer->id;
            $komisi->sales_order_header_id = $header->id;
            $komisi->user_id = $header->customer->sales->id;
            $komisi->percentage = $komisipersen;
            $komisi->save();
        } catch (\Throwable $th) {
            $komisi = new Komisi();
            $komisi->amount = $header->komisi;
            $komisi->current_amount = 0;
            $komisi->sales_order_header_id = $header->id;
            $komisi->user_id = $header->customer->sales->id;
            $komisi->percentage = $komisipersen;
            $komisi->save();
        }

        DB::commit();
        if ($request->has("prints")) {
            $this->sendPrintPenjualan($header->id);
        } else {
            return response()->json("success", 200);
        }

        // $header->diskon

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

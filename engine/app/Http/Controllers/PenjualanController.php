<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\DataVoid;
use App\Models\Konsumen;
use App\Models\SalesOrderHeader;
use App\Models\SalesOrderLine;
use App\Models\Stok;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Komisi;

class PenjualanController extends Controller
{
    protected $view = "penjualan.";
    protected $menu_header = "Penjualan";
    protected $menu_title = "Penjualan";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date_start = Carbon::now();
        $date_end = Carbon::now();
        if ($request->date_start != "") {
            $date_start = Carbon::parse($request->date_start);
        }
        if ($request->date_end != "") {
            $date_end = Carbon::parse($request->date_end." 23:59:59");
        }
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["date_start"] = $date_start;
        $d["date_end"] = $date_end;
        $data = SalesOrderHeader::query();
        if ($request->has("void")) {
            $d["void"] = true;
            $d["menu_title"] = $this->menu_header . " Dihapus";
            $d["date_start"] = Carbon::parse("2021-09-01");
            $data = $data->onlyTrashed();
        }
        if ($request->has("customer")) {
            $d["customer_filter"] = $request->customer;
            $customer = Konsumen::where("name", "like", '%' . $request->customer . '%')->pluck("id")->toArray();
            $data = $data->wherein("customer_id", $customer)
                ->whereBetween("createdOn", [$d["date_start"], $d["date_end"]]);
        }
        if ($request->has("invoice")) {
            $d["invoice_filter"] = $request->invoice;

            $data = $data->where("intnomorsales", "like", '%' . $request->invoice . '%')
                ->whereBetween("createdOn", [$d["date_start"], $d["date_end"]]);
        }

        $data = $data->whereBetween("createdOn", [$d["date_start"], $d["date_end"]]);


        $d["data"] = $data->orderBy("createdOn", "desc")->paginate(25)->withQueryString();

        //paginate variable
        $d["limit"] = $request->limit ?? 25;
        // $d["viewform"] = $this->create(1,1);
        return view($this->view . "index", $d);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($mode = 0, $popup = 0)
    {
        $d["popup"] = $popup;
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
        if($mode == 0){
        return view($this->view . "form", $d);
        }else{
        return view($this->view . "form", $d)->render();

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
        $header = new SalesOrderHeader();
        $header->due_date = Carbon::parse($request->due_date)->format('Y-m-d');
        $header->intnomorsales = $intnomorsales;
        $header->order_date = Carbon::parse($request->order_date)->format('Y-m-d');
        $header->payment_remain = $request->total_sales;
        $header->retur = 0;
        $header->status = "C";
        $header->total_paid = 0;
        $header->total_sales = $request->total_sales;
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

        $header->komisi = $request->total_sales * ($komisipersen / 100);
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
    public function show(Request $request, $id)
    {
        //
        $data = SalesOrderHeader::with("detail.stock.satuan", "customer.sales", "staffsupir", "staffkenek")->find($id);
        return $this->responseBase($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $d["popup"] = 0;
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["is_edit"] = true;
        $d["data"] = SalesOrderHeader::with("customer")->find($id);
        $d["customers"] = Konsumen::all();
        $d["stock"] = Stok::all();
        $d["kenek"] = User::where("role_id", 4)->get();
        $d["supir"] = User::where("role_id", 5)->get();
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
        $header = SalesOrderHeader::find($id);
        if ($request->has("update_header")) {
            $header->due_date = Carbon::parse($request->due_date)->format('Y-m-d');
            $header->order_date = Carbon::parse($request->order_date)->format('Y-m-d');
            $header->customer_id = $request->customer_id;
            $header->supir = $request->supir;
            $header->kenek = $request->kenek;
            $header->save();
        }
        $komisipersen = 0.8;

        if ($request->has("update_line")) {
            $modal = 0;
            if ($request->has('item_stock_id')) {
                $count_line = count($request->item_stock_id);
                for ($i = 0; $i < $count_line; $i++) {
                    $line = new SalesOrderLine();
                    if ($request->harga_satuan[$i] == 0) {
                        $line->bonus = 1;
                    } else {
                        $line->bonus = 0;
                    }
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
                    $modal += $line->sales_per_satuan_id * $line->qty;

                    //minus Stock
                    $stock = Stok::find($request->item_stock_id[$i]);
                    $stock->qty -= $request->quantity[$i];
                    $stock->save();
                }
                $header->modal += $modal;
                $selisih = $request->total_sales - $header->total_sales;
                $header->total_sales = $request->total_sales;
                $header->payment_remain += $selisih;
                $header->komisi = $request->total_sales * ($komisipersen / 100);

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

                $header->save();
            } else {
                //recalculate
                //preprare line
                $modal = 0;
                $komisipersen = 0.8;
                $totalsales = 0;
                foreach ($header->line as $line) {

                    $stock = Stok::find($line->item_stock_id);
                    $totalsales += $line->price_per_satuan_id * $line->qty;
                    $modal += $stock->purchase_price * $line->qty;
                }
                $header->total_sales = $totalsales;
                $header->payment_remain = $totalsales - $header->total_paid;
                $header->komisi = $totalsales * ($komisipersen / 100);
                $header->modal = $modal;
                $header->save();
            }
        }

        if ($request->has("print")) {
            $header->print = $request->print;
            $header->save();
            return  $this->responseBase($header, 200);
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
    public function destroy(Request $request, $id)
    {
        $sales = SalesOrderHeader::find($id);
        $sales->void_status = 1;
        $sales->save();
        $void = new DataVoid();

        $void->type = "SalesOrderHeader";
        $void->model_id = $id;
        $void->reason = $request->reason;
        $void->status = 0;
        $void->save();


        return redirect()->back()->with("message", "data menunggu approval");

        // $header = SalesOrderHeader::find($id);
        // $lines = SalesOrderLine::where("sales_order_header_id", $id)->get();
        // foreach ($lines as $data) {
        //     $stockname = $data->stock->name;
        //     //get total uang
        //     $stock = Stok::find($data->item_stock_id);
        //     $stock->qty += $data->qty;
        //     $stock->save();

        //     $totalsales = $data->price_per_satuan_id * $data->qty;
        //     $totalmodal = $data->sales_per_satuan_id * $data->qty;
        //     $header->modal -= $totalmodal;
        //     $header->total_sales -= $totalsales;
        //     $header->payment_remain -= $totalsales;
        //     $header->save();
        //     $data->delete();
        // }
        // $header->delete();
    }

    public function print($id)
    {
        $this->sendPrintPenjualan($id);
        return redirect()->back()->with("message", "Printing");
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

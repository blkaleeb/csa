<?php

namespace App\Http\Controllers;

use App\Models\RequestAccSalesHeader;
use App\Models\RequestAccSalesLine;
use App\Models\Counter;
use App\Models\DataVoid;
use App\Models\Komisi;
use App\Models\Konsumen;
use App\Models\Stok;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use App\Models\SalesOrderHeader;
use App\Models\SalesOrderLine;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListRequestAdminController extends Controller
{

    protected $view = "listrequestadmin.";
    protected $menu_header = "Daftar Request";
    protected $menu_title = "Daftar Request";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["customers"] = Konsumen::all();
        $d["kenek"] = User::where("role_id", 5)->get();
        $d["supir"] = User::where("role_id", 4)->get();
        $d["data"] = RequestAccSalesHeader::with('user')->orderBy('createdOn', 'desc')->where('stat', '=', 0)->paginate(10);
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

            $line->diskon = 0;
            $line->price_per_satuan_id = $request->harga_satuan[$i]; //harga jual 1 an
            $line->sales_per_satuan_id = $request->harga_satuan[$i]; //harga modal 1 an
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

        DB::table('requestacc_sales_header')->where('id',$request->header_id)->update(array(
            'stat'=>1,
        ));

        DB::commit();
        return redirect()->back()->with("success", "Pembayaran diterima");
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
        $d["menu_title"] = $this->menu_title;
        // $d["customers"] = Konsumen::all();
        $d["data2"] = RequestAccSalesHeader::join('customer','requestacc_sales_header.customer_id','=','customer.id')->join('users','requestacc_sales_header.createdBy','=','users.id')->where('requestacc_sales_header.id','=',$id)->get();
        $d["data"] = RequestAccSalesHeader::find($id);
        $d["detail"] = RequestAccSalesLine::join('requestacc_sales_header','requestacc_sales_line.request_acc_sales_header_id','=','requestacc_sales_header.id')
        ->join('item_stock','requestacc_sales_line.item_stock_id','=','item_stock.id')
        ->join('satuan','item_stock.satuan_id','=','satuan.id')->where('requestacc_sales_header.id','=',$id)
        ->select(DB::raw('date_format(requestacc_sales_header.createdOn,"%d/%m/%Y %H:%i:%s") as tgl, requestacc_sales_line.*, satuan.name as satuan_name, item_stock.name as barang, item_stock.purchase_price, item_stock.sell_price'))
        ->where("requestacc_sales_header.id","=",$id)->get();

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

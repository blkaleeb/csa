<?php

namespace App\Http\Controllers;

use App\Models\RequestSalesHeader;
use App\Models\RequestSalesLine;
use App\Models\RequestAccSalesHeader;
use App\Models\RequestAccSalesLine;
use App\Models\Konsumen;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListRequestOwnerController extends Controller
{
    protected $view = "listrequestowner.";
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
        $d["data"] = RequestSalesHeader::with('user')->paginate(10);
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
        DB::beginTransaction();
        //insert master
        DB::insert('INSERT INTO `requestacc_sales_header` (
            `createdBy`,`createdOn`,`updatedBy`,`updatedOn`,`diskon`,`due_date`,`intnomorsales`,`nomorsales`,`order_date`,`payment_remain`,`payment_terms`,`pos`,`retur`,`status`,`total_dp`,`total_paid`, `total_sales`,`bank_id`, `customer_id`,`modal`,`supir`, `kenek`,`print`,`deletedOn`,`komisi`)
            (SELECT
            '.Auth::user()->id.',NOW(),`updatedBy`, NOW(),`diskon`, `due_date`,`intnomorsales`, `nomorsales`,`order_date`,`payment_remain`,`payment_terms`, `pos`,`retur`, `status`,`total_dp`, `total_paid`,`total_sales`,`bank_id`, `customer_id`,`modal`,`supir`,`kenek`,`print`, `deletedOn`, `komisi`
          FROM `request_sales_header` where id='.$request->id.')');
        $lastid = DB::getPdo()->lastInsertId();
        if ($request->line != null) {
            $count_line = count($request->line);
            for ($i = 0; $i < $count_line; $i++) {
                if($request->qty[$i] == 0 && $request->status[$i] == '0'){
                }else{
                     //insert detail
                    DB::insert('INSERT INTO `requestacc_sales_line` (`createdBy`,`createdOn`,`updatedBy`,`updatedOn`,`bonus`,`code`,`diskon`,`price_per_satuan_id`,`qty`,`qty_pending_send`,`retur`,`item_stock_id`,`requestacc_sales_header_id`,`sales_per_satuan_id`,`komisi`,`deletedOn`,`status`)
                    (SELECT '.Auth::user()->id.',NOW(),`updatedBy`,NOW(),`bonus`,`code`,`diskon`,`price_per_satuan_id`,'.$request->qty[$i].',`qty_pending_send`,`retur`,`item_stock_id`,'.$lastid.',`sales_per_satuan_id`,`komisi`,`deletedOn`,'.$request->status[$i].' FROM `request_sales_line` WHERE id='.$request->line[$i].');');

                    //update totalan
                    DB::table('request_sales_line')->where('id',$request->line[$i])->update(array(
                        'qty_acc'=>$request->qtyawal2[$i]+$request->qty[$i],
                        'status'=>$request->status[$i],
                    ));
                }
               
            }
        } else {
            return redirect()->back()->with("error", "Data error");
        }
        DB::commit();
        if ($request->ajax()) {
            return response()->json("success", 200);
        }
        return redirect()->back()->with("success", "Data disimpan");
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
        $d["customers"] = Konsumen::all();
        $d["data2"] = RequestSalesHeader::join('customer','request_sales_header.customer_id','=','customer.id')->join('users','request_sales_header.createdBy','=','users.id')->where('request_sales_header.id','=',$id)->get();
        $d["data"] = RequestSalesHeader::find($id);
        $d["detail"] = RequestSalesLine::join('request_sales_header','request_sales_line.request_sales_header_id','=','request_sales_header.id')
        ->join('item_stock','request_sales_line.item_stock_id','=','item_stock.id')
        ->join('satuan','item_stock.satuan_id','=','satuan.id')->where('request_sales_header.id','=',$id)
        ->select(DB::raw('date_format(request_sales_header.createdOn,"%d/%m/%Y %H:%i:%s") as tgl, request_sales_line.*, satuan.name as satuan_name, item_stock.name as barang, item_stock.purchase_price, item_stock.sell_price'))
        ->where("request_sales_header.id","=",$id)->get();

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
        // $line = RequestSalesLine::where("request_sales_header_id",$id)->get();
        // foreach($line as $item){
        //     $item->status = 1;
        //     $item->save();
        // }
        // return redirect()->back()->with("message", "Data diubah");
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
        // $header = RequestSalesHeader::find($id);
        // $line = RequestSalesLine::where("request_sales_header_id",$id)->get();
        // foreach($line as $item){
        //     $item->deletedOn = date('Y-m-d H:i:s');
        //     $item->save();
        // }
        // $header->delete();
        // return redirect()->back()->with("message", "Data dihapus");
    }
}

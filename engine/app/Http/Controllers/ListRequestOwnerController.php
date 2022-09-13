<?php

namespace App\Http\Controllers;

use App\Models\RequestSalesHeader;
use App\Models\RequestSalesLine;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
        $line = RequestSalesLine::where("request_sales_header_id",$id)->get();
        foreach($line as $item){
            $item->status = 1;
            $item->save();
        }
        return redirect()->back()->with("message", "Data diubah");
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
        $header = RequestSalesHeader::find($id);
        $line = RequestSalesLine::where("request_sales_header_id",$id)->get();
        foreach($line as $item){
            $item->deletedOn = date('Y-m-d H:i:s');
            $item->save();
        }
        $header->delete();
        return redirect()->back()->with("message", "Data dihapus");
    }
}

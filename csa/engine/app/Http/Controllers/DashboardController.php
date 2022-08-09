<?php

namespace App\Http\Controllers;

use App\Models\SalesOrderHeader;
use App\Models\Konsumen;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $menuheader;

    public function __construct()
    {
        $this->menuheader = "Dashboard";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d["menu_header"] =  $this->menuheader;
        $d["menu_title"] = "Overview";
        // $d["data"] = SalesOrderHeader::where("createdOn",Carbon::today());
        $d["sales"] = SalesOrderHeader::where("createdOn",">=",Carbon::today())->paginate(10);
        $d["konsumen"] = Konsumen::all();
        foreach($d["konsumen"] as $item){
            $item["hutang"] = $item->salesorder->sum('payment_remain');
        }
        $d["konsumen"] = $d["konsumen"]->sortBy([
            ['hutang', 'desc'],
        ]);
        return view("index", $d);
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

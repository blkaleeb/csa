<?php

namespace App\Http\Controllers;

use App\Models\CustomerReturnLine;
use App\Models\Konsumen;
use App\Models\SalesOrderHeader;
use App\Models\SalesOrderLine;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KonsumenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $view = "konsumen.";
    protected $menu_header = "Konsumen";
    protected $menu_title = "Konsumen";

    public function index(Request $request)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["konsumens"] = Konsumen::query();
        if ($request->ajax()) {
            if ($request->has("q")) {
                $d["konsumens"] = $d["konsumens"]->where("name","like","%".$request->q."%")->get();
            }else{
                $d["konsumens"]=$d["konsumens"]->get();
            }
            return response()->json($d["konsumens"]);
        } else {
            $d["konsumens"] = $d["konsumens"]->get();
            return view($this->view . "index", $d);
        }
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
        $d["sales"] = User::wherein("role_id", [1, 3])->get();

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
        $konsumen = new Konsumen();
        foreach ($request->except("_token") as $key => $value) {
            $konsumen->$key = $value;
        }
        $konsumen->save();
        return redirect()->back()->with("message", "Data disimpan");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $date_start = Carbon::parse("2021-09-08");
        $date_end = Carbon::now();
        if ($request->date_start != "") {
            $date_start = Carbon::parse($request->date_start);
        }
        if ($request->date_end != "") {
            $date_end = Carbon::parse($request->date_end);
        }
        
        $konsumen = Konsumen::find($id);
        if ($konsumen == null){
            return 'Konsumen is Void';
        }
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $konsumen->name;
        
        //Data Penjualan
        $d["penjualan"] = SalesOrderLine::with("header")->whereHas("header", function ($query) use ($id) {
            $query->where('customer_id', $id);
        })->whereBetween("createdOn", [$date_start, $date_end])->get();

        //Data Retur Penjualan
        $d["retur_penjualan"] = CustomerReturnLine::with("header")->whereHas("header", function ($query) use ($id) {
            $query->where('customer_id', $id);
        })->whereBetween("createdOn", [$date_start, $date_end])->get();

        $d["date_start"] = $date_start;
        $d["date_end"] = $date_end;
        $d["konsumen"] = $konsumen;
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
        $d["konsumen"] = Konsumen::find($id);
        $d["sales"] = User::wherein("role_id", [1, 3])->get();
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
        $konsumen = Konsumen::find($id);
        foreach ($request->except("_token", "_method") as $key => $value) {
            $konsumen->$key = $value;
        }
        $konsumen->save();
        return redirect()->back()->with("message", "Data diubah");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $konsumen = Konsumen::find($id);
        $konsumen->delete();
        return redirect()->back()->with("message", "Data dihapus");
    }
}

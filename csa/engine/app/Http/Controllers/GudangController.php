<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $view = "gudang.";
    protected $menu_header = "Gudang";
    protected $menu_title = "Gudang";

    public function index(Request $request)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["warehouses"] = Warehouse::all();

        if ($request->ajax()) {
            return response()->json($d["warehouses"]);
        } else {
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
        $request->validate([
            'warehouse_code' => 'required',
            'warehouse_name' => 'required',
            'warehouse_address' => 'required',
            'warehouse_phone_no' => 'required',
        ]);
        Warehouse::create($request->except("_token", "_method"));
        return redirect()->back()->with("message", "Data disimpan");
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
        $d["warehouse"] = Warehouse::find($id);
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
        $request->validate([
            'warehouse_code' => 'required',
            'warehouse_name' => 'required',
            'warehouse_address' => 'required',
            'warehouse_phone_no' => 'required',
        ]);

        $warehouse = Warehouse::find($id);
        foreach ($request->except("_token", "_method") as $key => $value) {
            $warehouse->$key = $value;
        }
        $warehouse->save();
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
        $warehouse = Warehouse::find($id);
        $warehouse->delete();
        return redirect()->back()->with("message", "Data dihapus");
    }
}

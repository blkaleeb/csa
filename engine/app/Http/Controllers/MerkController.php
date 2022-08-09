<?php

namespace App\Http\Controllers;

use App\Models\Merk;
use App\Models\Stok;
use App\Models\User;
use Illuminate\Http\Request;

class MerkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $view = "merk.";
    protected $menu_header = "Merk";
    protected $menu_title = "Merk";

    public function index(Request $request)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["categories"] = Merk::all();
        if ($request->ajax()) {
            if ($request->has("q")) {
                return response()->json(Merk::where("name","like", "%".$request->q."%")->get());
            } else {
                $brand_id = Stok::select("brand_id")->get()->toArray();
                return response()->json(Merk::whereIn("id", $brand_id)->get());
            }
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
        $merk = new merk();
        foreach ($request->except("_token") as $key => $value) {
            $merk->$key = $value;
        }
        $merk->save();
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
        $d["merk"] = Merk::find($id);
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
        $merk = Merk::find($id);
        foreach ($request->except("_token", "_method") as $key => $value) {
            $merk->$key = $value;
        }
        $merk->save();
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
        $merk = Merk::find($id);
        $merk->delete();
        return redirect()->back()->with("message", "Data dihapus");
    }
}

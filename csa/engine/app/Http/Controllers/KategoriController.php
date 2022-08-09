<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Stok;
use App\Models\User;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $view = "kategori.";
    protected $menu_header = "Kategori";
    protected $menu_title = "Kategori";

    public function index(Request $request)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["categories"] = Kategori::all();

        if ($request->ajax()) {
            if ($request->has("q")) {
                return response()->json(Kategori::where("name", "like", "%" . $request->q . "%")->get());
            } else {
                $category_id = Stok::select("category_id")->get()->toArray();
                return response()->json(Kategori::whereIn("id", $category_id)->get());
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
        $Kategori = new Kategori();
        foreach ($request->except("_token") as $key => $value) {
            $Kategori->$key = $value;
        }
        $Kategori->save();
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
        $d["kategori"] = Kategori::find($id);
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
        $Kategori = Kategori::find($id);
        foreach ($request->except("_token", "_method") as $key => $value) {
            $Kategori->$key = $value;
        }
        $Kategori->save();
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
        $Kategori = Kategori::find($id);
        $Kategori->delete();
        return redirect()->back()->with("message", "Data dihapus");
    }
}

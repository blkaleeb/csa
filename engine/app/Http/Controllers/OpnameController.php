<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Merk;
use App\Models\StockOpname;
use App\Models\Stok;
use Illuminate\Http\Request;

class OpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $view = "opname.";
    protected $menu_header = "Barang";
    protected $menu_title = "Opname";

    public function index()
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["stocks"] = Stok::all();
        $d["category"] = Kategori::wherein("id", $d["stocks"]->pluck("category_id")->toArray())->get();
        $d["brand"] = Merk::whereIn("id", $d["stocks"]->pluck("brand_id")->toArray())->get();
        return view($this->view . "index", $d);
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
        foreach ($request->item as $item_stock_id => $qty) {
            $itemstock = Stok::find($item_stock_id);
            $opname = new StockOpname();
            $opname->in_out = 2;
            $opname->notes = "Stock Opname";
            $opname->qty = $qty;
            $opname->qty_before = $itemstock->qty;
            $opname->item_stock_id = $item_stock_id;
            $opname->save();

            $itemstock->qty = $qty;
            $itemstock->save();
        }
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
        return redirect()->back()->with("message", "Data dihapus");
    }
}

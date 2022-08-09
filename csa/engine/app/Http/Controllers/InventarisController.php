<?php

namespace App\Http\Controllers;

use App\Models\Inventoris;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    protected $view = "inventoris.";
    protected $menu_header = "Inventoris";
    protected $menu_title = "Inventoris";

    public function index(Request $request)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["data"] = Inventoris::all();
        if ($request->ajax()) {
            return response()->json($d["data"]);
        } else {
            return view($this->view . "index", $d);
        }
    }

    public function create()
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["is_edit"] = false;

        return view($this->view . "form", $d);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        Inventoris::create($request->except("_token", "_method"));
        return redirect()->back()->with("message", "Data disimpan");
    }

    public function show($id)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        return view($this->view . "show", $d);
    }

    public function edit($id)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["is_edit"] = true;
        $d["inventoris"] = Inventoris::find($id);
        return view($this->view . "form", $d);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $warehouse = Inventoris::find($id);
        foreach ($request->except("_token", "_method") as $key => $value) {
            $warehouse->$key = $value;
        }
        $warehouse->save();
        return redirect()->back()->with("message", "Data diubah");
    }

    public function destroy($id)
    {
        $warehouse = Inventoris::find($id);
        $warehouse->delete();
        return redirect()->back()->with("message", "Data dihapus");
    }
}

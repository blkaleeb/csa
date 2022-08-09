<?php

namespace App\Http\Controllers;

use App\Models\Inventoris;
use App\Models\KategoriPengeluaran;
use App\Models\Pengeluaran;
use App\Models\Stok;
use App\Models\User;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    protected $view = "pengeluaran.";
    protected $menu_header = "Pengeluaran";
    protected $menu_title = "Pengeluaran";

    public function index()
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["data"] = Pengeluaran::orderBy("createdOn","desc")->get();
        return view($this->view . "index", $d);
    }

    public function create()
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["is_edit"] = false;
        $d["kategori_pengeluaran"] = KategoriPengeluaran::get();
        $d["karyawan"] = User::select('id','displayName')->get();
        $d["inventoris"] = Inventoris::select('id','name')->get();
        $d["stock"] = Stok::select('id','name')->get();

        return view($this->view . "form", $d);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_bukti' => 'required',
            'tanggal' => 'required',
            'jumlah' => 'required',
            'kategori_pengeluaran_id' => 'required',
        ]);
        Pengeluaran::create($request->except("_token","_method"));
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
        $d["kategori_pengeluaran"] = KategoriPengeluaran::get();
        $d["karyawan"] = User::select('id','displayName')->get();
        $d["inventoris"] = Inventoris::select('id','name')->get();
        $d["stock"] = Stok::select('id','name')->get();
        $d["pengeluaran"] = Pengeluaran::find($id);
        return view($this->view . "form", $d);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'no_bukti' => 'required',
            'tanggal' => 'required',
            'jumlah' => 'required',
            'kategori_pengeluaran_id' => 'required',
        ]);
        
        $warehouse = Pengeluaran::find($id);
        foreach($request->except("_token","_method") as $key=>$value){
            $warehouse->$key = $value;
        }
        $warehouse->save();
        return redirect()->back()->with("message", "Data diubah");
    }

    public function destroy($id)
    {
        $warehouse = Pengeluaran::find($id);
        $warehouse->delete();
        return redirect()->back()->with("message", "Data dihapus");
    }
}

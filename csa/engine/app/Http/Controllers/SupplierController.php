<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\PoHeader;
use App\Models\PurchaseInvoiceLine;
use App\Models\Supplier;
use App\Models\SupplierReturnLine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $view = "supplier.";
    protected $menu_header = "Supplier";
    protected $menu_title = "Supplier";

    public function index(Request $request)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;

        if ($request->ajax()) {
            if ($request->has("q")) {
                $d["suppliers"] = Supplier::where("supplier_name","like", "%" . $request->q . "%")->get();
            } else {
                $d["suppliers"] = Supplier::all();
            }
            return response()->json($d["suppliers"]);
        } else {
            $d["suppliers"] = Supplier::all();
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
        $d["cities"] = City::get();

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
            'supplier_name' => 'required',
            'phone_num' => 'required',
            'supplier_address' => 'required',
            'suppliercode' => 'required',
            'city_id' => 'required',
            'email' => 'required',
        ]);
        Supplier::create($request->except("_token", "_method"));
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

        $supplier = Supplier::find($id);
        if ($supplier == null){
            return 'Supplier is Void';
        }
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $supplier->supplier_name;

        //Data Pembelian
        $poheader_id = PoHeader::where('supplier_id', $id)->get()->pluck('id')->toArray();
        $d["pembelian"] = PurchaseInvoiceLine::with("header","poline")->whereHas('header', function ($query) use ($poheader_id) {
            $query->whereIn('poheader_id', $poheader_id);
        })->whereBetween("createdOn", [$date_start, $date_end])->get();

        //Data Retur Pembelian
        $d["retur_pembelian"] = SupplierReturnLine::with("header")->whereHas('header', function ($query) use ($poheader_id) {
            $query->whereIn('po_id', $poheader_id);
        })->whereBetween("createdOn", [$date_start, $date_end])->get();

        $d["date_start"] = $date_start;
        $d["date_end"] = $date_end;
        $d["supplier"] = $supplier;
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
        $d["supplier"] = Supplier::find($id);
        $d["cities"] = City::get();
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
        $supplier = Supplier::find($id);
        foreach ($request->except("_token", "_method") as $key => $value) {
            $supplier->$key = $value;
        }
        $supplier->save();
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
        $supplier = Supplier::find($id);
        $supplier->delete();
        return redirect()->back()->with("message", "Data dihapus");
    }
}

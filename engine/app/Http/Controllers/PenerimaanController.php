<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Konsumen;
use App\Models\PoHeader;
use App\Models\PoLine;
use App\Models\PurchaseInvoiceHeader;
use App\Models\PurchaseInvoiceLine;
use App\Models\SalesOrderHeader;
use App\Models\SalesOrderLine;
use App\Models\Stok;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
class PenerimaanController extends Controller
{
    protected $view = "penerimaan.";
    protected $menu_header = "Penerimaan";
    protected $menu_title = "Penerimaan";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["data"] = PurchaseInvoiceHeader::orderBy("createdOn", "desc")->get();
        return view($this->view . "index", $d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["is_edit"] = false;
        $d["suppliers"] = Supplier::all();
        $d["data"] = PoHeader::find($id);
        $d["warehouses"] = Warehouse::all();
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
        $counter = Counter::where("table_name", "=", "pi")->first();
        $internal_invoice_no = date("m") . "." . date("Y") . "." . $counter->sequence_next_value;

        DB::beginTransaction();
        $header = new PurchaseInvoiceHeader();
        $header->due_date = Carbon::parse($request->invoice_date)->format('Y-m-d');;
        $header->internal_invoice_no = $internal_invoice_no;
        $header->supplier_invoice_no = $request->supplier_invoice_no;
        $header->invoice_date = Carbon::parse($request->invoice_date)->format('Y-m-d');;
        $header->paid_total = 0;
        $header->invoice_total = $request->invoice_total;
        $header->ppn = $request->ppn;
        $header->purchase_invoice_status = "C";
        $header->poheader_id = $request->po_header_id;
        $header->save();
        $counter->sequence_next_value += 1;
        $counter->save();

        $poheader = PoHeader::find($request->po_header_id);
        $poheader->po_total += $request->invoice_total;
        $poheader->rencana_diterima = Carbon::parse($request->rencana_diterima)->format('Y-m-d');
        $poheader->save();
        //preprare line
        if ($request->line != null) {
            $count_line = count($request->line);
            for ($i = 0; $i < $count_line; $i++) {
                $poline = PoLine::find($request->line[$i]);
                $item_stock = Stok::find($poline->item_stock_id);

                $line = new PurchaseInvoiceLine();
                $line->price_per_satuan_id = $request->harga_beli[$i] + ($request->harga_beli[$i] * $request->ppn/100);
                $line->sell_per_satuan_id = $request->harga_jual[$i];
                $line->qty = $request->qty[$i];
                $line->po_header_id = $request->po_header_id;
                $line->po_line_id = $request->line[$i];
                $line->purchase_invoice_header_id = $header->id;
                $line->satuan_id = $item_stock->satuan_id;
                $line->warehouse_id = $request->gudang[$i];
                $line->save();

                if ($request->harga_beli[$i] != 0) {
                    $purchaseprice = $item_stock->purchase_price;
                    $avgpp = $purchaseprice + ($request->harga_beli[$i] + ($request->harga_beli[$i] * $request->ppn/100));
                    $item_stock->purchase_price = $avgpp / 2;
                }
                $item_stock->qty += $request->qty[$i];
                $item_stock->save();

                //
                $poline->qty_get = $request->qty[$i];
                if ($poline->qty_get >= $poline->qty) {
                    $poline->penerimaan = 1;
                }
                $poline->price_per_satuan_id = $request->harga_beli[$i];
                $poline->sell_per_satuan_id = $request->harga_jual[$i];
                $poline->save();
            }
        } else {
            return redirect()->back()->with("error", "Data error");
        }
        DB::commit();
        if ($request->ajax()) {
            return response()->json("success", 200);
        }
        return redirect()->back()->with("success", "Data diubah");
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
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["is_edit"] = true;
        $d["data"] = PurchaseInvoiceHeader::with("poheader.supplier")->find($id);
        $d["warehouses"] = Warehouse::all();
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
        DB::beginTransaction();
        $header = PurchaseInvoiceHeader::find($id);

        //rollback
        $poheader = PoHeader::find($header->poheader_id);
        $poheader->po_total -= $header->invoice_total;
        $poheader->save();

        $header->due_date = Carbon::parse($request->invoice_date)->format('Y-m-d');;
        $header->supplier_invoice_no = $request->supplier_invoice_no;
        $header->invoice_date = Carbon::parse($request->invoice_date)->format('Y-m-d');;
        $header->paid_total = 0;
        $header->invoice_total = $request->invoice_total;
        $header->ppn = $request->ppn;
        $header->purchase_invoice_status = "C";
        $header->poheader_id = $request->po_header_id;
        $header->save();

        $poheader = PoHeader::find($request->po_header_id);
        $poheader->po_total += $request->invoice_total;
        $poheader->save();
        //preprare line
        $count_line = count($request->line);
        for ($i = 0; $i < $count_line; $i++) {

            $line = PurchaseInvoiceLine::find($request->line[$i]);

            $poline = PoLine::find($line->po_line_id);
            $item_stock = Stok::find($poline->item_stock_id);

            //rollback itemstock
            $item_stock->qty -= $line->qty;
            $item_stock->save();

            // //rollback harga
            // $hargarb = $item_stock->purchase_price * 2 - $line->price_per_satuan_id;
            // $item_stock->purchase_price = $hargarb;
            // $item_stock->save();

            $line->price_per_satuan_id = $request->harga_beli[$i] + ($request->harga_beli[$i] * $request->ppn/100);
            $line->qty = $request->qty[$i];
            $line->sell_per_satuan_id = $request->harga_jual[$i];
            $line->po_header_id = $poline->po_header_id;
            $line->po_line_id = $poline->id;
            $line->purchase_invoice_header_id = $header->id;
            $line->satuan_id = $item_stock->satuan_id;
            $line->warehouse_id = $request->gudang[$i];
            $line->save();


            // if ($request->harga_beli[$i] != 0) {
            //     $purchaseprice = $item_stock->purchase_price;
            //     $avgpp = $purchaseprice + ($request->harga_beli[$i] + ($request->harga_beli[$i] * $request->ppn/100));
            //     $item_stock->purchase_price = $avgpp / 2;
            // }

            $item_stock->qty += $request->qty[$i];
            $item_stock->save();

            //
            $poline->price_per_satuan_id = $request->harga_beli[$i];
            $poline->sell_per_satuan_id = $request->harga_jual[$i];
            $poline->qty_get = $request->qty[$i];
            if ($poline->qty_get >= $poline->qty) {
                $poline->penerimaan = 1;
            }
            $poline->save();
        }
        DB::commit();
        if ($request->ajax()) {
            return response()->json("success", 200);
        }
        return redirect()->back()->with("success", "Data diubah");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        $header = PurchaseInvoiceHeader::find($id);

        //rollback
        $poheader = PoHeader::find($header->poheader_id);
        $poheader->po_total -= $header->invoice_total;
        $poheader->save();

        foreach ($header->line as $line) {
            $line = PurchaseInvoiceLine::find($line->id);

            $poline = PoLine::find($line->po_line_id);
            $item_stock = Stok::find($poline->item_stock_id);
            //rollback itemstock
            $item_stock->qty -= $line->qty;
            $item_stock->save();

            //rollback harga
            $hargarb = $item_stock->purchase_price * 2 - $line->price_per_satuan_id;
            $item_stock->purchase_price = $hargarb;
            $item_stock->save();

            $poline->qty_get -= $line->qty;
            $poline->save();

            $line->delete();
        }
        $header->delete();
        DB::commit();


        return redirect()->back()->with("success", "Void berhasil");
    }
}

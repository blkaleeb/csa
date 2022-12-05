<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\CustomerReturnHeader;
use App\Models\Konsumen;
use App\Models\Komisi;
use App\Models\Pengeluaran;
use App\Models\PoHeader;
use App\Models\PoLine;
use App\Models\PurchaseInvoiceHeader;
use App\Models\PurchaseInvoiceLine;
use App\Models\SalesOrderHeader;
use App\Models\SalesInvoicePayment;
use App\Models\SalesOrderLine;
use App\Models\StockOpname;
use App\Models\Stok;
use App\Models\Supplier;
use App\Models\SupplierReturnHeader;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected $view = "report.";
    protected $menu_header = "Report";
    protected $menu_title = "Report";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        return view($this->view . "index", $d);
    }


    public function overview(Request $request)
    {
        if ($request->date_start == null) {
            $request->date_start = "2021-09-08";
        } else {
        }
        if ($request->date_end == null) {
            $request->date_end = date("Y-m-d");
        } else {
        }
        $d["start"] = $request->date_start;
        $d["end"] = $request->date_end;
        $totalsales = SalesOrderHeader::wherebetween("createdOn", [$request->date_start . " 00:00:00", $request->date_end . " 23:59:59"])->sum('total_sales');
        // dd(DB::getQueryLog());

        $modal = SalesOrderHeader::select(DB::raw("sum(modal) as `modal`"))->wherebetween("createdOn", [$request->date_start . " 00:00:00", $request->date_end . " 23:59:59"])->first();
        $d["omzet"] = ($totalsales);
        $pengeluaran = $this->getlaporanpengeluaran($request, true);
        $total_pengeluaran = $pengeluaran->sum("jumlah");
        $d["pengeluaran"] = $total_pengeluaran;

        $d["profit"] = $d["omzet"] - $d["pengeluaran"] - $modal->modal;
        $d["hutang"] = PurchaseInvoiceHeader::select(DB::raw("sum(invoice_total-paid_total) as `hutang`"))->whereBetween('purchase_invoice_header.invoice_date', [$request->date_start, $request->date_end])->first();
        $d["piutang"] = SalesOrderHeader::select(DB::raw("sum(payment_remain) as `piutang`"))->where("payment_remain", ">", 0)->wherebetween("createdOn", [$request->date_start . " 00:00:00", $request->date_end . " 23:59:59"])->first();
        $d["stock"] = Stok::select(DB::raw("sum(qty*purchase_price) as `stock`"))->first();
        return view("report.overview", $d);
    }


    public function getlaporanstockopname(Request $request)
    {
        if ($request->date_start == null) {
            $request->date_start = "2021-09-08";
        }
        if ($request->date_end == null) {
            $request->date_end = date("Y-m-d");
        }
        $query = StockOpname::with("stock")->wherebetween("createdOn", [$request->date_start . " 00:00:00", $request->date_end . " 23:59:59"]);
        if ($request->has("item_stock")) {
            $query = $query->where("item_stock_id", $request->item_stock);
        }
        $query = $query->get();
        return view('report.stockopname', ["data" => $query, "start" => $request->date_start, "end" => $request->date_end]);
    }

    public function getlaporanbarang(Request $request)
    {
        $data = Stok::with("satuan");

        if ($request->has("kategori")) {
            $data = $data->where("category_id", "=", $request->kategori);
        }
        if ($request->has("merk")) {
            $data = $data->where("brand_id", "=", $request->merk);
        }
        $data = $data->orderby("name", "asc")->get();
        return view(
            'report.daftarharga',
            [
                "data" => $data,
            ]
        );
    }

    public function getlaporanstock(Request $request)
    {
        if ($request->date_start == null || $request->date_end == null) {
            $request->date_start = "2021-09-08";
            $request->date_end = date("Y-m-d");
        }
        $query = Stok::with("category", "brand", "satuan", "warehouse")->select("*");
        $name = "Laporan/Stok/Laporan Persediaan Barang per-" . Date("YmdHis") . ".pdf";


        if ($request->has("supplier")) {
            $po_header = PoHeader::select("id")->where("supplier_id", $request->supplier)->get()->pluck("id")->toArray();
            $item_stock_poline = PoLine::whereIn("po_header_id", $po_header)->get()->pluck("item_stock_id")->toArray();
            $query = $query->whereIn("id", $item_stock_poline);
        } elseif ($request->has("customer")) {
            $sales_header = SalesOrderHeader::select("id")->where("customer_id", $request->customer)->get()->pluck("id")->toArray();
            $item_stock_salesline = SalesOrderLine::whereIn("sales_order_header_id", $sales_header)->get()->pluck("item_stock_id")->toArray();
            $query = $query->whereIn("id", $item_stock_salesline);
        }
        if ($request->has("kategori")) {
            $query = $query->where("category_id", $request->kategori);
        } elseif ($request->has("merk")) {
            $query = $query->where("brand_id", $request->merk);
        }
        if ($request->has("warehouse_id")) {
            $query = $query->where("warehouse_id", $request->warehouse_id);
        }

        $data = $query->get();

        if ($request->has("warehouse_id")) {

            $gudangnames = Warehouse::find($request->warehouse_id);
            return view('report.item_stock-ringkasan', ["data" => $data, "hargajual" => $request->hargajual, "hargabeli" => $request->hargabeli, "gudang" => $gudangnames->warehouse_name, "hargajualtotal" => $request->hargajualtotal, "hargabelitotal" => $request->hargabelitotal]);
        }
        if ($request->has("supplier")) {
            $suppliernames = Supplier::find($request->supplier);
            return view('report.item_stock-ringkasan', ["data" => $data, "suppliernames" => $suppliernames->supplier_name, "hargajual" => $request->hargajual, "hargabeli" => $request->hargabeli, "hargajualtotal" => $request->hargajualtotal, "hargabelitotal" => $request->hargabelitotal]);
        }
        if ($request->has("customer")) {

            $customernames = Konsumen::find($request->customer);
            return view('report.item_stock-ringkasan', ["data" => $data, "customernames" => $customernames->name, "hargajual" => $request->hargajual, "hargabeli" => $request->hargabeli, "hargajualtotal" => $request->hargajualtotal, "hargabelitotal" => $request->hargabelitotal]);
        }


        return view('report.item_stock-ringkasan', ["data" => $data, "category" => $data[0]->catname, "merk" => $data[0]->brandname, "hargajual" => $request->hargajual, "hargabeli" => $request->hargabeli, "hargajualtotal" => $request->hargajualtotal, "hargabelitotal" => $request->hargabelitotal]);
    }

    public function getlaporanpenjualan(Request $request)
    {
        if ($request->date_start == null) {
            $request->date_start = "2021-09-08";
        }
        if ($request->date_end == null) {
            $request->date_end = date("Y-m-d");
        }
        $total_pengeluaran = null;
        $request->date_end = date("Y-m-d", strtotime($request->date_end . "+1 days"));
        $d = [];
        $data = SalesOrderHeader::query();
        if (auth()->user()->role_id == 1) {
            if ($request->has("sales")) {
                $arr["pegawai"] = $request->sales;
                $request->merge($arr);
            }
            $pengeluaran = $this->getlaporanpengeluaran($request, true);
            $total_pengeluaran = $pengeluaran->sum("jumlah");

        }
        $data = $data->whereBetween("createdOn", [$request->date_start, $request->date_end]);
        if ($request->has("detail")) {
            $data = $data->where("customer_id", $request->customer)->get();
            $d["customer"] = Konsumen::find($request->customer);
            $d["data"] = SalesOrderLine::with("header")->wherein("sales_order_header_id", $data->pluck("id")->toArray())->get();
            dd($d);
            return view('report.penjualan-detail', ["total_pengeluaran" => $total_pengeluaran, "data" => $data, "start" => $request->date_start, "end" => $request->date_end, "lihatkomisi" => $request->lihatkomisi, "lihatsemuakomisi" => $request->semuakomisi], $d);
        } else {
            if ($request->has("item_stock")) {
                $linestock = SalesOrderLine::select("sales_order_header_id")->where("item_stock_id", $request->item_stock)->pluck("sales_order_header_id")->toArray();
                $data = $data->whereIn("id", $linestock);
                $d["stock"] = Stok::find($request->item_stock);
            }
            if ($request->has("customer")) {
                $data = $data->where("customer_id", $request->customer);
                $d["customer"] = Konsumen::find($request->customer);
            }
            if ($request->has("sales")) {
                $sales_id = Komisi::where("user_id",$request->sales)->get()->pluck("sales_order_header_id")->toArray();
                $customer = Konsumen::where("sales_id", $request->sales)->get()->pluck("id")->toArray();
                $data = $data->whereIn("id", $sales_id);
                $d["sales"] = User::find($request->sales);

            }
            $data = $data->get();
            return view('report.penjualan-ringkasan', ["total_pengeluaran" => $total_pengeluaran, "data" => $data, "start" => $request->date_start, "end" => $request->date_end, "lihatkomisi" => $request->lihatkomisi, "lihatsemuakomisi" => $request->semuakomisi], $d);
        }
    }

    public function getlaporanreturpembelian(Request $request)
    {
        if ($request->date_start == null) {
            $request->date_start = "2021-09-08";
        }
        if ($request->date_end == null) {
            $request->date_end = date("Y-m-d");
        }
        $d["start"] = $request->date_start;
        $d["end"] = $request->date_end;
        if ($request->has("item_stock") && ($request->has("supplier"))) {

            $item_id = ItemStock::find($request->item_stock);
            $d['item'] = $item_id->inventoryproperty->item->item_name;
            $supplier = Supplier::find($request->supplier);
            $d['supplier'] = $supplier->supplier_name;

            $d['data'] = SupplierReturnHeader::with("supplier", "detail.stock.item")
                ->select("supplier_return_header.*")
                ->leftjoin('supplier_return_line', 'supplier_return_line.supplier_return_header_id', '=', 'supplier_return_header.id')
                ->leftjoin('item_stock', 'item_stock.id', '=', 'supplier_return_line.item_stock_id')
                // ->leftjoin('item_stock', 'item_stock.item_id', '=', 'inventory_property.id')
                ->leftjoin('inventory_property', 'inventory_property.id', '=', 'item_stock.item_id')
                ->leftjoin('item', 'item.id', '=', 'inventory_property.item_id')
                ->whereBetween('supplier_return_header.createdOn', [$request->date_start, $request->date_end])
                ->where("supplier_return_line.item_id", "=", $item_id->item_id)
                ->where("supplier_return_header.supplier_code", "=", $request->supplier)
                ->get();
        } else if ($request->has("supplier")) {
            $supplier = Supplier::find($request->supplier);
            $d['supplier'] = $supplier->supplier_name;

            $d['data'] = SupplierReturnHeader::with("supplier", "detail.stock.item")
                ->whereBetween('supplier_return_header.createdOn', [$request->date_start, $request->date_end])
                ->where("supplier_code", "=", $request->supplier)
                ->get();
        } else if ($request->has("item_stock")) {
            $item_id = ItemStock::find($request->item_stock);
            $d['item'] = $item_id->inventoryproperty->item->item_name;

            $d['data'] = SupplierReturnHeader::with("supplier", "detail.stock.item")
                ->select("supplier_return_header.*")
                ->leftjoin('supplier_return_line', 'supplier_return_line.supplier_return_header_id', '=', 'supplier_return_header.id')
                ->leftjoin('item_stock', 'item_stock.id', '=', 'supplier_return_line.item_stock_id')
                // ->leftjoin('item_stock', 'item_stock.item_id', '=', 'inventory_property.id')
                ->leftjoin('inventory_property', 'inventory_property.id', '=', 'item_stock.item_id')
                ->leftjoin('item', 'item.id', '=', 'inventory_property.item_id')
                ->whereBetween('supplier_return_header.createdOn', [$request->date_start, $request->date_end])
                ->where("supplier_return_line.item_id", "=", $item_id->item_id)
                // ->where("supplier_return_header.supplier_code","=",$request->supplier)
                ->get();
        } else {
            $d['data'] = SupplierReturnHeader::with("supplier", "line.stock")
                ->whereBetween('supplier_return_header.createdOn', [$request->date_start, $request->date_end])
                ->get();
        }
        $d["detail"] = true;

        return view('report.return-pembelian', $d);
    }

    public function getlaporanreturpenjualan(Request $request)
    {
        if ($request->date_start == null) {
            $request->date_start = "2021-09-08";
        }
        if ($request->date_end == null) {
            $request->date_end = date("Y-m-d");
        }
        $d["start"] = $request->date_start;
        $d["end"] = $request->date_end;
        if ($request->has("item_stock") && ($request->has("customer"))) {
            $item_id = Stok::find($request->item_stock);
            $d['item'] = $item_id->inventoryproperty->item->item_name;

            $d['data'] = CustomerReturnHeader::with("customer", "line.stock")
                ->select("customer_return_header.*")
                ->leftjoin('customer_return_line', 'customer_return_line.customer_return_header_id', '=', 'customer_return_header.id')
                ->leftjoin('item_stock', 'item_stock.id', '=', 'customer_return_line.item_stock_id')
                // ->leftjoin('item_stock', 'item_stock.item_id', '=', 'inventory_property.id')
                ->leftjoin('inventory_property', 'inventory_property.id', '=', 'item_stock.item_id')
                ->leftjoin('item', 'item.id', '=', 'inventory_property.item_id')
                ->whereBetween('customer_return_header.createdOn', [$request->date_start, $request->date_end])
                ->where("customer_return_line.item_stock_id", "=", $request->item_stock)
                ->where("customer_return_header.customer_id", "=", $request->customer)
                ->get();
        } else if ($request->has("customer")) {
            $customer = Konsumen::find($request->customer);
            $d['customer'] = $customer->name;
            $d['data'] = CustomerReturnHeader::with("customer", "line.stock")
                ->whereBetween('customer_return_header.createdOn', [$request->date_start, $request->date_end])
                ->where("customer_id", "=", $request->customer)
                ->get();
        } else if ($request->has("item_stock")) {
            $item_id = Stok::find($request->item_stock);
            $d['item'] = $item_id->inventoryproperty->item->item_name;

            $d['data'] = CustomerReturnHeader::with("customer", "line.stock")
                ->select("customer_return_header.*")
                ->leftjoin('customer_return_line', 'customer_return_line.customer_return_header_id', '=', 'customer_return_header.id')
                ->leftjoin('item_stock', 'item_stock.id', '=', 'customer_return_line.item_stock_id')
                // ->leftjoin('item_stock', 'item_stock.item_id', '=', 'inventory_property.id')
                ->leftjoin('inventory_property', 'inventory_property.id', '=', 'item_stock.item_id')
                ->leftjoin('item', 'item.id', '=', 'inventory_property.item_id')
                ->whereBetween('customer_return_header.createdOn', [$request->date_start, $request->date_end])
                ->where("customer_return_line.item_stock_id", "=", $request->item_stock)
                // ->where("customer_return_header.customer_id","=",$request->customer)
                ->get();
        } else {
            $d['data'] = CustomerReturnHeader::with("customer", "line.stock")
                ->whereBetween('customer_return_header.createdOn', [$request->date_start, $request->date_end])
                ->get();
        }
        $d["detail"] = true;
        return view('report.return-penjualan', $d);
    }

    public function getlaporankomisi(Request $request)
    {
        $this->calckomisi($request->semuakomisi);
        if ($request->date_start == null) {
            $request->date_start = "2021-09-08";
        }
        if ($request->date_end == null) {
            $request->date_end = date("Y-m-d");
        }
        $request->date_start = $request->date_start." 00:00:00";
        $request->date_end = $request->date_end." 23:59:59";
        $tglkirim = date("Y-m-d", strtotime($request->date_end))." 23:59:59";
        if ($request->has("customer")) {
            // $customer = Konsumen::find($request->customer);

            // $data = Komisi::with("order")
            //         ->where("user_id",$customer->sales_id)
            //         ->whereBetween('sales_order_header.createdOn', [$request->date_start, $tglkirim]);

            $data = SalesOrderHeader::with("customer.sales", "line")
                ->select("sales_order_header.*")
                ->where("customer_id", "=", $request->customer)
                ->whereBetween('sales_order_header.createdOn', [$request->date_start, $tglkirim])
                ->orderby("sales_order_header.createdOn", "asc")
                ->get();

            $name = "Laporan/Penjualan/Laporan Penjualan per-" . Date("YmdHis") . ".pdf";

            $customer = Konsumen::find($request->customer);
            // $response = $this->client->get($this->base_uri("customer/" . $request->customer));
            // $hasilcustomer = json_decode($response->getBody()->getContents());
            // $datacustomer = $hasilcustomer->data;
            return view('report.komisi', ["customer" => $customer, "data" => $data, "start" => $request->date_start, "end" => $request->date_end, "lihatkomisi" => $request->lihatkomisi, "lihatsemuakomisi" => $request->semuakomisi]);
        } else if ($request->has("sales")) {
          
          
            $sales_id = Komisi::where("user_id",$request->sales)->get()->pluck("sales_order_header_id")->toArray();
            $data = SalesOrderHeader::with("customer.sales", "line")
                ->select("sales_order_header.*")
                ->join("customer", "customer.id", "=", "sales_order_header.customer_id")
                ->join("users", "users.id", "=", "customer.sales_id")
                ->wherein("sales_order_header.id",$sales_id)
                ->whereBetween('sales_order_header.createdOn', [$request->date_start, $tglkirim])
                ->orderby("sales_order_header.createdOn", "asc")
                ->get();
          
            // $data = SalesOrderHeader::with("customer.sales", "line")
            // ->select("sales_order_header.*")
            // ->join("customer", "customer.id", "=", "sales_order_header.customer_id")
            // ->join("users", "users.id", "=", "customer.sales_id")
            // ->where("users.id", "=", $request->sales)
            // ->whereBetween('sales_order_header.createdOn', [$request->date_start, $tglkirim])
            // ->orderby("sales_order_header.createdOn", "asc")
            // ->get();
          
                $name = "Laporan/Penjualan/Laporan Penjualan per-" . Date("YmdHis") . ".pdf";
            // $response = $this->client->get($this->base_uri("users/" . $request->sales));
            // $hasilsales = json_decode($response->getBody()->getContents());
            // $datasales = $hasilsales->data;
            $sales = User::find($request->sales);
            return view('report.komisi', ["sales" => $sales, "data" => $data, "start" => $request->date_start, "end" => $request->date_end, "lihatkomisi" => $request->lihatkomisi, "lihatsemuakomisi" => $request->semuakomisi]);
        } else {
            // $response = $this->client->get($this->base_uri("sales_order_header/" . $request->date_start . "/" . $tglkirim));
            // $hasil = json_decode($response->getBody()->getContents());
            // $data = $hasil->data;
            $data = SalesOrderHeader::with("customer.sales", "line")
                ->select("sales_order_header.*")
                ->whereBetween('sales_order_header.createdOn', [$request->date_start, $tglkirim])
                ->orderby("sales_order_header.createdOn", "asc")
                ->get();
            $name = "Laporan/Penjualan/Laporan Penjualan per-" . Date("YmdHis") . ".pdf";
            return view('report.komisi', ["data" => $data, "start" => $request->date_start, "end" => $request->date_end, "lihatkomisi" => $request->lihatkomisi, "lihatsemuakomisi" => $request->semuakomisi]);
        }

        return redirect()->back();
    }

    public function getlaporanpembelian(Request $request)
    {
        if ($request->date_start == null) {
            $request->date_start = "2021-09-08";
        }
        if ($request->date_end == null) {
            $request->date_end = date("Y-m-d");
        }
        $start = $request->date_start;
        $end = $request->date_end;
        $purchase_invoice = PurchaseInvoiceHeader::query();


        if ($request->has("detail_supplier")) {
            $d["detail"] = true;
            $po_line = PoLine::select("id");
            if ($request->has("supplier")) {
                $po_header = PoHeader::select("id")->where("supplier_id", $request->supplier)->get()->toArray();
                $po_line = $po_line->whereIn("po_header_id", $po_header);
            }
            if ($request->has("item_stock")) {
                $po_line = $po_line->where("item_stock_id", $request->item_stock);
            }
            $po_line = $po_line->get()->toArray();
            $purchase_invoice_line = PurchaseInvoiceLine::whereIn("po_line_id", $po_line)->where('createdOn', '>=', Carbon::parse($start)->toDateString())->where('createdOn', '<=', Carbon::parse($end)->addDays(1))->get();
            $d["data"] = $purchase_invoice_line;
        } else {
            if ($request->has("supplier")) {
                $po_header = PoHeader::select("id")->where("supplier_id", $request->supplier)->get()->toArray();
                $purchase_invoice = $purchase_invoice->whereIn("poheader_id", $po_header);
            }
            if ($request->has("item_stock")) {
                $po_line = PoLine::select("id")->where("item_stock_id", $request->item_stock_id)->get()->toArray();
                $purchase_invoice_line = PurchaseInvoiceLine::whereIn("po_line_id", $po_line)->get();
                $purchase_invoice = $purchase_invoice->whereIn("id", $purchase_invoice_line);
            }

            $purchase_invoice = $purchase_invoice->where('createdOn', '>=', Carbon::parse($start)->toDateString())->where('createdOn', '<=', Carbon::parse($end)->addDays(1));

            $d["data"] = $purchase_invoice->get();
        }
        $d["start"] = $start;
        $d["end"] = $end;
        if ($request->has("detail_supplier")) {
            return view('report.perbarangsupplier', $d);
        } else {
            return view('report.pembelian-ringkasan', $d);
        }
    }

    public function getlaporanpiutang(Request $request)
    {
        /**
         *
         */
        // $customer = Konsumen::all();
        // $cek = [];
        // $payment_remain = 0;
        // foreach($customer as $cust){
        // 	$query = SalesOrderHeader::select(DB::raw("sum(payment_remain) as payment_remain"))->where("customer_id",$cust->id)->first();
        // 	$cek[$cust->name]=$query->payment_remain;
        // 	$payment_remain +=$query->payment_remain;
        // }
        // dd($payment_remain);
        /**
         *
         */
        if ($request->date_start == null) {
            $request->date_start = "2021-09-08";
        }
        if ($request->date_end == null) {
            $request->date_end = date("Y-m-d");
        }

        $filter = "";

        $date_end = date("Y-m-d", strtotime($request->date_end . "+1 days"));
        if ($request->has("detail")) {
            // dd($request->all());
            $data = SalesOrderHeader::select("*", "customer.name as customername", "sales_order_header.createdOn as tanggalorder", "sales_order_header.total_sales", "sales_order_header.retur", "sales_order_header.intnomorsales as intnomorsales", "sales_order_header.createdOn as tanggalnya")
                // ->leftjoin('sales_order_line', 'sales_order_line.sales_order_header_id', '=', 'sales_order_header.id')
                ->leftjoin('customer', 'customer.id', '=', 'sales_order_header.customer_id')
                // ->leftjoin('item_stock', 'item_stock.id', '=', 'sales_order_line.item_stock_id')
                // ->leftjoin('inventory_property', 'inventory_property.id', '=', 'item_stock.item_id')
                // ->leftjoin('item', 'item.id', '=', 'inventory_property.item_id')
                ->whereBetween('sales_order_header.createdOn', [$request->date_start, $date_end])
                // ->orderby("sales_order_header.createdOn", "asc");
                ->orderby("sales_order_header.customer_id", "asc");
            if ($request->has("customer")) {
                $data = $data->where("sales_order_header.customer_id", "=", $request->customer);
            }
            if ($request->has("sales")) {
                $data = $data->where("customer.sales_id", "=", $request->sales);
            }
            if ($request->has("lunas")) {
                $data = $data->where("payment_remain", "=", 0)->where("payment_remain", "=", 0)->get();
                $filter = "Lunas";
            } else {
                $data = $data->where("payment_remain", ">", 0)->where("payment_remain", ">", 0)->get();
                $filter = "Belum Lunas";
            }
            $name = "Laporan/Piutang/Laporan Piutang per-" . Date("YmdHis") . ".pdf";
            // dd($data);
            return view('report.piutang', [
                "data" => $data,
                "tanggalstart" => $request->date_start,
                "tanggalend" => $request->date_end,
                "filter" => $filter,
            ]);
        } elseif ($request->has("customer")) {

            $data = SalesOrderHeader::select(DB::raw('`customer`.`name`,SUM(`total_sales`) as "totalsales", SUM(`payment_remain`) as "payment_remain",SUM(`total_paid`) as "totalpaid",sum(`retur`) as "retur",sum(`diskon`) as "diskon"'))
                ->join('customer', 'customer.id', '=', 'sales_order_header.customer_id')
                ->whereBetween('sales_order_header.createdOn', [$request->date_start, $date_end])
                ->where('customer.id', '=', $request->customer);

            if ($request->has("lunas")) {
                $data = $data->where("payment_remain", "=", 0)->havingRaw('SUM(payment_remain) = ?', [0]);
                $filter = "Lunas";
            } else if ($request->has("belumlunas")) {
                $data = $data->where("payment_remain", ">", 0)->havingRaw('SUM(payment_remain) > ?', [0]);
                $filter = "Belum Lunas";
            }
            $data = $data->groupBy('customer.name')
                ->orderby("sales_order_header.createdOn", "asc")
                ->get();

            $name = "Laporan/Piutang/Laporan Piutang per-" . Date("YmdHis") . ".pdf";

            return view('report.piutang', [
                "data" => $data,
                "tanggalstart" => $request->date_start,
                "tanggalend" => $request->date_end,
                "filter" => $filter,

            ]);
        } else if ($request->has("sales")) {
            $data = SalesOrderHeader::select(DB::raw('`customer`.`name`,SUM(`total_sales`) as "totalsales", SUM(`payment_remain`) as "payment_remain",SUM(`total_paid`) as "totalpaid",sum(`retur`) as "retur",sum(`diskon`) as "diskon"'))
                ->join('customer', 'customer.id', '=', 'sales_order_header.customer_id')
                ->join('users', 'users.id', '=', 'customer.sales_id')
                ->whereBetween('sales_order_header.createdOn', [$request->date_start, $date_end])
                ->where('customer.sales_id', '=', $request->sales);

            if ($request->has("lunas")) {
                $data = $data->where("payment_remain", "=", 0)->havingRaw('SUM(payment_remain) = ?', [0]);
                $filter = "Lunas";
            } else if ($request->has("belumlunas")) {
                $data = $data->where("payment_remain", ">", 0)->havingRaw('SUM(payment_remain) > ?', [0]);
                $filter = "Belum Lunas";
            }
            $data = $data->groupBy('customer.name')
                ->orderby("sales_order_header.createdOn", "asc")
                ->get();

            $user = User::where("id", "=", $request->sales)->first();

            $name = "Laporan/Piutang/Laporan Piutang per-" . Date("YmdHis") . ".pdf";
            return view('report.piutang', [
                "data" => $data,
                "user" => $user,
                "tanggalstart" => $request->date_start,
                "tanggalend" => $request->date_end,
                "filter" => $filter,

            ]);
        } else {
            DB::enableQueryLog();

            $data = SalesOrderHeader::select(DB::raw('`customer`.`name`,SUM(`total_sales`) as "totalsales", SUM(`payment_remain`) as "payment_remain",SUM(`total_paid`) as "totalpaid",sum(`retur`) as "retur",sum(`diskon`) as "diskon"'))
                ->join('customer', 'customer.id', '=', 'sales_order_header.customer_id')
                ->whereBetween('sales_order_header.createdOn', [$request->date_start, $date_end]);
            if ($request->has("lunas")) {
                $data = $data->where("payment_remain", "=", 0)->havingRaw('payment_remain = ?', [0]);
                $filter = "Lunas";
            } else if ($request->has("belumlunas")) {
                $data = $data->where("payment_remain", ">", 0)->havingRaw('payment_remain > ?', [0]);
                $filter = "Belum Lunas";
            } else {
                $data = $data->where("payment_remain", ">=", 0)->havingRaw('payment_remain >= ?', [0]);
            }


            $data = $data->groupBy('customer.name')
                // ->orderby("sales_order_header.createdOn", "asc")
                ->orderby("sales_order_header.customer_id", "asc")
                ->get();
            $name = "Laporan/Piutang/Laporan Piutang per-" . Date("YmdHis") . ".pdf";

            return view('report.piutang', [
                "data" => $data,
                "tanggalstart" => $request->date_start,
                "tanggalend" => $request->date_end,
                "filter" => $filter,
            ]);
        }
        return redirect()->back();
    }

    public function getlaporanpengeluaran(Request $request, $return = null)
    {
        if ($request->date_start == null) {
            $request->date_start = "2021-09-08";
        }
        if ($request->date_end == null) {
            $request->date_end = date("Y-m-d");
        }
        $date_start = $request->date_start . " 00:00:00";
        $date_end = $request->date_end . " 23:59:59";
        // $date_end = date("Y-m-d", strtotime($request->date_end . "+1 days"));
        $query = null;
        if ($request->has("pegawai")) {
            $query = Pengeluaran::select("pengeluaran.*", "users.displayName", "inventoris.name", "kategori_pengeluaran.name as ktname")->join("kategori_pengeluaran", "kategori_pengeluaran.id", "=", "pengeluaran.kategori_pengeluaran_id")->leftjoin("inventoris", "inventoris.id", "=", "pengeluaran.inventaris_id")->leftjoin("users", "users.id", "=", "pengeluaran.user_id")->where("user_id", "=", $request->pegawai)->whereBetween('pengeluaran.tanggal', [$date_start, $date_end]);
        } else if ($request->has("inventaris")) {
            $query = Pengeluaran::select("pengeluaran.*", "users.displayName", "inventoris.name", "kategori_pengeluaran.name as ktname")->join("kategori_pengeluaran", "kategori_pengeluaran.id", "=", "pengeluaran.kategori_pengeluaran_id")->leftjoin("inventoris", "inventoris.id", "=", "pengeluaran.inventaris_id")->leftjoin("users", "users.id", "=", "pengeluaran.user_id")->where("inventaris_id", "=", $request->inventaris)->whereBetween('pengeluaran.tanggal', [$date_start, $date_end]);
        } else {
            // $query = Pengeluaran::whereBetween('createdOn', [$date_start, $date_end]);
            $query = Pengeluaran::select("pengeluaran.*", "users.displayName", "inventoris.name", "kategori_pengeluaran.name as ktname")->join("kategori_pengeluaran", "kategori_pengeluaran.id", "=", "pengeluaran.kategori_pengeluaran_id")->leftjoin("inventoris", "inventoris.id", "=", "pengeluaran.inventaris_id")->leftjoin("users", "users.id", "=", "pengeluaran.user_id")->whereBetween('pengeluaran.tanggal', [$date_start, $date_end]);
        }

        if ($request->has("kategori_pengeluaran_id")) {
            $query = $query->where("kategori_pengeluaran_id", "=", $request->kategori_pengeluaran_id);
        }
        $query = $query->orderby("pengeluaran.tanggal", "asc")->get();
        $name = "Laporan/Pengeluaran/Laporan Pengeluaran per-" . Date("YmdHis") . ".pdf";
        if ($return == null) {
            return view('report.pengeluaran-ringkasan', [
                "data" => $query,
                "tanggalstart" => $request->date_start,
                "tanggalend" => $request->date_end,
            ]);
        } else {
            return $query;
        }
    }

    public function getlaporanhutang(Request $request)
    {
        if ($request->date_start == null) {
            $request->date_start = "2021-09-08";
        }
        if ($request->date_end == null) {
            $request->date_end = date("Y-m-d");
        }
        $data = [];
        $view = 'report.hutang';
        if ($request->has("detail")) {
            $data = PurchaseInvoiceHeader::select(DB::raw("purchase_invoice_header.createdOn,purchase_invoice_header.internal_invoice_no,purchase_invoice_header.supplier_invoice_no, supplier.supplier_name, purchase_invoice_header.retur,  purchase_invoice_header.paid_total,  purchase_invoice_header.invoice_total"))
                ->join('po_header', 'po_header.id', '=', 'purchase_invoice_header.poheader_id')
                ->join('supplier', 'supplier.id', '=', 'po_header.supplier_id')
                ->whereBetween('purchase_invoice_header.invoice_date', [$request->date_start, $request->date_end])
                ->orderby("purchase_invoice_header.createdOn", "asc");
            $view = 'report.hutang-detail';
        } else {
            $data = PurchaseInvoiceHeader::select(DB::raw('`supplier`.`supplier_name`,`supplier`.`createdOn`, SUM(`invoice_total`) as "invoice_total",SUM(`paid_total`) as "paid_total"'))
                ->join('po_header', 'po_header.id', '=', 'purchase_invoice_header.poheader_id')
                ->join('supplier', 'supplier.id', '=', 'po_header.supplier_id')
                ->whereBetween('purchase_invoice_header.invoice_date', [$request->date_start, $request->date_end])
                ->groupBy('supplier.supplier_name')
                ->orderby("purchase_invoice_header.invoice_date", "asc");
        }
        $filter = "";
        if ($request->has("lunas")) {
            $filter = "lunas";
        } elseif ($request->has("belumlunas")) {
            $filter = "belumlunas";
        }

        if ($request->has("supplier")) {
            $data = $data->where('supplier.id', '=', $request->supplier)
                // ->groupBy('supplier.supplier_name')
                ->orderby("purchase_invoice_header.invoice_date", "asc")
                ->get();
        } else {
            $data = $data->get();
        }

        return view($view, [
            "data" => $data,
            "tanggalstart" => $request->date_start,
            "tanggalend" => $request->date_end,
            "filter" => $filter
        ]);
    }

    public function getringkasankomisi(Request $request)
    {
        if ($request->date_start == null) {
            $request->date_start = "2021-09-08";
        }
        if ($request->date_end == null) {
            $request->date_end = date("Y-m-d");
        }
        $response = $this->client->get($this->base_uri("komisi/" . $request->sales . "/" . $request->date_start . "/" . $request->date_end));
        $response2 = $this->client->get($this->base_uri("users/" . $request->sales));

        $hasil = json_decode($response->getBody()->getContents());
        $hasil2 = json_decode($response2->getBody()->getContents());
        $datad = $hasil2->data;
        $data = $hasil->data;
        $name = "Laporan/Komisi/Laporan Komisi per-" . Date("YmdHis") . ".pdf";
        return view('report.komisi', ["data" => $hasil->data, "datas" => $datad]);
        // $printcmd = "java -classpath pdfbox-app-1.7.1.jar org.apache.pdfbox.PrintPDF -silentPrint -printerName hp1 $name";
        // exec($printcmd);

        return redirect()->back();
    }

    public function getringkasankas(Request $request)
    {
        if ($request->date_start == null) {
            $request->date_start = "2021-09-08";
        }
        if ($request->date_end == null) {
            $request->date_end = date("Y-m-d");
        }
        $response = $this->client->get($this->base_uri("bank_cash_transaction/" . $request->sales . "/" . $request->date_start . "/" . $request->date_end));
        $hasil = json_decode($response->getBody()->getContents());
        $data = $hasil->data;
        $name = "Laporan/Kas/Laporan Kas per-" . Date("YmdHis") . ".pdf";
        return view('report.kas', ["data" => $hasil->data]);
        // $printcmd = "java -classpath pdfbox-app-1.7.1.jar org.apache.pdfbox.PrintPDF -silentPrint -printerName hp1 $name";
        return redirect()->back();
    }

    public function getringkasanlabarugi(Request $request)
    {
        if ($request->date_start == null) {
            $request->date_start = "2021-09-08";
        }
        if ($request->date_end == null) {
            $request->date_end = date("Y-m-d");
        }
    }

    public function getringkasanneraca(Request $request)
    {
        if ($request->date_start == null) {
            $request->date_start = "2021-09-08";
        }
        if ($request->date_end == null) {
            $request->date_end = date("Y-m-d");
        }
        $response1 = $this->client->get($this->base_uri("bank_cash"));
        $hasilbank = json_decode($response1->getBody()->getContents());

        $response2 = $this->client->get($this->base_uri("item_stock"));
        $hasilitemstock = json_decode($response2->getBody()->getContents());

        $response3 = $this->client->get($this->base_uri("sales_order_header/" . $request->date_start . "/" . $request->date_end));
        $hasilpiutang = json_decode($response3->getBody()->getContents());

        $response4 = $this->client->get($this->base_uri("po_header_hutang/" . date('m-01-Y') . "/" . date('m-t-Y')));
        $hasilutang = json_decode($response4->getBody()->getContents());

        return view('report.neraca', [
            "stock" => $hasilitemstock->data,
            "bank" => $hasilbank->data,
            "piutang" => $hasilpiutang->data,
            "utang" => $hasilutang->data,
        ], [], [
            'format' => 'letter',
        ]);
        // return $pdf->stream('Laporan Neraca per ' . date("Y-m-d H:i:s") . '.pdf', array('Attachment' => 0));
    }

    public function getdetailstock(Request $request)
    {
        $response = $this->client->get($this->base_uri("stock_mutation/item_stock/" . $request->date_start . "/" . $request->date_end));
        $hasil = json_decode($response->getBody()->getContents());
        return view(
            'report.item_stock-detail',
            [
                "data" => $hasil->data,
            ]
        );
        // return $pdf->stream('Laporan Persediaan Barang Lengkap per ' . date("Y-m-d H:i:s") . '.pdf', array('Attachment' => 0));
    }

    public function getdetailpenjualan(Request $request)
    {
        $response = $this->client->get($this->base_uri("sales_invoice_header/" . $request->date_start . "/" . $request->date_end));
        $hasil = json_decode($response->getBody()->getContents());
        $line = (object) array();
        for ($i = 0; $i < count($hasil->data); $i++) {
            $response = $this->client->get($this->base_uri("sales_invoice_line/detail/" . $hasil->data[$i]->id));
            $hasil2 = json_decode($response->getBody()->getContents());
            $id = "id" . $hasil->data[$i]->id;
            $line->$id = $hasil2;
        }
        return view('report.penjualan-detail', [
            "data" => $hasil->data,
            "line" => $line,
        ]);
        // return $pdf->stream('Laporan Penjualan per '.date("Y-m-d H:i:s").'.pdf');
    }

    public function getdetailpembelian(Request $request)
    {
        $response = $this->client->get($this->base_uri("purchase_invoice_header/" . $request->date_start . "/" . $request->date_end));
        $hasil = json_decode($response->getBody()->getContents());
        $line = (object) array();
        for ($i = 0; $i < count($hasil->data); $i++) {
            $response = $this->client->get($this->base_uri("purchase_invoice_line/detail/" . $hasil->data[$i]->id));
            $hasil2 = json_decode($response->getBody()->getContents());
            $id = "id" . $hasil->data[$i]->id;
            $line->$id = $hasil2;
        }
        return view('report.pembelian-detail', [
            "data" => $hasil->data,
            "line" => $line,
        ]);
        // return $pdf->stream('Laporan Pembelian per '.date("Y-m-d H:i:s").'.pdf');
    }

    public function getdetailkomisi(Request $request)
    {
        $response = $this->client->get($this->base_uri("komisi/" . $request->sales . "/" . $request->date_start . "/" . $request->date_end));
        $hasil = json_decode($response->getBody()->getContents());
        return view('report.komisi', [
            "data" => $hasil->data,
        ], [], [
            'format' => 'letter',
        ]);
        // return $pdf->stream('Laporan Komisi per ' . date("Y-m-d H:i:s") . '.pdf', array('Attachment' => 0));
    }

    public function getdetailkas(Request $request)
    {
    }

    public function getlaporanlabarugi(Request $request)
    {
        if ($request->date_start == null) {
            $request->date_start = "2021-09-08";
        }
        if ($request->date_end == null) {
            $request->date_end = date("Y-m-d");
        }
        $datapenjualan = DB::table('sales_order_header')
            ->select(DB::raw('SUM(`total_sales`) as "totalsales", SUM(`payment_remain`) as "paymentremain",SUM(`total_paid`) as "totalpaid",sum(`retur`) as "retur",sum(`modal`) as "modal"'))
            ->join('customer', 'customer.id', '=', 'sales_order_header.customer_id')
            ->whereBetween('sales_order_header.createdOn', [$request->date_start, $request->date_end])
            ->first();

        $datapembelian = DB::table('purchase_invoice_header')
            ->select(DB::raw('SUM(`invoice_total`) as "invoice_total",SUM(`paid_total`) as "paid_total",sum(`retur`) as "retur"'))
            ->join('po_header', 'po_header.id', '=', 'purchase_invoice_header.poheader_id')
            ->join('supplier', 'supplier.id', '=', 'po_header.supplier_id')
            ->whereBetween('purchase_invoice_header.createdOn', [$request->date_start, $request->date_end])
            ->first();

        $datapengeluaran = DB::table('pengeluaran')
            ->select(DB::raw('SUM(`jumlah`) as "totalpengeluaran"'))
            ->whereBetween('pengeluaran.tanggal', [$request->date_start, $request->date_end])
            ->first();

        return view('report.labarugi', [
            "datapenjualan" => $datapenjualan,
            "datapembelian" => $datapembelian,
            "datapengeluaran" => $datapengeluaran,
            "tanggalstart" => $request->date_start,
            "tanggalend" => $request->date_end,
        ]);
    }

    public function getdetailneraca(Request $request)
    {
    }

    public function printfakturpenjualan($id)
    {
        $this->sendPrintPenjualan($id);
    }

    public function printfakturreturpenjualan($id)
    {
        $this->sendPrintReturPenjualan($id);
    }

    public function printpo($id)
    {
        $response = $this->getData("po-header/$id/detail");
        $name = "Laporan/Faktur/PO/" . 'PO-' . $id . " " . date("Y-m-d h-i-s") . '.pdf';
        $pdf = PDF::loadView('report.faktur-po', [
            "data" => $response,
            "line" => $response->line,
        ])->setPaper('a4')->setOrientation('landscape')->save($name);
        $printcmd = "java -classpath pdfbox-app-1.7.1.jar org.apache.pdfbox.PrintPDF -silentPrint -printerName lx $name";
        exec($printcmd);
    }

    public function printfakturreturpembelian($id)
    {
        $this->sendPrintReturPembelian($id);
    }

    public function printtojava($id)
    {
        $response = $this->client->get($this->base_uri("print/faktur-" . $id));
    }

    public function getPDF(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $pdf = PDF::loadView('pdf.result', ['student' => $student]);
        return $pdf->stream('result.pdf', array('Attachment' => 0));
    }

    public function calcModalSalesHeader($start = 1, $end = null)
    {
        dd("nonaktif");
        echo "<pre>";
        $data = SalesOrderHeader::where("id", ">=", $start);
        if ($end != null) {
            $data = $data->where("id", "<=", $end);
        }
        $data = $data->get();
        foreach ($data as $item) {
            echo $item->intnomorsales . PHP_EOL;
            $modal = 0;
            foreach ($item->line as $line) {
                $modal += $line->sales_per_satuan_id * $line->qty;
                echo $line->sales_per_satuan_id . "*" . $line->qty . PHP_EOL;
            }
            $item->modal = $modal;
            echo $modal . PHP_EOL;
            $item->save();
            echo '<hr>';
        }
    }

    public function calcTotalPaid($start = 1, $end = null)
    {
        echo "<pre>";
        $data = SalesOrderHeader::where("id", ">=", $start);
        if ($end != null) {
            $data = $data->where("id", "<=", $end);
        }
        $data = $data->get();
        foreach ($data as $item) {
            echo $item->intnomorsales . PHP_EOL;

            $getPayment = SalesInvoicePayment::where("sales_order_header_id", $item->id)->get();
            $totalPayment = 0;
            foreach ($getPayment as $payment) {
                $totalPayment += intval($payment->payment_value);
            }
            echo "<br>";
            echo ($item->total_sales - $totalPayment);
            echo "<br>";
            echo "<br>";
            echo "<hr>";
            
            $item->total_paid = $totalPayment;
            $item->payment_remain = $item->total_sales - $totalPayment;
            $item->save();
        }
    }

    public function calcTotalSales($start = 1, $end = null)
    {
        echo "<pre>";
        $data = SalesOrderHeader::where("id", ">=", $start);
        if ($end != null) {
            $data = $data->where("id", "<=", $end);
        }
        $data = $data->get();
        foreach ($data as $item) {
            echo $item->intnomorsales . PHP_EOL;

            $getLine = SalesOrderLine::where("sales_order_header_id", $item->id)->get();
            $totalSales = 0;
            foreach ($getLine as $line) {
                $totalSales += intval($line->price_per_satuan_id) * intval($line->qty);
            }
            $item->total_sales = $totalSales;
            $item->payment_remain = $totalSales-($item->retur + $item->total_paid);
            $item->save();
        }
    }

    function calckomisi($flagAll)
    {
        // echo "<pre>";
        $headers = SalesOrderHeader::get();
        foreach ($headers as $head) {
            $komisiheader = 0;
            $lines = SalesOrderLine::where("sales_order_header_id", $head->id)->get();
            $komisipersen = 0.8;
            foreach ($lines as $line) {
                // echo $line->stock->id . "<br>";
                if ($line->stock->brand) {
                    // echo $line->stock->brand->name . "<br>";
                    $komisipersen = $line->stock->brand->komisi;
                }
            }
            $head->komisi = $head->total_sales * ($komisipersen / 100);
            $head->save();
            try {
                $komisi = Komisi::where("sales_order_header_id", $head->id)->firstorfail();
                $komisi->amount = $head->komisi;
                $komisi->current_amount = 0;
                $komisi->customer_id = $head->customer->id;
                $komisi->sales_order_header_id = $head->id;
                $komisi->percentage = $komisipersen;
                $komisi->save();
            } catch (\Throwable $th) {
                $komisi = new Komisi();
                $komisi->amount = $head->komisi;
                $komisi->current_amount = 0;
                $komisi->sales_order_header_id = $head->id;
                $komisi->user_id = $head->customer->sales->id;
                $komisi->percentage = $komisipersen;
                $komisi->save();
            }


            # code...
            // echo "Done for sales header id " . $head->id . "<br>";
        }
        // echo "all done..";
    }
}

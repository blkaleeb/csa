<?php

namespace App\Http\Controllers;

use App\Models\CustomerReturnHeader;
use App\Models\CustomerReturnLine;
use App\Models\Kategori;
use App\Models\Merk;
use App\Models\PoHeader;
use App\Models\PoLine;
use App\Models\PurchaseInvoiceLine;
use App\Models\PurchaseOrderLine;
use App\Models\SalesOrderHeader;
use App\Models\SalesOrderLine;
use App\Models\Satuan;
use App\Models\StockOpname;
use App\Models\Stok;
use App\Models\SupplierReturnLine;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StokController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $view = "stok.";
    protected $menu_header = "Stok";
    protected $menu_title = "Stok";

    public function index(Request $request)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["stocks"] = Stok::with("gudang")->get();
        if ($request->ajax()) {
            if ($request->has("q")) {
                $d["stocks"] = Stok::with("gudang")->where("name", "like", "%" . $request->q . "%")->get();
            }
            return response()->json($d["stocks"]);
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
        $d["merks"] = Merk::all();
        $d["kategoris"] = Kategori::all();
        $d["satuan"] = Satuan::all();
        $d["gudangs"] = Warehouse::all();
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
        $stok = new Stok();
        foreach ($request->except("_token") as $key => $value) {
            $stok->$key = $value;
        }
        $stok->save();
        return redirect()->back()->with("message", "Data disimpan");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $date_start = Carbon::parse("2021-09-08");
        $date_end = Carbon::now();
        if ($request->date_start != "") {
            $date_start = Carbon::parse($request->date_start);
        }
        if ($request->date_end != "") {
            $date_end = Carbon::parse($request->date_end);
        }
        $stock = Stok::find($id);
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $stock->name;
        //Data Opname
        $d["opname"] = StockOpname::where("item_stock_id", $id)->get();
        //Data Penjualan
        $d["penjualan"] = SalesOrderLine::with("header")->where("item_stock_id", $id)->whereBetween("createdOn", [$date_start, $date_end])->get();
        //Data Pembelian
        $d["pembelian"] = PurchaseInvoiceLine::with(

            "header",
            "poline"

        )->whereHas('poline', function (Builder $query) use ($id) {
            $query->where('item_stock_id', '=', $id);
        })->whereBetween("createdOn", [$date_start, $date_end])->get();
        //Data Retur Penjualan
        $d["retur_penjualan"] = CustomerReturnLine::with("header")->where("item_stock_id", $id)->whereBetween("createdOn", [$date_start, $date_end])->get();
        //Data Retur Pembelian
        $d["retur_pembelian"] = SupplierReturnLine::with("header")->where("item_stock_id", $id)->whereBetween("createdOn", [$date_start, $date_end])->get();

        $d["date_start"] = $date_start;
        $d["date_end"] = $date_end;
        $d["stock"] = $stock;
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
        $d["stok"] = Stok::find($id);
        $d["merks"] = Merk::all();
        $d["kategoris"] = Kategori::all();
        $d["satuan"] = Satuan::all();
        $d["gudangs"] = Warehouse::all();

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
        $stok = Stok::find($id);
        foreach ($request->except("_token", "_method") as $key => $value) {
            $stok->$key = $value;
        }
        $stok->save();
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
        $stok = Stok::find($id);
        $stok->delete();
        return redirect()->back()->with("message", "Data dihapus");
    }

    public function getStockSupplierReturn($id)
    {
        $po = PoLine::with("stock")->where("po_header_id", $id)->get();
        return response()->json($po);
    }

    public function getStockCustomerReturn($id)
    {
        $po = SalesOrderLine::with("stock")->where("sales_order_header_id", $id)->get();
        return response()->json($po);
    }

    public function getStockByCategory(Request $request)
    {
        $data = Stok::where("category_id", $request->id)->get();
        return response()->json($data);
    }
    public function getStockByBrand(Request $request)
    {
        $data = Stok::where("brand_id", $request->id)->get();
        return response()->json($data);
    }
    public function getStockByName(Request $request)
    {
        $data = Stok::where("name", "like", "%" . $request->name . "%")->get();
        return response()->json($data);
    }

    //update
    function updateModalPrice($id, $check = 1)
    {
        //1x
        // $data1x = [7278, 7326, 7342, 7358, 7401, 7447, 7463, 7479, 7530, 6889, 7279, 7311, 7343, 7359, 7402, 7433, 7464, 7480, 7496, 7515, 7531, 6890, 7344, 7403, 7419, 7449, 7465, 7481, 7497, 7516, 7532, 6891, 7296, 7313, 7345, 7364, 7404, 7450, 7466, 7482, 7498, 7533, 6892, 7297, 7346, 7405, 7435, 7451, 7467, 7483, 7499, 7517, 7534, 7266, 7347, 7362, 7137, 7407, 7468, 7504, 7519, 7535, 7284, 7332, 7406, 7423, 7469, 7485, 7500, 7520, 7536, 7268, 7285, 7349, 7408, 7424, 7470, 7487, 7505, 7521, 7269, 7286, 7350, 7366, 7382, 7391, 7409, 7425, 7471, 7486, 7506, 7522, 7270, 7351, 7395, 7410, 7426, 7472, 7523, 7539, 7271, 7368, 7396, 7411, 7473, 7524, 7540, 7272, 7369, 7412, 7458, 7474, 7490, 7507, 7525, 7273, 7322, 7385, 7413, 7459, 7475, 7491, 7508, 7526, 7274, 7306, 7339, 7414, 7430, 7444, 7460, 7476, 7492, 7527, 7275, 7308, 7415, 7431, 7445, 7461, 7477, 7512, 7277, 7307, 7309, 7432, 7446, 7462, 7478, 7513, 7529];
        // $data2x = [7131, 3563, 7495, 7327, 7133, 7418, 7448, 7328, 7390, 7281, 7329, 7377, 7389, 7420, 7518, 7282, 7330, 7392, 7421, 7283, 7331, 7299, 7348, 7333, 7381, 7537, 7334, 7455, 7538, 7302, 7367, 7383, 7456, 7509, 442, 7352, 7510, 7288, 7337, 7353, 7305, 7338, 7354, 7323, 7355, 441, 7511, 7340, 7372, 7388, 7325, 7357, 7373, 7130, 7400, 7494];
        // $data3x = [7310, 7417, 7514, 7280, 7295, 7376, 443, 7379, 7422, 7454, 7484, 7267, 7363, 7380, 7452, 7300, 7427, 7489, 7397, 7289, 7370, 7398, 7429, 7290, 7371, 7324, 7528, 7341, 7416];
        // $data4x = [7374, 7375, 7434, 7314, 7378, 7437, 7393, 7318, 7439, 440, 7440, 7488, 7303, 7457, 7386, 7428, 7356, 7493];
        // $data5x = [7294, 7312, 7361, 7315, 7436, 4849, 7317, 7438, 7453, 7301, 7320, 7336, 7384, 7441, 7304, 7443, 7387, 7291];
        // $data6x = [7365, 7319, 7321, 7442];
        // $data7x = [7399];
        // $data8x = [7360];
        // $data9x = [7293, 7298, 7316];
        // $data10x = [7335];
        if ($id == 0) {
            $allStock = Stok::all();
        } else {
            $allStock = Stok::where("id", $id)->get();
        }

        foreach ($allStock as $item) {
            $poline = PoLine::where("item_stock_id", $item->id)->get();

            if ($poline) {
                $purchase_price = 0;
                for ($i = 0; $i < count($poline); $i++) {
                    // if ($purchase_line != null) {
                    if ($poline[$i]->purchaselinev2 != null) {
                        //getPPN
                        $ppn = $poline[$i]->purchaselinev2->header->ppn;
                        echo $poline[$i]->purchaselinev2->header->id;
                        if ($poline[$i]->purchaselinev2->price_per_satuan_id != 0) {
                            if ($i == 0) {
                                $purchase_price = $poline[$i]->purchaselinev2->price_per_satuan_id + ($poline[$i]->purchaselinev2->price_per_satuan_id * $ppn/100);
                            } else {
                                $purchase_price = ($purchase_price + $poline[$i]->purchaselinev2->price_per_satuan_id + ($poline[$i]->purchaselinev2->price_per_satuan_id * $ppn/100)) / 2;
                            }
                        }
                        echo ("Pembelian Ke-".$i+1 ." " . $poline[$i]->purchaselinev2->price_per_satuan_id . "<br>");
                    }
                    // if ($purchase_price == 0) {
                    //     $purchase_price += $poline[$i]->price_per_satuan_id;
                    // } else {
                    //     $purchase_price = ($poline[$i]->price_per_satuan_id + $purchase_price) / 2;
                    // }
                    // }
                }
                $item->purchase_price = $purchase_price;
                if ($check == 1) {
                    echo "Harga Akhir ".($purchase_price);
                } else {
                    $item->save();
                }
            }
        }
    }
}

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataVoidController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HutangController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KategoriPengeluaranController;
use App\Http\Controllers\KonsumenController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenjualanLineController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\PoController;
use App\Http\Controllers\PoLineController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturPembelianController;
use App\Http\Controllers\ReturPenjualanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\PenerimaanBarangController;
use App\Http\Controllers\StokBarangController;
use App\Http\Controllers\LapOpnameController;
use App\Http\Controllers\Penjualan\CreateInvoiceController;
use App\Http\Controllers\Penjualan\DaftarPiutangController;
use App\Http\Controllers\Penjualan\DaftarVoidController;
use App\Http\Controllers\Penjualan\LaporanPenjualanController;
use App\Http\Controllers\Penjualan\ReturPenjualanController as PenjualanReturPenjualanController;
use App\Http\Controllers\PermintaanReturController;

use App\Http\Controllers\LogController;

use App\Http\Controllers\RequestSalesController;
use App\Http\Controllers\ListRequestSalesController;
use App\Http\Controllers\ListRequestOwnerController;
use App\Http\Controllers\ListRequestAdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Example Routes
// Route::match(['get', 'post'], '/dashboard', function(){
//     return view('dashboard');
// })->name('dashboard');
// Route::view('/pages/slick', 'pages.slick')->name('slick');
// Route::view('/pages/datatables', 'pages.datatables')->name('datatables');
// Route::view('/pages/blank', 'pages.blank')->name('blank');

//Master
//Barang
//Barang-Kategori
Route::middleware(['web', 'auth'])->group(function () {

    ////////////////////////////////////////////////
    /////////////////NEW ROUTES/////////////////////
    ////////////////AFTER REVAMP////////////////////
    Route::prefix("penjualan")->as("penjualan-new.")->group(function () {
        Route::resource("create-invoice", CreateInvoiceController::class);
        Route::prefix("daftar-piutang")->as("daftar-piutang.")->group(function () {
            Route::get("{id}/print", [PenjualanController::class, "print"])->name("print");
        });
        Route::resource("daftar-piutang", DaftarPiutangController::class);
        Route::prefix("daftar-void")->as("daftar-void.")->group(function () {
            Route::get("{id}/approve", [DaftarVoidController::class, "approve"])->name("approve");
            Route::get("{id}/cancel", [DaftarVoidController::class, "cancel"])->name("cancel");
        });
        Route::resource("daftar-void", DaftarVoidController::class);
        Route::resource("laporan", LaporanPenjualanController::class);
        // Route::resource("retur", PenjualanReturPenjualanController::class);

        Route::get("retur/{id}/print", [ReturPenjualanController::class, "print"])->name("retur.print");
        Route::resource('retur', PenjualanReturPenjualanController::class);
        Route::resource('retur_line', PenjualanReturPenjualanController::class);

        // Route::resource('retur-line', ReturPembelianController::class);
    });
    ////////////////////////////////////////////////

    Route::get('/', [DashboardController::class, "index"])->name("dashboard");

    //additional
    Route::get("penerimaan/{id}/create", [PenerimaanController::class, "create"])->name("penerimaan.create");
    Route::get("retur_penjualan/{id}/print", [ReturPenjualanController::class, "print"])->name("retur_penjualan.print");
    Route::get("retur_pembelian/{id}/print", [ReturPembelianController::class, "print"])->name("retur_pembelian.print");


    Route::resource('karyawan', UserController::class);
    Route::resource('role', RoleController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('merk', MerkController::class);
    Route::resource('stok', StokController::class);
    Route::resource('opname', OpnameController::class);
    Route::resource('konsumen', KonsumenController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('gudang', GudangController::class);


    Route::resource('pengeluaran', PengeluaranController::class);
    Route::resource('kategori_pengeluaran', KategoriPengeluaranController::class);
    Route::resource('inventoris', InventarisController::class);

    //penjualan


    Route::resource('daftar-void', DataVoidController::class);

    Route::resource('daftar-piutang', PenjualanController::class);
    Route::resource('penjualan_line', PenjualanLineController::class);
    Route::resource('laporan-penjualan', PiutangController::class);
    Route::resource('retur-penjualan', ReturPenjualanController::class);
    Route::resource('retur-penjualan-line', ReturPembelianController::class);


    Route::resource('po', PoController::class);
    Route::resource('po_line', PoLineController::class);
    Route::resource('penerimaan', PenerimaanController::class)->except([
        'create'
    ]);
    Route::resource('hutang', HutangController::class);
    Route::resource('retur_pembelian', ReturPembelianController::class);
    Route::resource('retur_pembelian_line', ReturPembelianController::class);

    Route::resource('penerimaan_barang', PenerimaanBarangController::class);
    Route::resource('stok_barang', StokBarangController::class);
    Route::resource('lap_opname', LapOpnameController::class);
    Route::resource('permintaan_retur', PermintaanReturController::class);

    Route::resource('log', LogController::class);

    Route::resource("requestsales", RequestSalesController::class);
    Route::resource("listrequestsales", ListRequestSalesController::class);
    Route::resource("listrequestowner", ListRequestOwnerController::class);
    Route::resource("listrequestadmin", ListRequestAdminController::class);

    Route::prefix('report')->group(function () {
        Route::get('/barang', [ReportController::class, "getlaporanbarang"]);
        Route::get('/stock', [ReportController::class, "getlaporanstock"]);
        Route::get('/penjualan', [ReportController::class, "getlaporanpenjualan"]);
        Route::get('/komisi', [ReportController::class, "getlaporankomisi"]);
        Route::get('/pembelian', [ReportController::class, "getlaporanpembelian"]);
        Route::get('/piutang', [ReportController::class, "getlaporanpiutang"]);

        Route::get('/hutang', [ReportController::class, "getlaporanhutang"]);
        Route::get('/pengeluaran', [ReportController::class, "getlaporanpengeluaran"]);
        Route::get('/labarugi', [ReportController::class, "getlaporanlabarugi"]);
        Route::get('/penjualankonsumen', [ReportController::class, "getlaporanpenjualankonsumen"]);
        Route::get('/pembeliansupplier', [ReportController::class, "getlaporanpembeliansupplier"]);
        Route::get('rpenjualan', [ReportController::class, "getlaporanreturpenjualan"]);
        Route::get('rpembelian', [ReportController::class, "getlaporanreturpembelian"]);
        Route::get('opname', [ReportController::class, "getlaporanstockopname"]);
        Route::get('overview', [ReportController::class, "overview"]);
    });
    Route::resource('report', ReportController::class);

    Route::get('/home', [HomeController::class, 'index'])->name('home');
});
Auth::routes();

//update
Route::get("update/modal/{start}/{end}", [ReportController::class, "calcModalSalesHeader"]);
Route::get("update/totalpaid/{start}/{end?}", [ReportController::class, "calcTotalPaid"]);
Route::get("update/totalsales/{start}/{end?}", [ReportController::class, "calcTotalSales"]);
Route::get("update/komisi/{flagAll}", [ReportController::class, "calckomisi"]);
Route::get("update/{id}/{check}/purchaseprice", [StokController::class, "updateModalPrice"]);

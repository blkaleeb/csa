<?php

use App\Http\Controllers\GudangController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KategoriPengeluaranController;
use App\Http\Controllers\KonsumenController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ReturPembelianController;
use App\Http\Controllers\ReturPenjualanController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Models\SalesOrderHeader;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['api'])->group(function () {

Route::post("/salesorder/history/customer", [PenjualanController::class, "getSalesHistory"]);
Route::post("/salesline/history/customer", [PenjualanController::class, "getSalesLineHistory"]);
Route::get('/stock/po/{id}', [StokController::class, "getStockSupplierReturn"]);
Route::get('/stock/salesorder/{id}', [StokController::class, "getStockCustomerReturn"]);


Route::get("category", [KategoriController::class, 'index']);
Route::get("brand", [MerkController::class, 'index']);
Route::get("gudang", [GudangController::class, 'index']);
Route::get("customer", [KonsumenController::class, 'index']);
Route::get("supplier", [SupplierController::class, 'index']);
Route::get("itemstock", [StokController::class, 'index']);
Route::post("itemstock/category", [StokController::class, 'getStockByCategory']);
Route::post("itemstock/brand", [StokController::class, 'getStockByBrand']);
Route::post("itemstock/name", [StokController::class, 'getStockByName']);

Route::get("user", [UserController::class, 'index']);
Route::get("inventaris", [InventarisController::class, 'index']);
Route::get("kategori-pengeluaran", [KategoriPengeluaranController::class, 'index']);
});

//Print
Route::prefix('v1')->group(function () {
    Route::put("sales-order-header/{id}", [PenjualanController::class, "update"]);
    Route::get("sales-order-header/{id}/detail", [PenjualanController::class, "show"]);

    Route::get("customer-return-header/{id}", [ReturPenjualanController::class, "show"]);
    Route::get("supplier-return-header/{id}", [ReturPembelianController::class, "show"]);
});

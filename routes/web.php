<?php

use App\Http\Controllers\AAktivitasController;
use App\Http\Controllers\AAreaLokasiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AKategoriController;
use App\Http\Controllers\ALaporanController;
use App\Http\Controllers\AManajerController;
use App\Http\Controllers\AMerchantController;
use App\Http\Controllers\ASalesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MAktivitasController;
use App\Http\Controllers\MLaporanController;
use App\Http\Controllers\MMerchantController;
use App\Http\Controllers\MSalesController;
use App\Http\Controllers\ProfilAdminController;
use App\Http\Controllers\ProfilManajerController;
use App\Http\Controllers\ProfilSalesController;
use App\Http\Controllers\SAktivitasController;
use App\Http\Controllers\SMerchantController;

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


Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//DASHBOARD = TOP

Route::get('/', [HomeController::class, 'index'])->name('auth.login');
// Route::get('/home', [HomeController::class, 'index'])->name('auth.login');

// Route::get('/admin/dashboard', [HomeController::class, 'admin'])->name('admin.dashboard');
Route::group(['namespace' => 'admin', 'prefix' => 'admin', 'middleware' => 'is_admin'], function () {

    Route::get('/dashboard', [HomeController::class, 'admin'])->name('admin.dashboard');
    Route::get('status/{aktivitas}', [HomeController::class, 'status_admin']);
    Route::get('dashboard/data-table/{status_id}', [HomeController::class, 'indexDataAdmin'])->name('dashboard.data');

    //PROFIL
    Route::get('/ubahProfil/{user}', [ProfilAdminController::class, 'edit']);
    Route::patch('/{user}/ubahProfil', [ProfilAdminController::class, 'update']);

    //KATEGORI
    Route::get('kategori/table', [AKategoriController::class, 'index']);
    Route::get('kategori/data-table', [AKategoriController::class, 'indexData'])->name('kategori.data');
    Route::get('kategori/add', [AKategoriController::class, 'create']);
    Route::post('kategori/table', [AKategoriController::class, 'store']);
    Route::get('kategori/{kategori}/edit', [AKategoriController::class, 'edit']);
    Route::patch('kategori/{kategori}', [AKategoriController::class, 'update']);
    Route::delete('kategori/{kategori}', [AKategoriController::class, 'destroy']);
    Route::get('/exportAKategoriExcel', [AKategoriController::class, 'exportExcel'])->name('exportExcelKategori');
    Route::get('/exportAKategoriPdf', [AKategoriController::class, 'exportPdf'])->name('exportPdfKategori');

    //AREA LOKASI
    Route::get('areaLokasi/table', [AAreaLokasiController::class, 'index']);
    Route::get('areaLokasi/data-table', [AAreaLokasiController::class, 'indexData'])->name('areaLokasi.data');
    Route::get('areaLokasi/add', [AAreaLokasiController::class, 'create']);
    Route::get('areaLokasi/add/{id}', [AAreaLokasiController::class, 'getCity']);
    Route::get('areaLokasi/add1/{id}', [AAreaLokasiController::class, 'getDistrict']);
    Route::post('areaLokasi/table', [AAreaLokasiController::class, 'store']);
    Route::get('areaLokasi/{area_lokasi}/edit', [AAreaLokasiController::class, 'edit']);
    Route::get('areaLokasi/edit/{id}', [AAreaLokasiController::class, 'getCity']);
    Route::get('areaLokasi/edit1/{id}', [AAreaLokasiController::class, 'getDistrict']);
    Route::patch('areaLokasi/{area_lokasi}', [AAreaLokasiController::class, 'update']);
    Route::delete('areaLokasi/{area_lokasi}', [AAreaLokasiController::class, 'destroy']);
    Route::get('/exportAAreaLokasiExcel', [AAreaLokasiController::class, 'exportExcel'])->name('exportExcelArea');
    Route::get('/exportAAreaLokasiPdf', [AAreaLokasiController::class, 'exportPdf'])->name('exportPdfArea');

    //MANAJER
    Route::get('manajer/table', [AManajerController::class, 'index']);
    ROute::get('manajer/data-table', [AManajerController::class, 'indexData'])->name('manajer.data');
    Route::get('manajer/add', [AManajerController::class, 'create']);
    Route::post('manajer/table', [AManajerController::class, 'store']);
    Route::get('manajer/{user}/edit', [AManajerController::class, 'edit']);
    Route::patch('manajer/{user}', [AManajerController::class, 'update']);
    Route::delete('manajer/{user}', [AManajerController::class, 'destroy']);
    Route::get('/exportAManajerExcel', [AManajerController::class, 'exportExcel'])->name('exportExcelManajer');
    Route::get('/exportAManajerPdf', [AManajerController::class, 'exportPdf'])->name('exportPdfManajer');
    Route::patch('manajer/{user}/edit', [AManajerController::class, 'resetPassword']);
    Route::get('/exportATrashManajerExcel', [AManajerController::class, 'trashExcel'])->name('exportExcelTrashManajer');
    Route::get('/exportATrashManajerPdf', [AManajerController::class, 'trashPdf'])->name('exportPdfTrashManajer');

    Route::get('manajer/trash', [AManajerController::class, 'trash']);
    Route::get('manajer/trash-data', [AManajerController::class, 'trashData'])->name('trash.data');
    Route::get('manajer/restore/{manajer?}', [AManajerController::class, 'restore']);
    Route::get('manajer/delete', [AManajerController::class, 'delete']);
    Route::delete('manajer/delete/{manajer}', [AManajerController::class, 'delete']);

    //SALES
    Route::get('sales/table', [ASalesController::class, 'index']);
    Route::get('sales/data-table', [ASalesController::class, 'indexData'])->name('sales.data');
    Route::get('sales/add', [ASalesController::class, 'create']);
    Route::post('sales/table', [ASalesController::class, 'store']);
    Route::get('sales/{user}/edit', [ASalesController::class, 'edit']);
    Route::patch('sales/{user}', [ASalesController::class, 'update']);
    Route::delete('sales/{user}', [ASalesController::class, 'destroy']);
    Route::get('/exportASalesExcel', [ASalesController::class, 'exportExcel'])->name('exportExcelSales');
    Route::get('/exportASalesPdf', [ASalesController::class, 'exportPdf'])->name('exportPdfSales');
    Route::patch('sales/{user}/edit', [ASalesController::class, 'resetPassword']);
    Route::get('/exportATrashSalesExcel', [ASalesController::class, 'trashExcel'])->name('exportExcelTrashSales');
    Route::get('/exportATrashSalesPdf', [ASalesController::class, 'trashPdf'])->name('exportPdfTrashSales');

    Route::get('sales/trash', [ASalesController::class, 'trash']);
    Route::get('sales/trash-data', [ASalesController::class, 'trashdata'])->name('trash.dataS');
    Route::get('sales/restore/{sales?}', [ASalesController::class, 'restore']);
    Route::get('sales/delete/', [ASalesController::class, 'delete']);
    Route::delete('sales/delete/{sales}', [ASalesController::class, 'delete']);

    //MERCHANT
    Route::get('merchant/table', [AMerchantController::class, 'index']);
    Route::get('merchant/data-table', [AMerchantController::class, 'indexData'])->name('merchant.data');
    Route::get('merchant/add', [AMerchantController::class, 'create']);
    Route::get('/merchant/addS/{id}', [AMerchantController::class, 'getSales']);
    Route::get('merchant/add/{id}', [AMerchantController::class, 'getCity']);
    Route::get('merchant/add1/{id}', [AMerchantController::class, 'getDistrict']);
    Route::post('merchant/table', [AMerchantController::class, 'store']);
    Route::get('merchant/{merchant}/edit', [AMerchantController::class, 'edit']);
    Route::get('/merchant/editS/{id}', [AMerchantController::class, 'getSales']);
    Route::get('/merchant/edit/{id}', [AMerchantController::class, 'getCity']);
    Route::get('merchant/edit1/{id}', [AMerchantController::class, 'getDistrict']);
    Route::patch('merchant/{merchant}', [AMerchantController::class, 'update']);
    Route::delete('merchant/{merchant}', [AMerchantController::class, 'destroy']);
    Route::get('/exportAMerchantExcel', [AMerchantController::class, 'exportExcel'])->name('exportExcelMerchant');
    Route::get('/exportAMerchantPdf', [AMerchantController::class, 'exportPdf'])->name('exportPdfMerchant');
    Route::get('merchant/{merchant}/history', [AMerchantController::class, 'history']);
    Route::get('merchant/{history}/excel', [AMerchantController::class, 'excelHistory']);
    Route::get('merchant/{history}/pdf', [AMerchantController::class, 'pdfHistory']);

    //AKTIVITAS
    Route::get('aktivitas/table', [AAktivitasController::class, 'index']);
    Route::get('aktivitas/data-table', [AAktivitasController::class, 'indexData'])->name('aktivitas.data');
    Route::get('aktivitas/{aktivitas}/edit', [AAktivitasController::class, 'edit']);

    Route::patch('aktivitas/edit', [AAktivitasController::class, 'update']);
    Route::delete('aktivitas/{aktivitas}', [AAktivitasController::class, 'destroy']);
    Route::get('/exportDateExcel/{tglawal}/{tglakhir}', [AAktivitasController::class, 'exportDateExcel'])->name('exportDateExcel');
    Route::get('/exportDatePdf/{tglawal}/{tglakhir}', [AAktivitasController::class, 'exportDatePdf'])->name('exportDatePdf');

    //LAPORAN
    Route::get('laporan/table', [ALaporanController::class, 'index']);
    Route::get('laporan/data-table', [ALaporanController::class, 'indexData'])->name('laporan.data');
    Route::get('aktivitas/{aktivitas}/merchant', [ALaporanController::class, 'link_merchant']);
    Route::get('laporan/data-table/merchant/{sales_id}', [ALaporanController::class, 'indexDataMerchant'])->name('aktivitas_merchant.data');
    Route::get('aktivitas/{aktivitas}/status/{status}', [ALaporanController::class, 'link_status']);
    Route::get('laporan/data-table/status/{sales_id}/{status_id}', [ALaporanController::class, 'indexDataStatus'])->name('aktivitas_status.data');
    Route::get('/exportALaporanExcel', [ALaporanController::class, 'exportExcel'])->name('exportExcelLaporan');
    Route::get('/exportALaporanPdf', [ALaporanController::class, 'exportPdf'])->name('exportPdfLaporan');
});

Route::group(['namespace' => 'manajer', 'prefix' => 'manajer', 'middleware' => 'is_manajer'], function () {

    Route::get('dashboard', [HomeController::class, 'manajer'])->name('manajer.dashboard');
    Route::get('status/{status}', [HomeController::class, 'status_manajer']);
    Route::get('dashboard/data-table/{status_id}', [HomeController::class, 'indexDataManajer'])->name('dashboard.dataM');

    //PROFIL
    Route::get('/ubahProfil/{user}', [ProfilManajerController::class, 'edit']);
    Route::patch('/{user}/ubahProfil', [ProfilManajerController::class, 'update']);

    //SALES
    Route::get('sales/table', [MSalesController::class, 'index']);
    Route::get('sales-data-tableM', [MSalesController::class, 'indexData'])->name('sales.dataM');
    Route::get('sales/add', [MSalesController::class, 'create']);
    Route::post('sales/table', [MSalesController::class, 'store']);
    Route::get('sales/{user}/edit', [MSalesController::class, 'edit']);
    Route::patch('sales/{user}', [MSalesController::class, 'update']);
    Route::delete('sales/{user}', [MSalesController::class, 'destroy']);
    Route::get('/exportMSalesExcel', [MSalesController::class, 'exportExcel'])->name('exportExcelSalesM');
    Route::get('/exportMSalesPdf', [MSalesController::class, 'exportPdf'])->name('exportPdfSalesM');
    Route::patch('sales/{user}/edit', [MSalesController::class, 'resetPassword']);
    Route::get('/exportMTrashSalesExcel', [MSalesController::class, 'trashExcel'])->name('exportExcelTrashSalesM');
    Route::get('/exportMTrashSalesPdf', [MSalesController::class, 'trashPdf'])->name('exportPdfTrashSalesM');

    Route::get('sales/trash', [MSalesController::class, 'trash']);
    Route::get('sales-trash-data', [MSalesController::class, 'trashData'])->name('trash.dataM');
    Route::get('sales/restore/{sales?}', [MSalesController::class, 'restore']);
    Route::get('sales/delete/', [MSalesController::class, 'delete']);
    Route::delete('sales/delete/{sales}', [MSalesController::class, 'delete']);

    //MERCHANT
    Route::get('merchant/table', [MMerchantController::class, 'index']);
    Route::get('merchant/data-tableM', [MMerchantController::class, 'indexData'])->name('merchant.dataM');
    Route::get('merchant/add', [MMerchantController::class, 'create']);
    Route::get('merchant/addm/{id}', [MMerchantController::class, 'getCity']);
    Route::get('merchant/addm1/{id}', [MMerchantController::class, 'getDistrict']);
    Route::post('merchant/table', [MMerchantController::class, 'store']);
    Route::get('merchant/{merchant}/edit', [MMerchantController::class, 'edit']);
    Route::get('/merchant/edit/{id}', [MMerchantController::class, 'getCity']);
    Route::get('merchant/edit1/{id}', [MMerchantController::class, 'getDistrict']);
    Route::patch('merchant/{merchant}', [MMerchantController::class, 'update']);
    Route::delete('merchant/{merchant}', [MMerchantController::class, 'destroy']);
    Route::get('/exportMMerchantExcel', [MMerchantController::class, 'exportExcel'])->name('exportExcelMerchantM');
    Route::get('/exportMMerchantPdf', [MMerchantController::class, 'exportPdf'])->name('exportPdfMerchantM');
    Route::get('merchant/{merchant}/history', [MMerchantController::class, 'history']);
    Route::get('merchant/{history}/excel', [MMerchantController::class, 'excelHistory']);
    Route::get('merchant/{history}/pdf', [MMerchantController::class, 'pdfHistory']);

    //AKTIVITAS
    Route::get('aktivitas/table', [MAktivitasController::class, 'index']);
    Route::get('aktivitas/data-tableM', [MAktivitasController::class, 'indexData'])->name('aktivitas.dataM');
    Route::get('aktivitas/{aktivitas}/edit', [MAktivitasController::class, 'edit']);
    Route::patch('aktivitas/edit', [MAktivitasController::class, 'update']);
    Route::delete('aktivitas/{aktivitas}', [MAktivitasController::class, 'destroy']);
    Route::get('/exportDateExcelM/{tglawal}/{tglakhir}', [MAktivitasController::class, 'exportDateExcel'])->name('exportDateExcelM');
    Route::get('/exportDatePdfM/{tglawal}/{tglakhir}', [MAktivitasController::class, 'exportDatePdf'])->name('exportDatePdfM');

    //LAPORAN
    Route::get('laporan/table', [MLaporanController::class, 'index']);
    Route::get('laporan/data-tableM', [MLaporanController::class, 'indexData'])->name('laporan.dataM');
    Route::get('aktivitas/{aktivitas}/merchant', [MLaporanController::class, 'link_merchant']);
    Route::get('laporan/data-tableM/merchant/{sales_id}', [MLaporanController::class, 'indexDataMerchant'])->name('aktivitas_merchant.dataM');
    Route::get('aktivitas/{aktivitas}/status/{status}', [MLaporanController::class, 'link_status']);
    Route::get('laporan/data-tableM/status/{sales_id}/{status_id}', [MLaporanController::class, 'indexDataStatus'])->name('aktivitas_status.dataM');
    Route::get('/exportMLaporanExcel', [MLaporanController::class, 'exportExcel'])->name('exportExcelLaporanM');
    Route::get('/exportMLaporanPdf', [MLaporanController::class, 'exportPdf'])->name('exportPdfLaporanM');
});

Route::group(['namespace' => 'sales', 'prefix' => 'sales', 'middleware' => 'is_sales'], function () {

    Route::get('dashboard', [HomeController::class, 'sales'])->name('sales.dashboard');
    Route::get('status/{status}', [HomeController::class, 'status_sales']);
    Route::get('dashboard/data-tableS/{status_id}', [HomeController::class, 'indexDataSales'])->name('dashboard.dataS');

    //PROFIL
    Route::get('/ubahProfil/{user}', [ProfilSalesController::class, 'edit']);
    Route::patch('/{user}/ubahProfil', [ProfilSalesController::class, 'update']);

    //MERCHANT
    Route::get('merchant/table', [SMerchantController::class, 'index']);
    Route::get('merchant/data-tableS', [SMerchantController::class, 'indexData'])->name('merchant.dataS');
    Route::get('merchant/add', [SMerchantController::class, 'create']);
    Route::get('merchant/adds/{id}', [SMerchantController::class, 'getCity']);
    Route::get('merchant/adds1/{id}', [SMerchantController::class, 'getDistrict']);
    Route::post('merchant/table', [SMerchantController::class, 'store']);
    Route::get('merchant/{merchant}/edit', [SMerchantController::class, 'edit']);
    Route::get('/merchant/edit/{id}', [SMerchantController::class, 'getCity']);
    Route::get('/merchant/edit1/{id}', [SMerchantController::class, 'getDistrict']);
    Route::patch('merchant/{merchant}', [SMerchantController::class, 'update']);
    Route::delete('merchant/{merchant}', [SMerchantController::class, 'destroy']);

    //AKTIVITAS
    Route::get('aktivitas/tableL', [SAktivitasController::class, 'index']);
    Route::get('aktivitas/data-tableS', [SAktivitasController::class, 'indexData'])->name('aktivitas.dataS');
    Route::get('aktivitas/addL', [SAktivitasController::class, 'create']);
    Route::post('aktivitas/tableL', [SAktivitasController::class, 'store']);

    //AKTIVITAS
    Route::get('aktivitas/{aktivitas}/history', [SAktivitasController::class, 'history']);
    Route::post('aktivitas/history', [SAktivitasController::class, 'store1']);
    Route::get('aktivitas/{aktivitas}/edit', [SAktivitasController::class, 'edit']);
    Route::patch('aktivitas/edit', [SAktivitasController::class, 'update']);
    Route::delete('aktivitas/{aktivitas}', [SAktivitasController::class, 'destroy']);
});

Route::get('/logout', function () {
    Auth::logout();
    redirect('/');
});

Route::post('/login', [LoginController::class, 'login']);

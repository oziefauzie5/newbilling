<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\Applikasi\AppController;
use App\Http\Controllers\Barang\BarangController;
use App\Http\Controllers\Barang\KategoriController;
use App\Http\Controllers\Barang\SupplierControoler;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Pelanggan\LoginPelangganController;
use App\Http\Controllers\Pelanggan\PelangganController;
use App\Http\Controllers\PSB\PsbController;
use App\Http\Controllers\PSB\RegistrasiApiController;
use App\Http\Controllers\PSB\RegistrasiController;
use App\Http\Controllers\PSB\SementaraMigrasiController;
use App\Http\Controllers\Router\PaketController;
use App\Http\Controllers\Router\RouterController;
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\Teknisi\TeknisiController;
use App\Http\Controllers\Transaksi\InvoiceController;
use App\Http\Controllers\User\UserController;
use App\Models\Transaksi\Invoice;
use Illuminate\Support\Facades\Route;

Route::get('/adminapp', [LoginController::class, 'index'])->name('adminapp');
Route::post('/login-proses', [LoginController::class, 'login_proses'])->name('login-proses');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', [LoginPelangganController::class, 'index'])->name('login_pelanggan');
Route::post('/proses-login', [LoginPelangganController::class, 'login_proses'])->name('proses-login');
Route::get('/logout-client', [LoginPelangganController::class, 'logout'])->name('logout_client');

Route::group(['prefix' => 'client', 'middleware' => ['auth:pelanggan'], 'as' => 'client.'], function () {
    Route::get('/home', [PelangganController::class, 'index'])->name('index');
    Route::get('/details', [PelangganController::class, 'details'])->name('details');
    Route::get('/tagihan/{inv}', [PelangganController::class, 'tagihan'])->name('tagihan');
    // Route::post('/transaksi', [TransaksiController::class, 'payment_tripay'])->name('payment_tripay');
    // Route::get('/transaksi/show/{refrensi}', [TransaksiController::class, 'show'])->name('show');
});

// Route::group(['prefix' => 'client', 'middleware' => ['auth:pelanggan'], 'as' => 'client.'], function () {
//     Route::get('/home', [PelangganController::class, 'index'])->name('index');
//     Route::get('/tagihan/{inv}', [PelangganController::class, 'tagihan'])->name('tagihan');
// Route::post('/transaksi', [TransaksiController::class, 'payment_tripay'])->name('payment_tripay');
// Route::get('/transaksi/show/{refrensi}', [TransaksiController::class, 'show'])->name('show');
// });


Route::group(['prefix' => 'admin', 'middleware' => ['auth:web'], 'as' => 'admin.'], function () {

    Route::get('/home', [HomeController::class, 'home'])->name('home');
    ##CRUD DATA USER
    Route::get('/user', [UserController::class, 'index'])->name('user.index')->middleware('can:admin');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::put('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::delete('/use/{id}r/delete', [UserController::class, 'delete'])->name('user.delete');

    Route::get('/setting', [AppController::class, 'index'])->name('app.index')->middleware('can:admin');
    Route::post('/setting/app', [AppController::class, 'akun_store'])->name('app.akun_store')->middleware('can:admin');
    Route::put('/setting/{id}/app-akun-edit', [AppController::class, 'akun_edit'])->name('app.akun_edit')->middleware('can:admin');
    Route::delete('/setting/{id}/app-akun-delete', [AppController::class, 'akun_delete'])->name('app.akun_delete')->middleware('can:admin');
    Route::post('/setting/app-Tripay', [AppController::class, 'tripay_store'])->name('app.tripay_store')->middleware('can:admin');
    Route::post('/setting/applikasi', [AppController::class, 'aplikasi_store'])->name('app.aplikasi_store')->middleware('can:admin');
    Route::post('/setting/app-biaya', [AppController::class, 'biaya_store'])->name('app.biaya_store')->middleware('can:admin');
    Route::post('/setting/app-Waktu', [AppController::class, 'waktu_store'])->name('app.waktu_store')->middleware('can:admin');

    Route::get('/router', [RouterController::class, 'index'])->name('router.index');
    Route::post('/router/store', [RouterController::class, 'store'])->name('router.store');
    Route::put('/router/edit/{id}', [RouterController::class, 'edit'])->name('router.edit');
    Route::delete('/router/{id}/delete', [RouterController::class, 'delete_router'])->name('router.delete_router');
    Route::get('/router/cek/{id}', [RouterController::class, 'cekRouter'])->name('router.cekRouter');
    Route::get('/router/{id}/pppoe', [RouterController::class, 'getPppoe'])->name('router.getPppoe');
    Route::get('/router/{id}/hotspot', [RouterController::class, 'getHotspot'])->name('router.getHotspot');
    Route::get('/router/{id}/{ip}', [RouterController::class, 'router_remote'])->name('router.router_remote');
    Route::get('/router/{id}/{idmik}/kick', [RouterController::class, 'kick_hotspot'])->name('router.kick_hotspot');

    Route::get('/router/paket', [PaketController::class, 'index'])->name('router.paket.index');
    Route::get('/router/create', [PaketController::class, 'create'])->name('router.paket.create');
    Route::get('/router/paket/{id}/get', [PaketController::class, 'getRouter'])->name('router.paket.getRouter');
    Route::post('/router/paket/store', [PaketController::class, 'store'])->name('router.paket.store');
    Route::post('/router/paket/{id}/update', [PaketController::class, 'update'])->name('router.paket.update');
    Route::post('/router/paket/export', [PaketController::class, 'exportPaketToMikrotik'])->name('router.paket.exportPaketToMikrotik');


    Route::get('/PSB', [PsbController::class, 'index'])->name('psb.index');
    Route::get('/PSB/List-Input-Data', [PsbController::class, 'list_input'])->name('psb.list_input');
    Route::put('/PSB/Validasi/{ktp}', [PsbController::class, 'storeValidateKtp'])->name('psb.storeValidateKtp');
    Route::post('/PSB/Input-Data', [PsbController::class, 'store'])->name('psb.store');
    Route::get('/PSB/{id}/ShowEdit-Input-Data', [PsbController::class, 'edit_inputdata'])->name('psb.edit_inputdata');
    Route::post('/PSB/Input-Import', [PsbController::class, 'input_data_import'])->name('psb.input_data_import');
    Route::post('/PSB/Input-Data_update', [PsbController::class, 'input_data_update'])->name('psb.input_data_update');
    Route::delete('/PSB/Input-data-delete/{id}', [PsbController::class, 'input_data_delete'])->name('psb.input_data_delete');
    Route::get('/PSB/Registrasi', [RegistrasiController::class, 'index'])->name('reg.index');
    Route::get('/PSB/{id}/berita_acara', [RegistrasiController::class, 'berita_acara'])->name('psb.berita_acara');
    Route::get('/PSB/{id}/bukti-kas-keluar', [RegistrasiController::class, 'bukti_kas_keluar'])->name('psb.bukti_kas_keluar');

    Route::get('/PSB/sementara_migrasi', [SementaraMigrasiController::class, 'sementara_migrasi'])->name('reg.sementara_migrasi');
    Route::post('/PSB/sementara_migrasi-Store', [SementaraMigrasiController::class, 'store_sementara_migrasi'])->name('reg.store_sementara_migrasi');

    Route::get('/PSB/Registrasi-cari/{id}', [RegistrasiController::class, 'pilih_pelanggan_registrasi'])->name('reg.pilih_pelanggan_registrasi');
    Route::post('/PSB/Registrasi-Store', [RegistrasiController::class, 'store'])->name('reg.store');
    Route::get('/PSB/Validasi1/{id}', [RegistrasiController::class, 'validasi_pachcore'])->name('reg.validasi_pachcore');
    Route::get('/PSB/Validasi2/{id}', [RegistrasiController::class, 'validasi_adaptor'])->name('reg.validasi_adaptor');
    Route::get('/PSB/Validasi3/{id}', [RegistrasiController::class, 'validasi_ont'])->name('reg.validasi_ont');
    Route::get('/PSB/get-paket{id}', [RegistrasiController::class, 'getPaket'])->name('reg.getPaket');
    Route::post('/PSB/Registrasi-Import', [RegistrasiController::class, 'registrasi_import'])->name('reg.registrasi_import');
    Route::get('/PSB/Redirect/{id}', [RegistrasiApiController::class, 'registrasi_api'])->name('reg.registrasi_api');
    // Route::get('/PSB/Redirect-Edit/{id}', [RegistrasiApiController::class, 'edit_registrasi_api'])->name('reg.edit_registrasi_api');
    Route::get('/PSB/Edit/{id}/Pelanggan', [PsbController::class, 'edit_pelanggan'])->name('psb.edit_pelanggan');
    Route::put('/PSB/Update/{id}/Pelanggan', [RegistrasiApiController::class, 'update_pelanggan'])->name('psb.update_pelanggan');
    Route::put('/PSB/Update-Profile/{id}', [RegistrasiApiController::class, 'update_profile'])->name('psb.update_profile');
    Route::put('/PSB/Update-Router/{id}', [RegistrasiApiController::class, 'update_router'])->name('psb.update_router');
    // Route::get('/PSB/edit-validasi-ont/{id}', [RegistrasiController::class, 'edit_validasi_ont'])->name('reg.edit_validasi_ont');


    Route::post('/Supplier/Tambah-supplier', [SupplierControoler::class, 'store'])->name('supplier.store');

    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori/Tambah-kategori', [KategoriController::class, 'store'])->name('kategori.store');

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::post('/barang/Tambah-barang', [BarangController::class, 'store'])->name('barang.store');
    Route::post('/barang/Tambah-subbarang', [BarangController::class, 'store_subbarang'])->name('barang.store_subbarang');
    Route::post('/barang/Edit/{id}', [BarangController::class, 'edit'])->name('barang.edit');
    Route::get('/barang/sub-barang/{id}', [BarangController::class, 'sub_barang'])->name('barang.sub_barang');
    Route::get('/barang/rekap-barang/{id}', [BarangController::class, 'rekap_barang'])->name('barang.rekap_barang');
    Route::get('/barang/Hapus-sub-barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::put('/barang/input-subbarang/{id}', [BarangController::class, 'input_subbarang'])->name('barang.input_subbarang');

    Route::get('/Teknisi', [TeknisiController::class, 'index'])->name('teknisi.index');
    Route::post('/Teknisi/Job', [TeknisiController::class, 'job'])->name('teknisi.job');
    Route::get('/Teknisi/List-Aktivasi', [TeknisiController::class, 'list_aktivasi'])->name('teknisi.list_aktivasi');
    Route::get('/Teknisi/Aktivasi/{id}', [TeknisiController::class, 'aktivasi'])->name('teknisi.aktivasi');
    Route::put('/Teknisi/Proses-Aktivasi/{id}', [TeknisiController::class, 'proses_aktivasi'])->name('teknisi.proses_aktivasi');
    Route::get('/Teknisi/Teknisi-getBarang/{id}', [TeknisiController::class, 'getBarang'])->name('teknisi.getBarang');


    Route::get('/Invoice', [InvoiceController::class, 'index'])->name('inv.index');
    Route::get('/Invoice/Detail/{id}', [InvoiceController::class, 'sub_invoice'])->name('inv.sub_invoice');
    Route::put('/Invoice/Payment/{id}', [InvoiceController::class, 'payment'])->name('inv.payment');
    Route::put('/Invoice/addons/{id}', [InvoiceController::class, 'addons'])->name('inv.addons');
    Route::put('/Invoice/diskon/{id}', [InvoiceController::class, 'addDiskon'])->name('inv.addDiskon');
    Route::delete('/Invoice/addons-hapus/{id}/{inv}/{tot}', [InvoiceController::class, 'addons_delete'])->name('inv.addons_delete');


    Route::get('/Sales/index', [SalesController::class, 'index'])->name('sales.index');
    Route::get('/Sales/list', [SalesController::class, 'list_registrasi'])->name('sales.list_registrasi');
});

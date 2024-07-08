<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\Applikasi\AppController;
use App\Http\Controllers\Barang\BarangController;
use App\Http\Controllers\Barang\KategoriController;
use App\Http\Controllers\Barang\SupplierControoler;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\NOC\NocController;
use App\Http\Controllers\Pelanggan\LoginPelangganController;
use App\Http\Controllers\Pelanggan\PelangganController;
use App\Http\Controllers\PSB\PsbController;
use App\Http\Controllers\PSB\RegistrasiApiController;
use App\Http\Controllers\PSB\RegistrasiController;
use App\Http\Controllers\PSB\SementaraMigrasiController;
use App\Http\Controllers\Router\ExportExcel;
use App\Http\Controllers\Router\PaketController;
use App\Http\Controllers\Router\RouterController;
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\Teknisi\TeknisiController;
use App\Http\Controllers\Transaksi\CallbackController;
use App\Http\Controllers\Transaksi\InvoiceController;
use App\Http\Controllers\Transaksi\LaporanController;
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
    Route::post('/tagihan', [PelangganController::class, 'payment_tripay'])->name('payment_tripay');
    Route::get('/tagihan/show/{refrensi}', [PelangganController::class, 'show'])->name('show');
});

Route::post('/callback', [CallbackController::class, 'handle']);


Route::group(['prefix' => 'admin', 'middleware' => ['auth:web'], 'as' => 'admin.'], function () {

    Route::get('/home', [HomeController::class, 'home'])->name('home')->middleware(['role:admin|NOC|STAF ADMIN']);
    ##CRUD DATA USER
    Route::get('/user', [UserController::class, 'index'])->name('user.index')->middleware(['role:admin']);
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store')->middleware(['role:admin']);
    Route::put('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit')->middleware(['role:admin']);
    Route::delete('/user/{id}/delete', [UserController::class, 'delete'])->name('user.delete')->middleware(['role:admin']);

    Route::get('/setting', [AppController::class, 'index'])->name('app.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/setting/app', [AppController::class, 'akun_store'])->name('app.akun_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/setting/{id}/app-akun-edit', [AppController::class, 'akun_edit'])->name('app.akun_edit')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::delete('/setting/{id}/app-akun-delete', [AppController::class, 'akun_delete'])->name('app.akun_delete')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/setting/app-Tripay', [AppController::class, 'tripay_store'])->name('app.tripay_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/setting/applikasi', [AppController::class, 'aplikasi_store'])->name('app.aplikasi_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/setting/app-biaya', [AppController::class, 'biaya_store'])->name('app.biaya_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/setting/app-Waktu', [AppController::class, 'waktu_store'])->name('app.waktu_store')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::get('/router', [RouterController::class, 'index'])->name('router.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/router/store', [RouterController::class, 'store'])->name('router.store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/router/edit/{id}', [RouterController::class, 'edit'])->name('router.edit')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::delete('/router/{id}/delete', [RouterController::class, 'delete_router'])->name('router.delete_router')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/cek/{id}', [RouterController::class, 'cekRouter'])->name('router.cekRouter')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/{id}/pppoe', [RouterController::class, 'getPppoe'])->name('router.getPppoe')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/{id}/hotspot', [RouterController::class, 'getHotspot'])->name('router.getHotspot')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/{id}/{ip}', [RouterController::class, 'router_remote'])->name('router.router_remote')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/{id}/{idmik}/kick', [RouterController::class, 'kick_hotspot'])->name('router.kick_hotspot')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::get('/router/paket', [PaketController::class, 'index'])->name('router.paket.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/create', [PaketController::class, 'create'])->name('router.paket.create')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/paket/{id}/get', [PaketController::class, 'getRouter'])->name('router.paket.getRouter')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/router/paket/store', [PaketController::class, 'store'])->name('router.paket.store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/router/paket/{id}/update', [PaketController::class, 'update'])->name('router.paket.update')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/router/paket/export', [PaketController::class, 'exportPaketToMikrotik'])->name('router.paket.exportPaketToMikrotik')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::get('/Noc', [NocController::class, 'index'])->name('noc.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/Noc/{id}/Pengecekan', [NocController::class, 'pengecekan'])->name('noc.pengecekan')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/Noc/{id}/Pengecekan-Done', [NocController::class, 'pengecekan_put'])->name('noc.pengecekan_put')->middleware(['role:admin|NOC|STAF ADMIN']);


    Route::get('/PSB', [PsbController::class, 'index'])->name('psb.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/PSB/List-Input-Data', [PsbController::class, 'list_input'])->name('psb.list_input')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/PSB/Validasi/{ktp}', [PsbController::class, 'storeValidateKtp'])->name('psb.storeValidateKtp')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/PSB/Input-Data', [PsbController::class, 'store'])->name('psb.store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/PSB/{id}/ShowEdit-Input-Data', [PsbController::class, 'edit_inputdata'])->name('psb.edit_inputdata')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/PSB/Input-Import', [PsbController::class, 'input_data_import'])->name('psb.input_data_import')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/PSB/Input-Data_update', [PsbController::class, 'input_data_update'])->name('psb.input_data_update')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::delete('/PSB/Input-data-delete/{id}', [PsbController::class, 'input_data_delete'])->name('psb.input_data_delete')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/PSB/Registrasi', [RegistrasiController::class, 'index'])->name('reg.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/PSB/Delete/{id}/Registrasi', [RegistrasiController::class, 'delete_registrasi'])->name('reg.delete_registrasi')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/PSB/berita_acara', [RegistrasiController::class, 'berita_acara'])->name('psb.berita_acara')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/PSB/{id}/Print-Berita_Acara', [RegistrasiController::class, 'print_berita_acara'])->name('psb.print_berita_acara')->middleware(['role:admin|NOC|STAF ADMIN']);
    // ------------PENCAIRAN PSB-----------------
    Route::get('/Transaksi/Operasional', [RegistrasiController::class, 'operasional'])->name('inv.operasional')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/Transaksi/{id}/bukti-kas-keluar', [RegistrasiController::class, 'bukti_kas_keluar'])->name('psb.bukti_kas_keluar')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/Transaksi/Konfirm', [RegistrasiController::class, 'konfirm_pencairan'])->name('inv.konfirm_pencairan')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/Transaksi/laporan-harian', [LaporanController::class, 'index'])->name('inv.laporan')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/Transaksi/{id}/buat-laporan', [LaporanController::class, 'buat_laporan'])->name('inv.buat_laporan')->middleware(['role:admin|STAF ADMIN']);
    Route::delete('/Transaksi/{id}/Delete', [LaporanController::class, 'lap_delete'])->name('inv.lap_delete')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/Transaksi/Data-Laporan', [LaporanController::class, 'data_laporan'])->name('inv.data_laporan')->middleware(['role:admin|STAF ADMIN']);
    Route::delete('/Transaksi/{id}/Data-Laporan', [LaporanController::class, 'data_lap_delete'])->name('inv.data_lap_delete')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/Transaksi/{id}/Print-Laporan', [LaporanController::class, 'laporan_print'])->name('inv.laporan_print')->middleware(['role:admin|STAF ADMIN']);

    Route::get('/PSB/sementara_migrasi', [SementaraMigrasiController::class, 'sementara_migrasi'])->name('reg.sementara_migrasi')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/PSB/sementara_migrasi-Store', [SementaraMigrasiController::class, 'store_sementara_migrasi'])->name('reg.store_sementara_migrasi')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::get('/PSB/Registrasi-cari/{id}', [RegistrasiController::class, 'pilih_pelanggan_registrasi'])->name('reg.pilih_pelanggan_registrasi')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/PSB/Registrasi-Store', [RegistrasiController::class, 'store'])->name('reg.store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/PSB/Validasi1/{id}', [RegistrasiController::class, 'validasi_pachcore'])->name('reg.validasi_pachcore')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/PSB/Validasi2/{id}', [RegistrasiController::class, 'validasi_adaptor'])->name('reg.validasi_adaptor')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/PSB/Validasi3/{id}', [RegistrasiController::class, 'validasi_ont'])->name('reg.validasi_ont')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/PSB/get-paket{id}', [RegistrasiController::class, 'getPaket'])->name('reg.getPaket')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/PSB/Registrasi-Import', [RegistrasiController::class, 'registrasi_import'])->name('reg.registrasi_import')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/PSB/Redirect/{id}', [RegistrasiApiController::class, 'registrasi_api'])->name('reg.registrasi_api')->middleware(['role:admin|NOC|STAF ADMIN']);
    // Route::get('/PSB/Redirect-Edit/{id}', [RegistrasiApiController::class, 'edit_registrasi_api'])->name('reg.edit_registrasi_api')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/PSB/Edit/{id}/Pelanggan', [PsbController::class, 'edit_pelanggan'])->name('psb.edit_pelanggan')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/PSB/Edit/{id}/Cek Status', [NocController::class, 'status_inet'])->name('noc.status_inet')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/PSB/Update/{id}/Pelanggan', [RegistrasiApiController::class, 'update_pelanggan'])->name('psb.update_pelanggan')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/PSB/Update-Profile/{id}', [RegistrasiApiController::class, 'update_profile'])->name('psb.update_profile')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/PSB/Update-Router/{id}', [RegistrasiApiController::class, 'update_router'])->name('psb.update_router')->middleware(['role:admin|NOC|STAF ADMIN']);
    // Route::get('/PSB/edit-validasi-ont/{id}', [RegistrasiController::class, 'edit_validasi_ont'])->name('reg.edit_validasi_ont')->middleware(['role:admin|NOC|STAF ADMIN']);



    Route::post('/Supplier/Tambah-supplier', [SupplierControoler::class, 'store'])->name('supplier.store')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/kategori/Tambah-kategori', [KategoriController::class, 'store'])->name('kategori.store')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/barang/Tambah-barang', [BarangController::class, 'store'])->name('barang.store')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/barang/Tambah-subbarang', [BarangController::class, 'store_subbarang'])->name('barang.store_subbarang')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/barang/Edit/{id}', [BarangController::class, 'edit'])->name('barang.edit')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/barang/sub-barang/{id}', [BarangController::class, 'sub_barang'])->name('barang.sub_barang')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/barang/rekap-barang/{id}', [BarangController::class, 'rekap_barang'])->name('barang.rekap_barang')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/barang/Hapus-sub-barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/barang/input-subbarang/{id}', [BarangController::class, 'input_subbarang'])->name('barang.input_subbarang')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/barang/Print-Kode/{id}', [BarangController::class, 'print_kode_barang'])->name('barang.print_kode')->middleware(['role:admin|STAF ADMIN']);

    Route::get('/Teknisi', [TeknisiController::class, 'index'])->name('teknisi.index')->middleware(['role:TEKNISI']);
    Route::post('/Teknisi/Job', [TeknisiController::class, 'job'])->name('teknisi.job')->middleware(['role:TEKNISI']);
    Route::get('/Teknisi/List-Aktivasi', [TeknisiController::class, 'list_aktivasi'])->name('teknisi.list_aktivasi')->middleware(['role:TEKNISI']);
    Route::get('/Teknisi/Aktivasi/{id}', [TeknisiController::class, 'aktivasi'])->name('teknisi.aktivasi')->middleware(['role:TEKNISI']);
    Route::put('/Teknisi/Proses-Aktivasi/{id}', [TeknisiController::class, 'proses_aktivasi'])->name('teknisi.proses_aktivasi')->middleware(['role:TEKNISI']);
    Route::get('/Teknisi/Teknisi-getBarang/{id}', [TeknisiController::class, 'getBarang'])->name('teknisi.getBarang')->middleware(['role:TEKNISI']);


    Route::get('/Invoice', [InvoiceController::class, 'index'])->name('inv.index')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/Paid', [InvoiceController::class, 'paid'])->name('inv.paid')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/Invoice/Detail/{id}', [InvoiceController::class, 'sub_invoice'])->name('inv.sub_invoice')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/Invoice/Payment/{id}', [InvoiceController::class, 'payment'])->name('inv.payment')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/Invoice/addons/{id}', [InvoiceController::class, 'addons'])->name('inv.addons')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/Invoice/diskon/{id}', [InvoiceController::class, 'addDiskon'])->name('inv.addDiskon')->middleware(['role:admin|STAF ADMIN']);
    Route::delete('/Invoice/addons-hapus/{id}/{inv}/{tot}', [InvoiceController::class, 'addons_delete'])->name('inv.addons_delete')->middleware(['role:admin|STAF ADMIN']);


    Route::get('/Sales/index', [SalesController::class, 'index'])->name('sales.index')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/Sales/list', [SalesController::class, 'list_registrasi'])->name('sales.list_registrasi')->middleware(['role:admin|STAF ADMIN']);
})->middleware(['role:admin|STAF ADMIN']);

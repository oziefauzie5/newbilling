<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\Applikasi\AppController;
use App\Http\Controllers\Barang\BarangController;
use App\Http\Controllers\Barang\KategoriController;
use App\Http\Controllers\Barang\SupplierControoler;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Hotspot\HotspotController;
use App\Http\Controllers\Hotspot\TitikvhcController;
use App\Http\Controllers\Mitra\BillerController;
use App\Http\Controllers\Mitra\MitraController;
use App\Http\Controllers\NOC\NocController;
use App\Http\Controllers\Pelanggan\LoginPelangganController;
use App\Http\Controllers\Pelanggan\PelangganController;
use App\Http\Controllers\PSB\PsbController;
use App\Http\Controllers\PSB\RegistrasiApiController;
use App\Http\Controllers\PSB\RegistrasiController;
use App\Http\Controllers\PSB\SementaraMigrasiController;
use App\Http\Controllers\Router\ExportExcel;
use App\Http\Controllers\Router\PaketController;
use App\Http\Controllers\Router\PaketVoucherController;
use App\Http\Controllers\Router\RouterController;
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\Teknisi\TeknisiController;
use App\Http\Controllers\Tiket\TiketController;
use App\Http\Controllers\Transaksi\CallbackController;
use App\Http\Controllers\Transaksi\GenerateInvoice;
use App\Http\Controllers\Transaksi\InvoiceController;
use App\Http\Controllers\Transaksi\LaporanController;
use App\Http\Controllers\Transaksi\TransaksiController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Whatsapp\WhatsappApi;
use App\Http\Controllers\Whatsapp\WhatsappController;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\Transaksi;
use Illuminate\Support\Facades\Route;

Route::get('/adminapp', [LoginController::class, 'index'])->name('adminapp');
Route::post('/login-proses', [LoginController::class, 'login_proses'])->name('login-proses');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', [LoginPelangganController::class, 'index'])->name('login_pelanggan');
Route::post('/proses-login', [LoginPelangganController::class, 'login_proses'])->name('proses-login');
Route::get('/logout-client', [LoginPelangganController::class, 'logout'])->name('logout_client');
Route::get('/Client-logout', [LoginPelangganController::class, 'client_logout'])->name('client_logout');

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
    Route::post('/setting/app-tripay', [AppController::class, 'tripay_store'])->name('app.tripay_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/setting/applikasi', [AppController::class, 'aplikasi_store'])->name('app.aplikasi_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/setting/app-biaya', [AppController::class, 'biaya_store'])->name('app.biaya_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/setting/app-waktu', [AppController::class, 'waktu_store'])->name('app.waktu_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/setting/app-whatsapp', [AppController::class, 'whatsapp_store'])->name('app.whatsapp_store')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::get('/router', [RouterController::class, 'index'])->name('router.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/router/store', [RouterController::class, 'store'])->name('router.store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/router/edit/{id}', [RouterController::class, 'edit'])->name('router.edit')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::delete('/router/{id}/delete', [RouterController::class, 'delete_router'])->name('router.delete_router')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/cek/{id}', [RouterController::class, 'cekRouter'])->name('router.cekRouter')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/{id}/pppoe', [RouterController::class, 'getPppoe'])->name('router.getPppoe')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/{id}/hotspot', [RouterController::class, 'getHotspot'])->name('router.getHotspot')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/{id}/{ip}', [RouterController::class, 'router_remote'])->name('router.router_remote')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/{id}/{idmik}/kick', [RouterController::class, 'kick_hotspot'])->name('router.kick_hotspot')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::post('/router/paket/vhc', [PaketVoucherController::class, 'store'])->name('router.vhc.store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/titik-vhc', [TitikvhcController::class, 'titik_vhc'])->name('vhc.titik_vhc')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/titik-vhc-regist', [TitikvhcController::class, 'regist_titik'])->name('vhc.regist_titik')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/titik-vhc-store', [TitikvhcController::class, 'store_titik'])->name('vhc.store_titik')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::get('/router/paket', [PaketController::class, 'index'])->name('router.paket.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/create', [PaketController::class, 'create'])->name('router.paket.create')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/paket/{id}/get', [PaketController::class, 'getRouter'])->name('router.paket.getRouter')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/router/paket/store', [PaketController::class, 'store'])->name('router.paket.store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/router/paket/store isolir', [PaketController::class, 'store_isolir'])->name('router.paket.store_isolir')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/router/paket/{id}/update', [PaketController::class, 'update'])->name('router.paket.update')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/router/paket/export', [PaketController::class, 'exportPaketToMikrotik'])->name('router.paket.exportPaketToMikrotik')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::get('/noc', [NocController::class, 'index'])->name('noc.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/noc/{id}/Pengecekan', [NocController::class, 'pengecekan'])->name('noc.pengecekan')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/noc/{id}/Pengecekan-Done', [NocController::class, 'pengecekan_put'])->name('noc.pengecekan_put')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/noc/{id}/upload', [NocController::class, 'upload'])->name('noc.upload')->middleware(['role:admin|NOC|STAF ADMIN']);


    Route::get('/hotspot', [HotspotController::class, 'index'])->name('vhc.index')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::get('/pelanggan', [PsbController::class, 'index'])->name('psb.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/putus-langganan', [PsbController::class, 'listputus_langganan'])->name('psb.listputus_langganan')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/pelanggan/sambung-kembali/{idpel}', [RegistrasiController::class, 'sambung_kembali'])->name('psb.sambung_kembali')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/List-Input-Data', [PsbController::class, 'list_input'])->name('psb.list_input')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/pelanggan/Validasi/{ktp}', [PsbController::class, 'storeValidateKtp'])->name('psb.storeValidateKtp')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/pelanggan/Input-Data', [PsbController::class, 'store'])->name('psb.store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/{id}/ShowEdit-Input-Data', [PsbController::class, 'edit_inputdata'])->name('psb.edit_inputdata')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/pelanggan/Input-Import', [PsbController::class, 'input_data_import'])->name('psb.input_data_import')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/pelanggan/Input-Data_update', [PsbController::class, 'input_data_update'])->name('psb.input_data_update')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/Registrasi', [RegistrasiController::class, 'index'])->name('reg.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::delete('/pelanggan/Delete/{id}/Registrasi', [RegistrasiController::class, 'delete_registrasi'])->name('reg.delete_registrasi')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/berita_acara', [RegistrasiController::class, 'berita_acara'])->name('psb.berita_acara')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/{id}/Print-Berita_Acara', [RegistrasiController::class, 'print_berita_acara'])->name('psb.print_berita_acara')->middleware(['role:admin|NOC|STAF ADMIN']);
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
    Route::get('/Transaksi', [TransaksiController::class, 'index'])->name('inv.trx.index')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::get('/pelanggan/sementara_migrasi', [SementaraMigrasiController::class, 'sementara_migrasi'])->name('reg.sementara_migrasi')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/pelanggan/sementara_migrasi-Store', [SementaraMigrasiController::class, 'store_sementara_migrasi'])->name('reg.store_sementara_migrasi')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::get('/pelanggan/Registrasi-cari/{id}', [RegistrasiController::class, 'pilih_pelanggan_registrasi'])->name('reg.pilih_pelanggan_registrasi')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/pelanggan/Registrasi-Store', [RegistrasiController::class, 'store'])->name('reg.store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/Validasi1/{id}', [RegistrasiController::class, 'validasi_pachcore'])->name('reg.validasi_pachcore')->middleware(['role:admin|NOC|TEKNISI|STAF ADMIN']);
    Route::get('/pelanggan/Validasi2/{id}', [RegistrasiController::class, 'validasi_adaptor'])->name('reg.validasi_adaptor')->middleware(['role:admin|NOC|TEKNISI|STAF ADMIN']);
    Route::get('/pelanggan/Validasi3/{id}', [RegistrasiController::class, 'validasi_ont'])->name('reg.validasi_ont')->middleware(['role:admin|NOC|TEKNISI|STAF ADMIN']);
    Route::get('/pelanggan/get-paket{id}', [RegistrasiController::class, 'getPaket'])->name('reg.getPaket')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/pelanggan/Registrasi-Import', [RegistrasiController::class, 'registrasi_import'])->name('reg.registrasi_import')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/pelanggan/pb/{idpel}', [RegistrasiController::class, 'putus_berlanggan'])->name('psb.putus_berlanggan')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/Redirect/{id}', [RegistrasiApiController::class, 'registrasi_api'])->name('reg.registrasi_api')->middleware(['role:admin|NOC|STAF ADMIN']);
    // Route::get('/pelanggan/Redirect-Edit/{id}', [RegistrasiApiController::class, 'edit_registrasi_api'])->name('reg.edit_registrasi_api')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/Edit/{id}/Pelanggan', [PsbController::class, 'edit_pelanggan'])->name('psb.edit_pelanggan')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/Edit/{id}/Cek Status', [NocController::class, 'status_inet'])->name('noc.status_inet')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/{id}/isolir-manual', [NocController::class, 'isolir_manual'])->name('noc.isolir_manual')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/{id}/kick', [NocController::class, 'kick'])->name('noc.kick')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/{id}/buka-isolir-manual', [NocController::class, 'buka_isolir_manual'])->name('noc.buka_isolir_manual')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/status_secret/{id}', [NocController::class, 'status_secret'])->name('noc.status_secret')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/pelanggan/Update/{id}/Pelanggan', [RegistrasiApiController::class, 'update_pelanggan'])->name('psb.update_pelanggan')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/pelanggan/Update-Profile/{id}', [RegistrasiApiController::class, 'update_profile'])->name('psb.update_profile')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/pelanggan/Update-Router/{id}', [RegistrasiApiController::class, 'update_router'])->name('psb.update_router')->middleware(['role:admin|NOC|STAF ADMIN']);
    // Route::get('/pelanggan/edit-validasi-ont/{id}', [RegistrasiController::class, 'edit_validasi_ont'])->name('reg.edit_validasi_ont')->middleware(['role:admin|NOC|STAF ADMIN']);



    Route::post('/supplier/Tambah-supplier', [SupplierControoler::class, 'store'])->name('supplier.store')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/kategori/Tambah-kategori', [KategoriController::class, 'store'])->name('kategori.store')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/barang/Tambah-barang', [BarangController::class, 'store'])->name('barang.store')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/barang/Tambah-subbarang', [BarangController::class, 'store_subbarang'])->name('barang.store_subbarang')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/barang/Edit/{id}', [BarangController::class, 'edit'])->name('barang.edit')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/barang/sub-barang/{id}', [BarangController::class, 'sub_barang'])->name('barang.sub_barang')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/barang/rekap-barang/{id}', [BarangController::class, 'rekap_barang'])->name('barang.rekap_barang')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/barang/Hapus/{id}', [BarangController::class, 'destroy'])->name('barang.destroy')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/barang/Hapus-sub-barang/{id}', [BarangController::class, 'destroy_subbarang'])->name('barang.destroy_subbarang')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/barang/input-subbarang/{id}', [BarangController::class, 'input_subbarang'])->name('barang.input_subbarang')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/barang/Print-Kode/{id}', [BarangController::class, 'print_kode_barang'])->name('barang.print_kode')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/barang/pilih/{id}', [BarangController::class, 'cari_barang'])->name('barang.cari_barang')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/barang/barang-keluar', [BarangController::class, 'barang_keluar'])->name('barang.barang_keluar')->middleware(['role:admin|STAF ADMIN']);

    Route::get('/teknisi', [TeknisiController::class, 'index'])->name('teknisi.index')->middleware(['role:TEKNISI']);
    Route::post('/teknisi/job', [TeknisiController::class, 'job'])->name('teknisi.job')->middleware(['role:TEKNISI']);
    Route::post('/teknisi/tiket-job', [TeknisiController::class, 'job_tiket'])->name('teknisi.job_tiket')->middleware(['role:TEKNISI']);
    Route::get('/teknisi/tiket/details/{id}', [TeknisiController::class, 'details'])->name('teknisi.tiket.details')->middleware(['role:TEKNISI']);
    Route::put('/teknisi/tiket/close/{id}', [TeknisiController::class, 'close_tiket'])->name('teknisi.tiket.close_tiket')->middleware(['role:TEKNISI']);
    Route::get('/teknisi/tiket/{tiket_id}', [TeknisiController::class, 'update_tiket'])->name('teknisi.update_tiket')->middleware(['role:TEKNISI']);
    Route::get('/teknisi/list-aktivasi', [TeknisiController::class, 'list_aktivasi'])->name('teknisi.list_aktivasi')->middleware(['role:TEKNISI']);
    Route::get('/teknisi/list-tiket', [TeknisiController::class, 'list_tiket'])->name('teknisi.list_tiket')->middleware(['role:TEKNISI']);
    Route::get('/teknisi/Aktivasi/{id}', [TeknisiController::class, 'aktivasi'])->name('teknisi.aktivasi')->middleware(['role:TEKNISI']);
    Route::put('/teknisi/Proses-Aktivasi/{id}', [TeknisiController::class, 'proses_aktivasi'])->name('teknisi.proses_aktivasi')->middleware(['role:TEKNISI']);
    Route::get('/teknisi/teknisi-getBarang/{id}', [TeknisiController::class, 'getBarang'])->name('teknisi.getBarang')->middleware(['role:TEKNISI']);


    Route::get('/invoice', [InvoiceController::class, 'index'])->name('inv.index')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/invoice/rolback/{id}', [InvoiceController::class, 'rollback'])->name('inv.rollback')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/invoice/generate', [GenerateInvoice::class, 'generate_invoice'])->name('inv.generate_invoice')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/paid', [InvoiceController::class, 'paid'])->name('inv.paid')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/invoice/detail/{id}', [InvoiceController::class, 'sub_invoice'])->name('inv.sub_invoice')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/invoice/payment/{id}', [InvoiceController::class, 'payment'])->name('inv.payment')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/invoice/addons/{id}', [InvoiceController::class, 'addons'])->name('inv.addons')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/invoice/diskon/{id}', [InvoiceController::class, 'addDiskon'])->name('inv.addDiskon')->middleware(['role:admin|STAF ADMIN']);
    Route::delete('/invoice/addons-hapus/{id}/{inv}/{tot}', [InvoiceController::class, 'addons_delete'])->name('inv.addons_delete')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/test', [InvoiceController::class, 'suspand_otomatis'])->name('inv.suspand_otomatis')->middleware(['role:admin|STAF ADMIN']);

    Route::get('/mitra', [MitraController::class, 'index'])->name('mitra.index')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/mitra/Create', [MitraController::class, 'create'])->name('mitra.create')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/mitra/Add-Mitra', [MitraController::class, 'addmitra'])->name('mitra.addmitra')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/mitra/data/{id}', [MitraController::class, 'data'])->name('mitra.data')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/mitra/edit/{id}', [MitraController::class, 'edit'])->name('mitra.edit')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/mitra/store/edit/{id}', [MitraController::class, 'store_edit'])->name('mitra.store_edit')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/mitra/data/top-up/{id}', [MitraController::class, 'topup'])->name('mitra.topup')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/mitra/data/debet-saldo/{id}', [MitraController::class, 'debet_saldo'])->name('mitra.debet_saldo')->middleware(['role:admin|STAF ADMIN']);

    Route::get('/biller/index', [BillerController::class, 'index'])->name('biller.index')->middleware(['role:KOLEKTOR|BILLER']);
    Route::get('/biller/tagihan', [BillerController::class, 'list_tagihan'])->name('biller.list_tagihan')->middleware(['role:KOLEKTOR|BILLER']);
    Route::get('/biller/pembayaran', [BillerController::class, 'pembayaran'])->name('biller.pembayaran')->middleware(['role:KOLEKTOR|BILLER']);
    Route::get('/biller/data-pembayaran/{id}', [BillerController::class, 'getpelanggan'])->name('biller.getpelanggan')->middleware(['role:KOLEKTOR|BILLER']);
    Route::get('/biller/payment', [BillerController::class, 'payment'])->name('biller.payment')->middleware(['role:KOLEKTOR|BILLER']);
    Route::get('/biller/payment-tagihan/{inv_id}', [BillerController::class, 'paymentbytagihan'])->name('biller.paymentbytagihan')->middleware(['role:KOLEKTOR|BILLER']);
    Route::get('/biller/pembayaran/{id}', [BillerController::class, 'bayar'])->name('biller.bayar')->middleware(['role:KOLEKTOR|BILLER']);
    Route::get('/biller/data-lunas/{id}', [BillerController::class, 'getDataLunas'])->name('biller.getDataLunas')->middleware(['role:KOLEKTOR|BILLER']);
    Route::get('/biller/mutasi', [BillerController::class, 'mutasi'])->name('biller.mutasi')->middleware(['role:KOLEKTOR|BILLER']);
    // Route::get('/biller/daftar/transaksi', [BillerController::class, 'list_trx'])->name('biller.list_trx')->middleware(['role:KOLEKTOR|BILLER']);
    Route::post('/biller/export/pdf', [BillerController::class, 'mutasi_pdf'])->name('biller.export.mutasi')->middleware(['role:KOLEKTOR|BILLER']);
    Route::get('/biller/print/{id}', [BillerController::class, 'print'])->name('biller.print')->middleware(['role:KOLEKTOR|BILLER']);
    Route::put('/biller/pb/{idpel}', [BillerController::class, 'biller_putus_berlanggan'])->name('biller.biller_putus_berlanggan')->middleware(['role:KOLEKTOR']);

    // Route::get('/Kolektor/Tagihan', [KolektorController::class, 'index'])->name('kolektor.index');
    // Route::get('/Kolektor/kolektor-payment/{id}', [KolektorController::class, 'kolektor_payment'])->name('kolektor.kolektor_payment');
    Route::get('/whatsapp/update-group', [WhatsappApi::class, 'update_group_list'])->name('whatsapp.update_group_list')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/whatsapp/send-message', [WhatsappApi::class, 'send_message'])->name('whatsapp.send_message')->middleware(['role:admin|STAF ADMIN']);

    Route::get('/tiket', [TiketController::class, 'index'])->name('tiket.index')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/tiket/{id}', [TiketController::class, 'details'])->name('tiket.details')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/tiket/store', [TiketController::class, 'store'])->name('tiket.store')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/tiket/{id}/cari', [TiketController::class, 'pilih_pelanggan'])->name('tiket.pilih_pelanggan')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::get('/sales/index', [SalesController::class, 'index'])->name('sales.index')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/sales/list', [SalesController::class, 'list_registrasi'])->name('sales.list_registrasi')->middleware(['role:admin|STAF ADMIN']);


    Route::get('/whatsapp/pesan', [WhatsappController::class, 'index'])->name('wa.index')->middleware(['role:admin|STAF ADMIN']);
})->middleware(['role:admin|STAF ADMIN']);

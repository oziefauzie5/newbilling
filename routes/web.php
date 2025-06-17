<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\Applikasi\AppController;
use App\Http\Controllers\Global\GlobalController;
use App\Http\Controllers\Gudang\GudangController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Hotspot\HotspotController;
use App\Http\Controllers\Hotspot\TitikvhcController;
use App\Http\Controllers\Hotspot\PostVoucherController;
use App\Http\Controllers\ImportExport\ExportController;
use App\Http\Controllers\Mitra\BillerController;
use App\Http\Controllers\Mitra\MitraController;
use App\Http\Controllers\Mitra\SalesController;
use App\Http\Controllers\NOC\NocController;
use App\Http\Controllers\Pelanggan\LoginPelangganController;
use App\Http\Controllers\Pelanggan\PelangganController;
use App\Http\Controllers\PSB\PsbController;
use App\Http\Controllers\PSB\RegistrasiApiController;
use App\Http\Controllers\PSB\RegistrasiController;
use App\Http\Controllers\Router\PaketController;
use App\Http\Controllers\Router\PaketVoucherController;
use App\Http\Controllers\Router\RouterController;

use App\Http\Controllers\Teknisi\TeknisiController;
use App\Http\Controllers\Teknisi\TopologiController;
use App\Http\Controllers\Tiket\TiketController;
use App\Http\Controllers\Transaksi\CallbackController;
use App\Http\Controllers\Transaksi\GenerateInvoice;
use App\Http\Controllers\Transaksi\InvoiceController;
use App\Http\Controllers\Transaksi\LaporanController;
use App\Http\Controllers\Transaksi\TransaksiController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Whatsapp\WhatsappApi;
use App\Http\Controllers\Whatsapp\WhatsappController;
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

// Route::group(['prefix' => 'client', 'middleware' => ['auth:sales'], 'as' => 'sales.'], function () {
    //     Route::get('/home', [PelangganController::class, 'index'])->name('index');
    // });
    
    Route::post('/callback', [CallbackController::class, 'handle']);
    Route::post('/voucher', [PostVoucherController::class, 'handle']);
    
    
    Route::group(['prefix' => 'admin', 'middleware' => ['auth:web'], 'as' => 'admin.'], function () {
        
        Route::get('/home', [HomeController::class, 'home'])->name('home')->middleware(['role:admin|NOC|STAF ADMIN|KEUANGAN']);
        
        
        ##EXPORT
        Route::get('/input-export', [ExportController::class, 'export_input_data'])->name('export.export_input_data')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/input-import', [ExportController::class, 'import_input_data'])->name('export.import_input_data')->middleware(['role:admin|STAF ADMIN']);
        Route::get('/regist-export', [ExportController::class, 'export_registrasi'])->name('export.export_registrasi')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/regist-import', [ExportController::class, 'import_registrasi'])->name('export.import_registrasi')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/pop-import', [ExportController::class, 'import_pop'])->name('export.import_pop')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/olt-import', [ExportController::class, 'import_olt'])->name('export.import_olt')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/odc-import', [ExportController::class, 'import_odc'])->name('export.import_odc')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/odp-import', [ExportController::class, 'import_odp'])->name('export.import_odp')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/paket-import', [ExportController::class, 'import_paket'])->name('export.import_paket')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/instalsi-import', [ExportController::class, 'import_instalasi'])->name('export.import_instalasi')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/fee-import', [ExportController::class, 'import_fee'])->name('export.import_fee')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/barang-import', [ExportController::class, 'barang_import'])->name('export.barang_import')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/barang-keluar-import', [ExportController::class, 'barang_keluar_import'])->name('export.barang_keluar_import')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/Invoice-keluar-import', [ExportController::class, 'invoice_import'])->name('export.invoice_import')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/SubInvoice-import', [ExportController::class, 'Subinvoice_import'])->name('export.Subinvoice_import')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/SubInvoice-import_teknisi', [ExportController::class, 'import_teknisi'])->name('export.import_teknisi')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/SubInvoice-import_laporan', [ExportController::class, 'import_laporan'])->name('export.import_laporan')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/SubInvoice-import_mutasi', [ExportController::class, 'import_mutasi'])->name('export.import_mutasi')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/SubInvoice-import_akun', [ExportController::class, 'import_akun'])->name('export.import_akun')->middleware(['role:admin|STAF ADMIN']);
        Route::post('/SubInvoice-import_jurnal', [ExportController::class, 'import_jurnal'])->name('export.import_jurnal')->middleware(['role:admin|STAF ADMIN']);
 Route::post('/import_user', [ExportController::class, 'import_user'])->name('export.import_user')->middleware(['role:admin|STAF ADMIN']);
 Route::post('/import_kategori', [ExportController::class, 'import_kategori'])->name('export.import_kategori')->middleware(['role:admin|STAF ADMIN']);
 Route::post('/import_mitra', [ExportController::class, 'import_mitra'])->name('export.import_mitra')->middleware(['role:admin|STAF ADMIN']);
 Route::post('/import_tiket', [ExportController::class, 'import_tiket'])->name('export.import_tiket')->middleware(['role:admin|STAF ADMIN']);
    
    ##CRUD DATA USER
    Route::get('/user', [UserController::class, 'index'])->name('user.index')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit')->middleware(['role:admin']);

    Route::get('/setting', [AppController::class, 'index'])->name('app.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/setting/app', [AppController::class, 'akun_store'])->name('app.akun_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/setting/app', [AppController::class, 'site'])->name('app.site')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/setting/app/store-site', [AppController::class, 'site_store'])->name('app.site_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/setting/app/update-site/{id}', [AppController::class, 'update_site'])->name('app.update_site')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/setting/{id}/app-akun-edit', [AppController::class, 'akun_edit'])->name('app.akun_edit')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::delete('/setting/{id}/app-akun-delete', [AppController::class, 'akun_delete'])->name('app.akun_delete')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/setting/app-tripay', [AppController::class, 'tripay_store'])->name('app.tripay_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/setting/applikasi', [AppController::class, 'aplikasi_store'])->name('app.aplikasi_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/setting/app-biaya', [AppController::class, 'biaya_store'])->name('app.biaya_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/setting/app-waktu', [AppController::class, 'waktu_store'])->name('app.waktu_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/setting/app-whatsapp', [AppController::class, 'whatsapp_store'])->name('wa.whatsapp_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/setting/wa-getewai', [AppController::class, 'wa_getewai'])->name('app.wa_getewai')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/setting/wa-getewai-store', [AppController::class, 'store_wa_getewai'])->name('app.store_wa_getewai')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/setting/{id}/wa-getewai-update', [AppController::class, 'update_wa_getewai'])->name('app.update_wa_getewai')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/setting/kendaraan', [AppController::class, 'kendaraan'])->name('app.kendaraan')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/setting/add-Kendaraan', [AppController::class, 'store_kendaraan'])->name('app.store_kendaraan')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/setting/{id}/update-Kendaraan', [AppController::class, 'update_kendaraan'])->name('app.update_kendaraan')->middleware(['role:admin|STAF ADMIN']);
    Route::delete('/setting/{id}/delete-Kendaraan', [AppController::class, 'delete_kendaraan'])->name('app.delete_kendaraan')->middleware(['role:admin|STAF ADMIN']);
    
    Route::get('/router', [RouterController::class, 'index'])->name('topo.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/router/store', [RouterController::class, 'store'])->name('topo.store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/router/edit/{id}', [RouterController::class, 'edit'])->name('topo.edit')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::delete('/router/{id}/delete', [RouterController::class, 'delete_router'])->name('topo.delete_router')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/cek/{id}', [RouterController::class, 'cekRouter'])->name('topo.cekRouter')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/{id}/pppoe', [RouterController::class, 'getPppoe'])->name('topo.getPppoe')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/{id}/hotspot', [RouterController::class, 'getHotspot'])->name('topo.getHotspot')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/{id}/{ip}', [RouterController::class, 'router_remote'])->name('topo.router_remote')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/{id}/{idmik}/kick', [RouterController::class, 'kick_hotspot'])->name('topo.kick_hotspot')->middleware(['role:admin|NOC|STAF ADMIN']);

    Route::post('/router/paket/vhc', [PaketVoucherController::class, 'store'])->name('router.vhc.store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/titik-vhc', [TitikvhcController::class, 'titik_vhc'])->name('vhc.titik_vhc')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/titik-vhc-regist', [TitikvhcController::class, 'regist_titik'])->name('vhc.regist_titik')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/titik-vhc-store', [TitikvhcController::class, 'store_titik'])->name('vhc.store_titik')->middleware(['role:admin|NOC|STAF ADMIN']);
    

    ##--PAKET INTERNET
    Route::get('/router/paket', [PaketController::class, 'index'])->name('router.noc.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    // Route::get('/router/create', [PaketController::class, 'create'])->name('router.noc.create')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/router/paket/{id}/get', [PaketController::class, 'getRouter'])->name('router.noc.getRouter')->middleware(['role:admin|NOC']);
    Route::post('/router/paket/store', [PaketController::class, 'store'])->name('router.noc.store')->middleware(['role:admin|NOC']);
    Route::post('/router/paket/store isolir', [PaketController::class, 'store_isolir'])->name('router.noc.store_isolir')->middleware(['role:admin|NOC']);
    Route::post('/router/paket/{id}/update', [PaketController::class, 'update'])->name('router.noc.update')->middleware(['role:admin|NOC']);
    Route::post('/router/paket/export', [PaketController::class, 'exportPaketToMikrotik'])->name('router.noc.exportPaketToMikrotik')->middleware(['role:admin|NOC']);
    Route::get('/router/paket-harga', [PaketController::class, 'paket_harga'])->name('router.paket_harga')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/router/{id}/update-paket-harga', [PaketController::class, 'update_harga_paket'])->name('router.update_harga_paket')->middleware(['role:admin|STAF ADMIN']);
    
    // Route::get('/noc', [NocController::class, 'index'])->name('noc.index')->middleware(['role:admin|NOC|STAF ADMIN']); #HAPUS
    Route::get('/noc/{id}/Pengecekan', [NocController::class, 'pengecekan'])->name('noc.pengecekan')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/noc/Pengecekan-barang', [NocController::class, 'pengecekan_barang'])->name('noc.pengecekan_barang')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/barang/update-subbarang-status/{id}', [NocController::class, 'update_status_barang'])->name('barang.update_status_barang')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/noc/{id}/Pengecekan-Done', [NocController::class, 'pengecekan_put'])->name('noc.pengecekan_put')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/noc/{id}/upload', [NocController::class, 'upload'])->name('noc.upload')->middleware(['role:admin|NOC|STAF ADMIN']);
    
    Route::get('/topologi/pop', [TopologiController::class, 'pop'])->name('topo.pop')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/topologi/store', [TopologiController::class, 'pop_store'])->name('topo.pop_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/topologi/update-pop/{id}', [TopologiController::class, 'update_pop'])->name('topo.update_pop')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/topologi/olt', [TopologiController::class, 'olt'])->name('topo.olt')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/topologi/store-olt', [TopologiController::class, 'olt_store'])->name('topo.olt_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/topologi/update-olt/{id}', [TopologiController::class, 'update_olt'])->name('topo.update_olt')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/topologi/{id}/pilih-barang-tiket', [TopologiController::class, 'pilih_barang_tiket'])->name('topo.pilih_barang_tiket')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/topologi/odc', [TopologiController::class, 'odc'])->name('topo.odc')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/topologi/store-odc', [TopologiController::class, 'odc_store'])->name('topo.odc_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/topologi/update-odc/{id}', [TopologiController::class, 'update_odc'])->name('topo.update_odc')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/topologi/odp', [TopologiController::class, 'odp'])->name('topo.odp')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/topologi/store-odp', [TopologiController::class, 'odp_store'])->name('topo.odp_store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/topologi/odp/{id}/instalasi', [TopologiController::class, 'odp_instalasi'])->name('topo.odp_instalasi')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/topologi/odp/{id}/list', [TopologiController::class, 'odp_list'])->name('topo.odp_list')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/topologi/{id}/update-odp', [TopologiController::class, 'update_odp'])->name('topo.update_odp')->middleware(['role:admin|NOC|STAF ADMIN']);
    
    
    Route::get('/hotspot', [HotspotController::class, 'index'])->name('vhc.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    
    #--FTTH
    Route::get('/ftth', [PsbController::class, 'ftth'])->name('psb.ftth')->middleware(['role:admin|NOC|STAF ADMIN|KEUANGAN']);
    Route::get('/ftth/input-data', [PsbController::class, 'input_data'])->name('psb.input_data')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/ftth/{id}/input-data-update-view', [PsbController::class, 'input_data_update_view'])->name('psb.input_data_update_view')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/ftth/{id}/input-data_update', [PsbController::class, 'input_data_update'])->name('psb.input_data_update')->middleware(['role:admin|NOC|STAF ADMIN']); 
    Route::get('/ftth/promo', [PsbController::class, 'kode_promo'])->name('psb.kode_promo')->middleware(['role:admin|STAF ADMIN']); 
    Route::post('/ftth/promo-create', [PsbController::class, 'store_promo'])->name('psb.store_promo')->middleware(['role:admin|STAF ADMIN']); 
    Route::put('/ftth/{id}/promo->update', [PsbController::class, 'update_promo'])->name('psb.update_promo')->middleware(['role:admin|STAF ADMIN']); 
    
    ##--REGISTRASI
    Route::get('/ftth/getMitra', [RegistrasiController::class, 'getMitra'])->name('reg.getMitra')->middleware(['role:admin|NOC|STAF ADMIN']); 
    Route::get('/ftth/{id}/getMitraSub', [RegistrasiController::class, 'getMitraSub'])->name('reg.getMitraSub')->middleware(['role:admin|NOC|STAF ADMIN']); 
    Route::get('/ftth/{id}/getMitraSubfee', [RegistrasiController::class, 'getMitraSubfee'])->name('reg.getMitraSubfee')->middleware(['role:admin|NOC|STAF ADMIN']); 
    Route::get('/ftth/{id}/val-odp', [RegistrasiController::class, 'aktivasi_validasi_odp'])->name('reg.aktivasi_validasi_odp')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/ftth/{id}/validasiBarang', [RegistrasiController::class, 'validasiBarang'])->name('reg.validasiBarang')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/ftth/{id}/cek_invoice', [RegistrasiController::class, 'cek_invoice'])->name('reg.cek_invoice')->middleware(['role:admin|NOC|STAF ADMIN|KEUANGAN']);
    
    

    
    // Route::post('/pelanggan/export-excel', [PsbController::class, 'export_excel'])->name('reg.export_excel')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/putus-langganan', [PsbController::class, 'listputus_langganan'])->name('psb.listputus_langganan')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/mac_bermaslah', [PsbController::class, 'listmac_bermasalah'])->name('psb.listmac_bermasalah')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/pelanggan/update-mac_bermaslah/{idpel}', [PsbController::class, 'update_mac'])->name('psb.update_mac')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/pelanggan/sambung-kembali/{idpel}', [RegistrasiController::class, 'sambung_kembali'])->name('psb.sambung_kembali')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/List-Input-Data', [PsbController::class, 'list_input'])->name('psb.list_input')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/pelanggan/Validasi/{ktp}', [PsbController::class, 'storeValidateKtp'])->name('psb.storeValidateKtp')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/pelanggan/Input-Data', [PsbController::class, 'store'])->name('psb.store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/{id}/ShowEdit-Input-Data', [PsbController::class, 'edit_inputdata'])->name('psb.edit_inputdata')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/Registrasi', [RegistrasiController::class, 'index'])->name('reg.index')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/get/{id}/site', [GlobalController::class, 'getSite'])->name('reg.getSite')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/get/{id}/pop', [GlobalController::class, 'getPop'])->name('reg.getPop')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/get/{id}/olt', [GlobalController::class, 'getOlt'])->name('reg.getOlt')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/get/{id}/odc', [GlobalController::class, 'getOdc'])->name('reg.getOdc')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/get/{id}/odp', [GlobalController::class, 'getOdp'])->name('reg.getOdp')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/get/{id}/val-kabel', [GlobalController::class, 'validasi_kode_kabel'])->name('reg.validasi_kode_kabel')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/get/{id}/kode-site', [GlobalController::class, 'getKodeSite'])->name('reg.getKodeSite')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::delete('/pelanggan/Delete/{id}/Registrasi', [RegistrasiController::class, 'delete_registrasi'])->name('reg.delete_registrasi')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/berita_acara', [RegistrasiController::class, 'berita_acara'])->name('psb.berita_acara')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/{id}/Print-Berita_Acara', [RegistrasiController::class, 'print_berita_acara'])->name('psb.print_berita_acara')->middleware(['role:admin|NOC|STAF ADMIN']);
    // ------------PENCAIRAN PSB-----------------
    Route::get('/Transaksi/{id}/bukti-kas-keluar', [RegistrasiController::class, 'bukti_kas_keluar'])->name('reg.bukti_kas_keluar')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/Transaksi/{id}/buat-laporan', [LaporanController::class, 'buat_laporan'])->name('inv.buat_laporan')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/Transaksi/{id}/serah-terima', [LaporanController::class, 'serah_terima'])->name('inv.serah_terima')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/Transaksi/topup', [LaporanController::class, 'topup'])->name('inv.topup')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/Transaksi/{id}/topup', [LaporanController::class, 'lap_topup'])->name('inv.lap_topup')->middleware(['role:admin|STAF ADMIN']);
    Route::delete('/Transaksi/{id}/Delete', [LaporanController::class, 'lap_delete'])->name('inv.lap_delete')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/Transaksi/data-Laporan', [LaporanController::class, 'data_laporan'])->name('inv.data_laporan')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::post('/Transaksi/add-pendapatan', [LaporanController::class, 'store_add_transaksi'])->name('lap.store_add_transaksi')->middleware(['role:admin|STAF ADMIN']);
    Route::delete('/Transaksi/{id}/Data-Laporan', [LaporanController::class, 'data_lap_delete'])->name('inv.data_lap_delete')->middleware(['role:admin|STAF ADMIN']);

    Route::get('/Transaksi/jurnal', [TransaksiController::class, 'jurnal'])->name('lap.jurnal')->middleware(['role:admin|NOC|STAF ADMIN|KEUANGAN']);
    Route::get('/Transaksi/{id}/jurnal-laporan', [TransaksiController::class, 'jurnal_laporan'])->name('lap.jurnal_laporan')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/Transaksi/reimbuse', [TransaksiController::class, 'store_jurnal_reimbuse'])->name('lap.store_jurnal_reimbuse')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/Transaksi/download-file/{id}', [TransaksiController::class, 'download_file'])->name('lap.download_file')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/Transaksi/pinjaman-karyawan', [TransaksiController::class, 'store_jurnal_kasbon'])->name('lap.store_jurnal_kasbon')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/Transaksi/topup-jurnal', [TransaksiController::class, 'store_topup_jurnal'])->name('lap.store_topup_jurnal')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/Transaksi/pinjaman', [TransaksiController::class, 'pinjaman'])->name('lap.pinjaman')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/Transaksi/jurnal-pencairan', [TransaksiController::class, 'store_jurnal_pencairan'])->name('lap.store_jurnal_pencairan')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/Transaksi/pencairan-fee', [TransaksiController::class, 'store_jurnal_fee_sales'])->name('lap.store_jurnal_fee_sales')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/Transaksi/tutup-buku', [TransaksiController::class, 'jurnal_tutup_buku'])->name('lap.jurnal_tutup_buku')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/Transaksi/jurnal_pengeluaran', [TransaksiController::class, 'store_jurnal_pengeluaran'])->name('lap.store_jurnal_pengeluaran')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/Transaksi/data-laporan', [TransaksiController::class, 'data_laporan_mingguan'])->name('lap.data_laporan_mingguan')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::get('/Transaksi/{id}/laporan-mingguan-print', [TransaksiController::class, 'jurnal_print'])->name('lap.jurnal_print')->middleware(['role:admin|STAF ADMIN']);
    
    Route::get('/pelanggan/Registrasi-cari/{id}', [RegistrasiController::class, 'pilih_pelanggan_registrasi'])->name('reg.pilih_pelanggan_registrasi')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/pelanggan/Registrasi-Store', [RegistrasiController::class, 'store'])->name('reg.store')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/Validasi1/{id}', [RegistrasiController::class, 'validasi_pachcore'])->name('reg.validasi_pachcore')->middleware(['role:admin|NOC|TEKNISI|STAF ADMIN']);
    Route::get('/pelanggan/Validasi2/{id}', [RegistrasiController::class, 'validasi_adaptor'])->name('reg.validasi_adaptor')->middleware(['role:admin|NOC|TEKNISI|STAF ADMIN']);
    Route::get('/pelanggan/Validasi3/{id}', [RegistrasiController::class, 'validasi_ont'])->name('reg.validasi_ont')->middleware(['role:admin|NOC|TEKNISI|STAF ADMIN']);
    Route::get('/pelanggan/get-paket{id}', [RegistrasiController::class, 'getPaket'])->name('reg.getPaket')->middleware(['role:admin|NOC|STAF ADMIN']);
    // Route::post('/pelanggan/Registrasi-Import', [RegistrasiController::class, 'registrasi_import'])->name('reg.registrasi_import')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/Redirect/{id}', [RegistrasiApiController::class, 'registrasi_api'])->name('reg.registrasi_api')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/Edit/{id}/Cek Status', [NocController::class, 'status_inet'])->name('noc.status_inet')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/{id}/isolir-manual', [NocController::class, 'isolir_manual'])->name('noc.isolir_manual')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/{id}/buka-isolir-manual', [NocController::class, 'buka_isolir_manual'])->name('noc.buka_isolir_manual')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/status_secret/{id}', [NocController::class, 'status_secret'])->name('noc.status_secret')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/ftth/aktivasi/{id}/Pelanggan', [RegistrasiController::class, 'proses_aktivasi_pelanggan'])->name('reg.proses_aktivasi_pelanggan')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/pelanggan/Update-Profile/{id}', [RegistrasiApiController::class, 'update_profile'])->name('psb.update_profile')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/pelanggan/get-update-tgl/{id}', [RegistrasiApiController::class, 'get_update_tgl_tempo'])->name('psb.get_update_tgl_tempo')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::put('/pelanggan/update-tgl/{id}', [RegistrasiApiController::class, 'update_tgl_jth_tempo'])->name('psb.update_tgl_jth_tempo')->middleware(['role:admin|NOC|STAF ADMIN']);
    
    
    // Route::get('/teknisi', [TeknisiController::class, 'index'])->name('teknisi.index')->middleware(['role:TEKNISI']);
    // Route::post('/teknisi/job', [TeknisiController::class, 'job'])->name('teknisi.job')->middleware(['role:TEKNISI']);
    // Route::post('/teknisi/tiket-job', [TeknisiController::class, 'job_tiket'])->name('teknisi.job_tiket')->middleware(['role:TEKNISI']);
    // Route::get('/teknisi/tiket/details/{id}', [TeknisiController::class, 'details'])->name('teknisi.tiket.details')->middleware(['role:TEKNISI']);
    // Route::put('/teknisi/tiket/close/{id}', [TeknisiController::class, 'close_tiket'])->name('teknisi.tiket.close_tiket')->middleware(['role:TEKNISI']);
    // Route::get('/teknisi/tiket/{tiket_id}', [TeknisiController::class, 'update_tiket'])->name('teknisi.update_tiket')->middleware(['role:TEKNISI']);
    // Route::get('/teknisi/list-aktivasi', [TeknisiController::class, 'list_aktivasi'])->name('teknisi.list_aktivasi')->middleware(['role:TEKNISI']);
    // Route::get('/teknisi/list-tiket', [TeknisiController::class, 'list_tiket'])->name('teknisi.list_tiket')->middleware(['role:TEKNISI']);
    // Route::get('/teknisi/Aktivasi/{id}', [TeknisiController::class, 'aktivasi'])->name('teknisi.aktivasi')->middleware(['role:TEKNISI']);
    // Route::put('/teknisi/Proses-Aktivasi/{id}', [TeknisiController::class, 'proses_aktivasi'])->name('teknisi.proses_aktivasi')->middleware(['role:TEKNISI']);
    Route::get('/teknisi/teknisi-getBarang/{id}', [TeknisiController::class, 'getBarang'])->name('teknisi.getBarang')->middleware(['role:TEKNISI']);
    
    
    Route::get('/invoice', [InvoiceController::class, 'index'])->name('inv.index')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::put('/invoice/rolback/{id}', [InvoiceController::class, 'rollback'])->name('inv.rollback')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::get('/invoice/generate', [GenerateInvoice::class, 'generate_invoice'])->name('inv.generate_invoice')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::get('/paid', [InvoiceController::class, 'paid'])->name('inv.paid')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::get('/invoice/detail/{id}', [InvoiceController::class, 'sub_invoice'])->name('inv.sub_invoice')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::put('/invoice/print/{id}', [InvoiceController::class, 'print_inv'])->name('inv.print_inv')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::put('/invoice/payment/{id}', [InvoiceController::class, 'payment'])->name('inv.payment')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::put('/invoice/addons/{id}', [InvoiceController::class, 'addons'])->name('inv.addons')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::put('/invoice/diskon/{id}', [InvoiceController::class, 'addDiskon'])->name('inv.addDiskon')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::delete('/invoice/addons-hapus/{id}/{inv}/{tot}', [InvoiceController::class, 'addons_delete'])->name('inv.addons_delete')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::put('/invoice/edit-inv/{inv_id}', [InvoiceController::class, 'update_inv'])->name('inv.update_inv')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::put('/invoice/edit-tgl_bayar/{inv_id}', [InvoiceController::class, 'update_tgl_bayar'])->name('inv.update_tgl_bayar')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::delete('/invoice/delete-inv/{inv_id}', [InvoiceController::class, 'delete_inv'])->name('inv.delete_inv')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::post('/invoice/add-inv-manual', [InvoiceController::class, 'add_inv_manual'])->name('inv.add_inv_manual')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::get('/suspend-manual', [InvoiceController::class, 'suspend_manual'])->name('inv.suspend_manual')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::get('/test2', [InvoiceController::class, 'test2'])->name('inv.test2')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    
    
    
    Route::get('/biller/maintenance', [BillerController::class, 'maintenance'])->name('biller.maintenance')->middleware(['role:KOLEKTOR|BILLER']);
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
    
    
    Route::get('/sales/sales', [SalesController::class, 'sales'])->name('sales.sales')->middleware(['role:SALES|PIC']);
    Route::get('/sales/input', [SalesController::class, 'sales_input'])->name('sales.sales_input')->middleware(['role:SALES|PIC']);
    Route::post('/sales/store', [SalesController::class, 'sales_store'])->name('sales.sales_store')->middleware(['role:SALES|PIC']);
    Route::get('/sales/pelanggan', [SalesController::class, 'pelanggan'])->name('sales.pelanggan')->middleware(['role:SALES|PIC']);
    Route::get('/sales/mutasi', [SalesController::class, 'mutasi_sales'])->name('sales.mutasi_sales')->middleware(['role:SALES|PIC']);
    Route::post('/sales/sales-export/pdf', [SalesController::class, 'mutasi_sales_pdf'])->name('sales.mutasi_sales')->middleware(['role:SALES|PIC']);
    Route::get('/sales/validasi-promo/{id}', [SalesController::class, 'validasi_kode_promo'])->name('sales.validasi_kode_promo')->middleware(['role:SALES|PIC']);
    
    

    Route::get('/whatsapp/update-group', [WhatsappApi::class, 'update_group_list'])->name('whatsapp.update_group_list')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/whatsapp/send-message', [WhatsappApi::class, 'send_message'])->name('whatsapp.send_message')->middleware(['role:admin|STAF ADMIN']);
    
    Route::get('/sales/index', [SalesController::class, 'index'])->name('sales.index')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/sales/list', [SalesController::class, 'list_registrasi'])->name('sales.list_registrasi')->middleware(['role:admin|STAF ADMIN']);
    
    Route::put('/whatsapp/{id}/app-whatsapp-manual', [WhatsappController::class, 'kirim_pesan_manual'])->name('wa.kirim_pesan_manual')->middleware(['role:admin|NOC|STAF ADMIN']);
    
    Route::get('/whatsapp/pesan', [WhatsappController::class, 'index'])->name('wa.index')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/whatsapp/broadcast', [WhatsappController::class, 'broadcast'])->name('whatsapp.broadcast')->middleware(['role:admin|STAF ADMIN']);
    Route::delete('/whatsapp/pesan-delete/{id}', [WhatsappController::class, 'delete_pesan'])->name('wa.delete_pesan')->middleware(['role:admin|STAF ADMIN']);
    
    ##--FTTH
    
    
    ##--MITRA
    Route::get('/mitra/pic-1', [MitraController::class, 'pic1_view'])->name('mitra.pic1_view')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/mitra/pic-1-addview', [MitraController::class, 'pic1_add_view'])->name('mitra.pic1_add_view')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/mitra/store-pic-1', [MitraController::class, 'store_pic1'])->name('mitra.store_pic1')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/mitra/{id}/pic-1-edit', [MitraController::class, 'pic1_edit_view'])->name('mitra.pic1_edit_view')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/mitra/{id}/store-edit-pic-1/', [MitraController::class, 'store_edit_pic1'])->name('mitra.store_edit_pic1')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/mitra/{id}/sub-pic-view', [MitraController::class, 'pic_sub_view'])->name('mitra.pic_sub_view')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/mitra/{id}/{pic}/subpic-add-view', [MitraController::class, 'pic_addsub_view'])->name('mitra.pic_addsub_view')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/mitra/store-subpic', [MitraController::class, 'store_pic_sub'])->name('mitra.store_pic_sub')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/mitra/{id}/pic_sub-edit/{mit}', [MitraController::class, 'pic_sub_edit_view'])->name('mitra.pic_sub_edit_view')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/mitra/{id}/store-pic-sub-edit', [MitraController::class, 'store_edit_pic_sub'])->name('mitra.store_edit_pic_sub')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/mitra/{id}/pic-mutasi', [MitraController::class, 'mitra_mutasi'])->name('mitra.mitra_mutasi')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/mitra/mutasi', [MitraController::class, 'mutasi_continue'])->name('mitra.mutasi_continue')->middleware(['role:admin|STAF ADMIN']);
    
    
    
    Route::get('/mitra', [MitraController::class, 'index'])->name('mitra.index')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/mitra/add-biller', [MitraController::class, 'add_biller'])->name('biller.add_biller')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/mitra/Add-Mitra', [MitraController::class, 'addmitra'])->name('mitra.addmitra')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/mitra/data/{id}', [MitraController::class, 'data'])->name('mitra.data')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/mitra/edit/{id}', [MitraController::class, 'edit'])->name('mitra.edit')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/mitra/store/edit/{id}', [MitraController::class, 'store_edit'])->name('mitra.store_edit')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/mitra/data/top-up/{id}', [MitraController::class, 'topup'])->name('mitra.topup')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/mitra/data/debet-saldo/{id}', [MitraController::class, 'debet_saldo'])->name('mitra.debet_saldo')->middleware(['role:admin|STAF ADMIN']);




    ##--APPLIKASI
    Route::get('/setting/kelurahan', [AppController::class, 'kelurahan'])->name('app.kelurahan')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/setting/kelurahan-store', [AppController::class, 'kelurahan_store'])->name('app.kelurahan_store')->middleware(['role:admin|STAF ADMIN']);
    Route::put('/setting/{id}/kelurahan-update', [AppController::class, 'update_kelurahan'])->name('app.update_kelurahan')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/setting/rt', [AppController::class, 'data_rt'])->name('app.data_rt')->middleware(['role:admin|STAF ADMIN']);
    
    ##--KEUANGAN/TRANSAKSI
    // Route::get('/Transaksi/Operasional', [TransaksiController::class, 'pencairan_operasional'])->name('trx.pencairan_operasional')->middleware(['role:admin|NOC|STAF ADMIN|KEUANGAN']);
    Route::put('/Transaksi/Konfirmasi-Pencairan', [TransaksiController::class, 'konfirm_pencairan'])->name('trx.konfirm_pencairan')->middleware(['role:admin|NOC|STAF ADMIN|KEUANGAN']);
    Route::get('/Transaksi/laporan-harian-admin', [LaporanController::class, 'laporan_harian'])->name('trx.laporan_harian')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    Route::get('/Transaksi/{id}/Print-Laporan', [LaporanController::class, 'laporan_print'])->name('trx.laporan_print')->middleware(['role:admin|STAF ADMIN|KEUANGAN']);
    
    
    ##--HOSTPOT
    Route::get('/hotspot', [HotspotController::class, 'data_voucher'])->name('vhc.data_voucher')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/hotspot/data-pesanan', [HotspotController::class, 'data_pesanan'])->name('vhc.data_pesanan')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/hotspot/{id}/rincian-pesanan', [HotspotController::class, 'rincian_pesanan'])->name('vhc.rincian_pesanan')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::post('/hotspot/proses-pesanan', [HotspotController::class, 'proses_pesanan'])->name('vhc.proses_pesanan')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::put('/hotspot/{id}/bayar-pesanan', [HotspotController::class, 'bayar_pesanan'])->name('vhc.bayar_pesanan')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::post('/hotspot/store-pesanan', [HotspotController::class, 'store_pesanan'])->name('vhc.store_pesanan')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/hotspot/form-pesanan', [HotspotController::class, 'form_pesanan_voucher'])->name('vhc.form_pesanan_voucher')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/hotspot/data-outlet', [HotspotController::class, 'data_outlet'])->name('vhc.data_outlet')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::post('/hotspot/store-outlet', [HotspotController::class, 'store_outlet'])->name('vhc.store_outlet')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/hotspot/paket{id}', [HotspotController::class, 'getPaketHotspot'])->name('vhc.getPaketHotspot')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::put('/hotspot/store-vhc', [HotspotController::class, 'store_vhc'])->name('vhc.store_vhc')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/hotspot/{id}/print-vhc', [HotspotController::class, 'print_voucher'])->name('vhc.print_voucher')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/hotspot/update-data-voucher', [HotspotController::class, 'update_data_voucher'])->name('vhc.update_data_voucher')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/hotspot/voucher-tejual', [HotspotController::class, 'voucher_terjual'])->name('vhc.voucher_terjual')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/hotspot/data-voucher-tejual', [HotspotController::class, 'data_voucher_terjual'])->name('vhc.data_voucher_terjual')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/hotspot/{id}/detail-voucher-tejual', [HotspotController::class, 'detail_voucher_terjual'])->name('vhc.detail_voucher_terjual')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/hotspot/{username}/kick', [HotspotController::class, 'kick_voucher'])->name('vhc.kick_voucher')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/hotspot/{username}/reset', [HotspotController::class, 'reset_voucher'])->name('vhc.reset_voucher')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/hotspot/{id}/print-notas', [HotspotController::class, 'print_nota_pesanan'])->name('vhc.print_nota_pesanan')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/hotspot/update-voucher', [HotspotController::class, 'update_voucher'])->name('vhc.update_voucher')->middleware(['role:admin|NOC|STAF ADMIN']);

    
    ##--HOSTPOT-GLOBAL CONTROLLER
    Route::get('/hotspot/{id}/get-site', [GlobalController::class, 'getMitraSite'])->name('glb.getMitraSite')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/hotspot/{id}/get-outlet', [GlobalController::class, 'getOutlet'])->name('glb.getOutlet')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/hotspot/{id}/get-paket', [GlobalController::class, 'getPaket'])->name('glb.getPaket')->middleware(['role:admin|STAF ADMIN|NOC']);
    
    ##--TIKET
    Route::get('/tiket', [TiketController::class, 'dashboard_tiket'])->name('tiket.dashboard_tiket')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/tiket/data-tiket', [TiketController::class, 'data_tiket'])->name('tiket.data_tiket')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/tiket/data-tiket-project', [TiketController::class, 'data_tiket_project'])->name('tiket.data_tiket_project')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/tiket/buat-tiket', [TiketController::class, 'buat_tiket'])->name('tiket.buat_tiket')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::put('/tiket/{id}/tiket-update', [TiketController::class, 'tiket_update'])->name('tiket.tiket_update')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/tiket/{id}', [TiketController::class, 'details_tiket'])->name('tiket.details_tiket')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/tiket/{id}/tiket-closed', [TiketController::class, 'details_tiket_closed'])->name('tiket.details_tiket_closed')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/tiket/{id}/tiket-project', [TiketController::class, 'details_tiket_project'])->name('tiket.details_tiket_project')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::post('/tiket/store', [TiketController::class, 'store'])->name('tiket.store')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::post('/tiket/export', [TiketController::class, 'export_tiket'])->name('tiket.export_tiket')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/tiket/{id}/cari', [TiketController::class, 'pilih_pelanggan'])->name('tiket.pilih_pelanggan')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::post('/tiket/{id}/cek-ont', [TiketController::class, 'tiket_cek_ont'])->name('tiket.tiket_cek_ont')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/pelanggan/{id}/cek-adaptor', [TiketController::class, 'tiket_cek_adp'])->name('tiket.tiket_cek_adp')->middleware(['role:admin|STAF ADMIN|NOC']);
    Route::get('/tiket/{id}/val-odp', [TiketController::class, 'tiket_validasi_odp'])->name('tiket.tiket_validasi_odp')->middleware(['role:admin|STAF ADMIN|NOC']);
    
    
    ##--GUDANG--
    Route::post('/gudang/aktivasi-barang-keluar', [GudangController::class, 'barang_aktivasi_psb'])->name('gudang.barang_aktivasi_psb')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/gudang', [GudangController::class, 'data_barang'])->name('gudang.data_barang')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/gudang/Tambah-kategori', [GudangController::class, 'store_kategori'])->name('gudang.store_kategori')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/gudang/Tambah-barang', [GudangController::class, 'store_barang'])->name('gudang.store_barang')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/gudang/stok-gudang', [GudangController::class, 'stok_gudang'])->name('gudang.stok_gudang')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/gudang/barang-keluar', [GudangController::class, 'barang_keluar'])->name('gudang.barang_keluar')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/gudang/form-barang-keluar', [GudangController::class, 'form_barang_keluar'])->name('gudang.form_barang_keluar')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/gudang/proses-barang-keluar', [GudangController::class, 'proses_form_barang_keluar'])->name('gudang.proses_form_barang_keluar')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/gudang/proses-tiket-barang-keluar', [GudangController::class, 'proses_tiket_form_barang_keluar'])->name('gudang.proses_tiket_form_barang_keluar')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/gudang/data-kode-group', [GudangController::class, 'data_kode_group'])->name('gudang.data_kode_group')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/gudang/{id}/print-kode', [GudangController::class, 'print_kode'])->name('gudang.print_kode')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/gudang/print-stok-gudang', [GudangController::class, 'print_stok_gudang'])->name('gudang.print_stok_gudang')->middleware(['role:admin|STAF ADMIN']);
    Route::post('/gudang/print-barang_masuk', [GudangController::class, 'print_barang_masuk'])->name('gudang.print_barang_masuk')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/gudang/data-group-barang-keluar', [GudangController::class, 'data_group_barang_keluar'])->name('gudang.data_group_barang_keluar')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/gudang/print-skb', [GudangController::class, 'print_skb'])->name('gudang.print_skb')->middleware(['role:admin|STAF ADMIN']);
    Route::get('/gudang/print-request-barang', [GudangController::class, 'print_request_barang'])->name('gudang.print_request_barang')->middleware(['role:admin|STAF ADMIN']);
    ##--PUTUS BERLANGGAN--
    Route::put('/pelanggan/{id}/deaktivasi-pelanggan', [RegistrasiController::class, 'deaktivasi_pelanggan'])->name('reg.deaktivasi_pelanggan')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/follow-up', [RegistrasiController::class, 'followup'])->name('reg.followup')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/pelanggan/print-list-deaktivasi', [RegistrasiController::class, 'print_list_deaktivasi'])->name('reg.print_list_deaktivasi')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/data-deaktivasi', [RegistrasiController::class, 'data_deaktivasi'])->name('reg.data_deaktivasi')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/{id}/berita-acara-deaktivasi', [RegistrasiController::class, 'berita_acara_deaktivasi'])->name('reg.berita_acara_deaktivasi')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::post('/pelanggan/{id}/cek-perangkat', [RegistrasiController::class, 'cek_perangkat'])->name('reg.cek_perangkat')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/{id}/cek-perangkat-hilang', [RegistrasiController::class, 'cek_perangkat_hilang'])->name('reg.cek_perangkat_hilang')->middleware(['role:admin|NOC|STAF ADMIN']);
    ##--REGISTRASI/EDIT/AKTIVASI--
    Route::put('/pelanggan/update-Router/{id}', [RegistrasiApiController::class, 'update_router'])->name('reg.update_router')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/{id}/form-pelanggan', [RegistrasiController::class, 'form_update_pelanggan'])->name('reg.form_update_pelanggan')->middleware(['role:admin|NOC|STAF ADMIN|KEUANGAN']);
    // Route::put('/pelanggan/{id}/form-update-pelanggan/no-skb', [RegistrasiController::class, 'proses_update_noskb'])->name('reg.proses_update_noskb')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/pelanggan/data-aktivasi-pelanggan', [RegistrasiController::class, 'data_aktivasi_pelanggan'])->name('reg.data_aktivasi_pelanggan')->middleware(['role:admin|NOC|STAF ADMIN']);
    Route::get('/ftth/{id}/aktivasi_pelanggan', [RegistrasiController::class, 'aktivasi_pelanggan'])->name('reg.aktivasi_pelanggan')->middleware(['role:admin|NOC|STAF ADMIN']);
    ##--VALIDASI--
    Route::get('/validasi/barang/{id}', [GlobalController::class, 'valBarang'])->name('val.valBarang')->middleware(['role:admin|NOC|STAF ADMIN']);
    ##--TELEGRAM--
    // Route::get('/tele/store', [TelegramController::class, 'sendMessage'])->name('tel.sendMessage')->middleware(['role:admin|NOC|STAF ADMIN']);
})->middleware(['role:admin|STAF ADMIN']);

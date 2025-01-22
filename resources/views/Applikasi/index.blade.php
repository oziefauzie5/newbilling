@extends('layout.main')
@section('content')
<style>
  .notice{
    font-size:11px;
    color:red;
    font-weight: bold;
  }
</style>
<div class="content">
  <div class="page-inner">
    <div class="row">
        <div class="col-md-7">
          <div class="card">
            <div class="card-header bg-primary">
                <button class="btn btn-sm" data-toggle="modal" data-target="#modal-adduser" class="btn btn-primary btn-sm"><i class="fas fa-solid fa-plus"></i>Tambah Akun Pembayaran</button>
              </div>
              <!-- ----------------------------------------------------------------------MODAL ADD AKUN------------------------------------------------ -->
              
              <div class="modal fade" id="modal-adduser">
                  <div class="modal-dialog">
                    <form action="{{route('admin.app.akun_store')}}" method="POST">
                      @csrf
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h4 class="modal-title">TAMBAH AKUN </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">

                        
                            <div class="card-body">
                              <div class="form-group">
                                  <label>Id Akun</label>
                                  <input type="text" class="form-control" id="akun_id" name="akun_id" value="{{$akun_id}}">
                              </div>
                              <div class="form-group">
                                  <label>Nama Akun</label>
                                  <input type="text" class="form-control" id="nama_akun" name="nama_akun" placeholder="Masukan Nama Akun">
                              </div>
                              <div class="form-group">
                                  <label>Nomor Rekening</label>
                                  <input type="text" class="form-control" id="akun_rekening" name="akun_rekening" placeholder="Masukan Nomor Rekening"> 
                              </div>
                              <div class="form-group">
                                  <label>Nama Pemilik</label>
                                  <input type="text" class="form-control" id="akun_rekening" name="nama_pemilik" placeholder="Masukan Nama Pemilik">
                              </div>
                              <div class="form-group">
                                  <label>Nama Kategori</label>
                                  <select name="akun_kategori"class="form-control"  id="">
                                    <option value="">PILIH AKUN</option>
                                    <option value="PEMBAYARAN">PEMBAYARAN</option>
                                    <option value="LAPORAN">LAPORAN</option>
                                  </select>
                              </div>
                            </div>

                      </div>
                      <div class="modal-footer justify-content-between">
                              <button type="button" class="btn" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                          </div>
                        </form>
                  </div>
                </div>
              <!-- -------------------------------------------------------------------END MODAL ADD AKUN------------------------------------------------ -->
            
            <div class="card-body table-responsive -sm">
              @if ($errors->any())
                      <div class="alert alert-danger" role="alert">
                        <ul>
                          @foreach ($errors->all() as $item)
                              <li>{{ $item }}</li>
                          @endforeach
                        </ul>
                        </div>
              @endif
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Id Akun</th>
                      <th>Nama Akun</th>
                      <th>Rekening</th>
                      <th>Nama Pemilik</th>
                      <th>Kategori</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($SettingAkun as $d)
                      <tr>
                        @if($d->id>2)
                        <td>{{$d->akun_id}}</td>
                        <td>{{$d->akun_nama}}</td>
                        <td>{{$d->akun_rekening}}</td>
                        <td>{{$d->akun_pemilik}}</td>
                        <td>{{$d->akun_kategori}}</td>
                        <td>{{$d->akun_status}}</td>
                        <td>
                          <div class="form-button-action">
                              <button type="button" data-toggle="modal" data-target="#modal-edit{{$d->id}}" class="btn btn-link btn-primary btn-lg">
                                <i class="fa fa-edit"></i>
                              </button>
                              <button type="button" data-toggle="modal" data-target="#modal-hapus{{$d->id}}" class="btn btn-link btn-danger">
                                <i class="fa fa-times"></i>
                              </button>
                            </div>
                          </td>
                          @endif
                      <div class="modal fade" id="modal-edit{{$d->id}}">
                          <div class="modal-dialog">
                            <form action="{{route('admin.app.akun_edit',['id'=>$d->id])}}" method="POST">
                            @csrf  
                            @method('PUT')
                            <div class="modal-content">
                              <div class="modal-header bg-primary ">
                                <h4 class="modal-title">TAMBAH </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                    <div class="card-body">
                                    <div class="form-group">
                                  <label>Id Akun</label>
                                  <input type="text" class="form-control" id="akun_id" name="akun_id" value="{{$d->akun_id}}">
                              </div>
                                      <div class="form-group">
                                          <label>Nama akun</label>
                                          <input type="text" class="form-control" id="nama_akun" name="nama_akun" value="{{$d->akun_nama}}">
                                      </div>
                                      <div class="form-group">
                                          <label>Nomor Rekening</label>
                                          <input type="number" class="form-control" id="akun_rekening" name="akun_rekening" value="{{$d->akun_rekening}}" > 
                                      </div>
                                      <div class="form-group">
                                          <label>Nama Pemilik</label>
                                          <input type="text" class="form-control" id="akun_pemilik" name="akun_pemilik" value="{{$d->akun_pemilik}}" >
                                      </div>
                                    </div>
      
                              </div>
                              <div class="modal-footer justify-content-between">
                                      <button type="button" class="btn" data-dismiss="modal">Close</button>
                                      <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                  </div>
                                </form>
                          </div>
                        </div>
                      
                      </tr>
                      <div class="modal fade" id="modal-hapus{{$d->id}}">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header bg-primary">
                                <h4 class="modal-title">Konfirmasi hapus Data</h4>
                                <button type="button" class=" close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <p>Apakah kamu yakin ingin menghapus Akun {{$d->akun_nama}}?&hellip;</p>
                              </div>
                              <div class="modal-footer justify-content-between">
                                  <form action="{{route('admin.app.akun_delete',['id'=>$d->id])}}" method="POST">
                                      @csrf
                                      @method('DELETE')
                                      <button type="button" class="btn" data-dismiss="modal">Close</button>
                                      <button type="submit" class="btn btn-primary">Ya. Hapus</button>
                                  </form>
                              </div>
                            </div>
                          </div>
                        </div>
                  @endforeach
                </table>
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="card ">
            <div class="card-header">
              <ul class="nav nav-pills nav-primary" id="pills-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Tripay</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Aplikasi</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="pills-biaya-tab" data-toggle="pill" href="#pills-biaya" role="tab" aria-controls="pills-biaya" aria-selected="false">Biaya</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="pills-waktu-tab" data-toggle="pill" href="#pills-waktu" role="tab" aria-controls="pills-waktu" aria-selected="false">Jeda Waktu</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="pills-whatsapp-tab" data-toggle="pill" href="#pills-whatsapp" role="tab" aria-controls="pills-whatsapp" aria-selected="false">Whatsapp</a>
                </li>
              </ul>
            </div>
            {{-- AWAL --}}
            <div class="card-body">
              <div class="tab-pane fade show active" id="pills-app" role="tabpanel" aria-labelledby="pills-app-tab">
              </div>
              <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                <div class="tab-pane fade show" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                  <li>Fitur pelunasan otomatis dengan payment gateway Tripay</li>
                  <hr>
              <form action="{{route('admin.app.tripay_store')}}" method="POST">
                  @csrf
                  <div class="form-group">
                    <label >Kode Merchant</label>
                    <input type="text" class="form-control" name="tripay_kode_merchant" value="{{$tripay_kode_merchant}}"  required>
                  </div>
                  <div class="form-group">
                    <label >URL Callback</label>
                    <input type="text" class="form-control" name="tripay_url_callback" value="{{$tripay_url_callback}}"  required>
                    <span class="notice">Lihat di menu Merchant -> Opsi ->Edit<span>
                  </div>
                  <div class="form-group">
                    <label >API Key</label>
                    <input type="text" class="form-control"  name="tripay_apikey" id="" value="{{$tripay_apikey}}"   required>
                  </div>
                  <div class="form-group">
                    <label >Private Key</label>
                    <input type="text" class="form-control"  name="tripay_privatekey" id="" value="{{$tripay_privatekey}}"   required>
                  </div>
                  <div class="form-group">
                    <label >Pengisian saldo TopUp via payment gateway</label>
                    <select name="tripay_admin_topup" id="" class="form-control" required>
                      <option value="">Pilih</option>
                      @if($tripay_admin_topup == 1 )
                      <option value="1" selected>1.Nominal Topup + Biaya Admin</option>
                      @else
                      <option value="1">1.Nominal Topup + Biaya Admin</option>
                      @endif
                      @if($tripay_admin_topup == 2 )
                      <option value="2" selected>2.Nominal Topup - Biaya Admin</option>
                      @else
                      <option value="2" >2.Nominal Topup - Biaya Admin</option>
                      @endif
                      <!-- <option value="1">1.Nominal Topup + Biaya Admin</option> -->
                    </select>
                      <li class="notice">Pilihan no.1 saldo akan ditambah sebanyak nominal toup + biaya admin payment gateway</li>
                      <li class="notice">Pilihan no.2 saldo akan ditambah sejumlah nominal dikurangi biaya admin</li>
                      <button type="submit" class="btn btn-block btn-primary">Simpan</button>
                      </form>
                  </div>
                  
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                  <form action="{{route('admin.app.aplikasi_store')}}" method="POST" enctype="multipart/form-data">
                    @csrf

                <div class="form-group">
                  <label >Nama Perusahaan</label>
                  <input type="text" class="form-control" name="app_nama" value="{{$app_nama}}"  required>
                </div>
                <div class="form-group">
                  <label >Nama Brand</label>
                  <input type="text" class="form-control" name="app_brand" value="{{$app_brand}}"  required>
                </div>
                <div class="form-group">
                  <label >Alamat</label>
                  <input type="text" class="form-control"  name="app_alamat" id="" value="{{$app_alamat}}"   required>
                </div>
                <div class="form-group">
                  <label >Npwp</label>
                  <input type="text" class="form-control"  name="app_npwp" id="" value="{{$app_npwp}}">
                </div>
                <div class="form-group">
                  <label >Frefix Id Client</label>
                  <input type="text" class="form-control"  name="app_clientid" id="" value="{{$app_clientid}}">
                </div>
                <div class="form-group">
                  <label >Link Admin</label>
                  <input type="text" class="form-control"  name="app_link_admin" id="" value="{{$app_link_admin}}">
                </div>
                <div class="form-group">
                  <label >Link Pelanggan</label>
                  <input type="text" class="form-control"  name="app_link_pelanggan" id="" value="{{$app_link_pelanggan}}">
                </div>
                <div class="form-group">
                    <img src="{{ asset('storage/profile_perusahaan/'.$app_logo) }}" class="img-fluid" alt="{{'a'.$app_favicon}}">
                </div>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroupFileAddon01">Upload Logo</span>
                    </div>
                    <div class="custom-file">
                      <input type="file" name="app_logo" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                      <label class="custom-file-label" for="inputGroupFile01">Pilih file</label>
                    </div>
                  </div>
                  <div class="form-group">
                      <img src="{{ asset('storage/profile_perusahaan/'.$app_favicon) }}" class="img-fluid" alt="{{'a'.$app_favicon}}">
                  </div>

                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroupFileAddon01">Upload Favicon</span>
                    </div>
                    <div class="custom-file">
                      <input type="file" name="app_favicon" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                      <label class="custom-file-label" for="inputGroupFile01">Pilih file</label>
                    </div>
                  </div>
                <button type="submit" class="btn btn-block btn-primary">Simpan</button>
                    </form>
                </div>
                <div class="tab-pane fade" id="pills-biaya" role="tabpanel" aria-labelledby="pills-biaya-tab">
                  <form action="{{route('admin.app.biaya_store')}}" method="POST" enctype="multipart/form-data">
                    @csrf

                <div class="form-group">
                  <label >Biaya Pasang</label>
                  <input type="text" class="form-control" name="biaya_pasang" value="{{$biaya_pasang}}"  required>
                </div>
                <div class="form-group">
                  <label >Biaya PSB</label>
                  <input type="text" class="form-control" name="biaya_psb" value="{{$biaya_psb}}"  required>
                </div>
                <div class="form-group">
                  <label >Biaya Sales</label>
                  <input type="text" class="form-control" name="biaya_sales" value="{{$biaya_sales}}"  required>
                </div>
                <div class="form-group">
                  <label >Biaya Sales Continue</label>
                  <input type="text" class="form-control" name="biaya_sales_continue" value="{{$biaya_sales_continue}}"  required>
                </div>
                <div class="form-group">
                  <label >Biaya Deposit</label>
                  <input type="text" class="form-control" name="biaya_deposit" value="{{$biaya_deposit}}"  required>
                </div>
                <div class="form-group">
                  <label >Biaya Kas Wilayah</label>
                  <input type="text" class="form-control" name="biaya_kas" value="{{$biaya_kas}}"  required>
                  <span class="notice">Biaya kas wilayah yang dibebankan kepada persatu pelanggan</span>
                </div>
                <div class="form-group">
                  <label >Biaya Kerjasama</label>
                  <input type="text" class="form-control" name="biaya_kerjasama" value="{{$biaya_kerjasama}}"  required>
                  <span class="notice">Biaya kerjasama yang dibebankan kepada persatu pelanggan</span>
                </div>
                <div class="form-group">
                  <label >PPN %</label>
                  <input type="text" class="form-control" name="biaya_ppn" value="{{$biaya_ppn}}"  required>
                </div>
                
                <button type="submit" class="btn btn-block btn-primary">Simpan</button>
                    </form>
                </div>
                <div class="tab-pane fade" id="pills-waktu" role="tabpanel" aria-labelledby="pills-waktu-tab">
                  <form action="{{route('admin.app.waktu_store')}}" method="POST" enctype="multipart/form-data">
                    @csrf

                <div class="form-group">
                  <label >Isolir</label>
                  <input type="text" class="form-control" name="wt_jeda_isolir_hari" value="{{$wt_jeda_isolir_hari}}"  required>
                  <span class="notice">Jeda waktu isolir setelah jatuh tempo </span>
                </div>
                <div class="form-group">
                  <label >Tagihan</label>
                  <input type="text" class="form-control" name="wt_jeda_tagihan_pertama" value="{{$wt_jeda_tagihan_pertama}}"  required>
                  <span class="notice">Jeda Waktu isolir tagihan pertama </span>
                </div>
            
                
                <button type="submit" class="btn btn-block btn-primary">Simpan</button>
                    </form>
                </div>
                <div class="tab-pane fade" id="pills-whatsapp" role="tabpanel" aria-labelledby="pills-whatsapp-tab">
                  <form action="{{route('admin.app.whatsapp_store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                <div class="form-group">
                  <label >Whatsapp Nama</label>
                  <input type="text" class="form-control" name="wa_nama" value="{{$wa_nama}}"  required>
                </div>
                <div class="form-group">
                  <label >Whatsapp Api</label>
                  <input type="text" class="form-control" name="wa_key" value="{{$wa_key}}"  required>
                </div>
                <div class="form-group">
                  <label >Whatsapp URL</label>
                  <input type="text" class="form-control" name="wa_url" value="{{$wa_url}}"  required>
                </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input whatsapp" type="checkbox" name="wa_status" value="{{$wa_status}}" @if( $wa_status) checked @endif>
                    <span class="form-check-sign" id="wa" >@if($wa_status=='Enable') Enable @else Disable @endif</span>
                  </label>
                </div>
                <button type="submit" class="btn btn-block btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
          </div>
            {{-- AKHIR --}}
        </div>
        
    </div>
    
  </div>
</div>

@endsection
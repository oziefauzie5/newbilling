@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
      <div class="card">
      <div class="card-header">
									<div class="card-title">VOUCHER TERJUAL</div>
								</div>
        <div class="card-body">
          
                <div class="row">
                  <div class="col-3">
                    <a href="{{route('admin.vhc.data_voucher')}}"><button class="btn btn-primary btn-sm mb-3 btn-block" type="button" >Kembali</button></a>
                  </div>
                </div>
                  <hr>
                  <form >
                    <div class="row mb-1">
                        <div class="col-3">
                        <input type="text" name="q" class="form-control form-control-sm" value="" placeholder="Cari Data">
                        </div>
                        <div class="col-3">
                          <button type="submit" class="btn btn-block btn-dark btn-sm">Cari
                        </div>
                    </div>
                  <form >
                  <div class="table-responsive">
            <table id="" class="display table text-nowrap" >                          
                <thead>
                        <tr class="text-center">
                          <th>Id Voucher</th>
                          <th>Status</th>
                          <th>Waktu Login</th>
                          <th>Waktu Habis</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Paket</th>
                            <th>Site</th>
                            <th>Router</th>
                            <th>Mitra</th>
                            <th>Outlet</th>
                            <th>Tgl Buat</th>
                            <th>Tgl Hapus</th>
                            <th>Mac Address</th>
                            <th>HPP</th>
                            <th>Komisi</th>
                            <th>HJK</th>
                            <th>Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data_voucher as $d)
                        <tr>
                            <td>{{$d->vhc_id}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{$d->vhc_status_pakai}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{date('d-m-Y H:i:s', strtotime($d->vhc_tgl_jual))}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{date('d-m-Y H:i:s', strtotime($d->vhc_exp))}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{$d->vhc_username}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{$d->vhc_password}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{$d->paket_nama}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{$d->site_nama}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{$d->router_nama}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{$d->name}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{$d->outlet_nama}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{date('d-m-Y', strtotime($d->vhc_tgl_cetak))}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{$d->vhc_tgl_hapus}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{$d->vhc_mac}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{$d->vhc_hpp}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{$d->vhc_komisi}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{$d->vhc_hjk}}</td>
                            <td class="href_voucher_terjual" data-id="{{$d->vhc_username}}">{{$d->vhc_admin}}</td>
                        </tr>

                          <div class="modal fade" id="detail_voucher" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                <div class="form-group row">
                                  <label class=" col-sm-4 col-form-label">Paket</label>
                                  <div class="col-sm-8 ">
                                    <input type="text"  name="vhc_site" id="vhc_site" value="{{$d->paket_nama}}" class="form-control">
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class=" col-sm-4 col-form-label">Username</label>
                                  <div class="col-sm-8 ">
                                    <input type="text"  name="vhc_site" id="vhc_site" value="{{$d->vhc_username}}" class="form-control">
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class=" col-sm-4 col-form-label">Password</label>
                                  <div class="col-sm-8 ">
                                    <input type="text"  name="vhc_site" id="vhc_site" value="{{$d->vhc_password}}" class="form-control">
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class=" col-sm-4 col-form-label">Tanggal Aktif</label>
                                  <div class="col-sm-8 ">
                                    <input type="text"  name="vhc_site" id="vhc_tgl_jual" value="{{$d->vhc_tgl_jual}}" class="form-control">
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class=" col-sm-4 col-form-label">Tanggal Expired</label>
                                  <div class="col-sm-8 ">
                                    <input type="text"  name="vhc_site" id="vhc_exp" value="{{$d->vhc_exp}}" class="form-control">
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class=" col-sm-4 col-form-label">Durasi Pakai</label>
                                  <div class="col-sm-8 ">
                                    <input type="text"  name="uptime" id="uptime"  class="form-control">
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class=" col-sm-4 col-form-label">Status User</label>
                                  <div class="col-sm-4 " >
                                    <div id="status" ></div>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class=" col-sm-4 col-form-label">IP Adreess</label>
                                  <div class="col-sm-8 ">
                                    <input type="text"  name="vhc_site" id="address"  class="form-control">
                                  </div>
                                </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  <a href="{{route('admin.vhc.kick_voucher',['username'=>$d->vhc_username])}}"><button type="button" class="btn btn-danger" ><i class="fa fa-times"></i> Kick User</button></a>
                                  <a href="{{route('admin.vhc.reset_voucher',['username'=>$d->vhc_username])}}"><button type="button" class="btn btn-danger" ><i class="fa fa-times"></i> Reset User</button></a>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endforeach
                    </tbody>
                </table>
                <div class="pull-left">
              Showing
              {{$data_voucher->firstItem()}}
              to
              {{$data_voucher->lastItem()}}
              of
              {{$data_voucher->total()}}
              entries
            </div>
            <div class="pull-right">
              {{ $data_voucher->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
         </div>
        </div>
      </div>
    </div>
  </div>
</div>





 


 
@endsection

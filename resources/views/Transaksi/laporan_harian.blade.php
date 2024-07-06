@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <a href="{{route('admin.psb.list_input')}}" class="col-6 col-sm-4 col-lg-3">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">Rp. {{number_format($pendapatan)}}</div>
            <div class="text-muted mb-3">PENDAPATAN</div>
          </div>
        </div>
      </a>
      <a href="{{route('admin.reg.index')}}" class="col-6 col-sm-4 col-lg-3">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">Rp. {{number_format($refund)}}</div>
            <div class="text-muted mb-3">REFUND</div>
          </div>
        </div>
      </a>
      <a href="{{route('admin.reg.index')}}" class="col-6 col-sm-4 col-lg-3">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">Rp. {{number_format($biaya_adm)}}</div>
            <div class="text-muted mb-3">ADM</div>
          </div>
        </div>
      </a>
      <a href="{{route('admin.reg.index')}}" class="col-6 col-sm-4 col-lg-3">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$count_trx}}</div>
            <div class="text-muted mb-3">JUMLAH TRANSAKSI</div>
          </div>
        </div>
      </a>
      <a href="{{route('admin.reg.index')}}" class="col-6 col-sm-4 col-lg-3">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$sum_tunai}}</div>
            <div class="text-muted mb-3">PENDAPATAN TUNAI</div>
          </div>
        </div>
      </a>
    </div>
    <div class="row">
      
      <div class="card">
        <div class="card-body">
          <!-- Modal buat laporan-->
          <div class="modal fade" id="buat_laporan" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="staticBackdropLabel">BUAT LAPORAN HARIAN</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{ route('admin.inv.buat_laporan',['id'=>$admin_user])}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                          <label for="formGroupExampleInput">Laporan Id</label>
                          <input type="text" class="form-control" name="lap_id" value="{{rand(10000, 59999)}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Staf Admin</label>
                          <input type="text" class="form-control" name="user_admin" value="{{$admin_name}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Total Pendapatan</label>
                          <input type="text" class="form-control" name="total" value="{{$buat_laporan}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Total Pendapatan Tunai</label>
                          <input type="text" class="form-control" name="tunai" value="{{$sum_tunai}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Total Pendapatan Adm</label>
                          <input type="text" class="form-control" name="adm" value="{{$biaya_adm}}">
                        </div>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary btn-sm" >BAYAR</button>
                </div>
                </form>
              </div>
            </div>
          </div>
          {{-- end modal buat laporan --}}
       
            <button class="btn  btn-sm ml-auto m-1 btn-dark " data-toggle="modal" data-target="#buat_laporan">
              <i class="fa fa-plus"></i>
              BUAT LAPORAN
            </button>
            <a href="{{route('admin.inv.data_laporan')}}"><button class="btn btn-sm ml-auto m-1 btn-dark">DATA LAPORAN</button>
            </a>
          <button class="btn  btn-sm ml-auto m-1 btn-dark " data-toggle="modal" data-target="#import">
            <i class="fa fa-file-import"></i> PRINT
          </button>
          <form >
            <div class="row mb-1">
                <div class="col-sm-3">
                  <select name="adm" class="custom-select custom-select-sm">
                    @if($adm)
                    <option value="{{$adm}}" selected>{{$adm}}</option>
                    @endif
                    <option value="">ALL DATA</option>
                    @foreach ($admin as $d)
                    <option value="{{$d->name}}">{{$d->name}}</option>
                    @endforeach
                  </select>
              </div>
                <div class="col-sm-3">
                  <select name="ak" class="custom-select custom-select-sm">
                    @if($ak)
                    <option value="{{$ak}}" selected>{{$ak}}</option>
                    @endif
                    <option value="" selected>ALL AKUN</option>
                    @foreach ($akun as $d_akun)
                    <option value="{{$d_akun->akun_nama}}">{{$d_akun->akun_nama}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-sm-3">
                  <button type="submit" class="btn btn-block btn-dark btn-sm">Submit
                </div>
              </div>
          </form>
          @if ($errors->any())
          <div class="alert alert-danger">
            <div class="alert-title"><h4>Gagal!!</h4></div>
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
          </div> 
        @endif
          <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover text-nowrap" >
              <thead>
                <tr>
                  <th>ID</th>
                  <th>TANGGAL</th>
                  <th>INVOICE</th>
                  <th>KETERANGAN</th>
                  <th>CABAR</th>
                  <th>METODE BAYAR</th>
                  <th>KREDIT</th>
                  <th>AKSI</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($laporan as $d)
                <tr>
                      <td>{{$d->lap_id}}</td>
                      <td>{{$d->lap_tgl}}</td>
                      <td class="href_inv" data-id="{{$d->lap_id}}" >{{$d->lap_inv}}</td>
                      <td class="href_inv" data-id="{{$d->lap_id}}" >{{$d->lap_keterangan}}</td>
                      <td class="href_inv" data-id="{{$d->lap_id}}" >{{$d->lap_cabar}}</td>
                      <td class="href_inv" data-id="{{$d->lap_id}}" >{{$d->akun_nama}}</td>
                      <td class="href_inv" data-id="{{$d->lap_id}}" >{{$d->lap_kredit}}</td>
                      <td>
                        <div class="form-button-action">
                          <button type="button" data-toggle="modal" data-target="#modal_edit{{$d->lap_id}}" class="btn btn-link btn-primary btn-lg">
                            <i class="fa fa-edit"></i>
                          </button>
                          <button type="button" data-toggle="modal" data-target="#modal_hapus{{$d->lap_id}}" class="btn btn-link btn-danger">
                            <i class="fa fa-times"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                      <!-- Modal Hapus -->
                      <div class="modal fade" id="modal_hapus{{$d->lap_id}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header no-bd">
                              <h5 class="modal-title">
                                <span class="fw-mediumbold">
                                Hapus Data {{$d->name}}</span> 
                              </h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                              <div class="modal-body">
                              <p>Apakah anda yakin, akan menghapus data {{$d->lap_keterangan}} ??</p>
                              </div>
                              <div class="modal-footer no-bd">
                                <form action="{{route('admin.inv.lap_delete',['id'=>$d->lap_id])}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-success">Hapus</button>
                              </form>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- End Modal Hapus -->
                    @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
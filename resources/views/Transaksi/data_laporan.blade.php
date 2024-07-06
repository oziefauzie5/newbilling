@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      
      <div class="card">
        <div class="card-body">
           
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
                  <th>KETERANGAN</th>
                  <th>PENDAPATAN</th>
                  <th>TUNAI</th>
                  <th>AKSI</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($laporan as $d)
                <tr>
                      <td>{{$d->data_lap_id}}</td>
                      <td>{{$d->data_lap_tgl}}</td>
                      <td class="href_inv" data-id="{{$d->data_lap_id}}" >{{$d->data_lap_keterangan}}</td>
                      <td class="href_inv" data-id="{{$d->data_lap_id}}" >Rp. {{number_format($d->data_lap_pendapatan)}}</td>
                      <td class="href_inv" data-id="{{$d->data_lap_id}}" >Rp. {{number_format($d->data_lap_tunai)}}</td>
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
                      <div class="modal fade" id="modal_hapus{{$d->data_lap_id}}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <form action="{{route('admin.inv.lap_delete',['id'=>$d->data_lap_id])}}" method="POST">
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
@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <a href="{{route('admin.psb.list_input')}}" class="col-6 col-sm-4 col-lg-3">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">1</div>
            <div class="text-muted mb-3">JUMLAH INVOICE</div>
          </div>
        </div>
      </a>
      <a href="{{route('admin.reg.index')}}" class="col-6 col-sm-4 col-lg-3">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">2</div>
            <div class="text-muted mb-3">INV UNPAID</div>
          </div>
        </div>
      </a>
      <div class="col-6 col-sm-4 col-lg-3">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">3</div>
            <div class="text-muted mb-3">INV SUSPEND</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-3">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">4</div>
            <div class="text-muted mb-3">TOTAL BELUM LUNAS</div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      
      <div class="card">
        <div class="card-body">
        
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
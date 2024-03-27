@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <a href="{{route('admin.psb.list_input')}}" class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-success">
              6%
              <i class="fa fa-chevron-up"></i>
            </div>
            <div class="h1 m-0">43</div>
            <div class="text-muted mb-3">Input Data</div>
          </div>
        </div>
      </a>
      <a href="{{route('admin.reg.index')}}" class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-danger">
              -3%
              <i class="fa fa-chevron-down"></i>
            </div>
            <div class="h1 m-0">17</div>
            <div class="text-muted mb-3">Registrasi</div>
          </div>
        </div>
      </a>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-success">
              3%
              <i class="fa fa-chevron-up"></i>
            </div>
            <div class="h1 m-0">27.3K</div>
            <div class="text-muted mb-3">Teknisi</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-danger">
              -2%
              <i class="fa fa-chevron-down"></i>
            </div>
            <div class="h1 m-0">$95</div>
            <div class="text-muted mb-3">Aktivasi</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-danger">
              -1%
              <i class="fa fa-chevron-down"></i>
            </div>
            <div class="h1 m-0">621</div>
            <div class="text-muted mb-3">Pembayaran</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-success">
              9%
              <i class="fa fa-chevron-up"></i>
            </div>
            <div class="h1 m-0">7</div>
            <div class="text-muted mb-3">Tiket</div>
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
                  <th>STATUS</th>
                  <th>INVOICE</th>
                  <th>NO.LAYANAN</th>
                  <th>PELANGGAN</th>
                  <th>PROFILE</th>
                  <th>MITRA</th>
                  <th>KATEGORI</th>
                  <th>TGL TERBIT</th>
                  <th>JTH TEMPO</th>
                  <th>TOTAL</th>
                  <th>NOTE</th>
                  <th>ACTION</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_invoice as $d)
                <tr>
                      <td>{{$d->inv_status}}</td>
                      <td>{{$d->inv_id}}</td>
                      <td>{{$d->inv_nolayanan}}</td>
                      <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_nama}}</td>
                      <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_profile}}</td>
                      <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_mitra}}</td>
                      <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_kategori}}</td>
                      <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_tgl_tagih}}</td>
                      <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_tgl_jatuh_tempo}}</td>
                      <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_total}}</td>
                      <td>{{$d->inv_note}}</td>
                      <td>
                        <div class="form-button-action">
                          <button type="button" data-toggle="modal" data-target="#modal_edit{{$d->inv_id}}" class="btn btn-link btn-primary btn-lg">
                            <i class="fa fa-edit"></i>
                          </button>
                          <button type="button" data-toggle="modal" data-target="#modal_hapus{{$d->inv_id}}" class="btn btn-link btn-danger">
                            <i class="fa fa-times"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                      <!-- Modal Hapus -->
                      <div class="modal fade" id="modal_hapus{{$d->inv_id}}" tabindex="-1" role="dialog" aria-hidden="true">
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
                              <p>Apakah anda yakin, akan menghapus data {{$d->inv_nama}} ??</p>
                              </div>
                              <div class="modal-footer no-bd">
                                <form action="{{route('admin.user.delete',['id'=>$d->inv_id])}}" method="POST">
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
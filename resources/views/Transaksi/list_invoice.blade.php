@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col-6 col-sm-4 col-lg-3">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0 font-weight-bold text-danger">{{$inv_count_all}}</div>
            <div class="text-muted mb-3 font-weight-bold text-danger">JUMLAH INVOICE</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-3">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0 font-weight-bold text-warning">{{$inv_count_unpaid}}</div>
            <div class="text-muted mb-3 text-warning font-weight-bold">INV UNPAID</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-3">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0 font-weight-bold text-danger" >{{$inv_count_suspend}}</div>
            <div class="text-muted mb-3 text-danger font-weight-bold">INV SUSPEND</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-3">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0 font-weight-bold text-danger" >{{$inv_count_isolir}}</div>
            <div class="text-muted mb-3 text-danger font-weight-bold">INV ISOLIR</div>
          </div>
        </div>
      </div>
      </div>
      @role('admin')
      <div class="row">
      <div class="col-6 col-sm-4 col-lg-6">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0 font-weight-bold text-danger">Rp. {{number_format($inv_belum_lunas)}}</div>
            <div class="text-muted mb-3 font-weight-bold text-danger">TOTAL BELUM LUNAS</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-6">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0 font-weight-bold text-success">Rp. {{number_format($inv_lunas)}}</div>
            <div class="text-muted mb-3 font-weight-bold text-success">TOTAL LUNAS  {{$inv_count_lunas}}</div>
          </div>
        </div>
      </div>
    </div>
    @endrole
    <div class="row">
      
      <div class="card">
        <div class="card-body">
          <form >
            <div class="row mb-1">
                <div class="col-sm-3">
                  <select name="data_bulan" class="custom-select custom-select-sm">
                    @if($data_bulan)
                    <option value="{{$data_bulan}}" selected>{{$data_bulan}}</option>
                    @endif
                    <option value="">ALL DATA</option>
                    <option value="1">PELANGGAN BARU</option>
                    <option value="2">PELANGGAN 2 BULAN</option>
                    <option value="3">PELANGGAN 3 BULAN</option>
                  </select>
              </div>
                <div class="col-sm-3">
                  <select name="data_inv" class="custom-select custom-select-sm">
                    @if($data_inv)
                    <option value="{{$data_inv}}" selected>{{$data_inv}}</option>
                    @endif
                    <option value="" selected>ALL INVOICE</option>
                    <option value="UNPAID">INVOICE UNPAID</option>
                    <option value="SUSPEND">INVOICE SUSPEND</option>
                    <option value="ISOLIR">INVOICE ISOLIR</option>
                  </select>
                </div>
                <div class="col-sm-3">
                 <input type="text" name="q" class="form-control form-control-sm" value="{{$q}}">
                </div>
                <div class="col-sm-1">
                  <button type="submit" class="btn btn-block btn-dark btn-sm">Cari
                </div>
                @role('admin')
                <div class="col-sm-2">
                  <a href="{{route('admin.inv.generate_invoice')}}"><button type="button" class="btn btn-block btn-info btn-sm">Genearte Invoice</button></a>
                </div>
                @endrole
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
            <table class="display table table-striped table-hover text-nowrap" >
              <thead>
                <tr>
                  <th>STATUS</th>
                  <th>JTH TEMPO</th>
                  <th>INVOICE</th>
                  <th>NO.LAYANAN</th>
                  <th>PELANGGAN</th>
                  <th>PROFILE</th>
                  <th>MITRA</th>
                  <th>KATEGORI</th>
                  <th>TGL TERBIT</th>
                  <th>TOTAL</th>
                  <th>NOTE</th>
                  <th>ACTION</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_invoice as $d)
                <tr>
                  @if($d->inv_status == 'PAID')
                  <td> <span class="badge badge-success">{{$d->inv_status}}</span></td>
                  @elseif($d->inv_status == 'UNPAID')
                  <td > <span class="badge badge-warning">{{$d->inv_status}}</span></td>
                  @elseif($d->inv_status == 'SUSPEND')
                  <td> <span class="badge badge-danger">{{$d->inv_status}}</span></td>
                  @elseif($d->inv_status == 'ISOLIR')
                  <td> <span class="badge badge-danger">{{$d->inv_status}}</span></td>
                  @endif
                  <td class="href_inv" data-id="{{$d->inv_id}}" >{{date('d-m-Y', strtotime($d->inv_tgl_jatuh_tempo))}}</td>
                      <td>{{$d->inv_id}}</td>
                      <td>{{$d->inv_nolayanan}}</td>
                      <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_nama}}</td>
                      <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_profile}}</td>
                      <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_mitra}}</td>
                      <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_kategori}}</td>
                      <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_tgl_tagih}}</td>
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
        {{ $data_invoice->links('pagination::bootstrap-4') }}
      </div>
    </div>
  </div>
</div>

@endsection
@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
        <div href="{{route('admin.psb.list_input')}}" class="col">
          <div class="card">
            <div class="card-body p-3 text-center">
              <div class="h2 m-0">Rp. {{number_format($pendapatan)}}</div>
              <div class="text-muted mb-3">PENDAPATAN</div>
            </div>
          </div>
        </div>
        <div href="{{route('admin.reg.index')}}" class="col">
          <div class="card">
            <div class="card-body p-3 text-center">
              <div class="h2 m-0">Rp. {{number_format($refund)}}</div>
              <div class="text-muted mb-3">REFUND</div>
            </div>
          </div>
        </div>
        <div href="{{route('admin.reg.index')}}" class="col">
          <div class="card">
            <div class="card-body p-3 text-center">
              <div class="h2 m-0">Rp. {{number_format($biaya_adm)}}</div>
              <div class="text-muted mb-3">ADM</div>
            </div>
          </div>
        </div>
        <div href="{{route('admin.reg.index')}}" class="col">
          <div class="card">
            <div class="card-body p-3 text-center">
              <div class="h2 m-0">Rp. {{number_format($sum_tunai)}}</div>
              <div class="text-muted mb-3">PENDAPATAN TUNAI</div>
            </div>
          </div>
        </div>
        <div href="{{route('admin.reg.index')}}" class="col">
          <div class="card">
            <div class="card-body p-3 text-center">
              <div class="h2 m-0">{{$count_trx}}</div>
              <div class="text-muted mb-3">JUMLAH TRANSAKSI</div>
            </div>
          </div>
        </div>
       
      </div>
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
                  <input type="date" class="form-control form-control-sm" value="" name="start">
              </div>
              <div class="col-sm-3">
                  <input type="date" class="form-control form-control-sm" value="" name="end">
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
        <hr>
          <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover text-nowrap" >
              <thead>
                <tr>
                  <th>{{$count_data}}</th>
                  <th>AKSI</th>
                  <th>ID</th>
                  <th>TANGGAL</th>
                  <th>KETERANGAN</th>
                  <th>PENDAPATAN</th>
                  <th>TUNAI</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($laporan as $d)
                <tr>
                  <td id="center">{{$loop->iteration}}</td>
                  <td>
                    <div class="form-button-action">
                      <button type="button" data-toggle="modal" data-target="#modal_hapus{{$d->data_lap_id}}" class="btn btn-link btn-danger">
                        <i class="fa fa-times"></i>
                      </button>
                      <a href="{{route('admin.trx.laporan_print',['id'=>$d->data_lap_id])}}"><button type="button" class="btn btn-link btn-primary">
                        <i class="fa fa-print"></i>
                      </button></a>
                    </div>
                  </td>
                      <td>{{$d->data_lap_id}}</td>
                      <td>{{date('d-m-Y',strtotime($d->data_lap_tgl))}}</td>
                      <td>{{$d->data_lap_keterangan}}</td>
                      <td>Rp. {{number_format($d->data_lap_pendapatan)}}</td>
                      <td>Rp. {{number_format($d->data_lap_tunai)}}</td>
                    </tr>
                      <!-- Modal Hapus -->
                      <div class="modal fade" id="modal_hapus{{$d->data_lap_id}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header no-bd">
                              <h5 class="modal-title">
                                <span class="fw-mediumbold">
                                Hapus Data</span> 
                              </h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                              <div class="modal-body">
                              <p>Apakah anda yakin, akan menghapus data {{$d->data_lap_keterangan}} ??</p>
                              </div>
                              <div class="modal-footer no-bd">
                                <form action="{{route('admin.inv.data_lap_delete',['id'=>$d->data_lap_id])}}" method="POST">
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
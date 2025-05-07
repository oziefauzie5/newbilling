@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">

    <div class="row">
      <div class="card">
        <div class="card-body">
          <form>
            <div class="row mb-1">
              <div class="col-sm-3">
               <input type="text" name="q" class="form-control form-control-sm">
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
            <div class="alert-title">
              <h4>Gagal!!</h4>
            </div>
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          <hr>
          <div class="table-responsive">
            <table class=" table table-striped">
              <thead>
                <tr class="text-center">
                  <th>No</th>
                  <th>AKSI</th>
                  <th>ID</th>
                  <th>TANGGAL</th>
                  <th>ADMIN</th>
                  <th>PERIODE</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($laporan_mingguan as $d)
                <tr>
                  <td id="center">{{$loop->iteration}}</td>
                  <td>
                    <div class="form-button-action">
                      <a href="{{route('admin.lap.jurnal_laporan',['id'=>$d->lm_id])}}"><button type="button" class="btn btn-lg">
                        <i class="fa fa-eye"></i>
                      </button></a>
                    </div>
                  </td>
                  <td>{{$d->lm_id}}</td>
                  <td>{{date('d-m-Y H:m:s',strtotime($d->created_at))}}</td>
                  <td>{{$d->name}}</td>
                  <td>{{$d->lm_periode}}</td>
                </tr>
                <!-- Modal Hapus -->
                <div class="modal fade" id="modal_hapus{{$d->lm_id}}" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <form action="{{route('admin.inv.data_lap_delete',['id'=>$d->lm_id])}}" method="POST">
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
            <div class="pull-left">
              Showing
              {{$laporan_mingguan->firstItem()}}
              to
              {{$laporan_mingguan->lastItem()}}
              of
              {{$laporan_mingguan->total()}}
              entries
            </div>
            <div class="pull-right">
              {{ $laporan_mingguan->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
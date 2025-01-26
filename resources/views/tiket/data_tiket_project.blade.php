@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-primary">
            <div class="d-flex align-items-center">
              <h4 class="card-title text-light">DATA TIKET</h4>
            </div>
          </div>
          <div class="card-body">
            <button class="btn  btn-sm ml-auto m-1 btn-primary ">
              <i class="fa fa-plus"></i> HAPUS</button>
            <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#export" >
              <i class="fa fa-plus"></i> EXPORT PDF</button>
              <a href="{{route('admin.tiket.buat_tiket')}}"> <button class="btn  btn-sm ml-auto m-1 btn-primary">
                <i class="fa fa-plus"></i> BUAT TIKET</button></a>
           
            

<!-- Modaln export tiket -->
<div class="modal fade" id="export" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Export Tiket</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.tiket.export_tiket')}}" method="POST">
          @csrf
          @method('POST')
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label>Dari Tanggal</label>
              <input type="date" class="form-control" name="start_date">
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label>Sampai Tanggal</label>
              <input type="date" class="form-control" name="end_date">
            </div>
          </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Export Tiket</button>
      </div>
    </form>
    </div>
  </div>
</div>
<hr>


            
        {{-- END MODAL CARI DATA  --}}


            
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
                        <form >
              <div class="row mb-1">
                <div class="col-sm-4">
                  <input name="q" type="text" class="form-control form-control-sm" placeholder="Cari">
                </div>
                <div class="col-sm-2">
                  <button type="submit" class="btn btn-block btn-dark btn-sm">Submit
                </div>
              </div>
              </form>
              <hr>
            <div class="table-responsive">
              <table class="display table table-striped table-hover text-nowrap" >
                <thead>
                  <tr>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Tanggal Closed</th>
                    <th>No.Tiket</th>
                    <th>Jenis Laporan</th>
                    <th>No.Layanan</th>
                    <th>Nama</th>
                    <th>Nama Keluhan</th>
                    <th>Keterangan</th>
                    <th>Pembuat</th>
                    <th>Edit</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($tiket as $d)
                  <tr>
                    @if($d->tiket_status == 'Closed')
                    <td><div class="badge badge-success tiket_closed" data-id="{{$d->tiket_id}}">{{$d->tiket_status}}</div></td>
                    @else
                    <td><div class="badge badge-danger tiket_project" data-id="{{$d->tiket_id}}">{{$d->tiket_status}}</div></td>
                    @endif
                    <td>{{date('d-m-Y h:m' , strtotime($d->tanggal))}}</td>
                    @if($d->tiket_waktu_selesai)
                    <td>{{date('d-m-Y h:m' , strtotime($d->tiket_waktu_selesai))}}</td>
                    @else
                    <td class="tiket text-danger" data-id="{{$d->tiket_id}}">Belum dikerjakan</td>
                    @endif
                    <td>{{$d->tiket_kode}}</td>
                    <td>{{$d->tiket_jenis}}</td>
                    <td>{{$d->reg_nolayanan}}</td>
                    <td>{{$d->input_nama}}</td>
                    <td>{{$d->tiket_nama}}</td>
                    <td>{{$d->tiket_keterangan}}</td>
                    <td>{{$d->name}}</td>
                    <td>
                      <div class="form-button-action">
                        <button type="button" data-toggle="modal" data-target="#modal_edit{{$d->id}}" class="btn btn-link btn-danger">
                          <i class="fa fa-times"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              <div class="pull-left">
                Showing
                {{$tiket->firstItem()}}
                to
                {{$tiket->lastItem()}}
                of
                {{$tiket->total()}}
                entries
              </div>
              <div class="pull-right">
                {{ $tiket->withQueryString()->links('pagination::bootstrap-4') }}
              </div>
  
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
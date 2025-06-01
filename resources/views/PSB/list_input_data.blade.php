@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="d-flex align-items-center">
            <h4 class="card-title">Input Data</h4>
          </div>
        </div>
        <div class="card-body">
          <a href="{{route('admin.psb.ftth')}}"><button class="btn  btn-sm ml-auto m-1 btn-primary ">
            <i class="fas fa-angle-double-left "></i>
            Kembali
          </button></a>
          <a href="{{route('admin.psb.input_data')}}"><button class="btn  btn-sm ml-auto m-1 btn-primary ">
            <i class="fas fa-angle-double-left "></i>
            Input Data
          </button></a>
           <a href="{{route('admin.export.export_input_data')}}"><button class="btn  btn-sm ml-auto m-1 btn-primary " >
            <i class="fa fa-plus"></i>
            Input Data export 
          </button></a>
          <button class="btn  btn-sm ml-auto m-1 btn-warning " data-toggle="modal" data-target="#import">
            <i class="fa fa-file-import"></i> Import
          </button>
          <!-- Modal Import -->
          <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header no-bd">
                  <h5 class="modal-title">
                    <span class="fw-mediumbold">
                    Input Data Baru</span> 
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{route('admin.export.export_input_data')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Pilih file (EXCEL,CSV)</label>
                          <input id="import" type="file" class="form-control" name="file" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer no-bd">
                    <button type="submit" class="btn btn-success">Add</button>
                  </form>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
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
            <table id="input_data" class=" table table-striped table-hover text-nowrap" >
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Tanggal Regist</th>
                  <th>Nama</th>
                  <th>Whatsapp</th>
                  <th>Whatsapp Alternatif</th>
                  <th>Alamat Pasang</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($input_data as $d)
                <tr>
                      <td class="href_input_data" data-id="{{$d->id}}">{{$d->id}}</td>
                      <td class="href_input_data" data-id="{{$d->id}}">{{ date('d-m-Y', strtotime($d->input_tgl))}}</td>
                      <td class="href_input_data" data-id="{{$d->id}}">{{$d->input_nama}}</td>
                      <td class="href_input_data" data-id="{{$d->id}}">{{$d->input_hp}}</td>
                      <td class="href_input_data" data-id="{{$d->id}}">{{$d->input_hp_2}}</td>
                      <td class="href_input_data" data-id="{{$d->id}}">{{$d->input_alamat_pasang}}</td>
                      <td class="href_input_data" data-id="{{$d->id}}">{{$d->input_status}}</td>
                    </tr>
                    @endforeach
              </tbody>
            </table>           
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection




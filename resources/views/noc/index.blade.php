@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <a href="#" class="col-6 col-sm-4 col-lg-6">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$count_antrian}}</div>
            <div class="text-muted mb-3">Atrian Pengecekan</div>
          </div>
        </div>
      </a>
      <a href="#" class="col-6 col-sm-4 col-lg-6">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$count_selesai}}</div>
            <div class="text-muted mb-3">Selesai Pengecekan</div>
          </div>
        </div>
      </a>
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
        <form >
        <div class="row mb-1">
          <div class="col-sm-3">
              <select name="data" class="custom-select custom-select-sm">
                @if($data)
                <option value="{{$data}}" selected>{{$data}}</option>
                @endif
                <option value="">ALL DATA</option>
                <option value="1">ANTRIAN PENGECEKAN</option>
                <option value="2">SELESAI PENGECEKAN</option>
              </select>
          </div>
          <div class="col-sm-3">
            <button type="submit" class="btn btn-block btn-dark btn-sm">Submit
          </div>
        </div>
        </form>
        <hr>
          <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover text-nowrap" >
              <thead>
                <tr>
                  <th>#</th>
                  <th>TGL PASANG</th>
                  <th>NO. LAYANAN</th>
                  <th>PELANGGAN</th>
                  <th>ROUTER</th>
                  <th>ALAMAT PASANG</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_registrasi as $d)
                <tr>
                  <td>
                    <div class="form-button-action">
                    </button></a>
                    @if($d->reg_progres == '2')
                      <a href="{{route('admin.noc.pengecekan',['id'=>$d->reg_idpel])}}" target="_blank">
                      <button type="button" class="btn btn-block btn-info btn-sm">
                        Cek Router
                      </button></a>
                      @if($d->reg_img);
                      <a href="{{route('admin.noc.pengecekan_put',['id'=>$d->reg_idpel])}}">
                        <button type="button" class="btn btn-block btn-success btn-sm">
                          Done
                        </button></a>
                      @else
                      <button type="button" class="btn btn-block btn-danger btn-sm" data-toggle="modal" data-target="#exampleModal">
                        Upload Foto
                      </button>

                          <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Foto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.noc.upload',['id'=>$d->reg_idpel])}}" method="post" enctype="multipart/form-data"> 
          @csrf
          @method('PUT')
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label>Nama Pelanggan</label>
                <input type="text" class="form-control" name="pelanggan"placeholder="Nama Lengkap" value="{{ $d->input_nama }}" required>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" name="file">
                  <label class="custom-file-label" >Pilih File</label>
                </div>              </div>
         
            </div>
          </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
    </div>
  </div>
</div>
                          @endif
                      @endif
                    </div>
                  </td>
                  <td>{{$d->reg_tgl_pasang}}</td>
                      <td>{{$d->reg_nolayanan}}</td>
                      <td>{{$d->input_nama}}</td>
                      <td>{{$d->router_nama}}</td>
                      <td>{{$d->input_alamat_pasang}}</td>
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

@endsection
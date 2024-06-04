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
                      <button type="button" class="btn btn-link btn-dark">
                        Cek
                      </button></a>
                      <a href="{{route('admin.noc.pengecekan_put',['id'=>$d->reg_idpel])}}">
                      <button type="button" class="btn btn-link btn-dark">
                        Done
                      </button></a>
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
@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="card">
        <div class="card-body">
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
          <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover text-nowrap">
              <thead>
                <tr>
                  <th>#</th>
                  <th>TGL REGIST</th>
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
                      @if($d->reg_progres == '3')
                      <a href="{{route('admin.psb.bukti_kas_keluar',['id'=>$d->reg_idpel])}}" target="_blank">
                      <button type="button" class="btn btn-link btn-dark">
                        Kas
                      </button></a>
                      @elseif($d->reg_progres == '4')
                      <a>
                        <button type="button" class="btn btn-link btn-success">
                          Konfirmasi
                        </button></a>
                      @elseif($d->reg_progres == '5')
                      <a>
                        <button type="button" class="btn btn-link btn-success">
                          Lunas
                        </button></a>
                      @else
                      <a>
                        <button type="button" class="btn btn-link btn-warning">
                          Proses
                        </button></a>
                    
                      @endif
                    </div>
                  </td>
                  <td>{{$d->input_tgl}}</td>
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
@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    @role('admin|STAF ADMIN')
          <div class="row">
            <div class="col-6 col-sm-4 col-lg-6">
              <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">{{$count_aktivasi}}</div>
                    <div class="text-muted mb-3">Antrian Aktivasi</div>
                  </div>
                </div>
              </div>
              <div class="col-6 col-sm-4 col-lg-6">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">{{$count_teraktivasi}}</div>
                    <div class="text-muted mb-3">Teraktivasi hari ini</div>
                  </div>
                </div>
              </div>
            </div>
    @endrole
    
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
        <a href="{{route('admin.psb.index')}}">
          <button class="btn  btn-sm ml-auto m-1 btn-info">
            <i class="fa fa-plus"></i>
            KEMBALI
          </button>
        </a>
        <hr>
          <div class="table-responsive">
            <table id="datatable" class="display table table-striped table-hover text-nowrap" >
              <thead>
                <tr>
                  <th>NO LAYANAN</th>
                  <th>PELANGGAN</th>
                  <th>TGL JT TEMPO</th>
                  <th>TGL PASANG</th>
                  <th>TGL Registrasi</th>
                  <th>PROFILE</th>
                  <th>ROUTER</th>
                  <th>USERNAME</th>
                  <th>ALAMAT PASANG</th>
                  <th>NOTE</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_registrasi as $d)
                <tr>
                  @if($d->reg_progres == 'MIGRASI')
                  <td class="text-info">{{$d->reg_nolayanan}}</td>
                  @else
                  <td>{{$d->reg_nolayanan}}</td>
                  @endif
                      <td class="aktivasi" data-id="{{$d->reg_idpel}}">{{$d->input_nama}}</td>
                      @if($d->reg_tgl_jatuh_tempo)
                      @if($d->reg_status != 'PAID')
                      <td data-id="{{$d->reg_idpel}}" class="aktivasi text-danger font-weight-bold" data-id="{{$d->reg_idpel}}" >{{date('d-m-Y',strtotime($d->reg_tgl_jatuh_tempo))}}</td>
                      @else
                      <td data-id="{{$d->reg_idpel}}" class="aktivasi font-weight-bold" data-id="{{$d->reg_idpel}}" >{{date('d-m-Y',strtotime($d->reg_tgl_jatuh_tempo))}}---</td>
                      @endif
                      @else
                      <td  data-id="{{$d->reg_idpel}}" class="aktivasi text-danger font-weight-bold" >Belum Terpasang</td>
                      @endif
                      <td class="aktivasi" data-id="{{$d->reg_idpel}}" >{{$d->reg_tgl_pasang}}</td>
                      <td class="aktivasi" data-id="{{$d->reg_idpel}}" >{{date('d-m-Y',strtotime($d->input_tgl))}}</td>
                      <td class="aktivasi" data-id="{{$d->reg_idpel}}" >{{$d->paket_nama}}</td>
                      <td class="aktivasi" data-id="{{$d->reg_idpel}}" >{{$d->router_nama}}</td>
                      <td class="aktivasi" data-id="{{$d->reg_idpel}}" >{{$d->reg_username}}</td>
                      <td class="aktivasi" data-id="{{$d->reg_idpel}}" >{{$d->input_alamat_pasang}}</td>
                      <td>{{$d->reg_catatan}}</td>
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
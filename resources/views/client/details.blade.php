@extends('layout.pelanggan')
@section('content')

<div class="content">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
<a href="{{route('client.index')}}"><h3 class="text-light"><i class="fas fa-angle-double-left"></i> RINCIAN LAYANAN</h3></a>
                
            </div>
        </div>
    </div>
    <div class="page-inner mt--3">
        <div class="row mt--5">
          <div class="col">
            <div class="card card-stats card-round">
              <div class="card-body ">
                <ul class="list-group">
                  <li class="list-group-item"><div>Nomor Layanan <br><h5 class="font-weight-bold">{{$details->reg_nolayanan}}</h5></div></li>
                  <li class="list-group-item"><div>Jenis Layanan <br><h5 class="font-weight-bold">{{$details->reg_layanan}}</h5></div></li>
                  <li class="list-group-item"><div>Paket <br><h5 class="font-weight-bold">{{$details->paket_nama}}</h5></div></li>
                  <li class="list-group-item"><div>Tanggal Daftar <br><h5 class="font-weight-bold">{{$details->input_tgl}}</h5></div></li>
                  <li class="list-group-item"><div>Tanggal Isolir <br><h5 class="font-weight-bold">{{date('d-m-Y',strtotime($details->reg_tgl_jatuh_tempo))}}</h5></div></li>
                  <li class="list-group-item"><div>Alamat Pasang <br><h5 class="font-weight-bold">{{$details->input_alamat_pasang}}</h5></div></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
    </div>
@endsection
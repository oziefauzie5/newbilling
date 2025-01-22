@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    @role('admin|STAF ADMIN')
          <div class="row">
            <div class="col-6 col-sm-4 col-lg-2">
              <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">{{$total_deaktivasi}}</div>
                    <div class="text-muted mb-3">Total Deaktivasi</div>
                  </div>
                </div>
              </div>
              <div class="col-6 col-sm-4 col-lg-2">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">{{$deaktivasi_month}}</div>
                    <div class="text-muted mb-3">Deaktivasi Bulan ini</div>
                  </div>
                </div>
              </div>
              <div class="col-6 col-sm-4 col-lg-2">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">{{$ont_hilang}}</div>
                    <div class="text-muted mb-3">Perangkat hilang</div>
                  </div>
                </div>
              </div>
              <div class="col-6 col-sm-4 col-lg-2">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">{{$adaptor_hilang}}</div>
                    <div class="text-muted mb-3">adaptor hilang</div>
                  </div>
                </div>
              </div>
              <div class="col-6 col-sm-4 col-lg-2">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">{{$lengkap}}</div>
                    <div class="text-muted mb-3">Perangkat lengkap</div>
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
                  <th>Aksi</th>
                  <th>NO LAYANAN</th>
                  <th>PELANGGAN</th>
                  <th>TGL JT TEMPO</th>
                  <th>TGL PASANG</th>
                  <th>TGL Deaktivasi</th>
                  <th>ALAMAT PASANG</th>
                  <th>NOTE</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_registrasi as $d)
                <tr>
                      <td><button  class="btn  btn-sm ml-auto m-1 btn-success">Print</button></td>
                      <td class="href" data-id="{{$d->reg_idpel}}">{{$d->reg_nolayanan}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}">{{$d->input_nama}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}">{{date('d-m-Y',strtotime($d->reg_tgl_jatuh_tempo))}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}">{{date('d-m-Y',strtotime($d->reg_tgl_pasang))}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}">{{date('d-m-Y',strtotime($d->reg_tgl_deaktivasi))}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}">{{$d->input_alamat_pasang}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}">{{$d->reg_catatan}}</td>
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
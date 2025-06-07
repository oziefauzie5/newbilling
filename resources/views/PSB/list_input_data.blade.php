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




@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="d-flex align-items-center">
            <h4 class="card-title">DATA FOLLOW UP</h4>
          </div>
        </div>
        <div class="card-body">
          <a href="{{route('admin.psb.index')}}"><button class="btn  btn-sm ml-auto m-1 btn-primary ">
            <i class="fas fa-angle-double-left "></i>
            Kembali
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
            <table id="edit_inputdata" class=" table table-striped table-hover text-nowrap" >
              <thead>
                <tr>
                  <th>No. Layanan</th>
                  <th>Nama</th>
                  <th>Jatuh Tempo</th>
                  <th>Whatsapp</th>
                  <th>Whatsapp Alternatif</th>
                  <th>Alamat Pasang</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($list_followup as $d)
                <tr id="{{$d->id}}">
                      <td>{{$d->reg_nolayanan}}</td>
                      <td>{{$d->input_nama}}</td>
                      <td>{{date('d-m-Y', strtotime($d->reg_tgl_jatuh_tempo))}}</td>
                      <td>{{$d->input_hp}}</td>
                      <td>{{$d->input_hp_2}}</td>
                      <td>{{$d->input_alamat_pasang}}</td>
                    </tr>
                    @endforeach
              </tbody>
            </table>
            <hr>
            <div class="d-flex align-items-center">
              <h4 class="card-title">LIST DATA FOLLOW UP </h4> 
            </div>
            <hr>
            <ul>Noted
              <li>Copy dan kirim ke group</li>
            </ul>
            <textarea class="form-control" name="" rows="5" required>
@foreach ($list_followup as $d)
Nama : {{$d->input_nama}}
Jatuh Tempo : {{date('d-m-Y', strtotime($d->reg_tgl_jatuh_tempo))}}
Alamat : {{$d->input_alamat_pasang}}
Hp 1 : 0{{$d->input_hp}}
Hp 2 : 0{{$d->input_hp_2}}
Sales : {{$d->name}}
Sub Sales : {{$d->input_subseles}}

@endforeach
            </textarea>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection




@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
            <div class="col-10">
              <h4 class="card-title">{{$tiket->tiket_jenis}}</h4>
            </div>
            <div class="col-2">
              <h4 class="card-title">T-{{$tiket->tiket_id}}</h4>
            </div>
            </div>
            <hr>
            <form action="{{route('admin.tiket.tiket_update',['id'=>$tiket->tiket_id])}}" method="post" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nomor Tiket</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="tiket_id" required value="{{ $tiket->tiket_id }}">
                </div>
                <label class="col-sm-2 col-form-label">Jenis Laporan</label>
                <div class="col-sm-4">
                  <input class="form-control" required name="tiket_jenis" value="{{ $tiket->tiket_jenis }}">
                </div>
                </div>
              <div class="form-group row">
              <label class="col-sm-2 col-form-label">Nama Pelanggan</label>
              <div class="col-sm-4">
                <input type="text" class="form-control"  value="{{ $tiket->input_nama }}" name="tiket_pelanggan">
              </div>
              <label class="col-sm-2 col-form-label">Status Tiket</label>
              <div class="col-sm-4">
                <input type="text" class="form-control"  value="{{ $tiket->tiket_status }}" name="tiket_idpel">
              </div>
              </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Nama Laporan</label>
              <div class="col-sm-10">
                <input type="text" class="form-control"  value="{{ $tiket->tiket_nama }}" name="tiket_nama">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Jadwal Kunjungan</label>
              <div class="col-sm-4">
                <input type="text" class="form-control datepicker"  value="{{ $tiket->tiket_waktu_kunjungan }}" name="tiket_waktu_kunjungan">
              </div>
              <label class="col-sm-2 col-form-label">Waktu Dibuat</label>
              <div class="col-sm-4">
                <input type="text" class="form-control datepicker"  value="{{date('d-m-Y h:m',strtotime( $tiket->tgl_buat))}}" name="tiket_waktu_kunjungan">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Keterangan Komplain</label>
              <div class="col-sm-10">
                <textarea type="text" class="form-control"  name="tiket_keterangan" rows="5">{{ $tiket->tiket_keterangan }}</textarea>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
<div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">TIKET CLOSED</h4>
              <hr>
            
                <div class="form-group row">
               @if($tiket->tiket_pending)
               <label class="col-sm-2 col-form-label div_tiket_pending" >Alasan Pending</label>
               <div class="col-sm-4 div_tiket_pending" >
               <input name="" id="" value="{{$tiket->tiket_pending}}" type="text" class="form-control">
               </div>
               @endif
                  <label class="col-sm-2 col-form-label div_tiket_closed" >Waktu Penanganan</label>
                  <div class="col-sm-4 div_tiket_closed" >
                  <input name="" id="" value="{{date('d-m-Y h:m', strtotime($tiket->tiket_waktu_penanganan))}}" type="text" class="form-control ">
                  </div>
                </div>
              
                <div class="form-group row ">
                  <label class="col-sm-2 col-form-label">Teknisi 1</label>
                  <div class="col-sm-4">
                  <input name="" value="{{$tiket->name}}" class="form-control" id="">
                  </div>
                  <label class="col-sm-2 col-form-label">Teknisi 2</label>
                  <div class="col-sm-4">
                  <input name="" type="text" value="{{$tiket->tiket_teknisi2}}" class="form-control" id="">
                  </div>
                </div>
                <div class="form-group row div_tiket_closed"  >
                  <label class="col-sm-2 col-form-label">Kendala</label>
                  <div class="col-sm-4">
                  <textarea name="" value="" class="form-control"cols="30" rows="5">{{$tiket->tiket_kendala}}</textarea>
                  </div>
                  <label class="col-sm-2 col-form-label">Tindakan yang dilakukan</label>
                  <div class="col-sm-4">
                  <textarea name="" class="form-control"cols="30" rows="5">{{$tiket->tiket_tindakan}}</textarea>
                  </div>
                </div>
                <div class="form-group row div_tiket_closed"  >
                  <label class="col-sm-2 col-form-label">Foto Laporan Kerja</label>
                  <div class="col-sm-4">
                  <img src="{{ asset('storage/laporan-kerja/'.$tiket->tiket_foto) }}" width="100%" alt="" title=""></img>
                </div>
                  <label class="col-sm-2 col-form-label">Barang yang digunakan</label>
                  <div class="col-sm-4">
                  <textarea name="" value="" class="form-control"cols="30" rows="5">{{$tiket->tiket_barang}}</textarea>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>

{{-- ///////////////// --}}


  </div>
</div>

@endsection
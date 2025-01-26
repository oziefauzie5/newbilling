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
            <h4 class="card-title">TIKET PROGRESS</h4>
            <hr>
            <form action="{{route('admin.tiket.tiket_update',['id'=>$tiket->tiket_id])}}" method="post" enctype="multipart/form-data">
              @csrf
              @method('PUT')
          
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Jenis Laporan</label>
              <div class="col-sm-4">
                <input class="form-control readonly" name="tiket_jenis" value="{{ $tiket->tiket_jenis }}">
              </div>
              <label class="col-sm-2 col-form-label">Nama Pelanggan</label>
              <div class="col-sm-4">
                <input type="text" class="form-control readonly"  value="{{ $tiket->input_nama }}" name="tiket_pelanggan">
              </div>
              </div>
          
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Update Status</label>
                <div class="col-sm-4">
                <select name="tiket_status" class="form-control" id="tiket_status">
                  <option value="">- Pilih -</option>
                  <option value="Pending">Pending</option>
                  <option value="Closed">Closed</option>
                </select>
                </div>
                <label class="col-sm-2 col-form-label div_tiket_pending"  style="display:none;">Alasan Pending</label>
                <div class="col-sm-4 div_tiket_pending"  style="display:none;">
                <input name="tiket_pending" id="tiket_pending" type="text" class="form-control">
                </div>
                <label class="col-sm-2 col-form-label div_tiket_closed"  style="display:none;">Waktu Penanganan</label>
                <div class="col-sm-4 div_tiket_closed"  style="display:none;">
                <input name="tiket_waktu_penanganan" id="tiket_waktu_penanganan" type="datetime-local" value="{{date('Y-m-d h:s')}}" class="form-control ">
                </div>
              </div>
              <div class="form-group row ">
                <label class="col-sm-2 col-form-label">Teknisi 1</label>
                <div class="col-sm-4">
                <select name="tiket_teknisi1" class="form-control" id="tiket_teknisi1">
                  <option value="">- Pilih -</option>
                  @foreach ($teknisi as $t)
                  <option value="{{$t->user_id}}|{{$t->user_nama}}">{{$t->user_nama}}</option>
                  @endforeach
                </select>
                </div>
                <label class="col-sm-2 col-form-label">Teknisi 2</label>
                <div class="col-sm-4">
                <input name="tiket_teknisi2" type="text" class="form-control" id="tiket_teknisi2">
                </div>
              </div>
              <div class="form-group row div_tiket_closed"  style="display:none;" >
                <label class="col-sm-2 col-form-label">Kendala</label>
                <div class="col-sm-4">
                <textarea name="tiket_kendala" id="tiket_kendala" class="form-control"cols="30" rows="5"></textarea>
                </div>
                <label class="col-sm-2 col-form-label">Tindakan yang dilakukan</label>
                <div class="col-sm-4">
                <textarea name="tiket_tindakan" id="tiket_tindakan" class="form-control"cols="30" rows="5"></textarea>
                </div>
              </div>
              
              <div class="form-group row div_tiket_closed"  style="display:none;" >
                <label class="col-sm-2 col-form-label">Foto Laporan Kerja</label>
                <div class="col-sm-4">
                <input type="file" class="form-control" name="tiket_foto">
              </div>
              </div>
            </div>

                 <div class="card-footer">
                  <button type="button" class="btn  ">Batal</button>
                  <button type="submit" class="btn btn-primary float-right submit_tiket form">Simpan</button>
                  </div>
              </div>
              <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">TIKET BELUM DIKERJAKAN</h4>
            <hr>
            <div class="form-group row">
                <div class="col-sm-12">
                <textarea name="tiket_menunggu" class="form-control readonly"cols="50" rows="15">
WAITING LIST {{$tiket_count}} TICKETS PROJECT

@foreach($tiket_menunggu as $antrian)
{{$loop->iteration}}. {{$antrian->input_nama}}
      Waktu Laporan : {{date('d-m-y h:m',strtotime($antrian->tgl_buat))}}
      Keluhan       : {{$antrian->tiket_nama}}
@endforeach
                </textarea>
                </div>
              </div>
              </div>
          </div>
        </div>
    </div>
             
              </form>
          </div>
        </div>
      </div>
    
  </div>
</div>

@endsection
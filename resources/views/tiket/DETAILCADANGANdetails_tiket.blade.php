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
                <label class="col-sm-2 col-form-label">Site</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control readonly" id="tiket_site" name="tiket_site" value="{{ $tiket->site_nama }}">
                </div>
                <label class="col-sm-2 col-form-label">Nomor Tiket</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control readonly" id="tiket_id" name="tiket_id" value="{{ $tiket->tiket_id }}">
                </div>
                </div>
              <div class="form-group row">
              <label class="col-sm-2 col-form-label">Nama Pelanggan</label>
              <div class="col-sm-4">
                <input type="text" class="form-control readonly" id="tiket_nama_pelanggan" name="tiket_nama_pelanggan" value="{{ $tiket->input_nama }}" >
              </div>
              <label class="col-sm-2 col-form-label">Jenis Laporan</label>
              <div class="col-sm-4">
                <input class="form-control readonly" id="tiket_jenis" name="tiket_jenis" value="{{ $tiket->tiket_jenis }}">
              </div>
              </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Nama Laporan</label>
              <div class="col-sm-10">
                <input type="text" class="form-control readonly" id="tiket_nama" name="tiket_nama" value="{{ $tiket->tiket_nama }}" >
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Jadwal Kunjungan</label>
              <div class="col-sm-4">
                <input type="text" class="form-control readonly" name="tiket_waktu_kunjungan" value="{{ $tiket->tiket_jadwal_kunjungan }}" >
              </div>
              <label class="col-sm-2 col-form-label">Waktu Dibuat</label>
              <div class="col-sm-4">
                <input type="text" class="form-control readonly"  value="{{date('d-m-Y h:m',strtotime( $tiket->tgl_buat))}}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Keterangan Komplain</label>
              <div class="col-sm-10">
                <textarea type="text" class="form-control readonly"  name="tiket_keterangan" rows="5">{{ $tiket->tiket_keterangan }}</textarea>
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
            
          
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Update Status</label>
                <div class="col-sm-4">
                <select class="form-control" name="tiket_status" id="tiket_status" required>
                  <option value="">- Pilih -</option>
                  <option value="Pending">Pending</option>
                  <option value="Closed">Closed</option>
                </select>
                </div>
                <label class="col-sm-2 col-form-label div_tiket_ket_pending"  style="display:none;">Alasan Pending</label>
                <div class="col-sm-4 div_tiket_ket_pending"  style="display:none;">
                <input type="text" class="form-control" name="tiket_ket_pending" id="tiket_ket_pending"  >
                </div>
                <label class="col-sm-2 col-form-label div_tiket_closed"  style="display:none;">Waktu Penanganan</label>
                <div class="col-sm-4 div_tiket_closed"  style="display:none;">
                <input name="tiket_waktu_penanganan" id="tiket_waktu_penanganan" type="datetime-local" value="{{date('Y-m-d h:s')}}" class="form-control ">
                </div>
              </div>
              <div class="form-group row ">
                <label class="col-sm-2 col-form-label">Teknisi 1</label>
                <div class="col-sm-4">
                <select name="tiket_teknisi1" class="form-control" id="tiket_teknisi1" required>
                  <option value="">- Pilih -</option>
                  @foreach ($teknisi as $t)
                  <option value="{{$t->user_id}}|{{$t->user_nama}}">{{$t->user_nama}}</option>
                  @endforeach
                </select>
                </div>
                <label class="col-sm-2 col-form-label">Teknisi 2</label>
                <div class="col-sm-4">
                <input name="tiket_teknisi2" id="tiket_teknisi2" type="text" class="form-control" required>
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
                <input type="file" class="form-control" name="tiket_foto" id="tiket_foto">
              </div>
              </div>
              <div class="form-group row">
                <div class="form-check div_tiket_closed"  style="display:none;">
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" id="ganti_barang" value="1" name="ganti_barang">
                    <span class="form-check-sign">Ganti Barang</span>
                  </label>
                </div>
                <div class="form-check div_tiket_closed"  style="display:none;">
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" id="ganti_lainnya" value="1" name="ganti_lainnya">
                    <span class="form-check-sign">Lain-lain</span>
                  </label>
                </div>
              </div>

              <div class="form-group row">
                <div class="form-check div_ganti_barang"  style="display:none;">
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" id="ck_ganti_pactcore" value="1" name="pactcore">
                    <span class="form-check-sign">Pachtcore</span>
                  </label>
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" id="ck_ganti_adaptor" value="1" name="adaptor">
                    <span class="form-check-sign">Adaptor</span>
                  </label>
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" id="ck_ganti_ont" value="1" name="ck_ganti_ont">
                    <span class="form-check-sign">ONT</span>
                  </label>
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" id="ck_ganti_dropcore" value="1" name="ck_ganti_dropcore">
                    <span class="form-check-sign">Dropcore</span>
                  </label>
                </div>
              </div>
              {{-- VALIDASI PACTCORE --}}
              <div class="row">
              <div class="col-2">
                <div class="form-group div_ganti_pactcore notif_pactcore"  style="display:none;">
                  <label class=" div_ganti_pactcore"  style="display:none;">Kode Pactcore</label>
                  <input name="tiket_barang[]" id="kode_pactcore" type="number" class="form-control ">
                  <div class="pesan_pactcore"></div>
                </div>
                </div>
                <div class="col-2">
                <div class="form-group div_ganti_pactcore notif_pactcore"  style="display:none;">
                  <label class=" div_ganti_pactcore"  style="display:none;">Kategori Barang</label>
                  <input name="tiket_ktg[]" id="ktg_pactcore" type="text" class="form-control readonly"  >
                </div>
                </div>
                <div class="col-2">
                <div class="form-group div_ganti_pactcore notif_pactcore"  style="display:none;">
                  <label class=" div_ganti_pactcore"  style="display:none;">Jumlah digunakan</label>
                  <input name="tiket_jumlah[]" id="jumlah_pactcore" type="number" value="1" class="form-control" readonly>
                </div>
                </div>
                <div class="col-2">
                <div class="form-group div_ganti_pactcore notif_pactcore"  style="display:none;">
                  <label class=" div_ganti_pactcore"  style="display:none;">harga</label>
                  <input name="tiket_harga[]" id="harga_pactcore" type="number" value="1" class="form-control" readonly>
                </div>
                </div>
                <div class="col-2">
                <div class="form-group div_ganti_pactcore notif_pactcore"  style="display:none;">
                  <label class=" div_ganti_pactcore"  style="display:none;">Status</label>
                  <input name="tiket_status[]" id="status_pactcore" type="number" value="1" class="form-control" readonly>
                </div>
                </div>
                </div>
              {{-- END VALIDASI PACTCORE --}}
              {{-- VALIDASI ADAPTOR --}}
              <div class="row">
              <div class="col-2">
                <div class="form-group div_ganti_adaptor notif_adaptor"  style="display:none;">
                  <label class=" div_ganti_adaptor"  style="display:none;">Kode Adaptor</label>
                  <input name="tiket_barang[]" id="kode_adaptor" type="number" class="form-control ">
                  <div class="pesan_adaptor"></div>
                </div>
                </div>
                <div class="col-2">
                <div class="form-group div_ganti_adaptor notif_adaptor"  style="display:none;">
                  <label class=" div_ganti_adaptor"  style="display:none;">Kategori Barang</label>
                  <input name="tiket_ktg[]" id="ktg_adaptor" type="text" class="form-control readonly" required>
                </div>
                </div>
                <div class="col-2">
                <div class="form-group div_ganti_adaptor notif_adaptor"  style="display:none;">
                  <label class=" div_ganti_adaptor"  style="display:none;">Jumlah digunakan</label>
                  <input name="tiket_jumlah[]" id="tiket_jumlah" type="number" value="1" class="form-control" readonly>
                </div>
                </div>
                <div class="col-2">
                  <div class="form-group div_ganti_adaptor notif_adaptor"  style="display:none;">
                    <label class=" div_ganti_adaptor"  style="display:none;">Harga</label>
                    <input name="tiket_harga[]" id="tiket_harga" type="number" class="form-control" readonly>
                  </div>
                  </div>
                <div class="col-2">
                  <div class="form-group div_ganti_adaptor notif_adaptor"  style="display:none;">
                    <label class=" div_ganti_adaptor"  style="display:none;">Status</label>
                    <input name="tiket_status[]" id="status_adaptor" type="number" value="1" class="form-control" readonly>
                  </div>
                  </div>
                </div>
              {{-- END VALIDASI ADAPTOR --}}
              {{-- VALIDASI DROPCORE --}}
              <div class="row">
              <div class="col-2">
                <div class="form-group div_ganti_dropcore notif_dropcore"  style="display:none;">
                  <label class=" div_ganti_dropcore"  style="display:none;">Kode Dropcore</label>
                  <input name="tiket_barang[]" id="kode_dropcore" type="number" class="form-control ">
                  <div class="pesan_dropcore"></div>
                </div>
                </div>
                <div class="col-2">
                <div class="form-group div_ganti_dropcore notif_dropcore"  style="display:none;">
                  <label class=" div_ganti_dropcore"  style="display:none;">Kategori Barang</label>
                  <input name="tiket_ktg[]" id="ktg_dropcore" type="text" class="form-control " readonly>
                </div>
                </div>
                <div class="col-2">
                <div class="form-group div_ganti_dropcore notif_dropcore"  style="display:none;">
                  <label class=" div_ganti_dropcore"  style="display:none;">Before</label>
                  <input name="tiket_before" id="before" type="text" class="form-control " readonly>
                </div>
                </div>
                <div class="col-2">
                <div class="form-group div_ganti_dropcore notif_dropcore"  style="display:none;" placeholder="After">
                  <label class=" div_ganti_dropcore"  style="display:none;">After</label>
                  <input name="tiket_after" id="after" type="text" class="form-control ">
                  <div id="pesan_over"></div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group div_ganti_dropcore notif_dropcore"  style="display:none;" >
                  <label class=" div_ganti_dropcore"  style="display:none;">Total</label>
                  <input name="tiket_jumlah[]" id="total" type="number" class="form-control" readonly >
                </div>
                </div>
              <div class="col-2">
                <div class="form-group div_ganti_dropcore notif_dropcore"  style="display:none;" >
                  <label class=" div_ganti_dropcore"  style="display:none;">Harga</label>
                  <input name="tiket_harga[]" id="tiket_harga_dropcore" type="number" class="form-control" readonly >
                </div>
                </div>
              <div class="col-2">
                <div class="form-group div_ganti_dropcore notif_dropcore"  style="display:none;" >
                  <label class=" div_ganti_dropcore"  style="display:none;">Status</label>
                  <input name="tiket_status[]" id="status_dropcore" type="number" class="form-control" readonly>
                </div>
                </div>
                </div>
                {{-- END VALIDASI DROPCORE --}}
                {{-- VALIDASI ONT --}}
              <div class="row">
              <div class="col-2">
                <div class="form-group div_ganti_ont notif_ont"  style="display:none;">
                  <label class=" div_ganti_ont"  style="display:none;">Kode ONT</label>
                  <input name="tiket_barang[]" id="kode_ont" type="number" class="form-control " >
                  <div class="pesan_ont"></div>
                </div>
                </div>
                <div class="col-2">
                <div class="form-group div_ganti_ont notif_ont"  style="display:none;">
                  <label class=" div_ganti_ont"  style="display:none;">Kategori Barang</label>
                  <input name="tiket_ktg[]" id="ktg_ont" type="text" class="form-control " readonly>
                </div>
                </div>
                <div class="col-2">
                <div class="form-group div_ganti_ont notif_ont"  style="display:none;">
                  <label class=" div_ganti_ont"  style="display:none;">Jumlah digunakan</label>
                  <input name="tiket_jumlah[]" id="tiket_jumlah" type="number" value="1" class="form-control" readonly>
                </div>
                </div>
                <div class="col-2">
                <div class="form-group div_ganti_ont notif_ont"  style="display:none;">
                  <label class=" div_ganti_ont"  style="display:none;">Harga</label>
                  <input name="tiket_harga[]" id="tiket_harga" type="number"  class="form-control" readonly>
                </div>
                </div>
                <div class="col-2">
                <div class="form-group div_ganti_ont notif_ont"  style="display:none;">
                  <label class=" div_ganti_ont"  style="display:none;">Status</label>
                  <input name="tiket_status[]" id="status_ont" type="number" value="1" class="form-control" readonly>
                </div>
                </div>
                </div>
                 {{-- END VALIDASI ONT --}}
                 <div class="card-footer">
                  <button type="button" class="btn  ">Batal</button>
                  <button type="submit" class="btn btn-primary float-right submit_tiket">Simpan</button>
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
Nama : {{ $tiket->input_nama }}
No. Layanan : {{ $tiket->reg_nolayanan }}

WAITING LIST {{$tiket_count}} TICKETS

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
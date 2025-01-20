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
            <h4 class="card-title">TIKET PROGRESS</h4>
            <hr>
          
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
                <input name="tiket_waktu_penanganan" id="tiket_waktu_penanganan" type="datetime-local" class="form-control ">
                </div>
              </div>
            
              <div class="form-group row ">
                <label class="col-sm-2 col-form-label">Teknisi 1</label>
                <div class="col-sm-4">
                <select name="tiket_teknisi1" class="form-control" id="tiket_teknisi1">
                  <option value="">- Pilih -</option>
                  @foreach ($teknisi as $t)
                  <option value="{{$t->user_id}}">{{$t->user_nama}}</option>
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
              <div class="form-group row">
                <div class="form-check div_tiket_closed"  style="display:none;">
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" id="ck_ganti_pactcore" value="1" name="pactcore">
                    <span class="form-check-sign">Ganti Pachtcore</span>
                  </label>
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" id="ck_ganti_adaptor" value="1" name="adaptor">
                    <span class="form-check-sign">Ganti Adaptor</span>
                  </label>
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" id="ck_ganti_ont" value="1" name="ck_ganti_ont">
                    <span class="form-check-sign">Ganti ONT</span>
                  </label>
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" id="ck_ganti_dropcore" value="1" name="ck_ganti_dropcore">
                    <span class="form-check-sign">Ganti Dropcore</span>
                  </label>
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" id="ck_lain_lain" value="1" name="ck_lain_lain">
                    <span class="form-check-sign">Lainnya</span>
                  </label>
                </div>
              </div>
              {{-- VALIDASI PACTCORE --}}
              <div class="form-group row">
                <label class="col-sm-2 col-form-label div_ganti_pactcore"  style="display:none;">Kode Pactcore</label>
                <div class="col-sm-4 div_ganti_pactcore tiket_notif_1"  style="display:none;">
                  <input name="tiket_barang1" id="ganti_pactcore" type="text" class="form-control ">
                  <div class="tiket_pesan_1"></div>
                </div>
                <label class="col-sm-2 col-form-label div_ganti_pactcore"  style="display:none;">Nama Barang</label>
                <div class="col-sm-4 div_ganti_pactcore tiket_notif_1"  style="display:none;">
                  <input name="tiket_nama_barang1" id="nama_ganti_pactcore" type="text" class="form-control " readonly>
                </div>
                </div>
              {{-- END VALIDASI PACTCORE --}}
              {{-- VALIDASI ADAPTOR --}}
              <div class="form-group row">
                <label class="col-sm-2 col-form-label div_ganti_adaptor"  style="display:none;">Kode Adaptor</label>
                <div class="col-sm-4 div_ganti_adaptor tiket_notif_2"  style="display:none;">
                  <input name="tiket_barang2" id="ganti_adaptor" type="text" class="form-control ">
                  <div class="tiket_pesan_2"></div>
                </div>
                <label class="col-sm-2 col-form-label div_ganti_adaptor"  style="display:none;">Nama Barang</label>
                <div class="col-sm-4 div_ganti_adaptor tiket_notif_2"  style="display:none;">
                  <input name="tiket_nama_barang2" id="nama_ganti_adaptor" type="text" class="form-control " readonly>
                </div>
                </div>
              {{-- END VALIDASI ADAPTOR --}}
              {{-- VALIDASI DROPCORE --}}
              <div class="form-group row">
                <label class="col-sm-2 col-form-label div_ganti_dropcore"  style="display:none;">Kode Dropcore</label>
                <div class="col-sm-4 div_ganti_dropcore tiket_notif_3"  style="display:none;">
                  <input name="tiket_barang3" id="ganti_dropcore" type="text" class="form-control ">
                  <div class="tiket_pesan_3"></div>
                </div>
                <label class="col-sm-2 col-form-label div_ganti_dropcore"  style="display:none;">Before</label>
                <div class="col-sm-4 div_ganti_dropcore tiket_notif_3"  style="display:none;">
                  <input name="tiket_before" id="before" type="text" class="form-control " readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label div_ganti_dropcore"  style="display:none;">After</label>
                <div class="col-sm-4 div_ganti_dropcore tiket_notif_3"  style="display:none;">
                  <input name="tiket_after" id="after" type="text" class="form-control ">
                  <div id="pesan_over"></div>
                </div>
                <label class="col-sm-2 col-form-label div_ganti_dropcore"  style="display:none;">Total</label>
                <div class="col-sm-4 div_ganti_dropcore tiket_notif_3"  style="display:none;" >
                  <input name="tiket_total_kabel" id="total" type="text" class="form-control" readonly>
                </div>
                </div>
                {{-- END VALIDASI DROPCORE --}}
                {{-- VALIDASI ONT --}}
              <div class="form-group row">
                <label class="col-sm-2 col-form-label div_ganti_ont"  style="display:none;">Kode ONT</label>
                <div class="col-sm-4 div_ganti_ont tiket_notif_4"  style="display:none;">
                  <input name="tiket_barang4" id="ganti_ont" type="text" class="form-control " >
                  <div class="tiket_pesan_4"></div>
                </div>
                <label class="col-sm-2 col-form-label div_ganti_ont"  style="display:none;">Nama Barang</label>
                <div class="col-sm-4 div_ganti_ont tiket_notif_4"  style="display:none;">
                  <input name="tiket_nama_barang4" id="tiket_nama_barang4" type="text" class="form-control " readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label div_ganti_ont"  style="display:none;">Mac</label>
                <div class="col-sm-4 div_ganti_ont tiket_notif_4"  style="display:none;">
                  <input name="tiket_mac" id="tiket_mac" type="text" class="form-control " readonly>
                </div>
                <label class="col-sm-2 col-form-label div_ganti_ont"  style="display:none;" readonly>Sn</label>
                <div class="col-sm-4 div_ganti_ont tiket_notif_4"  style="display:none;">
                  <input name="tiket_sn" id="tiket_sn" type="text" class="form-control" readonly>
                </div>
                </div>
                 {{-- END VALIDASI ONT --}}
              </div>
              <div class="card-footer">
                <button type="button" class="btn  ">Batal</button>
                <button type="submit" class="btn btn-primary float-right submit_tiket">Simpan</button>
                </div>
              </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
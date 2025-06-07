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
                <label class="col-sm-2 col-form-label">Site Id</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control readonly" id="tiket_site" name="tiket_site" required value="{{ $tiket->data__site_id }}">
                </div>
                <label class="col-sm-2 col-form-label">Type Tiket</label>
                <div class="col-sm-4">
                  <input class="form-control readonly" id="tiket_type" name="tiket_type" value="{{ $tiket->tiket_type }}">
                </div>
                </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nomor Tiket</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="tiket_id" name="tiket_id" required value="{{ $tiket->tiket_id }}">
                </div>
                <label class="col-sm-2 col-form-label">Jenis Laporan</label>
                <div class="col-sm-4">
                  <input class="form-control readonly" id="tiket_jenis" name="tiket_jenis" value="{{ $tiket->tiket_jenis }}">
                </div>
                </div>
                <!-- e0:e0:c2:bf:f6:48 -->
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Id Pel</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="tiket_idpel" name="tiket_idpel" required value="{{ $tiket->tiket_idpel }}">
                </div>
                <label class="col-sm-2 col-form-label">No Layanan</label>
                <div class="col-sm-4">
                  <input class="form-control readonly" id="tiket_nolayanan" name="tiket_nolayanan" value="{{ $tiket->reg_nolayanan }}">
                </div>
                </div>
              <div class="form-group row">
              <label class="col-sm-2 col-form-label">Nama Pelanggan</label>
              <div class="col-sm-4">
                <input type="text" class="form-control readonly"  value="{{ $tiket->input_nama }}" name="tiket_pelanggan">
              </div>
              <label class="col-sm-2 col-form-label">Status Tiket</label>
              <div class="col-sm-4">
                <input type="text" class="form-control readonly"  value="{{ $tiket->tiket_status }}" name="tiket_idpel">
              </div>
              </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Nama Laporan</label>
              <div class="col-sm-10">
                <input type="text" class="form-control readonly"  value="{{ $tiket->tiket_nama }}" name="tiket_nama" id="tiket_nama">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Jadwal Kunjungan</label>
              <div class="col-sm-4">
                <input type="text" class="form-control readonly"  value="{{ date('d-m-Y', strtotime($tiket->tiket_jadwal_kunjungan)) }}" name="tiket_waktu_kunjungan">
              </div>
              <label class="col-sm-2 col-form-label">Waktu Dibuat</label>
              <div class="col-sm-4">
                <input type="text" class="form-control readonly"  value="{{date('d-m-Y h:m',strtotime( $tiket->tgl_buat))}}" name="tiket_waktu_kunjungan">
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
                <select name="tiket_status" class="form-control" id="tiket_status" required>
                  <option value="">- Pilih -</option>
                  <option value="Pending">Pending</option>
                  <option value="Closed">Closed</option>
                </select>
                </div>
                <label class="col-sm-2 col-form-label div_tiket_ket_pending"  style="display:none;">Alasan Pending</label>
                <div class="col-sm-4 div_tiket_ket_pending"  style="display:none;">
                <input name="tiket_ket_pending" id="tiket_ket_pending" type="text" class="form-control">
                </div>
                {{-- <label class="col-sm-2 col-form-label div_tiket_closed"  style="display:none;">Waktu Penanganan</label>
                <div class="col-sm-4 div_tiket_closed"  style="display:none;">
                <input name="tiket_waktu_penanganan" id="tiket_waktu_penanganan" type="datetime-local" class="form-control ">
                </div> --}}
              </div>

     

            
              <div class="form-group row div_tiket_teknisi " style="display:none;">
                <label class="col-sm-2 col-form-label">Teknisi 1</label>
                <div class="col-sm-4 notif">
                <select name="tiket_teknisi1" class="form-control" id="tiket_teknisi1">
                  <option value="">- Pilih -</option>
                  @foreach ($teknisi as $t)
                  <option value="{{$t->user_id}}|{{$t->user_nama}}">{{$t->user_nama}}</option>
                  @endforeach
                </select>
                </div>
                <label class="col-sm-2 col-form-label">Teknisi 2</label>
                <div class="col-sm-4 notif">
                <input name="tiket_teknisi2" type="text" class="form-control" id="tiket_teknisi2">
                </div>
              </div>
              <div class="form-group row div_tiket_closed"  style="display:none;" >
                <label class="col-sm-2 col-form-label">Kategori Tindakan</label>
                <div class="col-sm-4 notif">
                <select name="kate_tindakan" class="form-control" id="kate_tindakan">
                  <option value="">--Pilih Kategori--</option>
                  <option value="Ganti ONT">Ganti ONT</option>
                  <option value="Ganti Adaptor">Ganti Adaptor</option>
                  <option value="Lainnya">Lainnya</option>
                </select>
                </div>
              </div>

            <!-- JIKA CLOSE TIKET GANTI BARANG  -->
            <div class="form-group row div_ont"  style="display:none;" >
                      <label class="col-sm-2 col-form-label">Mac Address</label>
                      <div class="col-sm-4 notif_ganti">
                        <input type="text" class="form-control " value="" id="ganti_mac" name="ganti_mac" >
                        <span class="noted">Masukan Mac Address ONT Lama, Kesalahan input mac, dapat mengakibatkan pelanggan aktif menjadi non aktif jika mac digunakan oleh pelanggan yang berbeda</span>
                      </div>
                      <label  class="col-sm-2 col-form-label">Serial Number</label>
                      <div class="col-sm-4 notif_ganti">
                        <input type="text" class="form-control readonly" value="" id="ganti_sn" name="ganti_sn" >
                      </div>
                    
                      </div>
                      <div class="form-group row div_ont"  style="display:none;" >
                      <label class="col-sm-2 col-form-label">Kode ONT</label>
                      <div class="col-sm-4 notif_ganti">
                        <input type="text" class="form-control readonly " value="" id="kode_barang_ont" name="kode_barang_ont" >
                      </div>
                      <div class="col-sm-4 notif_ganti cek_mac">
                        <button type="button" class="btn btn-sm btn-primary " id="tiket_cek_ont">Cek Perangkat</button>
                      </div>

                     
                    </div>
                    <div class="form-group row div_adp"  style="display:none;" >
                      <label  class="col-sm-2 col-form-label">Kode Adaptor</label>
                      <div class="col-sm-4 notif_ganti">
                        <input type="text" class="form-control readonly notif" value="" id="kode_barang_adp" name="kode_barang_adp" >
                      </div>
                      <div class="col-sm-4 notif_ganti cek_id">
                        <button type="button" class="btn btn-sm btn-primary " id="cek_id">Data Perangakat</button>
                      </div>
                      </div>

                     

              <div class="form-group row div_tiket_closed"  style="display:none;" >
                <label class="col-sm-2 col-form-label">Kendala</label>
                <div class="col-sm-4 notif">
                <textarea name="tiket_kendala" id="tiket_kendala" class="form-control"cols="30" rows="5"></textarea>
                </div>
                <label class="col-sm-2 col-form-label">Tindakan yang dilakukan</label>
                <div class="col-sm-4 notif">
                <textarea name="tiket_tindakan" id="tiket_tindakan" class="form-control"cols="30" rows="5"></textarea>
                </div>
              </div>
                @if($ftth_instalasi->odp_id ?? 0 == 0 )
              <div class="form-group row div_tiket_topologi"  style="display:none;" >
                <label class="col-sm-2 col-form-label">POP</label>
                <div class="col-sm-4 notif">
                <input name="tiket_pop" id="tiket_pop" class="form-control readonly" value="{{$ftth_instalasi->pop_nama ?? 0}}">
              </div>
              <label class="col-sm-2 col-form-label">OLT</label>
              <div class="col-sm-4 notif">
                  <input name="tiket_olt" id="tiket_olt" class="form-control readonly" value="{{$ftth_instalasi->olt_nama ?? 0}}">
                </div>
              </div>

              <div class="form-group row div_tiket_topologi"  style="display:none;" >
                <label class="col-sm-2 col-form-label">ODC</label>
                <div class="col-sm-4 notif">
                <input name="tiket_odc" id="tiket_odc" class="form-control readonly" value="{{$ftth_instalasi->odc_nama ?? 0}}">
              </div>
              <label class="col-sm-2 col-form-label">ODP</label>
              <div class="col-sm-4 notif_valtiket">
                  <input name="tiket_odp" id="tiket_odp" class="form-control"  value="{{$ftth_instalasi->odp_id ?? 0}}">
                  <div id="pesan"></div>
                </div>
              </div>
               @endif
              <div class="form-group row div_tiket_topologi"  style="display:none;" >
                <label class="col-sm-2 col-form-label">Foto Laporan Kerja</label>
                <div class="col-sm-4 notif">
                  <input type="file" class="form-control" name="tiket_foto" id="tiket_foto">
                </div>
                </div>
                <div class="form-group row div_tiket_closed"  style="display:none;" >
                <label class="col-sm-2 col-form-label">No. SKB</label>
                <div class="col-sm-4 notif">
                <input type="text" class="form-control " name="tiket_noskb" id="tiket_noskb">
              </div>
              </div>

              <div class="form-group row div_tiket_closed"  style="display:none;">
              <label class="col-sm-2 col-form-label">Barang digunakan</label>
                <div class="col-sm-4">
                <button type="button" class="btn btn-primary btn-sm button_modal_barang " id="button_modal_barang">
                  Tambah Barang
                </button>
              </div>
                <div class="col-sm-8 ">
                  <div class="pesan"></div>
              </div>
              </div>


<!-- Modal -->
<div class="modal fade" id="modal_tambah_barang" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Form keluar barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Id Pel</label>
          <div class="col-sm-3">
            <input type="text" class="form-control" id="tiket_idpel" name="tiket_idpel" required value="{{ $tiket->tiket_idpel }}">
          </div>
          <label class="col-sm-2 col-form-label">No Layanan</label>
          <div class="col-sm-3">
            <input class="form-control readonly" id="tiket_nolayanan" name="tiket_nolayanan" value="{{ $tiket->reg_nolayanan }}">
          </div>
          </div>
      <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label">Kode barang</label>
          <div class="col-sm-3 notif_barang_id">
            <input type="text" class="form-control " id="barang_id">
            <div class="pesan_barang_id"></div>
          </div>
          <label for="" class="col-sm-2 col-form-label">Jumlah Barang</label>
          <div class="col-sm-3 notif_jumlah">
            <input type="number" class="form-control " value="0" id="jumlah_barang" >
            <div class="pesan_jumlah"></div>
          </div>
          <div class="col-sm-2">
            <button  type="button" class="btn btn-danger btn-sm  button_tambah_barang">Keranjang</button>
          </div>
      </div>
      <div class="form-group row">
        <div class="table-responsive">
        <table id="t" class="display table  table-sm table-striped table-hover text-center">
          <thead>
            <tr>
              <th>Kode Barang</th>
              <th>Kategori</th>
              <th>Nama Barang</th>
              <th>Merek</th>
              <th>Harga</th>
              <th>Stok Awal</th>
              <th>Digunakan</th>
              <th>Stok Akhir</th>
              <th>Terpakai Sebelumnya</th>
              <th>jumlah</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr>
            </tr>
          </tbody>
      </table>
      </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-sm btn-primary float-right simpan_barang_tiket">Simpan barang keluar</button>
      </div>
    </div>
  </div>
</div>
<div class="card-footer">
  <button type="button" class="btn  ">Batal</button>
  <button type="submit" class="btn btn-primary float-right submit_tiket">Simpan</button>
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
Name : {{ $tiket->input_nama }}
Customer Number : {{ $tiket->reg_nolayanan }}

WAITING LIST {{$tiket_count}} TICKETS

@foreach($tiket_menunggu as $antrian)
{{$loop->iteration}}. {{$antrian->input_nama}}
Report time : {{date('d-m-y h:m',strtotime($antrian->tgl_buat))}}
@if($antrian->tiket_jenis == 'Instalasi')
Action       : {{$antrian->tiket_nama}}
@elseif($antrian->tiket_jenis == 'Reaktivasi')
Action       : {{$antrian->tiket_nama}}
@else
Suspect       : {{$antrian->tiket_nama}}
@endif
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
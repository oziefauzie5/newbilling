@extends('layout.main')
@section('content')

<style>
  hr{
    border: none;
  height: 1px;
  /* Set the hr color */
  color: #161616;  /* old IE */
  background-color: #959292;  /* Modern Browsers */
  }
  span{
    font-size: 11px;
    color:rgb(255, 0, 0);
  }
  ul{
    font-size: 12px;
    color:rgb(255, 0, 0);
  }
</style>

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
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
           <form class="form-horizontal"action="{{route('admin.vhc.store_titik')}}" method="POST">
             @csrf

             <h3 class="mt-3 text-bolt">PELANGGAN</h3><hr>
             <div class="form-group row">
               <label class="col-sm-2 col-form-label">Nama Pemilik rumah</label>
               <div class="col-sm-10">
                 <input type="text" class="form-control" value="{{ Session::get('titik_nama') }}" name="titik_nama">
               </div>
             </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nama Titik</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" value="{{ Session::get('titik_nama_titik') }}" name="titik_nama_titik">
                </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-2 col-form-label" >ID Pelanggan</label>
                <div class="col-sm-4">
                  <input type="number"  class="form-control" name="reg_idpel" value="{{ Session::get('reg_idpel') }}" name="reg_idpel"  >
                </div>
                  <label class=" col-sm-2 col-form-label">No Whatsapp</label>
                <div class="col-sm-4">
                  <input type="number" class="form-control" value="{{ Session::get('titik_pen_jawab_hp') }}" name="titik_pen_jawab_hp" >
                </div>
              </div>
              <div class="form-group row">
                  <label for="alamat_pasang" class="col-sm-2 col-form-label">Alamat Pasang</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="{{ Session::get('titik_alamat') }}" name="titik_alamat" readonly>
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-2 col-form-label">Maps</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control"  name="titik_maps" value="{{ Session::get('titik_maps') }}" required>
                  </div>
              </div>
              <h3 class="mt-3 text-bolt">INTERNET & HADHWARE</h3><hr>

              <div class="form-group row">
                  <label for="router" class="col-sm-2 col-form-label">Router</label>
                  <div class="col-sm-10">
                    <select class="form-control"  name="titik_router" required >
                      @if( Session::get('titik_router'))
                    <option value="{{ Session::get('titik_router') }}">{{ Session::get('router_nama') }}</option>
                    <option value="">Pilih</option>
                    @foreach($data_router as $s)
                    <option value="{{$s->id}}">{{$s->router_nama}}</option>
                    @endforeach
                    @else
                    <option value="">Pilih</option>
                    @foreach($data_router as $s)
                    <option value="{{$s->id}}">{{$s->router_nama}}</option>
                    @endforeach
                    @endif
                    </select>
                  </div>
              </div>
              <div id="divip" class="form-group row" >
                <label class="col-sm-2 col-form-label">IP address</label>
                <div class="col-sm-10">
                  <input type="text"  name="titik_ip"  value="{{ Session::get('titik_ip') }}" class="form-control"  >
                  <span>Jika diisi maka ip address user ini akan di tambahkan ke address list Mikrotik (berfungsi untuk policy based routing dll)</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Username internet *</label>
              <div class="col-sm-4">
                <input type="text" id="tampil_username" name="titik_username" class="form-control hotspot" value="{{ Session::get('titik_username') }}" required >
              </div>
                <label class=" col-sm-2 col-form-label" >Passwd internet *</label>
              <div class="col-sm-4">
                <input type="text" class="form-control pwhotspot" name="titik_password" value="1234567" required >
              </div>
              </div>
              <div class="form-group row">
                <label class=" col-sm-2 col-form-label">Status perangkat</label>
              <div class="col-sm-4">
                <select type="text" name="titik_stt_perangkat" class="form-control" value="{{ Session::get('titik_stt_perangkat') }}" >
                  <option value="DIPINJAMKAN">DIPINJAMKAN</option>
                  <option value="MILIK PROBADI">MILIK PROBADI</option>
                </select>
              </div>
              <label class="col-sm-2 col-form-label">Merk perangkat</label>
              <div class="col-sm-4">
                <input type="text" name="titik_mrek" id="titik_mrek" class="form-control ont" value="{{ Session::get('titik_mrek') }}" readonly >
              </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Mac perangkat</label>
                <div class="col-sm-4">
                  <input type="text" name="titik_mac" id="titik_mac"  class="form-control ont" value="{{ Session::get('titik_mac') }}" readonly >
                </div>
                <label class=" col-sm-2 col-form-label" >SN perangkat</label>
              <div class="col-sm-4">
                <input type="text" name="titik_sn" id="titik_sn" class="form-control ont" value="{{ Session::get('titik_sn') }}" readonly >
              </div>
              </div>
              <div class="form-group row">
                <label class=" col-sm-2 col-form-label">Slot ONU</label>
                <div class="col-sm-4">
                  <input type="text" name="reg_slotonu" value="{{ Session::get('reg_slotonu') }}" class="form-control" >
                </div>
                <label class=" col-sm-2 col-form-label">ODP</label>
                <div class="col-sm-4">
                  <input type="text" name="reg_odp" value="{{ Session::get('reg_odp') }}" class="form-control" >
                </div>
              </div>
              <div class="form-group row">
              <label class=" col-sm-2 col-form-label">Kode Barang</label>
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" id="pactcore" value="1" name="pactcore" @if( Session::get('kode_pactcore')) checked @endif >
                  <span class="form-check-sign">Pachtcore</span>
                </label>
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" id="adaptor" value="1" name="adaptor" @if( Session::get('kode_adaptor')) checked @endif>
                  <span class="form-check-sign">Adaptor</span>
                </label>
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" id="ont" value="1" name="ont" @if( Session::get('kode_ont')) checked @endif>
                  <span class="form-check-sign">ONT</span>
                </label>
              </div>
              </div>


              <!-- Modal Validasi Pactcore -->
              <div class="modal fade" id="modal_pactcore" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">VALIDASI KODE PACTCORE</h5>
                    </div>
                    <div class="modal-body">
                      <div class="form-group row" id="validasi">
                        <label class="col-sm-4 col-form-label">Kode Pactcore</label>
                        <div class="col-sm-8">
                          <input type="text"  name="kode_pactcore" id="kode_pactcore" value="{{ Session::get('kode_pactcore') }}" class="form-control"  >
                          <div id="notif"></div>
                        </div>
                      </div>
                      <div id="note"></div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary hide_pachcore">Close</button>
                      <input class="btn btn-outline-secondary val_pachcore" value="Validasi"  type="button"></input>
                      <div id="buton"></div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Modal Validasi adaptor -->
              <div class="modal fade" id="modal_adaptor" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">VALIDASI KODE ADAPTOR</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="form-group row" id="validasi_adp">
                        <label class="col-sm-4 col-form-label">Kode Adaptor</label>
                        <div class="col-sm-8">
                          <input type="text"  name="kode_adaptor" id="kode_adaptor" class="form-control" value="{{ Session::get('kode_adaptor') }}" >
                          <div id="notif_adp"></div>
                        </div>
                      </div>
                      <div id="note_adp"></div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary hide_adp">Close</button>
                      <input class="btn btn-outline-secondary val_adp" value="Validasi"  type="button"></input>
                      <div id="buton_adp"></div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Modal Validasi ont -->
              <div class="modal fade" id="modal_ont" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">VALIDASI KODE ONT</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="form-group row" id="validasi_ont">
                        <label class="col-sm-4 col-form-label">Kode Ont</label>
                        <div class="col-sm-8">
                          <input type="text"  name="kode_ont" id="kode_ont"  value="{{ Session::get('kode_ont') }}" class="form-control"  >
                          <div id="notif_ont"></div>
                        </div>
                      </div>
                      <div id="note_ont"></div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary hide_ont">Close</button>
                      <input class="btn btn-outline-secondary val_ont" value="Validasi"  type="button"></input>
                      <div id="buton_ont"></div>
                    </div>
                  </div>
                </div>
              </div>

              <h3 class="mt-3">BILLING</h3><hr>
              <div class="form-group row">
                <label for="paket" class="col-sm-2 col-form-label" >Tanggal registrasi*</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" id="tampil_tgl" name="reg_tgl" value="{{Session::get('reg_tgl')}}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Sub Sales</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="tampil_subsales" name="input_subseles" value="{{Session::get('input_subseles')}}" readonly>
                </div>

            </div>
            </div>
            <h3 class="mt-3 text-bolt">CATATAN</h3><hr>
            <div class="form-group row">
              <label for="router" class="col-sm-2 col-form-label">Keterangan</label>
              <div class="col-sm-10">
              <textarea class="form-control is-invalid" id="tampil_keterangan" readonly >
              </textarea>
              </div>
          </div>
            <div class="form-group row">
              <label for="router" class="col-sm-2 col-form-label">Catatan</label>
              <div class="col-sm-10">
              <textarea class="form-control is-invalid" id="validationTextarea" name="reg_catatan">{{Session::get('reg_catatan')}}
              </textarea>
              </div>
          </div>
            
         <div class="card-footer">
           <button type="button" class="btn  ">Batal</button>
           <button type="submit" class="btn btn-primary float-right">Simpan</button>
         </div>
       </form>
      </div>
    </div>
  </div>
</div>

@endsection

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
        
        {{-- MODAL CARI DATA  --}}
<div class="modal fade" id="cari_data" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="staticBackdropLabel">Cari Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="input_data" class="display table table-striped table-hover text-nowrap" >
            <thead>
              <tr>
                <th>Tanggal Regist</th>
                <th>Nama</th>
                <th>Whatsapp</th>
                <th>Alamat Pasang</th>
              </tr>
            </thead>
            <tbody>
              @foreach($input_data as $d)
              <tr id="{{$d->id}}">
                <td>{{$d->input_tgl}}</td>
                <td>{{$d->input_nama}}</td>
                <td>{{$d->input_hp}}</td>
                <td>{{$d->input_alamat_pasang}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-sm">Submit</button>
      </div>
    </div>
  </div>
</div>
        {{-- END MODAL CARI DATA  --}}
        
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
           <form class="form-horizontal"action="{{route('admin.reg.store')}}" method="POST">
             @csrf

             <h3 class="mt-3 text-bolt">PELANGGAN</h3><hr>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nama Pelanggan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="tampil_nama" value="{{ Session::get('reg_nama') }}" data-toggle="modal" data-target="#cari_data" name="reg_nama">
                </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-2 col-form-label" >ID Pelanggan</label>
                <div class="col-sm-4">
                  <input type="number" id="tampil_idpel" class="form-control" name="reg_idpel" value="{{ Session::get('reg_idpel') }}" name="reg_idpel" readonly >
                </div>
                  <label class=" col-sm-2 col-form-label">No Layanan</label>
                <div class="col-sm-4">
                  <input type="text" id="tampil_nolay" name="reg_nolayanan" class="form-control" value="{{ Session::get('reg_nolayanan') }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                  <label for="hp" class="col-sm-2 col-form-label">No Whatsapp</label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control" id="tampil_hp" value="{{ Session::get('reg_hp') }}" name="reg_hp" readonly>
                  </div>
              </div>
              <div class="form-group row">
                  <label for="alamat_pasang" class="col-sm-2 col-form-label">Alamat Pasang</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="tampil_alamat_pasang" value="{{ Session::get('reg_alamat_pasang') }}" name="reg_alamat_pasang" readonly>
                  </div>
              </div>
              {{-- <div class="form-group row">
                  <label for="wilayah" class="col-sm-2 col-form-label">WILAYAH</label>
                  <div class="col-sm-10">
                    <select class="form-control" id="wilayah" name="wilayah" >
                      <option value="">Pilih</option>
                    </select>
                  </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Kordinat</label>
                <div class="col-sm-10">
                <input type="text" id="kordinat" name="kordinat" class="form-control" >
              </div>
              </div> --}}
              <div class="form-group row">
                  <label class="col-sm-2 col-form-label">Maps</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="tampil_maps" name="reg_maps" value="{{ Session::get('reg_maps') }}">
                  </div>
              </div>
              <h3 class="mt-3 text-bolt">INTERNET & HADHWARE</h3><hr>
              
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Layanan</label>
                <div class="col-sm-10">
                  <select class="form-control" id="layanan" name="reg_layanan"  >
                    @if( Session::get('reg_layanan'))
                    <option value="{{ Session::get('reg_layanan') }}">{{ Session::get('reg_layanan') }}</option>
                    @else
                    <option value="">Pilih</option>
                    <option value="PPP">PPP</option>
                    <option value="DHCP">DHCP</option>
                    <option value="HOTSPOT">HOTSPOT</option>
                    {{-- <option value="HOTSPOT">HOTSPOT</option> --}}
                    @endif
                  </select>
                </div>
              </div>
              <div class="form-group row">
                  <label for="router" class="col-sm-2 col-form-label">Router</label>
                  <div class="col-sm-10">
                    <select class="form-control" id="regrouter" name="reg_router" >
                      @if( Session::get('reg_router'))
                    <option value="{{ Session::get('reg_router') }}">{{ Session::get('router_nama') }}</option>
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
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">IP address</label>
                <div class="col-sm-10">
                  <input type="text"  name="reg_ip_address" value="{{ Session::get('reg_ip_address') }}" class="form-control"  >
                  <span>Jika diisi maka ip address user ini akan di tambahkan ke address list Mikrotik (berfungsi untuk policy based routing dll)</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Username internet *</label>
              <div class="col-sm-4">
                <input type="text" id="tampil_username" name="reg_username" class="form-control" value="{{ Session::get('reg_username') }}"  >
              </div>
                <label class=" col-sm-2 col-form-label">Passwd internet *</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="reg_password" value="1234567" >
              </div>
              </div>
              <div class="form-group row">
                <label class=" col-sm-2 col-form-label">Status perangkat</label>
              <div class="col-sm-4">
                <select type="text" name="reg_stt_perangkat" class="form-control" value="{{ Session::get('reg_stt_perangkat') }}" >
                  <option value="DIPINJAMKAN">DIPINJAMKAN</option>
                  <option value="MILIK PROBADI">MILIK PROBADI</option>
                </select>
              </div>
              <label class="col-sm-2 col-form-label">Merk perangkat</label>
              <div class="col-sm-4">
                <input type="text" name="reg_mrek" id="reg_mrek" class="form-control ont" value="{{ Session::get('reg_mrek') }}" readonly >
              </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Mac perangkat</label>
                <div class="col-sm-4">
                  <input type="text" name="reg_mac" id="reg_mac"  class="form-control ont" value="{{ Session::get('reg_mac') }}" readonly >
                </div>
                <label class=" col-sm-2 col-form-label" >SN perangkat</label>
              <div class="col-sm-4">
                <input type="text" name="reg_sn" id="reg_sn" class="form-control ont" value="{{ Session::get('reg_sn') }}" readonly >
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
                <label for="paket" class="col-sm-2 col-form-label">Profile langganan *</label>
                <div class="col-sm-4">
                  <select class="form-control" id="paket" name="reg_profile" >
                    @if(Session::get('reg_profile'))
                    <option value="{{(Session::get('reg_profile'))}}">{{(Session::get('paket_nama'))}}</option>
                    <option value="">Pilih</option>
                    @foreach($data_paket as $p)
                    <option value="{{$p->paket_id}}">{{$p->paket_nama}}</option>
                    @endforeach
                    @else
                    <option value="">Pilih</option>
                    @foreach($data_paket as $p)
                    <option value="{{$p->paket_id}}">{{$p->paket_nama}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
                <label for="jenis_tagihan" class=" col-sm-2 col-form-label">Jenis tagihan *</label>
                <div class="col-sm-4">
                  <select class="form-control" id="jenis_tagihan" name="reg_jenis_tagihan" >
                    @if( Session::get('reg_jenis_tagihan'))
                    <option value="{{ Session::get('reg_jenis_tagihan') }}">{{ Session::get('reg_jenis_tagihan')}}</option>
                    <option value="">Pilih</option>
                    <option value="PRABAYAR">PRABAYAR</option>
                    <option value="PASCABAYAR">PASCABAYAR</option>
                    <option value="DEPOSIT">DEPOSIT</option>
                    <option value="FREE">FREE</option>
                    @else
                    <option value="">Pilih</option>
                    <option value="PRABAYAR">PRABAYAR</option>
                    <option value="PASCABAYAR">PASCABAYAR</option>
                    <option value="DEPOSIT">DEPOSIT</option>
                    <option value="FREE">FREE</option>
                    @endif
                  </select>
                </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-2 col-form-label">Harga prorata</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="harga" name="reg_harga" value="{{Session::get('reg_harga')}}" readonly >
                </div>
                    <label class="form-check col-sm-2 col-form-label">PPN 11%&nbsp;&nbsp;
                      <input class="form-check-input checkboxppn" type="checkbox" id="ppn" value="{{$data_biaya->biaya_ppn}}" @if( Session::get('reg_ppn')) checked @endif>
                      <span class="form-check-sign"></span>
                    </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="biaya_ppn" name="reg_ppn" value="{{Session::get('reg_ppn')}}" >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Sub Sales</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="tampil_subsales" name="input_subseles" value="{{Session::get('input_subseles')}}" readonly>
                </div>
              <label class="form-check col-sm-2 col-form-label">Dana Kerja Sama &nbsp;&nbsp;
                <input class="form-check-input checkboxkerjasama" type="checkbox" id="kas" value="{{$data_biaya->dana_kas}}" @if( Session::get('reg_dana_kerjasama')) checked @endif>
                <span class="form-check-sign"></span>
              </label>
              <div class="col-sm-4">
                <input type="text" class="form-control" id="kerjasama" name="reg_dana_kerjasama" value="{{Session::get('reg_dana_kerjasama')}}" readonly>
              </div>
            </div>
              <div class="form-group row">
                  <label class="col-sm-2 col-form-label">Kode Unik</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="kode_unik" name="reg_kode_unik" value="{{Session::get('reg_kode_unik')}}" >
                </div>

                <label class="form-check col-sm-2 col-form-label">Dana Kas &nbsp;&nbsp;
                  <input class="form-check-input checkboxkas" type="checkbox" id="kas" value="{{$data_biaya->dana_kas}}" @if( Session::get('reg_dana_kas')) checked @endif>
                  <span class="form-check-sign"></span>
                </label>
              <div class="col-sm-4">
                <input type="text" class="form-control" id="biaya_kas" name="reg_dana_kas" value="{{Session::get('reg_dana_kas')}}" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label class="form-check col-sm-2 col-form-label">Invoice Suspand &nbsp;&nbsp;
              </label>
              <div class="col-sm-4">
                <select name="reg_inv_control" id="" class="form-control">
                  <option value="0" selected>SAMBUNG DARI TGL ISOLIR</option>
                  <option value="1">SAMBUNG DARI TGL BAYAR</option>
                </select>
              </div>
              {{-- <label class="form-check col-sm-2 col-form-label">Tanggal Penagihan</label> --}}
              {{-- <div class="col-sm-4">
                  <input type="text" class="form-control" id="kode_unik" name="" value="{{date('d-m-Y', strtotime($data->reg_tgl_tagih))}}" readonly >
              </div> --}}
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

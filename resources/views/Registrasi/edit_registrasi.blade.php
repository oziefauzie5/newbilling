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
          <h3 class="mt-3 text-bolt text-center">EDIT DATA BERLANGGANAN</h3>

          <h3 class="mt-3 text-bolt">PELANGGAN</h3><hr>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nama Pelanggan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="tampil_nama" value="{{ $data->input_nama}}" name="reg_nama" readonly>
                </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-2 col-form-label" >ID Pelanggan</label>
                <div class="col-sm-4">
                  <input type="number" id="tampil_idpel" class="form-control" name="reg_idpel" value="{{ $data->reg_idpel}}" readonly >
                </div>
                  <label class=" col-sm-2 col-form-label">No Layanan</label>
                <div class="col-sm-4">
                  <input type="text" id="tampil_nolay" name="reg_nolayanan" class="form-control" value="{{ $data->reg_nolayanan}}" readonly>
                </div>
              </div>
              <div class="form-group row">
                  <label for="hp" class="col-sm-2 col-form-label">No Whatsapp</label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control" id="tampil_hp" value="{{ $data->input_hp}}" name="reg_hp" readonly>
                  </div>
              </div>
              <div class="form-group row">
                  <label for="alamat_pasang" class="col-sm-2 col-form-label">Alamat Pasang</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="tampil_alamat_pasang" value="{{ $data->input_alamat_pasang}}" name="reg_alamat_pasang" readonly>
                  </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Sub Sales</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="tampil_subsales" name="input_subseles" value="{{$data->input_subseles}}" readonly>
                </div>
              </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Maps</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="tampil_maps" name="reg_maps" value="{{ $data->input_maps}}">
              </div>
            </div>
           
              <div class="form-group row">
                <div class="col-sm-6">
                @role('admin|STAF ADMIN')
                @if($data->reg_progres == '5')
                <button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#exampleModal">
                  STOP BERLANGGANAN
                </button>
                @elseif($data->reg_progres >= '5')
                <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#sambung_kembali">
                  SAMBUNG KEMBALI
                </button>
                @endif
                @endrole
               

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">STOP BERLANGGANAN</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form action="{{route('admin.psb.putus_berlanggan',['idpel'=>$data->reg_idpel])}}" method="POST">
                          @csrf
                          @method('PUT')

                          <div class="col-sm-12">
                            <div class="form-group">
                              <label for="tiket_deskripsi">Alasan Putus</label>
                              <textarea class="form-control" name="reg_catatan" rows="5"></textarea>
                            </div>
                          </div>
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label>Mac Address ONT</label>
                              <input type="text" class="form-control" name="reg_mac"  step="00.01" maxlength="17" minlength="17" value="">
                            </div>
                          </div>
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label>Status</label>
                              <select name="status" class="form-control" required>
                                <option value="PUTUS LANGGANAN">PUTUS LANGGANAN</option>
                                <option value="PUTUS SEMENTARA">PUTUS SEMENTARA</option>
                              </select>
                              {{-- <input type="text" class="form-control" name="reg_mac"  step="00.01" required maxlength="17" minlength="17" value=""> --}}
                            </div>
                          </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                          <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div> 
                </div>
                  
  
                  <!-- Modal -->
                  <div class="modal fade" id="sambung_kembali" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">SAMBUNG KEMABLI</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form action="{{route('admin.psb.sambung_kembali',['idpel'=>$data->reg_idpel])}}" method="POST">
                            @csrf
                            @method('PUT')
  
                            <div class="form-group row">
                              <label class=" col-sm-2 col-form-label">Status perangkat</label>
                            <div class="col-sm-4">
                              <select type="text" name="sam_stt_perangkat" class="form-control" value="{{ Session::get('reg_stt_perangkat') }}" >
                                <option value="DIPINJAMKAN">DIPINJAMKAN</option>
                                <option value="MILIK PROBADI">MILIK PRIBADI</option>
                              </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Merk perangkat</label>
                            <div class="col-sm-4">
                              <input type="text" name="sam_mrek" id="reg_mrek" class="form-control ont" value="{{ Session::get('reg_mrek') }}" readonly >
                            </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-2 col-form-label">Mac perangkat</label>
                              <div class="col-sm-4">
                                <input type="text" name="sam_mac" id="reg_mac"  class="form-control ont" value="{{ Session::get('reg_mac') }}" readonly >
                              </div>
                              <label class=" col-sm-2 col-form-label" >SN perangkat</label>
                            <div class="col-sm-4">
                              <input type="text" name="sam_sn" id="reg_sn" class="form-control ont" value="{{ Session::get('reg_sn') }}" readonly >
                            </div>
                            </div>
                            <div class="form-group row">
                            <label class=" col-sm-2 col-form-label">Kode Barang</label>
                            <div class="form-check">
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

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div> 
                <div class="col-sm-6">
                <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                 </div>
              </div>
            </form>
            </div>
          </div>
        </div>


        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <form class="form-horizontal"action="{{route('admin.psb.update_router',['id'=>$data->reg_idpel])}}" method="POST">
            @csrf
            @method('PUT')
            <h3 class="mt-3 text-bolt">INTERNET</h3><hr>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Layanan</label>
                <div class="col-sm-10">
                  <select class="form-control" id="layanan" name="reg_layanan"  >
                    @if( $data->reg_layanan)
                    <option value="{{ $data->reg_layanan}}">{{ $data->reg_layanan}}</option>
                    @else
                    <option value="">Pilih</option>
                    <option value="PPP">PPP</option>
                    <option value="HOTSPOT">HOTSPOT</option>
                    @endif
                  </select>
                </div>
              </div>
             {{-- @if($data->reg_layanan=='PPP') --}}
             <div class="form-group row">
              <label for="router" class="col-sm-2 col-form-label">Router</label>
              <div class="col-sm-10">
                <select class="form-control" id="regrouter" name="reg_router" >
                  @if( $data->reg_router)
                <option value="{{ $data->reg_router}}">{{ $data->router_nama}}</option>
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
             {{-- @endif --}}
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Username internet *</label>
              <div class="col-sm-4">
                <input type="text" id="tampil_username" name="reg_username" class="form-control" value="{{ $data->reg_username}}" >
              </div>
                <label class=" col-sm-2 col-form-label">Passwd internet *</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="reg_password" value="{{ $data->reg_password}}" >
              </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Status Internet :</label>
                <div class="col-sm-4">
                  @if($status=='CONNECTED')
                  <label class="col-sm-2 col-form-label font-weight-bold text-success" >{{ $status}}</label>
                  @else
                  <label class="col-sm-2 col-form-label font-weight-bold text-danger" >{{ $status}}</label>
                  @endif
                </div>
                <label class="col-sm-2 col-form-label">IP Address :</label>
              <div class="col-sm-4">
                <label class="col-sm-2 col-form-label font-weight-bold text-danger" >{{ $address}}</label>
              </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Aksi</label>
              <div class="col-sm-3 mt-1">
                <a href="{{route('admin.noc.pengecekan',['id'=>$data->reg_idpel])}}" target="_blank">
                  <button type="button" class="btn btn-sm btn-warning btn-block">
                    Remote
                  </button></a>             
                </div>
              <div class="col-sm-3 mt-1">
                <a href="{{route('admin.noc.kick',['id'=>$data->reg_idpel])}}">
                  <button type="button" class="btn btn-sm btn-danger btn-block">
                    Kick
                  </button></a>             
                </div>
              <div class="col-sm-3 mt-1">
                @if($status_secret=='false')
                <a href="{{route('admin.noc.status_secret',['id'=>$data->reg_idpel.'?stat=false'])}}">
                  <button type="button" class="btn btn-sm btn-danger btn-block">
                    Disable
                  </button></a>     
                  @elseif($status_secret=='true')
                  <a href="{{route('admin.noc.status_secret',['id'=>$data->reg_idpel.'?stat=true'])}}">
                    <button type="button" class="btn btn-sm btn-success btn-block">
                      Enable
                    </button></a>   
                    @else
                    <button type="button" class="btn btn-sm btn-danger btn-block">
                      Data tidak ditemukan
                    </button>
                  @endif        
                </div>
              </div>
              @role('admin|STAF ADMIN')
              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right">Simpan</button>
              </div>
              @endrole
            </form>
            </div>
          </div>
        </div>

        <div class="col-md-12">
          <div class="card">
            <div class="card-body"> 
              <form class="form-horizontal"action="{{route('admin.psb.update_pelanggan',['id'=>$data->reg_idpel])}}" method="POST">
                @csrf
                @method('PUT')
                <h3 class="mt-3 text-bolt"> HADHWARE</h3><hr>

              <div class="form-group row">
                <label class=" col-sm-2 col-form-label">Status perangkat</label>
              <div class="col-sm-4">
                <select type="text" name="reg_stt_perangkat" class="form-control" value="{{ $data->reg_stt_perangkat}}" >
                  <option value="DIPINJAMKAN">DIPINJAMKAN</option>
                  <option value="MILIK PROBADI">MILIK PROBADI</option>
                </select>
              </div>
              <label class="col-sm-2 col-form-label">Merk perangkat</label>
              <div class="col-sm-4">
                <input type="text" name="reg_mrek" id="edit_reg_mrek" class="form-control edit_ont" value="{{ $data->reg_mrek}}" readonly >
              </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Mac perangkat</label>
                <div class="col-sm-4">
                  <input type="text" name="reg_mac" id="edit_reg_mac"  class="form-control edit_ont" minlength="17" maxlength="17"  value="{{ $data->reg_mac}}" >
                </div>
                <label class=" col-sm-2 col-form-label" >SN perangkat</label>
              <div class="col-sm-4">
                <input type="text" name="reg_sn" id="edit_reg_sn" class="form-control edit_ont" value="{{ $data->reg_sn}}" readonly >
              </div>
              </div>
              <div class="form-group row">
                <label class=" col-sm-2 col-form-label">Slot ONU</label>
                <div class="col-sm-4">
                  <input type="text" name="reg_slotonu" value="{{ $data->reg_slotonu}}" class="form-control" >
                </div>
                <label class=" col-sm-2 col-form-label">ODP</label>
                <div class="col-sm-4">
                  <input type="text" name="reg_odp" value="{{ $data->reg_odp}}" class="form-control" >
                </div>
              </div>
              @role('admin|STAF ADMIN')
              <div class="form-group row">
                <label class=" col-sm-2 col-form-label">Kode Barang</label>
                <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" id="pactcore" value="1" name="pactcore" @if( $data->reg_kode_pactcore) checked @endif disabled>
                  <span class="form-check-sign">Pachtcore</span>
                </label>
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" id="adaptor" value="1" name="adaptor" @if( $data->reg_kode_adaptor) checked @endif disabled>
                  <span class="form-check-sign">Adaptor</span>
                </label>
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" id="edit_ont" value="1" name="edit_ont" @if( $data->reg_kode_ont) checked @endif>
                  <span class="form-check-sign">ONT</span>
                </label>
              </div>
              @endrole
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
                          <input type="text"  name="kode_pactcore" id="kode_pactcore" value="{{ $data->reg_kode_pactcore}}" class="form-control"  >
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
                          <input type="text"  name="kode_adaptor" id="kode_adaptor" class="form-control" value="{{ $data->reg_kode_adaptor}}" >
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
              <div class="modal fade" id="edit_modal_ont" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">VALIDASI KODE ONT</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="form-group row" id="validasi_ontlllllllll">
                        <label class="col-sm-4 col-form-label">Mac Address</label>
                        <div class="col-sm-8">
                          <input type="text"  name="kode_ont_lama" id="edit_kode_ont_lama"  value="{{ $data->reg_mac}}" class="form-control"  >
                        </div>
                      </div>
                      <div class="form-group row" id="edit_validasi_ont">
                        <label class="col-sm-4 col-form-label">Kode Ont baru</label>
                        <div class="col-sm-8">
                          <input type="text"  name="kode_ont" id="edit_kode_ont"  value="{{ $data->kode_ont}}" class="form-control"  >
                          <div id="edit_notif_ont"></div>
                        </div>
                      </div>
                      <div class="form-group row" id="edit_validasi_alasan">
                        <label class="col-sm-4 col-form-label">Alasan Ganti</label>
                        <div class="col-sm-8">
                          <select name="alasan" id="alasan" class="form-control">
                            <option value="">Pilih</option>
                            <option value="Rusak">Rusak</option>
                            <option value="Tukar">Tukar</option>
                            <option value="Upgrade">Upgrade</option>
                          </select>
                          <div id="edit_notif_alasan"></div>
                        </div>
                      </div>
                      <div class="form-group row" id="edit_validasi_keterangan">
                        <label class="col-sm-4 col-form-label">Keterangan</label>
                        <div class="col-sm-8">
                          <input type="text"  name="keterangan" id="keterangan" class="form-control"  >
                          <div id="edit_notif_keterangan"></div>
                        </div>
                      </div>
                      <div id="edit_note_ont"></div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary edit_hide_ont">Close</button>
                      <input class="btn btn-outline-secondary edit_val_ont" value="Validasi"  type="button"></input>
                      <div id="buton_ont"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
          <!-- <div class="card">
            <div class="card-body">  -->
            
              <h3 class="mt-3 text-bolt">CATATAN</h3><hr>
            <div class="form-group row">
              <label for="router" class="col-sm-2 col-form-label">Catatan</label>
              <div class="col-sm-10">
              <textarea class="form-control is-invalid" id="validationTextarea" name="reg_catatan">{{$data->reg_catatan}}
              </textarea>
              </div>
            </div>
            <div class="card-footer">
            @role('admin|STAF ADMIN')
            <button type="button" class="btn  ">Batal</button>
            <button type="submit" class="btn btn-primary float-right">Simpan</button>
            @endrole
            </div>
           </form>
          </div>
         </div>
        </div>
        @role('admin|STAF ADMIN')
        
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <h3 class="mt-3">BILLING</h3><hr>
              <form class="form-horizontal"action="{{route('admin.psb.update_profile',['id'=>$data->reg_idpel])}}" method="POST">
                @csrf
                @method('PUT')
              <div class="form-group row">
                <label for="paket" class="form-check col-sm-2 col-form-label">Profile langganan *</label>
                <div class="col-sm-4">
                  <select class="form-control paket" id="paket" name="reg_profile" >
                    @if($data->reg_profile)
                    <option value="{{($data->reg_profile)}}">{{($data->paket_nama)}}</option>
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
                <label for="jenis_tagihan" class="form-check col-sm-2 col-form-label">Jenis tagihan *</label>
                <div class="col-sm-4">
                  <select class="form-control" id="jenis_tagihan" name="reg_jenis_tagihan" >
                    @if( $data->reg_jenis_tagihan)
                    <option value="{{ $data->reg_jenis_tagihan}}">{{ $data->reg_jenis_tagihan}}</option>
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
                <label class=" form-check col-sm-2 col-form-label">Harga prorata</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control harga" id="harga" name="reg_harga" value="{{$data->reg_harga}}" readonly >
                </div>
                <label class="form-check col-sm-2 col-form-label">Kode Unik</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="kode_unik" name="reg_kode_unik" value="{{$data->reg_kode_unik}}" >
                </div>
              </div>
              <div class="form-group row">
                <label class="form-check col-sm-2 col-form-label">PPN 11%&nbsp;&nbsp;
                  <input class="form-check-input checkboxppn" type="checkbox" id="ppn" value="{{$data_biaya->biaya_ppn}}" @if( $data->reg_ppn) checked @endif>
                  <span class="form-check-sign"></span>
                </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control biaya_ppn" id="biaya_ppn" name="reg_ppn" value="{{$data->reg_ppn}}" >
                </div>
                  <label class="form-check col-sm-2 col-form-label">Dana Kas &nbsp;&nbsp;
                    <input class="form-check-input checkboxkas" type="checkbox" id="kas" value="{{$data_biaya->dana_kas}}" @if( $data->reg_dana_kas) checked @endif>
                    <span class="form-check-sign"></span>
                  </label>
                  <div class="col-sm-4">
                  <input type="text" class="form-control biaya_kas" id="biaya_kas" name="reg_dana_kas" value="{{$data->reg_dana_kas}}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="form-check col-sm-2 col-form-label">Dana Kerja Sama &nbsp;&nbsp;
                  <input class="form-check-input checkboxkerjasama" type="checkbox" id="kas" value="{{$data_biaya->dana_kas}}" @if( $data->reg_dana_kerjasama) checked @endif>
                  <span class="form-check-sign"></span>
                </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control kerjasama" id="kerjasama" name="reg_dana_kerjasama" value="{{$data->reg_dana_kerjasama}}" readonly>
                </div>
                <label class="form-check col-sm-2 col-form-label">Tanggal Pemasangan</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="kode_unik" name="tgl_pasang" value="{{date('d-m-Y', strtotime($data->reg_tgl_pasang))}}" readonly >
                </div>
              </div>
              <div class="form-group row">
                <label class="form-check col-sm-2 col-form-label">Tanggal Penagihan</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="" name="reg_tgl_tagih" value="{{date('d-m-Y', strtotime($data->reg_tgl_tagih))}}" readonly >
                </div>
                <label class="form-check col-sm-2 col-form-label">Tanggal Jatuh Tempo &nbsp;&nbsp;
                </label>

                <div class="col-sm-4">
                  @role('admin') 
                  <input type="text" class="form-control datepicker" name="reg_tgl_jatuh_tempo" value="{{date('d-m-Y', strtotime($data->reg_tgl_jatuh_tempo))}}" >
                  @endrole
                  @role('STAF ADMIN') 
                  <input type="text" class="form-control" name="reg_tgl_jatuh_tempo" value="{{date('d-m-Y', strtotime($data->reg_tgl_jatuh_tempo))}}" readonly >
                  @endrole
                </div>
              </div>
              <div class="form-group row">
                <label class="form-check col-sm-2 col-form-label">Invoice Suspand &nbsp;&nbsp;
                  </label>
                  <div class="col-sm-4">
                    <select name="reg_inv_control" id="" class="form-control">
                      @if($data->reg_inv_control==0)
                      <option value="0" selected>SAMBUNG DARI TGL ISOLIR</option>
                      @else
                    <option value="1" selected>SAMBUNG DARI TGL BAYAR</option>
                    @endif
                    <option value="0">SAMBUNG DARI TGL ISOLIR</option>
                    <option value="1">SAMBUNG DARI TGL BAYAR</option>
                  </select>
                </div>
                <label class="form-check col-sm-2 col-form-label">Status</label>
                <div class="col-sm-4">
                    <select name="reg_status" id="" class="form-control">
                      @if($data->reg_status)
                      <option value="{{$data->reg_status}}" selected>{{$data->reg_status}}</option>
                    @endif
                    <option value="UNPAID">UNPAID</option>
                    <option value="ISOLIR">ISOLIR</option>
                  </select>
                </div>
              </div>
              <div class="card-footer mb-3">
                <button type="submit" class="btn btn-primary float-right">Simpan</button>
              </div>
            </form>
              <div class="form-group row mt-5">
              <div class="col">
                <a href="{{route('admin.noc.isolir_manual', ['id'=>$data->reg_idpel])}}"><button type="button" class="btn btn-primary btn-sm btn-block">Isolir Manual</button></a>
              </div>
              <div class="col">
                
                <a href="{{route('admin.noc.buka_isolir_manual', ['id'=>$data->reg_idpel])}}"><button type="button" class="btn btn-primary btn-sm btn-block">Buka Isolir Manual</button></a>
              </div>
            </div>
            <div class="form-group row">
                <div class="col">

                <!-- Button trigger modal -->
<button type="button" class="btn btn-dark btn-block btn-sm" data-toggle="modal" data-target="#ubah_tgl">
Update Tanggal Jatuh Tempo
</button>

<!-- Modal -->
<div class="modal fade" id="ubah_tgl" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Update Tanggal Jatuh Tempo</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form class="form-horizontal"action="{{route('admin.psb.update_tgl_jth_tempo',['id'=>$data->reg_idpel])}}" method="POST">
        @csrf
        @method('PUT')
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label>Tanggal Jatuh Tempo</label>
              <div class="input-group">
                <input type="text" class="form-control" value="{{date('d-m-Y', strtotime($data->reg_tgl_jatuh_tempo))}}" readonly>
                <div class="input-group-append">
                </div>
              </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label>Update Tanggal aa</label>
              <div class="input-group">
                <input type="text" placeholder="Choose Date" class="form-control update_datepicker" id="update-tgl" value="" name="reg_tgl_jatuh_tempo">
                <div class="input-group-append">
                </div>
              </div>
          </div>
        </div>
        <input type="hidden" id="total_biaya_val" name="total_biaya">
        <input type="hidden" id="status" name="status">
        <input type="hidden" id="biaya" name="biaya">
        <input type="hidden" id="hari" name="hari">
        <input type="hidden" id="update_ppn" name="update_ppn" value="">
        <div class="col-sm-12">
         <h4 class="text-center">Rincian Biaya</h4>
         <h3 class="text-center" id="rincian"> - </h3>
          </div>
        <div class="col-sm-12">
         <h3 class="text-center">TOTAL PEMBAYARAN</h3>
         <h1 class="text-center" id="total_biaya">Rp. 0</h1>
          </div>
        </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
  </form>
  </div>
</div>
</div>

              </div>
                <div class="col">

                <!-- Button trigger modal -->
<button type="button" class="btn btn-info btn-block btn-sm" data-toggle="modal" data-target="#ubah_tgl">
Pindah Rumah
</button>

<!-- Modal -->
<div class="modal fade" id="ubah_tgl" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Rubah Tanggal Jatuh Tempo</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">

      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label>Tanggal Jatuh Tempo</label>
              <div class="input-group">
                <input type="text" class="form-control" value="{{date('d-m-Y', strtotime($data->reg_tgl_jatuh_tempo))}}">
                <div class="input-group-append">
                </div>
              </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label>Update Tanggal</label>
                <input type="text" placeholder="Choose Date" class="form-control" value="{{date('d-m-Y', strtotime($data->reg_tgl_jatuh_tempo))}}">
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
            <label>Tagihan</label>
                <input type="text" class="form-control" value="{{number_format($data->reg_harga+$data->reg_kode_unik + $data->reg_ppn + $data->reg_dana_kas + $data->reg_dana_kerjasama)}}">
              </div>
          </div>
        </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="button" class="btn btn-primary" id="update-tgl">Simpan</button>
    </div>
  </div>
</div>
</div>

              </div>

            </div>
            </div>
          </div>
        </div>
        @endrole 
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <h3 class="mt-3">INSTALASI</h3><hr>
              <div class="form-group row">
                <label class=" form-check col-sm-2 col-form-label">Teknisi Team</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control harga"value="{{$data->reg_teknisi_team}}" >
                </div>
                <label class="form-check col-sm-2 col-form-label">Lokasi FAT</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{{$data->reg_fat}}" >
                </div>
              </div>
              <div class="form-group row">
                  <label class=" form-check col-sm-2 col-form-label">FAT OPM</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control harga"value="{{$data->reg_fat_opm}} Dbm" >
                </div>
                <label class="form-check col-sm-2 col-form-label">HOME OPM</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{{$data->reg_home_opm}} Dbm" >
                </div>
              </div>
              <div class="form-group row">
                  <label class=" form-check col-sm-2 col-form-label">Total LOSS</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control harga"value="{{$data->reg_los_opm}} Dbm" >
                </div>
                <label class="form-check col-sm-2 col-form-label">Panjang Kabel</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{{$data->reg_penggunaan_dropcore}} Meter" >
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <h3 class="mt-3">MATERIAL</h3><hr>
              <div class="form-group row">
                  <label class=" form-check col-sm-2 col-form-label">KODEE KABEL</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control harga"value="{{$data->reg_kode_dropcore}}" >
                </div>
                <label class="form-check col-sm-2 col-form-label">KODE ONT</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{{$data->reg_kode_ont}}" >
                </div>
              </div>
              <div class="form-group row">
                  <label class=" form-check col-sm-2 col-form-label">KODE ADAPTOR</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control harga"value="{{$data->reg_kode_adaptor}} " >
                </div>
                <label class="form-check col-sm-2 col-form-label">KODE PACTCORE</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{{$data->reg_kode_pactcore}} " >
                </div>
              </div>
            </div>
          </div>
        </div>
  
  </div>
</div>

<script>
  	$(document).ready(function() {
		$('.checkboxppn').change(function () {
			if ($(this).is(":checked")) {
				var result1 = parseInt($("#ppn").val())/100 * parseInt($(".harga").val());
				if (!isNaN(result1)) {
					$('.biaya_ppn').val(result1);
				}
			} else {
				$(".biaya_ppn").val(0);
			}
		});
		$('.checkboxkas').change(function () {
			if ($(this).is(":checked")) {
        var kas = 50;
				$('.biaya_kas').val({{$data_biaya->biaya_kas}});
			} else {
				$(".biaya_kas").val(0);
			}
		});
		$('.checkboxkerjasama').change(function () {
			if ($(this).is(":checked")) {
				$('.kerjasama').val({{$data_biaya->biaya_kerjasama}});
			} else {
				$(".kerjasama").val(0);
			}
		});
										
	});
</script>

@endsection

@extends('layout.user')
@section('content')

<div class="content">
  <div class="page-inner">

    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header bg-primary">
            <div class="card-title text-light">{{$tiket->tiket_judul}}</div>
          </div>
            <div class="card-body">
              <p class="card-title">{{$tiket->tiket_deskripsi}}</p>
              <span>- {{$tiket->tgl_buat}}</span>
              
            </div>
        </div>
        <div class="card">
          <div class="card-header">
            <div class="card-title">TIKET PROGRESS</div>
          </div>
          <div class="card-body">
            <ul class="">
              @foreach($subtiket as $d)
              <li>
                    <h4 class="timeline-title">{{$d->subtiket_deskripsi}}</h4>
                    <span>STATUS : {{$d->subtiket_status}}</span><br>
                    @if($tiket->tiket_tindakan)
                    <span>TINDAKAN : {{$tiket->tiket_tindakan}}</span><br>
                    @endif
                    @if($d->subtiket_teknisi_team)
                    <span>PELAKSANA : {{$d->subtiket_teknisi_team}}</span><br>
                    @endif
                    <span>{{date('d M Y H:m:s', strtotime($d->tgl_progres))}}</span><br>
                    <span class="font-weight-bold">BY : {{$d->name}}</span><br><hr>
              </li>
              @endforeach
            </ul>
            
          </div>
        </div>
        
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-header bg-primary">
            <div class="card-title text-light ">PELANGGAN</div>
          </div>
          <div class="card-body">
              <ul class="list-group list-group-flush">
                <li class="list-group-item">{{$tiket->input_nama}}</li>
                <li class="list-group-item">{{$tiket->input_hp}}</li>
                <li class="list-group-item">{{$tiket->input_alamat_pasang}}</li>
              </ul>
          </div>
        </div>
        <div class="card">
          <div class="card-header bg-primary">
            <div class="card-title text-light ">TIKET {{$tiket->tiket_id}}</div>
          </div>
          <div class="card-body">
            
            <ul class="list-group list-group-flush">
              <li class="list-group-item">{{$tiket->tiket_departemen}}</li>
              <li class="list-group-item">{{$tiket->tiket_prioritas}}</li>
              <li class="list-group-item">{{$tiket->tiket_status}}</li>
              <li class="list-group-item">{{date('d-M-Y H:m:s', strtotime($tiket->tgl_dibuat))}}</li>
            </ul>
          </div>
        </div>
        <div class="card">
          <form action="{{route('admin.teknisi.tiket.close_tiket',['id'=> $tiket->tiket_id])}}" method="POST">
            @csrf
            @method('PUT')
          <div class="card-header bg-primary">
            <div class="card-title text-light ">TINDAKAN</div>
          </div>
          <div class="card-body">
            <div class="form-group row">
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" id="edit_pactcore" value="1" name="pactcore">
                  <span class="form-check-sign">Ganti Pachtcore</span>
                </label>
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" id="edit_adaptor" value="1" name="adaptor">
                  <span class="form-check-sign">Ganti Adaptor</span>
                </label>
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" id="edit_ont" value="1" name="edit_ont">
                  <span class="form-check-sign">Ganti ONT</span>
                </label>
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" id="edit_lain" value="1" name="edit_lain">
                  <span class="form-check-sign">Lainnya</span>
                </label>
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" id="edit_seting" value="1" name="edit_seting">
                  <span class="form-check-sign">Setting Ulang</span>
                </label>
              </div>
              {{-- MODAL VALIDASI PACTCORE --}}
              <div class="modal fade" id="edit_modal_pactcore" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">VALIDASI KODE PACTCORE</h5>
                    </div>
                    <div class="modal-body">
                      <div class="form-group row" id="edit_validasi_pactcore">
                        <label class="col-sm-4 col-form-label">Kode Pactcore</label>
                        <div class="col-sm-8">
                          <input type="text"  name="kode_pactcore" id="edit_kode_pactcore" class="form-control"  >
                          <div id="edit_notif_pactcore"></div>
                        </div>
                      </div>
                      <div id="edit_note_pactcore"></div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary edit_hide_pactcore">Close</button>
                      <input class="btn btn-outline-secondary edit_val_pactcore" value="Validasi"  type="button"></input>
                      <div id="buton"></div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Modal Validasi adaptor -->
              <div class="modal fade" id="edit_modal_adaptor" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header bg-primary">
                      <h5 class="modal-title" id="staticBackdropLabel">VALIDASI KODE ADAPTOR</h5>
                      <button type="button" class="edit_hide_adaptor close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      
                      <div class="form-group row" id="edit_validasi_adaptor">
                        <label class="col-sm-4 col-form-label">Kode Adaptor</label>
                        <div class="col-sm-8">
                          <input type="text"  name="kode_adaptor" id="edit_kode_adaptor" class="form-control edit_kode_adaptor" >
                          <div id="edit_notif_adaptor"></div>
                        </div>
                      </div>
                      <div id="edit_note_adaptor"></div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary edit_hide_adaptor">Close</button>
                      <input class="btn btn-outline-secondary edit_val_adaptor" value="Validasi"  type="button"></input>
                      <div id="buton_adaptor"></div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Modal Validasi ont -->
              <div class="modal fade " id="edit_modal_ont" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header bg-primary">
                      <h5 class="modal-title " id="staticBackdropLabel">VALIDASI KODE ONT</h5>
                      <button type="button" class="edit_hide_ont close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="form-group row" id="validasi_ont">
                        <label class="col-sm-4 col-form-label">Kode Ont sebelumya</label>
                        <div class="col-sm-8">
                          <input type="text"  name="kode_ont_lama" id="edit_kode_ont_lama"  value="{{ $tiket->reg_kode_ont}}" class="form-control"  >
                        </div>
                      </div>
                      <div class="form-group row" id="edit_validasi_ont">
                        <label class="col-sm-4 col-form-label">Kode Ont baru</label>
                        <div class="col-sm-8">
                          <input type="text"  name="kode_ont" id="edit_kode_ont"  class="form-control edit_kode_ont"  >
                          <div id="edit_notif_ont"></div>
                        </div>
                      </div>
                      <div class="form-group row" id="edit_validasi_alasan">
                        <label class="col-sm-4 col-form-label">Alasan Ganti</label>
                        <div class="col-sm-8">
                          <select name="alasan" id="alasan" class="form-control alasan">
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
                          <input type="text"  name="keterangan" id="keterangan" class="form-control keterangan"   >
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
                  {{-- END MODAL --}}
                </div>
                              
            <div id="seting_ulang" style="display:none;">
              <div  class="form-row mb-2" >
                  <div class="col-9">
                  <input type="text" class="form-control" value="{{$tiket->reg_username}}" id="myInput1">
                  </div>
                  <div class="col-3">
                  <button type="button" class="form-control" onclick="copy1()">Copy</button>
                  </div>
              </div>
              <div class="form-row mb-2">
                  <div class="col-9">
                  <input type="text" class="form-control" value="{{$tiket->reg_password}}" id="myInput2">
                  </div>
                  <div class="col-3">
                  <button type="button" class="form-control" onclick="copy2()">Copy</button>
                  </div>
              </div>
            </div>
          
            
              <div  class="form-row mb-2" id="show_ont" style="display:none;" >
                <label class="col-sm-4 col-form-label">Kode ONT</label>
                  <div class="col-12">
                  <input type="text" class="form-control"  id="edit_reg_kode_ont" name="edit_reg_kode_ont">
                  </div>
                <label class="col-sm-4 col-form-label">Mrek ONT</label>
                  <div class="col-12">
                  <input type="text" class="form-control"  id="edit_reg_mrek" name="edit_reg_mrek">
                  </div>
                <label class="col-sm-4 col-form-label">Mac Address</label>
                  <div class="col-12">
                  <input type="text" class="form-control"  id="edit_reg_mac"  name="edit_reg_mac">
                  </div>
                <label class="col-sm-4 col-form-label">Serial Number</label>
                  <div class="col-12">
                  <input type="text" class="form-control"  id="edit_reg_sn"  name="edit_reg_sn">
                  </div>
              </div>
              <div class="form-row mb-2">
                  <span>Sebutkan alasan atau tindakan yang sudah dilakukan</span>
                    <textarea class="form-control" name="edit_keterangan" rows="5"></textarea>
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-secondary" >Lanjutkan</button>
              </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
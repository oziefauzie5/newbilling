@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="d-flex align-items-center">
            <h4 class="card-title">INPUT DATA BARU</h4>
          </div>
        </div>
        <div class="card-body">
          <a href="{{route('admin.psb.ftth')}}"><button class="btn  btn-sm ml-auto m-1 btn-primary ">
            <i class="fas fa-angle-double-left "></i>
            Kembali
          </button></a>
          <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#addRowModal">
            <i class="fa fa-plus"></i>
            Input Data
          </button>

         
          </div>
          <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header no-bd">
                  <h5 class="modal-title">
                    <span class="fw-mediumbold">
                    Input Data Baru</span> 
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{route('admin.psb.store')}}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Id Pelanggan</label>
                          <input id="id" type="text" class="form-control" name="id"value="{{ $idpela }}" readonly required>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Nama Lengkap</label>
                          <input id="input_nama" type="text" class="form-control" name="input_nama"placeholder="Nama Lengkap" value="{{ Session::get('input_nama') }}" required>
                          <input id="id" type="hidden" class="form-control" name="id"value="{{ rand(10000,99999) }}" required>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>No Identitas</label>
                          <input id="input_ktp" type="text" class="form-control" value="{{ Session::get('input_ktp') }}" name="input_ktp" onkeyup="validasiKtp()" placeholder="No. Identitas" required>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>No Hp 1</label>
                          <input id="input_hp" type="text" class="form-control" value="{{ Session::get('input_hp') }}" name="input_hp" placeholder="No. Whatsapp 1" required>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>No Hp 2</label>
                          <input id="input_hp_2" type="text" class="form-control" value="{{ Session::get('input_hp_2') }}" name="input_hp_2" placeholder="No. Whatsapp 2" required>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Email</label>
                          <input id="input_email" type="text" class="form-control" value="{{ Session::get('input_email') }}" name="input_email" placeholder="Email">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Alamat Domisili</label>
                          <input id="input_alamat_ktp" type="text" class="form-control" value="{{ Session::get('input_alamat_ktp') }}" name="input_alamat_ktp" placeholder="Alamat KTP">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Alamat Pasang</label>
                          <input id="input_alamat_pasang" type="text" class="form-control" value="{{ Session::get('input_alamat_pasang') }}" name="input_alamat_pasang" placeholder="Alamat Pemasangan">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Sales </label>
                          <select name="input_sales" class="form-control">
                            <option value="">PILIH</option>
                            @foreach($data_user as $du)
                            <option value="{{$du->id}}">{{$du->name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Sub Sales </label>
                          <input id="input_subseles" type="text" class="form-control" value="{{ Session::get('input_subseles') }}" name="input_subseles" placeholder="Sub Sales">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Share Location</label>
                          <input id="input_maps" type="text" class="form-control" value="{{ Session::get('input_maps') }}" name="input_maps" placeholder="Share Location" required>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Keterangan</label>
                          <textarea name="input_keterangan" class="form-control" id="input_keterangan" cols="10">
Paket :
Keterangan :
</textarea>
<span class="text-bold text-danger" style="font-size:12px">Contoh = Keterangan : Pemasangan Perlu tiang</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer no-bd">
                    <button type="submit" class="btn btn-success">Add</button>
                  </form>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
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
                  <th>ID</th>
                  <th>Tanggal Regist</th>
                  <th>Nama</th>
                  <th>Whatsapp</th>
                  <th>Whatsapp Alternatif</th>
                  <th>Alamat Pasang</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($input_data as $d)
                <tr id="{{$d->id}}">
                      <td id="{{$d->id}}">{{$d->id}}</td>
                      <td id="{{$d->id}}">{{ date('d-m-Y', strtotime($d->input_tgl))}}</td>
                      <td id="{{$d->id}}">{{$d->input_nama}}</td>
                      <td id="{{$d->id}}">{{$d->input_hp}}</td>
                      <td id="{{$d->id}}">{{$d->input_hp_2}}</td>
                      <td id="{{$d->id}}">{{$d->input_alamat_pasang}}</td>
                      <td id="{{$d->id}}">{{$d->input_status}}</td>
                    </tr>
                    @endforeach
              </tbody>
            </table>
          
            <!-- Modal Edit -->
            <div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header no-bd">
                    <h5 class="modal-title">
                      <span class="fw-mediumbold">
                      Edit Data</span> 
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="{{route('admin.psb.input_data_update')}}" method="POST">
                      @csrf
                      @method('POST')
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>ID</label>
                            <input id="edit_id" type="text" class="form-control" name="edit_id" required>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input id="edit_input_nama" type="text" class="form-control" name="input_nama" required>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>No Identitas</label>
                            <input id="edit_input_ktp" type="text" class="form-control" value="" name="input_ktp" readonly required>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>No Hp 1</label>
                            <input id="edit_input_hp" type="text" class="form-control" value="" name="input_hp" required>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>No Hp 2</label>
                            <input id="edit_input_hp2" type="text" class="form-control" value="" name="nomorhp2" required>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Email</label>
                            <input id="edit_input_email" type="text" class="form-control" value="" name="input_email">
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Alamat Domisili</label>
                            <input id="edit_input_alamat_ktp" type="text" class="form-control" value="" name="input_alamat_ktp">
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Alamat Pasang</label>
                            <input id="edit_input_alamat_pasang" type="text" class="form-control" value="" name="input_alamat_pasang" >
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Sub Sales </label>
                            <input id="edit_input_subseles" type="text" class="form-control" value="" name="input_subseles" >
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Share Location</label>
                            <input id="edit_input_maps" type="text" class="form-control" value="" name="input_maps" required>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="input_keterangan" class="form-control" id="edit_input_keterangan" cols="10"></textarea>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Status</label>
                            <select name="input_status" id="edit_input_status" class="form-control">
                              <option value="INPUT DATA">INPUT DATA</option>
                              <option value="REGIST">REGIST</option>
                              <option value="MIGRASI">MIGRASI</option>
                              <option value="PUTUS BERLANGGAN">PUTUS BERLANGGAN</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer no-bd">
                      <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Modal Edit -->
           
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection




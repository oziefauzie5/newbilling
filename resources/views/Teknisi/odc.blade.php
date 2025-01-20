@extends('layout.main')
@section('content')
<style>
  .notice{
    font-size:11px;
    color:red;
    font-weight: bold;
  }
</style>
<div class="content">
  <div class="page-inner">
    <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header bg-primary">
                <button class="btn btn-sm" data-toggle="modal" data-target="#modal-adduser" class="btn btn-primary btn-sm"><i class="fas fa-solid fa-plus"></i>Tambah ODC</button>
              </div>
              
              <!-- ----------------------------------------------------------------------MODAL ADD AKUN------------------------------------------------ -->
              
              <div class="modal fade" id="modal-adduser">
                  <div class="modal-dialog modal-lg">
                    <form action="{{route('admin.topo.odc_store')}}" method="POST" enctype="multipart/form-data">
                      @csrf
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h4 class="modal-title">TAMBAH ODC </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 form-group">
                                      <label>Site</label>
                                      <select class="form-control" id="site" name=""  >
                                        <option value="">PILIH SITE</option>
                                        @foreach ($data_site as $d)
                                        <option value="{{$d->site_id}}">{{$d->site_nama}}</option>
                                        @endforeach
                                      </select>
                                  </div>
                                  <div class="col-6 form-group">
                                      <label>POP</label>
                                        <select class="form-control" id="pop" name=""  >
                                          <option value="">- Pilih POP -</option>
                                        </select>
                                  </div>
                                  <div class="col-6 form-group">
                                      <label>OLT</label>
                                        <select class="form-control" id="olt" name="odc_id_olt"  >
                                          <option value="">- Pilih OLT -</option>
                                        </select>
                                  </div>
                              <div class="col-6 form-group">
                                  <label>Nama Odc</label>
                                  <input type="text" class="form-control" required name="odc_nama">
                              </div>
                              <div class="col-6 form-group">
                                  <label>PON OLT</label>
                                  <input type="number" class="form-control" required max="8" name="odc_pon_olt">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Core Kabel </label>
                                  <select name="odc_core" id="" class="form-control">
                                    <option value="">- Pilih Warna Kabel -</option>
                                    <option value="1">Biru</option>
                                    <option value="2">Orange</option>
                                    <option value="3">Hijau</option>
                                    <option value="4">Coklat</option>
                                    <option value="5">Abu-abu</option>
                                    <option value="6">Putih</option>
                                    <option value="7">Merah</option>
                                    <option value="8">Hitam</option>
                                    <option value="9">Kuning</option>
                                    <option value="10">Ungu</option>
                                    <option value="11">Pink</option>
                                    <option value="12">Toska</option>
                                  </select>
                              </div>
                              <div class="col-6 form-group">
                                  <label>Jumlah Port </label>
                                  <input type="number" class="form-control" required  name="odc_jumlah_port">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Keterangan</label>
                                  <input type="text" class="form-control"  required name="odc_keterangan">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Koodinat</label>
                                  <input type="text" class="form-control"  required name="odc_koordinat">
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto Lokasi</label>
                                <input  type="file" class="form-control-file" required name="odc_lokasi_img">
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto Topologi</label>
                                <input  type="file" class="form-control-file" required name="odc_topologi_img">
                              </div>
                            </div>
                            </div>
                      </div>
                      <div class="modal-footer justify-content-between">
                              <button type="button" class="btn" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                          </div>
                        </form>
                  </div>
                </div>
              <!-- -------------------------------------------------------------------END MODAL ADD AKUN------------------------------------------------ -->
            <div class="card-body table-responsive -sm">
              @if ($errors->any())
                      <div class="alert alert-danger" role="alert">
                        <ul>
                          @foreach ($errors->all() as $item)
                              <li>{{ $item }}</li>
                          @endforeach
                        </ul>
                        </div>
              @endif
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Site</th>
                      <th>Pop</th>
                      <th>Olt</th>
                      <th>Kode Odc</th>
                      <th>Odc Pon</th>
                      <th>Core</th>
                      <th>Nama</th>
                      <th>Jumlah Port</th>
                      <th>Koordinat</th>
                      <th>Lokasi File</th>
                      <th>Topologi File</th>
                      <th>Keterangan</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($data_odc as $d)
                      <tr>
                                <td>{{$d->site_nama}}</td>
                                <td>{{$d->pop_nama}}</td>
                                <td>{{$d->olt_nama}}</td>
                                <td>{{$d->odc_kode}}</td>
                                <td>{{$d->odc_pon_olt}}</td>
                                @if ($d->odc_core==1)
                                <td>Biru</td>
                                @elseif($d->odc_core == 2)
                                <td>Orange</td>
                                @elseif($d->odc_core == 3)
                                <td>Hijau</td>
                                @elseif($d->odc_core == 4)
                                <td>Coklat</td>
                                @elseif($d->odc_core == 5)
                                <td>Abu-abu</td>
                                @elseif($d->odc_core == 6)
                                <td>Putih</td>
                                @elseif($d->odc_core == 7)
                                <td>Merah</td>
                                @elseif($d->odc_core == 8)
                                <td>Hitam</td>
                                @elseif($d->odc_core == 9)
                                <td>Kuning</td>
                                @elseif($d->odc_core == 10)
                                <td>Ungu</td>
                                @elseif($d->odc_core == 11)
                                <td>Pink</td>
                                @elseif($d->odc_core == 12)
                                <td>Toska</td>
                                @endif
                                <td>{{$d->odc_nama}}</td>
                                <td>{{$d->odc_jumlah_port}}</td>
                                <td>{{$d->odc_koordinat}}</td>
                                <td>{{$d->odc_lokasi_img}}</td>
                                <td>{{$d->odc_topologi_img}}</td>
                                <td>{{$d->odc_keterangan}}</td>
                                <td>{{$d->odc_status}}</td>
                        <td>
                          <div class="form-button-action">
                              <button type="button" data-toggle="modal" data-target="#modal-edit{{$d->odc_id}}" class="btn btn-link btn-primary btn-lg">
                                <i class="fa fa-edit"></i>
                              </button>
                            </div>
                          </td>
                      <div class="modal fade" id="modal-edit{{$d->odc_id}}">
                          <div class="modal-dialog modal-lg">
                            <form action="{{route('admin.topo.update_odc',['id'=>$d->odc_id])}}" method="POST" enctype="multipart/form-data">
                            @csrf  
                            @method('PUT')
                            <div class="modal-content">
                              <div class="modal-header bg-primary ">
                                <h4 class="modal-title">EDIT OLT </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <div class="card-body">
                                  <div class="row">
                                      <div class="col-6 form-group">
                                          <label>Id ODC</label>
                                          <input type="text" class="form-control"  required name="odc_kode" value="{{$d->odc_kode}}" readonly>
                                      </div>
                                      <div class="col-6 form-group">
                                          <label>OLT</label>
                                          <select required name="odc_id_olt" id=""  class="form-control">
                                            <option value="{{$d->olt_id}}">{{$d->olt_nama}}</option>
                                            @foreach ($data_olt as $da)
                                            <option value="{{$da->olt_id}}">{{$da->olt_nama}}</option>
                                            @endforeach
                                          </select>
                                      </div>
                                <div class="col-6 form-group">
                                    <label>Nama Odc</label>
                                    <input type="text" class="form-control" required value="{{$d->odc_nama}}" name="odc_nama">
                                </div>
                                <div class="col-6 form-group">
                                    <label>PON OLT</label>
                                    <input type="number" class="form-control" required value="{{$d->odc_pon_olt}}"  name="odc_pon_olt">
                                </div>
                                <div class="col-6 form-group">
                                    <label>Core Kabel </label>
                                    <select name="odc_core" id="" class="form-control" required >
                                      @if($d->odc_core == 1 )
                                      <option value="1">Biru</option>
                                      @elseif($d->odc_core == 2)
                                      <option value="2">Orange</option>
                                      @elseif($d->odc_core == 3)
                                      <option value="3">Hijau</option>
                                      @elseif($d->odc_core == 4)
                                      <option value="4">Coklat</option>
                                      @elseif($d->odc_core == 5)
                                      <option value="5">Abu-abu</option>
                                      @elseif($d->odc_core == 6)
                                      <option value="6">Putih</option>
                                      @elseif($d->odc_core == 7)
                                      <option value="7">Merah</option>
                                      @elseif($d->odc_core == 8)
                                      <option value="8">Hitam</option>
                                      @elseif($d->odc_core == 9)
                                      <option value="9">Kuning</option>
                                      @elseif($d->odc_core == 10)
                                      <option value="10">Ungu</option>
                                      @elseif($d->odc_core == 11)
                                      <option value="11">Pink</option>
                                      @elseif($d->odc_core == 12)
                                      <option value="12">Toska</option>
                                      @endif
                                      <option value="">- Pilih Warna Kabel -</option>
                                      <option value="1">Biru</option>
                                      <option value="2">Orange</option>
                                      <option value="3">Hijau</option>
                                      <option value="4">Coklat</option>
                                      <option value="5">Abu-abu</option>
                                      <option value="6">Putih</option>
                                      <option value="7">Merah</option>
                                      <option value="8">Hitam</option>
                                      <option value="9">Kuning</option>
                                      <option value="10">Ungu</option>
                                      <option value="11">Pink</option>
                                      <option value="12">Toska</option>
                                    </select>
                                </div>
                                <div class="col-6 form-group">
                                    <label>Jumlah Port </label>
                                    <input type="number" class="form-control" required value="{{$d->odc_jumlah_port}}"  name="odc_jumlah_port">
                                </div>
                                <div class="col-6 form-group">
                                    <label>Keterangan</label>
                                    <input type="text" class="form-control"  required value="{{$d->odc_keterangan}}" name="odc_keterangan">
                                </div>
                                <div class="col-6 form-group">
                                    <label>Koodinat</label>
                                    <input type="text" class="form-control"  required value="{{$d->odc_koordinat}}" name="odc_koordinat">
                                </div>
                                <div class="col-6 form-group">
                                  <label>Upload Foto Lokasi</label>
                                  <input  type="file" class="form-control-file"   name="odc_lokasi_img">
                                </div>
                                <div class="col-6 form-group">
                                  <label>Upload Foto Topologi</label>
                                  <input  type="file" class="form-control-file"   name="odc_topologi_img">
                                </div>
                                <div class="col-12 form-group">
                                  <label>Status</label>
                                  <select name="odc_status" class="form-control">
                                    @if($d->odc_status) 
                                    <option value="{{$d->odc_status}}">{{$d->odc_status}}</option>
                                    @endif
                                  <option value="">Pilih Status</option>
                                    <option value="Enable">Enable</option>
                                    <option value="Disable">Disable</option>
                                  </select>
                              </div>
                                <div class="col-6 form-group">
                                  <label>Lokasi</label>
                                  <img src="{{ asset('storage/topologi/'.$d->odc_lokasi_img) }}" width="100%" alt="" title=""></img>
                                </div>
                                <div class="col-6 form-group">
                                  <label>Topologi</label>
                                  <img src="{{ asset('storage/topologi/'.$d->odc_topologi_img) }}" width="100%" alt="" title=""></img>
                                </div>
                              </div>
                              </div>
                        </div>
                              <div class="modal-footer justify-content-between">
                                      <button type="button" class="btn" data-dismiss="modal">Close</button>
                                      <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                  </div>
                                </form>
                          </div>
                        </div>
                      </tr>
                  @endforeach
                </table>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>

@endsection
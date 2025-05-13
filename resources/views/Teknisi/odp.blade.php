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
                <button class="btn btn-sm" data-toggle="modal" data-target="#modal-adduser" class="btn btn-primary btn-sm"><i class="fas fa-solid fa-plus"></i>Tambah ODP</button>
              </div>
              
              <!-- ----------------------------------------------------------------------MODAL ADD AKUN------------------------------------------------ -->
              
              <div class="modal fade" id="modal-adduser">
                  <div class="modal-dialog modal-lg">
                    <form action="{{route('admin.topo.odp_store')}}" method="POST" enctype="multipart/form-data">
                      @csrf
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h4 class="modal-title">TAMBAH ODP </h4>
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
                                        <select class="form-control" id="olt" name=""  >
                                          <option value="">- Pilih OLT -</option>
                                        </select>
                                  </div>
                                    <div class="col-6 form-group">
                                        <label>ODC</label>
                                        <select required name="odp_id_odc" id="odc"  class="form-control">
                                          <option value="">- Pilih ODC -</option>
                                        </select>
                                    </div>
                              <div class="col-6 form-group">
                                  <label>Nama Odp</label>
                                  <input type="text" class="form-control" required name="odp_nama">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Port Odc</label>
                                  <input type="text" class="form-control" required name="odp_port_odc">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Opm Out Odc</label>
                                  <input type="text" class="form-control" required name="odp_opm_out_odc">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Opm In Odp</label>
                                  <input type="text" class="form-control" required name="odp_opm_in">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Opm Out Odp</label>
                                  <input type="text" class="form-control" required name="odp_opm_out">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Core Kabel </label>
                                  <select name="odp_core" id="" class="form-control">
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
                                  <input type="number" class="form-control" required  name="odp_jumlah_port">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Keterangan</label>
                                  <input type="text" class="form-control"  required name="odp_keterangan">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Koodinat</label>
                                  <input type="text" class="form-control"  required name="odp_koordinat">
                              </div>
                              <div class="col-12 form-group">
                                <label>Status</label>
                                <select name="odp_status" class="form-control">
                                  <option value="">Pilih Status</option>
                                  <option value="Enable">Enable</option>
                                  <option value="Disable">Disable</option>
                                </select>
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto Lokasi</label>
                                <input  type="file" class="form-control-file" required name="odp_lokasi_img">
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto Topologi</label>
                                <input  type="file" class="form-control-file" required name="odp_topologi_img">
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
                <table id="edit_inputdata" class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Action</th>
                      <th>Site</th>
                      <th>Pop</th>
                      <th>Olt</th>
                      <th>Odc</th>
                      <th>Kode Odp</th>
                      <th>Odc Port</th>
                      <th>Odc Opm Out</th>
                      <th>Odp Opm In</th>
                      <th>Odp Opm Out</th>
                      <th>Core</th>
                      <th>Nama</th>
                      <th>Jumlah Port</th>
                      <th>Koordinat</th>
                      <th>Lokasi File</th>
                      <th>Topologi File</th>
                      <th>Keterangan</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($data_odp as $d)
                    <tr>
                      
                      <td>
                        <div class="form-button-action">
                            <button type="button" data-toggle="modal" data-target="#modal-edit{{$d->odp_id}}" class="btn btn-link btn-primary btn-lg">
                              <i class="fa fa-edit"></i>
                            </button>
                          </div>
                        </td>

                        <td>{{$d->site_nama}}</td>
                        <td>{{$d->pop_nama}}</td>
                        <td>{{$d->olt_nama}}</td>
                        <td>{{$d->odc_nama}}</td>
                        <td>{{$d->odp_kode}}</td>
                        <td>{{$d->odp_port_odc}}</td>
                        <td>{{$d->odp_opm_out_odc}}</td>
                        <td>{{$d->odp_opm_in}}</td>
                        <td>{{$d->odp_opm_out}}</td>
                        @if ($d->odp_core==1)
                        <td>Biru</td>
                        @elseif($d->odp_core == 2)
                        <td>Orange</td>
                        @elseif($d->odp_core == 3)
                        <td>Hijau</td>
                        @elseif($d->odp_core == 4)
                        <td>Coklat</td>
                        @elseif($d->odp_core == 5)
                        <td>Abu-abu</td>
                        @elseif($d->odp_core == 6)
                        <td>Putih</td>
                        @elseif($d->odp_core == 7)
                        <td>Merah</td>
                        @elseif($d->odp_core == 8)
                        <td>Hitam</td>
                        @elseif($d->odp_core == 9)
                        <td>Kuning</td>
                        @elseif($d->odp_core == 10)
                        <td>Ungu</td>
                        @elseif($d->odp_core == 11)
                        <td>Pink</td>
                        @elseif($d->odp_core == 12)
                        <td>Toska</td>
                        @endif
                        <td>{{$d->odp_nama}}</td>
                        <td>{{$d->odp_jumlah_port}}</td>
                        <td>{{$d->odp_koordinat}}</td>
                        <td>{{$d->odp_lokasi_img}}</td>
                        <td>{{$d->odp_topologi_img}}</td>
                        <td>{{$d->odp_keterangan}}</td>
                        <td>{{$d->odp_status}}</td>
                      <div class="modal fade" id="modal-edit{{$d->odp_id}}">
                          <div class="modal-dialog modal-lg">
                            <form action="{{route('admin.topo.update_odc',['id'=>$d->odc_id])}}" method="POST" enctype="multipart/form-data">
                              @csrf  
                            @method('PUT')
                            <div class="modal-content">
                              <div class="modal-header bg-primary ">
                                <h4 class="modal-title">EDIT ODP </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <div class="card-body">
                                  <div class="row">
                                  <div class="col-6 form-group">
                                      <label>OLT</label>
                                        <select class="form-control" id="aktivasi_olt" name=""  >
                                          @if($d->olt_id)
                                          <option value="{{$d->olt_id}}">{{$d->olt_kode}}</option>
                                          @endif
                                          @foreach ($data_olt as $dol)
                                          <option value="{{$dol->olt_id}}">{{$dol->olt_kode}}</option>
                                          @endforeach
                                        </select>
                                  </div>
                                    <div class="col-6 form-group">
                                        <label>ODC</label>
                                        <select required name="odp_id_odc" id="aktivasi_odc"  class="form-control">
                                          @if($d->odp_id_odc)
                                          <option value="{{$d->odp_id_odc}}">{{$d->odc_nama}}</option>
                                          @endif
                                          <option value="">- Pilih ODC -</option>
                                        </select>
                                    </div>
                              <div class="col-6 form-group">
                                  <label>Nama Odp</label>
                                  <input type="text" class="form-control" required value="{{$d->odp_nama}}" name="odp_nama">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Port Odc</label>
                                  <input type="text" class="form-control" required value="{{$d->odp_port_odc}}"  name="odp_port_odc">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Opm Out Odc</label>
                                  <input type="text" class="form-control" required value="{{$d->odp_opm_out_odc}}"  name="odp_opm_out_odc">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Opm In Odp</label>
                                  <input type="text" class="form-control" required value="{{$d->odp_opm_in}}"  name="odp_opm_in">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Opm Out Odp</label>
                                  <input type="text" class="form-control" required value="{{$d->odp_opm_out}}"  name="odp_opm_out">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Core Kabel </label>
                                  <select  name="odp_core" id="" class="form-control">
                                    @if($d->odp_core)
                                    <option value="{{$d->odp_core}}">{{$d->odp_core}}</option>
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
                                  <input type="number" class="form-control" required  value="{{$d->odp_jumlah_port}}"  name="odp_jumlah_port">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Keterangan</label>
                                  <input type="text" class="form-control"  required value="{{$d->odp_keterangan}}"  name="odp_keterangan">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Koodinat</label>
                                  <input type="text" class="form-control"  required value="{{$d->odp_koordinat}}"  name="odp_koordinat">
                              </div>
                              <div class="col-12 form-group">
                                <label>Status</label>
                                <select value="{{$d->odp_status}}"  name="odp_status" class="form-control">
                                  <option value="">Pilih Status</option>
                                  <option value="Enable">Enable</option>
                                  <option value="Disable">Disable</option>
                                </select>
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto Lokasi</label>
                                <input  type="file" class="form-control-file" required name="odp_lokasi_img">
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto Topologi</label>
                                <input  type="file" class="form-control-file" required name="odp_topologi_img">
                              </div>
                               <div class="col-6 form-group">
                                  <label>Lokasi</label>
                                  <img src="{{ asset('storage/topologi/'.$d->odp_lokasi_img) }}" width="100%" alt="" title=""></img>
                                </div>
                                <div class="col-6 form-group">
                                  <label>Topologi</label>
                                  <img src="{{ asset('storage/topologi/'.$d->odp_topologi_img) }}" width="100%" alt="" title=""></img>
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
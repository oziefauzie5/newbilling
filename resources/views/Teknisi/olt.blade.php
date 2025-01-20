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
                <button class="btn btn-sm" data-toggle="modal" data-target="#modal-adduser" class="btn btn-primary btn-sm"><i class="fas fa-solid fa-plus"></i>Tambah OLT</button>
              </div>
              
              <!-- ----------------------------------------------------------------------MODAL ADD AKUN------------------------------------------------ -->
              
              <div class="modal fade" id="modal-adduser">
                  <div class="modal-dialog modal-lg">
                    <form action="{{route('admin.topo.olt_store')}}" method="POST" enctype="multipart/form-data">
                      @csrf
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h4 class="modal-title">TAMBAH OLT </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 form-group">
                                        <label>Site</label>
                                        <select class="form-control" id="site" name="olt_id_site"  >
                                          <option value="">PILIH SITE</option>
                                          @foreach ($data_site as $d)
                                          <option value="{{$d->site_id}}">{{$d->site_nama}}</option>
                                          @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label>POP</label>
                                          <select class="form-control" id="pop" name="olt_id_pop"  >
                                            <option value="">- Pilih POP -</option>
                                          </select>
                                    </div>
                              <div class="col-6 form-group">
                                  <label>Nama Olt</label>
                                  <input type="text" class="form-control" required name="olt_nama">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Merek & Type Olt</label>
                                  <input type="text" class="form-control" required name="olt_merek" id="">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Mac Olt </label>
                                  <input type="text" class="form-control" required name="olt_mac" id="">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Sn Olt </label>
                                  <input type="text" class="form-control" required name="olt_sn" id="">
                              </div>
                              <div class="col-6 form-group">
                                <label>Pon </label>
                                <input type="number" class="form-control" required name="olt_pon">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Ip Default </label>
                                  <input type="text" class="form-control" required name="olt_ip_default">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Username Default </label>
                                  <input type="text" class="form-control" required name="olt_username_default">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Password Default </label>
                                  <input type="text" class="form-control" required name="olt_password_default">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Ip Olt </label>
                                  <input type="text" class="form-control" required name="olt_ip">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Username Login </label>
                                  <input type="text" class="form-control" required name="olt_username">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Password Login </label>
                                  <input type="text" class="form-control" required name="olt_password">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Keterangan</label>
                                  <input type="text" class="form-control"  required name="olt_keterangan">
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto Topologi</label>
                                <input  type="file" class="form-control-file" required name="olt_topologi_img">
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
                      <th>Kode Olt</th>
                      <th>Site</th>
                      <th>Pop</th>
                      <th>Nama</th>
                      <th>Merek Olt</th>
                      <th>Mac Olt</th>
                      <th>Sn Olt</th>
                      <th>Pon Olt</th>
                      <th>IP Default Olt</th>
                      <th>Username Default Olt</th>
                      <th>Password Default Olt</th>
                      <th>Topologi File</th>
                      <th>Keterangan</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($data_olt as $d)
                      <tr>
                        <td>{{$d->olt_kode}}</td>
                        <td>{{$d->site_nama}}</td>
                        <td>{{$d->pop_nama}}</td>
                        <td>{{$d->olt_nama}}</td>
                        <td>{{$d->olt_merek}}</td>
                        <td>{{$d->olt_mac}}</td>
                        <td>{{$d->olt_sn}}</td>
                        <td>{{$d->olt_pon}}</td>
                        <td>{{$d->olt_ip_default}}</td>
                        <td>{{$d->olt_username_default}}</td>
                        <td>{{$d->olt_password_default}}</td>
                        <td>{{$d->olt_topologi_img}}</td>
                        <td>{{$d->olt_keterangan}}</td>
                        <td>{{$d->olt_status}}</td>
                        <td>
                          <div class="form-button-action">
                              <button type="button" data-toggle="modal" data-target="#modal-edit{{$d->olt_id}}" class="btn btn-link btn-primary btn-lg">
                                <i class="fa fa-edit"></i>
                              </button>
                            </div>
                          </td>
                      <div class="modal fade" id="modal-edit{{$d->olt_id}}">
                          <div class="modal-dialog modal-lg">
                            <form action="{{route('admin.topo.update_olt',['id'=>$d->olt_id])}}" method="POST" enctype="multipart/form-data">
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
                                          <label>Kode Olt</label>
                                          <input type="text" class="form-control"  required name="olt_kode" value="{{$d->olt_kode}}" readonly>
                                      </div>
                                      <div class="col-6 form-group">
                                          <label>POP</label>
                                          <select required name="olt_id_pop" id=""  class="form-control" disabled>
                                            <option value="{{$d->olt_id_pop}}">{{$d->pop_nama}}</option>
                                          </select>
                                      </div>
                                <div class="col-6 form-group">
                                    <label>Nama Olt</label>
                                    <input type="text" class="form-control" required name="olt_nama" value="{{$d->olt_nama}}">
                                </div>
                                <div class="col-6 form-group">
                                    <label>Merek & Type Olt</label>
                                    <input type="text" class="form-control" required name="olt_merek" value="{{$d->olt_merek}}">
                                </div>
                                <div class="col-6 form-group">
                                    <label>Mac Olt </label>
                                    <input type="text" class="form-control" required name="olt_mac" value="{{$d->olt_mac}}">
                                </div>
                                <div class="col-6 form-group">
                                    <label>Sn Olt </label>
                                    <input type="text" class="form-control" required name="olt_sn" value="{{$d->olt_sn}}">
                                </div>
                                <div class="col-6 form-group">
                                  <label>Pon </label>
                                  <input type="number" class="form-control" required name="olt_pon" value="{{$d->olt_pon}}">
                                </div>
                                <div class="col-6 form-group">
                                    <label>Ip Default </label>
                                    <input type="text" class="form-control" required name="olt_ip_default" value="{{$d->olt_ip_default}}">
                                </div>
                                <div class="col-6 form-group">
                                    <label>Username Default </label>
                                    <input type="text" class="form-control" required name="olt_username_default" value="{{$d->olt_username_default}}">
                                </div>
                                <div class="col-6 form-group">
                                    <label>Password Default </label>
                                    <input type="text" class="form-control" required name="olt_password_default" value="{{$d->olt_password_default}}">
                                </div>
                                <div class="col-6 form-group">
                                    <label>Ip Olt </label>
                                    <input type="text" class="form-control" required name="olt_ip" value="{{$d->olt_ip}}">
                                </div>
                                <div class="col-6 form-group">
                                    <label>Username Login </label>
                                    <input type="text" class="form-control" required name="olt_username" value="{{$d->olt_username}}">
                                </div>
                                <div class="col-6 form-group">
                                    <label>Password Login </label>
                                    <input type="text" class="form-control" required name="olt_password" value="{{$d->olt_password}}">
                                </div>
                                <div class="col-6 form-group">
                                    <label>Keterangan</label>
                                    <input type="text" class="form-control"  required name="olt_keterangan" value="{{$d->olt_keterangan}}">
                                </div>
                                <div class="col-6 form-group">
                                  <label>Upload Foto Topologi</label>
                                  <input  type="file" class="form-control-file" name="olt_topologi_img" >
                                </div>
                                <div class="col-6 form-group">
                                  <label>Status</label>
                                  <select name="olt_status" class="form-control" id="">
                                    <option value="{{$d->olt_status}}">{{$d->olt_status}}</option>
                                    <option value="Enable">Enable</option>
                                    <option value="Disable">Disable</option>
                                  </select>
                                </div>
                                <div class="col-6 form-group">
                                  <label>Topologi</label>
                                  <img src="{{ asset('storage/topologi/'.$d->olt_topologi_img) }}" width="100%" alt="" title=""></img>
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
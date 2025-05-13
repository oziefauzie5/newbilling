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
                <button class="btn btn-sm" data-toggle="modal" data-target="#modal-adduser" class="btn btn-primary btn-sm"><i class="fas fa-solid fa-plus"></i>Tambah POP</button>
              </div>
              <!-- ----------------------------------------------------------------------MODAL ADD AKUN------------------------------------------------ -->
              
              <div class="modal fade" id="modal-adduser">
                  <div class="modal-dialog modal-lg">
                    <form action="{{route('admin.topo.pop_store')}}" method="POST" enctype="multipart/form-data">
                      @csrf
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h4 class="modal-title">TAMBAH POP </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-12 form-group">
                              <label>Site</label>
                                        <select name="pop_id_site" id="site"  class="form-control">
                                          <option value="">- Pilih Site -</option>
                                          @foreach ($data_site as $ds)
                                          <option value="{{$ds->site_id}}">{{$ds->site_nama}}</option>
                                          @endforeach
                                        </select>
                                      </div>
                              <div class="col-12 form-group">
                                  <label>Nama Pop</label>
                                  <input type="text" class="form-control" name="pop_nama" placeholder="Masukan Nama Pop">
                              </div>
                              <div class="col-12 form-group">
                                  <label>Alamat Lengkap Site</label>
                                  <textarea name="pop_alamat" class="form-control" cols="30" rows="3"></textarea>
                              </div>
                              <div class="col-12 form-group">
                                  <label>Koordinat</label>
                                  <input type="text" class="form-control"  name="pop_koordinat" placeholder="Masukan Koordinat Pop">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Ip Address 1</label>
                                  <input type="text" class="form-control"  name="pop_ip1" placeholder="Masukan IP Public 1">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Ip Address 2</label>
                                  <input type="text" class="form-control"  name="pop_ip2" placeholder="Masukan IP Public 2">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Keterangan</label>
                                  <input type="text" class="form-control"  name="pop_keterangan" placeholder="Masukan Keterangan">
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto</label>
                                <input  type="file" class="form-control-file" name="pop_topologi_img" required>
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
                      <th>Kode Pop</th>
                      <th>Site</th>
                      <th>Nama Pop</th>
                      <th>Alamat Pop</th>
                      <th>Koordinat Pop</th>
                      <th>IP Addres 1</th>
                      <th>IP Addres 2</th>
                      <th>File Topologi</th>
                      <th>Keterangan</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($data_pop as $d)
                      <tr>
                        <td>{{$d->pop_kode}}</td>
                        <td>{{$d->site_nama}}</td>
                        <td>{{$d->pop_nama}}</td>
                        <td>{{$d->pop_alamat}}</td>
                        <td>{{$d->pop_koordinat}}</td>
                        <td>{{$d->pop_ip1}}</td>
                        <td>{{$d->pop_ip2}}</td>
                        <td>{{$d->pop_topologi_img}}</td>
                        <td>{{$d->pop_keterangan}}</td>
                        <td>{{$d->pop_status}}</td>
                        <td>
                          <div class="form-button-action">
                              <button type="button" data-toggle="modal" data-target="#modal-edit{{$d->pop_id}}" class="btn btn-link btn-primary btn-lg">
                                <i class="fa fa-edit"></i>
                              </button>
                            </div>
                          </td>
                      <div class="modal fade" id="modal-edit{{$d->pop_id}}">
                          <div class="modal-dialog modal-lg">
                            <form action="{{route('admin.topo.update_pop',['id'=>$d->pop_id])}}" method="POST" enctype="multipart/form-data">
                            @csrf  
                            @method('PUT')
                            <div class="modal-content">
                              <div class="modal-header bg-primary ">
                                <h4 class="modal-title">EDIT POP </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6 form-group">
                                            <label>Kode Pop</label>
                                            <input type="text" class="form-control" name="pop_kode" value="{{$d->pop_kode}}" readonly>
                                        </div>
                                        <div class="col-6 form-group">
                                            <label>Site</label>
                                            <select name="pop_id_site"  class="form-control" disabled>
                                                @if($d->pop_id_site)
                                                <option value="{{$d->pop_id_site}}">{{$d->site_nama}}</option>
                                                @endif
                                              <option value="">- Pilih Site -</option>
                                              @foreach ($data_site as $ds)
                                              <option value="{{$ds->site_id}}">{{$ds->site_nama}}</option>
                                              @endforeach
                                            </select>
                                        </div>
                                  <div class="col-12 form-group">
                                      <label>Nama Pop</label>
                                      <input type="text" class="form-control" name="pop_nama" value="{{$d->pop_nama}}">
                                  </div>
                                  <div class="col-12 form-group">
                                      <label>Alamat Lengkap Site</label>
                                      <textarea name="pop_alamat" class="form-control" cols="30" rows="3">{{$d->pop_alamat}}</textarea>
                                  </div>
                                  <div class="col-12 form-group">
                                      <label>Koordinat</label>
                                      <input type="text" class="form-control"  name="pop_koordinat" value="{{$d->pop_koordinat}}">
                                  </div>
                                  <div class="col-6 form-group">
                                      <label>Ip Address 1</label>
                                      <input type="text" class="form-control"  name="pop_ip1" value="{{$d->pop_ip1}}">
                                  </div>
                                  <div class="col-6 form-group">
                                      <label>Ip Address 2</label>
                                      <input type="text" class="form-control"  name="pop_ip2" value="{{$d->pop_ip2}}">
                                  </div>
                                  <div class="col-6 form-group">
                                      <label>Keterangan</label>
                                      <input type="text" class="form-control"  name="pop_keterangan" value="{{$d->pop_keterangan}}">
                                  </div>
                                  <div class="col-6 form-group">
                                    <label>Status</label>
                                    <select name="pop_status" class="form-control">
                                      @if($d->site_status) 
                                      <option value="{{$d->site_status}}">{{$d->site_status}}</option>
                                      @endif
                                    <option value="">Pilih Status</option>
                                      <option value="Enable">Enable</option>
                                      <option value="Disable">Disable</option>
                                    </select>
                                </div>
                                <div class="col-6 form-group">
                                    <label>Upload Foto</label>
                                    <input  type="file" class="form-control-file" name="pop_topologi_img">
                                  </div>
                                <div class="col-6 form-group">
                                    <label>Topologi</label>
                                    <img src="{{ asset('storage/topologi/'.$d->pop_topologi_img) }}" width="100%" alt="" title=""></img>
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
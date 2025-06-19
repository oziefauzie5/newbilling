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
              <h3 class="card-title text-light">Data POP</h3>
            </div>
            <div class="card-body table-responsive -sm">
              {{-- <button class="btn btn-sm btn-primary" data- toggle="modal" data-target="#modal-adduser" class="btn btn-primary btn-sm">Tambah POP</button> --}}
              
              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-solid fa-plus"></i> 
                Tambah Pop
              </button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah POP</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body">
                            <div class="row">
                              <div class="col-12 form-group">
                                 <form action="{{route('admin.topo.pop_store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                                <label>Site</label>
                                          <select name="data__site_id" id="site"  class="form-control">
                                            <option value="">- Pilih Site -</option>
                                            @foreach ($data_site as $ds)
                                            <option value="{{$ds->id}}">{{$ds->site_nama}}</option>
                                            @endforeach
                                          </select>
                                        </div>
                                <div class="col-12 form-group">
                                    <label>Nama Pop</label>
                                    <input type="text" class="form-control" value="{{ Session::get('pop_nama') }}" name="pop_nama" placeholder="Masukan Nama Pop">
                                </div>
                                <div class="col-12 form-group">
                                    <label>Alamat Lengkap POP</label>
                                    <textarea name="pop_alamat" class="form-control" value="" cols="30" rows="3">
  {{ Session::get('pop_alamat') }}</textarea>
                                </div>
                                <div class="col-12 form-group">
                                    <label>Koordinat</label>
                                    <input type="text" class="form-control" value="{{ Session::get('pop_koordinat') }}"  name="pop_koordinat" placeholder="Masukan Koordinat Pop">
                                </div>
                                <div class="col-6 form-group">
                                  <label>Upload Foto</label>
                                  <input  type="file" class="form-control-file" name="pop_file_topologi">
                                </div>
                              </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Save changes</button>
                            </form>
      </div>
    </div>
  </div>
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
                      <th>Nama Pop</th>
                      <th>Alamat Pop</th>
                      <th>Koordinat Pop</th>
                      <th>File Topologi</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($data_pop as $d)
                      <tr>
                        <td>{{$d->pop_site->site_nama}}</td>
                        <td>{{$d->pop_nama}}</td>
                        <td>{{$d->pop_alamat}}</td>
                        <td>{{$d->pop_koordinat}}</td>
                        <td>{{$d->pop_file_topologi}}</td>
                        <td>{{$d->pop_status}}</td>
                        <td>
                          <div class="form-button-action">
                              <button type="button" data-toggle="modal" data-target="#modal-edit{{$d->id}}" class="btn btn-link btn-primary btn-lg">
                                <i class="fa fa-edit"></i>
                              </button>
                            </div>
                          </td>
                      <div class="modal fade" id="modal-edit{{$d->id}}">
                          <div class="modal-dialog modal-lg">
                            <form action="{{route('admin.topo.update_pop',['id'=>$d->id])}}" method="POST" enctype="multipart/form-data">
                            @csrf  
                            @method('PUT')
                            <div class="modal-content">
                              <div class="modal-header bg-primary ">
                                <h4 class="modal-title">Edit Pop </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6 form-group">
                                            <label>Site</label>
                                            <select name="data__site_id"  class="form-control" >
                                                @if($d->data__site_id)
                                                <option value="{{$d->data__site_id}}">{{$d->pop_site->site_nama}}</option>
                                                @endif
                                            </select>
                                        </div>
                                  <div class="col-12 form-group">
                                      <label>Nama Pop</label>
                                      <input type="text" class="form-control" name="pop_nama" value="{{$d->pop_nama}}">
                                  </div>
                                  <div class="col-12 form-group">
                                      <label>Alamat Lengkap</label>
                                      <textarea name="pop_alamat" class="form-control" cols="30" rows="3">{{$d->pop_alamat}}</textarea>
                                  </div>
                                  <div class="col-12 form-group">
                                      <label>Koordinat</label>
                                      <input type="text" class="form-control"  name="pop_koordinat" value="{{$d->pop_koordinat}}">
                                  </div>
                                  <div class="col-6 form-group">
                                    <label>Status</label>
                                    <select name="pop_status" class="form-control">
                                      @if($d->site_status) 
                                      <option value="{{$d->site_status}}">{{$d->site_status}}</option>
                                      @endif
                                      <option value="Enable">Enable</option>
                                      <option value="Disable">Disable</option>
                                    </select>
                                </div>
                                <div class="col-6 form-group">
                                    <label>Upload Foto</label>
                                    <input  type="file" class="form-control-file" name="pop_file_topologi">
                                  </div>
                                <div class="col-6 form-group">
                                    <label>Topologi</label>
                                    <img src="{{ asset('storage/topologi/'.$d->pop_file_topologi) }}" width="100%" alt="" title=""></img>
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
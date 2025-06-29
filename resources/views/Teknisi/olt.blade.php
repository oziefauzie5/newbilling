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
              <div class="card-header card-primary">
                <h3 class="card-title text-light">Data OLT</h3>
              </div>
              <div class="card-body table-responsive -sm">
              <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-adduser" class="btn btn-primary btn-sm"><i class="fas fa-solid fa-plus"></i>Tambah OLT</button>
            <a href="{{route('admin.topo.odc')}}"><button class="btn  btn-sm ml-auto m-1 btn-primary"><i class="fas fa-solid fa-route"></i> ODP Distibusi</button></a>
              <a href="{{route('admin.topo.odp')}}"><button class="btn  btn-sm ml-auto m-1 btn-primary"><i class="fas fa-solid fa-route"></i> ODP</button></a>
              <a href="{{route('admin.topo.index')}}"><button class="btn  btn-sm ml-auto m-1 btn-primary"><i class="fas fa-solid fa-route"></i> Router</button></a>
         
              
              <!-- ----------------------------------------------------------------------MODAL ADD AKUN------------------------------------------------ -->
              
              <div class="modal fade" id="modal-adduser">
                  <div class="modal-dialog modal-lg">
                    <form action="{{route('admin.topo.olt_store')}}" method="POST" enctype="multipart/form-data">
                      @csrf
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h4 class="modal-title">Tambah OLT </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 form-group">
                                        <label>Pop</label>
                                          <select class="form-control" id="" name="data_pop_id"  >
                                            <option value="">- Pilih Pop -</option>
                                            @foreach ($data_pop as $r)
                                                <option value="{{$r->pop_id}}">{{$r->site_nama}} - {{$r->pop_nama}}</option>
                                            @endforeach
                                          </select>
                                    </div>
                              <div class="col-6 form-group">
                                  <label>Nama Olt</label>
                                  <input type="text" class="form-control" required name="olt_nama">
                              </div>
                              <div class="col-6 form-group">
                                <label>Jumlah Pon </label>
                                <input type="number" class="form-control" required name="olt_pon">
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto Topologi</label>
                                <input  type="file" class="form-control-file" required name="olt_file_topologi">
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
                      <th>Aksi</th>
                      <th>Olt Nama</th>
                      <th>Jumlah PON</th>
                      <th>PON Digunakan</th>
                      <th>Topologi File</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($data_olt as $d)
                      <tr>
                        <td>{{$d->site_nama ?? '-'}}</td>
                        <td>{{$d->pop_nama ?? '-'}}</td>
                        <td>
                          <div class="form-button-action">
                              <button type="button" data-toggle="modal" data-target="#modal-edit{{$d->olt_id}}" class="btn btn-link btn-primary btn-lg">
                                <i class="fa fa-edit"></i>
                              </button>
                            </div>
                          </td>
                        <td>{{$d->olt_nama ?? '-'}}</td>
                        <td>{{$d->olt_pon ?? '-'}}</td>
                        <td>{{$d->olt_odc->count() ?? '-'}}</td>
                        <td>{{$d->olt_file_topologi ?? '-'}}</td>
                        <td>{{$d->olt_status ?? '-'}}</td>
                          
                      <div class="modal fade" id="modal-edit{{$d->olt_id}}">
                          <div class="modal-dialog modal-lg">
                            <form action="{{route('admin.topo.update_olt',['id'=>$d->olt_id])}}" method="POST" enctype="multipart/form-data">
                            @csrf  
                            @method('PUT')
                            <div class="modal-content">
                              <div class="modal-header bg-primary ">
                                <h4 class="modal-title">Edit OLT </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <div class="card-body">
                                  <div class="row">
                                       <div class="col-6 form-group">
                                        <label>Pop</label>
                                          <select class="form-control" id="" name="data_pop_id"  >
                                            @if($d->data_pop_id)
                                            <option value="{{$d->data_pop_id}}">{{$d->pop_nama}}</option>
                                            @endif
                                             @foreach ($data_pop as $r)
                                                <option value="{{$r->pop_id}}">{{$r->site_nama}} - {{$r->pop_nama}}</option>
                                            @endforeach
                                          </select>
                                    </div>
                                <div class="col-6 form-group">
                                    <label>Nama Olt</label>
                                    <input type="text" class="form-control" required name="olt_nama" value="{{$d->olt_nama}}">
                                </div>
                                <div class="col-6 form-group">
                                  <label>Pon </label>
                                  <input type="number" class="form-control" required name="olt_pon" value="{{$d->olt_pon}}">
                                </div>
                                <div class="col-6 form-group">
                                  <label>Upload Foto Topologi</label>
                                  <input  type="file" class="form-control-file" name="olt_file_topologi" >
                                </div>
                                <div class="col-6 form-group">
                                  <label>Status</label>
                                  <select name="olt_status" class="form-control" id="">
                                    <option value="{{$d->olt_status}}">{{$d->olt_status}}</option>
                                    <option value="Enable">Enable</option>
                                    <option value="Disable">Disable</option>
                                  </select>
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
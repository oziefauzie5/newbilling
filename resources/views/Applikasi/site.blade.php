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
                <button class="btn btn-sm" data-toggle="modal" data-target="#modal-adduser" class="btn btn-primary btn-sm"><i class="fas fa-solid fa-plus"></i>Tambah Site</button>
              </div>
              <!-- ----------------------------------------------------------------------MODAL ADD AKUN------------------------------------------------ -->
              
              <div class="modal fade" id="modal-adduser">
                  <div class="modal-dialog">
                    <form action="{{route('admin.app.site_store')}}" method="POST">
                      @csrf
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h4 class="modal-title">TAMBAH SITE </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">

                        
                            <div class="card-body">
                              <div class="form-group">
                                  <label>Id Site</label>
                                  <input type="text" class="form-control"  name="id" value="{{$id}}" readonly>
                              </div>
                              <div class="form-group">
                                  <label>Nama Site</label>
                                  <input type="text" class="form-control" name="site_nama" placeholder="Masukan Nama Site">
                              </div>
                              <div class="form-group">
                                <label>Prefix kode</label>
                                <input type="text" class="form-control" name="site_prefix">
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
                      <th>Id Site</th>
                      <th>Nama Site</th>
                      <th>Kode Prefix</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($data_site as $d)
                      <tr>
                        <td>{{$d->id}}</td>
                        <td>{{$d->site_nama}}</td>
                        <td>{{$d->site_prefix}}</td>
                        <td>{{$d->site_status}}</td>
                        <td>
                          <div class="form-button-action">
                              <button type="button" data-toggle="modal" data-target="#modal-edit{{$d->id}}" class="btn btn-link btn-primary btn-lg">
                                <i class="fa fa-edit"></i>
                              </button>
                            </div>
                          </td>
                      <div class="modal fade" id="modal-edit{{$d->id}}">
                          <div class="modal-dialog">
                            <form action="{{route('admin.app.update_site',['id'=>$d->id])}}" method="POST">
                            @csrf  
                            @method('PUT')
                            <div class="modal-content">
                              <div class="modal-header bg-primary ">
                                <h4 class="modal-title">EDIT SITE </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                    <div class="card-body">
                            <div class="form-group">
                                <label>Nama Site</label>
                                <input type="text" class="form-control" name="site_nama" value="{{$d->site_nama}}">
                            </div>
                            <div class="form-group">
                                <label>Prefix kode</label>
                                <input type="text" class="form-control" name="site_prefix" value="{{$d->site_prefix}}">
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="site_status" class="form-control">
                                  @if($d->site_status) 
                                  <option value="{{$d->site_status}}">{{$d->site_status}}</option>
                                  @endif
                                  <option value="Enable">Enable</option>
                                  <option value="Disable">Disable</option>
                                </select>
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
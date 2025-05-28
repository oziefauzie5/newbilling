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
                <button class="btn btn-sm" data-toggle="modal" data-target="#modal-adduser" class="btn btn-primary btn-sm"><i class="fas fa-solid fa-plus"></i>Tambah Kelurahan</button>
                <a href="{{route('admin.app.data_rt')}}"><button class="btn btn-sm" class="btn btn-primary btn-sm"><i class="fas fa-solid fa-table"></i> Data RT</button></a>
              </div>
              <!-- ----------------------------------------------------------------------MODAL ADD AKUN------------------------------------------------ -->
              
              <div class="modal fade" id="modal-adduser">
                  <div class="modal-dialog">
                    <form action="{{route('admin.app.kelurahan_store')}}" method="POST">
                      @csrf
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h4 class="modal-title">TAMBAH KELURAHAN </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">

                        
                            <div class="card-body">
                              <div class="form-group">
                                  <label>Site</label>
                                  <select name="kel_site_id" class="form-control" required>
                                    <option value="">--Pilih Site--</option>
                                    @foreach($data_site as $site)
                                    <option value="{{$site->site_id}}">{{$site->site_nama}}</option>
                                    @endforeach
                                  </select>
                              </div>
                              <div class="form-group">
                                  <label>Nama Kelurahan</label>
                                  <input type="text" class="form-control" name="kel_nama" placeholder="Masukan Nama Kelurahan">
                              </div>
                              <div class="form-group">
                                  <label>Keterangan</label>
                                  <input type="text" class="form-control"  name="kel_ket" placeholder="Masukan Keterangan">
                              </div>
                            </div>
                      </div>
                      <div class="modal-footer justify-content-between">
                              <button type="button" class="btn btn-sm" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary btn-sm" >Simpan</button>
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
                      <th>Kelurahan</th>
                      <th>Keterangan</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($data_kelurahan as $d)
                      <tr>
                        <td>{{$d->site[0]->site_nama ?? ''}}</td>
                        <td>{{$d->kel_nama}}</td>
                        <td>{{$d->kel_ket}}</td>
                        <td>{{$d->kel_status}}</td>
                        <td>
                          <div class="form-button-action">
                              <button type="button" data-toggle="modal" data-target="#modal-edit{{$d->kel_id}}" class="btn btn-link btn-primary btn-lg">
                                <i class="fa fa-edit"></i>
                              </button>
                            </div>
                          </td>
                      <div class="modal fade" id="modal-edit{{$d->kel_id}}">
                          <div class="modal-dialog">
                            <form action="{{route('admin.app.update_kelurahan',['id'=>$d->kel_id])}}" method="POST">
                            @csrf  
                            @method('PUT')
                            <div class="modal-content">
                              <div class="modal-header bg-primary ">
                                <h4 class="modal-title">EDIT KELURAHAN </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                    <div class="card-body">
                                      <div class="form-group">
                                          <label>Site</label>
                                          <select name="kel_site_id" class="form-control" required>
                                            @if($d->site_nama)
                                            <option value="{{$d->site_id}}">{{$d->site_nama}}</option>
                                            @endif
                                            <option value="">--Pilih Site--</option>
                                            @foreach($data_site as $site)
                                            <option value="{{$site->site_id}}">{{$site->site_nama}}</option>
                                            @endforeach
                                          </select>
                                      </div>
                                      <div class="form-group">
                                          <label>Nama Kelurahan</label>
                                          <input type="text" class="form-control" name="kel_nama" value="{{$d->kel_nama}}">
                                      </div>
                                      <div class="form-group">
                                          <label>Keterangan</label>
                                          <input type="text" class="form-control"  name="kel_ket" value="{{$d->kel_ket}}">
                                      </div>
                                      <div class="form-group">
                                          <label>Status</label>
                                          <select name="kel_status" class="form-control">
                                            @if($d->kel_status) 
                                            <option value="{{$d->kel_status}}">{{$d->kel_status}}</option>
                                            @endif
                                          <option value="">--Pilih Status--</option>
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
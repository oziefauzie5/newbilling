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
              <button class="btn btn-sm" data-toggle="modal" data-target="#modal-adduser" class="btn btn-primary btn-sm"><i class="fas fa-solid fa-plus"></i>Tambah Kecamatan</button>
                <a href="{{route('admin.app.site')}}"><button class="btn btn-sm"  class="btn btn-primary btn-sm"><i class="fas fa-solid fa-plus"></i>Site</button></a>
                <a href="{{route('admin.app.kelurahan')}}"><button class="btn btn-sm"  class="btn btn-primary btn-sm"><i class="fas fa-solid fa-plus"></i>Kelurahan</button></a>
              </div>
              <!-- ----------------------------------------------------------------------MODAL ADD AKUN------------------------------------------------ -->
              
              <div class="modal fade" id="modal-adduser">
                  <div class="modal-dialog">
                    <form action="{{route('admin.app.kecamatan_store')}}" method="POST">
                      @csrf
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h4 class="modal-title">TAMBAH KECAMATAN </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">

                        
                            <div class="card-body">
                              <div class="form-group">
                                  <label>Site</label>
                                  <select name="data__site_id" id="" class="form-control" required>
                                    <option value="">--Pilih Site--</option>
                                    @foreach ($data_site as $s)
                                        <option value="{{$s->id}}">{{$s->site_nama}}</option>
                                    @endforeach
                                  </select>
                              </div>
                              <div class="form-group">
                                  <label>Nama Kecamatan</label>
                                  <input type="text" class="form-control" name="kec_nama" placeholder="Masukan Nama Site" required>
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
                      <th>Kecamatan</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($data_kecamatan as $d)
                      <tr>
                        <td>{{$d->kecamatan_site->site_nama}}</td>
                        <td>{{$d->kec_nama}}</td>
                        <td>
                          <div class="form-button-action">
                              <button type="button" data-toggle="modal" data-target="#modal-edit{{$d->id}}" class="btn btn-link btn-primary btn-lg">
                                <i class="fa fa-edit"></i>
                              </button>
                            </div>
                          </td>
                      <div class="modal fade" id="modal-edit{{$d->id}}">
                          <div class="modal-dialog">
                            <form action="{{route('admin.app.update_kecamatan',['id'=>$d->id])}}" method="POST">
                            @csrf  
                            @method('PUT')
                            <div class="modal-content">
                              <div class="modal-header bg-primary ">
                                <h4 class="modal-title">EDIT KECAMATAN </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                    <div class="card-body">
                                        <div class="form-group">
                                  <label>Site</label>
                                  <select name="data__site_id" id="" class="form-control" required>
                                    <option value="{{$d->data__site_id}}">{{$d->kecamatan_site->site_nama}}</option>
                                    @foreach ($data_site as $s)
                                        <option value="{{$s->id}}">{{$s->site_nama}}</option>
                                    @endforeach
                                  </select>
                              </div>
                            <div class="form-group">
                                <label>Nama Kecamatan</label>
                                <input type="text" class="form-control" name="kec_nama" value="{{$d->kec_nama}}">
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
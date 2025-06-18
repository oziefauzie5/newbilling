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
                <a href="{{route('admin.app.site')}}"><button class="btn btn-sm"  class="btn btn-primary btn-sm"><i class="fas fa-solid fa-plus"></i>Site</button></a>
                <a href="{{route('admin.app.kecamatan')}}"><button class="btn btn-sm"  class="btn btn-primary btn-sm"><i class="fas fa-solid fa-plus"></i>Kecamatan</button></a>
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
                                  <label>Kecamatan</label>
                                  <select name="data__kecamatan_id" id="" class="form-control" required>
                                    <option value="">--Pilih Kecamatan--</option>
                                    @foreach ($data_kecamatan as $k)
                                        <option value="{{$k->id}}">{{$k->kec_nama}}</option>
                                    @endforeach
                                  </select>
                              </div>
                              <div class="form-group">
                                  <label>Nama Kelurahan</label>
                                  <input type="text" class="form-control" name="kel_nama" placeholder="Masukan Nama Kelurahan" required>
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
                      <th>Kecamatan</th>
                      <th>Kelurahan</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($data_kelurahan as $d)
                      <tr>
                        <td>{{$d->kecamatan->kec_nama}}</td>
                        <td>{{$d->kel_nama}}</td>
                        <td>
                          <div class="form-button-action">
                              <button type="button" data-toggle="modal" data-target="#modal-edit{{$d->id}}" class="btn btn-link btn-primary btn-lg">
                                <i class="fa fa-edit"></i>
                              </button>
                            </div>
                          </td>
                      <div class="modal fade" id="modal-edit{{$d->id}}">
                          <div class="modal-dialog">
                            <form action="{{route('admin.app.update_kelurahan',['id'=>$d->id])}}" method="POST">
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
                                  <label>Kecamatan</label>
                                  <select name="data__kecamatan_id" id="" class="form-control" required>
                                    <option value="{{$d->data__kecamatan_id}}">{{$d->kecamatan->kec_nama}}</option>
                                    @foreach ($data_kecamatan as $k)
                                        <option value="{{$k->id}}">{{$k->kec_nama}}</option>
                                    @endforeach
                                  </select>
                              </div>
                            <div class="form-group">
                                <label>Nama Kelurahan</label>
                                <input type="text" class="form-control" name="kel_nama" value="{{$d->kel_nama}}">
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
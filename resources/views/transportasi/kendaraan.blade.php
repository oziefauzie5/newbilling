@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-primary">
            <div class="d-flex align-items-center">
              <h4 class="card-title text-light">DATA KENDARAAN</h4>
            </div>
          </div>
          <div class="card-body">
            <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#add">
              <i class="fa fa-plus"></i> Tambah Data Kendaraan</button>      
              
              <!-- Modal -->
<div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Data Kendaraan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.app.store_kendaraan')}}" method="post">
          @csrf
          @method('POST')

          <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Plat Nomor</label>
                    <input  type="text" class="form-control" name="trans_plat_nomor"placeholder="F 1234 RI" value="" required>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Jenis Kendaraan</label>
                    <input  type="text" class="form-control" name="trans_jenis_motor"placeholder="Honda Beat" value="" required>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Batas Anggaran Service</label>
                    <input  type="number" class="form-control" name="trans_service"placeholder="Honda Beat" value="0" required>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Batas Anggaran Bensin</label>
                    <input  type="number" class="form-control" name="trans_bensin" value="0" required>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Batas Anggaran Sewa</label>
                    <input  type="number" class="form-control" name="trans_sewa" value="0" required>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Divisi</label>
                    <select name="trans_divisi_id"  class="form-control">
                        <option value="">PILIH DIVISI</option>
                        @foreach ($permision as $divisi)
                        <option value="{{$divisi->id}}">{{$divisi->name}}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
         
            @if ($errors->any())
            <div class="alert alert-danger">
              <div class="alert-title"><h4>Gagal!!</h4></div>
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
            </div> 
            @endif
            <hr>
            <form >
              <div class="row mb-1">
                <div class="col-sm-4">
                  <input name="q" type="text" class="form-control form-control-sm" placeholder="Cari">
                </div>
                <div class="col-sm-2">
                  <button type="submit" class="btn btn-block btn-dark btn-sm">Submit
                </div>
              </div>
              </form>
              <hr>
              <div class="table-responsive">
                <table class="display table table-striped table-hover text-nowrap" >
                  <thead>
                    <tr>
                        <th>Plat Nomor</th>
                        <th>Jenis Motor</th>
                        <th>Biaya Bensin</th>
                        <th>Biaya Service</th>
                        <th>Biaya Sewa</th>
                        <th>Divisi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($kendaraan as $d)
                    <tr>
                    </td>    
                    <td>{{$d->trans_plat_nomor}}</td>
                    <td>{{$d->trans_jenis_motor}}</td>
                    <td>{{$d->trans_bensin}}</td>
                    <td>{{$d->trans_service}}</td>
                    <td>{{$d->trans_sewa}}</td>
                    <td>{{$d->name}}</td>
                    <td>{{$d->trans_status}}</td>
                    <td><div class="form-button-action">
                        <button class="btn btn btn-link btn-primary  " data-toggle="modal" data-target="#edit{{$d->trans_id}}">
                        <i class="fas fa-edit"></i></button>
                        <button class="btn btn-link btn-danger " data-toggle="modal" data-target="#delete{{$d->trans_id}}">
                        <i class="fas fa-trash"></i></button>        
                        </div>    
                        
                        <!-- Modal -->
          <div class="modal fade" id="edit{{$d->trans_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Tambah Data Kendaraan</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{route('admin.app.update_kendaraan',['id'=>$d->trans_id])}}" method="post">
                    @csrf
                    @method('PUT')
          
                    <div class="row">
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label>Plat Nomor</label>
                              <input  type="text" class="form-control" name="trans_plat_nomor"  value="{{$d->trans_plat_nomor}}" required>
                            </div>
                          </div>
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label>Jenis Kendaraan</label>
                              <input  type="text" class="form-control" name="trans_jenis_motor"  value="{{$d->trans_jenis_motor}}" required>
                            </div>
                          </div>
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label>Batas Anggaran Service</label>
                              <input  type="number" class="form-control" name="trans_service"  value="{{$d->trans_service}}" required>
                            </div>
                          </div>
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label>Batas Anggaran Bensin</label>
                              <input  type="number" class="form-control" name="trans_bensin" value="{{$d->trans_bensin}}" required>
                            </div>
                          </div>
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label>Batas Anggaran Sewa</label>
                              <input  type="number" class="form-control" name="trans_sewa" value="{{$d->trans_sewa}}" required>
                            </div>
                          </div>
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label>Divisi</label>
                              <select name="trans_divisi_id"  class="form-control">
                                  <option value="{{$d->trans_id}}">{{$d->name}}</option>
                                  @foreach ($permision as $divisi)
                                  <option value="{{$divisi->id}}">{{$divisi->name}}</option>
                                  @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label>Status</label>
                              <select name="trans_status"  class="form-control">
                                <option value="{{$d->trans_status}}">{{$d->trans_status}}</option>
                                <option value="Enable">Enable</option>
                                <option value="Disable">Disable</option>
                              </select>
                            </div>
                          </div>
                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
            <!-- Modal delete{ -->
<div class="modal fade" id="delete{{$d->trans_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Hapus Data Kendaraan</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form action="{{route('admin.app.delete_kendaraan',['id'=>$d->trans_id])}}" method="post">
        @csrf
        @method('DELETE')
Apakah anda yakin menghapus kendaraan {{$d->trans_plat_nomor}}
        
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Ya</button>
      </form>
    </div>
  </div>
</div>
</div></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                
                <div class="pull-left">
                  Showing
                  {{$kendaraan->firstItem()}}
                  to
                  {{$kendaraan->lastItem()}}
                  of
                  {{$kendaraan->total()}}
                  entries
                </div>
                <div class="pull-right">
                  {{ $kendaraan->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
              </div>
            
           
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
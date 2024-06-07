@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="d-flex align-items-center">
            <h4 class="card-title">INPUT DATA</h4>
            <button class="btn btn-primary btn-round ml-auto btn-sm" data-toggle="modal" data-target="#addRowModal">
              <i class="fa fa-plus"></i>
              Input Data
            </button>
          </div>
        </div>
        <div class="card-body">
          <!-- Modal -->
          <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header no-bd">
                  <h5 class="modal-title">
                    <span class="fw-mediumbold">
                    Input Data Baru</span> 
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{route('admin.user.store')}}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Nama Lengkap</label>
                          <input id="name" type="text" class="form-control" name="name"placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>No Identitas</label>
                          <input id="ktp" type="text" class="form-control" value="{{ old('ktp') }}" name="ktp" onkeyup="validasiKtp()" placeholder="No. Identitas" required>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>No Hp</label>
                          <input id="hp" type="text" class="form-control" value="{{ old('hp') }}" name="hp" placeholder="No. Whatsapp" required>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Email</label>
                          <input id="email" type="text" class="form-control" value="{{ old('email') }}" name="email" placeholder="Email">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Alamat Lengkap</label>
                          <input id="alamat_lengkap" type="text" class="form-control" value="{{ old('alamat_lengkap') }}" name="alamat_lengkap" placeholder="Alamat Lengkap">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Username</label>
                          <input id="username" type="text" class="form-control" value="{{ old('username') }}" name="username" placeholder="Username">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Password</label>
                          <input id="password" type="text" class="form-control" value="{{ old('password') }}" name="password" placeholder="Password">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="level" >Level</label>
                          <select name="level" id="level" class="form-control" required value="{{ old('level') }}">
                              <option value="">-PILIH-</option>
                              <option value="1|admin">ADMINISTRATOR</option>
                              <option value="5|staf">STAF ADMIN</option>
                              <option value="7|noc">NOC</option>
                              <option value="8|keuangan">KEUANGAN</option>
                              <option value="9|gudang">GUDANG</option>
                              <option value="11|teknisi">TEKNISI</option>
                              <option value="12|sales">SALES</option>
                              <option value="14|reseller">RESELLER</option>
                            </select>
                        </div>>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer no-bd">
                    <button type="submit" class="btn btn-success">Add</button>
                  </form>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
          <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover" >
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nama</th>
                  <th>Level</th>
                  <th>Whatsapp</th>
                  <th width="250%">Alamat Lengkap</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>ID</th>
                  <th>Nama</th>
                  <th>Level</th>
                  <th>Whatsapp</th>
                  <th width="250px">Alamat Lengkap</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </tfoot>
              <tbody>
                @foreach ($data_user as $d)
                <tr>
                      <td>{{$d->id}}</td>
                      <td>{{$d->name}}</td>
                      <td>{{$d->level}}</td>
                      <td>{{$d->hp}}</td>
                      <td width="250px">{{$d->alamat_lengkap}}</td>
                      <td>{{$d->status_user}}</td>
                      <td>
                        <div class="form-button-action">
                          <button type="button" data-toggle="modal" data-target="#modal_edit{{$d->id}}" class="btn btn-link btn-primary btn-lg">
                            <i class="fa fa-edit"></i>
                          </button>
                          <button type="button" data-toggle="modal" data-target="#modal_hapus{{$d->id}}" class="btn btn-link btn-danger">
                            <i class="fa fa-times"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                      <!-- Modal Edit -->
                      <div class="modal fade" id="modal_edit{{$d->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header no-bd">
                              <h5 class="modal-title">
                                <span class="fw-mediumbold">
                                Edit Data {{$d->input_nama}}</span> 
                              </h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <form action="{{route('admin.user.edit',['id'=>$d->id])}}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label>Nama Lengkap</label>
                                      <input id="name" type="text" class="form-control" name="name" value="{{ $d->name }}" required>
                                    </div>
                                  </div>
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label>No Identitas</label>
                                      <input id="ktp" type="text" class="form-control" value="{{ $d->ktp }}" name="ktp" onkeyup="validasiKtp()" placeholder="No. Identitas" required>
                                    </div>
                                  </div>
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label>No Hp</label>
                                      <input id="hp" type="text" class="form-control" value="{{ $d->hp }}" name="hp" placeholder="No. Whatsapp" required>
                                    </div>
                                  </div>
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label>Email</label>
                                      <input id="email" type="text" class="form-control" value="{{ $d->email }}" name="email" placeholder="Email">
                                    </div>
                                  </div>
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label>Alamat Lengkap</label>
                                      <input id="alamat_lengkap" type="text" class="form-control" value="{{ $d->alamat_lengkap }}" name="alamat_lengkap">
                                    </div>
                                  </div>
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label>Username</label>
                                      <input id="username" type="text" class="form-control" name="username" placeholder="Username">
                                    </div>
                                  </div>
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label>Password</label>
                                      <input id="password" type="text" class="form-control" name="password" placeholder="Password">
                                    </div>
                                  </div>
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label for="level" >Level</label>
                                      <select name="level" id="level" class="form-control" required>
                                        @if($d->level)
                                        <option selected value="{{$d->role_id}}|{{$d->level}}">{{$d->level}}</option>
                                        @endif
                                        @foreach ($role as $r)
                                        <option value="{{$r->id}}|{{$r->name}}">{{$r->name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer no-bd">
                                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- End Modal Edit -->
                      <!-- Modal Hapus -->
                      <div class="modal fade" id="modal_hapus{{$d->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header no-bd">
                              <h5 class="modal-title">
                                <span class="fw-mediumbold">
                                Hapus Data {{$d->name}}</span> 
                              </h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                              <div class="modal-body">
                              <p>Apakah anda yakin, akan menghapus data {{$d->name}} ??</p>
                              </div>
                              <div class="modal-footer no-bd">
                                <form action="{{route('admin.user.delete',['id'=>$d->id])}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-success">Hapus</button>
                              </form>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- End Modal Hapus -->
                    @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection




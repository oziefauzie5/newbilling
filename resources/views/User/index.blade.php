@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="d-flex align-items-center">
            <h4 class="card-title">DATA USER</h4>
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
                          <input id="name" type="text" class="form-control" name="name"placeholder="Nama Lengkap" value="{{ old('name') }}">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>No Identitas</label>
                          <input id="ktp" type="number" class="form-control" value="{{ old('ktp') }}" name="ktp" onkeyup="validasiKtp()" placeholder="No. Identitas">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>No Hp</label>
                          <input id="hp" type="number" class="form-control" value="{{ old('hp') }}" name="hp" placeholder="No. Whatsapp">
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
                          <label>Site</label>
                          <select name="data__site_id" class="form-control" id="">
                          <option value="">--Pilih Site--</option>
                          @foreach ($data_site as $site)
                              <option value="{{$site->id}}">{{$site->site_nama}}</option>
                          @endforeach
                        </select>
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
                          <select name="level" id="level" class="form-control" value="{{ old('level') }}">
                              <option value="">-PILIH-</option>
                              <option value="1|admin">ADMINISTRATOR</option>
                              <option value="5|STAF ADMIN">STAF ADMIN</option>
                              <option value="7|NOC">NOC</option>
                              <option value="8|KEUANGAN">KEUANGAN</option>
                              <option value="9|GUDANG">GUDANG</option>
                              <option value="11|TEKNISI">TEKNISI</option>
                              
                            </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer no-bd">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
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
          <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover" >
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Foto</th>
                  <th>Nama</th>
                  <th>Level</th>
                  <th>Whatsapp</th>
                  <th>Site</th>
                  <th width="250%">Alamat Lengkap</th>
                  <th>Tanggal Gabung</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
            </thead>
              <tbody>
                @foreach ($data_user as $d)
                <tr>
                      <td>{{$d->id}}</td>
                      <td> <img src="{{ asset('storage/photo-user/'.$d->photo ?? '-') }}" width="100" alt="" title=""></img></td>
                      <td>{{$d->name ?? '-'}}</td>
                      <td>{{$d->level ?? '-'}}</td>
                      <td>{{$d->hp ?? '-'}}</td>
                      <td>{{$d->user_site->site_nama ?? '-'}}</td>
                      <td width="250px">{{$d->alamat_lengkap ?? '-'}}</td>
                      <td>{{date('d-m-Y', strtotime($d->tgl_gabung)) ?? '-'}}</td>
                      <td>{{$d->status_user}}</td>
                      <td>
                        <div class="form-button-action">
                          <button type="button" data-toggle="modal" data-target="#modal_edit{{$d->id}}" class="btn btn-link btn-primary btn-lg">
                            <i class="fa fa-edit"></i>
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
                              <form action="{{route('admin.user.edit',['id'=>$d->id])}}" method="POST" enctype="multipart/form-data">
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
                                      <label>Site</label>
                                      <select name="data__site_id" class="form-control" id="">
                                        @if ($d->data__site_id)
                                            <option value="{{ $d->data__site_id }}">{{ $d->user_site->site_nama }}</option>
                                        @endif
                                      @foreach ($data_site as $site)
                                          <option value="{{$site->id}}">{{$site->site_nama}}</option>
                                      @endforeach
                                    </select>
                                    </div>
                                  </div>
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label>Username</label>
                                      <input id="username" type="text" class="form-control" value="{{$d->username}}" name="username" placeholder="Username">
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
                                      <label>Upload Foto</label>
                                      <input  type="file" class="form-control-file" name="file">
                                    </div>
                                  </div>
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label for="level" >Level</label>
                                      <select name="level" id="level" class="form-control" required>
                                        @if($d->level)
                                        <option value="{{$d->role_id}}|{{$d->level}}">{{$d->level}}</option>
                                        @endif
                                        @foreach ($role as $r)
                                        <option value="{{$r->id}}|{{$r->name}}">{{$r->name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                  </div>
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label for="" >Status</label>
                                      <select name="status_user" id="" class="form-control" required>
                                        <option value="{{$d->status_user}}">{{$d->status_user}}</option>
                                        <option value="Enable">Enable</option>
                                        <option value="Disable">Disable</option>
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




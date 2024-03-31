@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <a href="{{route('admin.psb.list_input')}}" class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-success">
              6%
              <i class="fa fa-chevron-up"></i>
            </div>
            <div class="h1 m-0">{{$count_inputdata}}</div>
            <div class="text-muted mb-3">Input Data</div>
          </div>
        </div>
      </a>
      <a href="{{route('admin.reg.index')}}" class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-danger">
              -3%
              <i class="fa fa-chevron-down"></i>
            </div>
            <div class="h1 m-0">{{$count_registrasi}}</div>
            <div class="text-muted mb-3">Registrasi</div>
          </div>
        </div>
      </a>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-success">
              3%
              <i class="fa fa-chevron-up"></i>
            </div>
            <div class="h1 m-0">{{$count_berlangganan}}</div>
            <div class="text-muted mb-3">Berlangganan</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-danger">
              -2%
              <i class="fa fa-chevron-down"></i>
            </div>
            <div class="h1 m-0">1</div>
            <div class="text-muted mb-3">Putus Berlanggan</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-danger">
              -1%
              <i class="fa fa-chevron-down"></i>
            </div>
            <div class="h1 m-0">2</div>
            <div class="text-muted mb-3">Isolir</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-success">
              9%
              <i class="fa fa-chevron-up"></i>
            </div>
            <div class="h1 m-0">7</div>
            <div class="text-muted mb-3">Tiket</div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="card">
        <div class="card-body">
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
        <a href="{{route('admin.reg.sementara_migrasi')}}">
        <button class="btn  btn-sm ml-auto m-1 btn-dark " data-toggle="modal" data-target="#addRowModal">
          <i class="fa fa-plus"></i>
          FITUR SEMENTARA MIGRASI
        </button>
      </a>
        <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#import">
          <i class="fa fa-file-import"></i> Import
        </button>
         <!-- Modal Import -->
         <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-hidden="true">
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
                <form action="{{route('admin.reg.registrasi_import')}}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('POST')
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label>Pilih file (EXCEL,CSV)</label>
                        <input id="import" type="file" class="form-control" name="file" placeholder="Nama Lengkap" required>
                      </div>
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
          <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover text-nowrap" >
              <thead>
                <tr>
                  <th>ID</th>
                  <th>INET</th>
                  <th>NOLAYANAN</th>
                  <th>PELANGGAN</th>
                  <th>KTG</th>
                  <th>PROFILE</th>
                  <th>JENIS TAGIHAN</th>
                  <th>TGL AKTIF</th>
                  <th>TGL TAGIH</th>
                  <th>TGL JT TEMPO</th>
                  <th>USERNAME</th>
                  <th>PASSWORD</th>
                  <th>ROUTER</th>
                  <th>IP ADDRESS</th>
                  <th>MAC ADDRESS</th>
                  <th>SERIAL NUMBER</th>
                  <th>ALAMAT PASANG</th>
                  <th>TG REGISTRASI</th>
                  <th>KODE UNIK</th>
                  <th>NOTE</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_registrasi as $d)
                <tr>
                      <td>{{$d->reg_idpel}}</td>
                      <td>{{$d->reg_idpel}}</td>
                      <td>{{$d->reg_nolayanan}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->input_nama}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_layanan}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->paket_nama}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_jenis_tagihan}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_tgl_pasang}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_tgl_tagih}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_tgl_jatuh_tempo}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_username}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_password}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->router_nama}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_ip_address}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_mac}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_sn}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->input_alamat_pasang}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->input_tgl}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_kode_unik}}</td>
                      <td>{{$d->reg_catatan}}</td>
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
                                      <input id="username" type="text" class="form-control"  name="username" placeholder="Username">
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
                                    </div>
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
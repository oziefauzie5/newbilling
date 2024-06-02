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
        <hr>
        <form >
        <div class="row mb-1">
          <div class="col-sm-3">
              <select name="data" class="custom-select custom-select-sm">
                @if($data)
                <option value="{{$data}}" selected>{{$data}}</option>
                @endif
                <option value="">ALL DATA</option>
                <option value="PPP">USER PPP</option>
                <option value="DHCP">USER DHCP</option>
                <option value="HOTSPOT">USER HOTSPOT</option>
                <option value="USER BARU">USER BARU</option>
                <option value="USER BULAN LALU">USER BULAN LALU</option>
              </select>
          </div>
          <div class="col-sm-3">
              <select name="router" class="custom-select custom-select-sm">
                @if($router)
                <option value="{{$router}}" selected>{{$r_nama}}</option>
                @endif
                <option value="">ALL ROUTER</option>
                @foreach($get_router as $router)
                <option value="{{$router->id}}">{{$router->router_nama}}</option>
                @endforeach
              </select>
          </div>
          <div class="col-sm-3">
              <select name="paket" class="custom-select custom-select-sm">
                @if($paket)
                <option value="{{$paket}}" selected>{{$p_nama}}</option>
                @endif
                <option value="">ALL PAKET</option>
                @foreach($get_paket as $paket)
                <option value="{{$paket->paket_id}}">{{$paket->paket_nama}}</option>
                @endforeach
              </select>
          </div>
          <div class="col-sm-3">
            <button type="submit" class="btn btn-block btn-dark btn-sm">Submit
          </div>
        </div>
        </form>
        <hr>
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
                  <th>#</th>
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
                  <th>KODE KABEL</th>
                  <th>BEFORE</th>
                  <th>AFTER</th>
                  <th>PENGGUNAAN</th>
                  <th>ODP OPM</th>
                  <th>HOME OPM</th>
                  <th>LOS OPM</th>
                  <th>NOTE</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_registrasi as $d)
                <tr>
                  <td>
                    <div class="form-button-action">
                      <button type="button" data-toggle="modal" data-target="#modal_hapus{{$d->reg_idpel}}" class="btn btn-link btn-danger">
                        <i class="fa fa-times"></i>
                      </button>
                      <a href="{{route('admin.psb.berita_acara',['id'=>$d->reg_idpel])}}" target="_blank">
                      <button type="button" class="btn btn-link btn-dark">
                        Berita acara
                      </button></a>
                      @if($d->reg_progres == '2')
                      <a href="{{route('admin.psb.bukti_kas_keluar',['id'=>$d->reg_idpel])}}" target="_blank">
                      <button type="button" class="btn btn-link btn-dark">
                        Kas
                      </button></a>
                      @elseif($d->reg_progres == '3')
                      <a>
                        <button type="button" class="btn btn-link btn-success">
                          Lunas
                        </button></a>
                      @else
                      <a>
                        <button type="button" class="btn btn-link btn-warning">
                          Proses
                        </button></a>
                    
                      @endif
                    </div>
                  </td>
                      <td>{{$d->reg_progres}}</td>
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
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_kode_dropcore}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_before}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_after}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_penggunaan_dropcore}} Meter</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_fat_opm}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_home_opm}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_los_opm}}</td>
                      <td>{{$d->reg_catatan}}</td>
                    </tr>
                      {{-- <!-- Modal Edit -->
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
                      <!-- End Modal Edit --> --}}
                      <!-- Modal Hapus -->
                      <div class="modal fade" id="modal_hapus{{$d->reg_idpel}}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <form action="{{route('admin.reg.delete_registrasi',['id'=>$d->reg_idpel])}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="text">
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
@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <div class="row">
              <div class="col-6 col-sm-4 col-lg-1"  data-target="#carouselExampleControls" data-slide="prev">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0"><i class="fas fa-angle-double-left "></i></div>
                    <div class="text-muted mb-3">Prev</div>
                  </div>
                </div>
              </div>


              <a href="{{route('admin.psb.list_input')}}" class="col-6 col-sm-4 col-lg-2">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">{{$count_inputdata}}</div>
                    <div class="text-muted mb-3">Input Data</div>
                  </div>
                </div>
              </a>
              <a href="{{route('admin.reg.index')}}" class="col-6 col-sm-4 col-lg-2">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">{{$count_registrasi}}</div>
                    <div class="text-muted mb-3">Registrasi</div>
                  </div>
                </div>
              </a>
              <div class="col-6 col-sm-4 col-lg-2">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">{{$count_berlangganan}}</div>
                    <div class="text-muted mb-3">Berlangganan</div>
                  </div>
                </div>
              </div>
              <div class="col-6 col-sm-4 col-lg-2">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">{{$count_pb}}</div>
                    <div class="text-muted mb-3">Putus Berlanggan</div>
                  </div>
                </div>
              </div>
              <div class="col-6 col-sm-4 col-lg-2">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">{{$count_ps}}</div>
                    <div class="text-muted mb-3">Putus Sementara</div>
                  </div>
                </div>
              </div>
              <div class="col-6 col-sm-4 col-lg-1"  data-target="#carouselExampleControls" data-slide="next">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0"><i class="fas fa-angle-double-right "></i></div>
                    <div class="text-muted mb-3">Next</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="carousel-item">
          <div class="row">
            <div class="col-6 col-sm-4 col-lg-1"  data-target="#carouselExampleControls" data-slide="Prev">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="h1 m-0"><i class="fas fa-angle-double-left "></i></div>
                  <div class="text-muted mb-3">Prev</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-sm-4 col-lg-2">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="h1 m-0">{{$count_inputdata}}</div>
                  <div class="text-muted mb-3">PPP</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-sm-4 col-lg-2">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="h1 m-0">{{$count_registrasi}}</div>
                  <div class="text-muted mb-3">DHCP</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-sm-4 col-lg-2">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="h1 m-0">{{$count_berlangganan}}</div>
                  <div class="text-muted mb-3">HOTSPOT</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-sm-4 col-lg-2">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="h1 m-0">{{$count_free_berlangganan}}</div>
                  <div class="text-muted mb-3">Free Berlanggan</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-sm-4 col-lg-2">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="h1 m-0">7</div>
                  <div class="text-muted mb-3">Tiket</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-sm-4 col-lg-1"  data-target="#carouselExampleControls" data-slide="next">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="h1 m-0"><i class="fas fa-angle-double-right "></i></div>
                  <div class="text-muted mb-3">Next</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
     {{-- <div class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </div> --}}
      <div class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
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
      <button class="btn  btn-sm ml-auto m-1 btn-dark " data-toggle="modal" data-target="#import">
        <i class="fa fa-file-import"></i> IMPORT
      </button>
      <a href="{{route('admin.psb.berita_acara')}}">
      <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#addRowModal">
        <i class="fa fa-plus"></i>
        BERITA ACARA
      </button>
    </a>
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
                  {{-- <th>ID</th>
                  <th>INET</th> --}}
                  <th>NO LAYANAN</th>
                  <th>PELANGGAN</th>
                  <th>TGL JT TEMPO</th>
                  <th>TGL Registrasi</th>
                  <th>PROFILE</th>
                  <th>ROUTER</th>
                  <th>KTG</th>
                  <th>JENIS TAGIHAN</th>
                  <th>USERNAME</th>
                  <th>IP ADDRESS</th>
                  <th>MAC ADDRESS</th>
                  <th>SERIAL NUMBER</th>
                  <th>ALAMAT PASANG</th>
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
                    </div>
                  </td>
                      <td>{{$d->reg_nolayanan}}</td>
                      <td>{{$d->input_nama}}</td>
                      @if($d->reg_tgl_jatuh_tempo)
                      @if($d->inv_status != 'PAID')
                      <td class="href font-weight-bold" data-id="{{$d->reg_idpel}}" >{{date('d-m-Y',strtotime($d->reg_tgl_jatuh_tempo))}}</td>
                      @else
                      <td class="href text-danger font-weight-bold" data-id="{{$d->reg_idpel}}" >{{date('d-m-Y',strtotime($d->reg_tgl_jatuh_tempo))}}</td>
                      @endif
                      @else
                      <td class="text-danger font-weight-bold" >Belum Terpasang</td>
                      @endif
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{date('d-m-Y',strtotime($d->input_tgl))}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->paket_nama}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->router_nama}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_layanan}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_jenis_tagihan}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_username}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_ip_address}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_mac}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->reg_sn}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->input_alamat_pasang}}</td>
                      <td>{{$d->reg_catatan}}</td>
                    </tr>
                      <!-- Modal Hapus -->
                      <div class="modal fade" id="modal_hapus{{$d->reg_idpel}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header no-bd">
                              <h5 class="modal-title">
                                <span class="fw-mediumbold">
                                Hapus Data</span> 
                              </h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                              <div class="modal-body">
                              <p>Apakah anda yakin, akan menghapus data {{$d->input_nama}} ??</p>
                              </div>
                              <div class="modal-footer no-bd">
                                <form action="{{route('admin.reg.delete_registrasi',['id'=>$d->reg_idpel])}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-success">Ya.!! Abdi yakin pisan</button>
                              </form>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal. Abdi Kurang yakin</button>
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
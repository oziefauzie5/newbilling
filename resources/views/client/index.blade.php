@extends('layout.user')
@section('content')

<div class="content">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">

                
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">



        <div class="row mt--5">
            <div class="col-6 col-sm-6">
              <div class="card ">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-danger">
                  </div>
                  <div class="h1 m-0">621</div>
                  <div class="text-muted mb-3">Tagihan</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-sm-6">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-success">
                  </div>
                  <div class="h1 m-0">7</div>
                  <div class="text-muted mb-3">Pengguanaan</div>
                </div>
              </div>
            </div>
          </div>

          <section class="content mt-3">
                <div class="info-box shadow-none col-12 card_custom">
                    <div class="table-responsive">
                        <table>
                            <tr>
                                <th>
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right text-success">
                                        </div>
                                        <div class="h1 m-0"><img src="{{ asset('atlantis/assets/img/add_user.png') }}" class=" bg-light"></div>
                                        <div class="text-light mb-3">Registrasi</div>
                                      </div>
                                </th>
                                <th>
                                    <a href="{{ route('admin.teknisi.list_aktivasi') }}" class="card-body p-3 text-center">
                                        <div class="text-right text-success">
                                        </div>
                                        <div class="h1 m-0"><img src="{{ asset('atlantis/assets/img/ceklis.png') }}" class=" bg-light"></div>
                                        <div class="text-light mb-3">Aktivasi</div>
                                      </a>
                                </th>
                                <th>
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right text-success">
                                        </div>
                                        <div class="h1 m-0"><img src="{{ asset('atlantis/assets/img/selesai.png') }}" class=" bg-light"></div>
                                        <div class="text-light mb-3">Selesai</div>
                                      </div>
                                </th>
                                <th>
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right text-success">
                                        </div>
                                        <div class="h1 m-0"><img src="{{ asset('atlantis/assets/img/komisi.png') }}" class=" bg-light"></div>
                                        <div class="text-light mb-3">Komisi</div>
                                      </div>
                                </th>
                                <th>
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right text-success">
                                        </div>
                                        <div class="h1 m-0"><img src="{{ asset('atlantis/assets/img/cari.png') }}" class=" bg-light"></div>
                                        <div class="text-light mb-3">Cari</div>
                                      </div>
                                </th>
                                <th>
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right text-success">
                                        </div>
                                        <div class="h1 m-0"><img src="{{ asset('atlantis/assets/img/values.png') }}" class=" bg-light"></div>
                                        <div class="text-light mb-3">Penilaian</div>
                                      </div>
                                </th>
                                <th>
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right text-success">
                                        </div>
                                        <div class="h1 m-0"><img src="{{ asset('atlantis/assets/img/shutdown.png') }}" class=" bg-light"></div>
                                        <div class="text-light mb-3">Logout</div>
                                      </div>
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <div class="info-box shadow-sm" style="border-radius: 20px;" data-toggle="modal" data-target="#layanan">
            <span class="info-box-icon bg-success" style="border-radius: 20px;" ><i class="far fa-flag"></i></span>
              <div class="info-box-content">
                <h5 class="info-box-text">{{$layanan->nama}}</h5>
                @if($layanan->upd_status=='SUSPEND')
                <span class="info-box-number">Status Internet : <span class="badge badge-danger">Terisolir</span></span>
                @endif
                <span class="info-box-number">Status Internet : <span class="badge badge-success">Aktif</span></span>
              </div>
            </div>
              <!-- Modal -->
              <div class="modal fade" id="layanan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">RINCIAN LAYANAN</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <table class="table">
                      @foreach($details_layanan as $d)
                      <tr>
                        <td>No. Layanan</td>
                        <td>{{$d->nolayanan}}</td>
                      </tr>
                      <tr>
                        <td>Nama</td>
                        <td>{{$d->nolayanan}}</td>
                      </tr>
                      <tr>
                        <td>Kategori Layanan  </td>
                        <td>{{$d->tgl_daftar}}</td>
                      </tr>
                      <tr>
                        <td>Tanggal Daftar</td>
                        <td>{{$d->tgl_daftar}}</td>
                      </tr>
                      <tr>
                        <td>Tanggal Isolir</td>
                        <td>{{$d->upd_tgl_jatuh_tempo}}</td>
                      </tr>
                      <tr>
                        <td>Status Internet</td>
                        @if($d->upd_status=='SUSPEND')
                        <td><span class="badge badge-danger">Terisolir</span></td>
                        @endif
                        <td><span class="badge badge-success">Aktif</span></td>
                      </tr>
                      <tr>
                        <td>Alamat Pasang</td>
                        <td>{{$d->alamat_pasang}}</td>
                      </tr>
                      @endforeach
                    </table>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>


            
    </div>
  </div>
@endsection
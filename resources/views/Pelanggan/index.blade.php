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
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-danger">
                  </div>
                  <div class="h1 m-0">621</div>
                  <div class="text-muted mb-3">Pembayaran</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-sm-6">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-success">
                  </div>
                  <div class="h1 m-0">7</div>
                  <div class="text-muted mb-3">Tiket</div>
                </div>
              </div>
            </div>
          </div>

            <div class="row ">
                <div class="info-box shadow-none col-12 "style="border-radius: 30px; height:100%; background: linear-gradient(to right, #5d69be, #C89FEB);">
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
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right text-success">
                                        </div>
                                        <div class="h1 m-0"><img src="{{ asset('atlantis/assets/img/ceklis.png') }}" class=" bg-light"></div>
                                        <div class="text-light mb-3">Aktivasi</div>
                                      </div>
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
                            </tr>
                        </table>
                  </div>
                  </div>
                  </div>
            
    </div>
  </div>
@endsection
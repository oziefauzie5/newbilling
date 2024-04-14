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
                  <div class="text-muted mb-3">JOB</div>
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



            <section class="content mt-3">
                @foreach($data_pelanggan as $job)
                <div class="col">
                    @if($job->reg_progres==0)
                    <div class="card card_custom1"  data-toggle="modal" data-target="#exampleModal{{$job->reg_idpel}}">
                    @else
                    <div class="card card_custom1"  data-toggle="modal" data-target="#exampleModal1{{$job->reg_idpel}}">
                    @endif
                        <div class="card-body skew-shadow">
                            <div class="row">
                                <div class="col-8 pr-0">
                                    <h3 class="fw-bold mb-1">{{$job->input_nama}}</h3>
                                    <div class="text-small text-uppercase fw-bold op-8">{{$job->input_alamat_pasang}}</div>
                                </div>
                                <div class="col-4 pl-0 text-right">
                                    <h3 class="fw-bold mb-1">{{$job->input_tgl}}</h3>
                                    <div class="text-small text-uppercase fw-bold op-8">{{$job->paket_nama}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- modal lihat job --}}
                <div class="modal fade" id="exampleModal{{$job->reg_idpel}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Terima Pekerjaan</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                            <label for="barang" class=" col-form-label">DATA LANGGANAN</label>
                            <ul class="list-group">
                             <li class="list-group-item">No. Layanan   : {{$job->reg_nolayanan}}</li>
                             <li class="list-group-item">Nama   : {{$job->input_nama}}</li>
                             <li class="list-group-item">Alamat : {{$job->input_alamat_pasang}}</li>
                             <li class="list-group-item">Lokasi : <a href="{{$job->input_maps}}" target="_blank">Lihat Google Maps</a> </li>
                             <li class="list-group-item">Whatsapp : <a href="https://wa.me/62{{$job->input_hp}}?text=Assalamualaikum" target="_blank"> <i class="fas fa-phone"></i> &nbsp;&nbsp;Hubungi</a>
                            </li>
                            <li class="list-group-item">Sales : {{$job->input_sales}}</li>
                            <li class="list-group-item">Sub Sales : {{$job->input_subseles}}</li>
                            </ul>
                            <hr>
                            <label for="barang" class=" col-form-label">PEMBAYARAN</label>
                            <ul class="list-group">
                             <li class="list-group-item">Jenis Tagihan: {{$job->reg_jenis_tagihan}}</li>
                             <li class="list-group-item">Jumlah Tagihan   : Rp. {{ number_format($job->reg_harga) }}</li>
                            </ul>
                            <hr>
                            <label for="barang" class=" col-form-label">TEAM TEKNISI</label>
                    
                            <div class="content">
                            <form action="{{route('admin.teknisi.job')}}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                    
                                                  <select class="form-control mb-3" id="teknisi" name="teknisi" required>
                                                    <option value="">Pilih Teknisi</option>
                                                    @foreach ($data_user as $user)
                                                    <option value="{{$user->id.'|'.$user->name.'|'.$user->hp}}">{{$user->name}}</option>
                                                    @endforeach
                                                  </select>
                                                  <input type="text" class="form-control" id="sub_teknisi" name="sub_teknisi" required>
                                                  <input type="hidden" id="idpel" name="idpel" value="{{$job->reg_idpel}}">
                                                  <input type="hidden" id="job" name="job" value="PSB">
                                              </div>
                    
                            </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Terima Job</button>
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @endforeach
            </section>




            
    </div>
  </div>
@endsection
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
      <div class="user mt--5">
        <div class="avatar-sm float-left mr-2">
          <img src="{{ asset('storage/photo-user/'.Auth::user()->photo) }}" alt="..." class="avatar-img rounded-circle">
        </div>
        <div class="info">
          <span> 
              <span class="user-level text-light font-weight-bold">{{strtoupper(Auth::user()->name)}}</span><br>
              <h6 class="user-level text-light ">TEKNISI</h6>
          <div class="clearfix"></div>
        </span>
        </div>

      {{-- <div class="h5 mt--5 text-light font-weight-bold ">TEKNISI : {{strtoupper($nama)}}</div><br> --}}
      
        <div class="row mt-1">
          <div class="col-6 col-sm-6">
            <div class="card">
              <div class="card-body p-3 text-center">
                <div class="text-right text-success">
                </div>
                <div class="h5 m-0">Rp. {{number_format($sum_saldo)}}</div>
                <div class="text-muted mb-3">SALDO</div>
              </div>
            </div>
          </div>
          <div class="col-6 col-sm-6">
            <div class="card">
              <div class="card-body p-3 text-center">
                <div class="text-right text-success">
                </div>
                <div class="h5 m-0">Rp. {{number_format($sum_pencairan)}}</div>
                <div class="text-muted mb-3">PENCAIRAN</div>
              </div>
            </div>
          </div>
            
          </div>

        <section class="content">
                <div class="card card-primary card_custom1 p-3">
                    <div class="table-responsive">
                        <table>
                            <tr>
                                <th>
                                  <a href="{{ route('admin.teknisi.list_tiket') }}" class="card mb-2 card_custom1" style="width: 4rem;">
                                    <img src="{{ asset('atlantis/assets/img/ticket.png') }}" class="card-img-center p-2" alt="...">
                                    </a>
                                    <div class="text-light mb-3 text-center">Tiket</div>
                                </th>
                                <th>
                                  <a href="{{ route('admin.teknisi.list_aktivasi') }}" class="card mb-2 card_custom1" style="width: 4rem;">
                                    <img src="{{ asset('atlantis/assets/img/ceklis.png') }}" class="card-img-center p-2" alt="...">
                                    </a>
                                    <div class="text-light mb-3 text-center">Aktivasi</div>
                                  </th>
                                  <th>
                                  <a href="#" onclick="comingson()"class="card mb-2 card_custom1" style="width: 4rem;">
                                    <img src="{{ asset('atlantis/assets/img/selesai.png') }}" class="card-img-center p-2" alt="...">
                                    </a>
                                    <div class="text-light mb-3 text-center">Selesai</div>
                                  </th>
                                  <th>
                                  <a href="#" onclick="comingson()"class="card mb-2 card_custom1" style="width: 4rem;">
                                    <img src="{{ asset('atlantis/assets/img/komisi.png') }}" class="card-img-center p-2" alt="...">
                                    </a>
                                    <div class="text-light mb-3 text-center">Komisi</div>
                                  </th>
                                  <th>
                                  <a href="#" onclick="comingson()"class="card mb-2 card_custom1" style="width: 4rem;">
                                    <img src="{{ asset('atlantis/assets/img/cari.png') }}" class="card-img-center p-2" alt="...">
                                    </a>
                                    <div class="text-light mb-3 text-center">Cari</div>
                                  </th>
                                  <th>
                                  <a href="#" onclick="comingson()"class="card mb-2 card_custom1" style="width: 4rem;">
                                    <img src="{{ asset('atlantis/assets/img/values.png') }}" class="card-img-center p-2" alt="...">
                                    </a>
                                    <div class="text-light mb-3 text-center">Penilaian</div>
                                  </th>
                                  <th>
                                  <a href="{{ route('logout') }}" class="card mb-2 card_custom1" style="width: 4rem;">
                                    <img src="{{ asset('atlantis/assets/img/shutdown.png') }}" class="card-img-center p-2" alt="...">
                                    </a>
                                    <div class="text-light mb-3 text-center">Logout</div>
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
        </section>
        <div class="row mt-1">
            <div class="col-6 col-sm-6">
              <div class="card ">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-danger">
                  </div>
                  <div class="h1 m-0">{{$count_psb}}</div>
                  <div class="text-muted mb-3">JOB</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-sm-6">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-success">
                  </div>
                  <div class="h1 m-0">{{$count_tiket}}</div>
                  <div class="text-muted mb-3">Tiket</div>
                </div>
              </div>
            </div>
            
          </div>



            <section class="content mt-3">
                @foreach($data_tiket as $job_tiket)
                <div class="col">
                  @if($job_tiket->tiket_status != 'DONE'&& $job_tiket->tiket_status != 'PROGRES')
                    <div class="card card_custom1  @if($job_tiket->prioritas == 'CRITICAL') bg-danger @else bg-warning @endif"  data-toggle="modal" data-target="#exampleModal{{$job_tiket->tiket_id}}" id="update_tiket" >
                      <div class="card-body skew-shadow">
                          <div class="row">
                              <div class="col-12 pr-0">
                                  <h3 class="fw-bold mb-1">{{$job_tiket->input_nama}}</h3>
                                  <div class="text-small text-uppercase fw-bold op-8">{{$job_tiket->input_alamat_pasang}}</div>
                              </div>
                              <div class="col-12 pr-0">
                                  <div class="text-small text-uppercase fw-bold text-danger">{{date('d M Y H:m:s',strtotime($job_tiket->tgl_tiket))}}</div>
                              </div>
                              <div class="col-12 pl-0 text-right">
                                  <div class="text-small text-uppercase fw-bold op-8">TIKET GANGGUAN</div>
                              </div>
                          </div>
                      </div>
                  </div>
                    @else 
                    <div class="card card_custom1  @if($job_tiket->prioritas == 'CRITICAL') bg-danger @else bg-warning @endif"  data-toggle="modal" id="update_tiket" >
                      <div class="card-body skew-shadow">
                          <div class="row">
                              <div class="col-12 pr-0">
                                  <h3 class="fw-bold mb-1">{{$job_tiket->input_nama}}</h3>
                                  <div class="text-small text-uppercase fw-bold op-8">{{$job_tiket->input_alamat_pasang}}</div>
                              </div>
                              <div class="col-12 pr-0">
                                <div class="text-small text-uppercase fw-bold text-danger">{{date('d M Y H:m:s',strtotime($job_tiket->tgl_tiket))}}</div>
                            </div>
                            <div class="col-12 pl-0 text-right">
                                <div class="text-small text-uppercase fw-bold op-8">TIKET GANGGUAN</div>
                            </div>
                          </div>
                      </div>
                  </div>
                    @endif 
                </div>
                {{-- modal lihat job_tiket --}}
                <div class="modal fade"  id="exampleModal{{$job_tiket->tiket_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" id="exampleModal{{$job_tiket->tiket_id}}" >
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">TIKET GANGGUAN</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                            <label for="barang" class=" col-form-label">DATA LANGGANAN</label>
                            <ul class="list-group">
                              <li class="list-group-item">No. Layanan   : {{$job_tiket->reg_nolayanan}}</li>
                              <li class="list-group-item">Nama   : {{$job_tiket->input_nama}}</li>
                             <li class="list-group-item">Alamat : {{$job_tiket->input_alamat_pasang}}</li>
                             <li class="list-group-item"><a href="{{$job_tiket->input_maps}}" target="_blank"><button class="btn btn-primary btn-sm">Lihat Google Maps</button></a>&nbsp;&nbsp;<a href="https://wa.me/62{{$job_tiket->input_hp}}?text=Assalamualaikum" target="_blank"><button class="btn btn-primary btn-sm"><i class="fas fa-phone"></i> &nbsp;&nbsp;Whatsapp</button></a> </li>                             <label for="barang" class=" col-form-label">DESKRIPSI</label>
                            </ul>
                            <ul class="list-group">
                              <li class="list-group-item font-weight-bold">{{$job_tiket->tiket_judul}}  </li>
                              <li class="list-group-item">{{$job_tiket->tiket_deskripsi}}</li>
                            </ul>
                            <hr>
                            <label for="barang" class=" col-form-label">TEAM TEKNISI</label>
                    
                            <div class="content">
                            <form action="{{route('admin.teknisi.job_tiket')}}" method="POST">
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
                                                  <input type="hidden" id="idpel" name="idpel" value="{{$job_tiket->reg_idpel}}">
                                                  <input type="hidden" id="tiket_id" name="tiket_id" value="{{$job_tiket->tiket_id}}">
                                                  <input type="hidden" id="job" name="job" value="TIKET">
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
                                  <h3 class="fw-bold mb-1">{{date('d M Y',strtotime($job->input_tgl))}}</h3>
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
                             <li class="list-group-item"><a href="{{$job->input_maps}}" target="_blank"><button class="btn btn-primary btn-sm">Lihat Google Maps</button></a>&nbsp;&nbsp;<a href="https://wa.me/62{{$job->input_hp}}?text=Assalamualaikum" target="_blank"><button class="btn btn-primary btn-sm"><i class="fas fa-phone"></i> &nbsp;&nbsp;Whatsapp</button></a> </li>
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
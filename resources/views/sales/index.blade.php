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
            <a href="{{route('admin.sales.list_registrasi')}}" class="col-6 col-sm-6">
              <div class="card ">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-danger">
                  </div>
                  <div class="h1 m-0">{{$input_data}}</div>
                  <div class="text-muted mb-3">INPUT DATA</div>
                </div>
              </div>
            </a>
            <div class="col-6 col-sm-6">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-success">
                  </div>
                  <div class="h1 m-0">{{$registrasi}}</div>
                  <div class="text-muted mb-3">REGISTRASI</div>
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
                                    <div class="card-body p-3 text-center" data-toggle="modal" data-target="#addRowModal">
                                        <div class="text-right text-success">
                                        </div>
                                        <div class="h1 m-0"><img src="{{ asset('atlantis/assets/img/add_user.png') }}" class=" bg-light"></div>
                                        <div class="text-light mb-3">Input Data</div>
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
                                    <a href="{{ route('logout') }}" class="card-body p-3 text-center">
                                        <div class="text-right text-success">
                                        </div>
                                        <div class="h1 m-0"><img src="{{ asset('atlantis/assets/img/shutdown.png') }}" class=" bg-light"></div>
                                        <div class="text-light mb-3">Logout</div>
                                      </a>
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
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
                  <form action="{{route('admin.psb.store')}}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Nama Lengkap</label>
                          <input id="input_nama" type="text" class="form-control" name="input_nama"placeholder="Nama Lengkap" value="{{ Session::get('input_nama') }}" required>
                          <input id="id" type="hidden" class="form-control" name="id"value="{{ rand(10000,99999) }}" required>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>No Identitas</label>
                          <input id="input_ktp" type="text" class="form-control" value="{{ Session::get('input_ktp') }}" name="input_ktp" onkeyup="validasiKtp()" placeholder="No. Identitas" required>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>No Hp</label>
                          <input id="input_hp" type="text" class="form-control" value="{{ Session::get('input_hp') }}" name="input_hp" placeholder="No. Whatsapp" required>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Email</label>
                          <input id="input_email" type="text" class="form-control" value="{{ Session::get('input_email') }}" name="input_email" placeholder="Email">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Alamat Domisili</label>
                          <input id="input_alamat_ktp" type="text" class="form-control" value="{{ Session::get('input_alamat_ktp') }}" name="input_alamat_ktp" placeholder="Alamat KTP">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Alamat Pasang</label>
                          <input id="input_alamat_pasang" type="text" class="form-control" value="{{ Session::get('input_alamat_pasang') }}" name="input_alamat_pasang" placeholder="Alamat Pemasangan">
                        </div>
                      </div>
                          <input  type="hidden" class="form-control" value="{{ $admin_user->id }}" name="input" readonly>
                          <input  type="hidden" class="form-control" value="{{ $id_sales }}" name="input_sales" readonly>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Sub Sales </label>
                          <input id="input_subseles" type="text" class="form-control" value="{{ Session::get('input_subseles') }}" name="input_subseles" placeholder="Sub Sales">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Share Location</label>
                          <input id="input_maps" type="text" class="form-control" value="{{ Session::get('input_maps') }}" name="input_maps" placeholder="Share Location" required>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Keterangan</label>
                          <textarea name="input_keterangan" class="form-control" id="input_keterangan" cols="10">
Paket :
Keterangan :
</textarea>
<span class="text-bold text-danger" style="font-size:12px">Contoh = Keterangan : Pemasangan Perlu tiang</span>
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
        </section>



            <section class="content mt-3">
                @foreach($data_pemasangan as $psb)
                <div class="col">
                    <div class="card card_custom1"  data-toggle="modal" data-target="#exampleModal{{$psb->id}}">
                        <div class="card-body skew-shadow">
                            <div class="row">
                                <div class="col-8 pr-0">
                                    <h3 class="fw-bold mb-1">{{$psb->input_nama}}</h3>
                                    <div class="text-small text-uppercase fw-bold op-8">{{$psb->input_alamat_pasang}}</div>
                                    @if($psb->input_status == 0)
                                    <div class="text-small text-uppercase fw-bold op-8">INPUT DATA</div>
                                    @elseif($psb->input_status == 1)
                                    <div class="text-small text-uppercase fw-bold op-8">REGISTRASI</div>
                                    @endif

                                </div>
                                <div class="col-4 pl-0 text-right">
                                    <h3 class="fw-bold mb-1">{{$psb->input_tgl}}</h3>
                                    <div class="text-small text-uppercase fw-bold op-8">{{$psb->paket_nama}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </section>




            
    </div>
  </div>
@endsection
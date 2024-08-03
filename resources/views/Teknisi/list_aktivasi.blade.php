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

            <section class="content mt-3">
                @foreach($data_pelanggan as $job)
                <div class="col">
                    {{-- @if($job->reg_progres==1) --}}
                    <div class="card card_custom1"  data-toggle="modal" data-target="#exampleModal{{$job->reg_idpel}}">
                    {{-- @else
                    <div class="card card_custom1"  data-toggle="modal" data-target="#exampleModal1{{$job->reg_idpel}}">
                    @endif --}}
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
                        <div class="modal-body">
                            <label for="barang" class=" col-form-label">DATA LANGGANAN</label>
                            <ul class="list-group">
                             <li class="list-group-item">No. Layanan   : {{$job->reg_nolayanan}}</li>
                             <li class="list-group-item">Nama   : {{$job->input_nama}}</li>
                             <li class="list-group-item">Alamat : {{$job->input_alamat_pasang}}</li>
                            <li class="list-group-item">Sub Sales : {{$job->input_subseles}}</li>
                            </ul>
                          <hr>
                          <label for="barang" class=" col-form-label">AKTIVASI</label>
                          <div class="form-row mb-2">
                              <div class="col-9">
                              <input type="text" class="form-control" value="{{$job->reg_username}}" id="myInput1">
                              </div>
                              <div class="col-3">
                              <button type="button" class="form-control" onclick="copy1()">Copy</button>
                              </div>
                          </div>
                          <div class="form-row mb-2">
                              <div class="col-9">
                              <input type="text" class="form-control" value="{{$job->reg_password}}" id="myInput2">
                              </div>
                              <div class="col-3">
                              <button type="button" class="form-control" onclick="copy2()">Copy</button>
                              </div>
                          </div>
                            <hr>
                            <label for="barang" class=" col-form-label">PEMBAYARAN</label>
                            <ul class="list-group">
                             <li class="list-group-item">Jenis Tagihan: {{$job->reg_jenis_tagihan}}</li>
                             <li class="list-group-item">Jumlah Tagihan   : Rp. {{ number_format($job->reg_harga+$job->reg_ppn+$job->reg_dana_kas+$job->reg_dana_kerjasama+$job->reg_kode_unik) }}</li>
                            </ul>
                    
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <a href="{{route('admin.teknisi.aktivasi',['id'=> $job->reg_idpel])}}" class="btn btn-secondary" >Lanjutkan</a>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
            </section>



            
    </div>
  </div>
  <script>
function copy1() {
  var copyText1 = document.getElementById("myInput1");
  copyText1.select();
  copyText1.setSelectionRange(0, 99999); // For mobile devices
  navigator.clipboard.writeText(copyText1.value);
  
    }
    function copy2() {
      var copyText2 = document.getElementById("myInput2");
            copyText2.select();
      copyText2.setSelectionRange(0, 99999); // For mobile devices
            navigator.clipboard.writeText(copyText2.value);

    }
    </script>
@endsection
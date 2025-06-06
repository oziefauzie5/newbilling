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
      {{-- <div class="h5 mt--5 text-light font-weight-bold ">MITRA : {{$nama}}</div><br> --}}
      <div class="user mt--5">
        <div class="avatar-sm float-left mr-2">
          <img src="@if(Auth::user()->photo) {{ asset('storage/photo-user/'.Auth::user()->photo) }} @else {{ asset('atlantis/assets/img/user.png') }}@endif" alt=".." class="avatar-img rounded-circle"> 
        </div>
        <div class="info">
          <span> 
              <span class="user-level text-light font-weight-bold">{{strtoupper(Auth::user()->name)}}</span><br>
              <h6 class="user-level text-light ">{{$role}}</h6>
          <div class="clearfix"></div>
        </span>
        </div>
      <div class="row mt--1">
            <a href="{{route('admin.sales.pelanggan',['q='])}}" class="col-4">
              <div class="card ">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-danger">
                  </div>
                  <div class="h5 m-0">{{$pelanggan_aktif}}</div>
                  <div class="h5 text-info ">Pel. Aktif</div>
                </div>
              </div>
            </a>
            <a  href="{{route('admin.sales.pelanggan',['q=FREE'])}}" class="col-4">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-success">
                  </div>
                  <div class="h5 m-0">{{$pelanggan_bulan_ini}}</div>
                  <div class="h5 text-warning">Pel. bulan ini</div>
                </div>
              </div>
            </a>
            <a href="{{route('admin.sales.pelanggan',['putus=5'])}}" class="col-4">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-success">
                  </div>
                  <div class="h5 m-0">{{$pelanggan_putus}}</div>
                  <div class="h5 text-danger">Pel. Putus</div>
                </div>
              </div>
            </a>
          </div>
      <div class="row mt--1">
            <a  href="{{route('admin.sales.pelanggan',['q=FREE'])}}" class="col-6">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-success">
                  </div>
                  <div class="h5 m-0">{{$pelanggan_free}}</div>
                  <div class="h5 text-warning">Pel. Free</div>
                </div>
              </div>
            </a>
            <a href="{{route('admin.sales.pelanggan',['putus=5'])}}" class="col-6">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-success">
                  </div>
                  <div class="h5 m-0">{{$total_pelanggan}}</div>
                  <div class="h5 text-danger">Total Pelanggan</div>
                </div>
              </div>
            </a>
          </div>

            <div class="row mt--1">
            <a href="{{route('admin.sales.pelanggan',['q='])}}" class="col-6">
              <div class="card ">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-danger">
                  </div>
                  <div class="h5 m-0">{{$pelanggan_lunas}}</div>
                  <div class="h5 text-info ">Terbayar</div>
                </div>
              </div>
            </a>
            <a  href="{{route('admin.sales.pelanggan',['q=FREE'])}}" class="col-6">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-success">
                  </div>
                  <div class="h5 m-0">{{$pelanggan_belum_lunas}}</div>
                  <div class="h5 text-warning">Belum Terbayar</div>
                </div>
              </div>
            </a>
          </div>
            <div class="row mt--1">
            <a href="{{route('admin.sales.pelanggan',['putus=5'])}}" class="col-12">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-success">
                  </div>
                  <div class="h5 m-0">{{$komisi}}</div>
                  <div class="h5 text-danger">Total Komisi</div>
                </div>
              </div>
            </a>
          </div>
        <div class="col">
          <div class="card ">
            <div class="card-body p-3 text-center">
              <form > 
            <div class="input-group mb-3">
              <input type="text" class="form-control" name="q" placeholder="Nama, No Layanan, No Invoice" >
              <div class="input-group-append">
                <button class="btn btn-outline-primary" type="submit" id="button-addon2">Cari</button>
              </div>
            </div>
          </form>
            </div>
          </div>
        </div>
          @foreach($data_pelanggan as $list)
          <div class="col">
              <div class="card card_custom1"  data-toggle="modal" data-target="#exampleModal{{$list->reg_idpel}}">
                  <div class="card-body skew-shadow">
                      <div class="row">
                          <div class="col-12 pr-0">
                              <h3 class="fw-bold mb-1">{{$list->input_nama}}</h3>
                          </div>
                          
                          <div class="col-12 pr-0">
                              <div class="text-small text-uppercase fw-bold op-8 ">{{$list->input_alamat_pasang}}</div>
                          </div>
                          <div class="col-8 pr-0">
                            <h6 class="text-uppercase text-info fw-bold op-8 ">Tanggal Pasang</h6>
                          </div>
                          <div class="col-4 pl-0 text-right">
                            <h6 class="text-uppercase text-info fw-bold op-8 ">{{date('d-m-Y',strtotime($list->reg_tgl_pasang))}}</h6>
                          </div>
                          <div class="col-8 pr-0">
                            <div class="text-uppercase fw-bold op-8 ">{{$list->reg_jenis_tagihan}}</div>
                          </div>
                          <div class="col-4 pl-0 text-right">
                            <div class="text-primary text-uppercase fw-bold op-8 ">Rp. {{number_format($list->reg_harga+$list->reg_kode_unik+$list->reg_ppn+$list->reg_dana_kas+$list->reg_dana_kerja_sama)}}</div>
                          </div>
                          <div class="col-4 pr-0">
                            <h6 class="text-uppercase fw-bold op-8 ">Status</h6>
                          </div>
                          <div class="col-8 pl-0 text-right">
                            @if($list->reg_progres == 5)
                            <h6 class="text-uppercase fw-bold op-8 ">Berlangganan</h6>
                            @elseif($list->reg_progres = 90)
                            <h6 class="text-uppercase fw-bold op-8 ">Putus Sementara</h6>
                            @elseif($list->reg_progres = 100)
                            <h6 class="text-uppercase fw-bold op-8 ">Putus Berlangganan</h6>
                            @endif
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          
          {{-- modal lihat job --}}
          <div class="modal fade" id="exampleModal{{$list->reg_idpel}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-body">
                      <ul class="list-group">
                       <li class="list-group-item">No. Layanan   : {{$list->reg_nolayanan}}</li>
                       <li class="list-group-item">Nama   : {{$list->input_nama}}</li>
                       <li class="list-group-item">Alamat : {{$list->input_alamat_pasang}}</li>
                       <li class="list-group-item">Jumlah Tagihan   : Rp. {{ number_format($list->reg_harga) }}</li>
                       <li class="list-group-item">Jatuh Tempo   : {{ date('d-m-Y',strtotime($list->reg_tgl_jatuh_tempo)) }}</li>
                      </ul>
                      <div class="row">
                        <div class="col">
                          <a href="https://wa.me/62{{$list->input_hp}}?text=Assalamualaikum" target="_blank">  <button class="btn btn-block btn-primary"><i class="fas fa-phone"></i>&nbsp;&nbsp;HUBUNGI</button></a>&nbsp;&nbsp;
                        </div>
                        <div class="col">
                          <a href="{{$list->input_maps}}"> <button class="btn btn-block btn-primary"><i class="fas fa-phone"></i>&nbsp;&nbsp;LOKASI</button></a>
                        </div>
                        <div class="col mt-3">
                          <a href="{{route('admin.biller.paymentbytagihan',['inv_id'=>$list->reg_nolayanan])}}"><button class="btn btn-block btn-primary">PROSES PEMBAYARAN</button></a>
                        </div>
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
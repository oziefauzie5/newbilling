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
                  <div class="h1 m-0 text-warning">{{$inv_count_suspend}}</div>
                  <div class="text-muted mb-3">SUSPEND</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-sm-6">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-success">
                  </div>
                  <div class="h1 m-0 text-danger">{{$inv_count_isolir}}</div>
                  <div class="text-muted mb-3">ISOLIR</div>
                </div>
              </div>
            </div>
          </div>
        <div class="row">
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
          </div>

            <section class="content mt-3">
                @foreach($data_invoice as $list)
                <div class="col">
                    {{-- @if($list->reg_progres==1) --}}
                    <div class="card card_custom1"  data-toggle="modal" data-target="#exampleModal{{$list->inv_id}}">
                    {{-- @else
                    <div class="card card_custom1"  data-toggle="modal" data-target="#exampleModal1{{$list->inv_id}}">
                    @endif --}}
                        <div class="card-body skew-shadow">
                            <div class="row">
                                <div class="col-8 pr-0">
                                    <h3 class="fw-bold mb-1">{{$list->input_nama}}</h3>
                                    <!-- <div class="text-small text-uppercase fw-bold op-8 ">{{$list->input_alamat_pasang}}</div> -->
                                </div>
                                <div class="col-4 pl-0 text-right">
                                  <div class="text-primary text-uppercase fw-bold op-8 ">Rp. {{number_format($list->inv_total)}}</div>
                                  <!-- <h6 class="fw-bold mb-1">{{date('d-m-Y',strtotime($list->inv_tgl_jatuh_tempo))}}</h6> -->
                                </div>
                                <div class="col-12 pr-0">
                                    <div class="text-small text-uppercase fw-bold op-8 ">{{$list->input_alamat_pasang}}</div>
                                </div>
                                <div class="col-8 pr-0">
                                  <div class="text-danger text-uppercase fw-bold op-8 ">Jatuh Tempo</div>
                                </div>
                                <div class="col-4 pl-0 text-right">
                                  <div class="text-danger text-uppercase fw-bold op-8 ">{{date('d-m-Y',strtotime($list->inv_tgl_jatuh_tempo))}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- modal lihat job --}}
                <div class="modal fade" id="exampleModal{{$list->inv_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-body">
                            <label for="barang" class=" col-form-label">DATA LANGGANAN</label>
                            <ul class="list-group">
                             <li class="list-group-item">No. Layanan   : {{$list->reg_nolayanan}}</li>
                             <li class="list-group-item">Nama   : {{$list->input_nama}}</li>
                             <li class="list-group-item">Alamat : {{$list->input_alamat_pasang}}</li>
                             <li class="list-group-item"><a href="https://wa.me/62{{$list->input_hp}}?text=Assalamualaikum" target="_blank">  <button class="btn btn-block btn-primary"><i class="fas fa-phone"></i>&nbsp;&nbsp;HUBUNGI</button></a>&nbsp;&nbsp;<a href="{{$list->input_maps}}"> <button class="btn btn-block btn-primary"><i class="fas fa-phone"></i>&nbsp;&nbsp;LOKASI</button></a>
                             </li>
                            </ul>
                          <hr>
                            <label for="barang" class=" col-form-label">PEMBAYARAN</label>
                            <ul class="list-group">
                             <li class="list-group-item">Jenis Tagihan: {{$list->reg_jenis_tagihan}}</li>
                             <li class="list-group-item">Jumlah Tagihan   : Rp. {{ number_format($list->reg_harga) }}</li>
                             <li class="list-group-item">Jatuh Tempo   : {{ date('d-m-Y',strtotime($list->reg_tgl_jatuh_tempo)) }}</li>
                             <li class="list-group-item">Tanggal Isolir   : {{ date('d-m-Y',strtotime($list->inv_tgl_isolir))  }}</li>
                             <li class="list-group-item"><a href="{{route('admin.biller.bayar',['id'=>$list->inv_id])}}"><button class="btn btn-block btn-primary">BAYAR</button></a></li>
                            </ul>
                    
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
            </section>



            
    </div>
  </div>

@endsection
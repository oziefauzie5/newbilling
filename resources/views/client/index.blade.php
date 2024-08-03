@extends('layout.pelanggan')
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
          @if($details)
          <a style="text-decoration:none" href="{{route('client.details')}}" class="col">
            @else
            <a style="text-decoration:none" href="#" class="col">
          @endif
            <div class="card card-stats card-round">
              <div class="card-body ">
                <h4>LAYANAN</h4><hr>
                <div class="row align-items-center">
                  <div class="col-icon">
                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                      <i class="fas fa-rss"></i>
                    </div>
                  </div>
                  <div class="col col-stats ml-3 ml-sm-0">
                    <div class="numbers">
                      <h4 class="card-title">{{$nama}}</h4>
                      @if($layanan)
                      @if($layanan->inv_status=='SUSPEND')
                      <p class="card-category">Status <span class="badge badge-warning">Telah Jatuh Tempo</span></p>
                      @elseif($layanan->inv_status=='UNPAID')
                      <p class="card-category">Status <span class="badge badge-warning">Belum Terbayar</span></p>
                      @elseif($layanan->inv_status=='ISOLIR')
                      <p class="card-category">Status <span class="badge badge-danger">Terisolir</span></p>
                      @elseif($layanan->inv_status=='PAID')
                      <p class="card-category">Status <span class="badge badge-success">Terbayar</span></p>
                      @else
                      <p class="card-category">Status <span class="badge badge-success">Tidak ada tagihan</span></p>
                      @endif
                      @else
                      <p class="card-category">Status <span class="badge badge-success">Tidak ada tagihan</span></p>
                      @endif
                    </div>
                  </div>
                  <div class="col-4 pl-0 text-right">
                    <i class="fas fa-chevron-right"></i>
                </div>
                </div>
              </div>
            </div>
          </a>
          </div>

          <section class="content mt-3">
            @foreach($tagihan as $tg)
            <div class="col">
                <div class="card card_custom1">
                    <div class="card-body skew-shadow">
                        <div class="row">
                            <div class="col-8 pr-0">
                              <h2 class="fw-bold mb-1">Rp. {{number_format($tg->inv_total)}}</h2>
                              <h4 class="fw-bold mb-1">Invoice {{$tg->inv_id}}</h4>
                              @if($tg->inv_status != 'PAID')
                                <div class="text-danger text-uppercase fw-bold op-8">Jatuh Tempo  {{ date('d-m-Y', strtotime($tg->inv_tgl_jatuh_tempo)) }}</div>
                                @else
                                <div class="text-success text-uppercase fw-bold op-8">Tanggal Bayar  {{ date('d-m-Y', strtotime($tg->inv_tgl_bayar)) }}</div>
                                @endif
                            </div>
                            <div class="col-4 pl-0 text-right">
                              @if($tg->inv_status != 'PAID')
                              <a href="{{route('client.tagihan',['inv'=>$tg->inv_id])}}" class="fw-bold op-8"><button class="btn btn-primary">Bayar</button></a>
                              @else
                                    <a href="#" class="fw-bold op-8"><button class="btn btn-success">Lunas</button></a>
                                    @endif
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
              @endforeach
            </section>
  </div>
@endsection
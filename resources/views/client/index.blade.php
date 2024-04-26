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
          <a style="text-decoration:none" href="{{route('client.details')}}" class="col">
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
                      @if($layanan->inv_status=='SUSPEND')
                      <p class="card-category">Status <span class="badge badge-danger">Terisolir</span></p>
                      @else
                      <p class="card-category">Status <span class="badge badge-success">Aktif</span></p>
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
            {{-- <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-body p-3">
                  <div class="h4 m-0"><span >LAYANAN</span></div><hr>
                  <div class="text-muted mb-3">97237598239</div>
                  @if($layanan->inv_status=='SUSPEND')
                  <div class="h1 m-0"><span class="badge badge-danger">Terisolir</span></div>
                  @endif
                  <div class="h1 m-0"><span class="badge badge-success">Aktif</span></div>
                </div>
              </div>
            </div> --}}
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
                              @if($tg->inv_status == 'UNPAID')
                                <div class="text-danger text-uppercase fw-bold op-8">Jatuh Tempo  {{ date('d-m-Y', strtotime($tg->inv_tgl_jatuh_tempo)) }}</div>
                                @else
                                <div class="text-success text-uppercase fw-bold op-8">Tanggal Bayar  {{ date('d-m-Y', strtotime($tg->inv_tgl_bayar)) }}</div>
                                @endif
                            </div>
                            <div class="col-4 pl-0 text-right">
                              @if($tg->inv_status == 'UNPAID')
                              <a href="{{route('client.tagihan',['inv'=>$tg->inv_id])}}" class="fw-bold op-8"><button class="btn btn-primary">Bayar</button></a>
                              @else
                                    <a href="{{route('client.tagihan',['inv'=>$tg->inv_id])}}" class="fw-bold op-8"><button class="btn btn-success">Lunas</button></a>
                                    @endif
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
              @endforeach
            </section>




{{-- 
        <div class="info-box shadow-sm" style="border-radius: 20px;" data-toggle="modal" data-target="#layanan">
            <span class="info-box-icon bg-success" style="border-radius: 20px;" ><i class="far fa-flag"></i></span>
              <div class="info-box-content">
                <h5 class="info-box-text">{{$layanan->nama}}</h5>
                @if($layanan->upd_status=='SUSPEND')
                <span class="info-box-number">Status Internet : <span class="badge badge-danger">Terisolir</span></span>
                @endif
                <span class="info-box-number">Status Internet : <span class="badge badge-success">Aktif</span></span>
              </div>
            </div> --}}
              <!-- Modal -->
              {{-- <div class="modal fade" id="layanan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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


            
    </div> --}}
  </div>
@endsection
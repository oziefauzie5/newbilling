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
                  <div class="h3 m-0">Rp {{ number_format($biaya_adm) }}</div>
                  <div class="h5 text-muted mb-3">PENDAPATAN</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-sm-6">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-success">
                  </div>
                  <div class="h3 m-0">Rp {{ number_format($saldo) }}</div>
                  <div class="h5 text-muted mb-3">SALDO</div>
                </div>
              </div>
            </div>
          </div>

          <section class="content mt-3">
                <div class="card card-primary card_custom1 p-3">
                    <div class="table-responsive">
                        <table>
                            <tr>
                              <td>
                                <a href="{{ route('admin.biller.payment') }}" class="card mb-2 card_custom1" style="width: 4rem;">
                                <img src="{{ asset('atlantis/assets/img/payment.png') }}" class="card-img-center p-3" alt="...">
                                </a>
                                <div class="text-light mb-3 text-center">Payment</div>
                              </td>
                              <td>
                                <a href="#" onclick="comingson()" class="card mb-2 card_custom1" style="width: 4rem;">
                                <img src="{{ asset('atlantis/assets/img/top-up.png') }}" class="card-img-center p-3" alt="...">
                                </a>
                                <div class="text-light mb-3 text-center">TopUp</div>
                              </td>
                              <td>
                                <a href="{{route('admin.biller.mutasi') }}" class="card mb-2 card_custom1" style="width: 4rem;">
                                <img src="{{ asset('atlantis/assets/img/mutasi.png') }}" class="card-img-center p-3" alt="...">
                                </a>
                                <div class="text-light mb-3 text-center">Mutasi</div>
                              </td>
                              @role('KOLEKTOR')
                              <td>
                                <a href="#" onclick="comingson()" class="card mb-2 card_custom1" style="width: 4rem;">
                                <img src="{{ asset('atlantis/assets/img/transaksi.png') }}" class="card-img-center p-3" alt="...">
                                </a>
                                <div class="text-light mb-3 text-center">Tagihan</div>
                              </td>
                              @endrole
                              <td>
                                <a href="{{route('logout')}}" class="card mb-2 card_custom1" style="width: 4rem;">
                                <img src="{{ asset('atlantis/assets/img/shutdown.png') }}" class="card-img-center p-3" alt="...">
                                </a>
                                <div class="text-light mb-3 text-center">Logout</div>
                              </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </section>


    </div>
  </div>
@endsection
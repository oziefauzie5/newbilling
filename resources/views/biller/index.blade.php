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
      <div class="h5 mt--5 text-light font-weight-bold ">COUNTER : {{$nama}}</div><br>
      <div class="row mt--1">
            <div class="col-6 col-sm-6">
              <div class="card ">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-danger">
                  </div>
                  <div class="h5 m-0">Rp {{ number_format($biaya_adm) }}</div>
                  <div class="h5 ">PENDAPATAN</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-sm-6">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-success">
                  </div>
                  <div class="h5 m-0">Rp {{ number_format($saldo) }}</div>
                  <div class="h5 ">SALDO</div>
                </div>
              </div>
            </div>
          </div>

          <section class="content">
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
                                <img src="{{ asset('atlantis/assets/img/tagihan.png') }}" class="card-img-center p-3" alt="...">
                                </a>
                                <div class="text-light mb-3 text-center">Tagihan</div>
                              </td>
                              @endrole
                              <td>
                                <div class="card mb-2 card_custom1 " data-toggle="modal" data-target="#exampleModal" style="width: 4rem;">
                                <img src="{{ asset('atlantis/assets/img/transaksi.png') }}" class="card-img-center p-3" alt="...">
                                </div>
                                <div class="text-light mb-3 text-center">Transaksi</div>
                              </td>
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
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">TRANSAKSI</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="table-responsive">
                              <table id="get_invoice" class=" table table-striped table-hover text-nowrap text-dark" >
                                <thead>
                                  <tr>
                                    <th>INVOICE</th>
                                    <th>NO LAYANAN</th>
                                    <th>TANGGAL BAYAR</th>
                                    <th>NAMA</th>
                                    <th>TOTAL</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach ($data as $d)
                                  <tr>
                                        <td  class="href" data-id="{{$d->inv_id}}">{{$d->inv_id}}</td>
                                        <td  class="href" data-id="{{$d->inv_id}}">{{$d->inv_nolayanan}}</td>
                                        <td  class="href" data-id="{{$d->inv_id}}">{{ date('d-m-Y', strtotime($d->inv_tgl_bayar))}}</td>
                                        <td  class="href" data-id="{{$d->inv_id}}">{{$d->inv_nama}}</td>
                                        <td  class="href" data-id="{{$d->inv_id}}">{{number_format($d->inv_total)}}</td>
                                      </tr>
                                      @endforeach
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    {{-- #end-modal --}}
            </div>
        </section>


    </div>
  </div>
@endsection
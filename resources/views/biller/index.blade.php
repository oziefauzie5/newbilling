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
      <div class="h5 mt--5 text-light font-weight-bold ">MITRA : {{$nama}}</div><br>
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
                                <img src="{{ asset('atlantis/assets/img/payment.png') }}" class="card-img-center p-2" alt="...">
                                </a>
                                <div class="text-light mb-3 text-center">Payment</div>
                              </td>
                              <td>
                                <a href="{{ route('admin.biller.sales') }}" class="card mb-2 card_custom1" style="width: 4rem;">
                                <img src="{{ asset('atlantis/assets/img/add_users.png') }}" class="card-img-center p-2" alt="...">
                                </a>
                                <div class="text-light mb-3 text-center">Sales</div>
                              </td>
                              <td>
                                <a href="#" onclick="comingson()" class="card mb-2 card_custom1" style="width: 4rem;">
                                <img src="{{ asset('atlantis/assets/img/top-up.png') }}" class="card-img-center p-2" alt="...">
                                </a>
                                <div class="text-light mb-3 text-center">TopUp</div>
                              </td>
                              <td>
                                <a href="{{route('admin.biller.mutasi') }}" class="card mb-2 card_custom1" style="width: 4rem;">
                                <img src="{{ asset('atlantis/assets/img/mutasi.png') }}" class="card-img-center p-2" alt="...">
                                </a>
                                <div class="text-light mb-3 text-center">Mutasi</div>
                              </td>
                              @role('KOLEKTOR')
                              <td>
                                <a href="{{route('admin.biller.list_tagihan') }}" class="card mb-2 card_custom1" style="width: 4rem;">
                                <img src="{{ asset('atlantis/assets/img/tagihan.png') }}" class="card-img-center p-2" alt="...">
                                </a>
                                <div class="text-light mb-3 text-center">Tagihan</div>
                              </td>
                              @endrole
                              <td>
                                <div class="card mb-2 card_custom1 " data-toggle="modal" data-target="#exampleModal" style="width: 4rem;">
                                <img src="{{ asset('atlantis/assets/img/transaksi.png') }}" class="card-img-center p-2" alt="...">
                                </div>
                                <div class="text-light mb-3 text-center">Transaksi</div>
                              </td>
                              <td>
                                <a href="{{route('logout')}}" class="card mb-2 card_custom1" style="width: 4rem;">
                                <img src="{{ asset('atlantis/assets/img/shutdown.png') }}" class="card-img-center p-2" alt="...">
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
        @role('KOLEKTOR')
        <section class="content mt-3">
          <div class="col">
            <div class="card card_custom1">
                <div class="card-body skew-shadow">
                    <div class="row">
                      <div class="col-12 pl-0 text-center">
                        <div class="text-danger text-uppercase fw-bold op-8 ">JUMLAH PENGAMBILAN PERANGKAT</div>
                      </div>
                        <div class="col-12 pr-0">
                            <h1 class="fw-bold op-8 text-center ">{{$count_pengambilan_perangkat}} Pelanggan</h1>
                        </div>
                    </div>
                </div>
            </div>
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
          @foreach($pengambilan_perangkat as $list)
          <div class="col">
              <div class="card card_custom1 bg-warning"  data-toggle="modal" data-target="#exampleModal{{$list->reg_idpel}}">
                  <div class="card-body skew-shadow">
                      <div class="row">
                        <div class="col-12 pl-0 text-center">
                          <div class=" text-uppercase fw-bold op-8 ">PENGAMBILAN PERANGKAT</div><hr>
                        </div>
                          <div class="col-8 pr-0">
                              <h3 class="fw-bold mb-1">{{$list->input_nama}}</h3>
                          </div>
                          <div class="col-4 pl-0 text-right">
                            <div class="text-primary text-uppercase fw-bold op-8 ">Rp. {{number_format($list->reg_harga+$list->reg_kode_unik+$list->reg_ppn+$list->reg_dana_kas+$list->reg_dana_kerja_sama)}}</div>
                          </div>
                          <div class="col-12 pr-0">
                              <div class="text-small text-uppercase fw-bold op-8 ">{{$list->input_alamat_pasang}}</div>
                          </div>
                          <div class="col-8 pr-0">
                            <div class="text-danger text-uppercase fw-bold op-8 ">Jatuh Tempo</div>
                          </div>
                          <div class="col-4 pl-0 text-right">
                            <div class="text-danger text-uppercase fw-bold op-8 ">{{date('d-m-Y',strtotime($list->reg_tgl_jatuh_tempo))}}</div>
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
                        <div class="col mt-3">
                          <button class="btn btn-block btn-primary" data-toggle="modal" data-target="#putus_sementara{{$list->reg_idpel}}">AMBIL ALAT</button>
                         
                        </div>
                      </div>
                      
                  </div>
                </div>
              </div>
            </div>

             <!-- Modal -->
             <div class="modal fade" id="putus_sementara{{$list->reg_idpel}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">STOP BERLANGGANAN</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="{{route('admin.biller.biller_putus_berlanggan',['idpel'=>$list->reg_idpel])}}" method="POST">
                      @csrf
                      @method('PUT')

                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="tiket_deskripsi">Alasan Putus</label>
                          <textarea class="form-control" name="reg_catatan" rows="5"></textarea>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Mac Address ONT</label>
                          <input type="text" class="form-control" name="reg_mac"  step="00.01" maxlength="17" minlength="17" value="">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Status</label>
                          <select name="status" class="form-control" required>
                            <option value="PUTUS LANGGANAN">PUTUS LANGGANAN</option>
                            <option value="PUTUS SEMENTARA">PUTUS SEMENTARA</option>
                          </select>
                        </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                      <button type="submit" class="btn btn-primary">SIMPAN</button>
                    </form>
                  </div>
                </div>
              </div>
            </div> 
                  {{-- END MODAL --}}
          @endforeach
      </section>
      @endrole


    </div>
  </div>
@endsection
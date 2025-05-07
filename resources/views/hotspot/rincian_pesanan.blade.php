@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
  <div class="card">
    <div class="invoice p-3">
            <div class="row m-3">
              <div class="col-sm-4">
                <address>
                  <b><strong>{{$rincian->outlet_pemilik}}</strong></b><br>
                  {{$rincian->outlet_alamat}}<br>
                  {{$rincian->outlet_hp}}<br>
                  {{$rincian->outlet_nama}}
                </address>
              </div>
              <div class="col-sm-4">

              </div>
              <div class="col-sm-4">
                <b >No Pesanan : <b>{{$rincian->pesanan_id}}</b></b><br>

                <!-- <b>Invoice : INV-{{$rincian->inv_id}}</b><br> -->
                <br>
                <b>Mitra : </b> <span ><strong> {{$rincian->name}}</strong></span><br>
                <b>Tanggal Pesanan</b> {{date('d-m-Y',strtotime($rincian->pesanan_tanggal))}}<br>
                <b>Status : </b> <span class="text-dark"><strong> {{$rincian->pesanan_status}}</strong></span><br>
                           </div>
            </div>

            <div class="row">
              <div class="col-12 table-responsive">
                <table class="table">
                  <thead>
                  <tr class="">
                    <th>No</th>
                    <th>Aksi</th>
                    <th>Deskripsi</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($deskripsi_pesanan as $desk)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                        @if($desk->pesanan_status == 'Proses')
                        <td> <button type="button" class="btn btn-warning btn-sm">Dalam Proses</button></td>
                        @elseif($desk->pesanan_status_generate == '1')
                        <td> <button type="button" class="btn btn-success btn-sm">Sudah Generate</button></td>
                        @elseif($desk->pesanan_status == 'UNPAID')
                        <form action="{{route('admin.vhc.store_vhc')}}" method="post">
                          @csrf
                          @method('PUT')
                          <td> <input type="text" name="pesanan_id" value="{{$desk->pesanan_id}}"> </td>
                          <td> <input type="text" name="paket_id" value="{{$desk->pesanan_paketid}}"> </td>
                          <td> <button type="submit" class="btn btn-primary btn-sm">Genearte</button> </td>
                        </form>
                        @endif
                    <td>{{$desk->paket_nama}}</td>
                    <td>{{$desk->pesanan_jumlah}}</td>
                    <td class="text-right ">Rp. {{number_format($desk->pesanan_hpp)}}</td>
                    <td class="text-right text-bold">Rp. {{number_format($desk->pesanan_total_hpp)}}</td>
                  </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                
              </div>
              <div class="col-6">
                <div class="table-responsive">
                  <table class="table" id="Table">
                    <tr>
                      <th>Total:</th>
                      <td class="text-right text-bold">Rp {{number_format($total)}}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <!-- Button trigger modal -->
              <a href="{{route('admin.vhc.print_nota_pesanan',['id'=>$rincian->pesanan_id])}}"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#print_pesanan">
                Print Nota
              </button></a>
              
              @if($rincian->pesanan_status == 'PAID')
              <button type="button" class="btn btn-success">
                Lunas
              </button>
              @elseif($rincian->pesanan_status == 'UNPAID')
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#bayar_pesanan">
                Bayar Pesanan
              </button>
              <a href="{{route('admin.vhc.print_voucher',['id'=>$rincian->pesanan_id])}}"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#print_voucher">
                Print Voucher
              </button></a>
              @elseif($rincian->pesanan_status == 'Proses')
              <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#proses_pesanan">
                Proses Pesanan
              </button>
              
              @endif

           
              <!-- Modal bayar pesanan-->
              <div class="modal fade" id="bayar_pesanan" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form action="{{route('admin.vhc.bayar_pesanan', ['id'=>$rincian->pesanan_id])}}" method="post">
                        @csrf
                        @method('PUT')
                    <div class="modal-body">
                      <div class="form-group row">
                              <label class="col-sm-4 col-form-label">Outlet</label>
                              <div class="col-sm-8">
                                <input type="text" class="form-control " name="outlet_nama" value="{{$rincian->outlet_nama }}">
                              </div>
                          </div>
                    <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Total Bayar</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control readonly" name="jumlah_bayar" value="{{$total}}">
                            </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Bagi Hasil</label>
                          <div class="col-sm-6">
                            <input type="number" class="form-control " name="bh_persen" value="">
                          </div>
                          <label class="col-sm-2 col-form-label">%</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Bayar Pesanan</button>
                    </form>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Modal proses pesanan-->
              <div class="modal fade" id="proses_pesanan" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="{{route('admin.vhc.proses_pesanan')}}" method="post">
                        @csrf
                        @method('POST')
                      <div class="form-group">
                           <label>Pesanan Id</label>
                           <input type="text" value="{{$rincian->pesanan_id}}" name="pesanan_id" class="form-control">
                       </div>
                      <div class="form-group">
                           <label>Mitra Id</label>
                           <input type="text" value="{{$rincian->pesanan_mitraid}}" name="pesanan_mitraid" class="form-control">
                       </div>
                       <div class="form-group">
                            <label>Nama Outlet</label>
                            <input type="text" value="{{$rincian->outlet_nama}}" name="outlet_nama" class="form-control">
                        </div>
                       <div class="form-group">
                            <label>Total Pesanan</label>
                            <input type="text" value="{{$total}}" name="pesanan_total" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Proses</button>
                    </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- Modal print pesanan-->
              <div class="modal fade" id="print_pesanan" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      1111
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary">Understood</button>
                    </div>
                  </div>
                </div>
              </div>
             

          </div>
      </div>
    </div>
  </div>
@endsection

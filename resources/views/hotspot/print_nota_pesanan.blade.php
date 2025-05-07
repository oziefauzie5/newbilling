@extends('layout.print')
@section('content')

<div class="content">
  <div class="page-inner">
  <div class="card">
    <div class="invoice p-3">
            <div class="row m-3 mb-5 ">
              <div class="col-sm-6">
                <img src="{{ asset('storage/img/'.Session::get('app_logo')) }}" alt="">
                {{-- <img class="float-right" src="{{ asset('storage/img/'.Session::get('app_logo')) }}" alt=""> --}}
              </div>
              <div class="col-sm-3"></div>
              <div class="col-sm-3">
                <h3>INVOICE</h3>
                <b>Tanggal Cetak</b> {{date('d-m-Y')}}<br>
                <b>Counter : </b> <span ><strong> {{$rincian->outlet_pemilik}}</strong></span><br>
              </div>
            </div>
            <div class="row m-3">
              <div class="col-sm-6">
                <address>
                  <b><strong>{{$rincian->outlet_pemilik}}</strong></b><br>
                  {{$rincian->outlet_alamat}}<br>
                  {{$rincian->outlet_hp}}<br>
                  {{$rincian->outlet_nama}}
                </address>
                
              </div>
              <div class="col-sm-3"></div>
              <div class="col-sm-3">
                <b>No Pesanan : {{$rincian->pesanan_id}}</b><br>
                <br>
                <b>Mitra : </b> <span ><strong> {{$rincian->name}}</strong></span><br>
                <b>Tanggal Pesanan</b> {{date('d-m-Y',strtotime($rincian->pesanan_tanggal))}}<br>
                <b>Status : </b> <span class="text-dark"><strong> {{$rincian->pesanan_status}}</strong></span><br>
             
                @endif
              </div>
            </div>

            <div class="row">
              <div class="col-12 table-responsive">
                <table class="table">
                  <thead>
                  <tr class="">
                    <th>No</th>
                    <th>Deskripsi</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>PPN</th>
                    <th>Total</th>
                    <th></th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($deskripsi_pesanan as $desk)
                  <tr>
                    <td>1</td>
                    <td>{{$desk->subinvoice_deskripsi}}</td>
                    <td>{{$desk->subinvoice_qty}}</td>
                    <td class="text-right ">{{number_format($desk->subinvoice_harga)}}</td>
                    <td class="text-right ">{{number_format($desk->subinvoice_ppn)}}</td>
                    <td class="text-right text-bold">{{number_format($desk->subinvoice_total)}}</td>
                    @if($desk->subinvoice_status=='1')
                    <form action="{{route('admin.inv.addons_delete',['id'=>$desk->id,'inv'=>$desk->subinvoice_id,'tot'=>$desk->subinvoice_total])}}" method="POST" >
                    @csrf
                    @method('DELETE')
                      <td><button type="submit" class="btn text-danger btn-sm" > <i class="fas fa-solid fa-trash"></i></button></td>
                    </form>
                    @else
                    <td></td>
                    @endif
                  </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-6"> </div>

              <div class="col-6">
                <div class="table-responsive">
                  <table class="table" id="Table">
                    <tr>
                      <th style="width:50%">Subtotal:</th>
                      <td>Rp {{number_format($sumharga-$sumppn)}}</td>
                    </tr>
                    <tr>
                      <th>PPN {{$ppnj}}%</th>
                      <td >Rp {{number_format($sumppn)}}</td>
                    </tr>
                    <tr>
                      <th>Diskon</th>
                      <td id="td">Rp {{number_format($rincian->inv_diskon)}}</td>
                    </tr>
                    <tr class="font-weight-bold">
                      <th>Total:</th>
                      <td id="tot" >Rp {{number_format($sumharga-$rincian->inv_diskon)}}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12"> 
<p>- Kami sampaikan terima kasih atas pembayaran tagihan yang bapak/ibu lakukan, dan layanan dapat gunakan sampai tagihan berikutnya</p>
<p class="mt--3">- Tanda terima ini adalah sah dan harap simpan sebagai bukti pembayaran</p>
              </div>
              </div>
            <div class="row mt-5">
              <div class="col-8"> 
              </div>
              <div class="col-4 text-center"> 
<p>Dengan Hormat,</p><br><br><br>
<p class="font-weight-bold">Sri Wahyuni</p>
<p class="mt--3 font-weight-bold">Finance</p>
              </div>
              </div>




           

          </div>
      </div>
    </div>
  </div>
@endsection

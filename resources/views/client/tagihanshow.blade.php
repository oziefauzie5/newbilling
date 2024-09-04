@extends('layout.pelanggan')
@section('content')
        <style>
            blink {
                color: #2d38be;
                font-size: 15px;
                font-weight: bold;
            }
        </style>
        
    <div class="content m-3">
      <div class="card mt-5 text-center">
        <div class="card-header">
          <a href="{{route('client.index')}}" style="text-decoration:none"><h3><i class="fas fa-angle-double-left"></i>PEMBAYARAN TAGIHAN</h3></a>
          <h3 class="card-title text-center ">MENUNGGU DIBAYAR</h3>
            </div>
            <div class="card-body text-center">
              <h5>Metode Pembayaran</h5>
              <img src="{{$tripay->order_items[0]->image_url}}" width="110px"  alt="">
              <br><br>
              @if($tripay->payment_method == 'QRIS')
              <img src="{{$tripay->qr_url}}" alt="">
              @elseif($tripay->payment_method == 'QRIS2')
              <img src="{{$tripay->qr_url}}" alt="">
              @elseif($tripay->payment_method == 'OVO')
              <a href="https://tripay.co.id/checkout/T2905017834241S7XWD"><button class="btn btn-sm btn-primary btn-block mb-3">Lanjutkan Pembayaran</button></a>
              @elseif($tripay->payment_method == 'DANA')
              <a href="https://m.dana.id/n/cashier/new/checkout?bizNo=20240828111212800110166736611610985&timestamp=1724825924006&originSourcePlatform=IPG&mid=216620000383553341323&did=216650000412588568324&sid=216660000412542653320&sign=rr2AmoY19RISIVYURyrpjL3sNTA0OFOCU4VQKLaJVUl4N%2Bt6WGVXizinPA2vZl8hvsFmTQRrX9LKMSb4P57XV9tP3F2ThrcvM%2FBIY7EtjCT2BuIkevP1OsvSI7XBaNk6ySbNwgi4lcPwKRMx4KQraBi0mPlslKSct7xg1GEo6UyYGXIJkwuo%2Bcc%2BRP3t3vcZrY25pFg%2FeHcDL0K2J41tFOgQamEWqSAkaUgcSzvKdluIIHRoUORDXmI%2B9u1e4kB%2B6b9LYkeAHgGVsLz8F3NtBILK%2Bp9tMwJYcI88c0INgu%2BXuU%2F8FpKAk1RWRiwSsgwwdjtiR0oFFL3vAdfjvCLKCQ%3D%3D&forceToH5=false"><button class="btn btn-sm btn-primary btn-block mb-3">Lanjutkan Pembayaran</button></a>
              @elseif($tripay->payment_method == 'SHOPEEPAY')
              <a href="https://id.shp.ee/sppay_checkout_id?type=start&mid=10290655&target_app=shopee&medium_index=Um80ZWF4Yk9xZmROEZJQaul4Ae55T4BXGBWtj9eI7YKNtl_SUHcdZq0Mf4ggyA&order_key=s0Zo5bfzNw9ygytx73mNvRrs3bR_03KCFJzXlOLzlh5odDahQvXXSNFlYXBCQvHn14piOXbUQwkmDQ&order_sn=137562694227074579&return_url=aHR0cHM6Ly90cmlwYXkuY28uaWQvcmVkaXJlY3QvZHBheT9hbW91bnQ9MTk5MDAwMDAmY2xpZW50X2lkPUR1cmlhbnBheSZvcmRlcl9yZWZfaWQ9VDI5MDUwMTc4MzQxNzJZWlVLUiZwYXltZW50X2lkPWNHRjVYekIwYzFsdGNEVTRiMnMxTkRVeiZyZWZlcmVuY2VfaWQ9cGF5XzB0c1ltcDU4b2s1NDUzJnJlc3VsdF9jb2RlPTIwMyZzaWduYXR1cmU9a0tYdV9WQzlabmdDbU9LY2dQRVNoejVjcF9uWTdOT1dXelRQSy1oSDdZSSUzRA%3D%3D&source=web&token=Um80ZWF4Yk9xZmROEZJQaul4Ae55T4BXGBWtj9eI7YKNtl_SUHcdZq0Mf4ggyA"><button class="btn btn-sm btn-primary btn-block mb-3">Lanjutkan Pembayaran</button></a>
              @else
              <h6>Nomor Virtual Account</h6>
              <h3><strong>{{$tripay->pay_code}}</strong></h3>
              @endif
              <br>
              <h6>SILAHKAN BAYAR SEBELUM</h6>
              <h3 class="text-danger">{{$expire}}</h3><br>
              <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
              </div>
              <h4 id="blink" class="text-danger">Menunggu Pembayaran</h4>
              <div class="spinner-border text-danger"></div><br><br>
              <h4> <strong>ADM : Rp. {{ number_format($tripay->total_fee)}}</strong></h4>
              <H6>TOTAL HARUS DIBAYAR</H6>
              <h1 class="text-primary"><strong>Rp. {{ number_format($tripay->amount) }}</strong></h1>
              <br>
              <hr>
              <h3>PETUNJUK PEMBAYARAN</h3>
            </div>
            @foreach($tripay->instructions as $item)
            <button type="button" class="btn btn-primary mt-2 text-uppercase" data-toggle="modal" data-target="#exampleModal">
              {{ $item->title }}
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title text-uppercase text-center" id="exampleModalLabel">{{ $item->title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    @foreach($item->steps as $step)
                    {{$loop->iteration}}. {!! $step !!}<br>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
              @endforeach
          </div>
    </div>

        <script type="text/javascript">
            var blink = document.getElementById('blink');
            setInterval(function() {
                blink.style.opacity = (blink.style.opacity == 0 ? 3 : 0);
            }, 1000);
        </script>
    
@endsection
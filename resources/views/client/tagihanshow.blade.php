@extends('layout.user')
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
              <h6>Nomor Virtual Account</h6>
              <h3><strong>{{$tripay->pay_code}}</strong></h3>
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
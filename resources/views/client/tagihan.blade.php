@extends('layout.user')
@section('content')

<div class="content">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
<a href="{{route('client.index')}}"><h3 class="text-light"><i class="fas fa-angle-double-left"></i> RINCIAN LAYANAN</h3></a>
                
            </div>
        </div>
    </div>
    <div class="page-inner mt--3">
        <div class="row mt--5">
          <div class="col">
            <div class="card card-stats card-round">
              <div class="card-body ">
                <h3 class="text-danger">BELUM BAYAR</h3>
                <div><h5 class="fw-bold mb-1">{{$layanan->inv_nama}}</h5></div>
                <div><h5 class="fw-bold mb-1">{{$layanan->input_hp}}</h5></div>
                <div><h5>{{$layanan->input_alamat_pasang}}</h5></div><br>
                <div><h1 class="fw-bold mb-1">INV {{$layanan->inv_id}}</h1></div>
                <div><h5>No. Layanan {{$layanan->inv_nolayanan}}</h5></div>
                <div><h5>Jatuh tempo <span class="fw-bold mb-1">{{date('d-m-Y', strtotime($layanan->inv_tgl_jatuh_tempo))}}</span> </h5></div><br><br>
                <div>RINCIAN</div><hr>
                <div class="row">
                  @foreach($subinvoice as $subinv)
                  <div class="col-6">
                    <h5>{{$subinv->subinvoice_deskripsi}}</h5>
                  </div>
                  <div class="col-6 text-right">
                    <h5>{{number_format($subinv->subinvoice_total)}}</h5>
                  </div>
                  @endforeach
                </div><br><hr class="mt--3">
                <div class="row">
                  <div class="col-6">
                    <h4 class="fw-bold mb-1">SUBTOTAL</h4>
                  </div>
                  <div class="col-6 text-right">
                    <h5>{{number_format($layanan->inv_total)}}</h5>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <h4>TOTAL TAGIHAN</h4>
                  </div>
                  <div class="col-6 text-right">
                    <h3 class="fw-bold mb-1">{{number_format($layanan->inv_total)}}</h3>
                  </div>
                </div>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#exampleModal">
                  BAYAR TAGIHAN
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">METODE PEMBYARAN</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        ...
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
@endsection
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
                  <div class="h1 m-0">{{$input_data}}</div>
                  <div class="text-muted mb-3">INPUT DATA</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-sm-6">
              <div class="card">
                <div class="card-body p-3 text-center">
                  <div class="text-right text-success">
                  </div>
                  <div class="h1 m-0">{{$registrasi}}</div>
                  <div class="text-muted mb-3">REGISTRASI</div>
                </div>
              </div>
            </div>
          </div>
            <section class="content mt-3">
                @foreach($data_pemasangan as $psb)
                <div class="col">
                    <div class="card card_custom1"  data-toggle="modal" data-target="#exampleModal{{$psb->id}}">
                        <div class="card-body skew-shadow">
                            <div class="row">
                                <div class="col-8 pr-0">
                                    <h3 class="fw-bold mb-1">{{$psb->input_nama}}</h3>
                                    <div class="text-small text-uppercase fw-bold op-8">{{$psb->input_alamat_pasang}}</div>
                                </div>
                                <div class="col-4 pl-0 text-right">
                                    <h3 class="fw-bold mb-1">{{$psb->input_tgl}}</h3>
                                    <div class="text-small text-uppercase fw-bold op-8">{{$psb->paket_nama}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </section>




            
    </div>
  </div>
@endsection
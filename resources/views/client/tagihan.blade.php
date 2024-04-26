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
                <ul class="list-group">
                  <li class="list-group-item">
                    <div>{{$layanan->inv_nama}}</div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
    </div>
@endsection
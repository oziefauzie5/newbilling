@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">{{$tiket->tiket_judul}}</h4>
            <hr>
            <h5 class="card-title">{{$tiket->tiket_deskripsi}}</h5>
            <span>- {{$tiket->tgl_buat}}</span>
            
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">

            
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">TIKET PROGRESS</h4>
            <hr>
            <div class="row">
              <div class="col-md-12">
                
                
                <ul class="timeline">
                  @foreach($subtiket as $d)
                  <li class="timeline">
                    <div class="timeline-badge success">{{$loop->iteration}}</div>
                    <div class="timeline-panel">
                      <div class="timeline-heading">
                        <h4 class="timeline-title">{{$d->subtiket_deskripsi}}</h4>
                        <span>STATUS : {{$d->subtiket_status}}</span><br>
                        <span>{{date('d-M-Y H:m:s', strtotime($d->tgl_progres))}}</span><br>
                        <span>BY : {{$d->name}}</span><br>
                      </div>
                    </div>
                  </li>
                  @endforeach
                </ul>
              </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
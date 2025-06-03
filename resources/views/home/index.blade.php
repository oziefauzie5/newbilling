@extends('layout.main')
@section('content')

<div class="content">
  <div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
        <div>
          <h2 class="text-white pb-2 fw-bold">{{Session::get('app_nama')}}</h2>
          <h3 class="text-white op-7 mb-2">{{Session::get('app_brand')}}</h3>
        </div>
      </div>
    </div>
  </div>
  <div class="page-inner mt--5">
  
  {{-- DASHBOARD REGISTRASI --}}
  {{-- <div class="row">
    <div class="col-6 col-sm-4 col-lg-2">
      <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$count_inputdata}}</div>
            <div class="text-muted mb-3">Data Belum Registrasi</div>
          </div>
        </div>
      </div>
      
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$count_berlangganan_ppp}}</div>
            <div class="text-muted mb-3">Berlangganan PPP</div>
          </div>
        </div>
      </div>
      
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$count_berlangganan_hotspot}}</div>
            <div class="text-muted mb-3">Berlangganan Hotspot</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$count_pb}}</div>
            <div class="text-muted mb-3">Putus Berlanggan</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$count_ps}}</div>
            <div class="text-muted mb-3">Putus Sementara</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$count_free_berlangganan}}</div>
            <div class="text-muted mb-3">Free Berlanggan</div>
          </div>
        </div>
      </div>
    </div> --}}
  {{-- DASHBOARD REGISTRASI --}}
  {{-- DASHBOARD TIKET --}}
  <div class="row">
    <a style="text-decoration:none" href="{{route('admin.tiket.data_tiket')}}" class="col-6 col-sm-4 col-lg-3">
      <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0 text-danger">{{$count_tiket_general}}</div>
            <div class="text-muted">Tiket General</div>
            <div class="d-flex justify-content-between mt-2">
              <p class="text-muted mb-0 text-danger">Tiket Hari ini</p>
              <p class="mb-0 text-danger">{{$count_tiket_general_hari_ini}}</p>
            </div>
          </div>
        </div>
      </a>
    <div class="col-6 col-sm-4 col-lg-3">
      <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0 text-danger">{{$count_tiket_project}}</div>
            <div class="text-muted">Tiket Project</div>
            <div class="d-flex justify-content-between mt-2">
              <p class="text-muted mb-0 text-danger">Tiket Project Hari ini</p>
              <p class="mb-0 text-danger">{{$count_tiket_project_hari_ini}}</p>
            </div>
          </div>
        </div>
      </div>
    <div class="col-6 col-sm-4 col-lg-3">
      <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0 text-danger">{{$count_tiket_closed}}</div>
            <div class="text-muted">Tiket Closed</div>
            <div class="d-flex justify-content-between mt-2">
              <p class="text-muted mb-0 text-danger">Tiket Closed Hari ini</p>
              <p class="mb-0 text-danger">{{$count_tiket_closed_hari_ini}}</p>
            </div>
          </div>
        </div>
      </div>
    <div class="col-6 col-sm-4 col-lg-3">
      <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0 text-danger">{{$count_tiket_pending}}</div>
            <div class="text-muted">Tiket Pending</div>
            <div class="d-flex justify-content-between mt-2">
              <p class="text-muted mb-0 text-danger">Tiket Pending Hari ini</p>
              <p class="mb-0 text-danger">{{$count_tiket_pending_hari_ini}}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  {{-- DASHBOARD TIKET --}}

</div>
</div>


@endsection
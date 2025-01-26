@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
     {{-- DASHBOARD TIKET --}}
  <div class="row">
    <div class="col-6 col-sm-4 col-lg-3">
      <a style="text-decoration:none" href="{{route('admin.tiket.data_tiket')}}" class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0 text-danger">{{$count_tiket_general}}</div>
            <div class="text-muted">Tiket General</div>
            <div class="d-flex justify-content-between mt-2">
              <p class="text-muted mb-0 text-danger">Tiket Hari ini</p>
              <p class="mb-0 text-danger">{{$count_tiket_general_hari_ini}}</p>
            </div>
          </div>
        </a>
      </div>
    <div class="col-6 col-sm-4 col-lg-3">
      <a tyle="text-decoration:none" href="{{route('admin.tiket.data_tiket_project')}}" class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0 text-danger">{{$count_tiket_project}}</div>
            <div class="text-muted">Tiket Project</div>
            <div class="d-flex justify-content-between mt-2">
              <p class="text-muted mb-0 text-danger">Tiket Project Hari ini</p>
              <p class="mb-0 text-danger">{{$count_tiket_project_hari_ini}}</p>
            </div>
          </div>
        </a>
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
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-primary">
            <div class="d-flex align-items-center">
              <h4 class="card-title text-light">DATA TIKET</h4>
            </div>
          </div>
          <div class="card-body">
           
            
          
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
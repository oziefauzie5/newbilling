@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <a href="{{route('admin.psb.list_input')}}" class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-success">
              {{-- 6% --}}
              <i class="fa fa-chevron-up"></i>
            </div>
            <div class="h1 m-0">43</div>
            <div class="text-muted mb-3">Input Data</div>
          </div>
        </div>
      </a>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-danger">
              -3%
              <i class="fa fa-chevron-down"></i>
            </div>
            <div class="h1 m-0">17</div>
            <div class="text-muted mb-3">Registrasi</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-success">
              9%
              <i class="fa fa-chevron-up"></i>
            </div>
            <div class="h1 m-0">7</div>
            <div class="text-muted mb-3">Konfirmasi</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-success">
              3%
              <i class="fa fa-chevron-up"></i>
            </div>
            <div class="h1 m-0">27.3K</div>
            <div class="text-muted mb-3">Teknisi</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-danger">
              -2%
              <i class="fa fa-chevron-down"></i>
            </div>
            <div class="h1 m-0">$95</div>
            <div class="text-muted mb-3">Aktivasi</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="text-right text-danger">
              -1%
              <i class="fa fa-chevron-down"></i>
            </div>
            <div class="h1 m-0">621</div>
            <div class="text-muted mb-3">Pembayaran</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
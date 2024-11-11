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
</div>

@endsection
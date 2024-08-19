@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-primary">
            <div class="d-flex align-items-center">
              <h4 class="card-title text-light">DATA ODP</h4>
            </div>
          </div>
          <div class="card-body">
            <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#addpaket">
              <i class="fa fa-plus"></i>TAMBAH ODP</button>
<hr>

            
            @if ($errors->any())
            <div class="alert alert-danger">
              <div class="alert-title"><h4>Gagal!!</h4></div>
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
            </div> 
            @endif
                        <form >
              <div class="row mb-1">
                <div class="col-sm-4">
                  <input name="q" type="text" class="form-control form-control-sm" placeholder="Cari">
                </div>
                <div class="col-sm-2">
                  <button type="submit" class="btn btn-block btn-dark btn-sm">Submit
                </div>
              </div>
              </form>
              <hr>
            <div class="table-responsive">

  
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
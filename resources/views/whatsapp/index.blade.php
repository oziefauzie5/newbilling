@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-primary">
            <div class="d-flex align-items-center">
              <h4 class="card-title text-light">WHATSAPP GETEWAY</h4>
            </div>
          </div>
          <div class="card-body">
            <button class="btn  btn-sm ml-auto m-1 btn-primary ">
              <i class="fa fa-plus"></i> HAPUS</button>
            <button class="btn  btn-sm ml-auto m-1 btn-primary ">
              <i class="fa fa-plus"></i> EXPORT</button>
            <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#addpaket">
              <i class="fa fa-plus"></i> BUAT TIKET</button>        
            </div>


            
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
            
            <div class="table-responsive">
              <table class="display table table-striped table-hover text-nowrap" >
                <thead>
                  <tr>
                    <th>Tujuan</th>
                    <th>Pesan</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($whatsapp as $d)
                  <tr>
                    <td>{{$d->target}}</td>
                    <td>{{$d->pesan}}</td>
                    <td>{{$d->status}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              <div class="pull-left">
                Showing
                {{$whatsapp->firstItem()}}
                to
                {{$whatsapp->lastItem()}}
                of
                {{$whatsapp->total()}}
                entries
              </div>
              <div class="pull-right">
                {{ $whatsapp->withQueryString()->links('pagination::bootstrap-4') }}
              </div>
  
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
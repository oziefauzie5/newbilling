@extends('layout.main')
@section('content')
<style>
  .notice{
    font-size:11px;
    color:red;
    font-weight: bold;
  }
</style>
<div class="content">
  <div class="page-inner">
    <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header bg-primary">
              <h4 class="card-title text-light">Data slot ODP {{$details_odp->odp_id}} ( {{$details_odp->odp_nama}} ) {{$details_odp->odp_keterangan}} </h4>
              </div>

             
              
            <div class="card-body table-responsive -sm">
                <a href="{{route('admin.topo.odp')}}"><button class="btn  btn-sm ml-auto m-1 btn-primary">
                  <i class="fas fa-solid fa-arrow-left"></i>
                    {{-- <i class="fa fa-plus"></i> --}}
                    Kembali
                  </button>
                </a>
              @if ($errors->any())
                      <div class="alert alert-danger" role="alert">
                        <ul>
                          @foreach ($errors->all() as $item)
                              <li>{{ $item }}</li>
                          @endforeach
                        </ul>
                        </div>
              @endif
              <div class="table-responsive">
                <table id="inputdata" class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Slot Odp</th>
                      <th>Id Pelanggan</th>
                      <th>Nama</th>
                      <th>Redaman</th>
                      <th>Alamat</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($data_odp as $d)
                    <tr>
                      

                      <td class="text-center">{{$d->reg_slot_odp ?? ''}}</td>
                        <td>{{$d->idpel ?? ''}}</td>
                        <td>{{$d->input_nama ?? ''}}</td>
                        <td>{{$d->reg_in_ont ?? ''}}</td>
                        <td>{{$d->input_alamat_pasang ?? ''}}</td>
                        <td>{{$d->odp_status ?? ''}}</td> 
                      </tr>
                  @endforeach
                </table>
                </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>

@endsection
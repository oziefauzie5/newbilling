@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Data Fee Continue</h3>
            </div>
              <div class="card-body">
<div class="table-responsive">
    <table id="input_data" class="display table table-striped table-hover text-nowrap" >
                      <thead>
                <tr>
                  <th>No</th>
                  <th>Pic Id</th>
                  <th>Pic</th>
                  <th>Pelanggan</th>
                  <th>Komisi</th>
                  
                </tr>
                </thead>
                <tbody>
                  @foreach ($mutasi as $d)
                  <tr>
                   <td>{{$loop->iteration}}</td>
                   <td>{{$d->mts_user_id ?? '-'}}</td>
                   <td>{{$d->user_mitra->name ?? '-'}}</td>
                   <td>{{$d->input_nama ?? '-'}}</td>
                   <td>Rp. {{ number_format($d->sum_komisi) ?? '0'}}</td>
                   
                   </tr>
                   @endforeach
                </tbody>
              </table>
              </div>
          </div>
          </div>
  </div>
</div>

@endsection
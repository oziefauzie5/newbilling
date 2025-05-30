@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col-6">
                <h3 class="card-title">Data Mutasi</h3>
              </div>
                <div class="col-6 text-right">
                <h3 class="card-title">Saldo - Rp. {{number_format($saldo ??'')}}</h3>
              </div>
              </div>
            </div>
              <div class="card-body">


<div class="table-responsive">
    <table id="" class="display table table-striped table-hover text-nowrap" >
                      <thead>
                <tr>
                  <th>No</th>
                  <th>Mitra</th>
                  <th>Nama</th>
                  <th>Jumlah</th>
                  <th>Tanggal</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($mutasi as $d)
                  <tr>
                   <td>{{$loop->iteration}}</td>
                   <td>{{$d->name ?? '-'}}</td>
                   <td>{{$d->mutasi_sales_deskripsi ?? '-'}}</td>
                   <td>{{$d->mutasi_sales_jumlah ?? '-'}}</td>
                   <td>{{$d->created_at ?? '-'}}</td>
                   </tr>
                   @endforeach
                </tbody>
                <tfoot>
              </table>
              </div>
          </div>
          </div>
  </div>
</div>

@endsection
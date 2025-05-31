@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Data Mutasi Mitra</h3>
            </div>
              <div class="card-body">


<div class="table-responsive">
    <table id="input_data" class="display table table-striped table-hover text-nowrap" >
                      <thead>
                <tr>
                  <th>No</th>
                  <th>Pic</th>
                  <th>Pelanggan</th>
                  <th>No Invoice</th>
                  <th>Tgl Transaksi</th>
                  <th>Type</th>
                  <th>Fee</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($mutasi as $d)
                  <tr>
                    {{-- 'mutasi_sales_mitra_id',
                    'mutasi_sales_deskripsi',
                    'mutasi_sales_inv_id', --}}
                   <td>{{$loop->iteration}}</td>
                   <td>{{$d->name?? '-'}}</td>
                   <td>{{$d->input_nama?? '-'}}</td>
                   <td>INV-{{$d->mutasi_sales_inv_id?? '-'}}</td>
                   <td>{{ date('d-m-Y H:i:s' ,strtotime($d->tgl_transaksi??'-'))}}</td>
                   <td>{{$d->mutasi_sales_type?? '-'}}</td>
                   <td>{{$d->mutasi_sales_jumlah?? '-'}}</td>
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
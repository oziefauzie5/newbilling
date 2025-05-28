@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Data Mutasi</h3>
            </div>
              <div class="card-body">


<div class="table-responsive">
    <table id="input_data" class="display table table-striped table-hover text-nowrap" >
                      <thead>
                <tr>
                  <th>No</th>
                  <th>Aksi</th>
                  <th>Site</th>
                  <th>Sub PIC Id</th>
                  <th>Nama</th>
                  <th>Whatsapp</th>
                  <th>Fee</th>
                  <th>Alamat</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($pic_sub_view as $d)
                  <tr>
                   <td>{{$loop->iteration}}</td>
                   <td>{{$d->submitra_site->site_nama ?? '-'}}</td>
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
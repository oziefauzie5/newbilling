@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-primary">
            <div class="d-flex align-items-center">
              <h4 class="card-title text-light">DATA PINJAMAN KARYAWAN</h4>
            </div>
          </div>
          <div class="card-body">
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
            <hr>
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
                <table class="display table table-striped table-hover text-nowrap" >
                  <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pinjaman</th>
                        <th>NIK Karyawan</th>
                        <th>Nama</th>
                        <th>Jenis Pinjaman</th>
                        <th>Tempo</th>
                        <th>Uraian</th>
                        <th>Kredit</th>
                        <th>Debet</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($kasbon as $d)
                    <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$d->created_at}}</td>
                    <td>{{$d->kasbon_user_id}}</td>
                    <td>{{$d->name}}</td>
                    <td>{{$d->kasbon_jenis}}</td>
                    <td>{{$d->kasbon_tempo}}</td>
                    <td>{{$d->kasbon_uraian}}</td>
                    <td>{{number_format($d->kasbon_kredit)}}</td>
                    <td>{{number_format($d->kasbon_debet)}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                
                <div class="pull-left">
                  Showing
                  {{$kasbon->firstItem()}}
                  to
                  {{$kasbon->lastItem()}}
                  of
                  {{$kasbon->total()}}
                  entries
                </div>
                <div class="pull-right">
                  {{ $kasbon->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
              </div>
            
           
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
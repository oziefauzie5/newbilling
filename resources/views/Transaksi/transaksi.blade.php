@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
        <div href="{{route('admin.psb.list_input')}}" class="col">
          <div class="card">
            <div class="card-body p-3 text-center">
              <div class="h2 m-0">Rp. {{number_format($in_mount)}}</div>
              <div class="text-muted mb-3">Pendapatan Bulan ini</div>
            </div>
          </div>
        </div>
        <div href="{{route('admin.reg.index')}}" class="col">
          <div class="card">
            <div class="card-body p-3 text-center">
              <div class="h2 m-0">Rp. {{number_format($out_mount)}}</div>
              <div class="text-muted mb-3">Laporan Admin Bulan ini</div>
            </div>
          </div>
        </div>
        <div href="{{route('admin.reg.index')}}" class="col">
          <div class="card">
            <div class="card-body p-3 text-center">
              <div class="h2 m-0">Rp. {{number_format($in_mount - $out_mount)}}</div>
              <div class="text-muted mb-3">Total Bulan ini</div>
            </div>
          </div>
        </div>
      </div>
    <div class="row">
        <div href="{{route('admin.psb.list_input')}}" class="col">
          <div class="card">
            <div class="card-body p-3 text-center">
              <div class="h2 m-0">Rp. {{number_format($in_day)}}</div>
              <div class="text-muted mb-3">Pendapatan Hari ini {{$count_in}}</div>
            </div>
          </div>
        </div>
        <div href="{{route('admin.reg.index')}}" class="col">
          <div class="card">
            <div class="card-body p-3 text-center">
              <div class="h2 m-0">Rp. {{number_format($out_day)}}</div>
              <div class="text-muted mb-3">Laporan Admin Hari ini {{$count_out}}</div>
            </div>
          </div>
        </div>
        <div href="{{route('admin.reg.index')}}" class="col">
          <div class="card">
            <div class="card-body p-3 text-center">
              <div class="h2 m-0">Rp. {{number_format($in_day - $out_day)}}</div>
              <div class="text-muted mb-3">Total Hari ini {{$count_in - $count_out}}</div>
            </div>
          </div>
        </div>
      </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-primary">
            <div class="d-flex align-items-center">
              <h4 class="card-title text-light">DATA TRANSAKSI</h4>
            </div>
          </div>
          <div class="card-body">
            <button class="btn  btn-sm ml-auto m-1 btn-success ">
              <i class="fa fa-plus-circle"></i> TAMBAH</button>
            <button class="btn  btn-sm ml-auto m-1 btn-warning ">
              <i class="fas fa-calendar-alt"></i> LAP HARIAN</button>
            <button class="btn  btn-sm ml-auto m-1 btn-info ">
              <i class="fas fa-calendar"></i> LAP BULANAN</button>        
            <button class="btn  btn-sm ml-auto m-1 btn-primary ">
              <i class="fas fa-file-export"></i> EXPORT</button>        
            <button class="btn  btn-sm ml-auto m-1 btn-danger ">
              <i class="fas fa-broom"></i> KOSONGKAN</button>        
            <a href="{{route('admin.inv.trx.voucher')}}"><button class="btn  btn-sm ml-auto m-1 btn-primary ">
              <i class="fas fa-broom"></i> TRANSAKSI VOUCHER</button></a>        
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
              <table id="input_data" class="display table table-striped table-hover text-nowrap" >
                <thead>
                  <tr>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Admin</th>
                    <th>Deskripsi</th>
                    <th>Qty</th>
                    <th>Debet</th>
                    <th>Kredit</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($transaksi as $d)
                  <tr>
                    <td>{{date('d-m-Y',strtotime($d->created_at))}}</td>
                    <td>{{$d->trx_kategori}}</td>
                    <td>{{$d->trx_jenis}}</td>
                    <td>{{$d->trx_admin}}</td>
                    <td>{{$d->trx_deskripsi}}</td>
                    <td>{{$d->trx_qty}}</td>
                    <td>Rp. {{number_format($d->trx_debet)}}</td>
                    <td>Rp. {{number_format($d->trx_kredit)}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
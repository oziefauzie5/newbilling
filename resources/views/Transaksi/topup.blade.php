@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0 jt" id="customProductPricing">0</div>
            <div class="text-muted mb-3">TRANSAKSI</div>
          </div>
        </div>
      </div>
      </div>
    <div class="row">

      <div class="card">
        <div class="card-body">
          @if ($errors->any())
          <div class="alert alert-danger">
            <div class="alert-title">
              <h4>Gagal!!</h4>
            </div>
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle btn-sm"  type="button" data-toggle="dropdown" aria-expanded="false">
              Menu
            </button><hr>
            <div class="dropdown-menu">
              <button class="dropdown-item topup " type="button">Top Up</button>
            </div>
          </div>
          {{-- <button class="btn btn-block btn-info btn-sm topup">Top Up</button> --}}
          <input type="hidden" value="{{$admin}}" class="id_lap">
          <div class="table-responsive"  >
            <table id="topup_list" class="display table table-striped table-hover" >
              <thead>
                <tr>
                  <th class="text-center"><input type="checkbox" id="selectAllCheckbox" class="checkboxtopup" disabled ></th>
                  <th>ID</th>
                  <th>TANGGAL</th>
                  <th>ADMIN</th>
                  <th>INVOICE</th>
                  <th>KETERANGAN</th>
                  <th>CABAR</th>
                  <th>METODE BAYAR</th>
                  <th>KREDIT</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($laporan as $d)
                {{-- <form action="{{route('admin.inv.lap_topup',['id'=>$d->lap_admin])}}" method="post">
                  @csrf
                  @method('POST') --}}
                <tr>
                  <td class="text-center"><input type="checkbox" class="checkboxtopup" name="id[]" value="{{$d->laporan_id}}" data-price="{{$d->lap_kredit}}"></td>
                  <td>{{$d->laporan_id}}</td>
                  <td>{{date('d-m-Y',strtotime($d->lap_tgl))}}</td>
                  <td>{{$d->name}}</td>
                  <td>{{$d->lap_inv}}</td>
                  <td>{{$d->lap_keterangan}}</td>
                  <td>{{$d->lap_cabar}}</td>
                  <td>{{$d->akun_nama}}</td>
                  <td >Rp. {{number_format($d->lap_kredit)}}</td>
                </tr>
                
                @endforeach
              </tbody>
            </table>
            
            {{-- </form> --}}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
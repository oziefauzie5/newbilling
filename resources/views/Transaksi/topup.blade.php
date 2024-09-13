@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
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
          <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover text-nowrap">
              <thead>
                <tr>
                  <th>#</th>
                  <th>ID</th>
                  <th>TANGGAL</th>
                  <th>ADMIN</th>
                  <th>INVOICE</th>
                  <th>KETERANGAN</th>
                  <th>CABAR</th>
                  <th>METODE BAYAR</th>
                  <th>KREDIT</th>
                  <th>DEBET</th>
                  <th>AKSI</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($laporan as $d)
                <form action="{{route('admin.inv.lap_topup',['id'=>$d->lap_admin])}}" method="post">
                  @csrf
                  @method('POST')
                <tr>
                  <td class="text-center"><input type="checkbox" name="id[]" value="{{$d->id}}"></td>
                  <td>{{$d->lap_id}}</td>
                  <td>{{date('d-m-Y',strtotime($d->lap_tgl))}}</td>
                  <td>{{$d->name}}</td>
                  <td>{{$d->lap_inv}}</td>
                  <td>{{$d->lap_keterangan}}</td>
                  <td>{{$d->lap_cabar}}</td>
                  <td>{{$d->akun_nama}}</td>
                  <td>{{$d->lap_kredit}}</td>
                  <td>{{$d->lap_debet}}</td>
                  @endforeach
                  <tr>
                    <td colspan="10"><button class="btn btn-block btn-info btn-sm">Top Up</button></td>
                  </tr>
                </form>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
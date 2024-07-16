@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">DATA MITRA RESELLER DAN BILLER</h3>
            </div>
              <div class="card-body">
                 <ul>
                     <li>Reseller adalah orang yang dapat mengelola data voucher dan pelangganya sendiri serta mendapatkan komisi untuk setiap transaksi yang dilakukan</li>
                     <li >Biller adalah orang yang dapat menerima pembayaran tagihan langganan, misalnya loket pembayaran, tukang tagih dll</li>
                 </ul>
               <hr>
<div class="btn-group" role="group">
  <button type="button" class="btn btn-dark dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
      <i class="fas fa-solid fa-bars"></i></i>&ensp;MENU
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="#">SET AKTIF</a>
    <a class="dropdown-item" href="#">NON AKTIF</a>
    <a class="dropdown-item" href="#">HAPUS</a>
  </div>
</div>
<button class="btn btn-dark btn-sm"><i class="fas fa-solid fa-plus"></i>&ensp;RESELLER</button>
<a href="{{ route('admin.mitra.create') }}" class="btn btn-dark btn-sm" ><i class="fas fa-solid fa-plus"></i>&ensp;BILLER</a><br><hr>

<div class="table-responsive">
    <table id="input_data" class="display table table-striped table-hover text-nowrap" >
                      <thead>
                <tr>
                  <th>No</th>
                  <th>Aksi</th>
                  <th>Nama</th>
                  <th>Kategori</th>
                  {{-- <th>Stok VC</th> --}}
                  <th>Phone</th>
                  <th>Alamat</th>
                  <th>Saldo</th>
                  <th>Limit Minus</th>
                  <th>Komisi</th>
                  <th>Kode Unik</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($datauser as $d)
                  <tr>
                   <td>{{$loop->iteration}}</td>
                   <td><a href="{{route('admin.mitra.edit',['id'=>$d->id])}}" class="btn btn-sm btn-block"><i class="fas fa-pen"></i>1</a></td>
                   <td><a href="{{route('admin.mitra.data',['id'=>$d->id])}}"><strong>{{$d->nama}}</strong> </a> </td>
                   <td>{{$d->name}}</td>
                   <td>{{$d->hp}}</td>
                   <td>{{$d->alamat_lengkap}}</td>
                   <td></td>
                   <td>{{$d->mts_limit_minus}}</td>
                   <td>{{$d->mts_komisi}}</td>
                   <td>{{$d->mts_kode_unik}}</td>
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
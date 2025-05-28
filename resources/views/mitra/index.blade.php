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
<a href="{{ route('admin.mitra.create') }}" class="btn btn-dark btn-sm" ><i class="fas fa-solid fa-plus"></i>&ensp;Tambah Mitra Lingkungan</a><br><hr>

<div class="table-responsive">
    <table id="input_data" class="display table table-striped table-hover text-nowrap" >
                      <thead>
                <tr>
                  <th>No</th>
                  <th>Aksi</th>
                  <th>Nama</th>
                  <th>Kategori</th>
                  <th>Phone</th>
                  <th>Alamat</th>
                  <th>Saldo</th>
                  <th>Komisi Biller</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($datauser as $d)
                  <tr>
                   <td>{{$loop->iteration}}</td>
                   <td><a href="{{route('admin.mitra.edit',['id'=>$d->id])}}" class="btn btn-sm btn-block"><i class="fas fa-edit"></i></a></td>
                   <td><a href="{{route('admin.mitra.data',['id'=>$d->id])}}"><strong>{{$d->nama}}</strong> </a> </td>
                   <td>{{$d->name}}</td>
                   <td>{{$d->hp}}</td>
                   <td>{{$d->alamat_lengkap}}</td>
                   <td></td>
                   <td>{{$d->mts_komisi}}</td>
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
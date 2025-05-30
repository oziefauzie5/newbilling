@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">DATA RESELLER DAN BILLER</h3>
            </div>
              <div class="card-body">
                 <ul>
                     <li>Reseller adalah orang yang dapat mengelola data voucher dan pelangganya sendiri serta mendapatkan komisi untuk setiap transaksi yang dilakukan</li>
                     <li >Biller adalah orang yang dapat menerima pembayaran tagihan langganan, misalnya loket pembayaran, tukang tagih dll</li>
                 </ul>
               <hr>
<a href="{{ route('admin.mitra.pic1_add_view',['Mitra=Biller']) }}" class="btn btn-dark btn-sm" ><i class="fas fa-solid fa-plus"></i>&ensp;Tambah Biller</a><br><hr>

<div class="table-responsive">
    <table id="input_data" class="display table table-striped table-hover text-nowrap" >
                         <thead>
                <tr>
                  <th>No</th>
                  <th>Aksi</th>
                  <th>PIC Id</th>
                  <th>Nama</th>
                  <th>Site</th>
                  <th>Phone</th>
                  <th>Alamat</th>
                  <th>Komisi</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($data_mitra as $d)
                  <tr>
                   <td>{{$loop->iteration}}</td>
                   
                   <td>
                    <div class="form-button-action">
                      <a href="{{route('admin.mitra.pic1_edit_view',['id'=>$d->mts_user_id,'Mitra=Biller'])}}" class="btn btn-link btn-primary"><i class="fas fa-edit"></i></a>
                      <a href="{{route('admin.mitra.mitra_mutasi',['id'=>$d->mts_user_id])}}" class="btn btn-link btn-primary"><i class="fas fa-receipt"></i></a>
										</div>
                   </td>
                   <td>{{$d->mts_user_id ?? '-'}}</td>
                   <td><a href="{{route('admin.mitra.data',['id'=>$d->mts_user_id])}}"><strong>{{$d->user_mitra->name ?? '-'}}</strong> </a> </td>
                   <td>{{$d->mitra_site->site_nama ?? '-'}}</td>
                   <td>{{$d->user_mitra->hp ?? '-'}}</td>
                   <td>{{$d->user_mitra->alamat_lengkap ?? '-'}}</td>
                   <td>Rp. {{ number_format($d->mts_komisi) ?? '0'}}</td>
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
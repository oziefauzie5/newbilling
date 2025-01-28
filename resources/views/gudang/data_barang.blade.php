@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
      <div class="card">
          <div class="card-body">
          
              <div class="row">
            <div class="col-3">
              <a href="{{route('admin.gudang.stok_gudang')}}"><button class="btn btn-primary btn-sm mb-3 btn-block" type="button" >Kembali</button></a>
            </div>
         
          <hr>
           <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover" >                          
              <thead>
            <tr class="text-center">
              {{-- <th>#</th> --}}
              <th>Id Group</th>
              <th>Kode Barang</th>
              <th>Kategori</th>
              <th>Tanggal Masuk</th>
              <th>Nama</th>
              <th>Merek</th>
              <th>Mac</th>
              <th>Stok Awal</th>
              <th>Stok Akhir</th>
              <th>Satuan</th>
              <th>Keterangan</th>
              <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($barang as $d)
            <tr>
              {{-- <td> --}}
                  {{-- <i class="fas fa-trash" data-toggle="modal" data-target="#hapus{{ $d->barang_id }}"></i>&nbsp;&nbsp; --}}
                  {{-- <button class="btn btn-danger" data-toggle="modal" data-target="#hapus{{ $d->barang_id }}"><i class="fas fa-trash"></i></button> --}}
                  {{-- <button class="btn" data-toggle="modal" data-target="#edit{{ $d->barang_id }}"><i class="fas fa-edit"></i></button> --}}
                  {{-- <i class="fas fa-edit" data-toggle="modal" data-target="#edit{{ $d->barang_id }}"></i> --}}
                  {{-- <a href="{{ route('admin.barang.rekap_barang',['id'=>$d->barang_id])}}" class="btn"><i class="fas fa-print"></i></a> --}}
               {{-- </td> --}}
               <td >{{ $d->barang_id_group }}</td>
               <td >{{ $d->barang_id }}</td>
               <td data-toggle="modal" data-target="#detail_barang{{ $d->barang_id }}">{{ $d->barang_kategori }}</td>
               <td data-toggle="modal" data-target="#detail_barang{{ $d->barang_id }}">{{  date('d-m-Y', strtotime($d->barang_tglmasuk)); }}</td>
               <td data-toggle="modal" data-target="#detail_barang{{ $d->barang_id }}">{{ $d->barang_nama }}</td>
               <td data-toggle="modal" data-target="#detail_barang{{ $d->barang_id }}">{{ $d->barang_merek }}</td>
               <td data-toggle="modal" data-target="#detail_barang{{ $d->barang_id }}">{{ $d->barang_mac }}</td>
               <td data-toggle="modal" data-target="#detail_barang{{ $d->barang_id }}">{{ $d->barang_qty }}</td>
               <td data-toggle="modal" data-target="#detail_barang{{ $d->barang_id }}">{{ $d->barang_qty - $d->barang_digunakan }}</td>
               <td data-toggle="modal" data-target="#detail_barang{{ $d->barang_id }}">{{ $d->barang_satuan }}</td>
               <td data-toggle="modal" data-target="#detail_barang{{ $d->barang_id }}">{{ $d->barang_ket }}</td>
               <td data-toggle="modal" data-target="#detail_barang{{ $d->barang_id }}">@if($d->barang_dicek == 1) Barang belum dicek  @elseif($d->barang_rusak == 1) Barang Rusak @elseif($d->barang_dijual == 1)Barang Terjual @else Barang Normal  @endif</td>
            
            </tr>

            {{-- -----------------------------------------------------------START DETAIL_BARANG--------------------------------------------------------------- --}}
            <!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="detail_barang{{ $d->barang_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        {{-- <div class="row"> --}}
          <div class="col-4">
            <h5 class="modal-title" id="exampleModalLabel">KODE BARANG : {{ $d->barang_id }}</h5>
          </div>
          <div class="col-4">
            <h5 class="modal-title" id="exampleModalLabel">KATEGORI  :{{ $d->barang_kategori }}</h5>
          </div>
          <div class="col-4">
            <h5 class="modal-title" id="exampleModalLabel">TANGGAL MASUK  :{{  date('d-m-Y', strtotime($d->barang_tglmasuk)); }}</h5>
          </div>
        {{-- </div> --}}
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul>
          <li> Nama Barang : {{ $d->barang_nama }}</li>
          <li> Merek : {{ $d->barang_merek }}</li>
          <li> Stok Awal : {{ $d->barang_qty }}</li>
          <li> Stok Akhir : {{ $d->barang_qty - $d->barang_digunakan }}</li>
          <li> Satuan : {{ $d->barang_satuan }}</li>
          <li> Keterangan : {{ $d->barang_ket }}</li>
          <li> Status Barang : @if($d->barang_status == 0)Bagus, normal dan bisa digunakan @elseif($d->barang_status == 1) Barang Normal dan telah digunakan @elseif($d->barang_status == 2) Barang dalam lis tiket @elseif($d->barang_status == 4) Tidak bagus, rusak dan tidak bisa digunakan @elseif($d->barang_status == 5)Barang belum dicek @endif</li>
          <li> Serial Number : {{ $d->barang_sn }}</li>
          <li> Mac Address : {{ $d->barang_mac }}</li>
          <li> Digunakan oleh :{{ $d->barang_nama_pengguna }}</li>
          <li> Penerima Barang {{ $d->barang_penerima }}</li>
          <li> Di update oleh :{{ $d->barang_admin_update }}</li>
          <li> Tanggal terakhir Update : {{  date('d-m-Y H:m:s', strtotime($d->updated_at)); }}</li>
        </ul>
      </div>
    </div>
  </div>
</div>
            {{-- -----------------------------------------------------------END DETAIL_BARANG--------------------------------------------------------------- --}}
            
           
             
                @endforeach
                          </tbody>
                        </table>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>





 


 
@endsection

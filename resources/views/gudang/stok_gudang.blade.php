@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
      <div class="card">
          <div class="card-body">
          
            <form >
              <div class="row">
                
                <div class="col">
                  <a href="{{route('admin.gudang.data_barang')}}"><button class="btn btn-warning btn-sm mb-3 btn-block" type="button" >Data Barang</button></a>
                  
                </div>
                <div class="col">
                  <a href="{{route('admin.gudang.barang_keluar')}}"><button class="btn btn-primary btn-sm mb-3 btn-block" type="button" >Barang Keluar</button></a>
                </div>
                <div class="col">
                  <a href="{{route('admin.gudang.form_barang_keluar')}}"><button class="btn btn-danger btn-sm mb-3 btn-block" type="button" >Form Barang Keluar</button></a>
                </div>
              </div>
            
          </div>
            </form>
          <hr>
           <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover" >                          
              <thead>
            <tr class="text-center">
              <th>Kategori</th>
              <th>Jenis Barang</th>
              <th>Satuan</th>
              <th>Nominal Rupiah</th>
              <th>Total Stok Awal</th>
              <th>Total Barang Digunakan</th>
              <th>Total Barang dijual</th>
              <th>Total Barang rusak</th>
              <th>Total Pengembalian</th>
              <th>Sisa Stok</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($stok_gudang as $d)
            <tr>
               <td class="href_data_barang" data-kategori="{{ $d->barang_kategori }}" >{{ $d->barang_kategori }}</td>
               <td>{{ $d->barang_jenis }}</td>
               <td>{{ $d->barang_satuan }}</td>
               <td>Rp. {{ number_format($d->total_harga) }}</td>
               <td>{{ $d->total }}</td>
               <td>{{ $d->digunakan }}</td>
               <td>{{ $d->dijual }}</td>
               <td>{{ $d->rusak }}</td>
               <td>{{ $d->kembali }}</td>
               <td>{{ $d->total-$d->digunakan-$d->dijual-$d->rusak+$d->kembali }}</td>
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


 
@endsection

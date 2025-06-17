@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
      <div class="card">
          <div class="card-body">
          
            <form >
              <div class="row">
            <div class="col-3">
              <a href="{{route('admin.gudang.stok_gudang')}}"><button class="btn btn-primary btn-sm mb-3 btn-block" type="button" >Kembali</button></a>
            </div>
            <div class="col-3">
              <a href="{{route('admin.gudang.form_barang_keluar')}}"><button class="btn btn-primary btn-sm mb-3 btn-block" type="button" >Tambah</button></a>
            </div>
          </div>
            </form>
          <hr>
           <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover text-nowrap" >                          
              <thead>
            <tr class="text-center">
              <th>Nomor SKB</th>
              <th>ID</th>
              <th>Jenis Laporan</th>
              <th>No Tiket</th>
              <th>Jenis Barang</th>
              <th>Satuan</th>
              <th>Tanggal masuk barang</th>
              <th>Nama Barang</th>
              <th>Model</th>
              <th>Mac</th>
              <th>SN</th>
              <th>Jumlah</th>
              <th>Keperluan</th>
              <th>Nama Pengguna</th>
              <th>Waktu Pengeluaran</th>
              <th>Di Input oleh</th>
              <th>Penerima</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($barang_keluar as $d)
            <tr>
              <td> <a href="{{route('admin.gudang.print_skb')}}?skb={{$d->bk_id}}"  target="_blank" >{{ $d->bk_id}}</a> </td>
              <td>{{ $d->bk_id_barang}}</td>
              <td>{{ $d->bk_jenis_laporan}}</td>
              <td>{{ $d->bk_id_tiket}}</td>
              <td>{{ $d->bk_kategori}}</td>
              <td>{{ $d->barang_satuan}}</td>
              <td>{{ $d->barang_tglmasuk}}</td>
              <td>{{ $d->barang_nama}}</td>
              <td>{{ $d->barang_merek}}</td>
              <td>{{ $d->barang_mac}}</td>
              <td>{{ $d->barang_sn}}</td>
              <td>{{ $d->bk_jumlah}}</td>
              <td>{{ $d->bk_keperluan}}</td>
              <td>{{ $d->bk_nama_penggunan}}</td>
              <td>{{ $d->bk_waktu_keluar}}</td>
              <td>{{ $d->bk_admin_input}}</td>
              <td>{{ $d->bk_penerima}}</td>
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

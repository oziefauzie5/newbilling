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
            
          </div>
            </form>
          <hr>
           <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover" >                          
              <thead>
            <tr class="text-center">
              <th>Aksi</th>
              <th>Id Group</th>
              <th>Tanggal Keluar</th>
              <th>Jenis Laporan</th> 
              <th>Keperluan</th> 
              <th>Total Barang</th>
              <th>Total Harga</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($data_group_bk as $d)
            <tr class="text-center">
               <td><a href="{{route('admin.gudang.print_skb')}}?skb={{$d->bk_id}}" ><button type="button" class="btn btn-info btn-sm">Print Kode</button></a></td>
               <td>{{ $d->bk_id }}</td>
               <td>{{ date('d-m-Y', strtotime($d->bk_waktu_keluar)) }}</td>
               <td>{{ $d->bk_jenis_laporan }}</td>
               <td>{{ $d->bk_keperluan }}</td>
               <td>{{ $d->count }}</td>
               <td>{{ $d->harga }}</td>
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

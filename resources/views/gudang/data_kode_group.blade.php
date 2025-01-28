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
              <th>Tanggal Masuk</th>
              <th>Kategori</th>
              <th>Total Masuk</th>
              <th>Satuan</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($data_kode_group as $d)
            <tr class="text-center">
               <td><a href="{{route('admin.gudang.print_kode',['id'=>$d->barang_id_group])}}"><button type="button" class="btn btn-info btn-sm">Print Kode</button></a></td>
               <td>{{ $d->barang_id_group }}</td>
               <td>{{ date('d-m-Y', strtotime($d->barang_tglmasuk)) }}</td>
               <td>{{ $d->barang_kategori }}</td>
               <td>{{ $d->total }}</td>
               <td>{{ $d->barang_satuan }}</td>
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

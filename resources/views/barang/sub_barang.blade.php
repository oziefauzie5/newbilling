@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
                  <a href="{{ route('admin.barang.index')}}" ><button class="btn btn-primary btn-sm mb-3"><i class="fas fa-arrow-left"></i>  Kembali</button></a>
                  <button class="btn btn-primary btn-sm mb-3" data-toggle="modal" data-target="#add"><i class="fas fa-plus"></i></i>  Tambah</button>
                  <a href="{{ route('admin.barang.print_kode',['id'=>$idbarang])}}" target="_blank" ><button class="btn btn-primary btn-sm mb-3"><i class="fas fa-print"></i>   Print Kode</button></a>
                  <form action="">
                   <div class="row">
                     <div class="col-4">
                       <input type="text" class="form-control form-control-sm" name="q" placeholder="Kode Barang, Mac Address, Nama" >
                      </div>
                      <div class="col-4">
                       <select name="status" class="form-control form-control-sm" >
                        <option value="">All Status</option>
                        <option value="Belum Terpakai">Belum Terpakai</option>
                        <option value="Terpakai">Terpakai</option>
                       </select>
                      </div>
                    <div class="col-2">
                      <button type="submit" class="btn btn-dark btn-sm mb-3 btn-block">Cari</button>
                    </div>
                  </div>
                  
                  </form>
                  <div class="table-responsive">
                    <table class="display table table-striped table-hover text-nowrap" >  
                  <thead>
                    <tr>
                      @role('admin')
                      <td></td>
                          @endrole
                      <td>ID Barang</td>
                      <td>Id SubBarang</td>
                      <td>Kategori</td>
                      <td>Mac Address</td>
                      <td>Keterangan</td>
                      <td>Deskripsi</td>
                      <td>Jumlah</td>
                      <td>Terpakai</td>
                      <td>Stok</td>
                      <td>Nama Barang</td>
                      <td>Serial Number</td>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($SubBarang as $sub)
                    {{-- ----------------------------------------------------------MODAL ADD BARANG------------------------------------------------------- --}}
  <div class="modal fade" id="add" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Tambah Barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="post" action="{{ route('admin.barang.store_subbarang') }}" >
                @csrf
                @method('POST')
                <div class="form-row">
                <div class="col">
                    <label for="">ID Barang<strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control" value="{{$sub->id_barang}}" name="idbarang"  readonly>
                  </div>
                  <div class="col">
                    <label for="">Tanggal Masuk Barang<strong class="text-danger">*</strong></label>
                    <input type="date" class="form-control" name="subbarang_tgl_masuk" >
                  </div>
                  <div class="col">
                    <label for="">Supplier<strong class="text-danger">*</strong></label>
                    <input type="hidden" class="form-control" value="{{$sub->id_supplier}}" name="id_supplier"  readonly>
                    <input type="text" class="form-control" value="{{$sub->supplier_nama}}" readonly>
                  </div>
                  <div class="col">
                    <label for="">Kategori<strong class="text-danger">*</strong></label>
                    <select name="nama_kategori" id="" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategori as $d)
                            <option value="{{ $d->nama_kategori }}">{{ $d->nama_kategori }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        Kategori belum terpilih
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-4">
                    <label for="">Nama Barang<strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control" placeholder="Masukan Nama Barang" name="barang_nama" required>
                    <div class="invalid-feedback">
                        Nama barang tidak boleh kosong
                    </div>
                  </div>
                  <div class="col-2">
                    <label for="">Qty<strong class="text-danger">*</strong></label>
                    <input type="number" class="form-control" id="qty" name="qty" value="1" required>
                    <div class="invalid-feedback">
                        Qty barang tidak boleh kosong
                    </div>
                    
                  </div>
                  <div class="col">
                    <label for="">Harga<strong class="text-danger">*</strong></label>
                    <input type="number" class="form-control" id="harga" name="harga" value="0" required>
                    <div class="invalid-feedback">
                        Harga barang tidak boleh kosong
                    </div>
                  </div>
                  <div class="col">
                    <label for="">Total</label>
                    <input type="text" class="form-control" id="total" name="total" readonly>
                    <div class="invalid-feedback">
                        Total Harga tidak boleh kosong
                    </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
      </div>
    </div>
  </div>
               

   <!-- --------------------------------------------------------------------------------HAPUS BARANG--------------------------------------------------- -->
   <div class="modal fade" id="hapus{{ $sub->id_subbarang }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Edit Barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
            <div class="modal-body">
                <div>Anda akan menghapus data : {{$sub->subbarang_nama}}</div>
            </div>
            <div class="modal-footer">
              <form action="{{route('admin.barang.destroy_subbarang',['id'=>$sub->id_subbarang])}}" method="DELETE">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="submit" class="btn btn-primary">Hapus</button>
                </form>
            </div>
        </form>
      </div>
    </div>
  </div> 
                        <tr>
                          @role('admin')
                          <td><i class="fas fa-trash" data-toggle="modal" data-target="#hapus{{ $sub->id_subbarang }}"></i>&nbsp;&nbsp;</td>
                          @endrole
                          <td>{{$sub->subbarang_idbarang}}</td>
                          <td >{{$sub->id_subbarang}}</td>
                          <td>{{$sub->subbarang_ktg}}</td>
                          <td>{{$sub->subbarang_mac}}</td>
                          <td>{{$sub->subbarang_keterangan}}</td>
                          <td>{{$sub->subbarang_deskripsi}}</td>
                          <td>{{$sub->subbarang_qty}}</td>
                          <td>{{$sub->subbarang_keluar}}</td>
                          <td>{{$sub->subbarang_stok}}</td>
                          <td>{{$sub->subbarang_nama}}</td>
                          <td>{{$sub->subbarang_sn}}</td>
                        </tr>
                      @endforeach
                  </tbody>
                </table>
                <div class="pull-left">
                  Showing
                  {{$SubBarang->firstItem()}}
                  to
                  {{$SubBarang->lastItem()}}
                  of
                  {{$SubBarang->total()}}
                  entries
                </div>
                <div class="pull-right">
                  {{ $SubBarang->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
              </div>
              
            </div>
            
            </div>
            </div>
        </div>
      </div>
     <endsection>
<script type="text/javascript">
  $(document).ready(function(){
    $("#qty, #harga,#editharga,#editqty").keyup(function() {
            var harga  = $("#harga").val();
            var jumlah = $("#qty").val();

            var total = parseInt(harga) * parseInt(jumlah);
            if (isNaN(total)) {
                total = '0';
                }
            $("#total").val(total);
            var editharga  = $("#editharga").val();
            var editqty = $("#editqty").val();

            var edittotal = parseInt(editharga) * parseInt(editqty);
            if (isNaN(total)) {
              edittotal = '0';
                }
            $("#edittotal").val(edittotal);
});
});

</script>
@endsection
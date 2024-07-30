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
                  <div class="table-responsive">
                    <table id="input_data" class="display table table-striped table-hover text-nowrap" >  
                  <thead>
                    <tr>
                      <td>ID Barang</td>
                      <td>Nomor Transaksi</td>
                      <td>Id SubBarang</td>
                      <td>Nama Barang</td>
                      <td>Kategori</td>
                      <td>Jumlah</td>
                      <td>Terpakai</td>
                      <td>Stok</td>
                      <td>Harga Satuan</td>
                      <td>Serial Number</td>
                      <td>Mac Address</td>
                      <td>Keterangan</td>
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
                    <input type="number" class="form-control" id="harga" name="harga" value="" required>
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
                    {{-- ----------------------------------------------------------MODAL BARANG KELUAR------------------------------------------------------- --}}
  <div class="modal fade" id="input{{$sub->id_subbarang}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Input Barang Keluar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="post" action="{{ route('admin.barang.input_subbarang',['id'=>$sub->id_subbarang]) }}" >
                @csrf
                @method('PUT')
                <div class="form-row">
                <div class="col">
                    <label for="">ID Barang<strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control" value="{{$sub->id_barang}}" name="idbarang"  readonly>
                  </div>
                  <div class="col">
                    <label for="">Supplier<strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control" value="{{$sub->supplier_nama}}" readonly>
                  </div>
                  <div class="col">
                    <label for="">ID SubBarang<strong class="text-danger">*</strong></label>
                    <input type="text" name="id_subbarang" class="form-control" value="{{$sub->id_subbarang}}" readonly>
                    <div class="invalid-feedback">
                        Kategori belum terpilih
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-4">
                    <label for="">Serial Number</label>
                    <input type="text" class="form-control" name="sn" value="{{$sub->subbarang_sn}}" >
                  </div>
                  <div class="col-4">
                    <label for="">Mac Address</label>
                    <input type="text" class="form-control" name="mac" value="{{$sub->subbarang_mac}}" >
                  </div>
                  <div class="col">
                    <label for="">Keterangan<strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control" id="ket" name="ket" value="{{$sub->subbarang_keterangan}}" required>
                    <div class="invalid-feedback">
                        Keterangan tidak boleh kosong
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
                        <tr>
                          <td>{{$sub->subbarang_idbarang}}</td>
                          <td>{{$sub->id_trx}}</td>
                          <td class="text-bold text-primary" data-toggle="modal" data-target="#input{{$sub->id_subbarang}}">{{$sub->id_subbarang}}</td>
                          <td>{{$sub->subbarang_nama}}</td>
                          <td>{{$sub->subbarang_ktg}}</td>
                          <td>{{$sub->subbarang_qty}}</td>
                          <td>{{$sub->subbarang_keluar}}</td>
                          <td>{{$sub->subbarang_stok}}</td>
                          <td class="text-right">{{number_format($sub->subbarang_harga)}}</td>
                          <td>{{$sub->subbarang_mac}}</td>
                          <td>{{$sub->subbarang_sn}}</td>
                          <td>{{$sub->subbarang_keterangan}}</td>
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
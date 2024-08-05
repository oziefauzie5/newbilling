@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
      <div class="card">
          <div class="card-body">
          
          @role('admin')
            <form >
          <div class="row">
            <div class="col-6">
              <button class="btn btn-primary btn-sm mb-3 btn-block" type="button" data-toggle="modal" data-target="#add">Tambah Barang</button>
            </div>
            <div class="col-4">
              <input type="text" class="form-control form-control-sm" name="q" placeholder="Kode Barang, Mac Address," >
            </div>
            <div class="col-2">
              <button type="submit" class="btn btn-dark btn-sm mb-3 btn-block">Cari</button>
            </div>
          </div>
            </form>
          @endrole
          <hr>
           <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover text-nowrap" >                          <thead>
                          <tr class="text-center">
                            <th>#</th>
                            <th>ID Barang</th>
                            <th>Stok</th>
                            <th>Nomor Transaksi</th>
                            <th>Tgl Masuk</th>
                            <th>Supplier</th>
                          </tr>
                          </thead>
                          <tbody>
                          @foreach ($barang as $d)
                          <tr>
                            <td>
                                <i class="fas fa-trash" data-toggle="modal" data-target="#hapus{{ $d->id_barang }}"></i>&nbsp;&nbsp;
                                {{-- <button class="btn btn-danger" data-toggle="modal" data-target="#hapus{{ $d->id_barang }}"><i class="fas fa-trash"></i></button> --}}
                                {{-- <button class="btn" data-toggle="modal" data-target="#edit{{ $d->id_barang }}"><i class="fas fa-edit"></i></button> --}}
                                <i class="fas fa-edit" data-toggle="modal" data-target="#edit{{ $d->id_barang }}"></i>
                                <a href="{{ route('admin.barang.rekap_barang',['id'=>$d->id_barang])}}" class="btn"><i class="fas fa-print"></i></a>
                             </td>
                            <td class="text-center text-bold"><a href="{{ route('admin.barang.sub_barang',['id'=>$d->id_barang])}}">{{ $d->id_barang }}</a> </td>
                            <td class="text-center">{{ $d->total }}</td>
                            <td>{{ $d->id_trx }}</td>
                            <td>{{  date('d-m-Y', strtotime($d->barang_tgl_beli)); }}</td>
                            <td>{{ $d->supplier_nama }}</td>
                          </tr>
                      
                          {{-- -----------------------------------------------------------EDIT BARANG--------------------------------------------------------------- --}}
                              <div class="modal fade" id="edit{{ $d->id_barang }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="staticBackdropLabel">Edit Barang</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="{{ route('admin.barang.edit',['id'=>$d->id_barang]) }}" >
                                            @csrf
                                            @method('POST')
                                            <div class="form-row">
                                              <div class="col-3">
                                                <label for="">ID</label>
                                                <input type="text" class="form-control" value="{{ $d->id_barang }}" name="id_barang" readonly>
                                              </div>
                                              <div class="col">
                                                <label for="">Tanggal Pembelian<strong class="text-danger">*</strong></label>
                                                <input type="date" class="form-control" name="tgl_beli" value="{{$d->barang_tgl_beli}}">
                                              </div>
                                            <div class="col">
                                                <label for="">Nomor Transaksi</label>
                                                <input type="text" class="form-control" name="id_trx" value="{{$d->id_trx}}" required>
                                              </div>
                                              <div class="col">
                                                <label for="">Supplier<strong class="text-danger">*</strong></label>
                                                <select name="id_supplier" id="" class="form-control" required>
                                                    <option value="{{ $d->id_supplier }}">{{ $d->supplier_nama }}</option>
                                                    @foreach ($supplier as $item)
                                                        <option value="{{ $item->id_supplier }}">{{ $item->supplier_nama }}</option>
                                                    @endforeach
                                                </select>
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
                              <div class="modal fade" id="hapus{{ $d->id_barang }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="staticBackdropLabel">Edit Barang</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                        <div class="modal-body">
                                            <div>Anda akan menghapus data barang {{$d->barang_nama}}</div>
                                        </div>
                                        <div class="modal-footer">
                                          <form action="{{route('admin.barang.destroy',['id'=>$d->id_barang])}}" method="DELETE">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                                            <button type="submit" class="btn btn-primary">Hapus</button>
                                            </form>
                                        </div>
                                    </form>
                                  </div>
                                </div>
                              </div>               
                              @endforeach
                          </tbody>
                        </table>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>



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
            <button class="btn btn-primary btn-sm " data-toggle="modal" data-target="#addkategori">Buat Kategori</button>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addsupplier">Tambah Supplier</button>
            <hr>
            <form method="post" action="{{ route('admin.barang.store') }}" >
                @csrf
                @method('POST')
                <div class="form-row">
                <div class="col">
                    <label for="">Tanggal Pembelian<strong class="text-danger">*</strong></label>
                    <input type="date" class="form-control" name="tgl_beli"  required>
                  </div>
                <div class="col-3">
                    <label for="">ID</label>
                    <input type="text" class="form-control" name="id_trx" required>
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
                  <div class="col">
                    <label for="">Supplier<strong class="text-danger">*</strong></label>
                    <select name="id_supplier" id="" class="form-control" required>
                        <option value="">Pilih Supplier</option>
                        @foreach ($supplier as $d)
                            <option value="{{ $d->id_supplier }}">{{ $d->supplier_nama }}</option>
                        @endforeach
                    </select>
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
                    <input type="number" class="form-control" id="harga" name="harga" required>
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





{{-- ----------------------------------------------------------MODAL ADD KATEGORI------------------------------------------------------- --}}
  <div class="modal fade" id="addkategori" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{route('admin.kategori.store')}}" method="POST" class="needs-validation" novalidate>
            @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
                <div class="form-row">
                  <div class="col-md-6 mb-3">
                    <label>Id Kategori</label>
                    <input type="text" class="form-control" name="id_kategori" value="{{ $id_kategori }}" readonly>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Nama Kategori</label>
                    <select name="nama_kategori" id="" class="form-control" required>
                      <option value="">Pilih</option>
                      <option value="ONT">ONT</option>
                        <option value="DROPCORE">DROPCORE</option>
                        <option value="PACTCORE">PACTCORE</option>
                        <option value="TALI TIES">TALI TIES</option>
                        <option value="KLEM KABEL">KLEM KABEL</option>
                        <option value="ARMORE">ARMORE</option>
                        <option value="ADSS">ADSS</option>
                        <option value="SPLITER">SPLITER</option>
                        <option value="CLOUSUR">CLOUSUR</option>
                        <option value="BOX FAT">BOX FAT</option>
                        <option value="ADAPTOR">ADAPTOR</option>
                    </select>
                    <!-- <input type="text" class="form-control" name="nama_kategori" required> -->
                    <div class="invalid-feedback">
                      Nama kategori tidak boleh kosong
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
{{-- ----------------------------------------------------------MODAL ADD SUPPLIER------------------------------------------------------- --}}
  <div class="modal fade" id="addsupplier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{route('admin.supplier.store')}}" method="POST" class="needs-validation" novalidate>
            @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Supplier</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label>Id Supplier</label>
                    <input type="text" class="form-control" name="id_supplier" value="{{ $id_supplier }}" readonly>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Nama Supplier</label>
                    <input type="text" class="form-control" name="supplier_nama" required>
                    <div class="invalid-feedback">
                        Nama Supplier tidak boleh kosong
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 mb-3">
                  <label>Tlpon</label>
                  <input type="text" class="form-control" name="supplier_tlp" required>
                  <div class="invalid-feedback">
                    Tlpon Supplier tidak boleh kosong
                  </div>
                </div>
                </div>
            <div class="form-row">
                    <div class="col-md-12 mb-3">
                      <label>Alamat</label>
                      <input type="text" class="form-control" name="supplier_alamat"  required>
                      <div class="invalid-feedback">
                            Alamat Supplier tidak boleh kosong
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
 
@endsection

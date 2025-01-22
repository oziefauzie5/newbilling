@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
      <div class="card">
          <div class="card-body">
          
            <form >
              <div class="row">
            @role('admin|STAF ADMIN')
            <div class="col">
              <button class="btn btn-primary btn-sm mb-3 btn-block" type="button" data-toggle="modal" data-target="#add">Tambah Barang</button>
            </div>
            <div class="col">
              <button class="btn btn-primary btn-sm mb-3 btn-block" type="button" data-toggle="modal" data-target="#addkategori">Buat Kategori</button>            
            </div>
            @endrole
            <div class="col">
              <a href="{{route('admin.gudang.stok_gudang')}}"><button class="btn btn-primary btn-sm mb-3 btn-block" type="button" >Stok Barang</button></a>
              
            </div>
            <div class="col">
              <a href="{{route('admin.gudang.barang_keluar')}}"><button class="btn btn-primary btn-sm mb-3 btn-block" type="button" >Barang Keluar</button></a>
              
            </div>
          </div>
            </form>
          <hr>
           <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover text-nowrap" >                          
              <thead>
            <tr class="text-center">
              {{-- <th>#</th> --}}
              <th>ID</th>
              <th>Kategori</th>
              <th>Nama</th>
              <th>Merek</th>
              <th>Stok Awal</th>
              <th>Satuan</th>
              <th>SN</th>
              <th>Mac Address</th>
              <th>Keterangan</th>
              <th>Status</th>
              <th>Nama Pengguna Perangkat</th>
              <th>Tanggal Terima</th>
              <th>Penerima</th>
              <th>Data diperbarui oleh</th>
              <th>Tanggal data diperbarui</th>
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
               <td>{{ $d->barang_id }}</td>
               <td>{{ $d->barang_kategori }}</td>
               <td>{{ $d->barang_nama }}</td>
               <td>{{ $d->barang_merek }}</td>
               <td>{{ $d->barang_qty }}</td>
               <td>{{ $d->barang_satuan }}</td>
               <td>{{ $d->barang_sn }}</td>
               <td>{{ $d->barang_mac }}</td>
               <td>{{ $d->barang_ket }}</td>
               <td>@if($d->barang_status == 0)Bagus, normal dan bisa digunakan @elseif($d->barang_status == 1) Barang Normal dan telah digunakan @elseif($d->barang_status == 4) Tidak bagus, rusak dan tidak bisa digunakan @elseif($d->barang_status == 5)Barang belum dicek @endif</td>
               <td>{{ $d->barang_nama_pengguna }}</td>
               <td>{{  date('d-m-Y', strtotime($d->barang_tglmasuk)); }}</td>
               <td>{{ $d->barang_penerima }}</td>
               <td>{{ $d->barang_admin_update }}</td>
               <td>{{  date('d-m-Y H:m:s', strtotime($d->updated_at)); }}</td>
            </tr>
        
            {{-- -----------------------------------------------------------EDIT BARANG--------------------------------------------------------------- --}}
                <div class="modal fade" id="edit{{ $d->barang_id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Edit Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                          
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                              <button type="submit" class="btn btn-primary">Simpan</button>
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
            <form method="post" action="{{ route('admin.gudang.store_barang') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="form-row">
                <div class="col-3">
                    <label for="">ID Barang</label>
                    <input type="text" class="form-control" name="barang_id" value="{{mt_rand(1000000,9999999)}}" required>
                  </div>
                  <div class="col">
                    <label for="">Kategori<strong class="text-danger">*</strong></label>
                    <select name="barang_kategori" id="" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategori as $d)
                            <option value="{{ $d->nama_kategori }}">{{ $d->nama_kategori }}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col">
                    <label for="">Jenis Barang<strong class="text-danger">*</strong></label>
                    <select name="barang_jenis" id="" class="form-control" required>
                        <option value="">Jenis Barang</option>
                        <option value="Umum">Umum</option>
                        <option value="Teknikal">Teknikal</option>
                    </select>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col">
                      <label for="">Lokasi<strong class="text-danger">*</strong></label>
                      <select name="barang_lokasi" id="" class="form-control" required>
                        <option value="">- Pilih Gudang -</option>
                        <option value="1">Ciomas</option>
                      </select>
                    </div>
                    <div class="col">
                      <label for="">Tanggal Terima<strong class="text-danger">*</strong></label>
                      <input type="text"  class="form-control datepicker" name="barang_tglmasuk"  required>
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="col">
                      <label for="">Jumlah Barang<strong class="text-danger">*</strong></label>
                      <input type="number" class="form-control" id="" name="barang_qty" value="1" required>
                    </div>
                    <div class="col">
                      <label for="">Satuan<strong class="text-danger">*</strong></label>
                      <select name="barang_satuan" id="" class="form-control" required>
                        <option value="">- Satuan -</option>
                        <option value="Pcs">Pcs</option>
                        <option value="Roll">Roll</option>
                        <option value="Pack">Pack</option>
                        <option value="Meter">Meter</option>
                        <option value="Kg">Kg</option>
                      </select>
                    </div>
                    </div>
                <div class="form-row">
                  <div class="col-4">
                    <label for="">Nama Barang<strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control" placeholder="Masukan Nama Barang" name="barang_nama" required>
                  </div>
                  <div class="col-4">
                    <label for="">Merek & Type <strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control" placeholder="Masukan Nama Barang" name="barang_merek" required>
                  </div>
                
                  <div class="col">
                    <label for="">Harga<strong class="text-danger">*</strong></label>
                    <input type="number" class="form-control" id="" name="barang_harga" value="0" required>
                </div>
            </div>
            
            <div class="form-row">
              <div class="col">
                <label for="">Status<strong class="text-danger">*</strong></label>
                <select name="barang_status" id="" class="form-control" required>
                  <option value="">- Status -</option>
                  <option value="0">Bagus, normal dan bisa digunakan</option>
                  <option value="4">Tidak bagus, rusak dan tidak bisa digunakan</option>
                </select>
              </div>   
              </div>   
              <div class="form-row">
                <div class="col">
                  <label for="">Keterangan</label>
                  <textarea name="barang_ket" class="form-control" cols="30" rows="3" required></textarea>
                </div>   
                </div>
              <div class="form-row">           
              <div class="col">
                <label for="">Foto Tanda Terima<strong class="text-danger">*</strong></label>
                <input  type="file" class="form-control-file" name="barang_img" required>
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
        <form action="{{route('admin.gudang.store_kategori')}}" method="POST" class="needs-validation" novalidate>
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
                        <option value="PIGTAIL">PIGTAIL</option>
                        <option value="SPLICER">SPLICER</option>
                        <option value="CLIPPER">CLIPPER</option>
                        <option value="STRIPPER">STRIPPER</option>
                        <option value="TALI TIES">TALI TIES</option>
                        <option value="SLITTER">SLITTER</option>
                        <option value="OLT">OLT</option>
                        <option value="OTB">OTB</option>
                        <option value="ROUTER">ROUTER</option>
                        <option value="SWITCH">SWITCH</option>
                        <option value="LAN">LAN</option>
                        <option value="RG45">RG45</option>
                        <option value="TESTER LAN">TESTER LAN</option>
                        <option value="TANG KRIMPING">TANG KRIMPING</option>
                        <option value="KLEM KABEL">KLEM KABEL</option>
                        <option value="ARMORE">ARMORE</option>
                        <option value="ADSS">ADSS</option>
                        <option value="MINI ADSS">MINI ADSS</option>
                        <option value="SPLITER 1:2">SPLITER 1:2</option>
                        <option value="SPLITER 1:4">SPLITER 1:4</option>
                        <option value="SPLITER 1:8">SPLITER 1:8</option>
                        <option value="SPLITER 1:16">SPLITER 1:16</option>
                        <option value="BOX ODP 1:8">BOX ODP 1:8</option>
                        <option value="BOX ODP 1:16">BOX ODP 1:16</option>
                        <option value="BOX ODP 1:24">BOX ODP 1:24</option>
                        <option value="CLOUSUR">CLOUSUR</option>
                        <option value="ADAPTOR">ADAPTOR</option>
                        <option value="PROTECTOR">PROTECTOR</option>
                        <option value="STEKER">STEKER</option>
                        <option value="ADAPTOR SC/UPC">ADAPTOR SC/UPC</option>
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
 
@endsection

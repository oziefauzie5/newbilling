@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0" ><span>Rp. {{number_format($kredit)}}</span> </div>
            <div class="text-muted mb-3">KREDIT</div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0"><span>Rp. {{number_format($debet)}}</span></div>
            <div class="text-muted mb-3">DEBET</div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0"><span>Rp. {{number_format($kredit-$debet)}}</span></div>
            <div class="text-muted mb-3">SALDO</div>
          </div>
        </div>
      </div>
    </div>

    
   
    <div class="row">
      
      <div class="card">
        <div class="card-body">
          @if ($errors->any())
          <div class="alert alert-danger">
            <div class="alert-title"><h4>Gagal!!</h4></div>
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
          </div> 
        @endif
        <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Reimburse Karyawan
</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#TopUp">
  TopUp
</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pengeluaran">
  Pengeluaran
</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pencairan">
  Pencairan
</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fee_Seles">
Pencairan Fee
</button>
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#kasbon">
  Kasbon
</button> -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tutup_buku">
  Tutup Buku Mingguan
</button>
<a href="{{route('admin.lap.data_laporan_mingguan')}}"><button type="button" class="btn btn-primary"> Data Laporan Mingguan</button></a>
<hr>
<h5 class="text-danger">CATATAN</h5>
<ul>
                     <li>Reimburse dilakukan Perminggu setiap hari Sabtu dan Tanggal terakhir pada akhir bulan. </li>
                     <li >Reimburse dapat dicairkan apabila karyawan mereimburse pada bulan yang sama dengan Nota.</li>
                 </ul>

<hr>

<!-- Modal Tutup Bukui-->
<div class="modal fade" id="tutup_buku" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">TUTUP BUKU MINGGUAN</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.lap.jurnal_tutup_buku')}}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label>Dari tanggal</label>
             <input type="date" class="form-control" name="startdate">
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Sampai tanggal</label>
              <input type="date" class="form-control" name="enddate">
            </div>
          </div>
          {{-- <div class="col-sm-12">
            <div class="form-group">
              <label>Upload Bukti</label>
            <input type="file" name="file" class="form-control" required>
            </div>
          </div> --}}
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal transfer-->

<!-- Modal PENGELUARAN-->
<div class="modal fade" id="pengeluaran" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">PENGELUARAN</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.lap.store_jurnal_pengeluaran')}}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label>Jenis Pengeluaran</label>
              <select class="form-control" name="jenis">
                <option value="">Pilih Jenis Pengeluaran</option>
                <option value="KOMSUMSI">KOMSUMSI</option>
                <option value="PERLENGKAPAN">PERLENGKAPAN</option>
                <option value="IKLAN & PROMOSI">IKLAN & PROMOSI</option>
                <option value="PERALATAN">PERALATAN</option>
                <option value="ATK">ATK</option>
                <option value="LISTRIK">LISTRIK</option>
                <option value="DONASI">DONASI</option>
                <option value="ENTERTIMENT">ENTERTIMENT</option>
                <option value="PERBAIKAN & PERAWATAN">PERBAIKAN & PERAWATAN</option>
                <option value="KEAMANAN DAN KEBERSIHAN">KEAMANAN DAN KEBERSIHAN</option>
                <option value="IURAN & BERLANGGANAN">IURAN & BERLANGGANAN</option>
                <option value="SEWA BANGUNAN">SEWA BANGUNAN</option>
                <option value="SEWA KENDARAAN">SEWA KENDARAAN</option>
                <option value="INFRASTRUKTUR">INFRASTRUKTUR</option>
                <option value="PERALATAN KANTOR">PERALATAN KANTOR</option>
                <option value="BIAYA ADMIN">BIAYA ADMIN</option>
                <option value="LAIN-LAIN">LAIN-LAIN</option>
              </select>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Uraian</label>
              <input type="text" class="form-control" value="" name="uraian" required>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Qty</label>
              <input type="number" class="form-control" value="1" name="qty" required>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Jumlah</label>
            <input type="number" class="form-control" value="0" name="jumlah" required>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Upload Bukti</label>
            <input type="file" name="file" class="form-control" required>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal fee_Seles-->
<div class="modal fade" id="fee_Seles" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">PENCAIRAN FEE SALES</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.lap.store_jurnal_fee_sales')}}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')
        
        <div class="row">
          <div class="col-sm-12">
            <div class="row">
              <div class="col">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h2 m-0 jt" id="pencairan_total_fee">0</div>
                    <div class="text-muted mb-3">JUMLAH PENCAIRAN</div>
                  </div>
                </div>
              </div>
              </div>
            <div class="table-responsive">
              <table class="display table table-striped table-hover text-nowrap" >
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Sales</th>
                      <th>Pelanggan</th>
                      <th>Fee</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($data_fee_sales as $d)
                    <tr>
                      <td> <input type="checkbox" class="pencairan_fee" name="id[]" value="{{$d->id}}" data-sales_id="{{$d->id_user}}"data-pelanggan="{{$d->smt_deskripsi}}" data-count_fee="1" data-fee="{{$d->smt_kredit}}"></td>
                      <td>{{$d->nama_user}}</td>
                      <td>{{$d->smt_deskripsi}}</td>
                      <td>{{$d->smt_kredit}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
          </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Uraian</label>
              <textarea class="form-control" name="desk" id="desk" cols="30" rows="5"></textarea>
            {{-- <input type="text" class="form-control" id="desk" value="" name="desk" required readonly> --}}
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>SALES ID</label>
            <input type="text" class="form-control" id="sales_id" value="0" name="user" required readonly>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>Penerima</label>
              <select name="penerima_fee" id="" class="form-control penerima" required>
                <option value="">PILIH PENERIMA</option>
              @foreach($user as $u)
              <option value="{{$u->id}}">{{$u->name}}</option>
              @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>FEE</label>
            <input type="number" class="form-control" id="fee" value="0" name="fee" required readonly>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>QTY</label>
            <input type="number" class="form-control" id="count_fee" value="0" name="count_fee" required readonly>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>TOTAL</label>
            <input type="number" class="form-control" id="total_komisi" value="0" name="total_komisi" required readonly>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Upload Bukti</label>
            <input type="file" name="file" class="form-control" required>
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Pencairan-->
<div class="modal fade" id="pencairan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">PENCAIRAN OPERASIONAL</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.lap.store_jurnal_pencairan')}}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')
        
        <div class="row">
          <div class="col-sm-12">
            <div class="row">
              <div class="col">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h2 m-0 jt" id="pencairan_total">0</div>
                    <div class="text-muted mb-3">JUMLAH PENCAIRAN</div>
                  </div>
                </div>
              </div>
              </div>
            <div class="table-responsive">
              <table class="display table table-striped table-hover text-nowrap" >
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>No Layanan</th>
                      <th>Nama</th>
                      <th>Alamat</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($data_registrasi as $d)
                    <tr>
                      <td> <input type="checkbox" class="jurnal_pencairan" name="idpel[]" value="{{$d->reg_idpel}}" data-nama="{{$d->input_nama}}" data-psb="{{$data_biaya->biaya_psb}}"data-cpsb="1" data-sales="@if($d->reg_fee > 0) 0 @else  {{$data_biaya->biaya_sales}} @endif"data-csales="@if($d->reg_fee > 0) 0 @else  1 @endif"></td>
                      <td>{{$d->reg_nolayanan}}</td>
                      <td>{{$d->input_nama}}</td>
                      <td>{{$d->input_alamat_pasang}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
          </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Uraian</label>
            <input type="text" class="form-control" id="uraian" value="" name="uraian" required readonly>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Penerima</label>
              <select name="penerima" id="" class="form-control penerima" required>
                <option value="">PILIH PENERIMA</option>
              @foreach($user as $u)
              <option value="{{$u->id}}">{{$u->name}}</option>
              @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label>PSB</label>
            <input type="number" class="form-control" id="psb" value="0" name="psb" required readonly>
            </div>
          </div>
          <div class="col-sm-2">
            <div class="form-group">
              <label>QTY</label>
            <input type="number" class="form-control" id="cpsb" value="0" name="cpsb" required readonly>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label>SALES</label>
            <input type="number" class="form-control" id="sales" value="0" name="sales" required readonly>
            </div>
          </div>
          <div class="col-sm-2">
            <div class="form-group">
              <label>QTY</label>
            <input type="number" class="form-control" id="csales" value="0" name="csales" required readonly>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>TOTAL</label>
            <input type="number" class="form-control" id="jumlah" value="0" name="jumlah" required readonly>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Upload Bukti</label>
            <input type="file" name="file" class="form-control" required>
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Kasbon-->
<div class="modal fade" id="kasbon" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">KASBON KARYAWAN</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.lap.store_jurnal_kasbon')}}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label>Jenis Kasbon</label>
              <select class="form-control" name="jenis">
                <option value="">Pilih Jenis Kasbon</option>
                <option value="Berjangka">Berjangka</option>
                <option value="Tidak Berjangka">Tidak Berjangka</option>
              </select>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Uraian</label>
              <input type="text" class="form-control" name="uraian" required>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Tempo</label>
              <input type="number" class="form-control" value="1" max="6" name="tempo" required>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Penerima</label>
              <select class="form-control" name="penerima" required>
                <option value="">Pilih Penerima</option>
                @foreach ($user as $u)
                    <option value="{{$u->id}}">{{$u->name}}</option>
                @endforeach
              </select>
            </div>
          </div>   
          <div class="col-sm-12">
            <div class="form-group">
              <label>Jumlah</label>
            <input type="number" class="form-control" value="0" name="jumlah" required>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Upload Bukti</label>
            <input type="file" name="file" class="form-control" required>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Reimburse-->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">REIMBURSE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.lap.store_jurnal_reimbuse')}}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label>Jenis Reimburse</label>
              <select class="form-control" name="jenis">
                <option value="">Pilih Jenis Reimburse</option>
                <option value="Bensin">Bensin</option>
                <option value="Service">Service</option>
                <option value="Sewa">Sewa</option>
              </select>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Plat Nomor</label>
              <select class="form-control" name="plat_kendaraan" required>
                <option value="">Pilih Nomor Kendaraan</option>
                @foreach ($kendaraan as $d)
                    <option value="{{$d->trans_plat_nomor}}">{{$d->trans_plat_nomor}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Penerima</label>
              <select class="form-control" name="penerima" required>
                <option value="">Pilih Penerima</option>
                @foreach ($user as $u)
                    <option value="{{$u->id}}">{{$u->name}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Qty</label>
            <input type="number" class="form-control" value="" name="qty" required>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Jumlah</label>
            <input type="number" class="form-control" value="0" name="jumlah" required>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Upload Bukti</label>
            <input type="file" name="file" class="form-control" required>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal TopUp-->
<div class="modal fade" id="TopUp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">TAMBAH PEMASUKAN</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.lap.store_topup_jurnal')}}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label>Uraian</label>
              <input type="text" class="form-control" value="" name="uraian" required>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Jumlah</label>
            <input type="number" class="form-control" value="0" name="jumlah">
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Upload Bukti</label>
            <input type="file" name="file" class="form-control" required>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </form>
      </div>
    </div>
  </div>
</div>
<form >
<div class="row mb-1">

                <div class="col">
                  <select name="kategori" class="custom-select custom-select-sm">
                    @if($kategori)
                    <option value="{{$kategori}}" selected>{{$kategori}}</option>
                    @endif
                    <option value="">ALL DATA</option>
                <option value="PSB">PSB</option>
                <option value="MARKETING">MARKETING</option>
                <option value="KOMSUMSI">KOMSUMSI</option>
                <option value="IKLAN & PROMOSI">IKLAN & PROMOSI</option>
                <option value="PERLENGKAPAN">PERLENGKAPAN</option>
                <option value="PERALATAN">PERALATAN</option>
                <option value="ATK">ATK</option>
                <option value="LISTRIK">LISTRIK</option>
                <option value="DONASI">DONASI</option>
                <option value="ENTERTEMENT">ENTERTEMENT</option>
                <option value="PERBAIKAN & PERAWATAN">PERBAIKAN & PERAWATAN</option>
                <option value="KEAMANAN DAN KEBERSIHAN">KEAMANAN DAN KEBERSIHAN</option>
                <option value="IURAN & BERLANGGANAN">IURAN & BERLANGGANAN</option>
                <option value="SEWA BANGUNAN">SEWA BANGUNAN</option>
                <option value="INFRASTRUKTUR">INFRASTRUKTUR</option>
                <option value="PERALATAN KANTOR">PERALATAN KANTOR</option>
                <option value="BIAYA ADMIN">BIAYA ADMIN</option>
                <!-- <option value="LAIN-LAIN">LAIN-LAIN</option> -->
                  </select>
                </div>
                <div class="col">
                  <input type="text" name="q" class="form-control form-control-sm" value="{{$q}}" placeholder="Cari Data">
                </div>
                </div>
                <div class="row mb-1">
                <div class="col">
                  <input name="bulan" type="month" class="form-control form-control-sm"></input>
                </div>
                  <div class="col">
                   <input type="date" name="start" class="form-control form-control-sm" value="{{$start}}" placeholder="Cari Data">
                  </div>
                  <div class="col">
                   <input type="date" name="end" class="form-control form-control-sm" value="{{$end}}" placeholder="Cari Data">
                  </div>
                  <div class="col">
                    <button type="submit" class="btn btn-block btn-dark btn-sm">Cari
                  </div>
                  </form>
                  </div>
          <div class="table-responsive">
          <table class="display table table-striped table-hover text-nowrap" >
              <thead>
                <tr>
                  <th>#</th>
               
                  <th>BUKTI TRX</th>
                  <th>TANGGAL</th>
                  <th>KATEGORI</th>
                  <th>KETERANGAN</th>
                  <th>URAIAN</th>
                  <th>QTY</th>
                  <th>KREDIT</th>
                  <th>DEBET</th>
                  <th>SALDO</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($jurnal as $d)
                <tr>
                  <td>{{$loop->iteration}}</td>
                      
                      <td>   <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#bukti_trx{{$d->jurnal_id}}">
                       File
                      </button>
                      <!-- Modal -->
                      <div class="modal fade" id="bukti_trx{{$d->jurnal_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                       <div class="modal-dialog">
                         <div class="modal-content">
                           <div class="modal-header">
                             <h5 class="modal-title" id="exampleModalLabel">Bukti Transfer</h5>
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                             </button>
                           </div>
                           <div class="modal-body">
                           <img class="rounded mx-auto d-block" src="{{ asset('storage/bukti-transaksi/'.$d->jurnal_img) }}" width="300" alt="">
                           </div>
                           <div class="modal-footer">
                             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                             @if($d->jurnal_img)
                             <a href="{{route('admin.lap.download_file',['id'=>$d->jurnal_img])}}"><button class="btn btn-secondary">Download</button></a>
                             @else
                             <button class="btn btn-secondary" disabled>Download</button>
                             @endif
                           </div>
                         </div>
                       </div>
                     </div>
                     <!-- ---end modal---- -->
                    </td>
                   
                    <td>{{date('d-m-Y H:m:s',strtotime($d->created_at))}}</td>
                      <td>{{$d->jurnal_kategori}}</td>
                      <td>{{$d->jurnal_keterangan}}</td>
                      <td>{{$d->jurnal_uraian}}</td>
                      <td>{{$d->jurnal_qty}}</td>
                      <td>{{number_format($d->jurnal_kredit)}}</td>
                      <td>{{number_format($d->jurnal_debet)}}</td>
                      <td>{{number_format($d->jurnal_saldo)}}</td>
                    </tr>
                    @endforeach
              </tbody>
            </table>
            <div class="pull-left">
              Showing
              {{$jurnal->firstItem()}}
              to
              {{$jurnal->lastItem()}}
              of
              {{$jurnal->total()}}
              entries
            </div>
            <div class="pull-right">
              {{ $jurnal->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0">Rp. {{number_format($kredit)}}</div>
            <div class="text-muted mb-3">PENDAPATAN</div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0">Rp. {{number_format($debet)}}</div>
            <div class="text-muted mb-3">PENGELUARAN</div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0">Rp. {{number_format($kredit-$debet)}}</div>
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
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pendapatan">
  Tambah Pemasukan
</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pengeluaran">
  Pengeluaran
</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#kasbon">
  Kasbon
</button>
<hr>

<!-- Modal Reimburse-->
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
        <form action="{{route('admin.lap.store_jurnal_reimbuse')}}" method="POST" enctype="multipart/form-data">
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
                <option value="PERALATAN KANTOR">PERALATAN KANTOR</option>
                <option value="ATK">ATK</option>
                <option value="LISTRIK">LISTRIK</option>
                <option value="KEAMANAN DAN KEBERSIHAN">KEAMANAN DAN KEBERSIHAN</option>
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
              <label>Metode Pembayaran</label>
              <select class="form-control" name="metode" required>
                <option value="">Pilih Metode</option>
                @foreach ($setting_akun as $a)
                    <option value="{{$a->id}}">{{$a->akun_nama}}</option>
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
              <label>Metode Pencairan</label>
              <select class="form-control" name="metode" required>
                <option value="">Pilih Metode</option>
                @foreach ($setting_akun as $a)
                    <option value="{{$a->id}}">{{$a->akun_nama}}</option>
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
              <label>Metode Pencairan</label>
              <select class="form-control" name="metode" required>
                <option value="">Pilih Metode</option>
                @foreach ($setting_akun as $a)
                    <option value="{{$a->id}}">{{$a->akun_nama}}</option>
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
<!-- Modal pemasukan-->
<div class="modal fade" id="pendapatan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">TAMBAH PEMASUKAN</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.lap.store_add_jurnal')}}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label>Jenis Pemasukan</label>
              <select class="form-control" name="jenis" required>
                <option value="">Pilih Jenis Pemasukan</option>
                <option value="VOUCHER">VOUCHER</option>
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
              <label>Metode Pembayaran</label>
              <select class="form-control" name="metode">
                <option value="">Pilih Metode</option>
                @foreach ($setting_akun as $a)
                    <option value="{{$a->id}}">{{$a->akun_nama}}</option>
                @endforeach
              </select>
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
                    <option value="PENDAPATAN">PENDAPATAN</option>
                    <option value="REIMBURSE">REIMBURSE</option>
                    <option value="PINJAMAN KARYAWAN">PINJAMAN KARYAWAN</option>
                    <option value="KOMSUMSI">KOMSUMSI</option>
                    <option value="PERLENGKAPAN">PERLENGKAPAN</option>
                    <option value="PERALATAN KANTOR">PERALATAN KANTOR</option>
                    <option value="ATK">ATK</option>
                    <option value="LISTRIK">LISTRIK</option>
                    <option value="KEAMANAN DAN KEBERSIHAN">KEAMANAN DAN KEBERSIHAN</option>
                    <option value="LAIN-LAIN">LAIN-LAIN</option>
                  </select>
                </div>
                <div class="col">
                  <input name="bulan" type="month" class="form-control form-control-sm"></input>
                </div>
                  <div class="col">
                   <input type="text" name="q" class="form-control form-control-sm" value="{{$q}}" placeholder="Cari Data">
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
                  <th>TANGGAL</th>
                  <th>KATEGORI</th>
                  <th>URAIAN</th>
                  <th>AKUN</th>
                  <th>KREDIT</th>
                  <th>DEBET</th>
                  <th>BUKTI TRX</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($jurnal as $d)
                <tr>
                  <td>{{$loop->iteration}}</td>
                      <td>{{date('d-m-Y',strtotime($d->tgl_trx))}}</td>
                      <td>{{$d->jurnal_kategori}}</td>
                      <td>{{$d->jurnal_uraian}}</td>
                      <td>{{$d->akun_nama}}</td>
                      <td>{{number_format($d->jurnal_kredit)}}</td>
                      <td>{{number_format($d->jurnal_debet)}}</td>
                      <td>   <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#bukti_trx{{$d->jurnal_id}}">
                        Lihat Bukti Transfer
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
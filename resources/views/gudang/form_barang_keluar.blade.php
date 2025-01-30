@extends('layout.main')
@section('content')

<style>
  hr{
    border: none;
  height: 1px;
  /* Set the hr color */
  color: #161616;  /* old IE */
  background-color: #959292;  /* Modern Browsers */
  }
  span{
    font-size: 11px;
    color:rgb(255, 0, 0);
  }
  ul{
    font-size: 12px;
    color:rgb(255, 0, 0);
  }
</style>

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
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
          <h3 class="mt-3 text-bolt text-center">FORM BARANG KELUAR</h3>

        <form class="form-horizontal"action="{{route('admin.gudang.proses_form_barang_keluar')}}" method="POST">
          @csrf
          @method('POST')
              <div class="form-group row">
                  <label class="col-sm-2 col-form-label" >Tipe Laporan</label>
                <div class="col-sm-4 notif1">
                  <select class="form-control" required id="tiket_type" name="tiket_type">
                    <option value="">- Pilih -</option>
                    <option value="General">General</option>
                    <option value="Project">Project</option>
                  </select>
                </div>
                  <label class=" col-sm-2 col-form-label">Site Id</label>
                <div class="col-sm-4 ">
                  <input type="text" id="tiket_site" name="tiket_site" class="form-control readonly" value="{{Session::get('data_site')}}" >
                </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-2 col-form-label" >Jenis Laporan</label>
                <div class="col-sm-4 notif1">
                    <select class="form-control" required id="bk_jenis_laporan" name="bk_jenis_laporan">
                      <option value="">- Pilih -</option>
                      <option value="Gangguan / Komplain">Gangguan / Komplain</option>
                      <option value="Edukasi">Edukasi</option>
                      <option value="Instalasi">Instalasi</option>
                      <option value="Instalasi Hotspot">Instalasi Hotspot</option>
                      <option value="Aktivasi">Aktivasi</option>
                      <option value="Upgrade">Upgrade</option>
                      <option value="Maintenance">Maintenance</option>
                      <option value="Pindah Alamat">Pindah Alamat</option>
                      <option value="Penarikan Kabel">Penarikan Kabel</option>
                      <option value="Dismentle Perangkat">Dismentle Perangkat</option>
                    </select>                  
                </div>
                  <label class=" col-sm-2 col-form-label">Tanggal Keluar</label>
                <div class="col-sm-4 ">
                  <input type="text" id="bk_waktu_keluar" name="bk_waktu_keluar" class="form-control " value="{{date('Y-m-d h:s')}}" >
                </div>
              </div>
              <div class="form-group row ">
                <label class="col-sm-2 col-form-label">Penerima Barang</label>
                <div class="col-sm-4 notif1">
                  <select class="form-control" name="bk_penerima" id="bk_penerima">
                    <option value="">- Pilih -</option>
                    @foreach ($data_user as $u)
                    <option value="{{$u->id}}">{{$u->name}}</option>
                    @endforeach
                  </select>
                </div>
                <label class="col-sm-2 col-form-label">Admin Id</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control readonly" id="id_admin" value="{{$id_admin}}" name=""  >
                </div>
              </div>
             
              <div class="form-group row">
                  <label for="" class="col-sm-2 col-form-label">Kode barang</label>
                  <div class="col-sm-3 notif_barang_id">
                    <input type="text" class="form-control " id="barang_id">
                    <div class="pesan_barang_id"></div>
                    {{-- <input type="text" class="form-control modal_cari"> --}}
                  </div>
                  <label for="" class="col-sm-2 col-form-label">Jumlah Barang</label>
                  <div class="col-sm-2 notif_jumlah">
                    <input type="number" class="form-control " value="0" id="jumlah_barang" min="1" max="">
                    <div class="pesan_jumlah"></div>
                  </div>
                  <div class="col-sm-2">
                    <button  type="button" class="btn btn-danger  button_masukan">Tambah barang</button>
                  </div>
              </div>
              <div class="form-group row">
                  <label for="" class="col-sm-2 col-form-label">Keperluan</label>
                  <div class="col-sm-10 notif1">
                    <textarea name="bk_keperluan" class="form-control"  id="bk_keperluan" cols="30" rows="5"></textarea>
                  </div>
              </div>
              <div class="form-group row">
                <div class="table-responsive">
                <table id="t" class="display table  table-sm table-striped table-hover text-center">
                  <thead>
                    <tr>
                      <th>Kode Barang</th>
                      <th>Kategori</th>
                      <th>Nama Barang</th>
                      <th>Merek</th>
                      <th>Harga</th>
                      <th>Stok Awal</th>
                      <th>Digunakan</th>
                      <th>Stok Akhir</th>
                      <th>Terpakai Sebelumnya</th>
                      <th>jumlah</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                    </tr>
                  </tbody>
              </table>
              </div>
              </div>
              
              <div class="card-footer">
                <a href="{{route('admin.gudang.stok_gudang')}}"><button type="button" class="btn  ">Batal</button></a>
                <button type="button" class="btn btn-primary float-right simpan">Simpan</button>
                </div>
            </form>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>


@endsection



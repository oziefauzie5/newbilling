@extends('layout.main')
@section('content')

<style>
  hr {
    border: none;
    height: 1px;
    /* Set the hr color */
    color: #161616;
    /* old IE */
    background-color: #959292;
    /* Modern Browsers */
  }

  span {
    font-size: 11px;
    color: rgb(255, 0, 0);
  }

  ul {
    font-size: 12px;
    color: rgb(255, 0, 0);
  }
</style>

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          @if ($errors->any())
          <div class="alert alert-danger">
            <div class="alert-title">
              <h4>Gagal!!</h4>
            </div>
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          <h3 class="mt-3 text-bolt text-center">FORM BARANG KELUAR</h3>
          <form class="form-horizontal" action="{{route('admin.gudang.proses_form_barang_keluar')}}" method="POST">
            @csrf
            @method('POST')

            {{-- MODAL CARI BARANG  --}}
            <div class="modal fade" id="modal_barang" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                  <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="staticBackdropLabel">Cari Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col">
                        <div class="card">
                          <div class="card-body p-3 text-center">
                            <div class="h2 m-0 jt" id="jumlah_barang">0</div>
                            <div class="text-muted mb-3">JUMLAH PENCAIRAN</div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="table-responsive-sm">
                      <table class="display text-nowrap table-striped table-hover text-center">
                        {{-- <table id="cari_kode_barang"  class="display text-nowrap table-striped table-hover text-center" > --}}
                        <thead>
                          <tr>
                            <th>Aksi</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Jumlah</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($data_barang as $d)
                          <tr>
                            <td><input type="checkbox" class="select_barang" value="{{$d->barang_id}}" data-fee="5000"></td>
                            <td width="20%"><input type="text" style="border:0;outline:0;" class="form-control text-center readonly " name="bk_id_barang[]" value="{{$d->barang_id}}"></td>
                            <td width="30%"><input type="text" style="border:0;outline:0;" class="form-control text-center readonly " name="bk_harga_barang[]" value="{{$d->barang_nama}}"></td>
                            <td width="20%"><input type="text" style="border:0;outline:0;" class="form-control text-center readonly " name="id[]" value="{{$d->barang_harga}}"></td>
                            <td width="10%"><input type="text" style="border:0;outline:0;" class="form-control text-center readonly " value="{{$d->barang_qty-$d->barang_digunakan}}"></td>
                            <td width="30%"><input type="number" min="1" max="{{$d->barang_qty-$d->barang_digunakan}}" class="form-control text-center readonly " name="bk_jumlah_qty[]" value="0"></td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            {{-- END MODAL CARI BARANG  --}}


            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Jenis Laporan</label>
              <div class="col-sm-4 has-error has-feedback">
                <select class="form-control" required name="bk_jenis_laporan">
                  <option value="">- Pilih -</option>
                  <option value="Gangguan / Komplain">Gangguan / Komplain</option>
                  <option value="Edukasi">Edukasi</option>
                  <option value="Instalasi Hotspot">Instalasi Hotspot</option>
                  <option value="Aktivasi">Aktivasi</option>
                  <option value="Upgrade">Upgrade</option>
                  <option value="Maintenance">Maintenance</option>
                  <option value="Pindah Alamat">Pindah Alamat</option>
                  <option value="Penarikan Kabel">Penarikan Kabel</option>
                  <option value="Dismentle Perangkat">Dismentle Perangkat</option>
                </select> </select>
              </div>
              <label class=" col-sm-2 col-form-label">Tanggal Keluar</label>
              <div class="col-sm-4 notif2">
                <input type="text" id="" name="bk_waktu_keluar" class="form-control readonly" value="{{date('Y-m-d h:s')}}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Penerima Barang</label>
              <div class="col-sm-4 notif3">
                <select class="form-control" name="bk_penerima" id="">
                  <option value="">- Pilih -</option>
                  @foreach ($data_user as $u)
                  <option value="{{$u->id}}">{{$u->name}}</option>
                  @endforeach
                </select>
              </div>
              <label class="col-sm-2 col-form-label">Admin Id</label>
              <div class="col-sm-4">
                <input type="text" class="form-control readonly" id="id_admin" value="{{$id_admin}}" name="">
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-sm-2 col-form-label">Kode barang</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" data-toggle="modal" data-target="#modal_barang" id="bk_id_barang" name="">
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-sm-2 col-form-label">Keperluan</label>
              <div class="col-sm-10">
                <textarea name="bk_keperluan" class="form-control" id="" cols="30" rows="5"></textarea>
              </div>
            </div>
            {{-- <div class="form-group row">
                <div class="table-responsive">
                <table id="t" class="display  table-sm table-striped table-hover text-center">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Kode Barang</th>
                      <th>Nama Barang</th>
                      <th>Merek</th>
                      <th>Harga</th>
                      <th>Stok</th>
                      <th>Qty</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="display: none" ><input type="text" value="" name="bk_id_barang[]"></td>
                      <td style="display: none" ><input type="text" value="0" name="bk_harga_barang[]"></td>
                      <td style="display: none" ><input type="text" value="0" name="bk_qty[]"></td>
                    </tr>
                  </tbody>
              </table>
              </div>
              </div> --}}
            <div class="card-footer">
              <a href="{{route('admin.psb.index')}}"><button type="button" class="btn  ">Batal</button></a>
              <button type="submit" class="btn btn-primary float-right simpan">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>


@endsection
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
         

               {{-- MODAL CARI BARANG  --}}
<div class="modal fade" id="modal_barang" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="staticBackdropLabel">Cari Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="cari_kode_barang" class="display table table-striped table-hover text-center" >
            <thead>
              <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Nama Barang</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data_barang as $d)
              <tr id="{{$d->barang_id}}">
                <td>{{$d->barang_id}}</td>
                <td>{{$d->barang_nama}}</td>
                <td>{{$d->barang_nama}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
        {{-- END MODAL CARI BARANG  --}}


              <div class="form-group row">
                  <label class="col-sm-2 col-form-label" >Keperluan</label>
                <div class="col-sm-4">
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
                    </select>                  </select>
                </div>
                  <label class=" col-sm-2 col-form-label">Tanggal Keluar</label>
                <div class="col-sm-4">
                  <input type="text" id="" name="bk_waktu_keluar" class="form-control readonly" value="{{date('Y-m-d h:s')}}" >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Penerima Barang</label>
                <div class="col-sm-4">
                  <select class="form-control" name="" id="">
                    <option value="">- Pilih -</option>
                    @foreach ($data_user as $u)
                    <option value="{{$u->id}}">{{$u->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group row">
                  <label for="" class="col-sm-2 col-form-label">Kode barang</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control"  data-toggle="modal" data-target="#modal_barang" id="bk_id_barang" name="bk_id_barang">
                  </div>
                  <label for="" class="col-sm-2 col-form-label">Nama Barang</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control readonly" id="bk_nama_barang" value="" name="bk_nama_barang" >
                  </div>
              </div>
           
                     
            </form>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>


@endsection

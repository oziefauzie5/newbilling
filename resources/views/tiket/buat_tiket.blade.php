@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-primary">
            <div class="d-flex align-items-center">
              <h4 class="card-title text-light">FORM TIKET</h4>
            </div>
          </div>
          <div class="card-body">
{{-- MODAL CARI DATA  --}}
  <div class="modal fade" id="cari_data" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="staticBackdropLabel">Cari Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="tiket_pilih_pelanggan" class="display table table-striped table-hover text-nowrap" >
            <thead>
              <tr>
                <th>Id</th>
                <th>No. Layanan</th>
                <th>Nama</th>
                <th>Whatsapp</th>
                <th>Alamat Pasang</th>
              </tr>
            </thead>
            <tbody>
              @foreach($input_data as $d)
              <tr id="{{$d->reg_idpel}}">
                <td>{{$d->id}}</td>
                <td>{{$d->reg_nolayanan}}</td>
                <td>{{$d->input_nama}}</td>
                <td>{{$d->input_hp}}</td>
                <td>{{$d->input_alamat_pasang}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
        {{-- END MODAL CARI DATA  --}}


            
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
            <form action="{{route('admin.tiket.store')}}" method="post">
              @csrf
              @method('POST')
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Site</label>
              <div class="col-sm-4">
                  <select name="tiket_site" id="" class="form-control">
                    <option value="">- Pilih -</option>
                    @foreach ($data_site as $site)
                    <option value="{{$site->site_id}}">{{$site->site_nama}}</option>
                    @endforeach
                  </select>
              </div>
              </div>
            <div class="form-group row">
              <!-- <label class="col-sm-2 col-form-label">Nomor Tiket</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="tiket_id" required value="">
              </div> -->
              <label class="col-sm-2 col-form-label">Jenis Laporan</label>
              <div class="col-sm-4">
                <select class="form-control" required name="tiket_jenis">
                  <option value="">--Pilih--</option>
                  <option value="Gangguan / Komplain">Gangguan / Komplain</option>
                  <option value="Edukasi">Edukasi</option>
                  <option value="Instalasi">Instalasi</option>
                  <option value="Aktivasi">Aktivasi</option>
                  <option value="Upgrade">Upgrade</option>
                  <option value="Maintenance">Maintenance</option>
                  <!-- <option value="Realtivasi">Reaktivasi</option> -->
                  <option value="Downgrade">Downgrade</option>
                  <option value="Setup Wifi">Setup Wifi</option>
                  <option value="Setup Ip Cam">Setup Ip Cam</option>
                  <option value="Pindah Alamat">Pindah Alamat</option>
                  <option value="Deaktivasi">Deaktivasi</option>
                  <option value="Penarikan Kabel">Penarikan Kabel</option>
                  <option value="Dismentle Perangkat">Dismentle Perangkat</option>
                  <option value="Pembayaran">Pembayaran</option>
                  <option value="Hotspot">Hotspot</option>
                </select>
              </div>
              </div>
              <div class="form-group row">
              <label class="col-sm-2 col-form-label">Nama Pelanggan</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" required id="tiket_pelanggan" value="{{ Session::get('tiket_pelanggan') }}" data-toggle="modal" data-target="#cari_data" name="tiket_pelanggan">
              </div>
              <label class="col-sm-2 col-form-label">Id Pelanggan</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" required id="tiket_idpel" value="{{ Session::get('tiket_idpel') }}" name="tiket_idpel">
              </div>
              </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Topik Laporan</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" required id="1ssss" value="{{ Session::get('tiket_nama') }}" name="tiket_nama">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Waktu Kunjungan</label>
              <div class="col-sm-4">
                <input type="text" class="form-control datepicker" id="1ssss" value="{{ Session::get('tiket_waktu_kunjungan') }}" name="tiket_waktu_kunjungan">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Keterangan Komplain</label>
              <div class="col-sm-10">
                <textarea type="text" class="form-control" required id="1ssss" name="tiket_keterangan" rows="5">{{ Session::get('tiket_keterangan') }}</textarea>
              </div>
            </div>
            <div class="card-footer">
              <a href="{{route('admin.tiket.data_tiket')}}"><button type="button" class="btn ">Batal</button></a>
              <button type="submit" class="btn btn-primary float-right">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
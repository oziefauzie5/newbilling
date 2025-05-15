@extends('layout.main')
@section('content')
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
          <h3 class="mt-3 text-bolt text-center">FORM PESANAN VOUCHER</h3>

        <form class="form-horizontal"action="{{route('admin.vhc.store_pesanan')}}" method="POST">
          @csrf
          @method('POST')
              <div class="form-group row">
                <label class=" col-sm-2 col-form-label">Site Id</label>
                <div class="col-sm-4 ">
                <select name="vhc_site" id="vhc_site" class="form-control" required>
                    <option value="">PILIH SITE</option>
                  @foreach($data_site as $site)
                   <option value="{{$site->site_id}}">{{$site->site_nama}}</option>
                   @endforeach
                  </select>
                </div>
                  <label class="col-sm-2 col-form-label" >Mitra</label>
                <div class="col-sm-4">
                  <select name="vhc_mitra" id="vhc_mitra" class="form-control" required>
                  <option value="">---Pilih Mitra---</option>
                  </select>
                </div>
              </div>
              <div class="form-group row ">
                <label class="col-sm-2 col-form-label">Outlet</label>
                <div class="col-sm-4">
                <select name="vhc_outlet" id="vhc_outlet" class="form-control" required>
                    <option value="">---Pilih Outlet---</option>
                </select>
                </div>
                <label class="col-sm-2 col-form-label">Router</label>
                <div class="col-sm-4">
                <select name="vhc_router" id="vhc_router" class="form-control" required>
                    <option value="">--Pilih Router--</option>
                    @foreach($data_router as $router)
                    <option value="{{$router->id}}">{{$router->router_nama}}</option>
                    @endforeach
                  </select>
                </div>
                <label class="col-sm-2 col-form-label">Admin Id</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control readonly" id="vhc_admin_id" value="{{$user_id}}" name=""  >
                </div>
                <label class="col-sm-2 col-form-label">Admin</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control readonly"  value="{{$user_nama}}" name=""  >
                </div>
              </div>
             
              <div class="form-group row">
                  <label for="" class="col-sm-2 col-form-label">Paket</label>
                  <div class="col-sm-3">
                    <select name="vhc_paket" id="paket_id" class="form-control ">
                      <option value="">--Pilih Paket--</option>
                      @foreach($data_paket as $paket)
                      <option value="{{$paket->paket_id}}">{{$paket->paket_nama}}</option>
                      @endforeach
                    </select>
                  </div>
                  <label for="" class="col-sm-2 col-form-label">Jumlah Voucher</label>
                  <div class="col-sm-2">
                    <input type="number" class="form-control " value="10" id="jumlah_voucher" min="1" max="">
                  </div>
                  <div class="col-sm-2">
                    <button  type="button" class="btn btn-danger button_add_voucher">Tambah Voucher</button>
                  </div>
              </div>
              <div class="pesan_error"></div>
              <div class="form-group row">
                <div class="table-responsive">
                <table id="tv" class="display table  table-sm table-striped table-hover text-center">
                  <thead>
                    <tr>
                      <th>Id Voucher</th>
                      <th>Voucher</th>
                      <th>Jumlah</th>
                      <th>HPP</th>
                      <th>Total HPP</th>
                      <th>Komisi</th>
                      <th>Total Komisi</th>
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
                <a href="{{route('admin.vhc.data_pesanan')}}"><button type="button" class="btn  ">Batal</button></a>
                <button type="button" class="btn btn-primary float-right simpan_pesanan_voucher">Simpan</button>
                </div>
            </form>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>


@endsection



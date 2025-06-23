@extends('layout.user')
@section('content')

<div class="content">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">

                
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
      <div class="user mt--5">
        <div class="avatar-sm float-left mr-2">
          <img src="@if(Auth::user()->photo) {{ asset('storage/image/'.Auth::user()->photo) }} @else {{ asset('atlantis/assets/img/user.png')}} @endif" alt=".." class="avatar-img rounded-circle"> 
        </div>
        <div class="info">
          <span> 
              <span class="user-level text-light font-weight-bold">{{strtoupper(Auth::user()->name)}}</span><br>
              <h6 class="user-level text-light ">{{$role}}</h6>
          <div class="clearfix"></div>
        </span>
        </div>
            <div class="row mt-1">
              <div class="col-md-12">
                <div class="card">
                  @if ($errors->any())
                  <div class="alert alert-danger" role="alert">
                     <ul>
                      @foreach ($errors->all() as $item)
                          <li>{{ $item }}</li>
                      @endforeach
                     </ul>
                    </div>
                  @endif


                    <div class="card-body ">
           
              <form action="{{route('admin.sales.update_store',['id'=>$input_data->id])}}" method="POST">
              @csrf
              @method('PUT')
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Nama Lengkap</label>
                      <input id="input_nama" type="text" class="form-control" name="input_nama"placeholder="Nama Lengkap" value="{{$input_data->input_nama}}" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>No Identitas</label>
                      <input id="input_ktp" type="text" class="form-control" value="{{$input_data->input_ktp}}" name="input_ktp" onkeyup="validasiKtp()" placeholder="No. Identitas" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>No Hp</label>
                      <input id="input_hp" type="text" class="form-control" value="{{$input_data->input_hp}}" name="input_hp" placeholder="No. Whatsapp" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>No Hp 2</label>
                      <input id="input_hp" type="text" class="form-control" value="{{$input_data->input_hp}}" name="input_hp_2" placeholder="No. Whatsapp" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Email</label>
                      <input id="input_email" type="text" class="form-control" value="{{$input_data->input_email}}" name="input_email" placeholder="Email" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Alamat</label>
                      <textarea id="input_alamat_ktp" type="text" class="form-control"  rows="5"  name="input_alamat_ktp" required>{{$input_data->input_alamat_ktp}}</textarea>
                    </div>
                  </div>
               
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Alamat ( Alamat Pemasangan )</label>
                      <textarea id="input_alamat_ktp" type="text" class="form-control"  rows="5"  name="input_alamat" required>{{$input_data->input_alamat_pasang}}</textarea>
                    </div>
                  </div>
                 
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Paket </label>
                      <select class="form-control" name="input_keterangan" id="input_paket" required >
                        <option value="">--Pilih Paket--</option>
                        @foreach ($paket as $p)
                           <option value="{{$p->paket_nama}}">{{$p->paket_nama.' - '.number_format($p->paket_harga)}}</option> 
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Share Location </label>
                      <input id="input_maps" type="text" class="form-control" value="{{$input_data->input_maps}}" name="input_maps" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Informasi</label>
                      <input id="" type="text" class="form-control" value="{{$input_data->input_subseles}}" name="input_subseles">
                      <span class="noted">Kosongkan jika tidak ada pemberi informasi</span>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group notif_validasi">
                      <label>Kode Promo</label>
                      <input id="validasi_kode_promo" type="text" class="form-control" value="{{$input_data->input_promo}}" name="input_promo">
                      <div class="text-danger" id="pesan"></div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Catatan</label>
                      <textarea id="keterangan" type="text" class="form-control"  rows="5"  name="input_keterangan" required>{{$input_data->input_keterangan}}</textarea>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Status</label>
                      <select name="input_status" class="form-control" id="">
                      <option value="{{$input_data->input_status}}">{{$input_data->input_status}}</option>
                      <option value="BATAL PEMASANGAN">BATAL PEMASANGAN</option>
                      </select>
                    </div>
                  </div>
                  <div class="card-footer">
                    <button type="submit" id="" class="btn btn-primary">Submit</button>
                  </form>
                  <a href=""><button type="button" class="btn btn-primary">Kembali</button></a>
                  </div>
                </div>
              </div>
              </div>
              </div>
              </div>
            </div>





            
    </div>
  </div>
@endsection
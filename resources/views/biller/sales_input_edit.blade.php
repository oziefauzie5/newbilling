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
          <img src="@if(Auth::user()->photo) {{ asset('storage/image/'.Auth::user()->photo) }} @else {{ asset('atlantis/assets/img/user.png}}@endif" alt=".." class="avatar-img rounded-circle"> 
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
           
              <form action="{{route('admin.sales.sales_update')}}" method="POST">
                @csrf
                @method('POST')
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
                      <input id="input_alamat_ktp" type="text" class="form-control" value="{{$input_data->input_alamat_ktp}}" name="input_alamat_ktp" placeholder="Kp / Perumahan / Jalan" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>RT</label>
                      <input id="rt" type="number" class="form-control" value="{{$input_data->rt_ktp}}" name="rt_ktp" placeholder="RT sesuai KTP" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>RW</label>
                      <input id="" type="number" class="form-control" value="{{$input_data->rw_ktp}}" name="rw_ktp" placeholder="RW sesuai KTP" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Kelurahan</label>
                      <input id="kelurahan" type="text" class="form-control" value="{{$input_data->kelurahan_ktp}}" name="kelurahan_ktp" placeholder="Kelurahan sesuai KTP" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Kecamatan</label>
                      <input id="kecamatan" type="text" class="form-control" value="{{$input_data->kecamatan_ktp}}" name="kecamatan_ktp" placeholder="Kecamatan sesuai KTP" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Kota/Kabupate</label>
                      <input id="kota_ktp" type="text" class="form-control" value="{{$input_data->kota_ktp}}" name="kota_ktp" placeholder="Kota/Kab. sesuai KTP" required>
                    </div>
                  </div>
                  <br>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Alamat ( Alamat Pemasangan )</label>
                      <input id="input_alamat" type="text" class="form-control" value="{{$input_data->input_alamat}}" name="input_alamat" placeholder="Kp / Perumahan / Jalan ( Alamat Pemasangan )" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>RT ( Alamat Pemasangan )</label>
                      <select name="rt" id="rt" class="form-control" required>
                        @if( Session::get('rt'))
                        <option value="{{$input_data->rt')}}">{{$input_data->rt')}}</option>
                        @endif
                        <option value="">--Pilih RT--</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>RW ( Alamat Pemasangan )</label>
                        <select name="rw" id="rw" class="form-control" required>
                        @if( Session::get('rw'))
                        <option value="{{$input_data->rw')}}">{{$input_data->rw')}}</option>
                        @endif
                        <option value="">--Pilih RW--</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                      </select>
                      {{-- <input id="rw" type="number" class="form-control" value="{{$input_data->rw}}" name="rw" placeholder="RW"> --}}
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group notif_validasi">
                      <label>Kelurahan ( Alamat Pemasangan )</label>
                      <input id="val_kelurahan" type="text" class="form-control" value="{{$input_data->kelurahan}}" name="kelurahan" placeholder="Kelurahan" required>
                       <div class="text-danger" id="pesan"></div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Kecamatan ( Alamat Pemasangan )</label>
                      <input id="kec" type="text" class="form-control read" value="{{$input_data->kecamatan}}" name="kecamatan" placeholder="kecamatan" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Kota/Kabupaten ( Alamat Pemasangan )</label>
                      <select name="kota" id="kota" class="form-control" required>
                      </select>
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
                      <input id="input_maps" type="text" class="form-control" value="{{$input_data->input_maps}}" name="input_maps" placeholder="Masukan Link Share Location" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Informasi</label>
                      <input id="" type="text" class="form-control" value="{{$input_data->sub_sales}}" name="sub_sales" placeholder="Masukan Nama pemberi informasi">
                      <span class="noted">Kosongkan jika tidak ada pemberi informasi</span>
                    </div>
                  </div>
                  @if($cek_role->role_id == 16 ) 
                   <div class="col-sm-12">
                    <div class="form-group">
                      <label>PIC</label>
                      <select class="form-control" name="input_sales" id="">
                      @foreach($sub_mitra as $sm)
                      <option value="{{$sm->id}}">{{$sm->name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                @elseif($cek_role->role_id == 15 ) 
                <div class="col-sm-12">
                    <div class="form-group">
                      <label>SUB-PIC</label>
                      <select class="form-control" name="input_sub_pic" id="">
                      <option value="">None</option>
                      @foreach($sub_mitra as $sm)
                      <option value="{{$sm->id}}">{{$sm->name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                @elseif($cek_role->role_id == 12 ) 
                <div class="col-sm-12">
                    <div class="form-group">
                      <label>PIC</label>
                      <select class="form-control" id="user_mitra" name="input_sub_pic" id="">
                        
                    </select>
                  </div>
                </div>
                @endif
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Tanggal Registrasi</label>
                      <input id="" type="date" class="form-control" value="{{$input_data->tgl_regist}}" name="tgl_regist"  required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group notif_validasi">
                      <label>Kode Promo</label>
                      <input id="validasi_kode_promo" type="text" class="form-control" value="{{$input_data->input_promo}}" name="input_promo">
                      <div class="text-danger" id="pesan"></div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <button type="submit" id="" class="btn btn-primary">Submit</button>
                  </form>
                  <a href="{{route('admin.sales.sales')}}"><button type="button" class="btn btn-primary">Kembali</button></a>
                  </div>
                </div>
              </div>
              </div>
              </div>
              </div>
            </div>
            {{-- </div>
 
    </section> --}}




            
    </div>
  </div>
@endsection
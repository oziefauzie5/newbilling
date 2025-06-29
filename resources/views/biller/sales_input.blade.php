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
          <img src="@if(Auth::user()->photo) {{ asset('storage/photo-user/'.Auth::user()->photo) }} @else {{ asset('atlantis/assets/img/user.png') }}@endif" alt=".." class="avatar-img rounded-circle"> 
        </div>
        <div class="info">
          <span> 
              <span class="user-level text-light font-weight-bold">{{strtoupper(Auth::user()->name)}}</span><br>
              <h6 class="user-level text-light ">{{$role}}</h6>
          <div class="clearfix"></div>
        </span>
        </div>

      {{-- <div class="h5 mt--5 text-light font-weight-bold ">TEKNISI : {{strtoupper($nama)}}</div><br> --}}
      
        {{-- <div class="row mt-1">
          <div class="col-6 col-sm-6">
            <div class="card">
              <div class="card-body p-3 text-center">
                <div class="text-right text-success">
                </div>
                <div class="h5 m-0">Rp. </div>
                <div class="text-muted mb-3">SALDO</div>
              </div>
            </div>
          </div>
          <div class="col-6 col-sm-6">
            <div class="card">
              <div class="card-body p-3 text-center">
                <div class="text-right text-success">
                </div>
                <div class="h5 m-0">Rp. </div>
                <div class="text-muted mb-3">PENCAIRAN</div>
              </div>
            </div>
          </div>
            
          </div> --}}
          {{-- <section class="content mt-1">

            <div class="page-inner"> --}}
            <div class="row mt-1">
              <div class="col-md-12">
                <div class="card">
                  {{-- <div class="card-header bg-primary">
                    <h3 class="card-title">Tambah Pelanggan</h3>
                  </div> --}}
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
           
              <form action="{{route('admin.sales.sales_store')}}" method="POST">
                @csrf
                @method('POST')
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Nama Lengkap</label>
                      <input id="input_nama" type="text" class="form-control" name="input_nama"placeholder="Nama Lengkap" value="{{ Session::get('input_nama') }}" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>No Identitas</label>
                      <input id="input_ktp" type="text" class="form-control" value="{{ Session::get('input_ktp') }}" name="input_ktp" onkeyup="validasiKtp()" placeholder="No. Identitas" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>No Hp</label>
                      <input id="input_hp" type="text" class="form-control" value="{{ Session::get('input_hp') }}" name="input_hp" placeholder="No. Whatsapp" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>No Hp 2</label>
                      <input id="input_hp" type="text" class="form-control" value="{{ Session::get('input_hp') }}" name="input_hp_2" placeholder="No. Whatsapp" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Email</label>
                      <input id="input_email" type="text" class="form-control" value="{{ Session::get('input_email') }}" name="input_email" placeholder="Email" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Alamat</label>
                      <input id="input_alamat_ktp" type="text" class="form-control" value="{{ Session::get('input_alamat_ktp') }}" name="input_alamat_ktp" placeholder="Kp / Perumahan / Jalan" required>
                    </div>
                  </div>
                   <div class="col-sm-6">
                    <div class="form-group">
                      <label>RT</label>
                        <select name="rt_ktp" id="rt_ktp" class="form-control" required>
                        @if( Session::get('rw'))
                        <option value="{{ Session::get('rt_ktp')}}">{{ Session::get('rt_ktp')}}</option>
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
                    </div>
                  </div>
                   <div class="col-sm-6">
                    <div class="form-group">
                      <label>RW</label>
                        <select name="rw_ktp" id="rw_ktp" class="form-control" required>
                        @if( Session::get('rw'))
                        <option value="{{ Session::get('rw_ktp')}}">{{ Session::get('rw_ktp')}}</option>
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
                    </div>
                  </div>

                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Kelurahan</label>
                      <input id="kelurahan" type="text" class="form-control" value="{{ Session::get('kelurahan_ktp') }}" name="kelurahan_ktp" placeholder="Kelurahan sesuai KTP" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Kecamatan</label>
                      <input id="kecamatan" type="text" class="form-control" value="{{ Session::get('kecamatan_ktp') }}" name="kecamatan_ktp" placeholder="Kecamatan sesuai KTP" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Kota/Kabupaten</label>
                      <input id="kota_ktp" type="text" class="form-control" value="{{ Session::get('kota_ktp') }}" name="kota_ktp" placeholder="Kota/Kab. sesuai KTP" required>
                    </div>
                  </div>
                  <br>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Alamat ( Alamat Pemasangan )</label>
                      <input id="input_alamat" type="text" class="form-control" value="{{ Session::get('input_alamat') }}" name="input_alamat" placeholder="Kp / Perumahan / Jalan ( Alamat Pemasangan )" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>RT ( Alamat Pemasangan )</label>
                      <select name="rt" id="rt" class="form-control" required>
                        @if( Session::get('rt'))
                        <option value="{{ Session::get('rt')}}">{{ Session::get('rt')}}</option>
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
                        <option value="{{ Session::get('rw')}}">{{ Session::get('rw')}}</option>
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
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group notif_validasi">
                      <label>Kelurahan ( Alamat Pemasangan )</label>
                      <input id="val_kelurahan" type="text" class="form-control" value="{{ Session::get('kelurahan') }}" name="kelurahan" placeholder="Kelurahan" required>
                       <div class="text-danger" id="pesan"></div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Kecamatan ( Alamat Pemasangan )</label>
                      <input id="kec" type="text" class="form-control read" value="{{ Session::get('kecamatan') }}" name="kecamatan" placeholder="kecamatan" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Kota/Kabupatenn ( Alamat Pemasangan )</label>
                      <select name="kota" id="kota" class="form-control" required>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Paket </label>
                      <select class="form-control" name="input_paket" id="input_paket" required >
                        <option value="">--Pilih Paket--</option>
                        @foreach ($paket as $p)
                           <option value="{{$p->paket_nama.' - '.number_format($p->paket_harga)}}">{{$p->paket_nama.' - '.number_format($p->paket_harga)}}</option> 
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Share Location </label>
                      <input id="input_maps" type="text" class="form-control" value="{{ Session::get('input_maps') }}" name="input_maps" placeholder="Masukan Link Share Location" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Informasi</label>
                      <input id="" type="text" class="form-control" value="{{ Session::get('sub_sales') }}" name="sub_sales" placeholder="Masukan Nama pemberi informasi">
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
                      <input id="" type="date" class="form-control" value="{{ Session::get('tgl_regist') }}" name="tgl_regist"  required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group notif_validasi">
                      <label>Kode Promo</label>
                      <input id="validasi_kode_promo" type="text" class="form-control" value="{{ Session::get('input_promo') }}" name="input_promo">
                      <div class="text-danger" id="pesan"></div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group notif_validasi">
                      <label>Catatan</label>
                      <textarea name="input_keterangan" id="" class="form-control"></textarea>
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
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
          <h3 class="mt-3 text-bolt text-center">AKTIVASI PELANGGAN</h3>

          <h3 class="mt-3 text-bolt">PELANGGAN</h3><hr>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nama Pelanggan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control readonly" id="tampil_nama" value="{{ $data->input_nama}}" name="reg_nama">
                </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-2 col-form-label" >ID Pelanggan</label>
                <div class="col-sm-4">
                  <input type="text" id="tampil_idpel" class="form-control readonly" name="reg_idpel" value="{{ $data->reg_idpel}}"  >
                </div>
                  <label class=" col-sm-2 col-form-label">No Layanan</label>
                <div class="col-sm-4">
                  <input type="text" id="tampil_nolay" name="reg_nolayanan" class="form-control readonly" value="{{ $data->reg_nolayanan}}" >
                </div>
              </div>
              <div class="form-group row">
                  <label for="hp" class="col-sm-2 col-form-label">No Whatsapp 1</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control readonly" id="tampil_hp" value="{{ $data->input_hp}}" name="reg_hp" >
                  </div>
                  <label for="hp" class="col-sm-2 col-form-label">No Whatsapp 2</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control readonly" id="tampil_hp_2" value="{{ $data->input_hp_2}}" name="reg_hp_2" >
                  </div>
              </div>
              <div class="form-group row">
                  <label for="alamat_pasang" class="col-sm-2 col-form-label">Alamat Pasang</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control readonly" id="tampil_alamat_pasang" value="{{ $data->input_alamat_pasang}}" name="reg_alamat_pasang" >
                  </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Sub Sales</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control readonly" id="tampil_subsales" name="input_subseles" value="{{$data->input_subseles}}" >
                </div>
              </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Maps</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="tampil_maps" name="reg_maps" value="{{ $data->input_maps}}">
              </div>
            </div>
                     
            </form>
            </div>
          </div>
        </div>


        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <form class="form-horizontal"action="{{route('admin.psb.update_router',['id'=>$data->reg_idpel])}}" method="POST">
            @csrf
            @method('PUT')
            <h3 class="mt-3 text-bolt">INTERNET</h3><hr>
              
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Status Internet :</label>
                <div class="col-sm-4">
                  @if($status=='CONNECTED')
                  <label class="col-sm-2 col-form-label font-weight-bold text-success" >{{ $status}}</label>
                  @else
                  <label class="col-sm-2 col-form-label font-weight-bold text-danger" >{{ $status}}</label>
                  @endif
                </div>
                <label class="col-sm-2 col-form-label">IP Address :</label>
              <div class="col-sm-4">
                <label class="col-sm-2 col-form-label font-weight-bold text-danger" >{{ $address}}</label>
              </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Aksi</label>
              <div class="col-sm-3 mt-1">
                <a href="{{route('admin.noc.pengecekan',['id'=>$data->reg_idpel])}}" target="_blank">
                  <button type="button" class="btn btn-sm btn-warning btn-block">
                    Remote
                  </button></a>             
                </div>
              <div class="col-sm-3 mt-1">
                <a href="{{route('admin.noc.kick',['id'=>$data->reg_idpel])}}">
                  <button type="button" class="btn btn-sm btn-danger btn-block">
                    Kick
                  </button></a>             
                </div>
              <div class="col-sm-3 mt-1">
                @if($status_secret=='false')
                <a href="{{route('admin.noc.status_secret',['id'=>$data->reg_idpel.'?stat=false'])}}">
                  <button type="button" class="btn btn-sm btn-danger btn-block">
                    Disable
                  </button></a>     
                  @elseif($status_secret=='true')
                  <a href="{{route('admin.noc.status_secret',['id'=>$data->reg_idpel.'?stat=true'])}}">
                    <button type="button" class="btn btn-sm btn-success btn-block">
                      Enable
                    </button></a>   
                    @else
                    <button type="button" class="btn btn-sm btn-danger btn-block">
                      Data tidak ditemukan
                    </button>
                  @endif        
                </div>
</form>
              </div>
            </div>
          </div>
        </div>
<div class="col-md-12">
  <div class="card">
    <div class="card-body"> 
      <form class="form-horizontal"action="{{route('admin.reg.proses_aktivasi_pelanggan',['id'=>$data->reg_idpel])}}" method="POST"  enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <h3 class="mt-3 text-bolt"> HADHWARE</h3><hr>
      <div class="form-group row">
        <label class=" col-sm-2 col-form-label">Site</label>
        <div class="col-sm-4">
          <select class="form-control" id="reg_site" name="reg_site" required >
            @if($data->reg_site)
            <option value="{{$data->site_id}}">{{$data->site_nama}}</option>
            @endif
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label class=" col-sm-2 col-form-label">POP <span>(Auto)</span></label>
        <div class="col-sm-4">
          <select name="reg_pop" id="" class="form-control" required>
            @if ($data->pop_id)
                <option value="{{$data->pop_id}}">{{$data->pop_nama}}</option>
            @endif
          </select>
        </div>
        <label for="router" class="col-sm-2 col-form-label">Router</label>
        <div class="col-sm-4">
          <select class="form-control" id="" name="reg_router" >
            @if( $data->reg_router)
          <option value="{{ $data->reg_router}}">{{ $data->router_nama}}</option>
          @endif
          @foreach ($router as $d)
              <option value="{{$d->id}}">{{$d->router_nama}}</option>
          @endforeach
          </select>
        </div>
        </div>
      <div class="form-group row">
        <label class=" col-sm-2 col-form-label">OLT <span>(Auto)</span></label>
        <div class="col-sm-4 notif">
          <input type="text" name="reg_olt" id="validasi_olt" class="form-control readonly" autocomplete="off"   required value="{{ Session::get('reg_olt') }}">
        </div>
        <label class=" col-sm-2 col-form-label">ODC <span>(Auto)</span></label>
        <div class="col-sm-4 notif">
          <input type="text" name="reg_odc" id="validasi_odc" class="form-control readonly" required value="{{ Session::get('reg_odc') }}" >
        </div>
    </div>
      <div class="form-group row">
        <label class=" col-sm-2 col-form-label">ODP</label>
        <div class="col-sm-4 notif">
          <input type="text" name="reg_odp" id="validasi_odp" class="form-control " required value="{{ Session::get('reg_odp') }}" >
          <div id="pesan"></div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Layanan <span>(Auto)</span></label>
        <div class="col-sm-10">
          <select class="form-control" id="layanan" name="reg_layanan"  >
            @if( $data->reg_layanan)
            <option value="{{ $data->reg_layanan}}">{{ $data->reg_layanan}}</option>
            @else
            <option value="">Pilih</option>
            <option value="PPP">PPP</option>
            <option value="HOTSPOT">HOTSPOT</option>
            @endif
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-2 col-form-label">Username internet <span>(Auto)</span></label>
      <div class="col-sm-4">
        <input type="text" id="tampil_username" name="reg_username" class="form-control readonly" value="{{ $data->reg_username}}" >
      </div>
        <label class=" col-sm-2 col-form-label">Passwd internet <span>(Auto)</span></label>
      <div class="col-sm-4">
        <input type="text" class="form-control readonly" name="reg_password" value="{{ $data->reg_password}}" >
      </div>
      </div>
      <!-- #</div> hilang terhapus -->
     
      <div class="form-group row">
        <label class=" col-sm-2 col-form-label">ONU ID</label>
        <div class="col-sm-4">
          <input type="text" name="reg_onuid" class="form-control" required value="{{ Session::get('reg_onuid') }}">
        </div>
        <label class=" col-sm-2 col-form-label">Slot ODP</label>
        <div class="col-sm-4">
          <input type="text" name="reg_slot_odp" class="form-control" required value="{{ Session::get('reg_slot_odp') }}" >
        </div>
      </div>
      <div class="form-group row">
        <label class=" col-sm-2 col-form-label">Redaman</label>
        <div class="col-sm-4">
          <input type="number" class="form-control" step="0.01"  placeholder="OPM" id="reg_in_ont" name="reg_in_ont" required value="{{ Session::get('reg_in_ont') }}" maxlength="6" minlength="6">
        </div>
      </div>
      <div class="form-group row">
        <label class=" col-sm-2 col-form-label">Teknisi 1</label>
        <div class="col-sm-4">
          <select name="teknisi1" id="" class="form-control" required value="{{ Session::get('teknisi1') }}">
            <option value="">- Pilih Teknisi -</option>
          @foreach ($data_teknisi as $t)
              <option value="{{$t->user_id}}|{{$t->user_nama}}">{{$t->user_nama}}</option>
          @endforeach
        </select>
        </div>
        <label class=" col-sm-2 col-form-label">Teknisi 2</label>
        <div class="col-sm-4">
          <input type="text" name="teknisi2" class="form-control" required value="{{ Session::get('teknisi2') }}" >
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-2 col-form-label">Koordinat Rumah</label>
        <div class="col-sm-4">
          <input type="text" class="form-control"  name="input_koordinat" required value="{{ Session::get('input_koordinat') }}">
        </div>
        <label class="col-sm-2 col-form-label">Koordinat ODP</label>
        <div class="col-sm-4">
          <input type="text" class="form-control"  name="reg_koordinat_odp" required value="{{ Session::get('reg_koordinat_odp') }}">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-2 col-form-label">Foto Rumah</label>
        <div class="col-sm-4">
          <input  type="file" class="form-control-file" name="reg_img"  value="{{ Session::get('reg_img') }}">
        </div>
        <label class="col-sm-2 col-form-label">Foto Lokasi ODP</label>
        <div class="col-sm-4">
          <input  type="file" class="form-control-file" name="reg_foto_odp"  value="{{ Session::get('reg_foto_odp') }}">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-2 col-form-label">Foto Rumah</label>
        <div class="col-sm-4">
          <img src="{{ asset('storage/laporan-kerja/'.$data->reg_img) }}" width="100%" alt="" title=""></img>
        </div>
        <label class="col-sm-2 col-form-label">Foto Lokasi ODP </label>
        <div class="col-sm-4">
          <img src="{{ asset('storage/laporan-kerja/'.$data->reg_foto_odp) }}" width="100%" alt="" title=""></img>
        </div>
      </div>
    <div class="card-footer">
    @role('admin|STAF ADMIN|NOC')
    <a href="{{route('admin.psb.index')}}"><button type="button" class="btn  ">Batal</button></a>
    <button type="submit" class="btn btn-primary float-right">Simpan</button>
    @endrole
    </div>
   </form>
  </div>
 </div>
</div>
  
  </div>
</div>

<script>
  var reg_in_ont = document.getElementById('reg_in_ont');
      reg_in_ont.addEventListener('keyup', function(e)
      {
        reg_in_ont.value = format_opm(this.value, '-');
      });
      
      function format_opm(angka, prefix)
      {
          var number_string = angka.replace(/[^,\d]/g, '').toString(),
              split    = number_string.split(','),
              sisa     = split[0].length % 2,
              rupiah     = split[0].substr(0, sisa),
              ribuan     = split[0].substr(sisa).match(/\d{2}/gi);
          if (ribuan) {
              separator = sisa ? '.' : '';
              rupiah += separator + ribuan.join('.');
          }
          return prefix == undefined ? rupiah : (rupiah ? '-' + rupiah : '');
      }
    </script>

@endsection

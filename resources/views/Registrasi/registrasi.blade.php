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
        
        {{-- MODAL CARI DATA  --}}
<div class="modal fade" id="cari_data" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
          <table id="input_data" class="display table table-striped table-hover text-nowrap" >
            <thead>
              <tr>
                <th>Tanggal Regist</th>
                <th>Nama</th>
                <th>Whatsapp</th>
                <th>Alamat Pasang</th>
              </tr>
            </thead>
            <tbody>
              @foreach($input_data as $d)
              <tr id="{{$d->id}}">
                <td>{{$d->input_tgl}}</td>
                <td>{{$d->input_nama}}</td>
                <td>{{$d->input_hp}}</td>
                <td>{{$d->input_alamat_pasang}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-sm">Submit</button>
      </div>
    </div>
  </div>
</div>
        {{-- END MODAL CARI DATA  --}}
        
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
           <form class="form-horizontal"action="{{route('admin.reg.store')}}" method="POST">
             @csrf
            @method('POST')
             <h3 class="mt-3 text-bolt text-center">FORM REGISTRASI BERLANGGANAN</h3>
             <h3 class="mt-3 text-bolt">PELANGGAN</h3><hr>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nama Pelanggan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="tampil_nama" value="{{ Session::get('reg_nama') }}" data-toggle="modal" data-target="#cari_data" name="reg_nama">
                </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-2 col-form-label" >ID Pelanggan</label>
                <div class="col-sm-4">
                  <input type="number" id="tampil_idpel" class="form-control" name="reg_idpel" value="{{ Session::get('reg_idpel') }}" name="reg_idpel" readonly >
                </div>
                  <label class=" col-sm-2 col-form-label">No Layanan</label>
                <div class="col-sm-4">
                  <input type="text" id="tampil_nolay" name="reg_nolayanan" class="form-control" value="{{ Session::get('reg_nolayanan') }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                  <label for="hp" class="col-sm-2 col-form-label">No Whatsapp 1</label>
                  <div class="col-sm-4">
                    <input type="number" class="form-control" id="tampil_hp" value="{{ Session::get('reg_hp') }}" name="reg_hp" readonly>
                  </div>
                  <label for="hp" class="col-sm-2 col-form-label">No Whatsapp 2</label>
                  <div class="col-sm-4">
                    <input type="number" class="form-control" id="tampil_hp2" value="{{ Session::get('reg_hp2') }}" name="reg_hp2" readonly>
                  </div>
              </div>
              <div class="form-group row">
                  <label for="alamat_pasang" class="col-sm-2 col-form-label">Alamat Pasang</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="tampil_alamat_pasang" value="{{ Session::get('reg_alamat_pasang') }}" name="reg_alamat_pasang" readonly>
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-2 col-form-label">Maps</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="tampil_maps" name="reg_maps" value="{{ Session::get('reg_maps') }}" required>
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-2 col-form-label">Sales Id</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tampil_sales" name="reg_sales" value="{{ Session::get('reg_sales') }}" required>
                  </div>
                  <label class="col-sm-2 col-form-label">Sales</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tampil_sales_nama" name="reg_sales_nama" value="{{ Session::get('reg_sales_nama') }}" required>
                  </div>
              </div>
              <h3 class="mt-3 text-bolt">INTERNET & HADHWARE</h3><hr>

              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Site</label>
                <div class="col-sm-4">
                  <select class="form-control" id="site" name="reg_site"  >
                    <option value="">PILIH SITE</option>
                    @foreach ($data_site as $d)
                    <option value="{{$d->site_id}}">{{$d->site_nama}}</option>
                    @endforeach
                  </select>
                </div>
                <label class="col-sm-2 col-form-label">POP</label>
                <div class="col-sm-4">
                  <select class="form-control pop_router" id="pop" name="reg_pop"  >
                    <option value="">- Pilih POP -</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="router" class="col-sm-2 col-form-label">Router</label>
                <div class="col-sm-10">
                  <select class="form-control" id="router" name="reg_router"  >
                    <option value="">- Pilih Router -</option>
                  </select>
                </div>
            </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Layanan</label>
                <div class="col-sm-10">
                  <select class="form-control" id="reg_layanan" name="reg_layanan" >
                    @if( Session::get('reg_layanan'))
                    <option value="{{ Session::get('reg_layanan') }}">{{ Session::get('reg_layanan') }}</option>
                    @else
                    <option value="">PILIH LAYANAN</option>
                    <option value="PPP">PPP</option>
                    <option value="HOTSPOT">HOTSPOT</option>
                    {{-- <option value="HOTSPOT">HOTSPOT</option> --}}
                    @endif
                  </select>
                </div>
              </div>
              
              <div id="divip" class="form-group row" >
                <label class="col-sm-2 col-form-label">IP address</label>
                <div class="col-sm-10">
                  <input type="text"  name="reg_ip_address" id="ipaddres" value="{{ Session::get('reg_ip_address') }}" class="form-control"  >
                  <span>Jika diisi maka ip address user ini akan di tambahkan ke address list Mikrotik (berfungsi untuk policy based routing dll)</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Username internet *</label>
              <div class="col-sm-4">
                <input type="text" id="tampil_username" name="reg_username" class="form-control hotspot" value="{{ Session::get('reg_username') }}" required >
              </div>
                <label class=" col-sm-2 col-form-label" >Passwd internet *</label>
              <div class="col-sm-4">
                <input type="text" class="form-control pwhotspot" name="reg_password" value="1234567" required >
              </div>
              </div>

              <h3 class="mt-3">BILLING</h3><hr>
              <div class="form-group row">
                <label for="paket" class="col-sm-2 col-form-label" >Tanggal registrasi*</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" id="tampil_tgl" name="reg_tgl" value="{{Session::get('reg_tgl')}}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="paket" class="col-sm-2 col-form-label">Profile langganan *</label>
                <div class="col-sm-4">
                  <select class="form-control" id="paket" name="reg_profile" >
                    @if(Session::get('reg_profile'))
                    <option value="{{(Session::get('reg_profile'))}}">{{(Session::get('paket_nama'))}}</option>
                    <option value="">Pilih</option>
                    @foreach($data_paket as $p)
                    <option value="{{$p->paket_id}}">{{$p->paket_nama}}</option>
                    @endforeach
                    @else
                    <option value="">Pilih</option>
                    @foreach($data_paket as $p)
                    <option value="{{$p->paket_id}}">{{$p->paket_nama}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
                <label for="jenis_tagihan" class=" col-sm-2 col-form-label">Jenis tagihan *</label>
                <div class="col-sm-4">
                  <select class="form-control" id="jenis_tagihan" name="reg_jenis_tagihan" >
                    @if( Session::get('reg_jenis_tagihan'))
                    <option value="{{ Session::get('reg_jenis_tagihan') }}">{{ Session::get('reg_jenis_tagihan')}}</option>
                    <option value="">Pilih</option>
                    <option value="PRABAYAR">PRABAYAR</option>
                    <option value="PASCABAYAR">PASCABAYAR</option>
                    <option value="DEPOSIT">DEPOSIT</option>
                    <option value="FREE">FREE</option>
                    @else
                    <option value="">Pilih</option>
                    <option value="PRABAYAR">PRABAYAR</option>
                    <option value="PASCABAYAR">PASCABAYAR</option>
                    <option value="DEPOSIT">DEPOSIT</option>
                    <option value="FREE">FREE</option>
                    @endif
                  </select>
                </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-2 col-form-label">Harga prorata</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="harga" name="reg_harga" value="{{Session::get('reg_harga')}}" readonly >
                </div>
                    <label class="form-check col-sm-2 col-form-label">PPN 11%&nbsp;&nbsp;
                      <input class="form-check-input checkboxppn" type="checkbox" id="ppn" value="{{$data_biaya->biaya_ppn}}" @if( Session::get('reg_ppn')) checked @endif>
                      <span class="form-check-sign"></span>
                    </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="biaya_ppn" name="reg_ppn" value="{{Session::get('reg_ppn')}}" >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Sub Sales</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="tampil_subsales" name="input_subseles" value="{{Session::get('input_subseles')}}" readonly>
                </div>
              <label class="form-check col-sm-2 col-form-label">Dana Kerja Sama &nbsp;&nbsp;
                <input class="form-check-input checkboxkerjasama" type="checkbox" id="kas" value="{{$data_biaya->dana_kas}}" @if( Session::get('reg_dana_kerjasama')) checked @endif>
                <span class="form-check-sign"></span>
              </label>
              <div class="col-sm-4">
                <input type="text" class="form-control" id="kerjasama" name="reg_dana_kerjasama" value="{{Session::get('reg_dana_kerjasama')}}" readonly>
              </div>
            </div>
              <div class="form-group row">
                  <label class="col-sm-2 col-form-label">Kode Unik</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="kode_unik" name="reg_kode_unik" value="{{Session::get('reg_kode_unik')}}" >
                </div>

                <label class="form-check col-sm-2 col-form-label">Dana Kas &nbsp;&nbsp;
                  <input class="form-check-input checkboxkas" type="checkbox" id="kas" value="{{$data_biaya->dana_kas}}" @if( Session::get('reg_dana_kas')) checked @endif>
                  <span class="form-check-sign"></span>
                </label>
              <div class="col-sm-4">
                <input type="text" class="form-control" id="biaya_kas" name="reg_dana_kas" value="{{Session::get('reg_dana_kas')}}" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label class="form-check col-sm-2 col-form-label">Invoice Suspand &nbsp;&nbsp;
              </label>
              <div class="col-sm-10">
                <select name="reg_inv_control" id="" class="form-control">
                  <option value="0">SAMBUNG DARI TGL ISOLIR</option>
                  <option value="1" selected>SAMBUNG DARI TGL BAYAR</option>
                </select>
                <span>Jika Sambung dari tanggal isolir, maka pemakaian selama isolir tetap dihitung kedalam invoice</span><br>
                <span>Jika Sambung dari tanggal bayar, maka pemakaian selama isolir akan diabaikan dan dihitung kembali mulai dari semanjak pembayaran</span>
              </div>
              {{-- <label class="form-check col-sm-2 col-form-label">Tanggal Penagihan</label> --}}
              {{-- <div class="col-sm-4">
                  <input type="text" class="form-control" id="kode_unik" name="" value="" readonly >
              </div> --}}
            </div>
            <h3 class="mt-3 text-bolt">CATATAN</h3><hr>
            <div class="form-group row">
              <label for="router" class="col-sm-2 col-form-label">Keterangan</label>
              <div class="col-sm-10">
              <textarea class="form-control is-invalid" id="tampil_keterangan" readonly >
              </textarea>
              </div>
          </div>
            <div class="form-group row">
              <label for="router" class="col-sm-2 col-form-label">Catatan</label>
              <div class="col-sm-10">
              <textarea class="form-control is-invalid" id="validationTextarea" name="reg_catatan">{{Session::get('reg_catatan')}}
              </textarea>
              </div>
          </div>
            
         <div class="card-footer">
           <button type="button" class="btn  ">Batal</button>
           <button type="submit" class="btn btn-primary float-right">Simpan</button>
         </div>
       </form>
      </div>
    </div>
  </div>
</div>

@endsection

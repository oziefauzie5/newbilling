@extends('layout.main')
@section('content')

    <section class="content">
        <div class="page-inner">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header bg-primary">
                  <h3 class="card-tittle text-light">Input Data</h3>
                      </div>
                      @if ($errors->any())
                      <div class="alert alert-danger" role="alert">
                        <ul>
                          @foreach ($errors->all() as $item)
                          <li>{{ $item }}</li>
                          @endforeach
                        </ul>
                      </div>
                      @endif
                      
                      
                      <form action="{{route('admin.psb.store')}}" method="POST" class="needs-validation" novalidate>
                          @csrf
                          @method('POST')
                        <div class="card-body ">
                          <div class="card-header bg-primary">
                            <span class="text-light">Data Pelanggan</span>
                          </div>
                            <div class="after-add-more ">

                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Nama Lengkap</label>
                                <div class="col-sm-4">
                                 <input id="input_nama" type="text" class="form-control" name="input_nama"placeholder="Nama Lengkap" value="{{ Session::get('input_nama') }}">
                                </div>
                                  <label class=" col-sm-2 col-form-label">Nomor Identitas</label>
                                <div class="col-sm-4">
                                <input id="input_ktp" type="text" class="form-control" value="{{ Session::get('input_ktp') }}" name="input_ktp" oninput="numberOnly(this.id);" minlength="16" maxlength="16" placeholder="No. Identitas" required>
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Nomor Hp</label>
                                <div class="col-sm-4">
                                  <div class="row">
                                  <div class="col">
                                  <input id="" type="text" class="form-control readonly" value="+62"readonly maxlength="3">
                                </div>
                                <div class="col">
                                  <input type="tel" id="phone" placeholder="No. Whatsapp 1" value="{{ Session::get('input_hp') }}" name="input_hp" maxlength="11" class="form-control"/>
                                </div>
                                </div>
                                </div>
                                <label class=" col-sm-2 col-form-label">Nomor Hp Alternatif</label>
                                <div class="col-sm-4 ">
                                  <div class="row">
                                  <div class="col">
                                    <input id="" type="text" class="form-control readonly" value="+62"readonly maxlength="3">
                                </div>
                                  <div class="col">
                                  <input type="tel" id="phone1" class="form-control" value="{{ Session::get('input_hp_2') }}" name="input_hp_2" placeholder="No. Whatsapp 2" oninput="numberOnly(this.id);" maxlength="11">
                                </div>
                                </div>
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Email</label>
                                <div class="col-sm-4">
                                  <input id="" type="email" class="form-control" value="{{ Session::get('input_email') }}" name="input_email" placeholder="Email">
                                </div>
                                </div>
                                <div class="card-header bg-primary">
                            <span class="text-light">Alamat KTP</span>
                          </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Alamat</label>
                                <div class="col-sm-10">
                                  <input id="" type="email" class="form-control" value="{{ Session::get('input_alamat_ktp') }}" name="input_alamat_ktp" placeholder="Alamat">
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Kecamatan</label>
                                  <div class="col-sm-4">
                                    <input id="" type="text" class="form-control" value="{{ Session::get('kecamatan') }}" name="kecamatan">
                                  </div>
                                  <label class="col-sm-2 col-form-label" >Desa/Kelurahan</label>
                                  <div class="col-sm-4">
                                  <input id="" type="text" class="form-control" value="{{ Session::get('kelurahan') }}" name="kelurahan">
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Kota/Kabupaten</label>
                                  <div class="col-sm-4">
                                    <input id="" type="text" class="form-control" value="{{ Session::get('kota') }}" name="kota">
                                  </div>
                                  <label class="col-sm-1 col-form-label" >RW</label>
                                  <div class="col-sm-2">
                                  <input id="" type="number" class="form-control" value="{{ Session::get('rw') }}" name="rw">
                                </div>
                                  <label class="col-sm-1 col-form-label" >RT</label>
                                  <div class="col-sm-2">
                                  <input id="" type="number" class="form-control" value="{{ Session::get('rt') }}" name="rt">
                                </div>
                              </div>
                              <div class="card-header bg-primary">
                            <span class="text-light">Alamat Pemasangan</span>
                          </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Alamat</label>
                                <div class="col-sm-10">
                                  <input id="" type="email" class="form-control" value="{{ Session::get('input_alamat_pasang') }}" name="input_alamat_pasang" placeholder="Alamat">
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Kecamatan</label>
                                  <div class="col-sm-4">
                                    <input id="" type="text" class="form-control" value="{{ Session::get('kecamatan1') }}" name="kecamatan1">
                                  </div>
                                  <label class="col-sm-2 col-form-label" >Desa/Kelurahan</label>
                                  <div class="col-sm-4">
                                  <input id="" type="text" class="form-control" value="{{ Session::get('kelurahan1') }}" name="kelurahan1">
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Kota/Kabupaten</label>
                                  <div class="col-sm-4">
                                        <select name="kota1" class="form-control" required>
                                          <option value="">--Pilih Kota/Kabupaten--</option>
                                          @foreach($data_site as $s)
                                          <option value="{{$s->id.'|'.$s->site_nama}}">{{$s->site_nama}}</option>
                                          @endforeach
                                        </select>
                                  </div>
                                  <label class="col-sm-1 col-form-label" >RW</label>
                                  <div class="col-sm-2">
                                  <input id="" type="number" class="form-control" value="{{ Session::get('rw1') }}" name="rw1">
                                </div>
                                  <label class="col-sm-1 col-form-label" >RT</label>
                                  <div class="col-sm-2">
                                  <input id="" type="number" class="form-control" value="{{ Session::get('rt1') }}" name="rt1">
                                </div>
                              </div>
                              
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Share Lokasi</label>
                                <div class="col-sm-10">
                                  <input id="input_maps" type="text" class="form-control" value="{{ Session::get('input_maps') }}" name="input_maps" placeholder="Share Location" required>
                                </div>
                              </div>
                              <div class="card-header bg-primary">
                            <span class="text-light">Sales</span>
                          </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Sales</label>
                                <div class="col-sm-4">
                                    <select name="input_sales" class="form-control" required>
                                    <option value="">PILIH</option>
                                    @foreach($data_user as $du)
                                    <option value="{{$du->user_id}}">{{$du->nama_user}}</option>
                                    @endforeach
                                  </select>
                                </div>
                                  <label class=" col-sm-2 col-form-label">Sub Sales</label>
                                <div class="col-sm-4">
                                  <input id="input_subseles" type="text" class="form-control" value="{{ Session::get('input_subseles') }}" name="input_subseles" placeholder="Sub Sales">
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class=" col-sm-2 col-form-label">Keterangan</label>
                                <div class="col-sm-10">
<textarea name="input_keterangan" class="form-control" id="input_keterangan" cols="15" rows="5">
Keterangan :
</textarea>
<span class="text-bold text-danger" style="font-size:12px">Contoh = Keterangan : Pemasangan Perlu tiang</span>                               
                                </div>
                              </div>
                              

                            

                            </div>
                          </div>
                        <div class="card-footer">
                          <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                      </form>
                      
                    </div>
                  </div>
                </div>
            </form>
        </div>
      </section>
@endsection

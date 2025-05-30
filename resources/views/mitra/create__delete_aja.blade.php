@extends('layout.main')
@section('content')

    <section class="content">
        <div class="page-inner">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header bg-primary">
                  <h3 class="card-title text-light">Tambah Data PIC</h3>
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
                      
                      
                      <form action="{{route('admin.mitra.store_pic1')}}" method="POST" class="needs-validation" novalidate>
                          @csrf
                          @method('POST')
                        <div class="card-body ">
                            <div class="after-add-more ">

                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Nama Lengkap</label>
                                <div class="col-sm-4">
                                  <input type="text" id="" class="form-control" name="name" value="{{ Session::get('name') }}">
                                </div>
                                  <label class=" col-sm-2 col-form-label">Nomor Identitas</label>
                                <div class="col-sm-4">
                                  <input type="number" id="" class="form-control" name="ktp" value="{{ Session::get('ktp') }}">
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Email</label>
                                <div class="col-sm-4">
                                  <input type="email" id="" class="form-control" name="email" value="{{ Session::get('email') }}">
                                </div>
                                  <label class=" col-sm-2 col-form-label">Nomor Whatsapp</label>
                                <div class="col-sm-4">
                                  <input type="number" id="" class="form-control" name="hp" value="{{ Session::get('hp') }}">
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Alamat Lengkap</label>
                                <div class="col-sm-10">
                                  <textarea  id="" class="form-control" name="alamat_lengkap" cols="30" rows="5">{{ Session::get('alamat_lengkap') }}</textarea>
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Username</label>
                                <div class="col-sm-4">
                                  <input type="text" id="" class="form-control" name="username" value="{{ Session::get('username') }}">
                                </div>
                                  <label class=" col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-4">
                                  <input type="text" id="" class="form-control" name="password" value="{{ Session::get('password') }}">
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Tanggal Gabung</label>
                                <div class="col-sm-4">
                                  <input type="text" id="" class="form-control datepicker" name="tgl_gabung" value="{{ Session::get('tgl_gabung') }}">
                                </div>
                                  <label class=" col-sm-2 col-form-label">Komisi</label>
                                <div class="col-sm-4">
                                  <input type="text" id="" class="form-control" name="mts_komisi" value="{{ Session::get('mts_komisi') }}">
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class=" col-sm-2 col-form-label">Site</label>
                                <div class="col-sm-4">
                                  <select name="data__site_id" id="" class="form-control">\
                                    <option value="">--Pilih Site--</option>
                                    @foreach ($data_site as $s)
                                        <option value="{{$s->id}}">{{$s->site_nama}}</option>
                                    @endforeach
                                  </select>
                                </div>
                                  <label class=" col-sm-2 col-form-label">Level</label>
                                <div class="col-sm-4">
                                  <select name="level" id="level" class="form-control" required >
                                    <option value="">-PILIH-</option>
                                      <option value="10|BILLER">BILLER</option>
                                      <option value="13|KOLEKTOR">KOLEKTOR</option>
                                      <option value="14|RESELLER">RESELLER</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                        <div class="card-footer">
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                      </form>
                      
                    </div>
                  </div>
                </div>
            </form>
        </div>
      </section>
@endsection


    {{-- <section class="content">
        <div class="page-inner">
            <form action="{{route('admin.mitra.addmitra')}}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="row">
                  <div class="col-md-6">
                    <div class="card">
                      <div class="card-header bg-primary">
                        <h3 class="card-title text-light">Tambah Data</h3>
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


                        <div class="card-body ">
                            <div class="form-group">
                              <label for="name">Nama Lengkap</label>
                              <input type="text" class="form-control" id="validationCustom05" name="name" required value="{{ old('name') }}">
                              <div class="invalid-feedback">
                                  Nama tidak boleh kosong
                              </div>
                          </div>
                            <div class="form-group">
                              <label for="ktp">Nomor Identitas</label>
                              <input type="number" class="form-control" id="ktp" name="ktp" required value="{{ old('ktp') }}">
                              <div class="invalid-feedback">
                                  Nomor Identitas tidak boleh kosong
                              </div>
                          </div>
                            <div class="form-group">
                              <label for="hp">Nomor Whatsapp</label>
                              <input type="number" class="form-control" id="hp" name="hp" required value="{{ old('hp') }}">
                              <div class="invalid-feedback">
                                  Nomor Whatsapp tidak boleh kosong
                              </div>

                          </div>
                            <div class="form-group">
                              <label for="alamat_lengkap">Alamat Lengkap</label>
                              <input type="text" class="form-control" id="alamat_lengkap" name="alamat_lengkap" required value="{{ old('alamat_lengkap') }}" >
                              <div class="invalid-feedback">
                                  Alamat tidak boleh kosong
                              </div>
                          </div>
                            <div class="form-group">
                              <label for="email">Email</label>
                              <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" >
                              <div class="invalid-feedback">
                                  Email tidak boleh kosong
                              </div>
                      </div>
                      <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required value="{{ old('username') }}">
                        <div class="invalid-feedback">
                          Username tidak boleh kosong
                      </div>
                </div>
                      <div class="form-group">
                          <label for="password" >Password</label>
                          <input type="password" class="form-control" id="password" name="password" required >
                          <div class="invalid-feedback">
                          Password tidak boleh kosong
                        </div>
                            </div>
                      <div class="form-group">
                          <label for="password" >Tanggal Gabung</label>
                          <input type="text" class="form-control datepicker" id="tgl_gabung"  name="tgl_gabung" required value="{{ old('tgl_gabung') }}">
                          <div class="invalid-feedback">
                            Tanggal Bergabung tidak boleh kosong
                        </div>
                            </div>
                            <div class="form-group">
                                <label>Limit Minus</label>
                                <input type="number" class="form-control" id="limit_minus"  name="limit_minus" value="{{ old('limit_minus') }}" required>
                                <div class="invalid-feedback">
                                    Limit Minus tidak boleh kosong
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Kode Unik</label>
                                <input type="number" class="form-control" id="kode_unik"  name="kode_unik" value="0" required>
                                <div class="invalid-feedback">
                                    Kode Unik tidak boleh kosong
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Komisi Biller</label>
                                <input type="number" class="form-control" id="mts_komisi"  name="mts_komisi" value="0" required>
                                <div class="invalid-feedback">
                                    Komisi Biller tidak boleh kosong
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Komisi Sales</label>
                                <input type="number" class="form-control" id="mts_komisi_sales"  name="mts_komisi_sales" value="0" required>
                                <div class="invalid-feedback">
                                    Komisi Sales tidak boleh kosong
                                </div>
                            </div>
                      <div class="form-group">
                          <label for="level" >Level</label>
                          <select name="level" id="level" class="form-control" required >
                              <option value="">-PILIH-</option>
                              <option value="10|BILLER">BILLER</option>
                              <option value="12|SALES">SALES</option>
                              <option value="13|KOLEKTOR">KOLEKTOR</option>
                              <option value="14|RESELLER">RESELLER</option>
                              <option value="15|PIC LINGKUNGAN">PIC LINGKUNGAN</option>
                            </select>
                            <div class="invalid-feedback">
                              Pilih Level terlebih dahulu
                          </div>
                            </div>
                                </div>
                        <div class="card-footer">
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
            </form>
        </div>
      </section>

@endsection --}}

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
                      
                      
                      <form action="{{route('admin.mitra.store_pic1',['Mitra='.$Mitra])}}" method="POST" class="needs-validation" novalidate>
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
                                  <label class="col-sm-2 col-form-label" >Alamat</label>
                                <div class="col-sm-10">
                                  <input type="text" placeholder="Kp / Perumahan / Jalan " value="{{ Session::get('alamat_lengkap') }}" class="form-control" name="alamat_lengkap">
                                </div>
                              </div>
                               
                            
                              <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Kota/Kabupaten</label>
                                <div class="col-sm-4">
                                  <select name="data__site_id" id="kota"  class="form-control">
                                    @if(Session::get('data__site_id'))  
                                    <option value="{{Session::get('data__site_id')}}">{{Session::get('site_nama')}}</option>
                                     @endif
                                  </select>
                                  {{-- <input  type="text" class="form-control readonly" value="{{ Session::get('data__site_id') }}"  placeholder="Kota/Kabupaten"> --}}
                              </div>
                                <label class="col-sm-2 col-form-label">RW/RT</label>
                                <div class="col-sm-2">
                                <input id="rw" type="number" class="form-control" value="{{ Session::get('rw') }}" name="rw" placeholder="RW">
                              </div>
                                <div class="col-sm-2">
                                <input id="rt" type="number" class="form-control" value="{{ Session::get('rt') }}" name="rt" placeholder="RT">
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label">Kecamatan</label>
                                <div class="col-sm-4">
                                <input id="kecamatan" type="text" class="form-control readonly" value="{{ Session::get('kecamatan') }}" name="kecamatan" placeholder="kecamatan">
                              </div>
                                <label class="col-sm-2 col-form-label">Kelurahan</label>
                                <div class="col-sm-4 notif_validasi">
                                <input id="val_kelurahan" type="text" class="form-control" value="{{ Session::get('kelurahan') }}" name="kelurahan" placeholder="Kelurahan">
                              <div class="text-danger" id="pesan"></div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label">Wilayah PIC</label>
                          <label class="form-check col-sm-2 col-form-label">Kelurahan&nbsp;&nbsp;
                            <input class="form-check-input checkbox_kel" type="checkbox" id="wil_kel" name="wil_kel" value="1" @if( Session::get('wil_kel')) checked @endif >
                            <span class="form-check-sign"></span>
                          </label>
                          <label class="form-check col-sm-2 col-form-label">RW&nbsp;&nbsp;
                            <input class="form-check-input checkbox_rw" type="checkbox" id="wil_rw" name="wil_rw" value="1" @if( Session::get('wil_rw')) checked @endif>
                            <span class="form-check-sign"></span>
                            <div class="text-danger" id="pesan1"></div>
                                    </label>

                                         <label class=" col-sm-2 col-form-label">Wilayah</label>
                                <div class="col-sm-4">
                                  <input type="text" id="wilayah" class="form-control" name="wilayah" value="{{ Session::get('wilayah') }}">
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
                                  <input type="text" id="mts_komisi" class="form-control" name="mts_komisi" value="{{ Session::get('mts_komisi') ?? '0' }}">
                                </div>
                              </div>
                              @if($Mitra)
                              <div class="form-group row">
                                <div class="col-sm-6">
                              </div>
                                  <label class=" col-sm-2 col-form-label">Limit Minus</label>
                                  <div class="col-sm-4">
                                  <input type="number" id="" class="form-control" name="mts_limit_minus" value="{{ Session::get('mts_limit_minus') ?? '0' }}">
                                </div>
                              </div>
                              @endif
                              <div class="form-group row">
                                  <label class=" col-sm-2 col-form-label">Level</label>
                                <div class="col-sm-4">
                                  <select name="level" id="level" class="form-control" required >
                                    @if($Mitra)
                                    <option value="">--Pilih Level--</option>
                                       <option value="10|BILLER">BILLER</option>
                                      <option value="13|KOLEKTOR">KOLEKTOR</option>
                                      <option vaslue="14|RESELLER">RESELLER</option>
                                    @else
                                    <option value="">--Pilih Level--</option>
                                    <option value="12|SALES">SALES</option>
                                    <option value="15|PIC">PIC</option>
                                    @endif
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

      <script type="text/javascript">
          $(document).ready(function() {
            $(".add-more").click(function(){ 
                var html = $(".copy").html();
                $(".after-add-more").after(html);
            });
      
            // saat tombol remove dklik control group akan dihapus 
            $("body").on("click",".remove",function(){ 
                $(this).parents(".control-group").remove();
            });
          });
      </script>
@endsection

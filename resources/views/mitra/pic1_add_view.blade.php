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

@extends('layout.main')
@section('content')

    <section class="content">
        <div class="page-inner">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header bg-primary">
                  <h3 class="card-title text-light">Edit data Sub-Pic | Pic - {{$pic_mitra->user_mitra->name}}  </h3>
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
                      
                      
                      <form action="{{route('admin.mitra.store_edit_pic_sub',['id'=>$data_sub_pic->user_submitra->mts_sub_user_id])}}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
                          @csrf
                          @method('PUT')
                        <div class="card-body ">
                            <div class="after-add-more ">
                                <div class="form-group row">
                                  <label class=" col-sm-2 col-form-label">Pic Id</label>
                                <div class="col-sm-4">
                                  <input type="text" id="" class="form-control" name="mts_sub_mitra_id" value="{{$pic_mitra->user_mitra->id}}" readonly>
                                </div>
                                  <label class="col-sm-2 col-form-label" >Sub Pic Id</label>
                                <div class="col-sm-4">
                                  <input type="number" id="" class="form-control" name="mts_sub_user_id" value="{{$data_sub_pic->user_submitra->mts_sub_user_id}}" readonly>
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Nama Lengkap</label>
                                <div class="col-sm-4">
                                  <input type="text" id="" class="form-control" name="name" value="{{$data_sub_pic->name}}">
                                </div>
                                  <label class=" col-sm-2 col-form-label">Nomor Identitas</label>
                                <div class="col-sm-4">
                                  <input type="number" id="" class="form-control" name="ktp" value="{{$data_sub_pic->ktp}}">
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Email</label>
                                <div class="col-sm-4">
                                  <input type="email" id="" class="form-control" name="email" value="{{$data_sub_pic->email}}">
                                </div>
                                  <label class=" col-sm-2 col-form-label">Nomor Whatsapp</label>
                                <div class="col-sm-4">
                                  <input type="number" id="" class="form-control" name="hp" value="{{$data_sub_pic->hp}}">
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Alamat Lengkap</label>
                                <div class="col-sm-10">
                                  <textarea  id="" class="form-control" name="alamat_lengkap" cols="30" rows="5">{{$data_sub_pic->alamat_lengkap}}</textarea>
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Username</label>
                                <div class="col-sm-4">
                                  <input type="text" id="" class="form-control" name="username" value="{{$data_sub_pic->username}}">
                                </div>
                                  <label class=" col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-4">
                                  <input type="text" id="" class="form-control" name="password" value="">
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Tanggal Gabung</label>
                                <div class="col-sm-4">
                                  <input type="text" id="" class="form-control datepicker" name="tgl_gabung" value="{{date('d-m-Y', strtotime($data_sub_pic->tgl_gabung ))}}" readonly>
                                </div>
                                  <label class=" col-sm-2 col-form-label">Fee Continue</label>
                                <div class="col-sm-4">
                                  <input type="text" id="" class="form-control" name="mts_komisi" value="{{$data_sub_pic->user_mitra->mts_komisi}}">
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Upload Foto</label>
                                <div class="col-sm-4">
                                  <input type="file" class="form-control-file" name="file">
                                </div>
                                  <label class=" col-sm-2 col-form-label">Site</label>
                                <div class="col-sm-4">
                                  <select name="data__site_id" id="" class="form-control">
                                    <option value="{{$data_sub_pic->user_site->id}}">{{$data_sub_pic->user_site->site_nama}}</option>
                                  </select>
                                </div>
                              </div>
                               <div class="form-group row">
                                  <label class=" col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-4">
                                  <select name="status_user" id="" class="form-control" required>
                                        <option value="{{$data_sub_pic->status_user}}">{{$data_sub_pic->status_user}}</option>
                                        <option value="Enable">Enable</option>
                                        <option value="Disable">Disable</option>
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

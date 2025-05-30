@extends('layout.main')
@section('content')

    <section class="content">
        <div class="page-inner">
            <form action="{{route('admin.mitra.store_edit',['id'=>$data_mitra->mts_user_id])}}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('POST')
                <div class="row">
                  <div class="col-md-6">
                    <div class="card">
                      <div class="card-header bg-primary">
                        <h3 class="card-title">EDIT MITRA</h3>
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
                              <input type="text" class="form-control" id="validationCustom05" name="name" required value="{{$data_mitra->nama}}">
                              <div class="invalid-feedback">
                                  Nama tidak boleh kosong
                              </div>
                          </div>
                            <div class="form-group">
                              <label for="ktp">Nomor Identitas</label>
                              <input type="number" class="form-control" id="ktp" name="ktp" required value="{{$data_mitra->ktp}}">
                              <div class="invalid-feedback">
                                  Nomor Identitas tidak boleh kosong
                              </div>
                          </div>
                            <div class="form-group">
                              <label for="hp">Nomor Whatsapp</label>
                              <input type="number" class="form-control" id="hp" name="hp" required value="{{$data_mitra->hp}}">
                              <div class="invalid-feedback">
                                  Nomor Whatsapp tidak boleh kosong
                              </div>

                          </div>
                            <div class="form-group">
                              <label for="alamat_lengkap">Alamat Lengkap</label>
                              <input type="text" class="form-control" id="alamat_lengkap" name="alamat_lengkap" required value="{{$data_mitra->alamat_lengkap}}" >
                              <div class="invalid-feedback">
                                  Alamat tidak boleh kosong
                              </div>
                          </div>
                            <div class="form-group">
                              <label for="email">Email</label>
                              <input type="email" class="form-control" id="email" name="email" value="{{$data_mitra->email}}" >
                              <div class="invalid-feedback">
                                  Email tidak boleh kosong
                              </div>
                      </div>
                      <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required value="{{$data_mitra->username}}">
                        <div class="invalid-feedback">
                          Username tidak boleh kosong
                      </div>
                </div>
                      <div class="form-group">
                          <label for="password" >Password</label>
                          <input type="password" class="form-control" id="password" name="password" >
                            </div>
                            <div class="form-group">
                                <label>Limit Minus</label>
                                <input type="number" class="form-control" id="limit_minus"  name="limit_minus" value="{{$data_mitra->mts_limit_minus}}" required>
                                <div class="invalid-feedback">
                                    Limit Minus tidak boleh kosong
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Komisi Biller</label>
                                <input type="number" class="form-control" id="mts_komisi"  name="mts_komisi" value="{{$data_mitra->mts_komisi}}" required>
                                <div class="invalid-feedback">
                                    Komisi Biller tidak boleh kosong
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Komisi Sales</label>
                                <input type="number" class="form-control" id="mts_komisi_sales"  name="mts_komisi_sales" value="{{$data_mitra->mts_komisi_sales}}" required>
                                <div class="invalid-feedback">
                                    Komisi Sales tidak boleh kosong
                                </div>
                            </div>
                      <div class="form-group">
                          <label for="level" >Level</label>
                          <select name="level" id="level" class="form-control" required >
                            @if($data_mitra->role_id)
                            <option value="{{$data_mitra->role_id}}|{{$data_mitra->role_name}}" selected>{{$data_mitra->role_name}}</option>
                            @endif
                              <option value="">-PILIH-</option>
                              <option value="10|BILLER">BILLER</option>
                            <option value="13|KOLEKTOR">KOLEKTOR</option>
                            <option value="14|RESELLER">RESELLER</option>
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

@endsection

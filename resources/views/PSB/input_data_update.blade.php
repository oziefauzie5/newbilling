@extends('layout.main')
@section('content')

    <section class="content">
        <div class="page-inner">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header bg-primary">
                  <h3 class="card-title text-light">Input Data</h3>
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
                      
                      
                      <form action="{{route('admin.psb.input_data_update',['id'=>$data->id])}}" method="POST" class="needs-validation" novalidate>
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
                                 <input id="input_nama" type="text" class="form-control" name="input_nama"placeholder="Nama Lengkap" value="{{$data->input_nama ?? ''}}">
                                </div>
                                  <label class=" col-sm-2 col-form-label">Nomor Identitas</label>
                                <div class="col-sm-4">
                                <input id="input_ktp" type="text" class="form-control" value="{{$data->input_ktp ?? ''}}" name="input_ktp" oninput="numberOnly(this.id);" minlength="16" maxlength="16" placeholder="No. Identitas" required>
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Nomor Hp</label>
                                <div class="col-sm-4">
                                  <input id="" type="text" class="form-control" value="{{$data->input_hp ?? ''}}" name="input_hp" placeholder="No. Whatsapp 1" oninput="numberOnly(this.id);" minlength="10" maxlength="13">
                                </div>
                                <label class=" col-sm-2 col-form-label">Nomor Hp Alternatif</label>
                                <div class="col-sm-4">
                                  <input id="" type="text" class="form-control" value="{{$data->input_hp_2 ?? ''}}" name="input_hp_2" placeholder="No. Whatsapp 2" oninput="numberOnly(this.id);" minlength="10" maxlength="13">
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Email</label>
                                <div class="col-sm-4">
                                  <input id="" type="email" class="form-control" value="{{$data->input_email ?? ''}}" name="input_email" placeholder="Email">
                                </div>
                                </div>
                                <div class="card-header bg-primary">
                            <span class="text-light">Alamat KTP</span>
                          </div>
                            <div class="form-group row">
                                  <label class=" col-sm-2 col-form-label">Alamat KTP</label>
                                <div class="col-sm-10">
                                <textarea name="input_alamat_ktp" class="form-control readonly" id="" cols="15" rows="5">
{{$data->input_alamat_ktp ?? ''}}
                                </textarea>
                                </div>
                              </div>
                              <div class="card-header bg-primary">
                            <span class="text-light">Alamat Pemasangan</span>
                          </div>
                           <div class="form-group row">
                                  <label class=" col-sm-2 col-form-label">Alamat Pemasangan</label>
                                <div class="col-sm-10">
                                <textarea name="input_alamat_pasang" class="form-control " id="" cols="15" rows="5">
{{$data->input_alamat_pasang ?? ''}}
                                </textarea>
                                </div>
                              </div>
                              
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Share Lokasi</label>
                                <div class="col-sm-10">
                                  <input id="input_maps" type="text" class="form-control" value="{{$data->input_maps ?? ''}}" name="input_maps" placeholder="Share Location" required>
                                </div>
                              </div>
                              <div class="card-header bg-primary">
                            <span class="text-light">Sales</span>
                          </div>
                              <div class="form-group row">
                                  <label class="col-sm-2 col-form-label" >Sales</label>
                                <div class="col-sm-4">
                                    <select name="input_sales" class="form-control" required>
                                      @if($data->input_sales)
                                      <option value="{{$data->input_sales ?? ''}}">{{$data->user_sales->name ?? ''}}</option>
                                      @else
                                      <option value="">--None--</option>
                                      @endif
                                  </select>
                                </div>
                                  <label class=" col-sm-2 col-form-label">Sub Sales</label>
                                <div class="col-sm-4">
                                  <input id="input_subseles" type="text" class="form-control" value="{{$data->input_subseles ?? ''}}" name="input_subseles" placeholder="Sub Sales">
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class=" col-sm-2 col-form-label">Keterangan</label>
                                <div class="col-sm-10">
<textarea name="input_keterangan" class="form-control" id="input_keterangan" cols="15" rows="5">
{{$data->input_keterangan ?? ''}}
</textarea>
<span class="text-bold text-danger" style="font-size:12px">Contoh = Keterangan : Pemasangan Perlu tiang</span>                                
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label class=" col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-4">
                                   <select name="input_status" id="" class="form-control">
                                    @if($data->input_status)
                                    <option value="{{$data->input_status ?? ''}}">{{$data->input_status ?? ''}}</option>
                                    @endif
                                    <option value="INPUT DATA">INPUT DATA</option>
                                    <option value="REGIST">REGIST</option>
                                    <option value="MIGRASI">MIGRASI</option>
                                    <option value="PUTUS BERLANGGAN">PUTUS BERLANGGAN</option>
                                  </select>
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

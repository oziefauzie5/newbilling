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
           
              <form action="{{route('admin.biller.sales_store')}}" method="POST">
                @csrf
                @method('POST')
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Nama Lengkap</label>
                      <input id="input_nama" type="text" class="form-control" name="input_nama"placeholder="Nama Lengkap" value="{{ Session::get('input_nama') }}" required>
                      <input id="id" type="hidden" class="form-control" name="id"value="{{ rand(10000,99999) }}" required>
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
                      <input id="input_email" type="text" class="form-control" value="{{ Session::get('input_email') }}" name="input_email" placeholder="Email">
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Alamat Domisili</label>
                      <input id="input_alamat_ktp" type="text" class="form-control" value="{{ Session::get('input_alamat_ktp') }}" name="input_alamat_ktp" placeholder="Alamat KTP">
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Alamat Pasang</label>
                      <input id="input_alamat_pasang" type="text" class="form-control" value="{{ Session::get('input_alamat_pasang') }}" name="input_alamat_pasang" placeholder="Alamat Pemasangan">
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Paket </label>
                      <select class="form-control" name="input_keterangan" id="" >
                        @foreach ($paket as $p)
                           <option value="{{$p->paket_nama}}">{{$p->paket_nama}}</option> 
                        @endforeach
                        <option value="">--Pilih Paket--</option>
                        {{-- <option value="10 Mbps">10 Mbps</option>
                        <option value="15 Mbps">15 Mbps</option>
                        <option value="20 Mbps">20 Mbps</option>
                        <option value="30 Mbps">30 Mbps</option> --}}
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Share Location </label>
                      <input id="input_maps" type="text" class="form-control" value="{{ Session::get('input_maps') }}" name="input_maps" placeholder="Masukan Link Share Location" required>
                    </div>
                  </div>
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </form>
                  <a href="{{route('admin.biller.sales')}}"><button type="button" class="btn btn-primary">Kembali</button></a>
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
@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
      <div class="card">
          <div class="card-body">
          
              <div class="row">
            <div class="col-3">
            <a href="{{route('admin.vhc.data_pesanan')}}"><button class="btn btn-primary btn-sm mb-3 btn-block" type="button" >Kembali</button></a>
            </div>
            <div class="col-3">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambah-outlet">
                Tambah Outlet
                </button>

                <!-- Modal -->
                <div class="modal fade" id="tambah-outlet" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" >TAMBAH OUTLET</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin.vhc.store_outlet')}}" method="post">
                            @csrf
                            @method('POST')
                        <div class="form-group">
                            <label>Mitra</label>
                            <select name="outlet_mitra" id="" class="form-control">
                                <option value="">PILIH OUTLET</option>
                                @foreach($data_mitra as $mitra)
                                <option value="{{$mitra->mts_user_id}}">{{$mitra->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nama Outlet</label>
                            <input type="text" value="" name="outlet_nama" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Nama Pemilik</label>
                            <input type="text" value="" name="outlet_pemilik" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>No Whatsapp</label>
                            <input type="number" value="" name="outlet_hp" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" value="" name="outlet_alamat" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Bergabung</label>
                            <input type="text" value="" name="outlet_tgl_gabung" class="form-control datepicker">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="outlet_status" id="" class="form-control">
                                <option value="">Pilih Status</option>
                                <option value="Enable">Enable</option>
                                <option value="Disable">Disable</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                    </div>
                    </div>
                </div>
                </div>
            </div>
         
          <hr>
          
           <div class="table-responsive">
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
            <table id="input_data" class="display table table-striped table-hover" >                          
                    <thead>
                        <tr class="text-center">
                            <th>Id Outlet</th>
                            <th>Nama</th>
                            <th>Pemilik</th>
                            <th>No Hp</th>
                            <th>Mitra</th>
                            <th>Alamat</th>
                            <th>Tgl Gabung</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data_outlet as $d)
                        <tr>
                            <td>{{$d->outlet_id}}</td>
                            <td>{{$d->outlet_nama}}</td>
                            <td>{{$d->outlet_pemilik}}</td>
                            <td>{{$d->outlet_hp}}</td>
                            <td>{{$d->outlet_mitra}}</td>
                            <td>{{$d->outlet_alamat}}</td>
                            <td>{{$d->outlet_tgl_gabung}}</td>
                            <td>{{$d->outlet_status}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
         </div>
      </div>
    </div>
  </div>
</div>





 


 
@endsection

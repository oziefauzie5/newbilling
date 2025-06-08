@extends('layout.main')
@section('content')
<style>
  .notice{
    font-size:11px;
    color:red;
    font-weight: bold;
  }
</style>
<div class="content">
  <div class="page-inner">
    <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header bg-primary">
              <h3 class="card-title text-light">Data Promo</h3>
            </div>
            <div class="card-body table-responsive -sm">
              <button class="btn  btn-sm ml-auto m-1 btn-primary" data-toggle="modal" data-target="#modal-adduser"><i class="fas fa-solid fa-plus"></i>Tambah Promo</button>

            </div>
              
              <!-- ----------------------------------------------------------------------MODAL ADD AKUN------------------------------------------------ -->
              
              <div class="modal fade" id="modal-adduser">
                  <div class="modal-dialog modal-lg">
                    <form action="{{route('admin.psb.store_promo')}}" method="POST">
                      @csrf
                      @method('POST')
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h4 class="modal-title">TAMBAH PROMO </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                            <div class="card-body">
                                <div class="row">
                              <div class="col-6 form-group">
                                  <label>Nama Promo</label>
                                  <input type="text" class="form-control" value="{{Session::get('promo_nama')}}" name="promo_nama">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Promo Id</label>
                                  <input type="text" class="form-control" value="{{Session::get('promo_id')}}" name="promo_id">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Promo Harga</label>
                                  <input type="text" class="form-control" value="{{Session::get('promo_harga')}}" name="promo_harga">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Promo Expired</label>
                                  <input type="text" class="form-control datepicker" value="{{Session::get('promo_expired')}}" name="promo_expired">
                              </div>
                              <div class="col-12 form-group">
                                <label>Status</label>
                                <select name="promo_status" class="form-control">
                                  @if(Session::get('promo_status'))
                                  <option value="{{Session::get('promo_status')}}">{{Session::get('promo_status')}}</option>
                                  @endif
                                  <option value="">--Pilih Status--</option>
                                  <option value="Enable">Enable</option>
                                  <option value="Disable">Disable</option>
                                </select>
                              </div>
                            </div>
                            </div>

                      </div>
                      <div class="modal-footer justify-content-between">
                              <button type="button" class="btn" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                          </div>
                        </form>
                  </div>
                </div>
              <!-- -------------------------------------------------------------------END MODAL ADD AKUN------------------------------------------------ -->
            <div class="card-body table-responsive -sm">
              @if ($errors->any())
                      <div class="alert alert-danger" role="alert">
                        <ul>
                          @foreach ($errors->all() as $item)
                              <li>{{ $item }}</li>
                          @endforeach
                        </ul>
                        </div>
              @endif
                <table id="input_data" class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Promo Nama</th>
                      <th>Promo Id</th>
                      <th>Promo Harga</th>
                      <th>Promo Expired</th>
                      <th>Promo Status</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($data_promo as $d)
                    <tr>
                      
                      
                      <td>{{$d->promo_nama ?? ''}}</td>
                      <td>{{$d->promo_id ?? ''}}</td>
                      <td>{{$d->promo_harga ?? ''}}</td>
                      <td>{{$d->promo_expired ?? ''}}</td>
                      <td>{{$d->promo_status ?? ''}}</td>
                      <td>
                        <div class="form-button-action">
                            <button type="button"class="btn btn-link btn-primary btn-lg" data-toggle="modal" data-target="#modal-edit{{$d->id}}">
                              <i class="fa fa-pen"></i>
                            </button></div>
                        </td>

                        {{-- -------------------------------MODAL ODP------------------------------------ --}}
                      <div class="modal fade" id="modal-edit{{$d->id}}">
                          <div class="modal-dialog modal-lg">
                            <form action="{{route('admin.psb.update_promo',['id'=>$d->id])}}" method="POST">
                              @csrf  
                            @method('PUT')
                            <div class="modal-content">
                              <div class="modal-header bg-primary ">
                                <h4 class="modal-title">Promo Edit </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <div class="card-body">
                                  <div class="row">
                                     <div class="col-6 form-group">
                                  <label>Nama Promo</label>
                                  <input type="text" class="form-control" value="{{$d->promo_nama}}" name="promo_nama">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Promo Id</label>
                                  <input type="text" class="form-control" value="{{$d->promo_id}}" name="promo_id">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Promo Harga</label>
                                  <input type="text" class="form-control" value="{{$d->promo_harga}}" name="promo_harga">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Promo Expired</label>
                                  <input type="text" class="form-control" value="{{$d->promo_expired}}" name="promo_expired">
                              </div>
                              <div class="col-12 form-group">
                                <label>Status</label>
                                <select name="promo_status" class="form-control">
                                  @if($d->promo_status)
                                  <option value="{{$d->promo_status}}">{{$d->promo_status}}</option>
                                  @endif
                                  <option value="">--Pilih Status--</option>
                                  <option value="Enable">Enable</option>
                                  <option value="Disable">Disable</option>
                                </select>
                              </div>
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer justify-content-between">
                                      <button type="button" class="btn" data-dismiss="modal">Close</button>
                                      <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                  </div>
                                </form>
                          </div>
                        </div>
                      </tr>
                  @endforeach
                </table>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>

@endsection
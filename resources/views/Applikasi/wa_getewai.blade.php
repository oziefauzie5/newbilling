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
                <button class="btn btn-sm" data-toggle="modal" data-target="#modal-adduser" class="btn btn-primary btn-sm"><i class="fas fa-solid fa-plus"></i>Tambah</button>
              </div>
              <!-- ----------------------------------------------------------------------MODAL ADD AKUN------------------------------------------------ -->
              
              <div class="modal fade" id="modal-adduser">
                  <div class="modal-dialog">
                    <form action="{{route('admin.app.store_wa_getewai')}}" method="POST">
                      @csrf
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h4 class="modal-title">TAMBAH WHASTAPP GETEWAI </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">

                        
                            <div class="card-body">
                              <div class="form-group">
                                  <label>Whatsapp Agent</label>
                                  <select name="wa_nama" class="form-control" id="">
                                    <option value="">--Pilih Agent--</option>
                                    <option value="CS">CS</option>
                                    <option value="NOC">NOC</option>
                                    <option value="NOTIF">NOTIF</option>
                                  </select>
                              </div>
                              <div class="form-group">
                                <label>Whatsapp Key</label>
                                <input type="text" class="form-control" name="wa_key">
                            </div>
                              <div class="form-group">
                                  <label>Whatsapp Ulr</label>
                                  <input type="text" class="form-control"  name="wa_url" >
                              </div>
                              <div class="form-group">
                                  <label>Nomor Whatsapp</label>
                                  <input type="text" class="form-control"  name="wa_nomor" >
                              </div>
                              <div class="form-group">
                                  <label>Status</label>
                                  <select name="wa_status" class="form-control" required>
                                    <option value="Enable">Enable</option>
                                    <option value="Disable">Disable</option>
                                  </select>
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
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Agent</th>
                      <th>Key</th>
                      <th>Url</th>
                      <th>Nomor</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($data_whatsapp as $d)
                      <tr>
                        <td>{{$d->wa_nama}}</td>
                        <td>{{$d->wa_key}}</td>
                        <td>{{$d->wa_url}}</td>
                        <td>{{$d->wa_nomor}}</td>
                        <td>{{$d->wa_status}}</td>
                        <td>
                          <div class="form-button-action">
                              <button type="button" data-toggle="modal" data-target="#modal-edit{{$d->id}}" class="btn btn-link btn-primary btn-lg">
                                <i class="fa fa-edit"></i>
                              </button>
                            </div>
                          </td>
                      <div class="modal fade" id="modal-edit{{$d->id}}">
                          <div class="modal-dialog">
                            <form action="{{route('admin.app.update_wa_getewai',['id'=>$d->id])}}" method="POST">
                            @csrf  
                            @method('PUT')
                            <div class="modal-content">
                              <div class="modal-header bg-primary ">
                                <h4 class="modal-title">EDIT WHASTAPP GETEWAI </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <div class="card-body">
                                 <div class="form-group">
                                  <label>Whatsapp Agent</label>
                                  <select name="wa_nama" class="form-control" id="">
                                    @if($d->wa_nama)
                                    <option value="{{$d->wa_nama}}">{{$d->wa_nama}}</option>
                                    @endif
                                    <option value="CS">CS</option>
                                    <option value="NOC">NOC</option>
                                    <option value="NOTIF">NOTIF</option>
                                  </select>
                              </div>
                                  <div class="form-group">
                                    <label>Whatsapp Key</label>
                                    <input type="text" class="form-control" name="wa_key" value="{{$d->wa_key}}">
                                </div>
                                  <div class="form-group">
                                      <label>Whatsapp Ulr</label>
                                      <input type="text" class="form-control"  name="wa_url" value="{{$d->wa_url}}">
                                  </div>
                                  <div class="form-group">
                                      <label>Nomor Whatsapp</label>
                                      <input type="text" class="form-control"  name="wa_nomor" value="{{$d->wa_nomor}}">
                                  </div>
                                  <div class="form-group">
                                      <label>Status</label>
                                      <select name="wa_status" class="form-control" required>
                                        @if($d->wa_status)
                                        <option value="{{$d->wa_status}}">{{$d->wa_status}}</option>
                                        @endif
                                        <option value="Enable">Enable</option>
                                        <option value="Disable">Disable</option>
                                      </select>
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
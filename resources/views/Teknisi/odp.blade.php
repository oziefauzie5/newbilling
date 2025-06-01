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
              <h3 class="card-title text-light">Data Odp</h3>
            </div>
            <div class="card-body table-responsive -sm">
              <button class="btn  btn-sm ml-auto m-1 btn-primary" data-toggle="modal" data-target="#modal-adduser"><i class="fas fa-solid fa-plus"></i>Tambah ODP</button>
              <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#import">
                <i class="fa fa-file-import"></i> Import
              </button>
              <a href="{{route('admin.topo.odc')}}"><button class="btn  btn-sm ml-auto m-1 btn-primary"><i class="fas fa-solid fa-route"></i> ODP Distibusi</button></a>
              <a href="{{route('admin.topo.olt')}}"><button class="btn  btn-sm ml-auto m-1 btn-primary"><i class="fas fa-solid fa-route"></i> OLT</button></a>
              <a href="{{route('admin.topo.index')}}"><button class="btn  btn-sm ml-auto m-1 btn-primary"><i class="fas fa-solid fa-route"></i> Router</button></a>
            </div>
          <!-- Modal Import -->
          <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header no-bd">
                  <h5 class="modal-title">
                    <span class="fw-mediumbold">
                    Import Odp</span> 
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{route('admin.export.import_odp')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Pilih file (EXCEL,CSV)</label>
                          <input id="import" type="file" class="form-control" name="file" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer no-bd">
                    <button type="submit" class="btn btn-success">Add</button>
                  </form>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
              
              <!-- ----------------------------------------------------------------------MODAL ADD AKUN------------------------------------------------ -->
              
              <div class="modal fade" id="modal-adduser">
                  <div class="modal-dialog modal-lg">
                    <form action="{{route('admin.topo.odp_store')}}" method="POST" enctype="multipart/form-data">
                      @csrf
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h4 class="modal-title">TAMBAH ODP </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 form-group">
                                      <label>ODP Distribusi</label>
                                      <select class="form-control" id="" name="data__odc_id"  >
                                        <option value="">--Pilih Odp Distribusi--</option>
                                        @foreach ($data_odc as $d)
                                        <option value="{{$d->id}}">{{$d->odc_nama ?? ''}} -|- {{$d->olt_nama ?? ''}} -|- {{$d->router_nama ?? ''}} -|- {{$d->pop_nama ?? ''}} -|- {{$d->site_nama ?? ''}}</option>
                                        @endforeach
                                      </select>
                                  </div>
                              <div class="col-6 form-group">
                                  <label>Nama Odp</label>
                                  <input type="text" class="form-control" value="{{Session::get('odp_nama')}}" name="odp_nama">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Odp Id</label>
                                  <input type="text" class="form-control" value="{{Session::get('odp_id')}}" name="odp_id">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Slot ODP Distribusi </label>
                                    <select name="odp_slot_odc" id="" class="form-control">
                                      @if(Session::get('odp_slot_odc'))
                                      <option value="{{Session::get('odp_slot_odc')}}">{{Session::get('odp_slot_odc')}}</option>
                                      @endif
                                    <option value="">- Pilih Slot ODP Distribusi -</option>
                                      <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                  </select>
                              </div>
                              <div class="col-6 form-group">
                                  <label>Core Kabel </label>
                                  <select name="odp_core" id="" class="form-control">
                                      @if(Session::get('odp_core'))
                                      <option value="{{Session::get('odp_core')}}">{{Session::get('odp_core')}}</option>
                                      @endif
                                    <option value="">- Pilih Core Kabel -</option>
                                      <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                  </select>
                              </div>
                              <div class="col-6 form-group">
                                  <label>Jumlah Slot Spliter Odp </label>
                                  <select name="odp_jumlah_slot" id="" class="form-control">
                                      @if(Session::get('odp_jumlah_slot'))
                                      <option value="{{Session::get('odp_jumlah_slot')}}">{{Session::get('odp_jumlah_slot')}}</option>
                                      @endif
                                    <option value="">- Pilih Core Kabel -</option>
                                      <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                  </select>
                              </div>
                              <div class="col-6 form-group">
                                  <label>Keterangan</label>
                                  <input type="text" class="form-control"  value="{{Session::get('odp_keterangan')}}" name="odp_keterangan">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Koodinat</label>
                                  <input type="text" class="form-control"  value="{{Session::get('odp_koordinat')}}" name="odp_koordinat">
                              </div>
                              <div class="col-12 form-group">
                                <label>Status</label>
                                <select name="odp_status" class="form-control">
                                  @if(Session::get('odp_status'))
                                  <option value="{{Session::get('odp_status')}}">{{Session::get('odp_status')}}</option>
                                  @endif
                                  <option value="">--Pilih Status--</option>
                                  <option value="Enable">Enable</option>
                                  <option value="Disable">Disable</option>
                                </select>
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto Lokasi</label>
                                <input  type="file" class="form-control-file" name="odp_lokasi_img">
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto Topologi</label>
                                <input  type="file" class="form-control-file" name="odp_file_topologi">
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
                      <th>Site</th>
                      <th>Pop</th>
                      <th>Router Dist</th>
                      <th>Olt</th>
                      <th>Odp Dist.</th>
                      <th>Action</th>
                      <th>Odp Id</th>
                      <th>Odp Nama</th>
                      <th>Odp Dist. Slot</th>
                      <th>Core</th>
                      <th>Jumlah Slot Spliter</th>
                      <th>Slot digunakan</th>
                      <th>Koordinat</th>
                      <th>Lokasi File</th>
                      <th>Topologi File</th>
                      <th>Keterangan</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($data_odp as $d)
                    <tr>
                      
                      
                      <td>{{$d->site_nama ?? ''}}</td>
                      <td>{{$d->pop_nama ?? ''}}</td>
                      <td>{{$d->router_nama ?? ''}}</td>
                      <td>{{$d->olt_nama ?? ''}}</td>
                      <td><a href="http://">{{$d->odc_nama ?? ''}}</a></td>
                      <td>
                        <div class="form-button-action">
                            <button type="button" data-toggle="modal" data-target="#modal-edit{{$d->id ?? '1'}}" class="btn btn-link btn-primary btn-lg">
                              <i class="fa fa-edit"></i>
                            </button>
                          </div>
                        <div class="form-button-action">
                          <a href="{{route('admin.topo.odp_instalasi',['id'=>$d->id_odp ?? '1'])}}">
                            <button type="button"class="btn btn-link btn-primary btn-lg">
                              <i class="fa fa-users"></i>
                            </button></a>
                          </div>
                        </td>
                        <td>{{$d->odp_nama ?? ''}}</td>
                        <td>{{$d->odp_id ?? ''}}</td>
                        <td>{{$d->odp_slot_odc ?? ''}}</td>
                        <td>{{$d->odp_core ?? ''}}</td>
                        <td>{{$d->odp_jumlah_slot ?? ''}}</td>
                        <td>{{$d->data_isntalasi->count() ?? ''}}</td>
                        <td><a href="https://www.google.com/maps/place/{{$d->odp_koordinat ?? ''}}">{{$d->odp_koordinat ?? ''}}</a></td>
                        <td>{{$d->odp_lokasi_img ?? ''}}</td>
                        <td>{{$d->odp_file_topologi ?? ''}}</td>
                        <td>{{$d->odp_keterangan ?? ''}}</td>
                        <td>{{$d->odp_status ?? ''}}</td>

                     
                        {{-- -------------------------------MODAL ODP------------------------------------ --}}
                      <div class="modal fade" id="modal-edit{{$d->id}}">
                          <div class="modal-dialog modal-lg">
                            <form action="{{route('admin.topo.update_odp',['id'=>$d->id])}}" method="POST" enctype="multipart/form-data">
                              @csrf  
                            @method('PUT')
                            <div class="modal-content">
                              <div class="modal-header bg-primary ">
                                <h4 class="modal-title">Edit ODP </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <div class="card-body">
                                  <div class="row">
                                    <div class="col-12 form-group">
                                      <label>ODP Distribusi</label>
                                      <select class="form-control" id="" name="data__odc_id"  >
                                        @if($d->data__odc_id)
                                        <option value="{{$d->data__odc_id}}">{{$d->odc_nama}}</option>
                                        @endif
                                        @foreach ($data_odc as $odc)
                                        <option value="{{$odc->id}}">{{$odc->odc_nama ?? ''}} -|- {{$odc->olt_nama ?? ''}} -|- {{$odc->router_nama ?? ''}} -|- {{$odc->pop_nama ?? ''}} -|- {{$odc->site_nama ?? ''}}</option>
                                        @endforeach
                                      </select>
                                  </div>
                              <div class="col-6 form-group">
                                  <label>Nama Odp</label>
                                  <input type="text" class="form-control" value="{{$d->odp_nama}}" name="odp_nama">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Odp Id</label>
                                  <input type="text" class="form-control" value="{{$d->odp_id}}" name="odp_id">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Slot ODP Distribusi </label>
                                    <select name="odp_slot_odc" id="" class="form-control">
                                      @if($d->odp_slot_odc)
                                      <option value="{{$d->odp_slot_odc}}">{{$d->odp_slot_odc}}</option>
                                      @endif
                                    <option value="">- Pilih Slot ODP Distribusi -</option>
                                      <option value="1">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                      <option value="4">4</option>
                                      <option value="5">5</option>
                                      <option value="6">6</option>
                                      <option value="7">7</option>
                                      <option value="8">8</option>
                                      <option value="9">9</option>
                                      <option value="10">10</option>
                                      <option value="11">11</option>
                                      <option value="12">12</option>
                                      <option value="13">13</option>
                                      <option value="14">14</option>
                                      <option value="15">15</option>
                                      <option value="16">16</option>
                                  </select>
                              </div>
                              <div class="col-6 form-group">
                                  <label>Core Kabel </label>
                                  <select name="odp_core" id="" class="form-control">
                                      @if($d->odp_core)
                                      <option value="{{$d->odp_core}}">{{$d->odp_core}}</option>
                                      @endif
                                    <option value="">- Pilih Core Kabel -</option>
                                      <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                  </select>
                              </div>
                              <div class="col-6 form-group">
                                  <label>Jumlah Slot Spliter Odp </label>
                                  <select name="odp_jumlah_slot" id="" class="form-control">
                                      @if($d->odp_jumlah_slot)
                                      <option value="{{$d->odp_jumlah_slot}}">{{$d->odp_jumlah_slot}}</option>
                                      @endif
                                    <option value="">- Pilih Core Kabel -</option>
                                      <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                  </select>
                              </div>
                              <div class="col-6 form-group">
                                  <label>Keterangan</label>
                                  <input type="text" class="form-control"  value="{{$d->odp_keterangan}}" name="odp_keterangan">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Koodinat</label>
                                  <input type="text" class="form-control"  value="{{$d->odp_koordinat}}" name="odp_koordinat">
                              </div>
                              <div class="col-12 form-group">
                                <label>Status</label>
                                <select name="odp_status" class="form-control">
                                  @if($d->odp_status)
                                  <option value="{{$d->odp_status}}">{{$d->odp_status}}</option>
                                  @endif
                                  <option value="">--Pilih Status--</option>
                                  <option value="Enable">Enable</option>
                                  <option value="Disable">Disable</option>
                                </select>
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto Lokasi</label>
                                <input  type="file" class="form-control-file" name="odp_lokasi_img">
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto Topologi</label>
                                <input  type="file" class="form-control-file" name="odp_file_topologi">
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
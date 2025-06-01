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
              <h3 class="card-title text-light">Data Odp Distibusi </h3>
            </div>
            <div class="card-body table-responsive -sm">
              <button class="btn btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#modal-adduser"><i class="fas fa-solid fa-plus"></i>Tambah Odp Distibusi</button>
              <button class="btn btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#import">
                <i class="fa fa-file-import"></i> Import
              </button>
                <a href="{{route('admin.topo.odp')}}"><button class="btn  btn-sm ml-auto m-1 btn-primary"><i class="fas fa-solid fa-route"></i> ODP</button></a>
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
                    Import Odc</span> 
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{route('admin.export.import_odc')}}" method="POST" enctype="multipart/form-data">
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
                    <form action="{{route('admin.topo.odc_store')}}" method="POST" enctype="multipart/form-data">
                      @csrf
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h4 class="modal-title">Tambah ODC </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 form-group">
                                      <label>Pilih OLT</label>
                                      <select class="form-control" id="" name="data__olt_id"  >
                                        <option value="">--Pilih Olt--</option>
                                        @foreach ($data_olt as $d)
                                        <option value="{{$d->id}}">{{$d->olt_nama}} -|- {{$d->router_nama}} -|- {{$d->pop_nama}} -|- {{$d->site_nama}} </option>
                                        @endforeach
                                      </select>
                                  </div>
                              <div class="col-6 form-group">
                                  <label>Odc Id</label>
                                  <input type="text" class="form-control"value="{{ Session::get('odc_id') }}" name="odc_id">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Odp Dist. Nama</label>
                                  <input type="text" class="form-control"value="{{ Session::get('odc_nama') }}" name="odc_nama">
                              </div>
                              <div class="col-6 form-group">
                                  <label>PON OLT</label>
                                      <select name="odc_pon_olt" id="" class="form-control">
                                      @if(Session::get('odc_pon_olt'))
                                      <option value="{{ Session::get('odc_pon_olt') }}">{{ Session::get('odc_pon_olt') }}</option>
                                      @endif
                                    <option value="">--Pilih Pon OLT--</option>
                                      <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                  </select>
                              </div>
                              <div class="col-6 form-group">
                                  <label>Core Kabel </label>
                                  <select name="odc_core" id="" class="form-control">
                                       @if(Session::get('odc_core'))
                                      <option value="{{ Session::get('odc_core') }}">{{ Session::get('odc_core') }}</option>
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
                                  <label>Jumlah Slot Spliter </label>
                                    <select name="odc_jumlah_port" id="" class="form-control">
                                      @if(Session::get('odc_jumlah_port'))
                                      <option value="{{ Session::get('odc_jumlah_port') }}">{{ Session::get('odc_jumlah_port') }}</option>
                                      @endif
                                    <option value="">--Pilih Julah Slot--</option>
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
                                  <input type="text" class="form-control" value="{{ Session::get('odc_keterangan') }}" name="odc_keterangan">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Koodinat</label>
                                  <input type="text" class="form-control" value="{{ Session::get('odc_koordinat') }}" name="odc_koordinat">
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto Lokasi</label>
                                <input  type="file" class="form-control-file" name="odc_lokasi_img">
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto Topologi</label>
                                <input  type="file" class="form-control-file" name="odc_file_topologi">
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
                <table id="edit_inputdata" class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Site</th>
                      <th>Pop</th>
                      <th>Router Distribusi</th>
                      <th>Olt</th>
                      <th>Aksi</th>
                      <th>Odp Dist. Nama</th>
                      {{-- <th>Odp Dist. Id</th> --}}
                      <th>Olt Pon</th>
                      <th>Core</th>
                      <th>Jumlah Slot Odc</th>
                      <th>Slot Terpakai</th>
                      <th>Koordinat</th>
                      <th>Lokasi File</th>
                      <th>Topologi File</th>
                      <th>Keterangan</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($data_odc as $d)
                      <tr>
                        <td>{{$d->site_nama}}</td>
                        <td>{{$d->pop_nama}}</td>
                        <td>{{$d->router_nama}}</td>
                        <td>{{$d->olt_nama}}</td>
                        <td>
                        <div class="form-button-action">
                          <button type="button" data-toggle="modal" data-target="#modal-edit{{$d->id}}" class="btn btn-link btn-primary btn-lg">
                            <i class="fa fa-edit"></i>
                              </button>
                            </div>
                          </td>
                                <td>{{$d->odc_nama}}</td>
                                {{-- <td>{{$d->odc_id}}</td> --}}
                                <td>{{$d->odc_pon_olt}}</td>
                                <td>Core {{$d->odc_core}}</td>
                                <td>{{$d->odc_jumlah_port}}</td>
                                <td>{{$d->odp_odc->count()}}</td>
                                <td>{{$d->odc_koordinat}}</td>
                                <td>{{$d->odc_lokasi_img}}</td>
                                <td>{{$d->odc_file_topologi}}</td>
                                <td>{{$d->odc_keterangan}}</td>
                                <td>{{$d->odc_status}}</td>
                      <div class="modal fade" id="modal-edit{{$d->id}}">
                          <div class="modal-dialog modal-lg">
                            <form action="{{route('admin.topo.update_odc',['id'=>$d->id])}}" method="POST" enctype="multipart/form-data">
                            @csrf  
                            @method('PUT')
                            <div class="modal-content">
                              <div class="modal-header bg-primary ">
                                <h4 class="modal-title">Edit Odc </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <div class="card-body">
                                  <div class="row">
                                    <div class="col-6 form-group">
                                      <label>Pilih OLT</label>
                                      <select class="form-control" id="" name="data__olt_id"  >
                                        @if($d->data__olt_id)
                                        <option value="{{$d->data__olt_id}}">{{$d->olt_nama}} -|- {{$d->router_nama}} -|- {{$d->pop_nama}} -|- {{$d->site_nama}} </option>
                                        @endif
                                        @foreach ($data_olt as $olt)
                                        <option value="{{$olt->id}}">{{$olt->olt_nama}} -|- {{$olt->router_nama}} -|- {{$olt->pop_nama}} -|- {{$olt->site_nama}} </option>
                                        @endforeach
                                      </select>
                                  </div>
                              {{-- <div class="col-6 form-group">
                                  <label>Odc Id</label>
                                  <input type="text" class="form-control"value="{{$d->odc_id}}" name="odc_id">
                              </div> --}}
                              <div class="col-6 form-group">
                                  <label>Odp Dist. Nama</label>
                                  <input type="text" class="form-control"value="{{$d->odc_nama}}" name="odc_nama">
                              </div>
                              <div class="col-6 form-group">
                                  <label>PON OLT</label>
                                      <select name="odc_pon_olt" id="" class="form-control">
                                      @if($d->odc_pon_olt)
                                      <option value="{{ $d->odc_pon_olt }}">{{ $d->odc_pon_olt }}</option>
                                      @endif
                                    <option value="">--Pilih Pon OLT--</option>
                                      <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                  </select>
                              </div>
                              <div class="col-6 form-group">
                                  <label>Core Kabel </label>
                                    <select name="odc_core" id="" class="form-control">
                                      @if($d->odc_core)
                                      <option value="{{ $d->odc_core }}">{{ $d->odc_core }}</option>
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
                                  <label>Jumlah Slot Spliter </label>
                                    <select name="odc_jumlah_port" id="" class="form-control">
                                      @if($d->odc_jumlah_port)
                                      <option value="{{ $d->odc_jumlah_port }}">{{ $d->odc_jumlah_port }}</option>
                                      @endif
                                    <option value="">--Pilih Julah Slot--</option>
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
                                  <input type="text" class="form-control" value="{{ $d->odc_keterangan }}" name="odc_keterangan">
                              </div>
                              <div class="col-6 form-group">
                                  <label>Koodinat</label>
                                  <input type="text" class="form-control" value="{{ $d->odc_koordinat }}" name="odc_koordinat">
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto Lokasi</label>
                                <input  type="file" class="form-control-file" name="odc_lokasi_img">
                              </div>
                              <div class="col-6 form-group">
                                <label>Upload Foto Topologi</label>
                                <input  type="file" class="form-control-file" name="odc_file_topologi">
                              </div>
                              
                              <div class="col-6 form-group">
                                    <label>Status</label>
                                    <select name="odc_status" class="form-control">
                                      @if($d->odc_status) 
                                      <option value="{{$d->odc_status}}">{{$d->odc_status}}</option>
                                      @endif
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
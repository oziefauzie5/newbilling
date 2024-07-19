@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-primary">
            <div class="d-flex align-items-center">
              <h4 class="card-title text-light">TIKET</h4>
            </div>
          </div>
          <div class="card-body">
            <button class="btn  btn-sm ml-auto m-1 btn-primary ">
              <i class="fa fa-plus"></i> HAPUS</button>
            <button class="btn  btn-sm ml-auto m-1 btn-primary ">
              <i class="fa fa-plus"></i> EXPORT</button>
            <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#addpaket">
              <i class="fa fa-plus"></i> BUAT TIKET</button>
              {{-- #MODAL BUAT TIKET --}}
            <div class="modal fade" id="addpaket" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header no-bd bg-primary">
                    <h5 class="modal-title">
                      <span class="fw-mediumbold">
                      Buat Tiket</span> 
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="{{route('admin.tiket.store')}}" method="POST">
                      @csrf
                      @method('POST')
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Pelanggan</label>
                            <input type="text" class="form-control" id="tiket_pelanggan" value="{{ Session::get('tiket_pelanggan') }}" data-toggle="modal" data-target="#cari_data" name="tiket_pelanggan">
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>No.Layanan</label>
                            <input type="text" class="form-control" id="tiket_nolayanan" name="tiket_nolayanan" value="{{ Session::get('tiket_nolayanan') }}">
                            <input type="hidden" class="form-control" id="tiket_idpel" name="tiket_idpel" value="{{ Session::get('tiket_idpel') }}">
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Departemen</label>
                            <select name="tiket_departemen" class="form-control" >
                              <option value="{{ Session::get('tiket_departemen') }}">{{ Session::get('tiket_departemen') }}</option>
                              <option value="">- PILIH -</option>
                              <option value="BILLING SUPPORT">BILLING SUPPORT</option>
                              <option value="SELES DEPARTEMENT">SELES DEPARTEMENT</option>
                              <option value="TECHNICAL SUPPORT">TECHNICAL SUPPORT</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Prioritas</label>
                            <select name="tiket_prioritas" class="form-control" >
                              <option value="{{ Session::get('tiket_prioritas') }}">{{ Session::get('tiket_prioritas') }}</option>
                              <option value="">- PILIH -</option>
                              <option value="LOW">LOW</option>
                              <option value="MEDIUM">MEDIUM</option>
                              <option value="HIGHT">HIGHT</option>
                              <option value="CRITICAL">CRITICAL</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Judul</label>
                            <input type="text" class="form-control" name="tiket_judul" value="{{ Session::get('tiket_judul') }}">
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label for="tiket_deskripsi">Deskripsi</label>
                            <textarea class="form-control" name="tiket_deskripsi" rows="5">{{ Session::get('tiket_deskripsi') }}</textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer no-bd">
                      <button type="button" class="btn" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <!-- end Modal buat tiket -->
<hr>


            

{{-- MODAL CARI DATA  --}}
  <div class="modal fade" id="cari_data" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="staticBackdropLabel">Cari Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="tiket_pilih_pelanggan" class="display table table-striped table-hover text-nowrap" >
            <thead>
              <tr>
                <th>Id</th>
                <th>Nama</th>
                <th>Whatsapp</th>
                <th>Alamat Pasang</th>
              </tr>
            </thead>
            <tbody>
              @foreach($input_data as $d)
              <tr id="{{$d->reg_idpel}}">
                <td>{{$d->id}}</td>
                <td>{{$d->input_nama}}</td>
                <td>{{$d->input_hp}}</td>
                <td>{{$d->input_alamat_pasang}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
        {{-- END MODAL CARI DATA  --}}


            
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
            
            <div class="table-responsive">
              <table id="input_data" class="display table table-striped table-hover text-nowrap" >
                <thead>
                  <tr>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>No.Tiket</th>
                    <th>Departemen</th>
                    <th>Pelanggan</th>
                    <th>Whatsapp</th>
                    <th>Judul</th>
                    <th>Prioritas</th>
                    <th>No.Layanan</th>
                    <th>Pembuat</th>
                    <th>Edit</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($tiket as $d)
                  <tr>
                    <td class="tiket" data-id="{{$d->tiket_id}}">{{$d->tiket_status}}</td>
                    <td class="tiket" data-id="{{$d->tiket_id}}">{{$d->created_at}}</td>
                    <td class="tiket" data-id="{{$d->tiket_id}}">{{$d->tiket_id}}</td>
                    <td class="tiket" data-id="{{$d->tiket_id}}">{{$d->tiket_departemen}}</td>
                    <td class="tiket" data-id="{{$d->tiket_id}}">{{$d->tiket_pelanggan}}</td>
                    <td class="tiket" data-id="{{$d->tiket_id}}">{{$d->tiket_whatsapp}}</td>
                    <td class="tiket" data-id="{{$d->tiket_id}}">{{$d->tiket_judul}}</td>
                    <td class="tiket" data-id="{{$d->tiket_id}}">{{$d->tiket_prioritas}}</td>
                    <td class="tiket" data-id="{{$d->tiket_id}}">{{$d->tiket_nolayanan}}</td>
                    <td class="tiket" data-id="{{$d->tiket_id}}">{{$d->name}}</td>
                    <td>
                      <div class="form-button-action">
                        <button type="button" data-toggle="modal" data-target="#modal_edit{{$d->id}}" class="btn btn-link btn-danger">
                          <i class="fa fa-times"></i>
                        </button>
                      </div>
                    </td>
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
</div>

@endsection
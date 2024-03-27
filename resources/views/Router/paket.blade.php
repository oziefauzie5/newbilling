@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-primary">
            <div class="d-flex align-items-center">
              <h4 class="card-title text-bold">PAKET INTERNET</h4>
            </div>
          </div>
          <div class="card-body">
            <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#addpaket">
              <i class="fa fa-plus"></i>
              Tambah Paket Internet
            <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#export">
              <i class="fa fa-file-export"></i> Export to mikrotik
            </button>

            <div class="modal fade" id="addpaket" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                  <div class="modal-header no-bd bg-primary">
                    <h5 class="modal-title">
                      <span class="fw-mediumbold">
                      Buat Paket</span> 
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="{{route('admin.router.paket.store')}}" method="POST">
                      @csrf
                      @method('POST')
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control" name="paket_nama" value="{{ Session::get('paket_nama') }}" required>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Limitasi</label>
                            <input type="text" class="form-control" value="{{ Session::get('paket_limitasi') }}" name="paket_limitasi" required>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Lokal Address</label>
                            <input type="text" class="form-control" value="{{ Session::get('paket_lokal') }}" name="paket_lokal" required>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Shared</label>
                            <input type="number" class="form-control" value="1" name="paket_shared" required>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Masa Aktif (Hari)</label>
                            <input type="number" class="form-control" value="30" name="paket_masa_aktif" required>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Harga</label>
                            <input type="number" class="form-control" value="{{ Session::get('paket_harga') }}" name="paket_harga" required>
                            <span>Harga diluar PPN</span>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Komisi</label>
                            <input type="number" class="form-control" value="{{ Session::get('paket_komisi') }}" name="paket_komisi" required>
                            <span>Komisi reseller yang akan dikeuarkan tiap pembayaran
                              (Isi 0 jika hitungan komisi dalam bentuk persentase)</span>
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
            <!-- Modal export mikrotik -->
            <div class="modal fade" id="export" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                  <div class="modal-header no-bd bg-primary">
                    <h5 class="modal-title">
                      <span class="fw-mediumbold">
                      Export Paket</span> 
                    </h5>
                    <div name="badge" id="badge" class="ml-auto"></div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="{{route('admin.router.paket.exportPaketToMikrotik')}}" method="POST">
                      @csrf
                      @method('POST')
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Router</label>
                            <select class="form-control" id="paket_router" name="paket_router">
                              <option value="">Pilih</option>
                              @foreach ($data_router as $rout)
                              <option value="{{$rout->id}}">{{$rout->router_nama}}</option>
                              @endforeach 
                            </select>  
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Lokal Address</label>
                            <input type="text" class="form-control"  name="paket_lokal" placeholder="192.168.100.1" required>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label class="  col-form-label">Remote Address</label>
                            <select name="pool" id="pool" class="form-control" placeholder="pool">
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer no-bd">
                      <button type="button" class="btn btn-sm" data-dismiss="modal">Close</button>
                      {{-- <input type="submit" id="Button" class="btn btn-primary" ></input>                               --}}
                      <input id="Button" type="submit" value=" Submit " class="btn btn-primary btn-sm"/>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <!-- end Modal export mikrotik -->
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
                    <th>#</th>
                    <th>Nama Paket</th>
                    <th>HPP</th>
                    <th>Komisi</th>
                    <th>Rate Limit</th>
                    <th>Shared</th>
                    <th>Aktif</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Nama Paket</th>
                    <th>HPP</th>
                    <th>Komisi</th>
                    <th>Rate Limit</th>
                    <th>Shared</th>
                    <th>Aktif</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </tfoot>
                <tbody>
                  @foreach ($data_paket as $d)
                  <tr>
                      <td>{{$d->paket_id}}</td>
                      <td data-toggle="modal" data-target="#modal_edit{{$d->paket_id}}">{{$d->paket_nama}}</td>
                      <td data-toggle="modal" data-target="#modal_edit{{$d->paket_id}}">Rp. {{ number_format( $d->paket_harga)}}</td>
                      <td data-toggle="modal" data-target="#modal_edit{{$d->paket_id}}">Rp. {{ number_format( $d->paket_komisi)}}</td>
                      <td data-toggle="modal" data-target="#modal_edit{{$d->paket_id}}">{{$d->paket_limitasi}}</td>
                      <td data-toggle="modal" data-target="#modal_edit{{$d->paket_id}}">{{$d->paket_shared}}</td>
                      <td data-toggle="modal" data-target="#modal_edit{{$d->paket_id}}">{{$d->paket_masa_aktif}}</td>
                      <td>{{$d->paket_status}}</td>
                      <td>
                        <div class="form-button-action">
                          <button type="button" data-toggle="modal" data-target="#modal_hapus{{$d->paket_id}}" class="btn btn-link btn-danger">
                            <i class="fa fa-times"></i>
                          </button>
                        </div>
                      </td>
                      </tr>
                        <!-- Modal Edit -->
                        <div class="modal fade" id="modal_edit{{$d->paket_id}}" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header no-bd">
                                <h5 class="modal-title">
                                  <span class="fw-mediumbold">
                                  Edit Data {{$d->input_nama}}</span> 
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <form action="{{route('admin.router.paket.update',['id'=>$d->paket_id])}}" method="POST">
                                  @csrf
                                  @method('POST')
                                  <div class="row">
                                    <div class="col-sm-12">
                                      <div class="form-group">
                                        <label>Nama Paket</label>
                                        <input type="text" class="form-control" name="paket_nama" value="{{ $d->paket_nama }}" required>
                                      </div>
                                    </div>
                                    <div class="col-sm-12">
                                      <div class="form-group">
                                        <label>Limitasi</label>
                                        <input type="text" class="form-control" value="{{ $d->paket_limitasi }}" name="paket_limitasi" required>
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <label>Shared</label>
                                        <input type="number" class="form-control" value="{{ $d->paket_shared }}" name="paket_shared" required>
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <label>Masa Aktif (Hari)</label>
                                        <input type="number" class="form-control" value="{{ $d->paket_masa_aktif }}" name="paket_masa_aktif" required>
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <label>Harga</label>
                                        <input type="number" class="form-control" value="{{ $d->paket_harga }}" name="paket_harga" required>
                                        <span>Harga diluar PPN</span>
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <label>Komisi</label>
                                        <input type="number" class="form-control" value="{{ $d->paket_komisi }}" name="paket_komisi" required>
                                        <span>Komisi reseller yang akan dikeuarkan tiap pembayaran
                                          (Isi 0 jika hitungan komisi dalam bentuk persentase)</span>
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
                        <!-- End Modal Edit -->
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
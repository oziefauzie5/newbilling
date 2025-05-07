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
              BUAT PROFILE PPP
            <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#addpaketvhc">
              <i class="fa fa-plus"></i>
              BUAT PROFILE HOTSPOT
              <form action="{{route('admin.router.paket.store_isolir')}}" method="POST">
              @csrf
              @method('POST')
              <button class="btn  btn-sm ml-auto m-1 btn-primary ">
                <i class="fa fa-plus"></i>
                BUAT PROFILE ISOLIR</button>
              </form>
            
            <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#export">
              <i class="fa fa-file-export"></i> Export to mikrotik
            </button>

            <div class="modal fade" id="addpaket" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                  <div class="modal-header no-bd bg-primary">
                    <h5 class="modal-title">
                      <span class="fw-mediumbold">
                      Buat Profile PPP</span> 
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
                            <label>Router</label>
                            <select type="text" class="form-control" name="router" value="{{ Session::get('router') }}" required>
                              <option value="">- PILIH ROUTER - </option>
                              @foreach($data_router as $s)
                              <option value="{{$s->id}}">{{$s->router_nama}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Nama Paket</label>
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
            {{-- END MODAL BUAT PAKET PPP --}}
            <div class="modal fade" id="addpaketvhc" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                  <div class="modal-header no-bd bg-primary">
                    <h5 class="modal-title">
                      <span class="fw-mediumbold">
                      Buat Profile Hotspot</span> 
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="{{route('admin.router.vhc.store')}}" method="POST">
                      @csrf
                      @method('POST')
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Router</label>
                            <select type="text" class="form-control" name="router" value="{{ Session::get('router') }}" required>
                              <option value="">- PILIH ROUTER - </option>
                              @foreach($data_router as $s)
                              <option value="{{$s->id}}">{{$s->router_nama}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Nama Profile</label>
                            <input type="text" class="form-control" name="paket_nama" value="{{ Session::get('paket_nama') }}" required>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Limitasi</label>
                            <input type="text" class="form-control" value="{{ Session::get('paket_limitasi') }}" name="paket_limitasi" required>
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
                            <label>Mode Masa Berlaku</label>
                            <select class="form-control" onchange="RequiredV();" id="expmode" name="paket_mode" required="1">
                              <option value=""> - Pilih Masa Berlaku - </option>
                              <option value="0">Tidak ada masa berlaku</option>
                              <option value="rem">Remove</option>
                              <option value="ntf">Notice</option>
                              <option value="remc">Remove & Record</option>
                              <option value="ntfc">Notice & Record</option>
                          </select>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Masa Aktif</label>
                            <input type="text" class="form-control" name="paket_masa_aktif" required>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Kode Warna</label>
                            <input type="text" class="form-control" name="paket_warna" required>
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
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label class="  col-form-label">Layanan</label>
                            <select name="layanan" class="form-control" required>
                              <option value="">PILIH LAYANAN</option>
                              <option value="PPP">PPP</option>
                              <option value="HOTSPOT">HOTSPOT</option>
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
                    @if($d->paket_nama == 'APPBILL_ISOLIR')
                    <td>{{$d->paket_id}}</td>
                    <td >{{$d->paket_nama}}</td>
                    <td >Rp. {{ number_format( $d->paket_harga)}}</td>
                    <td >Rp. {{ number_format( $d->paket_komisi)}}</td>
                    <td >{{$d->paket_limitasi}}</td>
                    <td >{{$d->paket_shared}}</td>
                    <td >{{$d->paket_masa_aktif}}</td>
                    <td>{{$d->paket_status}}</td>
                    <td>
                      <div class="form-button-action">
                        <button type="button" data-toggle="modal" data-target="#modal_hapus{{$d->paket_id}}" class="btn btn-link btn-danger">
                          <i class="fa fa-times"></i>
                        </button>
                      </div>
                    </td>
                    @else 
                    <td>{{$d->paket_id}}</td>
                    <td data-toggle="modal" data-target="#modal_edit{{$d->paket_id}}">{{$d->paket_nama}}</td>
                    <td data-toggle="modal" data-target="#modal_edit{{$d->paket_id}}">Rp. {{ number_format( $d->paket_harga)}}</td>
                    <td data-toggle="modal" data-target="#modal_edit{{$d->paket_id}}">Rp. {{ number_format( $d->paket_komisi)}}</td>
                    <td data-toggle="modal" data-target="#modal_edit{{$d->paket_id}}">{{$d->paket_limitasi}}</td>
                    <td data-toggle="modal" data-target="#modal_edit{{$d->paket_id}}">{{$d->paket_shared}}</td>
                    <td data-toggle="modal" data-target="#modal_edit{{$d->paket_id}}">{{$d->paket_masa_aktif}}</td>
                    <td data-toggle="modal" data-target="#modal_edit{{$d->paket_id}}">{{$d->paket_layanan}}</td>
                    <td>{{$d->paket_status}}</td>
                    <td>
                      <div class="form-button-action">
                        <button type="button" data-toggle="modal" data-target="#modal_hapus{{$d->paket_id}}" class="btn btn-link btn-danger">
                          <i class="fa fa-times"></i>
                        </button>
                      </div>
                    </td>
                    @endif
                  
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
                                        <label>Router</label>
                                        <select name="router" id="" class="form-control" required>
                                          <option value="">-- Pilih Router --</option>
                                          @foreach($data_router as $dr)
                                          <option value="{{$dr->id}}">{{$dr->router_nama}}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-sm-12">
                                      <div class="form-group">
                                        <label>Layanan</label>
                                        <input type="text" class="form-control readonly" name="paket_layanan" value="{{ $d->paket_layanan }}" required>
                                      </div>
                                    </div>
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
                                  @if($d->paket_layanan == 'VOUCHER')
                                  <div class="col-sm-6">
                                      <div class="form-group">
                                        <label>Mode Masa Berlaku</label>
                                        <select class="form-control" id="expmode" name="paket_mode" required="1">
                                          @if( $d->paket_mode == '0')
                                          <option value="{{ $d->paket_mode}}">Tidak ada masa berlaku</option>
                                          @elseif( $d->paket_mode == 'rem')
                                          <option value="{{ $d->paket_mode}}">Remove</option>
                                          @elseif( $d->paket_mode == 'ntf')
                                          <option value="{{ $d->paket_mode}}">Notice</option>
                                          @elseif( $d->paket_mode == 'remc')
                                          <option value="{{ $d->paket_mode}}">Remove & Record</option>
                                          @elseif( $d->paket_mode == 'ntfc')
                                          <option value="{{ $d->paket_mode}}">Notice & Record</option>
                                          @endif
                                          <option value="0">Tidak ada masa berlaku</option>
                                          <option value="rem">Remove</option>
                                          <option value="ntf">Notice</option>
                                          <option value="remc">Remove & Record</option>
                                          <option value="ntfc">Notice & Record</option>
                                      </select>
                                      </div>
                                    </div>
                                  @endif
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <label>Masa Aktif (Hari)</label>
                                        <input type="text" class="form-control" value="{{ $d->paket_masa_aktif }}" name="paket_masa_aktif" required>
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
@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0 jt" id="total_pencairan">0</div>
            <div class="text-muted mb-3">JUMLAH PENCAIRAN</div>
          </div>
        </div>
      </div>
      </div>
    <div class="row">
      <div class="card">
        <div class="card-body">
          @if ($errors->any())
          <div class="alert alert-danger">
            <div class="alert-title">
              <h4>Gagal!!</h4>
            </div>
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
           <!-- Button trigger modal -->
                      {{-- <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_pencairan">
                        Konfirmasi
                      </button><hr> --}}

                      <!-- Modal -->
                      <div class="modal fade" id="modal_pencairan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Pencairan</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <form method="post" action="{{route('admin.inv.konfirm_pencairan')}}">
                                  @csrf
                                  @method('POST')
                                  <div class="form-row">
                                    <div class="col">
                                      <select name="akun" id="" class="form-control akun">
                                        <option value="">PILIH METODE BAYAR</option>
                                      @foreach($data_bank as $bank)
                                      <option value="{{$bank->id}}">{{$bank->akun_nama}}</option>
                                      @endforeach
                                      </select>
                                    </div>
                                    <div class="col">
                                      <select name="penerima" id="" class="form-control penerima">
                                        <option value="">PILIH PENERIMA</option>
                                      @foreach($data_user as $user)
                                      <option value="{{$user->id}}">{{$user->name}}</option>
                                      @endforeach
                                      </select>
                                    </div>
                                  </div>
                                  <div id="notif1"></div>
                                  <div id="notif2"></div>
                                  <div id="notif3"></div>
                              
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                                  <button type="button" class="btn btn-sm btn-primary submit_pencairan">Konfirmasi</button>
                                </form>
                            </div>
                          </div>
                        </div>
                      </div>
          <div class="table-responsive">
            <table id="pencairan_list" class="display table table-striped table-hover text-nowrap">
              <thead>
                <tr>
                  <th>#</th>
                  <th>#</th>
                  <th>TGL PASANG</th>
                  <th>NO. LAYANAN</th>
                  <th>PELANGGAN</th>
                  <th>ROUTER</th>
                  <th>ALAMAT PASANG</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_registrasi as $d)
                <tr>
                  <td class="text-center">
                    <div class="form-button-action">
                      @if($d->reg_progres == '3')
                      <a href="{{route('admin.psb.bukti_kas_keluar',['id'=>$d->reg_idpel])}}" target="_blank">
                      <button type="button" class="btn btn-link btn-dark">
                        Print
                      </button></a>
                      @elseif($d->reg_progres == '4')
                      <input type="checkbox" class="cb_pencairan" name="idpel[]" value="{{$d->reg_idpel}}" data-price="{{$data_biaya->biaya_psb+$data_biaya->biaya_sales}}">
                      @elseif($d->reg_progres == '5')
                      <a>
                        <button type="button" class="btn btn-link btn-success">
                          Lunas
                        </button></a>
                      @else
                      <a>
                        <button type="button" class="btn btn-link btn-warning">
                          Proses
                        </button></a>
                    
                      @endif
                    </div>
                  </td>
                  <td></td>
                  <td>{{date('d-m-Y', strtotime($d->reg_tgl_pasang))}}</td>
                  <td>{{$d->reg_nolayanan}}</td>
                  <td>{{$d->input_nama}}</td>
                  <td>{{$d->router_nama}}</td>
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
</div>

@endsection
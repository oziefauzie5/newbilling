@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
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
          <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover text-nowrap">
              <thead>
                <tr>
                  <th>#</th>
                  <th>TGL REGIST</th>
                  <th>NO. LAYANAN</th>
                  <th>PELANGGAN</th>
                  <th>ROUTER</th>
                  <th>ALAMAT PASANG</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_registrasi as $d)
                <tr>
                  <td>
                    <div class="form-button-action">
                      @if($d->reg_progres == '3')
                      <a href="{{route('admin.psb.bukti_kas_keluar',['id'=>$d->reg_idpel])}}" target="_blank">
                      <button type="button" class="btn btn-link btn-dark">
                        Kas
                      </button></a>
                      @elseif($d->reg_progres == '4')
                      <!-- Button trigger modal -->
                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_pencairan">
                        Konfirmasi
                      </button>

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
                              <form ethod="post" action="{{route('admin.psb.konfirm_pencairan')}}">
                                  @csrf
                                  @method('POST')
                                  <div class="form-row">
                                    <input type="text" name="idpel" value="{{$d->reg_idpel}}">
                                    <div class="col">
                                      <label for="">METODE BAYAR<strong class="text-danger">*</strong></label>
                                      <select name="bank" id="" class="form-control">
                                        <option value="">PILIH</option>
                                      @foreach($data_bank as $bank)
                                      <option value="{{$bank->id}}">{{$bank->akun_nama}}</option>
                                      @endforeach
                                      </select>
                                    </div>
                                    <div class="col">
                                      <label for="">PENERIMA<strong class="text-danger">*</strong></label>
                                      <select name="penerima" id="" class="form-control">
                                        <option value="">PILIH</option>
                                      @foreach($data_user as $user)
                                      <option value="{{$user->id}}">{{$user->name}}</option>
                                      @endforeach
                                      </select>
                                    </div>
                                  </div>
                              
                              </form>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                              <button type="button" class="btn btn-sm btn-primary">Konfirmasi</button>
                            </div>
                          </div>
                        </div>
                      </div>
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
                  <td>{{$d->input_tgl}}</td>
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
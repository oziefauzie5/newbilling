@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0">Rp. {{number_format($pendapatan-$refund)}}</div>
            <div class="text-muted mb-3">PENDAPATAN</div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0">Rp. {{number_format($refund)}}</div>
            <div class="text-muted mb-3">REFUND</div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0">Rp. {{number_format($biaya_adm)}}</div>
            <div class="text-muted mb-3">ADM</div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0">{{$count_trx}}</div>
            <div class="text-muted mb-3">JUMLAH TRANSAKSI</div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0">{{$sum_tunai}}</div>
            <div class="text-muted mb-3">PENDAPATAN TUNAI</div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      
      <div class="card">
        <div class="card-body">
          <!-- Modal buat laporan-->
          <div class="modal fade" id="buat_laporan" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="staticBackdropLabel">BUAT LAPORAN HARIAN</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{ route('admin.inv.buat_laporan',['id'=>$admin_user])}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                          <label for="formGroupExampleInput">Laporan Id</label>
                          <input type="text" class="form-control" name="lap_id" value="{{rand(10000, 59999)}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Staf Admin</label>
                          <input type="text" class="form-control" name="user_admin" value="{{$admin_name}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Total Pendapatan</label>
                          <input type="text" class="form-control" name="total" value="{{$buat_laporan}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Total Pendapatan Tunai</label>
                          <input type="text" class="form-control" name="tunai" value="{{$sum_tunai}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Total Refund</label>
                          <input type="text" class="form-control" name="refund" value="{{$refund}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Total Pendapatan Adm</label>
                          <input type="text" class="form-control" name="adm" value="{{$biaya_adm}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Total Transaksi</label>
                          <input type="text" class="form-control" name="count_trx" value="{{$count_trx}}">
                        </div>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary btn-sm" >BAYAR</button>
                </div>
                </form>
              </div>
            </div>
          </div>
          {{-- end modal buat laporan --}}
          <!-- Modal SERAH TERIMA-->
          <div class="modal fade" id="serah_terima" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="staticBackdropLabel">BUAT LAPORAN HARIAN</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{ route('admin.inv.serah_terima',['id'=>$admin_user])}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                          <label for="formGroupExampleInput">Laporan Id</label>
                          <input type="text" class="form-control" name="lap_id" value="{{time()}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Staf Admin</label>
                          <input type="text" class="form-control" name="user_admin" value="{{$admin_name}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Serah Terima kepada</label>
                          <select name="user_admin2" class="custom-select" required>
                            <option value="">PILIH ADMIN</option>
                            @foreach ($users as $us)
                            <option value="{{$us->id}}">{{$us->name}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Total Pendapatan</label>
                          <input type="text" class="form-control" name="total" value="{{$buat_laporan}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Total Pendapatan Tunai</label>
                          <input type="text" class="form-control" name="tunai" value="{{$sum_tunai}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Total Refund</label>
                          <input type="text" class="form-control" name="refund" value="{{$refund}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Total Pendapatan Adm</label>
                          <input type="text" class="form-control" name="adm" value="{{$biaya_adm}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Total Transaksi</label>
                          <input type="text" class="form-control" name="count_trx" value="{{$count_trx}}">
                        </div>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary btn-sm" >BAYAR</button>
                </div>
                </form>
              </div>
            </div>
          </div>
          {{-- end modal SERAH TERIMA --}}
          <!-- Modal TopUp-->
          <div class="modal fade" id="topup" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="staticBackdropLabel">TOPUP</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{ route('admin.inv.topup')}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                          <label for="formGroupExampleInput">Biller</label>
                          <select name="user_admin" class="custom-select" required>
                            <option value="">PILIH ADMIN</option>
                            @foreach ($biller as $us)
                            <option value="{{$us->id}}">{{$us->name}}</option>
                            @endforeach
                            <option value="10">TRIPAY</option>
                          </select>
                        </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary btn-sm" >Submit</button>
                </div>
                </form>
              </div>
            </div>
          </div>
          {{-- end modal TopUp --}}
          <!-- Modal tambah transaksi-->
<div class="modal fade" id="addtransaksi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">TAMBAH PEMASUKAN</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.lap.store_add_transaksi')}}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label>Jenis Pemasukan</label>
              <select class="form-control" name="jenis" required>
                <option value="">Pilih Jenis Pemasukan</option>
                <option value="VOUCHER">VOUCHER</option>
                <option value="LAIN-LAIN">LAIN-LAIN</option>
              </select>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Uraian</label>
              <input type="text" class="form-control" value="" name="uraian" required>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Metode Pembayaran</label>
              <select class="form-control" name="metode">
                <option value="">Pilih Metode</option>
                @foreach ($setting_akun as $a)
                    <option value="{{$a->id}}">{{$a->akun_nama}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Jumlah</label>
            <input type="number" class="form-control" value="0" name="jumlah">
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Upload Bukti</label>
            <input type="file" name="file" class="form-control" required>
            </div>
          </div>
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
       
            <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#addtransaksi">
              <i class="fa fa-plus"></i>
              TAMBAH TRANSAKSI
            </button>
            <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#serah_terima">
              <i class="fa fa-plus"></i>
              SERAH TERIMA
            </button>
            <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#topup">
              <i class="fa fa-plus"></i>
              TOPUP
            </button>
            <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#buat_laporan">
              <i class="fa fa-plus"></i>
              BUAT LAPORAN
            </button>
            <a href="{{route('admin.inv.data_laporan')}}"><button class="btn btn-sm ml-auto m-1 btn-primary">DATA LAPORAN</button>
            </a>
          <hr>
          <form >
            <div class="row mb-1">
                <div class="col-sm-3">
                  <select name="adm" class="custom-select custom-select-sm">
                    @if($adm)
                    <option value="{{$adm}}" selected>{{$adm}}</option>
                    @endif
                    <option value="">ALL DATA</option>
                    @foreach ($admin as $d)
                    <option value="{{$d->name}}">{{$d->name}}</option>
                    @endforeach
                  </select>
              </div>
                <div class="col-sm-3">
                  <select name="ak" class="custom-select custom-select-sm">
                    @if($ak)
                    <option value="{{$ak}}" selected>{{$ak}}</option>
                    @endif
                    <option value="" selected>ALL AKUN</option>
                    @foreach ($akun as $d_akun)
                    <option value="{{$d_akun->akun_nama}}">{{$d_akun->akun_nama}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-sm-3">
                  <button type="submit" class="btn btn-block btn-primary btn-sm">Submit
                </div>
              </div>
          </form>
          <hr>
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
                  <th>ID</th>
                  <th>TANGGAL</th>
                  <th>ADMIN</th>
                  <th>INVOICE</th>
                  <th>KETERANGAN</th>
                  <th>CABAR</th>
                  <th>METODE BAYAR</th>
                  <th>KREDIT</th>
                  <th>DEBET</th>
                  <th>AKSI</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($laporan as $d)
                <tr>
                  <td>{{$loop->iteration}}</td>
                      <td>{{$d->lap_id}}</td>
                      <td>{{date('d-m-Y',strtotime($d->lap_tgl))}}</td>
                      <td>{{$d->name}}</td>
                      <td>{{$d->lap_inv}}</td>
                      <td>{{$d->lap_keterangan}}</td>
                      <td>{{$d->lap_cabar}}</td>
                      <td>{{$d->akun_nama}}</td>
                      <td>{{$d->lap_kredit}}</td>
                      <td>{{$d->lap_debet}}</td>
                      <td>
                        <div class="form-button-action">
                          <button type="button" data-toggle="modal" data-target="#modal_edit{{$d->lap_id}}" class="btn btn-link btn-primary btn-lg">
                            <i class="fa fa-edit"></i>
                          </button>
                          <button type="button" data-toggle="modal" data-target="#modal_hapus{{$d->lap_id}}" class="btn btn-link btn-danger">
                            <i class="fa fa-times"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                      <!-- Modal Hapus -->
                      <div class="modal fade" id="modal_hapus{{$d->lap_id}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header no-bd">
                              <h5 class="modal-title">
                                <span class="fw-mediumbold">
                                Hapus Data {{$d->name}}</span> 
                              </h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                              <div class="modal-body">
                              <p>Apakah anda yakin, akan menghapus data {{$d->lap_keterangan}} ??</p>
                              </div>
                              <div class="modal-footer no-bd">
                                <form action="{{route('admin.inv.lap_delete',['id'=>$d->lap_id])}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-success">Hapus</button>
                              </form>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- End Modal Hapus -->
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
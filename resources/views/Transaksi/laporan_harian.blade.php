@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
    @foreach($sum_role as $sr)
      	<div class="col-sm-6 col-lg-4">
							<div class="card p-3">
								<div class="d-flex align-items-center">
									<span class="stamp stamp-md bg-secondary mr-3">
										<i class="fa fa-dollar-sign"></i>
									</span>
									<div>
										<h5 class="mb-1"><b><a href="#">{{$sr->count_trx}} <small>Transaksi</small></a></b></h5>
										<small class="text-muted">{{$sr->name}}  Rp. {{number_format($sr->sum_jumlah)}}</small>
									</div>
								</div>
							</div>
						</div>
      @endforeach
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
                          <input type="text" class="form-control" name="lap_id" value="{{rand(10000, 59999)}}" readonly>
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Staf Admin</label>
                          <input type="text" class="form-control" name="user_admin" value="{{$admin_name}}" readonly>
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Total Pendapatan</label>
                          <input type="text" class="form-control" name="total" value="{{$serah_terima->sum_jumlah ?? '0'}}" readonly>
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Total Transaksi</label>
                          <input type="text" class="form-control" name="count_trx" value="{{$serah_terima->count_trx ?? '0'}}" readonly>
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
                          <input type="text" class="form-control" name="total" value="{{$serah_terima->sum_jumlah ?? '0'}}">
                        </div>
                        <div class="form-group">
                          <label for="formGroupExampleInput">Total Transaksi</label>
                          <input type="text" class="form-control" name="count_trx" value="{{$serah_terima->count_trx ?? '0'}}">
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
                  <th>Id</th>
                  <th>Tanggal</th>
                  <th>Admin</th>
                  <th>Keterangan</th>
                  <th>Metode Bayar</th>
                  <th>Jumlah</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($laporan as $d)
                <tr>
                  <td>{{$loop->iteration}}</td>
                      <td>{{$d->lap_id}}</td>
                      <td>{{date('d-m-Y',strtotime($d->lap_tgl))}}</td>
                      <td>{{$d->name}}</td>
                      <td>{{$d->lap_keterangan}}</td>
                      <td>{{$d->akun_nama}}</td>
                      <td>{{number_format($d->lap_jumlah)}}</td>
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
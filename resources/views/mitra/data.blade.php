@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="card">
        <div class="card-body">
            <div class="page-inner">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="row invoice-info">
                                <div class="col invoice-col">
                                  <h3>{{ $datauser->nama }}</h3>
                                  <address>
                                      <strong>{{ $datauser->name }}</strong><br>
                                      <strong>{{ $datauser->hp }}</strong><br>
                                      <strong>{{ $datauser->alamat_lengkap }}</strong><br>
                                    </address>
                                    <h1>Rp {{ number_format($saldo) }}.-</h1>
                                    <address>
                                        - Saldo saat ini<br>
                                      </address>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                              <h3 class="card-title">DATA MUTASI SALDO</h3>
                            </div>
                              <div class="card-body">
                                <div class="row ">
                                    <div class="col-6">
                                        <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#modal-AddSaldo">TAMBAH SALDO</button>
                                        <div class="modal fade" id="modal-AddSaldo">
                                            <div class="modal-dialog modal-lg">
                                              <div class="modal-content">
                                                <div class="modal-header">
                                                  <h4 class="modal-title">TAMBAH SALDO</h4>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                  </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.mitra.topup',['id'=> $datauser->id]) }}" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        <h5 class="text-danger">CATATAN</h5>
                                                            <ul>
                                                                <li>Proses ini akan menambah saldo mitra bersangkutan secara manual tanpa melalui proses dan request topup saldo</li>
                                                                <li >Proses ini bisa gunakan untuk penarikan hutang (saldo yang minus dari mitra bersangkutan), isi sejumlah saldo yang minus atau yang dibayar oleh mitra sampai akhirnya saldo kembali menjadi 0</li>
                                                            </ul>
                                                    <hr>
                                                    <div class="m-4">
                                                    <div class="form-group">
                                                        <label for="name">NOMINAL</label>
                                                        <input type="text" class="form-control" style="height:60px;" id="nominal" name="nominal" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="name">CARA BAYAR</label>
                                                        <select class="form-control" name="cabar" id="cabar" required>
                                                            <option value="">PILIH</option>
                                                            @foreach ($akun as $d )
                                                            @if($d->id>=2)
                                                                <option value="{{ $d->akun_id }}">{{ $d->akun_nama }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="name">UPLOAD FILE</label>
                                                        <input type="file" name="file" class="form-control" required>
                                                    </div>
                                                </div>
        
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                  <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                  <button type="submit" class="btn btn-dark">TAMBAH SALDO </button>
                                                </div>
                                            </form>
                                              </div>
                                            </div>
                                          </div>
                                          <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#modal-DebetSaldo">DEBET SALDO</button>
                                          <div class="modal fade" id="modal-DebetSaldo">
                                              <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h4 class="modal-title">DEBET SALDO</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                      <form action="{{ route('admin.mitra.debet_saldo',['id'=> $datauser->id]) }}" method="post" enctype="multipart/form-data">
                                                          @csrf
                                                          <h6>Debet saldo adalah proses pengurangan saldo mitra bersangkutan</h6>
                                                            <ul>
                                                                <li class="text-danger"><h5><strong>PENARIKAN</strong></h5></li>
                                                                <ul>
                                                                    <li>Dana diserahkan kepada Mitra dalam bentuk uang dan bisa dilakukan apabila saldo plus (Saldo akan berkurang sebanyak jumlah yang ditarik)</li>
                                                                </ul>
                                                                <li  class="text-danger"><h5><strong>PENGEMBALIAN</strong></h5></li>
                                                                <ul>
                                                                    <li>Pengembalian dana (Refund), contohnya ketika anda salah/kelebihan dalam menambahkan saldo dan ini berfungsi untuk mengurangi kembali saldo mitra</li>
                                                                </ul>
                                                            </ul>
        
                                                      <hr>
                                                      <div class="m-4">
                                                        <div class="form-group">
                                                            <label for="name">KATEGORI</label>
                                                            <select class="form-control" name="kategori" id="kategori" required>
                                                                <option value="">PILIH</option>
                                                                <option value="PENARIKAN">PENARIKAN</option>
                                                                <option value="PENGEMBALIAN">PENGEMBALIAN</option>
                                                            </select>
                                                        </div>
                                                    <div class="form-group">
                                                        <label for="name">NOMINAL DEBET</label>
                                                        <input type="text" class="form-control" style="height:60px;" id="nominal_debet" name="nominal_debet" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="name">CARA BAYAR</label>
                                                        <select class="form-control" name="cabar" id="cabar" required>
                                                            <option value="PILIH"></option>
                                                            @foreach ($akun as $d )
                                                            @if($d->id>=2)
                                                            <option value="{{ $d->akun_id }}">{{ $d->akun_nama }}</option>
                                                            @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                      <label for="name">UPLOAD FILE</label>
                                                      <input type="file" name="file" class="form-control" required>
                                                  </div>
                                                    </div>
        
                                                  </div>
                                                  <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-dark"> DEBET SALDO </button>
                                                  </div>
                                              </form>
                                                </div>
                                              </div>
                                            </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-6 col-form-label">TANGGAL</label>
                                            <div class="col-sm-6">
                                            <input type="text" class="form-control pickupDate" id="tgl_gabung"  name="tgl_gabung" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table id="input_data" class="display table table-striped table-hover text-nowrap" >
                                <thead>
                          <tr>
                            <th>NO</th>
                            <th>TANGGAL</th>
                            <th>KATEGORI</th>
                            <th>KETERANGAN</th> 
                            <th>KREDIT</th>
                            <th>DEBET</th>
                            <th>SALDO</th>
                          </tr>
                          </thead>
                          <tbody>
                            @foreach ($mutasi as $d)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                {{-- <td>{{ $d->id }}</td> --}}
                                <td>{{ date('d-m-Y H:m:s', strtotime($d->tgl_trx ))}}</td>
                                <td>{{ $d->mt_kategori }}</td>
                                <td>{{ $d->mt_deskripsi }}</td>
                                <td>{{ number_format($d->mt_kredit) }}</td>
                                <td>{{ number_format($d->mt_debet) }}</td>
                                <td>{{ number_format($d->mt_saldo) }}</td>
                            </tr>
                             @endforeach
                          </tbody>
                          <tfoot>
                        </table>
                        </div>
                              </div>
                          </div>
                    </div>
                </div>
            </div>

        </div>
      </div>
    </div>
  </div>
</div>

@endsection
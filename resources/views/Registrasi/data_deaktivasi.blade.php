@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    @role('admin|STAF ADMIN')
          <div class="row">
            <div class="col-6 col-sm-4 col-lg-3">
              <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">{{$total_deaktivasi}}</div>
                    <div class="text-muted mb-3">Total Deaktivasi</div>
                  </div>
                </div>
              </div>
              <div class="col-6 col-sm-4 col-lg-3">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">{{$deaktivasi_month}}</div>
                    <div class="text-muted mb-3">Deaktivasi Bulan ini</div>
                  </div>
                </div>
              </div>
              <div class="col-6 col-sm-4 col-lg-3">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">{{$pengambilan_perangkat}}</div>
                    <div class="text-muted mb-3">Pengambilan perangkat</div>
                  </div>
                </div>
              </div>
              <div class="col-6 col-sm-4 col-lg-3">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">{{$verifikasi_deaktivasi}}</div>
                    <div class="text-muted mb-3">Perangakat belum terambil</div>
                  </div>
                </div>
              </div>
            </div>
    @endrole
    
    <div class="row">
      <div class="card">
        <div class="card-body">
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
        <a href="{{route('admin.psb.index')}}">
          <button class="btn  btn-sm ml-auto m-1 btn-info">
            <i class="fa fa-plus"></i>
            KEMBALI
          </button>
        </a>
        <hr>
          <div class="table-responsive">
            <table id="datatable" class="display table table-striped table-hover text-nowrap" >
              <thead>
                <tr>
                  <th>Aksi</th>
                  <th>NO LAYANAN</th>
                  <th>PELANGGAN</th>
                  <th>TGL JT TEMPO</th>
                  <th>TGL PASANG</th>
                  <th>TGL Registrasi</th>
                  <th>PROFILE</th>
                  <th>ROUTER</th>
                  <th>USERNAME</th>
                  <th>ALAMAT PASANG</th>
                  <th>NOTE</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_registrasi as $d)
                <tr>
                  @if($d->reg_progres == 89)
                  <td><button  class="btn  btn-sm ml-auto m-1 btn-success" data-toggle="modal" data-target="#exampleModal">Verifikasi</button></td>
                  @elseif($d->reg_progres == 99)
                  <td><button  class="btn  btn-sm ml-auto m-1 btn-success" data-toggle="modal" data-target="#exampleModal">Verifikasi</button></td>
                  @else
                  <td><button  class="btn  btn-sm ml-auto m-1 btn-warning">Terverifikasi</button></td>
                  @endif
                  <td>{{$d->reg_nolayanan}}</td>
                      <td >{{$d->input_nama}}</td>
                      @if($d->reg_tgl_jatuh_tempo)
                      @if($d->reg_status != 'PAID')
                      <td  class="text-danger font-weight-bold"  >{{date('d-m-Y',strtotime($d->reg_tgl_jatuh_tempo))}}</td>
                      @else
                      <td  class="font-weight-bold"  >{{date('d-m-Y',strtotime($d->reg_tgl_jatuh_tempo))}}---</td>
                      @endif
                      @else
                      <td   class="text-danger font-weight-bold" >Belum Terpasang</td>
                      @endif
                      <td  >{{$d->reg_tgl_pasang}}</td>
                      <td  >{{date('d-m-Y',strtotime($d->input_tgl))}}</td>
                      <td  >{{$d->paket_nama}}</td>
                      <td  >{{$d->router_nama}}</td>
                      <td  >{{$d->reg_username}}</td>
                      <td  >{{$d->input_alamat_pasang}}</td>
                      <td>{{$d->reg_catatan}}</td>
                    </tr>
                    @endforeach
              </tbody>
            </table>
            <!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button> -->

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="">
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kelengkapan Barang</label>
                        <div class="col-sm-4">
                          <select type="text" class="form-control" name="deaktivasi_kelengkapan_perangkat" required>
                            <option value="">- Pilih -</option>
                            <option value="ONT & Adaptor">ONT & Adaptor</option>  
                            <option value="ONT">ONT</option>  
                            <option value="Hilang">hilang</option>  
                          </select>
                        </div>
                        <label class="col-sm-2 col-form-label">Pembuat Laporan</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" value="{{$user_nama}}"  name="" >
                        </div>
                      </div>
                      <div class="form-group row">
                      <label class="col-sm-2 col-form-label">Pernyataan</label>
                      <div class="col-sm-10 pernyataan_1" style="display:none">
                          <textarea type="text" class="form-control readonly" id="deaktivasi_pernyataan1"  name="deaktivasi_pernyataan" >Dengan ini saya {{$user_nama}} menyatakan benar, bahwa adaptor hilang. Saya siap bertanggung jawab dikemudian hari.</textarea>
                        </div>
                        <div class="col-sm-10 pernyataan_2" style="display:none">
                        <textarea type="text" class="form-control readonly" id="deaktivasi_pernyataan2"  name="deaktivasi_pernyataan" >Dengan ini saya {{$user_nama}} menyatakan benar, bahwa ONT & adaptor hilang. Saya siap bertanggung jawab dikemudian hari.</textarea>
                        </div>
                      </div>
                    <div class="form-group row div_ont" style="display:none">
                    <label class="col-sm-2 col-form-label">Mac Address</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control deaktivasi_mac" value="" id="mac" name="deaktivasi_mac" >
                      </div>
                      <label  class="col-sm-2 col-form-label">Serial Number</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" value="" id="deaktivasi_sn" name="deaktivasi_sn" >
                      </div>
                    </div>
                    <div class="form-group row div_ont" style="display:none">
                    <label class="col-sm-2 col-form-label">Pengambil Barang</label>
                      <div class="col-sm-4">
                        <select type="text" class="form-control" value="" id="deaktivasi_pengambil_perangkat" name="deaktivasi_pengambil_perangkat" >
                          <option value="">- Pilih pengambil perangkat -</option>
                          @foreach($user as $u)
                          <option value="{{$u->id}}">{{$u->name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label  class="col-sm-2 col-form-label">Alasan Deaktivasi</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="" id="deaktivasi_alasan_deaktivasi" name="deaktivasi_alasan_deaktivasi" >
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                  </div>
                </form>
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
@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner">
          <div class="row">
              <a href="#" class="col-6 col-sm-4 col-lg-2">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">0</div>
                    <div class="text-muted mb-3">Jumlah Titik</div>
                  </div>
                </div>
              </a>
              <a href="#" class="col-6 col-sm-4 col-lg-2">
                <div class="card">
                  <div class="card-body p-3 text-center">
                    <div class="h1 m-0">0</div>
                    <div class="text-muted mb-3">Registrasi</div>
                  </div>
                </div>
              </a>
            </div>
      </div>
      <div class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </div>
    </div>
    
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

          <a href="{{route('admin.vhc.regist_titik')}}">
          <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#addRowModal">
            <i class="fa fa-plus"></i>
            BERITA ACARA
          </button>
        </a>
          <a href="{{route('admin.psb.berita_acara')}}">
          <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#addRowModal">
            <i class="fa fa-plus"></i>
            BERITA ACARA
          </button>
        </a>
        <hr>
        <form >
        <div class="row mb-1">
          <div class="col-sm-2">
              <select name="data" class="custom-select custom-select-sm">

                <option value="">ALL DATA</option>
                <option value="BELUM TERPASANG">BELUM TERPASANG</option>
                <option value="PPP">USER PPP</option>
                <option value="DHCP">USER DHCP</option>
                <option value="HOTSPOT">USER HOTSPOT</option>
                <option value="USER BARU">USER BARU</option>
                <option value="USER BULAN LALU">USER BULAN LALU</option>
              </select>
          </div>
          <div class="col-sm-2">
            <input name="q" type="text" class="form-control form-control-sm" placeholder="Cari">
          </div>
          <div class="col-sm-2">
            <button type="submit" class="btn btn-block btn-dark btn-sm">Submit
          </div>
        </div>
        </form>
       
          <div class="table-responsive">
            <table id="" class="display table table-striped table-hover text-nowrap" >
              <thead>
                <tr>
                  <th class="text-center"></th>
                  <th>NO LAYANAN</th>
                  <th>NAMA</th>
                  <th>MAPS</th>
                  <th>IP ADDRESS</th>
                  <th>USERNAME REMOTE</th>
                  <th>PASSWORD REMOTE</th>
                  <th>PENANGGUNG JAWAB</th>
                  <th>NO.HP PEN. JAWAB</th>
                  <th>CATATAN</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_titik as $d)
                <tr>
                  <td>
                    <div class="form-button-action">
                      <button type="button" data-toggle="modal" data-target="#modal_hapus{{$d->titik_id}}" class="btn btn-link btn-danger">
                        <i class="fa fa-times"></i>
                      </button>
                    </div>
                  </td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{date('d-m-Y',strtotime($d->tgl_pasang))}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->titik_nama}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->titik_alamat}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->titik_maps}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->titik_ip}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->titik_username}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->titik_password}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->titik_pen_jawab_hp}}</td>
                      <td class="href" data-id="{{$d->reg_idpel}}" >{{$d->name}}</td>
                      <td>{{$d->reg_catatan}}</td>
                    </tr>
                      <!-- Modal Hapus -->
                      <div class="modal fade" id="modal_hapus{{$d->reg_idpel}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header no-bd">
                              <h5 class="modal-title">
                                <span class="fw-mediumbold">
                                Hapus Data</span> 
                              </h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                              <div class="modal-body">
                              <p>Apakah anda yakin, akan menghapus data {{$d->input_nama}} ??</p>
                              </div>
                              <div class="modal-footer no-bd">
                                <form action="{{route('admin.reg.delete_registrasi',['id'=>$d->reg_idpel])}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-success">Ya.!! Abdi yakin pisan</button>
                              </form>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal. Abdi Kurang yakin</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- End Modal Hapus -->
                    @endforeach
              </tbody>
            </table>
            <div class="pull-left">
              Showing
              {{$data_titik->firstItem()}}
              to
              {{$data_titik->lastItem()}}
              of
              {{$data_titik->total()}}
              entries
            </div>
            <div class="pull-right">
              {{ $data_titik->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
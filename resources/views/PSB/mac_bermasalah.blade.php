@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
   
    <div class="row">
      <div class="card">
        <div class="card-body">
        <form >
        <div class="row mb-1">
         
        
          <div class="col-sm-2">
            <input name="q" type="text" class="form-control form-control-sm" placeholder="Cari">
          </div>
          <div class="col-sm-2">
            <button type="submit" class="btn btn-block btn-dark btn-sm">Submit
          </div>
        </div>
        </form>
        <hr>
         <!-- Modal Import -->
         <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header no-bd">
                <h5 class="modal-title">
                  <span class="fw-mediumbold">
                  Input Data Baru</span> 
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('admin.reg.registrasi_import')}}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('POST')
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label>Pilih file (EXCEL,CSV)</label>
                        <input id="import" type="file" class="form-control" name="file" placeholder="Nama Lengkap" required>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer no-bd">
                  <button type="submit" class="btn btn-success">Add</button>
                </form>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
          <div class="table-responsive">
            <table id="" class="display table table-striped table-hover text-nowrap" >
              <thead>
                <tr>
                  <th>NO LAYANAN</th>
                  <th>PELANGGAN</th>
                  <th>TGL JT TEMPO</th>
                  <th>PROFILE</th>
                  <th>ROUTER</th>
                  <th>KTG</th>
                  <th>JENIS TAGIHAN</th>
                  <th>ALAMAT PASANG</th>
                  <th>NOTE</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_registrasi as $d)
                <tr>
                  </td>
                  @if($d->reg_progres == 'MIGRASI')
                  <td class="text-info">{{$d->reg_nolayanan}}</td>
                  @else
                  <td>{{$d->reg_nolayanan}}</td>
                  @endif
                      <td>{{$d->input_nama}}</td>
                      @if($d->reg_tgl_jatuh_tempo)
                      @if($d->reg_status != 'PAID')
                      <td class=" font-weight-bold" data-toggle="modal" data-target="#mac{{$d->reg_idpel}}" >{{date('d-m-Y',strtotime($d->reg_tgl_jatuh_tempo))}}---</td>
                      @else
                      <td class=" text-danger font-weight-bold" data-toggle="modal" data-target="#mac{{$d->reg_idpel}}" >{{date('d-m-Y',strtotime($d->reg_tgl_jatuh_tempo))}}</td>
                      @endif
                      @else
                      <td class="text-danger font-weight-bold" >Belum Terpasang</td>
                      @endif
                      <td data-toggle="modal" data-target="#mac{{$d->reg_idpel}}">{{$d->paket_nama}}</td>
                      <td data-toggle="modal" data-target="#mac{{$d->reg_idpel}}">{{$d->router_nama}}</td>
                      <td data-toggle="modal" data-target="#mac{{$d->reg_idpel}}">{{$d->reg_layanan}}</td>
                      <td data-toggle="modal" data-target="#mac{{$d->reg_idpel}}">{{$d->reg_jenis_tagihan}}</td>
                      <td data-toggle="modal" data-target="#mac{{$d->reg_idpel}}">{{$d->input_alamat_pasang}}</td>
                      <td data-toggle="modal" data-target="#mac{{$d->reg_idpel}}">{{$d->reg_catatan}}</td>
                    </tr>
                    <!-- Modal -->
<div class="modal fade" id="mac{{$d->reg_idpel}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">EDIT MAC ADDRESS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal"action="{{route('admin.psb.update_mac',['idpel'=>$d->reg_idpel])}}" method="POST">
          @csrf
          @method('PUT')
        <div class="form-group row">
          <label class=" col-sm-2 col-form-label" >Mrek Perangkat</label>
        <div class="col-sm-10">
          <input type="text" name="reg_mrek" class="form-control edit_ont" value="{{ $d->reg_mrek}}"  >
        </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Mac perangkat</label>
          <div class="col-sm-4">
            <input type="text" name="reg_mac"  class="form-control edit_ont" minlength="17" maxlength="17"  value="{{$d->reg_mac}}" >
          </div>
          <label class=" col-sm-2 col-form-label" >SN perangkat</label>
        <div class="col-sm-4">
          <input type="text" name="reg_sn" class="form-control edit_ont" value="{{ $d->reg_sn}}"  >
        </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </form>
      </div>
    </div>
  </div>
</div>
                    @endforeach

              </tbody>
            </table>
          </div>
        </div>
        {{ $data_registrasi->links('pagination::bootstrap-4') }}
      </div>
    </div>
  </div>
</div>

@endsection
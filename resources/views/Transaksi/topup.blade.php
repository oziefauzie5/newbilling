@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0 jt" id="customProductPricing">0</div>
            <div class="text-muted mb-3">TRANSAKSI</div>
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
<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#topup">
Topup
</button>
<hr>
<!-- Modal -->
<div class="modal fade" id="topup" tabindex="-1" aria-labelledby="topupLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Topop</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{-- <input type="text" value="{{$admin}}" class="id_lap"> --}}
         <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label>Id Laporan</label>
                          <input id="" type="text" class="form-control id_lap" name="name" value="{{$admin}}">
                        </div>
                      </div>
                      <div class="col">
                        <div class="form-group">
                          <label>Metode Bayar</label>
                          <select class="form-control" id="metode_bayar">
                            @foreach ($data_akun as $akun)
                            <option value="{{$akun->id}}">{{$akun->akun_nama}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary topup">Proses</button>
      </div>
    </div>
  </div>
</div>

          {{-- <button class="btn btn-block btn-info btn-sm topup">Top Up</button> --}}
          
          <div class="table-responsive"  >
            <table id="topup_list" class="display table table-striped table-hover" >
              <thead>
                <tr>
                  <th class="text-center"><input type="checkbox" id="selectAllCheckbox" class="checkboxtopup" disabled ></th>
                  <th>ID</th>
                  <th>TANGGAL</th>
                  <th>ADMIN</th>
                  <th>INVOICE</th>
                  <th>KETERANGAN</th>
                  <th>METODE BAYAR</th>
                  <th>KREDIT</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($laporan as $d)
                {{-- <form action="{{route('admin.inv.lap_topup',['id'=>$d->lap_admin])}}" method="post">
                  @csrf
                  @method('POST') --}}
                <tr>
                  <td class="text-center"><input type="checkbox" class="checkboxtopup" name="id[]" value="{{$d->lap_id}}" data-price="{{$d->lap_pokok + $d->lap_ppn}}"></td>
                  <td>{{$d->lap_id}}</td>
                  <td>{{date('d-m-Y',strtotime($d->lap_tgl))}}</td>
                  <td>{{$d->name}}</td>
                  <td>{{$d->lap_inv}}</td>
                  <td>{{$d->lap_keterangan}}</td>
                  <td>{{$d->akun_nama}}</td>
                  <td >Rp. {{number_format($d->lap_pokok + $d->lap_ppn)}}</td>
                </tr>
                
                @endforeach
              </tbody>
            </table>
            
            {{-- </form> --}}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
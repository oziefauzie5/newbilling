@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
  <div class="row">
     
     <div class="col-6 col-sm-3 col-lg-3">
       <div class="card">
         <div class="card-body p-3 text-center">
           <div class="h1 m-0 font-weight-bold text-info">Rp. {{number_format($ALL_UNPAID)}}</div>
           <div class="text-muted text-info font-weight-bold">UNPAID</div>
           <h6 class="text-muted mb-3 font-weight-bold">ALL PERIODE</h6>
         </div>
       </div>
     </div>
     <div class="col-6 col-sm-3 col-lg-3">
       <div class="card">
         <div class="card-body p-3 text-center">
           <div class="h1 m-0 font-weight-bold text-warning" >Rp. {{number_format($ALL_PAID)}}</div>
           <div class="text-muted text-warning font-weight-bold">PAID</div>
           <h6 class="text-muted mb-3 font-weight-bold">ALL PERIODE</h6>
         </div>
       </div>
     </div>
     <div class="col-6 col-sm-3 col-lg-3">
       <div class="card">
         <div class="card-body p-3 text-center">
           <div class="h1 m-0 font-weight-bold text-danger" >Rp. {{number_format($UNPAID)}}</div>
           <div class="text-muted text-danger font-weight-bold">UNPAID</div>
           <h6 class="text-muted mb-3 font-weight-bold">PERIODE {{$periode}}</h6>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-3 col-lg-3">
       <div class="card">
         <div class="card-body p-3 text-center">
           <div class="h1 m-0 font-weight-bold text-danger">Rp. {{number_format($PAID)}}</div>
           <div class="text-muted font-weight-bold text-danger">PAID</div>
           <h6 class="text-muted mb-3 font-weight-bold">PERIODE {{$periode}}</h6>
         </div>
       </div>
     </div>
     </div>
    <div class="col-md-12">
      <div class="card">
          <div class="card-body">
          
              <div class="row">
            <div class="col-3">
              <a href="{{route('admin.vhc.data_voucher')}}"><button class="btn btn-primary btn-sm mb-3 btn-block" type="button" >Kembali</button></a>
            </div>
            <div class="col-3">
                <a href="{{route('admin.vhc.data_outlet')}}"><button class="btn btn-primary btn-sm mb-3 btn-block" type="button" >Data Outlet</button></a>
            </div>
            <div class="col-3">
                <a href="{{route('admin.vhc.form_pesanan_voucher')}}"><button class="btn btn-primary btn-sm mb-3 btn-block" type="button" >Form Pesanan Voucher</button></a>
            </div>
            <div class="col-3">
                <a href="{{route('admin.vhc.update_data_voucher')}}"><button class="btn btn-primary btn-sm mb-3 btn-block" type="button" >Update data voucher manual</button></a>
            </div>
          <hr>
          <form >
            <div class="row mb-1">
              
                <div class="col-3">
                  <select name="status" class="custom-select custom-select-sm">
                    <option value="">-- All Data--</option>
                    <option value="PAID">PAID</option>
                    <option value="UNPAID">UNPAID</option>
                  </select>
                </div>
                <div class="col-3">
                  <input name="bulan" type="month" class="form-control form-control-sm"></input>
                </div>
                <div class="col-3">
                 <input type="text" name="q" class="form-control form-control-sm" value="" placeholder="Cari Data">
                </div>
                <div class="col-3">
                  <button type="submit" class="btn btn-block btn-dark btn-sm">Cari
                </div>
            </div>
            <form >
           <div class="table-responsive">
            <table id="" class="display table table-nowrap table-striped table-hover" >                          
                <thead>
                        <tr class="text-center">
                            <th>Id Pesanan</th>
                            <th>Site</th>
                            <th>Mitra</th>
                            <th>Outlet</th>
                            <th>Total</th>
                            <th>Tanggal Pesanan</th>
                            <th>Tanggal Proses</th>
                            <th>Tanggal Bayar</th>
                            <th>Status Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data_pesanan as $d)
                        <tr>
                        <td class="href_pesanan" data-id="{{$d->pesanan_id}}">{{$d->pesanan_id}}</td>
                        <td class="href_pesanan" data-id="{{$d->pesanan_id}}">{{$d->site_nama}}</td>
                        <td class="href_pesanan" data-id="{{$d->pesanan_id}}">{{$d->name}}</td>
                        <td class="href_pesanan" data-id="{{$d->pesanan_id}}">{{$d->outlet_nama}}</td>
                        <td class="href_pesanan" data-id="{{$d->pesanan_id}}">{{number_format($d->sumtotal_hpp)}}</td>
                        <td class="href_pesanan" data-id="{{$d->pesanan_id}}">{{$d->pesanan_tanggal}}</td>
                        <td class="href_pesanan" data-id="{{$d->pesanan_id}}">{{$d->pesanan_tgl_proses}}</td>
                        <td class="href_pesanan" data-id="{{$d->pesanan_id}}">{{$d->pesanan_tgl_bayar}}</td>
                        <td class="href_pesanan" data-id="{{$d->pesanan_id}}">{{$d->pesanan_status}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pull-left">
              Showing
              {{$data_pesanan->firstItem()}}
              to
              {{$data_pesanan->lastItem()}}
              of
              {{$data_pesanan->total()}}
              entries
            </div>
            <div class="pull-right">
              {{ $data_pesanan->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
            </div>
         </div>
      </div>
    </div>
  </div>
</div>





 


 
@endsection

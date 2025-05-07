@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
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
           
         
          <hr>
           <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover" >                          
                <thead>
                        <tr class="text-center">
                            <th>Id</th>
                            <th>Site</th>
                            <th>Paket</th>
                            <th>Paket</th>
                            <th>Id Voucher</th>
                            <th>Admin</th>
                            <th>Tanggal Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data_pesanan as $d)
                        <tr>
                        <td>{{$d->pesanan_id}}</td>
                        <td>{{$d->pesanan_siteid}}</td>
                        <td>{{$d->pesanan_paketid}}</td>
                        <td>{{$d->pesanan_mitraid}}</td>
                        <td>{{$d->pesanan_outletid}}</td>
                        <td>{{$d->pesanan_routerid}}</td>
                        <td>{{$d->pesanan_jumlah}}</td>
                        <td>{{$d->pesanan_hpp}}</td>
                        <td>{{$d->pesanan_komisi}}</td>
                        <td>{{$d->pesanan_total_hpp}}</td>
                        <td>{{$d->pesanan_total_komisi}}</td>
                        <td>{{$d->pesanan_admin}}</td>
                        <td>{{$d->pesanan_tanggal}}</td>
                        <td>{{$d->pesanan_status}}</td>
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

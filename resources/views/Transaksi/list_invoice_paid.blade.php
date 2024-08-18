@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <a href="{{route('admin.psb.list_input')}}" class="col-6 col-sm-4 col-lg-4">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$inv_count_bulan}}</div>
            <div class="text-muted mb-3">INV LUNAS BULAN INI</div>
          </div>
        </div>
      </a>
      <a href="{{route('admin.reg.index')}}" class="col-6 col-sm-4 col-lg-4">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">Rp. {{number_format($inv_hari)}}</div>
            <div class="text-muted mb-3">TOTAL LUNAS HARI INI</div>
          </div>
        </div>
      </a>
      <div class="col-6 col-sm-4 col-lg-4">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">Rp. {{number_format($inv_bulan)}}</div>
            <div class="text-muted mb-3">TOTAL LUNAS BULAN INI</div>
          </div>
        </div>
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
        <hr>
        <form >
          <div class="row mb-1">
            <div class="col-sm-4">
              <input name="tglbayar" type="date" class="form-control form-control-sm" >
            </div>
            <div class="col-sm-4">
              <input name="q" type="text" class="form-control form-control-sm" placeholder="Cari">
            </div>
            <div class="col-sm-2">
              <button type="submit" class="btn btn-block btn-dark btn-sm">Submit1
            </div>
          </div>
          </form>
          <hr>
          <div class="table-responsive">
            <table id="" class="display table table-striped table-hover text-nowrap" >
              <thead>
                <tr>
                  <th>INVOICE</th>
                  <th>TGL BAYAR</th>
                  <th>TGL ISOLIR</th>
                  <th>ADMIN</th>
                  <th>CABAR</th>
                  <th>NO.LAYANAN</th>
                  <th>PELANGGAN</th>
                  <th>PROFILE</th>
                  <th>CHANNEL</th>
                  <th>MITRA</th>
                  <th>TOTAL</th>
                  <th>KATEGORI</th>
                  <th>REKENING</th>
                  <th>NOTE</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_invoice as $d)
                <tr>
                  <td class="href_inv" data-id="{{$d->inv_id}}" >INV-{{$d->inv_id}}</td>
                  <td class="href_inv" data-id="{{$d->inv_id}}" >{{date('d-m-Y', strtotime($d->inv_tgl_bayar))}}</td>
                  <td class="href_inv" data-id="{{$d->inv_id}}" >{{date('d-m-Y', strtotime($d->inv_tgl_isolir))}}</td>
                  <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_admin}}</td>
                  <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_cabar}}</td>
                  <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_nolayanan}}</td>
                  <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_nama}}</td>
                  <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_profile}}</td>
                  <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_payment_method}}</td>
                  <td class="href_inv" data-id="{{$d->inv_id}}" >{{number_format($d->inv_total_amount)}}</td>
                  <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_mitra}}</td>
                  <td class="href_inv" data-id="{{$d->inv_id}}" >{{$d->inv_kategori}}</td>
                  <td class="href_inv" data-id="{{$d->inv_id}}" >-</td>
                      <td>{{$d->inv_note}}</td>
                    </tr>
                    @endforeach
              </tbody>
            </table>
          </div>
          <div class="pull-left">
            Showing
            {{$data_invoice->firstItem()}}
            to
            {{$data_invoice->lastItem()}}
            of
            {{$data_invoice->total()}}
            entries
          </div>
          <div class="pull-right">
            {{ $data_invoice->withQueryString()->links('pagination::bootstrap-4') }}
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

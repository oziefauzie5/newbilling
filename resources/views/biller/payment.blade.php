@extends('layout.user')
@section('content')

<div class="content">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">

                
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--5">
            <div class="col-12 col-sm-12">
                <!-- <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                      <input type="number" class="form-control"  id="biller_idpel" placeholder="No. Invoice/Id Pelanggan">
                      <button type="submit" id="cari_pelanggan" class="btn btn-primary" ><strong><i class="fas fa-search"></i>Cari</strong></button>
                    </div>
                  </div>
              </div> -->

              <div class="input-group mb-3">
                <input type="text" class="form-control" id="biller_idpel" placeholder="No. Invoice/Id Pelanggan" data-toggle="modal" data-target="#cari_data_inv">
                  <div class="input-group-append">
                    <button class="btn btn-primary" type="submit" id="cari_pelanggan"  ><i class="fas fa-search"></i> Cari</button>
                  </div>
            </div>
{{-- MODAL CARI DATA  --}}
<div class="modal fade" id="cari_data_inv" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="staticBackdropLabel">Cari Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="cari_inv" class="display table table-striped table-hover text-nowrap" >
            <thead>
              <tr>
                <th>No Layanan</th>
                <th>Nama</th>
                <th>No Hp</th>
                <th>Alamat</th>
              </tr>
            </thead>
            <tbody>
              @foreach($input_data as $d)
              <tr id="{{$d->inv_id}}">
                <td>{{$d->inv_nolayanan}}</td>
                <td>{{$d->inv_nama}}</td>
                <td>0{{$d->input_hp}}</td>
                <td>{{$d->input_alamat_pasang}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-sm">Submit</button>
      </div>
    </div>
  </div>
</div>
        {{-- END MODAL CARI DATA  --}}

            </div>
          </div>
          <section class="content mt-3">
            <div class="card card_custom1">
                <div id="progress"></div>
            <div  id="text" class="text-center text-danger card"></div>
<div class="card" id="hidden_div" style="display:none;">
  <div class="table-responsive">
    <table class="display table table-striped table-hover text-nowrap" >
        <tbody>
          <tr>
            <td width="115px">Pelanggan</td>
            <td><div id="upd_pelanggan"></div></td>
          </tr>
          <tr>
            <td>Phone</td>
            <td id="hp"></td>
          </tr>
          <tr>
            <td>No. Layanana</td>
            <td id="nolay"></td>
          </tr>
          <tr>
            <td>Alamat</td>
            <td id="alamat_pasang"></td>
          </tr>
          <tr>
            <td>Jenis Invoice</td>
            <td id="upd_kategori"></td>
          </tr>
          <tr>
            <td>Tgl Invoice</td>
            <td id="upd_tgl_tagih"></td>
          </tr>
          <tr>
            <td>Jatuh Temo</td>
            <td id="upd_tgl_jatuh_tempo"></td>
        </tr>
        <tr>
            <td>Sub Total</td>
            <td id="subinvoice_harga"></td>
        </tr>
        <tr>
            <td>PPN</td>
            <td id="subinvoice_ppn"></td>
        </tr>
        <tr>
            <td>Diskon</td>
            <td id="subinvoice_diskon"></td>
        </tr>
        <tr>
            <td>Biaya ADM</td>
            <td id="biaya_layanan"></td>
        </tr>
        <tr>
            <td>Total Tagihan</td>
            <td id="subinvoice_total"></td>
          </tr>
        </tbody>
      </table>
    </div>
      <div id="buton"></div>
    </div>
    
    <a href="{{ URL::previous() }}" class="btn btn-block btn-primary text-light">Kembali</a>
        </div>
    </section>


</div>
</div>

@endsection

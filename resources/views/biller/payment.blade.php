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
                <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                      <input type="number" class="form-control"  id="biller_idpel" placeholder="No. Invoice/Id Pelanggan">
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <button type="submit" id="cari_pelanggan" class="btn btn-primary" ><strong><i class="fas fa-search"></i>Cari</strong></button>
                    </div>
                  </div>
              </div>
            </div>
          </div>
          <section class="content mt-3">
            <div class="card card_custom1 p-3">
                <div id="progress"></div>
            <div  id="text" class="text-center text-danger card"></div>
<div class="card" id="hidden_div" style="display:none;">
    <table class="table table-sm">
        <tbody>
          <tr>
            <td width="115px">Pelanggan</td>
            <td>:</td>
            <td><div id="upd_pelanggan"></div></td>
          </tr>
          <tr>
            <td>Phone</td>
            <td>:</td>
            <td id="hp"></td>
          </tr>
          <tr>
            <td>No. Layanana</td>
            <td>:</td>
            <td id="nolay"></td>
          </tr>
          <tr>
            <td>Alamat</td>
            <td>:</td>
            <td id="alamat_pasang"></td>
          </tr>
          <tr>
            <td>Jenis Invoice</td>
            <td>:</td>
            <td id="upd_kategori"></td>
          </tr>
          <tr>
            <td>Tgl Invoice</td>
            <td>:</td>
            <td id="upd_tgl_tagih"></td>
          </tr>
          <tr>
            <td>Jatuh Temo</td>
            <td>:</td>
            <td id="upd_tgl_jatuh_tempo"></td>
        </tr>
        <tr>
            <td>Sub Total</td>
            <td>:</td>
            <td id="subinvoice_harga"></td>
        </tr>
        <tr>
            <td>PPN</td>
            <td>:</td>
            <td id="subinvoice_ppn"></td>
        </tr>
        <tr>
            <td>Diskon</td>
            <td>:</td>
            <td id="subinvoice_diskon"></td>
        </tr>
        <tr>
            <td>Biaya ADM</td>
            <td>:</td>
            <td id="biaya_layanan"></td>
        </tr>
        <tr>
            <td>Total Tagihan</td>
            <td>:</td>
            <td id="subinvoice_total"></td>
          </tr>
        </tbody>
      </table>
      <div id="buton"></div>
    </div>
    <a href="{{ URL::previous() }}" class="btn btn-block btn-primary text-light">Kembali</a>
        </div>
    </section>


</div>
</div>

@endsection

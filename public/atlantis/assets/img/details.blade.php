@extends('layout.main_biller')
@section('content')
<div class="content-wrapper ">
    <section class="content">

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
      <a href="{{ route('admin.biller.print',['id'=>$invoice]) }}" target="_blank" class="btn btn-block" style="background: linear-gradient(to right, #5d69be, #C89FEB);">Print</a>
      {{-- <div id="buton"></div> --}}
    </div>
    <a href="{{ URL::previous() }}" class="btn btn-block" style="background: linear-gradient(to right, #5d69be, #C89FEB);">Kembali</a>
<script>
    // $('#cari').click(function(e) {
        $( function() {

        let id   = {{ $invoice }};
        


        $("#progress").html('<button class="btn btn-block " style="background: linear-gradient(to right, #5d69be, #C89FEB);" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>');
    var url = '{{ route("admin.biller.getDataLunas", ":id") }}';
    url = url.replace(':id', id);
    var url_bayar = '{{ route("admin.biller.print", ":id") }}';
    url_bayar = url_bayar.replace(':id', id);



    if ({{ $invoice }}) {
        $.ajax({
            url: url,
                        type: 'GET',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
                            console.log(data);
                            if (data['data']) {
                                // $('#myModal').modal('hide');
                                document.getElementById('hidden_div').style.display = "block";
                                $("#progress").html('');
                                $("#text").html('');
                                var harga = data['sumharga'];
                                var ppn = data['data'].subinvoice_ppn;
                                var komisi = data['biller'].mts_komisi;
                                var diskon = data['data'].upd_diskon;
                                var total = parseInt(harga)+parseInt(ppn)+parseInt(komisi)-parseInt(diskon);
                                document.getElementById("upd_pelanggan").innerHTML =data['data'].nama;
                                document.getElementById("hp").innerHTML =data['data'].hp;
                                document.getElementById("nolay").innerHTML =data['data'].upd_nolayanan;
                                document.getElementById("alamat_pasang").innerHTML =data['data'].alamat_pasang;
                                document.getElementById("upd_kategori").innerHTML =data['data'].upd_kategori;
                                document.getElementById("upd_tgl_tagih").innerHTML =data['data'].upd_tgl_tagih;
                                document.getElementById("upd_tgl_jatuh_tempo").innerHTML =data['data'].upd_tgl_jatuh_tempo;
                                document.getElementById("subinvoice_harga").innerHTML =rupiah(data['sumharga']);
                                document.getElementById("subinvoice_diskon").innerHTML =rupiah(data['data'].upd_diskon);
                                document.getElementById("subinvoice_ppn").innerHTML =rupiah(data['data'].subinvoice_ppn);
                                document.getElementById("biaya_layanan").innerHTML =rupiah(data['biller'].mts_komisi);
                                document.getElementById("subinvoice_total").innerHTML =rupiah(total);
                                $("#buton").html('<input value="Proses Pembayaran" id="bayar" class="btn btn-block" style="background: linear-gradient(to right, #5d69be, #C89FEB);" ></input>');
                                $('#bayar').click(function(e) {

                                    $("#buton").html('<button class="btn btn-block " style="background: linear-gradient(to right, #5d69be, #C89FEB);" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>');

                                    $.ajax({
                                        url: url_bayar,
                                        type: 'GET',
                                        data: {
                                            '_token': '{{ csrf_token() }}'
                                        },
                                        dataType: 'json',
                                        success: function(data) {
                                            console.log(data)
                                            // $("#bayar").html('LUNAS');
                                            if(data.alert=='success'){
                                                Swal.fire({
                                                    title: data.pesan,
                                                    icon: data.alert
                                                })
                                                $("#buton").html('<input value="Lunas" disable class="btn btn-block" style="background: linear-gradient(to right, #5d69be, #C89FEB);" ></input>');
                                        }else {
                                            Swal.fire({
                                                title: data.pesan,
                                                icon: data.alert
                                            })
                                            $("#buton").html('<input value="Proses Pembayaran" id="bayar" class="btn btn-block" style="background: linear-gradient(to right, #5d69be, #C89FEB);" ></input>');
                                        }

                                        },
                                        error: function(error) {
                                            $("#buton").html('<input value="Proses Pembayaran" id="bayar" class="btn btn-block" style="background: linear-gradient(to right, #5d69be, #C89FEB);" ></input>');
                                             $("#text").html('Transaksi Gagal. Coba lagi nanti...');


                                        },
                                    });



                                });
                            }  else {
                                document.getElementById('hidden_div').style.display = "none";
                                $("#progress").html('');
                                $("#text").html('<strong>No. Invoice/Id Pelanggan tidak ditemukan</strong>');
                            }
                        },
                        error: function(error) {
                            $("#progress").html('');
                            $("#text").html('error. Coba lagi nanti...');
                        },

                    });
                } else {
                    document.getElementById('hidden_div').style.display = "none";
                            $("#progress").html('');
                            $("#text").html('Masukan No. Invoice/Id Pelanggan terlebih dahulu');
                    $('#harga').empty();
                }

            });
</script>


{{-- <script>
   jQuery(document).ready(function ($) {

$("#cari").submit(function (event) {
            event.preventDefault();
            //validation for login form
    $("#progress").html('<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>');
    var url = '{{ route("admin.biller.getDataBayar", ":id") }}';
                                        url = url.replace(':id', id);
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (returndata)
            {
                //show return answer
                alert(returndata);
            },
            error: function(){
            alert("error in ajax form submission");
                                }
    });
    return false;
});
});
  </script> --}}
    </section>
  </div>
@endsection

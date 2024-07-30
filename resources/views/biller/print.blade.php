<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>{{Session::get('app_brand')}}</title>
        <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
        <link rel="icon" href="{{asset('atlantis/assets/img/icon.ico')}}" type="image/x-icon"/>
    <style>
        body {
            margin: 10px;
            padding: 0;
            font-family: 'PT Sans', sans-serif;
        }

        @page {
            size: 2.8in 11in;
            margin-top: 0cm;
            margin-left: 0cm;
            margin-right: 0cm;
        }

        table {
            width: 100%;
        }

        tr {
            width: 100%;
            height: 20px;
        }

        h1 {
            text-align: center;
            vertical-align: middle;
        }


        header {
            width: 100%;
            text-align: center;
            -webkit-align-content: center;
            align-content: center;
            vertical-align: middle;
        }


        .center-align {
            text-align: center;
        }

        .bill-details td {
            font-size: 12px;
        }

        .receipt {
            font-size: medium;
        }

        .items .heading {
            font-size: 12.5px;
            text-transform: uppercase;
            margin-bottom: 5px;
            border-bottom: 2px solid black;
            vertical-align: middle;
        }

        .items thead tr th:first-child,
        .items tbody tr td:first-child {
            width: 47%;
            min-width: 47%;
            max-width: 47%;
            word-break: break-all;
            text-align: left;
        }

        .items td {
            font-size: 12px;
            /* text-align: right; */
            vertical-align: bottom;
            /* height: 30PX; */
        }

        .price::before {
             content: "Rp";
            font-family: Arial;
            text-align: right;
        }

        .sum-up {
            text-align: right !important;
        }
        .total {
            font-size: 13px;
            border-top:1px dashed black !important;
            border-bottom:1px dashed black !important;
        }
        .total.text, .total.price {
            text-align: right;
        }
        .total.price::before {
            content: "Rp";
        }
        .line {
            border-top:1px solid black !important;

        }
        .heading.rate {
            width: 20%;
        }
        .heading.amount {
            width: 25%;
        }
        .heading.qty {
            width: 5%
        }
        p {
            padding: 1px;
            margin: 0;
        }
        section, footer {
            font-size: 12px;
        }
    </style>
</head>

<body>

    <table class="bill-details">
        <tbody>
            <tr>
                <td>Tanggal Cetak : <span>{{ date('d-m-Y h:m:s') }}</span></td>
            </tr>

        </tbody>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th class="heading name" colspan="2">{{Session::get('app_brand')}}</th>
                <th class="heading name " style="text-align:right" >Admin : {{ $admin }}</th>
            </tr>

        </thead>

        <tbody>

            <tr>
                <td style="width:10%;">NO.INVOICE</td>
                <td style="width:1%;">:</td>
                <td colspan="2" style="width:auto;">{{ $data->inv_id }}</td>
            </tr>
            <tr>
                <td>NO.LAYANAN</td>
                <td>:</td>
                <td  colspan="2">{{ $data->inv_nolayanan }}</td>
              </tr>


              <tr>
                <td>PELANGGAN</td>
                <td>:</td>
                <td  colspan="2">{{ $data->inv_nama }}</td>
              </tr>


              <tr>
                <td>TGL INV</td>
                <td>:</td>
                <td colspan="2">{{ date('d/m/Y',strtotime($data->inv_tgl_tagih)) }}</td>
              </tr>
              <tr>
                <td>JTH TEMPO</td>
                <td>:</td>
                <td  colspan="2">{{ date('d/m/Y',strtotime($data->inv_tgl_jatuh_tempo)) }}</td>
              </tr>


              <tr>
                <td>TGL BAYAR</td>
                <td>:</td>
                <td  colspan="2">{{ date('d/m/Y',strtotime($data->inv_tgl_bayar)) }}</td>
              </tr>

              <tr>
                <td>CHANNEL</td>
                <td>:</td>
                <td  colspan="2">{{ $data->akun_nama }}</td>
              </tr>
              <tr>
                <td>STS BAYAR</td>
                <td>:</td>
                <td  colspan="2">Sudah Lunas</td>
              </tr>
              @foreach ($datainvoice as $c )
<tr>
<td colspan="2" class="line" style="width:95%;">{{ $c->subinvoice_deskripsi . ' x ' . $c->subinvoice_qty }}<br><sup style="font-size:8px; margin-top:0px"></sup></td>
<td class="line" style="font-weight:bold; text-align:right">{{ number_format($c->subinvoice_harga) }}</td>
</tr>
@endforeach
            <tr>
                <td colspan="2" class="sum-up line">Subtotal</td>
                <td class="line price" style="text-align:right">{{ number_format($sumharga) }}</td>
            </tr>
            <tr>
                <td colspan="2" class="sum-up">Ppn {{ env('PPN') }}%</td>
                <td class="price" style="text-align:right">{{ number_format($sumppn) }}</td>
            </tr>
            <tr>
                <td colspan="2" class="sum-up">Diskon</td>
                <td class="price" style="text-align:right">{{ number_format($data->inv_diskon) }}</td>
            </tr>
            <tr>
                <td colspan="2" class="sum-up">Biaya Admin</td>
                <td class="price" style="text-align:right">{{ number_format($biller->mts_komisi) }}</td>
            </tr>
            <tr>
                <th colspan="2" class="sum-up">Total</th>
                <th class="sum-up" style="text-align:right">{{ number_format($sumharga+$sumppn+$biller->mts_komisi-$data->inv_diskon) }}</th>
            </tr>
        </tbody>
    </table>
<br><br><br>
<button onclick="window.print()" class="no-print">Print</button>
</body>

</html>

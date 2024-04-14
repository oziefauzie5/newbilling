<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            /* border-collapse: collapse; */
            width: 100%;
        }

        #customers td,
        #customers th {
            /* border: 1px solid #5b5b5b; */
            padding: 3px;
            font-size: 9pt;
        }
        #rincian {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #rincian td,
        #rincian th {
            border: 1px solid #5b5b5b;
            padding: 3px;
            font-size: 9pt;
            border-collapse: collapse;
        }

        

        #box {
            border: 1px solid #5b5b5b;
            width: 17%;
        }

        #kop {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            text-align: left;
            font-size: 10pt;
        }

        #ttd {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;

        }

        #ttd td,
        #ttd th {
            padding: 3px;
            font-size: 9pt;
            height: 90px;

        }

        #customers .rupiah {
            text-align: right;
        }

        #sk {
            font-size: 10pt;
            color: red;
        }


        /* #customers tr:nth-child(even){background-color: #dbdbdb;} */

        /* #customers tr:hover {background-color: #8b3838;} */

        #customers th {
            /* padding-top: 12px;
          padding-bottom: 12px; */
            text-align: left;
            /* background-color: #b8b8b8; */
            /* color: rgb(0, 0, 0); */
            font-size: 10pt;

        }

        #invoice {
            float: right;
            text-align: right;
        }
        #nominal {
            text-align: right;
        }

        #client {
            float: left;
        }

        hr {
            margin-top: -10px;
            margin-bottom: 1px
        }
    </style>
</head>

<body>

    <table id="kop">
        <tr>
            <th width="60%" style=" text-align: left;"><img src="{{ asset('atlantis/assets/img/'.$profile_perusahaan->app_logo)}}" alt=""></th>
            <td width="40%"><strong>{{$profile_perusahaan->app_nama}}</strong> <br><span>{{$profile_perusahaan->app_brand}}</span><br><span>{{$profile_perusahaan->app_alamat}}</span></td>
        </tr>
    </table>
    <br>
    <hr>
    <table id="customers">
        <br>
        <tr>
            <th colspan="3" style="text-align: center; font-size: 12pt;">BUKTI PENGELUARAN KAS</th>
        </tr>
        <tr>
            <td width="15%">No</td>
            <td>:</td>
            <td width="85%">................</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ date('d-m-Y', strtotime( $kas->reg_tgl_pasang)) }}</td>
        </tr>
        <tr>
            <td>Dibayarkan kepada</td>
            <td>:</td>
            <td>{{$kas->teknisi_team}}</td>
        </tr>
       
    </table>
    <table id="rincian">
        <tr>
            <th width="5%">NO</th>
            <th>KETERANGAN</th>
            <th width="25%">NOMINAL</th>
        </tr>
        <tr>
            <td style="text-align: center;">1</td>
            <td>PSB {{$kas->input_nama}}</td>
            <td id="nominal">Rp. {{number_format($kas->teknisi_psb)}}</td>
        </tr>
        <tr>
            <td style="text-align: center;">2</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: center;">3</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: center;">4</td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <table id="customers">
        <tr>
            <td width="50%"></td>
            <td id="invoice" width="15%">JUMLAH</td>
            <td>:</td>
            <td id="invoice">Rp. {{number_format($kas->teknisi_psb)}}</td>
        </tr>
        <tr>
            <td width="50%"></td>
            <td id="invoice" width="15%">TOTAL</td>
            <td>:</td>
            <td id="invoice">Rp. {{number_format($kas->teknisi_psb)}}</td>
        </tr>
    </table>
    <table id="ttd">
        <tr>
            <th width="25%" higth="20px">Admin</th>
            <th width="25%">NOC</th>
            <th width="25%">Keuangan</th>
            <th width="25%">Penerima</th>
        </tr>
        <tr>
            <th width="25%" higth="20px">{{ $nama_admin }}</th>
            <th width="25%">...................................</th>
            <th width="25%">...................................</th>
            <th width="25%">{{$kas->teknisi_team}}</th>
        </tr>
    </table>
    <table id="kop">
        <tr>
            <th width="60%" style=" text-align: left;"><img src="{{ asset('atlantis/assets/img/'.$profile_perusahaan->app_logo)}}" alt=""></th>
            <td width="40%"><strong>{{$profile_perusahaan->app_nama}}</strong> <br><span>{{$profile_perusahaan->app_brand}}</span><br><span>{{$profile_perusahaan->app_alamat}}</span></td>
        </tr>
    </table>
    <br>
    <hr>
    <table id="customers">
        <br>
        <tr>
            <th colspan="3" style="text-align: center; font-size: 12pt;">BUKTI PENGELUARAN KAS</th>
        </tr>
        <tr>
            <td width="15%">No</td>
            <td>:</td>
            <td width="85%">................</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ date('d-m-Y', strtotime( $kas->reg_tgl_pasang)) }}</td>
        </tr>
        <tr>
            <td>Dibayarkan kepada</td>
            <td>:</td>
            <td>{{$seles->name}}</td>
        </tr>
       
    </table>
    <table id="rincian">
        <tr>
            <th width="5%">NO</th>
            <th>KETERANGAN</th>
            <th width="25%">NOMINAL</th>
        </tr>
        <tr>
            <td style="text-align: center;">1</td>
            <td>PSB {{$kas->input_nama}}</td>
            <td id="nominal">Rp. {{number_format($biaya_sales->biaya_sales)}}</td>
        </tr>
        <tr>
            <td style="text-align: center;">2</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: center;">3</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: center;">4</td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <table id="customers">
        <tr>
            <td width="50%"></td>
            <td id="invoice" width="15%">JUMLAH</td>
            <td>:</td>
            <td id="invoice">Rp. {{number_format($biaya_sales->biaya_sales)}}</td>
        </tr>
        <tr>
            <td width="50%"></td>
            <td id="invoice" width="15%">TOTAL</td>
            <td>:</td>
            <td id="invoice">Rp. {{number_format($biaya_sales->biaya_sales)}}</td>
        </tr>
    </table>
    <table id="ttd">
        <tr>
            <th width="25%" higth="20px">Admin</th>
            <th width="25%">NOC</th>
            <th width="25%">Keuangan</th>
            <th width="25%">Penerima</th>
        </tr>
        <tr>
            <th width="25%" higth="20px">{{ $nama_admin }}</th>
            <th width="25%">...................................</th>
            <th width="25%">...................................</th>
            <th width="25%">{{$seles->name}}</th>
        </tr>
    </table>
 

</body>

</html>
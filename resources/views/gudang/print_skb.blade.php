<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        #kas {
            font-family: Arial, Helvetica, sans-serif;
            /* border-collapse: collapse; */
            width: 100%;
        }

        #kas td,
        #kas th {
            /* border: 1px solid #5b5b5b; */
            padding: 3px;
            font-size: 9pt;
        }
        #kas_rincian {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #kas_rincian td,
        #kas_rincian th {
            border: 1px solid #5b5b5b;
            padding: 3px;
            font-size: 11pt;
            /* word-wrap: break-word; */
            border-collapse: collapse;
        }

        

        #kas_box {
            border: 1px solid #5b5b5b;
            width: 17%;
        }

        #kas_kop {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            text-align: left;
            font-size: 10pt;
        }

        #kas_ttd {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;

        }

        #kas_ttd td,
        #kas_ttd th {
            padding: 3px;
            font-size: 9pt;
            height: 90px;

        }

        #kas .rupiah {
            text-align: right;
        }

        #kas_sk {
            font-size: 10pt;
            color: red;
        }


        /* #customers tr:nth-child(even){background-color: #dbdbdb;} */

        /* #customers tr:hover {background-color: #8b3838;} */

        #kas_ th {
            /* padding-top: 12px;
          padding-bottom: 12px; */
            text-align: left;
            /* background-color: #b8b8b8; */
            /* color: rgb(0, 0, 0); */
            font-size: 10pt;

        }

        #kas_invoice {
            float: right;
            text-align: right;
        }
        #kas_nominal {
            text-align: right;
        }

        #kas_client {
            float: left;
        }

        hr {
            margin-top: -10px;
            margin-bottom: 1px
        }
        #customers {
          font-family: Arial, Helvetica, sans-serif;
          /* border-collapse: collapse; */
          width: 100%;
        }
        #customers td, #customers th {
            /* border: 1px solid #5b5b5b; */
            padding: 3px;
            font-size: 9pt;
        }
        #box {
            border: 1px solid #5b5b5b;
            width:17%;
        }
        #ttd {
          font-family: Arial, Helvetica, sans-serif;
          border-collapse: collapse;
          width: 100%;
          
        }
        #ttd td, #ttd th {
          padding: 3px;
          font-size: 9pt;
          height: 80px;

        }
        #customers .rupiah{
            text-align: right;
        }
        #sk {
            font-size: 10pt;
            color:red;
        }

        #customers th {
          text-align: left;
          font-size: 10pt;

        }
        #invoice {
        float: right;
        text-align: right;
        }
        #client {
        float: left;
    }
    </style>
</head>

<body>

    <table id="kas_kop">
        <tr>
            <th width="60%" style=" text-align: left;"><img src="{{ asset('storage/profile_perusahaan/'.$profile_perusahaan->app_logo)}}" width="20%" alt=""></th>
            <td width="40%"><strong>{{$profile_perusahaan->app_nama}}</strong> <br><span>{{$profile_perusahaan->app_brand}}</span><br><span>{{$profile_perusahaan->app_alamat}}</span></td>
        </tr>
    </table>
    <br>
    <hr>
    <table id="kas">
        <br>
        <tr>
            <th colspan="3" style="text-align: center; font-size: 12pt;">INVOICE</th>
        </tr>
        <tr>
            <td width="15%">No</td>
            <td>:</td>
            <td width="40%">{{$data->bk_id}}</td>
        </tr>
        <tr>
            <td>Tanggal Cetak</td>
            <td>:</td>
            <td>{{date('d-M-Y h:m')}}</td>
            <td>Tanggal Barang Keluar</td>
            <td>:</td>
            <td>{{date('d-M-Y', strtotime($data->bk_waktu_keluar))}}</td>
                </tr>
                <tr>
                    <td>Jenis Laporan</td>
                    <td>:</td>
                    <td>{{$data->bk_jenis_laporan}}</td>
                </tr>
        <tr>
            <td>Keterangan</td>
            <td>:</td>
            <td>{{$data->bk_keperluan}}</td>
        </tr>

       
    </table>
    <table id="kas_rincian">
        <tr>
                <th>#</th>
                <th>Jenis Barang</th>
                <th>Kategori</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Jumlah</th>
        </tr>
        @foreach($print_skb as $skb )
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ $skb->barang_jenis }}</td>
            <td >{{ $skb->barang_kategori }}</td>
            <td>{{ ucfirst($skb->barang_nama) .'  '. ucfirst($skb->barang_merek) .'  '.strtolower($skb->barang_sn) .'  '. strtolower($skb->barang_mac)}}</td>
            <td style="text-align: center">{{ $skb->bk_jumlah }}</td>
            <td style="text-align: center">{{ $skb->barang_satuan }}</td>
            <td style="text-align: right">{{ number_format($skb->barang_harga_satuan) }}</td>
            <td style="text-align: right">{{ number_format($skb->barang_harga_satuan * $skb->bk_jumlah) }}</td>
        </tr>
        @endforeach
        <tr>
        <td colspan="7" style="text-align: right">Total</td>
        <td colspan="1" style="text-align: right">{{ number_format($total) }}</td>
        </tr>
    </table>
    <br>
    <table id="kas_ttd">
        <tr>
            <th width="50%" higth="20px">Admin</th>
            <th width="50%">Penerima Barang</th>
        </tr>
        <tr>
            <th width="50%" higth="20px">{{ strtoupper($nama_admin )}}</th>
            <th width="50%">( ........................................... )</th>
        </tr>
    </table>
 
 

</body>

</html>
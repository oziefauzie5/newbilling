
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
          border-collapse: collapse;
          width: 100%;
        }
        #customers td, #customers th {
            border: 1px solid #5b5b5b;
            padding: 10px;
            font-size: 10pt;
        }
        #box {
            border: 1px solid #5b5b5b;
            width:17%;
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
        #header  {
          font-family: Arial, Helvetica, sans-serif;
          /* border-collapse: collapse; */
          
          width: 100%;
          text-align: left;
          font-size: 10pt;
          
        }


        #customers tr:nth-child(even){background-color: #dbdbdb;}

        #customers tr:hover {background-color: #8b3838;}

        #customers th {
          padding-top: 12px;
          padding-bottom: 12px;
          text-align: left;
          background-color: #b8b8b8;
          color: rgb(0, 0, 0);
          font-size: 10pt;

        }
        #invoice {
        float: right;
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
            <th style="text-align: left;" width="60%"><img src="{{ asset('lte/dist/img/'.$profile_perusahaan->app_logo)}}" alt=""></th>
            <td width="40%"><strong>{{$profile_perusahaan->app_nama}}</strong> <br><span>{{$profile_perusahaan->app_brand}}</span><br><span>{{$profile_perusahaan->app_alamat}}</span></td>
        </tr>
    </table>
    <br>
 <hr>
 <br>
 <table id="header">
 @foreach($invoice as $inv)
      <tr>
        <th style="text-align: left;" width="15%">Suplier</th>
        <th style="text-align: left;">:</th>
        <th style="text-align: left;" width="55%">{{$inv->supplier_nama}}</th>
        <th style="text-align: left;" width="15%">Tanggal Pembelian</th>
        <th style="text-align: left;">:</th>
        <th style="text-align: left;">{{$inv->barang_tgl_beli}}</th>
      </tr>
      <tr>
        <th style="text-align: left;" width="15%">Alamat</th>
        <th>:</th>
        <th style="text-align: left;" width="55%">{{$inv->supplier_alamat}}</th>
        <th style="text-align: left;" width="15%">Nomor Transaksi</th>
        <th>:</th>
        <th style="text-align: left;">{{$inv->id_trx}}</th>
      </tr>
        @endforeach
 </table>
    <table id="customers">
        <br>
       <tr>
            <th colspan="7"><center>LAPORAN IN/OUT BARANG</center></th>
        </tr>
        <tr>
            <th width="70px">Tanggal</th>
            <th width="65px">ID Barang</th>
            <th>Nama Barang</th>
            <th>Mac Address</th>
            <th>Keterangan</th>
            <th>Admin</th>
            <th>Tanda Tangan</th>
        </tr>
        <tbody>
            @foreach($data_barang as $dt)
            <tr>
                @if($dt->tgl_digunakan)
                <td>{{ date('d-m-Y', strtotime( $dt->tgl_digunakan)) }}</td>
                @else
                <td></td>
                @endif
                <td>{{$dt->id_subbarang}}</td>
                <td>{{$dt->subbarang_nama}}</td>
                <td>{{$dt->subbarang_mac}}</td>
                <td>{{$dt->subbarang_keterangan}}</td>
                <td></td>
                <td></td>
            </tr>
            @endforeach
       </tbody>
    </table>
    <table id="ttd">
        <tr>
            <th width="50%" higth="20px">Storeman</th>
            <th width="50%">Mengetahui</th>
        </tr>
        <tr>
            <th width="50%" higth="20px">{{ $nama_admin }}</th>
            <th width="50%">...................................</th>
        </tr>
    </table>
    
</body>
</html>

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
            <td width="85%">................</td>
        </tr>
        <tr>
            <td>Tanggal Cetak</td>
            <td>:</td>
            <td>{{date('d-M-Y h:i')}}</td>
        </tr>
        <tr>
            <td>Tanggal Print</td>
            <td>:</td>
            <td>{{date('d-M-Y', strtotime($start_date))}} - {{date('d-M-Y', strtotime($end_date))}}</td>
        </tr>
       
    </table>
    <table id="kas_rincian">
        <tr>
              <th>#</th>
              <th>Kategori</th>
              <th>Jenis Jurnal</th>
              <th>Nama Barang</th>
              <th>Tanggal Masuk</th>
              <th>Satuan</th>
              <th width="15%">Total Stok Awal</th>
              <th>Nominal Rupiah</th>
        </tr>
        @foreach($stok_gudang as $sg )
        <tr>
            <td style="text-align: center">{{$loop->iteration}}</td>
            <td style="text-align: center">{{ $sg->barang_kategori }}</td> 
            <td style="text-align: center">{{ $sg->barang_jenis_jurnal }}</td> 
            <td style="text-align: left">{{ $sg->barang_nama }}</td> 
               <td style="text-align: center">{{ date('d-m-Y',strtotime($sg->barang_tglmasuk)) }}</td>
               <td style="text-align: center">{{ $sg->barang_satuan }}</td>
               <td style="text-align: center">{{ $sg->total }}</td>
               <td style="text-align: right">Rp. {{ number_format($sg->total_harga) }}</td>
        </tr>
           @endforeach
           <tr>
            <td colspan="7">TOTAL</td>
            <td style="text-align: right;font-weight: bold;">Rp. {{number_format($total_harga)}}</td>
           </tr>
    </table>
    <br>
    <table id="kas_ttd">
        <tr>
            <th width="30%">Manager</th>
            <th width="30%" higth="20px">Admin</th>
            <th width="30%">Staf yang melakukan pengecekan</th>
        </tr>
        <tr>
            <th width="30%">Sri Wahyuni</th>
            <th width="30%" higth="20px">{{ strtoupper($nama_admin )}}</th>
            <th width="30%">( ........................................... )</th>
        </tr>
    </table> <br><br>
    <table id="kas_rincian">
        @foreach($stok_gudang_akum as $sg )
        <tr>
            <td style="text-align: center">{{$loop->iteration}}</td>
            <td style="text-align: center">{{ $sg->barang_jenis_jurnal }}</td> 
               <td style="text-align: right">Rp. {{ number_format($sg->total_harga) }}</td>
        </tr>
           @endforeach
    </table>
    
    
    
</body>

</html>
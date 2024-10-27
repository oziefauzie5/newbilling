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

        #customers td,
        #customers th {
            border: 1px solid #5b5b5b;
            padding: 3px;
            font-size: 9pt;
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
            height: 80px;

        }

        #customers .rupiah {
            text-align: right;
        }


        #customers tr:nth-child(even) {
            background-color: #dbdbdb;
        }

        #customers tr:hover {
            background-color: #8b3838;
        }

        #customers th {
            /* padding-top: 12px;
          padding-bottom: 12px; */
            text-align: center;
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
            text-align: left;
        }

        hr {
            margin-top: -20px;
            margin-bottom: 1px
        }

        #invoice .date {
            font-size: 10pt;
            color: #000000;
            margin-top: -20px;
        }

        #client .no {
            font-size: 11pt;
            color: #000000;
            margin-top: -20px;
        }

        h3 {
            /* font-size: 10pt; */
            color: #034314;
            /* margin-top: -20px; */
        }

        #center {
            text-align: center;
        }

        #right {
            text-align: right;
        }
    </style>
</head>

<body>
    <h2>LAPORAN MINGGUAN ADMIN</h2>
    <hr>
    <div id="client">
        <h3>Nama : {{ $data->name }} </h3>
        <h3 class="no">ID Laporan : {{ $data->lm_id }}</h3>
    </div>
    <div id="invoice">

        <h3>Saldo Akhir : Rp. {{number_format($kredit-$debet)}}</h3>
        <div class="date">Periode Laporan : {{$data->lm_periode}}</div>
    </div>
    <br><br>
    
    <h2 id="center">JURNAL</h2>
    <table id="customers">
        <thead>
            <tr>
                <th width="25px">No</th>
                <th>TANGGAL</th>
                <th>KATEGORI</th>
                <th>KETERANGAN</th>
                <th>DESKRIPSI</th>
                <th>AKUN</th>
                <th>KREDIT</th>
                <th>DEBET</th>
                <th>SALDO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lap_mingguan as $d)
            <tr>
                <td id="center">{{$loop->iteration}}</td>
                <td>{{$d->tgl_trx}}</td>
                <td>{{$d->jurnal_kategori}}</td>
                <td>{{$d->jurnal_keterangan}}</td>
                <td>{{$d->jurnal_uraian}}</td>
                <td>{{$d->akun_nama}}</td>
                <td id="right">{{number_format($d->jurnal_kredit)}}</td>
                <td id="right">{{number_format($d->jurnal_debet)}}</td>
                <td id="right">{{number_format($d->jurnal_saldo)}}</td>
            </tr>
            @endforeach
           
    

        </tbody>
    </table>
    <br><br>
    <table id="customers">
        <thead>
            <tr>
                <th>SALDO AWAL</th>
                <th>TOTAL TRANSAKSI DEBET</th>
                <th>TOTAL TRANSAKSI KREDIT</th>
                <th>SALDO AKHIR</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td id="right">{{number_format($kredit)}}</td>
                <td id="right">{{number_format($debet)}}</td>
                <td id="right">{{number_format($kredit-$debet)}}</td>
            </tr>
        </tbody>
    </table>

   
   
    
    <br><br><br><br>
    <table id="ttd">
        <tr>
            <th width="50%" higth="20px">Diserahkan oleh</th>
            <th width="50%">Diterima Oleh</th>
        </tr>
        <tr>
            <th width="50%" higth="20px">{{ $admin['user_nama'] }}</th>
            <th width="50%">...................................</th>
        </tr>
    </table>
</body>

</html>
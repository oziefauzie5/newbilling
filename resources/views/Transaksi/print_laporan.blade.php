
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
            padding: 3px;
            font-size: 9pt;
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


        #customers tr:nth-child(even){background-color: #dbdbdb;}

        #customers tr:hover {background-color: #8b3838;}

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
        #center{
            text-align: center;
        }
        #right{
            text-align: right;
        }
        </style>
</head>
<body>
<h2>LAPORAN HARIAN ADMIN</h2>
<hr>
        <div id="client">
            <h3>Nama : {{ $admin }} </h3>
            <h3 class="no">ID Laporan : {{ $data_laporan->data_lap_id }}</h3>
        </div>
        <div id="invoice">
            {{-- <div class="to">Nama : {{ $admin_name }}</div> --}}
          <h3>Saldo Akhir : Rp. {{ number_format($data_laporan->data_lap_pendapatan) }}</h3>
          <div class="date">Periode Laporan : {{ date('d-m-Y',strtotime($data_laporan->data_lap_tgl)) }}</div>
        </div>
<br><br>
<br><br>
<table id="customers">
    <thead>
        <tr >
            <th>{{$sum_akun->akun_nama}}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td id="center">{{ number_format($sum_akun->jumlah) }}</td>
        </tr>
    </tbody>
</table>

<br><br>
    <table id="customers">
        <thead>
            <tr>
                <th width="25px">No</th>
                <th>TANGGAL</th>
                <th>INVOICE</th>
                <th>KETERANGAN</th>
                <th>POKOK</th>
                <th>PPN</th>
                <th>BPH_USO</th>
                <th>FEE MITRA</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $d)
            <tr>
                <td id="center">{{$loop->iteration}}</td>
                <td id="center">{{$d->tgl_trx}}</td>
                <td id="center">{{$d->lap_inv}}</td>
                <td>{{$d->lap_keterangan}}</td>
                <td id="right">{{number_format($d->lap_pokok)}}</td>
                <td id="right">{{number_format($d->lap_ppn)}}</td>
                <td id="right">{{number_format($d->lap_bph_uso)}}</td>
                <td id="right">{{number_format($d->lap_fee_mitra)}}</td>
                <td id="right">{{number_format($d->lap_pokok + $d->lap_ppn + $d->lap_bph_uso)}}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" id="center">TOTAL</td>
                <td id="right">{{number_format($sum_laporan->pokok)}}</td>
                <td id="right">{{number_format($sum_laporan->ppn)}}</td>
                <td id="right">{{number_format($sum_laporan->bph_uso)}}</td>
                <td id="right">{{number_format($sum_laporan->fee_mitra)}}</td>
                <td id="right">{{number_format($sum_laporan->jumlah)}}</td>
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
            <th width="50%" higth="20px">{{ $admin }}</th>
            <th width="50%">...................................</th>
        </tr>
    </table>
</body>
</html>

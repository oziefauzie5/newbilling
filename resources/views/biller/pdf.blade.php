
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
<h2>MUTASI TRANSAKSI</h2>
<hr>
        <div id="client">
            <h3>Nama : {{ $admin_name }} </h3>
        </div>
        <div id="invoice">
            {{-- <div class="to">Nama : {{ $admin_name }}</div> --}}
          <h3>Saldo Akhir : Rp. {{ number_format($saldo) }}</h3>
          <div class="date">Periode Export : {{ date('d-m-Y',strtotime($start_date)) .' - '. date('d-m-Y',strtotime($end_date)) }}</div>
        </div>
<br><br>
<br><br>
    <table id="customers">
        <thead>
            <tr>
                <th width="25px">No</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Debet</th>
                <th>Kredit</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mutasi as $d)
            <tr>
                <td id="center">{{$loop->iteration}}</td>
                <td id="center">{{ date('d-m-Y H:m:s',strtotime($d->tgl)) }}</td>
                <td id="center">{{$d->mt_kategori}}</td>
                <td >{{ $d->mt_deskripsi }}</td>
                <td id="right">{{ number_format($d->mt_kredit) }}</td>
                <td id="right">{{ number_format($d->mt_debet) }}</td>
                <td id="right">{{ number_format($d->mt_saldo) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

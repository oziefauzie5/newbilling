
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
        <tr>
            <th>TOTAL TRANSAKSI</th>
            <th>TRANSAKSI TUNAI</th>
            <th>REFUND</th>
            <th>TOTAL</th>
            <th>QTY</th>
        </tr>
    </thead>
    <tbody>
        <tr >
            <td id="center">Rp. {{number_format($data_laporan->data_lap_pendapatan)}}</td>
            <td id="center">Rp. {{number_format($total_tunai)}}</td>
            <td id="center">Rp. {{number_format($data_laporan->data_lap_refund)}}</td>
            <td id="center">Rp. {{number_format($total)}}</td>
            <td id="center">{{$data_laporan->data_lap_trx}}</td>
        </tr>
    </tbody>
</table>
<br><br>
<table id="customers">
    <thead>
        <tr>
            <th colspan="7">REKAPITULASI</th>
        </tr>
        <tr>
            <th>PENDAPATAN</th>
            <th>REFUND</th>
            <th>DANA LINGKUNGAN</th>
            <th>DANA KERJA SAMA</th>
            <th>FEE SALES</th>
            <th>PPN</th>
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>
        <tr >
            <td id="center">Rp. {{number_format($total-$total_kerjasama-$total_fee-$total_kas-$total_ppn)}}</td>
            <td id="center">Rp. {{number_format($data_laporan->data_lap_refund)}}</td>
            <td id="center">Rp. {{number_format($total_kas)}}</td>
            <td id="center">Rp. {{number_format($total_kerjasama)}}</td>
            <td id="center">Rp. {{number_format($total_fee)}}</td>
            <td id="center">Rp. {{number_format($total_ppn)}}</td>
            <td id="center">Rp. {{number_format($total-$data_laporan->data_lap_refund)}}</td>
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
                <th>FEE LINGKUNGAN</th>
                <th>FEE KERJA SAMA</th>
                <th>FEE SALES</th>
                <th>PPN</th>
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
                <td id="right">{{number_format($d->lap_fee_lingkungan)}}</td>
                <td id="right">{{number_format($d->lap_fee_kerja_sama)}}</td>
                <td id="right">{{number_format($d->lap_fee_marketing)}}</td>
                <td id="right">{{number_format($d->lap_ppn)}}</td>
                <td id="right">{{number_format($d->lap_kredit)}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br><br>
    <h2>LAPORAN HARIAN TRANSAKSI</h2>
    <hr>
    <br><br>
    <table id="customers">
    <thead>
                  <tr>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Admin</th>
                    <th>Deskripsi</th>
                    <th>Qty</th>
                    <th>Debet</th>
                    <th>Kredit</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($transaksi as $d)
                  <tr>
                    <td>{{date('d-m-Y',strtotime($d->created_at))}}</td>
                    <td>{{$d->trx_kategori}}</td>
                    <td>{{$d->trx_jenis}}</td>
                    <td>{{$d->trx_admin}}</td>
                    <td>{{$d->trx_deskripsi}}</td>
                    <td>{{$d->trx_qty}}</td>
                    <td>Rp. {{number_format($d->trx_debet)}}</td>
                    <td>Rp. {{number_format($d->trx_kredit)}}</td>
                  </tr>
                  @endforeach
                </tbody>
</table>
<br><br>
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

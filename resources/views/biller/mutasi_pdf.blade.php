
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
                <th>Tanggal Pembayaran</th>
                <th>Pelanggan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mutasi_sales as $d)
            <tr>
                <td id="center">{{$loop->iteration}}</td>
                <td id="center">{{ date('d-m-Y H:i:s',strtotime($d->mutasi_sales_tgl_transaksi)) }}</td>
                <td >{{ $d->mutasi_sales_deskripsi }}</td>
                <td id="right">{{ number_format($d->mutasi_sales_jumlah) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table id="kas_ttd">
        <tr>
            <th width="30%">Bogor, {{date('d M Y')}}</th>
            <th width="30%">Ketua {{ $admin_name }} </th>
            
        </tr>
        <tr>
            <th width="30%" higth="20px">PT Ovall Solusindo Mandiri</th>
            <th width="30%">( ........................................... )</th>
        </tr>
    </table>
<h2>DATA PELANGGAN {{ $admin_name }}</h2>
<hr>
<br>
    <table id="customers">
      <thead>
        <tr>
            <th>Pelanggan Aktif</th>
            <th>Pelanggan Putus</th>
            <th>Pelanggan Free</th>
            <th>Pelanggan Baru</th>
            <th>Total Pelanggan</th>
            <th>Pelanggan Lunas</th>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td id="center">{{$pel_aktif}}</td>
            <td id="center">{{$count_putus}}</td>
            <td id="center">{{$count_pelfree}}</td>
            <td id="center">{{$count_pel_baru}}</td>
            <td id="center">{{$total_pel}}</td>
            <td id="center">{{$pelanggan_lunas}}</td>
        </tr>
      </tbody>
    </table>

<br><br>
    <table id="customers">
        <thead>
            <tr>
                <th width="25px">No</th>
                <th>Pelanggan</th>
                <th>Tanggal Pemasangan</th>
                <th>Status Berlangganan</th>
                <th>Layanan</th>
                <th>Status Pembayaran</th>
                <th>Fee</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_pelannggan as $d)
            <tr>
                <td id="center">{{$loop->iteration}}</td>
                <td >{{ $d->input_nama }}</td>
                <td id="center">{{ date('d-m-Y',strtotime($d->reg_tgl_pasang)) }}</td>
                @if($d->reg_progres <= '5')
                <td id="center">Berlangganan</td>
                @elseif($d->reg_progres > '5')
                <td id="center">Putus Berlangganan</td>
                @endif
                @if($d->reg_jenis_tagihan == 'FREE')
                <td id="center">Free</td>
                @else
                <td id="center">{{$d->reg_jenis_tagihan}}</td>
                @endif
                @if( $d->reg_progres > '5' )
                <td id="">-</td>
                @else
                @if( $d->reg_status == 'PAID' )
                <td id="">Lunas</td>
                @else
                <td id="">Belum Bayar</td>
                @endif
                @endif
                <td id="right">Rp. {{ number_format($d->reg_fee) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table id="kas_ttd">
        <tr>
            <th width="30%">Bogor, {{date('d M Y')}}</th>
            <th width="30%">Ketua {{ $admin_name }} </th>
            
        </tr>
        <tr>
            <th width="30%" higth="20px">PT Ovall Solusindo Mandiri</th>
            <th width="30%">( ........................................... )</th>
        </tr>
        <!-- <tr>
            <th width="30%" >Keuangan</th>
        </tr> -->
    </table>
</body>
</html>

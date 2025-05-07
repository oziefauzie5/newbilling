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
            <th width="60%" style=" text-align: left;"><img src="{{ asset('storage/profile_perusahaan/'.Session::get('app_logo'))}}" width="20%" alt=""></th>
            <td width="40%"><strong>{{Session::get('app_nama')}}</strong> <br><span>{{Session::get('app_brand')}}</span><br><span>{{Session::get('app_alamat')}}</span></td>
        </tr>
    </table>
    <br>
    <hr>
    <table id="kas">
        <br>
        <tr>
            <th colspan="3" style="text-align: center; font-size: 12pt;">LAPORAN DEAKTIVASI PELANGGAN</th>
        </tr>
        <tr>
            <td width="15%">No</td>
            <td>:</td>
            <td width="85%">................</td>
        </tr>
        <tr>
            <td>Tanggal Cetak</td>
            <td>:</td>
            <td>{{date('d-M-Y h:m')}}</td>
        </tr>
        <tr>
            <td>Periode</td>
            <td>:</td>
            <td>{{date('d-m-Y',strtotime($start_date)) .' - '. date('d-m-Y',strtotime($end_date))}}</td>
        </tr>
       
    </table>
    <table id="kas_rincian">
        <tr>
                <th>#</th>
                <th>Admin</th>
                <th>Id Pelanggan</th>
                <th>Nomor Layanan</th>
                <th>Nama</th>
                <th>Alasan Deaktivasi</th>
                <th>Kelangkapan Perangkat</th>
                <th>Tanggal Pengambilan</th>
                <th>Mac Address</th>
                <th>Serial Number</th>
        </tr>
        @foreach($list_deaktivasi as $ld )
        <tr>
            <td>{{$loop->iteration}}</td>
            <td >{{ $ld->deaktivasi_admin }}</td>
            <td >{{ $ld->deaktivasi_idpel }}</td>
            <td >{{ $ld->reg_nolayanan }}</td>
            <td >{{ $ld->input_nama }}</td>
            <td >{{ $ld->deaktivasi_alasan_deaktivasi }}</td>
            <td >{{ $ld->deaktivasi_kelengkapan_perangkat }}</td>
            <td >{{ date('d-m-Y', strtotime($ld->deaktivasi_tanggal_pengambilan)) }}</td>
            <td >{{ $ld->deaktivasi_mac }}</td>
            <td >{{ $ld->deaktivasi_sn }}</td>
        </tr>
           @endforeach
    </table>
    <br>
    <table id="kas_ttd">
        <tr>
            <th width="30%">Manager</th>
            <th width="30%" higth="20px">Admin</th>
        </tr>
        <tr>
            <th width="30%">Sri Wahyuni</th>
            <th width="30%">{{$admin}}</th>
        </tr>
    </table>
 
 

</body>

</html>
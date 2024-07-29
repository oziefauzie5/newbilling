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
            font-size: 9pt;
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
            <th width="60%" style=" text-align: left;"><img src="{{ asset('atlantis/assets/img/'.$profile_perusahaan->app_logo)}}" alt=""></th>
            <td width="40%"><strong>{{$profile_perusahaan->app_nama}}</strong> <br><span>{{$profile_perusahaan->app_brand}}</span><br><span>{{$profile_perusahaan->app_alamat}}</span></td>
        </tr>
    </table>
    <br>
    <hr>
    <table id="kas">
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
    <table id="kas_rincian">
        <tr>
            <th width="5%">NO</th>
            <th>KETERANGAN</th>
            <th width="25%">NOMINAL</th>
        </tr>
        <tr>
            <td style="text-align: center;">1</td>
            <td>PSB {{$kas->input_nama}}</td>
            <td id="kas_nominal">Rp. {{number_format($kas->teknisi_psb)}}</td>
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
    <table id="kas">
        <tr>
            <td width="50%"></td>
            <td id="kas_invoice" width="15%">JUMLAH</td>
            <td>:</td>
            <td id="kas_invoice">Rp. {{number_format($kas->teknisi_psb)}}</td>
        </tr>
        <tr>
            <td width="50%"></td>
            <td id="kas_invoice" width="15%">TOTAL</td>
            <td>:</td>
            <td id="kas_invoice">Rp. {{number_format($kas->teknisi_psb)}}</td>
        </tr>
    </table>
    <table id="kas_ttd">
        <tr>
            <th width="25%" higth="20px">Admin</th>
            <th width="25%">NOC</th>
            <th width="25%">Keuangan</th>
            <th width="25%">Penerima</th>
        </tr>
        <tr>
            <th width="25%" higth="20px">{{ strtoupper($nama_admin )}}</th>
            <th width="25%">{{strtoupper($noc->name)}}</th>
            <th width="25%">...................................</th>
            <th width="25%">...................................</th>
        </tr>
    </table>
    <table id="kas_kop">
        <tr>
            <th width="60%" style=" text-align: left;"><img src="{{ asset('atlantis/assets/img/'.$profile_perusahaan->app_logo)}}" alt=""></th>
            <td width="40%"><strong>{{$profile_perusahaan->app_nama}}</strong> <br><span>{{$profile_perusahaan->app_brand}}</span><br><span>{{$profile_perusahaan->app_alamat}}</span></td>
        </tr>
    </table>
    <br>
    <hr>
    <table id="kas">
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
            <td>Tanggal Pasang</td>
            <td>:</td>
            <td>{{ date('d-m-Y', strtotime( $kas->reg_tgl_pasang)) }}</td>
        </tr>
        <tr>
            <td>Dibayarkan kepada</td>
            <td>:</td>
            <td>{{$seles->name}}</td>
        </tr>
       
    </table>
    <table id="kas_rincian">
        <tr>
            <th width="5%">NO</th>
            <th>KETERANGAN</th>
            <th width="25%">NOMINAL</th>
        </tr>
        <tr>
            <td style="text-align: center;">1</td>
            <td>PSB {{$kas->input_nama}}</td>
            <td id="kas_nominal">Rp. {{number_format($biaya_sales->biaya_sales)}}</td>
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
    <table id="kas">
        <tr>
            <td width="50%"></td>
            <td id="kas_invoice" width="15%">JUMLAH</td>
            <td>:</td>
            <td id="kas_invoice">Rp. {{number_format($biaya_sales->biaya_sales)}}</td>
        </tr>
        <tr>
            <td width="50%"></td>
            <td id="kas_invoice" width="15%">TOTAL</td>
            <td>:</td>
            <td id="kas_invoice">Rp. {{number_format($biaya_sales->biaya_sales)}}</td>
        </tr>
    </table>
    <table id="kas_ttd">
        <tr>
            <th width="25%" higth="20px">Admin</th>
            <th width="25%">NOC</th>
            <th width="25%">Keuangan</th>
            <th width="25%">Penerima</th>
        </tr>
        <tr>
            <th width="25%" higth="20px">{{ strtoupper($nama_admin )}}</th>
            <th width="25%">{{strtoupper($noc->name)}}</th>
            <th width="25%">...................................</th>
            <th width="25%">...................................</th>
        </tr>
    </table>
    <table id="kas_kop">
        <tr>
            <th width="60%"  style=" text-align: left;"><img src="{{ asset('atlantis/assets/img/'.$profile_perusahaan->app_logo)}}" alt=""></th>
            <td width="40%"><strong>{{$profile_perusahaan->app_nama}}</strong> <br><span>{{$profile_perusahaan->app_brand}}</span><br><span>{{$profile_perusahaan->app_alamat}}</span></td>
        </tr>
    </table>
    <br>
 <hr>
    <table id="customers">
        <br>
        <tr>
            <th colspan="3" style="text-align: center; font-size: 12pt;">LAPORAN KERJA TEKNISI</th>
        </tr>
            <tr>
                <td width="15%">Id Pelanggan</td>
                <td>:</td>
                <td width="85%">{{$berita_acara->reg_idpel}}</td>
            </tr>
            <tr>
                <td>No. Layanan</td>
                <td>:</td>
                <td>{{$berita_acara->reg_nolayanan}}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{$berita_acara->input_nama}}</td>
            </tr>
            <tr>
                <td>Whatsapp</td>
                <td>:</td>
                <td>{{$berita_acara->input_hp}}</td>
            </tr>
            <tr>
                <td>Paket Internet</td>
                <td>:</td>
                <td>{{$berita_acara->paket_nama}}</td>
            </tr>
            <tr>
                <td>Jenis Billing</td>
                <td>:</td>
                <td>{{$berita_acara->reg_jenis_tagihan}}</td>
            </tr>
            <tr>
                <td>Tagihan</td>
                <td>:</td>
                @if($berita_acara->reg_jenis_tagihan=='DEPOSIT')
                <td>Rp. {{number_format($berita_acara->reg_deposit)}}</td>
                @else
                <td>Rp. {{number_format( $berita_acara->reg_harga + $berita_acara->reg_dana_kas + $berita_acara->reg_dana_kerjasama + $berita_acara->reg_kode_unik  + $berita_acara->reg_ppn)}}</td>
                @endif
            </tr>
            <tr>
                <td>Sales</td>
                <td>:</td>
                <td>{{$seles->name}}</td>
            </tr>
            <tr>
                <td>Sub Sales</td>
                <td>:</td>
                <td>{{$berita_acara->input_subseles}}</td>
            </tr>
            
            <tr>
                <td>Alamat KTP</td>
                <td>:</td>
                <td>{{$berita_acara->input_alamat_ktp}}</td>
            </tr>
            <tr>
                <td>Alamat Pasang</td>
                <td>:</td>
                <td>{{$berita_acara->input_alamat_pasang}}</td>
            </tr>
    </table >
    <br>
    <hr>
    <table id="customers">
            <tr>
                <td width="15%">Tanggal Registrasi</td>
                <td>:</td>
                @if($berita_acara->input_tgl<"2020-01-01")
                <td width="85%">-</td>
                @else
                <td width="85%">{{ date('d-m-Y', strtotime( $berita_acara->input_tgl)) }}</td>
                @endif
            </tr>
            <tr>
                <td>Tanggal Pemasangan</td>
                <td>:</td>
                @if($berita_acara->reg_tgl_pasang)
                <td>{{ date('d-m-Y', strtotime( $berita_acara->reg_tgl_pasang)) }}</td>
                @else
                <td></td>
                @endif
            </tr>
    </table>
    <br>
    <hr>

    <table id="customers">
        <br>
        <tr>
        <td width="15%">Kode Pactcore</td>
        <td>:</td>
        <td id="box" >{{$berita_acara->reg_kode_pactcore}}</td>
        <td></td>
        <td width="15%">Kode Adaptor</td>
        <td>:</td>
        <td id="box">{{$berita_acara->reg_kode_adaptor}}</td>
    </tr>
    <tr>
        <td width="15%">Kode ONT</td>
        <td>:</td>
        <td id="box">{{$berita_acara->reg_kode_ont}}</td>
        <td></td>
        <td width="15%">Kode Kabel</td>
        <td>:</td>
        <td id="box">{{$berita_acara->reg_kode_dropcore}}</td>
    </tr>
    <tr>
        <td>Before</td>
        <td>:</td>
        <td id="box">{{$berita_acara->reg_before}}</td>
        <td></td>
        <td>After</td>
        <td>:</td>
        <td id="box">{{$berita_acara->reg_after}}</td>
        <td></td>
    </tr>
    <tr>
        <td>Losis</td>
        <td>:</td>
        <td id="box">{{$berita_acara->reg_los_opm}} dbm</td>
        <td></td>
        <td>Kabel</td>
        <td>:</td>
        <td id="box">{{$berita_acara->reg_penggunaan_dropcore}} Meter</td>
        <td></td>
    </tr>
        <tr>
        <td>Status Perangkat</td>
        <td>:</td>
        <td>Dipinjamkan</td>
        <td></td>
        <td>Mac Address</td>
        <td>:</td>
        <td id="box">{{$berita_acara->reg_mac}}</td>
        </tr>
    </table>
    <br>
    <hr>
    <table id="customers">
            <tr>
                <td width="15%">Jeni Layanan</td>
                <td>:</td>
                <td width="85%">{{$berita_acara->reg_layanan}}</td>
            </tr>
            <tr>
                <td width="15%">Username</td>
                <td>:</td>
                <td width="85%">{{$berita_acara->reg_username}}</td>
            </tr>
            <tr>
                <td>Password</td>
                <td>:</td>
                <td>{{$berita_acara->reg_password}}</td>
            </tr>
    </table>
    <br><hr><br>
    <div> Hallo {{strtoupper($berita_acara->reg_teknisi_team)}}. Terimakasih telah menyelesaikan tugas anda. Anda mulai mengerjakan Pukul {{ date('H:m:s', strtotime( $kas->mulai)) }} dan selesai Pukul {{ date('H:m:s', strtotime( $kas->teknisi_job_selesai)) }}, dengan waktu {{ $kas->teknisi_waktu_kerja }}</div><br>

    <div>Nilai untuk anda : {{$kas->teknisi_nilai}}</div>
    <div>Pesan untuk anda : {{$kas->teknisi_note}}</div>
 
 

</body>

</html>
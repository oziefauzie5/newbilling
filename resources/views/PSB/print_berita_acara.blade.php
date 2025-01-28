
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
          /* border-collapse: collapse; */
          width: 100%;
        }
        #customers td, #customers th {
            /* border: 1px solid #5b5b5b; */
            padding: 3px;
            font-size: 9pt;
            text-align: left;
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
          height: 100px;

        }
        /* #customers .rupiah{
            text-align: left;
        } */
        #sk {
            font-size: 10pt;
            color:red;
        }

        /* #customers th {
          text-align: left;
          font-size: 10pt;

        } */
        /* #invoice {
        float: right;
        text-align: right;
        }
        #client {
        float: left;
        } */
        hr {
        margin-top: -10px;
        margin-bottom: 1px
        }

        </style>
</head>
<body>

    <table id="kop">
        <tr>
            <th width="60%"  style=" text-align: left;"><img src="{{ asset('storage/profile_perusahaan/'.$profile_perusahaan->app_logo) }}" alt=""></th>
            <td width="40%"><strong>{{$profile_perusahaan->app_nama}}</strong> <br><span>{{$profile_perusahaan->app_brand}}</span><br><span>{{$profile_perusahaan->app_alamat}}</span></td>
        </tr>
    </table>
    <br>
 <hr>
 <br>
 <table id="customers">
    <tr >
        <th  style="text-align: center; font-size: 12pt;">BERITA ACARA INSTALASI LAYANAN INTERNET</th>
        <td></td>
    </tr>
</table>
<br>
<hr>
 <table id="customers">
    <tr >
        <th width="40%" style="text-align:left; font-size: 11pt; background-color: #0071bc"> Data Pelanggan - Custumer Data</th>
        <td></td>
    </tr>
</table>
    <table id="customers">
            <tr>
                <td width="15%">Id Pelanggan</td>
                <td>:</td>
                <td colspan="3">{{$berita_acara->reg_idpel}}</td>
                <td >No. Layanan</td>
                <td>:</td>
                <td>{{$berita_acara->reg_nolayanan}}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td colspan="5">{{$berita_acara->input_nama}}</td>
            </tr>
            <tr>
                <td>No. KTP</td>
                <td>:</td>
                <td colspan="5">{{$berita_acara->input_ktp}}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td id="sk" colspan="5">....................................................................................................... *Diisi oleh teknisi</td>
            </tr>
            <tr>
                <td>Nomor untuk dihubungi</td>
                <td>:</td>
                <td width="10%">No. HP Utama</td>
                <td>:</td>
                <td>0{{$berita_acara->input_hp}}</td>
                <td width="10%">No. HP Alternatif</td>
                <td>:</td>
                <td>0{{$berita_acara->input_hp2}}</td>
            </tr>
            <tr>
                <td>Sales</td>
                <td>:</td>
                <td colspan="3">{{$seles}}</td>
                <td>Sub Sales</td>
                <td>:</td>
                <td>{{$berita_acara->input_subseles}}</td>
            </tr>
            
            <tr>
                <td>Alamat KTP</td>
                <td>:</td>
                <td colspan="5">{{$berita_acara->input_alamat_ktp}}</td>
            </tr>
            <tr>
                <td>Alamat Pasang</td>
                <td>:</td>
                <td colspan="5">{{$berita_acara->input_alamat_pasang}}</td>
            </tr>
    </table >
    <br>
    <hr>
    <table id="customers">
        <tr >
            <th width="40%" style="text-align:left; font-size: 11pt; background-color: #0071bc"> Layanan - Service</th>
            <td></td>
        </tr>
    </table>
    <table id="customers">
            <tr>
                <td width="15%">Paket Internet</td>
                <td>:</td>
                <td width="85%"> {{$berita_acara->paket_nama}}</td>
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
            
    </table >
    <br>
    <hr>
    <table id="customers">
        <tr >
            <th width="40%" style="text-align:left; font-size: 11pt; background-color: #0071bc"> Data Perangkat - Device Data</th>
            <td></td>
        </tr>
    </table>
    <table id="customers">
        <tr>
        <td width="15%">Serial Number</td>
        <td>:</td>
        <td colspan="7"  >{{$berita_acara->reg_sn}}</td>
        <td width="15%">Mac Address</td>
        <td>:</td>
        <td >{{$berita_acara->reg_mac}}</td>
        </tr>
        <tr>
            <td width="15%">Merek dan Tipe</td>
            <td>:</td>
            <td colspan="8">{{$berita_acara->reg_mrek}}</td>
        </tr>
        <tr>
            <td>Kebutuh Kabel</td>
            <td>:</td>
            <td width="15%">Before</td>
            <td>:</td>
            <td colspan="5" id="sk">...............................</td>
            <td width="15%">After</td>
            <td>:</td>
            <td id="sk">...............................</td>
        </tr>
        <tr>
            <td>Kelengkapan lain</td>
            <td>:</td>
            <td width="5%">1</td>
            <td>:</td>
            <td colspan="" id="sk">...............................</td>
            <td width="5%">2</td>
            <td>:</td>
            <td colspan="2" id="sk">...............................</td>
            <td width="5%">3</td>
            <td>:</td>
            <td id="sk">...............................</td>
        </tr>
    </table>
    <br>
    <hr>
    <table id="customers">
        <tr>
            <td width="15%">Tanggal Registrasi</td>
            <td>:</td>
            @if($berita_acara->input_tgl<"2020-01-01")
            <td colspan="4">-</td>
            @else
            <td colspan="4">{{ date('d-m-Y', strtotime( $berita_acara->input_tgl)) }}</td>
            @endif
            <td width="20%">Tanggal Pemasangan</td>
            <td>:</td>
            @if($berita_acara->reg_tgl_pasang)
            <td >{{ date('d-m-Y', strtotime( $berita_acara->reg_tgl_pasang)) }}</td>
            @else
            <td></td>
            @endif
        </tr>
</table>
<br>
<hr>
    <table id="customers">
        <tr >
            <th width="40%" style="text-align:left; font-size: 11pt; background-color: #0071bc"> SYARAT DAN KETENTUAN</th>
            <td></td>
        </tr>
    </table>
    <span style="font-size:12px ">KEWAJIBAN PELANGGAN</span>
    <ul id="sk">
        <li>Berlangganan minimal 6 Bulan</li>
        <li>Mengembalikan Perangkat ONT ( Modem ) saat berhenti berlangganan</li>
        <li>Pelanggan akan dikenakan Denda Pengakhiran sebesar Rp 500.000,- sesuai dengan ketentuan dalam Syarat dan Ketentuan, apabila memutuskan untuk berhenti berlangganan Layanan Ovall Fiber sebelum 6 bulan.</li>
        <li>Kontrak Berlangganan ini digunakan sebagai dasar untuk menjaga pelayanan dan komitmen antara pelanggan dengan Ovall Fiber.</li>
    </ul>
    <table id="ttd">
        <tr>
            <th width="25%" >Admin</th>
            <th width="25%">Sales</th>
            <th width="25%">Teknisi</th>
            <th width="25%">Pelanggan</th>
        </tr>
        <tr>
            <th width="25%" >{{ $nama_admin }}</th>
            <th width="25%">{{$berita_acara->input_subseles}}</th>
            <th width="25%">...................................</th>
            <th width="25%">{{$berita_acara->input_nama}}</th>
        </tr>
    </table>
    
</body>
</html>

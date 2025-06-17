<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>

        #berita_acara {
          font-family: Arial, Helvetica, sans-serif;
          /* border-collapse: collapse; */
          width: 100%;
        }
        #berita_acara td, #berita_acara th {
            /* border: 1px solid #5b5b5b; */
            padding: 3px;
            font-size: 9pt;
            text-align: left;
        }





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
            <th width="60%" style=" text-align: left;"><img src="{{ asset('storage/profile_perusahaan/'.$profile_perusahaan->app_logo)}}" width="40%" alt=""></th>
            <td width="40%"><strong>{{$profile_perusahaan->app_nama}}</strong> <br><span>{{$profile_perusahaan->app_brand}}</span><br><span>{{$profile_perusahaan->app_alamat}}</span></td>
        </tr>
    </table>
    <br>
 <hr>
 <br>
<table id="berita_acara">
    <tr >
        <th width="40%" style="text-align:left; font-size: 11pt; background-color: #0071bc"> Data Pelanggan - Custumer Data</th>
        <td></td>
    </tr>
</table>
    <table id="berita_acara">
            <tr>
                <td width="15%">Id Pelanggan</td>
                <td>:</td>
                <td width="85%" >{{$berita_acara->reg_idpel}}</td>
                
            </tr>
            <tr>
                <td >No. Layanan</td>
                <td>:</td>
                <td>{{$berita_acara->reg_nolayanan}}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td >{{$berita_acara->input_nama}}</td>
            </tr>
            <tr>
                <td>No. KTP</td>
                <td>:</td>
                <td >{{$berita_acara->input_ktp}}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td id="" >{{$berita_acara->input_email}}</td>
            </tr>
            <tr>
                <td>No. HP Utama</td>
                <td>:</td>
                <td>0{{$berita_acara->input_hp}}</td>
            </tr>
            <tr>
                <td>No. HP Alternatif</td>
                <td>:</td>
                <td>0{{$berita_acara->input_hp2}}</td>
            </tr>
            <tr>
                <td>Sales</td>
                <td>:</td>
                <td >{{$seles->name ?? '-'}}</td>
              
            </tr>
            <tr>
                <td>Sub Sales</td>
                <td>:</td>
                <td>{{$berita_acara->input_subseles ?? '-'}}</td>
            </tr>
            <tr>
                <td>Alamat KTP</td>
                <td>:</td>
                <td colspan="6">{{$berita_acara->input_alamat_ktp}}</td>
            </tr>
            <tr>
                <td>Alamat Pasang</td>
                <td>:</td>
                <td colspan="6">{{$berita_acara->input_alamat_pasang}}</td>
            </tr>
    </table >
    <br>
    <hr>
    <table id="berita_acara">
        <tr >
            <th width="40%" style="text-align:left; font-size: 11pt; background-color: #0071bc"> Layanan - Service</th>
            <td></td>
        </tr>
    </table>
    <table id="berita_acara">
        <tr>
            <td width="15%">Layanan</td>
            <td>:</td>
            <td width="85%">{{$berita_acara->reg_layanan}}</td>
           
            </tr>
            <tr>
                <td >Jenis Billing</td>
                <td>:</td>
                <td >{{$berita_acara->reg_jenis_tagihan}}</td>
            </tr>
            <tr>
                <td>Paket Internet</td>
                <td>:</td>
                <td>{{$berita_acara->paket_nama}}</td>
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
                <td>PPN</td>
                <td>:</td>
                <td >{{number_format($berita_acara->reg_ppn)}}</td>
            </tr>
              
            <tr>
                <td>Tanggal Pasang</td>
                <td>:</td>
                <td >{{date('d-m-Y', strtotime($berita_acara->reg_tgl_pasang))}}</td>
            </tr>
            <tr>
                <td>Jatuh Tempo</td>
                <td>:</td>
                <td >{{date('d-m-Y', strtotime($berita_acara->reg_tgl_jatuh_tempo))}}</td>
            </tr>
            
    </table >
    <br>
    <hr>
    <table id="berita_acara">
        <tr >
            <th width="40%" style="text-align:left; font-size: 11pt; background-color: #0071bc"> Data Instalasi - Instalation Data</th>
            <td></td>
        </tr>
    </table>
    <table id="berita_acara">
        <tr>
            <td>NOC</td>
            <td>:</td>
            <td>{{strtoupper($berita_acara->noc)}}</td>
        </tr>
        <tr>
            <td>Teknisi Team</td>
            <td>:</td>
            <td>{{strtoupper($berita_acara->teknisi_team)}}</td>
        </tr>
        
        <tr>
            <td width="15%">Site</td>
            <td>:</td>
            <td width="85%">{{strtoupper($berita_acara->site_nama)}}</td>
        </tr>
        <tr>
            <td width="15%">POP</td>
            <td>:</td>
            <td width="85%">{{strtoupper($berita_acara->pop_nama)}}</td>
        </tr>
            <tr>
                <td>OLT</td>
            <td>:</td>
            <td>{{strtoupper($berita_acara->olt_nama)}}</td>
            </tr>
            <tr>
                <td>Router</td>
                <td>:</td>
                <td>{{strtoupper($berita_acara->router_nama)}}</td>
            </tr>
            <tr>
            <td>ODC</td>
            <td>:</td>
            <td>{{strtoupper($berita_acara->odc_nama)}}</td>
            </tr>
        <tr>
            <tr>
                <td>ODP</td>
                <td>:</td>
                <td>{{$berita_acara->odp_id}}</td>
            </tr>
            <tr>
                <td>Slot ODP</td>
                <td>:</td>
                <td>{{$berita_acara->reg_slot_odp}}</td>
            </tr>
        </tr>
        <tr>
            <td>Koordinat Rumah</td>
            <td>:</td>
            <td>{{$berita_acara->input_koordinat}}</td>
            </tr>
    </table>
        <br>
    <hr>
    <table id="berita_acara">
        <tr >
            <th width="40%" style="text-align:left; font-size: 11pt; background-color: #0071bc"> Data Perangkat - Device Data</th>
            <td></td>
        </tr>
    </table>
    <br>
    <table id="kas_rincian">
        <tr>
                <th>#</th>
                <th>Id Barang</th>
                <th>Kategori</th>
                <th>Nama Barang</th>
                <th>Mac ONT</th>
                <th>Mac OLT</th>
                <th>Qty</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Jumlah</th>
        </tr>
        @foreach($print_skb as $skb )
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ $skb->barang_id }}</td>
            <td >{{ $skb->barang_kategori }}</td>
            <td>{{ strtoupper($skb->barang_nama) .'  '. strtoupper($skb->barang_merek) .' | '.strtoupper($skb->barang_sn)}}</td>
            <td style="text-align: center">{{ strtoupper($skb->barang_mac_olt)}}</td>
            <td style="text-align: center">{{ strtoupper($skb->barang_mac)}}</td>
            <td style="text-align: center">{{ $skb->bk_jumlah }}</td>
            <td style="text-align: center">{{ $skb->barang_satuan }}</td>
            <td style="text-align: right">{{ number_format($skb->barang_harga_satuan) }}</td>
            <td style="text-align: right">{{ number_format($skb->bk_harga) }}</td>
            @endforeach
        </tr>
        <tr>
        <td colspan="9" style="text-align: right">Total</td>
        <td colspan="1" style="text-align: right">{{ number_format($total) }}</td>
        </tr>
    </table>
     <br>
    <hr>
    <table id="berita_acara">
        <tr >
            <th width="40%" style="text-align:left; font-size: 11pt; background-color: #0071bc"> Data Mitra - Mitra Data</th>
            <td></td>
        </tr>
    </table>
    
    <br>
    <table id="kas_rincian">
        <tr>
                <th>#</th>
                <th>SALES / PIC</th>
                <th>FEE</th>
        </tr>
        @foreach($mitra as $mit )
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ $mit->name ?? '-'}}</td>
            <td style="text-align: right">{{ number_format($mit->reg_fee ?? 0) }}</td>
            @endforeach
        </tr>
    </table>
    <br>

    <table>
        <tr>
            <td ><img src="{{ asset('storage/laporan-kerja/'.$berita_acara->reg_img) }}"  height="100%" alt="" title=""></img></td>
            @if($berita_acara->reg_foto_odp)
            <td ><img src="{{ asset('storage/laporan-kerja/'.$berita_acara->reg_foto_odp) }}"  height="100%" alt="" title=""></img></td>
            @endif
            
        </tr>
    </table>
 
 

</body>

</html>
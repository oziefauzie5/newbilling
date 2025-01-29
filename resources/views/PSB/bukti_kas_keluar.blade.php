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
            <th width="60%" style=" text-align: left;"><img src="{{ asset('storage/profile_perusahaan/'.$profile_perusahaan->app_logo)}}" width="20%" alt=""></th>
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
                <td >{{$seles->name}}</td>
              
            </tr>
            <tr>
                <td>Sub Sales</td>
                <td>:</td>
                <td>{{$berita_acara->input_subseles}}</td>
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
            <th width="40%" style="text-align:left; font-size: 11pt; background-color: #0071bc"> Data Perangkat - Device Data</th>
            <td></td>
        </tr>
    </table>
    <table id="berita_acara">
        <tr>
        <td width="15%">Serial Number</td>
        <td>:</td>
        <td width="85%">{{$berita_acara->reg_sn}}</td>
        </tr>
        <tr>
        <td>Mac Address</td>
        <td>:</td>
        <td >{{$berita_acara->reg_mac}}</td>
        </tr>
        <tr>
        <td>Merek dan Tipe</td>
        <td>:</td>
        <td>{{$berita_acara->reg_mrek}}</td>
        </tr>
        <tr>
        <td>Mac Address OLT</td>
        <td>:</td>
        <td >{{$berita_acara->reg_mac_olt}}</td>
        </tr>
       
    </table>
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
            <td width="15%">POP</td>
            <td>:</td>
            <td width="85%">{{$berita_acara->reg_pop}}</td>
        </tr>
            <tr>
                <td>OLT</td>
            <td>:</td>
            <td>{{$berita_acara->reg_olt}}</td>
            </tr>
            <tr>
                <td>Router</td>
                <td>:</td>
                <td>{{$berita_acara->reg_router}}</td>
            </tr>
            <tr>
            <td>ODC</td>
            <td>:</td>
            <td>{{$berita_acara->reg_odc}}</td>
            </tr>
        <tr>
            <tr>
                <td>ODP</td>
                <td>:</td>
                <td>{{$berita_acara->reg_odp}}</td>
            </tr>
            <tr>
                <td>Slot ODP</td>
                <td>:</td>
                <td>{{$berita_acara->reg_slot_odp}}</td>
            </tr>
        </tr>
        <tr>
            <td>Onu Id</td>
            <td>:</td>
            <td>{{$berita_acara->reg_onuid}}</td>
            
        </tr>
        <tr>
            <td>Teknisi Team</td>
            <td>:</td>
            <td>{{$kas->teknisi_team}}</td>
        </tr>
        <tr>
            <td>Koordinat Rumah</td>
            <td>:</td>
            <td>{{$berita_acara->input_koordinat}}</td>
            
            </tr>
            <tr>
            <td>Koordinat ODP</td>
            <td>:</td>
            <td>{{$berita_acara->reg_koodinat_odp}}</td>
            </tr>
    </table>
    <br>

    <table id="kas_kop">
        <tr>
            <th width="60%" style=" text-align: left;"><img src="{{ asset('storage/profile_perusahaan/'.$profile_perusahaan->app_logo)}}" width="20%" alt=""></th>
            <td width="40%"><strong>{{$profile_perusahaan->app_nama}}</strong> <br><span>{{$profile_perusahaan->app_brand}}</span><br><span>{{$profile_perusahaan->app_alamat}}</span></td>
        </tr>
    </table>
    <br>
    <hr>
    <table id="customers">
        <tr >
            <th  style="text-align: center; font-size: 12pt;">BUKTI PENGELUARAN KAS</th>
            <td></td>
        </tr>
    </table>
    <table id="kas">

        <tr>
            <td width="15%">No</td>
            <td>:</td>
            <td width="40%">................</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ date('d-m-Y', strtotime( $kas->reg_tgl_pasang)) }}</td>
            <td>Dibayarkan kepada</td>
            <td>:</td>
            <td>{{$kas->teknisi_team}}</td>
        </tr>
        <tr>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td>:</td>
            <td>{{$kas->input_nama}}</td>
            <td>No. Layanan</td>
            <td>:</td>
            <td>{{$kas->reg_nolayanan}}</td>
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
    </table>
    <table id="kas">
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
            <th width="25%">{{$noc->name}}</th>
            <th width="25%">...................................</th>
            <th width="25%">...................................</th>
        </tr>
    </table>
    <hr>
    <br>
    <table id="kas_kop">
        <tr>
            <th width="60%" style=" text-align: left;"><img src="{{ asset('storage/profile_perusahaan/'.$profile_perusahaan->app_logo)}}" width="20%" alt=""></th>
            <td width="40%"><strong>{{$profile_perusahaan->app_nama}}</strong> <br><span>{{$profile_perusahaan->app_brand}}</span><br><span>{{$profile_perusahaan->app_alamat}}</span></td>
        </tr>
    </table>
    <br>
    <hr>
    <table id="customers">
        <tr >
            <th  style="text-align: center; font-size: 12pt;">BUKTI PENGELUARAN KAS</th>
            <td></td>
        </tr>
    </table>
    <table id="kas">

        <tr>
            <td width="15%">No</td>
            <td>:</td>
            <td width="40%">................</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ date('d-m-Y', strtotime( $kas->reg_tgl_pasang)) }}</td>
            <td>Dibayarkan kepada</td>
            <td>:</td>
            <td>{{$seles->name}}</td>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td>:</td>
            <td>{{$kas->input_nama}}</td>
            <td>No. Layanan</td>
            <td>:</td>
            <td>{{$kas->reg_nolayanan}}</td>
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
    </table>
    <table id="kas">
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
            <th width="25%">{{$noc->name}}</th>
            <th width="25%">...................................</th>
            <th width="25%">...................................</th>
        </tr>
    </table>
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
            <td width="40%">{{$data->bk_id}}</td>
        </tr>
        <tr>
            <td>Tanggal Cetak</td>
            <td>:</td>
            <td>{{date('d-M-Y h:m')}}</td>
            <td>Tanggal Barang Keluar</td>
            <td>:</td>
            <td>{{date('d-M-Y', strtotime($data->bk_waktu_keluar))}}</td>
                </tr>
                <tr>
                    <td>Jenis Laporan</td>
                    <td>:</td>
                    <td>{{$data->bk_jenis_laporan}}</td>
                    <td>Keterangan</td>
                    <td>:</td>
                    <td>{{$data->bk_keperluan}}</td>
                </tr>

       
    </table>
    <table id="kas_rincian">
        <tr>
                <th>#</th>
                <th>Id Barang</th>
                <th>Kategori</th>
                <th>Nama Barang</th>
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
            <td>{{ ucfirst($skb->barang_nama) .'  '. ucfirst($skb->barang_merek) .'  '.strtolower($skb->barang_sn) .'  '. strtolower($skb->barang_mac)}}</td>
            <td style="text-align: center">{{ $skb->bk_jumlah }}</td>
            <td style="text-align: center">{{ $skb->barang_satuan }}</td>
            <td style="text-align: right">{{ number_format($skb->barang_harga_satuan) }}</td>
            <td style="text-align: right">{{ number_format($skb->bk_harga) }}</td>
            @endforeach
        </tr>
        <tr>
        <td colspan="7" style="text-align: right">Total</td>
        <td colspan="1" style="text-align: right">{{ number_format($total) }}</td>
        </tr>
    </table>
    <br>
    <table id="kas_ttd">
        <tr>
            <th width="50%" higth="20px">Admin</th>
            <th width="50%">Penerima Barang</th>
        </tr>
        <tr>
            <th width="50%" higth="20px">{{ strtoupper($nama_admin )}}</th>
            <th width="50%">( ........................................... )</th>
        </tr>
    </table>

    <table>
        <tr>
            <td ><img src="{{ asset('storage/rumah_pelanggan/'.$kas->reg_img) }}"  height="420" alt="" title=""></img></td>
            @if($kas->reg_foto_odp)
            <td ><img src="{{ asset('storage/odp_pelanggan/'.$kas->reg_foto_odp) }}"  height="420" alt="" title=""></img></td>
            @endif
            
        </tr>
    </table>
 
 

</body>

</html>
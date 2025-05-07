
<!DOCTYPE html>
<!-- saved from url=(0041)https://fisnet.rlradius.com/voucher/print -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>PRINT VOUCHER</title>

<meta http-equiv="pragma" content="no-cache">
<script src="./PRINT VOUCHER_files/qrious.min.js.download"></script>

<style>
body {
  color: #000000;
  background-color: #FFFFFF;
  font-size: 11px;
  font-family:  "Helvetica", arial, sans-serif;
  margin: 0px;
  -webkit-print-color-adjust: exact;
}


@page{
  size: auto;
  margin-left: 7mm;
  margin-right: 5mm;
  margin-top: 5mm;
  margin-bottom: 2mm;
}

@media print{
  table { 
      page-break-after:auto; 
    }
  tr    { 
      page-break-inside:avoid; 
      page-break-after:auto;
    }
  td { 
      page-break-inside:avoid; 
      page-break-after:auto;
    }

  thead { 
      display:table-header-group; 
    }

  tfoot { 
      display:table-footer-group; 
    }
}

</style>
</head>
<body onload="window.print()" oncontextmenu="return false;">   

@foreach($data_voucher as $dv)
<table style="display:inline-block; border:1px solid #666; margin:2px; width:180px; overflow:hidden; position:relative; padding:2px; margin:1px; border:1px solid {{$dv->paket_warna}};">
 
 
 <thead>
 <tr style="border:1px solid #3700ff;">
 <th valign="left" style="width:60%">
 <img style="float:left; margin:2px 0 0 5px;" height="18" src="{{ asset('storage/hotspot/FISNET.png') }}">
 </th> 
 
 <th valign="center" style="width:40%">
 <div style="float:right;margin-top:-6px;margin-right:0px;width:5%;text-align:right;font-size:7px;">
 </div>
 <div style="text-align:right;font-weight:bold;font-family:Tahoma;font-size:15px;padding-left:17px;color:{{$dv->paket_warna}}">{{number_format($dv->vhc_hjk)}}</div> 
 </th> 
 </tr>
 </thead>
 
 
 
 <tbody>
 <tr>
 <td valign="top" style="border-collapse:collapse; color:#000;">
 <div style="margin-top:2px;margin-bottom:2px;">
 <div style="font-weight:bold;font-size:16px;color:{{$dv->paket_warna}};">{{$dv->vhc_username}}</div>
 
 </div>
 
 <div valign="top" style="text-align:left;font-size:8px;margin:0px;padding-left:1px; color:#000;">
 <b>{{$dv->paket_nama}}</b><br> 
 Mitra : {{$dv->name}}<br>
 Tgl cetak : {{date('d-m-Y', strtotime($dv->vhc_tgl_cetak))}}<br>
 No : <span>{{ $loop->iteration }}</span>
 </div>
 </td> 
 
 <td rowspan="2" valign="center">
 <div style="padding:1px;text-align:right;margin:0;">
 <canvas id="{{$dv->vhc_username}}" height="68" width="68"> </canvas> 
 </div>
 </td> 
 </tr>
 
 
 
 <tr> 
 <td colspan="1" valign="center" style="background:{{$dv->paket_warna}};padding:0px;width:100%">
 <div style="text-align:left;color:#FFF;font-size:9px;font-weight:bold;margin:0px;padding:2.5px;"><b>{{$dv->outlet_nama}}</b></div>
 </td>
 </tr>
 
 </tbody>
 </table>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.js"></script>
 <script>
     (function() {
         let qr = new QRious({
             element:document.getElementById("{{$dv->vhc_username}}"),
 size:68,
 level:"L",
 value:"http://fis.id/login?username={{$dv->vhc_username}}&password={{$dv->vhc_username}}"
});
})();
</script>
@endforeach

<!-- <canvas id="qr"></canvas>
  
  <script>
    (function() {
      var qr = new QRious({
        element: document.getElementById('qr'),
        value: document.getElementById('theLink').innerText
      });
    })();
  </script> -->
 
</body></html>
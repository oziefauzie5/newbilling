
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
       table, td, th {
  border: 1px solid;
}

table {
  width: 100%;
  border-collapse: collapse;
  font-size: 40px;
}

        </style>
</head>
<body>

    <table>
        @foreach($kode_barang as $kode)
        <tr>
            <th>{{$kode->id_subbarang}}</th>
        </tr>
        @endforeach
    </table>
    
</body>
</html>

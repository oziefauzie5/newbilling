<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
      
        #customers {
          font-family: Arial;
          /* border-collapse: collapse; */
          width: 100%;
          font-size: 12px;
        }
        .container {
  /* margin-top: 20vh; */
  display: grid;
  grid-template-columns: auto auto auto auto auto auto ;
  grid-gap: 1px;
  /* background-color: coral; */
  /* padding: 12px; */
}

.container > div {
  /* background-color: skyblue; */
  display: flex;
  /* align-items: center; */
  justify-content: center;
  text-align: center;
  font-size: 25px;
  font-weight: bold;
  padding: 0.5em;
  border: 1px solid black;
}

    </style>
</head>

<body>
<!-- <table id="customers">
            <tr>
                <td width="15%">Kategori</td>
                <td>:</td>
                <td width="30%">{{$kode_group->barang_kategori}}</td>
                <td width="15%">ID Group</td>
                <td>:</td>
                <td width="30%">{{$kode_group->barang_id_group}}</td>
            </tr>
            <tr>
    </table>
    <br> -->

    <div class="container">
    @foreach($data_kode_group as $t )

        <div>{{$t->barang_id}}</div>
    @endforeach

    </div>
 
 

</body>

</html>
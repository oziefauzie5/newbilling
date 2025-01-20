@extends('layout.user')
@section('content')

<div class="content">
   
    <div class="page-inner mt-3">


      <section class="content">
        <div class="card ">
 
        <form action="{{ route('admin.teknisi.proses_aktivasi',['id'=> $data_aktivasi->reg_idpel]) }}" method="POST" enctype="multipart/form-data">
         @csrf
         @method('PUT')

         
         <div class="form-row m-1">
           <div class="col">
             <label for="inputCity">Penggunaan Kabel</label>
             <input class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal_cari" alt="" value="Cari Kode Kabel"></input>
           </div>
         </div>
         <div id="di"></div>
         <div class="form-row m-1">
           <div class="col">
             <label for="inputCity">Sebelum</label>
             <input type="number" class="form-control" placeholder="Before" id="before" name="before" value="{{Session::get('before')}}" required readonly>
           </div>
           <div class="col">
             <label for="inputCity">Sesudah</label>
             <input type="number" class="form-control" placeholder="After" id="after" name="after" value="{{Session::get('after')}}" required>
           </div>
           <div class="col">
             <label for="inputCity">Jumlah</label>
             <input type="number" class="form-control" placeholder="Meter" id="total" name="total" value="{{Session::get('total')}}" required readonly>
           </div>
          </div>
          <hr>
          {{-- <div class="form-row m-1">
           <div class="col">
                <label for="inputCity">Lokasi ODP</label>
                <input type="text" class="form-control" name="fat" value="{{Session::get('fat')}}" >
                <span style="font-size: 11px; color:red">Masukan link Share lokasi FAT pada kolom FAT. </span>
              </div>
            </div> --}}
         <div class="form-row m-1">
           <div class="col-4">
                <label for="inputCity">Output ODP</label>
                <input type="number" class="form-control" step="0.01"  placeholder="OPM" id="reg_out_odp" name="reg_out_odp" value="{{Session::get('reg_out_odp')}}" required maxlength="6" minlength="6">
              </div>
              <div class="col-4">
                <label for="inputCity">Input ONT</label>
                <input type="number" class="form-control" step="0.01"  placeholder="OPM" id="reg_in_ont" name="reg_in_ont" value="{{Session::get('reg_in_ont')}}" required maxlength="6" minlength="6">
              </div>
              <div class="col-4">
                <label for="inputCity">Hasil</label>
                <input type="number" class="form-control" step="0.01"  placeholder="Los" name="los_opm" id="los_opm" value="{{Session::get('los_opm')}}" readonly required maxlength="6" minlength="6"> 
              </div>
              </div>

              {{-- <div class="form-group">
                <label >Upload Foto Rumah ( Tampak Depan )</label>
                <input type="file" class="form-control-file" name="file" disabled>
                <span class="text-danger" style="font-size: 12px">Fitur ini dalam pengembangan. Sementara kirim NOC untuk di upload, agar bisa pencairan PSB</span>
              </div> --}}
             
 
              
              <div class="modal fade" id="modal_cari" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Pencarian Kode</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div id="notif"></div>
                    <div class="form-row m-1">

                      <div class="col-12">
                        <input type="text" class="form-control" name="kode" id="kode" value="{{Session::get('kode')}}" placeholder="Masukan Kode Kabel" required>
                      </div>
                      <div class="col-12 mt-1">
                        <input class="btn btn-primary btn-block cari"  value="Cari Kode">
                      </div>

                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            <ul> Kirim Foto kepada NOC
              <li>Foto ODP ( Tampak Spliter beserta pactcore saat dicolok dan beri catatan Slot yang digunakan pada Time Mark )</li> 
              <li >Foto Rumah Tampak Depan</li>
          </ul>
 
            <div class="form-row m-1">
              <div class="col">
                <a href="https://wa.me/6285210845012?text=Bang%20ini%20foto%20rumah%20si%20'.$data_aktivasi->input_nama.'%20ya."><button type="button" class="btn btn-success btn-block mb-2">Kirim Photo</button></a>
                <button type="submit" class="btn btn-primary btn-block">Proses Aktivasi</button>
                <button type="button" class="btn btn-primary btn-block">Kembali</button>
              </div>
            </div>
            
         
        </form>
 
      </div>
      
     </section>
     
     
            
    </div>
  </div>
  <script>
    

    var reg_out_odp = document.getElementById('reg_out_odp');
    var reg_in_ont = document.getElementById('reg_in_ont');
        reg_out_odp.addEventListener('keyup', function(e)
        {
            reg_out_odp.value = format_opm(this.value, '-');
        });
        reg_in_ont.addEventListener('keyup', function(e)
        {
          reg_in_ont.value = format_opm(this.value, '-');
        });
        
        function format_opm(angka, prefix)
        {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split    = number_string.split(','),
                sisa     = split[0].length % 2,
                rupiah     = split[0].substr(0, sisa),
                ribuan     = split[0].substr(sisa).match(/\d{2}/gi);
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            return prefix == undefined ? rupiah : (rupiah ? '-' + rupiah : '');
        }
    
    
        
    
    
   
      </script>
@endsection
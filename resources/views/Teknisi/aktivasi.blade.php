@extends('layout.user')
@section('content')

<div class="content">
   
    <div class="page-inner mt-3">


      <section class="content">
        <div class="card ">
 
        <form action="{{ route('admin.teknisi.proses_aktivasi',['id'=> $data_aktivasi->reg_idpel]) }}" method="POST">
         @csrf
         @method('PUT')

         
         
         {{-- <div class="form-row m-1">
           <div class="col">
             <label for="inputCity">Mac Address</label>
             <input type="text" class="form-control" name="mac" value="{{Session::get('mac')}}" required >
           </div>
         </div>
         <hr> --}}
         <div class="form-row m-1">
           <div class="col">
             <label for="inputCity">Penggunaan Kabel</label>
             <input class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal_cari" alt="" value="Cari Kode Kabel"></input>
           </div>
         </div>
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
          <div class="form-row m-1">
           <div class="col">
                <label for="inputCity">Lokasi ODP</label>
                <input type="text" class="form-control" name="fat" value="{{Session::get('fat')}}" >
                <span style="font-size: 11px; color:red">Masukan link Share lokasi FAT pada kolom FAT. </span>
              </div>
            </div>
         <div class="form-row m-1">
           <div class="col-4">
                <label for="inputCity">ODP</label>
                <input type="text" class="form-control" step="0.01"  placeholder="OPM" id="fat_opm" name="fat_opm" value="{{Session::get('fat_opm')}}" required maxlength="6" minlength="6">
              </div>
              <div class="col-4">
                <label for="inputCity">Home</label>
                <input type="text" class="form-control" step="0.01"  placeholder="OPM" id="home_opm" name="home_opm" value="{{Session::get('home_opm')}}" required maxlength="6" minlength="6">
              </div>
              <div class="col-4">
                <label for="inputCity">Hasil</label>
                <input type="text" class="form-control" step="0.01"  placeholder="Los" name="los_opm" id="los_opm" value="{{Session::get('los_opm')}}" readonly required maxlength="6" minlength="6"> 
              </div>
              </div>
 
              
              <div class="modal fade" id="modal_cari" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Pencarian Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div id="notif"></div>
                    <div class="form-row m-1">
                      <div class="col-6">
                        <input type="text" class="form-control" name="kode" id="kode" value="{{Session::get('kode')}}" placeholder="Masukan Kode Kabel" >
                      </div>
                      <div class="col-4">
                        <input class="btn btn-primary cari"  value="Cari Kode">
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
 
 
            <div class="form-row m-1">
              <div class="col">
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
    

    var fat_opm = document.getElementById('fat_opm');
    var home_opm = document.getElementById('home_opm');
        fat_opm.addEventListener('keyup', function(e)
        {
            fat_opm.value = format_opm(this.value, '-');
        });
        home_opm.addEventListener('keyup', function(e)
        {
          home_opm.value = format_opm(this.value, '-');
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
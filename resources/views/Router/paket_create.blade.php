@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
    <div class="col-md-12">
      <div class="card card-dark">
        <div class="card-header bg-primary">
            <div class="d-flex align-items-center">
              <h4 class="card-title text-bold">PAKET INTERNET</h4>
              <div name="badge" id="badge" class="ml-auto"></div>
            </div>
          </div>
          <div class="card-body">
                <div class="modal-body">
               <form action="{{route('admin.router.paket.export')}}" method="POST">
                @csrf
                @method('POST')
                 <div class="form-group row">
                   <div class="col-sm-8">
                  <label for="paket_nama" class=" col-form-label">Nama Paket</label>
                  <input type="text" id="paket_nama" name="paket_nama" class="form-control" >
                </div>
                <div class="col-sm-4">
                  <label for="paket_router" class="  col-form-label">Router</label>
                  <select class="form-control" id="paket_router" name="paket_router">
                    <option value="">Pilih</option>
                    @foreach ($data_router as $rout)
                    <option value="{{$rout->id}}">{{$rout->router_nama}}</option>
                    @endforeach 
                  </select>                               
                </div>
                </div>
                 <div class="form-group row">
                   <div class="col-sm-8">
                  <label for="paket_limitasi" class=" col-form-label">Rate Limit</label>
                  <input type="text" id="paket_limitasi" name="paket_limitasi" class="form-control" >
                </div>
                <div class="col-sm-1">
                  <label for="paket_shared" class="  col-form-label">Shared</label>
                  <input type="number" id="paket_shared" name="paket_shared" class="form-control" value="1">
                </div>
                <div class="col-sm-3">
                  <label for="paket_masa_aktif" class="  col-form-label">Masa Aktif</label>
                  <input type="text" id="paket_masa_aktif" name="paket_masa_aktif" class="form-control" value="30" >
                </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-8">
                 <label for="paket_komisi" class=" col-form-label">Komisi</label>
                 <input type="number" id="paket_komisi" name="paket_komisi" class="form-control" >
               </div>
               <div class="col-sm-4">
                 <label for="paket_harga" class="  col-form-label">Harga</label>
                 <input type="number" id="paket_harga" name="paket_harga" class="form-control" >
               </div>
               </div>
                <div class="form-group row">
                  <div class="col-sm-8">
                 <label for="paket_lokal" class=" col-form-label">Lokal Address</label>
                 <input type="text" id="paket_lokal" name="paket_lokal" class="form-control" >
               </div>
               <div class="col-sm-4">
                 <label for="paket_remote" class="  col-form-label">Remote Address</label>
                 <select name="pool" id="pool" class="form-control" placeholder="pool">
                  </select>
               </div>
               </div>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn " data-dismiss="modal">Keluar</button>
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </form>
            </div>
                
            
          </div>
         
      </div>
    </div>
         </div>
</form> 
  </div>
</div>

@endsection
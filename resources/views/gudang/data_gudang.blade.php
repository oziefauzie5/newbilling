@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="col-md-12">
      <div class="card">
          <div class="card-body">
          
              <div class="row">
            <div class="col-3">
               <div class="col">
                  <button class="btn btn-info btn-sm mb-3 btn-block"  data-toggle="modal" data-target="#add_gudang" type="button" >Tambah Gudang</button>
                </div>
            </div>
             {{-- ----------------------------------------------------------MODAL ADD KATEGORI------------------------------------------------------- --}}
 <div class="modal fade" id="add_gudang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{route('admin.gudang.store_gudang')}}" method="POST" class="needs-validation" novalidate>
          @csrf
          @method('POST')
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-row">
              <div class="col-md-6 mb-3">
                <label>Site</label>
                <select name="data__site_id" class="form-control" id="">
                  <option value="">--Pilih Site--</option>
                  @foreach ($data_site as $s)
                      <option value="{{$s->id}}">{{$s->site_nama}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label>Alamat</label>
                  <input type="text" class="form-control" name="gudang_alamat" value="">
              </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
  </form>
    </div>
  </div>
</div>
         
          <hr>
           <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover" >                          
              <thead>
            <tr class="text-center">
              <th>Site</th>
              <th>Alamat</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($data_gudang as $d)
            <tr>
               <td >{{ $d->site_gudang->site_nama ?? '-'}}</td>
               <td >{{ $d->gudang_alamat }}</td>
            </tr>
            @endforeach
            </tbody>
            </table>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

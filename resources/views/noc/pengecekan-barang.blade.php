@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <a href="#" class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$count_antrian}}</div>
            <div class="text-muted mb-3">Atrian Pengecekan</div>
          </div>
        </div>
      </a>
    </div>
    <div class="row">
      <div class="card">
        <div class="card-body">
          @if ($errors->any())
          <div class="alert alert-danger">
            <div class="alert-title"><h4>Gagal!!</h4></div>
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
          </div> 
        @endif
          <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover text-nowrap" >
              <thead>
                <tr>
                  <th>KODE BARANG</th>
                  <th>STATUS</th>
                  <th>TGL MASUK</th>
                  <th>NAMA</th>
                  <th>KETERANGAN</th>
                  <th>DESKRIPSI</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($sub_barang as $d)
                <tr>
                  <td>{{$d->id_subbarang}}</td>
                      <td><button class="btn btn-block btn-primary btn-sm" data-toggle="modal" data-target="#status_barang{{$d->id_subbarang}}">Update</button></td>
                      <td>{{$d->subbarang_tgl_masuk}}</td>
                      <td>{{$d->subbarang_nama}}</td>
                      <td>{{$d->subbarang_keterangan}}</td>
                      <td>{{$d->subbarang_deskripsi}}</td>
                    </tr>
                   <!-- Modal -->
<div class="modal fade" id="status_barang{{$d->id_subbarang}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="form-row">
      <form method="post" action="{{ route('admin.barang.update_status_barang',['id'=>$d->id_subbarang]) }}" >
      @csrf
       @method('PUT')
                 
                  <div class="col">
                    <label for="">ID Barang</label>
                    <input type="text" class="form-control" name="idbarang" value="{{$d->subbarang_idbarang}}" readonly>
                  </div>
                  <div class="col">
                    <label for="">Status Barang</label>
                    <input type="text" class="form-control" value="{{$d->subbarang_status}}" readonly>
                  </div>
                  <div class="col">
                    <label for="">Stok Barang</label>
                    <input type="text" class="form-control" value="{{$d->subbarang_stok}}" readonly>
                  </div>
                  <div class="col-12">
                    <label for="">Keterangan</label>
                    <select name="ket" id="" class="form-control">
                      <option value="Dalam Pengecekan">Dalam Pengecekan</option>
                      <option value="Rusak">Rusak</option>
                      <option value="Barang Retur">Barang Retur</option>
                      <option value="Barang Normal">Barang Normal</option>
                    </select>
                  </div>
                  <div class="col-12">
                    <label for="">Deskripsi<strong class="text-danger">*</strong></label>
                    <textarea type="text" class="form-control" id="desk" name="desk" value="{{$d->subbarang_deskripsi}}" row="5" required></textarea>
                    <div class="invalid-feedback">
                        Keterangan tidak boleh kosong
                    </div>
                  </div>
                </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
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
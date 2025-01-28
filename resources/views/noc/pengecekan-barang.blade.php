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
        <form>
          <div class="form-group row">
            <div class="col-sm-4">
              <input name="q" type="text" class="form-control form-control-sm" placeholder="Cari">
            </div>
            <div class="col-sm-2">
              <button type="submit" class="btn btn-block btn-dark btn-sm">Submit
            </div>
          </div>
        </form>
          <div class="table-responsive">
            <table id="" class="display table table-striped table-hover text-nowrap" >
              <thead>
                <tr>
                  <th>KODE BARANG</th>
                  <th>STATUS</th>
                  <th>TGL MASUK</th>
                  <th>NAMA BARANG</th>
                  <th>KATEGORI</th>
                  <th>KETERANGAN</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_barang as $d)
                <tr>
                  <td>{{$d->barang_id}}</td>
                      <td><button class="btn btn-block btn-primary btn-sm" data-toggle="modal" data-target="#status_barang{{$d->barang_id}}">Update</button></td>
                      <td>{{$d->barang_tglmasuk}}</td>
                      <td>{{$d->barang_nama}}</td>
                      <td>{{$d->barang_kategori}}</td>
                      <td>{{$d->barang_ket}}</td>
                    </tr>
                   
                   <!-- Modal -->
<div class="modal fade" id="status_barang{{$d->barang_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Quality Control </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="form-row">
      <form method="post" action="{{ route('admin.barang.update_status_barang',['id'=>$d->barang_id]) }}" >
      @csrf
       @method('PUT')
                 
                  <div class="col-6">
                    <label for="">ID Barang</label>
                    <input type="text" class="form-control" name="idbarang" value="{{$d->barang_id}}" readonly>
                  </div>
                  <div class="col-6">
                    <label for="">Stok Barang</label>
                    <input type="text" class="form-control" value="{{$d->barang_qty}}" readonly>
                  </div>
                  <div class="col-12">
                    <label for="">Status Barang</label>
                    <input type="text" class="form-control" value="@if($d->barang_status == 0)Bagus, normal dan bisa digunakan @elseif($d->barang_status == 4) Tidak bagus, rusak dan tidak bisa digunakan @elseif($d->barang_status == 5)Barang belum dicek @endif"  readonly>
                  </div>
                 
                  @if($d->barang_kategori == 'ONT')
                    <div class="col-6">
                      <label for="">Serial Number</label>
                      <input type="text" class="form-control" name="barang_sn"  value="" required>
                    </div>
                    <div class="col-6">
                      <label for="">Mac Address</label>
                      <input type="text" class="form-control" name="barang_mac" id="mac" minlength="17" maxlength="17" value="" required>
                    </div> 
                    <div class="col-6">
                      <label for="">Mac Address OLT</label>
                      <input type="text" class="form-control" name="barang_mac_olt" id="mac1" minlength="17" maxlength="17" value="" required>
                    </div> 
                    @elseif($d->barang_kategori == 'OLT')
                    <div class="col-6">
                      <label for="">Serial Number</label>
                      <input type="text" class="form-control" name="barang_sn"  value="" required>
                    </div>
                    <div class="col-6">
                      <label for="">Mac Address</label>
                      <input type="text" class="form-control" name="barang_mac" id="" value="" required>
                    </div> 
                    @elseif($d->barang_kategori == 'ROUTER')
                    <div class="col-6">
                      <label for="">Serial Number</label>
                      <input type="text" class="form-control" name="barang_sn" value="" required>
                    </div>
                    <div class="col-6">
                      <label for="">Mac Address</label>
                      <input type="text" class="form-control" name="barang_mac" id="" value="" required>
                    </div> 
                    @elseif($d->barang_kategori == 'SWITCH')
                    <div class="col-6">
                      <label for="">Serial Number</label>
                      <input type="text" class="form-control" name="barang_sn" value="" required>
                    </div>
                    <div class="col-6">
                      <label for="">Mac Address</label>
                      <input type="text" class="form-control" name="barang_mac" id=""  value="" required>
                    </div> 
                    @elseif($d->barang_kategori == 'SPLICER')

                  @endif
                  <div class="col-12">
                    <label for="">Status Barang</label>
                    <select name="barang_status" id="" class="form-control" required>
                      <option value="">- PILIH -</option>
                      <option value="Normal">Normal</option>
                      <option value="Rusak">Rusak</option>
                      {{-- <option value="1">Barang normal dan telah digunakan</option> --}}
                      {{-- <option value="5">Barang belum dicek</option> --}}
                    </select>
                  </div>
                  <div class="col-12">
                    <label for="">Keterangan<strong class="text-danger">*</strong></label>
                    <textarea type="text" class="form-control" id="" name="barang_ket" row="5" required>{{$d->barang_ket}}</textarea>
                    <div class="invalid-feedback">
                        Keterangan tidak boleh kosong
                    </div>
                  </div>
                </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Perbarui</button>
        </form>
      </div>
    </div>
  </div>
</div>
                    @endforeach
              </tbody>
            </table>
            <div class="pull-left">
              Showing
              {{$data_barang->firstItem()}}
              to
              {{$data_barang->lastItem()}}
              of
              {{$data_barang->total()}}
              entries
            </div>
            <div class="pull-right">
              {{ $data_barang->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
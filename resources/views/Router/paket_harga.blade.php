@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-primary">
            <div class="d-flex align-items-center">
              <span class="text-light text-bold">PAKET INTERNET</span>
            </div>
          </div>
          <div class="card-body">
    
            <!-- end Modal export mikrotik -->
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
                    <th>#</th>
                    <th>Nama Paket</th>
                    <th>Harga</th>
                    <th>Aktif</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($data_paket as $d)
                  <tr>

                    <td>{{$d->paket_id}}</td>
                    <td data-toggle="modal" data-target="#modal_edit{{$d->paket_id}}">{{$d->paket_nama}}</td>
                    <td data-toggle="modal" data-target="#modal_edit{{$d->paket_id}}">Rp. {{ number_format( $d->paket_harga)}}</td>
                    <td data-toggle="modal" data-target="#modal_edit{{$d->paket_id}}">{{$d->paket_layanan}}</td>
                    <td>{{$d->paket_status}}</td>
                      </tr>
                        <!-- Modal Edit -->
                        <div class="modal fade" id="modal_edit{{$d->paket_id}}" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header no-bd">
                                <h5 class="modal-title">
                                  <span class="fw-mediumbold">
                                  Edit harga paket {{$d->input_nama}}</span> 
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <form action="{{route('admin.router.update_harga_paket',['id'=>$d->paket_id])}}" method="POST">
                                  @csrf
                                  @method('POST')
                                  <div class="row">
                                    <div class="col-sm-12">
                                      <div class="form-group">
                                        <label>Nama Paket</label>
                                        <input type="text" class="form-control" name="paket_nama" value="{{ $d->paket_nama }}" required>
                                      </div>
                                    </div>
                                    <div class="col-sm-12">
                                      <div class="form-group">
                                        <label>Harga</label>
                                        <input type="number" class="form-control" value="{{ $d->paket_harga }}" name="paket_harga" required>
                                        <span>Harga diluar PPN</span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="modal-footer no-bd">
                                  <button type="button" class="btn" data-dismiss="modal">Close</button>
                                  <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- End Modal Edit -->
                      @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
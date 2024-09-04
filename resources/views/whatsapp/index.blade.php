@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-primary">
            <div class="d-flex align-items-center">
              <h4 class="card-title text-light">WHATSAPP GETEWAY</h4>
            </div>
          </div>
          <div class="card-body">
            <button class="btn  btn-sm ml-auto m-1 btn-primary ">
              <i class="fa fa-plus"></i> HAPUS</button>
            <button class="btn  btn-sm ml-auto m-1 btn-primary ">
              <i class="fa fa-plus"></i> EXPORT</button>
            <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#broadcast">
              <i class="fa fa-plus"></i> BROADCAST</button>      
              
              <!-- Modal -->
<div class="modal fade" id="broadcast" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">BROADCAST</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.whatsapp.broadcast')}}" method="post">
          @csrf
          @method('POST')

          <div class="row">
            <div class="col-12 mb-3">
              <label for="">Router</label>
              <select name="router" id="" class="form-control">
                <option value=""> - PILIH ROUTER - </option>
                @foreach($router as $r)
                <option value="{{$r->id}}">{{$r->router_nama}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12 mb-3">
              <label for="">Pesan</label>
              <textarea name="pesan" rows="10" class="form-control"></textarea>
            </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
      </div>
    </div>
  </div>
</div>
         
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
            <hr>
            <form >
              <div class="row mb-1">
                <div class="col-sm-4">
                  <input name="q" type="text" class="form-control form-control-sm" placeholder="Cari">
                </div>
                <div class="col-sm-2">
                  <button type="submit" class="btn btn-block btn-dark btn-sm">Submit
                </div>
              </div>
              </form>
              <hr>
              <div class="table-responsive">
                <table class="display table table-striped table-hover text-nowrap" >
                  <thead>
                    <tr>
                      <th>Status</th>
                      <th>Tujuan</th>
                      <th>Nama</th>
                      <th>Pesan</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($whatsapp as $d)
                    <tr>
                    <td>
                    <div class="form-button-action">
                      <button type="button" data-toggle="modal" data-target="#modal_hapus{{$d->id}}" class="btn btn-link btn-danger">
                        <i class="fa fa-times"></i>
                      </button>
                    </div>
                  </td>
                      @if($d->status == 'Done')
                      <td><div class="badge badge-success">{{$d->status}}</div></td>
                      @else
                      <td><div class="badge badge-danger">@if($d->status=='0')Process @else {{$d->status}} @endif</div></td>
                      @endif
                      <td>0{{$d->target}}</td>
                      <td >{{$d->nama}}</td>
                      <td data-toggle="modal" data-target="#exampleModal{{$d->id}}">{{$d->pesan}}</td>
                    </tr>
                    <!-- Modal -->
<div class="modal fade" id="exampleModal{{$d->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">PESAN WHATSAPP</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-mb-3">
          <label class="col-lg-12 col-form-label" >Tujuan</label>
          <div class="col-lg-12">
          <input type="text" class="form-control" name="tujuan" value="0{{$d->target}}" required>
          </div>
      </div>
      
      <div class="col-mb-3">
          <label class="col-lg-12 col-form-label" >Isi pesan</label>
          <div class="col-lg-12">
          <textarea class="form-control" name="pesan" rows="12" required>{{$d->pesan}}</textarea>
          </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Hapus -->
<div class="modal fade" id="modal_hapus{{$d->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header no-bd">
                              <h5 class="modal-title">
                                <span class="fw-mediumbold">
                                Hapus Data {{$d->name}}</span> 
                              </h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                              <div class="modal-body">
                              <p>Apakah anda yakin, akan menghapus pesan ke {{$d->nama}} ??</p>
                              </div>
                              <div class="modal-footer no-bd">
                                <form action="{{route('admin.wa.delete_pesan',['id'=>$d->id])}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-success">Hapus</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- End Modal Hapus -->
                    @endforeach
                  </tbody>
                </table>
                
                <div class="pull-left">
                  Showing
                  {{$whatsapp->firstItem()}}
                  to
                  {{$whatsapp->lastItem()}}
                  of
                  {{$whatsapp->total()}}
                  entries
                </div>
                <div class="pull-right">
                  {{ $whatsapp->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
              </div>
            
           
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
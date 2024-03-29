@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div id="myId" class="d-none">
    <div class="row">
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                  <i class="flaticon-success"></i>
                </div>
              </div>
              <div class="col col-stats ml-3 ml-sm-0">
                <div class="numbers">
                  <h4 class="card-title" id="resource"></h4>
                  <p class="card-category" >CPU Load</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                  <i class="flaticon-success"></i>
                </div>
              </div>
              <div class="col col-stats ml-3 ml-sm-0">
                <div class="numbers">
                  <h4 class="card-title" id="architecture-name"></h4>
                  <p class="card-category" >Architecture</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                  <i class="flaticon-success"></i>
                </div>
              </div>
              <div class="col col-stats ml-3 ml-sm-0">
                <div class="numbers">
                  <h4 class="card-title" id="cpu"></h4>
                  <p class="card-category" >CPU</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                  <i class="flaticon-success"></i>
                </div>
              </div>
              <div class="col col-stats ml-3 ml-sm-0">
                <div class="numbers">
                  <h4 class="card-title" id="version"></h4>
                  <p class="card-category" >Verison</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
    
    <center id="loading" class="d-none"><div class="loader" ></div></center>
    <div class="card " >
      <div class="card-header card-primary">
        <div class="card-title"><button class="btn btn-sm" data-toggle="modal" data-target="#addRowModal">Tambah</button></div>
      </div>
      <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header no-bd">
              <h5 class="modal-title">
                <span class="fw-mediumbold">
                Tambah Router</span> 
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="{{route('admin.router.store')}}" method="POST">
                @csrf
                @method('POST')
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Nama Router</label>
                      <input type="text" class="form-control" name="router_nama" value="{{ Session::get('router_nama') }}" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Host</label>
                      <input id="router_ip" type="text" class="form-control" value="{{ Session::get('router_ip') }}" name="router_ip" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>DNS</label>
                      <input id="router_dns" type="text" class="form-control" value="{{ Session::get('router_dns') }}" name="router_dns" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Port API</label>
                      <input id="router_port_api" type="text" class="form-control" value="{{ Session::get('router_port_api') }}" name="router_port_api" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Port Remote</label>
                      <input id="router_port_remote" type="text" class="form-control" value="{{ Session::get('router_port_remote') }}" name="router_port_remote" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Username</label>
                      <input id="router_username" type="text" class="form-control" value="{{ Session::get('router_username') }}" name="router_username" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Password</label>
                      <input id="router_password" type="text" class="form-control" value="{{ Session::get('router_password') }}" name="router_password" required>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer no-bd">
                <button type="submit" class="btn btn-success btn-sm">Submit</button>
              </form>
              <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div id="alert" class="alert alert-danger d-none">									
          <span id="router_nama" class=" text-danger"></span>
        </div>        
         <div class="table-responsive">
            <table id="edit_inputdata" class="display table table-striped table-hover" >
          <thead>
            <tr>
              <th scope="col">Router</th>
              <th scope="col">Host</th>SS
              <th scope="col">Status</th>
              <th style="width: 10%">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($router as $d)
            <tr>
             <td><a href="{{route('admin.router.getPppoe',['id'=>$d->id])}}" >{{$d->router_nama}}</a> </td> 
             <td class="navigateTest" id="{{$d->id}}">{{$d->router_ip}}</td> 
             <td class="navigateTest" id="{{$d->id}}">{{$d->router_status}}</td> 
             <td>
              <div class="form-button-action">
                <button type="button" data-toggle="modal" data-target="#modal_edit{{$d->id}}" class="btn btn-link btn-primary btn-lg">
                  <i class="fa fa-edit"></i>
                </button>
                <button type="button" data-toggle="modal" data-target="#modal_delete{{$d->id}}" class="btn btn-link btn-danger">
                  <i class="fa fa-times"></i>
                </button>
              </div>
            </td>
            {{-- Edit Router --}}
            <div class="modal fade" id="modal_edit{{$d->id}}" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header no-bd">
                    <h5 class="modal-title">
                      <span class="fw-mediumbold">
                      Tambah Router</span> 
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="{{route('admin.router.edit',['id'=>$d->id])}}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Nama Router</label>
                            <input type="text" class="form-control" name="router_nama" value="{{ $d->router_nama }}" required>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Host</label>
                            <input id="router_ip" type="text" class="form-control" value="{{ $d->router_ip }}" name="router_ip" required>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>DNS</label>
                            <input id="router_dns" type="text" class="form-control" value="{{ $d->router_dns }}" name="router_dns" required>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Port API</label>
                            <input id="router_port_api" type="text" class="form-control" value="{{ $d->router_port_api }}" name="router_port_api" required>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Port Remote</label>
                            <input id="router_port_remote" type="text" class="form-control" value="{{ $d->router_port_remote }}" name="router_port_remote" required>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Username</label>
                            <input id="router_username" type="text" class="form-control" value="{{ $d->router_username }}" name="router_username" required>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Password</label>
                            <input id="router_password" type="password" class="form-control" value="{{ $d->router_password }}" name="router_password" required>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" id="flexCheckDefault" onclick="myFunction()">
                              <span class="form-check-sign">Lihat Password</span>
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer no-bd">
                      <button type="submit" class="btn btn-success btn-sm">Submit</button>
                    </form>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            {{-- End Edit Router --}}
            {{-- Delete Router --}}
            <div class="modal fade" id="modal_delete{{$d->id}}" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header no-bd">
                    <h5 class="modal-title">
                      <span class="fw-mediumbold">
                      Tambah Router</span> 
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="{{route('admin.router.delete_router',['id'=>$d->id])}}" method="POST">
                      @csrf
                      @method('DELETE')
                      <p>Yakin menghapus Router {{$d->router_nama}}</p>
                    </div>
                    <div class="modal-footer no-bd">
                      <button type="submit" class="btn btn-success btn-sm">Submit</button>
                    </form>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            {{-- End Delete Router --}}
            </tr>   
            @endforeach
          </tbody>
        </table>
      </div>
      </div>
    </div>






  </div>
</div>

@endsection
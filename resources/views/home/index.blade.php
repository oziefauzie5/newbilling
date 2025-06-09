@extends('layout.main')
@section('content')

<div class="content">
  <div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
        <div>
          <h2 class="text-white pb-2 fw-bold">{{Session::get('app_nama')}}</h2>
          <h3 class="text-white op-7 mb-2">{{Session::get('app_brand')}}</h3>
        </div>
      </div>
    </div>
  </div>
  <div class="page-inner mt--5">
  
  {{-- DASHBOARD REGISTRASI --}}
  {{-- <div class="row">
    <div class="col-6 col-sm-4 col-lg-2">
      <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$count_inputdata}}</div>
            <div class="text-muted mb-3">Data Belum Registrasi</div>
          </div>
        </div>
      </div>
      
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$count_berlangganan_ppp}}</div>
            <div class="text-muted mb-3">Berlangganan PPP</div>
          </div>
        </div>
      </div>
      
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$count_berlangganan_hotspot}}</div>
            <div class="text-muted mb-3">Berlangganan Hotspot</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$count_pb}}</div>
            <div class="text-muted mb-3">Putus Berlanggan</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$count_ps}}</div>
            <div class="text-muted mb-3">Putus Sementara</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0">{{$count_free_berlangganan}}</div>
            <div class="text-muted mb-3">Free Berlanggan</div>
          </div>
        </div>
      </div>
    </div> --}}
  {{-- DASHBOARD REGISTRASI --}}
  {{-- DASHBOARD TIKET --}}
  <div class="row">
    <a style="text-decoration:none" href="{{route('admin.tiket.data_tiket')}}" class="col-6 col-sm-4 col-lg-3">
      <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0 text-danger">{{$count_tiket_general}}</div>
            <div class="text-muted">Tiket General</div>
            <div class="d-flex justify-content-between mt-2">
              <p class="text-muted mb-0 text-danger">Tiket Hari ini</p>
              <p class="mb-0 text-danger">{{$count_tiket_general_hari_ini}}</p>
            </div>
          </div>
        </div>
      </a>
    <div class="col-6 col-sm-4 col-lg-3">
      <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0 text-danger">{{$count_tiket_project}}</div>
            <div class="text-muted">Tiket Project</div>
            <div class="d-flex justify-content-between mt-2">
              <p class="text-muted mb-0 text-danger">Tiket Project Hari ini</p>
              <p class="mb-0 text-danger">{{$count_tiket_project_hari_ini}}</p>
            </div>
          </div>
        </div>
      </div>
    <div class="col-6 col-sm-4 col-lg-3">
      <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0 text-danger">{{$count_tiket_closed}}</div>
            <div class="text-muted">Tiket Closed</div>
            <div class="d-flex justify-content-between mt-2">
              <p class="text-muted mb-0 text-danger">Tiket Closed Hari ini</p>
              <p class="mb-0 text-danger">{{$count_tiket_closed_hari_ini}}</p>
            </div>
          </div>
        </div>
      </div>
    <div class="col-6 col-sm-4 col-lg-3">
      <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h1 m-0 text-danger">{{$count_tiket_pending}}</div>
            <div class="text-muted">Tiket Pending</div>
            <div class="d-flex justify-content-between mt-2">
              <p class="text-muted mb-0 text-danger">Tiket Pending Hari ini</p>
              <p class="mb-0 text-danger">{{$count_tiket_pending_hari_ini}}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  {{-- DASHBOARD TIKET --}}



     <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#import_teknisi">
                <i class="fa fa-file-import"></i> Import Teknisi
              </button>
          <!-- Modal Import -->
          <div class="modal fade" id="import_teknisi" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header no-bd">
                  <h5 class="modal-title">
                    <span class="fw-mediumbold">
                    Import Odp</span> 
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{route('admin.export.import_teknisi')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Pilih file (EXCEL,CSV)</label>
                          <input id="import" type="file" class="form-control" name="file" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer no-bd">
                    <button type="submit" class="btn btn-success">Add</button>
                  </form>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <button class="btn  btn-sm ml-auto m-1 btn-dark " data-toggle="modal" data-target="#import_registrasis">
            <i class="fa fa-file-import"></i> IMPORT
          </button>
          <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#import_laporan">
                     <i class="fa fa-file-import"></i> Import Laporan
                   </button>
          <!-- Modal Import -->
          <div class="modal fade" id="import_laporan" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header no-bd">
                  <h5 class="modal-title">
                    <span class="fw-mediumbold">
                    Import Odp</span> 
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{route('admin.export.import_laporan')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Pilih file (EXCEL,CSV)</label>
                          <input id="import" type="file" class="form-control" name="file" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer no-bd">
                    <button type="submit" class="btn btn-success">Add</button>
                  </form>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#import_jurnal">
                     <i class="fa fa-file-import"></i> Import Laporan
                   </button>
          <!-- Modal Import -->
          <div class="modal fade" id="import_jurnal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header no-bd">
                  <h5 class="modal-title">
                    <span class="fw-mediumbold">
                    Import Odp</span> 
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{route('admin.export.import_jurnal')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Pilih file (EXCEL,CSV)</label>
                          <input id="import" type="file" class="form-control" name="file" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer no-bd">
                    <button type="submit" class="btn btn-success">Add</button>
                  </form>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
     <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#import_mutasi">
                <i class="fa fa-file-import"></i> Import Mutasi
              </button>
          <!-- Modal Import -->
          <div class="modal fade" id="import_mutasi" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header no-bd">
                  <h5 class="modal-title">
                    <span class="fw-mediumbold">
                    Import Odp</span> 
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{route('admin.export.import_mutasi')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Pilih file (EXCEL,CSV)</label>
                          <input id="import" type="file" class="form-control" name="file" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer no-bd">
                    <button type="submit" class="btn btn-success">Add</button>
                  </form>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
     <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#import_user">
                <i class="fa fa-file-import"></i> Import User
              </button>
          <!-- Modal Import -->
          <div class="modal fade" id="import_user" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header no-bd">
                  <h5 class="modal-title">
                    <span class="fw-mediumbold">
                    Import Odp</span> 
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{route('admin.export.import_user')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Pilih file (EXCEL,CSV)</label>
                          <input id="import" type="file" class="form-control" name="file" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer no-bd">
                    <button type="submit" class="btn btn-success">Add</button>
                  </form>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <button class="btn  btn-sm ml-auto m-1 btn-warning " data-toggle="modal" data-target="#import_input_data">
            <i class="fa fa-file-import"></i> import_input_data
          </button>
          <!-- Modal Import -->
          <div class="modal fade" id="import_input_data" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header no-bd">
                  <h5 class="modal-title">
                    <span class="fw-mediumbold">
                    Input Data Baru</span> 
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{route('admin.export.import_input_data')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Pilih file (EXCEL,CSV)</label>
                          <input id="import" type="file" class="form-control" name="file" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer no-bd">
                    <button type="submit" class="btn btn-success">Add</button>
                  </form>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <button class="btn  btn-sm ml-auto m-1 btn-warning " data-toggle="modal" data-target="#import_mitra">
            <i class="fa fa-file-import"></i> import_mitra
          </button>
          <!-- Modal Import -->
          <div class="modal fade" id="import_mitra" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header no-bd">
                  <h5 class="modal-title">
                    <span class="fw-mediumbold">
                    Input Data Baru</span> 
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{route('admin.export.import_mitra')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Pilih file (EXCEL,CSV)</label>
                          <input id="import" type="file" class="form-control" name="file" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer no-bd">
                    <button type="submit" class="btn btn-success">Add</button>
                  </form>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
               <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#import_instalasi">
                <i class="fa fa-file-import"></i> IMPORT FTTH INSTALASI
              </button>
                   <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#import_fee">
                <i class="fa fa-file-import"></i> IMPORT FTTH FEE
              </button>
          <!-- Modal Import -->
          <div class="modal fade" id="import_instalasi" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header no-bd">
                  <h5 class="modal-title">
                    <span class="fw-mediumbold">
                    Import Instalasi</span> 
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{route('admin.export.import_instalasi')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Pilih file (EXCEL,CSV)</label>
                          <input id="import" type="file" class="form-control" name="file" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer no-bd">
                    <button type="submit" class="btn btn-success">Add</button>
                  </form>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Modal Import -->
          <div class="modal fade" id="import_fee" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header no-bd">
                  <h5 class="modal-title">
                    <span class="fw-mediumbold">
                    Import Fee</span> 
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{route('admin.export.import_fee')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Pilih file (EXCEL,CSV)</label>
                          <input id="import" type="file" class="form-control" name="file" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer no-bd">
                    <button type="submit" class="btn btn-success">Add</button>
                  </form>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#import_akun">
          <i class="fa fa-file-import"></i> IMPORT AKUN
          </button>
          <!-- Modal Import -->
          <div class="modal fade" id="import_akun" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header no-bd">
          <h5 class="modal-title">
           <span class="fw-mediumbold">
           Import Instalasi</span> 
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
          </button>
          </div>
          <div class="modal-body">
          <form action="{{route('admin.export.import_akun')}}" method="POST" enctype="multipart/form-data">
           @csrf
           @method('POST')
           <div class="row">
             <div class="col-sm-12">
               <div class="form-group">
                 <label>Pilih file (EXCEL,CSV)</label>
                 <input id="import" type="file" class="form-control" name="file" required>
               </div>
             </div>
           </div>
          </div>
          <div class="modal-footer no-bd">
           <button type="submit" class="btn btn-success">Add</button>
          </form>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
          </div>
          </div>
          </div>
          <button class="btn  btn-sm ml-auto m-1 btn-primary " data-toggle="modal" data-target="#import_kategori">
          <i class="fa fa-file-import"></i> IMPORT KATEGORI
          </button>
          <!-- Modal Import -->
          <div class="modal fade" id="import_kategori" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header no-bd">
          <h5 class="modal-title">
           <span class="fw-mediumbold">
           Import Instalasi</span> 
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
          </button>
          </div>
          <div class="modal-body">
          <form action="{{route('admin.export.import_kategori')}}" method="POST" enctype="multipart/form-data">
           @csrf
           @method('POST')
           <div class="row">
             <div class="col-sm-12">
               <div class="form-group">
                 <label>Pilih file (EXCEL,CSV)</label>
                 <input id="import" type="file" class="form-control" name="file" required>
               </div>
             </div>
           </div>
          </div>
          <div class="modal-footer no-bd">
           <button type="submit" class="btn btn-success">Add</button>
          </form>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
          </div>
          </div>
          </div>

           <!-- Modal Import -->
         <div class="modal fade" id="import_registrasis" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header no-bd">
                <h5 class="modal-title">
                  <span class="fw-mediumbold">
                  Input Data Baru</span> 
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('admin.export.import_registrasi')}}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('POST')
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label>Pilih file (EXCEL,CSV)</label>
                        <input id="import" type="file" class="form-control" name="file" placeholder="Nama Lengkap" required>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer no-bd">
                  <button type="submit" class="btn btn-success">Add</button>
                </form>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
          
</div>
</div>


@endsection
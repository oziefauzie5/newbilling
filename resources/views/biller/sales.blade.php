@extends('layout.user')
@section('content')

<div class="content">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">

                
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
      <div class="user mt--5">
        <div class="avatar-sm float-left mr-2">
          <img src="@if(Auth::user()->photo) {{ asset('storage/image/'.Auth::user()->photo) }} @else {{ asset('atlantis/assets/img/user.png') }}@endif" alt=".." class="avatar-img rounded-circle"> 
        </div>
        <div class="info">
          <span> 
              <span class="user-level text-light font-weight-bold">{{strtoupper(Auth::user()->name)}}</span><br>
              <h6 class="user-level text-light ">{{$role}}</h6>
          <div class="clearfix"></div>
        </span>
        </div>

      {{-- <div class="h5 mt--5 text-light font-weight-bold ">TEKNISI : {{strtoupper($nama)}}</div><br> --}}
      
        <div class="row mt-1">
          <div class="col-6 col-sm-6">
            <div class="card">
              <div class="card-body p-3 text-center">
                <div class="text-right text-success">
                </div>
                <div class="h5 m-0">Rp. {{number_format($komisi)}}</div>
                <div class="text-muted mb-3">SALDO</div>
              </div>
            </div>
          </div>
          <div class="col-6 col-sm-6">
            <div class="card">
              <div class="card-body p-3 text-center">
                <div class="text-right text-success">
                </div>
                <div class="h5 m-0">Rp. {{number_format($pencairan)}}</div>
                <div class="text-muted mb-3">PENCAIRAN</div>
              </div>
            </div>
          </div>
            
          </div>
   
          <section class="content">
          <div class="card card-primary card_custom1 p-3">
              <div class="table-responsive">
                  <table>
                      <tr>
                        {{-- <td>
                          <a href="{{ route('admin.sales.index') }}" class="card mb-2 card_custom1" style="width: 4rem;">
                          <img src="{{ asset('atlantis/assets/img/home.png') }}" class="card-img-center p-2" alt="...">
                          </a>
                          <div class="text-light mb-3 text-center">Home</div>
                        </td> --}}
                        <td>
                          <a href="{{ route('admin.sales.sales_input') }}" class="card mb-2 card_custom1" style="width: 4rem;">
                          <img src="{{ asset('atlantis/assets/img/add_users.png') }}" class="card-img-center p-2" alt="...">
                          </a>
                          <div class="text-light mb-3 text-center">Input</div>
                        </td>
                        <td>
                          <a href="{{ route('admin.sales.pelanggan') }}" class="card mb-2 card_custom1" style="width: 4rem;">
                          <img src="{{ asset('atlantis/assets/img/users.png') }}" class="card-img-center p-2" alt="...">
                          </a>
                          <div class="text-light mb-3 text-center">Pelanggan</div>
                        </td>
                        <td>
                          <a href="{{route('admin.sales.mutasi_sales') }}" class="card mb-2 card_custom1" style="width: 4rem;">
                          <img src="{{ asset('atlantis/assets/img/mutasi.png') }}" class="card-img-center p-2" alt="...">
                          </a>
                          <div class="text-light mb-3 text-center">Mutasi</div>
                        </td>
                      </tr>
                  </table>
                </div>
              </div>
      </div>
  </section>
  <section class="content mt-3">
    @foreach($input_data as $pel)
    <div class="col">
        <a href="{{route('admin.sales.sales_update',['id'=>$pel->id])}}" class="card card_custom1" id="update_tiket" >
          <div class="card-body skew-shadow">
              <div class="row">
                  <div class="col-12 pr-0">
                      <h3 class="fw-bold mb-1">{{$pel->input_nama}}</h3>
                      <div class="text-small text-uppercase fw-bold op-8">{{$pel->input_alamat_pasang}}</div>
                  </div>
                  <div class="col-12 pr-0">
                      <div class="text-small text-uppercase fw-bold text-danger"><span class="text-dark">Tanggal Input : </span> {{date('d M Y H:m:s',strtotime($pel->created_at))}}</div>
                  </div>
                  <div class="col-12 pl-0 text-right">
                      <div class="text-small text-uppercase fw-bold op-8">@if($pel->input_status=='REGIST') Menunggu Teknisi @else {{$pel->input_status}} @endif</div>
                  </div>
              </div>
          </div>
      </a>
    </div>
    @endforeach
    @foreach($registrasi as $pel)
    <div class="col">
        <div class="card card_custom1"  data-toggle="modal" data-target="#exampleModal{{$pel->id}}" id="update_tiket" >
          <div class="card-body skew-shadow">
              <div class="row">
                  <div class="col-12 pr-0">
                      <h3 class="fw-bold mb-1">{{$pel->input_nama}}</h3>
                      <div class="text-small text-uppercase fw-bold op-8">{{$pel->input_alamat_pasang}}</div>
                  </div>
                  <div class="col-12 pr-0">
                      <div class="text-small text-uppercase fw-bold text-danger"><span class="text-dark">Tanggal Input : </span> {{date('d M Y H:m:s',strtotime($pel->created_at))}}</div>
                  </div>
                  <div class="col-12 pl-0 text-right">
                      <div class="text-small text-uppercase fw-bold op-8">@if($pel->reg_progres=='0') Menunggu Teknisi @elseif($pel->reg_progres=='1') Pemasangan Teknisi @elseif($pel->reg_progres>='2') Pemasangan Selesai  @endif</div>
                  </div>
              </div>
          </div>
      </div>
    </div>
    @endforeach
</section>




            
    </div>
  </div>
@endsection
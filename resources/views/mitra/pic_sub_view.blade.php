@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Data Sub PIC Lingkungan - {{$pic_mitra->user_mitra->name}}</h3>
            </div>
              <div class="card-body">


<div class="table-responsive">
    <table id="input_data" class="display table table-striped table-hover text-nowrap" >
                      <thead>
                <tr>
                  <th>No</th>
                  <th>Aksi</th>
                  <th>Site</th>
                  <th>Sub PIC Id</th>
                  <th>Nama</th>
                  <th>Whatsapp</th>
                  <th>Fee</th>
                  <th>Alamat</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($pic_sub_view as $d)
                  <tr>
                   <td>{{$loop->iteration}}</td>
                   <td>
                    <a href="{{route('admin.mitra.pic_sub_edit_view',['id'=>$d->mts_sub_user_id,'mit'=>$pic_mitra->user_mitra->id])}}" class="btn btn-link btn-primary"><i class="fas fa-edit"></i></a>
                   </td>
                   <td>{{$d->submitra_site->site_nama ?? '-'}}</td>
                   <td>{{$d->mts_sub_user_id ?? '-'}}</td>
                   <td><a href="{{route('admin.mitra.data',['id'=>$d->mts_sub_user_id])}}"><strong class="text-primary">{{$d->user_submitra->name ?? '-'}}</strong> </a> </td>
                   <td>0{{$d->user_submitra->hp ?? '-'}}</td>
                   <td>Rp. {{ number_format($d->mts_fee) ?? '0'}}</td>
                   <td>{{$d->user_submitra->alamat_lengkap ?? '-'}}</td>
                   </tr>
                   @endforeach
                </tbody>
                <tfoot>
              </table>
              </div>
          </div>
          </div>
  </div>
</div>

@endsection
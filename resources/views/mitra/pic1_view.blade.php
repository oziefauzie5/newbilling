@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Data PIC Lingkungan</h3>
            </div>
              <div class="card-body">
<a href="{{ route('admin.mitra.pic1_add_view') }}" class="btn btn-dark btn-sm" ><i class="fas fa-solid fa-plus"></i>&ensp;Tambah PIC Lingkungan</a><br><hr>


<div class="table-responsive">
    <table id="input_data" class="display table table-striped table-hover text-nowrap" >
                      <thead>
                <tr>
                  <th>No</th>
                  <th>Aksi</th>
                  <th>PIC Id</th>
                  <th>Nama</th>
                  <th>Site</th>
                  <th>Sub Mitra</th>
                  <th>Phone</th>
                  <th>Alamat</th>
                  <th>Komisi</th>
                  <th>Fee Continue</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($pic1_view as $d)
                  <tr>
                   <td>{{$loop->iteration}}</td>
                   
                   <td>
                    <div class="form-button-action">
                      <a href="{{route('admin.mitra.pic1_edit_view',['id'=>$d->mts_user_id,'Mitra=Pic'])}}" class="btn btn-link btn-primary"><i class="fas fa-edit"></i></a>
                      <a href="{{route('admin.mitra.pic_sub_view',['id'=>$d->mts_user_id])}}" class="btn btn-link btn-primary"><i class="fas fa-users"></i></a>
                      <a href="{{route('admin.mitra.pic_addsub_view',['id'=>$d->mts_user_id,'pic'=>$d->user_mitra->name ?? ''])}}" class="btn btn-link btn-primary"><i class="fas fa-plus"></i></a>
                      <a href="{{route('admin.mitra.mitra_mutasi',['id'=>$d->mts_user_id])}}" class="btn btn-link btn-primary"><i class="fas fa-receipt"></i></a>
										</div>
                   </td>
                   <td>{{$d->mts_user_id ?? '-'}}</td>
                   <td><a href="{{route('admin.mitra.data',['id'=>$d->mts_user_id])}}"><strong>{{$d->user_mitra->name ?? '-'}}</strong> </a> </td>
                   <td>{{$d->mitra_site->site_nama ?? '-'}}</td>
                   <td>{{$d->mitra_sub->count() ?? '-'}}</td>
                   <td>{{$d->user_mitra->hp ?? '-'}}</td>
                   <td>{{$d->user_mitra->alamat_lengkap ?? '-'}}</td>
                   <td>Rp. {{ number_format($d->mts_komisi) ?? '0'}}</td>
                   <td>Rp. {{ number_format($d->mts_komisi) ?? '0'}}</td>
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
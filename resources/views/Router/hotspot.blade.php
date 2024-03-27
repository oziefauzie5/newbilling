@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="card " >
      <div class="card-header card-primary">
            <a href="{{route('admin.router.index')}}" class="btn btn-primary stretched-link">ROUTER</a>
            <a href="{{route('admin.router.getPppoe',['id'=>$id])}}" class="btn btn-primary stretched-link">PPPOE</a>
            <a href="{{route('admin.router.getHotspot',['id'=>$id])}}" class="btn btn-primary stretched-link">HOTSPOT</a>
        </div>
      <div class="card-body">
        <div id="alert" class="alert alert-danger d-none">									
          <span  class=" text-danger">Router Disconnect</span>
        </div>        
        {{-- <table class="table mt-3 " id="employees" > --}}
          <div class="table-responsive">
            <table id="input_data" class="display table table-striped table-hover" >
          <thead>
            <tr>
              <th style="width: 10%">Action</th>
              <th scope="col">Server</th>
              <th scope="col">Username</th>
              <th scope="col">uptime</th>
              <th scope="col">address</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($getuseractive as $d)
            <tr> 
              <td>
               <div class="form-button-action">
                 <a href="{{route('admin.router.kick_hotspot',['id'=>$id,'idmik'=>$d[".id"]])}}" class="btn btn-link btn-danger"><i class="fa fa-times"></i></a>
               </div>
              </td>
              <td>{{$d['server']}}</td> 
             <td>{{$d['user']}}</td> 
             <td>{{$d['uptime']}}</td> 
             <td>{{$d["address"]}}</td>
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
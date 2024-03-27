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
              <th scope="col">Nama</th>
              <th scope="col">Caller Id</th>
              <th scope="col">address</th>
              <th scope="col">uptime</th>
              <th style="width: 10%">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($getuseractive as $d)
            <tr> 
             <td>{{$d['name']}}</td> 
             <td>{{$d['caller-id']}}</td> 
             <td><a href="{{route('admin.router.router_remote',['id'=>$id,'ip'=>$d["address"]])}}" target="_blank" rel="noopener noreferrer">{{$d["address"]}}</a></td> 
             <td>{{$d['uptime']}}</td> 
             <td></td>
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
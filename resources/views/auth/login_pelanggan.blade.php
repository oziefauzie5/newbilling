<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OVALL FIBER</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{ asset('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css')}}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      {{-- <a href="" class="h1"><b>OVALL FIBER</b></a> --}}
<img src="{{ asset('atlantis/assets/img/ovall_logo.png')}}" alt="OVALL FIBER" class="brand-image " style="opacity: .8">

    </div>
    <div class="card-body">
      <form action="{{ route('proses-login')}}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="number" class="form-control" name="input_hp" placeholder="Masukan nomor Whatsapp" value="{{ old('input_hp')}}">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
        </div>
        @error('input_hp')
            <span>{{$message}}</span>
        @enderror
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              {{-- <label for="remember">
                Remember Me
              </label> --}}
            </div>
          </div>
          <div class="col-4 r">
            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>
<script src="{{ asset('lte/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('lte/dist/js/adminlte.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if ($message = Session::get('success'))
    <script>Swal.fire("{{$message}}");</script>
@endif
@if ($message = Session::get('failed'))
    <script>Swal.fire("{{$message}}");</script>
@endif
</body>
</html>

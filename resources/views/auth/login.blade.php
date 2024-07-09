<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OVALL FIBER</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css')}}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      {{-- <a href="{{ route('adminapp')}}" class="h1"><b>OVALL FIBER</b></a> --}}
      <img src="{{ asset('atlantis/assets/img/ovall_logo.png')}}" alt="OVALL FIBER" class="brand-image " style="opacity: .8">
    </div>
    <div class="card-body">
      <img src="https://www.google.com/search?q=ologo&sca_esv=595776748&rlz=1C1CHBF_enID1019ID1019&tbm=isch&sxsrf=AM9HkKmK7K55ccDTQHqwDOxWo7zpc8tsDg:1704404147583&source=lnms&sa=X&ved=2ahUKEwiLwJSe2MSDAxXI4TgGHVb7AdAQ_AUoAXoECAMQAw&biw=1366&bih=599&dpr=1#imgrc=XC41OE24tqKTDM" alt="">

      <form action="{{ route('login-proses')}}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="Usernamae" value="{{ old('username')}}">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        @error('username')
            <span>{{$message}}</span>
        @enderror
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        @error('password')
        <span>{{$message}}</span>
        @enderror
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
          </div>
        </div>
      </form>


      <!-- /.social-auth-links -->

  
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('lte/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
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

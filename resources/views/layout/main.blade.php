	


<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>{{Session::get('app_brand')}}</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="{{ asset('storage/img/'.Session::get('app_favicon')) }}" type="image/x-icon"/>

	<!-- Fonts and icons -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
	<script src="{{asset('atlantis/assets/js/plugin/webfont/webfont.min.js')}}"></script>
	<script>
		WebFont.load({
			google: {"families":["Lato:300,400,700,900"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ["{{asset('atlantis/assets/css/fonts.min.css')}}"]},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="{{asset('atlantis/assets/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('atlantis/assets/css/atlantis.min.css')}}">

	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link rel="stylesheet" href="{{asset('atlantis/assets/css/demo.css')}}">

	<style>
		   /* HTML:  */
		   .loader {
  border: 5px solid #f3f3f3;
  /* position: fixed; */
  border-radius: 50%;
  border-top: 5px solid #5d69be;
  margin-bottom: 30px;
  width: 50px;
  height: 50px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
	</style>
	<style>
		.nav {
			color:black
		}
		.card {
			border-radius: 15px; overflow: hidden;
		}
		.btn {
  border-radius: 15px;
  font-weight: bold;
}
	</style>

</head>
<body>
	<div class="wrapper">
		<div class="main-header">
			<!-- Logo Header -->
			<div class="logo-header" data-background-color="blue" >
				
				<a href="index.html" class="logo">
					{{-- <img src="{{ asset('storage/img/'.Session::get('app_logo')) }}" alt="navbar brand" class="navbar-brand"> --}}
					<h3 class="navbar-brand text-light"><strong> {{Session::get('app_brand')}}</strong></h3>
				</a>
				<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon">
						<i class="icon-menu"></i>
					</span>
				</button>
				<button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
				<div class="nav-toggle">
					<button class="btn btn-toggle toggle-sidebar">
						<i class="icon-menu"></i>
					</button>
				</div>
			</div>
			<!-- End Logo Header -->

			<!-- Navbar Header -->
			<nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">
				
				<div class="container-fluid">
					<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
						<li class="nav-item toggle-nav-search hidden-caret">
							<a class="nav-link" data-toggle="collapse" href="#search-nav" role="button" aria-expanded="false" aria-controls="search-nav">
								<i class="fa fa-search"></i>
							</a>
						</li>
						<li class="nav-item dropdown hidden-caret">
							<a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-envelope"></i>
							</a>
							<ul class="dropdown-menu messages-notif-box animated fadeIn" aria-labelledby="messageDropdown">
								<li>
									<div class="dropdown-title d-flex justify-content-between align-items-center">
										Messages 									
										<a href="#" class="small">Mark all as read</a>
									</div>
								</li>
								<li>
									<div class="message-notif-scroll scrollbar-outer">
										<div class="notif-center">
											<a href="#">
												<div class="notif-img"> 
													<img src="{{asset('atlantis/assets/img/jm_denis.jpg')}}" alt="Img Profile">
												</div>
												<div class="notif-content">
													<span class="subject">Jimmy Denis</span>
													<span class="block">
														How are you ?
													</span>
													<span class="time">5 minutes ago</span> 
												</div>
											</a>
											<a href="#">
												<div class="notif-img"> 
													<img src="{{asset('atlantis/assets/img/chadengle.jpg')}}" alt="Img Profile">
												</div>
												<div class="notif-content">
													<span class="subject">Chad</span>
													<span class="block">
														Ok, Thanks !
													</span>
													<span class="time">12 minutes ago</span> 
												</div>
											</a>
											<a href="#">
												<div class="notif-img"> 
													<img src="{{asset('atlantis/assets/img/mlane.jpg')}}" alt="Img Profile">
												</div>
												<div class="notif-content">
													<span class="subject">Jhon Doe</span>
													<span class="block">
														Ready for the meeting today...
													</span>
													<span class="time">12 minutes ago</span> 
												</div>
											</a>
											<a href="#">
												<div class="notif-img"> 
													<img src="{{asset('atlantis/assets/img/talha.jpg')}}" alt="Img Profile">
												</div>
												<div class="notif-content">
													<span class="subject">Talha</span>
													<span class="block">
														Hi, Apa Kabar ?
													</span>
													<span class="time">17 minutes ago</span> 
												</div>
											</a>
										</div>
									</div>
								</li>
								<li>
									<a class="see-all" href="javascript:void(0);">See all messages<i class="fa fa-angle-right"></i> </a>
								</li>
							</ul>
						</li>
						<li class="nav-item dropdown hidden-caret">
							<a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-bell"></i>
								<span class="notification">4</span>
							</a>
							<ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
								<li>
									<div class="dropdown-title">You have 4 new notification</div>
								</li>
								<li>
									<div class="notif-scroll scrollbar-outer">
										<div class="notif-center">
											<a href="#">
												<div class="notif-icon notif-primary"> <i class="fa fa-user-plus"></i> </div>
												<div class="notif-content">
													<span class="block">
														New user registered
													</span>
													<span class="time">5 minutes ago</span> 
												</div>
											</a>
											<a href="#">
												<div class="notif-icon notif-success"> <i class="fa fa-comment"></i> </div>
												<div class="notif-content">
													<span class="block">
														Rahmad commented on Admin
													</span>
													<span class="time">12 minutes ago</span> 
												</div>
											</a>
											<a href="#">
												<div class="notif-img"> 
													<img src="{{asset('atlantis/assets/img/profile2.jpg')}}" alt="Img Profile">
												</div>
												<div class="notif-content">
													<span class="block">
														Reza send messages to you
													</span>
													<span class="time">12 minutes ago</span> 
												</div>
											</a>
											<a href="#">
												<div class="notif-icon notif-danger"> <i class="fa fa-heart"></i> </div>
												<div class="notif-content">
													<span class="block">
														Farrah liked Admin
													</span>
													<span class="time">17 minutes ago</span> 
												</div>
											</a>
										</div>
									</div>
								</li>
								<li>
									<a class="see-all" href="javascript:void(0);">See all notifications<i class="fa fa-angle-right"></i> </a>
								</li>
							</ul>
						</li>
						<li class="nav-item dropdown hidden-caret">
							<a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
								<i class="fas fa-layer-group"></i>
							</a>
							<div class="dropdown-menu quick-actions quick-actions-info animated fadeIn">
								<div class="quick-actions-header">
									<span class="title mb-1">Quick Actions</span>
									<span class="subtitle op-8">Shortcuts</span>
								</div>
								<div class="quick-actions-scroll scrollbar-outer">
									<div class="quick-actions-items">
										<div class="row m-0">
											<a class="col-6 col-md-4 p-0" href="#">
												<div class="quick-actions-item">
													<i class="flaticon-file-1"></i>
													<span class="text">Generated Report</span>
												</div>
											</a>
											<a class="col-6 col-md-4 p-0" href="#">
												<div class="quick-actions-item">
													<i class="flaticon-database"></i>
													<span class="text">Create New Database</span>
												</div>
											</a>
											<a class="col-6 col-md-4 p-0" href="#">
												<div class="quick-actions-item">
													<i class="flaticon-pen"></i>
													<span class="text">Create New Post</span>
												</div>
											</a>
											<a class="col-6 col-md-4 p-0" href="#">
												<div class="quick-actions-item">
													<i class="flaticon-interface-1"></i>
													<span class="text">Create New Task</span>
												</div>
											</a>
											<a class="col-6 col-md-4 p-0" href="#">
												<div class="quick-actions-item">
													<i class="flaticon-list"></i>
													<span class="text">Completed Tasks</span>
												</div>
											</a>
											<a class="col-6 col-md-4 p-0" href="#">
												<div class="quick-actions-item">
													<i class="flaticon-file"></i>
													<span class="text">Create New Invoice</span>
												</div>
											</a>
										</div>
									</div>
								</div>
							</div>
						</li>
						<li class="nav-item dropdown hidden-caret">
							<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
								<div class="avatar-sm">
									<img src="{{ asset('storage/photo-user/'.Auth::user()->photo) }}" alt="..." class="avatar-img rounded-circle">
								</div>
							</a>
							<ul class="dropdown-menu dropdown-user animated fadeIn">
								<div class="dropdown-user-scroll scrollbar-outer">
									<li>
										<div class="user-box">
											<div class="avatar-lg"><img src="{{ asset('storage/photo-user/'.Auth::user()->photo) }}" alt="image profile" class="avatar-img rounded"></div>
											<div class="u-text">
												<h4>{{Auth::user()->name;}}</h4>
												<p class="text-muted">hello@example.com</p><a href="profile.html" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
											</div>
										</div>
									</li>
									<li>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="#">My Profile</a>
										<a class="dropdown-item" href="#">My Balance</a>
										<a class="dropdown-item" href="#">Inbox</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="#">Account Setting</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="#">Logout</a>
									</li>
								</div>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
			<!-- End Navbar -->
		</div>

		<!-- Sidebar -->
		<div class="sidebar sidebar-style-2">			
			<div class="sidebar-wrapper scrollbar scrollbar-inner">
				<div class="sidebar-content">
					<div class="user">
						<div class="avatar-sm float-left mr-2">
							<img src="{{ asset('storage/photo-user/'.Auth::user()->photo) }}" alt="..." class="avatar-img rounded-circle">
						</div>
						<div class="info">
							<a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
								<span>
									<!-- {{Auth::user()->name;}} -->
									<span class="user-level">{{Auth::user()->name}}</span>
									<!-- <span class="user-level">Administrator</span> -->
									<span class="caret"></span>
								</span>
							</a>
							<div class="clearfix"></div>

							<div class="collapse in" id="collapseExample">
								<ul class="nav">
									<li>
										<a href="#profile')}}">
											<span class="link-collapse">My Profile</span>
										</a>
									</li>
									<li>
										<a href="#edit">
											<span class="link-collapse">Edit Profile</span>
										</a>
									</li>
									<li>
										<a href="#settings">
											<span class="link-collapse">Settings</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<ul class="nav nav-primary">
						<li class="nav-item {{\Route::is('admin.home') ? 'active' : ''}}">
							<a href="{{route('admin.home')}}" class="collapsed" aria-expanded="false">
								<i class="fas fa-home"></i>
								<p>Dashboard</p>
							</a>
						</li>
						@role('admin|STAF ADMIN')
						<li class="nav-item {{\Route::is('admin.mitra.*') ? 'active' : ''}}">
							<a href="{{route('admin.mitra.index')}}">
								<i class="fas fas fa-user-friends"></i>
								<p>Mitra</p>
							</a>
						</li>
						<li class="nav-item {{\Route::is('admin.vhc.*') ? 'active' : ''}}">
							<a data-toggle="collapse" href="#vhc">
								<i class="fas fa-wifi"></i>
								<p>Voucher</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="vhc">
								<ul class="nav nav-collapse">
									<li>
										<a href="{{route('admin.vhc.index')}}">
											<span class="sub-item">Profile Voucher</span>
										</a>
									</li>
									<li>
										<a href="{{route('admin.vhc.index')}}">
											<span class="sub-item">Stok Voucher</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item {{\Route::is('admin.psb.*') ? 'active' : ''}}">
							<a data-toggle="collapse" href="#base">
								<i class="fas fa-users"></i>
								<p>Pelanggan</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="base">
								<ul class="nav nav-collapse">
									<li>
										<a href="{{route('admin.psb.index')}}">
											<span class="sub-item">Berlangganan</span>
										</a>
									</li>
									<li>
										<a href="{{route('admin.psb.listputus_langganan')}}">
											<span class="sub-item">Putus Berlangganan</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item {{\Route::is('admin.tiket.*') ? 'active' : ''}}">
							<a href="{{route('admin.tiket.index')}}">
								<i class="fas fa-ticket-alt"></i>
								<p>Tiket</p>
							</a>
						</li>
						
						<li class="nav-item {{\Route::is('admin.inv.*') ? 'active' : ''}}">
							<a data-toggle="collapse" href="#transaksi">
								<i class="fas fa-random"></i>
								<p>Transaksi</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="transaksi">
								<ul class="nav nav-collapse">
									<li>
										<a href="{{route('admin.inv.index')}}">
											<span class="sub-item">Invoice Unpaid</span>
										</a>
									</li>
									<li>
										<a href="{{route('admin.inv.paid')}}">
											<span class="sub-item">Invoice Paid</span>
										</a>
									</li>
									<li>
										<a href="{{route('admin.inv.operasional')}}">
											<span class="sub-item">Pencairan PSB & Sales</span>
										</a>
									</li>
									<li>
										<a href="{{route('admin.inv.laporan')}}">
											<span class="sub-item">Laporan Harian</span>
										</a>
									</li>
									<li>
										<a href="{{route('admin.inv.trx.index')}}">
											<span class="sub-item">Transaksi</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item {{\Route::is('admin.gudang.*') ? 'active' : ''}}">
							<a href="{{route('admin.barang.index')}}">
								<i class="fas fa-list-alt"></i>
								<p>Barang</p>
							</a>
						</li>
						<li class="nav-item {{\Route::is('admin.wa.*') ? 'active' : ''}}">
							<a href="{{route('admin.wa.index')}}">
								<i class="fas fa-list-alt"></i>
								<p>whatsapp</p>
							</a>
						</li>
						@role('admin')
						<li class="nav-item">
							<a data-toggle="collapse" href="#charts">
								<i class="fas fa-cog"></i>
								<p>Setting</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="charts">
								<ul class="nav nav-collapse">
									<li>
										<a href="{{route('admin.user.index')}}">
											<span class="sub-item">User</span>
										</a>
									</li>
									<li>
										<a href="{{route('admin.app.index')}}">
											<span class="sub-item">Aplikasi</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						@endrole
						{{-- <li class="nav-item">
							<a data-toggle="collapse" href="#submenu">
								<i class="fas fa-bars"></i>
								<p>Menu Levels</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="submenu">
								<ul class="nav nav-collapse">
									<li>
										<a data-toggle="collapse" href="#subnav1">
											<span class="sub-item">Level 1</span>
											<span class="caret"></span>
										</a>
										<div class="collapse" id="subnav1">
											<ul class="nav nav-collapse subnav">
												<li>
													<a href="#">
														<span class="sub-item">Level 2</span>
													</a>
												</li>
												<li>
													<a href="#">
														<span class="sub-item">Level 2</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
									<li>
										<a data-toggle="collapse" href="#subnav2">
											<span class="sub-item">Level 1</span>
											<span class="caret"></span>
										</a>
										<div class="collapse" id="subnav2">
											<ul class="nav nav-collapse subnav">
												<li>
													<a href="#">
														<span class="sub-item">Level 2</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
									<li>
										<a href="#">
											<span class="sub-item">Level 1</span>
										</a>
									</li>
								</ul>
							</div>
						</li> --}}
						@endrole
						@role('admin|NOC')
						<li class="nav-item {{\Route::is('admin.noc.*') ? 'active' : ''}}">
							<a data-toggle="collapse" href="#sidebarNoc">
								<i class="fas fa-server"></i>
								<p>NOC</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="sidebarNoc">
								<ul class="nav nav-collapse">
									<li>
										<a href="{{route('admin.noc.index')}}">
											<span class="sub-item">Pengecekan</span>
										</a>
									</li>
									<li>
										<a href="{{route('admin.noc.index')}}">
											<span class="sub-item">Remote</span>
										</a>
									</li>
									<li>
										<a href="{{route('admin.noc.index')}}">
											<span class="sub-item">Cek Trafik</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item {{\Route::is('admin.router.*') ? 'active' : ''}}">
							<a data-toggle="collapse" href="#sidebarRouter">
								<i class="fas fa-server"></i>
								<p>Router</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="sidebarRouter">
								<ul class="nav nav-collapse">
									<li>
										<a href="{{route('admin.router.index')}}">
											<span class="sub-item">Router</span>
										</a>
									</li>
									@role('admin')
									<li>
										<a href="{{route('admin.router.paket.index')}}">
											<span class="sub-item">Paket</span>
										</a>
									</li>
									@endrole
								</ul>
							</div>
						</li>
						@endrole
						
						
						<li class="nav-item">
							<a  href="{{ route('logout') }}">
								<i class="fas fa-sign-out-alt"></i>
								<p>Logout</p>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Sidebar -->

		<div class="main-panel">
			
 @yield('content')
      
			<footer class="footer">
				<div class="container-fluid">
					<nav class="pull-left">
						<ul class="nav">
							<li class="nav-item">
								<a class="nav-link" href="https://www.themekita.com">
									ThemeKita
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">
									Help
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">
									Licenses
								</a>
							</li>
						</ul>
					</nav>
					<div class="copyright ml-auto">
						2018, made with <i class="fa fa-heart heart text-danger"></i> by <a href="https://www.themekita.com">ThemeKita</a>
					</div>				
				</div>
			</footer>
		</div>
		
		<!-- Custom template | don't include it in your project! -->
		<div class="custom-template">
			<div class="title">Settings</div>
			<div class="custom-content">
				<div class="switcher">
					<div class="switch-block">
						<h4>Logo Header</h4>
						<div class="btnSwitch">
							<button type="button" class="changeLogoHeaderColor" data-color="dark"></button>
							<button type="button" class="selected changeLogoHeaderColor" data-color="blue"></button>
							<button type="button" class="changeLogoHeaderColor" data-color="purple"></button>
							<button type="button" class="changeLogoHeaderColor" data-color="light-blue"></button>
							<button type="button" class="changeLogoHeaderColor" data-color="green"></button>
							<button type="button" class="changeLogoHeaderColor" data-color="orange"></button>
							<button type="button" class="changeLogoHeaderColor" data-color="red"></button>
							<button type="button" class="changeLogoHeaderColor" data-color="white"></button>
							<br/>
							<button type="button" class="changeLogoHeaderColor" data-color="dark2"></button>
							<button type="button" class="changeLogoHeaderColor" data-color="blue2"></button>
							<button type="button" class="changeLogoHeaderColor" data-color="purple2"></button>
							<button type="button" class="changeLogoHeaderColor" data-color="light-blue2"></button>
							<button type="button" class="changeLogoHeaderColor" data-color="green2"></button>
							<button type="button" class="changeLogoHeaderColor" data-color="orange2"></button>
							<button type="button" class="changeLogoHeaderColor" data-color="red2"></button>
						</div>
					</div>
					<div class="switch-block">
						<h4>Navbar Header</h4>
						<div class="btnSwitch">
							<button type="button" class="changeTopBarColor" data-color="dark"></button>
							<button type="button" class="changeTopBarColor" data-color="blue"></button>
							<button type="button" class="changeTopBarColor" data-color="purple"></button>
							<button type="button" class="changeTopBarColor" data-color="light-blue"></button>
							<button type="button" class="changeTopBarColor" data-color="green"></button>
							<button type="button" class="changeTopBarColor" data-color="orange"></button>
							<button type="button" class="changeTopBarColor" data-color="red"></button>
							<button type="button" class="changeTopBarColor" data-color="white"></button>
							<br/>
							<button type="button" class="changeTopBarColor" data-color="dark2"></button>
							<button type="button" class="selected changeTopBarColor" data-color="blue2"></button>
							<button type="button" class="changeTopBarColor" data-color="purple2"></button>
							<button type="button" class="changeTopBarColor" data-color="light-blue2"></button>
							<button type="button" class="changeTopBarColor" data-color="green2"></button>
							<button type="button" class="changeTopBarColor" data-color="orange2"></button>
							<button type="button" class="changeTopBarColor" data-color="red2"></button>
						</div>
					</div>
					<div class="switch-block">
						<h4>Sidebar</h4>
						<div class="btnSwitch">
							<button type="button" class="selected changeSideBarColor" data-color="white"></button>
							<button type="button" class="changeSideBarColor" data-color="dark"></button>
							<button type="button" class="changeSideBarColor" data-color="dark2"></button>
						</div>
					</div>
					<div class="switch-block">
						<h4>Background</h4>
						<div class="btnSwitch">
							<button type="button" class="changeBackgroundColor" data-color="bg2"></button>
							<button type="button" class="changeBackgroundColor selected" data-color="bg1"></button>
							<button type="button" class="changeBackgroundColor" data-color="bg3"></button>
							<button type="button" class="changeBackgroundColor" data-color="dark"></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Custom template -->
	</div>
	<!--   Core JS Files   -->
	<script src="{{asset('atlantis/assets/js/core/jquery.3.2.1.min.js')}}"></script>
	<script src="{{asset('atlantis/assets/js/core/popper.min.js')}}"></script>
	<script src="{{asset('atlantis/assets/js/core/bootstrap.min.js')}}"></script>

	<!-- jQuery UI -->
	<script src="{{asset('atlantis/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js')}}"></script>
	<script src="{{asset('atlantis/assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js')}}"></script>

	<!-- jQuery Scrollbar -->
	<script src="{{asset('atlantis/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>


	<!-- Chart JS -->
	<script src="{{asset('atlantis/assets/js/plugin/chart.js/chart.min.js')}}"></script>

	<!-- jQuery Sparkline -->
	<script src="{{asset('atlantis/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js')}}"></script>

	<!-- Chart Circle -->
	<script src="{{asset('atlantis/assets/js/plugin/chart-circle/circles.min.js')}}"></script>

	<!-- Datatables -->
	<script src="{{asset('atlantis/assets/js/plugin/datatables/datatables.min.js')}}"></script>

	<!-- Bootstrap Notify -->
	<script src="{{asset('atlantis/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js')}}"></script>

	<!-- jQuery Vector Maps -->
	<script src="{{asset('atlantis/assets/js/plugin/jqvmap/jquery.vmap.min.js')}}"></script>
	<script src="{{asset('atlantis/assets/js/plugin/jqvmap/maps/jquery.vmap.world.js')}}"></script>

	<!-- Sweet Alert -->
	<script src="{{asset('atlantis/assets/js/plugin/sweetalert/sweetalert.min.js')}}"></script>

	<!-- Atlantis JS -->
	<script src="{{asset('atlantis/assets/js/atlantis.min.js')}}"></script>


	<!-- Atlantis DEMO methods, don't include it in your project! -->
	{{-- <script src="{{asset('atlantis/assets/js/setting-demo.js')}}"></script>
	<script src="{{asset('atlantis/assets/js/demo.js')}}"></script> --}}


  {{-- JAVASCRIPT INPUT DATA REGIST PELANGGAN --}}
 <script >
		$(document).ready(function() {
	
			
			$('#input_data').DataTable({
				"pageLength": 10,
				
			});
			$('#pilih_data').DataTable({
				"pageLength": 10,
			});
			$('#tiket_pilih_pelanggan').DataTable({
				"pageLength": 10,
			});

			// #EDIT INPUT DATA
			var table = $('#edit_inputdata').DataTable(); $('#edit_inputdata tbody').on( 'click', 'tr', function () 
			{  
			var idpel = table.row( this ).id();

			
			var url = '{{ route("admin.psb.edit_inputdata", ":id") }}';
			url = url.replace(':id', idpel);
			console.log(idpel);
			$.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
						if (data) {
							$('#modal_edit').modal('show')
							// console.log(data[0]['input_nama'])
							$("#edit_id").val(data[0]['id']);
							$("#edit_input_nama").val(data[0]['input_nama']);
							$("#edit_input_hp").val(data[0]['input_hp']);
							$("#edit_input_ktp").val(data[0]['input_ktp']);
							$("#edit_input_email").val(data[0]['input_email']);
							$("#edit_input_alamat_ktp").val(data[0]['input_alamat_ktp']);
							$("#edit_input_alamat_pasang").val(data[0]['input_alamat_pasang']);
							$("#edit_input_seles").val(data[0]['input_seles']);
							$("#edit_input_subseles").val(data[0]['input_subseles']);
							$("#edit_input_maps").val(data[0]['input_maps']);
							$("#edit_input_keterangan").val(data[0]['input_keterangan']);			 
							$("#edit_input_status").val(data[0]['input_status']);			 
                        } else {
							
                        }
                    }
                });
 });
 
		});
	</script>

	<script>
		$(document).ready(function() {
} );
	</script>
	{{-- Start Router --}}
	<script>

function myFunction() {
      var x = document.getElementById("router_password");
      if (x.type === "password") {
		  x.type = "text";
		  alert(x.type)
		} else {
			x.type = "password";
			alert(x.type)
      }
    }

	$(function(){ //jQuery shortcut for .ready (ensures DOM ready)
		$('.navigateTest').click(function(){
			var idRouter = this.id;
			var url = '{{ route("admin.router.cekRouter", ":id") }}';
			url = url.replace(':id', idRouter);
			$("#myId").addClass('d-none');
			$("#loading").removeClass('d-none');
			$.ajax({
				url: url,
				type: 'GET',
				data: {
					'_token': '{{ csrf_token() }}'
				},
				dataType: 'json',
				success: function(data) {
					if (data) {
						if (data.resource=='error') {
							$("#loading").addClass('d-none');
							$("#alert").removeClass('d-none');
							document.getElementById("router_nama").innerHTML ='Router '+data.router_nama+' Disconnect';
						}else{
							$("#myId").removeClass('d-none');
							document.getElementById("resource").innerHTML =data.resource+'%';
							document.getElementById("architecture-name").innerHTML =data.architecture;
							document.getElementById("cpu").innerHTML =data.cpu;
							document.getElementById("version").innerHTML =data.version;
							$("#loading").addClass('d-none');
						}
					} else {
						
						$("#loading").addClass('d-none');
						$("#alert").removeClass('d-none');
						$("#myId").addClass('d-none');
					}
				}
			});
		});

		
		
		// START AMBIL HARGA PAKET #REGISTRASI


$('#paket').on('change', function() {
                var kode_paket = $(this).val();
                var url = '{{ route("admin.reg.getPaket", ":id") }}';
				url = url.replace(':id', kode_paket);
				$('#biaya_ppn').val(0);
				$(".checkboxppn").prop('checked', false);
				$('#biaya_kas').val(0);
				$(".checkboxkas").prop('checked', false);
				$('#kerjasama').val(0);
				$(".checkboxkerjasama").prop('checked', false);
                if (kode_paket) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
                            
                            if (data) {
                                $('#harga').empty();
                                $('#harga').val(data['data_paket'][0]['paket_harga']);
									$('.checkboxppn').change(function () {
									if ($(this).is(":checked")) {
										var result1 = parseInt(data['data_biaya']['biaya_ppn'])/100 * parseInt(data['data_paket'][0]['paket_harga']);
										if (!isNaN(result1)) {
											$('#biaya_ppn').val(result1);
										}
									} else {
										$("#biaya_ppn").val(0);
									}
								});
								$('.checkboxkas').change(function () {
									if ($(this).is(":checked")) {
										$('#biaya_kas').val(data['data_biaya']['biaya_kas']);
									} else {
										$("#biaya_kas").val(0);
									}
								});
								$('.checkboxkerjasama').change(function () {
									if ($(this).is(":checked")) {
										$('#kerjasama').val(data['data_biaya']['biaya_kerjasama']);
									} else {
										$("#kerjasama").val(0);
									}
								});
                            } else {
                                $('#harga').empty();
                            }
                        }
                    });
                } else {
                    $('#harga').empty();
                }
        });
      
// END AMBIL HARGA PAKET #REGISTRASI

var totalPrice = 0;
$("#ppn").html(0);
$('.checkboxClass').change(function () {
  if ($(this).is(":checked")) {
	
    ppn += parseFloat($(this).attr('data-price'));
    $("#ppn").val(ppn);
  } else {
    ppn -= parseFloat($(this).attr('data-price'));
    $("#ppn").val(ppn);
  }
});
// END CHECKBOX PPN #REGISTRASI

//  pilih registrasi
$(function(){ //jQuery shortcut for .ready (ensures DOM ready)
//  $('.pilih').click(function(){
//   var idpel = (this.id);
  var table = $('#input_data').DataTable(); $('#input_data tbody').on( 'click', 'tr', function () 
			{  
			var idpel = table.row( this ).id();
  var url = '{{ route("admin.reg.pilih_pelanggan_registrasi", ":id") }}';
url = url.replace(':id', idpel);
  $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
						
						if (data) {
							$("#cari_data").modal('hide');
							document.getElementById("tampil_hp").value =data['tampil_data']['input_hp'];
							document.getElementById("tampil_alamat_pasang").value =data['tampil_data']['input_alamat_pasang'];
							document.getElementById("tampil_idpel").value =data['tampil_data']['id'];
								document.getElementById("tampil_nama").value =data['tampil_data']['input_nama'];
								document.getElementById("tampil_nolay").value =data['nolay'];
								document.getElementById("tampil_username").value =data['username'];
								document.getElementById("tampil_maps").value =data['tampil_data']['input_maps'];
								document.getElementById("tampil_tgl").value =data['tampil_data']['input_tgl'];
								document.getElementById("tampil_subsales").value =data['tampil_data']['input_subseles'];
								document.getElementById("tampil_keterangan").value =data['tampil_data']['input_keterangan'];
								
                        } else {
							
                        }
                    }
                });
 });
});
// end pilih registrasi




});
		</script>
		
		{{-- End Router --}}

{{-- START VALISASI KODE BARANG REGISTRASI --}}

		<script>

			$(document).ready(function() {
				
				//Validasi Pactcore
				
			$("#pactcore").click(function() {
				if($(this).is(":checked")) {
					$('#modal_pactcore').modal('show')

				} else {
					$('#validasi').removeClass("has-error has-feedback");
					$('#validasi').removeClass("has-success");
					$("#kode_pactcore").val('');
					$('#notif').html('');
					$('#note').html('');
				}
			});
			$('.hide_pachcore').click(function(){
				$("#modal_pactcore").modal('hide');
				$("#pactcore").prop('checked', false);
				$('#validasi').removeClass("has-error has-feedback");
				$('#validasi').removeClass("has-success");
				$("#kode_pactcore").val('');
				$('#notif').html('');
				$('#note').html('');
			});

				$('.val_pachcore').click(function(){
					var kode_pact = $('#kode_pactcore').val();
					var url = '{{ route("admin.reg.validasi_pachcore", ":id") }}';
					url = url.replace(':id', kode_pact);
						$.ajax({
							url: url,
							type: 'GET',
							data: {
								'_token': '{{ csrf_token() }}'
							},
							dataType: 'json',
							success: function(data) {
								if(data.id_subbarang){
									$('#validasi').removeClass("has-error has-feedback");
									$("#validasi").addClass("has-success");
									$('#notif').html('');
									$("#modal_pactcore").modal('hide');
									$('#note').html('');
								}else{
									$("#validasi").addClass("has-error has-feedback");
									$('#notif').html('<small class="form-text text-muted text-danger">Kode Pactcore tidak ada atau telah digunakan</small>');
									$('#note').html('<ul><li>Pastikan kode belum digunkan</li><li>Pastikan kode terdaftar pada sistem</li><li>Kode yang dimasukan harus sesuai kategori barang</li></ul>');
								}
							},
							error: function(data) {
								$("#validasi").addClass("has-error has-feedback");
								$('#notif').html('<small class="form-text text-muted text-danger">Kode Pactcore tidak boleh kosong</small>');
								$('#note').html('<ul><li>Pastikan kode belum digunkan</li><li>Pastikan kode terdaftar pada sistem</li><li>Kode yang dimasukan harus sesuai kategori barang</li></ul>');
							}
						});
					});
				


				// #VALIDASI KODE ADAPTOR
			$("#adaptor").click(function() {
				if($(this).is(":checked")) {
					$('#modal_adaptor').modal('show')

				} else {
					$('#validasi_adp').removeClass("has-error has-feedback");
					$('#validasi_adp').removeClass("has-success");
					$("#kode_adaptor").val('');
					$('#notif_adp').html('');
					$('#note_adp').html('');
				}
			});
			$('.hide_adp').click(function(){
				$("#modal_adaptor").modal('hide');
				$("#adaptor").prop('checked', false);
				$('#validasi_adp').removeClass("has-error has-feedback");
				$('#validasi_adp').removeClass("has-success");
				$("#kode_adaptor").val('');
				$('#notif_adp').html('');
				$('#note_adp').html('');

			});


				$('.val_adp').click(function(){
					var kode_adp = $('#kode_adaptor').val();
					var url = '{{ route("admin.reg.validasi_adaptor", ":id") }}';
					url = url.replace(':id', kode_adp);
				$.ajax({
                    url: url,
                    type: 'GET',
                    data: {
						'_token': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
						if(data.id_subbarang){
							$('#validasi_adp').removeClass("has-error has-feedback");
							$("#validasi_adp").addClass("has-success");
							$('#notif_adp').html('');
							$("#modal_adaptor").modal('hide');
							$('#note_adp').html('');

						}else{
							$("#validasi_adp").addClass("has-error has-feedback");
							$('#notif_adp').html('<small class="form-text text-muted text-danger">Kode adaptor tidak ada atau telah digunakan</small>');
							$('#note_adp').html('<ul><li>Pastikan kode belum digunkan</li><li>Pastikan kode terdaftar pada sistem</li><li>Kode yang dimasukan harus sesuai kategori barang</li></ul>');
						}
                    },
					error: function(data) {
						$("#validasi_adp").addClass("has-error has-feedback");
						$('#notif_adp').html('<small class="form-text text-muted text-danger">Kode adaptor tidak boleh kosong</small>');
						$('#note_adp').html('<ul><li>Pastikan kode belum digunkan</li><li>Pastikan kode terdaftar pada sistem</li><li>Kode yang dimasukan harus sesuai kategori barang</li></ul>');
						
					}
				});
			});


			//Validasi ONT
				
			$("#ont").click(function() {
				if($(this).is(":checked")) {
					$('#modal_ont').modal('show')
					$('.ont').attr('required', 'required');

				} else {
					$('#validasi_ont').removeClass("has-error has-feedback");
					$('#validasi_ont').removeClass("has-success");
					$("#kode_ont").val('');
					$('#notif_ont').html('');
					$('#note_ont').html('');
					$('#reg_mac').val('');
					$('#reg_sn').val('');
					$('#reg_mrek').val('');
					$('.ont').removeAttr('required');
				}
			});
			$('.hide_ont').click(function(){
				$("#modal_ont").modal('hide');
				$("#ont").prop('checked', false);
				$('#validasi_ont').removeClass("has-error has-feedback");
				$('#validasi_ont').removeClass("has-success");
				$("#kode_ont").val('');
				$('#notif_ont').html('');
				$('#note_ont').html('');
				$('#reg_mac').val('');
									$('#reg_sn').val('');
									$('#reg_mrek').val('');
			});

				$('.val_ont').click(function(){
					var kode_pact = $('#kode_ont').val();
					var url = '{{ route("admin.reg.validasi_ont", ":id") }}';
					url = url.replace(':id', kode_pact);
						$.ajax({
							url: url,
							type: 'GET',
							data: {
								'_token': '{{ csrf_token() }}'
							},
							dataType: 'json',
							success: function(data) {
								if(data.id_subbarang){
									
									$('#validasi_ont').removeClass("has-error has-feedback");
									$("#validasi_ont").addClass("has-success");
									$('#notif_ont').html('');
									$("#modal_ont").modal('hide');
									$('#note_ont').html('');
									$('#reg_mac').val(data.subbarang_mac);
									$('#reg_sn').val(data.subbarang_sn);
									$('#reg_mrek').val(data.subbarang_nama);
								}else{
									$("#validasi_ont").addClass("has-error has-feedback");
									$('#notif_ont').html('<small class="form-text text-muted text-danger">Kode ont tidak ada atau telah digunakan</small>');
									$('#note_ont').html('<ul><li>Pastikan kode belum digunkan</li><li>Pastikan kode terdaftar pada sistem</li><li>Kode yang dimasukan harus sesuai kategori barang</li></ul>');
									$('#reg_mac').val('');
									$('#reg_sn').val('');
									$('#reg_mrek').val('');
								}
							},
							error: function(data) {
								$("#validasi_ont").addClass("has-error has-feedback");
								$('#notif_ont').html('<small class="form-text text-muted text-danger">Kode ont tidak boleh kosong</small>');
								$('#note_ont').html('<ul><li>Pastikan kode belum digunkan</li><li>Pastikan kode terdaftar pada sistem</li><li>Kode yang dimasukan harus sesuai kategori barang</li></ul>');
								$('#reg_mac').val('');
									$('#reg_sn').val('');
									$('#reg_mrek').val('');
							}
						});
					});


		});
			
			



			
			
			
			
			
			
				</script>
{{-- END VALISASI KODE BARANG REGISTRASI --}}
{{-- START VALISASI KODE BARANG REGISTRASI=EDIT --}}

		<script>

			$(document).ready(function() {
			$("#edit_ont").click(function() {
				if($(this).is(":checked")) {
					$('#edit_modal_ont').modal('show')
					$('.edit_ont').attr('required', 'required');

				} else {
					$('#edit_validasi_ont').removeClass("has-error has-feedback");
					$('#edit_validasi_ont').removeClass("has-success");
					$("#edit_kode_ont").val('');
					$('#edit_notif_ont').html('');
					$('#edit_note_ont').html('');
					$('#edit_reg_mac').val('');
					$('#edit_reg_sn').val('');
					$('#edit_reg_mrek').val('');
					$('#alasan').val('');
					$('#keterangan').val('');
					$('.edit_ont').removeAttr('required');
					$("#edit_validasi_keterangan").removeClass("has-error has-feedback");
								$('#edit_notif_keterangan').html('');
								$("#edit_validasi_alasan").removeClass("has-error has-feedback");
						$('#edit_notif_alasan').html('');
				}
			});
			$('.edit_hide_ont').click(function(){
				$("#edit_modal_ont").modal('hide');
				$("#edit_ont").prop('checked', false);
				$('#edit_validasi_ont').removeClass("has-error has-feedback");
				$('#edit_validasi_ont').removeClass("has-success");
				$("#edit_kode_ont").val('');
				$('#edit_notif_ont').html('');
				$('#edit_note_ont').html('');
				$('#edit_reg_mac').val('');
				$('#edit_reg_sn').val('');
				$('#edit_reg_mrek').val('');
			});

				$('.edit_val_ont').click(function(){

					if($('#alasan').val()=== ""){
						$("#edit_validasi_alasan").addClass("has-error has-feedback");
						$('#edit_notif_alasan').html('<small class="form-text text-muted text-danger">Alasan tidak boleh kosong</small>');
					}  else {
						$('#edit_validasi_alasan').removeClass("has-error has-feedback");
						$('#edit_notif_alasan').html('');
						if($('#keterangan').val()=== ""){
								$("#edit_validasi_keterangan").addClass("has-error has-feedback");
								$('#edit_notif_keterangan').html('<small class="form-text text-muted text-danger">keterangan tidak boleh kosong</small>');
							} else {
								$('#edit_validasi_keterangan').removeClass("has-error has-feedback");
								$('#edit_notif_keterangan').html('');
								var kode_pact = $('#edit_kode_ont').val();
					var url = '{{ route("admin.reg.validasi_ont", ":id") }}';
					url = url.replace(':id', kode_pact);
					
						$.ajax({
							url: url,
							type: 'GET',
							data: {
								'_token': '{{ csrf_token() }}'
							},
							dataType: 'json',
							success: function(data) {
								if(data.id_subbarang){
									
									$('#edit_validasi_ont').removeClass("has-error has-feedback");
									$("#edit_validasi_ont").addClass("has-success");
									$('#edit_notif_ont').html('');
									$("#edit_modal_ont").modal('hide');
									$('#edit_note_ont').html('');
									$('#edit_reg_mac').val(data.subbarang_mac);
									$('#edit_reg_sn').val(data.subbarang_sn);
									$('#edit_reg_mrek').val(data.subbarang_nama);
								}else{
									$("#edit_validasi_ont").addClass("has-error has-feedback");
									$('#edit_notif_ont').html('<small class="form-text text-muted text-danger">Kode ont tidak ada atau telah digunakan</small>');
									$('#edit_note_ont').html('<ul><li>Pastikan kode belum digunkan</li><li>Pastikan kode terdaftar pada sistem</li><li>Kode yang dimasukan harus sesuai kategori barang</li></ul>');
									$('#edit_reg_mac').val('');
									$('#edit_reg_sn').val('');
									$('#edit_reg_mrek').val('');
								}
							},
							error: function(data) {
								$("#edit_validasi_ont").addClass("has-error has-feedback");
								$('#edit_notif_ont').html('<small class="form-text text-muted text-danger">Kode ont tidak boleh kosong</small>');
								$('#edit_note_ont').html('<ul><li>Pastikan kode belum digunkan</li><li>Pastikan kode terdaftar pada sistem</li><li>Kode yang dimasukan harus sesuai kategori barang</li></ul>');
								$('#edit_reg_mac').val('');
									$('#edit_reg_sn').val('');
									$('#edit_reg_mrek').val('');
							}
						});
							}
					}
				});


		});
			
			



			
			
			
			
			
			
				</script>
{{-- END VALISASI KODE BARANG REGISTRASI=EDIT --}}


		{{-- TAMBAH PAKET --}}
		<script>
			$(document).ready(function() {
				var button = $('#Button');
    			$(button).attr('disabled', 'disabled');
				$('#paket_router').on('change', function() {
					var kode_roter = $(this).val();
					var url = '{{ route("admin.router.paket.getRouter", ":id") }}';
					url = url.replace(':id', kode_roter);
					if (kode_roter) {
						$(button).attr('disabled', 'disabled');
						$('#badge').empty();
						$('#pool').empty();
						$.ajax({
							url: url,
                    type: 'GET',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data) {
                                $('#badge').append('<h6><span class="badge bg-success">Connected</span></h6>');
                                $.each(data, function(key, pool) {
                                    $('select[name="pool"]').append(
										'<option value="' + pool.name + '">' +
											pool.name + '</option>'
											);
										});
										$(button).removeAttr('disabled');

                        } else {
                            $('#pool').empty();
                        }
                    }
                });
            } else {
              $('#pool').empty();
            }
 });
});
</script>




{{-- END TAMBAH PAKET --}}
	<script>
//  NOTIFIKASI
@if (Session::has('pesan'))
swal("{{Session::get('alert')}}!", "{{Session::get('pesan')}}", {
						icon : "{{Session::get('alert')}}",
						buttons: {        			
							confirm: {
								className : 'btn btn-success'
							}
						},
					});
@endif

	  function validasiKtp() {
			var input_ktp =$("#input_ktp").val();

			var url = '{{ route("admin.psb.storeValidateKtp", ":ktp") }}';
			url = url.replace(':ktp', '1');
			$.ajax({
				url: url,
				type: 'PUT',
				data: {
                    input_ktp:input_ktp,
                          '_token': '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
							
                                     },
						error: function(response){
							$.each( response.responseJSON.errors, function( key, value ) {
								console.log(value);has-error
                    });
                }
                    });
				}
				
				//  #START EDIT BARANG 
			</script>
				<script type="text/javascript">
					$(document).ready(function(){
					  $("#qty, #harga,#editharga,#editqty").keyup(function() {
							  var harga  = $("#harga").val();
							  var jumlah = $("#qty").val();
				  
							  var total = parseInt(harga) * parseInt(jumlah);
							  if (isNaN(total)) {
								  total = '0';
								  }
							  $("#total").val(total);
							  var editharga  = $("#editharga").val();
							  var editqty = $("#editqty").val();
				  
							  var edittotal = parseInt(editharga) * parseInt(editqty);
							  if (isNaN(total)) {
								edittotal = '0';
								  }
							  $("#edittotal").val(edittotal);
					  });
					});
					//  END EDIT BARANG
					</script>
					<script>
						// START EDIT HREF PSB
						$('.href').click(function(){
							var id =$(this).data("id");
							var url = '{{ route("admin.psb.edit_pelanggan", ":id") }}';
							url = url.replace(':id', id);
							// alert(url);
							window.location=url;
						});
					</script>
					<script>
						// START DETAIL INVOICE
						$('.href_inv').click(function(){
							var id =$(this).data("id");
							var url = '{{ route("admin.inv.sub_invoice", ":id") }}';
							url = url.replace(':id', id);
							// alert(url);
							window.location=url;
						});
					</script>
					<script>
						// #DINVOICE
						$(function() {
    enable_cb();
    $("#persen").click(enable_cb);
  });
	function enable_cb() {
    if (this.checked) {
      var persen = $(".inv_ppn").val();
      var harga  = $("#harga").val();
              var qty = $("#qty").val();
              var jumlah = parseInt(qty) * parseInt(harga)
              var ppn = jumlah*persen/100 ;
              var total = jumlah + ppn;
              if (isNaN(total)) {
                  total = '0';
                  }
                  if(isNaN(ppn)){
                    ppn = '0';
                  }
              $("#total").val(total);
              $("#ppn").val(ppn);
          $("#qty, #harga").keyup(function() {
              var harga  = $("#harga").val();
              var qty = $("#qty").val();
              var jumlah = parseInt(qty) * parseInt(harga)
              var ppn = jumlah*persen/100 ;
              var total = jumlah + ppn;
              if (isNaN(total)) {
                  total = '0';
                  }
                  if(isNaN(ppn)){
                    ppn = '0';
                  }
              $("#total").val(total);
              $("#ppn").val(ppn);
          });
    } else {
      var harga  = $("#harga").val();
              var qty = $("#qty").val();
              var total = (parseInt(qty) * parseInt(harga)) ;
              if (isNaN(total)) {
                  total = '0';
                  }
                  if(isNaN(ppn)){
                    ppn = '0';
                  }
              $("#total").val(total);
              $("#ppn").val(ppn);
      $("#qty, #harga").keyup(function() {
              var harga  = $("#harga").val();
              var qty = $("#qty").val();
              var total = (parseInt(qty) * parseInt(harga)) ;
              if (isNaN(total)) {
				  total = '0';
				}
				if(isNaN(ppn)){
                    ppn = '0';
                  }
              $("#total").val(total);
              $("#ppn").val(ppn);
          });
    }
  }

  $('#submit_diskon').click(function(e) {
        e.preventDefault();
    var diskon = $('#diskon').val();
    var idi = $('#inv_id').val();
    var harga = $('#inv_harga').val();
    var jumlah = $('#inv_jumlah').val();
    var total = jumlah-diskon;
    var url = '{{ route("admin.inv.addDiskon", ":id") }}';
    url = url.replace(':id', idi);
                $.ajax({
                  url: url,
                  type: 'PUT',
                  data: {
                    harga:harga,
                    jumlah:jumlah,
                          diskon:diskon,
                          total:total,
                          '_token': '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {

							swal("Berhasil!", "Diskon berhasil ditambahkan", {
						icon : "success",
						buttons: {        			
							confirm: {
								className : 'btn btn-success'
							}
						},
					});
                            let rupiahFormat = new Intl.NumberFormat('id-ID', {
                              style: 'currency',minimumFractionDigits: 0,
                              currency: 'IDR',
                            }).format(diskon);

                            let jumlah = new Intl.NumberFormat('id-ID', {
                              style: 'currency',minimumFractionDigits: 0,
                              currency: 'IDR',
                            }).format(total);
							
                            const row1 = document.getElementById('tot');
                            const ww = document.getElementById('td');
                            row1.innerHTML = `
                          <td>`
                            +jumlah+`
                          </td>`;
                          ww.innerHTML = `
                          <td>`
                            +rupiahFormat+`
                            </td>`;
                            table.appendChild(ww);
                            table.appendChild(row1);


                        }
                    });
					
				});
				
  
  
	$('select[name=cabar]').change(function () {
		if ($(this).val() == 'TRANSFER') {
			$('#transfer').show();
			$('#transfer').attr('required', 'required');;
			$('#jb').show();
			$('#bb').show();
			$('#jb').attr('required', 'required');;
			$('#bb').attr('required', 'required');;
		} else {
			$('#transfer').hide();
			$('#transfer').removeAttr('required');
			$('#jb').hide();
			$('#bb').hide();
			$('#jb').removeAttr('required');
			$('#bb').removeAttr('required');
		}
	});
// #LAYANAN REGISTRASI
	$('select[name=reg_layanan]').change(function () {
		if ($(this).val() == 'HOTSPOT') {
			$('#divip').hide();
			$(".hotspot").val("");
			$(".hotspot").keyup(function () {
			var value = $(this).val();
			$(".pwhotspot").val(value);
			}).keyup();
			$('.pwhotspot').attr('readonly', 'readonly');;
		} else {
			$('#divip').show();
		}
	});

    
	</script>
	<script>
	$('.whatsapp').change(function () {
		if ($(this).is(":checked")) {
			$('#wa').html('Enable');
			$('.whatsapp').val('Enable');
		} else {
			$('.whatsapp').val('Disable');
			$("#wa").html('Disable');
			alert('Disable');
		}
	});
		</script>
		<script>
			// BUAT TIKET
			$(function(){ 
  var table = $('#tiket_pilih_pelanggan').DataTable(); $('#tiket_pilih_pelanggan tbody').on( 'click', 'tr', function () 
			{  
			var idpel = table.row( this ).id();
  var url = '{{ route("admin.tiket.pilih_pelanggan", ":id") }}';
url = url.replace(':id', idpel);
  $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
						// console.log(data)
							$("#cari_data").modal('hide');
							$("#tiket_pelanggan").val(data['data_pelanggan']['input_nama']);
							$("#tiket_nolayanan").val(data['data_pelanggan']['reg_nolayanan']);
							$("#tiket_idpel").val(data['data_pelanggan']['reg_idpel']);
                    }
                });
 });
});
		</script>
		<script>
			// DETAILS TIKET
			$('.tiket').click(function(){
				var id =$(this).data("id");
				var url = '{{ route("admin.tiket.details", ":id") }}';
				url = url.replace(':id', id);
				// alert(url);
				window.location=url;
			});
		</script>

		

	
					<script>
		Circles.create({
			id:'circles-1',
			radius:45,
			value:60,
			maxValue:100,
			width:7,
			text: 5,
			colors:['#f1f1f1', '#FF9E27'],
			duration:400,
			wrpClass:'circles-wrp',
			textClass:'circles-text',
			styleWrapper:true,
			styleText:true
		})

		Circles.create({
			id:'circles-2',
			radius:45,
			value:70,
			maxValue:100,
			width:7,
			text: 36,
			colors:['#f1f1f1', '#2BB930'],
			duration:400,
			wrpClass:'circles-wrp',
			textClass:'circles-text',
			styleWrapper:true,
			styleText:true
		})

		Circles.create({
			id:'circles-3',
			radius:45,
			value:40,
			maxValue:100,
			width:7,
			text: 12,
			colors:['#f1f1f1', '#F25961'],
			duration:400,
			wrpClass:'circles-wrp',
			textClass:'circles-text',
			styleWrapper:true,
			styleText:true
		})

		var totalIncomeChart = document.getElementById('totalIncomeChart').getContext('2d');

		var mytotalIncomeChart = new Chart(totalIncomeChart, {
			type: 'bar',
			data: {
				labels: ["S", "M", "T", "W", "T", "F", "S", "S", "M", "T"],
				datasets : [{
					label: "Total Income",
					backgroundColor: '#ff9e27',
					borderColor: 'rgb(23, 125, 255)',
					data: [6, 4, 9, 5, 4, 6, 4, 3, 8, 10],
				}],
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				legend: {
					display: false,
				},
				scales: {
					yAxes: [{
						ticks: {
							display: false //this will remove only the label
						},
						gridLines : {
							drawBorder: false,
							display : false
						}
					}],
					xAxes : [ {
						gridLines : {
							drawBorder: false,
							display : false
						}
					}]
				},
			}
		});

		$('#lineChart').sparkline([105,103,123,100,95,105,115], {
			type: 'line',
			height: '70',
			width: '100%',
			lineWidth: '2',
			lineColor: '#ffa534',
			fillColor: 'rgba(255, 165, 52, .14)'
		});
	</script>
</body>
</html>
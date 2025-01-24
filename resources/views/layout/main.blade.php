	


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
	   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">
	   {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" /> --}}

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
						
						<li class="nav-item dropdown hidden-caret">
							<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
								<div class="avatar-sm">
									<img src="@if(asset('storage/photo-user/'.Auth::user()->photo))" {{ asset('storage/photo-user/'.Auth::user()->photo) }} @else {{ asset('storage/photo-user/user.png') }} @endif alt="..." class="avatar-img rounded-circle">
								</div>
							</a>
							<ul class="dropdown-menu dropdown-user animated fadeIn">
								<div class="dropdown-user-scroll scrollbar-outer">
									<li>
										<div class="user-box">
											<div class="avatar-lg"><img src="@if(asset('storage/photo-user/'.Auth::user()->photo))" {{ asset('storage/photo-user/'.Auth::user()->photo) }} @else {{ asset('storage/photo-user/user.png') }} @endif alt="..." class="avatar-img rounded-circle">
											</div>
											
											<div class="u-text">
												<h4>{{Auth::user()->name;}}</h4>
											</div>
										</div>
									</li>
									<li>
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
							<img src="@if(asset('storage/photo-user/'.Auth::user()->photo))" {{ asset('storage/photo-user/'.Auth::user()->photo) }} @else {{ asset('storage/photo-user/user.png') }} @endif alt="..." class="avatar-img rounded-circle">
						</div>
						<div class="info">
							<a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
								<span>
									<span class="user-level">{{Auth::user()->name}}</span>
									<span class="user-level">{{Session::get('nama_roles')}}</span>
								</span>
							</a>
							{{-- <div class="clearfix"></div> --}}
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
									<li>
										<a href="{{route('admin.vhc.titik_vhc')}}">
											<span class="sub-item">Titik Voucher</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						@endrole
						@role('admin|STAF ADMIN|NOC')
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
						@endrole
						@role('admin|STAF ADMIN')
						<li class="nav-item {{\Route::is('admin.tiket.*') ? 'active' : ''}}">
							<a href="{{route('admin.tiket.data_tiket')}}">
								<i class="fas fa-ticket-alt"></i>
								<p>Tiket</p>
							</a>
						</li>
						
						<li class="nav-item {{\Route::is('admin.lap.*') ? 'active' : ''}}">
							<a data-toggle="collapse" href="#keuangan">
								<i class="fas fa-random"></i>
								<p>Keuangan</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="keuangan">
								<ul class="nav nav-collapse">
									<li>
										<a href="{{route('admin.lap.jurnal')}}">
											<span class="sub-item">Jurnal</span>
										</a>
									</li>
									<li>
										<a href="{{route('admin.lap.pinjaman')}}">
											<span class="sub-item">Pinjaman</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item {{\Route::is('admin.inv.*') ? 'active' : ''}}">
							<a data-toggle="collapse" href="#transaksi">
								<i class="fas fa-receipt"></i>
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
							<a href="{{route('admin.gudang.data_barang')}}">
								<i class="fas fa-list-alt"></i>
								<p>Gudang</p>
							</a>
						</li>
						<li class="nav-item {{\Route::is('admin.wa.*') ? 'active' : ''}}">
							<a href="{{route('admin.wa.index')}}">
								<i class="fab fa-whatsapp"></i>
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
									<li>
										<a href="{{route('admin.app.site')}}">
											<span class="sub-item">Site</span>
										</a>
									</li>
									<li>
										<a href="{{route('admin.app.kendaraan')}}">
											<span class="sub-item">Kendaraan</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						@endrole
						@endrole
						@role('admin|NOC')
						<li class="nav-item {{\Route::is('admin.topologi.*') ? 'active' : ''}}">
							<a data-toggle="collapse" href="#sidebartopologi">
								<i class="fas fa-server"></i>
								<p>Data Topologi</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="sidebartopologi">
								<ul class="nav nav-collapse">
									<li>
										<a href="{{route('admin.topo.pop')}}">
											<span class="sub-item">POP</span>
										</a>
									</li>
									<li>
										<a href="{{route('admin.topo.olt')}}">
											<span class="sub-item">OLT</span>
										</a>
									</li>
									<li>
										<a href="{{route('admin.topo.odc')}}">
											<span class="sub-item">ODC</span>
										</a>
									</li>
									<li>
										<a href="{{route('admin.topo.odp')}}">
											<span class="sub-item">ODP</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
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
										<a href="{{route('admin.noc.pengecekan_barang')}}">
											<span class="sub-item">Pengecekan-barang</span>
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

	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

	{{-- <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script> --}}
	
	
	<!-- Atlantis DEMO methods, don't include it in your project! -->
	{{-- <script src="{{asset('atlantis/assets/js/setting-demo.js')}}"></script>
	<script src="{{asset('atlantis/assets/js/demo.js')}}"></script> --}}


  <script >
	//--------------------START FORMAT MAC----------------------
	  $(document).ready(function() {
		  //format mac adrress Aktivasi pelanggan
		  document.getElementById("mac").addEventListener('keyup', function() { 
			  this.value = 
			  (this.value.toUpperCase()
			  .replace(/[^\d|A-Z]/g, '')
			  .match(/.{1,2}/g) || [])
			  .join(":")
			});
		});
	//--------------------END FORMAT MAC----------------------

	//--------------------START INPUT READONLY----------------------
	$(document).ready(function() {
		$('.readonly').keydown(function(e) {
			if (e.keyCode === 8 || e.keyCode === 46)  // Backspace & del
			e.preventDefault();
		}).on('keypress paste cut', function(e) {
			e.preventDefault();
		});
	});
	//--------------------END INPUT READONLY----------------------

	//--------------------START TABLE CLICK EDIT DATA PELANGGAN----------------------
	// PsbController -> index.blade.php					
						$('.href').click(function(){
							var id =$(this).data("id");
							var url = '{{ route("admin.reg.form_data_pelanggan", ":id") }}';
							url = url.replace(':id', id);
							window.location=url;
						});
	//--------------------END TABLE CLICK EDIT DATA PELANGGAN----------------------

	//--------------------START TABLE CLICK AKTIVASI DATA PELANGGAN----------------------
	// RegistrasiController -> index.blade.php					
						$('.aktivasi').click(function(){
							var id =$(this).data("id");
							var url = '{{ route("admin.reg.aktivasi_pelanggan", ":id") }}';
							url = url.replace(':id', id);
							window.location=url;
						});
	//--------------------END TABLE CLICK AKTIVASI DATA PELANGGAN----------------------
		$(document).ready(function() {
	
			
			$('#datatable').DataTable({
				"pageLength": 10,
				
			});


			$('#input_data').DataTable({
				"pageLength": 10,
			});
			$('#cari_kode_barang').DataTable({
				"pageLength": 10,
			});
			$('#pilih_data').DataTable({
				"pageLength": 10,
			});
			$('#pencairan_list').DataTable({
				"pageLength": 10,
			});
			$('#topup_list').DataTable({
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
			// console.log(idpel);
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
							$("#edit_input_hp2").val(data[0]['input_hp_2']);
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
$(function(){ 
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
						// console.log(data)
						if (data) {
							$("#cari_data").modal('hide');
							document.getElementById("tampil_hp").value =data['tampil_data']['input_hp'];
							document.getElementById("tampil_hp2").value =data['tampil_data']['input_hp_2'];
							document.getElementById("tampil_alamat_pasang").value =data['tampil_data']['input_alamat_pasang'];
							document.getElementById("tampil_idpel").value =data['tampil_data']['id'];
								document.getElementById("tampil_nama").value =data['tampil_data']['input_nama'];
								document.getElementById("tampil_nolay").value =data['nolay'];
								document.getElementById("tampil_username").value =data['username'];
								document.getElementById("tampil_maps").value =data['tampil_data']['input_maps'];
								document.getElementById("tampil_sales").value =data['tampil_data']['input_sales'];
								document.getElementById("tampil_sales_nama").value =data['tampil_data']['user_nama'];
								document.getElementById("tampil_tgl").value =data['tampil_data']['input_tgl'];
								document.getElementById("tampil_subsales").value =data['tampil_data']['input_subseles'];
								document.getElementById("tampil_keterangan").value =data['tampil_data']['input_keterangan'];
								
                        } else {
							
                        }
                    }
                });
			});
		});

				


			

//--------DROPDONW SITE-ROUTER-POP-ODC-ODP------------------
	$('#site').on('change', function() {
	var site = $(this).val();
	var url = '{{ route("admin.reg.getSite", ":id") }}';
	url = url.replace(':id', site);
	// console.log(site)
	$.ajax({
		url: url,
				type: 'GET',
							data: {
								'_token': '{{ csrf_token() }}'
							},
							dataType: 'json',
							success: function(data) {
								if(data){
									$('#pop').empty()
									$('#pop').append('<option value="">- Pilih POP -</option>')
									$('#olt').empty()
									$('#olt').append('<option value="">- Pilih OLT -</option>')
									$('#odc').empty()
									$('#odc').append('<option value="">- Pilih ODC -</option>')
									$('#router').empty()
									$('#router').append('<option value="">- Pilih Router -</option>')
									
									for (let i = 0; i < data.length; i++) {
										$('#pop').append('<option value="'+data[i].pop_id+'">'+data[i].pop_nama+'</option>');
									}

									//--------- start get olt-------------
									$('#pop').on('change', function() {
										var olt = $(this).val();
										// console.log(olt)
										var url = '{{ route("admin.reg.getOlt", ":id") }}';
										url = url.replace(':id', olt);
										
										$.ajax({
													url: url,
													type: 'GET',
													data: {
																	'_token': '{{ csrf_token() }}'
																},
																dataType: 'json',
																success: function(data) {
																	if(data){
																		console.log(olt);
																		// console.log(data)
																		$('#olt').empty()
																		$('#olt').append('<option value="">- Pilih OLT -</option>')
																		$('#odc').empty()
																		$('#odc').append('<option value="">- Pilih ODC -</option>')
																		for (let i = 0; i < data.length; i++) {
																			$('#olt').append('<option value="'+data[i].olt_id+'">'+data[i].olt_nama+'</option>');
																		}
																		//--------- start get odc-------------
									$('#olt').on('change', function() {
										var odc = $(this).val();
										var url = '{{ route("admin.reg.getOdc", ":id") }}';
										url = url.replace(':id', odc);
										$.ajax({
													url: url,
													type: 'GET',
													data: {'_token': '{{ csrf_token() }}'},
																dataType: 'json',
																success: function(data) {
																	if(data){
																		
																		$('#odc').empty()
																		$('#odc').append('<option value="">- Pilih ODC -</option>')
																		for (let i = 0; i < data.length; i++) {
																			$('#odc').append('<option value="'+data[i].odc_id+'">'+data[i].odc_nama+'</option>');
																		}
																	} else{
																		$('#odc').append('<option value="">- Data tidak ditemukan -</option>')
																	}
																},
																error: function(error) {
																	$('#odc').append('<option value="">- Pilih ODC -</option>')
																},
															});
									});
																		//--------- end get odc-------------
																	} else{
																		$('#olt').append('<option value="">- Data tidak ditemukan -</option>')
																	}
																},
																error: function(error) {
																	$('#olt').append('<option value="">- Pilih OLT -</option>')
																},
															});
									});
																		//--------- end get olt-------------

									$('.pop_router').on('change', function() {
										var pop_router = $(this).val();
										var url = '{{ route("admin.reg.getPop", ":id") }}';
										url = url.replace(':id', pop_router);
										
											$.ajax({
													url: url,
													type: 'GET',
																data: {
																	'_token': '{{ csrf_token() }}'
																},
																dataType: 'json',
																success: function(data) {
																	if(data){
																		$('#router').empty()
																		$('#router').append('<option value="">- Pilih Router -</option>')
																		for (let i = 0; i < data.length; i++) {
																			$('#router').append('<option value="'+data[i].id+'">'+data[i].router_nama+'</option>');
																		}
																		
																	} else{
																		$('#router').append('<option value="">- Data tidak ditemukan -</option>')
																	}
																},
																error: function(error) {
																	$('#router').append('<option value="">- Pilih Router -</option>')
																},
															});
											});
									
								} else{
									$('#pop').append('<option value="">- Data tidak ditemukan -</option>')
								}
							},
							error: function(error) {
								$('#pop').append('<option value="">- Pilih POP -</option>')
							},
	
						});
});

//--------BATAS AKHIR DROPDONW SITE-ROUTER-POP-ODC-ODP------------------

//
//--------START DROPDONW AKTIVASI SITE-ROUTER-POP-ODC-ODP------------------




// validasi_odp

$('#validasi_odp').keyup(function() {
var validasi_odp = $('#validasi_odp').val();
var url = '{{ route("admin.reg.validasi_odp", ":id") }}';
url = url.replace(':id', validasi_odp);
// console.log(validasi_odp)
$.ajax({
	url: url,
			type: 'GET',
			data: {'_token': '{{ csrf_token() }}'},
						dataType: 'json',
						success: function(data) {
							console.log(data.odp_kode)
							if(data.odp_kode){
								$('#validasi_olt').val(data.olt_kode)
								$('#validasi_odc').val(data.odc_kode)
								$('.read').readOnly = true
								$('.notif').removeClass('has-error has-feedback')
								$('.notif').addClass('has-success has-feedback')
								$('#pesan').html('')
							} else{
								$('#validasi_odc').val('')
								$('#validasi_olt').val('')
								$('.notif').addClass('has-error has-feedback')
								$('#pesan').html('<small id="text" class="form-text text-muted text-danger">Kode ODP tidak ditemukan</small>')
								}
						},
						error: function(error) {
							console.log(error)

						},
					});
});

//validasi kode kabel
$("#reg_kode_dropcore").keyup(function(){
	var kode_kabel =$("#reg_kode_dropcore").val();
	// console.log(kode_kabel)
        var url = '{{ route("admin.val.valBarang", ":id") }}';
    url = url.replace(':id', kode_kabel);
	$.ajax({
		url: url,
                        type: 'GET',
                        data: {
                          kode_kabel: kode_kabel,
                            '_token': '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
							// console.log(data.barang_qty - data.barang_digunakan)
                            if (data.barang_kategori == 'DROPCORE') {
								if (data.barang_qty - data.barang_digunakan  > '0') {

								var after  = $("#after").val();
								// var before = $("#before").val();
								$('.notif_kabel').removeClass('has-error has-feedback')
								$('.notif_kabel').addClass('has-success has-feedback')
								$('#pesan_kabel').html('')
								$("#before").val(data.barang_qty-data.barang_digunakan);
								}
                              } else {
                                $('#before').val('');
                                $("#total").val('');
								$('.notif_kabel').addClass('has-error has-feedback')
								$('#pesan_kabel').html('<small id="text" class="form-text text-muted text-danger">Kode Kabel tidak ditemukan / tidak sesuai kategori</small>')
                              }
                            },
                            error: function(error){
								$('#before').val('');
                                $("#total").val('');
								$('.notif_kabel').addClass('has-error has-feedback')
								$('#pesan_kabel').html('<small id="text" class="form-text text-muted text-danger">Kode Kabel tidak ditemukan / tidak sesuai kategori</small>')
                              }
                        
                    });
      });

	  $(document).ready(function() {
            $("#before, #after").keyup(function() {
                var after  = $("#after").val();
                var before = $("#before").val();
    
                var total = parseInt(before) - parseInt(after);
                if (isNaN(total)) {
                    hasil = '';
					} else{
						hasil = total;
						if(hasil<0){
						$("#total").val('');
						$('.notif_over').addClass('has-error has-feedback') //NOTIF UNTUK AKTIVASI
						$('.tiket_notif_3').addClass('has-error has-feedback') //NOTIF UNTUK UPDATE TIKET
						$('#pesan_over').html('<small id="text" class="form-text text-muted text-danger">Panjang kabel tidak cukup</small>')
						$('#tiket_pesan_3').html('<small id="text" class="form-text text-muted text-danger">Panjang kabel tidak cukup</small>')
						
					}else{
						$('#pesan_over').html('')
						$('.notif_over').removeClass('has-error has-feedback')//NOTIF UNTUK AKTIVASI
						$('.tiket_notif_3').removeClass('has-error has-feedback') //NOTIF UNTUK UPDATE TIKET
						$('.notif_over').addClass('has-success has-feedback')//NOTIF UNTUK AKTIVASI
						$('.tiket_notif_3').addClass('has-success has-feedback')//NOTIF UNTUK UPDATE TIKET
						$("#total").val(hasil);
                    }
					}

            });
            });

		



//--------- start get kode site-------------
									$('#pop_kode').on('change', function() {
										var pop_kode = $(this).val();
										var url = '{{ route("admin.reg.getKodeSite", ":id") }}';
										url = url.replace(':id', pop_kode);
										console.log(pop_kode)
										$.ajax({
													url: url,
													type: 'GET',
													data: {
																	'_token': '{{ csrf_token() }}'
																},
																dataType: 'json',
																success: function(data) {
																	console.log(data)
																	if(data){
																		$('#aktivasi_odc').empty()
																		$('#aktivasi_odc').append('<option value="">- Pilih ODC -</option>')
																		for (let i = 0; i < data.length; i++) {
																			$('#aktivasi_odc').append('<option value="'+data[i].odc_id+'">'+data[i].odc_nama+'</option>');
																		}
																	} else{
																		$('#aktivasi_odc').append('<option value="">- Data tidak ditemukan -</option>')
																	}
																},
																error: function(error) {
																	$('#aktivasi_odc').append('<option value="">- Pilih ODC -</option>')
																},
															});
									});
																		//--------- end get kode site-------------






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
								if(data.barang_id){
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
						// console.log(data)
						if(data.barang_id){
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
									$('#reg_nama_barang').val('');
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
								// console.log(data)
								if(data.barang_id){
									
									$('#validasi_ont').removeClass("has-error has-feedback");
									$("#validasi_ont").addClass("has-success");
									$('#notif_ont').html('');
									$("#modal_ont").modal('hide');
									$('#note_ont').html('');
									$('#reg_mac').val(data.barang_mac);
									$('#reg_sn').val(data.barang_sn);
									$('#reg_mrek').val(data.barang_merek);
									$('#reg_nama_barang').val(data.barang_nama);
								}else{
									$("#validasi_ont").addClass("has-error has-feedback");
									$('#notif_ont').html('<small class="form-text text-muted text-danger">Kode ont tidak ada atau telah digunakan</small>');
									$('#note_ont').html('<ul><li>Pastikan kode belum digunkan</li><li>Pastikan kode terdaftar pada sistem</li><li>Kode yang dimasukan harus sesuai kategori barang</li></ul>');
									$('#reg_mac').val('');
									$('#reg_sn').val('');
									$('#reg_mrek').val('');
									$('#reg_nama_barang').val('');
								}
							},
							error: function(data) {
								$("#validasi_ont").addClass("has-error has-feedback");
								$('#notif_ont').html('<small class="form-text text-muted text-danger">Kode ont tidak boleh kosong</small>');
								$('#note_ont').html('<ul><li>Pastikan kode belum digunkan</li><li>Pastikan kode terdaftar pada sistem</li><li>Kode yang dimasukan harus sesuai kategori barang</li></ul>');
								$('#reg_mac').val('');
									$('#reg_sn').val('');
									$('#reg_mrek').val('');
									$('#reg_nama_barang').val('');
							}
						});
					});


		});
			
			



			
			
			
			
			
			
				</script>
{{-- END VALISASI KODE BARANG REGISTRASI --}}
{{-- START VALISASI KODE BARANG REGISTRASI=EDIT

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
			
				</script> --}}
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

	//   function validasiKtp() {
	// 		var input_ktp =$("#input_ktp").val();

	// 		var url = '{{ route("admin.psb.storeValidateKtp", ":ktp") }}';
	// 		url = url.replace(':ktp', '1');
	// 		$.ajax({
	// 			url: url,
	// 			type: 'PUT',
	// 			data: {
    //                 input_ktp:input_ktp,
    //                       '_token': '{{ csrf_token() }}'
    //                     },
    //                     dataType: 'json',
    //                     success: function(data) {
							
    //                                  },
	// 					error: function(response){
	// 						$.each( response.responseJSON.errors, function( key, value ) {
	// 							console.log(value);has-error
    //                 });
    //             }
    //                 });
	// 			}
				
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

	/////---------------------------------------------------------------UPDATE TIKET--------------------------------------------------
	$(document).ready(function() {
		$('.submit_tiket').attr('disabled','disabled');
		$('select[name=tiket_status]').change(function () {
			if ($(this).val() == 'Pending') {
				$('.submit_tiket').attr('disabled');
				$('.div_tiket_pending').show();
				$('.div_tiket_closed').hide();
				$('#tiket_pending').attr('required', 'required');
				$('.tiket_waktu_penanganan').hide();
				$('#tiket_waktu_penanganan').removeAttr('required');
				$('#div_tiket_kendala').removeAttr('required');
				$('#tiket_tindakan').removeAttr('required');
				$('#tiket_teknisi1').attr('required', 'required');
				$('#tiket_teknisi2').attr('required', 'required');
				$('.submit_tiket').removeAttr('disabled');
				//PACTCORE nama_ganti_adaptor
				$("#ck_ganti_pactcore").prop("checked", false);
				$('.div_ganti_pactcore').hide();
				$('#ganti_pactcore').removeAttr('required');
				$('#nama_ganti_pactcore').removeAttr('required');
				$('#ganti_pactcore').val('');
				$('#nama_ganti_pactcore').val('');
				//ADAPTOR
				$("#ck_ganti_adptor").prop("checked", false);
				$('.div_ganti_adptor').hide();
				$('#ganti_adptor').removeAttr('required');
				$('#ganti_adptor').val('');
				$('#nama_ganti_adaptor').removeAttr('required');
				$('#nama_ganti_adaptor').val('');
				
				//ONT
				$("#ck_ganti_ont").prop("checked", false);
				$('.div_ganti_ont').hide();
				$('#ganti_ont').removeAttr('required');
				$('#ganti_ont').val('');
				$('#tiket_sn').removeAttr('required');
				$('#tiket_sn').val('');
				$('#tiket_mac').removeAttr('required');
				$('#tiket_mac').val('');
				$('#tiket_nama_barang4').removeAttr('required');
				$('#tiket_nama_barang4').val('');
				//DROPCORE
				$("#ck_ganti_dropcore").prop("checked", false);
				$('.div_ganti_dropcore').hide();
				$('#ganti_dropcore').removeAttr('required');
				$('#before').removeAttr('required');
				$('#after').removeAttr('required');
				$('#total').removeAttr('required');
				$('#ganti_dropcore').val('');
				$('#before').val('')
				$('#after').val('')
				$('#total').val('')
				
				
				
			} else if ($(this).val() == 'Closed') {
				$('.submit_tiket').attr('disabled','disabled');
				$('.div_tiket_closed').show();
				$('.div_tiket_pending').hide();
				$('#tiket_waktu_penanganan').attr('required', 'required');
				$('#tiket_teknisi1').attr('required', 'required');
				$('#tiket_teknisi2').attr('required', 'required');
				$('#tiket_kendala').attr('required', 'required');
				$('#tiket_tindakan').attr('required', 'required');
				$('#tiket_pending').removeAttr('required');
				//---------------GANTI PACTCORE---------------
				$("#ck_ganti_pactcore").click(function() {
				$('.submit_tiket').removeAttr('disabled');
				if($(this).is(":checked")) {
					$('.div_ganti_pactcore').show();
					$('#ganti_pactcore').attr('required', 'required');
					$('#nama_ganti_pactcore').attr('required', 'required');
					// ---------------VALIDASI GANTI PACTCORE---------------
					$('#ganti_pactcore').keyup(function() {
						var ganti_pactcore = $('#ganti_pactcore').val();
						var url = '{{ route("admin.val.valBarang", ":id") }}';
						url = url.replace(':id', ganti_pactcore);
						$.ajax({
							url: url,
							type: 'get',
							data: {'_token': '{{ csrf_token() }}'
                        },
							dataType: 'json',
							success: function(data) {
								if(data.barang_kategori == 'PACTCORE'){
									if(data.barang_qty - data.barang_digunakan > 0){
										$('.tiket_pesan_1').html('')
										$('.tiket_notif_1').removeClass('has-error has-feedback')
										$('.tiket_notif_1').addClass('has-success has-feedback')
										$('.submit_tiket').removeAttr('disabled');
										$('#nama_ganti_pactcore').val(data.barang_nama)
									} else{
										$('#nama_ganti_pactcore').val('')
										$('.submit_tiket').attr('disabled','disabled');
										$('.tiket_notif_1').addClass('has-error has-feedback')
										$('.tiket_pesan_1').html('<small id="text" class="form-text text-muted text-danger">Stok barang ini habis</small>')
									}
								} else{
									$('#nama_ganti_pactcore').val('')
									$('.submit_tiket').attr('disabled','disabled');
									$('.tiket_notif_1').addClass('has-error has-feedback')
									$('.tiket_pesan_1').html('<small id="text" class="form-text text-muted text-danger">Kode tidak sesuai kategori</small>')
								}
						},
					});
				});
				// ---------------END VALIDASI GANTI PACTCORE---------------
			} else {
				$('.submit_tiket').attr('disabled','disabled');
				$('.div_ganti_pactcore').hide();
				$('#ganti_pactcore').removeAttr('required');
				$('#nama_ganti_pactcore').removeAttr('required');
				$('#ganti_pactcore').val('');
				$('#nama_ganti_pactcore').val('');
				
			}
		});
		//------------END GANTI PACTCORE---------------
				//---------------GANTI ADAPTOR---------------
				$("#ck_ganti_adaptor").click(function() {
				$('.submit_tiket').removeAttr('disabled');
				if($(this).is(":checked")) {
					$('.div_ganti_adaptor').show();
					$('#ganti_adaptor').attr('required', 'required');
					$('#nama_ganti_adaptor').attr('required', 'required');
					// ---------------VALIDASI GANTI ADAPTOR---------------
					$('#ganti_adaptor').keyup(function() {
						var ganti_adaptor = $('#ganti_adaptor').val();
						var url = '{{ route("admin.val.valBarang", ":id") }}';
						url = url.replace(':id', ganti_adaptor);
						$.ajax({
							url: url,
							type: 'get',
							data: {'_token': '{{ csrf_token() }}'},
							dataType: 'json',
							success: function(data) {
								if(data.barang_kategori == 'ADAPTOR'){
									if(data.barang_qty - data.barang_digunakan > 0){
										$('.tiket_pesan_2').html('')
										$('.tiket_notif_2').removeClass('has-error has-feedback')
										$('.tiket_notif_2').addClass('has-success has-feedback')
										$('.submit_tiket').removeAttr('disabled');
										$('#nama_ganti_adaptor').val(data.barang_nama)
									} else{
										$('#nama_ganti_adaptor').val('')
										$('.submit_tiket').attr('disabled','disabled');
										$('.tiket_notif_2').addClass('has-error has-feedback')
										$('.tiket_pesan_2').html('<small id="text" class="form-text text-muted text-danger">Kode Pactcore ditemukan / Stok habis</small>')
									}
								} else{
										$('#nama_ganti_adaptor').val('')
										$('.submit_tiket').attr('disabled','disabled');
										$('.tiket_notif_2').addClass('has-error has-feedback')
										$('.tiket_pesan_2').html('<small id="text" class="form-text text-muted text-danger">Kode Pactcore ditemukan / Stok habis</small>')
									}
						},
					});
				});
				// ---------------END VALIDASI GANTI ADAPTOR---------------
			} else {
				$('.submit_tiket').attr('disabled','disabled');
				$('.div_ganti_adaptor').hide();
				$('#ganti_adaptor').removeAttr('required');
				$('#ganti_adaptor').val('');
				$('#nama_ganti_adaptor').removeAttr('required');
				$('#nama_ganti_adaptor').val('');
				
			}
		});
		//------------END GANTI ADAPTOR---------------
				//---------------GANTI DROPCORE---------------
				$("#ck_ganti_dropcore").click(function() {
				$('.submit_tiket').removeAttr('disabled');
				if($(this).is(":checked")) {
					$('.div_ganti_dropcore').show();
					$('#ganti_dropcore').attr('required', 'required');
					$('#before').attr('required', 'required');
					$('#after').attr('required', 'required');
					$('#total').attr('required', 'required');
					// ---------------VALIDASI GANTI DROPCORE---------------
					$('#ganti_dropcore').keyup(function() {
						var ganti_dropcore = $('#ganti_dropcore').val();
						var url = '{{ route("admin.val.valBarang", ":id") }}';
						url = url.replace(':id', ganti_dropcore);
						$.ajax({
							url: url,
							type: 'GET',
							data: {'_token': '{{ csrf_token() }}'},
							dataType: 'json',
							success: function(data) {
								if(data.barang_kategori == 'DROPCORE'){
									if(data.barang_qty - data.barang_digunakan > 0){
										$('.tiket_pesan_3').html('')
										$('.tiket_notif_3').removeClass('has-error has-feedback')
										$('.tiket_notif_3').addClass('has-success has-feedback')
										$('.submit_tiket').removeAttr('disabled');
										$('#before').val(data.barang_qty - data.barang_digunakan)
									} else{
										$('#before').val('')
										$('#after').val('')
										$('#total').val('')
										$('.submit_tiket').attr('disabled','disabled');
										$('.tiket_notif_3').addClass('has-error has-feedback')
										$('.tiket_pesan_3').html('<small id="text" class="form-text text-muted text-danger">Kode barang ini habis</small>')
									}
								} else{
									$('#before').val('')
									$('#after').val('')
									$('#total').val('')
									$('.submit_tiket').attr('disabled','disabled');
									$('.tiket_notif_3').addClass('has-error has-feedback')
									$('.tiket_pesan_3').html('<small id="text" class="form-text text-muted text-danger">Kode tidak sesuai kategori</small>')
								}
								
						},
					});
				});
				// ---------------END VALIDASI GANTI DROPCORE---------------
			} else {
				$('.submit_tiket').attr('disabled','disabled');
				$('.div_ganti_dropcore').hide();
				$('#ganti_dropcore').removeAttr('required');
				$('#ganti_dropcore').val('')
				$('#before').val('')
				$('#after').val('')
				$('#total').val('')
			}
		});
		//------------END GANTI DROPCORE---------------
				//---------------GANTI ONT---------------
				$("#ck_ganti_ont").click(function() {
				$('.submit_tiket').removeAttr('disabled');
				if($(this).is(":checked")) {
					$('.div_ganti_ont').show();
					$('#ganti_ont').attr('required', 'required');
					$('#tiket_nama_barang4').attr('required', 'required');
					$('#tiket_mac').attr('required', 'required');
					$('#tiket_sn').attr('required', 'required');
					// ---------------VALIDASI GANTI ONT---------------
					$('#ganti_ont').keyup(function() {
						var ganti_ont = $('#ganti_ont').val();
						var url = '{{ route("admin.val.valBarang", ":id") }}';
						url = url.replace(':id', ganti_ont);
						$.ajax({
							url: url,
							type: 'GET',
							data: {'_token': '{{ csrf_token() }}'},
							dataType: 'json',
							success: function(data) {
								if(data.barang_kategori == 'ONT'){
									if(data.barang_qty - data.barang_digunakan > 0){
										$('.tiket_pesan_4').html('')
										$('.tiket_notif_4').removeClass('has-error has-feedback')
										$('.tiket_notif_4').addClass('has-success has-feedback')
										$('.submit_tiket').removeAttr('disabled');
										$('#tiket_nama_barang4').val(data.barang_nama)
										$('#tiket_mac').val(data.barang_mac)
										$('#tiket_sn').val(data.barang_sn)
									} else{
										$('#tiket_nama_barang4').val('')
										$('#tiket_mac').val('')
										$('#tiket_sn').val('')
										$('.submit_tiket').attr('disabled','disabled');
										$('.tiket_notif_4').addClass('has-error has-feedback')
										$('.tiket_pesan_4').html('<small id="text" class="form-text text-muted text-danger">Kode barang ini habis</small>')
									}
								} else{
									$('#tiket_nama_barang4').val('')
									$('#tiket_mac').val('')
									$('#tiket_sn').val('')
									$('.submit_tiket').attr('disabled','disabled');
									$('.tiket_notif_4').addClass('has-error has-feedback')
									$('.tiket_pesan_4').html('<small id="text" class="form-text text-muted text-danger">Kode tidak sesuai kategori</small>')
								}
								
						},
					});
				});
				// ---------------END VALIDASI GANTI ONT---------------
			} else {
				$('.submit_tiket').attr('disabled','disabled');
				$('.div_ganti_ont').hide();
				$('#ganti_ont').removeAttr('required');
				$('#ganti_ont').val('')
				$('#tiket_nama_barang4').val('')
				$('#tiket_mac').val('')
				$('#tiket_sn').val('')
			}
		});
		//------------END GANTI ONT---------------
				//---------------GANTI ONT---------------
				$("#ck_lain_lain").click(function() {
					if($(this).is(":checked")) {
						$('.submit_tiket').removeAttr('disabled');
					} else {
						$('.submit_tiket').attr('disabled','disabled');
					}
				});
				//------------END GANTI ONT---------------
				
			}
		});
	});

	//----------------------END TIKET
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
			// console.log(data['data_pelanggan']['input_nama'])
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
				var url = '{{ route("admin.tiket.details_tiket", ":id") }}';
				url = url.replace(':id', id);
				// alert(url);
				window.location=url;
			});
			// DETAILS TIKET CLOSED
			$('.tiket_closed').click(function(){
				var id =$(this).data("id");
				var url = '{{ route("admin.tiket.details_tiket_closed", ":id") }}';
				url = url.replace(':id', id);
				// alert(url);
				window.location=url;
			});
		</script>
    <script>
		//TOPUP
        document.getElementById('selectAllCheckbox')
                  .addEventListener('change', function () {
            let checkboxtopup = document.querySelectorAll('.checkboxtopup');
            checkboxtopup.forEach(function (checkbox) {
                checkbox.checked = this.checked;
            }, this);
        });
		
		let addonCheckboxes = document.querySelectorAll(".checkboxtopup")
let priceSection = document.getElementById("priceSection")
let customProductPricing = document.getElementById("customProductPricing")
let sum = 0
for (let i = 0; i < addonCheckboxes.length; i++) {
  addonCheckboxes[i].addEventListener("change", function(e) {

    console.log(e.target.dataset.price)
    
    if (addonCheckboxes[i].checked != false) {
      
      sum = sum +Number(e.target.dataset.price) 
	  
    } else {
      sum =  sum -Number(e.target.dataset.price) 
    }
	let rupiahFormat = new Intl.NumberFormat('id-ID', {
                              style: 'currency',minimumFractionDigits: 0,
                              currency: 'IDR',
                            }).format(sum);
    
    customProductPricing.innerHTML = rupiahFormat
    
  })

}


		$('.topup').click(function(){  
			var id=$(".id_lap").val();//getting value of input field
			var url = '{{ route("admin.inv.lap_topup", ":id") }}';
			url = url.replace(':id', id);
			var checkboxtopup_value = []; 
			$('.checkboxtopup').each(function(){  
				//if($(this).is(":checked")) { 
					if(this.checked) {              
						checkboxtopup_value.push($(this).val());                                                                               
					}  
				});                              
				// checkboxtopup_value = checkboxtopup_value.toString(); 
				// alert(checkboxtopup_value)

				$.ajax({
                    url: url,
                    type: 'PUT',
                    data: {
						checkboxtopup_value:checkboxtopup_value,
                        '_token': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {

					window.location.href = data+"/admin/Transaksi/laporan-harian";
                    }
                });
		});  
			
			
			</script>
    <script>
		// pencairan
        // document.getElementById('selectAllpencairan')
        //           .addEventListener('change', function () {
        //     let pencairan = document.querySelectorAll('.pencairan');
        //     pencairan.forEach(function (checkbox) {
        //         checkbox.checked = this.checked;
        //     }, this);
        // });
		
		////PENCAIRAN OPERASIONAL
		let cb_pencairan = document.querySelectorAll(".cb_pencairan")
let total_pencairan = document.getElementById("total_pencairan")
let sum1 = 0
for (let i = 0; i < cb_pencairan.length; i++) {
  cb_pencairan[i].addEventListener("change", function(e) {   
	console.log(e.target.dataset.price) 
	  if (cb_pencairan[i].checked != false) {
		  sum1 = sum1 +Number(e.target.dataset.price) 
    } else {
      sum1 =  sum1 -Number(e.target.dataset.price) 
    }
	let rupiah_pencairan = new Intl.NumberFormat('id-ID', {
                              style: 'currency',minimumFractionDigits: 0,
                              currency: 'IDR',
                            }).format(sum1);
    
							total_pencairan.innerHTML = rupiah_pencairan
			
  })
}
		//////PENCAIRAN JURNAL
		let jurnal_pencairan = document.querySelectorAll(".jurnal_pencairan")
let pencairan_total = document.getElementById("pencairan_total")
let cpsb = 0
let csales = 0
let sum2 = 0
let sum3 = 0
let result = "";
for (let i = 0; i < jurnal_pencairan.length; i++) {
  jurnal_pencairan[i].addEventListener("change", function(e) {   
	  if (jurnal_pencairan[i].checked != false) {
		  //   console.log('e.target.nama : ' + JSON.stringify(e.target.dataset.nama))  
		  result += e.target.dataset.nama
		  + " " + ", ";
		  console.log(i)
		  sum2 = sum2 +Number(e.target.dataset.psb) 
		  sum3 = sum3 +Number(e.target.dataset.sales) 
		  cpsb = cpsb +Number(e.target.dataset.cpsb) 
		  csales = csales +Number(e.target.dataset.csales) 
    } else {
		cpsb = cpsb -Number(e.target.dataset.cpsb) 
		csales = csales -Number(e.target.dataset.csales) 
      sum2 =  sum2 -Number(e.target.dataset.psb) 
      sum3 =  sum3 -Number(e.target.dataset.sales) 
    }
	$("#uraian").val(result);
	$("#psb").val(sum2);
	$("#sales").val(sum3);
	$("#cpsb").val(cpsb);
	$("#csales").val(csales);
	$("#jumlah").val(sum2+sum3);
	let rupiah_pencairan = new Intl.NumberFormat('id-ID', {
                              style: 'currency',minimumFractionDigits: 0,
                              currency: 'IDR',
                            }).format(sum2+sum3);
							$(".pencairan_total").val();
							pencairan_total.innerHTML = rupiah_pencairan
			
  })
}
		//////PENCAIRAN JURNAL
		let pencairan_fee = document.querySelectorAll(".pencairan_fee")
let pencairan_total_fee = document.getElementById("pencairan_total_fee")
let count_fee = 0
let total_komisi = 0
let desk = "";
for (let i = 0; i < pencairan_fee.length; i++) {
  pencairan_fee[i].addEventListener("change", function(e) {   
	  if (pencairan_fee[i].checked != false) {
		  //   console.log('e.target.nama : ' + JSON.stringify(e.target.dataset.nama))  
		  
		//   desk += e.target.dataset.pelanggan
		//   + " " + ", ";

		  total_komisi = total_komisi +Number(e.target.dataset.fee) 
		  count_fee = count_fee +Number(e.target.dataset.count_fee)
		  desk +=count_fee + ". "+ e.target.dataset.pelanggan
		  + "\n"; 
    } else {
		total_komisi =  total_komisi -Number(e.target.dataset.fee) 
		count_fee = count_fee -Number(e.target.dataset.count_fee) 
    }
	$("#desk").text(desk);
	$("#fee").val(e.target.dataset.fee);
	$("#sales_id").val(e.target.dataset.sales_id);
	$("#total_komisi").val(total_komisi);
	$("#count_fee").val(count_fee);
	let rupiah_pencairan = new Intl.NumberFormat('id-ID', {
                              style: 'currency',minimumFractionDigits: 0,
                              currency: 'IDR',
                            }).format(total_komisi);
							$(".pencairan_total_fee").val();
							pencairan_total_fee.innerHTML = rupiah_pencairan
			
  })
}

				
				$('.submit_pencairan').click(function(){  
					var akun=$(".akun").val();
					var penerima=$(".penerima").val();
					var idpel = []; 
					if(idpel==''){
						$('#notif3').html('<small class="form-text text-muted text-danger">Metode Bayar tidak boleh kosong</small>');
					} else{
						$('#notif3').html('');
					}
					if(akun==''){
						$('#notif1').html('<small class="form-text text-muted text-danger">Metode Bayar tidak boleh kosong</small>');
					} else{
						$('#notif1').html('');
					}

					if(penerima==''){
						$('#notif2').html('<small class="form-text text-muted text-danger">Penerima tidak boleh kosong</small>');
					} else{
						$('#notif2').html('');
					}


			var url = '{{ route("admin.inv.konfirm_pencairan") }}';
			$('.cb_pencairan').each(function(){  
					if(this.checked) {              
						idpel.push($(this).val());                                                                               
					}  
				});                              

				$.ajax({
                    url: url,
                    type: 'PUT',
                    data: {
						idpel:idpel,
						penerima:penerima,
						akun:akun,
                        '_token': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
						location.reload();
						if(data['saldo_tidak_cukup']){
							
							swal("Berhasil!", "Diskon berhasil ditambahkan", {
								icon : "error",
						buttons: {        			
							confirm: {
								className : 'btn btn-error'
							}
						},
					});
					}else{
						swal("Berhasil!", "Diskon berhasil ditambahkan", {
								icon : "success",
						buttons: {        			
							confirm: {
								className : 'btn btn-success'
							}
						},
					});
					}
                    }
                });
		});  
		//////AKUMULASI JURNAL
		let jurnal_akumulasi = document.querySelectorAll(".jurnal_akumulasi")
let akumulasi_total = document.getElementById("akumulasi_total")
let sum_akum = 0
let sum_count = 0;
for (let i = 0; i < jurnal_akumulasi.length; i++) {
  jurnal_akumulasi[i].addEventListener("change", function(e) {   
	  if (jurnal_akumulasi[i].checked != false) {
		//   console.log('e.target.nama : ' + JSON.stringify(e.target.dataset.count))  
		  sum_count = sum_count +Number(e.target.dataset.count) 
		  sum_akum = sum_akum +Number(e.target.dataset.price_akum) 
		} else {
		sum_count = sum_count -Number(e.target.dataset.count) 
      sum_akum =  sum_akum -Number(e.target.dataset.price_akum) 
    }
	$("#jumlah_count").val(sum_count);
	$("#jumlah_akum").val(sum_akum);
	let rupiah_akumulasi = new Intl.NumberFormat('id-ID', {
                              style: 'currency',minimumFractionDigits: 0,
                              currency: 'IDR',
                            }).format(sum_akum);
							$(".akumulasi_total").val();
							akumulasi_total.innerHTML = rupiah_akumulasi
			
  })
}

				
				$('.submit_pencairan').click(function(){  
					var akun=$(".akun").val();
					var penerima=$(".penerima").val();
					var idpel = []; 
					if(idpel==''){
						$('#notif3').html('<small class="form-text text-muted text-danger">Metode Bayar tidak boleh kosong</small>');
					} else{
						$('#notif3').html('');
					}
					if(akun==''){
						$('#notif1').html('<small class="form-text text-muted text-danger">Metode Bayar tidak boleh kosong</small>');
					} else{
						$('#notif1').html('');
					}

					if(penerima==''){
						$('#notif2').html('<small class="form-text text-muted text-danger">Penerima tidak boleh kosong</small>');
					} else{
						$('#notif2').html('');
					}


			var url = '{{ route("admin.inv.konfirm_pencairan") }}';
			$('.cb_pencairan').each(function(){  
					if(this.checked) {              
						idpel.push($(this).val());                                                                               
					}  
				});                              

				$.ajax({
                    url: url,
                    type: 'PUT',
                    data: {
						idpel:idpel,
						penerima:penerima,
						akun:akun,
                        '_token': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
						location.reload();
						if(data['saldo_tidak_cukup']){
							
							swal("Berhasil!", "Diskon berhasil ditambahkan", {
								icon : "error",
						buttons: {        			
							confirm: {
								className : 'btn btn-error'
							}
						},
					});
					}else{
						swal("Berhasil!", "Diskon berhasil ditambahkan", {
								icon : "success",
						buttons: {        			
							confirm: {
								className : 'btn btn-success'
							}
						},
					});
					}
                    }
                });
		});  
			

		$(function () {
  $('.datepicker').datepicker({
    language: "es",
    autoclose: true,
    format: "dd-mm-yyyy",
  });
  $('.datepicker_waktu').datepicker({
    language: "es",
    autoclose: true,
    format: "dd-mm-yyyy",
	timeFormat: 'hh:mm tt'
  });
});
		$(function () {
  $('.update_datepicker').datepicker({
    language: "es",
    autoclose: true,
    format: "dd-mm-yyyy",
	startDate: '+1d',
	endDate: '+15d'
  });
});




$("#update-tgl").change(function() {
    var d = $(this).datepicker("getDate");
	var date =new Date(d).toLocaleDateString("es-CL");
	var month = d.getMonth() + 1;

	var now = new Date();
	var month_now = now.getMonth() + 1;
    
	
	if(month > month_now ){
		var status = month-month_now; 
	}
	// console.log(status);
	//awal
var id=$("#tampil_idpel").val();
var tagihan=$("#biaya-tagihan").val();
var url = '{{ route("admin.psb.get_update_tgl_tempo", ":id") }}';
    url = url.replace(':id', id);
	$.ajax({
		url: url,
                  type: 'PUT',
                  data: {
                    date:date,
                    status:status,
                          '_token': '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
							console.log(data);

							let rupiahFormat = new Intl.NumberFormat('id-ID', {
								style: 'currency',minimumFractionDigits: 0,
                              	currency: 'IDR',
                            }).format(data['total_bayar']);
							$("#total_biaya").html(rupiahFormat);
							$("#rincian").html(data['rincian']);
							$("#total_biaya_val").val(data['total_bayar']);
							$("#biaya").val(data['biaya']);
							$("#hari").val(data['hari']);
							$("#update_ppn").val(data['update_ppn']);
							$("#status").val(data['status']);//jika status 1 maka tidak ditambah addons melainkan di rubah harga
				            }
				});

//akhir
});
			</script>

			<script>
					//--------------------START DEAKTIVASI----------------------
					$(document).ready(function() {
					// 
					$('select[name=deaktivasi_kelengkapan_perangkat]').change(function () {
						if ($(this).val() == 'ONT') {
							$('.pernyataan_1').hide()
							$('.pernyataan_2').hide()
							$('.div_ont').show();
							$('.deaktivasi_mac').attr('required', 'required');
							$('#deaktivasi_sn').attr('required', 'required');
							$('#deaktivasi_pernyataan1').attr('required', 'required');
							$('#deaktivasi_pengambil_perangkat').attr('required', 'required');
							$('#deaktivasi_alasan_deaktivasi').attr('required', 'required');
							$('#deaktivasi_tanggal_pengambilan').attr('required', 'required');
							$('.pernyataan_1').show()
						} else if($(this).val() == 'ONT & Adaptor') { 
							$('.pernyataan_1').hide()
							$('.pernyataan_2').hide()
							$('.div_ont').show();
							$('.deaktivasi_mac').attr('required', 'required');
							$('#deaktivasi_sn').attr('required', 'required');
							$('#deaktivasi_pengambil_perangkat').attr('required', 'required');
							$('#deaktivasi_alasan_deaktivasi').attr('required', 'required');
							$('#deaktivasi_tanggal_pengambilan').attr('required', 'required');
						} else if($(this).val() == 'Hilang') { 
							$('.pernyataan_1').hide()
							$('.pernyataan_2').show()
							$('.div_ont').hide();
							$('#deaktivasi_pernyataan1').attr('required', 'required');
							$('#deaktivasi_pernyataan2').attr('required', 'required');
							$('#deaktivasi_pengambil_perangkat').attr('required', 'required');
							$('#deaktivasi_alasan_deaktivasi').attr('required', 'required');
							$('#deaktivasi_tanggal_pengambilan').attr('required', 'required');
						}
					});
					});
		
					//--------------------END DEAKTIVASI----------------------
					</script>

{{-- <script>
	$(document).ready(function() {
		$(document).on('click', 'button.removebutton_tiket', function () {
												$(this).closest('tr').remove();
												return false;
											});

											

											$("#tiket_barang").append('<tr>'+
								'<td>'+ i +'</td>'+
								'<td><input name="tiket_test" id="tiket_pending" value="aaaa" type="text" class="form-control"></td>'+
									
									'<td with><button type="button" class="removebutton btn btn-danger btn-sm" title="Remove this row">X</button></td></tr>').find("input").each(function () {
								});
								i++;
							});
</script> --}}

					<script>
						//--------------------START BARANG KELUAR----------------------

						var i = 1;
							$(document).on('click', 'button.removebutton', function () {
								$(this).closest('tr').remove();
								return false;
							});

							

							$('.pilih_barang').click(function(){
								var kode_barang =$(this).data("id");
								var nama_barang =$(this).data("nama");
								var merek =$(this).data("merek");
							var harga =$(this).data("harga");
							var qty =$(this).data("qty");
							// var qty =$('#qty').val()
							$('#modal_barang').modal('hide')
							$("#t").append('<tr>'+
								'<td>'+ i +'</td>'+
								'<td><input type="text" value="'+kode_barang+'" class="form-control " name="bk_id_barang[]" id="" readonly ></td>'+
								'<td><input type="text" value="'+nama_barang+'" class="form-control " name="bk_nama_barang[]" id="" readonly ></td>'+
								'<td><input type="text" value="'+merek+'" class="form-control" name="" readonly ></td>'+
								'<td><input type="text" value="'+harga+'" class="form-control" name="bk_harga_barang[]" readonly ></td>'+
									'<td width="10%"><input type="text" readonly value="'+qty+'" class="form-control"></td>'+
									'<td width="10%"><input type="number" value="1" class="form-control"  min="1" max="'+qty+'" onkeydown="return false name="bk_qty[]"></td>'+
									
									'<td with><button type="button" class="removebutton btn btn-danger btn-sm" title="Remove this row">X</button></td></tr>').find("input").each(function () {
								});
								i++;
							});
				
				//--------------------END BARANG KELUAR----------------------
					</script>
					<script>
						//--------------------START TIKET NEW----------------------

		// 				$(document).ready(function() {

						




		// 	$('select[name=tiket_status]').change(function () {
		// 	if ($(this).val() == 'Pending') {
		// 		$('.submit_tiket').removeAttr('disabled');
		// 		$('.div_tiket_pending').show();
		// 		$('.div_tiket_closed').hide();
		// 		$('#tiket_pending').attr('required', 'required');
		// 		$('.tiket_waktu_penanganan').hide();
		// 		$('#tiket_waktu_penanganan').removeAttr('required');
		// 		$('#div_tiket_kendala').removeAttr('required');
		// 		$('#tiket_tindakan').removeAttr('required');
		// 		$('#tiket_teknisi1').attr('required', 'required');
		// 		$('#tiket_teknisi2').attr('required', 'required');
		// 		$('.submit_tiket').removeAttr('disabled');

		// 	} else if ($(this).val() == 'Closed') {

		// 		$('.submit_tiket').attr('disabled','disabled');
		// 		$('.div_tiket_closed').show();
		// 		$('.div_tiket_pending').hide();
		// 		$('#tiket_waktu_penanganan').attr('required', 'required');
		// 		$('#tiket_teknisi1').attr('required', 'required');
		// 		$('#tiket_teknisi2').attr('required', 'required');
		// 		$('#tiket_kendala').attr('required', 'required');
		// 		$('#tiket_tindakan').attr('required', 'required');
		// 		$("#ganti_barang").click(function() {
		// 			if($(this).is(":checked")) {
		// 				$('.div_ganti_barang').show();
		// 				$("#ganti_lainnya").prop('checked', false);
		// 				$(document).on('click', 'button.tiket_pilih_barang', function () {
		// 					var kode_barang = $('#kode_barang').val();
		// 					var url = '{{ route("admin.val.valBarang", ":id") }}';
		// 					url = url.replace(':id', kode_barang);
		// 					$.ajax({
		// 						url: url,
		// 						type: 'GET',
		// 						data: {'_token': '{{ csrf_token() }}'},
		// 						dataType: 'json',
		// 						success: function(data) {
		// 							$('.submit_tiket').removeAttr('disabled');
		// 							if(data.barang_id){
		// 							if(data.barang_qty - data.barang_digunakan > 0){
		// 								$('#kode_barang').val('');
		// 								$('.tiket_notif_1').removeClass('has-error has-feedback')
		// 								$('.tiket_notif_1').addClass('has-success has-feedback')
		// 								$('.hide_tr').show()
		// 								$('.tiket_pesan_1').html('')
										
		// 									$(document).on('click', 'button.removebutton_tiket', function () {
		// 										$(this).closest('tr').remove();
		// 										return false;
		// 									});

											
		// 									var qty = parseInt(data.barang_qty) - parseInt(data.barang_digunakan)
		// 									$("#tiket_barang").append('<tr>'+
		// 									'<td><input type="text" value="'+data.barang_id+'" class="form-control " name="tiket_barangId[]"  ></td>'+
		// 									'<td><input type="text" value="'+data.barang_nama+'" class="form-control " name="tiket_barang_nama[]" readonly ></td>'+
		// 									'<td><input type="text" value="'+data.barang_merek+'" class="form-control" name="" readonly ></td>'+
		// 									'<td><input type="text" value="'+data.barang_harga+'" class="form-control" name="tiket_barang_harga[]" readonly ></td>'+
		// 									'<td width="10%"><input type="text" readonly value="'+qty+'" class="form-control"></td>'+
		// 									'<td width="10%"><input type="number" value="1" class="form-control"  min="1" max="'+qty+'" onkeydown="return false id="txtTitle" name="tiket_barang_qty[]"></td>'+
											
		// 									'<td with><button type="button" class="removebutton btn btn-danger btn-sm" title="Remove this row">X</button></td></tr>').find("input").each(function () {
		// 									});

		// 									$("form").submit(function (event) {
		// 										var url = '{{ route("admin.psb.get_update_tgl_tempo", ":id") }}';
												
		// 										url = url.replace(':id', id);
		// 											$.ajax({
		// 												url: url,
		// 												type: 'PUT',
		// 												data: {
		// 													barang_id:data.barang_idate,
		// 													barang_nama:data.barang_nama,
		// 													qty:qty *data.barang_harga,
		// 													tiket_jenis:('#tiket_jenis').val(),
		// 													tiket_status:('#tiket_status').val(),
		// 													tiket_pending:('#tiket_pending').val(),
		// 													tiket_kendala:('#tiket_kendala').val(),
		// 													tiket_tindakan:('#tiket_tindakan').val(),
		// 													tiket_foto:('#tiket_foto').val(),
		// 													teknisi_id:$teknisi_id,
		// 													tiket_teknisi2:$request->tiket_teknisi2,
		// 													'_token': '{{ csrf_token() }}'
		// 												},
		// 											dataType: 'json',
		// 											success: function(data) {
		// 												console.log(data);;
		// 											$("#biaya").val(data['biaya']);
		// 											}
		// 										});
		// 										event.preventDefault();

		// 										});
		// 							} else{
		// 								$('.tiket_notif_1').addClass('has-error has-feedback')
		// 								$('.tiket_pesan_1').html('<small id="text" class="form-text text-muted text-danger">Stok habis</small>')
		// 							}
		// 							} else{
		// 								$('.tiket_notif_1').addClass('has-error has-feedback')
		// 								$('.tiket_pesan_1').html('<small id="text" class="form-text text-muted text-danger">Kode Barang tidak ditemukan</small>')
		// 							}
		// 						},
		// 						error: function(error) {
		// 							// console.log(error)
									
		// 						},
		// 					});
		// 				});
		

		// 			}else {
		// 				$('.div_ganti_barang').hide();
		// 			}
		// 		});
		// 		$("#ganti_lainnya").click(function() {
		// 			if($(this).is(":checked")) {
		// 				$('.div_ganti_barang').hide();
		// 				$("#ganti_barang").prop('checked', false);
		// 				$('.submit_tiket').removeAttr('disabled');
		// 				$('#tiket_barang').empty();
		// 				$('#kode_barang').val('');
		// 				$('.tiket_notif_1').removeClass('has-error has-feedback')
		// 			};
		// 			});
		// 	}
		// });
		// });

				//--------------------END TIKET_NEW----------------------
					</script>
			

			
</body>
</html>
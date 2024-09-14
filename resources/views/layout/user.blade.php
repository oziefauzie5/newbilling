<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>{{Session::get('app_brand')}}</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="{{ asset('storage/img/'.Session::get('app_favicon')) }}" type="image/x-icon"/>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js')}}"></script>  
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
		.img{
  width: 20px;
  /* border-style:solid; */
	border-width: 20px;
    border-color: rgb(255, 255, 255);
    width: 10px;
    border-radius: 3px;

	}
	.card_custom{
		border-radius: 20px; height:100%; background: linear-gradient(to right, #5d69be, #C89FEB);
	}
	.card_custom1{
		border-radius: 20px;;
	}
	
    
	</style>
</head>
<body>
	<div class="wrapper overlay-sidebar">
		<div class="main-header">
			<!-- Logo Header -->
			<div class="logo-header" data-background-color="blue">
				
				<a href="index.html" class="logo">
					<img src="{{ asset('storage/img/'.Session::get('app_logo')) }}" alt="navbar brand" class="navbar-brand">
				</a>
				<button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
			
			</div>
			<!-- End Logo Header -->

			<!-- Navbar Header -->
			<nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">
				
				<div class="container-fluid">
					<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
						<li class="nav-item dropdown hidden-caret">
							<a class="nav-link dropdown-toggle" href="{{ route('logout') }}" >
							<i class="fas fa-sign-out-alt"> </i> LOGOUT
							</a>
						</li>
						<li class="nav-item dropdown hidden-caret">
							<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
								<div class="avatar-sm">
									<img src="{{ asset('storage/photo-user/'.Auth::user()->photo) }}" alt="..." class="avatar-img rounded-circle">
								</div>
							</a>
						</li>
					</ul>
				</div>
			</nav>
			<!-- End Navbar -->
		</div>

		<!-- End Sidebar -->

		<div class="main-panel">
            @yield('content')
			
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
	<!-- <script src="{{asset('atlantis/assets/js/setting-demo.js')}}"></script>
	<script src="{{asset('atlantis/assets/js/demo.js')}}"></script> -->

	<script>

			$(document).ready(function() {
            $("#before, #after").keyup(function() {
                var after  = $("#after").val();
                var before = $("#before").val();
    
                var total = parseInt(before) - parseInt(after);
                if (isNaN(total)) {
                    total = '';
                    }
                $("#total").val(total);
            });


            $("#before_dropcore, #after_dropcore").keyup(function() {
				var after_dropcore  = $("#after_dropcore").val();
                var before_dropcore = $("#before_dropcore").val();
    
                var total_dropcore = parseInt(before_dropcore) - parseInt(after_dropcore);
                if (isNaN(total_dropcore)) {
                    total_dropcore = '';
                    }
                $("#total_dropcore").val(total_dropcore);
            });


            });
			$("#fat_opm, #home_opm").keyup(function() {
                var home_opm  = $("#home_opm").val();
                var fat_opm = $("#fat_opm").val();
                var num1 = "1";
                var total_lost = Number((fat_opm-home_opm).toFixed(2)); // 1 instead of 1.01;
                $("#los_opm").val(total_lost);
            });





		$(".cari").click(function(){
        // $("#kode").css("border-color", "yellow");
        var kode_kabel =$("#kode").val();
        var url = '{{ route("admin.teknisi.getBarang", ":id") }}';
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
                            // 
                            if (data.subbarang_stok) {
                              var after  = $("#after").val();
                              // var before = $("#before").val();
                                $("#before").val(data.subbarang_stok);
                                $("#before").css("border-color", "rgb(60, 179, 113)");
                                $("#notif").html('');
    
                                var total = parseInt(data.subbarang_stok) - parseInt(after);
                                if (isNaN(total)) {
                                    total = '';
                                    }
                                $("#total").val(total);
								$("#modal_cari").modal('hide');
                              } else {
                                $('#before').val('');
                                $("#notif").html('<div class="alert alert-danger" role="alert">Kode barang tidak ditemukan</div>');
                                $("#before").css("border-color", "red");
                                $("#total").val('');
                              }
                            },
                            error: function(error){
                              // or');
                              $('#before').val('');
                              $("#before").css("border-color", "red");
                              $("#notif").html('<div class="alert alert-danger" role="alert">Kode barang tidak ditemukan</div>');
                              $("#total").val('');
                            }
                        
                    });
      });
	</script>
	<script>
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
	</script>
	{{-- #BILLER PAYMENT --}}
	<script>
	
// 	function myFunction() {
// 		let idi   = $('#invoice_id').val();
// 		var url_kolektor = '{{ route("admin.biller.paymentbytagihan", ":id") }}';
// 		url_kolektor = url_kolektor.replace(':id', idi);
// 		// alert(url_kolektor);
// 		window.location.href = url_kolektor;
// }

		$('#cari_pelanggan').click(function(e) {
			let id   = $('#biller_idpel').val();
			$("#progress").html('<button class="btn btn-block btn-primary text-light" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>');
			var url = '{{ route("admin.biller.getpelanggan", ":id") }}';
			url = url.replace(':id', id);
			var url_bayar = '{{ route("admin.biller.bayar", ":id") }}';
			url_bayar = url_bayar.replace(':id', id);
			var url_print = '{{ route("admin.biller.print", ":id") }}';
			url_print = url_print.replace(':id', id);
			if (id) {
				$.ajax({
				url: url,
				type: 'GET',
							data: {
								'_token': '{{ csrf_token() }}'
							},
							dataType: 'json',
							success: function(data) {
								
								if(data['data']=='PAID'){
									$("#progress").html('');
									$("#text").html('Tagihan telah Terbayar');
									// data']);
								} else {
								if (data['data']) {
									// $('#myModal').modal('hide');
									document.getElementById('hidden_div').style.display = "block";
									$("#progress").html('');
									$("#text").html('');
									var harga = data['sumharga'];
									var ppn = data['data'].subinvoice_ppn;
									var komisi = data['biller'].mts_komisi;
									var diskon = data['data'].inv_diskon;
									if(diskon == null){
										diskon =0;
									}else if(ppn == null){
										ppn =0;
									}else if(harga==null){
										harga=0
									}else if(komisi==null){
										komisi=0
									}
									var total = parseInt(harga)+parseInt(ppn)+parseInt(komisi)-parseInt(diskon);
									document.getElementById("upd_pelanggan").innerHTML =data['data'].inv_nama;
									document.getElementById("hp").innerHTML =data['data'].input_hp;
									document.getElementById("nolay").innerHTML =data['data'].inv_nolayanan;
									document.getElementById("alamat_pasang").innerHTML =data['data'].input_alamat_pasang;
									document.getElementById("upd_kategori").innerHTML =data['data'].inv_kategori;
									document.getElementById("upd_tgl_tagih").innerHTML =data['data'].inv_tgl_tagih;
									document.getElementById("upd_tgl_jatuh_tempo").innerHTML =data['data'].inv_tgl_jatuh_tempo;
									document.getElementById("subinvoice_harga").innerHTML = new Intl.NumberFormat('id-ID', {
																				style: 'currency',minimumFractionDigits: 0,
																				currency: 'IDR',
																				}).format(harga);
									document.getElementById("subinvoice_diskon").innerHTML = new Intl.NumberFormat('id-ID', {
																				style: 'currency',minimumFractionDigits: 0,
																				currency: 'IDR',
																				}).format(data['data'].inv_diskon);
									document.getElementById("subinvoice_ppn").innerHTML = new Intl.NumberFormat('id-ID', {
																				style: 'currency',minimumFractionDigits: 0,
																				currency: 'IDR',
																				}).format(data['data'].subinvoice_ppn);
									document.getElementById("biaya_layanan").innerHTML = new Intl.NumberFormat('id-ID', {
																				style: 'currency',minimumFractionDigits: 0,
																				currency: 'IDR',
																				}).format(data['biller'].mts_komisi);
									document.getElementById("subinvoice_total").innerHTML = new Intl.NumberFormat('id-ID', {
																				style: 'currency',minimumFractionDigits: 0,
																				currency: 'IDR',
																				}).format(total);
									$("#buton").html('<input value="Proses Pembayaran" id="bayar" class="btn btn-block  btn-primary" ></input>');
									$('#bayar').click(function(e) {
	
										$("#buton").html('<button class="btn btn-block btn-primary text-light " type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>');
	
										$.ajax({
											url: url_bayar,
											type: 'GET',
											data: {
												'_token': '{{ csrf_token() }}'
											},
											dataType: 'json',
											success: function(data) {
												if(data.alert=='success'){
													swal(data.pesan, {
														icon : data.alert,
														buttons: {        			
															confirm: {
																className : 'btn btn-success'
															}
														},
													});
													$("#buton").html('<a href="'+url_print+'" target="_blank"><button class="btn btn-block  btn-success">Print</button></a>');
											}else {
												swal(data.pesan, {
														icon : data.alert,
														buttons: {        			
															confirm: {
																className : 'btn btn-error'
															}
														},
													});
												$("#buton").html('<input value="Proses Pembayaran" id="bayar" class="btn btn-block btn-primary text-light " ></input>');
											}
	
											},
											error: function(error) {
												$("#buton").html('<input value="Proses Pembayaran" id="bayar" class="btn btn-block btn-primary text-light" ></input>');
												 $("#text").html('Transaksi Gagal. Coba lagi nanti...');
	
	
											},
										});
	
	
	
									});
								}  else {
									document.getElementById('hidden_div').style.display = "none";
									$("#progress").html('');
									$("#text").html('<strong>No. Invoice/Id Pelanggan tidak ditemukan</strong>');
								}
								}
							},
							error: function(error) {
								$("#progress").html('');
								$("#text").html('error. Coba lagi nanti...');
							},
	
						});
					} else {
						document.getElementById('hidden_div').style.display = "none";
								$("#progress").html('');
								$("#text").html('Masukan No. Invoice/Id Pelanggan terlebih dahulu');
						$('#harga').empty();
					}
	
				});
				</script>
				
	<script>
		function comingson() {
			swal('Comingsoon');
		}
		$(document).ready(function() {
	
			
		$('#table').DataTable({
			"pageLength": 5,
			
		});
		$('#get_invoice').DataTable({
			"pageLength": 5,
			
		});
		});
	</script>
	<script>
			$('.href').click(function(){
							var id =$(this).data("id");
							var url = '{{ route("admin.biller.print", ":id") }}';
							url = url.replace(':id', id);
							// alert(url);
							window.location=url;
						});
	</script>
	<script>
			$(document).ready(function() {
    $('#update_tiket').click(function(e) {  
		let id   = $('#tiket_id').val();
		var url = '{{ route("admin.teknisi.update_tiket", ":id") }}';
			url = url.replace(':id', id);
                $.ajax({
                  url: url,
				  type: 'GET',
                    data: {
						'_token': '{{ csrf_token() }}'
                    },
                        dataType: 'json',
                        success: function(data) {
						}
                    });
				});
			});
		
	</script>
	<script>
		$(document).ready(function() {
			$("#edit_pactcore").click(function() {
				if($(this).is(":checked")) {
					$('#edit_modal_pactcore').modal('show')
					$('.edit_kode_pactcore').attr('required', 'required');

					// //Hapus Adaptor
					// $("#edit_adaptor").prop("checked", false);
					// $("#edit_kode_adaptor").val('');
					
					// //Hapus Ont
					// $("#edit_ont").prop("checked", false);
					// $('#edit_validasi_ont').removeClass("has-error has-feedback");
					// $('#edit_validasi_ont').removeClass("has-success");
					// $("#edit_kode_ont").val('');
					// $('#edit_notif_ont').html('');
					// $('#edit_note_ont').html('');
					// $('#edit_reg_mac').val('');
					// $('#edit_reg_sn').val('');
					// $('#edit_reg_mrek').val('');
					// $('#alasan').val('');
					// $('#keterangan').val('');
					// $('.edit_ont').removeAttr('required');
					// $('#show_ont').hide()

					//Hapus seting ulang
					$("#edit_seting").prop("checked", false);
					$('#seting_ulang').hide()

					//Hapus lainnya
					$("#edit_lain").prop("checked", false);
					
					
				} else {
					$('#edit_modal_pactcore').modal('hide')
					$("#edit_kode_pactcore").val('');
					
				}
			});

			$('.edit_hide_pactcore').click(function(){
				$("#edit_modal_pactcore").modal('hide');
				$("#edit_pactcore").prop('checked', false);
				$('#edit_validasi_pactcore').removeClass("has-error has-feedback");
				$('#edit_validasi_pactcore').removeClass("has-success");
				$("#edit_kode_pactcore").val('');
				$('#edit_notif_pactcore').html('');
				$('#edit_note_pactcore').html('');
				// $('.kode_pactcore').removeAttr('required', 'required');
			});
			// ============================================ END PACTCORE ===========================================


			$("#edit_adaptor").click(function() {
				if($(this).is(":checked")) {
					$('#edit_modal_adaptor').modal('show')
					$('.kode_pactcore').attr('required', 'required');

					//Hapus pactcore
					// $("#edit_pactcore").prop("checked", false);
					// $("#edit_kode_pactcore").val('');

					//Hapus seting ulang
					$("#edit_seting").prop("checked", false);
					$('#seting_ulang').hide()

					//Hapus lainnya
					$("#edit_lain").prop("checked", false);
					
				} else {
					$('#edit_modal_adaptor').modal('hide')
					
				}
			});
			
			$('.edit_hide_adaptor').click(function(){
				$("#edit_modal_adaptor").modal('hide');
				$("#edit_adaptor").prop('checked', false);
				$('#edit_validasi_adaptor').removeClass("has-error has-feedback");
				$('#edit_validasi_adaptor').removeClass("has-success");
				$("#edit_kode_adaptor").val('');
				$('#edit_notif_adaptor').html('');
				$('#edit_note_adaptor').html('');
				$('.edit_kode_adaptor').removeAttr('required', 'required');
			});
			// ============================================ END ADAPTOR ===========================================
			$("#edit_dropcore").click(function() {
				if($(this).is(":checked")) {
					$('#edit_modal_dropcore').modal('show')
					$('.edit_kode_dropcore').attr('required', 'required');
					$('.before_dropcore').attr('required', 'required');
					$('.after_dropcore').attr('required', 'required');
					$('.total_dropcore').attr('required', 'required');
					
					//Hapus seting ulang
					$("#edit_seting").prop("checked", false);
					$('#seting_ulang').hide()

					//Hapus lainnya
					$("#edit_lain").prop("checked", false);
					
				} else {
					$('#edit_modal_dropcore').modal('hide')
					$(".edit_kode_dropcore").val('');
					$(".before_dropcore").val('');
					$(".after_dropcore").val('');
					$(".total_dropcore").val('');
					$('.edit_kode_dropcore').removeAttr('required', 'required');
					$('.before_dropcore').removeAttr('required', 'required');
					$('.after_dropcore').removeAttr('required', 'required');
					$('.total_dropcore').removeAttr('required', 'required');
					$('#show_dropcore').hide()
					
				}
			});
			
			$('.edit_hide_dropcore').click(function(){
				$("#edit_modal_dropcore").modal('hide');
				$("#edit_dropcore").prop('checked', false);
				$('#edit_validasi_dropcore').removeClass("has-error has-feedback");
				$('#edit_validasi_dropcore').removeClass("has-success");
				$("#edit_kode_dropcore").val('');
				$('#edit_notif_dropcore').html('');
				$('#edit_note_dropcore').html('');
				$('.edit_kode_dropcore').removeAttr('required', 'required');
			});
			// ============================================ END dropcore ===========================================

			$("#edit_seting").click(function() {
				if($(this).is(":checked")) {
					$('#seting_ulang').show()

					//Hapus pactcore
					$("#edit_pactcore").prop("checked", false);
					$("#edit_kode_pactcore").val('');

					//Hapus Adaptor
					$("#edit_adaptor").prop("checked", false);
					$("#edit_kode_adaptor").val('');
					
					//Hapus Ont
					$("#edit_ont").prop("checked", false);
					$('#edit_validasi_ont').removeClass("has-error has-feedback");
					$('#edit_validasi_ont').removeClass("has-success");
					$("#edit_kode_ont").val('');
					$('#edit_notif_ont').html('');
					$('#edit_note_ont').html('');
					$('#edit_reg_mac').val('');
					$('#edit_reg_sn').val('');
					$('#edit_reg_mrek').val('');
					$('#alasan').val('');
					$('#show_ont').hide()

					//Hapus lainnya
					$("#edit_lain").prop("checked", false);
				} else {
					$('#seting_ulang').hide()
					
				}
			});
			$("#edit_lain").click(function() {
				if($(this).is(":checked")) {
					//Hapus pactcore
					$("#edit_pactcore").prop("checked", false);
					$("#edit_kode_pactcore").val('');

					//Hapus Adaptor
					$("#edit_adaptor").prop("checked", false);
					$("#edit_kode_adaptor").val('');
					
					//Hapus Ont
					$("#edit_ont").prop("checked", false);
					$('#edit_validasi_ont').removeClass("has-error has-feedback");
					$('#edit_validasi_ont').removeClass("has-success");
					$("#edit_kode_ont").val('');
					$('#edit_notif_ont').html('');
					$('#edit_note_ont').html('');
					$('#edit_reg_mac').val('');
					$('#edit_reg_sn').val('');
					$('#edit_reg_mrek').val('');
					$('#alasan').val('');

					$('#show_ont').hide()

					//Hapus lainnya
					$("#edit_lain").prop("checked", false);
					$('#seting_ulang').hide()

					//Hapus seting ulang
					$("#edit_seting").prop("checked", false);
					$('#seting_ulang').hide()
				} else {
					
				}
			});
			$("#edit_ont").click(function() {
				if($(this).is(":checked")) {
					$('#edit_modal_ont').modal('show')
					$('.edit_kode_ont').attr('required', 'required');
					$('.alasan').attr('required', 'required');

					
					// //Hapus pactcore
					// $("#edit_pactcore").prop("checked", false);
					// $("#edit_kode_pactcore").val('');

					//Hapus seting ulang
					$("#edit_seting").prop("checked", false);
					$('#seting_ulang').hide()

					//Hapus lainnya
					$("#edit_lain").prop("checked", false);

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
					$('.edit_kode_ont').removeAttr('required', 'required');
					$('.alasan').removeAttr('required', 'required');
					$('#show_ont').hide()
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
				$('#alasan').val('');

				
				$('#edit_notif_ont').html('');
				$('#edit_note_ont').html('');
				$('#edit_reg_mac').val('');
				$('#edit_reg_sn').val('');
				$('#edit_reg_mrek').val('');

				$('.edit_kode_ont').removeAttr('required', 'required');
				$('.alasan').removeAttr('required', 'required');
				$('#show_ont').hide()

			});

				$('.edit_val_ont').click(function(){

					if($('#alasan').val()=== ""){
						$("#edit_validasi_alasan").addClass("has-error has-feedback");
						$('#edit_notif_alasan').html('<small class="form-text text-muted text-danger">Alasan tidak boleh kosong</small>');
					}  else {
						$('#edit_validasi_alasan').removeClass("has-error has-feedback");
						$('#edit_notif_alasan').html('');
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
									$('#show_ont').show()
									$('#edit_validasi_ont').removeClass("has-error has-feedback");
									$("#edit_validasi_ont").addClass("has-success");
									$('#edit_notif_ont').html('');
									$("#edit_modal_ont").modal('hide');
									$('#edit_note_ont').html('');
									$('#edit_reg_mac').val(data.subbarang_mac);
									$('#edit_reg_sn').val(data.subbarang_sn);
									$('#edit_reg_mrek').val(data.subbarang_nama);
									$('#edit_reg_kode_ont').val(data.id_subbarang);
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
					});

					// ==================================END ONT ===========================================

					$('.edit_val_pactcore').click(function(){
					var kode_pact = $('#edit_kode_pactcore').val();
				if(kode_pact){

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
									$("#edit_modal_pactcore").modal('hide');
									$('#edit_validasi_pactcore').removeClass("has-error has-feedback");
									$('#edit_notif_pactcore').html('');
									$('#edit_note_pactcore').html('');
								}else{
									$("#edit_validasi_pactcore").addClass("has-error has-feedback");
									$('#edit_notif_pactcore').html('<small class="form-text text-muted text-danger">Kode Pactcore tidak ada atau telah digunakan</small>');
									$('#edit_note_pactcore').html('<ul><li>Pastikan kode belum digunkan</li><li>Pastikan kode terdaftar pada sistem</li><li>Kode yang dimasukan harus sesuai kategori barang</li></ul>');

								}
							},
							error: function(data) {
								$("#edit_validasi_pactcore").addClass("has-error has-feedback");
								$('#edit_notif_pactcore').html('<small class="form-text text-muted text-danger">Kode Pactcore tidak boleh kosong</small>');
								$('#edit_note_pactcore').html('<ul><li>Pastikan kode belum digunkan</li><li>Pastikan kode terdaftar pada sistem</li><li>Kode yang dimasukan harus sesuai kategori barang</li></ul>');
								$('#edit_reg_mac').val('');
									$('#edit_reg_sn').val('');
									$('#edit_reg_mrek').val('');
								}
						});
					} else{
						$('#edit_notif_pactcore').html('<small class="form-text text-muted text-danger">Kode Pactcore tidak boleh kosong</small>');
					}
				});
				// ==================================END PACTCORE ===========================================
					$('.edit_val_adaptor').click(function(){
					var kode_adaptor = $('#edit_kode_adaptor').val();
				if(kode_adaptor){

					var url = '{{ route("admin.reg.validasi_adaptor", ":id") }}';
					url = url.replace(':id', kode_adaptor);
					$.ajax({
						url: url,
						type: 'GET',
						data: {
							'_token': '{{ csrf_token() }}'
						},
						dataType: 'json',
						success: function(data) {
								if(data.id_subbarang){
									$("#edit_modal_adaptor").modal('hide');
									$('#edit_validasi_adaptor').removeClass("has-error has-feedback");
									$('#edit_notif_adaptor').html('');
									$('#edit_note_adaptor').html('');
								}else{
									$("#edit_validasi_adaptor").addClass("has-error has-feedback");
									$('#edit_notif_adaptor').html('<small class="form-text text-muted text-danger">Kode Adaptor tidak ada atau telah digunakan</small>');
									$('#edit_note_adaptor').html('<ul><li>Pastikan kode belum digunkan</li><li>Pastikan kode terdaftar pada sistem</li><li>Kode yang dimasukan harus sesuai kategori barang</li></ul>');

								}
							},
							error: function(data) {
								$("#edit_validasi_adaptor").addClass("has-error has-feedback");
								$('#edit_notif_adaptor').html('<small class="form-text text-muted text-danger">Kode Adaptor tidak boleh kosong</small>');
								$('#edit_note_adaptor').html('<ul><li>Pastikan kode belum digunkan</li><li>Pastikan kode terdaftar pada sistem</li><li>Kode yang dimasukan harus sesuai kategori barang</li></ul>');
								$('#edit_reg_mac').val('');
									$('#edit_reg_sn').val('');
									$('#edit_reg_mrek').val('');
								}
						});
					} else{
						$('#edit_notif_adaptor').html('<small class="form-text text-muted text-danger">Kode Adaptor tidak boleh kosong</small>');
						
					}
				});
				// ==================================END ADAPTOR ===========================================

	// 			$(".cari").click(function(){
    //     // $("#kode").css("border-color", "yellow");
    //     var kode_kabel =$("#kode").val();
    //     var url = '{{ route("admin.teknisi.getBarang", ":id") }}';
    // url = url.replace(':id', kode_kabel);
    //                 $.ajax({
    //                     url: url,
    //                     type: 'GET',
    //                     data: {
    //                       kode_kabel: kode_kabel,
    //                         '_token': '{{ csrf_token() }}'
    //                     },
    //                     dataType: 'json',
    //                     success: function(data) {
    //                         // 
    //                         if (data.subbarang_stok) {
    //                           var after  = $("#after").val();
    //                           // var before = $("#before").val();
    //                             $("#before").val(data.subbarang_stok);
    //                             $("#before").css("border-color", "rgb(60, 179, 113)");
    //                             $("#notif").html('');
    
    //                             var total = parseInt(data.subbarang_stok) - parseInt(after);
    //                             if (isNaN(total)) {
    //                                 total = '';
    //                                 }
    //                             $("#total").val(total);
	// 							$("#modal_cari").modal('hide');
    //                           } else {
    //                             $('#before').val('');
    //                             $("#notif").html('<div class="alert alert-danger" role="alert">Kode barang tidak ditemukan</div>');
    //                             $("#before").css("border-color", "red");
    //                             $("#total").val('');
    //                           }
    //                         },
    //                         error: function(error){
    //                           // or');
    //                           $('#before').val('');
    //                           $("#before").css("border-color", "red");
    //                           $("#notif").html('<div class="alert alert-danger" role="alert">Kode barang tidak ditemukan</div>');
    //                           $("#total").val('');
    //                         }
                        
    //                 });
    //   });




					$('.edit_val_dropcore').click(function(){
					var kode_dropcore = $('#edit_kode_dropcore').val();
				if(kode_dropcore){

					var url = '{{ route("admin.teknisi.getBarang", ":id") }}';
					url = url.replace(':id', kode_dropcore);
					$.ajax({
						url: url,
						type: 'GET',
						data: {
							'_token': '{{ csrf_token() }}'
						},
						dataType: 'json',
						success: function(data) {
								if(data.subbarang_stok){
									$("#edit_modal_dropcore").modal('hide');
									$('#show_dropcore').show()
									$('#edit_validasi_dropcore').removeClass("has-error has-feedback");
									$('#edit_notif_dropcore').html('');
									$('#edit_note_dropcore').html('');
									var after_dropcore  = $("#after_dropcore").val();
                                $("#before_dropcore").val(data.subbarang_stok);
                                $("#before_dropcore").css("border-color", "rgb(60, 179, 113)");
                                $("#notif").html('');
    
                                var total_dropcore = parseInt(data.subbarang_stok) - parseInt(after_dropcore);
                                if (isNaN(total_dropcore)) {
                                    total_dropcore = '';
                                    }
                                $("#total_dropcore").val(total_dropcore);
								}else{
									$("#edit_validasi_dropcore").addClass("has-error has-feedback");
									$('#edit_notif_dropcore').html('<small class="form-text text-muted text-danger">Kode dropcore tidak ada atau telah digunakan</small>');
									$('#edit_note_dropcore').html('<ul><li>Pastikan kode belum digunkan</li><li>Pastikan kode terdaftar pada sistem</li><li>Kode yang dimasukan harus sesuai kategori barang</li></ul>');

								}
							},
							error: function(data) {
								$("#edit_validasi_dropcore").addClass("has-error has-feedback");
								$('#edit_notif_dropcore').html('<small class="form-text text-muted text-danger">Kode dropcore tidak boleh kosong</small>');
								$('#edit_note_dropcore').html('<ul><li>Pastikan kode belum digunkan</li><li>Pastikan kode terdaftar pada sistem</li><li>Kode yang dimasukan harus sesuai kategori barang</li></ul>');
								}
						});
					} else{
						$('#edit_notif_dropcore').html('<small class="form-text text-muted text-danger">Kode dropcore tidak boleh kosong</small>');
						
					}
				});
				// ==================================END DROPCORE ===========================================


		});

		//  Cari Invoice
$(function(){ 
  var table = $('#cari_inv').DataTable(); $('#cari_inv tbody').on( 'click', 'tr', function () 
			{  
			var id = table.row( this ).id();

			$("#progress").html('<button class="btn btn-block btn-primary text-light" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>');
			var url = '{{ route("admin.biller.getpelanggan", ":id") }}';
			url = url.replace(':id', id);
			var url_bayar = '{{ route("admin.biller.bayar", ":id") }}';
			url_bayar = url_bayar.replace(':id', id);
			var url_print = '{{ route("admin.biller.print", ":id") }}';
			url_print = url_print.replace(':id', id);
			if (id) {
				$.ajax({
				url: url,
				type: 'GET',
							data: {
								'_token': '{{ csrf_token() }}'
							},
							dataType: 'json',
							success: function(data) {
								$("#cari_data_inv").modal('hide');
								if(data['data']=='PAID'){
									$("#progress").html('');
									$("#text").html('Tagihan telah Terbayar');
									// data']);
								} else {
								if (data['data']) {
									// $('#myModal').modal('hide');
									document.getElementById('hidden_div').style.display = "block";
									$("#progress").html('');
									$("#text").html('');
									var harga = data['sumharga'];
									var ppn = data['data'].subinvoice_ppn;
									var komisi = data['biller'].mts_komisi;
									var diskon = data['data'].inv_diskon;
									if(diskon == null){
										diskon =0;
									}else if(ppn == null){
										ppn =0;
									}else if(harga==null){
										harga=0
									}else if(komisi==null){
										komisi=0
									}
									var total = parseInt(harga)+parseInt(ppn)+parseInt(komisi)-parseInt(diskon);
									document.getElementById("upd_pelanggan").innerHTML =data['data'].inv_nama;
									document.getElementById("hp").innerHTML =data['data'].input_hp;
									document.getElementById("nolay").innerHTML =data['data'].inv_nolayanan;
									document.getElementById("alamat_pasang").innerHTML =data['data'].input_alamat_pasang;
									document.getElementById("upd_kategori").innerHTML =data['data'].inv_kategori;
									document.getElementById("upd_tgl_tagih").innerHTML =data['data'].inv_tgl_tagih;
									document.getElementById("upd_tgl_jatuh_tempo").innerHTML =data['data'].inv_tgl_jatuh_tempo;
									document.getElementById("subinvoice_harga").innerHTML = new Intl.NumberFormat('id-ID', {
																				style: 'currency',minimumFractionDigits: 0,
																				currency: 'IDR',
																				}).format(harga);
									document.getElementById("subinvoice_diskon").innerHTML = new Intl.NumberFormat('id-ID', {
																				style: 'currency',minimumFractionDigits: 0,
																				currency: 'IDR',
																				}).format(data['data'].inv_diskon);
									document.getElementById("subinvoice_ppn").innerHTML = new Intl.NumberFormat('id-ID', {
																				style: 'currency',minimumFractionDigits: 0,
																				currency: 'IDR',
																				}).format(data['data'].subinvoice_ppn);
									document.getElementById("biaya_layanan").innerHTML = new Intl.NumberFormat('id-ID', {
																				style: 'currency',minimumFractionDigits: 0,
																				currency: 'IDR',
																				}).format(data['biller'].mts_komisi);
									document.getElementById("subinvoice_total").innerHTML = new Intl.NumberFormat('id-ID', {
																				style: 'currency',minimumFractionDigits: 0,
																				currency: 'IDR',
																				}).format(total);
									$("#buton").html('<input value="Proses Pembayaran" id="bayar" class="btn btn-block  btn-primary" ></input>');
									$('#bayar').click(function(e) {
	
										$("#buton").html('<button class="btn btn-block btn-primary text-light " type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>');
	
										$.ajax({
											url: url_bayar,
											type: 'GET',
											data: {
												'_token': '{{ csrf_token() }}'
											},
											dataType: 'json',
											success: function(data) {
												if(data.alert=='success'){
													swal(data.pesan, {
														icon : data.alert,
														buttons: {        			
															confirm: {
																className : 'btn btn-success'
															}
														},
													});
													$("#buton").html('<a href="'+url_print+'" target="_blank"><button class="btn btn-block  btn-success">Print</button></a>');
											}else {
												swal(data.pesan, {
														icon : data.alert,
														buttons: {        			
															confirm: {
																className : 'btn btn-error'
															}
														},
													});
												$("#buton").html('<input value="Proses Pembayaran" id="bayar" class="btn btn-block btn-primary text-light " ></input>');
											}
	
											},
											error: function(error) {
												$("#buton").html('<input value="Proses Pembayaran" id="bayar" class="btn btn-block btn-primary text-light" ></input>');
												 $("#text").html('Transaksi Gagal. Coba lagi nanti...');
	
	
											},
										});
	
	
	
									});
								}  else {
									document.getElementById('hidden_div').style.display = "none";
									$("#progress").html('');
									$("#text").html('<strong>No. Invoice/Id Pelanggan tidak ditemukan</strong>');
								}
								}
							},
							error: function(error) {
								$("#progress").html('');
								$("#text").html('error. Coba lagi nanti...');
							},
	
						});
					} else {
						document.getElementById('hidden_div').style.display = "none";
								$("#progress").html('');
								$("#text").html('Masukan No. Invoice/Id Pelanggan terlebih dahulu');
						$('#harga').empty();
					}
  
 });
});
	</script>
	<script>
		$('#lineChart').sparkline([102,109,120,99,110,105,115], {
			type: 'line',
			height: '70',
			width: '100%',
			lineWidth: '2',
			lineColor: '#177dff',
			fillColor: 'rgba(23, 125, 255, 0.14)'
		});

		$('#lineChart2').sparkline([99,125,122,105,110,124,115], {
			type: 'line',
			height: '70',
			width: '100%',
			lineWidth: '2',
			lineColor: '#f3545d',
			fillColor: 'rgba(243, 84, 93, .14)'
		});

		$('#lineChart3').sparkline([105,103,123,100,95,105,115], {
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
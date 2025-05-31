
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
	  $(document).ready(function() {
		  //format mac adrress Aktivasi pelanggan
		  document.getElementById("mac1").addEventListener('keyup', function() { 
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
						$('.href_input_data').click(function(){
							var id =$(this).data("id");
							var url = '{{ route("admin.psb.input_data_update_view", ":id") }}';
							url = url.replace(':id', id);
							window.location=url;
						});
	//--------------------END TABLE CLICK EDIT DATA PELANGGAN----------------------
	//--------------------START TABLE CLICK EDIT DATA PELANGGAN----------------------
	// PsbController -> index.blade.php					
						$('.href').click(function(){
							var id =$(this).data("id");
							var url = '{{ route("admin.reg.form_update_pelanggan", ":id") }}';
							url = url.replace(':id', id);
							window.location=url;
						});
	//--------------------END TABLE CLICK EDIT DATA PELANGGAN----------------------

	//--------------------START TABLE CLICK DATA BARANG---------------------
						$('.href_data_barang').click(function(){
							var kategori =$(this).data("kategori");
							var url = '{{ route("admin.gudang.data_barang", ":find_kate") }}';
							url = url.replace(':find_kate', 'find_kate='+kategori);
							window.location=url;
						});
	//--------------------END TABLE CLICK DATA BARANG---------------------

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
				"pageLength": 200,
			});
			$('#tiket_pilih_pelanggan').DataTable({
				"pageLength": 10,
			});

		});

		$(document).ready(function() {
} );


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
			var url = '{{ route("admin.topo.cekRouter", ":id") }}';
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
				$('#biaya_bph_uso').val(0);
				$(".checkboxbiaya_bph_uso").prop('checked', false);
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
								$('.checkboxbiaya_bph_uso').change(function () {
									if ($(this).is(":checked")) {
										var result1 = parseInt(data['data_biaya']['biaya_bph_uso'])/100 * parseInt(data['data_paket'][0]['paket_harga']);
										if (!isNaN(result1)) {
											$('#biaya_bph_uso').val(result1);
										}
									} else {
										$("#biaya_bph_uso").val(0);
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
		// START AMBIL HARGA PAKET #HOTSPOT


$('#paket_hotspot').on('change', function() {
					$('#vhc_hpp').empty();
                    $('#vhc_hjk').empty();
                    $('#vhc_komisi').empty();
                var kode_paket = $(this).val();
                var url = '{{ route("admin.vhc.getPaketHotspot", ":id") }}';
				url = url.replace(':id', kode_paket);
                if (kode_paket) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
                            console.log(data)
                            if (data) {
                                $('#vhc_hpp').empty();
                                $('#vhc_hjk').empty();
                                $('#vhc_komisi').empty();
								var komisi = data['data_paket'][0]['paket_komisi'];
								var hpp = data['data_paket'][0]['paket_harga'];
								var vhc_jumlah = $('#vhc_jumlah').val();
								var total = parseInt(hpp) + parseInt(komisi);
								if (isNaN(total)) {
									total = '0';
								}
								var total_komisi = parseInt(komisi) * parseInt(vhc_jumlah);
							  		if (isNaN(total_komisi)) {
										total_komisi = '0';
									}
								var vhc_total_hpp = parseInt(hpp) * parseInt(vhc_jumlah);
							  		if (isNaN(vhc_total_hpp)) {
										vhc_total_hpp = '0';
									}
                                $('#vhc_hpp').val(hpp);
                                $('#vhc_hjk').val(total);
                                $('#vhc_komisi').val(komisi);
                                $('#total_komisi').val(total_komisi);
                                $('#vhc_total_hpp').val(vhc_total_hpp);
                            } else {
								$('#vhc_hpp').empty();
                                $('#vhc_hjk').empty();
                                $('#vhc_komisi').empty();
                            }
                        }
                    });
                } else {
					$('#vhc_hpp').empty();
                  $('#vhc_hjk').empty();
                  $('#vhc_komisi').empty();
                }
        });

		$(document).ready(function() {
			$("#vhc_jumlah").keyup(function() {
				var keyup_hpp = $('#vhc_hpp').val();
                var vhc_jumlah = $('#vhc_jumlah').val();
                var keyup_komisi = $('#vhc_komisi').val();
				var total_komisi = parseInt(keyup_komisi) * parseInt(vhc_jumlah);
				if (isNaN(total_komisi)) {
					total_komisi = '0';
				}
				var vhc_total_hpp = parseInt(keyup_hpp) * parseInt(vhc_jumlah);
				if (isNaN(vhc_total_hpp)) {
					vhc_total_hpp = '0';
				}
				$('#total_komisi').val(total_komisi);
				$('#vhc_total_hpp').val(vhc_total_hpp);
			});
		});
      
// END AMBIL HARGA PAKET #HOTSPOT



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
																		// console.log(olt);
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







// validasi_tiket_odp

$('#tiket_odp').keyup(function() {
var tiket_odp = $('#tiket_odp').val();
var url = '{{ route("admin.tiket.tiket_validasi_odp", ":id") }}';
url = url.replace(':id', tiket_odp);
// console.log(validasi_odp)
$.ajax({
	url: url,
			type: 'GET',
			data: {'_token': '{{ csrf_token() }}'},
						dataType: 'json',
						success: function(data) {
							// console.log(data.odp_kode)
							if(data.odp_kode){
								$('#tiket_olt').val(data.olt_kode)
								$('#tiket_odc').val(data.odc_kode)
								$('.read').readOnly = true
								$('.notif_valtiket').removeClass('has-error has-feedback')
								$('.notif_valtiket').addClass('has-success has-feedback')
								$('#pesan').html('')
							} else{
								$('#tiket_odc').val('')
								$('#tiket_olt').val('')
								$('#pesan').html('<small id="text" class="form-text text-muted text-danger">Kode ODP tidak ditemukan</small>')
								}
						},
						error: function(error) {
							// console.log(error)

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
								$("#terpakai").val(data.barang_digunakan);
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
							$('.notif_dropcore').addClass('has-error has-feedback') //NOTIF UNTUK UPDATE TIKET
							$('#pesan_over').html('<small id="text" class="form-text text-muted text-danger">Panjang kabel tidak cukup</small>')
							$('#pesan_dropcore').html('<small id="text" class="form-text text-muted text-danger">Panjang kabel tidak cukup</small>')
							$(".submit_tiket").attr('disabled','disabled');//STATUS BARANG DROPCORE JIKA HABIS DISABLE TOMBOL SUMBIT
							$("#status_dropcore").val('');//STATUS BARANG DROPCORE JIKA BELUM HABIS
						}else{
							if(hasil == 0){
								$('#pesan_over').html('')
								$('.notif_over').removeClass('has-error has-feedback')//NOTIF UNTUK AKTIVASI
								$('.notif_dropcore').removeClass('has-error has-feedback') //NOTIF UNTUK UPDATE TIKET
								$('.notif_over').addClass('has-success has-feedback')//NOTIF UNTUK AKTIVASI
								$('.notif_dropcore').addClass('has-success has-feedback')//NOTIF UNTUK UPDATE TIKET
								$("#total").val(hasil);
								$("#status_dropcore").val('1');//STATUS BARANG DROPCORE JIKA HABIS
							} else{
								$('#pesan_over').html('')
								$('.notif_over').removeClass('has-error has-feedback')//NOTIF UNTUK AKTIVASI
								$('.notif_dropcore').removeClass('has-error has-feedback') //NOTIF UNTUK UPDATE TIKET
								$('.notif_over').addClass('has-success has-feedback')//NOTIF UNTUK AKTIVASI
								$('.notif_dropcore').addClass('has-success has-feedback')//NOTIF UNTUK UPDATE TIKET
								$("#total").val(hasil);
								$("#status_dropcore").val('0');//STATUS BARANG DROPCORE JIKA BELUM HABIS
								$(".submit_tiket").removeAttr('disabled');//STATUS BARANG DROPCORE JIKA HABIS ENABLE TOMBOL SUMBIT
							}
						}
					}

            });
            });

		



//--------- start get kode site-------------
									$('#pop_kode').on('change', function() {
										var pop_kode = $(this).val();
										var url = '{{ route("admin.reg.getKodeSite", ":id") }}';
										url = url.replace(':id', pop_kode);
										// console.log(pop_kode)
										$.ajax({
													url: url,
													type: 'GET',
													data: {
																	'_token': '{{ csrf_token() }}'
																},
																dataType: 'json',
																success: function(data) {
																	// console.log(data)
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

$(document).ready(function() {
				var button = $('#Button');
    			$(button).attr('disabled', 'disabled');
				$('#paket_router').on('change', function() {
					var kode_roter = $(this).val();
					var url = '{{ route("admin.router.noc.getRouter", ":id") }}';
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



					
						// START DETAIL INVOICE
						$('.href_inv').click(function(){
							var id =$(this).data("id");
							var url = '{{ route("admin.inv.sub_invoice", ":id") }}';
							url = url.replace(':id', id);
							// alert(url);
							window.location=url;
						});
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
						$('#tunai').hide();
						$('#transfer').show();
						$('#transfer').attr('required', 'required');;
						$('#jb').show();
						$('#bb').show();
						$('#jb').attr('required', 'required');;
						$('#bb').attr('required', 'required');;
						$('#tunai').removeAttr('required');
					} else {
						$('#tunai').show();
						$('#tunai').attr('required', 'required');;
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
			// DETAILS TIKET
			$('.tiket').click(function(){
				var id =$(this).data("id");
				var url = '{{ route("admin.tiket.details_tiket", ":id") }}';
				url = url.replace(':id', id);
				// alert(url);
				window.location=url;
			});
			// DETAILS TIKET PROJECT
			$('.tiket_project').click(function(){
				var id =$(this).data("id");
				var url = '{{ route("admin.tiket.details_tiket_project", ":id") }}';
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

    // console.log(e.target.dataset.price)
    
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
	// console.log(e.target.dataset.price) 
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


			var url = '{{ route("admin.trx.konfirm_pencairan") }}';
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


			var url = '{{ route("admin.trx.konfirm_pencairan") }}';
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
							// console.log(data);

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

					//--------------------START DEAKTIVASI----------------------
					$(document).ready(function() {
					// 
					$('.simpan_deaktivasi').attr('disabled','disabled');
					$('select[name=kelengkapan]').change(function () {
						if ($(this).val() == 'ONT') {
							$('#deaktivasi_mac').val('');
							$('#deaktivasi_sn').val('');
							$('#kode_barang_ont').val('');
							$('#kode_barang_adp').val('');
							$('.pernyataan_1').hide()
							$('.pernyataan_2').hide()
							$('.cek_id').hide()
							$('.cek_mac').show()
							$('.div_pernyataan').show();
							$('#deaktivasi_mac').attr('required', 'required');
							$('#deaktivasi_sn').attr('required', 'required');
							$('#kode_barang_ont').attr('required', 'required');
							$('#kode_barang_adp').attr('required', 'required');
							$('#deaktivasi_pernyataan1').attr('required', 'required');
							$('#deaktivasi_pengambil_perangkat').attr('required', 'required');
							$('#deaktivasi_alasan_deaktivasi').attr('required', 'required');
							$('#deaktivasi_tanggal_pengambilan').attr('required', 'required');
							$('.pernyataan_1').show()
							$('.simpan_deaktivasi').attr('disabled','disabled');
						} else if($(this).val() == 'ONT & Adaptor') { 
							$('#deaktivasi_mac').val('');
							$('#deaktivasi_sn').val('');
							$('#kode_barang_ont').val('');
							$('#kode_barang_adp').val('');
							$('.pernyataan_1').hide()
							$('.pernyataan_2').hide()
							$('.div_pernyataan').hide()
							$('.cek_id').hide()
							$('.cek_mac').show();
							$('#deaktivasi_mac').attr('required', 'required');
							$('#deaktivasi_sn').attr('required', 'required');
							$('#kode_barang_ont').attr('required', 'required');
							$('#kode_barang_adp').attr('required', 'required');
							$('#deaktivasi_pengambil_perangkat').attr('required', 'required');
							$('#deaktivasi_alasan_deaktivasi').attr('required', 'required');
							$('#deaktivasi_tanggal_pengambilan').attr('required', 'required');
							$('.simpan_deaktivasi').attr('disabled','disabled');
						} else if($(this).val() == 'Hilang') { 
							$('#deaktivasi_mac').val('');
							$('#deaktivasi_sn').val('');
							$('#kode_barang_ont').val('');
							$('#kode_barang_adp').val('');
							$('#kode_barang_ont').attr('required', 'required');
							$('#kode_barang_adp').attr('required', 'required');
							$('.pernyataan_1').hide()
							$('.pernyataan_2').show()
							$('.div_pernyataan').show()
							$('#deaktivasi_mac').attr('required', 'required');
							$('#deaktivasi_sn').attr('required', 'required');
							$('.cek_mac').hide();
							$('.cek_id').show()
							// $('.div_ont').hide();
							// $('.div_adp').hide();
							$('#deaktivasi_pernyataan1').attr('required', 'required');
							$('#deaktivasi_pernyataan2').attr('required', 'required');
							$('#deaktivasi_pengambil_perangkat').attr('required', 'required');
							$('#deaktivasi_alasan_deaktivasi').attr('required', 'required');
							$('#deaktivasi_tanggal_pengambilan').attr('required', 'required');
							$('.simpan_deaktivasi').removeAttr('disabled');
						
						}
					});
					});

					$('#cek_perangkat').click(function(){
						var mac = $('#deaktivasi_mac').val()
						var idpel = $('#tampil_idpel').val()
						var kelengkapan = $('#kelengkapan').val()
						var url = '{{ route("admin.reg.cek_perangkat", ":id") }}';
									url = url.replace(':id', idpel);
									$.ajax({
										url: url,
											type: 'POST',
											data: {
												mac:mac,
												kelengkapan:kelengkapan,
												'_token': '{{ csrf_token() }}'
											},
											dataType: 'json',
											success: function(data) {
												if(data){
													if(data == 0){
														// barang digunkan oleh pelanggan lain
														$('.simpan_deaktivasi').attr('disabled','disabled');
														swal("Gagal!", "barang digunkan oleh pelanggan lain.", {
															icon : "error",
															buttons: {        			
																confirm: {
																	className : 'btn btn-error'
																}
															},
														});
													} else if(data == 1){
														// barang belum digunakan
														$('.simpan_deaktivasi').attr('disabled','disabled');
														swal("Gagal!", "Barang belum digunakan.", {
															icon : "error",
															buttons: {        			
																confirm: {
																	className : 'btn btn-error'
																}
															},
														});
													} else if(data == 2){
														swal("Gagal!", "Mac tidak ditemukan.", {
															icon : "error",
															buttons: {        			
																confirm: {
																	className : 'btn btn-error'
																}
															},
														});
														$('.simpan_deaktivasi').attr('disabled','disabled');
													} else {
														$('#deaktivasi_sn').val(data['barang_sn'])
														$('#kode_barang_ont').val(data['barang_id_ont'])
														$('#kode_barang_adp').val(data['barang_id_adp'])
														$('.simpan_deaktivasi').removeAttr('disabled');
														
														console.log(data);
													}
												}
											}
										});
					});
					$('#cek_id').click(function(){
						// var mac = $('#mac').val()
						var idpel = $('#tampil_idpel').val()
						// alert(idpel)
						var kelengkapan = $('#kelengkapan').val()
						var url = '{{ route("admin.reg.cek_perangkat_hilang", ":id") }}';
									url = url.replace(':id', idpel);
									$.ajax({
										url: url,
											type: 'GET',
											data: {
												kelengkapan:kelengkapan,
												'_token': '{{ csrf_token() }}'
											},
											dataType: 'json',
											success: function(data) {
												if(data){
													if(data == 0){
														// barang digunkan oleh pelanggan lain
														$('.simpan_deaktivasi').attr('disabled','disabled');
														swal("barang digunkan oleh pelanggan lain.", {
															icon : "warning",
															buttons: {        			
																confirm: {
																	className : 'btn btn-error'
																}
															},
														});
													} else if(data == 1){
														// barang belum digunakan
														$('.simpan_deaktivasi').attr('disabled','disabled');
														swal("Barang tidak ditemukan.", {
															icon : "error",
															buttons: {        			
																confirm: {
																	className : 'btn btn-error'
																}
															},
														});
													}  else {
														$('#deaktivasi_mac').val(data['barang_mac'])
														$('#deaktivasi_sn').val(data['barang_sn'])
														$('#kode_barang_ont').val(data['barang_id_ont'])
														$('#kode_barang_adp').val(data['barang_id_adp'])
														$('.simpan_deaktivasi').removeAttr('disabled');
														
														console.log(data);
													}
												}
											}
										});
					});
		
					//--------------------END DEAKTIVASI----------------------

						//--------------------START BARANG KELUAR----------------------
						$('.button_masukan').click(function(){
								var i = 1;
								if($('#jumlah_barang').val()==0){
									$('.pesan_jumlah').html('<small id="text" class="form-text text-muted text-danger">Jumlah tidak boleh kosong</small>')
									$('.notif_jumlah').addClass('has-error has-feedback')
								} else {
									$('.pesan_jumlah').html('')
									$('.notif_jumlah').removeClass('has-error has-feedback')
									var jumlah_barang = $('#jumlah_barang').val();
									var kode_barang = $('#barang_id').val();
									var url = '{{ route("admin.val.valBarang", ":id") }}';
									url = url.replace(':id', kode_barang);
									$.ajax({
										url: url,
											type: 'GET',
											data: {
												'_token': '{{ csrf_token() }}'
											},
											dataType: 'json',
											success: function(data) {
												if(data.barang_id){
													let stok_barang = parseInt(data.barang_qty) - parseInt(data.barang_digunakan) - parseInt(data.barang_dijual) - parseInt(data.barang_rusak) - parseInt(data.barang_dicek);
														if( stok_barang != 0){
													// if(data.barang_qty - data.barang_digunakan != 0){
														if(data.barang_qty - data.barang_digunakan < $('#jumlah_barang').val()){
														$('.pesan_jumlah').html('<small id="text" class="form-text text-muted text-danger">Jumlah barang melebih batas stok</small>')
														$('.notif_jumlah').addClass('has-error has-feedback')
													} else {
														let before = parseInt(data.barang_qty) - parseInt(data.barang_digunakan);
														let after = parseInt(data.barang_qty) - parseInt(data.barang_digunakan) - parseInt(jumlah_barang) ;
														let terpakai = parseInt(data.barang_digunakan) ;
														if(data.barang_kategori == 'ONT'){
															if(data.barang_mac == null && data.barang_mac_olt == null && data.barang_sn == null ){
																$('.notif_barang_id').addClass('has-error has-feedback')
																$('.pesan_barang_id').html('<small id="text" class="form-text text-muted text-danger">Mac Address / Mac Address OLT/ Serial Number belum di update </small>')
															} else {
																
																$('.notif_barang_id').removeClass('has-error has-feedback')
																$('.pesan_barang_id').html('')
																i = i + 1
																var html ='<tr id="'+data.barang_id+'" '+data.barang_id+' >';
																	html += '<td contenteditable="true" class="barang_id">'+data.barang_id+'</td>';
																	html += '<td contenteditable="true" class="barang_kategori">'+data.barang_kategori+'</td>';
																	html += '<td contenteditable="true" class="barang_nama">'+data.barang_nama+'</td>';
																	html += '<td contenteditable="true" >'+data.barang_merek+'</td>';
																	html += '<td contenteditable="true" >'+data.barang_harga_satuan+'</td>';
																	html += '<td contenteditable="true" class="before">'+before+'</td>';
																	html += '<td contenteditable="true" class="jumlah_barang">'+jumlah_barang+'</td>';
																	html += '<td contenteditable="true" class="after">'+after+'</td>';
																	html += '<td contenteditable="true" class="terpakai">'+terpakai+'</td>';
																	html += '<td contenteditable="true" class="jumlah_harga">'+data.barang_harga_satuan * jumlah_barang+'</td>';
																	html += '<td><button type="button" class="btn btn-sm btn-danger" data-row="'+data.barang_id+'" '+data.barang_id+' id="hapus"> Hapus</button></td>';
																	html += '</tr>';
					
																$('#t').append(html)
																$('.simpan').removeAttr('disabled');
															}
														} else{
															$('.notif_barang_id').removeClass('has-error has-feedback')
															$('.pesan_barang_id').html('')
															i = i + 1
															var html ='<tr id="'+data.barang_id+'" '+data.barang_id+' >';
																html += '<td contenteditable="true" class="barang_id">'+data.barang_id+'</td>';
																html += '<td contenteditable="true" class="barang_kategori">'+data.barang_kategori+'</td>';
																html += '<td contenteditable="true" class="barang_nama">'+data.barang_nama+'</td>';
																html += '<td contenteditable="true" >'+data.barang_merek+'</td>';
																html += '<td contenteditable="true" >'+data.barang_harga_satuan+'</td>';
																html += '<td contenteditable="true" class="before">'+before+'</td>';
																html += '<td contenteditable="true" class="jumlah_barang">'+jumlah_barang+'</td>';
																html += '<td contenteditable="true" class="after">'+after+'</td>';
																html += '<td contenteditable="true" class="terpakai">'+terpakai+'</td>';
																html += '<td contenteditable="true" class="jumlah_harga">'+data.barang_harga_satuan * jumlah_barang+'</td>';
																html += '<td><button type="button" class="btn btn-sm btn-danger" data-row="'+data.barang_id+'" '+data.barang_id+' id="hapus"> Hapus</button></td>';
																html += '</tr>';
				
															$('#t').append(html)
															$('.simpan').removeAttr('disabled');
														}
													}
													} else {
														$('.notif_barang_id').addClass('has-error has-feedback')
														$('.pesan_barang_id').html('<small id="text" class="form-text text-muted text-danger">Stok habis atau barang belum dicek</small>')
														
													}
												} else {
													$('.notif_barang_id').addClass('has-error has-feedback')
													$('.pesan_barang_id').html('<small class="form-text text-muted text-danger">Kode barang tidak ditemukan</small>')
	
												}
											}
										});
								}


								});  
								$(document).on('click','#hapus', function(){
								   let hapus = $(this).data('row')
								   $('#' + hapus).remove()
								});

								$(document).on('click','.simpan', function(){
									
									
									let bk_jenis_laporan =$('#bk_jenis_laporan').val()
									let bk_penerima =$('#bk_penerima').val()
									let bk_keperluan =$('#bk_keperluan').val()
									let tiket_type =$('#tiket_type').val()
									let tiket_site =$('#tiket_site').val()
									let bk_waktu_keluar =$('#bk_waktu_keluar').val()
									

										if(bk_jenis_laporan != "" && bk_penerima != "" && bk_keperluan != "" && tiket_site != "" && tiket_type != ""){
											$('.notif1').removeClass('has-error has-feedback')

										let barang_id = []
										let barang_nama = []
										let jumlah_harga = []
										let jumlah_barang = []
										let barang_kategori = []
										let before = []
										let after = []
										let terpakai = []
										// console.log(barang_id)
										$('.barang_id').each(function(){
											barang_id.push($(this).text())
										})
										$('.barang_nama').each(function(){
											barang_nama.push($(this).text())
										})
										$('.jumlah_harga').each(function(){
											jumlah_harga.push($(this).text())
										})
										$('.jumlah_barang').each(function(){
											jumlah_barang.push($(this).text())
										})
										$('.barang_kategori').each(function(){
											barang_kategori.push($(this).text())
										})
										$('.before').each(function(){
											before.push($(this).text())
										})
										$('.after').each(function(){
											after.push($(this).text())
										})
										$('.terpakai').each(function(){
											terpakai.push($(this).text())
										})
										var url = '{{ route("admin.gudang.proses_form_barang_keluar") }}';
									$.ajax({
									url: url,
									type: 'POST',
									data: {
										barang_id:barang_id,
										jumlah_harga:jumlah_harga,
										jumlah_barang:jumlah_barang,
										bk_penerima:bk_penerima,
										barang_kategori:barang_kategori,
										bk_jenis_laporan:bk_jenis_laporan,
										bk_keperluan:bk_keperluan,
										barang_nama:barang_nama,
										tiket_type:tiket_type,
										tiket_site:tiket_site,
										bk_waktu_keluar:bk_waktu_keluar,
										before:before,
										after:after,
										terpakai:terpakai,
										'_token': '{{ csrf_token() }}'},
									dataType: 'json',
									success: function(data) {
											if(data == 'failed'){
												swal("Gagal!", "No Skb sudah ada. Silahkan coba klik simpan kembali.", {
													icon : "error",
													buttons: {        			
														confirm: {
															className : 'btn btn-error'
														}
													},
												});
											} else {
											$('.simpan').attr('disabled','disabled');
													swal("Berhasil!", "Berhasil menyimpan data barang keluar ke database", {
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


										} else{
											$('.notif1').addClass('has-error has-feedback')
										}
								});
								// });
								//--------------------END BARANG KELUAR----------------------
			

						//--------------------START TIKET BARANG KELUAR----------------------
							$('.button_tambah_barang').click(function(){
								var i = 1;
								if($('#jumlah_barang').val()==0){
									$('.pesan_jumlah').html('<small id="text" class="form-text text-muted text-danger">Jumlah tidak boleh kosong</small>')
									$('.notif_jumlah').addClass('has-error has-feedback')
								} else {
									$('.pesan_jumlah').html('')
									$('.notif_jumlah').removeClass('has-error has-feedback')
									var jumlah_barang = $('#jumlah_barang').val();
									var kode_barang = $('#barang_id').val();
									var url = '{{ route("admin.val.valBarang", ":id") }}';
									url = url.replace(':id', kode_barang);
									$.ajax({
										url: url,
											type: 'GET',
											data: {
												'_token': '{{ csrf_token() }}'
											},
											dataType: 'json',
											success: function(data) {
												if(data.barang_id){
												
													// if(data.barang_qty - data.barang_digunakan != 0){
														let stok_barang = parseInt(data.barang_qty) - parseInt(data.barang_digunakan) - parseInt(data.barang_dijual) - parseInt(data.barang_rusak) - parseInt(data.barang_dicek);
														if( stok_barang != 0){
														if(data.barang_qty - data.barang_digunakan < $('#jumlah_barang').val()){
														$('.pesan_jumlah').html('<small id="text" class="form-text text-muted text-danger">Jumlah barang melebih batas stok</small>')
														$('.notif_jumlah').addClass('has-error has-feedback')
														} else {
															let before = parseInt(data.barang_qty) - parseInt(data.barang_digunakan);
															let after = parseInt(data.barang_qty) - parseInt(data.barang_digunakan) - parseInt(jumlah_barang) ;
															let terpakai = parseInt(data.barang_digunakan) ;
															if(data.barang_kategori == 'ONT'){
																if(data.barang_mac == null && data.barang_mac_olt == null && data.barang_sn == null ){
																	$('.notif_barang_id').addClass('has-error has-feedback')
																	$('.pesan_barang_id').html('<small id="text" class="form-text text-muted text-danger">Mac Address / Mac Address OLT/ Serial Number belum di update </small>')
																} else {
																	
																	$('.notif_barang_id').removeClass('has-error has-feedback')
																	$('.pesan_barang_id').html('')
																	i = i + 1
																	var html ='<tr id="'+data.barang_id+'" '+data.barang_id+' >';
																		html += '<td contenteditable="true" class="barang_id">'+data.barang_id+'</td>';
																		html += '<td contenteditable="true" class="barang_kategori">'+data.barang_kategori+'</td>';
																		html += '<td contenteditable="true" class="barang_nama">'+data.barang_nama+'</td>';
																		html += '<td contenteditable="true" >'+data.barang_merek+'</td>';
																		html += '<td contenteditable="true" >'+data.barang_harga_satuan+'</td>';
																		html += '<td contenteditable="true" class="before">'+before+'</td>';
																		html += '<td contenteditable="true" class="jumlah_barang">'+jumlah_barang+'</td>';
																		html += '<td contenteditable="true" class="after">'+after+'</td>';
																		html += '<td contenteditable="true" class="terpakai">'+terpakai+'</td>';
																		html += '<td contenteditable="true" class="jumlah_harga">'+data.barang_harga_satuan * jumlah_barang+'</td>';
																		html += '<td><button type="button" class="btn btn-sm btn-danger" data-row="'+data.barang_id+'" '+data.barang_id+' id="hapus"> Hapus</button></td>';
																		html += '</tr>';
						
																	$('#t').append(html)
																	$('.simpan').removeAttr('disabled');
																}
															} else{
																$('.notif_barang_id').removeClass('has-error has-feedback')
																$('.pesan_barang_id').html('')
																i = i + 1
																var html ='<tr id="'+data.barang_id+'" '+data.barang_id+' >';
																	html += '<td contenteditable="true" class="barang_id">'+data.barang_id+'</td>';
																	html += '<td contenteditable="true" class="barang_kategori">'+data.barang_kategori+'</td>';
																	html += '<td contenteditable="true" class="barang_nama">'+data.barang_nama+'</td>';
																	html += '<td contenteditable="true" >'+data.barang_merek+'</td>';
																	html += '<td contenteditable="true" >'+data.barang_harga_satuan+'</td>';
																	html += '<td contenteditable="true" class="before">'+before+'</td>';
																	html += '<td contenteditable="true" class="jumlah_barang">'+jumlah_barang+'</td>';
																	html += '<td contenteditable="true" class="after">'+after+'</td>';
																	html += '<td contenteditable="true" class="terpakai">'+terpakai+'</td>';
																	html += '<td contenteditable="true" class="jumlah_harga">'+data.barang_harga_satuan * jumlah_barang+'</td>';
																	html += '<td><button type="button" class="btn btn-sm btn-danger" data-row="'+data.barang_id+'" '+data.barang_id+' id="hapus"> Hapus</button></td>';
																	html += '</tr>';
					
																$('#t').append(html)
																$('.simpan').removeAttr('disabled');
															}
														}
													} else {
														$('.notif_barang_id').addClass('has-error has-feedback')
														$('.pesan_barang_id').html('<small id="text" class="form-text text-muted text-danger">Stok habis</small>')
														
													}
												} else {
													$('.notif_barang_id').addClass('has-error has-feedback')
													$('.pesan_barang_id').html('<small class="form-text text-muted text-danger">Kode barang tidak ditemukan</small>')
	
												}
											}
										});
								}


								});  
								$(document).on('click','#hapus', function(){
								   let hapus = $(this).data('row')
								   $('#' + hapus).remove()
								});

								$(document).on('click','.simpan_barang_tiket', function(){
									
									
									let tiket_jenis =$('#tiket_jenis').val()
									let tiket_teknisi1 =$('#tiket_teknisi1').val()
								  	let tiket_tindakan =$('#tiket_tindakan').val()
								  	let tiket_type =$('#tiket_type').val()
								  	let tiket_site =$('#tiket_site').val()
								  	let tiket_id =$('#tiket_id').val()
								  	let tiket_idpel =$('#tiket_idpel').val()
									  
							
										let barang_id = []
										let barang_nama = []
										let jumlah_harga = []
										let jumlah_barang = []
										let barang_kategori = []
										let before = []
										let after = []
										let terpakai = []
										
										// console.log(barang_id)
										$('.barang_id').each(function(){
											barang_id.push($(this).text())
										})
										$('.barang_nama').each(function(){
											barang_nama.push($(this).text())
										})
										$('.jumlah_harga').each(function(){
											jumlah_harga.push($(this).text())
										})
										$('.jumlah_barang').each(function(){
											jumlah_barang.push($(this).text())
										})
										$('.barang_kategori').each(function(){
											barang_kategori.push($(this).text())
										})
										$('.before').each(function(){
											before.push($(this).text())
										})
										$('.after').each(function(){
											after.push($(this).text())
										})
										$('.terpakai').each(function(){
											terpakai.push($(this).text())
										})
										var url = '{{ route("admin.gudang.proses_tiket_form_barang_keluar") }}';
									$.ajax({
									url: url,
									type: 'POST',
									data: {
										barang_id:barang_id,
										jumlah_harga:jumlah_harga,
										jumlah_barang:jumlah_barang,
										tiket_teknisi1:tiket_teknisi1,
										barang_kategori:barang_kategori,
										tiket_jenis:tiket_jenis,
										tiket_tindakan:tiket_tindakan,
										barang_nama:barang_nama,
										tiket_type:tiket_type,
										tiket_site:tiket_site,
										tiket_id:tiket_id,
										tiket_idpel:tiket_idpel,
										before:before,
										after:after,
										terpakai:terpakai,
										'_token': '{{ csrf_token() }}'},
									dataType: 'json',
									success: function(data) {
										console.log(data)
										if(data == 'failed'){
												swal("Gagal!", "No Skb sudah ada. Silahkan coba klik simpan kembali.", {
													icon : "error",
													buttons: {        			
														confirm: {
															className : 'btn btn-error'
														}
													},
												});
											} else {
											$('#jumlah_barang').removeAttr('required');
											$('.simpan_barang_tiket').attr('disabled','disabled');
											$('.tiket_noskb').attr('required','required');
											$('#modal_tambah_barang').modal('hide');
											$('#tiket_noskb').val(data);
											$('.submit_tiket').removeAttr('disabled');
											}
										}
									});
								});
								// });
								//--------------------END TIKET BARANG KELUAR----------------------

					//--------------------START TIKET CLOSE GANTI BARANG----------------------
					$(document).ready(function() {
					// 
					
					});

					$('#tiket_cek_ont').click(function(){
						var mac = $('#ganti_mac').val()
						var idpel = $('#tiket_idpel').val()
						// console.log(idpel );
						var url = '{{ route("admin.tiket.tiket_cek_ont", ":id") }}';
						url = url.replace(':id', idpel);
						// console.log(idpel)
									$.ajax({
										url: url,
											type: 'POST',
											data: {
												mac:mac,
												'_token': '{{ csrf_token() }}'
											},
											dataType: 'json',
											success: function(data) {
												console.log(data)
												if(data){
													if(data == 0){
														// barang digunkan oleh pelanggan lain
														$('.submit_tiket').attr('disabled','disabled');
														swal("barang belum digunakan atau digunakan oleh pelanggan lain.", {
															icon : "warning",
															buttons: {        			
																confirm: {
																	className : 'btn btn-error'
																}
															},
														});
													} else if(data == 1){
														// barang belum digunakan
														$('.submit_tiket').attr('disabled','disabled');
														swal("Tidak ada barang yang digunakan oleh pelanggan ini.", {
															icon : "warning",
															buttons: {        			
																confirm: {
																	className : 'btn btn-error'
																}
															},
														});
													} else if(data == 2){
														swal("Mac tidak ditemukan.", {
															icon : "warning",
															buttons: {        			
																confirm: {
																	className : 'btn btn-error'
																}
															},
														});
														$('.submit_tiket').attr('disabled','disabled');
													} else {
														$('#ganti_sn').val(data['barang_sn'])
														$('#kode_barang_ont').val(data['barang_id'])
														$('.submit_tiket').removeAttr('disabled');
														
														
														// console.log(data);
													}
												}
											}
										});
					});
					$('#tiket_cek_id').click(function(){
						// var mac = $('#mac').val()
						var idpel = $('#tiket_idpel').val()
						// alert(idpel)
						var url = '{{ route("admin.tiket.tiket_cek_adp", ":id") }}';
									url = url.replace(':id', idpel);
									$.ajax({
										url: url,
											type: 'GET',
											data: {
												'_token': '{{ csrf_token() }}'
											},
											dataType: 'json',
											success: function(data) {
												// console.log(data);
												if(data){
													if(data == 0){
														// barang digunkan oleh pelanggan lain
														$('.submit_tiket').attr('disabled','disabled');
														swal("barang belum digunakan atau barang digunakan oleh pelanggan lain.", {
															icon : "warning",
															buttons: {        			
																confirm: {
																	className : 'btn btn-error'
																}
															},
														});
													} else if(data == 1){
														// barang belum digunakan
														$('.submit_tiket').attr('disabled','disabled');
														swal( "Tidak ada barang yang digunakan oleh pelanggan ini.", {
															icon : "warning",
															buttons: {        			
																confirm: {
																	className : 'btn btn-error'
																}
															},
														});
													}  else {
														$('#kode_barang_adp').val(data['barang_id_adp'])
													}
												}
											}
										});
					});
		
					//--------------------END TIKET CLOSE GANTI BARANG----------------------

						//-----------------------START CLOSED TIKET NEW ---------------------------
							$('select[name=tiket_status]').change(function () {
								if ($(this).val() == 'Pending') {
									$('.div_tiket_ket_pending').show();
									$('#tiket_ket_pending').attr('required', 'required');
									$('#tiket_teknisi1').attr('required', 'required');
									$('#tiket_teknisi2').attr('required', 'required');
									$('#tiket_kendala').removeAttr('required');
									$('#kate_tindakan').removeAttr('required');
									$('#tiket_tindakan').removeAttr('required');
									$('#tiket_foto').removeAttr('required');
									$('#tiket_noskb').removeAttr('required');
								} else if ($(this).val() == 'Closed') {
									$('.div_tiket_closed').show();
									$('.div_tiket_ket_pending').hide();
									$('#tiket_ket_pending').removeAttr('required');
									// $('#tiket_waktu_penanganan').attr('required', 'required');
									
									$('#kate_tindakan').attr('required', 'required');
									
									$('#tiket_kendala').attr('required', 'required');
									$('#tiket_tindakan').attr('required', 'required');
									if($('#tiket_nama').val() == 'Instalasi PSB'){
										$('.div_tiket_teknisi').hide();
										$('#tiket_teknisi1').removeAttr('required');
										$('#tiket_teknisi2').removeAttr('required');
										$('#tiket_kendala').val($('#tiket_nama').val())
										$('#tiket_tindakan').val($('#tiket_nama').val())
										$('#tiket_pop').removeAttr('required');
										$('#tiket_olt').removeAttr('required');
										$('#tiket_odc').removeAttr('required');
										$('#tiket_odp').removeAttr('required');
										$('#tiket_noskb').attr('required', 'required');
										
										$('.button_modal_barang').click(function(){
											if($('#tiket_kendala').val()!= "" && $('#tiket_tindakan').val()!= ""&& $('#kate_tindakan').val()!= ""){
												// $('#modal_tambah_barang').modal('show');
												$('.notif').removeClass('has-error has-feedback')
											} else {
												$('.notif').addClass('has-error has-feedback')
												$('.pesan').html('<small id="text" class="form-text text-muted text-danger">Lengkapi dulu semua data</small>')
											}
										})
									} else if($('#tiket_nama').val() == 'Reaktivasi layanan'){
										$('.div_tiket_teknisi').hide();
										$('#tiket_teknisi1').removeAttr('required');
										$('#tiket_teknisi2').removeAttr('required');
										$('#tiket_kendala').val($('#tiket_nama').val())
										$('#tiket_tindakan').val($('#tiket_nama').val())
										$('#tiket_noskb').attr('required', 'required');
										if($('#tiket_odp').val()){
											$('#tiket_pop').removeAttr('required');
											$('#tiket_olt').removeAttr('required');
											$('#tiket_odc').removeAttr('required');
											$('#tiket_odp').removeAttr('required');
										}else {
											$('#tiket_pop').attr('required', 'required');
											$('#tiket_olt').attr('required', 'required');
											$('#tiket_odc').attr('required', 'required');
											$('#tiket_odp').attr('required', 'required');
										}
										$('.button_modal_barang').click(function(){
											if($('#tiket_kendala').val()!= "" && $('#tiket_tindakan').val()!= ""&& $('#kate_tindakan').val()!= ""){
												// $('#modal_tambah_barang').modal('show');
												$('.notif').removeClass('has-error has-feedback')
											} else {
												$('.notif').addClass('has-error has-feedback')
												$('.pesan').html('<small id="text" class="form-text text-muted text-danger">Lengkapi dulu semua data</small>')
											}
										})
									} else {
										$('.div_tiket_teknisi').show();
										$('.div_tiket_topologi').show();
										$('#tiket_foto').attr('required', 'required');
										$('#tiket_teknisi1').attr('required', 'required');
										$('#tiket_teknisi2').attr('required', 'required');
										$('#tiket_noskb').removeAttr('required');
										
										if($('#tiket_odp').val()){
											$('#tiket_pop').removeAttr('required');
											$('#tiket_olt').removeAttr('required');
											$('#tiket_odc').removeAttr('required');
											$('#tiket_odp').removeAttr('required');
										}else {
											$('#tiket_pop').attr('required', 'required');
											$('#tiket_olt').attr('required', 'required');
											$('#tiket_odc').attr('required', 'required');
											$('#tiket_odp').attr('required', 'required');
										}

										$('.button_modal_barang').click(function(){
												if($('#tiket_teknisi1').val() != "" && $('#tiket_teknisi2').val() != "" && $('#tiket_kendala').val()!= "" && $('#tiket_tindakan').val()!= ""&& $('#kate_tindakan').val()!= ""){
													// $('#modal_tambah_barang').modal('show');
													$('.notif').removeClass('has-error has-feedback')
												} else {
													$('#modal_tambah_barang').modal('hide');
													$('.notif').addClass('has-error has-feedback')
													$('.pesan').html('<small id="text" class="form-text text-muted text-danger">Lengkapi dulu semua data</small>')
												}
										})
									}

									$('.submit_tiket').attr('disabled','disabled');
									$('select[name=kate_tindakan]').change(function () {
										if ($(this).val() == 'Ganti ONT') {
											$('#ganti_mac').val('');
											$('#ganti_sn').val('');
											$('#kode_barang_ont').val('');
											$('#kode_barang_adp').val('');
											$('.div_adp').hide()
											$('.div_ont').show()
											$('#ganti_mac').attr('required', 'required');
											$('#ganti_sn').attr('required', 'required');
											$('#kode_barang_ont').attr('required', 'required');
											$('#kode_barang_adp').removeAttr('required');
											$('#button_modal_barang').click(function(){
												if($('#ganti_mac').val() != "" && $('#ganti_sn').val() != "" && $('#kode_barang_ont').val()!= ""){
													$('#modal_tambah_barang').modal('show');
													$('.notif_ganti').removeClass('has-error has-feedback')
												} else {
													$('.notif_ganti').addClass('has-error has-feedback')
													$('.pesan').html('<small id="text" class="form-text text-muted text-danger">Lengkapi dulu semua data</small>')
												}

											})
										} else if($(this).val() == 'Ganti Adaptor') { 
											$('#modal_tambah_barang').modal('hide');
											$('.notif_ganti').removeClass('has-error has-feedback')
											$('#ganti_mac').val('');
											$('#ganti_sn').val('');
											$('#kode_barang_ont').val('');
											$('#kode_barang_adp').val('');
											$('.div_adp').show()
											$('.div_ont').hide()
											$('#ganti_mac').removeAttr('required');
											$('#ganti_sn').removeAttr('required');
											$('#kode_barang_ont').removeAttr('required');
											$('#kode_barang_adp').attr('required', 'required');
											$('#button_modal_barang').click(function(){
												if($('#kode_barang_adp').val() != ""){
													$('#modal_tambah_barang').modal('show');
													$('.notif_ganti').removeClass('has-error has-feedback')
												} else {
													$('.notif_ganti').addClass('has-error has-feedback')
													$('.pesan').html('<small id="text" class="form-text text-muted text-danger">Lengkapi dulu semua data</small>')
												}
											})
										} else if($(this).val() == 'Lainnya') { 
											$('.notif_ganti').removeClass('has-error has-feedback')
											$('#ganti_mac').val('');
											$('#ganti_sn').val('');
											$('#kode_barang_ont').val('');
											$('#kode_barang_adp').val('');
											$('.div_adp').hide()
											$('.div_ont').hide()
											$('#ganti_mac').removeAttr('required');
											$('#ganti_sn').removeAttr('required');
											$('#kode_barang_ont').removeAttr('required');
											$('#kode_barang_adp').removeAttr('required');
											$('.submit_tiket').removeAttr('disabled');
											$('#button_modal_barang').click(function(){
													$('#modal_tambah_barang').modal('show');
													$('.notif_ganti').removeClass('has-error has-feedback')
											})
										}
									});
									


									

								};
							});
						//-----------------------END CLOSED TIKET NEW -----------------------------

						//-----------------------START KIRIM WHATSAPP MANUAL-----------------------------
						$('.pesan_manual').click(function(){
							var id =$(this).data("id");
							var pesan =$(this).data("url");
							var url = '{{ route("admin.wa.kirim_pesan_manual", ":id") }}';
							url = url.replace(':id', id);

							$.ajax({
									url: url,
									type: 'PUT',
									data: {
										'_token': '{{ csrf_token() }}'},
									dataType: 'json',
									success: function(data) {
										if(data == 'berhasil'){
											window.location=open(pesan, '_blank');
											location.reload();
										}
									}
								})
						});
						//-----------------------END KIRIM WHATSAPP MANUAL-----------------------------

				//START BUAT PESANAN VOUCHER
					$('#vhc_site').on('change', function() {
					$('#vhc_mitra').empty();
                    $('#vhc_outlet').empty();
                    
							var idsite = $(this).val();
							var url = '{{ route("admin.glb.getMitraSite", ":id") }}';
							url = url.replace(':id', idsite);
							// console.log(idsite) 
							if (idsite) {
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
											$("#vhc_mitra").append('<option>---Pilih Mitra---</option>');
											// $("#desa").append('<option>---Pilih Desa---</option>');
											$.each(data,function(key,dd){
												$("#vhc_mitra").append('<option value="'+dd.id+'">'+dd.name+'</option>');
											});
										} else {
											$('#vhc_mitra').empty();
											$('#vhc_outlet').empty();
											
										}
									}
								});
							} else {
								$('#vhc_mitra').empty();
								$('#vhc_outlet').empty();
								
							}
					});
					$('#vhc_mitra').on('change', function() {
                    $('#vhc_outlet').empty();
							var id_mitra = $(this).val();
							var url = '{{ route("admin.glb.getOutlet", ":id") }}';
							url = url.replace(':id', id_mitra);
							// console.log(id_mitra) 
							if (id_mitra) {
								$.ajax({
									url: url,
									type: 'GET',
									data: {
										'_token': '{{ csrf_token() }}'
									},
									dataType: 'json',
									success: function(data) {
										if (data) {
											$("#vhc_outlet").append('<option>---Pilih Outlet---</option>');
											$.each(data,function(key,dd){
												$("#vhc_outlet").append('<option value="'+dd.outlet_id+'">'+dd.outlet_nama+'</option>');
											});
										} else {
											$('#vhc_outlet').empty();
											
										}
									}
								});
							} else {
								$('#vhc_outlet').empty();
								
							}
					});

					$('.button_add_voucher').click(function(){
								var i = 1;
								var jumlah_voucher = $('#jumlah_voucher').val();
								var paket_id = $('#paket_id').val();
								var url = '{{ route("admin.glb.getPaket", ":id") }}';
								url = url.replace(':id', paket_id);
								$.ajax({
									url: url,
									type: 'GET',
									data: {
										'_token': '{{ csrf_token() }}'
									},
									dataType: 'json',
									success: function(data) {
										if(data){
											$('.pesan_error').html('')
											i = i + 1
											var html ='<tr id="'+data.paket_id+'" '+data.paket_id+' >';
											html += '<td contenteditable="true" class="paket_id">'+data.paket_id+'</td>';
											html += '<td contenteditable="true" class="paket_nama">'+data.paket_nama+'</td>';
											html += '<td contenteditable="true" class="jumlah_voucher">'+jumlah_voucher+'</td>';
											html += '<td contenteditable="true" class="paket_harga">'+data.paket_harga+'</td>';
											html += '<td contenteditable="true" class="total_hpp">'+data.paket_harga * jumlah_voucher+'</td>';
											html += '<td contenteditable="true" class="paket_komisi">'+data.paket_komisi+'</td>';
											html += '<td contenteditable="true" class="total_komisi">'+data.paket_komisi * jumlah_voucher+'</td>';
											html += '<td><button type="button" class="btn btn-sm btn-danger" data-row="'+data.paket_id+'" '+data.paket_id+' id="hapus_voucher"> Hapus Voucher</button></td>';
											html += '</tr>';
											
											$('#tv').append(html)
											$('.simpan').removeAttr('disabled');
										} else {
											$('.pesan_error').html('<small class="form-text text-muted text-danger">Kode barang tidak ditemukan</small>')
										}
									}
								});
								
								
								
							});  
							$(document).on('click','#hapus_voucher', function(){
								   let hapus_voucher = $(this).data('row')
								   $('#' + hapus_voucher).remove()
								});

								$(document).on('click','.simpan_pesanan_voucher', function(){
									
									
									let pesanan_site =$('#vhc_site').val()
									let pesanan_mitra =$('#vhc_mitra').val()
									let pesanan_outlet =$('#vhc_outlet').val()
									let pesanan_router =$('#vhc_router').val()
									let pesanan_adminid =$('#vhc_admin_id').val()
							
										let paket_id = []
										let jumlah_voucher = []
										let paket_harga = []
										let total_hpp = []
										let paket_komisi = []
										let total_komisi = []

										$('.paket_id').each(function(){
											paket_id.push($(this).text())
										})
										$('.jumlah_voucher').each(function(){
											jumlah_voucher.push($(this).text())
										})
										$('.paket_harga').each(function(){
											paket_harga.push($(this).text())
										})
										$('.total_hpp').each(function(){
											total_hpp.push($(this).text())
										})
										$('.paket_komisi').each(function(){
											paket_komisi.push($(this).text())
										})
										$('.total_komisi').each(function(){
											total_komisi.push($(this).text())
										})
										
										
										var url = '{{ route("admin.vhc.store_pesanan") }}';
									$.ajax({
									url: url,
									type: 'POST',
									data: {
										pesanan_site:pesanan_site,
										pesanan_mitra:pesanan_mitra,
										pesanan_outlet:pesanan_outlet,
										pesanan_router:pesanan_router,
										pesanan_adminid:pesanan_adminid,
										pesanan_paketid:paket_id,
										pesanan_jumlah:jumlah_voucher,
										pesanan_harga:paket_harga,
										pesanan_total_hpp:total_hpp,
										pesanan_komisi:paket_komisi,
										pesanan_total_komisi:total_komisi,
										'_token': '{{ csrf_token() }}'},
									dataType: 'json',
									success: function(data) {
										if(data == 'failed'){
												swal("Gagal!", "Nomor Pesanan Duplikat.", {
													icon : "error",
													buttons: {        			
														confirm: {
															className : 'btn btn-error'
														}
													},
												});
											} else {
												window.location.href = "{{route('admin.vhc.data_pesanan')}}";
											}
										}
									});
								});

						// START DETAIL PESANAN
						$('.href_pesanan').click(function(){
							var id =$(this).data("id");
							var url = '{{ route("admin.vhc.rincian_pesanan", ":id") }}';
							url = url.replace(':id', id);
							window.location=url;
						});
							

				//END BUAT PESANAN VOUCHER
	
							//--------------------START TABLE CLICK LIHAT VOUCHER TERJUAL----------------------
							$('.href_voucher_terjual').click(function(){
							var id =$(this).data("id");
							// var timeStart = new Date($('#vhc_exp').val());
             				// var timeEnd = new Date();
							// var hourDiff = timeEnd - timeStart;    
							// var ms = hourDiff % 1000;
							// var ss = Math.floor(hourDiff / 1000) % 60;
							// var mm = Math.floor(hourDiff / 1000 / 60) % 60;
							// var hh = Math.floor(hourDiff / 1000 / 60 / 60);
							
							// $("#sisa_waktu").val( hh+":"+mm+":"+ss ) 
							$('#detail_voucher').modal('show')
							var url = '{{ route("admin.vhc.detail_voucher_terjual", ":id") }}';
							url = url.replace(':id', id);
							// console.log(url);
							$.ajax({
									url: url,
									type: 'GET',
									data: {
										'_token': '{{ csrf_token() }}'
									},
									dataType: 'json',
									success: function(data) {
										console.log(data);
										$('#uptime').val(data['user'][0]['uptime'])
										if(data['active']){
											$('#address').val(data['active'][0]['address'])
											$('#status').html('<span class="badge badge-success">Sedang Digunakan</span>')
										} else {
											$('#status').html('<span class="badge badge-danger">Tidak Aktif</span>')
										}
										
									}
								});

							
							});
							//--------------------END TABLE CLICK LIHAT VOUCHER TERJUAL----------------------
					// -- -----------------------------FORMAT NO HP------------------------------- --
						window.addEventListener('load', () => {
					const phoneInput = document.querySelector('#phone');
					phoneInput.addEventListener('keydown', disallowNonNumericInput);
					phoneInput.addEventListener('keyup', formatToPhone);
					});

					const disallowNonNumericInput = (evt) => {
					if (evt.ctrlKey) { return; }
					if (evt.key.length > 1) { return; }
					if (/[0-9.]/.test(evt.key)) { return; }
					evt.preventDefault();
					}

					const formatToPhone = (evt) => {
					const digits = evt.target.value.replace(/\D/g,'').substring(0,11);
					// const areaCode = digits.substring(0,2);
					// const prefix = digits.substring(2,5);
					// const suffix = digits.substring(5,9);
					// const suffix2 = digits.substring(9,11);

					// if(digits.length > 5) {evt.target.value = `(${areaCode}) ${prefix} - ${suffix}- ${suffix2}`;}
					// else if(digits.length > 2) {evt.target.value = `(${areaCode}) ${prefix}`;}
					// else if(digits.length > 0) {evt.target.value = `(${areaCode}`;}
					};
						window.addEventListener('load', () => {
					const phoneInput1 = document.querySelector('#phone1');
					phoneInput1.addEventListener('keydown', disallowNonNumericInput1);
					phoneInput1.addEventListener('keyup', formatToPhone1);
					});

					const disallowNonNumericInput1 = (evt) => {
					if (evt.ctrlKey) { return; }
					if (evt.key.length > 1) { return; }
					if (/[0-9.]/.test(evt.key)) { return; }
					evt.preventDefault();
					}

					const formatToPhone1 = (evt) => {
					const digits = evt.target.value.replace(/\D/g,'').substring(0,11);
					};

	//  pilih registrasi
	$(function(){ 

		var table = $('#input_data').DataTable(); $('#input_data tbody').on( 'click', 'tr', function () 
		{  
			
			$('#reg_mitra').empty()
			$('#sub_mitra').empty()
			$('#fee_pic').empty()
			$('#fee_subpic').empty()
			// $('#fee_pic').val('');
			// $('#fee_subpic').val('');
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
					console.log(data)
					if (data) {

							$("#cari_data").modal('hide');
							
							$('#fee_pic').empty()
							$('#fee_subpic').empty()
							// $('#fee_pic').val('');
							// $('#fee_subpic').val('');
							// $('#reg_mitra').empty()
							// $('#reg_mitra').append('<option value="">--None--</option>')
							$('#sub_mitra').empty()
							$('#sub_mitra').append('<option value="">--None--</option>')
							$('#tampil_hp').val(data['tampil_data']['input_hp']);
							$('#tampil_hp2').val(data['tampil_data']['input_hp_2']);
							$('#tampil_alamat_pasang').val(data['tampil_data']['input_alamat_pasang']);
							$('#tampil_idpel').val(data['tampil_data']['id']);
							$('#tampil_nama').val(data['tampil_data']['input_nama']);
							$('#tampil_nolay').val(data['nolay']);
							$('#tampil_username').val(data['username']);
							
							$('#tampil_maps').val(data['tampil_data']['input_maps']);
							$('#tampil_tgl').val(data['tampil_data']['input_tgl']);
							
							$('#tampil_subsales').val(data['tampil_data']['input_subseles']);
							$('#tampil_keterangan').val(data['tampil_data']['input_keterangan']);
							$('#tampil_site').val(data['tampil_data']['data__site_id']);
							


							if(data['tampil_data']['input_sales']){
								$('#tampil_sales').empty()
								$('#tampil_fee_sales').empty()
								// $('#tampil_fee_sales').val('')
								$('#tampil_sales').append('<option value="'+data['tampil_data']['input_sales']+'">'+data['tampil_data']['user_sales']['name']+'</option>');
								$('#tampil_fee_sales').append('<option value="'+data['tampil_data']['input_mitra']['mts_komisi']+'">'+data['tampil_data']['input_mitra']['mts_komisi']+'</option>');
								// document.getElementById("tampil_fee_sales").value =data['tampil_data']['input_mitra']['mts_komisi'];
							} else {
								$('#tampil_fee_sales').empty()
								$('#tampil_sales').empty()
								$('#tampil_sales').append('<option value="">--None--</option>')
								// $('#tampil_fee_sales').val('')
							}
								$('#jenis_tagihan').empty()
								$('#jenis_tagihan').append('<option value="">--Pilih Jenis Tagihan--</option>')
								$('#jenis_tagihan').append('<option value="PRABAYAR">PRABAYAR</option>') 
								$('#jenis_tagihan').append('<option value="PASCABAYAR">PASCABAYAR</option>') 
								$('#jenis_tagihan').append('<option value="FREE">FREE</option>') 

							
								
							} else {
									$('#jenis_tagihan').empty()
									$('#jenis_tagihan').append('<option value="">--Pilih Jenis Tagihan--</option>')
									$('#jenis_tagihan').append('<option value="PRABAYAR">PRABAYAR</option>') 
									$('#jenis_tagihan').append('<option value="PASCABAYAR">PASCABAYAR</option>') 
									$('#jenis_tagihan').append('<option value="FREE">FREE</option>') 
									
								}
							}
			});
			});
		});
											
		
							
		
								$('#jenis_tagihan').on('change', function() {
									
									var jenis_tagihan = $(this).val();
									if(jenis_tagihan != 'FREE'){

										var url = '{{ route("admin.reg.getMitra") }}';
										$.ajax({
											url: url,
											type: 'GET',
											data: {
												'_token': '{{ csrf_token() }}'
											},
											dataType: 'json',
											success: function(data) {
												
												
												$('#reg_mitra').empty()
												$('#reg_mitra').append('<option value="null">--None--</option>')
												for (let i = 0; i < data.length; i++) {
													$('#reg_mitra').append('<option value="'+data[i].mts_user_id+'">'+data[i]['user_mitra'].name+'</option>');
												}
												
											}
										});
									} else {
										// $('#tampil_fee_sales').val('') 
										// $('#fee_subpic').val('') 
										// $('#fee_pic').val('') 
										$('#fee_pic').empty()
										$('#fee_subpic').empty()
										$('#tampil_sales').empty()
										$('#tampil_fee_sales').empty()
										$('#reg_mitra').empty()
										$('#reg_mitra').append('<option value="null">--None--/option>')
										$('#sub_mitra').empty()
										$('#sub_mitra').append('<option value="null">--None--/option>')
										$('#tampil_sales').append('<option value="null">--None--/option>')
									}
								});


									$('#reg_mitra').on('change', function() {
										$('#sub_mitra').empty()
										$('#sub_mitra').append('<option value="null">--None--</option>')
										$('#fee_pic').val()
										$('#fee_subpic').val()
										var idpel = $('#tampil_idpel').val()
										var getMitraSub = $(this).val();
									var url = '{{ route("admin.reg.getMitraSub", ":id") }}';
									url = url.replace(':id', getMitraSub);
										$.ajax({
											url: url,
											type: 'GET',
											data: {
												idpel:idpel,
												'_token': '{{ csrf_token() }}'
												
											},
											dataType: 'json',
											success: function(data) {
												// Harga fee pic jika ada sales pada pelanggan
												var harga_fee_pic = 5000; 


												for (let i = 0; i < data['mitrasub_data'].length; i++) {
													$('#sub_mitra').append('<option value="'+data['mitrasub_data'][i].mts_sub_user_id+'">'+data['mitrasub_data'][i]['user_submitra'].name+'</option>');
												}


												
												if(data['fee_sales']['input_sales']){
													// Fee Sales
													var fee_sales = data['fee_sales']['input_mitra']['mts_komisi'];
													if(data['mitra_subfee'] == ''){
														$('#fee_pic').empty();
														$('#tampil_fee_sales').empty();
														$('#tampil_fee_sales').append('<option value="'+fee_sales+'">'+fee_sales+'</option>')
														// $('#fee_pic').val();
														// $('#tampil_fee_sales').val(fee_sales);
														
													} else {
														// ##--Jika menambahkan Mitra pada pelanggan yang dibawa oleh sales
														$('#fee_pic').empty();
														$('#fee_pic').append('<option value="'+harga_fee_pic+'">'+harga_fee_pic+'</option>')
														// $('#fee_pic').val(harga_fee_pic);
														var total1 = parseInt(fee_sales) - parseInt(harga_fee_pic);
														if (isNaN(total1)) {
															total1 = '';
														}
														$('#tampil_fee_sales').empty();
														$('#tampil_fee_sales').append('<option value="'+total1+'">'+total1+'</option>')
														$('#sub_mitra').attr('disabled','disabled');
														// $('#tampil_fee_sales').val(total1);

													}
												} else {
														$('#sub_mitra').removeAttr('disabled');
														$('#fee_subpic').empty();
														$('#fee_pic').empty();
														$('#fee_pic').append('<option value="'+data['mitra_subfee'][0]['mts_komisi']+'">'+data['mitra_subfee'][0]['mts_komisi']+'</option>')
													// $('#fee_pic').val(data['mitra_subfee'][0]['mts_komisi']);
												}


												$('#sub_mitra').on('change', function() {
													var getMitraSub = $(this).val();
													var url = '{{ route("admin.reg.getMitraSubfee", ":id") }}';
													url = url.replace(':id', getMitraSub);
													$.ajax({
														url: url,
														type: 'GET',
														data: {
															'_token': '{{ csrf_token() }}'
														},
														dataType: 'json',
														success: function(data) {
															if(data){
																// $('#fee_subpic').val('');
																$('#fee_subpic').empty();
																$('#fee_subpic').append('<option value="'+data[0]['mts_komisi']+'">'+data[0]['mts_komisi']+'</option>')
																// $('#fee_subpic').val(data[0]['mts_komisi']);
															}else {
																$('#fee_subpic').empty();
																// $('#fee_subpic').val('');
															}
															}
														});
													});
												}
										});
									});
								// validasi_odp

$('#validasi_odp').keyup(function() {
var validasi_odp = $('#validasi_odp').val();
var url = '{{ route("admin.reg.aktivasi_validasi_odp", ":id") }}';
url = url.replace(':id', validasi_odp);
// console.log(validasi_odp)
$.ajax({
	url: url,
			type: 'GET',
			data: {'_token': '{{ csrf_token() }}'},
						dataType: 'json',
						success: function(data) {
							console.log(data.odc_id)
							if(data.odp_id){
								// $('#validasi_olt').val(data.olt_nama)
								$('#validasi_odc').val(data.odc_id)
								$('#validasi_olt').append('<option value="'+data.id_router+'">'+data.olt_nama+'</option>')
								$('#validasi_router').append('<option value="'+data.id_router+'">'+data.router_nama+'</option>')
								$('#validasi_pop').append('<option value="'+data.id_pop+'">'+data.pop_nama+'</option>')
								$('#validasi_site').append('<option value="'+data.id_site+'">'+data.site_nama+'</option>')

								$('.read').readOnly = true
								$('.notif').removeClass('has-error has-feedback')
								$('.notif').addClass('has-success has-feedback')
								$('.notif_valtiket').removeClass('has-error has-feedback')
								$('.notif_valtiket').addClass('has-success has-feedback')
								$('#pesan').html('')
							} else{
								$('#validasi_odc').val('')
								// $('#validasi_olt').val('')
								$('#validasi_olt').empty()
								$('#validasi_router').empty()
								$('#validasi_pop').empty()
								$('#validasi_site').empty()
								$('.notif').addClass('has-error has-feedback')
								$('.notif_valtiket').addClass('has-error has-feedback')
								$('#pesan').html('<small id="text" class="form-text text-muted text-danger">Kode ODP tidak ditemukan</small>')
								}
						},
						error: function(error) {
							// console.log(error)

						},
					});
});



					// --------------------------------------START ADD KATEGORI BARANG-------------------------------------
					$('select[name=kategori_satuan]').change(function () {
						if ($(this).val() == 'Roll') {
							$('.div_satuan').show();
						$('.kategori_qty').attr('required', 'required');;
					} else {
							$('.div_satuan').hide();
						}
					});
					// --------------------------------------END ADD KATEGORI BARANG-------------------------------------

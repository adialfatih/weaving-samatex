<script src="<?=base_url('assets/');?>vendors/scripts/core.js"></script>
		<script src="<?=base_url('assets/');?>vendors/scripts/script.min.js"></script>
		<script src="<?=base_url('assets/');?>vendors/scripts/process.js"></script>
		<script src="<?=base_url('assets/');?>vendors/scripts/layout-settings.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/apexcharts/apexcharts.min.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		<script src="<?=base_url('assets/');?>vendors/scripts/dashboard.js"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
		<!-- Google Tag Manager (noscript) -->
		<script>
			$('#inputnewkode').keyup(function(){
				var txt = $('#inputnewkode').val();
				if(txt==""){
					$('#newcode').hide(500);
				} else {
				$.ajax({
                    url:"<?=base_url();?>prosesajax/cekkoderollbaru",
                    type: "POST",
                    data: {"txt" : txt},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                $('#newcode').hide();
                            } else {
								$('#newcode').html('<code>Kode sudah digunakan</code>');
								$('#newcode').show();
                            }
                        }
                });
				}
			});
			$('#costumID').keyup(function(){
				var txt = $('#costumID').val();
				if(txt==""){
					$('#loader').hide(500);
					$('#cekijo').hide(400);
					$('#nid1').hide(300);
					$('#idcustom').val('0');
					$('#notelid').val('Masukan nomor telepon');
					$('#almtcusid').html('Alamat customer');
				} else {
				$('#loader').show();
				$.ajax({
                    url:"<?=base_url();?>prosesajax/cekCustomer",
                    type: "POST",
                    data: {"txt" : txt},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                $('#cekijo').show(400);
								$('#loader').hide();
								$('#idcustom').val(''+dataResult.id);
								$('#almtcusid').html(''+dataResult.alm);
								$('#notelid').val(''+dataResult.nohp);
								$('#nid1').hide(300);
                            } else {
                                $('#cekijo').hide(400);
								$('#loader').hide();
								$('#idcustom').val('0');
								$('#notelid').val('Masukan nomor telepon');
								$('#almtcusid').html('Alamat customer');
								$('#nid1').show(300);
                            }
                        }
                });
				}
			});
			$('#cekKodeRollid').click(function(){
				$('#gbrLoading').show();
				var txt = $('#kodeRollsearch').val();
				if(txt==""){
					$('#kodetidaktemukan').show();
					$('#dtText').html('<code>Anda tidak memasukan kode Roll</code>');
					$('#gbrLoading').hide();
					$('#kodeditemukan').hide(300);
					$('#splitbtn').hide(300);
				} else {
				$('#gbrLoading').show();
				$.ajax({
                    url:"<?=base_url();?>prosesajax/cekKodeRoll",
                    type: "POST",
                    data: {"txt" : txt},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
							if(dataResult.statusCode==200){
								$('#kodetidaktemukan').hide();
								$('#gbrLoading').hide();
								$('#kodeditemukan').show(300);
								$('#splitbtn').show(300);
								$('#tb2').html(''+dataResult.ig+' m');
								$('#tb3').html(''+dataResult.uif+'');
								$('#tb4').html(''+dataResult.ufol+'');
								$('#tb1').html(''+dataResult.kdkons+'');
								$('#tb0').html(''+dataResult.noroll+'');
                            } else {
								$('#kodetidaktemukan').show();
								$('#dtText').html('<code>Kode Roll tidak ditemukan</code>');
								$('#gbrLoading').hide();
								$('#kodeditemukan').hide(300);
								$('#splitbtn').hide(300);
								$('#splitTable').hide(300);
                            }
                        }
                });
				}
			});
			$('#splitbtn').click(function(){
				$('#splitTable').show(300);
				$('#saveSplitRoll').show(300);

			});
			function carisjthis(val, mtd){
				$('#bodyTable').html('<tr><td colspan="7">Loading...</td></tr>');
				if(mtd=="sj"){
					var metode = "nosj";
				} else {
					if(mtd=="tgl"){
						var metode = "tgl";
					} else {
						if(mtd=="bln"){
							var metode = "bln";
							//alert('tes '+val);
						} else {
							var metode = "nosj";
						}
					}
				}
				$.ajax({
                    url:"<?=base_url();?>reload/showbysj",
                    type: "POST",
                    data: {"metode" : metode, "sj" : val},
                        cache: false,
                        success: function(dataResult){
							$('#bodyTable').html(dataResult);
                        }
                });
			}
			$('#saveSplitRoll').click(function(){
				var kdRollAsli = $('#kodeRollsearch').val();
				var kdRollsplit = $('#split_kode').val();
				var prosname = $('#split_prosname').find(":selected").val();
				var tgl = $('#split_tgl').val();
				var ukuran = $('#split_ukuran').val();
				var opr = $('#split_operator').val();
				if(kdRollAsli!="" && kdRollsplit!="" && prosname!="" && tgl!="" && ukuran!="" && opr!=""){
					$.ajax({
                    url:"<?=base_url();?>prosesajax/simpanSplit",
                    type: "POST",
                    data: {"kd1" : kdRollAsli, "kd2" : kdRollsplit, "prosname" : prosname, "tgl" : tgl, "ukuran" : ukuran, "opr" : opr},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
							if(dataResult.statusCode==200){
								$('#peringatanSave').html('<font style="color:green;">Proses disimpan</font>');
								$('#split_kode').val('');
								$("#split_prosname").val('0').change();
								$('#split_tgl').val('');
								$('#split_ukuran').val('');
								$('#split_operator').val('');
								$('#saveSplitRoll').hide();
                            } else {
								$('#peringatanSave').html('<code>'+dataResult.psn+'</code>');
                            }
                        }
                	});
				} else {
					$('#peringatanSave').html('<code>Anda harus mengisi semua data dengan benar</code>');
				}
			});
			
            function peringatan(txt) {
                Toastify({
                    text: ""+txt+"",
                    duration: 4000,
                    close:true,
                    gravity:"bottom",
                    position: "right",
                    backgroundColor: "#cc214e",
                }).showToast();
            }
            function suksestoast(txt){
                Toastify({
                    text: ""+txt+"",
                    duration: 3000,
                    close:true,
                    gravity:"bottom",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
            }
		</script>
		<noscript
			><iframe
				src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
				height="0"
				width="0"
				style="display: none; visibility: hidden"
			></iframe
		></noscript>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
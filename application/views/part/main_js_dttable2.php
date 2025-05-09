<!-- js -->
<script src="<?=base_url('assets/');?>vendors/scripts/core.js"></script>
		<script src="<?=base_url('assets/');?>vendors/scripts/script.min.js"></script>
		<script src="<?=base_url('assets/');?>vendors/scripts/process.js"></script>
		<script src="<?=base_url('assets/');?>vendors/scripts/layout-settings.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		<!-- buttons for Export datatable -->
		<script src="<?=base_url('assets/');?>src/plugins/datatables/js/dataTables.buttons.min.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/datatables/js/buttons.print.min.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/datatables/js/buttons.html5.min.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/datatables/js/buttons.flash.min.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/datatables/js/pdfmake.min.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/datatables/js/vfs_fonts.js"></script>
        <script src="<?=base_url('assets/selectjs/js/');?>jquery.multi-select.js" type="text/javascript"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
		

		<!-- Datatable Setting js -->
		<script src="<?=base_url('assets/');?>vendors/scripts/datatable-setting.js"></script>
		<script>
			//multiselect yang pertama
			$('#pre-selected-options').multiSelect({
				selectionFooter: "<div class='custom-header' id='footss'>Jumlah penjualan : <strong>0</strong></div>"
			});
			
			$("#tombolUtama").click(function(){
				$('#show1').show();
				$('#idtotaljual').show();
				$('#tombolUtama').hide();
			});
			$("#btnshow1").click(function(){
				$('#show2').show();
				$('#btnshow1').hide();
			});
			$("#btnshow2").click(function(){
				$('#show3').show();
				$('#btnshow2').hide();
			});
			$("#btnshow3").click(function(){
				$('#show4').show();
				$('#btnshow3').hide();
			});
			$("#btnshow4").click(function(){
				$('#show5').show();
				$('#btnshow4').hide();
			});
			$("#btnshow5").click(function(){
				$('#show6').show();
				$('#btnshow5').hide();
			});
			$("#btnshow6").click(function(){
				$('#show7').show();
				$('#btnshow6').hide();
			});
			$("#btnshow7").click(function(){
				$('#show8').show();
				$('#btnshow7').hide();
			});
			$("#btnshow8").click(function(){
				$('#show9').show();
				$('#btnshow8').hide();
			});
			$("#btnshow9").click(function(){
				$('#show10').show();
				$('#btnshow9').hide();
			});
			$("#idpilihan").change(function(){
				var nilaiAwal = $('#idpilihan').val();
				if(nilaiAwal==''){ var nilaiAwal=0; }
				var nilaiAkhir = $('#idtambahan').val();
				if(nilaiAkhir==''){ var nilaiAkhir=0; }
				var allNilai = parseFloat($nilaiAwal)+parseFloat($nilaiAkhir);
				var rnAllNilai = roundToTwo(allNilai);
				$('#idpenjualan_akhir').html(''+rnAllNilai);
			});
			$("#idpilihan").keyup(function(){
				var nilaiAwal = $('#idpilihan').val();
				if(nilaiAwal==''){ var nilaiAwal=0; }
				var nilaiAkhir = $('#idtambahan').val();
				if(nilaiAkhir==''){ var nilaiAkhir=0; }
				var allNilai = parseFloat($nilaiAwal)+parseFloat($nilaiAkhir);
				var rnAllNilai = roundToTwo(allNilai);
				$('#idpenjualan_akhir').html(''+rnAllNilai);
			});
			for (let a = 1; a < 11; a++) {
				$("#ukuran"+a+"").keyup(function(){
					var nilaiThis1 = $('#ukuran1').val();
					if(nilaiThis1 == '') { var nilaiThis1 = 0; } var prsnt1 = parseFloat(nilaiThis1); var rnd1 = roundToTwo(prsnt1);
					var nilaiThis2 = $('#ukuran2').val();
					if(nilaiThis2 == '') { var nilaiThis2 = 0; } var prsnt2 = parseFloat(nilaiThis2); var rnd2 = roundToTwo(prsnt2);
					var nilaiThis3 = $('#ukuran3').val();
					if(nilaiThis3 == '') { var nilaiThis3 = 0; } var prsnt3 = parseFloat(nilaiThis3); var rnd3 = roundToTwo(prsnt3);
					var nilaiThis4 = $('#ukuran4').val();
					if(nilaiThis4 == '') { var nilaiThis4 = 0; } var prsnt4 = parseFloat(nilaiThis4); var rnd4 = roundToTwo(prsnt4);
					var nilaiThis5 = $('#ukuran5').val();
					if(nilaiThis5 == '') { var nilaiThis5 = 0; } var prsnt5 = parseFloat(nilaiThis5); var rnd5 = roundToTwo(prsnt5);
					var nilaiThis6 = $('#ukuran6').val();
					if(nilaiThis6 == '') { var nilaiThis6 = 0; } var prsnt6 = parseFloat(nilaiThis6); var rnd6 = roundToTwo(prsnt6);
					var nilaiThis7 = $('#ukuran7').val();
					if(nilaiThis7 == '') { var nilaiThis7 = 0; } var prsnt7 = parseFloat(nilaiThis7); var rnd7 = roundToTwo(prsnt7);
					var nilaiThis8 = $('#ukuran8').val();
					if(nilaiThis8 == '') { var nilaiThis8 = 0; } var prsnt8 = parseFloat(nilaiThis8); var rnd8 = roundToTwo(prsnt8);
					var nilaiThis9 = $('#ukuran9').val();
					if(nilaiThis9 == '') { var nilaiThis9 = 0; } var prsnt9 = parseFloat(nilaiThis9); var rnd9 = roundToTwo(prsnt9);
					var nilaiThis10 = $('#ukuran10').val();
					if(nilaiThis10 == '') { var nilaiThis10 = 0; } var prsnt10 = parseFloat(nilaiThis10); var rnd10 = roundToTwo(prsnt10);
					var nilaiPilihan = $('#idpilihan').val(); var pasnp = parseFloat(nilaiPilihan); var rndnp = roundToTwo(pasnp);
					var new_nilai = rnd1 + rnd2 + rnd3 + rnd4 + rnd5 + rnd6 + rnd7 + rnd8 + rnd9 + rnd10 + rndnp;
					var nlclear = roundToTwo(new_nilai);
					$('#idtambahan').val(''+nlclear);
					$('#idpenjualan_akhir').html(''+nlclear);
					
					
					//console.log('isi'+nilaiThis1);
				});
			}

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
		<!-- Google Tag Manager (noscript) -->
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
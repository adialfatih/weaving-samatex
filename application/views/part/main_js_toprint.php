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
		<!-- Google Tag Manager (noscript) -->
		<script src="<?=base_url('assets/dom-to-image-master/src/FileSaver.js');?>"></script>
		<script src="<?=base_url('assets/dom-to-image-master/src/dom-to-image.js');?>"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
		<script>
			domtoimage.toBlob(document.getElementById('noteidbody'))
			.then(function (blob) {
				window.saveAs(blob, '<?=$title;?>.png');
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
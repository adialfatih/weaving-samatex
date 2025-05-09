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
		<script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>

		<!-- Datatable Setting js -->
		<script src="<?=base_url('assets/');?>vendors/scripts/datatable-setting.js"></script>
		<script>
            const autoCompleteJS = new autoComplete({
                selector: "#autoComplete",
                placeHolder: "Ketik dan Pilih...",
                data: {
                    src: [<?=$dataauto;?>],
                    cache: true,
                },
                resultsList: {
                    element: (list, data) => {
                        if (!data.results.length) {
                            const message = document.createElement("div");
                            message.setAttribute("class", "no_result");
                            message.innerHTML = `<span>Found No Results for "${data.query}"</span>`;
                            list.prepend(message);
                        }
                    },
                    noResults: true,
                },
                resultItem: {
                    highlight: true
                },
                events: {
                    input: {
                        selection: (event) => {
                            const selection = event.detail.selection.value;
                            autoCompleteJS.input.value = selection;
                        }
                    }
                }
            });
			//multiselect yang pertama
			$('#pre-selected-options').multiSelect({
				selectionFooter: "<div class='custom-header' id='footss'>Jumlah penjualan : <strong>0</strong></div>"
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
            function ceksaldoawal(id,nm){
                //suksestoast(''+id);
                $('#idnmcus').html('<strong>'+nm+'</strong>');ididcus
                $('#ididcus').val(''+id);
                    $.ajax({
                        url:"<?=base_url();?>prosesajax/cekSaldoAwal",
                        type: "POST",
                        data: {"id" : id},
                            cache: false,
                            success: function(dataResult){
                                var dataResult = JSON.parse(dataResult);
                                if(dataResult.statusCode==200){
                                    $('#idsaldoawal').val(''+dataResult.total);
                                    $('#idnota').val(''+dataResult.idnota);
                                    $('#idnmcus').html('<strong>'+nm+'</strong>');
                                    //peringatan('tes1');
                                } else {
                                    if(dataResult.statusCode == 500){
                                        $('#idsaldoawal').val('Tidak memiliki saldo awal');
                                        $('#idnota').val('0');
                                        $('#idnmcus').html('<strong>'+nm+'</strong>');
                                        //peringatan('tes12');
                                    } else {
                                        peringatan('Erorr.. Hubungi Developer..');
                                        $('#idsaldoawal').val('null');
                                        $('#idnota').val('null');
                                        //peringatan('tes3');
                                    }
                                    
                                }
                            }
                    });
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
<!-- js -->
<script src="<?=base_url('assets/');?>vendors/scripts/core.js"></script>
		<script src="<?=base_url('assets/');?>vendors/scripts/script.min.js"></script>
		<script src="<?=base_url('assets/');?>vendors/scripts/process.js"></script>
		<script src="<?=base_url('assets/');?>vendors/scripts/layout-settings.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/highcharts-6.0.7/code/highcharts.js"></script>
		<script src="https://code.highcharts.com/highcharts-3d.js"></script>
		<script src="<?=base_url('assets/');?>src/plugins/highcharts-6.0.7/code/highcharts-more.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
		
        <script>
            // chart 4
            Highcharts.chart('chart4', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Report Stok Barang'
                },
                subtitle: {
                    text: 'PT. Rindang Jati Spinning'
                },
                xAxis: {
                    categories: [<?=$crt_kd;?>],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Stok'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:12px">Stok {point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} m</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'RJS',
                    data: [<?=$crt_rjs;?>]

                }, {
                    name: 'Pusatex',
                    data: [<?=$crt_pst;?>]

                }, {
                    name: 'Samatex',
                    data: [<?=$crt_smt;?>]

                }]
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
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
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        <?php if(!empty($daterange)) { ?>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script>
            $('input[name="datesr"]').daterangepicker();
        </script>
        <?php } ?>
	<?php if(!empty($autocomplet)) { ?>
		<script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
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
                        // Create "No Results" message element
                        const message = document.createElement("div");
                        // Add class to the created element
                        message.setAttribute("class", "no_result");
                        // Add message text content
                        message.innerHTML = `<span>Found No Results for "${data.query}"</span>`;
                        // Append message element to the results list
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
        
    </script>
	<?php } ?>
    <script>
        
        function klikNotaPilih(idnota,kurang,no){
            var cek = $('#cekpilih-'+no+'').val();
            var idDipilih = $('#idDipilih').val();
            var idallkurang = $('#idallkurang').val();
            var piutangDipilih = $('#jumlahUangdipilih').val();
            if(cek==0){
                $('#cekpilih-'+no+'').val('1');
                var nambahPiutang = parseInt(piutangDipilih) + parseInt(kurang);
            } else {
                $('#cekpilih-'+no+'').val('0');
                var nambahPiutang = parseInt(piutangDipilih) - parseInt(kurang);
            }
            $('#jumlahUangdipilih').val(''+nambahPiutang);
            $('#textTotalID').html('Total Rp. '+nambahPiutang.toLocaleString()+'');
            $('#jmlPembKonsum').val(''+nambahPiutang.toLocaleString()+'');
            if(idDipilih.includes(''+idnota)==false){
                document.getElementById('idDipilih').value = ''+idDipilih+''+idnota+',';
                
            } else {
                var newStr = idDipilih.replace(''+idnota+',','');
                document.getElementById('idDipilih').value = ''+newStr;
                
            }
            if(idallkurang.includes(''+kurang)==false){
                document.getElementById('idallkurang').value = ''+idallkurang+''+kurang+',';
                
            } else {
                var newStr = idallkurang.replace(''+kurang+',','');
                document.getElementById('idallkurang').value = ''+newStr;
                
            }
        }
        for (let index = 0; index < 55; index++) {
            var total = 0;
            $('#harga-'+index+'').keyup(function(event){
                //var n = parseInt(this.value.replace(/\D/g,''),10);
                //this.value = n.toLocaleString();
                var pjg = $('#pjg-'+index+'').val();
                var harga = parseFloat(this.value);
                var total = harga * pjg;
                //console.log(total.toLocaleString());
                $('#total-'+index+'').val('Rp. '+total.toLocaleString()); 
                $('#total2-'+index+'').val(''+total); 
            });  
                   
        }
        $('#howek').keyup(function(event){
                var n = parseInt(this.value.replace(/\D/g,''),10);
                this.value = n.toLocaleString(); 
        }); 
        $('#jmlPembKonsum').keyup(function(event){
                var n = parseInt(this.value.replace(/\D/g,''),10);
                this.value = n.toLocaleString(); 
        }); 
        $("#clickbtn1").click(function(){
            $('#shows1').show();
            $('#showsbtn2').show();
            $('#showsbtn1').hide();
        });
        $("#clickbtn2").click(function(){
            $('#shows2').show();
            $('#showsbtn3').show();
            $('#showsbtn2').hide();
        });
        $("#clickbtn3").click(function(){
            $('#shows3').show();
            $('#showsbtn4').show();
            $('#showsbtn3').hide();
        });
        $("#clickbtn4").click(function(){
            $('#shows4').show();
            $('#showsbtn5').show();
            $('#showsbtn4').hide();
        });
        $("#clickbtn5").click(function(){
            $('#shows5').show();
            $('#showsbtn5').hide();
        });
        $("#folid").change(function(){
            var st = $("#folid").val();
            //console.log(''+st);
            if(st=="FG"){
                $('#satuanid').html('Meter');
            } else if(st=="FF"){
                $('#satuanid').html('Yard');
            } else {
                $('#satuanid').html('');
            }
        });
        
            var ni = $('#forulangid').val();
            var tgls = $('#fortglid').val();
            for (let i = 0; i < ni; i++) {
                $('#customCheck'+i+'').click(function(){
                    var isi = $('#utm'+i+'').val();
                    if(isi==""){
                        $('#utm'+i+'').val(''+tgls);
                    } else {
                        $('#utm'+i+'').val('');
                    }
                });
            }
            for (let i = 0; i < ni; i++) {
                $('#customChecks'+i+'').click(function(){
                    var isi = $('#utms'+i+'').val();
                    if(isi==""){
                        $('#utms'+i+'').val(''+tgls);
                    } else {
                        $('#utms'+i+'').val('');
                    }
                });
            }
            for (let i = 0; i < ni; i++) {
                $('#customCheckss'+i+'').click(function(){
                    var isi = $('#utmss'+i+'').val();
                    if(isi==""){
                        $('#utmss'+i+'').val(''+tgls);
                    } else {
                        $('#utmss'+i+'').val('');
                    }
                });
            }
            for (let i = 0; i < ni; i++) {
                $('#customChecksss'+i+'').click(function(){
                    var isi = $('#utmsss'+i+'').val();
                    if(isi==""){
                        $('#utmsss'+i+'').val(''+tgls);
                    } else {
                        $('#utmsss'+i+'').val('');
                    }
                });
            }
            $("#cekkode").keydown(function (e) {
                if (e.keyCode == 13) {
                e.preventDefault();
                submitchat();
                }
            });

            function submitchat(){
                var kd = $('#cekkode').val();
                //console.log("SUBMITCHAT function "+kd);
                $.ajax({
                    url:"prosesajax/tesajax",
                    type: "POST",
                    data: {"kd" : kd},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                //console.log('proses sukses'+kd+'');
                                Toastify({
                                text: "Sukses kode "+kd+"",
                                className: "info",
                                style: {
                                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                                }
                                }).showToast();
                            } else {
                                //console.log('Kode '+kd+' tidak di temukan');
                                Toastify({
                                text: "Kode "+kd+" tidak ditemukan",
                                className: "info",
                                style: {
                                    background: "linear-gradient(to right, #eda8a8, #cc0c0c)",
                                }
                                }).showToast();
                            }
                        }
                })
            }
            $( '#cekall' ).click( function () {
                $( 'input[type="checkbox"]' ).prop('checked', this.checked);
                var ck = $('#ceksemuaid').val();
                var nilai = $('#aslitotal').val();
                if(ck==0){
                    $('#ceksemuaid').val('1');
                    $('.pilnilai').val('1');
                    document.getElementById('ttlpnj').value = ''+nilai;
                    document.getElementById('ukuranpid').innerHTML = 'Total panjang yang dipilih <strong>'+nilai+'</strong>';
                } else {
                    $('#ceksemuaid').val('0');
                    $('.pilnilai').val('0');
                    document.getElementById('ttlpnj').value = '0';
                    document.getElementById('ukuranpid').innerHTML = 'Total panjang yang dipilih <strong>0</strong>';
                }
            });
            function uncek23(kode,token){
                $.ajax({
                    url:"<?=base_url();?>prosesajax/uncekpkg",
                    type: "POST",
                    data: {"kode" : kode, "token" : token},
                        cache: false,
                        success: function(dataResult){
                            console.log('berhasil');
                        }
                });
            }
            function uncek33(kode,token){
                $.ajax({
                    url:"<?=base_url();?>prosesajax/uncekpkg2",
                    type: "POST",
                    data: {"kode" : kode, "token" : token},
                        cache: false,
                        success: function(dataResult){
                            console.log('berhasil');
                        }
                });
            }
            function uncek89(kode,token){
                $.ajax({
                    url:"<?=base_url();?>prosesajax/uncekpkg45",
                    type: "POST",
                    data: {"kode" : kode, "token" : token},
                        cache: false,
                        success: function(dataResult){
                            console.log('berhasil');
                        }
                });
            }
            $( '#idselpengiriman' ).change( function () {
                var val = $('#idselpengiriman').val();
                var dep = $('#locnowid').val();
                if(val=="cus"){
                    $('#frh1').show(300);
                    $('#frh2').show(300);
                    $('#frh3').show(300);
                    $.ajax({
                    url:"<?=base_url();?>prosesajax/ceksuratjalan",
                    type: "POST",
                    data: {"txt" : "cus", "dep" : dep},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                $('#nosjid').val(''+dataResult.psn);
                            } else {
                                $('#nosjid').val('');
                            }
                        }
                    });
                } else {
                    $('#frh1').hide(300);
                    $('#frh2').hide(300);
                    $('#frh3').hide(300);
                    $.ajax({
                    url:"<?=base_url();?>prosesajax/ceksuratjalan",
                    type: "POST",
                    data: {"txt" : "pabrik", "dep" : dep},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                $('#nosjid').val(''+dataResult.psn);
                            } else {
                                $('#nosjid').val('');
                            }
                        }
                    });
                }
            });
            $( '#namaCustomer' ).keyup( function () {
                $('#loader').show();
                var nama = $('#namaCustomer').val();
                if(nama==""){
                    $('#loader').hide();
                } else {
                    $.ajax({
                    url:"<?=base_url();?>prosesajax/cekCustomer",
                    type: "POST",
                    data: {"txt" : nama},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                $('#cekijo').show();
                                $('#cekabang').hide();
								$('#loader').hide();
								$('#idcus').val(''+dataResult.id);
								$('#almtkid').val(''+dataResult.alm);
								$('#notelpkid').val(''+dataResult.nohp);
                            } else {
                                $('#cekijo').hide();
                                $('#cekabang').show();
								$('#loader').hide();
								$('#idcus').val('0');
								$('#notelpkid').val('Masukan nomor telepon');
								$('#almtkid').val('Alamat customer');
                            }
                        }
                    });
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
            function isiBox2(kode,ukuran,satuan,id,kons,pkg,jns,satuan){
                var isis = document.getElementById('txt'+id+'').value;
                var proses = "";
                if(isis==0){
                    document.getElementById('txt'+id+'').value= ''+ukuran+'';
                    var ttl = document.getElementById('ttlpnj').value;
                    var newttl = parseFloat(ttl) + parseFloat(ukuran);
                    document.getElementById('ttlpnj').value = ''+newttl;
                    var proses = "1";
                } else {
                    document.getElementById('txt'+id+'').value= '0';
                    var ttl = document.getElementById('ttlpnj').value;
                    var newttl = parseFloat(ttl) - parseFloat(ukuran);
                    document.getElementById('ttlpnj').value = ''+newttl;
                    var proses = "2";
                }
                var show = roundToTwo(newttl);
                document.getElementById('ukuranpid').innerHTML = 'Total panjang yang dipilih : <strong>'+show+'</strong>';
                    $.ajax({
                    url:"<?=base_url();?>prosesajax/masukanKodeKePkg",
                    type: "POST",
                    data: {"proses" : proses, "kode" : kode, "ukuran" : ukuran, "kons" : kons, "pkg" : pkg, "jns" : jns, "satuan" : satuan},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                suksestoast('Kode '+kode+' ditambahkan ke paket');
                            } else {
                                peringatan('Kode '+kode+' dihapus dari paket')
                            }
                        }
                    });
            }
            function isiBox(ukuran,satuan,id){
                var isis = document.getElementById('txt'+id+'').value;
                if(isis==0){
                    document.getElementById('txt'+id+'').value= ''+ukuran+'';
                    var ttl = document.getElementById('ttlpnj').value;
                    var newttl = parseFloat(ttl) + parseFloat(ukuran);
                    document.getElementById('ttlpnj').value = ''+newttl;
                } else {
                    document.getElementById('txt'+id+'').value= '0';
                    var ttl = document.getElementById('ttlpnj').value;
                    var newttl = parseFloat(ttl) - parseFloat(ukuran);
                    document.getElementById('ttlpnj').value = ''+newttl;
                }
                var show = roundToTwo(newttl);
                document.getElementById('ukuranpid').innerHTML = 'Total panjang yang dipilih : <strong>'+show+'</strong>';
            }
            function roundToTwo(num) {
                return +(Math.round(num + "e+2")  + "e-2");
            }
            function upsj(val){
                    $.ajax({
                        url:"<?=base_url();?>kirim/carinosj",
                        type: "POST",
                        data: {"val" : val},
                            cache: false,
                            success: function(dataResult){
                                var dataResult = JSON.parse(dataResult);
                                if(dataResult.statusCode==200){
                                    $('#nosjid').val(''+dataResult.psn);
                                } else {
                                    peringatan('Ada yang error');
                                }
                            }
                    });
            }
   
        
        var jns = $('#jnsid').val();
        //var kons = $('#autoComplete').val();
        //console.log('tgl :'+tgl);
        //console.log('kons :'+kons);
        function loadData(jns){
            $('#owekloading').css({"display": "flex", "width": "100%", "justify-content": "center"});
            $.ajax({
                url:"<?=base_url();?>alldashboard/loaddatastok",
                type: "POST",
                data: {"jns" : jns},
                cache: false,
                success: function(dataResult){
                    $('#kontenStok').html(dataResult);
                    $('#owekloading').hide();
                    //console.log('s'+tgl);
                }
            });
        }
        loadData(jns);
        $( "#jnsid" ).on( "change", function() { 
            var jns = $('#jnsid').val();
            loadData(jns);
        });
        function lihatSj(id){
            $('#dataModal').html('Loading...');
            $.ajax({
                url:"<?=base_url();?>beranda/ceksuratjalan",
                type: "POST",
                data: {"id" : id},
                cache: false,
                success: function(dataResult){
                    $('#dataModal').html(dataResult);
                }
            });
        }
    </script>
    <?php if(!empty($loti)) { ?>
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <?php } ?>
		<!-- Datatable Setting js -->
		<script src="<?=base_url('assets/');?>vendors/scripts/datatable-setting.js"></script>
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0" style="display: none; visibility: hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
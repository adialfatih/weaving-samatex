<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Manager</title>
    <link rel="stylesheet" href="<?=base_url('new_db/');?>style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Vithkuqi&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .autoComplete_wrapper input{
            width: 95%;
            transform: translateX(10px);
        }
        .dobel.fullrjs { width:100%; background:#035797; color: #fff;}
    </style>
</head>
<body>
    <?php
    $namaHari = array(
        'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
    );
    $tanggalHariini = date('d');
    $bulanHariini = date('m');
    $tahunHariini = date('Y');
    $dateNow = date('Y-m-d');
    $hariIni = date('w');
    $tanggalSebelumnya = date('Y-m-d', strtotime('-1 day'));
    $hariSebelumnya = date('w', strtotime('-1 day'));
    // echo $tanggalHariini."<br>";
    // echo $namaHari[$hariIni]."<br>";
    // echo $namaHari[$hariSebelumnya]."<br>";
    // echo $tanggalSebelumnya."<br>";
    $ar = array(
        '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    );
    $ex = explode('-', $tanggalSebelumnya);
    $printHarIni = $namaHari[$hariIni].", ".$tanggalHariini." ".$ar[$bulanHariini]." ".$tahunHariini;
    $printHariKemarin = $namaHari[$hariSebelumnya].", ".$ex[2]." ".$ar[$ex[1]]." ".$ex[0];
    //cek penjualan
    $cekPenjualan = $this->db->query("SELECT SUM(total_panjang) AS ukr FROM a_nota WHERE tgl_nota='$dateNow'")->row("ukr");
    if($cekPenjualan > 0){
        if(fmod($cekPenjualan, 1) !== 0.00){
            $jum_penjualan = number_format($cekPenjualan,2,',','.');
        } else {
            $jum_penjualan = number_format($cekPenjualan,0,',','.');
        }
        $jual_grey = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.kd, a_nota.konstruksi, a_nota.jml_roll, a_nota.total_panjang, SUM(a_nota.total_panjang) as ukr, a_nota.tgl_nota, new_tb_packinglist.kd, new_tb_packinglist.tanggal_dibuat, new_tb_packinglist.ttl_panjang, new_tb_packinglist.jns_fold FROM a_nota,new_tb_packinglist WHERE a_nota.kd = new_tb_packinglist.kd AND new_tb_packinglist.jns_fold='Grey' AND a_nota.tgl_nota = '$dateNow' ")->row("ukr");
        if(fmod($jual_grey, 1) !== 0.00){
            $jum_penjualan_grey = number_format($jual_grey,2,',','.');
        } else {
            $jum_penjualan_grey = number_format($jual_grey,0,',','.');
        }
        $jual_finish = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.kd, a_nota.konstruksi, a_nota.jml_roll, a_nota.total_panjang, SUM(a_nota.total_panjang) as ukr, a_nota.tgl_nota, new_tb_packinglist.kd, new_tb_packinglist.tanggal_dibuat, new_tb_packinglist.ttl_panjang, new_tb_packinglist.jns_fold FROM a_nota,new_tb_packinglist WHERE a_nota.kd = new_tb_packinglist.kd AND new_tb_packinglist.jns_fold='Finish' AND a_nota.tgl_nota = '$dateNow' ")->row("ukr");
        if(fmod($jual_finish, 1) !== 0.00){
            $jum_penjualan_finish = number_format($jual_finish,2,',','.');
        } else {
            $jum_penjualan_finish = number_format($jual_finish,0,',','.');
        }
    } else {
        $jum_penjualan = 0;
        $jum_penjualan_grey = 0;
        $jum_penjualan_finish = 0;
    }
    // end penjualan
    // show stok 
    $stokGrey = $this->db->query("SELECT SUM(prod_fg) AS ukr FROM data_stok WHERE dep='newSamatex'")->row("ukr");
    $stokFinish = $this->db->query("SELECT SUM(prod_ff) AS ukr FROM data_stok WHERE dep='newSamatex'")->row("ukr");
    $allStok = floatval($stokGrey) + floatval($stokFinish);
    if($allStok > 0){
        if(fmod($stokGrey, 1) !== 0.00){
            $showStokGrey = number_format($stokGrey,2,',','.');
        } else {
            $showStokGrey = number_format($stokGrey,0,',','.');
        }
        if(fmod($stokFinish, 1) !== 0.00){
            $showStokFinish = number_format($stokFinish,2,',','.');
        } else {
            $showStokFinish = number_format($stokFinish,0,',','.');
        }
        if(fmod($allStok, 1) !== 0.00){
            $showAllStok = number_format($allStok,2,',','.');
        } else {
            $showAllStok = number_format($allStok,0,',','.');
        }
    } else {
        $showStokGrey = 0;
        $showStokFinish = 0;
        $showAllStok = 0;
    }
    //end stok
    //start produksi harian
    $prod = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$dateNow, 'dep' => 'Samatex'])->row_array();
    $igrjs = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$dateNow, 'dep' => 'RJS'])->row("prod_ig");
    if(fmod($igrjs, 1) !== 0.00){
        $igrjs2 = number_format($igrjs,2,',','.');
    } else {
        $igrjs2 = number_format($igrjs,0,',','.');
    }
    if(fmod($prod['prod_ig'], 1) !== 0.00){
        $prod_ig = number_format($prod['prod_ig'],2,',','.');
    } else {
        $prod_ig = number_format($prod['prod_ig'],0,',','.');
    }
    if(fmod($prod['prod_if'], 1) !== 0.00){
        $prod_if = number_format($prod['prod_if'],2,',','.');
    } else {
        $prod_if = number_format($prod['prod_if'],0,',','.');
    }
    if(fmod($prod['prod_fg'], 1) !== 0.00){
        $prod_fg = number_format($prod['prod_fg'],2,',','.');
    } else {
        $prod_fg = number_format($prod['prod_fg'],0,',','.');
    }
    if(fmod($prod['prod_ff'], 1) !== 0.00){
        $prod_ff = number_format($prod['prod_ff'],2,',','.');
    } else {
        $prod_ff = number_format($prod['prod_ff'],0,',','.');
    }
    $prod_inspect = floatval($prod['prod_ig']) + floatval($prod['prod_if']);
    $prod_folding = floatval($prod['prod_fg']) + floatval($prod['prod_ff']);
    if(fmod($prod_inspect, 1) !== 0.00){
        $prod_inspect = number_format($prod_inspect,2,',','.');
    } else {
        $prod_inspect = number_format($prod_inspect,0,',','.');
    }
    if(fmod($prod_folding, 1) !== 0.00){
        $prod_folding = number_format($prod_folding,2,',','.');
    } else {
        $prod_folding = number_format($prod_folding,0,',','.');
    }
    $ajl = $this->db->query("SELECT SUM(hasil) as ukr FROM dt_produksi_mesin WHERE tanggal_produksi = '$tanggalSebelumnya'")->row("ukr");
    $ajl_smt = $this->db->query("SELECT SUM(hasil) as ukr FROM dt_produksi_mesin WHERE tanggal_produksi = '$tanggalSebelumnya' AND lokasi='Samatex'")->row("ukr");
    $ajl_rjs = $this->db->query("SELECT SUM(hasil) as ukr FROM dt_produksi_mesin WHERE tanggal_produksi = '$tanggalSebelumnya' AND lokasi='RJS'")->row("ukr");
    if(fmod($ajl, 1) !== 0.00){
        $ajl1 = number_format($ajl,2,',','.');
    } else {
        $ajl1 = number_format($ajl,0,',','.');
    }
    if(fmod($ajl_smt, 1) !== 0.00){
        $ajl2 = number_format($ajl_smt,2,',','.');
    } else {
        $ajl2 = number_format($ajl_smt,0,',','.');
    }
    if(fmod($ajl_rjs, 1) !== 0.00){
        $ajl3 = number_format($ajl_rjs,2,',','.');
    } else {
        $ajl3 = number_format($ajl_rjs,0,',','.');
    }
    ?>
    <div class="konten-mobile">
        <h1>Dashboard</h1>
        <div class="card-awal blue">
            <div class="items">Penjualan : </div>
            <div class="items-nilai">
                <a href="<?=base_url('penjualan-all');?>" class="nodec"><?=$jum_penjualan;?></a>
                <span><?=$printHarIni;?></span>
            </div>
            <div class="items-dobel">
                <div class="dobel green" id="hrefToPenjualanGrey">
                    Grey : <?=$jum_penjualan_grey;?>
                </div>                
                <div class="dobel red" id="hrefToPenjualanFinish">
                    Finish : <?=$jum_penjualan_finish;?>
                </div>
            </div>
        </div>
        <div class="card-awal blue">
            <div class="items">Stok : </div>
            <div class="items-nilai" id="hrefToStokAll">
                <?=$showAllStok;?>
            </div>
            <div class="items-dobel">
                <div class="dobel green" id="hrefToStokGrey">
                    Grey : <?=$showStokGrey;?>
                </div>
                <div class="dobel red" id="hrefToStokFinish">
                    Finish : <?=$showStokFinish;?>
                </div>
            </div>
        </div>
        <div class="card-awal blue">
            <div class="items">Produksi (AJL): </div>
            <div class="items-nilai">
                <?=$ajl1;?>
                <span><?=$printHariKemarin;?></span>
            </div>
            <div class="items-dobel">
                <div class="dobel grey" id="clickToAjlSamatex">
                    Samatex : <?=$ajl2;?>
                </div>
                <div class="dobel grey" id="clickToAjlRjs">
                    RJS : <?=$ajl3;?>
                </div>
            </div>
        </div>
        <div class="card-awal blue">
            <div class="items">Inspect : </div>
            <div class="items-nilai" id="clickToInspect">
                <?=$prod_inspect;?>
                <span><?=$printHarIni;?></span>
            </div>
            <div class="items-dobel">
                <div class="dobel green" id="clickToInspectGrey">
                    Grey : <?=$prod_ig;?>
                </div>
                <div class="dobel red" id="clickToInspectFinish">
                    Finish : <?=$prod_if;?>
                </div>
            </div>
            <div class="items-dobel">
                <div class="dobel fullrjs" id="clickToInspectRjs">
                    RJS Inspect Grey : <?=$igrjs2;?>
                </div>
                
            </div>
        </div>
        <div class="card-awal blue">
            <div class="items">Folding : </div>
            <div class="items-nilai" id="clickToFolding">
                <?=$prod_folding;?>
                <span><?=$printHarIni;?></span>
            </div>
            <div class="items-dobel">
                <div class="dobel green" id="clickToFoldingGrey">
                    Grey : <?=$prod_fg;?>
                </div>
                <div class="dobel red" id="clickToFoldingFinish">
                    Finish : <?=$prod_ff;?>
                </div>
            </div>
        </div>
        <div class="card-awal blue">
            <div class="items">Antrian Grey Finish : </div>
            <div class="items-nilai" id="agf_total">
                Loading...
            </div>
            <div class="items-dobel">
                <div class="dobel grey" id="agf_samatex">
                    Loading...
                </div>
                <div class="dobel grey" id="agf_rjs">
                    Loading...
                </div>
            </div>
        </div>
        <div class="card-awal blue">
            <div class="items">Download Laporan Bulanan ( Pdf ) : </div>
            <div class="items-nilai" style="display:flex;align-items:center;justify-content:center;margin-top:20px;">
                <img src="<?=base_url('report.png');?>" alt="Gambar" style="width:30px;margin-right:10px;">
                <input type="month" style="padding:7px;border-radius:3px;outline:none;border:none;width:50%;" placeholder="Pilih bulan" id="iptbulan">
            </div>
            <div class="items-dobel">
                <div class="dobel green" style="font-size:12px;align-items:center;" id="lap_jual">
                    Penjualan
                </div>
                <div class="dobel grey" style="font-size:12px;align-items:center;" id="lap_prod">
                    Produksi Samatex
                </div>
                <div class="dobel grey" style="font-size:12px;align-items:center;" id="lap_prod_rjs">
                    Produksi RJS
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        $( "#hrefToPenjualanGrey" ).on( "click", function() { 
            window.location.href = "<?=base_url('penjualan-grey');?>";
        });
        $( "#hrefToPenjualanFinish" ).on( "click", function() { 
            window.location.href = "<?=base_url('penjualan-finish');?>";
        });
        $( "#hrefToStokGrey" ).on( "click", function() { 
            window.location.href = "<?=base_url('stok-grey');?>";
        });
        $( "#hrefToStokFinish" ).on( "click", function() { 
            window.location.href = "<?=base_url('stok-finish');?>";
        });
        $( "#hrefToStokAll" ).on( "click", function() { 
            window.location.href = "<?=base_url('stok-all');?>";
        });
        $( "#clickToInspect" ).on( "click", function() { 
            window.location.href = "<?=base_url('inspect-all');?>";
        });
        $( "#clickToInspectGrey" ).on( "click", function() { 
            window.location.href = "<?=base_url('inspect-grey');?>";
        });
        $( "#clickToInspectFinish" ).on( "click", function() { 
            window.location.href = "<?=base_url('inspect-finish');?>";
        });
        $( "#clickToInspectRjs" ).on( "click", function() { 
            window.location.href = "<?=base_url('inspect-rjs');?>";
        });
        $( "#clickToFolding" ).on( "click", function() { 
            window.location.href = "<?=base_url('folding');?>";
        });
        $( "#clickToFoldingGrey" ).on( "click", function() { 
            window.location.href = "<?=base_url('folding-grey');?>";
        });
        $( "#clickToFoldingFinish" ).on( "click", function() { 
            window.location.href = "<?=base_url('folding-finish');?>";
        });
        $( "#clickToAjlSamatex" ).on( "click", function() { 
            window.location.href = "<?=base_url('ajl-samatex');?>";
        });
        $( "#clickToAjlRjs" ).on( "click", function() { 
            window.location.href = "<?=base_url('ajl-rindang');?>";
        });
        $( "#lap_jual" ).on( "click", function() { 
            var bulan = $('#iptbulan').val();
            console.log('jual '+ bulan);
            if(bulan == ""){
                peringatan('Silahkan pilih bulan');
            } else {
                window.location.href = "<?=base_url('cetakmng/penjualan/');?>"+bulan;
            }
        });
        $( "#lap_prod" ).on( "click", function() {
            var bulan = $('#iptbulan').val();
            console.log('produksi '+ bulan);
            if(bulan == ""){
                peringatan('Silahkan pilih bulan');
            } else {
                window.location.href = "<?=base_url('cetakmng/produksi/');?>"+bulan;
            }
            
        });
        $( "#lap_prod_rjs" ).on( "click", function() {
            var bulan = $('#iptbulan').val();
            console.log('produksi rjs'+ bulan);
            if(bulan == ""){
                peringatan('Silahkan pilih bulan');
            } else {
                window.location.href = "<?=base_url('cetakmng/produksirjs/');?>"+bulan;
            }
            
        });
        function peringatan(txt) {
            Toastify({
                    text: ""+txt+"",
                    duration: 4000,
                    close:true,
                    gravity:"top",
                    position: "right",
                    backgroundColor: "#cc214e",
            }).showToast();
        }
        function cek_agf(){
            $.ajax({
                    url:"<?=base_url();?>alldashboard/show_agf",
                    type: "POST",
                    data: {"tes" : "tes"},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                $('#agf_total').html(''+dataResult.psn_total+'');
                                $('#agf_samatex').html('Samatex : '+dataResult.psn_smt+'');
                                $('#agf_rjs').html('RJS : '+dataResult.psn_rjs+'');
                            } else {
                                $('#agf_total').html('Erorr');
                                $('#agf_samatex').html('Erorr');
                                $('#agf_rjs').html('Erorr');
                            }
                        }
            });
        }
        cek_agf();
        $( "#agf_total" ).on( "click", function() { 
            window.location.href = "<?=base_url('stok-on-pusatex');?>";
        });
        $( "#agf_samatex" ).on( "click", function() { 
            window.location.href = "<?=base_url('stok-on-pusatex');?>";
        });
        $( "#agf_samatex" ).on( "click", function() { 
            window.location.href = "<?=base_url('stok-on-pusatex');?>";
        });
    </script>
</body>
</html>
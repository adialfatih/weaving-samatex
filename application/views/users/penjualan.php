<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Penjualan</title>
    <link rel="stylesheet" href="<?=base_url('new_db/');?>style.css?versi=3">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .autoComplete_wrapper input{
            width: 95%;
            transform: translateX(10px);
        }
    </style>
</head>
<body>
    <?php
        $hariIni = new DateTime();
        $sf = $hariIni->format('l F Y, H:i');
        $ex = explode(' ', $sf);
        $nToday = $ex[0];
        //echo $nToday;
        $hariIndo = ["Sunday"=>"Minggu", "Monday"=>"Senin", "Tuesday"=>"Selasa", "Wednesday"=>"Rabu", "Thursday"=>"Kamis", "Friday"=>"Jumat", "Saturday"=>"Sabtu"];
        $newToday = $hariIndo[$nToday];
        //echo $newToday;
        $tgl = date('Y-m-d');
        $ex_tgl = explode('-', $tgl);
        //echo $tgl;
        $ar = array(
            '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        );
        $prinTgl = $ex_tgl[2]." ".$ar[$ex_tgl[1]]." ".$ex_tgl[0];
    ?>
    <h1>Packing Penjualan</h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong>, Username : <strong id="nmoptid">Nur Hikmah</strong></small>
    <div class="container">
        <div class="fortable has" style="font-size: 13px;">
            <table id="idtable">
                <tr><td>Loading...</td></tr>
            </table>
        </div>
        <div class="kotaknewpkg98">
            <span>Buat Paket Baru</span>
            <div class="newpkg" >
                <div class="autoComplete_wrapper">
                    <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off">
                    <input type="hidden" id="id_username" value="0">
                    <input type="hidden" id="id_tgl" value="<?=$tgl;?>">
                </div>
                <button id="createPkg">Buat Paket</button>
            </div>
            <input type="text" placeholder="Masukan Nama Customer" class="iptnewoke" id="idNamaCus">
        </div>
        <div class="kotaknewpkg">
            <span>Data Stok</span>
            <div class="fortable has2">
                <table id="tableStok">
                    <tr>
                        <td colspan="2">Loading...</td>
                    </tr>
                </table>
            </div>
        </div>
        <!--<div class="kotaknewpkg98">-->
        <!--    <span>Gabung Packinglist</span>-->
        <!--    <div class="newpkg" >-->
        <!--        <input type="text" placeholder="Masukan No PKG Terbesar" class="iptnewoke" id="pkgutama" name="pkgutama">-->
        <!--        <button id="gabungPkg">Gabung</button>-->
        <!--    </div>-->
        <!--    <p style="font-size:10px;width:100%;text-align:left;margin-top:10px;">Masukan No PKG yang akan di gabung ke PKG terbesar</p>-->
        <!--    <input type="text" placeholder="PKG123" class="iptnewoke" id="pkggabungan" name="pkggabungan">-->
        <!--</div>-->
        <button class="btn-save" style="background:#ccc;color:#000;border:1px solid #FFF;" id="btn-greys">Grey ke Finish</button>
        <button class="btn-save" style="background:red;border:1px solid #FFF;" id="btn-logout">Logout</button>
    </div>
    <?php
        $kons = $this->data_model->get_record('tb_konstruksi');
        $ar_kons = array();
        foreach($kons->result() as $val){
            //echo $val->kode_konstruksi."<br>";
            $ar_kons[] = '"'.$val->kode_konstruksi.'"';
            if($val->chto!="NULL"){
                $ar_kons[]= '"'.$val->chto.'"';
            }
        }
        $im_kons = implode(',', $ar_kons);
        //jika yusuf keluar finish
        //jiki rizik keluar grey
    ?>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $( "#btn-logout" ).on( "click", function() {
            window.location.href = "<?=base_url('users/');?>";
        });
        let personName = sessionStorage.getItem("userName");
        console.log(''+personName);
        if(personName == "edi" || personName == "riziq"){
            $('#btn-greys').show();
            $( "#btn-greys" ).on( "click", function() {
                window.location.href = "<?=base_url('users/greykefinish');?>";
            });
        } else {
            $('#btn-greys').hide();
        }
        document.getElementById('nmoptid').innerHTML = ''+personName;
        document.getElementById('id_username').value = ''+personName;
        function loadData(){
            $.ajax({
                url:"<?=base_url();?>users2/loadpenjualan",
                type: "POST",
                data: {"username" : personName},
                cache: false,
                success: function(dataResult){
                    $('#idtable').html(dataResult);
                }
            });
        }
        loadData();
        function loadDataStok(){
            $.ajax({
                url:"<?=base_url();?>users2/loadDataStok",
                type: "POST",
                data: {"username" : personName},
                cache: false,
                success: function(dataResult){
                    $('#tableStok').html(dataResult);
                }
            });
        }
        loadDataStok();
        const autoCompleteJS = new autoComplete({
            placeHolder: "Ketik Konstruksi...",
            data: {
                src: [<?=$im_kons;?>],
                cache: true,
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
        $( "#createPkg" ).on( "click", function() {
            suksestoast('Loading...');
            var kodekons = document.getElementById('autoComplete').value;
            var username = document.getElementById('id_username').value;
            var namacus = document.getElementById('idNamaCus').value;
            var tgl = document.getElementById('id_tgl').value;
            if(kodekons!="" && username!="null" && tgl!="" && namacus!=""){
                $.ajax({
                    url:"<?=base_url();?>users2/prosesCreatepkg",
                    type: "POST",
                    data: {"kodekons" : kodekons, "tgl":tgl, "username":username, "namacus":namacus},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                setTimeout(() => {
                                    window.location.href = "<?=base_url('users/createpenjualan/');?>"+dataResult.psn+"";
                                }, "1300");
                            } else {
                                peringatan('Konstruksi tidak ditemukan');
                                document.getElementById('autoComplete').value ='';
                            }
                        }
                });
            } else {
                peringatan('Anda perlu mengisi kode konstruksi');
            }                    
        });
        $( "#gabungPkg" ).on( "click", function() {
            suksestoast('Loading gabung...');
            var pkggabungan = document.getElementById('pkggabungan').value;
            var pkgutama = document.getElementById('pkgutama').value;
            if(pkggabungan!="" && pkgutama!="null" ){
                $.ajax({
                    url:"<?=base_url();?>users2/prosesGabungpkg",
                    type: "POST",
                    data: {"pkgutama" : pkgutama, "pkggabungan":pkggabungan},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                suksestoast(''+dataResult.psn+'');
                                setTimeout(() => {
                                    window.location.href = "<?=base_url('users/penjualan/');?>";
                                }, "1300");
                            } else {
                                peringatan(''+dataResult.psn+'');
                                document.getElementById('pkgutama').value ='';
                                document.getElementById('pkggabungan').value ='';
                            }
                        }
                });
            } else {
                peringatan('Anda perlu mengisi semua kode pkg');
            }                    
        });
            function peringatan(txt) {
                Toastify({
                    text: ""+txt+"",
                    duration: 4000,
                    close:true,
                    gravity:"top",
                    position: "center",
                    backgroundColor: "#cc214e",
                }).showToast();
            }
            function suksestoast(txt){
                Toastify({
                    text: ""+txt+"",
                    duration: 5000,
                    close:true,
                    gravity:"top",
                    position: "center",
                    backgroundColor: "#4fbe87",
                }).showToast();
            } 
            setTimeout(() => {
                loadDataStok();
            }, 60000);
    </script>
</body>
</html>
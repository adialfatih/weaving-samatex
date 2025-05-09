<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Ke Pusatex</title>
    <link rel="stylesheet" href="<?=base_url('new_db/');?>style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .autoComplete_wrapper {
            width:100%;
        }
        .autoComplete_wrapper input{
            width: 95%;
            transform: translateX(10px);
        }
        .iptcol2 {
            width: 100%;
            display:flex;
            justify-content:space-between;
        }
        .isiIptcol {
            width:49%;
            display:flex;
            flex-direction:column;
        }
        .isiIptcol input {
            border:1px solid #ccc;
            padding:10px;
            outline:none;
            border-radius:3px;
        }
        .testing {width:100%;display:flex;flex-direction:column;}
        .skeleton {
            background: linear-gradient(
                90deg,
                #eee 0%,
                #eee 40%,
                #ddd 50%,
                #ddd 55%,
                #eee 65%,
                #eee 100%
            );
            background-size:300%;
            animation: skeletons 1.5s infinite;
        }
        @keyframes skeletons {
            from {
                background-position:100%;
            }
            to {
                background-position:0%;
            }
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
    <h1>Terima dari Pusatex</h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong>, Username : <strong id="nmoptid">Nur Hikmah</strong></small>
    <br>
    
    <div class="container">
        
        <div class="kotaknewpkg">
            <span>Masukan Kode Roll</span>
                <div class="autoComplete_wrapper">
                    <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off">
                    <input type="hidden" id="id_username" value="0">
                    <input type="hidden" id="id_tgl" value="<?=$tgl;?>">
                </div>
                
        </div>
        <!--<div class="kotaknewpkg">-->
        <!--    <span>Stok Lama</span>-->
        <!--    <div class="testing">-->
        <!--    <div class="iptcol2">-->
        <!--        <div class="isiIptcol">-->
        <!--            <label for="newkode">Kode Roll</label>-->
        <!--            <input type="text" id="newkode" placeholder="Masukan Kode">-->
                    
        <!--        </div>-->
        <!--        <div class="isiIptcol">-->
        <!--            <label for="newukuran">Ukuran</label>-->
        <!--            <input type="tel" id="newukuran" placeholder="Masukan Ukuran">-->
        <!--        </div>-->
        <!--    </div>-->
        <!--    <div class="iptcol2" style="margin-top:5px;">-->
        <!--        <div class="isiIptcol">-->
        <!--            <label for="newmc">No Mesin</label>-->
        <!--            <input type="text" id="newmc" placeholder="Masukan MC">-->
        <!--        </div>-->
        <!--        <div class="isiIptcol">-->
        <!--            <label for="kons">Konstruksi</label>-->
        <!--            <input type="text" id="kons" placeholder="Masukan Konstruksi">-->
        <!--        </div>-->
        <!--    </div>-->
        <!--    <div class="iptcol2" style="margin-top:5px;">-->
        <!--        <div class="isiIptcol">-->
        <!--            &nbsp;-->
        <!--        </div>-->
        <!--        <div class="isiIptcol">-->
        <!--            <button style="background:blue;color:#FFFFFF;display:flex;justify-content:center;align-items:center;padding:10px 0;border-radius:7px;outline:none;border:none;transform:translateY(5px);" id="plusSimpan">+ Tambah</button>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--    </div>-->
        <!--</div>-->
        
        <div class="kotaknewpkg">
            <span>Roll diterima</span>
            <div class="fortable has2">
                <table id="tableStokKiriman">
                    <tr>
                        <td>Kode Roll</td>
                        <td>Ukuran</td>
                        <td>MC</td>
                        <td>Konstruksi</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="kotaknewpkg">
            <span>Rekap 7 Hari Terakhir</span>
            <div class="fortable has2 skeleton" id="tableStokKiriman89_load">
                <table id="tableStokKiriman89">
                    <tr>
                        <td>Loading...</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <button class="btn-save" style="background:red;border:1px solid #FFF;" id="btn-logout">Logout</button>
    </div>
    <?php
        $kons = $this->data_model->get_record('new_roll_onpst');
        $ar_kons = array();
        foreach($kons->result() as $val){
            $ar_kons[] = '"'.$val->kode_roll.'"';
        }
        $im_kons = implode(',', $ar_kons);
        //jika yusuf keluar finish
        //jiki rizik keluar grey
    ?>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let personName = sessionStorage.getItem("userName");
        document.getElementById('nmoptid').innerHTML = ''+personName;
        document.getElementById('id_username').value = ''+personName;
        function delpkg(kd){
            $.ajax({
                url:"<?=base_url();?>users/hapusKirimanPusatex",
                type: "POST",
                data: {"kd" : kd},
                cache: false,
                success: function(dataResult){
                    loadDataStokKiriman();
                    loadDataStokKiriman89();
                }
            });
        }
        function loadDataStokKiriman(){
            var tgl = document.getElementById('id_tgl').value;
            $.ajax({
                url:"<?=base_url();?>users/loadKirimanPusatex",
                type: "POST",
                data: {"username" : personName, "tgl" : tgl},
                cache: false,
                success: function(dataResult){
                    $('#tableStokKiriman').html(dataResult);
                }
            });
        }
        loadDataStokKiriman();
        const autoCompleteJS = new autoComplete({
            placeHolder: "Ketik dan Pilih...",
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
                        var tgl = document.getElementById('id_tgl').value;
                        $.ajax({
                            url:"<?=base_url();?>users/getCodeFromPst",
                            type: "POST",
                            data: {"kode" : selection, "tgl" : tgl, "username" : personName},
                            cache: false,
                            success: function(dataResult){
                                suksestoast("Kode "+selection+" diterima Samatex");
                                loadDataStokKiriman();
                                loadDataStokKiriman89();
                                autoCompleteJS.input.value = '';
                            }
                        });
                    }
                }
            }
        });
        $( "#btn-logout" ).on( "click", function() {
            window.location.href = "<?=base_url('users/');?>";
        });
        $( "#autoComplete" ).on( "change", function() {
            var tgl = document.getElementById('id_tgl').value;
            var selection = document.getElementById('autoComplete').value;
            $.ajax({
                url:"<?=base_url();?>users/getCodeFromPst",
                type: "POST",
                data: {"kode" : selection, "tgl" : tgl, "username" : personName},
                cache: false,
                success: function(dataResult){
                        suksestoast("Kode "+selection+" diterima Samatex");
                        loadDataStokKiriman();
                        loadDataStokKiriman89();
                        document.getElementById('autoComplete').value = '';
                }
            });
        });
        
        $( "#plusSimpan" ).on( "click", function() {
            var kode = $('#newkode').val();
            var ukr = $('#newukuran').val();
            var mc = $('#newmc').val();
            var kons = $('#kons').val();
            var tgl = document.getElementById('id_tgl').value;
            if(kode!="" && ukr!="" && mc!="" && kons!=""){
                $.ajax({
                    url:"<?=base_url();?>users/getCodeFromPst2",
                    type: "POST",
                    data: {"kode" : kode, "ukr" : ukr, "mc" : mc, "kons" : kons, "tgl" : tgl, "username" : personName},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            suksestoast("Kode "+kode+" diterima Samatex");
                            loadDataStokKiriman('');
                            $('#newkode').val('');
                            $('#newukuran').val('');
                            $('#newmc').val('');
                            $('#kons').val('');
                        } else {
                            peringatan(''+dataResult.psn);
                        }
                    }
                });
            } else {
                peringatan('Anda harus mengisi semua data.!!');
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
            function suksestoast(txt){
                Toastify({
                    text: ""+txt+"",
                    duration: 5000,
                    close:true,
                    gravity:"top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
            } 
            function loadDataStokKiriman89(){
                $('#tableStokKiriman89_load').addClass('skeleton');
                //var tgl = document.getElementById('id_tgl').value;
                $.ajax({
                    url:"<?=base_url();?>users/loadKirimanPusatex7hari",
                    type: "POST",
                    data: {"username" : personName},
                    cache: false,
                    success: function(dataResult){
                        $('#tableStokKiriman89').html(dataResult);
                        $('#tableStokKiriman89_load').removeClass('skeleton');
                    }
                });
            }
            loadDataStokKiriman89();
            
    </script>
</body>
</html>
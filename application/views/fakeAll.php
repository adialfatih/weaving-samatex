<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packinglist Ke Pusatex</title>
    <link rel="stylesheet" href="<?=base_url('new_db/');?>style.css">
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
        $kd = $this->uri->segment(3);
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
        if(!empty($kd)){
            //echo "$kd";
            $cek_kode1 = $this->data_model->get_byid('fake_pkglist', ['id_fakepkg'=>$kd]);
            if($cek_kode1->num_rows() == 1){
                $id_koide = $kd;
                $name_pkglist = $cek_kode1->row("name_pkglist");
                $keterangan = $cek_kode1->row("keterangan");
                $yg_buat = $cek_kode1->row("yg_buat");
                $konstruksi = $cek_kode1->row("konstruksi");
            } else {
                redirect(base_url('fake/fakepkglist'));
            }
        } else {
            $koide = $this->data_model->acakKode(9);
            $this->data_model->saved('fake_pkglist', [
                'name_pkglist' => $koide,
                'keterangan' => 'null',
                'yg_buat' => 'null',
                'konstruksi' => 'nulll'
            ]);
            $id_koide = $this->data_model->get_byid('fake_pkglist', ['name_pkglist'=>$koide])->row("id_fakepkg");
            $name_pkglist = "null";
            $keterangan = "null";
            $yg_buat = "null";
            $konstruksi = "null";
        }
    ?>
    <h1>Kode Paket <?=$id_koide;?></h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong>, Username : <strong id="nmoptid">Nur Hikmah</strong></small><br><small class="sm">Konstruksi : <strong><?=$kons;?></strong></small>
    <input type="hidden" value="<?=$ygbuat;?>" id="depPkg">
    <div class="container">
        <div class="form-label">
            <small>Ketik dan pilih kode roll untuk menambahkan ke dalam paket</small>
        </div>
        <div class="form-label">
            <label for="namepkg">Nama Pkglist</label>
            <?php if($name_pkglist=="null") { ?>
            <input type="text" id="namepkg" class="ipt" placeholder="Masukan nama packinglist" onchange="simpangperubahan()">
            <?php } else { ?>
            <input type="text" id="namepkg" class="ipt" value="<?=$name_pkglist;?>" onchange="simpangperubahan()">
            <?php } ?>
        </div>
        <div class="form-label">
            <label for="ketpkg">Ket Pkglist</label>
            <?php if($keterangan=="null") { ?>
            <textarea id="ketpkg" class="ipt" placeholder="Masukan Keterangan packinglist" onchange="simpangperubahan()"></textarea>
            <?php } else { ?>
            <textarea id="ketpkg" class="ipt" onchange="simpangperubahan()"><?=$keterangan;?></textarea>
            <?php } ?>
        </div>
        <div class="form-label">
            <label for="pkgkons">Konstruksi</label>
            <?php if($konstruksi=="null") { ?>
            <input type="text" id="pkgkons" class="ipt" placeholder="HURUF BESAR SEMUA" onchange="simpangperubahan()">
            <?php } else { ?>
            <input type="text" id="pkgkons" class="ipt" value="<?=$konstruksi;?>" onchange="simpangperubahan()">
            <?php } ?>
        </div>
        <div class="form-label">
            <label for="ygbuat">Yang Buat</label>
            <?php if($yg_buat=="null") { ?>
            <input type="text" id="ygbuat" class="ipt" placeholder="Masukan nama anda" onchange="simpangperubahan()">
            <?php } else { ?>
            <input type="text" id="ygbuat" class="ipt" value="<?=$yg_buat;?>" onchange="simpangperubahan()">
            <?php } ?>
        </div>
        <div class="form-label">
            <label for="autoComplete">Kode Roll</label>
            <div class="autoComplete_wrapper">
                <input id="autoComplete" type="search"  dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" onchange="simpangperubahan2()">
            </div>
            <!-- <input type="text" class="ipt" name="kons" id="kons" autofocus> -->
            <input type="hidden" id="id_username" value="0">
            <input type="hidden" id="id_tgl" value="">
            <input type="hidden" id="id_kons" value="">
            <input type="hidden" id="id_pkg" value="<?=$id_koide;?>">
        </div>
        <div class="fortable2">
        <table style="font-size:13px;" id="tablePaket">
            <tr>
                <td><strong>No</strong></td>
                <td><strong>Kode Roll</strong></td>
                <td><strong>Ukuran</strong></td>
                <td><strong>#</strong></td>
            </tr>
            <tr>
                <td colspan="4">Loading...</td>
            </tr>
        </table>
        </div>
        <div style="display:flex;" id="btnBox1">
        <a style="background:red;color:#FFFFFF;width:105px;display:flex;justify-content:center;align-items:center;margin-top:15px;font-size:12px;padding:5px 0;border-radius:7px;" id="delPaket_oke">Hapus Paket</a>
        <a style="background:green;color:#FFFFFF;width:105px;display:flex;justify-content:center;align-items:center;margin-top:15px;font-size:12px;padding:5px 0;border-radius:7px;margin-left:5px;" href="<?=base_url('fake/fakepkglistall');?>">Simpan</a>
        </div>
        <br>
        <!-- <button class="btn-simpan" style="padding:5px 0px;" id="addrollLama">Tambahkan Stok Lama</button> -->
    </div>
    
    <?php
        $kdrol = $this->db->query("SELECT kode_roll FROM data_ig WHERE loc_now!='RJS' AND kode_roll NOT IN (SELECT kode_roll FROM data_if) AND kode_roll NOT IN (SELECT kode_roll FROM data_fol) AND kode_roll NOT IN (SELECT kode_roll FROM fake_isi)");
        //$kdrol = $this->data_model->get_byid('data_ig', ['konstruksi'=>$kons, 'loc_now'=>'Samatex']);
        $ar_kdrol = array();
        foreach($kdrol->result() as $val){
            //echo $val->kode_konstruksi."<br>";
            $ar_kdrol[] = '"'.$val->kode_roll.'"';
        }
        $im_kons = implode(',', $ar_kdrol);
    ?>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $( "#addrollLama" ).on( "click", function() {
            window.location.href = "<?=base_url('users/createkirimpst2/'.$kd);?>";
        } );
        function simpangperubahan(){
            var nmpkg = $('#namepkg').val();
            var ketpkg = $('#ketpkg').val();
            var pkgkons = $('#pkgkons').val();
            var ygbuat = $('#ygbuat').val();
            var id_pkg = $('#id_pkg').val();
            if(nmpkg!="" && ketpkg!="" && pkgkons!="" && ygbuat!="" && id_pkg!=""){
                $.ajax({
                    url:"<?=base_url();?>fake/updatepkg",
                    type: "POST",
                    data: {"nmpkg" : nmpkg, "ketpkg" : ketpkg, "ygbuat" : ygbuat, "id_pkg":id_pkg, "pkgkons":pkgkons},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            console.log('success');
                        } else {
                            console.log('Error_2');
                        }
                    }
                });
            } else {
                console.log('Error_1');
            }
        }
        function simpangperubahan2(){
            var selection = $('#autoComplete').val();
            var nmpkg = $('#namepkg').val();
                        var ketpkg = $('#ketpkg').val();
                        var pkgkons = $('#pkgkons').val();
                        var ygbuat = $('#ygbuat').val();
                        var id_pkg = $('#id_pkg').val();
                        if(nmpkg!="" && ketpkg!="" && pkgkons!="" && ygbuat!="" && id_pkg!=""){
                            $.ajax({
                                url:"<?=base_url();?>fake/updatepkg_isi",
                                type: "POST",
                                data: {"selection":selection, "nmpkg" : nmpkg, "ketpkg" : ketpkg, "ygbuat" : ygbuat, "id_pkg":id_pkg, "pkgkons":pkgkons},
                                cache: false,
                                success: function(dataResult){
                                    var dataResult = JSON.parse(dataResult);
                                    if(dataResult.statusCode==200){
                                        console.log('success_1');
                                        loadisi();
                                        $('#autoComplete').val('');
                                    } else {
                                        peringatan(''+dataResult.psn+'');
                                    }
                                }
                            });
                        } else {
                            peringatan('Ada yang Belum di isi');
                        }
        }
        const autoCompleteJS = new autoComplete({
            placeHolder: "Ketik & Pilih Kode Roll...",
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
                        var nmpkg = $('#namepkg').val();
                        var ketpkg = $('#ketpkg').val();
                        var pkgkons = $('#pkgkons').val();
                        var ygbuat = $('#ygbuat').val();
                        var id_pkg = $('#id_pkg').val();
                        if(nmpkg!="" && ketpkg!="" && pkgkons!="" && ygbuat!="" && id_pkg!=""){
                            $.ajax({
                                url:"<?=base_url();?>fake/updatepkg_isi",
                                type: "POST",
                                data: {"selection":selection, "nmpkg" : nmpkg, "ketpkg" : ketpkg, "ygbuat" : ygbuat, "id_pkg":id_pkg, "pkgkons":pkgkons},
                                cache: false,
                                success: function(dataResult){
                                    var dataResult = JSON.parse(dataResult);
                                    if(dataResult.statusCode==200){
                                        console.log('success_1');
                                        loadisi();
                                        $('#autoComplete').val('');
                                    } else {
                                        peringatan(''+dataResult.psn+'');
                                    }
                                }
                            });
                        } else {
                            peringatan('Ada yang Belum di isi');
                        }
                    }
                }
            }
        });
        function delpkg(kode){
            //peringatan('del'+kode);
            $.ajax({
                url:"<?=base_url();?>fake/del_isi",
                type: "POST",
                data: {"kode" : kode},
                cache: false,
                success: function(dataResult){
                    peringatan('Hapus kode '+kode+'');
                    loadisi();
                    
                }
            });
        }
        let personName = sessionStorage.getItem("userName");
        document.getElementById('nmoptid').innerHTML = ''+personName;
        document.getElementById('id_username').value = ''+personName;
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
            function loadisi(){
                var id_pkg = $('#id_pkg').val();
                $.ajax({
                    url:"<?=base_url();?>fake/loadisipkg",
                    type: "POST",
                    data: {"id_pkg" : id_pkg},
                    cache: false,
                    success: function(dataResult){
                        $('#tablePaket').html(dataResult);
                    }
                });
            }
            
            loadisi();
            $( "#delPaket_oke" ).on( "click", function() {
                var id_pkg = $('#id_pkg').val();
                $.ajax({
                    url:"<?=base_url();?>fake/deletPkg_isi",
                    type: "POST",
                    data: {"id_pkg" : id_pkg},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            window.location.href = "<?=base_url('fake/fakepkglistall');?>";
                        } else {
                            peringatan(''+dataResult.psn);
                        }
                    }
                });
            } );
    </script>
</body>
</html>
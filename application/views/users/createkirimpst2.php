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
        $alldt = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kd]);
        if($alldt->num_rows() == 1){
            $ad = $alldt->row_array();
            $kons = $ad['kode_konstruksi'];
        } else { redirect(base_url('users/kirimpst')); }
    ?>
    <h1>Kode Paket <?=$kd;?></h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong>, Username : <strong id="nmoptid">Nur Hikmah</strong></small><br><small class="sm">Konstruksi : <strong><?=$kons;?></strong></small>
    
    <div class="container">
        <div class="form-label">
            <small>Untuk menambahkan stok lama, anda perlu memasukan kode roll dan ukuran.</small>
        </div>
        <div class="iptcol2">
            <div class="isiIptcol">
                <label for="newkode">Kode Roll</label>
                <input type="text" id="newkode" placeholder="Masukan Kode">
            </div>
            <div class="isiIptcol">
                <label for="newukuran">Ukuran</label>
                <input type="tel" id="newukuran" placeholder="Masukan Ukuran">
            </div>
        </div>
        <div class="iptcol2" style="margin-top:5px;">
            <div class="isiIptcol">
                <label for="mc">No Mesin</label>
                <input type="text" id="mc" placeholder="Masukan No Mesin">
            </div>
            <div class="isiIptcol">
                <button style="background:blue;color:#FFFFFF;display:flex;justify-content:center;align-items:center;padding:10px 0;border-radius:7px;outline:none;border:none;transform:translateY(20px)" id="plusSimpan">+ Tambah</button>
            </div>
        </div>
        <div class="form-label">
            <!-- <label for="autoComplete">Kode Roll</label>
            <div class="autoComplete_wrapper">
                <input id="autoComplete" type="search"  dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off">
            </div> -->
            <!-- <input type="text" class="ipt" name="kons" id="kons" autofocus> -->
            <input type="hidden" id="id_username" value="0">
            <input type="hidden" id="id_tgl" value="<?=$tgl;?>">
            <input type="hidden" id="id_kons" value="<?=$kons;?>">
            <input type="hidden" id="id_pkg" value="<?=$kd;?>">
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
        <div style="display:flex;">
        <a style="background:red;color:#FFFFFF;width:105px;display:flex;justify-content:center;align-items:center;margin-top:15px;font-size:12px;padding:5px 0;border-radius:7px;" id="delPaket">Hapus Paket</a>
        <a style="background:green;color:#FFFFFF;width:105px;display:flex;justify-content:center;align-items:center;margin-top:15px;font-size:12px;padding:5px 0;border-radius:7px;margin-left:5px;" href="<?=base_url('users/kirimpst');?>">Simpan</a>
        </div>
        
    </div>
    
    <?php
        $kdrol = $this->db->query("SELECT kode_roll FROM data_ig WHERE konstruksi='$kons' AND loc_now='Samatex' AND kode_roll NOT IN (SELECT kode_roll FROM data_if) AND kode_roll NOT IN (SELECT kode_roll FROM data_fol)");
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
        let personName = sessionStorage.getItem("userName");
        document.getElementById('nmoptid').innerHTML = ''+personName;
        document.getElementById('id_username').value = ''+personName;
        var kons = $('#id_kons').val();
        var pkg = $('#id_pkg').val();
        var tgl = $('#id_tgl').val();

        $( "#plusSimpan" ).on( "click", function() {
            var oldKodeRoll = $('#newkode').val();
            var oldUkuran = $('#newukuran').val();
            var mc = $('#mc').val();
            if(oldKodeRoll!="" && oldUkuran!=""){
                $.ajax({
                    url:"<?=base_url();?>users2/addRollLama",
                    type: "POST",
                    data: {"oldKodeRoll" : oldKodeRoll, "oldUkuran" : oldUkuran, "pkg" : pkg, "mc" : mc, "kons": kons, "tgl" : tgl, "user" : personName},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            loadisi();
                            suksestoast('Berhasil menambah');
                            $('#newkode').val('');
                            $('#newukuran').val('');
                        } else {
                            peringatan(''+dataResult.psn);
                        }
                    }
                });
            } else {
                peringatan('Kode dan Ukuran harus di isi');
            }
        } );
        
        
        function delpkg(kode){
            //peringatan('del'+kode);
            $.ajax({
                url:"<?=base_url();?>users2/delisi2",
                type: "POST",
                data: {"kode" : kode},
                cache: false,
                success: function(dataResult){
                    var dataResult = JSON.parse(dataResult);
                    if(dataResult.statusCode==200){
                        peringatan('Hapus kode '+kode+'');
                        loadisi();
                    } 
                }
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
                    duration: 5000,
                    close:true,
                    gravity:"bottom",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
            } 
            function loadisi(){
                $.ajax({
                    url:"<?=base_url();?>users2/loadisipkg",
                    type: "POST",
                    data: {"pkg" : pkg},
                    cache: false,
                    success: function(dataResult){
                        $('#tablePaket').html(dataResult);
                    }
                });
            }
            loadisi();
            $( "#delPaket" ).on( "click", function() {
                $.ajax({
                    url:"<?=base_url();?>users2/deletPkg",
                    type: "POST",
                    data: {"pkg" : pkg},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            window.location.href = "<?=base_url('users/kirimpst/');?>";
                        } else {
                            peringatan(''+dataResult.psn);
                        }
                    }
                });
            } );
    </script>
</body>
</html>
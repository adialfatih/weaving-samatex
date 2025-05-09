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
        $this->db->query("DELETE FROM fake_pkglist WHERE keterangan='null' AND yg_buat='null' AND konstruksi='null'");
        
    ?>
    <h1>Fake Packinglist</h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong>, Username : <strong id="nmoptid">Nur Hikmah</strong></small><br><small class="sm"></small>
    <input type="hidden" value="<?=$ygbuat;?>" id="depPkg">
    <div class="container">
        <div class="form-label">
            <small>Packing List Stock Opname</small>
        </div>
        <?php
        $daata = $this->db->query("SELECT * FROM fake_pkglist WHERE keterangan!='null' AND yg_buat!='null' AND konstruksi!='null'");
        ?>
        <div class="fortable2">
        <table style="font-size:13px;" id="tablePaket">
            <?php if($daata->num_rows() > 0){ 
            foreach($daata->result() as $val): 
                $id = $val->id_fakepkg;
                $jml_roll = $this->db->query("SELECT * FROM fake_isi WHERE id_fakepkg='$id'")->num_rows();
                $pjg = $this->db->query("SELECT SUM(ukuran) AS ukr FROM fake_isi WHERE id_fakepkg='$id'")->row("ukr");
            ?>
            <tr style="background:#e2e2e2;">
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>ID</td>
                <td><strong><?=$id;?></strong></td>
            </tr>
            <tr>
                <td>PKG</td>
                <td><strong><?=$val->name_pkglist;?></strong></td>
            </tr>
            <tr>
                <td>KET</td>
                <td><strong><?=$val->keterangan;?></strong></td>
            </tr>
            <tr>
                <td>KONSTRUKSI</td>
                <td><strong><?=$val->konstruksi;?></strong></td>
            </tr>
            <tr>
                <td>ROLL</td>
                <td><strong><?=$jml_roll;?></strong></td>
            </tr>
            <tr>
                <td>PANJANG</td>
                <td><strong><?=$pjg;?></strong></td>
            </tr>
            <tr>
                <td></td>
                <td><a href="<?=base_url('fake/fakepkglist/'.$id);?>">Lihat Selengkapnya</a></td>
            </tr>
            
            <?php endforeach;
            } else { ?>
            <tr><td>Kosong</td></tr>
            <?php } ?>
        </table>
        </div>
        
        <!-- <button class="btn-simpan" style="padding:5px 0px;" id="addrollLama">Tambahkan Stok Lama</button> -->
    </div>
    
    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
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
                var id_pkg = $('#id_pkg').val()
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
            
    </script>
</body>
</html>
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
        $sj = $this->data_model->get_byid('surat_jalan', ['sha1(no_sj)'=>$kd]);
        if($sj->num_rows() == 1){
            $nosj = $sj->row("no_sj");
        } else {
            $nosj = "Tidak Ditemukan";
        }
        $kodepkg = $this->db->query("SELECT kode_konstruksi,kd,no_sj FROM new_tb_packinglist WHERE no_sj='$nosj'");
    ?>
    <h1>No SJ : <?=$nosj;?></h1>
    <?php
        foreach($kodepkg->result() as $val):
            $roll = $this->data_model->get_byid('new_tb_isi', ['kd'=>$val->kd]);
            if($roll->num_rows() > 0 ){
                $dari = 1;
            } else {
                $dari = 2;
                $roll = $this->data_model->get_byid('data_ig',['loc_now'=>$val->kd]);
            }
    ?>
    <div class="container">
        <div class="fortable2">
        <table style="font-size:13px;" id="tablePaket">
            <tr>
                <td colspan="5">Kode PKG : <strong><?=$val->kd;?></strong>, Konstruksi <strong><?=$val->kode_konstruksi;?></strong></td>
            </tr>
            <tr>
                <td><strong>No</strong></td>
                <td><strong>Kode Roll</strong></td>
                <td><strong>Ukuran</strong></td>
                <td><strong>MC</strong></td>
                <td><strong>#</strong></td>
            </tr>
            <?php
            $total = 0;
            foreach ($roll->result() as $n => $val) { 
                $i = $n+1; 
                if($dari == 1){
                  $kd = $val->kode;
                } else {
                  $kd = $val->kode_roll;
                }
                $dtroll = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kd])->row_array();
                $total+=$dtroll['ukuran_ori'];
                ?>
                <tr>
                    <td><?=$i?></td>
                    <td><?=$kd?></td>
                    <td><?=$dtroll['ukuran_ori']?></td>
                    <td><?=$dtroll['no_mesin']?></td>
                    <td>
                        <input type="checkbox">
                    </td>
                </tr>
                <?php
            } //end foreach
            ?>
            <tr>
                <td colspan="3">Total :</td>
                <td><?=number_format($total,0,',','.');?></td>
                <td></td>
            </tr>
        </table>
        </div>
        
    </div>
    
    <?php endforeach; ?>
    
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
            
    </script>
</body>
</html>
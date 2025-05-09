<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok di Pusatex</title>
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
        $pabrik = strtoupper($kd);
        $_kons = strtoupper($this->uri->segment(4));
        //echo $pabrik."-".$_kons;
        $_dtrol = $this->db->query("SELECT * FROM new_roll_onpst WHERE kode_roll LIKE '$pabrik%' AND kons = '$_kons'");
    ?>
    <h1>Stok di Pusatex</h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong>, Username : <strong id="nmoptid">Nur Hikmah</strong></small><br><small class="sm">Konstruksi : <strong><?=$_kons;?></strong></small>
    <input type="hidden" value="<?=$ygbuat;?>" id="depPkg">
    <div class="container">
        <div class="fortable2">
        <table style="font-size:13px;" id="tablePaket">
            <tr>
                <td style="text-align:center;"><strong>No</strong></td>
                <td><strong>Kode Roll</strong></td>
                <td><strong>Ukuran</strong></td>
                <td><strong>#</strong></td>
            </tr>
            <?php if($_dtrol->num_rows() > 0){
            $no=1;
            $utotal = 0;
            foreach($_dtrol->result() as $kd):
                echo "<tr>";
                echo "<td style='text-align:center;'>".$no."</td>";
                echo "<td>".strtoupper($kd->kode_roll)."</td>";
                echo "<td>".$kd->ukuran."</td>";
                ?><td><img src="<?=base_url('assets/del.png');?>" alt="Hapus kode" style="width:20px;" onclick="delthis('<?=$kd->kode_roll;?>')"></td><?php
                echo "</tr>";
                $utotal+=$kd->ukuran;
                $no++;
            endforeach;
            echo "<tr>";
            echo "<td colspan='2'><strong>Total</strong></td>";
            echo "<td><strong>".number_format($utotal)."</strong></td>";
            echo "<td></td>";
            echo "<tr>";
            } else { ?>
            <tr>
                <td colspan="4">Tidak ada data</td>
            </tr>
            <?php } ?>
        </table>
        </div>
        <p>&nbsp;</p>
        <p>
            <strong>Note : </strong>Jika anda menghapus stok di pusatex. Status lokasi barang tidak akan berubah. Gunakan menu terima barang dari Pusatex untuk merubah status lokasi barang menjadi di Samatex
        </p>
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
            function delthis(kd){
                var confirmDelete = confirm("Yakin mau menghapus kode "+kd+"?");
                if(confirmDelete==true){
                    location.href="<?=base_url('Users/delpkgonpst/'.$pabrik.'/'.$_kons.'/');?>"+kd+"";
                }
            }
    </script>
</body>
</html>
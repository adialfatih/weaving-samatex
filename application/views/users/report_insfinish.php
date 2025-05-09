<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inspect Finish</title>
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
        $tgl = date('Y-m-d');
        $ex = explode('-', $tgl);
        $arbln = $this->data_model->printBln($ex[1]);
        $printTgl = $ex[2]." ".$arbln." ".$ex[0];
        $username =strtolower($this->uri->segment(3));
        $cek_username = $this->data_model->get_byid('a_operator', ['username'=>$username]);
        if($cek_username->num_rows() == 1){
            $cek_ig = $this->data_model->get_byid('data_if', ['tgl_potong'=>$tgl,'operator'=>$username]);
        
    ?>
        <p style="font-size:12px;">Resume <br>
        Nama Operator : <strong><?=ucfirst($username);?></strong><br>
        Tanggal : <strong><?=$printTgl;?></strong><br>
        Inspect Finish :
        </p>
        <div class="fortable2">
            <table id="fortable" style="font-size:12px;">
                <tr>
                    <td><strong>No</strong></td>
                    <td><strong>Kode Roll</strong></td>
                    <td><strong>Konstruksi</strong></td>
                    <td><strong>MC</strong></td>
                    <td><strong>Ukr Sblm</strong></td>                    
                    <td><strong>Ukuran (Yrd)</strong></td>
                </tr>
                <?php
                if($cek_ig->num_rows() > 0){
                    $total_ukuran = 0;
                    $total_sebelum = 0;
                    foreach($cek_ig->result() as $n => $val):
                        
                        echo "<tr>";
                        $num = $n+1;
                        echo "<td>".$num."</td>";
                        echo "<td>".$val->kode_roll."</td>";
                        echo "<td>".$val->konstruksi."</td>";
                        $mc = $this->data_model->get_byid('data_ig',['kode_roll'=>$val->kode_roll]);
                        if($mc->num_rows()==1){
                            $nomc = $mc->row("no_mesin");
                        } else {
                            $koderollmin = substr($val->kode_roll, 0, -1);
                            $mc = $this->data_model->get_byid('data_ig',['kode_roll'=>$koderollmin]);
                            $nomc = $mc->row("no_mesin");
                        }
                        echo "<td>".$nomc."</td>";
                        echo "<td>".$val->ukuran_sebelum."</td>";
                        $yarrd = floatval($val->ukuran_ori) / 0.9144;
                        $total_ukuran+= floatval($yarrd);
                        $total_sebelum+= floatval($val->ukuran_sebelum);
                        if(fmod($yarrd, 1) !== 0.00){
                            $yarrd = number_format($yarrd,2,',','.');
                        } else {
                            $yarrd = number_format($yarrd,0,',','.');
                        }
                        echo "<td>".$yarrd."</td>";
                        echo "</tr>";
                    endforeach;
                    echo "<tr>";
                    echo "<td>#</td><td colspan='3'><strong>Total</strong></td>";
                    if(fmod($total_sebelum, 1) !== 0.00){
                        $total_sebelum = number_format($total_sebelum,2,',','.');
                    } else {
                        $total_sebelum = number_format($total_sebelum,0,',','.');
                    }
                    echo "<td>".$total_sebelum."</td>";
                    if(fmod($total_ukuran, 1) !== 0.00){
                        $total_ukuran = number_format($total_ukuran,2,',','.');
                    } else {
                        $total_ukuran = number_format($total_ukuran,0,',','.');
                    }
                    echo "<td>".$total_ukuran."</td>";
                    echo "</tr>";
                ?>
                <tr>
                    <td colspan="6">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="6">Produksi : </td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Konstruksi</strong></td>
                    <td colspan='2'><strong>ORI (yrd)</strong></td>
                    <td><strong>BS</strong></td>
                    <td><strong>BP</strong></td>
                </tr>
                <?php
                    $cek_prod = $this->data_model->get_byid('data_produksi_opt', ['username_opt'=>$username,'tgl'=>$tgl,'proses'=>'insfinish']);
                    foreach($cek_prod->result() as $vals):
                        echo "<tr>";
                        echo "<td colspan='2'>".$vals->konstruksi."</td>";
                        $yarrd2 = floatval($vals->ukuran) / 0.9144;
                        if(fmod($yarrd2, 1) !== 0.00){
                            $yarrd2 = number_format($yarrd2,2,',','.');
                        } else {
                            $yarrd2 = number_format($yarrd2,0,',','.');
                        }
                        echo "<td colspan='2'>".$yarrd2."</td>";
                        echo "<td>".$vals->bs."</td>";
                        echo "<td>".$vals->bp."</td>";
                        echo "</tr>";
                    endforeach;
                } else {
                    echo "<tr><td colspan='5'>Data Inspect Grey Kosong</td></tr>";
                }
                ?>
                
            </table>
        </div>
        <div class="doble-btn no-print">
                <div class="db-btn" onclick="window.print()">Cetak</div>
                <div class="db-btn red" onclick="logout()">Keluar</div>
        </div>
        
    <?php } else { echo "Username tidak ditemukan"; } ?>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
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
            function logout(){
                sessionStorage.removeItem("userName");
                peringatan('Logout.. Please wait');
                setTimeout(() => {
                    window.location.href = "<?=base_url('users');?>";
                }, "3300");
            }
    </script>
</body>
</html>
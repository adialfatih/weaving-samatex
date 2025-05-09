<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STOK FOLDING FINISH</title>
    <link rel="stylesheet" href="<?=base_url('new_db/');?>style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
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
        .hamburger {
        cursor: pointer;
        }

        .hamburger input {
        display: none;
        }

        .hamburger svg {
        /* The size of the SVG defines the overall size */
        height: 3em;
        /* Define the transition for transforming the SVG */
        transition: transform 600ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        .line {
        fill: none;
        stroke: #4287f5;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-width: 3;
        /* Define the transition for transforming the Stroke */
        transition: stroke-dasharray 600ms cubic-bezier(0.4, 0, 0.2, 1),
                    stroke-dashoffset 600ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        .line-top-bottom {
        stroke-dasharray: 12 63;
        }

        .hamburger input:checked + svg {
        transform: rotate(-45deg);
        }

        .hamburger input:checked + svg .line-top-bottom {
        stroke-dasharray: 20 300;
        stroke-dashoffset: -32.42;
        }
        .mblmenu {
            width: 100%;
            height:100vh;
            position: absolute;
            top: 0;
            left: 0;
            background:#fff;
            position: fixed;
            z-index: 999;
            padding:100px 20px 0px 20px;
            transition: all 0.5s ease;
            transform:translateX(100%);
        }
        .mblmenu.active {
            transform:translateX(0);
        }
        .mblmenu a {text-decoration:none;}
        .isimenu {
            width: 100%;
            padding:15px 10px;
            background:#e3e3e3;
            margin-bottom:10px;
            color:#000;
        }
        .isimenu:hover {
            background:#4287f5;
            color:#fff;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <div style="width:100%;display:flex;justify-content:space-between;align-items:center;padding:10px;z-index:9999;">
        <div style="display:flex;flex-direction:column;">
            <h1>Stok Folding Finish</h1>
            <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong>, Username : <strong id="nmoptid">Nur Hikmah</strong></small>
        </div>
        <label class="hamburger">
            <input type="checkbox" id="hambmenuid">
            <svg viewBox="0 0 32 32">
                <path class="line line-top-bottom" d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22"></path>
                <path class="line" d="M7 16 27 16"></path>
            </svg>
        </label>
    </div>
        <div class="mblmenu">
            <a href="<?=base_url('users/terimafrompst');?>">
                <div class="isimenu">Terima Barang dari Pusatex</div>
            </a>
            <a href="<?=base_url('users/terimafromrjs');?>">
                <div class="isimenu">Terima Barang dari RJS</div>
            </a>
            <a href="<?=base_url('users/terimafromrjs2');?>">
                <div class="isimenu">Data Stok dari RJS</div>
            </a>
            <a href="<?=base_url('fake/fakepkglistall');?>">
                <div class="isimenu">Fake Packinglist</div>
            </a>
            <a href="<?=base_url('fake/fakepkglist');?>">
                <div class="isimenu">Buat Fake Packinglist</div>
            </a>
            <a href="<?=base_url('users/delstok');?>">
                <div class="isimenu">Hapus Stok Folding</div>
            </a>
            <a href="<?=base_url('users/editkoderoll');?>">
                <div class="isimenu">Edit Kode Roll</div>
            </a>
            <a href="<?=base_url('stok/sinkronstok');?>">
                <div class="isimenu" style="color:red;">SINKRONKAN STOK FOLDING GREY</div>
            </a>
            <a href="<?=base_url('stok/sinkronstok2');?>">
                <div class="isimenu" style="color:red;">SINKRONKAN STOK FOLDING FINISH</div>
            </a>
            <a href="<?=base_url('users/');?>">
                <div class="isimenu">Logout</div>
            </a>
        </div>
    <input type="hidden" id="id_username">
    <div class="container">
        <?php
        $qry = $this->db->query("SELECT DISTINCT konstruksi FROM data_fol WHERE posisi='Samatex' OR posisi LIKE '%PKT%' ORDER BY konstruksi ASC")->result();
        ?>
        <div class="kotaknewpkg">
            <span>Stok Finish</span>
            <div class="fortable has" style="font-size: 13px;min-height: calc(100vh - 200px);">
                <table id="idtable_owek">
                    <tr>
                        <th>No.</th>
                        <th>Konstruksi</th>
                        <th>Jumlah Roll</th>
                        <th>Stok Folding</th>
                    </tr>
                    <?php
                    $no = 1;
                    foreach ($qry as $row) {
                        $konstruksi = $row->konstruksi;
                        $chto = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$konstruksi])->row("chto");
                        if($chto == "null" OR $chto == "NULL"){
                            $chto = $konstruksi;
                        }
                        $jmlRoll = $this->db->query("SELECT * FROM data_fol WHERE konstruksi='$konstruksi' AND jns_fold='Finish' AND ( posisi='Samatex' OR posisi LIKE '%PKT%' )")->num_rows();
                        $totalPanjang = $this->db->query("SELECT SUM(ukuran) AS ukr FROM data_fol WHERE konstruksi='$konstruksi' AND jns_fold='Finish' AND ( posisi='Samatex' OR posisi LIKE '%PKT%' )")->row("ukr");
                        if($jmlRoll > 0){
                        echo "<tr>";
                        echo "<td>".$no++."</td>";
                        echo "<td>".$chto."</td>";
                        echo "<td>".$jmlRoll."</td>";
                        echo "<td>".$totalPanjang."</td>";
                        echo "</tr>";
                        $this->data_model->updatedata('kode_konstruksi',$konstruksi,'data_stok',['prod_ff'=>$totalPanjang]);
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
        
        
        <button class="btn-save" style="background:red;border:1px solid #FFF;" id="btn-logout">Logout</button>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let personName = sessionStorage.getItem("userName");
        document.getElementById('nmoptid').innerHTML = ''+personName;
        document.getElementById('id_username').value = ''+personName;
                
        $( "#btn-logout" ).on( "click", function() {
            window.location.href = "<?=base_url('users/');?>";
        });
        $('#hambmenuid').on('change', function() {
            $('.mblmenu').toggleClass('active');
        });
            
    </script>
</body>
</html>
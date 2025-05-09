<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok On Packinglist</title>
    <link rel="stylesheet" href="<?=base_url();?>new_db/style.css?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Vithkuqi&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .autoComplete_wrapper input{
            width: 110%;
            transform: translateX(-10%);
        }
        .lds-ring {
        display: inline-block;
        position: relative;
        width: 20px;
        height: 20px;
        }
        .lds-ring div {
        box-sizing: border-box;
        display: block;
        position: absolute;
        width: 24px;
        height: 24px;
        margin: 8px;
        border: 8px solid #ccc;
        border-radius: 50%;
        animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        border-color: #ccc transparent transparent transparent;
        }
        .lds-ring div:nth-child(1) {
        animation-delay: -0.45s;
        }
        .lds-ring div:nth-child(2) {
        animation-delay: -0.3s;
        }
        .lds-ring div:nth-child(3) {
        animation-delay: -0.15s;
        }
        @keyframes lds-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
        }
        .card-kons {
            width: 100px;
            background: #FFFFFF;
            margin: 0 10px 10px 0;
            box-shadow: 2px 5px 10px #c2defc;
            display: flex;
            flex-direction: column;
            border-radius: 7px;
            border: 1px solid #edeeed;
            overflow: hidden;
        }
        .card-kons div {
            width: 100%;
            text-align: center;
        }
        .card-kons div:nth-child(1){
            background: #096cd6;
            color:#FFFFFF;
            padding: 5px 0;
            font-size:12px;
        }
        .card-kons div:nth-child(2){
            font-family: 'Noto Serif Vithkuqi', serif;
            font-size: 16px;
            padding: 10px 0;
        }
            
    </style>
</head>
<body>
    
    <div class="topbar">
        <?php 
            $kons = $this->uri->segment(2);
            $showKonstruksi = "null";
            $namkons = $this->data_model->get_byid('tb_konstruksi', ['sha1(kode_konstruksi)'=>$kons]);
            if($namkons->num_rows() == 0){
                $namkons2 = $this->data_model->get_byid('tb_konstruksi', ['sha1(chto)'=>$kons]);
                $showKonstruksi = $namkons2->row("chto");
            } elseif($namkons->num_rows() == 1) {
                $showKonstruksi = $namkons->row("kode_konstruksi");
            }
            $cekkons = $this->data_model->get_byid('new_tb_packinglist', ['sha1(kode_konstruksi)'=>$kons,'lokasi_now'=>'Samatex','siap_jual'=>'y','no_sj'=>'NULL']);
            if($cekkons->num_rows() > 0){
                echo $showKonstruksi." On Packinglist";
            } else {
                $cekkons = $this->data_model->get_byid('new_tb_packinglist', ['lokasi_now'=>'Samatex','siap_jual'=>'y','no_sj'=>'NULL']);
                echo "Stok On Packinglist";
            }
        ?>
    </div>
    <div class="konten-mobile2">
            <div class="fortable2" style="margin-bottom: 25px; font-size:12px;">
                <table>
                    <tr>
                        <td><strong>No PKG</strong></td>
                        <td><strong>Konstruksi</strong></td>
                        <td><strong>Roll</strong></td>
                        <td><strong>Jumlah</strong></td>
                        <td><strong>Tujuan</strong></td>
                    </tr>
                    <?php
                    foreach($cekkons->result() as $val):
                    ?>
                    <tr>
                        <td>
                            <?php
                                $ex = explode('G', $val->kd);
                                echo $ex[1];
                            ?>
                        </td>
                        <td><?=$val->kode_konstruksi;?></td>
                        <td><?=$val->jumlah_roll;?></td>
                        <td>
                            <?php
                                if(fmod($val->ttl_panjang, 1) !== 0.00){
                                    echo number_format($val->ttl_panjang,2,',','.');
                                } else {
                                    echo number_format($val->ttl_panjang,0,',','.');
                                }
                            ?>
                        </td>
                        <td><?=ucfirst( $val->customer );?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
    </div>       
    
    
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    
</body>
</html>
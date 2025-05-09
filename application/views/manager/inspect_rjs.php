<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RJS Produksi Inspect Grey</title>
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
    <?php
    $namaHari = array(
        'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
    );
    $tanggalHariini = date('d');
    $bulanHariini = date('m');
    $tahunHariini = date('Y');
    $dateNow = date('Y-m-d');
    $hariIni = date('w');
    $tanggalSebelumnya = date('Y-m-d', strtotime('-1 day'));
    $hariSebelumnya = date('w', strtotime('-1 day'));
    // echo $tanggalHariini."<br>";
    // echo $namaHari[$hariIni]."<br>";
    // echo $namaHari[$hariSebelumnya]."<br>";
    // echo $tanggalSebelumnya."<br>";
    $ar = array(
        '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    );
    $ex = explode('-', $tanggalSebelumnya);
    $printHarIni = $namaHari[$hariIni].", ".$tanggalHariini." ".$ar[$bulanHariini]." ".$tahunHariini;
    ?>
    <div class="topbar">
       Produksi Inspect Grey (RJS)
    </div>
    <div class="konten-mobile2">
        <div class="kotaknewpkg">
            <span>Filter Tanggal</span>
            <div style="width: 100%;display: flex;flex-direction: column;">
                
                <div class="form-label">
                    <label for="mctgl">Tanggal</label>
                    <input type="date" class="ipt" name="tanggal" id="mctgl" value="<?=date('Y-m-d');?>">
                </div>
                <div style="display:none;" id="owekloading">
                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                </div>
            </div>
        </div>
    </div>
    
    <input type="hidden" id="nowTgl" value="<?=$dateNow;?>">
        <div class="konten-mobile2" style="margin-top:20px;" id="kontenStok">
            Loading...
        </div>
           
    
    
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        var tgl = $('#nowTgl').val();
        //var kons = $('#autoComplete').val();
        //console.log('tgl :'+tgl);
        //console.log('kons :'+kons);
        function loadData(tgl){
            $('#owekloading').css({"display": "flex", "width": "100%", "justify-content": "center"});
            $.ajax({
                url:"<?=base_url();?>alldashboard/loaddataInspect",
                type: "POST",
                data: {"jns" : "RJS", "tgl" : tgl},
                cache: false,
                success: function(dataResult){
                    $('#kontenStok').html(dataResult);
                    $('#owekloading').hide();
                    //console.log('s'+tgl);
                }
            });
        }
        loadData(tgl);
        $( "#mctgl" ).on( "change", function() { 
            var tgl = $('#mctgl').val();
            loadData(tgl);
        });
       
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Folding Grey</title>
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
    <h1>Produksi Folding Grey</h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong> Operator : <strong id="nmoptid">Nur Hikmah</strong></small>
    <div class="container">
        <div class="form-label" id="khususAdmin" style="display:none;">
            <label for="autoComplete">Tgl Folding</label>
            <input type="date" name="oltgl" class="ipt" id="oldTanggalFolding">
        </div>
        <div class="form-label" id="khususAdmin2" style="display:none;">
            <label for="autoComplete">Operator</label>
            <input type="text" name="optfol" class="ipt" placeholder="Masukan operator folding" id="oldOperator">
        </div>
        <div class="form-label" id="khususAdmin3" style="display:none;">
            <label for="autoComplete">Note</label>
            <textarea name="notes" class="ipt" placeholder="Masukan keterangan" id="noteOperator"></textarea>
        </div>
        <div class="form-label">
            <label for="autoComplete">Kode Roll</label>
            <div class="autoComplete_wrapper">
                <input id="autoComplete" onchange="teschange(this.value)" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off">
            </div>
            <!-- <input type="text" class="ipt" name="kons" id="kons" autofocus> -->
            <input type="hidden" id="id_username" value="0">
            <input type="hidden" id="id_tgl" value="<?=$tgl;?>">
        </div>
        <div class="inforsblm" id="resultKode">
            Klik untuk melihat.
        </div>
        <div class="form-label" style="margin-top:5px;">
            <label for="ukuran">Ukuran</label>
        </div>
        <div class="form-ukuran">
            <input type="tel" id="ukuran" name="ukuran[]" placeholder="Ukuran Folding">
            <small class="geser-yard">Meter</small>
            <span class="circle-green" id="addbtn">+</span>
        </div>
        <?php for ($i=2; $i <11 ; $i++) { ?>
            <div class="form-ukuran" id="divukuran<?=$i;?>" style="display:none;">
                <input type="tel" id="ukuran<?=$i;?>" name="ukuran[]" placeholder="Ukuran Folding">
                
                <small class="geser-yard">Meter</small>
                <span class="circle-green" id="addbtn<?=$i;?>">+</span>
            </div>
        <?php } ?>
        

        
        
        <button class="btn-save" onclick="simpanData()">Simpan</button>
        <div class="fortable">
            <table id="fortable" style="font-size:12px;">
            </table>
        </div>
        <a href="" id="btnSelesai" class="btn-save2">Selesai</a>
    </div>
    <?php
        $ig = $this->db->query("SELECT kode_roll,loc_now FROM data_ig WHERE loc_now IN ('Samatex','stok') AND kode_roll NOT IN (SELECT kode_roll FROM data_fol) AND kode_roll NOT IN (SELECT kode_roll FROM data_if)");
        $ar_ig = array();
        foreach($ig->result() as $val){
            $ar_ig[] = '"'.$val->kode_roll.'"';
        }
        $im_kons = implode(',', $ar_ig);
        
    ?>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function teschange(kode){
            $.ajax({
                url:"<?=base_url();?>users/cariInsgrey2",
                type: "POST",
                data: {"kode" : kode},
                cache: false,
                success: function(dataResult){
                    var dataResult = JSON.parse(dataResult);
                    if(dataResult.statusCode==200){
                        $('#resultKode').html(""+dataResult.psn+"");
                        //alert('tes');
                    } else {
                        peringatan(''+dataResult.psn+'');
                        $('#resultKode').html("<span style='color:red;'>Kode tidak ditemukan</span>");
                    }
                }
            });
        }
            function roundToTwo(num) {
                return +(Math.round(num + "e+2")  + "e-2");
            }
            
            $( "#addbtn" ).on( "click", function() {
                    $( "#addbtn" ).hide();
                    $( "#divukuran2" ).show();
                    
            } );
            for (let io = 2; io < 11; io++) {
                $( "#addbtn"+io+"" ).on( "click", function() {
                    $( "#addbtn"+io+"" ).hide();
                    var newnum = io+1;
                    $( "#divukuran"+newnum+"" ).show();
                } );
                
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
                    }
                }
            }
        });
        let personName = sessionStorage.getItem("userName");
        document.getElementById('nmoptid').innerHTML = ''+personName;
        if(personName == "Adenna" || personName == "adenna"){
            $('#khususAdmin').show();
            $('#khususAdmin2').show();
            $('#khususAdmin3').show();
            var old_tgl = $('#oldTanggalFolding').val();
            var old_opt = $('#oldOperator').val();
            var old_note = $('#noteOperator').val();
        } else {
            $('#khususAdmin').hide();
            $('#khususAdmin2').hide();
            $('#khususAdmin3').hide();
            var old_tgl = "null";
            var old_opt = "null";
            var old_note = "null";
        }
        document.getElementById('id_username').value = ''+personName;
        document.getElementById('btnSelesai').href = "<?=base_url();?>users/laporanfolgrey/"+personName+"";
        function loadData(){
            $.ajax({
                url:"<?=base_url();?>users/loaddata",
                type: "POST",
                data: {"username" : personName, "proses" : "folgrey"},
                cache: false,
                success: function(dataResult){
                    $('#fortable').html(dataResult);
                }
            });
        }
        loadData();
        function kuyhas(iddata){
            var result = window.confirm("Apakah Anda yakin ingin menghapus?");
            if (result) {
            $.ajax({
                url:"<?=base_url();?>produksistx/delfolgrey",
                type: "POST",
                data: {"iddata" : iddata},
                cache: false,
                success: function(dataResult){
                    var dataResult = JSON.parse(dataResult);
                    if(dataResult.statusCode==200){
                        peringatan('Hapus kode '+dataResult.psn+'');
                        loadData();
                    } 
                }
            });
            } else { console.log('Erorr'); }
        }
        function simpanData(){
            var koderoll = document.getElementById('autoComplete').value;
            var ori = document.querySelectorAll('input[name="ukuran[]"]');
            const ukuranArray = [];
            ori.forEach(ori => {
                ukuranArray.push(ori.value);
            });
            var tgl = document.getElementById('id_tgl').value;
            var username = document.getElementById('id_username').value;
            if(koderoll!="" && username!="null"){
                $.ajax({
                    url:"<?=base_url();?>users2/prosesFolGrey",
                    type: "POST",
                    data: {"koderoll" : koderoll, "ori" : ukuranArray, "tgl":tgl, "username":username, "old_tgl" : old_tgl, "old_opt" : old_opt, "old_note" : old_note},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                suksestoast(''+dataResult.psn+'');
                                loadData();
                                document.getElementById('autoComplete').value = '';
                                $('#resultKode').html('Klik untuk melihat');
                                document.getElementById('ukuran').value = '';
                                $('#addbtn').show();
                                for (let po = 2; po < 11; po++) {
                                    document.getElementById('ukuran'+po+'').value = '';
                                    $('#divukuran'+po+'').hide();
                                    $('#addbtn'+po+'').show();
                                }
                            } else {
                                peringatan(''+dataResult.psn+'');
                            }
                        }
                });
            } else {
                peringatan('Anda perlu mengisi semua data');
            }
        } // end proses simpan
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
    </script>
</body>
</html>
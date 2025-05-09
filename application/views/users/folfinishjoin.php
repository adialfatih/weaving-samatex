<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Folding Finish</title>
    <link rel="stylesheet" href="<?=base_url('new_db/');?>style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .autoComplete_wrapper input{
            width: 95%;
            transform: translateX(10px);
        }
        .tigakolom {
            width:100%;
            display:flex;
            justify-content:space-between;
        }
        .tigakolom div {
            width:30%;
            display:flex;
            flex-direction:column;
        }
        .tigakolom div label {font-size:12px;}
        .tigakolom div input {
            padding :7px;
            outline:none;
            border :1px solid #CCC;
            border-radius:4px;
        }
        .tigakolom div button {font-size:12px;
            padding:7px;
            transform:translateY(14px);
            outline:none;
            background:#05b514;
            color:#FFFFFF;
            border:none;
            border-radius:7px;
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
    <h1>Join Folding Finish</h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong> Operator : <strong id="nmoptid">Nur Hikmah</strong></small>
    <div class="container">
        <div class="form-label">
            <label for="autoComplete">Konstruksi</label>
            <div class="autoComplete_wrapper">
                <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off">
            </div>
            <!-- <input type="text" class="ipt" name="kons" id="kons" autofocus> -->
            <input type="hidden" id="id_username" value="0">
            <input type="hidden" id="id_tgl" value="<?=$tgl;?>">
        </div>
        <div class="kotaknewpkg">
            <span>Join Kode Roll</span>
            <div class="tigakolom">
                <div>
                    <label for="kodeol"><strong>Kode Roll</strong></label>
                    <input type="text" id="inkodeol" placeholder="Input Kode">
                </div>
                <div>
                    <label for="ukrol"><strong>Ukuran</strong></label>
                    <input type="tel" id="inukrol" placeholder="Input Ukuran">
                </div>
                <div>
                    <button id="addButon50">Add</button>
                </div>
            </div>
        </div>
        
        <div class="inforsblm" id="resultKode" style="margin-top:10px;">
            Kode yang akan di join:
        </div>
        <div class="form-label" style="margin-top:10px;">
            <label for="ukuran">Ukuran Akhir</label>
        </div>
        <div class="form-ukuran">
            <input type="tel" id="ukuranJoin" name="ukuran" placeholder="Ukuran ORI">
            
            <small class="geser-yard">Yard</small>
        </div>
        
        
        <div class="fortable">
            <table id="fortable" style="font-size:12px;">
            </table>
        </div>
        <div class="kolom2">
            <a href="javascript:;" class="btn-save2" style="background:#4287f5;border:none;" onclick="simpanData()">Simpan</a>
            <a href="<?=base_url();?>users/folfinish" id="btnJoin5" class="btn-save3">&laquo; Kembali</a>
        </div>
        
    </div>
    <?php
        $ig = $this->db->query("SELECT * FROM tb_konstruksi");
        $ar_ig = array();
        foreach($ig->result() as $val){
            $ar_ig[] = '"'.$val->kode_konstruksi.'"';
        }
        $im_kons = implode(',', $ar_ig);
        
    ?>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
            function roundToTwo(num) {
                return +(Math.round(num + "e+2")  + "e-2");
            }
            $( "#ukuran" ).on( "keyup", function() {
                    var nilai = parseInt($('#ukuran').val());
                    var nYard = nilai / 0.9144;
                    var nYard2 = roundToTwo(nYard);
                    if(!isNaN(nYard)){
                        $('#ukuranyy').val(''+nYard2);  
                    } else {
                        $('#ukuranyy').val('');  
                    }      
            } );
            $( "#addbtn" ).on( "click", function() {
                    $( "#addbtn" ).hide();
                    $( "#divukuran2" ).show();
                    
            } );
            $( "#addButon50" ).on( "click", function() {
                var inOldkode = $('#inkodeol').val();               
                var inOllukr = $('#inukrol').val();   
                var kons = $('#autoComplete').val();   
                if(inOllukr!=""){
                    $.ajax({
                        url:"<?=base_url();?>users/kodetojoin",
                        type: "POST",
                        data: {"inOldkode" : inOldkode, "kons":kons, "inOllukr":inOllukr},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                var kontainerDiv = document.getElementById('resultKode');
                                var paragrafBaru = document.createElement('p');
                                paragrafBaru.innerHTML = "Kode : <strong>"+inOldkode+"</strong>, Ukuran : <strong>"+dataResult.ukuran+"</strong>, <input type='hidden' name='st[]' value='"+dataResult.st+"'> <input type='hidden' name='koder[]' value='"+inOldkode+"'> <input type='hidden' name='ukra[]' value='"+dataResult.ukuran+"'>";
                                kontainerDiv.appendChild(paragrafBaru);
                                $('#inkodeol').val('');
                                $('#inukrol').val('');
                            } else {
                                peringatan('Kode yang anda masukan beda konstruksi');
                            }
                        }
                    });
                } else {
                    peringatan('Anda perlu mengisi ukuran');
                } 
            } );

        const autoCompleteJS = new autoComplete({
            placeHolder: "Ketik & Pilih...",
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
        document.getElementById('id_username').value = ''+personName;
        
        
        function loadData(){
            $.ajax({
                url:"<?=base_url();?>users/loaddata",
                type: "POST",
                data: {"username" : personName, "proses" : "folfinish"},
                cache: false,
                success: function(dataResult){
                    $('#fortable').html(dataResult);
                }
            });
        }
        loadData();
        function simpanData(){
            var kons12 = document.getElementById('autoComplete').value;
            var ori = document.querySelectorAll('input[name="koder[]"]');
            const koder = [];
            ori.forEach(ori => {
                koder.push(ori.value);
            });
            var ori2 = document.querySelectorAll('input[name="ukra[]"]');
            const ukra = [];
            ori2.forEach(ori2 => {
                ukra.push(ori2.value);
            });
            var ori3 = document.querySelectorAll('input[name="st[]"]');
            const st = [];
            ori3.forEach(ori3 => {
                st.push(ori3.value);
            });
            console.log(''+koder);
            var tgl = document.getElementById('id_tgl').value;
            var ukuranJoin = document.getElementById('ukuranJoin').value;
            var username = document.getElementById('id_username').value;
            var koder1 = koder.toString();
            var st1 = st.toString();
            if(kons12!="" && username!="null"){
                $.ajax({
                    url:"<?=base_url();?>users2/prosesJoinPieces",
                    type: "POST",
                    data: {"koder" : koder1, "ukra" : ukra, "st":st1, "username":username, "tgl":tgl, "kons":kons12, "ukuranJoin":ukuranJoin},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                suksestoast('Berhasil join pieces');
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
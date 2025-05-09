<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Inspect Finish</title>
    <link rel="stylesheet" href="<?=base_url('new_db/');?>style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .box-masket {
            width: 100%;
            display: flex;
            flex-direction: column;
            padding :10px;
            border: 1px solid #000;
            border-radius:4px;
            margin:20px 0px 10px 0px;
            position: relative;
            min-height: 50px;
        }
        .box-masket .lbl {font-weight: bold; color:#cc0b08;background: #ebe8e8;position: absolute;top: -20px;left: 15px;padding:10px;}
        .notes {
            width: 100%;
            background: #ccc;
            color: #000;
            padding: 10px;
            font-size: 12px;
            margin:10px 0px;
        }
        .loadingspinner{--square:26px;--offset:30px;--duration:2.4s;--delay:0.2s;--timing-function:ease-in-out;--in-duration:0.4s;--in-delay:0.1s;--in-timing-function:ease-out;width:calc(3 * var(--offset) + var(--square));height:calc(2 * var(--offset) + var(--square));padding:0;margin-left:auto;margin-right:auto;margin-top:10px;margin-bottom:30px;position:relative}.loadingspinner div{display:inline-block;background:darkorange;border:none;border-radius:2px;width:var(--square);height:var(--square);position:absolute;padding:0;margin:0;font-size:6pt;color:#000}.loadingspinner #square1{left:calc(0 * var(--offset));top:calc(0 * var(--offset));animation:square1 var(--duration) var(--delay) var(--timing-function) infinite,squarefadein var(--in-duration) calc(1 * var(--in-delay)) var(--in-timing-function) both}.loadingspinner #square2{left:calc(0 * var(--offset));top:calc(1 * var(--offset));animation:square2 var(--duration) var(--delay) var(--timing-function) infinite,squarefadein var(--in-duration) calc(1 * var(--in-delay)) var(--in-timing-function) both}.loadingspinner #square3{left:calc(1 * var(--offset));top:calc(1 * var(--offset));animation:square3 var(--duration) var(--delay) var(--timing-function) infinite,squarefadein var(--in-duration) calc(2 * var(--in-delay)) var(--in-timing-function) both}.loadingspinner #square4{left:calc(2 * var(--offset));top:calc(1 * var(--offset));animation:square4 var(--duration) var(--delay) var(--timing-function) infinite,squarefadein var(--in-duration) calc(3 * var(--in-delay)) var(--in-timing-function) both}.loadingspinner #square5{left:calc(3 * var(--offset));top:calc(1 * var(--offset));animation:square5 var(--duration) var(--delay) var(--timing-function) infinite,squarefadein var(--in-duration) calc(4 * var(--in-delay)) var(--in-timing-function) both}@keyframes square1{0%{left:calc(0 * var(--offset));top:calc(0 * var(--offset))}8.333%{left:calc(0 * var(--offset));top:calc(1 * var(--offset))}100%{left:calc(0 * var(--offset));top:calc(1 * var(--offset))}}@keyframes square2{0%{left:calc(0 * var(--offset));top:calc(1 * var(--offset))}8.333%{left:calc(0 * var(--offset));top:calc(2 * var(--offset))}16.67%{left:calc(1 * var(--offset));top:calc(2 * var(--offset))}25.00%{left:calc(1 * var(--offset));top:calc(1 * var(--offset))}83.33%{left:calc(1 * var(--offset));top:calc(1 * var(--offset))}91.67%{left:calc(1 * var(--offset));top:calc(0 * var(--offset))}100%{left:calc(0 * var(--offset));top:calc(0 * var(--offset))}}@keyframes square3{0%,100%{left:calc(1 * var(--offset));top:calc(1 * var(--offset))}16.67%{left:calc(1 * var(--offset));top:calc(1 * var(--offset))}25.00%{left:calc(1 * var(--offset));top:calc(0 * var(--offset))}33.33%{left:calc(2 * var(--offset));top:calc(0 * var(--offset))}41.67%{left:calc(2 * var(--offset));top:calc(1 * var(--offset))}66.67%{left:calc(2 * var(--offset));top:calc(1 * var(--offset))}75.00%{left:calc(2 * var(--offset));top:calc(2 * var(--offset))}83.33%{left:calc(1 * var(--offset));top:calc(2 * var(--offset))}91.67%{left:calc(1 * var(--offset));top:calc(1 * var(--offset))}}@keyframes square4{0%{left:calc(2 * var(--offset));top:calc(1 * var(--offset))}33.33%{left:calc(2 * var(--offset));top:calc(1 * var(--offset))}41.67%{left:calc(2 * var(--offset));top:calc(2 * var(--offset))}50.00%{left:calc(3 * var(--offset));top:calc(2 * var(--offset))}58.33%{left:calc(3 * var(--offset));top:calc(1 * var(--offset))}100%{left:calc(3 * var(--offset));top:calc(1 * var(--offset))}}@keyframes square5{0%{left:calc(3 * var(--offset));top:calc(1 * var(--offset))}50.00%{left:calc(3 * var(--offset));top:calc(1 * var(--offset))}58.33%{left:calc(3 * var(--offset));top:calc(0 * var(--offset))}66.67%{left:calc(2 * var(--offset));top:calc(0 * var(--offset))}75.00%{left:calc(2 * var(--offset));top:calc(1 * var(--offset))}100%{left:calc(2 * var(--offset));top:calc(1 * var(--offset))}}@keyframes squarefadein{0%{transform:scale(.75);opacity:0}100%{transform:scale(1);opacity:1}}
        .buttonData {
            width: 50%;
            background:#fff;
            color:#403d3d;
            font-weight:bold;
            display:flex;
            justify-content:center;
            padding:10px;
            border-radius:3px;
        }
        .buttonData.active {
            background:#0b53b8;
            color:#ffffff;
        }
        .buttonData.out {
            width: 100%;
            background:#d10404;
            color:#ffffff;
            margin-top:20px;
        }
        .isi {
            width: 100%;
            display: flex;
            flex-direction: column;
            margin-top:15px;
        }
        .table-container{max-width:100%;overflow-x:auto;border:1px solid #ddd;border-radius:8px;box-shadow:0 4px 8px rgba(0,0,0,0.1);background-color:#fff}
        table{width:100%;border-collapse:collapse;min-width:600px;text-align:left}
        th,td{padding:12px 15px;white-space:nowrap;border:1px solid #ddd}
        thead{background-color:#007bff;color:#fff}
        tbody tr:nth-child(odd){background-color:#f9f9f9}
        tbody tr:nth-child(even){background-color:#fff}
        tbody tr:hover{background-color:#007bff;color:#fff;transition:background-color 0.3s ease,color 0.3s ease}
        th{position:sticky;top:0;z-index:1}
        @media(max-width:600px){table{font-size:14px}
        th,td{padding:10px}}
        .swal-title {font-size: 16px; } .swal-text {font-size: 12px;}
        .spbwen {
            width: 100%;
            display: flex;
            justify-content: space-between;
            gap:10px;
        }
    </style>
</head>
<body style="background: #ebe8e8;">
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
    <h1>Timbang Data BS</h1>
    <div class="container">
        <small><?=$newToday;?>, <strong><?=$prinTgl;?></strong> Operator : <strong id="nmoptid">Nur Hikmah</strong></small>
        <small style="margin-top:5px;">Halaman ini menampilkan data BS yang anda timbang hari ini.</small>
    </div>
    <input type="hidden" name="allid" id="allid" value="null">
    <input type="hidden" name="allkons" id="allkons" value="null">
    <div class="container" id="contenerId">
        <div class="box-masket">
            <label for="dataBS" class="lbl">Timbang Data BS (Hasil Inspect)</label>
            <select name="dataBStipe" id="dataBStipe" style="padding:10px;border-radius:3px;border:1px solid #0965ed;margin-top:20px;background:#fff;">
                <option value="">Pilih Grey/Finish</option>
                <option value="grey">Grey</option>
                <option value="finish">Finish</option>
            </select>
            <div class="spbwen" style="margin-top:10px;">
                <input type="tel" name="dataBS" id="idDataBS" style="width:75%;padding:10px;border-radius:3px;border:1px solid #0965ed;" placeholder="Masukan Kode Roll">
                <button id="cekData" style="width:25%;outline:none;border:none;padding:7px;background:#0b53b8;color:#fff;border-radius:3px;">Tambahkan</button>
            </div>
            <div id="tambahan" style="margin-top:10px;font-size:11px;"></div>
            <div class="spbwen" style="margin-top:10px;">
                <input type="tel" name="dataBS2" id="dataBS2" style="width:75%;padding:10px;border-radius:3px;border:1px solid #0965ed;" placeholder="Masukan Total Berat">
                <button id="simpanData" style="width:25%;outline:none;border:none;padding:7px;background:#30b504;color:#fff;border-radius:3px;">Simpan</button>
            </div>
        </div>
        <div class="box-masket">
            <label for="dataBS" class="lbl">Timbang Data BS (Stok Lama)</label>
            <div style="width:100%;display:flex;justify-content:space-between;align-items:center;">
                <span style="width:30%;">Konstruksi</span>
                <select name="dataBStipe2" id="dataBStipe2" style="width:70%;padding:10px;border-radius:3px;border:1px solid #0965ed;margin-top:20px;background:#fff;">
                    <option value="">Pilih Konstruksi</option>
                    <option value="BS L 120 GREY">BS L 120 GREY</option>
                    <option value="BS L 135 GREY">BS L 135 GREY</option>
                    <option value="BS L 150 GREY">BS L 150 GREY</option>
                    <option value="BS L 90 GREY">BS L 90 GREY</option>
                    <option value="BS MAKLOON">BS MAKLOON</option>
                    <option value="AVAL GREY">AVAL GREY</option>
                    <option value="BS L 120">BS L 120</option>
                    <option value="BS L 135">BS L 135</option>
                    <option value="BS L 150">BS L 150</option>
                    <option value="BS L 90">BS L 90</option>
                    <option value="AVAL PUTIHAN">AVAL PUTIHAN</option>
                </select>
            </div>
            <div class="spbwen" style="margin-top:10px;">
                <input type="tel" name="pjg" id="pjgid" style="width:75%;padding:10px;border-radius:3px;border:1px solid #0965ed;" placeholder="Masukan Panjang Roll">
                <input type="tel" name="brt" id="brtid" style="width:75%;padding:10px;border-radius:3px;border:1px solid #0965ed;" placeholder="Masukan Berat">
            </div>
            <div class="spbwen" style="margin-top:10px;">
                <button id="simpanData2" style="width:100%;outline:none;border:none;padding:7px;background:#30b504;color:#fff;border-radius:3px;">Simpan</button>
            </div>
        </div>
        <div class="box-masket" id="divIsiData">
            <label for="dataBS" class="lbl">Timbang Hari Ini</label>
            <br>
            <p>Tidak ada data timbangan hari ini.</p>
        </div>
        
        <div class="buttonData out" id="btnBack">&laquo; Kembali ke Data BS</div>
        
        
    </div>
    <input type="hidden" id="id_username">
    
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let personName = sessionStorage.getItem("userName");
        let restrictedNames = ["edi","nina", "pendi", "syafiq", "riziq", "arif", "wisnu","Wisnu"];
        // Cek apakah personName ada dalam array restrictedNames
        if (restrictedNames.includes(personName)) {
            console.log("Selamat datang, " + personName);
            document.getElementById('id_username').value = ''+personName;
        } else {
            $('#contenerId').html('Anda tidak memiliki Akses ke halaman ini!!!');
            document.getElementById('id_username').value = 'null';
        }
        document.getElementById('nmoptid').innerHTML = ''+personName;
        function hariIni(ini){
            $.ajax({
                url:"<?=base_url();?>nusers/loadTimbangan",
                type: "POST",
                data: {"username" : personName, "ini" : ini},
                cache: false,
                success: function(dataResult){
                    $('#divIsiData').html(dataResult);
                }
            });
        }
        hariIni('null');
        var btnBack = document.getElementById("btnBack");
        var cekData = document.getElementById("cekData");
        var simpanData = document.getElementById("simpanData");
        var simpanData2 = document.getElementById("simpanData2");
        //console.log('tes1241');
        btnBack.addEventListener("click", function() {
            document.location.href = "<?=base_url();?>nusers/databs";
        });
        simpanData2.addEventListener("click", function() {
            var kons = document.getElementById("dataBStipe2").value;
            var pjg = document.getElementById('pjgid').value;
            var brt = document.getElementById('brtid').value;
            if(kons!="" && pjg!="" && brt!=""){
                $.ajax({
                    url:"<?=base_url();?>nusers/simpanisidata2",
                    type: "POST",
                    data: {"username" : personName, "kons" : kons,"pjg":pjg,"brt":brt},
                    cache: false,
                    success: function(dataResult){
                        var data = JSON.parse(dataResult);
                        if(data.statusCode==200){
                            Swal.fire('','Berhasil Menyimpan Data','success');
                            hariIni('null');
                            $('#dataBStipe2').val('');
                            $('#pjgid').val('');
                            $('#brtid').val('');
                        } else {
                            Swal.fire('','Gagal Menyimpan Data','warning');
                        }
                    }
                });
            } else {
                Swal.fire('','Data tidak boleh kosong','warning');
            }
        });
        simpanData.addEventListener("click", function() {
            var allid = document.getElementById("allid").value;
            var allkons = document.getElementById('allkons').value;
            var berat = document.getElementById('dataBS2').value;
            var dataBStipe = document.getElementById('dataBStipe').value;
            if(allid!="null" && allkons!="null"){
                if(parseFloat(berat)>0){
                    console.log('berat :'+berat);
                    console.log('allid :'+allid);
                    console.log('allkons :'+allkons);
                    $.ajax({
                        url:"<?=base_url();?>nusers/simpanisidata",
                        type: "POST",
                        data: {"username" : personName, "id" : allid,"allkons":allkons,"berat":berat,"dataBStipe":dataBStipe},
                        cache: false,
                        success: function(dataResult){
                            var data = JSON.parse(dataResult);
                            if(data.statusCode==200){
                                Swal.fire('','Berhasil Menyimpan Data','success');
                                hariIni('null');
                                $('#idDataBS').val('');
                                $('#dataBS2').val('');
                                $('#allid').val('null');
                                $('#allkons').val('null');
                                $('#tambahan').html('');
                            } else {
                                Swal.fire('','Gagal Menyimpan Data','error');
                            }
                        }
                    });
                } else {
                    Swal.fire('','Anda harus mengisi berat','error');
                }
            } else {
                Swal.fire('','Anda harus mengisi kode roll dan konstruksi','error');
            }
        });
        cekData.addEventListener("click", function() {
            var dataBS = document.getElementById("idDataBS").value;
            var idsudah = document.getElementById('allid').value;
            if(dataBS==""){
                Swal.fire('Anda harus mengisi kode roll');
            } else {
                var dataBStipe = document.getElementById("dataBStipe").value;
                //var dataBStipe = document.getElementById("dataBStipe").value;
                $.ajax({
                    url:"<?=base_url();?>nusers/carikode",
                    type: "POST",
                    data: {"username" : personName, "dataBS" : dataBS, "dataBStipe":dataBStipe},
                    cache: false,
                    success: function(dataResult){
                        var data = JSON.parse(dataResult);
                        if(data.statusCode==200){
                            var konsNow = document.getElementById('allkons').value;
                            if(konsNow=='null'){
                                document.getElementById('allkons').value = ''+data.konstruksi;
                                Swal.fire('Kode ditemukan!', '', 'success');
                                if(idsudah=="null"){
                                    document.getElementById('allid').value = ''+data.kode+'';
                                } else {
                                    document.getElementById('allid').value = idsudah+'-'+data.kode+'';
                                }
                                document.getElementById('idDataBS').value = '';
                                loadFunc();
                            } else {
                                if(konsNow == data.konstruksi){
                                    Swal.fire('Kode ditemukan!', '', 'success');
                                    if(idsudah=="null"){
                                        document.getElementById('allid').value = ''+data.kode+'';
                                    } else {
                                        document.getElementById('allid').value = idsudah+'-'+data.kode+'';
                                    }
                                    document.getElementById('idDataBS').value = '';
                                    loadFunc();
                                } else {
                                    Swal.fire('Kode Roll Tidak Sesuai Konstruksinya.!!','','error');
                                }
                            }
                        } else {
                            Swal.fire(''+data.psn+'');
                        }
                    }
                });
            }
        });
        function loadFunc(){
            var idsudah = document.getElementById('allid').value;
            let array = idsudah.split("-");
            let hasil = [];
            array.forEach((item) => {
                if (!hasil.includes(item)) {
                hasil.push(item);
                }
            });
            var hasilakhir = hasil.join("-");
            document.getElementById('allid').value = hasilakhir;
            var idsudah = document.getElementById('allid').value;
            var dataBStipe = document.getElementById("dataBStipe").value;
            $('#tambahan').html('');
            if(idsudah!="null"){
                $.ajax({
                    url:"<?=base_url();?>nusers/loaddataisi",
                    type: "POST",
                    data: {"username" : personName, "id" : idsudah,"dataBStipe":dataBStipe},
                    cache: false,
                    success: function(dataResult){
                        $('#tambahan').html(dataResult);
                    }
                });
            } else {
                $('#tambahan').html('');
            }
        }
        function hapusKode(id,tipe,mtr){
            Swal.fire({
                title: "Hapus ?",
                text: "Hapus Kode "+id+" dengan panjang "+mtr+" meter?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let input = document.getElementById('allid').value;
                        let array = input.split("-");
                        let filteredArray = array.filter(item => item != id);
                        var news = filteredArray.join("-");
                        if(news==""){
                            document.getElementById('allid').value = 'null';
                            document.getElementById('allkons').value = 'null';
                        } else {
                            document.getElementById('allid').value = ''+news;
                        }
                        loadFunc();
                    }
            });
        }
        function deldata(id){
            Swal.fire({
                title: "Hapus ?",
                text: "Hapus Data Dengan Kode "+id+" ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:"<?=base_url();?>nusers/delisiData",
                            type: "POST",
                            data: {"username" : personName, "id" : id},
                            cache: false,
                            success: function(dataResult){ hariIni('null'); }
                        });
                    }
            });
        }
    </script>
</body>
</html>
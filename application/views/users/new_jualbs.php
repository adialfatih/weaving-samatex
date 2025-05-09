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
        .buttonData.submit {
            width: 100%;
            background:#027d02;
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
        .konten {width: 100%;padding:10px;display:flex;flex-direction:column;align-items:center;}
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
        .input-container {
            width: 100%;
            display: flex;
        }

        /* Gaya untuk input teks */
        .text-input {
            width: 60%;
            padding: 10px; /* Padding dalam input */
            font-size: 16px; /* Ukuran teks */
            border: 1px solid #ccc; /* Border input */
            border-radius: 4px; /* Membuat sudut melengkung */
            margin-right: 10px; /* Memberikan jarak antara input dan tombol */
            outline: none; /* Menghilangkan outline default */
            transition: border-color 0.3s ease; /* Animasi smooth pada border */
        }

        .text-input:focus {
            border-color: #66afe9; /* Border warna biru saat fokus */
        }

        /* Gaya untuk tombol */
        .add-button {
            padding: 8px 16px; /* Ukuran tombol kecil */
            background-color: #28a745; /* Warna hijau */
            color: white; /* Teks putih */
            border: none; /* Menghilangkan border */
            border-radius: 4px; /* Membuat sudut melengkung */
            font-size: 14px; /* Ukuran teks */
            cursor: pointer; /* Menampilkan cursor pointer */
            transition: background-color 0.3s ease, transform 0.2s ease; /* Animasi smooth */
        }

        .add-button:hover {
            background-color: #218838; /* Warna hijau lebih gelap saat hover */
            transform: scale(1.05); /* Efek zoom kecil */
        }

        .add-button:active {
            background-color: #1e7e34; /* Warna hijau lebih gelap saat aktif */
            transform: scale(0.95); /* Efek zoom kecil saat klik */
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
    <h1>Penjualan BS</h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong> Operator : <strong id="nmoptid">Nur Hikmah</strong></small>
    <div class="konten">
        <div class="box-masket">
            <label class="lbl" for="DataBS">Buat Package</label>
            <div class="box-bs-bp">
                <div>
                    <label for="kons"><strong>Konstruksi</strong></label>
                    <select name="kons" id="kons" style="width: 100%;height: 30px;margin-top: 5px;">
                        <option value="">Pilih Konstruksi</option>
                        <?php
                        $_kons = $this->db->query("SELECT DISTINCT konstruksi FROM bs_stok_samatex ORDER BY konstruksi ASC")->result();
                        foreach($_kons as $val){
                            echo "<option value='".$val->konstruksi."'>".$val->konstruksi."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="tujuan"><strong>Tujuan</strong></label>
                    <input type="text" name="bp" id="tujuan" placeholder="Masukan Tujuan">
                </div>
            </div>
            <div class="buttonData submit" id="btnCreateNew" style="margin-top:10px;" onclick="buatPaket('new','new','new')">Buat Paket</div>
        </div>
        <div class="box-masket">
            <label class="lbl" for="DataBS">Package BS</label>
            <div id="paketBS" style="width:100%;margin-top:5px;">
                Loading...
            </div>
        </div>
    </div>
    <input type="hidden" id="id_username">
    
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let personName = sessionStorage.getItem("userName");
        let restrictedNames = ["edi","nina", "pendi", "syafiq", "riziq", "arif","wisnu","Wisnu"];
        // Cek apakah personName ada dalam array restrictedNames
        if (restrictedNames.includes(personName)) {
            console.log("Selamat datang, " + personName);
        } else {
            $('#contenerId').html('Anda tidak memiliki Akses ke halaman ini!!!');
        }
        document.getElementById('nmoptid').innerHTML = ''+personName;
        document.getElementById('id_username').value = ''+personName;
        function refresh(){
            kons = $('#kons').val('');
            tujuan = $('#tujuan').val('');
            loadData();
            $('#btnCreateNew').show();
        }
        function loadData(){
            $('#paketBS').html('<div style="margin-top:50px;"><div class="loadingspinner"><div id="square1"></div><div id="square2"></div><div id="square3"></div><div id="square4"></div><div id="square5"></div></div></div>');
            $.ajax({
                url:"<?=base_url();?>nusers/loadAllPaket",
                type: "POST",
                data: {"username" : "id"},
                cache: false,
                success: function(dataResult){
                    setTimeout(() => { 
                        $('#paketBS').html(dataResult);
                    }, "1200");
                }
            });
        }
        loadData();
        function buatPaket(tipe,newkons,newtujuan){
            var kons = $('#kons').val();
            var tujuan = $('#tujuan').val();
            if(kons!="" && tujuan!=""){
                $('#paketBS').html('<div class="loadingspinner"><div id="square1"></div><div id="square2"></div><div id="square3"></div><div id="square4"></div><div id="square5"></div></div>');
                $.ajax({
                    url:"<?=base_url();?>nusers/createPaket",
                    type: "POST",
                    data: {"kons" : kons, "tujuan" : tujuan, "personName":personName, "tipe":tipe, "newkons":newkons,"newtujuan":newtujuan},
                    cache: false,
                    success: function(dataResult){
                        $('#btnCreateNew').hide();
                        setTimeout(() => { 
                            $('#paketBS').html(dataResult);
                        }, "1200");
                    }
                });
            } else {
                Swal.fire({
                    title: "Warning!",
                    text: "Anda perlu mengisi konstruksi dan tujuan",
                    icon: "warning",
                    customClass: {
                        text: 'swal-text'
                    }
                });
            }
        }
        function tambahkan(){
            var id = $('#angkaKode').val();
            var kd = $('#hurufKode').val();
            var pkt = $('#kodePaket').val();
            if(id!=""){
                $.ajax({
                    url:"<?=base_url();?>nusers/addkodeToPaket",
                    type: "POST",
                    data: {"id" : id, "kd" : kd, "pkt" : pkt},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            loadPaket(pkt);
                            Swal.fire('Berhasil', 'Kode berhasil ditambahkan', 'success');
                            $('#angkaKode').val('');
                            
                        } else {
                            Swal.fire(dataResult.psn);
                            $('#angkaKode').val('');
                            
                        }
                    }
                });
            } else {
                Swal.fire('Anda harus mengisi kode roll');
            }
        }
        function loadPaket(pkt){
            //$('#idPaketNew').html('Load Data...');
            $.ajax({
                url:"<?=base_url();?>nusers/loadPaket",
                type: "POST",
                data: {"pkt" : pkt},
                cache: false,
                success: function(dataResult){
                    $('#idPaketNew').html(dataResult);
                }
            });
        }
        function showAllData(pkt,kons,tuj){
            var kons2 = $('#kons').val(''+kons);
            var tujuan2 = $('#tujuan').val(''+tuj);
            buatPaket(pkt,kons,tuj);
            setTimeout(() => { 
                loadPaket(pkt);
                $('#kodePaket').val(''+pkt);
            }, "1300");
            console.log(pkt);
        }
        function delbs(id,kd,mtr){
            Swal.fire({
            title: "Hapus "+kd+" ?",
            text: "Anda akan menghapus BS dengan panjang "+mtr+" meter.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya. Hapus!",
            customClass: {
                title: 'swal-title',
                text: 'swal-text'
            }
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url:"<?=base_url();?>nusers/deldata",
                    type: "POST",
                    data: {"kd" : kd},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            Swal.fire({
                                title: "Berhasil",
                                text: "Data berhasil dihapus.",
                                icon: "success",
                                customClass: {
                                    title: 'swal-title',
                                    text: 'swal-text'
                                }
                            }).then((result) => {
                                loadData(dataResult.psn);
                            });
                        } 
                    }
                });
            }
            });
        }
        function DeletePaket(id){
            $.ajax({
                url:"<?=base_url();?>nusers/delPaket",
                type: "POST",
                data: {"id" : id},
                cache: false,
                success: function(dataResult){
                    var dataResult = JSON.parse(dataResult);
                    if(dataResult.statusCode==200){
                        refresh();
                    } else {
                        Swal.fire('Gagal Hapus',dataResult.psn,'error');
                    }
                }
            });
        }
        function kembalikanRoll(id,tipe,pkt){
            $.ajax({
                url:"<?=base_url();?>nusers/backPaket",
                type: "POST",
                data: {"id" : id, "tipe":tipe, "pkt":pkt},
                cache: false,
                success: function(dataResult){
                    loadPaket(pkt);
                    console.log(dataResult);
                }
            });
        }
    </script>
</body>
</html>
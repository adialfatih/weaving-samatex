<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Ke RJS</title>
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
    <h1>Terima dari RJS</h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong>, Username : <strong id="nmoptid">Nur Hikmah</strong></small>
    <br>
    
    <div class="container">
        
        <div class="kotaknewpkg">
            <span>Masukan Packing List</span>
                <div class="autoComplete_wrapper">
                    <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off">
                    <input type="hidden" id="id_username" value="0">
                    <input type="hidden" id="id_tgl" value="<?=$tgl;?>">
                </div>
                
        </div>
        
        
        <div class="kotaknewpkg">
            <span>Roll diterima</span>
            <div class="fortable has2" style="min-height:700px;">
                <table id="output" style="min-height:700px;">
                    
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
        function delpkg(kd){
            $.ajax({
                url:"<?=base_url();?>users/hapusKirimanPusatex",
                type: "POST",
                data: {"kd" : kd},
                cache: false,
                success: function(dataResult){
                    loadDataStokKiriman();
                    loadDataStokKiriman89();
                }
            });
        }
        document.getElementById("autoComplete").addEventListener("change", function() {
            const id = document.getElementById("autoComplete").value.trim();
            if (id === "") {
                Swal.fire("Masukkan ID terlebih dahulu!");
                return;
            }

            const apiUrl = `https://inspect.rdgjt.com/api/inspect?id=${id}`; // Ganti dengan URL API yang sesuai

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.status && data.jumlahData > 0) {
                        let outputHtml = `<tr><td colspan="4">PACKING LIST : <strong>${id}</strong></td></tr>`;
                        
                        data.data.forEach(item => {
                            var tsh = item.kode;
                            outputHtml += `
                                <tr>
                                    <td>${item.kode}</td>
                                    <td>${item.ukuran}</td>
                                    <td>${item.konstruksi}</td>
                                    <td><input type="checkbox" onclick="addPkg('${item.kode}','${item.kd}',this.checked)"></td>
                                </tr>
                            `;
                            
                        });
                        outputHtml += "";
                        document.getElementById("output").innerHTML = outputHtml;
                    } else {
                        document.getElementById("output").innerHTML = "<tr><td>Data tidak ditemukan.</td></tr>";
                    }
                })
                .catch(error => {
                    console.error("Error mengambil data:", error);
                    document.getElementById("output").innerHTML = "<tr><td>Terjadi kesalahan saat mengambil data.</td></tr>";
                });
        });
        function addPkg(koderoll,kdpkg,isChecked) {
            //const apiUrl = "http://localhost/ci3/api/get_data/2"; 
            const apiUrl = `https://inspect.rdgjt.com/api/folding?id=${koderoll}`;
            if (isChecked) {
                //Swal.fire('kode : '+koderoll+' CENTANG : '+kdpkg);
                var inti = "tambah";
            } else {
                //Swal.fire('kode : '+koderoll+' TIDAK CENTANG : '+kdpkg);
                var inti = "hapus";
            }
            fetch(apiUrl)
                .then(response => response.json()) // Ubah response ke JSON
                .then(data => {
                    if (data.status) {
                        // Simpan data ke variabel global
                        apiData = data;
                        console.log("Data API berhasil diambil:", apiData);
                        processData(apiData, inti);
                    } else {
                        console.error("Gagal mengambil data dari API");
                    }
                })
                .catch(error => console.error("Error fetching data:", error));
        }
        function processData(data, inti) {
            if (data.jumlahData > 0) {
                const item = data.data[0]; // Ambil item pertama dari array
                console.log("ID Fold:", item.id_fol);
                console.log("Kode Roll:", item.kode_roll);
                console.log("Konstruksi:", item.konstruksi);
                console.log("Ukuran:", item.ukuran);
                console.log("Jenis Fold:", item.jns_fold);
                console.log("Tanggal:", item.tgl);
                console.log("Operator:", item.operator);
                console.log("Lokasi:", item.loc);
                console.log("Posisi:", item.posisi);
                console.log("Join Status:", item.joinss);
                console.log("Joined From:", item.joindfrom);
                var idFol = item.id_fol;
                var kodeRoll = item.kode_roll;
                var konstruksi = item.konstruksi;
                var ukuran = item.ukuran;
                var jenisFold = item.jns_fold;
                var tanggal = item.tgl;
                var operator = item.operator;
                var lokasi = item.loc;
                var posisi = item.posisi;
                var joinStatus = item.joinss;
                var joinedFrom = item.joindfrom;
                $.ajax({
                    url:"<?=base_url();?>users/simpanDariRindang",
                    type: "POST",
                    data: {"inti" : inti, "idFol":idFol,"kodeRoll":kodeRoll,"konstruksi":konstruksi,"ukuran":ukuran,"jenisFold":jenisFold,"tanggal":tanggal,"operator":operator,"lokasi":lokasi,"posisi":posisi,"joinStatus":joinStatus,"joinedFrom":joinedFrom},
                    cache: false,
                    success: function(dataResult){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil di '+inti
                        });
                    }
                });
            } else {
                console.log("Tidak ada data ditemukan.");
            }
        }

        
        const autoCompleteJS = new autoComplete({
            placeHolder: "Masukan Kode Paket",
            data: {
                src: [],
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
        $( "#btn-logout" ).on( "click", function() {
            window.location.href = "<?=base_url('users/');?>";
        });
        
            
    </script>
</body>
</html>
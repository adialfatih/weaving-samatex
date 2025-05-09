<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Stok Grey ke Pusatex</title>
    <link rel="stylesheet" href="<?=base_url('new_db/');?>style.css?versi=3">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .autoComplete_wrapper input{
            width: 95%;
            transform: translateX(10px);
        }
        #myTable tr td, #myTable tr th {
            padding:5px;
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
    <h1>Kirim Stok Grey ke Pusatex</h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong>, Username : <strong id="nmoptid">Nur Hikmah</strong></small>
    <div class="container">
        
        <div class="kotaknewpkg">
            <span>Data Stok</span>
            <div class="fortable has2">
                <table id="tableStok">
                    <tr>
                        <td colspan="2">Loading...</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="kotaknewpkg98">
            <span>Buat Paket Ke Pusatex</span>
            <div class="newpkg" >
                <div class="autoComplete_wrapper">
                    <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off">
                    <input type="hidden" id="id_username" value="0">
                    <input type="hidden" id="id_tgl" value="<?=$tgl;?>">
                </div>
                <button id="createPkg">Cek Kode</button>
            </div>
            <input type="text" placeholder="Masukan Kode Roll" class="iptnewoke" id="idKoderoll">
            <input type="hidden" id="kodeid" value="">
            <input type="hidden" id="ukuranid" value="">
            <table id="myTable" border="1" style="border-collapse:collapse;width:100%;margin-top:10px;border:1px solid #ccc;">
                <thead>
                    <tr>
                        <th>Kode Roll</th>
                        <th>Ukuran</th>
                        <!-- Tambahkan header lainnya sesuai kebutuhan -->
                    </tr>
                </thead>
                <tbody>
                    <!-- Isi tabel disini jika diperlukan -->
                </tbody>
            </table>
            
        </div>
        <button class="btn-save" style="background:#blue;color:#fff;border:1px solid #FFF;" id="btn-send">Kirim </button>
        <button class="btn-save" style="background:#ccc;color:#000;border:1px solid #FFF;" id="btn-back" onclick="history.back()">Kembali</button>
        <button class="btn-save" style="background:red;border:1px solid #FFF;" id="btn-logout">Logout</button>
    </div>
    <?php
        $kons = $this->data_model->get_record('tb_konstruksi');
        $ar_kons = array();
        foreach($kons->result() as $val){
            //echo $val->kode_konstruksi."<br>";
            $ar_kons[] = '"'.$val->kode_konstruksi.'"';
        }
        $im_kons = implode(',', $ar_kons);
        //jika yusuf keluar finish
        //jiki rizik keluar grey
    ?>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $( "#btn-logout" ).on( "click", function() {
            window.location.href = "<?=base_url('users/');?>";
        });
        let personName = sessionStorage.getItem("userName");
        console.log(''+personName);
        if(personName == "edi" || personName == "riziq"){
            $('#btn-greys').show();
            $( "#btn-greys" ).on( "click", function() {
                window.location.href = "<?=base_url('users/greykefinish');?>";
            });
        } else {
            $('#btn-greys').hide();
        }
        document.getElementById('nmoptid').innerHTML = ''+personName;
        document.getElementById('id_username').value = ''+personName;
        // function loadData(){
        //     $.ajax({
        //         url:"<=base_url();?>users2/loadpenjualan",
        //         type: "POST",
        //         data: {"username" : personName},
        //         cache: false,
        //         success: function(dataResult){
        //             $('#idtable').html(dataResult);
        //         }
        //     });
        // }
        // loadData();
        function loadDataStok(){
            $.ajax({
                url:"<?=base_url();?>users2/loadDataStok",
                type: "POST",
                data: {"username" : personName},
                cache: false,
                success: function(dataResult){
                    $('#tableStok').html(dataResult);
                }
            });
        }
        loadDataStok();
        const autoCompleteJS = new autoComplete({
            placeHolder: "Ketik Konstruksi...",
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
        $( "#createPkg" ).on( "click", function() {
            var idkons = $('#autoComplete').val();             
            var kdroll = $('#idKoderoll').val();
            var txt_kode = $('#kodeid').val();
            var txt_ukuran = $('#ukuranid').val();
            if(idkons == "" && kdroll == ""){
                peringatan('Anda harus mengisi konstruksi dan kode roll');
            } else {
                if(idkons!="" && kdroll!=""){
                    //suksestoast('okebos');
                    $('#createPkg').html('Loading');
                    $.ajax({
                        url:"<?=base_url();?>users/cekkoderollss",
                        type: "POST",
                        data: {"kons" : idkons, "kode" : kdroll, "txt_kode" : txt_kode, "txt_ukuran" : txt_ukuran},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                var table = document.getElementById("myTable");
                                var newRow = document.createElement("tr");
                                var cell1 = newRow.insertCell(0);
                                var cell2 = newRow.insertCell(1);
                                newRow.id = "s"+dataResult.kode_roll+"";
                                cell1.innerHTML = "<font onclick=\"delss('"+dataResult.kode_roll+"')\">" + dataResult.kode_roll+"</font>";
                                cell2.innerHTML = ""+dataResult.ukuran+"";
                                table.appendChild(newRow);
                                $('#kodeid').val(''+dataResult.in_kode);
                                $('#ukuranid').val(''+dataResult.in_ukr);
                                suksestoast('Menambahkan '+kdroll+'');
                                $('#createPkg').html('Cek Kode');
                                $('#idKoderoll').val('');
                                $('#btn-send').html('Kirim '+dataResult.jmlRoll+' Roll, Total '+dataResult.total_array+'');
                                
                            } else {
                                peringatan(''+dataResult.psn);
                                $('#createPkg').html('Cek Kode');
                            }

                        }
                    });
                } else {
                    if(idkons==""){ peringatan('Anda harus mengisi konstruksi'); }
                    if(kdroll==""){ peringatan('Anda harus mengisi kode roll'); }
                }
            }
        });
        $('#btn-send').on( "click", function() {
            var data1 = $('#kodeid').val();
            var data2 = $('#ukuranid').val();
            var data3 = $('#autoComplete').val();
            var en_data1 = btoa(data1);
            var en_data2 = btoa(data2);
            var en_data3 = btoa(data3);
            console.log('data 1 = '+en_data1);
            console.log('data 2 = '+en_data2);
            window.location.href = "<?=base_url('usersporses/greykefinish/');?>"+en_data1+"/"+en_data2+"/"+en_data3+"";
        });
        
        function delss(kode){
            console.log(''+kode);
            // var txt_kode = $('#kodeid').val();
            // var txt_ukuran = $('#ukuranid').val();
            // $.ajax({
            //     url:"<?=base_url();?>users/delkoderollss",
            //     type: "POST",
            //     data: {"kode" : kode, "txt_kode" : txt_kode, "txt_ukuran" : txt_ukuran},
            //     cache: false,
            //     success: function(dataResult){
            //         var dataResult = JSON.parse(dataResult);
            //         $('#s'+kode+'').hide();
            //         $('#kodeid').val(''+dataResult.in);
            //         $('#ukuranid').val(''+dataResult.in2);
            //     }
            // });
            
        }
            function peringatan(txt) {
                Toastify({
                    text: ""+txt+"",
                    duration: 4000,
                    close:true,
                    gravity:"top",
                    position: "center",
                    backgroundColor: "#cc214e",
                }).showToast();
            }
            function suksestoast(txt){
                Toastify({
                    text: ""+txt+"",
                    duration: 5000,
                    close:true,
                    gravity:"top",
                    position: "center",
                    backgroundColor: "#4fbe87",
                }).showToast();
            } 
            setTimeout(() => {
                loadDataStok();
            }, 60000);
    </script>
</body>
</html>
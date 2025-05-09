<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packinglist Ke Pusatex</title>
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
        $kd = $this->uri->segment(3);
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
    <h1>Hapus Stok Folding</h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong>, Username : <strong id="nmoptid">Nur Hikmah</strong></small>
    
    <div class="container">
        <div class="form-label">
            <small>Anda dapat menghapus Stok Folding dihalaman ini</small>
        </div>
        <div class="form-label" style="margin-top:20px;">
            <label for="autoComplete">Konstruksi</label>
            <div class="autoComplete_wrapper">
                <input id="autoComplete" type="search"  dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" onchange="cekstok()">
            </div>
            <!-- <input type="text" class="ipt" name="kons" id="kons" autofocus> -->
            <input type="hidden" id="id_username" value="0">
            <input type="hidden" id="id_tgl" value="<?=$tgl;?>">
        </div>
        <div class="form-label">
            <label for="folid">Jenis Folding</label>
            <select class="ipt" name="folid" id="folid" onchange="cekstok()">
                <option value="">Pilih</option>
                <option value="Finish">Folding Finish</option>
                <option value="Grey">Folding Grey</option>
            </select>
        </div>
        <div class="form-label">
            <label for="alasan">Alasan dihapus</label>
            <textarea name="alasan" id="alasan" cols="30" rows="3" class="ipt" placeholder="Masukan Alasan di hapus"></textarea>
        </div>
        <div class="form-label">
            <label for="hpsall">Hapus Semua ?</label>
            <select class="ipt" name="hpsall" id="hpsall">
                <option value="">Pilih</option>
                <option value="y">Hapus Semua Stok</option>
                <option value="n">Pilih Kode Untuk dihapus</option>
            </select>
        </div>
        <div class="form-label">
            <label for="sendpst">Kirim Pusatex ?</label>
            <select class="ipt" name="sendpst" id="sendpst">
                <option value="">Pilih</option>
                <option value="y">Ya</option>
                <option value="n">Tidak</option>
            </select>
        </div>
        <div class="fortable2">
        <table style="font-size:13px;margin-bottom:10px;" id="tablePaket">
            <tr>
                <td><strong>Konstruksi</strong></td>
                <td><strong>Jumlah Roll</strong></td>
                <td><strong>Total Ukuran</strong></td>
            </tr>
            <tr>
                <td id="id2kons">--</td>
                <td id="id2jml">--</td>
                <td id="id2ukr">--</td>
            </tr>
        </table>
        <span style="font-size:14px;color:red;" id="notifsd"></span>
        </div>
        <div style="display:flex;" id="btnBox1">
        <a style="background:red;color:#FFFFFF;width:105px;display:flex;justify-content:center;align-items:center;margin-top:15px;font-size:12px;padding:5px 0;border-radius:7px;" onclick="history.back()">Batal</a>
        <a style="background:green;color:#FFFFFF;width:105px;display:flex;justify-content:center;align-items:center;margin-top:15px;font-size:12px;padding:5px 0;border-radius:7px;margin-left:5px;" onclick="simpanoke()">Simpan</a>
        </div>
        <br>
        
    </div>
    
    <?php
        $kdrol = $this->db->query("SELECT kode_konstruksi FROM `tb_konstruksi`");
        //$kdrol = $this->data_model->get_byid('data_ig', ['konstruksi'=>$kons, 'loc_now'=>'Samatex']);
        $ar_kdrol = array();
        foreach($kdrol->result() as $val){
            //echo $val->kode_konstruksi."<br>";
            $ar_kdrol[] = '"'.$val->kode_konstruksi.'"';
        }
        $im_kons = implode(',', $ar_kdrol);
    ?>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        
        var kons = $('#id_kons').val();
        var pkg = $('#id_pkg').val();
        const autoCompleteJS = new autoComplete({
            placeHolder: "Masukan Konstruksi",
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
                        //suksestoast(''+selection);
                        // $.ajax({
                        //     url:"<=base_url();?>users2/inputstok",
                        //     type: "POST",
                        //     data: {"selection" : selection, "kons" : kons, "pkg" : pkg},
                        //     cache: false,
                        //     success: function(dataResult){
                        //         var dataResult = JSON.parse(dataResult);
                        //         if(dataResult.statusCode==200){
                        //             suksestoast('Add '+selection+'');
                        //             autoCompleteJS.input.value = '';
                        //             loadisi();
                        //         } else {
                        //             peringatan(''+dataResult.psn+'');
                        //         }
                        //     }
                        // });
                    }
                }
            }
        });
        let personName = sessionStorage.getItem("userName");
        document.getElementById('nmoptid').innerHTML = ''+personName;
        document.getElementById('id_username').value = ''+personName;
        function cekstok(){
            var kons = $('#autoComplete').val();
            var jns = $('#folid').val();
            if(kons==''){
                peringatan('Masukan Konstruksi');
            }
            if(jns==''){
                peringatan('Pilih Jenis Folding');
            }
            if(kons!='' && jns!=''){
                $.ajax({
                    url:"<?=base_url();?>users2/cekstokfolding",
                    type: "POST",
                    data: {"kons" : kons, "jns" : jns},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            $('#id2kons').html(kons);
                            $('#id2jml').html(dataResult.jmlroll);
                            $('#id2ukr').html(dataResult.totalpanjang);
                            $('#notifsd').html('Roll di dalam packinglist tidak di hitung');
                        } else {
                            peringatan(''+dataResult.psn+'');
                            $('#notifsd').html('');
                            $('#id2kons').html('');
                            $('#id2jml').html('');
                            $('#id2ukr').html('');
                        }
                    }
                });
            }
        }
        function simpanoke(){
            var kons = $('#autoComplete').val();
            var jns = $('#folid').val();
            var alasan = $('#alasan').val();
            var hpsall = $('#hpsall').val();
            var sendpst = $('#sendpst').val();
            var id_username = $('#id_username').val();
            var id_tgl = $('#id_tgl').val();
            if(kons!='' && jns!="" && alasan!="" && hpsall!="" && sendpst!="" && id_username!="" && id_tgl!=""){
                $.ajax({
                    url:"<?=base_url();?>users2/prosdelfol",
                    type: "POST",
                    data: {"kons" : kons, "jns" : jns, "alasan" : alasan, "hpsall" : hpsall, "sendpst" : sendpst, "id_username" : id_username, "id_tgl" : id_tgl},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            window.location.href = "<?=base_url('users/delfolding/');?>"+dataResult.kodeHapus+"";
                        } else {
                            peringatan('error');
                        }
                    }
                });
            } else {
                peringatan('Form harus di isi semua');
            }
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
            function loadisi(){
                $.ajax({
                    url:"<?=base_url();?>users2/loadisipkg",
                    type: "POST",
                    data: {"pkg" : pkg},
                    cache: false,
                    success: function(dataResult){
                        $('#tablePaket').html(dataResult);
                    }
                });
            }
    </script>
</body>
</html>
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
        $cekdt = $this->data_model->get_byid('a_hps_stok', ['kode_hapus'=>$kd])->row_array();
        $hpsall = $cekdt['hpsall'];
        $inkons = $cekdt['konstruksi'];
        $injns = $cekdt['jns'];
        $inusr = $cekdt['yg_hapus'];
        $inkrm = $cekdt['krm_pstx'];
        if($hpsall == "y"){
            $cekroll = $this->db->query("SELECT * FROM `data_fol` WHERE konstruksi='$inkons' AND jns_fold='$injns' AND posisi='Samatex'");
            foreach($cekroll->result() as $kl){
                $klkd = $kl->kode_roll;
                $klukr= $kl->ukuran;
                $cekdiig = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$klkd'");
                if($cekdiig->num_rows()==0){
                    $this->data_model->saved('data_ig', [
                        'kode_roll' => $klkd,
                        'konstruksi' => $inkons,
                        'no_mesin' => 'null',
                        'no_beam' => 'null',
                        'oka' => 'null',
                        'ukuran_ori' => $klukr,
                        'ukuran_bs' => 0,
                        'ukuran_bp' => 0,
                        'tanggal' => $tgl,
                        'operator' => $inusr,
                        'bp_can_join' => 'false',
                        'dep' => 'Samatex',
                        'loc_now' => 'Samatex',
                        'yg_input' => $inusr,
                        'kode_upload' => 'null',
                        'shift_op' => 'null',
                        'input_from' => 'app',
                        'kode_potongan' => 'null'
                    ]);
                    //echo $klkd." -> NO<br>";
                } else {
                    $this->data_model->updatedata('kode_roll',$klkd,'data_ig',['ukuran_ori'=>$klukr]);
                    //echo $klkd." -> ".$cekdiig->num_rows()."<br>";
                }
                $cekhpscode = $this->data_model->get_byid('a_hps_code', ['kode_roll'=>$klkd])->num_rows();
                if($cekhpscode == 0){
                    $this->data_model->saved('a_hps_code', [
                        'jns_fold' => $injns,
                        'kode_roll' => $klkd,
                        'ukuran' => $klukr,
                        'kons' => $inkons,
                        'tgl_hps' => $tgl,
                        'kode_hapus' => $kd
                    ]);
                
                    if($injns == "Grey"){
                        $stoknow = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex', 'kode_konstruksi'=>$inkons])->row_array();
                        $stokSekarang = $stoknow['prod_fg'] - $klukr;
                        $this->data_model->updatedata('idstok',$stoknow['idstok'],'data_stok',['prod_fg'=>round($stokSekarang,2)]);
                    } else {
                        $stoknow = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex', 'kode_konstruksi'=>$inkons])->row_array();
                        $stokSekarang = $stoknow['prod_ff'] - $klukr;
                        $this->data_model->updatedata('idstok',$stoknow['idstok'],'data_stok',['prod_ff'=>round($stokSekarang,2)]);
                    }
                }
                $this->db->query("DELETE FROM data_fol WHERE kode_roll='$klkd'");
                if($inkrm=="y"){ $this->data_model->updatedata('kode_roll',$klkd,'data_ig',['loc_now'=>'Samatex']); }
            } //end forach
        }
    ?>
    <h1>Hapus Stok Folding</h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong>, Username : <strong id="nmoptid">Nur Hikmah</strong></small>
    
    <div class="container">
        <div class="form-label">
            <small>Masukan kode yang akan di hapus</small>
        </div>
        <div class="form-label" style="margin-top:20px;">
            <label for="autoComplete">Kode Roll</label>
            <div class="autoComplete_wrapper">
                <input id="autoComplete" type="search"  dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" >
            </div>
            <!-- <input type="text" class="ipt" name="kons" id="kons" autofocus> -->
            <input type="hidden" id="id_username" value="0">
            <input type="hidden" id="id_tgl" value="<?=$tgl;?>">
        </div>
        <input type="hidden" id="id2kons" value="<?=$cekdt['konstruksi'];?>">
        <input type="hidden" id="id2jns" value="<?=$cekdt['jns'];?>">
        <input type="hidden" id="id2kode" value="<?=$kd;?>">
        <div class="fortable2">
        <table style="font-size:13px;margin-bottom:10px;" id="tablePaket">
            <tr>
                <td><strong>No</strong></td>
                <td><strong>Kode Roll</strong></td>
                <td><strong>Konstruksi</strong></td>
                <td><strong>Ukuran</strong></td>
                <td><strong>Batal</strong></td>
            </tr>
            <tr>
                <td colspan="5">Loading...</td>
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
        $k1 = $cekdt['konstruksi'];
        $k2 = $cekdt['jns'];
        $kdrol = $this->db->query("SELECT * FROM `data_fol` WHERE konstruksi='$k1' AND jns_fold='$k2' AND posisi='Samatex' ");
        //$kdrol = $this->data_model->get_byid('data_ig', ['konstruksi'=>$kons, 'loc_now'=>'Samatex']);
        $ar_kdrol = array();
        foreach($kdrol->result() as $val){
            //echo $val->kode_konstruksi."<br>";
            $ar_kdrol[] = '"'.$val->kode_roll.'"';
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
            placeHolder: "Masukan Kode Roll",
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
        function reloadkode(){
            var kd = $('#id2kode').val();
            if(kd!=''){
                $.ajax({
                    url:"<?=base_url();?>users2/reloadfol",
                    type: "POST",
                    data: {"kd" : kd},
                    cache: false,
                    success: function(dataResult){
                        $('#tablePaket').html(dataResult);
                    }
                });
            } else {
                peringatan('Form harus di isi semua');
            }
        }
        reloadkode();
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
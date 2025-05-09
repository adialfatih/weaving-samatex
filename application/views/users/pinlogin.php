<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pin Login User</title>
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
    
    <h1>PIN Login Operator</h1>
    
    <div class="container">
        <div class="form-label">
            <label for="autoComplete">Operator</label>
            <div class="autoComplete_wrapper">
                <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off">
            </div>
        </div>
        <div class="form-label">
            <label for="mc">PIN</label>
            <input type="password" class="ipt" name="mc" id="pin1" placeholder="Masukan PIN">
        </div>
        <div class="form-label">
            <label for="beam">PIN</label>
            <input type="password" class="ipt" name="beam" id="pin2" placeholder="Masukan Ulang">
        </div>
        
        <a href="javascript:void(0);" id="btnSelesai" class="btn-save2" onclick="simpanData()">Simpan</a>
        <div style="margin-top:10px;" id="notices">
            <span>Tes id peringatan</span>
        </div>
    </div>
    <?php
        $kons = $this->data_model->get_record('a_operator');
        $ar_kons = array();
        foreach($kons->result() as $val){
            //echo $val->kode_konstruksi."<br>";
            $ar_kons[] = '"'.$val->username.'"';
        }
        
        $im_kons = implode(',', $ar_kons);
        
    ?>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const autoCompleteJS = new autoComplete({
            placeHolder: "Pilih Operator...",
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
        function simpanData(){
            document.getElementById('btnSelesai').innerHTML = 'Loading..';
            var user = document.getElementById('autoComplete').value;
            var pin1 = document.getElementById('pin1').value;
            var pin2 = document.getElementById('pin2').value;

            if(user!="" && pin1!="" && pin2!=""){
                $.ajax({
                    url:"<?=base_url();?>users/updatepin",
                    type: "POST",
                    data: {"user" : user, "pin1" : pin1, "pin2" : pin2 },
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                $('#notices').html('<span style="color:green;">Update PIN berhasil</span>');
                                document.getElementById('autoComplete').value = '';
                                document.getElementById('pin1').value = '';
                                document.getElementById('pin2').value = '';
                                document.getElementById('btnSelesai').innerHTML = 'Simpan';
                            } else {
                                
                                $('#notices').html('<span style="color:red;">Isi data dengan benar</span>');
                                document.getElementById('btnSelesai').innerHTML = 'Simpan';
                                document.getElementById('autoComplete').value = '';
                                document.getElementById('pin1').value = '';
                                document.getElementById('pin2').value = '';
                            }
                        }
                });
            } else {
                $('#notices').html('<span style="color:red;">Isi data dengan benar</span>');
                document.getElementById('btnSelesai').innerHTML = 'Simpan';
                document.getElementById('autoComplete').value = '';
                document.getElementById('pin1').value = '';
                document.getElementById('pin2').value = '';
            }
        } // end proses simpan
    </script>
</body>
</html>
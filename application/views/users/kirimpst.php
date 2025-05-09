<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Ke Pusatex</title>
    <link rel="stylesheet" href="<?=base_url('new_db/');?>style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" integrity="sha256-ZCK10swXv9CN059AmZf9UzWpJS34XvilDMJ79K+WOgc=" crossorigin="anonymous">
    <style>
        .autoComplete_wrapper input{
            width: 95%;
            transform: translateX(10px);
        }
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
        .hamburger {
        cursor: pointer;
        }

        .hamburger input {
        display: none;
        }

        .hamburger svg {
        /* The size of the SVG defines the overall size */
        height: 3em;
        /* Define the transition for transforming the SVG */
        transition: transform 600ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        .line {
        fill: none;
        stroke: #4287f5;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-width: 3;
        /* Define the transition for transforming the Stroke */
        transition: stroke-dasharray 600ms cubic-bezier(0.4, 0, 0.2, 1),
                    stroke-dashoffset 600ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        .line-top-bottom {
        stroke-dasharray: 12 63;
        }

        .hamburger input:checked + svg {
        transform: rotate(-45deg);
        }

        .hamburger input:checked + svg .line-top-bottom {
        stroke-dasharray: 20 300;
        stroke-dashoffset: -32.42;
        }
        .mblmenu {
            width: 100%;
            height:100vh;
            position: absolute;
            top: 0;
            left: 0;
            background:#fff;
            position: fixed;
            z-index: 999;
            padding:100px 20px 0px 20px;
            transition: all 0.5s ease;
            transform:translateX(100%);
        }
        .mblmenu.active {
            transform:translateX(0);
        }
        .mblmenu a {text-decoration:none;}
        .isimenu {
            width: 100%;
            padding:15px 10px;
            background:#e3e3e3;
            margin-bottom:10px;
            color:#000;
        }
        .isimenu:hover {
            background:#4287f5;
            color:#fff;
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
    <div style="width:100%;display:flex;justify-content:space-between;align-items:center;padding:10px;z-index:9999;">
        <div style="display:flex;flex-direction:column;">
            <h1>Kirim ke Pusatex</h1>
            <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong>, Username : <strong id="nmoptid">Nur Hikmah</strong></small>
        </div>
        <label class="hamburger">
            <input type="checkbox" id="hambmenuid">
            <svg viewBox="0 0 32 32">
                <path class="line line-top-bottom" d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22"></path>
                <path class="line" d="M7 16 27 16"></path>
            </svg>
        </label>
    </div>
    <div class="mblmenu">
        <a href="<?=base_url('users/terimafrompst');?>">
            <div class="isimenu">Terima Barang dari Pusatex</div>
        </a>
        <a href="<?=base_url('users/terimafromrjs');?>">
            <div class="isimenu">Terima Barang dari RJS</div>
        </a>
        <a href="<?=base_url('users/terimafromrjs2');?>">
            <div class="isimenu">Data Stok dari RJS</div>
        </a>
        <a href="<?=base_url('fake/fakepkglistall');?>">
            <div class="isimenu">Fake Packinglist</div>
        </a>
        <a href="<?=base_url('fake/fakepkglist');?>">
            <div class="isimenu">Buat Fake Packinglist</div>
        </a>
        <a href="<?=base_url('users/delstok');?>">
            <div class="isimenu">Hapus Stok Folding</div>
        </a>
        <a href="<?=base_url('users/editkoderoll');?>">
            <div class="isimenu">Edit Kode Roll</div>
        </a>
        <a href="<?=base_url('stok/sinkronstok');?>">
            <div class="isimenu" style="color:red;">SINKRONKAN STOK FOLDING GREY</div>
        </a>
        <a href="<?=base_url('stok/sinkronstok2');?>">
            <div class="isimenu" style="color:red;">SINKRONKAN STOK FOLDING FINISH</div>
        </a>
        <a href="<?=base_url('users/');?>">
            <div class="isimenu">Logout</div>
        </a>
    </div>
    
    <div class="container">
        <div class="kotaknewpkg">
            <span>Buat Paket Baru</span>
            <div class="newpkg" >
                <div class="autoComplete_wrapper">
                    <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off">
                    <input type="hidden" id="id_username" value="0">
                    <input type="hidden" id="id_tgl" value="<?=$tgl;?>">
                </div>
                <button id="createPkg">Buat Paket</button>
            </div>
            
        </div>
        <div class="kotaknewpkg">
            <span>Paket Belum Kirim</span>
            <div class="fortable has" style="font-size: 13px;">
                <table id="idtable">
                    <tr><td>Loading...</td></tr>
                </table>
            </div>
        </div>
        <div class="kotaknewpkg">
            <span>Kiriman ke Pusatex</span>
            <div class="fortable has" style="font-size: 13px;">
                <table id="idtable_owek">
                    <tr><td>Loading...</td></tr>
                </table>
            </div>
        </div>
        <div class="kotaknewpkg">
            <span>Paket Kiriman dari Rindang</span>
            <div class="fortable has2">
                <table id="tableStokKiriman" style="min-width:100%;">
                    <tr>
                        <td>No PKG</td>
                        <td>Tanggal</td>
                        <td>Dari</td>
                        <td>Panjang</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="kotaknewpkg">
            <span>Stok di Samatex</span>
            <div class="fortable has2">
                <table id="tableStok">
                    <tr>
                        <td colspan="2">Loading...</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="kotaknewpkg">
            <span>Stok di Pusatex</span>
            <div class="fortable has2 skeleton" id="tableStokPstLoad">
                <table id="tableStokPst">
                    <tr>
                        <td colspan="2">Loading...</td>
                    </tr>
                </table>
            </div>
        </div>
        
        
    </div>
    <?php
        $kons = $this->data_model->get_record('tb_konstruksi');
        $ar_kons = array();
        foreach($kons->result() as $val){
            $ar_kons[] = '"'.$val->kode_konstruksi.'"';
        }
        $im_kons = implode(',', $ar_kons);
        //jika yusuf keluar finish
        //jiki rizik keluar grey
    ?>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
    <script>
        let personName = sessionStorage.getItem("userName");
        document.getElementById('nmoptid').innerHTML = ''+personName;
        document.getElementById('id_username').value = ''+personName;
        function loadData(){
            $.ajax({
                url:"<?=base_url();?>users/loadpaketpst",
                type: "POST",
                data: {"username" : personName},
                cache: false,
                success: function(dataResult){
                    $('#idtable').html(dataResult);
                }
            });
        }
        loadData();
        function loadData2(){
            //url sebemnya stok di ganti uses
            $.ajax({
                url:"<?=base_url();?>stok/loadpaketpst23",
                type: "POST",
                data: {"username" : personName},
                cache: false,
                success: function(dataResult){
                    $('#idtable_owek').html(dataResult);
                }
            });
        }
        loadData2();
        function loadDataStok(){
            $.ajax({
                url:"<?=base_url();?>users/loadDataStokGrey",
                type: "POST",
                data: {"username" : personName},
                cache: false,
                success: function(dataResult){
                    $('#tableStok').html(dataResult);
                }
            });
        }
        loadDataStok();
        function loadDataStokKiriman(){
            $.ajax({
                url:"<?=base_url();?>users/loadDataStokGreyKiriman",
                type: "POST",
                data: {"username" : personName},
                cache: false,
                success: function(dataResult){
                    $('#tableStokKiriman').html(dataResult);
                }
            });
        }
        loadDataStokKiriman();
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
            suksestoast('Loading...');
            var kodekons = document.getElementById('autoComplete').value;
            var username = document.getElementById('id_username').value;
            var tgl = document.getElementById('id_tgl').value;
            if(kodekons!="" && username!="null" && tgl!=""){
                $.ajax({
                    url:"<?=base_url();?>users2/prosesCreatepkg2",
                    type: "POST",
                    data: {"kodekons" : kodekons, "tgl":tgl, "username":username, "topst":"topstoke"},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                setTimeout(() => {
                                    window.location.href = "<?=base_url('users/createkirimpst/');?>"+dataResult.psn+"";
                                }, "1300");
                            }
                        }
                });
            } else {
                peringatan('Anda perlu mengisi kode konstruksi');
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
            function loadDataStokPusatex(){
            $.ajax({
                    url:"<?=base_url();?>users/loadDataStokGreyInPusatex",
                    type: "POST",
                    data: {"username" : personName},
                    cache: false,
                    success: function(dataResult){
                        $('#tableStokPst').html(dataResult);
                        $('#tableStokPstLoad').removeClass('skeleton');
                    }
                });
            }
            loadDataStokPusatex();
            setTimeout(() => {
                loadDataStok();
            }, 60000);
            $('#hambmenuid').on('change', function() {
                $('.mblmenu').toggleClass('active');
            });
            function kliksj(sj,id){
                $.ajax({
                    url:"<?=base_url();?>stok/loadsj",
                    type: "POST",
                    data: {"sj" : sj},
                    cache: false,
                    success: function(dataResult){
                        Swal.fire({
                            title: 'SJ : '+sj,
                            html: ``+dataResult+``,
                            showCloseButton: true,
                            showCancelButton: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "<?=base_url('stok/showsj/');?>"+id+"";
                            }
                        });
                    }
                });
            
                
            }
            function klikkonstruksi(kd){
                Swal.fire({
                    title: 'Konstruksi : '+kd,
                    html: ``+kd+``
                });
            }
    </script>
</body>
</html>
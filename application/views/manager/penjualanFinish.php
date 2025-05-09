<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan Kain Finish</title>
    <link rel="stylesheet" href="<?=base_url();?>new_db/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Vithkuqi&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .autoComplete_wrapper input{
            width: 110%;
            transform: translateX(-10%);
        }
        .lds-ring {
        display: inline-block;
        position: relative;
        width: 20px;
        height: 20px;
        }
        .lds-ring div {
        box-sizing: border-box;
        display: block;
        position: absolute;
        width: 24px;
        height: 24px;
        margin: 8px;
        border: 8px solid #ccc;
        border-radius: 50%;
        animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        border-color: #ccc transparent transparent transparent;
        }
        .lds-ring div:nth-child(1) {
        animation-delay: -0.45s;
        }
        .lds-ring div:nth-child(2) {
        animation-delay: -0.3s;
        }
        .lds-ring div:nth-child(3) {
        animation-delay: -0.15s;
        }
        @keyframes lds-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
        }

            
    </style>
</head>
<body>
    <div class="topbar">
       Penjualan Kain Finish
    </div>
    <div class="konten-mobile2">
        <div class="kotaknewpkg">
            <span>Filter Penjualan</span>
            <div style="width: 100%;display: flex;flex-direction: column;">
                <div class="form-label">
                    <label for="mc">Tanggal</label>
                    <input type="text" class="ipt" name="dates" id="tglid">
                </div>
                <div class="form-label">
                    <label for="autoComplete">Konsumen</label>
                    <div class="autoComplete_wrapper">
                        <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off">
                    </div>
                </div>
                <div style="display:none;" id="owekloading">
                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                </div>
            </div>
        </div>
        <!-- <div class="kotaknewpkg">
            <span>Data Penjualan</span> -->
            <div class="fortable2" id="fortable" style="margin-bottom: 25px; font-size:12px;">
                <table>
                    <tr>
                        <td><strong>No</strong></td>
                        <td><strong>Konsumen</strong></td>
                        <td><strong>Konstruksi</strong></td>
                        <td><strong>Jumlah</strong></td>
                    </tr>
                    <tr>
                        <td colspan="4">Loading...</td>
                    </tr>
                </table>
            </div>
        <!-- </div> -->
        <!-- <div style="width: 100%; height: 25px;"></div>
            <div class="card-kons">
                <div>SM03</div>
                <div>192.901</div>
            </div>
            <div class="card-kons">
                <div>SM03</div>
                <div>192.901</div>
            </div>
            <div class="card-kons">
                <div>SM03</div>
                <div>192.901</div>
            </div>
            <div class="card-kons">
                <div>SM03</div>
                <div>192.901</div>
            </div>
            <div class="card-kons">
                <div>SM03</div>
                <div>192.901</div>
            </div>
            <div class="card-kons">
                <div>SM03</div>
                <div>192.901</div>
            </div>
            <div class="card-kons">
                <div>SM03</div>
                <div>192.901</div>
            </div>
            <div class="card-kons">
                <div>SM03</div>
                <div>192.901</div>
            </div>
            <div class="card-kons">
                <div>SM03</div>
                <div>192.901</div>
            </div>
            <div class="card-kons">
                <div>SM03</div>
                <div>192.901</div>
            </div>       
            <div class="card-kons">
                <div>SM03</div>
                <div>192.901</div>
            </div>
            <div class="card-kons">
                <div>SM03</div>
                <div>192.901</div>
            </div>
            <div class="card-kons">
                <div>SM03</div>
                <div>192.901</div>
            </div>
            <div class="card-kons">
                <div>SM03</div>
                <div>192.901</div>
            </div>
            <div class="card-kons">
                <div>SM03</div>
                <div>192.901</div>
            </div>     -->
           
    </div>
    <?php
        $kons = $this->data_model->get_record('dt_konsumen');
        $ar_kons = array();
        foreach($kons->result() as $val){
            //echo $val->kode_konstruksi."<br>";
            $ar_kons[] = '"'.$val->nama_konsumen.'"';
        }
        
        $im_kons = implode(',', $ar_kons);
        
    ?>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $('input[name="dates"]').daterangepicker();
        const autoCompleteJS = new autoComplete({
            placeHolder: "Ketik Nama Konsumen...",
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
                        var tgl = $('#tglid').val();
                        loadData(tgl, selection);
                    }
                }
            }
        });
        var tgl = $('#tglid').val();
        var kons = $('#autoComplete').val();
        //console.log('tgl :'+tgl);
        //console.log('kons :'+kons);
        function loadData(tgl, kons){
            $('#owekloading').css({"display": "flex", "width": "100%", "justify-content": "center"});
            $.ajax({
                url:"<?=base_url();?>alldashboard/loaddata",
                type: "POST",
                data: {"tgl" : tgl, "kons" : kons, "jns" : "Finish"},
                cache: false,
                success: function(dataResult){
                    $('#fortable').html(dataResult);
                    $('#owekloading').hide();
                    //console.log('s'+tgl);
                }
            });
        }
        loadData(tgl, kons);
        $( "#tglid" ).on( "change", function() { 
            var tgl = $('#tglid').val();
            var kons = $('#autoComplete').val();
            console.log('tgl :'+tgl);
            console.log('kons :'+kons);
            loadData(tgl, kons);
        });
        $( "#autoComplete" ).on( "change", function() { 
            var tgl = $('#tglid').val();
            var kons = $('#autoComplete').val();
            console.log('tgl :'+tgl);
            console.log('kons :'+kons);
            loadData(tgl, kons);
        });
    </script>
</body>
</html>
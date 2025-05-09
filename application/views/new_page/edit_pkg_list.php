<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header d-print-none">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data Packinglist</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Data</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Packinglist</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Edit
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<?php 
                            $ex = explode('-', $dtkn['tanggal_dibuat']);
                            $printTgl = $ex[2]." ".$bln[$ex[1]]." ".$ex[0];
                            if($sess_dep=="RJS"){
                                $plc = "Ex: R1,R2,R3,R4,R5 / R1-R5";
                            } else {
                                $plc = "Ex: S1,S2,S3,S4,S5 / S1-S5";
                            }
							?>
					<!-- Simple Datatable start -->
                    <div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								<strong>Kode Packinglist (<strong><?=$token;?></strong>)</strong>
							</p><small>Packinglist di buat tanggal <strong><?=$printTgl;?></strong></small>
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                                <?php if($sess_dep=="RJS"){ ?>
                                <form action="<?=base_url('newpros/editpkg_rjs');?>" method="post" entype="multipart/form-data" name="f1">
                                <?php } else { ?>
                                <form action="<?=base_url('newpros/editpkg');?>" method="post" entype="multipart/form-data" name="f1">
                                <?php } ?>
                                <input type="hidden" name="token" value="<?=$token;?>">
                                <input type="hidden" value="0" name="jumlah_roll_all" id="jumlah_roll_all">
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Kode Konstruksi-<?=$sess_dep;?></label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:200px;" type="text" value="<?=$dtkn['kode_konstruksi'];?>" name="kdp" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Jenis Paket</label>
                                        <div class="col-sm-12 col-md-10">
                                            <select name="jnspkt" id="jnspkt" class="form-control" style="width:300px;" required>
                                                <option value="">Pilih Jenis Paket</option>
                                                <option value="y" <?=$dtkn['siap_jual']=="y"?'selected':'';?>>Siap Jual</option>
                                                <option value="n" <?=$dtkn['siap_jual']=="n"?'selected':'';?>>Sedang Proses</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Lokasi Paket</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:350px;" value="<?=$dtkn['lokasi_now'];?>" type="text" name="loc" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Total Ukuran</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:250px;" id="idUkuranTotal" value="<?=$dtkn['ttl_panjang'];?>" name="new_ttlpjg" type="text" readonly>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Data Roll</label>
                                        
                                    </div>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">Kode Roll</th>
                                            <th colspan="3" style="text-align:center;">Ukuran</th>
                                            <th rowspan="2" class="d-print-none">Action</th>
                                        </tr>
                                        <tr>
                                            <th>Inspect Grey</th>
                                            <th>Inspect Finish</th>
                                            <th>Folding</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                            if($dtkn['siap_jual']=='y'){
                                                $query = $this->data_model->get_byid('data_fol',['posisi'=>$token]);
                                            } else {
                                                $query = $this->data_model->get_byid('data_ig',['loc_now'=>$token]);
                                            }
                                            $ukuran_akhir = 0; $satuan_akhir = 0; $ukuran_akhir_total = 0;
                                            $numbering = 0;
                                            foreach($query->result() as $n => $val):
                                                $numbering+=1;
                                            if($dtkn['siap_jual']=="y"){
                                                $col1 = '';
                                                $col2 = '';
                                                $ukuran = $val->ukuran;
                                                if($val->jns_fold == "Grey"){$stuann="meter";}else {$stuann="yard";}
                                                $col3 = $val->ukuran." ".$stuann;
                                            } else {
                                                $ukuran = $val->ukuran_ori;
                                                $col1 = $val->ukuran_ori;
                                                $col2 = '';
                                                $col3 = '';
                                            }
                                            //$ukuran_akhir_total = $ukuran_akhir_total + floatval($ukuran_akhir);
                                        ?>
                                        <tr>
                                            <td><?=$numbering;?></td>
                                            <td><?=$val->kode_roll;?></td>
                                            <td><?=$col1;?></td>
                                            <td><?=$col2;?></td>
                                            <td><?=$col3;?></td>
                                            <td class="d-print-none">
                                                <input type="hidden" id="jumlahrow" value="<?=$query->num_rows();?>">
                                                <input type="hidden" value="<?=$ukuran;?>" id="ukuranRow<?=$n;?>">
                                                <input type="hidden" value="1" id="sertakanRow<?=$n;?>" name="sertakan[]">
                                                <input type="hidden" value="<?=$val->kode_roll;?>" name="roll[]">
                                                <input type="checkbox" name="kdroll[]" value="<?=$val->kode_roll;?>" style="width:20px;height:20px;cursor:pointer;" id="customCheck<?=$n;?>" onclick="uncek('<?=$n;?>')" checked />
                                            </td>
                                        </tr>
                                        <?php endforeach; 
                                        $cekfol_lama = $this->data_model->get_byid('data_fol_lama',['lokasi'=>$token]);
                                        if($cekfol_lama->num_rows() > 0){
                                            $nos = $n+2;
                                            $numbering+=1;
                                            foreach($cekfol_lama->result() as $nns => $cklm): ?>
                                        <tr>
                                            <td><?=$numbering;?></td>
                                            <td><?=$cklm->kode_roll;?></td>
                                            <td></td>
                                            <td></td>
                                            <td><?=$cklm->ukuran_asli;?><?=$cklm->folding=='Grey' ? ' meter':' yard';?></td>
                                            <td class="d-print-none">
                                                <input type="hidden" id="jumlahrow2" value="<?=$cekfol_lama->num_rows();?>">
                                                <input type="hidden" value="<?=$cklm->ukuran_asli;?>" id="ukuranRows22<?=$nns;?>">
                                                <input type="checkbox" name="kdroll2[]" value="<?=$val->kode_roll;?>" style="width:20px;height:20px;cursor:pointer;" id="customChecks<?=$nos;?>" onclick="uncek23('<?=$cklm->kode_roll;?>','<?=$token;?>')" checked />
                                            </td>
                                        </tr>
                                        <?php $nos++; $numbering++;
                                            endforeach;
                                        }
                                            $cek_folnocode = $this->data_model->get_byid('data_stok_lama',['posisi'=>$token]);
                                            if($cek_folnocode->num_rows() > 0){
                                                $nosz2 = $nos;
                                                $numbering+=1;
                                                foreach($cek_folnocode->result() as $ts => $nocode):
                                        ?>
                                        <tr>
                                            <td><?=$numbering;?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><?=$nocode->ukuran;?><?=$nocode->st_folding=='Grey' ? ' meter':' yard';?></td>
                                            <td class="d-print-none">
                                                <input type="hidden" id="jumlahrow3" value="<?=$cek_folnocode->num_rows();?>">
                                                <input type="hidden" value="<?=$nocode->ukuran;?>" id="ukuranRows33<?=$ts;?>">
                                                <input type="checkbox" name="kdroll3[]" value="" style="width:20px;height:20px;cursor:pointer;" id="customChecks<?=$nosz2;?>" onclick="uncek33('<?=$nocode->id_sl;?>','<?=$token;?>')" checked />
                                            </td>
                                        </tr>
                                        <?php
                                                    $nosz2++; $numbering++;
                                                endforeach;
                                            
                                            }
                                            $cek_on_isi = $this->data_model->get_byid('new_tb_isi', ['kd'=>$token, 'status!='=>'oke']);
                                            if($cek_on_isi->num_rows() > 0){
                                                $numbering+=1;
                                                foreach ($cek_on_isi->result() as $key => $value) {
                                                    ?>
                                        <tr>
                                            <td><?=$numbering;?></td>
                                            <td><?=$value->kode=="null" ? '':$value->kode;?></td>
                                            <td><?php if($dtkn['siap_jual']=="n"){ echo $value->ukuran." ".$value->satuan; }?></td>
                                            <td></td>
                                            <td><?php if($dtkn['siap_jual']=="y"){ echo $value->ukuran." ".$value->satuan; }?></td>
                                            <td class="d-print-none">
                                                <input type="hidden" id="jumlahrow4" value="<?=$cek_on_isi->num_rows();?>">
                                                <input type="hidden" value="<?=$value->ukuran;?>" id="ukuranRows44<?=$key;?>">
                                                <input type="checkbox" name="kdroll4[]" value="" style="width:20px;height:20px;cursor:pointer;" id="customChecks<?=$key;?>" onclick="uncek89('<?=$value->id_isi;?>','<?=$token;?>')" checked />
                                            </td>
                                        </tr>
                                                    <?php $numbering++;
                                                }
                                            }
                                        
                                        ?>
                                        <tbody>
                                    </table>
                                    <?php $dept = $this->session->userdata('departement'); 
                                    if($dept=="Samatex"){ ?>
                                    
                                        <button type="button" class="btn d-print-none" data-bgcolor="#046e16" data-color="#ffffff" data-toggle="modal" data-target="#Medium-modalnew1">
                                            <i class="icon-copy fa fa-plus-circle" aria-hidden="true"></i> &nbsp; Tambahkan Roll
                                        </button>
                                        <button type="button" class="btn d-print-none" data-bgcolor="#32a852" data-color="#ffffff" data-toggle="modal" data-target="#Medium-modal23r">
                                            <i class="icon-copy bi bi-cloud-upload"></i> &nbsp; Upload Excel
                                        </button>
                                    <?php } else { ?>
                                        <button type="button" class="btn d-print-none" data-bgcolor="#046e16" data-color="#ffffff" data-toggle="modal" data-target="#Medium-modal">
                                            <i class="icon-copy fa fa-plus-circle" aria-hidden="true"></i> &nbsp; Tambahkan Kode Roll
                                        </button>

                                        <!-- <button type="button" class="btn d-print-none" data-bgcolor="#eba40c" data-color="#ffffff" data-toggle="modal" data-target="#Medium-modal2s">
                                            <i class="icon-copy fa fa-plus-circle" aria-hidden="true"></i> &nbsp; Tambahkan Roll Tanpa Kode
                                        </button> -->
                                    <?php } ?>
                                        
                                    
                                        <button type="submit" class="btn d-print-none" data-bgcolor="#3f72d1" data-color="#ffffff">
                                            <span class="icon-copy ti-save"></span> &nbsp; Simpan
                                        </button>
                                </form>
                            </div>
						</div>
					</div>
					<!-- Bootstrap Select End -->
					<!-- pemisah modal -->
                                <div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Tambahkan Roll
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            <?php if($sess_dep=="Samatex"){ ?>
                                            <form name="fr1" action="<?=base_url('addroll/topackage');?>" method="post">
                                            <?php } else { ?>
                                            <form name="fr1" action="<?=base_url('proses/addroll2');?>" method="post">
                                            <?php } ?>
                                            <input type="hidden" name="siap_dol" value="<?=$dtkn['siap_jual'];?>">
											<div class="modal-body">
												<p>
													Anda dapat menambahkan Kode Roll dalam packinglist melalui form ini.
												</p>
                                                <table class="table">
                                                    <tr>
                                                        <td>Kode Packinglist</td>
                                                        <td><input type="text" name="kdlistId" id="kdlistId" value="<?=$token;?>" class="form-control" readonly></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td>Kode Roll</td>
                                                        <td><input type="hidden" name="txt2" class="form-control" placeholder="<?=$plc;?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <textarea name="txt" id="txt" cols="30" rows="4" class="form-control" placeholder="Example : S1000-S1001-S1002-R9090-R9091-JS9821-JS9822"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <small><strong>Note : </strong>Hati-hati tidak boleh ada spasi pada kolom pengisian kode roll</small>
                                                        </td>
                                                    </tr>
                                                </table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
												<button type="submit" class="btn btn-primary">
													SIMPAN
												</button>
											</div>
                                            </form>
										</div>
									</div>
								</div>
                                <!-- pemisah modal -->
                                <div class="modal fade" id="Medium-modal2s" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Tambahkan Roll
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            <?php if($sess_dep=="Samatex"){ ?>
                                            <form name="fr1" action="<?=base_url('addroll/topackage2');?>" method="post">
                                            <?php } else { ?>
                                            <form name="fr1" action="<?=base_url('proses/addroll');?>" method="post">
                                            <?php } ?>
                                            <input type="hidden" name="siap_dol" value="<?=$dtkn['siap_jual'];?>">
											<div class="modal-body">
												<p>
													Anda dapat menambahkan Roll tanpa kode dalam packinglist melalui form ini. Masukan ukuran secara spesifik dan gunakan titik untuk desimal.
												</p>
                                                <table class="table">
                                                    <tr>
                                                        <td>Kode Packinglist</td>
                                                        <td><input type="text" name="kdlistId" id="kdlistId" value="<?=$token;?>" class="form-control" readonly></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td>Ukuran Roll</td>
                                                        <td><input type="hidden" name="txt2" class="form-control" placeholder="<?=$plc;?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <textarea name="txt" id="txt" cols="30" rows="4" class="form-control" placeholder="Example : 192.98-100-123.05-200-50.25-198-145.93"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <small><strong>Note : </strong>Hati-hati tidak boleh ada spasi pada kolom pengisian ukuran roll</small>
                                                        </td>
                                                    </tr>
                                                </table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
												<button type="submit" class="btn btn-primary">
													SIMPAN
												</button>
											</div>
                                            </form>
										</div>
									</div>
								</div>
                                <!-- pemisah modal -->
                                <div class="modal fade" id="Medium-modal23r" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Upload Excel Packinglist
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            <?php echo form_open_multipart('impfol/pkgfromex',array('name' => 'spreadsheet')); ?>
                                            <input type="hidden" name="siap_dol" value="<?=$dtkn['siap_jual'];?>">
											<div class="modal-body">
												<p>
													Anda dapat menambahkan Roll dengan mengupload file excel, pastikan terdapat 2 kolom dalam file excel yakni <strong>kolom kode </strong>dan<strong> kolom ukuran</strong>.
												</p>
                                                <table class="table">
                                                    <tr>
                                                        <td>Kode Packinglist</td>
                                                        <td><input type="text" name="kdlistId" id="kdlistId23" value="<?=$token;?>" class="form-control" readonly></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Konstruksi</td>
                                                        <td><input type="text" name="kons2a" id="kons2a" value="<?=$dtkn['kode_konstruksi'];?>" class="form-control" readonly></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td>File</td>
                                                        <td><input type="file" name="upload_file" class="form-control"></td>
                                                    </tr>
                                                    
                                                </table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
												<button type="submit" class="btn btn-primary">
													SIMPAN
												</button>
											</div>
                                            <?php echo form_close();?>
										</div>
									</div>
								</div>
                                <!-- pemisah modal -->
                                <div class="modal fade" id="Medium-modalnew1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Tambah Roll
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            <?php echo form_open_multipart('impfol/pkgfromex2',array('name' => 'spreadsheet')); ?>
                                            <input type="hidden" name="siap_dol" value="<?=$dtkn['siap_jual'];?>">
                                            <input type="hidden" name="kdlistId" value="<?=$token;?>">
                                            <input type="hidden" name="kons2a"value="<?=$dtkn['kode_konstruksi'];?>">
											<div class="modal-body">
												
                                                <table class="table" id="owek98">
                                                    <tr>
                                                        <td>Satuan</td>
                                                        <td>
                                                            <select name="satuan" id="satuan" class="form-control" required>
                                                                <option value="">Pilih satuan</option>
                                                                <option value="Yard">Yard</option>
                                                                <option value="Meter">Meter</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Kode</td>
                                                        <td>Ukuran</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="text" name="kode[]" class="form-control"></td>
                                                        <td><input type="tel" name="ukuran[]" class="form-control" onchange="eventt()"></td>
                                                    </tr>
                                                    
                                                </table>
											</div>
											<div class="modal-footer">
                                                <input type="hidden" name="jmlrol" id="jmlrolid" value="1">
                                                <p>Jumlah total : <span id="spanidroll"></span> Roll  / <span id="spanidroll2"></span></p>
												<button type="button" class="btn btn-warning" onclick="tambahBaris()">Add Roll</button>
												<button type="submit" class="btn btn-primary">
													SIMPAN
												</button>
											</div>
                                            <?php echo form_close();?>
										</div>
									</div>
								</div>
        </div>
    </div>
</div>
<script>
    var an = document.getElementById('jumlahrow').value;
    var an2 = document.getElementById('jumlahrow2').value;
    var an3 = document.getElementById('jumlahrow3').value;
    var an4 = document.getElementById('jumlahrow4').value;
    var jumlah_semua = 0;
    for (let i = 0; i < an; i++) {
       var jumlah = document.getElementById('ukuranRow'+i+'').value;
       jumlah_semua = jumlah_semua + parseFloat(jumlah);
    }
    for (let ad = 0; ad < an2 ; ad++) {
       var jumlah = document.getElementById('ukuranRows22'+ad+'').value;
       jumlah_semua = jumlah_semua + parseFloat(jumlah);
    }
    for (let ad2 = 0; ad2 < an3 ; ad2++) {
       var jumlah = document.getElementById('ukuranRows33'+ad2+'').value;
       jumlah_semua = jumlah_semua + parseFloat(jumlah);
    }
    for (let ad3 = 0; ad3 < an4 ; ad3++) {
       var jumlah = document.getElementById('ukuranRows44'+ad3+'').value;
       jumlah_semua = jumlah_semua + parseFloat(jumlah);
    }
    document.getElementById('idUkuranTotal').value = ''+jumlah_semua;
    var totalRollAll = parseInt(an) + parseInt(an2) + parseInt(an3) + parseInt(an4);
    document.getElementById('jumlah_roll_all').value = ''+totalRollAll;

    function uncek(n){
        var inputAtas = document.getElementById('idUkuranTotal').value;
        var ygDiklik = document.getElementById('sertakanRow'+n+'').value;
        var ukuranDiklik = document.getElementById('ukuranRow'+n+'').value;
        if(ygDiklik==1){
            document.getElementById('sertakanRow'+n+'').value = '0';
            var newUkuran = parseFloat(inputAtas) - parseFloat(ukuranDiklik);
            document.getElementById('idUkuranTotal').value = ''+newUkuran;
        } else {
            document.getElementById('sertakanRow'+n+'').value = '1';
            var newUkuran = parseFloat(inputAtas) + parseFloat(ukuranDiklik);
            document.getElementById('idUkuranTotal').value = ''+newUkuran;
        }
    }
    function tambahBaris() {
        var roll = document.getElementById("jmlrolid").value;
        var jmlnewroll = parseInt(roll) + 1;
        document.getElementById('spanidroll').innerHTML = '<strong>'+jmlnewroll+'</strong>';
        document.getElementById("jmlrolid").value = ''+jmlnewroll;
        var table = document.getElementById("owek98");
        var newRow = document.createElement("tr");

        var cell1 = document.createElement("td");
        cell1.innerHTML = '<input type="text" name="kode[]" class="form-control">';
        newRow.appendChild(cell1);

        var cell2 = document.createElement("td");
        cell2.innerHTML = '<input type="tel" name="ukuran[]" class="form-control" onchange="eventt()"> ';
        newRow.appendChild(cell2);

        table.appendChild(newRow);
    }
    function eventt(){
        
        var inputs = document.getElementsByName("ukuran[]");
        var total = 0;

        for (var i = 0; i < inputs.length; i++) {
            var nilai = parseFloat(inputs[i].value);
            if (!isNaN(nilai)) {
            total += nilai;
            }
        }
        var roll = document.getElementById("jmlrolid").value;
        var satuan = document.getElementById("satuan").value;
        var newtotal = roundToTwo(total);
        document.getElementById('spanidroll2').innerHTML = '<strong>'+newtotal+'</strong>'+satuan+'';
    }
    function roundToTwo(num) {
        return +(Math.round(num + "e+2")  + "e-2");
    }
</script>
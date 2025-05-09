<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data List (Nomor Packinglist : <?=$kdp2;?>)</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="Javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="Javascript:void(0);">Data Produksi</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											List
										</li>
									</ol>
								</nav>
							</div>
							
						</div>
					</div>
					<!-- Simple Datatable start -->
                <form name="fr2" method="post" action="<?=base_url('newpros/up_pkginspect');?>" entype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                    <div class="page-header">
						<div class="row pd-20">
                                <?php 
                                    $pn = $prosesnext->row_array();
                                    $sendtgl = $pn['tgl'];
                                ?>
								<div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th colspan="2">Total Inspect</th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td colspan="4">Satuan Ukuran
                                                <select name="satuan" id="satuan" class="form-control" required>
                                                    <option value="">--Pilih Satuan--</option>
                                                    <option value="Yard" <?=$pn['satuan']=="Yard"?'selected':'';?>>Yard</option>
                                                    <option value="Meter" <?=$pn['satuan']=="Meter"?'selected':'';?>>Meter</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th colspan="2">Inpect Grey</th>
                                            <th colspan="3">Inspect Finish
                                                <input type="hidden" name="kode_produksi" value="<?=$kdp2;?>">
                                                <input type="hidden" name="tgl_produksi" value="<?=$pros_tgl;?>">
                                                <input type="hidden" name="lokasi_now" value="<?=$this->session->userdata('departement');?>">
                                            </th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            
                                            <th>No. Roll</th>
                                            <th>Ukuran </th>
                                            <th><i class="icon-copy bi bi-check2-circle"></i></th>
                                            <th>Ori</th>
                                            <th>A</th>
                                            <th>B</th>
                                            <th>C</th>
                                            <th>Ukuran BS</th>
                                            <th>Operator</th>
                                            <th></th>
                                        </tr><!-- <input type="hidden" name="id_produksi" value=""> -->
                                        </tr><input type="hidden" name="arleng" id="arleng" value="<?=$dt_roll->num_rows();?>">
                                        <?php $forulang = $dt_roll->num_rows(); 
                                        echo '<input type="hidden" id="forulangid" value="'.$forulang.'">';
                                        echo '<input type="hidden" id="fortglid" value="'.$sendtgl.'">';
                                            if($forulang == 0){
                                            ?>
                                                <tr>
                                                    <td>1.</td>
                                                    <td><input type="text" class="form-control" name="nomc[]"></td>
                                                    <td><input type="text" class="form-control" name="ukuran[]"></td>
                                                    <td><input type="date" class="form-control" name="fol_tgl[]"></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuran[]"></td>
                                                    <td><input type="text" class="form-control" name="fol_ukurana[]"></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranb[]"></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranc[]"></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranbs[]"></td>
                                                    <td><input type="text" class="form-control" name="fol_operator[]">
                                                        <input type="hidden" name="idpkg[]" value="">
                                                        <input type="hidden" name="pkglist[]" value="">
                                                        <input type="hidden" name="update[]" value="">
                                                    </td>
                                                </tr>
                                            <?php
                                        } else {
                                            
                                            foreach ($dt_roll->result() as $key => $value): ?>
                                                <tr>
                                                    <td><?=$key+1;?></td>
                                                    <td><input type="text" class="form-control" name="nomc[]" value="<?=$value->no_roll;?>" readonly></td>
                                                    <td><input type="text" class="form-control" name="ukuran[]" value="<?=$value->ukuran_now;?>" readonly></td>
                                                    <td>
                                                        <input type="hidden" class="form-control" name="fol_tgl[]" id="utm<?=$key;?>" <?=$value->ukuran_now==0 ? 'readonly':'';?>>
                                                        <?php if($value->ukuran_now!=0){ ?>
                                                        <div class="custom-control custom-checkbox mb-5">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck<?=$key;?>" /><label class="custom-control-label" for="customCheck<?=$key;?>" >&nbsp;</label>
                                                        </div>
                                                        <?php } ?>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="fol_ukuran[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_ukurana[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranb[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranc[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranbs[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_operator[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>>
                                                        <input type="hidden" name="idpkg[]" value="<?=$value->id_pkg;?>">
                                                    </td>
                                                    <td><a href="javascript:void(0);"><i class="icon-copy bi bi-plus-square-dotted" id="btn-<?=$key;?>"></i></a></td>
                                                </tr>
                                                <tr id="mncl-<?=$key;?>" style="display:none;">
                                                    <td></td>
                                                    <td><input type="text" class="form-control" name="nomc[]" value="<?=$value->no_roll;?>" readonly></td>
                                                    <td><input type="text" class="form-control" readonly></td>
                                                    <td>
                                                        <input type="hidden" class="form-control" name="fol_tgl[]" id="utms<?=$key;?>" <?=$value->ukuran_now==0 ? 'readonly':'';?>>
                                                        <?php if($value->ukuran_now!=0){ ?>
                                                        <div class="custom-control custom-checkbox mb-5">
                                                            <input type="checkbox" class="custom-control-input" id="customChecks<?=$key;?>" /><label class="custom-control-label" for="customChecks<?=$key;?>" >&nbsp;</label>
                                                        </div>
                                                        <?php } ?>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="fol_ukuran[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_ukurana[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranb[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranc[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranbs[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_operator[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>>
                                                        <input type="hidden" name="idpkg[]" value="<?=$value->id_pkg;?>">
                                                    </td>
                                                    <td><a href="javascript:void(0);"><i class="icon-copy bi bi-plus-square-dotted" id="btns-<?=$key;?>"></i></a></td>
                                                </tr>
                                                <tr id="mncls-<?=$key;?>" style="display:none;">
                                                    <td></td>
                                                    <td><input type="text" class="form-control" name="nomc[]" value="<?=$value->no_roll;?>" readonly></td>
                                                    <td><input type="text" class="form-control" readonly></td>
                                                    <td>
                                                        <input type="hidden" class="form-control" name="fol_tgl[]" id="utmss<?=$key;?>" <?=$value->ukuran_now==0 ? 'readonly':'';?>>
                                                        <?php if($value->ukuran_now!=0){ ?>
                                                        <div class="custom-control custom-checkbox mb-5">
                                                            <input type="checkbox" class="custom-control-input" id="customCheckss<?=$key;?>" /><label class="custom-control-label" for="customCheckss<?=$key;?>" >&nbsp;</label>
                                                        </div>
                                                        <?php } ?>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="fol_ukuran[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_ukurana[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranb[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranc[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranbs[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_operator[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>>
                                                        <input type="hidden" name="idpkg[]" value="<?=$value->id_pkg;?>">
                                                    </td>
                                                    <td><a href="javascript:void(0);"><i class="icon-copy bi bi-plus-square-dotted" id="btnss-<?=$key;?>"></i></a></td>
                                                </tr>
                                                <tr id="mnclss-<?=$key;?>" style="display:none;">
                                                    <td></td>
                                                    <td><input type="text" class="form-control" name="nomc[]" value="<?=$value->no_roll;?>" readonly></td>
                                                    <td><input type="text" class="form-control" readonly></td>
                                                    <td>
                                                        <input type="hidden" class="form-control" name="fol_tgl[]" id="utmsss<?=$key;?>" <?=$value->ukuran_now==0 ? 'readonly':'';?>>
                                                        <?php if($value->ukuran_now!=0){ ?>
                                                        <div class="custom-control custom-checkbox mb-5">
                                                            <input type="checkbox" class="custom-control-input" id="customChecksss<?=$key;?>" /><label class="custom-control-label" for="customChecksss<?=$key;?>" >&nbsp;</label>
                                                        </div>
                                                        <?php } ?>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="fol_ukuran[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_ukurana[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranb[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranc[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranbs[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>></td>
                                                    <td><input type="text" class="form-control" name="fol_operator[]" <?=$value->ukuran_now==0 ? 'readonly':'';?>>
                                                        <input type="hidden" name="idpkg[]" value="<?=$value->id_pkg;?>">
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                                <tr id="showsbtn1">
                                                    <td colspan="11">
                                                        <a href="javascript:void(0);" style="color:#3477eb;" id="clickbtn1">+ Tambahkan Roll diluar packing list</a>
                                                    </td>
                                                </tr>
                                                <tr id="shows1" style="display:none;">
                                                    <td><?=$key+2;?></td>
                                                    <td><input type="text" class="form-control" placeholder="No Roll" name="annorol[]"></td>
                                                    <td><input type="text" class="form-control" readonly></td>
                                                    <td><input type="date" class="form-control" name="antgl[]"></td>
                                                    <td colspan="2"><input type="text" class="form-control" name="anukuran[]" placeholder="Ukuran"></td>
                                                    <td colspan="5"></td>
                                                </tr>
                                                <tr id="showsbtn2" style="display:none;">
                                                    <td colspan="11">
                                                        <a href="javascript:void(0);" style="color:#3477eb;" id="clickbtn2">+ Tambahkan Roll diluar packing list</a>
                                                    </td>
                                                </tr>
                                                <tr id="shows2" style="display:none;">
                                                    <td><?=$key+3;?></td>
                                                    <td><input type="text" class="form-control" placeholder="No Roll" name="annorol[]"></td>
                                                    <td><input type="text" class="form-control" readonly></td>
                                                    <td><input type="date" class="form-control" name="antgl[]"></td>
                                                    <td colspan="2"><input type="text" class="form-control" name="anukuran[]" placeholder="Ukuran"></td>
                                                    <td colspan="5"></td>
                                                </tr>
                                                <tr id="showsbtn3" style="display:none;">
                                                    <td colspan="11">
                                                        <a href="javascript:void(0);" style="color:#3477eb;" id="clickbtn3">+ Tambahkan Roll diluar packing list</a>
                                                    </td>
                                                </tr>
                                                <tr id="shows3" style="display:none;">
                                                    <td><?=$key+4;?></td>
                                                    <td><input type="text" class="form-control" placeholder="No Roll" name="annorol[]"></td>
                                                    <td><input type="text" class="form-control" readonly></td>
                                                    <td><input type="date" class="form-control" name="antgl[]"></td>
                                                    <td colspan="2"><input type="text" class="form-control" name="anukuran[]" placeholder="Ukuran"></td>
                                                    <td colspan="5"></td>
                                                </tr>
                                                <tr id="showsbtn4" style="display:none;">
                                                    <td colspan="11">
                                                        <a href="javascript:void(0);" style="color:#3477eb;" id="clickbtn4">+ Tambahkan Roll diluar packing list</a>
                                                    </td>
                                                </tr>
                                                <tr id="shows4" style="display:none;">
                                                    <td><?=$key+5;?></td>
                                                    <td><input type="text" class="form-control" placeholder="No Roll" name="annorol[]"></td>
                                                    <td><input type="text" class="form-control" readonly></td>
                                                    <td><input type="date" class="form-control" name="antgl[]"></td>
                                                    <td colspan="2"><input type="text" class="form-control" name="anukuran[]" placeholder="Ukuran"></td>
                                                    <td colspan="5"></td>
                                                </tr>
                                                <tr id="showsbtn5" style="display:none;">
                                                    <td colspan="11">
                                                        <a href="javascript:void(0);" style="color:#3477eb;" id="clickbtn5">+ Tambahkan Roll diluar packing list</a>
                                                    </td>
                                                </tr>
                                                <tr id="shows5" style="display:none;">
                                                    <td><?=$key+6;?></td>
                                                    <td><input type="text" class="form-control" placeholder="No Roll" name="annorol[]"></td>
                                                    <td><input type="text" class="form-control" readonly></td>
                                                    <td><input type="date" class="form-control" name="antgl[]"></td>
                                                    <td colspan="2"><input type="text" class="form-control" name="anukuran[]" placeholder="Ukuran"></td>
                                                    <td colspan="5"></td>
                                                </tr>
                                                <tr id="showsbtn6" style="display:none;">
                                                    <td colspan="11">
                                                        <a href="javascript:void(0);" style="color:#3477eb;" id="clickbtn6">+ Tambahkan Roll diluar packing list</a>
                                                    </td>
                                                </tr>
                                                <tr id="shows6" style="display:none;">
                                                    <td><?=$key+7;?></td>
                                                    <td><input type="text" class="form-control" placeholder="No Roll" name="annorol[]"></td>
                                                    <td><input type="text" class="form-control" readonly></td>
                                                    <td><input type="date" class="form-control" name="antgl[]"></td>
                                                    <td colspan="2"><input type="text" class="form-control" name="anukuran[]" placeholder="Ukuran"></td>
                                                    <td colspan="5"></td>
                                                </tr>
                                            <?php
                                             } 
                                             //$url4 = $this->uri->segment(5);
                                             //if($url4=="" OR $url4=="0"){} else {
                                               // for ($i=0; $i <$url4 ; $i++) { 
                                                    ?>
                                                <!-- <tr>
                                                    <td><=$key+2+$i;?></td>
                                                    <td><input type="text" class="form-control" name="nomc[]"></td>
                                                    <td><input type="text" class="form-control" name="ukuran[]" readonly></td>
                                                    <td><input type="date" class="form-control" name="fol_tgl[]"></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuran[]"></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranb[]"></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranc[]"></td>
                                                    <td><input type="text" class="form-control" name="fol_ukuranbs[]"></td>
                                                    <td><input type="text" class="form-control" name="fol_operator[]">
                                                        <input type="hidden" name="idpkg[]" value="">
                                                        <input type="hidden" name="pkglist[]" value="">
                                                        <input type="hidden" name="update[]" value="">
                                                    </td>
                                                </tr> -->
                                                    <?php
                                               // }
                                             //}
                                             ?>

                                    </table>
                                </div>
                                <button type="submit" class="btn btn-primary">
									Simpan
								<!-- </button>&nbsp;&nbsp;&nbsp;&nbsp;Tambah &nbsp;<input type="number" id="addcol" style="width:50px;" min="0">&nbsp; Roll &nbsp; <a class="btn btn-dark btn-sm" style="color:#fff;" onclick="newcol()">Go</a> -->
                                
                                &nbsp;
                                <!-- <small><strong>Note :</strong> Jika anda tidak mengisi semua data maka packing list akan di bagi dua. Data yang anda isi akan menjadi packing list yang baru.</small> -->
						</div>
                               
					</div>
                </form>
        </div>
    </div>
</div>
<script>
    function newcol(){
        var a = document.getElementById('addcol').value;
        window.location.href = '<?=base_url("data/inspect/".$url1."/".$url2."/");?>'+a;
    }
    var arleng = document.getElementById("arleng").value;
    for (let i = 0; i < arleng; i++) {
        document.getElementById("btn-"+i+"").onclick = function() {
           document.getElementById("mncl-"+i+"").style.display = '';
           document.getElementById("btn-"+i+"").style.display = 'none';
        };
        document.getElementById("btns-"+i+"").onclick = function() {
           document.getElementById("mncls-"+i+"").style.display = '';
           document.getElementById("btns-"+i+"").style.display = 'none';
        };
        document.getElementById("btnss-"+i+"").onclick = function() {
           document.getElementById("mnclss-"+i+"").style.display = '';
           document.getElementById("btnss-"+i+"").style.display = 'none';
        };
    }
</script>
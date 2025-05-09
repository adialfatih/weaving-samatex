<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data List (Nomor Packinglist : <?=$kdp;?>)</h4>
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
                <form name="fr2" method="post" action="<?=base_url('newpros/up_pkgfolding');?>" entype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                    <div class="page-header">
						<div class="row pd-20">
								<div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th colspan="2"><?=$aling=='ff' ?'Folding Finish':'Folding Grey';?></th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Satuan Ukuran
                                                <select name="satuan" id="satuan" class="form-control" required>
                                                    <option value="">--Pilih Satuan--</option>
                                                    <option value="Yard" <?=$stan=='Yard'?'selected':'';?>>Yard</option>
                                                    <option value="Meter" <?=$stan=='Meter'?'selected':'';?>>Meter</option>
                                                </select>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th colspan="2"><?=$aling=='ff' ?'Inspect Finish':'Inspect Grey';?></th>
                                            <th colspan="3"><?=$aling=='ff' ?'Folding Finish':'Folding Grey';?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            
                                            <th>No. Roll</th>
                                            <th>Ukuran</th>
                                            <th><i class="icon-copy bi bi-check2-circle"></i></th>
                                            <th>Ukuran</th>
                                            <th>Operator</th>
                                            <td></td>
                                        </tr><input type="hidden" name="kd_produksi" value="<?=$kdp;?>">
                                        </tr><input type="hidden" name="proses" value="<?=$aling;?>">
                                        <input type="hidden" name="arleng" id="arleng" value="<?=$dtlist_view->num_rows();?>">
                                        <?php 
                                        $forulang = $dtlist_view->num_rows();
                                        echo '<input type="hidden" id="forulangid" value="'.$forulang.'">';
                                        echo '<input type="hidden" id="fortglid" value="'.$send_tgl.'">';
                                            foreach($dtlist_view->result() as $n => $val):
                                                ?>
                                                <tr>
                                                    <td <?=$val->operator=='SL'?'style="vertical-align:top;"':'';?>><?=$n+1;?>.</td>
                                                    <td <?=$val->operator=='SL'?'style="vertical-align:top;"':'';?>>
                                                        <input type="text" class="form-control" name="noroll[]" value="<?=$val->no_roll;?>" readonly>
                                                        <?php
                                                            if($aling=="ff"){
                                                                ?><input type="hidden" name="idpkg[]" value="<?=$val->id_pkgins;?>"><?php
                                                            } else {
                                                                ?><input type="hidden" name="idpkg[]" value="<?=$val->id_pkg;?>"><?php
                                                            }
                                                        ?>
                                                    </td>
                                                    <td <?=$val->operator=='SL'?'style="vertical-align:top;"':'';?>>
                                                        <input type="text" class="form-control" value="<?=$val->ukuran_now;?>" readonly>
                                                        <?php if($val->operator=="SL"){ ?>
                                                        <small style="color:red;">Stok Lama</small><?php } ?>
                                                    </td>
                                                    <td <?=$val->operator=='SL'?'style="vertical-align:top;"':'';?>>
                                                        <input type="hidden" class="form-control" name="tgl[]" id="utm<?=$n;?>" <?=$val->ukuran_now=='0' ? 'readonly':'';?>>
                                                        <?php if($val->ukuran_now!=0){ ?>
                                                        <div class="custom-control custom-checkbox mb-5">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck<?=$n;?>" /><label class="custom-control-label" for="customCheck<?=$n;?>" >&nbsp;</label>
                                                        </div>
                                                        <?php } ?>
                                                    </td>
                                                    <td <?=$val->operator=='SL'?'style="vertical-align:top;"':'';?>>
                                                        <input type="text" class="form-control" name="ukuran[]" <?=$val->ukuran_now=='0' ? 'readonly':'';?>>
                                                    </td>
                                                    <td <?=$val->operator=='SL'?'style="vertical-align:top;"':'';?>>
                                                        <input type="text" class="form-control" name="operator[]" <?=$val->ukuran_now=='0' ? 'readonly':'';?>>
                                                    </td>
                                                    <td <?=$val->operator=='SL'?'style="vertical-align:top;"':'';?>><a href="javascript:void(0);"><i class="icon-copy bi bi-plus-square-dotted" id="btn-<?=$n;?>"></i></a></td>
                                                </tr>
                                                <tr id="mncl-<?=$n;?>" style="display:none;">
                                                    <td></td>
                                                    <td>
                                                        <input type="text" class="form-control" name="noroll[]" value="<?=$val->no_roll;?>" readonly>
                                                        <?php
                                                            if($aling=="ff"){
                                                                ?><input type="hidden" name="idpkg[]" value="<?=$val->id_pkgins;?>"><?php
                                                            } else {
                                                                ?><input type="hidden" name="idpkg[]" value="<?=$val->id_pkg;?>"><?php
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" class="form-control" name="tgl[]" id="utms<?=$n;?>" <?=$val->ukuran_now=='0' ? 'readonly':'';?>>
                                                        <?php if($val->ukuran_now!=0){ ?>
                                                        <div class="custom-control custom-checkbox mb-5">
                                                            <input type="checkbox" class="custom-control-input" id="customChecks<?=$n;?>" /><label class="custom-control-label" for="customChecks<?=$n;?>" >&nbsp;</label>
                                                        </div>
                                                        <?php } ?>                                                        
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="ukuran[]" <?=$val->ukuran_now=='0' ? 'readonly':'';?>>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="operator[]" <?=$val->ukuran_now=='0' ? 'readonly':'';?>>
                                                    </td>
                                                    <td><a href="javascript:void(0);"><i class="icon-copy bi bi-plus-square-dotted" id="btns-<?=$n;?>"></i></a></td>
                                                </tr>
                                                <tr id="mncls-<?=$n;?>" style="display:none;">
                                                    <td></td>
                                                    <td>
                                                        <input type="text" class="form-control" name="noroll[]" value="<?=$val->no_roll;?>" readonly>
                                                        <?php
                                                            if($aling=="ff"){
                                                                ?><input type="hidden" name="idpkg[]" value="<?=$val->id_pkgins;?>"><?php
                                                            } else {
                                                                ?><input type="hidden" name="idpkg[]" value="<?=$val->id_pkg;?>"><?php
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" class="form-control" name="tgl[]" id="utmss<?=$n;?>" <?=$val->ukuran_now=='0' ? 'readonly':'';?>>
                                                        <?php if($val->ukuran_now!=0){ ?>
                                                        <div class="custom-control custom-checkbox mb-5">
                                                            <input type="checkbox" class="custom-control-input" id="customCheckss<?=$n;?>" /><label class="custom-control-label" for="customCheckss<?=$n;?>" >&nbsp;</label>
                                                        </div>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="ukuran[]" <?=$val->ukuran_now=='0' ? 'readonly':'';?>>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="operator[]" <?=$val->ukuran_now=='0' ? 'readonly':'';?>>
                                                    </td>
                                                    <td><a href="javascript:void(0);"><i class="icon-copy bi bi-plus-square-dotted" id="btnss-<?=$n;?>"></i></a></td>
                                                </tr>
                                                <tr id="mnclss-<?=$n;?>" style="display:none;">
                                                    <td></td>
                                                    <td>
                                                        <input type="text" class="form-control" name="noroll[]" value="<?=$val->no_roll;?>" readonly>
                                                        <?php
                                                            if($aling=="ff"){
                                                                ?><input type="hidden" name="idpkg[]" value="<?=$val->id_pkgins;?>"><?php
                                                            } else {
                                                                ?><input type="hidden" name="idpkg[]" value="<?=$val->id_pkg;?>"><?php
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" class="form-control" name="tgl[]" id="utmsss<?=$n;?>" <?=$val->ukuran_now=='0' ? 'readonly':'';?>>
                                                        <?php if($val->ukuran_now!=0){ ?>
                                                        <div class="custom-control custom-checkbox mb-5">
                                                            <input type="checkbox" class="custom-control-input" id="customChecksss<?=$n;?>" /><label class="custom-control-label" for="customChecksss<?=$n;?>" >&nbsp;</label>
                                                        </div>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="ukuran[]" <?=$val->ukuran_now=='0' ? 'readonly':'';?>>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="operator[]" <?=$val->ukuran_now=='0' ? 'readonly':'';?>>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <?php
                                            endforeach;   ?>
                                                <tr id="showsbtn1">
                                                    <td colspan="7">
                                                        <a href="javascript:void(0);" style="color:#3477eb;" id="clickbtn1">+ Tambahkan Roll diluar packing list</a>
                                                    </td>
                                                </tr>
                                                <tr id="shows1" style="display:none;">
                                                    <td><?=$n+2;?></td>
                                                    <td><input type="text" class="form-control" placeholder="No Roll" name="annorol[]"></td>
                                                    <td><input type="text" class="form-control" readonly></td>
                                                    <td><input type="date" class="form-control" name="antgl[]"></td>
                                                    <td><input type="text" class="form-control" name="anukuran[]" placeholder="Ukuran"></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr id="showsbtn2" style="display:none;">
                                                    <td colspan="11">
                                                        <a href="javascript:void(0);" style="color:#3477eb;" id="clickbtn2">+ Tambahkan Roll diluar packing list</a>
                                                    </td>
                                                </tr>
                                                <tr id="shows2" style="display:none;">
                                                    <td><?=$n+3;?></td>
                                                    <td><input type="text" class="form-control" placeholder="No Roll" name="annorol[]"></td>
                                                    <td><input type="text" class="form-control" readonly></td>
                                                    <td><input type="date" class="form-control" name="antgl[]"></td>
                                                    <td><input type="text" class="form-control" name="anukuran[]" placeholder="Ukuran"></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr id="showsbtn3" style="display:none;">
                                                    <td colspan="11">
                                                        <a href="javascript:void(0);" style="color:#3477eb;" id="clickbtn3">+ Tambahkan Roll diluar packing list</a>
                                                    </td>
                                                </tr>
                                                <tr id="shows3" style="display:none;">
                                                    <td><?=$n+4;?></td>
                                                    <td><input type="text" class="form-control" placeholder="No Roll" name="annorol[]"></td>
                                                    <td><input type="text" class="form-control" readonly></td>
                                                    <td><input type="date" class="form-control" name="antgl[]"></td>
                                                    <td><input type="text" class="form-control" name="anukuran[]" placeholder="Ukuran"></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr id="showsbtn4" style="display:none;">
                                                    <td colspan="11">
                                                        <a href="javascript:void(0);" style="color:#3477eb;" id="clickbtn4">+ Tambahkan Roll diluar packing list</a>
                                                    </td>
                                                </tr>
                                                <tr id="shows4" style="display:none;">
                                                    <td><?=$n+5;?></td>
                                                    <td><input type="text" class="form-control" placeholder="No Roll" name="annorol[]"></td>
                                                    <td><input type="text" class="form-control" readonly></td>
                                                    <td><input type="date" class="form-control" name="antgl[]"></td>
                                                    <td><input type="text" class="form-control" name="anukuran[]" placeholder="Ukuran"></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr id="showsbtn5" style="display:none;">
                                                    <td colspan="11">
                                                        <a href="javascript:void(0);" style="color:#3477eb;" id="clickbtn5">+ Tambahkan Roll diluar packing list</a>
                                                    </td>
                                                </tr>
                                                <tr id="shows5" style="display:none;">
                                                    <td><?=$n+6;?></td>
                                                    <td><input type="text" class="form-control" placeholder="No Roll" name="annorol[]"></td>
                                                    <td><input type="text" class="form-control" readonly></td>
                                                    <td><input type="date" class="form-control" name="antgl[]"></td>
                                                    <td><input type="text" class="form-control" name="anukuran[]" placeholder="Ukuran"></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr id="showsbtn6" style="display:none;">
                                                    <td colspan="11">
                                                        <a href="javascript:void(0);" style="color:#3477eb;" id="clickbtn6">+ Tambahkan Roll diluar packing list</a>
                                                    </td>
                                                </tr>
                                                <tr id="shows6" style="display:none;">
                                                    <td><?=$n+7;?></td>
                                                    <td><input type="text" class="form-control" placeholder="No Roll" name="annorol[]"></td>
                                                    <td><input type="text" class="form-control" readonly></td>
                                                    <td><input type="date" class="form-control" name="antgl[]"></td>
                                                    <td><input type="text" class="form-control" name="anukuran[]" placeholder="Ukuran"></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                    </table>
                                </div>
                                <button type="submit" class="btn btn-primary">
									Simpan
								</button>
                                &nbsp;
                                <!-- <small><strong>Note :</strong> Jika anda tidak mengisi semua data maka packing list akan di bagi dua. Data yang anda isi akan menjadi packing list yang baru.</small> -->
						</div>
                               
					</div>
                </form>
        </div>
    </div>
</div>
<script>
    
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
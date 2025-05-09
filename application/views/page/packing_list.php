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
                    <div class="page-header" id="import-excell" style="display:none;">
						<div class="row pd-20">
                        <?php echo form_open_multipart('phpsp/import',array('name' => 'spreadsheet')); ?>
                        <table align="center" cellpadding = "5">
                        <tr>
                        <td>File :</td>
                        <td><input type="file" size="40px" name="upload_file" /></td>
                        <td class="error"><?php echo form_error('name'); ?></td>
                        <td colspan="5" align="center">
                        <button type="submit" class="btn" data-bgcolor="#046e16" data-color="#ffffff">
                            <span class="icon-copy ti-export"></span> &nbsp; Upload Excel
                        </button>
                        </tr>
                        </table>
                        <?php echo form_close();?>
                        </div>
                    </div>
                <form name="fr2" method="post" action="<?=base_url('newpros/up_pkglist');?>" entype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                    <div class="page-header">
						<div class="row pd-20">
								<div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th colspan="2">Inpect Grey</th>
                                            <td colspan="3">
                                                <a href="<?=base_url('phpsp/export/'.$kdp.'/'.$satuan_asli.'');?>">
                                                <button type="button" class="btn" data-bgcolor="#5281cc" data-color="#ffffff">
                                                    <i class="icon-copy fa fa-file-excel-o" aria-hidden="true"></i> &nbsp; Download Template
                                                </button></a>
                                                <button type="button" id="button_im1" onclick="testopen()" class="btn" data-bgcolor="#046e16" data-color="#ffffff">
                                                    <span class="icon-copy ti-export"></span> &nbsp; Upload Excel
                                                </button>
                                            </td>
                                            <td colspan="2">Satuan Ukuran
                                                <select name="satuan" id="satuan" class="form-control" required>
                                                    <option value="">--Pilih Satuan--</option>
                                                    <option value="Yard" <?=$satuan_asli=='Yard' ? 'selected':'';?>>Yard</option>
                                                    <option value="Meter" <?=$satuan_asli=='Meter' ? 'selected':'';?>>Meter</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="table-active">
                                            <th rowspan="2">No.</th>
                                            <th rowspan="2">No. Roll</th>
                                            <th colspan="4" class="text-center">Ukuran</th>
                                            <th rowspan="2">Operator</th>
                                        </tr><input type="hidden" name="id_produksi" value="<?=$kdp;?>">
                                        <tr class="table-active">
                                            <th class="text-center">ORI</th>
                                            <th class="text-center">B</th>
                                            <th class="text-center">C</th>
                                            <th class="text-center">BS</th>
                                        </tr>
                                        <?php 
                                        if($dtlist=="ada"){
                                            $nolist = 1;
                                            foreach($dtlist_view->result() as $n => $val):
                                                ?>
                                                <tr>
                                                    <td><?=$n+1;?>. <?php $nolist+=1;?></td>
                                                    <td><input type="text" class="form-control" value="<?=$val->no_roll;?>" name="nomesin[]"></td>
                                                    <td><input type="text" class="form-control" value="<?=$val->ukuran_now;?>" name="ukuran[]"></td>
                                                    <td><input type="text" class="form-control" value="<?=$val->ukuran_b;?>" name="ukuranb[]"></td>
                                                    <td><input type="text" class="form-control" value="<?=$val->ukuran_c;?>" name="ukuranc[]"></td>
                                                    <td><input type="text" class="form-control" value="<?=$val->ukuran_bs;?>" name="ukuranbs[]"></td>
                                                    
                                                    <td><input type="text" class="form-control" value="<?=$val->operator;?>" name="operator[]">
                                                        <input type="hidden" name="idpkg[]" value="<?=$val->id_pkg;?>">
                                                    </td>
                                                </tr>
                                                <?php
                                            endforeach;
                                             if(empty($this->uri->segment(4)) OR $this->uri->segment(4)==0){} else {
                                                for ($i=0; $i <$this->uri->segment(4) ; $i++) { 
                                                        ?>
                                                        <tr>
                                                            <td><?=$nolist;?>. <?php $nolist+=1;?></td>
                                                            <td><input type="text" class="form-control" name="nomesin[]"></td>
                                                            <td><input type="text" class="form-control" name="ukuran[]"></td>
                                                            <td><input type="text" class="form-control" name="ukuranb[]"></td>
                                                            <td><input type="text" class="form-control" name="ukuranc[]"></td>
                                                            <td><input type="text" class="form-control" name="ukuranbs[]"></td>
                                                            
                                                            <td><input type="text" class="form-control" name="operator[]">
                                                            <input type="hidden" name="idpkg[]" value="zero">
                                                            </td>
                                                        </tr>
                                                        <?php
                                                } //end for
                                            } //end if empty
                                        } else {
                                         $cols=$col+1; for ($i=1; $i<$cols  ; $i++) { ?>
                                         
                                        <tr>
                                            <td><?=$i;?>.</td>
                                            <td><input type="text" class="form-control" name="nomesin[]"></td>
                                            <td><input type="text" class="form-control" name="ukuran[]"></td>
                                            <td><input type="text" class="form-control" name="ukuranb[]"></td>
                                            <td><input type="text" class="form-control" name="ukuranc[]"></td>
                                            <td><input type="text" class="form-control" name="ukuranbs[]"></td>
                                            <td><input type="text" class="form-control" name="operator[]">
                                                <input type="hidden" name="idpkg[]" value="zero">
                                            </td>
                                        </tr>
                                        <?php } }  ?>
                                    </table>
                                </div>
                                <button type="submit" class="btn btn-primary">
									Simpan
								</button>&nbsp;&nbsp;&nbsp;&nbsp;Tambah &nbsp;<input type="number" id="addcol" style="width:50px;" min="0">&nbsp; Roll &nbsp; <a class="btn btn-dark btn-sm" style="color:#fff;" onclick="newcol()">Go</a>
						</div>
                                <div class="row pd-20">
                                    <div class="alert alert-dark alert-dismissible fade show" role="alert" >
                                        <strong>Note : </strong> &nbsp; Kosongkan jika tidak di isi
                                    </div>
                                </div>
					</div>
                </form>
        </div>
    </div>
</div>
<script>
    function newcol(){
        var a = document.getElementById('addcol').value;
        window.location.href = '<?=base_url("data/packinglist/".sha1($kdp)."/");?>'+a;
    }
    function testopen(){
        let fork = document.getElementById('import-excell');
        let btn1 = document.getElementById('button_im1');
        fork.style.display = "block";
        btn1.style.display = "none";
    }
</script>
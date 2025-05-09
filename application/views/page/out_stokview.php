<?php $arb = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar','04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des']; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data Produksi</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Data Produksi</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Di Luar
										</li>
									</ol>
								</nav>
							</div>
							
						</div>
					</div>
					
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Menampilkan data produksi yang di kirim keluar.
							</p>
						</div>
						<div class="pb-20">
							<div class="table-responsive">
                                <table class="data-table table stripe hover nowrap">
                                    <thead>
                                        <tr>
                                            <th class="table-plus datatable-nosort">No</th>
                                            <th>Kode Produksi</th>
                                            <th>Lokasi</th>
                                            <th>Tanggal Pengiriman</th>
                                            <th>Jumlah</th>
                                            <th>Tanggal Kembali</th>
                                            <!-- <th>Jumlah Mesin</th> -->
                                            <th class="datatable-nosort">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if($dttable->num_rows() > 0){
                                            foreach($dttable->result() as $n => $val):
                                                ?>
                                        <tr>
                                            <td><?=$n+1;?></td>
                                            <td><?=$val->kode_produksi;?></td>
                                            <td><?=ucwords($val->lokasi_kirim);?></td>
                                            <td>
                                                <?php $ex = explode('-', $val->tgl_kirim); echo $ex[2]."-".$arb[$ex[1]]."-".$ex[0].""; ?>
                                            </td>
                                            <td><?=$val->jml_kirim;?> m</td>
                                            <td>
                                                <?php if($val->tgl_kembali=='null'){echo"<code>Belum Kembali</code>";}else{
                                                    $er = explode('-', $val->tgl_kembali);
                                                    echo $er[2]."-".$arb[$er[1]]."-".$er[0]."";
                                                } ?>
                                            </td>
                                            <td>
                                                <?php if($val->st_back=="null"){ ?>
                                                <div class="dropdown">
                                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                        <i class="dw dw-more"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="reuturfs('<?=$val->kode_produksi;?>','<?=$val->idpout;?>','<?=ucwords($val->lokasi_kirim);?>')" data-toggle="modal" data-target="#Medium-modal221"><i class="icon-copy fa fa-check-circle" aria-hidden="true"></i> Finish</a>
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="reutur('<?=$val->kode_produksi;?>','<?=$val->idpout;?>')" data-toggle="modal" data-target="#Medium-modal"><i class="icon-copy fa fa-refresh" aria-hidden="true"></i> Retur</a>
                                                    </div>
                                                </div>
                                                <?php } else { echo "Has ".$val->st_back.""; } ?>
                                            </td>
                                        </tr>
                                                <?php
                                            endforeach;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
						</div>
					</div>
					<!-- pemisah modal -- modal retur -->
                                <div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Retur Barang
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            <form name="fr1" action="<?=base_url('proses/returdep');?>" method="post">
											<div class="modal-body">
												<p>
													Anda akan mengembalikan barang dengan kode produksi <span id="idprospan">128491</span> ke <strong><?=$sess_dep;?></strong>?
												</p>
                                                <input type="hidden" id="idproid" name="idprod">
                                                <input type="hidden" id="idpouidasli" name="idasli">
                                                <table class="table table-bordered">
													<tr>
														<td>Tanggal Retur</td>
														<td><input type="date" name="tgl" class="form-control" placeholder="Masukan tanggal kembali" required></td>
													</tr>
                                                </table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
												<button type="submit" class="btn btn-primary">
													Retur
												</button>
											</div>
                                            </form>
										</div>
									</div>
								</div>
                                <!-- pemisah modal -- modal finish-->
                                <div class="modal fade" id="Medium-modal221" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel2">
													Retur Finish
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            
                                            <?php echo form_open_multipart('phpsp/finishdep',array('name' => 'spreadsheet')); ?>
											<div class="modal-body">
                                                <input type="hidden" id="idproid2" name="idprod">
                                                <input type="hidden" id="idpouidasli2" name="idasli">
                                                <p>Anda akan menarik barang yang berada di <span id="locisd"></span> ke <strong><?=$sess_dep;?></strong> dan menjadi barang siap jual. Silahkan gunakan template untuk data retur <a href="<?=base_url('phpsp/exfinishdep/');?>" style="color:blue;">download disini</a></p>
                                                <table class="table table-bordered">
													<tr>
														<td>Tanggal Retur</td>
														<td><input type="date" name="tgl" class="form-control" placeholder="Masukan tanggal kembali" required></td>
													</tr>
                                                    <tr>
														<td>Folding</td>
														<td>
                                                            <select name="fol" id="foldid" class="form-control" required>
                                                                <option value="">-Pilih Jenis-</option>
                                                                <option value="Grey">Grey</option>
                                                                <option value="Finish">Finish</option>
                                                            </select>
                                                        </td>
													</tr>
                                                    <tr>
														<td>Data Retur</td>
														<td>
                                                            <input type="file" name="upload_file" class="form-control" required >
                                                        </td>
													</tr>
                                                </table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
												<button type="submit" class="btn btn-primary">
													Simpan Data
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
    function reutur(kd,id){
        document.getElementById('idprospan').innerHTML = "<strong>"+kd+"</strong>";
        document.getElementById('idproid').value = ''+kd;
        document.getElementById('idpouidasli').value = ''+id;
    }
    function reuturfs(kd,id,loc){
        document.getElementById('locisd').innerHTML = "<strong>"+loc+"</strong>";
        document.getElementById('idproid2').value = ''+kd;
        document.getElementById('idpouidasli2').value = ''+id;
    }
</script>
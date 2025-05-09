<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Pengiriman Packaging List</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="Javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="Javascript:void(0);">Data</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="Javascript:void(0);">Packaging List</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Pengiriman
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<!-- Simple Datatable start -->
				
				<!-- basic table  Start -->
                <div class="pd-20 card-box mb-30">
						<div class="clearfix mb-20">
							<div class="pull-left">
								<h4 class="h4">Data Packaging List</h4>
							</div>
						</div>
						<div class="table-responsive">
						<table class="data-table table stripe hover nowrap">
							<thead>
								<tr>
                                    <th>No.</th>
                                    <th>Kode PKG</th>
									<th>Konstruksi</th>
                                    <th>Dari</th>
                                    <th>Kirim ke</th>
									<th>Jenis Paket</th>
                                    <th>Jumlah Roll</th>
                                    <th>Tanggal Kirim</th>
									<th>#</th>
                                    <td></td>
                                </tr>
							</thead>
							<tbody><?php
                                foreach($dt_list->result() as $n => $val): 
                                $kdpkg = $val->kd;
                                $dtpkt = $this->data_model->get_byid('new_tb_packinglist',['kd'=>$kdpkg]);
                                $ex = explode('-', $val->tgl_kirim);
                                $printTgl = $ex[2]."-".$bln[$ex[1]]."-".$ex[0];
                                $dari = $val->kirim_dari;
                                ?>
                                <tr>
                                    <td><?=$n+1;?></td>
                                    <td><?=$kdpkg;?></td>
                                    <td><?=$dtpkt->row("kode_konstruksi");?></td>
                                    <td><?=$val->kirim_dari;?></td>
                                    <td><?=$val->kirim_ke;?></td>
									<td><?=$dtpkt->row("siap_jual")=='y' ? 'Paket Siap Jual':'Paket Produksi';?></td>
                                    <td>
                                        <?php
                                        $jml_roll = $this->data_model->get_byid('new_tb_isi_paket', ['id_kdlist'=>$kdpkg])->num_rows();
                                        echo $jml_roll;
                                        ?>
                                    </td>
                                    <td><?=$printTgl;?></td>
									<td>
                                        <a href="javascript:void(0);" title="Terima Paket" onclick="verif('<?=$kdpkg;?>','<?=$dari;?>','<?=$printTgl;?>')" data-toggle="modal" data-target="#Medium-modal"><i class="icon-copy bi bi-check-circle-fill" style="color:green;"></i></a>
                                        &nbsp;
                                        <a href="javascript:void(0);" title="Kembalikan Paket"><i class="icon-copy bi bi-info-circle-fill" style="color:red;"></i></a>
                                    </td>
                                    <td><?php if($loc==$val->kirim_dari){ ?>
                                        <i class="icon-copy bi bi-box-arrow-up-right" title="Paket di kirim ke <?=$val->kirim_ke;?>" style="color:red;"></i>
                                        <?php } else { ?>
                                        <i class="icon-copy bi bi-box-arrow-in-down-left" title="Paket di kirim dari <?=$val->kirim_dari;?>" style="color:green;"></i>
                                        <?php }?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
							</tbody>
						</table>
						</div>
					</div>
					<!-- basic table  End -->
                    <!-- pemisah modal -->
								<div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Verifikasi Paket
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            <form name="fr1" action="owek" method="post">
											<div class="modal-body">
												<table class="table">
													<tr>
														<td>Nomor Paket</td>
														<td><input type="text" class="form-control" name="kd" id="sendid" readonly></td>
													</tr>
													<tr>
														<td>Di Kirim Dari</td>
														<td><input type="text" class="form-control" name="locnow" id="locidnow" readonly></td>
													</tr>
													<tr>
														<td>Tanggal</td>
														<td>
															<input type="text" class="form-control" name="tgl" id="idtgl" readonly>
														</td>
													</tr>
												</table>
                                                <hr>
                                                <strong>Note : </strong> Setelah anda verifikasi pengiriman paket, stok di <strong><?=$loc;?></strong> akan bertambah sesuai jumlah paket yang dikirimkan dari <strong id="dinamicid"></strong>.
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
												<button type="submit" class="btn btn-primary">
													Verifikasi
												</button>
											</div>
                                            </form>
										</div>
									</div>
								</div>
        </div>
    </div>
</div>
<script>
    function verif(kd,dr,tgl){
        document.getElementById('sendid').value = ''+kd;
        document.getElementById('locidnow').value = ''+dr;
        document.getElementById('dinamicid').innerHTML = ''+dr;
        document.getElementById('idtgl').value = ''+tgl;
    }
</script>
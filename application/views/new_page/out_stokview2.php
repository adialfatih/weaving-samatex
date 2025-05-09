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
											Pengiriman
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
								Menampilkan pengiriman dan penjualan dengan surat jalannya.
							</p>
						</div>
						<div class="pb-20">
							<div class="table-responsive">
                                <table class="data-table table stripe hover nowrap">
                                    <thead>
                                        <tr>
                                            <th class="table-plus datatable-nosort">No</th>
                                            <th>Surat Jalan</th>
                                            <th>Dikirim ke</th>
                                            <th>Tanggal Pengiriman</th>
                                            <th>Jumlah Packinglist</th>
                                            <th class="datatable-nosort">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($dttable->result() as $n => $val): ?>
                                        <tr>
                                            <td><?=$n+1;?></td>
                                            <td><?=$val->no_sj;?></td>
                                            <td>
											<?php if($val->tujuan_kirim=="cus"){
												$idcus = $val->id_customer;
												$namakonsumen = $this->data_model->get_byid('dt_konsumen',['id_konsumen'=>$idcus])->row("nama_konsumen");
												echo $namakonsumen;
											} else {
												echo $val->tujuan_kirim;
											}
											?>
											</td>
                                            <td>
                                                <?php $ex = explode('-', $val->tgl_kirim); echo $ex[2]."-".$arb[$ex[1]]."-".$ex[0]; ?>
                                            </td>
                                            <td>
                                                <?php 
												$cek_pkglist = $this->data_model->get_byid('new_tb_packinglist', ['no_sj'=>$val->no_sj]);
												echo $cek_pkglist->num_rows();
												?>
                                            </td>
                                            <td>
												<?php if($sess_dep=="Samatex"){?>
												<a href="<?=base_url('cetak/suratjalan/'.sha1($val->id_sj));?>" target="_blank">
												<i class="icon-copy bi bi-printer-fill" style="color:black;" aria-hidden="true"></i></a>
												<?php } else { ?>
												<a href="<?=base_url('cetakrjs/sj/'.sha1($val->id_sj));?>" target="_blank">
												<i class="icon-copy bi bi-printer-fill" style="color:black;" aria-hidden="true"></i></a>
												<?php } ?>
												&nbsp;
												<a href="<?=base_url('inputt/hapussj/'.$val->id_sj);?>" style="color:red;">
												Delete & Reverse
												</a>
											</td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
						</div>
					</div>
        </div>
    </div>
</div>
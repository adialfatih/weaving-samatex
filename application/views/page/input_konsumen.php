<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data Konsumen</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="index.html">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Data Konsumen
										</li>
									</ol>
								</nav>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a class="btn btn-primary dropdown-toggle no-arrow" href="javascript:void(0);" role="button" data-toggle="modal" data-target="#bd-example-modal-lg">
										Tambah Data
									</a>
									
								</div>
							</div>
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Data Konsumen
							</p>
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
									    <th class="table-plus datatable-nosort">No</th>
										<th>Nama</th>
										<th>No Telp</th>
										<th>Alamat</th>
										<th>Nota Penjualan</th>
										<th class="datatable-nosort">Action</th>
									</tr>
								</thead>
								<tbody>
                                    <?php 
                                    if($dt_table->num_rows() > 0){
                                    foreach($dt_table->result() as $n => $val):
									$idcus = $val->id_konsumen;
									?>
									<tr>
                                        <td><?=$n+1;?></td>
                                        <td><?=$val->nama_konsumen;?></td>
                                        <td><?=$val->no_hp;?></td>
                                        <td><?=$val->alamat;?></td>
										<td>
											<?php 
												$qr_nota = $this->db->query("SELECT a_nota.id_nota,a_nota.no_sj,surat_jalan.no_sj,surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj = surat_jalan.no_sj AND surat_jalan.id_customer='$idcus'");
												echo $qr_nota->num_rows();
											?>
										</td>
                                        <td>
                                            <div class="dropdown">
												<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="javascript:void(0);" role="button" data-toggle="dropdown" >
													<i class="dw dw-more"></i>
												</a>
												<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list" >
													<a class="dropdown-item" href="<?=base_url('nota/konsumen/'.sha1($idcus));?>">
														<i class="bi bi-clipboard-check"></i> Lihat Nota Penjualan
													</a>
													<a class="dropdown-item" href="<?=base_url('nota/pembayaran/'.sha1($idcus));?>">
														<i class="icon-copy fa fa-money" aria-hidden="true"></i>
														Riwayat Pembayaran
													</a>
													<a class="dropdown-item" href="javascript:void(0);" onclick="upedit('<?=$val->id_konsumen;?>','<?=$val->nama_konsumen;?>','<?=$val->no_hp;?>','<?=$val->alamat;?>')" data-toggle="modal" data-target="#editmodal"><i class="dw dw-edit2"></i> Edit Data Konsumen</a
													>
													<a class="dropdown-item" href="javascript:void(0);" onclick="dels('<?=$val->id_konsumen;?>','<?=$val->nama_konsumen;?>')" data-toggle="modal" data-target="#Medium-modal"><i class="dw dw-delete-3"></i> Delete Konsumen</a
													>
													
											    </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach;
                                    } else {
                                        echo '
                                        <tr>
                                            <td><code>Data empty</code></td>
                                            <td><code>Data empty</code></td>
                                            <td><code>Data empty</code></td>
                                            <td><code>Data empty</code></td>
                                            <td><code>Data empty</code></td>
                                            
                                        </tr>
                                        ';
                                    } ?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- Simple Datatable End -->
					            <div class="modal fade bs-example-modal-lg" id="bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg modal-dialog-centered">
										<div class="modal-content">
											<form name="fr2" action="<?=base_url('proses/addkonsumen');?>" method="post" entype="multipart/form-data">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Input Data Konsumen Baru
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<div class="modal-body">
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Nama Konsumen</label>
														<div class="col-sm-12 col-md-10">
															<input class="form-control" type="text" placeholder="Masukan nama konsumen baru" name="konsum" required />
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">No Telepon</label>
														<div class="col-sm-12 col-md-10">
                                                            <input class="form-control" type="text" placeholder="Ex : 62" name="notelp" value="62" required />
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Alamat</label>
														<div class="col-sm-12 col-md-10">
															<textarea name="almat" id="alamat" class="form-control" rows="2" required></textarea>
														</div>
													</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">
													Close
												</button>
												<button type="submit" class="btn btn-primary">
													Save changes
												</button>
											</div>
											</form>
										</div>
									</div>
								</div>
						<!-- Medium modal -->
                        <!-- Simple Datatable End -->
					            <div class="modal fade bs-example-modal-lg" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg modal-dialog-centered">
										<div class="modal-content">
											<form name="fr2" action="<?=base_url('proses/up_konsum');?>" method="post" entype="multipart/form-data">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Edit Data Konsumen owek
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<div class="modal-body">
                                                <input type="hidden" id="up_id1" name="idkons">
                                                    <div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Nama Konsumen</label>
														<div class="col-sm-12 col-md-10">
															<input class="form-control" id="up_nm" type="text" placeholder="Masukan nama konsumen baru" name="konsum" required />
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">No Telepon</label>
														<div class="col-sm-12 col-md-10">
                                                            <input class="form-control" id="up_no" type="text" placeholder="Ex : 62" name="notelp" value="62" required />
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Alamat</label>
														<div class="col-sm-12 col-md-10">
															<textarea name="almat" id="up_alamat" class="form-control" rows="2" required></textarea>
														</div>
													</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">
													Close
												</button>
												<button type="submit" class="btn btn-primary">
													Save changes
												</button>
											</div>
											</form>
										</div>
									</div>
								</div>
						<!-- Medium modal -->
								<div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Hapus Konsumen
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<form name="fr2" action="<?=base_url('proses/del_kons');?>" method="post">
											<div class="modal-body">
												Anda akan menghapus data konsumen <span id="tglss"></span> ?
                                                <input type="hidden" id="idkons1" name="idkons">
                                                <input type="hidden" id="nmkons1" name="nmkons">
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">
													Tutup
												</button>
												<button type="submit" class="btn btn-danger">
													Hapus
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
    function dels(id,nm){
        document.getElementById('tglss').innerHTML = '<strong>'+nm+'</strong>';
        document.getElementById('idkons1').value = ''+id;
        document.getElementById('nmkons1').value = ''+nm;
    }
    function upedit(id,nm,no,al){
        document.getElementById('up_id1').value = ''+id;
        document.getElementById('up_nm').value = ''+nm;
        document.getElementById('up_no').value = ''+no;
        document.getElementById('up_alamat').value = ''+al;
    }
</script>
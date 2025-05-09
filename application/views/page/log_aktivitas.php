<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Manage Aktivitas User</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="index.html">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
                                            Aktivitas User
										</li>
									</ol>
								</nav>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a class="btn btn-danger dropdown-toggle no-arrow" href="javascript:void(0);" role="button" data-toggle="modal" data-target="#Medium-modal">
										Hapus Log
									</a>
									
								</div>
							</div>
						</div>
					</div>
					
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Aktivitas User
							</p>
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort">No</th>
										<th>Tanggal</th>
										<th>Nama User</th>
										<th>Aktivitas</th>
										<th class="datatable-nosort">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($data_table->result() as $no => $val): ?>
									<tr>
										<td class="table-plus"><?=$no+1;?></td>
										<td><?=$val->tm_stmp;?></td>
										<td>
                                            <?php $user = $this->db->query("SELECT id_user,nama_user FROM user WHERE id_user='$val->id_user'")->row("nama_user"); echo $user; ?>
                                        </td>
										<td><?=$val->log;?></td>
										
										<td>
                                            <a class="dropdown-item" href="javascript:void(0);"><i class="dw dw-delete-3"></i> Delete</a>
										</td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- Simple Datatable End -->
                    <!-- pemisah modal -->
                    <div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Hapus Aktivitas
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            <form name="fr1" action="<?=base_url('proses/deluser');?>" method="post">
											<div class="modal-body">
												<p>
													Anda yakin akan menghapus semua log aktivitas user ?
												</p>
                                                <input type="hidden" name="iduser" id="dinamic_id">
											</div>
											<div class="modal-footer">
                                                <button type="submit" class="btn btn-default">
													Hapus Log
												</button>
												<button type="button" class="btn btn-primary" data-dismiss="modal">Tidak</button>
												
											</div>
                                            </form>
										</div>
									</div>
								</div>
        </div>
    </div>
</div>
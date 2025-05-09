<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data Konstruksi</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Data Konstruksi
										</li>
									</ol>
								</nav>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a class="btn btn-primary dropdown-toggle no-arrow" href="javascript:void(0);" role="button" data-toggle="modal" data-target="#bd-example-modal-lg">
										Tambah Konstruksi
									</a>
									
								</div>
							</div>
						</div>
					</div>	
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Data Konstruksi
							</p>
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort">No</th>
										<th>Kode Konstruksi</th>
										<th>Keterangan</th>
										<th>Nama Lain</th>
										<th class="datatable-nosort">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($data_table->result() as $no => $val): ?>
									<tr>
										<td class="table-plus"><?=$no+1;?></td>
										<td><?=$val->kode_konstruksi;?></td>
										<td><?=$val->ket;?></td>
										<td><?=$val->chto;?></td>
										<td>
											<div class="dropdown">
												<a
													class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
													href="#"
													role="button"
													data-toggle="dropdown"
												>
													<i class="dw dw-more"></i>
												</a>
												<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
													<a class="dropdown-item" href="javascript:void(0);" onclick="upkons('<?=$val->kode_konstruksi;?>','<?=$val->ket;?>','<?=$val->chto;?>')" data-toggle="modal" data-target="#upup2"><i class="dw dw-edit2"></i> Edit</a>

													<a class="dropdown-item" href="javascript:void(0);" onclick="del('<?=$val->kode_konstruksi;?>')" data-toggle="modal" data-target="#Medium-modal"><i class="dw dw-delete-3"></i> Delete</a>
												</div>
											</div>
										</td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- Simple Datatable End -->
								<div class="modal fade bs-example-modal-lg" id="bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg modal-dialog-centered">
										<div class="modal-content">
											<form name="fr1" action="<?=base_url('proses/addkons');?>" method="post" entype="multipart/form-data">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Input Data Konstruksi
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
												</button>
											</div>
											<div class="modal-body">
												<input type="hidden" name="id_user" value="<?=$sess_id;?>">
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Kode Konstruksi</label>
														<div class="col-sm-12 col-md-10">
															<input class="form-control" type="text" placeholder="Masukan kode konstruksi" name="kode" required />
														</div>
													</div>
													
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Keterangan</label>
														<div class="col-sm-12 col-md-10">
															<input class="form-control" type="text" placeholder="Masukan keterangan" name="ket" required />
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Nama Lain</label>
														<div class="col-sm-12 col-md-10">
															<input class="form-control" type="text" placeholder="Masukan nama konstruksi penjualan" name="chto" required />
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
								<!-- Update modal form -->
								<div class="modal fade bs-example-modal-lg" id="upup2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg modal-dialog-centered">
										<div class="modal-content">
											<form name="fr1" action="<?=base_url('proses/upkons');?>" method="post" entype="multipart/form-data">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Input Data Konstruksi
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
												</button>
											</div>
											<div class="modal-body">
												<input type="hidden" name="id_user" value="<?=$sess_id;?>">
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Kode Konstruksi</label>
														<div class="col-sm-12 col-md-10">
															<input class="form-control" type="text" placeholder="Masukan kode konstruksi" name="kode" id="id_kode" readonly />
														</div>
													</div>
													
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Keterangan</label>
														<div class="col-sm-12 col-md-10">
															<input class="form-control" type="text" placeholder="Masukan keterangan" name="ket" id="id_ket" required />
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Nama Lain</label>
														<div class="col-sm-12 col-md-10">
															<input class="form-control" type="text" placeholder="Masukan keterangan" name="chto" id="id_chto" required />
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
								<!-- pemisah modal -- modal delete -->
                                <div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Delete Konstruksi
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            <form name="fr1" action="<?=base_url('proses/delkons');?>" method="post">
											<div class="modal-body">
												<p>
													Anda akan menghapus konstruksi dengan kode konstruksi <span id="dinamic_kode">kode</span>?
												</p>
                                                <input type="hidden" name="del_kd" id="del_kd">
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
												<button type="submit" class="btn btn-default">
													Yes, Delete
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
	function del(kd){
		document.getElementById("dinamic_kode").innerHTML = '<strong>'+kd+'</strong>';
		document.getElementById("del_kd").value = ''+kd;
	}
	function upkons(kd,ket,chto){
		document.getElementById("id_kode").value = ''+kd;
		document.getElementById("id_ket").value = ''+ket;
		document.getElementById("id_chto").value = ''+chto;
	}
</script>
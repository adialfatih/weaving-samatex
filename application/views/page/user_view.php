<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Manage Data User</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="index.html">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Data User
										</li>
									</ol>
								</nav>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a
										class="btn btn-primary dropdown-toggle no-arrow"
										href="javascript:void(0);"
										role="button"
										data-toggle="modal"
										data-target="#bd-example-modal-lg"
									
									>
										Tambah User
									</a>
									
								</div>
							</div>
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Manage Data User
							</p>
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort">No</th>
										<th>Nama User</th>
										<th>Email</th>
										<th>Hak Akses</th>
										<th>Departement</th>
										<th class="datatable-nosort">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($data_table->result() as $no => $val): ?>
									<tr>
										<td class="table-plus"><?=$no+1;?></td>
										<td><?=$val->nama_user;?></td>
										<td><?=$val->username;?></td>
										<td><?=$val->hak_akses;?></td>
										<td><?=$val->departement;?></td>
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
												<div
													class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
												>
													<a class="dropdown-item" href="javascript:void(0);" onclick="click_edit('<?=$val->id_user;?>','<?=$val->nama_user;?>','<?=$val->username;?>','<?=$val->hak_akses;?>','<?=$val->departement;?>','<?=$val->password;?>')" data-toggle="modal" data-target="#owek2"><i class="dw dw-edit2"></i> Edit</a>
													<a class="dropdown-item" href="javascript:void(0);" onclick="click_delete('<?=$val->nama_user;?>','<?=$val->hak_akses;?>','<?=$val->id_user;?>')" data-toggle="modal" data-target="#Medium-modal"
														><i class="dw dw-delete-3"></i> Delete</a
													>
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
								<div class="modal fade bs-example-modal-lg" id="bd-example-modal-lg"tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg modal-dialog-centered">
										<div class="modal-content">
											<form name="fr1" action="<?=base_url('proses/adduser');?>" method="post" entype="multipart/form-data">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Input Data User
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<div class="modal-body">
												<input type="hidden" name="id_user" value="<?=$sess_id;?>">
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Nama</label>
														<div class="col-sm-12 col-md-10">
															<input class="form-control" type="text" placeholder="Masukan Nama User" name="nama" required />
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Email login</label>
														<div class="col-sm-12 col-md-10">
															<input class="form-control" type="text" placeholder="Masukan email untuk login" name="email" required />
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Password Login</label>
														<div class="col-sm-12 col-md-10">
															<input class="form-control" type="password" placeholder="Masukan password untuk login" name="pass" required />
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Hak Akses</label>
														<div class="col-sm-12 col-md-10">
															<select name="hak" id="hak" class="form-control">
                                                                <option value="">--Pilih Akses User--</option>
                                                                <option value="Admin">Admin</option>
                                                                <option value="Manager">Manager</option>
                                                            </select>
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Departement</label>
														<div class="col-sm-12 col-md-10">
															<select name="dep" id="hak" class="form-control">
                                                                <option value="">--Pilih Departement User--</option>
                                                                <option value="RJS">RJS</option>
                                                                <option value="Pusatex">Pusatex</option>
                                                                <option value="Samatex">Samatex</option>
                                                            </select>
														</div>
													</div>
												
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												<button type="submit" class="btn btn-primary">
													Save changes
												</button>
											</div>
											</form>
										</div>
									</div>
								</div>
<!-- pemisah modal -->
								<div class="modal fade bs-example-modal-lg2" id="owek2"tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg modal-dialog-centered">
										<div class="modal-content">
											<form name="fr1" action="<?=base_url('proses/updateuser');?>" method="post" entype="multipart/form-data">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Update Data User
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<div class="modal-body">
												<input type="hidden" name="id_user" value="<?=$sess_id;?>">
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Nama</label>
														<div class="col-sm-12 col-md-10">
															<input class="form-control" type="text" id="chnama" placeholder="Masukan Nama User" name="nama" required />
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Email login</label>
														<div class="col-sm-12 col-md-10">
															<input class="form-control" type="text" id="chemail" placeholder="Masukan email untuk login" name="email" required />
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Password Login</label>
														<div class="col-sm-12 col-md-10">
															<input class="form-control" type="password" id="chpass" placeholder="Kosongi jika tidak ingin mengganti password" name="pass" />
															<input type="hidden" name="oldpass" id="oldpass">
															<input type="hidden" name="iduser" id="chiduser">
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Hak Akses</label>
														<div class="col-sm-12 col-md-10">
															<select name="hak" id="chhak" class="form-control">
                                                                <option value="">--Pilih Akses User--</option>
                                                                <option value="Admin">Admin</option>
                                                                <option value="Manager">Manager</option>
                                                            </select>
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Departement</label>
														<div class="col-sm-12 col-md-10">
															<select name="dep" id="chdep" class="form-control">
                                                                <option value="">--Pilih Departement User--</option>
                                                                <option value="RJS">RJS</option>
                                                                <option value="Pusatex">Pusatex</option>
                                                                <option value="Samatex">Samatex</option>
                                                            </select>
														</div>
													</div>
												
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												<button type="submit" class="btn btn-primary">
													Save changes
												</button>
											</div>
											</form>
										</div>
									</div>
								</div>
<!-- pemisah modal -->
                                <div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Delete User
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            <form name="fr1" action="<?=base_url('proses/deluser');?>" method="post">
											<div class="modal-body">
												<p>
													Anda akan menghapus user <span id="dinamic_nama">Adi</span> dengan hak akses <span id="dinamic_hak">Manager </span>?
												</p>
                                                <input type="hidden" name="iduser" id="dinamic_id">
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
    function click_delete(nm,hak,id){
        document.getElementById('dinamic_nama').innerHTML = '<strong>'+nm+'</strong>';
        document.getElementById('dinamic_hak').innerHTML = '<strong>'+hak+'</strong>';
        document.getElementById('dinamic_id').value = ''+id;
    }
	function click_edit(id,nama,email,hak,dep,pass){
		document.getElementById('chnama').value = ''+nama;
		document.getElementById('chemail').value = ''+email;
		document.getElementById('oldpass').value = ''+pass;
		document.getElementById('chiduser').value = ''+id;
		if(hak=='Manager'){
			document.getElementById("chhak").options.selectedIndex = 2;
		} else if(hak=='Operator'){
			document.getElementById("chhak").options.selectedIndex = 1;
		} else {
			document.getElementById("chhak").options.selectedIndex = 0;
		}
		if(dep=='RJS'){
			document.getElementById("chdep").options.selectedIndex = 1;
		} else if(dep=='Pusatex'){
			document.getElementById("chdep").options.selectedIndex = 2;
		} else if(dep=='Samatex'){
			document.getElementById("chdep").options.selectedIndex = 3;
		} else {
			document.getElementById("chdep").options.selectedIndex = 0;
		}
	}
</script>
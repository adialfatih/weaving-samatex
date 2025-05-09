<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data Produksi Mesin</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="index.html">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Data Produksi Mesin
										</li>
									</ol>
								</nav>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a class="btn btn-primary dropdown-toggle no-arrow" href="javascript:void(0);" role="button" data-toggle="modal" data-target="#bd-example-modal-lg">
										Tambah Produksi
									</a>
									<a class="btn btn-success dropdown-toggle no-arrow" href="<?=base_url('adm/reportmesin');?>">
										Report
									</a>
									
								</div>
							</div>
						</div>
					</div>
					
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Data Produksi Mesin
							</p>
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
									    <th class="table-plus datatable-nosort">No</th>
										<th>Kode Konstruksi</th>
										<th>Tanggal</th>
										<th>Jumlah Mesin</th>
										<th>Jumlah Produksi</th>
										<th>Lokasi</th>
										<th class="datatable-nosort">Action</th>
									</tr>
								</thead>
								<tbody>
                                    <?php 
                                    if($dt_table->num_rows() > 0){
                                    foreach($dt_table->result() as $n => $val):?>
									<tr>
                                        <td><?=$n+1;?></td>
                                        <td><?=$val->kode_konstruksi;?></td>
                                        <td>
                                            <?php
                                            $ex = explode('-', $val->tanggal_produksi);
                                            $tgl = $ex[2]." ".$bln[$ex[1]]." ".$ex[0]."";
                                            echo $tgl;
                                            ?>
                                        </td>
                                        <td><?=$val->jumlah_mesin;?></td>
                                        <td>
											<?php if(fmod($val->hasil, 1) !== 0.00){
                                                $hasil = number_format($val->hasil,2,',','.');
                                              } else {
                                                $hasil = number_format($val->hasil,0,',','.');
                                              }
											  echo $hasil;
											?>
										</td>
                                        <td><?=$val->lokasi;?></td>
                                        <td>
                                            <div class="dropdown">
												<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="javascript:void(0);" role="button" data-toggle="dropdown" >
													<i class="dw dw-more"></i>
												</a>
												<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list" >
													<a class="dropdown-item" href="javascript:void(0);" onclick="upedit('<?=$val->id_produksi_mc;?>','<?=$val->kode_konstruksi;?>','<?=$val->hasil;?>','<?=$val->jumlah_mesin;?>')" data-toggle="modal" data-target="#editmodal"><i class="dw dw-edit2"></i> Edit</a
													>
													<a class="dropdown-item" href="javascript:void(0);" onclick="dels('<?=$val->id_produksi_mc;?>','<?=$val->kode_konstruksi;?>','<?=$tgl;?>')" data-toggle="modal" data-target="#Medium-modal"><i class="dw dw-delete-3"></i> Delete</a
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
											<form name="fr2" action="<?=base_url('proses/produksimc');?>" method="post" entype="multipart/form-data">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Input Data Produksi Mesin
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<div class="modal-body">
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Tanggal Produksi</label>
														<div class="col-sm-12 col-md-10">
															<input class="form-control" type="date" placeholder="Masukan tanggal produksi" name="dtproduksi" required />
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Kode Konstruksi</label>
														<div class="col-sm-12 col-md-10">
															<div class="autoComplete_wrapper">
																<input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" placeholder="Kode konstruksi" name="kode" required>
															</div>
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Lokasi Produksi</label>
														<div class="col-sm-12 col-md-10">
															<select class="form-control" name="loc" required>
																<option value="">--Pilih lokasi produksi--</option>
																<option value="Samatex">SAMATEX</option>
																<option value="Pusatex">PUSATEX</option>
																<option value="RJS">RINDANG JATI SPINNING</option>
															</select>
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Jumlah Mesin</label>
														<div class="col-sm-12 col-md-10">
															<input
																class="form-control"
																type="number"
																placeholder="Masukan jumlah mesin"
																name="jmlmesin" min="0" oninput="this.value = Math.abs(this.value)"
																required
															/>
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Jumlah Produksi</label>
														<div class="col-sm-12 col-md-10">
															<input
																class="form-control"
																type="text"
																placeholder="Masukan jumlah produksi"
																name="jmlproduksi"
																required
															/>
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
											<form name="fr2" action="<?=base_url('proses/up_produksimc');?>" method="post" entype="multipart/form-data">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Edit Data Produksi Mesin
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<div class="modal-body">
                                                <input type="hidden" id="up_id1" name="id_mesin">
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Kode Konstruksi</label>
														<div class="col-sm-12 col-md-10">
                                                            <input class="form-control" type="text" id="ed_kds" name="kdskons" readonly>
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Jumlah Mesin</label>
														<div class="col-sm-12 col-md-10">
															<input class="form-control" type="number" id="ed_jmlmc"
																placeholder="Masukan jumlah mesin"
																name="jmlmesin" min="0" oninput="this.value = Math.abs(this.value)"
																required
															/>
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Jumlah Produksi</label>
														<div class="col-sm-12 col-md-10">
															<input
																class="form-control" id="ed_jmlpromc"
																type="text"
																placeholder="Masukan jumlah produksi"
																name="jmlproduksi"
																required
															/>
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
													Hapus Data
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<form name="fr2" action="<?=base_url('proses/del_dtmc');?>" method="post">
											<div class="modal-body">
												Anda akan menghapus data produksi mesin tanggal <span id="tglss"></span> dengan kode konstruksi <span id="kodd">900</span>
                                                <input type="hidden" id="koddin" name="idkoddinn">
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
    function dels(id,kd,tgl){
        document.getElementById('kodd').innerHTML = '<strong>'+kd+'</strong>';
        document.getElementById('tglss').innerHTML = '<strong>'+tgl+'</strong>';
        document.getElementById('koddin').value = ''+id;
    }
    function upedit(id,kd,hs,jml){
        document.getElementById('up_id1').value = ''+id;
        document.getElementById('ed_kds').value = ''+kd;
        document.getElementById('ed_jmlmc').value = ''+jml;
        document.getElementById('ed_jmlpromc').value = ''+hs;
    }
</script>
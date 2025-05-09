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
											<a href="index.html">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Data Produksi
										</li>
									</ol>
								</nav>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a class="btn btn-primary dropdown-toggle no-arrow" href="<?=base_url('input-produksi-insgrey');?>" role="button"> Tambah Produksi </a>
									<!-- <a class="btn btn-primary dropdown-toggle no-arrow" href="javascript:void(0);" role="button" data-toggle="modal" data-target="#bd-example-modal-lg" > Tambah Produksi </a> -->
									<a class="btn btn-secondary dropdown-toggle no-arrow" href="<?=base_url('input-produksi-inspect');?>" role="button">Inpect Finish</a>
									<a class="btn btn-dark dropdown-toggle no-arrow" href="<?=base_url('input-produksi-folding');?>" role="button">Folding</a>
								</div>
							</div>
						</div>
					</div>
					
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Data Produksi
							</p>
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
									<th class="table-plus datatable-nosort">No</th>
										<th>No PL</th>
										<th>Kode Konstruksi</th>
										<th>Tanggal Produksi</th>
										<th>Produksi</th>
										<!-- <th>Jumlah Mesin</th> -->
										<th>Inspect Grey</th>
										<th>Inspect Finish</th>
										<th>Folding</th>
										<th class="datatable-nosort">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($data_table->result() as $no => $val): ?>
									<tr><td><?=$no+1;?></td>
									<td class="table-plus"><a href="<?=base_url('data/list/'.sha1($val->kode_produksi));?>"><?=$val->kode_produksi;?></a></td>
										<td><?=$val->kode_konstruksi;?></td>
										<td>
											<?php 
												$ex = explode("-", $val->tgl_produksi);
												echo $ex[2]."-".$arb[$ex[1]]."-".$ex[0]."";
											?>
										</td>
										<td><?=$val->lokasi_produksi;?></td>
										<!-- <td><=$val->jumlah_mesin;?></td> -->
										<td>
											<?php 
												if($val->st_produksi=='IG'){
													if($val->satuan=="Meter"){
														echo number_format($$val->jumlah_produksi_awal, 2);
														echo " <em>m</em>";
													} else {
														echo number_format($val->jumlah_produksi_awal_yard,2);
														echo " <em>y</em>";
													}
												} else {
													echo "0";
												}
											?>
										</td>
										<td>
											<?php 
												if($val->st_produksi=='IF'){
													if($val->satuan=="Meter"){
														echo $val->jumlah_produksi_awal;
														echo " <em>m</em>";
													} else {
														echo $val->jumlah_produksi_awal_yard;
														echo " <em>y</em>";
													}
												} else {
													//cari di proses produksi
													$look = $this->data_model->get_byid('tb_proses_produksi', ['kode_produksi'=>$val->kode_produksi, 'proses_name'=>'IF']);
													if($look->num_rows()==0){
														echo "0";
													} else {
														$ins_fm = 0; $ins_fy = 0;
														$ssatuan = "null";
														foreach ($look->result() as $lk) {
															$ins_fm+=$lk->jumlah_awal;
															$ins_fy+=$lk->jumlah_awal_yard;
															$ssatuan = $lk->satuan;
														}
														if($ssatuan=="Meter"){
															echo $ins_fm;
															echo " <em>m</em>";
														} else {
															echo $ins_fy;
															echo " <em>y</em>";
														}
													}
													
												}
											?>
										</td>
										<td>
											<?php
											//cek foldingan
											if($val->st_produksi=="FF"){
												echo $val->jumlah_produksi_awal_yard." y";
											} elseif ($val->st_produksi=="FG") {
												echo $val->jumlah_produksi_awal." m";
											} else {
												$sfol = $this->data_model->get_byid('tb_proses_produksi', ['kode_produksi'=>$val->kode_produksi, 'proses_name!='=>'IF']);
												$foljum =0; $st=""; $foljuy = 0;
												foreach ($sfol->result() as $ak => $av) {
													$foljum+=$av->jumlah_akhir;
													$foljuy+=$av->jumlah_akhir_yard;
													$st = $av->satuan;
												}
												if($foljum==0){
													echo "0";
												} else {
													//echo $foljum;
													echo $st=='Yard' ? ''.round($foljuy,2).' y':''.round($foljum,2).' m';
												}
											}
											?>
										</td>
										<td>
											<div class="dropdown">
												<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
													<i class="dw dw-more"></i>
												</a>
												<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
													<a class="dropdown-item" href="javascript:void(0);" onclick="send('<?=$val->kode_produksi;?>','<?=$val->kode_konstruksi;?>','<?=$val->tgl_produksi;?>','<?=$val->lokasi_produksi;?>','<?=$val->jumlah_produksi_now;?>','<?=$val->jumlah_produksi_now_yard;?>','Luar')" data-toggle="modal" data-target="#Medium-modal"
														><i class="dw bi bi-send"></i> Kirim Luar</a>
													<?php 
													if($dep_user!='Pusatex') { ?>
													<a class="dropdown-item" href="javascript:void(0);" onclick="send('<?=$val->kode_produksi;?>','<?=$val->kode_konstruksi;?>','<?=$val->tgl_produksi;?>','<?=$val->lokasi_produksi;?>','<?=$val->jumlah_produksi_now;?>','<?=$val->jumlah_produksi_now_yard;?>','Pusatex')" data-toggle="modal" data-target="#Medium-modal"
														><i class="dw bi bi-send"></i> Kirim Pusatex</a
													><?php } if($dep_user!='Samatex') {?>
													<a class="dropdown-item" href="javascript:void(0);" onclick="send('<?=$val->kode_produksi;?>','<?=$val->kode_konstruksi;?>','<?=$val->tgl_produksi;?>','<?=$val->lokasi_produksi;?>','<?=$val->jumlah_produksi_now;?>','<?=$val->jumlah_produksi_now_yard;?>','Samatex')" data-toggle="modal" data-target="#Medium-modal"
														><i class="dw bi bi-send"></i> Kirim Samatex</a
													>
													<?php } if($dep_user!='RJS') { ?>
													<a class="dropdown-item" href="javascript:void(0);" onclick="inspect('<?=$val->kode_produksi;?>')" data-toggle="modal" data-target="#Medium-modal3"
														><i class="dw bi bi-aspect-ratio"></i> Inspect</a
													>
													<a class="dropdown-item" href="javascript:void(0);" onclick="folding('<?=$val->kode_produksi;?>','<?=$val->kode_konstruksi;?>')" data-toggle="modal" data-target="#Medium-modal2"
														><i class="dw bi bi-check2-circle"></i> Folding</a
													><?php if($val->lokasi_produksi!=$dep_user) { ?><a class="dropdown-item" href="javascript:void(0);" onclick="retur('<?=$val->kode_produksi;?>','<?=$val->lokasi_saat_ini;?>')" data-toggle="modal" data-target="#Medium-modal4"
														><i class="dw bi bi-arrow-counterclockwise"></i> Retur</a
													><?php } } ?>
													<a class="dropdown-item" href="<?=base_url('data/produksi/'.sha1($val->kode_produksi));?>"
														><i class="dw bi bi-list-task"></i> View</a
													>
													<a class="dropdown-item" href="javascript:void(0);"
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
					<div class="modal fade bs-example-modal-lg" id="bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg modal-dialog-centered">
										<div class="modal-content">
											<form name="fr2" action="<?=base_url('newpros/newprosproduksi');?>" method="post" entype="multipart/form-data">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Input Data Produksi
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<div class="modal-body">
													<input type="hidden" name="kode_produksi" value="<?=$kode_produksi;?>">
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
														<label class="col-sm-12 col-md-2 col-form-label">Jumlah Produksi</label>
														<div class="col-sm-12 col-md-10">
															<input
																class="form-control"
																type="number"
																placeholder="Masukan jumlah produksi"
																name="jmlproduksi" min="0" step="0.01" oninput="this.value = Math.abs(this.value)"
																required
															/>
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">&nbsp;</label>
														<div class="col-sm-12 col-md-10">
															<div class="custom-control custom-checkbox" style="display:flex;">
																<div>
																<input
																	type="checkbox"
																	class="custom-control-input"
																	id="customCheck1" onclick="inspect()"
																	name="status" value="Inspect"
																/>
																<label class="custom-control-label" for="customCheck1" onclick="inspect_cek()"
																	>Inspect</label
																></div>
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<div>
																<input
																	type="checkbox"
																	class="custom-control-input"
																	id="customCheck2" onclick="folding()"
																	name="status" value="Folding"
																/>
																<label class="custom-control-label" for="customCheck2" onclick="folding_cek()"
																	>Folding</label
																></div>
															</div>
															
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Satuan</label>
														<div class="col-sm-12 col-md-10">
															<select name="satuan" id="satuan" class="form-control">
																<option value="">--Pilih Satuan--</option>
																<option value="Yard">Yard</option>
																<option value="Meter">Meter</option>
															</select>
														</div>
													</div>
													<!-- <div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Jumlah Mesin</label>
														<div class="col-sm-12 col-md-10"> -->
															<input
																class="form-control"
																type="hidden" value="0"
																placeholder="Masukan jumlah mesin"
																name="jmlmesin" min="0" oninput="this.value = Math.abs(this.value)"
																required
															/>
														<!-- </div>
													</div> -->
													<div class="form-group row">
														<label class="col-sm-12 col-md-2 col-form-label">Keterangan</label>
														<div class="col-sm-12 col-md-10">
															<input
																class="form-control"
																type="text"
																placeholder="Masukan keterangan"
																name="ket"
															/>
														</div>
													</div>
												
											</div>
											<div class="modal-footer">
												<button
													type="button"
													class="btn btn-secondary"
													data-dismiss="modal"
												>
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
													Kirim Barang
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<form name="fr2" action="<?=base_url('proses/prosend');?>" method="post">
											<div class="modal-body">
												<input type="hidden" name="kode_produksi" id="dinamic_kdp">
												<input type="hidden" name="lokasi_kirim" id="dinamic_loc2">
												Anda akan mengirim barang ke <span id="dinamic_ke">Samatex</span> dengan data sebagai berikut :
												<table class="table table-bordered">
													<tr>
														<td>Kode Konstruksi</td>
														<td id="dinamic_kdk">082OOPK</td>
													</tr>
													<tr>
														<td>Tanggal Produksi</td>
														<td id="dinamic_tgl">082OOPK</td>
													</tr>
													<tr>
														<td>Lokasi Produksi</td>
														<td id="dinamic_loc">082OOPK</td>
													</tr>
													<tr>
														<td>Jumlah</td>
														<td id="dinamic_jml">082OOPK</td>
													</tr>
													<tr id="tujuanid">
														<td>Tujuan Pengiriman</td>
														<td><input type="text" class="form-control" placeholder="Masukan tujuan pengiriman" name="tujuankirim" id="frtujuanid"></td>
													</tr>
													<tr id="tujuanid2">
														<td>Tanggal Pengiriman</td>
														<td><input type="date" class="form-control" name="tglkirim" id="frtglid"></td>
													</tr>
												</table>
											</div>
											<div class="modal-footer">
												<button
													type="button"
													class="btn btn-secondary"
													data-dismiss="modal"
												>
													Tutup
												</button>
												<button type="submit" class="btn btn-primary">
													Kirim
												</button>
											</div>
											</form>
										</div>
									</div>
								</div>
								<!-- Medium modal 2 -->
								<div class="modal fade" id="Medium-modal2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Proses Folding
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<form name="fr2" action="<?=base_url('newpros/profolding');?>" method="post">
											<div class="modal-body">
												<input type="hidden" name="kode_produksi" id="foding_kdp">
												<input type="hidden" name="kode_konstruksi" id="foding_kons">
												<table class="table table-bordered">
													<tr>
														<td>No Packinglist</td>
														<td id="dinamic_nopac">082OOPK</td>
													</tr>
													<tr>
														<td>Tanggal</td>
														<td><input type="date" name="tgl" class="form-control" required></td>
													</tr>
													<tr>
														<td>Jumlah</td>
														<td><input type="number" name="jumlah" placeholder="Masukan jumlah" min="0" step=".01" oninput="this.value = Math.abs(this.value)" class="form-control" required></td>
													</tr>
													<tr>
														<td>Satuan</td>
														<td>
															<select name="satuan" id="satuan" class="form-control" required>
																<option value="">--Pilih satuan--</option>
																<option value="Yard">Yard</option>
																<option value="Meter">Meter</option>
															</select>
														</td>
													</tr>
													<tr>
														<td>Folding</td>
														<td>
															<select name="st_folding" id="st_folding" class="form-control" required>
																<option value="">--Pilih Folding--</option>
																<option value="Grey">Grey</option>
																<option value="Finish">Finish</option>
															</select>
														</td>
													</tr>
												</table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
												<button type="submit" class="btn btn-primary">Proses</button>
											</div>
											</form>
										</div>
									</div>
								</div>
								<!-- Medium modal 3 -->
								<div class="modal fade" id="Medium-modal3" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Inspect Ulang
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<form name="fr2" action="<?=base_url('newpros/proinspect');?>" method="post">
											<div class="modal-body">
												<input type="hidden" name="kode_produksi" id="foding_kdp2">
												<table class="table table-bordered">
													<tr>
														<td>No Packinglist</td>
														<td id="dinamic_nopac2">082OOPK</td>
													</tr>
													<tr>
														<td>Tanggal</td>
														<td><input type="date" name="tgl" class="form-control" required></td>
													</tr>
													<tr>
														<td>Jumlah</td>
														<td><input type="number" name="jumlah" placeholder="Masukan jumlah" min="0"  step="0.01" oninput="this.value = Math.abs(this.value)" class="form-control" required></td>
													</tr>
													<tr>
														<td>Satuan</td>
														<td>
															<select name="satuan" id="satuan" class="form-control" required>
																<option value="">--Pilih Satuan--</option>
																<option value="Yard">Yard</option>
																<option value="Meter">Meter</option>
															</select>
														</td>
													</tr>
													<tr>
														<td>Inspect</td>
														<td><strong>Finish</strong></td>
													</tr>
												</table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
												<button type="submit" class="btn btn-primary">Proses</button>
											</div>
											</form>
										</div>
									</div>
								</div>
								<!-- Medium modal 4 -->
								<div class="modal fade" id="Medium-modal4" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Retur Barang
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<form name="fr2" action="<?=base_url('proses/proretur');?>" method="post">
											<div class="modal-body">
												<input type="hidden" name="kode_produksi" id="foding_kdp3">
												<input type="hidden" name="loc_now" id="locnow">
												<table class="table table-bordered">
													<tr>
														<td>No Packinglist</td>
														<td id="dinamic_nopac3">082OOPK</td>
													</tr>
													<tr>
														<td>Keterangan</td>
														<td><textarea name="alasan" style="height:90px;" class="form-control" placeholder="Masukan keterangan/alasan retur" required></textarea></td>
													</tr>				
												</table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
												<button type="submit" class="btn btn-primary">Retur</button>
											</div>
											</form>
										</div>
									</div>
								</div>
        </div>
    </div>
</div>
<script>
	function folding_cek(){
		document.getElementById("customCheck1").checked = false;
	}
	function inspect_cek(){
		document.getElementById("customCheck2").checked = false;
	}
	function send(kdp,kdk,tgl,loc,jml,jml2,ke){
		document.getElementById("frtujuanid").value = '';
		document.getElementById("frtglid").value = '';
		document.getElementById("dinamic_ke").innerHTML = '<strong>'+ke+'</strong>';
		document.getElementById("dinamic_kdk").innerHTML = '<strong>'+kdk+'</strong>';
		document.getElementById("dinamic_tgl").innerHTML = '<strong>'+tgl+'</strong>';
		document.getElementById("dinamic_loc").innerHTML = '<strong>'+loc+'</strong>';
		document.getElementById("dinamic_loc2").value = ''+ke;
		document.getElementById("dinamic_jml").innerHTML = '<strong>'+jml+' m / '+jml2+' y</strong>';
		document.getElementById("dinamic_kdp").value = ''+kdp;
		if(ke=='Luar'){
			document.getElementById("tujuanid").style.display = "";
			document.getElementById("tujuanid2").style.display = "";
		} else {
			document.getElementById("tujuanid").style.display = "none";
			document.getElementById("tujuanid2").style.display = "none";
		}
	}
	function folding(kd,kd2) {
		document.getElementById("dinamic_nopac").innerHTML = '<strong>'+kd+'</strong>';
		document.getElementById("foding_kdp").value = ''+kd;
		document.getElementById("foding_kons").value = ''+kd2;
	}
	function inspect(kd) {
		document.getElementById("dinamic_nopac2").innerHTML = '<strong>'+kd+'</strong>';
		document.getElementById("foding_kdp2").value = ''+kd;
	}
	function retur(kd,loc) {
		document.getElementById("dinamic_nopac3").innerHTML = '<strong>'+kd+'</strong>';
		document.getElementById("foding_kdp3").value = ''+kd;
		document.getElementById("locnow").value = ''+loc;
	}
</script>
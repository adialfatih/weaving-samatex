<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data Packaging List</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="Javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="Javascript:void(0);">Data</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Packaging List
										</li>
									</ol>
								</nav>
							</div>
							
							<div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a href="<?=base_url('create-packing-list');?>">
									<button type="button" class="btn btn-primary">
										<i class="icon-copy bi bi-box"></i> &nbsp;&nbsp; Buat Paket
									</button></a>
									<a href="<?=base_url('pengiriman');?>">
									<button type="button" class="btn btn-dark">
										<i class="icon-copy bi bi-truck"></i> &nbsp;&nbsp; Riwayat Pengiriman
										<?php if($notif==0){} else { ?> <span class="notiftruck"><?=$notif;?></span><?php } ?>
									</button></a>
								</div>
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
						<form action="<?=base_url('surat-jalan');?>" name="fr12" id="fr12" method="post">
						<input type="hidden" id="ygdipilih" name="text_kode"></form>
						<table class="data-table table stripe hover nowrap">
							<thead>
								<tr>
                                    <th>No.</th>
                                    <th>Kode</th>
									<th>Konstruksi</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Lokasi Paket</th>
                                    <th>Tujuan Kirim</th>
                                    <th>Jumlah</th>
									<th>Paket</th>
									<th>Action</th>
                                </tr>
							</thead>
							<tbody><?php
                                foreach($dt_list->result() as $n => $val): 
								//if($val->kepada=="NULL" OR $val->kepada==$loc){
								?>
                                <tr>
                                    <td><?=$n+1;?></td>
                                    <td><?=$val->kd;?></td>
                                    <td><?=$val->kode_konstruksi;?></td>
                                    <td><a href="javascript:void(0);" onclick="changTgl('<?=$val->kd;?>')" data-toggle="modal" data-target="#Medium-modal287" style="color:blue;"><?php $ex= explode('-',$val->tanggal_dibuat); echo $ex[2]."-".$bln[$ex[1]]."-".$ex[0];?></a></td>
                                    
                                    <td>
										<?php if($val->kepada == "NULL"){
											echo '<span style="background:red;color:#FFFFFF;font-size:12px;padding:3px 10px;border-radius:5px;">';
											echo $val->lokasi_now;
											echo '</span>';
										} else {
											if($val->kepada == "Pusatex"){
												echo '<span style="background:blue;color:#FFFFFF;font-size:12px;padding:3px 10px;border-radius:5px;">Pusatex</span>';
											} else {
											echo $val->kepada;
											}
										} ?>
									</td>
									<td><a href="javascript:void(0);" onclick="changPenerima('<?=$val->kd;?>','<?=$val->customer;?>')" data-toggle="modal" data-target="#Medium-modal1996" style="color:blue;"><?=$val->customer;?></a></td>
                                    <td>
										(<?=$val->jumlah_roll;?>) <?=number_format($val->ttl_panjang);?>
									</td>
									<!-- <php if($val->siap_jual=='y'){ ?>
									<td>Paket Siap Jual</td>
									<td>
										<a href="<=base_url('kirim/customer/'.sha1($val->kd));?>" title="Kirim ke customer"><i class="icon-copy fa fa-send" aria-hidden="true"></i></a>
										&nbsp;&nbsp;
										<a href="<=base_url('data/kode/'.$val->kd);?>" title="Edit paket"><i class="icon-copy fa fa-edit" aria-hidden="true"></i></a>
										&nbsp;&nbsp;
										<a href="javascript:void(0);" title="Hapus paket" onclick="delpaket('<=$val->kd;?>')"  data-toggle="modal" data-target="#Medium-modal"><i class="icon-copy fa fa-trash-o" style="color:red;" aria-hidden="true"></i></a>
									</td>
									<php } elseif($val->siap_jual=='n') { ?> 
									<td>Proses Produksi</td>
									<td>
										<a href="javascript:void(0);" title="Kirim paket" onclick="sendpaket2('<=$val->kd;?>','<=$n;?>')"><i class="icon-copy fa fa-truck" id="truk<=$n;?>" aria-hidden="true"></i></a>
										 <a href="javascript:void(0);" title="Kirim paket" onclick="sendpaket('<=$val->kd;?>','<=$val->lokasi_now;?>')" data-toggle="modal" data-target="#Medium-modal2"><i class="icon-copy fa fa-truck" aria-hidden="true"></i></a> 
										&nbsp;&nbsp;
										<a href="<=base_url('data/kode/'.$val->kd);?>" title="Edit paket"><i class="icon-copy fa fa-edit" aria-hidden="true"></i></a>
										&nbsp;&nbsp;
										<a href="javascript:void(0);" title="Hapus paket" onclick="delpaket('<=$val->kd;?>')" data-toggle="modal" data-target="#Medium-modal"><i class="icon-copy fa fa-trash-o" style="color:red;" aria-hidden="true"></i></a>
									</td> -->
									<!-- <php } else {
										echo "<td>Terjual</td><td></td>";
									} ?> -->
									<td>
										<?php if($val->siap_jual=="y"){ echo '<span style="background:green;color:#FFFFFF;font-size:12px;padding:3px 10px;border-radius:5px;">Penjualan</span>'; } 
										 	  if($val->siap_jual=="n"){ echo '<span style="background:red;color:#FFFFFF;font-size:12px;padding:3px 10px;border-radius:5px;">Proses</span>'; } 
										 	  if($val->siap_jual=="sold"){ echo "Terjual"; } ?>
									</td>
									<td>
										<?php if($val->siap_jual=="sold"){} else { if($val->kepada=='NULL'){ ?>
										<a href="javascript:void(0);" title="Kirim paket" onclick="sendpaket2('<?=$val->kd;?>','<?=$n;?>')"><i class="icon-copy fa fa-truck" id="truk<?=$n;?>" aria-hidden="true"></i></a>
										<!-- <a href="javascript:void(0);" title="Kirim paket" onclick="sendpaket('<=$val->kd;?>','<=$val->lokasi_now;?>')" data-toggle="modal" data-target="#Medium-modal2"><i class="icon-copy fa fa-truck" aria-hidden="true"></i></a> -->
										
										&nbsp;&nbsp; 
										<a href="javascript:void(0);" title="Hapus paket" onclick="delpaket('<?=$val->kd;?>','<?=$val->siap_jual;?>','<?=$val->lokasi_now;?>')" data-toggle="modal" data-target="#Medium-modal"><i class="icon-copy fa fa-trash-o" style="color:red;" aria-hidden="true"></i></a>
										&nbsp;&nbsp;
										<?php } } if($loc=="RJS"){ ?> 
										<a href="<?=base_url('cetakrjs/packinglist/'.$val->kd.'/1');?>" target="_blank" title="Cetak Packinglist">
											<i class="icon-copy bi bi-printer-fill" style="color:black;" aria-hidden="true"></i>
										</a>
										&nbsp;&nbsp;
										<a href="<?=base_url('data/kode/'.$val->kd);?>" title="Edit paket"><i class="icon-copy fa fa-edit" aria-hidden="true"></i></a>
										<?php } else { 
											
										?>
										<a href="<?=base_url('cetakstx/packinglist/'.$val->kd.'/1');?>" target="_blank" title="Cetak Packinglist">
											<i class="icon-copy bi bi-printer-fill" style="color:black;" aria-hidden="true"></i>
										</a>
										&nbsp;&nbsp;
										<a href="<?=base_url('data/kode/'.$val->kd);?>" title="Edit paket"><i class="icon-copy fa fa-edit" aria-hidden="true"></i></a>
										<?php } ?>
									</td>
									
                                </tr>
                                <?php  endforeach; ?>
							</tbody>
						</table>
						<div class="text-right" style="margin-top:20px; display:none;" id="btnsendoke">
						<button type="button" class="btn btn-primary" onclick="submitfr()">
							<i class="icon-copy bi bi-truck"></i> &nbsp;&nbsp; Kirim Barang
						</button></div>
						</div>
					</div>

					<!-- basic table  End -->
					<!-- basic table  Start -->
					<div class="pd-20 card-box mb-30">
						<div class="clearfix mb-20">
							<div class="pull-left">
								<h4 class="h4">Data Packaging List Kiriman</h4>
							</div>
						</div>
						<div class="table-responsive">
						<form action="<?=base_url('surat-jalan');?>" name="fr12" id="fr12" method="post">
						<input type="hidden" id="ygdipilihwew" name="text_kode"></form>
						<table class="data-table table stripe hover nowrap">
							<thead>
								<tr>
                                    <th>No.</th>
                                    <th>Kode PKG</th>
									<th>Konstruksi</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Lokasi Sekarang</th>
                                    <th>Jumlah Paket</th>
									<th>Jenis Paket</th>
									<th>Action</th>
                                </tr>
							</thead>
							<tbody><?php
								$qur = $this->db->query("SELECT * FROM new_tb_packinglist WHERE kepada='$dep'");
                                foreach($qur->result() as $n => $val): 
								//if($val->kepada=="NULL" OR $val->kepada==$loc){
								?>
                                <tr>
                                    <td><?=$n+1;?></td>
                                    <td><?=$val->kd;?></td>
                                    <td><?=$val->kode_konstruksi;?></td>
                                    <td><?php $ex= explode('-',$val->tanggal_dibuat); echo "".$ex[2]."-".$bln[$ex[1]]."-".$ex[0]."";?></td>
                                    
                                    <td><?=$val->kepada=='NULL' ? $val->lokasi_now:$val->kepada;?></td>
                                    <td>
										<?=$val->jumlah_roll;?> Roll
									</td>
									<td>
										<?php if($val->siap_jual=="y"){ echo "Paket Siap Jual"; } 
										 	  if($val->siap_jual=="n"){ echo "Proses Produksi"; } 
										 	  if($val->siap_jual=="sold"){ echo "Terjual"; } ?>
									</td>
									<td>
										<?php if($val->siap_jual=="sold"){} else { if($val->kepada=='NULL'){ ?>
										<a href="javascript:void(0);" title="Kirim paket" onclick="sendpaket2('<?=$val->kd;?>','<?=$n;?>')"><i class="icon-copy fa fa-truck" id="truk<?=$n;?>" aria-hidden="true"></i></a>
										<!-- <a href="javascript:void(0);" title="Kirim paket" onclick="sendpaket('<=$val->kd;?>','<=$val->lokasi_now;?>')" data-toggle="modal" data-target="#Medium-modal2"><i class="icon-copy fa fa-truck" aria-hidden="true"></i></a> -->
										&nbsp;&nbsp;
										<a href="<?=base_url('data/kode/'.$val->kd);?>" title="Edit paket"><i class="icon-copy fa fa-edit" aria-hidden="true"></i></a>
										&nbsp;&nbsp; 
										<a href="javascript:void(0);" title="Hapus paket" onclick="delpaket('<?=$val->kd;?>','<?=$val->siap_jual;?>','<?=$val->lokasi_now;?>')" data-toggle="modal" data-target="#Medium-modal"><i class="icon-copy fa fa-trash-o" style="color:red;" aria-hidden="true"></i></a>
										&nbsp;&nbsp;
										<?php } } //if($loc=="RJS"){ ?> 
										<a href="<?=base_url('cetakrjs/packinglist/'.$val->kd.'/1');?>" target="_blank" title="Cetak Packinglist">
											<i class="icon-copy bi bi-printer-fill" style="color:black;" aria-hidden="true"></i>
										</a>
										<!-- <php //} else { ?> -->
										<!-- <a href="<=base_url('cetak/packinglist/'.$val->kd.'/1');?>" target="_blank" title="Cetak Packinglist">
											<i class="icon-copy bi bi-printer-fill" style="color:black;" aria-hidden="true"></i>
										</a> 
										<php //} ?>-->
									</td>
									
                                </tr>
                                <?php  endforeach; ?>
							</tbody>
						</table>
						<div class="text-right" style="margin-top:20px; display:none;" id="btnsendoke">
						<button type="button" class="btn btn-primary" onclick="submitfr()">
							<i class="icon-copy bi bi-truck"></i> &nbsp;&nbsp; Kirim Barang
						</button></div>
						</div>
					</div>

					<!-- basic table  End -->
				<!-- pemisah modal -->
								<div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Delete Paket
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            <form name="fr1" action="<?=base_url('newpros/delpaket');?>" method="post">
											<div class="modal-body">
												<p>
													Anda akan menghapus Packaginglist dengan nomor paket <span id="dinamic_nama">Adi</span>? Roll di dalam paket akan di keluarkan dari packaging list.
												</p>
                                                <input type="hidden" name="id" id="dinamic_id">
                                                <input type="hidden" name="jnspaket" id="dinamic_jnspkt">
                                                <input type="hidden" name="loc" id="dinamic_loc">
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
												<button type="submit" class="btn btn-danger">
													Yes, Delete
												</button>
											</div>
                                            </form>
										</div>
									</div>
								</div>
								<!-- pemisah modal -->
								<div class="modal fade" id="Medium-modal2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Kirim Paket
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            <form name="fr1" action="<?=base_url('kirim/kepabrik');?>" method="post">
											<div class="modal-body">
												<table class="table">
													<tr>
														<td>Nomor Paket</td>
														<td><input type="text" class="form-control" name="kd" id="sendid" readonly></td>
													</tr>
													<tr>
														<td>Lokasi Sekarang</td>
														<td><input type="text" class="form-control" name="locnow" id="locidnow" readonly></td>
													</tr>
													<tr>
														<td>Lokasi Tujuan</td>
														<td>
															<select name="loctuj" id="loctuj" class="form-control">
																<option value="">--Pilih Tujuan--</option>
																<option value="Samatex">Samatex</option>
																<option value="Pusatex">Pusatex</option>
																<option value="RJS">RJS</option>
															</select>
														</td>
													</tr>
												</table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
												<button type="submit" class="btn btn-primary">
													Kirim Paket
												</button>
											</div>
                                            </form>
										</div>
									</div>
								</div>
								<!-- pemisah modal -->
								<div class="modal fade" id="Medium-modal287" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Update Tanggal Paket
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            <form name="fr1" action="<?=base_url('beranda/chTanggal');?>" method="post">
											<div class="modal-body">
												<table class="table">
													<tr>
														<td>Kode Paket</td>
														<td><input type="text" class="form-control" name="kd" id="sendidmk98" readonly></td>
													</tr>
													<tr>
														<td>Tanggal</td>
														<td><input type="date" class="form-control" name="tgl" id="locidnowmkos9" value="<?=date('Y-m-d');?>"></td>
													</tr>
												</table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
												<button type="submit" class="btn btn-success">
													Update Tanggal
												</button>
											</div>
                                            </form>
										</div>
									</div>
								</div>
								<!-- pemisah modal -->
								<div class="modal fade" id="Medium-modal1996" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Update Penerima Paket
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            <form name="fr1" action="<?=base_url('beranda/chPenerimaPaket');?>" method="post">
											<div class="modal-body">
												<table class="table">
													<tr>
														<td>Kode Paket</td>
														<td><input type="text" class="form-control" name="kd" id="sendidmk1872" readonly></td>
													</tr>
													<tr>
														<td>Tujuan Kirim</td>
														<td><input type="text" class="form-control" name="pnr" id="locidnowmkos98923"></td>
													</tr>
												</table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
												<button type="submit" class="btn btn-success">
													Update Paket
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
	function delpaket(pk,jns,loc){
		document.getElementById('dinamic_nama').innerHTML = '<strong>'+pk+'</strong>';
		document.getElementById('dinamic_id').value = ''+pk;
		document.getElementById('dinamic_jnspkt').value = ''+jns;
		document.getElementById('dinamic_loc').value = ''+loc;
	}
	function sendpaket(pk,loc){
		document.getElementById('sendid').value = ''+pk;
		document.getElementById('locidnow').value = ''+loc;
	}
	function sendpaket2(kdpkt,num){
		var box = document.getElementById('ygdipilih').value;
		if(box.includes(''+kdpkt)==false){
			document.getElementById('truk'+num+'').style = 'color:blue';
			document.getElementById('ygdipilih').value = ''+box+''+kdpkt+',';
			oke();
		} else {
			document.getElementById('truk'+num+'').style = 'color:grey';
			var newStr = box.replace(''+kdpkt+',','');
			document.getElementById('ygdipilih').value = ''+newStr;
			oke();
		}
		
	}
	function oke(){
		var box = document.getElementById('ygdipilih').value;
		if(box==''){
			document.getElementById('btnsendoke').style = 'margin-top:20px; display:none;';
		} else {
			document.getElementById('btnsendoke').style = 'margin-top:20px; display:block;';
		}
	}
	function submitfr(){
		document.getElementById("fr12").submit();
	}
	function changTgl(kd){
		document.getElementById('sendidmk98').value = ''+kd;
	}
	function changPenerima(kd,pnm){
		document.getElementById('sendidmk1872').value = ''+kd;
		document.getElementById('locidnowmkos98923').value = ''+pnm;
	}
</script>
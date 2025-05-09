<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data Produksi </h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Data</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Produksi</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Proses
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<?php
						$cekupload = $this->data_model->get_byid('log_input_roll', ['kodeauto'=>$acakkode]);
						if($cekupload->num_rows() == 0){
							echo '<div class="alert alert-success" role="alert">Aplikasi siap untuk memproses data.</div>';
						} else {
							echo '<div class="alert alert-danger" role="alert">PERINGATAN..!! Anda perlu merefresh halaman ini. tekan F5</div>';
						}
					?>
					<!-- Simple Datatable start -->
					<!-- Bootstrap Select End -->
					<div class="row">
						<!-- Bootstrap Switchery Start -->
						<div class="col-md-6 col-sm-12 mb-30">
							<div class="pd-20 card-box height-100-p">
								<div class="clearfix mb-30">
									<div class="pull-left">
										<h4 class="text-blue h4">Inspect Grey</h4>
									</div>
								</div>
                                <?php echo form_open_multipart('importinsgrey/import_insgrey2',array('name' => 'spreadsheet')); ?>
								<input type="hidden" name="kodeauto" value="<?=$acakkode;?>">
								<table class="table">
                                    <tr>
                                        <td>Upload File</td>
                                        <td><input type="file" name="upload_file" class="form-control" placeholder="Upload file excel" required>
                                        <small>Jika anda belum memiliki template anda bisa download <a href="<?=base_url('phpsp/exportinsgrey');?>" target="_blank" style="color:blue;">disini</a></small></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <button type="submit" class="btn" data-bgcolor="#3f72d1" data-color="#ffffff">
                                                <span class="icon-copy ti-save"></span> &nbsp; Proses
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                                <?php echo form_close();?>
							</div>
						</div>
						<!-- Bootstrap Switchery End -->
						<!-- Bootstrap Switchery Start -->
						<div class="col-md-6 col-sm-12 mb-30">
							<div class="pd-20 card-box height-100-p">
								<div class="clearfix mb-30">
									<div class="pull-left">
										<h4 class="text-blue h4">Inspect Finish</h4>
									</div>
								</div><!--asline phpsq2 -->
                                <?php echo form_open_multipart('phpsq2/new_import_if2',array('name' => 'spreadsheet')); ?>
								<input type="hidden" name="kodeauto" value="<?=$acakkode;?>">
								<table class="table">
                                    <tr>
                                        <td>Upload File</td>
                                        <td><input type="file" name="upload_file" class="form-control" placeholder="Upload file excel" required>
                                        <small>Jika anda belum memiliki template anda bisa download <a href="<?=base_url('phpsp/import_newprosif');?>" target="_blank" style="color:blue;">disini</a></small></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <button type="submit" class="btn" data-bgcolor="#3f72d1" data-color="#ffffff">
                                                <span class="icon-copy ti-save"></span> &nbsp; Proses Inspect
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                                <?php echo form_close();?>
							</div>
						</div>
						<!-- Bootstrap Switchery End -->
						<!-- Bootstrap Tags Input Start -->
						<div class="col-md-6 col-sm-12 mb-30">
							<div class="pd-20 card-box height-100-p">
								<div class="clearfix mb-30">
									<div class="pull-left">
										<h4 class="text-blue h4">Folding</h4>
									</div>
								</div><!--asline phpsq2 -->
								<?php echo form_open_multipart('phpsq2/new_import_fol2',array('name' => 'spreadsheet')); ?>
								<input type="hidden" name="kodeauto" value="<?=$acakkode;?>">
								<table class="table">
                                    <tr>
                                        <td>Upload File</td>
                                        <td><input type="file" name="upload_file" class="form-control" placeholder="Upload file excel" required>
                                        <small>Jika anda belum memiliki template anda bisa download <a href="<?=base_url('phpsq/import_newprosfol');?>" target="_blank" style="color:blue;">disini</a></small></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <button type="submit" class="btn" data-bgcolor="#3f72d1" data-color="#ffffff">
                                                <span class="icon-copy ti-save"></span> &nbsp; Proses Folding
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                                <?php echo form_close();?>
							</div>
						</div>
						<!-- Bootstrap Tags Input End -->
						<!-- Bootstrap Tags Input Start -->
						<div class="col-md-6 col-sm-12 mb-30">
							<div class="pd-20 card-box height-100-p">
								<div class="clearfix mb-30">
									<div class="pull-left">
										<h4 class="text-blue h4">JOIN KODE ROLL</h4>
									</div>
								</div>
								<form name="fr1" action="<?=base_url('proses/join');?>" method="post">
								<table class="table">
                                    <tr>
                                        <td>Join Kode Roll</td>
                                        <td><input type="text" name="koderolling" class="form-control" placeholder="Masukan Kode Roll yang akan di gabung" required><small>Pisahkan dengan koma. Ex: <em>S100A,S230B,R99C</em></small>
                                        </td>
                                    </tr>
									<tr>
                                        <td>Ukuran Akhir</td>
                                        <td><input type="text" name="ukrakhir" class="form-control" placeholder="Jumlah ukuran akhir" required>
                                        </td>
                                    </tr>
									<tr>
                                        <td>Satuan</td>
                                        <td>
											<select name="satuan" id="satuan" class="form-control" required>
												<option value="">Pilih Satuan</option>
												<option value="Meter">Meter</option>
												<option value="Yard">Yard</option>
											</select>
                                        </td>
                                    </tr>
									<tr>
                                        <td>Kode Akhir</td>
                                        <td><input type="text" name="kdakhir" id="inputnewkode" class="form-control" placeholder="Kode Akhir" required><small id="newcode" style="display:none;"><code>Kode sudah digunakan</code></small>
                                        </td>
                                    </tr>
									<tr>
                                        <td>Tanggal Join</td>
                                        <td><input type="date" name="tgljoin" class="form-control">
                                        </td>
                                    </tr>
									<tr id="idadaeorr" style="display:none;">
										<td colspan="2" id="adaerorr">
											<code>Ada yang erorr</code>
										</td>
									</tr>
									<tr>
                                        <td></td>
                                        <td>
                                            <button type="submit" class="btn" data-bgcolor="#3f72d1" data-color="#ffffff">
                                                <span class="icon-copy ti-save"></span> &nbsp; Join Roll
                                            </button>
                                        </td>
                                    </tr>
                                </table>
								</form>
							</div>
						</div>
						
					</div>
                    <!-- Bootstrap Select End -->
					<div class="row">
						<!-- Bootstrap Switchery Start -->
						<div class="col-md-6 col-sm-12 mb-30">
							<div class="pd-20 card-box height-100-p">
								<div class="clearfix mb-30">
									<div class="pull-left">
										<h4 class="h4" style="color:red;">Reverse Produksi</h4>
									</div>
								</div>
                                <?php echo form_open_multipart('reverse/produksi',array('name' => 'spreadsheet')); ?>
								<table class="table">
                                    <tr>
                                        <td>Pilih Produksi</td>
                                        <td>
											<select name="reverse" id="rvre" class="form-control" required>
												<option value="">--Pilih Produksi--</option>
												<option value="ig">Inspect Grey</option>
												<option value="fg">Folding Grey</option>
												<option value="iff">Inspect Finish</option>
												<option value="ff">Folding Finish</option>
											</select>
										</td>
                                    </tr>
									<tr>
                                        <td>Tanggal Produksi</td>
                                        <td>
											<input type="date" class="form-control" name="tgl" required>
										</td>
                                    </tr>
									<tr>
										<td colspan="2">
											<strong>Note : </strong> proses ini akan menghapus semua produksi dari tanggal yang di pilih dan mengurangi stok data yang telah masuk di tanggal tersebut.
										</td>
									</tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <button type="submit" class="btn" data-bgcolor="#3f72d1" data-color="#ffffff">
                                                <span class="icon-copy ti-save"></span> &nbsp; Proses
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                                <?php echo form_close();?>
							</div>
						</div>
						<!-- Bootstrap Switchery Start -->
						<div class="col-md-6 col-sm-12 mb-30">
							<div class="pd-20 card-box height-100-p">
								<div class="clearfix mb-30">
									<div class="pull-left">
										<h4 class="h4" style="color:#000;">Tracking Produksi</h4>
									</div>
								</div>
                                <?php echo form_open_multipart('tracking-produksi',array('name' => 'spreadsheet')); ?>
								<table class="table">
                                    <tr>
                                        <td>Pilih Produksi</td>
                                        <td>
											<select name="produksi" id="rvre" class="form-control" required>
												<option value="">--Pilih Produksi--</option>
												<option value="ig">Inspect Grey</option>
												<option value="fg">Folding Grey</option>
												<option value="iff">Inspect Finish</option>
												<option value="ff">Folding Finish</option>
											</select>
										</td>
                                    </tr>
									<tr>
                                        <td>Tanggal Produksi</td>
                                        <td>
											<input type="date" class="form-control" name="tgl" required>
										</td>
                                    </tr>
									<tr>
										<td colspan="2">
											<strong>Note : </strong> proses ini akan melihat tracking produksi berdasarkan produksi dan tanggal yang anda pilih. Sistem akan menampilkan proses baik sebelumnya ataupun proses sesudahnya.
										</td>
									</tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <button type="submit" class="btn" data-bgcolor="#3f72d1" data-color="#ffffff">
                                                <span class="icon-copy ti-save"></span> &nbsp; Proses
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                                <?php echo form_close();?>
							</div>
						</div>
						
						
					</div>
					
        </div>
    </div>
</div>

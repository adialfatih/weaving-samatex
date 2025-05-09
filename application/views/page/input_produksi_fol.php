<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
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
											<a href="javascript:void(0);">Data</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Input Produksi</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Folding
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
								Data Folding
							</p><small>Upload data folding stok lama yang tidak memiliki kode.</small>
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                                <?php echo form_open_multipart('phpsp/importfol',array('name' => 'spreadsheet')); ?>
                                    <!-- <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Kode Produksi</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:200px;" type="text" value="<=$kdp;?>" name="kdp" readonly>
                                        </div>
                                    </div> -->
                                    <!-- <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Jenis Folding</label>
                                        <div class="col-sm-12 col-md-10">
                                            <select name="fol" id="folid" class="form-control" style="width:300px;" required>
                                                <option value="">-Pilih-</option>
                                                <option value="FG">Folding Grey</option>
                                                <option value="FF">Folding Finish</option>
                                            </select>
                                        </div>
                                    </div> -->
                                    <!-- <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Tanggal Produksi</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:200px;" placeholder="Tanggal produksi" type="date" name="tgl" required>
                                        </div>
                                    </div> -->
                                    <!-- <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Konstruksi</label>
                                        <div class="col-sm-12 col-md-10">
                                            <div class="autoComplete_wrapper">
												<input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" placeholder="Kode konstruksi" name="kode" required>
											</div>
                                        </div>
                                    </div> -->
                                    <!-- <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Lokasi Produksi</label>
                                        <div class="col-sm-12 col-md-10">
                                            <select name="loc" id="loc" class="form-control" style="width:300px;" required>
                                                <option value="">Pilih lokasi produksi</option>
                                                <option value="RJS">RJS</option>
                                                <option value="Samatex">SAMATEX</option>
                                                <option value="Pusatex">PUSATEX</option>
                                            </select>
                                        </div>
                                    </div> -->
                                    <!-- <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Jumlah Produksi</label>
                                        <div class="col-sm-12 col-md-10" style="display:flex;align-items:center;">
                                            <input class="form-control" style="width:200px;" placeholder="Jumlah produksi" type="text" name="jumlah" required>
                                            &nbsp;&nbsp; <small id="satuanid"></small>
                                        </div>
                                    </div> -->
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">List</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:350px;" placeholder="Upload List" type="file" name="upload_file" required>
                                        </div>
                                    </div>
                                    <hr>
                                    <a href="<?=base_url('phpsp/exportfol/');?>">
                                        <button type="button" class="btn" data-bgcolor="#046e16" data-color="#ffffff">
                                            <i class="icon-copy fa fa-file-excel-o" aria-hidden="true"></i> &nbsp; Download List Template
                                        </button>
                                    </a>
                                        <button type="submit" class="btn" data-bgcolor="#3f72d1" data-color="#ffffff">
                                            <span class="icon-copy ti-save"></span> &nbsp; Simpan
                                        </button>
                                <?php echo form_close();?>
                            </div>
						</div>
					</div>
					<!-- Simple Datatable End -->
					
        </div>
    </div>
</div>

<?php $arb = [ '00'=>'undefined', '01' => 'Jan', '02' => 'Feb', '03' => 'Mar','04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des']; ?>
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
											<a href="#">Home</a>
										</li>
										<li class="breadcrumb-item">
											<a href="#">Data Produksi</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Export To Excel
										</li>
									</ol>
									</ol>
								</nav>
							</div>
							
						</div>
					</div>
					<!-- Simple Datatable start -->
                    <div class="pd-20 card-box mb-30">
						<div class="clearfix">
							<div class="pull-left">
								<h4 class="text-blue h4">Export To Excel</h4>
								<p class="mb-30">Export Data Produksi Ke File Excel</p>
							</div>
						</div>
                        <?php echo form_open_multipart('phpsq2023/exportProduksi',array('name' => 'spreadsheet')); ?>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Pilih Produksi</label>
										<select
											class="custom-select2 form-control"
											name="state"
											style="width: 100%; height: 38px"
                                            required
										>
											<optgroup label="Rindang Jati">
												<option value="RJS_IG">RJS - Inpect Grey</option>
											</optgroup>
											<optgroup label="Samatex">
												<option value="SMT_IG">Samatex - Inspect Grey</option>
												<option value="SMT_IF">Samatex - Inppect Finish</option>
												<option value="SMT_FG">Samatex - Folding Grey</option>
												<option value="SMT_FF">Samatex - Folding Finish</option>
											</optgroup>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Pilih Operator (Bisa Lebih dari 1)</label>
										<select
											class="custom-select2 form-control"
											multiple="multiple"
                                            name="opt[]"
											style="width: 100%"
                                            required
										>
											<optgroup label="Operator Samatex">
                                                <?php foreach($optsmt->result() as $val): ?>
												<option value="<?=$val->username;?>"><?=ucfirst($val->username);?></option>
                                                <?php endforeach; ?>
											</optgroup>
											<optgroup label="Operator Rindang Jati">
                                                <?php foreach($optrjs->result() as $val): ?>
												<option value="<?=$val->username;?>"><?=ucfirst($val->username);?></option>
                                                <?php endforeach; ?>
											</optgroup>
										</select>
									</div>
								</div>
							</div>
                            <div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Tanggal Produksi</label>
										<input type="text" class="form-control" name="datesr" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<button class="btn btn-success">Export Data</button>
									</div>
								</div>
							</div>
                        <?php echo form_close();?>
					</div>
					<!-- Select-2 end -->
					
					
        </div>
    </div>
</div>
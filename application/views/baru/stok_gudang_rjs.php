<?php $arb = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar','04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des']; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data Stok Gudang</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="index.html">Data</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Stok Gudang
										</li>
									</ol>
								</nav>
							</div>
							<!-- <div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a class="btn btn-primary dropdown-toggle no-arrow" href="<=base_url('proses-produksi');?>" role="button"> Tambah Produksi </a>
									
								</div>
							</div> -->
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
							<strong>Stok Gudang (Baru)</strong>
							</p>
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
									<th class="table-plus datatable-nosort">No</th>
										<th>Departement</th>
										<th>Konstruksi</th>
										<th>Inspect Grey</th>
										<th>Folding Grey</th>
										<th>Inspect Finish</th>
										<th>Folding Finish</th>
										<th>BS</th>
										<th>BP</th>
										<th>CRT</th>
									</tr>
								</thead>
								<tbody>
                                <?php $no=1; foreach($tbdata->result() as $n => $val):
									
									?>
									<tr>
										<td><?=$no;?></td>
										<td><?=$val->dep=="newRJS" ?"RJS":$val->dep;?></td>
										<td><?=$val->kode_konstruksi;?></td>
										<td>
											<?php 
												if(fmod($val->prod_ig, 1) !== 0.00){
													$stok_ig = number_format($val->prod_ig,2,',','.');
												} else {
													$stok_ig = number_format($val->prod_ig,0,',','.');
												}
												echo $stok_ig;
											?>
										</td>
										<td>
											<?php 
												if(fmod($val->prod_fg, 1) !== 0.00){
													$stok_fg = number_format($val->prod_fg,2,',','.');
												} else {
													$stok_fg = number_format($val->prod_fg,0,',','.');
												}
												echo $stok_fg;
											?>
										</td>
										<td>
											<?php 
												if(fmod($val->prod_if, 1) !== 0.00){
													$stok_if = number_format($val->prod_if,2,',','.');
												} else {
													$stok_if = number_format($val->prod_if,0,',','.');
												}
												echo $stok_if;
											?>
										</td>
										<td>
											<?php 
												if(fmod($val->prod_ff, 1) !== 0.00){
													$stok_ff = number_format($val->prod_ff,2,',','.');
												} else {
													$stok_ff = number_format($val->prod_ff,0,',','.');
												}
												echo $stok_ff;
											?>
										</td>
										<td>
											<?php 
												$bs = floatval($val->prod_bs1) + floatval($val->prod_bs2);
												if(fmod($bs, 1) !== 0.00){
													$printbs = number_format($bs,2,',','.');
												} else {
													$printbs = number_format($bs,0,',','.');
												}
												echo $printbs;
											?>
										</td>
										<td>
											<?php 
												$bp = floatval($val->prod_bp1) + floatval($val->prod_bp2);
												if(fmod($bs, 1) !== 0.00){
													$printbp = number_format($bp,2,',','.');
												} else {
													$printbp = number_format($bp,0,',','.');
												}
												echo $printbp;
											?>
										</td>
										<td>
											<?php 
												if(fmod($val->crt, 1) !== 0.00){
													$crt = number_format($val->crt,2,',','.');
												} else {
													$crt = number_format($val->crt,0,',','.');
												}
												echo $crt;
											?>
										</td>
									</tr>
									<?php $no++;  endforeach; ?>
								</tbody>
							</table>
						</div>
						
					</div>
					<!-- Simple Datatable End -->
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<!-- pemisah table -->
						<div class="pd-20">
							<p class="mb-0">
								<strong>Stok milik Rindang di gudang Samatex</strong>
							</p>
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
									<th class="table-plus datatable-nosort">No</th>
										<th>Konstruksi</th>
										<th>Inspect Grey</th>
										<th>Folding Grey</th>
										<th>Inspect Finish</th>
										<th>Folding Finish</th>
										<th>BS</th>
										<th>BP</th>
										<th>CRT</th>
									</tr>
								</thead>
								<tbody>
                                <?php 
								$qr1 = $this->db->query("SELECT * FROM data_stok WHERE dep='rjsToSamatex'");
								$no=1; foreach($qr1->result() as $n => $val):
									
									?>
									<tr>
										<td><?=$no;?></td>
										<td><?=$val->kode_konstruksi;?></td>
										<td>
											<?php 
												if(fmod($val->prod_ig, 1) !== 0.00){
													$stok_ig = number_format($val->prod_ig,2,',','.');
												} else {
													$stok_ig = number_format($val->prod_ig,0,',','.');
												}
												echo $stok_ig;
											?>
										</td>
										<td>
											<?php 
												if(fmod($val->prod_fg, 1) !== 0.00){
													$stok_fg = number_format($val->prod_fg,2,',','.');
												} else {
													$stok_fg = number_format($val->prod_fg,0,',','.');
												}
												echo $stok_fg;
											?>
										</td>
										<td>
											<?php 
												if(fmod($val->prod_if, 1) !== 0.00){
													$stok_if = number_format($val->prod_if,2,',','.');
												} else {
													$stok_if = number_format($val->prod_if,0,',','.');
												}
												echo $stok_if;
											?>
										</td>
										<td>
											<?php 
												if(fmod($val->prod_ff, 1) !== 0.00){
													$stok_ff = number_format($val->prod_ff,2,',','.');
												} else {
													$stok_ff = number_format($val->prod_ff,0,',','.');
												}
												echo $stok_ff;
											?>
										</td>
										<td>
											<?php 
												$bs = floatval($val->prod_bs1) + floatval($val->prod_bs2);
												if(fmod($bs, 1) !== 0.00){
													$printbs = number_format($bs,2,',','.');
												} else {
													$printbs = number_format($bs,0,',','.');
												}
												echo $printbs;
											?>
										</td>
										<td>
											<?php 
												$bp = floatval($val->prod_bp1) + floatval($val->prod_bp2);
												if(fmod($bs, 1) !== 0.00){
													$printbp = number_format($bp,2,',','.');
												} else {
													$printbp = number_format($bp,0,',','.');
												}
												echo $printbp;
											?>
										</td>
										<td>
											<?php 
												if(fmod($val->crt, 1) !== 0.00){
													$crt = number_format($val->crt,2,',','.');
												} else {
													$crt = number_format($val->crt,0,',','.');
												}
												echo $crt;
											?>
										</td>
									</tr>
									<?php $no++;  endforeach; ?>
								</tbody>
							</table>
						</div>
						<!-- pemsiah tabel -->
					</div>
					<!-- Simple Datatable End -->
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<!-- pemisah table -->
						<div class="pd-20">
							<p class="mb-0">
								<strong>Stok milik Rindang di gudang Pusatex</strong>
							</p>
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
									<th class="table-plus datatable-nosort">No</th>
										<th>Konstruksi</th>
										<th>Inspect Grey</th>
										<th>Folding Grey</th>
										<th>Inspect Finish</th>
										<th>Folding Finish</th>
										<th>BS</th>
										<th>BP</th>
										<th>CRT</th>
									</tr>
								</thead>
								<tbody>
                                <?php 
								$qr1 = $this->db->query("SELECT * FROM data_stok WHERE dep='rjsToPusatex'");
								$no=1; foreach($qr1->result() as $n => $val):
									
									?>
									<tr>
										<td><?=$no;?></td>
										<td><?=$val->kode_konstruksi;?></td>
										<td>
											<?php 
												if(fmod($val->prod_ig, 1) !== 0.00){
													$stok_ig = number_format($val->prod_ig,2,',','.');
												} else {
													$stok_ig = number_format($val->prod_ig,0,',','.');
												}
												echo $stok_ig;
											?>
										</td>
										<td>
											<?php 
												if(fmod($val->prod_fg, 1) !== 0.00){
													$stok_fg = number_format($val->prod_fg,2,',','.');
												} else {
													$stok_fg = number_format($val->prod_fg,0,',','.');
												}
												echo $stok_fg;
											?>
										</td>
										<td>
											<?php 
												if(fmod($val->prod_if, 1) !== 0.00){
													$stok_if = number_format($val->prod_if,2,',','.');
												} else {
													$stok_if = number_format($val->prod_if,0,',','.');
												}
												echo $stok_if;
											?>
										</td>
										<td>
											<?php 
												if(fmod($val->prod_ff, 1) !== 0.00){
													$stok_ff = number_format($val->prod_ff,2,',','.');
												} else {
													$stok_ff = number_format($val->prod_ff,0,',','.');
												}
												echo $stok_ff;
											?>
										</td>
										<td>
											<?php 
												$bs = floatval($val->prod_bs1) + floatval($val->prod_bs2);
												if(fmod($bs, 1) !== 0.00){
													$printbs = number_format($bs,2,',','.');
												} else {
													$printbs = number_format($bs,0,',','.');
												}
												echo $printbs;
											?>
										</td>
										<td>
											<?php 
												$bp = floatval($val->prod_bp1) + floatval($val->prod_bp2);
												if(fmod($bs, 1) !== 0.00){
													$printbp = number_format($bp,2,',','.');
												} else {
													$printbp = number_format($bp,0,',','.');
												}
												echo $printbp;
											?>
										</td>
										<td>
											<?php 
												if(fmod($val->crt, 1) !== 0.00){
													$crt = number_format($val->crt,2,',','.');
												} else {
													$crt = number_format($val->crt,0,',','.');
												}
												echo $crt;
											?>
										</td>
									</tr>
									<?php $no++;  endforeach; ?>
								</tbody>
							</table>
						</div>
						<!-- pemsiah tabel -->
					</div>
					
					<!-- Simple Datatable End -->
					
        </div>
    </div>
</div>
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
										
										<li class="breadcrumb-item active" aria-current="page">
											Data Produksi
										</li>
									</ol>
								</nav>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<!-- <a class="btn btn-primary dropdown-toggle no-arrow" href="<=base_url('proses-produksi');?>" role="button"> Tambah Produksi </a> -->
									<a class="btn btn-primary dropdown-toggle no-arrow" href="<?=base_url('export-produksi');?>" role="button"> Export Produksi </a>
									<!-- <a class="btn btn-primary dropdown-toggle no-arrow" href="<=base_url('input-produksi-insgrey');?>" role="button"> Tambah Produksi </a> -->
									<!-- <a class="btn btn-primary dropdown-toggle no-arrow" href="javascript:void(0);" role="button" data-toggle="modal" data-target="#bd-example-modal-lg" > Tambah Produksi </a> -->
									<!-- <a class="btn btn-secondary dropdown-toggle no-arrow" href="<?=base_url('input-produksi-inspect');?>" role="button">Inpect Finish</a> -->
									<a class="btn btn-dark dropdown-toggle no-arrow" href="<?=base_url('input-produksi-folding');?>" role="button">Folding Stok Lama</a>
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
										<th>Tanggal</th>
										<th>Inspect Grey</th>
										<th>Folding Grey</th>
										<th>Inspect Finish</th>
										<th>Folding Finish</th>
										<th>BS</th>
										<th>BP</th>
										<th>BS Finish</th>
										<th>BP Finish</th>
										<th>CRT</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$no=1; foreach($dtkons2->result() as $n => $val):
									?>
									<tr>
										<td><?=$no;?></td>
										<td><?php $ex=explode('-',$val->tgl); echo "<a href='input/produksi/$val->tgl'>".$ex[2]." ".$arb[$ex[1]]." ".$ex[0]."</a>";?></td>
										<td><a href="<?=base_url('produksi-insgrey/'.$val->tgl);?>">
											<?php 
												if(fmod($val->prod_ig, 1) !== 0.00){
													$stok_ig = number_format($val->prod_ig,2,',','.');
												} else {
													$stok_ig = number_format($val->prod_ig,0,',','.');
												}
												echo $stok_ig;
											?></a>
										</td>
										<td><a href="<?=base_url('produksi-folgrey/'.$val->tgl);?>">
											<?php 
												if(fmod($val->prod_fg, 1) !== 0.00){
													$stok_fg = number_format($val->prod_fg,2,',','.');
												} else {
													$stok_fg = number_format($val->prod_fg,0,',','.');
												}
												echo $stok_fg;
											?></a>
										</td>
										<td><a href="<?=base_url('produksi-insfinish/'.$val->tgl);?>">
											<?php 
												$jml_yard = floatval($val->prod_if) / 0.9144;
												if(fmod($jml_yard, 1) !== 0.00){
													$stok_if = number_format($jml_yard,2,',','.');
												} else {
													$stok_if = number_format($jml_yard,0,',','.');
												}
												echo $stok_if;
											?></a>
										</td>
										<td><a href="<?=base_url('produksi-folfinish/'.$val->tgl);?>">
											<?php 
												if(fmod($val->prod_ff, 1) !== 0.00){
													$stok_ff = number_format($val->prod_ff,2,',','.');
												} else {
													$stok_ff = number_format($val->prod_ff,0,',','.');
												}
												echo $stok_ff;
											?></a>
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
												if(fmod($val->prod_bs2, 1) !== 0.00){
													$bs2 = number_format($val->prod_bs2,2,',','.');
												} else {
													$bs2 = number_format($val->prod_bs2,0,',','.');
												}
												echo $bs2;
											?>
										</td>
										<td>
											<?php 
												if(fmod($val->prod_bp2, 1) !== 0.00){
													$bp2 = number_format($val->prod_bp2,2,',','.');
												} else {
													$bp2 = number_format($val->prod_bp2,0,',','.');
												}
												echo $bp2;
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
					
					
        </div>
    </div>
</div>
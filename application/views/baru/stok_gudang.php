<?php $arb = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar','04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des']; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data Stok</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="#">Data</a>
										</li>
										<li class="breadcrumb-item">
											<a href="#">Stok Gudang</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
										<?=$dep;?>
										</li>
									</ol>
								</nav>
							</div>
							<?php
							$kons = $this->data_model->get_record('tb_konstruksi');
							?>
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
							<strong>Stok Gudang <?=$dep;?></strong>
							</p>
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort" rowspan="2" style="vertical-align: middle;">No</th>
										<th rowspan="2" style="vertical-align: middle;text-align: center;">Konstruksi</th>
										<th colspan="2" style="text-align: center;">Folding Grey</th>
										<th rowspan="2" style="vertical-align: middle;text-align: center;">Stok Grey</th>
										<th colspan="2" style="text-align: center;">Folding Finish</th>
										<th rowspan="2" style="vertical-align: middle;text-align: center;">Stok Finish</th>
									</tr>
									<tr>
										<th style="text-align: center;">Di Gudang</th>
										<th style="text-align: center;">Di Paket</th>
										<th style="text-align: center;">Di Gudang</th>
										<th style="text-align: center;">Di Paket</th>
									</tr>
								</thead>
								<tbody>
								<?php
								$no=1;
								foreach($kons->result() as $val){
									$k = strtoupper($val->kode_konstruksi);
        							if($val->chto == "NULL") {  $chto="";  } else { $chto=" / <font style='color:blue;'>".$val->chto."</font>";  }
									$fg = $this->db->query("SELECT SUM(ukuran) AS jm FROM data_fol WHERE konstruksi='$k' AND jns_fold='Grey' AND posisi='Samatex' ")->row("jm");
									$fg_2 = $this->db->query("SELECT SUM(ukuran) AS jm FROM data_fol WHERE konstruksi='$k' AND jns_fold='Grey' AND posisi LIKE '%PKT%' ")->row("jm");
									$ff = $this->db->query("SELECT SUM(ukuran) AS jm FROM data_fol WHERE konstruksi='$k' AND jns_fold='Finish' AND posisi='Samatex'")->row("jm");
        							$ff_2 = $this->db->query("SELECT SUM(ukuran) AS jm FROM data_fol WHERE konstruksi='$k' AND jns_fold='Finish' AND posisi LIKE '%PKT%' ")->row("jm");
									$jml1 = $fg + $fg_2; 
									$jml2 = $ff + $ff_2; 
									if(floatval($fg)>0){
										if(floor($fg)==$fg){
											$fg = number_format($fg,0,',','.');
										} else {
											$fg = number_format($fg,2,',','.');
										}
									} else {
										$fg = 0;
									}
									if(floatval($fg_2)>0){
										if(floor($fg_2)==$fg_2){
											$fg_2 = number_format($fg_2,0,',','.');
										} else {
											$fg_2 = number_format($fg_2,2,',','.');
										}
									} else {
										$fg_2 = 0;
									}
									
									if(floatval($jml1) > 0){ 
										if(floor($jml1)==$jml1){
											$jml1 = number_format($jml1,0,',','.');
										} else {
											$jml1 = number_format($jml1,2,',','.');
										}
									} else {
										$jml1 = 0;
									}
									if(floatval($ff)>0){
										if(floor($ff)==$ff){
											$ff = number_format($ff,0,',','.');
										} else {
											$ff = number_format($ff,2,',','.');
										}
									} else {
										$ff = 0;
									}
									if(floatval($ff_2)>0){
										if(floor($ff_2)==$ff_2){
											$ff_2 = number_format($ff_2,0,',','.');
										} else {
											$ff_2 = number_format($ff_2,2,',','.');
										}
									} else {
										$ff_2 = 0;
									}
									
									if(floatval($jml2) > 0){ 
										if(floor($jml2)==$jml2){
											$jml2 = number_format($jml2,0,',','.');
										} else {
											$jml2 = number_format($jml2,2,',','.');
										}
									} else {
										$jml2 = 0;
									}
									if($fg==0 AND $fg_2==0 AND $ff==0 AND $ff_2==0){ } else {
									?>
									<tr>
										<td><?=$no++;?></td>
										<td><?=$k."".$chto;?></td>
										<td><?=$fg;?></td>
										<td><?=$fg_2;?></td>
										<td><?=$jml1;?></td>
										<td style='color:blue;'><?=$ff;?></td>
										<td style='color:blue;'><?=$ff_2;?></td>
										<td style='color:blue;'><?=$jml2;?></td>
									</tr>
									<?php }
								}
								?>
								</tbody>
							</table>
						</div>
						
					</div>
					<!-- Simple Datatable End -->
					<!-- Simple Datatable start -->
					
					
        </div>
    </div>
</div>
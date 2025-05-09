<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ];
$ex1 = explode('-', $tgl_awal);
$ex2 = explode('-', $tgl_akhir);
$print_tgl_awal = $ex1[2]." ".$bln[$ex1[1]]." ".$ex1[0];
$print_tgl_akhir = $ex2[2]." ".$bln[$ex2[1]]." ".$ex2[0];
?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								<strong>Laporan Produksi</strong>
							</p><small>Menampilkan laporan produksi di departement <strong><?=$dep;?></strong> tanggal <strong><?=$print_tgl_awal;?></strong> s/d <strong><?=$print_tgl_akhir;?></strong></small>
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                                <div class="table-responsive">
									<table class="data-table table stripe hover nowrap">
                                        <thead>
											<tr class="table-secondary">
												<th>No.</th>
												<th>Konstruksi</th>
												<th>Tanggal</th>
												<th>Inspect Grey</th>
												<th>Inspect Finish</th>
												<th>Folding Grey</th>
												<th>Folding Finish</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											$ukr1 = 0; $ukr3 = 0; $ukr2 = 0; $ukr4 = 0;
											foreach($qrdata->result() as $n => $val): ?>
											<tr>
												<td><strong><?=$n+1;?></strong></td>
												<td><strong><?=$val->kode_konstruksi;?></strong></td>
												<td><?php $ex=explode('-',$val->tgl); echo $ex[2]." ".$bln[$ex[1]]." ".$ex[0];?></td>
												<td><?php
												  if(fmod($val->prod_ig, 1) !== 0.00){
													$insgrey = number_format($val->prod_ig,2,',','.');
												  } else {
													$insgrey = number_format($val->prod_ig,0,',','.');
												  }
												echo $insgrey;
												$ukr1 += floatval($val->prod_ig);
												?></td>
												<td>
												  <?php 
												 	if($val->prod_if == 0){
														$insfnsh = "-";
														echo $insfnsh;
													} else {
														  if(fmod($val->prod_if, 1) !== 0.00){
															$insfnsh = number_format($val->prod_if,2,',','.');
														  } else {
															$insfnsh = number_format($val->prod_if,0,',','.');
														  }
														  echo $insfnsh;
														  $ukr2 += floatval($val->prod_if);
													}
												  ?>
												</td>
												<td>
													<?php
														
															if($val->prod_fg==0){
																echo "-";
															} else {
															  if(fmod($val->fol_grey, 1) !== 0.00){
																$fol_grey = number_format($val->prod_fg,2,',','.');
															  } else {
																$fol_grey = number_format($val->prod_fg,0,',','.');
															  }
															  echo $fol_grey;
															  $ukr3 += floatval($val->prod_fg);
															}
														
													?>
												</td>
												<td>
													<?php
														
															if($val->prod_fg==0){
																echo "-";
															} else {
															  if(fmod($val->prod_fg, 1) !== 0.00){
																$folfnsh = number_format($val->prod_fg,2,',','.');
															  } else {
																$folfnsh = number_format($val->prod_fg,0,',','.');
															  }
															  echo $folfnsh;
															  $ukr4 += floatval($val->prod_fg);
															}
														
													?>
												</td>
											</tr>
											<?php endforeach; ?>
											
										</tbody>
										<tfoot>
										<tr class="table-primary">
												<td colspan="3"><strong>Total</strong></td>
												<td>
													<?php 
														if(fmod($ukr1, 1) !== 0.00){
															echo number_format($ukr1,2,',','.');
														} else {
															echo number_format($ukr1,0,',','.');
														}
													?> Meter
												</td>
												<td>
													<?php 
														if(fmod($ukr2, 1) !== 0.00){
															echo number_format($ukr2,2,',','.');
														} else {
															echo number_format($ukr2,0,',','.');
														}
													?>
												</td>
												<td>
													<?php 
														if(fmod($ukr3, 1) !== 0.00){
															echo number_format($ukr3,2,',','.');
														} else {
															echo number_format($ukr3,0,',','.');
														}
													?>
												</td>
												<td>
													<?php 
														if(fmod($ukr4, 1) !== 0.00){
															echo number_format($ukr4,2,',','.');
														} else {
															echo number_format($ukr4,0,',','.');
														}
													?>
												</td>
											</tr>
										</tfoot>
                                    </table>
                                </div>
                            </div>
						</div>
					</div>
					<!-- Simple Datatable End -->
        </div>
    </div>
</div>

<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Report Produksi</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Report</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Produksi
										</li>
									</ol>
								</nav>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a
										class="btn btn-primary dropdown-toggle no-arrow"
										href="javascript:void(0);"
										role="button"
										data-toggle="modal"
										data-target="#bd-example-modal-lg"
									
									>
										Cetak
									</a>
									
								</div>
							</div>
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Report Produksi Hari ini
							</p>
						</div>
						<div class="pb-20">
                            <div class="table-responsive">
								<table class="table">
									<tr>
										<th rowspan="2">Kode Konstruksi</th>
										<th>RJS</th>
										<th colspan="2" style="background:#f5f5f5;">Samatex</th>
										<th colspan="2" style="background:#e6e6e6;">Pusatex</th>
									</tr>
									<tr>
										<td>Inspect Grey</td>
										<td style="background:#f5f5f5;">Inspect Grey</td> <!--ini di ganti grey-->
										<td style="background:#f5f5f5;">Folding</td>
										<td style="background:#e6e6e6;">Inspect Finish</td>
										<td style="background:#e6e6e6;">Folding </td>
									</tr>
									<?php 
									$today = date('Y-m-d');
									foreach($dt_kons->result() as $kons): 
										$kdkons= $kons->kode_konstruksi;
									?>
									<tr>
										<td><?=$kdkons;?></td>
										<td>
											<?php
												$ig_rjs = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kdkons, 'lokasi_produksi'=>'RJS', 'waktu'=>$today])->row('ins_grey');
												if($ig_rjs==0){ echo "-"; } else { echo $ig_rjs; }
											?>
										</td>
										<td style="background:#f5f5f5;">
											<?php
												$query_stx = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kdkons, 'lokasi_produksi'=>'Samatex', 'waktu'=>$today]);
												$if_stx = $query_stx->row('ins_grey');
												if($if_stx==0){ echo "-"; } else { echo $if_stx." y"; }
											?>
										</td>
										<td style="background:#f5f5f5;">
											<?php
												$folding_grey = $query_stx->row("fol_grey");
												$folding_finish = $query_stx->row("fol_finish");
												$folding_grey2 = $query_stx->row("fol_grey_yard");
												$folding_finish2 = $query_stx->row("fol_finish_yard");
												if($folding_grey>$folding_finish){
													if($folding_grey==0){echo"-";} else {
													echo $folding_grey." m"; }
												} else {
													if($folding_finish2==0){echo"-";}else{
													echo $folding_finish2." y"; }
												}
											?>
										</td>
										<td style="background:#e6e6e6;">
											<?php
												$query_pst1 = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kdkons, 'lokasi_produksi'=>'Pusatex', 'waktu'=>$today]);
												$if_pst = $query_pst1->row('ins_finish_yard');
												if($if_pst==0){ echo "-"; } else { echo $if_pst." y"; }
											?>
										</td>
										<td style="background:#e6e6e6;">
											<?php
												$query_pst = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kdkons, 'lokasi_produksi'=>'Pusatex', 'waktu'=>$today]);
												$if_pst = $query_pst->row('fol_finish_yard');
												if($if_pst==0){ echo "-"; } else { echo $if_pst." y"; }
											?>
										</td>
									</tr>
									<?php endforeach; ?>
								</table>
							</div>
						</div>
					</div>
					<!-- Simple Datatable start -->
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Report Produksi Total
							</p>
						</div>
						<div class="pb-20">
                            <div class="table-responsive">
								<table class="table">
									<tr>
										<th rowspan="2">Kode Konstruksi</th>
										<th>RJS</th>
										<th colspan="2" style="background:#f5f5f5;">Samatex</th>
										<th colspan="2" style="background:#e6e6e6;">Pusatex</th>
									</tr>
									<tr>
										<td>Inspect Grey</td>
										<td style="background:#f5f5f5;">Inspect Grey</td> <!--ini di ganti grey-->
										<td style="background:#f5f5f5;">Folding</td>
										<td style="background:#e6e6e6;">Inspect Finish</td>
										<td style="background:#e6e6e6;">Folding </td>
									</tr>
									<?php 
									$today = date('Y-m-d');
									foreach($dt_kons->result() as $kons): 
										$kdkons= $kons->kode_konstruksi;
									?>
									<tr>
										<td><?=$kdkons;?></td>
										<td >
											<?php
												$ig_rjs = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kdkons, 'lokasi_produksi'=>'RJS'])->row('ins_grey');
												if($ig_rjs==0){ echo "-"; } else { echo $ig_rjs; }
											?>
										</td>
										<td style="background:#f5f5f5;">
											<?php
												$ig_smt = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kdkons, 'lokasi_produksi'=>'Samatex']);
												$nilai=0; $fg=0; $ff=0;
												foreach($ig_smt->result() as $val):
													$ni = $nilai + $val->ins_grey;
													$nilai = round($ni,2);
													$fg = $fg + $val->fol_grey;
													$ff = $ff + $val->fol_finish_yard;
												endforeach;
												echo $nilai=='0'?'-':''.$nilai.' m';
											?>
										</td>
										<td style="background:#f5f5f5;">
											<?php
												if($ff > $fg){
													if($ff==0){echo"-";}else{
													echo $ff." y";}
												} else {
													if($fg==0){echo"-";}else{
													echo $fg." m";}
												}
											?>
										</td>
										<td style="background:#e6e6e6;">
										<?php
												$ig_pst = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kdkons, 'lokasi_produksi'=>'Pusatex']);
												$nilai=0; $fg=0; $ff=0;
												foreach($ig_pst->result() as $val):
													$ni = $nilai + $val->ins_finish;
													$nilai = round($ni,2);
													$fg = $fg + $val->fol_grey;
													$ff = $ff + $val->fol_finish_yard;
												endforeach;
												echo $nilai=='0'?'-':''.$nilai.' m';
											?>
										</td>
										<td style="background:#e6e6e6;">
											<?php
												if($ff > $fg){
													if($ff==0){echo"-";}else{
													echo $ff." y";}
												} else {
													if($fg==0){echo"-";}else{
													echo $fg." m";}
												}
											?>
										</td>
										
									</tr>
									<?php endforeach; ?>
								</table>
							</div>
						</div>
					</div>
        </div>
    </div>
</div>

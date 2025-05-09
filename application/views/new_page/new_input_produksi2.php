<?php $arb = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret','04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Okteber', '11' => 'November', '12' => 'Desember'];
$ix = explode('-', $uri_tgl);
$printTgl = $ix[2]." ".$arb[$ix[1]]." ".$ix[0];
?>
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
										<li class="breadcrumb-item">
											Data Produksi
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											<?=$printTgl;?>
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
								Data Produksi
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
										<th>BS Finish</th>
										<th>BP Finish</th>
										<th>CRT</th>
									</tr>
								</thead>
								<tbody>
									<?php 
                                    $total1=0;$total2=0;$total3=0;$total4=0;$total5=0;$total6=0;$total7=0;$total8=0;$total9=0;
                                    $no=1; foreach($dtkons2->result() as $n => $val):
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
                                                $total1+=floatval($val->prod_ig);
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
                                                $total2+=$val->prod_fg;
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
                                                $total3+=$val->prod_if;
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
                                                $total4+=$val->prod_ff;
											?>
										</td>
										<td>
											<?php 
												$bs = floatval($val->prod_bs1);
												if(fmod($bs, 1) !== 0.00){
													$printbs = number_format($bs,2,',','.');
												} else {
													$printbs = number_format($bs,0,',','.');
												}
												echo $printbs;
                                                $total5+=$val->prod_bs1;
											?>
										</td>
										<td>
											<?php 
												$bp = floatval($val->prod_bp1);
												if(fmod($bs, 1) !== 0.00){
													$printbp = number_format($bp,2,',','.');
												} else {
													$printbp = number_format($bp,0,',','.');
												}
												echo $printbp;
                                                $total6+=$bp;
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
                                                $total1+=$val->prod_bs2;
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
                                                $total1+=$val->prod_bp2;
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
                                                $total1+=$val->crt;
											?>
										</td>
									</tr>
									<?php $no++;  endforeach; ?>
                                    <tfoot>
                                        <tr class='table-primary'>
                                            <td colspan="2"><strong>Total</strong></td>
                                            <td>
                                                <?php 
													$sum_ig = $this->db->query("SELECT SUM(prod_ig) AS total FROM data_produksi WHERE dep='$depuser' AND tgl='$tgl'")->row("total");
                                                    if(fmod($sum_ig, 1) !== 0.00){
                                                        $alltotal1 = number_format($sum_ig,2,',','.');
                                                    } else {
                                                        $alltotal1 = number_format($sum_ig,0,',','.');
                                                    }
                                                    echo $alltotal1;
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
													$sum_fg = $this->db->query("SELECT SUM(prod_fg) AS total FROM data_produksi WHERE dep='$depuser' AND tgl='$tgl'")->row("total");
                                                    if(fmod($sum_fg, 1) !== 0.00){
                                                        $alltotal2 = number_format($sum_fg,2,',','.');
                                                    } else {
                                                        $alltotal2 = number_format($sum_fg,0,',','.');
                                                    }
                                                    echo $alltotal2;
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
													$sum_if = $this->db->query("SELECT SUM(prod_if) AS total FROM data_produksi WHERE dep='$depuser' AND tgl='$tgl'")->row("total");
                                                    if(fmod($sum_if, 1) !== 0.00){
                                                        $alltotal3 = number_format($sum_if,2,',','.');
                                                    } else {
                                                        $alltotal3 = number_format($sum_if,0,',','.');
                                                    }
                                                    echo $alltotal3;
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
													$sum_ff = $this->db->query("SELECT SUM(prod_ff) AS total FROM data_produksi WHERE dep='$depuser' AND tgl='$tgl'")->row("total");
                                                    if(fmod($sum_ff, 1) !== 0.00){
                                                        $alltotal4 = number_format($sum_ff,2,',','.');
                                                    } else {
                                                        $alltotal4 = number_format($sum_ff,0,',','.');
                                                    }
                                                    echo $alltotal4;
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
													$sum_bs1 = $this->db->query("SELECT SUM(prod_bs1) AS total FROM data_produksi WHERE dep='$depuser' AND tgl='$tgl'")->row("total");
                                                    if(fmod($sum_bs1, 1) !== 0.00){
                                                        $alltotal5 = number_format($sum_bs1,2,',','.');
                                                    } else {
                                                        $alltotal5 = number_format($sum_bs1,0,',','.');
                                                    }
                                                    echo $alltotal5;
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
													$sum_bp1 = $this->db->query("SELECT SUM(prod_bp1) AS total FROM data_produksi WHERE dep='$depuser' AND tgl='$tgl'")->row("total");
                                                    if(fmod($sum_bp1, 1) !== 0.00){
                                                        $alltotal6 = number_format($sum_bp1,2,',','.');
                                                    } else {
                                                        $alltotal6 = number_format($sum_bp1,0,',','.');
                                                    }
                                                    echo $alltotal6;
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
													$sum_bs2 = $this->db->query("SELECT SUM(prod_bs2) AS total FROM data_produksi WHERE dep='$depuser' AND tgl='$tgl'")->row("total");
                                                    if(fmod($sum_bs2, 1) !== 0.00){
                                                        $alltotal7 = number_format($sum_bs2,2,',','.');
                                                    } else {
                                                        $alltotal7 = number_format($sum_bs2,0,',','.');
                                                    }
                                                    echo $alltotal7;
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
													$sum_bp2 = $this->db->query("SELECT SUM(prod_bp2) AS total FROM data_produksi WHERE dep='$depuser' AND tgl='$tgl'")->row("total");
                                                    if(fmod($sum_bp2, 1) !== 0.00){
                                                        $alltotal8 = number_format($sum_bp2,2,',','.');
                                                    } else {
                                                        $alltotal8 = number_format($sum_bp2,0,',','.');
                                                    }
                                                    echo $alltotal8;
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
													$sum_crt = $this->db->query("SELECT SUM(crt) AS total FROM data_produksi WHERE dep='$depuser' AND tgl='$tgl'")->row("total");
                                                    if(fmod($sum_crt, 1) !== 0.00){
                                                        $alltotal9 = number_format($sum_crt,2,',','.');
                                                    } else {
                                                        $alltotal9 = number_format($sum_crt,0,',','.');
                                                    }
                                                    echo $alltotal9;
                                                ?>
                                            </td>
                                            
                                            
                                        </tr>
                                    </tfoot>
								</tbody>
							</table>
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								<strong>Data Produksi Inspect Grey</strong>
							</p>
						</div>
						<div class="pd-20">
							<?php
							$one_queri = $this->db->query("SELECT id_data,tanggal,operator,dep FROM data_ig WHERE tanggal='$uri_tgl' AND dep='$depuser' GROUP BY operator");
							$two_queri = $this->db->query("SELECT id_data,konstruksi,tanggal,dep FROM data_ig WHERE tanggal='$uri_tgl' AND dep='$depuser' GROUP BY konstruksi");
							?>
							<div class="table-responsive">
							<table class="data-table table stripe hover nowrap">
								<thead>
								<tr>
									<th>No.</th>
									<th>Nama</th>
									<?php foreach($two_queri->result() as $k): echo "<th>".$k->konstruksi."</th>"; endforeach; ?>
									<th>Total</th>
								</tr>
								<thead>
								<tbody>
							<?php
							$nu=1;
							foreach($one_queri->result() as $val):
								echo "<tr>";
								echo "<td>".$nu."</td>";
								echo "<td>".ucfirst($val->operator)."</td>";
								$total_perorang = 0;
								foreach($two_queri->result() as $k): 
									$jumlah = $this->data_model->get_byid('data_ig', ['konstruksi'=>$k->konstruksi, 'tanggal'=>$uri_tgl, 'operator'=>$val->operator, 'dep'=>$depuser]);
									$ori=0;
									foreach($jumlah->result() as $nil):
										$ori+=$nil->ukuran_ori;
									endforeach;
									echo "<td>".number_format($ori,0,',','.')."</td>";
									$total_perorang+=$ori;
								endforeach;
								echo "<td>".number_format($total_perorang,0,',','.')."</td>";
								echo "</tr>";
								
								$nu++;
							endforeach;
							?>
								</tbody>
							</table>
							</div>
							<p>&nbsp;</p>
							<?php foreach($two_queri->result() as $n => $ks):
							$jumlah = $this->data_model->get_byid('data_ig', ['konstruksi'=>$ks->konstruksi, 'tanggal'=>$uri_tgl,  'dep'=>$depuser]);
							$uori=0; $ubp=0;
							foreach($jumlah->result() as $nil):
								if($nil->bp_can_join=="true"){
									$ubp+=$nil->ukuran_ori;
								} else {
									$uori+=$nil->ukuran_ori;
								}
							endforeach;
							$new_total = $uori + $ubp;
							?>
							<p><strong><?=$n+1;?>.</strong> Konstruksi <strong><?=$ks->konstruksi;?></strong> Total Inspect <strong><?=number_format($new_total,0,',','.');?></strong> Meter (<strong>ORI <?=number_format($uori,0,',','.');?></strong> Meter, <strong>BP</strong> dengan kode <strong><?=number_format($ubp,0,',','.');?></strong> Meter)</p>
							<?php endforeach; ?>
						</div>
						
					</div>
                </div>
    </div>
</div>

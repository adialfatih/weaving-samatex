<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Report Data Penjualan</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Report</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Data</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Penjualan
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p>Data Penjualan</p>
						</div>
                        <div class="table-responsive">
                            <table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort">No</th>
										<th>Kode Konstruksi</th>
									
                                        <td>Data Terjual (Yard)</td>
                                        <td>Stok Lama Terjual</td>
                                        
                                    </tr>
								</thead>
								<tbody>
									<?php
									$no=1;
                                    foreach($kode->result() as $n => $val):
									$kd = $val->kode_konstruksi;
									$dt1 = $this->db->query("SELECT id_stok,kode_konstruksi,terjual,terjual_yard FROM report_stok WHERE kode_konstruksi='$kd'");
									$terjual=0; $terjual_yard=0;
									if($dt1->num_rows()>0){
										foreach($dt1->result() as $dtt1):
											$terjual+=$dtt1->terjual;
											$terjual_yard+=$dtt1->terjual_yard;
										endforeach;
									}
									$dt2 = $this->db->query("SELECT id_sl,kode_konstruksi,terjual,terjual_yard FROM report_stok_lama WHERE kode_konstruksi='$kd'");
									$terjual2=0; $terjual_yard2=0;
									if($dt2->num_rows()>0){
										foreach($dt2->result() as $dtt2):
											$terjual2+=$dtt2->terjual;
											$terjual_yard2+=$dtt2->terjual_yard;
										endforeach;
									}
									if($terjual==0 AND $terjual_yard==0 AND $terjual2==0 AND $terjual_yard2==0){} else {
                                    ?>
                                    <tr>
                                        <td><?=$no;?></td>
                                        <td><?=$kd;?></td>
                                        
                                        <td><?=$terjual_yard;?></td>
                                        <td><?=$terjual_yard2;?></td>
                                    </tr>
                                    <?php $no++; }
                                    endforeach;
                                    ?>
							</table>
                        </div>
					</div>
                    
					<!-- Simple Datatable End -->
					
        </div>
    </div>
</div>

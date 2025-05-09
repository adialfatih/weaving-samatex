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
								<strong>Laporan Produksi Mesin</strong>
							</p><small>Menampilkan laporan produksi mesin di departement <strong><?=$dep;?></strong> tanggal <strong><?=$print_tgl_awal;?></strong><?php if($tgl_awal==$tgl_akhir){} else {;?> s/d <strong><?=$print_tgl_akhir;?></strong><?php } ?></small>
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                                <div class="table-responsive">
									<table class="data-table table stripe hover nowrap">
                                        <thead>
											<tr class="table-secondary">
												<th>No.</th>
												<th>Kode Konstruksi</th>
												<th>Tanggal</th>
												<th>Jumlah Mesin</th>
												<th>Jumlah Produksi</th>
												<th>Lokasi</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											$total = 0;
											foreach($qrdata->result() as $n => $val): ?>
											<tr>
												<td><strong><?=$n+1;?></strong></td>
												<td><strong><?=$val->kode_konstruksi;?></strong></td>
												<td><?php $ex=explode('-',$val->tanggal_produksi); echo $ex[2]." ".$bln[$ex[1]]." ".$ex[0];?></td>
												<td><?=$val->jumlah_mesin;?></td>
												<td>
												  <?php 
                                                  $total+=$val->hasil;
														  if(fmod($val->hasil, 1) !== 0.00){
															$hasil = number_format($val->hasil,2,',','.');
														  } else {
															$hasil = number_format($val->hasil,0,',','.');
														  }
														  echo $hasil;
														  
												  ?>
												</td>
												<td><?=$val->lokasi;?></td>
												
											</tr>
											<?php endforeach; ?>
											
										</tbody>
										<tfoot>
										<tr class="table-primary">
												<td colspan="3"><strong>Total</strong></td>
												<td></td>
												<td>
                                                    <?php
                                                    if(fmod($total, 1) !== 0.00){
                                                        $total = number_format($total,2,',','.');
                                                      } else {
                                                        $total = number_format($total,0,',','.');
                                                      }
                                                      echo $total;
                                                    ?>
                                                </td>
												<td></td>
												
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

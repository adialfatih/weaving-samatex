<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30" id="notemy">
						<div class="pd-20">
							<p class="mb-0">
								Report Produksi Hari ini
							</p>
						</div>
						<div class="pb-20">
                            
								<table class="table">
									<tr>
										<th rowspan="2">Kode Konstruksi</th>
										<th>RJS</th>
										<th colspan="3">Samatex</th>
										<th colspan="3">Pusatex</th>
									</tr>
									<tr>
										<td>Inspect Grey</td>
										<td>Inspect Finish</td>
										<td>Folding Grey</td>
										<td>Folding Finish</td>
										<td>Inspect Finish</td>
										<td>Folding Grey</td>
										<td>Folding Finish</td>
									</tr>
									<?php foreach($dt_kons->result() as $kons): ?>
									<tr>
										<td><?=$kons->kode_konstruksi;?></td>
										<td>0</td>
										<td>0</td>
										<td>0</td>
										<td>0</td>
										<td>0</td>
										<td>0</td>
										<td>0</td>
									</tr>
									<?php endforeach; ?>
								</table>
							
						</div>
					</div>
					<!-- Simple Datatable start -->
					

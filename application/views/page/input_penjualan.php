<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data Penjualan</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Data Penjualan
										</li>
									</ol>
								</nav>
							</div>
							<!-- <div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a class="btn btn-primary dropdown-toggle no-arrow" href="javascript:void(0);" role="button" data-toggle="modal" data-target="#bd-example-modal-lg">
										Tambah Penjualan
									</a>
									
								</div>
							</div> -->
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								
							</p>
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort">No</th>
										<th>Tanggal</th>
										<th>Konstruksi</th>
										<th>Penjualan</th>
										<th>Jumlah Roll</th>
										<th>Total Panjang</th>
										<th>#</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($dtpnj->result() as $n => $val): 
									$ex = explode('-', $val->tanggal);
									$prinTgl = $ex[2]."-".$bln[$ex[1]]."-".$ex[0];
									?>
									<tr>
										<td><?=$n+1;?></td>
										<td><?=$prinTgl;?></td>
										<td><?=$val->konstruksi;?></td>
										<td><?=$val->jns_fold;?></td>
										<td><?=$val->jml_roll;?> Roll</td>
										<td><?=$val->jumlah_penjualan;?> <?=$val->jns_fold=="Grey" ? "Meter":"Yard";?></td>
										<td>#</td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- Simple Datatable End -->
					
        </div>
    </div>
</div>

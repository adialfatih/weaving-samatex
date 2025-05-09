<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Marc', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header d-print-none">
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
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Data Penjualan</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Nomor</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											<?=sprintf("%04d", $nomor);?>
										</li>
									</ol>
								</nav>
							</div>
							
						</div>
					</div>
					<?php 
                            $ex = explode('-', $dt_all['tgl']);
                            $print_tgl = $ex[2]." ".$bln[$ex[1]]." ".$ex[0];
                            $ix = explode(' ', $dt_all['tm_stmp']);
                            $ox = explode('-', $ix[0]);
                            $print_tms = $ox[2]."-".$bln[$ox[1]]."-".$ox[0];
							?>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
                            <h4 class="text-center mb-30 weight-600">DATA PACKING LIST</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th class="nop">No</th>
                                    <th>Ukuran</th>
                                    <th>Kode</th>
                                    <th>Ket</th>
                                    <th class="nop">No</th>
                                    <th>Ukuran</th>
                                    <th>Kode</th>
                                    <th>Ket</th>
                                    <th class="nop">No</th>
                                    <th>Ukuran</th>
                                    <th>Kode</th>
                                    <th>Ket</th>
                                    <th class="nop">No</th>
                                    <th>Ukuran</th>
                                    <th>Kode</th>
                                    <th>Ket</th>
                                </tr>
                                <?php for ($i=1; $i <27 ; $i++) { ?>
                                <tr>
                                    <th class="nop"><?=$i;?></th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th class="nop">&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th class="nop">&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th class="nop">&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                </tr>
                                <?php } ?>
                            </table>
                               
						</div>
					</div>
					<!-- Simple Datatable End -->
					
        </div>
    </div>
</div>

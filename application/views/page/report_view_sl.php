<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Report Stok</h4>
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
											<a href="javascript:void(0);">Stok</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Stok Lama
										</li>
									</ol>
								</nav>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a class="btn btn-primary dropdown-toggle no-arrow" href="javascript:void(0);" role="button" data-toggle="modal" data-target="#bd-example-modal-lg">Cetak</a>
								</div>
							</div>
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Table Stok Lama
							</p><small>Menampilkan data produksi dari stok lama yang tidak melalui proses tersistem.</small>
						</div>
						<div class="pb-20">
                            <div class="table-responsive">
								<table class="table">
									<tr>
                                        <th style="text-align: center;">No.</th>
										<th>Kode Konstruksi</th>
										<th style="text-align: center;">Samatex</th>
										<th style="text-align: center;">Pusatex</th>
									</tr>
									
									<?php 
									$today = date('Y-m-d');
                                    $nomer =1;
									$jml1=0; $jml2=0;
									foreach($dt_kons->result() as $n => $kons): 
										$kdkons= $kons->kode_konstruksi;
                                        // CEK 1
                                        $cekdt = $this->data_model->get_byid('report_stok_lama', ['kode_konstruksi'=>$kdkons, 'departement'=>'Samatex']);
                                        if($cekdt->num_rows()==0){
                                            $cek1= "0";
                                            $cek1_print= "-";
                                        } else {
                                            $fol_finish = 0; $fol_grey = 0;
                                            foreach ($cekdt->result() as $ba) {
                                                $fol_finish = $fol_finish + round($ba->fol_finish_yard,2);
                                                $fol_grey = $fol_grey + round($ba->fol_grey,2);
                                            }
                                            if($fol_finish>$fol_grey){
                                                $cek1 = $fol_finish;
                                                $cek1_print = "".$fol_finish." y";
												$jml1+=$fol_finish;
												  if(fmod($fol_finish, 1) !== 0.00){
													$cek1_print = number_format($fol_finish,2,',','.')." y";
												  } else {
													$cek1_print = number_format($fol_finish,0,',','.')." y";
												  }
                                            } else {
                                                $cek1 = $fol_grey;
                                                $cek1_print = "".$fol_grey." m";
												$jml1+=$fol_grey;
												  if(fmod($fol_grey, 1) !== 0.00){
													$cek1_print = number_format($fol_grey,2,',','.')." m";
												  } else {
													$cek1_print = number_format($fol_grey,0,',','.')." m";
												  }
                                            }
                                        }
                                        //END CEK 1
                                        $cekdtp = $this->data_model->get_byid('report_stok_lama', ['kode_konstruksi'=>$kdkons, 'departement'=>'Pusatex']);
                                                if($cekdtp->num_rows()==0){
                                                    $cek2= "0";
                                                    $cek2_print= "-";
                                                } else {
                                                    $fol_finish = 0; $fol_grey = 0; 
                                                    foreach ($cekdtp->result() as $ba) {
                                                        $fol_finish = $fol_finish + round($ba->fol_finish,2);
                                                        $fol_grey = $fol_grey + round($ba->fol_grey,2);
                                                    }
                                                    if($fol_finish>$fol_grey){
                                                        $cek2 = $fol_finish;
                                                        $cek2_print = "".$fol_finish." y";
														$jml2+=$fol_finish;
														  if(fmod($fol_finish, 1) !== 0.00){
															$cek2_print = number_format($fol_finish,2,',','.')." y";
														  } else {
															$cek2_print = number_format($fol_finish,0,',','.')." y";
														  }
                                                    } else {
                                                        $cek2 = $fol_grey;
                                                        $cek2_print = "".$fol_grey." m";
														$jml2+=$fol_grey;
														  if(fmod($fol_grey, 1) !== 0.00){
															$cek2_print = number_format($fol_grey,2,',','.')." m";
														  } else {
															$cek2_print = number_format($fol_grey,0,',','.')." m";
														  }
                                                    }
                                                }
                                        if($cek1=="0" AND $cek2=="0"){} else {
									?>
									<tr>
                                        <td style="text-align: center;"><?=$nomer;?></td>
										<td><?=$kdkons;?></td>
										<td style="text-align: center;"><?=$cek1_print;?></td>
										<td style="text-align: center;"><?=$cek2_print;?></td>
									</tr>
									<?php $nomer++; } endforeach; ?>
									<!-- <tr class="table-primary">
										<th></th>
										<th>Total</th>
										<th style="text-align: center;"><=$jml1;?></th>
										<th style="text-align: center;"><=$jml2;?></th>
									</tr> -->
								</table>
							</div>
						</div>
					</div>
        </div>
    </div>
</div>

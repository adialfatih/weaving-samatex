<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Report Stok Barang</h4>
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
											Stok Barang
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
								Menampilkan stok barang milik <strong><?=$sess_dep;?></strong> di gudang <strong><?=$sess_dep;?></strong>
							</p>
						</div>
						<div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                                <thead>
                                    <tr class="table-secondary">
                                        <th>No.</th>
                                        <th>Kode Konstruksi</th>
                                        <th>Inspect Grey</th>
                                        <th>Inspect Finish</th>
                                        <th>Folding Grey</th>
                                        <th>Folding Finish</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $ukr1=0; $ukr2=0; $ukr3=0; $ukr4=0; $no=1;
                                    foreach($dtstok->result() as $n => $val):
                                        if($val->stok_ins == 0){
                                            $stok_ins = "-";
                                            //echo $stok_ins;
                                        } else {
                                              if(fmod($val->stok_ins, 1) !== 0.00){
                                                $stok_ins = number_format($val->stok_ins,2,',','.');
                                              } else {
                                                $stok_ins = number_format($val->stok_ins,0,',','.');
                                              }
                                              //echo $stok_ins;
                                              $ukr1 += floatval($val->stok_ins);
                                        }
                                        if($val->stok_ins_finish_yard == 0){
                                            $stok_ins_finish_yard = "-";
                                            //echo $stok_ins_finish_yard;
                                        } else {
                                              if(fmod($val->stok_ins_finish_yard, 1) !== 0.00){
                                                $stok_ins_finish_yard = number_format($val->stok_ins_finish_yard,2,',','.');
                                              } else {
                                                $stok_ins_finish_yard = number_format($val->stok_ins_finish_yard,0,',','.');
                                              }
                                              //echo $stok_ins_finish_yard;
                                              $ukr2 += floatval($val->stok_ins_finish_yard);
                                        }
                                        if($val->stok_fol == 0){
                                            $stok_fol = "-";
                                            //echo $stok_fol;
                                        } else {
                                              if(fmod($val->stok_fol, 1) !== 0.00){
                                                $stok_fol = number_format($val->stok_fol,2,',','.');
                                              } else {
                                                $stok_fol = number_format($val->stok_fol,0,',','.');
                                              }
                                              //echo $stok_fol;
                                              $ukr3 += floatval($val->stok_fol);
                                        }
                                        if($val->stok_fol_finish_yard == 0){
                                            $stok_fol_finish_yard = "-";
                                            //echo $stok_fol_finish_yard;
                                        } else {
                                              if(fmod($val->stok_fol_finish_yard, 1) !== 0.00){
                                                $stok_fol_finish_yard = number_format($val->stok_fol_finish_yard,2,',','.');
                                              } else {
                                                $stok_fol_finish_yard = number_format($val->stok_fol_finish_yard,0,',','.');
                                              }
                                              //echo $stok_fol_finish_yard;
                                              $ukr4 += floatval($val->stok_fol_finish_yard);
                                        }
                                        if($stok_ins==0 && $stok_ins_finish_yard==0 && $stok_fol==0 && $stok_fol_finish_yard==0){} else {
                                    ?>
                                    <tr>
                                        <td><strong><?=$no;?></strong></td>
                                        <td><strong><?=$val->kode_konstruksi;?></strong></td>
                                        <td><?=$stok_ins;?></td>
                                        <td><?=$stok_ins_finish_yard;?></td>
                                        <td><?=$stok_fol;?></td>
                                        <td><?=$stok_fol_finish_yard;?></td>
                                    </tr>
                                    <?php $no++; 
                                } endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <td colspan="2"><strong>Total</strong></td>
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
                    <?php
                    if($sess_dep=="RJS"){
                        //menampilkan stok RJS di samtex/pusatex
                        $qr1 = $this->data_model->get_byid('report_stok', ['departement'=>'RJS-IN-Samatex']);
                        $qr2 = $this->data_model->get_byid('report_stok', ['departement'=>'RJS-IN-Pusatex']); ?>
                    <!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Menampilkan stok barang milik <strong>RJS</strong> di gudang <strong>Samatex</strong>
							</p>
						</div>
						<div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                                <thead>
                                    <tr class="table-secondary">
                                        <th>No.</th>
                                        <th>Kode Konstruksi</th>
                                        <th>Inspect Grey</th>
                                        <th>Inspect Finish</th>
                                        <th>Folding Grey</th>
                                        <th>Folding Finish</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $ukr1=0; $ukr2=0; $ukr3=0; $ukr4=0; $no=1;
                                    foreach($qr1->result() as $n => $val):
                                        if($val->stok_ins == 0){
                                            $stok_ins = "-";
                                            //echo $stok_ins;
                                        } else {
                                              if(fmod($val->stok_ins, 1) !== 0.00){
                                                $stok_ins = number_format($val->stok_ins,2,',','.');
                                              } else {
                                                $stok_ins = number_format($val->stok_ins,0,',','.');
                                              }
                                              //echo $stok_ins;
                                              $ukr1 += floatval($val->stok_ins);
                                        }
                                        if($val->stok_ins_finish_yard == 0){
                                            $stok_ins_finish_yard = "-";
                                            //echo $stok_ins_finish_yard;
                                        } else {
                                              if(fmod($val->stok_ins_finish_yard, 1) !== 0.00){
                                                $stok_ins_finish_yard = number_format($val->stok_ins_finish_yard,2,',','.');
                                              } else {
                                                $stok_ins_finish_yard = number_format($val->stok_ins_finish_yard,0,',','.');
                                              }
                                              //echo $stok_ins_finish_yard;
                                              $ukr2 += floatval($val->stok_ins_finish_yard);
                                        }
                                        if($val->stok_fol == 0){
                                            $stok_fol = "-";
                                            //echo $stok_fol;
                                        } else {
                                              if(fmod($val->stok_fol, 1) !== 0.00){
                                                $stok_fol = number_format($val->stok_fol,2,',','.');
                                              } else {
                                                $stok_fol = number_format($val->stok_fol,0,',','.');
                                              }
                                              //echo $stok_fol;
                                              $ukr3 += floatval($val->stok_fol);
                                        }
                                        if($val->stok_fol_finish_yard == 0){
                                            $stok_fol_finish_yard = "-";
                                            //echo $stok_fol_finish_yard;
                                        } else {
                                              if(fmod($val->stok_fol_finish_yard, 1) !== 0.00){
                                                $stok_fol_finish_yard = number_format($val->stok_fol_finish_yard,2,',','.');
                                              } else {
                                                $stok_fol_finish_yard = number_format($val->stok_fol_finish_yard,0,',','.');
                                              }
                                              //echo $stok_fol_finish_yard;
                                              $ukr4 += floatval($val->stok_fol_finish_yard);
                                        }
                                        if($stok_ins==0 && $stok_ins_finish_yard==0 && $stok_fol==0 && $stok_fol_finish_yard==0){} else {
                                    ?>
                                    <tr>
                                        <td><strong><?=$no;?></strong></td>
                                        <td><strong><?=$val->kode_konstruksi;?></strong></td>
                                        <td><?=$stok_ins;?></td>
                                        <td><?=$stok_ins_finish_yard;?></td>
                                        <td><?=$stok_fol;?></td>
                                        <td><?=$stok_fol_finish_yard;?></td>
                                    </tr>
                                    <?php $no++; 
                                } endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <td colspan="2"><strong>Total</strong></td>
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
                    <!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Menampilkan stok barang milik <strong>RJS</strong> di gudang <strong>Pusatex</strong>
							</p>
						</div>
						<div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                                <thead>
                                    <tr class="table-secondary">
                                        <th>No.</th>
                                        <th>Kode Konstruksi</th>
                                        <th>Inspect Grey</th>
                                        <th>Inspect Finish</th>
                                        <th>Folding Grey</th>
                                        <th>Folding Finish</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $ukr1=0; $ukr2=0; $ukr3=0; $ukr4=0; $no=1;
                                    foreach($qr2->result() as $n => $val):
                                        if($val->stok_ins == 0){
                                            $stok_ins = "-";
                                            //echo $stok_ins;
                                        } else {
                                              if(fmod($val->stok_ins, 1) !== 0.00){
                                                $stok_ins = number_format($val->stok_ins,2,',','.');
                                              } else {
                                                $stok_ins = number_format($val->stok_ins,0,',','.');
                                              }
                                              //echo $stok_ins;
                                              $ukr1 += floatval($val->stok_ins);
                                        }
                                        if($val->stok_ins_finish_yard == 0){
                                            $stok_ins_finish_yard = "-";
                                            //echo $stok_ins_finish_yard;
                                        } else {
                                              if(fmod($val->stok_ins_finish_yard, 1) !== 0.00){
                                                $stok_ins_finish_yard = number_format($val->stok_ins_finish_yard,2,',','.');
                                              } else {
                                                $stok_ins_finish_yard = number_format($val->stok_ins_finish_yard,0,',','.');
                                              }
                                              //echo $stok_ins_finish_yard;
                                              $ukr2 += floatval($val->stok_ins_finish_yard);
                                        }
                                        if($val->stok_fol == 0){
                                            $stok_fol = "-";
                                            //echo $stok_fol;
                                        } else {
                                              if(fmod($val->stok_fol, 1) !== 0.00){
                                                $stok_fol = number_format($val->stok_fol,2,',','.');
                                              } else {
                                                $stok_fol = number_format($val->stok_fol,0,',','.');
                                              }
                                              //echo $stok_fol;
                                              $ukr3 += floatval($val->stok_fol);
                                        }
                                        if($val->stok_fol_finish_yard == 0){
                                            $stok_fol_finish_yard = "-";
                                            //echo $stok_fol_finish_yard;
                                        } else {
                                              if(fmod($val->stok_fol_finish_yard, 1) !== 0.00){
                                                $stok_fol_finish_yard = number_format($val->stok_fol_finish_yard,2,',','.');
                                              } else {
                                                $stok_fol_finish_yard = number_format($val->stok_fol_finish_yard,0,',','.');
                                              }
                                              //echo $stok_fol_finish_yard;
                                              $ukr4 += floatval($val->stok_fol_finish_yard);
                                        }
                                        if($stok_ins==0 && $stok_ins_finish_yard==0 && $stok_fol==0 && $stok_fol_finish_yard==0){} else {
                                    ?>
                                    <tr>
                                        <td><strong><?=$no;?></strong></td>
                                        <td><strong><?=$val->kode_konstruksi;?></strong></td>
                                        <td><?=$stok_ins;?></td>
                                        <td><?=$stok_ins_finish_yard;?></td>
                                        <td><?=$stok_fol;?></td>
                                        <td><?=$stok_fol_finish_yard;?></td>
                                    </tr>
                                    <?php $no++; 
                                } endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <td colspan="2"><strong>Total</strong></td>
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
                    <!-- end data if rjs -->
<?php
                    }
                    if($sess_dep=="Samatex"){
                        $qr3 = $this->data_model->get_byid('report_stok', ['departement'=>'Samatex-IN-Pusatex']);
                        $qr4 = $this->data_model->get_byid('report_stok', ['departement'=>'RJS-IN-Samatex']); ?>
                    <!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Menampilkan stok barang milik <strong>Samatex</strong> di gudang <strong>Pusatex</strong>
							</p>
						</div>
						<div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                                <thead>
                                    <tr class="table-secondary">
                                        <th>No.</th>
                                        <th>Kode Konstruksi</th>
                                        <th>Inspect Grey</th>
                                        <th>Inspect Finish</th>
                                        <th>Folding Grey</th>
                                        <th>Folding Finish</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $ukr1=0; $ukr2=0; $ukr3=0; $ukr4=0; $no=1;
                                    foreach($qr3->result() as $n => $val):
                                        if($val->stok_ins == 0){
                                            $stok_ins = "-";
                                            //echo $stok_ins;
                                        } else {
                                              if(fmod($val->stok_ins, 1) !== 0.00){
                                                $stok_ins = number_format($val->stok_ins,2,',','.');
                                              } else {
                                                $stok_ins = number_format($val->stok_ins,0,',','.');
                                              }
                                              //echo $stok_ins;
                                              $ukr1 += floatval($val->stok_ins);
                                        }
                                        if($val->stok_ins_finish_yard == 0){
                                            $stok_ins_finish_yard = "-";
                                            //echo $stok_ins_finish_yard;
                                        } else {
                                              if(fmod($val->stok_ins_finish_yard, 1) !== 0.00){
                                                $stok_ins_finish_yard = number_format($val->stok_ins_finish_yard,2,',','.');
                                              } else {
                                                $stok_ins_finish_yard = number_format($val->stok_ins_finish_yard,0,',','.');
                                              }
                                              //echo $stok_ins_finish_yard;
                                              $ukr2 += floatval($val->stok_ins_finish_yard);
                                        }
                                        if($val->stok_fol == 0){
                                            $stok_fol = "-";
                                            //echo $stok_fol;
                                        } else {
                                              if(fmod($val->stok_fol, 1) !== 0.00){
                                                $stok_fol = number_format($val->stok_fol,2,',','.');
                                              } else {
                                                $stok_fol = number_format($val->stok_fol,0,',','.');
                                              }
                                              //echo $stok_fol;
                                              $ukr3 += floatval($val->stok_fol);
                                        }
                                        if($val->stok_fol_finish_yard == 0){
                                            $stok_fol_finish_yard = "-";
                                            //echo $stok_fol_finish_yard;
                                        } else {
                                              if(fmod($val->stok_fol_finish_yard, 1) !== 0.00){
                                                $stok_fol_finish_yard = number_format($val->stok_fol_finish_yard,2,',','.');
                                              } else {
                                                $stok_fol_finish_yard = number_format($val->stok_fol_finish_yard,0,',','.');
                                              }
                                              //echo $stok_fol_finish_yard;
                                              $ukr4 += floatval($val->stok_fol_finish_yard);
                                        }
                                        if($stok_ins==0 && $stok_ins_finish_yard==0 && $stok_fol==0 && $stok_fol_finish_yard==0){} else {
                                    ?>
                                    <tr>
                                        <td><strong><?=$no;?></strong></td>
                                        <td><strong><?=$val->kode_konstruksi;?></strong></td>
                                        <td><?=$stok_ins;?></td>
                                        <td><?=$stok_ins_finish_yard;?></td>
                                        <td><?=$stok_fol;?></td>
                                        <td><?=$stok_fol_finish_yard;?></td>
                                    </tr>
                                    <?php $no++; 
                                } endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <td colspan="2"><strong>Total</strong></td>
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
                    <!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Menampilkan stok barang milik <strong>RJS</strong> di gudang <strong>Samatex</strong>
							</p>
						</div>
						<div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                                <thead>
                                    <tr class="table-secondary">
                                        <th>No.</th>
                                        <th>Kode Konstruksi</th>
                                        <th>Inspect Grey</th>
                                        <th>Inspect Finish</th>
                                        <th>Folding Grey</th>
                                        <th>Folding Finish</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $ukr1=0; $ukr2=0; $ukr3=0; $ukr4=0; $no=1;
                                    foreach($qr4->result() as $n => $val):
                                        if($val->stok_ins == 0){
                                            $stok_ins = "-";
                                            //echo $stok_ins;
                                        } else {
                                              if(fmod($val->stok_ins, 1) !== 0.00){
                                                $stok_ins = number_format($val->stok_ins,2,',','.');
                                              } else {
                                                $stok_ins = number_format($val->stok_ins,0,',','.');
                                              }
                                              //echo $stok_ins;
                                              $ukr1 += floatval($val->stok_ins);
                                        }
                                        if($val->stok_ins_finish_yard == 0){
                                            $stok_ins_finish_yard = "-";
                                            //echo $stok_ins_finish_yard;
                                        } else {
                                              if(fmod($val->stok_ins_finish_yard, 1) !== 0.00){
                                                $stok_ins_finish_yard = number_format($val->stok_ins_finish_yard,2,',','.');
                                              } else {
                                                $stok_ins_finish_yard = number_format($val->stok_ins_finish_yard,0,',','.');
                                              }
                                              //echo $stok_ins_finish_yard;
                                              $ukr2 += floatval($val->stok_ins_finish_yard);
                                        }
                                        if($val->stok_fol == 0){
                                            $stok_fol = "-";
                                            //echo $stok_fol;
                                        } else {
                                              if(fmod($val->stok_fol, 1) !== 0.00){
                                                $stok_fol = number_format($val->stok_fol,2,',','.');
                                              } else {
                                                $stok_fol = number_format($val->stok_fol,0,',','.');
                                              }
                                              //echo $stok_fol;
                                              $ukr3 += floatval($val->stok_fol);
                                        }
                                        if($val->stok_fol_finish_yard == 0){
                                            $stok_fol_finish_yard = "-";
                                            //echo $stok_fol_finish_yard;
                                        } else {
                                              if(fmod($val->stok_fol_finish_yard, 1) !== 0.00){
                                                $stok_fol_finish_yard = number_format($val->stok_fol_finish_yard,2,',','.');
                                              } else {
                                                $stok_fol_finish_yard = number_format($val->stok_fol_finish_yard,0,',','.');
                                              }
                                              //echo $stok_fol_finish_yard;
                                              $ukr4 += floatval($val->stok_fol_finish_yard);
                                        }
                                        if($stok_ins==0 && $stok_ins_finish_yard==0 && $stok_fol==0 && $stok_fol_finish_yard==0){} else {
                                    ?>
                                    <tr>
                                        <td><strong><?=$no;?></strong></td>
                                        <td><strong><?=$val->kode_konstruksi;?></strong></td>
                                        <td><?=$stok_ins;?></td>
                                        <td><?=$stok_ins_finish_yard;?></td>
                                        <td><?=$stok_fol;?></td>
                                        <td><?=$stok_fol_finish_yard;?></td>
                                    </tr>
                                    <?php $no++; 
                                } endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <td colspan="2"><strong>Total</strong></td>
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
                    <!-- end if samatex -->
<?php
                    }
                    if($sess_dep=="Pusatex"){
                        $qr5 = $this->data_model->get_byid('report_stok', ['departement'=>'Samatex-IN-Pusatex']);
                        $qr6 = $this->data_model->get_byid('report_stok', ['departement'=>'RJS-IN-Pusatex']); ?>
                    <!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Menampilkan stok barang milik <strong>Samatex</strong> di gudang <strong>Pusatex</strong>
							</p>
						</div>
						<div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                                <thead>
                                    <tr class="table-secondary">
                                        <th>No.</th>
                                        <th>Kode Konstruksi</th>
                                        <th>Inspect Grey</th>
                                        <th>Inspect Finish</th>
                                        <th>Folding Grey</th>
                                        <th>Folding Finish</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $ukr1=0; $ukr2=0; $ukr3=0; $ukr4=0; $no=1;
                                    foreach($qr5->result() as $n => $val):
                                        if($val->stok_ins == 0){
                                            $stok_ins = "-";
                                            //echo $stok_ins;
                                        } else {
                                              if(fmod($val->stok_ins, 1) !== 0.00){
                                                $stok_ins = number_format($val->stok_ins,2,',','.');
                                              } else {
                                                $stok_ins = number_format($val->stok_ins,0,',','.');
                                              }
                                              //echo $stok_ins;
                                              $ukr1 += floatval($val->stok_ins);
                                        }
                                        if($val->stok_ins_finish_yard == 0){
                                            $stok_ins_finish_yard = "-";
                                            //echo $stok_ins_finish_yard;
                                        } else {
                                              if(fmod($val->stok_ins_finish_yard, 1) !== 0.00){
                                                $stok_ins_finish_yard = number_format($val->stok_ins_finish_yard,2,',','.');
                                              } else {
                                                $stok_ins_finish_yard = number_format($val->stok_ins_finish_yard,0,',','.');
                                              }
                                              //echo $stok_ins_finish_yard;
                                              $ukr2 += floatval($val->stok_ins_finish_yard);
                                        }
                                        if($val->stok_fol == 0){
                                            $stok_fol = "-";
                                            //echo $stok_fol;
                                        } else {
                                              if(fmod($val->stok_fol, 1) !== 0.00){
                                                $stok_fol = number_format($val->stok_fol,2,',','.');
                                              } else {
                                                $stok_fol = number_format($val->stok_fol,0,',','.');
                                              }
                                              //echo $stok_fol;
                                              $ukr3 += floatval($val->stok_fol);
                                        }
                                        if($val->stok_fol_finish_yard == 0){
                                            $stok_fol_finish_yard = "-";
                                            //echo $stok_fol_finish_yard;
                                        } else {
                                              if(fmod($val->stok_fol_finish_yard, 1) !== 0.00){
                                                $stok_fol_finish_yard = number_format($val->stok_fol_finish_yard,2,',','.');
                                              } else {
                                                $stok_fol_finish_yard = number_format($val->stok_fol_finish_yard,0,',','.');
                                              }
                                              //echo $stok_fol_finish_yard;
                                              $ukr4 += floatval($val->stok_fol_finish_yard);
                                        }
                                        if($stok_ins==0 && $stok_ins_finish_yard==0 && $stok_fol==0 && $stok_fol_finish_yard==0){} else {
                                    ?>
                                    <tr>
                                        <td><strong><?=$no;?></strong></td>
                                        <td><strong><?=$val->kode_konstruksi;?></strong></td>
                                        <td><?=$stok_ins;?></td>
                                        <td><?=$stok_ins_finish_yard;?></td>
                                        <td><?=$stok_fol;?></td>
                                        <td><?=$stok_fol_finish_yard;?></td>
                                    </tr>
                                    <?php $no++; 
                                } endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <td colspan="2"><strong>Total</strong></td>
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
                    <!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Menampilkan stok barang milik <strong>RJS</strong> di gudang <strong>Pusatex</strong>
							</p>
						</div>
						<div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                                <thead>
                                    <tr class="table-secondary">
                                        <th>No.</th>
                                        <th>Kode Konstruksi</th>
                                        <th>Inspect Grey</th>
                                        <th>Inspect Finish</th>
                                        <th>Folding Grey</th>
                                        <th>Folding Finish</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $ukr1=0; $ukr2=0; $ukr3=0; $ukr4=0; $no=1;
                                    foreach($qr6->result() as $n => $val):
                                        if($val->stok_ins == 0){
                                            $stok_ins = "-";
                                            //echo $stok_ins;
                                        } else {
                                              if(fmod($val->stok_ins, 1) !== 0.00){
                                                $stok_ins = number_format($val->stok_ins,2,',','.');
                                              } else {
                                                $stok_ins = number_format($val->stok_ins,0,',','.');
                                              }
                                              //echo $stok_ins;
                                              $ukr1 += floatval($val->stok_ins);
                                        }
                                        if($val->stok_ins_finish_yard == 0){
                                            $stok_ins_finish_yard = "-";
                                            //echo $stok_ins_finish_yard;
                                        } else {
                                              if(fmod($val->stok_ins_finish_yard, 1) !== 0.00){
                                                $stok_ins_finish_yard = number_format($val->stok_ins_finish_yard,2,',','.');
                                              } else {
                                                $stok_ins_finish_yard = number_format($val->stok_ins_finish_yard,0,',','.');
                                              }
                                              //echo $stok_ins_finish_yard;
                                              $ukr2 += floatval($val->stok_ins_finish_yard);
                                        }
                                        if($val->stok_fol == 0){
                                            $stok_fol = "-";
                                            //echo $stok_fol;
                                        } else {
                                              if(fmod($val->stok_fol, 1) !== 0.00){
                                                $stok_fol = number_format($val->stok_fol,2,',','.');
                                              } else {
                                                $stok_fol = number_format($val->stok_fol,0,',','.');
                                              }
                                              //echo $stok_fol;
                                              $ukr3 += floatval($val->stok_fol);
                                        }
                                        if($val->stok_fol_finish_yard == 0){
                                            $stok_fol_finish_yard = "-";
                                            //echo $stok_fol_finish_yard;
                                        } else {
                                              if(fmod($val->stok_fol_finish_yard, 1) !== 0.00){
                                                $stok_fol_finish_yard = number_format($val->stok_fol_finish_yard,2,',','.');
                                              } else {
                                                $stok_fol_finish_yard = number_format($val->stok_fol_finish_yard,0,',','.');
                                              }
                                              //echo $stok_fol_finish_yard;
                                              $ukr4 += floatval($val->stok_fol_finish_yard);
                                        }
                                        if($stok_ins==0 && $stok_ins_finish_yard==0 && $stok_fol==0 && $stok_fol_finish_yard==0){} else {
                                    ?>
                                    <tr>
                                        <td><strong><?=$no;?></strong></td>
                                        <td><strong><?=$val->kode_konstruksi;?></strong></td>
                                        <td><?=$stok_ins;?></td>
                                        <td><?=$stok_ins_finish_yard;?></td>
                                        <td><?=$stok_fol;?></td>
                                        <td><?=$stok_fol_finish_yard;?></td>
                                    </tr>
                                    <?php $no++; 
                                } endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <td colspan="2"><strong>Total</strong></td>
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
                    <!-- end if pusatex -->
<?php
                    }
                    ?>
                    
					<!-- <div class="bg-white pd-20 card-box mb-30">
						<div id="chart4"></div>
					</div> -->
        </div>
    </div>
</div>

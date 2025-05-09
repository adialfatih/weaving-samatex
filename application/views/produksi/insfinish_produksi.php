<?php $arb = [ '00'=>'undefined', '01' => 'Jan', '02' => 'Feb', '03' => 'Mar','04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des']; 
$tgl = $this->uri->segment(2);
$ex = explode('-', $tgl);
$printTgl = $ex[2]." ".$arb[$ex[1]]." ".$ex[0];
?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Inspect Finish</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="index.html">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="index.html">Data Produksi</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Inspect Finish
										</li>
									</ol>
								</nav>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a class="btn btn-primary dropdown-toggle no-arrow" href="<?=base_url('proses-produksi');?>" role="button"> Tambah Inspect Grey </a>
								</div>
							</div>
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Data Produksi Inspect Finish Tanggal <?=$printTgl;?>
							</p>
						</div>
						<div class="pb-20">
							<table class="data-table table hover nowrap">
								<thead>
									<tr class="table-primary">
									<th class="table-plus datatable-nosort">No</th>
										<th>Kode Roll</th>
										<th>Konstruksi</th>
										<th>Ukuran Sebelum</th>
										<th>Ukuran ORI (Yrd)</th>
										<th>BP</th>
										<th>BS</th>
										<th>CRT</th>
										<th>Operator</th>
                                        <th>#</th>
									</tr>
								</thead>
								<tbody>
									<?php
                                    $query = $this->data_model->get_byid('data_if',['tgl_potong'=>$tgl]);
									$ar_opt = array();
									$ar_kons = array();
                                    foreach($query->result() as $n => $val):
										if (in_array($val->konstruksi, $ar_kons)) {} else {
											$ar_kons[]=$val->konstruksi;
										}
										if (in_array($val->operator, $ar_opt)) {} else {
											$ar_opt[]=$val->operator;
										}
                                ?>
                                    <tr>
                                        <td><?=$n+1;?></td>
                                        <td><?=$val->kode_roll;?></td>
                                        <td><?=$val->konstruksi;?></td>
                                        <td><?=$val->ukuran_sebelum;?></td>
										<?php
										$oriyy = floatval($val->ukuran_ori) / 0.9144;
										if(fmod($oriyy, 1) !== 0.00){
											echo "<td>". number_format($oriyy,2,',','.')."</td>";
										} else {
											echo "<td>". number_format($oriyy,0,',','.')."</td>";
										}
										?>
                                        <td><?=$val->ukuran_bp;?></td>
                                        <td><?=$val->ukuran_bs;?></td>
                                        <td><?=$val->ukuran_crt;?></td>
                                        <td><?=ucfirst($val->operator);?></td>
                                        <td>
                                            <a href="javascript:;" style="color:red;" onclick="chdata('<?=$val->id_infs;?>','<?=$val->kode_roll;?>')" title="Hapus Data" data-toggle="modal" data-target="#Medium-modal">
                                            <i class="icon-copy fa fa-trash-o" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                <?php
                                    endforeach;
                                    ?>
								</tbody>
							</table>
						</div>
					</div>
					<?php 
						$data_array = array_unique($ar_kons);
						$data_array = array_values($data_array);
					?>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Data Produksi Inspect Finish Tanggal <?=$printTgl;?>
							</p>
						</div>
						<div class="pb-20">
							<table class="table hover nowrap">
								<thead>
									<tr class="table-primary">
										<th class="table-plus datatable-nosort">No</th>
										<th>Nama Operator</th>
										<?php
										foreach($ar_kons as $kons){
											echo "<th>".$kons."</th>";
										}
										?>
										<th>Total per operator</th>
									</tr>
								</thead>
								<tbody>
									<?php
										foreach($ar_opt as $n => $opt){
											$num = $n+1;
											echo "<tr>";
											echo "<td>".$num."</td>";
											echo "<td>".ucfirst($opt)."</td>";
											$totalPerOpt = 0;
											foreach($data_array as $kons){
												$qr = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_if WHERE tgl_potong='$tgl' AND operator='$opt' AND konstruksi='$kons'")->row("ukr");
												echo "<td>";
												if($qr==""){
													echo "-";
												} else {
													if($qr < 1){
														echo "-";
													} else {
														$qry = floatval($qr) / 0.9144;
														if(fmod($qry, 1) !== 0.00){
															echo "". number_format($qry,2,',','.')."";
														} else {
															echo "". number_format($qry,0,',','.')."";
														}
													}
												}
												echo "</td>";
												$totalPerOpt+=floatval($qry);
											}
											if(fmod($totalPerOpt, 1) !== 0.00){
												echo "<td>". number_format($totalPerOpt,2,',','.')."<span style='color:red;font-size:10px;'>&nbsp;Yard</span></td>";
											} else {
												echo "<td>". number_format($totalPerOpt,0,',','.')."<span style='color:red;font-size:10px;'>&nbsp;Yard</span></td>";
											}
											echo "</tr>";
										}
									?>
									<tr class="table-primary">
										<th colspan="2">Total</th>
										<?php
											$allTotalOwek = 0;
											foreach($data_array as $kons){
												$qr = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_if WHERE tgl_potong='$tgl' AND konstruksi='$kons'")->row("ukr");
												echo "<td>";
												if($qr==""){
													echo "-";
												} else {
													if($qr < 1){
														echo "-";
													} else {
														$qry = floatval($qr) / 0.9144;
														if(fmod($qry, 1) !== 0.00){
															echo "". number_format($qry,2,',','.')."";
														} else {
															echo "". number_format($qry,0,',','.')."";
														}
													}
												}
												echo "</td>";
												$allTotalOwek+=floatval($qry);
											}
											if(fmod($allTotalOwek, 1) !== 0.00){
												echo "<td>". number_format($allTotalOwek,2,',','.')."<span style='color:red;font-size:10px;'>&nbsp;Yard</span></td>";
											} else {
												echo "<td>". number_format($allTotalOwek,0,',','.')."<span style='color:red;font-size:10px;'>&nbsp;Yard</span></td>";
											}
										?>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<?php 
						echo "<pre>";
						print_r($data_array);
						echo "</pre>";
					?>
                                <div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Hapus Data
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<div class="modal-body" id="mdlbodyid">
												<p>
													Lorem ipsum dolor sit amet, consectetur adipisicing
													elit, sed do eiusmod tempor incididunt ut labore et
													dolore magna aliqua. Ut enim ad minim veniam, quis
													nostrud exercitation ullamco laboris nisi ut aliquip
													ex ea commodo consequat. Duis aute irure dolor in
													reprehenderit in voluptate velit esse cillum dolore eu
													fugiat nulla pariatur. Excepteur sint occaecat
													cupidatat non proident, sunt in culpa qui officia
													deserunt mollit anim id est laborum.
												</p>
											</div>
											<div class="modal-footer">
                                                <form action="<?=base_url('produksistx/delinsfinish');?>" method="post">
                                                <input type="hidden" name="iddata" id="kdroll1">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">
													Close
												</button>
												<button type="submit" class="btn btn-danger">
													Delete
												</button></form>
											</div>
										</div>
									</div>
								</div>
        </div>
    </div>
</div>
<script>
    function chdata(id,kd){
        document.getElementById('mdlbodyid').innerHTML = '<p>Anda akan menghapus Proses Inspect Finish untuk kode roll <strong>'+kd+'</strong></p>';
        document.getElementById('kdroll1').value = ''+kd;
    }
    function editdata(kd,kons,mc,beam,oka,ori,bs,bp){
        document.getElementById('autoComplete').value = ''+kons;
        document.getElementById('konsbefore').value = ''+kons;
        document.getElementById('iptkoderoll').value = ''+kd;
        document.getElementById('iptmc').value = ''+mc;
        document.getElementById('iptbeam').value = ''+beam;
        document.getElementById('iptoka').value = ''+oka;
        document.getElementById('iptukuran').value = ''+ori;
        document.getElementById('iptbs').value = ''+bs;
        document.getElementById('iptbp').value = ''+bp;
    }
</script>
<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Informasi Produksi (Nomor Packinglist : <?=$kdp;?>)</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="Javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="Javascript:void(0);">Data Produksi</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Detail
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<!-- Simple Datatable start -->
				<?php if($from_ig!="null"){ 
					$kdop = $from_ig->row("kode_produksi");
					$pkg_from = $this->data_model->get_byid('new_tb_pkg_list', ['kode_produksi'=>$kdop]);
					$jns_kain = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kdop])->row("kode_konstruksi");
				?>
				<!-- basic table  Start -->
                <div class="pd-20 card-box mb-30">
						<div class="clearfix mb-20">
							<div class="pull-left">
								<h4 class="h4">Jenis Kain <span class="text-blue "><?=$jns_kain;?></span></h4>
							</div>
						</div>
						<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr class="table-active">
									<th scope="col"></th>
									<th scope="col" colspan="7">Inspect Grey</th>
									
								</tr>
                                <tr class="table-active">
                                    <td rowspan="2">#</td>
                                    <td rowspan="2">Kode</td>
                                    <td colspan="4" class="text-center">Ukuran</td>
									<td rowspan="2">Tanggal</td>
									<td rowspan="2">Operator</td>
                                </tr>
								<tr>
									<td>Origin</td>
									<td>B</td>
									<td>C</td>
									<td>BS</td>
								</tr>
							</thead>
							<tbody>
                                <?php foreach($pkg_from->result() as $n => $val): ?>
								<tr>
									<th scope="row"><?=$n+1;?></th>
									<td><?=$val->no_roll;?></td>
									<td>
										<?php
										if($val->satuan=="Meter"){
											if($val->ukuran_ori==0){echo"0";} else {
											echo round($val->ukuran_ori,2)." m"; }
										} else {
											if($val->ukuran_ori_yard==0){echo"0";} else {
											echo round($val->ukuran_ori_yard,2)." y"; }
										}
										?>
									</td>
									<td>
									<?php 
										if($val->satuan=="Meter"){
											if($val->ukuran_b==0){echo"0";} else {
											echo round($val->ukuran_b,2)." m"; }
										} else {
											if($val->ukuran_b_yard==0){echo"0";} else {
											echo round($val->ukuran_b_yard,2)." y"; }
										}
										?>									
									</td>
									<td>
									<?php
										if($val->satuan=="Meter"){
											if($val->ukuran_c==0){echo"0";} else {
											echo round($val->ukuran_c,2)." m"; }
										} else {
											if($val->ukuran_c_yard==0){echo"0";} else {
											echo round($val->ukuran_c_yard,2)." y"; }
										}
										?>
									</td>
									<td>
									<?php
										if($val->satuan=="Meter"){
											if($val->ukuran_bs==0){echo"0";} else {
											echo round($val->ukuran_bs,2)." m"; }
										} else {
											if($val->ukuran_bs_yard==0){echo"0";} else {
											echo round($val->ukuran_bs_yard,2)." y"; }
										}
										?>
									</td>
									<td><?php $ex2 = explode('-',$dt['tgl_produksi']); echo "".$ex2[2]." ".$bln[$ex2[1]]." ".$ex2[0].""; ?></td>
									<td><?=$val->operator;?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						</div>
					</div>

					<!-- basic table  End -->
				<?php } ?>
				<!-- basic table  Start -->
                <div class="pd-20 card-box mb-30">
						<div class="clearfix mb-20">
							<div class="pull-left">
								<h4 class="h4">Jenis Kain <span class="text-blue "><?=$dt['kode_konstruksi'];?></span></h4>
							</div>
							<?php
								$cek_folgrey = $this->data_model->get_byid('tb_proses_produksi',['kode_produksi'=>$kdp, 'proses_name'=>'FG']);
								$cek_insfns = $this->data_model->get_byid('tb_proses_produksi',['kode_produksi'=>$kdp, 'proses_name'=>'IF']);
								$cek_folfns = $this->data_model->get_byid('tb_proses_produksi',['kode_produksi'=>$kdp, 'proses_name'=>'FF']);
							?>
							<div class="text-right">
								<div class="dropdown">
									<button type="submit" class="btn" data-bgcolor="#e3e3e3" data-color="#000">
										<i class="icon-copy bi bi-printer-fill"></i> &nbsp; Cetak
                                    </button>
								</div>
							</div>
						</div>
						<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr class="table-active">
									<th scope="col"></th>
									<th scope="col" colspan="9">Inspect <?=$pkg_awal=='IG'?'Grey':'Finish';?></th>
								</tr>
                                <tr class="table-active">
                                    <td rowspan="2">#</td>
                                    <td rowspan="2">Kode</td>
                                    <td rowspan="2">No MC</td>
                                    <td rowspan="2">No Beam</td>
                                    <td colspan="4" class="text-center">Ukuran</td>
									<td rowspan="2">Tanggal</td>
									<td rowspan="2">Operator</td>
                                </tr>
								<tr>
									<td>Origin</td>
									<td>B</td>
									<td>C</td>
									<td>BS</td>
								</tr>
							</thead>
							<tbody>
                                <?php foreach($pkg->result() as $n => $val): 
									if($val->ukuran_ori!=0){ ?>
								<tr>
									<th scope="row"><?=$n+1;?></th>
									<td><?=$val->no_roll;?></td>
									<td><?=$val->no_mesin;?></td>
									<td><?=$val->no_beam;?></td>
									<td>
									<?php
										if($val->satuan=="Meter"){
											if($val->ukuran_ori==0){echo"0";} else {
											echo round($val->ukuran_ori,2)." m"; }
										} else {
											if($val->ukuran_ori_yard==0){echo"0";} else {
											echo round($val->ukuran_ori_yard,2)." y"; }
										}
										?>
									</td>
									<td>
									<?php
										if($val->satuan=="Meter"){
											if($val->ukuran_b==0){echo"0";} else {
											echo round($val->ukuran_b,2)." m"; }
										} else {
											if($val->ukuran_b_yard==0){echo"0";} else {
											echo round($val->ukuran_b_yard,2)." y"; }
										}
										?>
									</td>
									<td>
									<?php
										if($val->satuan=="Meter"){
											if($val->ukuran_c==0){echo"0";} else {
											echo round($val->ukuran_c,2)." m"; }
										} else {
											if($val->ukuran_c_yard==0){echo"0";} else {
											echo round($val->ukuran_c_yard,2)." y"; }
										}
										?>
									</td>
									<td>
									<?php
										if($val->satuan=="Meter"){
											if($val->ukuran_bs==0){echo"0";} else {
											echo round($val->ukuran_bs,2)." m"; }
										} else {
											if($val->ukuran_bs_yard==0){echo"0";} else {
											echo round($val->ukuran_bs_yard,2)." y"; }
										}
										?></td>
									<td><?php $ex1 = explode('-',$dt['tgl_produksi']); echo "".$ex1[2]." ".$bln[$ex1[1]]." ".$ex1[0].""; ?></td>
									<td><?=$val->operator;?></td>
								</tr>
								<?php } endforeach; ?>
							</tbody>
						</table>
						<?php if($cek_folgrey->num_rows()!=0){ ?>
						<table class="table table-striped">
							<thead>
								<tr class="table-active">
									<th scope="col"></th>
									<th scope="col" colspan="5">Folding Grey</th>
									
								</tr>
                                <tr class="table-active">
                                    <td rowspan="2">#</td>
                                    <td rowspan="2">No MC</td>
                                    <td colspan="2" class="text-center">Ukuran</td>
									<td rowspan="2">Tanggal</td>
									<td rowspan="2">Operator</td>
                                </tr>
								<tr>
									<td>Ukuran Awal</td>
									<td>Ukuran sekarang</td>
								</tr>
							</thead>
							<tbody>
                                <?php 
								$newpkg = $this->data_model->get_byid('new_tb_pkg_fol', ['kode_produksi'=>$kdp, 'st_folding'=>'Grey']);
								foreach($newpkg->result() as $n => $val): ?>
								<tr>
									<th scope="row"><?=$n+1;?></th>
									<td><?=$val->no_roll;?></td>
									<td><?=$val->ukuran." m";?></td>
									<td><?=$val->ukuran_now." m";?></td>
									<td><?php $ex3 = explode('-',$val->tgl); echo "".$ex3[2]." ".$bln[$ex3[1]]." ".$ex3[0].""; ?></td>
									<td><?=$val->operator;?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<?php } ?>
						<?php if($cek_insfns->num_rows()!=0){ ?>
						<table class="table table-striped">
							<thead>
								<tr class="table-active">
									<th scope="col"></th>
									<th scope="col" colspan="8">Inspect Finish</th>
									
								</tr>
                                <tr class="table-active">
                                    <td rowspan="2">#</td>
                                    <td rowspan="2">No Roll</td>
                                    <td colspan="5" class="text-center">Ukuran</td>
									<td rowspan="2">Tanggal</td>
									<td rowspan="2">Operator</td>
                                </tr>
								<tr>
									<td>Origin</td>
									<td>A</td>
									<td>B</td>
									<td>C</td>
									<td>BS</td>
								</tr>
							</thead>
							<tbody>
                                <?php 
								$newpkg = $this->data_model->get_byid('new_tb_pkg_ins', ['kode_produksi'=>$kdp]);
								foreach($newpkg->result() as $n => $val): ?>
								<tr <?=$val->operator=='SL' ? 'class="table-danger"':'';?>>
									<th scope="row"><?=$n+1;?></th>
									<td><?=$val->no_roll;?></td>
									<td><?=$val->ukuran_ori_yard==0?'0':''.$val->ukuran_ori_yard.' y';?></td>
									<td><?=$val->ukuran_a_yard==0?'0':''.$val->ukuran_a_yard.' y';?></td>
									<td><?=$val->ukuran_b_yard==0?'0':''.$val->ukuran_b_yard.' y';?></td>
									<td><?=$val->ukuran_c_yard==0?'0':''.$val->ukuran_c_yard.' y';?></td>
									<td><?=$val->ukuran_bs_yard==0?'0':''.$val->ukuran_bs_yard.' y';?></td>
									<td><?php $ex4 = explode('-',$val->tgl); echo "".$ex4[2]." ".$bln[$ex4[1]]." ".$ex4[0].""; ?></td>
									<td><?=$val->operator=='SL' ? 'Stok Lama':$val->operator;?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<?php } ?>
						<?php if($cek_folfns->num_rows()!=0){ ?>
						<table class="table table-striped">
							<thead>
								<tr class="table-active">
									<th scope="col"></th>
									<th scope="col" colspan="5">Folding Finish</th>
								</tr>
                                <tr class="table-active">
                                    <td rowspan="2">#</td>
                                    <td rowspan="2">No Roll</td>
                                    <td colspan="2" class="text-center">Ukuran</td>
									<td rowspan="2">Tanggal</td>
									<td rowspan="2">Operator</td>
                                </tr>
								<tr>
									<td>Ukuran Awal</td>
									<td>Ukuran Saat ini</td>
								</tr>
							</thead>
							<tbody>
                                <?php 
								$newpkg = $this->data_model->get_byid('new_tb_pkg_fol', ['kode_produksi'=>$kdp, 'st_folding'=>'Finish']);
								foreach($newpkg->result() as $n => $val): ?>
								<tr>
									<th scope="row"><?=$n+1;?></th>
									<td><?=$val->no_roll;?></td>
									<td><?=$val->ukuran_yard;?> y</td>
									<td><?=$val->ukuran_now_yard;?> y</td>
									<td><?=$val->tgl;?></td>
									<td><?=$val->operator;?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<?php } ?>
						</div>
					</div>
					<!-- basic table  End -->
                
					
        </div>
    </div>
</div>
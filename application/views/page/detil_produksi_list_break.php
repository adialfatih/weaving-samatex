<?php $bln = [
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember',
]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Informasi Produksi (Nomor Packinglist : <?=$kdp;?> <?=$alpa=='0'?'':'-'.$alpa.'';?>)</h4>
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
				<!-- basic table  Start -->
                <div class="pd-20 card-box mb-30">
						<div class="clearfix mb-20">
							<div class="pull-left">
								<h4 class="h4">Jenis Kain <span class="text-blue "><?=$dt['kode_konstruksi'];?></span></h4>
							</div>
							
						</div>
						<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr class="table-active">
									<th scope="col"></th>
									<th scope="col" colspan="5">Inspect Grey</th>
									<th scope="col" colspan="3">Folding Grey</th>
									<th scope="col" colspan="6">Inspect Finish</th>
									<th scope="col" colspan="4">Folding Finish</th>
								</tr>
                                <tr class="table-active">
                                    <td rowspan="2">#</td>
                                    <td rowspan="2">No MC</td>
                                    <td colspan="4" class="text-center">Ukuran</td>
                                    <td rowspan="2">Tanggal</td>
                                    <td rowspan="2">Ukuran</td>
                                    <td rowspan="2">Operator</td>
                                    <td rowspan="2">Tanggal</td>
                                    <td colspan="4" class="text-center">Ukuran</td>
                                    <td rowspan="2">Operator</td>
                                    <td rowspan="2">Tanggal</td>
                                    <td rowspan="2">Ukuran</td>
                                    <td rowspan="2">Operator</td>
                                </tr>
								<tr>
									<td>Origin</td>
									<td>B</td>
									<td>C</td>
									<td>BS</td>
									<td>Origin</td>
									<td>B</td>
									<td>C</td>
									<td>BS</td>
								</tr>
							</thead>
							<tbody>
                                <?php foreach($pkg->result() as $n => $val): ?>
								<tr>
									<th scope="row"><?=$n+1;?></th>
									<td><?=$val->ig_nomc;?></td>
									<td><?=$val->ig_ukuran;?> <small><?=$val->ig_satuan=='Yard' ? 'y':'';?><?=$val->ig_satuan=='Meter' ? 'm':'';?></small></td>
									<td><?=$val->ig_ukuranb;?> <small><?=$val->ig_satuan=='Yard' ? 'y':'';?><?=$val->ig_satuan=='Meter' ? 'm':'';?></small></td>
									<td><?=$val->ig_ukuranc;?> <small><?=$val->ig_satuan=='Yard' ? 'y':'';?><?=$val->ig_satuan=='Meter' ? 'm':'';?></small></td>
									<td><?=$val->ig_ukuranbs;?> <small><?=$val->ig_satuan=='Yard' ? 'y':'';?><?=$val->ig_satuan=='Meter' ? 'm':'';?></small></td>
									<td><?=$val->fg_tgl=='0000-00-00' ? '':$val->fg_tgl;?></td>
                                    <?php if($val->fg_ukuran==0){ echo "<td></td>"; } else { ?>
                                    <td><?=$val->fg_ukuran;?> <small><?=$val->fg_satuan=='Yard' ? 'Y':'';?><?=$val->fg_satuan=='Meter' ? 'M':'';?></small></td><?php } ?>
									<td><?=$val->fg_operator=='null'?'':$val->fg_operator;?></td>
                                    <td><?=$val->if_tgl=='0000-00-00' ? '':$val->if_tgl;?></td>
                                    <?php if($val->if_ukuran==0){ echo "<td></td><td></td><td></td><td></td>"; } else { ?>
                                    <td><?=$val->if_ukuran;?> <small><?=$val->if_satuan=='Yard' ? 'y':'';?><?=$val->if_satuan=='Meter' ? 'm':'';?></small></td>
                                    <td><?=$val->if_ukuranb;?> <small><?=$val->if_satuan=='Yard' ? 'y':'';?><?=$val->if_satuan=='Meter' ? 'm':'';?></small></td>
                                    <td><?=$val->if_ukuranc;?> <small><?=$val->if_satuan=='Yard' ? 'y':'';?><?=$val->if_satuan=='Meter' ? 'm':'';?></small></td>
                                    <td><?=$val->if_ukuranbs;?> <small><?=$val->if_satuan=='Yard' ? 'y':'';?><?=$val->if_satuan=='Meter' ? 'm':'';?></small></td><?php } ?>
									<td><?=$val->if_operator=='null'?'':$val->if_operator;?></td>
                                    <td><?=$val->ff_tgl=='0000-00-00' ? '':$val->ff_tgl;?></td>
                                    <?php if($val->ff_ukuran==0){ echo "<td></td>"; } else { ?>
                                    <td><?=$val->ff_ukuran;?> <small><?=$val->ff_satuan=='Yard' ? 'y':'';?><?=$val->ff_satuan=='Meter' ? 'm':'';?></td></small><?php } ?>
									<td><?=$val->ff_operator=='null'?'':$val->ff_operator;?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						</div>
					</div>
					<!-- basic table  End -->
                
					
        </div>
    </div>
</div>
<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data Packaging List BS</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="Javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="Javascript:void(0);">Data</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Packaging List BS
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<!-- Simple Datatable start -->
				<form action="<?=base_url('surat-jalan/bs');?>" name="fr12" method="post">
				<!-- basic table  Start -->
                <div class="pd-20 card-box mb-30">
						<div class="clearfix mb-20">
							<div class="pull-left">
								<h4 class="h4">Data Packaging List BS</h4>
							</div>
							<div class="pull-right">
								<button type="submit" class="btn btn-primary" onclick="submitfr()">
									<i class="icon-copy bi bi-truck"></i> &nbsp;&nbsp; Kirim Barang
								</button>
							</div>
						</div>
						<div class="table-responsive">
						<form action="<?=base_url('surat-jalan');?>" name="fr12" id="fr12" method="post">
						<input type="hidden" id="ygdipilih" name="text_kode"></form>
						<table class="data-table table stripe hover nowrap">
							<thead>
								<tr>
                                    <th>No.</th>
                                    <th>Kode Paket</th>
									<th>Konstruksi</th>
									<th>Tujuan</th>
									<th>Jml roll</th>
									<th>Total Panjang</th>
									<th>Total Berat</th>
									<th>SJ</th>
									<th>Pilih</th>
                                </tr>
							</thead>
							<tbody>
                            <?php
                            $dtpkt = $this->db->query("SELECT * FROM bs_paket  ORDER BY idpktbs DESC");
                            if($dtpkt->num_rows() > 0){
                                $no = 1;
                                foreach($dtpkt->result() as $pkt){
                                $jenis = $pkt->jenis;
                                $pjg = $pkt->panjang;
                                if($jenis == "finish"){
                                    $pjg = floatval($pjg) * 0.9144;
                                    $sat = "Yard";
                                } else {
                                    $pjg = floatval($pjg);
                                    $sat = "Meter";
                                }
                                if($pjg == floor($pjg)){
                                    $npjg = number_format($pjg,0,',','.');
                                } else {
                                    $npjg = number_format($pjg,2,',','.');
                                }
                                ?>
                                <tr>
                                    <td><?=$no++;?></td>
                                    <td><?=$pkt->pktbs;?></td>
                                    <td><?=$pkt->konstruksi;?></td>
                                    <td><?=strtoupper($pkt->tujuan);?></td>
                                    <td><?=$pkt->jmlroll;?></td>
                                    <td><?=$npjg." ".$sat;?></td>
                                    <td><?=$pkt->berat;?> Kg</td>
									<?php
									if($pkt->surat_jalan == "null"){?>
									<td>-</td>
									<td><input type="checkbox" id="set<?=$pkt->pktbs;?>" name="pilih[]" value="<?=$pkt->pktbs;?>"></td>
									<?php
									} else {
									?>
                                    <td><a href="<?=base_url('surat-jalan/bs/'.$pkt->surat_jalan);?>" style="color:blue;"><?=strtoupper($pkt->surat_jalan);?></a></td>
									<td><a href="<?=base_url('cetak/notabs/'.$pkt->surat_jalan.'/1');?>" target="_blank"><i class='icon-copy bi bi-printer'></i></a></td>
									<?php } ?>
                                </tr>               
                            <?php } } ?>
							</tbody>
						</table>
						
					</div>
				</form>
					
        </div>
    </div>
</div>

</script>
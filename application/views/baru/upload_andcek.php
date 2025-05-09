<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', '00' => 'Undefined' ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="title">
							<h4>Surat Jalan <?=$dep_user=="Samatex" ? "Samatex" : "Rindang Jati";?></h4>
							<p>Menampilkan <strong>50</strong> surat jalan terkahir pengiriman dari Rindang Jati.</p>
							
					    </div>
							
						<div class="body">
							<hr>
						<form>
							<div class="row">
								<div class="col-md-4 col-sm-12">
									<div class="form-group">
										<label>Tampil Berdasarkan Bulan</label>
										<input type="month" class="form-control" onchange="carisjthis(this.value, 'bln')" />
									</div>
								</div>
								<div class="col-md-4 col-sm-12">
									<div class="form-group">
										<label>Tampilkan Per Tanggal</label>
										<input type="date" class="form-control" onchange="carisjthis(this.value, 'tgl')" />
									</div>
								</div>
								<div class="col-md-4 col-sm-12">
									<div class="form-group">
										<label>Cari Surat Jalan</label>
										<input type="text" class="form-control" placeholder="Ketikan Surat Jalan" onkeyup="carisjthis(this.value, 'sj')" />
									</div>
								</div>
							</div>
						</form>
						<hr>
							<table class="table table-bordered table-hover">
								<thead>
								<tr>
									<th>No.</th>
									<th>Tanggal</th>
									<th>No SJ</th>
									<th>Tujuan Kirim</th>
									<th>PackingList</th>
									<th>Total Panjang </th>
									<th>Lock</th>
								</tr>
								</thead>
								<tbody id="bodyTable">
									<?php
									$no=1;
									foreach($dtrow->result() as $dr):
									$ex = explode('-', $dr->tgl_kirim);
									$printTgl = $ex[2]." ".$bln[$ex[1]]." ".$ex[0];
									$sj = $dr->no_sj;
									$jmlpkg = $this->db->query("SELECT COUNT(no_sj) as jml FROM new_tb_packinglist WHERE no_sj='$sj'")->row("jml");
									$ttl_panjang = $this->db->query("SELECT SUM(ttl_panjang) as jml FROM new_tb_packinglist WHERE no_sj='$sj'")->row("jml");
									?>
									<tr>
										<td><?=$no;?></td>
										<td><?=$printTgl;?></td>
										<td><?=$sj;?></td>
										<td>
											<?php if($dr->tujuan_kirim == "Samatex"){
												echo "<span style='background:#2e73f2;color:#fff;font-size:10px;padding:3px 5px;border-radius:4px;'>Samatex</span>";
											} elseif($dr->tujuan_kirim == "Pusatex") {
												echo "<span style='background:#189e23;color:#fff;font-size:10px;padding:3px 5px;border-radius:4px;'>Pusatex</span>";
											} else {
												echo "<span style='background:red;color:#fff;font-size:10px;padding:3px 5px;border-radius:4px;'>Customer</span>";
											}
											if($dr->tujuan_kirim == "cus"){
												$idcus = $dr->id_customer;
												$nmkosn = $this->data_model->get_byid('dt_konsumen', ['id_konsumen'=>$idcus])->row("nama_konsumen");
												$nmcus2 = strtolower($nmkosn);
												$nmcus = ucwords($nmcus2);
												echo "<span style='background:orange;color:#fff;font-size:10px;padding:3px 5px;border-radius:4px;margin-left:5px;'>$nmcus</span>";
											}
											?>
										</td>
										<?php
										$cekdtlock = $this->data_model->get_byid('lock_surat_jalan',['nosj'=>$sj]);
										if($cekdtlock->num_rows() == 1){
											echo "<td>".$cekdtlock->row('jml_pkg')."</td>";
											if(fmod($cekdtlock->row('total_pjg'), 1) !== 0.00){
												echo "<td>".number_format($cekdtlock->row('total_pjg'),2,',','.')."</td>";
											} else {
												echo "<td>".number_format($cekdtlock->row('total_pjg'),0,',','.')."</td>";
											}
										} else {
										?>
										<td><?=$jmlpkg;?></td>
										<td>
											<?php if(fmod($ttl_panjang, 1) !== 0.00){
												echo number_format($ttl_panjang,2,',','.');
											} else {
												echo number_format($ttl_panjang,0,',','.');
											}
											?>
										</td>
										<?php } ?>
										<td>
											<?php if($dr->create_lock == "yes"){ ?>
											<a href="<?=base_url('reload/sj/'.$sj.'/'.$jmlpkg.'/'.$ttl_panjang);?>">
											<i class="icon-copy bi bi-shield-lock-fill" title="Packinglist Telah di kunci" style='color:#f58a07;font-size:20px;'></i></a>
											<?php } else { ?>
											<a href="<?=base_url('reload/sj/'.$sj.'/'.$jmlpkg.'/'.$ttl_panjang);?>">
											<i class="icon-copy bi bi-shield-lock-fill" title="Packinglist Belum di kunci. Data bisa saja Hilang" style='color:#ccc;font-size:20px;'></i></a>
											<?php } ?>
										</td>
									</tr>
									<?php $no++; endforeach; ?>
								</tbody>
							</table>
						</div>
                        
					</div>
					<!-- Simple Datatable start -->
					<!-- Bootstrap Select End -->
					
					
        </div>
    </div>
</div>

<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Buat Nota Penjualan</h4>
                                    <small>Pilih packing list berdasarkan surat jalan untuk membuat nota</small>
								</div>
							</div>
							
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort">No</th>
										<th>Surat Jalan</th>
										<th>Tanggal</th>
										<th>Jumlah Packing list</th>
										<th>Konstruksi</th>
                                        <th>Konsumen</th>
                                        <th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($dt_sj->result() as $n => $val): ?>
                                    <tr>
                                        <td><?=$n+1;?></td>
                                        <td><?=$val->no_sj;?></td>
                                        <td>
                                            <?php $ex=explode('-', $val->tgl_kirim); echo $ex[2]." ".$bln[$ex[1]]." ".$ex[0]; ?>
                                        </td>
                                        <td>
                                            <?php $jml_pkg = $this->data_model->get_byid('new_tb_packinglist',['no_sj'=>$val->no_sj]); echo $jml_pkg->num_rows(); ?>
                                        </td>
                                        <td>
                                            <?php 
                                            foreach($jml_pkg->result() as $no => $kons): 
                                                if($no!=0){ echo ","; }
                                                echo $kons->kode_konstruksi;
                                                 
                                            endforeach; 
                                            ?>
                                        </td>
                                        <td>
                                            <?php $cus = $this->data_model->get_byid('dt_konsumen',['id_konsumen'=>$val->id_customer])->row("nama_konsumen");
                                            echo $cus; ?>
                                        </td>
                                        <td>
                                            <a href="<?=base_url('nota/baru/'.sha1($val->id_sj));?>">
							                    <button class="btn btn-primary">Pilih</button>
                                            </a>
                                        </td>
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

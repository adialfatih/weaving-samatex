<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Riwayat Pembayaran Konsumen</h4>
                                    <small>Menampilkan semua pembayaran konsumen atas nama <strong><?=$customer['nama_konsumen'];?></strong></small>
								</div>
							</div>
							
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr class="table-primary">
                                            <th>No.</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Nominal</th>
                                            <th>Metode Bayar</th>
                                            <th>Nomor Bukti</th>
                                            <th>Nomor Nota</th>
                                            <th>Tanggal Input</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($allbayar->result() as $n => $val): 
                                            $ex = explode('-', $val->tgl_pemb);
                                            $printTglBayar = $ex[2]." ".$bln[$ex[1]]." ".$ex[0];
                                            $er = explode(' ', $val->tmstpm);
                                            $ert = explode('-', $er[0]);
                                            $printTglInput = $ert[2]."-".$bln[$ert[1]]."-".$ert[0].", ".$er[1];
                                        ?>
                                        <tr>
                                            <td><?=$n+1;?></td>
                                            <td><?=$printTglBayar;?></td>
                                            <td>
                                                <?php
                                                    if(fmod($val->nominal_pemb, 1) !== 0.00){
                                                        $ttl = number_format($val->nominal_pemb,2,',','.');
                                                    } else {
                                                        $ttl = number_format($val->nominal_pemb,0,',','.');
                                                    }
                                                    echo "Rp. ".$ttl; 
                                                ?>
                                            </td>
                                            <td><?=$val->metode_bayar;?></td>
                                            <td><?=$val->nomor_bukti;?></td>
                                            <td><?=$val->id_nota;?></td>
                                            <td><?=$printTglInput;?></td>
                                            <td><a href="Del" onclick="del34('<?=$val->id_pemb2;?>')" title="Hapus Pembayaran" data-toggle="modal" data-target="#Medium-modal"><i class="icon-copy fa fa-trash" style="color:red;font-size:16px;" aria-hidden="true"></i></a></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
						</div>
					</div>
					<!-- Simple Datatable End -->
                    <!-- Medium modal -->
								<div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Pembayaran Konsumen
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<form name="fr2" action="<?=base_url('nota/del_bayar');?>" method="post">
											<div class="modal-body">
                                                <input type="hidden" name="idcus" value="<?=$customer['id_konsumen'];?>">
                                                <input type="hidden" id="idpemb2" name="id_pemb">
												Anda yakin akan menghapus Pembayaran Konsumen ?
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">
													Tutup
												</button>
												<button type="submit" class="btn btn-danger">
													Hapus
												</button>
											</div>
											</form>
										</div>
									</div>
								</div>
					
        </div>
    </div>
</div>
<script>
    function del34(id){
        document.getElementById('idpemb2').value =''+id;
    }
</script>
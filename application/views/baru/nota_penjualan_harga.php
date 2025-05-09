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
                    <?php
                        $idkonsumen = $dt_sj['id_customer'];
                        $kons = $this->data_model->get_byid('dt_konsumen', ['id_konsumen'=>$idkonsumen]);
                        if($kons->num_rows() == 1){
                            $nama_konsumen = $kons->row("nama_konsumen");
                            $nohp_konsumen = $kons->row("no_hp");
                            $almt_konsumen = $kons->row("alamat");
                        } else {
                            $nama_konsumen = 'Undefined';
                            $nohp_konsumen = 'Undefined';
                            $almt_konsumen = 'Undefined';
                        }
                    ?>
                    <form method="post" action="<?=base_url('nota/prosessimpan');?>" enctype="multipart/form-data">
                    <input type="hidden" name="nosj" value="<?=$dt_sj['no_sj'];?>">
					<div class="card-box mb-30">
						<div class="pd-20">
                            <table>
                                <tr>
                                    <td style="width:25px;">1.</td>
                                    <td style="width:200px;">Nomor Surat Jalan</td>
                                    <td>:&nbsp;</td>
                                    <th><?=$dt_sj['no_sj'];?></th>
                                </tr>
                                <tr>
                                    <td>2.</td>
                                    <td style="width:200px;">Nama Konsumen</td>
                                    <td>:&nbsp;</td>
                                    <th><?=$nama_konsumen;?></th>
                                </tr>
                                <tr>
                                    <td>3.</td>
                                    <td style="width:200px;">Alamat Konsumen</td>
                                    <td>:&nbsp;</td>
                                    <th><?=$nohp_konsumen;?></th>
                                </tr>
                                <tr>
                                    <td>4.</td>
                                    <td style="width:200px;">Telpon Konsumen</td>
                                    <td>:&nbsp;</td>
                                    <th><?=$almt_konsumen;?></th>
                                </tr>
                                <tr>
                                    <td>5.</td>
                                    <td style="width:200px;">Tanggal Pengiriman</td>
                                    <td>:&nbsp;</td>
                                    <th><?php $ex=explode('-', $dt_sj['tgl_kirim']); echo $ex[2]." ".$bln[$ex[1]]." ".$ex[0]; ?></th>
                                </tr>
                                <!-- <tr>
                                    <td>6.</td>
                                    <td style="width:200px;">Tanggal Nota</td>
                                    <td>:&nbsp;</td>
                                    <th>
                                        <input type="date" class="form-control">
                                    </th>
                                </tr> -->
                            </table>
                            <p>&nbsp;</p>
							<table class="table stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort">No PKG</th>
										<th>Konstruksi</th>
										<th>Jumlah Roll</th>
										<th>Total Panjang</th>
										<th>Harga Satuan</th>
                                        <th>Harga Total</th>
									</tr>
								</thead>
								<tbody>
									<?php
                                        $nosj = $dt_sj['no_sj'];
                                        $pkg = $this->data_model->get_byid('new_tb_packinglist', ['no_sj'=>$nosj]);
                                        foreach($pkg->result() as $noid => $pk):
                                    ?>
                                    <tr>
                                        <td>
                                            <?=$pk->kd;?>
                                            <input type="hidden" name="kdpkg[]" value="<?=$pk->kd;?>">
                                        </td>
                                        <td>
                                            <?=$pk->kode_konstruksi;?>
                                            <input type="hidden" name="konstruksi[]" value="<?=$pk->kode_konstruksi;?>">
                                        </td>
                                        <td>
                                            <?=$pk->jumlah_roll;?>
                                            <input type="hidden" name="jmlroll[]" value="<?=$pk->jumlah_roll;?>">
                                        </td>
                                        <td>
                                            <?php
                                                if(fmod($pk->ttl_panjang, 1) !== 0.00){
                                                    $ttl = number_format($pk->ttl_panjang,2,',','.');
                                                } else {
                                                    $ttl = number_format($pk->ttl_panjang,0,',','.');
                                                }
                                                echo $ttl; 
                                            ?>
                                            <input type="hidden" name="ttlpanjang[]" id="pjg-<?=$noid;?>" value="<?=$pk->ttl_panjang;?>">
                                        </td>
                                        <td>
                                            <input type="text" name="hargasatuan[]" class="form-control formattedNumberField" id="harga-<?=$noid;?>" placeholder="Masukan harga satuan">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="total-<?=$noid;?>" readonly>
                                            <input type="hidden" id="total2-<?=$noid;?>" name="totalharga[]">
                                        </td>
                                    </tr>
                                    <?php
                                        endforeach;
                                    ?>
								</tbody>
							</table>
                            <button type="submit" class="btn btn-primary"><i class="icon-copy fa fa-save" aria-hidden="true"></i>&nbsp;&nbsp;Simpan Nota</button>
						</div>
					</div>
                    </form>
					<!-- Simple Datatable End -->
					
        </div>
    </div>
</div>

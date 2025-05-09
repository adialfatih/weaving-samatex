<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ];
$nosj = $nota['no_sj'];
$er = explode('-',$nota['tgl_nota']);
$tglNota = $er[2]." ".$bln[$er[1]]." ".$er[0];
$idcus = $this->data_model->get_byid('surat_jalan',['no_sj'=>$nosj])->row("id_customer");
$cus = $this->data_model->get_byid('dt_konsumen', ['id_konsumen'=>$idcus])->row_array();
$nama_konsumen = $cus['nama_konsumen'];
$nohp_konsumen = $cus['no_hp'];
$almt_konsumen = $cus['alamat'];
?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Kartu Piutang Konsumen</h4>
                                    <small>Menampilkan kartu piutang konsumen atas nama <strong><?=$nama_konsumen;?></strong> nomor nota <strong><?=$nota['id_nota'];?></strong></small>
								</div>
							</div>
							
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
                            <table class="table">
                                <tr>
                                    <td style="width:200px;">Nama Konsumen</td>
                                    <th><?=$nama_konsumen;?></th>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <th><?=$almt_konsumen;?></th>
                                </tr>
                            </table>
							<table class="table table-bordered stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort" style="text-align:center;">Tanggal</th>
										<th style="text-align:center;">Keterangan</th>
										<th style="text-align:center;">Nomor Bukti</th>
										<th style="text-align:center;">Debet</th>
										<th style="text-align:center;">Kredit</th>
                                        <th style="text-align:center;">Saldo</th>
									</tr>
								</thead>
								<tbody>
                                    <tr>
                                        <td><?=$tglNota;?></td>
                                        <td>Pembelian</td>
                                        <td>Invoice No. <?=$nota['id_nota'];?></td>
                                        <td><?="Rp. ".number_format($nota['total_harga'],0,',','.');?></td>
                                        <td></td>
                                        <td><?="Rp. ".number_format($nota['total_harga'],0,',','.');?></td>
                                    </tr>
                                    <?php
                                    $saldo = $nota['total_harga'];
                                    $pemb = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$nota['id_nota']]);
                                    foreach($pemb->result() as $pb):
                                    ?>
                                    <tr>
                                        <td>
                                            <?php $po = explode('-', $pb->tgl_pemb); echo $po[2]." ".$bln[$po[1]]." ".$po[0]; ?> 
                                        </td>
                                        <td>Pembayaran</td>
                                        <td><?=$pb->nomor_bukti;?></td>
                                        <td></td>
                                        <td><?="Rp. ".number_format($pb->nominal_pemb,0,',','.');?></td>
                                        <td>
                                            <?php
                                                $min_saldo = $saldo - $pb->nominal_pemb;
                                                echo "Rp. ".number_format($min_saldo,0,',','.');
                                            ?>
                                        </td>
                                    </tr>
                                    <?php $saldo = $min_saldo;
                                    endforeach;
                                    ?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- Simple Datatable End -->
                    
					
        </div>
    </div>
</div>
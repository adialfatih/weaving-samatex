<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; 
$idcus = $kons['id_konsumen'];
?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Kartu Piutang Konsumen</h4>
                                    <small>Menampilkan kartu piutang konsumen atas nama <strong><?=$kons['nama_konsumen'];?></strong></small>
								</div>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a
										class="btn btn-primary dropdown-toggle"
										href="#"
										role="button"
										data-toggle="dropdown"
									>
										Export & Download
									</a>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item" href="<?=base_url('exportexcel/piutang/'.$idcus);?>">Export Excel</a>
										<a class="dropdown-item" href="#">Export PDF</a>
									</div>
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
                                    <th><?=$kons['nama_konsumen'];?></th>
                                </tr>
                                <tr>
                                    <td>No HP</td>
                                    <th><?=$kons['no_hp'];?></th>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <th><?=$kons['alamat'];?></th>
                                </tr>
                            </table>
							<table class="table table-bordered stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort" style="text-align:center;">Tanggal</th>
										<!-- <th style="text-align:center;">Keterangan</th> -->
										<th style="text-align:center;">No Faktur</th>
										<!-- <th style="text-align:center;">Nomor Bukti</th> -->
										<th style="text-align:center;">Jenis Barang</th>
										<th style="text-align:center;">Panjang</th>
										<th style="text-align:center;">Harga</th>
										<th style="text-align:center;">Total Harga</th>
										<th style="text-align:center;">Bayar</th>
                                        <th style="text-align:center;">Saldo</th>
									</tr>
								</thead>
								<tbody>
                                    <?php
                                    $tglar = array();
                                    $jenis = array();
                                    $arid = array();
                                    $surjal = array();
                                    $konstruksi = array();
                                    $panjang = array();
                                    $harga = array();
                                    $harga_total = array();
                                    $idcus = $kons['id_konsumen'];
                                    $query = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.konstruksi, a_nota.total_panjang, a_nota.harga_satuan, a_nota.total_harga, a_nota.tgl_nota, surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj=surat_jalan.no_sj AND surat_jalan.id_customer='$idcus'");
                                    
                                    foreach($query->result() as $val):
                                        $tglar[]= $val->tgl_nota;
                                        $jenis[]='Pembelian';
                                        $arid[]=$val->id_nota;
                                        $surjal[]=$val->no_sj;
                                        $konstruksi[] = $val->konstruksi;
                                        $panjang[] = $val->total_panjang;
                                        $harga[] = $val->harga_satuan;
                                        $harga_total[] = $val->total_harga;
                                        $idnota = $val->id_nota;
                                        $cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota]);
                                        foreach($cekbyr->result() as $val2):
                                            $tglar[]= $val2->tgl_pemb;
                                            $jenis[]='Pembayaran';
                                            $arid[]=$val2->id_pemb;
                                            $surjal[]="null";
                                            $konstruksi[] = "null";
                                            $panjang[] = "null";
                                            $harga[] = "null";
                                            $harga_total[] = "null";
                                        endforeach;
                                    endforeach;
                                    if($idcus==100){
                                        $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'KM%'");
                                        foreach ($allIdKm->result() as $key => $value) {
                                            $newid = $value->id_konsumen;
                                            $query = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.konstruksi, a_nota.total_panjang, a_nota.harga_satuan, a_nota.total_harga, a_nota.tgl_nota, surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj=surat_jalan.no_sj AND surat_jalan.id_customer='$newid'");
                                    
                                            foreach($query->result() as $val):
                                                $tglar[]= $val->tgl_nota;
                                                $jenis[]='Pembelian';
                                                $arid[]=$val->id_nota;
                                                $surjal[]=$val->no_sj;
                                                $konstruksi[] = $val->konstruksi;
                                                $panjang[] = $val->total_panjang;
                                                $harga[] = $val->harga_satuan;
                                                $harga_total[] = $val->total_harga;
                                                $idnota = $val->id_nota;
                                                $cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota]);
                                                foreach($cekbyr->result() as $val2):
                                                    $tglar[]= $val2->tgl_pemb;
                                                    $jenis[]='Pembayaran';
                                                    $arid[]=$val2->id_pemb;
                                                    $surjal[]="null";
                                                    $konstruksi[] = "null";
                                                    $panjang[] = "null";
                                                    $harga[] = "null";
                                                    $harga_total[] = "null";
                                                endforeach;
                                            endforeach;
                                        }
                                    } //if KM ended
                                    if($idcus==29){
                                        $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'PS%'");
                                        foreach ($allIdKm->result() as $key => $value) {
                                            $newid = $value->id_konsumen;
                                            $query = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.konstruksi, a_nota.total_panjang, a_nota.harga_satuan, a_nota.total_harga, a_nota.tgl_nota, surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj=surat_jalan.no_sj AND surat_jalan.id_customer='$newid'");
                                    
                                            foreach($query->result() as $val):
                                                $tglar[]= $val->tgl_nota;
                                                $jenis[]='Pembelian';
                                                $arid[]=$val->id_nota;
                                                $surjal[]=$val->no_sj;
                                                $konstruksi[] = $val->konstruksi;
                                                $panjang[] = $val->total_panjang;
                                                $harga[] = $val->harga_satuan;
                                                $harga_total[] = $val->total_harga;
                                                $idnota = $val->id_nota;
                                                $cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota]);
                                                foreach($cekbyr->result() as $val2):
                                                    $tglar[]= $val2->tgl_pemb;
                                                    $jenis[]='Pembayaran';
                                                    $arid[]=$val2->id_pemb;
                                                    $surjal[]="null";
                                                    $konstruksi[] = "null";
                                                    $panjang[] = "null";
                                                    $harga[] = "null";
                                                    $harga_total[] = "null";
                                                endforeach;
                                            endforeach;
                                        }
                                    } //if ps ended
                                    if($idcus==101){
                                        $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'PB%'");
                                        foreach ($allIdKm->result() as $key => $value) {
                                            $newid = $value->id_konsumen;
                                            $query = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.konstruksi, a_nota.total_panjang, a_nota.harga_satuan, a_nota.total_harga, a_nota.tgl_nota, surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj=surat_jalan.no_sj AND surat_jalan.id_customer='$newid'");
                                    
                                            foreach($query->result() as $val):
                                                $tglar[]= $val->tgl_nota;
                                                $jenis[]='Pembelian';
                                                $arid[]=$val->id_nota;
                                                $surjal[]=$val->no_sj;
                                                $konstruksi[] = $val->konstruksi;
                                                $panjang[] = $val->total_panjang;
                                                $harga[] = $val->harga_satuan;
                                                $harga_total[] = $val->total_harga;
                                                $idnota = $val->id_nota;
                                                $cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota]);
                                                foreach($cekbyr->result() as $val2):
                                                    $tglar[]= $val2->tgl_pemb;
                                                    $jenis[]='Pembayaran';
                                                    $arid[]=$val2->id_pemb;
                                                    $surjal[]="null";
                                                    $konstruksi[] = "null";
                                                    $panjang[] = "null";
                                                    $harga[] = "null";
                                                    $harga_total[] = "null";
                                                endforeach;
                                            endforeach;
                                        }
                                    } //if pB ended
                                    asort($tglar);
                                    $sum_panjang = 0;
                                    $sum_harga =0;
                                    $sum_bayar = 0;
                                    $saldo=0;
                                    foreach($tglar as $index => $value){
                                        if($jenis[$index]=="Pembelian"){
                                            $dtqr = $this->data_model->get_byid('a_nota', ['id_nota'=>$arid[$index]]);
                                            $dtqr2 = $this->db->query("SELECT * FROM a_nota WHERE id_nota='$arid[$index]' AND no_sj NOT LIKE '%SLD%'");
                                            $nobukti = "<a href='".base_url('nota/piutang/'.sha1($arid[$index]))."' style='color:#135bd6;text-decoration:none;'>Invoice No.".$dtqr->row("id_nota")."</a>";
                                              if(fmod($dtqr->row("total_harga"), 1) !== 0.00){
                                                $debet = "Rp. ".number_format($dtqr->row("total_harga"),2,',','.');
                                              } else {
                                                $debet = "Rp. ".number_format($dtqr->row("total_harga"),0,',','.');
                                              }
                                            $sum_harga = $sum_harga + $dtqr2->row("total_harga");
                                            $kredit = "";
                                            $saldo = $saldo + $dtqr->row("total_harga");
                                            $jns = "Penjualan";
                                        } else {
                                            $dtqr = $this->data_model->get_byid('a_nota_bayar', ['id_pemb'=>$arid[$index]]);
                                            $nobukti = $dtqr->row("nomor_bukti");
                                            $debet = "";
                                              if(fmod($dtqr->row("nominal_pemb"), 1) !== 0.00){
                                                $kredit = "Rp. ".number_format($dtqr->row("nominal_pemb"),2,',','.');
                                              } else {
                                                $kredit = "Rp. ".number_format($dtqr->row("nominal_pemb"),0,',','.');
                                              }
                                            $sum_bayar = $sum_bayar + $dtqr->row("nominal_pemb");
                                            $saldo = $saldo - $dtqr->row("nominal_pemb");
                                            $jns = "<a href='".base_url('nota/piutang/'.sha1($dtqr->row('id_nota')))."' style='color:#135bd6;text-decoration:none;'>Pembayaran Invoice No.".$dtqr->row("id_nota")."</a>";
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            <?php $ek=explode('-',$value); echo $ek[2]." ".$bln[$ek[1]]." ".$ek[0]; ?>
                                        </td>
                                        <!-- <td><=$jns;?></td> -->
                                        <td>
                                        <?php
                                            $rtx = explode('/', $surjal[$index]);
                                            $idcusbysj = $this->data_model->get_byid('surat_jalan',['no_sj'=>$surjal[$index]])->row("id_customer");
                                            $nm_cus = $this->data_model->get_byid('dt_konsumen',['id_konsumen'=>$idcusbysj])->row("nama_konsumen");
                                            if($rtx[0]=="SLD"){
                                                echo "<strong>Saldo Awal</strong>";
                                            } elseif($rtx[0]=="null") {
                                                echo "<font style='color:red;'>$nobukti</font>";
                                            } else {
                                                echo "<a href='#' data-toggle='tooltip' data-placement='top' title='$nm_cus'>J".$rtx[3]."".$rtx[0]."</a>";
                                            }
                                        ?>
                                        </td>
                                        <!-- <td><=$nobukti;?></td> -->
                                        <td><?=$konstruksi[$index]=="null" ? '':$konstruksi[$index];?></td>
                                        <td>
                                        <?php if($panjang[$index]=="null" || $panjang[$index]==0){} else {
                                            if(fmod($panjang[$index], 1) !== 0.00){
                                                echo "".number_format($panjang[$index],2,',','.');
                                            } else {
                                                echo "".number_format($panjang[$index],0,',','.');
                                            }
                                        }
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                            $sum_panjang = $sum_panjang + $panjang[$index];
                                            if($harga[$index]=="null" || $harga[$index]==0){} else {
                                            if(fmod($harga[$index], 1) !== 0.00){
                                                echo "Rp. ".number_format($harga[$index],2,',','.');
                                            } else {
                                                echo "Rp. ".number_format($harga[$index],0,',','.');
                                            }
                                        }
                                        ?>
                                        </td>
                                        <td>
                                            <?php if($rtx[0]=="SLD"){ echo ""; } else { echo $debet; } ?>
                                        </td>
                                        <td><?=$kredit;?></td>
                                        <td>
                                        <?php
                                            if(fmod($saldo, 1) !== 0.00){
                                                echo "Rp. ".number_format($saldo,2,',','.');
                                            } else {
                                                echo "Rp. ".number_format($saldo,0,',','.');
                                            }
                                        
                                        ?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                    <tr>
                                        <td colspan="3"><strong>TOTAL</strong></td>
                                        <td><?php
                                          if(fmod($sum_panjang, 1) !== 0.00){
                                            echo "".number_format($sum_panjang,2,',','.');
                                          } else {
                                            echo "".number_format($sum_panjang,0,',','.');
                                          } ?>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            <?php
                                              if(fmod($sum_harga, 1) !== 0.00){
                                                echo  "Rp. ".number_format($sum_harga,2,',','.');
                                              } else {
                                                echo "Rp. ".number_format($sum_harga,0,',','.');
                                              }
                                              ?>
                                        </td>
                                        <td>
                                            <?php
                                            if(fmod($sum_bayar, 1) !== 0.00){
                                                echo "Rp. ".number_format($sum_bayar,2,',','.');
                                              } else {
                                                echo "Rp. ".number_format($sum_bayar,0,',','.');
                                              }
                                              ?>
                                        </td>
                                        <td><?php
                                            if(fmod($saldo, 1) !== 0.00){
                                                echo "Rp. ".number_format($saldo,2,',','.');
                                            } else {
                                                echo "Rp. ".number_format($saldo,0,',','.');
                                            }
                                        
                                        ?></td>
                                    </tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- Simple Datatable End -->
                    
					
        </div>
    </div>
</div>
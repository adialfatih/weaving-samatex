<?php 
$blns = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des' ];
$bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember' ];
$today = date('D M y, H:i');
$today_date = date('Y-m-d');
$before = date('Y-m-d',strtotime($today_date . "-1 days"));
$kemarin = date('D', strtotime($before));
//echo $today."<br>".$today_date;
$ex = explode(' ',$today);
$day_name = $ex[0];
//echo "<br>".$day_name;
$day_ar = [ 'Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa', 'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu' ];
$print_hari = $day_ar[$day_name];
//echo "<br>".$print_hari;
$exdate = explode('-',$today_date);
$exdate2 = explode('-',$before);
$print_tanggal = $exdate[2]." ".$bln[$exdate[1]]." ".$exdate[0];
$print_tanggal_kemarin = $exdate2[2]." ".$bln[$exdate2[1]]." ".$exdate2[0];
//echo "<br>".$print_tanggal;
?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					
					<!-- Simple Datatable start -->
                    <div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								<strong>Saldo Piutang</strong><br>
                                <small><?="".$print_hari.", ".$print_tanggal;?></small>
							</p>
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                               <div class="table-responsive" style="font-size:14px;">
                               <table class="data-table table stripe hover nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Customer</th>
                                            <th>Nota Tagihan</th>
                                            <th>Saldo Awal</th>
                                            <th>Outstanding</th>
                                            <th class="datatable-nosort">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $cust = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen NOT LIKE 'KM%' AND nama_konsumen NOT LIKE 'PS%'");
                                            $no=1;
                                            foreach ($cust->result() as $key => $value) {
                                            $idcus = $value->id_konsumen;
                                            $no_sj = "SLD/AWL/".$idcus."";
                                            $total_piutang = $this->db->query("SELECT SUM(total_harga) AS ukr FROM view_nota WHERE id_customer='$idcus' AND no_sj NOT LIKE '%SLD%'")->row("ukr");
                                            $total_bayar = $this->db->query("SELECT SUM(nominal_pemb) AS ukr FROM a_nota_bayar WHERE id_cus='$idcus'")->row("ukr");
                                            if($idcus==100){
                                                $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'KM%'");
                                                $all_total_piutang = 0; $all_total_bayar = 0;
                                                foreach($allIdKm->result() as $bal){
                                                    $idcusfor = $bal->id_konsumen;
                                                    $total_piutang2 = $this->db->query("SELECT SUM(total_harga) AS ukr FROM view_nota WHERE id_customer='$idcusfor' AND no_sj NOT LIKE '%SLD%'")->row("ukr");
                                                    $all_total_piutang+=floatval($total_piutang2);

                                                    $total_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) AS ukr FROM a_nota_bayar WHERE id_cus='$idcusfor'")->row("ukr");
                                                    $all_total_bayar+=floatval($total_bayar2);
                                                }
                                                $total_bayar = $total_bayar + $all_total_bayar;
                                                $total_piutang = $total_piutang + $all_total_piutang;
                                            } //km%
                                            if($idcus==29){
                                                $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'PS%'");
                                                $all_total_piutang = 0;
                                                foreach($allIdKm->result() as $bal){
                                                    $idcusfor = $bal->id_konsumen;
                                                    $total_piutang2 = $this->db->query("SELECT SUM(total_harga) AS ukr FROM view_nota WHERE id_customer='$idcusfor' AND no_sj NOT LIKE '%SLD%'")->row("ukr");
                                                    $all_total_piutang+=floatval($total_piutang2);

                                                    $total_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) AS ukr FROM a_nota_bayar WHERE id_cus='$idcusfor'")->row("ukr");
                                                    $all_total_bayar+=floatval($total_bayar2);
                                                }
                                                $total_bayar = $total_bayar + $all_total_bayar;
                                                $total_piutang = $total_piutang + $all_total_piutang;
                                            } //ps%
                                            
                                            $saldoAwal = $this->db->query("SELECT * FROM a_nota WHERE no_sj='$no_sj'")->row("total_harga");
                                            $outstanding = floatval($saldoAwal) + floatval($total_piutang);
                                            if($total_piutang <= 0){
                                                if($saldoAwal <= 0){

                                                } else {
                                                    $saldoAwal = $saldoAwal - $total_bayar;
                                                    $outstanding = floatval($saldoAwal) + floatval($total_piutang);
                                                }
                                            } else {
                                                $total_piutang = $total_piutang - $total_bayar;
                                                $outstanding = floatval($saldoAwal) + floatval($total_piutang);
                                            }
                                            // tidak di tampilkan jika outstanding 
                                            if($outstanding<1){} else {
                                        ?>
                                        <tr>
                                            <td class="table-plus"><?=$no;?></td>
                                            <td><?=$value->nama_konsumen;?></td>
                                            
                                            <td>
                                                Rp. 
                                                <?php
                                                  if(fmod($total_piutang, 1) !== 0.00){
                                                    echo number_format($total_piutang,2,',','.');
                                                  } else {
                                                    echo number_format($total_piutang,0,',','.');
                                                  }
                                                ?>
                                            </td>
                                            <td>Rp. 
                                                <?php
                                                  if(fmod($saldoAwal, 1) !== 0.00){
                                                    echo number_format($saldoAwal,2,',','.');
                                                  } else {
                                                    echo number_format($saldoAwal,0,',','.');
                                                  }
                                                ?>
                                            </td>
                                            <td>Rp. 
                                                <?php
                                                  if(fmod($outstanding, 1) !== 0.00){
                                                    echo number_format($outstanding,2,',','.');
                                                  } else {
                                                    echo number_format($outstanding,0,',','.');
                                                  }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown" >
                                                        <i class="dw dw-more"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                        <a class="dropdown-item" href="<?=base_url('nota/piutangshowall/'.sha1($idcus));?>"><i class="dw dw-eye"></i> View</a>
                                                        <a class="dropdown-item" href="#" onclick="ceksaldoawal('<?=$idcus;?>','<?=$value->nama_konsumen;?>')" data-toggle="modal" data-target="#Medium-modal"><i class="dw dw-edit2"></i> Edit Saldo Awal</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php $no++; } } ?>
                                    </tbody>
                                </table>
                               </div>
                            </div>
						</div>
					</div>
					<!-- Simple Datatable start -->
                                <div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
                                            <form action="<?=base_url('prosesajax/inputSaldoAwal');?>" method="post">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Saldo Awal
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<div class="modal-body">
												<div class="table-responsive">
                                                    <table class="table">
                                                        <tr>
                                                            <td>Nama Customer<input type="hidden" id="ididcus" name="ididcus" value="0"></td>
                                                            <td id="idnmcus"><strong>Mutiara</strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Saldo Awal</td>
                                                            <td><input type="text" class="form-control" id="idsaldoawal" name="saldoawal"></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                Masukan nominal saldo awal tanpa koma. Jika nilai decimal gunakan titik.
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
											</div>
											<div class="modal-footer">
                                                <input type="hidden" id="idnota" name="idnota" value="0">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">
													Close
												</button>
												<button type="submit" class="btn btn-primary">
													Save changes
												</button>
											</div>
                                            </form>
										</div>
                                    </div>
                                </div>
					<!-- Simple Datatable End -->
        </div>
    </div>
</div>

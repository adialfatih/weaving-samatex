<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; 
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
$print_tanggal = $exdate[2]." ".$bln[$exdate[1]]." ".$exdate[0];
//echo "<br>".$print_tanggal;
$testTgl = '2023-06-10';
$exdate2 = explode('-',$testTgl);
$print_tanggal_kemarin = $exdate2[2]." ".$bln[$exdate2[1]]." ".$exdate2[0];
$query = $this->data_model->get_byid('dt_produksi_mesin', ['tanggal_produksi'=> $testTgl]);
?>

				<div class="min-height-200px">
					
					<!-- Simple Datatable start -->
                    <div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0" style="text-align:center; font-size:2em;">
								<strong>Laporan Harian Produksi dan Penjualan Tanggal <?=$print_tanggal_kemarin;?></strong>
							</p>
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                                <?php
                                    $ar_kons = array();
                                    foreach($query->result() as $val):
                                        $kons = $val->kode_konstruksi;
                                        if(in_array($kons, $ar_kons)) { } else {
                                            $ar_kons[] = $kons;
                                        }
                                    endforeach;
                                ?>
                                <?="<strong>".$day_ar[$kemarin].", ".$print_tanggal_kemarin."</br>";?>
                               <div class="table-responsive" style="font-size:14px;">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr class="table-secondary">
                                            <th rowspan="2">No.</th>
                                            <th rowspan="2">Konstruksi</th>
                                            <th colspan="2" style="text-align:center;">Jumlah Mesin</th>
                                            <th colspan="2" style="text-align:center;">Jumlah Produksi</th>
                                            <th rowspan="2">Penjualan</th>
                                            <th colspan="2" style="text-align:center;">Stok Akhir</th>
                                        </tr>
                                        <tr class="table-secondary">
                                            <th style="text-align:center;">Samatex</th>
                                            <th style="text-align:center;">Rindang</th>
                                            <th style="text-align:center;">Samatex</th>
                                            <th style="text-align:center;">Rindang</th>
                                            <th style="text-align:center;">Grey</th>
                                            <th style="text-align:center;">Finish</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $total1=0;$total2=0;$total3=0;
                                            $total4=0;$total5=0;$total6=0;
                                            $total7=0;$total7=0;
                                            foreach($ar_kons as $n => $ar): ?>
                                            <tr>
                                                <td><?=$n+1;?>.</td>
                                                <td><?=$ar;?></td>
                                                <td>
                                                    <?php
                                                        $qry1 = $this->db->query("SELECT * FROM dt_produksi_mesin WHERE kode_konstruksi='$ar' AND tanggal_produksi='$testTgl' AND lokasi='Samatex'")->row("jumlah_mesin");
                                                        if($qry1==0){echo "-";} else { echo $qry1; $total1+=$qry1; }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        $qry2 = $this->db->query("SELECT * FROM dt_produksi_mesin WHERE kode_konstruksi='$ar' AND tanggal_produksi='$testTgl' AND lokasi='RJS'")->row("jumlah_mesin");
                                                        if($qry2==0){echo "-";} else { echo $qry2; $total2+=$qry2; }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        $qry3 = $this->db->query("SELECT * FROM dt_produksi_mesin WHERE kode_konstruksi='$ar' AND tanggal_produksi='$testTgl' AND lokasi='Samatex'")->row("hasil");
                                                        if($qry3==0){echo "-";} else { echo $qry3; $total3+=$qry3; }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        $qry4 = $this->db->query("SELECT * FROM dt_produksi_mesin WHERE kode_konstruksi='$ar' AND tanggal_produksi='$testTgl' AND lokasi='RJS'")->row("hasil");
                                                        if($qry4==0){echo "-";} else { echo $qry4; $total4+=$qry4; }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        //$qry5 = $this->db->query("SELECT * FROM data_produksi WHERE kode_konstruksi='$ar' AND tgl='$testTgl' AND dep='Samatex'")->row("prod_fg");
                                                        $qry5=0;
                                                        $total5+=$qry5;
                                                        if($qry5==0){echo"-";} else {
                                                        if(fmod($qry5, 1) !== 0.00){
                                                            echo number_format($qry5,2,',','.');
                                                        } else {
                                                            echo number_format($qry5,0,',','.');
                                                        } }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        $folg1 = $this->db->query("SELECT id_fol,konstruksi,ukuran,jns_fold,loc,posisi,SUM(ukuran) as total FROM data_fol WHERE konstruksi='$ar' AND jns_fold='Grey' AND loc='Samatex' AND posisi='Samatex'")->row("total");
                                                        $folg2 = $this->db->query("SELECT ukuran_now,folding,lokasi,konstruksi,SUM(ukuran_now) as total FROM data_fol_lama WHERE folding='Grey' AND konstruksi='$ar' AND lokasi='Samatex'")->row("total");
                                                        $qry6= $folg1 + $folg2;
                                                        $total6+=$qry6;
                                                        if($qry6==0){echo"-";} else {
                                                        if(fmod($qry6, 1) !== 0.00){
                                                            echo number_format($qry6,2,',','.');
                                                        } else {
                                                            echo number_format($qry6,0,',','.');
                                                        } }
                                                        $idstok = $this->data_model->get_byid('data_stok', ['dep'=>'Samatex','kode_konstruksi'=>$ar])->row("idstok");
                                                        $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_fg'=>round($folg1,2)]);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        $folf1 = $this->db->query("SELECT id_fol,konstruksi,ukuran,jns_fold,loc,posisi,SUM(ukuran) as total FROM data_fol WHERE konstruksi='$ar' AND jns_fold='Finish' AND loc='Samatex' AND posisi='Samatex'")->row("total");
                                                        $folf2 = $this->db->query("SELECT ukuran_now,folding,lokasi,konstruksi,SUM(ukuran_now) as total FROM data_fol_lama WHERE folding='Finish' AND konstruksi='$ar' AND lokasi='Samatex'")->row("total");
                                                        $qry7= $folf1 + $folf2;
                                                        $total7+=$qry7;
                                                        if($qry7==0){echo"-";} else {
                                                        if(fmod($qry7, 1) !== 0.00){
                                                            echo number_format($qry7,2,',','.');
                                                        } else {
                                                            echo number_format($qry7,0,',','.');
                                                        } }
                                                        $idstok = $this->data_model->get_byid('data_stok', ['dep'=>'Samatex','kode_konstruksi'=>$ar])->row("idstok");
                                                        $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ff'=>round($folf1,2)]);
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-primary">
                                                <th>Total</th>
                                                <th></th>
                                                <th><?=$total1;?></th>
                                                <th><?=$total2;?></th>
                                                <th><?php
                                                    if(fmod($total3, 1) !== 0.00){
                                                        echo number_format($total3,2,',','.');
                                                    } else {
                                                        echo number_format($total3,0,',','.');
                                                    } ?>
                                                </th>
                                                <th><?php
                                                    if(fmod($total4, 1) !== 0.00){
                                                        echo number_format($total4,2,',','.');
                                                    } else {
                                                        echo number_format($total4,0,',','.');
                                                    } ?>
                                                </th>
                                                <th><?php
                                                    if(fmod($total5, 1) !== 0.00){
                                                        echo number_format($total5,2,',','.');
                                                    } else {
                                                        echo number_format($total5,0,',','.');
                                                    } ?>
                                                </th>
                                                <th><?php
                                                    if(fmod($total6, 1) !== 0.00){
                                                        echo number_format($total6,2,',','.');
                                                    } else {
                                                        echo number_format($total6,0,',','.');
                                                    } ?>
                                                </th>
                                                <th><?php
                                                    if(fmod($total7, 1) !== 0.00){
                                                        echo number_format($total7,2,',','.');
                                                    } else {
                                                        echo number_format($total7,0,',','.');
                                                    } ?>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                               </div>
                            </div>
						</div>
					</div>
					<!-- Simple Datatable start -->
                    <!-- Simple Datatable start -->
                    
        </div>
<?php
$newqr = $this->db->query("SELECT * FROM tb_konstruksi");
foreach($newqr->result() as $val):
    $kd = $val->kode_konstruksi;
    $dtig = $this->db->query("SELECT konstruksi,ukuran_ori,dep,loc_now,SUM(ukuran_ori) as total FROM data_ig WHERE konstruksi='$kd' AND dep='Samatex' AND loc_now='Samatex'")->row("total");

    $dtif1 = $this->db->query("SELECT ukuran_ori,konstruksi,SUM(ukuran_ori) as total FROM data_if WHERE konstruksi='$kd'")->row("total");
    $dtif2 = $this->db->query("SELECT panjang,kodekons,SUM(panjang) as total FROM data_if_lama WHERE kodekons='$kd'")->row("total");
    $totalif = $dtif1 + $dtif2;
    //echo $kd."-".$dtig."<br>";
    $idstok = $this->data_model->get_byid('data_stok', ['dep'=>'Samatex','kode_konstruksi'=>$kd])->row("idstok");
    $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>$dtig, 'prod_if'=>round($dtif1,2)]);
endforeach;
?>
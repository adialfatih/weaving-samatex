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
$query = $this->data_model->get_byid('data_produksi', ['tgl'=> $testTgl]);
?>

				<div class="min-height-200px">
					
					<!-- Simple Datatable start -->
                    <div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0" style="text-align:center; font-size:2em;">
								<strong>Laporan Harian Tanggal <?=$print_tanggal_kemarin;?></strong>
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
                                            
                                            <th colspan="2" style="text-align:center;">Inspect Grey</th>
                                            <th rowspan="2">Folding Grey</th>
                                            <th rowspan="2">Inspect Finish</th>
                                            <th rowspan="2">Folding Finish</th>
                                            
                                        </tr>
                                        <tr class="table-secondary">
                                            <th style="text-align:center;">Samatex</th>
                                            <th style="text-align:center;">Rindang</th>
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
                                                        $qry3 = $this->db->query("SELECT * FROM data_produksi WHERE kode_konstruksi='$ar' AND tgl='$testTgl' AND dep='Samatex'")->row("prod_ig");
                                                        $total3+=$qry3;
                                                        if($qry3==0){echo"-";} else { 
                                                        if(fmod($qry3, 1) !== 0.00){
                                                           echo number_format($qry3,2,',','.');
                                                        } else {
                                                           echo number_format($qry3,0,',','.');
                                                        } }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        $qry4 = $this->db->query("SELECT * FROM data_produksi WHERE kode_konstruksi='$ar' AND tgl='$testTgl' AND dep='RJS'")->row("prod_ig");
                                                        $total4+=$qry4;
                                                        if($qry4==0){echo"-";} else {
                                                        if(fmod($qry4, 1) !== 0.00){
                                                            echo number_format($qry4,2,',','.');
                                                        } else {
                                                            echo number_format($qry4,0,',','.');
                                                        } }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        $qry5 = $this->db->query("SELECT * FROM data_produksi WHERE kode_konstruksi='$ar' AND tgl='$testTgl' AND dep='Samatex'")->row("prod_fg");
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
                                                        $qry6 = $this->db->query("SELECT * FROM data_produksi WHERE kode_konstruksi='$ar' AND tgl='$testTgl' AND dep='Samatex'")->row("prod_if");
                                                        $total6+=$qry6;
                                                        if($qry6==0){echo"-";} else {
                                                        if(fmod($qry6, 1) !== 0.00){
                                                            echo number_format($qry6,2,',','.');
                                                        } else {
                                                            echo number_format($qry6,0,',','.');
                                                        } }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        $qry7 = $this->db->query("SELECT * FROM data_produksi WHERE kode_konstruksi='$ar' AND tgl='$testTgl' AND dep='Samatex'")->row("prod_ff");
                                                        $total7+=$qry7;
                                                        if($qry7==0){echo"-";} else {
                                                        if(fmod($qry7, 1) !== 0.00){
                                                            echo number_format($qry7,2,',','.');
                                                        } else {
                                                            echo number_format($qry7,0,',','.');
                                                        } }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-primary">
                                                <th>Total</th>
                                                <th></th>
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
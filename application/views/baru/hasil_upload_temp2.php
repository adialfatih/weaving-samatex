<?php $arb = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar','04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'];
?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">Menampilkan File Temporary Upload Folding</p>
						</div>
						<div class="pd-20">
                            <table border="1" style="border-collapse:collapse;">
                                <tr>
                                    <td style="padding:5px" style="padding:5px;">No</td>
                                    <td style="padding:5px">Kode Roll</td>
                                    <td style="padding:5px">Panjang</td>
                                    <td style="padding:5px">Folding</td>
                                    <td style="padding:5px">Tanggal</td>
                                    <td style="padding:5px">Operator</td>
                                    <td style="padding:5px">Konstruksi</td>
                                    <td style="padding:5px">Joins</td>
                                    <td style="padding:5px">Note</td>
                                    <td style="padding:5px">Total Ukuran</td>
                                    <td style="padding:5px">Konstruksi Sebelumnya</td>
                                    <td style="padding:5px">Nomo Mesin</td>
                                    <td style="padding:5px">Tanggal Tersimpan</td>
                                </tr>
                                <?php 
                                $dtroll = array(); 
                                foreach($queue->result() as $nn => $vall):
                                    $dtroll[] = $vall->kode_roll;
                                endforeach;
                                $total_roll=0; $total_panjang_all=0;
                                foreach($queue->result() as $n => $val):
                                    $count_values = array_count_values($dtroll);
                                    $occurrences = $count_values[$val->kode_roll];
                                    $ex = explode(',',$val->ukuran);
                                    $cek_kons_before = $this->data_model->get_byid('data_ig', ['kode_roll'=>$val->kode_roll]);
                                ?>
                                <tr>
                                    <td style="padding:5px"><?=$n+1;?></td>
                                    <td style="padding:5px"><?=$occurrences > 1 ? '<span style="color:red;">'.$val->kode_roll.'</span>':''.$val->kode_roll.'';?></td>
                                    <td style="padding:5px"><?=$val->ukuran;?></td>
                                    <td style="padding:5px"><?=$val->folding;?></td>
                                    <td style="padding:5px"><?=$val->tgl;?></td>
                                    <td style="padding:5px"><?=$val->operator;?></td>
                                    <td style="padding:5px"><?=$val->kons;?></td>
                                    <td style="padding:5px"><?=$val->joinss;?></td>
                                    <?php 
                                    if(count($ex) > 1){
                                        echo "<td style='padding:5px'>Jumlah Roll <span style='color:red;'>".count($ex)."</span></td>";
                                        $total_roll+=count($ex);
                                        $split_total = 0;
                                        for ($i=0; $i <count($ex) ; $i++) { 
                                            $split_total+=$ex[$i];
                                        }
                                        echo "<td style='padding:5px'>".$split_total."</td>";
                                        $total_panjang_all+=$split_total;
                                    } else {
                                        echo "<td style='padding:5px'>Jumlah Roll 1</td>";
                                        $total_roll+=1;
                                        echo "<td style='padding:5px'>".$val->ukuran."</td>";
                                        $total_panjang_all+=$val->ukuran;
                                    }
                                    if($cek_kons_before->num_rows()==1){
                                        if($cek_kons_before->row("konstruksi") == $val->kons){
                                            echo '<td style="padding:5px;">'.$cek_kons_before->row("konstruksi").'</td>';
                                            echo '<td style="padding:5px;">'.$cek_kons_before->row("no_mesin").'</td>';
                                        } else {
                                            echo '<td style="padding:5px; color:red;">'.$cek_kons_before->row("konstruksi").'</td>';
                                            echo '<td style="padding:5px; color:red;">'.$cek_kons_before->row("no_mesin").'</td>';
                                        }
                                    } else {
                                        echo '<td style="padding:5px;">Tidak terdeteksi</td>';
                                        echo '<td style="padding:5px;"></td>';
                                    }
                                    //cekkode-roll-on-folding
                                    $cekkronf = $this->data_model->get_byid('data_fol',['kode_roll'=>$val->kode_roll]);
                                    if($cekkronf->num_rows() == 1){
                                        $save_tgl = $cekkronf->row("tgl");
                                        if($val->tgl == $save_tgl){
                                            echo '<td style="padding:5px;">'.$save_tgl.'</td>';
                                        } else {
                                            echo '<td style="padding:5px;color:red;">'.$save_tgl.'</td>';
                                        }
                                    } else {
                                        $cekkronf2 = $this->data_model->get_byid('data_fol_lama',['kode_roll'=>$val->kode_roll]);
                                        if($cekkronf2->num_rows() == 1){
                                            $save_tgl = $cekkronf2->row("tanggal");
                                            if($val->tgl == $save_tgl){
                                                echo '<td style="padding:5px;">'.$save_tgl.'</td>';
                                            } else {
                                                echo '<td style="padding:5px;color:red;">'.$save_tgl.'</td>';
                                            }
                                        } else {
                                            echo '<td style="padding:5px;color:red;">Tidak tersimpan</td>';
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td style="padding:5px"></td>
                                    <td style="padding:5px"></td>
                                    <td style="padding:5px"></td>
                                    <td style="padding:5px"></td>
                                    <td style="padding:5px"></td>
                                    <td style="padding:5px"></td>
                                    <td style="padding:5px"></td>
                                    <td style="padding:5px"></td>
                                    <td style="padding:5px">Total Roll : <?=$total_roll;?></td>
                                    <td style="padding:5px">Total Panjang : <?=$total_panjang_all;?></td>
                                    <td style="padding:5px"></td>
                                    <td style="padding:5px"></td>
                                </tr>
                            </table>
                            
						</div>

						
					</div>
					<!-- Simple Datatable End -->
					
					
					
        </div>
    </div>
</div>
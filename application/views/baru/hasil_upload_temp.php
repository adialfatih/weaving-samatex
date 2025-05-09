<?php $arb = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar','04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'];
?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">Menampilkan File Temporary Upload Inspect Finish</p>
						</div>
						<div class="pd-20">
                            <table border="1" style="border-collapse:collapse;">
                                <tr>
                                    <td style="padding:5px" style="padding:5px;">No</td>
                                    <td style="padding:5px">Kode Roll</td>
                                    <td style="padding:5px">Tanggal di excel</td>
                                    <td style="padding:5px">Panjang</td>
                                    <td style="padding:5px">BS</td>
                                    <td style="padding:5px">CRT</td>
                                    <td style="padding:5px">BP</td>
                                    <td style="padding:5px">Satuan</td>
                                    <td style="padding:5px">Operator</td>
                                    <td style="padding:5px">Ket</td>
                                    <td style="padding:5px">Kode Konstruksi</td>
                                    <td style="padding:5px">Note</td>
                                    <td style="padding:5px">Total Ukuran</td>
                                    <td style="padding:5px">Simpan</td>
                                    <td style="padding:5px">Tanggal Simpan</td>
                                    
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
                                    $ex = explode(',',$val->panjang);
                                    $tgl = $val->tgl;
                                ?>
                                <tr>
                                    <td style="padding:5px"><?=$n+1;?></td>
                                    <td style="padding:5px"><?=$occurrences > 1 ? '<span style="color:red;">'.$val->kode_roll.'</span>':''.$val->kode_roll.'';?></td>
                                    <td style="padding:5px"><?=$tgl;?></td>
                                    <td style="padding:5px"><?=$val->panjang;?></td>
                                    <td style="padding:5px"><?=$val->bs;?></td>
                                    <td style="padding:5px"><?=$val->crt;?></td>
                                    <td style="padding:5px"><?=$val->bp;?></td>
                                    <td style="padding:5px"><?=$val->satuan;?></td>
                                    <td style="padding:5px"><?=$val->operator;?></td>
                                    <td style="padding:5px"><?=$val->ket;?></td>
                                    <td style="padding:5px"><?=$val->kons;?></td>
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
                                        echo "<td style='padding:5px'>".$val->panjang."</td>";
                                        $total_panjang_all+=$val->panjang;
                                    }
                                    ?>
                                    <td style="padding:5px">
                                        <?php 
                                            $cek_data_diif = $this->data_model->get_byid('data_if', ['kode_roll' => $val->kode_roll]);
                                            $cek_data_diif_lama = $this->data_model->get_byid('data_if_lama', ['kode_roll' => $val->kode_roll]);
                                            if($cek_data_diif->num_rows() == 0){
                                                echo "0";
                                            } else {
                                                echo "1";
                                            }
                                            if($cek_data_diif_lama->num_rows() == 0){
                                                echo "0";
                                            } else {
                                                echo "1";
                                            }
                                        ?>
                                    </td>
                                    <td style="padding:5px">
                                        <?php
                                            if($cek_data_diif->num_rows() == 0){
                                                echo "";
                                            } else {
                                                if($cek_data_diif->row('tgl_potong') != $tgl){
                                                    echo "<span style='color:red;'>".$cek_data_diif->row('tgl_potong')."</span>";
                                                } else {
                                                    echo "<span>".$cek_data_diif->row('tgl_potong')."</span>";
                                                }
                                            }
                                            if($cek_data_diif_lama->num_rows() == 0){
                                                echo "";
                                            } else {
                                                if($cek_data_diif_lama->row('tgl') != $tgl){
                                                    echo "<span style='color:red;'>".$cek_data_diif_lama->row('tgl')."</span>";
                                                } else {
                                                    echo "<span>".$cek_data_diif_lama->row('tgl')."</span>";
                                                }
                                               
                                            }
                                        ?>
                                    </td>
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
                                    <td style="padding:5px"></td>
                                    <td style="padding:5px"></td>
                                    <td style="padding:5px"></td>
                                    <td style="padding:5px">Total Roll : <?=$total_roll;?></td>
                                    <td style="padding:5px">Total Panjang : <?=$total_panjang_all;?></td>
                                    <td></td>
                                </tr>
                            </table>
                            
						</div>

						
					</div>
					<!-- Simple Datatable End -->
					
					
					
        </div>
    </div>
</div>
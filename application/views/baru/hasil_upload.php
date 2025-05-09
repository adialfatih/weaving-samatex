<?php $arb = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar','04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des']; 
if($produksi=="ig"){
    $dt_produksi = "Inspect Grey";
    $query = $this->data_model->get_byid('data_ig', ['tanggal'=>$tgl, 'dep'=>$sess_dep]);
    $query2 = "null";
} elseif($produksi=="fg"){
    $dt_produksi = "Folding Grey";
    $query = $this->data_model->get_byid('data_fol', ['jns_fold'=>'Grey','tgl'=>$tgl, 'loc'=>$sess_dep]);
    $query2 = $this->data_model->get_byid('data_fol_lama', ['folding'=>'Grey','lokasi'=>$sess_dep,'tanggal'=>$tgl]);
} elseif($produksi=="iff"){
    $dt_produksi = "Inspect Finish";
    $query = $this->data_model->get_byid('data_if', ['tgl_potong'=>$tgl]);
    $query2 = $this->data_model->get_byid('data_if_lama', ['tgl'=>$tgl]);
} else {
    $dt_produksi = "Folding Finish";
    $query = $this->data_model->get_byid('data_fol', ['jns_fold'=>'Finish','tgl'=>$tgl, 'loc'=>$sess_dep]);
    $query2 = $this->data_model->get_byid('data_fol_lama', ['folding'=>'Finish','lokasi'=>$sess_dep,'tanggal'=>$tgl]);
}
$ex = explode('-', $tgl);
$prinTgl= $ex[2]." ".$arb[$ex[1]]." ".$ex[0];
?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">Menampilkan Hasil Upload Produksi <strong><?=$dt_produksi;?></strong> pada tanggal <strong><?=$prinTgl;?></strong></p>
						</div>
						<div class="pd-20">
                            <!-- ini batas inspect Grey tampilan awal -->
                            <?php if($produksi=="ig"){ ?>
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4" class="table-primary"><strong>Hasil upload Inspect Grey</strong></td>
                                    <td colspan="3"><strong>Inspect Finish</strong></td>
                                    <td colspan="4"><strong>Folding</strong></td>
                                </tr>
                                <tr>
                                    <td class="table-primary"><strong>No</strong></td>
                                    <td class="table-primary"><strong>Kode Roll</strong></td>
                                    <td class="table-primary"><strong>Ukuran Inspect</strong></td>
                                    <td class="table-primary"><strong>Konstruksi</strong></td>
                                    <td><strong>Ukuran</strong></td>
                                    <td><strong>Konstruksi</strong></td>
                                    <td><strong>Tanggal</strong></td>
                                    <td><strong>Jenis Folding</strong></td>
                                    <td><strong>Ukuran</strong></td>
                                    <td><strong>Tanggal Folding</strong></td>
                                    <td><strong>Konstruksi</strong></td>
                                </tr>
                            <?php 
                            $total=0;
                            $_kons = array(); $_jumlah = array();
                            foreach($query->result() as $n => $val): 
                                $kode_roll = $val->kode_roll;
                                $konstruksi1 = $val->konstruksi;
                                $total+=$val->ukuran_ori;
                                if(in_array($val->konstruksi, $_kons)) {
                                    $indexs = array_search($val->konstruksi, $_kons);
                                    $ukuran_sekarang = floatval($_jumlah[$indexs]);
                                    $tambah_ukuran = $ukuran_sekarang + $val->ukuran_ori;
                                    $_jumlah[$indexs] = $tambah_ukuran;
                                } else {
                                    $_kons[] = $val->konstruksi;
                                    $_jumlah[] = $val->ukuran_ori;
                                }
                                $cek_if = $this->data_model->get_byid('data_if', ['kode_roll'=>$kode_roll]);
                                $cek_fol = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll]);
                            ?>
                                <tr>
                                    <td class="table-primary"><?=$n+1;?></td>
                                    <td class="table-primary"><?=$kode_roll;?></td>
                                    <td class="table-primary"><?=$val->ukuran_ori;?></td>
                                    <td class="table-primary"><?=$val->konstruksi;?></td>
                                    <?php if($cek_if->num_rows()==1){ ?>
                                    <td><?=$cek_if->row("ukuran_ori");?></td>
                                    <td><?=$cek_if->row("konstruksi");?>
                                        <?php if($cek_if->row("konstruksi") == $konstruksi1){
                                            echo "".$cek_if->row("konstruksi")."";
                                        } else {
                                            echo "<code>".$cek_if->row("konstruksi")."</code>";
                                        } ?>
                                    </td>
                                    <td>
                                    <?php
                                        $tgl_if = explode('-', $cek_if->row("tgl_potong"));
                                        echo $tgl_if[2]." ".$arb[$tgl_if[1]]." ".$tgl_if[0];
                                    ?>
                                    </td>
                                    <?php
                                    } else {
                                        echo "<td colspan='3'><small>Tidak / belum di inspect finish</small></td>";
                                    } 
                                    if($cek_fol->num_rows() == 1){
                                        echo "<td>Folding ".$cek_fol->row("jns_fold")."</td>";
                                        echo "<td>".$cek_fol->row("ukuran")."</td>";
                                        $oi = explode('-', $cek_fol->row("tgl"));
                                        echo "<td>".$oi[2]." ".$arb[$oi[1]]." ".$oi[0]."</td>";
                                        if($konstruksi1==$cek_fol->row("konstruksi")){
                                            echo "<td>".$cek_fol->row("konstruksi")."</td>";
                                        } else {
                                            echo "<td><code>".$cek_fol->row("konstruksi")."</code></td>";
                                        }
                                    } else {
                                        echo "<td colspan='4'><small>Tidak / belum di folding</small></td>";
                                    }
                                    ?>
                                    
                                </tr>    
                            <?php endforeach; ?>
                                <tr>
                                    <td colspan="2" class="table-primary"><strong>Total</strong></td>
                                    <td class="table-primary"><strong><?=number_format($total,0,',','.');?></strong> Meter</td>
                                    <td class="table-primary"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                            <p><strong>Upload Summary : </strong><br>Jumlah Roll <strong><?=$query->num_rows();?></strong><br>Total Panjang <strong><?=number_format($total,0,',','.');?></strong> Meter</p>
                            <?php 
                                for ($i=0; $i <count($_kons) ; $i++) { 
                                    echo "Ukuran konstruksi <strong>".$_kons[$i]."</strong> adalah <strong>".$_jumlah[$i]."</strong> Meter <br>";
                                }
                                
                            } elseif($produksi=="fg"){ ?>
                            <!-- ini batas inspect Grey tampilan akhir -->
                            <!-- ini batas folding Grey tampilan awal -->
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4" class="table-primary"><strong>Hasil Upload Folding Grey</strong></td>
                                    <td colspan="3"><strong>Data Inspect Grey</strong></td>
                                </tr>
                                <tr>
                                    <td class="table-primary">No.</td>
                                    <td class="table-primary"><strong>Kode Roll</strong></td>
                                    <td class="table-primary"><strong>Ukuran</strong></td>
                                    <td class="table-primary"><strong>Konstruksi</strong></td>
                                    <td><strong>Ukuran Inspect</strong></td>
                                    <td><strong>Tanggal</strong></td>
                                    <td><strong>Konstruksi</strong></td>
                                    
                                </tr>
                                <?php 
                                $total = 0; $_kons = array(); $_jumlah = array();
                                foreach($query->result() as $n => $val): 
                                    $konstruksi1 = $val->konstruksi;
                                    $total+=$val->ukuran;
                                    if(in_array($val->konstruksi, $_kons)) {
                                        $indexs = array_search($val->konstruksi, $_kons);
                                        $ukuran_sekarang = floatval($_jumlah[$indexs]);
                                        $tambah_ukuran = $ukuran_sekarang + $val->ukuran;
                                        $_jumlah[$indexs] = $tambah_ukuran;
                                    } else {
                                        $_kons[] = $val->konstruksi;
                                        $_jumlah[] = $val->ukuran;
                                    }
                                    $cek_ig = $this->data_model->get_byid('data_ig', ['kode_roll'=>$val->kode_roll]);
                                ?>
                                <tr>
                                    <td class="table-primary"><?=$n+1;?></td>
                                    <td class="table-primary"><?=$val->kode_roll;?></td>
                                    <td class="table-primary"><?=$val->ukuran;?></td>
                                    <td class="table-primary"><?=$val->konstruksi;?></td>
                                    <?php
                                    if($cek_ig->num_rows() == 1){
                                        echo "<td>".$cek_ig->row("ukuran_ori")."</td>";
                                        echo "<td>".$cek_ig->row("tanggal")."</td>";
                                        if($cek_ig->row("konstruksi") == $konstruksi1){
                                            echo "<td>".$cek_ig->row("konstruksi")."</td>";
                                        } else {
                                            echo "<td><code>".$cek_ig->row("konstruksi")."</code></td>";
                                        }
                                    } else {
                                        echo "<td colspan='3'><small>Data inspect Grey tidak ditemukan</small></td>";
                                    }
                                    ?>
                                </tr>
                                <?php endforeach; 
                                $total2 = 0;
                                foreach($query2->result() as $nn => $vall): 
                                    $total2+=$vall->ukuran_asli;
                                    if(in_array($vall->konstruksi, $_kons)) {
                                        $indexs = array_search($vall->konstruksi, $_kons);
                                        $ukuran_sekarang = floatval($_jumlah[$indexs]);
                                        $tambah_ukuran = $ukuran_sekarang + $vall->ukuran_asli;
                                        $_jumlah[$indexs] = $tambah_ukuran;
                                    } else {
                                        $_kons[] = $vall->konstruksi;
                                        $_jumlah[] = $vall->ukuran_asli;
                                    }
                                ?>
                                <tr>
                                    <td class="table-secondary"><?=$n+2;?></td>
                                    <td class="table-secondary"><?=$vall->kode_roll;?></td>
                                    <td class="table-secondary"><?=$vall->ukuran_asli;?></td>
                                    <td class="table-secondary"><?=$vall->konstruksi;?></td>
                                    <td class="table-secondary"></td>
                                    <td class="table-secondary"></td>
                                    <td class="table-secondary"></td>
                                </tr>
                                <?php $n++; endforeach; 
                                $jumlah_roll_asli = $query->num_rows();
                                $jumlah_roll_lama = $query2->num_rows();
                                $jumlah_roll_total = $jumlah_roll_asli + $jumlah_roll_lama;
                                $total_ukuran = $total + $total2;
                                ?>
                            </table>
                            <p><strong>Upload Summary : </strong><br>Jumlah roll secara total adalah <strong><?=$jumlah_roll_total;?></strong> roll. Sebanyak <strong><?=$jumlah_roll_asli;?></strong> roll (<strong>
                            <?php if(fmod($total, 1) !== 0.00){ 
                                echo number_format($total,2,',','.'); 
                            } else { 
                                echo number_format($total,0,',','.');
                            } ?>    
                            </strong> Meter) terdeteksi sebagai roll baru karena kode roll ditemukan di dalam data inspect grey. Sedangkan sebanyak <strong><?=$jumlah_roll_lama;?></strong> roll (<strong>
                            <?php if(fmod($total2, 1) !== 0.00){ 
                                echo number_format($total2,2,',','.'); 
                            } else { 
                                echo number_format($total2,0,',','.');
                            } ?>    
                            </strong> Meter) dihitung sebagai stok lama karena kode roll tidak ditemukan di data inspect grey <br>Total Panjang <strong>
                            <?php if(fmod($total_ukuran, 1) !== 0.00){ 
                                echo number_format($total_ukuran,2,',','.'); 
                            } else { 
                                echo number_format($total_ukuran,0,',','.');
                            } ?>
                            </strong> Meter</p>
                            <?php 
                                for ($i=0; $i <count($_kons) ; $i++) { 
                                    echo "Jumlah Folding konstruksi <strong>".$_kons[$i]."</strong> adalah <strong>".$_jumlah[$i]."</strong> Meter <br>";
                                }
                                ?>
                            <p>&nbsp;</p>
                            <p><a href="<?=base_url('reverse/tempfol/'.$tgl.'/Grey');?>" target="_blank" style="color:blue;">File Temporary</a></p>
                            <?php
                            } elseif($produksi=="iff"){ ?>
                            <!-- ini batas folding Grey tampilan akhir -->
                            <!-- ini batas inspect finish tampilan awal -->
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4" class="table-primary"><strong>Hasil Upload Inspect Finish</strong></td>
                                </tr>
                                <tr>
                                    <td class="table-primary"><strong>No</strong></td>
                                    <td class="table-primary"><strong>Kode Roll</strong></td>
                                    <td class="table-primary"><strong>Ukuran</strong></td>
                                    <td class="table-primary"><strong>Konstruksi</strong></td>
                                    <td>Note</td>
                                    <td>Konstruksi Sebelumnya</td>
                                    <td>Nomor Mesin</td>
                                </tr>
                                <?php
                                $n=0; $total=0; $_kons = array(); $_jumlah = array();
                                foreach($query->result() as $n => $val):
                                    $konstruksi1 = $val->konstruksi;
                                    $total+= $val->ukuran_ori;
                                    if(in_array($val->konstruksi, $_kons)) {
                                        $indexs = array_search($val->konstruksi, $_kons);
                                        $ukuran_sekarang = floatval($_jumlah[$indexs]);
                                        $tambah_ukuran = $ukuran_sekarang + $val->ukuran_ori;
                                        $_jumlah[$indexs] = $tambah_ukuran;
                                    } else {
                                        $_kons[] = $val->konstruksi;
                                        $_jumlah[] = $val->ukuran_ori;
                                    }
                                    $cek_kode_dalam_table = $this->data_model->get_byid('data_if', ['kode_roll'=>$val->kode_roll]);
                                    $cek_kons_before = $this->data_model->get_byid('data_ig', ['kode_roll'=>$val->kode_roll]);
                                ?>
                                <tr>
                                    <td class="table-primary"><?=$n+1;?></td>
                                    <td class="table-primary"><?=$val->kode_roll;?></td>
                                    <td class="table-primary"><?=$val->ukuran_ori;?></td>
                                    <td class="table-primary"><?=$konstruksi1;?></td>
                                    <?php if($cek_kode_dalam_table->num_rows() == 1){ echo "<td></td>"; } else { echo '<td style="color:red;">Doble Kode</td>'; } 
                                    if($cek_kons_before->num_rows()==0){
                                        echo "<td colspan='2'>Tidak ditemukan kode di inspect grey</td>";
                                    } else {
                                        if($konstruksi1 == $cek_kons_before->row("konstruksi")){
                                            echo "<td>".$cek_kons_before->row("konstruksi")."</td>";
                                            echo "<td>".$cek_kons_before->row("no_mesin")."</td>";
                                        } else {
                                            echo "<td style='color:red;'>".$cek_kons_before->row("konstruksi")."</td>";
                                            echo "<td>".$cek_kons_before->row("no_mesin")."</td>";
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php $n++;
                                endforeach;
                                ?>
                                <?php $total2 = 0;
                                foreach($query2->result() as $nn => $vall):
                                    $konstruksi2 = $vall->kodekons; 
                                    $total2+= $vall->panjang;
                                    if(in_array($vall->kodekons, $_kons)) {
                                        $indexs = array_search($vall->kodekons, $_kons);
                                        $ukuran_sekarang = floatval($_jumlah[$indexs]);
                                        $tambah_ukuran = $ukuran_sekarang + $vall->panjang;
                                        $_jumlah[$indexs] = $tambah_ukuran;
                                    } else {
                                        $_kons[] = $vall->kodekons;
                                        $_jumlah[] = $vall->panjang;
                                    }
                                    $cek_kode_dalam_table2 = $this->data_model->get_byid('data_if_lama', ['kode_roll'=>$vall->kode_roll]);
                                ?>
                                <tr>
                                    <td class="table-secondary"><?=$nn+1;?></td>
                                    <td class="table-secondary"><?=$vall->kode_roll;?></td>
                                    <td class="table-secondary"><?=$vall->panjang;?></td>
                                    <td class="table-secondary"><?=$konstruksi2;?></td>
                                    <?php if($cek_kode_dalam_table2->num_rows() == 1){ echo "<td></td>"; } else { echo '<td style="color:red;">Doble Kode</td>'; } ?>
                                </tr>
                                <?php
                                endforeach;
                                $jumlah_roll_asli = $query->num_rows();
                                $jumlah_roll_lama = $query2->num_rows();
                                $jumlah_roll_total = $jumlah_roll_asli + $jumlah_roll_lama;
                                $total_ukuran = $total + $total2;
                                ?>
                            </table>
                            <p><strong>Upload Summary : </strong><br>Jumlah roll secara total adalah <strong><?=$jumlah_roll_total;?></strong> roll. Sebanyak <strong><?=$jumlah_roll_asli;?></strong> roll (<strong>
                            <?php if(fmod($total, 1) !== 0.00){ 
                                echo number_format($total,2,',','.'); 
                            } else { 
                                echo number_format($total,0,',','.');
                            } ?>    
                            </strong> Yard) terdeteksi sebagai roll baru karena kode roll ditemukan di dalam data inspect grey. Sedangkan sebanyak <strong><?=$jumlah_roll_lama;?></strong> roll (<strong>
                            <?php if(fmod($total2, 1) !== 0.00){ 
                                echo number_format($total2,2,',','.'); 
                            } else { 
                                echo number_format($total2,0,',','.');
                            } ?>    
                            </strong> Yard) dihitung sebagai stok lama karena kode roll tidak ditemukan di data inspect grey <br>Total Panjang <strong>
                            <?php if(fmod($total_ukuran, 1) !== 0.00){ 
                                echo number_format($total_ukuran,2,',','.'); 
                            } else { 
                                echo number_format($total_ukuran,0,',','.');
                            } ?>
                            </strong> Yard</p>
                            <?php 
                                for ($i=0; $i <count($_kons) ; $i++) { 
                                    echo "Jumlah Inspect Finish Konstruksi <strong>".$_kons[$i]."</strong> adalah <strong>";
                                    if(fmod($_jumlah[$i], 1) !== 0.00){ 
                                        echo number_format($_jumlah[$i],2,',','.'); 
                                    } else { 
                                        echo number_format($_jumlah[$i],0,',','.');
                                    }
                                    echo "</strong> Meter <br>";
                                }
                            ?>
                            <p>&nbsp;</p>
                            <p><a href="<?=base_url('reverse/tempif/'.$tgl);?>" target="_blank" style="color:blue;">File Temporary</a></p>
                            <?php
                            } elseif($produksi=="ff"){ ?>
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4" class="table-primary"><strong>Hasil Upload Folding Finish</strong></td>
                                    <td colspan="3"><strong>Data Inspect Grey</strong></td>
                                </tr>
                                <tr>
                                    <td class="table-primary">No.</td>
                                    <td class="table-primary"><strong>Kode Roll</strong></td>
                                    <td class="table-primary"><strong>Ukuran</strong></td>
                                    <td class="table-primary"><strong>Konstruksi</strong></td>
                                    <td><strong>Ukuran Inspect</strong></td>
                                    <td><strong>Tanggal</strong></td>
                                    <td><strong>Konstruksi</strong></td>
                                    
                                </tr>
                                <?php 
                                $total = 0; $_kons = array(); $_jumlah = array();
                                foreach($query->result() as $n => $val): 
                                    $konstruksi1 = $val->konstruksi;
                                    $total+=$val->ukuran;
                                    if(in_array($val->konstruksi, $_kons)) {
                                        $indexs = array_search($val->konstruksi, $_kons);
                                        $ukuran_sekarang = floatval($_jumlah[$indexs]);
                                        $tambah_ukuran = $ukuran_sekarang + $val->ukuran;
                                        $_jumlah[$indexs] = $tambah_ukuran;
                                    } else {
                                        $_kons[] = $val->konstruksi;
                                        $_jumlah[] = $val->ukuran;
                                    }
                                    $cek_ig = $this->data_model->get_byid('data_ig', ['kode_roll'=>$val->kode_roll]);
                                ?>
                                <tr>
                                    <td class="table-primary"><?=$n+1;?></td>
                                    <td class="table-primary"><?=$val->kode_roll;?></td>
                                    <td class="table-primary"><?=$val->ukuran;?></td>
                                    <td class="table-primary"><?=$val->konstruksi;?></td>
                                    <?php
                                    if($cek_ig->num_rows() == 1){
                                        echo "<td>".$cek_ig->row("ukuran_ori")."</td>";
                                        echo "<td>".$cek_ig->row("tanggal")."</td>";
                                        if($cek_ig->row("konstruksi") == $konstruksi1){
                                            echo "<td>".$cek_ig->row("konstruksi")."</td>";
                                        } else {
                                            echo "<td><code>".$cek_ig->row("konstruksi")."</code></td>";
                                        }
                                    } else {
                                        echo "<td colspan='3'><small>Data inspect Grey tidak ditemukan</small></td>";
                                    }
                                    ?>
                                </tr>
                                <?php endforeach; 
                                $total2 = 0;
                                foreach($query2->result() as $nn => $vall): 
                                    $total2+=$vall->ukuran_asli;
                                    if(in_array($vall->konstruksi, $_kons)) {
                                        $indexs = array_search($vall->konstruksi, $_kons);
                                        $ukuran_sekarang = floatval($_jumlah[$indexs]);
                                        $tambah_ukuran = $ukuran_sekarang + $vall->ukuran_asli;
                                        $_jumlah[$indexs] = $tambah_ukuran;
                                    } else {
                                        $_kons[] = $vall->konstruksi;
                                        $_jumlah[] = $vall->ukuran_asli;
                                    }
                                ?>
                                <tr>
                                    <td class="table-secondary"><?=$n+2;?></td>
                                    <td class="table-secondary"><?=$vall->kode_roll;?></td>
                                    <td class="table-secondary"><?=$vall->ukuran_asli;?></td>
                                    <td class="table-secondary"><?=$vall->konstruksi;?></td>
                                    <td class="table-secondary"></td>
                                    <td class="table-secondary"></td>
                                    <td class="table-secondary"></td>
                                </tr>
                                <?php $n++; endforeach; 
                                $jumlah_roll_asli = $query->num_rows();
                                $jumlah_roll_lama = $query2->num_rows();
                                $jumlah_roll_total = $jumlah_roll_asli + $jumlah_roll_lama;
                                $total_ukuran = $total + $total2;
                                ?>
                            </table>
                            <p><strong>Upload Summary : </strong><br>Jumlah roll secara total adalah <strong><?=$jumlah_roll_total;?></strong> roll. Sebanyak <strong><?=$jumlah_roll_asli;?></strong> roll (<strong>
                            <?php if(fmod($total, 1) !== 0.00){ 
                                echo number_format($total,2,',','.'); 
                            } else { 
                                echo number_format($total,0,',','.');
                            } ?>    
                            </strong> Meter) terdeteksi sebagai roll baru karena kode roll ditemukan di dalam data inspect grey. Sedangkan sebanyak <strong><?=$jumlah_roll_lama;?></strong> roll (<strong>
                            <?php if(fmod($total2, 1) !== 0.00){ 
                                echo number_format($total2,2,',','.'); 
                            } else { 
                                echo number_format($total2,0,',','.');
                            } ?>    
                            </strong> Meter) dihitung sebagai stok lama karena kode roll tidak ditemukan di data inspect grey <br>Total Panjang <strong>
                            <?php if(fmod($total_ukuran, 1) !== 0.00){ 
                                echo number_format($total_ukuran,2,',','.'); 
                            } else { 
                                echo number_format($total_ukuran,0,',','.');
                            } ?>
                            </strong> Meter</p>
                            <?php 
                                for ($i=0; $i <count($_kons) ; $i++) { 
                                    echo "Jumlah Folding konstruksi <strong>".$_kons[$i]."</strong> adalah <strong>".$_jumlah[$i]."</strong> Meter <br>";
                                }
                                ?>
                            <p>&nbsp;</p>
                            <p><a href="<?=base_url('reverse/tempfol/'.$tgl.'/Finish');?>" target="_blank" style="color:blue;">File Temporary</a></p>
                            <!-- ini batas inspect finish tampilan akhir -->
                            <?php } ?>
						</div>

						
					</div>
					<!-- Simple Datatable End -->
					
					
					
        </div>
    </div>
</div>
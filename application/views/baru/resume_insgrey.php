<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', '00' => 'Undefined' ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="title">
							<h4>Resume Hasil Upload</h4>
					    </div>
                        <?php 
                            if($alldata->num_rows() > 0){
                        ?>
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>No.</strong></td>
                                <td><strong>Kode Roll</strong></td>
                                <td><strong>Konstruksi</strong></td>
                                <td><strong>No MC</strong></td>
                                <td><strong>No Beam</strong></td>
                                <td><strong>OKA</strong></td>
                                <td><strong>Ukuran Panjang</strong></td>
                                <td><strong>BS</strong></td>
                                <td><strong>BP</strong></td>
                                <td><strong>Tanggal</strong></td>
                                <td><strong>Operator</strong></td>
                                <td><strong>Ket</strong></td>
                            </tr>
                            <?php
                            $error_msg = 0;
                            $total_panjang = 0;
                            $total_bs = 0;
                            $total_bp = 0;
                            $ar_kons = array(); $ar_operator = array();
                            $ar_koderoll = array();
                            foreach($alldata->result() as $n => $val): 
                                $ar_kode[]=$val->kode_roll;
                            endforeach;
                            foreach($alldata->result() as $n => $val):
                                $count_values = array_count_values($ar_kode);
                                $occurrences = $count_values[$val->kode_roll];
                            $kdrol = $val->kode_roll;
                            $cek_kdrol = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kdrol]);
                            if($cek_kdrol->num_rows() == 0){
                                $pesan_error = '<i class="icon-copy bi bi-check-circle-fill" style="color:green;"></i>';
                            } else {
                                $kd_uptgl = $cek_kdrol->row("tanggal");
                                $pesan_error = "<code>Kode sudah digunakan (upload tgl: ".$kd_uptgl.")</code>";
                            }
                            $kdkons = $val->konstruksi;
                            $cek_kons = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kdkons]);
                            if($cek_kons->num_rows() == 1){
                                $printKons = "".$kdkons."";
                            } else {
                                $printKons = '<span style="color:red;">'.$kdkons.'</span>';
                            }
                            $total_panjang+=$val->ukuran;
                            $total_bs+=$val->bs;
                            $total_bp+=$val->bp;
                            if (in_array($kdkons, $ar_kons)){} else {
                                $ar_kons[]=$kdkons;
                            }
                            if (in_array($val->operator, $ar_operator)){} else {
                                $ar_operator[]=$val->operator;
                            }
                            ?>
                            <tr>
                                <td><?=$n+1;?></td>
                                <td><?=$occurrences > 1 ? '<span style="color:red;">'.$val->kode_roll.'</span>':''.$val->kode_roll.'';?></td>
                                <td><?=$printKons;?></td>
                                <td><?=$val->nomc;?></td>
                                <td><?=$val->nobeam;?></td>
                                <td><?=$val->oka;?></td>
                                <td><?=$val->ukuran;?></td>
                                <td><?=$val->bs;?></td>
                                <td><?=$val->bp;?></td>
                                <td>
                                    <?php $ex=explode('-', $val->tgl); echo $ex[2]." ".$bln[$ex[1]]." ".$ex[0]; ?>
                                </td>
                                <td><?=$val->operator;?></td>
                                <td>
                                    <?php
                                    $simpan = $this->data_model->get_byid('log_input_roll', ['kodeauto'=>$kode])->row("simpan");
                                    if($simpan=="saved"){
                                        echo '<i class="icon-copy bi bi-check-circle-fill" style="color:green;"></i>';
                                    } else {
                                        echo $pesan_error;
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                            endforeach;
                            ?>
                            <tr>
                                <td colspan="6">Total</td>
                                <td>
                                    <?php
                                        if(fmod($total_panjang, 1) !== 0.00){
                                            $ttlpjg = number_format($total_panjang,2,',','.');
                                        } else {
                                            $ttlpjg = number_format($total_panjang,0,',','.');
                                        }
                                        echo $ttlpjg;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        if(fmod($total_bs, 1) !== 0.00){
                                            $ttlbs = number_format($total_bs,2,',','.');
                                        } else {
                                            $ttlbs = number_format($total_bs,0,',','.');
                                        }
                                        echo $ttlbs;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        if(fmod($total_bp, 1) !== 0.00){
                                            $ttlbp = number_format($total_bp,2,',','.');
                                        } else {
                                            $ttlbp = number_format($total_bp,0,',','.');
                                        }
                                        echo $ttlbp;
                                    ?>
                                </td>
                                
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="12">
                                    <ol>
                                        <li>1. Total jumlah roll yang ada di excel anda adalah <strong><?=$alldata->num_rows();?> Roll</strong></li>
                                        <?php 
                                        for ($i=0; $i < count($ar_kons); $i++) { 
                                            $jml_per_kons = $this->db->query("SELECT SUM(ukuran) AS ukr FROM temp_upload_ig WHERE konstruksi='$ar_kons[$i]' AND kode_upload='$kode'")->row("ukr");
                                            if(fmod($jml_per_kons, 1) !== 0.00){
                                                $jml_per_kons2 = number_format($jml_per_kons,2,',','.');
                                            } else {
                                                $jml_per_kons2 = number_format($jml_per_kons,0,',','.');
                                            }
                                            $jml_per_kons_bp = $this->db->query("SELECT SUM(ukuran) AS ukr FROM temp_upload_ig WHERE konstruksi='$ar_kons[$i]' AND ukuran<50 AND kode_upload='$kode'")->row("ukr");
                                            if($jml_per_kons_bp > 0){
                                                $ori = $jml_per_kons - $jml_per_kons_bp;
                                                $txt = "(ORI <strong>".number_format($ori)."</strong> BP <strong>".number_format($jml_per_kons_bp)."</strong>)";
                                            } else {
                                                $txt ="";
                                            }
                                        ?>
                                        <li><?=$i+2;?>. Total panjang untuk konstruksi <strong><?=$ar_kons[$i];?></strong> adalah : <strong><?=$jml_per_kons2;?></strong> Meter <?=$txt;?></li>
                                        <?php
                                        }
                                        ?>
                                    </ol>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>Nama Operator</td>
                                            <?php 
                                            for ($t=0; $t < count($ar_kons); $t++) { 
                                                echo "<td>".$ar_kons[$t]."</td>";
                                            }
                                            ?>
                                            <td>Total Per Operator</td>
                                        </tr>
                                        <?php for ($r=0; $r < count($ar_operator); $r++) { ?>
                                        <tr>
                                            <td><?=$ar_operator[$r];?></td>
                                            <?php 
                                            $total_operator = 0;
                                            for ($p=0; $p < count($ar_kons); $p++) { 
                                                $cek = $this->db->query("SELECT SUM(ukuran) AS ukr FROM temp_upload_ig WHERE konstruksi='$ar_kons[$p]' AND operator='$ar_operator[$r]' AND kode_upload='$kode'")->row("ukr");
                                                echo "<td>".number_format($cek)."</td>";
                                                $total_operator+=$cek;
                                            }
                                            ?>
                                            <td><?=number_format($total_operator);?></td>
                                        </tr>
                                        <?php } ?>
                                        
                                    </table>
                                    <p style="width:100%;text-align:right;">
                                        <a href="<?=base_url('resume/deleteinsgrey/'.$kode);?>"><button class="btn btn-danger">HAPUS</button></a>
                                        <a href="<?=base_url('resume/savedinsgrey/'.$kode);?>"><button class="btn btn-success">SIMPAN PROSES</button></a>
                                    </p>
                                    <p><strong>Note</strong> : Proses ini belum tersimpan di sistem, anda diharuskan mengecek ulang hasil import dari file excel yang anda upload. Jika terjadi kesalahan hapus proses ini dan upload ulang file excel Inspect Grey.</p>
                                </td>
                            </tr>
                            
                            
                        </table>
                        <?php
                            } else {
                                echo "<code>Data tidak ditemukan</code>";
                            }
                        ?>
						
							
					</div>
					<!-- Simple Datatable start -->
					<!-- Bootstrap Select End -->
					
					
        </div>
    </div>
</div>

<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', '00' => 'Undefined' ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
                
				<div class="min-height-200px">
                    <div class="alert alert-success" role="alert">
						Terimpan
					</div>
					<div class="page-header">
						<div class="title">
							<h4>Resume Hasil Upload</h4>
					    </div>
                        <?php 
                            if($alldata->num_rows() > 0){
                            $dtkod = $this->data_model->get_byid('log_input_roll', ['kodeauto'=>$kode])->row_array();
                                                            
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
                            $input_tgl = array();
                            foreach($alldata->result() as $n => $val):
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
                                <td><?=$val->kode_roll;?></td>
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
                                if($dtkod['simpan']=="null"){
                                $dtlist = [
                                    'kode_roll' => $kdrol,
                                    'konstruksi' => $kdkons,
                                    'no_mesin' => $val->nomc,
                                    'no_beam'=> $val->nobeam,
                                    'oka' => $val->oka,
                                    'ukuran_ori'=> $val->ukuran,
                                    'ukuran_bs'=> $val->bs,
                                    'ukuran_bp'=> $val->bp,
                                    'tanggal'=> $val->tgl,
                                    'operator'=> $val->operator,
                                    'bp_can_join'=> $val->ukuran < 50 ? 'true':'false',
                                    'dep'=> $dep_user,
                                    'loc_now'=> $dep_user,
                                    'yg_input'=> $dtkod['penginput'],
                                    'kode_upload'=> $kode,
                                ];
                                $this->data_model->saved('data_ig', $dtlist); }
                                $input_tgl []= $val->tgl;
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
                                <?php 
                                    $tgltocek = $input_tgl[0];
                                    //cek produksi harial total (tidak per sm)
                                    if($dtkod['simpan']=="null"){
                                    $cek1 = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgltocek, 'dep'=>$dep_user]);
                                    if($cek1->num_rows() == 0){
                                        $dtlist = [
                                            'tgl' => $tgltocek,
                                            'dep' => $dep_user,
                                            'prod_ig' => $total_panjang,
                                            'prod_fg' => 0,
                                            'prod_if' => 0,
                                            'prod_ff' => 0,
                                            'prod_bs1' => $total_bs,
                                            'prod_bp1' => $total_bp,
                                            'prod_bs2' => 0,
                                            'prod_bp2' => 0,
                                            'crt' => 0
                                        ];
                                        $this->data_model->saved('data_produksi_harian', $dtlist);
                                    } elseif($cek1->num_rows() == 1){
                                        $idedit = $cek1->row("id_prod_hr");
                                        $newig = floatval($cek1->row("prod_ig")) + $total_panjang;
                                        $newbs = floatval($cek1->row("prod_bs1")) + $total_bs;
                                        $newbp = floatval($cek1->row("prod_bp1")) + $total_bp;
                                        $dtlist = [
                                            'prod_ig' => round($newig,2),
                                            'prod_bs1' => round($newbs,2),
                                            'prod_bp1' => round($newbp,2)
                                        ];
                                        $this->data_model->updatedata('id_prod_hr',$idedit, 'data_produksi_harian', $dtlist);
                                    }
                                    }
                                ?>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="12">
                                    <ol>
                                        <li>1. Total jumlah roll yang ada di excel anda adalah <strong><?=$alldata->num_rows();?> Roll</strong></li>
                                        <?php 
                                        for ($i=0; $i < count($ar_kons); $i++) { //start for
                                            $jml_per_kons = $this->db->query("SELECT SUM(ukuran) AS ukr FROM temp_upload_ig WHERE konstruksi='$ar_kons[$i]' AND kode_upload='$kode'")->row("ukr");
                                            $jml_bsper_kons = $this->db->query("SELECT SUM(bs) AS ukr FROM temp_upload_ig WHERE konstruksi='$ar_kons[$i]' AND kode_upload='$kode'")->row("ukr");
                                            $jml_bpper_kons = $this->db->query("SELECT SUM(bp) AS ukr FROM temp_upload_ig WHERE konstruksi='$ar_kons[$i]' AND kode_upload='$kode'")->row("ukr");
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
                                            //input produksi per sm 
                                            if($dtkod['simpan']=="null"){
                                            $cek2 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$ar_kons[$i], 'tgl'=>$tgltocek, 'dep'=>$dep_user]);
                                            if($cek2->num_rows() == 0){
                                                $dtlist = [
                                                    'kode_konstruksi' => $ar_kons[$i],
                                                    'tgl' => $tgltocek,
                                                    'dep' => $dep_user,
                                                    'prod_ig' => $jml_per_kons,
                                                    'prod_fg' => 0,
                                                    'prod_if' => 0,
                                                    'prod_ff' => 0,
                                                    'prod_bs1' => $jml_bsper_kons,
                                                    'prod_bp1' => $jml_bpper_kons,
                                                    'prod_bs2' => 0,
                                                    'prod_bp2' => 0,
                                                    'crt' => 0
                                                ];
                                                $this->data_model->saved('data_produksi', $dtlist);
                                            } elseif($cek2->num_rows() == 1){
                                                $idedit = $cek2->row("id_produksi");
                                                $newig = floatval($cek2->row("prod_ig")) + $jml_per_kons;
                                                $newbs = floatval($cek2->row("prod_bs1")) + $jml_bsper_kons;
                                                $newbp = floatval($cek2->row("prod_bp1")) + $jml_bpper_kons;
                                                $dtlist = [
                                                    'prod_ig' => round($newig,2),
                                                    'prod_bs1' => round($newbs,2),
                                                    'prod_bp1' => round($newbp,2)
                                                ];
                                                $this->data_model->updatedata('id_produksi',$idedit, 'data_produksi', $dtlist);
                                            }
                                            //input stok by produksi per sm
                                            $cek3 = $this->data_model->get_byid('data_stok', ['dep'=>$dep_user,'kode_konstruksi'=>$ar_kons[$i]]);
                                            if($cek3->num_rows() == 0){
                                                $dtlist = [
                                                    'dep' => $dep_user,
                                                    'kode_konstruksi' => $ar_kons[$i],
                                                    'prod_ig' => $jml_per_kons,
                                                    'prod_fg' => 0,
                                                    'prod_if' => 0,
                                                    'prod_ff' => 0,
                                                    'prod_bs1' => $jml_bsper_kons,
                                                    'prod_bp1' => $jml_bpper_kons,
                                                    'prod_bs2' => 0,
                                                    'prod_bp2' => 0,
                                                    'crt' => 0
                                                ];
                                                $this->data_model->saved('data_stok', $dtlist);
                                            } elseif($cek3->num_rows() == 1){
                                                $idedit = $cek3->row("idstok");
                                                $newig = floatval($cek3->row("prod_ig")) + $jml_per_kons;
                                                $newbs = floatval($cek3->row("prod_bs1")) + $jml_bsper_kons;
                                                $newbp = floatval($cek3->row("prod_bp1")) + $jml_bpper_kons;
                                                $dtlist = [
                                                    'prod_ig' => round($newig,2),
                                                    'prod_bs1' => round($newbs,2),
                                                    'prod_bp1' => round($newbp,2)
                                                ];
                                                $this->data_model->updatedata('idstok',$idedit, 'data_stok', $dtlist);
                                            }
                                            }
                                        } //end for
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
<?php
$this->data_model->updatedata('kodeauto',$kode,'log_input_roll',['simpan'=>'saved']);
?>
<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', '00' => 'Undefined' ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="title">
							<h4>Resume File Excel</h4>
					    </div>
                        <?php 
                            if($alldata->num_rows() > 0){
                                $ar_tgl = array();
                                foreach($alldata2->result() as $val){
                                    $ar_tgl[]=$val->tgl;
                                }
                                $setTgl = $ar_tgl[0];
                        ?>
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>No.</strong></td>
                                <td><strong>Kode Roll</strong></td>
                                <td><strong>Tanggal</strong></td>
                                <td><strong>Panjang</strong></td>
                                <td><strong>BS</strong></td>
                                <td><strong>CRT</strong></td>
                                <td><strong>BP</strong></td>
                                <td><strong>Satuan</strong></td>
                                <td><strong>Operator</strong></td>
                                <td><strong>Konstruksi</strong></td>
                                <td><strong>Ket</strong></td>
                            </tr>
                            <?php
                            $ar_kons = array();
                            $ar_oprt = array();
                            $ar_kode = array();
                            $total_bs = 0;
                            $total_bp = 0;
                            $total_crt = 0;
                            foreach($alldata->result() as $n => $val){
                                $ar_kode[]=$val->kode_roll;
                                $total_bp+=$val->bp;
                                $total_bs+=$val->bs;
                                $total_crt+=$val->crt;
                            }
                            foreach($alldata->result() as $n => $val):
                                $ex = explode('-', $val->tgl);
                                $tglIndo = $ex[2]."-".$ex[1]."-".$ex[0];
                                $kdrol = $val->kode_roll;
                                if (in_array($val->kons, $ar_kons)){} else {
                                    $ar_kons[]=$val->kons;
                                }
                                if (in_array($val->operator, $ar_oprt)){} else {
                                    $ar_oprt[]=$val->operator;
                                }
                                $count_values = array_count_values($ar_kode);
                                $occurrences = $count_values[$val->kode_roll];
                            ?>
                            <tr>
                                <td><?=$n+1;?></td>
                                <td><?=$occurrences > 1 ? '<span style="color:red;">'.$val->kode_roll.'</span>':''.$val->kode_roll.'';?></td>
                                <td><?=$tglIndo;?></td>
                                <td><?=$val->panjang;?></td>
                                <td><?=$val->bs;?></td>
                                <td><?=$val->crt;?></td>
                                <td><?=$val->bp;?></td>
                                <td><?=$val->satuan;?></td>
                                <td><?=$val->operator;?></td>
                                <td><?=$val->kons;?></td>
                                <td>
                                    <?php
                                        $ror_error = array();
                                        //cek kode ini sudah ada dalam database inspect finish / belum
                                        $cek1 = $this->data_model->get_byid('data_if', ['kode_roll'=>$kdrol]);
                                        $cek2 = $this->data_model->get_byid('data_if_lama', ['kode_roll'=>$kdrol]);
                                        if($cek1->num_rows()>0){
                                            $tgl_sudah = $cek1->row("tgl_potong");
                                            $ror_error[]="Kode sudah digunakan ($tgl_sudah)";
                                        }
                                        if($cek2->num_rows()>0){
                                            $tgl_sudah = $cek2->row("tgl");
                                            $ror_error[]="Kode sudah digunakan ($tgl_sudah)";
                                        }
                                        $cek3 = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kdrol]);
                                        if($cek3->num_rows() == 1){
                                            $konstruksi_awal = $cek3->row("konstruksi");
                                            $konstruksi_skrg = $val->kons;
                                            if($konstruksi_awal == $konstruksi_skrg){

                                            } else {
                                                $ror_error[]="Konstruksi awal kode ini adalah ".$konstruksi_awal."";
                                            }
                                        }
                                        if(count($ror_error) > 0){
                                            for ($i=0; $i < count($ror_error); $i++) { 
                                                echo "<span style='color:red;'>".$ror_error[$i]."</span><br>";
                                            }
                                        } else {
                                            echo '<i class="icon-copy bi bi-check-circle-fill" style="color:green;"></i>';
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php
                            endforeach;
                            ?>
                            <tr>
                                <td colspan="11">
                                    <ol>
                                        <li>1. Jumlah baris di dalam excel adalah <strong><?=$alldata->num_rows();?></strong> baris</li>
                                        <li>2. Jumlah baris yang akan di proses adalah <strong><?=$alldata2->num_rows();?></strong> baris</li>
                                    </ol>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>Nama Operator</td>
                                            <?php foreach($ar_kons as $kons): echo "<td><strong>$kons</strong></td>"; endforeach;?>
                                            <td>Total Operator</td>
                                        </tr>
                                        <?php foreach($ar_oprt as $opt): 
                                        ?>
                                        <tr>
                                            <td><?=$opt;?></td>
                                            <?php $jml_per_opt = 0;
                                            foreach($ar_kons as $kons):
                                                $jum_perkons = $this->db->query("SELECT SUM(panjang) AS ukr FROM temp_upload_if2 WHERE operator='$opt' AND kons='$kons' AND kodeauto='$kode'")->row("ukr");
                                                //echo "<td>$jum_perkons</td>";
                                                $jml_per_opt += $jum_perkons;
                                                if(fmod($jum_perkons, 1) !== 0.00){
                                                    echo "<td>". number_format($jum_perkons,2,',','.')." </td>";
                                                } else {
                                                    echo "<td>". number_format($jum_perkons,0,',','.')." </td>";
                                                }
                                             endforeach;?>
                                            <td>
                                                <?php
                                                if(fmod($jml_per_opt, 1) !== 0.00){
                                                    echo "". number_format($jml_per_opt,2,',','.')."";
                                                } else {
                                                    echo "". number_format($jml_per_opt,0,',','.')."";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        endforeach;?>
                                        <tr>
                                            <td><strong>Total Per Konstruksi</strong></td>
                                            <?php $total_hasil_ins = 0; 
                                            foreach($ar_kons as $kons):
                                                 $jum_perkons = $this->db->query("SELECT SUM(panjang) AS ukr FROM temp_upload_if2 WHERE kons='$kons' AND kodeauto='$kode'")->row("ukr");
                                                 $bs_perkons = $this->db->query("SELECT SUM(bs) AS ukr FROM temp_upload_if2 WHERE kons='$kons' AND kodeauto='$kode'")->row("ukr");
                                                 $bp_perkons = $this->db->query("SELECT SUM(bp) AS ukr FROM temp_upload_if2 WHERE kons='$kons' AND kodeauto='$kode'")->row("ukr");
                                                 $crt_perkons = $this->db->query("SELECT SUM(crt) AS ukr FROM temp_upload_if2 WHERE kons='$kons' AND kodeauto='$kode'")->row("ukr");
                                                 $total_hasil_ins+=$jum_perkons;
                                                 if(fmod($jum_perkons, 1) !== 0.00){
                                                    echo "<td>". number_format($jum_perkons,2,',','.')." </td>";
                                                 } else {
                                                    echo "<td>". number_format($jum_perkons,0,',','.')." </td>";
                                                 }
                                                 $cek2 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kons, 'tgl'=>$setTgl, 'dep'=>'Samatex']);
                                                 if($cek2->num_rows() == 0){
                                                    $dtlist = [
                                                        'kode_konstruksi' => $kons,
                                                        'tgl' => $setTgl,
                                                        'dep' => 'Samatex',
                                                        'prod_ig' => 0,
                                                        'prod_fg' => 0,
                                                        'prod_if' => round($jum_perkons,2),
                                                        'prod_ff' => 0,
                                                        'prod_bs1' => 0,
                                                        'prod_bp1' => 0,
                                                        'prod_bs2' => round($bs_perkons,2),
                                                        'prod_bp2' => round($bp_perkons,2),
                                                        'crt' => round($crt_perkons,2)
                                                    ];
                                                    $this->data_model->saved('data_produksi', $dtlist);
                                                 } else {
                                                    $id = $cek2->row("id_produksi");
                                                    $newif = floatval($cek2->row("prod_if")) + $jum_perkons;
                                                    $newbs = floatval($cek2->row("prod_bs2")) + $bs_perkons;
                                                    $newbp = floatval($cek2->row("prod_bp2")) + $bp_perkons;
                                                    $newcrt = floatval($cek2->row("crt")) + $crt_perkons;
                                                    $dtlist = [
                                                        'prod_if' => round($newif, 2),
                                                        'prod_bs2' => round($newbs, 2),
                                                        'prod_bp2' => round($newbp, 2),
                                                        'crt' => round($newcrt, 2)
                                                    ];
                                                    $this->data_model->updatedata('id_produksi', $id, 'data_produksi', $dtlist);
                                                 }
                                            endforeach;?>
                                            <td>
                                                <?php
                                                 if(fmod($total_hasil_ins, 1) !== 0.00){
                                                    echo "". number_format($total_hasil_ins,2,',','.')." ";
                                                 } else {
                                                    echo "". number_format($total_hasil_ins,0,',','.')." ";
                                                 }

                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                        <?php
                            $ar_tgl = array();
                            foreach($alldata2->result() as $val){
                                $ar_tgl[]=$val->tgl;
                                if($val->ada_digrey == "y"){
                                    $dtlist = [
                                        'kode_roll' => $val->kode_roll,
                                        'tgl_potong' => $val->tgl,
                                        'ukuran_ori' => $val->panjang,
                                        'ukuran_bs' => $val->bs,
                                        'ukuran_crt' => $val->crt,
                                        'ukuran_bp' => $val->bp,
                                        'operator' => $val->operator,
                                        'ket' => $val->ket,
                                        'asal' => 0,
                                        'bp_canjoin' => $val->panjang < 50 ? 'true':'false',
                                        'konstruksi' => $val->kons
                                    ];
                                    $this->data_model->saved('data_if', $dtlist);
                                }
                                if($val->ada_digrey == "n"){
                                    $dtlist = [
                                        'kode_roll' => $val->kode_roll,
                                        'tgl' => $val->tgl,
                                        'panjang' => $val->panjang,
                                        'bs' => $val->bs,
                                        'crt' => $val->crt,
                                        'bp' => $val->bp,
                                        'satuan' => 'Yard',
                                        'operator' => $val->operator,
                                        'ket' => $val->ket,
                                        'kodekons' => $val->kons,
                                        'posisi' => 'Samatex',
                                        'panjang_now' => $val->panjang,
                                        'bp_canjoin' => $val->panjang < 50 ? 'true':'false'
                                    ];
                                    $this->data_model->saved('data_if_lama', $dtlist);
                                }
                            }
                            $setTgl = $ar_tgl[0];
                                $cek1 = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$setTgl, 'dep'=>'Samatex']);
                                if($cek1->num_rows() == 0){
                                    $dtlist = [
                                        'tgl' => $setTgl,
                                        'dep' => 'Samatex',
                                        'prod_ig' => 0,
                                        'prod_fg' => 0,
                                        'prod_if' => round($total_hasil_ins,2),
                                        'prod_ff' => 0,
                                        'prod_bs1' => 0,
                                        'prod_bp1' => 0,
                                        'prod_bs2' => round($total_bs,2),
                                        'prod_bp2' => round($total_bp,2),
                                        'crt' => round($total_crt,2)
                                    ];
                                    $this->data_model->saved('data_produksi_harian', $dtlist);
                                } else {
                                    $id = $cek1->row("id_prod_hr");
                                    $newif = floatval($cek1->row("prod_if")) + $total_hasil_ins;
                                    $newbs = floatval($cek1->row("prod_bs2")) + $total_bs;
                                    $newbp = floatval($cek1->row("prod_bp2")) + $total_bp;
                                    $newcrt = floatval($cek1->row("crt")) + $total_crt;
                                    $dtlist = [
                                        'prod_if' => round($newif, 2),
                                        'prod_bs2' => round($newbs, 2),
                                        'prod_bp2' => round($newbp, 2),
                                        'crt' => round($newcrt, 2)
                                    ];
                                    $this->data_model->updatedata('id_prod_hr', $id, 'data_produksi_harian', $dtlist);
                                }                        
                                

                                //--
                                foreach($ar_kons as $kons):
                                    $jum_perkons = $this->db->query("SELECT SUM(panjang) AS ukr FROM temp_upload_if2 WHERE kons='$kons' AND kodeauto='$kode' AND ada_digrey='y'")->row("ukr");
                                    $bs_perkons = $this->db->query("SELECT SUM(bs) AS ukr FROM temp_upload_if2 WHERE kons='$kons' AND kodeauto='$kode' AND ada_digrey='y'")->row("ukr");
                                    $bp_perkons = $this->db->query("SELECT SUM(bp) AS ukr FROM temp_upload_if2 WHERE kons='$kons' AND kodeauto='$kode' AND ada_digrey='y'")->row("ukr");
                                    $crt_perkons = $this->db->query("SELECT SUM(crt) AS ukr FROM temp_upload_if2 WHERE kons='$kons' AND kodeauto='$kode' AND ada_digrey='y'")->row("ukr");
                                    
                                    
                                    $cek4 = $this->data_model->get_byid('data_stok', ['kode_konstruksi'=>$kons, 'tgl'=>$setTgl, 'dep'=>'Samatex']);
                                     
                               endforeach;
                                //-tambahan
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

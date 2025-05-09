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
                                <td colspan="3"><strong>Total</strong></td>
                                <td id="totalukuranid"></td>
                                <td><?=$total_bs;?></td>
                                <td><?=$total_crt;?></td>
                                <td><?=$total_bp;?></td>
                                <td colspan="4"></td>
                            </tr>
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
                                                 $total_hasil_ins+=$jum_perkons;
                                                 if(fmod($jum_perkons, 1) !== 0.00){
                                                    echo "<td>". number_format($jum_perkons,2,',','.')." </td>";
                                                 } else {
                                                    echo "<td>". number_format($jum_perkons,0,',','.')." </td>";
                                                 }
                                            endforeach;?>
                                            <td>
                                                <?php
                                                 if(fmod($total_hasil_ins, 1) !== 0.00){
                                                    echo "". number_format($total_hasil_ins,2,',','.')." ";
                                                    //echo "<script>document.getElementById('totalukuranid').innerHTML = ''+".number_format($total_hasil_ins,2,',','.').";</script>";
                                                 } else {
                                                    echo "". number_format($total_hasil_ins,0,',','.')." ";
                                                    //echo "<script>document.getElementById('totalukuranid').innerHTML = ''+".number_format($total_hasil_ins,0,',','.').";</script>";
                                                 }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <p style="width:100%;text-align:right;">
                                        <a href="<?=base_url('resume/deleteinsfinish/'.$kode);?>"><button class="btn btn-danger">HAPUS</button></a>
                                        <a href="<?=base_url('resume/savedinsfinish/'.$kode);?>"><button class="btn btn-success">SIMPAN PROSES</button></a>
                                    </p>
                                </td>
                            </tr>
                            
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

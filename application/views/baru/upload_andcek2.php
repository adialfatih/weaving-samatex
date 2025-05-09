<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', '00' => 'Undefined' ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="title">
							<h4>Surat Jalan <?=$dep_user=="Samatex" ? "Samatex" : "Rindang Jati";?></h4>
							<p>Menampilkan data berdasarkan surat jalan nomor <strong><?=$uri;?></strong></p>
					    </div>
							
						<div class="body">
						    <hr>
							<?php
                            $crpkg = $this->db->query("SELECT * FROM new_tb_packinglist WHERE no_sj='$uri'");
                            foreach($crpkg->result() as $no => $val):
                            $num = $no+1;
                            $_kd = $val->kd;
                            $cek_kd = $this->data_model->get_byid('new_tb_isi_lock', ['kd'=>$_kd]);
                            if($cek_kd > 0){ 
                            $__jml_roll = $this->db->query("SELECT COUNT(kd) AS jml FROM new_tb_isi_lock WHERE kd='$_kd'")->row("jml");
                            $__jml_tttl = $this->db->query("SELECT SUM(ukuran) AS jml FROM new_tb_isi_lock WHERE kd='$_kd'")->row("jml");
                            ?>
                            <table class="table table-bordered">
                                <tr>
                                    <td style="background:#dedede;color:#3d3d3d;"><?=$num;?>. Kode Packing List : <a href="<?=base_url('cetakrjs/packinglist/'.$_kd.'/1');?>" style="color:#1640b5;" target="_blank"><strong><?=$_kd;?></strong> ( <strong><?=$val->kode_konstruksi;?></strong> )</a> Jumlah Roll : ( <strong><?=$__jml_roll;?></strong> / <strong><?=number_format($__jml_tttl);?></strong> )</td>
                                    
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>No.</td>
                                                <td>Kode Roll</td>
                                                <td>No Beam</td>
                                                <td>Ukuran</td>
                                            </tr>
                                            <?php
                                            $isi_pkg = $this->db->query("SELECT kd,kode FROM new_tb_isi WHERE kd='$_kd'");
                                            $rtrow=1;
                                            $totaluk=0;
                                            foreach($isi_pkg->result() as $op){
                                                $_tes = $this->db->query("SELECT kode_roll,no_beam,ukuran_ori FROM data_ig WHERE kode_roll='$op->kode'")->row_array();
                                                echo "<tr>";
                                                echo "<td>$rtrow</td>";
                                                echo "<td>$op->kode</td>";
                                                echo "<td>".$_tes['no_beam']."</td>";
                                                echo "<td>".$_tes['ukuran_ori']."</td>";
                                                echo "</tr>";
                                                $totaluk+=$_tes['ukuran_ori'];
                                                $rtrow++;
                                                $cekdtoke = $this->data_model->get_byid('new_tb_isi_lock', ['kd'=>$_kd,'kode'=>$op->kode]);
                                                if($cekdtoke->num_rows() == 0){
                                                $this->data_model->saved('new_tb_isi_lock', [
                                                    'kd' => $_kd,
                                                    'konstruksi' => $val->kode_konstruksi,
                                                    'siap_jual' => 'n',
                                                    'kode' => $op->kode,
                                                    'ukuran' => $_tes['ukuran_ori'],
                                                    'status' => 'fixsend',
                                                    'satuan' => 'Meter',
                                                    'validasi' => 'null',
                                                    'nobeam' => $_tes['no_beam']
                                                ]);
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <td colspan="3">Total</td>
                                                <td>
                                                    <?php
                                                    if(fmod($totaluk, 1) !== 0.00){
                                                        echo number_format($totaluk,2,',','.');
                                                    } else {
                                                        echo number_format($totaluk,0,',','.');
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <?php
                            } else {
                            if(fmod($val->ttl_panjang, 1) !== 0.00){
                                $_ttl_panjang = number_format($val->ttl_panjang,2,',','.');
                            } else {
                                $_ttl_panjang = number_format($val->ttl_panjang,0,',','.');
                            }
                            ?>
                            <table class="table table-bordered">
                                <tr>
                                    <td style="background:#dedede;color:#3d3d3d;"><?=$num;?>. Kode Packing List : <a href="<?=base_url('cetakrjs/packinglist/'.$_kd.'/1');?>" style="color:#1640b5;" target="_blank"><strong><?=$_kd;?></strong> ( <strong><?=$val->kode_konstruksi;?></strong> )</a> Jumlah Roll : ( <strong><?=$val->jumlah_roll;?></strong> / <strong><?=$_ttl_panjang;?></strong> )</td>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>No.</td>
                                                <td>Kode Roll</td>
                                                <td>No Beam</td>
                                                <td>Ukuran</td>
                                            </tr>
                                            <?php
                                            $isi_pkg = $this->db->query("SELECT kd,kode FROM new_tb_isi WHERE kd='$_kd'");
                                            $rtrow=1;
                                            $totaluk=0;
                                            foreach($isi_pkg->result() as $op){
                                                $_tes = $this->db->query("SELECT kode_roll,no_beam,ukuran_ori FROM data_ig WHERE kode_roll='$op->kode'")->row_array();
                                                echo "<tr>";
                                                echo "<td>$rtrow</td>";
                                                echo "<td>$op->kode</td>";
                                                echo "<td>".$_tes['no_beam']."</td>";
                                                echo "<td>".$_tes['ukuran_ori']."</td>";
                                                echo "</tr>";
                                                $totaluk+=$_tes['ukuran_ori'];
                                                $rtrow++;
                                                $cekdtoke = $this->data_model->get_byid('new_tb_isi_lock', ['kd'=>$_kd,'kode'=>$op->kode]);
                                                if($cekdtoke->num_rows() == 0){
                                                $this->data_model->saved('new_tb_isi_lock', [
                                                    'kd' => $_kd,
                                                    'konstruksi' => $val->kode_konstruksi,
                                                    'siap_jual' => 'n',
                                                    'kode' => $op->kode,
                                                    'ukuran' => $_tes['ukuran_ori'],
                                                    'status' => 'fixsend',
                                                    'satuan' => 'Meter',
                                                    'validasi' => 'null',
                                                    'nobeam' => $_tes['no_beam']
                                                ]);
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <td colspan="3">Total</td>
                                                <td>
                                                    <?php
                                                    if(fmod($totaluk, 1) !== 0.00){
                                                        echo number_format($totaluk,2,',','.');
                                                    } else {
                                                        echo number_format($totaluk,0,',','.');
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <?php }
                                endforeach;
                            ?>
						</div>
                        
					</div>
					<!-- Simple Datatable start -->
					<!-- Bootstrap Select End -->
					
					
        </div>
    </div>
</div>

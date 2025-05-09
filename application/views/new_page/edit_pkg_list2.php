<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header d-print-none">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data Packinglist</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Data</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Packinglist</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Edit
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<?php 
                    $ex = explode('-', $txt);
                    $dtrow = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kdlist])->row_array();
                    $tg = explode('-', $dtrow['tanggal_dibuat']);
                    $printTgl = $tg[2]." ".$bln[$tg[1]]." ".$tg[0];
                    ?>
					<!-- Simple Datatable start -->
                    <div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								<strong>Kode Packinglist (<?=$kdlist;?>)</strong>
							</p><small>Packinglist Konstruksi (<strong><?=$dtrow['kode_konstruksi'];?></strong>) di buat tanggal <strong><?=$printTgl;?></strong></small>
						</div>
						<div class="pd-20 card-box mb-30">
                            <form action="<?=base_url('addroll/proses');?>" method="post">
                            <input type="hidden" value="<?=$kdlist;?>" name="kodelist">
                            <div class="clearfix">
                                <table class="table">
                                    <tr>
                                        <td>No.</td>
                                        <td>Kode Roll</td>
                                        <td>Ukuran</td>
                                        <td>Folding</td>
                                        <td>Konstruksi</td>
                                        <td>Status</td>
                                        <td></td>
                                    </tr>
                                    <?php 
                                    $totalukuran = 0;
                                    
                                    foreach ($ex as $key => $value) { $kdroll = $value;
                                    
                                    $uniqueData = array_unique($ex);
                                    $qry = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kdroll]);
                                    if($qry->num_rows() == 1){
                                        $from = 1;
                                        $ukuran = $qry->row("ukuran");
                                        $konstruksi = $qry->row("konstruksi");
                                        echo "<tr>";
                                        $no = $key+1;
                                        echo "<td>".$no."</td>";
                                        if (array_search($value, $uniqueData) === $key) {
                                            // Data pertama, tampilkan dengan warna hitam
                                            //7echo '<span style="color: black;">' . $value . '</span>';
                                            echo "<td style='color: black;'>".$kdroll."</td>";
                                        } else {
                                            // Data berulang, tampilkan dengan warna merah
                                            //echo '<span style="color: red;">' . $value . '</span>';
                                            echo "<td style='color: red;'>".$kdroll."</td>";
                                        }
                                        
                                        echo "<td>".$ukuran."</td>";
                                        if($qry->row("jns_fold")=="Grey"){ echo "<td>Grey</td>"; } else { echo "<td>Finish</td>"; }
                                        if($dtrow['kode_konstruksi'] == $konstruksi){
                                        echo "<td>".$konstruksi."</td>";
                                        
                                        } else {
                                        echo "<td><code>".$konstruksi."</code></td>";
                                        }
                                        if($qry->row("posisi")=="Samatex"){
                                        echo "<td>(".$from.")</td>";
                                        } else {
                                        echo "<td>(".$from.") <code>On Package</code></td>";
                                        }
                                        echo "<td>";
                                        if($qry->row("posisi")=="Samatex"){ 
                                        if (array_search($value, $uniqueData) === $key) {    
                                            $totalukuran+=$ukuran;
                                        ?>
                                        <input type="checkbox" value="<?=$kdroll;?>" name="kdroll[]" id="ckbox<?=$key;?>" style="width:18px;height:18px;" checked>
                                        <input type="hidden" value="<?=$from;?>" name="from[]">
                                        <?php
                                        } else {

                                        }
                                        ?>
                                        
                                        <?php } else { ?>

                                        <?php }
                                        echo "</td>";
                                        echo "</tr>";
                                        
                                    } elseif($qry->num_rows() == 0) {
                                        $qry2 = $this->data_model->get_byid('data_fol_lama', ['kode_roll'=>$kdroll]);
                                        if($qry2->num_rows() == 1){
                                            $from = 2;
                                            $ukuran = $qry2->row("ukuran_now");
                                            $konstruksi = $qry2->row("konstruksi");
                                            echo "<tr>";
                                            $no = $key+1;
                                            echo "<td>".$no."</td>";
                                            if (array_search($value, $uniqueData) === $key) {
                                                // Data pertama, tampilkan dengan warna hitam
                                                //7echo '<span style="color: black;">' . $value . '</span>';
                                                echo "<td style='color: black;'>".$kdroll."</td>";
                                            } else {
                                                // Data berulang, tampilkan dengan warna merah
                                                //echo '<span style="color: red;">' . $value . '</span>';
                                                echo "<td style='color: red;'>".$kdroll."</td>";
                                            }
                                            echo "<td>".$ukuran."</td>";
                                            if($qry2->row("folding")=="Grey"){ echo "<td>Grey</td>"; } else { echo "<td>Finish</td>"; }
                                            if($dtrow['kode_konstruksi'] == $konstruksi){
                                                echo "<td>".$konstruksi."</td>";
                                                
                                            } else {
                                                echo "<td><code>".$konstruksi."</code></td>";
                                            }
                                            if($qry2->row("lokasi") == "Samatex"){ echo "<td>(".$from.")</td>"; } else { echo "<td>(".$from.") <code>On Package</code></td>"; }
                                            
                                            echo "<td>";
                                            if($qry2->row("lokasi")=="Samatex"){ 
                                            if (array_search($value, $uniqueData) === $key) {
                                                $totalukuran+=$ukuran;
                                            ?>
                                            <input type="checkbox" value="<?=$kdroll;?>" name="kdroll[]" id="ckbox<?=$key;?>" style="width:18px;height:18px;" checked>
                                            <input type="hidden" value="<?=$from;?>" name="from[]">
                                            <?php
                                            } else {

                                            }
                                            ?>
                                            
                                            <?php } else { ?>
                                                
                                            <?php }
                                            echo "</td>";
                                            echo "</tr>";
                                        } else {
                                            echo "<tr><td colspan='7'><code>".$kdroll." tidak di temukan</code></td></tr>";
                                        }
                                    }
                                    
                                    ?>
                                    <?php } ?>
                                    <tr>
                                        <td></td>
                                        <td><strong>Total Panjang</strong></td>
                                        <td>
                                            <?php
                                                if(fmod($totalukuran, 1) !== 0.00){
                                                    $ttl = number_format($totalukuran,2,',','.');
                                                } else {
                                                    $ttl = number_format($totalukuran,0,',','.');
                                                }
                                                echo "".$ttl; 
                                            ?>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </table>
                                <button type="submit" class="btn btn-success">Simpan Roll ke Paket</button>
                            </div>
                            </form>
						</div>
					</div>
					<!-- Bootstrap Select End -->
					
                                
                    
					
        </div>
    </div>
</div>
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
					
					<!-- Simple Datatable start -->
                    <div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								<strong>Kode Packinglist (<strong><?=$token;?></strong>)</strong>
							</p>
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                                <form action="<?=base_url('proses/tambahkanroll2');?>" method="post">
                                <input type="hidden" name="token" value="<?=$token;?>">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Kode Roll</td>
                                        <td>Konstruksi</td>
                                        <td>Ukuran</td>
                                        <td>Pilih</td>
                                    </tr>
                                    <?php
                                    $cektoken = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$token])->row_array();
                                    $kons_pkg = $cektoken['kode_konstruksi'];
                                    $ex = explode('-', $exdata);
                                    $total_ukuran=0; $jml_roll=0;
                                    foreach ($ex as $key => $value) {
                                        $cek = $this->data_model->get_byid('data_ig', ['kode_roll'=>$value]);
                                        if($cek->num_rows() == 1){
                                    ?>
                                    <tr>
                                        <td><?=$value;?></td>
                                        <td>
                                            <?php
                                            if($cek->row("konstruksi") != $kons_pkg){
                                                echo "<span style='color:red;'>".$cek->row("konstruksi")."";
                                            } else {
                                                echo $cek->row("konstruksi");
                                            }
                                            ?>
                                        </td>
                                        <td><?=$cek->row("ukuran_ori");?></td>
                                        <td>
                                            <?php
                                            if($cek->row("konstruksi") == $kons_pkg){
                                                $total_ukuran+=$cek->row("ukuran_ori");
                                                $jml_roll+=1;
                                            ?>
                                            <!-- <input type="checkbox" value="<=$value;?>" name="koderoll[]" style="width:20px;height:20px;cursor:pointer;" checked disabled> -->
                                            <input type="text" value="<?=$value;?>" name="kode[]">
                                            <i class="icon-copy bi bi-check-circle-fill" style="color:green;"></i>
                                            <?php
                                            } else {echo '<i class="icon-copy bi bi-info-circle-fill" style="color:red;"></i>';}
                                            ?>
                                        </td>
                                    </tr>
                                        <?php } else { 
                                            echo "<tr><td colspan='4'><code>Code ".$value." tidak ditemukan</code></td></tr>";
                                        }
                                    } //end foreach
                                    ?>
                                    <tr>
                                        <td colspan="2">Total Ukuran yang bisa ditambahkan</td>
                                        <td><?=number_format($total_ukuran);?></td>
                                        <td><?=$jml_roll;?> Roll</td>
                                        <input type="hidden" name="jmlroll" value="<?=$jml_roll;?>">
                                        <input type="hidden" name="panjangroll" value="<?=$total_ukuran;?>">
                                    </tr>
                                </table>
                                <button type="submit" class="btn btn-primary">Simpan ke Packinglist</button>
                                </form>
                            </div>
						</div>
					</div>
					<!-- Bootstrap Select End -->
					<!-- pemisah modal -->
                                
        </div>
    </div>
</div>
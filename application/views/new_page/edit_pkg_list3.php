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
                            <form action="<?=base_url('addroll/proses2');?>" method="post">
                            <input type="hidden" value="<?=$kdlist;?>" name="kodelist">
                            <div class="clearfix">
                                <table class="table">
                                    <tr>
                                        <td>No.</td>
                                        <td>Ukuran</td>
                                        <td>Folding</td>
                                        <td>Status</td>
                                        <td></td>
                                    </tr>
                                    
                                    <?php 
                                    $kons_asli = $dtrow['kode_konstruksi'];
                                    $cek_kons = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kons_asli]);
                                    if($cek_kons->num_rows() == 1){} else {
                                        $cek_kons2 = $this->data_model->get_byid('tb_konstruksi', ['chto'=>$kons_asli]);
                                        $kons_asli = $cek_kons2->row("kode_konstruksi");
                                    }
                                    $total_ukuran_terpilih=0;
                                    
                                    foreach ($ex as $key => $value) {
                                    $ukuran = $value;
                                    $uniqueData = array_unique($ex);
                                    $cek_roll = $this->data_model->get_byid('data_stok_lama', ['konstruksi'=>$kons_asli, 'ukuran'=>$ukuran, 'posisi'=>'Samatex']);
                                    if($cek_roll->num_rows() < 1){
                                        $no = $key+1;
                                        echo "<tr><td>".$no."</td><td colspan='2'>Ukuran <strong>".$ukuran."</strong> dengan kode konstruksi <strong>".$kons_asli."</strong> tidak ditemukan</td><td></td></tr>";
                                        
                                        
                                    } else {
                                        if($cek_roll->num_rows() > 1){
                                            $dtukr = $this->db->query("SELECT * FROM data_stok_lama WHERE konstruksi='$kons_asli' AND ukuran='$ukuran' AND posisi='Samatex' LIMIT 1")->row_array();
                                        } else {
                                            $dtukr = $cek_roll->row_array();
                                        }
                                        
                                        $total_ukuran_terpilih+=$ukuran;
                                        ?>
                                        <tr>
                                            <td><?=$key+1;?></td>
                                        <?php
                                        if (array_search($value, $uniqueData) === $key) {
                                            echo "<td style='color: black;'>".$ukuran."</td>";
                                        } else {
                                            echo "<td style='color: black;'>".$ukuran."</td>";
                                        }
                                        ?>
                                            
                                            <td><?=$dtukr['st_folding'];?></td>
                                            <td>
                                                <input type="checkbox" style="width:18px;height:18px;cursor:pointer;" value="<?=$dtukr['id_sl'];?>" name="roll[]" onclick="cekup('<?=$key;?>','<?=$ukuran;?>')" checked>
                                                <input type="hidden" name="cekidn[]" value="1" id="valid<?=$key;?>">
                                                <input type="hidden" name="idasli[]" value="<?=$dtukr['id_sl'];?>">
                                                <input type="hidden" name="ukurans[]" value="<?=$ukuran;?>">
                                            </td>
                                        </tr>
                                        <?php
                                        $this->data_model->updatedata('id_sl',$dtukr['id_sl'],'data_stok_lama',['posisi'=>$kdlist]);
                                    }
                                    ?>
                                    
                                    <?php } ?>
                                    <tr>
                                        <td></td>
                                        <td colspan="3">Total  : <strong id="idtotalUkuran"><?=$total_ukuran_terpilih;?></strong>
                                            <input type="hidden" id="idTotalUkuranTxt" value="<?=$total_ukuran_terpilih;?>">
                                        </td>
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
<script>
    function cekup(n, ukuran){
        var num = document.getElementById('valid'+n+'').value;
        var total = document.getElementById('idTotalUkuranTxt').value;
        if(num == 1){
            document.getElementById('valid'+n+'').value = '0';
            var newtotal = parseFloat(total) - parseFloat(ukuran);
        } else {
            document.getElementById('valid'+n+'').value = '1';
            var newtotal = parseFloat(total) + parseFloat(ukuran);
        }
        document.getElementById('idTotalUkuranTxt').value = ''+newtotal;
        document.getElementById('idtotalUkuran').innerHTML = ''+newtotal;
    }
</script>
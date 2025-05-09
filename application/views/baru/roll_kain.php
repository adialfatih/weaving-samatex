<?php $arb = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar','04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des']; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data Stok Gudang</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Data</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Stok Gudang</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Roll Kain
										</li>
									</ol>
								</nav>
							</div>
							
						</div>
					</div><form action="<?=base_url('find-code');?>" method="post" name="fr12">
                    <div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12" style="display:flex;">
								<input type="text" class="form-control" placeholder="Cari Kode Roll, Ex: S451,S452,R156,SA123" name="kode" required>
                                <button class="btn btn-primary" style="margin-left:10px;">Submit</button>
							</div>
						</div>
					</div></form>
                    <form action="<?=base_url('phpsq2023/hasfolex');?>" method="post" name="fr122">
                    <div class="page-header">
						<div class="row">
                            <p><strong>Cek Hasil Folding</strong></p>
							<div class="col-md-6 col-sm-12" style="display:flex;">
                            <select name="fol" id="fol" class="form-control">
                                <option value="" selected>--Pilih Folding--</option>
                                <option value="Grey">Folding Grey</option>
                                <option value="Finish">Folding Finish</option>
                            </select>
								<input type="text" class="form-control" name="datesr" required>
                                <button class="btn btn-primary" style="margin-left:10px;">Submit</button>
							</div>
						</div>
					</div></form>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
							<strong>Stok Gudang</strong><br>
                            <small>Hanya menampilkan 1000 roll kain terbaru</small>
							</p>
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
									<th class="table-plus datatable-nosort">No</th>
										<th>Kode Roll</th>
                                        <th>Konstruksi</th>
										<th>Inspect Grey</th>
										<th>Folding Grey</th>
										<th>Inspect Finish</th>
										<th>Folding Finish</th>
									</tr>
								</thead>
								<tbody>
                                    <?php
                                        $number =1 ;
                                        foreach($dbdata->result() as $n => $val):
                                        $kdroll = $val->kode_roll;
                                        $ex = explode('-',$val->tanggal);
                                        $printTgl = $ex[2]." ".$arb[$ex[1]]." ".$ex[0];
                                        $cek_folgrey = $this->data_model->get_byid('data_fol',['kode_roll'=>$kdroll]);
                                        if($cek_folgrey->num_rows()==1){
                                            $folding = $cek_folgrey->row("ukuran");
                                            $satuan = $cek_folgrey->row("jns_fold")=='Grey' ?'Meter':'Yard';
                                            $po = explode('-',$cek_folgrey->row("tgl"));
                                            $foltgl = $po[2]." ".$arb[$po[1]]." ".$po[0]; 
                                            $susut1 = floatval($folding) - floatval($val->ukuran_ori);
                                        } else {
                                            $folding = 0; 
                                            $susut1 = 0;
                                        }
                                        $cek_insf = $this->data_model->get_byid('data_if',['kode_roll'=>$kdroll]);
                                        if($cek_insf->num_rows()==1){
                                            $tf = explode("-",$cek_insf->row("tgl_potong"));
                                            $tglif = $tf[2]." ".$arb[$tf[1]]." ".$tf[0];
                                            $nilaiMeter = floatval($cek_insf->row("ukuran_ori")) / 0.9144;
                                            $susut2 = floatval($nilaiMeter) - floatval($val->ukuran_ori);
                                            $susut2Print = round($susut2, 2);
                                            $insf = $cek_insf->row("ukuran_ori")." Yard <small>(".$tglif.") (susut $susut2Print meter)</small>";
                                            
                                        } else {
                                            $insf = "-";
                                        }
                                        $cek_outside = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kode_roll='$kdroll'")->num_rows();
                                        if($cek_outside == 0){
                                    ?>
                                    <tr>
                                        <td><?=$number;?></td>
                                        <td><?=$kdroll;?></td>
                                        <td><?=$val->konstruksi;?></td>
                                        <td><?=$val->ukuran_ori;?> Meter <small>(<?=$printTgl;?>)</small></td>
                                        <td>
                                            <?php if($folding==0){ echo "-"; } else {
                                                if($satuan=="Meter"){
                                                    echo "$folding $satuan <small>($foltgl)</small>";
                                                    if($susut1!=0){ echo "<small>(susut $susut1)</samll>"; }
                                                } else {
                                                    echo "-";
                                                }
                                            } ?>
                                        </td>
                                        <td><?=$insf;?></td>
                                        <td>
                                            <?php if($folding==0){ echo "-"; } else {
                                                if($satuan=="Yard"){
                                                    echo "$folding $satuan <small>($foltgl)</small>";
                                                    $susut3 = floatval($folding) - floatval($cek_insf->row("ukuran_ori"));
                                                    echo "<small>(susut $susut3 yard)</samll>";
                                                } else {
                                                    echo "-";
                                                }
                                            } ?>
                                        </td>
                                    </tr>
                                    <?php $number++;
                                            } else {

                                          }
                                        endforeach; ?>
								</tbody>
							</table>
						</div>
						
					</div>
					<!-- Simple Datatable End -->
					
					
					
        </div>
    </div>
</div>
<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
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
											<a href="javascript:void(0);">Create</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Packinglist</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
                                            Insert Roll
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
								<strong>NO PAKET (<strong><?=$no_paket;?></strong>) KODE KONSTRUKSI (<?=$kd_kons;?>)</strong>
							</p><small>Pilih Kode Roll yang akan anda masukan dalam paket (<strong><?=$no_paket;?></strong>).</small>
						</div>
						<div class="pd-20 card-box mb-30">
                            <?php if($dep_user!="Samatex"){ ?>
                            <form action="<?=base_url('newpros/insert_pkg');?>" method="post" entype="multipart/form-data">
                            <?php } ?>
                            <div class="clearfix">
                                <table class="data-table table stripe hover nowrap">
                                    <thead>
                                        
                                        <tr>
                                            <th class="table-plus datatable-nosort">No</th>
                                            <th>Kode Konstruksi</th>
                                            <th>Kode Roll</th>
                                            <?php if($jns_paket=="y"){ echo"<th>Ukuran Folding</th>"; } else { echo "<th>Inspect Grey (Meter)</th>"; } ?>
                                            <th class="datatable-nosort">Pilih</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if($jns_paket=="y"){
                                            //$dtroll = $this->data_model->get_byid('data_fol', ['konstruksi'=>$kd_kons]);
                                            $searchKons = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kd_kons]);
                                            if($searchKons->num_rows() == 1){
                                                $dtroll = $this->db->query("SELECT * FROM data_fol WHERE konstruksi='$kd_kons' AND jns_fold='Grey' AND posisi='Samatex'");
                                                $tabelKonst = $kd_kons;
                                            } else {
                                                $searchKons2 = $this->data_model->get_byid('tb_konstruksi', ['chto'=>$kd_kons])->row("kode_konstruksi");
                                                $dtroll = $this->db->query("SELECT * FROM data_fol WHERE konstruksi='$searchKons2' AND jns_fold='Finish' AND posisi='Samatex'");
                                                $tabelKonst = $searchKons2;
                                            }
                                        } else {
                                            //$dtroll = $this->data_model->get_byid('data_ig', ['konstruksi'=>$kd_kons]);
                                            $dtroll = $this->db->query("SELECT * FROM data_ig WHERE konstruksi='$kd_kons' AND loc_now='$dep_user'");
                                        }
                                        //echo $dep_user;
                                        foreach($dtroll->result() as $n => $val):
                                        ?>
                                        <tr>
                                            <td><?=$n+1;?></td>
                                            <td><?=$kd_kons;?></td>
                                            <td><?=$val->kode_roll;?></td>
                                            <?php 
                                                if($jns_paket=="y"){
                                                    echo "<td>";
                                                    if(fmod($val->ukuran, 1) !== 0.00){
                                                        $ukuran = number_format($val->ukuran,2,',','.');
                                                    } else {
                                                        $ukuran = number_format($val->ukuran,0,',','.');
                                                    }
                                                    echo $ukuran;
                                                    if($val->jns_fold=="Finish"){ echo " Yard"; $s_satuan="Yard"; } else { echo " Meter"; $s_satuan="Meter"; }
                                                    echo "</td>";
                                                    $hitung_ukuran = $val->ukuran;
											
                                                } else {
                                                    echo "<td>";
                                                    if(fmod($val->ukuran_ori, 1) !== 0.00){
                                                        $ukuran = number_format($val->ukuran_ori,2,',','.');
                                                    } else {
                                                        $ukuran = number_format($val->ukuran_ori,0,',','.');
                                                    }
                                                    echo $ukuran;
                                                    echo "</td>";
                                                    $hitung_ukuran = $val->ukuran_ori;
                                                    $s_satuan="Meter";
                                                }
                                            ?>
                                            <td>
                                                <?php if($dep_user=="Samatex"){ ?>
                                                <input type="hidden" id="txt<?=$n;?>" class="pilnilai" value="0">
                                                <input type="checkbox" name="kdroll[]" onclick="isiBox2('<?=$val->kode_roll;?>','<?=$hitung_ukuran;?>','sa','<?=$n;?>','<?=$kd_kons;?>','<?=$no_paket;?>','<?=$jns_paket;?>','<?=$s_satuan;?>')" value="<?=$val->kode_roll;?>" style="width:20px;height:20px;cursor:pointer;" id="customCheck<?=$n;?>" />
                                                <?php } else { ?>
                                                <input type="hidden" id="txt<?=$n;?>" class="pilnilai" value="0">
                                                <input type="checkbox" name="kdroll[]" onclick="isiBox('<?=$hitung_ukuran;?>','sa','<?=$n;?>')" value="<?=$val->kode_roll;?>" style="width:20px;height:20px;cursor:pointer;" id="customCheck<?=$n;?>" />
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-primary">
                                            <td><input type="hidden" id="ttlpnj" name="ttlpnjng" value="0"></td>
                                            <td><input type="hidden" value="<?=$no_paket;?>" name="nopaket"></td>
                                            <td><input type="hidden" value="<?=$jns_paket;?>" name="jnspaket"></td>
                                            <td><!--Pilih semua--></td> 
                                            <td><input type="hidden" id="ceksemuaid" value="0">
                                                <!-- <input type="checkbox" id="cekall" onclick="ceksemua()" style="width:20px;height:20px;cursor:pointer;" id="customCheck1" /> -->
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <p>&nbsp;</p>
                                <p id="ukuranpid">Total panjang yang dipilih : </p>
                                <hr>
                                <?php if($dep_user!="Samatex"){ ?>
                                <button type="submit" class="btn btn-primary pull-right">
                                    <i class="icon-copy fa fa-save" aria-hidden="true"></i> &nbsp;&nbsp; Simpan Paket
								</button>
                                <?php } else { ?>
                                <a href="<?=base_url('prosesajax/simpanrollstx/'.$no_paket);?>">
                                <button type="button" class="btn btn-primary pull-right">
                                    <i class="icon-copy fa fa-save" aria-hidden="true"></i> &nbsp;&nbsp; Simpan Paket
								</button></a>
                                <?php } ?>
                            </div>
                            <?php if($dep_user!="Samatex"){ ?>
                            </form><?php } ?>
						</div>
					</div>
					<!-- Simple Datatable End -->
					
        </div>
    </div>
</div>
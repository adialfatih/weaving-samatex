<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Informasi Produksi (Nomor Packinglist : <?=$kdp;?>)</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="Javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="Javascript:void(0);">Data Produksi</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Detail
										</li>
									</ol>
								</nav>
							</div>
							
						</div>
					</div>
					
					<!-- Simple Datatable start -->
				<div class="row">
					<div class="col-lg-7 col-md-12 col-sm-12 mb-30">
						<div class="card-box pd-20" style="margin-bottom:15px;">
							<h4 class="mb-30 h4">1. Lokasi Produksi : <strong><?=$dt['lokasi_produksi'];?></strong></h4>
							<table class="table table-bordered">
                                <tr>
                                    <td>Nomor Packinglist</td>
                                    <th><?=$dt['kode_produksi'];?></th>
                                </tr>
                                <tr>
                                    <td>Tanggal Produksi</td>
                                    <th>
                                        <?php $ex = explode('-', $dt['tgl_produksi']);
                                        echo $ex[2]." ".$bln[$ex[1]]." ".$ex[0]; ?>
                                    </th>
                                </tr>
                                <tr>
                                    <td>Kode Konstruksi</td>
                                    <th><?=$dt['kode_konstruksi'];?></th>
                                </tr>
                                <tr>
                                    <td>Jumlah Mesin</td>
                                    <th><?=$dt['jumlah_mesin'];?></th>
                                </tr>
                                <tr>
                                    <td>Jumlah Produksi</td>
                                    <td><strong><?=$dt['jumlah_produksi_awal'];?></strong> <em><?=$dt['satuan']=="Meter"?'m':'y';?></em></td>
                                </tr>
                                <tr>
                                    <td>Produksi</td>
                                    <td><strong><?=$dt['st_produksi']=="IG"?'Inspect Grey':'Inspect Finish';?></strong></td>
                                </tr>
                                <tr>
                                    <td>User</td>
                                    <th>
                                        <?php 
                                            $nm_user = $this->data_model->get_byid('user', ['id_user'=>$dt['id_user']])->row("nama_user");
                                            echo $nm_user;
                                        ?>
                                    </th>
                                </tr>
                                <tr>
                                    <td>Data List</td>
                                    <th>
                                        <a href="<?=base_url('data/list/'.sha1($kdp));?>" style="color:blue;">Lihat list</a>
                                    </th>
                                </tr>
                            </table>
						</div>
                        <?php
                $query = $this->data_model->get_byid('tb_proses_produksi',['kode_produksi'=>$kdp]);
                foreach($query->result() as $nu => $val):
                ?>
                
						<div class="card-box pd-20" style="margin-bottom:15px;">
							<h4 class="mb-30 h4"><?=$nu+2;?>. Lokasi Produksi : <strong><?=$val->lokasi_produksi;?></strong></h4>
							<table class="table table-bordered">
                                <tr>
                                    <td>Nomor Packinglist</td>
                                    <th><?=$dt['kode_produksi'];?></th>
                                </tr>
                                <tr>
                                    <td>Tanggal Produksi</td>
                                    <th>
                                        <?php $ex = explode('-', $val->tgl);
                                        echo $ex[2]." ".$bln[$ex[1]]." ".$ex[0]; ?>
                                    </th>
                                </tr>
                                <tr>
                                    <td>Kode Konstruksi</td>
                                    <th><?=$dt['kode_konstruksi'];?>
                                        <?php
                                            if($val->ch_to!=0){
                                                $cek_kons_ins = $this->data_model->get_byid('tb_produksi', ['id_produksi'=>$val->ch_to])->row("kode_konstruksi");
                                                echo '&nbsp;&nbsp;&nbsp; <i class="icon-copy bi bi-arrow-right-circle-fill"></i> &nbsp;&nbsp;&nbsp;'.$cek_kons_ins;
                                            }
                                        ?>
                                    </th>
                                </tr>
                                <tr>
                                    <td>Jumlah Produksi</td>
                                    <td><strong><?=$val->jumlah_awal;?></strong> <em><?=$val->satuan=='Meter'?'m':'y';?></em>
                                    
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>Produksi</td>
                                    <td><strong>
                                        <?php if($val->proses_name=="IF"){
                                            echo "Inspect Finish";
                                        } elseif ($val->proses_name=="FG") {
                                            echo "Folding Grey";
                                        } elseif ($val->proses_name=="FF") {
                                            echo "Folding Finish";
                                        }
                                        ?>
                                    </strong>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>User</td>
                                    <th>
                                        <?php
                                        $nm_user_pros = $this->data_model->get_byid('user', ['id_user'=>$val->pemroses])->row("nama_user");
                                        echo $nm_user_pros;
                                        ?>
                                    </th>
                                </tr>
                            </table>
						</div>
                        
                <?php endforeach; ?>
					</div>

					<div class="col-lg-5 col-md-12 col-sm-12 mb-30">
						<div class="card-box pd-30 height-100-p">
							<h4 class="mb-30 h4">Records</h4>
							<!-- timeline -->
                            <div class="timeline mb-30">
							<ul>
                                <?php foreach($timeline->result() as $value):
                                $dtimle = explode(' ', $value->tms_tmp);
                                $dtgle = explode('-', $dtimle[0]); ?>
								<li>
									<div class="timeline-date"><small><?=$dtgle['2']." ".$bln[$dtgle[1]]." ".$dtgle[0];?></small></div>
									<div class="timeline-desc card-box">
										<div class="pd-20">
											<h6 class="mb-10 h6">
                                            <?php 
                                                $nm_user1 = $this->data_model->get_byid('user', ['id_user'=>$value->id_user])->row("nama_user");
                                                echo $nm_user1;
                                            ?>
											</h6>
											<p><small><?=$value->log;?></small></p>
										</div>
									</div>
								</li>
                                <?php endforeach; ?>
							</ul>
						</div>
							<!-- timeline -->
						</div>
					</div>
				</div>
                
					
        </div>
    </div>
</div>
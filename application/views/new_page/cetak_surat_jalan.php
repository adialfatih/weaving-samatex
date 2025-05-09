<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header d-print-none">
						<div class="row">
							<div class="col-md-6 col-sm-12">
                                <div class="title">
									<h4>Data Pengiriman</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Pengiriman</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Nomor</a>
										</li>
										<li class="breadcrumb-item" aria-current="page">
                                            <?=$dtsj['no_sj'];?>
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20 d-print-none">
							<p class="mb-0">Surat Jalan Pengiriman</p>
                            <div class="text-right">
                                <?php if($sess_dep=="RJS"){ ?> 
                                    <a href="<?=base_url('cetakrjs/sj/'.sha1($dtsj['id_sj']).'/1');?>" target="_blank">
                                    <button type="button" class="btn btn-primary">
										<i class="icon-copy bi bi-printer-fill"></i> &nbsp; Cetak
                                    </button></a>
                                <?php } else { ?>
									<button type="button" onclick="window.print();" class="btn btn-primary">
										<i class="icon-copy bi bi-printer-fill"></i> &nbsp; Cetak
                                    </button>
                                <?php } ?>
							</div>
						</div>
						<div class="pd-20 card-box mb-30">
                            <!-- <form name="fr2" action="<=base_url('kirim/save_sj');?>" method="post" entype="multipart/form-data"> -->
                            <div class="clearfix">
                                <input type="hidden" value="<?=$sess_dep;?>" id="locnowid" name="dept">
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Tujuan Pengiriman</label>
                                        <div class="col-sm-12 col-md-10 col-form-label">
                                            <?php
                                            if($dtsj['tujuan_kirim']=="cus"){
                                                $nama_cus = $this->data_model->get_byid('dt_konsumen',['id_konsumen'=>$dtsj['id_customer']])->row("nama_konsumen");
                                                echo "<strong>".$nama_cus."</strong>";
                                            } else {
                                                echo "<strong>".$dtsj['tujuan_kirim']."</strong>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">No Surat Jalan</label>
                                        <div class="col-sm-12 col-md-10 col-form-label">
                                            <strong><?=$dtsj['no_sj'];?></strong>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Tanggal</label>
                                        <div class="col-sm-12 col-md-10 col-form-label">
                                            <?php
                                                $ex = explode('-', $dtsj['tgl_kirim']);
                                                echo "<strong>".$ex[2]." ".$bln[$ex[1]]." ".$ex[0]."</strong>";
                                            ?>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Data Pengiriman</label>
                                        <div class="col-sm-12 col-md-10">
                                            &nbsp;
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Kode PKG</th>
                                                <th>Konstruksi</th>
                                                <th>Jumlah Roll</th>
                                                <th>Total Panjang</th>
                                                <th class="d-print-none"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $total_roll=0; $total_panjang_semua=0;
                                                $query = $this->data_model->get_byid('new_tb_packinglist', ['no_sj'=>$dtsj['no_sj']]);
                                                foreach($query->result() as $num => $val):
                                                $jml_rol = $this->data_model->get_byid('kode_roll_outsite', ['kd'=>$val->kd]);
                                                $panjang=0; $total_panjang=0; $satuan="";
                                                foreach($jml_rol->result() as $rl):
                                                    $koderoll = $rl->kode_roll;
                                                    
                                                    $_satu = $this->data_model->get_byid('data_ig',['kode_roll'=>$koderoll]);
                                                    if($_satu->num_rows() == 1){
                                                        $panjang = $_satu->row("ukuran_ori");
                                                        $satuan = "Meter";
                                                        
                                                    } 
                                                    $_dua = $this->data_model->get_byid('data_if', ['kode_roll'=>$koderoll]);
                                                    if($_dua->num_rows() == 1){
                                                        $panjang = $_dua->row("ukuran_ori");
                                                        $satuan = "Yard";
                                                        
                                                    }
                                                    $_tiga = $this->data_model->get_byid('data_fol', ['kode_roll'=>$koderoll]);
                                                    if($_tiga->num_rows() == 1){
                                                        if($_tiga->row("jns_fold") == "Finish"){
                                                            $panjang = $_tiga->row("ukuran");
                                                            $satuan = "Yard";
                                                        } else {
                                                            $panjang = $_tiga->row("ukuran");
                                                            $satuan = "Meter";
                                                        }
                                                        //echo $panjang."owek";
                                                    }
                                                    $total_panjang = floatval($total_panjang) + $panjang;
                                                    
                                                endforeach;
                                                $total_roll = $total_roll + $jml_rol->num_rows();
                                                $total_panjang_semua = $total_panjang_semua + $total_panjang;
                                                $this->data_model->updatedata('kd',$val->kd,'new_tb_packinglist',['jumlah_roll'=>$jml_rol->num_rows(), 'ttl_panjang'=>round($total_panjang,2)]);
                                                
                                            ?>
                                            <tr>
                                                <td><?=$num+1;?></td>
                                                <td><?=$val->kd;?></td>
                                                <td><?=$val->kode_konstruksi;?><input type="hidden" name="kons_kode[]" value="<?=$val->kode_konstruksi;?>"></td>
                                                <td><?=$jml_rol->num_rows();?> Roll</td>
                                                <td><?=number_format($total_panjang)." ".$satuan;?>
                                                <input type="hidden" name="pkg_kode[]" value="<?=$val->kd;?>"></td>
                                                <td class="d-print-none">
                                                    <?php if($sess_dep=="RJS"){ ?> 
                                                    <a href="<?=base_url('cetakrjs/packinglist/'.$val->kd.'/1');?>" target="_blank"><i class="icon-copy bi bi-printer-fill"></i></a>
                                                    <?php } else { ?>
                                                    <a href="<?=base_url('cetak/packinglist/'.$val->kd.'/1');?>" target="_blank"><i class="icon-copy bi bi-printer-fill"></i></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td colspan="3"><strong>Total</strong></td>
                                                <td><?=$total_roll;?> Roll</td>
                                                <td><?=number_format($total_panjang_semua);?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                            </div>
                            <!-- </form> -->
						</div>
					</div>
					<!-- Simple Datatable End -->
					
        </div>
    </div>
</div>

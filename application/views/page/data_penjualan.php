<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Marc', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header d-print-none">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data Penjualan</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Data Penjualan</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Nomor</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											<?=sprintf("%04d", $nomor);?>
										</li>
									</ol>
								</nav>
							</div>
							
						</div>
					</div>
					<?php 
                            $ex = explode('-', $dt_all['tgl']);
                            $print_tgl = $ex[2]." ".$bln[$ex[1]]." ".$ex[0];
                            $ix = explode(' ', $dt_all['tm_stmp']);
                            $ox = explode('-', $ix[0]);
                            $print_tms = $ox[2]."-".$bln[$ox[1]]."-".$ox[0];
							?>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
                            <div class="invoice-wrap">
                                <div class="invoice-box">
                                    <!-- <div class="invoice-header">
                                        <div class="logo text-center">
                                            <img src="vendors/images/deskapp-logo.png" alt="" />
                                        </div>
                                    </div> -->
                                    <h4 class="text-center mb-30 weight-600">DATA PENJUALAN</h4>
                                    <div class="row pb-30">
                                        <div class="col-md-6">
                                            <h5 class="mb-15">Konstruksi : <?=$dt_all['kode_konstruksi'];?></h5>
                                            <p class="font-14 mb-5">
                                                Date Issued: <strong class="weight-600"><?=$print_tgl;?></strong>
                                            </p>
                                            <p class="font-14 mb-5">
                                                Invoice No: <strong class="weight-600"><?=sprintf("%04d", $nomor);?></strong>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-right">
                                                <p class="font-14 mb-5">To</p>
                                                <p class="font-14 mb-5"><?=$dt_all['nama_konsumen'];?></p>
                                                <p class="font-14 mb-5">+<?=$dt_all['no_hp'];?></p>
                                                <p class="font-14 mb-5"><?=$dt_all['alamat'];?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invoice-desc pb-30">
                                        <div class="invoice-desc-head clearfix">
                                            <div class="invoice-sub">Kode Produksi</div>
                                            <div class="invoice-rate">Roll</div>
                                            <div class="invoice-hours">Jns</div>
                                            <div class="invoice-subtotal">Ukuran</div>
                                        </div>
                                        <div class="invoice-desc-body">
                                            <ul>
                                                <?php $ukuran_total=0;
                                                    if($dt_all['type_penjualan']=="StokLama"){
                                                        $dt_list = explode(',', $dt_all['penjualan_list']);
                                                        for ($i=0; $i < count($dt_list); $i++) { 
                                                            $ex = explode('-', $dt_list[$i]);
                                                            $norol = $ex[0];
                                                            $ukuran = $ex[1];
                                                            $ukuran_total = $ukuran_total + $ukuran;
                                                            ?>
                                                <li class="clearfix">
                                                    <div class="invoice-sub">Stok Lama</div>
                                                    <div class="invoice-rate"><?=$norol;?></div>
                                                    <div class="invoice-hours"><?=$dt_all['satuan']=="Yard"?"Finish":"Grey";?></div>
                                                    <div class="invoice-subtotal">
                                                        <span class="weight-600">
                                                            <?=$ukuran;?><?=$dt_all['satuan']=="Yard"?" <em>y</em>":" <em>m</em>";?>
                                                        </span>
                                                    </div>
                                                </li>
                                                            <?php
                                                        }
                                                    } else {
                                                    
                                                    $dt_list = explode('-',$dt_all['penjualan_list']);
                                                    for($i=0; $i<count($dt_list); $i++){
                                                    $ceklist=$this->data_model->get_byid('new_tb_pkg_fol',['id_fol'=>$dt_list[$i]])->row_array();
                                                    $numli=$this->data_model->get_byid('new_tb_pkg_fol',['id_fol'=>$dt_list[$i]])->num_rows();
                                                    if($numli==1){
                                                ?>
                                                <li class="clearfix">
                                                    <div class="invoice-sub"><?=$ceklist['kode_produksi'];?></div>
                                                    <div class="invoice-rate"><?=$ceklist['no_roll'];?></div>
                                                    <div class="invoice-hours"><?=$ceklist['st_folding'];?></div>
                                                    <div class="invoice-subtotal">
                                                        <span class="weight-600">
                                                            <?php if($ceklist['st_folding']=="Grey"){
                                                                echo $ceklist['ukuran']." m";
                                                                $ukuran_total = $ukuran_total + floatval($ceklist['ukuran']);
                                                            } else {
                                                                echo $ceklist['ukuran_yard']." y";
                                                                $ukuran_total = $ukuran_total + floatval($ceklist['ukuran_yard']);
                                                            } ?>
                                                        </span>
                                                    </div>
                                                </li>
                                                <?php } } } ?>
                                                
                                            </ul>
                                        </div>
                                        <div class="invoice-desc-footer">
                                            <div class="invoice-desc-head clearfix">
                                                <div class="invoice-sub">Data Info</div>
                                                <div class="invoice-rate">Input By</div>
                                                <div class="invoice-subtotal">Total Ukuran</div>
                                            </div>
                                            <div class="invoice-desc-body">
                                                <ul>
                                                    <li class="clearfix">
                                                        <div class="invoice-sub">
                                                            <p class="font-14 mb-5">
                                                                Date Input :
                                                                <strong class="weight-600"><?=$print_tms;?></strong>
                                                            </p>
                                                            <p class="font-14 mb-5">
                                                                Time: <strong class="weight-600"><?=$ix[1];?></strong>
                                                            </p>
                                                        </div>
                                                        <div class="invoice-rate font-20 weight-600">
                                                            <?=$dt_all['nama_user'];?>
                                                        </div>
                                                        <div class="invoice-subtotal">
                                                            <span class="weight-600 font-24 text-danger"
                                                                ><?=round($ukuran_total,2);?></span
                                                            >
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="text-center pb-20">Thank You!!</h4>
                                </div>
                            </div>
						</div>
					</div>
					<!-- Simple Datatable End -->
					
        </div>
    </div>
</div>

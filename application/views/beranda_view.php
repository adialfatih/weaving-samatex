<?php
        $ar = array(
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ); 
                            
?>
		<div class="main-container">
			<div class="pd-ltr-20">
				<div class="card-box pd-20 height-100-p mb-30">
					<div class="row align-items-center">
						<div class="col-md-4">
							<img src="<?=base_url('assets/');?>vendors/images/banner-img.png" alt="" />
						</div>
						<div class="col-md-8">
							<h4 class="font-20 weight-500 mb-10 text-capitalize">
								Welcome Back <?=$sess_hak=='Manager' ? 'Manager':'';?>
								<div class="weight-600 font-30 text-blue"><?=$sess_nama;?></div>
							</h4>
							<p class="font-18 max-width-600">
								Selamat datang di sistem tracking produksi textile. Aplikasi ini secara otomatis akan menyimpan alur proses produksi di <strong>Rindang Jati Spinning</strong>, <strong>PT. Samatex</strong> dan <strong>PT. Pusatex</strong>
							</p>
						</div>
					</div>
				</div>
				
				
				<div class="footer-wrap pd-20 mb-20 card-box">
					&copy; <?=date('Y');?> : PT. Rindang Jati Spinning - Versi 1.0
				</div>
			</div>
		</div>
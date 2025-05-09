<?php 
$url = $this->uri->segment(1); $url2 = $this->uri->segment(2);
?>
<div class="left-side-bar">
			<div class="brand-logo">
				<a href="<?=base_url('assets/');?>index.html">
					<img src="<?=base_url('assets/');?>vendors/images/deskapp-logo.svg" alt="" class="dark-logo" />
					<img
						src="<?=base_url('assets/');?>vendors/images/deskapp-logo-white.svg"
						alt=""
						class="light-logo"
					/>
				</a>
				<div class="close-sidebar" data-toggle="left-sidebar-close">
					<i class="ion-close-round"></i>
				</div>
			</div>
			<div class="menu-block customscroll">
				<div class="sidebar-menu">
					<ul id="accordion-menu">
						<li class="dropdown">
							<a href="<?=base_url('pagedireksi');?>" class="dropdown-toggle no-arrow <?=$url==''?'active':'';?><?=$url=='beranda'?'active':'';?>">
								<span class="micon bi bi-house"></span
								><span class="mtext">Home</span>
							</a>
						</li>
						
						<li>
							<div class="sidebar-small-cap">Manager Menu</div>
						</li>
						
						<!-- <li>
							<a
								href="<=base_url('report-dashboard');?>"
								class="dropdown-toggle no-arrow <=$url=='report-dashboard'?'active':'';?>"
							>
								<span class="micon bi bi-layout-text-window-reverse"></span>
								<span class="mtext" >Dashboard Laporan</span>
							</a>
						</li> -->
						<li>
							<a href="<?=base_url('stok-gudang-samatex');?>" class="dropdown-toggle no-arrow <?=$url=='stok-gudang-samatex'?'active':'';?>">
								<span class="micon bi bi-layout-text-window-reverse"></span>
								<span class="mtext" >Stok Gudang</span>
							</a>
						</li>
						<li>
							<a href="<?=base_url('rekap-piutang');?>" class="dropdown-toggle no-arrow <?=$url=='rekap-piutang'?'active':'';?>">
								<span class="micon bi bi-layout-text-window-reverse"></span>
								<span class="mtext" >Rekap Penjualan</span>
							</a>
						</li>
						<li>
							<a href="<?=base_url('saldo-piutang');?>" class="dropdown-toggle no-arrow <?=$url=='saldo-piutang'?'active':'';?>">
								<span class="micon bi bi-layout-text-window-reverse"></span>
								<span class="mtext" >Saldo Piutang</span>
							</a>
						</li>
						<?php if($this->session->userdata('id')=='0002'){ ?>
						<li>
							<a
								href="<?=base_url('report-wa');?>"
								class="dropdown-toggle no-arrow <?=$url=='report-wa'?'active':'';?>"
							>
								<span class="micon bi bi-telephone-forward-fill"></span>
								<span class="mtext" >Laporan WA</span>
							</a>
						</li>
						<li>
							<a
								href="<?=base_url('report-wa2');?>"
								class="dropdown-toggle no-arrow <?=$url=='report-wa2'?'active':'';?>"
							>
								<span class="micon bi bi-telephone-forward-fill"></span>
								<span class="mtext" >Laporan WA</span>
							</a>
						</li><?php } ?>
                        
						<!-- <li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle <=$url=='report-produksi'?'active':'';?><=$url=='report-stok'?'class="active"':'';?>">
								<span class="micon bi-calendar4-week"></span
								><span class="mtext">Report Produksi</span>
							</a>
							<ul class="submenu">
								<li><a href="<=base_url('report-produksi');?>" <=$url=='report-produksi'?'class="active"':'';?>>Report Produksi</a></li>
								<li><a href="<=base_url('report-stok');?>" <=$url=='report-stok'?'class="active"':'';?>>Report Stok</a></li>
								<li><a href="<=base_url('report-stok-lama');?>" <=$url=='report-stok-lama'?'class="active"':'';?>>Report Stok Lama</a></li>
							</ul>
						</li> -->
                        <!-- <li>
							<a href="<=base_url('report-penjualan');?>" class="dropdown-toggle no-arrow <=$url=='report-penjualan'?'active':'';?>">
								<span class="micon bi bi-currency-exchange"></span
								><span class="mtext">Report Penjualan</span>
							</a>
						</li> -->
                        <!-- <li>
							<a href="<=base_url('manage-user');?>" class="dropdown-toggle no-arrow <=$url=='manage-user'?'active':'';?>">
								<span class="micon fa fa-user-circle"></span
								><span class="mtext">Manage User</span>
							</a>
						</li>
						 -->
                        
                        <li>
							<a href="<?=base_url('login');?>" class="dropdown-toggle no-arrow">
								<span class="micon dw dw-logout1"></span
								><span class="mtext">Logout</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="mobile-menu-overlay"></div>
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
							<a href="<?=base_url('beranda');?>" class="dropdown-toggle no-arrow <?=$url==''?'active':'';?><?=$url=='beranda'?'active':'';?>">
								<span class="micon bi bi-house"></span
								><span class="mtext">Home</span>
							</a>
						</li>
						<?php if($this->session->userdata('hak') == "Manager"){ ?>
						<li>
							<div class="sidebar-small-cap">Manager Menu</div>
						</li>
						
						<li>
							<a
								href="<?=base_url('manager-dashboard');?>"
								class="dropdown-toggle no-arrow <?=$url=='manager-dashboard'?'active':'';?>"
							>
								<span class="micon bi bi-layout-text-window-reverse"></span>
								<span class="mtext" >Dashboard Manager</span>
							</a>
						</li>
                        
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle <?=$url=='report-produksi'?'active':'';?><?=$url=='report-stok'?'class="active"':'';?>">
								<span class="micon bi-calendar4-week"></span
								><span class="mtext">Report</span>
							</a>
							<ul class="submenu">
								<li><a href="<?=base_url('report-produksi');?>" <?=$url=='report-produksi'?'class="active"':'';?>>Report Produksi</a></li>
								<!-- <li><a href="<=base_url('report-stok');?>stok-gudang" <=$url=='report-stok'?'class="active"':'';?>>Report Stok</a></li> -->
								<li><a href="<?=base_url('stok-gudang');?>" <?=$url=='report-stok'?'class="active"':'';?>>Report Stok</a></li>
								<!-- <li><a href="<=base_url('report-stok-lama');?>" <=$url=='report-stok-lama'?'class="active"':'';?>>Report Stok Lama</a></li> -->
							</ul>
						</li>
                        <li>
							<a href="<?=base_url('report-penjualan');?>" class="dropdown-toggle no-arrow <?=$url=='report-penjualan'?'active':'';?>">
								<span class="micon bi bi-currency-exchange"></span
								><span class="mtext">Report Penjualan</span>
							</a>
						</li>
                        <li>
							<a href="<?=base_url('manage-user');?>" class="dropdown-toggle no-arrow <?=$url=='manage-user'?'active':'';?>">
								<span class="micon fa fa-user-circle"></span
								><span class="mtext">Manage User</span>
							</a>
						</li>
						<li>
							<a href="<?=base_url('log-aktivitas');?>" class="dropdown-toggle no-arrow <?=$url=='log-aktivitas'?'active':'';?>">
								<span class="micon bi bi-activity"></span
								><span class="mtext">Log Aktivitas</span>
							</a>
						</li>
						<?php } ?>
                        <li>
							<div class="sidebar-small-cap">Admin Menu</div>
						</li>
                        <li>
							<a href="<?=base_url('operator-dashboard');?>"
								class="dropdown-toggle no-arrow <?=$url=='operator-dashboard'?'active':'';?>">
								<span class="micon bi bi-layout-text-window-reverse"></span>
								<span class="mtext" >Dashboard Operator</span>
							</a>
						</li>
                        <li>
							<a href="<?=base_url('input-konstruksi');?>" class="dropdown-toggle no-arrow <?=$url=='input-konstruksi'?'active':'';?>">
								<span class="micon fi-folder-add"></span
								><span class="mtext"><?=$this->session->userdata('hak') == 'Manager' ?'':'Input';?> Data Konstruksi</span>
							</a>
						</li>
						<!-- <li>
							<a href="<=base_url('produksi-mesin');?>" class="dropdown-toggle no-arrow <=$url=='produksi-mesin'?'active':'';?>">
								<span class="micon dw dw-analytics-8"></span
								><span class="mtext">Produksi Mesin</span>
							</a>
						</li> -->
                        <!-- <li>
							<a href="<=base_url('input-produksi');?>" class="dropdown-toggle no-arrow <=$url=='input-produksi'?'active':'';?>">
								<span class="micon dw dw-add-file2"></span
								><span class="mtext"><=$this->session->userdata('hak') == 'Manager' ?'':'Input';?> Data Produksi</span>
							</a>
						</li> -->
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle <?=$url=='input-produksi'?'active':'';?>">
								<span class="micon dw dw-add-file2"></span
								><span class="mtext">Produksi</span>
							</a>
							<ul class="submenu">
								<li><a href="<?=base_url('generate-kode');?>" <?=$url=='generate-kode'?'class="active"':'';?>>Generate Kode</a></li>
								<li><a href="<?=base_url('input-produksi');?>" <?=$url=='input-produksi'?'class="active"':'';?>>Produksi</a></li>
								<li><a href="<?=base_url('proses-produksi');?>" <?=$url=='proses-produksi'?'class="active"':'';?>>Upload Produksi</a></li>
								<li><a href="<?=base_url('produksi-mesin');?>" <?=$url=='produksi-mesin'?'class="active"':'';?>>Produksi Mesin</a></li>
								<li><a href="<?=base_url('pengiriman');?>" <?=$url=='pengiriman'?'class="active"':'';?>>Pengiriman ke luar</a></li>
							</ul>
						</li>
						<li>
							<a href="<?=base_url('stok-gudang');?>" class="dropdown-toggle no-arrow <?=$url=='stok-gudang'?'active':'';?>">
								<span class="micon bi bi-menu-button-wide-fill"></span
								><span class="mtext">Stok Gudang</span>
							</a>
						</li>
						<li>
							<a href="<?=base_url('packing-list');?>" class="dropdown-toggle no-arrow <?=$url=='packing-list'?'active':'';?>">
								<span class="micon bi bi-box-seam"></span><span class="mtext">Packing List</span>
							</a>
						</li>
						<li>
							<a href="<?=base_url('packing-list-bs');?>" class="dropdown-toggle no-arrow <?=$url=='packing-list-bs'?'active':'';?>">
								<span class="micon bi bi-box-seam"></span><span class="mtext">Packing List BS</span>
							</a>
						</li>
						<li>
							<a href="<?=base_url('data-roll');?>" class="dropdown-toggle no-arrow <?=$url=='data-roll'?'active':'';?>">
								<span class="micon bi bi-hdd-stack"></span><span class="mtext">Data Roll</span>
							</a>
						</li>
						<?php if($this->session->userdata('departement') == "Samatex"){ ?>
                        <li>
							<a href="<?=base_url('input-penjualan');?>" class="dropdown-toggle no-arrow <?=$url=='input-penjualan'?'active':'';?>">
								<span class="micon bi bi-currency-exchange"></span
								><span class="mtext">Data Penjualan</span>
							</a>
						</li>
						<li>
							<a href="<?=base_url('nota-penjualan');?>" class="dropdown-toggle no-arrow <?=$url=='nota-penjualan'?'active':'';?>">
								<span class="micon bi bi-currency-exchange"></span
								><span class="mtext">Nota Penjualan</span>
							</a>
						</li>
						<li>
							<a href="<?=base_url('data-konsumen');?>" class="dropdown-toggle no-arrow <?=$url=='data-konsumen'?'active':'';?>">
								<span class="micon bi bi-people"></span
								><span class="mtext">Data Konsumen</span>
							</a>
						</li>
						<?php } ?>
						<li>
							<a href="<?=base_url('sj');?>" class="dropdown-toggle no-arrow <?=$url=='sj'?'active':'';?>">
								<span class="micon bi bi-truck"></span
								><span class="mtext">Surat Jalan </span>
							</a>
						</li>
						
						<li>
							<a href="<?=base_url('operator');?>" class="dropdown-toggle no-arrow <?=$url=='operator'?'active':'';?>">
								<span class="micon fa fa-id-badge"></span
								><span class="mtext">Operator Produksi</span>
							</a>
						</li>
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
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title><?=$title;?></title>

		<!-- Site favicon -->
		<link rel="apple-touch-icon" sizes="180x180" href="<?=base_url('assets/');?>vendors/images/apple-touch-icon.png"/>
		<link rel="icon" type="image/png" sizes="32x32" href="<?=base_url('assets/');?>vendors/images/favicon-32x32.png"/>
		<link rel="icon" type="image/png" sizes="16x16" href="<?=base_url('assets/');?>vendors/images/favicon-16x16.png"/>

		<!-- Mobile Specific Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

		<!-- Google Font -->
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="<?=base_url('assets/');?>vendors/styles/core.css" />
		<link rel="stylesheet" type="text/css" href="<?=base_url('assets/');?>vendors/styles/icon-font.min.css" />
		<link rel="stylesheet" type="text/css" href="<?=base_url('assets/');?>src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
		<link rel="stylesheet" type="text/css" href="<?=base_url('assets/');?>src/plugins/datatables/css/responsive.bootstrap4.min.css" />
		<link rel="stylesheet" type="text/css" href="<?=base_url('assets/');?>vendors/styles/style.css" />
		<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
		<style>
			.notiftruck {
				width: 20px;
				height: 20px;
				background-color:red;
				color:#ffffff;
				position :absolute;
				border-radius:50%;
				display:flex;
				justify-content:center;
				align-items:center;
				font-size:10px;
				right:-5px;
				top:-5px;
			}
			table.table-bordered{
				border:1px solid #000000 !important;
				margin-top:20px;
			}
			table.table-bordered > thead > tr > th{
				border:1px solid #000000 !important;
			}
			table.table-bordered > tbody > tr > td{
				border:1px solid #000000 !important;
			}
			.lds-ring {
                display: inline-block;
                position: relative;
                width: 20px;
                height: 20px;
                }
                .lds-ring div {
                box-sizing: border-box;
                display: block;
                position: absolute;
                width: 24px;
                height: 24px;
                margin: 8px;
                border: 8px solid #ccc;
                border-radius: 50%;
                animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
                border-color: #ccc transparent transparent transparent;
                }
                .lds-ring div:nth-child(1) {
                animation-delay: -0.45s;
                }
                .lds-ring div:nth-child(2) {
                animation-delay: -0.3s;
                }
                .lds-ring div:nth-child(3) {
                animation-delay: -0.15s;
                }
                @keyframes lds-ring {
                0% {
                    transform: rotate(0deg);
                }
                100% {
                    transform: rotate(360deg);
                }
                }
                .card-kons {
                    width: 100px;
                    background: #FFFFFF;
                    margin: 0 10px 10px 0;
                    box-shadow: 2px 5px 10px #c2defc;
                    display: flex;
                    flex-direction: column;
                    border-radius: 7px;
                    border: 1px solid #edeeed;
                    overflow: hidden;
                }
                .card-kons div {
                    width: 100%;
                    text-align: center;
                }
                .card-kons div:nth-child(1){
                    background: #096cd6;
                    color:#FFFFFF;
                    padding: 5px 0;
                    font-size:12px;
                }
                .card-kons div:nth-child(2){
                    font-family: 'Noto Serif Vithkuqi', serif;
                    font-size: 16px;
                    padding: 10px 0;
                }
			
		</style>
		<?php  if(!empty($autocomplet)) { ?>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
		<?php } ?>
		<?php  if(!empty($daterange)) { ?>
		<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
		<?php } ?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85" ></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag() {
				dataLayer.push(arguments);
			}
			gtag("js", new Date());

			gtag("config", "G-GBZ3SGGX85");
		</script>
		<!-- Google Tag Manager -->
		<script>
			(function (w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({ "gtm.start": new Date().getTime(), event: "gtm.js" });
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s),
					dl = l != "dataLayer" ? "&l=" + l : "";
				j.async = true;
				j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, "script", "dataLayer", "GTM-NXZMQSS");
		</script>
		<!-- End Google Tag Manager -->
	</head>
<?php 
    $announce = $this->session->flashdata('announce'); $gagal = $this->session->flashdata('gagal');
    if(!empty($announce)){ ?>
    <body onload="suksestoast('<?=$announce;?>')">
<?php
    } else {
    if(!empty($gagal)){  ?>
    <body onload="peringatan('<?=$gagal;?>')">
<?php
    } else {
		echo "<body id='noteidbody'>";
	}
	}
?>
		<!-- <div class="pre-loader">
			<div class="pre-loader-box">
				<div class="loader-logo">
					<img src="base_url('assets/');?>vendors/images/deskapp-logo.svg" alt="" />
				</div>
				<div class="loader-progress" id="progress_div">
					<div class="bar" id="bar1"></div>
				</div>
				<div class="percent" id="percent1">0%</div>
				<div class="loading-text">Loading...</div>
			</div>
		</div> -->

		<div class="header d-print-none">
			<div class="header-left">
				<div class="menu-icon bi bi-list"></div>
				<div
					class="search-toggle-icon bi bi-search"
					data-toggle="header_search"
				></div>
				<div class="header-search">
					<form>
						<div class="form-group mb-0">
							<i class="dw dw-search2 search-icon"></i>
							<input
								type="text"
								class="form-control search-input"
								placeholder="Search Here"
							/>
							<div class="dropdown">
								<a class="dropdown-toggle no-arrow" href="<?=base_url('assets/');?>#" role="button" data-toggle="dropdown" >
									<i class="ion-arrow-down-c"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<div class="form-group row">
										<label class="col-sm-12 col-md-2 col-form-label"
											>From</label
										>
										<div class="col-sm-12 col-md-10">
											<input
												class="form-control form-control-sm form-control-line"
												type="text"
											/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-12 col-md-2 col-form-label">To</label>
										<div class="col-sm-12 col-md-10">
											<input
												class="form-control form-control-sm form-control-line"
												type="text"
											/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-12 col-md-2 col-form-label"
											>Subject</label
										>
										<div class="col-sm-12 col-md-10">
											<input
												class="form-control form-control-sm form-control-line"
												type="text"
											/>
										</div>
									</div>
									<div class="text-right">
										<button class="btn btn-primary">Search</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="header-right">
				<div class="dashboard-setting user-notification">
					<div class="dropdown">
						<a
							class="dropdown-toggle no-arrow"
							href="javascript:void(0);"
							data-toggle="right-sidebar"
						>
							<i class="dw dw-settings2"></i>
						</a>
					</div>
				</div>
				<div class="user-notification">
					<div class="dropdown">
						<a
							class="dropdown-toggle no-arrow"
							href="<?=base_url('assets/');?>#"
							role="button"
							data-toggle="dropdown"
						>
							<i class="icon-copy dw dw-notification"></i>
							<span class="badge notification-active"></span>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<div class="notification-list mx-h-350 customscroll">
								<ul>
									<?php
									 	$ceknotif = $this->data_model->get_byid("notifikasi", ['departement'=>$this->session->userdata('departement')]);
										if($ceknotif->num_rows()==0){
											echo "<li>Tidak ada notifikasi</li>";
										} else {
											foreach($ceknotif->result() as $vnotif):
									?>
									
									<li>
										<a href="javascript:void(0);">
											<img src="<?=base_url('assets/download.png');?>" alt="" />
											<h3><small>
												<?php
													$ep = explode(" ", $vnotif->tm_stmp);
													$ep2 = explode("-", $ep[0]);
													echo $ep2[2]."-".$ep2[1]."-".$ep2[0]." ".$ep[1];
												?>
											</small></h3>
											<p><?=$vnotif->notif;?></p>
										</a>
									</li>
									<?php endforeach; } ?>
									
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="user-info-dropdown">
					<div class="dropdown">
						<a
							class="dropdown-toggle"
							href="<?=base_url('assets/');?>#"
							role="button"
							data-toggle="dropdown"
						>
							<span class="user-icon">
								<img src="<?=base_url('assets/');?>vendors/images/photo1.jpg" alt="" />
							</span>
							<span class="user-name"><?=$sess_nama;?></span>
						</a>
						<div
							class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
						>
							<a class="dropdown-item" href="<?=base_url('assets/');?>profile.html"
								><i class="dw dw-user1"></i> Profile</a
							>
							<a class="dropdown-item" href="<?=base_url('assets/');?>profile.html"
								><i class="dw dw-settings2"></i> Setting</a
							>
							<a class="dropdown-item" href="<?=base_url('assets/');?>faq.html"
								><i class="dw dw-help"></i> Help</a
							>
							<a class="dropdown-item" href="<?=base_url('login/');?>"
								><i class="dw dw-logout"></i> Log Out</a
							>
						</div>
					</div>
				</div>
				
			</div>
		</div>

		<div class="right-sidebar">
			<div class="sidebar-title">
				<h3 class="weight-600 font-16 text-blue">
					Layout Settings
					<span class="btn-block font-weight-400 font-12"
						>User Interface Settings</span
					>
				</h3>
				<div class="close-sidebar" data-toggle="right-sidebar-close">
					<i class="icon-copy ion-close-round"></i>
				</div>
			</div>
			<div class="right-sidebar-body customscroll">
				<div class="right-sidebar-body-content">
					<h4 class="weight-600 font-18 pb-10">Header Background</h4>
					<div class="sidebar-btn-group pb-30 mb-10">
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary header-white active"
							>White</a
						>
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary header-dark"
							>Dark</a
						>
					</div>

					<h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
					<div class="sidebar-btn-group pb-30 mb-10">
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary sidebar-light"
							>White</a
						>
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary sidebar-dark active"
							>Dark</a
						>
					</div>

					<h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
					<div class="sidebar-radio-group pb-10 mb-10">
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebaricon-1"
								name="menu-dropdown-icon"
								class="custom-control-input"
								value="icon-style-1"
								checked=""
							/>
							<label class="custom-control-label" for="sidebaricon-1"
								><i class="fa fa-angle-down"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebaricon-2"
								name="menu-dropdown-icon"
								class="custom-control-input"
								value="icon-style-2"
							/>
							<label class="custom-control-label" for="sidebaricon-2"
								><i class="ion-plus-round"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebaricon-3"
								name="menu-dropdown-icon"
								class="custom-control-input"
								value="icon-style-3"
							/>
							<label class="custom-control-label" for="sidebaricon-3"
								><i class="fa fa-angle-double-right"></i
							></label>
						</div>
					</div>

					<h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
					<div class="sidebar-radio-group pb-30 mb-10">
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-1"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-1"
								checked=""
							/>
							<label class="custom-control-label" for="sidebariconlist-1"
								><i class="ion-minus-round"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-2"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-2"
							/>
							<label class="custom-control-label" for="sidebariconlist-2"
								><i class="fa fa-circle-o" aria-hidden="true"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-3"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-3"
							/>
							<label class="custom-control-label" for="sidebariconlist-3"
								><i class="dw dw-check"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-4"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-4"
								checked=""
							/>
							<label class="custom-control-label" for="sidebariconlist-4"
								><i class="icon-copy dw dw-next-2"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-5"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-5"
							/>
							<label class="custom-control-label" for="sidebariconlist-5"
								><i class="dw dw-fast-forward-1"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-6"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-6"
							/>
							<label class="custom-control-label" for="sidebariconlist-6"
								><i class="dw dw-next"></i
							></label>
						</div>
					</div>

					<div class="reset-options pt-30 text-center">
						<button class="btn btn-danger" id="reset-settings">
							Reset Settings
						</button>
					</div>
				</div>
			</div>
		</div>
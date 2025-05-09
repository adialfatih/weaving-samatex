
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Login - PPC Weaving</title>

		<!-- Site favicon -->
		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="<?=base_url('assets/');?>vendors/images/apple-touch-icon.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="<?=base_url('assets/');?>vendors/images/favicon-32x32.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="<?=base_url('assets/');?>vendors/images/favicon-16x16.png"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="<?=base_url('assets/');?>vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="<?=base_url('assets/');?>vendors/styles/icon-font.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="<?=base_url('assets/');?>vendors/styles/style.css" />

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script
			async
			src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"
		></script>
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
	<body class="login-page">
		<div class="login-header box-shadow">
			<div
				class="container-fluid d-flex justify-content-between align-items-center"
			>
				<div class="brand-logo">
					<a href="login.html" style="color:#000;">
						PPC WEAVING
					</a>
				</div>
				
			</div>
		</div>
		<div
			class="login-wrap d-flex align-items-center flex-wrap justify-content-center"
		>
			<div class="container">
				<div class="row align-items-center">
					<div class="col-md-6 col-lg-7">
						<img src="<?=base_url('assets/');?>vendors/images/login-page-img.png" alt="" />
					</div>
					<div class="col-md-6 col-lg-5">
						<div class="login-box bg-white box-shadow border-radius-10">
							<div class="login-title">
								<h2 class="text-center text-primary">Login PPC Weaving</h2>
								
							</div>
							<form name="fr1" enctype="multipart/form-data" action="<?=base_url('login/action');?>" method="post">
								<div class="select-role">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<label class="btn active">
											<input type="radio" name="options" id="admin" value="Manager" required />
											<div class="icon">
												<img
													src="<?=base_url('assets/');?>vendors/images/briefcase.svg"
													class="svg"
													alt="Pilih login sebagai Manager"
												/>
											</div>
											<span>I'm</span>
											Manager
										</label>
										<label class="btn">
											<input type="radio" name="options" id="teller" value="Admin" />
											<div class="icon">
												<img
													src="<?=base_url('assets/');?>vendors/images/person.svg"
													class="svg"
													alt="Pilih login sebagai Operator"
												/>
											</div>
											<span>I'm</span>
											Admin
										</label>
									</div>
								</div>
								<div class="input-group custom">
									<input
										type="text"
										class="form-control form-control-lg"
										placeholder="Username / Email"
                                        name="username" id="kodelogin" required
									/>
									<div class="input-group-append custom">
										<span class="input-group-text"
											><i class="icon-copy dw dw-user1"></i
										></span>
									</div>
								</div>
								<div class="input-group custom">
									<input
										type="password"
										class="form-control form-control-lg"
										placeholder="**********"
                                        name="password" id="paslogin" required
									/>
									<div class="input-group-append custom">
										<span class="input-group-text"
											><i class="dw dw-padlock1"></i
										></span>
									</div>
								</div>
								<div class="row pb-30">
									<div class="col-6">
										<div class="custom-control custom-checkbox">
											<input
												type="checkbox"
												class="custom-control-input"
												id="customCheck1"
											/>
											<!-- <label class="custom-control-label" for="customCheck1"
												>Remember</label
											> -->
										</div>
									</div>
									<div class="col-6">
										<div class="forgot-password">
											<a href="javascript:void(0);">Forgot Password</a>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="input-group mb-0">
											<!--
											use code for form submit
											<input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
										-->
											<button type="submit"
												class="btn btn-primary btn-lg btn-block"
												
												>Sign In</button>
										</div>
										
									</div>
								</div>
							<?php
							$announce = $this->session->flashdata('announce');
                            if(!empty($announce)){
							?>
								<div class="row">
									<div class="col-sm-12">
										&nbsp;<br>
										<small class="text-danger"><?=$announce;?></small>
									</div>
								</div>
							<?php } ?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
        
		<!-- js -->
		<script src="<?=base_url('assets/');?>vendors/scripts/core.js"></script>
		<script src="<?=base_url('assets/');?>vendors/scripts/script.min.js"></script>
		<script src="<?=base_url('assets/');?>vendors/scripts/process.js"></script>
		<script src="<?=base_url('assets/');?>vendors/scripts/layout-settings.js"></script>
		<!-- Google Tag Manager (noscript) -->
		<noscript
			><iframe
				src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
				height="0"
				width="0"
				style="display: none; visibility: hidden"
			></iframe
		></noscript>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>

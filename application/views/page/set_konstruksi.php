<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Setting Data Konstruksi</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Settings</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Data Konstruksi
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
								Pengaturan pengubahan data konstruksi secara otomatis setiap proses. Anda dapat mengatur proses perubahan nama konstruksi pada proses inspect finish secara otomatis melalui pengaturan di bawah ini. 
							</p>
						</div>
						<div class="pb-20">
							<form name="fr1" method="post" action="<?=base_url('newpros/setkodekons');?>">
							<table class="table stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort">No</th>
										<th>Kode Konstruksi Awal</th>
										<th></th>
										<th>Kode Konstruksi Akhir</th>
										
									</tr>
								</thead>
								<tbody>
									<?php foreach($data_table->result() as $no => $val): ?>
									<tr>
										<td class="table-plus"><?=$no+1;?></td>
										<td><?=$val->kode_konstruksi;?></td>
										<td><span class="icon-copy ti-angle-double-right"></span></td>
										<td>
                                            <select name="kdagain[]" id="kdagain" class="form-control">
												<option value="NULL">--Pilih kode konstruksi--</option>
												<?php if($val->chto=="NULL"){ ?>
												<option value="NULL" selected>-</option>
												<?php } else { ?>
												<option value="<?=$val->chto;?>" selected><?=$val->chto;?></option>
												<option value="NULL">-</option>
												<?php } ?>
												<?php foreach($data_table->result() as $vl): 
												if($vl->kode_konstruksi!=$val->kode_konstruksi){
												?>
												<option value="<?=$vl->kode_konstruksi;?>"><?=$vl->kode_konstruksi;?></option>
												<?php } endforeach; ?>
											</select>
                                        </td>
										<input type="hidden" name="kdawal[]" value="<?=$val->kode_konstruksi;?>">
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
							<button type="submit" class="btn btn-primary" style="margin-left:20px;">Simpan</button>
							</form>
						</div>
					</div>
					
        </div>
    </div>
</div>
<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Report Produksi Mesin</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Report</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Produksi Mesin
										</li>
									</ol>
								</nav>
							</div>
							
						</div>
					</div>
					<!-- Simple Datatable start -->
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Data Produksi Mesin
							</p><small>Tampilkan data produksi mesin berdasarkan tanggal dan departement</small>
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                                <form action="<?=base_url('laporan/mesin');?>" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-12 col-md-2 col-form-label">Departement</label>
                                    <div class="col-sm-12 col-md-10">
                                        <select name="dep" id="dep" class="form-control" style="width:300px;">
                                            <option value="">--Pilih Departement--</option>
                                            <option value="RJS">RJS</option>
                                            <option value="Samatex">Samatex</option>
                                            <option value="Pusatex">Pusatex</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-12 col-md-2 col-form-label">Tanggal</label>
                                    <div class="col-sm-12 col-md-10">
                                        <input name="datesr" class="form-control" style="width:300px;">
                                    </div>
                                </div>
                                <hr>
                                    
                                <button type="submit" class="btn btn-primary">
                                    <span class="icon-copy ti-search"></span> &nbsp; Lihat Laporan
                                </button>
                                </form>
                            </div>
						</div>
					</div>
					<!-- Simple Datatable End -->
        </div>
    </div>
</div>

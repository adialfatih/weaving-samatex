<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data Packinglist</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Data</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Create</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
                                        Packinglist
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
								<strong>BUAT PACKINGLIST</strong>
							</p><small>Buat packinglist untuk beberapa roll kain untuk mempermudah pengiriman dan penjualan.</small>
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                                <form class="fr1" action="<?=base_url('insert-data-list');?>" method="post" entype="multipart/form-data">
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Nomor Packinglist</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:200px;" type="text" value="<?=$kd;?>" name="nopkt" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Tanggal Packing</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:200px;" placeholder="Tanggal produksi" type="date" name="tgl" value="<?=date('Y-m-d');?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Kode Konstruksi</label>
                                        <div class="col-sm-12 col-md-10">
                                            <div class="autoComplete_wrapper">
												<input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" placeholder="Kode konstruksi" name="kode" required>
											</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Lokasi Paket</label>
                                        <div class="col-sm-12 col-md-10">
                                            <!-- <input type="text" value="<=$dep_user;?>" style="width:300px;" class="form-control" name="loc" readonly> -->
                                            <select name="loc" id="loc" class="form-control" style="width:300px;" required>
                                                <option value="">Pilih Lokasi Paket</option>
                                                <option value="Samatex" <?=$dep_user=="Samatex" ? 'selected':'';?>>Samatex</option>
                                                <option value="Pusatex" <?=$dep_user=="Pusatex" ? 'selected':'';?>>Pusatex</option>
                                                <option value="RJS" <?=$dep_user=="RJS" ? 'selected':'';?>>Rindang Jati Spinning</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Jenis Packinglist</label>
                                        <div class="col-sm-12 col-md-10">
                                            <select name="jnspkt" id="jnspkt" class="form-control" style="width:200px;" required>
                                                <option value="">Pilih Jenis Paket</option>
                                                <option value="y">Siap Jual</option>
                                                <option value="n">Proses Produksi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row" id="hargaSatuan" style="display:none;">
                                        <label class="col-sm-12 col-md-2 col-form-label">Harga per satuan</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input type="text" placeholder="Masukan harga perstuan" class="form-control" style="width:300px;" id="formattedNumberField">
                                        </div>
                                    </div> -->
                                    <hr>
                                    <strong>Note : </strong> Setelah anda menyimpan, anda akan di arahkan ke halaman isi pakcing list untuk memilih roll mana saja yang masuk ke packing list yang anda buat.
                                    <hr>

                                        <button type="submit" class="btn" data-bgcolor="#3f72d1" data-color="#ffffff">
                                            <span class="icon-copy ti-save"></span> &nbsp; Simpan
                                        </button>
                                </form>
                            </div>
						</div>
					</div>
					<!-- Simple Datatable End -->
					
        </div>
    </div>
</div>

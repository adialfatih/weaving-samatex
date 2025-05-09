<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								<strong>Akses Dibatasi</strong>
							</p><small>Anda perlu memasukan password untuk mengakses laporan di departement lain.</small>
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                                <form action="<?=base_url('laporan/mesin');?>" method="post">
                                <!-- <div class="form-group row">
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
                                </button> -->
                                <divs style="width:100%;display:flex;flex-direction:column;align-items:center;">
                                    <lottie-player src="https://assets8.lottiefiles.com/packages/lf20_3dtypvor.json"  background="transparent"  speed="0.5"  style="width: 300px; height: 300px;"  loop  autoplay></lottie-player>
                                    <h5>Masukan password Super Admin</h5>
                                    <p></p>
                                    <input type="password" name="akspass" placeholder="Masukan password untuk melanjutkan" class="form-control" style="width:500px;">
                                    <?=$pass=="null" ?'':'<code>Password yang anda masukan salah</code>';?>
                                    <p>&nbsp;</p>
                                    
                                    <input type="hidden" name="dep" value="<?=$dep;?>">
                                    <input type="hidden" name="datesr" value="<?=$tgl;?>">
                                    <button type="submit" class="btn btn-secondary">
                                        <span class="icon-copy bi bi-unlock"></span> &nbsp; Lihat Laporan
                                    </button>
                                </div>
                                </form>
                            </div>
						</div>
					</div>
					<!-- Simple Datatable End -->
        </div>
    </div>
</div>

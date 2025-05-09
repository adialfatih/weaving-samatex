<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Pengiriman</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Pengiriman</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Surat Jalan</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Buat Baru
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
								Buat Surat Jalan
							</p><small>Anda harus membuat surat jalan untuk pengiriman paket.</small>
						</div>
						<div class="pd-20 card-box mb-30">
                            <form name="fr2" action="<?=base_url('kirim/kirim_paket2');?>" method="post" entype="multipart/form-data">
                            <div class="clearfix">
                                <input type="hidden" value="<?=$sess_dep;?>" id="locnowid" name="dept">
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Tujuan Pengiriman</label>
                                        <div class="col-sm-12 col-md-10">
                                            <select name="loc" id="idselpengiriman" onchange="upsj(this.value)" class="form-control" style="width:350px;" required>
                                                <option value="">Pilih Lokasi</option>
                                                <?=$sess_dep=='Samatex' ? '':'<option value="Samatex">Samatex</option>';?>
                                                <?=$sess_dep=='Pusatex' ? '':'<option value="Pusatex">Pusatex</option>';?>
                                                <?=$sess_dep=='RJS' ? '':'<option value="RJS">Rindang Jati</option>';?>
                                                <option value="cus">Customer / Pembeli</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">No Surat Jalan</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:350px;" placeholder="Masukan nomor surat jalan" type="text" id="nosjid" name="sj" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Tanggal</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:250px;" type="date" name="tgl" value="<?=date('Y-m-d');?>" required>
                                        </div>
                                    </div>
                                    <input type="hidden" id="idcus" name="idcus" value="0">
                                    <div class="form-group row" id="frh1" style="display:none;">
                                        <label class="col-sm-12 col-md-2 col-form-label">Masukan nama customer</label>
                                        <div class="col-sm-12 col-md-10" style="display:flex;">
                                            <!-- <input class="form-control" style="width:300px;" type="text" name="namacus" placeholder="Masukan nama customer / pembeli" id="namaCustomer">
                                            &nbsp;&nbsp;
                                            <span id="loader" style="display:none;"><img src="<=base_url('assets/oval.svg');?>" style="width:20px;" ></span>
                                            <i class="icon-copy bi bi-check-circle-fill" style="display:none;color:green;" id="cekijo" title="Customer telah terdaftar di dalam sistem"></i>
                                            <i class="icon-copy bi bi-info-circle-fill" style="display:none;color:red;" id="cekabang" title="Customer belum terdaftar di dalam sistem. Mohon masukan nomor telepon dan alamat"></i> -->
                                            <div class="autoComplete_wrapper">
												<input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" placeholder="Nama Konsumen" name="namacus">
											</div>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row" id="frh2" style="display:none;">
                                        <label class="col-sm-12 col-md-2 col-form-label">Masukan No Telpon</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:300px;" type="text" name="notelp" value="62" id="notelpkid">
                                        </div>
                                    </div>
                                    <div class="form-group row" id="frh3" style="display:none;">
                                        <label class="col-sm-12 col-md-2 col-form-label">Masukan Alamat</label>
                                        <div class="col-sm-12 col-md-10">
                                            <textarea class="form-control" style="width:350px;height:100px;" name="almt" placeholder="Masukan alamat customer / pembeli" id="almtkid"></textarea>
                                        </div>
                                    </div> -->
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Data Pengiriman</label>
                                        <div class="col-sm-12 col-md-10">
                                            &nbsp;
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Kode PKG</th>
                                                <th>Konstruksi</th>
                                                <th>Jumlah Roll</th>
                                                <th>Total Panjang</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $total_roll=0; $total_panjang_semua=0; $ar_jns_paket = array();
                                                for ($i=0; $i <count($dt) ; $i++) {
                                                if($dt[$i]!=""){
                                                $query = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$dt[$i]])->row_array();
                                                $jml_rol = $query['jumlah_roll'];
                                                $total_roll+=intval($jml_rol);
                                                $total_panjang_semua+=floatval($query['ttl_panjang']);
                                                $ar_jns_paket[]=$query['siap_jual'];
                                            ?>
                                            <tr>
                                                <td><?=$i+1;?></td>
                                                <td><?=$query['kd'];?></td>
                                                <td><?=$query['kode_konstruksi'];?><input type="hidden" name="kons_kode[]" value="<?=$query['kode_konstruksi'];?>"></td>
                                                <td><?=$jml_rol;?> Roll</td>
                                                <td>
                                                    <?php 
                                                        if(fmod($query['ttl_panjang'], 1) !== 0.00){
                                                            $pjg = number_format($query['ttl_panjang'],2,',','.');
                                                        } else {
                                                            $pjg = number_format($query['ttl_panjang'],0,',','.');
                                                        }
                                                        echo $pjg;
                                                    ?>
                                                <input type="hidden" name="pkg_kode[]" value="<?=$dt[$i];?>"></td>
                                            </tr>
                                            <?php } } ?>
                                            <tr>
                                                <td colspan="3"><strong>Total</strong></td>
                                                <td><?=$total_roll;?> Roll</td>
                                                <td>
                                                    <?php 
                                                        if(fmod($total_panjang_semua, 1) !== 0.00){
                                                            $pjgall = number_format($total_panjang_semua,2,',','.');
                                                        } else {
                                                            $pjgall = number_format($total_panjang_semua,0,',','.');
                                                        }
                                                        echo $pjgall;
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                    <?php
                                    if (count(array_unique($ar_jns_paket)) === 1) {
                                        ?>
                                        <input type="hidden" name="thisjnspaket" value="<?=$ar_jns_paket[0];?>">
                                        <!-- echo "Semua nilai dalam array bernilai sama"; -->
                                        <button type="submit" class="btn" data-bgcolor="#3f72d1" data-color="#ffffff">
                                            <span class="icon-copy bi bi-send-plus"></span> &nbsp; Simpan & Kirim
                                        </button>
                                        <?php
                                    } else {
                                        echo "<code>Jenis paket ada yang siap jual dan ada yang proses produksi.</code>";
                                    }
                                    ?>
                                    
                                    
                            </div>
                            </form>
						</div>
					</div>
					<!-- Simple Datatable End -->
					
        </div>
    </div>
</div>
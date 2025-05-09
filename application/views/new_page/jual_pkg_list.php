<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header d-print-none">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Input Penjualan</h4>
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
											<a href="javascript:void(0);">Penjualan</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Input
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<?php 
                            $ex = explode('-', $dtkn['tanggal_dibuat']);
                            $printTgl = $ex[2]." ".$bln[$ex[1]]." ".$ex[0];
							?>
					<!-- Simple Datatable start -->
                    <div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								<strong>Kode Packinglist (<strong><?=$token;?></strong>)</strong>
							</p><small>Packinglist di buat tanggal <strong><?=$printTgl;?></strong></small>
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                                <form action="<?=base_url('kirim/proseskirim');?>" method="post" entype="multipart/form-data" name="f1">
                                <input type="hidden" name="token" value="<?=$token;?>">
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Kode Konstruksi</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:200px;" type="text" value="<?=$dtkn['kode_konstruksi'];?>" name="kd_kons" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Lokasi Paket</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:350px;" value="<?=$dtkn['lokasi_now'];?>" type="text" name="loc" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Total Ukuran</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:250px;" id="idUkuranTotal" value="0" type="text" name="totalukuran" readonly>
                                            <input type="hidden" name="satuan2" id="satuanidpost" value="0">
                                            <input type="hidden" name="ukuran2" id="satuanukuranpost" value="0">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Kirim ke Customer</label>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Nama Customer</label>
                                        <div class="col-sm-12 col-md-10" style="display:flex; align-items:center;">
                                            <input class="form-control" style="width:300px;" type="text" placeholder="Masukan nama customer" name="nama_custom" id="costumID" required>
                                            &nbsp;&nbsp;
                                            <span id="loader" style="display:none;"><img src="<?=base_url('assets/oval.svg');?>" style="width:20px;" ></span>
                                            <i class="icon-copy bi bi-check-circle-fill" style="display:none;color:green;" id="cekijo" title="Customer telah terdaftar di dalam sistem"></i>
                                        </div>
                                        
                                    </div>
                                    <input type="hidden" name="id_custom" id="idcustom" value="0">
                                    <div class="form-group row" id="nid1" style="display:none;">
                                        <label class="col-sm-12 col-md-2 col-form-label"></label>
                                        <div class="col-sm-12 col-md-10" style="color:red; font-size:12px;">
                                            Customer belum terdaftar di sistem, masukan alamat & nomor telepon.
                                        </div>
                                    </div>
                                    <div class="form-group row" id="nid2">
                                        <label class="col-sm-12 col-md-2 col-form-label">Nomor Telephone</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:250px;" name="notelp" id="notelid" value="0" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group row" id="nid3">
                                        <label class="col-sm-12 col-md-2 col-form-label">Alamat</label>
                                        <div class="col-sm-12 col-md-10">
                                            <textarea name="almt" id="almtcusid" class="form-control" style="width:400px;height:75px;"></textarea>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Data Roll</label>
                                        
                                    </div> -->
                                    <!-- <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">Kode Roll</th>
                                            <th colspan="3" style="text-align:center;">Ukuran</th>
                                            <th rowspan="2" class="d-print-none">Action</th>
                                        </tr>
                                        <tr>
                                            <th>Inspect Grey</th>
                                            <th>Inspect Finish</th>
                                            <th>Folding</th>
                                        </tr>
                                        </thead>
                                        <tbody> -->
                                        <?php 
                                            $query = $this->data_model->get_byid('new_tb_isi_paket',['id_kdlist'=>$token]);
                                            $ukuran_akhir = 0; $satuan_akhir = 0; $ukuran_akhir_total = 0;
                                            foreach($query->result() as $n => $val):
                                            $kdrol = $val->kode_roll;
                                            $cek_ig = $this->db->query("SELECT no_roll,ukuran_ori FROM new_tb_pkg_list WHERE no_roll='$kdrol'");
                                            if($cek_ig->num_rows()==1){ $_ig = $cek_ig->row("ukuran_ori")." m"; $ukuran_akhir=$cek_ig->row("ukuran_ori"); $satuan_akhir="m"; } else { $_ig='-'; }
                                            $cek_if = $this->db->query("SELECT no_roll,ukuran_ori_yard FROM new_tb_pkg_ins WHERE no_roll='$kdrol'");
                                            if($cek_if->num_rows()==1){ $_if = $cek_if->row("ukuran_ori_yard")." y"; $ukuran_akhir=$cek_if->row("ukuran_ori_yard"); $satuan_akhir="y"; } else { $_if='-'; }
                                            $cek_fol = $this->db->query("SELECT no_roll,ukuran,st_folding,ukuran_yard FROM new_tb_pkg_fol WHERE no_roll='$kdrol'");
                                            if($cek_fol->num_rows()==1){
                                                if($cek_fol->row("st_folding") == "Finish"){
                                                    $_fol = $cek_fol->row("ukuran_yard")." y";
                                                    $ukuran_akhir=$cek_fol->row("ukuran_yard"); $satuan_akhir="y";
                                                } else {
                                                    $_fol = $cek_fol->row("ukuran")." m";
                                                    $ukuran_akhir=$cek_fol->row("ukuran"); $satuan_akhir="m";
                                                }
                                            } else {
                                                $_fol = '-';
                                            }
                                            $ukuran_akhir_total = $ukuran_akhir_total + floatval($ukuran_akhir);
                                            ?>
                                            <input type="hidden" id="jumlahrow" value="<?=$query->num_rows();?>">
                                            <input type="hidden" name="satuanpost" id="satuanid" value="<?=$satuan_akhir;?>">
                                            <input type="hidden" value="<?=$ukuran_akhir;?>" id="ukuranRow<?=$n;?>">
                                            <?php
                                            endforeach;
                                        ?>
                                        <!-- <tbody>
                                    </table> -->
                                    
                                        <!-- <button type="button" class="btn d-print-none" data-bgcolor="#046e16" data-color="#ffffff" data-toggle="modal" data-target="#Medium-modal">
                                            <i class="icon-copy fa fa-plus-circle" aria-hidden="true"></i> &nbsp; Tambahkan Roll
                                        </button> -->
                                    
                                        <button type="submit" class="btn d-print-none" data-bgcolor="#3f72d1" data-color="#ffffff">
                                            <span class="icon-copy ti-save"></span> &nbsp; Simpan & Kirim
                                        </button>
                                </form>
                            </div>
						</div>
					</div>
					<!-- Bootstrap Select End -->
        </div>
    </div>
</div>
<script>
    var an = document.getElementById('jumlahrow').value;
    var satuanid = document.getElementById('satuanid').value;
    if(satuanid=='y') { var st = 'Yard'; } else { var st= 'Meter'; }
    var jumlah_semua = 0;
    for (let i = 0; i < an; i++) {
       var jumlah = document.getElementById('ukuranRow'+i+'').value;
       jumlah_semua = jumlah_semua + parseFloat(jumlah);
    }
    document.getElementById('idUkuranTotal').value = ''+jumlah_semua+' '+st+'';
    document.getElementById('satuanukuranpost').value = ''+jumlah_semua;
    document.getElementById('satuanidpost').value = ''+st;
</script>
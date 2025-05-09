<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								<strong>Data Operator</strong>
							</p><small>Menampilkan semua operator produksi di <strong><?=$sess_dep;?></strong></small>
                            <div class="pull-right">
								<a href="#basic-form1" class="btn btn-secondary btn-sm scroll-click" rel="content-y" role="button" data-toggle="modal" data-target="#Medium-modalnew1"><i class="bi bi-person-plus-fill"></i> Tambah Operator</a>
							</div>
						</div>
						<div class="pd-20 card-box mb-30">
                           <div class="table-responsive">
                            <table class="table table-hover">
                                <tr class="table-primary">
                                    <th>No.</th>
                                    <th>Username</th>
                                    <th>Nama Operator</th>
                                    <th>Tugas Operator</th>
                                    <th>#</th>
                                </tr>
                                <?php foreach($records->result() as $n => $val): ?>
                                <tr>
                                    <td><?=$n+1;?></td>
                                    <td><?=ucfirst($val->username);?></td>
                                    <td><?=$val->nama_opt;?></td>
                                    <td>
                                    <?php
                                        if($val->produksi == "insgrey") { echo "Inspect Grey"; }
                                        if($val->produksi == "insfinish") { echo "Inspect Finish"; }
                                        if($val->produksi == "folgrey") { echo "Folding Grey"; }
                                        if($val->produksi == "folfinish") { echo "Folding Finish"; }
                                        if($val->produksi == "penjualan") { echo "Packing Penjualan"; }
                                        if($val->produksi == "kirimpst") { echo "Kiriman ke Pusatex"; }
                                    ?>
                                    </td>
                                    <td>
                                        <span style="background:orange;color:#fff;cursor:pointer;padding:3px 10px;border-radius:3px;font-size:10px;" onclick="opdlopt('<?=$val->nama_opt;?>','<?=$val->username;?>','<?=$val->produksi;?>','<?=$val->id_operator;?>')" data-toggle="modal" data-target="#Medium-modalnew4">Edit</span>
                                        <span style="background:red;color:#fff;cursor:pointer;padding:3px 10px;border-radius:3px;font-size:10px;" onclick="delopt('<?=$val->nama_opt;?>','<?=$val->username;?>')" data-toggle="modal" data-target="#Medium-modalnew2">Hapus</span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                           </div>
						</div>
					</div>
					<!-- Simple Datatable End -->
                                <div class="modal fade" id="Medium-modalnew1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Tambah Operator
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            <form action="<?=base_url('operator/simpan');?>" method="post">
											<div class="modal-body">
												<label for="nama">Nama Operator</label>
                                                <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukan Nama Lengkap Operator" required>
                                                <br>
                                                <label for="username">Username Operator</label>
                                                <input type="text" id="useraname" name="username" class="form-control" placeholder="Masukan Username" required>
                                                <br>
                                                <label for="tugas">Tugas Operator</label>
                                                <select name="tugas" id="tugas" class="form-control" required>
                                                    <option value="">Pilih Tugas Operator</option>
                                                    <option value="insgrey">Inspect Grey</option>
                                                    <option value="insfinish">Inspect Finish</option>
                                                    <option value="folgrey">Folding Grey</option>
                                                    <option value="folfinish">Folding Finish</option>
                                                    <option value="penjualan">Packing Penjualan</option>
                                                    <option value="kirimpst">Kiriman ke Pusatex</option>
                                                </select>
                                                <input type="hidden" name="dep" value="<?=$sess_dep;?>" required>
											</div>
											<div class="modal-footer">
												<button type="submit" class="btn btn-primary">
													SIMPAN
												</button>
											</div>
                                            </form>
										</div>
									</div>
								</div>
                                <!-- Simple Datatable End -->
                                <div class="modal fade" id="Medium-modalnew2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Hapus Operator
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            <form action="<?=base_url('operator/delopt');?>" method="post">
											<div class="modal-body">
												Anda akan menghapus operator atas nama <span id="din_opt"></span> ?
                                                <input type="hidden" name="idopt" id="din_id" value="0">
											</div>
											<div class="modal-footer">
												<button type="submit" class="btn btn-danger">
													SIMPAN
												</button>
											</div>
                                            </form>
										</div>
									</div>
								</div>
                                <!-- Simple Datatable End -->
                                <div class="modal fade" id="Medium-modalnew4" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Edit Data Operator
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
                                            <form action="<?=base_url('operator/update');?>" method="post">
											<div class="modal-body">
                                                <input type="hidden" id="id4" name="idopt" value="0">
												<label for="nama">Nama Operator</label>
                                                <input type="text" id="nama4" name="nama" class="form-control" placeholder="Masukan Nama Lengkap Operator" required>
                                                <br>
                                                <label for="username">Username Operator</label>
                                                <input type="text" id="useraname4" name="username" class="form-control" placeholder="Masukan Username" required>
                                                <br>
                                                <label for="tugas">Tugas Operator</label>
                                                <select name="tugas" id="tugas4" class="form-control" required>
                                                    <option value="">Pilih Tugas Operator</option>
                                                    <option value="insgrey">Inspect Grey</option>
                                                    <option value="insfinish">Inspect Finish</option>
                                                    <option value="folgrey">Folding Grey</option>
                                                    <option value="folfinish">Folding Finish</option>
                                                    <option value="penjualan">Packing Penjualan</option>
                                                    <option value="kirimpst">Kiriman ke Pusatex</option>
                                                </select>
											</div>
											<div class="modal-footer">
												<button type="submit" class="btn btn-primary">
													SIMPAN
												</button>
											</div>
                                            </form>
										</div>
									</div>
								</div>
					
        </div>
    </div>
</div>
<script>
    function delopt(nama,user){
        document.getElementById('din_opt').innerHTML = '<strong>'+nama+'</strong>';
        document.getElementById('din_id').value = ''+user;
    }
    function opdlopt(nm,us,pr,id){
        document.getElementById('useraname4').value = ''+us;
        document.getElementById('nama4').value = ''+nm;
        document.getElementById('id4').value = ''+id;
        document.getElementById('tugas4').value = ''+pr;
    }
</script>
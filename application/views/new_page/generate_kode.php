<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header d-print-none">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Data Produksi</h4>
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
											<a href="javascript:void(0);">Produksi</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Generate Kode
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<!-- Simple Datatable start -->
					<!-- Select-2 Start -->
					<div class="pd-20 card-box mb-30">
						<div class="clearfix">
							<div class="pull-left d-print-none">
								<h4 class="text-blue h4">Generate Kode</h4>
								<p class="mb-30">Aplikasi ini akan menggenerate kode untuk setiap ROLL secara otomatis</p>
							</div>
                            <div class="text-right d-print-none">
								<div class="dropdown">
                                    <button type="button" class="btn" data-bgcolor="#e3e3e3" data-color="#000" data-toggle="modal"
									data-target="#Medium-modal">
                                        <i class="icon-copy bi bi-123"></i> &nbsp; Atur Penomoran
                                    </button>
									<button type="button" onclick="window.print();" class="btn btn-primary">
										<i class="icon-copy bi bi-printer-fill"></i> &nbsp; Cetak
                                    </button>
								</div>
							</div>
						</div>
                        <div class="clearfix table-responsive">
                            <table class="table table-bordered" style="border:1px solid #000000;">
                                <thead>
                                <tr>
                                    <th>Kode Roll</th>
                                    <th>Konstruksi</th>
                                    <th>No. Mesin</th>
                                    <th>No. Beam</th>
                                    <th>OKA</th>
                                    <th>Ukuran Ori</th>
                                    <th>BS</th>
                                    <th>BP</th>
                                    <th>Tanggal</th>
                                    <th>Operator</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if($nomor==0){
                                    $newnomor = $nomor+1;
                                    $end = $newnomor + 500;
                                } else {
                                    $newnomor = $nomor;
                                    $end = $newnomor + 500;
                                }
                                for ($i=$newnomor; $i < $end ; $i++) { ?>
                                <tr>
                                    <td><?php if($loc=="RJS"){ echo "R"; } else { echo "SC"; } echo $i;?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php } ?>
                                </tbody>
                            </table>
						</div>
					</div>
					<!-- Select-2 end -->
                                <div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Generate Kode
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<div class="modal-body">
												<p>Sistem akan menggenerate sejumlah 500 kode dalam sekali cetak. Masukan nilai awal untuk memulai penomoran generate kode.</p>
                                                <input type="number" id="ang" class="form-control" placeholder="Masukan angka...">
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">
													Close
												</button>
												<button type="button" onclick="redir()" class="btn btn-primary">
													Generate
												</button>
											</div>
										</div>
									</div>
								</div>
        </div>
    </div>
</div>
<script>
    function redir(){
        var n = document.getElementById('ang').value;
        let inp = document.querySelector('#ang');
        if(n==''){
            inp.classList.add('form-control-danger');
        } else {
            window.location = "<?=base_url('generate-kode');?>/"+n+"";
        }
    }
</script>
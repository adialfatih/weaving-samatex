<?php $arb = [ '00'=>'undefined', '01' => 'Jan', '02' => 'Feb', '03' => 'Mar','04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des']; 
$tgl = $this->uri->segment(2);
$ex = explode('-', $tgl);
$printTgl = $ex[2]." ".$arb[$ex[1]]." ".$ex[0];
?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Folding <?=$datafol;?></h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="index.html">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="index.html">Data Produksi</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Folding <?=$datafol;?>
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
								Data Produksi Folding <?=$datafol;?> Tanggal <?=$printTgl;?>
							</p>
						</div>
						<div class="pb-20">
							<table class="data-table table hover nowrap">
								<thead>
									<tr class="table-primary">
									<th class="table-plus datatable-nosort">No</th>
										<th>Kode Roll</th>
										<th>Konstruksi</th>
										<th>Ukuran</th>
										<th>Operator</th>
                                        <th>#</th>
									</tr>
								</thead>
								<tbody>
									<?php
                                    if($datafol=="Finish"){
                                        $query = $this->data_model->get_byid('data_fol',['jns_fold'=>'Finish','tgl'=>$tgl]);
                                    } else {
                                    $query = $this->data_model->get_byid('data_fol',['jns_fold'=>'Grey','tgl'=>$tgl]); }
                                    foreach($query->result() as $n => $val):
                                ?>
                                    <tr>
                                        <td><?=$n+1;?></td>
                                        <td><?=strtoupper($val->kode_roll);?></td>
                                        <td><?=$val->konstruksi;?></td>
                                        <td><?=$val->ukuran;?></td>
                                        <td><?=ucfirst($val->operator);?></td>
                                        <td>
                                            <a href="javascript:;" onclick="chdata('<?=strtoupper($val->kode_roll);?>','<?=$val->id_fol;?>')" style="color:red;" title="Hapus Data" data-toggle="modal" data-target="#Medium-modal">
                                            <i class="icon-copy fa fa-trash-o" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                <?php
                                    endforeach;
                                    ?>
								</tbody>
							</table>
						</div>
					</div>
					
					            <div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Hapus Data
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<div class="modal-body" id="mdlbodyid">
												<p>
													Lorem ipsum dolor sit amet, consectetur adipisicing
													elit, sed do eiusmod tempor incididunt ut labore et
													dolore magna aliqua. Ut enim ad minim veniam, quis
													nostrud exercitation ullamco laboris nisi ut aliquip
													ex ea commodo consequat. Duis aute irure dolor in
													reprehenderit in voluptate velit esse cillum dolore eu
													fugiat nulla pariatur. Excepteur sint occaecat
													cupidatat non proident, sunt in culpa qui officia
													deserunt mollit anim id est laborum.
												</p>
											</div>
											<div class="modal-footer">
                                                <form action="<?=base_url('produksistx/delfolgrey');?>" method="post">
                                                <input type="hidden" name="iddata" id="kdroll1">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">
													Close
												</button>
												<button type="submit" class="btn btn-danger">
													Delete
												</button></form>
											</div>
										</div>
									</div>
								</div>
                                
        </div>
    </div>
</div>
<script>
    function chdata(kd,id){
        document.getElementById('mdlbodyid').innerHTML = '<p>Anda akan menghapus hasil folding dari kode roll <strong>'+kd+'</strong>?</p>';
        document.getElementById('kdroll1').value = ''+id;
    }
    
</script>
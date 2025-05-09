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
									<h4>Inspect Grey</h4>
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
											Inspect Grey
										</li>
									</ol>
								</nav>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a class="btn btn-primary dropdown-toggle no-arrow" href="<?=base_url('proses-produksi');?>" role="button"> Tambah Inspect Grey </a>
								</div>
							</div>
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Data Produksi Inspect Grey Tanggal <?=$printTgl;?>
							</p>
						</div>
						<div class="pb-20">
							<table class="data-table table hover nowrap">
								<thead>
									<tr class="table-primary">
									<th class="table-plus datatable-nosort">No</th>
										<th>Kode Roll</th>
										<th>Konstruksi</th>
										<th>MC</th>
										<th>Beam</th>
										<th>OKA</th>
										<th>Ukuran ORI</th>
										<th>BP</th>
										<th>BS</th>
										<th>Operator</th>
                                        <th>#</th>
									</tr>
								</thead>
								<tbody>
									<?php 
                                    
                                    $query = $this->data_model->get_byid('data_ig',['tanggal'=>$tgl,'dep'=>$sess_dep]);
                                    foreach($query->result() as $n => $val):
                                ?>
                                    <tr>
                                        <td><?=$n+1;?></td>
                                        <td><?=$val->kode_roll;?></td>
                                        <td><?=$val->konstruksi;?></td>
                                        <td><?=$val->no_mesin;?></td>
                                        <td><?=$val->no_beam;?></td>
                                        <td><?=$val->oka;?></td>
                                        <td><?=$val->ukuran_ori;?></td>
                                        <td><?=$val->ukuran_bp;?></td>
                                        <td><?=$val->ukuran_bs;?></td>
                                        <td><?=ucfirst($val->operator);?></td>
                                        <td><a href="javascript:;" style="color:blue;" title="Edit Data" onclick="editdata('<?=$val->kode_roll;?>','<?=$val->konstruksi;?>','<?=$val->no_mesin;?>','<?=$val->no_beam;?>','<?=$val->oka;?>','<?=$val->ukuran_ori;?>','<?=$val->ukuran_bp;?>','<?=$val->ukuran_bs;?>')" data-toggle="modal" data-target="#Medium-modal2">
                                            <i class="icon-copy fa fa-edit" aria-hidden="true"></i></a>
                                            &nbsp;
                                            <a href="javascript:;" style="color:red;" onclick="chdata('<?=$val->kode_roll;?>','<?=$val->konstruksi;?>')" title="Hapus Data" data-toggle="modal" data-target="#Medium-modal">
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
                                                <form action="<?=base_url('produksistx/delinsgrey');?>" method="post">
                                                <input type="hidden" name="kdroll" id="kdroll1">
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
                                <div class="modal fade" id="Medium-modal2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
                                            <form action="<?=base_url('produksistx/prosesupdate');?>" method="post">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Edit Data
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<div class="modal-body">
												<table class="table">
                                                    <tr>
                                                        <td>Konstruksi</td>
                                                        <td>
                                                            <div class="autoComplete_wrapper" style="width:100%;">
                                                                <input id="autoComplete" style="width:100%;" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" placeholder="Kode konstruksi" name="kode" required>
                                                            </div>
                                                            <input type="hidden" id="konsbefore" name="konsbefore">
                                                            <input type="hidden" id="iptkoderoll" name="koderoll">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>MC</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="mc" id="iptmc">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Beam</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="beam" id="iptbeam">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>OKA</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="oka" id="iptoka">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Ukuran</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="ukuran" id="iptukuran">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>BS</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="bs" id="iptbs">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>BP</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="bp" id="iptbp">
                                                        </td>
                                                    </tr>
                                                </table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">
													Close
												</button>
												<button type="submit" class="btn btn-danger">
													Delete
												</button>
											</div>
                                            </from>
										</div>
									</div>
								</div>
        </div>
    </div>
</div>
<script>
    function chdata(kd,kons){
        document.getElementById('mdlbodyid').innerHTML = '<p>Anda akan menghapus kode roll <strong>'+kd+'</strong>? ini akan mengurangi proses produksi inspect grey untuk konstruksi <strong>'+kons+'</strong>';
        document.getElementById('kdroll1').value = ''+kd;
    }
    function editdata(kd,kons,mc,beam,oka,ori,bs,bp){
        document.getElementById('autoComplete').value = ''+kons;
        document.getElementById('konsbefore').value = ''+kons;
        document.getElementById('iptkoderoll').value = ''+kd;
        document.getElementById('iptmc').value = ''+mc;
        document.getElementById('iptbeam').value = ''+beam;
        document.getElementById('iptoka').value = ''+oka;
        document.getElementById('iptukuran').value = ''+ori;
        document.getElementById('iptbs').value = ''+bs;
        document.getElementById('iptbp').value = ''+bp;
    }
</script>
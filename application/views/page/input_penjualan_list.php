<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Input Data Penjualan</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Input Data</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Penjualan
										</li>
									</ol>
								</nav>
							</div>
							<!-- <div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a class="btn btn-primary dropdown-toggle no-arrow" href="javascript:void(0);" role="button" data-toggle="modal" data-target="#bd-example-modal-lg">
										Tambah Penjualan
									</a>
									
								</div>
							</div> -->
						</div>
					</div>
					<?php 
                            $warning = $this->session->flashdata('warning');
                            $id_konsumen = $this->session->flashdata('id_konsumen');
                            $jumlah = $this->session->flashdata('jumlah');
                            $konsumen = $this->session->flashdata('konsumen');
                            $notlis = $this->session->flashdata('notlis');
                            $kode_konstruksi = $this->session->flashdata('kode_konstruksi');
                            $tanggal = $this->session->flashdata('tanggal');
                            if(!empty($tanggal)){
                            $ex = explode('-', $tanggal);
                            $print_tgl = $ex[2]." ".$bln[$ex[1]]." ".$ex[0].""; }
                            
                            if(!empty($warning)){
                                echo '
								<div
									class="alert alert-warning alert-dismissible fade show"
									role="alert"
								>
									'.$warning.'
									<button
										type="button"
										class="close"
										data-dismiss="alert"
										aria-label="Close"
									>
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
                                ';
                            }
							?>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								DATA PENJUALAN
							</p>
                            <br>
                            <?php if(empty($id_konsumen)){
                                echo "<code>Proses penjualan gagal mohon ulangi proses</code>";
                            } else { 
                                
                                $cek_pkg = $this->data_model->get_byid('v_pkg', ['kode_konstruksi'=>$kode_konstruksi, 'lokasi_saat_ini'=>$dep_user]);     
                            ?>
                            <form action="<?=base_url('proses/sellwithlist');?>" method="post" entype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                            <table class="table">
                                <tr>
                                    <td width="200px">Tanggal</td>
                                    <td width="50px">:</td>
                                    <th>
                                        <?=$print_tgl;?>
                                        <input type="hidden" name="tb_tgl" value="<?=$tanggal;?>">
                                    </th>
                                </tr>
                                <tr>
                                    <td>Kode Konstruksi</td>
                                    <td>:</td>
                                    <th>
                                        <?=$kode_konstruksi;?>
                                        <input type="hidden" name="tb_kode" value="<?=$kode_konstruksi;?>">
                                    </th>
                                </tr>
                                <tr>
                                    <td>Nama Konsumen</td>
                                    <td>:</td>
                                    <th>
                                        <?=$konsumen;?>
                                        <input type="hidden" name="tb_konsum" value="<?=$konsumen;?>">
                                    </th>
                                </tr>
                                <?php
                                    if($id_konsumen=="kosong"){ ?>
                                <tr>
                                    <td>Nomor Telepon</td>
                                    <td>:</td>
                                    <th>
                                        <input type="text" name="tb_nohp" placeholder="Masukan nomor hp konsumen" class="form-control" value="62" style="width:250px;">
                                        <input type="hidden" name="tb_idkonsum" value="kosong">
                                    </th>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:</td>
                                    <th>
                                        <input type="text" name="tb_almt" placeholder="Masukan alamat konsumen" class="form-control">
                                    </th>
                                </tr>
                                <?php
                                    } else {
                                        //echo "".$id_konsumen;
                                        echo '<input type="hidden" name="tb_idkonsum" value="'.$id_konsumen.'">';
                                    }
                                ?>
                                <tr>
                                    <td>Jumlah Penjualan List</td>
                                    <td>:<input type="hidden" name="tb_jumlah" value="<?=$jumlah;?>"></td>
                                    <th id="idjumlahawal"><?=$notlis=="true" ?'0':$jumlah;?></th>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <p><strong>Pilih barang dalam packing list</strong></p>
                                        <select id='pre-selected-options' multiple='multiple' name="slect[]">
                                            <?php
                                                foreach($cek_pkg->result() as $val):
                                                    echo '<optgroup label="No Packing List : '.$val->kode_produksi.'">';
                                                    $nopkg = $val->no_pkg;
                                                    $jml_Stok = $this->data_model->get_byid('new_tb_pkg_fol', ['id_effected_row'=>$nopkg]);
                                                    foreach($jml_Stok->result() as $val2):
                                                        if($val2->st_folding=="Finish"){
                                                            if($val2->ukuran_now_yard > 0){ ?>
                                                            <option value="<?=$val2->id_fol;?>">No Roll : <?=$val2->no_roll;?>, Ukuran : <?=$val2->ukuran_now_yard;?> y</option><?php }
                                                        } else {
                                                            if($val2->ukuran_now > 0){ ?>
                                                            <option value="<?=$val2->id_fol;?>">No Roll : <?=$val2->no_roll;?>, Ukuran : <?=$val2->ukuran_now;?> m</option><?php }
                                                        }
                                                    endforeach;
                                                    echo '</optgroup>';
                                                endforeach;
                                            ?>
                                        </select>

                                    </td>
                                </tr>
                                <?php if($notlis=="true"){ ?>
                                <tr>
                                    <td>Satuan</td>
                                    <td>:</td>
                                    <td>
                                        <select name="satuan" id="satuanid" class="form-control" style="width:200px;">
                                            <option value="">Masukan Satuan</option>
                                            <option value="Yard">Yard</option>
                                            <option value="Meter">Meter</option>
                                        </select>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr id="tombolUtama">
                                    <td colspan="3">
                                        <a href="javascript:void(0);" style="color:#3477eb;">+ Tambahkan Barang diluar packing list</a>
                                    </td>
                                </tr>
                                
                                <tr style="display:none;" id="show1">
                                    <td colspan="3">
                                        <table>
                                            <tr>
                                                <th>1</th>
                                                <td><input type="text" class="form-control" name="noroll[]" placeholder="Masukan no Roll"></td>
                                                <td><input type="text" class="form-control" name="ukuran[]" id="ukuran1" id="ukuran1" placeholder="Masukan ukuran"></td>
                                                <td><a href="javascript:void(0);" id="btnshow1"><i class="icon-copy bi bi-plus-square"></i></a></td>
                                            </tr>
                                            <tr style="display:none;" id="show2">
                                                <th>2</th>
                                                <td><input type="text" class="form-control" name="noroll[]" placeholder="Masukan no Roll"></td>
                                                <td><input type="text" class="form-control" name="ukuran[]" id="ukuran2" placeholder="Masukan ukuran"></td>
                                                <td><a href="javascript:void(0);" id="btnshow2"><i class="icon-copy bi bi-plus-square"></i></a></td>
                                            </tr>
                                            <tr style="display:none;" id="show3">
                                                <th>3</th>
                                                <td><input type="text" class="form-control" name="noroll[]" placeholder="Masukan no Roll"></td>
                                                <td><input type="text" class="form-control" name="ukuran[]" id="ukuran3" placeholder="Masukan ukuran"></td>
                                                <td><a href="javascript:void(0);" id="btnshow3"><i class="icon-copy bi bi-plus-square"></i></a></td>
                                            </tr>
                                            <tr style="display:none;" id="show4">
                                                <th>4</th>
                                                <td><input type="text" class="form-control" name="noroll[]" placeholder="Masukan no Roll"></td>
                                                <td><input type="text" class="form-control" name="ukuran[]" id="ukuran4" placeholder="Masukan ukuran"></td>
                                                <td><a href="javascript:void(0);" id="btnshow4"><i class="icon-copy bi bi-plus-square"></i></a></td>
                                            </tr>
                                            <tr style="display:none;" id="show5">
                                                <th>5</th>
                                                <td><input type="text" class="form-control" name="noroll[]" placeholder="Masukan no Roll"></td>
                                                <td><input type="text" class="form-control" name="ukuran[]" id="ukuran5" placeholder="Masukan ukuran"></td>
                                                <td><a href="javascript:void(0);" id="btnshow5"><i class="icon-copy bi bi-plus-square"></i></a></td>
                                            </tr>
                                            <tr style="display:none;" id="show6">
                                                <th>6</th>
                                                <td><input type="text" class="form-control" name="noroll[]" placeholder="Masukan no Roll"></td>
                                                <td><input type="text" class="form-control" name="ukuran[]" id="ukuran6" placeholder="Masukan ukuran"></td>
                                                <td><a href="javascript:void(0);" id="btnshow6"><i class="icon-copy bi bi-plus-square"></i></a></td>
                                            </tr>
                                            <tr style="display:none;" id="show7">
                                                <th>7</th>
                                                <td><input type="text" class="form-control" name="noroll[]" placeholder="Masukan no Roll"></td>
                                                <td><input type="text" class="form-control" name="ukuran[]" id="ukuran7" placeholder="Masukan ukuran"></td>
                                                <td><a href="javascript:void(0);" id="btnshow7"><i class="icon-copy bi bi-plus-square"></i></a></td>
                                            </tr>
                                            <tr style="display:none;" id="show8">
                                                <th>8</th>
                                                <td><input type="text" class="form-control" name="noroll[]" placeholder="Masukan no Roll"></td>
                                                <td><input type="text" class="form-control" name="ukuran[]" id="ukuran8" placeholder="Masukan ukuran"></td>
                                                <td><a href="javascript:void(0);" id="btnshow8"><i class="icon-copy bi bi-plus-square"></i></a></td>
                                            </tr>
                                            <tr style="display:none;" id="show9">
                                                <th>9</th>
                                                <td><input type="text" class="form-control" name="noroll[]" placeholder="Masukan no Roll"></td>
                                                <td><input type="text" class="form-control" name="ukuran[]" id="ukuran9" placeholder="Masukan ukuran"></td>
                                                <td><a href="javascript:void(0);" id="btnshow9"><i class="icon-copy bi bi-plus-square"></i></a></td>
                                            </tr>
                                            <tr style="display:none;" id="show10">
                                                <th>10</th>
                                                <td><input type="text" class="form-control" name="noroll[]" placeholder="Masukan no Roll"></td>
                                                <td><input type="text" class="form-control" name="ukuran[]" id="ukuran10" placeholder="Masukan ukuran"></td>
                                                <td></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr style="display:none;" id="idtotaljual">
                                    <td>Total Jumlah Penjualan</td>
                                    <td>:</td>
                                    <th id="idpenjualan_akhir"></th>
                                </tr>
                            </table>
                            <input type="hidden" id="idpilihan" value="0" name="pilihanNilai">
                            <input type="hidden" id="idtambahan" value="0" name="pilihanSL">
                            <p>&nbsp;</p>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                            <?php } ?>
						</div>
                        
					</div>
                    
					<!-- Simple Datatable End -->
					
        </div>
    </div>
</div>

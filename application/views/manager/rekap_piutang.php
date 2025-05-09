<?php 
$blns = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des' ];
$bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember' ];
$today = date('D M y, H:i');
$today_date = date('Y-m-d');
$before = date('Y-m-d',strtotime($today_date . "-1 days"));
$kemarin = date('D', strtotime($before));
//echo $today."<br>".$today_date;
$ex = explode(' ',$today);
$day_name = $ex[0];
//echo "<br>".$day_name;
$day_ar = [ 'Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa', 'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu' ];
$print_hari = $day_ar[$day_name];
//echo "<br>".$print_hari;
$exdate = explode('-',$today_date);
$exdate2 = explode('-',$before);
$print_tanggal = $exdate[2]." ".$bln[$exdate[1]]." ".$exdate[0];
$print_tanggal_kemarin = $exdate2[2]." ".$bln[$exdate2[1]]." ".$exdate2[0];
//echo "<br>".$print_tanggal;
?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								<strong>Tampilkan Laporan Rekap Penjualan tes</strong>
							</p><small>Tampilkan laporan rekap penjualan berdasarkan tanggal</small>
                            
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                                <form action="<?=base_url('report-penjualan');?>" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-12 col-md-2 col-form-label">Tanggal</label>
                                    <div class="col-sm-12 col-md-10">
                                        <input name="datesr" class="form-control" style="width:300px;">
                                    </div>
                                    
                                </div>
                                <div class="form-group row">
                                    
                                    <label class="col-sm-12 col-md-2 col-form-label" for="autoComplete">Customer</label>
                                    <div class="col-sm-12 col-md-10">
                                        
                                        <div class="autoComplete_wrapper">
                                            <input id="autoComplete" name="cust" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <button type="submit" class="btn btn-success">
                                    <span class="icon-copy ti-search"></span> &nbsp; Lihat Laporan
                                </button>
                                </form>
                               
                            </div>
						</div>
					</div>
					<!-- Simple Datatable End -->
					<!-- Simple Datatable start -->
          <?php
          if($showcus == "Semua Customer"){
            $showcusoke = "ke semua customer";
          } else {
            $showcusoke = "ke ".$showcus;
          }
          ?>
          <div class="card-box mb-30">
						<div class="pd-20" style="width:100%;display:flex;justify-content:space-between;">
							<p class="mb-0">
								<strong>Rekap Nota Penjualan</strong><br>
                <?php if($tanggalRange == "yes"){
                  $et1 = explode('-', $tanggal1);
                  $printTanggalAwal = $et1[2]." ".$bln[$et1[1]]." ".$et1[0];
                  $et2 = explode('-', $tanggal2);
                  $printTanggalAkhir = $et2[2]." ".$bln[$et2[1]]." ".$et2[0];
                  echo "<small>";
                  if($printTanggalAkhir == $printTanggalAwal){
                    echo "Menampilkan laporan penjualan $showcusoke tanggal <strong>$printTanggalAwal</strong>";
                  } else {
                    echo "Menampilkan laporan penjualan $showcusoke dari tanggal <strong>$printTanggalAwal</strong>";
                    echo "s/d <strong>$printTanggalAkhir</strong>";
                  }
                  echo "</small>";
                } else { ?>
                <small><?="Menampilkan 500 Data Terakhir, ".$print_hari.", ".$print_tanggal;?></small> <?php } ?>
							</p>
              <form action="<?=base_url('exportexcel/laporanjual');?>" method="post">
                  <input type="hidden" name="txtquery" value="<?=$qrdata_txt;?>">
                  <input type="hidden" name="tanggalRange" value="<?=$tanggalRange;?>">
                  <?php if($tanggalRange=="yes"){ ?>
                    <input type="hidden" name="tanggal1" value="<?=$printTanggalAwal;?>">
                    <input type="hidden" name="tanggal2" value="<?=$printTanggalAkhir;?>">
                  <?php } ?>
                  <button type="submit" class="btn btn-primary">
                      <span class="icon-copy ti-download"></span> &nbsp; Download Laporan
                  </button>
              </form>          
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                                
                               <div class="table-responsive" style="font-size:14px;">
                               <table class="data-table table stripe hover nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Nama Customer</th>
                                            <th>No. Faktur</th>
                                            <th>Jenis Barang</th>
                                            <th>Panjang</th>
                                            <th>Satuan</th>
                                            <th>Harga</th>
                                            <th>Total Harga</th>
                                            <!-- <th class="datatable-nosort">#</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total_finish = 0; $total_grey = 0; $ar_jual=array();
                                        $sum_panjang=0;
                                        $sum_total_harga=0;
                                        foreach($qrdata->result() as $n => $val):
                                            $ex = explode('-', $val->tgl_nota);
                                            $tgl = $ex[2]."-".$blns[$ex[1]]."-".$ex[0];
                                            $sj = $val->no_sj;
                                            $exsj = explode('/', $sj);
                                            $noFaktur = "J".$exsj[3]."".$exsj[0];
                                            $idcus = $this->data_model->get_byid('surat_jalan',['no_sj'=>$sj])->row("id_customer");
                                            $nmcus = $this->data_model->get_byid('dt_konsumen',['id_konsumen'=>$idcus])->row("nama_konsumen");
                                            $kdpkg = $val->kd;
                                            $fold = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kdpkg])->row("jns_fold");
                                            if($fold == "Grey"){
                                                $total_grey+=floatval($val->total_panjang);
                                            } else {
                                                $total_finish+=floatval($val->total_panjang);
                                            }
                                            $txt = $val->konstruksi."-".$val->total_panjang."-".$fold;
                                            $ar_jual[] = $txt;
                                        ?>
                                        <tr>
                                            <td class="table-plus"><?=$n+1;?></td>
                                            <td><?=$tgl;?></td>
                                            <td><?=$nmcus;?></td>
                                            <td><a href="javascript:;" data-toggle="modal" data-target="#Medium-modal" onclick="lihatSj('<?=$sj;?>')"><?=$noFaktur;?></a></td>
                                            <td><?=$val->konstruksi;?></td>
                                            <td>
                                                <?php $sum_panjang+=$val->total_panjang;
                                                  if(fmod($val->total_panjang, 1) !== 0.00){
                                                    echo number_format($val->total_panjang,2,',','.');
                                                  } else {
                                                    echo number_format($val->total_panjang,0,',','.');
                                                  }
                                                ?>
                                            </td>
                                            <td><?=$fold=="Grey"?"Meter":"Yard";?></td>
                                            <td>
                                                <?php
                                                  if(fmod($val->harga_satuan, 1) !== 0.00){
                                                    echo number_format($val->harga_satuan,2,',','.');
                                                  } else {
                                                    echo number_format($val->harga_satuan,0,',','.');
                                                  }
                                                ?>
                                            </td>
                                            <td>
                                                <?php $sum_total_harga+=$val->total_harga;
                                                  if(fmod($val->total_harga, 1) !== 0.00){
                                                    echo "Rp. ".number_format($val->total_harga,2,',','.');
                                                  } else {
                                                    echo "Rp. ".number_format($val->total_harga,0,',','.');
                                                  }
                                                ?>
                                            </td>
                                            <!-- <td>
                                                <div class="dropdown">
                                                    <a
                                                        class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                        href="#"
                                                        role="button"
                                                        data-toggle="dropdown"
                                                    >
                                                        <i class="dw dw-more"></i>
                                                    </a>
                                                    <div
                                                        class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
                                                    >
                                                        <a class="dropdown-item" href="#"
                                                            ><i class="dw dw-eye"></i> View</a
                                                        >
                                                        <a class="dropdown-item" href="#"
                                                            ><i class="dw dw-edit2"></i> Edit</a
                                                        >
                                                        <a class="dropdown-item" href="#"
                                                            ><i class="dw dw-delete-3"></i> Delete</a
                                                        >
                                                    </div>
                                                </div>
                                            </td> -->
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-primary">
                                            <td colspan="2">Total</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><?php 
                                                  if(fmod($sum_panjang, 1) !== 0.00){
                                                    echo "".number_format($sum_panjang,2,',','.');
                                                  } else {
                                                    echo "".number_format($sum_panjang,0,',','.');
                                                  }
                                                ?></td>
                                            <td></td>
                                            <td></td>
                                            <td><?php 
                                                  if(fmod($sum_total_harga, 1) !== 0.00){
                                                    echo "Rp. ".number_format($sum_total_harga,2,',','.');
                                                  } else {
                                                    echo "Rp. ".number_format($sum_total_harga,0,',','.');
                                                  }
                                                ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                               </div>
                            </div>
						</div>
					</div>
					<!-- Simple Datatable start -->
          <div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								<strong>Rekap Penjualan</strong>
							</p>   
						</div>
						<div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                              <td>
                                <?php
                                  if(fmod($total_grey, 1) !== 0.00){
                                      echo "Total Penjualan Grey : <strong>".number_format($total_grey,2,',','.')."</strong> Meter";
                                  } else {
                                      echo "Total Penjualan Grey : <strong>".number_format($total_grey,0,',','.')."</strong> Meter";
                                  }
                                ?>
                              </td>
                              <td>
                                <?php
                                  if(fmod($total_finish, 1) !== 0.00){
                                      echo "Total Penjualan Finish : <strong>".number_format($total_finish,2,',','.')."</strong> Yard";
                                  } else {
                                      echo "Total Penjualan Finish : <strong>".number_format($total_finish,0,',','.')."</strong> Yard";
                                  }
                                ?>
                              </td>
                            </tr>
                            <?php if($tanggalRange=="yes"){ ?>
                              <tr>
                                <td style="vertical-align:top;">
                                    <?php 
                                     $nquery = "SELECT a_nota.id_nota, a_nota.no_sj, a_nota.kd, a_nota.konstruksi, a_nota.jml_roll, a_nota.total_panjang, a_nota.tgl_nota, new_tb_packinglist.kd, new_tb_packinglist.tanggal_dibuat, new_tb_packinglist.ttl_panjang, new_tb_packinglist.jns_fold FROM a_nota,new_tb_packinglist WHERE a_nota.kd = new_tb_packinglist.kd AND new_tb_packinglist.jns_fold='Grey' AND a_nota.tgl_nota BETWEEN '$tanggal1' AND '$tanggal2'";
                                     $nquery_exc = $this->db->query($nquery);
                                     $ar_kons1 = array();
                                     foreach ($nquery_exc->result() as $key => $value) {
                                        //echo "".$value->konstruksi." - ".$value->total_panjang."<br>";
                                        if (in_array($value->konstruksi, $ar_kons1)) {
                                            
                                        } else {
                                            $ar_kons1[] = $value->konstruksi;
                                        }
                                     }
                                     foreach($ar_kons1 as $kons){
                                        $sumqr = $this->db->query("SELECT SUM(total_panjang) as ukr FROM a_nota WHERE konstruksi='$kons' AND tgl_nota BETWEEN '$tanggal1' AND '$tanggal2'")->row("ukr");
                                        //echo "$kons : $sumqr<br>";
                                        if(fmod($sumqr, 1) !== 0.00){
                                          echo "$kons : <strong>".number_format($sumqr,2,',','.')."</strong> <br>";
                                        } else {
                                          echo "$kons : <strong>".number_format($sumqr,0,',','.')."</strong> <br>";
                                        }
                                     }
                                    ?>
                                </td>
                                <td style="vertical-align:top;">
                                    <?php 
                                     $nquery = "SELECT a_nota.id_nota, a_nota.no_sj, a_nota.kd, a_nota.konstruksi, a_nota.jml_roll, a_nota.total_panjang, a_nota.tgl_nota, new_tb_packinglist.kd, new_tb_packinglist.tanggal_dibuat, new_tb_packinglist.ttl_panjang, new_tb_packinglist.jns_fold FROM a_nota,new_tb_packinglist WHERE a_nota.kd = new_tb_packinglist.kd AND new_tb_packinglist.jns_fold='Finish' AND a_nota.tgl_nota BETWEEN '$tanggal1' AND '$tanggal2'";
                                     $nquery_exc = $this->db->query($nquery);
                                     $ar_kons2 = array();
                                     foreach ($nquery_exc->result() as $key => $value) {
                                        //echo "".$value->konstruksi." - ".$value->total_panjang."<br>";
                                        if (in_array($value->konstruksi, $ar_kons2)) {
                                            
                                        } else {
                                            $ar_kons2[] = $value->konstruksi;
                                        }
                                     }
                                     foreach($ar_kons2 as $kons){
                                        $sumqr = $this->db->query("SELECT SUM(total_panjang) as ukr FROM a_nota WHERE konstruksi='$kons' AND tgl_nota BETWEEN '$tanggal1' AND '$tanggal2'")->row("ukr");
                                        //echo "$kons : $sumqr<br>";
                                        if(fmod($sumqr, 1) !== 0.00){
                                          echo "$kons : <strong>".number_format($sumqr,2,',','.')."</strong> <br>";
                                        } else {
                                          echo "$kons : <strong>".number_format($sumqr,0,',','.')."</strong> <br>";
                                        }
                                     }
                                    ?>
                                </td>
                            </tr>
                            <?php } else {  ?>
                            <tr>
                                <td style="vertical-align:top;">
                                    <?php 
                                     $nquery = "SELECT a_nota.id_nota, a_nota.no_sj, a_nota.kd, a_nota.konstruksi, a_nota.jml_roll, a_nota.total_panjang, a_nota.tgl_nota, new_tb_packinglist.kd, new_tb_packinglist.tanggal_dibuat, new_tb_packinglist.ttl_panjang, new_tb_packinglist.jns_fold FROM a_nota,new_tb_packinglist WHERE a_nota.kd = new_tb_packinglist.kd AND new_tb_packinglist.jns_fold='Grey'";
                                     $nquery_exc = $this->db->query($nquery);
                                     $ar_kons1 = array();
                                     foreach ($nquery_exc->result() as $key => $value) {
                                        //echo "".$value->konstruksi." - ".$value->total_panjang."<br>";
                                        if (in_array($value->konstruksi, $ar_kons1)) {
                                            
                                        } else {
                                            $ar_kons1[] = $value->konstruksi;
                                        }
                                     }
                                     foreach($ar_kons1 as $kons){
                                        $sumqr = $this->db->query("SELECT SUM(total_panjang) as ukr FROM a_nota WHERE konstruksi='$kons'")->row("ukr");
                                        //echo "$kons : $sumqr<br>";
                                        if(fmod($sumqr, 1) !== 0.00){
                                          echo "$kons : <strong>".number_format($sumqr,2,',','.')."</strong> <br>";
                                        } else {
                                          echo "$kons : <strong>".number_format($sumqr,0,',','.')."</strong> <br>";
                                        }
                                     }
                                    ?>
                                </td>
                                <td style="vertical-align:top;">
                                    <?php 
                                     $nquery = "SELECT a_nota.id_nota, a_nota.no_sj, a_nota.kd, a_nota.konstruksi, a_nota.jml_roll, a_nota.total_panjang, a_nota.tgl_nota, new_tb_packinglist.kd, new_tb_packinglist.tanggal_dibuat, new_tb_packinglist.ttl_panjang, new_tb_packinglist.jns_fold FROM a_nota,new_tb_packinglist WHERE a_nota.kd = new_tb_packinglist.kd AND new_tb_packinglist.jns_fold='Finish'";
                                     $nquery_exc = $this->db->query($nquery);
                                     $ar_kons2 = array();
                                     foreach ($nquery_exc->result() as $key => $value) {
                                        //echo "".$value->konstruksi." - ".$value->total_panjang."<br>";
                                        if (in_array($value->konstruksi, $ar_kons2)) {
                                            
                                        } else {
                                            $ar_kons2[] = $value->konstruksi;
                                        }
                                     }
                                     foreach($ar_kons2 as $kons){
                                        $sumqr = $this->db->query("SELECT SUM(total_panjang) as ukr FROM a_nota WHERE konstruksi='$kons'")->row("ukr");
                                        //echo "$kons : $sumqr<br>";
                                        if(fmod($sumqr, 1) !== 0.00){
                                          echo "$kons : <strong>".number_format($sumqr,2,',','.')."</strong><br>";
                                        } else {
                                          echo "$kons : <strong>".number_format($sumqr,0,',','.')."</strong><br>";
                                        }
                                     }
                                    ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>                              
                </div>
						</div>
					</div>
					<!-- Simple Datatable End -->
          <!-- Medium modal -->
						<div class="col-md-4 col-sm-12 mb-30">
							<div class="pd-20 card-box height-100-p">
								
								<div
									class="modal fade"
									id="Medium-modal"
									tabindex="-1"
									role="dialog"
									aria-labelledby="myLargeModalLabel"
									aria-hidden="true"
								>
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Data Packinglist
												</h4>
												<button
													type="button"
													class="close"
													data-dismiss="modal"
													aria-hidden="true"
												>
													×
												</button>
											</div>
											<div class="modal-body" id="dataModal">
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
												<button
													type="button"
													class="btn btn-secondary"
													data-dismiss="modal"
												>
													Close
												</button>
												<button type="button" class="btn btn-primary">
													Save changes
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
        </div>
    </div>
</div>

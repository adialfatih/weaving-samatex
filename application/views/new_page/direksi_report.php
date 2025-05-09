<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; 
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
if($frmdep=="null" AND $frmtgl1=="null" AND $frmtgl2=="null"){
    $qr_mesin = $this->data_model->get_byid('dt_produksi_mesin', ['tanggal_produksi'=>$before]);
    $qr_produksi = $this->data_model->get_byid('data_produksi', ['tgl'=>$before]);
} else {
    $qr_mesin = $this->db->query("SELECT * FROM dt_produksi_mesin WHERE lokasi = '$frmdep' AND tanggal_produksi BETWEEN '$frmtgl1' AND '$frmtgl2'");
    $qr_produksi = $this->db->query("SELECT * FROM data_produksi WHERE dep = '$frmdep' AND tgl BETWEEN '$frmtgl1' AND '$frmtgl2'");
    $exdate = explode('-',$frmtgl1);
    $exdate2 = explode('-',$frmtgl2);
    $print_tanggal1 = $exdate[2]." ".$bln[$exdate[1]]." ".$exdate[0];
    $print_tanggal2 = $exdate2[2]." ".$bln[$exdate2[1]]." ".$exdate2[0];
}
$qr_stok_rj = $this->data_model->get_byid('data_stok', ['dep'=>'RJS']);
$qr_stok_st = $this->data_model->get_byid('data_stok', ['dep'=>'Samatex']);
?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					
					<!-- Simple Datatable start -->
                    <div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								<strong>Laporan Produksi Mesin</strong>
							</p>
                            <?php if($frmdep=="null"){ ?>
                            <small>Menampilkan laporan produksi mesin hari <strong><?=$day_ar[$kemarin];?></strong>, <strong><?=$print_tanggal_kemarin;?></strong></small>
                            <?php } else { ?>
                            <small>Menampilkan laporan produksi mesin dari tanggal <strong><?=$print_tanggal1;?></strong> s/d  <strong><?=$print_tanggal2;?></strong></small>
                            <?php } ?>
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                               <div class="table-responsive" style="font-size:14px;">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                        <tr class="table-secondary">
                                            <th>No.</th>
                                            <th>Konstruksi</th>
                                            <th>Departement</th>
                                            <th>Jumlah Mesin</th>
                                            <th>Jumlah Produksi</th>
                                            <?php if($frmdep!="null"){ echo "<th>Tanggal</th>"; } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $total=0; $ttl_mesin=0; foreach($qr_mesin->result() as $n => $val): 
                                        $ttl_mesin+=$val->jumlah_mesin;
                                        ?>
                                        <tr>
                                            <td><?=$n+1;?></td>
                                            <td><?=$val->kode_konstruksi;?></td>
                                            <td><?=$val->lokasi;?></td>
                                            <td><?=$val->jumlah_mesin;?></td>
                                            <td>
                                                <?php $total+=$val->hasil;
                                                    if(fmod($val->hasil, 1) !== 0.00){
                                                        $hasil = number_format($val->hasil,2,',','.');
                                                      } else {
                                                        $hasil = number_format($val->hasil,0,',','.');
                                                      }
                                                      echo $hasil;
                                                ?>
                                            </td>
                                            <?php if($frmdep!="null"){ 
                                                $ix = explode('-',$val->tanggal_produksi);
                                                echo "<td>";
                                                echo $ix[2]." ".$bln[$ix[1]]." ".$ix[0];
                                                echo "</td>";
                                             } ?>
                                        </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-primary">
                                                <th colspan="3">Total</th>
                                                <th><?=$ttl_mesin;?></th>
                                                <th>
                                                <?php 
                                                    if(fmod($total, 1) !== 0.00){
                                                        $ptotal = number_format($total,2,',','.');
                                                      } else {
                                                        $ptotal = number_format($total,0,',','.');
                                                      }
                                                      echo $ptotal;
                                                      if($total > 0 AND $ttl_mesin >0){
                                                      $tml_hasil = $total / $ttl_mesin;
                                                      } else {$tml_hasil=0;}
                                                      if(fmod($tml_hasil, 1) !== 0.00){
                                                        $tml_hasil = number_format($tml_hasil,2,',','.');
                                                      } else {
                                                        $tml_hasil = number_format($tml_hasil,0,',','.');
                                                      }
                                                ?>
                                                </th>
                                                <?php if($frmdep!="null"){ echo"<th></th>"; }?>
                                            </tr>
                                            <tr class="table-primary">
                                                <td colspan="6">Rata - rata Produksi per mesin : <strong><?=$ptotal."</strong> / <strong>".$ttl_mesin."</strong> = <strong>".$tml_hasil."</strong>";?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                               </div>
                            </div>
						</div>
					</div>
					<!-- Simple Datatable start -->
                    <!-- Simple Datatable start -->
                    <div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								<strong>Laporan Produksi & Penjualan</strong>
							</p>
                            <?php if($frmdep=="null"){ ?>
                                <small>Menampilkan laporan produksi dan penjualan berdasarkan konstruksi hari <strong><?=$day_ar[$kemarin];?></strong>, <strong><?=$print_tanggal_kemarin;?></strong></small>
                            <?php } else { ?>
                                <small>Menampilkan laporan produksi dan penjualan berdasarkan konstruksi dari tanggal <strong><?=$print_tanggal1;?></strong> s/d <strong><?=$print_tanggal2;?></strong></small>
                            <?php } ?>
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                               <div class="table-responsive" style="font-size:14px;">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                        <tr class="table-secondary">
                                            <th>No.</th>
                                            <th>Konstruksi</th>
                                            <th>Departement</th>
                                            <th>Inspect Grey</th>
                                            <th>Folding Grey</th>
                                            <th>Inpsect Finish</th>
                                            <th>Folding Finish</th>
                                            <th>Penjualan</th>
                                            <?php if($frmdep!="null"){ echo "<th>Tanggal</th>"; } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                        $total_ig=0; $total_fg=0; $total_if=0; $total_ff=0; $terjual=0;
                                        foreach($qr_produksi->result() as $n => $val): ?>
                                        <tr>
                                            <td><?=$n+1;?></td>
                                            <td><?=$val->kode_konstruksi;?></td>
                                            <td><?=$val->dep;?></td>
                                            <td>
                                                <?php
                                                    if(fmod($val->prod_ig, 1) !== 0.00){
                                                        $ig = number_format($val->prod_ig,2,',','.');
                                                    } else {
                                                        $ig = number_format($val->prod_ig,0,',','.');
                                                    }
                                                    echo $ig;
                                                    $total_ig+=$val->prod_ig;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if(fmod($val->prod_fg, 1) !== 0.00){
                                                        $fg = number_format($val->prod_fg,2,',','.');
                                                    } else {
                                                        $fg = number_format($val->prod_fg,0,',','.');
                                                    }
                                                    echo $fg;
                                                    $total_fg+=$val->prod_fg;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if(fmod($val->prod_if, 1) !== 0.00){
                                                        $if = number_format($val->prod_if,2,',','.');
                                                    } else {
                                                        $if = number_format($val->prod_if,0,',','.');
                                                    }
                                                    echo $if;
                                                    $total_if+=$val->prod_if;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if(fmod($val->prod_ff, 1) !== 0.00){
                                                        $ff = number_format($val->prod_ff,2,',','.');
                                                    } else {
                                                        $ff = number_format($val->prod_ff,0,',','.');
                                                    }
                                                    echo $ff;
                                                    $total_ff+=$val->prod_ff;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    // if(fmod($val->terjual_yard, 1) !== 0.00){
                                                    //     $jual = number_format($val->terjual_yard,2,',','.');
                                                    // } else {
                                                    //     $jual = number_format($val->terjual_yard,0,',','.');
                                                    // }
                                                    // echo $jual;
                                                    // $terjual+=$val->terjual_yard;
                                                    //penjualan taruh sini ya guys
                                                ?>
                                            </td>
                                            <?php if($frmdep!="null"){ 
                                                $ix = explode('-',$val->tgl);
                                                echo "<td>";
                                                echo $ix[2]." ".$bln[$ix[1]]." ".$ix[0];
                                                echo "</td>";
                                             } ?>
                                        </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-primary">
                                                <th colspan="3">Total</th>
                                                <td>
                                                    <?php
                                                        if(fmod($total_ig, 1) !== 0.00){
                                                            $total_ig = number_format($total_ig,2,',','.');
                                                        } else {
                                                            $total_ig = number_format($total_ig,0,',','.');
                                                        }
                                                        echo $total_ig;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if(fmod($total_fg, 1) !== 0.00){
                                                            $total_fg = number_format($total_fg,2,',','.');
                                                        } else {
                                                            $total_fg = number_format($total_fg,0,',','.');
                                                        }
                                                        echo $total_fg;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if(fmod($total_if, 1) !== 0.00){
                                                            $total_if = number_format($total_if,2,',','.');
                                                        } else {
                                                            $total_if = number_format($total_if,0,',','.');
                                                        }
                                                        echo $total_if;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if(fmod($total_ff, 1) !== 0.00){
                                                            $total_ff = number_format($total_ff,2,',','.');
                                                        } else {
                                                            $total_ff = number_format($total_ff,0,',','.');
                                                        }
                                                        echo $total_ff;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if(fmod($terjual, 1) !== 0.00){
                                                            $terjual = number_format($terjual,2,',','.');
                                                        } else {
                                                            $terjual = number_format($terjual,0,',','.');
                                                        }
                                                        echo $terjual;
                                                    ?>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                               </div>
                            </div>
						</div>
					</div>
					<!-- Simple Datatable start -->
                    <!-- Simple Datatable start -->
                    <div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								<strong>Laporan Stok Rindang</strong>
							</p>
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                               <div class="table-responsive" style="font-size:14px;">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr class="table-secondary">
                                                <th>No.</th>
                                                <th>Konstruksi</th>
                                                <th>Stok di Rindang</th>
                                                <th>Stok di Samatex</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ins_total = 0; $ins2_total=0;
                                            foreach($qr_stok_rj->result() as $n => $val): 
                                            $kdkons = $val->kode_konstruksi;
                                            ?>
                                            <tr>
                                                <td><?=$n+1;?></td>
                                                <td><?=$kdkons;?></td>
                                                <td>
                                                    <?php
                                                        if(fmod($val->prod_ig, 1) !== 0.00){
                                                            $ins = number_format($val->prod_ig,2,',','.');
                                                        } else {
                                                            $ins = number_format($val->prod_ig,0,',','.');
                                                        }
                                                        echo $ins;
                                                        $ins_total+=$val->prod_ig;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $ins_2 = $this->db->query("SELECT * FROM data_stok WHERE kode_konstruksi='$kdkons' AND dep='RJS-IN-Samatex'")->row("stok_ins");
                                                    if(fmod($ins_2, 1) !== 0.00){
                                                        $ins2 = number_format($ins_2,2,',','.');
                                                    } else {
                                                        $ins2 = number_format($ins_2,0,',','.');
                                                    }
                                                    echo $ins2;
                                                    $ins2_total+=$ins_2;
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-primary">
                                                <td colspan="2">Total</td>
                                                <td>
                                                    <?php
                                                    if(fmod($ins_total, 1) !== 0.00){
                                                        $ins_total = number_format($ins_total,2,',','.');
                                                    } else {
                                                        $ins_total = number_format($ins_total,0,',','.');
                                                    }
                                                    echo $ins_total;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if(fmod($ins2_total, 1) !== 0.00){
                                                        $ins2_total = number_format($ins2_total,2,',','.');
                                                    } else {
                                                        $ins2_total = number_format($ins2_total,0,',','.');
                                                    }
                                                    echo $ins2_total;
                                                    ?>
                                                </td>
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
								<strong>Laporan Stok Samatex</strong>
							</p>
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                               <div class="table-responsive" style="font-size:14px;">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr class="table-secondary">
                                                <th rowspan="2" style="vertical-align:middle;">Konstruksi</th>
                                                <th colspan="4" style="text-align:center;">Stok di Samatex</th>
                                                <th colspan="4" style="text-align:center;">Stok di Pusatex</th>
                                            </tr>
                                            <tr class="table-secondary">
                                                <td style="text-align:center;">Inspect Grey</td>
                                                <td style="text-align:center;">Folding Grey</td>
                                                <td style="text-align:center;">Inspect Finish</td>
                                                <td style="text-align:center;">Folding Finish</td>
                                                <td style="text-align:center;">Inspect Grey</td>
                                                <td style="text-align:center;">Inspect Finish</td>
                                                <td style="text-align:center;">Folding Finish</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $total1=0;$total2=0;$total3=0;$total4=0;$total5=0;$total6=0;$total7=0;
                                            foreach($qr_stok_st->result() as $n => $val): 
                                            $kons = $val->kode_konstruksi;
                                            $ps = $this->db->query("SELECT * FROM data_stok WHERE kode_konstruksi='$kons' AND dep='Samatex-IN-Pusatex'");
                                            ?>
                                                <tr>
                                                    <td><?=$kons;?></td>
                                                    <td>
                                                        <?php
                                                        if(fmod($val->prod_ig, 1) !== 0.00){
                                                            $st_ins = number_format($val->prod_ig,2,',','.');
                                                        } else {
                                                            $st_ins = number_format($val->prod_ig,0,',','.');
                                                        }
                                                        echo $st_ins;
                                                        $total1+=$val->prod_ig;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if(fmod($val->prod_fg, 1) !== 0.00){
                                                            $st_fol = number_format($val->prod_fg,2,',','.');
                                                        } else {
                                                            $st_fol = number_format($val->prod_fg,0,',','.');
                                                        }
                                                        echo $st_fol;
                                                        $total2+=$val->prod_fg;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if(fmod($val->prod_if, 1) !== 0.00){
                                                            $st_insf = number_format($val->prod_if,2,',','.');
                                                        } else {
                                                            $st_insf = number_format($val->prod_if,0,',','.');
                                                        }
                                                        echo $st_insf;
                                                        $total3+=$val->prod_if;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if(fmod($val->prod_ff, 1) !== 0.00){
                                                            $st_folf = number_format($val->prod_ff,2,',','.');
                                                        } else {
                                                            $st_folf = number_format($val->prod_ff,0,',','.');
                                                        }
                                                        echo $st_folf;
                                                        $total4+=$val->prod_ff;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if(fmod($ps->row("prod_fg"), 1) !== 0.00){
                                                            $ps_fol = number_format($ps->row("prod_fg"),2,',','.');
                                                        } else {
                                                            $ps_fol = number_format($ps->row("prod_fg"),0,',','.');
                                                        }
                                                        echo $ps_fol;
                                                        $total5+=$ps->row("prod_fg");
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if(fmod($ps->row("prod_if"), 1) !== 0.00){
                                                            $ps_ins = number_format($ps->row("prod_if"),2,',','.');
                                                        } else {
                                                            $ps_ins = number_format($ps->row("prod_if"),0,',','.');
                                                        }
                                                        echo $ps_ins;
                                                        $total6+=$ps->row("prod_if");
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if(fmod($ps->row("prod_ff"), 1) !== 0.00){
                                                            $ps_folf = number_format($ps->row("prod_ff"),2,',','.');
                                                        } else {
                                                            $ps_folf = number_format($ps->row("prod_ff"),0,',','.');
                                                        }
                                                        echo $ps_folf;
                                                        $total7+=$ps->row("prod_ff");
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-primary">
                                                <td>Total</td>
                                                <td>
                                                    <?php
                                                        if(fmod($total1, 1) !== 0.00){
                                                            $total1 = number_format($total1,2,',','.');
                                                        } else {
                                                            $total1 = number_format($total1,0,',','.');
                                                        }
                                                        echo $total1;
                                                       
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if(fmod($total2, 1) !== 0.00){
                                                            $total2 = number_format($total2,2,',','.');
                                                        } else {
                                                            $total2 = number_format($total2,0,',','.');
                                                        }
                                                        echo $total2;
                                                       
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if(fmod($total3, 1) !== 0.00){
                                                            $total3 = number_format($total3,2,',','.');
                                                        } else {
                                                            $total3 = number_format($total3,0,',','.');
                                                        }
                                                        echo $total3;
                                                       
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if(fmod($total4, 1) !== 0.00){
                                                            $total4 = number_format($total4,2,',','.');
                                                        } else {
                                                            $total4 = number_format($total4,0,',','.');
                                                        }
                                                        echo $total4;
                                                       
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if(fmod($total5, 1) !== 0.00){
                                                            $total5 = number_format($total5,2,',','.');
                                                        } else {
                                                            $total5 = number_format($total5,0,',','.');
                                                        }
                                                        echo $total5;
                                                       
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if(fmod($total6, 1) !== 0.00){
                                                            $total6 = number_format($total6,2,',','.');
                                                        } else {
                                                            $total6 = number_format($total6,0,',','.');
                                                        }
                                                        echo $total6;
                                                       
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if(fmod($total7, 1) !== 0.00){
                                                            $total7 = number_format($total7,2,',','.');
                                                        } else {
                                                            $total7 = number_format($total7,0,',','.');
                                                        }
                                                        echo $total7;
                                                       
                                                    ?>
                                                </td>
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
								<strong>Tampilkan Laporan</strong>
							</p><small>Tampilkan laporan berdasarkan tanggal dan departement</small>
						</div>
						<div class="pd-20 card-box mb-30">
                            <div class="clearfix">
                                <form action="<?=base_url('report-dashboard');?>" method="post">
                                <div class="form-group row">
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
                                </button>
                                </form>
                            </div>
						</div>
					</div>
					<!-- Simple Datatable End -->
        </div>
    </div>
</div>

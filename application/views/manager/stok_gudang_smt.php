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
								<strong>Stok Gudang</strong>
							</p><small>Menampilkan Stok Grey dan Finish Gudang Samatex</small>
                            
						</div>
						<div class="pd-20 card-box mb-30">
						        <div class="konten-mobile2">
                                    <div class="kotaknewpkg">
                                        <span>Filter Stok</span>
                                        <div style="width: 100%;display: flex;flex-direction: column;">
                                            <div class="form-label">
                                                <label for="jnsid">Jenis Kain</label>
                                                <select class="ipt" name="dates" id="jnsid">
                                                    <option value="All">Semua</option>
                                                    <option value="Grey">Grey</option>
                                                    <option value="Finish">Finish</option>
                                                </select>
                                            </div>
                                            
                                            
                                            <div style="display:none;" id="owekloading">
                                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                    <div class="konten-mobile2" style="margin-top:20px;display:flex;flex-wrap:wrap;" id="kontenStok">
                                        Loading...
                                    </div>
						</div>
				    </div>
				    </div>
                </div>
            </div>
</div>

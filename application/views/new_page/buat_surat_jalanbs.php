<?php $bln = [ '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Pengiriman BS</h4>
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
                     <?php
                    $uri = $this->uri->segment(3);
                    $cek = $this->data_model->get_byid('bs_paket_kirim',['sj'=>$uri]);
                    if($cek->num_rows()==1){
                        $oke = "yes";
                    } else {
                        $oke = "no";
                    }
                     ?>
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">
								Buat Surat Jalan BS
							</p><small>Anda harus membuat surat jalan untuk pengiriman paket.</small>
						</div>
						<div class="pd-20 card-box mb-30">
                            <form name="fr2" action="<?=base_url('kirim/kirim_paketbs');?>" method="post" entype="multipart/form-data">
                            <div class="clearfix">
                                <input type="hidden" value="<?=$sess_dep;?>" id="locnowid" name="dept">
                                   
                                    <div class="form-group row" id="frh1">
                                        <label class="col-sm-12 col-md-2 col-form-label">Kirim Customer</label>
                                        <div class="col-sm-12 col-md-10" style="display:flex;">
                                            <?php if($oke=="yes"){
                                                ?>
                                                <input type="text" class="form-control" name="namacus" style="width:350px;" value="<?=$cek->row()->cus;?>" readonly>
                                                <input type="hidden" name="idupdate" value="<?=$cek->row()->idbspktkrim;?>">
                                                <?php
                                            } else { ?>
                                            <div class="autoComplete_wrapper">
												<input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" placeholder="Nama Konsumen" name="namacus">
											</div>
                                            <input type="hidden" name="idupdate" value="no">
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">No Surat Jalan</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:350px;" placeholder="Masukan nomor surat jalan" type="text" id="nosjid" name="sj" value="<?=$oke=="yes"?$cek->row()->sj:"";?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">No Faktur</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:350px;" placeholder="Masukan nomor faktur" type="text" id="fakturid" name="faktur" value="<?=$oke=="yes"?$cek->row()->faktur:"";?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-2 col-form-label">Tanggal</label>
                                        <div class="col-sm-12 col-md-10">
                                            <input class="form-control" style="width:250px;" type="date" name="tgl" value="<?=$oke=="yes"?$cek->row()->tglkirim:date('Y-m-d');?>" required>
                                        </div>
                                    </div>
                                    <input type="hidden" id="idcus" name="idcus" value="0">
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
                                                <th>Roll</th>
                                                <th>Total Panjang</th>
                                                <th>Total Berat</th>
                                                <th>Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if($oke=="yes"){
                                                $alldata = $cek->row()->dtkrim;
                                                $x = explode(';',$alldata);
                                                for ($i=0; $i <count($x) ; $i++) { 
                                                    echo "<tr>";
                                                    echo "<td>".($i+1)."</td>";
                                                    $til = explode('-',$x[$i]);
                                                    echo "<td>".$til[0]."</td>";
                                                    echo "<td>".$til[1]."</td>";
                                                    echo "<td>".$til[2]."</td>";
                                                    echo "<td>".$til[3]."</td>";
                                                    echo "<td>".$til[4]."</td>";
                                                    ?>
                                                    <td style="display:flex;align-items:center;">
                                                        <input type="hidden" name="kodepkg[]" value="<?=$til[0];?>">
                                                        <input type="hidden" name="konstruksi[]" value="<?=$til[1];?>">
                                                        <input type="hidden" name="jmlroll[]" value="<?=$til[2];?>">
                                                        <input type="hidden" name="pjgkirim[]" value="<?=$til[3];?>">
                                                        <input type="hidden" name="berat[]" value="<?=$til[4];?>">
                                                        <input type="text" class="form-control harga" name="harga[]" placeholder="Masukan harga" value="<?=$til[5];?>"> / 
                                                        <input type="text" class="form-control harga" name="satuan[]" placeholder="Masukan harga" value="<?=$til[6];?>">
                                                    </td>
                                                    <?php
                                                    echo "</tr>";
                                                    
                                                }
                                                echo "<tr><td colspan='6'>Total</td><td>Rp. ".number_format($cek->row()->ttlharga,2,',','.')."</td></tr>";
                                            } else {
                                            for ($i=0; $i <count($dt) ; $i++) { 
                                                $row = $this->data_model->get_byid('bs_paket',['pktbs'=>$dt[$i]])->row_array();
                                                $jns = $row['jenis'];
                                                if($jns=="finish"){
                                                    $pjg1 = floatval($row['panjang']) * 0.9144;
                                                    $pjg = floatval($row['panjang']) * 0.9144;
                                                    if($pjg == floor($pjg)){
                                                        $pjg = number_format($pjg,0,',','.');
                                                    } else {
                                                        $pjg = number_format($pjg,2,',','.');
                                                    }
                                                    $sat = "Yard";
                                                } else {
                                                    $pjg1 = $row['panjang'];
                                                    $pjg = number_format($row['panjang'],0,',','.');
                                                    $sat = "Meter";
                                                }
                                                echo "<tr>";
                                                echo "<td>".($i+1)."</td>";
                                                echo "<td>".$dt[$i]."</td>";
                                                echo "<td>".$row['konstruksi']."</td>";
                                                echo "<td>".$row['jmlroll']."</td>";
                                                echo "<td>".$pjg." ".$sat."</td>";
                                                echo "<td>".$row['berat']." Kg</td>";
                                                ?>
                                                <td style="display:flex;align-items:center;">
                                                    <input type="hidden" name="kodepkg[]" value="<?=$dt[$i];?>">
                                                    <input type="hidden" name="konstruksi[]" value="<?=$row['konstruksi'];?>">
                                                    <input type="hidden" name="jmlroll[]" value="<?=$row['jmlroll'];?>">
                                                    <input type="hidden" name="pjgkirim[]" value="<?=$pjg1;?>">
                                                    <input type="hidden" name="berat[]" value="<?=$row['berat'];?>">
                                                    <input type="text" class="form-control harga" name="harga[]" placeholder="Masukan harga"> / 
                                                    <select name="satuan[]" class="form-control">
                                                        <option value="Kg">Kg</option>
                                                        <option value="<?=$sat;?>"><?=$sat;?></option>
                                                    </select>
                                                </td>
                                                <?php
                                                echo "</tr>";
                                            } }
                                            ?>
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary">
                                            Simpan Surat Jalan
                                    </button>
                                    <?php
                                    if($oke=="yes"){
                                        echo '<a href="'.base_url('cetak/notabs/'.$cek->row()->sj.'/1').'" target="_blank">';
                                        echo "<button type='button' class='btn btn-secondary'><i class='icon-copy bi bi-printer'></i> &nbsp; Cetak</button></a>";
                                    }
                                    ?>
                                    <hr>
                                    
                                    
                                    
                            </div>
                            </form>
						</div>
					</div>
					<!-- Simple Datatable End -->
					
        </div>
    </div>
</div>
<input type="text" class="form-control harga" name="harga[]" placeholder="Masukan harga">

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".harga").forEach(input => {
        input.addEventListener("input", function() {
            let value = this.value.replace(/[^0-9.]/g, ""); // Hanya angka dan titik
            let parts = value.split(".");

            // Pastikan hanya ada satu titik desimal
            if (parts.length > 2) {
                value = parts[0] + "." + parts.slice(1).join(""); // Gabungkan hanya satu titik desimal
            }

            // Pisahkan bagian integer dan decimal
            let [integerPart, decimalPart] = value.split(".");

            // Hilangkan koma lama lalu format angka ribuan
            integerPart = integerPart.replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            // Jika ada angka desimal, batasi hanya 2 angka setelah titik
            decimalPart = decimalPart !== undefined ? "." + decimalPart.slice(0, 2) : "";

            this.value = integerPart + decimalPart;
        });

        input.addEventListener("keydown", function(event) {
            // Izinkan tombol kontrol seperti backspace, delete, panah kiri/kanan
            if (["Backspace", "Delete", "ArrowLeft", "ArrowRight"].includes(event.key)) {
                return;
            }

            // Cegah jika lebih dari satu titik sudah ada
            if (event.key === "." && this.value.includes(".")) {
                event.preventDefault();
            }

            // Cegah jika bukan angka atau titik
            if (!/[0-9.]/.test(event.key)) {
                event.preventDefault();
            }
        });
    });
});
</script>

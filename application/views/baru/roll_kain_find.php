<?php $arb = ['00'=>'Undefined', '01' => 'Jan', '02' => 'Feb', '03' => 'Mar','04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des']; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0"><strong>Hasil Pencarian</strong></p>
						</div>
						<div class="pd-20">
<?php
    $ex = explode(',',$code);
    $n=1;
    foreach($ex as $kode_roll):
        $cari_1 = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kode_roll]);
        if($cari_1->num_rows() == 0){
            echo "Kode ".$kode_roll." tidak ditemukan.";
        } else {
            foreach($cari_1->result() as $val){
                $tg = explode('-', $val->tanggal);
                $printTgl = $tg[2]." ".$arb[$tg[1]]." ".$tg[0];
                echo "Inspect Grey Kode <strong>".$kode_roll."</strong> (<strong>".$val->ukuran_ori."</strong> Meter)<br>- Konstruksi <strong>".$val->konstruksi."</strong>, Nomor Mesin <strong>".$val->no_mesin."</strong>, No Beam <strong>".$val->no_beam."</strong>, Operator <strong>".$val->operator."</strong>, Tanggal Inspect <strong>".$printTgl."</strong> (Lokasi : <strong>".$val->loc_now."</strong>) <br>";
            }
        }
        $cari_2s = $this->data_model->get_byid('data_if', ['kode_roll'=>$kode_roll]);
        $cari_2 = $this->db->query("SELECT * FROM data_if WHERE kode_roll LIKE '%$kode_roll%'");
        if($cari_2->num_rows() == 0){

        } else {
            foreach($cari_2->result() as $val){
                $tg = explode('-', $val->tgl_potong);
                $printTgl = $tg[2]." ".$arb[$tg[1]]." ".$tg[0];
                echo "Inspect Finish Kode <strong>".$val->kode_roll."</strong> (<strong>".$val->ukuran_ori."</strong> Meter)<br>- Konstruksi <strong>".$val->konstruksi."</strong>, Operator <strong>".$val->operator."</strong>, Tanggal Inspect <strong>".$printTgl."</strong><br>";
            }
        }
        $cari_3s = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll]);
        $cari_3 = $this->db->query("SELECT * FROM data_fol WHERE kode_roll LIKE '%$kode_roll%'");
        if($cari_3->num_rows() == 0){

        } else {
            foreach($cari_3->result() as $val){
                $tg = explode('-', $val->tgl);
                $satuan = $val->jns_fold=='Grey' ? 'Meter':'Yard';
                $printTgl = $tg[2]." ".$arb[$tg[1]]." ".$tg[0];
                echo "Folding <strong>".$val->jns_fold."</strong> Kode <strong>".$val->kode_roll."</strong> (<strong>".$val->ukuran."</strong> ".$satuan.")<br>- Konstruksi <strong>".$val->konstruksi."</strong>, Operator <strong>".$val->operator."</strong>, Tanggal Folding <strong>".$printTgl."</strong>, (Lokasi sekarang ".$val->posisi.")<br>";
            }
        }
        // if($cari_1->num_rows()==1){
        //     echo "<p>$n. Kode Roll <strong>$kode_roll</strong><br>";
        //     $rx = explode("-",$cari_1->row("tanggal"));
        //     echo "&nbsp;&nbsp;&nbsp; Ukuran inspect grey <strong>".$cari_1->row("ukuran_ori")."</strong> meter (".$rx[2]." ".$arb[$rx[1]]." ".$rx[0].")";

        //     $cari_2 = $this->data_model->get_byid('data_if', ['kode_roll'=>$kode_roll]);
        //     if($cari_2->num_rows()==1){
        //         $tx = explode('-', $cari_2->row("tgl_potong"));
        //         echo ", Ukuran inspect finish <strong>".$cari_2->row("ukuran_ori")."</strong> yard (".$tx[2]." ".$arb[$tx[1]]." ".$tx[0].")";
        //     }
        //     $cari_3 = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll]);
        //     if($cari_3->num_rows()==1){
        //         $zx = explode('-', $cari_3->row("tgl"));
        //         if($cari_3->row("jns_fold")=="Grey"){$ukuran="meter";}else{$ukuran="yard";}
        //         echo ", Ukuran folding <strong>".$cari_3->row("ukuran")."</strong> ".$ukuran." (".$zx[2]." ".$arb[$zx[1]]." ".$zx[0].")";
        //     }

        //     echo "</p>";
        //     $n++;
            
        // } else {
        //     $cari_4 = $this->data_model->get_byid('data_if_lama',['kode_roll'=>$kode_roll]);
        //     if($cari_4->num_rows()==1){
        //         echo "<p>$n. Kode Roll <strong>$kode_roll</strong><br>";
        //         $qx = explode("-",$cari_4->row("tgl"));
        //         echo "&nbsp;&nbsp;&nbsp; <code>Tidak ditemukan Ukuran Inspect Grey</code>, Ukuran inspect finish <strong>".$cari_4->row("panjang")."</strong> yard (".$qx[2]." ".$arb[$qx[1]]." ".$qx[0].")";
        //     } else {

        //     }
        // }
        echo "<hr>";
    endforeach;
?>
						</div>
						
					</div>
					<!-- Simple Datatable End -->
					
					
					
        </div>
    </div>
</div>
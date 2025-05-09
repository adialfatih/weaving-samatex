<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
    //   if($this->session->userdata('login_form') != "rindangjati_sess"){
    //     redirect(base_url('login'));
    //   }
      
  }
   
  function index(){ 
      $this->load->view('block');
  } //end
  function produksimesin(){
        $loc = $this->uri->segment(3);
        $loc_now = ucfirst($loc);
        $bulan = $this->uri->segment(4);
        $oldyear = $this->uri->segment(5);
        
        $nb = sprintf('%02d', $bulan);
        if(intval($bulan) > 0 && intval($bulan) < 13){
        $printBulan = $this->data_model->printBln($nb);
        if($oldyear==2023){ $tahun = 2023; } else {
        $tahun = date('Y'); }
        //echo $oldyear;
        $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $nb, $tahun);
        echo "<h1>LAPORAN PRODUKSI MESIN BULAN ". strtoupper($printBulan) ." ".$tahun."</h1>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>TANGGAL</th>";
        echo "<th>JUMLAH MESIN</th>";
        echo "<th>HASIL</th>";
        echo "</tr>";
        $total=0;
        for ($tanggal = 1; $tanggal <= $jumlah_hari; $tanggal++) {
            $tanggal_lengkap = sprintf('%02d', $tanggal); // menambahkan leading zero jika diperlukan
            $tanggal_bulan_tahun = $tahun . '-' . $nb . '-' . $tanggal_lengkap;
            $tgl_show = $tanggal_lengkap."/".$nb."/".$tahun;
            $jumlahmesin = $this->db->query("SELECT SUM(jumlah_mesin) AS jm FROM dt_produksi_mesin WHERE tanggal_produksi='$tanggal_bulan_tahun' AND lokasi='$loc_now'")->row("jm");
            $hasilmesin = $this->db->query("SELECT SUM(hasil) AS jm FROM dt_produksi_mesin WHERE tanggal_produksi='$tanggal_bulan_tahun' AND lokasi='$loc_now'")->row("jm");
            echo "<tr>";
            echo "<td>".$tgl_show."</td>";
            echo "<td>".$jumlahmesin."</td>";
            echo "<td>".round($hasilmesin,2)."</td>";
            $total+=$hasilmesin;
            echo "</tr>";
            //echo $tanggal_bulan_tahun . "<br>";
        } //END PERULANGAN
        
        echo "<tr>";
        echo "<th>TOTAL</th>";
        
        echo "<th></th>";
        echo "<th>".round($total,2)."</th>";
        echo "</tr>";
        
        echo "</table>";
        
        }
  } //end laporan
  function bsbp(){
        $bulan = $this->uri->segment(3);
        $oldyear = $this->uri->segment(4);
        
        $nb = sprintf('%02d', $bulan);
        if(intval($bulan) > 0 && intval($bulan) < 13){
        $printBulan = $this->data_model->printBln($nb); }
        if($oldyear==""){ 
            $tahun = date('Y'); 
        } else {
            $tahun = $oldyear; 
        }
        //echo $oldyear;
        $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $nb, $tahun);
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>TANGGAL</th>";
        echo "<th style='width:150px;'>BS</th>";
        echo "<th style='width:150px;'>BP</th>";
        echo "<th style='width:150px;'>BC</th>";
        echo "</tr>";
        for ($tanggal = 1; $tanggal <= $jumlah_hari; $tanggal++) {
            $tanggal_lengkap = sprintf('%02d', $tanggal); // menambahkan leading zero jika diperlukan
            $tanggal_bulan_tahun = $tahun . '-' . $nb . '-' . $tanggal_lengkap;
            $tgl_show = $tanggal_lengkap."/".$nb."/".$tahun;
            echo "<tr>";
            echo "<td>".$tgl_show."</td>";
            $bs = $this->db->query("SELECT SUM(ukuran_bs) AS jml FROM data_ig_bs WHERE tgl='$tanggal_bulan_tahun' AND dep='Samatex'")->row("jml");
            $bp = $this->db->query("SELECT SUM(ukuran_bp) AS jml FROM data_ig_bp WHERE tgl='$tanggal_bulan_tahun' AND dep='Samatex'")->row("jml");
            $bc = $this->db->query("SELECT SUM(ukuran_bc) AS jml FROM data_ig_bc WHERE tgl='$tanggal_bulan_tahun' AND dep='Samatex'")->row("jml");
            
            ?>
            <td style='text-align:center;'><a href="<?=base_url('laporan/bstgl/'.$tanggal_bulan_tahun.'');?>" target="_blank"><?=$bs;?></a></td>
            <td style='text-align:center;'><a href="<?=base_url('laporan/bptgl/'.$tanggal_bulan_tahun.'');?>" target="_blank"><?=$bp;?></a></td>
            <td style='text-align:center;'><a href="<?=base_url('laporan/bctgl/'.$tanggal_bulan_tahun.'');?>" target="_blank"><?=$bc;?></a></td>
            <?php
            echo "</tr>";
        }
        echo "</table>";
  }
  function bstgl(){
      $tgl = $this->uri->segment(3);
      //echo $tgl;
      $dt = $this->data_model->get_byid('data_ig_bs', ['tgl'=>$tgl, 'dep'=>'Samatex']);
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>KODE ROLL</th>";
        echo "<th>UKURAN BS</th>";
        echo "<th>KETERANGAN</th>";
        echo "<th>OPERATOR</th>";
        echo "<th>SHIFT</th>";
        echo "</tr>";
      foreach($dt->result() as $n => $val){
            echo "<tr><td>".$val->kode_roll."</td>";
            echo "<td>".$val->ukuran_bs."</td>";
            echo "<td>".$val->keterangan."</td>";
            echo "<td>".$val->operator."</td>";
            echo "<td>".$val->shift_op."</td></tr>";
      }
  }
  function bptgl(){
      $tgl = $this->uri->segment(3);
      //echo $tgl;
      $dt = $this->data_model->get_byid('data_ig_bp', ['tgl'=>$tgl, 'dep'=>'Samatex']);
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>KODE ROLL</th>";
        echo "<th>UKURAN BP</th>";
        echo "<th>KETERANGAN</th>";
        echo "<th>OPERATOR</th>";
        echo "<th>SHIFT</th>";
        echo "</tr>";
      foreach($dt->result() as $n => $val){
            echo "<tr><td>".$val->kode_roll."</td>";
            echo "<td>".$val->ukuran_bp."</td>";
            echo "<td>".$val->keterangan."</td>";
            echo "<td>".$val->operator."</td>";
            echo "<td>".$val->shift_op."</td></tr>";
      }
  }
  function bctgl(){
      $tgl = $this->uri->segment(3);
      //echo $tgl;
      $dt = $this->data_model->get_byid('data_ig_bc', ['tgl'=>$tgl, 'dep'=>'Samatex']);
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>KODE ROLL</th>";
        echo "<th>UKURAN BC</th>";
        echo "<th>KETERANGAN</th>";
        echo "<th>OPERATOR</th>";
        echo "<th>SHIFT</th>";
        echo "</tr>";
      foreach($dt->result() as $n => $val){
            echo "<tr><td>".$val->kode_roll."</td>";
            echo "<td>".$val->ukuran_bc."</td>";
            echo "<td>".$val->keterangan."</td>";
            echo "<td>".$val->operator."</td>";
            echo "<td>".$val->shift_op."</td></tr>";
      }
  }

  function produksi(){
        $bulan = $this->uri->segment(3);
        $oldyear = $this->uri->segment(4);
        //echo $oldyear;
        $nb = sprintf('%02d', $bulan);
        if(intval($bulan) > 0 && intval($bulan) < 13){
        $printBulan = $this->data_model->printBln($nb);
        if($oldyear==""){ 
            $tahun = date('Y');
        } else {
            $tahun = $oldyear;
        }
        //echo $oldyear;
        $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $nb, $tahun);
        echo "<h1>LAPORAN PRODUKSI BULAN tes". strtoupper($printBulan) ." ".$tahun."</h1>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>TANGGAL</th>";
        echo "<th>INSPECT GREY</th>";
        echo "<th>BS GREY</th>";
        echo "<th>BP GREY</th>";
        echo "<th>INSPECT FINISH</th>";
        echo "<th>BS FINISH</th>";
        echo "<th>BP FINISH</th>";
        echo "<th>FOLDING GREY</th>";
        echo "<th>FOLDING FINISH</th>";
        echo "<th>TOTAL FOLDING</th>";
        echo "<th>PENJUALAN GREY</th>";
        echo "<th>PENJUALAN FINISH</th>";
        echo "<th>STOK AKHIR GREY</th>";
        echo "<th>STOK AKHIR FINISH</th>";
        echo "</tr>";
        $total_ig = 0; $total_bs_ig = 0; $total_bp_ig = 0;
        $total_fg = 0; $total_ff = 0; $all_to = 0;
        $total_insf=0; $total_bsfns=0; $total_bpfns=0;
        $ttl_penjualan_grey = 0;
        $ttl_penjualan_finish = 0;
        for ($tanggal = 1; $tanggal <= $jumlah_hari; $tanggal++) {
            $tanggal_lengkap = sprintf('%02d', $tanggal); // menambahkan leading zero jika diperlukan
            $tanggal_bulan_tahun = $tahun . '-' . $nb . '-' . $tanggal_lengkap;
            $tgl_show = $tanggal_lengkap."/".$nb."/".$tahun;
            $fg = $this->db->query("SELECT SUM(ukuran) AS ukr FROM data_fol WHERE jns_fold='Grey' AND tgl='$tanggal_bulan_tahun'")->row("ukr");
            if($fg < 1){ $fg_show="-"; } else {
            if(fmod($fg, 1) !== 0.00){
                $fg_show = number_format($fg,2,',','.');
            } else {
                $fg_show = number_format($fg,0,',','.');
            } }
            $total_fg+=$fg;
            $ff = $this->db->query("SELECT SUM(ukuran) AS ukr FROM data_fol WHERE jns_fold='Finish' AND tgl='$tanggal_bulan_tahun'")->row("ukr");
            if($ff < 1){ $ff_show="-"; } else {
            if(fmod($ff, 1) !== 0.00){
                $ff_show = number_format($ff,2,',','.');
            } else {
                $ff_show = number_format($ff,0,',','.');
            } }
            $total_ff+=$ff;
            echo "<tr>";
            echo "<td>".$tgl_show."</td>";
            //HITUNG INSPECT GREY INSPECT FINISH

            $ins_grey = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_ig WHERE tanggal='$tanggal_bulan_tahun' AND dep='Samatex' AND operator NOT IN ('pendi','edi','hadi','adenna')")->row("ukr");
            $bsins_grey = $this->db->query("SELECT SUM(ukuran_bs) AS ukr FROM data_ig WHERE tanggal='$tanggal_bulan_tahun' AND dep='Samatex' AND operator NOT IN ('pendi','edi','hadi','adenna')")->row("ukr");
            $bpins_grey = $this->db->query("SELECT SUM(ukuran_bp) AS ukr FROM data_ig WHERE tanggal='$tanggal_bulan_tahun' AND dep='Samatex' AND operator NOT IN ('pendi','edi','hadi','adenna')")->row("ukr");
            $ins_finish = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_if WHERE tgl_potong='$tanggal_bulan_tahun'")->row("ukr");
            if($ins_grey < 1){ $ig_show = "-"; } else {
                if(fmod($ins_grey, 1) !== 0.00){
                    $ig_show = number_format($ins_grey,2,',','.');
                } else {
                    $ig_show = number_format($ins_grey,0,',','.');
                }
            }
            $total_ig+=$ins_grey;
            if($ins_finish < 1){ $if_show = "-"; } else {
                $ins_finish_yard = $ins_finish;
                if(fmod($ins_finish, 1) !== 0.00){
                    $if_show = number_format($ins_finish,2,',','.');
                } else {
                    $if_show = number_format($ins_finish,0,',','.');
                }
            }
            $total_insf+=$ins_finish;
            if($ig_show=="-"){ echo "<td>-</td>"; } else {
            ?><td><a href="<?=base_url('laporan/insgrey/'.$tanggal_bulan_tahun);?>" style="text-decoration:none;color:#000;"><?=$ig_show;?></a></td><?php }
            if($bsins_grey>0){ echo "<td>".$bsins_grey."</td>"; } else { echo "<td>-</td>"; }
            if($bpins_grey>0){ echo "<td>".$bpins_grey."</td>"; } else { echo "<td>-</td>"; }
            $total_bs_ig+=$bsins_grey;
            $total_bp_ig+=$bpins_grey;
            // echo "<td>".$bsins_grey."</td>";
            // echo "<td>".$bpins_grey."</td>";
            if($if_show=="-"){ echo "<td>-</td>"; } else {
                ?><td><a href="<?=base_url('laporan/insfinish/'.$tanggal_bulan_tahun);?>" style="text-decoration:none;color:#000;"><?=$if_show;?></a></td><?php }
            //echo "<td><a href='".base_url('laporan/insgrey/')."'>".$ig_show."</a></td>";
            //echo "<td>".$if_show."</td>";

            //END INSPECT
            $bs_finish = $this->db->query("SELECT SUM(ukuran_bs) AS ukr FROM data_if WHERE tgl_potong='$tanggal_bulan_tahun'")->row("ukr");
            $bp_finish = $this->db->query("SELECT SUM(ukuran_bp) AS ukr FROM data_if WHERE tgl_potong='$tanggal_bulan_tahun'")->row("ukr");
            if($bs_finish>0){ echo "<td>".$bs_finish."</td>"; } else { echo "<td>-</td>"; }
            if($bp_finish>0){ echo "<td>".$bp_finish."</td>"; } else { echo "<td>-</td>"; }
            // echo "<td>".$bs_finish."</td>";
            // echo "<td>".$bp_finish."</td>";
            $total_bsfns+=$bs_finish;
            $total_bpfns+=$bp_finish;
            if($fg_show=="-"){ echo "<td>-</td>"; } else {
                ?><td><a href="<?=base_url('laporan/folgrey/'.$tanggal_bulan_tahun);?>" style="text-decoration:none;color:#000;"><?=$fg_show;?></a></td><?php }
            if($ff_show=="-"){ echo "<td>-</td>"; } else {
                ?><td><a href="<?=base_url('laporan/folfinish/'.$tanggal_bulan_tahun);?>" style="text-decoration:none;color:#000;"><?=$ff_show;?></a></td><?php }
            //echo "<td>".$fg_show."</td>";
            //echo "<td>".$ff_show."</td>";
            $total_ff_fg = $fg + $ff;
            if($total_ff_fg < 1){ $total_ff_fg2="-"; } else {
            if(fmod($total_ff_fg, 1) !== 0.00){
                $total_ff_fg2 = number_format($total_ff_fg,2,',','.');
            } else {
                $total_ff_fg2 = number_format($total_ff_fg,0,',','.');
            } }
            $all_to+=$total_ff_fg;
            echo "<td>".$total_ff_fg2."</td>";
            //PENJUALAN GREY 
            $pj_grey = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota2 WHERE jns_fold='Grey' AND tgl_nota='$tanggal_bulan_tahun'")->row("ttl");
            $pj_finish = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota2 WHERE jns_fold='Finish' AND tgl_nota='$tanggal_bulan_tahun'")->row("ttl");
            if($pj_grey < 1){ $pj_grey2="-"; } else {
            if(fmod($pj_grey, 1) !== 0.00){
                $pj_grey2 = number_format($pj_grey,2,',','.');
            } else {
                $pj_grey2 = number_format($pj_grey,0,',','.');
            } }
            if($pj_finish < 1){ $pj_finish2="-"; } else {
            if(fmod($pj_finish, 1) !== 0.00){
                $pj_finish2 = number_format($pj_finish,2,',','.');
            } else {
                $pj_finish2 = number_format($pj_finish,0,',','.');
            } }
            //stok akhir 
            $stok_akg = $this->db->query("SELECT SUM(stok_grey) AS ukr FROM lock_stok WHERE tanggal='$tanggal_bulan_tahun'")->row("ukr");
            $stok_akf = $this->db->query("SELECT SUM(stok_finish) AS ukr FROM lock_stok WHERE tanggal='$tanggal_bulan_tahun'")->row("ukr");
            if($stok_akg < 1){ $stok_akg2="-"; } else {
                if(fmod($stok_akg, 1) !== 0.00){
                    $stok_akg2 = number_format($stok_akg,2,',','.');
                } else {
                    $stok_akg2 = number_format($stok_akg,0,',','.');
                } 
            }
            if($stok_akf < 1){ $stok_akf2="-"; } else {
                if(fmod($stok_akf, 1) !== 0.00){
                    $stok_akf2 = number_format($stok_akf,2,',','.');
                } else {
                    $stok_akf2 = number_format($stok_akf,0,',','.');
                } 
            }
            
            echo "<td>".$pj_grey2."</td>";
            echo "<td>".$pj_finish2."</td>";
            echo "<td>".$stok_akg2."</td>";
            echo "<td>".$stok_akf2."</td>";
            echo "</tr>";
            //echo $tanggal_bulan_tahun . "<br>";
        } //END PERULANGAN
        if(fmod($total_fg, 1) !== 0.00){
            $total_fg2 = number_format($total_fg,2,',','.');
        } else {
            $total_fg2 = number_format($total_fg,0,',','.');
        }
        if(fmod($total_ff, 1) !== 0.00){
            $total_ff2 = number_format($total_ff,2,',','.');
        } else {
            $total_ff2 = number_format($total_ff,0,',','.');
        }
        if(fmod($all_to, 1) !== 0.00){
            $all_to2 = number_format($all_to,2,',','.');
        } else {
            $all_to2 = number_format($all_to,0,',','.');
        }
        if(fmod($total_insf, 1) !== 0.00){
            $total_insf_show = number_format($total_insf,2,',','.');
        } else {
            $total_insf_show = number_format($total_insf,0,',','.');
        }
        if(fmod($total_ig, 1) !== 0.00){
            $total_ig_show = number_format($total_ig,2,',','.');
        } else {
            $total_ig_show = number_format($total_ig,0,',','.');
        }
        echo "<tr>";
        echo "<th>TOTAL</th>";
        echo "<th>".$total_ig_show."</th>";
        echo "<th>".$total_bs_ig."</th>";
        echo "<th>".$total_bp_ig."</th>";
        echo "<th>".$total_insf_show."</th>";
        echo "<th>".$total_bsfns."</th>";
        echo "<th>".$total_bpfns."</th>";
        if($bulan==12 AND $oldyear==2023) { echo "<th>719.199,5</th>"; } else {
        echo "<th>".$total_fg2."</th>"; }
        if($bulan==12 AND $oldyear==2023) { echo "<th>976.135</th>"; } else {
        echo "<th>".$total_ff2."</th>"; }
        if($bulan==12 AND $oldyear==2023) { echo "<th>1.695.334,5</th>"; } else {
        echo "<th>".$all_to2."</th>"; }
        echo "<th></th>";
        echo "<th></th>";
        echo "<th></th>";
        echo "<th></th>";
        echo "</tr>";
        echo "<tr>";
        echo "<th style='font-size:12px;'>".strtoupper($printBulan)."</th>";
        echo "<th style='font-size:12px;'>INSPECT GREY</th>";
        echo "<th style='font-size:12px;'>BS GREY</th>";
        echo "<th style='font-size:12px;'>BP GREY</th>";
        echo "<th style='font-size:12px;'>INSPECT FINISH</th>";
        echo "<th style='font-size:12px;'>BS FINISH</th>";
        echo "<th style='font-size:12px;'>BP FINISH</th>";
        echo "<th style='font-size:12px;'>FOLDING GREY</th>";
        echo "<th style='font-size:12px;'>FOLDING FINISH</th>";
        echo "<th style='font-size:12px;'>TOTAL FOLDING</th>";
        echo "<th style='font-size:12px;'>PENJUALAN GREY</th>";
        echo "<th style='font-size:12px;'>PENJUALAN FINISH</th>";
        echo "<th style='font-size:12px;'>STOK AKHIR GREY</th>";
        echo "<th style='font-size:12px;'>STOK AKHIR FINISH</th>";
        echo "</tr>";
        echo "</table>";
        echo "<hr>";
        echo "<div style='width:100%;height:50px;'>&nbsp;</div>";
        echo "<a href='https://sm.rindangjati.com/hapus/folding/grey' style='margin-top:20px;border-radius:5px;padding:8px 20px;background:green;color:#fff;text-decoration:none;'>Hapus Stok (Folding Grey)</a>";
        echo "<a href='https://sm.rindangjati.com/hapus/folding/grey' style='margin-top:20px;border-radius:5px;padding:8px 20px;background:red;color:#fff;text-decoration:none;margin-left:20px;'>Hapus Stok (Folding Finish)</a>";
        echo "<div style='width:100%;height:50px;'>&nbsp;</div>";
        }
  } //end laporan

  function harian(){ 
     $depuser = $this->session->userdata('departement');
     $dep = $this->input->post('dep');
     $tgl = $this->input->post('datesr');
     $access = "false"; $pass="null";
     if($depuser==$dep){
        $access = "true";
     } else {
     if(empty($this->input->post('akspass'))){
        $access = "false";
     } else {
        $password = "Rjs123spinning*";
        if($this->input->post('akspass') == $password){
            $access = "true";
        } else {
            $access = "false";
            $pass = "salah";
        }
     }
     }
     $ex = explode(' - ', $tgl);
     $ex1 = explode('/', $ex[0]);
     $tgl_awal = $ex1[2]."-".$ex1[0]."-".$ex1[1];
     $ex2 = explode('/', $ex[1]);
     $tgl_akhir = $ex2[2]."-".$ex2[0]."-".$ex2[1];
     //echo "Departement : ".$dep."<br>";
     //echo "Tanggal Awal ".$tgl_awal."<br>";
     //echo "Tanggal Akhir ".$tgl_akhir."<br>";
     //$query = "SELECT * FROM report_produksi_harian WHERE lokasi_produksi = '$dep' AND waktu BETWEEN '$tgl_awal' AND '$tgl_akhir'";
     $qr = $this->db->query("SELECT * FROM data_produksi WHERE dep = '$dep' AND tgl BETWEEN '$tgl_awal' AND '$tgl_akhir'");
     if($this->session->userdata('hak') == 'Manager'){
        if($access=="false"){
            $data = array(
                'title' => 'Report Produksi',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'data_table' => $this->data_model->get_record('user'),
                'loti' => 'true',
                'dep' => $dep,
                'tgl' => $tgl,
                'pass' => $pass
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            $this->load->view('new_page/report_produksi_lock', $data);
            $this->load->view('part/main_js_dttable');
        } else {
            $data = array(
                'title' => 'Report Produksi',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'data_table' => $this->data_model->get_record('user'),
                'dep' => $dep,
                'tgl' => $tgl,
                'tgl_awal' => $tgl_awal,
                'tgl_akhir' => $tgl_akhir,
                'qrdata' => $qr
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            $this->load->view('new_page/report_produksi', $data);
            $this->load->view('part/main_js_dttable');
        }
     } else {
        $this->load->view('blok_view');
     }
     
  } //end

  function mesin(){ 
    $depuser = $this->session->userdata('departement');
    $dep = $this->input->post('dep');
    $tgl = $this->input->post('datesr');
    $access = "false"; $pass="null";
    if($depuser==$dep){
       $access = "true";
    } else {
    if(empty($this->input->post('akspass'))){
       $access = "false";
    } else {
       $password = "Rjs123spinning*";
       if($this->input->post('akspass') == $password){
           $access = "true";
       } else {
           $access = "false";
           $pass = "salah";
       }
    }
    }
    $ex = explode(' - ', $tgl);
    $ex1 = explode('/', $ex[0]);
    $tgl_awal = $ex1[2]."-".$ex1[0]."-".$ex1[1];
    $ex2 = explode('/', $ex[1]);
    $tgl_akhir = $ex2[2]."-".$ex2[0]."-".$ex2[1];
    //echo "Departement : ".$dep."<br>";
    //echo "Tanggal Awal ".$tgl_awal."<br>";
    //echo "Tanggal Akhir ".$tgl_akhir."<br>";
    //$query = "SELECT * FROM report_produksi_harian WHERE lokasi_produksi = '$dep' AND waktu BETWEEN '$tgl_awal' AND '$tgl_akhir'";
    $qr = $this->db->query("SELECT * FROM dt_produksi_mesin WHERE lokasi = '$dep' AND tanggal_produksi BETWEEN '$tgl_awal' AND '$tgl_akhir'");
    if($this->session->userdata('hak') == 'Manager'){
       if($access=="false"){
           $data = array(
               'title' => 'Report Produksi Mesin',
               'sess_nama' => $this->session->userdata('nama'),
               'sess_id' => $this->session->userdata('id'),
               'data_table' => $this->data_model->get_record('user'),
               'loti' => 'true',
               'dep' => $dep,
               'tgl' => $tgl,
               'pass' => $pass
           );
           $this->load->view('part/main_head', $data);
           $this->load->view('part/left_sidebar', $data);
           $this->load->view('new_page/report_produksi_lock2', $data);
           $this->load->view('part/main_js_dttable');
       } else {
           $data = array(
               'title' => 'Report Produksi Mesin',
               'sess_nama' => $this->session->userdata('nama'),
               'sess_id' => $this->session->userdata('id'),
               'data_table' => $this->data_model->get_record('user'),
               'dep' => $dep,
               'tgl' => $tgl,
               'tgl_awal' => $tgl_awal,
               'tgl_akhir' => $tgl_akhir,
               'qrdata' => $qr
           );
           $this->load->view('part/main_head', $data);
           $this->load->view('part/left_sidebar', $data);
           $this->load->view('new_page/report_produksi_mesin', $data);
           $this->load->view('part/main_js_dttable');
       }
    } else {
       $this->load->view('blok_view');
    }

    
    
 } //end

function direksi(){
    $data = array(
        'title' => 'Welcome - Special Report',
        'sess_nama' => $this->session->userdata('nama'),
    );
    $this->load->view('part/main_head', $data);
    $this->load->view('part/left_sidebar2', $data);
    $this->load->view('beranda_view', $data);
    $this->load->view('part/main_js');
} //end

function dasboarddireksi(){
    $dep = $this->input->post('dep');
    $tgl = $this->input->post('datesr');
    if(!empty($dep) AND !empty($tgl)){
        $ex = explode(' - ', $tgl);
        $ex1 = explode('/', $ex[0]);
        $tgl_awal = $ex1[2]."-".$ex1[0]."-".$ex1[1];
        $ex2 = explode('/', $ex[1]);
        $tgl_akhir = $ex2[2]."-".$ex2[0]."-".$ex2[1];
        $tgl1 = $tgl_awal;
        $tgl2 = $tgl_akhir;
    } else {
        $dep = "null";
        $tgl1 = "null";
        $tgl2 = "null";
    }
    $data = array(
        'title' => 'Management Report',
        'sess_nama' => $this->session->userdata('nama'),
        'daterange' => 'true',
        'frmdep' => $dep,
        'frmtgl1' => $tgl1,
        'frmtgl2' => $tgl2
    );
    $this->load->view('part/main_head', $data);
    $this->load->view('part/left_sidebar2', $data);
    $this->load->view('new_page/direksi_report', $data);
    $this->load->view('part/main_js_dttable');
} //end

    function dashboardwa(){
        $data = array(
            'title' => 'Laporan-Harian-Produksi-',
            'sess_nama' => $this->session->userdata('nama'),
            'daterange' => 'true'
        );
        $this->load->view('part/main_head_toprint', $data);
        //$this->load->view('part/left_sidebar2', $data);
        $this->load->view('baru/laporan_wa', $data);
        $this->load->view('part/main_js_toprint', $data);
    } //end

    function dashboardwa2(){
        $data = array(
            'title' => 'Laporan-Harian-Produksi-dan-Penjualan-',
            'sess_nama' => $this->session->userdata('nama'),
            'daterange' => 'true'
        );
        $this->load->view('part/main_head_toprint', $data);
        //$this->load->view('part/left_sidebar2', $data);
        $this->load->view('baru/laporan_wa2', $data);
        $this->load->view('part/main_js_toprint', $data);
    } //end

    function insgrey(){
        $tgl = $this->uri->segment(3);
        $ex = explode('-', $tgl);
        $printTgl = $ex[2]." ".$this->data_model->printBln($ex[1])." ".$ex[0];
        echo "<h1>Hasil Inspect Grey </h1><br><small>Tanggal ".$printTgl."</small><hr>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>No.</th>";
        echo "<th>KODE ROLL</th>";
        echo "<th>KONSTRUKSI</th>";
        echo "<th>NO MESIN</th>";
        echo "<th>UKURAN ORI</th>";
        echo "<th>UKURAN BS</th>";
        echo "<th>UKURAN BP</th>";
        echo "<th>OPERATOR</th>";
        echo "<th>KETERANGAN</th>";
        echo "</tr>";
        $no=1;
        $query = $this->db->query("SELECT * FROM data_ig WHERE tanggal='$tgl' AND dep='Samatex' AND operator NOT IN ('pendi','edi','hadi','adenna')");
        $kons = array();
        $ope = array();
        foreach($query->result() as $val){
            $_KD = $val->kode_roll;
            $ket = $this->data_model->get_byid('data_ig_bs', ['kode_roll'=>$_KD])->row("keterangan");
            echo "<tr>";
            echo "<td>".$no."</td>";
            echo "<td>".$val->kode_roll."</td>";
            echo "<td>".$val->konstruksi."</td>";
            echo "<td>".$val->no_mesin."</td>";
            if($val->ukuran_ori < 50){
                echo "<td style='color:red;'>".$val->ukuran_ori."</td>";
            } else {
                echo "<td>".$val->ukuran_ori."</td>";
            }
            echo "<td>".$val->ukuran_bs."</td>";
            echo "<td>".$val->ukuran_bp."</td>";
            echo "<td>".ucwords($val->operator)."</td>";
            echo "<td>".ucwords($ket)."</td>";
            echo "</tr>";
            if (in_array($val->konstruksi, $kons)) {} else { $kons[]=$val->konstruksi ;}
            if (in_array($val->operator, $ope)) {} else { $ope[]=$val->operator ;}
            $no++;
        } //end perulangan

        echo "</table>";
        echo "<hr>";
        echo "<table border='1' style='border-collapse:collapse;'>";
        echo "<tr>";
        echo "<th>No.</th>";
        echo "<th>Operator</th>";
        foreach($kons as $dtkons){
            echo "<th>&nbsp;".$dtkons."&nbsp;</th>";
        }
        echo "<th>Total Per Operator</th>";
        echo "<tr>";
        $nos=1;
        sort($ope);
        foreach($ope as $op){
            echo "<tr>";
            echo "<td>".$nos."</td>";
            echo "<td>".ucwords($op)."</td>";
            $total = 0;
            foreach($kons as $dtkons){
                $nilai = $this->db->query("SELECT SUM(ukuran_ori) AS urk FROM data_ig WHERE konstruksi='$dtkons' AND tanggal='$tgl' AND operator='$op' AND dep='Samatex'")->row("urk");
                if($nilai==""){ echo "<td>-</td>"; } else {
                    if($nilai > 0){
                        echo "<td>".$nilai."</td>";
                        $total+=$nilai;
                    } else {
                        echo "<td>-</td>";
                    }
                }
                //echo "<td>".$nilai."</td>";
            }
            echo "<td>".$total."</td>";
            echo "</tr>";
            $nos++;
        }
        echo "<tr>";
        echo "<th colspan='2'>Total</th>";
        $total2=0;
        foreach($kons as $dtkons){
            $nilai2 = $this->db->query("SELECT SUM(ukuran_ori) AS urk FROM data_ig WHERE konstruksi='$dtkons' AND tanggal='$tgl' AND dep='Samatex'")->row("urk");
                if($nilai2==""){ echo "<th>-</th>"; } else {
                    if($nilai2 > 0){
                        echo "<th>".$nilai2."</th>";
                        $total2+=$nilai2;
                    } else {
                        echo "<th>-</th>";
                    }
                }
            //echo "<th>".$dtkons."</th>";
        }
        echo "<td>".$total2."</td>";
        echo "</tr>";
    } //end

    function insfinish(){
        $tgl = $this->uri->segment(3);
        $ex = explode('-', $tgl);
        $printTgl = $ex[2]." ".$this->data_model->printBln($ex[1])." ".$ex[0];
        echo "<h1>Hasil Inspect Finish </h1><br><small>Tanggal ".$printTgl."</small><hr>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>No.</th>";
        echo "<th>KODE ROLL</th>";
        echo "<th>KONSTRUKSI</th>";
        echo "<th>UKURAN ORI (mtr)</th>";
        echo "<th>UKURAN ORI (yrd)</th>";
        echo "<th>UKURAN BS</th>";
        echo "<th>UKURAN BP</th>";
        echo "<th>CRT</th>";
        echo "<th>OPERATOR</th>";
        echo "<th>KETERANGAN</th>";
        echo "</tr>";
        $no=1;
        $query = $this->db->query("SELECT * FROM data_if WHERE tgl_potong='$tgl'");
        $kons = array();
        $ope = array();
        foreach($query->result() as $val){
            $_KD = $val->kode_roll;
            $ket = $this->data_model->get_byid('data_ig_bs', ['kode_roll'=>$_KD])->row("keterangan");
            echo "<tr>";
            echo "<td>".$no."</td>";
            echo "<td>".$val->kode_roll."</td>";
            echo "<td>".$val->konstruksi."</td>";
            $ukr_ori = $val->ukuran_ori / 0.9144;
            
            if($ukr_ori < 50){
                echo "<td style='color:red;'>".$val->ukuran_ori."</td>";
                echo "<td style='color:red;'>".round($ukr_ori,2)."</td>";
            } else {
                echo "<td>".$val->ukuran_ori."</td>";
                echo "<td>".round($ukr_ori,2)."</td>";
            }
           
            echo "<td>".$val->ukuran_bs."</td>";
            echo "<td>".$val->ukuran_bp."</td>";
            echo "<td>".$val->crt."</td>";
            echo "<td>".ucwords($val->operator)."</td>";
            echo "<td>".ucwords($ket)."</td>";
            echo "</tr>";
            if (in_array($val->konstruksi, $kons)) {} else { $kons[]=$val->konstruksi ;}
            if (in_array($val->operator, $ope)) {} else { $ope[]=$val->operator ;}
            $no++;
        } //end perulangan

        echo "</table>";
        echo "<hr>";
        echo "<table border='1' style='border-collapse:collapse;'>";
        echo "<tr>";
        echo "<th>No.</th>";
        echo "<th>Operator</th>";
        foreach($kons as $dtkons){
            echo "<th>&nbsp;".$dtkons."&nbsp;</th>";
        }
        echo "<th>Total Per Operator</th>";
        echo "<tr>";
        $nos=1;
        sort($ope);
        foreach($ope as $op){
            echo "<tr>";
            echo "<td>".$nos."</td>";
            echo "<td>".ucwords($op)."</td>";
            $total = 0;
            foreach($kons as $dtkons){
                $nilai = $this->db->query("SELECT SUM(ukuran_ori) AS urk FROM data_if WHERE konstruksi='$dtkons' AND tgl_potong='$tgl' AND operator='$op' ")->row("urk");
                if($nilai==""){ echo "<td>-</td>"; } else {
                    if($nilai > 0){
                        $nilai_yard = $nilai / 0.9144;
                        echo "<td>&nbsp;".round($nilai_yard,2)."&nbsp;</td>";
                        $total+=$nilai;
                    } else {
                        echo "<td>-</td>";
                    }
                }
                //echo "<td>".$nilai."</td>";
            }
            $total_yard = $total / 0.9144;
            echo "<td>".round($total_yard,2)."</td>";
            echo "</tr>";
            $nos++;
        }
        echo "<tr>";
        echo "<th colspan='2'>Total</th>";
        $total2=0;
        foreach($kons as $dtkons){
            $nilai2 = $this->db->query("SELECT SUM(ukuran_ori) AS urk FROM data_if WHERE konstruksi='$dtkons' AND tgl_potong='$tgl'")->row("urk");
                if($nilai2==""){ echo "<th>-</th>"; } else {
                    if($nilai2 > 0){
                        $nilai2_yard = $nilai2 / 0.9144;
                        echo "<th>".round($nilai2_yard,2)."</th>";
                        $total2+=$nilai2;
                    } else {
                        echo "<th>-</th>";
                    }
                }
            //echo "<th>".$dtkons."</th>";
        }
        $total2_yard = $total2 / 0.9144;
        echo "<td>".round($total2_yard,2)."</td>";
        echo "</tr>";
    } //end

    function folgrey(){
        $tgl = $this->uri->segment(3);
        $ex = explode('-', $tgl);
        $printTgl = $ex[2]." ".$this->data_model->printBln($ex[1])." ".$ex[0];
        echo "<h1>Hasil Folding Grey </h1><br><small>Tanggal ".$printTgl."</small><hr>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>No.</th>";
        echo "<th>KODE ROLL</th>";
        echo "<th>KONSTRUKSI</th>";
        echo "<th>UKURAN</th>";
        echo "<th>OPERATOR</th>";
        echo "</tr>";
        $no=1;
        $query = $this->db->query("SELECT * FROM data_fol WHERE tgl='$tgl' AND jns_fold='Grey'");
        $kons = array();
        $ope = array();
        foreach($query->result() as $val){
            echo "<tr>";
            echo "<td>".$no."</td>";
            echo "<td>".$val->kode_roll."</td>";
            echo "<td>".$val->konstruksi."</td>";
            echo "<td>".$val->ukuran."</td>";
            echo "<td>".ucwords($val->operator)."</td>";
            echo "</tr>";
            if (in_array($val->konstruksi, $kons)) {} else { $kons[]=$val->konstruksi ;}
            if (in_array($val->operator, $ope)) {} else { $ope[]=$val->operator ;}
            $no++;
        } //end perulangan

        echo "</table>";
        echo "<hr>";
        echo "<table border='1' style='border-collapse:collapse;'>";
        echo "<tr>";
        echo "<th>No.</th>";
        echo "<th>Operator</th>";
        foreach($kons as $dtkons){
            echo "<th>&nbsp;".$dtkons."&nbsp;</th>";
        }
        echo "<th>Total Per Operator</th>";
        echo "<tr>";
        $nos=1;
        sort($ope);
        foreach($ope as $op){
            echo "<tr>";
            echo "<td>".$nos."</td>";
            echo "<td>".ucwords($op)."</td>";
            $total = 0;
            foreach($kons as $dtkons){
                $nilai = $this->db->query("SELECT SUM(ukuran) AS urk FROM data_fol WHERE konstruksi='$dtkons' AND jns_fold='Grey' AND tgl='$tgl' AND operator='$op' ")->row("urk");
                if($nilai==""){ echo "<td>-</td>"; } else {
                    if($nilai > 0){
                        echo "<td>&nbsp;".$nilai."&nbsp;</td>";
                        $total+=$nilai;
                    } else {
                        echo "<td>-</td>";
                    }
                }
                //echo "<td>".$nilai."</td>";
            }
            echo "<td>".$total."</td>";
            echo "</tr>";
            $nos++;
        }
        echo "<tr>";
        echo "<th colspan='2'>Total</th>";
        $total2=0;
        foreach($kons as $dtkons){
            $nilai2 = $this->db->query("SELECT SUM(ukuran) AS urk FROM data_fol WHERE konstruksi='$dtkons' AND jns_fold='Grey' AND tgl='$tgl'")->row("urk");
                if($nilai2==""){ echo "<th>-</th>"; } else {
                    if($nilai2 > 0){
                        echo "<th>".$nilai2."</th>";
                        $total2+=$nilai2;
                    } else {
                        echo "<th>-</th>";
                    }
                }
            //echo "<th>".$dtkons."</th>";
        }
        
        echo "<td>".$total2."tes</td>";
        echo "</tr>";
    } //end

    function folfinish(){
        $tgl = $this->uri->segment(3);
        $ex = explode('-', $tgl);
        $printTgl = $ex[2]." ".$this->data_model->printBln($ex[1])." ".$ex[0];
        echo "<h1>Hasil Folding Finish </h1><br><small>Tanggal ".$printTgl."</small><hr>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>No.</th>";
        echo "<th>KODE ROLL</th>";
        echo "<th>KONSTRUKSI</th>";
        echo "<th>UKURAN</th>";
        echo "<th>OPERATOR</th>";
        echo "</tr>";
        $no=1;
        $query = $this->db->query("SELECT * FROM data_fol WHERE tgl='$tgl' AND jns_fold='Finish'");
        $kons = array();
        $ope = array();
        foreach($query->result() as $val){
            echo "<tr>";
            echo "<td>".$no."</td>";
            echo "<td>".$val->kode_roll."</td>";
            echo "<td>".$val->konstruksi."</td>";
            echo "<td>".$val->ukuran."</td>";
            echo "<td>".ucwords($val->operator)."</td>";
            echo "</tr>";
            if (in_array($val->konstruksi, $kons)) {} else { $kons[]=$val->konstruksi ;}
            if (in_array($val->operator, $ope)) {} else { $ope[]=$val->operator ;}
            $no++;
        } //end perulangan

        echo "</table>";
        echo "<hr>";
        echo "<table border='1' style='border-collapse:collapse;'>";
        echo "<tr>";
        echo "<th>No.</th>";
        echo "<th>Operator</th>";
        foreach($kons as $dtkons){
            echo "<th>&nbsp;".$dtkons."&nbsp;</th>";
        }
        echo "<th>Total Per Operator</th>";
        echo "<tr>";
        $nos=1;
        sort($ope);
        foreach($ope as $op){
            echo "<tr>";
            echo "<td>".$nos."</td>";
            echo "<td>".ucwords($op)."</td>";
            $total = 0;
            foreach($kons as $dtkons){
                $nilai = $this->db->query("SELECT SUM(ukuran) AS urk FROM data_fol WHERE konstruksi='$dtkons' AND jns_fold='Finish' AND tgl='$tgl' AND operator='$op' ")->row("urk");
                if($nilai==""){ echo "<td>-</td>"; } else {
                    if($nilai > 0){
                        echo "<td>&nbsp;".$nilai."&nbsp;</td>";
                        $total+=$nilai;
                    } else {
                        echo "<td>-</td>";
                    }
                }
                //echo "<td>".$nilai."</td>";
            }
            echo "<td>".$total."</td>";
            echo "</tr>";
            $nos++;
        }
        echo "<tr>";
        echo "<th colspan='2'>Total</th>";
        $total2=0;
        foreach($kons as $dtkons){
            $nilai2 = $this->db->query("SELECT SUM(ukuran) AS urk FROM data_fol WHERE konstruksi='$dtkons' AND jns_fold='Finish' AND tgl='$tgl'")->row("urk");
                if($nilai2==""){ echo "<th>-</th>"; } else {
                    if($nilai2 > 0){
                        echo "<th>".$nilai2."</th>";
                        $total2+=$nilai2;
                    } else {
                        echo "<th>-</th>";
                    }
                }
            //echo "<th>".$dtkons."</th>";
        }
        
        echo "<td>".$total2."tes</td>";
        echo "</tr>";
    } //end

}
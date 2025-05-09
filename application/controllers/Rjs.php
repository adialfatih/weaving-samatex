<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rjs extends CI_Controller
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
  function stokreal(){
        $this->load->view('chart/show');
  }
   
  function index(){ 
      $this->load->view('block');
  } //end

  function produksi(){
        $bulan = $this->uri->segment(3);
        $newyear = $this->uri->segment(4);
        $nb = sprintf('%02d', $bulan);
        if(intval($bulan) > 0 && intval($bulan) < 13){
        $printBulan = $this->data_model->printBln($nb);
        if($newyear==""){
            $tahun = date('Y'); 
        } else {
            $tahun = $newyear;
        }
        $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $nb, $tahun);
        echo "<h1>LAPORAN BULAN ". strtoupper($printBulan) ." ".$tahun."</h1>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>TANGGAL</th>";
        echo "<th>INSPECT GREY</th>";
        echo "<th>BS</th>";
        echo "<th>BP</th>";
        echo "</tr>";
        $total_ig = 0; $total_bs = 0; $all_bp = 0;
        for ($tanggal = 1; $tanggal <= $jumlah_hari; $tanggal++) {
            $tanggal_lengkap = sprintf('%02d', $tanggal); // menambahkan leading zero jika diperlukan
            $tanggal_bulan_tahun = $tahun . '-' . $nb . '-' . $tanggal_lengkap;
            $tgl_show = $tanggal_lengkap."/".$nb."/".$tahun;
            
            $tanggal_sebelumnya = date("Y-m-d", strtotime($tanggal_bulan_tahun . " -1 day"));
           
            $tanggal_sesudahnya = date("Y-m-d", strtotime($tanggal_bulan_tahun . " +1 day"));
            
            $cek_true3 = $this->data_model->get_byid('data_ig', ['tanggal'=>$tanggal_bulan_tahun,'shift_op'=>'0'])->num_rows();
            $cek_true = $this->data_model->get_byid('data_ig', ['tanggal'=>$tanggal_bulan_tahun,'shift_op'=>'31'])->num_rows();
            $cek_true2 = $this->data_model->get_byid('data_ig', ['tanggal'=>$tanggal_sesudahnya,'shift_op'=>'31'])->num_rows();
            $pengurangan_ig = 0; $pengurangan_bs = 0; $pengurangan_bp = 0;
            if($cek_true > 0){
                $pengurangan_ig = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_ig WHERE tanggal='$tanggal_bulan_tahun' AND shift_op='31'")->row("ukr");
                $pengurangan_bs = $this->db->query("SELECT SUM(ukuran_bs) AS ukr FROM data_ig WHERE tanggal='$tanggal_bulan_tahun' AND shift_op='31'")->row("ukr");
                $pengurangan_bp = $this->db->query("SELECT SUM(ukuran_bp) AS ukr FROM data_ig WHERE tanggal='$tanggal_bulan_tahun' AND shift_op='31'")->row("ukr");
            }
            $penambahan_ig = 0; $penambahan_bs = 0; $penambahan_bp = 0;
            if($cek_true2 > 0){
                $penambahan_ig = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_ig WHERE tanggal='$tanggal_sesudahnya' AND shift_op='31'")->row("ukr");
                $penambahan_bs = $this->db->query("SELECT SUM(ukuran_bs) AS ukr FROM data_ig WHERE tanggal='$tanggal_sesudahnya' AND shift_op='31'")->row("ukr");
                $penambahan_bp = $this->db->query("SELECT SUM(ukuran_bp) AS ukr FROM data_ig WHERE tanggal='$tanggal_sesudahnya' AND shift_op='31'")->row("ukr");
            }

            echo "<tr>";
            if($cek_true3 > 0){
                echo "<td style='color:red;font-weight:bold;'>".$tgl_show."</td>"; 
            } else {
                echo "<td>".$tgl_show."</td>"; 
            }
            //HITUNG INSPECT GREY INSPECT FINISH
            
            $ins_grey_asli = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_ig WHERE tanggal='$tanggal_bulan_tahun' AND dep='RJS'")->row("ukr");
            $ins_grey_min = $ins_grey_asli - $pengurangan_ig;
            $ins_grey = $ins_grey_min + $penambahan_ig;
            $total_ig+=$ins_grey;

            $bs_grey_asli = $this->db->query("SELECT SUM(ukuran_bs) AS ukr FROM data_ig WHERE tanggal='$tanggal_bulan_tahun' AND dep='RJS'")->row("ukr");
            $bs_grey_min = $bs_grey_asli - $pengurangan_bs;
            $bs_grey = $bs_grey_min + $penambahan_bs;
            $total_bs+=$bs_grey;

            $bp_grey_asli = $this->db->query("SELECT SUM(ukuran_bp) AS ukr FROM data_ig WHERE tanggal='$tanggal_bulan_tahun' AND dep='RJS'")->row("ukr");
            $bp_grey_min = $bp_grey_asli - $pengurangan_bp;
            $bp_grey = $bp_grey_min + $penambahan_bp;
            $total_bp+=$bp_grey;
            
            if($ins_grey < 1){ $ig_show = "-"; } else {
                if(fmod($ins_grey, 1) !== 0.00){
                    $ig_show = number_format($ins_grey,2,',','.');
                } else {
                    $ig_show = number_format($ins_grey,0,',','.');
                }
            }
            
            if($ig_show=="-"){ echo "<td>-</td>"; } else {
            ?><td><a href="<?=base_url('rjs/insgrey/'.$tanggal_bulan_tahun);?>" style="text-decoration:none;color:#000;"><?=$ig_show;?></a></td><?php }
            echo "<td>".$bs_grey."</td>";
            echo "<td>".$bp_grey."</td>";
            echo "</tr>";
            //echo $tanggal_bulan_tahun . "<br>";
            //}
        } //END PERULANGAN
        if(fmod($total_ig, 1) !== 0.00){
            $total_ig2 = number_format($total_ig,2,',','.');
        } else {
            $total_ig2 = number_format($total_ig,0,',','.');
        }
        
        echo "<tr>";
        echo "<th>TOTAL</th>";
        echo "<th>".$total_ig2."</th>";
        echo "<th>".$total_bs."</th>";
        echo "<th>".$total_bp."</th>";
        echo "</tr>";
        echo "</table>";
        echo "<hr>";
        $alldata = $this->db->query("SELECT * FROM tb_konstruksi");
        
        echo "<h1>STOK DI RINDANG JATI</h1>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th style='padding:3px 20px;'>Konstruksi</th>";
        echo "<th style='padding:3px 20px;'>Stok ORI</th>";
        echo "<th style='padding:3px 20px;'>Stok BS</th>";
        echo "<th style='padding:3px 20px;'>Stok BP</th>";
        $_total_stok = 0;
        $_total_bs = 0;
        $_total_bp = 0;
        foreach($alldata->result() as $kons):
            $kt = $kons->kode_konstruksi;
            $stok = $this->db->query("SELECT SUM(ukuran_ori) as ukr FROM data_ig WHERE konstruksi='$kt' AND dep='RJS' AND loc_now LIKE '%RJS%'")->row("ukr");
            $stok_bs = $this->db->query("SELECT SUM(ukuran_bs) as ukr FROM data_ig WHERE konstruksi='$kt' AND dep='RJS'")->row("ukr");
            $stok_bp = $this->db->query("SELECT SUM(ukuran_bp) as ukr FROM data_ig WHERE konstruksi='$kt' AND dep='RJS'")->row("ukr");
            if($stok > 0 ) {
                echo "<tr>";
                echo "<td style='padding:3px 20px;'>".$kons->kode_konstruksi."</td>";
                echo "<td style='padding:3px 20px;'>".$stok."</td>";
                echo "<td style='padding:3px 20px;'>".$stok_bs."</td>";
                echo "<td style='padding:3px 20px;'>".$stok_bp."</td>";
                echo "</tr>";
            }
            $_total_stok+=$stok;
            $_total_bs+=$stok_bs;
            $_total_bp+=$stok_bp;
        endforeach;
        echo "<tr style='background:#424040;color:#fff;'>";
        echo "<td style='padding:3px 20px;'>TOTAL</td>";
        echo "<td style='padding:3px 20px;'>".$_total_stok."</td>";
        echo "<td style='padding:3px 20px;'>".$_total_bs."</td>";
        echo "<td style='padding:3px 20px;'>".$_total_bp."</td>";
        echo "</tr>";
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
        $tgl_sebelumnya = date("Y-m-d", strtotime($tgl . " -1 day"));
        $tgl_sesudahnya = date("Y-m-d", strtotime($tgl . " +1 day"));
        $ex = explode('-', $tgl);
        $printTgl = $ex[2]." ".$this->data_model->printBln($ex[1])." ".$ex[0];
        echo "<h1>Hasil Inspect Grey </h1><br><small>Tanggal ".$printTgl."</small><hr>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>No.</th>";
        echo "<th>KODE ROLL</th>";
        echo "<th>KONSTRUKSI</th>";
        echo "<th>NO MESIN</th>";
        echo "<th>NO BEAM</th>";
        echo "<th>OKA</th>";
        echo "<th>UKURAN ORI</th>";
        echo "<th>UKURAN BS</th>";
        echo "<th>UKURAN BP</th>";
        echo "<th>OPERATOR</th>";
        echo "<th>SHIFT</th>";
        echo "<th>WAKTU INPUT</th>";
        echo "</tr>";
        $no=1;
        $query = $this->db->query("SELECT * FROM data_ig WHERE tanggal='$tgl' AND dep='RJS' AND shift_op!='31' ");
        $query2 = $this->db->query("SELECT * FROM data_ig WHERE tanggal='$tgl_sesudahnya' AND dep='RJS' AND shift_op='31' ");
        $kons = array();
        $ope = array();
        $shift_op = array();
        foreach($query->result() as $val){
            echo "<tr>";
            echo "<td>".$no."</td>";
            echo "<td>".$val->kode_roll."</td>";
            echo "<td>".$val->konstruksi."</td>";
            echo "<td>".$val->no_mesin."</td>";
            echo "<td>".$val->no_beam."</td>";
            echo "<td>".$val->oka."</td>";
            if($val->ukuran_ori < 50){
                echo "<td style='color:red;'><strong>".$val->ukuran_ori."</strong></td>";
            } else {
                echo "<td><strong>".$val->ukuran_ori."</strong></td>"; 
                
            }
            echo "<td>".$val->ukuran_bs."</td>";
            echo "<td>".$val->ukuran_bp."</td>";
            echo "<td>".ucwords($val->operator)."</td>";
            echo "<td>".$val->shift_op."</td>";
            $lk = explode(' ', $val->kode_upload);
            echo "<td style='text-align:center;'>".$lk[1]."</td>";
            echo "</tr>";
            $val_kons = strtoupper($val->konstruksi);
            $val_oprt = strtolower($val->operator);
            if (in_array($val_kons, $kons)) {} else { $kons[]=$val_kons; }
            if (in_array($val_oprt, $ope)) {} else { $ope[]=$val_oprt; }
            $no++;
        } //end perulangan
        foreach($query2->result() as $bal){
            echo "<tr>";
            echo "<td>".$no."</td>";
            echo "<td>".$bal->kode_roll."</td>";
            echo "<td>".$bal->konstruksi."</td>";
            echo "<td>".$bal->no_mesin."</td>";
            echo "<td>".$bal->no_beam."</td>";
            echo "<td>".$bal->oka."</td>";
            if($bal->ukuran_ori < 50){
                echo "<td style='color:red;'><strong>".$bal->ukuran_ori."</strong></td>";
            } else {
                echo "<td><strong>".$bal->ukuran_ori."</strong></td>"; 
                
            }
            echo "<td>".$bal->ukuran_bs."</td>";
            echo "<td>".$bal->ukuran_bp."</td>";
            echo "<td>".ucwords($bal->operator)."</td>";
            if($bal->shift_op == "31"){ echo "<td>3</td>"; } else {
            echo "<td>".$bal->shift_op."</td>"; }
            $mk = explode(' ', $bal->kode_upload);
            $mj = explode('-', $mk[0]);
            echo "<td style='text-align:center;'>".$mj[2]."/".$mj[1]."/".$mj[0]." ".$mk[1]."</td>";
            echo "</tr>";
            $bal_kons = strtoupper($bal->konstruksi);
            $bal_oprt = strtolower($bal->operator);
            if (in_array($bal_kons, $kons)) {} else { $kons[]=$bal_kons; }
            if (in_array($bal_oprt, $ope)) {} else { $ope[]=$bal_oprt; }
            $no++;
        } //end perulangan

        echo "</table>";
        $jumlah_kons = count($kons);
        $jumlah_row = $jumlah_kons + 2;
        echo "<hr>";
        $cek_true = $this->data_model->get_byid('data_ig',['shift_op'=>'0', 'tanggal'=>$tgl])->num_rows();
        if($cek_true > 0){
            //jika shift belum berjalan
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
                    $nilai = $this->db->query("SELECT SUM(ukuran_ori) AS urk FROM data_ig WHERE konstruksi='$dtkons' AND tanggal='$tgl' AND operator='$op' AND dep='RJS'")->row("urk");
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
                $nilai2 = $this->db->query("SELECT SUM(ukuran_ori) AS urk FROM data_ig WHERE konstruksi='$dtkons' AND tanggal='$tgl' AND dep='RJS'")->row("urk");
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
            //end shift belum berjalan
        } else {      
        echo "<table border='1' style='border-collapse:collapse;'>";
        //shift 1
        echo "<tr style='background:#e3e3e3;'>";
        ?><td colspan="<?=$jumlah_row;?>" style="font-weight:bold;">Shift 1</td><?php
        echo "</tr>";

        echo "<tr>";
        echo "<th style='padding:7px 15px;'>Nama Operator</th>";
        for ($i=0; $i <count($kons) ; $i++) { 
            echo "<th style='padding:7px 15px;'>".$kons[$i]."</th>";
        }
        echo "<th style='padding:7px 15px;'>Total</th>";
        echo "</tr>";
        $op_shift1 = $this->db->query("SELECT * FROM data_ig WHERE tanggal='$tgl' AND shift_op='1'");
        $operator_shift1 = array();
        foreach($op_shift1->result() as $op1){
            $op1_opt = strtolower($op1->operator);
            if(in_array($op1_opt, $operator_shift1)){} else {$operator_shift1[]=$op1_opt;}
        }
        foreach($operator_shift1 as $nm_op1){
            echo "<tr>";
            echo "<td style='padding:7px 15px;'>".$nm_op1."</td>";
            $total_untuk_op = 0;
            for ($i2=0; $i2 <count($kons) ; $i2++) { 
                $total_perkons = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_ig WHERE konstruksi='$kons[$i2]' AND tanggal='$tgl' AND operator='$nm_op1' AND dep='RJS' AND shift_op='1'")->row("ukr");
                if($total_perkons > 0){
                    echo "<td style='padding:7px 15px;'>".$total_perkons."</td>";
                    $total_untuk_op+=$total_perkons;
                } else {
                    echo "<td style='padding:7px 15px;'>-</td>";
                }
            }
            echo "<td>".$total_untuk_op."</td>";
            echo "</tr>";
        }
        //end shift1
        //shift 2
        echo "<tr style='background:#e3e3e3;'>";
        ?><td colspan="<?=$jumlah_row;?>" style="font-weight:bold;">Shift 2</td><?php
        echo "</tr>";
        echo "<tr>";
        echo "<th style='padding:7px 15px;'>Nama Operator</th>";
        for ($a=0; $a <count($kons) ; $a++) { 
            echo "<th style='padding:7px 15px;'>".$kons[$a]."</th>";
        }
        echo "<th style='padding:7px 15px;'>Total</th>";
        echo "</tr>";
        $op_shift2 = $this->db->query("SELECT * FROM data_ig WHERE tanggal='$tgl' AND shift_op='2'");
        $operator_shift2 = array();
        foreach($op_shift2->result() as $op2){
            $op2_opt = strtolower($op2->operator);
            if(in_array($op2_opt, $operator_shift2)){} else {$operator_shift2[]=$op2_opt;}
        }
        foreach($operator_shift2 as $nm_op2){
            echo "<tr>";
            echo "<td style='padding:7px 15px;'>".$nm_op2."</td>";
            $total_untuk_op2 = 0;
            for ($i3=0; $i3 <count($kons) ; $i3++) { 
                $total_perkons2 = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_ig WHERE konstruksi='$kons[$i3]' AND tanggal='$tgl' AND operator='$nm_op2' AND dep='RJS' AND shift_op='2'")->row("ukr");
                if($total_perkons2 > 0){
                    echo "<td style='padding:7px 15px;'>".$total_perkons2."</td>";
                    $total_untuk_op2+=$total_perkons2;
                } else {
                    echo "<td style='padding:7px 15px;'>-</td>";
                }
            }
            echo "<td>".$total_untuk_op2."</td>";
            echo "</tr>";
        }
        //end shift 2
        //shift 3
        echo "<tr style='background:#e3e3e3;'>";
        ?><td colspan="<?=$jumlah_row;?>" style="font-weight:bold;">Shift 3 tes</td><?php
        echo "</tr>";
        echo "<tr>";
        echo "<th style='padding:7px 15px;'>Nama Operator</th>";
        for ($b=0; $b <count($kons) ; $b++) { 
            echo "<th style='padding:7px 15px;'>".$kons[$b]."</th>";
        }
        echo "<th style='padding:7px 15px;'>Total</th>";
        echo "</tr>";
        $op_shift3 = $this->db->query("SELECT * FROM data_ig WHERE tanggal='$tgl' AND shift_op='3'");
        $op_shift_31 = $this->db->query("SELECT * FROM data_ig WHERE tanggal='$tgl_sesudahnya' AND shift_op='31'");
        $operator_shift3 = array();
        foreach($op_shift3->result() as $op3){
            $op3_opt = strtolower($op3->operator);
            if(in_array($op3->operator, $operator_shift3)){} else {$operator_shift3[]=$op3->operator;}
        }
        foreach($op_shift_31->result() as $op_31){
            $op31_opt = strtolower($op_31->operator);
            if(in_array($op31_opt, $operator_shift3)){} else {$operator_shift3[]=$op31_opt;}
        }
        foreach($operator_shift3 as $nm_op3){
            echo "<tr>";
            echo "<td style='padding:7px 15px;'>".$nm_op3."</td>";
            $total_untuk_op3 = 0;
            for ($i7=0; $i7 <count($kons) ; $i7++) { 
                $total_perkons_3 = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_ig WHERE konstruksi='$kons[$i7]' AND tanggal='$tgl' AND operator='$nm_op3' AND dep='RJS' AND shift_op='3'")->row("ukr");
                $total_perkons_31 = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_ig WHERE konstruksi='$kons[$i7]' AND tanggal='$tgl_sesudahnya' AND operator='$nm_op3' AND dep='RJS' AND shift_op='31'")->row("ukr");
                $total_perkons3  = $total_perkons_3 + $total_perkons_31;
                if($total_perkons3 > 0){
                    echo "<td style='padding:7px 15px;'>".$total_perkons3."</td>";
                    $total_untuk_op3+=$total_perkons3;
                } else {
                    echo "<td style='padding:7px 15px;'>-</td>";
                }
            }
            echo "<td>".$total_untuk_op3."</td>";
        }
        //end shift 3
        echo $tgl_sesudahnya;
        }
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
        echo "<th>UKURAN ORI</th>";
        echo "<th>UKURAN BS</th>";
        echo "<th>UKURAN BP</th>";
        echo "<th>OPERATOR</th>";
        echo "</tr>";
        $no=1;
        $query = $this->db->query("SELECT * FROM data_if WHERE tgl_potong='$tgl'");
        $kons = array();
        $ope = array();
        foreach($query->result() as $val){
            echo "<tr>";
            echo "<td>".$no."</td>";
            echo "<td>".$val->kode_roll."</td>";
            echo "<td>".$val->konstruksi."</td>";
            $ukr_ori = $val->ukuran_ori / 0.9144;
            echo "<td>".round($ukr_ori,2)."</td>";
            echo "<td>".$val->ukuran_bs."</td>";
            echo "<td>".$val->ukuran_bp."</td>";
            echo "<td>".ucwords($val->operator)."</td>";
            echo "</tr>";
            $val_kons = strtoupper($val->konstruksi);
            $val_oprt = strtolower($val->operator);
            if (in_array($val_kons, $kons)) {} else { $kons[]=$val_kons;}
            if (in_array($valoprt, $ope)) {} else { $ope[]=$val_oprt ;}
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
            $val_kons = strtoupper($val->konstruksi);
            $val_oprt = strtolower($val->operator);
            if (in_array($val_kons, $kons)) {} else { $kons[]=$val_kons; }
            if (in_array($val_oprt, $ope)) {} else { $ope[]=$val_oprt; }
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
            $val_kons = strtoupper($val->konstruksi);
            $val_oprt = strtolower($val->operator);
            if (in_array($val_kons, $kons)) {} else { $kons[]=$val_kons; }
            if (in_array($val_oprt, $ope)) {} else { $ope[]=$val_oprt; }
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
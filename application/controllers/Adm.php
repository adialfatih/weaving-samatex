<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adm extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
      if($this->session->userdata('hak') != "Manager"){
        //redirect(base_url('block'));
      }
      
  }
  function cekins(){
      $cek = $this->db->query("SELECT * FROM `data_ig` WHERE `tanggal` BETWEEN '2024-10-31' AND '2024-11-29' AND dep='RJS'");
      echo "<table border='1'>";
        echo "<tr>";
        echo "<td>NO</td>";
        echo "<td>TANGGAL</td>";
        echo "<td>KODE ROLL</td>";
        echo "<td>KONSTRUKSI</td>";
        echo "<td>UKURAN INSPECT</td>";
        echo "<td>UKURAN FOLDING GREY</td>";
        echo "<td>SUSUT</td>";
        echo "</tr>";
        $no = 1;
        foreach($cek->result() as $val){
            $kode_roll=strtoupper($val->kode_roll);
            $ori = $val->ukuran_ori;
            $folding = $this->db->query("SELECT kode_roll,ukuran,jns_fold FROM data_fol WHERE kode_roll='$kode_roll' AND jns_fold='Grey'")->row("ukuran");
            if($folding==""){$folding=0;}
            $susut = $ori - $folding;
            echo "<tr>";
            echo "<td>".$no."</td>";
            echo "<td>".$val->tanggal."</td>";
            echo "<td>".$kode_roll."</td>";
            echo "<td>".$val->konstruksi."</td>";
            echo "<td>".$val->ukuran_ori."</td>";
            echo "<td>".$folding."</td>";
            echo "<td>".$susut."</td>";
            $no++;
        }
        echo "</table>";
  }
  function cekproduksibs(){
      $cek = $this->db->query("SELECT * FROM `data_ig_bs` WHERE `tgl` BETWEEN '2024-11-01' AND '2024-11-30' AND dep='Samatex' GROUP BY kode_roll");
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>NO</td>";
        echo "<td>TANGGAL</td>";
        echo "<td>KODE ROLL</td>";
        echo "<td>UKURAN BS</td>";
        echo "<td>KETERANGAN</td>";
        echo "<td>DEP</td>";
        echo "<td>KONSTRUKSI</td>";
        echo "<td>OPERATOR</td>";
        echo "<td>HASIL INSPECT</td>";
        echo "</tr>";
        $no = 1;
        foreach($cek->result() as $val){
            $kode_roll=strtoupper($val->kode_roll);
            $ni = $this->data_model->get_byid('data_if',['kode_roll'=>$kode_roll]);
            if($ni->num_rows() == 0){
                $ukuran = $this->data_model->get_byid('data_ig',['kode_roll'=>$kode_roll])->row("ukuran_ori");
            } else {
                $ukuran_sebelum = $this->db->query("SELECT SUM(ukuran_sebelum) AS jml FROM data_if WHERE kode_roll = '$kode_roll'")->row("jml");
                $ukuran_insf_meter = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_if WHERE kode_roll = '$kode_roll'")->row("jml");
                $ukuran_insf_yard = $ukuran_insf_meter / 0.9144;
                $ukuran = $ukuran_insf_meter;
            }
            if (strpos($kode_roll, 'S') === 0) {
                $dep = "Samatex";
            } elseif (strpos($kode_roll, 'R') === 0) {
                $dep = "RJS";
            } else {
                $dep = "Unknown"; // Nilai default jika tidak diawali dengan huruf S atau R
            }
            $kons = $this->db->query("SELECT kode_roll,konstruksi FROM data_ig WHERE kode_roll='$kode_roll'")->row("konstruksi");
            echo "<tr>";
            echo "<td>".$no."</td>";
            echo "<td>".$val->tgl."</td>";
            echo "<td>".strtoupper($val->kode_roll)."</td>";
            echo "<td>".$val->ukuran_bs."</td>";
            echo "<td>".$val->keterangan."</td>";
            echo "<td>".$dep."</td>";
            echo "<td>".strtoupper($kons)."</td>";
            echo "<td>".strtoupper($val->operator)."</td>";
            echo "<td>".$ukuran."</td>";
            echo "</tr>";
            $no++;
        }
        echo "</table>";
      
  }
  function bsbp(){
        //$cek = $this->db->query("SELECT * FROM data_ig WHERE ukuran_ori>0 AND ukuran_ori<=20 AND tanggal BETWEEN '2024-09-01' AND '2024-09-31' AND dep='RJS'");
        //$cek = $this->db->query("SELECT * FROM data_ig WHERE ukuran_ori>0 AND ukuran_ori<=20 AND tanggal ='2024-09-01' AND dep='RJS' AND shift_op!='31' ");
        $cek = $this->db->query("SELECT * FROM data_ig WHERE ukuran_ori>0 AND ukuran_ori<=20 AND tanggal ='2024-10-01' AND dep='RJS' AND shift_op='31'");
        $cek = $this->db->query("SELECT * FROM data_ig WHERE ukuran_ori>0 AND ukuran_ori<=20 AND tanggal ='2024-10-01' AND dep='RJS' AND shift_op='31'");
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>NO</td>";
        echo "<td>KODE ROLL</td>";
        echo "<td>KONSTRUKSI</td>";
        echo "<td>UKURAN ORI</td>";
        echo "<td>UKURAN BS</td>";
        echo "<td>UKURAN BP</td>";
        echo "</tr>";
        $no = 1;
        foreach($cek->result() as $val){
            echo "<tr>";
            echo "<td>".$no."</td>";
            echo "<td>".$val->kode_roll."</td>";
            echo "<td>".$val->konstruksi."</td>";
            echo "<td>".$val->ukuran_ori."</td>";
            echo "<td>".$val->ukuran_bs."</td>";
            echo "<td>".$val->ukuran_bp."</td>";
            echo "</tr>";
            $no++;
        }
        echo "</table>";
  }
  function hapusa(){
      $qr = $this->db->query("SELECT * FROM `data_fol` WHERE `posisi` LIKE 'PKT6320'");
      echo "<table border='1'>";
      echo "<tr>";
      echo "<td>NO</td>";
      echo "<td>KODE ROLL</td>";
      echo "<td>KONSTRUKSI</td>";
      echo "<td>UKURAN</td>";
      echo "<td>FOLDING</td>";
      echo "<td>TANGGAL</td>";
      echo "<td>OPERATOR</td>";
      echo "<td>POSISI</td>";
      echo "</tr>";
      $no=1;
      foreach($qr->result() as $val){
          echo "<tr>";
          echo "<td>".$no++."</td>";
          echo "<td>".$val->kode_roll."</td>";
          echo "<td>".$val->konstruksi."</td>";
          echo "<td>".$val->ukuran."</td>";
          echo "<td>".$val->jns_fold."</td>";
          echo "<td>".$val->tgl."</td>";
          echo "<td>".$val->operator."</td>";
          echo "<td>".$val->posisi."</td>";
          echo "</tr>";
      }
  }
  function index(){ 
      $this->load->view('blok_view');
  } //end
  function owek(){
      echo "owek";
      $dbs = $this->db->query("SELECT * FROM `new_tb_isi` WHERE `kd` LIKE '%PKT2799%'");
      echo "<pre>";
      print_r($dbs->result());
      echo "</pre>";
      foreach($dbs->result() as $v){
          $kode_roll = $v->kode;
          $this->db->query("UPDATE data_fol SET posisi='PKT2799' WHERE kode_roll='$kode_roll'");
      }
  }
  

  function rproduksi(){ 
    if($this->session->userdata('hak') == 'Manager'){
      $data = array(
          'title' => 'Report Produksi - PPC Weaving',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'dt_kons' => $this->data_model->get_record('tb_konstruksi'),
          'daterange' => 'true'
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('page/report_view2', $data);
      $this->load->view('part/main_js_dttable');
    } else {
      $this->load->view('blok_view');
    }
  } //end

  function reportmesin(){
    if($this->session->userdata('hak') == 'Manager'){
      $data = array(
          'title' => 'Report Produksi - PPC Weaving',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'dt_table' => $this->data_model->sort_record('id_produksi','tb_produksi'),
          'dt_kons' => $this->data_model->get_record('tb_konstruksi'),
          'daterange' => 'true'
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('page/report_view3', $data);
      $this->load->view('part/main_js_dttable');
    } else {
      $this->load->view('blok_view');
    }
  }

  function rstoklama(){ 
    if($this->session->userdata('hak') == 'Manager'){
      $data = array(
          'title' => 'Report Stok Lama - PPC Weaving',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'dt_kons' => $this->data_model->get_record('tb_konstruksi')
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('page/report_view_sl', $data);
      $this->load->view('part/main_js_dttable');
    } else {
      $this->load->view('blok_view');
    }
  } //end

  function this_report(){
      if($this->session->userdata('hak') == 'Manager'){
        $data = array(
            'title' => 'Report Produksi - PPC Weaving',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'dt_table' => $this->data_model->sort_record('id_produksi','tb_produksi'),
            'dt_kons' => $this->data_model->get_record('tb_konstruksi')
        );
        $this->load->view('part/main_head_toprint', $data);
        //$this->load->view('part/left_sidebar', $data);
        $this->load->view('page/report_view_image', $data);
        $this->load->view('part/main_js_toprint');
      } else {
        $this->load->view('blok_view');
      }
  } //end

  function this_report2(){
      $my_img = imagecreate( 200, 80 );
      $background = imagecolorallocate( $my_img, 0, 0, 255 );
      $text_colour = imagecolorallocate( $my_img, 255, 255, 0 );
      $line_colour = imagecolorallocate( $my_img, 128, 255, 0 );
      imagestring( $my_img, 4, 30, 25, "text",
        $text_colour );
      imagesetthickness ( $my_img, 5 );
      imageline( $my_img, 30, 45, 165, 45, $line_colour );
      ob_start();
      imagepng( $my_img );
      $image_string = ob_get_flush();
      $imageb64 = base64_encode($image_string);
      imagecolordeallocate( $line_color );
      imagecolordeallocate( $text_color );
      imagecolordeallocate( $background );
      imagedestroy( $my_img );
      $url = "data:image/png;base64,".$imageb64;
      return $url;
      
  } //end

  function rstok(){ 
    $dep=$this->session->userdata('departement');
    $dt_kons = $this->data_model->get_record('tb_konstruksi');
    $kd_konstruksi = array();
    $dt_rjs = array(25, 30, 90, 85, 50, 45);
    $dt_pst = array(30, 30, 40, 65, 90, 80);
    $dt_smt = array(40, 90 ,90, 40, 45, 60);
    foreach($dt_kons->result() as $kons):
      $kd_konstruksi[]="'".$kons->kode_konstruksi."'";
    endforeach;
    $chart_kd_konstruksi = implode(", ", $kd_konstruksi);
    $chart_rjs = implode(", ", $dt_rjs);
    $chart_pst = implode(", ", $dt_pst);
    $chart_smt = implode(", ", $dt_smt);
    // echo $chart_kd_konstruksi."<br>";
    // echo $chart_rjs."<br>";
    // echo $chart_pst."<br>";
    // echo $chart_smt."<br>";
    if($this->session->userdata('hak') == 'Manager'){
      $data = array(
          'title' => 'Stok Barang - PPC Weaving',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'sess_dep' => $this->session->userdata('departement'),
          'dt_table' => $dt_kons,
          'dtstok' => $this->data_model->get_byid('report_stok', ['departement'=>$dep]),
          'crt_kd' => $chart_kd_konstruksi,
          'crt_rjs' => $chart_rjs,
          'crt_pst' => $chart_pst,
          'crt_smt' => $chart_smt
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('page/report_stok', $data);
      $this->load->view('part/main_jss2');
    } else {
      $this->load->view('blok_view');
    }
  } //end

  function rpenjualan(){ 
    if($this->session->userdata('hak') == 'Manager'){
      $data = array(
          'title' => 'Manage Data User - PPC Weaving',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'data_table' => $this->data_model->get_record('user'),
          'kode' => $this->data_model->get_record('tb_konstruksi')
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('page/report_penjualan_view', $data);
      $this->load->view('part/main_js_dttable');
    } else {
      $this->load->view('blok_view');
    }
  } //end

  function mnguser(){ 
    if($this->session->userdata('hak') == 'Manager'){
      $data = array(
          'title' => 'Manage Data User - PPC Weaving',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'data_table' => $this->data_model->get_record('user')
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('page/user_view', $data);
      $this->load->view('part/main_js_dttable');
    } else {
      $this->load->view('blok_view');
    }
  } //end

  function logactv(){ 
    if($this->session->userdata('hak') == 'Manager'){
      $data = array(
          'title' => 'Aktivitas User - PPC Weaving',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'data_table' => $this->data_model->sort_record('id_logprogram', 'log_program')
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('page/log_aktivitas', $data);
      $this->load->view('part/main_js_dttable');
    } else {
      $this->load->view('blok_view');
    }
  } //end

    
}
?>
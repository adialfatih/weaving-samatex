<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Input extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
      // if($this->session->userdata('login_form') != "rindangjati_sess"){
      //   redirect(base_url('login'));
      // }
      
  }
   
  function index(){ 
      $this->load->view('block');
  } //end

  function produksi(){ 
      $url = $this->uri->segment(3);
      //echo $url;
      $dep = $this->session->userdata('departement');
      $data = array(
        'title' => 'Data Produksi',
        'sess_nama' => $this->session->userdata('nama'),
        'sess_id' => $this->session->userdata('id'),
        'dtkons2' => $this->db->query("SELECT * FROM data_produksi WHERE dep='$dep' AND tgl='$url'"),
        'uri_tgl' => $url,
        'depuser' => $dep,
        'tgl' => $url
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('new_page/new_input_produksi2', $data);
      $this->load->view('part/main_js_dttable');
  } //end

  function stokpusatex(){ 
      $this->load->view('input_pusatex');
  } //end

  function rollpst(){
      $query = $this->db->query("SELECT * FROM new_roll_onpst ORDER BY id_auto25 DESC LIMIT 100");
      echo "<tr>";
      echo "<td><strong>Kode Roll</strong></td>";
      echo "<td><strong>Ukuran</strong></td>";
      echo "<td><strong>Konstruksi</strong></td>";
      echo "<td><strong>#</strong></td>";
      echo "</tr>";
      foreach($query->result() as $val){
          echo "<tr>";
          echo "<td>".$val->kode_roll."</td>";
          echo "<td>".$val->ukuran."</td>";
          echo "<td>".$val->kons."</td>";
          
          echo "<td><a style='text-decoration:none;color:red;' onclick='return confirm_delete()' href='".base_url('input/hapus/'.$val->kode_roll)."'>Hapus</a></td>";
          
          echo "</tr>";
      }
  } //end

  function hapus(){
      $kd = $this->uri->segment(3);
      $this->db->query("DELETE FROM new_roll_onpst WHERE kode_roll='$kd'");
      $cekIG = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kd]);
      if($cekIG->num_rows() == 1){
          $yginput = $cekIG->row("yg_input");
          if($yginput == "Pusatex"){
              $this->db->query("DELETE FROM data_ig WHERE kode_roll='$kd'");
          }
      }
      redirect(base_url('pusatex'));
  }

  function inputkode(){
      $kd = $this->input->post('kdrol');
      $ukr = $this->input->post('ori');
      $kons = $this->input->post('kons');
      $cekkons = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kons]);
      if($cekkons->num_rows() == 1){
      $cek1 = $this->data_model->get_byid('new_roll_onpst', ['kode_roll'=>$kd]);
      if($cek1->num_rows() > 0){
          echo json_encode(array("statusCode"=>404, "psn"=>"Kode Roll sudah digunakan dan sudah berada di Pusatex"));
      } else {
          $cek2 = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kd]);
          if($cek2->num_rows() > 0){
            echo json_encode(array("statusCode"=>404, "psn"=>"Kode Roll sudah digunakan dan sudah melalui proses folding"));
          } else {
            $cek3 = $this->data_model->get_byid('data_if', ['kode_roll'=>$kd]);
            if($cek3->num_rows() > 0){
              echo json_encode(array("statusCode"=>404, "psn"=>"Kode Roll sudah digunakan dan sudah melalui proses inspect finish"));
            } else {
                $cek4 = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kd]);
                if($cek4->num_rows() == 1){
                     $kons_asli = $cek4->row("konstruksi");
                     if($kons_asli == $kons){
                     $this->data_model->saved('new_roll_onpst', [
                          'kode_roll' => $kd,
                          'ukuran' => $ukr,
                          'kons' => $kons,
                     ]);
                     $this->data_model->updatedata('kode_roll',$kd,'data_ig', ['loc_now'=>'Pusatex']);
                     echo json_encode(array("statusCode"=>200, "psn"=>"Kode Roll di posisikan ke Pusatex"));
                     } else {
                        echo json_encode(array("statusCode"=>404, "psn"=>"Kode Roll sudah digunakan konstruksi yang berbeda"));
                     }
                } elseif($cek4->num_rows() == 0) {
                    $this->data_model->saved('new_roll_onpst', [
                          'kode_roll' => $kd,
                          'ukuran' => $ukr,
                          'kons' => $kons,
                    ]);
                    $this->data_model->saved('data_ig', [
                          'kode_roll' => $kd,
                          'konstruksi' => $kons,
                          'no_mesin' => 0,
                          'no_beam' => 0,
                          'oka' => 0,
                          'ukuran_ori' => $ukr,
                          'ukuran_bs' => 0,
                          'ukuran_bp' => 0,
                          'tanggal' => date('Y-m-d'),
                          'operator' => 'Pusatex',
                          'bp_can_join' => $ukr<50 ? 'true':'false',
                          'dep' => 'Samatex',
                          'loc_now' => 'Pusatex',
                          'yg_input' => 'Pusatex',
                          'kode_upload' => 'Pusatex'
                    ]);
                    echo json_encode(array("statusCode"=>200, "psn"=>"Kode Roll ditambahkan ke Pusatex"));
                } else {
                    echo json_encode(array("statusCode"=>404, "psn"=>"Kode Roll sudah digunakan dan terdeteksi ganda."));
                }
            }
          }
      }

      } else {
        echo json_encode(array("statusCode"=>404, "psn"=>"Konstruksi tidak ditemukan"));
      }
  } //end


}
?>
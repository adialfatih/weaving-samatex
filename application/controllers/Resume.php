<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resume extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
      if($this->session->userdata('login_form') != "rindangjati_sess"){
        redirect(base_url('login'));
      }
      
  }
   
  function index(){ 
      $this->load->view('block');
  } //end

  function insgrey(){ 
        $kode = $this->uri->segment(3);
        $cekkode = $this->data_model->get_byid('temp_upload_ig', ['kode_upload'=>$kode]);
        $dep = $this->session->userdata('departement');
        $data = array(
            'title' => 'Resume Hasil Upload Inspect Grey',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'dep_user' => $dep,
            'alldata' => $cekkode,
            'kode' => $kode
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/resume_insgrey', $data);
        $this->load->view('part/main_js');
    
  } //end

  function insfinish(){ 
        $kode = $this->uri->segment(3);
        $cekkode = $this->data_model->get_byid('temp_upload_if', ['kodeauto'=>$kode]);
        $cekkode2 = $this->data_model->get_byid('temp_upload_if2', ['kodeauto'=>$kode]);
        $dep = $this->session->userdata('departement');
        $data = array(
            'title' => 'Resume Hasil Upload Inspect Finish',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'dep_user' => $dep,
            'alldata' => $cekkode,
            'alldata2' => $cekkode2,
            'kode' => $kode
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/resume_insfinish', $data);
        $this->load->view('part/main_js');
    
  } //end//

  function savedinsfinish(){ 
    $kode = $this->uri->segment(3);
    $cekkode = $this->data_model->get_byid('temp_upload_if', ['kodeauto'=>$kode]);
    $cekkode2 = $this->data_model->get_byid('temp_upload_if2', ['kodeauto'=>$kode]);
    $dep = $this->session->userdata('departement');
    $data = array(
        'title' => 'Resume Hasil Upload Inspect Finish',
        'sess_nama' => $this->session->userdata('nama'),
        'sess_id' => $this->session->userdata('id'),
        'dep_user' => $dep,
        'alldata' => $cekkode,
        'alldata2' => $cekkode2,
        'kode' => $kode
    );
    $this->load->view('part/main_head', $data);
    $this->load->view('part/left_sidebar', $data);
    $this->load->view('baru/resume_insfinish_saved', $data);
    $this->load->view('part/main_js');

} //end//savedinsfinish

  function savedinsgrey(){
      $kode = $this->uri->segment(3);
      $cekkode = $this->data_model->get_byid('temp_upload_ig', ['kode_upload'=>$kode]);
      $dep = $this->session->userdata('departement');
      $data = array(
          'title' => 'Resume Hasil Upload Inspect Grey',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'dep_user' => $dep,
          'alldata' => $cekkode,
          'kode' => $kode
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('baru/resume_insgrey_saved', $data);
      $this->load->view('part/main_js');

  } //end

  function deleteinsgrey(){
        $kode = $this->uri->segment(3);
        $this->db->query("DELETE FROM temp_upload_ig WHERE kode_upload='$kode'");
        $this->db->query("DELETE FROM log_input_roll WHERE kodeauto='$kode'");
        redirect(base_url('cek-produksi'));
  }

  function deleteinsfinish(){
        $kode = $this->uri->segment(3);
        $this->db->query("DELETE FROM temp_upload_if WHERE kodeauto='$kode'");
        $this->db->query("DELETE FROM temp_upload_if2 WHERE kodeauto='$kode'");
        $this->db->query("DELETE FROM log_input_roll WHERE kodeauto='$kode'");
        redirect(base_url('cek-produksi'));
  }

    
}
?>
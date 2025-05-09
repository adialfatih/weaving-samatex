<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller
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

  function harian(){ 
     $dep = $this->input->post('dep');
     $tgl = $this->input->post('datesr');
     echo $tgl;
  } //end

}
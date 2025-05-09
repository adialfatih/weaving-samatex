<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Under_construction extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
   //    $this->load->model('data_model');
   //    if($this->session->userdata('status') != "login"){
			// redirect(base_url("auth_login"));
	    //}
  }
   
  function index(){
      $data = array(
          'nama'  => $this->session->userdata('user'),
          'email' => $this->session->userdata('email'),
          'hakses' => $this->session->userdata('hakses'),
          'title' => 'Under Construction'
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/top_navbar');
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('part/right_sidebar');
      $this->load->view('construct_view');
      $this->load->view('part/main_js');
  }
   
}
?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operator extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      if($this->session->userdata('login_form') != "rindangjati_sess"){
        redirect(base_url('login'));
      }
  }
   
  function index(){ 
      $dep = $this->session->userdata('departement');
      $data = array(
          'title' => 'Data Operator',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'sess_hak' => $this->session->userdata('hak'),
          'sess_dep' => $dep,
          'records' => $this->data_model->get_byid('a_operator', ['dep'=>$dep])
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('users/operator_view', $data);
      $this->load->view('part/main_js');
  } //end

  function simpan(){
        $nama = $this->data_model->clean($this->input->post('nama'));
        $user = $this->data_model->clean($this->input->post('username'));
        $tugs = $this->data_model->clean($this->input->post('tugas'));
        $deps = $this->data_model->clean($this->input->post('dep'));
        $kecilkan_nama = strtolower($nama);
        $kecilkan_user = strtolower($user);
        if($nama!="" AND $user!="" AND $tugs!="" AND $deps!=""){
            $cek = $this->data_model->get_byid('a_operator', ['username'=>$user])->num_rows();
            if($cek == 0){
            $dtlist = [
                'username' => $kecilkan_user,
                'nama_opt' => ucwords($kecilkan_nama),
                'produksi' => $tugs,
                'dep' => $deps
            ];
            $this->data_model->saved('a_operator', $dtlist);
            }
            $this->session->set_flashdata('announce', 'Berhasil menyimpan');
            redirect(base_url('operator'));
        } else {
            $this->session->set_flashdata('gagal', 'Anda tidak memasukan data dengan benar.');
            redirect(base_url('operator'));
        }
  }//end

  function delopt(){
        $username = strtolower($this->input->post('idopt'));
        $this->data_model->delete('a_operator', 'username', $username);
        $this->session->set_flashdata('gagal', 'Hapus operator sukses');
        redirect(base_url('operator'));
  } //edn

  function update(){
        $nama = $this->data_model->clean($this->input->post('nama'));
        $user = $this->data_model->clean($this->input->post('username'));
        $tugs = $this->data_model->clean($this->input->post('tugas'));
        $id = $this->data_model->clean($this->input->post('idopt'));
        $kecikan_user = strtolower($user);
        $kecikan_nama = strtolower($nama);
        $cek = $this->data_model->get_byid('a_operator', ['username'=>$user])->num_rows();
        if($cek==0){
            $dtlist = [
                'username' => $kecikan_user,
                'nama_opt' => ucwords($kecikan_nama),
                'produksi' => $tugs
            ];
            $this->data_model->updatedata('id_operator',$id, 'a_operator', $dtlist);
        } 
        $this->session->set_flashdata('announce', 'Berhasil menyimpan');
        redirect(base_url('operator'));
  }


}
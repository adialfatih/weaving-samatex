<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan extends CI_Controller
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
      echo "Token erorr..";
  } //end

  function id(){
        $id = $this->uri->segment(3);
        $hak = $this->session->userdata('hak');
        $dep = $this->session->userdata('departement');
        $cek_id = $this->data_model->get_byid('v_penjualan', ['sha1(id_penjualan)'=>$id]);
        if($cek_id->num_rows() == 1){
            $data = array(
                'title' => 'Data Penjualan',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'nomor' => $cek_id->row("id_penjualan"),
                'dt_all' => $cek_id->row_array(),
                'dep_user' => $dep,
                'kons' => $this->data_model->get_record('dt_konsumen'),
                'dt_terjual' => $this->data_model->sort_record('id_penjualan', 'tb_penjualan')
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            $this->load->view('page/data_penjualan', $data);
            $this->load->view('part/main_js_dttable');
        } else {
            $this->session->set_flashdata('gagal', 'Token tidak ditemukan / kadaluarsa.');
            redirect(base_url('input-penjualan'));
        }
  } //end 

  function kd(){
    $id = $this->uri->segment(3);
    $hak = $this->session->userdata('hak');
    $dep = $this->session->userdata('departement');
    $cek_id = $this->data_model->get_byid('v_penjualan', ['sha1(id_penjualan)'=>$id]);
    if($cek_id->num_rows() == 1){
        $data = array(
            'title' => 'Data Penjualan',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'nomor' => $cek_id->row("id_penjualan"),
            'dt_all' => $cek_id->row_array(),
            'dep_user' => $dep,
            'kons' => $this->data_model->get_record('dt_konsumen'),
            'dt_terjual' => $this->data_model->sort_record('id_penjualan', 'tb_penjualan')
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('page/data_penjualan2', $data);
        $this->load->view('part/main_js_dttable');
    } else {
        $this->session->set_flashdata('gagal', 'Token tidak ditemukan / kadaluarsa.');
        redirect(base_url('input-penjualan'));
    }
} //end 
function customer(){
    echo "<table border='1'>";
    echo "<tr>";
    echo "<td>No.</td>";
    echo "<td>ID</td>";
    echo "<td>Customer</td>";
    echo "<td>JANUARI</td>";
    echo "<td>FEBRUARI</td>";
    echo "<td>MARET</td>";
    echo "<td>APRIL</td>";
    echo "<td>MEI</td>";
    echo "<td>JUNI</td>";
    echo "<td>JULI</td>";
    echo "</tr>";
    echo "<tr>";
        echo "<td>1</td>";
        echo "<td>136</td>";
        echo "<td>BIMATEX</td>";
        echo "<td>";
            $januari = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-01'")->row("ttl");
        echo $januari;
        echo "</td>";
        echo "<td>";
            $feb = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-02'")->row("ttl");
        echo $feb;
        echo "</td>";
        echo "<td>";
            $mar = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-03'")->row("ttl");
        echo $mar;
        echo "</td>";
        echo "<td>";
            $apr = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-04'")->row("ttl");
        echo $apr;
        echo "</td>";
        echo "<td>";
            $mei = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-05'")->row("ttl");
        echo $mei;
        echo "</td>";
        echo "<td>";
            $jun = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-06'")->row("ttl");
        echo $jun;
        echo "</td>";
        echo "<td>";
            $jul = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-07'")->row("ttl");
        echo $jul;
        echo "</td>";
        echo "</tr>";
        // end  bimatex
        echo "<tr>";
        echo "<td>2</td>";
        echo "<td>100</td>";
        echo "<td>KEMBANG MENAWAN</td>";
        echo "<td>";
            $januari = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='100' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-01'")->row("ttl");
        echo $januari;
        echo "</td>";
        echo "<td>";
            $feb = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='100' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-02'")->row("ttl");
        echo $feb;
        echo "</td>";
        echo "<td>";
            $mar = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='100' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-03'")->row("ttl");
        echo $mar;
        echo "</td>";
        echo "<td>";
            $apr = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='100' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-04'")->row("ttl");
        echo $apr;
        echo "</td>";
        echo "<td>";
            $mei = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='100' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-05'")->row("ttl");
        echo $mei;
        echo "</td>";
        echo "<td>";
            $jun = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='100' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-06'")->row("ttl");
        echo $jun;
        echo "</td>";
        echo "<td>";
            $jul = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='100' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-07'")->row("ttl");
        echo $jul;
        echo "</td>";
        echo "</tr>";
        //end kembang menawan
        echo "<tr>";
        echo "<td>3</td>";
        echo "<td>119</td>";
        echo "<td>BALONG</td>";
        echo "<td>";
            $januari = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-01'")->row("ttl");
        echo $januari;
        echo "</td>";
        echo "<td>";
            $feb = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-02'")->row("ttl");
        echo $feb;
        echo "</td>";
        echo "<td>";
            $mar = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-03'")->row("ttl");
        echo $mar;
        echo "</td>";
        echo "<td>";
            $apr = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-04'")->row("ttl");
        echo $apr;
        echo "</td>";
        echo "<td>";
            $mei = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-05'")->row("ttl");
        echo $mei;
        echo "</td>";
        echo "<td>";
            $jun = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-06'")->row("ttl");
        echo $jun;
        echo "</td>";
        echo "<td>";
            $jul = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-07'")->row("ttl");
        echo $jul;
        echo "</td>";
        echo "</tr>";
        // end balong
        echo "<tr>";
        echo "<td>3</td>";
        echo "<td>10</td>";
        echo "<td>PUTRI DIANA</td>";
        echo "<td>";
            $januari = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-01'")->row("ttl");
        echo $januari;
        echo "</td>";
        echo "<td>";
            $feb = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-02'")->row("ttl");
        echo $feb;
        echo "</td>";
        echo "<td>";
            $mar = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-03'")->row("ttl");
        echo $mar;
        echo "</td>";
        echo "<td>";
            $apr = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-04'")->row("ttl");
        echo $apr;
        echo "</td>";
        echo "<td>";
            $mei = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-05'")->row("ttl");
        echo $mei;
        echo "</td>";
        echo "<td>";
            $jun = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-06'")->row("ttl");
        echo $jun;
        echo "</td>";
        echo "<td>";
            $jul = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-07'")->row("ttl");
        echo $jul;
        echo "</td>";
        echo "</tr>";

    $qr = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'KM%' ORDER BY id_konsumen ASC");
    foreach ($qr->result() as $key => $value) {
        $_id = $value->id_konsumen;
        echo "<tr>";
        echo "<td>".($key+1)."</td>";
        echo "<td>".$value->id_konsumen."</td>";
        echo "<td>".$value->nama_konsumen."</td>";
        echo "<td>";
            $januari = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='$_id' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-01'")->row("ttl");
        echo $januari;
        echo "</td>";
        echo "<td>";
            $feb = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='$_id' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-02'")->row("ttl");
        echo $feb;
        echo "</td>";
        echo "<td>";
            $mar = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='$_id' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-03'")->row("ttl");
        echo $mar;
        echo "</td>";
        echo "<td>";
            $apr = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='$_id' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-04'")->row("ttl");
        echo $apr;
        echo "</td>";
        echo "<td>";
            $mei = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='$_id' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-05'")->row("ttl");
        echo $mei;
        echo "</td>";
        echo "<td>";
            $jun = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='$_id' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-06'")->row("ttl");
        echo $jun;
        echo "</td>";
        echo "<td>";
            $jul = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='$_id' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-07'")->row("ttl");
        echo $jul;
        echo "</td>";
        echo "</tr>";
    } 
    echo "</table>";
} //end customer

function customer2(){
    echo "<table border='1'>";
    echo "<tr>";
    echo "<td>No.</td>";
    echo "<td>ID</td>";
    echo "<td>Customer</td>";
    echo "<td>JANUARI</td>";
    echo "<td>FEBRUARI</td>";
    echo "<td>MARET</td>";
    echo "<td>APRIL</td>";
    echo "<td>MEI</td>";
    echo "<td>JUNI</td>";
    echo "<td>JULI</td>";
    echo "</tr>";
    echo "<tr>";
        echo "<td>1</td>";
        echo "<td>136</td>";
        echo "<td>BIMATEX</td>";
        echo "<td>";
            $januari = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-01'")->row("ttl");
        echo $januari."<br>";
            $_jan = $this->db->query("SELECT * FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-01'");
            echo "<table border='1'>";
            foreach($_jan->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $feb = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-02'")->row("ttl");
        echo $feb."<br>";
            $_feb = $this->db->query("SELECT * FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-02'");
            echo "<table border='1'>";
            foreach($_feb->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $mar = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-03'")->row("ttl");
        echo $mar."<br>";
            $_mar = $this->db->query("SELECT * FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-03'");
            echo "<table border='1'>";
            foreach($_mar->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $apr = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-04'")->row("ttl");
        echo $apr."<br>";
            $_apr = $this->db->query("SELECT * FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-04'");
            echo "<table border='1'>";
            foreach($_apr->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $mei = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-05'")->row("ttl");
        echo $mei."<br>";
            $_mei = $this->db->query("SELECT * FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-05'");
            echo "<table border='1'>";
            foreach($_mei->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $jun = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-06'")->row("ttl");
        echo $jun."<br>";
            $_jun = $this->db->query("SELECT * FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-06'");
            echo "<table border='1'>";
            foreach($_jun->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $jul = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-07'")->row("ttl");
        echo $jul."<br>";
            $_jul = $this->db->query("SELECT * FROM view_nota WHERE id_customer='136' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-07'");
            echo "<table border='1'>";
            foreach($_jul->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "</tr>";
        // end  bimatex
        echo "<tr>";
        echo "<td>2</td>";
        echo "<td>100</td>";
        echo "<td>KEMBANG MENAWAN</td>";
        echo "<td>";
            $januari = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='100' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-01'")->row("ttl");
        echo $januari;
        echo "</td>";
        echo "<td>";
            $feb = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='100' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-02'")->row("ttl");
        echo $feb;
        echo "</td>";
        echo "<td>";
            $mar = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='100' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-03'")->row("ttl");
        echo $mar;
        echo "</td>";
        echo "<td>";
            $apr = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='100' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-04'")->row("ttl");
        echo $apr;
        echo "</td>";
        echo "<td>";
            $mei = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='100' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-05'")->row("ttl");
        echo $mei;
        echo "</td>";
        echo "<td>";
            $jun = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='100' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-06'")->row("ttl");
        echo $jun;
        echo "</td>";
        echo "<td>";
            $jul = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='100' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-07'")->row("ttl");
        echo $jul;
        echo "</td>";
        echo "</tr>";
        //end kembang menawan
        echo "<tr>";
        echo "<td>3</td>";
        echo "<td>119</td>";
        echo "<td>BALONG</td>";
        echo "<td>";
            $januari = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-01'")->row("ttl");
        echo $januari."<br>";
            $_jan = $this->db->query("SELECT * FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-01'");
            echo "<table border='1'>";
            foreach($_jan->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $feb = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-02'")->row("ttl");
        echo $feb."<br>";
            $_feb = $this->db->query("SELECT * FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-02'");
            echo "<table border='1'>";
            foreach($_feb->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $mar = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-03'")->row("ttl");
        echo $mar."<br>";
            $_mar = $this->db->query("SELECT * FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-03'");
            echo "<table border='1'>";
            foreach($_mar->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $apr = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-04'")->row("ttl");
        echo $apr."<br>";
            $_apr = $this->db->query("SELECT * FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-04'");
            echo "<table border='1'>";
            foreach($_apr->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $mei = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-05'")->row("ttl");
        echo $mei."<br>";
            $_mei = $this->db->query("SELECT * FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-05'");
            echo "<table border='1'>";
            foreach($_mei->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $jun = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-06'")->row("ttl");
        echo $jun."<br>";
            $_jun = $this->db->query("SELECT * FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-06'");
            echo "<table border='1'>";
            foreach($_jun->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $jul = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-07'")->row("ttl");
        echo $jul."<br>";
            $_jul = $this->db->query("SELECT * FROM view_nota WHERE id_customer='119' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-07'");
            echo "<table border='1'>";
            foreach($_jul->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "</tr>";
        // end balong
        echo "<tr>";
        echo "<td>3</td>";
        echo "<td>10</td>";
        echo "<td>PUTRI DIANA</td>";
        echo "<td>";
            $januari = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-01'")->row("ttl");
        echo $januari."<br>";
            $_januari = $this->db->query("SELECT * FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-01'");
            echo "<table border='1'>";
            foreach($_januari->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $feb = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-02'")->row("ttl");
        echo $feb."<br>";
            $_feb = $this->db->query("SELECT * FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-02'");
            echo "<table border='1'>";
            foreach($_feb->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $mar = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-03'")->row("ttl");
        echo $mar."<br>";
            $_mar = $this->db->query("SELECT * FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-03'");
            echo "<table border='1'>";
            foreach($_mar->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $apr = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-04'")->row("ttl");
        echo $apr."<br>";
            $_apr = $this->db->query("SELECT * FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-04'");
            echo "<table border='1'>";
            foreach($_apr->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $mei = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-05'")->row("ttl");
        echo $mei."<br>";
            $_mei = $this->db->query("SELECT * FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-05'");
            echo "<table border='1'>";
            foreach($_mei->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $jun = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-06'")->row("ttl");
        echo $jun."<br>";
            $_jun = $this->db->query("SELECT * FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-06'");
            echo "<table border='1'>";
            foreach($_jun->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "<td>";
            $jul = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-07'")->row("ttl");
        echo $jul."<br>";
            $_jul = $this->db->query("SELECT * FROM view_nota WHERE id_customer='10' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-07'");
            echo "<table border='1'>";
            foreach($_jul->result() as $val){
                echo "<tr><td>".$val->konstruksi."</td><td>".$val->total_panjang."</td><td>".$val->tgl_nota."</td></tr>";
            }
            echo "</table>";
        echo "</td>";
        echo "</tr>";

    $qr = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'KM%' ORDER BY id_konsumen ASC");
    foreach ($qr->result() as $key => $value) {
        $_id = $value->id_konsumen;
        echo "<tr>";
        echo "<td>".($key+1)."</td>";
        echo "<td>".$value->id_konsumen."</td>";
        echo "<td>".$value->nama_konsumen."</td>";
        echo "<td>";
            $januari = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='$_id' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-01'")->row("ttl");
        echo $januari;
        echo "</td>";
        echo "<td>";
            $feb = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='$_id' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-02'")->row("ttl");
        echo $feb;
        echo "</td>";
        echo "<td>";
            $mar = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='$_id' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-03'")->row("ttl");
        echo $mar;
        echo "</td>";
        echo "<td>";
            $apr = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='$_id' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-04'")->row("ttl");
        echo $apr;
        echo "</td>";
        echo "<td>";
            $mei = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='$_id' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-05'")->row("ttl");
        echo $mei;
        echo "</td>";
        echo "<td>";
            $jun = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='$_id' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-06'")->row("ttl");
        echo $jun;
        echo "</td>";
        echo "<td>";
            $jul = $this->db->query("SELECT SUM(total_panjang) AS ttl FROM view_nota WHERE id_customer='$_id' AND DATE_FORMAT(tgl_nota, '%Y-%m') = '2024-07'")->row("ttl");
        echo $jul;
        echo "</td>";
        echo "</tr>";
    } 
    echo "</table>";
} //end customer 2

}
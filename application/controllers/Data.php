<?php
defined('BASEPATH') OR exit('No direct script access allowed');
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Data extends CI_Controller
{
    function __construct()
    {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
      if($this->session->userdata('login_form') != "rindangjati_sess"){
		  //redirect(base_url("login"));
	  }
    }
   
  function index(){
       echo 'Invalid token';
  } //end
  function data1(){
      $dbs = $this->db->query("SELECT * FROM `dt_produksi_mesin` WHERE `tanggal_produksi` BETWEEN '2024-02-01' AND '2024-02-29' AND lokasi='Samatex';");
      $no=1;
      echo "<table border='1'>";
      echo "<tr>";
      echo "<td>No</td>";
      echo "<td>tgl</td>";
      echo "<td>kons</td>";
      echo "<td>jumlah mesin</td>";
      echo "<td>total produksi</td>";
      echo "</tr>";
      $kons = array();
      foreach($dbs->result() as $val){
          echo "<tr>";
          echo "<td>".$no."</td>";
          echo "<td>".$val->tanggal_produksi."</td>";
          echo "<td>".$val->kode_konstruksi."</td>";
          echo "<td>".$val->jumlah_mesin."</td>";
          echo "<td>".$val->hasil."</td>";
          echo "</tr>";
          $no++;
          if (in_array($val->kode_konstruksi, $kons)){}else { $kons[] = $val->kode_konstruksi; }
      }
      echo "</table>";
      echo "<table border='1'>";
      echo "<tr>";
      echo "<td>KONSTRUKSI</td>";
      echo "<td></td>";
      echo "<td></td>";
      
      echo "</tr>";
      sort($kons);
      
      foreach($kons as $k){
        echo "<tr>";
        echo "<td>".$k."</td>";
        $jmlmesin = $this->db->query("SELECT SUM(jumlah_mesin) as mc FROM dt_produksi_mesin WHERE `tanggal_produksi` BETWEEN '2024-02-01' AND '2024-02-29' AND lokasi='Samatex' AND kode_konstruksi='$k'")->row("mc");
        $hasil = $this->db->query("SELECT SUM(hasil) as hs FROM dt_produksi_mesin WHERE `tanggal_produksi` BETWEEN '2024-02-01' AND '2024-02-29' AND lokasi='Samatex' AND kode_konstruksi='$k'")->row("hs");
        echo "<td>".$jmlmesin."</td>";
        echo "<td>".$hasil."</td>";
        echo "</tr>";
      }
  }

  function rollkain(){
        $dep = $this->session->userdata('departement');
        $data = array(
            'title' => 'Data Roll Kain',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_hak' => $this->session->userdata('hak'),
            'dbdata' => $this->db->query("SELECT * FROM data_ig WHERE dep='$dep' AND loc_now='$dep' ORDER BY id_data DESC LIMIT 1000"),
            'daterange' => 'true'
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/roll_kain', $data);
        $this->load->view('part/main_js_dttable', $data);
  } ///end

  function find(){
      $kode = $this->input->post('kode');
      if($kode==""){
            $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
            redirect(base_url('data-roll'));
      } else {
            $dep = $this->session->userdata('departement');
            $data = array(
                'title' => 'Data Roll Kain',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'sess_hak' => $this->session->userdata('hak'),
                'code' => $kode
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            $this->load->view('baru/roll_kain_find', $data);
            $this->load->view('part/main_js_dttable');
      }
  } ///end

    function produksi(){
        $token = $this->uri->segment(3);
        $cek_token = $this->data_model->get_byid('tb_produksi', ['sha1(kode_produksi)'=>$token]);
        if($cek_token->num_rows() ==1){
            //echo "token anda ".$token;
            $dt = $cek_token->row_array();
            //echo "<br> Mean ".$dt['kode_produksi'];
            $kdp = $dt['kode_produksi'];
            $data = array(
                'title' => 'Data Produksi ('.$kdp.')',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'sess_hak' => $this->session->userdata('hak'),
                'kdp' => $kdp,
                'dt' => $dt,
                'timeline' => $this->data_model->get_byid('log_produksi', ['kode_produksi'=>$kdp])
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            $this->load->view('page/detil_produksi', $data);
            $this->load->view('part/main_js_dttable');
        } else {
            echo "Invalid Token";
        }
    } //end

    function kode(){
        $token = $this->uri->segment(3);
        $cek_token = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$token]);
        if($cek_token->num_rows() ==1){
            $data = array(
                'title' => 'Packing list ('.$token.')',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'sess_hak' => $this->session->userdata('hak'),
                'sess_hak' => $this->session->userdata('hak'),
                'sess_dep' =>$this->session->userdata('departement'),
                'token' => $token,
                'dtkn' => $cek_token->row_array()
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            $this->load->view('new_page/edit_pkg_list', $data);
            $this->load->view('part/main_js_dttable');
        } else {
            echo "Invalid Token";
        }
    } //end

    function list(){
        $token = $this->uri->segment(3);
        $from_ig = "null";
        $cek_token = $this->data_model->get_byid('tb_produksi', ['sha1(kode_produksi)'=>$token]);
        if($cek_token->num_rows() ==1){
            //echo "token anda ".$token;
            $dt = $cek_token->row_array();
            //echo "<br> Mean ".$dt['kode_produksi'];
            $kdp = $dt['kode_produksi'];
            $kode_ch = $dt['id_produksi'];
            $pkg_awal = $dt['st_produksi'];
            if($pkg_awal=='IG'){
                $query = $this->data_model->get_byid('new_tb_pkg_list', ['kode_produksi'=>$kdp]);
            } else {
                $query = $this->data_model->get_byid('new_tb_pkg_ins', ['kode_produksi'=>$kdp]);
                $from_ig = $this->data_model->get_byid('tb_proses_produksi',['ch_to'=>$kode_ch]);
            }
            $data = array(
                'title' => 'Data Produksi ('.$kdp.')',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'sess_hak' => $this->session->userdata('hak'),
                'kdp' => $kdp,
                'dt' => $dt,
                'pkg' => $query,
                'pkg_awal' => $pkg_awal,
                'from_ig' => $from_ig
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            $this->load->view('page/detil_produksi_list', $data);
            $this->load->view('part/main_js_dttable');
        } else {
            echo "Invalid Token";
        }
    } //end

    function nolist(){
        $token = $this->uri->segment(3);
        $tokenlist = $this->uri->segment(4);
        $no_pkg = $this->data_model->get_byid('pkg', ['sha1(nolist)'=>$tokenlist])->row("nolist");
        $alpa = $this->data_model->get_byid('pkg', ['sha1(nolist)'=>$tokenlist])->row("alpabet");
        $cek_token = $this->data_model->get_byid('tb_produksi', ['sha1(kode_produksi)'=>$token]);
        if($cek_token->num_rows() ==1){
            //echo "token anda ".$token;
            $dt = $cek_token->row_array();
            //echo "<br> Mean ".$dt['kode_produksi'];
            $kdp = $dt['kode_produksi'];
            $data = array(
                'title' => 'Data Produksi ('.$kdp.')',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'sess_hak' => $this->session->userdata('hak'),
                'kdp' => $kdp,
                'dt' => $dt,
                'pkg' => $this->data_model->get_byid('tb_packinglist', ['new_pkglist'=>$no_pkg]),
                'alpa' => $alpa
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            $this->load->view('page/detil_produksi_list_break', $data);
            $this->load->view('part/main_js_dttable');
        } else {
            echo "Invalid Token";
        }
    } //end

    function packinglist(){
        $token = $this->uri->segment(3);
        $col = $this->uri->segment(4);
        $column=20;
        if(empty($col) OR $col==0){ $column=20; } else { $column=20+$col; }
        $cek_token = $this->data_model->get_byid('tb_produksi', ['sha1(kode_produksi)'=>$token]);
        if($cek_token->num_rows() ==1){
            //echo "token anda ".$token;
            $dt = $cek_token->row_array();
            //echo "<br> Mean ".$dt['kode_produksi'];
            $kdp = $dt['kode_produksi'];
            $cek_pkglist = $this->data_model->get_byid('new_tb_pkg_list', ['kode_produksi'=>$kdp]);
            $data = array(
                'title' => 'Packing List ('.$kdp.')',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'sess_hak' => $this->session->userdata('hak'),
                'kdp' => $kdp,
                'dt' => $dt,
                'dtlist' => $cek_pkglist->num_rows()==0 ? 'null':'ada',
                'dtlist_view' => $cek_pkglist,
                'satuan_asli' => $dt['satuan'],
                'col' => $column
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            $this->load->view('page/packing_list', $data);
            $this->load->view('part/main_js_dttable');
        } else {
            echo "Invalid Token";
        }
    } //end pkglist

    function folding(){
        $fol = $this->uri->segment(3);
        $kd = $this->uri->segment(4);
        $id_proses = $this->uri->segment(5);
        $ar = $this->data_model->get_byid('tb_proses_produksi', ['id_proses'=>$id_proses]);
        $sendtgl = $ar->row("tgl");
        $stn = $ar->row("satuan");
        $cek_token = $this->data_model->get_byid('tb_produksi', ['sha1(kode_produksi)'=>$kd]);
        //$cek_id = $this->data_model->get_byid('tb_tracking_produksi', ['sha1(id_tracking)'=>$id]);
        if($cek_token->num_rows() ==1){
            //echo "token anda ".$token;
            $dt = $cek_token->row_array();
            //echo "<br> Mean ".$dt['kode_produksi'];
            $kdp = $dt['kode_produksi'];
            if($fol=="ff"){
                $cek_pkglist = $this->data_model->get_byid('new_tb_pkg_ins', ['kode_produksi'=>$kdp]);
            } elseif ($fol=="fg") {
                $cek_pkglist = $this->data_model->get_byid('new_tb_pkg_list', ['kode_produksi'=>$kdp]);
            }
            
            $data = array(
                'title' => 'Packing List ('.$kdp.')',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'sess_hak' => $this->session->userdata('hak'),
                'kdp' => $kdp,
                'tracking' => '0',
                'dt' => $dt,
                'dtlist_view' => $cek_pkglist,
                'aling' => $fol,
                'send_tgl' => $sendtgl,
                'stan' => $stn
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            $this->load->view('page/packing_list_folding', $data);
            $this->load->view('part/main_js_dttable');
        } else {
            echo "Invalid Token";
        }
    } //end 

    function inspect(){
        $kd = $this->uri->segment(3);
        $kd_new = $this->uri->segment(4);
        $id_proses = $this->uri->segment(5);
        $nulls = sha1('null');
        $cek_token1 = $this->data_model->get_byid('tb_produksi', ['sha1(kode_produksi)'=>$kd]);
        $dt = $cek_token1->row_array();
        $pros_tgl = $dt['tgl_produksi'];
        if($kd_new!=$nulls){ 
            $cek_token2 = $this->data_model->get_byid('tb_produksi', ['sha1(kode_produksi)'=>$kd_new]); 
            $dt2 = $cek_token2->row_array();
            $kode_new = $dt2['kode_produksi'];
            $pros_tgl = $dt2['tgl_produksi'];
        } else {
            $kode_new = $dt['kode_produksi'];
        }
        
        if($cek_token1->num_rows() ==1){
            $old_pkg = $this->data_model->get_byid('new_tb_pkg_list', ['kode_produksi'=>$dt['kode_produksi']]);
            $data = array(
                'title' => 'Packing List ',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'sess_hak' => $this->session->userdata('hak'),
                'url1' => $this->uri->segment(3),
                'url2' => $this->uri->segment(4),
                'kdp1' => $dt['kode_produksi'],
                'kdp2' => $kode_new,
                'pros_tgl' => $pros_tgl,
                'dt_roll' => $this->data_model->get_byid('new_tb_pkg_list', ['kode_produksi'=>$dt['kode_produksi']]),
                'prosesnext' => $this->data_model->get_byid('tb_proses_produksi', ['id_proses'=>$id_proses])
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            $this->load->view('page/packing_list_inspect', $data);
            $this->load->view('part/main_js_dttable');
        } else {
            echo "Invalid Token";
        }
    } //end 
  
}
?>
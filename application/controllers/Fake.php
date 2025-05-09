<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fake extends CI_Controller
{
    function __construct()
    {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
    //   if($this->session->userdata('login_form') != "rindangjati_sess"){
	// 	  redirect(base_url("login"));
	//   }
    }
   
  function index(){
        //$this->load->view('users/login');
        echo "..";
  } //end
  function fakepkglistall(){
        $this->load->view('fakeAll_show');
  }
  function fakepkglist(){
        $this->load->view('fakeAll');
  }
  function updatepkg(){
      $nmpkg = $this->input->post('nmpkg');
      $ketpkg = $this->input->post('ketpkg');
      $pkgkons = $this->input->post('pkgkons');
      $ygbuat = $this->input->post('ygbuat');
      $id_pkg = $this->input->post('id_pkg');
      if($nmpkg!="" AND $ketpkg!="" AND $pkgkons!="" AND $ygbuat!="" AND $id_pkg!=""){
          $this->data_model->updatedata('id_fakepkg',$id_pkg, 'fake_pkglist', [
              'name_pkglist' => $nmpkg,
              'keterangan' => $ketpkg,
              'yg_buat' => $ygbuat,
              'konstruksi' => $pkgkons
          ]);
          echo json_encode(array("statusCode"=>200, "psn"=>"success"));
      } else {
          echo json_encode(array("statusCode"=>404, "psn"=>"failed"));
      }
  } //end

  function updatepkg_isi(){
      $nmpkg = $this->input->post('nmpkg');
      $ketpkg = $this->input->post('ketpkg');
      $pkgkons = $this->input->post('pkgkons');
      $ygbuat = $this->input->post('ygbuat');
      $id_pkg = $this->input->post('id_pkg');
      $kode_roll = $this->input->post('selection');
      if($nmpkg!="" AND $ketpkg!="" AND $pkgkons!="" AND $ygbuat!="" AND $id_pkg!=""){
          $this->data_model->updatedata('id_fakepkg',$id_pkg, 'fake_pkglist', [
              'name_pkglist' => $nmpkg,
              'keterangan' => $ketpkg,
              'yg_buat' => $ygbuat,
              'konstruksi' => $pkgkons
          ]);
          $cek_kode = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kode_roll]);
          if($cek_kode->num_rows() == 1){
              $kode_roll_konstruksi = $cek_kode->row("konstruksi");
              if($kode_roll_konstruksi == $pkgkons){
                $cek_kode_folding = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll]);
                if($cek_kode_folding->num_rows() == 0){
                    $cek_isi_pkg = $this->data_model->get_byid('fake_isi', ['kode_roll'=>$kode_roll]);
                    if($cek_isi_pkg->num_rows() == 0){
                        $this->data_model->saved('fake_isi', [
                          'id_fakepkg' => $id_pkg,
                          'kode_roll' => $kode_roll,
                          'ukuran' => $cek_kode->row('ukuran_ori')
                        ]);
                        echo json_encode(array("statusCode"=>200, "psn"=>"success"));
                    } else {
                        echo json_encode(array("statusCode"=>500, "psn"=>"Kode Sudah di Packinglist"));
                    }
                    
                } else {
                    echo json_encode(array("statusCode"=>500, "psn"=>"Kain Telah di proses folding"));
                }
              } else {
                  $txt = "Konstruksi Salah (".$kode_roll_konstruksi.")";
                  echo json_encode(array("statusCode"=>500, "psn"=>$txt));
              }  
          } else if($cek_kode->num_rows() > 1){
                echo json_encode(array("statusCode"=>500, "psn"=>"Terdapat Doble Kode"));
          } else {
                echo json_encode(array("statusCode"=>500, "psn"=>"Kode Tidak Ditemukan"));
          }
          
      } else {
          echo json_encode(array("statusCode"=>404, "psn"=>"failed"));
      }
  } //end

  function loadisipkg(){
      $id = $this->input->post('id_pkg');
      //$data = $this->data_model->get_byid('fake_isi', ['id_fakepkg'=>$id]);
      $data = $this->db->query("SELECT * FROM fake_isi WHERE id_fakepkg='$id' ORDER BY id_fakeisi DESC");
      $ukuran_total = $this->db->query("SELECT SUM(ukuran) AS ukr FROM fake_isi WHERE id_fakepkg='$id' ORDER BY id_fakeisi DESC")->row("ukr");
      if($data->num_rows() > 0){
          echo '<tr>
                    <td><strong>No</strong></td>
                    <td><strong>Kode Roll</strong></td>
                    <td><strong>Ukuran</strong></td>
                    <td><strong>#</strong></td>
                </tr>';
          $no=1;
          foreach($data->result() as $val){
              echo "<tr>";
              echo "<td>".$no."</td>";
              echo "<td>".$val->kode_roll."</td>";
              echo "<td>".$val->ukuran."</td>";
              ?><td><a href="#" onclick="delpkg('<?=$val->kode_roll;?>')" style="text-decoration:none;color:red;">Del</a></td><?php
              //echo "<td></td>";
              echo "</tr>";
              $no++;
          } 
          echo "<tr>";
          echo "<td colspan='2'><strong>Total</strong></td>";
          echo "<td><strong>".$ukuran_total."</strong></td>";
          echo "<td></td>";
          echo "</tr>";
      } else {
          echo '<tr>
                    <td><strong>No</strong></td>
                    <td><strong>Kode Roll</strong></td>
                    <td><strong>Ukuran</strong></td>
                    <td><strong>#</strong></td>
                </tr>
                <tr>
                    <td colspan="4">Paket Kosong...</td>
                </tr>';
      }
      
  } //end

  function del_isi(){
     $kode = $this->input->post('kode');
     $this->db->query("DELETE FROM fake_isi WHERE kode_roll='$kode'");
     echo "success";
  }

  function deletPkg_isi(){
      $id = $this->input->post('id_pkg');
      $cek_isi = $this->data_model->get_byid('fake_isi', ['id_fakepkg'=>$id]);
      if($cek_isi->num_rows() > 0){
          echo json_encode(array("statusCode"=>404, "psn"=>"Kosongi dulu isi paket"));
      } else {
          $this->db->query("DELETE FROM fake_pkglist WHERE id_fakepkg='$id'");
          echo json_encode(array("statusCode"=>200, "psn"=>"success"));
      }
  } //
  
  function fakedata(){
      $kons = $this->db->query("SELECT * FROM v_fakeisi GROUP BY konstruksi");
      echo "<table border='1'>";
      echo "<tr>";
      echo "<th>No.</th>";
      echo "<th>Konstruksi</th>";
      echo "<th>Total Roll</th>";
      echo "<th>Total Panjang</th>";
      echo "</tr>";
      $no=1;
      foreach($kons->result() as $val):
          $k = $val->konstruksi;
          echo "<tr>";
          echo "<td>".$no."</td>";
          echo "<td>".$k."</td>";
          $jmlroll = $this->db->query("SELECT COUNT(konstruksi) AS jml FROM v_fakeisi WHERE konstruksi='$k'")->row("jml");
          $ukr = $this->db->query("SELECT SUM(ukuran) AS jml FROM v_fakeisi WHERE konstruksi='$k'")->row("jml");
          echo "<td>".$jmlroll."</td>";
          echo "<td>".number_format($ukr,0,',','.')."</td>";
          echo "</tr>";
          $no++;
      endforeach;
      echo "</table>";
      echo "<hr>";
      
      echo "<table border='1'>";
      echo "<tr>";
      echo "<th>No.</th>";
      echo "<th>Konstruksi</th>";
      echo "<th>Packinglist</th>";
      echo "<th>Keterangan</th>";
      echo "<th>Yg Buat</th>";
      echo "<th>Jml Roll</th>";
      echo "<th>total Panjang</th>";
      echo "</tr>";
      $n=1;
      foreach($kons->result() as $b):
          $k2 = $b->konstruksi;
          $cekdata = $this->db->query("SELECT * FROM fake_pkglist WHERE konstruksi='$k2'");
          foreach($cekdata->result() as $bal):
              $id = $bal->id_fakepkg;
              $jml_roll = $this->db->query("SELECT COUNT(id_fakepkg) AS jm FROM fake_isi WHERE id_fakepkg='$id'")->row("jm");
              $pjg_roll = $this->db->query("SELECT SUM(ukuran) AS jm FROM fake_isi WHERE id_fakepkg='$id'")->row("jm");
              echo "<tr>";
              echo "<td>".$n."</td>";
              echo "<td>".$k2."</td>";
              ?><td><a href="<?=base_url('fake/fakepkglist/'.$id);?>"><?=$bal->name_pkglist;?></a></td><?php
              //echo "<td><a href=''>".$bal->name_pkglist."</a></td>";
              echo "<td>".$bal->keterangan."</td>";
              echo "<td>".$bal->yg_buat."</td>";
              echo "<td>".$jml_roll."</td>";
              echo "<td>".number_format($pjg_roll,0,',','.')."</td>";
              echo "</tr>";
              $n++;
          endforeach;
      endforeach;
  }

}
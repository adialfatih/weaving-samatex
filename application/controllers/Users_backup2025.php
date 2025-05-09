<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
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
        $this->load->view('users/login');
  } //end

  function pinlogin(){
        $this->load->view('users/pinlogin');
  }
  function updatepin(){
        $user = $this->input->post('user');
        $pin1 = $this->input->post('pin1');
        $pin2 = $this->input->post('pin2');
        if($pin1 == $pin2){
            //$user = "Daniel";
            if (strpos($user, 'Add-') !== false) {
                $ex = explode('-', $user);
                $namauser = $ex[1];
                $this->data_model->saved('a_operator',['username'=>$namauser,'pinuser'=>$pin1,'nama_opt'=>$namauser,'produksi'=>'kirimpst','dep'=>'RJS']);
                echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
            } else {
                $this->data_model->updatedata('username',$user,'a_operator',['pinuser'=>$pin1]);
                echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
            }
            
        } else {
            echo json_encode(array("statusCode"=>404, "psn"=>"oke"));
        }
    }

  function insgrey(){
        $this->load->view('users/insgrey');
  }
  function insfinish(){
      $this->load->view('users/insfinish');
  }
  function folgrey(){
      $this->load->view('users/folgrey');
  }
  function folfinish(){
      $this->load->view('users/folfinish');
  }
  function delstok(){
      $this->load->view('users/delstokfolding');
  }
  function delfolding(){
      $this->load->view('users/delstokfoldingchois');
  }
  function joinpieces(){
    $this->load->view('users/folfinishjoin');
  } 
  function penjualan(){
      $this->load->view('users/penjualan');
  }
  function greykefinish(){
      $this->load->view('users/greykefinish');
  }
  function createpenjualan(){
    $this->load->view('users/createpenjualan');
  }
  //ini  dibawah untuk menambahkan stok lama
  function createpenjualan2(){
    $this->load->view('users/createpenjualan2');
  }
  function createkirimpst(){
    $this->load->view('users/createkirimpst');
  }
  function createkirimpst2(){
    $this->load->view('users/createkirimpst2');
  }
  function createkirimpst3(){
    $this->load->view('users/createkirimpst3');
  }
    function kirimpst(){
      $this->load->view('users/kirimpst');
  }
  function terimafrompst(){
    $this->load->view('users/terimabarangfrompst');
  }
  function hapusKirimanPusatex(){
        $kd = $this->input->post('kd');
        $cek_isi = $this->data_model->get_byid('new_tb_isi', ['kode'=>$kd]);
        
        $akd = $this->data_model->get_byid('kiriman_pusatex',['kode_roll'=>$kd])->row_array();
        $ukr = $akd['ukuran'];
        $kons = $akd['konstruksi'];
        $tgl = $akd['tanggal'];
        $opt = $akd['operator'];
        $this->data_model->saved('new_roll_onpst', [
            'kode_roll' => $kd,
            'ukuran' => $ukr,
            'kons' => $kons
        ]);
        $stok = $this->data_model->get_byid('data_stok',['dep'=>'newSamatex','kode_konstruksi'=>$kons])->row_array();
        $ukrNow = floatval($stok['prod_ig']) - floatval($ukr);
        $this->data_model->updatedata('idstok',$stok['idstok'],'data_stok',['prod_ig'=>round($ukrNow,2)]);
        $this->data_model->updatedata('kode_roll',$kd,'data_ig',['loc_now'=>'Pusatex']);
        //$this->data_model->delete('data_ig','kode_roll',$kd);
        $this->data_model->delete('kiriman_pusatex','kode_roll',$kd);
        //-- 
        if($cek_isi->num_rows() == 0){
            $this->data_model->delete('data_ig','kode_roll',$kd);
        } else {
            $dep = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kd])->row("dep");
            $cek_isi2 = $this->db->query("SELECT * FROM new_tb_isi WHERE kode='$kd' ORDER BY id_isi DESC LIMIT 1");
            if(strpos($cek_isi2->row("kd"), "PK") !== false){
                $stok = $this->data_model->get_byid('data_stok',['dep'=>'stxToPusatex','kode_konstruksi'=>$kons])->row_array();
                $ukrNow = floatval($stok['prod_ig']) + floatval($ukr);
                $this->data_model->updatedata('idstok',$stok['idstok'],'data_stok',['prod_ig'=>round($ukrNow,2)]);
            } 
            if(strpos($cek_isi2->row("kd"), "RJ") !== false) {
                $stok = $this->data_model->get_byid('data_stok',['dep'=>'rjsToPusatex','kode_konstruksi'=>$kons])->row_array();
                $ukrNow = floatval($stok['prod_ig']) + floatval($ukr);
                $this->data_model->updatedata('idstok',$stok['idstok'],'data_stok',['prod_ig'=>round($ukrNow,2)]);
            }
            
        }
        echo "oke";
  }
  function loadKirimanPusatex(){
        $user = $this->input->post('username');
        $tgl = $this->input->post('tgl');
        //$query = $this->data_model->get_byid('kiriman_pusatex',['tanggal'=>$tgl, 'operator'=>$user]);
        $query = $this->db->query("SELECT * FROM kiriman_pusatex WHERE tanggal='$tgl' AND operator='$user' ORDER BY idterima DESC");
        if($query->num_rows() > 0){
            echo '<tr>
                    <td>Kode Roll</td>
                    <td>Ukuran</td>
                    <td>MC</td>
                    <td>Konstruksi</td>
                    <td>Del</td>
                </tr>';
            foreach($query->result() as $val):
            echo "<tr>";
            echo "<td>".$val->kode_roll."</td>";
            echo "<td>".$val->ukuran."</td>";
            echo "<td>".$val->mc."</td>";
            echo "<td>".$val->konstruksi."</td>";
            ?><td sytle="color:red;">
                <img src="<?=base_url('assets/del.png');?>" alt="Delete" style="width:20px;" onclick="delpkg('<?=$val->kode_roll;?>')">
            </td><?php
            echo "</tr>";
            endforeach;
        } else {
            echo '<tr>
                    <td>Kode Roll</td>
                    <td>Ukuran</td>
                    <td>MC</td>
                    <td>Konstruksi</td>
                </tr>';
            echo "<tr><td colspan='4'>Tidak ada data kiriman hari ini</td></tr>";
        }
  }

  function cekopt(){
      $proses = $this->input->post('proses');
      $namaUser = $this->input->post('namaUser');
      $pinUser = $this->input->post('pinUser');
      $kecilkan_user = strtolower($namaUser);
      $cek_user = $this->data_model->get_byid('a_operator', ['username'=>$kecilkan_user]);
      if($cek_user->num_rows() == 1){
          $this->data_model->updatedata('username',$kecilkan_user,'a_operator',['produksi'=>$proses]);
          //$proses_user = $cek_user->row("produksi");
          //if($proses_user == $proses){
            
          //} else {
            //echo json_encode(array("statusCode"=>200, "psn"=>"null"));
          //}
          $cek_user2 = $this->data_model->get_byid('a_operator', ['username'=>$kecilkan_user, 'pinuser'=>$pinUser]);
          if($cek_user2->num_rows() == 1){
                echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
          } else {
                echo json_encode(array("statusCode"=>404, "psn"=>"Pin Salah"));
          }
      } else {
          echo json_encode(array("statusCode"=>404, "psn"=>"Username Tidak Ditemukan"));
      }
      
  } //end
  
  function loaddata(){
      $proses = $this->input->post('proses');
      $username = $this->input->post('username');
      $username = strtolower($username);
      $tgl = date('Y-m-d');
      if($proses == "insgrey"){
          //$query = $this->data_model->get_byid('data_ig', ['tanggal'=>$tgl, 'operator'=>$username]);
          $query = $this->db->query("SELECT * FROM data_ig WHERE tanggal='$tgl' AND operator='$username' ORDER BY id_data DESC");
          echo "<tr>
                    <td><strong>No.</strong></td>
                    <td><strong>Kode Roll</strong></td>
                    <td><strong>Konstruksi</strong></td>
                    <td><strong>MC</strong></td>
                    <td><strong>ORI</strong></td>
                    <td><strong>BS</strong></td>
                    <td><strong>BP</strong></td>
                    <td><strong>#</strong></td>
                </tr>";
          if($query->num_rows() > 0){
              $jumlah_data = $query->num_rows();
              foreach($query->result() as $n => $val):
                  echo "<tr>";
                  $nomor = $n+1;
                  echo "<td>".$jumlah_data."</td>";
                  echo "<td>".$val->kode_roll."</td>";
                  echo "<td>".$val->konstruksi."</td>";
                  echo "<td>".$val->no_mesin."</td>";
                  echo "<td>".$val->ukuran_ori."</td>";
                  echo "<td>".$val->ukuran_bs."</td>";
                  echo "<td>".$val->ukuran_bp."</td>";
                  ?><td>
                        <img src="<?=base_url('assets/del.png');?>" alt="Delete" style="width:20px;" onclick="owek('<?=$val->id_data;?>')">
                    </td><?php
                  echo "</tr>";
                  $jumlah_data--;
              endforeach;
          } else {
              echo '<tr><td colspan="8">Data Inspect Grey Anda Masih Kosong</td></tr>';
          }
      } //end insgrey
      if($proses == "insfinish"){
        //$query = $this->data_model->get_byid('data_ig', ['tanggal'=>$tgl, 'operator'=>$username]);
        $query = $this->db->query("SELECT * FROM data_if WHERE tgl_potong='$tgl' AND operator='$username' ORDER BY id_infs DESC");
        echo "<tr>
                  <td><strong>No.</strong></td>
                  <td><strong>Kode Roll</strong></td>
                  <td><strong>Konstruksi</strong></td>
                  <td><strong>Ukr Sblm</strong></td>
                  <td><strong>Ukr (Yrd)</strong></td>
                  <td><strong>Del</strong></td>
              </tr>";
        if($query->num_rows() > 0){
            $jumlah_data = $query->num_rows();
            foreach($query->result() as $n => $val):
                echo "<tr>";
                $nomor = $n+1;
                echo "<td>".$jumlah_data."</td>";
                echo "<td>".$val->kode_roll."</td>";
                echo "<td>".$val->konstruksi."</td>";
                $nomc = $this->data_model->get_byid('data_ig',['kode_roll'=>$val->kode_roll]);
                if($nomc->num_rows() == 1){
                    $nomc2 = $nomc->row("no_mesin");
                } else {
                    $aslikode = substr($val->kode_roll, 0, -1);
                    $nomc2 = $this->data_model->get_byid('data_ig',['kode_roll'=>$aslikode])->row("no_mesin");
                }
                echo "<td>".$val->ukuran_sebelum."</td>";
                $ori_yard = $val->ukuran_ori / 0.9144;
                $showyard = round($ori_yard,2);
                echo "<td>".$showyard."</td>";
                ?><td><a href="#as" onclick="kuyhas('<?=$val->id_infs;?>')">
                        <img src="<?=base_url('assets/del.png');?>" alt="Delete" style="width:20px;" ></a>
                    </td><?php
                echo "</tr>";
                $jumlah_data--;
            endforeach;
        } else {
            echo '<tr><td colspan="5">Data Inspect Finish Anda Masih Kosong</td></tr>';
        }
    } //end insfinsih
    if($proses == "folgrey"){
      //$query = $this->data_model->get_byid('data_ig', ['tanggal'=>$tgl, 'operator'=>$username]);
      $query = $this->db->query("SELECT * FROM data_fol WHERE jns_fold='Grey' AND tgl='$tgl' AND operator='$username' ORDER BY id_fol DESC");
      echo "<tr>
                <td><strong>No.</strong></td>
                <td><strong>Kode Roll</strong></td>
                <td><strong>Konstruksi</strong></td>
                <td><strong>Ukr Sblm</strong></td>
                <td><strong>Ukuran (Mtr)</strong></td>
                <td><strong>Del</strong></td>
            </tr>";
      if($query->num_rows() > 0){
          $jumlah_data = $query->num_rows();
          foreach($query->result() as $n => $val):
              echo "<tr>";
              $nomor = $n+1;
              echo "<td>".$jumlah_data."</td>";
              echo "<td>".$val->kode_roll."</td>";
              echo "<td>".$val->konstruksi."</td>";
              $nomc = $this->data_model->get_byid('data_ig',['kode_roll'=>$val->kode_roll]);
              if($nomc->num_rows() == 1){
                  $nomc2 = $nomc->row("ukuran_ori");
              } else {
                  $aslikode = substr($val->kode_roll, 0, -1);
                  $nomc2 = $this->data_model->get_byid('data_ig',['kode_roll'=>$aslikode])->row("ukuran_ori");
              }
              echo "<td>".$nomc2."</td>";
              //$ori_yard = $val->ukuran_ori * 0.9144;
              //$showyard = round($ori_yard,2);
              echo "<td>".$val->ukuran."</td>";
              ?><td><a href="#del" onclick="kuyhas('<?=$val->id_fol;?>')">
                        <img src="<?=base_url('assets/del.png');?>" alt="Delete" style="width:20px;" ></a>
                    </td><?php
              echo "</tr>";
              $jumlah_data--;
          endforeach;
      } else {
          echo '<tr><td colspan="5">Data Folding Grey Anda Masih Kosong</td></tr>';
      }
    } //end folgrey
    if($proses == "folfinish"){
      //$query = $this->data_model->get_byid('data_ig', ['tanggal'=>$tgl, 'operator'=>$username]);
      $query = $this->db->query("SELECT * FROM data_fol WHERE jns_fold='Finish' AND tgl='$tgl' AND operator='$username' ORDER BY id_fol DESC");
      echo "<tr>
                <td><strong>No.</strong></td>
                <td><strong>Kode Roll</strong></td>
                <td><strong>Konstruksi</strong></td>
                <td><strong>Ukr Sblm</strong></td>
                <td><strong>Ukuran (Yrd)</strong></td>
                <td><strong>Del</strong></td>
            </tr>";
      if($query->num_rows() > 0){
          $jumlah_data = $query->num_rows();
          foreach($query->result() as $n => $val):
              echo "<tr>";
              $nomor = $n+1;
              echo "<td>".$jumlah_data."</td>";
              echo "<td>".$val->kode_roll."</td>";
              echo "<td>".$val->konstruksi."</td>";
              $nomc = $this->data_model->get_byid('data_ig',['kode_roll'=>$val->kode_roll]);
              if($nomc->num_rows() == 1){
                  $nomc2 = $nomc->row("ukuran_ori");
              } else {
                  $aslikode = substr($val->kode_roll, 0, -1);
                  $nomc2 = $this->data_model->get_byid('data_ig',['kode_roll'=>$aslikode])->row("ukuran_ori");
              }
              echo "<td>".$nomc2."</td>";
              //$ori_yard = $val->ukuran_ori * 0.9144;
              //$showyard = round($ori_yard,2);
              echo "<td>".$val->ukuran."</td>";
              ?><td><a href="#del" onclick="kuyhas('<?=$val->id_fol;?>')">
                        <img src="<?=base_url('assets/del.png');?>" alt="Delete" style="width:20px;" ></a>
                    </td><?php
              echo "</tr>";
              $jumlah_data--;
          endforeach;
      } else {
          echo '<tr><td colspan="5">Data Folding Finish Anda Masih Kosong</td></tr>';
      }
    } //end folfinish
      
  } //end

  function prosesInsGrey_bc(){
        $jamSaatIni = date('H');
        if ($jamSaatIni >= 14) {
            $shift = "2";
        } else {
            $shift = "1";
        }
        $tableKode = $this->data_model->get_byid('data_ig_code', ['idcode'=>'1'])->row_array();
        $numSkr = $tableKode['numskr'];
        $newNumber = intval($numSkr) + 1;
        $setKode = $tableKode['alpabet']."".$newNumber;
        // $searchKode = "SF";
        // $pemisah = "F";
        // $cekkode = $this->db->query("SELECT id_data,kode_roll FROM data_ig WHERE kode_roll LIKE '%$searchKode%' ORDER BY id_data DESC LIMIT 1");
        // if($cekkode->num_rows() == 0){
        //     $setKode = "".$searchKode."1";
        // } else {
        //     $ex = explode($pemisah, $cekkode->row('kode_roll'));
        //     $number = intval($ex[1]) + 1;
        //     $setKode = "".$searchKode."".$number."";
        // }
        
        $kons = $this->input->post('kons');
        $mc = $this->input->post('mc');
        $beam = $this->input->post('beam');
        $oka = $this->input->post('oka');
        $ori = $this->input->post('ori');
        $bs = $this->input->post('bs');
        $bp = $this->input->post('bp');
        $tgl = $this->input->post('tgl');
        $operator = $this->input->post('username');
        $opt = strtolower($operator);
        $cek_opt = $this->data_model->get_byid('a_operator',['username'=>$opt]);
        if($cek_opt->num_rows() == 1){
            $cek_kons = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kons]);
            if($cek_kons->num_rows() == 1){
                $cekKoder = $this->data_model->get_byid('data_ig',['kode_roll'=>$setKode]);
                if($cekKoder->num_rows() == 0){
                    $dtlist = [
                        'kode_roll' => $setKode,
                        'konstruksi' => $kons,
                        'no_mesin' => $mc,
                        'no_beam' => $beam,
                        'oka' => $oka,
                        'ukuran_ori' => $ori,
                        'ukuran_bs' => $bs,
                        'ukuran_bp' => $bp,
                        'tanggal' => $tgl,
                        'operator' => $opt,
                        'bp_can_join' => $ori<50 ? 'true':'false',
                        'dep' => 'Samatex',
                        'loc_now' => 'Samatex',
                        'yg_input' => 0,
                        'kode_upload' => 'tes'
                    ];
                    $this->data_model->saved('data_ig', $dtlist);
                    $this->data_model->updatedata('idcode',1,'data_ig_code',['numskr'=>$newNumber]);
                } else {
                    $newNumber2 = $newNumber + 1;
                    $setKode = $tableKode['alpabet']."".$newNumber2;
                    //$trueKode = "S1".$pemisah."".$ex[1];
                    $dtlist = [
                        'kode_roll' => $setKode,
                        'konstruksi' => $kons,
                        'no_mesin' => $mc,
                        'no_beam' => $beam,
                        'oka' => $oka,
                        'ukuran_ori' => $ori,
                        'ukuran_bs' => $bs,
                        'ukuran_bp' => $bp,
                        'tanggal' => $tgl,
                        'operator' => $opt,
                        'bp_can_join' => $ori<50 ? 'true':'false',
                        'dep' => 'Samatex',
                        'loc_now' => 'Samatex',
                        'yg_input' => 0,
                        'kode_upload' => 'tes'
                    ];
                    $this->data_model->saved('data_ig', $dtlist);
                    $this->data_model->updatedata('idcode',1,'data_ig_code',['numskr'=>$newNumber2]);
                }
                //cek produksi per sm harian
                $cek1 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kons,'tgl'=>$tgl,'dep'=>'Samatex']);
                if($cek1->num_rows() == 1){
                        $id_produksi = $cek1->row("id_produksi");
                        $new_ig = floatval($cek1->row("prod_ig")) + floatval($ori);
                        $new_bs = floatval($cek1->row("prod_bs1")) + floatval($bs);
                        $new_bp = floatval($cek1->row("prod_bp1")) + floatval($bp);
                        $dtlist1 = [
                            'prod_ig' => round($new_ig,2),
                            'prod_bs1' => round($new_bs,2),
                            'prod_bp1' => round($new_bp,2)
                        ];
                        $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',$dtlist1);
                } else {
                    $dtlist1 = [
                        'kode_konstruksi' => $kons,
                        'tgl' => $tgl,
                        'dep' => 'Samatex',
                        'prod_ig' => $ori,
                        'prod_fg' => 0,
                        'prod_if' => 0,
                        'prod_ff' => 0,
                        'prod_bs1' => $bs,
                        'prod_bp1' => $bp,
                        'prod_bs2' => 0,
                        'prod_bp2' => 0,
                        'crt' => 0
                    ];
                    $this->data_model->saved('data_produksi', $dtlist1);
                }
                //end cek 1
                //cek produksi harian total
                $cek2 = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl,'dep'=>'Samatex']);
                if($cek2->num_rows() == 1){
                        $id_prod_hr = $cek2->row("id_prod_hr");
                        $new_ig = floatval($cek2->row("prod_ig")) + floatval($ori);
                        $new_bs = floatval($cek2->row("prod_bs1")) + floatval($bs);
                        $new_bp = floatval($cek2->row("prod_bp1")) + floatval($bp);
                        $dtlist1 = [
                            'prod_ig' => round($new_ig,2),
                            'prod_bs1' => round($new_bs,2),
                            'prod_bp1' => round($new_bp,2)
                        ];
                        $this->data_model->updatedata('id_prod_hr',$id_prod_hr,'data_produksi_harian',$dtlist1);
                } else {
                    $dtlist1 = [
                        'tgl' => $tgl,
                        'dep' => 'Samatex',
                        'prod_ig' => $ori,
                        'prod_fg' => 0,
                        'prod_if' => 0,
                        'prod_ff' => 0,
                        'prod_bs1' => $bs,
                        'prod_bp1' => $bp,
                        'prod_bs2' => 0,
                        'prod_bp2' => 0,
                        'crt' => 0
                    ];
                    $this->data_model->saved('data_produksi_harian', $dtlist1);
                }
                //end cek 2
                //cek produksi opt
                $cek3 = $this->data_model->get_byid('data_produksi_opt', ['username_opt'=>$opt,'konstruksi'=>$kons,'tgl'=>$tgl,'proses'=>'insgrey']);
                if($cek3->num_rows() == 1){
                    $id_propt = $cek3->row("id_propt");
                    $new_ori = floatval($cek3->row("ukuran")) + floatval($ori);
                    $new_bs = floatval($cek3->row("bs")) + floatval($bs);
                    $new_bp = floatval($cek3->row("bp")) + floatval($bp);
                    $dtlist2 = [
                        'ukuran' => round($new_ori,2),
                        'bs' => round($new_bs,2),
                        'bp' => round($new_bp,2)
                    ];
                    $this->data_model->updatedata('id_propt',$id_propt,'data_produksi_opt',$dtlist2);
                } else {
                    $dtlist2 = [
                        'username_opt' => $opt,
                        'konstruksi' => $kons,
                        'tgl' => $tgl,
                        'proses' => 'insgrey',
                        'ukuran' => $ori, 
                        'bs' => $bs,
                        'bp' => $bp,
                        'crt' => 0,
                        'shift' => $shift
                    ];
                    $this->data_model->saved('data_produksi_opt', $dtlist2);
                }
                //end cek 3
                //cek data stok ke 4
                $cekStok = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex','kode_konstruksi'=>$kons]);
                if($cekStok->num_rows() == 0){
                    $listStok = [
                        'dep' => 'newSamatex',
                        'kode_konstruksi' => $kons,
                        'prod_ig' => $ori,
                        'prod_fg' => 0,
                        'prod_if' => 0,
                        'prod_ff' => 0,
                        'prod_bs1' => $bs,
                        'prod_bp1' => $bp,
                        'prod_bs2' => 0,
                        'prod_bp2' => 0,
                        'crt' => 0
                    ];
                    $this->data_model->saved('data_stok', $listStok);
                } else {
                    $idstok = $cekStok->row("idstok");
                    $newig = floatval($cekStok->row("prod_ig")) + floatval($ori);
                    $newbs = floatval($cekStok->row("prod_bs1")) + floatval($bs);
                    $newbp = floatval($cekStok->row("prod_bp1")) + floatval($bp);
                    $listStok = [
                        'prod_ig' => round($newig,2),
                        'prod_bs1' => round($newbs,2),
                        'prod_bp1' => round($newbp,2)
                    ];
                    $this->data_model->updatedata('idstok',$idstok,'data_stok',$listStok);
                }
                //end cek 4
                echo json_encode(array("statusCode"=>200, "psn"=>$setKode));
            } else {
                echo json_encode(array("statusCode"=>404, "psn"=>"Konstruksi tidak ditemukan"));
            }
        } else {
            echo json_encode(array("statusCode"=>404, "psn"=>"Username operator tidak ditemukan"));
        }
        
       
  } //end proses insgrey saved 1
  
  function prosesInsGrey(){
        $jamSaatIni = date('H');
        if ($jamSaatIni >= 14) {
            $shift = "2";
        } else {
            $shift = "1";
        }
        $rjsoke = $this->input->post('rjsoke');
        if($rjsoke=="n"){
            $tableKode = $this->data_model->get_byid('data_ig_code', ['idcode'=>'1'])->row_array();
            $numSkr = $tableKode['numskr'];
            $newNumber = intval($numSkr) + 1;
            $setKode = $tableKode['alpabet']."".$newNumber;
            $id_idkode = 1;
        } else {
            $tableKode = $this->data_model->get_byid('data_ig_code', ['idcode'=>'5'])->row_array();
            $numSkr = $tableKode['numskr'];
            $newNumber = intval($numSkr) + 1;
            $setKode = $tableKode['alpabet']."".$newNumber;
            $id_idkode = 5;
        }
        // $searchKode = "SF";
        // $pemisah = "F";
        // $cekkode = $this->db->query("SELECT id_data,kode_roll FROM data_ig WHERE kode_roll LIKE '%$searchKode%' ORDER BY id_data DESC LIMIT 1");
        // if($cekkode->num_rows() == 0){
        //     $setKode = "".$searchKode."1";
        // } else {
        //     $ex = explode($pemisah, $cekkode->row('kode_roll'));
        //     $number = intval($ex[1]) + 1;
        //     $setKode = "".$searchKode."".$number."";
        // }
        
        $kons = $this->input->post('kons');
        $mc = $this->input->post('mc');
        $beam = $this->input->post('beam');
        $oka = $this->input->post('oka');
        $ori = $this->input->post('ori');
        $bs = $this->input->post('bs');
        $bp = $this->input->post('bp');
        $tgl = $this->input->post('tgl');
        $operator = $this->input->post('username');
        $masket = $this->input->post('masket');
        $opt = strtolower($operator);
        $cek_opt = $this->data_model->get_byid('a_operator',['username'=>$opt]);
        if($cek_opt->num_rows() == 1){
            $cek_kons = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kons]);
            if($cek_kons->num_rows() == 1){
                $cekKoder = $this->data_model->get_byid('data_ig',['kode_roll'=>$setKode]);
                if($cekKoder->num_rows() == 0){
                    $dtlist = [
                        'kode_roll' => $setKode,
                        'konstruksi' => $kons,
                        'no_mesin' => $mc,
                        'no_beam' => $beam,
                        'oka' => $oka,
                        'ukuran_ori' => $ori,
                        'ukuran_bs' => $bs,
                        'ukuran_bp' => $bp,
                        'tanggal' => $tgl,
                        'operator' => $opt,
                        'bp_can_join' => $ori<50 ? 'true':'false',
                        'dep' => 'Samatex',
                        'loc_now' => 'Samatex',
                        'yg_input' => 0,
                        'kode_upload' => date('Y-m-d H:i:s'),
                        'shift_op' => $shift,
                        'input_from' => 'app',
                        'kode_potongan' => '0'
                    ];
                    $this->data_model->saved('data_ig', $dtlist);
                    $this->data_model->updatedata('idcode',$id_idkode,'data_ig_code',['numskr'=>$newNumber]);
                } else {
                    $newNumber2 = $newNumber + 1;
                    $setKode = $tableKode['alpabet']."".$newNumber2;
                    //$trueKode = "S1".$pemisah."".$ex[1];
                    $dtlist = [
                        'kode_roll' => $setKode,
                        'konstruksi' => $kons,
                        'no_mesin' => $mc,
                        'no_beam' => $beam,
                        'oka' => $oka,
                        'ukuran_ori' => $ori,
                        'ukuran_bs' => $bs,
                        'ukuran_bp' => $bp,
                        'ukuran_bp' => 0,
                        'tanggal' => $tgl,
                        'operator' => $opt,
                        'bp_can_join' => $ori<50 ? 'true':'false',
                        'dep' => 'Samatex',
                        'loc_now' => 'Samatex',
                        'yg_input' => 0,
                        'kode_upload' => date('Y-m-d H:i:s'),
                        'shift_op' => $shift,
                        'input_from' => 'app',
                        'kode_potongan' => '0'
                    ];
                    $this->data_model->saved('data_ig', $dtlist);
                    $this->data_model->updatedata('idcode',$id_idkode,'data_ig_code',['numskr'=>$newNumber2]);
                }
                if(intval($ori) < 50){
                    $this->data_model->saved('data_ig_bp', [
                        'kode_roll' => $setKode,
                        'ukuran_bp' => $ori,
                        'tgl' => $tgl,
                        'shift_op' => $shift,
                        'keterangan' => $masket,
                        'operator' => $opt,
                        'dep' => 'Samatex',
                        'lokasi_now' => 'Samatex'
                    ]);
                }
                if(intval($bs) > 0){
                    $this->data_model->saved('data_ig_bs', [
                        'kode_roll' => $setKode,
                        'ukuran_bs' => $bs,
                        'tgl' => $tgl,
                        'shift_op' => $shift,
                        'keterangan' => $masket,
                        'operator' => $opt,
                        'dep' => 'Samatex',
                        'lokasi_now' => 'Samatex'
                    ]);
                }
                //cek produksi per sm harian
                $cek1 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kons,'tgl'=>$tgl,'dep'=>'Samatex']);
                if($cek1->num_rows() == 1){
                        $id_produksi = $cek1->row("id_produksi");
                        $new_ig = floatval($cek1->row("prod_ig")) + floatval($ori);
                        $new_bs = floatval($cek1->row("prod_bs1")) + floatval($bs);
                        $new_bp = floatval($cek1->row("prod_bp1")) + floatval($bp);
                        $dtlist1 = [
                            'prod_ig' => round($new_ig,2),
                            'prod_bs1' => round($new_bs,2),
                            'prod_bp1' => round($new_bp,2)
                        ];
                        $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',$dtlist1);
                } else {
                    $dtlist1 = [
                        'kode_konstruksi' => $kons,
                        'tgl' => $tgl,
                        'dep' => 'Samatex',
                        'prod_ig' => $ori,
                        'prod_fg' => 0,
                        'prod_if' => 0,
                        'prod_ff' => 0,
                        'prod_bs1' => $bs,
                        'prod_bp1' => $bp,
                        'prod_bs2' => 0,
                        'prod_bp2' => 0,
                        'crt' => 0
                    ];
                    $this->data_model->saved('data_produksi', $dtlist1);
                }
                //end cek 1
                //cek produksi harian total
                $cek2 = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl,'dep'=>'Samatex']);
                if($cek2->num_rows() == 1){
                        $id_prod_hr = $cek2->row("id_prod_hr");
                        $new_ig = floatval($cek2->row("prod_ig")) + floatval($ori);
                        $new_bs = floatval($cek2->row("prod_bs1")) + floatval($bs);
                        $new_bp = floatval($cek2->row("prod_bp1")) + floatval($bp);
                        $dtlist1 = [
                            'prod_ig' => round($new_ig,2),
                            'prod_bs1' => round($new_bs,2),
                            'prod_bp1' => round($new_bp,2)
                        ];
                        $this->data_model->updatedata('id_prod_hr',$id_prod_hr,'data_produksi_harian',$dtlist1);
                } else {
                    $dtlist1 = [
                        'tgl' => $tgl,
                        'dep' => 'Samatex',
                        'prod_ig' => $ori,
                        'prod_fg' => 0,
                        'prod_if' => 0,
                        'prod_ff' => 0,
                        'prod_bs1' => $bs,
                        'prod_bp1' => $bp,
                        'prod_bs2' => 0,
                        'prod_bp2' => 0,
                        'crt' => 0
                    ];
                    $this->data_model->saved('data_produksi_harian', $dtlist1);
                }
                //end cek 2
                //cek produksi opt
                $cek3 = $this->data_model->get_byid('data_produksi_opt', ['username_opt'=>$opt,'konstruksi'=>$kons,'tgl'=>$tgl,'proses'=>'insgrey']);
                if($cek3->num_rows() == 1){
                    $id_propt = $cek3->row("id_propt");
                    $new_ori = floatval($cek3->row("ukuran")) + floatval($ori);
                    $new_bs = floatval($cek3->row("bs")) + floatval($bs);
                    $new_bp = floatval($cek3->row("bp")) + floatval($bp);
                    $dtlist2 = [
                        'ukuran' => round($new_ori,2),
                        'bs' => round($new_bs,2),
                        'bp' => round($new_bp,2)
                    ];
                    $this->data_model->updatedata('id_propt',$id_propt,'data_produksi_opt',$dtlist2);
                } else {
                    $dtlist2 = [
                        'username_opt' => $opt,
                        'konstruksi' => $kons,
                        'tgl' => $tgl,
                        'proses' => 'insgrey',
                        'ukuran' => $ori, 
                        'bs' => $bs,
                        'bp' => $bp,
                        'crt' => 0,
                        'shift' => $shift
                    ];
                    $this->data_model->saved('data_produksi_opt', $dtlist2);
                }
                //end cek 3
                //cek data stok ke 4
                $cekStok = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex','kode_konstruksi'=>$kons]);
                if($cekStok->num_rows() == 0){
                    $listStok = [
                        'dep' => 'newSamatex',
                        'kode_konstruksi' => $kons,
                        'prod_ig' => $ori,
                        'prod_fg' => 0,
                        'prod_if' => 0,
                        'prod_ff' => 0,
                        'prod_bs1' => $bs,
                        'prod_bp1' => $bp,
                        'prod_bs2' => 0,
                        'prod_bp2' => 0,
                        'crt' => 0
                    ];
                    $this->data_model->saved('data_stok', $listStok);
                } else {
                    $idstok = $cekStok->row("idstok");
                    $newig = floatval($cekStok->row("prod_ig")) + floatval($ori);
                    $newbs = floatval($cekStok->row("prod_bs1")) + floatval($bs);
                    $newbp = floatval($cekStok->row("prod_bp1")) + floatval($bp);
                    $listStok = [
                        'prod_ig' => round($newig,2),
                        'prod_bs1' => round($newbs,2),
                        'prod_bp1' => round($newbp,2)
                    ];
                    $this->data_model->updatedata('idstok',$idstok,'data_stok',$listStok);
                }
                //end cek 4
                echo json_encode(array("statusCode"=>200, "psn"=>$setKode));
            } else {
                echo json_encode(array("statusCode"=>404, "psn"=>"Konstruksi tidak ditemukan"));
            }
        } else {
            echo json_encode(array("statusCode"=>404, "psn"=>"Username operator tidak ditemukan"));
        }
        
       
  } //end proses insgrey saved 2

  function prosesInsFinish(){
        $jamSaatIni = date('H');
        if ($jamSaatIni >= 14) {
            $shift = "2";
        } else {
            $shift = "1";
        }
        $koderoll = $this->input->post('koderoll');
        $ukuran = $this->input->post('ori');
        $bs = $this->input->post('bs');
        $bp = $this->input->post('bp');
        $crt = $this->input->post('crt');
        $tgl = $this->input->post('tgl');
        $masket = $this->input->post('masket');
        $operator = $this->input->post('username');
        $ukrSebelum = $this->input->post('ukrSebelum');
        $kecilkanOperator = strtolower($operator);
        $cekusername = $this->data_model->get_byid('a_operator', ['username'=>$kecilkanOperator, 'produksi'=>'insfinish', 'dep'=>'Samatex']);
        if($cekusername->num_rows() == 1){
        $cekKodeRoll = $this->data_model->get_byid('data_ig', ['kode_roll'=>$koderoll]);
        if($cekKodeRoll->num_rows() == 1){
            $kons = $cekKodeRoll->row("konstruksi");
            $ukuran_sebelum = $cekKodeRoll->row("ukuran_ori");
            $cekKodeRollFinish = $this->data_model->get_byid('data_if', ['kode_roll'=>$koderoll]);
            if($cekKodeRollFinish->num_rows() == 0){
                $dataBener=0; $totalOri=0; 
                $jadikode = array();
                $alphabet = ['0'=>'','1'=>'A','2'=>'B','3'=>'C','4'=>'D','5'=>'E','6'=>'F','7'=>'G','8'=>'H','9'=>'I','10'=>'J'];
                for ($i=0; $i <count($ukuran) ; $i++) { 
                    if($ukuran[$i]!="" AND $ukuran[$i]>0){
                        $dataBener+=1;
                        $totalOri+=floatval($ukuran[$i]);
                        $kodeRollInput = $koderoll."".$alphabet[$i];
                        $jadikode[]= $kodeRollInput;
                        $dtlist = [
                            'kode_roll' => $kodeRollInput,
                            'tgl_potong' => $tgl,
                            'ukuran_sebelum' => $ukrSebelum,
                            'ukuran_ori' => $ukuran[$i],
                            'ukuran_bs' => $i==0 ? $bs:0,
                            'ukuran_crt' => $i==0 ? $crt:0,
                            'ukuran_bp' => $i==0 ? $bp:0,
                            'operator' => $kecilkanOperator,
                            'ket' => 'new',
                            'asal' => '0',
                            'bp_canjoin' => $ukuran[$i]<50 ? 'true':'false',
                            'konstruksi' => $kons
                        ];
                        $this->data_model->saved('data_if',$dtlist);
                            if(intval($ukuran[$i]) < 50){
                                $this->data_model->saved('data_ig_bc', [
                                    'kode_roll' => $kodeRollInput,
                                    'ukuran_bc' => $ukuran[$i],
                                    'tgl' => $tgl,
                                    'shift_op' => $shift,
                                    'keterangan' => $masket,
                                    'operator' => $kecilkanOperator,
                                    'dep' => 'Samatex',
                                    'lokasi_now' => 'Samatex'
                                ]);
                            }
                        
                    } 
                } //end for 
                if(intval($bs) > 0){
                    $this->data_model->saved('data_ig_bs', [
                        'kode_roll' => $koderoll,
                        'ukuran_bs' => $bs,
                        'tgl' => $tgl,
                        'shift_op' => $shift,
                        'keterangan' => $masket,
                        'operator' => $kecilkanOperator,
                        'dep' => 'Samatex',
                        'lokasi_now' => 'Samatex'
                    ]);
                }
                //cek produksi per sm harian
                $cek1 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kons,'tgl'=>$tgl,'dep'=>'Samatex']);
                if($cek1->num_rows() == 1){
                        $id_produksi = $cek1->row("id_produksi");
                        $new_if = floatval($cek1->row("prod_if")) + floatval($totalOri);
                        $new_bs = floatval($cek1->row("prod_bs2")) + floatval($bs);
                        $new_bp = floatval($cek1->row("prod_bp2")) + floatval($bp);
                        $new_crt = floatval($cek1->row("crt")) + floatval($crt);
                        $dtlist1 = [
                            'prod_if' => round($new_if,2),
                            'prod_bs2' => round($new_bs,2),
                            'prod_bp2' => round($new_bp,2),
                            'crt' => round($new_crt,2)
                        ];
                        $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',$dtlist1);
                } else {
                    $dtlist1 = [
                        'kode_konstruksi' => $kons,
                        'tgl' => $tgl,
                        'dep' => 'Samatex',
                        'prod_ig' => 0,
                        'prod_fg' => 0,
                        'prod_if' => round($totalOri,2),
                        'prod_ff' => 0,
                        'prod_bs1' => 0,
                        'prod_bp1' => 0,
                        'prod_bs2' => $bs,
                        'prod_bp2' => $bp,
                        'crt' => $crt
                    ];
                    $this->data_model->saved('data_produksi', $dtlist1);
                }
                //end cek 1
                //cek produksi harian total
                $cek2 = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl,'dep'=>'Samatex']);
                if($cek2->num_rows() == 1){
                        $id_prod_hr = $cek2->row("id_prod_hr");
                        $new_if = floatval($cek2->row("prod_if")) + floatval($totalOri);
                        $new_bs = floatval($cek2->row("prod_bs2")) + floatval($bs);
                        $new_bp = floatval($cek2->row("prod_bp2")) + floatval($bp);
                        $new_crt = floatval($cek2->row("crt")) + floatval($crt);
                        $dtlist1 = [
                            'prod_if' => round($new_if,2),
                            'prod_bs2' => round($new_bs,2),
                            'prod_bp2' => round($new_bp,2),
                            'crt' => round($new_crt,2)
                        ];
                        $this->data_model->updatedata('id_prod_hr',$id_prod_hr,'data_produksi_harian',$dtlist1);
                } else {
                    $dtlist1 = [
                        'tgl' => $tgl,
                        'dep' => 'Samatex',
                        'prod_ig' => 0,
                        'prod_fg' => 0,
                        'prod_if' => round($totalOri,2),
                        'prod_ff' => 0,
                        'prod_bs1' => 0,
                        'prod_bp1' => 0,
                        'prod_bs2' => $bs,
                        'prod_bp2' => $bp,
                        'crt' => $crt
                    ];
                    $this->data_model->saved('data_produksi_harian', $dtlist1);
                }
                //end cek 2
                //cek produksi opt
                $cek3 = $this->data_model->get_byid('data_produksi_opt', ['username_opt'=>$kecilkanOperator,'konstruksi'=>$kons,'tgl'=>$tgl,'proses'=>'insfinish']);
                if($cek3->num_rows() == 1){
                    $id_propt = $cek3->row("id_propt");
                    $new_ori = floatval($cek3->row("ukuran")) + floatval($totalOri);
                    $new_bs = floatval($cek3->row("bs")) + floatval($bs);
                    $new_bp = floatval($cek3->row("bp")) + floatval($bp);
                    $new_crt = floatval($cek3->row("crt")) + floatval($crt);
                    $dtlist2 = [
                        'ukuran' => round($new_ori,2),
                        'bs' => round($new_bs,2),
                        'bp' => round($new_bp,2),
                        'crt' => round($new_crt,2)
                    ];
                    $this->data_model->updatedata('id_propt',$id_propt,'data_produksi_opt',$dtlist2);
                } else {
                    $jamSaatIni = date('H');
                    if ($jamSaatIni >= 14) {
                        $shift = "2";
                    } else {
                        $shift = "1";
                    }
                    $dtlist2 = [
                        'username_opt' => $kecilkanOperator,
                        'konstruksi' => $kons,
                        'tgl' => $tgl,
                        'proses' => 'insfinish',
                        'ukuran' => round($totalOri,2), 
                        'bs' => $bs,
                        'bp' => $bp,
                        'crt' => $crt,
                        'shift' => $shift
                    ];
                    $this->data_model->saved('data_produksi_opt', $dtlist2);
                }
                //end cek 3
                //cek data stok ke 4
                $cekStok = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex','kode_konstruksi'=>$kons]);
                if($cekStok->num_rows() == 0){
                    $listStok = [
                        'dep' => 'newSamatex',
                        'kode_konstruksi' => $kons,
                        'prod_ig' => 0,
                        'prod_fg' => 0,
                        'prod_if' => round($totalOri,2),
                        'prod_ff' => 0,
                        'prod_bs1' => 0,
                        'prod_bp1' => 0,
                        'prod_bs2' => $bs,
                        'prod_bp2' => $bp,
                        'crt' => $crt
                    ];
                    $this->data_model->saved('data_stok', $listStok);
                } else {
                    $idstok = $cekStok->row("idstok");
                    $newig = floatval($cekStok->row("prod_ig")) - floatval($ukuran_sebelum);
                    $newif = floatval($cekStok->row("prod_if")) + floatval($totalOri);
                    $newbs = floatval($cekStok->row("prod_bs2")) + floatval($bs);
                    $newbp = floatval($cekStok->row("prod_bp2")) + floatval($bp);
                    $newcrt = floatval($cekStok->row("crt")) + floatval($crt);
                    $listStok = [
                        'prod_ig' => round($newig,2),
                        'prod_if' => round($newif,2),
                        'prod_bs2' => round($newbs,2),
                        'prod_bp2' => round($newbp,2),
                        'crt' => round($newcrt,2)
                    ];
                    $this->data_model->updatedata('idstok',$idstok,'data_stok',$listStok);
                }
                //end cek 4
                $imJadi = implode('-', $jadikode);
                $text = "Kode ".$imJadi."";
                $this->data_model->updatedata('kode_roll',$koderoll,'data_ig',['loc_now'=>'iff']);
                echo json_encode(array("statusCode"=>200, "psn"=>$text));
            } else {
                $text = "Kode ".$koderoll." sudah di proses Finish.";
                echo json_encode(array("statusCode"=>404, "psn"=>$text));
            }
        } else {
            $text = "Kode ".$koderoll." tidak ditemukan.";
            echo json_encode(array("statusCode"=>404, "psn"=>$text));
        }
        //$text = implode('-', $ukuran);
        } else {
            $text = "Username tidak ditemukan!!";
            echo json_encode(array("statusCode"=>404, "psn"=>$text));
        }
  } //end proses insfinish saved

  function laporaninsgrey(){
        $this->load->view('users/report_insgrey');
  } //end report insgrey-

  function laporaninsfinish(){
        $this->load->view('users/report_insfinish');
  } //end report insfinish-

  function laporanfolgrey(){
        $this->load->view('users/report_folgrey');
  } //end report laporanfolgrey-

  function laporanfolfinish(){
        $this->load->view('users/report_folfinish');
  } //end report laporanfolfinish-

  function cariInsgrey(){
        $kode = $this->input->post('kode');
        $cekkode = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kode]);
        if($cekkode->num_rows() == 1){
            $posisi = $cekkode->row("loc_now");
            if($posisi == "Samatex" OR $posisi == "stok" OR $posisi == "iff"){
            $cek_finish = $this->data_model->get_byid('data_if', ['kode_roll'=>$kode]);
            if($cek_finish->num_rows() == 0){
                $kons = $cekkode->row("konstruksi");
                $mc = $cekkode->row("no_mesin");
                $ori = $cekkode->row("ukuran_ori");
                $text = "Data Inspect Grey<br>- Konstruksi &nbsp;<strong>".$kons."</strong><br>- Nomor Mesin &nbsp;<strong>".$mc."</strong><br>- Ukuran &nbsp;<strong>".$ori."</strong> Meter";
                echo json_encode(array("statusCode"=>200, "psn"=>$text, "ukrsblm"=>$ori));
            } else {
                $text = "<span style='color:red;'>".$kode." telah di proses inspect Finish</code>";
                echo json_encode(array("statusCode"=>200, "psn"=>$text));
            }
            } else {
                echo json_encode(array("statusCode"=>404, "psn"=>"Posisi barang tidak di Samatex"));
            }
        } else {
            echo json_encode(array("statusCode"=>404, "psn"=>"Kode tidak ditemukan"));
        }
  } //end

  function cariInsgrey2(){
        $kode = $this->input->post('kode');
        $cekkode = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kode]);
        if($cekkode->num_rows() == 1){
            $cek_fol = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode]);
            if($cek_fol->num_rows() == 0){
                $kons = $cekkode->row("konstruksi");
                $mc = $cekkode->row("no_mesin");
                $ori = $cekkode->row("ukuran_ori");
                $text = "Data Inspect Grey<br>- Konstruksi &nbsp;<strong>".$kons."</strong><br>- Nomor Mesin &nbsp;<strong>".$mc."</strong><br>- Ukuran &nbsp;<strong>".$ori."</strong> Meter";
                echo json_encode(array("statusCode"=>200, "psn"=>$text));
            } else {
                $text = "<span style='color:red;'>".$kode." telah di proses Folding</code>";
                echo json_encode(array("statusCode"=>200, "psn"=>$text));
            }
            
        } else {
            echo json_encode(array("statusCode"=>404, "psn"=>"Kode tidak ditemukan"));
        }

  } //end

  function loadpaketpst23(){
    $bln = ['00'=>'undf', '01'=>'Jan', '02'=>'Feb', '03'=>'Mar', '04'=>'Apr', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Ags', '09'=>'Sep', '10'=>'Okt', '11'=>'Nov', '12'=>'Des'];
    $yr = date('Y');
        $query = $this->db->query("SELECT * FROM new_tb_pkgkepst WHERE to_pabrik='Pusatex' ORDER BY tmps_tmp DESC limit 200");
        if($query->num_rows() > 0){
            echo '<tr>
                    <td><strong>Kode Pkg</strong></td>
                    <td><strong>Konstruksi</strong></td>
                    <td><strong>Jml Roll</strong></td>
                    <td><strong>Total Panjang</strong></td>
                    <td><strong>Tanggal</strong></td>
                  </tr>'; 
            foreach($query->result() as $ar){
                echo "<tr>";
                echo "<td><a href='".base_url('users/createkirimpst3/'.$ar->kode_pkg)."'>".$ar->kode_pkg."</a></td>";
                $konst = $this->db->query("SELECT * FROM new_tb_packinglist WHERE kd='$ar->kode_pkg'")->row("kode_konstruksi");
                echo "<td>".$konst."</td>";
                echo "<td>".$ar->jml_roll."</td>";
                if(fmod($ar->total_panjang, 1) !== 0.00){
                    $pjg = number_format($ar->total_panjang,2,',','.');
                } else {
                    $pjg = number_format($ar->total_panjang,0,',','.');
                }
                echo "<td>".$pjg."</td>";
                $nows = explode(' ', $ar->tmps_tmp);
                $ex = explode('-', $nows[0]);
                if($ex[0]==$yr){
                    echo "<td>".$ex[2]." ".$bln[$ex[1]]."</td>";
                } else {
                    echo "<td>".$ex[2]." ".$bln[$ex[1]]." ".$ex[0]."</td>";
                }
                
                echo "</tr>";
            }
        } else {
            echo "<tr><td style='color:red;'>Tidak ada kiriman ke Pusatex</td></tr>";
        }
        
  }

  function loadpaketpst(){
    $bln = ['00'=>'undf', '01'=>'Jan', '02'=>'Feb', '03'=>'Mar', '04'=>'Apr', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Ags', '09'=>'Sep', '10'=>'Okt', '11'=>'Nov', '12'=>'Des'];
    $yr = date('Y');
    $username =strtolower($this->input->post('username'));
    $cekusername = $this->data_model->get_byid('a_operator', ['username'=>$username, 'produksi'=>'kirimpst']);
    if($cekusername->num_rows() == 1){
        $query = $this->data_model->get_byid('new_tb_packinglist', ['lokasi_now'=>'Samatex','siap_jual'=>'n', 'kepada'=>'NULL', 'no_sj'=>'NULL']);
        if($query->num_rows() > 0){
            echo '<tr>
                    <td>Kode Pkg</td>
                    <td>Konstruksi</td>
                    <td>Jml Roll</td>
                    <td>Total Panjang</td>
                    <td>Tanggal</td>
                    <td>User</td>
                  </tr>'; 
            foreach($query->result() as $val):
                echo "<tr>";
                if($val->kepada=="NULL"){
                    echo "<td style='font-weight:bold;color:#4287f5;'><a href='".base_url('users/createkirimpst/'.$val->kd)."'>".$val->kd."</a></td>";
                } else { 
                    echo "<td style='font-weight:bold;color:#000000;'><a style='color:#000;text-decoration:none;' href='".base_url('users/createkirimpst/'.$val->kd)."'>".$val->kd."</a></td>";
                }
                echo "<td>".$val->kode_konstruksi."</td>";
                echo "<td>".$val->jumlah_roll."</td>";
                if(fmod($val->ttl_panjang, 1) !== 0.00){
                    $pjg = number_format($val->ttl_panjang,2,',','.');
                } else {
                    $pjg = number_format($val->ttl_panjang,0,',','.');
                }
                echo "<td>".$pjg."</td>";
                $ex = explode('-', $val->tanggal_dibuat);
                if($ex[0]==$yr){
                    echo "<td>".$ex[2]." ".$bln[$ex[1]]."</td>";
                } else {
                    echo "<td>".$ex[2]." ".$bln[$ex[1]]." ".$ex[0]."</td>";
                }
                echo "<td>".$val->ygbuat."</td>";
                echo "</tr>";
            endforeach;
        } else {
            echo "<tr><td style='color:red;'>Semua Paket Sudah di Kirim ke Pusatex</td></tr>";
        }
    } else {
        echo "<tr><td colspan='5'><span style='color:red;'>Tidak berhasil mengambil data packing penjualan. Anda perlu login ulang</span></td></tr>";
    }
  } //end
  function loadDataStokGreyKiriman(){
    $ar = array(
        '00' => 'NaN', '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
       );
    $query = $this->data_model->get_byid('new_tb_packinglist', ['kepada'=>'Samatex']);
    $query = $this->db->query("SELECT * FROM new_tb_packinglist WHERE kepada='Samatex' ORDER BY tanggal_dibuat DESC LIMIT 100");
    if($query->num_rows() > 0){
    echo "<tr><td><strong>No.PKG</strong></td><td style='width:100px;'><strong>Tgl SJ</strong></td><td><strong>Konstruksi</strong></td><td><strong>Roll</strong></td><td><strong>Panjang</strong></td></tr>";
    foreach($query->result() as $val){
        //if($val->prod_ig > 0){
            $_sj = $val->no_sj;
            $tgl_sj = $this->db->query("SELECT no_sj,tgl_kirim FROM surat_jalan WHERE no_sj='$_sj'")->row("tgl_kirim");
            echo "<tr>";
            echo "<td><a href='".base_url('users/createkirimpst3/'.$val->kd)."'><strong>".$val->kd."</strong></a></td>";
            $ex=explode('-',$tgl_sj);
            if($ex[0]==date('Y')){ echo "<td>".$ex[2]." ".$ar[$ex[1]]."</td>"; } else {
            echo "<td>".$ex[2]."/".$ex[1]."/".$ex[0]."</td>";}
            //echo "<td>".$val->no_sj."</td>";
            echo "<td>".$val->kode_konstruksi."</td>";
            echo "<td>".$val->jumlah_roll."</td>";
            if(fmod($val->ttl_panjang, 1) !== 0.00){
                $pjg = number_format($val->ttl_panjang,2,',','.');
            } else {
                $pjg = number_format($val->ttl_panjang,0,',','.');
            }
            echo "<td>".$pjg."</td>";
            echo "</tr>";
        //}
    }
    } else {
        echo "<tr><td><strong>Tidak ada kiriman</strong></td></tr>";
    }
  } //end

  function loadDataStokGrey(){
    $username =strtolower($this->input->post('username'));
    $cekusername = $this->data_model->get_byid('a_operator', ['username'=>$username, 'produksi'=>'kirimpst']);
    if($cekusername->num_rows() == 1){
        $query = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex']);
        echo "<tr><td><strong>KONSTRUKSI</strong></td><td><strong>ORI</strong></td><td><strong>BS</strong></td><td><strong>BP</strong></td></tr>";
        foreach($query->result() as $vl){
            $ig = $vl->prod_ig;
            $bs = $vl->prod_bs1;
            $bp = $vl->prod_bp1;
            if($ig < 1 AND $bs < 1 AND $bp < 1){} else {
            echo "<tr>";
            echo "<td>".$vl->kode_konstruksi."</td>";
            if($ig>0){echo "<td>".number_format($ig)."</td>";} else {echo"<td>-</td>";}
            if($bs>0){echo "<td>".number_format($bs)."</td>";} else {echo"<td>-</td>";}
            if($bp>0){echo "<td>".number_format($bp)."</td>";} else {echo"<td>-</td>";}
            echo "</tr>";
            }
        }
    } else {
        echo "<tr><td colspan='2'><span style='color:red;'>Tidak berhasil mengambil data stok. Anda perlu login ulang</span></td></tr>";
    }
  } //end
  
  function inputRollLama(){
        $koderoll = $this->input->post('newKode');
        $ukuran = $this->input->post('newukuran');
        $pkg = $this->input->post('id_pkg');
        if(floatval($ukuran)>0 AND $pkg!=""){
            $thispkg = $this->data_model->get_byid('new_tb_packinglist',['kd'=>$pkg])->row_array();
            $kons = $thispkg['kode_konstruksi'];
            $siapjual = $thispkg['siap_jual'];
            $roll = $thispkg['jumlah_roll'];
            $panjang = $thispkg['ttl_panjang'];
            $satuan = $thispkg['jns_fold']=="Grey" ? "Meter":"Yard";
            if($koderoll==""){
                $dtlis = [
                    'kd' => $pkg,
                    'konstruksi' => $kons,
                    'siap_jual' => $siapjual,
                    'kode' => '',
                    'ukuran' => $ukuran,
                    'status' => 'null',
                    'satuan' => $satuan
                ];
                $this->data_model->saved('new_tb_isi', $dtlis);
                $newRoll = intval($roll) + 1;
                $newpjng = floatval($panjang) + floatval($ukuran);
                $dtlispkg = [
                    'jumlah_roll' => $newRoll,
                    'ttl_panjang' => round($newpjng,2)
                ];
                $this->data_model->updatedata('kd',$pkg,'new_tb_packinglist',$dtlispkg);
                echo json_encode(array("statusCode"=>200, "psn"=>"Added Roll"));
            } else {
                $carikode = $this->data_model->get_byid('new_tb_isi', ['kode'=>$koderoll])->num_rows();
                if($carikode == 0){
                    $dtlis = [
                        'kd' => $pkg,
                        'konstruksi' => $kons,
                        'siap_jual' => $siapjual,
                        'kode' => $koderoll,
                        'ukuran' => $ukuran,
                        'status' => 'null',
                        'satuan' => $satuan
                    ];
                    $this->data_model->saved('new_tb_isi', $dtlis);
                    $newRoll = intval($roll) + 1;
                    $newpjng = floatval($panjang) + floatval($ukuran);
                    $dtlispkg = [
                        'jumlah_roll' => $newRoll,
                        'ttl_panjang' => round($newpjng,2)
                    ];
                    $this->data_model->updatedata('kd',$pkg,'new_tb_packinglist',$dtlispkg);
                    echo json_encode(array("statusCode"=>200, "psn"=>"Added Roll"));
                } else {
                    echo json_encode(array("statusCode"=>404, "psn"=>"Kode Roll sudah di packinglist"));
                }
            }
        } else {
            echo json_encode(array("statusCode"=>404, "psn"=>"Isi data dengan benar!!"));
        }
  } //end

  function getCodeFromPst(){
        $koderoll = $this->input->post('kode');
        
        //$tgl = $this->input->post('tgl');
        $tgl = date('Y-m-d');
        $tgl2 = date('Y-m-d H:i:s');
        $user = $this->input->post('username');
        $kd1 = $this->data_model->get_byid('data_ig', ['kode_roll'=>$koderoll]);
        $kd = $kd1->row_array();
        $kons = $kd['konstruksi'];
        $ukuran = $kd['ukuran_ori'];
        $mc = $kd['no_mesin'];
        $locAwal = $kd['dep'];
        $locNow = $kd['loc_now'];
        if($locNow == "Samatex"){
            echo "Barang Sudah di Samatex";
        } else {
            $locAwal2 = $this->db->query("SELECT * FROM new_tb_isi WHERE kode='$koderoll' ORDER BY id_isi DESC LIMIT 1")->row("kd");
            if(strpos($locAwal2, "RJ") !== false){
                $dep = "rjsToPusatex";
            } elseif(strpos($locAwal2, "PK") !== false){
                $dep = "stxToPusatex";
            }
            // if($locAwal=="RJS"){
            //     $dep = "rjsToPusatex";
            // } else {
            //     $dep = "stxToPusatex";
            // }
        $this->data_model->updatedata('kode',$koderoll,'new_tb_isi',['status'=>'fixsend']);
        $this->data_model->updatedata('kode_roll',$koderoll,'data_ig',['loc_now'=>'Samatex']);
        $cekstok = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex','kode_konstruksi'=>$kons]);
        if($cekstok->num_rows() == 1){
        $idstok = $cekstok->row("idstok");
        $jmlStok = floatval($cekstok->row("prod_ig")) + floatval($ukuran);
        $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($jmlStok,2)]);
        } else {
            $dtlis = [
                'dep' => 'newSamatex',
                'kode_konstruksi' => $kons,
                'prod_ig' => $ukuran,
                'prod_fg' => 0,
                'prod_if' => 0,
                'prod_ff' => 0,
                'prod_bs1' => 0,
                'prod_bp1' => 0,
                'prod_bs2' => 0,
                'prod_bp2' => 0,
                'crt' => 0
            ];
            $this->data_model->saved('data_stok', $dtlis);
        }

        $cekstok = $this->data_model->get_byid('data_stok', ['dep'=>$dep,'kode_konstruksi'=>$kons]);
        $idstok = $cekstok->row("idstok");
        $jmlStok = floatval($cekstok->row("prod_ig")) - floatval($ukuran);
        $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($jmlStok,2)]);
        $dtlis = [
            'kode_roll' => $koderoll,
            'mc' => $mc,
            'ukuran' => $ukuran,
            'konstruksi' => $kons,
            'tanggal' => $tgl,
            'operator' => strtolower($user),
            'tmstmp' => $tgl2
        ];
        $this->data_model->saved('kiriman_pusatex', $dtlis);
        $this->db->query("DELETE FROM new_roll_onpst WHERE kode_roll='$koderoll' ");
        echo "berhasil";
        }
  }//end

  function getCodeFromPst2(){
        $kode = $this->input->post('kode');
        $ukr = $this->input->post('ukr');
        $mc = $this->input->post('mc');
        $kons = $this->input->post('kons');
        $tgl = $this->input->post('tgl');
        $opt = $this->input->post('username');

        if($kode!="" AND $ukr!="" AND $mc!="" AND $kons!=""){
            $cek_kons = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kons]);
            if($cek_kons->num_rows() == 1){
            $cekkode = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kode]);
            if($cekkode->num_rows() == 0){
                $dtlist = [
                    'kode_roll' => $kode,
                    'konstruksi' => $kons,
                    'no_mesin' => $mc,
                    'no_beam' => '',
                    'oka' => '',
                    'ukuran_ori' => $ukr,
                    'ukuran_bs' => 0,
                    'ukuran_bp' => 0,
                    'tanggal' => $tgl,
                    'operator' => $opt,
                    'bp_can_join' => $ukr<50 ? 'true':'false',
                    'dep' => 'Samatex',
                    'loc_now' => 'Samatex',
                    'yg_input' => 0,
                    'kode_upload' => 'tes'
                ];
                $this->data_model->saved('data_ig', $dtlist);
                $dtlis22 = [
                    'kode_roll' => $kode,
                    'mc' => $mc,
                    'ukuran' => $ukr,
                    'konstruksi' => $kons,
                    'tanggal' => $tgl,
                    'operator' => $opt
                ];
                $this->data_model->saved('kiriman_pusatex', $dtlis22);
                $cekStok = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex','kode_konstruksi'=>$kons]);
                if($cekStok->num_rows() == 0){
                    $listStok = [
                        'dep' => 'newSamatex',
                        'kode_konstruksi' => $kons,
                        'prod_ig' => $ukr,
                        'prod_fg' => 0,
                        'prod_if' => 0,
                        'prod_ff' => 0,
                        'prod_bs1' => 0,
                        'prod_bp1' => 0,
                        'prod_bs2' => 0,
                        'prod_bp2' => 0,
                        'crt' => 0
                    ];
                    $this->data_model->saved('data_stok', $listStok);
                } else {
                    $idstok = $cekStok->row("idstok");
                    $newig = floatval($cekStok->row("prod_ig")) + floatval($ukr);
                    $listStok = [
                        'prod_ig' => round($newig,2)
                    ];
                    $this->data_model->updatedata('idstok',$idstok,'data_stok',$listStok);
                }
                $txt = "$kode diterima Samatex";
                echo json_encode(array("statusCode"=>200, "psn"=>$txt));
            } else {
                echo json_encode(array("statusCode"=>404, "psn"=>"Kode Roll sudah digunakan"));
            }
            
            } else {
                echo json_encode(array("statusCode"=>404, "psn"=>"Salah penulisan konstruksi"));
            }
        } else {
            echo json_encode(array("statusCode"=>404, "psn"=>"Isi data dengan benar!!"));
        }
  } //end

  function kodetojoin(){
        $kode = $this->input->post('inOldkode');
        $kons = strtoupper($this->input->post('kons'));
        $ukr = $this->input->post('inOllukr');
        $cekKode = $this->data_model->get_byid('data_if', ['kode_roll'=>$kode]);
        if($cekKode->num_rows() == 1){
            $kodeRoll = $cekKode->row("kode_roll");
            $ukuran = $cekKode->row("ukuran_ori");
            $konsasli = strtoupper($cekKode->row("konstruksi"));
            if($kons == $konsasli){
            $st = "fromdata";
            echo json_encode(array("statusCode"=>200, "koderoll"=>$kodeRoll, "ukuran"=>$ukuran, "kons"=>$kons, "st"=>$st));
            } else {
                echo json_encode(array("statusCode"=>404, "psn"=>"Beda konstruksi"));
            }
        } else {
            $st = "olddata";
            echo json_encode(array("statusCode"=>200, "koderoll"=>$kode, "ukuran"=>$ukr, "kons"=>$kons, "st"=>$st));
        }
  } //end

  function delinsgrey(){
        $id_data = $this->input->post('iddata');
        $dt = $this->data_model->get_byid('data_ig', ['id_data'=>$id_data])->row_array();
        $konstruksi = $dt['konstruksi'];
        $ukuran = $dt['ukuran_ori'];
        $bs = $dt['ukuran_bs'];
        $bp = $dt['ukuran_bp'];
        $tgl = $dt['tanggal'];
        $operator = $dt['operator'];
        $dep = $dt['dep'];
        $stokdep = "new".$dep."";
        //del produksi 
        $prod = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$konstruksi, 'tgl'=>$tgl, 'dep'=>$dep])->row_array();
        $id_prod = $prod['id_produksi'];
        $thisig = floatval($prod['prod_ig']) - floatval($ukuran);
        $thisbs = floatval($prod['prod_bs1']) - floatval($bs);
        $thisbp = floatval($prod['prod_bp1']) - floatval($bp);
        $this->data_model->updatedata('id_produksi',$id_prod,'data_produksi',['prod_ig'=>round($thisig,2),'prod_bs1'=>round($thisbs,2),'prod_bp1'=>round($thisbp,2)]);
        //del produksi harian
        $prod = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl, 'dep'=>$dep])->row_array();
        $id_prod = $prod['id_prod_hr'];
        $thisig = floatval($prod['prod_ig']) - floatval($ukuran);
        $thisbs = floatval($prod['prod_bs1']) - floatval($bs);
        $thisbp = floatval($prod['prod_bp1']) - floatval($bp);
        $this->data_model->updatedata('id_prod_hr',$id_prod,'data_produksi_harian',['prod_ig'=>round($thisig,2),'prod_bs1'=>round($thisbs,2),'prod_bp1'=>round($thisbp,2)]);
        //del produksi operator
        $prod = $this->data_model->get_byid('data_produksi_opt', ['username_opt'=>$operator,'konstruksi'=>$konstruksi,'tgl'=>$tgl,'proses'=>'insgrey'])->row_array();
        $id_prod = $prod['id_propt'];
        $thisig = floatval($prod['ukuran']) - floatval($ukuran);
        $thisbs = floatval($prod['bs']) - floatval($bs);
        $thisbp = floatval($prod['bp']) - floatval($bp);
        $this->data_model->updatedata('id_propt',$id_prod,'data_produksi_opt',['ukuran'=>round($thisig,2),'bs'=>round($thisbs,2),'bp'=>round($thisbp,2)]);
        //del stok
        $stok = $this->data_model->get_byid('data_stok', ['dep'=>$stokdep, 'kode_konstruksi'=>$konstruksi])->row_array();
        $idstok = $stok['idstok'];
        $thisig = floatval($stok['prod_ig']) - floatval($ukuran);
        $thisbs = floatval($stok['prod_bs1']) - floatval($bs);
        $thisbp = floatval($stok['prod_bp1']) - floatval($bp);
        $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($thisig,2),'prod_bs1'=>round($thisbs,2),'prod_bp1'=>round($thisbp,2)]);
        $this->db->query("DELETE FROM data_ig WHERE id_data='$id_data'");
        echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
  } //end
  function delinsfinish(){
    $id_infs = $this->input->post('iddata');
    //echo $id_infs;
    $dt = $this->data_model->get_byid('data_if',['id_infs'=>$id_infs])->row_array();
    $kode_roll = $dt['kode_roll'];
    $kons = $dt['konstruksi'];
    $tgl = $dt['tgl_potong'];
    $ukuran = $dt['ukuran_ori'];
    $bs = $dt['ukuran_bs'];
    $bp = $dt['ukuran_bp'];
    $crt = $dt['ukuran_crt'];
    $ukr_seblm = $dt['ukuran_sebelum'];
    $operator = $dt['operator'];
    $idfol = $this->input->post('kdroll');
        //hapus dulu data produksi
        $produksi = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kons,'tgl'=>$tgl,'dep'=>'Samatex'])->row_array();
        $id_produksi = $produksi['id_produksi'];
        
            $newif = floatval($produksi['prod_if']) - floatval($ukuran);
            $newcrt = floatval($produksi['crt']) - floatval($crt);
            $newbp = floatval($produksi['prod_bp2']) - floatval($bp);
            $newbs = floatval($produksi['prod_bs2']) - floatval($bs);
            $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',['prod_if'=>round($newif,2), 'prod_bs2'=>round($newbs,2), 'prod_bp2'=>round($newbp,2), 'crt'=>round($newcrt,2)]);
        
        //hapus produksi harian total
        $produksi = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl,'dep'=>'Samatex'])->row_array();
        $id_produksi = $produksi['id_prod_hr'];
        
            $newif = floatval($produksi['prod_if']) - floatval($ukuran);
            $newcrt = floatval($produksi['crt']) - floatval($crt);
            $newbp = floatval($produksi['prod_bp2']) - floatval($bp);
            $newbs = floatval($produksi['prod_bs2']) - floatval($bs);
            $this->data_model->updatedata('id_prod_hr',$id_produksi,'data_produksi_harian',['prod_if'=>round($newif,2), 'prod_bs2'=>round($newbs,2), 'prod_bp2'=>round($newbp,2), 'crt'=>round($newcrt,2)]);
        
        //hapus produksi operator tersebut
        
            $cekopt = $this->data_model->get_byid('data_produksi_opt', ['username_opt'=>$operator,'konstruksi'=>$kons,'tgl'=>$tgl,'proses'=>'insfinish']);
        
        $id_propt = $cekopt->row("id_propt");
        $newUkuran = floatval($cekopt->row("ukuran")) - floatval($ukuran);
        $newbs = floatval($cekopt->row("bs")) - floatval($bs);
        $newbp = floatval($cekopt->row("bp")) - floatval($bp);
        $newcrt = floatval($cekopt->row("crt")) - floatval($crt);
        $this->data_model->updatedata('id_propt',$id_propt,'data_produksi_opt',['ukuran'=>round($newUkuran,2), 'bs'=>round($newbs),'bp'=>round($newbp,2),'crt'=>round($newcrt,2)]);
        //update stok 
        $stok = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex','kode_konstruksi'=>$kons]);
        $idstok = $stok->row("idstok");
        
            $ukuran_ig = $this->data_model->get_byid('data_ig',['kode_roll'=>$kode_roll])->row("ukuran_ori");
            $prod_ig = floatval($stok->row("prod_ig")) + floatval($ukuran_ig);
            $prod_if = floatval($stok->row("prod_if")) - floatval($ukuran);
            $bs2 = floatval($stok->row("prod_bs2")) - floatval($bs);
            $bp2 = floatval($stok->row("prod_bp2")) - floatval($bp);
            $crt2 = floatval($stok->row("crt")) - floatval($crt);
            $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($prod_ig,2),'prod_if'=>round($prod_if,2),'prod_bs2'=>round($bs2,2),'prod_bp2'=>round($bp2,2),'crt'=>round($crt,2)]);
        
        $this->data_model->delete('data_if','id_infs',$id_infs);
        $this->data_model->updatedata('kode_roll',$kode_roll,'data_ig',['loc_now'=>'Samatex']);
        echo json_encode(array("statusCode"=>200, "psn"=>$kode_roll));
} //end


    function loadDataStokGreyInPusatex(){
        $alldata = $this->db->query("SELECT * FROM new_roll_onpst");
        if($alldata->num_rows() == 0){
            echo "<tr><td>Tidak Ada Kain di Pusatex</td></tr>";
        } else {
            $kons = array();       
            foreach($alldata->result() as $val){
                if(in_array($val->kons, $kons)){

                } else {
                    $kons[]=$val->kons;
                }
            }
            echo "<tr>";
            echo "<td><strong>Konstruksi</strong></td>";
            echo "<td><strong>RJS</strong></td>";
            echo "<td><strong>Samatex</strong></td>";
            echo "<td><strong>Total</strong></td>";
            echo "</tr>";
            foreach($kons as $kons2){
                echo "<tr>";
                echo "<td>".$kons2."</td>";

                $stok_rjs = $this->db->query("SELECT SUM(ukuran) AS ukr FROM new_roll_onpst WHERE kode_roll LIKE 'R%' AND kons='$kons2' ")->row("ukr");
                if(fmod($stok_rjs, 1) !== 0.00){
                    $pjg_rjs = number_format($stok_rjs,2,',','.');
                } else {
                    $pjg_rjs = number_format($stok_rjs,0,',','.');
                }
                //echo "<td>".$pjg_rjs."</td>";
                ?><td><a href="<?=base_url('stok/pst/r/'.$kons2);?>" style="text-decoration:none;"><?=$pjg_rjs;?></a></td><?php
                $stok_smt = $this->db->query("SELECT SUM(ukuran) AS ukr FROM new_roll_onpst WHERE kode_roll LIKE 'S%' AND kons='$kons2' ")->row("ukr");
                if(fmod($stok_smt, 1) !== 0.00){
                    $pjg_smt = number_format($stok_smt,2,',','.');
                } else {
                    $pjg_smt = number_format($stok_smt,0,',','.');
                }
                //echo "<td>".$pjg_smt."</td>";
                ?><td><a href="<?=base_url('stok/pst/s/'.$kons2);?>" style="text-decoration:none;"><?=$pjg_smt;?></a></td><?php
                $stok_all = $this->db->query("SELECT SUM(ukuran) AS ukr FROM new_roll_onpst WHERE kons='$kons2' ")->row("ukr");
                if(fmod($stok_all, 1) !== 0.00){
                    $pjg_all = number_format($stok_all,2,',','.');
                } else {
                    $pjg_all = number_format($stok_all,0,',','.');
                }
                echo "<td>".$pjg_all."</td>";
                echo "</tr>";
            }
        }
    } //end function

    function loadKirimanPusatex7hari(){
        $bln = ['00'=>'undf', '01'=>'Jan', '02'=>'Feb', '03'=>'Mar', '04'=>'Apr', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Ags', '09'=>'Sep', '10'=>'Okt', '11'=>'Nov', '12'=>'Des'];
        $hari = [
            'Sunday' => 'Minggu',
            'Saturday' => 'Sabtu',
            'Friday' => 'Jumat',
            'Thursday' => 'Kamis',
            'Wednesday' => 'Rabu',
            'Tuesday' => 'Selasa',
            'Monday' => 'Senin' ];
        
        $tanggalHariIni = date('Y-m-d', strtotime('today'));
        // echo "<tr>";
        // echo '<td>Tanggal hari ini: ' . $tanggalHariIni . '</td>';
        // echo "</tr>";
        // Tanggal 6 hari sebelumnya
        for ($i = 0; $i < 7; $i++) {
            $tanggal = date('Y-m-d', strtotime('-' . $i . ' days'));
            $nama_hari = date("l", strtotime($tanggal));
            $ecx = explode('-', $tanggal);
            $terima_total = $this->db->query("SELECT SUM(ukuran) AS ukr FROM kiriman_pusatex WHERE tanggal='$tanggal'")->row("ukr");
            if(fmod($terima_total, 1) !== 0.00){
                $pjg_all = number_format($terima_total,2,',','.');
            } else {
                $pjg_all = number_format($terima_total,0,',','.');
            }
            if($terima_total > 0){
            echo "<tr><td>Diterima : <strong>".$hari[$nama_hari].", ".$ecx[2]."-".$bln[$ecx[1]]."</strong> (Total : <strong>".$pjg_all."</strong>)";
                $alldtq = $this->db->query("SELECT * FROM kiriman_pusatex WHERE tanggal='$tanggal'");
                $arkons = array();
                foreach ($alldtq->result() as $key => $value) {
                    if(in_array($value->konstruksi, $arkons)){} else { $arkons[]=$value->konstruksi; }
                }
                foreach($arkons as $kons){
                    echo "<br>";
                    $terima_total2 = $this->db->query("SELECT SUM(ukuran) AS ukr FROM kiriman_pusatex WHERE konstruksi='$kons' AND tanggal='$tanggal'")->row("ukr");
                    if(fmod($terima_total2, 1) !== 0.00){
                        $pjg_all2 = number_format($terima_total2,2,',','.');
                    } else {
                        $pjg_all2 = number_format($terima_total2,0,',','.');
                    }

                    $terima_total3 = $this->db->query("SELECT SUM(ukuran) AS ukr FROM kiriman_pusatex WHERE kode_roll LIKE 'R%' AND konstruksi='$kons' AND tanggal='$tanggal'")->row("ukr");
                    if(fmod($terima_total3, 1) !== 0.00){
                        $pjg_all3 = number_format($terima_total3,2,',','.');
                    } else {
                        $pjg_all3 = number_format($terima_total3,0,',','.');
                    }

                    $terima_total4 = $this->db->query("SELECT SUM(ukuran) AS ukr FROM kiriman_pusatex WHERE kode_roll LIKE 'S%' AND konstruksi='$kons' AND tanggal='$tanggal'")->row("ukr");
                    if(fmod($terima_total4, 1) !== 0.00){
                        $pjg_all4 = number_format($terima_total4,2,',','.');
                    } else {
                        $pjg_all4 = number_format($terima_total4,0,',','.');
                    }
                    echo $kons." : ".$pjg_all2." (";
                    if($terima_total3 > 0){
                        echo "RJS : ".$pjg_all3."";
                    }
                    if($terima_total4 > 0){
                        if($terima_total3>0) {echo ", ";}
                        echo "SMT : ".$pjg_all4."";
                    }
                    echo ")";
                }
                echo "</td></tr>";
            }
            
        }
    } //end
    function cekkoderollss(){
        $kons = $this->input->post('kons');
        $kode = $this->input->post('kode');
        $txt_kode = $this->input->post('txt_kode');
        $txt_ukuran = $this->input->post('txt_ukuran');
        $x_one = explode('-', $txt_kode);
        if (in_array($kode, $x_one)){
            echo json_encode(array("statusCode"=>400, "psn"=>"Kode Roll Sudah dalam paket"));
        } else {
            $cekKons = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kons])->num_rows();
            if($cekKons == 1){
                $cekKode = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode, 'jns_fold'=>'Grey']);
                if($cekKode->num_rows() == 1){
                    $hslkons = $cekKode->row("konstruksi");
                    $posisi = $cekKode->row("posisi");
                    if($hslkons == $kons){
                        if($posisi == "Samatex"){
                            $_kode = $cekKode->row("kode_roll");
                            $_ukuran = $cekKode->row("ukuran");
                            if($txt_kode == ""){
                                $in_kode = "".$_kode;
                                $in_ukr = "".$_ukuran;
                            } else {
                                $in_kode = $txt_kode."-".$_kode;
                                $in_ukr = $txt_ukuran."-".$_ukuran;
                            }
                            $ukri_ex = explode('-', $in_ukr);
                            $total_roll = count($ukri_ex);
                            $total_array = array_sum($ukri_ex);
                            echo json_encode(array("statusCode"=>200, "psn"=>"oke", "kode_roll"=>$_kode, "ukuran"=>$_ukuran, "in_kode"=>$in_kode, "in_ukr"=>$in_ukr, "total_array"=>$total_array, "jmlRoll"=>$total_roll));
                        } else {
                            echo json_encode(array("statusCode"=>400, "psn"=>"Posisi barang tidak di samatex"));
                        }
                    } else {
                        echo json_encode(array("statusCode"=>400, "psn"=>"Konstruksi tidak sama"));
                    }
                } else {
                    echo json_encode(array("statusCode"=>400, "psn"=>"Kode roll tidak ditemukan"));
                }
            } else {
                echo json_encode(array("statusCode"=>400, "psn"=>"Konstruksi tidak ditemukan"));
            }
        } 
    } //end

    function delkoderollss(){
        $kode = $this->input->post('kode');
        $txt_kode = $this->input->post('txt_kode');
        $txt_ukuran = $this->input->post('txt_ukuran');
        $x_one = explode('-', $txt_kode);
        $x_two = explode('-', $txt_ukuran);
        $index = array_search($kode, $x_one);
        unset($x_one[$index]);
        unset($x_tow[$index]);
        $im_one = implode('-', $x_one);
        $im_two = implode('-', $x_two);
        echo json_encode(array("statusCode"=>200, "in"=>$im_one, "in2"=>$im_two));
    } //end
    
    function greyfinishproses(){
        $kons = $this->input->post('kons');
        $ukr_fol = $this->input->post('totalukr');
        $kode = $this->input->post('allkode');
        $jml = count($kode);
        $ukuran_ins = array();
        $ukuran_fol = array();
        // echo "<pre>";
        // print_r($kode);
        // echo "</pre>";
          $cekkode = $this->data_model->get_byid('data_ig_code', ['idcode'=>3])->row_array();
          $cekkode_alpabet = $cekkode['alpabet'];
          $new_number = intval($cekkode['numskr']) + 1;
          $kdpkg = $cekkode_alpabet."".$new_number;
        foreach($kode as $kd):
            //echo "$kd <br>";
            $ukr = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kd])->row("ukuran_ori");
            $ukr2 = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kd])->row("ukuran");
            $ukuran_ins[] = $ukr;
            $ukuran_fol[] = $ukr2;
            $this->data_model->updatedata('kode_roll',$kd,'data_ig',['loc_now'=>$kdpkg]);
            $this->db->query("DELETE FROM data_fol WHERE kode_roll='$kd' AND jns_fold='Grey'");
            $this->data_model->saved('new_tb_isi', [
                'kd' => $kdpkg,
                'konstruksi' => $kons,
                'siap_jual' => 'n',
                'kode' => $kd,
                'ukuran' => $ukr,
                'status' => 'oke',
                'satuan' => 'Meter'
            ]);
            $this->data_model->saved('delete_Stok', [
                'kode_roll' => $kd,
                'ukuran_folding' => $ukr2,
                'jns_fold' => 'Grey'
            ]);
        endforeach;
        // echo "Ukuran Inspect".array_sum($ukuran_ins);
        // echo "Ukuran Folding".array_sum($ukuran_fol);
        $total_ukrfol = array_sum($ukuran_fol);
          $dtlist = [
              'kode_konstruksi' => $kons,
              'kd' => $kdpkg,
              'tanggal_dibuat' => date('Y-m-d'),
              'lokasi_now' => 'Samatex',
              'siap_jual' => 'n',
              'jumlah_roll' => $jml,
              'ttl_panjang' => array_sum($ukuran_ins),
              'kepada' => 'NULL',
              'no_sj' => 'NULL',
              'ygbuat' => 'edi',
              'jns_fold' => 'null'
          ];
          $this->data_model->saved('new_tb_packinglist', $dtlist);
          $this->data_model->updatedata('idcode',3,'data_ig_code',['numskr'=>$new_number]);
          $stok_grey = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex','kode_konstruksi'=>$kons])->row("prod_fg");
          $idstok = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex','kode_konstruksi'=>$kons])->row("idstok");
          $new_stok = floatval($stok_grey) - floatval($total_ukrfol);
          $new_stok2 = round($new_stok, 2);
          //echo "-".$new_stok2."<br>";
          //echo $stok_grey;
          $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_fg'=>$new_stok2]);
          
        // foreach($kode as $kd2){
        //     $this->data_model->updatedata('kode_roll',$kd2,'data_ig',['loc_now'=>$kdpkg]);
        //     $this->db->query("DELETE FROM data_fol WHERE kode_roll='$kd2' AND jns_fold='Grey'");
        //     $ukr = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kd2])->row("ukuran_ori");
            // $this->data_model->saved('new_tb_isi', [
            //     'kd' => $kdpkg,
            //     'konstruksi' => $kons,
            //     'siap_jual' => 'n',
            //     'kode' => $kd2,
            //     'ukuran' => $ukr,
            //     'status' => 'oke',
            //     'satuan' => 'Meter'
            // ]);
        // }
        // $idstok = $this->data_model->get_byid('data_stok',['dep'=>'newSamatex','kode_konstruksi'=>$kons])->row("idstok");
        // $ukrseblum = $this->data_model->get_byid('data_stok',['dep'=>'newSamatex','kode_konstruksi'=>$kons])->row("prod_fg");
        // $updatestok = floatval($ukrseblum) - floatval($ukr);
        // $this->data_model->updatedate('idstok',$idstok,'data_stok',['prod_fg'=>round($updatestok,2)]);
        redirect(base_url('users/penjualan'));
        //echo json_encode(array("statusCode"=>200, "psn"=>$kdpkg));
    } //end

    function delpkgonpst(){
        $kd1 = $this->uri->segment(3);
        $kd2 = $this->uri->segment(4);
        $kd3 = $this->uri->segment(5);
        //echo $kd1."<br>".$kd2."<br>".$kd3;
        $this->db->query("DELETE FROM new_roll_onpst WHERE kode_roll='$kd3'");
        redirect(base_url('stok/pst/'.$kd1.'/'.$kd2));
    }

}
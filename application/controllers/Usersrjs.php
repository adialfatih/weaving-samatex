<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usersrjs extends CI_Controller
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
        $this->load->view('users/loginrjs');
  } //end

  function insgrey(){
        $this->load->view('users/insgreyrjs');
  }
  
  function createkirimpst(){
    $this->load->view('users/createkirimpstrjsrjs');
  }
    function kirimpst(){
      $this->load->view('users/kirimpstrjs');
  }

  function cekopt(){
      $proses = $this->input->post('proses');
      $namaUser = $this->input->post('namaUser');
      $kecilkan_user = strtolower($namaUser);
      $cek_user = $this->data_model->get_byid('a_operator', ['username'=>$kecilkan_user]);
      if($cek_user->num_rows() == 1){
          $proses_user = $cek_user->row("produksi");
          if($proses_user == $proses){
            echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
          } else {
            echo json_encode(array("statusCode"=>200, "psn"=>"null"));
          }
          
      } else {
          echo json_encode(array("statusCode"=>404, "psn"=>"oke"));
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
                    <td><strong>Ukuran</strong></td>
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
                  echo "<td>".$val->no_mesin."</td>";
                  echo "<td>".$val->ukuran_ori."</td>";
                  ?><td>
                        <img src="<?=base_url('assets/del.png');?>" alt="Delete" style="width:20px;" onclick="owek('<?=$val->id_data;?>')">
                  </td><?php
                  echo "</tr>";
                  $jumlah_data--;
              endforeach;
          } else {
              echo '<tr><td colspan="5">Data Inspect Grey Anda Masih Kosong</td></tr>';
          }
      } //end insgrey
      if($proses == "insfinish"){
        //$query = $this->data_model->get_byid('data_ig', ['tanggal'=>$tgl, 'operator'=>$username]);
        $query = $this->db->query("SELECT * FROM data_if WHERE tgl_potong='$tgl' AND operator='$username' ORDER BY id_infs DESC");
        echo "<tr>
                  <td><strong>No.</strong></td>
                  <td><strong>Kode Roll</strong></td>
                  <td><strong>Konstruksi</strong></td>
                  <td><strong>MC</strong></td>
                  <td><strong>Ukuran (Yrd)</strong></td>
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
                echo "<td>".$nomc2."</td>";
                $ori_yard = $val->ukuran_ori / 0.9144;
                $showyard = round($ori_yard,2);
                echo "<td>".$showyard."</td>";
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
                <td><strong>MC</strong></td>
                <td><strong>Ukuran (Mtr)</strong></td>
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
              echo "<td>".$nomc2."</td>";
              //$ori_yard = $val->ukuran_ori * 0.9144;
              //$showyard = round($ori_yard,2);
              echo "<td>".$val->ukuran."</td>";
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
                <td><strong>MC</strong></td>
                <td><strong>Ukuran (Yrd)</strong></td>
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
              echo "<td>".$nomc2."</td>";
              //$ori_yard = $val->ukuran_ori * 0.9144;
              //$showyard = round($ori_yard,2);
              echo "<td>".$val->ukuran."</td>";
              echo "</tr>";
              $jumlah_data--;
          endforeach;
      } else {
          echo '<tr><td colspan="5">Data Folding Finish Anda Masih Kosong</td></tr>';
      }
    } //end folfinish
      
  } //end

  function prosesInsGrey(){
        
    $tableKode = $this->data_model->get_byid('data_ig_code', ['idcode'=>'2'])->row_array();
    $numSkr = $tableKode['numskr'];
    $newNumber = intval($numSkr) + 1;
    $allkode = $tableKode['alpabet']."".$newNumber;

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
            $dtlist = [
                'kode_roll' => $allkode,
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
                'dep' => 'RJS',
                'loc_now' => 'RJS',
                'yg_input' => 0,
                'kode_upload' => 'tes'
            ];
            $this->data_model->saved('data_ig', $dtlist);
            $this->data_model->updatedata('idcode',2,'data_ig_code',['numskr'=>$newNumber]);
            //cek produksi per sm harian
            $cek1 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kons,'tgl'=>$tgl,'dep'=>'RJS']);
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
                    'dep' => 'RJS',
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
            $cek2 = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl,'dep'=>'RJS']);
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
                    'dep' => 'RJS',
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
                    'crt' => 0
                ];
                $this->data_model->saved('data_produksi_opt', $dtlist2);
            }
            //end cek 3
            //cek data stok ke 4
            $cekStok = $this->data_model->get_byid('data_stok', ['dep'=>'newRJS','kode_konstruksi'=>$kons]);
            if($cekStok->num_rows() == 0){
                $listStok = [
                    'dep' => 'newRJS',
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
            echo json_encode(array("statusCode"=>200, "psn"=>$allkode));
        } else {
            echo json_encode(array("statusCode"=>404, "psn"=>"Konstruksi tidak ditemukan"));
        }
    } else {
        echo json_encode(array("statusCode"=>404, "psn"=>"Username operator tidak ditemukan"));
    }
      
       
  } //end proses insgrey saved

  function prosesInsFinish(){
        $koderoll = $this->input->post('koderoll');
        $ukuran = $this->input->post('ori');
        $bs = $this->input->post('bs');
        $bp = $this->input->post('bp');
        $crt = $this->input->post('crt');
        $tgl = $this->input->post('tgl');
        $operator = $this->input->post('username');
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
                    } 
                } //end for 
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
                    $dtlist2 = [
                        'username_opt' => $kecilkanOperator,
                        'konstruksi' => $kons,
                        'tgl' => $tgl,
                        'proses' => 'insfinish',
                        'ukuran' => round($totalOri,2), 
                        'bs' => $bs,
                        'bp' => $bp,
                        'crt' => $crt
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
        $this->load->view('users/report_insgreyrjs');
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
            $cek_finish = $this->data_model->get_byid('data_if', ['kode_roll'=>$kode]);
            if($cek_finish->num_rows() == 0){
                $kons = $cekkode->row("konstruksi");
                $mc = $cekkode->row("no_mesin");
                $ori = $cekkode->row("ukuran_ori");
                $text = "Data Inspect Grey<br>- Konstruksi &nbsp;<strong>".$kons."</strong><br>- Nomor Mesin &nbsp;<strong>".$mc."</strong><br>- Ukuran &nbsp;<strong>".$ori."</strong> Meter";
                echo json_encode(array("statusCode"=>200, "psn"=>$text));
            } else {
                $text = "<span style='color:red;'>".$kode." telah di proses inspect Finish</code>";
                echo json_encode(array("statusCode"=>200, "psn"=>$text));
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
  function loadpaketpst(){
    $bln = ['00'=>'undf', '01'=>'Jan', '02'=>'Feb', '03'=>'Mar', '04'=>'Apr', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Ags', '09'=>'Sep', '10'=>'Okt', '11'=>'Nov', '12'=>'Des'];
    $yr = date('Y');
    $username =strtolower($this->input->post('username'));
    $cekusername = $this->data_model->get_byid('a_operator', ['username'=>$username, 'produksi'=>'kirimpst']);
    if($cekusername->num_rows() == 1){
        $query = $this->data_model->get_byid('new_tb_packinglist', ['lokasi_now'=>'RJS','siap_jual'=>'n','ygbuat'=>$username]);
        if($query->num_rows() > 0){
            echo '<tr>
                    <td>Kode Pkg</td>
                    <td>Konstruksi</td>
                    <td>Jml Roll</td>
                    <td>Total Panjang</td>
                    <td>Tanggal</td>
                  </tr>'; 
            foreach($query->result() as $val):
                echo "<tr>";
                if($val->kepada=="NULL"){
                echo "<td style='font-weight:bold;color:#4287f5;'><a href='".base_url('usersrjs/createkirimpst/'.$val->kd)."'>".$val->kd."</a></td>";
                } else { echo "<td>".$val->kd."</td>"; }
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
                
                echo "</tr>";
            endforeach;
        } else {
            echo '<tr>
                    <td>Kode Pkg</td>
                    <td>Konstruksi</td>
                    <td>Jml Roll</td>
                    <td>Total Panjang</td>
                    <td>Tanggal</td>
                  </tr>'; 
            echo "<tr><td colspan='5'><span style='color:red;'>Anda belum membuat paket</span></td></tr>";
        }
    } else {
        echo "<tr><td colspan='5'><span style='color:red;'>Tidak berhasil mengambil data packing penjualan. Anda perlu login ulang</span></td></tr>";
    }
  } //end

  function loadDataStokGrey(){
    $username =strtolower($this->input->post('username'));
    $cekusername = $this->data_model->get_byid('a_operator', ['username'=>$username, 'produksi'=>'kirimpst']);
    if($cekusername->num_rows() == 1){
        $query = $this->data_model->get_byid('data_stok', ['dep'=>'newRJS']);
        echo "<tr><td><strong>Konstruksi</strong></td><td><strong>Stok</strong></td></tr>";
        foreach($query->result() as $val){
            if($val->prod_ig > 0){
                echo "<tr>";
                echo "<td>".$val->kode_konstruksi."</td>";
                if(fmod($val->prod_ig, 1) !== 0.00){
                    $pjg = number_format($val->prod_ig,2,',','.');
                } else {
                    $pjg = number_format($val->prod_ig,0,',','.');
                }
                echo "<td>".$pjg."</td>";
                echo "</tr>";
            }
        }
    } else {
        echo "<tr><td colspan='2'><span style='color:red;'>Tidak berhasil mengambil data stok. Anda perlu login ulang</span></td></tr>";
    }
  } //end
  
  function prosesCreatepkg2(){
        $username = strtolower($this->input->post('username'));
        $kodekons = $this->input->post('kodekons');
        //
        $cekkons = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kodekons]);
        if($cekkons->num_rows() == 1){
            $konstruksi = "true";
        } else {
            $cekkons2 = $this->data_model->get_byid('tb_konstruksi', ['chto'=>$kodekons]);
            if($cekkons2->num_rows() == 1){
                $konstruksi = "true";
            } else {
                $konstruksi = "false";
            }
            
        }
        if($konstruksi=="true"){
        $tgl =$this->input->post('tgl');
        $cekPkg = $this->db->query("SELECT * FROM new_tb_packinglist WHERE lokasi_now='RJS' ORDER BY id_kdlist DESC LIMIT 1");
        if($cekPkg->num_rows() == 0){
            $kdpkg = "RJS1";
        } else {
            $kode_sebelum = $cekPkg->row("kd");
            $ex = explode('S', $kode_sebelum);
            $num = intval($ex[1]) + 1;
            $kdpkg = "RJS".$num."";
        }
        $dtlist = [
            'kode_konstruksi' => $kodekons,
            'kd' => $kdpkg,
            'tanggal_dibuat' => $tgl,
            'lokasi_now' => 'RJS',
            'siap_jual' => 'n',
            'jumlah_roll' => 0,
            'ttl_panjang' => 0,
            'kepada' => 'NULL',
            'no_sj' => 'NULL',
            'ygbuat' => $username,
            'jns_fold' => 'null'
        ];
        $this->data_model->saved('new_tb_packinglist', $dtlist);
        echo json_encode(array("statusCode"=>200, "psn"=>$kdpkg));
        } else {
            echo json_encode(array("statusCode"=>404, "psn"=>'Konstruksi tidak ditemukan'));
        }
        //
  }

}
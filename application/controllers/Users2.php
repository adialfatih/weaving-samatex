<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users2 extends CI_Controller
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

  function cariAjaxKode1(){
        $kode = $this->input->post('kode');
        $cari = $this->db->query("SELECT kode_roll,loc_now FROM data_ig WHERE kode_roll LIKE '%$kode%' AND loc_now='Samatex'");
        if($cari->num_rows() == 0){
            //echo json_encode(array("statusCode"=>404, "psn"=>"Kode tidak ditemukan"));
            echo "Kode Tidak Ditemukan";
        } else {
            $ar_ig = array();
            foreach($cari->result() as $val){
                $ar_ig[] = '"'.$val->kode_roll.'"';
            }
            $im_kode = implode(',', $ar_ig);
            //echo json_encode(array("statusCode"=>200, "psn"=>$im_kode));
            echo $im_kode;
        }
  }//end

  function prosesFolGrey(){
    $koderoll = $this->input->post('koderoll');
    $ukuran = $this->input->post('ori');
    $tgl = $this->input->post('tgl');
    $operator = $this->input->post('username');
    $old_tgl = $this->input->post('old_tgl');
    $old_opt = $this->input->post('old_opt');
    $old_note = $this->input->post('old_note');
    if($old_tgl=="null"){

    } else {
        $tgl = $old_tgl;
        $operator = $old_opt;
        $this->data_model->saved('log_program', ['id_user'=>7,'log_text'=>$old_note]);
    }
    $kecilkanOperator = strtolower($operator);
    $cekusername = $this->data_model->get_byid('a_operator', ['username'=>$kecilkanOperator, 'produksi'=>'folgrey', 'dep'=>'Samatex']);
    if($cekusername->num_rows() == 1){
    $cekKodeRoll = $this->data_model->get_byid('data_ig', ['kode_roll'=>$koderoll]);
    if($cekKodeRoll->num_rows() == 1){
        $kons = $cekKodeRoll->row("konstruksi");
        $ukuran_sebelum = $cekKodeRoll->row("ukuran_ori");
        $cekKodeRollFinish = $this->data_model->get_byid('data_fol', ['kode_roll'=>$koderoll,'jns_fold'=>'Grey']);
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
                        'konstruksi' => $kons,
                        'ukuran' => $ukuran[$i],
                        'jns_fold' => 'Grey',
                        'tgl' => $tgl,
                        'operator' => $kecilkanOperator,
                        'loc' => 'Samatex',
                        'posisi' => 'Samatex',
                        'joinss' => 'false'
                    ];
                    $this->data_model->saved('data_fol',$dtlist);
                } 
            } //end for 
            //cek produksi per sm harian
            $cek1 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kons,'tgl'=>$tgl,'dep'=>'Samatex']);
            if($cek1->num_rows() == 1){
                    $id_produksi = $cek1->row("id_produksi");
                    $new_fol = floatval($cek1->row("prod_fg")) + floatval($totalOri);
                    $dtlist1 = ['prod_fg' => round($new_fol,2)];
                    $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',$dtlist1);
            } else {
                $dtlist1 = [
                    'kode_konstruksi' => $kons,
                    'tgl' => $tgl,
                    'dep' => 'Samatex',
                    'prod_ig' => 0,
                    'prod_fg' => round($totalOri,2),
                    'prod_if' => 0,
                    'prod_ff' => 0,
                    'prod_bs1' => 0,
                    'prod_bp1' => 0,
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
                    $new_fol = floatval($cek2->row("prod_fg")) + floatval($totalOri);
                    $dtlist1 = [ 'prod_fg' => round($new_fol,2) ];
                    $this->data_model->updatedata('id_prod_hr',$id_prod_hr,'data_produksi_harian',$dtlist1);
            } else {
                $dtlist1 = [
                    'tgl' => $tgl,
                    'dep' => 'Samatex',
                    'prod_ig' => 0,
                    'prod_fg' => round($totalOri,2),
                    'prod_if' => 0,
                    'prod_ff' => 0,
                    'prod_bs1' => 0,
                    'prod_bp1' => 0,
                    'prod_bs2' => 0,
                    'prod_bp2' => 0,
                    'crt' => 0
                ];
                $this->data_model->saved('data_produksi_harian', $dtlist1);
            }
            //end cek 2
            //cek produksi opt
            $cek3 = $this->data_model->get_byid('data_produksi_opt', ['username_opt'=>$kecilkanOperator,'konstruksi'=>$kons,'tgl'=>$tgl,'proses'=>'folgrey']);
            if($cek3->num_rows() == 1){
                $id_propt = $cek3->row("id_propt");
                $new_ori = floatval($cek3->row("ukuran")) + floatval($totalOri);
                $dtlist2 = [ 'ukuran' => round($new_ori,2) ];
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
                    'proses' => 'folgrey',
                    'ukuran' => round($totalOri,2), 
                    'bs' => 0,
                    'bp' => 0,
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
                    'prod_ig' => 0,
                    'prod_fg' => round($totalOri,2),
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
                $newig = floatval($cekStok->row("prod_ig")) - floatval($ukuran_sebelum);
                $newfg = floatval($cekStok->row("prod_fg")) + floatval($totalOri);
                $listStok = [
                    'prod_ig' => round($newig,2),
                    'prod_fg' => round($newfg,2)
                ];
                $this->data_model->updatedata('idstok',$idstok,'data_stok',$listStok);
            }
            //end cek 4
            $imJadi = implode('-', $jadikode);
            $text = "Kode ".$imJadi."";
            $this->data_model->updatedata('kode_roll',$koderoll,'data_ig',['loc_now'=>'fg']);
            echo json_encode(array("statusCode"=>200, "psn"=>$text));
        } else {
            $text = "Kode ".$koderoll." sudah di proses Folding.";
            echo json_encode(array("statusCode"=>404, "psn"=>$text));
        }
    } else {
        $text = "Kode ".$koderoll." tidak ditemukan.";
        echo json_encode(array("statusCode"=>404, "psn"=>$text));
    }
    //$text = implode('-', $ukuran);
    } else {
        $text = "Username tidak ditemukan owek!! (".$tgl.")";
        echo json_encode(array("statusCode"=>404, "psn"=>$text));
    }
  } //end

  function prosesFolFinish(){
    $koderoll = $this->input->post('koderoll');
    $ukuran = $this->input->post('ori');
    $tgl = $this->input->post('tgl');
    $operator = $this->input->post('username');
    $kecilkanOperator = strtolower($operator);
    $cekusername = $this->data_model->get_byid('a_operator', ['username'=>$kecilkanOperator, 'produksi'=>'folfinish', 'dep'=>'Samatex']);
    if($cekusername->num_rows() == 1){
    $cekKodeRoll = $this->data_model->get_byid('data_if', ['kode_roll'=>$koderoll]);
    if($cekKodeRoll->num_rows() == 1){
        $kons = $cekKodeRoll->row("konstruksi");
        $ukuran_sebelum = $cekKodeRoll->row("ukuran_ori");
        $cekKodeRollFinish = $this->data_model->get_byid('data_fol', ['kode_roll'=>$koderoll,'jns_fold'=>'Finish']);
        if($cekKodeRollFinish->num_rows() == 0){
            $dataBener=0; $totalOri=0; 
            $jadikode = array();
            $alphabet = ['0'=>'','1'=>'Z','2'=>'X','3'=>'V','4'=>'R','5'=>'T','6'=>'Y','7'=>'U','8'=>'P','9'=>'Q','10'=>'L'];
            for ($i=0; $i <count($ukuran) ; $i++) { 
                if($ukuran[$i]!="" AND $ukuran[$i]>0){
                    $dataBener+=1;
                    $totalOri+=floatval($ukuran[$i]);
                    $kodeRollInput = $koderoll."".$alphabet[$i];
                    $jadikode[]= $kodeRollInput;
                    $dtlist = [
                        'kode_roll' => $kodeRollInput,
                        'konstruksi' => $kons,
                        'ukuran' => $ukuran[$i],
                        'jns_fold' => 'Finish',
                        'tgl' => $tgl,
                        'operator' => $kecilkanOperator,
                        'loc' => 'Samatex',
                        'posisi' => 'Samatex',
                        'joinss' => 'false'
                    ];
                    $this->data_model->saved('data_fol',$dtlist);
                } 
            } //end for 
            //cek produksi per sm harian
            $cek1 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kons,'tgl'=>$tgl,'dep'=>'Samatex']);
            if($cek1->num_rows() == 1){
                    $id_produksi = $cek1->row("id_produksi");
                    $new_fol = floatval($cek1->row("prod_ff")) + floatval($totalOri);
                    $dtlist1 = ['prod_ff' => round($new_fol,2)];
                    $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',$dtlist1);
            } else {
                $dtlist1 = [
                    'kode_konstruksi' => $kons,
                    'tgl' => $tgl,
                    'dep' => 'Samatex',
                    'prod_ig' => 0,
                    'prod_fg' => 0,
                    'prod_if' => 0,
                    'prod_ff' => round($totalOri,2),
                    'prod_bs1' => 0,
                    'prod_bp1' => 0,
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
                    $new_fol = floatval($cek2->row("prod_ff")) + floatval($totalOri);
                    $dtlist1 = [ 'prod_ff' => round($new_fol,2) ];
                    $this->data_model->updatedata('id_prod_hr',$id_prod_hr,'data_produksi_harian',$dtlist1);
            } else {
                $dtlist1 = [
                    'tgl' => $tgl,
                    'dep' => 'Samatex',
                    'prod_ig' => 0,
                    'prod_fg' => 0,
                    'prod_if' => 0,
                    'prod_ff' => round($totalOri,2),
                    'prod_bs1' => 0,
                    'prod_bp1' => 0,
                    'prod_bs2' => 0,
                    'prod_bp2' => 0,
                    'crt' => 0
                ];
                $this->data_model->saved('data_produksi_harian', $dtlist1);
            }
            //end cek 2
            //cek produksi opt
            $cek3 = $this->data_model->get_byid('data_produksi_opt', ['username_opt'=>$kecilkanOperator,'konstruksi'=>$kons,'tgl'=>$tgl,'proses'=>'folfinish']);
            if($cek3->num_rows() == 1){
                $id_propt = $cek3->row("id_propt");
                $new_ori = floatval($cek3->row("ukuran")) + floatval($totalOri);
                $dtlist2 = [ 'ukuran' => round($new_ori,2) ];
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
                    'proses' => 'folfinish',
                    'ukuran' => round($totalOri,2), 
                    'bs' => 0,
                    'bp' => 0,
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
                    'prod_ig' => 0,
                    'prod_fg' => 0,
                    'prod_if' => 0,
                    'prod_ff' => round($totalOri,2),
                    'prod_bs1' => 0,
                    'prod_bp1' => 0,
                    'prod_bs2' => 0,
                    'prod_bp2' => 0,
                    'crt' => 0
                ];
                $this->data_model->saved('data_stok', $listStok);
            } else {
                $idstok = $cekStok->row("idstok");
                $newig = floatval($cekStok->row("prod_if")) - floatval($ukuran_sebelum);
                $newfg = floatval($cekStok->row("prod_ff")) + floatval($totalOri);
                $listStok = [
                    'prod_if' => round($newig,2),
                    'prod_ff' => round($newfg,2)
                ];
                $this->data_model->updatedata('idstok',$idstok,'data_stok',$listStok);
            }
            //end cek 4
            $imJadi = implode('-', $jadikode);
            $text = "Kode ".$imJadi."";
            $this->data_model->updatedata('kode_roll',$koderoll,'data_ig',['loc_now'=>'ff']);
            echo json_encode(array("statusCode"=>200, "psn"=>$text));
        } else {
            $text = "Kode ".$koderoll." sudah di proses Folding.";
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
  } //end

  function cariInsFinish(){
        $kode = $this->input->post('kode');
        $cekkode = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kode]);
        //if($cekkode->num_rows() == 1){
            $cek_finish = $this->data_model->get_byid('data_if', ['kode_roll'=>$kode]);
            if($cek_finish->num_rows() == 1){
                $kons = $cek_finish->row("konstruksi");
                //$mc = $cekkode->row("no_mesin");
                $ori = $cek_finish->row("ukuran_ori");
                $ori_yard = floatval($ori) / 0.9144;
                $cek_fol = $this->data_model->get_byid('data_fol',['kode_roll'=>$kode]);
                if($cek_fol->num_rows() == 0){
                    $text = "Data Inspect Finish<br>- Konstruksi &nbsp;<strong>".$kons."</strong><br>- Ukuran &nbsp;<strong>".$ori."</strong> Meter / <strong>".round($ori_yard,2)."</strong> Yard";
                    echo json_encode(array("statusCode"=>200, "psn"=>$text));
                } else {
                    $text = "<span style='color:red;'>".$kode." telah di proses folding</code>";
                    echo json_encode(array("statusCode"=>200, "psn"=>$text));
                }
            } else {
                $text = "<span style='color:red;'>".$kode." belum di proses inspect Finish</code>";
                echo json_encode(array("statusCode"=>200, "psn"=>$text));
            }
            
        // } else {
        //     echo json_encode(array("statusCode"=>404, "psn"=>"Kode tidak ditemukan"));
        // }
  } //end
  
  function prosesGabungpkg(){
        $kode1 = $this->input->post('pkgutama');
        $kode2 = $this->input->post('pkggabungan');
        $cek1 = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kode1]);
        if($cek1->num_rows() == 1){
            $sj = $cek1->row("no_sj");
            if($sj == "NULL"){
                $cek2 = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kode2]);
                if($cek2->num_rows() == 1){
                    $sj2 = $cek2->row("no_sj");
                    if($sj2 == "NULL"){
                        //update data_fol set posisi='PKT1271' WHERE posisi='PKT1252';
                        //update new_tb_isi set kd='PKT1271' WHERE kd='PKT1252';
                         $this->db->query("UPDATE data_fol SET posisi='$kode1' WHERE posisi='$kode2'");
                         $this->db->query("UPDATE new_tb_isi SET kd='$kode1' WHERE kd='$kode2'");
                        // $jumlahroll1 = $cek1->row("jumlah_roll");
                        // $jumlahroll2 = $cek2->row("jumlah_roll");
                        // $pjg1 = $cek1->row("ttl_panjang");
                        // $pjg2 = $cek2->row("ttl_panjang");
                        // $roll_baru = intval($jumlahroll1) + intval($jumlahroll2);
                        // $pjg_baru = floatval($pjg1) + floatval($pjg2);
                        // $pjg_baru2 = round($pjg_baru,2);
                        // $this->db->query("UPDATE new_tb_packinglist SET jumlah_roll='$roll_baru' AND ttl_panjang='$pjg_baru2' WHERE kd='$kode1'");
                         $this->db->query("DELETE FROM new_tb_packinglist WHERE kd='$kode2'");
                        $txt = "Berhasil merge packinglist ".$kode1."";
                        echo json_encode(array("statusCode"=>200, "psn"=>$txt));
                    } else {
                        $txt = "".$kode2." sudah berada di customer dengan No Surat Jalan ".$sj2."";
                        echo json_encode(array("statusCode"=>404, "psn"=>$txt));
                    }
                } else {
                    $txt = $kode2." tidak ditemukan";
                     echo json_encode(array("statusCode"=>404, "psn"=>$txt));
                }
            } else {
                $txt = "".$kode1." sudah berada di customer dengan No Surat Jalan ".$sj."";
                echo json_encode(array("statusCode"=>404, "psn"=>$txt));
            } 
        } else {
            $txt = $kode1." tidak ditemukan";
             echo json_encode(array("statusCode"=>404, "psn"=>"TES"));
        }
  } //end
  
  function prosesCreatepkg(){
        $namacus = strtolower($this->input->post('namacus'));
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
         $cekPkg = $this->db->query("SELECT * FROM new_tb_packinglist WHERE lokasi_now='Samatex' ORDER BY id_kdlist DESC LIMIT 1");
        if($cekPkg->num_rows() == 0){
            $kdpkg = "PKG1";
        } else {
            $kode_sebelum = $cekPkg->row("kd");
            $ex = explode('G', $kode_sebelum);
            $num = intval($ex[1]) + 1;
            $kdpkg = "PKG".$num."";
        }

        $cekkode = $this->data_model->get_byid('data_ig_code', ['idcode'=>4])->row_array();
        $cekkode_alpabet = $cekkode['alpabet'];
        $new_number = intval($cekkode['numskr']) + 1;
        $kdpkg = $cekkode_alpabet."".$new_number;

        $dtlist = [
            'kode_konstruksi' => $kodekons,
            'kd' => $kdpkg,
            'tanggal_dibuat' => $tgl,
            'lokasi_now' => 'Samatex',
            'siap_jual' => 'y',
            'jumlah_roll' => 0,
            'ttl_panjang' => 0,
            'kepada' => 'NULL',
            'no_sj' => 'NULL',
            'ygbuat' => $username,
            'jns_fold' => $username=='riziq' ? 'Grey':'Finish',
            'customer' => $namacus
        ];
        $this->data_model->saved('new_tb_packinglist', $dtlist);
        $this->data_model->updatedata('idcode',4,'data_ig_code',['numskr'=>$new_number]);
        echo json_encode(array("statusCode"=>200, "psn"=>$kdpkg));
        } else {
            echo json_encode(array("statusCode"=>404, "psn"=>'Konstruksi tidak ditemukan'));
        }
        //
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
        $cekkode = $this->data_model->get_byid('data_ig_code', ['idcode'=>3])->row_array();
        $cekkode_alpabet = $cekkode['alpabet'];
        $new_number = intval($cekkode['numskr']) + 1;
        $kdpkg = $cekkode_alpabet."".$new_number;

        $dtlist = [
            'kode_konstruksi' => $kodekons,
            'kd' => $kdpkg,
            'tanggal_dibuat' => $tgl,
            'lokasi_now' => 'Samatex',
            'siap_jual' => 'n',
            'jumlah_roll' => 0,
            'ttl_panjang' => 0,
            'kepada' => 'NULL',
            'no_sj' => 'NULL',
            'ygbuat' => $username,
            'jns_fold' => 'null'
        ];
        $this->data_model->saved('new_tb_packinglist', $dtlist);
        $this->data_model->updatedata('idcode',3,'data_ig_code',['numskr'=>$new_number]);
        echo json_encode(array("statusCode"=>200, "psn"=>$kdpkg));
        } else {
            echo json_encode(array("statusCode"=>404, "psn"=>'Konstruksi tidak ditemukan'));
        }
        //
  } //end
  function loadDataStok(){
    $stokAmbil="";
    $username =strtolower($this->input->post('username'));
    $cekusername = $this->data_model->get_byid('a_operator', ['username'=>$username, 'produksi'=>'penjualan']);
    if($cekusername->num_rows() == 1){
        if($username == "yusuf"){ $stokAmbil = "Finish"; }
        if($username == "arif"){ $stokAmbil = "Finish"; }
        if($username == "riziq"){ $stokAmbil = "Grey"; }
        if($username == "rizik"){ $stokAmbil = "Grey"; }
        if($username == "syafiq"){ $stokAmbil = "All"; }
        if($username == "8012"){ $stokAmbil = "All"; }
        if($stokAmbil!=""){
            if($stokAmbil=="All"){
                $query = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex']);
                echo "<tr><td><strong>Konstruksi</strong></td><td><strong>Stok Siap Jual</strong></td><td><strong>On Pkg</strong></td></tr>";
                foreach($query->result() as $val){
                    if($val->prod_ff > 0){
                        echo "<tr>";
                        $cekChto = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$val->kode_konstruksi])->row("chto");
                        if($cekChto=="NULL"){
                        echo "<td>".$val->kode_konstruksi."</td>"; } else {
                            echo "<td>".$cekChto."</td>";
                        }
                        if(fmod($val->prod_ff, 1) !== 0.00){
                            $pjg = number_format($val->prod_ff,2,',','.');
                        } else {
                            $pjg = number_format($val->prod_ff,0,',','.');
                        }
                        //cek dulu di packinglist
                        $cekTotalOnPkg = $this->data_model->get_byid('new_tb_packinglist', ['kode_konstruksi'=>$cekChto,'lokasi_now'=>'Samatex','siap_jual'=>'y','no_sj'=>'NULL']);
                        $jumlahOnPKG = 0;
                        foreach($cekTotalOnPkg->result() as $pe){
                            $jumlahOnPKG+=floatval($pe->ttl_panjang);
                        }
                        if(fmod($jumlahOnPKG, 1) !== 0.00){
                            $jumlahOnPKG2 = number_format($jumlahOnPKG,2,',','.');
                        } else {
                            $jumlahOnPKG2 = number_format($jumlahOnPKG,0,',','.');
                        }
                        //end cek pkglist
                        if($jumlahOnPKG == 0){
                            echo "<td>".$pjg."</td>";
                            echo "<td>-</td>";
                        } else {
                            $newPjg = floatval($val->prod_ff) - floatval($jumlahOnPKG);
                            if($newPjg < 0){ echo "<td>0</td>"; } else {
                            if(fmod($newPjg, 1) !== 0.00){
                                $pjg2 = number_format($newPjg,2,',','.');
                            } else {
                                $pjg2 = number_format($newPjg,0,',','.');
                            }
                            echo "<td>".$pjg2."</td>"; }
                            echo "<td style='color:red;'>".$jumlahOnPKG2."</td>";
                        }
                        echo "</tr>";
                    }
                    if($val->prod_fg > 0){
                        echo "<tr>";
                        echo "<td>".$val->kode_konstruksi."</td>";
                        if(fmod($val->prod_fg, 1) !== 0.00){
                            $pjg = number_format($val->prod_fg,2,',','.');
                        } else {
                            $pjg = number_format($val->prod_fg,0,',','.');
                        }
                        //cek dulu di packinglist
                        $cekTotalOnPkg = $this->data_model->get_byid('new_tb_packinglist', ['kode_konstruksi'=>$val->kode_konstruksi,'lokasi_now'=>'Samatex','siap_jual'=>'y','no_sj'=>'NULL']);
                        $jumlahOnPKG = 0;
                        foreach($cekTotalOnPkg->result() as $pe){
                            $jumlahOnPKG+=floatval($pe->ttl_panjang);
                        }
                        if(fmod($jumlahOnPKG, 1) !== 0.00){
                            $jumlahOnPKG2 = number_format($jumlahOnPKG,2,',','.');
                        } else {
                            $jumlahOnPKG2 = number_format($jumlahOnPKG,0,',','.');
                        }
                        //end cek pkglist
                        if($jumlahOnPKG == 0){
                            echo "<td>".$pjg."</td>";
                            echo "<td>-</td>";
                        } else {
                            $newPjg = floatval($val->prod_fg) - floatval($jumlahOnPKG);
                            if($newPjg < 0){ echo "<td>0</td>"; } else {
                            if(fmod($newPjg, 1) !== 0.00){
                                $pjg2 = number_format($newPjg,2,',','.');
                            } else {
                                $pjg2 = number_format($newPjg,0,',','.');
                            }
                            echo "<td>".$pjg2."</td>"; }
                            echo "<td style='color:red;'>".$jumlahOnPKG2."</td>";
                        }
                        echo "</tr>";
                    }
                }   
            }
            if($stokAmbil=="Finish"){
                $query = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex']);
                echo "<tr><td><strong>Konstruksi</strong></td><td><strong>Stok Siap Jual</strong></td><td><strong>On Pkg</strong></td></tr>";
                foreach($query->result() as $val){
                    if($val->prod_ff > 0){
                        echo "<tr>";
                        $cekChto = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$val->kode_konstruksi])->row("chto");
                        if($cekChto=="NULL"){
                        echo "<td>".$val->kode_konstruksi."</td>"; } else {
                            echo "<td>".$cekChto."</td>";
                        }
                        if(fmod($val->prod_ff, 1) !== 0.00){
                            $pjg = number_format($val->prod_ff,2,',','.');
                        } else {
                            $pjg = number_format($val->prod_ff,0,',','.');
                        }

                        //cek dulu di packinglist
                        $cekTotalOnPkg = $this->data_model->get_byid('new_tb_packinglist', ['kode_konstruksi'=>$cekChto,'lokasi_now'=>'Samatex','siap_jual'=>'y','no_sj'=>'NULL']);
                        $jumlahOnPKG = 0;
                        foreach($cekTotalOnPkg->result() as $pe){
                            $jumlahOnPKG+=floatval($pe->ttl_panjang);
                        }
                        if(fmod($jumlahOnPKG, 1) !== 0.00){
                            $jumlahOnPKG2 = number_format($jumlahOnPKG,2,',','.');
                        } else {
                            $jumlahOnPKG2 = number_format($jumlahOnPKG,0,',','.');
                        }
                        //end cek pkglist
                        if($jumlahOnPKG == 0){
                            echo "<td>".$pjg."</td>";
                            echo "<td>-</td>";
                        } else {
                            $newPjg = floatval($val->prod_ff) - floatval($jumlahOnPKG);
                            if($newPjg < 0){ echo "<td>0</td>"; } else {
                            if(fmod($newPjg, 1) !== 0.00){
                                $pjg2 = number_format($newPjg,2,',','.');
                            } else {
                                $pjg2 = number_format($newPjg,0,',','.');
                            }
                            echo "<td>".$pjg2."</td>"; }
                            echo "<td style='color:red;'>".$jumlahOnPKG2."</td>";
                        }

                        echo "</tr>";
                    }
                }   
            }
            if($stokAmbil=="Grey"){
                $query = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex']);
                echo "<tr><td><strong>Konstruksi</strong></td><td><strong>Stok Siap Jual</strong></td><td><strong>On Pkg</strong></td></tr>";
                foreach($query->result() as $val){
                    if($val->prod_fg > 0){
                        echo "<tr>";
                        echo "<td>".$val->kode_konstruksi."</td>";
                        if(fmod($val->prod_fg, 1) !== 0.00){
                            $pjg = number_format($val->prod_fg,2,',','.');
                        } else {
                            $pjg = number_format($val->prod_fg,0,',','.');
                        }
                        //cek dulu di packinglist
                        $cekTotalOnPkg = $this->data_model->get_byid('new_tb_packinglist', ['kode_konstruksi'=>$val->kode_konstruksi,'lokasi_now'=>'Samatex','siap_jual'=>'y','no_sj'=>'NULL']);
                        $jumlahOnPKG = 0;
                        foreach($cekTotalOnPkg->result() as $pe){
                            $jumlahOnPKG+=floatval($pe->ttl_panjang);
                        }
                        if(fmod($jumlahOnPKG, 1) !== 0.00){
                            $jumlahOnPKG2 = number_format($jumlahOnPKG,2,',','.');
                        } else {
                            $jumlahOnPKG2 = number_format($jumlahOnPKG,0,',','.');
                        }
                        //end cek pkglist
                        if($jumlahOnPKG == 0){
                            echo "<td>".$pjg."</td>";
                            echo "<td>-</td>";
                        } else {
                            $newPjg = floatval($val->prod_fg) - floatval($jumlahOnPKG);
                            if($newPjg < 0){ echo "<td>0</td>"; } else {
                            if(fmod($newPjg, 1) !== 0.00){
                                $pjg2 = number_format($newPjg,2,',','.');
                            } else {
                                $pjg2 = number_format($newPjg,0,',','.');
                            }
                            echo "<td>".$pjg2."</td>"; }
                            echo "<td style='color:red;'>".$jumlahOnPKG2."</td>";
                        }
                        
                        echo "</tr>";
                    }
                }   
            }
        } else {
            echo "<tr><td colspan='2'><span style='color:red;'>Tidak berhasil mengambil data stok. Anda perlu login ulang</span></td></tr>";
        }
    } else {
        echo "<tr><td colspan='2'><span style='color:red;'>Tidak berhasil mengambil data stok. Anda perlu login ulang</span></td></tr>";
    }
  } //end
  function loadpenjualan(){
        $bln = ['00'=>'undf', '01'=>'Jan', '02'=>'Feb', '03'=>'Mar', '04'=>'Apr', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Ags', '09'=>'Sep', '10'=>'Okt', '11'=>'Nov', '12'=>'Des'];
        $yr = date('Y');
        $username =strtolower($this->input->post('username'));
        $cekusername = $this->data_model->get_byid('a_operator', ['username'=>$username, 'produksi'=>'penjualan']);
        if($cekusername->num_rows() == 1){
            //$query = $this->data_model->get_byid('new_tb_packinglist', ['lokasi_now'=>'Samatex','siap_jual'=>'y','ygbuat'=>$username]);
            if($username=="syafiq" OR $username=="8012"){
                $query = $this->db->query("SELECT * FROM new_tb_packinglist WHERE lokasi_now='Samatex' AND siap_jual='y' ORDER BY id_kdlist DESC LIMIT 1000");
                if($query->num_rows() > 0){
                    echo '<tr>
                            <td>Kode Pkg</td>
                            <td>Konstruksi</td>
                            <td>Roll</td>
                            <td>Panjang</td>
                            <td>SJ</td>
                        </tr>'; 
                    foreach($query->result() as $val):
                        echo "<tr>";
                        if($val->kepada!="NULL"){ echo "<td><a href='".base_url('users/createpenjualan/'.$val->kd)."' style='text-decoration:none; color:#000000;'>".$val->kd."</a></td>"; } else {
                        echo "<td style='font-weight:bold;color:#4287f5;'><a href='".base_url('users/createpenjualan/'.$val->kd)."' style='text-decoration:none;'>".$val->kd."</a></td>"; 
                        }
                        echo "<td>".$val->kode_konstruksi."</td>";
                        echo "<td>".$val->jumlah_roll."</td>";
                        if(fmod($val->ttl_panjang, 1) !== 0.00){
                            $pjg = number_format($val->ttl_panjang,2,',','.');
                        } else {
                            $pjg = number_format($val->ttl_panjang,0,',','.');
                        }
                        echo "<td>".$pjg."</td>";
                        // $ex = explode('-', $val->tanggal_dibuat);
                        // if($ex[0]==$yr){
                        //     echo "<td>".$ex[2]." ".$bln[$ex[1]]."</td>";
                        // } else {
                        //     echo "<td>".$ex[2]." ".$bln[$ex[1]]." ".$ex[0]."</td>";
                        // }
                        if($val->no_sj == "NULL"){ echo "<td>-</td>"; } else {
                        echo "<td>".$val->no_sj."</td>"; }
                        
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
                    echo "<tr><td colspan='5'><span style='color:red;'>Anda belum membuat paket penjualan</span></td></tr>";
                }
            } else {
                $query = $this->db->query("SELECT * FROM new_tb_packinglist WHERE lokasi_now='Samatex' AND siap_jual='y' AND no_sj='NULL' AND ygbuat='$username' ORDER BY id_kdlist DESC LIMIT 300"); 
                if($query->num_rows() > 0){
                    echo '<tr>
                            <td>Kode Pkg</td>
                            <td>Konstruksi</td>
                            <td>Roll</td>
                            <td>Panjang</td>
                            <td>Tujuan</td>
                            <td>Tanggal</td>
                        </tr>'; 
                    foreach($query->result() as $val):
                        echo "<tr>";
                        if($val->kepada!="NULL"){ echo "<td><a href='".base_url('users/createpenjualan/'.$val->kd)."' style='text-decoration:none; color:#000000;'>".$val->kd."</a></td>"; } else {
                        echo "<td style='font-weight:bold;color:#4287f5;'><a href='".base_url('users/createpenjualan/'.$val->kd)."' style='text-decoration:none;'>".$val->kd."</a></td>"; 
                        }
                        echo "<td>".$val->kode_konstruksi."</td>";
                        echo "<td>".$val->jumlah_roll."</td>";
                        if(fmod($val->ttl_panjang, 1) !== 0.00){
                            $pjg = number_format($val->ttl_panjang,2,',','.');
                        } else {
                            $pjg = number_format($val->ttl_panjang,0,',','.');
                        }
                        echo "<td>".$pjg."</td>";
                        echo "<td>".$val->customer."</td>";
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
                    echo "<tr><td colspan='5'><span style='color:red;'>Anda belum membuat paket penjualan</span></td></tr>";
                }
            }
            
        } else {
            echo "<tr><td colspan='5'><span style='color:red;'>Tidak berhasil mengambil data packing penjualan. Anda perlu login ulang</span></td></tr>";
        }
  } //end

  function delisi(){
        $id_isi = $this->input->post("kode");
        $cekstatus = $this->data_model->get_byid('new_tb_isi', ['id_isi'=>$id_isi]);
        $kode = $cekstatus->row("kode");
        if($cekstatus->row("status") == "oke"){
            $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode])->row("ukuran");
            $kdpkg = $this->data_model->get_byid('new_tb_isi', ['kode'=>$kode])->row("kd");
            $pkglist = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kdpkg]);

            $jml_roll = intval($pkglist->row("jumlah_roll")) - 1;
            $ttl_pjg = floatval($pkglist->row("ttl_panjang")) - $ukuran;
            $this->data_model->updatedata('kd',$kdpkg,'new_tb_packinglist',['jumlah_roll'=>$jml_roll, 'ttl_panjang'=>round($ttl_pjg,2)]);
            $this->db->query("DELETE FROM new_tb_isi WHERE id_isi='$id_isi'");
            $this->data_model->updatedata('kode_roll',$kode,'data_fol',['posisi'=>'Samatex']);
            
            echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
        } else {
            $ukuran = $cekstatus->row("ukuran");
            $kdpkg = $cekstatus->row("kd");
            $pkglist = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kdpkg]);

            $jml_roll = intval($pkglist->row("jumlah_roll")) - 1;
            $ttl_pjg = floatval($pkglist->row("ttl_panjang")) - $ukuran;
            $this->data_model->updatedata('kd',$kdpkg,'new_tb_packinglist',['jumlah_roll'=>$jml_roll, 'ttl_panjang'=>round($ttl_pjg,2)]);
            $this->db->query("DELETE FROM new_tb_isi WHERE id_isi='$id_isi'");
                        
            echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
        }
  } //end
  
  function delisi2(){
    //$kode = $this->input->post("kode");
    $id_isi = $this->input->post("kode");
        $cekstatus = $this->data_model->get_byid('new_tb_isi', ['id_isi'=>$id_isi]);
        $kode = $cekstatus->row("kode");
    $ukuran = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kode])->row("ukuran_ori");
    $kdpkg = $this->data_model->get_byid('new_tb_isi', ['kode'=>$kode])->row("kd");
    $pkglist = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kdpkg]);

    $jml_roll = intval($pkglist->row("jumlah_roll")) - 1;
    $ttl_pjg = floatval($pkglist->row("ttl_panjang")) - $ukuran;
    $this->data_model->updatedata('kd',$kdpkg,'new_tb_packinglist',['jumlah_roll'=>$jml_roll, 'ttl_panjang'=>round($ttl_pjg,2)]);
    $this->db->query("DELETE FROM new_tb_isi WHERE id_isi='$id_isi'");
    $this->data_model->updatedata('kode_roll',$kode,'data_ig',['loc_now'=>'Samatex']);
    
    echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
} //end
function delisi256(){
    //$kode = $this->input->post("kode");
    $id_isi = $this->input->post("kode");
        $cekstatus = $this->data_model->get_byid('new_tb_isi', ['id_isi'=>$id_isi]);
        $kode = $cekstatus->row("kode");
    $ukuran = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kode])->row("ukuran_ori");
    $kdpkg = $this->data_model->get_byid('new_tb_isi', ['kode'=>$kode])->row("kd");
    $pkglist = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kdpkg]);

    $jml_roll = intval($pkglist->row("jumlah_roll")) - 1;
    $ttl_pjg = floatval($pkglist->row("ttl_panjang")) - $ukuran;
    $this->data_model->updatedata('kd',$kdpkg,'new_tb_packinglist',['jumlah_roll'=>$jml_roll, 'ttl_panjang'=>round($ttl_pjg,2)]);
    $this->db->query("DELETE FROM new_tb_isi WHERE id_isi='$id_isi'");
    $this->data_model->updatedata('kode_roll',$kode,'data_ig',['loc_now'=>'RJS']);
    
    echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
} //end

function validasi_isipaket(){
    $id = $this->input->post('id');
    $cek = $this->data_model->get_byid('new_tb_isi', ['id_isi'=>$id]);
    if($cek->num_rows() == 1){
        if($cek->row("validasi") == "valid"){
            $this->data_model->updatedata('id_isi',$id,'new_tb_isi',['validasi'=>'null']);
            echo json_encode(array("statusCode"=>200, "psn"=>"success"));
        } else {
            $this->data_model->updatedata('id_isi',$id,'new_tb_isi',['validasi'=>'valid']);
            echo json_encode(array("statusCode"=>200, "psn"=>"success"));
        }
    } else {
        echo json_encode(array("statusCode"=>400, "psn"=>"Kode Roll tidak ditemukan"));
    }
} //end

function simpanpkgoke(){
    $kd = $this->input->post("pkg");
    $cek = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kd]);
    $__jumlah_roll = $cek->row("jumlah_roll");
    $__jumlah_valid = $this->db->query("SELECT * FROM new_tb_isi WHERE kd='$kd' AND validasi='valid'")->num_rows();
    if($__jumlah_roll == $__jumlah_valid){
        echo json_encode(array("statusCode"=>200, "psn"=>"success"));
    } else {
        $blm_valid = $__jumlah_roll - $__jumlah_valid;
        $txt = "Ada ".$blm_valid." roll yang belum di validasi";
        echo json_encode(array("statusCode"=>400, "psn"=>$txt));
    }
} //end 

  function loadisipkg(){
        $kd = $this->input->post("pkg");
        $user = $this->input->post("user");
        $user2 = strtolower($user);
        $kepada = $this->db->query("SELECT * FROM new_tb_packinglist WHERE kd='$kd'")->row("kepada"); 
        //$query = $this->data_model->get_byid('new_tb_isi', ['kd'=>$kd]);
        $query = $this->db->query("SELECT * FROM new_tb_isi WHERE kd='$kd' ORDER BY id_isi DESC");
        if($query->num_rows() > 0){
            echo    '<tr>
                        <td><strong>No</strong></td>
                        <td><strong>Kode Roll</strong></td>
                        <td><strong>Ukuran</strong></td>
                        <td><strong>#</strong></td>
                        <td><strong>Del</strong></td>
                    </tr>';
            $total = 0; $jml_roll = 0;
            $jumlahowek = $query->num_rows();
            foreach($query->result() as $no => $val){
                $nos = $no+1;
                $jml_roll+=1;
                $total+=floatval($val->ukuran);
                echo "<tr>";
                echo "<td>".$jumlahowek."</td>";
                echo "<td>".$val->kode."</td>";
                echo "<td>".$val->ukuran."</td>";
                if($user2 == "syafiq"){
                    ?><td><input type="checkbox" onclick="validasi('<?=$val->id_isi;?>')" <?=$val->validasi=='valid' ? 'checked':'';?>></td><?php
                } else {
                    ?><td><input type="checkbox" <?=$val->validasi=='valid' ? 'checked':'';?>></td><?php
                }
                if($kepada=="NULL") {
                ?><td sytle="color:red;">
                    <img src="<?=base_url('assets/del.png');?>" alt="Delete" style="width:20px;" onclick="delpkg('<?=$val->id_isi;?>','<?=$val->kode;?>')">
                </td><?php
                } else {
                    echo "<td></td>";
                }
                echo "</tr>";
                $jumlahowek--;
            }
            if(fmod($total, 1) !== 0.00){
                $pjg = number_format($total,2,',','.');
            } else {
                $pjg = number_format($total,0,',','.');
            }
            echo "<tr><td colspan='2'><strong>Total</strong></td><td>".$pjg."</td><td colspan='2'></td>";
            $this->data_model->updatedata('kd',$kd,'new_tb_packinglist',['jumlah_roll'=>$jml_roll,'ttl_panjang'=>round($total,2)]);
        } else {
            echo '<tr>
                        <td><strong>No</strong></td>
                        <td><strong>Kode Roll</strong></td>
                        <td><strong>Ukuran</strong></td>
                        <td><strong>#</strong></td>
                    </tr>
                    <tr>
                        <td colspan="4">Paket masih kosong</td>
                    </tr>';
        }

  } //end
  function loadisipkg15(){
        //proses retur barang rjs
        $kode = $this->input->post('kode');
        $cek = $this->data_model->get_byid('new_tb_isi', ['kode'=>$kode])->row_array();
        if($cek['status'] == "delete"){
            $this->data_model->updatedata('kode',$kode,'new_tb_isi',['status'=>'oke']);
            $this->data_model->updatedata('kode_roll',$kode,'data_ig',['loc_now'=>'Samatex']);
        } else {
            $this->data_model->updatedata('kode',$kode,'new_tb_isi',['status'=>'delete']);
            $this->data_model->updatedata('kode_roll',$kode,'data_ig',['loc_now'=>'RJS']);
        }
        echo json_encode(array("statusCode"=>200, "psn"=>"oke"));

  } //end
  function loadisipkg13(){
    $kd = $this->input->post("pkg");
    //$query = $this->data_model->get_byid('new_tb_isi', ['kd'=>$kd]);
    $query = $this->db->query("SELECT * FROM new_tb_isi WHERE kd='$kd' ORDER BY id_isi DESC");
    if($query->num_rows() > 0){
        echo '<tr>
                    <td><strong>No</strong></td>
                    <td><strong>Kode Roll</strong></td>
                    <td><strong>Ukuran</strong></td>
                    <td><strong>MC</strong></td>
                    <td><strong>Retur</strong></td>
                </tr>';
        $total = 0; 
        foreach($query->result() as $no => $val){
            $nos = $no+1;
            $_kd = $val->kode;
            $mc = $this->db->query("SELECT kode_roll,no_mesin FROM data_ig WHERE kode_roll='$_kd'")->row("no_mesin");
            $total+=floatval($val->ukuran);
            echo "<tr>";
            echo "<td>".$nos."</td>";
            echo "<td>".$val->kode."</td>";
            echo "<td>".$val->ukuran."</td>";
            echo "<td>".strtoupper($mc)."</td>";
            ?>
            <td><input type="checkbox" style="accent-color:#e80202;" onclick="returRjs('<?=$val->kode;?>')"></td>
            <?php
            echo "</tr>";
        }
        if(fmod($total, 1) !== 0.00){
            $pjg = number_format($total,2,',','.');
        } else {
            $pjg = number_format($total,0,',','.');
        }
        echo "<tr><td colspan='2'><strong>Total</strong></td><td>".$pjg."</td><td colspan='2'></td>";
    } else {
        echo '<tr>
                    <td><strong>No</strong></td>
                    <td><strong>Kode Roll</strong></td>
                    <td><strong>Ukuran</strong></td>
                    <td><strong>MC</strong></td>
                </tr>
                <tr>
                    <td colspan="4">Paket masih kosong</td>
                </tr>';
    }

} //end
  function inputstok(){
    $kode = $this->input->post("selection");
    $kons = $this->input->post("kons");
    $pkg = $this->input->post("pkg");
    $cekpkg = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$pkg])->row_array();
    $jmlroll = $cekpkg['jumlah_roll'];
    $ttlpjg = $cekpkg['ttl_panjang'];
    if($cekpkg['siap_jual']=="y"){
        $cekkode = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode, 'posisi'=>'Samatex']);
        $siapJual = "y";
        $ukuran = $cekkode->row('ukuran');
    } else {
        $cekkode = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kode, 'loc_now'=>'Samatex']);
	$cekkode = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kode' AND loc_now IN ('Samatex','fg','FG') ");
        $siapJual = "n";
        $ukuran = $cekkode->row('ukuran_ori');
    }
    
    if($cekkode->num_rows() == 1){
        $cek_isi = $this->data_model->get_byid('new_tb_isi', ['kode'=>$kode, 'status!='=>'fixsend', 'siap_jual'=>$siapJual]);
        if($cek_isi->num_rows() == 0){
            $dtlist = [
                'kd' => $pkg,
                'konstruksi' => $kons,
                'siap_jual' => $siapJual,
                'kode' => $kode,
                'ukuran' => $ukuran,
                'status' => 'oke',
                'satuan' => $cekpkg['jns_fold']=='Finish' ? 'Yard':'Meter'
            ];
            $this->data_model->saved('new_tb_isi', $dtlist);
            if($siapJual=="y"){
            $this->data_model->updatedata('kode_roll',$kode,'data_fol',['posisi'=>$pkg]); } else {
                $this->data_model->updatedata('kode_roll',$kode,'data_ig',['loc_now'=>$pkg]);
            }
            $new_jmlroll = intval($jmlroll) + 1;
            $new_ttlpjg = floatval($ttlpjg) + floatval($ukuran);
            $this->data_model->updatedata('kd',$pkg,'new_tb_packinglist',['jumlah_roll'=>$new_jmlroll, 'ttl_panjang'=>round($new_ttlpjg,2)]);
            echo json_encode(array("statusCode"=>200, "psn"=>"Kode Roll sudah di packinglist lain"));
        } else {
            echo json_encode(array("statusCode"=>404, "psn"=>"Kode Roll sudah di packinglist"));
        }
    } else {
        echo json_encode(array("statusCode"=>404, "psn"=>"Kode Roll tidak ditemukan"));
    }
} //end

  function inputstokrjs(){
        $kode = $this->input->post("selection");
        $kons = $this->input->post("kons");
        $pkg = $this->input->post("pkg");
        $cekpkg = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$pkg])->row_array();
        $jmlroll = $cekpkg['jumlah_roll'];
        $ttlpjg = $cekpkg['ttl_panjang'];
        if($cekpkg['siap_jual']=="y"){
            $cekkode = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode, 'posisi'=>'RJS']);
            $siapJual = "y";
            $ukuran = $cekkode->row('ukuran');
        } else {
            $cekkode = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kode, 'loc_now'=>'RJS']);
            $siapJual = "n";
            $ukuran = $cekkode->row('ukuran_ori');
        }
        
        if($cekkode->num_rows() == 1){
            $cek_isi = $this->data_model->get_byid('new_tb_isi', ['kode'=>$kode]);
            if($cek_isi->num_rows() == 0){
                $dtlist = [
                    'kd' => $pkg,
                    'konstruksi' => $kons,
                    'siap_jual' => $siapJual,
                    'kode' => $kode,
                    'ukuran' => $ukuran,
                    'status' => 'oke',
                    'satuan' => $cekpkg['jns_fold']=='Finish' ? 'Yard':'Meter'
                ];
                $this->data_model->saved('new_tb_isi', $dtlist);
                if($siapJual=="y"){
                $this->data_model->updatedata('kode_roll',$kode,'data_fol',['posisi'=>$pkg]); } else {
                    $this->data_model->updatedata('kode_roll',$kode,'data_ig',['loc_now'=>$pkg]);
                }
                $new_jmlroll = intval($jmlroll) + 1;
                $new_ttlpjg = floatval($ttlpjg) + floatval($ukuran);
                $this->data_model->updatedata('kd',$pkg,'new_tb_packinglist',['jumlah_roll'=>$new_jmlroll, 'ttl_panjang'=>round($new_ttlpjg,2)]);
                echo json_encode(array("statusCode"=>200, "psn"=>"Kode Roll sudah di packinglist lain"));
            } else {
                echo json_encode(array("statusCode"=>404, "psn"=>"Kode Roll sudah di packinglist"));
            }
        } else {
            echo json_encode(array("statusCode"=>404, "psn"=>"Kode Roll tidak ditemukan"));
        }
  } //end

  function caristok(){
        $kode = $this->input->post("kode");
        $cek_kode = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kode]);
        if($cek_kode->num_rows() == 1){
            $jenis = "Grey";
            $jumlah_stok = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex','kode_konstruksi'=>$kode])->row("prod_fg");
        } else {
            $jenis = "Finish";
            $kodeKons = $this->data_model->get_byid('tb_konstruksi', ['chto'=>$kode])->row("kode_konstruksi");
            $jumlah_stok = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex','kode_konstruksi'=>$kodeKons])->row("prod_ff");
        }
        echo json_encode(array("statusCode"=>200, "jenis"=>"oke"));
  } //end

  function deletPkg(){
        $kd = $this->input->post("pkg");
        $cekisi_dulu = $this->data_model->get_byid('new_tb_isi', ['kd'=>$kd]);
        if($cekisi_dulu->num_rows() == 0){
            $this->db->query("DELETE FROM new_tb_packinglist WHERE kd='$kd'");
            echo json_encode(array("statusCode"=>200, "psn"=>""));
        } else {
            echo json_encode(array("statusCode"=>404, "psn"=>"Hapus dulu semua isi paket.!"));
        }
  } //end

  function addRollLama(){
        $kd = $this->input->post("oldKodeRoll");
        $ukr = $this->input->post("oldUkuran");
        $pkg = $this->input->post("pkg");
        $mc = $this->input->post("mc");
        $kons = $this->input->post("kons");
        $tgl = $this->input->post("tgl");
        $user = $this->input->post("user");

        if($kd!="" AND $ukr!=""){
            $cekKodediIg = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kd]);
            if($cekKodediIg->num_rows() == 0){
                $cekDiTableIsi = $this->data_model->get_byid('new_tb_isi', ['kode'=>$kd,'status!='=>'fixsend']);
                if($cekDiTableIsi->num_rows() == 0){
                    $pkgs = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$pkg])->row_array(); 
                    $dtlist = [
                        'kd' => $pkg,
                        'konstruksi' => $pkgs['kode_konstruksi'],
                        'siap_jual' => $pkgs['siap_jual'],
                        'kode' => $kd,
                        'ukuran' => $ukr,
                        'status' => 'oke',
                        'satuan' => 'Meter'
                    ];
                    $this->data_model->saved('new_tb_isi', $dtlist);
                    $newRoll = intval($pkgs['jumlah_roll']) + 1;
                    $newPanj = floatval($pkgs['ttl_panjang']) + floatval($ukr);
                    $this->data_model->updatedata('kd',$pkg,'new_tb_packinglist',['jumlah_roll'=>$newRoll,'ttl_panjang'=>round($newPanj,2)]);
                    $dtlist2 = [
                        'kode_roll' => $kd,
                        'konstruksi' => $pkgs['kode_konstruksi'],
                        'no_mesin' => $mc,
                        'no_beam' => 'n',
                        'oka' => 'n',
                        'ukuran_ori' => $ukr,
                        'ukuran_bs' => '0',
                        'ukuran_bp' => '0',
                        'tanggal' => $tgl,
                        'operator' => $user,
                        'bp_can_join' => $ukr<50 ? 'true':'false',
                        'dep' => 'Samatex',
                        'loc_now' => $pkg,
                        'yg_input' => 'n',
                        'kode_upload' => 'tes'
                    ];
                    //$this->data_model->saved('data_ig', $dtlist2);
                    $this->data_model->saved('data_ig', $dtlist2);
                    $stok = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex', 'kode_konstruksi'=>$kons]);
                    $idstok = $stok->row("idstok");
                    $newig = floatval($stok->row("prod_ig")) + floatval($ukr);
                    $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($newig,2)]);
                    echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
                } else {
                    echo json_encode(array("statusCode"=>404, "psn"=>"Kode sudah ada dipaket"));
                }
            } else {
                echo json_encode(array("statusCode"=>404, "psn"=>"Kode sudah digunakan"));
            }
            
        } else {
            echo json_encode(array("statusCode"=>404, "psn"=>"Kode dan Ukuran harus di isi"));
        }
  }

  function prosesJoinPieces(){
        $ukuranJoin = $this->input->post('ukuranJoin');
        $kons = $this->input->post('kons');
        $kode = $this->input->post('koder');
        $ukra = $this->input->post('ukra');
        $str = $this->input->post('st');
        $username = $this->input->post('username');
        $tgl = $this->input->post('tgl');
        $exxr = explode(',',$kode);
        $st = explode(',',$str);
        $jml = count($exxr);
        //$joinFrom = implode(', ',$kode);
        for ($i=0; $i < $jml; $i++) { 
            if($st[$i] == "fromdata"){
                $cekStok = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex','kode_konstruksi'=>$kons]);
                $stokif = floatval($cekStok->row("prod_if")) - floatval($ukra[$i]);
                $idstok = $cekStok->row("idstok");
                $this->data_model->updatedata('idstok',$idstok,'data_stok', ['prod_if'=>round($stokif,2)]);
                //harusnya mengubah status di data_if ket diganti dengan wasjoin biar gak bisa di input lagi
            }
        }
            $cekKodeJoin = $this->data_model->get_byid('data_fol', ['joinss'=>'true']);
            $cekKodeJoin = $this->db->query("SELECT * FROM data_fol WHERE joinss='true' ORDER BY id_fol DESC LIMIT 1");
            if($cekKodeJoin->num_rows() == 0){
                $kodeJoin = "JP1";
            } else {
                $ex = explode('P', $cekKodeJoin->row("kode_roll"));
                $num = intval($ex[1]) + 1;
                $kodeJoin = "JP".$num."";
            }
            $dtfol = [
                'kode_roll' => $kodeJoin,
                'konstruksi' => $kons,
                'ukuran' => $ukuranJoin,
                'jns_fold' => 'Finish',
                'tgl' => $tgl,
                'operator' => strtolower($username),
                'loc' => 'Samatex',
                'posisi' => 'Samatex',
                'joinss' => 'true',
                'joindfrom' => $kode,
            ];
            $this->data_model->saved('data_fol', $dtfol);
            //cek produksi per sm harian
            $cek1 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kons,'tgl'=>$tgl,'dep'=>'Samatex']);
            if($cek1->num_rows() == 1){
                    $id_produksi = $cek1->row("id_produksi");
                    $new_fol = floatval($cek1->row("prod_ff")) + floatval($ukuranJoin);
                    $dtlist1 = ['prod_ff' => round($new_fol,2)];
                    $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',$dtlist1);
            } else {
                $dtlist1 = [
                    'kode_konstruksi' => $kons,
                    'tgl' => $tgl,
                    'dep' => 'Samatex',
                    'prod_ig' => 0,
                    'prod_fg' => 0,
                    'prod_if' => 0,
                    'prod_ff' => round($ukuranJoin,2),
                    'prod_bs1' => 0,
                    'prod_bp1' => 0,
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
                    $new_fol = floatval($cek2->row("prod_ff")) + floatval($ukuranJoin);
                    $dtlist1 = [ 'prod_ff' => round($new_fol,2) ];
                    $this->data_model->updatedata('id_prod_hr',$id_prod_hr,'data_produksi_harian',$dtlist1);
            } else {
                $dtlist1 = [
                    'tgl' => $tgl,
                    'dep' => 'Samatex',
                    'prod_ig' => 0,
                    'prod_fg' => 0,
                    'prod_if' => 0,
                    'prod_ff' => round($ukuranJoin,2),
                    'prod_bs1' => 0,
                    'prod_bp1' => 0,
                    'prod_bs2' => 0,
                    'prod_bp2' => 0,
                    'crt' => 0
                ];
                $this->data_model->saved('data_produksi_harian', $dtlist1);
            }
            //end cek 2
            //cek produksi opt
            $cek3 = $this->data_model->get_byid('data_produksi_opt', ['username_opt'=>strtolower($username),'konstruksi'=>$kons,'tgl'=>$tgl,'proses'=>'folfinish']);
            if($cek3->num_rows() == 1){
                $id_propt = $cek3->row("id_propt");
                $new_ori = floatval($cek3->row("ukuran")) + floatval($ukuranJoin);
                $dtlist2 = [ 'ukuran' => round($new_ori,2) ];
                $this->data_model->updatedata('id_propt',$id_propt,'data_produksi_opt',$dtlist2);
            } else {
                $jamSaatIni = date('H');
                if ($jamSaatIni >= 14) {
                $shift = "2";
                } else {
                $shift = "1";
                }
                $dtlist2 = [
                    'username_opt' => strtolower($username),
                    'konstruksi' => $kons,
                    'tgl' => $tgl,
                    'proses' => 'folfinish',
                    'ukuran' => round($ukuranJoin,2), 
                    'bs' => 0,
                    'bp' => 0,
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
                    'prod_ig' => 0,
                    'prod_fg' => 0,
                    'prod_if' => 0,
                    'prod_ff' => round($ukuranJoin,2),
                    'prod_bs1' => 0,
                    'prod_bp1' => 0,
                    'prod_bs2' => 0,
                    'prod_bp2' => 0,
                    'crt' => 0
                ];
                $this->data_model->saved('data_stok', $listStok);
            } else {
                $idstok = $cekStok->row("idstok");
                $newfg = floatval($cekStok->row("prod_ff")) + floatval($ukuranJoin);
                $listStok = [
                    'prod_ff' => round($newfg,2)
                ];
                $this->data_model->updatedata('idstok',$idstok,'data_stok',$listStok);
            }
            echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
  } //end

  function cekstokfolding(){
        $kons = $this->input->post('kons');
        $jns = $this->input->post('jns');
        $cekstok = $this->data_model->get_byid('data_fol', ['konstruksi'=>$kons,'jns_fold'=>$jns, 'posisi'=>'Samatex']);
        if($cekstok->num_rows() > 0){
            $jml = $cekstok->num_rows();
            $total1 = $this->db->query("SELECT SUM(ukuran) as ukr FROM data_fol WHERE konstruksi='$kons' AND jns_fold='$jns' AND posisi='Samatex'")->row("ukr");
            $total = round($total1,2);
            echo json_encode(array("statusCode"=>200, "jmlroll"=>$jml, "totalpanjang"=>$total));
        } else {
            echo json_encode(array("statusCode"=>400, "psn"=>"Stok tidak ditemukan atau sudah habis"));
        }
  } //end

  function prosdelfol(){
        $kons = $this->input->post('kons');
        $jns = $this->input->post('jns');
        $alasan = $this->input->post('alasan');
        $hpsall = $this->input->post('hpsall');
        $send = $this->input->post('sendpst');
        $user = $this->input->post('id_username');
        $tgl = $this->input->post('id_tgl');
        $kodeHapus = $this->data_model->acakKode(15);
        $dtlist = [
            'konstruksi' => strtoupper($kons),
            'jns' => $jns,
            'alasan_hapus' => $alasan,
            'hpsall' => $hpsall,
            'krm_pstx' => $send,
            'tgl_hps' => $tgl,
            'yg_hapus' => $user,
            'kode_hapus' => $kodeHapus, 
            'timestapms' => date('Y-m-d H:i:s')
        ];
        $this->data_model->saved('a_hps_stok', $dtlist);
        echo json_encode(array("statusCode"=>200, "kodeHapus"=>$kodeHapus));
  } //end

  function reloadfol(){
        $kd = $this->input->post('kd');
        $kddt = $this->data_model->get_byid('a_hps_code', ['kode_hapus'=>$kd]);
        echo '<tr>
            <td><strong>No</strong></td>
            <td><strong>Kode Roll</strong></td>
            <td><strong>Konstruksi</strong></td>
            <td><strong>Ukuran</strong></td>
            <td><strong>Folding</strong></td>
            <td><strong>Batal</strong></td>
        </tr>';
        $no=1;
        $total=0;
        foreach($kddt->result() as $val){
            echo "<tr>";
            echo "<td>".$no."</td>";
            echo "<td>".$val->kode_roll."</td>";
            echo "<td>".$val->kons."</td>";
            echo "<td>".$val->ukuran."</td>";
            echo "<td>".$val->jns_fold."</td>";
            echo "<td>Batal</td>";
            echo "</tr>";
            $total+=$val->ukuran;
            $no++;
        }
        echo "<tr>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td>".round($total,2)."</td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<tr>";
  } //end



}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prosesajax extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
  }
   
  function index(){
      echo "Not Index...";
  }

  function tesajax(){
    $kd = $this->input->post('kd');
    $kode = $this->data_model->get_byid('new_tb_pkg_list', ['no_roll'=>$kd]);
    if($kode->num_rows() == 1){
        echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
    } else {
        echo json_encode(array("statusCode"=>404, "psn"=>"failde"));
    }
    
  }//end

  function ceksuratjalan(){
      $txt = $this->input->post('txt');
      $dep = $this->input->post('dep');
      $kodep="null";
      if($dep=="RJS"){ $kodep ="R"; }
      if($dep=="Samatex"){ $kodep ="S"; }
      if($dep=="Pusatex"){ $kodep ="P"; }
      $ceksj = $this->data_model->get_byid('new_tb_suratjalan', ['departement'=>$dep,'tujuan_kirim'=>$txt]);
      if($ceksj->num_rows()==0){
          if($txt=="cus"){ $no_sj = "0001/SELL/$kodep/23"; } else { $no_sj = "0001/GDG/$kodep/23"; }
      } else {
          $qu = $this->db->query("SELECT * FROM new_tb_suratjalan WHERE tujuan_kirim='$txt' ORDER BY id_sj DESC LIMIT 1")->row("numering");
          $new_num = intval($qu) + 1;
          if($txt=="cus"){
              $no_sj = sprintf("%04d", $new_num)."/SELL/$kodep/23";
          } else {
              $no_sj = sprintf("%04d", $new_num)."/GDG/$kodep/23";
          }
      }
      echo json_encode(array("statusCode"=>200, "psn"=>$no_sj));
  }

  function cekCustomer(){
    $txt = $this->input->post('txt');
    $cek_nama = $this->data_model->get_byid('dt_konsumen', ['nama_konsumen'=>$txt]);
    if($cek_nama->num_rows() == 1){
        $id = $cek_nama->row("id_konsumen");
        $no = $cek_nama->row("no_hp");
        $al = $cek_nama->row("alamat");
        echo json_encode(array("statusCode"=>200, "psn"=>"oke", "id"=>$id, "nohp"=>$no, "alm"=>$al));
    } else {
        echo json_encode(array("statusCode"=>404, "psn"=>"kosong"));
    }
    //echo json_encode(array("statusCode"=>200, "psn"=>"kosong"));
    
  }//end

  function cekKodeRoll(){
      $kdrol = $this->input->post('txt');
      $cek_nama = $this->data_model->get_byid('new_tb_pkg_list', ['no_roll'=>$kdrol]);
      if($cek_nama->num_rows() == 1){
          $ig = $cek_nama->row("ukuran_ori");
          $noroll = $cek_nama->row("no_roll");
          $kdp = $cek_nama->row("kode_produksi");
          $kdkons = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kdp])->row("kode_konstruksi");
          $cek_if = $this->data_model->get_byid('new_tb_pkg_ins', ['no_roll'=>$kdrol]);
          if($cek_if->num_rows()==1){
            $ukuran_if = $cek_if->row("ukuran_ori_yard")." y";
          } else {
            $ukuran_if = '-';
          }
          $cek_fol = $this->data_model->get_byid('new_tb_pkg_fol', ['no_roll'=>$kdrol]);
          if($cek_fol->num_rows()==1){
            if($cek_fol->row("st_folding") == "Grey"){
              $fol = $cek_fol->row("ukuran")." m";
            } else {
              $fol = $cek_fol->row("ukuran_yard")." y";
            }
          } else {
            $fol = '-';
          }
          echo json_encode(array("statusCode"=>200, "psn"=>"oke", "ig"=>$ig, "kdkons"=>$kdkons, "uif"=>$ukuran_if, "ufol"=>$fol, "noroll"=>$noroll));
      } else {
          echo json_encode(array("statusCode"=>404, "psn"=>"kosong"));
      }
  } // 

  function simpanSplit(){
     $kdrol = $this->input->post('kd1');
     $kdsplit = $this->input->post('kd2');
     $prosname = $this->input->post('prosname');
     $ukuran = $this->input->post('ukuran');
     if($prosname=="IFF"){
        $satuan = "Yard";
        $ukuran_yard = floatval($ukuran);
        $ukuran_meter = $ukuran_yard * 0.9144;
     } elseif($prosname=="FG"){
        $satuan = "Meter";
        $ukuran_meter = floatval($ukuran);
        $ukuran_yard = $ukuran_meter / 0.9144;
     } elseif($prosname=="FF"){
        $satuan = "Yard";
        $ukuran_yard = floatval($ukuran);
        $ukuran_meter = $ukuran_yard * 0.9144;
     }
     $tgl = $this->input->post('tgl');
     
     $opr = $this->input->post('opr');
     $dt = $this->data_model->get_byid('new_tb_pkg_list', ['no_roll'=>$kdrol]);
     $kode_konstruksi = $this->data_model->get_byid('tb_produksi',['kode_produksi'=>$dt->row('kode_produksi')])->row("kode_konstruksi");
     $cek = $this->data_model->get_byid('new_tb_pkg_list', ['no_roll'=>$kdsplit]);
     if($cek->num_rows()==0){
        // $dtlist = [
        //     'kode_produksi' => $dt->row('kode_produksi'),
        //     'no_roll' => $kdsplit,
        //     'no_mesin' => $dt->row('no_mesin'),
        //     'no_beam' => $dt->row('no_beam'),
        //     'ukuran_ori' => round($ukuran_meter,2),
        //     'ukuran_b' => 0,
        //     'ukuran_c' => 0,
        //     'ukuran_bs' => 0,
        //     'ukuran_now' => round($ukuran_meter,2),
        //     'operator' => $opr,
        //     'st_pkg' => 'IG',
        //     'satuan' => $satuan,
        //     'ukuran_ori_yard' => round($ukuran_yard,2),
        //     'ukuran_b_yard' => 0,
        //     'ukuran_c_yard' => 0,
        //     'ukuran_bs_yard' => 0,
        //     'ukuran_now_yard' => round($ukuran_yard,2),
        //     'oka' => $dt->row('oka')
        // ];
        // $this->data_model->saved('new_tb_pkg_list', $dtlist);
        if($prosname=="IFF"){
          $list_if = [
            'id_pkg' => 0,
            'kode_produksi' => $dt->row('kode_produksi'),
            'no_roll' => $kdsplit,
            'ukuran_ori' => round($ukuran_meter,2),
            'ukuran_a' => 0,
            'ukuran_b' => 0,
            'ukuran_c' => 0,
            'ukuran_bs' => 0,
            'ukuran_now' => round($ukuran_meter,2),
            'operator' => $opr,
            'satuan' => 'Yard',
            'tgl' => $tgl,
            'ukuran_ori_yard' => round($ukuran_yard,2),
            'ukuran_a_yard' => 0,
            'ukuran_b_yard' => 0,
            'ukuran_c_yard' => 0,
            'ukuran_bs_yard' => 0,
            'ukuran_now_yard' => round($ukuran_yard,2)
          ];
          $this->data_model->saved('new_tb_pkg_ins', $list_if);
        } elseif($prosname=="FG"){
          $list_if = [
            'kode_produksi' => $dt->row('kode_produksi'),
            'asal' => 'null',
            'id_asal' => 0,
            'no_roll' => $kdsplit,
            'tgl' => $tgl,
            'ukuran' => round($ukuran_meter,2),
            'operator' => $opr,
            'st_folding' => 'Grey',
            'ukuran_now' => round($ukuran_meter,2),
            'ukuran_yard' => round($ukuran_yard,2),
            'ukuran_now_yard' => round($ukuran_yard,2),
            'id_effected_row' => '0'
          ];
          $this->data_model->saved('new_tb_pkg_fol', $list_if);
        } elseif($prosname=="FF"){
          $list_if = [
            'kode_produksi' => $dt->row('kode_produksi'),
            'asal' => 'null',
            'id_asal' => 0,
            'no_roll' => $kdsplit,
            'tgl' => $tgl,
            'ukuran' => round($ukuran_meter,2),
            'operator' => $opr,
            'st_folding' => 'Finish',
            'ukuran_now' => round($ukuran_meter,2),
            'ukuran_yard' => round($ukuran_yard,2),
            'ukuran_now_yard' => round($ukuran_yard,2),
            'id_effected_row' => '0'
          ];
          $this->data_model->saved('new_tb_pkg_fol', $list_if);
        }
        $loc = $this->session->userdata('departement');
        $cek_hr = $this->data_model->get_byid('report_produksi_harian',['kode_konstruksi'=>$kode_konstruksi,'lokasi_produksi'=>$loc,'waktu'=>$tgl]);
                if($cek_hr->num_rows()==0){
                    $rptd_list = [
                        'kode_konstruksi' => $kode_konstruksi,
                        'ins_grey' => 0,
                        'ins_finish' => $prosname=='IFF' ? round($ukuran_meter,2):'0',
                        'fol_grey' => $prosname=='FG' ? round($ukuran_meter,2):'0',
                        'fol_finish' => $prosname=='FF' ? round($ukuran_meter,2):'0',
                        'lokasi_produksi' => $loc,
                        'waktu' => $tgl,
                        'terjual' => 0,
                        'bs' => 0,
                        'ins_grey_yard' => 0,
                        'ins_finish_yard' => $prosname=='IFF' ? round($ukuran_yard,2):'0',
                        'fol_grey_yard' => $prosname=='FG' ? round($ukuran_yard,2):'0',
                        'fol_finish_yard' => $prosname=='FF' ? round($ukuran_yard,2):'0',
                        'terjual_yard' => 0,
                        'bs_yard' =>0,
                        'crt' => 0,
                        'crt_yard' => 0,
                        'bp' => 0,
                        'bo_yard' => 0
                    ];
                    $this->data_model->saved('report_produksi_harian',$rptd_list);
                } else {
                    $id_rptd = $cek_hr->row("id_rptd");
                    if($prosname=="IFF"){
                      $insf = floatval($cek_hr->row("ins_finish")) + $ukuran_meter;
                      $insfy = floatval($cek_hr->row("ins_finish_yard")) + $ukuran_yard;
                      $insg = floatval($cek_hr->row("ins_grey")) - $ukuran_meter;
                      $insgy = floatval($cek_hr->row("ins_grey_yard")) - $ukuran_yard;
                      $uprptd = [
                        'ins_finish' => round($insf,2),
                        'ins_finish_yard' => round($insfy,2),
                        'ins_grey' => round($insg,2),
                        'ins_grey_yard' => round($insgy,2)
                      ];
                    } elseif($prosname=="FG") {
                      $folg = floatval($cek_hr->row("fol_grey")) + $ukuran_meter;
                      $folgy = floatval($cek_hr->row("fol_grey_yard")) + $ukuran_yard;
                      $insg = floatval($cek_hr->row("ins_grey")) - $ukuran_meter;
                      $insgy = floatval($cek_hr->row("ins_grey_yard")) - $ukuran_yard;
                      $uprptd = [
                        'fol_grey' => round($folg,2),
                        'fol_grey_yard' => round($folgy,2),
                        'ins_grey' => round($insg,2),
                        'ins_grey_yard' => round($insgy,2)
                      ];
                    } elseif($prosname=="FF") {
                      $folf = floatval($cek_hr->row("fol_finish")) + $ukuran_meter;
                      $folfy = floatval($cek_hr->row("fol_finish_yard")) + $ukuran_yard;
                      $insf = floatval($cek_hr->row("ins_finish")) - $ukuran_meter;
                      $insfy = floatval($cek_hr->row("ins_finish_yard")) - $ukuran_yard;
                      $uprptd = [
                        'fol_finish' => round($folf,2),
                        'fol_finish_yard' => round($folfy,2),
                        'ins_finish' => round($insf,2),
                        'ins_finish_yard' => round($insfy,2)
                      ];
                    }
                    $this->data_model->updatedata('id_rptd',$id_rptd,'report_produksi_harian',$uprptd);
                }
                $cek_stok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kode_konstruksi, 'departement'=>$loc]);
                if($cek_stok->num_rows()==1){
                    $id_stok = $cek_stok->row("id_stok");
                    if($prosname=="IFF"){
                      $insf = floatval($cek_hr->row("stok_ins_finish")) + $ukuran_meter;
                      $insfy = floatval($cek_hr->row("stok_ins_finish_yard")) + $ukuran_yard;
                      $insg = floatval($cek_hr->row("stok_ins")) - $ukuran_meter;
                      $insgy = floatval($cek_hr->row("stok_ins_yard")) - $ukuran_yard;
                      $uprptd = [
                        'stok_ins_finish' => round($insf,2),
                        'stok_ins_finish_yard' => round($insfy,2),
                        'stok_ins' => round($insg,2),
                        'stok_ins_yard' => round($insgy,2)
                      ];
                    } elseif($prosname=="FG") {
                      $folg = floatval($cek_hr->row("stok_fol")) + $ukuran_meter;
                      $folgy = floatval($cek_hr->row("stok_fol_yard")) + $ukuran_yard;
                      $insg = floatval($cek_hr->row("stok_ins")) - $ukuran_meter;
                      $insgy = floatval($cek_hr->row("stok_ins_yard")) - $ukuran_yard;
                      $uprptd = [
                        'stok_fol' => round($folg,2),
                        'stok_fol_yard' => round($folgy,2),
                        'stok_ins' => round($insg,2),
                        'stok_ins_yard' => round($insgy,2)
                      ];
                    } elseif($prosname=="FF") {
                      $folf = floatval($cek_hr->row("stok_fol_finish")) + $ukuran_meter;
                      $folfy = floatval($cek_hr->row("stok_fol_finish_yard")) + $ukuran_yard;
                      $insf = floatval($cek_hr->row("stok_ins_finish")) - $ukuran_meter;
                      $insfy = floatval($cek_hr->row("stok_ins_finish_yard")) - $ukuran_yard;
                      $uprptd = [
                        'stok_fol_finish' => round($folf,2),
                        'stok_fol_finish_yard' => round($folfy,2),
                        'stok_ins_finish' => round($insf,2),
                        'stok_ins_finish_yard' => round($insfy,2)
                      ];
                    }
                    $this->data_model->updatedata('id_stok',$id_stok,'report_stok',$uprptd);
                } else {
                    if($kd_konstruksi!=""){
                    $stok_list = [
                        'kode_konstruksi' => $kode_konstruksi,
                        'stok_ins' => 0,
                        'stok_ins_finish' => $prosname=='IFF' ? round($ukuran_meter,2):'0',
                        'stok_fol' => $prosname=='FG' ? round($ukuran_meter,2):'0',
                        'stok_fol_finish' => $prosname=='FF' ? round($ukuran_meter,2):'0',
                        'terjual' => 0,
                        'bs' => 0,
                        'retur' => 0,
                        'departement' => $loc,
                        'stok_ins_yard' => 0,
                        'stok_ins_finish_yard' => $prosname=='IFF' ? round($ukuran_yard,2):'0',
                        'stok_fol_yard' => $prosname=='FG' ? round($ukuran_yard,2):'0',
                        'stok_fol_finish_yard' => $prosname=='FF' ? round($ukuran_yard,2):'0',
                        'terjual_yard' => 0,
                        'bs_yard' => 0,
                        'retur_yard' => 0,
                        'crt' => 0,
                        'crt_yard' => 0,
                        'bp' => 0,
                        'bo_yard' => 0
                    ];
                    $this->data_model->saved('report_stok', $stok_list); }
                }
        echo json_encode(array("statusCode"=>200, "psn"=>"sukses"));
     } else {
       $txt = "Kode ".$kdsplit." telah digunakan";
       echo json_encode(array("statusCode"=>404, "psn"=>$txt));
     }
  } //end

  function cekkoderollbaru(){
      $kdrol = $this->input->post('txt');
      $cek = $this->data_model->get_byid('new_tb_pkg_list', ['no_roll'=>$kdrol]);
      if($cek->num_rows() == 0){
        echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
      } else {
        echo json_encode(array("statusCode"=>404, "psn"=>"oke"));
      }
      
  } //end

  function uncekpkg(){
      $kode = $this->input->post('kode');
      $token = $this->input->post('token');
      $cek = $this->data_model->get_byid('data_fol_lama', ['kode_roll'=>$kode]);
      if($cek->row("lokasi") == $token){
          $this->data_model->updatedata('kode_roll',$kode,'data_fol_lama',['lokasi'=>'Samatex']);
      } else {
          $this->data_model->updatedata('kode_roll',$kode,'data_fol_lama',['lokasi'=>$token]);
      }
      echo "oke";
  } //end
  function uncekpkg2(){
      $kode = $this->input->post('kode');
      $token = $this->input->post('token');
      $cek = $this->data_model->get_byid('data_stok_lama', ['id_sl'=>$kode]);
      if($cek->row("posisi") == $token){
          $this->data_model->updatedata('id_sl',$kode,'data_stok_lama',['posisi'=>'Samatex']);
      } else {
          $this->data_model->updatedata('id_sl',$kode,'data_stok_lama',['posisi'=>$token]);
      }
      echo "oke";
  } //end
  function uncekpkg45(){
      $kode = $this->input->post('kode');
      $token = $this->input->post('token');
      $cek = $this->data_model->get_byid('new_tb_isi', ['id_isi'=>$kode]);
      if($cek->row("kd") == $token){
          $this->data_model->updatedata('id_isi',$kode,'new_tb_isi',['kd'=>'null']);
      } else {
          $this->data_model->updatedata('id_isi',$kode,'new_tb_isi',['kd'=>$token]);
      }
      echo "oke";
  } //end

  function cekSaldoAwal(){
      $idcus = $this->input->post('id');
      $no_sj = "SLD/AWL/".$idcus."";
      $cekNota = $this->data_model->get_byid('a_nota', ['no_sj'=>$no_sj]);
      if($cekNota->num_rows() == 0){
          echo json_encode(array("statusCode"=>500, "psn"=>"none"));
      } elseif($cekNota->num_rows() == 1){
          $total_harga = $cekNota->row("total_harga");
          $idnota = $cekNota->row("id_nota");
          echo json_encode(array("statusCode"=>200, "psn"=>"oke", "idnota"=>$idnota, "total"=>$total_harga));
      } else {
          echo json_encode(array("statusCode"=>404, "psn"=>"Erorr.. Hubungi Developer"));
      }
  } //end

  function inputSaldoAwal(){
      $idcus = $this->input->post('ididcus');
      $saldo = $this->input->post('saldoawal');
      $mysaldo = str_replace(array(',', ' '), '', $saldo);
      $no_sj = "SLD/AWL/".$idcus."";
      $cekNota = $this->data_model->get_byid('a_nota', ['no_sj'=>$no_sj]);
      if($cekNota->num_rows() == 0){
          $dtlist = [
            'no_sj' => $no_sj,
            'kd' => 'null',
            'konstruksi' => 'null',
            'jml_roll' => 0,
            'total_panjang' => 0,
            'harga_satuan' => 0,
            'total_harga' => $mysaldo,
            'tgl_nota' => '2023-07-31',
            'pembuat_nota' => $this->session->userdata('id')
          ];
          $this->data_model->saved('a_nota', $dtlist);
          $sjlist = [
            'no_sj' => $no_sj,
            'dep_asal' => 'Samatex',
            'tujuan_kirim' => 'Samatex',
            'tgl_kirim' => '2023-07-31',
            'id_user' => $this->session->userdata('id'),
            'id_customer' => $idcus,
            'create_nota' => 'y'
          ];
          $this->data_model->saved('surat_jalan', $sjlist);
          redirect(base_url('saldo-piutang'));
      } elseif($cekNota->num_rows() == 1) {
          $this->data_model->updatedata('no_sj',$no_sj,'a_nota',['total_harga'=>$mysaldo]);
          redirect(base_url('saldo-piutang'));
      } else {
          echo "Erorr Input Saldo awal";
      }
  }

  function masukanKodeKePkg(){
      $proses = $this->input->post('proses');
      $koderol = $this->input->post('kode');
      $ukuran = $this->input->post('ukuran');
      $kons = $this->input->post('kons');
      $pkg = $this->input->post('pkg');
      $jns = $this->input->post('jns');
      $satuan = $this->input->post('satuan');
      if($proses=="1"){
          $dtlist = [
            'kd' => $pkg,
            'konstruksi' => $kons,
            'siap_jual' => $jns,
            'kode' => $koderol,
            'ukuran' => $ukuran,
            'status' => 'oke',
            'satuan' => $satuan
          ];
          $this->data_model->saved('new_tb_isi', $dtlist);
          echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
      }
      if($proses=="2"){
          $this->db->query("DELETE FROM new_tb_isi WHERE kode='$koderol'");
          echo json_encode(array("statusCode"=>404, "psn"=>"oke"));
      }

  } //end

  function simpanrollstx($no){
      $jmlroll = $this->data_model->get_byid('new_tb_isi', ['kd'=>$no])->num_rows();
      $roll = $this->data_model->get_byid('new_tb_isi', ['kd'=>$no]);
      $totalPanjang = $this->db->query("SELECT SUM(ukuran) as ukr FROM new_tb_isi WHERE kd='$no'")->row("ukr");
      $this->data_model->updatedata('kd',$no,'new_tb_packinglist', ['jumlah_roll'=>$jmlroll, 'ttl_panjang'=>round($totalPanjang,2)]);
      foreach ($roll->result() as $key => $value) {
          $this->data_model->updatedata('kode_roll', $value->kode, 'data_ig', ['loc_now'=>$no]);
      }
      redirect(base_url('packing-list'));
  } //end

}
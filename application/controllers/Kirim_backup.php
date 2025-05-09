<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kirim extends CI_Controller
{
    function __construct()
    {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
      if($this->session->userdata('login_form') != "rindangjati_sess"){
		  redirect(base_url("login"));
	  }
    }
   
  function index(){
       echo 'Invalid token';
  } //end

  function customer($token){
     $cek = $this->data_model->get_byid('new_tb_packinglist', ['sha1(kd)'=>$token,'lokasi_now!='=>'cus']);
     $kd = $cek->row("kd");
     if($cek->num_rows() == 1){
        $data = array(
            'title' => 'Penjualan Packing list ('.$kd.')',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_hak' => $this->session->userdata('hak'),
            'sess_hak' => $this->session->userdata('hak'),
            'sess_dep' =>$this->session->userdata('departement'),
            'token' => $kd,
            'dtkn' => $cek->row_array()
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('new_page/jual_pkg_list', $data);
        $this->load->view('part/main_js');
     } else {
        echo "Invalid token";
     }
  } //end

  function kepabrik(){
    $tglNow = date('Y-m-d');
    $kd = $this->input->post('kd');
    $locnow = $this->input->post('locnow');
    $tujuan = $this->input->post('loctuj');
    if($locnow!=$tujuan){
        $this->data_model->updatedata('kd',$kd,'new_tb_packinglist',['lokasi_now'=>'ongoing']);
        $dtlist = [ 'kd' => $kd, 'kirim_dari' => $locnow, 'kirim_ke' => $tujuan, 'tgl_kirim' => $tglNow, 'verf_penerima' => 'n' ];
        $this->data_model->saved('new_tb_trackpaket',$dtlist);
        $this->session->set_flashdata('announce', 'Pengiriman sukses. Menunggu verifikasi dari <strong>'.$tujuan.'</strong>');
        redirect(base_url('packing-list'));
    } else {
        $this->session->set_flashdata('gagal', 'Anda tidak bisa memilih lokasi tujuan yang sama dengan lokasi sekarang');
        redirect(base_url('packing-list'));
    }
  } //end

  function proseskirim(){
      $id_user = $this->session->userdata('id');
      $kd_pkg = $this->data_model->filter($this->input->post('token'));    
      $kons = $this->data_model->filter($this->input->post('kd_kons'));    
      $loc = $this->data_model->filter($this->input->post('loc'));    
      $totalukuran = $this->data_model->filter($this->input->post('totalukuran'));
      $ukuran2 = $this->data_model->filter($this->input->post('ukuran2'));
      $idcostum = $this->data_model->filter($this->input->post('id_custom'));
      $nama_custom = $this->data_model->filter($this->input->post('nama_custom'));
      $notelp = $this->data_model->filter($this->input->post('notelp'));
      $almt = $this->data_model->filter($this->input->post('almt'));
      $satuan = $this->data_model->filter($this->input->post('satuan2'));
      if($satuan=="Yard"){
        $ukuran_yard = $ukuran2;
        $ukuran_meter = floatval($ukuran2) * 0.9144;
      } else {
        $ukuran_meter = $ukuran2;
        $ukuran_yard = floatval($ukuran2) / 0.9144;
      }
      $tgl = date('Y-m-d');
      if($kd_pkg!="" AND $kons!="" AND $loc!="" AND $totalukuran!="" AND $idcostum!="" AND $nama_custom!=""){
          if($idcostum==0){
              $this->data_model->saved('dt_konsumen', ['nama_konsumen'=>$nama_custom, 'no_hp'=>$notelp, 'alamat'=>$almt]);
              $id_konsumen = $this->data_model->get_byid('dt_konsumen', ['nama_konsumen'=>$nama_custom, 'no_hp'=>$notelp, 'alamat'=>$almt])->row("id_konsumen");
          } else {
              $id_konsumen = $idcostum;
          }
          $list_penjualan = [
              'kode_konstruksi' => $kons,
              'tgl' => $tgl,
              'jml_jual_list' => round($ukuran2,2),
              'jml_jual_sl' => 0,
              'satuan' => $satuan,
              'id_user' => $id_user,
              'id_konsumen' => $id_konsumen,
              'penjualan_list' => $kd_pkg,
              'departement' => $loc,
              'type_penjualan' => 'FromPaket'
          ];
          $this->data_model->saved('tb_penjualan', $list_penjualan);
          //cek harian = //
          $cek_hr = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kons, 'lokasi_produksi'=>$loc, 'waktu'=>$tgl]);
          if($cek_hr->num_rows() == 1){
              $id_rptd = $cek_hr->row("id_rptd");
              $terjual = floatval($cek_hr->row("terjual")) + $ukuran_meter;
              $terjual2 = floatval($cek_hr->row("terjual_yard")) + $ukuran_yard;
              $uprptd = [ 'terjual' => round($terjual,2), 'terjual_yard' => round($terjual2,2) ];
              $this->data_model->updatedata('id_rptd',$id_rptd,'report_produksi_harian',$uprptd);
          } else {
              $uprpt_data = [
                'kode_konstruksi' => $kons,
                'ins_grey' => 0, 'ins_finish' => 0, 'fol_grey' => 0, 'fol_finish' => 0, 
                'lokasi_produksi' => $loc,  'waktu' => $tgl,
                'terjual' => round($ukuran_meter,2),
                'bs' => 0, 'ins_grey_yard' => 0, 'ins_finish_yard' => 0, 'fol_grey_yard' => 0, 'fol_finish_yard' => 0,
                'terjual_yard' => round($ukuran_yard,2), 'bs_yard' => 0
              ];
              $this->data_model->saved('report_produksi_harian', $uprpt_data);
          }
          //cek stok total 
          $stok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kons, 'departement'=>$loc]);
          $id_stok = $stok->row("id_stok");
          if($satuan=="Yard"){
              $fol_finish = floatval($stok->row("stok_fol_finish")) - $ukuran_meter;
              $fol_finishy = floatval($stok->row("stok_fol_finish_yard")) - $ukuran_yard;
              $terjual = floatval($stok->row("terjual")) + $ukuran_meter;
              $terjualy = floatval($stok->row("terjual_yard")) + $ukuran_yard;
              $upstok = [
                'stok_fol_finish' => round($fol_finish,2),
                'stok_fol_finish_yard' => round($fol_finishy,2),
                'terjual' => round($terjual,2),
                'terjual_yard' => round($terjualy,2),
              ];
          } else {
              $fol_finish = floatval($stok->row("stok_fol")) - $ukuran_meter;
              $fol_finishy = floatval($stok->row("stok_fol_yard")) - $ukuran_yard;
              $terjual = floatval($stok->row("terjual")) + $ukuran_meter;
              $terjualy = floatval($stok->row("terjual_yard")) + $ukuran_yard;
              $upstok = [
                'stok_fol' => round($fol_finish,2),
                'stok_fol_yard' => round($fol_finishy,2),
                'terjual' => round($terjual,2),
                'terjual_yard' => round($terjualy,2),
              ];
          }
          $this->data_model->updatedata('id_stok',$id_stok,'report_stok',$upstok);
          $this->data_model->updatedata('kd',$kd_pkg,'new_tb_packinglist',['siap_jual'=>'sold']);
          $this->session->set_flashdata('announce', 'Berhasil menyimpan dan memproses penjualan');
          redirect(base_url('input-penjualan'));
      } else {
          $this->session->set_flashdata('gagal', 'Anda tidak paket penjualan dengan benar');
          redirect(base_url('packing-list'));
      }
  } //end

  function srt_jalan(){
    $txt = $this->data_model->filter($this->input->post('text_kode'));
    $ex = explode(',', $txt);
    if(count($ex) > 0){
        $data = array(
            'title' => 'Buat Surat Jalan Pengiriman',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_dep' => $this->session->userdata('departement'),
            'dt' => $ex
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('new_page/buat_surat_jalan', $data);
        $this->load->view('part/main_js_dttable');
    } else {
      echo "Token expired";
    }
  }//end

  function sj(){
    $token = $this->uri->segment(3);
    $cek = $this->data_model->get_byid('surat_jalan', ['sha1(id_sj)'=>$token]);
    if($cek->num_rows()==1){
        $data = array(
            'title' => 'Surat Jalan Pengiriman',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_dep' => $this->session->userdata('departement'),
            'dtsj' => $cek->row_array()
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('new_page/cetak_surat_jalan', $data);
        $this->load->view('part/main_js_dttable');
    } else {
      echo "Token expired";
    }
  }//end

  function save_sj(){
      $loc = $this->data_model->filter($this->input->post('loc'));
      $sj = $this->data_model->filter($this->input->post('sj'));
      $tgl = $this->data_model->filter($this->input->post('tgl'));
      $idcus = $this->data_model->filter($this->input->post('idcus'));
      $dep = $this->data_model->filter($this->input->post('dept'));
      $pkg = $this->input->post('pkg_kode');
      $konst = $this->input->post('kons_kode');
      if($loc!="" AND $sj!="" AND $tgl!="" AND $idcus!=""){
          if($loc=="cus"){
              $namacus = $this->data_model->filter($this->input->post('namacus'));
              $notelp = $this->data_model->filter($this->input->post('notelp'));
              $almt = $this->data_model->filter($this->input->post('almt'));
              $ceknum = $this->db->query("SELECT * FROM new_tb_suratjalan WHERE departement='$dep' ORDER BY id_sj DESC LIMIT 1");
              if($ceknum->num_rows()==0){
                $numbering = 1;
              } else {
                $numbering = intval($ceknum->row("numering")) + 1;
              }
              $cek_nosj = $this->data_model->get_byid('new_tb_suratjalan', ['no_sj'=>$sj])->num_rows();
              if($cek_nosj == 0){
                  //cekcustomer dulu
                  $cek_cus = $this->data_model->get_byid('dt_konsumen',['id_konsumen'=>$idcus]);
                  if($cek_cus->num_rows()==1){
                      $locnow_cus = $idcus;
                  } else {
                      $dtcus = ['nama_konsumen'=>$namacus,'no_hp'=>$notelp,'alamat'=>$almt];
                      $this->data_model->saved('dt_konsumen',$dtcus);
                      $idcusnew = $this->data_model->get_byid('dt_konsumen', $dtcus)->row("id_konsumen");
                      $locnow_cus = $idcusnew;
                  }
                  $dtlist = [
                      'numering' => $numbering,
                      'no_sj' => $sj,
                      'departement' => $dep,
                      'tgl_kirim' => $tgl,
                      'tujuan_kirim' => 'cus',
                      'lokasi_now' => 'Customer',
                      'id_konsumen' => $locnow_cus,
                      'id_user' => $this->session->userdata('id'),
                      'status' => 'sell',
                      'alasan_retur' => 'null'
                  ];
                  $this->data_model->saved('new_tb_suratjalan',$dtlist);
                  for ($i=0; $i < count($pkg); $i++) {
                    $this->data_model->updatedata('kd',$pkg[$i],'new_tb_packinglist',['kepada'=>$loc,'no_sj'=>$sj]);
                    $dtrol = $this->data_model->get_byid('new_tb_isi_paket', ['id_kdlist'=>$pkg[$i]]);
                    $kd_kons = $konst[$i];
                    if($dtrol->num_rows() > 0 ){
                      foreach($dtrol->result() as $val):
                          $no_roll = $val->kode_roll;
                          $two_car = substr( $no_roll, 0, 2 );
                          if($two_car == "SL"){ $sl_true = "True"; } else { $sl_true = "False"; }
                          $cek_kondisi_roll = $this->data_model->get_byid('new_tb_stok_outside', ['kd_roll'=>$no_roll]);
                          if($cek_kondisi_roll->num_rows()==1){
                              $id_stokout = $cek_kondisi_roll->row("id_stokout");
                              $ukuran_fol = $cek_kondisi_roll->row("ukuran_fol");
                              $jns_fol = $cek_kondisi_roll->row("jns_fol");
                              $dep_loc = $cek_kondisi_roll->row("pemilik")."-IN-".$cek_kondisi_roll->row("lokasi_skrg");
                              $cek_stok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kd_kons,'departement'=>$dep_loc]);
                              $id_stok = $cek_stok->row("id_stok");
                              if($jns_fol=="Finish"){
                                  $terjualy = $ukuran_fol;
                                  $terjualm = $ukuran_fol * 0.9144;
                                  $stok_terjual = floatval($cek_stok->row("terjual_yard")) + $ukuran_fol;
                                  $stok_terjualm = $stok_terjual * 0.9144;
                                  $stok_if = floatval($cek_stok->row("stok_fol_finish_yard")) - $ukuran_fol;
                                  $stok_ifm = $stok_if * 0.9144;
                                  $this->data_model->updatedata('id_stok',$id_stok,'report_stok',['stok_fol_finish'=>round($stok_ifm,2), 'terjual'=>round($stok_terjualm,2), 'stok_fol_finish_yard'=>round($stok_if,2), 'terjual_yard'=>round($stok_terjual,2)]);
                              } elseif($jns_fol=="Grey") {
                                  $terjualm = $ukuran_fol;
                                  $terjualy = $ukuran_fol / 0.9144;
                                  $stok_terjual = floatval($cek_stok->row("terjual")) + $ukuran_fol;
                                  $stok_terjualy = $stok_terjual / 0.9144;
                                  $stok_ig = floatval($cek_stok->row("stok_fol")) - $ukuran_fol;
                                  $stok_igy = $stok_ig / 0.9144;
                                  $this->data_model->updatedata('id_stok',$id_stok,'report_stok',['stok_fol'=>round($stok_ig,2), 'terjual'=>round($stok_terjual,2), 'stok_fol_yard'=>round($stok_igy,2), 'terjual_yard'=>round($stok_terjualy,2)]);
                              } else {
                                
                              }
                              $this->data_model->updatedata('id_stokout',$id_stokout,'new_tb_stok_outside',['lokasi_skrg'=>'Customer']);
                              $cek_harian = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kd_kons, 'lokasi_produksi'=>$dep, 'waktu'=>$tgl]);
                              if($cek_harian->num_rows() == 0){
                                  $rptd_list = [
                                      'kode_konstruksi' => $kd_kons, 'ins_grey' => 0, 'ins_finish' => 0, 'fol_grey' => 0, 'fol_finish' => 0, 'lokasi_produksi' => $dep, 'waktu' => $tgl, 'terjual' => round($terjualm,2), 'bs' => 0, 'ins_grey_yard' => 0, 'ins_finish_yard' =>0, 'fol_grey_yard' => 0, 'fol_finish_yard' => 0, 'terjual_yard' => round($terjualy,2), 'bs_yard' => 0, 'crt' => 0, 'crt_yard' => 0, 'bp' => 0, 'bp_yard' => 0
                                  ];
                                  $this->data_model->saved('report_produksi_harian',$rptd_list); 
                              } else {
                                  $id_rptd = $cek_harian->row("id_rptd");
                                  $newterjual = floatval($cek_harian->row("terjual")) + $terjualm;
                                  $newterjual2 = floatval($cek_harian->row("terjual_yard")) + $terjualy;
                                  $this->data_model->updatedata('id_rptd',$id_rptd, 'report_produksi_harian', ['terjual'=>round($newterjual,2) , 'terjual_yard'=>round($newterjual2,2)]);
                              }
                          } else {
                              //ini kalau roll tidak di kirim keluar
                              $cek_kondisi_roll = $this->data_model->get_byid('new_tb_pkg_fol', ['no_roll'=>$no_roll]);
                              $jns_fol = $cek_kondisi_roll->row("st_folding");
                              if($sl_true == "True"){
                                $cek_stok = $this->data_model->get_byid('report_stok_lama', ['kode_konstruksi'=>$kd_kons, 'departement'=>$dep]);
                                $id_stok = $cek_stok->row("id_sl");
                                if($jns_fol=="Finish"){
                                    $terjualy = $cek_kondisi_roll->row("ukuran_yard");
                                    $terjualm = $terjualy * 0.9144;
                                    $stok_terjual = floatval($cek_stok->row("terjual_yard")) + $terjualy;
                                    $stok_terjualm = $stok_terjual * 0.9144;
                                    $stok_if = floatval($cek_stok->row("fol_finish_yard")) - $terjualy;
                                    $stok_ifm = $stok_if * 0.9144;
                                    $this->data_model->updatedata('id_sl',$id_stok,'report_stok_lama',['fol_finish'=>round($stok_ifm,2), 'terjual'=>round($stok_terjualm,2), 'fol_finish_yard'=>round($stok_if,2), 'terjual_yard'=>round($stok_terjual,2)]);
                                } elseif($jns_fol=="Grey") {
                                    $terjualm = $cek_kondisi_roll->row("ukuran");
                                    $terjualy = $terjualm / 0.9144;
                                    $stok_terjual = floatval($cek_stok->row("terjual")) + $terjualm;
                                    $stok_terjualy = $stok_terjual / 0.9144;
                                    $stok_ig = floatval($cek_stok->row("fol_grey")) - $terjualm;
                                    $stok_igy = $stok_ig / 0.9144;
                                    $this->data_model->updatedata('id_sl',$id_stok,'report_stok_lama',['fol_grey'=>round($stok_ig,2), 'terjual'=>round($stok_terjual,2), 'fol_grey_yard'=>round($stok_igy,2), 'terjual_yard'=>round($stok_terjualy,2)]);
                                } else {
                                  
                                }
                              } else {
                              $cek_stok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kd_kons,'departement'=>$dep]);
                              $id_stok = $cek_stok->row("id_stok");
                              if($jns_fol=="Finish"){
                                  $terjualy = $cek_kondisi_roll->row("ukuran_yard");
                                  $terjualm = $terjualy * 0.9144;
                                  $stok_terjual = floatval($cek_stok->row("terjual_yard")) + $terjualy;
                                  $stok_terjualm = $stok_terjual * 0.9144;
                                  $stok_if = floatval($cek_stok->row("stok_fol_finish_yard")) - $terjualy;
                                  $stok_ifm = $stok_if * 0.9144;
                                  $this->data_model->updatedata('id_stok',$id_stok,'report_stok',['stok_fol_finish'=>round($stok_ifm,2), 'terjual'=>round($stok_terjualm,2), 'stok_fol_finish_yard'=>round($stok_if,2), 'terjual_yard'=>round($stok_terjual,2)]);
                              } elseif($jns_fol=="Grey") {
                                  $terjualm = $cek_kondisi_roll->row("ukuran");
                                  $terjualy = $terjualm / 0.9144;
                                  $stok_terjual = floatval($cek_stok->row("terjual")) + $terjualm;
                                  $stok_terjualy = $stok_terjual / 0.9144;
                                  $stok_ig = floatval($cek_stok->row("stok_fol")) - $terjualm;
                                  $stok_igy = $stok_ig / 0.9144;
                                  $this->data_model->updatedata('id_stok',$id_stok,'report_stok',['stok_fol'=>round($stok_ig,2), 'terjual'=>round($stok_terjual,2), 'stok_fol_yard'=>round($stok_igy,2), 'terjual_yard'=>round($stok_terjualy,2)]);
                              } else {
                                
                              }
                              }
                              $cek_harian = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kd_kons, 'lokasi_produksi'=>$dep, 'waktu'=>$tgl]);
                              if($cek_harian->num_rows() == 0){
                                  $rptd_list = [
                                      'kode_konstruksi' => $kd_kons, 'ins_grey' => 0, 'ins_finish' => 0, 'fol_grey' => 0, 'fol_finish' => 0, 'lokasi_produksi' => $dep, 'waktu' => $tgl, 'terjual' => round($terjualm,2), 'bs' => 0, 'ins_grey_yard' => 0, 'ins_finish_yard' =>0, 'fol_grey_yard' => 0, 'fol_finish_yard' => 0, 'terjual_yard' => round($terjualy,2), 'bs_yard' => 0, 'crt' => 0, 'crt_yard' => 0, 'bp' => 0, 'bp_yard' => 0
                                  ];
                                  $this->data_model->saved('report_produksi_harian',$rptd_list); 
                              } else {
                                  $id_rptd = $cek_harian->row("id_rptd");
                                  $newterjual = floatval($cek_harian->row("terjual")) + $terjualm;
                                  $newterjual2 = floatval($cek_harian->row("terjual_yard")) + $terjualy;
                                  $this->data_model->updatedata('id_rptd',$id_rptd, 'report_produksi_harian', ['terjual'=>round($newterjual,2) , 'terjual_yard'=>round($newterjual2,2)]);
                              }
                          }
                      endforeach;
                    }
                  }
              } else {
                  $this->session->set_flashdata('announce', 'Nomor surat jalan sudah digunakan');
                  redirect(base_url('packing-list'));
              }
          } else {
              $ceknum = $this->db->query("SELECT * FROM new_tb_suratjalan WHERE departement='$dep' ORDER BY id_sj DESC LIMIT 1");
              if($ceknum->num_rows()==0){
                $numbering = 1;
              } else {
                $numbering = intval($ceknum->row("numering")) + 1;
              }
              $cek_nosj = $this->data_model->get_byid('new_tb_suratjalan', ['no_sj'=>$sj])->num_rows();
              if($cek_nosj == 0){
                  $dtlist = [
                      'numering' => $numbering,
                      'no_sj' => $sj,
                      'departement' => $dep,
                      'tgl_kirim' => $tgl,
                      'tujuan_kirim' => 'pabrik',
                      'lokasi_now' => $loc,
                      'id_konsumen' => 0,
                      'id_user' => $this->session->userdata('id'),
                      'status' => 'send',
                      'alasan_retur' => 'null'
                  ];
                  $this->data_model->saved('new_tb_suratjalan',$dtlist);
                  $stok_kirim_dep = $dep."-IN-".$loc;
                  for ($i=0; $i < count($pkg); $i++) { 
                      $this->data_model->updatedata('kd',$pkg[$i],'new_tb_packinglist',['kepada'=>$loc,'no_sj'=>$sj]);
                      $dtrol = $this->data_model->get_byid('new_tb_isi_paket', ['id_kdlist'=>$pkg[$i]]);
                      if($dtrol->num_rows() > 0 ){
                        foreach($dtrol->result() as $val):
                            $no_roll = $val->kode_roll;
                            $ukuran_ig = 0; $ukuran_if = 0; $ukuran_fol = 0; $jns_fol = "null"; $kdkons="null";
                            $qr1 = $this->db->query("SELECT kode_produksi,no_roll,ukuran_ori FROM new_tb_pkg_list WHERE no_roll='$no_roll'");
                            if($qr1->num_rows()==1){ $ukuran_ig=$qr1->row("ukuran_ori");
                            $kdkons = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$qr1->row('kode_produksi')])->row("kode_konstruksi");
                            }
                            $qr2 = $this->db->query("SELECT no_roll,ukuran_ori_yard FROM new_tb_pkg_ins WHERE no_roll='$no_roll'");
                            if($qr2->num_rows()==1){ $ukuran_if=$qr2->row("ukuran_ori_yard"); }
                            $qr3 = $this->db->query("SELECT no_roll,st_folding,ukuran_now,ukuran_now_yard FROM new_tb_pkg_fol WHERE no_roll='$no_roll'");
                            if($qr3->num_rows()==1){
                              if($qr3->row("st_folding") == "Finish"){
                                  $ukuran_fol = $qr3->row("ukuran_now_yard"); $jns_fol = "Finish";
                              } else {
                                  $ukuran_fol = $qr3->row("ukuran_now"); $jns_fol = "Grey";
                              }
                            }
                            $this->data_model->saved('new_tb_stok_outside', ['pemilik'=>$dep,'lokasi_skrg'=>$loc,'ukuran_ig'=>$ukuran_ig,'ukuran_if'=>$ukuran_if,'ukuran_fol'=>$ukuran_fol,'jns_fol'=>$jns_fol,'kd_roll'=>$no_roll]);
                            if($kdkons!="null"){
                              $cek_stok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kdkons, 'departement'=>$stok_kirim_dep]);
                              if($cek_stok->num_rows()==0){
                                  $ukuran_igy = floatval($ukuran_ig) / 0.9144;
                                  $ukuran_ifm = floatval($ukuran_if) * 0.9144;
                                  if($jns_fol=='Finish'){
                                      $ukuran_foly = $ukuran_fol;
                                      $ukuran_folm = $ukuran_fol * 0.9144;
                                  } else {
                                      $ukuran_folm = $ukuran_fol;
                                      $ukuran_foly = $ukuran_fol / 0.9144;
                                  }
                                  if($ukuran_fol>0){
                                    $dtlist = [
                                      'kode_konstruksi' => $kdkons,
                                      'stok_ins' => 0,
                                      'stok_ins_finish' => 0,
                                      'stok_fol' => $jns_fol=='Finish' ? '0':round($ukuran_folm,2),
                                      'stok_fol_finish' => $jns_fol=='Finish' ? round($ukuran_folm,2):'0',
                                      'terjual' => 0,
                                      'bs' => 0,
                                      'terjual' => 0,
                                      'departement' => $stok_kirim_dep,
                                      'stok_ins_yard' => 0,
                                      'stok_ins_finish_yard' => 0,
                                      'stok_fol_yard' => $jns_fol=='Grey' ? round($ukuran_foly,2):'0',
                                      'stok_fol_finish_yard' => $jns_fol=='Finish' ? round($ukuran_foly,2):'0',
                                      'terjual_yard' => 0,
                                      'bs_yard' => 0,
                                      'retur_yard' => 0,
                                      'crt' => 0,
                                      'crt_yard' => 0,
                                      'bp' => 0,
                                      'bp_yard' => 0
                                    ];
                                  } else {
                                    if($ukuran_if>0){
                                      $dtlist = [
                                        'kode_konstruksi' => $kdkons,
                                        'stok_ins' => 0,
                                        'stok_ins_finish' => round($ukuran_ifm,2),
                                        'stok_fol' => 0,
                                        'stok_fol_finish' => 0,
                                        'terjual' => 0,
                                        'bs' => 0,
                                        'terjual' => 0,
                                        'departement' => $stok_kirim_dep,
                                        'stok_ins_yard' => 0,
                                        'stok_ins_finish_yard' => round($ukuran_if,2),
                                        'stok_fol_yard' => 0,
                                        'stok_fol_finish_yard' => 0,
                                        'terjual_yard' => 0,
                                        'bs_yard' => 0,
                                        'retur_yard' => 0,
                                        'crt' => 0,
                                        'crt_yard' => 0,
                                        'bp' => 0,
                                        'bp_yard' => 0
                                      ];
                                    } else {
                                      if($ukuran_ig>0){
                                        $dtlist = [
                                          'kode_konstruksi' => $kdkons,
                                          'stok_ins' => round($ukuran_ig,2),
                                          'stok_ins_finish' => 0,
                                          'stok_fol' => 0,
                                          'stok_fol_finish' => 0,
                                          'terjual' => 0,
                                          'bs' => 0,
                                          'terjual' => 0,
                                          'departement' => $stok_kirim_dep,
                                          'stok_ins_yard' => round($ukuran_igy,2),
                                          'stok_ins_finish_yard' => 0,
                                          'stok_fol_yard' => 0,
                                          'stok_fol_finish_yard' => 0,
                                          'terjual_yard' => 0,
                                          'bs_yard' => 0,
                                          'retur_yard' => 0,
                                          'crt' => 0,
                                          'crt_yard' => 0,
                                          'bp' => 0,
                                          'bp_yard' => 0
                                        ];
                                      }
                                    }
                                  }
                                  $this->data_model->saved('report_stok', $dtlist);
                              } else {
                                  $id_stok = $cek_stok->row("id_stok");
                                  $stok_ins = floatval($cek_stok->row("stok_ins")) + $ukuran_ig;
                                  $stok_insy = $stok_ins / 0.9144;
                                  $stok_ins_finish = floatval($cek_stok->row("stok_ins_finish_yard")) + $ukuran_if;
                                  $stok_ins_finishm = $stok_ins_finish * 0.9144;
                                  if($ukuran_fol>0){
                                      if($jns_fol=="Grey"){
                                          $stok_fol = floatval($cek_stok->row("stok_fol")) + $ukuran_fol;
                                          $stok_foly = $stok_fol / 0.9144;
                                          $updt = [
                                            'stok_fol' => round($stok_fol,2),
                                            'stok_fol_yard' => round($stok_foly,2)
                                          ];
                                      } else {
                                          $stok_fol = floatval($cek_stok->row("stok_fol_finish_yard")) + $ukuran_fol;
                                          $stok_folm = $stok_fol * 0.9144;
                                          $updt = [
                                            'stok_fol_finish' => round($stok_fol,2),
                                            'stok_fol_finish_yard' => round($stok_folm,2)
                                          ];
                                      }
                                  } else {
                                      if($ukuran_if>0){
                                          $updt = [
                                            'stok_ins_finish' => round($stok_ins_finishm,2),
                                            'stok_ins_finish_yard' => round($stok_ins_finish,2)
                                          ];
                                      } else {
                                          if($ukuran_ig>0){
                                            $updt = [
                                              'stok_ins' => round($stok_ins,2),
                                              'stok_ins_yard' => round($stok_insy,2)
                                            ];
                                          }
                                      }
                                  }
                                  $this->data_model->updatedata('id_stok',$id_stok,'report_stok',$updt);
                              }
                              $cek_stok2 = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kdkons, 'departement'=>$dep]);
                              $id_stok = $cek_stok2->row("id_stok");
                              if($ukuran_fol>0){
                                  if($jns_fol=="Grey"){
                                    $stok_fol = floatval($cek_stok2->row("stok_fol")) - $ukuran_fol;
                                    $stok_foly = $stok_fol / 0.9144;
                                    $this->data_model->updatedata('id_stok',$id_stok,'report_stok',['stok_fol'=>round($stok_fol,2), 'stok_fol_yard'=>round($stok_foly,2)]);
                                  } else {
                                    $stok_fol = floatval($cek_stok2->row("stok_fol_finish_yard")) - $ukuran_fol;
                                    $stok_folm = $stok_fol * 0.9144;
                                    $this->data_model->updatedata('id_stok',$id_stok,'report_stok',['stok_fol_finish'=>round($stok_folm,2), 'stok_fol_finish_yard'=>round($stok_fol,2)]);
                                  }
                              } else {
                                if($ukuran_if>0){
                                    $stok_if = floatval($cek_stok2->row("stok_ins_finish_yard")) - $ukuran_if;
                                    $stok_ifm = $stok_if * 0.9144;
                                    $this->data_model->updatedata('id_stok',$id_stok,'report_stok',['stok_ins_finish'=>round($stok_ifm,2), 'stok_ins_finish_yard'=>round($stok_if,2)]);
                                } else {
                                  if($ukuran_ig>0){
                                    $stok_ig = floatval($cek_stok2->row("stok_ins")) - $ukuran_ig;
                                    $stok_igy = $stok_ig / 0.9144;
                                    $this->data_model->updatedata('id_stok',$id_stok,'report_stok',['stok_ins'=>round($stok_ig,2), 'stok_ins_yard'=>round($stok_igy,2)]);
                                  }
                                }
                              }
                            }
                        endforeach;
                      }
                  }
                  $this->session->set_flashdata('announce', 'Berhasil membuat surat jalan pengiriman ke '.$loc.'');
                  redirect(base_url('pengiriman'));
              } else {
                  $this->session->set_flashdata('announce', 'Nomor surat jalan sudah digunakan');
                  redirect(base_url('packing-list'));
              }
          }
      } else {
        echo "Erorr empty form";
      }
      
  } //end

  function kirim_paket(){
      $loc_kirim = $this->data_model->filter($this->input->post('loc'));
      $surat_jalan = $this->data_model->filter($this->input->post('sj'));
      $tgl_kirim = $this->data_model->filter($this->input->post('tgl'));
      $id_customer = $this->data_model->filter($this->input->post('idcus'));
      $dep = $this->data_model->filter($this->input->post('dept'));
      $pkg = $this->input->post('pkg_kode');
      $konst = $this->input->post('kons_kode');
      if($id_customer=="0") {
          $nama_customer = $this->data_model->filter($this->input->post('namacus'));
          $notelp_customer = $this->data_model->filter($this->input->post('notelp'));
          $alamat_customer = $this->data_model->filter($this->input->post('almt'));
          if($nama_customer!="" AND $notelp_customer!="62" AND $alamat_customer!=""){
          $dtlistcus = ['nama_konsumen'=>$nama_customer, 'no_hp'=>$notelp_customer, 'alamat'=>$alamat_customer];
          $this->data_model->saved('dt_konsumen',$dtlistcus);
          $id_customer = $this->data_model->get_byid('dt_konsumen',$dtlistcus)->row("id_konsumen"); }
      }
      $dt_list = [
        'no_sj' => $surat_jalan,
        'dep_asal' => $dep,
        'tujuan_kirim' => $loc_kirim,
        'tgl_kirim' => $tgl_kirim,
        'id_user' => $this->session->userdata('id'),
        'id_customer' => $id_customer
      ];
      $this->data_model->saved('surat_jalan',$dt_list);
      for ($i=0; $i < count($pkg); $i++) { 
          $this->data_model->updatedata('kd',$pkg[$i],'new_tb_packinglist',['kepada'=>$loc_kirim,'no_sj'=>$surat_jalan]);
          $dtrow = $this->data_model->get_byid('new_tb_packinglist',['kd'=>$pkg[$i]]);
          if($dtrow->row("siap_jual") == 'y'){
              $query = $this->data_model->get_byid('data_fol', ['posisi'=>$pkg[$i]]);
          } else {
              $query = $this->data_model->get_byid('data_ig', ['loc_now'=>$pkg[$i]]);
          }
          foreach($query->result() as $val):
              $kode_roll = $val->kode_roll;
              if($dtrow->row("siap_jual") == 'y'){
                  $this->data_model->saved('kode_roll_outsite', ['dep_pembuat'=>$val->loc, 'dep_dikirim'=>$loc_kirim, 'kode_roll'=>$kode_roll, 'kd'=>$pkg[$i]]);
                  $dtroll = $this->data_model->get_byid('data_fol',['kode_roll'=>$kode_roll]);
                  $ukuran = $dtroll->row("ukuran");
                  $jns_fol = $dtroll->row("jns_fold");
                  $kode_konstruksi = $dtroll->row("konstruksi");
                  $cek_stok = $this->data_model->get_byid('data_stok',['dep'=>$val->loc, 'kode_konstruksi'=>$kode_konstruksi]);
                  $new_stok_send = $val->loc."-IN-".$loc_kirim;
                  $cek_stok2 = $this->data_model->get_byid('data_stok',['dep'=>$new_stok_send, 'kode_konstruksi'=>$kode_konstruksi]);
                  if($jns_fol=="Grey"){
                      $new_fg = floatval($cek_stok->row("prod_fg")) - $ukuran;
                      $idstok = $cek_stok->row("idstok");
                      $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_fg'=>round($new_fg,2)]);
                      if($cek_stok2->num_rows()==0){
                          $dtlist = [
                            'dep' => $new_stok_send,
                            'kode_konstruksi' => $kode_konstruksi,
                            'prod_ig' => 0, 'prod_fg' => $ukuran, 'prod_if' => 0, 'prod_ff' => 0,
                            'prod_bs1' => 0, 'prod_bp1' => 0, 'prod_bs2' => 0, 'prod_bp2' => 0, 'crt' => 0
                          ];
                          $this->data_model->saved('data_stok',$dtlist);
                      } else {
                          $idstok = $cek_stok2->row("idstok");
                          $new_ig = floatval($cek_stok2->row("prod_fg")) + $ukuran;
                          $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_fg'=>round($new_ig,2)]);
                      }
                      $new_stok_before = $val->loc."-IN-".$dep;
                      $cek_stok3 = $this->data_model->get_byid('data_stok',['dep'=>$new_stok_before, 'kode_konstruksi'=>$kode_konstruksi]);
                      if($cek_stok3->num_rows()==1){
                          $idstok = $cek_stok3->row("idstok");
                          $new_ig = floatval($cek_stok3->row("prod_fg")) - $ukuran;
                          $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_fg'=>round($new_ig,2)]);
                      } 
                      $cek_penjualan = $this->data_model->get_byid('data_penjualan', ['konstruksi'=>$kode_konstruksi, 'jns_fold'=>'Grey','tanggal'=>$tgl_kirim]);
                      if($cek_penjualan->num_rows()==0){
                          $dtpenj = [
                            'konstruksi' => $kode_konstruksi, 'jns_fold' => 'Grey', 'tanggal' => $tgl_kirim,
                            'jumlah_penjualan' => $ukuran, 'jml_roll' => '1'
                          ];
                          $this->data_model->saved('data_penjualan', $dtpenj);
                      } else {
                          $idpnj = $cek_penjualan->row("idautopen");
                          $ukuran_table = $cek_penjualan->row("jumlah_penjualan");
                          $new_ukuran = $ukuran_table + $ukuran;
                          $add_pjroll = intval($cek_penjualan->row("jml_roll")) + 1;
                          $this->data_model->updatedata('idautopen',$idpnj,'data_penjualan',['jumlah_penjualan'=>round($new_ukuran,2), 'jml_roll'=>$add_pjroll]);
                      }
                  } else {
                      $new_ff = floatval($cek_stok->row("prod_ff")) - $ukuran;
                      $idstok = $cek_stok->row("idstok");
                      $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ff'=>round($new_ff,2)]);
                      if($cek_stok2->num_rows()==0){
                          $dtlist = [
                            'dep' => $new_stok_send,
                            'kode_konstruksi' => $kode_konstruksi,
                            'prod_ig' => 0, 'prod_fg' => 0, 'prod_if' => 0, 'prod_ff' => $ukuran,
                            'prod_bs1' => 0, 'prod_bp1' => 0, 'prod_bs2' => 0, 'prod_bp2' => 0, 'crt' => 0
                          ];
                          $this->data_model->saved('data_stok',$dtlist);
                      } else {
                          $idstok = $cek_stok2->row("idstok");
                          $new_ig = floatval($cek_stok2->row("prod_ff")) + $ukuran;
                          $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ff'=>round($new_ig,2)]);
                      }
                      $new_stok_before = $val->loc."-IN-".$dep;
                      $cek_stok3 = $this->data_model->get_byid('data_stok',['dep'=>$new_stok_before, 'kode_konstruksi'=>$kode_konstruksi]);
                      if($cek_stok3->num_rows()==1){
                          $idstok = $cek_stok3->row("idstok");
                          $new_ig = floatval($cek_stok3->row("prod_ff")) - $ukuran;
                          $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ff'=>round($new_ig,2)]);
                      }
                      $cek_penjualan = $this->data_model->get_byid('data_penjualan', ['konstruksi'=>$kode_konstruksi, 'jns_fold'=>'Finish','tanggal'=>$tgl_kirim]);
                      if($cek_penjualan->num_rows()==0){
                          $dtpenj = [
                            'konstruksi' => $kode_konstruksi, 'jns_fold' => 'Finish', 'tanggal' => $tgl_kirim,
                            'jumlah_penjualan' => $ukuran, 'jml_roll' => '1'
                          ];
                          $this->data_model->saved('data_penjualan', $dtpenj);
                      } else {
                          $idpnj = $cek_penjualan->row("idautopen");
                          $ukuran_table = $cek_penjualan->row("jumlah_penjualan");
                          $new_ukuran = $ukuran_table + $ukuran;
                          $add_pjroll = intval($cek_penjualan->row("jml_roll")) + 1;
                          $this->data_model->updatedata('idautopen',$idpnj,'data_penjualan',['jumlah_penjualan'=>round($new_ukuran,2), 'jml_roll'=>$add_pjroll]);
                      }
                  }
              } else {
                  $this->data_model->saved('kode_roll_outsite', ['dep_pembuat'=>$val->dep, 'dep_dikirim'=>$loc_kirim, 'kode_roll'=>$kode_roll, 'kd'=>$pkg[$i]]);
                  $dtroll = $this->data_model->get_byid('data_ig',['kode_roll'=>$kode_roll]);
                  $ukuran = $dtroll->row("ukuran_ori");
                  $kode_konstruksi = $dtroll->row("konstruksi");
                  $cek_stok = $this->data_model->get_byid('data_stok',['dep'=>$val->dep, 'kode_konstruksi'=>$kode_konstruksi]);
                  $new_ig = floatval($cek_stok->row("prod_ig")) - $ukuran;
                  $idstok = $cek_stok->row("idstok");
                  $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($new_ig,2)]);
                  $new_stok_send = $val->dep."-IN-".$loc_kirim;
                  $cek_stok2 = $this->data_model->get_byid('data_stok',['dep'=>$new_stok_send, 'kode_konstruksi'=>$kode_konstruksi]);
                  if($cek_stok2->num_rows()==0){
                      $dtlist = [
                        'dep' => $new_stok_send,
                        'kode_konstruksi' => $kode_konstruksi,
                        'prod_ig' => $ukuran, 'prod_fg' => 0, 'prod_if' => 0, 'prod_ff' => 0,
                        'prod_bs1' => 0, 'prod_bp1' => 0, 'prod_bs2' => 0, 'prod_bp2' => 0, 'crt' => 0
                      ];
                      $this->data_model->saved('data_stok',$dtlist);
                  } else {
                      $idstok = $cek_stok2->row("idstok");
                      $new_ig = floatval($cek_stok2->row("prod_ig")) + $ukuran;
                      $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($new_ig,2)]);
                  }
                  $new_stok_before = $val->dep."-IN-".$dep;
                  $cek_stok3 = $this->data_model->get_byid('data_stok',['dep'=>$new_stok_before, 'kode_konstruksi'=>$kode_konstruksi]);
                  if($cek_stok3->num_rows()==1){
                      $idstok = $cek_stok3->row("idstok");
                      $new_ig = floatval($cek_stok3->row("prod_ig")) - $ukuran;
                      $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($new_ig,2)]);
                  } 
              }
              
          endforeach;
          if($dtrow->row("siap_jual") == 'y'){
              $this->data_model->updatedata('posisi',$pkg[$i],'data_fol',['posisi'=>$loc_kirim]);
          } else {
              $this->data_model->updatedata('loc_now',$pkg[$i],'data_ig',['loc_now'=>$loc_kirim]);
          }
      }
      $this->session->set_flashdata('announce', 'Pengiriman berhasil');
      redirect(base_url('packing-list'));
  } //end
  
}
?>
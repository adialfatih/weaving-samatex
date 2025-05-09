<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newpros extends CI_Controller
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
  
  function setkodekons(){
      $awal = $this->input->post('kdawal');
      $akir = $this->input->post('kdagain');
      for ($i=0; $i <count($awal) ; $i++) { 
            $this->data_model->updatedata('kode_konstruksi', $awal[$i], 'tb_konstruksi', ['chto'=>$akir[$i]]);
      }
      $this->session->set_flashdata('announce', 'Pengaturan telah berhasil di simpan');
      redirect(base_url('settings-konstruksi'));
  }

  function newprosproduksi(){
        $st_produksi = "IG";
        $status = $this->input->post('status');
        if($status==""){ $st_produksi = "IG"; } 
        elseif ($status=="Inspect") { $st_produksi = "IG"; }
        elseif ($status=="Folding") { $st_produksi = "FG"; }
        $kode = $this->input->post('kode');
        $tgl = $this->data_model->filter($this->input->post('dtproduksi'));
        $loc = $this->data_model->filter($this->input->post('loc'));
        $jml_produksi = $this->data_model->filter($this->input->post('jmlproduksi'));
        $jml_mesin = $this->data_model->filter($this->input->post('jmlmesin'));
        $kode_produksi = $this->data_model->filter($this->input->post('kode_produksi'));
        $satuan = $this->data_model->filter($this->input->post('satuan'));
        if($satuan=='Yard'){
            $nilai_yard = $jml_produksi;
            $nilai_meter = $jml_produksi * 0.9144;
        } else {
            $nilai_meter = $jml_produksi;
            $nilai_yard = $jml_produksi / 0.9144;
        }
        if($kode!='' AND $tgl!='' AND $loc!='' AND $jml_produksi!='' AND $jml_mesin!='' AND $kode_produksi!=''){
            $dtlist = [
              'kode_produksi' => $kode_produksi,
              'tgl_produksi' => $tgl,
              'kode_konstruksi' => $kode,
              'lokasi_produksi' => $loc,
              'jumlah_mesin' => $jml_mesin,
              'id_user' => $this->session->userdata('id'),
              'jumlah_produksi_awal' => round($nilai_meter,2),
              'jumlah_produksi_now' => round($nilai_meter,2),
              'jumlah_produksi_awal_yard' => round($nilai_yard,2),
              'jumlah_produksi_now_yard' => round($nilai_yard,2),
               'lokasi_saat_ini' => $loc,
               'satuan' => $satuan,
               'st_produksi' => $st_produksi
            ];
            $this->data_model->saved('tb_produksi', $dtlist);
            // input log program //
            $txt = "Telah menambahkan produksi baru dengan kode produksi (<strong>".$kode_produksi."</strong>)";
            $dtlog = [ 'id_user' => $this->session->userdata('id'), 'log' => $txt ];
            $this->data_model->saved('log_program', $dtlog);
            // input log produksi //
            $txt = "Telah menambahkan produksi baru dengan kode produksi (<strong>".$kode_produksi."</strong>)";
            $dtlog = [ 'id_user' => $this->session->userdata('id'), 'kode_produksi' => $kode_produksi, 'log'=>$txt ];
            $this->data_model->saved('log_produksi', $dtlog);
            //input produksi harian 
            $cek_report = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kode, 'lokasi_produksi'=>$loc, 'waktu'=>$tgl]);
            if($cek_report->num_rows()==1){
                $id_report = $cek_report->row("id_rptd");
                $jml_ig_meter = $cek_report->row("ins_grey");
                $jml_ig_yard = $cek_report->row("ins_grey_yard");
                $jml_fol_meter = $cek_report->row("fol_grey");
                $jml_fol_yard = $cek_report->row("fol_grey_yard");
                if($st_produksi=="FG"){
                    $up_jml_meter = $jml_fol_meter + $nilai_meter;
                    $up_jml_yard = $jml_fol_yard + $nilai_yard;
                    $uprpt_data = [ 'fol_grey' => round($up_jml_meter,2), 'fol_grey_yard' => round($up_jml_yard,2) ];
                    $this->data_model->updatedata('id_rptd', $id_report, 'report_produksi_harian', $uprpt_data);
                } elseif($st_produksi=="IG"){
                    $up_jml_meter = $jml_ig_meter + $nilai_meter;
                    $up_jml_yard = $jml_ig_yard + $nilai_yard;
                    $uprpt_data = [ 'ins_grey' => round($up_jml_meter,2), 'ins_grey_yard'=>round($up_jml_yard,2) ];
                    $this->data_model->updatedata('id_rptd', $id_report, 'report_produksi_harian', $uprpt_data);
                }
            } elseif($cek_report->num_rows()==0) {
                $uprpt_data = [
                'kode_konstruksi' => $kode,
                'ins_grey' => $st_produksi=='IG' ? round($nilai_meter,2) : 0,
                'ins_finish' => 0,
                'fol_grey' => $st_produksi=='FG' ? round($nilai_meter,2) : 0,
                'fol_finish' => 0, 
                'lokasi_produksi' => $loc, 
                'waktu' => $tgl,
                'terjual' => 0,
                'bs' => 0,
                'ins_grey_yard' => $st_produksi=='IG' ? round($nilai_yard,2) : 0,
                'ins_finish_yard' => 0,
                'fol_grey_yard' => $st_produksi=='FG' ? round($nilai_yard,2) : 0,
                'fol_finish_yard' => 0,
                'terjual_yard' => 0,
                'bs_yard' => 0
                ];
                $this->data_model->saved('report_produksi_harian', $uprpt_data);
            } 
            // end report harian //
            // input stok barang secara total 
            $cek_ttl_stok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kode, 'departement'=>$loc]);
            if($cek_ttl_stok->num_rows()==0){
                $stoklikst = [
                    'kode_konstruksi' => $kode,
                    'stok_ins' => $st_produksi=='IG' ? round($nilai_meter,2) : 0,
                    'stok_ins_finish' => 0, 
                    'stok_fol' => $st_produksi=='FG' ? round($nilai_meter,2) : 0, 
                    'stok_fol_finish' => 0,
                    'terjual' => 0, 
                    'bs' => 0, 
                    'retur' => 0,
                    'departement' => $loc,
                    'stok_ins_yard' => $st_produksi=='IG' ? round($nilai_yard,2) : 0,
                    'stok_ins_finish_yard' => 0, 
                    'stok_fol_yard' => $st_produksi=='FG' ? round($nilai_yard,2) : 0, 
                    'stok_fol_finish_yard' => 0,
                    'terjual_yard' => 0, 
                    'bs_yard' => 0, 
                    'retur_yard' => 0,
                ];
                $this->data_model->saved('report_stok',$stoklikst);
            } elseif ($cek_ttl_stok->num_rows()==1) {
                $id_ttl_stok = $cek_ttl_stok->row("id_stok");
                if($st_produksi=='IG'){
                    $jml_sblm_meter = $cek_ttl_stok->row("stok_ins");
                    $jml_sblm_yard = $cek_ttl_stok->row("stok_ins_yard");
                    $jumlah_stlh_meter = $jml_sblm_meter + $nilai_meter;
                    $jumlah_stlh_yard = $jml_sblm_yard + $nilai_yard;
                    $ar = ['stok_ins'=>round($jumlah_stlh_yard,2), 'stok_ins_yard'=>round($jumlah_stlh_yard,2)];
                } elseif ($st_produksi=='FG') {
                    $jml_sblm_meter = $cek_ttl_stok->row("stok_fol");
                    $jml_sblm_yard = $cek_ttl_stok->row("stok_fol_yard");
                    $jumlah_stlh_meter = $jml_sblm_meter + $nilai_meter;
                    $jumlah_stlh_yard = $jml_sblm_yard + $nilai_yard;
                    $ar = ['stok_fol'=>round($jumlah_stlh_yard,2), 'stok_fol_yard'=>round($jumlah_stlh_yard,2)];
                }
                $this->data_model->updatedata('id_stok', $id_ttl_stok, 'report_stok', $ar);
            }
            $this->session->set_flashdata('announce', 'Berhasil input data produksi, silahkan isi packing list');
            redirect(base_url('data/packinglist/'.sha1($kode_produksi)));
        } else {
            $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
            redirect(base_url('input-produksi'));
        }
  } //end proses produksi

  function up_pkglist(){
        $satuan = $this->data_model->filter($this->input->post('satuan'));
        $kd_produksi = $this->data_model->filter($this->input->post('id_produksi'));
        $nomesin = $this->input->post('nomesin');
        $ukuran = $this->input->post('ukuran');
        $ukuranb = $this->input->post('ukuranb');
        $ukuranc = $this->input->post('ukuranc');
        $ukuranbs = $this->input->post('ukuranbs');
        $operator = $this->input->post('operator');
        $idpkg = $this->input->post('idpkg');
        $auto = $this->data_model->get_byid('tb_produksi',['kode_produksi'=>$kd_produksi]);
        $st_pkg = $auto->row("st_produksi");
        if($satuan=="Yard"){
            $ukuran_total = $auto->row("jumlah_produksi_now_yard");
        } else {
            $ukuran_total = $auto->row("jumlah_produksi_now");
        }
        $kode_kons = $auto->row("kode_konstruksi");
        $tgl_pros = $auto->row("tgl_produksi");
        $loc = $this->session->userdata('departement');
        $all_data=0;
        $stok_bs = 0;
        // jumlah inputan
        $jumlah_inputan = array_sum($ukuran);
        if($jumlah_inputan>$ukuran_total){ //1. start if
            $this->session->set_flashdata('gagal', 'Total ukuran dalam packinglist lebih banyak dari jumlah produksi');
            redirect(base_url('data/packinglist/'.sha1($kd_produksi)));
        } else { //1. else
            for ($i=0; $i < count($idpkg) ; $i++) { 
                if($idpkg[$i]=="zero"){
                    if($nomesin[$i]!="" AND $ukuran[$i]!="" AND $operator[$i]!=""){
                        if($satuan=='Yard'){
                            $ukuran_in = floatval($ukuran[$i]) * 0.9144;
                            $ukuranb_in = floatval($ukuranb[$i]) * 0.9144;
                            $ukuranc_in = floatval($ukuranc[$i]) * 0.9144;
                            $ukuranbs_in = floatval($ukuranbs[$i]) * 0.9144;
                            $dtlist = [
                                'kode_produksi' => $kd_produksi,
                                'no_roll' => $nomesin[$i],
                                'ukuran_ori' => round($ukuran_in,2),
                                'ukuran_b' => round($ukuranb_in,2),
                                'ukuran_c' => round($ukuranc_in,2),
                                'ukuran_bs' => round($ukuranbs_in,2),
                                'ukuran_now' => round($ukuran_in,2),
                                'operator' => $operator[$i],
                                'st_pkg' => $st_pkg,
                                'satuan' => $satuan,
                                'ukuran_ori_yard' => round($ukuran[$i],2),
                                'ukuran_b_yard' => round($ukuranb[$i],2),
                                'ukuran_c_yard' => round($ukuranc[$i],2),
                                'ukuran_bs_yard' => round($ukuranbs[$i],2),
                                'ukuran_now_yard' => round($ukuran[$i],2)
                            ];
                        } elseif ($satuan=='Meter') {
                            $ukuran_in = floatval($ukuran[$i]) / 0.9144;
                            $ukuranb_in = floatval($ukuranb[$i]) / 0.9144;
                            $ukuranc_in = floatval($ukuranc[$i]) / 0.9144;
                            $ukuranbs_in = floatval($ukuranbs[$i]) / 0.9144;
                            $dtlist = [
                                'kode_produksi' => $kd_produksi,
                                'no_roll' => $nomesin[$i],
                                'ukuran_ori' => round($ukuran[$i],2),
                                'ukuran_b' => round($ukuranb[$i],2),
                                'ukuran_c' => round($ukuranc[$i],2),
                                'ukuran_bs' => round($ukuranbs[$i],2),
                                'ukuran_now' => round($ukuran[$i],2),
                                'operator' => $operator[$i],
                                'st_pkg' => $st_pkg,
                                'satuan' => $satuan,
                                'ukuran_ori_yard' => round($ukuran_in,2),
                                'ukuran_b_yard' => round($ukuranb_in,2),
                                'ukuran_c_yard' => round($ukuranc_in,2),
                                'ukuran_bs_yard' => round($ukuranbs_in,2),
                                'ukuran_now_yard' => round($ukuran_in,2)
                            ];
                        }
                        $this->data_model->saved('new_tb_pkg_list', $dtlist);
                        $all_data+=1;
                        $stok_bs+=floatval($ukuranbs[$i]);
                    }
                } else {
                    if($nomesin[$i]!="" AND $ukuran[$i]!="" AND $operator[$i]!="" AND $idpkg[$i]!=""){
                        if($satuan=='Yard'){
                            $ukuran_in = floatval($ukuran[$i]) * 0.9144;
                            $ukuranb_in = floatval($ukuranb[$i]) * 0.9144;
                            $ukuranc_in = floatval($ukuranc[$i]) * 0.9144;
                            $ukuranbs_in = floatval($ukuranbs[$i]) * 0.9144;
                            $dtlist = [
                                'kode_produksi' => $kd_produksi,
                                'no_roll' => $nomesin[$i],
                                'ukuran_ori' => round($ukuran_in,2),
                                'ukuran_b' => round($ukuranb_in,2),
                                'ukuran_c' => round($ukuranc_in,2),
                                'ukuran_bs' => round($ukuranbs_in,2),
                                'ukuran_now' => round($ukuran_in,2),
                                'operator' => $operator[$i],
                                'st_pkg' => $st_pkg,
                                'satuan' => $satuan,
                                'ukuran_ori_yard' => round($ukuran[$i],2),
                                'ukuran_b_yard' => round($ukuranb[$i],2),
                                'ukuran_c_yard' => round($ukuranc[$i],2),
                                'ukuran_bs_yard' => round($ukuranbs[$i],2),
                                'ukuran_now_yard' => round($ukuran[$i],2)
                            ];
                        } elseif ($satuan=='Meter') {
                            $ukuran_in = floatval($ukuran[$i]) / 0.9144;
                            $ukuranb_in = floatval($ukuranb[$i]) / 0.9144;
                            $ukuranc_in = floatval($ukuranc[$i]) / 0.9144;
                            $ukuranbs_in = floatval($ukuranbs[$i]) / 0.9144;
                            $dtlist = [
                                'kode_produksi' => $kd_produksi,
                                'no_roll' => $nomesin[$i],
                                'ukuran_ori' => round($ukuran[$i],2),
                                'ukuran_b' => round($ukuranb[$i],2),
                                'ukuran_c' => round($ukuranc[$i],2),
                                'ukuran_bs' => round($ukuranbs[$i],2),
                                'ukuran_now' => round($ukuran[$i],2),
                                'operator' => $operator[$i],
                                'st_pkg' => $st_pkg,
                                'satuan' => $satuan,
                                'ukuran_ori_yard' => round($ukuran_in,2),
                                'ukuran_b_yard' => round($ukuranb_in,2),
                                'ukuran_c_yard' => round($ukuranc_in,2),
                                'ukuran_bs_yard' => round($ukuranbs_in,2),
                                'ukuran_now_yard' => round($ukuran_in,2)
                            ];
                        }
                        $this->data_model->updatedata('id_pkg', $idpkg[$i],'new_tb_pkg_list', $dtlist);
                        $all_data+=1;
                        $stok_bs+=floatval($ukuranbs[$i]);
                    }
                }

            } //end pengulangan
            $txt = "Menambahkan sebanyak ".$all_data." roll data di dalam packing list (".$kd_produksi.").";
            $this->data_model->saved("log_program", ["id_user"=>$this->session->userdata('id'), "log"=>$txt]);
            $cek_today = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kode_kons, 'lokasi_produksi'=>$loc, 'waktu'=>$tgl_pros]);
            $idnow = $cek_today->row("id_rptd");
            //stok bs warning
            if($satuan=="Meter"){
                $up_nilai_bs_yard = $stok_bs / 0.9144 ;
                $ar_nilai = ['bs' => round($stok_bs,2), 'bs_yard' => round($up_nilai_bs_yard,2) ];
            } elseif($satuan=="Yard") {
                $up_nilai_bs_meter = $stok_bs * 0.9144 ;
                $ar_nilai = ['bs' => round($up_nilai_bs_meter,2), 'bs_yard' => round($stok_bs,2) ];
            }
            $this->data_model->updatedata('id_rptd', $idnow, 'report_produksi_harian', $ar_nilai);
            $cek_ttl_stok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kode_kons, 'departement'=>$loc]);
            $id_stok = $cek_ttl_stok->row("id_stok");
            $rs_bs = $cek_ttl_stok->row("bs");
            $new_rs_bs = $rs_bs + $stok_bs;
            if($satuan=="Yard"){
                $bs_meter = $new_rs_bs * 0.9144;
                $ar_nilai2 = ['bs'=>round($bs_meter,2), 'bs_yard'=>round($new_rs_bs,)];
            } elseif ($satuan=="Meter") {
                $bs_yard = $new_rs_bs / 0.9144;
                $ar_nilai2 = ['bs'=>round($new_rs_bs,2), 'bs_yard'=>round($bs_yard,2)];
            }
            $this->data_model->updatedata('id_stok', $id_stok, 'report_stok', $ar_nilai2);
            $this->session->set_flashdata('announce', 'Berhasil menyimpan '.$all_data.' roll data ke packinglist.');
            redirect(base_url('input-produksi'));
        } //1. end if 
        
  } //end proses pkglist

  function proinspect(){
        $kd = $this->data_model->filter($this->input->post('kode_produksi'));
        $dt_prod = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kd])->row_array();
        $tgl = $this->data_model->filter($this->input->post('tgl'));
        $jumlah = $this->data_model->filter($this->input->post('jumlah'));
        $satuan = $this->data_model->filter($this->input->post('satuan'));
        $dep = $this->session->userdata('departement');
        $st_produksi = "IF";
        //cek jumlah produksi saat ini
        $jml_produksi = $dt_prod["jumlah_produksi_now"];
        $jml_mesin = $dt_prod["jumlah_mesin"];
        //cek dulu konstruksinya 
        $kd_kons = $dt_prod["kode_konstruksi"];
        $loc = $this->session->userdata('departement');
        //cek perubahan konstruksinya
        $chto = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kd_kons])->row("chto");
        if($kd!="" AND $tgl!="" AND $jumlah!="" AND $satuan!=""){
            if($chto=="NULL"){ //jika tidak ada perubahan konsturksi
                if($satuan=="Meter"){
                    $jumlah_yard = floatval($jumlah) / 0.9144;
                    $dtlist = [
                        'kode_produksi' =>  $kd,
                        'tgl' => $tgl,
                        'jumlah_awal' => round($jumlah,2),
                        'satuan' => $satuan,
                        'proses_name' => $st_produksi,
                        'pemroses' => $this->session->userdata('id'),
                        'jumlah_akhir' => round($jumlah,2),
                        'ch_to' => '0',
                        'lokasi_produksi' => $dep,
                        'jumlah_awal_yard' => round($jumlah_yard,2),
                        'jumlah_akhir_yard' => round($jumlah_yard,2)
                    ];
                } elseif ($satuan=="Yard") {
                    $jumlah_meter = floatval($jumlah) * 0.9144;
                    $dtlist = [
                        'kode_produksi' =>  $kd,
                        'tgl' => $tgl,
                        'jumlah_awal' => round($jumlah_meter,2),
                        'satuan' => $satuan,
                        'proses_name' => $st_produksi,
                        'pemroses' => $this->session->userdata('id'),
                        'jumlah_akhir' => round($jumlah_meter,2),
                        'ch_to' => '0',
                        'lokasi_produksi' => $dep,
                        'jumlah_awal_yard' => round($jumlah,2),
                        'jumlah_akhir_yard' => round($jumlah,2)
                    ];
                }
                $this->data_model->saved('tb_proses_produksi',$dtlist);
                $ch_tolink = "null";
                $cek_ttl_stok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kd_kons, 'departement'=>$loc]);
                $id_stok = $cek_ttl_stok->row("id_stok");
                $stok_ig_meter = $cek_ttl_stok->row("stok_ins");
                $stok_if_meter = $cek_ttl_stok->row("stok_ins_finish");
                $stok_ig_yard = $cek_ttl_stok->row("stok_ins_yard");
                $stok_if_yard = $cek_ttl_stok->row("stok_ins_finish_yard");
                if($satuan=="Yard"){
                    $jumlah_meter = $jumlah * 0.9144;
                    $up_stok_ig_yard = $stok_ig_yard - $jumlah;
                    $up_stok_if_yard = $stok_if_yard + $jumlah;
                    $up_stok_ig_meter = $stok_ig_meter - $jumlah_meter;
                    $up_stok_if_meter = $stok_if_meter + $jumlah_meter;
                } elseif ($satuan=="Meter") {
                    $jumlah_yard = $jumlah / 0.9144;
                    $up_stok_ig_yard = $stok_ig_yard - $jumlah_yard;
                    $up_stok_if_yard = $stok_if_yard + $jumlah_yard;
                    $up_stok_ig_meter = $stok_ig_meter - $jumlah;
                    $up_stok_if_meter = $stok_if_meter + $jumlah;
                }
                $this->data_model->updatedata('id_stok', $id_stok, 'report_stok', ['stok_ins'=>round($up_stok_ig_meter,2), 'stok_ins_finish'=>round($up_stok_if_meter,2), 'stok_ins_yard'=>round($up_stok_ig_yard,2), 'stok_ins_finish_yard'=>round($up_stok_if_yard,2)]);
            } else { //jika  ada perubahan konsturksi
                //karena inpect finish berubah nama
                //kita bikin dulu produksi baru dengan nama baru
                if($satuan=="Yard"){
                    $jumlah_meter = floatval($jumlah) * 0.9144;
                    $prolist = [
                        'kode_produksi' => $this->data_model->acakKode(5),
                        'tgl_produksi' => $tgl,
                        'kode_konstruksi' => $chto,
                        'lokasi_produksi' => $this->session->userdata('departement'),
                        'jumlah_mesin' => $jml_mesin,
                        'id_user' => $this->session->userdata('id'),
                        'jumlah_produksi_awal' => round($jumlah_meter,2),
                        'jumlah_produksi_now' => round($jumlah_meter,2),
                        'jumlah_produksi_awal_yard' => round($jumlah,2),
                        'jumlah_produksi_now_yard' => round($jumlah,2),
                        'lokasi_saat_ini' => $this->session->userdata('departement'),
                        'satuan' => $satuan,
                        'st_produksi' => 'IF'
                    ];
                } elseif ($satuan=="Meter") {
                    $jumlah_yard = floatval($jumlah) / 0.9144;
                    $prolist = [
                        'kode_produksi' => $this->data_model->acakKode(5),
                        'tgl_produksi' => $tgl,
                        'kode_konstruksi' => $chto,
                        'lokasi_produksi' => $this->session->userdata('departement'),
                        'jumlah_mesin' => $jml_mesin,
                        'id_user' => $this->session->userdata('id'),
                        'jumlah_produksi_awal' => round($jumlah,2),
                        'jumlah_produksi_now' => round($jumlah,2),
                        'jumlah_produksi_awal_yard' => round($jumlah_yard,2),
                        'jumlah_produksi_now_yard' => round($jumlah_yard,2),
                        'lokasi_saat_ini' => $this->session->userdata('departement'),
                        'satuan' => $satuan,
                        'st_produksi' => 'IF'
                    ];
                }
                $this->data_model->saved('tb_produksi', $prolist);
                $id_baru = $this->db->query("SELECT id_produksi FROM tb_produksi ORDER BY id_produksi DESC LIMIT 1")->row("id_produksi");
                $kode_pro_baru = $this->data_model->get_byid('tb_produksi',['id_produksi'=>$id_baru])->row("kode_produksi");
                if($satuan=="Meter"){
                    $jumlah_yard = floatval($jumlah) / 0.9144;
                    $dtlist = [
                        'kode_produksi' =>  $kd,
                        'tgl' => $tgl,
                        'jumlah_awal' => round($jumlah,2),
                        'satuan' => $satuan,
                        'proses_name' => $st_produksi,
                        'pemroses' => $this->session->userdata('id'),
                        'jumlah_akhir' => round($jumlah,2),
                        'ch_to' => $id_baru,
                        'lokasi_produksi' => $this->session->userdata('departement'),
                        'jumlah_awal_yard' => round($jumlah_yard,2),
                        'jumlah_akhir_yard' => round($jumlah_yard,2)
                    ];
                } elseif ($satuan=="Yard") {
                    $jumlah_meter = floatval($jumlah) * 0.9144;
                    $dtlist = [
                        'kode_produksi' =>  $kd,
                        'tgl' => $tgl,
                        'jumlah_awal' => round($jumlah_meter,2),
                        'satuan' => $satuan,
                        'proses_name' => $st_produksi,
                        'pemroses' => $this->session->userdata('id'),
                        'jumlah_akhir' => round($jumlah_meter,2),
                        'ch_to' => $id_baru,
                        'lokasi_produksi' => $this->session->userdata('departement'),
                        'jumlah_awal_yard' => round($jumlah,2),
                        'jumlah_akhir_yard' => round($jumlah,2)
                    ];
                }
                $this->data_model->saved('tb_proses_produksi',$dtlist);
                $ch_tolink = $kode_pro_baru;
                $cek_ttl_stok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$chto, 'departement'=>$loc]);
                if($cek_ttl_stok->num_rows()==0){
                    if($satuan=="Meter"){
                        $jumlah_yard = floatval($jumlah) / 0.9144;
                        $stoklikst = [
                            'kode_konstruksi' => $chto,
                            'stok_ins' => 0, 
                            'stok_ins_finish' => round($jumlah,2), 
                            'stok_fol' => 0, 
                            'stok_fol_finish' => 0,
                            'terjual' => 0, 
                            'bs' => 0, 
                            'retur' => 0,
                            'departement' => $loc,
                            'stok_ins_yard' => 0, 
                            'stok_ins_finish_yard' => round($jumlah_yard,2), 
                            'stok_fol_yard' => 0, 
                            'stok_fol_finish_yard' => 0,
                            'terjual_yard' => 0, 
                            'bs_yard' => 0, 
                            'retur_yard' => 0,
                        ];
                    } elseif ($satuan=="Yard") {
                        $jumlah_meter = floatval($jumlah) * 0.9144;
                        $stoklikst = [
                            'kode_konstruksi' => $chto,
                            'stok_ins' => 0, 
                            'stok_ins_finish' => round($jumlah_meter,2),
                            'stok_fol' => 0, 
                            'stok_fol_finish' => 0,
                            'terjual' => 0, 
                            'bs' => 0, 
                            'retur' => 0,
                            'departement' => $loc,
                            'stok_ins_yard' => 0, 
                            'stok_ins_finish_yard' => round($jumlah,2), 
                            'stok_fol_yard' => 0, 
                            'stok_fol_finish_yard' => 0,
                            'terjual_yard' => 0, 
                            'bs_yard' => 0, 
                            'retur_yard' => 0,
                        ];
                    }                    
                    $this->data_model->saved('report_stok',$stoklikst);
                } elseif($cek_ttl_stok->num_rows()==1){
                    $id_stok = $cek_ttl_stok->row("id_stok");
                    $stok_ig = $cek_ttl_stok->row("stok_ins");
                    $stok_if = $cek_ttl_stok->row("stok_ins_finish");
                    $stok_ig_yard = $cek_ttl_stok->row("stok_ins_yard");
                    $stok_if_yard = $cek_ttl_stok->row("stok_ins_finish_yard");
                    if($satuan=="Yard"){
                        $jumlah_meter = $jumlah * 0.9144;
                        $up_stok_ig = $stok_ig - $jumlah_meter;
                        $up_stok_if = $stok_if + $jumlah_meter;
                        $up_stok_ig_yard = $stok_ig_yard - $jumlah;
                        $up_stok_if_yard = $stok_if_yard + $jumlah;
                    } elseif ($satuan=="Meter") {
                        $jumlah_yard = $jumlah / 0.9144;
                        $up_stok_ig = $stok_ig - $jumlah;
                        $up_stok_if = $stok_if + $jumlah;
                        $up_stok_ig_yard = $stok_ig_yard - $jumlah_yard;
                        $up_stok_if_yard = $stok_if_yard + $jumlah_yard;
                    }
                    $this->data_model->updatedata('id_stok', $id_stok, 'report_stok', ['stok_ins'=>round($up_stok_ig,2), 'stok_inspect_finish'=>round($up_stok_if,2), 'stok_ins_yard'=>round($up_stok_ig_yard,2), 'stok_ins_finish_yard'=>round($up_stok_if_yard,2)]);
                }
                // input log program //
                $txt = "<strong>".$kd."</strong> telah berubah menjadi produksi baru dengan kode produksi (<strong>".$kode_pro_baru."</strong>)";
                $dtlog = [ 'id_user' => $this->session->userdata('id'), 'log' => $txt ];
                $this->data_model->saved('log_program', $dtlog);
                // input log produksi //
                $dtlog = [ 'id_user' => $this->session->userdata('id'), 'kode_produksi' => $kd, 'log'=>$txt ];
                $this->data_model->saved('log_produksi', $dtlog);
                $txt = "Proses inspect finish berasal dari inspect grey dengan kode produksi (<strong>".$kd."</strong>).";
                $dtlog = [ 'id_user' => $this->session->userdata('id'), 'kode_produksi' => $kode_pro_baru, 'log'=>$txt ];
                $this->data_model->saved('log_produksi', $dtlog);
            } //end..jika ada perubahan konsturksi 
            if($satuan=="Meter"){
                $jumlah_yard = floatval($jumlah) / 0.9144;
                $jumlah_yard = round($jumlah_yard,2);
                $jumlah_baru_meter = $jml_produksi - round($jumlah,2);
                $jumlah_baru_yard = $dt_prod['jumlah_produksi_now_yard'] - $jumlah_yard;
            } elseif ($satuan=="Yard") {
                $jumlah_meter = floatval($jumlah) * 0.9144;
                $jumlah_meter = round($jumlah_meter,2);
                $jumlah_baru_meter = $jml_produksi - $jumlah_meter;
                $jumlah_baru_yard = $dt_prod['jumlah_produksi_now_yard'] - round($jumlah,2);
            }
            // $jumlah_baru = $jml_produksi - $jumlah;
            $this->data_model->updatedata('kode_produksi',$kd, 'tb_produksi', ['jumlah_produksi_now'=>round($jumlah_baru_meter,2), 'jumlah_produksi_now_yard'=>round($jumlah_baru_yard,2) ]);
            $txt = "Proses Inspect Finish dengan kode produksi (<strong>".$kd."</strong>)";
            $dtlog = [
                'id_user' => $this->session->userdata('id'),
                'kode_produksi' => $kd,
                'log' => $txt
            ];
            $dtlog2 = [ 'id_user' => $this->session->userdata('id'), 'log' => $txt];
            $this->data_model->saved('log_produksi',$dtlog);
            $this->data_model->saved('log_program',$dtlog2);
            $id_proses = $this->data_model->get_byid('tb_proses_produksi', $dtlist)->row("id_proses");
            $this->session->set_flashdata('announce', 'Proses inspect berhasil. Silahkan isi packing list');
            redirect(base_url('data/inspect/'.sha1($kd)."/".sha1($ch_tolink)."/".$id_proses));
        } else {
            $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
            redirect(base_url('input-produksi'));
        }

  } //end proses inspect finish
  
  function up_pkginspect(){
        $satuan = $this->data_model->filter($this->input->post('satuan'));
        $kdp = $this->data_model->filter($this->input->post('kode_produksi'));
        $loc = $this->data_model->filter($this->input->post('lokasi_now'));
        $tgl_produksi = $this->data_model->filter($this->input->post('tgl_produksi'));
        //cek kd kons
        $kode_kons = $this->data_model->get_byid('tb_produksi',['kode_produksi'=>$kdp])->row("kode_konstruksi");
        $fol_tgl = $this->input->post('fol_tgl');
        $fol_ukuran = $this->input->post('fol_ukuran');
        $fol_ukurana = $this->input->post('fol_ukurana');
        $fol_ukuranb = $this->input->post('fol_ukuranb');
        $fol_ukuranc = $this->input->post('fol_ukuranc');
        $fol_ukuranbs = $this->input->post('fol_ukuranbs');
        $fol_operator = $this->input->post('fol_operator');
        $nomc = $this->input->post('nomc');
        $idpkg = $this->input->post('idpkg');
        //echo $satuan."<hr>";
        $stok_bs = 0;
        $stok_asli = 0;
        for ($i=0; $i < count($nomc); $i++) { 
            //echo $i."->(".$nomc[$i].")".$fol_tgl[$i]."->".$fol_ukuran[$i]."->".$fol_ukuranb[$i]."->".$fol_ukuranc[$i]."->".$fol_ukuranbs[$i]."->".$fol_operator[$i]."<br>";
            if($fol_tgl[$i]!="" AND $fol_ukuran[$i]!="" AND $fol_operator[$i]!=""){
                if($satuan=="Yard"){
                    $mtr_ori = floatval($fol_ukuran[$i]) * 0.9144; 
                    $mtr_a = floatval($fol_ukurana[$i]) * 0.9144; 
                    $mtr_b = floatval($fol_ukuranb[$i]) * 0.9144; 
                    $mtr_c = floatval($fol_ukuranc[$i]) * 0.9144; 
                    $mtr_bs = floatval($fol_ukuranbs[$i]) * 0.9144;
                    $dtlist = [
                        'id_pkg' => $idpkg[$i],
                        'kode_produksi' => $kdp,
                        'no_roll' => $nomc[$i],
                        'ukuran_ori' => round($mtr_ori,2),
                        'ukuran_a' => round($mtr_a,2),
                        'ukuran_b' => round($mtr_b,2),
                        'ukuran_c' => round($mtr_c,2),
                        'ukuran_bs' => round($mtr_bs,2),
                        'ukuran_now' => round($mtr_ori,2),
                        'operator' => $fol_operator[$i],
                        'satuan' => $satuan,
                        'tgl' => $fol_tgl[$i],
                        'ukuran_ori_yard' => round($fol_ukuran[$i],2),
                        'ukuran_a_yard' => round($fol_ukurana[$i],2),
                        'ukuran_b_yard' => round($fol_ukuranb[$i],2),
                        'ukuran_c_yard' => round($fol_ukuranc[$i],2),
                        'ukuran_bs_yard' => round($fol_ukuranbs[$i],2),
                        'ukuran_now_yard' => round($fol_ukuran[$i],2)
                    ];
                } else {
                    $yrd_ori = floatval($fol_ukuran[$i]) / 0.9144; 
                    $yrd_a = floatval($fol_ukurana[$i]) / 0.9144; 
                    $yrd_b = floatval($fol_ukuranb[$i]) / 0.9144; 
                    $yrd_c = floatval($fol_ukuranc[$i]) / 0.9144; 
                    $yrd_bs = floatval($fol_ukuranbs[$i]) / 0.9144;
                    $dtlist = [
                        'id_pkg' => $idpkg[$i],
                        'kode_produksi' => $kdp,
                        'no_roll' => $nomc[$i],
                        'ukuran_ori' => round($fol_ukuran[$i],2),
                        'ukuran_a' => round($fol_ukurana[$i],2),
                        'ukuran_b' => round($fol_ukuranb[$i],2),
                        'ukuran_c' => round($fol_ukuranc[$i],2),
                        'ukuran_bs' => round($fol_ukuranbs[$i],2),
                        'ukuran_now' => round($fol_ukuran[$i],2),
                        'operator' => $fol_operator[$i],
                        'satuan' => $satuan,
                        'tgl' => $fol_tgl[$i],
                        'ukuran_ori_yard' => round($yrd_ori,2),
                        'ukuran_a_yard' => round($yrd_a,2),
                        'ukuran_b_yard' => round($yrd_b,2),
                        'ukuran_c_yard' => round($yrd_c,2),
                        'ukuran_bs_yard' => round($yrd_bs,2),
                        'ukuran_now_yard' => round($yrd_ori,2)
                    ];
                }
                
                $this->data_model->saved('new_tb_pkg_ins', $dtlist);
                $stok_bs = $stok_bs + floatval($fol_ukuranbs[$i]);
                $stok_asli = $stok_asli + floatval($fol_ukuran[$i]);
                //echo "owek-";$fol_ukuranbs[$i];
                //update_pkg_sebelumnya
                if($satuan=="Yard"){
                    $nilai_meter = floatval($fol_ukuran[$i]) * 0.9144;
                    $nilai_yard = $fol_ukuran[$i];
                    $cek_pkg_before_m = $this->data_model->get_byid('new_tb_pkg_list', ['id_pkg'=>$idpkg[$i]])->row("ukuran_now");
                    $cek_pkg_before_y = $this->data_model->get_byid('new_tb_pkg_list', ['id_pkg'=>$idpkg[$i]])->row("ukuran_now_yard");
                    $kurangi_m = $cek_pkg_before_m - $nilai_meter;
                    $kurangi_y = $cek_pkg_before_y - $nilai_yard;
                    $this->data_model->updatedata('id_pkg', $idpkg[$i], 'new_tb_pkg_list', ['ukuran_now'=>round($kurangi_m,2), 'ukuran_now_yard'=>round($kurangi_y,2)]);
                } elseif ($satuan=="Meter") {
                    $nilai_yard = floatval($fol_ukuran[$i]) / 0.9144;
                    $nilai_meter = $fol_ukuran[$i];
                    $cek_pkg_before_m = $this->data_model->get_byid('new_tb_pkg_list', ['id_pkg'=>$idpkg[$i]])->row("ukuran_now");
                    $cek_pkg_before_y = $this->data_model->get_byid('new_tb_pkg_list', ['id_pkg'=>$idpkg[$i]])->row("ukuran_now_yard");
                    $kurangi_m = $cek_pkg_before_m - $nilai_meter;
                    $kurangi_y = $cek_pkg_before_y - $nilai_yard;
                    $this->data_model->updatedata('id_pkg', $idpkg[$i], 'new_tb_pkg_list', ['ukuran_now'=>round($kurangi_m,2), 'ukuran_now_yard'=>round($kurangi_y,2)]);
                }
                $cek_today = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kode_kons, 'lokasi_produksi'=>$loc, 'waktu'=>$fol_tgl[$i]]);
                if($cek_today->num_rows()==1){
                    if($satuan=="Yard"){
                        $mtr_ins = floatval($fol_ukuran[$i]) * 0.9144;
                        $yrd_ins = floatval($fol_ukuran[$i]);
                        $nilai_ins_m = $cek_today->row("ins_finish");
                        $nilai_ins_y = $cek_today->row("ins_finish_yard");
                        $up_nilai_ins_m = $nilai_ins_m + $mtr_ins;
                        $up_nilai_ins_y = $nilai_ins_y + $yrd_ins;
                        $mtr_bs = floatval($fol_ukuranbs[$i]) * 0.9144;
                        $yrd_bs = floatval($fol_ukuranbs[$i]);
                        $nilai_bs_m = $cek_today->row("bs");
                        $nilai_bs_y = $cek_today->row("bs_yard");
                        $up_nilai_bs_m = floatval($nilai_bs_m) + $mtr_bs;
                        $up_nilai_bs_y = floatval($nilai_bs_y) + $yrd_bs;
                        $ar_nilai = ['ins_finish'=>round($up_nilai_ins_m,2), 'bs'=>round($up_nilai_bs_m,2), 'ins_finish_yard'=>round($up_nilai_ins_y,2), 'bs_yard'=>round($up_nilai_bs_y,2)];
                    } elseif ($satuan=="Meter") {
                        $yrd_ins = floatval($fol_ukuran[$i]) / 0.9144;
                        $mtr_ins = $fol_ukuran[$i];
                        $nilai_ins_m = $cek_today->row("ins_finish");
                        $nilai_ins_y = $cek_today->row("ins_finish_yard");
                        $up_nilai_ins_m = $nilai_ins_m + $mtr_ins;
                        $up_nilai_ins_y = $nilai_ins_y + $yrd_ins;
                        $yrd_bs = floatval($fol_ukuranbs[$i]) / 0.9144;
                        $mtr_bs = $fol_ukuranbs[$i];
                        $nilai_bs_m = $cek_today->row("bs");
                        $nilai_bs_y = $cek_today->row("bs_yard");
                        $up_nilai_bs_m = floatval($nilai_bs_m) + floatval($mtr_bs);
                        $up_nilai_bs_y = floatval($nilai_bs_y) + floatval($yrd_bs);
                        $ar_nilai = ['ins_finish'=>round($up_nilai_ins_m,2), 'bs'=>round($up_nilai_bs_m,2), 'ins_finish_yard'=>round($up_nilai_ins_y,2), 'bs_yard'=>round($up_nilai_bs_y,2)];
                    }
                    $idnow = $cek_today->row("id_rptd");
                    $this->data_model->updatedata('id_rptd', $idnow, 'report_produksi_harian', $ar_nilai);
                } elseif ($cek_today->num_rows()==0) {
                    if($satuan=="Yard"){
                        $mtr_nilai = floatval($fol_ukuran[$i]) * 0.9144;
                        $mtr_bs = floatval($fol_ukuranbs[$i]) * 0.9144;
                        $ar_nilai = [
                            'kode_konstruksi' => $kode_kons,
                            'ins_grey' => 0, 
                            'ins_finish' => round($mtr_nilai,2),
                            'fol_grey' => 0, 
                            'fol_finish' => 0, 
                            'lokasi_produksi' => $loc,
                            'waktu' => $fol_tgl[$i], 
                            'terjual' => 0, 
                            'bs' => round($mtr_bs,2),
                            'ins_grey_yard' => 0, 
                            'ins_finish_yard' => round($fol_ukuran[$i],2),
                            'fol_grey_yard' => 0, 
                            'fol_finish_yard' => 0, 
                            'terjual_yard' => 0,
                            'bs_yard' => round($fol_ukuranbs[$i],2)
                        ];
                    } elseif ($satuan=="Meter") {
                        $yrd_nilai = floatval($fol_ukuran[$i]) / 0.9144;
                        $yrd_bs = floatval($fol_ukuranbs[$i]) / 0.9144;
                        $ar_nilai = [
                            'kode_konstruksi' => $kode_kons,
                            'ins_grey' => 0, 
                            'ins_finish' => round($fol_ukuran[$i],2),
                            'fol_grey' => 0, 
                            'fol_finish' => 0, 
                            'lokasi_produksi' => $loc,
                            'waktu' => $fol_tgl[$i], 
                            'terjual' => 0, 
                            'bs' => round($fol_ukuranbs[$i],2),
                            'ins_grey_yard' => 0, 
                            'ins_finish_yard' => round($yrd_nilai,2),
                            'fol_grey_yard' => 0, 
                            'fol_finish_yard' => 0, 
                            'terjual_yard' => 0,
                            'bs_yard' => round($yrd_bs,2)
                        ];
                    }
                    $this->data_model->saved('report_produksi_harian', $ar_nilai);
                }
            } else {
                //jika data kosong
            }
        } //end perulangan array inputan
        //cek penambahan non roll
        $annorol = $this->input->post('annorol');
        $antgl = $this->input->post('antgl');
        $anukuran = $this->input->post('anukuran');
        $con_an = count($annorol);
        $sl_mtr = 0; $sl_yrd = 0;
        for($a=0; $a<$con_an; $a++){
            if($annorol[$a]!="" AND $antgl[$a]!="" AND $anukuran!=""){
                if($satuan=="Yard"){
                    $ukr = floatval($anukuran[$a]);
                    $ukr_meter = $ukr * 0.9144;
                    $dtlist = [
                        'id_pkg' => '0',
                        'kode_produksi' => $kdp,
                        'no_roll' => $annorol[$a],
                        'ukuran_ori' => round($ukr_meter,2),
                        'ukuran_a' => '0',
                        'ukuran_b' => '0',
                        'ukuran_c' => '0',
                        'ukuran_bs' => '0',
                        'ukuran_now' => round($ukr_meter,2),
                        'operator' => 'SL',
                        'satuan' => $satuan,
                        'tgl' => $antgl[$a],
                        'ukuran_ori_yard' => round($ukr,2),
                        'ukuran_a_yard' => '0',
                        'ukuran_b_yard' => '0',
                        'ukuran_c_yard' => '0',
                        'ukuran_bs_yard' => '0',
                        'ukuran_now_yard' => round($ukr,2)
                    ];
                    $sl_mtr = $sl_mtr + $ukr_meter;
                    $sl_yrd = $sl_yrd + $ukr;
                } else {
                    $ukr = floatval($anukuran[$a]);
                    $ukr_yard = $ukr / 0.9144;
                    $dtlist = [
                        'id_pkg' => '0',
                        'kode_produksi' => $kdp,
                        'no_roll' => $annorol[$a],
                        'ukuran_ori' => round($ukr,2),
                        'ukuran_a' => '0',
                        'ukuran_b' => '0',
                        'ukuran_c' => '0',
                        'ukuran_bs' => '0',
                        'ukuran_now' => round($ukr,2),
                        'operator' => 'SL',
                        'satuan' => $satuan,
                        'tgl' => $antgl[$a],
                        'ukuran_ori_yard' => round($ukr_yard,2),
                        'ukuran_a_yard' => '0',
                        'ukuran_b_yard' => '0',
                        'ukuran_c_yard' => '0',
                        'ukuran_bs_yard' => '0',
                        'ukuran_now_yard' => round($ukr_yard,2)
                    ];
                    $sl_mtr = $sl_mtr + $ukr;
                    $sl_yrd = $sl_yrd + $ukr_yard;
                }
                $this->data_model->saved('new_tb_pkg_ins', $dtlist);
                $cek_sl = $this->data_model->get_byid('report_stok_lama', ['kode_konstruksi'=>$kode_kons, 'departement'=>$loc]);
                if($cek_sl->num_rows()==0){
                    $sllist = [
                        'kode_konstruksi' => $kode_kons,
                        'ins_finish' => round($sl_mtr,2),
                        'fol_grey' => 0,
                        'fol_finish' => 0,
                        'terjual' => 0,
                        'ins_finish_yard' => round($sl_yrd,2),
                        'fol_grey_yard' => 0,
                        'fol_finish_yard' => 0,
                        'terjual_yard' => 0,
                        'departement' => $loc
                    ];
                    $this->data_model->saved('report_stok_lama', $sllist);
                } else {
                    $idsl = $cek_sl->row("id_sl");
                    $dt_slmtr = $cek_sl->row('ins_finish');
                    $dt_slyrd = $cek_sl->row('ins_finish_yard');
                    $up_slmtr = $dt_slmtr + round($sl_mtr,2);
                    $up_slyrd = $dt_slyrd + round($sl_yrd,2);
                    $this->data_model->updatedata('id_sl',$idsl,'report_stok_lama',['ins_finish'=>round($up_slmtr,2), 'ins_finish_yard'=>round($up_slyrd,2)]);
                }
            }
        }
        //end cek non roll
        $cek_ttl_stok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kode_kons, 'departement'=>$loc]);
        $id_sstok = $cek_ttl_stok->row("id_stok");
        if($satuan=="Yard"){
            $stok_bs_meter = floatval($stok_bs) * 0.9144;
            $stok_bs_yard = $stok_bs;
        } elseif ($satuan=="Meter") {
            $stok_bs_yard = floatval($stok_bs) / 0.9144;
            $stok_bs_meter = $stok_bs;
        }
        $nbs = $cek_ttl_stok->row("bs");
        $up_bs_mtr = $stok_bs_meter + $nbs;
        $up_bs_yrd = $stok_bs_yard + $nbs;
        $this->data_model->updatedata('id_stok',$id_sstok,'report_stok',['bs'=>round($up_bs_mtr,2), 'bs_yard'=>round($up_bs_yrd,2)]);
        $this->session->set_flashdata('announce', 'Update packinglist berhasil');
        redirect(base_url('input-produksi'));
        ///NOTE BELOM MASUKE KE STOK REPORT PRODUKSI HARIAN
  } //end proses

  function profolding(){
        $kd = $this->data_model->filter($this->input->post('kode_produksi'));
        $kdkons = $this->data_model->filter($this->input->post('kode_konstruksi'));
        $tgl = $this->data_model->filter($this->input->post('tgl'));
        $jumlah = $this->data_model->filter($this->input->post('jumlah'));
        $satuan = $this->data_model->filter($this->input->post('satuan'));
        if($satuan=="Yard"){
            $jumlah_yard = $jumlah;
            $jumlah_mtr = floatval($jumlah) * 0.9144;
        } else {
            $jumlah_yard = floatval($jumlah) / 0.9144;
            $jumlah_mtr = $jumlah;
        }
        $round_mtr = round($jumlah_mtr,2);
        $round_yrd = round($jumlah_yard,2);
        $st_folding = $this->data_model->filter($this->input->post('st_folding'));
        $loc = $this->session->userdata('departement');
        //code 1.0--input dulu ke proses produksi
            $dtlist = [
                'kode_produksi' => $kd,
                'tgl' => $tgl,
                'jumlah_awal' => $round_mtr,
                'satuan' => $satuan,
                'proses_name' => $st_folding=='Grey' ? 'FG':'FF',
                'pemroses' => $this->session->userdata('id'),
                'jumlah_akhir' => $round_mtr,
                'ch_to' => 0,
                'lokasi_produksi' => $loc,
                'jumlah_awal_yard' => $round_yrd,
                'jumlah_akhir_yard' => $round_yrd
            ];
        $this->data_model->saved('tb_proses_produksi', $dtlist);
        $id_proses = $this->data_model->get_byid('tb_proses_produksi', $dtlist)->row("id_proses");
        $cek_pros_harian = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kdkons, 'lokasi_produksi'=>$loc, 'waktu' => $tgl]);
        $id_pos = $cek_pros_harian->row("id_rptd");
        // end code 1.1--
        if($st_folding=="Grey"){
            //code 1.1-- Jika folding grey maka ambilkan data dari inspect grey
            $cek_ig = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kd, 'st_produksi'=>'IG']);
            $idproduksi = $cek_ig->row("id_produksi");
            $jml_before = $cek_ig->row("jumlah_produksi_now");
            $jml_before2 = $cek_ig->row("jumlah_produksi_now_yard");
            $jml_now = $jml_before - $round_mtr;
            $jml_now2 = $jml_before2 - $round_yrd;
            $this->data_model->updatedata('id_produksi', $idproduksi, 'tb_produksi', ['jumlah_produksi_now'=>$jml_now, 'jumlah_produksi_now_yard'=>$jml_now2]);
            //cek stok harian
            $jml_fg_before = $cek_pros_harian->row("fol_grey");
            $jml_fg_before2 = $cek_pros_harian->row("fol_grey_yard");
            $jml_fg_now = $jml_fg_before + $round_mtr;
            $jml_fg_now2 = $jml_fg_before2 + $round_yrd;
            $this->data_model->updatedata('id_rptd', $id_pos, 'report_produksi_harian', ['fol_grey'=>$jml_fg_now, 'fol_grey_yard'=>$jml_fg_now2]);
            // end code 1.1--
            $txt = "Proses folding grey dengan kode produksi (<strong>".$kd."</strong>)";
            $loglist = [
                'id_user' => $this->session->userdata('id'),
                'kode_produksi' => $kd,
                'log' => $txt
            ];
            $this->data_model->saved('log_produksi', $loglist);
            $this->data_model->saved('log_program', ['id_user'=> $this->session->userdata('id'), 'log'=>$txt]);
            $cek_ttl_stok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kdkons, 'departement'=>$loc]);
            if($cek_ttl_stok->num_rows() == 1){
                $id_stok = $cek_ttl_stok->row("id_stok");
                $stok_fg = $cek_ttl_stok->row("stok_fol");
                $up_stok_fg = $stok_fg + $round_mtr;
                $stok_ig = $cek_ttl_stok->row("stok_ins");
                $up_stok_ig = $stok_ig - $round_mtr;
                // pemisah
                $stok_fg2 = $cek_ttl_stok->row("stok_fol_yard");
                $up_stok_fg2 = $stok_fg2 + $round_yrd;
                $stok_ig2 = $cek_ttl_stok->row("stok_ins_yard");
                $up_stok_ig2 = $stok_ig2 - $round_yrd;
                $this->data_model->updatedata('id_stok', $id_stok, 'report_stok', ['stok_ins'=>$up_stok_ig, 'stok_fol'=>$up_stok_fg, 'stok_ins_yard' => $up_stok_fg2, 'stok_fol_yard' => $up_stok_ig2]);
            } elseif ($cek_ttl_stok->num_rows()==0) {
                $stoklikst = [
                    'kode_konstruksi' => $kdkons,
                    'stok_ins' => 0, 
                    'stok_ins_finish' => 0, 'stok_fol' => $round_mtr, 'stok_fol_finish' => 0,
                    'terjual' => 0, 'bs' => 0, 'retur' => 0,
                    'departement' => $loc, 'stok_ins_yard' => 0, 'stok_ins_finish_yard' => 0,
                    'stok_fol_yard' => $round_yrd, 'stok_fol_finish_yard' => 0, 'terjual_yard' => 0,
                    'bs_yard' => 0, 'retur_yard' => 0
                ];
                $this->data_model->saved('report_stok',$stoklikst);
            }
            $this->session->set_flashdata('announce', 'Proses folding grey berhasil. silahkan lengkapi packinglist');
            redirect(base_url('data/folding/fg/'.sha1($kd)."/".$id_proses));
        } elseif ($st_folding=="Finish") {
            //code 1.2-- Jika folding finish maka ambilkan data dari inspect finish
            $cek_if = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kd, 'st_produksi'=>'IF']);
            if($cek_if->num_rows()==1){
                $idproduksi = $cek_if->row("id_produksi");
                $jml_before = $cek_if->row("jumlah_produksi_now");
                $jml_before2 = $cek_if->row("jumlah_produksi_now_yard");
                $jml_now = $jml_before - $round_mtr;
                $jml_now2 = $jml_before2 - $round_yrd;
                $this->data_model->updatedata('id_produksi', $idproduksi, 'tb_produksi', ['jumlah_produksi_now'=>$jml_now, 'jumlah_produksi_now_yard' => $jml_now2]);
            }
            //cek juga di proses
            $cek_if2 = $this->data_model->get_byid('tb_proses_produksi', ['kode_produksi'=>$kd, 'proses_name'=>'IF']);
            if($cek_if2->num_rows()==1){
                $id_proses = $cek_if2->row("id_proses");
                $jml_before = $cek_if2->row("jumlah_akhir");
                $jml_before2 = $cek_if2->row("jumlah_akhir_yard");
                $jml_now = $jml_before - $round_mtr;
                $jml_now2 = $jml_before2 - $round_yrd;
                $this->data_model->updatedata('id_proses', $id_proses, 'tb_proses_produksi', ['jumlah_akhir'=>$jml_now, 'jumlah_akhir_yard'=>$jml_now2]);
            }
            $jml_fg_before = $cek_pros_harian->row("fol_finish");
            $jml_fg_before2 = $cek_pros_harian->row("fol_finish_yard");
            $jml_fg_now = $jml_fg_before + $round_mtr;
            $jml_fg_now2 = $jml_fg_before2 + $round_yrd;
            $this->data_model->updatedata('id_rptd', $id_pos, 'report_produksi_harian', ['fol_finish'=>$jml_fg_now, 'fol_finish_yard'=>$jml_fg_now2]);
            // end code 1.2--
            $cek_ttl_stok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kdkons, 'departement'=>$loc]);
            if($cek_ttl_stok->num_rows() == 1){
                $id_stok = $cek_ttl_stok->row("id_stok");
                $stok_ff = $cek_ttl_stok->row("stok_fol_finish");
                $up_stok_ff = $stok_ff + $round_mtr;
                $stok_if = $cek_ttl_stok->row("stok_ins_finish");
                $up_stok_if = $stok_if - $round_mtr;
                // pemisah
                $stok_ff2 = $cek_ttl_stok->row("stok_fol_finish_yard");
                $up_stok_ff2 = $stok_ff2 + $round_yrd;
                $stok_if2 = $cek_ttl_stok->row("stok_ins_finish_yard");
                $up_stok_if2 = $stok_if2 - $round_yrd;
                $this->data_model->updatedata('id_stok', $id_stok, 'report_stok', ['stok_ins_finish'=>$up_stok_if, 'stok_fol_finish'=>$up_stok_ff, 'stok_ins_finish_yard'=>$up_stok_if2, 'stok_fol_finish_yard'=>$up_stok_ff2]);
            } elseif ($cek_ttl_stok->num_rows()==0) {
                $stoklikst = [
                    'kode_konstruksi' => $kdkons,
                    'stok_ins' => 0, 
                    'stok_ins_finish' => 0, 'stok_fol' => $round_mtr, 'stok_fol_finish' => 0,
                    'terjual' => 0, 'bs' => 0, 'retur' => 0,
                    'departement' => $loc,
                    'stok_ins_yard' => 0,
                    'stok_ins_finish_yard' => 0,
                    'stok_fol_yard' => $round_yrd,
                    'stok_fol_finish_yard' => 0,
                    'terjual_yard' => 0,
                    'bs_yard' => 0,
                    'retur_yard' => 0
                ];
                $this->data_model->saved('report_stok',$stoklikst);
            }
            $this->session->set_flashdata('announce', 'Proses folding finish berhasil. silahkan lengkapi packinglist');
            redirect(base_url('data/folding/ff/'.sha1($kd)."/".$id_proses));
        } else {
            echo "Token expired.";
        }
  } //end proses folding
  
  function up_pkgfolding(){
        $kd = $this->data_model->filter($this->input->post('kd_produksi'));
        $proses = $this->data_model->filter($this->input->post('proses'));
        if($proses=="ff") { $st_folding="Finish"; } else { $st_folding="Grey"; }
        $kd_kons = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kd])->row("kode_konstruksi");
        $loc = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kd])->row("lokasi_produksi");
        $cek_pkg = $this->data_model->get_byid('pkg', ['kode_produksi'=>$kd, 'kode_konstruksi'=>$kd_kons]);
        $numbering = intval($cek_pkg->num_rows()) + 1;
        $numlist = ['kode_produksi'=>$kd, 'kode_konstruksi'=>$kd_kons, 'nolist'=>$numbering, 'st_folding' => $st_folding];
        $this->data_model->saved('pkg', $numlist);
        $id_effected_row = $this->data_model->get_byid('pkg', ['kode_produksi'=>$kd, 'kode_konstruksi'=>$kd_kons])->row("no_pkg");
        $satuan = $this->data_model->filter($this->input->post('satuan'));
        
        $idpkg = $this->input->post('idpkg');
        $noroll = $this->input->post('noroll');
        $tgl = $this->input->post('tgl');
        $ukuran = $this->input->post('ukuran');
        $operator = $this->input->post('operator');
        //cek penambahan non roll
        $annorol = $this->input->post('annorol');
        $antgl = $this->input->post('antgl');
        $anukuran = $this->input->post('anukuran');
        $con_an = count($annorol);
        $sl_mtr = 0; $sl_yrd = 0;
        // end variable non roll
        if($proses=='ff'){
            for ($i=0; $i < count($idpkg); $i++) { 
                if($tgl[$i]!="" AND $ukuran[$i]!="" AND $operator!=""){
                    if($satuan=="Yard"){
                        $ukuran_mtr = floatval($ukuran[$i]) * 0.9144;
                        $round_mtr = round($ukuran_mtr,2);
                        $round_yrd = round($ukuran[$i],2);
                        $folist = [
                            'kode_produksi' => $kd,
                            'asal' => 'ins',
                            'id_asal' => $idpkg[$i],
                            'no_roll' => $noroll[$i],
                            'tgl' => $tgl[$i],
                            'ukuran'=> $round_mtr,
                            'operator' => $operator[$i],
                            'st_folding' => 'Finish',
                            'ukuran_now' => $round_mtr,
                            'ukuran_yard' => $round_yrd,
                            'ukuran_now_yard' => $round_yrd,
                            'id_effected_row' => $id_effected_row
                        ];
                        $this->data_model->saved('new_tb_pkg_fol', $folist);
                        $cek_pkg_before = $this->data_model->get_byid('new_tb_pkg_ins', ['id_pkgins'=>$idpkg[$i]])->row("ukuran_now");
                        $cek_pkg_before2 = $this->data_model->get_byid('new_tb_pkg_ins', ['id_pkgins'=>$idpkg[$i]])->row("ukuran_now_yard");
                        $up_ukuran = $cek_pkg_before - $round_mtr;
                        $up_ukuran2 = $cek_pkg_before2 - $round_yrd;
                        $this->data_model->updatedata('id_pkgins', $idpkg[$i], 'new_tb_pkg_ins', ['ukuran_now'=>$up_ukuran, 'ukuran_now_yard' => $up_ukuran2]);
                        $cek_stok_lama = $this->data_model->get_byid('new_tb_pkg_ins', ['id_pkgins'=>$idpkg[$i]])->row("operator");
                        if($cek_stok_lama=="SL"){
                            $cek_sl_insfi = $this->data_model->get_byid('report_stok_lama', ['kode_konstruksi'=>$kd_kons, 'departement'=>$loc]);
                            $sl_if = $cek_sl_insfi->row("ins_finish");
                            $sl_folf = $cek_sl_insfi->row("fol_finish");
                            $sl_ify = $cek_sl_insfi->row("ins_finish_yard");
                            $sl_folfy = $cek_sl_insfi->row("fol_finish_yard");
                            $up_slif = $sl_if - $round_mtr;
                            $up_slify = $sl_ify - $round_yrd;
                            $up_slfolfy = $sl_folfy + $round_yrd;
                            $up_slfolf = $sl_folf + $round_mtr;
                            $this->data_model->updatedata('kode_konstruksi',$kd_kons, 'report_stok_lama', ['ins_finish'=>round($up_slif,2), 'fol_finish'=>round($up_slfolf,2), 'ins_finish_yard'=>round($up_slify,2), 'fol_finish_yard'=>round($up_slfolfy,2)]);
                        }
                    } else {
                        $ukuran_yrd = floatval($ukuran[$i]) / 0.9144;
                        $round_mtr = round($ukuran[$i],2);
                        $round_yrd = round($ukuran_yrd,2);
                        $folist = [
                            'kode_produksi' => $kd,
                            'asal' => 'ins',
                            'id_asal' => $idpkg[$i],
                            'no_roll' => $noroll[$i],
                            'tgl' => $tgl[$i],
                            'ukuran'=> $round_mtr,
                            'operator' => $operator[$i],
                            'st_folding' => 'Finish',
                            'ukuran_now' => $round_mtr,
                            'ukuran_yard' => $round_yrd,
                            'ukuran_now_yard' => $round_yrd,
                            'id_effected_row' => $id_effected_row
                        ];
                        $this->data_model->saved('new_tb_pkg_fol', $folist);
                        $cek_pkg_before = $this->data_model->get_byid('new_tb_pkg_ins', ['id_pkgins'=>$idpkg[$i]])->row("ukuran_now");
                        $cek_pkg_before2 = $this->data_model->get_byid('new_tb_pkg_ins', ['id_pkgins'=>$idpkg[$i]])->row("ukuran_now_yard");
                        $up_ukuran = $cek_pkg_before - $round_mtr;
                        $up_ukuran2 = $cek_pkg_before2 - $round_yrd;
                        $this->data_model->updatedata('id_pkgins', $idpkg[$i], 'new_tb_pkg_ins', ['ukuran_now'=>$up_ukuran, 'ukuran_now_yard' => $up_ukuran2]);
                        $cek_stok_lama = $this->data_model->get_byid('new_tb_pkg_ins', ['id_pkgins'=>$idpkg[$i]])->row("operator");
                        if($cek_stok_lama=="SL"){
                            $cek_sl_insfi = $this->data_model->get_byid('report_stok_lama', ['kode_konstruksi'=>$kd_kons, 'departement'=>$loc]);
                            $sl_if = $cek_sl_insfi->row("ins_finish");
                            $sl_folf = $cek_sl_insfi->row("fol_finish");
                            $sl_ify = $cek_sl_insfi->row("ins_finish_yard");
                            $sl_folfy = $cek_sl_insfi->row("fol_finish_yard");
                            $up_slif = $sl_if - $round_mtr;
                            $up_slify = $sl_ify - $round_yrd;
                            $up_slfolfy = $sl_folfy + $round_yrd;
                            $up_slfolf = $sl_folf + $round_mtr;
                            $this->data_model->updatedata('kode_konstruksi',$kd_kons, 'report_stok_lama', ['ins_finish'=>round($up_slif,2), 'fol_finish'=>round($up_slfolf,2), 'ins_finish_yard'=>round($up_slify,2), 'fol_finish_yard'=>round($up_slfolfy,2)]);
                        }
                    }
                    
                }
            }
            for($a=0; $a<$con_an; $a++){
                if($annorol[$a]!="" AND $antgl[$a]!="" AND $anukuran!=""){
                    if($satuan=="Yard"){
                        $ukr = floatval($anukuran[$a]);
                        $ukr_meter = $ukr * 0.9144;
                        $dtlistan = [
                            'kode_produksi' => $kd,
                            'asal' => 'ins',
                            'id_asal' => 0,
                            'no_roll' => $annorol[$a],
                            'tgl' => $antgl[$a],
                            'ukuran'=> round($ukr_meter,2),
                            'operator' => 'SL',
                            'st_folding' => 'Finish',
                            'ukuran_now' => round($ukr_meter,2),
                            'ukuran_yard' => round($ukr,2),
                            'ukuran_now_yard' => round($ukr,2),
                            'id_effected_row' => $id_effected_row
                        ];
                        $sl_mtr = $sl_mtr + $ukr_meter;
                        $sl_yrd = $sl_yrd + $ukr;
                    } else {
                        $ukr = floatval($anukuran[$a]);
                        $ukr_yard = $ukr / 0.9144;
                        $dtlistan = [
                            'kode_produksi' => $kd,
                            'asal' => 'ins',
                            'id_asal' => 0,
                            'no_roll' => $annorol[$a],
                            'tgl' => $antgl[$a],
                            'ukuran'=> round($ukr,2),
                            'operator' => 'SL',
                            'st_folding' => 'Finish',
                            'ukuran_now' => round($ukr,2),
                            'ukuran_yard' => round($ukr_yard,2),
                            'ukuran_now_yard' => round($ukr_yard,2),
                            'id_effected_row' => $id_effected_row
                        ];
                        $sl_mtr = $sl_mtr + $ukr;
                        $sl_yrd = $sl_yrd + $ukr_yard;
                    }
                    $this->data_model->saved('new_tb_pkg_fol', $dtlistan);
                    $cek_sl = $this->data_model->get_byid('report_stok_lama', ['kode_konstruksi'=>$kd_kons, 'departement'=>$loc]);
                    if($cek_sl->num_rows()==0){
                        $sllist = [
                            'kode_konstruksi' => $kd_kons,
                            'ins_finish' => 0,
                            'fol_grey' => 0,
                            'fol_finish' => round($sl_mtr,2),
                            'terjual' => 0,
                            'ins_finish_yard' => 0,
                            'fol_grey_yard' => 0,
                            'fol_finish_yard' => round($sl_yrd,2),
                            'terjual_yard' => 0,
                            'departement' => $loc
                        ];
                        $this->data_model->saved('report_stok_lama', $sllist);
                    } else {
                        $idsl = $cek_sl->row("id_sl");
                        $dt_slmtr = $cek_sl->row('fol_finish');
                        $dt_slyrd = $cek_sl->row('fol_finish_yard');
                        $up_slmtr = $dt_slmtr + round($sl_mtr,2);
                        $up_slyrd = $dt_slyrd + round($sl_yrd,2);
                        $this->data_model->updatedata('id_sl',$idsl,'report_stok_lama',['fol_finish'=>round($up_slmtr,2), 'fol_finish_yard'=>round($up_slyrd,2)]);
                    }
                }
            }
            $txt = "Proses folding finish dengan kode produksi (<strong>".$kd."</strong>)";
            $dtlog = [
                'id_user' => $this->session->userdata('id'), 'kode_produksi' => $kd, 'log' => $txt
            ];
            $this->data_model->saved('log_produksi', $dtlog);
            $this->data_model->saved('log_program', ['id_user'=>$this->session->userdata('id'), 'log'=>$txt]);
            $this->session->set_flashdata('announce', 'Proses folding finish berhasil');
            redirect(base_url('input-produksi'));
        } else {
            for ($i=0; $i < count($idpkg); $i++) { 
                if($tgl[$i]!="" AND $ukuran[$i]!="" AND $operator!=""){
                    if($satuan=="Yard"){
                        $nilai_mtr = floatval($ukuran[$i]) * 0.9144;
                        $round_mtr = round($nilai_mtr,2);
                        $round_yrd = round($ukuran[$i],2);
                        $folist = [
                            'kode_produksi' => $kd,
                            'asal' => 'list',
                            'id_asal' => $idpkg[$i],
                            'no_roll' => $noroll[$i],
                            'tgl' => $tgl[$i],
                            'ukuran'=> $round_mtr,
                            'operator' => $operator[$i],
                            'st_folding' => 'Grey',
                            'ukuran_now' => $round_mtr,
                            'ukuran_yard' => $round_yrd,
                            'ukuran_now_yard' => $round_yrd,
                            'id_effected_row' => $id_effected_row
                        ];
                        $this->data_model->saved('new_tb_pkg_fol', $folist);
                        $cek_pkg_before = $this->data_model->get_byid('new_tb_pkg_list', ['id_pkg'=>$idpkg[$i]])->row("ukuran_now");
                        $cek_pkg_before2 = $this->data_model->get_byid('new_tb_pkg_list', ['id_pkg'=>$idpkg[$i]])->row("ukuran_now_yard");
                        $up_ukuran = $cek_pkg_before - $round_mtr;
                        $up_ukuran2 = $cek_pkg_before2 - $round_yrd;
                        $this->data_model->updatedata('id_pkg', $idpkg[$i], 'new_tb_pkg_list', ['ukuran_now'=>$up_ukuran, 'ukuran_now_yard' => $up_ukuran2]);
                    } elseif ($satuan=="Meter") {
                        $nilai_yrd = floatval($ukuran[$i]) / 0.9144;
                        $round_mtr = round($ukuran[$i],2);
                        $round_yrd = round($nilai_yrd);
                        $folist = [
                            'kode_produksi' => $kd,
                            'asal' => 'list',
                            'id_asal' => $idpkg[$i],
                            'no_roll' => $noroll[$i],
                            'tgl' => $tgl[$i],
                            'ukuran'=> $round_mtr,
                            'operator' => $operator[$i],
                            'st_folding' => 'Grey',
                            'ukuran_now' => $round_mtr,
                            'ukuran_yard' => $round_yrd,
                            'ukuran_now_yard' => $round_yrd,
                            'id_effected_row' => $id_effected_row
                        ];
                        $this->data_model->saved('new_tb_pkg_fol', $folist);
                        $cek_pkg_before = $this->data_model->get_byid('new_tb_pkg_list', ['id_pkg'=>$idpkg[$i]])->row("ukuran_now");
                        $cek_pkg_before2 = $this->data_model->get_byid('new_tb_pkg_list', ['id_pkg'=>$idpkg[$i]])->row("ukuran_now_yard");
                        $up_ukuran = $cek_pkg_before - $round_mtr;
                        $up_ukuran2 = $cek_pkg_before2 - $round_yrd;
                        $this->data_model->updatedata('id_pkg', $idpkg[$i], 'new_tb_pkg_list', ['ukuran_now'=>$up_ukuran, 'ukuran_now_yard' => $up_ukuran2]);
                    }
                    
                }
            }
            for($a=0; $a<$con_an; $a++){
                if($annorol[$a]!="" AND $antgl[$a]!="" AND $anukuran!=""){
                    if($satuan=="Yard"){
                        $ukr = floatval($anukuran[$a]);
                        $ukr_meter = $ukr * 0.9144;
                        $dtlistan = [
                            'kode_produksi' => $kd,
                            'asal' => 'ins',
                            'id_asal' => 0,
                            'no_roll' => $annorol[$a],
                            'tgl' => $antgl[$a],
                            'ukuran'=> round($ukr_meter,2),
                            'operator' => 'SL',
                            'st_folding' => 'Grey',
                            'ukuran_now' => round($ukr_meter,2),
                            'ukuran_yard' => round($ukr,2),
                            'ukuran_now_yard' => round($ukr,2),
                            'id_effected_row' => $id_effected_row
                        ];
                        $sl_mtr = $sl_mtr + $ukr_meter;
                        $sl_yrd = $sl_yrd + $ukr;
                    } else {
                        $ukr = floatval($anukuran[$a]);
                        $ukr_yard = $ukr / 0.9144;
                        $dtlistan = [
                            'kode_produksi' => $kd,
                            'asal' => 'ins',
                            'id_asal' => 0,
                            'no_roll' => $annorol[$a],
                            'tgl' => $antgl[$a],
                            'ukuran'=> round($ukr,2),
                            'operator' => 'SL',
                            'st_folding' => 'Grey',
                            'ukuran_now' => round($ukr,2),
                            'ukuran_yard' => round($ukr_yard,2),
                            'ukuran_now_yard' => round($ukr_yard,2),
                            'id_effected_row' => $id_effected_row
                        ];
                        $sl_mtr = $sl_mtr + $ukr;
                        $sl_yrd = $sl_yrd + $ukr_yard;
                    }
                    $this->data_model->saved('new_tb_pkg_fol', $dtlistan);
                    $cek_sl = $this->data_model->get_byid('report_stok_lama', ['kode_konstruksi'=>$kd_kons, 'departement'=>$loc]);
                    if($cek_sl->num_rows()==0){
                        $sllist = [
                            'kode_konstruksi' => $kd_kons,
                            'ins_finish' => 0,
                            'fol_grey' => round($sl_mtr,2),
                            'fol_finish' => 0,
                            'terjual' => 0,
                            'ins_finish_yard' => 0,
                            'fol_grey_yard' => round($sl_yrd,2),
                            'fol_finish_yard' => 0,
                            'terjual_yard' => 0,
                            'departement' => $loc
                        ];
                        $this->data_model->saved('report_stok_lama', $sllist);
                    } else {
                        $idsl = $cek_sl->row("id_sl");
                        $dt_slmtr = $cek_sl->row('fol_grey');
                        $dt_slyrd = $cek_sl->row('fol_grey_yard');
                        $up_slmtr = $dt_slmtr + round($sl_mtr,2);
                        $up_slyrd = $dt_slyrd + round($sl_yrd,2);
                        $this->data_model->updatedata('id_sl',$idsl,'report_stok_lama',['fol_grey'=>round($up_slmtr,2), 'fol_grey_yard'=>round($up_slyrd,2)]);
                    }
                }
            }
            $txt = "Proses folding grey dengan kode produksi (<strong>".$kd."</strong>)";
            $dtlog = [
                'id_user' => $this->session->userdata('id'), 'kode_produksi' => $kd, 'log' => $txt
            ];
            $this->data_model->saved('log_produksi', $dtlog);
            $this->data_model->saved('log_program', ['id_user'=>$this->session->userdata('id'), 'log'=>$txt]);
            $this->session->set_flashdata('announce', 'Proses folding grey berhasil');
            redirect(base_url('input-produksi'));
        }
  } //end proses up_pkg_folding

  function insert_pkg(){
        $no_paket = $this->input->post('nopaket');
        $jnspaket = $this->input->post('jnspaket');
        $kdroll = $this->input->post('kdroll');
        $ttlpnjng = $this->input->post('ttlpnjng');
        $cn = count($kdroll);
        $this->data_model->updatedata('kd',$no_paket,'new_tb_packinglist',['jumlah_roll'=>$cn,'ttl_panjang'=>$ttlpnjng]);
        $dt_masuk = 0; $dt_tidak_masuk = 0;
        for ($i=0; $i <$cn ; $i++) { 
            //echo "".$kdroll[$i]."<br>";
            if($jnspaket=='y'){
                $this->data_model->updatedata('kode_roll',$kdroll[$i],'data_fol',['posisi'=>$no_paket]);
            } else {
                $this->data_model->updatedata('kode_roll',$kdroll[$i],'data_ig',['loc_now'=>$no_paket]);
            }
        }
        
        $txt = "Berhasil menambahkan ".$cn." roll kedalam paket.";
        $this->session->set_flashdata('announce', $txt);
        redirect(base_url('packing-list'));
  } //end

  function delpaket(){
     $no_paket = $this->input->post('id');
     $jnspaket = $this->input->post('jnspaket');
     $loc = $this->input->post('loc');
     $this->data_model->delete('new_tb_packinglist', 'kd', $no_paket);
     if($jnspaket=="y"){
        $this->data_model->updatedata('posisi',$no_paket,'data_fol',['posisi'=>$loc]);
     } else {
        $this->data_model->updatedata('loc_now',$no_paket,'data_ig',['loc_now'=>$loc]);
     }
     $this->data_model->updatedata('lokasi',$no_paket,'data_fol_lama', ['lokasi'=>'Samatex']);
     $this->data_model->updatedata('posisi',$no_paket,'data_stok_lama', ['posisi'=>'Samatex']);
     $this->data_model->delete('new_tb_isi', 'kd', $no_paket);
     $this->session->set_flashdata('gagal', 'Berhasil menghapus paket');
     redirect(base_url('packing-list'));
  } //end
  function editpkg_rjs(){
        $token = $this->input->post('token');
        $kdroll = $this->input->post('roll');
        $sertakan = $this->input->post('sertakan');
        for ($i=0; $i < count($kdroll); $i++) { 
            if($sertakan[$i]=="0"){
                $this->data_model->updatedata('kode_roll',$kdroll[$i],'data_ig',['loc_now'=>'RJS']);
            }
        } //end for
        $jumlah_roll = $this->data_model->get_byid('data_ig', ['loc_now'=>$token])->num_rows();
        $panjang_roll = $this->db->query("SELECT SUM(ukuran_ori) as ukr FROM data_ig WHERE loc_now='$token'")->row("ukr");
        $this->data_model->updatedata('kd',$token,'new_tb_packinglist', ['jumlah_roll'=>$jumlah_roll,'ttl_panjang'=>round($panjang_roll)]);
        redirect(base_url('packing-list'));
  } //end
  function editpkg(){
     $token = $this->input->post('token');
     $jns = $this->input->post('jnspkt');
     $kdroll = $this->input->post('roll');
     $jmlkdroll = $this->input->post('jumlah_roll_all');
     $pjg = $this->input->post('new_ttlpjg');
     $sertakan = $this->input->post('sertakan');
     $locuser = $this->session->userdata('departement');
     if($token!="" AND $jns!=""){

        $this->data_model->updatedata('kd',$token,'new_tb_packinglist',['ttl_panjang'=>$pjg]);
        for ($i=0; $i < count($kdroll); $i++) { 
            if($sertakan[$i]==0){
                if($jns=='y'){
                    $this->data_model->updatedata('kode_roll',$kdroll[$i],'data_fol',['posisi'=>$locuser]);
                    
                } else {
                    $this->data_model->updatedata('kode_roll',$kdroll[$i],'data_ig',['loc_now'=>$locuser]);
                }
                $cek_roll = $this->data_model->get_byid('new_tb_packinglist',['kd'=>$token])->row("jumlah_roll");
                $new_roll = intval($cek_roll) - 1;
                $this->data_model->updatedata('kd',$token,'new_tb_packinglist',['jumlah_roll'=>$jmlkdroll]);
            }
        }
        $jmlroll1 = $this->data_model->get_byid('data_fol', ['posisi'=>$token])->num_rows();
        $jmlroll2 = $this->data_model->get_byid('data_fol_lama', ['lokasi'=>$token])->num_rows();
        $jmlroll3 = $this->data_model->get_byid('data_stok_lama', ['posisi'=>$token])->num_rows();
        $total_roll = $jmlroll1 + $jmlroll2 + $jmlroll3;
        $this->data_model->updatedata('kd',$token,'new_tb_packinglist',['jumlah_roll'=>$total_roll]);
        $ukuran1 = $this->db->query("SELECT SUM(ukuran) as nilai FROM data_fol WHERE posisi='$token'")->row("nilai");
        $ukuran2 = $this->db->query("SELECT SUM(ukuran_asli) as nilai FROM data_fol_lama WHERE lokasi='$token'")->row("nilai");
        $ukuran3 = $this->db->query("SELECT SUM(ukuran) as nilai FROM data_stok_lama WHERE posisi='$token'")->row("nilai");
        $total_ukuran = floatval($ukuran1) + floatval($ukuran2) + floatval($ukuran3);
        $this->data_model->updatedata('kd',$token,'new_tb_packinglist',['ttl_panjang'=>round($total_ukuran,2)]);

        $panjang_skr = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$token])->row("ttl_panjang");
        $roll_skr = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$token])->row("jumlah_roll");
        $panjang_skr = floatval($panjang_skr);
        if($locuser=="Samatex"){
        $cekimport = $this->data_model->get_byid('new_tb_isi', ['kd'=>$token]);
        if($cekimport->num_rows() > 0){
            $total_panjang=0;
            foreach($cekimport->result() as $val):
                $total_panjang+= floatval($val->ukuran);
            endforeach;
            $total_ukuran2 = $total_panjang + $panjang_skr;
            $jumlah_roll = $roll_skr + $cekimport->num_rows();
            $this->data_model->updatedata('kd',$token,'new_tb_packinglist',['jumlah_roll'=>$jumlah_roll,'ttl_panjang'=>round($total_ukuran2,2)]);
        }
        }
        $this->session->set_flashdata('announce', 'Update paket berhasil');
        //redirect(base_url('data/kode/'.$token));
     } else {
        $this->session->set_flashdata('gagal', 'Update paket gagal');
        echo "gagal";
        redirect(base_url('data/kode/'.$token));
     }
  } //end
  

}
?>
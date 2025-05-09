<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetakstx extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      $this->load->library('pdf');
      date_default_timezone_set("Asia/Jakarta");
    
  }
  function index(){
    echo "";
  }
  function packinglist(){
    $token = $this->uri->segment(3);
    $wicode = $this->uri->segment(4);
    $ukrfont = $this->uri->segment(5);
    if($ukrfont==""){
      $zise = 10;
    } else {
      $zise = $ukrfont;
    }
    $ar = array(
      '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    );
    $cek = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$token]);
    if($cek->num_rows() == 1){
        $kode_roll = array();
        $ukuran = array();
        $idfrom = array();
        $satuan = "";
        // $cek_kode1 = $this->data_model->get_byid('data_fol', ['posisi'=>$token]);
        // $cek_kode2 = $this->data_model->get_byid('data_fol_lama', ['lokasi'=>$token]);
        // $cek_kode3 = $this->data_model->get_byid('data_stok_lama', ['posisi'=>$token]);
        // $cek_kode4 = $this->data_model->get_byid('kode_roll_outsite', ['kd'=>$token]);
        // $cek_kode5 = $this->data_model->get_byid('data_ig', ['loc_now'=>$token]);
        $cek_kode6 = $this->data_model->get_byid('new_tb_isi', ['kd'=>$token]);
        // if($cek_kode1->num_rows() > 0){
        //     foreach($cek_kode1->result() as $val):
        //         $kode_roll[] = $val->kode_roll;
        //         $ukuran[] = $val->ukuran;
        //         $idfrom[] = "1";
        //     endforeach;
        // }
        // if($cek_kode2->num_rows() > 0){
        //     foreach($cek_kode2->result() as $val):
        //         $kode_roll[] = $val->kode_roll;
        //         $ukuran[] = $val->ukuran_asli;
        //         $idfrom[] = "2";
        //     endforeach;
        // }
        // if($cek_kode3->num_rows() > 0){
        //     foreach($cek_kode3->result() as $val):
        //         $kode_roll[] = $val->id_sl;
        //         $ukuran[] = $val->ukuran;
        //         $idfrom[] = "3";
        //     endforeach;
        // }
        // if($cek_kode4->num_rows() > 0){
        //     foreach($cek_kode4->result() as $val):
        //         $kode_roll[] = $val->kode_roll;
        //         $ukuran_ori = $this->data_model->get_byid('data_ig',['kode_roll'=>$val->kode_roll])->row("ukuran_ori");
        //         $ukuran[] = $ukuran_ori;
        //         $idfrom[] = $val->fromm;
        //     endforeach;
        // }
        // if($cek_kode5->num_rows() > 0){
        //   foreach($cek_kode5->result() as $val):
        //       $kode_roll[] = $val->kode_roll;
        //       $ukuran[] = $val->ukuran_ori;
        //       $idfrom[] = "4";
        //   endforeach;
        // }
        
        if($cek_kode6->num_rows() > 0){
          foreach($cek_kode6->result() as $val):
              $kode_roll[] = $val->kode;
              $ukuran[] = $val->ukuran;
              $idfrom[] = "5";
              $satuan = $val->satuan;
          endforeach;
        }
        // for ($zs=0; $zs <188 ; $zs++) { 
        //     $kode_roll[] = "te";
        //     $ukuran[] = "90";
        //     $idfrom[] = "1";
        // }
        $row = $cek->row_array();
        if($row['kode_konstruksi']=="Samatex A"){
           $viewkons = "Samatex A / L : 120 CM";
        } elseif($row['kode_konstruksi']=="Samatex B"){
           $viewkons = "Samatex B / L : 120 CM";
        } elseif($row['kode_konstruksi']=="L90A") {
           $viewkons = "L 90 A";
        } elseif($row['kode_konstruksi']=="L90") {
            $viewkons = "L 90";
        } elseif($row['kode_konstruksi']=="L150B") {
            $viewkons = "L 150 B";
        } elseif($row['kode_konstruksi']=="L150") {
            $viewkons = "L 150 CM";
        } elseif($row['kode_konstruksi']=="L135") {
            $viewkons = "L 135 CM";
        } elseif($row['kode_konstruksi']=="L120T") {
          $viewkons = "L 120 T";
        } else {
            $viewkons = $row['kode_konstruksi'];
        }
        $jml_pkt = count($kode_roll);
        $ex = explode('-', $row['tanggal_dibuat']);
              $printTgl = $ex[2]." ".$ar[$ex[1]]." ".$ex[0];
              $pdf = new FPDF('p','mm','A4');
              // membuat halaman baru
              $pdf->AddPage();
              // setting jenis font yang akan digunakan
              $pdf->SetTitle('Packing List - '.$token.'');
      
              $pdf->SetFont('Arial','B',14);
              $pdf->Cell(0, 8, 'PACKING LIST', 1, 0, 'C' );
              $pdf->Ln(8);
              $pdf->Cell(0, 18, '', 1, 0, 'C' );
              $pdf->SetFont('Arial','',$zise);
              $pdf->Text(13,23, 'No');
              $pdf->Text(13,28, 'Jenis Kain');
              $pdf->Text(13,33, 'Kepada');
              $pdf->Text(35,23, ':  '.$row['no_sj'].'');
              $pdf->Text(35,28, ':  '.$viewkons.'');
              if($row['kepada']=="cus"){
                $idcus = $this->data_model->get_byid('surat_jalan', ['no_sj'=>$row['no_sj']])->row("id_customer");
                $nama_cus = $this->data_model->get_byid('dt_konsumen', ['id_konsumen'=>$idcus])->row("nama_konsumen");
              } else {
                $nama_cus = $row['kepada'];
              }
              $pdf->Text(35,33, ':  '.$nama_cus);
              $pdf->Text(135,23, 'Tanggal');
              $pdf->Text(135,28, 'Surat Jalan');
              $pdf->Text(165,23, ':  '.$printTgl.'');
              $pdf->Text(165,28, ':  '.$row['no_sj'].'');
              $pdf->Ln(18);
              $pdf->setFillColor(156, 153, 152); 
              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
              $pdf->setFillColor(224, 224, 222); 
              $pdf->Ln(5);
              $ttl1=0; $ttl2=0; $ttl3=0; $ttl4=0;
              for ($i=0; $i < 40 ; $i++) { 
              // start konten 1
              if(isset($kode_roll[$i])){
                $num=$i+1;
                $pdf->Cell(8, 5, $num, 1, 0, 'C',1 );
                if($idfrom[$i]=="3"){
                    $pdf->Cell(15, 5, '', 1, 0, 'C' );
                } else {
                    $printkode = $kode_roll[$i]=='null' ? '':$kode_roll[$i];
                    $printkode2 = $wicode=='0' ? '':$printkode;
                    $pdf->Cell(15, 5, $printkode2, 1, 0, 'C' );
                }
                $pdf->Cell(12.47, 5, $ukuran[$i], 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $ttl1+=$ukuran[$i];
              } else {
                $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C' );
                $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
              }
              //-- start konten 2
              $kis = $i + 41;
              $nkis = $kis - 1;
              if(isset($kode_roll[$nkis])){
                $pdf->Cell(8, 5, $kis, 1, 0, 'C',1 );
                if($idfrom[$nkis]=="3"){
                    $pdf->Cell(15, 5, '', 1, 0, 'C' );
                } else {
                    $printkode = $kode_roll[$nkis]=='null' ? '':$kode_roll[$nkis];
                    $printkode2 = $wicode=='0' ? '':$printkode;
                    $pdf->Cell(15, 5, $printkode2, 1, 0, 'C' );
                }
                $pdf->Cell(12.47, 5, $ukuran[$nkis], 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $ttl2+=$ukuran[$nkis];
              } else {
                $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C' );
                $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
              }
              
              //-- start konten 3
              $kis2 = $i + 81;
              $nkis2 = $kis2 - 1;
              if(isset($kode_roll[$nkis2])){
                $pdf->Cell(8, 5, $kis2, 1, 0, 'C',1 );
                if($idfrom[$nkis2]=="3"){
                    $pdf->Cell(15, 5, '', 1, 0, 'C' );
                } else {
                    $printkode = $kode_roll[$nkis2]=='null' ? '':$kode_roll[$nkis2];
                    $printkode2 = $wicode=='0' ? '':$printkode;
                    $pdf->Cell(15, 5, $printkode2, 1, 0, 'C' );
                }
                $pdf->Cell(12.47, 5, $ukuran[$nkis2], 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $ttl3+=$ukuran[$nkis2];
              } else {
                $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C' );
                $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
              }
              
              //-- start konten 4
              $kis3 = $i + 121;
              $nkis3 = $kis3 - 1;
              if(isset($kode_roll[$nkis3])){
                $pdf->Cell(8, 5, $kis3, 1, 0, 'C',1 );
                if($idfrom[$nkis3]=="3"){
                    $pdf->Cell(15, 5, '', 1, 0, 'C' );
                } else {
                  $printkode = $kode_roll[$nkis3]=='null' ? '':$kode_roll[$nkis3];
                    $printkode2 = $wicode=='0' ? '':$printkode;
                    $pdf->Cell(15, 5, $printkode2, 1, 0, 'C' );
                }
                $pdf->Cell(12.47, 5, $ukuran[$nkis3], 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $ttl4+=$ukuran[$nkis3];
              } else {
                $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C' );
                $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
              }
              
              //--
              $pdf->Ln(5);
              }

              $pdf->setFillColor(156, 153, 152);
              $pdf->SetFont('Arial','B',8);
              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, $ttl1==0?'':$ttl1, 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, $ttl2==0?'':$ttl2, 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, $ttl3==0?'':$ttl3, 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, $ttl4==0?'':$ttl4, 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
              $pdf->SetFont('Arial','B',10);
              $grttl = $ttl1 + $ttl2 + $ttl3 + $ttl4;
              $format_grttl = number_format($grttl,2,",",".");
              if($jml_pkt > 0 AND $jml_pkt < 161){
                $pdf->Text(10,255, 'GRAND TOTAL : '.$format_grttl.' '.$satuan.'');
              } else {
                $pdf->Text(10,256, 'TOTAL : '.$format_grttl.'');
              }
              $pdf->SetFont('Arial','',10);
              if($jml_pkt<161){ $pdf->Text(165,256, 'Pengirim'); }
              $pdf->Ln(35);
              $pdf->Cell(145);
              if($jml_pkt<161){ $pdf->Cell(37, 0, '', 1, 0, 'C' ); }
              
            //--- page 2
            if($jml_pkt>160){
            $pdf->AddPage();
            $pdf->SetFont('Arial','',$zise);
            $pdf->Ln(18);
              $pdf->setFillColor(156, 153, 152); 
              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
              $pdf->setFillColor(224, 224, 222); 
              $pdf->Ln(5);
              $ttl5=0; $ttl6=0; $ttl7=0; $ttl8=0;
              for ($i=0; $i < 40 ; $i++) { 
              // start konten 1
              $kis0 = $i + 161;
              $nkis0 = $kis0 - 1;
              if(isset($kode_roll[$nkis0])){
                $pdf->Cell(8, 5, $kis0, 1, 0, 'C',1 );
                if($idfrom[$nkis0]=="3"){
                    $pdf->Cell(15, 5, '', 1, 0, 'C' );
                } else {
                    $pdf->Cell(15, 5, $kode_roll[$nkis0], 1, 0, 'C' );
                }
                $pdf->Cell(12.47, 5, $ukuran[$nkis0], 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $ttl5+=$ukuran[$nkis0];
              } else {
                $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C' );
                $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
              }
              //-- start konten 2
              $kis = $i + 201;
              $nkis = $kis - 1;
              if(isset($kode_roll[$nkis])){
                $pdf->Cell(8, 5, $kis, 1, 0, 'C',1 );
                if($idfrom[$nkis]=="3"){
                    $pdf->Cell(15, 5, '', 1, 0, 'C' );
                } else {
                    $pdf->Cell(15, 5, $kode_roll[$nkis], 1, 0, 'C' );
                }
                $pdf->Cell(12.47, 5, $ukuran[$nkis], 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $ttl6+=$ukuran[$nkis];
              } else {
                $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C' );
                $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
              }
              
              //-- start konten 3
              $kis2 = $i + 241;
              $nkis2 = $kis2 - 1;
              if(isset($kode_roll[$nkis2])){
                $pdf->Cell(8, 5, $kis2, 1, 0, 'C',1 );
                if($idfrom[$nkis2]=="3"){
                    $pdf->Cell(15, 5, '', 1, 0, 'C' );
                } else {
                    $pdf->Cell(15, 5, $kode_roll[$nkis2], 1, 0, 'C' );
                }
                $pdf->Cell(12.47, 5, $ukuran[$nkis2], 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $ttl7+=$ukuran[$nkis2];
              } else {
                $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C' );
                $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
              }
              
              //-- start konten 4
              $kis3 = $i + 281;
              $nkis3 = $kis3 - 1;
              if(isset($kode_roll[$nkis3])){
                $pdf->Cell(8, 5, $kis3, 1, 0, 'C',1 );
                if($idfrom[$nkis3]=="3"){
                    $pdf->Cell(15, 5, '', 1, 0, 'C' );
                } else {
                    $pdf->Cell(15, 5, $kode_roll[$nkis3], 1, 0, 'C' );
                }
                $pdf->Cell(12.47, 5, $ukuran[$nkis3], 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $ttl8+=$ukuran[$nkis3];
              } else {
                $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C' );
                $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
              }
              
              //--
              $pdf->Ln(5);
              }

              $pdf->setFillColor(156, 153, 152);
              $pdf->SetFont('Arial','B',8);
              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, $ttl5==0?'':$ttl5, 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, $ttl6==0?'':$ttl6, 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, $ttl7==0?'':$ttl7, 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, $ttl8==0?'':$ttl8, 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
            
              $pdf->SetFont('Arial','B',10);
              $grttl2 = $ttl5 + $ttl6 + $ttl7 + $ttl8;
              $format_grttl2 = number_format($grttl2,2,",",".");
              $pdf->Text(10,250, 'TOTAL : '.$format_grttl2.'');
              if($jml_pkt > 160 AND $jml_pkt < 321){
                $all_grand_total =  $grttl2 + $grttl;
                $format_grttl_all = number_format($all_grand_total,2,",",".");
                $pdf->Text(10,255, 'GRAND TOTAL : '.$format_grttl_all.' '.$satuan.'');
              }
              $pdf->SetFont('Arial','',10);
              if($jml_pkt<320){ $pdf->Text(165,250, 'Pengirim'); }
              $pdf->Ln(35);
              $pdf->Cell(145);
              if($jml_pkt<320){ $pdf->Cell(37, 0, '', 1, 0, 'C' ); }
            }
            //--- end page 2
            //--- page 3
            if($jml_pkt>320){
              $pdf->AddPage();
              $pdf->SetFont('Arial','',8);
              $pdf->Ln(18);
                $pdf->setFillColor(156, 153, 152); 
                $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
                $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
                $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
                $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
                $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
                $pdf->setFillColor(224, 224, 222); 
                $pdf->Ln(5);
                $ttl9=0; $ttl10=0; $ttl11=0; $ttl12=0;
                for ($i=0; $i < 40 ; $i++) { 
                // start konten 1
                $kis0 = $i + 321;
                $nkis0 = $kis0 - 1;
                if(isset($kode_roll[$nkis0])){
                  $pdf->Cell(8, 5, $kis0, 1, 0, 'C',1 );
                  if($idfrom[$nkis0]=="3"){
                      $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  } else {
                      $pdf->Cell(15, 5, $kode_roll[$nkis0], 1, 0, 'C' );
                  }
                  $pdf->Cell(12.47, 5, $ukuran[$nkis0], 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                  $ttl9+=$ukuran[$nkis0];
                } else {
                  $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                  $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                }
                //-- start konten 2
                $kis = $i + 361;
                $nkis = $kis - 1;
                if(isset($kode_roll[$nkis])){
                  $pdf->Cell(8, 5, $kis, 1, 0, 'C',1 );
                  if($idfrom[$nkis]=="3"){
                      $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  } else {
                      $pdf->Cell(15, 5, $kode_roll[$nkis], 1, 0, 'C' );
                  }
                  $pdf->Cell(12.47, 5, $ukuran[$nkis], 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                  $ttl10+=$ukuran[$nkis];
                } else {
                  $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                  $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                }
                
                //-- start konten 3
                $kis2 = $i + 401;
                $nkis2 = $kis2 - 1;
                if(isset($kode_roll[$nkis2])){
                  $pdf->Cell(8, 5, $kis2, 1, 0, 'C',1 );
                  if($idfrom[$nkis2]=="3"){
                      $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  } else {
                      $pdf->Cell(15, 5, $kode_roll[$nkis2], 1, 0, 'C' );
                  }
                  $pdf->Cell(12.47, 5, $ukuran[$nkis2], 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                  $ttl11+=$ukuran[$nkis2];
                } else {
                  $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                  $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                }
                
                //-- start konten 4
                $kis3 = $i + 441;
                $nkis3 = $kis3 - 1;
                if(isset($kode_roll[$nkis3])){
                  $pdf->Cell(8, 5, $kis3, 1, 0, 'C',1 );
                  if($idfrom[$nkis3]=="3"){
                      $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  } else {
                      $pdf->Cell(15, 5, $kode_roll[$nkis3], 1, 0, 'C' );
                  }
                  $pdf->Cell(12.47, 5, $ukuran[$nkis3], 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                  $ttl12+=$ukuran[$nkis3];
                } else {
                  $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                  $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                }
                
                //--
                $pdf->Ln(5);
                }
  
                $pdf->setFillColor(156, 153, 152);
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, $ttl9==0?'':$ttl9, 1, 0, 'C',1 );
                $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, $ttl10==0?'':$ttl10, 1, 0, 'C',1 );
                $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, $ttl11==0?'':$ttl11, 1, 0, 'C',1 );
                $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, $ttl12==0?'':$ttl12, 1, 0, 'C',1 );
                $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
              
                $pdf->SetFont('Arial','B',10);
                $grttl3 = $ttl9 + $ttl10 + $ttl11 + $ttl12;
                $format_grttl3 = number_format($grttl3,2,",",".");
                $pdf->Text(10,250, 'TOTAL : '.$format_grttl3.'');
                if($jml_pkt > 320 AND $jml_pkt < 481){
                  $all_grand_total = $grttl3 + $grttl2 + $grttl;
                  $format_grttl_all = number_format($all_grand_total,2,",",".");
                  $pdf->Text(10,255, 'GRAND TOTAL : '.$format_grttl_all.' '.$satuan.'');
                }
                $pdf->SetFont('Arial','',10);
                if($jml_pkt<480){ $pdf->Text(165,250, 'Pengirim'); }
                $pdf->Ln(35);
                $pdf->Cell(145);
                if($jml_pkt<480){ $pdf->Cell(37, 0, '', 1, 0, 'C' ); }
              }
              //--- end page 3
              //--- page 4
            if($jml_pkt>480){
              $pdf->AddPage();
              $pdf->SetFont('Arial','',8);
              $pdf->Ln(18);
                $pdf->setFillColor(156, 153, 152); 
                $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
                $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
                $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
                $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
                $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
                $pdf->setFillColor(224, 224, 222); 
                $pdf->Ln(5);
                $ttl13=0; $ttl14=0; $ttl15=0; $ttl16=0;
                for ($i=0; $i < 40 ; $i++) { 
                // start konten 1
                $kis0 = $i + 481;
                $nkis0 = $kis0 - 1;
                if(isset($kode_roll[$nkis0])){
                  $pdf->Cell(8, 5, $kis0, 1, 0, 'C',1 );
                  if($idfrom[$nkis0]=="3"){
                      $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  } else {
                      $pdf->Cell(15, 5, $kode_roll[$nkis0], 1, 0, 'C' );
                  }
                  $pdf->Cell(12.47, 5, $ukuran[$nkis0], 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                  $ttl13+=$ukuran[$nkis0];
                } else {
                  $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                  $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                }
                //-- start konten 2
                $kis = $i + 521;
                $nkis = $kis - 1;
                if(isset($kode_roll[$nkis])){
                  $pdf->Cell(8, 5, $kis, 1, 0, 'C',1 );
                  if($idfrom[$nkis]=="3"){
                      $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  } else {
                      $pdf->Cell(15, 5, $kode_roll[$nkis], 1, 0, 'C' );
                  }
                  $pdf->Cell(12.47, 5, $ukuran[$nkis], 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                  $ttl14+=$ukuran[$nkis];
                } else {
                  $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                  $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                }
                
                //-- start konten 3
                $kis2 = $i + 561;
                $nkis2 = $kis2 - 1;
                if(isset($kode_roll[$nkis2])){
                  $pdf->Cell(8, 5, $kis2, 1, 0, 'C',1 );
                  if($idfrom[$nkis2]=="3"){
                      $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  } else {
                      $pdf->Cell(15, 5, $kode_roll[$nkis2], 1, 0, 'C' );
                  }
                  $pdf->Cell(12.47, 5, $ukuran[$nkis2], 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                  $ttl15+=$ukuran[$nkis2];
                } else {
                  $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                  $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                }
                
                //-- start konten 4
                $kis3 = $i + 601;
                $nkis3 = $kis3 - 1;
                if(isset($kode_roll[$nkis3])){
                  $pdf->Cell(8, 5, $kis3, 1, 0, 'C',1 );
                  if($idfrom[$nkis3]=="3"){
                      $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  } else {
                      $pdf->Cell(15, 5, $kode_roll[$nkis3], 1, 0, 'C' );
                  }
                  $pdf->Cell(12.47, 5, $ukuran[$nkis3], 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                  $ttl16+=$ukuran[$nkis3];
                } else {
                  $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                  $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                }
                
                //--
                $pdf->Ln(5);
                }
  
                $pdf->setFillColor(156, 153, 152);
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, $ttl13==0?'':$ttl13, 1, 0, 'C',1 );
                $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, $ttl14==0?'':$ttl14, 1, 0, 'C',1 );
                $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, $ttl15==0?'':$ttl15, 1, 0, 'C',1 );
                $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, $ttl16==0?'':$ttl16, 1, 0, 'C',1 );
                $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
              
                $pdf->SetFont('Arial','B',10);
                $grttl4 = $ttl13 + $ttl14 + $ttl15 + $ttl16;
                $format_grttl4 = number_format($grttl4,2,",",".");
                $pdf->Text(10,250, 'TOTAL : '.$format_grttl4.'');
                if($jml_pkt > 480 AND $jml_pkt < 641){
                  $all_grand_total = $grttl4 + $grttl3 + $grttl2 + $grttl;
                  $format_grttl_all = number_format($all_grand_total,2,",",".");
                  $pdf->Text(10,255, 'GRAND TOTAL : '.$format_grttl_all.' '.$satuan.'');
                }
                $pdf->SetFont('Arial','',10);
                if($jml_pkt<641){ $pdf->Text(165,250, 'Pengirim'); }
                $pdf->Ln(35);
                $pdf->Cell(145);
                if($jml_pkt<641){ $pdf->Cell(37, 0, '', 1, 0, 'C' ); }
              }
              //--- end page 4
            $pdf->Output('I','Packing-list.pdf'); 
    } else {
        echo "Kode Packinglist Tidak ditemukan";
    }

  } //end

  function packinglist2(){
    $token = $this->uri->segment(3);
    $wicode = $this->uri->segment(4);
    $ar = array(
      '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    );
    $cek = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$token]);
    if($cek->num_rows() == 1){
        $kode_roll = array();
        $ukuran = array();
        $idfrom = array();
        $satuan = "";
        // $cek_kode1 = $this->data_model->get_byid('data_fol', ['posisi'=>$token]);
        // $cek_kode2 = $this->data_model->get_byid('data_fol_lama', ['lokasi'=>$token]);
        // $cek_kode3 = $this->data_model->get_byid('data_stok_lama', ['posisi'=>$token]);
        // $cek_kode4 = $this->data_model->get_byid('kode_roll_outsite', ['kd'=>$token]);
        // $cek_kode5 = $this->data_model->get_byid('data_ig', ['loc_now'=>$token]);
        $cek_kode6 = $this->data_model->get_byid('new_tb_isi', ['kd'=>$token]);
        // if($cek_kode1->num_rows() > 0){
        //     foreach($cek_kode1->result() as $val):
        //         $kode_roll[] = $val->kode_roll;
        //         $ukuran[] = $val->ukuran;
        //         $idfrom[] = "1";
        //     endforeach;
        // }
        // if($cek_kode2->num_rows() > 0){
        //     foreach($cek_kode2->result() as $val):
        //         $kode_roll[] = $val->kode_roll;
        //         $ukuran[] = $val->ukuran_asli;
        //         $idfrom[] = "2";
        //     endforeach;
        // }
        // if($cek_kode3->num_rows() > 0){
        //     foreach($cek_kode3->result() as $val):
        //         $kode_roll[] = $val->id_sl;
        //         $ukuran[] = $val->ukuran;
        //         $idfrom[] = "3";
        //     endforeach;
        // }
        // if($cek_kode4->num_rows() > 0){
        //     foreach($cek_kode4->result() as $val):
        //         $kode_roll[] = $val->kode_roll;
        //         $ukuran_ori = $this->data_model->get_byid('data_ig',['kode_roll'=>$val->kode_roll])->row("ukuran_ori");
        //         $ukuran[] = $ukuran_ori;
        //         $idfrom[] = $val->fromm;
        //     endforeach;
        // }
        // if($cek_kode5->num_rows() > 0){
        //   foreach($cek_kode5->result() as $val):
        //       $kode_roll[] = $val->kode_roll;
        //       $ukuran[] = $val->ukuran_ori;
        //       $idfrom[] = "4";
        //   endforeach;
        // }
        
        if($cek_kode6->num_rows() > 0){
          foreach($cek_kode6->result() as $val):
              $kode_roll[] = $val->kode;
              $ukuran[] = $val->ukuran;
              $idfrom[] = "5";
              $satuan = $val->satuan;
          endforeach;
        }
        // for ($zs=0; $zs <188 ; $zs++) { 
        //     $kode_roll[] = "te";
        //     $ukuran[] = "90";
        //     $idfrom[] = "1";
        // }
        $row = $cek->row_array();
        if($row['kode_konstruksi']=="Samatex A"){
            $viewkons = "Samatex A / L : 120 CM";
        } elseif($row['kode_konstruksi']=="Samatex B"){
            $viewkons = "Samatex B / L : 120 CM";
        } elseif($row['kode_konstruksi']=="L90A") {
            $viewkons = "L 90 A";
        } elseif($row['kode_konstruksi']=="L90") {
            $viewkons = "L 90";
        } elseif($row['kode_konstruksi']=="L150B") {
            $viewkons = "L 150 B";
        } elseif($row['kode_konstruksi']=="L150") {
            $viewkons = "L 150 CM";
        } elseif($row['kode_konstruksi']=="L135") {
            $viewkons = "L 135 CM";
        } else {
            $viewkons = $row['kode_konstruksi'];
        }
        $jml_pkt = count($kode_roll);
        $ex = explode('-', $row['tanggal_dibuat']);
              $printTgl = $ex[2]." ".$ar[$ex[1]]." ".$ex[0];
              $pdf = new FPDF('p','mm','A4');
              // membuat halaman baru
              $pdf->AddPage();
              // setting jenis font yang akan digunakan
              $pdf->SetTitle('Packing List - '.$token.'');
      
              $pdf->SetFont('Arial','B',14);
              $pdf->Cell(0, 8, 'PACKING LIST', 1, 0, 'C' );
              $pdf->Ln(8);
              $pdf->Cell(0, 15, '', 1, 0, 'C' );
              $pdf->SetFont('Arial','',10);
              $pdf->Text(13,23, 'KEPADA');
              $pdf->Text(13,28, '');
              $pdf->Text(13,33, '');
              if($row['kepada']=="cus"){
                $idcus = $this->data_model->get_byid('surat_jalan', ['no_sj'=>$row['no_sj']])->row("id_customer");
                $nama_cus = $this->data_model->get_byid('dt_konsumen', ['id_konsumen'=>$idcus])->row("nama_konsumen");
              } else {
                $nama_cus = $row['kepada'];
              }
              $pdf->Text(35,23, ':  '.$nama_cus.'');
              $pdf->Text(35,28, ':  '.$viewkons.'');
              
              $pdf->Text(35,33, '');
              $pdf->Text(135,23, 'Tanggal');
              $pdf->Text(135,28, 'Surat Jalan');
              $pdf->Text(165,23, ':  '.$printTgl.'');
              $pdf->Text(165,28, ':  '.$row['no_sj'].'');
              $pdf->Ln(15);
              $pdf->setFillColor(156, 153, 152); 
              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(17, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(13.47, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'SMT', 1, 0, 'C',1 );
              $pdf->Cell(13.15, 5, 'CUST', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(17, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(13.47, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'SMT', 1, 0, 'C',1 );
              $pdf->Cell(13.15, 5, 'CUST', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(17, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(13.47, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'SMT', 1, 0, 'C',1 );
              $pdf->Cell(12.3, 5, 'CUST', 1, 0, 'C',1 );

              
              $pdf->setFillColor(224, 224, 222); 
              $pdf->Ln(5);
              $ttl1=0; $ttl2=0; $ttl3=0; $ttl4=0;
              for ($i=0; $i < 40 ; $i++) { 
              // start konten 1
              if(isset($kode_roll[$i])){
                $num=$i+1;
                $pdf->Cell(8, 5, $num, 1, 0, 'C',1 );
                if($idfrom[$i]=="3"){
                    $pdf->Cell(17, 5, '', 1, 0, 'C' );
                } else {
                    $printkode = $kode_roll[$i]=='null' ? '':$kode_roll[$i];
                    $printkode2 = $wicode=='0' ? '':$printkode;
                    $pdf->Cell(17, 5, $printkode2, 1, 0, 'C' );
                }
                $pdf->Cell(13.47, 5, $ukuran[$i], 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $pdf->Cell(13.15, 5, '', 1, 0, 'C' );
                $ttl1+=$ukuran[$i];
              } else {
                $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(17, 5, '', 1, 0, 'C' );
                $pdf->Cell(13.47, 5, '', 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $pdf->Cell(13.15, 5, '', 1, 0, 'C' );
              }
              //-- start konten 2
              $kis = $i + 41;
              $nkis = $kis - 1;
              if(isset($kode_roll[$nkis])){
                $pdf->Cell(8, 5, $kis, 1, 0, 'C',1 );
                if($idfrom[$nkis]=="3"){
                    $pdf->Cell(17, 5, '', 1, 0, 'C' );
                } else {
                    $printkode = $kode_roll[$nkis]=='null' ? '':$kode_roll[$nkis];
                    $printkode2 = $wicode=='0' ? '':$printkode;
                    $pdf->Cell(17, 5, $printkode2, 1, 0, 'C' );
                }
                $pdf->Cell(13.47, 5, $ukuran[$nkis], 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $pdf->Cell(13.15, 5, '', 1, 0, 'C' );
                $ttl2+=$ukuran[$nkis];
              } else {
                $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(17, 5, '', 1, 0, 'C' );
                $pdf->Cell(13.47, 5, '', 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $pdf->Cell(13.15, 5, '', 1, 0, 'C' );
              }
              
              //-- start konten 3
              $kis2 = $i + 81;
              $nkis2 = $kis2 - 1;
              if(isset($kode_roll[$nkis2])){
                $pdf->Cell(8, 5, $kis2, 1, 0, 'C',1 );
                if($idfrom[$nkis2]=="3"){
                    $pdf->Cell(17, 5, '', 1, 0, 'C' );
                } else {
                    $printkode = $kode_roll[$nkis2]=='null' ? '':$kode_roll[$nkis2];
                    $printkode2 = $wicode=='0' ? '':$printkode;
                    $pdf->Cell(17, 5, $printkode2, 1, 0, 'C' );
                }
                $pdf->Cell(13.47, 5, $ukuran[$nkis2], 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $pdf->Cell(12.3, 5, '', 1, 0, 'C' );
                $ttl3+=$ukuran[$nkis2];
              } else {
                $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(17, 5, '', 1, 0, 'C' );
                $pdf->Cell(13.47, 5, '', 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $pdf->Cell(12.3, 5, '', 1, 0, 'C' );
              }
              
              
              
              //--
              $pdf->Ln(5);
              }
              

              $pdf->setFillColor(156, 153, 152);
              $pdf->SetFont('Arial','B',8);
              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(17, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(13.47, 5, $ttl1==0?'':$ttl1, 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(13.15, 5, '', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(17, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(13.47, 5, $ttl2==0?'':$ttl2, 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(13.15, 5, '', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(17, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(13.47, 5, $ttl3==0?'':$ttl3, 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12.3, 5, '', 1, 0, 'C',1 );

              
              $grttl = $ttl1 + $ttl2 + $ttl3 + $ttl4;
              $format_grttl = number_format($grttl,2,",",".");

              $pdf->Ln(5);
              if($jml_pkt<121){
              $pdf->SetFont('Arial','B',11);
              $pdf->Cell(100, 6, '', 0, 0, 'C' );
              $pdf->Cell(35.3,6, 'Grand Total', 0, 0, 'C' );
              $pdf->Cell(30.5, 6, ''.$format_grttl.'', 1, 0, 'C' );
              $pdf->Cell(24.25, 6, $satuan, 1, 0, 'C' );
              }
              
              $pdf->SetFont('Arial','',10);
              if($jml_pkt<121){ $pdf->Text(10,256, 'SAMATEX'); $pdf->Text(90,256, 'SUPIR'); $pdf->Text(165,256, 'PEMBELI'); }
              $pdf->Ln(25);
              if($jml_pkt<121){ $pdf->Cell(30, 0, '', 1, 0, 'C' ); $pdf->Cell(40); $pdf->Cell(30, 0, '', 1, 0, 'C' ); $pdf->Cell(50); $pdf->Cell(30, 0, '', 1, 0, 'C' );  }
              
            //--- page 2
            if($jml_pkt>120){
            $pdf->AddPage();
            $pdf->SetFont('Arial','',10);
            $pdf->Ln(18);
              $pdf->setFillColor(156, 153, 152); 
              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(17, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(13.47, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'SMT', 1, 0, 'C',1 );
              $pdf->Cell(13.15, 5, 'CUST', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(17, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(13.47, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'SMT', 1, 0, 'C',1 );
              $pdf->Cell(13.15, 5, 'CUST', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(17, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(13.47, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'SMT', 1, 0, 'C',1 );
              $pdf->Cell(13, 5, 'CUST', 1, 0, 'C',1 );

              $pdf->setFillColor(224, 224, 222); 
              $pdf->Ln(5);
              $ttl5=0; $ttl6=0; $ttl7=0; $ttl8=0;
              for ($i=0; $i < 40 ; $i++) { 
              // start konten 1
              $kis0 = $i + 121;
              $nkis0 = $kis0 - 1;
              if(isset($kode_roll[$nkis0])){
                $pdf->Cell(8, 5, $kis0, 1, 0, 'C',1 );
                if($idfrom[$nkis0]=="3"){
                    $pdf->Cell(17, 5, '', 1, 0, 'C' );
                } else {
                    $pdf->Cell(17, 5, $kode_roll[$nkis0], 1, 0, 'C' );
                }
                $pdf->Cell(13.47, 5, $ukuran[$nkis0], 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $pdf->Cell(13.15, 5, '', 1, 0, 'C' );
                $ttl5+=$ukuran[$nkis0];
              } else {
                $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(17, 5, '', 1, 0, 'C' );
                $pdf->Cell(13.47, 5, '', 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $pdf->Cell(13.15, 5, '', 1, 0, 'C' );
              }
              //-- start konten 2
              $kis = $i + 161;
              $nkis = $kis - 1;
              if(isset($kode_roll[$nkis])){
                $pdf->Cell(8, 5, $kis, 1, 0, 'C',1 );
                if($idfrom[$nkis]=="3"){
                    $pdf->Cell(17, 5, '', 1, 0, 'C' );
                } else {
                    $pdf->Cell(17, 5, $kode_roll[$nkis], 1, 0, 'C' );
                }
                $pdf->Cell(13.47, 5, $ukuran[$nkis], 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $pdf->Cell(13.15, 5, '', 1, 0, 'C' );
                $ttl6+=$ukuran[$nkis];
              } else {
                $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(17, 5, '', 1, 0, 'C' );
                $pdf->Cell(13.47, 5, '', 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $pdf->Cell(13.15, 5, '', 1, 0, 'C' );
              }
              
              //-- start konten 3
              $kis2 = $i + 241;
              $nkis2 = $kis2 - 1;
              if(isset($kode_roll[$nkis2])){
                $pdf->Cell(8, 5, $kis2, 1, 0, 'C',1 );
                if($idfrom[$nkis2]=="3"){
                    $pdf->Cell(17, 5, '', 1, 0, 'C' );
                } else {
                    $pdf->Cell(17, 5, $kode_roll[$nkis2], 1, 0, 'C' );
                }
                $pdf->Cell(13.47, 5, $ukuran[$nkis2], 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $pdf->Cell(13.15, 5, '', 1, 0, 'C' );
                $ttl7+=$ukuran[$nkis2];
              } else {
                $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(17, 5, '', 1, 0, 'C' );
                $pdf->Cell(13.47, 5, '', 1, 0, 'C' );
                $pdf->Cell(12, 5, '', 1, 0, 'C' );
                $pdf->Cell(13.15, 5, '', 1, 0, 'C' );
              }
              
              //--
              $pdf->Ln(5);
              }

              $pdf->setFillColor(156, 153, 152);
              $pdf->SetFont('Arial','B',8);
              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(17, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(13.47, 5, $ttl5==0?'':$ttl5, 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(13.15, 5, '', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(17, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(13.47, 5, $ttl6==0?'':$ttl6, 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(13.15, 5, '', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(17, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(13.47, 5, $ttl7==0?'':$ttl7, 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(13.3, 5, '', 1, 0, 'C',1 );

            
              $pdf->SetFont('Arial','B',10);
              $grttl2 = $ttl5 + $ttl6 + $ttl7 + $ttl8;
              $grttl2_all = $grttl2 + $grttl;
              $format_grttl2 = number_format($grttl2_all,2,",",".");
              

              $pdf->Ln(5);
              $pdf->SetFont('Arial','B',11);
              $pdf->Cell(100, 6, '', 0, 0, 'C' );
              $pdf->Cell(35.3,6, 'Grand Total', 0, 0, 'C' );
              $pdf->Cell(30.5, 6, ''.$format_grttl2.'', 1, 0, 'C' );
              $pdf->Cell(25.25, 6, $satuan, 1, 0, 'C' );

              
              $pdf->SetFont('Arial','',10);
              if($jml_pkt<261){ $pdf->Text(10,256, 'SAMATEX'); $pdf->Text(90,256, 'SUPIR'); $pdf->Text(165,256, 'PEMBELI'); }
              $pdf->Ln(35);
              if($jml_pkt<261){ $pdf->Cell(30, 0, '', 1, 0, 'C' ); $pdf->Cell(40); $pdf->Cell(30, 0, '', 1, 0, 'C' ); $pdf->Cell(50); $pdf->Cell(30, 0, '', 1, 0, 'C' );  }
              
            }
            //--- end page 2
            //--- page 3
            if($jml_pkt>320){
              $pdf->AddPage();
              $pdf->SetFont('Arial','',8);
              $pdf->Ln(18);
                $pdf->setFillColor(156, 153, 152); 
                $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
                $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
                $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
                $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
                $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
                $pdf->setFillColor(224, 224, 222); 
                $pdf->Ln(5);
                $ttl9=0; $ttl10=0; $ttl11=0; $ttl12=0;
                for ($i=0; $i < 40 ; $i++) { 
                // start konten 1
                $kis0 = $i + 321;
                $nkis0 = $kis0 - 1;
                if(isset($kode_roll[$nkis0])){
                  $pdf->Cell(8, 5, $kis0, 1, 0, 'C',1 );
                  if($idfrom[$nkis0]=="3"){
                      $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  } else {
                      $pdf->Cell(15, 5, $kode_roll[$nkis0], 1, 0, 'C' );
                  }
                  $pdf->Cell(12.47, 5, $ukuran[$nkis0], 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                  $ttl9+=$ukuran[$nkis0];
                } else {
                  $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                  $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                }
                //-- start konten 2
                $kis = $i + 361;
                $nkis = $kis - 1;
                if(isset($kode_roll[$nkis])){
                  $pdf->Cell(8, 5, $kis, 1, 0, 'C',1 );
                  if($idfrom[$nkis]=="3"){
                      $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  } else {
                      $pdf->Cell(15, 5, $kode_roll[$nkis], 1, 0, 'C' );
                  }
                  $pdf->Cell(12.47, 5, $ukuran[$nkis], 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                  $ttl10+=$ukuran[$nkis];
                } else {
                  $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                  $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                }
                
                //-- start konten 3
                $kis2 = $i + 401;
                $nkis2 = $kis2 - 1;
                if(isset($kode_roll[$nkis2])){
                  $pdf->Cell(8, 5, $kis2, 1, 0, 'C',1 );
                  if($idfrom[$nkis2]=="3"){
                      $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  } else {
                      $pdf->Cell(15, 5, $kode_roll[$nkis2], 1, 0, 'C' );
                  }
                  $pdf->Cell(12.47, 5, $ukuran[$nkis2], 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                  $ttl11+=$ukuran[$nkis2];
                } else {
                  $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                  $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                }
                
                //-- start konten 4
                $kis3 = $i + 441;
                $nkis3 = $kis3 - 1;
                if(isset($kode_roll[$nkis3])){
                  $pdf->Cell(8, 5, $kis3, 1, 0, 'C',1 );
                  if($idfrom[$nkis3]=="3"){
                      $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  } else {
                      $pdf->Cell(15, 5, $kode_roll[$nkis3], 1, 0, 'C' );
                  }
                  $pdf->Cell(12.47, 5, $ukuran[$nkis3], 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                  $ttl12+=$ukuran[$nkis3];
                } else {
                  $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                  $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                }
                
                //--
                $pdf->Ln(5);
                }
  
                $pdf->setFillColor(156, 153, 152);
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, $ttl9==0?'':$ttl9, 1, 0, 'C',1 );
                $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, $ttl10==0?'':$ttl10, 1, 0, 'C',1 );
                $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, $ttl11==0?'':$ttl11, 1, 0, 'C',1 );
                $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, $ttl12==0?'':$ttl12, 1, 0, 'C',1 );
                $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
              
                $pdf->SetFont('Arial','B',10);
                $grttl3 = $ttl9 + $ttl10 + $ttl11 + $ttl12;
                $format_grttl3 = number_format($grttl3,2,",",".");
                $pdf->Text(10,250, 'TOTAL : '.$format_grttl3.'');
                if($jml_pkt > 320 AND $jml_pkt < 481){
                  $all_grand_total = $grttl3 + $grttl2 + $grttl;
                  $format_grttl_all = number_format($all_grand_total,2,",",".");
                  $pdf->Text(10,255, 'GRAND TOTAL : '.$format_grttl_all.' '.$satuan.'');
                }
                $pdf->SetFont('Arial','',10);
                if($jml_pkt<480){ $pdf->Text(165,250, 'Pengirim'); }
                $pdf->Ln(35);
                $pdf->Cell(145);
                if($jml_pkt<480){ $pdf->Cell(37, 0, '', 1, 0, 'C' ); }
              }
              //--- end page 3
              //--- page 4
            if($jml_pkt>480){
              $pdf->AddPage();
              $pdf->SetFont('Arial','',8);
              $pdf->Ln(18);
                $pdf->setFillColor(156, 153, 152); 
                $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
                $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
                $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
                $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, 'Kode', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, 'Ukuran', 1, 0, 'C',1 );
                $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
                $pdf->setFillColor(224, 224, 222); 
                $pdf->Ln(5);
                $ttl13=0; $ttl14=0; $ttl15=0; $ttl16=0;
                for ($i=0; $i < 40 ; $i++) { 
                // start konten 1
                $kis0 = $i + 481;
                $nkis0 = $kis0 - 1;
                if(isset($kode_roll[$nkis0])){
                  $pdf->Cell(8, 5, $kis0, 1, 0, 'C',1 );
                  if($idfrom[$nkis0]=="3"){
                      $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  } else {
                      $pdf->Cell(15, 5, $kode_roll[$nkis0], 1, 0, 'C' );
                  }
                  $pdf->Cell(12.47, 5, $ukuran[$nkis0], 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                  $ttl13+=$ukuran[$nkis0];
                } else {
                  $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                  $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                }
                //-- start konten 2
                $kis = $i + 521;
                $nkis = $kis - 1;
                if(isset($kode_roll[$nkis])){
                  $pdf->Cell(8, 5, $kis, 1, 0, 'C',1 );
                  if($idfrom[$nkis]=="3"){
                      $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  } else {
                      $pdf->Cell(15, 5, $kode_roll[$nkis], 1, 0, 'C' );
                  }
                  $pdf->Cell(12.47, 5, $ukuran[$nkis], 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                  $ttl14+=$ukuran[$nkis];
                } else {
                  $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                  $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                }
                
                //-- start konten 3
                $kis2 = $i + 561;
                $nkis2 = $kis2 - 1;
                if(isset($kode_roll[$nkis2])){
                  $pdf->Cell(8, 5, $kis2, 1, 0, 'C',1 );
                  if($idfrom[$nkis2]=="3"){
                      $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  } else {
                      $pdf->Cell(15, 5, $kode_roll[$nkis2], 1, 0, 'C' );
                  }
                  $pdf->Cell(12.47, 5, $ukuran[$nkis2], 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                  $ttl15+=$ukuran[$nkis2];
                } else {
                  $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                  $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                }
                
                //-- start konten 4
                $kis3 = $i + 601;
                $nkis3 = $kis3 - 1;
                if(isset($kode_roll[$nkis3])){
                  $pdf->Cell(8, 5, $kis3, 1, 0, 'C',1 );
                  if($idfrom[$nkis3]=="3"){
                      $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  } else {
                      $pdf->Cell(15, 5, $kode_roll[$nkis3], 1, 0, 'C' );
                  }
                  $pdf->Cell(12.47, 5, $ukuran[$nkis3], 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                  $ttl16+=$ukuran[$nkis3];
                } else {
                  $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
                  $pdf->Cell(15, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
                  $pdf->Cell(12, 5, '', 1, 0, 'C' );
                }
                
                //--
                $pdf->Ln(5);
                }
  
                $pdf->setFillColor(156, 153, 152);
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, $ttl13==0?'':$ttl13, 1, 0, 'C',1 );
                $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, $ttl14==0?'':$ttl14, 1, 0, 'C',1 );
                $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, $ttl15==0?'':$ttl15, 1, 0, 'C',1 );
                $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
  
                $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
                $pdf->Cell(15, 5, '', 1, 0, 'C',1 );
                $pdf->Cell(12.47, 5, $ttl16==0?'':$ttl16, 1, 0, 'C',1 );
                $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
              
                $pdf->SetFont('Arial','B',10);
                $grttl4 = $ttl13 + $ttl14 + $ttl15 + $ttl16;
                $format_grttl4 = number_format($grttl4,2,",",".");
                $pdf->Text(10,250, 'TOTAL : '.$format_grttl4.'');
                if($jml_pkt > 480 AND $jml_pkt < 641){
                  $all_grand_total = $grttl4 + $grttl3 + $grttl2 + $grttl;
                  $format_grttl_all = number_format($all_grand_total,2,",",".");
                  $pdf->Text(10,255, 'GRAND TOTAL : '.$format_grttl_all.' '.$satuan.'');
                }
                $pdf->SetFont('Arial','',10);
                if($jml_pkt<641){ $pdf->Text(165,250, 'Pengirim'); }
                $pdf->Ln(35);
                $pdf->Cell(145);
                if($jml_pkt<641){ $pdf->Cell(37, 0, '', 1, 0, 'C' ); }
              }
              //--- end page 4
            $pdf->Output('I','Packing-list.pdf'); 
    } else {
        echo "Kode Packinglist Tidak ditemukan";
    }

  } //end

  
  
  
}
?>
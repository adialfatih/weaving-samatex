<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      $this->load->library('pdf');
      date_default_timezone_set("Asia/Jakarta");
    //   if($this->session->userdata('login_form') != "grafamedia_admin"){
    //       redirect(base_url("login"));
    //   }
  }
  function index(){
    echo "";
  }
  
  function suratjalan(){
      $arbln = ["00"=>"undefined","01"=>"Januari","02"=>"Februari","03"=>"Maret","04"=>"April","05"=>"Mei","06"=>"Juni","07"=>"Juli","08"=>"Agustus","09"=>"September","10"=>"Oktober","11"=>"November","12"=>"Desember"];
      $idsj = $this->uri->segment(3);
      $cek = $this->data_model->get_byid('surat_jalan', ['sha1(id_sj)'=>$idsj]);
      if($cek->num_rows() == 1){
        $nosj = $cek->row("no_sj");
        $idcus = $cek->row("id_customer");
        $ntg = $cek->row("tgl_kirim");
        $ntgex = explode('-', $ntg);
        $printNtgl = $ntgex[2]." ".$arbln[$ntgex[1]]." ".$ntgex[0];
        $cek2 = $this->data_model->get_byid('dt_konsumen', ['id_konsumen'=>$idcus]);
        $nama_cus = $cek2->row("nama_konsumen");
        $almt_cus = $cek2->row("alamat");
        // echo $nosj."<br>";
        // echo $nama_cus."<br>";
        // echo $almt_cus."<br>";
        $paket = $this->data_model->get_byid('new_tb_packinglist', ['no_sj'=>$nosj]);
        $kode = array();
        $jns = array();
        $roll = array();
        $panjang = array();
        $satuan = array();
        $ket = array();
        foreach($paket->result() as $val):
            $kdpkg = $val->kd;
            $cekyard = $this->db->query("SELECT * FROM new_tb_isi WHERE kd='$kdpkg' LIMIT 1")->row("satuan");
            $kode [] = $val->kode_konstruksi;
            $jns [] = "Kain Rayon";
            $roll [] = $val->jumlah_roll;
            $panjang [] = $val->ttl_panjang;
            $satuan [] = $cekyard;
            $ket [] = "null";
        endforeach;
        $jumlahdata = count($kode);
        if($jumlahdata<5){
           $kekurangan = 5 - $jumlahdata;
           for ($a=0; $a <$kekurangan ; $a++) { 
              $kode[]="null";
              $jns[]="null";
              $roll[]="null";
              $panjang[]="null";
              $ket[]="null";
           }
        }
        $pdf = new FPDF('l','mm',array(240,140));
        // membuat halaman baru
        $pdf->AddPage();
        // setting jenis font yang akan digunakan
        $pdf->SetTitle('Surat Jalan No. '.$nosj.'');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0, 8, '', 0, 0, 'R' );
        $pdf->Text(160,13, 'Pekalongan, '.$printNtgl.'');
        $pdf->Text(180,18, 'Kepada Yth,');
        $pdf->Text(165,29, $nama_cus);
        $pdf->Text(165,37, $almt_cus);
        $pdf->Line(160,14,230,14);
        $pdf->Line(160,23,230,23);
        $pdf->Line(160,31,230,31);
        $pdf->Line(160,39,230,39);
        $pdf->SetLineWidth(0.7);
        $pdf->Line(160,40,230,40);
        $pdf->SetFont('Arial','B',15);
        $pdf->Text(80,23, 'SURAT JALAN');
        $pdf->SetLineWidth(0);
        $pdf->Line(80,25,118,25);
        $pdf->SetFont('Arial','',12);
        $pdf->Text(79,30, 'No. : '.$nosj.'');
        $pdf->Text(10,50, 'Bersama ini dengan kendaraan no. .................................. kami kirimkan barang-barang tersebut dibawah ini');
        $pdf->Ln(44);
        $pdf->Cell(35, 8, 'Nomor / Kode', 1, 0, 'C' );
        $pdf->Cell(65, 8, 'Jenis Barang', 1, 0, 'C' );
        $pdf->Cell(35, 8, 'Satuan', 1, 0, 'C' );
        $pdf->Cell(35, 8, 'Jumlah', 1, 0, 'C' );
        $pdf->Cell(50, 8, 'Keterangan', 1, 0, 'C' );
        // baris 1
        for ($i=0; $i <count($kode) ; $i++) { 
          if($kode[$i]=="null"){
            $pdf->Ln(8);
            $pdf->Cell(35, 8, '', 1, 0, 'C' );
            $pdf->Cell(65, 8, '', 1, 0, 'C' );
            $pdf->Cell(35, 8, '', 1, 0, 'C' );
            $pdf->Cell(35, 8, '', 1, 0, 'C' );
            $pdf->Cell(50, 8, '', 1, 0, 'C' );
          } else {
            if(fmod($panjang[$i], 1) !== 0.00){
              $crt = number_format($panjang[$i],2,',','.')." ".$satuan[$i];
            } else {
              $crt = number_format($panjang[$i],0,',','.')." ".$satuan[$i];
            }
            $pdf->Ln(8);
            $pdf->Cell(35, 8, $kode[$i], 1, 0, 'C' );
            $pdf->Cell(65, 8, $jns[$i], 1, 0, 'C' );
            $pdf->Cell(35, 8, ''.$roll[$i].' Roll', 1, 0, 'C' );
            $pdf->Cell(35, 8, $crt, 1, 0, 'C' );
            $pdf->Cell(50, 8, $ket[$i]=='null' ?'':$ket[$i], 1, 0, 'C' );
          }
          # code...
        }
        
        
        $pdf->Text(25,108, 'Telah diterima dalam');
        $pdf->Text(31,113, 'Keadaan baik');
        $pdf->Text(95,108, 'Pembawa Barang');
        $pdf->Text(175,108, 'Hormat Kami,');
        $pdf->Line(30,128,58,128);
        $pdf->Line(95,128,128,128);
        $pdf->Line(95,128,128,128);
        $pdf->Line(170,128,208,128);
        $pdf->Output('I','penjualan.pdf'); 
      } else {
        echo "ID Surat Jalan Tidak Ditemukan";
      }
        
  } //end surat jalan
  function nota(){
    
    $url = $this->uri->segment(3);
    $font = $this->uri->segment(4);
    $ar = array(
      '00'=>'undefined', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    );
    $tahun = date('Y');
    $angka = substr($tahun, -2);
    $sj = $this->data_model->get_byid('surat_jalan', ['id_sj'=>$url]);
    if($sj->num_rows() == 1){
    $nosj = $sj->row("no_sj");
    $ex = explode('/',$nosj);
    $idcus = $sj->row("id_customer");
    $cus = $this->data_model->get_byid('dt_konsumen', ['id_konsumen'=>$idcus]);
    $namacus = $cus->row("nama_konsumen");
    $almtcus = $cus->row("alamat");
    $tglkirim = $sj->row("tgl_kirim");
    $tg = explode('-', $tglkirim);
    $printTgl = $tg[2]." ".$ar[$tg[1]]." ".$tg[0]; 
    $pkg = $this->data_model->get_byid('a_nota', ['no_sj'=>$nosj]);
    $jns = array(); $roll = array(); $pjg = array(); $hrg = array(); $total = array(); $satuans=array();
    $totalHarga = 0;
    foreach($pkg->result() as $val):
      $jns[]=$val->konstruksi;
      $roll[]=$val->jml_roll;
      $pjg[]=$val->total_panjang;
      $hrg[]=$val->harga_satuan;
      $total[]=$val->total_harga;
      $totalHarga+=$val->total_harga;
      $nkode = $val->kd;
      $satuans[] = $this->db->query("SELECT * FROM new_tb_isi WHERE kd='$nkode' LIMIT 1")->row("satuan");
    endforeach;
    $pdf = new FPDF('l','mm',array(240,140));
              // membuat halaman baru
              $pdf->AddPage();
              // setting jenis font yang akan digunakan
              $pdf->SetTitle('Nota Penjualan - Surat Jalan '.$nosj.'');
              //$pdf->AddFont('sans-serif', '', 'courier.php');
              if($font==1){ $pdf->SetFont('arial','',12); $titik=". . . . . . . . . . . . . . . . . . . .";}
              if($font==""){ $pdf->SetFont('arial','',12); $titik=". . . . . . . . . . . . . . . . . . . .";}
              if($font==2){ $pdf->AddFont('courier', '', 'courier.php'); $pdf->SetFont('courier','',12); $titik="....................";}
              if($font==3){ $pdf->AddFont('helvetica', '', 'helvetica.php'); $pdf->SetFont('helvetica','',12); $titik="....................";}
              $pdf->Cell(0, 8, '', 0, 0, 'R' );
              $pdf->Text(180,13, 'Kepada YTH :');
              if($namacus=="PUTRI DIANA"){
                  $namacus = "Tn..";
              } elseif($namacus=="SALIM"){
                  $namacus = "Helmy PB";
              } else {
                  $namacus = $namacus;
              }
              $pdf->Text(180,18, ''.$namacus.'');
              $pdf->Text(180,23, $almtcus);
              $pdf->Ln(8);
              $pdf->Ln(8);
              $pdf->Cell(45, 5, 'No Faktur', 0, 0, 'L' );
              $pdf->Cell(5, 5, ':', 0, 0, 'L' );
              $pdf->Cell(100, 5, 'J'.$angka.''.$ex[0].'', 0, 0, 'L' );
              $pdf->Ln(5);
              $pdf->Cell(45, 5, 'Nomor Surat Jalan', 0, 0, 'L' );
              $pdf->Cell(5, 5, ':', 0, 0, 'L' );
              $pdf->Cell(100, 5, $nosj, 0, 0, 'L' );
              $pdf->Ln(8);
              $pdf->Cell(20, 5, 'No.', 0, 0, 'C' );
              $pdf->Cell(40, 5, 'JENIS BARANG', 0, 0, 'C' );
              $pdf->Cell(20, 5, 'ROLL', 0, 0, 'C' );
              $pdf->Cell(50, 5, 'PANJANG', 0, 0, 'C' );
              $pdf->Cell(35, 5, 'HARGA', 0, 0, 'C' );
              $pdf->Cell(55, 5, 'TOTAL', 0, 0, 'C' );
              $pdf->Line(11,38,230,38);
              $pdf->Line(11,44,230,44);
              
              $jarakbaris = 10;
              $io = $pkg->num_rows();
              if($io<9){
                $jarakbaris = 10;
              } else {
                $jarakbaris = 5;
              }
              $pdf->Ln($jarakbaris);
              $baris_bawah = 53;
              
              for ($i=0; $i <$io ; $i++) { 
              $no=$i+1;
              $pdf->Cell(20, 5, $no, 0, 0, 'C' );
              $pdf->Cell(40, 5, $jns[$i], 0, 0, 'C' );
              $pdf->Cell(20, 5, $roll[$i], 0, 0, 'C' );
              $stst = $satuans[$i]=="Meter" ? 'Mtr':'Yrd';
                if(fmod($pjg[$i], 1) !== 0.00){
                  $crt1 = number_format($pjg[$i],2,',','.')." ";
                } else {
                  $crt1 = number_format($pjg[$i],0,',','.')." ";
                }
              $pdf->Cell(50, 5, ''.$crt1.''.$stst, 0, 0, 'C' );
                if(fmod($hrg[$i], 1) !== 0.00){
                  $crt2 = number_format($hrg[$i],2,',','.')." ";
                } else {
                  $crt2 = number_format($hrg[$i],0,',','.')." ";
                }
              $pdf->Cell(35, 5, $crt2, 0, 0, 'C' );
                if(fmod($total[$i], 1) !== 0.00){
                  $crt3 = number_format($total[$i],2,',','.')." ";
                } else {
                  $crt3 = number_format($total[$i],0,',','.')." ";
                }
              $pdf->Cell(55, 5, $crt3, 0, 0, 'C' );
              $pdf->Ln($jarakbaris);
                  $baris_bawah = $baris_bawah + $jarakbaris;
              }
              $pdf->Cell(20, 5, '', 0, 0, 'C' );
              $pdf->Cell(40, 5, '', 0, 0, 'C' );
              $pdf->Cell(20, 5, '', 0, 0, 'C' );
              $pdf->Cell(50, 5, '', 0, 0, 'C' );
              $pdf->Cell(35, 5, 'TOTAL', 0, 0, 'C' );
                if(fmod($totalHarga, 1) !== 0.00){
                  $crt4 = number_format($totalHarga,2,',','.')." ";
                } else {
                  $crt4 = number_format($totalHarga,0,',','.')." ";
                }
              $pdf->Cell(55, 5, 'Rp. '.$crt4, 0, 0, 'C' );
              $pdf->Line(11,$baris_bawah,230,$baris_bawah);
              $pdf->Line(30,38,30,$baris_bawah);
              $pdf->Line(70,38,70,$baris_bawah);
              $pdf->Line(93,38,93,$baris_bawah);
              $pdf->Line(138,38,138,$baris_bawah);
              $pdf->Line(178,38,178,$baris_bawah);
              $pdf->Ln(10);
              $pdf->Cell(140);
              $pdf->Cell(55, 5, 'Pekalongan, '.$printTgl.'', 0, 0, 'C' );
              $pdf->Ln(5);
              $pdf->Cell(140);
              $pdf->Cell(55, 5, 'BAGIAN PENJUALAN', 0, 0, 'C' );
              $pdf->Ln(15);
              $pdf->Cell(140);
              $pdf->Cell(55, 5, 'AHMAD', 0, 0, 'C' );
              $pdf->Ln(3);
              $pdf->Cell(140);
              $pdf->Cell(55, 5, $titik, 0, 0, 'C' );
              $pdf->Output('I','nota-'.$namacus.'-'.$nosj.'.pdf'); 
            } else {
              echo "Nota tidak ditemukan";
            }
  }

  function notabs(){
    
    $url = $this->uri->segment(3);
    $font = $this->uri->segment(4);
    $ar = array(
      '00'=>'undefined', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    );
    $tahun = date('Y');
    $angka = substr($tahun, -2);
    $sj = $this->data_model->get_byid('bs_paket_kirim', ['sj'=>$url]);
    if($sj->num_rows() == 1){
    $nosj = $sj->row("sj");
    $idcus = $sj->row("cusid");
    $namacus = $sj->row("cus");
    $cus = $this->data_model->get_byid('dt_konsumen', ['id_konsumen'=>$idcus]);
    //$namacus = $cus->row("nama_konsumen");
    $almtcus = $cus->row("alamat");
    $tglkirim = $sj->row("tgl_kirim");
    $tg = explode('-', $tglkirim);
    $printTgl = $tg[2]." ".$ar[$tg[1]]." ".$tg[0];
    $pkg = explode(';', $sj->row('dtkrim'));
    $io = count($pkg);
    //$pkg = $this->data_model->get_byid('a_nota', ['no_sj'=>$nosj]);
    //$jns = array(); $roll = array(); $pjg = array(); $hrg = array(); $total = array(); $satuans=array();
    $totalHarga = 0;
    foreach($pkg as $val):
      $xval = explode('-', $val);
      $kdpkt[]=$xval[0];
      $jns[]=$xval[1];
      $_jmlroll[]=$xval[2];
      $_tpjg[]=$xval[3];
      $kilo[]=$xval[4];
      $sat[]=$xval[6];
      $hrg[]=$xval[5];
      $total[]=$xval[4];
      $_thisHrg = str_replace(",", "", $xval[5]);
      $_thisHrg = floatval($_thisHrg);
      if($xval[6]=="Kg"){
          $_thisHrg1 = $_thisHrg * floatval($xval[4]);
          $_allhrg[] = $_thisHrg1;
      } else {
          $_thisHrg1 = $_thisHrg * floatval($xval[3]);
          $_allhrg[] = $_thisHrg1;
      }
      $totalHarga+=$_thisHrg1;
      //$nkode = $xval[0];
      
    endforeach;
    $pdf = new FPDF('l','mm',array(240,140));
              // membuat halaman baru
              $pdf->AddPage();
              // setting jenis font yang akan digunakan
              $pdf->SetTitle('Nota Penjualan - Surat Jalan '.$nosj.'');
              //$pdf->AddFont('sans-serif', '', 'courier.php');
              if($font==1){ $pdf->SetFont('arial','',12); $titik=". . . . . . . . . . . . . . . . . . . .";}
              if($font==""){ $pdf->SetFont('arial','',12); $titik=". . . . . . . . . . . . . . . . . . . .";}
              if($font==2){ $pdf->AddFont('courier', '', 'courier.php'); $pdf->SetFont('courier','',12); $titik="....................";}
              if($font==3){ $pdf->AddFont('helvetica', '', 'helvetica.php'); $pdf->SetFont('helvetica','',12); $titik="....................";}
              $pdf->Cell(0, 8, '', 0, 0, 'R' );
              $pdf->Text(180,13, 'Kepada YTH :');
              if($namacus=="PUTRI DIANA"){
                  $namacus = "Tn..";
              } elseif($namacus=="SALIM"){
                  $namacus = "Helmy PB";
              } else {
                  $namacus = $namacus;
              }
              $pdf->Text(180,18, ''.$namacus.'');
              $pdf->Text(180,23, $almtcus);
              $pdf->Ln(8);
              $pdf->Ln(8);
              $pdf->Cell(45, 5, 'No Faktur', 0, 0, 'L' );
              $pdf->Cell(5, 5, ':', 0, 0, 'L' );
              $pdf->Cell(100, 5, 'J'.$angka.''.$ex[0].'', 0, 0, 'L' );
              $pdf->Ln(5);
              $pdf->Cell(45, 5, 'Nomor Surat Jalan', 0, 0, 'L' );
              $pdf->Cell(5, 5, ':', 0, 0, 'L' );
              $pdf->Cell(100, 5, $nosj, 0, 0, 'L' );
              $pdf->Ln(8);
              $pdf->Cell(15, 5, 'No.', 0, 0, 'C' );
              $pdf->Cell(55, 5, 'JENIS BARANG', 0, 0, 'C' );
              $pdf->Cell(30, 5, 'QTY', 0, 0, 'C' );
              $pdf->Cell(20, 5, 'Sat',0, 0, 'C' );
              $pdf->Cell(40, 5, 'HARGA', 0, 0, 'C' );
              $pdf->Cell(55, 5, 'TOTAL', 0, 0, 'C' );
              $pdf->Line(11,38,230,38);
              $pdf->Line(11,44,230,44);
              
              $jarakbaris = 10;
              //$io = $pkg->num_rows();
              if($io<9){
                $jarakbaris = 10;
              } else {
                $jarakbaris = 5;
              }
              $pdf->Ln($jarakbaris);
              $baris_bawah = 53;
              
              for ($i=0; $i <$io ; $i++) { 
              $no=$i+1;
              $pdf->Cell(15, 5, $no, 0, 0, 'C' );
              $pdf->Cell(55, 5, $jns[$i], 0, 0, 'C' );
              $pdf->Cell(30, 5, $kilo[$i], 0, 0, 'C' );
              
              $pdf->Cell(20, 5, $sat[$i], 0, 0, 'C' );
                
              $pdf->Cell(40, 5, $hrg[$i], 0, 0, 'C' );
              if(fmod($_allhrg[$i], 1) !== 0.00){
                $crt6 = "".number_format($_allhrg[$i],2,',','.')." ";
              } else {
                $crt6 = "".number_format($_allhrg[$i],0,',','.')." ";
              }
              $pdf->Cell(55, 5, $crt6, 0, 0, 'C' );
              $pdf->Ln($jarakbaris);
                  $baris_bawah = $baris_bawah + $jarakbaris;
              }
              $pdf->Cell(20, 5, '', 0, 0, 'C' );
              $pdf->Cell(40, 5, '', 0, 0, 'C' );
              $pdf->Cell(20, 5, '', 0, 0, 'C' );
              $pdf->Cell(40, 5, '', 0, 0, 'C' );
              $pdf->Cell(40, 5, 'TOTAL', 0, 0, 'C' );
                if(fmod($totalHarga, 1) !== 0.00){
                  $crt4 = number_format($totalHarga,2,',','.')." ";
                } else {
                  $crt4 = number_format($totalHarga,0,',','.')." ";
                }
              $pdf->Cell(60, 5, 'Rp. '.$crt4, 0, 0, 'C' );
              $pdf->Line(11,$baris_bawah,230,$baris_bawah);
              $pdf->Line(25,38,25,$baris_bawah);
              $pdf->Line(80,38,80,$baris_bawah);
              $pdf->Line(110,38,110,$baris_bawah);
              $pdf->Line(130,38,130,$baris_bawah);
              $pdf->Line(170,38,170,$baris_bawah);
              $pdf->Ln(10);
              $pdf->Cell(140);
              $pdf->Cell(55, 5, 'Pekalongan, '.$printTgl.'', 0, 0, 'C' );
              $pdf->Ln(5);
              $pdf->Cell(140);
              $pdf->Cell(55, 5, 'BAGIAN PENJUALAN', 0, 0, 'C' );
              $pdf->Ln(15);
              $pdf->Cell(140);
              $pdf->Cell(55, 5, 'AHMAD', 0, 0, 'C' );
              $pdf->Ln(3);
              $pdf->Cell(140);
              $pdf->Cell(55, 5, $titik, 0, 0, 'C' );
              $pdf->Output('I','nota-'.$namacus.'-'.$nosj.'.pdf'); 
            } else {
              echo "Nota tidak ditemukan";
            }
  }
  
  function penjualan(){
    $token = $this->uri->segment(3);
    $wicode = $this->uri->segment(4);
    $ar = array(
      '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    );
    $cek = $this->data_model->get_byid('v_penjualan', ['sha1(id_penjualan)'=>$token]);
    if($cek->num_rows() == 1){
    $row = $cek->row_array();
    $kode_pkg = $row['penjualan_list'];
    $cek_isipaket = $this->data_model->get_byid('v_roll', ['id_kdlist'=>$kode_pkg]);
    $dt = $cek_isipaket->result_array();
    $jml_pkt = $cek_isipaket->num_rows();
             $ex = explode('-', $row['tgl']);
             $printTgl = $ex[2]." ".$ar[$ex[1]]." ".$ex[0];
             $pdf = new FPDF('p','mm','A4');
              // membuat halaman baru
              $pdf->AddPage();
              // setting jenis font yang akan digunakan
              $pdf->SetTitle('Packing List - '.$kode_pkg.'');
      
              $pdf->SetFont('Arial','B',14);
              $pdf->Cell(0, 8, 'PACKING LIST PENJUALAN', 1, 0, 'C' );
              $pdf->Ln(8);
              $pdf->Cell(0, 18, '', 1, 0, 'C' );
              $pdf->SetFont('Arial','',8);
              $pdf->Text(13,23, 'No');
              $pdf->Text(13,28, 'Jenis Kain');
              $pdf->Text(13,33, 'Kepada');
              $pdf->Text(35,23, ':  '.sprintf("%04d", $row['id_penjualan']).'/SMT/III/23');
              $pdf->Text(35,28, ':  '.$row['kode_konstruksi'].'');
              $pdf->Text(35,33, ':  '.$row['nama_konsumen'].'');
              $pdf->Text(135,23, 'Tanggal');
              $pdf->Text(135,28, 'Surat Jalan');
              $pdf->Text(165,23, ':  '.$printTgl.'');
              $pdf->Text(165,28, ':  '.sprintf("%04d", $row['id_penjualan']).'/SMT/III/23');
              $pdf->Ln(18);
              $pdf->setFillColor(156, 153, 152); 
              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
              $pdf->setFillColor(224, 224, 222); 
              $ttl1=0; $ttl2=0; $ttl3=0; $ttl4=0;
              for($i=0; $i<40; $i++){
              $pdf->Ln(5);
              $query1 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $i");
              if($query1->num_rows()==1){
              $num = $i+1;
              if($query1->row("st_folding") == "Finish"){ $ukuran = $query1->row("ukuran_yard")." y"; $ttl1+=$query1->row("ukuran_yard"); } else { $ukuran = $query1->row("ukuran")." m";$ttl1+=$query1->row("ukuran"); }
              $pdf->Cell(8, 5, $num, 1, 0, 'C',1 );
              $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
              $kode = $wicode=='0' ? '-':$query1->row('kode_roll');
              $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
              $pdf->Cell(12, 5, '', 1, 0, 'C' );
              
              } else {
              $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, '', 1, 0, 'C' );
              $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
              $pdf->Cell(12, 5, '', 1, 0, 'C' );
              }

              $of2 = $i+40;
              $query2 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of2");
              if($query2->num_rows()==1){
              $num2 = 40+1;
              $of2s = $of2 + 1;
              if($query2->row("st_folding") == "Finish"){ $ukuran = $query2->row("ukuran_yard")." y"; $ttl2+=$query2->row("ukuran_yard"); } else { $ukuran = $query2->row("ukuran")." m"; $ttl2+=$query2->row("ukuran"); }
              $pdf->Cell(8, 5, $of2s, 1, 0, 'C',1 );
              $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
              $kode = $wicode=='0' ? '-':$query2->row('kode_roll');
              $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
              $pdf->Cell(12, 5, '', 1, 0, 'C' );
              
              } else {
              $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, '', 1, 0, 'C' );
              $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
              $pdf->Cell(12, 5, '', 1, 0, 'C' );
              }
              
              $of3 = $i+80;
              $query3 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of3");
              if($query3->num_rows()==1){
              $num3 = 80+1;
              $of3s = $of3 + 1;
              if($query3->row("st_folding") == "Finish"){ $ukuran = $query3->row("ukuran_yard")." y"; $ttl3+=$query3->row("ukuran_yard"); } else { $ukuran = $query3->row("ukuran")." m"; $ttl3+=$query3->row("ukuran"); }
              $pdf->Cell(8, 5, $of3s, 1, 0, 'C',1 );
              $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
              $kode = $wicode=='0' ? '-':$query3->row('kode_roll');
              $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
              $pdf->Cell(12, 5, '', 1, 0, 'C' );
              
              } else {
              $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, '', 1, 0, 'C' );
              $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
              $pdf->Cell(12, 5, '', 1, 0, 'C' );
              }

              $of4 = $i+120;
              $query4 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of4");
              if($query4->num_rows()==1){
              $num4 = 120+1;
              $of4s = $of4 + 1;
              if($query4->row("st_folding") == "Finish"){ $ukuran = $query4->row("ukuran_yard")." y"; $ttl4+=$query4->row("ukuran_yard"); } else { $ukuran = $query4->row("ukuran")." m"; $ttl4+=$query4->row("ukuran"); }
              $pdf->Cell(8, 5, $of4s, 1, 0, 'C',1 );
              $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
              $kode = $wicode=='0' ? '-':$query4->row('kode_roll');
              $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
              $pdf->Cell(12, 5, '', 1, 0, 'C' );
              
              } else {
              $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, '', 1, 0, 'C' );
              $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
              $pdf->Cell(12, 5, '', 1, 0, 'C' );  
              }

              }
              $grand1 = $ttl1 + $ttl2 + $ttl3 + $ttl4;
              $grand_total = number_format($grand1,2,",",".");
              $pdf->Ln(5);
              $pdf->setFillColor(156, 153, 152);
              $pdf->SetFont('Arial','B',8);
              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, ''.$ttl1.'', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, $ttl2, 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, $ttl3, 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, $ttl4, 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
              if($jml_pkt>160){} else {
              $pdf->SetFont('Arial','B',10);
              $pdf->Text(10,252, 'GRAND TOTAL : '.$grand_total.'');
              $pdf->SetFont('Arial','',10);
              $pdf->Text(165,258, 'Pengirim');
              $pdf->Ln(35);
              $pdf->Cell(145);
              $pdf->Cell(37, 0, '', 1, 0, 'C' ); }
              
              
      
      if($jml_pkt>160){
        //echo "lebih 160 paket";
        // -------------------
        $pdf->AddPage();
        $pdf->SetFont('Arial','',8);
        $pdf->Ln(18);
        $pdf->setFillColor(156, 153, 152); 
        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
        $pdf->setFillColor(224, 224, 222); 
        $ttl5=0; $ttl6=0; $ttl7=0; $ttl8=0;
        for($i=1; $i<41; $i++){
        $pdf->Ln(5);
          $of5 = $i+159;
          $query5 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of5");
          if($query5->num_rows()==1){
          $num5 = 159+1;
          $of5s = $of5 + 1;
          if($query5->row("st_folding") == "Finish"){ $ukuran = $query5->row("ukuran_yard")." y"; $ttl5+=$query5->row("ukuran_yard"); } else { $ukuran = $query5->row("ukuran")." m"; $ttl5+=$query5->row("ukuran"); }
          $pdf->Cell(8, 5, $of5s, 1, 0, 'C',1 );
          $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
          
          $kode = $wicode=='0' ? '-':$query5->row('kode_roll');
          $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
          $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, $i, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }
        
          $of6 = $i+199;
          $query6 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of6");
          if($query6->num_rows()==1){
          $num6 = 199+1;
          $of6s = $of6 + 1;
          if($query6->row("st_folding") == "Finish"){ $ukuran = $query6->row("ukuran_yard")." y"; $ttl6+=$query6->row("ukuran_yard"); } else { $ukuran = $query6->row("ukuran")." m"; $ttl6+=$query6->row("ukuran"); }
            $pdf->Cell(8, 5, $of6s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            
            $kode = $wicode=='0' ? '-':$query6->row('kode_roll');
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

          $of7 = $i+239;
          $query7 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of7");
          if($query7->num_rows()==1){
          $num7 = 239+1;
          $of7s = $of7 + 1;
          if($query7->row("st_folding") == "Finish"){ $ukuran = $query7->row("ukuran_yard")." y"; $ttl7+=$query7->row("ukuran_yard"); } else { $ukuran = $query7->row("ukuran")." m"; $ttl7+=$query7->row("ukuran"); }
            $pdf->Cell(8, 5, $of7s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            
            $kode = $wicode=='0' ? '-':$query7->row('kode_roll');
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

          $of8 = $i+279;
          $query8 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of8");
          if($query8->num_rows()==1){
          $num8 = 279+1;
          $of8s = $of8 + 1;
          if($query8->row("st_folding") == "Finish"){ $ukuran = $query8->row("ukuran_yard")." y"; $ttl8+=$query8->row("ukuran_yard"); } else { $ukuran = $query8->row("ukuran")." m"; $ttl8+=$query8->row("ukuran"); }
            $pdf->Cell(8, 5, $of8s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            
            $kode = $wicode=='0' ? '-':$query8->row('kode_roll');
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

        }
        $grand2 = $ttl5 + $ttl6 + $ttl7 + $ttl8;
        $grand_total2 = number_format($grand2,2,",",".");
        $new_grand = $grand1 + $grand2;
        $new_grand2 = number_format($new_grand,2,",",".");
        $pdf->Ln(5);
        $pdf->setFillColor(156, 153, 152);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl5, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl6, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl7, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl8, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
        if($jml_pkt>320){} else {
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(10,246, 'GRAND TOTAL : '.$new_grand.'');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(165,250, 'Pengirim');
        $pdf->Ln(35);
        $pdf->Cell(145);
        $pdf->Cell(37, 0, '', 1, 0, 'C' ); }
      }

      if($jml_pkt>320){
        //echo "lebih 200 paket";
        // -------------------
        $pdf->AddPage();
        $pdf->SetFont('Arial','',8);
        $pdf->Ln(18);
        $pdf->setFillColor(156, 153, 152); 
        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
        $pdf->setFillColor(224, 224, 222);
        $ttl9=0; $ttl10=0; $ttl11=0; $ttl12=0;
        for($i=1; $i<41; $i++){
        $pdf->Ln(5);
          $of5 = $i+319;
          $query5 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of5");
          if($query5->num_rows()==1){
          $num5 = 319+1;
          $of5s = $of5 + 1;
          if($query5->row("st_folding") == "Finish"){ $ukuran = $query5->row("ukuran_yard")." y"; $ttl9+=$query5->row("ukuran_yard"); } else { $ukuran = $query5->row("ukuran")." m"; $ttl9+=$query5->row("ukuran"); }
          $pdf->Cell(8, 5, $of5s, 1, 0, 'C',1 );
          $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
          $kode = $wicode=='0' ? '-':$query5->row('kode_roll');
          $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
          $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, $i, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }
        
          $of6 = $i+359;
          $query6 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of6");
          if($query6->num_rows()==1){
          $num6 = 359+1;
          $of6s = $of6 + 1;
          if($query6->row("st_folding") == "Finish"){ $ukuran = $query6->row("ukuran_yard")." y"; $ttl10+=$query6->row("ukuran_yard"); } else { $ukuran = $query6->row("ukuran")." m"; $ttl10+=$query6->row("ukuran"); }
            $pdf->Cell(8, 5, $of6s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$query6->row('kode_roll');
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

          $of7 = $i+399;
          $query7 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of7");
          if($query7->num_rows()==1){
          $num7 = 399+1;
          $of7s = $of7 + 1;
          if($query7->row("st_folding") == "Finish"){ $ukuran = $query7->row("ukuran_yard")." y"; $ttl11+=$query7->row("ukuran_yard"); } else { $ukuran = $query7->row("ukuran")." m"; $ttl11+=$query7->row("ukuran"); }
            $pdf->Cell(8, 5, $of7s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$query7->row('kode_roll');
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

          $of8 = $i+439;
          $query8 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of8");
          if($query8->num_rows()==1){
          $num8 = 439+1;
          $of8s = $of8 + 1;
          if($query8->row("st_folding") == "Finish"){ $ukuran = $query8->row("ukuran_yard")." y"; $ttl12+=$query8->row("ukuran_yard"); } else { $ukuran = $query8->row("ukuran")." m"; $ttl12+=$query8->row("ukuran"); }
            $pdf->Cell(8, 5, $of8s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$query8->row('kode_roll');
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

        }
        $grand3 = $ttl9 + $ttl10 + $ttl11 + $ttl12;
        $grand_total3 = number_format($grand3,2,",",".");
        $new_grand = $grand1 + $grand2 + $grand3;
        $new_grand2 = number_format($new_grand,2,",",".");
        $pdf->Ln(5);
        $pdf->setFillColor(156, 153, 152);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl9, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl10, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl11, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl12, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
        if($jml_pkt>480){} else {
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(10,246, 'GRAND TOTAL : '.$new_grand2.'');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(165,250, 'Pengirim');
        $pdf->Ln(35);
        $pdf->Cell(145);
        $pdf->Cell(37, 0, '', 1, 0, 'C' ); }
      }
      if($jml_pkt>480){
        //echo "lebih 200 paket";
        // -------------------
        $pdf->AddPage();
        $pdf->SetFont('Arial','',8);
        $pdf->Ln(18);
        $pdf->setFillColor(156, 153, 152); 
        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
        $pdf->setFillColor(224, 224, 222); 
        $ttl13=0;$ttl14=0;$ttl15=0;$ttl16=0;
        for($i=1; $i<41; $i++){
        $pdf->Ln(5);
          $of5 = $i+359;
          $query5 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of5");
          if($query5->num_rows()==1){
          $num5 = 359+1;
          $of5s = $of5 + 1;
          if($query5->row("st_folding") == "Finish"){ $ukuran = $query5->row("ukuran_yard")." y"; $ttl13+=$query5->row("ukuran_yard"); } else { $ukuran = $query5->row("ukuran")." m"; $ttl13+=$query5->row("ukuran"); }
          $pdf->Cell(8, 5, $of5s, 1, 0, 'C',1 );
          $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
          $kode = $wicode=='0' ? '-':$query5->row('kode_roll');
          $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
          $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, $i, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }
        
          $of6 = $i+399;
          $query6 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of6");
          if($query6->num_rows()==1){
          $num6 = 399+1;
          $of6s = $of6 + 1;
          if($query6->row("st_folding") == "Finish"){ $ukuran = $query6->row("ukuran_yard")." y"; $ttl14+=$query6->row("ukuran_yard"); } else { $ukuran = $query6->row("ukuran")." m"; $ttl14+=$query6->row("ukuran"); }
            $pdf->Cell(8, 5, $of6s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$query6->row('kode_roll');
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

          $of7 = $i+439;
          $query7 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of7");
          if($query7->num_rows()==1){
          $num7 = 439+1;
          $of7s = $of7 + 1;
          if($query7->row("st_folding") == "Finish"){ $ukuran = $query7->row("ukuran_yard")." y"; $ttl15+=$query7->row("ukuran_yard"); } else { $ukuran = $query7->row("ukuran")." m"; $ttl15+=$query7->row("ukuran"); }
            $pdf->Cell(8, 5, $of7s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$query7->row('kode_roll');
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

          $of8 = $i+479;
          $query8 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of8");
          if($query8->num_rows()==1){
          $num8 = 479+1;
          $of8s = $of8 + 1;
          if($query8->row("st_folding") == "Finish"){ $ukuran = $query8->row("ukuran_yard")." y"; $ttl16+=$query8->row("ukuran_yard"); } else { $ukuran = $query8->row("ukuran")." m"; $ttl16+=$query8->row("ukuran"); }
            $pdf->Cell(8, 5, $of8s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$query8->row('kode_roll');
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

        }
        $grand4 = $ttl13 + $ttl14 + $ttl15 + $ttl16;
        $grand_total4 = number_format($grand4,2,",",".");
        $new_grand = $grand1 + $grand2 + $grand3 + $grand4;
        $new_grand2 = number_format($new_grand,2,",",".");
        $pdf->Ln(5);
        $pdf->setFillColor(156, 153, 152);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl13, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl14, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl15, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl16, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
        if($jml_pkt>520){} else {
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(10,246, 'GRAND TOTAL : '.$new_grand2.'');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(165,250, 'Pengirim');
        $pdf->Ln(35);
        $pdf->Cell(145);
        $pdf->Cell(37, 0, '', 1, 0, 'C' ); }
      }
      if($jml_pkt>520){
        //echo "lebih 200 paket";
        // -------------------
        $pdf->AddPage();
        $pdf->SetFont('Arial','',8);
        $pdf->Ln(18);
        $pdf->setFillColor(156, 153, 152); 
        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
        $pdf->setFillColor(224, 224, 222); 
        $ttl17=0;$ttl18=0;$ttl19=0;$ttl20=0;
        for($i=1; $i<41; $i++){
        $pdf->Ln(5);
          $of5 = $i+519;
          $query5 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of5");
          if($query5->num_rows()==1){
          $num5 = 519+1;
          $of5s = $of5 + 1;
          if($query5->row("st_folding") == "Finish"){ $ukuran = $query5->row("ukuran_yard")." y"; $ttl17+=$query5->row("ukuran_yard"); } else { $ukuran = $query5->row("ukuran")." m"; $ttl17+=$query5->row("ukuran"); }
          $pdf->Cell(8, 5, $of5s, 1, 0, 'C',1 );
          $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
          $kode = $wicode=='0' ? '-':$query5->row('kode_roll');
          $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
          $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, $i, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }
        
          $of6 = $i+559;
          $query6 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of6");
          if($query6->num_rows()==1){
          $num6 = 559+1;
          $of6s = $of6 + 1;
          if($query6->row("st_folding") == "Finish"){ $ukuran = $query6->row("ukuran_yard")." y"; $ttl18+=$query6->row("ukuran_yard"); } else { $ukuran = $query6->row("ukuran")." m"; $ttl18+=$query6->row("ukuran"); }
            $pdf->Cell(8, 5, $of6s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$query6->row('kode_roll');
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

          $of7 = $i+599;
          $query7 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of7");
          if($query7->num_rows()==1){
          $num7 = 599+1;
          $of7s = $of7 + 1;
          if($query7->row("st_folding") == "Finish"){ $ukuran = $query7->row("ukuran_yard")." y"; $ttl19+=$query7->row("ukuran_yard"); } else { $ukuran = $query7->row("ukuran")." m"; $ttl19+=$query7->row("ukuran"); }
            $pdf->Cell(8, 5, $of7s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$query7->row('kode_roll');
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

          $of8 = $i+639;
          $query8 = $this->db->query("SELECT * FROM v_roll WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of8");
          if($query8->num_rows()==1){
          $num8 = 639+1;
          $of8s = $of8 + 1;
          if($query8->row("st_folding") == "Finish"){ $ukuran = $query8->row("ukuran_yard")." y"; $ttl20+=$query8->row("ukuran_yard"); } else { $ukuran = $query8->row("ukuran")." m"; $ttl20+=$query8->row("ukuran_yard"); }
            $pdf->Cell(8, 5, $of8s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$query8->row('kode_roll');
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

        }
        $grand5 = $ttl17 + $ttl18 + $ttl19 + $ttl20;
        $grand_total5 = number_format($grand5,2,",",".");
        $new_grand = $grand1 + $grand2 + $grand3 + $grand4 + $grand5;
        $new_grand2 = number_format($new_grand,2,",",".");
        $pdf->Ln(5);
        $pdf->setFillColor(156, 153, 152);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl17, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl18, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl19, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl20, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(10,246, 'GRAND TOTAL : '.$new_grand2.'');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(165,250, 'Pengirim');
        $pdf->Ln(35);
        $pdf->Cell(145);
        $pdf->Cell(37, 0, '', 1, 0, 'C' );
      }
      $pdf->Output('I','penjualan.pdf'); 
    } else {
      echo "Token erorr";
    }
  } //end

  function packinglist(){
    $token = $this->uri->segment(3);
    $wicode = $this->uri->segment(4);
    $ar = array(
      '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    );
    $cek = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$token]);
    if($cek->num_rows() == 1){
    $row = $cek->row_array();
    $kode_pkg = $row['kd'];
    if($row['siap_jual']=="y"){
        $cek_isipaket = $this->data_model->get_byid('data_fol', ['posisi'=>$kode_pkg]);
    } else {
        $cek_isipaket = $this->data_model->get_byid('data_ig', ['loc_now'=>$kode_pkg]);
    }
    if($row['kepada']=="NULL"){
        $dt_pkg = "inpabrik";
    } else {
        $dt_pkg = "outside";
    }
    $dt = $cek_isipaket->result_array();
    $jml_pkt = $cek_isipaket->num_rows();
             $ex = explode('-', $row['tanggal_dibuat']);
             $printTgl = $ex[2]." ".$ar[$ex[1]]." ".$ex[0];
             $pdf = new FPDF('p','mm','A4');
              // membuat halaman baru
              $pdf->AddPage();
              // setting jenis font yang akan digunakan
              $pdf->SetTitle('Packing List - '.$kode_pkg.'');
      
              $pdf->SetFont('Arial','B',14);
              $pdf->Cell(0, 8, 'PACKING LIST', 1, 0, 'C' );
              $pdf->Ln(8);
              $pdf->Cell(0, 18, '', 1, 0, 'C' );
              $pdf->SetFont('Arial','',8);
              $pdf->Text(13,23, 'No');
              $pdf->Text(13,28, 'Jenis Kain');
              $pdf->Text(13,33, 'Kepada');
              $pdf->Text(35,23, ':  '.$row['no_sj'].'');
              $pdf->Text(35,28, ':  '.$row['kode_konstruksi'].'');
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
              $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
              $pdf->setFillColor(224, 224, 222); 
              $ttl1=0; $ttl2=0; $ttl3=0; $ttl4=0;
              for($i=0; $i<40; $i++){
              $pdf->Ln(5);
              $num = $i+1;
              $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query1 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $i");
                    $ukuran = $query1->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query1 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $i");
                    $kode_roll = $query1->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query1 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $i");
                      $kodenew1 = $query1->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query1 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $i");
                      $ukuran = $query1->row("ukuran_ori");
                      $satuan="Meter";
                  }
                  
              }         
              if($query1->num_rows()==1){
              $kode_roll = $query1->row("kode_roll");
              $ttl1+=$ukuran;

              $pdf->Cell(8, 5, $num, 1, 0, 'C',1 );
              $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
              $kode = $wicode=='0' ? '-':$kode_roll;
              $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
              $pdf->Cell(12, 5, '', 1, 0, 'C' );
              
              } else {
              $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, '', 1, 0, 'C' );
              $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
              $pdf->Cell(12, 5, '', 1, 0, 'C' );
              }

              $of2 = $i+40;
              $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query2 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of2");
                    $ukuran = $query2->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query2 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of2");
                    $kode_roll = $query2->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query2 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of2");
                      $kodenew1 = $query2->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query2 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of2");
                      $ukuran = $query2->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
              
              if($query2->num_rows()==1){
              $num2 = 40+1;
              $of2s = $of2 + 1;
              
              $kode_roll = $query2->row("kode_roll");
              
              $ttl2+=$ukuran;

              $pdf->Cell(8, 5, $of2s, 1, 0, 'C',1 );
              $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
              $kode = $wicode=='0' ? '-':$kode_roll;
              $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
              $pdf->Cell(12, 5, '', 1, 0, 'C' );
              
              } else {
              $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, '', 1, 0, 'C' );
              $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
              $pdf->Cell(12, 5, '', 1, 0, 'C' );
              }
              
              $of3 = $i+80;
              $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query3 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of3");
                    $ukuran = $query2->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query3 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of3");
                    $kode_roll = $query3->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query3 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of3");
                      $kodenew1 = $query3->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query3 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of3");
                      $ukuran = $query3->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
              
              if($query3->num_rows()==1){
              $num3 = 80+1;
              $of3s = $of3 + 1;
              
              
              $kode_roll = $query3->row("kode_roll");
              
              $ttl3+=$ukuran;

              $pdf->Cell(8, 5, $of3s, 1, 0, 'C',1 );
              $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
              $kode = $wicode=='0' ? '-':$kode_roll;
              $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
              $pdf->Cell(12, 5, '', 1, 0, 'C' );
              
              } else {
              $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, '', 1, 0, 'C' );
              $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
              $pdf->Cell(12, 5, '', 1, 0, 'C' );
              }

              $of4 = $i+120;
              $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query4 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of4");
                    $ukuran = $query4->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query4 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of4");
                    $kode_roll = $query4->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query4 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of4");
                      $kodenew1 = $query4->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query4 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of4");
                      $ukuran = $query4->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
              
              if($query4->num_rows()==1){
              $num4 = 120+1;
              $of4s = $of4 + 1;
              
              
              $kode_roll = $query4->row("kode_roll");
              
              $ttl4+=$ukuran;

              $pdf->Cell(8, 5, $of4s, 1, 0, 'C',1 );
              $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
              $kode = $wicode=='0' ? '-':$kode_roll;
              $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
              $pdf->Cell(12, 5, '', 1, 0, 'C' );
              
              } else {
              $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, '', 1, 0, 'C' );
              $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
              $pdf->Cell(12, 5, '', 1, 0, 'C' );  
              }

              }
              $grand1 = $ttl1 + $ttl2 + $ttl3 + $ttl4;
              $grand_total = number_format($grand1,2,",",".");
              $pdf->Ln(5);
              $pdf->setFillColor(156, 153, 152);
              $pdf->SetFont('Arial','B',8);
              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, ''.$ttl1.'', 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, $ttl2, 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, $ttl3, 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

              $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
              $pdf->Cell(15, 5, $ttl4, 1, 0, 'C',1 );
              $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
              $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
              if($jml_pkt>160){} else {
              $pdf->SetFont('Arial','B',10);
              $pdf->Text(10,252, 'GRAND TOTAL : '.$grand_total.'');
              $pdf->SetFont('Arial','',10);
              $pdf->Text(165,258, 'Pengirim');
              $pdf->Ln(35);
              $pdf->Cell(145);
              $pdf->Cell(37, 0, '', 1, 0, 'C' ); }
              
              
      
      if($jml_pkt>160){
        //echo "lebih 200 paket";
        // -------------------
        $pdf->AddPage();
        $pdf->SetFont('Arial','',8);
        $pdf->Ln(18);
        $pdf->setFillColor(156, 153, 152); 
        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
        $pdf->setFillColor(224, 224, 222); 
        $ttl5=0; $ttl6=0; $ttl7=0; $ttl8=0;
        for($i=1; $i<41; $i++){
        $pdf->Ln(5);
          $of5 = $i+159;
          $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query5 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of5");
                    $ukuran = $query5->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query5 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of5");
                    $kode_roll = $query5->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query5 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of5");
                      $kodenew1 = $query5->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query5 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of5");
                      $ukuran = $query5->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
          //$query5 = $this->db->query("SELECT * FROM new_tb_isi_paket WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of5");
          if($query5->num_rows()==1){
          $num5 = 159+1;
          $of5s = $of5 + 1;
          $kode_roll = $query5->row("kode_roll");
          
          $ttl5+=$ukuran;

          $pdf->Cell(8, 5, $of5s, 1, 0, 'C',1 );
          $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
          $kode = $wicode=='0' ? '-':$kode_roll;
          $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
          $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, $i, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }
        
          $of6 = $i+199;
          $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query6 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of6");
                    $ukuran = $query5->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query6 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of6");
                    $kode_roll = $query6->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query6 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of6");
                      $kodenew1 = $query6->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query6 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of6");
                      $ukuran = $query6->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
          //$query6 = $this->db->query("SELECT * FROM new_tb_isi_paket WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of6");
          if($query6->num_rows()==1){
          $num6 = 199+1;
          $of6s = $of6 + 1;
          $kode_roll = $query6->row("kode_roll");
          
          $ttl6+=$ukuran;

            $pdf->Cell(8, 5, $of6s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            
            $kode = $wicode=='0' ? '-':$kode_roll;
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

          $of7 = $i+239;
          $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query7 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of7");
                    $ukuran = $query7->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query7 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of7");
                    $kode_roll = $query7->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query7 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of7");
                      $kodenew1 = $query7->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query7 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of7");
                      $ukuran = $query7->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
          //$query7 = $this->db->query("SELECT * FROM new_tb_isi_paket WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of7");
          if($query7->num_rows()==1){
          $num7 = 239+1;
          $of7s = $of7 + 1;
          $kode_roll = $query7->row("kode_roll");
          
          $ttl7+=$ukuran;

            $pdf->Cell(8, 5, $of7s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            
            $kode = $wicode=='0' ? '-':$kode_roll;
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

          $of8 = $i+279;
          $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query8 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of8");
                    $ukuran = $query8->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query8 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of8");
                    $kode_roll = $query8->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query8 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of8");
                      $kodenew1 = $query8->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query8 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of8");
                      $ukuran = $query8->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
          //$query8 = $this->db->query("SELECT * FROM new_tb_isi_paket WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of8");
          if($query8->num_rows()==1){
          $num8 = 279+1;
          $of8s = $of8 + 1;
          $kode_roll = $query8->row("kode_roll");
          
          $ttl8+=$ukuran;

            $pdf->Cell(8, 5, $of8s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            
            $kode = $wicode=='0' ? '-':$kode_roll;
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

        }
        $grand2 = $ttl5 + $ttl6 + $ttl7 + $ttl8;
        $grand_total2 = number_format($grand2,2,",",".");
        $new_grand = $grand1 + $grand2;
        $new_grand2 = number_format($new_grand,2,",",".");
        $pdf->Ln(5);
        $pdf->setFillColor(156, 153, 152);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl5, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl6, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl7, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl8, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
        if($jml_pkt>320){} else {
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(10,246, 'GRAND TOTAL : '.$new_grand.'');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(165,250, 'Pengirim');
        $pdf->Ln(35);
        $pdf->Cell(145);
        $pdf->Cell(37, 0, '', 1, 0, 'C' ); }
      }

      if($jml_pkt>320){
        //echo "lebih 200 paket";
        // -------------------
        $pdf->AddPage();
        $pdf->SetFont('Arial','',8);
        $pdf->Ln(18);
        $pdf->setFillColor(156, 153, 152); 
        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
        $pdf->setFillColor(224, 224, 222);
        $ttl9=0; $ttl10=0; $ttl11=0; $ttl12=0;
        for($i=1; $i<41; $i++){
        $pdf->Ln(5);
          $of5 = $i+319;
          $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query5 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of5");
                    $ukuran = $query5->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query5 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of5");
                    $kode_roll = $query5->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query5 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of5");
                      $kodenew1 = $query5->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query5 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of5");
                      $ukuran = $query5->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
          //$query5 = $this->db->query("SELECT * FROM new_tb_isi_paket WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of5");
          if($query5->num_rows()==1){
          $num5 = 319+1;
          $of5s = $of5 + 1;
          $kode_roll = $query5->row("kode_roll");
          
          $ttl9+=$ukuran;

          $pdf->Cell(8, 5, $of5s, 1, 0, 'C',1 );
          $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
          $kode = $wicode=='0' ? '-':$kode_roll;
          $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
          $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, $i, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }
        
          $of6 = $i+359;
          $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query6 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of6");
                    $ukuran = $query6->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query6 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of6");
                    $kode_roll = $query6->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query6 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of6");
                      $kodenew1 = $query6->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query6 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of6");
                      $ukuran = $query6->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
          //$query6 = $this->db->query("SELECT * FROM new_tb_isi_paket WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of6");
          if($query6->num_rows()==1){
          $num6 = 359+1;
          $of6s = $of6 + 1;
          $kode_roll = $query6->row("kode_roll");
          
          $ttl10+=$ukuran;

            $pdf->Cell(8, 5, $of6s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$kode_roll;
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

          $of7 = $i+399;
          $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query7 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of7");
                    $ukuran = $query7->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query7 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of7");
                    $kode_roll = $query7->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query7 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of7");
                      $kodenew1 = $query7->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query7 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of7");
                      $ukuran = $query7->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
          //$query7 = $this->db->query("SELECT * FROM new_tb_isi_paket WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of7");
          if($query7->num_rows()==1){
          $num7 = 399+1;
          $of7s = $of7 + 1;
          $kode_roll = $query7->row("kode_roll");
          
          $ttl11+=$ukuran;

            $pdf->Cell(8, 5, $of7s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$kode_roll;
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

          $of8 = $i+439;
          $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query8 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of8");
                    $ukuran = $query8->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query8 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of8");
                    $kode_roll = $query8->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query8 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of8");
                      $kodenew1 = $query8->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query8 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of8");
                      $ukuran = $query8->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
          //$query8 = $this->db->query("SELECT * FROM new_tb_isi_paket WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of8");
          if($query8->num_rows()==1){
          $num8 = 439+1;
          $of8s = $of8 + 1;
          $kode_roll = $query8->row("kode_roll");
          
          $ttl12+=$ukuran;

            $pdf->Cell(8, 5, $of8s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$kode_roll;
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

        }
        $grand3 = $ttl9 + $ttl10 + $ttl11 + $ttl12;
        $grand_total3 = number_format($grand3,2,",",".");
        $new_grand = $grand1 + $grand2 + $grand3;
        $new_grand2 = number_format($new_grand,2,",",".");
        $pdf->Ln(5);
        $pdf->setFillColor(156, 153, 152);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl9, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl10, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl11, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl12, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
        if($jml_pkt>480){} else {
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(10,246, 'GRAND TOTAL : '.$new_grand2.'');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(165,250, 'Pengirim');
        $pdf->Ln(35);
        $pdf->Cell(145);
        $pdf->Cell(37, 0, '', 1, 0, 'C' ); }
      }
      if($jml_pkt>480){
        //echo "lebih 200 paket";
        // -------------------
        $pdf->AddPage();
        $pdf->SetFont('Arial','',8);
        $pdf->Ln(18);
        $pdf->setFillColor(156, 153, 152); 
        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
        $pdf->setFillColor(224, 224, 222); 
        $ttl13=0;$ttl14=0;$ttl15=0;$ttl16=0;
        for($i=1; $i<41; $i++){
        $pdf->Ln(5);
          $of5 = $i+359;
          $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query5 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of5");
                    $ukuran = $query5->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query5 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of5");
                    $kode_roll = $query5->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  
                  if($row['kepada']=="Pusatex"){
                      $query5 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of5");
                      $kodenew1 = $query5->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query5 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of5");
                      $ukuran = $query5->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
          //$query5 = $this->db->query("SELECT * FROM new_tb_isi_paket WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of5");
          if($query5->num_rows()==1){
          $num5 = 359+1;
          $of5s = $of5 + 1;
          $kode_roll = $query5->row("kode_roll");
          
          $ttl13+=$ukuran;

          $pdf->Cell(8, 5, $of5s, 1, 0, 'C',1 );
          $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
          $kode = $wicode=='0' ? '-':$kode_roll;
          $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
          $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, $i, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }
        
          $of6 = $i+399;
          $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query6 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of6");
                    $ukuran = $query6->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query6 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of6");
                    $kode_roll = $query6->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query6 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of6");
                      $kodenew1 = $query6->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query6 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of6");
                      $ukuran = $query6->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
          //$query6 = $this->db->query("SELECT * FROM new_tb_isi_paket WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of6");
          if($query6->num_rows()==1){
          $num6 = 399+1;
          $of6s = $of6 + 1;
          $kode_roll = $query6->row("kode_roll");
          
          $ttl14+=$ukuran;

            $pdf->Cell(8, 5, $of6s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$kode_roll;
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

          $of7 = $i+439;
          $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query7 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of7");
                    $ukuran = $query7->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query7 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of7");
                    $kode_roll = $query7->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query7 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of7");
                      $kodenew1 = $query7->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query7 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of7");
                      $ukuran = $query7->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
          //$query7 = $this->db->query("SELECT * FROM new_tb_isi_paket WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of7");
          if($query7->num_rows()==1){
          $num7 = 439+1;
          $of7s = $of7 + 1;
          $kode_roll = $query7->row("kode_roll");
          
          $ttl15+=$ukuran;

            $pdf->Cell(8, 5, $of7s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$kode_roll;
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

          $of8 = $i+479;
          $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query8 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of8");
                    $ukuran = $query8->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query8 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of8");
                    $kode_roll = $query8->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query8 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of8");
                      $kodenew1 = $query8->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query8 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of8");
                      $ukuran = $query8->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
          //$query8 = $this->db->query("SELECT * FROM new_tb_isi_paket WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of8");
          if($query8->num_rows()==1){
          $num8 = 479+1;
          $of8s = $of8 + 1;
          $kode_roll = $query8->row("kode_roll");
          
          $ttl16+=$ukuran;

            $pdf->Cell(8, 5, $of8s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$kode_roll;
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

        }
        $grand4 = $ttl13 + $ttl14 + $ttl15 + $ttl16;
        $grand_total4 = number_format($grand4,2,",",".");
        $new_grand = $grand1 + $grand2 + $grand3 + $grand4;
        $new_grand2 = number_format($new_grand,2,",",".");
        $pdf->Ln(5);
        $pdf->setFillColor(156, 153, 152);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl13, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl14, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl15, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl16, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
        if($jml_pkt>520){} else {
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(10,246, 'GRAND TOTAL : '.$new_grand2.'');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(165,250, 'Pengirim');
        $pdf->Ln(35);
        $pdf->Cell(145);
        $pdf->Cell(37, 0, '', 1, 0, 'C' ); }
      }
      if($jml_pkt>520){
        //echo "lebih 200 paket";
        // -------------------
        $pdf->AddPage();
        $pdf->SetFont('Arial','',8);
        $pdf->Ln(18);
        $pdf->setFillColor(156, 153, 152); 
        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'No', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, 'Ukuran', 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, 'Kode', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, 'Ket', 1, 0, 'C',1 );
        $pdf->setFillColor(224, 224, 222); 
        $ttl17=0;$ttl18=0;$ttl19=0;$ttl20=0;
        for($i=1; $i<41; $i++){
        $pdf->Ln(5);
          $of5 = $i+519;
          $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query5 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of5");
                    $ukuran = $query5->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query5 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of5");
                    $kode_roll = $query5->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  
                  if($row['kepada']=="Pusatex"){
                      $query5 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of5");
                      $kodenew1 = $query5->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query5 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of5");
                      $ukuran = $query5->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
          //$query5 = $this->db->query("SELECT * FROM new_tb_isi_paket WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of5");
          if($query5->num_rows()==1){
          $num5 = 519+1;
          $of5s = $of5 + 1;
          $kode_roll = $query5->row("kode_roll");
          
          $ttl17+=$ukuran;

          $pdf->Cell(8, 5, $of5s, 1, 0, 'C',1 );
          $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
          $kode = $wicode=='0' ? '-':$kode_roll;
          $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
          $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, $i, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }
        
          $of6 = $i+559;
          $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query6 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of6");
                    $ukuran = $query6->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query6 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of6");
                    $kode_roll = $query6->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query6 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of6");
                      $kodenew1 = $query6->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query6 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of6");
                      $ukuran = $query6->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
          //$query6 = $this->db->query("SELECT * FROM new_tb_isi_paket WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of6");
          if($query6->num_rows()==1){
          $num6 = 559+1;
          $of6s = $of6 + 1;
          $kode_roll = $query6->row("kode_roll");
          
          $ttl18+=$ukuran;

            $pdf->Cell(8, 5, $of6s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$kode_roll;
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

          $of7 = $i+599;
          $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query7 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of7");
                    $ukuran = $query7->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query7 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of7");
                    $kode_roll = $query7->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query7 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of7");
                      $kodenew1 = $query7->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query7 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of7");
                      $ukuran = $query7->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
          //$query7 = $this->db->query("SELECT * FROM new_tb_isi_paket WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of7");
          if($query7->num_rows()==1){
          $num7 = 599+1;
          $of7s = $of7 + 1;
          $kode_roll = $query7->row("kode_roll");
          
          $ttl19+=$ukuran;

            $pdf->Cell(8, 5, $of7s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$kode_roll;
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

          $of8 = $i+639;
          $ukuran = 0; $satuan="null";
              if($row['siap_jual']=="y"){ 
                if($dt_pkg=="inpabrik"){
                    $query8 = $this->db->query("SELECT * FROM data_fol WHERE posisi='$kode_pkg' LIMIT 1 OFFSET $of8");
                    $ukuran = $query8->row("ukuran");
                    if($query1->row("jns_fold") == "Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                } else {
                    $query8 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of8");
                    $kode_roll = $query8->row("kode_roll");
                    $ukuran = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("ukuran");
                    $ts = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->row("jns_fold");
                    if($ts=="Grey"){ $satuan="Meter"; } else { $satuan="Yard"; }
                }
              } else {
                  if($row['kepada']=="Pusatex"){
                      $query8 = $this->db->query("SELECT * FROM kode_roll_outsite WHERE kd='$kode_pkg' LIMIT 1 OFFSET $of8");
                      $kodenew1 = $query8->row("kode_roll");
                      $ukuran = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kodenew1'")->row("ukuran_ori");
                      $satuan="Meter";
                  } else {
                      $query8 = $this->db->query("SELECT * FROM data_ig WHERE loc_now='$kode_pkg' LIMIT 1 OFFSET $of8");
                      $ukuran = $query8->row("ukuran_ori");
                      $satuan="Meter";
                  }
              }
          //$query8 = $this->db->query("SELECT * FROM new_tb_isi_paket WHERE id_kdlist='$kode_pkg' LIMIT 1 OFFSET $of8");
          if($query8->num_rows()==1){
          $num8 = 639+1;
          $of8s = $of8 + 1;
          $kode_roll = $query8->row("kode_roll");
          
          $ttl20+=$ukuran;

            $pdf->Cell(8, 5, $of8s, 1, 0, 'C',1 );
            $pdf->Cell(15, 5, $ukuran, 1, 0, 'C' );
            $kode = $wicode=='0' ? '-':$kode_roll;
            $pdf->Cell(12.47, 5, $kode, 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          } else {
            $pdf->Cell(8, 5, '', 1, 0, 'C',1 );
            $pdf->Cell(15, 5, '', 1, 0, 'C' );
            $pdf->Cell(12.47, 5, '', 1, 0, 'C' );
            $pdf->Cell(12, 5, '', 1, 0, 'C' );
          }

        }
        $grand5 = $ttl17 + $ttl18 + $ttl19 + $ttl20;
        $grand_total5 = number_format($grand5,2,",",".");
        $new_grand = $grand1 + $grand2 + $grand3 + $grand4 + $grand5;
        $new_grand2 = number_format($new_grand,2,",",".");
        $pdf->Ln(5);
        $pdf->setFillColor(156, 153, 152);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl17, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl18, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl19, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );

        $pdf->Cell(8, 5, 'TTL', 1, 0, 'C',1 );
        $pdf->Cell(15, 5, $ttl20, 1, 0, 'C',1 );
        $pdf->Cell(12.47, 5, '', 1, 0, 'C',1 );
        $pdf->Cell(12, 5, '', 1, 0, 'C',1 );
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(10,246, 'GRAND TOTAL : '.$new_grand2.'');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(165,250, 'Pengirim');
        $pdf->Ln(35);
        $pdf->Cell(145);
        $pdf->Cell(37, 0, '', 1, 0, 'C' );
      }
      $pdf->Output('I','Packing-list.pdf'); 
    } else {
      echo "Token erorr";
    }
  } //end
  
  
}
?>
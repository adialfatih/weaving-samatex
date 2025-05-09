<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetakrjs2 extends CI_Controller
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
  
  function packinglist(){
    $token = $this->uri->segment(3);
    $var2 = $this->data_model->get_byid('tb_pkg_sementara', ['kodepkg'=>$token])->row_array();
             $pdf = new FPDF('p','mm','A4');
              // membuat halaman baru
              $pdf->AddPage();
              // setting jenis font yang akan digunakan
              $pdf->SetTitle('Packing List - '.$token.'');
      
              $pdf->SetFont('Arial','B',14);
              $pdf->Image('https://data.rindangjati.com/assets/logo_rjs2.jpg', 13, 10, 25, 0, 'JPG');
              $pdf->Cell(0, 6, 'PT RINDANG JATI SPINNING', 0, 0, 'C' );
              $pdf->SetFont('Arial','',10);
              $pdf->Ln(6);
              $pdf->Cell(0, 6, 'Jl Raya Jatirejo Rt 004 Rw 001 Kel Jatirejo Kec Ampel Gading Pemalang', 0, 0, 'C' );
              $pdf->Ln(5);
              $pdf->Cell(0, 6, 'TELP (0285) 5750072 FAX (0285) 5750073', 0, 0, 'C' );
              $pdf->Line(13, 33, 210-15, 33);
              $pdf->Line(13, 33.1, 210-15, 33.1);
              $pdf->Line(13, 33.2, 210-15, 33.2);
              $pdf->Line(13, 33.8, 210-15, 33.8);
              $pdf->Ln(18);
              $pdf->Cell(120, 5, '', 0, 0, 'L' );
              $pdf->Cell(30, 5, 'Kode Packing', 0, 0, 'L' );
              $pdf->Cell(5, 5, ':', 0, 0, 'L' );
              $pdf->Cell(30, 5, ''.$token.'', 0, 0, 'L' );
              $pdf->Ln(5);
              $pdf->Cell(120, 5, '', 0, 0, 'L' );
              $pdf->Cell(30, 5, 'Tanggal Packing', 0, 0, 'L' );
              $pdf->Cell(5, 5, ':', 0, 0, 'L' );
              $pdf->Cell(30, 5, ''.$var2['tgl_pkg'].'', 0, 0, 'L' );
              $pdf->Ln(13);
              $pdf->Cell(3, 10, '', 0, 0, 'L' );
              $pdf->Cell(10, 10, 'No.', 1, 0, 'C' );
              $pdf->Cell(25, 10, 'Kode Roll', 1, 0, 'C' );
              $pdf->Cell(37, 10, 'Tanggal Potong', 1, 0, 'C' );
              $pdf->Cell(50, 5, 'Nomor', 1, 0, 'C' );
              $pdf->Cell(30, 10, 'Panjang (Mtr)', 1, 0, 'C' );
              $pdf->Cell(30, 10, 'Konstruksi', 1, 0, 'C' );
              $pdf->Ln(5);
              $pdf->Cell(3, 5, '', 0, 0, 'L' );
              $pdf->Cell(10, 5, '', 0, 0, 'C' );
              $pdf->Cell(25, 5, '', 0, 0, 'C' );
              $pdf->Cell(37, 5, '', 0, 0, 'C' );
              $pdf->Cell(25, 5, 'M/C', 1, 0, 'C' );
              $pdf->Cell(25, 5, 'Beam', 1, 0, 'C' );
              $total_panjang = 0;
              
              $pdf->Ln(5);
              $pdf->Cell(3, 5, '', 0, 0, 'L' );
              $pdf->Cell(10, 5, 'tes1', 1, 0, 'C' );
              $pdf->Cell(25, 5, 'tes2', 1, 0, 'C' );
              $pdf->Cell(37, 5, 'tes3', 1, 0, 'C' );
              $pdf->Cell(25, 5, 'tes4', 1, 0, 'C' );
              $pdf->Cell(25, 5, 'tes5', 1, 0, 'C' );
              $pdf->Cell(30, 5, 'tes6', 1, 0, 'C' );
              $pdf->Cell(30, 5, 'tes7', 1, 0, 'C' );
              //$total_panjang += floatval($dtroll['ukuran_ori']);
              
              //$var2 = round($total_panjang);
              if(fmod($var2['jml_pkg'], 1) !== 0.00){
                $printTotal = number_format($var2['jml_pkg'],2,',','.');
              } else {
                $printTotal = number_format($var2['jml_pkg'],0,',','.');
              }
              $pdf->SetFont('Arial','B',10);
              $pdf->Ln(5);
              $pdf->Cell(3, 5, '', 0, 0, 'L' );
              $pdf->Cell(122, 5, 'Total Panjang', 1, 0, 'C' );
              $pdf->Cell(30, 5, ''.$printTotal.'', 1, 0, 'C' );
              $pdf->Cell(30, 5, '', 1, 0, 'C' );
              $pdf->Ln(7);
              $pdf->SetFont('Arial','',10);
              $pdf->Cell(3, 5, '', 0, 0, 'L' );
              $pdf->Cell(45, 5, 'Diperiksa Oleh', 0, 0, 'L' );
              $pdf->Cell(40, 5, 'Driver', 0, 0, 'L' );
              $pdf->Cell(40, 5, 'Security', 0, 0, 'L' );
              $pdf->Cell(40, 5, 'Mengetahui', 0, 0, 'L' );
              $pdf->Output('I','cetak-packinglist-'.$token.'.pdf'); 
    
  } //end
  
  
}
?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetakmng extends CI_Controller
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
  

  function penjualan(){
        $bulan = $this->uri->segment(3);
        $ex = explode('-', $bulan);
        $ar = array(
            '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        );
        $thn = $ex[0];
        $bln = $ex[1];
        //echo "Laporan penjualan bulan ".$ar[$bln]."<br>";
        $judul = "Laporan Penjualan Bulan ".$ar[$bln]."";
        $judul_file = "Laporan-Penjualan-Bulan-".$ar[$bln].".pdf";
        $query = $this->db->query("SELECT * FROM view_nota2 WHERE DATE_FORMAT(tgl_nota, '%m') = '$bln'");
        $kons = array();
        foreach($query->result() as $val){
            //echo $val->konstruksi." - ".$val->total_panjang."<br>";
            if (in_array($val->konstruksi, $kons)) {
                //echo "Mangga ditemukan dalam array.";
            } else {
                $kons[]=$val->konstruksi;
            }
        }
        $data1 = array();
        $data2 = array();
        $data3 = array();
        $data4 = array();
        foreach($kons as $kons){
            $jumlah = $this->db->query("SELECT jns_fold,SUM(total_panjang) as ukr FROM view_nota2 WHERE konstruksi='$kons' AND DATE_FORMAT(tgl_nota, '%m') = '$bln'")->row("ukr");
            $jns = $this->db->query("SELECT jns_fold,SUM(total_panjang) as ukr FROM view_nota2 WHERE konstruksi='$kons' AND DATE_FORMAT(tgl_nota, '%m') = '$bln'")->row("jns_fold");
            //echo "".$kons." = ".$jumlah."<br>";
            $data1[]=$kons;
            $data2[]=$jumlah;
            if(fmod($jumlah, 1) !== 0.00){
                $data3[] = number_format($jumlah,2,',','.');
            } else {
                $data3[] = number_format($jumlah,0,',','.');
            }
            $data4[]=$jns;
        }
        $total_jual = array_sum($data2);
        if(fmod($total_jual, 1) !== 0.00){
            $total_jual2 = number_format($total_jual,2,',','.');
        } else {
            $total_jual2 = number_format($total_jual,0,',','.');
        }
        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
        // setting jenis font yang akan digunakan
        $pdf->SetTitle(''.$judul.'');
        $pdf->setFillColor(224, 224, 222);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0, 6, $judul, 0, 0, 'C' );
        $pdf->SetFont('Arial','B',11);
        $pdf->Ln(20);
        $pdf->Cell(12, 10, 'No.', 1, 0, 'C',1 );
        $pdf->Cell(45, 10, 'Konstruksi', 1, 0, 'C',1 );
        $pdf->Cell(35, 10, 'Jumlah', 1, 0, 'C' ,1);
        $pdf->Cell(12, 10, 'No.', 1, 0, 'C',1 ,1);
        $pdf->Cell(45, 10, 'Konstruksi', 1, 0, 'C',1 );
        $pdf->Cell(35, 10, 'Jumlah', 1, 0, 'C',1 );
        $pdf->SetFont('Arial','',11);
        $pdf->Ln(10);
        for ($i=0; $i <30 ; $i++) { 
            $num = $i+1;
            $pdf->Cell(12, 7, ''.$num.'', 1, 0, 'C',1 );
            $pdf->Cell(45, 7, ''.$data1[$i].'', 1, 0, 'C' );
            $pdf->Cell(35, 7, ''.$data3[$i].'', 1, 0, 'C' );
            
            

            $iplus = $i+30;
            $numplus = $i+31;
             
            $pdf->Cell(12, 7, ''.$numplus.'', 1, 0, 'C',1 );
            $pdf->Cell(45, 7, ''.$data1[$iplus].'', 1, 0, 'C' );
            $pdf->Cell(35, 7, ''.$data3[$iplus].'', 1, 0, 'C' );
            
            $pdf->Ln(7);
        }
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(184, 10, 'Total Penjualan : '.$total_jual2.'', 1, 0, 'C',1 );
        $pdf->Output('D',$judul_file); 
  } //end

  function produksi(){
        $bulan = $this->uri->segment(3);
        $ex = explode('-', $bulan);
        $ar = array(
            '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        );
        $thn = $ex[0];
        $bln = $ex[1];
        $judul_file = "Laporan-Hasil-Produksi-Mesin-AJL-Samatex-Bulan-".$ar[$bln].".pdf";
        $query = $this->db->query("SELECT * FROM dt_produksi_mesin WHERE DATE_FORMAT(tanggal_produksi, '%m') = '$bln' AND lokasi='Samatex'");
        $kons = array();
        foreach($query->result() as $val){
            //echo $val->konstruksi." - ".$val->total_panjang."<br>";
            if (in_array($val->kode_konstruksi, $kons)) {
                //echo "Mangga ditemukan dalam array.";
            } else {
                $kons[]=$val->kode_konstruksi;
            }
        }
        $data1 = array();
        $data2 = array();
        $data3 = array();
        $data4 = array();
        foreach($kons as $kons){
            $jumlah = $this->db->query("SELECT SUM(hasil) as ukr FROM dt_produksi_mesin WHERE kode_konstruksi='$kons' AND DATE_FORMAT(tanggal_produksi, '%m') = '$bln' AND lokasi='Samatex'")->row("ukr");
            
            $data1[]=$kons;
            $data2[]=$jumlah;
            if(fmod($jumlah, 1) !== 0.00){
                $data3[] = number_format($jumlah,2,',','.');
            } else {
                $data3[] = number_format($jumlah,0,',','.');
            }
        }
        $total_produksi = array_sum($data2);
        if(fmod($total_produksi, 1) !== 0.00){
            $total_produksi2 = number_format($total_produksi,2,',','.');
        } else {
            $total_produksi2 = number_format($total_produksi,0,',','.');
        }
        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
        // setting jenis font yang akan digunakan
        $pdf->SetTitle('Laporan Hasil Produksi Mesin AJL Samatex');
        $pdf->setFillColor(224, 224, 222);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0, 6, 'Laporan Hasil Produksi Mesin AJL Bulan '.$ar[$bln].'', 0, 0, 'C' );
        $pdf->Ln(6);
        $pdf->Cell(0, 6, 'PT. Salam Mandiri Textile', 0, 0, 'C' );
        $pdf->SetFont('Arial','B',11);
        $pdf->Ln(20);
        $pdf->Cell(12, 10, 'No.', 1, 0, 'C',1 );
        $pdf->Cell(45, 10, 'Konstruksi', 1, 0, 'C',1 );
        $pdf->Cell(35, 10, 'Jumlah', 1, 0, 'C' ,1);
        $pdf->Cell(12, 10, 'No.', 1, 0, 'C',1 ,1);
        $pdf->Cell(45, 10, 'Konstruksi', 1, 0, 'C',1 );
        $pdf->Cell(35, 10, 'Jumlah', 1, 0, 'C',1 );
        $pdf->SetFont('Arial','',11);
        $pdf->Ln(10);
        for ($i=0; $i <30 ; $i++) { 
            $num = $i+1;
            $pdf->Cell(12, 7, ''.$num.'', 1, 0, 'C',1 );
            $pdf->Cell(45, 7, ''.$data1[$i].'', 1, 0, 'C' );
            $pdf->Cell(35, 7, ''.$data3[$i].'', 1, 0, 'C' );
            
            

            $iplus = $i+30;
            $numplus = $i+31;
             
            $pdf->Cell(12, 7, ''.$numplus.'', 1, 0, 'C',1 );
            $pdf->Cell(45, 7, ''.$data1[$iplus].'', 1, 0, 'C' );
            $pdf->Cell(35, 7, ''.$data3[$iplus].'', 1, 0, 'C' );
            
            $pdf->Ln(7);
        }
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(184, 10, 'Total Produksi Mesin AJL : '.$total_produksi2.'', 1, 0, 'C',1 );
        $pdf->Output('D',$judul_file); 
  } //end

  function produksirjs(){
        $bulan = $this->uri->segment(3);
        $ex = explode('-', $bulan);
        $ar = array(
            '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        );
        $thn = $ex[0];
        $bln = $ex[1];
        
        $judul_file = "Laporan-Hasil-Produksi-Mesin-AJL-RJS-Bulan-".$ar[$bln].".pdf";
        $query = $this->db->query("SELECT * FROM dt_produksi_mesin WHERE DATE_FORMAT(tanggal_produksi, '%m') = '$bln' AND lokasi='RJS'");
        $kons = array();
        foreach($query->result() as $val){
            //echo $val->konstruksi." - ".$val->total_panjang."<br>";
            if (in_array($val->kode_konstruksi, $kons)) {
                //echo "Mangga ditemukan dalam array.";
            } else {
                $kons[]=$val->kode_konstruksi;
            }
        }
        $data1 = array();
        $data2 = array();
        $data3 = array();
        $data4 = array();
        foreach($kons as $kons){
            $jumlah = $this->db->query("SELECT SUM(hasil) as ukr FROM dt_produksi_mesin WHERE kode_konstruksi='$kons' AND DATE_FORMAT(tanggal_produksi, '%m') = '$bln' AND lokasi='RJS'")->row("ukr");
            
            $data1[]=$kons;
            $data2[]=$jumlah;
            if(fmod($jumlah, 1) !== 0.00){
                $data3[] = number_format($jumlah,2,',','.');
            } else {
                $data3[] = number_format($jumlah,0,',','.');
            }
        }
        $total_produksi = array_sum($data2);
        if(fmod($total_produksi, 1) !== 0.00){
            $total_produksi2 = number_format($total_produksi,2,',','.');
        } else {
            $total_produksi2 = number_format($total_produksi,0,',','.');
        }
        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
        // setting jenis font yang akan digunakan
        $pdf->SetTitle('Laporan Hasil Produksi Mesin AJL Rindang Jati Spinning');
        $pdf->setFillColor(224, 224, 222);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0, 6, 'Laporan Hasil Produksi Mesin AJL Bulan '.$ar[$bln].'', 0, 0, 'C' );
        $pdf->Ln(6);
        $pdf->Cell(0, 6, 'PT. Rindang Jati Spinning', 0, 0, 'C' );
        $pdf->SetFont('Arial','B',11);
        $pdf->Ln(20);
        $pdf->Cell(12, 10, 'No.', 1, 0, 'C',1 );
        $pdf->Cell(45, 10, 'Konstruksi', 1, 0, 'C',1 );
        $pdf->Cell(35, 10, 'Jumlah', 1, 0, 'C' ,1);
        $pdf->Cell(12, 10, 'No.', 1, 0, 'C',1 ,1);
        $pdf->Cell(45, 10, 'Konstruksi', 1, 0, 'C',1 );
        $pdf->Cell(35, 10, 'Jumlah', 1, 0, 'C',1 );
        $pdf->SetFont('Arial','',11);
        $pdf->Ln(10);
        for ($i=0; $i <30 ; $i++) { 
            $num = $i+1;
            $pdf->Cell(12, 7, ''.$num.'', 1, 0, 'C',1 );
            $pdf->Cell(45, 7, ''.$data1[$i].'', 1, 0, 'C' );
            $pdf->Cell(35, 7, ''.$data3[$i].'', 1, 0, 'C' );
            
            

            $iplus = $i+30;
            $numplus = $i+31;
             
            $pdf->Cell(12, 7, ''.$numplus.'', 1, 0, 'C',1 );
            $pdf->Cell(45, 7, ''.$data1[$iplus].'', 1, 0, 'C' );
            $pdf->Cell(35, 7, ''.$data3[$iplus].'', 1, 0, 'C' );
            
            $pdf->Ln(7);
        }
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(184, 10, 'Total Produksi Mesin AJL : '.$total_produksi2.'', 1, 0, 'C',1 );
        $pdf->Output('D',$judul_file); 
  } //end
  
  function tesdata(){
        $query = $this->db->query("SELECT * FROM data_fol WHERE konstruksi LIKE 'SM19' AND posisi LIKE '%RAHMAWATI%'");
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>No</td>";
        echo "<td>Kode</td>";
        echo "<td>No Mesin</td>";
        echo "<td>Operator Inspect</td>";
        echo "<td>Tanggal Inspect</td>";
        echo "<td>No SJ - Tanggal Kirim</td>";
        echo "<td>Kirim Ke</td>";
        echo "</tr>";
        $n=1;
        foreach($query->result() as $val):
            echo "<tr>";
            echo "<td>".$n."</td>";
            echo "<td>".$val->kode_roll."</td>";
            $info = $this->data_model->get_byid('data_ig', ['kode_roll'=>$val->kode_roll]);
            if($info->num_rows() == 1){
                echo "<td>".$info->row('no_mesin')."</td>";
                echo "<td>".$info->row('operator')."</td>";
                echo "<td>".$info->row('tanggal')."</td>";
            } else {
                echo "<td>Tidak ditemukan</td>";
                echo "<td>Tidak ditemukan</td>";
                echo "<td>Tidak ditemukan</td>";
            }
            
            $pkg = $this->db->query("SELECT * FROM new_tb_isi WHERE kode='$val->kode_roll' ORDER BY id_isi DESC LIMIT 1")->row("kd");
            $sj = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$pkg])->row_array();
            echo "<td>".$sj['no_sj']." - ".$sj['tanggal_dibuat']."</td>";
            echo "<td>Rahmawati</td>";
            echo "</tr>";
            $n++;
        endforeach;
        echo "</table>";
    }
  
  
}
?>
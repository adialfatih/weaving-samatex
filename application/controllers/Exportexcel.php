<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Exportexcel extends CI_Controller {
public function __construct(){
    parent::__construct();
    $this->load->model('data_model');
    date_default_timezone_set("Asia/Jakarta");
}
public function index(){
    $this->load->view('spreadsheet');
}
// lets get export file
public function piutang(){
    $kode = $this->uri->segment(3);
    $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; 
    $cust = $this->data_model->get_byid('dt_konsumen', ['id_konsumen'=>$kode]);
    $nama_cus = $cust->row("nama_konsumen");

                                    $tglar = array();
                                    $jenis = array();
                                    $arid = array();
                                    $surjal = array();
                                    $konstruksi = array();
                                    $panjang = array();
                                    $harga = array();
                                    $harga_total = array();
                                    $idcus = $cust->row("id_konsumen");
                                    $query = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.konstruksi, a_nota.total_panjang, a_nota.harga_satuan, a_nota.total_harga, a_nota.tgl_nota, surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj=surat_jalan.no_sj AND surat_jalan.id_customer='$idcus'");
                                    
                                    foreach($query->result() as $val):
                                        $tglar[]= $val->tgl_nota;
                                        $jenis[]='Pembelian';
                                        $arid[]=$val->id_nota;
                                        $surjal[]=$val->no_sj;
                                        $konstruksi[] = $val->konstruksi;
                                        $panjang[] = $val->total_panjang;
                                        $harga[] = $val->harga_satuan;
                                        $harga_total[] = $val->total_harga;
                                        $idnota = $val->id_nota;
                                        $cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota]);
                                        foreach($cekbyr->result() as $val2):
                                            $tglar[]= $val2->tgl_pemb;
                                            $jenis[]='Pembayaran';
                                            $arid[]=$val2->id_pemb;
                                            $surjal[]="null";
                                            $konstruksi[] = "null";
                                            $panjang[] = "null";
                                            $harga[] = "null";
                                            $harga_total[] = "null";
                                        endforeach;
                                    endforeach;
                                    if($idcus==100){
                                        $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'KM%'");
                                        foreach ($allIdKm->result() as $key => $value) {
                                            $newid = $value->id_konsumen;
                                            $query = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.konstruksi, a_nota.total_panjang, a_nota.harga_satuan, a_nota.total_harga, a_nota.tgl_nota, surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj=surat_jalan.no_sj AND surat_jalan.id_customer='$newid'");
                                    
                                            foreach($query->result() as $val):
                                                $tglar[]= $val->tgl_nota;
                                                $jenis[]='Pembelian';
                                                $arid[]=$val->id_nota;
                                                $surjal[]=$val->no_sj;
                                                $konstruksi[] = $val->konstruksi;
                                                $panjang[] = $val->total_panjang;
                                                $harga[] = $val->harga_satuan;
                                                $harga_total[] = $val->total_harga;
                                                $idnota = $val->id_nota;
                                                $cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota]);
                                                foreach($cekbyr->result() as $val2):
                                                    $tglar[]= $val2->tgl_pemb;
                                                    $jenis[]='Pembayaran';
                                                    $arid[]=$val2->id_pemb;
                                                    $surjal[]="null";
                                                    $konstruksi[] = "null";
                                                    $panjang[] = "null";
                                                    $harga[] = "null";
                                                    $harga_total[] = "null";
                                                endforeach;
                                            endforeach;
                                        }
                                    } //if KM ended
                                    if($idcus==29){
                                        $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'PS%'");
                                        foreach ($allIdKm->result() as $key => $value) {
                                            $newid = $value->id_konsumen;
                                            $query = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.konstruksi, a_nota.total_panjang, a_nota.harga_satuan, a_nota.total_harga, a_nota.tgl_nota, surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj=surat_jalan.no_sj AND surat_jalan.id_customer='$newid'");
                                    
                                            foreach($query->result() as $val):
                                                $tglar[]= $val->tgl_nota;
                                                $jenis[]='Pembelian';
                                                $arid[]=$val->id_nota;
                                                $surjal[]=$val->no_sj;
                                                $konstruksi[] = $val->konstruksi;
                                                $panjang[] = $val->total_panjang;
                                                $harga[] = $val->harga_satuan;
                                                $harga_total[] = $val->total_harga;
                                                $idnota = $val->id_nota;
                                                $cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota]);
                                                foreach($cekbyr->result() as $val2):
                                                    $tglar[]= $val2->tgl_pemb;
                                                    $jenis[]='Pembayaran';
                                                    $arid[]=$val2->id_pemb;
                                                    $surjal[]="null";
                                                    $konstruksi[] = "null";
                                                    $panjang[] = "null";
                                                    $harga[] = "null";
                                                    $harga_total[] = "null";
                                                endforeach;
                                            endforeach;
                                        }
                                    } //if ps ended
                                    asort($tglar);
                                    
                                    $saldo=0;
                                    
                                    
    //$st = $this->uri->segment(4);
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $style_col = [
        'font' => ['bold' => true], // Set font nya jadi bold
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
        ]
      ];
      // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
    $style_row = [
        'alignment' => [
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
        ]
      ];
      $sheet->setCellValue('B2', "Nama Konsumen : "); // Set kolom A1 
      $sheet->setCellValue('C2', "".$nama_cus.""); // Set kolom A1 
      $sheet->setCellValue('B4', "Tanggal"); // Set kolom A1 
      $sheet->setCellValue('C4', "No Faktur"); // Set kolom A1 
      $sheet->setCellValue('D4', "Jenis Barang"); // Set kolom A1 
      $sheet->setCellValue('E4', "Panjang"); // Set kolom A1 
      $sheet->setCellValue('F4', "Harga"); // Set kolom A1 
      $sheet->setCellValue('G4', "Total Harga"); // Set kolom A1 
      $sheet->setCellValue('H4', "Bayar"); // Set kolom A1 
      $sheet->setCellValue('I4', "Saldo"); // Set kolom A1 
      $sheet->getColumnDimension('A')->setAutoSize(true);
      $sheet->getColumnDimension('B')->setAutoSize(true);
      $sheet->getColumnDimension('C')->setAutoSize(true);
      $sheet->getColumnDimension('D')->setAutoSize(true);
      $sheet->getColumnDimension('E')->setAutoSize(true);
      $sheet->getColumnDimension('F')->setAutoSize(true);
      $sheet->getColumnDimension('G')->setAutoSize(true);
      $sheet->getColumnDimension('H')->setAutoSize(true);
      $sheet->getColumnDimension('I')->setAutoSize(true);
      // Apply style header yang telah kita buat tadi ke masing-masing kolom header
      $sheet->getStyle('B2')->applyFromArray($style_col);
      $sheet->getStyle('C2')->applyFromArray($style_col);
      $sheet->getStyle('B4')->applyFromArray($style_col);
      $sheet->getStyle('C4')->applyFromArray($style_col);
      $sheet->getStyle('D4')->applyFromArray($style_col);
      $sheet->getStyle('E4')->applyFromArray($style_col);
      $sheet->getStyle('F4')->applyFromArray($style_col);
      $sheet->getStyle('G4')->applyFromArray($style_col);
      $sheet->getStyle('H4')->applyFromArray($style_col);
      $sheet->getStyle('I4')->applyFromArray($style_col);
      $sum_panjang = 0;
      $sum_harga =0;
      $sum_bayar = 0;
      $nomering = 5;
      foreach($tglar as $index => $value){
            if($jenis[$index]=="Pembelian"){
                $dtqr = $this->data_model->get_byid('a_nota', ['id_nota'=>$arid[$index]]);
                $dtqr2 = $this->db->query("SELECT * FROM a_nota WHERE id_nota='$arid[$index]' AND no_sj NOT LIKE '%SLD%'");
                $nobukti = "<a href='".base_url('nota/piutang/'.sha1($arid[$index]))."' style='color:#135bd6;text-decoration:none;'>Invoice No.".$dtqr->row("id_nota")."</a>";
                if(fmod($dtqr->row("total_harga"), 1) !== 0.00){
                    $debet = "Rp. ".number_format($dtqr->row("total_harga"),2,',','.');
                    $debet1 = $dtqr->row("total_harga");
                } else {
                    $debet = "Rp. ".number_format($dtqr->row("total_harga"),0,',','.');
                    $debet1 = $dtqr->row("total_harga");
                }
                $sum_harga = $sum_harga + $dtqr2->row("total_harga");
                $kredit = "";
                $saldo = $saldo + $dtqr->row("total_harga");
                $jns = "Penjualan";
            } else {
                $dtqr = $this->data_model->get_byid('a_nota_bayar', ['id_pemb'=>$arid[$index]]);
                $nobukti = $dtqr->row("nomor_bukti");
                $debet = "";
                if(fmod($dtqr->row("nominal_pemb"), 1) !== 0.00){
                    $kredit = "Rp. ".number_format($dtqr->row("nominal_pemb"),2,',','.');
                } else {
                    $kredit = "Rp. ".number_format($dtqr->row("nominal_pemb"),0,',','.');
                }
                $sum_bayar = $sum_bayar + $dtqr->row("nominal_pemb");
                $saldo = $saldo - $dtqr->row("nominal_pemb");
                $jns = "<a href='".base_url('nota/piutang/'.sha1($dtqr->row('id_nota')))."' style='color:#135bd6;text-decoration:none;'>Pembayaran Invoice No.".$dtqr->row("id_nota")."</a>";
            }
            $ek=explode('-',$value); 
            $print1 = $ek[2]." ".$bln[$ek[1]]." ".$ek[0];
            $rtx = explode('/', $surjal[$index]);
            $idcusbysj = $this->data_model->get_byid('surat_jalan',['no_sj'=>$surjal[$index]])->row("id_customer");
            $nm_cus = $this->data_model->get_byid('dt_konsumen',['id_konsumen'=>$idcusbysj])->row("nama_konsumen");
            if($rtx[0]=="SLD"){
                $print2 = "Saldo Awal";
            } elseif($rtx[0]=="null") {
                $print2 = "$nobukti";
            } else {
                $print2 = "J".$rtx[3]."".$rtx[0]."";
            }
            $print3 = $konstruksi[$index]=="null" ? '':$konstruksi[$index];
            if($panjang[$index]=="null" || $panjang[$index]==0){ $print4=""; } else {
                if(fmod($panjang[$index], 1) !== 0.00){
                    $print4= "".number_format($panjang[$index],2,',','.');
                } else {
                    $print4= "".number_format($panjang[$index],0,',','.');
                }
            }
            $sum_panjang = $sum_panjang + $panjang[$index];
            if($harga[$index]=="null" || $harga[$index]==0){ $print5=""; } else {
                if(fmod($harga[$index], 1) !== 0.00){
                    $print5= "Rp. ".number_format($harga[$index],2,',','.');
                } else {
                    $print5= "Rp. ".number_format($harga[$index],0,',','.');
                }
            }
            if($rtx[0]=="SLD"){ $print6 = ""; } else { $print6 = $debet; }
            if(fmod($saldo, 1) !== 0.00){
                $print7 = "Rp. ".number_format($saldo,2,',','.');
            } else {
                $print7 = "Rp. ".number_format($saldo,0,',','.');
            }
            $sheet->setCellValue('B'.$nomering.'', $print1);
            $sheet->setCellValue('C'.$nomering.'', $print2);
            $sheet->setCellValue('D'.$nomering.'', $print3);
            $sheet->setCellValue('E'.$nomering.'', $print4);
            $sheet->setCellValue('F'.$nomering.'', $print5);
            $sheet->setCellValue('G'.$nomering.'', $print6);
            $sheet->setCellValue('H'.$nomering.'', $kredit);
            $sheet->setCellValue('I'.$nomering.'', $print7);
            
            

            $nomering++;
      } //end for
      $new_nomering = $nomering + 2;
      if(fmod($sum_panjang, 1) !== 0.00){
        $sum_panjang = "".number_format($sum_panjang,2,',','.');
      } else {
        $sum_panjang = "".number_format($sum_panjang,0,',','.');
      }
      if(fmod($sum_harga, 1) !== 0.00){
        $sum_harga = "Rp. ".number_format($sum_harga,2,',','.');
      } else {
        $sum_harga = "Rp. ".number_format($sum_harga,0,',','.');
      }
      if(fmod($sum_bayar, 1) !== 0.00){
        $sum_bayar = "Rp. ".number_format($sum_bayar,2,',','.');
      } else {
        $sum_bayar = "Rp. ".number_format($sum_bayar,0,',','.');
      }
      $sheet->setCellValue('B'.$new_nomering.'', 'Total');
      $sheet->setCellValue('C'.$new_nomering.'', '');
      $sheet->setCellValue('D'.$new_nomering.'', '');
      $sheet->setCellValue('E'.$new_nomering.'', $sum_panjang);
      $sheet->setCellValue('F'.$new_nomering.'', '');
      $sheet->setCellValue('G'.$new_nomering.'', $sum_harga);
      $sheet->setCellValue('H'.$new_nomering.'', $sum_bayar);
      $sheet->setCellValue('I'.$new_nomering.'', $print7);     
      $sheet->getStyle('B'.$new_nomering.'')->applyFromArray($style_col);
      $sheet->getStyle('C'.$new_nomering.'')->applyFromArray($style_col);
      $sheet->getStyle('D'.$new_nomering.'')->applyFromArray($style_col);
      $sheet->getStyle('E'.$new_nomering.'')->applyFromArray($style_col);
      $sheet->getStyle('F'.$new_nomering.'')->applyFromArray($style_col);
      $sheet->getStyle('G'.$new_nomering.'')->applyFromArray($style_col);
      $sheet->getStyle('H'.$new_nomering.'')->applyFromArray($style_col);
      $sheet->getStyle('I'.$new_nomering.'')->applyFromArray($style_col); 

      //$sheet->setCellValue('A2', $kode); // Set kolom A1 
      //$sheet->setCellValue('H2', $st); // Set kolom A1 
      $sheet->getStyle('A1')->applyFromArray($style_row);
      
      $writer = new Xlsx($spreadsheet);
      $filename = 'Kartu-Piutang-Penjualan-Atas-Nama-Konsumen-'.$nama_cus.'-'.date('Y-m-d').'';

      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output'); // download file
}
    function laporanjual(){
        $txt = $this->input->post('txtquery');
        $tanggalRange = $this->input->post('tanggalRange');
        $blns = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; 
        $qrdata = $this->db->query($txt);
        if($tanggalRange=="yes"){
            $tmTgl1 = $this->input->post('tanggal1');
            $tmTgl2 = $this->input->post('tanggal2');
            if($tmTgl1 == $tmTgl2){
                $filename = 'Laporan Penjualan Tanggal '.$tmTgl1.'';
            } else {
                $filename = 'Laporan Penjualan Tanggal '.$tmTgl1.' s/d '.$tmTgl2.'';
            }
        } else {
        $filename = 'Export Laporan Penjualan'; }
        //$st = $this->uri->segment(4);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->mergeCells('B2:I2');
        $style_col = [
            'font' => ['bold' => true], // Set font nya jadi bold
            'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
            'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
            'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
            'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
            'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];
        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = [
            'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
            'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
            'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
            'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
            'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];
        $sheet->setCellValue('B2', $filename); // Set kolom A1 
        //$sheet->setCellValue('C2', "".$nama_cus.""); // Set kolom A1 
        $sheet->setCellValue('B4', "Tanggal"); // Set kolom A1 
        $sheet->setCellValue('C4', "Nama Customer"); // Set kolom A1 
        $sheet->setCellValue('D4', "No Faktur"); // Set kolom A1 
        $sheet->setCellValue('E4', "Jenis Barang"); // Set kolom A1 
        $sheet->setCellValue('F4', "Panjang"); // Set kolom A1 
        $sheet->setCellValue('G4', "Satuan"); // Set kolom A1 
        $sheet->setCellValue('H4', "Harga"); // Set kolom A1 
        $sheet->setCellValue('I4', "Total Harga"); // Set kolom A1 
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $sheet->getStyle('B2:I2')->applyFromArray($style_col);
        //$sheet->getStyle('C2')->applyFromArray($style_col);
        $sheet->getStyle('B4')->applyFromArray($style_col);
        $sheet->getStyle('C4')->applyFromArray($style_col);
        $sheet->getStyle('D4')->applyFromArray($style_col);
        $sheet->getStyle('E4')->applyFromArray($style_col);
        $sheet->getStyle('F4')->applyFromArray($style_col);
        $sheet->getStyle('G4')->applyFromArray($style_col);
        $sheet->getStyle('H4')->applyFromArray($style_col);
        $sheet->getStyle('I4')->applyFromArray($style_col);
        $sum_panjang=0;
        $sum_total_harga=0;
        $nomber = 5;
        foreach($qrdata->result() as $n => $val):
            $ex = explode('-', $val->tgl_nota);
            $tgl = $ex[2]."-".$blns[$ex[1]]."-".$ex[0];
            $sj = $val->no_sj;
            $exsj = explode('/', $sj);
            $noFaktur = "J".$exsj[3]."".$exsj[0];
            $idcus = $this->data_model->get_byid('surat_jalan',['no_sj'=>$sj])->row("id_customer");
            $nmcus = $this->data_model->get_byid('dt_konsumen',['id_konsumen'=>$idcus])->row("nama_konsumen");
            $kdpkg = $val->kd;
            $fold = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kdpkg])->row("jns_fold");
        
            $sheet->setCellValue('B'.$nomber.'', $tgl);
            $sheet->setCellValue('C'.$nomber.'', $nmcus);
            $sheet->setCellValue('D'.$nomber.'', $noFaktur);
            $sheet->setCellValue('E'.$nomber.'', $val->konstruksi);

            $sum_panjang+=$val->total_panjang;
            if(fmod($val->total_panjang, 1) !== 0.00){
                $nPrint1 = number_format($val->total_panjang,2,',','.');
            } else {
                $nPrint1 = number_format($val->total_panjang,0,',','.');
            }
            //$sheet->setCellValue('F'.$nomber.'', $val->total_panjang);
            $sheet->setCellValueExplicit('F'.$nomber.'', $val->total_panjang, DataType::TYPE_NUMERIC);
            $sheet->setCellValue('G'.$nomber.'', $fold=="Grey"?"Meter":"Yard");

            if(fmod($val->harga_satuan, 1) !== 0.00){
                $nPrint2= number_format($val->harga_satuan,2,',','.');
            } else {
                $nPrint2= number_format($val->harga_satuan,0,',','.');
            }
            //$sheet->setCellValue('H'.$nomber.'', $val->harga_satuan);
            $sheet->setCellValueExplicit('H'.$nomber.'', $val->harga_satuan, DataType::TYPE_NUMERIC);
            $sum_total_harga+=$val->total_harga;
            if(fmod($val->total_harga, 1) !== 0.00){
                $nPrint3 = "Rp. ".number_format($val->total_harga,2,',','.');
            } else {
                $nPrint3 = "Rp. ".number_format($val->total_harga,0,',','.');
            }
            //$sheet->setCellValue('I'.$nomber.'', $val->total_harga);
            $sheet->setCellValueExplicit('I'.$nomber.'', ''.$val->total_harga, DataType::TYPE_NUMERIC);
            $sheet->getStyle('B'.$nomber.'')->applyFromArray($style_row);
            $sheet->getStyle('C'.$nomber.'')->applyFromArray($style_row);
            $sheet->getStyle('D'.$nomber.'')->applyFromArray($style_row);
            $sheet->getStyle('E'.$nomber.'')->applyFromArray($style_row);
            $sheet->getStyle('F'.$nomber.'')->applyFromArray($style_row);
            $sheet->getStyle('G'.$nomber.'')->applyFromArray($style_row);
            $sheet->getStyle('H'.$nomber.'')->applyFromArray($style_row);
            $sheet->getStyle('I'.$nomber.'')->applyFromArray($style_row);
            //$sheet->getStyle('A1')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('F'.$nomber.'')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('H'.$nomber.'')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('I'.$nomber.'')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $nomber++;                             
        endforeach;
        $new_nomber = $nomber+0;
        $sheet->setCellValue('B'.$new_nomber.'', 'Total');
        if(fmod($sum_panjang, 1) !== 0.00){
            $ttl1=  "".number_format($sum_panjang,2,',','.');
        } else {
            $ttl1= "".number_format($sum_panjang,0,',','.');
        }
        $sheet->setCellValue('F'.$new_nomber.'', $ttl1);
        $sheet->setCellValueExplicit('F'.$new_nomber.'', $sum_panjang, DataType::TYPE_NUMERIC);
        $sheet->getStyle('F'.$new_nomber.'')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        if(fmod($sum_total_harga, 1) !== 0.00){
            $ttl2= "Rp. ".number_format($sum_total_harga,2,',','.');
        } else {
            $ttl2= "Rp. ".number_format($sum_total_harga,0,',','.');
        }
        $sheet->setCellValue('I'.$new_nomber.'', $ttl2);
        $sheet->getStyle('B'.$new_nomber.'')->applyFromArray($style_col);
        $sheet->getStyle('C'.$new_nomber.'')->applyFromArray($style_col);
        $sheet->getStyle('D'.$new_nomber.'')->applyFromArray($style_col);
        $sheet->getStyle('E'.$new_nomber.'')->applyFromArray($style_col);
        $sheet->getStyle('F'.$new_nomber.'')->applyFromArray($style_col);
        $sheet->getStyle('G'.$new_nomber.'')->applyFromArray($style_col);
        $sheet->getStyle('H'.$new_nomber.'')->applyFromArray($style_col);
        $sheet->getStyle('I'.$new_nomber.'')->applyFromArray($style_col);
        //$sheet->setCellValue('A2', $kode); // Set kolom A1 
        //$sheet->setCellValue('H2', $st); // Set kolom A1 
        //$sheet->getStyle('A1')->applyFromArray($style_row);
        
        $writer = new Xlsx($spreadsheet);
        

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output'); // download file
        
    } //end
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Exportexcel2 extends CI_Controller {
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
        $editable = $this->uri->segment(4);
        $nama_cus = $this->db->query("SELECT nama_konsumen,kode_proses FROM tb_piutang_sementara WHERE nama_konsumen!='total' AND kode_proses='$kode' LIMIT 1")->row("nama_konsumen");
        $data1 = $this->db->query("SELECT * FROM tb_piutang_sementara WHERE nama_konsumen!='total' AND tanggal!='total' AND kode_proses='$kode'");
        $data2 = $this->db->query("SELECT * FROM tb_piutang_sementara WHERE nama_konsumen='total' AND tanggal='total' AND kode_proses='$kode'")->row_array();
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
        $nomering = 5;
        if($editable=="1"){
            foreach($data1->result() as $val){
                    $sheet->setCellValue('B'.$nomering.'', $val->tanggal);
                    $sheet->setCellValue('C'.$nomering.'', $val->nofaktur);
                    $sheet->setCellValue('D'.$nomering.'', $val->jenis_barang);
                    $sheet->setCellValue('E'.$nomering.'', $val->panjang_txt);
                    $sheet->setCellValue('F'.$nomering.'', $val->harga_txt);
                    $sheet->setCellValue('G'.$nomering.'', $val->total_harga_txt);
                    $sheet->setCellValue('H'.$nomering.'', $val->bayar_txt);
                    $sheet->setCellValue('I'.$nomering.'', $val->saldo_txt);
                    $sheet->getStyle('B'.$nomering.'')->applyFromArray($style_row);
                    $sheet->getStyle('C'.$nomering.'')->applyFromArray($style_row);
                    $sheet->getStyle('D'.$nomering.'')->applyFromArray($style_row);
                    $sheet->getStyle('E'.$nomering.'')->applyFromArray($style_row);
                    $sheet->getStyle('F'.$nomering.'')->applyFromArray($style_row);
                    $sheet->getStyle('G'.$nomering.'')->applyFromArray($style_row);
                    $sheet->getStyle('H'.$nomering.'')->applyFromArray($style_row);
                    $sheet->getStyle('I'.$nomering.'')->applyFromArray($style_row);
                    $nomering++;
            } //end for
        } else {
            foreach($data1->result() as $val){
                $sheet->setCellValue('B'.$nomering.'', $val->tanggal);
                $sheet->setCellValue('C'.$nomering.'', $val->nofaktur);
                $sheet->setCellValue('D'.$nomering.'', $val->jenis_barang);
                $sheet->setCellValue('E'.$nomering.'', $val->panjang_int);
                $sheet->setCellValue('F'.$nomering.'', $val->harga_int);
                $sheet->setCellValue('G'.$nomering.'', $val->total_harga_int);
                $sheet->setCellValue('H'.$nomering.'', $val->bayar_int);
                $sheet->setCellValue('I'.$nomering.'', $val->saldo_int);
                $sheet->getStyle('B'.$nomering.'')->applyFromArray($style_row);
                $sheet->getStyle('C'.$nomering.'')->applyFromArray($style_row);
                $sheet->getStyle('D'.$nomering.'')->applyFromArray($style_row);
                $sheet->getStyle('E'.$nomering.'')->applyFromArray($style_row);
                $sheet->getStyle('F'.$nomering.'')->applyFromArray($style_row);
                $sheet->getStyle('G'.$nomering.'')->applyFromArray($style_row);
                $sheet->getStyle('H'.$nomering.'')->applyFromArray($style_row);
                $sheet->getStyle('I'.$nomering.'')->applyFromArray($style_row);
                $nomering++;
            } //end for
        }
        $new_nomering = $nomering + 2;
        
        $sheet->setCellValue('B'.$new_nomering.'', 'Total');
        $sheet->setCellValue('C'.$new_nomering.'', '');
        $sheet->setCellValue('D'.$new_nomering.'', '');
        if($editable=="1"){
            $_ens = number_format($data2['sum_panjang_int'],0,'.',',');
            $sheet->setCellValue('E'.$new_nomering.'', $_ens);
            $sheet->setCellValue('F'.$new_nomering.'', '');
            $sheet->setCellValue('G'.$new_nomering.'', $data2['sum_total_harga_txt']);
            $sheet->setCellValue('H'.$new_nomering.'', $data2['sum_bayar_txt']);
            $sheet->setCellValue('I'.$new_nomering.'', $data2['sum_saldo_txt']);    
        } else {
            $sheet->setCellValue('E'.$new_nomering.'', $data2['sum_panjang_int']);
            $sheet->setCellValue('F'.$new_nomering.'', '');
            $sheet->setCellValue('G'.$new_nomering.'', $data2['sum_total_harga_int']);
            $sheet->setCellValue('H'.$new_nomering.'', $data2['sum_bayar_int']);
            $sheet->setCellValue('I'.$new_nomering.'', $data2['sum_saldo_int']);  
        }
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
    } //end
}
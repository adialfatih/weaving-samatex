<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    class Phpsk extends CI_Controller {
        public function __construct(){
        parent::__construct();
        $this->load->model('data_model');
        $this->load->model('data_model2');
        date_default_timezone_set("Asia/Jakarta");
    }
    
    public function index(){
        //$this->load->view('spreadsheet');
    }

    public function cekif(){
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(isset($_FILES['upload_file']['name']) && in_array($_FILES['upload_file']['type'], $file_mimes)) {
            $arr_file = explode('.', $_FILES['upload_file']['name']);
            $extension = end($arr_file);
            if('csv' == $extension){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            
            echo "<table border='1' style='border-collapse:collapse;'>";
            echo "<tr><td>No</td><td>Kode Roll</td><td>Status 1</td><td>Status 2</td</tr>";
            for ($i=1; $i <count($sheetData) ; $i++) { 
                $kode = $sheetData[$i][0];
                echo "<tr>";
                echo "<td>".$i."</td>";
                echo "<td>";
                echo $kode;
                echo "</td>";
                $cekBaru = $this->data_model->get_byid('data_if', ['kode_roll'=>$kode]);
                if($cekBaru->num_rows()==0){ echo "<td style='color:red;'>Tidak tersimpan di stok baru</td>"; }
                if($cekBaru->num_rows()==1){ echo "<td style='color:green;'>Tersimpan di stok baru</td>"; }
                if($cekBaru->num_rows()>1){ echo "<td style='color:red;'>Error</td>"; }
                $cekLama = $this->data_model->get_byid('data_if_lama', ['kode_roll'=>$kode]);
                if($cekLama->num_rows()==0){ echo "<td style='color:red;'>Tidak tersimpan di stok Lama</td>"; }
                if($cekLama->num_rows()==1){ echo "<td style='color:green;'>Tersimpan di stok Lama</td>"; }
                if($cekLama->num_rows()>1){ echo "<td style='color:red;'>Error</td>"; }
                echo "</tr>";
            } //end for perulangan by excel
            echo "</table>";
            
        } else {
            $this->session->set_flashdata('gagal', 'Format file yang anda masukan salah');
            redirect(base_url('cek-produksi'));
        }
    } //end1
    public function cekff(){
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(isset($_FILES['upload_file']['name']) && in_array($_FILES['upload_file']['type'], $file_mimes)) {
            $arr_file = explode('.', $_FILES['upload_file']['name']);
            $extension = end($arr_file);
            if('csv' == $extension){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            echo "<table border='1'>";
            for ($i=1; $i <count($sheetData) ; $i++) { 
                $kode = $sheetData[$i][0];
                echo "<tr>";
                echo "<td>";
                echo $kode;
                echo "</td>";
                echo "</tr>";
            } //end for perulangan by excel
            echo "</table>";
        } else {
            $this->session->set_flashdata('gagal', 'Format file yang anda masukan salah');
            redirect(base_url('cek-produksi'));
        }
    } //end1
}
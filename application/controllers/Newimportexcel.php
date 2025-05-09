<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Newimportexcel extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('data_model');
        date_default_timezone_set("Asia/Jakarta");
    }
    public function index(){
        echo "Token Expired...";
    }
// lets get export file

    function import_insgrey2(){
        $id_user = $this->session->userdata('id'); 
        $depuser = $this->session->userdata('departement');
        $kodeupload = $this->input->post('kodeauto');
        echo "ID User : ".$id_user."<br>";
        echo "Dep User : ".$depuser."<br>";
        echo "Kode Upload : ".$kodeupload."<hr>";
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
            
            for ($i=1; $i <count($sheetData) ; $i++) {
                $koderoll = $sheetData[$i][0];
                $kodekons = $sheetData[$i][1];
                $nomesin = $sheetData[$i][2];
                $nobeam = $sheetData[$i][3];
                $n_oka = $sheetData[$i][4]=='' ? '-':$sheetData[$i][4];
                $ori = $sheetData[$i][5]; 
                $bs = $sheetData[$i][6]; 
                $ukr_bp = $sheetData[$i][7];
                $tgl = $sheetData[$i][8]; 
                $ex = explode('-', $tgl);
                if(count($ex)>1){
                $formatTgl = $ex[2]."-".$ex[1]."-".$ex[0]; }
                $opr = $sheetData[$i][9];
                if($ori < 50 ){ $bptxt = "true"; } else { $bptxt= "false"; }
                echo "$koderoll - $kodekons - $nomesin - $nobeam - $n_oka - $ori - $bs - $ukr_bp - $formatTgl - $opr<br>";
               
            } //end for
            // $this->session->set_flashdata('announce', 'Berhasil menyimpan proses produksi inspect grey');
            // redirect(base_url('input-produksi'));
            echo "<hr>Berhasil oke";
          } else {
            echo "Format file yang anda masukan salah";
            //   $this->session->set_flashdata('gagal', 'Format file yang anda masukan salah');
            //   redirect(base_url('proses-produksi'));
          }
    } //end function
    function import_insgrey(){
        $id_user = $this->session->userdata('id'); 
        $loc = $this->session->userdata('departement');
        if($id_user==5) { $loc="RJS"; }
        if($id_user==7) { $loc="Samatex"; }
        if($id_user==9) { $loc="RJS"; }
        if($id_user==10) { $loc="RJS"; }
        if($id_user==71) { $loc="Samatex"; }
        if($id_user==11) { $loc="Pusatex"; }
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
            
            for ($i=1; $i <count($sheetData) ; $i++) {
                $koderoll = $sheetData[$i][0];
                $kodekons = $sheetData[$i][1];
                $nomesin = $sheetData[$i][2];
                $nobeam = $sheetData[$i][3];
                $n_oka = $sheetData[$i][4]=='' ? '-':$sheetData[$i][4];
                $ori = $sheetData[$i][5]; 
                $bs = $sheetData[$i][6]; 
                $ukr_bp = $sheetData[$i][7];
                $tgl = $sheetData[$i][8]; 
                $ex = explode('-', $tgl);
                if(count($ex)>1){
                $formatTgl = $ex[2]."-".$ex[1]."-".$ex[0]; }
                $opr = $sheetData[$i][9];
                $cek_kode = $this->data_model->get_byid('data_ig', ['kode_roll'=>$koderoll]);
                if($koderoll=="" AND $kodekons==""){} else {
                //BATAS BP 
                if($ori < 50 ){ $bptxt = "true"; } else { $bptxt= "false"; }
                if($cek_kode->num_rows()==0){
                    $dtlist = [
                        'kode_roll' => $koderoll,
                        'konstruksi' => $kodekons,
                        'no_mesin' => $nomesin,
                        'no_beam' => $nobeam,
                        'oka' => $n_oka,
                        'ukuran_ori' => $ori,
                        'ukuran_bs' => $bs,
                        'ukuran_bp' => $ukr_bp,
                        'tanggal' => $formatTgl,
                        'operator' => $opr,
                        'bp_can_join' => $bptxt,
                        'dep' => $loc,
                        'loc_now' => $loc,
                        'yg_input' => $id_user
                    ];
                    $this->data_model->saved('data_ig', $dtlist);
                    $dt_kons = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kodekons]);
                    $new_ig = floatval($dt_kons->row("stok_ig")) + floatval($ori);
                    $new_bs = floatval($dt_kons->row("stok_bs")) + floatval($bs);
                    $new_bp = floatval($dt_kons->row("stok_bp")) + floatval($ukr_bp);
                    $this->data_model->updatedata('kode_konstruksi',$kodekons,'tb_konstruksi',[ 'stok_ig' => round($new_ig,2), 'stok_bs' => round($new_bs,2), 'stok_bp' => round($new_bp,2) ]);
                    $cek_prod = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kodekons, 'tgl'=>$formatTgl, 'dep'=>$loc]);
                    if($cek_prod->num_rows()==0){
                        $dtprod = [
                            'kode_konstruksi' => $kodekons,
                            'tgl' => $formatTgl,
                            'dep' => $loc,
                            'prod_ig' => $ori,
                            'prod_fg' => 0,
                            'prod_if' => 0,
                            'prod_ff' => 0,
                            'prod_bs1' => $bs,
                            'prod_bp1' => $ukr_bp,
                            'prod_bs2' => 0,
                            'prod_bp2' => 0,
                            'crt' => 0
                        ];
                        $this->data_model->saved('data_produksi',$dtprod);
                    } else {
                        $idprod = $cek_prod->row("id_produksi");
                        $new_ig = floatval($cek_prod->row("prod_ig")) + floatval($ori);
                        $new_bs = floatval($cek_prod->row("prod_bs1")) + floatval($bs);
                        $new_bp = floatval($cek_prod->row("prod_bp1")) + floatval($ukr_bp);
                        $this->data_model->updatedata('id_produksi',$idprod,'data_produksi',['prod_ig'=>round($new_ig,2),'prod_bs1'=>round($new_bs,2),'prod_bp1'=>round($new_bp,2)]);
                    }
                    $cek_harian = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$formatTgl,'dep'=>$loc]);
                    if($cek_harian->num_rows()==0){
                        $dtprod_hr = [
                            'tgl' => $formatTgl,
                            'dep' => $loc,
                            'prod_ig' => $ori,
                            'prod_fg' => 0,
                            'prod_if' => 0,
                            'prod_ff' => 0,
                            'prod_bs1' => $bs,
                            'prod_bp1' => $ukr_bp,
                            'prod_bs2' => 0,
                            'prod_bp2' => 0,
                            'crt' => 0
                        ];
                        $this->data_model->saved('data_produksi_harian',$dtprod_hr);
                    } else {
                        $idprod = $cek_harian->row("id_prod_hr");
                        $new_ig = floatval($cek_harian->row("prod_ig")) + floatval($ori);
                        $new_bs = floatval($cek_harian->row("prod_bs1")) + floatval($bs);
                        $new_bp = floatval($cek_harian->row("prod_bp1")) + floatval($ukr_bp);
                        $this->data_model->updatedata('id_prod_hr',$idprod,'data_produksi_harian',['prod_ig'=>round($new_ig,2),'prod_bs1'=>round($new_bs,2),'prod_bp1'=>round($new_bp,2)]);
                    }
                    $cek_stok = $this->data_model->get_byid('data_stok', ['dep'=>$loc,'kode_konstruksi'=>$kodekons]);
                    if($cek_stok->num_rows()==0){
                        $dtstok = [
                            'dep' => $loc,
                            'kode_konstruksi' => $kodekons,
                            'prod_ig' => $ori,
                            'prod_fg' => 0,
                            'prod_if' => 0,
                            'prod_ff' => 0,
                            'prod_bs1' => $bs,
                            'prod_bp1' => $ukr_bp,
                            'prod_bs2' => 0,
                            'prod_bp2' => 0,
                            'crt' => 0
                        ];
                        $this->data_model->saved('data_stok',$dtstok);
                    } else {
                        $idprod = $cek_stok->row("idstok");
                        $new_ig = floatval($cek_stok->row("prod_ig")) + floatval($ori);
                        $new_bs = floatval($cek_stok->row("prod_bs1")) + floatval($bs);
                        $new_bp = floatval($cek_stok->row("prod_bp1")) + floatval($ukr_bp);
                        $this->data_model->updatedata('idstok',$idprod,'data_stok',['prod_ig'=>round($new_ig,2),'prod_bs1'=>round($new_bs,2),'prod_bp1'=>round($new_bp,2)]);
                    }
                } else {
                    $txt = $koderoll." tidak di input ke sistem karena kode sudah ada";
                    $dtlist = [
                        'id_user' => $id_user,
                        'log_text' => $txt
                    ];
                    $this->data_model->saved('log_program', $dtlist);
                }
              }  
            } //end for
            $this->session->set_flashdata('announce', 'Berhasil menyimpan proses produksi inspect grey');
            redirect(base_url('input-produksi'));
          } else {
              $this->session->set_flashdata('gagal', 'Format file yang anda masukan salah');
              redirect(base_url('proses-produksi'));
          }
        
    } //end

}
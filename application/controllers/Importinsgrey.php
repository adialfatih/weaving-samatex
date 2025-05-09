<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Importinsgrey extends CI_Controller {

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
    // echo "ID User : ".$id_user."<br>";
    // echo "Dep User : ".$depuser."<br>";
    // echo "Kode Upload : ".$kodeupload."<hr>";
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
            $koderoll2 = str_replace(' ','',$koderoll);
            $koderoll3 = preg_replace('/\s+/','',$koderoll2);
            $kodekons = $sheetData[$i][1];
            $kodekons2 = str_replace(' ','',$kodekons);
            $kodekons3 = preg_replace('/\s+/','',$kodekons2);
            $nomesin = $sheetData[$i][2];
            $nobeam = $sheetData[$i][3];
            $n_oka = $sheetData[$i][4]=='' ? '-':$sheetData[$i][4];
            $ori = $sheetData[$i][5]; 
            $bs = $sheetData[$i][6]; 
            $ukr_bp = $sheetData[$i][7];
            $tgl = $sheetData[$i][8]; 
            $tgl2 = str_replace(' ','',$tgl);
            $tgl3 = preg_replace('/\s+/','',$tgl2);
            $ex = explode('-', $tgl3);
            if(count($ex)>1){
            $formatTgl = $ex[2]."-".$ex[1]."-".$ex[0]; }
            $opr = $sheetData[$i][9];
            if($ori < 50 ){ $bptxt = "true"; } else { $bptxt= "false"; }
            //echo "$koderoll3 - $kodekons3 - $nomesin - $nobeam - $n_oka - $ori - $bs - $ukr_bp - $formatTgl - $opr<br>";
            $dtlist = [
                'kode_roll' => $koderoll3,
                'konstruksi' => $kodekons3,
                'nomc' => $nomesin,
                'nobeam' => $nobeam,
                'oka' => $n_oka,
                'ukuran' => $ori,
                'bs' => $bs,
                'bp' => $ukr_bp,
                'tgl' => $formatTgl,
                'operator' => $opr,
                'kode_upload' => $kodeupload
            ];
            $this->data_model->saved('temp_upload_ig', $dtlist);
        } //end for
        $tdlist = [
            'kodeauto' => $kodeupload,
            'proses_name' => 'Inspect Grey',
            'penginput' => $id_user
        ];
        $this->data_model->saved('log_input_roll', $tdlist);
        $this->session->set_flashdata('announce', 'Berhasil menyimpan proses produksi inspect grey');
        redirect(base_url('resume/insgrey/'.$kodeupload));
        //echo "<hr>Berhasil oke";
      } else {
        echo "Format file yang anda masukan salah";
        //   $this->session->set_flashdata('gagal', 'Format file yang anda masukan salah');
        //   redirect(base_url('proses-produksi'));
      }
} //end function

    function import_insgrey(){
        $id_user = $this->session->userdata('id'); 
        $loc = $this->session->userdata('departement');
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
            $adadidb=0; $tidakadadb=0; $semua=0; $ngawur=0; $kodenull=0;
            for ($i=1; $i <count($sheetData) ; $i++) {
                $koderoll = $sheetData[$i][0]=='' ? 'null':$sheetData[$i][0];
                $koderoll2 = str_replace(' ','',$koderoll);
                $koderoll3 = preg_replace('/\s+/','',$koderoll2);
                $kodekons = $sheetData[$i][1]=='' ? 'null':$sheetData[$i][1];
                $kodekons2 = str_replace(' ','',$kodekons);
                $kodekons3 = preg_replace('/\s+/','',$kodekons2);
                $nomesin = $sheetData[$i][2]=='' ? 'null':$sheetData[$i][2];
                $nobeam = $sheetData[$i][3]=='' ? 'null':$sheetData[$i][3];;
                $n_oka = $sheetData[$i][4]=='' ? 'null':$sheetData[$i][4];
                $ori = $sheetData[$i][5]=='' ? '0':$sheetData[$i][5];
                $bs = $sheetData[$i][6]=='' ? '0':$sheetData[$i][6];
                $ukr_bp = $sheetData[$i][7]=='' ? '0':$sheetData[$i][7];
                $tgl = $sheetData[$i][8]=='' ? '0000-00-00':$sheetData[$i][8];
                $tgl2 = str_replace(' ','',$tgl);
                $tgl3 = preg_replace('/\s+/','',$tgl2);
                $ex = explode('-', $tgl3);
                if(count($ex)>1){
                $formatTgl = $ex[2]."-".$ex[1]."-".$ex[0]; }
                $opr = $sheetData[$i][9]=='' ? 'null':$sheetData[$i][9];
                if($ori < 50 ){ $bptxt = "true"; } else { $bptxt= "false"; }
                $semua = $semua + 1;
                //echo "($i)".$koderoll3."";
                if($ori < 50 ){ $bptxt = "true"; } else { $bptxt= "false"; }
                if($koderoll3=="null"){ $kodenull+=1; } else {
                    $cekkode = $this->data_model->get_byid('data_ig', ['kode_roll'=>$koderoll3]);
                    if($cekkode->num_rows() == 1){
                        $adadidb = $adadidb + 1;
                        //$this->data_model->updatedata('id_data',$cekkode->row('id_data'),'data_ig',['yg_input'=>'admin']);
                    } elseif($cekkode->num_rows() == 0) {
                        $tidakadadb = $tidakadadb + 1;
                    } else {
                        $ngawur = $ngawur + 1;
                    }
                    //echo "()--".$cekkode->row("yg_input")."";
                }
                //echo "<br>";
                
                $dtlist = [
                    'kode_roll' => $koderoll3,
                    'konstruksi' => $kodekons3,
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
                    'yg_input' => 'admin'
                ];
                $this->data_model->saved('data_ig', $dtlist);
                $cek_harian = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$formatTgl, 'dep'=>$loc]);
                if($cek_harian->num_rows() == 0 ){
                    $input1 = [
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
                    $this->data_model->saved('data_produksi_harian', $input1);
                } else {
                    $id_prod_hr = $cek_harian->row("id_prod_hr");
                    $new_ig = floatval($cek_harian->row("prod_ig")) + $ori;
                    $new_bs = floatval($cek_harian->row("prod_bs1")) + $bs;
                    $new_bp = floatval($cek_harian->row("prod_bp1")) + $ukr_bp;
                    $updatess = [
                        'prod_ig' => round($new_ig,2),
                        'prod_bs1' => round($new_bs,2),
                        'prod_bp1' => round($new_bp,2)
                    ];
                    $this->data_model->updatedata('id_prod_hr', $id_prod_hr, 'data_produksi_harian', $updatess);
                }
                $cek_produksi = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kodekons3,'tgl'=>$formatTgl, 'dep'=>$loc]);
                if($cek_produksi->num_rows() == 0 ){
                    $input2 = [
                        'kode_konstruksi' => $kodekons3,
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
                    $this->data_model->saved('data_produksi', $input2);
                } else {
                    $id_produksi = $cek_produksi->row("id_produksi");
                    $new_ig = floatval($cek_produksi->row("prod_ig")) + $ori;
                    $new_bs = floatval($cek_produksi->row("prod_bs1")) + $bs;
                    $new_bp = floatval($cek_produksi->row("prod_bp1")) + $ukr_bp;
                    $updatess = [
                        'prod_ig' => round($new_ig,2),
                        'prod_bs1' => round($new_bs,2),
                        'prod_bp1' => round($new_bp,2)
                    ];
                    $this->data_model->updatedata('id_produksi', $id_produksi, 'data_produksi', $updatess);
                }
            } //end for
            // echo "Jumlah data semuanya ada ".$semua."<br>";
            // echo "Jumlah yang sudah masuk db semuanya ada ".$adadidb."<br>";
            // echo "Jumlah yang tidak masuk db semuanya ada ".$tidakadadb."<br>";
            // echo "Jumlah ngawur ".$ngawur."<br>";
            // echo "kode null ".$kodenull."--$loc<br>";
            //echo $dataall." data yang tidak ada di database sigini $dataiwek -- $datangawur -- $pusatex";
            $this->session->set_flashdata('announce', 'Berhasil menyimpan proses produksi inspect grey');
            //redirect(base_url('input-produksi'));
          } else {
              $this->session->set_flashdata('gagal', 'Format file yang anda masukan salah');
              //redirect(base_url('proses-produksi'));
          }
        
    } //end

}
<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    class Impfol extends CI_Controller {
        public function __construct(){
            parent::__construct();
            $this->load->model('data_model');
            $this->load->model('data_model2');
            date_default_timezone_set("Asia/Jakarta");
        }
    
    public function index(){
        //$this->load->view('spreadsheet');
    }

    function pkgfromex2(){
        $kd = $this->input->post('kdlistId');
        $kons = $this->input->post('kons2a');
        $satuan = $this->input->post('satuan');
        $cek_kons = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kons]);
        if($cek_kons->num_rows() == 1){ $st=$kons; } else { 
            $cek_kons2 = $this->data_model->get_byid('tb_konstruksi', ['chto'=>$kons]);
            if($cek_kons2->num_rows() == 1){
                $st = $cek_kons2->row("kode_konstruksi");
            } else {
                $st = $kons;
            }
        }
        $siapdol = $this->input->post('siap_dol');
        $kode = $this->input->post('kode');
        $ukuran = $this->input->post('ukuran');
        $jumlah_roll = 0; $total_panjang = 0;
        for ($i=0; $i < count($kode); $i++) { 
            if($ukuran[$i]==""){} else {
                $jumlah_roll+=1;
                $newukuran = str_replace(",", ".", $ukuran[$i]);
                $panjang = floatval($newukuran);
                $total_panjang+=$panjang;
                $dtlist = [
                    'kd' => strtoupper($kd),
                    'konstruksi' => $st,
                    'siap_jual' => $siapdol,
                    'kode' => $kode[$i]=='' ? 'null':strtoupper($kode[$i]),
                    'ukuran' => $newukuran,
                    'status' => 'null',
                    'satuan' => $satuan
                ];
                $this->data_model->saved('new_tb_isi', $dtlist);
            }
        } //end perulangan
            $pkg = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kd]);
            $newroll = intval($pkg->row("jumlah_roll")) + $jumlah_roll;
            $newpanjang = floatval($pkg->row("ttl_panjang")) + $total_panjang;
            $this->data_model->updatedata('kd',$kd,'new_tb_packinglist', ['jumlah_roll'=>$newroll, 'ttl_panjang'=>round($newpanjang,2)]);
            $this->session->set_flashdata('announce', 'Packinglist berhasil di simpan');
            redirect(base_url('data/kode/'.$kd));
        
    }


    function pkgfromex(){
        $kd = $this->input->post('kdlistId');
        $kons = $this->input->post('kons2a');
        $cek_kons = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kons]);
        if($cek_kons->num_rows() == 1){ $st=$kons; } else { 
            $cek_kons2 = $this->data_model->get_byid('tb_konstruksi', ['chto'=>$kons]);
            if($cek_kons2->num_rows() == 1){
                $st = $cek_kons2->row("kode_konstruksi");
            } else {
                $st = $kons;
            }
        }
        $siapdol = $this->input->post('siap_dol');
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
            $jumlah_roll = 0; $total_panjang = 0;
            for ($i=1; $i <count($sheetData) ; $i++) {
                $koderoll = $sheetData[$i][1];
                $ukuran = $sheetData[$i][2];
                $ym = $sheetData[$i][3];
                $koderoll2 = str_replace(' ','',$koderoll);
                $koderoll3 = preg_replace('/\s+/','',$koderoll2);
                $ukuran2 = str_replace(' ','',$ukuran);
                $ukuran3 = preg_replace('/\s+/','',$ukuran2);
                $flukuran = str_replace(',', '.', $ukuran3);;
                echo "$koderoll3 - $ukuran3 - $flukuran<br>";
                if($ukuran3!=""){
                    $jumlah_roll+=1;
                    $panjang = doubleval($flukuran);
                    $total_panjang+=$panjang;
                    $dtlist = [
                        'kd' => $kd,
                        'konstruksi' => $st,
                        'siap_jual' => $siapdol,
                        'kode' => $koderoll3=='' ? 'null':$koderoll3,
                        'ukuran' => $ukuran3,
                        'status' => 'null',
                        'satuan' => $ym
                    ];
                    $this->data_model->saved('new_tb_isi', $dtlist);
                }
                
            } //end for perulangan by excel
            //echo "$kd - $st - $siapdol - $total_panjang";
            $pkg = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kd]);
            $newroll = intval($pkg->row("jumlah_roll")) + $jumlah_roll;
            $newpanjang = intval($pkg->row("ttl_panjang")) + $total_panjang;
            $this->data_model->updatedata('kd',$kd,'new_tb_packinglist', ['jumlah_roll'=>$newroll, 'ttl_panjang'=>round($newpanjang,2)]);
            $this->session->set_flashdata('announce', 'Packinglist berhasil di simpan');
            redirect(base_url('data/kode/'.$kd));
        } else {
            $this->session->set_flashdata('gagal', 'Format file yang anda masukan salah');
            redirect(base_url('data/kode/'.$kd));
        }
    }

    function new_import_fol2(){
        $abjad = ['0'=>'','1'=>'A','2'=>'B','3'=>'C','4'=>'D','5'=>'E','6'=>'F','7'=>'G','8'=>'H'];
        $sess_id = $this->session->userdata('id');
        $loc = $this->session->userdata('departement');
        $tgl_now = date('Y-m-d');
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
                if($koderoll3=="") { $koderoll4="null"; } else { $koderoll4=$koderoll3; }
                $ukuran = $sheetData[$i][1];
                $ukuran2 = str_replace(' ','',$ukuran);
                $ukuran3 = preg_replace('/\s+/','',$ukuran2);
                $folding = $sheetData[$i][2];
                $tgl_fol = $sheetData[$i][3];
                $tgl_fol2 = str_replace(' ','',$tgl_fol);
                $tgl_fol3 = preg_replace('/\s+/','',$tgl_fol2);
                if($tgl_fol!=""){
                $ex = explode('-', $tgl_fol3);
                $formatTgl = $ex[2]."-".$ex[1]."-".$ex[0]; }
                $operator = $sheetData[$i][4];
                $_kdkons = $sheetData[$i][5];
                $_kdkons2 = str_replace(' ','',$_kdkons);
                $_kdkons3 = preg_replace('/\s+/','',$_kdkons2);
                $_join = $sheetData[$i][6];
                $temp_file = [
                    'kode_roll' => $sheetData[$i][0],
                    'ukuran' => $sheetData[$i][1],
                    'folding' => $sheetData[$i][2],
                    'tgl' => $formatTgl,
                    'operator' => $sheetData[$i][4],
                    'kons' => $sheetData[$i][5],
                    'joinss' => $sheetData[$i][6]
                ];
                //$this->data_model->saved('temp_upload_fol', $temp_file);
                if($_join=="") { $joinss="false"; } else { $joinss="true"; }
                echo "($i) -- $koderoll4 - $ukuran3 - $folding - $tgl_fol - $operator - $_kdkons3<br>";
                $input1 = [
                    'kode_roll' => $koderoll4,
                    'konstruksi' => $_kdkons3,
                    'ukuran' => $ukuran3,
                    'jns_fold' => $folding,
                    'tgl' => $formatTgl,
                    'operator' => $operator,
                    'loc' => $loc,
                    'posisi' => $loc,
                    'join' => $joinss
                ];
                $this->data_model->saved('data_fol', $input1);
                $cek_harian = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$formatTgl, 'dep'=>$loc]);
                if($cek_harian->num_rows() == 0 ){
                    $input1 = [
                        'tgl' => $formatTgl,
                        'dep' => $loc,
                        'prod_ig' => 0,
                        'prod_fg' => $folding=='Grey' ? $ukuran3:0,
                        'prod_if' => 0,
                        'prod_ff' => $folding=='Finish' ? $ukuran3:0,
                        'prod_bs1' => 0,
                        'prod_bp1' => 0,
                        'prod_bs2' => 0,
                        'prod_bp2' => 0,
                        'crt' => 0
                    ];
                    $this->data_model->saved('data_produksi_harian', $input1);
                } else {
                    $id_prod_hr = $cek_harian->row("id_prod_hr");
                    if($folding=="Grey"){
                        $new_fg = floatval($cek_harian->row("prod_fg")) + $ukuran3;
                        $updatess = [
                            'prod_fg' => round($new_fg,2)
                        ];
                    } else {
                        $new_ff = floatval($cek_harian->row("prod_ff")) + $ukuran3;
                        $updatess = [
                            'prod_ff' => round($new_ff,2)
                        ];
                    }
                    $this->data_model->updatedata('id_prod_hr', $id_prod_hr, 'data_produksi_harian', $updatess);
                }
                $cek_produksi = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$_kdkons3,'tgl'=>$formatTgl, 'dep'=>$loc]);
                if($cek_produksi->num_rows() == 0 ){
                    $input2 = [
                        'kode_konstruksi' => $_kdkons3,
                        'tgl' => $formatTgl,
                        'dep' => $loc,
                        'prod_ig' => 0,
                        'prod_fg' => $folding=='Grey' ? $ukuran3:0,
                        'prod_if' => 0,
                        'prod_ff' => $folding=='Finish' ? $ukuran3:0,
                        'prod_bs1' => 0,
                        'prod_bp1' => 0,
                        'prod_bs2' => 0,
                        'prod_bp2' => 0,
                        'crt' => 0
                    ];
                    $this->data_model->saved('data_produksi', $input2);
                } else {
                    $id_produksi = $cek_produksi->row("id_produksi");
                    if($folding=="Grey"){
                        $prod_fg = floatval($cek_produksi->row("prod_fg")) + $ukuran3;
                        $updatess = [
                            'prod_fg' => round($prod_fg,2)
                        ];
                    } else {
                        $prod_ff = floatval($cek_produksi->row("prod_ff")) + $ukuran3;
                        $updatess = [
                            'prod_ff' => round($prod_ff,2)
                        ];
                    }
                    $this->data_model->updatedata('id_produksi', $id_produksi, 'data_produksi', $updatess);
                }
            } //end for perulangan by excel
            $this->session->set_flashdata('announce', 'Proses Folding berhasil di simpan');
            //redirect(base_url('proses-produksi'));
        } else {
            $this->session->set_flashdata('gagal', 'Format file yang anda masukan salah');
            redirect(base_url('proses-produksi'));
        }
    } //end
}

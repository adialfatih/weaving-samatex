<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    class Phpsq3 extends CI_Controller {
        public function __construct(){
        parent::__construct();
        $this->load->model('data_model');
        $this->load->model('data_model2');
        date_default_timezone_set("Asia/Jakarta");
    }
    
    public function index(){
        //$this->load->view('spreadsheet');
    }

    function new_import_fol(){
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
            $total_ukuran = 0;
            for ($i=1; $i <count($sheetData) ; $i++) {
                $kode_roll = $sheetData[$i][0];
                $ukuran = $sheetData[$i][1];
                $folding = $sheetData[$i][2];
                $tgl_fol = $sheetData[$i][3];
                if($tgl_fol!=""){
                $ex = explode('-', $tgl_fol);
                $formatTgl = $ex[2]."-".$ex[1]."-".$ex[0]; }
                $operator = $sheetData[$i][4];
                $_kdkons = $sheetData[$i][5];
                $_join = $sheetData[$i][6];
                if($_join=="") { $joinss="false"; } else { $joinss="true"; }
                if($kode_roll=="" AND $ukuran=="" AND $folding=="" AND $tgl_fol=="" AND $operator=="" AND $_kdkons==""){} else {
                echo $i.". ".$kode_roll." - ".$ukuran." - ".$folding." - ".$formatTgl." - ".$operator." - ".$_kdkons." - ".$_join."()-->";
                $pecah_ukuran = explode(',', $ukuran);
                if(count($pecah_ukuran) > 1){
                    echo "ada pecahan ini<br>";
                    for ($zx=0; $zx <count($pecah_ukuran) ; $zx++) { 
                        $hitungan = floatval($pecah_ukuran[$zx]);
                        $total_ukuran+=$hitungan;
                        if($zx==0){
                            $new_kode_roll = $kode_roll;
                        } else {
                            $new_kode_roll = $kode_roll."-".$zx;
                        }
                    $cek_kode_roll = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kode_roll]);
                    $cek_konstruksi = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$_kdkons]);
                    if($cek_kode_roll->num_rows() == 1){
                        //ini di input dan di hitung stok baru. cek dulu konstruksinya.
                        $cek_kode_roll_folding = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->num_rows();
                        if($cek_kode_roll_folding==0){
                            if($cek_konstruksi->num_rows()==1){
                                $dtfol = [
                                    'kode_roll' => $new_kode_roll,
                                    'konstruksi' => $_kdkons,
                                    'ukuran' => round($hitungan,2),
                                    'jns_fold' => $folding,
                                    'tgl' => $formatTgl,
                                    'operator' => $operator,
                                    'loc' => $loc,
                                    'posisi' => $loc,
                                    'join' => $joinss
                                ];
                                $this->data_model->saved('data_fol',$dtfol);
                                //cek data produksi 
                                $cek_prod = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$_kdkons, 'tgl'=>$formatTgl, 'dep'=>$loc]);
                                if($cek_prod->num_rows()==0){
                                    $dtprod = [
                                        'kode_konstruksi' => $_kdkons,
                                        'tgl' => $formatTgl,
                                        'dep' => $loc,
                                        'prod_ig' => 0,
                                        'prod_fg' => round($hitungan,2),
                                        'prod_if' => 0,
                                        'prod_ff' => 0,
                                        'prod_bs1' => 0,
                                        'prod_bp1' => 0,
                                        'prod_bs2' => 0,
                                        'prod_bp2' => 0,
                                        'crt' => 0
                                    ];
                                    $this->data_model->saved('data_produksi',$dtprod);
                                } else {
                                    $id_produksi = $cek_prod->row("id_produksi");
                                    if($folding=="Grey"){
                                        $new_fg = floatval($cek_prod->row("prod_fg")) + $hitungan;
                                        $updt = ['prod_fg'=>round($new_fg,2)];
                                    } else {
                                        $new_ff = floatval($cek_prod->row("prod_ff")) + $hitungan;
                                        $updt = ['prod_ff'=>round($new_ff,2)];
                                    }
                                    $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',$updt);
                                }
                                //cek produksi harian
                                $cek_prod_hr = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$formatTgl, 'dep'=>$loc]);
                                if($cek_prod_hr->num_rows()==0){
                                    $dtprod = [
                                        'tgl' => $formatTgl,
                                        'dep' => $loc,
                                        'prod_ig' => 0,
                                        'prod_fg' => round($hitungan,2),
                                        'prod_if' => 0,
                                        'prod_ff' => 0,
                                        'prod_bs1' => 0,
                                        'prod_bp1' => 0,
                                        'prod_bs2' => 0,
                                        'prod_bp2' => 0,
                                        'crt' => 0
                                    ];
                                    $this->data_model->saved('data_produksi_harian',$dtprod);
                                } else {
                                    $id_produksi = $cek_prod_hr->row("id_prod_hr");
                                    if($folding=="Grey"){
                                        $new_fg = floatval($cek_prod_hr->row("prod_fg")) + $hitungan;
                                        $updt = ['prod_fg'=>round($new_fg,2)];
                                    } else {
                                        $new_ff = floatval($cek_prod_hr->row("prod_ff")) + $hitungan;
                                        $updt = ['prod_ff'=>round($new_ff,2)];
                                    }
                                    $this->data_model->updatedata('id_prod_hr',$id_produksi,'data_produksi_harian',$updt);
                                }
                                //cek stok tambahkan stok
                                if($cek_kode_roll->row("dep")==$loc AND $cek_kode_roll->row("loc_now")==$loc){
                                    $all_dep = $loc;
                                } else {
                                    $all_dep = $cek_kode_roll->row("dep")."-IN-".$cek_kode_roll->row("loc_now");
                                }
                                $cek_stok = $this->data_model->get_byid('data_stok', ['dep'=>$all_dep, 'kode_konstruksi'=>$_kdkons]);
                                if($cek_stok->num_rows()==0){
                                    $dtprod = [
                                        'tgl' => $formatTgl,
                                        'dep' => $all_dep,
                                        'prod_ig' => 0,
                                        'prod_fg' => round($hitungan,2),
                                        'prod_if' => 0,
                                        'prod_ff' => 0,
                                        'prod_bs1' => 0,
                                        'prod_bp1' => 0,
                                        'prod_bs2' => 0,
                                        'prod_bp2' => 0,
                                        'crt' => 0
                                    ];
                                    $this->data_model->saved('data_stok',$dtprod);
                                } else {
                                    $idstok = $cek_stok->row("idstok");
                                    if($folding=="Grey"){
                                        $new_fg = floatval($cek_stok->row("prod_fg")) + $hitungan;
                                        $new_ig = floatval($cek_stok->row("prod_ig")) - $hitungan;
                                        $updt = ['prod_ig'=>round($new_ig,2),'prod_fg'=>round($new_fg,2)];
                                    } else {
                                        $new_ff = floatval($cek_stok->row("prod_ff")) + $hitungan;
                                        $new_if = floatval($cek_stok->row("prod_if")) - $hitungan;
                                        $updt = ['prod_if'=>round($new_if,2),'prod_ff'=>round($new_fg,2)];
                                    }
                                    $this->data_model->updatedata('idstok',$idstok,'data_stok',$updt);
                                }
                            } else {
                                $log = [
                                    'id_user' => $sess_id,
                                    'log_text' => 'Baris ke-'.$i.' tidak di input karena konstruksi '.$_kdkons.' tidak ditemukan'
                                ];
                                $this->data_model->saved('log_program', $log);
                            }
                        } else {
                            $log = [
                                'id_user' => $sess_id,
                                'log_text' => 'Baris ke-'.$i.' tidak di input karena kode '.$kode_roll.' telah di folding'
                            ];
                            $this->data_model->saved('log_program', $log);
                        }
                    } else {
                        //ini diinput dan dihitung stok laama sebelum tanggal 15
                        //di stok lama di cek dulu ada gak kode nya di stok lama
                        $cek_kode_sl = $this->data_model->get_byid('data_fol_lama',['kode_roll'=>$kode_roll]);
                        if($cek_kode_sl->num_rows()==0){
                            $dtfol = [
                                'kode_roll' => $new_kode_roll,
                                'ukuran_asli' => round($hitungan,2),
                                'ukuran_now' => round($hitungan,2),
                                'folding' => $folding,
                                'lokasi' => $loc,
                                'tanggal' => $formatTgl,
                                'operator' => $operator,
                                'konstruksi' => $_kdkons,
                                'join' => $joinss
                            ];
                            $this->data_model->saved('data_fol_lama',$dtfol);
                            //cek data produksi 
                            $cek_prod = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$_kdkons, 'tgl'=>$formatTgl, 'dep'=>$loc]);
                            if($cek_prod->num_rows()==0){
                                $dtprod = [
                                    'kode_konstruksi' => $_kdkons,
                                    'tgl' => $formatTgl,
                                    'dep' => $loc,
                                    'prod_ig' => 0,
                                    'prod_fg' => round($hitungan,2),
                                    'prod_if' => 0,
                                    'prod_ff' => 0,
                                    'prod_bs1' => 0,
                                    'prod_bp1' => 0,
                                    'prod_bs2' => 0,
                                    'prod_bp2' => 0,
                                    'crt' => 0
                                ];
                                $this->data_model->saved('data_produksi',$dtprod);
                            } else {
                                $id_produksi = $cek_prod->row("id_produksi");
                                if($folding=="Grey"){
                                    $new_fg = floatval($cek_prod->row("prod_fg")) + $hitungan;
                                    $updt = ['prod_fg'=>round($new_fg,2)];
                                } else {
                                    $new_ff = floatval($cek_prod->row("prod_ff")) + $hitungan;
                                    $updt = ['prod_ff'=>round($new_ff,2)];
                                }
                                $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',$updt);
                            }
                            //cek produksi harian
                            $cek_prod_hr = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$formatTgl, 'dep'=>$loc]);
                            if($cek_prod_hr->num_rows()==0){
                                $dtprod = [
                                    'tgl' => $formatTgl,
                                    'dep' => $loc,
                                    'prod_ig' => 0,
                                    'prod_fg' => round($hitungan,2),
                                    'prod_if' => 0,
                                    'prod_ff' => 0,
                                    'prod_bs1' => 0,
                                    'prod_bp1' => 0,
                                    'prod_bs2' => 0,
                                    'prod_bp2' => 0,
                                    'crt' => 0
                                ];
                                $this->data_model->saved('data_produksi_harian',$dtprod);
                            } else {
                                $id_produksi = $cek_prod_hr->row("id_prod_hr");
                                if($folding=="Grey"){
                                    $new_fg = floatval($cek_prod_hr->row("prod_fg")) + $hitungan;
                                    $updt = ['prod_fg'=>round($new_fg,2)];
                                } else {
                                    $new_ff = floatval($cek_prod_hr->row("prod_ff")) + $hitungan;
                                    $updt = ['prod_ff'=>round($new_ff,2)];
                                }
                                $this->data_model->updatedata('id_prod_hr',$id_produksi,'data_produksi_harian',$updt);
                            }
                            //cek stok tambahkan stok
                            $cek_stok = $this->data_model->get_byid('data_stok', ['dep'=>'SL', 'kode_konstruksi'=>$_kdkons]);
                            if($cek_stok->num_rows()==0){
                                $dtprod = [
                                    'tgl' => $formatTgl,
                                    'dep' => $all_dep,
                                    'prod_ig' => 0,
                                    'prod_fg' => round($hitungan,2),
                                    'prod_if' => 0,
                                    'prod_ff' => 0,
                                    'prod_bs1' => 0,
                                    'prod_bp1' => 0,
                                    'prod_bs2' => 0,
                                    'prod_bp2' => 0,
                                    'crt' => 0
                                ];
                                $this->data_model->saved('data_stok',$dtprod);
                            } else {
                                $idstok = $cek_stok->row("idstok");
                                if($folding=="Grey"){
                                    $new_fg = floatval($cek_stok->row("prod_fg")) + $hitungan;
                                    $new_ig = floatval($cek_stok->row("prod_ig")) - $hitungan;
                                    $updt = ['prod_ig'=>round($new_ig,2),'prod_fg'=>round($new_fg,2)];
                                } else {
                                    $new_ff = floatval($cek_stok->row("prod_ff")) + $hitungan;
                                    $new_if = floatval($cek_stok->row("prod_if")) - $hitungan;
                                    $updt = ['prod_if'=>round($new_if,2),'prod_ff'=>round($new_fg,2)];
                                }
                                $this->data_model->updatedata('idstok',$idstok,'data_stok',$updt);
                            }
                        } else {
                            $log = [
                                'id_user' => $sess_id,
                                'log_text' => 'Baris ke-'.$i.' tidak di input karena kode '.$kode_roll.' telah di folding'
                            ];
                            $this->data_model->saved('log_program', $log);
                        }
                    }

                    }
                } else {
                    echo "gak ada pecahan <br>";
                    $hitungan = floatval($ukuran);
                    $total_ukuran+=$hitungan;
                    //hitungan adalah ukuran yang tertera di dalam excel
                    $cek_kode_roll = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kode_roll]);
                    $cek_konstruksi = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$_kdkons]);
                    if($cek_kode_roll->num_rows() == 1){
                        //ini di input dan di hitung stok baru. cek dulu konstruksinya.
                        $cek_kode_roll_folding = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->num_rows();
                        if($cek_kode_roll_folding==0){
                            if($cek_konstruksi->num_rows()==1){
                                $dtfol = [
                                    'kode_roll' => $kode_roll,
                                    'konstruksi' => $_kdkons,
                                    'ukuran' => round($hitungan,2),
                                    'jns_fold' => $folding,
                                    'tgl' => $formatTgl,
                                    'operator' => $operator,
                                    'loc' => $loc,
                                    'posisi' => $loc,
                                    'join' => $joinss
                                ];
                                $this->data_model->saved('data_fol',$dtfol);
                                //cek data produksi 
                                $cek_prod = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$_kdkons, 'tgl'=>$formatTgl, 'dep'=>$loc]);
                                if($cek_prod->num_rows()==0){
                                    $dtprod = [
                                        'kode_konstruksi' => $_kdkons,
                                        'tgl' => $formatTgl,
                                        'dep' => $loc,
                                        'prod_ig' => 0,
                                        'prod_fg' => round($hitungan,2),
                                        'prod_if' => 0,
                                        'prod_ff' => 0,
                                        'prod_bs1' => 0,
                                        'prod_bp1' => 0,
                                        'prod_bs2' => 0,
                                        'prod_bp2' => 0,
                                        'crt' => 0
                                    ];
                                    $this->data_model->saved('data_produksi',$dtprod);
                                } else {
                                    $id_produksi = $cek_prod->row("id_produksi");
                                    if($folding=="Grey"){
                                        $new_fg = floatval($cek_prod->row("prod_fg")) + $hitungan;
                                        $updt = ['prod_fg'=>round($new_fg,2)];
                                    } else {
                                        $new_ff = floatval($cek_prod->row("prod_ff")) + $hitungan;
                                        $updt = ['prod_ff'=>round($new_ff,2)];
                                    }
                                    $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',$updt);
                                }
                                //cek produksi harian
                                $cek_prod_hr = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$formatTgl, 'dep'=>$loc]);
                                if($cek_prod_hr->num_rows()==0){
                                    $dtprod = [
                                        'tgl' => $formatTgl,
                                        'dep' => $loc,
                                        'prod_ig' => 0,
                                        'prod_fg' => round($hitungan,2),
                                        'prod_if' => 0,
                                        'prod_ff' => 0,
                                        'prod_bs1' => 0,
                                        'prod_bp1' => 0,
                                        'prod_bs2' => 0,
                                        'prod_bp2' => 0,
                                        'crt' => 0
                                    ];
                                    $this->data_model->saved('data_produksi_harian',$dtprod);
                                } else {
                                    $id_produksi = $cek_prod_hr->row("id_prod_hr");
                                    if($folding=="Grey"){
                                        $new_fg = floatval($cek_prod_hr->row("prod_fg")) + $hitungan;
                                        $updt = ['prod_fg'=>round($new_fg,2)];
                                    } else {
                                        $new_ff = floatval($cek_prod_hr->row("prod_ff")) + $hitungan;
                                        $updt = ['prod_ff'=>round($new_ff,2)];
                                    }
                                    $this->data_model->updatedata('id_prod_hr',$id_produksi,'data_produksi_harian',$updt);
                                }
                                //cek stok tambahkan stok
                                if($cek_kode_roll->row("dep")==$loc AND $cek_kode_roll->row("loc_now")==$loc){
                                    $all_dep = $loc;
                                } else {
                                    $all_dep = $cek_kode_roll->row("dep")."-IN-".$cek_kode_roll->row("loc_now");
                                }
                                $cek_stok = $this->data_model->get_byid('data_stok', ['dep'=>$all_dep, 'kode_konstruksi'=>$_kdkons]);
                                if($cek_stok->num_rows()==0){
                                    $dtprod = [
                                        'tgl' => $formatTgl,
                                        'dep' => $all_dep,
                                        'prod_ig' => 0,
                                        'prod_fg' => round($hitungan,2),
                                        'prod_if' => 0,
                                        'prod_ff' => 0,
                                        'prod_bs1' => 0,
                                        'prod_bp1' => 0,
                                        'prod_bs2' => 0,
                                        'prod_bp2' => 0,
                                        'crt' => 0
                                    ];
                                    $this->data_model->saved('data_stok',$dtprod);
                                } else {
                                    $idstok = $cek_stok->row("idstok");
                                    if($folding=="Grey"){
                                        $new_fg = floatval($cek_stok->row("prod_fg")) + $hitungan;
                                        $new_ig = floatval($cek_stok->row("prod_ig")) - $hitungan;
                                        $updt = ['prod_ig'=>round($new_ig,2),'prod_fg'=>round($new_fg,2)];
                                    } else {
                                        $new_ff = floatval($cek_stok->row("prod_ff")) + $hitungan;
                                        $new_if = floatval($cek_stok->row("prod_if")) - $hitungan;
                                        $updt = ['prod_if'=>round($new_if,2),'prod_ff'=>round($new_fg,2)];
                                    }
                                    $this->data_model->updatedata('idstok',$idstok,'data_stok',$updt);
                                }
                            } else {
                                $log = [
                                    'id_user' => $sess_id,
                                    'log_text' => 'Baris ke-'.$i.' tidak di input karena konstruksi '.$_kdkons.' tidak ditemukan'
                                ];
                                $this->data_model->saved('log_program', $log);
                            }
                        } else {
                            $log = [
                                'id_user' => $sess_id,
                                'log_text' => 'Baris ke-'.$i.' tidak di input karena kode '.$kode_roll.' telah di folding'
                            ];
                            $this->data_model->saved('log_program', $log);
                        }
                    } else {
                        //ini diinput dan dihitung stok laama sebelum tanggal 15
                        //di stok lama di cek dulu ada gak kode nya di stok lama
                        $cek_kode_sl = $this->data_model->get_byid('data_fol_lama',['kode_roll'=>$kode_roll]);
                        if($cek_kode_sl->num_rows()==0){
                            $dtfol = [
                                'kode_roll' => $kode_roll,
                                'ukuran_asli' => round($hitungan,2),
                                'ukuran_now' => round($hitungan,2),
                                'folding' => $folding,
                                'lokasi' => $loc,
                                'tanggal' => $formatTgl,
                                'operator' => $operator,
                                'konstruksi' => $_kdkons,
                                'join' => $joinss
                            ];
                            $this->data_model->saved('data_fol_lama',$dtfol);
                            //cek data produksi 
                            $cek_prod = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$_kdkons, 'tgl'=>$formatTgl, 'dep'=>$loc]);
                            if($cek_prod->num_rows()==0){
                                $dtprod = [
                                    'kode_konstruksi' => $_kdkons,
                                    'tgl' => $formatTgl,
                                    'dep' => $loc,
                                    'prod_ig' => 0,
                                    'prod_fg' => round($hitungan,2),
                                    'prod_if' => 0,
                                    'prod_ff' => 0,
                                    'prod_bs1' => 0,
                                    'prod_bp1' => 0,
                                    'prod_bs2' => 0,
                                    'prod_bp2' => 0,
                                    'crt' => 0
                                ];
                                $this->data_model->saved('data_produksi',$dtprod);
                            } else {
                                $id_produksi = $cek_prod->row("id_produksi");
                                if($folding=="Grey"){
                                    $new_fg = floatval($cek_prod->row("prod_fg")) + $hitungan;
                                    $updt = ['prod_fg'=>round($new_fg,2)];
                                } else {
                                    $new_ff = floatval($cek_prod->row("prod_ff")) + $hitungan;
                                    $updt = ['prod_ff'=>round($new_ff,2)];
                                }
                                $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',$updt);
                            }
                            //cek produksi harian
                            $cek_prod_hr = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$formatTgl, 'dep'=>$loc]);
                            if($cek_prod_hr->num_rows()==0){
                                $dtprod = [
                                    'tgl' => $formatTgl,
                                    'dep' => $loc,
                                    'prod_ig' => 0,
                                    'prod_fg' => round($hitungan,2),
                                    'prod_if' => 0,
                                    'prod_ff' => 0,
                                    'prod_bs1' => 0,
                                    'prod_bp1' => 0,
                                    'prod_bs2' => 0,
                                    'prod_bp2' => 0,
                                    'crt' => 0
                                ];
                                $this->data_model->saved('data_produksi_harian',$dtprod);
                            } else {
                                $id_produksi = $cek_prod_hr->row("id_prod_hr");
                                if($folding=="Grey"){
                                    $new_fg = floatval($cek_prod_hr->row("prod_fg")) + $hitungan;
                                    $updt = ['prod_fg'=>round($new_fg,2)];
                                } else {
                                    $new_ff = floatval($cek_prod_hr->row("prod_ff")) + $hitungan;
                                    $updt = ['prod_ff'=>round($new_ff,2)];
                                }
                                $this->data_model->updatedata('id_prod_hr',$id_produksi,'data_produksi_harian',$updt);
                            }
                            //cek stok tambahkan stok
                            $cek_stok = $this->data_model->get_byid('data_stok', ['dep'=>'SL', 'kode_konstruksi'=>$_kdkons]);
                            if($cek_stok->num_rows()==0){
                                $dtprod = [
                                    'tgl' => $formatTgl,
                                    'dep' => $all_dep,
                                    'prod_ig' => 0,
                                    'prod_fg' => round($hitungan,2),
                                    'prod_if' => 0,
                                    'prod_ff' => 0,
                                    'prod_bs1' => 0,
                                    'prod_bp1' => 0,
                                    'prod_bs2' => 0,
                                    'prod_bp2' => 0,
                                    'crt' => 0
                                ];
                                $this->data_model->saved('data_stok',$dtprod);
                            } else {
                                $idstok = $cek_stok->row("idstok");
                                if($folding=="Grey"){
                                    $new_fg = floatval($cek_stok->row("prod_fg")) + $hitungan;
                                    $new_ig = floatval($cek_stok->row("prod_ig")) - $hitungan;
                                    $updt = ['prod_ig'=>round($new_ig,2),'prod_fg'=>round($new_fg,2)];
                                } else {
                                    $new_ff = floatval($cek_stok->row("prod_ff")) + $hitungan;
                                    $new_if = floatval($cek_stok->row("prod_if")) - $hitungan;
                                    $updt = ['prod_if'=>round($new_if,2),'prod_ff'=>round($new_fg,2)];
                                }
                                $this->data_model->updatedata('idstok',$idstok,'data_stok',$updt);
                            }
                        } else {
                            $log = [
                                'id_user' => $sess_id,
                                'log_text' => 'Baris ke-'.$i.' tidak di input karena kode '.$kode_roll.' telah di folding'
                            ];
                            $this->data_model->saved('log_program', $log);
                        }
                    }
                }
                $total_ukuran = round($total_ukuran,2);
                }
            } //end for perulangan by excel
            echo "<hr>";
            echo "Total ukuran = ".$total_ukuran."<br>";
            $this->session->set_flashdata('announce', 'Proses Folding berhasil di simpan');
            //redirect(base_url('proses-produksi'));
        } else {
            $this->session->set_flashdata('gagal', 'Format file yang anda masukan salah');
            //redirect(base_url('proses-produksi'));
        }
    } //end
}
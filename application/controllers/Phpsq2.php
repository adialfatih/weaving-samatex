<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    class Phpsq2 extends CI_Controller {
        public function __construct(){
        parent::__construct();
        $this->load->model('data_model');
        $this->load->model('data_model2');
        date_default_timezone_set("Asia/Jakarta");
    }
    
    public function index(){
        //$this->load->view('spreadsheet');
    }

    public function new_import_if2(){
        $abjad = ['0'=>'','1'=>'A','2'=>'B','3'=>'C','4'=>'D','5'=>'E','6'=>'F','7'=>'G','8'=>'H'];
        $id_user = $this->session->userdata('id'); 
        $depuser = 'Samatex';
        $kodeupload = $this->input->post('kodeauto');
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
                $tgl = $sheetData[$i][1];
                $tgl2 = str_replace(' ','',$tgl);
                $tgl3 = preg_replace('/\s+/','',$tgl2);
                if($tgl3!=""){
                $ex = explode('-', $tgl3);
                $formatTgl = $ex[2]."-".$ex[1]."-".$ex[0]; }
                $panjang = $sheetData[$i][2];
                $bs = $sheetData[$i][3];
                $crt = $sheetData[$i][4];
                if($crt==""){$crt="0";}
                if($bs==""){$bs="0";}
                $ukrbp = $sheetData[$i][5];
                if($ukrbp==""){$ukrbp="0";}
                $satuan = $sheetData[$i][6];
                $oper_nama = $sheetData[$i][7];
                $ket = $sheetData[$i][8];
                $_kdkons = $sheetData[$i][9];
                $_kdkons2 = str_replace(' ','',$_kdkons);
                $_kdkons3 = preg_replace('/\s+/','',$_kdkons2);
                $temp_data = [
                    'kode_roll' => $koderoll3,
                    'tgl' => $formatTgl,
                    'panjang' => $sheetData[$i][2],
                    'bs' => $bs,
                    'crt' => $crt,
                    'bp' => $ukrbp,
                    'satuan' => $sheetData[$i][6],
                    'operator' => $sheetData[$i][7],
                    'ket' => $sheetData[$i][8],
                    'kons' => $_kdkons3,
                    'kodeauto' => $kodeupload
                ];
                $this->data_model->saved('temp_upload_if', $temp_data);
                $cek_kode_digrey = $this->data_model->get_byid('data_ig', ['kode_roll'=>$koderoll3]);
                if($cek_kode_digrey->num_rows() == 1){ $ada = "y"; } else { $ada = "n"; }
                $pecah = explode(',', $panjang);
                if(count($pecah) > 1){
                    for ($z=0; $z < count($pecah) ; $z++) { 
                        if($pecah[$z]==0){
                            $temp_data = [
                                'kode_roll' => $koderoll3,
                                'tgl' => $formatTgl,
                                'panjang' => $pecah[$z],
                                'bs' => $bs,
                                'crt' => $crt,
                                'bp' => $ukrbp,
                                'satuan' => $sheetData[$i][6],
                                'operator' => $sheetData[$i][7],
                                'ket' => $sheetData[$i][8],
                                'kons' => $_kdkons3,
                                'kodeauto' => $kodeupload,
                                'ada_digrey' => $ada
                            ];
                            $this->data_model->saved('temp_upload_if2', $temp_data);
                        } else {
                            $newkode = $koderoll3."".$abjad[$z];
                            $temp_data = [
                                'kode_roll' => $newkode,
                                'tgl' => $formatTgl,
                                'panjang' => $pecah[$z],
                                'bs' => 0,
                                'crt' => 0,
                                'bp' => 0,
                                'satuan' => $sheetData[$i][6],
                                'operator' => $sheetData[$i][7],
                                'ket' => $sheetData[$i][8],
                                'kons' => $_kdkons3,
                                'kodeauto' => $kodeupload,
                                'ada_digrey' => $ada
                            ];
                            $this->data_model->saved('temp_upload_if2', $temp_data);
                        }
                        
                    }
                } else {
                    $temp_data = [
                        'kode_roll' => $koderoll3,
                        'tgl' => $formatTgl,
                        'panjang' => $sheetData[$i][2],
                        'bs' => $bs,
                        'crt' => $crt,
                        'bp' => $ukrbp,
                        'satuan' => $sheetData[$i][6],
                        'operator' => $sheetData[$i][7],
                        'ket' => $sheetData[$i][8],
                        'kons' => $_kdkons3,
                        'kodeauto' => $kodeupload,
                        'ada_digrey' => $ada
                    ];
                    $this->data_model->saved('temp_upload_if2', $temp_data);
                }
                
            } //end for perulangan by excel
            $tdlist = [
                'kodeauto' => $kodeupload,
                'proses_name' => 'Inspect Finish',
                'penginput' => $id_user
            ];
            $this->data_model->saved('log_input_roll', $tdlist);
            $this->session->set_flashdata('announce', 'Proses Inspect Finish berhasil di simpan');
            redirect(base_url('resume/insfinish/'.$kodeupload));
        } else {
            $this->session->set_flashdata('gagal', 'Format file yang anda masukan salah');
            //redirect(base_url('proses-produksi'));
        }
    }//end

    public function new_import_if(){
        $abjad = ['0'=>'','1'=>'A','2'=>'B','3'=>'C','4'=>'D','5'=>'E','6'=>'F','7'=>'G','8'=>'H'];
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
            $sess_id = $this->session->userdata('id');
            $loc = $this->session->userdata('departement');
            
            for ($i=1; $i <count($sheetData) ; $i++) { 
                $kode_roll = $sheetData[$i][0];
                $tgl = $sheetData[$i][1];
                if($tgl!=""){
                $ex = explode('-', $tgl);
                $formatTgl = $ex[2]."-".$ex[1]."-".$ex[0]; }
                $panjang = $sheetData[$i][2];
                $bs = $sheetData[$i][3];
                $crt = $sheetData[$i][4];
                if($crt==""){$crt="0";}
                if($bs==""){$bs="0";}
                $ukrbp = $sheetData[$i][5];
                if($ukrbp==""){$ukrbp="0";}
                $satuan = $sheetData[$i][6];
                $oper_nama = $sheetData[$i][7];
                $ket = $sheetData[$i][8];
                $_kdkons = $sheetData[$i][9];
                $temp_data = [
                    'kode_roll' => $sheetData[$i][0],
                    'tgl' => $formatTgl,
                    'panjang' => $sheetData[$i][2],
                    'bs' => $sheetData[$i][3],
                    'crt' => $sheetData[$i][4],
                    'bp' => $sheetData[$i][5],
                    'satuan' => $sheetData[$i][6],
                    'operator' => $sheetData[$i][7],
                    'ket' => $sheetData[$i][8],
                    'kons' => $sheetData[$i][9],
                ];
                $this->data_model->saved('temp_upload_if', $temp_data);
                
                //tes perhitungan perulangan
                $total_panjang= 0;
                $dx_new = explode(',',$panjang);
                if(count($dx_new) == 1){
                    $total_panjang = floatval($panjang);
                } else {
                    for ($cakep=0; $cakep < count($dx_new); $cakep++) { 
                        $total_panjang+= floatval($dx_new[$cakep]);
                    }
                }
                $total_panjang = round($total_panjang,2);
                if($kode_roll=="" AND $tgl=="" AND $panjang=="" AND $satuan=="" AND $oper_nama==""){} else {
                $cek_dtig = $this->data_model->get_byid('data_ig',['kode_roll'=>$kode_roll]);
                if($cek_dtig->num_rows()==1){ 
                    $stok_asli = "true";
                    $kdkons = $cek_dtig->row("konstruksi");
                } else {
                    $kdkons = $_kdkons;
                    $stok_asli = "false";
                }
                $cek_konstruksi = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kdkons]);
                if($cek_konstruksi->num_rows()==1){
                    $cek_dtif = $this->data_model->get_byid('data_if',['kode_roll'=>$kode_roll]);
                    if($cek_dtif->num_rows()==0){
                        $dx = explode(',',$panjang);
                        $total_panjang_zzz = 0; $total_bs=0; $total_crt=0; $total_bp=0;
                        if(count($dx)>1){
                            for ($iowek=0; $iowek < count($dx); $iowek++) { 
                                $new_koderoll = $kode_roll."".$abjad[$iowek];
                                if($iowek==0){
                                    if($stok_asli=="true"){
                                    $dtif = [
                                        'kode_roll' => $new_koderoll,
                                        'tgl_potong' => $formatTgl,
                                        'ukuran_ori' => $dx[$iowek],
                                        'ukuran_bs' => $bs,
                                        'ukuran_crt' => $crt,
                                        'ukuran_bp' => $ukrbp,
                                        'operator' => $oper_nama,
                                        'ket' => $ket,
                                        'asal' => '0',
                                        'bp_canjoin' => $dx[$iowek]<50 ? 'true':'false',
                                        'konstruksi' => $_kdkons
                                    ];
                                    $this->data_model->saved('data_if', $dtif);
                                    } else {
                                        $cek_koderollagain = $this->data_model->get_byid('data_if_lama',['kode_roll'=>$new_koderoll]);
                                        if($cek_koderollagain->num_rows()==0){
                                        $dtif = [
                                            'kode_roll' => $new_koderoll,
                                            'tgl' => $formatTgl,
                                            'panjang' => $dx[$iowek],
                                            'bs' => $bs,
                                            'crt' => $crt,
                                            'bp' => $ukrbp,
                                            'satuan' => 'Yard',
                                            'operator' => $oper_nama,
                                            'ket' => $ket,
                                            'kodekons' => $kdkons,
                                            'posisi' => 'gudang',
                                            'panjang_now' => $dx[$iowek],
                                            'bp_canjoin' => $dx[$iowek]<50 ? 'true':'false'
                                        ];
                                        $this->data_model->saved('data_if_lama', $dtif); }
                                    }
                                    $total_bs+=floatval($bs);
                                    $total_bp+=floatval($ukrbp);
                                    $total_crt+=floatval($crt);
                                } else {
                                    if($stok_asli=="true"){
                                    $dtif = [
                                        'kode_roll' => $new_koderoll,
                                        'tgl_potong' => $formatTgl,
                                        'ukuran_ori' => $dx[$iowek],
                                        'ukuran_bs' => 0,
                                        'ukuran_crt' => 0,
                                        'ukuran_bp' => 0,
                                        'operator' => $oper_nama,
                                        'ket' => $ket,
                                        'asal' => $kode_roll,
                                        'bp_canjoin' => $dx[$iowek]<50 ? 'true':'false',
                                        'konstruksi' => $_kdkons
                                    ];
                                    $this->data_model->saved('data_if', $dtif);
                                    } else {
                                        $cek_koderollagain = $this->data_model->get_byid('data_if_lama',['kode_roll'=>$new_koderoll]);
                                        if($cek_koderollagain->num_rows()==0){
                                        $dtif = [
                                            'kode_roll' => $new_koderoll,
                                            'tgl' => $formatTgl,
                                            'panjang' => $dx[$iowek],
                                            'bs' => 0,
                                            'crt' => 0,
                                            'bp' => 0,
                                            'satuan' => 'Yard',
                                            'operator' => $oper_nama,
                                            'ket' => $ket,
                                            'kodekons' => $kdkons,
                                            'posisi' => 'gudang',
                                            'panjang_now' => $dx[$iowek],
                                            'bp_canjoin' => $dx[$iowek]<50 ? 'true':'false'
                                        ];
                                        $this->data_model->saved('data_if_lama', $dtif); }
                                    }
                                }
                                $pj_pecahan2 = floatval($dx[$iowek]);
                                $pj_pecahan = round($pj_pecahan2,2);
                                $total_panjang_zzz+=$pj_pecahan;
                            }
                        } else {
                            if($stok_asli=="true"){
                            $dtif = [
                                'kode_roll' => $kode_roll,
                                'tgl_potong' => $formatTgl,
                                'ukuran_ori' => $panjang,
                                'ukuran_bs' => $bs,
                                'ukuran_crt' => $crt,
                                'ukuran_bp' => $ukrbp,
                                'operator' => $oper_nama,
                                'ket' => $ket,
                                'asal' => '0',
                                'bp_canjoin' => $panjang<50 ? 'true':'false',
                                'konstruksi' => $_kdkons
                            ];
                            $this->data_model->saved('data_if', $dtif);
                            } else {
                                $cek_koderollagain = $this->data_model->get_byid('data_if_lama',['kode_roll'=>$kode_roll]);
                                if($cek_koderollagain->num_rows()==0){
                                $dtif = [
                                    'kode_roll' => $kode_roll,
                                    'tgl' => $formatTgl,
                                    'panjang' => $panjang,
                                    'bs' => $bs,
                                    'crt' => $crt,
                                    'bp' => $ukrbp,
                                    'satuan' => 'Yard',
                                    'operator' => $oper_nama,
                                    'ket' => $ket,
                                    'kodekons' => $kdkons,
                                    'posisi' => 'gudang',
                                    'panjang_now' => $panjang,
                                    'bp_canjoin' => $panjang<50 ? 'true':'false'
                                ];
                                $this->data_model->saved('data_if_lama', $dtif); }
                            }
                            $total_panjang_zzz += floatval($panjang);
                            $total_bs+=floatval($bs);
                            $total_bp+=floatval($ukrbp);
                            $total_crt+=floatval($crt);
                        }
                        $total_panjang_meter = $total_panjang * 0.9144;
                        $total_panjang_meter = round($total_panjang_meter,2);
                        $dt_kons = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kdkons]);
                        
                        $new_ig = floatval($dt_kons->row("stok_ig")) - floatval($total_panjang_meter);
                        $new_if = floatval($dt_kons->row("stok_if")) + floatval($total_panjang);
                        $new_bs = floatval($dt_kons->row("stok_bs2")) + floatval($total_bs);
                        $new_bp = floatval($dt_kons->row("stok_bp2")) + floatval($total_bp);
                        $new_crt = floatval($dt_kons->row("stok_crt")) + floatval($total_crt);
                        if($stok_asli=="true"){
                            $this->data_model->updatedata('kode_konstruksi',$kdkons,'tb_konstruksi',[ 'stok_ig'=>round($new_ig,2),'stok_if'=>round($new_if,2),'stok_bs2'=>round($new_bs,2),'stok_bp2'=>round($new_bp,2),'stok_crt'=>round($new_crt,2) ]);
                        } else {
                            $this->data_model->updatedata('kode_konstruksi',$kdkons,'tb_konstruksi',[ 'stok_if'=>round($new_if,2),'stok_bs2'=>round($new_bs,2),'stok_bp2'=>round($new_bp,2),'stok_crt'=>round($new_crt,2) ]);
                        }
                        $cek_prod = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kdkons, 'tgl'=>$formatTgl, 'dep'=>$loc]);
                        if($cek_prod->num_rows()==0){
                            $dtprod = [
                                'kode_konstruksi' => $kdkons,
                                'tgl' => $formatTgl,
                                'dep' => $loc,
                                'prod_ig' => 0,
                                'prod_fg' => 0,
                                'prod_if' => round($total_panjang,2),
                                'prod_ff' => 0,
                                'prod_bs1' => 0,
                                'prod_bp1' => 0,
                                'prod_bs2' => round($total_bs,2),
                                'prod_bp2' => round($total_bp,2),
                                'crt' => round($total_crt,2)
                            ];
                            $this->data_model->saved('data_produksi',$dtprod);
                        } else {
                            $idprod = $cek_prod->row("id_produksi");
                            $new_if = floatval($cek_prod->row("prod_if")) + floatval($total_panjang);
                            $new_bs = floatval($cek_prod->row("prod_bs2")) + floatval($total_bs);
                            $new_bp = floatval($cek_prod->row("prod_bp2")) + floatval($total_bp);
                            $new_crt = floatval($cek_prod->row("crt")) + floatval($total_crt);
                            $this->data_model->updatedata('id_produksi',$idprod,'data_produksi',['prod_if'=>round($new_if,2),'prod_bs2'=>round($new_bs,2),'prod_bp2'=>round($new_bp,2),'crt'=>round($new_crt,2)]);
                        }
                        $cek_harian = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$formatTgl,'dep'=>$loc]);
                        if($cek_harian->num_rows()==0){
                            $dtprod_hr = [
                                'tgl' => $formatTgl,
                                'dep' => $loc,
                                'prod_ig' => 0,
                                'prod_fg' => 0,
                                'prod_if' => round($total_panjang,2),
                                'prod_ff' => 0,
                                'prod_bs1' => 0,
                                'prod_bp1' => 0,
                                'prod_bs2' => round($total_bs,2),
                                'prod_bp2' => round($total_bp,2),
                                'crt' => round($total_crt,2)
                            ];
                            $this->data_model->saved('data_produksi_harian',$dtprod_hr);
                        } else {
                            $idprod = $cek_harian->row("id_prod_hr");
                            $new_if = floatval($cek_harian->row("prod_if")) + floatval($total_panjang);
                            $new_bs = floatval($cek_harian->row("prod_bs2")) + floatval($total_bs);
                            $new_bp = floatval($cek_harian->row("prod_bp2")) + floatval($total_bp);
                            $new_crt = floatval($cek_harian->row("crt")) + floatval($total_crt);
                            $this->data_model->updatedata('id_prod_hr',$idprod,'data_produksi_harian',['prod_if'=>round($new_if,2),'prod_bs2'=>round($new_bs,2),'prod_bp2'=>round($new_bp,2),'crt'=>round($new_crt,2)]);
                        }
                        if($stok_asli=="true"){
                        //start of stok
                        if($cek_dtig->row("dep") == $loc AND $cek_dtig->row("loc_now") == $loc){
                            $cek_stok = $this->data_model->get_byid('data_stok', ['dep'=>$loc,'kode_konstruksi'=>$kdkons]);
                            if($cek_stok->num_rows()==0){
                                $dtstok = [
                                    'dep' => $loc,
                                    'kode_konstruksi' => $kdkons,
                                    'prod_ig' => 0,
                                    'prod_fg' => 0,
                                    'prod_if' => round($total_panjang,2),
                                    'prod_ff' => 0,
                                    'prod_bs1' => 0,
                                    'prod_bp1' => 0,
                                    'prod_bs2' => round($total_bs,2),
                                    'prod_bp2' => round($total_bp,2),
                                    'crt' => round($total_crt,2)
                                ];
                                $this->data_model->saved('data_stok',$dtstok);
                            } else {
                                $idprod = $cek_stok->row("idstok");
                                $new_ig = floatval($cek_stok->row("prod_ig")) - floatval($total_panjang_meter);
                                $new_if = floatval($cek_stok->row("prod_if")) + floatval($total_panjang);
                                $new_bs = floatval($cek_stok->row("prod_bs2")) + floatval($total_bs);
                                $new_bp = floatval($cek_stok->row("prod_bp2")) + floatval($total_bp);
                                $new_crt = floatval($cek_stok->row("crt")) + floatval($total_crt);
                                $this->data_model->updatedata('idstok',$idprod,'data_stok',['prod_ig'=>round($new_ig,2),'prod_if'=>round($new_if,2),'prod_bs2'=>round($new_bs,2),'prod_bp2'=>round($new_bp,2),'crt'=>round($total_crt,2)]);
                            }
                        } else {
                            $depstok = $cek_dtig->row("dep")."-IN-".$cek_dtig->row("loc_now");
                            $cek_stok = $this->data_model->get_byid('data_stok', ['dep'=>$depstok,'kode_konstruksi'=>$kdkons]);
                            $idprod = $cek_stok->row("idstok");
                            $ig_dlmyrd = floatval($cek_stok->row("prod_ig")) / 0.9144;
                            $ig_dlmyrd2 = round($ig_dlmyrd,2);
                            $new_ig = $ig_dlmyrd2 - floatval($total_panjang);
                            $new_if = floatval($cek_stok->row("prod_if")) + floatval($total_panjang);
                            $new_bs = floatval($cek_stok->row("prod_bs2")) + floatval($total_bs);
                            $new_bp = floatval($cek_stok->row("prod_bp2")) + floatval($total_bp);
                            $new_crt = floatval($cek_stok->row("crt")) + floatval($total_crt);
                            $this->data_model->updatedata('idstok',$idprod,'data_stok',['prod_ig'=>round($new_ig,2),'prod_if'=>round($new_if,2),'prod_bs2'=>round($new_bs,2),'prod_bp2'=>round($new_bp,2),'crt'=>round($total_crt,2)]);
                        }
                        //end of stok
                        } else {
                            $cek_stok = $this->data_model->get_byid('data_stok', ['dep'=>'SL','kode_konstruksi'=>$kdkons]);
                            if($cek_stok->num_rows()==0){
                                $dtstok = [
                                    'dep' => 'SL',
                                    'kode_konstruksi' => $kdkons,
                                    'prod_ig' => 0,
                                    'prod_fg' => 0,
                                    'prod_if' => round($total_panjang,2),
                                    'prod_ff' => 0,
                                    'prod_bs1' => 0,
                                    'prod_bp1' => 0,
                                    'prod_bs2' => round($total_bs,2),
                                    'prod_bp2' => round($total_bp,2),
                                    'crt' => round($total_crt,2)
                                ];
                                $this->data_model->saved('data_stok',$dtstok);
                            } else {
                                $idprod = $cek_stok->row("idstok");
                                $new_if = floatval($cek_stok->row("prod_if")) + floatval($total_panjang);
                                $new_bs = floatval($cek_stok->row("prod_bs2")) + floatval($total_bs);
                                $new_bp = floatval($cek_stok->row("prod_bp2")) + floatval($total_bp);
                                $new_crt = floatval($cek_stok->row("crt")) + floatval($total_crt);
                                $this->data_model->updatedata('idstok',$idprod,'data_stok',['prod_if'=>round($new_if,2),'prod_bs2'=>round($new_bs,2),'prod_bp2'=>round($new_bp,2)]);
                            }
                        }
                    } else {
                        $txt = $kode_roll." tidak di input ke sistem karena kode sudah di simpan dalam database";
                        $dtlist = [ 'id_user' => $sess_id, 'log_text' => $txt];
                        $this->data_model->saved('log_program', $dtlist);
                    }
                } else {
                    $txt = "Baris ke-".$i." tidak di input ke sistem karena kode konstruksi tidak ditemukan";
                    $dtlist = [ 'id_user' => $sess_id, 'log_text' => $txt];
                    $this->data_model->saved('log_program', $dtlist);
                }
              }  
            } //end for perulangan by excel
            $this->session->set_flashdata('announce', 'Proses Inspect Finish berhasil di simpan');
            redirect(base_url('proses-produksi'));
        } else {
            $this->session->set_flashdata('gagal', 'Format file yang anda masukan salah');
            redirect(base_url('proses-produksi'));
        }
    } //end1

    public function import_newprosfol(){
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
          $sheet->setCellValue('A1', "Kode Roll"); // Set kolom A1 
          $sheet->setCellValue('B1', "Ukuran"); // Set kolom A1 
          $sheet->setCellValue('C1', "Folding"); // Set kolom A1
          $sheet->setCellValue('D1', "Tanggal Folding"); // Set kolom A1
          $sheet->setCellValue('E1', "Operator Folding"); // Set kolom A1
          $sheet->setCellValue('H1', "Note :"); // Set kolom A1
          $sheet->getColumnDimension('A')->setAutoSize(true);
          $sheet->getColumnDimension('B')->setAutoSize(true);
          $sheet->getColumnDimension('C')->setAutoSize(true);
          $sheet->getColumnDimension('D')->setAutoSize(true);
          $sheet->getColumnDimension('E')->setAutoSize(true);
          $sheet->getColumnDimension('H')->setAutoSize(true);
          // Apply style header yang telah kita buat tadi ke masing-masing kolom header
          $sheet->getStyle('A1')->applyFromArray($style_col);
          $sheet->getStyle('B1')->applyFromArray($style_col);
          $sheet->getStyle('C1')->applyFromArray($style_col);
          $sheet->getStyle('D1')->applyFromArray($style_col);
          $sheet->getStyle('E1')->applyFromArray($style_col);
          $sheet->getStyle('H1')->applyFromArray($style_col);
          $sheet->setCellValue('H2', "- Gunakan format tanggal DD-MM-YYYY (Ex : 01-12-2023)");
          $sheet->setCellValue('H3', "- Kolom Folding di isi dengan (Finish / Grey)");
          $sheet->setCellValue('H4', "- Satuan ukuran di tentukan dari kolom folding");
          $sheet->setCellValue('H5', "- jika di isi dengan Folding Finish maka satuan akan di tetapkan sebagai Yard");
          $sheet->setCellValue('H6', "- jika di isi dengan Folding Grey maka satuan akan di tetapkan sebagai Meter");
          
          $writer = new Xlsx($spreadsheet);
          $filename = 'Format Folding';
    
          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
          header('Cache-Control: max-age=0');
          $writer->save('php://output'); // download file
    } //end

    function new_import_fol(){
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
            $data_benar = 0; $data_salah = 0;
            for ($i=1; $i <count($sheetData) ; $i++) {
                $kode_roll = $sheetData[$i][0];
                $ukuran = floatval($sheetData[$i][1]);
                $folding = $sheetData[$i][2];
                $tgl_fol = $sheetData[$i][3];
                if($tgl_fol==""){ $tgl=$tgl_now; } else {
                    $ex = explode('-', $tgl_fol);
                    $tgl = "".$ex[2]."-".$ex[1]."-".$ex[0]."";
                }
                if($folding=="Grey" OR $folding=="grey"){
                    $cek_kdrol = $this->data_model->get_byid('new_tb_pkg_list', ['no_roll'=>$kode_roll]);
                    if($cek_kdrol->num_rows()==1){
                        $ukuran_sebelum = $cek_kdrol->row("ukuran_now");
                        if($ukuran_sebelum >= $ukuran){
                            $data_benar+=1;
                            $kode_produksi = $cek_kdrol->row("kode_produksi");
                            //proses folding grey
                            $cek_kdrol_fol = $this->data_model->get_byid('new_tb_pkg_fol', ['no_roll'=>$kode_roll]);
                            if($cek_kdrol_fol->num_rows()==0){
                                $ukuran_yard = $ukuran / 0.9144;
                                $dtlist_fol = [
                                    'kode_produksi' => $kode_produksi,
                                    'asal' => 'list',
                                    'id_asal' => 0,
                                    'no_roll' => $kode_roll,
                                    'tgl' => $tgl,
                                    'ukuran' => round($ukuran,2),
                                    'operator' => 'null',
                                    'st_folding' => 'Grey',
                                    'ukuran_now' => round($ukuran,2),
                                    'ukuran_yard' => round($ukuran_yard,2),
                                    'ukuran_now_yard' => round($ukuran_yard,2),
                                    'id_effected_row' => 0
                                ];
                                $this->data_model->saved('new_tb_pkg_fol', $dtlist_fol);
                            } else {
                                $ukuran_yard = $ukuran / 0.9144;
                                $dtlist_fol = [
                                    'tgl' => $tgl, 
                                    'ukuran' => round($ukuran,2),
                                    'ukuran_now' => round($ukuran,2),
                                    'ukuran_yard' => round($ukuran_yard,2),
                                    'ukuran_now_yard' => round($ukuran_yard,2),
                                ];
                                $this->data_model->updatedata('no_roll',$kode_roll,'new_tb_pkg_fol',$dtlist_fol);
                            }
                            //end proses folding grey
                            //cek proses produksi
                            $where = ['kode_produksi'=>$kode_produksi, 'tgl'=>$tgl, 'proses_name'=>'FG', 'lokasi_produksi'=>$loc];
                            $cek_pros = $this->data_model->get_byid('tb_proses_produksi', $where);
                            if($cek_pros->num_rows()==0){
                                $pros_list = [
                                    'kode_produksi' => $kode_produksi,
                                    'tgl' => $tgl,
                                    'jumlah_awal' => round($ukuran,2),
                                    'satuan' => 'Meter',
                                    'proses_name' => 'FG',
                                    'pemroses' => $sess_id,
                                    'jumlah_akhir' => round($ukuran,2),
                                    'ch_to' => 0,
                                    'lokasi_produksi' => $loc,
                                    'jumlah_awal_yard' => round($ukuran_yard,2),
                                    'jumlah_akhir_yard' => round($ukuran_yard,2)
                                ];
                                $this->data_model->saved('tb_proses_produksi',$pros_list);
                            } else {
                                $id_proses = $cek_pros->row("id_proses");
                                $_ukuran = $cek_pros->row("jumlah_awal"); 
                                $_ukuran2 = $cek_pros->row("jumlah_akhir"); 
                                $_up_ukuran = $_ukuran + $ukuran;
                                $_up_ukuran2 = $_ukuran2 + $ukuran;
                                $_y_up_ukuran = $_up_ukuran / 0.9144;
                                $_y_up_ukuran2 = $_up_ukuran2 / 0.9144;
                                $uplist = [
                                    'jumlah_awal' => $_up_ukuran,
                                    'jumlah_akhir' => $_up_ukuran2,
                                    'jumlah_awal_yard' => $_y_up_ukuran,
                                    'jumlah_akhir_yard' => $_y_up_ukuran2
                                ];
                                $this->data_model->updatedata('id_proses',$id_proses,'tb_proses_produksi',$uplist);
                            }
                            //end cek proses produksi
                            //cek produksi harian 
                            $kode_konstruksi = $this->data_model->get_byid('tb_produksi',['kode_produksi'=>$kode_produksi])->row("kode_konstruksi");
                            $cek_hr = $this->data_model->get_byid('report_produksi_harian',['kode_konstruksi'=>$kode_konstruksi,'lokasi_produksi'=>$loc,'waktu'=>$tgl]);
                            if($cek_hr->num_rows()==0){
                                $rptd_list = [
                                    'kode_konstruksi' => $kode_konstruksi,
                                    'ins_grey' => 0,
                                    'ins_finish' => 0,
                                    'fol_grey' => round($ukuran,2),
                                    'fol_finish' => 0,
                                    'lokasi_produksi' => $loc,
                                    'waktu' => $tgl,
                                    'terjual' => 0,
                                    'bs' => 0,
                                    'ins_grey_yard' => 0,
                                    'ins_finish_yard' => 0,
                                    'fol_grey_yard' => round($ukuran_yard,2),
                                    'fol_finish_yard' => 0,
                                    'terjual_yard' => 0,
                                    'bs_yard' =>0,
                                    'crt' => 0,
                                    'crt_yard' => 0
                                ];
                                $this->data_model->saved('report_produksi_harian',$rptd_list);
                            } else {
                                $id_rptd = $cek_hr->row("id_rptd");
                                $fol_grey = $cek_hr->row("fol_grey");
                                $new_fol_grey = $fol_grey + $ukuran;
                                $new_fol_grey_yard = $new_fol_grey / 0.9144;
                                $uprptd = [
                                    'fol_grey' => round($new_fol_grey,2),
                                    'fol_grey_yard' => round($new_fol_grey_yard,2)
                                ];
                                $this->data_model->updatedata('id_rptd',$id_rptd,'report_produksi_harian',$uprptd);
                            }
                            //end cek produksi harian
                            //proses data stok
                            $cek_st = $this->data_model->get_byid('report_stok',['kode_konstruksi'=>$kode_konstruksi,'departement'=>$loc]);
                            if($cek_st->num_rows() == 0){
                                $stok_list = [
                                    'kode_konstruksi' => $kode_konstruksi,
                                    'stok_ins' => 0,
                                    'stok_ins_finish' => 0,
                                    'stok_fol' => round($ukuran,2),
                                    'stok_fol_finish' => 0,
                                    'terjual' => 0,
                                    'bs' => 0,
                                    'retur' => 0,
                                    'departement' => $loc,
                                    'stok_ins_yard' => 0,
                                    'stok_ins_finish_yard' => 0,
                                    'stok_fol_yard' => round($ukuran_yard,2),
                                    'stok_fol_finish_yard' => 0,
                                    'terjual_yard' => 0,
                                    'bs_yard' => 0,
                                    'retur_yard' => 0,
                                    'crt' => 0,
                                    'crt_yard' => 0
                                ];
                                $this->data_model->saved('report_stok', $stok_list);
                            } else {
                                $id_stok = $cek_st->row("id_stok");
                                $stok_ins = $cek_st->row("stok_ins");
                                $stok_fol = $cek_st->row("stok_fol");
                                $stok_ins_new = $stok_ins - $ukuran;
                                $stok_fol_new = $stok_fol + $ukuran;
                                $stok_ins_new_y = $stok_ins_new / 0.9144;
                                $stok_fol_new_y = $stok_fol_new / 0.9144;
                                $upstok = [
                                    'stok_ins' => round($stok_ins_new,2),
                                    'stok_fol' => round($stok_fol_new,2),
                                    'stok_ins_yard' => round($stok_ins_new_y,2),
                                    'stok_fol_yard' => round($stok_fol_new_y,2)
                                ];
                                $this->data_model->updatedata('id_stok',$id_stok,'report_stok',$upstok);
                            }
                            //end proses data stok
                        } else {
                            $data_salah+=1;
                            //proses folding finish gagal karena ukuran folding lebih besar dari ukuran sebelumnya
                        }
                    } else {
                        $data_salah+=1;
                        //proses folding grey gagal karena kode roll tidak ditemukan
                    }
                    //end proses grey
                } elseif($folding=="Finish" OR $folding=="finish") {
                    $cek_kdrol = $this->data_model->get_byid('new_tb_pkg_ins', ['no_roll'=>$kode_roll]);
                    if($cek_kdrol->num_rows()==1){
                        $ukuran_sebelum = $cek_kdrol->row("ukuran_now_yard");
                        if($ukuran_sebelum >= $ukuran){
                            $data_benar+=1;
                            $kode_produksi = $cek_kdrol->row("kode_produksi");
                            //proses folding finish
                            $cek_kdrol_fol = $this->data_model->get_byid('new_tb_pkg_fol', ['no_roll'=>$kode_roll]);
                            if($cek_kdrol_fol->num_rows()==0){
                                $ukuran_meter = $ukuran * 0.9144;
                                $dtlist_fol = [
                                    'kode_produksi' => $kode_produksi,
                                    'asal' => 'ins',
                                    'id_asal' => 0,
                                    'no_roll' => $kode_roll,
                                    'tgl' => $tgl,
                                    'ukuran' => round($ukuran_meter,2),
                                    'operator' => 'null',
                                    'st_folding' => 'Finish',
                                    'ukuran_now' => round($ukuran_meter,2),
                                    'ukuran_yard' => round($ukuran,2),
                                    'ukuran_now_yard' => round($ukuran,2),
                                    'id_effected_row' => 0
                                ];
                                $this->data_model->saved('new_tb_pkg_fol', $dtlist_fol);
                            } else {
                                $ukuran_meter = $ukuran * 0.9144;
                                $dtlist_fol = [
                                    'tgl' => $tgl, 
                                    'ukuran' => round($ukuran_meter,2),
                                    'ukuran_now' => round($ukuran_meter,2),
                                    'ukuran_yard' => round($ukuran,2),
                                    'ukuran_now_yard' => round($ukuran,2),
                                ];
                                $this->data_model->updatedata('no_roll',$kode_roll,'new_tb_pkg_fol',$dtlist_fol);
                            }
                            //end proses folding finish
                            //cek proses produksi
                            $where = ['kode_produksi'=>$kode_produksi, 'tgl'=>$tgl, 'proses_name'=>'FF', 'lokasi_produksi'=>$loc];
                            $cek_pros = $this->data_model->get_byid('tb_proses_produksi', $where);
                            if($cek_pros->num_rows()==0){
                                $pros_list = [
                                    'kode_produksi' => $kode_produksi,
                                    'tgl' => $tgl,
                                    'jumlah_awal' => round($ukuran_meter,2),
                                    'satuan' => 'Yard',
                                    'proses_name' => 'FF',
                                    'pemroses' => $sess_id,
                                    'jumlah_akhir' => round($ukuran_meter,2),
                                    'ch_to' => 0,
                                    'lokasi_produksi' => $loc,
                                    'jumlah_awal_yard' => round($ukuran,2),
                                    'jumlah_akhir_yard' => round($ukuran,2)
                                ];
                                $this->data_model->saved('tb_proses_produksi',$pros_list);
                            } else {
                                $id_proses = $cek_pros->row("id_proses");
                                $_ukuran = $cek_pros->row("jumlah_awal_yard"); 
                                $_ukuran2 = $cek_pros->row("jumlah_akhir_yard"); 
                                $_up_ukuran = $_ukuran + $ukuran;
                                $_up_ukuran2 = $_ukuran2 + $ukuran;
                                $_m_up_ukuran = $_up_ukuran * 0.9144;
                                $_m_up_ukuran2 = $_up_ukuran2 * 0.9144;
                                $uplist = [
                                    'jumlah_awal' => $_m_up_ukuran,
                                    'jumlah_akhir' => $_m_up_ukuran2,
                                    'jumlah_awal_yard' => $_up_ukuran,
                                    'jumlah_akhir_yard' => $_up_ukuran2
                                ];
                                $this->data_model->updatedata('id_proses',$id_proses,'tb_proses_produksi',$uplist);
                            }
                            //end cek proses produksi
                            //cek produksi harian 
                            $kode_konstruksi = $this->data_model->get_byid('tb_produksi',['kode_produksi'=>$kode_produksi])->row("kode_konstruksi");
                            $cek_hr = $this->data_model->get_byid('report_produksi_harian',['kode_konstruksi'=>$kode_konstruksi,'lokasi_produksi'=>$loc,'waktu'=>$tgl]);
                            if($cek_hr->num_rows()==0){
                                $rptd_list = [
                                    'kode_konstruksi' => $kode_konstruksi,
                                    'ins_grey' => 0,
                                    'ins_finish' => 0,
                                    'fol_grey' => 0,
                                    'fol_finish' => round($ukuran,2),
                                    'lokasi_produksi' => $loc,
                                    'waktu' => $tgl,
                                    'terjual' => 0,
                                    'bs' => 0,
                                    'ins_grey_yard' => 0,
                                    'ins_finish_yard' => 0,
                                    'fol_grey_yard' => 0,
                                    'fol_finish_yard' => round($ukuran_yard,2),
                                    'terjual_yard' => 0,
                                    'bs_yard' =>0,
                                    'crt' => 0,
                                    'crt_yard' => 0
                                ];
                                $this->data_model->saved('report_produksi_harian',$rptd_list);
                            } else {
                                $id_rptd = $cek_hr->row("id_rptd");
                                $fol_finish = $cek_hr->row("fol_finish");
                                $new_fol_finish = $fol_finish + $ukuran;
                                $new_fol_finish_yard = $new_fol_finish * 0.9144;
                                $uprptd = [
                                    'fol_finish' => round($new_fol_finish,2),
                                    'fol_finish_yard' => round($new_fol_finish_yard,2)
                                ];
                                $this->data_model->updatedata('id_rptd',$id_rptd,'report_produksi_harian',$uprptd);
                            }
                            //end cek produksi harian
                            //proses data stok
                            $cek_st = $this->data_model->get_byid('report_stok',['kode_konstruksi'=>$kode_konstruksi,'departement'=>$loc]);
                            if($cek_st->num_rows() == 0){
                                $stok_list = [
                                    'kode_konstruksi' => $kode_konstruksi,
                                    'stok_ins' => 0,
                                    'stok_ins_finish' => 0,
                                    'stok_fol' => 0,
                                    'stok_fol_finish' => round($ukuran_meter,2),
                                    'terjual' => 0,
                                    'bs' => 0,
                                    'retur' => 0,
                                    'departement' => $loc,
                                    'stok_ins_yard' => 0,
                                    'stok_ins_finish_yard' => 0,
                                    'stok_fol_yard' => 0,
                                    'stok_fol_finish_yard' => round($ukuran,2),
                                    'terjual_yard' => 0,
                                    'bs_yard' => 0,
                                    'retur_yard' => 0,
                                    'crt' => 0,
                                    'crt_yard' => 0
                                ];
                                $this->data_model->saved('report_stok', $stok_list);
                            } else {
                                $id_stok = $cek_st->row("id_stok");
                                $stok_ins = $cek_st->row("stok_ins_finish");
                                $stok_fol = $cek_st->row("stok_fol_finish");
                                $stok_ins_new = $stok_ins - $ukuran;
                                $stok_fol_new = $stok_fol + $ukuran;
                                $stok_ins_new_y = $stok_ins_new * 0.9144;
                                $stok_fol_new_y = $stok_fol_new * 0.9144;
                                $upstok = [
                                    'stok_ins_finish' => round($stok_ins_new,2),
                                    'stok_fol_finish' => round($stok_fol_new,2),
                                    'stok_ins_finish_yard' => round($stok_ins_new_y,2),
                                    'stok_fol_finish_yard' => round($stok_fol_new_y,2)
                                ];
                                $this->data_model->updatedata('id_stok',$id_stok,'report_stok',$upstok);
                            }
                            //end proses data stok
                        } else {
                            $data_salah+=1;
                            //proses folding finish gagal karena ukuran folding lebih besar dari ukuran sebelumnya
                        }
                    } else {
                        $data_salah+=1;
                        //proses folding finish gagal karena kode roll tidak ditemukan
                    }
                } else {
                    $data_salah+=1;
                    //proses folding gagal karena folding tidak jelas finish/grey
                }
                
            } //end for perulangan by excel
            $this->session->set_flashdata('announce', 'Proses Folding berhasil di simpan');
            redirect(base_url('proses-produksi'));
        } else {
            $this->session->set_flashdata('gagal', 'Format file yang anda masukan salah');
            redirect(base_url('proses-produksi'));
        }
    } //end

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
                $temp_file = [
                    'kode_roll' => $sheetData[$i][0],
                    'ukuran' => $sheetData[$i][1],
                    'folding' => $sheetData[$i][2],
                    'tgl' => $formatTgl,
                    'operator' => $sheetData[$i][4],
                    'kons' => $sheetData[$i][5],
                    'joinss' => $sheetData[$i][6]
                ];
                $this->data_model->saved('temp_upload_fol', $temp_file);
                if($_join=="") { $joinss="false"; } else { $joinss="true"; }
                //echo "$kode_roll - $ukuran - $folding - $tgl_fol - $operator <br>";
                $kdkons = "null";
                if($kode_roll=="" AND $ukuran=="" AND $folding=="" AND $tgl_fol=="" AND $operator==""){} else {
                $cek_ig = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kode_roll]);
                if($cek_ig->num_rows()==1){
                    $kdkons = $cek_ig->row("konstruksi");
                    if($cek_ig->row("dep")==$loc AND $cek_ig->row("loc_now")==$loc){
                        $all_dep = $loc;
                    } else {
                        $all_dep = $cek_ig->row("dep")."-IN-".$cek_ig->row("loc_now");
                    }
                    $stok_asli = "true";
                } else {
                    $cek_if = $this->data_model->get_byid('data_if', ['kode_roll'=>$kode_roll]);
                    if($cek_if->num_rows()==1){
                        $asal = $cek_if->row("asal");
                        $kdkons2 = $this->data_model->get_byid('data_ig', ['kode_roll'=>$asal]);
                        $kdkons = $kdkons2->row("konstruksi");
                        if($kdkons2->row("dep")==$loc AND $kdkons2->row("loc_now")==$loc){
                            $all_dep = $loc;
                        } else {
                            $all_dep = $kdkons2->row("dep")."-IN-".$kdkons2->row("loc_now");
                        }
                        $stok_asli = "true";
                    } else {
                        
                        $kdkons = $_kdkons;
                        $stok_asli = "false";
                    }
                }
                if($kdkons=="null"){
                    
                } else {
                    $cek_kode_difolding = $this->data_model->get_byid('data_fol',['kode_roll'=>$kode_roll]);
                    if($cek_kode_difolding->num_rows()==0){
                    $ox = explode(',',$ukuran);
                    if(count($ox) > 1){
                        $ukuran = 0;
                        for ($iss=0; $iss < count($ox); $iss++) { 
                            if($stok_asli=="false"){
                                $celk_iflama = $this->data_model->get_byid('data_if_lama',['kode_roll'=>$kode_roll]);
                                if($celk_iflama->num_rows()==1){
                                    $new_panjang_now = floatval($celk_iflama->row("panjang_now")) - floatval($ox[$iss]);
                                    $this->data_model->updatedata('kode_roll',$kode_roll,'data_if_lama',['panjang_now'=>round($new_panjang_now,2)]);
                                }
                            }
                            if($iss==0){
                                if($stok_asli=="true"){
                                $dtfol = [
                                    'kode_roll' => $kode_roll,
                                    'konstruksi' => $kdkons,
                                    'ukuran' => $ox[$iss],
                                    'jns_fold' => $folding,
                                    'tgl' => $formatTgl,
                                    'operator' => $operator,
                                    'loc' => $loc,
                                    'posisi' => $loc,
                                    'join' => $joinss
                                ];
                                $this->data_model->saved('data_fol',$dtfol);
                                } else {
                                    $cek_koderollagain = $this->data_model->get_byid('data_fol_lama',['kode_roll'=>$kode_roll]);
                                    if($cek_koderollagain->num_rows()==0){
                                    $dtfol = [
                                        'kode_roll' => $kode_roll,
                                        'ukuran_asli' => $ox[$iss],
                                        'ukuran_now' => $ox[$iss],
                                        'folding' => $folding,
                                        'lokasi' => $loc,
                                        'tanggal' => $formatTgl,
                                        'operator' => $operator,
                                        'konstruksi' => $kdkons,
                                        'join' => $joinss
                                    ];
                                    $this->data_model->saved('data_fol_lama',$dtfol); }
                                }
                            } else {
                                $new_kode = $kode_roll."-".$iss;
                                if($stok_asli=="true"){
                                $dtfol = [
                                    'kode_roll' => $new_kode,
                                    'konstruksi' => $kdkons,
                                    'ukuran' => $ox[$iss],
                                    'jns_fold' => $folding,
                                    'tgl' => $formatTgl,
                                    'operator' => $operator,
                                    'loc' => $loc,
                                    'posisi' => $loc,
                                    'join' => $joinss
                                ];
                                $this->data_model->saved('data_fol',$dtfol);
                                } else {
                                    $cek_koderollagain = $this->data_model->get_byid('data_fol_lama',['kode_roll'=>$new_kode]);
                                    if($cek_koderollagain->num_rows()==0){
                                    $dtfol = [
                                        'kode_roll' => $new_kode,
                                        'ukuran_asli' => $ox[$iss],
                                        'ukuran_now' => $ox[$iss],
                                        'folding' => $folding,
                                        'lokasi' => $loc,
                                        'tanggal' => $formatTgl,
                                        'operator' => $operator,
                                        'konstruksi' => $kdkons,
                                        'join' => $joinss
                                    ];
                                    $this->data_model->saved('data_fol_lama',$dtfol); }
                                }
                            }
                            $_pecahan = floatval($ox[$iss]);
                            $_pecahan2 = round($_pecahan,2);
                            $ukuran += $_pecahan2;
                        }
                    } else {
                        if($stok_asli=="true"){
                        $dtfol = [
                            'kode_roll' => $kode_roll,
                            'konstruksi' => $kdkons,
                            'ukuran' => $ukuran,
                            'jns_fold' => $folding,
                            'tgl' => $formatTgl,
                            'operator' => $operator,
                            'loc' => $loc,
                            'posisi' => $loc,
                            'join' => $joinss
                        ];
                        $this->data_model->saved('data_fol',$dtfol);
                        } else {
                            $cek_koderollagain = $this->data_model->get_byid('data_fol_lama',['kode_roll'=>$kode_roll]);
                            if($cek_koderollagain->num_rows()==0){
                            $dtfol = [
                                'kode_roll' => $kode_roll,
                                'ukuran_asli' => $ukuran,
                                'ukuran_now' => $ukuran,
                                'folding' => $folding,
                                'lokasi' => $loc,
                                'tanggal' => $formatTgl,
                                'operator' => $operator,
                                'konstruksi' => $kdkons,
                                'join' => $joinss
                            ];
                            $this->data_model->saved('data_fol_lama',$dtfol); }
                            
                            $celk_iflama = $this->data_model->get_byid('data_if_lama',['kode_roll'=>$kode_roll]);
                            if($celk_iflama->num_rows()==1){
                                $new_panjang_now = floatval($celk_iflama->row("panjang_now")) - floatval($ukuran);
                                $this->data_model->updatedata('kode_roll',$kode_roll,'data_if_lama',['panjang_now'=>round($new_panjang_now,2)]);
                            }
                        }
                       
                    }
                    $dt_kons = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kdkons]);
                    if($folding=="Finish"){
                        $ukuran_meter = floatval($ukuran) * 0.9144;
                        $ukuran = round($ukuran,2);
                        $new_if = floatval($dt_kons->row("stok_if")) - floatval($ukuran);
                        $new_ff = floatval($dt_kons->row("stok_ff")) + floatval($ukuran);
                        if($stok_asli=="true"){
                            $this->data_model->updatedata('kode_konstruksi',$kdkons,'tb_konstruksi',[ 'stok_if' => round($new_if,2), 'stok_ff' => round($new_ff,2)]);
                        } else {
                            $this->data_model->updatedata('kode_konstruksi',$kdkons,'tb_konstruksi',[ 'stok_ff' => round($new_ff,2)]);
                        }
                        //folding finish
                        $cek_prod = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kdkons, 'tgl'=>$formatTgl, 'dep'=>$loc]);
                        if($cek_prod->num_rows()==0){
                            $dtprod = [
                                'kode_konstruksi' => $kdkons,
                                'tgl' => $formatTgl,
                                'dep' => $loc,
                                'prod_ig' => 0,
                                'prod_fg' => 0,
                                'prod_if' => 0,
                                'prod_ff' => round($ukuran,2),
                                'prod_bs1' => 0,
                                'prod_bp1' => 0,
                                'prod_bs2' => 0,
                                'prod_bp2' => 0,
                                'crt' => 0
                            ];
                            $this->data_model->saved('data_produksi',$dtprod);
                        } else {
                            $idprod = $cek_prod->row("id_produksi");
                            $new_ff = floatval($cek_prod->row("prod_ff")) + floatval($ukuran);
                            $this->data_model->updatedata('id_produksi',$idprod,'data_produksi',['prod_ff'=>round($new_ff,2)]);
                        }
                        $cek_harian = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$formatTgl,'dep'=>$loc]);
                        if($cek_harian->num_rows()==0){
                            $dtprod_hr = [
                                'tgl' => $formatTgl,
                                'dep' => $loc,
                                'prod_ig' => 0,
                                'prod_fg' => 0,
                                'prod_if' => 0,
                                'prod_ff' => round($ukuran,2),
                                'prod_bs1' => 0,
                                'prod_bp1' => 0,
                                'prod_bs2' => 0,
                                'prod_bp2' => 0,
                                'crt' => 0
                            ];
                            $this->data_model->saved('data_produksi_harian',$dtprod_hr);
                        } else {
                            $idprod = $cek_harian->row("id_prod_hr");
                            $new_ff = floatval($cek_harian->row("prod_ff")) + floatval($ukuran);
                            $this->data_model->updatedata('id_prod_hr',$idprod,'data_produksi_harian',['prod_ff'=>round($new_ff,2)]);
                        }
                        //if($cek_ig->row("dep")==$loc AND $cek_ig->row("loc_now")==$loc){
                        if($stok_asli=="true"){
                            $cek_stok = $this->data_model->get_byid('data_stok', ['dep'=>$all_dep,'kode_konstruksi'=>$kdkons]);
                            if($cek_stok->num_rows()==0){
                                $dtstok = [
                                    'dep' => $loc,
                                    'kode_konstruksi' => $kdkons,
                                    'prod_ig' => 0,
                                    'prod_fg' => 0,
                                    'prod_if' => 0,
                                    'prod_ff' => round($ukuran,2),
                                    'prod_bs1' => 0,
                                    'prod_bp1' => 0,
                                    'prod_bs2' => 0,
                                    'prod_bp2' => 0,
                                    'crt' => 0
                                ];
                                $this->data_model->saved('data_stok',$dtstok);
                            } else {
                                $idprod = $cek_stok->row("idstok");
                                $ukuran = round($ukuran,2);
                                $new_if = floatval($cek_stok->row("prod_if")) - floatval($ukuran);
                                $new_ff = floatval($cek_stok->row("prod_ff")) + floatval($ukuran);
                                $this->data_model->updatedata('idstok',$idprod,'data_stok',['prod_if'=>round($new_if,2),'prod_ff'=>round($new_ff,2)]);
                            }
                        } else {
                            $cek_stok = $this->data_model->get_byid('data_stok', ['dep'=>'SL','kode_konstruksi'=>$kdkons]);
                            if($cek_stok->num_rows()==0){
                                $dtstok = [
                                    'dep' => 'SL',
                                    'kode_konstruksi' => $kdkons,
                                    'prod_ig' => 0,
                                    'prod_fg' => 0,
                                    'prod_if' => 0,
                                    'prod_ff' => round($ukuran,2),
                                    'prod_bs1' => 0,
                                    'prod_bp1' => 0,
                                    'prod_bs2' => 0,
                                    'prod_bp2' => 0,
                                    'crt' => 0
                                ];
                                $this->data_model->saved('data_stok',$dtstok);
                            } else {
                                $idprod = $cek_stok->row("idstok");
                                $ukuran = round($ukuran,2);
                                $new_if = floatval($cek_stok->row("prod_if")) - floatval($ukuran);
                                $new_ff = floatval($cek_stok->row("prod_ff")) + floatval($ukuran);
                                $this->data_model->updatedata('idstok',$idprod,'data_stok',['prod_if'=>round($new_if,2),'prod_ff'=>round($new_ff,2)]);
                            }
                        }
                        
                        //end folding finish
                    } else {
                        $ukuran_yard = floatval($ukuran) / 0.9144;
                        $ukuran = round($ukuran,2);
                        $new_ig = floatval($dt_kons->row("stok_ig")) - floatval($ukuran);
                        $new_fg = floatval($dt_kons->row("stok_fg")) + floatval($ukuran);
                        if($stok_asli=="true"){
                            $this->data_model->updatedata('kode_konstruksi',$kdkons,'tb_konstruksi',[ 'stok_ig' => round($new_ig,2), 'stok_fg' => round($new_fg,2)]);
                        } else {
                            $this->data_model->updatedata('kode_konstruksi',$kdkons,'tb_konstruksi',[ 'stok_fg' => round($new_fg,2)]);
                        }
                        //folding grey
                        $cek_prod = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kdkons, 'tgl'=>$formatTgl, 'dep'=>$loc]);
                        if($cek_prod->num_rows()==0){
                            $dtprod = [
                                'kode_konstruksi' => $kdkons,
                                'tgl' => $formatTgl,
                                'dep' => $loc,
                                'prod_ig' => 0,
                                'prod_fg' => round($ukuran,2),
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
                            $idprod = $cek_prod->row("id_produksi");
                            $new_fg = floatval($cek_prod->row("prod_fg")) + floatval($ukuran);
                            $this->data_model->updatedata('id_produksi',$idprod,'data_produksi',['prod_fg'=>round($new_fg,2)]);
                        }
                        $cek_harian = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$formatTgl,'dep'=>$loc]);
                        if($cek_harian->num_rows()==0){
                            $dtprod_hr = [
                                'tgl' => $formatTgl,
                                'dep' => $loc,
                                'prod_ig' => 0,
                                'prod_fg' => round($ukuran,2),
                                'prod_if' => 0,
                                'prod_ff' => 0,
                                'prod_bs1' => 0,
                                'prod_bp1' => 0,
                                'prod_bs2' => 0,
                                'prod_bp2' => 0,
                                'crt' => 0
                            ];
                            $this->data_model->saved('data_produksi_harian',$dtprod_hr);
                        } else {
                            $idprod = $cek_harian->row("id_prod_hr");
                            $new_fg = floatval($cek_harian->row("prod_fg")) + floatval($ukuran);
                            $this->data_model->updatedata('id_prod_hr',$idprod,'data_produksi_harian',['prod_fg'=>round($new_fg,2)]);
                        }
                        //if($cek_ig->row("dep")==$loc AND $cek_ig->row("loc_now")==$loc){
                        if($stok_asli=="true"){
                            $cek_stok = $this->data_model->get_byid('data_stok', ['dep'=>$all_dep,'kode_konstruksi'=>$kdkons]);
                            if($cek_stok->num_rows()==0){
                                $dtstok = [
                                    'dep' => $loc,
                                    'kode_konstruksi' => $kdkons,
                                    'prod_ig' => 0,
                                    'prod_fg' => round($ukuran,2),
                                    'prod_if' => 0,
                                    'prod_ff' => 0,
                                    'prod_bs1' => 0,
                                    'prod_bp1' => 0,
                                    'prod_bs2' => 0,
                                    'prod_bp2' => 0,
                                    'crt' => 0
                                ];
                                $this->data_model->saved('data_stok',$dtstok);
                            } else {
                                $idprod = $cek_stok->row("idstok");
                                $new_ig = floatval($cek_stok->row("prod_ig")) - floatval($ukuran);
                                $new_fg = floatval($cek_stok->row("prod_fg")) + floatval($ukuran);
                                $this->data_model->updatedata('idstok',$idprod,'data_stok',['prod_ig'=>round($new_ig,2),'prod_fg'=>round($new_fg,2)]);
                            }
                        } else {
                            $cek_stok = $this->data_model->get_byid('data_stok', ['dep'=>'SL','kode_konstruksi'=>$kdkons]);
                            if($cek_stok->num_rows()==0){
                                $dtstok = [
                                    'dep' => 'SL',
                                    'kode_konstruksi' => $kdkons,
                                    'prod_ig' => 0,
                                    'prod_fg' => round($ukuran,2),
                                    'prod_if' => 0,
                                    'prod_ff' => 0,
                                    'prod_bs1' => 0,
                                    'prod_bp1' => 0,
                                    'prod_bs2' => 0,
                                    'prod_bp2' => 0,
                                    'crt' => 0
                                ];
                                $this->data_model->saved('data_stok',$dtstok);
                            } else {
                                $idprod = $cek_stok->row("idstok");
                                $new_fg = floatval($cek_stok->row("prod_fg")) + floatval($ukuran);
                                $this->data_model->updatedata('idstok',$idprod,'data_stok',['prod_fg'=>round($new_fg,2)]);
                            }
                        }
                        
                        //end folding grey
                    }

                   }
                }
              }
            } //end for perulangan by excel
            $this->session->set_flashdata('announce', 'Proses Folding berhasil di simpan');
            redirect(base_url('proses-produksi'));
        } else {
            $this->session->set_flashdata('gagal', 'Format file yang anda masukan salah');
            redirect(base_url('proses-produksi'));
        }
    } //end
}
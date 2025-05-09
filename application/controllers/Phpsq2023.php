<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    class Phpsq2023 extends CI_Controller {
        public function __construct(){
        parent::__construct();
        $this->load->model('data_model');
        $this->load->model('data_model2');
        date_default_timezone_set("Asia/Jakarta");
    }
    
    public function index(){
        //$this->load->view('spreadsheet');
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
            echo "<table border='1'><thead><tr><td>No</td><td>Kode Roll</td><td>Ukuran</td><td>Folding</td><td>Tanggal</td><td>Operator</td><td>Konstruksi</td><td>Join</td><td>Keterangan</td><td>Kode Baru</td></tr></thead><tbody>";
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
                echo "<tr>";
                echo "<td>".$i."</td>";
                echo "<td>".$kode_roll."</td>";
                echo "<td>".$ukuran."</td>";
                echo "<td>".$folding."</td>";
                echo "<td>".$formatTgl."</td>";
                echo "<td>".$operator."</td>";
                echo "<td>".$_kdkons."</td>";
                echo "<td>".$_join."</td>";
                $cekKodeRoll = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll])->num_rows();
                if($cekKodeRoll > 0){
                    echo "<td style='color:red;'>Kode roll sudah pernah di gunakan 1</td>";
                    echo "<td>".$kode_roll."G</td>";
                    $kode_roll_input = "".$kode_roll."G";
                } else {
                    $cekKodeRoll2 = $this->data_model->get_byid('new_tb_isi', ['kode'=>$kode_roll])->num_rows();
                    if($cekKodeRoll2 > 0){
                        echo "<td style='color:red;'>Kode roll sudah pernah di gunakan 2</td>";
                        echo "<td>".$kode_roll."H</td>";
                        $kode_roll_input = "".$kode_roll."H";
                    } else {
                        echo "<td>oke</td>";
                        echo "<td></td>";
                        $kode_roll_input = "".$kode_roll."";
                    }
                }
                echo "</tr>";
                $this->data_model->saved('temp_upload_fol', $temp_file);
                $dtfol = [
                    'kode_roll' => $kode_roll_input,
                    'konstruksi' => $_kdkons,
                    'ukuran' => $ukuran,
                    'jns_fold' => 'Grey',
                    'tgl' => '2023-08-10',
                    'operator' => 'Rizik',
                    'loc' => 'Samatex',
                    'posisi' => 'Samatex',
                    'joinss' => 'false',
                    'joindfrom' => 'null'
                ];
                $this->data_model->saved('data_fol', $dtfol);

            } //end for perulangan by excel
            echo "</tbody>";
            $this->session->set_flashdata('announce', 'Proses Folding berhasil di simpan');
            redirect(base_url('proses-produksi'));
        } else {
            $this->session->set_flashdata('gagal', 'Format file yang anda masukan salah');
            redirect(base_url('proses-produksi'));
        }
    } //end

    function exportProduksi(){
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
        $produksi = $this->input->post('state');
        $opt = $this->input->post('opt');
        $tgl = $this->input->post('datesr');
        $ex = explode(' - ', $tgl);
        $ex1 = explode('/', $ex[0]);
        $formatTgl1 = $ex1[2]."-".$ex1[0]."-".$ex1[1];
        $ex2 = explode('/', $ex[1]);
        $formatTgl2 = $ex2[2]."-".$ex2[0]."-".$ex2[1];
        if($produksi=="RJS_IG" OR $produksi=="SMT_IG"){
                $sheet->setCellValue('A1', "Kode Roll"); // Set kolom A1 
                $sheet->setCellValue('B1', "Konstruksi"); // Set kolom A1 
                $sheet->setCellValue('C1', "No Mesin"); // Set kolom A1 
                $sheet->setCellValue('D1', "No Beam"); // Set kolom A1 
                $sheet->setCellValue('E1', "OKA"); // Set kolom A1 
                $sheet->setCellValue('F1', "Ukuran ORI"); // Set kolom A1 
                $sheet->setCellValue('G1', "Ukuran BS"); // Set kolom A1 
                $sheet->setCellValue('H1', "Ukuran BP"); // Set kolom A1 
                $sheet->setCellValue('I1', "Tanggal"); // Set kolom A1 
                $sheet->setCellValue('J1', "Nama Operator"); // Set kolom A1 
                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getColumnDimension('E')->setAutoSize(true);
                $sheet->getColumnDimension('F')->setAutoSize(true);
                $sheet->getColumnDimension('G')->setAutoSize(true);
                $sheet->getColumnDimension('H')->setAutoSize(true);
                $sheet->getColumnDimension('I')->setAutoSize(true);
                $sheet->getColumnDimension('J')->setAutoSize(true);
                $sheet->getStyle('A1')->applyFromArray($style_col);
                $sheet->getStyle('B1')->applyFromArray($style_col);
                $sheet->getStyle('C1')->applyFromArray($style_col);
                $sheet->getStyle('D1')->applyFromArray($style_col);
                $sheet->getStyle('E1')->applyFromArray($style_col);
                $sheet->getStyle('F1')->applyFromArray($style_col);
                $sheet->getStyle('G1')->applyFromArray($style_col);
                $sheet->getStyle('H1')->applyFromArray($style_col);
                $sheet->getStyle('I1')->applyFromArray($style_col);
                $sheet->getStyle('J1')->applyFromArray($style_col);
        }
        $cell=2;
        foreach($opt as $opt){
            //echo "Operator ".$opt."-".$produksi."-".$tgl."<br>";
            if($produksi == "RJS_IG"){
                $query = "SELECT * FROM data_ig WHERE tanggal BETWEEN '$formatTgl1' AND '$formatTgl2' AND operator='$opt' AND dep='RJS'";
                //echo "-- Data Inspect Grey Operator <strong>".$opt."</strong>";
                $dataig = $this->db->query($query);
                if($dataig->num_rows() > 0){
                    foreach($dataig->result() as $val):
                        $sheet->setCellValue('A'.$cell.'', $val->kode_roll); // Set kolom A1 
                        $sheet->setCellValue('B'.$cell.'', $val->konstruksi); // Set kolom A1 
                        $sheet->setCellValue('C'.$cell.'', $val->no_mesin); // Set kolom A1 
                        $sheet->setCellValue('D'.$cell.'', $val->no_beam); // Set kolom A1 
                        $sheet->setCellValue('E'.$cell.'', $val->oka); // Set kolom A1 
                        $sheet->setCellValue('F'.$cell.'', $val->ukuran_ori); // Set kolom A1 
                        $sheet->setCellValue('G'.$cell.'', $val->ukuran_bs); // Set kolom A1 
                        $sheet->setCellValue('H'.$cell.'', $val->ukuran_bp); // Set kolom A1 
                        $sheet->setCellValue('I'.$cell.'', $val->tanggal); // Set kolom A1 
                        $sheet->setCellValue('J'.$cell.'', $val->operator); // Set kolom A1 
                        $sheet->getStyle('A'.$cell.'')->applyFromArray($style_row);
                        $sheet->getStyle('B'.$cell.'')->applyFromArray($style_row);
                        $sheet->getStyle('C'.$cell.'')->applyFromArray($style_row);
                        $sheet->getStyle('D'.$cell.'')->applyFromArray($style_row);
                        $sheet->getStyle('E'.$cell.'')->applyFromArray($style_row);
                        $sheet->getStyle('F'.$cell.'')->applyFromArray($style_row);
                        $sheet->getStyle('G'.$cell.'')->applyFromArray($style_row);
                        $sheet->getStyle('H'.$cell.'')->applyFromArray($style_row);
                        $sheet->getStyle('I'.$cell.'')->applyFromArray($style_row);
                        $sheet->getStyle('J'.$cell.'')->applyFromArray($style_row);
                        $cell++;
                    endforeach;
                } else {
                    $sheet->setCellValue('A'.$cell.'', 'NO DATA'); // Set kolom A1 
                    $sheet->setCellValue('J'.$cell.'', $val->operator); // Set kolom A1 
                    $sheet->getStyle('A'.$cell.'')->applyFromArray($style_row);
                    $sheet->getStyle('J'.$cell.'')->applyFromArray($style_row);
                    $cell++;
                }
            }
            if($produksi == "SMT_IG"){
                $query = "SELECT * FROM data_ig WHERE tanggal BETWEEN '$formatTgl1' AND '$formatTgl2' AND operator='$opt' AND dep='Samatex'";
                $dataig = $this->db->query($query);
                if($dataig->num_rows() > 0){
                foreach($dataig->result() as $val):
                    $sheet->setCellValue('A'.$cell.'', $val->kode_roll); // Set kolom A1 
                    $sheet->setCellValue('B'.$cell.'', $val->konstruksi); // Set kolom A1 
                    $sheet->setCellValue('C'.$cell.'', $val->no_mesin); // Set kolom A1 
                    $sheet->setCellValue('D'.$cell.'', $val->no_beam); // Set kolom A1 
                    $sheet->setCellValue('E'.$cell.'', $val->oka); // Set kolom A1 
                    $sheet->setCellValue('F'.$cell.'', $val->ukuran_ori); // Set kolom A1 
                    $sheet->setCellValue('G'.$cell.'', $val->ukuran_bs); // Set kolom A1 
                    $sheet->setCellValue('H'.$cell.'', $val->ukuran_bp); // Set kolom A1 
                    $sheet->setCellValue('I'.$cell.'', $val->tanggal); // Set kolom A1 
                    $sheet->setCellValue('J'.$cell.'', $val->operator); // Set kolom A1 
                    $sheet->getStyle('A'.$cell.'')->applyFromArray($style_row);
                    $sheet->getStyle('B'.$cell.'')->applyFromArray($style_row);
                    $sheet->getStyle('C'.$cell.'')->applyFromArray($style_row);
                    $sheet->getStyle('D'.$cell.'')->applyFromArray($style_row);
                    $sheet->getStyle('E'.$cell.'')->applyFromArray($style_row);
                    $sheet->getStyle('F'.$cell.'')->applyFromArray($style_row);
                    $sheet->getStyle('G'.$cell.'')->applyFromArray($style_row);
                    $sheet->getStyle('H'.$cell.'')->applyFromArray($style_row);
                    $sheet->getStyle('I'.$cell.'')->applyFromArray($style_row);
                    $sheet->getStyle('J'.$cell.'')->applyFromArray($style_row);
                    $cell++;
                endforeach;
                } else {
                    $sheet->setCellValue('A'.$cell.'', 'NO DATA'); // Set kolom A1 
                    $sheet->setCellValue('J'.$cell.'', $val->operator); // Set kolom A1 
                    $sheet->getStyle('A'.$cell.'')->applyFromArray($style_row);
                    $sheet->getStyle('J'.$cell.'')->applyFromArray($style_row);
                    $cell++;
                }
            }
        } // end for operator

        $writer = new Xlsx($spreadsheet);
        $filename = 'Export '.$produksi.'';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output'); // download file
    } //end


    function hasfolex(){
            $fol = $this->input->post('fol');
            $date = $this->input->post('datesr');
            // echo $fol."<br>";
            // echo $date."<br>";
            $ex = explode(' - ', $date);
            $ext1 = explode('/', $ex[0]);
            $tanggalAwal = $ext1[2]."-".$ext1[0]."-".$ext1[1];
            $ext2 = explode('/', $ex[1]);
            $tanggalAkhir = $ext2[2]."-".$ext2[0]."-".$ext2[1];
            //echo $tanggalAwal." s/d ".$tanggalAkhir."<br>";
            $query = $this->db->query("SELECT * FROM data_fol WHERE kode_roll LIKE 'R%' AND jns_fold = '$fol' AND tgl BETWEEN '$tanggalAwal' AND '$tanggalAkhir'");
            //echo "<table border='1'><tr><td>No</td><td>Kode Roll</td><td>Konstruksi</td><td>Ukuran Inspect ".$fol."</td><td>Ukuran Folding ".$fol."</td><td>Tanggal Folding</td></tr>";
            
            //echo "</table>";



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
            $sheet->setCellValue('A1', "No."); // Set kolom A1 
            $sheet->setCellValue('B1', "Kode Roll"); // Set kolom A1 
            $sheet->setCellValue('C1', "Konstruksi"); // Set kolom A1 
            $sheet->setCellValue('D1', "Ukuran Inspect ".$fol.""); // Set kolom A1 
            $sheet->setCellValue('E1', "Ukuran Folding ".$fol.""); // Set kolom A1 
            $sheet->setCellValue('F1', "Ukuran Folding ".$fol.""); // Set kolom A1 
            $sheet->setCellValue('G1', "Tanggal Folding"); // Set kolom A1 
            $sheet->setCellValue('H1', "Presentase Susut"); // Set kolom A1 
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            // Apply style header yang telah kita buat tadi ke masing-masing kolom header
            $sheet->getStyle('A1')->applyFromArray($style_col);
            $sheet->getStyle('B1')->applyFromArray($style_col);
            $sheet->getStyle('C1')->applyFromArray($style_col);
            $sheet->getStyle('D1')->applyFromArray($style_col);
            $sheet->getStyle('E1')->applyFromArray($style_col);
            $sheet->getStyle('F1')->applyFromArray($style_col);
            $sheet->getStyle('G1')->applyFromArray($style_col);
            $sheet->getStyle('H1')->applyFromArray($style_col);

            $no=1;
            $inrow =2;
            foreach($query->result() as $dtfol){
                $kdr = $dtfol->kode_roll;
                $sheet->setCellValue('A'.$inrow.'', $no); // Set kolom A1 
                $sheet->getStyle('A'.$inrow.'')->applyFromArray($style_row);
                $sheet->setCellValue('B'.$inrow.'', $kdr); // Set kolom A1 
                $sheet->getStyle('B'.$inrow.'')->applyFromArray($style_row);
                $sheet->setCellValue('C'.$inrow.'', $dtfol->konstruksi); // Set kolom A1 
                $sheet->getStyle('C'.$inrow.'')->applyFromArray($style_row);
                if($fol=="Grey"){
                    $ukrins = $this->db->query("SELECT * FROM data_ig WHERE kode_roll='$kdr'");
                    if($ukrins->num_rows() == 1){
                        $ukrinspect = $ukrins->row("ukuran_ori");
                    } else {
                        $ukrinspect = "Tidak Ditemukan";
                    }
                    
                } else {
                    $ukrins = $this->db->query("SELECT * FROM data_if WHERE kode_roll='$kdr'");
                    if($ukrins->num_rows() == 1){
                        $ukrinspect = $ukrins->row("ukuran_ori");
                    } else {
                        $ukrinspect = "Tidak Ditemukan";
                    }
                }
                $sheet->setCellValue('D'.$inrow.'', $ukrinspect); // Set kolom A1 
                $sheet->getStyle('D'.$inrow.'')->applyFromArray($style_row);
                $sheet->setCellValue('E'.$inrow.'', $dtfol->ukuran); // Set kolom A1 
                $sheet->getStyle('E'.$inrow.'')->applyFromArray($style_row);
                $nex = explode('-', $dtfol->tgl);
                $bulan = $this->data_model->printBln($nex[1]);
                $sheet->getStyle('F'.$inrow.'')->applyFromArray($style_row);
                
                $newkdr = $kdr."A";
                $cekFolLain = $this->data_model->get_byid('data_fol', ['kode_roll'=>$newkdr]);
                if($cekFolLain->num_rows() == 1){
                    $susut = floatval($ukrinspect) - floatval($dtfol->ukuran) - floatval($cekFolLain->row('ukuran'));
                } else {
                    $susut = floatval($ukrinspect) - floatval($dtfol->ukuran);
                }
                
                if($ukrinspect=="Tidak Ditemukan"){
                    $sheet->setCellValue('F'.$inrow.'', ''); // Set kolom A1
                } else {
                    $sheet->setCellValue('F'.$inrow.'', $susut); // Set kolom A1
                }
                
                $sheet->setCellValue('G'.$inrow.'', "".$nex[2]." ".$bulan." ".$nex[0].""); // Set kolom A1 
                $sheet->getStyle('G'.$inrow.'')->applyFromArray($style_row);

                if($ukrinspect=="Tidak Ditemukan"){
                    $sheet->setCellValue('H'.$inrow.'', ''); // Set kolom A1
                } else {
                    $presentase = (floatval($susut) / floatval($ukrinspect)) * 100;
                    $alpres = round($presentase,8);
                    $sheet->setCellValue('H'.$inrow.'', ''.$alpres.''); // Set kolom A1
                }
                
                $sheet->getStyle('H'.$inrow.'')->applyFromArray($style_row);
                
                $no++;
                $inrow++;
            }
            
            $writer = new Xlsx($spreadsheet);
            $filename = 'Hasil Folding '.$fol.' Tanggal '.$tanggalAwal.' s/d '.$tanggalAkhir.'';

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output'); // download file
    } //end
}
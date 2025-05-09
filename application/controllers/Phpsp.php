<?php
defined('BASEPATH') OR exit('No direct script access allowed');
  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Phpsp extends CI_Controller {
public function __construct(){
  parent::__construct();
  $this->load->model('data_model');
  date_default_timezone_set("Asia/Jakarta");
}
public function index(){
$this->load->view('spreadsheet');
}
// lets get export file
public function export(){
    $kode = $this->uri->segment(3);
    $st = $this->uri->segment(4);
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
      $sheet->setCellValue('A1', "Kode"); // Set kolom A1 
      $sheet->setCellValue('B1', "No. Roll"); // Set kolom A1 
      $sheet->setCellValue('C1', "Ukuran Ori"); // Set kolom A1 
      $sheet->setCellValue('D1', "Ukuran B"); // Set kolom A1 
      $sheet->setCellValue('E1', "Ukuran C"); // Set kolom A1 
      $sheet->setCellValue('F1', "Ukuran BS"); // Set kolom A1 
      $sheet->setCellValue('G1', "Nama Operator"); // Set kolom A1 
      $sheet->setCellValue('H1', "Satuan"); // Set kolom A1 
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

      $sheet->setCellValue('A2', $kode); // Set kolom A1 
      $sheet->setCellValue('H2', $st); // Set kolom A1 
      $sheet->getStyle('A1')->applyFromArray($style_row);
      
      $writer = new Xlsx($spreadsheet);
      $filename = 'Data-packing-list-kode-'.$kode.'-'.date('Y-m-d').'';

      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output'); // download file
}
//end export file
public function import(){
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
      $kode_produksi = $sheetData[1][0];
      $satuan = $sheetData[1][7];
      $all_data=0;
      $stok_bs=0;
      $auto = $this->data_model->get_byid('tb_produksi',['kode_produksi'=>$kode_produksi]);
      $st_pkg = $auto->row("st_produksi");
      for ($i=1; $i <count($sheetData) ; $i++) { 
        if($sheetData[$i][0]!="" AND $sheetData[$i][1]!="" AND $sheetData[$i][2]!="" AND $sheetData[$i][6]!="" AND $sheetData[$i][7]!=""){
            if($satuan=="Yard"){
              $ukuran_in = floatval($sheetData[$i][2]) * 0.9144;
              $ukuranb_in = floatval($sheetData[$i][3]) * 0.9144;
              $ukuranc_in = floatval($sheetData[$i][4]) * 0.9144;
              $ukuranbs_in = floatval($sheetData[$i][5]) * 0.9144;
              $dtlist = [
                  'kode_produksi' => $sheetData[$i][0],
                  'no_roll' => $sheetData[$i][1],
                  'ukuran_ori' => round($ukuran_in,2),
                  'ukuran_b' => round($ukuranb_in,2),
                  'ukuran_c' => round($ukuranc_in,2),
                  'ukuran_bs' => round($ukuranbs_in,2),
                  'ukuran_now' => round($ukuran_in,2),
                  'operator' => $sheetData[$i][6],
                  'st_pkg' => $st_pkg,
                  'satuan' => $satuan,
                  'ukuran_ori_yard' => round($sheetData[$i][2],2),
                  'ukuran_b_yard' => round($sheetData[$i][3],2),
                  'ukuran_c_yard' => round($sheetData[$i][4],2),
                  'ukuran_bs_yard' => round($sheetData[$i][5],2),
                  'ukuran_now_yard' => round($sheetData[$i][2],2)
              ];
              $this->data_model->saved('new_tb_pkg_list', $dtlist);
              $all_data = $all_data + 1;
              $stok_bs = $stok_bs + floatval($sheetData[$i][5]);
          } elseif ($satuan=="Meter") {
              $ukuran_in = floatval($sheetData[$i][2]) / 0.9144;
              $ukuranb_in = floatval($sheetData[$i][3]) / 0.9144;
              $ukuranc_in = floatval($sheetData[$i][4]) / 0.9144;
              $ukuranbs_in = floatval($sheetData[$i][5]) / 0.9144;
              $dtlist = [
                  'kode_produksi' => $sheetData[$i][0],
                  'no_roll' => $sheetData[$i][1],
                  'ukuran_ori' => round($sheetData[$i][2],2),
                  'ukuran_b' => round($sheetData[$i][3],2),
                  'ukuran_c' => round($sheetData[$i][4],2),
                  'ukuran_bs' => round($sheetData[$i][5],2),
                  'ukuran_now' => round($sheetData[$i][2],2),
                  'operator' => $sheetData[$i][6],
                  'st_pkg' => $st_pkg,
                  'satuan' => $satuan,
                  'ukuran_ori_yard' => round($ukuran_in,2),
                  'ukuran_b_yard' => round($ukuranb_in,2),
                  'ukuran_c_yard' => round($ukuranc_in,2),
                  'ukuran_bs_yard' => round($ukuranbs_in,2),
                  'ukuran_now_yard' => round($ukuran_in,2)
              ];
              $this->data_model->saved('new_tb_pkg_list', $dtlist);
              $all_data = $all_data + 1;
              $stok_bs = $stok_bs + floatval($sheetData[$i][5]);
          }
          //jika data tidak kosong kode di atas ini
        } else {}
      } //end for
      
      $kode_kons = $auto->row("kode_konstruksi");
      $tgl_pros = $auto->row("tgl_produksi");
      $loc = $this->session->userdata('departement');

      $txt = "Menambahkan sebanyak ".$all_data." roll data di dalam packing list (".$kode_produksi.").";
      $this->data_model->saved("log_program", ["id_user"=>$this->session->userdata('id'), "log"=>$txt]);
      $cek_today = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kode_kons, 'lokasi_produksi'=>$loc, 'waktu'=>$tgl_pros]);
      $idnow = $cek_today->row("id_rptd");
      if($satuan=="Meter"){
          $up_nilai_bs_yard = $stok_bs / 0.9144 ;
          $ar_nilai = ['bs' => round($stok_bs,2), 'bs_yard' => round($up_nilai_bs_yard,2) ];
      } elseif($satuan=="Yard") {
          $up_nilai_bs_meter = $stok_bs * 0.9144 ;
          $ar_nilai = ['bs' => round($up_nilai_bs_meter,2), 'bs_yard' => round($stok_bs,2) ];
      }
      $this->data_model->updatedata('id_rptd', $idnow, 'report_produksi_harian', $ar_nilai);
      $cek_ttl_stok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kode_kons, 'departement'=>$loc]);
            $id_stok = $cek_ttl_stok->row("id_stok");
            $rs_bs = $cek_ttl_stok->row("bs");
            $new_rs_bs = $rs_bs + $stok_bs;
            if($satuan=="Yard"){
                $bs_meter = $new_rs_bs * 0.9144;
                $ar_nilai2 = ['bs'=>round($bs_meter,2), 'bs_yard'=>round($new_rs_bs,)];
            } elseif ($satuan=="Meter") {
                $bs_yard = $new_rs_bs / 0.9144;
                $ar_nilai2 = ['bs'=>round($new_rs_bs,2), 'bs_yard'=>round($bs_yard,2)];
            }
            $this->data_model->updatedata('id_stok', $id_stok, 'report_stok', $ar_nilai2);
            $this->session->set_flashdata('announce', 'Berhasil menyimpan '.$all_data.' roll data ke packinglist.');
            redirect(base_url('input-produksi'));
  }
} //end


    public function exportif(){
      $kode = $this->uri->segment(3);
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
        $sheet->setCellValue('A1', "Kode"); // Set kolom A1 
        $sheet->setCellValue('B1', "No. Roll"); // Set kolom A1 
        $sheet->setCellValue('C1', "Ukuran Ori"); // Set kolom A1 
        $sheet->setCellValue('D1', "Ukuran A"); // Set kolom A1 
        $sheet->setCellValue('E1', "Ukuran B"); // Set kolom A1 
        $sheet->setCellValue('F1', "Ukuran C"); // Set kolom A1 
        $sheet->setCellValue('G1', "Ukuran BS"); // Set kolom A1 
        $sheet->setCellValue('H1', "Nama Operator"); // Set kolom A1 
        $sheet->setCellValue('I1', "Satuan"); // Set kolom A1 
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
        $sheet->getStyle('A1')->applyFromArray($style_col);
        $sheet->getStyle('B1')->applyFromArray($style_col);
        $sheet->getStyle('C1')->applyFromArray($style_col);
        $sheet->getStyle('D1')->applyFromArray($style_col);
        $sheet->getStyle('E1')->applyFromArray($style_col);
        $sheet->getStyle('F1')->applyFromArray($style_col);
        $sheet->getStyle('G1')->applyFromArray($style_col);
        $sheet->getStyle('H1')->applyFromArray($style_col);
        $sheet->getStyle('I1')->applyFromArray($style_col);
  
        $sheet->setCellValue('A2', $kode); // Set kolom A1 
        $sheet->setCellValue('I2', 'Yard'); // Set kolom A1 
        $sheet->getStyle('A1')->applyFromArray($style_row);
        
        $writer = new Xlsx($spreadsheet);
        $filename = 'Data-packing-list-inspect-kode-'.$kode.'-'.date('Y-m-d').'';
  
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output'); // download file
    } //edn export if

    public function importif(){
        $user = $this->session->userdata('id');
        $kode_konstruksi = $this->data_model->filter($this->input->post('kode'));
        $kode_produksi = $this->data_model->filter($this->input->post('kdp'));
        $tanggal = $this->data_model->filter($this->input->post('tgl'));
        $loc = $this->data_model->filter($this->input->post('loc'));
        $jumlah = $this->data_model->filter($this->input->post('jumlah'));
        $jumlah_meter = floatval($jumlah) * 0.9144;
        if($kode_produksi!="" AND $kode_konstruksi!="" AND $tanggal!="" AND $loc!="" AND $jumlah!=""){
            //echo "Kode Produksi : ".$kode_produksi."<br>";
            //echo "Kode Konstruksi : ".$kode_konstruksi."<br>";
            //echo "Tanggal : ".$tanggal."<br>";
            //echo "loc : ".$loc."<br>";
            //echo "jumlah : ".round($jumlah,2)." y<br>";
            //echo "jumlah : ".round($jumlah_meter,2)." m<br><hr>";
          // mulai proses input excel
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
              $data_salah = 0; $data_benar = 0;
              for ($i=1; $i <count($sheetData) ; $i++) { 
                if($sheetData[$i][0]!="" AND $sheetData[$i][1]!="" AND $sheetData[$i][2]!="" AND $sheetData[$i][7]!="" AND $sheetData[$i][8]!=""){
                    // echo "Kode Produksi (".$sheetData[$i][0].") - ";
                    // echo "Kode roll (".$sheetData[$i][1].") - ";
                    // echo "ori (".$sheetData[$i][2].") - ";
                    // echo "a (".$sheetData[$i][3].") - ";
                    // echo "b (".$sheetData[$i][4].") - ";
                    // echo "c (".$sheetData[$i][5].") - ";
                    // echo "bs (".$sheetData[$i][6].") - ";
                    // echo "operator (".$sheetData[$i][7].") - ";
                    // echo "Satuan (".$sheetData[$i][8].") - <br>";
                    if($sheetData[$i][0]==$kode_produksi){ $data_benar+=1; } else { $data_salah+=1; }
                }
              } //end for
              //cek kebenaran data (kode_produksi)
              if($data_salah>0){
                  $this->session->set_flashdata('gagal', 'Kode di dalam excel tidak sesuai yang tertera pada form. Silahkan download ulang dan upload.');
                  redirect(base_url('input-produksi'));
              } else {
                  $dt_produksi = [
                    'kode_produksi' => $kode_produksi,
                    'tgl_produksi' => $tanggal,
                    'kode_konstruksi' => $kode_konstruksi,
                    'lokasi_produksi' => $loc,
                    'jumlah_mesin' => 0,
                    'id_user' => $user,
                    'jumlah_produksi_awal' => $jumlah_meter,
                    'jumlah_produksi_now' => $jumlah_meter,
                    'jumlah_produksi_awal_yard' => $jumlah,
                    'jumlah_produksi_now_yard' => $jumlah,
                    'lokasi_saat_ini' => $loc,
                    'satuan' => 'Yard',
                    'st_produksi' => 'IF'
                  ];
                  $this->data_model->saved('tb_produksi', $dt_produksi);
                  $txt = "Telah menambahkan produksi inspect finish untuk stok lama dengan kode produksi (".$kode_produksi.")";
                  $this->data_model->saved('log_produksi', ['id_user'=>$user,'kode_produksi'=>$kode_produksi,'log'=>$txt]);
                  $this->data_model->saved('log_program', ['id_user'=>$user,'log'=>$txt]);
                  for ($i=1; $i <count($sheetData) ; $i++) { 
                      $ori = floatval($sheetData[$i][2]); $ori_mtr = $ori * 0.9144;
                      $uka = floatval($sheetData[$i][3]); $uka_mtr = $uka * 0.9144;
                      $ukb = floatval($sheetData[$i][4]); $ukb_mtr = $ukb * 0.9144;
                      $ukc = floatval($sheetData[$i][5]); $ukc_mtr = $ukc * 0.9144;
                      $ukbs = floatval($sheetData[$i][6]); $ukbs_mtr = $ukbs * 0.9144;
                      $list_if = [
                        'id_pkg' => 0,
                        'kode_produksi' => $kode_produksi,
                        'no_roll' => $sheetData[$i][1],
                        'ukuran_ori' => round($ori_mtr,2),
                        'ukuran_a' => round($uka_mtr,2),
                        'ukuran_b' => round($ukb_mtr,2),
                        'ukuran_c' => round($ukc_mtr,2),
                        'ukuran_bs' => round($ukbs_mtr,2),
                        'ukuran_now' => round($ori_mtr,2),
                        'operator' => $sheetData[$i][7],
                        'satuan' => 'Yard',
                        'tgl' => $tanggal,
                        'ukuran_ori_yard' => round($ori,2),
                        'ukuran_a_yard' => round($uka,2),
                        'ukuran_b_yard' => round($ukb,2),
                        'ukuran_c_yard' => round($ukc,2),
                        'ukuran_bs_yard' => round($ukbs,2),
                        'ukuran_now_yard' => round($ori,2),
                      ];
                      $this->data_model->saved('new_tb_pkg_ins', $list_if);
                  } //end for input
                  //cek report stok lama 
                  $stok_lama = $this->data_model->get_byid('report_stok_lama', ['kode_konstruksi'=>$kode_konstruksi, 'departement'=>$loc]);
                  if($stok_lama->num_rows()==1){
                      $id_sl = $stok_lama->row("id_sl");
                      $ins = $stok_lama->row('ins_finish');
                      $ins_yrd = $stok_lama->row('ins_finish_yard');
                      $ins_now = $jumlah_meter + $ins;
                      $ins_yrd_now = floatval($jumlah) + $ins_yrd;
                      $this->data_model->updatedata('id_sl',$id_sl,'report_stok_lama',['ins_finish'=>round($ins_now,2), 'ins_finish_yard'=>round($ins_yrd_now,2)]);
                  } elseif ($stok_lama->num_rows()==0) {
                      $sl_list = [
                        'kode_konstruksi' => $kode_konstruksi, 'ins_finish' => round($jumlah_meter,2), 'fol_grey' => 0, 'fol_finish' => 0, 'terjual' => 0, 'ins_finish_yard' => round($jumlah,2), 'fol_grey_yard' => 0, 'fol_finish_yard' => 0, 'terjual_yard' => 0, 'departement' => $loc,
                      ];
                      $this->data_model->saved('report_stok_lama', $sl_list);
                  }
                  //cek produksi_harian
                  $cek_rh = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kode_konstruksi, 'lokasi_produksi'=>$loc, 'waktu'=>$tanggal]);
                  if($cek_rh->num_rows()==1){
                      $id = $cek_rh->row("id_rptd");
                      $ins = $cek_rh->row("ins_finish");
                      $insy = $cek_rh->row("ins_finish_yard");
                      $new_ins = round($jumlah_meter) + $ins;
                      $new_insy = floatval($jumlah) + $insy;
                      $this->data_model->updatedata('id_rptd', $id, 'report_produksi_harian', ['ins_finish'=>round($new_ins,2), 'ins_finish_yard'=>round($jumlah,2)]);
                  } elseif ($cek_rh->num_rows()==0) {
                      $rhlist = [
                        'kode_konstruksi' => $kode_konstruksi, 'ins_grey' => 0, 'ins_finish' => round($jumlah_meter,2), 'fol_grey' => 0, 'fol_finish' => 0, 'lokasi_produksi' => $loc, 'waktu' => $tanggal, 'terjual' => 0, 'bs' => 0, 'ins_grey_yard' => 0, 'ins_finish_yard' => round($jumlah,2), 'fol_grey_yard' => 0, 'fol_finish_yard' => 0, 'terjual_yard' => 0, 'bs_yard' => 0
                      ];
                      $this->data_model->saved('report_produksi_harian', $rhlist);
                  }
                  $this->session->set_flashdata('announce', 'Proses produksi inspect finish untuk stok lama berhasil. Menambahkan '.$data_benar.' data dalam packing list');
                  redirect(base_url('input-produksi-inspect'));
              }
          //end proses input excel
            }
        } else {
            $this->session->set_flashdata('gagal', 'Anda tidak mengisi data produksi dengan benar.');
            redirect(base_url('input-produksi-inspect'));
        }
    } //end importif

    public function exportfol(){
      //$kode = $this->uri->segment(3);
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
        $sheet->setCellValue('A1', "Kode Konstruksi"); // Set kolom A1 
        $sheet->setCellValue('B1', "Ukuran Folding"); // Set kolom A1 
        $sheet->setCellValue('C1', "Grey / Finish"); // Set kolom A1 
        $sheet->setCellValue('D1', "Tanggal Folding"); // Set kolom A1 
        $sheet->setCellValue('E1', "Operator Folding"); // Set kolom A1 
        $sheet->setCellValue('F1', "Departement"); // Set kolom A1 
        $sheet->setCellValue('I1', "NOTE :"); // Set kolom A1 
        
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $sheet->getStyle('A1')->applyFromArray($style_col);
        $sheet->getStyle('B1')->applyFromArray($style_col);
        $sheet->getStyle('C1')->applyFromArray($style_col);
        $sheet->getStyle('D1')->applyFromArray($style_col);
        $sheet->getStyle('E1')->applyFromArray($style_col);
        $sheet->getStyle('F1')->applyFromArray($style_col);
        $sheet->getStyle('I1')->applyFromArray($style_col);

        $sheet->setCellValue('I2', "-Gunakan format tanggal DD-MM-YYYY (Ex : 01-12-2023)");
        $sheet->setCellValue('I3', "-Semua kolom wajib di isi");
        $sheet->setCellValue('I4', "-Desimal menggunakan titik");
        $sheet->setCellValue('I5', "-Pemisah angka menggunakan koma");
        $sheet->setCellValue('I6', "-Departement (Samatex,Pusatex,RJS)");
        
        $writer = new Xlsx($spreadsheet);
        $filename = 'Folding-stok-lama-tanpa-kode-roll';
  
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output'); // download file
    } //end export folding

    public function importfol(){
      $id_user = $this->session->userdata('id');
      $dep = $this->session->userdata('departement');
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
                $kdkons = $sheetData[$i][0];
                $ukuran = $sheetData[$i][1];
                $stfol = $sheetData[$i][2];
                $tgl = $sheetData[$i][3];
                if($tgl!=""){
                $ex = explode('-', $tgl);
                $formatTgl = $ex[2]."-".$ex[1]."-".$ex[0]; }
                $operator = $sheetData[$i][4];
                $posisi_barang = $sheetData[$i][5];
                //echo "$kdkons - $ukuran - $stfol - $formatTgl - $operator<br>";
                    $dtlama = [
                      'konstruksi' => $kdkons,
                      'ukuran' => $ukuran,
                      'tgl_fol' => $formatTgl,
                      'st_folding' => $stfol,
                      'operator' => $operator,
                      'posisi' => $dep
                    ];
                    $this->data_model->saved('data_stok_lama', $dtlama);
                    $cek = $this->data_model->get_byid('data_stok_lama_harian', ['konstruksi'=>$kdkons, 'folding'=>$stfol, 'tanggal'=>$formatTgl]);
                    if($cek->num_rows()==0){
                        $dtlist = [
                          'konstruksi' => $kdkons,
                          'ukuran' => $ukuran,
                          'folding' => $stfol,
                          'tanggal' => $formatTgl
                        ];
                        $this->data_model->saved('data_stok_lama_harian', $dtlist);
                    } else {
                        $id_dslh = $cek->row("id_dslh");
                        $new_ukuran = floatval($cek->row("ukuran")) + floatval($ukuran);
                        $this->data_model->updatedata('id_dslh',$id_dslh,'data_stok_lama_harian', ['ukuran'=>round($new_ukuran,2)]);
                    }
                    $cek2 = $this->data_model->get_byid('data_stok_lama_total', ['konstruksi'=>$kdkons, 'folding'=>$stfol]);
                    if($cek2->num_rows()==0){
                        $dtlist = [
                          'konstruksi' => $kdkons,
                          'ukuran' => $ukuran,
                          'folding' => $stfol
                        ];
                        $this->data_model->saved('data_stok_lama_total', $dtlist);
                    } else {
                        $new_ukuran2 = floatval($cek2->row("ukuran")) + floatval($ukuran);
                        $this->data_model->updatedata('konstruksi',$kdkons,'data_stok_lama_total', ['ukuran'=>round($new_ukuran2,2)]);
                    }
                    //HARUS TAMBAHKAN JUGA PROSES PRODUKSI HARIANNYA
                    $cek_pd = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kdkons, 'tgl'=>$formatTgl, 'dep'=>'Samatex']);
                    if($cek_pd->num_rows()==0){
                        $dtlistprod = [
                          'kode_konstruksi' => $kdkons,
                          'tgl' => $formatTgl,
                          'dep' => 'Samatex',
                          'prod_ig' => 0,
                          'prod_fg' => $stfol=='Grey' ? $ukuran:'0',
                          'prod_if' => 0,
                          'prod_ff' => $stfol=='Finish' ? $ukuran:'0',
                          'prod_bs1' => 0,
                          'prod_bp1' => 0,
                          'prod_bs2' => 0,
                          'prod_bp2' => 0,
                          'crt' => 0
                        ];
                        $this->data_model->saved('data_produksi', $dtlistprod);
                    } else {
                        $id_pd = $cek_pd->row("id_produksi");
                        if($stfol=="Grey"){
                            $new_prod_grey = floatval($cek_pd->row("prod_fg")) + floatval($ukuran);
                            $this->data_model->updatedata('id_produksi',$id_pd,'data_produksi',['prod_fg'=>round($new_prod_grey,2)]);
                        } 
                        if($stfol=="Finish"){
                            $new_prod_finish = floatval($cek_pd->row("prod_ff")) + floatval($ukuran);
                            $this->data_model->updatedata('id_produksi',$id_pd,'data_produksi',['prod_ff'=>round($new_prod_finish,2)]);
                        } 
                    }
                    //HARUS TAMBAHKAN JUGA PROSES PRODUKSI HARIANNYA
                    $cek_pd = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$formatTgl, 'dep'=>'Samatex']);
                    if($cek_pd->num_rows()==0){
                        $dtlistprod = [
                          'tgl' => $formatTgl,
                          'dep' => 'Samatex',
                          'prod_ig' => 0,
                          'prod_fg' => $stfol=='Grey' ? $ukuran:'0',
                          'prod_if' => 0,
                          'prod_ff' => $stfol=='Finish' ? $ukuran:'0',
                          'prod_bs1' => 0,
                          'prod_bp1' => 0,
                          'prod_bs2' => 0,
                          'prod_bp2' => 0,
                          'crt' => 0
                        ];
                        $this->data_model->saved('data_produksi_harian', $dtlistprod);
                    } else {
                        $id_pd = $cek_pd->row("id_prod_hr");
                        if($stfol=="Grey"){
                            $new_prod_grey = floatval($cek_pd->row("prod_fg")) + floatval($ukuran);
                            $this->data_model->updatedata('id_prod_hr',$id_pd,'data_produksi_harian',['prod_fg'=>round($new_prod_grey,2)]);
                        } 
                        if($stfol=="Finish"){
                            $new_prod_finish = floatval($cek_pd->row("prod_ff")) + floatval($ukuran);
                            $this->data_model->updatedata('id_prod_hr',$id_pd,'data_produksi_harian',['prod_ff'=>round($new_prod_finish,2)]);
                        } 
                    }
                
            } //end for
            $this->session->set_flashdata('announce', 'Berhasil menyimpan data stok lama.');
            redirect(base_url('stok-gudang'));
        //end proses input excel
          } else {
              echo "File tidak bisa terbaca oleh sistem";
          }
        
    } //end

    public function exportinsgrey(){
      //$kode = $this->uri->segment(3);
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
        $sheet->setCellValue('B1', "Kode Konstruksi"); // Set kolom A1 
        $sheet->setCellValue('C1', "Nomor Mesin"); // Set kolom A1 
        $sheet->setCellValue('D1', "Nomor Beam"); // Set kolom A1 
        $sheet->setCellValue('E1', "OKA"); // Set kolom A1 
        $sheet->setCellValue('F1', "Ukuran Ori"); // Set kolom A1 
        $sheet->setCellValue('G1', "Ukuran BS"); // Set kolom A1 
        $sheet->setCellValue('H1', "Ukuran Bp"); // Set kolom A1 
        $sheet->setCellValue('I1', "Tanggal"); // Set kolom A1 
        $sheet->setCellValue('J1', "Operator"); // Set kolom A1 
        $sheet->setCellValue('M1', "Petunjuk Pengisian :"); // Set kolom A1 
        
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
        $sheet->getColumnDimension('M')->setAutoSize(true);
        
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
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
        $sheet->getStyle('M1')->applyFromArray($style_col);
        
  
        $sheet->setCellValue('A2', ''); // Set kolom A1
        $sheet->setCellValue('M2', '- Gunakan Format tanggal DD-MM-YYYY (ex: 01-09-2023)'); // Set kolom A1
        $sheet->setCellValue('M3', '- Semua Kolom wajib di isi'); // Set kolom A1
        $sheet->setCellValue('M4', '- Kolom Ukuran BS bisa di isi dengan angka nol'); // Set kolom A1
        $sheet->getStyle('A1')->applyFromArray($style_row);
        
        $writer = new Xlsx($spreadsheet);
        $filename = 'Data-to-inspect-grey';
  
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output'); // download file
    } //end

    public function importinsgrey(){
        $user = $this->session->userdata('id');
        $kode_konstruksi = $this->data_model->filter($this->input->post('kode'));
        $kode_produksi = $this->data_model->filter($this->input->post('kdp'));
        $tanggal = $this->data_model->filter($this->input->post('tgl'));
        $loc = $this->data_model->filter($this->input->post('loc'));
        $jumlah = $this->data_model->filter($this->input->post('jumlah'));
        $jumlah = floatval($jumlah); $jumlah = round($jumlah,2);
        $satuan = $this->data_model->filter($this->input->post('satuan'));
        
        if($kode_konstruksi!="" AND $kode_produksi!="" AND $tanggal!="" AND $loc!=""){
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
            $data_salah = 0; $data_benar = 0; $jumlah_excel=0; $jumlah_bs_asli=0;
            $not_input_err = 0;
            for ($i=1; $i <count($sheetData) ; $i++) { 
              if($sheetData[$i][1]!="" AND $sheetData[$i][4]!="" AND $sheetData[$i][2]!="" AND $sheetData[$i][3]!=""){
                $kode_roll2 = $sheetData[$i][1];
                $cek_kode_roll2 = $this->data_model->get_byid('new_tb_pkg_list',['no_roll'=>$kode_roll2])->num_rows();
                if($cek_kode_roll2==0){
                    $data_benar = $data_benar+1; 
                    $jumlah_ori = floatval($sheetData[$i][4]);
                    $jumlah_ori_rnd = round($jumlah_ori,2);
                    $jumlah_excel = $jumlah_excel + $jumlah_ori_rnd;
                    $jumlah_bs_asli = $jumlah_bs_asli + floatval($sheetData[$i][7]);
                } else { $not_input_err+=1; }
              }
            } //end for
            if($satuan=="Yard"){
              $jumlah_yard = $jumlah_excel;
              $jumlah_meter = $jumlah_excel * 0.9144;
              $jumlah_bs_asliy = $jumlah_bs_asli;
              $jumlah_bs_aslim = $jumlah_bs_asli * 0.9144;
            } else {
              $jumlah_meter = $jumlah_excel;
              $jumlah_yard = $jumlah_excel / 0.9144;
              $jumlah_bs_aslim = $jumlah_bs_asli;
              $jumlah_bs_asliy = $jumlah_bs_asli / 0.9144;
            }
            //cek kebenaran data (kode_produksi)
            if($data_salah>0){
                $this->session->set_flashdata('gagal', 'Kode di dalam excel tidak sesuai yang tertera pada form. Silahkan download ulang dan upload.');
                redirect(base_url('input-produksi-insgrey'));
            } else {
                //if($jumlah_excel==$jumlah){
                    $dt_produksi = [
                      'kode_produksi' => $kode_produksi,
                      'tgl_produksi' => $tanggal,
                      'kode_konstruksi' => $kode_konstruksi,
                      'lokasi_produksi' => $loc,
                      'jumlah_mesin' => 0,
                      'id_user' => $user,
                      'jumlah_produksi_awal' => round($jumlah_meter,2),
                      'jumlah_produksi_now' => round($jumlah_meter,2),
                      'jumlah_produksi_awal_yard' => round($jumlah_yard,2),
                      'jumlah_produksi_now_yard' => round($jumlah_yard,2),
                      'lokasi_saat_ini' => $loc,
                      'satuan' => $satuan,
                      'st_produksi' => 'IG'
                    ];
                    $this->data_model->saved('tb_produksi', $dt_produksi);
                    // input log program //
                    $txt = "Telah menambahkan produksi baru dengan kode produksi (<strong>".$kode_produksi."</strong>)";
                    $dtlog = [ 'id_user' => $user, 'log' => $txt ];
                    $this->data_model->saved('log_program', $dtlog);
                    // input log produksi //
                    $dtlog = [ 'id_user' => $user, 'kode_produksi' => $kode_produksi, 'log'=>$txt ];
                    $this->data_model->saved('log_produksi', $dtlog);
                    //mulai input list
                    $stok_bs_meter = 0;
                    $stok_bs_yard = 0;
                    for ($i=1; $i <count($sheetData) ; $i++) { 
                        if($satuan=="Yard"){
                            $ori_yrd = floatval($sheetData[$i][4]); $ori_mtr = $ori_yrd * 0.9144;
                            $ori_by = floatval($sheetData[$i][5]); $ori_bm = $ori_by * 0.9144;
                            $ori_cy = floatval($sheetData[$i][6]); $ori_cm = $ori_cy * 0.9144;
                            $ori_bsy = floatval($sheetData[$i][7]); $ori_bsm = $ori_bsy * 0.9144;
                            $stok_bs_yard = $stok_bs_yard + round($ori_bsy);
                        } else {
                            $ori_mtr = floatval($sheetData[$i][4]); $ori_yrd = $ori_mtr / 0.9144;
                            $ori_bm = floatval($sheetData[$i][5]); $ori_by = $ori_bm / 0.9144;
                            $ori_cm = floatval($sheetData[$i][6]); $ori_cy = $ori_cm / 0.9144;
                            $ori_bsm = floatval($sheetData[$i][7]); $ori_bsy = $ori_bsm / 0.9144;
                            $stok_bs_meter = $stok_bs_meter + round($ori_bsm);
                        }
                        $kode_roll = $sheetData[$i][1];
                        $cek_kode_roll = $this->data_model->get_byid('new_tb_pkg_list', ['no_roll'=>$kode_roll])->num_rows();
                        if($cek_kode_roll==0){
                        $list_if = [
                          'kode_produksi' => $kode_produksi,
                          'no_roll' => $kode_roll,
                          'no_mesin' => $sheetData[$i][2],
                          'no_beam' => $sheetData[$i][3],
                          'ukuran_ori' => round($ori_mtr,2),
                          'ukuran_b' => round($ori_bm,2),
                          'ukuran_c' => round($ori_cm,2),
                          'ukuran_bs' => round($ori_bsm,2),
                          'ukuran_now' => round($ori_mtr,2),
                          'operator' => $sheetData[$i][8],
                          'st_pkg' => 'IG',
                          'satuan' => $satuan,
                          'ukuran_ori_yard' => round($ori_yrd,2),
                          'ukuran_b_yard' => round($ori_by,2),
                          'ukuran_c_yard' => round($ori_cy,2),
                          'ukuran_bs_yard' => round($ori_bsy,2),
                          'ukuran_now_yard' => round($ori_yrd,2)
                        ];
                        if($kode_roll==NULL){} else {
                        $this->data_model->saved('new_tb_pkg_list', $list_if); }
                        } else {
                          $err_txt = "Baris ke ".$i." tidak di input ke sistem karena kode ".$kode_roll." telah di gunakan.";
                          $dtlog_erorr = [ 'id_user' => $user, 'log' => $err_txt];
                          $this->data_model->saved('log_program',$dtlog_erorr);
                        }
                    } //end for input list
                    //cek produksi_harian
                    $cek_rh = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kode_konstruksi, 'lokasi_produksi'=>$loc, 'waktu'=>$tanggal]);
                    if($cek_rh->num_rows()==1){
                        $id = $cek_rh->row("id_rptd");
                            $ffm = $cek_rh->row("ins_grey");
                            $ffy = $cek_rh->row("ins_grey_yard");
                            $tbbs = $cek_rh->row("bs");
                            $tbbs_yard = $cek_rh->row("bs_yard");
                            $up_ffm = $jumlah_meter + $ffm;
                            $up_ffy = $jumlah_yard + $ffy;
                            $up_tbbs = $tbbs + round($jumlah_bs_aslim,2);
                            $up_tbbs_yard = $tbbs_yard + round($jumlah_bs_asliy,2);
                            $this->data_model->updatedata('id_rptd',$id,'report_produksi_harian',['ins_grey'=>round($up_ffm,2), 'bs'=>round($up_tbbs,2), 'ins_grey_yard'=>round($up_ffy,2), 'bs_yard'=>round($up_tbbs_yard,2)]);
                    } elseif ($cek_rh->num_rows()==0) {
                          $rhlist = [
                            'kode_konstruksi' => $kode_konstruksi, 'ins_grey' => round($jumlah_meter,2), 'ins_finish' => 0, 'fol_grey' => 0, 'fol_finish' => 0, 'lokasi_produksi' => $loc, 'waktu' => $tanggal, 'terjual' => 0, 'bs' => round($jumlah_bs_aslim,2), 'ins_grey_yard' => round($jumlah_yard,2), 'ins_finish_yard' => 0, 'fol_grey_yard' => 0, 'fol_finish_yard' => 0, 'terjual_yard' => 0, 'bs_yard' => round($jumlah_bs_asliy,2)
                          ];
                        $this->data_model->saved('report_produksi_harian', $rhlist);
                    }
                    //cek stok total
                    ///disini di isi stok total yaa
                    $cek_ttl_stok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kode_konstruksi, 'departement'=>$loc]);
                    if($cek_ttl_stok->num_rows()==1){
                        $id_stok = $cek_ttl_stok->row("id_stok");
                        $jml_greym = $cek_ttl_stok->row("stok_ins");
                        $jml_greyy = $cek_ttl_stok->row("stok_ins_yard");
                        $jml_bsm = $cek_ttl_stok->row("bs");
                        $jml_bsy = $cek_ttl_stok->row("bs_yard");
                        $upjml_bsy = $jml_bsy + round($jumlah_bs_asliy,2);
                        $upjml_bsm = $jml_bsm + round($jumlah_bs_aslim,2);
                        $up_jmlgm = $jml_greym + round($jumlah_meter,2);
                        $up_jmlgy = $jml_greyy + round($jumlah_yard,2);
                        $this->data_model->updatedata('id_stok',$id_stok, 'report_stok', ['stok_ins'=>round($up_jmlgm,2), 'bs'=>round($upjml_bsm,2), 'stok_ins_yard'=>round($up_jmlgy,2), 'bs_yard'=>round($upjml_bsy)]);
                    } elseif ($cek_ttl_stok->num_rows()==0) {
                        $stklist = [
                          'kode_konstruksi' => $kode_konstruksi,
                          'stok_ins' => round($jumlah_meter,2),
                          'stok_ins_finish' => 0, 'stok_fol' => 0, 'stok_fol_finish' => 0, 'terjual' => 0,
                          'bs' => round($jumlah_bs_aslim,2),
                          'retur' => 0,
                          'departement' => $loc,
                          'stok_ins_yard' => round($jumlah_yard,2),
                          'stok_ins_finish_yard' => 0, 'stok_fol_yard' => 0, 'stok_fol_finish_yard' => 0, 'terjual_yard' => 0,
                          'bs_yard' => round($jumlah_bs_asliy,2),
                          'retur_yard' => 0
                        ];
                        $this->data_model->saved('report_stok',$stklist);
                    }
                   if($not_input_err==0){
                    $notis = "Semua data dalam excel / ".$data_benar." jumlah roll Berhasil di simpan.";
                   } else {
                    $notis = "Berhasil menyimpan sejumlah ".$data_benar." roll. Gagal simpan ".$not_input_err." roll.";
                   }
                   $this->session->set_flashdata('announce', $notis);
                   redirect(base_url('data/list/'.sha1($kode_produksi)));
               
            }    
                
        //end proses input excel
          } else {
              $this->session->set_flashdata('gagal', 'File yang anda masukan salah');
              redirect(base_url('input-produksi-insgrey'));
          }
        } else {
            $this->session->set_flashdata('gagal', 'Anda tidak mengisi data produksi dengan benar.');
            redirect(base_url('input-produksi-insgrey'));
        }
    } //end

    public function exfinishdep(){
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
        $sheet->setCellValue('A1', "No. Roll"); // Set kolom A1 
        $sheet->setCellValue('B1', "Ukuran"); // Set kolom A1 
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $sheet->getStyle('A1')->applyFromArray($style_col);
        $sheet->getStyle('B1')->applyFromArray($style_col);
        
        $writer = new Xlsx($spreadsheet);
        $filename = 'Data-from-out-list'.date('Y-m-d').'';
  
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output'); // download file
    } //end

    function finishdep(){
        $id = $this->data_model->filter($this->input->post('idasli'));
        $kd = $this->data_model->filter($this->input->post('idprod'));
        $tgl = $this->data_model->filter($this->input->post('tgl'));
        $fol = $this->data_model->filter($this->input->post('fol'));
        $dep = $this->session->userdata('departement');
        $kode_kons = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kd])->row("kode_konstruksi");
        if($id!="" AND $kd!="" AND $tgl!="" AND $fol!="" AND $dep!=""){
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
            $data_benar = 0; $jumlah_total = 0; $jumlah_asli_ex = 0;
            for ($i=1; $i <count($sheetData) ; $i++) { 
              if($sheetData[$i][0]!="" AND $sheetData[$i][1]!=""){
                  $data_benar+=1;
                  $norol = $sheetData[$i][0];
                  $ukuran = floatval($sheetData[$i][1]);
                  $jumlah_asli_ex = $jumlah_asli_ex + $ukuran;
                  if($fol=="Finish"){
                      $ukuran_yard = round($ukuran,2);
                      $ukuran_m = $ukuran_yard * 0.9144;
                      $ukuran_meter = round($ukuran_m,2);
                      $jumlah_total = $jumlah_total + $ukuran_yard;
                  } else {
                      $ukuran_meter = round($ukuran,2);
                      $ukuran_y = $ukuran_meter / 0.9144;
                      $ukuran_yard = round($ukuran_y,2);
                      $jumlah_total = $jumlah_total + $ukuran_meter;
                  }
                  $dtlist = [
                    'kode_produksi' => $kd,
                    'asal' => 'null',
                    'id_asal' => 0,
                    'no_roll' => $norol,
                    'tgl' => $tgl,
                    'ukuran' => $ukuran_meter,
                    'operator' => 'null',
                    'st_folding' => $fol,
                    'ukuran_now' => $ukuran_meter,
                    'ukuran_yard' => $ukuran_yard,
                    'ukuran_now_yard' => $ukuran_yard,
                    'id_effected_row' => 0
                  ];
                  $this->data_model->saved('new_tb_pkg_fol', $dtlist);
              }
            } //end for
            //end proses input excel
            $this->data_model->updatedata('idpout',$id,'tb_produksi_out',['tgl_kembali'=>$tgl, 'jml_kembali'=>round($jumlah_total,2), 'st_back'=>'Finish']);
            
            //cek stok 
            $cekstok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kode_kons, 'departement'=>$dep]);
            $cekstokid = $cekstok->row("id_stok");
            if($fol=="Finish"){
              $jumlah_yard = round($jumlah_total,2);
              $jumlah_m = $jumlah_yard * 0.9144;
              $jumlah_meter = round($jumlah_m,2);
              $pnm = 'FF';
              $satuan = 'Yard';
              $stok_fol = $cekstok->row("stok_fol_finish");
              $up_stok_fol = $stok_fol + $jumlah_yard;
              $up_stok_foly = $up_stok_fol * 0.9144;
              $this->data_model->updatedata('id_stok',$cekstokid,'report_stok',['stok_fol_finish'=>round($up_stok_foly,2), 'stok_fol_finish_yard'=>round($up_stok_fol,2)]);
            } else {
              $jumlah_meter = round($jumlah_total,2);
              $jumlah_y = $jumlah_meter / 0.9144;
              $jumlah_yard = round($jumlah_y,2);
              $pnm = 'FG';
              $satuan = 'Meter';
              $stok_fol = $cekstok->row("stok_fol");
              $up_stok_fol = $stok_fol + $jumlah_meter;
              $up_stok_foly = $up_stok_fol / 0.9144;
              $this->data_model->updatedata('id_stok',$cekstokid,'report_stok',['stok_fol'=>round($up_stok_foly,2), 'stok_fol_yard'=>round($up_stok_fol,2)]);
            }
            $lokasi_produksi = $this->data_model->get_byid('tb_produksi_out',['idpout'=>$id])->row("lokasi_kirim");
            $proseslist = [
              'kode_produksi' =>$kd,
              'tgl' => $tgl,
              'jumlah_awal' => $jumlah_meter,
              'satuan' => $satuan,
              'proses_name' => $pnm,
              'pemroses' => $this->session->userdata('id'),
              'jumlah_akhir' => $jumlah_meter,
              'ch_to' => 0,
              'lokasi_produksi' => ucwords($lokasi_produksi),
              'jumlah_awal_yard' => $jumlah_yard,
              'jumlah_akhir_yard' => $jumlah_yard
            ];
            $this->data_model->saved('tb_proses_produksi',$proseslist);
            $cekstokluar = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kode_kons, 'departement'=>$lokasi_produksi]);
            $idstok = $cekstokluar->row("id_stok");
            $stluar_now = $cekstokluar->row("stok_ins");
            $up_stluar_now = $stluar_now - $jumlah_total;
            $this->data_model->updatedata('id_stok',$idstok,'report_stok',['stok_ins'=>round($up_stluar_now,2)]);
            //log
            $txt = "Telah di folding ".$fol." di ".$lokasi_produksi." dan telah di kembalikan ke ".$dep."";
            $dtlog = [
              'id_user' => $this->session->userdata('id'),
              'kode_produksi' => $kd,
              'log' => $txt
            ];
            $this->data_model->saved('log_produksi',$dtlog);
            
            $this->data_model->updatedata('kode_produksi',$kd,'tb_produksi',['jumlah_produksi_now'=>round($jumlah_meter,2), 'jumlah_produksi_now_yard'=>round($jumlah_yard,2), 'lokasi_saat_ini'=>$dep]);
            $this->session->set_flashdata('announce', 'Proses finish berhasil. Menambahkan sebanyak '.$jumlah_asli_ex.' data ke '.$dep.'.');
            redirect(base_url('pengiriman'));
          } else {
              $this->session->set_flashdata('gagal', 'Format file yang anda masukan salah');
              redirect(base_url('pengiriman'));
          }
        } else {
            $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar.');
            redirect(base_url('pengiriman'));
        }
    } //end

    public function import_newprosif(){
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
        $sheet->setCellValue('B1', "Tanggal Potong"); // Set kolom A1 
        $sheet->setCellValue('C1', "Panjang Sesudah"); // Set kolom A1 
        $sheet->setCellValue('D1', "BS"); // Set kolom A1 
        $sheet->setCellValue('E1', "CRT"); // Set kolom A1 
        $sheet->setCellValue('F1', "BP"); // Set kolom A1 
        $sheet->setCellValue('G1', "Satuan"); // Set kolom A1 
        $sheet->setCellValue('H1', "Operator"); // Set kolom A1 
        $sheet->setCellValue('I1', "Keterangan"); // Set kolom A1
        $sheet->setCellValue('J1', "Kode Konstruksi"); // Set kolom A1
        $sheet->setCellValue('L1', "Note :"); // Set kolom A1
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
        $sheet->getColumnDimension('L')->setAutoSize(true);
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
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
        $sheet->getStyle('L1')->applyFromArray($style_col);
        $sheet->setCellValue('L2', "-Gunakan format tanggal DD-MM-YYYY (Ex : 01-12-2023)");
        $sheet->setCellValue('L3', "-Gunakan format satuan (Yard / Meter)");
        $sheet->setCellValue('L4', "-Kolom satuan bisa di isi hanya 1 pada baris pertama");
        $sheet->setCellValue('L5', "-Jika tanggal di kosongi maka akan otomatis mengikuti");
        $sheet->setCellValue('L6', "tanggal pada baris pertama");
        $sheet->setCellValue('L7', "-Kolom BS,CRT bisa anda kosongi atau di isi dengan angka 0");
        
        $writer = new Xlsx($spreadsheet);
        $filename = 'Format Inspect Finish';
  
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output'); // download file
    } //end

}
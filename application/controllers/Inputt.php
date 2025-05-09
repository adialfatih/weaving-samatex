<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inputt extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
      if($this->session->userdata('login_form') != "rindangjati_sess"){
        redirect(base_url('login'));
      }
      
  }
   
  function index(){ 
      $this->load->view('block');
  } //end

  function konstruksi(){ 
    
        $data = array(
            'title' => 'Data Konstruksi - PPC Weaving',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_dep' => $this->session->userdata('departement'),
            'data_table' => $this->data_model->sort_record('id_konstruksi', 'tb_konstruksi')
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('page/input_konstruksi', $data);
        $this->load->view('part/main_js_dttable');
    
  } //end

  function produksikirim(){
        $dep = $this->session->userdata('departement');
        //$query1 = $this->data_model->get_byid('new_tb_suratjalan', ['departement'=>$dep]);
        $query = $this->db->query("SELECT * FROM surat_jalan WHERE dep_asal='$dep' AND no_sj NOT LIKE '%SLD%' ORDER BY id_sj DESC");
        $data = array(
            'title' => 'Surat Jalan Pengiriman',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_dep' => $dep,
            'dttable' => $query
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('new_page/out_stokview2', $data);
        $this->load->view('part/main_js_dttable');
  } //end

  function hapussj(){
    //$dep = $this->session->userdata('departement');
        $sj = $this->uri->segment(3);
        $dtsj = $this->data_model->get_byid('surat_jalan',['id_sj'=>$sj])->row_array();
        $nosj = $dtsj['no_sj'];
        $tujuan = $dtsj['tujuan_kirim'];
        $dep = $dtsj['dep_asal'];
        $pkg = $this->data_model->get_byid('new_tb_packinglist', ['no_sj'=>$nosj]);
        
            foreach($pkg->result() as $val){
                $kons = $val->kode_konstruksi;
                $konsAsli = $val->kode_konstruksi;
                $panjang = $val->ttl_panjang;
                $kd = $val->kd;
                if($tujuan == "cus"){
                    $tgl = $val->tanggal_dibuat;
                    $roll = $val->jumlah_roll;
                    $jns_fol = $val->jns_fold;
                    $cekKons = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$kons]);
                    if($cekKons->num_rows()==0){
                        $cekKons = $this->data_model->get_byid('tb_konstruksi',['chto'=>$kons]);
                        $kons = $cekKons->row("kode_konstruksi");
                    }
                    $cekpj = $this->data_model->get_byid('data_penjualan',['konstruksi'=>$konsAsli, 'jns_fold'=>$jns_fol, 'tanggal'=>$tgl,]);
                    $newJumlah = floatval($cekpj->row("jumlah_penjualan")) - floatval($panjang);
                    $newRoll = floatval($cekpj->row("jml_roll")) - floatval($roll);
                    $this->data_model->updatedata('idautopen',$cekpj->row('idautopen'),'data_penjualan',['jumlah_penjualan'=>round($newJumlah,2),'jml_roll'=>round($newRoll,2)]);

                    $allkode = $this->data_model->get_byid('new_tb_isi',['kd'=>$kd]);
                    foreach($allkode->result() as $lk):
                        $kode = $lk->kode;
                        $this->data_model->updatedata('kode_roll',$kode,'data_fol',['posisi'=>$kd]);
                    endforeach;

                    $stok = $this->data_model->get_byid('data_stok',['dep'=>'newSamatex','kode_konstruksi'=>$kons]);
                    $idstok = $stok->row("idstok");
                    if($jns_fol=="Grey"){
                        $new_ig = floatval($stok->row("prod_fg")) + floatval($panjang);
                        $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_fg'=>round($new_ig,2)]);
                    } else {
                        $new_ig = floatval($stok->row("prod_ff")) + floatval($panjang);
                        $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ff'=>round($new_ig,2)]);
                    }
                } else {
                    $allkode = $this->data_model->get_byid('new_tb_isi',['kd'=>$kd]);
                    $this->db->query("DELETE FROM new_tb_pkgkepst WHERE kode_pkg='$kd'");
                    $isi_oke = $this->db->query("SELECT * FROM new_tb_isi WHERE kd='$kd'");
                    foreach($isi_oke->result() as $iko){
                        $_koderoll = $iko->kode;
                        $this->db->query("DELETE FROM new_roll_onpst WHERE kode_roll='$_koderoll'");
                    }
                    foreach($allkode->result() as $lk):
                        $kode = $lk->kode;
                        $this->data_model->updatedata('kode_roll',$kode,'data_ig',['loc_now'=>$kd]);
                    endforeach;
                    if($dep=="Samatex"){
                        $stok = $this->data_model->get_byid('data_stok',['dep'=>'stxToPusatex','kode_konstruksi'=>$kons]);
                        $idstok = $stok->row("idstok");
                        $new_ig = floatval($stok->row("prod_ig")) - floatval($panjang);
                        $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($new_ig,2)]);
                        $stok = $this->data_model->get_byid('data_stok',['dep'=>'newSamatex','kode_konstruksi'=>$kons]);
                        $idstok = $stok->row("idstok");
                        $new_ig = floatval($stok->row("prod_ig")) + floatval($panjang);
                        $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($new_ig,2)]);
                    } else {
                        if($tujuan=="Samatex"){
                            $stok = $this->data_model->get_byid('data_stok',['dep'=>'rjsToSamatex','kode_konstruksi'=>$kons]);
                            $idstok = $stok->row("idstok");
                            $new_ig = floatval($stok->row("prod_ig")) - floatval($panjang);
                            $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($new_ig,2)]);
                            $stok = $this->data_model->get_byid('data_stok',['dep'=>'newSamatex','kode_konstruksi'=>$kons]);
                            $idstok = $stok->row("idstok");
                            $new_ig = floatval($stok->row("prod_ig")) - floatval($panjang);
                            $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($new_ig,2)]);
                            $stok = $this->data_model->get_byid('data_stok',['dep'=>'newRJS','kode_konstruksi'=>$kons]);
                            $idstok = $stok->row("idstok");
                            $new_ig = floatval($stok->row("prod_ig")) + floatval($panjang);
                            $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($new_ig,2)]);
                        } else {
                            $stok = $this->data_model->get_byid('data_stok',['dep'=>'rjsToPusatex','kode_konstruksi'=>$kons]);
                            $idstok = $stok->row("idstok");
                            $new_ig = floatval($stok->row("prod_ig")) - floatval($panjang);
                            $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($new_ig,2)]);
                            $stok = $this->data_model->get_byid('data_stok',['dep'=>'newRJS','kode_konstruksi'=>$kons]);
                            $idstok = $stok->row("idstok");
                            $new_ig = floatval($stok->row("prod_ig")) + floatval($panjang);
                            $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($new_ig,2)]);
                        }
                    }
                }
                $kd = $val->kd;
                $this->data_model->updatedata('kd',$kd,'new_tb_packinglist',['kepada'=>'NULL','no_sj'=>'NULL']);
            }
        $this->data_model->delete('surat_jalan','id_sj',$sj);
        redirect(base_url('pengiriman'));
  }
  function konsumen(){ 
    
    $data = array(
        'title' => 'Data Konsumen',
        'sess_nama' => $this->session->userdata('nama'),
        'sess_id' => $this->session->userdata('id'),
        'dt_table' => $this->data_model->sort_record('id_konsumen', 'dt_konsumen')
    );
    $this->load->view('part/main_head', $data);
    $this->load->view('part/left_sidebar', $data);
    $this->load->view('page/input_konsumen', $data);
    $this->load->view('part/main_js_dttable');

} //end

  function promesin(){ 
        $hak = $this->session->userdata('hak');
        $dep = $this->session->userdata('departement');
        $cekdata = $this->db->query('SELECT kode_konstruksi FROM tb_konstruksi')->result();
        $data = array();
        foreach($cekdata as $val): $data [] = '"'.$val->kode_konstruksi.'"'; endforeach;
        $datanew = implode(',', $data);
            $data = array(
                'title' => 'Produksi Mesin - PPC Weaving',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'autocomplet' => 'is',
                'dataauto' => $datanew,
                'dep_user' => $dep,
                'dt_table' => $this->data_model->sort_record('id_produksi_mc', 'dt_produksi_mesin')
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            $this->load->view('page/input_produksi_mesin', $data);
            $this->load->view('part/main_js_dttable');
       
    } //end


  function setkonstruksi(){ 
        $cekdata = $this->db->query('SELECT kode_konstruksi FROM tb_konstruksi')->result();
            $data2 = array();
            foreach($cekdata as $val): $data2 [] = '"'.$val->kode_konstruksi.'"'; endforeach;
            $datanew = implode(',', $data2);
        $jml_konstruksi = count($data2);
        $data = array(
            'title' => 'Data Konstruksi - PPC Weaving',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'data_table' => $this->data_model->sort_record('id_konstruksi', 'tb_konstruksi'),
            'dataauto' => $datanew,
            'autocomplet' => 'is',
            'jml_kons' => $jml_konstruksi
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('page/set_konstruksi', $data);
        $this->load->view('part/main_js_dttable');

    } //end

  function produksi(){ 
        $hak = $this->session->userdata('hak');
        $dep = $this->session->userdata('departement');
        $cekdata = $this->db->query('SELECT kode_konstruksi FROM tb_konstruksi')->result();
        $data = array();
        foreach($cekdata as $val): $data [] = '"'.$val->kode_konstruksi.'"'; endforeach;
        $datanew = implode(',', $data);
        
        //$cek_kode_produksi = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kode_produksi])->num_rows();
        // if($hak=='Manager'){
        //     $query = $this->data_model->sort_record('id_produksi', 'tb_produksi');
        // } else {
            //$query = $this->db->query("SELECT * FROM tb_produksi WHERE lokasi_saat_ini='".$dep."' ORDER BY id_produksi DESC");
        //}
        //if($cek_kode_produksi==0){
            $data = array(
                'title' => 'Data Produksi - PPC Weaving',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'autocomplet' => 'is',
                'dataauto' => $datanew,
                'dep_user' => $dep,
                'dtkons2' => $this->db->query("SELECT * FROM data_produksi_harian WHERE dep='$dep' ORDER BY tgl DESC")
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            // if($hak=='Manager'){
            // $this->load->view('page/input_produksi2', $data);
            // } else {
            $this->load->view('new_page/new_input_produksi', $data);
            //}
            $this->load->view('part/main_js_dttable');
        // } else {
        //     echo "kode produksi sudah ada mohon reload halaman atau pencet f5 pada keyboard";
        // }
  } //end

  function exportproduksi(){
        $data = array(
            'title' => 'Export Produksi',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_dep' => $this->session->userdata('departement'),
            'optsmt' => $this->data_model->get_byid('a_operator', ['dep'=>'Samatex']),
            'optrjs' => $this->data_model->get_byid('a_operator', ['dep'=>'RJS']),
            'daterange' => 'true'
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('page/export_produksi', $data);
        $this->load->view('part/main_js_dttable');
  } //end

  function penjualan2(){ 
        $data = array(
            'title' => 'Data Penjualan - PPC Weaving',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('page/input_penjualan', $data);
        $this->load->view('part/main_js_dttable');
  } //end
  
  function penjualan(){ 
        $hak = $this->session->userdata('hak');
        $dep = $this->session->userdata('departement');
        //$cekdata = $this->db->query('SELECT kode_konstruksi FROM tb_konstruksi')->result();
        //$data = array();
        //foreach($cekdata as $val): $data [] = '"'.$val->kode_konstruksi.'"'; endforeach;
        $datanew = "tes";
            $data = array(
                'title' => 'Data Penjualan',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'autocomplet' => 'is',
                'dataauto' => $datanew,
                'dep_user' => $dep,
                'dtpnj' => $this->db->query("SELECT * FROM `data_penjualan` WHERE YEAR(tanggal) = 2025 ORDER BY tanggal DESC")
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            $this->load->view('page/input_penjualan', $data);
            $this->load->view('part/main_js_dttable');
    
    } //end

  function tes(){
        $hak = $this->session->userdata('hak');
        $dep = $this->session->userdata('departement');
        $cekdata = $this->db->query('SELECT kode_konstruksi FROM tb_konstruksi')->result();
        $data = array();
        foreach($cekdata as $val): $data [] = '"'.$val->kode_konstruksi.'"'; endforeach;
        $datanew = implode(',', $data);
        $data = array(
            'title' => 'Data Penjualan',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'autocomplet' => 'is',
            'dataauto' => $datanew,
            'dep_user' => $dep,
            'kons' => $this->data_model->get_record('dt_konsumen')
        );
        $this->load->view('part/main_head2', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('page/input_penjualan_list', $data);
        $this->load->view('part/main_js_dttable2');
  } //end

  function inputif(){
        $kode_produksi = $this->data_model->acakKode(5);
        $cek_kode_produksi = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kode_produksi])->num_rows();
        if($cek_kode_produksi==0){
        $hak = $this->session->userdata('hak');
        $dep = $this->session->userdata('departement');
        $cekdata = $this->db->query('SELECT kode_konstruksi FROM tb_konstruksi')->result();
        $data = array();
        foreach($cekdata as $val): $data [] = '"'.$val->kode_konstruksi.'"'; endforeach;
        $datanew = implode(',', $data);
            $data = array(
                'title' => 'Input Produksi Inspect Finish',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'autocomplet' => 'is',
                'dataauto' => $datanew,
                'dep_user' => $dep,
                'kdp' => $kode_produksi
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            $this->load->view('page/input_produksi_if', $data);
            $this->load->view('part/main_js_dttable');
        } else {
            $this->session->set_flashdata('gagal', 'Terjadi erorr saat proses produksi, silahkan ulangi proses.');
            redirect(base_url('input-produksi'));
        }
  } //end

  function inputfol(){
        
        $hak = $this->session->userdata('hak');
        $dep = $this->session->userdata('departement');
        $cekdata = $this->db->query('SELECT kode_konstruksi FROM tb_konstruksi')->result();
        $data = array();
        foreach($cekdata as $val): $data [] = '"'.$val->kode_konstruksi.'"'; endforeach;
        $datanew = implode(',', $data);
            $data = array(
                'title' => 'Input Produksi Folding',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'autocomplet' => 'is',
                'dataauto' => $datanew,
                'dep_user' => $dep
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            $this->load->view('page/input_produksi_fol', $data);
            $this->load->view('part/main_js_dttable');
        
  } //end

  function inputgrey(){
        $kode_produksi = $this->data_model->acakKode(5);
        $cek_kode_produksi = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kode_produksi])->num_rows();
        if($cek_kode_produksi==0){
        $hak = $this->session->userdata('hak');
        $dep = $this->session->userdata('departement');
        $cekdata = $this->db->query('SELECT kode_konstruksi FROM tb_konstruksi')->result();
        $data = array();
        foreach($cekdata as $val): $data [] = '"'.$val->kode_konstruksi.'"'; endforeach;
        $datanew = implode(',', $data);
            $data = array(
                'title' => 'Input Produksi Inspect Grey',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_id' => $this->session->userdata('id'),
                'autocomplet' => 'is',
                'dataauto' => $datanew,
                'dep_user' => $dep,
                'kdp' => $kode_produksi
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar', $data);
            $this->load->view('page/input_produksi_insgrey', $data);
            $this->load->view('part/main_js_dttable');
        } else {
            $this->session->set_flashdata('gagal', 'Terjadi erorr saat proses produksi, silahkan ulangi proses.');
            redirect(base_url('input-produksi'));
        }
  } //end

    function proses_produksi(){
        $dep = $this->session->userdata('departement');
        $data = array(
            'title' => 'Input Produksi Inspect Grey',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'dep_user' => $dep,
            'acakkode' => $this->data_model->acakKode(6)
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('page/proses_produksi', $data);
        $this->load->view('part/main_js');
   
    } //end

    function create_pkg(){
        $cekdata = $this->db->query('SELECT * FROM tb_konstruksi')->result();
        $data = array();
        foreach($cekdata as $val): $data [] = '"'.$val->kode_konstruksi.'"'; endforeach;
        foreach($cekdata as $val):
            if($val->chto != "NULL"){
                $data [] = '"'.$val->chto.'"';
            }
        endforeach;
        $datanew = implode(',', $data);
        $dep = $this->session->userdata('departement');
        $cek_pkg = $this->db->query("SELECT * FROM new_tb_packinglist WHERE lokasi_now='$dep' ORDER BY id_kdlist DESC LIMIT 1");
        if($cek_pkg->num_rows()==1){
            if($dep=="Samatex"){ 
                $kd = explode('G', $cek_pkg->row("kd")); $new_number = $kd[1] + 1; $new_kd = "PKG".$new_number."";
            } else { 
                $kd = explode('JS', $cek_pkg->row("kd")); $new_number = $kd[1] + 1; $new_kd = "RJS".$new_number.""; 
            }
        } else {
            if($dep=="Samatex"){ $new_kd = "PKG1"; } else { $new_kd = "RJ1"; }
        }   
        $data = array(
            'title' => 'Create Packinglist',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'dep_user' => $dep,
            'autocomplet' => 'is',
            'dataauto' => $datanew,
            'kd' => $new_kd
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('new_page/create_pkg_list', $data);
        $this->load->view('part/main_js_dttable');
   
    } //end

    function insert_pkg(){
        $no_paket = $this->data_model->filter($this->input->post('nopkt'));
        $kd_kons = $this->data_model->filter($this->input->post('kode'));
        $tgl = $this->data_model->filter($this->input->post('tgl'));
        $loc = $this->data_model->filter($this->input->post('loc'));
        $jns = $this->data_model->filter($this->input->post('jnspkt'));
        if($no_paket!="" AND $kd_kons!="" AND $tgl!="" AND $loc!="" AND $jns!=""){
            $cek_nopaket = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$no_paket])->num_rows();
            if($cek_nopaket==0){
            $dtlist = [ 'kode_konstruksi' => $kd_kons, 'kd' => $no_paket, 'tanggal_dibuat' => $tgl, 'lokasi_now' => $loc, 'siap_jual' => $jns, 'jumlah_roll' => '0', 'ttl_panjang' => '0', 'kepada' => 'NULL', 'no_sj' => 'NULL' ];
            $this->data_model->saved('new_tb_packinglist', $dtlist); }
            $dt_insert = "true";
        } else {
            $dt_insert = "false";
        }
        $dep = $this->session->userdata('departement');
        $data = array(
            'title' => 'Insert Roll to Packinglist',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'dep_user' => $loc,
            'dt_insert' => $dt_insert,
            'kd_kons' => $kd_kons,
            'no_paket' => $no_paket,
            'jns_paket' => $jns
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('new_page/insert_pkg_list', $data);
        $this->load->view('part/main_js_dttable');
   
    } //end

    
}
?>
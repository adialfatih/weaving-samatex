<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produksistx extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
    //   if($this->session->userdata('login_form') != "rindangjati_sess"){
    //     redirect(base_url('login'));
    //   }
      
  }
   
  function index(){ 
      $this->load->view('block');
  } //end
  function delinsgrey(){
        $id_data = $this->uri->segment(3);
        $konstruksi = $dt['konstruksi'];
        $ukuran = $dt['ukuran_ori'];
        $bs = $dt['ukuran_bs'];
        $bp = $dt['ukuran_bp'];
        $tgl = $dt['tanggal'];
        $operator = $dt['operator'];
        $dep = $dt['dep'];
        $stokdep = "new".$dep."";
        //del produksi 
        $prod = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$konstruksi, 'tgl'=>$tgl, 'dep'=>$dep])->row_array();
        $id_prod = $prod['id_produksi'];
        $thisig = floatval($prod['prod_ig']) - floatval($ukuran);
        $thisbs = floatval($prod['prod_bs1']) - floatval($bs);
        $thisbp = floatval($prod['prod_bp1']) - floatval($bp);
        $this->data_model->updatedata('id_produksi',$id_prod,'data_produksi',['prod_ig'=>round($thisig,2),'prod_bs1'=>round($thisbs,2),'prod_bp1'=>round($thisbp,2)]);
        //del produksi harian
        $prod = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl, 'dep'=>$dep])->row_array();
        $id_prod = $prod['id_prod_hr'];
        $thisig = floatval($prod['prod_ig']) - floatval($ukuran);
        $thisbs = floatval($prod['prod_bs1']) - floatval($bs);
        $thisbp = floatval($prod['prod_bp1']) - floatval($bp);
        $this->data_model->updatedata('id_prod_hr',$id_prod,'data_produksi_harian',['prod_ig'=>round($thisig,2),'prod_bs1'=>round($thisbs,2),'prod_bp1'=>round($thisbp,2)]);
        //del produksi operator
        $prod = $this->data_model->get_byid('data_produksi_opt', ['username_opt'=>$operator,'konstruksi'=>$konstruksi,'tgl'=>$tgl,'proses'=>'insgrey'])->row_array();
        $id_prod = $prod['id_propt'];
        $thisig = floatval($prod['ukuran']) - floatval($ukuran);
        $thisbs = floatval($prod['bs']) - floatval($bs);
        $thisbp = floatval($prod['bp']) - floatval($bp);
        $this->data_model->updatedata('id_propt',$id_prod,'data_produksi_opt',['ukuran'=>round($thisig,2),'bs'=>round($thisbs,2),'bp'=>round($thisbp,2)]);
        //del stok
        $stok = $this->data_model->get_byid('data_stok', ['dep'=>$stokdep, 'kode_konstruksi'=>$konstruksi])->row_array();
        $idstok = $stok['idstok'];
        $thisig = floatval($stok['prod_ig']) - floatval($ukuran);
        $thisbs = floatval($stok['prod_bs1']) - floatval($bs);
        $thisbp = floatval($stok['prod_bp1']) - floatval($bp);
        $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($thisig,2),'prod_bs1'=>round($thisbs,2),'prod_bp1'=>round($thisbp,2)]);
        $this->db->query("DELETE FROM data_ig WHERE id_data='$id_data'");
        redirect(base_url('users/insgrey'));
  } //end 

  function prosesupdate(){
        $kd = $this->data_model->filter($this->input->post('koderoll'));
        $in_kons = $this->data_model->filter($this->input->post('kode'));
        $in_mc = $this->data_model->filter($this->input->post('mc'));
        $in_beam = $this->data_model->filter($this->input->post('beam'));
        $in_oka = $this->data_model->filter($this->input->post('oka'));
        $in_ukuran = $this->data_model->filter($this->input->post('ukuran'));
        $in_bs = $this->data_model->filter($this->input->post('bs'));
        $in_bp = $this->data_model->filter($this->input->post('bp'));
        //proses hapus-----------
        $dt = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kd])->row_array();
        $konstruksi = $dt['konstruksi'];
        $ukuran = $dt['ukuran_ori'];
        $bs = $dt['ukuran_bs'];
        $bp = $dt['ukuran_bp'];
        $tgl = $dt['tanggal'];
        $operator = $dt['operator'];
        $dep = $dt['dep'];
        $depnow = $dt['loc_now'];
        $stokdep = "new".$dep."";
        //del produksi 
        $prod = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$konstruksi, 'tgl'=>$tgl, 'dep'=>$dep])->row_array();
        $id_prod = $prod['id_produksi'];
        $thisig = floatval($prod['prod_ig']) - floatval($ukuran);
        $thisbs = floatval($prod['prod_bs1']) - floatval($bs);
        $thisbp = floatval($prod['prod_bp1']) - floatval($bp);
        $this->data_model->updatedata('id_produksi',$id_prod,'data_produksi',['prod_ig'=>round($thisig,2),'prod_bs1'=>round($thisbs,2),'prod_bp1'=>round($thisbp,2)]);
        //del produksi harian
        $prod = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl, 'dep'=>$dep])->row_array();
        $id_prod = $prod['id_prod_hr'];
        $thisig = floatval($prod['prod_ig']) - floatval($ukuran);
        $thisbs = floatval($prod['prod_bs1']) - floatval($bs);
        $thisbp = floatval($prod['prod_bp1']) - floatval($bp);
        $this->data_model->updatedata('id_prod_hr',$id_prod,'data_produksi_harian',['prod_ig'=>round($thisig,2),'prod_bs1'=>round($thisbs,2),'prod_bp1'=>round($thisbp,2)]);
        //del produksi operator
        $prod = $this->data_model->get_byid('data_produksi_opt', ['username_opt'=>$operator,'konstruksi'=>$konstruksi,'tgl'=>$tgl,'proses'=>'insgrey'])->row_array();
        $id_prod = $prod['id_propt'];
        $thisig = floatval($prod['ukuran']) - floatval($ukuran);
        $thisbs = floatval($prod['bs']) - floatval($bs);
        $thisbp = floatval($prod['bp']) - floatval($bp);
        $this->data_model->updatedata('id_propt',$id_prod,'data_produksi_opt',['ukuran'=>round($thisig,2),'bs'=>round($thisbs,2),'bp'=>round($thisbp,2)]);
        //del stok
        $stok = $this->data_model->get_byid('data_stok', ['dep'=>$stokdep, 'kode_konstruksi'=>$konstruksi])->row_array();
        $idstok = $stok['idstok'];
        $thisig = floatval($stok['prod_ig']) - floatval($ukuran);
        $thisbs = floatval($stok['prod_bs1']) - floatval($bs);
        $thisbp = floatval($stok['prod_bp1']) - floatval($bp);
        $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($thisig,2),'prod_bs1'=>round($thisbs,2),'prod_bp1'=>round($thisbp,2)]);
        //$this->db->query("DELETE FROM data_ig WHERE kode_roll='$kd'");
        // setelah di hapus mari tambahkan lagi datanya.. 
        $dtlist = [
            
            'konstruksi' => $in_kons,
            'no_mesin' => $in_mc,
            'no_beam' => $in_beam,
            'oka' => $in_oka,
            'ukuran_ori' => $in_ukuran,
            'ukuran_bs' => $in_bs,
            'ukuran_bp' => $in_bp
            
        ];
        $this->data_model->updatedata('kode_roll',$kd,'data_ig', $dtlist);
        //cek produksi per sm harian
        $cek1 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$in_kons,'tgl'=>$tgl,'dep'=>$dep]);
        if($cek1->num_rows() == 1){
                $id_produksi = $cek1->row("id_produksi");
                $new_ig = floatval($cek1->row("prod_ig")) + floatval($in_ukuran);
                $new_bs = floatval($cek1->row("prod_bs1")) + floatval($in_bs);
                $new_bp = floatval($cek1->row("prod_bp1")) + floatval($ins_bp);
                $dtlist1 = [
                    'prod_ig' => round($new_ig,2),
                    'prod_bs1' => round($new_bs,2),
                    'prod_bp1' => round($new_bp,2)
                ];
                $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',$dtlist1);
        } else {
            $dtlist1 = [
                'kode_konstruksi' => $in_kons,
                'tgl' => $tgl,
                'dep' => $dep,
                'prod_ig' => $in_ukuran,
                'prod_fg' => 0,
                'prod_if' => 0,
                'prod_ff' => 0,
                'prod_bs1' => $in_bs,
                'prod_bp1' => $in_bp,
                'prod_bs2' => 0,
                'prod_bp2' => 0,
                'crt' => 0
            ];
            $this->data_model->saved('data_produksi', $dtlist1);
        }
        //end cek 1
        //cek produksi harian total
        $cek2 = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl,'dep'=>$dep]);
        if($cek2->num_rows() == 1){
                $id_prod_hr = $cek2->row("id_prod_hr");
                $new_ig = floatval($cek2->row("prod_ig")) + floatval($in_ukuran);
                $new_bs = floatval($cek2->row("prod_bs1")) + floatval($in_bs);
                $new_bp = floatval($cek2->row("prod_bp1")) + floatval($in_bp);
                $dtlist1 = [
                    'prod_ig' => round($new_ig,2),
                    'prod_bs1' => round($new_bs,2),
                    'prod_bp1' => round($new_bp,2)
                ];
                $this->data_model->updatedata('id_prod_hr',$id_prod_hr,'data_produksi_harian',$dtlist1);
        } else {
            $dtlist1 = [
                'tgl' => $tgl,
                'dep' => $dep,
                'prod_ig' => $in_ukuran,
                'prod_fg' => 0,
                'prod_if' => 0,
                'prod_ff' => 0,
                'prod_bs1' => $in_bs,
                'prod_bp1' => $in_bp,
                'prod_bs2' => 0,
                'prod_bp2' => 0,
                'crt' => 0
            ];
            $this->data_model->saved('data_produksi_harian', $dtlist1);
        }
        //end cek 2
        //cek produksi opt
        $cek3 = $this->data_model->get_byid('data_produksi_opt', ['username_opt'=>$operator,'konstruksi'=>$in_kons,'tgl'=>$tgl,'proses'=>'insgrey']);
        if($cek3->num_rows() == 1){
            $id_propt = $cek3->row("id_propt");
            $new_ori = floatval($cek3->row("ukuran")) + floatval($in_ukuran);
            $new_bs = floatval($cek3->row("bs")) + floatval($in_bs);
            $new_bp = floatval($cek3->row("bp")) + floatval($in_bp);
            $dtlist2 = [
                'ukuran' => round($new_ori,2),
                'bs' => round($new_bs,2),
                'bp' => round($new_bp,2)
            ];
            $this->data_model->updatedata('id_propt',$id_propt,'data_produksi_opt',$dtlist2);
        } else {
            $dtlist2 = [
                'username_opt' => $operator,
                'konstruksi' => $in_kons,
                'tgl' => $tgl,
                'proses' => 'insgrey',
                'ukuran' => $in_ukuran, 
                'bs' => $in_bs,
                'bp' => $in_bp,
                'crt' => 0
            ];
            $this->data_model->saved('data_produksi_opt', $dtlist2);
        }
        //end cek 3
        //cek data stok ke 4
        $cekStok = $this->data_model->get_byid('data_stok', ['dep'=>$stokdep,'kode_konstruksi'=>$in_kons]);
        if($cekStok->num_rows() == 0){
            $listStok = [
                'dep' => $stokdep,
                'kode_konstruksi' => $in_kons,
                'prod_ig' => $in_ukuran,
                'prod_fg' => 0,
                'prod_if' => 0,
                'prod_ff' => 0,
                'prod_bs1' => $in_bs,
                'prod_bp1' => $in_bp,
                'prod_bs2' => 0,
                'prod_bp2' => 0,
                'crt' => 0
            ];
            $this->data_model->saved('data_stok', $listStok);
        } else {
            $idstok = $cekStok->row("idstok");
            $newig = floatval($cekStok->row("prod_ig")) + floatval($in_ukuran);
            $newbs = floatval($cekStok->row("prod_bs1")) + floatval($in_bs);
            $newbp = floatval($cekStok->row("prod_bp1")) + floatval($in_bp);
            $listStok = [
                'prod_ig' => round($newig,2),
                'prod_bs1' => round($newbs,2),
                'prod_bp1' => round($newbp,2)
            ];
            $this->data_model->updatedata('idstok',$idstok,'data_stok',$listStok);
        }
        //end cek 4
        redirect(base_url('produksi-insgrey/'.$tgl));
  } //edn

  function delfolgrey(){
        $idfol = $this->input->post('iddata');
        $dtfol = $this->data_model->get_byid('data_fol', ['id_fol'=>$idfol])->row_array();
        $kode_roll = $dtfol['kode_roll'];
        $kons = $dtfol['konstruksi'];
        $ukuran = $dtfol['ukuran'];
        $jnsfol = $dtfol['jns_fold'];
        $tgl = $dtfol['tgl'];
        $operator = $dtfol['operator'];
        //hapus dulu data produksi
        $produksi = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kons,'tgl'=>$tgl,'dep'=>'Samatex'])->row_array();
        $id_produksi = $produksi['id_produksi'];
        if($jnsfol == "Grey"){
            $newFol = floatval($produksi['prod_fg']) - floatval($ukuran);
            $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',['prod_fg'=>round($newFol,2)]);
        } else {
            $newFol = floatval($produksi['prod_ff']) - floatval($ukuran);
            $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',['prod_ff'=>round($newFol,2)]);
        }
        //hapus produksi harian total
        $produksi = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl,'dep'=>'Samatex'])->row_array();
        $id_produksi = $produksi['id_prod_hr'];
        if($jnsfol == "Grey"){
            $newFol = floatval($produksi['prod_fg']) - floatval($ukuran);
            $this->data_model->updatedata('id_prod_hr',$id_produksi,'data_produksi_harian',['prod_fg'=>round($newFol,2)]);
        } else {
            $newFol = floatval($produksi['prod_ff']) - floatval($ukuran);
            $this->data_model->updatedata('id_prod_hr',$id_produksi,'data_produksi_harian',['prod_ff'=>round($newFol,2)]);
        }
        //hapus produksi operator tersebut
        if($jnsfol=="Grey"){
            $cekopt = $this->data_model->get_byid('data_produksi_opt', ['username_opt'=>$operator,'konstruksi'=>$kons,'tgl'=>$tgl,'proses'=>'folgrey']);
        } else {
            $cekopt = $this->data_model->get_byid('data_produksi_opt', ['username_opt'=>$operator,'konstruksi'=>$kons,'tgl'=>$tgl,'proses'=>'folfinish']);
        }
        $id_propt = $cekopt->row("id_propt");
        $newUkuran = floatval($cekopt->row("ukuran")) - floatval($ukuran);
        $this->data_model->updatedata('id_propt',$id_propt,'data_produksi_opt',['ukuran'=>round($newUkuran,2)]);
        //update stok 
        $stok = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex','kode_konstruksi'=>$kons]);
        $idstok = $stok->row("idstok");
        if($jnsfol=="Grey"){
            $ukuran_ig = $this->data_model->get_byid('data_ig',['kode_roll'=>$kode_roll])->row("ukuran_ori");
            $prod_ig = floatval($stok->row("prod_ig")) + floatval($ukuran_ig);
            $prod_fg = floatval($stok->row("prod_fg")) - floatval($ukuran);
            $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($prod_ig,2),'prod_fg'=>round($prod_fg,2)]);
        } else {
            $ukuran_if = $this->data_model->get_byid('data_if',['kode_roll'=>$kode_roll])->row("ukuran_ori");
            $prod_ig = floatval($stok->row("prod_if")) + floatval($ukuran_if);
            $prod_fg = floatval($stok->row("prod_ff")) - floatval($ukuran);
            $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_if'=>round($prod_ig,2),'prod_ff'=>round($prod_fg,2)]);
        }
        $this->data_model->delete('data_fol','id_fol',$idfol);
        echo json_encode(array("statusCode"=>200, "psn"=>$kode_roll));
  }

  function insgrey(){
        $cekdata = $this->db->query('SELECT kode_konstruksi FROM tb_konstruksi')->result();
        $data = array();
        foreach($cekdata as $val): $data [] = '"'.$val->kode_konstruksi.'"'; endforeach;
        $datanew = implode(',', $data);
        $data = array(
            'title' => 'Data Produksi Inspect Grey',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_dep' => $this->session->userdata('departement'),
            'data_table' => $this->data_model->sort_record('id_konstruksi', 'tb_konstruksi'),
            'autocomplet' => 'is',
            'dataauto' => $datanew
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('produksi/insgrey_produksi', $data);
        $this->load->view('part/main_js_dttable3');
    
  } //end

  function folgrey(){
        $cekdata = $this->db->query('SELECT kode_konstruksi FROM tb_konstruksi')->result();
        $data = array();
        foreach($cekdata as $val): $data [] = '"'.$val->kode_konstruksi.'"'; endforeach;
        $datanew = implode(',', $data);
        $data = array(
            'title' => 'Data Produksi Folding Grey',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_dep' => $this->session->userdata('departement'),
            'data_table' => $this->data_model->sort_record('id_konstruksi', 'tb_konstruksi'),
            'autocomplet' => 'is',
            'datafol' => 'Grey',
            'dataauto' => $datanew
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('produksi/folgrey_produksi', $data);
        $this->load->view('part/main_js_dttable3');

  } //end

  function folfinish(){
        $cekdata = $this->db->query('SELECT kode_konstruksi FROM tb_konstruksi')->result();
        $data = array();
        foreach($cekdata as $val): $data [] = '"'.$val->kode_konstruksi.'"'; endforeach;
        $datanew = implode(',', $data);
        $data = array(
            'title' => 'Data Produksi Folding Finish',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_dep' => $this->session->userdata('departement'),
            'data_table' => $this->data_model->sort_record('id_konstruksi', 'tb_konstruksi'),
            'autocomplet' => 'is',
            'datafol' => 'Finish',
            'dataauto' => $datanew
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('produksi/folgrey_produksi', $data);
        $this->load->view('part/main_js_dttable3');

  } //end
  

  function insfinish(){
    $cekdata = $this->db->query('SELECT kode_konstruksi FROM tb_konstruksi')->result();
    $data = array();
    foreach($cekdata as $val): $data [] = '"'.$val->kode_konstruksi.'"'; endforeach;
    $datanew = implode(',', $data);
    $data = array(
        'title' => 'Data Produksi Folding Finish',
        'sess_nama' => $this->session->userdata('nama'),
        'sess_id' => $this->session->userdata('id'),
        'sess_dep' => $this->session->userdata('departement'),
        'data_table' => $this->data_model->sort_record('id_konstruksi', 'tb_konstruksi'),
        'autocomplet' => 'is',
        'datafol' => 'Finish',
        'dataauto' => $datanew
    );
    $this->load->view('part/main_head', $data);
    $this->load->view('part/left_sidebar', $data);
    $this->load->view('produksi/insfinish_produksi', $data);
    $this->load->view('part/main_js_dttable3');

} //end

    function delinsfinish(){
        $id_infs = $this->input->post('iddata');
        //echo $id_infs;
        $dt = $this->data_model->get_byid('data_if',['id_infs'=>$id_infs])->row_array();
        $kode_roll = $dt['kode_roll'];
        $kons = $dt['konstruksi'];
        $tgl = $dt['tgl_potong'];
        $ukuran = $dt['ukuran_ori'];
        $bs = $dt['ukuran_bs'];
        $bp = $dt['ukuran_bp'];
        $crt = $dt['ukuran_crt'];
        $ukr_seblm = $dt['ukuran_sebelum'];
        $operator = $dt['operator'];
        $idfol = $this->input->post('kdroll');
            //hapus dulu data produksi
            $produksi = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$kons,'tgl'=>$tgl,'dep'=>'Samatex'])->row_array();
            $id_produksi = $produksi['id_produksi'];
            
                $newif = floatval($produksi['prod_if']) - floatval($ukuran);
                $newcrt = floatval($produksi['crt']) - floatval($crt);
                $newbp = floatval($produksi['prod_bp2']) - floatval($bp);
                $newbs = floatval($produksi['prod_bs2']) - floatval($bs);
                $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',['prod_if'=>round($newif,2), 'prod_bs2'=>round($newbs,2), 'prod_bp2'=>round($newbp,2), 'crt'=>round($newcrt,2)]);
            
            //hapus produksi harian total
            $produksi = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl,'dep'=>'Samatex'])->row_array();
            $id_produksi = $produksi['id_prod_hr'];
            
                $newif = floatval($produksi['prod_if']) - floatval($ukuran);
                $newcrt = floatval($produksi['crt']) - floatval($crt);
                $newbp = floatval($produksi['prod_bp2']) - floatval($bp);
                $newbs = floatval($produksi['prod_bs2']) - floatval($bs);
                $this->data_model->updatedata('id_prod_hr',$id_produksi,'data_produksi_harian',['prod_if'=>round($newif,2), 'prod_bs2'=>round($newbs,2), 'prod_bp2'=>round($newbp,2), 'crt'=>round($newcrt,2)]);
            
            //hapus produksi operator tersebut
            
                $cekopt = $this->data_model->get_byid('data_produksi_opt', ['username_opt'=>$operator,'konstruksi'=>$kons,'tgl'=>$tgl,'proses'=>'insfinish']);
            
            $id_propt = $cekopt->row("id_propt");
            $newUkuran = floatval($cekopt->row("ukuran")) - floatval($ukuran);
            $newbs = floatval($cekopt->row("bs")) - floatval($bs);
            $newbp = floatval($cekopt->row("bp")) - floatval($bp);
            $newcrt = floatval($cekopt->row("crt")) - floatval($crt);
            $this->data_model->updatedata('id_propt',$id_propt,'data_produksi_opt',['ukuran'=>round($newUkuran,2), 'bs'=>round($newbs),'bp'=>round($newbp,2),'crt'=>round($newcrt,2)]);
            //update stok 
            $stok = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex','kode_konstruksi'=>$kons]);
            $idstok = $stok->row("idstok");
            
                $ukuran_ig = $this->data_model->get_byid('data_ig',['kode_roll'=>$kode_roll])->row("ukuran_ori");
                $prod_ig = floatval($stok->row("prod_ig")) + floatval($ukuran_ig);
                $prod_if = floatval($stok->row("prod_if")) - floatval($ukuran);
                $bs2 = floatval($stok->row("prod_bs2")) - floatval($bs);
                $bp2 = floatval($stok->row("prod_bp2")) - floatval($bp);
                $crt2 = floatval($stok->row("crt")) - floatval($crt);
                $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($prod_ig,2),'prod_if'=>round($prod_if,2),'prod_bs2'=>round($bs2,2),'prod_bp2'=>round($bp2,2),'crt'=>round($crt,2)]);
            
            $this->data_model->delete('data_if','kode_roll',$id_infs);
            echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
    } //end

}
?>
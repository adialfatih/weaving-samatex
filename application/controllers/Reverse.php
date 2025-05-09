<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reverse extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
  }
   
  function index(){
      echo "Not Index...";
  }
  
  function produksi(){
        $produksi = $this->input->post('reverse');
        $tgl = $this->input->post('tgl');
        $dep = $this->session->userdata('departement');
        if($produksi=="ig"){
            //proses reverse inspect grey
            $query = $this->data_model->get_byid('data_ig',['tanggal'=>$tgl,'dep'=>$dep]);
            foreach($query->result() as $val):
                $konstruksi = $val->konstruksi;
                $ukuran_ori = $val->ukuran_ori;
                $ukuran_bs = $val->ukuran_bs;
                $ukuran_bp = $val->ukuran_bp;
                $stok1 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$konstruksi, 'tgl'=>$tgl, 'dep'=>$dep]);
                $id_produksi = $stok1->row("id_produksi");
                $new_ori = floatval($stok1->row("prod_ig")) - floatval($ukuran_ori);
                $new_bs = floatval($stok1->row("prod_bs1")) - floatval($ukuran_bs);
                $new_bp = floatval($stok1->row("prod_bp1")) - floatval($ukuran_bp);
                $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',['prod_ig'=>round($new_ori,2),'prod_bs1'=>round($new_bs,2),'prod_bp1'=>round($new_bp,2)]);

                $stok2 = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl, 'dep'=>$dep]);
                $id_prod_hr = $stok2->row("id_prod_hr");
                $new_ori2 = floatval($stok2->row("prod_ig")) - floatval($ukuran_ori);
                $new_bs2 = floatval($stok2->row("prod_bs1")) - floatval($ukuran_bs);
                $new_bp2 = floatval($stok2->row("prod_bp1")) - floatval($ukuran_bp);
                $this->data_model->updatedata('id_prod_hr',$id_prod_hr,'data_produksi_harian',['prod_ig'=>round($new_ori2,2),'prod_bs1'=>round($new_bs2,2),'prod_bp1'=>round($new_bp2,2)]);

                $stok3 = $this->data_model->get_byid('data_stok', ['dep'=>$dep,'kode_konstruksi'=>$konstruksi]);
                $idstok = $stok3->row("idstok");
                $new_ori3 = floatval($stok3->row("prod_ig")) - floatval($ukuran_ori);
                $new_bs3 = floatval($stok3->row("prod_bs1")) - floatval($ukuran_bs);
                $new_bp3 = floatval($stok3->row("prod_bp1")) - floatval($ukuran_bp);
                $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($new_ori3,2),'prod_bs1'=>round($new_bs3,2),'prod_bp1'=>round($new_bp3,2)]);

            endforeach;
            $this->data_model->del_multi('data_ig',['tanggal'=>$tgl,'dep'=>$dep]);
            //end proses reverse inspect grey
        }
        if($produksi=="fg"){
            //proses reverse folding grey
            $this->db->query("DELETE FROM temp_upload_fol WHERE folding='Grey' AND tgl='$tgl'");
            $query = $this->data_model->get_byid('data_fol',['jns_fold'=>'Grey','tgl'=>$tgl,'loc'=>$dep]);
            foreach($query->result() as $val):
                $konstruksi = $val->konstruksi;
                $ukuran = $val->ukuran;

                $stok1 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$konstruksi, 'tgl'=>$tgl, 'dep'=>$dep]);
                $id_produksi = $stok1->row("id_produksi");
                $new_fg = floatval($stok1->row("prod_fg")) - floatval($ukuran);
                $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',['prod_fg'=>round($new_fg,2)]);

                $stok2 = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl, 'dep'=>$dep]);
                $id_prod_hr = $stok2->row("id_prod_hr");
                $new_fg2 = floatval($stok2->row("prod_fg")) - floatval($ukuran);
                $this->data_model->updatedata('id_prod_hr',$id_prod_hr,'data_produksi_harian',['prod_fg'=>round($new_fg2,2)]);

                $stok3 = $this->data_model->get_byid('data_stok', ['dep'=>$dep,'kode_konstruksi'=>$konstruksi]);
                $idstok = $stok3->row("idstok");
                $prod_fg3 = floatval($stok3->row("prod_fg")) - floatval($ukuran);
                $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_fg'=>round($prod_fg3,2)]);

            endforeach;
            $this->data_model->del_multi('data_fol',['jns_fold'=>'Grey','tgl'=>$tgl,'loc'=>$dep]);

            $query = $this->data_model->get_byid('data_fol_lama',['folding'=>'Grey','lokasi'=>$dep,'tanggal'=>$tgl]);
            foreach($query->result() as $val):
                $konstruksi = $val->konstruksi;
                $ukuran = $val->ukuran_asli;

                $stok1 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$konstruksi, 'tgl'=>$tgl, 'dep'=>$dep]);
                $id_data = $stok1->row("id_produksi");
                $new_fg = floatval($stok1->row("prod_fg")) - floatval($ukuran);
                $this->data_model->updatedata('id_produksi',$id_data,'data_produksi',['prod_fg'=>round($new_fg,2)]);

                $stok2 = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl, 'dep'=>$dep]);
                $id_prod_hr = $stok2->row("id_prod_hr");
                $new_fg2 = floatval($stok2->row("prod_fg")) - floatval($ukuran);
                $this->data_model->updatedata('id_prod_hr',$id_prod_hr,'data_produksi_harian',['prod_fg'=>round($new_fg2,2)]);

            endforeach;
            $this->data_model->del_multi('data_fol_lama',['folding'=>'Grey','lokasi'=>$dep,'tanggal'=>$tgl]);
            //end proses reverse folding grey
        }
        if($produksi=="iff"){
            //proses reverse inspect finish
            $this->db->query("DELETE FROM temp_upload_if WHERE tgl='$tgl'");
            $query = $this->data_model->get_byid('data_if',['tgl_potong'=>$tgl]);
            foreach($query->result() as $val):
                $konstruksi = $val->konstruksi;
                $ukuran = $val->ukuran_ori;
                $ukuran_bs = $val->ukuran_bs;
                $ukuran_bp = $val->ukuran_bp;
                $ukuran_crt = $val->ukuran_crt;

                $stok1 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$konstruksi, 'tgl'=>$tgl, 'dep'=>$dep]);
                $id_produksi = $stok1->row("id_produksi");
                $new_if = floatval($stok1->row("prod_if")) - floatval($ukuran);
                $new_bs = floatval($stok1->row("prod_bs2")) - floatval($ukuran_bs);
                $new_bp = floatval($stok1->row("prod_bp2")) - floatval($ukuran_bp);
                $new_crt = floatval($stok1->row("crt")) - floatval($ukuran_crt);
                $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',['prod_if'=>0,'prod_bs2'=>0,'prod_bp2'=>0,'crt'=>0]);

                $stok2 = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl, 'dep'=>$dep]);
                $id_prod_hr = $stok2->row("id_prod_hr");
                $new_if2 = floatval($stok2->row("prod_if")) - floatval($ukuran);
                $new_bs2 = floatval($stok2->row("prod_bs2")) - floatval($ukuran_bs);
                $new_bp2 = floatval($stok2->row("prod_bp2")) - floatval($ukuran_bp);
                $new_crt2 = floatval($stok2->row("crt")) - floatval($ukuran_crt);
                $this->data_model->updatedata('id_prod_hr',$id_prod_hr,'data_produksi_harian',['prod_if'=>0,'prod_bs2'=>0,'prod_bp2'=>0,'crt'=>0]);

                $stok3 = $this->data_model->get_byid('data_stok', ['dep'=>$dep,'kode_konstruksi'=>$konstruksi]);
                $idstok = $stok3->row("idstok");
                $new_if3 = floatval($stok3->row("prod_if")) - floatval($ukuran);
                $new_ig3 = floatval($stok3->row("prod_ig")) + floatval($ukuran);
                $new_bs3 = floatval($stok3->row("prod_bs2")) - floatval($ukuran_bs);
                $new_bp3 = floatval($stok3->row("prod_bp2")) - floatval($ukuran_bp);
                $new_crt3 = floatval($stok3->row("crt")) - floatval($ukuran_crt);
                $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_ig'=>round($new_ig3,2),'prod_if'=>round($new_if3,2),'prod_bs2'=>round($new_bs3),'prod_bp2'=>round($new_bp3,2),'crt'=>round($new_crt3,2)]);

            endforeach;
            $this->data_model->del_multi('data_if',['tgl_potong'=>$tgl]);

            $query = $this->data_model->get_byid('data_if_lama',['tgl'=>$tgl]);
            foreach($query->result() as $val):
                $konstruksi = $val->kodekons;
                $ukuran = $val->panjang;
                $ukuran_bs = $val->bs;
                $ukuran_bp = $val->bp;
                $ukuran_crt = $val->crt;

                $stok1 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$konstruksi, 'tgl'=>$tgl, 'dep'=>$dep]);
                $id_produksi = $stok1->row("id_produksi");
                $new_if = floatval($stok1->row("prod_if")) - floatval($ukuran);
                $new_bs = floatval($stok1->row("prod_bs2")) - floatval($ukuran_bs);
                $new_bp = floatval($stok1->row("prod_bp2")) - floatval($ukuran_bp);
                $new_crt = floatval($stok1->row("crt")) - floatval($ukuran_crt);
                $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',['prod_if'=>0,'prod_bs2'=>0,'prod_bp2'=>0,'crt'=>0]);

                $stok2 = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl, 'dep'=>$dep]);
                $id_prod_hr = $stok2->row("id_prod_hr");
                $new_if2 = floatval($stok2->row("prod_if")) - floatval($ukuran);
                $new_bs2 = floatval($stok2->row("prod_bs2")) - floatval($ukuran_bs);
                $new_bp2 = floatval($stok2->row("prod_bp2")) - floatval($ukuran_bp);
                $new_crt2 = floatval($stok2->row("crt")) - floatval($ukuran_crt);
                $this->data_model->updatedata('id_prod_hr',$id_prod_hr,'data_produksi_harian',['prod_if'=>0,'prod_bs2'=>0,'prod_bp2'=>0,'crt'=>0]);

            endforeach;
            $this->data_model->del_multi('data_if_lama',['tgl'=>$tgl]);
            //end proses reverse inspect finish
        }
        if($produksi=="ff"){
            //proses reverse folding finish
            $this->db->query("DELETE FROM temp_upload_fol WHERE folding='Finish' AND tgl='$tgl'");
            $query = $this->data_model->get_byid('data_fol',['jns_fold'=>'Finish','tgl'=>$tgl,'loc'=>'Samatex']);
            foreach($query->result() as $val):
                $konstruksi = $val->konstruksi;
                $ukuran = $val->ukuran;

                $stok1 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$konstruksi, 'tgl'=>$tgl, 'dep'=>'Samatex']);
                $id_produksi = $stok1->row("id_produksi");
                $new_fg = floatval($stok1->row("prod_ff")) - floatval($ukuran);
                $this->data_model->updatedata('id_produksi',$id_produksi,'data_produksi',['prod_ff'=>0]);

                $stok2 = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl, 'dep'=>'Samatex']);
                $id_prod_hr = $stok2->row("id_prod_hr");
                $new_fg2 = floatval($stok2->row("prod_ff")) - floatval($ukuran);
                $this->data_model->updatedata('id_prod_hr',$id_prod_hr,'data_produksi_harian',['prod_ff'=>0]);

                $stok3 = $this->data_model->get_byid('data_stok', ['dep'=>$dep,'kode_konstruksi'=>$konstruksi]);
                $idstok = $stok3->row("idstok");
                $prod_fg3 = floatval($stok3->row("prod_ff")) - floatval($ukuran);
                $prod_ig3 = floatval($stok3->row("prod_if")) + floatval($ukuran);
                $this->data_model->updatedata('idstok',$idstok,'data_stok',['prod_if'=>round($prod_ig3,2),'prod_ff'=>round($prod_fg3,2)]);

            endforeach;
            $this->data_model->del_multi('data_fol',['jns_fold'=>'Finish','tgl'=>$tgl,'loc'=>'Samatex']);

            $query = $this->data_model->get_byid('data_fol_lama',['folding'=>'Finish','lokasi'=>'Samatex','tanggal'=>$tgl]);
            foreach($query->result() as $val):
                $konstruksi = $val->konstruksi;
                $ukuran = $val->ukuran_asli;

                $stok1 = $this->data_model->get_byid('data_produksi', ['kode_konstruksi'=>$konstruksi, 'tgl'=>$tgl, 'dep'=>'Samatex']);
                $id_data = $stok1->row("id_produksi");
                $new_fg = floatval($stok1->row("prod_fg")) - floatval($ukuran);
                $this->data_model->updatedata('id_produksi',$id_data,'data_produksi',['prod_ff'=>0]);

                $stok2 = $this->data_model->get_byid('data_produksi_harian', ['tgl'=>$tgl, 'dep'=>$dep]);
                $id_prod_hr = $stok2->row("id_prod_hr");
                $new_fg2 = floatval($stok2->row("prod_fg")) - floatval($ukuran);
                $this->data_model->updatedata('id_prod_hr',$id_prod_hr,'data_produksi_harian',['prod_ff'=>0]);

            endforeach;
            $this->data_model->del_multi('data_fol_lama',['folding'=>'Finish','lokasi'=>'Samatex','tanggal'=>$tgl]);
            //end proses reverse folding finishe
            echo "owek $tgl";
        }
        $this->session->set_flashdata('announce', 'Reverse produksi sukses, cek kembali data anda');
        //redirect(base_url('proses-produksi'));
  } //end reverse produksi

  function stokrjs(){
        $kons = $this->data_model->get_record('tb_konstruksi');
        foreach($kons->result() as $val):
            $kdkons = $val->kode_konstruksi;
            $dt = $this->db->query("SELECT konstruksi,ukuran_ori,dep,SUM(ukuran_ori) as total FROM data_ig WHERE konstruksi='$kdkons' AND dep='RJS'");
            echo $val->kode_konstruksi."-- Ukuran total yang pernah dibuat --- ".$dt->row('total')."-";
            $dt2 = $this->db->query("SELECT konstruksi,ukuran_ori,dep,loc_now,SUM(ukuran_ori) as total1 FROM data_ig WHERE konstruksi='$kdkons' AND dep='RJS' AND loc_now='Samatex'");
            echo "--Ukuran yang di kirim ke rindang--".$dt2->row("total1")."-";
            echo "<br>";
            $cek_idstok = $this->data_model->get_byid('data_stok', ['dep'=>'RJS-IN-Samatex', 'kode_konstruksi'=>$kdkons])->row("idstok");
            $this->data_model->updatedata('idstok',$cek_idstok,'data_stok',['prod_ig'=>$dt2->row('total1')]);
        endforeach;
        $roll = $this->data_model->get_byid('data_ig', ['dep'=>'RJS']);
        foreach($roll->result() as $val):
            $kdroll = $val->kode_roll;
            $konstruksi = $val->konstruksi;
            $ukuran = floatval($val->ukuran_ori);
            $cek_fol = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kdroll,'konstruksi'=>$konstruksi]);
            if($cek_fol->num_rows() == 1){
                $cek_idstok = $this->data_model->get_byid('data_stok', ['dep'=>'RJS-IN-Samatex', 'kode_konstruksi'=>$konstruksi]);
                $idstok = $cek_idstok->row("idstok");
                if($cek_fol->row("jns_fold")=="Grey"){
                    $jmlgrey = floatval($cek_idstok->row("prod_fg")) + $ukuran;
                    $this->data_model->updatedata('idstok',$idstok, 'data_stok', ['prod_fg'=>round($jmlgrey,2)]);
                } else {
                    $jmlfinish = floatval($cek_idstok->row("prod_ff")) + $ukuran;
                    $this->data_model->updatedata('idstok',$idstok, 'data_stok', ['prod_ff'=>round($jmlfinish,2)]);
                }
            }
            $cek_insf = $this->data_model->get_byid('data_if', ['kode_roll'=>$kdroll,'konstruksi'=>$konstruksi]);
        endforeach;
  } //end stok reverse

  function stoksmt(){
        $rolllama = $this->data_model->get_record('data_ig290');
        foreach($rolllama->result() as $val):
            $koderoll = $val->kode_roll;
            $cek = $this->data_model->get_byid('data_ig', ['kode_roll'=>$koderoll]);
            if($cek->num_rows() == 0){
                $dtlist = [
                    'kode_roll' => $koderoll,
                    'konstruksi' => $val->konstruksi,
                    'no_mesin' => $val->no_mesin,
                    'no_beam' => $val->no_beam,
                    'oka' => $val->oka,
                    'ukuran_ori' => $val->ukuran_ori,
                    'ukuran_bs' => $val->ukuran_bs,
                    'ukuran_bp' => $val->ukuran_bp,
                    'tanggal' => $val->tanggal,
                    'operator' => $val->operator,
                    'bp_can_join' => $val->bp_can_join,
                    'dep' => $val->dep,
                    'loc_now' => $val->loc_now,
                    'yg_input' => $val->yg_input
                ];
                $this->data_model->saved('data_ig', $dtlist);
            }
        endforeach;
  } //end stok reverse

  function showproduksi(){
        $dep = $this->session->userdata('departement');
        $produksi = $this->input->post('produksi');
        $tgl = $this->input->post('tgl');
        if($produksi=="" AND $tgl==""){
            echo "Erorr data tidak titemukan";
        } else {
        $data = array(
            'title' => 'Hasil Upload Produksi',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_hak' => $this->session->userdata('hak'),
            'sess_dep' => $dep,
            'produksi' => $produksi,
            'tgl' => $tgl
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/hasil_upload', $data);
        $this->load->view('part/main_js_dttable');
        }
  } //end stok reverse

  function tempif(){
        $tgl = $this->uri->segment(3);
        //echo $tgl;
        $dep = $this->session->userdata('departement');
        $data = array(
            'title' => 'Hasil Upload Produksi',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_hak' => $this->session->userdata('hak'),
            'sess_dep' => $dep,
            'queue' => $this->data_model->get_byid('temp_upload_if', ['tgl' => $tgl])
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/hasil_upload_temp', $data);
        $this->load->view('part/main_js_dttable');
    
  } //end stok reverse

  function tempfol(){
    $tgl = $this->uri->segment(3);
    $fol = $this->uri->segment(4);
    //echo $tgl;
    $dep = $this->session->userdata('departement');
    $data = array(
        'title' => 'Hasil Upload Produksi',
        'sess_nama' => $this->session->userdata('nama'),
        'sess_id' => $this->session->userdata('id'),
        'sess_hak' => $this->session->userdata('hak'),
        'sess_dep' => $dep,
        'queue' => $this->data_model->get_byid('temp_upload_fol', ['folding'=>$fol,'tgl' => $tgl])
    );
    $this->load->view('part/main_head', $data);
    $this->load->view('part/left_sidebar', $data);
    $this->load->view('baru/hasil_upload_temp2', $data);
    $this->load->view('part/main_js_dttable');

  } //end stok reverse
  
}
?>
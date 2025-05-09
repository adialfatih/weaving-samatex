<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usersporses extends CI_Controller
{
    function __construct()
    {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
    //   if($this->session->userdata('login_form') != "rindangjati_sess"){
	// 	  redirect(base_url("login"));
	//   }
    }
   
  function index(){
        $this->load->view('users/login');
  } //end

  function savepkgpermanent(){
        $kd = $this->uri->segment(3);
        $this->db->query("DELETE FROM new_tb_isi_saved WHERE kd='$kd'");
        $dt = $this->data_model->get_byid('new_tb_isi', ['kd'=>$kd])->result();
        foreach($dt as $val){
            $list = [
                'kd' => $val->kd,
                'konstruksi' => $val->konstruksi,
                'siap_jual' => $val->siap_jual,
                'kode' => $val->kode,
                'ukuran' => $val->ukuran,
                'status' => $val->status,
                'satuan' => $val->satuan
            ];
            $this->data_model->saved('new_tb_isi_saved', $list);
        }
        redirect(base_url('users/kirimpst'));
  } //end

  function returs(){
      $kode = $this->input->post("detil");
      $ex   = explode(', ', $kode);
      $no   = 1;
      $koderoll = array();
      $ukuran = array();
      $pkg = array();
      foreach($ex as $dt){
          if($dt!=""){
          //echo $no.". ".$dt."<br>";
          $cekkode = $this->data_model->get_byid('new_tb_isi', ['kode'=>$dt,'siap_jual'=>'n']);
            if($cekkode->num_rows() == 1){
                $koderoll[] = $cekkode->row("kode");
                $ukuran[] = $cekkode->row("ukuran");
                $pkg[] = $cekkode->row("kd");
            }
          $no++;
          }
      }
      if(count($koderoll) > 0){
          $roll = count($koderoll);
          $total_panjang = array_sum($ukuran);
          echo '<!DOCTYPE html>
          <html lang="en">
          <head>
              <meta charset="UTF-8">
              <meta name="google" content="notranslate">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <title>Packinglist Ke Pusatex</title>
              <link rel="stylesheet" href="'.base_url('new_db/').'style.css">
              <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
              <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
              <style>
                  .autoComplete_wrapper input{
                      width: 95%;
                      transform: translateX(10px);
                  }
              </style>
          </head>
          <body>';
          echo '<div style="width:100%;height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;padding:20px">';
          echo "<p>Anda kan mengembalikan sejumlah ".$roll." Roll kain dengan total panjang ".$total_panjang." meter ? </p>";
          ?>
          <table style="width:100%;border:1px solid #ccc;border-collapse:collapse; margin-top:20px;" border="1">
                  <tr>
                    <td style="padding:5px;">No.</td>
                    <td style="padding:5px;">Kode Roll</td>
                    <td style="padding:5px;">Ukuran</td>
                  </tr>
                  <?php $nu=1; for ($i=0; $i <count($koderoll) ; $i++) { 
                    echo "<tr>";
                    echo "<td style='padding:5px;'>".$nu."</td>";
                    echo "<td style='padding:5px;'>".$koderoll[$i]."</td>";
                    echo "<td style='padding:5px;'>".$ukuran[$i]."</td>";
                    echo "</tr>";
                    $nu++;
                  }
                  ?>
          </table>
          <?php
          $sj = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$pkg[0]])->row("no_sj");
          echo "<p>&nbsp;</p>";
          echo "<p>Proses ini akan secara otomatis merubah packinglist <strong>".$pkg[0]."</strong> dan surat jalan nomor <strong>".$sj."</strong>. <br>Masukan alasan retur.</p>";
          $kodelist = implode("-", $koderoll);
          ?>
          <form action="<?=base_url('usersporses/again');?>" method="post" style="width:100%;margin-top:10px;display:flex;flex-direction:column;"> 
            <input type="hidden" name="kodelist" value="<?=$kodelist;?>">
            <input type="hidden" name="roll" value="<?=$roll;?>">
            <input type="hidden" name="totalukr" value="<?=$total_panjang;?>">
            <input type="hidden" name="kdpkg" value="<?=$pkg[0];?>">
            <textarea name="alasan" id="als" cols="30" rows="4" style="width:100%;border-radius:5px;padding:10px;" placeholder="Masukan alasan retur" required></textarea>
            <button type="submit" style="width:200px;margin-top:10px;padding:5px;border-radius:5px;border:1px solid #ccc;outline:none;">Simpan</button>
          </form>
          <?php
          echo '</div>';
          echo "</body></html>";
      } else {
          echo "noselected";
      }
  } //end returs

  function again(){
      $kode_retur = $this->input->post('kodelist');
      $pjg = $this->input->post('totalukr');
      $pkg = $this->input->post('kdpkg');
      $als = $this->input->post('alasan');
      $roll = $this->input->post('roll');
      $this->data_model->saved('data_retur', [
        'kode_retur'=>$kode_retur, 'total_panjang'=>$pjg, 'pkg'=>$pkg, 'alasanretur'=>$als
      ]);
      $dt = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$pkg]);
      $id_kdlist = $dt->row("id_kdlist");
      $jumlah_roll = $dt->row("jumlah_roll");
      $ttl_panjang = $dt->row("ttl_panjang");
      $new_roll = intval($jumlah_roll) - intval($roll);
      $new_pjg = floatval($ttl_panjang) - floatval($pjg);
      $this->data_model->updatedata('id_kdlist', $id_kdlist, 'new_tb_packinglist', ['jumlah_roll'=>$new_roll, 'ttl_panjang'=>$new_pjg]);
      $exp = explode('-', $kode_retur);
      for ($i=0; $i < count($exp) ; $i++) { 
          $this->data_model->del_multi('new_tb_isi', ['kd'=>$pkg,'siap_jual'=>'n','kode'=>$exp[$i]]);
          $this->data_model->updatedata('kode_roll',$exp[$i], 'data_ig', ['loc_now'=>'RJS']);
      }
      redirect(base_url('users/createkirimpst3/'.$pkg));
  } //end 
  
  function greykefinish(){
      $im_kode = base64_decode($this->uri->segment(3));
      $im_ukrn = base64_decode($this->uri->segment(4));
      $kons = base64_decode($this->uri->segment(5));
      //echo $im_kode."<br>".$im_ukrn;
      $kode = explode('-',$im_kode);
      $ukrn = explode('-',$im_ukrn);
      $roll = count($kode);
      $total_panjang = array_sum($ukrn);
      echo '<!DOCTYPE html>
          <html lang="en">
          <head>
              <meta charset="UTF-8">
              <meta name="google" content="notranslate">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <title>Packinglist Ke Pusatex</title>
              <link rel="stylesheet" href="'.base_url('new_db/').'style.css">
              <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
              <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
              <style>
                  .autoComplete_wrapper input{
                      width: 95%;
                      transform: translateX(10px);
                  }
              </style>
          </head>
          <body>';
          echo '<div style="width:100%;height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;padding:20px">';
          echo "<p>Anda akan menghapus sejumlah <strong>".$roll." Roll</strong> kain dengan total panjang <strong>".$total_panjang."</strong> meter dari stok Grey <strong>".$kons."</strong> </p>";
          ?>
          <form action="<?=base_url('users/greyfinishproses');?>" method="post" style="width:100%;margin-top:10px;display:flex;flex-direction:column;">
          <table style="width:100%;border:1px solid #ccc;border-collapse:collapse; margin-top:20px;" border="1">
                  <tr>
                    <th style="padding:5px;text-align:center;">No.</th>
                    <th style="padding:5px;text-align:center;">Kode Roll</th>
                    <th style="padding:5px;text-align:center;">Ukuran</th>
                    <th style="padding:5px;text-align:center;">Hapus</th>
                  </tr>
                  <?php $nu=1; for ($i=0; $i <count($kode) ; $i++) { 
                    echo "<tr>";
                    echo "<td style='padding:5px;'>".$nu."</td>";
                    echo "<td style='padding:5px;'>".$kode[$i]."</td>";
                    echo "<td style='padding:5px;'>".$ukrn[$i]."</td>";
                    ?><td style="padding:5px;text-align:center;">
                      <input type="checkbox" value="<?=$kode[$i];?>" name="allkode[]" checked>
                    </td><?php
                    echo "</tr>";
                    $nu++;
                  }
                  ?>
          </table>
          <p>&nbsp;</p>
          <p>
            Proses ini akan menghapus kode Roll di atas di dalam stok Kain Grey, dan akan memindahkan ke packinglist pengiriman ke Pusatex. Lanjutkan ?
          </p>
           
            <input type="hidden" name="jmlrol" value="<?=$roll;?>">
            <input type="hidden" name="kons" value="<?=$kons;?>">
            <input type="hidden" name="totalukr" value="<?=$total_panjang;?>">
            <div style="width:100%;display:flex;">
            <button type="submit" style="width:200px;margin-top:10px;padding:5px;border-radius:5px;border:1px solid #ccc;outline:none;">Simpan</button>
            <a href="https://sm.rindangjati.com/users/penjualan">
            <button type="button" style="width:100px;background:red;color:#fff;margin:10px 0 0 10px;padding:5px;border-radius:5px;border:1px solid #ccc;outline:none;" id="batlkan">Batal</button>          
            </a>
            </div>
          </form>
          <?php
          echo '</div>';
          echo "</body></html>";
  }//end

  function createpaket(){
      $kons = $this->input->post('kons');
      $ukr = $this->input->post('totalukr');
      $kode = $this->input->post('allkode');
      $jml = count($kode);
      $ukuran_grey = array();
      foreach($kode as $kd):
          //echo "$kd <br>";
          $ukr = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kd])->row("ukuran_ori");
          $ukuran_grey[] = $ukr;
          $this->data_model->saved('new_tb_isi');
      endforeach;
        $cekkode = $this->data_model->get_byid('data_ig_code', ['idcode'=>3])->row_array();
        $cekkode_alpabet = $cekkode['alpabet'];
        $new_number = intval($cekkode['numskr']) + 1;
        $kdpkg = $cekkode_alpabet."".$new_number;

        $dtlist = [
            'kode_konstruksi' => $kons,
            'kd' => $kdpkg,
            'tanggal_dibuat' => date('Y-m-d'),
            'lokasi_now' => 'Samatex',
            'siap_jual' => 'n',
            'jumlah_roll' => $jml,
            'ttl_panjang' => array_sum($ukuran_grey),
            'kepada' => 'NULL',
            'no_sj' => 'NULL',
            'ygbuat' => 'edi',
            'jns_fold' => 'null'
        ];
        $this->data_model->saved('new_tb_packinglist', $dtlist);
        $this->data_model->updatedata('idcode',3,'data_ig_code',['numskr'=>$new_number]);
      foreach($kode as $kd){
          $this->data_model->get_byid('kode_roll',$kd,'data_ig',['loc_now'=>$kdpkg]);
          $this->db->query("DELETE FROM data_fol WHERE kode_roll='$kd' AND jns_fold='Grey'");
          $ukr = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kd])->row("ukuran_ori");
          $this->data_model->saved('new_tb_isi', [
              'kd' => $kdpkg,
              'konstruksi' => $kons,
              'siap_jual' => 'n',
              'kode' => $kd,
              'ukuran' => $ukr,
              'status' => 'oke',
              'satuan' => 'Meter'
          ]);
      }
      $idstok = $this->data_model->get_byid('data_stok',['dep'=>'newSamatex','kode_konstruksi'=>$kons])->row("idstok");
      $ukrseblum = $this->data_model->get_byid('data_stok',['dep'=>'newSamatex','kode_konstruksi'=>$kons])->row("prod_fg");
      $updatestok = floatval($ukrseblum) - floatval($ukr);
      $this->data_model->updatedate('idstok',$idstok,'data_stok',['prod_fg'=>round($updatestok,2)]);
      redirect(base_url('users/penjualan'));
      //echo json_encode(array("statusCode"=>200, "psn"=>$kdpkg));
  } //end

}
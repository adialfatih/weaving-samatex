<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hapus extends CI_Controller
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

   }
  function folding(){ 
      $jns = $this->uri->segment(3);
      ?>
      <form action="https://sm.rindangjati.com/hapus/verif" method="post">
            <input type="hidden" name="jns" value="<?=$jns;?>">
            <label for="kode">Kode Roll</label>
            <textarea name="kode" id="kode" cols="30" rows="10" placeholder="Masukan Kode Roll. Ex: SH1234,SH7687,RW2345" style="padding:10px;"></textarea>
            <input type="submit" value="Submit">
      </form>
      <?php
  } //end
  function oke(){
        $kode = $this->input->post('koderoll');
        $ket = $this->input->post('ketid');
        echo $ket."<br>";
        for ($i=0; $i < count($kode); $i++) {
            $cek = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode[$i]])->row_array(); 
            echo "Berhasil hapus : ".$kode[$i]."<br>";
            $this->data_model->saved('delete_stok', [
                'kode_roll' => $kode[$i],
                'ukuran_folding' => $cek['ukuran'],
                'jns_fold' => $cek['jns_fold'],
                'operator' => $cek['operator'],
                'ket' => $ket
            ]);
        }
        for ($a=0; $a < count($kode); $a++) {
            $asd = $kode[$a];
            $this->db->query("DELETE FROM data_fol WHERE kode_roll='$asd'");
        }
        $bln = date('m');
        echo "<a href='https://sm.rindangjati.com/laporan/produksi/'".$bln.">Kembali ke beranda</a>";
  }

  function verif(){
        $jns = $this->input->post('jns');
        $nJns = ucfirst($jns);
        $kode = $this->input->post('kode');
        $ex = explode(',', $kode);
        ?>
        <form action="https://sm.rindangjati.com/hapus/oke" method="post">
        <table border="1">
            <tr>
                <th>No.</th>
                <th>Hapus</th>
                <th>Kode</th>
                <th>Konstruksi</th>
                <th>Ukuran</th>
                <th>Operator</th>
                <th>Posisi</th>
            </tr>
            <?php for ($i=0; $i <count($ex) ; $i++) { 
                $cek = $this->data_model->get_byid('data_fol', ['kode_roll'=>$ex[$i], 'jns_fold'=>$nJns]);
                if($cek->num_rows() != 1){
                    echo "<tr><td colspan='7' style='color:red;'>Kode ".$ex[$i]." Tidak ditemukan</td></tr>";
                } else {
                $posisi = $cek->row("posisi");
            ?>
            <tr>
                <td><?=$i+1;?></td>
                <?php if($posisi=="Samatex"){ ?>
                <td><input type="checkbox" name="koderoll[]" value="<?=$ex[$i];?>"></td>
                <?php } else {echo "<td></td>";} ?>
                <td><?=$ex[$i];?></td>
                <td><?=$cek->row("konstruksi");?></td>
                <td><?=$cek->row("ukuran");?></td>
                <td><?=$cek->row("operator");?></td>
                <td style="<?=$posisi=='Samatex' ?'color:#000;':'color:red;';?>"><?=$posisi;?></td>
            </tr>
            <?php } } ?>
        </table>
        <p>Masukan Keterangan</p>
        <textarea style="width:300px;height:50px;padding:5px;" name="ketid" id="ketid" placeholder="Masukan keterangan / alasan yang jelas hapus stok"></textarea>
        <p>&nbsp;</p>
        <input type="submit" value="Submit">
        </form>
        <?php
  }

}
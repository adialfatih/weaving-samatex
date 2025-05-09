<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reload extends CI_Controller
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
  function cekupload(){
        $dep = $this->session->userdata('departement');
        $data = array(
            'title' => 'Surat Jalan PT. Rindang Jati Spinning',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'dep_user' => $dep
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/upload_andcek', $data);
        $this->load->view('part/main_js');
  }
    function sjrindang(){
        $dep = $this->session->userdata('departement');
        $data = array(
            'title' => 'Surat Jalan PT. Rindang Jati Spinning',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'dep_user' => $dep,
            'dtrow' => $this->db->query("SELECT * FROM surat_jalan WHERE dep_asal='$dep' ORDER BY id_sj DESC LIMIT 50")
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/upload_andcek', $data);
        $this->load->view('part/main_js');
    }
    
    function sj(){
        $uri = $this->uri->segment(3);
        $pkg = $this->uri->segment(4);
        $pjg = $this->uri->segment(5);
        $cekdt = $this->data_model->get_byid('lock_surat_jalan', ['nosj'=>$uri]);
        if($cekdt->num_rows() == 0){
            $this->data_model->saved('lock_surat_jalan', ['nosj'=>$uri,'jml_pkg'=>$pkg,'total_pjg'=>$pjg]);
            $this->data_model->updatedata('no_sj',$uri,'surat_jalan',['create_lock'=>'yes']);
        }
        $dep = $this->session->userdata('departement');
        $data = array(
            'title' => 'Surat Jalan PT. Rindang Jati Spinning',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'dep_user' => $dep,
            'uri' => $uri
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/upload_andcek2', $data);
        $this->load->view('part/main_js');
    }

  function smt(){
    $url = $this->uri->segment(3);
    if(empty($url) OR $url==""){
        $tgl = date('Y-m-d');
    } else {
        $tgl = $url;
    }
    echo "Inspect Grey Tanggal -- $tgl<br>";
    $ig = $this->db->query("SELECT SUM(ukuran_ori) as ori, SUM(ukuran_bs) as bs, SUM(ukuran_bp) as bp, tanggal FROM data_ig WHERE tanggal='$tgl'");
    echo "ukuran ori (".$ig->row('ori').") -- ukuran bs (".$ig->row('bs').") -- ukuran bp (".$ig->row('bp').")";
    echo "<hr>";

    echo "Folding Grey<br>";
    $fg = $this->db->query("SELECT SUM(ukuran) as ori,jns_fold,tgl FROM data_fol WHERE jns_fold='Grey' AND tgl='$tgl'");
    echo "ukuran ori (".$fg->row('ori').")";
    echo "<hr>";

    echo "Inspect Finish<br>";
    $iff = $this->db->query("SELECT tgl_potong, SUM(ukuran_ori) as ori, SUM(ukuran_bs) as bs, SUM(ukuran_crt) as crt, SUM(ukuran_bp) as bp FROM data_if WHERE tgl_potong='$tgl'");
    echo "ukuran ori (".round($iff->row('ori'),2).") -- ukuran bs (".round($iff->row('bs'),2).")-- ukuran crt (".round($iff->row('crt'),2).") -- ukuran bp (".round($iff->row('bp'),2).")";
    echo "<hr>";

    echo "Folding Finish<br>";
    $ff = $this->db->query("SELECT SUM(ukuran) as ori,jns_fold,tgl FROM data_fol WHERE jns_fold='Finish' AND tgl='$tgl'");
    echo "ukuran ori (".$ff->row('ori').")";
    echo "<hr>";


    echo "<strong>Stok Lama</strong><br>";


    echo "SL Folding Grey<br>";
    $fg1 = $this->db->query("SELECT SUM(ukuran_asli) as ori,folding,tanggal FROM data_fol_lama WHERE folding='Grey' AND tanggal='$tgl'");
    echo "ukuran ori (".$fg1->row('ori').")";
    echo "<hr>";

    echo "SL Inspect Finish<br>";
    $iff2 = $this->db->query("SELECT tgl, SUM(panjang) as ori, SUM(bs) as bs, SUM(crt) as crt, SUM(bp) as bp FROM data_if_lama WHERE tgl='$tgl'");
    echo "ukuran ori (".round($iff2->row('ori'),2).") -- ukuran bs (".round($iff2->row('bs'),2).")-- ukuran crt (".round($iff2->row('crt'),2).") -- ukuran bp (".round($iff2->row('bp'),2).")";
    echo "<hr>";

    echo "SL Folding Finish<br>";
    $ff1 = $this->db->query("SELECT SUM(ukuran_asli) as ori,folding,tanggal FROM data_fol_lama WHERE folding='Finish' AND tanggal='$tgl'");
    echo "ukuran ori (".$ff1->row('ori').")";
    echo "<hr>";

    echo "<strong>TOTAL PRODUKSI PADA TANGGAL ".$tgl."</strong>";
    echo "<div style='display:flex;'>";

    echo '<div style="width:200px;display:flex;flex-direction:column;border:1px solid #000;justify-content:center;align-items:center;">';
    echo "<span>Inspect Grey</span>";
    echo "<span>".$ig->row('ori')."</span>";
    echo "</div>";

    echo '<div style="width:200px;display:flex;flex-direction:column;border:1px solid #000;justify-content:center;align-items:center;">';
    echo "<span>Folding Grey</span>";
    $ttl_fg = $fg1->row('ori') + $fg->row('ori');
    echo "<span>".$ttl_fg."</span>";
    echo "</div>";

    echo '<div style="width:200px;display:flex;flex-direction:column;border:1px solid #000;justify-content:center;align-items:center;">';
    echo "<span>Inspect Finish</span>";
    $ttl_if = $iff->row('ori') + $iff2->row('ori');
    echo "<span>".$ttl_if."</span>";
    echo "</div>";

    echo '<div style="width:200px;display:flex;flex-direction:column;border:1px solid #000;justify-content:center;align-items:center;">';
    echo "<span>Folding Finish</span>";
    $ttl_ff = $ff->row('ori') + $ff1->row('ori');
    echo "<span>".$ttl_ff."</span>";
    echo "</div>";

    echo "</div>";

  } //

  public function showbysj(){
      $dep = $this->session->userdata('departement');
      $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', '00' => 'Undefined' ];
      $metode = $this->input->post('metode');
      $sj = $this->input->post('sj');
      if($metode == "nosj"){
            $dtrow = $this->db->query("SELECT * FROM surat_jalan WHERE no_sj LIKE '%$sj%' AND dep_asal='$dep' ORDER BY id_sj DESC LIMIT 10");
      }
      //end metode nosj
      if($metode == "tgl"){
            $dtrow = $this->db->query("SELECT * FROM surat_jalan WHERE tgl_kirim = '$sj' AND dep_asal='$dep' ORDER BY id_sj DESC LIMIT 10");
      }
      if($metode == "bln"){
            //SELECT * FROM nama_tabel WHERE YEAR(tanggal) = @tahun AND MONTH(tanggal) = @bulan;
            $ex = explode('-', $sj);
            $_tahun = $ex[0];
            $_bulan = $ex[1];
            $dtrow = $this->db->query("SELECT * FROM surat_jalan WHERE YEAR(tgl_kirim) = '$_tahun' AND MONTH(tgl_kirim) = '$_bulan' AND dep_asal='$dep' ORDER BY id_sj DESC ");
      }
      //end metode tanggal
      if($dtrow->num_rows() < 1){
        echo "<tr><td colspan='7'>Data Tidak ditemukan</td></tr>";
      } else {
      $no=1;
			foreach($dtrow->result() as $dr):
			$ex = explode('-', $dr->tgl_kirim);
			$printTgl = $ex[2]." ".$bln[$ex[1]]." ".$ex[0];
			$sj = $dr->no_sj;
			$jmlpkg = $this->db->query("SELECT COUNT(no_sj) as jml FROM new_tb_packinglist WHERE no_sj='$sj'")->row("jml");
			$ttl_panjang = $this->db->query("SELECT SUM(ttl_panjang) as jml FROM new_tb_packinglist WHERE no_sj='$sj'")->row("jml");
			?>
				<tr>
					<td><?=$no;?></td>
				    <td><?=$printTgl;?></td>
					<td><?=$sj;?></td>
					<td>
						<?php 
                        if($dr->tujuan_kirim == "Samatex"){
							echo "<span style='background:#2e73f2;color:#fff;font-size:10px;padding:3px 5px;border-radius:4px;'>Samatex</span>";
						} elseif($dr->tujuan_kirim == "Pusatex") {
							echo "<span style='background:#189e23;color:#fff;font-size:10px;padding:3px 5px;border-radius:4px;'>Pusatex</span>";
						} else {
							echo "<span style='background:red;color:#fff;font-size:10px;padding:3px 5px;border-radius:4px;'>Customer</span>";
						}
						if($dr->tujuan_kirim == "cus"){
							$idcus = $dr->id_customer;
							$nmkosn = $this->data_model->get_byid('dt_konsumen', ['id_konsumen'=>$idcus])->row("nama_konsumen");
							$nmcus2 = strtolower($nmkosn);
							$nmcus = ucwords($nmcus2);
							echo "<span style='background:orange;color:#fff;font-size:10px;padding:3px 5px;border-radius:4px;margin-left:5px;'>$nmcus</span>";
						}
						?>
					</td>
					<td><?=$jmlpkg;?></td>
					<td>
						<?php 
                        if(fmod($ttl_panjang, 1) !== 0.00){
							echo number_format($ttl_panjang,2,',','.');
					    } else {
							echo number_format($ttl_panjang,0,',','.');
						}
						?>
					</td>
					<td>
						<?php 
                        if($dr->create_lock == "yes"){ ?>
						    <i class="icon-copy bi bi-shield-lock-fill" title="Packinglist Telah di kunci" style='color:#f58a07;font-size:20px;'></i>
						<?php } else { ?>
							<a href="<?=base_url('reload/sj/'.$sj.'');?>">
							<i class="icon-copy bi bi-shield-lock-fill" title="Packinglist Belum di kunci. Data bisa saja Hilang" style='color:#ccc;font-size:20px;'></i></a>
						<?php } ?>
					</td>
						</tr>
		<?php $no++; endforeach;
      }
  }
    
}
?>
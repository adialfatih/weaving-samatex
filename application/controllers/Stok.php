<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends CI_Controller
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

  function gudang(){
        $dep = $this->session->userdata('departement');
        if($dep=="Samatex"){$newDep = "newSamatex"; }
        if($dep=="RJS"){$newDep = "newRJS"; }
        if($dep=="Pusatex"){$newDep = "newPusatex"; }
        $data = array(
            'title' => 'Stok Gudang',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_dep' => $this->session->userdata('departement'),
            'tbdata' => $this->db->query("SELECT * FROM data_stok WHERE dep='$newDep'"),
            'tbdata2' => $this->db->query("SELECT * FROM data_stok_lama_total"),
            'dep'=>$dep
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/stok_gudang', $data);
        $this->load->view('part/main_js_dttable');
  } //end

  function onpkg(){
        $uri = $this->uri->segment(3);
        echo $uri;
  }

  function tesoke(){
        $data_ig = $this->db->query("SELECT * FROM data_ig WHERE dep='Samatex' AND loc_now='Pusatex' AND kode_roll NOT IN (SELECT kode_roll FROM data_if) AND kode_roll NOT IN (SELECT kode_roll FROM data_fol) ");
        $kons = array();
        foreach($data_ig->result() as $val){
            $kode = $val->kode_roll;
            $cek1 = $this->data_model->get_byid('data_if',['kode_roll'=>$kode])->num_rows();
            if($cek1 == 0){
                $cek2 = $this->data_model->get_byid('data_fol',['kode_roll'=>$kode])->num_rows();
                if($cek2 == 0){
                    echo $val->konstruksi."-".$val->ukuran_ori."<br>";
                } 
            }
            if(in_array($val->konstruksi, $kons)){

            } else {
                $kons[]=$val->konstruksi;
            }
            
        }

        echo "<pre>";
        print_r($kons);
        echo "</pre>";

        foreach($kons as $kons){
            $total = $this->db->query("SELECT kode_roll,SUM(ukuran_ori) AS total FROM data_ig WHERE konstruksi='$kons' AND dep='Samatex' AND loc_now='Pusatex' AND kode_roll NOT IN(SELECT kode_roll FROM data_if) AND kode_roll NOT IN (SELECT kode_roll FROM data_fol) ")->row("total");
            echo $kons." = ".$total."<br>";
        }
  } //end
  public function showtes(){
      echo "oke";
  }

  function perroll(){
        $kons2 = $this->uri->segment(3);
        $jns2 = $this->uri->segment(4);
        echo $kons2."--".$jns2;
        $kons = strtoupper($kons2);
        $jns = ucfirst($jns2);
        // if($kons2!="" AND $jns2!=""){
        //     $url1 = $this->db->query("SELECT * FROM data_fol WHERE konstruksi='$kons' AND jns_fold='$jns' AND posisi='Samatex'");
        //     $url2 = $this->db->query("SELECT * FROM data_fol WHERE konstruksi='$kons' AND jns_fold='$jns' AND posisi LIKE '%PKT%'");
        // } else {
        //     $url1 = $this->db->query("SELECT * FROM data_fol WHERE konstruksi='SM05B' AND jns_fold='Finish' AND posisi='Samatex'");
        //     $url2 = $this->db->query("SELECT * FROM data_fol WHERE konstruksi='SM05B' AND jns_fold='Finish' AND posisi LIKE '%PKT%'");
        // }
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>No.</td>";
        echo "<td>Kode</td>";
        echo "<td>Ukuran</td>";
        echo "<td>konstruksi</td>";
        echo "<td>Tgl Fol</td>";
        echo "<td>Posisi</td>";
        echo "<tr>";
        $no = 1;
        $total=0;
        // foreach($url1->result() as $val){
        //     echo "<tr>";
        //     echo "<td>".$no."</td>";
        //     echo "<td>".$val->kode_roll."</td>";
        //     echo "<td>".$val->ukuran."</td>";
        //     echo "<td>".$val->konstruksi."</td>";
        //     echo "<td>".$val->tgl."</td>";
        //     echo "<td>".$val->posisi."</td>";
        //     echo "</tr>";
        //     $total+=$val->ukuran;
        //     $no++;
        // }
        // foreach($url2->result() as $val){
        //     echo "<tr>";
        //     echo "<td>".$no."</td>";
        //     echo "<td>".$val->kode_roll."</td>";
        //     echo "<td>".$val->ukuran."</td>";
        //     echo "<td>".$val->konstruksi."</td>";
        //     echo "<td>".$val->tgl."</td>";
        //     echo "<td>".$val->posisi."</td>";
        //     echo "</tr>";
        //     $total+=$val->ukuran;
        //     $no++;
        // }
        echo "<tr>";
        echo "<td colspan='6'>Total : ".$total."</td>";
        echo "</tr>";
        
  }
  function perroll_error(){
    $kons2 = $this->uri->segment(3);
    $jns2 = $this->uri->segment(4);
    
    $url2 = $this->db->query("SELECT * FROM data_fol WHERE posisi LIKE '%cus1%'");
    
    echo "<table border='1'>";
    echo "<tr>";
    echo "<td>No.</td>";
    echo "<td>Kode</td>";
    echo "<td>Ukuran</td>";
    echo "<td>konstruksi</td>";
    echo "<td>Tgl Fol</td>";
    echo "<td>jns Fol</td>";
    echo "<td>Posisi</td>";
    echo "<td>Terjual</td>";
    echo "<tr>";
    $no = 1;
    $total=0;
    $arkons = array();
    foreach($url2->result() as $val){
        echo "<tr>";
        echo "<td>".$no."</td>";
        echo "<td>".$val->kode_roll."</td>";
        echo "<td>".$val->ukuran."</td>";
        echo "<td>".$val->konstruksi."</td>";
        echo "<td>".$val->tgl."</td>";
        echo "<td>".$val->jns_fold."</td>";
        echo "<td>".$val->posisi."</td>";
        $cek_terjual = $this->data_model->get_byid('new_tb_isi', ['siap_jual'=>'y','kode'=>$val->kode_roll]);
        if($cek_terjual->num_rows() == 0){
            echo "<td style='color:green;'>On Samatex</td>";
        } else {
            echo "<td style='color:red;'>".$cek_terjual->row('kd')."</td>";
        }
        
        echo "</tr>";
        $total+=$val->ukuran;
        $no++;
        if(in_array($val->konstruksi, $arkons)){

        } else {
            $arkons[]=$val->konstruksi;
        }
    }
    
    echo "<tr>";
    echo "<td colspan='7'>Total : ".$total."</td>";
    echo "</tr>";
    foreach($arkons as $tal){
        $jml = $this->db->query("SELECT SUM(ukuran) as tl FROM data_fol WHERE konstruksi='$tal' AND jns_fold='Finish' AND posisi LIKE '%cus1%'")->row("tl");
    echo "<tr>";
    echo "<td colspan='7'>Konstruksi : ".$tal." = ".$jml."</td>";
    echo "</tr>";
    }
    
}
  
  function byfolding(){
      $cek = $this->data_model->get_byid('data_fol', ['posisi'=>'Samatex']);
      
      echo "<table border='1'>";
      echo "<tr>";
      echo "<td>No.</td>";
      echo "<td>Konstruksi</td>";
      echo "<td style='background:#ccc;'>Stok Folding Grey</td>";
      echo "<td style='background:#ccc;'>Stok Folding Grey (ON PAKET)</td>";
      echo "<td style='background:#ccc;'>Jumlah Grey</td>";
      echo "<td style='background:#ccc;'>Tampil di Direksi</td>";
      echo "<td>Stok Folding Finish</td>";
      echo "<td>Stok Folding Finish (ON PAKET)</td>";
      echo "<td>Jumlah Finish</td>";
      echo "<td>Tampil di Direksi</td>";
      echo "</tr>";
      $kons = $this->data_model->get_record('tb_konstruksi');
      $no=1;
      foreach($kons->result() as $val){
        $k = strtoupper($val->kode_konstruksi);
        echo "<tr>";
        
        echo "<td>".$no."</td>";
        
        if($val->chto == "NULL") {  echo "<td>".$k."</td>";  } else { echo "<td>".$k." - ".$val->chto."</td>";  }
        
        
        $fg = $this->db->query("SELECT SUM(ukuran) AS jm FROM data_fol WHERE konstruksi='$k' AND jns_fold='Grey' AND posisi='Samatex' ")->row("jm");
        $fg_2 = $this->db->query("SELECT SUM(ukuran) AS jm FROM data_fol WHERE konstruksi='$k' AND jns_fold='Grey' AND posisi LIKE '%PKT%' ")->row("jm");
        $ff = $this->db->query("SELECT SUM(ukuran) AS jm FROM data_fol WHERE konstruksi='$k' AND jns_fold='Finish' AND posisi='Samatex'")->row("jm");
        $ff_2 = $this->db->query("SELECT SUM(ukuran) AS jm FROM data_fol WHERE konstruksi='$k' AND jns_fold='Finish' AND posisi LIKE '%PKT%' ")->row("jm");
        
        
        if($fg < 1){ echo "<td  style='background:#ccc;'>-</td>"; } else { echo "<td  style='background:#ccc;'>".number_format($fg,2,',','.')."</td>"; }
        
        if($fg_2 < 1){ echo "<td  style='background:#ccc;'>-</td>"; } else { echo "<td  style='background:#ccc;'>".number_format($fg_2,2,',','.')."</td>"; }
        
        $jml1 = $fg + $fg_2; if($jml1<1){ $jml1="-"; }
        
        $cekstok1 = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex', 'kode_konstruksi'=>$k])->row("prod_fg");
        echo "<td style='background:#ccc;'>".$jml1."</td>";
        echo "<td style='background:#ccc;'>".$cekstok1."</td>";

        if($ff < 1){ echo "<td>-</td>"; } else { echo "<td>".number_format($ff,2,',','.')."</td>"; }
        if($ff_2 < 1){ echo "<td>-</td>"; } else { echo "<td>".number_format($ff_2,2,',','.')."</td>"; }

        $jml2 = $ff + $ff_2;
        if($jml2<1){ $jml2="-"; }
        $cekstok2 = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex', 'kode_konstruksi'=>$k])->row("prod_ff");
        echo "<td>".$jml2."</td>";
        echo "<td>".$cekstok2."</td>";

        echo "</tr>";
        $no++;
      }
      echo "</table>";
  } //end
  function gudangsamatex(){
      $cek = $this->data_model->get_byid('data_fol', ['posisi'=>'Samatex']);
      
      echo "<table border='1'>";
      echo "<tr>";
      echo "<td>No.</td>";
      echo "<td>Konstruksi</td>";
      echo "<td style='background:#ccc;'>Stok Folding Grey</td>";
      echo "<td style='background:#ccc;'>Stok Folding Grey (ON PAKET)</td>";
      echo "<td style='background:#ccc;'>Jumlah STOK GUDANG Grey</td>";
      //echo "<td style='background:#ccc;'>Tampil di Direksi</td>";
      echo "<td>Stok Folding Finish</td>";
      echo "<td>Stok Folding Finish (ON PAKET)</td>";
      echo "<td>Jumlah STOK GUDANG Finish</td>";
      //echo "<td>Tampil di Direksi</td>";
      echo "</tr>";
      $kons = $this->data_model->get_record('tb_konstruksi');
      $no=1;
      foreach($kons->result() as $val){
        $k = strtoupper($val->kode_konstruksi);
        echo "<tr>";
        
        echo "<td>".$no."</td>";
        
        if($val->chto == "NULL") {  echo "<td>".$k."</td>";  } else { echo "<td>".$k." - ".$val->chto."</td>";  }
        
        
        $fg = $this->db->query("SELECT SUM(ukuran) AS jm FROM data_fol WHERE konstruksi='$k' AND jns_fold='Grey' AND posisi='Samatex' ")->row("jm");
        $fg_2 = $this->db->query("SELECT SUM(ukuran) AS jm FROM data_fol WHERE konstruksi='$k' AND jns_fold='Grey' AND posisi LIKE '%PKT%' ")->row("jm");
        $ff = $this->db->query("SELECT SUM(ukuran) AS jm FROM data_fol WHERE konstruksi='$k' AND jns_fold='Finish' AND posisi='Samatex'")->row("jm");
        $ff_2 = $this->db->query("SELECT SUM(ukuran) AS jm FROM data_fol WHERE konstruksi='$k' AND jns_fold='Finish' AND posisi LIKE '%PKT%' ")->row("jm");
        
        
        if($fg < 1){ echo "<td  style='background:#ccc;'>-</td>"; } else { echo "<td  style='background:#ccc;'>".number_format($fg,2,',','.')."</td>"; }
        
        if($fg_2 < 1){ echo "<td  style='background:#ccc;'>-</td>"; } else { echo "<td  style='background:#ccc;'>".number_format($fg_2,2,',','.')."</td>"; }
        
        $jml1 = $fg + $fg_2; if($jml1<1){ $jml1="-"; }
        
        $cekstok1 = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex', 'kode_konstruksi'=>$k])->row("prod_fg");
        echo "<td style='background:#ccc;'>".$jml1."</td>";
        //echo "<td style='background:#ccc;'>".$cekstok1."</td>";

        if($ff < 1){ echo "<td>-</td>"; } else { echo "<td>".number_format($ff,2,',','.')."</td>"; }
        if($ff_2 < 1){ echo "<td>-</td>"; } else { echo "<td>".number_format($ff_2,2,',','.')."</td>"; }

        $jml2 = $ff + $ff_2;
        if($jml2<1){ $jml2="-"; }
        $cekstok2 = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex', 'kode_konstruksi'=>$k])->row("prod_ff");
        //echo "<td>".$jml2."</td>";
        ?>
        <td><a href="<?=base_url('stok/roll/');?>"><?=$jml2;?></a></td>
        <?php
        //echo "<td>".$cekstok2."</td>";

        echo "</tr>";
        $no++;
      }
      echo "</table>";
  } //end
  function roll(){
      echo "tes";
  }
  
  function insgrey(){
      $cek = $this->db->query("SELECT * FROM new_roll_onpst GROUP BY kons");
      
      echo "<table border='1'>";
      echo "<tr>";
      echo "<td>No.</td>";
      echo "<td>Konstruksi</td>";
      echo "<td>Jumlah</td>";
      echo "</tr>";
      $no=1;
      foreach($cek->result() as $val){
        $jml = $this->db->query("SELECT SUM(ukuran) AS jml FROM new_roll_onpst WHERE kons='$val->kons'")->row("jml");
        echo "<tr>";
        echo "<td>".$no."</td>";
        echo "<td>".$val->kons."</td>";
        echo "<td>".number_format($jml)."</td>";
        echo "</tr>";
        $no++;
      }
      echo "</table>";
  } //end

  function pst(){
        $this->load->view("users/showstokonpst");
  }
  function sinkronstok(){
        $this->load->view("users/sinkronstokg");
  }
  function sinkronstok2(){
        $this->load->view("users/sinkronstokf");
  }
  function loadKirimanRJS(){
        $dbst = $this->db->query("SELECT loc,tgl FROM data_fol WHERE loc='RJS' AND tgl>='2025-02-10' GROUP BY tgl ORDER BY tgl DESC");
        if($dbst->num_rows() < 1){
            echo "<div style='width:100%; text-align:center;padding:50px 15px;color:red;'>Data ini mulai menampilkan per tanggal 10 Februari 2025</div>";
        } else {
        foreach($dbst->result() as $val){
            $tgl = date('d M Y', strtotime($val->tgl));
            $tgl1 = $val->tgl;
            $kons = $this->db->query("SELECT DISTINCT konstruksi FROM data_fol WHERE loc='RJS' AND tgl='$tgl1'");
            echo "<tr>";
            echo "<td>";
            echo "Kiriman Tanggal : <strong>".$tgl."</strong>";
                echo "<table>";
                foreach ($kons->result() as $value) { $ks = strtoupper($value->konstruksi);
                    $cek_g = $this->db->query("SELECT * FROM data_fol WHERE konstruksi='$ks' AND jns_fold='Grey' AND loc='RJS' AND tgl='$tgl1'")->num_rows();
                    $cek_f = $this->db->query("SELECT * FROM data_fol WHERE konstruksi='$ks' AND jns_fold='Finish' AND loc='RJS' AND tgl='$tgl1'")->num_rows();
                    if($cek_g > 0){
                        $cek_sum_g = $this->db->query("SELECT SUM(ukuran) AS total FROM data_fol WHERE konstruksi='$ks' AND jns_fold='Grey' AND loc='RJS' AND tgl='$tgl1'")->row("total");
                        if($cek_sum_g == floor($cek_sum_g)){ $total = number_format($cek_sum_g,0,',','.'); } else { $total = number_format($cek_sum_g,2,',','.'); ; }
                        echo "<tr>";
                        echo "<td>".$ks."</td>";
                        echo "<td>Grey</td>";
                        echo "<td>".$cek_g." Roll</td>";
                        echo "<td>$total Meter</td>";
                        echo "</tr>";
                    }
                    if($cek_f > 0){
                        $chto = $this->db->query("SELECT chto FROM tb_konstruksi WHERE kode_konstruksi='$ks'")->row("chto");
                        if($chto == "null" OR $chto =="NULL"){ $chto = $ks; }
                        $cek_sum_g = $this->db->query("SELECT SUM(ukuran) AS total FROM data_fol WHERE konstruksi='$ks' AND jns_fold='Finish' AND loc='RJS' AND tgl='$tgl1'")->row("total");
                        if($cek_sum_g == floor($cek_sum_g)){ $total = number_format($cek_sum_g,0,',','.'); } else { $total = number_format($cek_sum_g,2,',','.'); ; }
                        echo "<tr>";
                        echo "<td>".$chto."</td>";
                        echo "<td>Finish</td>";
                        echo "<td>".$cek_f." Roll</td>";
                        echo "<td>$total Yard</td>";
                        echo "</tr>";
                    }
               
                }
                echo "</table>";
            echo "</td>";
            echo "</tr>";
        }
        }
  }

  function loadpaketpst23(){
        $bln = ['00'=>'undf', '01'=>'Jan', '02'=>'Feb', '03'=>'Mar', '04'=>'Apr', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Ags', '09'=>'Sep', '10'=>'Okt', '11'=>'Nov', '12'=>'Des'];
        $yr = date('Y');
        $sj = $this->db->query("SELECT * FROM surat_jalan WHERE tujuan_kirim = 'Pusatex' ORDER BY tgl_kirim DESC LIMIT 200");
        if($sj->num_rows() > 0){
            echo '<tr>
                <td><strong>Tanggal</strong></td>
                <td><strong>SJ</strong></td>
                <td><strong>Jml PKG</strong></td>
                <td><strong>Total Panjang</strong></td>
            </tr>'; 
            foreach($sj->result() as $val):
                $x = explode('-',$val->tgl_kirim);
                $sj = $val->no_sj;
                $idsj = sha1($sj);
                if($x[0] == $yr){
                    $tgl = $x[2]." ".$bln[$x[1]];
                } else {
                    $tgl = $x[2]." ".$bln[$x[1]]." ".$x[0];
                }
                if($val->dep_asal == "RJS"){
                    echo "<tr style='background:#e9ecf2;'>";
                } else {
                    echo "<tr style='background:#d8dce3;'>";
                }
                $jmlpkg = $this->db->query("SELECT COUNT(no_sj) AS jml FROM new_tb_packinglist WHERE no_sj='$sj'")->row("jml");
                $ttl = $this->db->query("SELECT SUM(ttl_panjang) AS jml FROM new_tb_packinglist WHERE no_sj='$sj'")->row("jml");
                    echo "<td>".$tgl."</td>";
                    ?><td><a href="javascript:void(0);" onclick="kliksj('<?=$sj;?>', '<?=$idsj;?>')" style="color:#174091;text-decoration:none;"><?=$sj;?></a></td><?php
                    echo "<td style='text-align:center;'>".$jmlpkg."</td>";
                    echo "<td>".number_format($ttl,0,',','.')."</td>";
                    echo "</tr>"; 
            endforeach;
        } else {
            echo "<tr><td style='color:red;'>Tidak ada kiriman ke Pusatex</td></tr>";
        }
  } //end
  function loadsj(){
        $sj = $this->input->post('sj');
        $dt = $this->data_model->get_byid('new_tb_packinglist', ['no_sj'=>$sj]);
        if($dt->num_rows() > 0){
            foreach($dt->result() as $val){
                $ttl = number_format($val->ttl_panjang,0,',','.');
                ?><a href="javascript:void(0);" onclick="klikkonstruksi('<?=$val->kd;?>')" style="color:#000;text-decoration:none;font-weight:bold;"><?=$val->kode_konstruksi;?></a>  - <?=$val->jumlah_roll?> Roll - <?=$ttl?> Meter<br>
                <?php
                //echo "<strong><a href='javascript:void(0);' onlick='klikkonstruksi(\"".$val->kd."\")' style='text-decoration:none;color:#000;'>".$val->kode_konstruksi."</a></strong> - ".$val->jumlah_roll." Roll - ".$ttl." Meter<br>";
            }
        } else {
            echo "Tidak ada packinglist di dalam surat jalan.";
        }
  } //end

  function showsj(){
        $this->load->view('users/showtablesj');
  }


}
?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nusers extends CI_Controller
{
    function __construct()
    {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
   
    }
   
  function index(){
        $this->load->view('users/login');
  } //end

  function jualbs(){
    $this->load->view('users/new_jualbs');
  } //end
  function databs(){
    $this->load->view('users/new_databs');
  } //end-
  function timbangbs(){
    $this->load->view('users/new_databstimbang');
  } //end-timbangbs
  function datastokbs(){
    $this->load->view('users/new_databstok');
  }
  function loaddataallstok(){
      $kons= $this->input->post('kons');
      if($kons=="null"){
          $cekstok = $this->db->query("SELECT DISTINCT konstruksi FROM bs_stok_samatex ORDER BY konstruksi ASC");
      } else {
          $cekstok = $this->data_model->get_byid('bs_stok_samatex', ['konstruksi'=>$kons, 'lokasi_now'=>'Samatex']);
      }
      if($cekstok->num_rows() > 0){
          echo '<div class="table-container">';
          echo "<table>";
          if($kons == "null"){
          echo "<thead><tr><td>No.</td><td>Konstruksi</td><td>Total Roll</td><td>Total Panjang</td><td>Total Berat</td></tr></thead>";
          } else {
          echo "<thead><tr><td>No.</td><td>Kode roll</td><td>Konstruksi</td><td>Panjang</td><td>Berat</td></tr></thead>";
          }
          echo "<tbody>";
          $no=1;
          if($kons=="null"){
            foreach($cekstok->result() as $op){
                $kons = $op->konstruksi;
                $ttl_roll = $this->data_model->get_byid('bs_stok_samatex', ['konstruksi'=>$kons])->num_rows();
                $ttl_panjang = $this->db->query("SELECT SUM(ukuran_bs) AS jml FROM bs_stok_samatex WHERE konstruksi='$kons' AND lokasi_now='Samatex'")->row('jml');
                $ttl_berat = $this->db->query("SELECT SUM(berat_bs) AS jml FROM bs_stok_samatex WHERE konstruksi='$kons' AND lokasi_now='Samatex'")->row('jml');
                $tipe = $this->db->query("SELECT konstruksi,konstruksi_real FROM bs_stok_samatex WHERE konstruksi='$kons' LIMIT 1")->row('konstruksi_real');
                if($tipe="grey"){ $st = "Meter"; } else { $st = "Yard"; }
                echo "<tr>";
                echo "<td>".$no."</td>";
                ?>
                <td><a href="javascript:void(0);" onclick="loadData('<?=$kons?>')" style="text-decoration: none;color: #066fd1;"><?=$kons;?></a></td>
                <?php
                //echo "<td>".$kons."</td>";
                echo "<td>".$ttl_roll."</td>";
                echo "<td>".$ttl_panjang." ".$st."</td>";
                echo "<td>".$ttl_berat." Kg</td>";
                echo "</tr>";
                $no++;
            }
          } else {
            foreach($cekstok->result() as $op){
                if($op->konstruksi_real=="grey"){ $st = "Meter"; } else { $st = "Yard"; }
                echo "<tr>";
                echo "<td>".$no."</td>";
                echo "<td>".$op->iddataigbs."</td>";
                echo "<td>".$op->konstruksi."</td>";
                echo "<td>".$op->ukuran_bs." ".$st."</td>";
                echo "<td>".$op->berat_bs." Kg</td>";
                echo "</tr>";
                $no++;
            }
            echo "<tr><td colspan='3'>Total : </td><td>0</td><td>0</td></tr>";
            //echo "<tr><td colspan='5'>Tampilkan Semua</td></tr>";
            ?>
            <tr><td colspan="5"><a href="javascript:void(0);" onclick="loadData('null')" style="text-decoration: none;color: #066fd1;">Tampilkan Semua</a></td></tr>
            <?php
          }
          echo "</tbody>";
          echo "</table>";
      } else {
        echo "<table><tr><td>Data Stok Kosong!!</td></tr></table>";
      }
  }
  function carikode(){
      $username = $this->input->post('username');
      $dataBS = $this->input->post('dataBS');
      $dataBStipe = $this->input->post('dataBStipe');
      if($username!="" AND $dataBS!="" AND $dataBStipe!=""){
          if($dataBStipe=="grey"){
              $cek = $this->data_model->get_byid('bs_grey_samatex', ['iddataigbs'=>$dataBS, 'createstok'=>'no']);
              if($cek->num_rows() == 1){
                  $kd = $cek->row("iddataigbs");
                  $konstruksi_real = $cek->row("konstruksi_real");
                  $konstruksi = $cek->row("konstruksi");
                  $ukuran_bs = $cek->row("ukuran_bs");
                  echo json_encode(array("statusCode"=>200, "psn"=>"Kode Roll Ditemukan!!", "kode"=>$kd, "konstruksi"=>$konstruksi, "konstruksi_real"=>$konstruksi_real, "ukuran_bs"=>$ukuran_bs));
              } else {
                echo json_encode(array("statusCode"=>404, "psn"=>"Kode Roll Tidak Ditemukan!!"));
              }
          } else {
            if($dataBStipe=="finish"){
              $cek = $this->data_model->get_byid('bs_finish_samatex', ['iddataigbs'=>$dataBS, 'createstok'=>'no']);
              if($cek->num_rows() == 1){
                  $kd = $cek->row("iddataigbs");
                  $konstruksi_real = $cek->row("konstruksi_real");
                  $konstruksi = $cek->row("konstruksi");
                  $ukuran_bs = $cek->row("ukuran_bs");
                  echo json_encode(array("statusCode"=>200, "psn"=>"Kode Roll Ditemukan!!", "kode"=>$kd, "konstruksi"=>$konstruksi, "konstruksi_real"=>$konstruksi_real, "ukuran_bs"=>$ukuran_bs));
              } else {
                echo json_encode(array("statusCode"=>404, "psn"=>"Kode Roll Tidak Ditemukan!!"));
              }
            } else {
              echo json_encode(array("statusCode"=>404, "psn"=>"Anda tidak memilih finish/grey"));
            }
          }
      } else {
          echo json_encode(array("statusCode"=>404, "psn"=>"Anda tidak mengisi data dengan benar!!"));
      }
  }
  function loaddataisi(){
      $id = $this->input->post('id');
      $ar_id = explode('-',$id);
      $tipe = $this->input->post('tipe');
      $username = $this->input->post('username');
      $dataBStipe = $this->input->post('dataBStipe');
      if($dataBStipe == "grey"){
          $no=1; $total_meter = 0;
          foreach($ar_id as $id){
              $cek = $this->data_model->get_byid('bs_grey_samatex', ['iddataigbs'=>$id])->row_array();
              $mtr = $cek['ukuran_bs'];
              $total_meter = $total_meter + $mtr;
              echo $no.". Kode <strong>".$id."</strong> Konstruksi <strong>".$cek['konstruksi_real']."</strong> Ukuran <strong>".$cek['ukuran_bs']."</strong> Meter &nbsp;";
              ?>
              <a href="javascript:void(0);" style="color:red;" onclick="hapusKode('<?=$id?>','grey','<?=$mtr;?>')">Hapus</a>
              <?php
              echo "<br>";
              $no++;
          }
          echo "Total Panjang : <strong>".round($total_meter,2)."</strong> Meter";
      } elseif($dataBStipe == "finish"){ 
          $no=1; $total_meter = 0;
          foreach($ar_id as $id){
              $cek = $this->data_model->get_byid('bs_finish_samatex', ['iddataigbs'=>$id])->row_array();
              $mtr = $cek['ukuran_bs'];
              $total_meter = $total_meter + $mtr;
              echo $no.". Kode <strong>".$id."</strong> Konstruksi <strong>".$cek['konstruksi_real']."</strong> Ukuran <strong>".$cek['ukuran_bs']."</strong> Meter &nbsp;";
              ?>
              <a href="javascript:void(0);" style="color:red;" onclick="hapusKode('<?=$id?>','finish','<?=$mtr;?>')">Hapus</a>
              <?php
              echo "<br>";
              $no++;
          }
          $yar = $total_meter * 0.9144;
          echo "Total Panjang : <strong>".round($total_meter,2)."</strong> Meter / <strong>".round($yar,2)."</strong> Yard";
      } else {
          echo "";
      }
  }
  function simpanisidata2(){
        $user = $this->input->post('username');
        $kons = $this->input->post('kons');
        $pjg = $this->input->post('pjg');
        $brt = $this->input->post('brt');
        $cekusername = $this->data_model->get_byid('a_operator', ['username'=>$user]);
        if ($kons == "BS L 120 GREY" || $kons == "BS L 135 GREY" || $kons == "BS L 150 GREY" || $kons == "BS L 90 GREY" || $kons == "BS MAKLOON" || $kons == "AVAL GREY") {
            $tipe = "grey";
        } else {
            $tipe = "finish";
        }
        if($cekusername->num_rows() == 1){
            $dtlist = [
                'kode_roll' => '0',
                'konstruksi' => $kons,
                'konstruksi_real' => $tipe,
                'ukuran_bs' => $pjg,
                'berat_bs' => $brt,
                'tgl' => date('Y-m-d'),
                'shift_op' => date('H:i:s'),
                'keterangan' => '0',
                'operator' => $user,
                'dep' => 'Samatex',
                'lokasi_now' => 'Samatex',
                'asalbs' => 'SL'
            ];
            $this->data_model->saved('bs_stok_samatex',$dtlist);
            echo json_encode(array("statusCode"=>200, "psn"=>"Berhasil menyimpan ke stok.!!"));
        } else {
            echo json_encode(array("statusCode"=>404, "psn"=>"Anda harus login.!!"));
        }
  } //end
  function simpanisidata(){
      $id = $this->input->post('id');
      $id2 = $this->input->post('id');
      $username = $this->input->post('username');
      $allkons = $this->input->post('allkons');
      $berat = $this->input->post('berat');
      $tipe = $this->input->post('dataBStipe');
      $cekusername = $this->data_model->get_byid('a_operator', ['username'=>$username]);
      if($cekusername->num_rows() == 1){
          $all_id = explode('-',$id);
          $all_mtr = 0;
          foreach($all_id as $id){
              if($tipe=="grey"){
                  $dt = $this->data_model->get_byid('bs_grey_samatex', ['iddataigbs'=>$id])->row_array();
                  $this->data_model->updatedata('iddataigbs',$id,'bs_grey_samatex',['createstok'=>'yes']);
              } 
              if($tipe=="finish"){
                  $dt = $this->data_model->get_byid('bs_finish_samatex', ['iddataigbs'=>$id])->row_array();
                  $this->data_model->updatedata('iddataigbs',$id,'bs_finish_samatex',['createstok'=>'yes']);
              }
              $mtr = $dt['ukuran_bs'];
              $all_mtr = $all_mtr + $mtr;
          } 
          $dtlist = [
            'kode_roll' => '0',
            'konstruksi' => $allkons,
            'konstruksi_real' => $tipe,
            'ukuran_bs' => $all_mtr,
            'berat_bs' => $berat,
            'tgl' => date('Y-m-d'),
            'shift_op' => date('H:i:s'),
            'keterangan' => '0',
            'operator' => $username,
            'dep' => 'Samatex',
            'lokasi_now' => 'Samatex',
            'asalbs' => $id2
          ];
          $ceksaved = $this->data_model->get_byid('bs_stok_samatex', $dtlist)->num_rows();
          if($ceksaved==0){ $this->data_model->saved('bs_stok_samatex', $dtlist); }
          echo json_encode(array("statusCode"=>200, "psn"=>"success"));
      } else {
          echo json_encode(array("statusCode"=>404, "psn"=>"Anda perlu login ulang..!!"));
      }
  }
  function loadTimbangan(){
      $ini = $this->input->post('ini');
      if($ini=="null"){
          $tgl = date('Y-m-d');
          $printTgl = date('d M Y', strtotime($tgl));
      } else {
          $tgl = $ini;
          $printTgl = date('d M Y', strtotime($tgl));
      }
      $user = $this->input->post('username');
      $cekusername = $this->data_model->get_byid('a_operator', ['username'=>$user]);
      if($cekusername->num_rows() == 1){
          if($ini=="null"){
          echo '<label for="dataBS" class="lbl">Timbang Hari ini, '.$printTgl.'</label>';
          } else {
          echo '<label for="dataBS" class="lbl">Timbang tanggal, '.$printTgl.'</label>';
          }
          //$alldata = $this->data_model->get_byid('bs_stok_samatex', ['tgl'=>$tgl, 'operator'=>$user]);
          if($user=="edi" OR $user=="syafiq"){
            $alldata = $this->db->query("SELECT * FROM bs_stok_samatex WHERE tgl='$tgl' ORDER BY iddataigbs DESC");
            echo "<div style='width:100%;display:flex;justify-content:space-between;align-items:center;margin-top:10px;'>";
            echo "<span>Tampilkan Tanggal</span>";
            ?>
            <input type="date" id="newdate" style="padding:10px;" value="<?=$tgl?>" onchange="hariIni(this.value)">
            <?php
            echo "</div>";
          } else {
            $alldata = $this->db->query("SELECT * FROM bs_stok_samatex WHERE tgl='$tgl' AND operator='$user' ORDER BY iddataigbs DESC");
          }
          
          if($alldata->num_rows() > 0){
            echo "<div style='width:100%;overflow-x:auto;'><table style='width:100%;border-collapse:collapse;margin-top:7px;border:1px solid #000;'>";
            echo "<tr>";
            echo "<td>Kode Roll</td>";
            echo "<td>Konstruksi</td>";
            echo "<td>Ukuran</td>";
            echo "<td>Berat</td>";
            echo "<td>Del</td>";
            echo "</tr>";
            foreach($alldata->result() as $n => $val){
                if($val->konstruksi_real == "grey"){
                    $satuan = "Meter";
                    $ukuran = $val->ukuran_bs;
                } else {
                    $satuan = "Yard";
                    $ukuran = $val->ukuran_bs * 0.9144;
                    $ukuran = round($ukuran,2);
                }
                echo "<tr>";
                echo "<td><strong>".$val->iddataigbs."</strong></td>";
                echo "<td>".$val->konstruksi."</td>";
                echo "<td>".$ukuran." ".$satuan."</td>";
                echo "<td>".$val->berat_bs." Kg</td>";
                //echo "<td>Hapus</td>";
                ?>
                <td><a href="javascript:;" style="color:red;text-decoration:none;" onclick="deldata('<?=$val->iddataigbs;?>')" title="Hapus Data">Hapus</a></td>
                <?php
                echo "</tr>";
            }
            echo "</table></div>";
          } else {
            echo "<p style='margin-top:7px;'>Hari ini belum ada timbangan.</p>";
          }
      } else {
          echo '<label for="dataBS" class="lbl">Timbang Hari ini, '.$printTgl.'</label><br>';
          echo "<p>Anda perlu login ulang..!!</p>";
      }
  }
  function delisiData(){
      $id = $this->input->post('id');
      $cek = $this->data_model->get_byid('bs_stok_samatex',['iddataigbs'=>$id])->row_array();
      $asalbs = $cek['asalbs'];
      $real = $cek['konstruksi_real'];
      $ex = explode('-', $asalbs);
      foreach($ex as $val){
          if($real=="finish"){
              $this->data_model->updatedata('iddataigbs',$val,'bs_finish_samatex',['createstok'=>'no']);
          } else {
              $this->data_model->updatedata('iddataigbs',$val,'bs_grey_samatex',['createstok'=>'no']);
          }
      }
      $this->data_model->delete('bs_stok_samatex','iddataigbs',$id);
      echo json_encode(array("statusCode"=>200, "psn"=>"success"));
  }
  function loaddata(){
        $id = $this->input->post('id');
        $tipe = $this->input->post('num');
        $username = $this->input->post('username');
          if($id == "grey"){
              if($tipe == "null"){
                //$qry = $this->data_model->get_spesifik('lokasi_now','Samatex','iddataigbs','bs_grey_samatex');
                $qry = $this->db->query("SELECT DISTINCT konstruksi FROM bs_grey_samatex WHERE lokasi_now='Samatex' AND createstok='no' ORDER BY konstruksi ASC");
              } else {
                $qry = $this->db->query("SELECT * FROM bs_grey_samatex WHERE konstruksi='$tipe' AND lokasi_now='Samatex' AND createstok='no' ORDER BY iddataigbs ASC");
              }
              $total_bs = $this->db->query("SELECT SUM(ukuran_bs) AS mtr FROM bs_grey_samatex WHERE lokasi_now='Samatex' AND createstok='no'")->row("mtr");
              $berat_bs = $this->db->query("SELECT SUM(berat_bs) AS mtr FROM bs_grey_samatex WHERE lokasi_now='Samatex' AND createstok='no'")->row("mtr");
              $kode_bs = "BSG";
              $kode_dt = "grey";
              $_table = "bs_grey_samatex";
          } else {
              //$qry = $this->data_model->get_spesifik('lokasi_now','Samatex','iddataigbs','bs_finish_samatex');
              if($tipe == "null"){
                //$qry = $this->data_model->get_spesifik('lokasi_now','Samatex','iddataigbs','bs_grey_samatex');
                $qry = $this->db->query("SELECT DISTINCT konstruksi FROM bs_finish_samatex WHERE lokasi_now='Samatex' AND createstok='no' ORDER BY konstruksi ASC");
              } else {
                $qry = $this->db->query("SELECT * FROM bs_finish_samatex WHERE konstruksi='$tipe' AND lokasi_now='Samatex' AND createstok='no' ORDER BY iddataigbs ASC");
              }
              $total_bs = $this->db->query("SELECT SUM(ukuran_bs) AS mtr FROM bs_finish_samatex WHERE lokasi_now='Samatex' AND createstok='no'")->row("mtr");
              $berat_bs = $this->db->query("SELECT SUM(berat_bs) AS mtr FROM bs_finish_samatex WHERE lokasi_now='Samatex' AND createstok='no'")->row("mtr");
              $kode_bs = "BSF";
              $kode_dt = "finish";
              $_table = "bs_finish_samatex";
          }
          $num = $qry->num_rows();
          ?>
          <div style="width:100%;display:flex;justify-content:space-between;align-items:center;">
            <span>Jumlah Roll : <strong><?php echo number_format($num); ?></strong></span>
            <span>Total : <strong><?php echo number_format($total_bs); ?></strong> Mtr / <strong><?php echo number_format($berat_bs); ?></strong> Kg</span>
          </div>
          <?php
          // echo "<span>Jumlah Data : <strong>".number_format($num)."</strong></span>";
          // echo "<span>Total Panjang : <strong>".number_format($total_bs)."</strong></span>";
          // echo "<span>Total Berat : <strong>".number_format($berat_bs)."</strong></span>";
          echo '<div class="table-container">';
          echo "<table>";
          echo "<thead><tr>";
          if($tipe=="null"){
            echo "<th>No</th><th>Konstruksi</th><th>Roll</th><th>Meter</th><th>KG</th>";
          } else {
            echo "<th>No.</th>";
            echo "<th>Kode</th>";
            echo "<th>Konstruksi</th>";
            echo "<th>Kain</th>";
            echo "<th>Panjang (Mtr)</th>";
            echo "<th>Berat (Kg)</th>";
            echo "<th>Tanggal</th>";
            echo "<th>Operator</th>";
            echo "<th>Del</th>";
          }
          echo "</tr>";
          echo "</thead><tbody>";
          $no=1;
          if($tipe=="null"){
              foreach($qry->result() as $val){
                $_kons = $val->konstruksi;
                $_roll = $this->data_model->get_byid($_table, ['konstruksi'=>$_kons])->num_rows();
                $_mtr = $this->db->query("SELECT SUM(ukuran_bs) AS mtr FROM $_table WHERE konstruksi='$_kons'")->row("mtr");
                $_kg = $this->db->query("SELECT SUM(berat_bs) AS mtr FROM $_table WHERE konstruksi='$_kons'")->row("mtr");
                echo "<tr>";
                echo "<th style='text-align:center;'>".$no++."</th>";
                //echo "<td>".$_kons."</td>";
                ?><td><a href="javascript:void(0);" onclick="loadData('<?=$kode_dt;?>','<?=$_kons;?>')" style='text-decoration:none;color:#0d6adb;font-weight:bold;'><?=$_kons;?></a></td><?php
                echo "<td>".number_format($_roll)."</td>";
                echo "<td>".number_format($_mtr,2)."</td>";
                echo "<td>".number_format($_kg,2)."</td>";
                echo "</tr>";
              }
          } else {
            foreach($qry->result() as $val){
                $kodebs = $kode_bs."".$val->iddataigbs;
                echo "<tr>";
                echo "<th style='text-align:center;'>".$no++."</th>";
                echo "<th>".$val->iddataigbs."</th>";
                echo "<td>".$val->konstruksi."</td>";
                echo "<td>".$val->konstruksi_real."</td>";
                echo "<td>".$val->ukuran_bs."</td>";
                echo "<td>".$val->berat_bs."</td>";
                echo "<td>".date("d M Y", strtotime($val->tgl))."</td>";
                echo "<td>".ucfirst($val->operator)."</td>";
                ?>
                <td>
                  <a href="javascript:void(0);" onclick="delbs('<?=$val->iddataigbs;?>','<?=$kodebs;?>','<?=$val->ukuran_bs;?>')"><img src="<?=base_url('assets/del.png');?>" alt="Delete" style="width:17px;" ></a>
                </td>
                <?php
                echo "</tr>";
            }
          }
          echo "</tbody></table></div>";
        
  } //end load data

  function deldata(){
      $kd = $this->input->post('kd');
      if (strpos($kd, 'BSG') === 0) {
          $x = explode("G", $kd);
          $id = $x[1];
          $this->db->query("DELETE FROM bs_grey_samatex WHERE iddataigbs = '$id'");
          echo json_encode(array("statusCode"=>200, "psn"=>"grey"));
      } elseif (strpos($kd, 'BSF') === 0) {
          $x = explode("F", $kd);
          $id = $x[1];
          $this->db->query("DELETE FROM bs_finish_samatex WHERE iddataigbs = '$id'");
          echo json_encode(array("statusCode"=>200, "psn"=>"finish"));
      } else {
          echo json_encode(array("statusCode"=>404, "psn"=>"Kode tidak valid"));
      }
  } //end 

  function createPaket(){
        $user = $this->input->post('personName');
        $kons = $this->input->post('kons');
        $tujuan = $this->input->post('tujuan');
        $tipe = $this->input->post('tipe');
        $newkons = $this->input->post('newkons');
        if($newkons!="new"){ $kons=$newkons; }
        $newtujuan = $this->input->post('newtujuan');
        if($newtujuan!="new"){ $tujuan=$newtujuan; }
        $cekuser = $this->data_model->get_byid('a_operator', ['username'=>$user])->num_rows();
        $cekTipe = $this->db->query("SELECT * FROM bs_stok_samatex WHERE konstruksi='$kons' LIMIT 1")->row("konstruksi_real");
        if($cekTipe=="grey"){
            $_thisBS = "Grey";
            $cekkode = $this->db->query("SELECT * FROM bs_paket WHERE jenis='grey' ORDER BY idpktbs DESC LIMIT 1");
            if($cekkode->num_rows() == 1){
                $pktbs = $cekkode->row("pktbs");
                $x = explode('G', $pktbs);
                $no = intval($x[1]);
                $no_data = $no + 1;
                $num = sprintf("%03d", $no_data);
                $_thisKode = "PG".$num;
            } else {
                $_thisKode = "PG001";
            }
        } else {
            $_thisBS = "Finish";
            $cekkode = $this->db->query("SELECT * FROM bs_paket WHERE jenis='finish' ORDER BY idpktbs DESC LIMIT 1");
            if($cekkode->num_rows() == 1){
                $pktbs = $cekkode->row("pktbs");
                $x = explode('F', $pktbs);
                $no = intval($x[1]);
                $no_data = $no + 1;
                $num = sprintf("%03d", $no_data);
                $_thisKode = "PF".$num;
            } else {
                $_thisKode = "PF001";
            }
        }
        if($tipe=="new"){
            $this->data_model->saved('bs_paket', [
                'pktbs'       => $_thisKode,
                'konstruksi'  => $kons,
                'jenis'       => strtolower($_thisBS),
                'tgl_buat'    => date('Y-m-d'),
                'username'    => $user,
                'tujuan'      => strtoupper($tujuan),
                'jmlroll'     => 0,
                'panjang'     => 0,
                'berat'       => 0
            ]); 
        }

        if($cekuser == 1){
            ?>
            <div style="width:100%;display:flex;flex-direction:column;">
                <span>Masukan Kode Roll BS <?=$_thisBS;?> :</span>
                <div class="input-container">
                    <input type="text" placeholder="Masukkan angka saja..." class="text-input" id="angkaKode">
                    <button class="add-button" onclick="tambahkan()">Tambahkan</button>
                </div>
                <input type="hidden" id="hurufKode" value="<?=$_thisBS=='Grey' ? 'BSG':'BSF';?>">
                <input type="hidden" id="kodePaket" value="<?=$_thisKode;?>">
                <div class="table-container" style="margin-top:10px;" id="idPaketNew"></div>
                <div class="buttonData submit" id="btnRefresh" style="margin-top:10px;" onclick="refresh()">Selesai</div>
            </div>
            <?php
        
        } else {
            echo json_encode(array("statusCode"=>503, "psn"=>"Anda perlu login.!!"));
        }
  } //end
  function addkodeToPaket(){
      $id = $this->input->post('id');
      $kd = $this->input->post('kd');
      $pkt = $this->input->post('pkt');
      if($kd == "BSF"){
        $_table = 'finish';
      } else {
        $_table = 'grey';
      }
      $konstruksi = $this->data_model->get_byid('bs_paket', ['pktbs'=>$pkt])->row('konstruksi');
      $cek_id = $this->data_model->get_byid('bs_stok_samatex', ['iddataigbs'=>$id,'lokasi_now'=>'Samatex']);
      if($cek_id->num_rows() == 1){
          $_konstruksi = $cek_id->row('konstruksi');
          if($_konstruksi == $konstruksi){
            $this->data_model->updatedata('iddataigbs',$id,'bs_stok_samatex',['lokasi_now'=>$pkt]);
            $jmlroll = $this->data_model->get_byid('bs_stok_samatex',['lokasi_now'=>$pkt])->num_rows();
            $ukr = $this->db->query("SELECT SUM(ukuran_bs) AS jml FROM bs_stok_samatex WHERE lokasi_now='$pkt'")->row("jml");
            $brt = $this->db->query("SELECT SUM(berat_bs) AS jml FROM bs_stok_samatex WHERE lokasi_now='$pkt'")->row("jml");
            $this->data_model->updatedata('pktbs',$pkt,'bs_paket',['jmlroll'=>$jmlroll,'panjang'=>$ukr,'berat'=>$brt]);
            echo json_encode(array("statusCode"=>200, "psn"=>"okes"));
          } else {
            echo json_encode(array("statusCode"=>404, "psn"=>"Kode roll tidak sesuai konstruksi..!!"));
          }
      } else {
          echo json_encode(array("statusCode"=>404, "psn"=>"Kode roll tidak ditemukan.!!"));
      }
  } //end
  function loadPaket(){
      $pkt = $this->input->post('pkt');
      $cek = $this->data_model->get_byid('bs_paket', ['pktbs'=>$pkt]);
      if($cek->num_rows() == 1){
          $tujuan = $cek->row('tujuan');
          $kons = $cek->row('konstruksi');
          $jenis = $cek->row('jenis');
          echo "<div style='width:100%;display:flex;flex-direction:column;padding:8px;'>";
          echo "<span>No Paket : <strong>".$pkt."</strong></span>";
          echo "<span>Tujuan : <strong>".$tujuan."</strong></span>";
          echo "<span>Konstruksi : <strong>".$kons."</strong></span>";
          echo "</div><table>";
          echo "<thead>";
          echo "<tr>";
          echo "<th>No</th>";
          echo "<th>Kode Roll</th>";
          echo "<th>Meter</th>";
          echo "<th>Berat</th>";
          echo "<th></th>";
          echo "</tr>";
          echo "</thead>";
          echo "<tbody>";
          if($jenis=="finish"){
            //$isipaket = $this->data_model->get_byid('bs_finish_samatex', ['lokasi_now'=>$pkt]);
            $_kode = "BSF";
            //$_table = "bs_finish_samatex";
          } else {
            //$isipaket = $this->data_model->get_byid('bs_grey_samatex', ['lokasi_now'=>$pkt]);
            $_kode = "BSG";
            //$_table = "bs_grey_samatex";
          }
          $isipaket = $this->data_model->get_byid('bs_stok_samatex', ['lokasi_now'=>$pkt]);
          $_table = "bs_stok_samatex";
          $_total_panjang=0; $_total_berat=0;
          foreach($isipaket->result() as $key => $value){
              $_total_panjang += $value->ukuran_bs;
              $_total_berat += $value->berat_bs;
              echo "<tr>";
              echo "<td>".($key+1)."</td>";
              echo "<td>".$_kode."".$value->iddataigbs."</td>";
              echo "<td>".number_format($value->ukuran_bs)."</td>";
              echo "<td>".number_format($value->berat_bs,2)."</td>";
              ?>
              <td>
                  <a href="javascript:void(0);" onclick="kembalikanRoll('<?=$value->iddataigbs;?>','<?=$_table;?>','<?=$pkt;?>')"><img src="<?=base_url('assets/del.png');?>" alt="Delete" style="width:17px;" ></a>
              </td>
              <?php
              //echo "<td><button class='btn btn-danger' onclick='hapusKode(".$value->iddataigbs.")'>Hapus</button></td>";
              echo "</tr>";
          }
          echo "<tr>";
          echo "<td colspan='2'><strong>Total</strong></td>";
          echo "<td>".number_format($_total_panjang,2)."</td>";
          echo "<td>".number_format($_total_berat,2)."</td><td></td></tr>";
          echo "</tbody>";
          echo "</table>";
      }
  } //end

  function loadAllPaket(){
      $allpaket=$this->db->query("SELECT * FROM bs_paket ORDER BY idpktbs DESC");
      if($allpaket->num_rows() > 0){
        echo "<div class='table-container'><table>";
          echo "<thead>";
          echo "<tr>";
          echo "<th>No</th>";
          echo "<th>Paket</th>";
          echo "<th>Tujuan</th>";
          echo "<th>Konstruksi</th>";
          echo "<th>Roll</th>";
          echo "<th>Jumlah</th>";
          echo "<th>SJ</th>";
          echo "<th></th>";
          echo "</tr>";
          echo "</thead>";
          echo "<tbody>";
          echo "</tbody>";
          $no=0;
          foreach($allpaket->result() as $val){
              $idpktbs = $val->idpktbs;
              $kons = $val->konstruksi;
              $tujuan = $val->tujuan;
              $jenis = $val->jenis;
              $pktbs = $val->pktbs;
              $pnjg = $val->panjang;
              $brt = $val->berat;
              if(floor($pnjg) == $pnjg){
                  $_pjg = number_format($pnjg,0,',','.');
              } else {
                  $_pjg = number_format($pnjg,2,',','.');
              }
              if(floor($brt) == $brt){
                  $_brt = number_format($brt,0,',','.');
              } else {
                  $_brt = number_format($brt,2,',','.');
              }
              echo "<tr>";
              echo "<td>".($no+1)."</td>";
              ?>
              <td><a href="javascript:void(0);" style="text-decoration:none;color:#033b9c;" onclick="showAllData('<?=$val->pktbs;?>','<?=$kons;?>','<?=$tujuan;?>')"><?=$val->pktbs;?></a></td>
              <?php
              //echo "<td>".$val->pktbs."</td>";
              echo "<td>".$val->tujuan."</td>";
              echo "<td>".$val->konstruksi."</td>";
              echo "<td>".number_format($val->jmlroll)."</td>";
              echo "<td>".$_pjg." M / ".$_brt." Kg</td>";
              if($val->surat_jalan == "null"){
                echo "<td>-</td>";
              } else {
                echo "<td>".$val->surat_jalan."</td>";
              }
              
              ?>
              <td>
                  <a href="javascript:void(0);" onclick="DeletePaket('<?=$pktbs;?>')"><img src="<?=base_url('assets/del.png');?>" alt="Delete" style="width:17px;" ></a>
              </td>
              <?php
              echo "</tr>";
              $no++;
          }
          echo "</table>";
          echo "</div>";
      } else {
        echo "Belum ada paket penjualan BS";
      }
  } //end

  function delPaket(){
      $id = $this->input->post('id');
      $cek = $this->data_model->get_byid('bs_paket',['pktbs'=>$id]);
      if($cek->num_rows() == 1){
          $jenis = $cek->row("jenis");
          $jml = $this->data_model->get_byid('bs_stok_samatex',['lokasi_now'=>$id])->num_rows();
          if($jml > 0){
              echo json_encode(array("statusCode"=>404, "psn"=>"Anda harus menghapus isi dalam paket!!"));
          } else {
              $this->db->query("DELETE FROM bs_paket WHERE pktbs='$id'");
              echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
          }
      } else {
          echo json_encode(array("statusCode"=>404, "psn"=>"Kode Paket Tidak Ditemukan!!"));
      }
  } //end

  function backPaket(){
      $id = $this->input->post('id');
      $_table = $this->input->post('tipe');
      $pkt = $this->input->post('pkt');
      $this->data_model->updatedata('iddataigbs',$id,$_table,['lokasi_now'=>'Samatex']);
      $jmlroll = $this->data_model->get_byid($_table,['lokasi_now'=>$pkt])->num_rows();
      $ukr = $this->db->query("SELECT SUM(ukuran_bs) AS jml FROM $_table WHERE lokasi_now='$pkt'")->row("jml");
      $brt = $this->db->query("SELECT SUM(berat_bs) AS jml FROM $_table WHERE lokasi_now='$pkt'")->row("jml");
      $this->data_model->updatedata('pktbs',$pkt,'bs_paket',['jmlroll'=>$jmlroll,'panjang'=>$ukr,'berat'=>$brt]);
      echo "success";
  }

}
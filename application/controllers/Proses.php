<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proses extends CI_Controller
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
  function autostok(){
      $stok = $this->data_model->get_record('data_stok');
      foreach($stok->result() as $val){
          $id = $val->idstok;
          $_ig = $val->prod_ig;
          $_fg = $val->prod_fg;
          $_if = $val->prod_if;
          $_ff = $val->prod_ff;
          if($_ig < 0){ $this->data_model->updatedata('idstok',$id,'data_stok',['prod_ig'=>'0']); }
          //if($_fg < 0){ $this->data_model->updatedata('idstok',$id,'data_stok',['prod_fg'=>'0']); }
          if($_if < 0){ $this->data_model->updatedata('idstok',$id,'data_stok',['prod_if'=>'0']); }
          //if($_ff < 0){ $this->data_model->updatedata('idstok',$id,'data_stok',['prod_ff'=>'0']); }
      }
  } //end
  function savestok(){
      $tgl = date('Y-m-d');
      $this->db->query("DELETE FROM lock_stok WHERE tanggal='$tgl'");
    //   $this->data_model->saved('lock_stok', [
    //         'tanggal' => $tgl,
    //         'konstruksi' => 'coba',
    //         'stok_grey' => '123',
    //         'stok_finish' => '321'
    //   ]);
      $qur = $this->db->query("SELECT * FROM data_stok WHERE dep='newSamatex' ");
      foreach($qur->result() as $val):
          $kons = $val->kode_konstruksi;
          $fg = $val->prod_fg;
          $ff = $val->prod_ff;
          if($fg > 0){ $stok_fg = $fg; } else { $stok_fg = "0"; }
          if($ff > 0){ $stok_ff = $ff; } else { $stok_ff = "0"; }
          if($fg < 1 && $ff < 1){} else {
             $this->data_model->saved('lock_stok', [
                'tanggal' => $tgl,
                'konstruksi' => $kons,
                'stok_grey' => $stok_fg,
                'stok_finish' => $stok_ff
             ]);
          }
      endforeach;
  }
  
  function prosestespiutang(){
    $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; 
      $cus = $this->data_model->get_record('dt_konsumen');
      foreach($cus->result() as $val){
          $idcus = $val->id_konsumen;
          $_nmcus = $val->nama_konsumen;
          ?>
          <table class="table table-bordered stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort" style="text-align:center;">Tanggal</th>
										<!-- <th style="text-align:center;">Keterangan</th> -->
										<th style="text-align:center;">No Faktur</th>
										<!-- <th style="text-align:center;">Nomor Bukti</th> -->
										<th style="text-align:center;">Jenis Barang</th>
										<th style="text-align:center;">Panjang</th>
										<th style="text-align:center;">Harga</th>
										<th style="text-align:center;">Total Harga</th>
										<th style="text-align:center;">Bayar</th>
                                        <th style="text-align:center;">Saldo</th>
									</tr>
								</thead>
								<tbody>
                                    <?php
                                    $tglar = array();
                                    $jenis = array();
                                    $arid = array();
                                    $surjal = array();
                                    $konstruksi = array();
                                    $panjang = array();
                                    $harga = array();
                                    $harga_total = array();
                                    $idcus = $val->id_konsumen;
                                    $query = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.konstruksi, a_nota.total_panjang, a_nota.harga_satuan, a_nota.total_harga, a_nota.tgl_nota, surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj=surat_jalan.no_sj AND surat_jalan.id_customer='$idcus'");
                                    
                                    foreach($query->result() as $val):
                                        $tglar[]= $val->tgl_nota;
                                        $jenis[]='Pembelian';
                                        $arid[]=$val->id_nota;
                                        $surjal[]=$val->no_sj;
                                        $konstruksi[] = $val->konstruksi;
                                        $panjang[] = $val->total_panjang;
                                        $harga[] = $val->harga_satuan;
                                        $harga_total[] = $val->total_harga;
                                        $idnota = $val->id_nota;
                                        $cekbyr = $this->data_model->get_byid('a_nota_bayar2', ['id_nota'=>$idnota]);
                                        foreach($cekbyr->result() as $val2):
                                            $tglar[]= $val2->tgl_pemb;
                                            $jenis[]='Pembayaran';
                                            $arid[]=$val2->id_pemb2;
                                            $surjal[]="null";
                                            $konstruksi[] = "null";
                                            $panjang[] = "null";
                                            $harga[] = "null";
                                            $harga_total[] = "null";
                                        endforeach;
                                    endforeach;
                                    if($idcus==100){
                                        $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'KM%'");
                                        foreach ($allIdKm->result() as $key => $value) {
                                            $newid = $value->id_konsumen;
                                            $query = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.konstruksi, a_nota.total_panjang, a_nota.harga_satuan, a_nota.total_harga, a_nota.tgl_nota, surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj=surat_jalan.no_sj AND surat_jalan.id_customer='$newid'");
                                    
                                            foreach($query->result() as $val):
                                                $tglar[]= $val->tgl_nota;
                                                $jenis[]='Pembelian';
                                                $arid[]=$val->id_nota;
                                                $surjal[]=$val->no_sj;
                                                $konstruksi[] = $val->konstruksi;
                                                $panjang[] = $val->total_panjang;
                                                $harga[] = $val->harga_satuan;
                                                $harga_total[] = $val->total_harga;
                                                $idnota = $val->id_nota;
                                                $cekbyr = $this->data_model->get_byid('a_nota_bayar2', ['id_nota'=>$idnota]);
                                                foreach($cekbyr->result() as $val2):
                                                    $tglar[]= $val2->tgl_pemb;
                                                    $jenis[]='Pembayaran';
                                                    $arid[]=$val2->id_pemb2;
                                                    $surjal[]="null";
                                                    $konstruksi[] = "null";
                                                    $panjang[] = "null";
                                                    $harga[] = "null";
                                                    $harga_total[] = "null";
                                                endforeach;
                                            endforeach;
                                        }
                                    } //if KM ended
                                    if($idcus==29){
                                        $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'PS%'");
                                        foreach ($allIdKm->result() as $key => $value) {
                                            $newid = $value->id_konsumen;
                                            $query = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.konstruksi, a_nota.total_panjang, a_nota.harga_satuan, a_nota.total_harga, a_nota.tgl_nota, surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj=surat_jalan.no_sj AND surat_jalan.id_customer='$newid'");
                                    
                                            foreach($query->result() as $val):
                                                $tglar[]= $val->tgl_nota;
                                                $jenis[]='Pembelian';
                                                $arid[]=$val->id_nota;
                                                $surjal[]=$val->no_sj;
                                                $konstruksi[] = $val->konstruksi;
                                                $panjang[] = $val->total_panjang;
                                                $harga[] = $val->harga_satuan;
                                                $harga_total[] = $val->total_harga;
                                                $idnota = $val->id_nota;
                                                $cekbyr = $this->data_model->get_byid('a_nota_bayar2', ['id_nota'=>$idnota]);
                                                foreach($cekbyr->result() as $val2):
                                                    $tglar[]= $val2->tgl_pemb;
                                                    $jenis[]='Pembayaran';
                                                    $arid[]=$val2->id_pemb2;
                                                    $surjal[]="null";
                                                    $konstruksi[] = "null";
                                                    $panjang[] = "null";
                                                    $harga[] = "null";
                                                    $harga_total[] = "null";
                                                endforeach;
                                            endforeach;
                                        }
                                    } //if ps ended
                                    if($idcus==101){
                                        $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'PB%'");
                                        foreach ($allIdKm->result() as $key => $value) {
                                            $newid = $value->id_konsumen;
                                            $query = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.konstruksi, a_nota.total_panjang, a_nota.harga_satuan, a_nota.total_harga, a_nota.tgl_nota, surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj=surat_jalan.no_sj AND surat_jalan.id_customer='$newid'");
                                    
                                            foreach($query->result() as $val):
                                                $tglar[]= $val->tgl_nota;
                                                $jenis[]='Pembelian';
                                                $arid[]=$val->id_nota;
                                                $surjal[]=$val->no_sj;
                                                $konstruksi[] = $val->konstruksi;
                                                $panjang[] = $val->total_panjang;
                                                $harga[] = $val->harga_satuan;
                                                $harga_total[] = $val->total_harga;
                                                $idnota = $val->id_nota;
                                                $cekbyr = $this->data_model->get_byid('a_nota_bayar2', ['id_nota'=>$idnota]);
                                                foreach($cekbyr->result() as $val2):
                                                    $tglar[]= $val2->tgl_pemb;
                                                    $jenis[]='Pembayaran';
                                                    $arid[]=$val2->id_pemb2;
                                                    $surjal[]="null";
                                                    $konstruksi[] = "null";
                                                    $panjang[] = "null";
                                                    $harga[] = "null";
                                                    $harga_total[] = "null";
                                                endforeach;
                                            endforeach;
                                        }
                                    } //if pB ended
                                    asort($tglar);
                                    $sum_panjang = 0;
                                    $sum_harga =0;
                                    $sum_bayar = 0;
                                    $saldo=0;
                                    foreach($tglar as $index => $value){
                                        
                                        if($jenis[$index]=="Pembelian"){
                                            $dtqr = $this->data_model->get_byid('a_nota', ['id_nota'=>$arid[$index]]);
                                            $dtqr2 = $this->db->query("SELECT * FROM a_nota WHERE id_nota='$arid[$index]' AND no_sj NOT LIKE '%SLD%'");
                                            $nobukti = "<a href='".base_url('nota/piutang/'.sha1($arid[$index]))."' style='color:#135bd6;text-decoration:none;'>Invoice No.".$dtqr->row("id_nota")."</a>";
                                              if(fmod($dtqr->row("total_harga"), 1) !== 0.00){
                                                $debet = "Rp. ".number_format($dtqr->row("total_harga"),2,',','.');
                                              } else {
                                                $debet = "Rp. ".number_format($dtqr->row("total_harga"),0,',','.');
                                              }
                                            $sum_harga = $sum_harga + $dtqr2->row("total_harga");
                                            $kredit = "";
                                            $saldo = $saldo + $dtqr->row("total_harga");
                                            $jns = "Penjualan";
                                        } else {
                                            $dtqr = $this->data_model->get_byid('a_nota_bayar2', ['id_pemb2'=>$arid[$index]]);
                                            $nobukti = $dtqr->row("nomor_bukti");
                                            $debet = "";
                                              if(fmod($dtqr->row("nominal_pemb"), 1) !== 0.00){
                                                $kredit = "Rp. ".number_format($dtqr->row("nominal_pemb"),2,',','.');
                                              } else {
                                                $kredit = "Rp. ".number_format($dtqr->row("nominal_pemb"),0,',','.');
                                              }
                                            $sum_bayar = $sum_bayar + $dtqr->row("nominal_pemb");
                                            $saldo = $saldo - $dtqr->row("nominal_pemb");
                                            $jns = "<a href='".base_url('nota/piutang/'.sha1($dtqr->row('id_nota')))."' style='color:#135bd6;text-decoration:none;'>Pembayaran Invoice No.".$dtqr->row("id_nota")."</a>";
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            <?php $ek=explode('-',$value); echo $ek[2]." ".$bln[$ek[1]]." ".$ek[0]; ?>
                                        </td>
                                        <!-- <td><=$jns;?></td> -->
                                        <td>
                                        <?php
                                            $rtx = explode('/', $surjal[$index]);
                                            $idcusbysj = $this->data_model->get_byid('surat_jalan',['no_sj'=>$surjal[$index]])->row("id_customer");
                                            $nm_cus = $this->data_model->get_byid('dt_konsumen',['id_konsumen'=>$idcusbysj])->row("nama_konsumen");
                                            if($rtx[0]=="SLD"){
                                                echo "<strong>Saldo Awal</strong>";
                                            } elseif($rtx[0]=="null") {
                                                echo "<font style='color:red;'>$nobukti</font>";
                                            } else {
                                                echo "<a href='#' data-toggle='tooltip' data-placement='top' title='$nm_cus'>J".$rtx[3]."".$rtx[0]."</a>";
                                            }
                                        ?>
                                        </td>
                                        <!-- <td><=$nobukti;?></td> -->
                                        <td><?=$konstruksi[$index]=="null" ? '':$konstruksi[$index];?></td>
                                        <td>
                                        <?php if($panjang[$index]=="null" || $panjang[$index]==0){} else {
                                            if(fmod($panjang[$index], 1) !== 0.00){
                                                echo "".number_format($panjang[$index],2,',','.');
                                            } else {
                                                echo "".number_format($panjang[$index],0,',','.');
                                            }
                                        }
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                            $sum_panjang = floatval($sum_panjang) + floatval($panjang[$index]);
                                            if($harga[$index]=="null" || $harga[$index]==0){} else {
                                            if(fmod($harga[$index], 1) !== 0.00){
                                                echo "Rp. ".number_format($harga[$index],2,',','.');
                                            } else {
                                                echo "Rp. ".number_format($harga[$index],0,',','.');
                                            }
                                        }
                                        ?>
                                        </td>
                                        <td>
                                            <?php if($rtx[0]=="SLD"){ echo ""; } else { echo $debet; } ?>
                                        </td>
                                        <td><?=$kredit;?></td>
                                        <td>
                                        <?php
                                            if(fmod($saldo, 1) !== 0.00){
                                                echo "Rp. ".number_format($saldo,2,',','.');
                                            } else {
                                                echo "Rp. ".number_format($saldo,0,',','.');
                                            }
                                        
                                        ?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                    <tr>
                                        <td colspan="3"><strong>TOTAL</strong></td>
                                        <td><?php
                                          if(fmod($sum_panjang, 1) !== 0.00){
                                            echo "".number_format($sum_panjang,2,',','.');
                                          } else {
                                            echo "".number_format($sum_panjang,0,',','.');
                                          } ?>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            <?php
                                              if(fmod($sum_harga, 1) !== 0.00){
                                                echo  "Rp. ".number_format($sum_harga,2,',','.');
                                              } else {
                                                echo "Rp. ".number_format($sum_harga,0,',','.');
                                              }
                                              ?>
                                        </td>
                                        <td>
                                            <?php
                                            if(fmod($sum_bayar, 1) !== 0.00){
                                                echo "Rp. ".number_format($sum_bayar,2,',','.');
                                              } else {
                                                echo "Rp. ".number_format($sum_bayar,0,',','.');
                                              }
                                              ?>
                                        </td>
                                        <td><?php
                                            if(fmod($saldo, 1) !== 0.00){
                                                $__saldopiutang = "Rp. ".number_format($saldo,2,',','.')."";
                                                echo "Rp. ".number_format($saldo,2,',','.');
                                            } else {
                                                $__saldopiutang = "Rp. ".number_format($saldo,0,',','.')."";
                                                echo "Rp. ".number_format($saldo,0,',','.');
                                            }
                                        
                                        ?></td>
                                    </tr>
								</tbody>
							</table>
          <?php
          $cek_data = $this->data_model->get_byid('tes_piutang2', ['id_konsumen'=>$idcus]);
          if($cek_data->num_rows() == 1){
              $this->data_model->updatedata('id_konsumen',$idcus,'tes_piutang2',['nominal_piutang'=>$__saldopiutang,'saldop'=>$saldo]);
          }
          if($cek_data->num_rows() == 0){
              $this->data_model->saved('tes_piutang2', ['id_konsumen'=>$idcus,'nominal_piutang'=>$__saldopiutang,'saldop'=>$saldo,'nmcus'=>$_nmcus]);
          }
      } //end cus
      $this->data_model->saved('tes_piutang', ['last_update'=>'null']);
  } //end cronjob
  function tesdelup(){
      $this->db->truncate('tes_piutang');
  }
  function delkons(){
    $id = $this->input->post('del_kd');
    $this->data_model->delete('tb_konstruksi', 'kode_konstruksi', $id);
    $this->session->set_flashdata('gagal', 'Konstruksi <strong>'.$id.'</strong> telah dihapus dari akses aplikasi');
    redirect(base_url('input-konstruksi'));
  }

  function addkons(){
        $kode = $this->data_model->filter($this->input->post('kode'));
        $cek_kode = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kode])->num_rows();
        $ket = $this->data_model->filter($this->input->post('ket'));
        $chto = $this->data_model->filter($this->input->post('chto'));
        if($cek_kode==0){
            if($kode!='' AND $ket!='' AND $chto!=''){
                $dtlist = [
                  'kode_konstruksi' => $kode,
                  'stok_ig' => 0,
                  'stok_fg' => 0,
                  'stok_if' => 0,
                  'stok_ff' => 0,
                  'stok_bs' => 0,
                  'stok_bp' => 0,
                  'stok_bs2' => 0,
                  'stok_bp2' => 0,
                  'stok_crt' => 0,
                  'ket' => $ket,
                  'chto' => $chto
                ];
                $this->data_model->saved('tb_konstruksi', $dtlist);
                $this->session->set_flashdata('announce', 'Berhasil menambahkan Kode konstruksi ('.$kode.')');
                redirect(base_url('input-konstruksi'));
            } else {
              $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
              redirect(base_url('input-konstruksi'));
            }
        } else {
            $this->session->set_flashdata('gagal', 'Kode konstruksi telah terdaftar');
            redirect(base_url('input-konstruksi'));
        } 
  } //end

  function upkons(){
        $kode = $this->input->post('kode');
        $cek_kode = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kode])->num_rows();
        $ket = $this->data_model->filter($this->input->post('ket'));
        $chto = $this->data_model->filter($this->input->post('chto'));
            if($kode!='' AND $ket!='' AND $chto!=''){
                $dtlist = [ 'ket' => $ket, 'chto' => $chto ];
                $this->data_model->updatedata('kode_konstruksi',$kode,'tb_konstruksi', $dtlist);
                $this->session->set_flashdata('announce', 'Berhasil mengupdate data konstruksi Kode konstruksi ('.$kode.')');
                redirect(base_url('input-konstruksi'));
            } else {
              $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
              redirect(base_url('input-konstruksi'));
            }
        
  } //end

  function adduser(){
      $email = $this->input->post('email');
      $pass = $this->input->post('pass');
      $nm = $this->data_model->filter($this->input->post('nama'));
      $hak = $this->data_model->filter($this->input->post('hak'));
      $dep = $this->data_model->filter($this->input->post('dep'));
      
      if($email!='' AND $pass!='' AND $nm!='' AND $hak!='' AND $dep!=''){
        $dtlist = [
          'username' => $email,
          'password' => sha1($pass),
          'nama_user' => $nm,
          'hak_akses' => $hak,
          'departement' => $dep,
          'yg_nambah' => $this->session->userdata('id')
        ];
        $this->data_model->saved('user', $dtlist);
        $this->session->set_flashdata('announce', 'Berhasil menambahkan user baru');
        redirect(base_url('manage-user'));
    } else {
      $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
      redirect(base_url('manage-user'));
    }
  } //enda

  function updateuser(){
    $email = $this->input->post('email');
    $pass = $this->input->post('pass');
    $oldpass = $this->input->post('oldpass');
    $iduser = $this->input->post('iduser');
    $nm = $this->data_model->filter($this->input->post('nama'));
    $hak = $this->data_model->filter($this->input->post('hak'));
    $dep = $this->data_model->filter($this->input->post('dep'));
    
    if($email!='' AND $nm!='' AND $hak!='' AND $dep!=''){
      $dtlist = [
        'username' => $email,
        'password' => $pass=='' ? $oldpass:sha1($pass),
        'nama_user' => $nm,
        'hak_akses' => $hak,
        'departement' => $dep
      ];
      $this->data_model->updatedata('id_user', $iduser, 'user', $dtlist);
      $this->session->set_flashdata('announce', 'Berhasil mengupdate data user');
      redirect(base_url('manage-user'));
  } else {
    $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
    redirect(base_url('manage-user'));
  }
} //enda

  function deluser(){
      $id = $this->input->post('iduser');
      $this->data_model->delete('user', 'id_user', $id);
      $this->session->set_flashdata('gagal', 'User telah dihapus dari akses aplikasi');
      redirect(base_url('manage-user'));
  
  } //enda

  function prosend(){
      $kd = $this->data_model->filter($this->input->post('kode_produksi'));
      $loc = $this->data_model->filter($this->input->post('lokasi_kirim'));
      $tujuankirim = $this->data_model->filter($this->input->post('tujuankirim'));
      $tglkirim = $this->data_model->filter($this->input->post('tglkirim'));
      //echo "$kd<br>$loc<br>$tujuankirim";
      $tujuankirims = strtolower($tujuankirim);
    if($kd!='' AND $loc!=''){
        $dt_produksi = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kd])->row_array();
        $loc_before = $dt_produksi['lokasi_saat_ini'];
        $kd_kons = $dt_produksi['kode_konstruksi'];
        $st = $dt_produksi['st_produksi'];
        $jml = $dt_produksi['jumlah_produksi_now'];
        $jmly = $dt_produksi['jumlah_produksi_now_yard'];
        //update stok
        $rs = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kd_kons, 'departement'=>$loc_before]);
        $id_rs = $rs->row("id_stok");
        if($st=="IG"){
            $stok_ig = $rs->row("stok_ins");
            $stok_igy = $rs->row("stok_ins_yard");
            $up_stok = $stok_ig - $jml;
            $up_stoky = $stok_igy - $jmly;
            $this->data_model->updatedata('id_stok',$id_rs,'report_stok',['stok_ins'=>round($up_stok,2), 'stok_ins_yard'=>round($up_stoky,2)]);
        } elseif ($st=="IF") {
            $stok_if = $rs->row("stok_ins_finish");
            $stok_ify = $rs->row("stok_ins_finish_yard");
            $up_stok = $stok_if - $jml;
            $up_stoky = $stok_ify - $jmly;
            $this->data_model->updatedata('id_stok',$id_rs,'report_stok',['stok_ins_finish'=>round($up_stok,2), 'stok_ins_finish_yard'=>round($up_stoky,2)]);
        }
        if($loc=="Luar"){
          $listluar = [
              'kode_produksi' => $kd,
              'lokasi_kirim' => $tujuankirims,
              'tgl_kirim' => $tglkirim,
              'jml_kirim' => $jml,
              'tgl_kembali' => 'null',
              'jml_kembali' => 0,
              'iduser' => $this->session->userdata('id')
          ]; 
          $this->data_model->saved('tb_produksi_out',$listluar);
          $this->data_model->updatedata('kode_produksi',$kd,'tb_produksi',['jumlah_produksi_now'=>0,'jumlah_produksi_now_yard'=>0]);
          $rs2 = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kd_kons, 'departement'=>$tujuankirims]);
          $txt = "Telah mengirim barang ke <strong>".$tujuankirims."</strong> dengan kode produksi (<strong>".$kd."</strong>)";
          $this->session->set_flashdata('announce', 'Berhasil mengirim barang ke '.$tujuankirims.'. Silahkan isi packing list barang');
          $locss = $tujuankirims;
        } else {
          $rs2 = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kd_kons, 'departement'=>$loc]);
          $txt = "Telah mengirim barang ke <strong>".$loc."</strong> dengan kode produksi (<strong>".$kd."</strong>)";
          $this->session->set_flashdata('announce', 'Berhasil mengirim barang ke '.$loc.'. Silahkan isi packing list barang');
          $locss = $loc;
        }
        if($rs2->num_rows() == 0){
            $stoklikst = [
              'kode_konstruksi' => $kd_kons,
              'stok_ins' => $st=='IG' ? $jml:'0', 
              'stok_ins_finish' => $st=='IF' ? $jml:'0', 'stok_fol' => 0, 'stok_fol_finish' => 0,
              'terjual' => 0, 'bs' => 0, 'retur' => 0,
              'departement' => $locss
            ];
            $this->data_model->saved('report_stok',$stoklikst);
        } elseif ($rs2->num_rows() == 1) {
            $id_stok = $rs2->row("id_stok");
            if($st=="IG"){
                $stok_ig = $rs2->row("stok_ins");
                $up_stok_ig = $jml + $stok_ig;
                $up_stok_igy = $up_stok_ig / 0.9144;
                $this->data_model->updatedata('id_stok', $id_stok, 'report_stok', ['stok_ins'=>round($up_stok_ig,2), 'stok_ins_yard'=>round($up_stok_igy,2)]);
            } elseif ($st=="IF") {
                $stok_if = $rs2->row("stok_ins_finish");
                $up_stok_if = $jml + $stok_if;
                $up_stok_ify = $up_stok_if / 0.9144;
                $this->data_model->updatedata('id_stok', $id_stok, 'report_stok', ['stok_ins_finish'=>round($up_stok_if,2), 'stok_ins_finish'=>round($up_stok_ify,2)]);
            }
        }
        // perubahan lokasi_saat_ini
        $this->data_model->updatedata('kode_produksi',$kd,'tb_produksi',['lokasi_saat_ini'=>$loc]);
        // update_log
        $dtlog = [
          'id_user' => $this->session->userdata('id'),
          'kode_produksi' => $kd,
          'log' => $txt
        ];
        $dtlog2 = [ 'id_user' => $this->session->userdata('id'), 'log' => $txt];
        $this->data_model->saved('log_produksi',$dtlog);
        $this->data_model->saved('log_program',$dtlog2);
        //create notification for departement
        $notif = "".$dt_produksi['lokasi_produksi']." telah mengirim barang ke ".$loc." dengan nomor packing list (".$kd.")";
        $this->data_model->saved('notifikasi', ['departement'=>$loc, 'notif'=>$notif]);
        
        redirect(base_url('input-produksi'));
    } else {
      $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
      redirect(base_url('input-produksi'));
    }
  } //enda

  

  function proretur(){
      $kd = $this->data_model->filter($this->input->post('kode_produksi'));
      $alasan = $this->data_model->filter($this->input->post('alasan'));
      $lokasi_sebelum = $this->data_model->filter($this->input->post('loc_now'));
      if($alasan!='' AND $kd!=''){
          $loc = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kd])->row('lokasi_produksi');
          $this->data_model->updatedata('kode_produksi',$kd, 'tb_produksi', ['lokasi_saat_ini'=>$loc]);
          $txt = "Telah meretur barang dari <strong>".$lokasi_sebelum."</strong> ke <strong>".$loc."</strong> dengan alasan <em>".$alasan."</em>";
          $dtlog = [
            'id_user' => $this->session->userdata('id'),
            'kode_produksi' => $kd,
            'log' => $txt
          ];
          $dtlog2 = [ 'id_user' => $this->session->userdata('id'), 'log' => $txt];
          $this->data_model->saved('log_produksi',$dtlog);
          $this->data_model->saved('log_program',$dtlog2);
          $this->data_model->saved('notifikasi', ['departement'=>$loc, 'notif'=>$txt]);
          $this->session->set_flashdata('announce', 'Proses retur ke <strong>'.$loc.'</strong> berhasil');
          redirect(base_url('input-produksi'));
      } else {
          $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
          redirect(base_url('input-produksi'));
      }
  } //end

  function produksimc(){
      $tgl = $this->input->post('dtproduksi');
      $kode = $this->data_model->filter($this->input->post('kode'));
      $loc = $this->data_model->filter($this->input->post('loc'));
      $jmlmc = $this->data_model->filter($this->input->post('jmlmesin'));
      $promc = $this->data_model->filter($this->input->post('jmlproduksi')); $promc_float = floatval($promc); $promc_round = round($promc_float,2);
      $id = $this->session->userdata('id');
      if($tgl!="" AND $kode!="" AND $loc!="" AND $jmlmc!="" AND $promc!=""){
          $dtlist = [
            'kode_konstruksi' => $kode,
            'jumlah_mesin' => $jmlmc,
            'hasil' => $promc_round,
            'tanggal_produksi' => $tgl,
            'lokasi' => $loc,
            'yg_nambah' => $id
          ];
          $this->data_model->saved('dt_produksi_mesin', $dtlist);
          $this->session->set_flashdata('announce', 'Berhasil menyimpan data produksi mesin');
          redirect(base_url('produksi-mesin'));
      } else {
          $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
          redirect(base_url('produksi-mesin'));
      }
  } //end produksi mesin

  function del_dtmc(){
      $id = $this->input->post('idkoddinn');
      $this->data_model->delete('dt_produksi_mesin', 'id_produksi_mc', $id);
      $this->session->set_flashdata('gagal', 'Data telah berhasil di hapus');
      redirect(base_url('produksi-mesin'));
  } //edn

  function up_produksimc(){
      $id = $this->data_model->filter($this->input->post('id_mesin'));
      $kode = $this->data_model->filter($this->input->post('kdskons'));
      $jmc = $this->data_model->filter($this->input->post('jmlmesin'));
      $hasil = $this->data_model->filter($this->input->post('jmlproduksi'));
      if($id!="" AND $kode!="" AND $jmc!="" AND $hasil!=""){
          $dtlist = [ 'jumlah_mesin' => $jmc, 'hasil' => $hasil ];
          $this->data_model->updatedata('id_produksi_mc',$id,'dt_produksi_mesin',$dtlist);
          $this->session->set_flashdata('announce', 'Berhasil menyimpan perubahan data produksi mesin');
          redirect(base_url('produksi-mesin'));
      } else {
          $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
          redirect(base_url('produksi-mesin'));
      }
  } //end

  function addkonsumen(){
    $nm = $this->data_model->filter($this->input->post('konsum'));
    $no = $this->data_model->filter($this->input->post('notelp'));
    $al = $this->data_model->filter($this->input->post('almat'));
    $ceknama = $this->data_model->get_byid('dt_konsumen', ['nama_konsumen'=>$nm]);
    if($ceknama->num_rows() == 0){
        if($nm!="" AND $no!="" AND $al!=""){
            $dtlist = [
              'nama_konsumen' => $nm,
              'no_hp' => $no,
              'alamat' => $al
            ];
            $this->data_model->saved('dt_konsumen', $dtlist);
            $this->session->set_flashdata('announce', 'Berhasil menyimpan data konsumen atas nama <strong>'.$nm.'</strong>');
            redirect(base_url('data-konsumen'));
        } else {
            $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
            redirect(base_url('data-konsumen'));
        }
    } else {
        $this->session->set_flashdata('gagal', 'Nama Konsumen Sudah Terdaftar');
        redirect(base_url('data-konsumen'));
    }
    
  } //end

  function del_kons(){
    $id = $this->input->post('idkons');
    $nm = $this->input->post('nmkons');
    $this->data_model->delete('dt_konsumen', 'id_konsumen', $id);
    $this->session->set_flashdata('gagal', '<strong>'.$nm.'</strong> telah berhasil di hapus dari data konsumen');
    redirect(base_url('data-konsumen'));
  } //end

  function up_konsum(){
      $nm = $this->data_model->filter($this->input->post('konsum'));
      $no = $this->data_model->filter($this->input->post('notelp'));
      $al = $this->data_model->filter($this->input->post('almat'));
      $id = $this->data_model->filter($this->input->post('idkons'));

      $ceknama = $this->data_model->get_byid('dt_konsumen', ['nama_konsumen'=>$nm]);
      //if($ceknama->num_rows()==1){
          if($nm!="" AND $no!="" AND $al!=""){
              $dtlist = [
                'nama_konsumen' => $nm,
                'no_hp' => $no,
                'alamat' => $al
              ];
              $this->data_model->updatedata('id_konsumen',$id,'dt_konsumen', $dtlist);
              $this->session->set_flashdata('announce', 'Berhasil menyimpan perubahan data konsumen atas nama <strong>'.$nm.'</strong>');
              redirect(base_url('data-konsumen'));
          } else {
              $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
              redirect(base_url('data-konsumen'));
          }
      // } else {
      //     $this->session->set_flashdata('gagal', 'Tidak dapat menyimpan perubahan. Terdapat nama konsumen yang sama.');
      //     //redirect(base_url('data-konsumen'));
      // }
      
  } //end

  function sell(){
      $tgl = $this->data_model->filter($this->input->post('tgl'));
      $kd = $this->data_model->filter($this->input->post('kode'));
      $nama = $this->data_model->filter($this->input->post('konsumen'));
      $jumlah = $this->data_model->filter($this->input->post('jumlah'));
      $cek_konstruksi = $this->data_model->get_byid('tb_konstruksi', ['kode_konstruksi'=>$kd]);
      if($cek_konstruksi->num_rows()==1){
          $cek_pkg = $this->data_model->get_byid('pkg', ['kode_konstruksi'=>$kd]); 
          if($cek_pkg->num_rows()>0){
              $total_stok = 0;
              foreach($cek_pkg->result() as $val):
                  $nopkg = $val->no_pkg;
                  $jml_Stok = $this->data_model->get_byid('new_tb_pkg_fol', ['id_effected_row'=>$nopkg]);
                  foreach($jml_Stok->result() as $val2):
                      $one_stok = $val2->ukuran_now_yard;
                      $total_stok = $total_stok + $one_stok;
                  endforeach;
              endforeach;
              if($jumlah > $total_stok){
                $this->session->set_flashdata('gagal', 'Kode konstruksi <strong>'.$kd.'</strong>. Jumlah penjualan melebihi stok yang ada.');
                redirect(base_url('input-penjualan'));
              } else {
                $cek_nama = $this->data_model->get_byid('dt_konsumen', ['nama_konsumen'=>$nama]);
                if($cek_nama->num_rows() == 1){
                    $id_kons = $cek_nama->row("id_konsumen");
                    $this->session->set_flashdata('tanggal', $tgl);
                    $this->session->set_flashdata('kode_konstruksi', $kd);
                    $this->session->set_flashdata('konsumen', $nama);
                    $this->session->set_flashdata('jumlah', $jumlah);
                    $this->session->set_flashdata('id_konsumen', $id_kons);
                    $this->session->set_flashdata('notlis', 'false');
                    redirect(base_url('input-penjualan-list'));
                } else {
                    $this->session->set_flashdata('tanggal', $tgl);
                    $this->session->set_flashdata('kode_konstruksi', $kd);
                    $this->session->set_flashdata('konsumen', $nama);
                    $this->session->set_flashdata('jumlah', $jumlah);
                    $this->session->set_flashdata('id_konsumen', 'kosong');
                    $this->session->set_flashdata('notlis', 'false');
                    redirect(base_url('input-penjualan-list'));
                }
                //echo "stok mencukupi ".$total_stok;
              }
          } else {
              $this->session->set_flashdata('warning', 'Kode konstruksi <strong>'.$kd.'</strong> tidak memiliki stok yang mencukupi. Anda bisa mengisi penjualan barang diluar packing list.');
              //redirect(base_url('input-penjualan'));
              $cek_nama = $this->data_model->get_byid('dt_konsumen', ['nama_konsumen'=>$nama]);
                if($cek_nama->num_rows() == 1){
                    $id_kons = $cek_nama->row("id_konsumen");
                    $this->session->set_flashdata('tanggal', $tgl);
                    $this->session->set_flashdata('kode_konstruksi', $kd);
                    $this->session->set_flashdata('konsumen', $nama);
                    $this->session->set_flashdata('jumlah', $jumlah);
                    $this->session->set_flashdata('id_konsumen', $id_kons);
                    $this->session->set_flashdata('notlis', 'true');
                    redirect(base_url('input-penjualan-list'));
                } else {
                    $this->session->set_flashdata('tanggal', $tgl);
                    $this->session->set_flashdata('kode_konstruksi', $kd);
                    $this->session->set_flashdata('konsumen', $nama);
                    $this->session->set_flashdata('jumlah', $jumlah);
                    $this->session->set_flashdata('id_konsumen', 'kosong');
                    $this->session->set_flashdata('notlis', 'true');
                    redirect(base_url('input-penjualan-list'));
                }
          }
      } else {
          $this->session->set_flashdata('gagal', 'Kode konstruksi tidak ditemukan');
          redirect(base_url('input-penjualan'));
      }
  } //end

  function sellwithlist(){
      $user = $this->session->userdata('id');
      $dep_user = $this->session->userdata('departement');
      $id = $this->data_model->filter($this->input->post('tb_idkonsum'));
      $tgl = $this->data_model->filter($this->input->post('tb_tgl'));
      $kd_konstruksi = $this->data_model->filter($this->input->post('tb_kode'));
      $nm_konsum = $this->data_model->filter($this->input->post('tb_konsum'));
      $nohp = $this->data_model->filter($this->input->post('tb_nohp'));
      $almt = $this->data_model->filter($this->input->post('tb_almt'));
      $jumlah = $this->data_model->filter($this->input->post('tb_jumlah'));
      $jumlah_list = $this->data_model->filter($this->input->post('pilihanNilai'));
      $jumlah_sl = $this->data_model->filter($this->input->post('pilihanSL'));
      $dtlist = $this->input->post('slect');
      $noroll = $this->input->post('noroll');
      $ukuran = $this->input->post('ukuran');
    if($jumlah_list==0 AND $jumlah_sl==0){
          $this->session->set_flashdata('gagal', 'Anda tidak memilih dari packing list ataupun mengisi penjualan stok lama.');
          redirect(base_url('input-penjualan'));
    } else {
    //   echo "<pre>";
    //   print_r($dtlist);
    //   echo "</pre>";
      if($id!="" AND $tgl!="" AND $kd_konstruksi!="" AND $nm_konsum!="" AND $jumlah!=""){
          if(empty($dtlist) OR $dtlist==""){ $con=0; } else {
          $con = count($dtlist); }
          if($con>0){
              //cek dulu jumlahnya lah
              $jumlah_dipilih = 0; $satuan = "null"; $kd_produksi = "null";
              for($i=0; $i<$con; $i++){ 
                $idfol = $dtlist[$i];
                $cek_pilih = $this->data_model->get_byid('new_tb_pkg_fol',['id_fol'=>$idfol]);
                if($cek_pilih->row("st_folding") == "Finish"){
                    $jumlah_fromdb = $cek_pilih->row("ukuran_now_yard");
                    $jumlah_dipilih = $jumlah_dipilih + $jumlah_fromdb;
                    $satuan = "Yard";
                } else {
                    $jumlah_fromdb = $cek_pilih->row("ukuran_now");
                    $jumlah_dipilih = $jumlah_dipilih + $jumlah_fromdb;
                    $satuan = "Meter";
                }
                $kd_produksi = $cek_pilih->row("kode_produksi");
              }
              $jumlah_jual_list = 0;
              if($jumlah_dipilih==$jumlah_list){
                $jumlah_jual_list = round($jumlah_dipilih,2);
              } else {
                $jumlah_jual_list = round($jumlah_list,2);
              }
              $jumlah_jual_sl = round($jumlah_sl,2);
              $jumlah_jual_sl_asli = $jumlah_jual_sl - $jumlah_jual_list;
              //if($jumlah_dipilih == $jumlah){ // jika jumlah penjualan sama dengan jumlah yang di select
              //input penjualan dan konsumen dulu
              if($id=="kosong"){
                //input konsumen baru dulu
                  $list_konsumen = [
                    'nama_konsumen' => $nm_konsum,
                    'no_hp' => $nohp,
                    'alamat' => $almt
                  ];
                  $this->data_model->saved('dt_konsumen', $list_konsumen);
                  //cek id_konsumen baru
                  $cek_id_kons = $this->data_model->get_byid('dt_konsumen', $list_konsumen)->row("id_konsumen");
                  // baru input penjualan kemudian
                  $list_penjualan = [
                      'kode_konstruksi' => $kd_konstruksi,
                      'tgl' => $tgl,
                      'jml_jual_list' => $jumlah_jual_list,
                      'jml_jual_sl' => round($jumlah_jual_sl_asli,2),
                      'satuan' => $satuan,
                      'id_user' => $user,
                      'id_konsumen' => $cek_id_kons,
                      'penjualan_list' => implode('-',$dtlist),
                      'departement' => $dep_user,
                      'type_penjualan' => $jumlah_jual_sl=='0' ? 'FromList':'Both'
                  ];
                  $this->data_model->saved('tb_penjualan', $list_penjualan);
              } else {
                 //input penjualan langsung
                 $list_penjualan = [
                    'kode_konstruksi' => $kd_konstruksi,
                    'tgl' => $tgl,
                    'jml_jual_list' => $jumlah_jual_list,
                    'jml_jual_sl' => round($jumlah_jual_sl_asli,2),
                    'satuan' => $satuan,
                    'id_user' => $user,
                    'id_konsumen' => $id,
                    'penjualan_list' => implode('-',$dtlist),
                    'departement' => $dep_user,
                    'type_penjualan' => $jumlah_jual_sl=='0' ? 'FromList':'Both'
                 ];
                 $this->data_model->saved('tb_penjualan', $list_penjualan);
              }
              //end input penjualan dan konsumen
              //cari id penjualan dulu
              $get_id_penjualan = $this->db->query("SELECT * FROM tb_penjualan WHERE kode_konstruksi='$kd_konstruksi' AND tgl='$tgl' AND id_user='$user' ORDER BY id_penjualan DESC LIMIT 1")->row("id_penjualan");
              $old_im = $this->data_model->get_byid('tb_penjualan',['id_penjualan'=>$get_id_penjualan])->row("penjualan_list");
              //input stok lama jika ada
              if($jumlah_jual_sl>0){
                  $id_new = array(); $jumlah_jual_sl2 = 0;
                  for($n=0; $n<count($noroll); $n++){
                    if($noroll[$n]!="" AND $ukuran[$n]!=""){
                      $jumlah_jual_sl2 = $jumlah_jual_sl2 + floatval($ukuran[$n]);
                      if($satuan=="Yard"){
                          $in_yard = floatval($ukuran[$n]);
                          $in_mtr = $in_yard * 0.9144;
                          $st = "Finish";
                      } else {
                          $in_mtr = floatval($ukuran[$n]);
                          $in_yard = $in_mtr / 0.9144;
                          $st = "Grey";
                      }
                       $pkgfol = [
                          'kode_produksi' => $kd_produksi,
                          'asal' => 'null',
                          'id_asal' => 0,
                          'no_roll' => $noroll[$n],
                          'tgl' => $tgl,
                          'ukuran' => round($in_mtr,2),
                          'operator' => 'SL',
                          'st_folding' => $st,
                          'ukuran_now' => 0,
                          'ukuran_yard' => round($in_yard,2),
                          'ukuran_now_yard' => 0,
                          'id_effected_row' => 0,
                       ];
                       $this->data_model->saved('new_tb_pkg_fol', $pkgfol);
                       $cek_newid = $this->db->query("SELECT id_fol FROM new_tb_pkg_fol ORDER BY id_fol DESC LIMIT 1")->row("id_fol");
                       $id_new[]=$cek_newid;
                       $cek_stok_lama = $this->data_model->get_byid('report_stok_lama', ['kode_konstruksi'=>$kd_konstruksi, 'departement'=>$dep_user]);
                       if($cek_stok_lama->num_rows() == 1){
                            $id_sl = $cek_stok_lama->row("id_sl");
                            $terjual = $cek_stok_lama->row("terjual");
                            $terjual_yard = $cek_stok_lama->row("terjual_yard");
                            $in_terjual = round($in_mtr,2) + $terjual;
                            $in_terjual_yard = round($in_yard,2) + $terjual_yard;
                            $this->data_model->updatedata('id_sl', $id_sl, 'report_stok_lama', ['terjual'=>round($in_terjual,2), 'terjual_yard'=>round($in_terjual_yard,2)]);
                       } else {
                            $stok_sllist = [
                              'kode_konstruksi' => $kd_konstruksi,
                              'ins_finish' => 0,
                              'fol_grey' => 0,
                              'fol_finish' => 0,
                              'terjual' => round($in_mtr,2),
                              'ins_finish_yard' => 0,
                              'fol_grey_yard' => 0,
                              'fol_finish_yard' => 0,
                              'terjual_yard' => round($in_yard,2),
                              'departement' => $dep_user
                            ];
                            $this->data_model->saved('report_stok_lama',$stok_sllist);
                       }
                    }
                  }
                  if(count($id_new)>0){
                  $new_im = implode('-',$id_new);
                  $old_new_im = "".$old_im."-".$new_im."";
                  $this->data_model->updatedata('id_penjualan', $get_id_penjualan, 'tb_penjualan', ['jml_jual_sl' => round($jumlah_jual_sl2,2), 'penjualan_list'=>$old_new_im]); }
              }
              
              $jumlah_dipilih = 0;
              for($i=0; $i<$con; $i++){ 
                  $idfol = $dtlist[$i];
                  $cek_pilih = $this->data_model->get_byid('new_tb_pkg_fol',['id_fol'=>$idfol]);
                  $cek_report_stok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kd_konstruksi, 'departement'=>$dep_user]);
                  $id_stok = $cek_report_stok->row("id_stok");
                  $cek_produksi = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kd_konstruksi, 'lokasi_produksi'=>$dep_user, 'waktu'=>$tgl]);
                  $id_produksi = $cek_produksi->row("id_rptd");
                  if($cek_pilih->row("st_folding") == "Finish"){
                      $jumlah_fromdb = $cek_pilih->row("ukuran_now_yard");
                      $jumlah_dipilih = $jumlah_dipilih + $jumlah_fromdb;
                      //laporan stok total
                      $jumlah_terjual = $cek_report_stok->row("terjual_yard");
                      $jumlah_terjual_baru = $jumlah_terjual + $jumlah_fromdb;
                      $jumlah_terjual_baru_meter = floatval($jumlah_terjual_baru) * 0.9144;
                      $jumlah_folding = $cek_report_stok->row("stok_fol_finish_yard");
                      $up_jumlah_folding = $jumlah_folding - $jumlah_fromdb;
                      $up_jumlah_folding_meter = $up_jumlah_folding * 0.9144;
                      $this->data_model->updatedata('id_stok',$id_stok,'report_stok',['stok_fol_finish'=>round($up_jumlah_folding_meter,2), 'terjual'=>round($jumlah_terjual_baru_meter,2),'stok_fol_finish_yard'=>round($up_jumlah_folding,2), 'terjual_yard'=>round($jumlah_terjual_baru,2)]);
                      //laporan produksi harian 
                      $jumlah_terjual = $cek_produksi->row("terjual_yard");
                      $jumlah_terjual_baru = $jumlah_terjual + $jumlah_fromdb;
                      $jumlah_terjual_baru_meter = floatval($jumlah_terjual_baru) * 0.9144;
                      $this->data_model->updatedata('id_rptd',$id_produksi,'report_produksi_harian',['terjual'=>round($jumlah_terjual_baru_meter,2), 'terjual_yard'=>round($jumlah_terjual_baru,2)]);
                  } else {
                      $jumlah_fromdb = $cek_pilih->row("ukuran_now");
                      $jumlah_dipilih = $jumlah_dipilih + $jumlah_fromdb;
                      //laporan stok total
                      $jumlah_terjual = $cek_report_stok->row("terjual");
                      $jumlah_terjual_baru = $jumlah_terjual + $jumlah_fromdb;
                      $jumlah_terjual_baru_yard = floatval($jumlah_terjual_baru) / 0.9144;
                      $jumlah_folding = $cek_report_stok->row("stok_fol_finish");
                      $up_jumlah_folding = $jumlah_folding - $jumlah_fromdb;
                      $up_jumlah_folding_yard = $up_jumlah_folding / 0.9144;
                      $this->data_model->updatedata('id_stok',$id_stok,'report_stok',['stok_fol_finish'=>round($up_jumlah_folding,2), 'terjual'=>round($jumlah_terjual_baru,2), 'stok_fol_finish_yard'=>round($up_jumlah_folding_yard,2), 'terjual_yard'=>round($jumlah_terjual_baru_yard,2)]);
                      //laporan produksi harian 
                      $jumlah_terjual = $cek_produksi->row("terjual_yard");
                      $jumlah_terjual_baru = $jumlah_terjual + $jumlah_fromdb;
                      $jumlah_terjual_baru_yard = floatval($jumlah_terjual_baru) / 0.9144;
                      $this->data_model->updatedata('id_rptd',$id_produksi,'report_produksi_harian',['terjual'=>round($jumlah_terjual_baru,2), 'terjual_yard'=>round($jumlah_terjual_baru_yard,2)]);
                  }
                  //hapus stok di table folding
                  $this->data_model->updatedata('id_fol', $idfol, 'new_tb_pkg_fol', ['ukuran_now'=>'0', 'ukuran_now_yard'=>'0']);
                 
              }
              $this->session->set_flashdata('announce', 'Input data penjualan sukses');
              redirect(base_url('input-penjualan'));
            //   } else { //jika jumlah penjualan tidak sama dengan jumlah yang di select
            //       $this->session->set_flashdata('gagal', 'Total jumlah roll yang anda masukan tidak sama dengan penjualan.');
            //       redirect(base_url('input-penjualan'));
            //   }
          } else {
              //$this->session->set_flashdata('gagal', 'Input penjualan gagal. Anda tidak menambahkan list penjualan.');
              //redirect(base_url('input-penjualan'));
              //INPUT PENJUALAN BARANG DI LUAR PACKING LIST
              $satuan_list = $this->input->post('satuan');
              if($satuan_list==""){
                $this->session->set_flashdata('gagal', 'Anda tidak memiilih satuan ukuran barang');
                redirect(base_url('input-penjualan'));
              } else {
                $jumlah_jual_sl=0; $listArray = array();
                for ($i=0; $i <count($noroll) ; $i++) { 
                    if($noroll[$i]!="" AND $ukuran[$i]!=""){
                        $ukuran_fl = floatval($ukuran[$i]);
                        $ukuran_rnd = round($ukuran_fl,2);
                        $jumlah_jual_sl = $jumlah_jual_sl + $ukuran_rnd;
                        $lsar = "".$noroll[$i]."-".$ukuran_rnd."";
                        $listArray[]=$lsar;
                    }
                }
                $im_listArray = implode(',', $listArray);
                if($satuan_list=="Yard"){
                    $total_yard = round($jumlah_jual_sl,2);
                    $total_mtr = $total_yard * 0.9144;
                    $total_mtr = round($total_mtr,2);
                } else {
                    $total_mtr = round($jumlah_jual_sl,2);
                    $total_yard = $total_mtr / 0.9144;
                    $total_yard = round($total_yard,2);
                }
                if($jumlah_jual_sl>0){
                    $list_penjualan = [
                        'kode_konstruksi' => $kd_konstruksi,
                        'tgl' => $tgl,
                        'jml_jual_list' => 0,
                        'jml_jual_sl' => round($jumlah_jual_sl,2),
                        'satuan' => $satuan_list,
                        'id_user' => $user,
                        'id_konsumen' => $id=='kosong'?'0':$id,
                        'penjualan_list' => $im_listArray,
                        'departement' => $dep_user,
                        'type_penjualan' => 'StokLama'
                    ];
                    $this->data_model->saved('tb_penjualan', $list_penjualan);
                    $id_last_penjualan = $this->db->query("SELECT id_penjualan,type_penjualan FROM tb_penjualan WHERE type_penjualan='StokLama' ORDER BY id_penjualan DESC LIMIT 1")->row("id_penjualan");
                    if($id=="kosong"){
                        $list_konsumen = [
                          'nama_konsumen' => $nm_konsum,
                          'no_hp' => $nohp,
                          'alamat' => $almt
                        ];
                        $this->data_model->saved('dt_konsumen', $list_konsumen);
                        //cek id_konsumen baru
                        $cek_id_kons = $this->data_model->get_byid('dt_konsumen', $list_konsumen)->row("id_konsumen");
                        $this->data_model->updatedata('id_penjualan',$id_last_penjualan, 'tb_penjualan', ['id_konsumen'=>$cek_id_kons]);
                    } 
                    $cek_stok_lama = $this->data_model->get_byid('report_stok_lama', ['kode_konstruksi'=>$kd_konstruksi, 'departement'=>$dep_user]);
                    if($cek_stok_lama->num_rows()==1){
                        $idsl = $cek_stok_lama->row("id_sl");
                        $jml_yrd = $cek_stok_lama->row("terjual_yard");
                        $jml_mtr = $cek_stok_lama->row("terjual");
                        $up_yrd = $jml_yrd + $total_yard;
                        $up_mtr = $jml_mtr + $total_mtr;
                        $this->data_model->updatedata('id_sl',$idsl,'report_stok_lama',['terjual'=>round($up_mtr,2), 'terjual_yard'=>round($up_yrd,2)]);
                    } else {
                      $stok_sllist = [
                        'kode_konstruksi' => $kd_konstruksi,
                        'ins_finish' => 0,
                        'fol_grey' => 0,
                        'fol_finish' => 0,
                        'terjual' => $total_mtr,
                        'ins_finish_yard' => 0,
                        'fol_grey_yard' => 0,
                        'fol_finish_yard' => 0,
                        'terjual_yard' => $total_yard,
                        'departement' => $dep_user
                      ];
                      $this->data_model->saved('report_stok_lama',$stok_sllist);
                    }
                } else {
                    $this->session->set_flashdata('gagal', 'Input penjualan gagal. Data tidak di masukan dengan benar.');
                    redirect(base_url('input-penjualan'));
                }
                $this->session->set_flashdata('announce', 'Input data penjualan STOK LAMA sukses');
                redirect(base_url('input-penjualan'));
              }
          }
      } else {
          $this->session->set_flashdata('gagal', 'Input penjualan gagal. Data tidak di masukan dengan benar.');
          redirect(base_url('input-penjualan'));
      }
    }
  } //end

  function getValue(){
      $id = 4;
      $val = $this->input->post('id');
      $val2 = $this->uri->segment(3);
      $ex = explode('?', $val2);
      $idasli = $ex[0];
      //echo $idasli;
      $nilai=0;
      $cek = $this->db->query("SELECT * FROM new_tb_pkg_fol WHERE id_fol='".$idasli."'");
      if($cek->num_rows()==1){
         $st_folding = $cek->row("st_folding");
         if($st_folding=="Grey"){
            $nilai = $cek->row("ukuran_now");
         } else {
            $nilai = $cek->row("ukuran_now_yard");
         }
         echo json_encode(array("statusCode"=>200, "psn"=>$nilai));
      } else {
         echo json_encode(array("statusCode"=>300, "psn"=>"failed"));
      }
  } //end

  function returdep(){
      $id = $this->data_model->filter($this->input->post('idasli'));
      $kd = $this->data_model->filter($this->input->post('idprod'));
      $tgl = $this->data_model->filter($this->input->post('tgl'));
      $dep = $this->session->userdata('departement');
      if($id!="" AND $kd!="" AND $tgl!=""){
          $getid = $this->data_model->get_byid('tb_produksi_out',['idpout'=>$id]);
          $aprod = $this->data_model->get_byid('tb_produksi',['kode_produksi'=>$kd]);
          $jml_now = $aprod->row("jumlah_produksi_now");
          
          $kd_kons = $aprod->row("kode_konstruksi");
          if($getid->num_rows()==1){
            $val = $getid->row_array();
            $jumlah = $val['jml_kirim'];
            $lokasi = $val['lokasi_kirim'];
            $dtlist = [
              'tgl_kembali' => $tgl,
              'jml_kembali' => $jumlah,
              'st_back' => 'Retur'
            ];
            $this->data_model->updatedata('idpout',$id,'tb_produksi_out',$dtlist);
            $up_jml_now = $jml_now + $jumlah;
            $up_jml_nowy = $up_jml_now / 0.9144;
            $this->data_model->updatedata('kode_produksi',$kd,'tb_produksi',['jumlah_produksi_now'=>round($up_jml_now,2), 'jumlah_produksi_now_yard'=>round($up_jml_nowy,2), 'lokasi_saat_ini'=>$dep]);
            //kurangi yang di stok_report 
            $getsreport = $this->data_model->get_byid('report_stok',['kode_konstruksi'=>$kd_kons, 'departement'=>$lokasi]);
            $idstok = $getsreport->row("id_stok");
            $jml_stok = $getsreport->row("stok_ins");
            $up_jmlstok = $jml_stok - $jumlah;
            $this->data_model->updatedata('id_stok',$idstok,'report_stok',['stok_ins'=>round($up_jmlstok,2)]);
            $getreport_before = $this->data_model->get_byid('report_stok',['kode_konstruksi'=>$kd_kons, 'departement'=>$dep]);
            $id_stok = $getreport_before->row("id_stok");
            $jml_stok = $getreport_before->row("stok_ins");
            $up_jmlstok = $jml_stok + $jumlah;
            $up_jmlstoky = $up_jmlstok / 0.9144;
            $this->data_model->updatedata('id_stok',$id_stok,'report_stok',['stok_ins'=>round($up_jmlstok,2), 'stok_ins_yard'=>round($up_jmlstoky,2)]);
            $this->session->set_flashdata('announce', 'Proses retur ke <strong>'.$dep.'</strong> berhasil');
            redirect(base_url('pengiriman'));
          } else {
            $this->session->set_flashdata('gagal', 'ID Tidak ditemukan');
            redirect(base_url('pengiriman'));
          }
      } else {
          $this->session->set_flashdata('gagal', 'Anda tidak memasukan data dengan benar.');
          redirect(base_url('pengiriman'));
      }
  } //end
  function tambahkanroll2(){
      $token = $this->input->post('token');
      $kode = $this->input->post('kode');
      $jml = $this->input->post('jmlroll');
      $pjg = $this->input->post('panjangroll');
      foreach($kode as $kd){
          $this->data_model->updatedata('kode_roll',$kd,'data_ig',['loc_now'=>$token]);
      }
      $roll_sekarang = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$token])->row("jumlah_roll");
      $pnjg_sekarang = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$token])->row("ttl_panjang");
      $new_roll = $jml + $roll_sekarang;
      $new_pnjg = $pjg + $pnjg_sekarang;
      $this->data_model->updatedata('kd',$token,'new_tb_packinglist',['jumlah_roll'=>$new_roll, 'ttl_panjang'=>$new_pnjg]);
      redirect(base_url('packing-list'));
  }
  
  function addroll2(){
      $kdlist = $this->input->post('kdlistId');
      $txt = $this->input->post('txt');
      //$ex = explode("-", $txt);
      $data = array(
          'title' => 'Packing list ('.$kdlist.')',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'sess_hak' => $this->session->userdata('hak'),
          'sess_hak' => $this->session->userdata('hak'),
          'sess_dep' =>$this->session->userdata('departement'),
          'token' => $kdlist,
          'exdata' => $txt
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('new_page/edit_pkg_list4', $data);
      $this->load->view('part/main_js_dttable');
  } //end add roll 2

  function addroll(){
     $kdlist = $this->input->post('kdlistId');
     $txt = $this->input->post('txt');
     $siap_jual = $this->input->post('siap_dol');
     $id_user = $this->session->userdata('id');
     $loc = $this->session->userdata('departement');
     if($kdlist!="" AND $txt!=""){
        $ex = explode("-", $txt);
        if(count($ex)==2){
          //echo "bener pake dash";
          $kode_roll1 = $ex[0];
          $kode_roll2 = $ex[1];
          
          if($loc=="RJS"){
            //start range
            $data_input = 0; $data_gagal=0;
            $nex = explode('R', $kode_roll1);
            $net = explode('R', $kode_roll2);
            if(count($nex)==2 AND count($net)==2){
            //echo "nomor 1 ".$nex[1];
            //echo "---nomor 2 ".$net[1];
            $jumlah_data = intval($net[1]) - intval($nex[1]);
            $start_awal = $nex[1];
            $start_akhir = $net[1] + 1;
            //echo "---range data".$jumlah_data;
              for ($i=$start_awal; $i <$start_akhir ; $i++) { 
                //echo "R".$i."<br>";
                $kodeRoll = "R".$i."";
                if($siap_jual=='y'){
                    $cek_kode = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kodeRoll]);
                    $in_paket = $cek_kode->row('posisi');
                    $ukuran = $cek_kode->row('ukuran');
                } else {
                    $cek_kode = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kodeRoll]);
                    $in_paket = $cek_kode->row('loc_now');
                    $ukuran = $cek_kode->row('ukuran_ori');
                }
                if($cek_kode->num_rows()==1){
                  if($in_paket=="Samatex" OR $in_paket=="Pusatex" OR $in_paket=="RJS"){
                    //$this->data_model->saved('new_tb_isi_paket', ['id_kdlist'=>$kdlist, 'kode_roll'=>$kodeRoll, 'siap_jual'=>$siap_jual]);
                    if($siap_jual=='y'){
                        $this->data_model->updatedata('kode_roll',$kodeRoll,'data_fol',['posisi'=>$kdlist]);
                    }else{
                        $this->data_model->updatedata('kode_roll',$kodeRoll,'data_ig',['loc_now'=>$kdlist]);
                    }
                    $cek_roll = $this->data_model->get_byid('new_tb_packinglist',['kd'=>$kdlist])->row("jumlah_roll");
                    $cek_ukuran = $this->data_model->get_byid('new_tb_packinglist',['kd'=>$kdlist])->row("ttl_panjang");
                    $new_ukuran = floatval($cek_ukuran) + $ukuran;
                    $new_roll = intval($cek_roll) + 1;
                    $this->data_model->updatedata('kd',$kdlist,'new_tb_packinglist',['jumlah_roll'=>$new_roll,'ttl_panjang'=>round($new_ukuran,2)]);
                    $data_input+=1;
                  } else {
                    $log = "Kode Roll (".$kodeRoll.") sudah berada di packinglist";
                    $this->data_model->saved('log_program', ['id_user'=>$id_user, 'log'=>$log]);
                    $data_gagal+=1;
                  }
                } else {
                  $log = "Kode Roll (".$kodeRoll.") tidak ditemukan";
                  $this->data_model->saved('log_program', ['id_user'=>$id_user, 'log'=>$log]);
                  $data_gagal+=1;
                }
              }
              $this->session->set_flashdata('announce', 'Berhasil menambahkan '.$data_input.' Roll ke packinglist (gagal menyimpan '.$data_gagal.' Roll)');
              redirect(base_url('data/kode/'.$kdlist));
            } else {
              echo "Erorr departement 1";
            }
          } elseif ($loc=="Samatex") {
            $data_input = 0; $data_gagal=0;
            $nex = explode('S', $kode_roll1);
            $net = explode('S', $kode_roll2);
            if(count($nex)==2 AND count($net)==2){
            //echo "nomor 1 ".$nex[1];
            //echo "---nomor 2 ".$net[1];
            $jumlah_data = intval($net[1]) - intval($nex[1]);
            $start_awal = $nex[1];
            $start_akhir = $net[1] + 1;
            //echo "---range data".$jumlah_data;
              for ($i=$start_awal; $i <$start_akhir ; $i++) { 
                //echo "S".$i."<br>";
                $kodeRoll = "S".$i."";
                if($siap_jual=='y'){
                    $cek_kode = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kodeRoll]);
                    $in_paket = $cek_kode->row('posisi');
                    $ukuran = $cek_kode->row('ukuran');
                } else {
                    $cek_kode = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kodeRoll]);
                    $in_paket = $cek_kode->row('loc_now');
                    $ukuran = $cek_kode->row('ukuran_ori');
                }
                if($cek_kode->num_rows()==1){
                  if($in_paket=="Samatex" OR $in_paket=="Pusatex" OR $in_paket=="RJS"){
                    //$this->data_model->saved('new_tb_isi_paket', ['id_kdlist'=>$kdlist, 'kode_roll'=>$kodeRoll, 'siap_jual'=>$siap_jual]);
                    if($siap_jual=='y'){
                        $this->data_model->updatedata('kode_roll',$kodeRoll,'data_fol',['posisi'=>$kdlist]);
                    }else{
                        $this->data_model->updatedata('kode_roll',$kodeRoll,'data_ig',['loc_now'=>$kdlist]);
                    }
                    $cek_roll = $this->data_model->get_byid('new_tb_packinglist',['kd'=>$kdlist])->row("jumlah_roll");
                    $cek_ukuran = $this->data_model->get_byid('new_tb_packinglist',['kd'=>$kdlist])->row("ttl_panjang");
                    $new_ukuran = floatval($cek_ukuran) + $ukuran;
                    $new_roll = intval($cek_roll) + 1;
                    $this->data_model->updatedata('kd',$kdlist,'new_tb_packinglist',['jumlah_roll'=>$new_roll,'ttl_panjang'=>round($new_ukuran,2)]);
                    $data_input+=1;
                  } else {
                    $log = "Kode Roll (".$kodeRoll.") sudah berada di packinglist";
                    $this->data_model->saved('log_program', ['id_user'=>$id_user, 'log'=>$log]);
                    $data_gagal+=1;
                  }
                } else {
                  $log = "Kode Roll (".$kodeRoll.") tidak ditemukan";
                  $this->data_model->saved('log_program', ['id_user'=>$id_user, 'log'=>$log]);
                  $data_gagal+=1;
                }
              }
              $this->session->set_flashdata('announce', 'Berhasil menambahkan '.$data_input.' Roll ke packinglist (gagal menyimpan '.$data_gagal.' Roll)');
              redirect(base_url('data/kode/'.$kdlist));
            } else {
              echo "Erorr departement 2";
            }
          } else {
            echo "Erorr departement 3";
          }
          //end--
          
        } else {
          $er = explode(',', $txt);
          if(count($er)>1){
            //echo "bener pake koma dengan jumlah ".count($er);
            $data_input = 0; $data_gagal=0;
            for ($i=0; $i < count($er); $i++) {
              $kodeRoll = $er[$i];
              if($siap_jual=='y'){
                  $cek_kode = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kodeRoll]);
                  $in_paket = $cek_kode->row('posisi');
                  $ukuran = $cek_kode->row("ukuran");
              } else {
                  $cek_kode = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kodeRoll]);
                  $in_paket = $cek_kode->row('loc_now');
                  $ukuran = $cek_kode->row("ukuran_ori");
              }
              if($cek_kode->num_rows()==1){
                if($in_paket=="Samatex" OR $in_paket=="Pusatex" OR $in_paket=="RJS"){
                  //$this->data_model->saved('new_tb_isi_paket', ['id_kdlist'=>$kdlist, 'kode_roll'=>$kodeRoll, 'siap_jual'=>$siap_jual]);
                  if($siap_jual=='y'){
                      $this->data_model->updatedata('kode_roll',$kodeRoll,'data_fol',['posisi'=>$kdlist]);
                  }else{
                      $this->data_model->updatedata('kode_roll',$kodeRoll,'data_ig',['loc_now'=>$kdlist]);
                  }
                  $cek_roll = $this->data_model->get_byid('new_tb_packinglist',['kd'=>$kdlist])->row("jumlah_roll");
                  $cek_ukuran = $this->data_model->get_byid('new_tb_packinglist',['kd'=>$kdlist])->row("ttl_panjang");
                  $new_ukuran = floatval($cek_ukuran) + $ukuran;
                  $new_roll = intval($cek_roll) + 1;
                  $this->data_model->updatedata('kd',$kdlist,'new_tb_packinglist',['jumlah_roll'=>$new_roll,'ttl_panjang'=>round($new_ukuran,2)]);
                  $data_input+=1;
                } else {
                  $log = "Kode Roll (".$kodeRoll.") sudah berada di packinglist";
                  $this->data_model->saved('log_program', ['id_user'=>$id_user, 'log'=>$log]);
                  $data_gagal+=1;
                }
              } else {
                $log = "Kode Roll (".$kodeRoll.") tidak ditemukan";
                $this->data_model->saved('log_program', ['id_user'=>$id_user, 'log'=>$log]);
                $data_gagal+=1;
              }
            }
            $this->session->set_flashdata('announce', 'Berhasil menambahkan '.$data_input.' Roll ke packinglist (gagal menyimpan '.$data_gagal.' Roll)');
            redirect(base_url('data/kode/'.$kdlist));
          } else {
            //echo "cek data 1 tok";
            if($siap_jual=='y'){
                $cek_data = $this->data_model->get_byid('data_fol', ['kode_roll'=>$txt]);
                $in_paket = $cek_data->row('posisi');
                $ukuran = $cek_data->row("ukuran");
            } else {
                $cek_data = $this->data_model->get_byid('data_ig', ['kode_roll'=>$txt]);
                $in_paket = $cek_data->row('loc_now');
                $ukuran = $cek_data->row("ukuran_ori");
            }
            //$cek_data = $this->data_model->get_byid('new_tb_pkg_list', ['no_roll'=>$txt]);
            if($cek_data->num_rows() == 1){
              //$cek_lagi = $this->data_model->get_byid('new_tb_isi_paket', ['kode_roll'=>$txt,'siap_jual'=>$siap_jual]);
              if($in_paket=="Samatex" OR $in_paket=="Pusatex" OR $in_paket=="RJS"){
              //$this->data_model->saved('new_tb_isi_paket', ['id_kdlist'=>$kdlist, 'kode_roll'=>$txt, 'siap_jual'=>$siap_jual]);
              if($siap_jual=='y'){
                  $this->data_model->updatedata('kode_roll',$txt,'data_fol',['posisi'=>$kdlist]);
              }else{
                  $this->data_model->updatedata('kode_roll',$txt,'data_ig',['loc_now'=>$kdlist]);
              }
              $cek_roll = $this->data_model->get_byid('new_tb_packinglist',['kd'=>$kdlist])->row("jumlah_roll");
              $cek_ukuran = $this->data_model->get_byid('new_tb_packinglist',['kd'=>$kdlist])->row("ttl_panjang");
              $new_ukuran = floatval($cek_ukuran) + $ukuran;
              $new_roll = intval($cek_roll) + 1;
              $this->data_model->updatedata('kd',$kdlist,'new_tb_packinglist',['jumlah_roll'=>$new_roll,'ttl_panjang'=>round($new_ukuran,2)]);
              $this->session->set_flashdata('announce', 'Berhasil menambahkan 1 Roll ke packinglist');
              redirect(base_url('data/kode/'.$kdlist));
              } else {
                $this->session->set_flashdata('announce', 'Kode Roll sudah berada di dalam paket packinglist');
                redirect(base_url('data/kode/'.$kdlist));
              }
            } else {
              $this->session->set_flashdata('gagal', 'Kode Roll yang anda masukan tidak ditemukan');
              redirect(base_url('data/kode/'.$kdlist));
            }
          }
        }
     } else {
        $this->session->set_flashdata('gagal', 'Anda tidak menambahkan data dengan benar');
        redirect(base_url('data/kode/'.$kdlist));
     }
    //  $loaddata = $this->data_model->get_byid('new_tb_isi_paket', ['id_kdlist'=>$kdlist]);
    //  $panjang_total = 0;
    //  foreach($loaddata->result() as $val):
    //     $kdroll = $val->kode_roll;
    //     $roll = $this->data_model->get_byid('new_tb_pkg_fol', ['no_roll'=>$kdroll]);
    //     if($roll->num_rows()==1){
          
    //     } else {

    //     }
    //  endforeach;
  } //end

  function join(){
      $joinkode = $this->input->post('koderolling');
      $ukrakhir = $this->input->post('ukrakhir');
      $kdakhir = $this->input->post('kdakhir');
      $satuan = $this->input->post('satuan');
      $tgljoin = $this->input->post('tgljoin');
      $dep_user = $this->session->userdata('departement');
      if($joinkode!="" AND $ukrakhir!="" AND $kdakhir!=""){
          $ex = explode(',', $joinkode);
          if(count($ex) > 1){
              $roll_benar = 0; $kode_erorr = "";
              for ($i=0; $i <count($ex) ; $i++) { 
                  $cek_roll = $this->data_model->get_byid('new_tb_pkg_list', ['no_roll'=>$ex[$i]]);
                  if($cek_roll->num_rows()==0){
                    $roll_benar+=1;
                    $kode_erorr .= ",".$ex[$i]."";
                  }
              }
              if($roll_benar==0){
                $cek_kdakhir = $this->data_model->get_byid('new_tb_pkg_list', ['no_roll'=>$kdakhir]);
                if($cek_kdakhir->num_rows() == 0){
                    if($satuan=="Yard"){
                      $uky = floatval($ukrakhir);
                      $ukm = $ukrakhir * 0.9144;
                    } else {
                      $ukm = floatval($ukrakhir);
                      $uky = $ukrakhir / 0.9144;
                    }
                    
                    for ($i=0; $i <count($ex) ; $i++) {
                        if($i==0){
                          if($satuan=="Yard"){
                              $dt = $this->data_model->get_byid('new_tb_pkg_ins', ['no_roll'=>$ex[$i]]);
                              $kd_produksi = $dt->row("kode_produksi");
                              $kdkons = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kd_produksi])->row("kode_konstruksi");
                              $ukuran = $dt->row("ukuran_ori_yard");
                              $cekroll_out = $this->data_model->get_byid('new_tb_stok_outside', ['kd_roll'=>$ex[$i]]);
                              if($cekroll_out->num_rows()==1){
                                  $cek_dep = $cekroll_out->row("pemilik")."-IN-".$cekroll_out->row("lokasi_skrg");
                                  $cekstok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kdkons, 'departement'=>$cek_dep]);
                                  $id_stok = $cekstok->row("id_stok");
                              } else {
                                  $cekstok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kdkons, 'departement'=>$dep_user]);
                                  $id_stok = $cekstok->row("id_stok");
                              }
                              $insf = floatval($cekstok->row("stok_ins_finish_yard")) - $ukuran;
                              $insfm = $insf * 0.9144;
                              $folf = floatval($cekstok->row("stok_fol_finish_yard")) + $uky;
                              $folfm = floatval($cekstok->row("stok_fol_finish")) + $ukm;
                              $this->data_model->updatedata('id_stok',$id_stok,'report_stok',['stok_ins_finish'=>round($insf,2),'stok_fol_finish'=>round($folfm,2), 'stok_ins_finish_yard'=>round($insfm,2), 'stok_fol_finish_yard'=>round($folf,2)]);
                          } else {
                              $dt = $this->data_model->get_byid('new_tb_pkg_list', ['no_roll'=>$ex[$i]]);
                              $kd_produksi = $dt->row("kode_produksi");
                              $kdkons = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kd_produksi])->row("kode_konstruksi");
                              $ukuran = $dt->row("ukuran_ori");
                              $cekroll_out = $this->data_model->get_byid('new_tb_stok_outside', ['kd_roll'=>$ex[$i]]);
                              if($cekroll_out->num_rows()==1){
                                  $cek_dep = $cekroll_out->row("pemilik")."-IN-".$cekroll_out->row("lokasi_skrg");
                                  $cekstok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kdkons, 'departement'=>$cek_dep]);
                                  $id_stok = $cekstok->row("id_stok");
                              } else {
                                  $cekstok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kdkons, 'departement'=>$dep_user]);
                                  $id_stok = $cekstok->row("id_stok");
                              }
                              $insf = floatval($cekstok->row("stok_ins")) - $ukuran;
                              $insfy = $insf / 0.9144;
                              $folf = floatval($cekstok->row("stok_fol_yard")) + $uky;
                              $folfm = floatval($cekstok->row("stok_fol")) + $ukm;
                              $this->data_model->updatedata('id_stok',$id_stok,'report_stok',['stok_ins_finish'=>round($insf,2),'stok_fol'=>round($folfm,2), 'stok_ins_finish_yard'=>round($insfm,2), 'stok_fol_yard'=>round($folf,2)]);
                          }
                          $dtlist_fol = [
                              'kode_produksi' => $kd_produksi,
                              'asal' => 'list',
                              'id_asal' => 0,
                              'no_roll' => $kdakhir,
                              'tgl' => $tgljoin,
                              'ukuran' => round($ukm,2),
                              'operator' => 'null',
                              'st_folding' => $satuan=='Yard'?'Finish':'Grey',
                              'ukuran_now' => round($ukm,2),
                              'ukuran_yard' => round($uky,2),
                              'ukuran_now_yard' => round($uky,2),
                              'id_effected_row' => 0
                          ];
                          $this->data_model->saved('new_tb_pkg_fol', $dtlist_fol);
                            $dtlist = [
                              'kode_produksi' => $kd_produksi,
                              'no_roll' => $kdakhir,
                              'ukuran_ori' => 0,
                              'ukuran_b' => 0,
                              'ukuran_c' => 0,
                              'ukuran_bs' => 0,
                              'ukuran_now' => 0,
                              'operator' => 'null',
                              'st_pkg' => 'IG',
                              'satuan' => 'Meter',
                              'ukuran_ori_yard' => 0,
                              'ukuran_b_yard' => 0,
                              'ukuran_c_yard' => 0,
                              'ukuran_bs_yard' => 0,
                              'ukuran_now_yard' => 0,
                              'oka' => ''
                          ];
                          $this->data_model->saved('new_tb_pkg_list', $dtlist);
                        } else {
                          if($satuan=="Yard"){
                              $dt = $this->data_model->get_byid('new_tb_pkg_ins', ['no_roll'=>$ex[$i]]);
                              $kd_produksi = $dt->row("kode_produksi");
                              $kdkons = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kd_produksi])->row("kode_konstruksi");
                              $ukuran = $dt->row("ukuran_ori_yard");
                              $cekroll_out = $this->data_model->get_byid('new_tb_stok_outside', ['kd_roll'=>$ex[$i]]);
                              if($cekroll_out->num_rows()==1){
                                  $cek_dep = $cekroll_out->row("pemilik")."-IN-".$cekroll_out->row("lokasi_skrg");
                                  $cekstok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kdkons, 'departement'=>$cek_dep]);
                                  $id_stok = $cekstok->row("id_stok");
                              } else {
                                  $cekstok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kdkons, 'departement'=>$dep_user]);
                                  $id_stok = $cekstok->row("id_stok");
                              }
                              $insf = floatval($cekstok->row("stok_ins_finish_yard")) - $ukuran;
                              $insfm = $insf * 0.9144;
                              $this->data_model->updatedata('id_stok',$id_stok,'report_stok',['stok_ins_finish'=>round($insf,2), 'stok_ins_finish_yard'=>round($insfm,2)]);
                          } else {
                              $dt = $this->data_model->get_byid('new_tb_pkg_list', ['no_roll'=>$ex[$i]]);
                              $kd_produksi = $dt->row("kode_produksi");
                              $kdkons = $this->data_model->get_byid('tb_produksi', ['kode_produksi'=>$kd_produksi])->row("kode_konstruksi");
                              $ukuran = $dt->row("ukuran_ori");
                              $cekroll_out = $this->data_model->get_byid('new_tb_stok_outside', ['kd_roll'=>$ex[$i]]);
                              if($cekroll_out->num_rows()==1){
                                  $cek_dep = $cekroll_out->row("pemilik")."-IN-".$cekroll_out->row("lokasi_skrg");
                                  $cekstok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kdkons, 'departement'=>$cek_dep]);
                                  $id_stok = $cekstok->row("id_stok");
                              } else {
                                  $cekstok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kdkons, 'departement'=>$dep_user]);
                                  $id_stok = $cekstok->row("id_stok");
                              }
                              $insf = floatval($cekstok->row("stok_ins")) - $ukuran;
                              $insfy = $insf / 0.9144;
                              $this->data_model->updatedata('id_stok',$id_stok,'report_stok',['stok_ins'=>round($insf,2), 'stok_ins_yard'=>round($insfy,2)]);
                          }
                        }
                    }
                } else {
                  $this->session->set_flashdata('gagal', 'Gagal Join, Kode Roll '.$kdakhir.' telah digunakan');
                  redirect(base_url('proses-produksi'));
                }
              } else {
                $this->session->set_flashdata('gagal', 'Gagal Join. Kode Roll '.$kode_erorr.' tidak ditemukan');
                redirect(base_url('proses-produksi'));
              }
          } else {
            $this->session->set_flashdata('gagal', 'Minimal join 2 kode roll');
            redirect(base_url('proses-produksi'));
          }
      } else {
          $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
          redirect(base_url('proses-produksi'));
      }

  } //end

}
?>
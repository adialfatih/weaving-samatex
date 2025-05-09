<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alldashboard extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
           
  }
   
  function index(){ 
      $this->load->view('manager/logindashboard');
  } //end

  function loaddataajl(){
        $pss = $this->input->post('posisi');
        $tgl = $this->input->post('tgl');
        //echo "owek $pss $tgl";
        echo "<table>";
        if($pss == "RJS"){
            $query = $this->data_model->get_byid('dt_produksi_mesin', ['tanggal_produksi'=>$tgl,'lokasi'=>"RJS"]);
            echo '<tr>
                <td><strong>No</strong></td>
                <td><strong>Konstruksi</strong></td>
                <td><strong>Mesin</strong></td>
                <td><strong>Hasil Produksi</strong></td>
            </tr>';
            if($query->num_rows() > 0){
                $totalmc=0;
                $totalhasil=0;
                foreach($query->result() as $n => $val):
                    echo "<tr>";
                    $num = $n+1;
                    echo "<td>".$num."</td>";
                    echo "<td>".$val->kode_konstruksi."</td>";
                    echo "<td>".$val->jumlah_mesin."</td>";
                    if(fmod($val->hasil, 1) !== 0.00){
                        echo "<td>". number_format($val->hasil,2,',','.')."</td>";
                    } else {
                        echo "<td>". number_format($val->hasil,0,',','.')."</td>";
                    }
                    echo "</tr>";
                    $totalmc+=$val->jumlah_mesin;
                    $totalhasil+=$val->hasil;
                endforeach;
                echo "<tr>";
                echo "<td colspan='2'><strong>Total</strong></td>";
                echo "<td>".$totalmc."</td>";
                if(fmod($totalhasil, 1) !== 0.00){
                    echo "<td>". number_format($totalhasil,2,',','.')."</td>";
                } else {
                    echo "<td>". number_format($totalhasil,0,',','.')."</td>";
                }
                echo "</tr>";
            } else {
                echo "<tr><td colspan='4'>Tidak ada data</td></tr>";
            }
        }
        if($pss == "Samatex"){
            $query = $this->data_model->get_byid('dt_produksi_mesin', ['tanggal_produksi'=>$tgl,'lokasi'=>"Samatex"]);
            echo '<tr>
                <td><strong>No</strong></td>
                <td><strong>Konstruksi</strong></td>
                <td><strong>Mesin</strong></td>
                <td><strong>Hasil Produksi</strong></td>
            </tr>';
            if($query->num_rows() > 0){
                $totalmc=0;
                $totalhasil=0;
                foreach($query->result() as $n => $val):
                    echo "<tr>";
                    $num = $n+1;
                    echo "<td>".$num."</td>";
                    echo "<td>".$val->kode_konstruksi."</td>";
                    echo "<td>".$val->jumlah_mesin."</td>";
                    if(fmod($val->hasil, 1) !== 0.00){
                        echo "<td>". number_format($val->hasil,2,',','.')."</td>";
                    } else {
                        echo "<td>". number_format($val->hasil,0,',','.')."</td>";
                    }
                    echo "</tr>";
                    $totalmc+=$val->jumlah_mesin;
                    $totalhasil+=$val->hasil;
                endforeach;
                echo "<tr>";
                echo "<td colspan='2'><strong>Total</strong></td>";
                echo "<td>".$totalmc."</td>";
                if(fmod($totalhasil, 1) !== 0.00){
                    echo "<td>". number_format($totalhasil,2,',','.')."</td>";
                } else {
                    echo "<td>". number_format($totalhasil,0,',','.')."</td>";
                }
                echo "</tr>";
            } else {
                echo "<tr><td colspan='4'>Tidak ada data</td></tr>";
            }
        }
        echo "</table>";
  } //end

  function loaddata(){
        $jns = $this->input->post('jns');
        if($jns=="Grey"){
            $jenis = "Grey";
        } elseif($jns=="Finish"){
            $jenis = "Finish";
        } else {
            $jenis = "All";
        }
        $tgl = $this->input->post('tgl');
        $kons = $this->input->post('kons');
        $cekkons = $this->data_model->get_byid('dt_konsumen', ['nama_konsumen'=>$kons]);
        if($cekkons->num_rows() == 1){
            $idcus = $cekkons->row("id_konsumen");
        } else {
            $idcus = "all";
        }
        $ex = explode(' - ', $tgl);
        $ex1 = explode('/', $ex[0]);
        $tanggal1 = $ex1[2]."-".$ex1[0]."-".$ex1[1];
        $ex2 = explode('/', $ex[1]);
        $tanggal2 = $ex2[2]."-".$ex2[0]."-".$ex2[1];
        if($tanggal1 == $tanggal2){
            if($idcus == "all"){
                if($jenis == "All"){
                    $query = $this->db->query("SELECT * FROM view_nota2 WHERE tgl_nota='$tanggal1' ");
                    $query1 = "SELECT * FROM view_nota2 WHERE tgl_nota='$tanggal1' ";
                } else {
                    $query = $this->db->query("SELECT * FROM view_nota2 WHERE jns_fold='$jenis' AND tgl_nota='$tanggal1' ");
                    $query1 = "SELECT * FROM view_nota2 WHERE jns_fold='$jenis' AND tgl_nota='$tanggal1' ";
                }
            } else {
                if($jenis == "All"){
                    $query = $this->db->query("SELECT * FROM view_nota2 WHERE id_customer='$idcus' AND tgl_nota='$tanggal1' ");
                    $query1 = "SELECT * FROM view_nota2 WHERE id_customer='$idcus' AND tgl_nota='$tanggal1' ";
                } else {
                    $query = $this->db->query("SELECT * FROM view_nota2 WHERE id_customer='$idcus' AND jns_fold='$jenis' AND  tgl_nota='$tanggal1' ");
                    $query1 = "SELECT * FROM view_nota2 WHERE id_customer='$idcus' AND jns_fold='$jenis' AND  tgl_nota='$tanggal1' ";
                }
            }
        } else {
            if($idcus == "all"){
                if($jenis == "All"){
                    $query = $this->db->query("SELECT * FROM view_nota2 WHERE tgl_nota BETWEEN '$tanggal1' AND '$tanggal2' ");
                    $query1 = "SELECT * FROM view_nota2 WHERE tgl_nota BETWEEN '$tanggal1' AND '$tanggal2' ";
                } else {
                    $query = $this->db->query("SELECT * FROM view_nota2 WHERE jns_fold='$jenis' AND tgl_nota BETWEEN '$tanggal1' AND '$tanggal2' ");
                    $query1 = "SELECT * FROM view_nota2 WHERE jns_fold='$jenis' AND tgl_nota BETWEEN '$tanggal1' AND '$tanggal2' ";
                }
                
            } else {
                if($jenis == "All"){
                    $query = $this->db->query("SELECT * FROM view_nota2 WHERE id_customer='$idcus' AND tgl_nota BETWEEN '$tanggal1' AND '$tanggal2'  ");
                    $query1 = "SELECT * FROM view_nota2 WHERE id_customer='$idcus' AND tgl_nota BETWEEN '$tanggal1' AND '$tanggal2'  ";
                } else {
                    $query = $this->db->query("SELECT * FROM view_nota2 WHERE id_customer='$idcus' AND jns_fold='$jenis' AND tgl_nota BETWEEN '$tanggal1' AND '$tanggal2'  ");
                    $query1 = "SELECT * FROM view_nota2 WHERE id_customer='$idcus' AND jns_fold='$jenis' AND tgl_nota BETWEEN '$tanggal1' AND '$tanggal2'  ";
                }
            }
        }
        echo "<table>";
        echo "<tr>";
        echo "<td><strong>No.</strong></td>";
        echo "<td><strong>Konsumen</strong></td>";
        echo "<td><strong>Konstruksi</strong></td>";
        echo "<td><strong>Jumlah</strong></td>";
        echo "</tr>";
        $n=1;
        $total = 0;
        if($query->num_rows() > 0){
            $ar_konstruksi = array();
            foreach($query->result() as $val):
                echo "<tr>";
                echo "<td>".$n."</td>";
                echo "<td>".$val->nama_konsumen."</td>";
                if (in_array($val->konstruksi, $ar_konstruksi)) {} else {
                    $ar_konstruksi[]=$val->konstruksi;
                }
                echo "<td>".$val->konstruksi."</td>";
                if(fmod($val->total_panjang, 1) !== 0.00){
                    $ajl3 = number_format($val->total_panjang,2,',','.');
                } else {
                    $ajl3 = number_format($val->total_panjang,0,',','.');
                }
                $total+=$val->total_panjang;
                echo "<td>".$ajl3."</td>";
                echo "</tr>";
                $n++;
            endforeach;
            if(fmod($total, 1) !== 0.00){
                $ttl = number_format($total,2,',','.');
            } else {
                $ttl = number_format($total,0,',','.');
            }
            echo "<tr>";
            echo "<td colspan='3'><strong>Total</strong></td>";
            echo "<td><strong>".$ttl."</strong></td>";
            echo "</tr>";
            echo "<tr><td colspan='4'>&nbsp;</td></tr>";
            echo "<tr>";
            echo "<td colspan='4'><strong>Resume</strong></td>";
            foreach($ar_konstruksi as $bal){
                echo "<tr>";
                echo "<td></td><td></td>";
                echo "<td>$bal</td>";
                $ex_query = explode('* FROM view_nota2 WHERE', $query1);
                $new_query = "SELECT SUM(total_panjang) AS ukr FROM view_nota2 WHERE konstruksi='$bal' AND ".$ex_query[1]."";
                $ukr = $this->db->query($new_query)->row("ukr");
                if(fmod($ukr, 1) !== 0.00){
                    $ukr2 = number_format($ukr,2,',','.');
                } else {
                    $ukr2 = number_format($ukr,0,',','.');
                }
                echo "<td>$ukr2</td>";
                echo "</tr>";
                //echo "<tr><td colspan='4'>$new_query</td></tr>";
            }
            echo "<tr>";
        } else {
            echo "<tr>";
            echo "<td colspan='4'>Belum ada penjualan $jenis</td>";
            echo "</tr>";
        }
        //echo "<tr><td>".$query1."</td></tr>";
        echo "</table>";
        
  } //
  function proseslogin(){
        $username = $this->data_model->clean($this->input->post('username'));
        $password = $this->input->post('password');
if($username == "adi2" OR $username == "Hamid" OR $username == "hamid"){
        if($username!="" AND $password!=""){
            $ceklogin = $this->data_model->get_byid('user', ['username'=>$username,'password'=>sha1($password),'hak_akses'=>'Manager']);
            if($ceklogin->num_rows() == 1){
                $dt = $ceklogin->row_array();
                $data_session = array(
                    'nama'  => $dt['nama_user'],
                    'username'=> $dt['username'],
                    'password' => $dt['password'],
                    'hak'     => $dt['hak_akses'],
                    'departement' => $dt['departement'],
                    'mng_dash'=> 'manager_dash'
                );
                $this->session->set_userdata($data_session);
                redirect(base_url('dash-manager'));
            } else {
                echo "Username dan Password anda tidak cocok.";
            }
        } else {
            echo "Anda harus mengisi username dan password.";
        }
 } else {
            $this->load->view('under_maintainance');
        }
  } //end

  function halamanutama(){
        if($this->session->userdata('mng_dash') == "manager_dash"){
            $this->load->view('manager/showdashboard');
        } else {
            echo "<a href='".base_url('all-dashboard')."' style='text-decoration:none;'><div style='width:100%;height:100vh;display:flex;justify-content:center;align-items:center;font-size:3em;'>Akses diblokir</div></a>";
        }
  } //end

  function penjualangrey(){
        $this->load->view('manager/penjualanGrey');
  } //end

  function penjualanfinish(){
        $this->load->view('manager/penjualanFinish');
  } //end
  function penjualanall(){
        $this->load->view('manager/penjualanAll');
  } //end-
  function stokallpusatex(){
        $this->load->view('manager/stok_all_pusatex');
  } //end
  
  function stokall(){
        $this->load->view('manager/stok_all');
  } //end
  function stokgrey(){
        $this->load->view('manager/stok_grey');
  } //end
  function stokfinish(){
        $this->load->view('manager/stok_finish');
  } //end-
  function stokonpkg(){
    $this->load->view('manager/stok_onpkg');
  } //end-
  function inspect_all(){
        $this->load->view('manager/inspect_all');
  } //end-
  function inspect_grey(){
        $this->load->view('manager/inspect_grey');
  } //end-
  function inspect_finish(){
        $this->load->view('manager/inspect_finish');
  } //end-
  function inspect_rjs(){
        $this->load->view('manager/inspect_rjs');
  } //end-
  function folding(){
        $this->load->view('manager/hasil_folding');
  } //end-
  function folding_grey(){
        $this->load->view('manager/hasil_folding_grey');
  } //end-
  function folding_finish(){
        $this->load->view('manager/hasil_folding_finish');
  } //end-
  function ajl_rindang(){
        $this->load->view('manager/ajl_rindang');
  } //end-
  function ajl_samatex(){
        $this->load->view('manager/ajl_samatex');
  } //end-

  function loaddataFolding(){
    $jns = $this->input->post('jns');
    $tgl = $this->input->post('tgl');
        if($jns == "Grey"){
            $query = $this->data_model->get_byid('data_produksi', ['tgl'=>$tgl, 'dep'=>'Samatex']);
            foreach($query->result() as $val){
                $kons = $val->kode_konstruksi;
                $fg = $val->prod_fg;
                if(floatval($fg) > 0){
                    if(fmod($fg, 1) !== 0.00){
                        $fg2 = number_format($fg,2,',','.');
                    } else {
                        $fg2 = number_format($fg,0,',','.');
                    }
                    echo '<div class="card-kons">';
                    echo '<div>'.$kons.'</div>'; 
                    echo '<div>'.$fg2.'</div>';
                    echo '</div>';
                }
            }
        } elseif($jns == "Finish"){
            $query = $this->data_model->get_byid('data_produksi', ['tgl'=>$tgl, 'dep'=>'Samatex']);
            foreach($query->result() as $val){
                $kons = $val->kode_konstruksi;
                $ff = $val->prod_ff;
                if(floatval($ff) > 0){
                    if(fmod($ff, 1) !== 0.00){
                        $ff2 = number_format($ff,2,',','.');
                    } else {
                        $ff2 = number_format($ff,0,',','.');
                    }
                    $chto = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$kons])->row("chto");
                    echo '<div class="card-kons">';
                    echo '<div>'.$chto.'</div>'; 
                    echo '<div>'.$ff2.'</div>';
                    echo '</div>';
                }
            }
        } elseif($jns == "RJS"){
            $query = $this->data_model->get_byid('data_produksi', ['tgl'=>$tgl, 'dep'=>'RJS']);
            foreach($query->result() as $val){
                $kons = $val->kode_konstruksi;
                $fg = $val->prod_fg;
                if(floatval($fg) > 0){
                    if(fmod($fg, 1) !== 0.00){
                        $fg2 = number_format($fg,2,',','.');
                    } else {
                        $fg2 = number_format($fg,0,',','.');
                    }
                    echo '<div class="card-kons">';
                    echo '<div>'.$kons.'</div>'; 
                    echo '<div>'.$fg2.'</div>';
                    echo '</div>';
                }
            }
        } else {
            $query = $this->data_model->get_byid('data_produksi', ['tgl'=>$tgl, 'dep'=>'Samatex']);
            foreach($query->result() as $val){
                $kons = $val->kode_konstruksi;
                $fg = $val->prod_fg;
                $ff = $val->prod_ff;
                if(floatval($fg) > 0){
                    if(fmod($fg, 1) !== 0.00){
                        $fg2 = number_format($fg,2,',','.');
                    } else {
                        $fg2 = number_format($fg,0,',','.');
                    }
                    echo '<div class="card-kons">';
                    echo '<div>'.$kons.'</div>'; 
                    echo '<div>'.$fg2.'</div>';
                    echo '</div>';
                }
                if(floatval($ff) > 0){
                    if(fmod($ff, 1) !== 0.00){
                        $ff2 = number_format($ff,2,',','.');
                    } else {
                        $ff2 = number_format($ff,0,',','.');
                    }
                    $chto = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$kons])->row("chto");
                    echo '<div class="card-kons">';
                    echo '<div>'.$chto.'</div>'; 
                    echo '<div>'.$ff2.'</div>';
                    echo '</div>';
                }
                
            }
        }
  } //end

  function loaddataInspect(){
    $jns = $this->input->post('jns');
    $tgl = $this->input->post('tgl');
        if($jns == "Grey"){
            $query = $this->data_model->get_byid('data_produksi', ['tgl'=>$tgl, 'dep'=>'Samatex']);
            foreach($query->result() as $val){
                $kons = $val->kode_konstruksi;
                $fg = $val->prod_ig;
                if(floatval($fg) > 0){
                    if(fmod($fg, 1) !== 0.00){
                        $fg2 = number_format($fg,2,',','.');
                    } else {
                        $fg2 = number_format($fg,0,',','.');
                    }
                    echo '<div class="card-kons">';
                    echo '<div>'.$kons.'</div>'; 
                    echo '<div>'.$fg2.'</div>';
                    echo '</div>';
                }
            }
        } elseif($jns == "Finish"){
            $query = $this->data_model->get_byid('data_produksi', ['tgl'=>$tgl, 'dep'=>'Samatex']);
            foreach($query->result() as $val){
                $kons = $val->kode_konstruksi;
                $ff = $val->prod_if;
                if(floatval($ff) > 0){
                    if(fmod($ff, 1) !== 0.00){
                        $ff2 = number_format($ff,2,',','.');
                    } else {
                        $ff2 = number_format($ff,0,',','.');
                    }
                    $chto = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$kons])->row("chto");
                    echo '<div class="card-kons">';
                    echo '<div>'.$chto.'</div>'; 
                    echo '<div>'.$ff2.'</div>';
                    echo '</div>';
                }
            }
        } elseif($jns == "RJS"){
            $query = $this->data_model->get_byid('data_produksi', ['tgl'=>$tgl, 'dep'=>'RJS']);
            foreach($query->result() as $val){
                $kons = $val->kode_konstruksi;
                $fg = $val->prod_ig;
                if(floatval($fg) > 0){
                    if(fmod($fg, 1) !== 0.00){
                        $fg2 = number_format($fg,2,',','.');
                    } else {
                        $fg2 = number_format($fg,0,',','.');
                    }
                    echo '<div class="card-kons">';
                    echo '<div>'.$kons.'</div>'; 
                    echo '<div>'.$fg2.'</div>';
                    echo '</div>';
                }
            }
        } else {
            $query = $this->data_model->get_byid('data_produksi', ['tgl'=>$tgl, 'dep'=>'Samatex']);
            foreach($query->result() as $val){
                $kons = $val->kode_konstruksi;
                $fg = $val->prod_ig;
                $ff = $val->prod_if;
                if(floatval($fg) > 0){
                    if(fmod($fg, 1) !== 0.00){
                        $fg2 = number_format($fg,2,',','.');
                    } else {
                        $fg2 = number_format($fg,0,',','.');
                    }
                    echo '<div class="card-kons">';
                    echo '<div>'.$kons.'</div>'; 
                    echo '<div>'.$fg2.'</div>';
                    echo '</div>';
                }
                if(floatval($ff) > 0){
                    if(fmod($ff, 1) !== 0.00){
                        $ff2 = number_format($ff,2,',','.');
                    } else {
                        $ff2 = number_format($ff,0,',','.');
                    }
                    $chto = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$kons])->row("chto");
                    echo '<div class="card-kons">';
                    echo '<div>'.$chto.'</div>'; 
                    echo '<div>'.$ff2.'</div>';
                    echo '</div>';
                }
                
            }
        }
  }

  function loaddatastok(){
        $jns = $this->input->post('jns');
        if($jns == "Grey"){
            $query = $this->db->query("SELECT * FROM data_stok WHERE dep='newSamatex' AND prod_fg > 0 ORDER BY kode_konstruksi");
            //$query = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex', 'prod_fg >='=>1]);
            foreach($query->result() as $val){
                $kons = $val->kode_konstruksi;
                $fg = $val->prod_fg;
                if(floatval($fg) > 0){
                    if(fmod($fg, 1) !== 0.00){
                        $fg2 = number_format($fg,2,',','.');
                    } else {
                        $fg2 = number_format($fg,0,',','.');
                    }
                    $cekTotalOnPkg = $this->data_model->get_byid('new_tb_packinglist', ['kode_konstruksi'=>$kons,'lokasi_now'=>'Samatex','siap_jual'=>'y','no_sj'=>'NULL','customer!='=>'tes']);
                    $jumlahOnPKG = 0;
                    foreach($cekTotalOnPkg->result() as $pe){
                        $jumlahOnPKG+=floatval($pe->ttl_panjang);
                    }
                    if(fmod($jumlahOnPKG, 1) !== 0.00){
                        $jumlahOnPKG2 = number_format($jumlahOnPKG,2,',','.');
                    } else {
                        $jumlahOnPKG2 = number_format($jumlahOnPKG,0,',','.');
                    }
                    if($jumlahOnPKG > 0){
                        $new_fg = floatval($fg) - floatval($jumlahOnPKG);
                        if(fmod($new_fg, 1) !== 0.00){
                            $fg2 = number_format($new_fg,2,',','.');
                        } else {
                            $fg2 = number_format($new_fg,0,',','.');
                        }
                    }
                    echo '<div class="card-kons">';
                    echo '<div>'.$kons.'</div>';
                    if(floatval($fg2) > 0){
                        echo '<div>'.$fg2.'</div>';
                    } else {
                        echo '<div>0</div>';
                    }
                    if($jumlahOnPKG>0){
                        echo "<a href='".base_url('stok-onpkg/'.sha1($kons))."' style='text-decoration:none;'>";
                        echo '<div style="background:#ccc;color:red;padding:3px 0;font-size:13px;">'.$jumlahOnPKG2.'</div>';
                        echo '</a>';
                    } else {
                        echo '<div style="background:#ccc;color:red;padding:3px 0;font-size:13px;">-</div>';
                    }
                    echo '</div>';
                }
            }
        } elseif($jns == "Finish"){
            $query = $this->db->query("SELECT * FROM data_stok WHERE dep='newSamatex' AND prod_ff > 0 ORDER BY kode_konstruksi");
            //$query = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex', 'prod_ff >='=>1]);
            foreach($query->result() as $val){
                $kons = $val->kode_konstruksi;
                $ff = $val->prod_ff;
                if(floatval($ff) > 0){
                    if(fmod($ff, 1) !== 0.00){
                        $ff2 = number_format($ff,2,',','.');
                    } else {
                        $ff2 = number_format($ff,0,',','.');
                    }
                    $chto = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$kons])->row("chto");
                    $cekTotalOnPkg = $this->data_model->get_byid('new_tb_packinglist', ['kode_konstruksi'=>$chto,'lokasi_now'=>'Samatex','siap_jual'=>'y','no_sj'=>'NULL','customer!='=>'tes']);
                    $jumlahOnPKG = 0;
                    foreach($cekTotalOnPkg->result() as $pe){
                        $jumlahOnPKG+=floatval($pe->ttl_panjang);
                    }
                    if(fmod($jumlahOnPKG, 1) !== 0.00){
                        $jumlahOnPKG2 = number_format($jumlahOnPKG,2,',','.');
                    } else {
                        $jumlahOnPKG2 = number_format($jumlahOnPKG,0,',','.');
                    }
                    if($jumlahOnPKG > 0){
                        $new_ff = floatval($ff) - floatval($jumlahOnPKG);
                        if(fmod($new_ff, 1) !== 0.00){
                            $ff2 = number_format($new_ff,2,',','.');
                        } else {
                            $ff2 = number_format($new_ff,0,',','.');
                        }
                    }
                    echo '<div class="card-kons">';
                    echo '<div>'.$chto.'</div>'; 
                    if(floatval($ff2) > 0){
                        echo '<div>'.$ff2.'</div>';
                    } else {
                        echo '<div>0</div>';
                    }
                    if($jumlahOnPKG>0){
                        echo "<a href='".base_url('stok-onpkg/'.sha1($chto))."' style='text-decoration:none;'>";
                        echo '<div style="background:#ccc;color:red;padding:3px 0;font-size:13px;">'.$jumlahOnPKG2.'</div>';
                        echo '</a>';
                    } else {
                        echo '<div style="background:#ccc;color:red;padding:3px 0;font-size:13px;">-</div>';
                    }
                    echo '</div>';
                }
            }
        } else {
            $query = $this->db->query("SELECT * FROM data_stok WHERE dep='newSamatex' ORDER BY kode_konstruksi");
            //$query = $this->data_model->get_byid('data_stok', ['dep'=>'newSamatex']);
            foreach($query->result() as $val){
                $kons = $val->kode_konstruksi;
                $fg = $val->prod_fg;
                $ff = $val->prod_ff;
                if(floatval($fg) > 0){
                    if(fmod($fg, 1) !== 0.00){
                        $fg2 = number_format($fg,2,',','.');
                    } else {
                        $fg2 = number_format($fg,0,',','.');
                    }
                    $cekTotalOnPkg = $this->data_model->get_byid('new_tb_packinglist', ['kode_konstruksi'=>$kons,'lokasi_now'=>'Samatex','siap_jual'=>'y','no_sj'=>'NULL','customer!='=>'tes']);
                    $jumlahOnPKG = 0;
                    foreach($cekTotalOnPkg->result() as $pe){
                        $jumlahOnPKG+=floatval($pe->ttl_panjang);
                    }
                    if(fmod($jumlahOnPKG, 1) !== 0.00){
                        $jumlahOnPKG2 = number_format($jumlahOnPKG,2,',','.');
                    } else {
                        $jumlahOnPKG2 = number_format($jumlahOnPKG,0,',','.');
                    }
                    if($jumlahOnPKG > 0){
                        $new_fg = floatval($fg) - floatval($jumlahOnPKG);
                        if(fmod($new_fg, 1) !== 0.00){
                            $fg2 = number_format($new_fg,2,',','.');
                        } else {
                            $fg2 = number_format($new_fg,0,',','.');
                        }
                    }
                    echo '<div class="card-kons">';
                    echo '<div>'.$kons.'</div>'; 
                    if(floatval($fg2) > 0){
                        echo '<div>'.$fg2.'</div>';
                    } else {
                        echo '<div>0</div>';
                    }
                    if($jumlahOnPKG>0){
                        echo "<a href='".base_url('stok-onpkg/'.sha1($kons))."' style='text-decoration:none;'>";
                        echo '<div style="background:#ccc;color:red;padding:3px 0;font-size:13px;">'.$jumlahOnPKG2.'</div>';
                        echo '</a>';
                    } else {
                        echo '<div style="background:#ccc;color:red;padding:3px 0;font-size:13px;">-</div>';
                    }
                    echo '</div>';
                }
                if(floatval($ff) > 0){
                    if(fmod($ff, 1) !== 0.00){
                        $ff2 = number_format($ff,2,',','.');
                    } else {
                        $ff2 = number_format($ff,0,',','.');
                    }
                    $chto = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$kons])->row("chto");
                    $cekTotalOnPkg = $this->data_model->get_byid('new_tb_packinglist', ['kode_konstruksi'=>$chto,'lokasi_now'=>'Samatex','siap_jual'=>'y','no_sj'=>'NULL','customer!='=>'tes']);
                    $jumlahOnPKG = 0;
                    foreach($cekTotalOnPkg->result() as $pe){
                        $jumlahOnPKG+=floatval($pe->ttl_panjang);
                    }
                    if(fmod($jumlahOnPKG, 1) !== 0.00){
                        $jumlahOnPKG2 = number_format($jumlahOnPKG,2,',','.');
                    } else {
                        $jumlahOnPKG2 = number_format($jumlahOnPKG,0,',','.');
                    }
                    if($jumlahOnPKG > 0){
                        $new_ff = floatval($ff) - floatval($jumlahOnPKG);
                        if(fmod($new_ff, 1) !== 0.00){
                            $ff2 = number_format($new_ff,2,',','.');
                        } else {
                            $ff2 = number_format($new_ff,0,',','.');
                        }
                    }
                    echo '<div class="card-kons">';
                    echo '<div>'.$chto.'</div>';
                    if(floatval($ff2) > 0){
                        echo '<div>'.$ff2.'</div>';
                    } else {
                        echo '<div>0</div>';
                    }
                    if($jumlahOnPKG>0){
                        echo "<a href='".base_url('stok-onpkg/'.sha1($chto))."' style='text-decoration:none;'>";
                        echo '<div style="background:#ccc;color:red;padding:3px 0;font-size:13px;">'.$jumlahOnPKG2.'</div>';
                        echo '</a>';
                    } else {
                        echo '<div style="background:#ccc;color:red;padding:3px 0;font-size:13px;">-</div>';
                    }
                    echo '</div>';
                }
                
            }
        }
        
  } //end

  function show_agf(){
        $agf_total = $this->db->query("SELECT SUM(ukuran) AS ukr FROM new_roll_onpst")->row("ukr");
        $agf_samatex = $this->db->query("SELECT SUM(ukuran) AS ukr FROM new_roll_onpst WHERE kode_roll LIKE 'S%' ")->row("ukr");
        $agf_rjs = $this->db->query("SELECT SUM(ukuran) AS ukr FROM new_roll_onpst  WHERE kode_roll LIKE 'R%'")->row("ukr");

        if(fmod($agf_total, 1) !== 0.00){
            $psn1 = number_format($agf_total,2,',','.');
        } else {
            $psn1 = number_format($agf_total,0,',','.');
        }
        if(fmod($agf_samatex, 1) !== 0.00){
            $psn2 = number_format($agf_samatex,2,',','.');
        } else {
            $psn2 = number_format($agf_samatex,0,',','.');
        }
        if(fmod($agf_rjs, 1) !== 0.00){
            $psn3 = number_format($agf_rjs,2,',','.');
        } else {
            $psn3 = number_format($agf_rjs,0,',','.');
        }
        
        echo json_encode(array("statusCode"=>200, "psn_total"=>$psn1, "psn_smt"=>$psn2, "psn_rjs"=>$psn3));
  } //edn 

  function loaddatastokonpusatex(){
        $alldata = $this->db->query("SELECT * FROM new_roll_onpst");
        if($alldata->num_rows() == 0){
            echo "Tidak Ada Kain di Pusatex";
        } else {
            $kons = array();       
            foreach($alldata->result() as $val){
                if(in_array($val->kons, $kons)){

                } else {
                    $kons[]=$val->kons;
                }
            }
            sort($kons);
            foreach($kons as $kons2){
                    $stok_rjs = $this->db->query("SELECT SUM(ukuran) AS ukr FROM new_roll_onpst WHERE kode_roll LIKE 'R%' AND kons='$kons2' ")->row("ukr");
                    if(fmod($stok_rjs, 1) !== 0.00){
                        $pjg_rjs = number_format($stok_rjs,2,',','.');
                    } else {
                        $pjg_rjs = number_format($stok_rjs,0,',','.');
                    }

                    
                    $stok_smt = $this->db->query("SELECT SUM(ukuran) AS ukr FROM new_roll_onpst WHERE kode_roll LIKE 'S%' AND kons='$kons2' ")->row("ukr");
                    if(fmod($stok_smt, 1) !== 0.00){
                        $pjg_smt = number_format($stok_smt,2,',','.');
                    } else {
                        $pjg_smt = number_format($stok_smt,0,',','.');
                    }
                    
                    $total_panjang = floatval($stok_rjs) + floatval($stok_smt);
                    if(fmod($total_panjang, 1) !== 0.00){
                        $total_panjang2 = number_format($total_panjang,2,',','.');
                    } else {
                        $total_panjang2 = number_format($total_panjang,0,',','.');
                    }
                    ?>
                    <div class="card-awal blue">
                        <div class="items" style="color:#000;"><?=$kons2;?> : </div>
                        <div class="items-nilai" id="agf_total">
                            <?=$total_panjang2;?>
                        </div>
                        <div class="items-dobel">
                            <div class="dobel grey" id="agf_samatex">
                               Samatex : <?=$pjg_smt;?>
                            </div>
                            <div class="dobel grey" id="agf_rjs">
                                RJS : <?=$pjg_rjs;?>
                            </div>
                        </div>
                    </div>
                    <?php
                    
            }
        }
  } //end

  function loadkirimanbarang(){
        $tgl = $this->input->post('jns');
        $kiriman = $this->db->query("SELECT * FROM new_tb_pkgkepst WHERE tanggal='$tgl' ORDER BY id_auto24 DESC");
        if($kiriman->num_rows() >0 ){
            echo "<tr>";
            echo "<td><strong>Dari</strong></td>";
            echo "<td><strong>Kirim ke</strong></td>";
            echo "<td><strong>Konstruksi</strong></td>";
            echo "<td><strong>Panjang</strong></td>";
            echo "<td><strong>Roll</strong></td>";
            echo "</tr>";
            foreach($kiriman->result() as $bal){
                $kd = $bal->kode_pkg;
                $konst = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kd])->row("kode_konstruksi");
                echo "<tr>";
                echo "<td>".$bal->from_pabrik."</td>";
                echo "<td>".$bal->to_pabrik."</td>";
                echo "<td>".$konst."</td>";
                echo "<td>".number_format($bal->total_panjang)."</td>";
                echo "<td>".$bal->jml_roll."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td>Belum ada kiriman Kain</td></tr>";
        }
  }

}
?>
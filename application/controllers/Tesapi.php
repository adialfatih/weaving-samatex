<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tesapi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // Load database
        header('Content-Type: application/json'); // Set response dalam format JSON
    }

    public function getSavedCodes()
    {
        //$query = $this->db->select('kode_roll')->from('data_fol')->get();
        $query = $this->db->select('kode_roll')
                          ->from('data_fol')
                          ->where('tgl >=', '2024-12-30') // Filter data dari tanggal 30 Desember 2024 ke atas
                          ->get();
        $result = $query->result_array();

        if (!empty($result)) {
            echo json_encode([
                "status" => true,
                "jumlahData" => count($result),
                "data" => $result
            ]);
        } else {
            echo json_encode([
                "status" => false,
                "jumlahData" => 0,
                "data" => []
            ]);
        }
    }
}

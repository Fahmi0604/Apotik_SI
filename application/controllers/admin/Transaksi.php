<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("m_transaksi");
        $this->load->library('form_validation');
    
        if ($this->session->userdata('level') != "admin") {
            
            redirect('login','refresh');
            
        }
    }

    public function index()
    {
        $data["transaksi"] = $this->m_transaksi->getAll();
        $this->load->view("admin/transaksi/list_transaksi", $data);
    }
   
}

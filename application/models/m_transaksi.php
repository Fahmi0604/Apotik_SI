<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_transaksi extends CI_Model
{
    private $_table = "transaksi";

    public function getAll()
    {
        return $this->db->get($this->_table)->result();
    }
    
}

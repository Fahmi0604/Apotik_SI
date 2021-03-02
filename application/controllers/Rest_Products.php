<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Rest_Products extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    //Menampilkan data kontak
    function index_get() {
        $id = $this->get('product_id');
        if ($id == '') {
            $obat = $this->db->get('products')->result();
        } else {
            $this->db->where('product_id', $id);
            $obat = $this->db->get('products')->result();
        }
        $this->response($obat, 200);
    }

    //Mengirim atau menambah data kontak baru
    function index_post() {
        $data = array(
                    'product_id'   => $this->post('product_id'),
                    'name'   => $this->post('name'),
                    'price'    => $this->post('price'),
                    'description' => $this->post('description'));
        $insert = $this->db->insert('products', $data);
        if ($insert) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

       //Memperbarui data kontak yang telah ada
       function index_put() {
        $id = $this->put('product_id');
        $data = array(
            'product_id'   => $this->put('product_id'),
            'name'   => $this->put('name'),
            'price'    => $this->put('price'),
            'description' => $this->put('description'));
        $this->db->where('product_id', $id);
        $update = $this->db->update('products', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    //Masukan function selanjutnya disini

        //Menghapus salah satu data kontak
        function index_delete() {
            $id = $this->delete('product_id');
            $this->db->where('product_id', $id);
            $delete = $this->db->delete('products');
            if ($delete) {
                $this->response(array('status' => 'success'), 201);
            } else {
                $this->response(array('status' => 'fail', 502));
            }
        }
}
?>
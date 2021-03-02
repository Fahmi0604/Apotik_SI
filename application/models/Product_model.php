<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class Product_model extends CI_Model
{
    private $_client;
    private $_table = "products";

    public $product_id;
    public $name;
    public $price;
    public $image = "default.jpg";
    public $description;

    
    public function __construct()
    {
        $this->_client = new Client([
            'base_uri' => 'http://localhost/_Apotik-rest-server/api/',
            'auth' => ['admin', '1234']
        ]);   
    }
    

    public function rules()
    {
        return [
            ['field' => 'name',
            'label' => 'Name',
            'rules' => 'required'],

            ['field' => 'price',
            'label' => 'Price',
            'rules' => 'numeric'],
            
            ['field' => 'description',
            'label' => 'Description',
            'rules' => 'required']
        ];
    }

    function cekId($table,$where){		
		return $this->db->get_where($table,$where);
	}

    public function buatId(Type $var = null)
    {
        
        
        $kodejadi = '';
        

        do {
            $kode = rand(1000,9999);
            $cek = $this->db->get_where('products',$kode);
            $count = $cek->num_rows();
            $kodejadi = '6040'.$kode;
        } while ($count > 0);

        return $kodejadi;
    }

    public function getAll()
    {
        // return $this->db->get($this->_table)->result();

        $response = $this->_client->request('GET', 'Apotik', [
            'query' => [
                'APOTIK-API-KEY' => 'Apotik123'
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result['data'];
    }
    
    public function getById($id)
    {
        // return $this->db->get_where($this->_table, ["product_id" => $id])->row();

        $response = $this->_client->request('GET', 'Apotik', [
            'query' => [
                'APOTIK-API-KEY' => 'Apotik123',
                'id' => $id
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result['data'][0];
    }

    public function delete($id)
    {
        $this->_deleteImage($id);
        // return $this->db->delete($this->_table, array("product_id" => $id));

        $response = $this->_client->request('delete', 'Apotik', [
            'form_params' => [
                'id' => $id,
                'APOTIK-API-KEY' => 'Apotik123'
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result;

    }

    public function save()
    {
        // $post = $this->input->post();
        // $this->product_id = $this->buatId();
        // $this->name = $post["name"];
        // $this->price = $post["price"];
        // $this->image = $this->_uploadImage();
        // $this->description = $post["description"];
        // $this->db->insert($this->_table, $this);

        $data = [
            'product_id' => $this->buatId(),
            'name' => $this->input->post('name', true),
            'price' => $this->input->post('price', true),
            'image' => $this->_uploadImage(),
            'description' => $this->input->post('description', true),
            'APOTIK-API-KEY' => 'Apotik123'
        ];
        
        $response = $this->_client->request('POST', 'Apotik', [
            'form_params' => $data
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result;
    }

    public function update()
    {
        $post = $this->input->post();
        // $this->product_id = $post["id"];
        // $this->name = $post["name"];
        // $this->price = $post["price"];

        // if (!empty($_FILES["image"]["name"])) {
        //     $this->image = $this->_uploadImage();
        // } else {
        //     $this->image = $post["old_image"];
        // }

        // $this->description = $post["description"];
        // $this->db->update($this->_table, $this, array('product_id' => $post['id']));

        $data = [
            'product_id' => $this->input->post('id', true),
            'name' => $this->input->post('name', true),
            'price' => $this->input->post('price', true),
            'image' => $this->_uploadImage(),
            'description' => $this->input->post('description', true),
            'APOTIK-API-KEY' => 'Apotik123'   
        ];

        $response = $this->_client->request('PUT', 'Apotik', [
            'form_params' => $data
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result;
    }

    private function _uploadImage()
    {
    $config['upload_path']          = './upload/product/';
    $config['allowed_types']        = 'gif|jpg|png';
    $config['file_name']            = $this->product_id;
    $config['overwrite']			= true;
    $config['max_size']             = 1024; // 1MB
    // $config['max_width']            = 1024;
    // $config['max_height']           = 768;

    $this->load->library('upload', $config);

    if ($this->upload->do_upload('image')) {
        return $this->upload->data("file_name");
    }
    
    return "default.jpg";
    }

    private function _deleteImage($id)
    {
        $product = $this->getById($id);
        if ($product->image != "default.jpg") {
	        $filename = explode(".", $product->image)[0];
		    return array_map('unlink', glob(FCPATH."upload/product/$filename.*"));
        }
    }
}

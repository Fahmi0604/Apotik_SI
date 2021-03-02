<?php defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class m_pasien extends CI_Model
{
    private $_table = "pasien";

    public $id_pasien;
    public $nama_pasien;
    public $umur;
    public $image = "default1.jpg";
    public $alamat;

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
            $cek = $this->db->get_where('pasien',$kode);
            $count = $cek->num_rows();
            $kodejadi = '0343'.$kode;
        } while ($count > 0);

        return $kodejadi;
    }

    public function getAll()
    {
        return $this->db->get($this->_table)->result();
        // $client = new Client();
        // $response = $client->request('GET', 'http://localhost:8080/api/pasien');

        // $result = json_decode((string) $response->getBody(), true);

        // return $result;
    }
    
    public function getById($id)
    {
        return $this->db->get_where($this->_table, ["id_pasien" => $id])->row();
    }

    public function save()
    {
        $post = $this->input->post();
        $this->id_pasien = $this->buatId();
        $this->nama_pasien = $post["name"];
        $this->umur = $post["price"];
        $this->image = $this->_uploadImage();
        $this->alamat = $post["description"];
        $this->db->insert($this->_table, $this);
    }

    public function update()
    {
        $post = $this->input->post();
        $this->id_pasien = $post["id"];
        $this->nama_pasien = $post["name"];
        $this->umur = $post["price"];

        if (!empty($_FILES["image"]["name"])) {
            $this->image = $this->_uploadImage();
        } else {
            $this->image = $post["old_image"];
        }

        $this->alamat = $post["description"];
        $this->db->update($this->_table, $this, array('id_pasien' => $post['id']));
    }

    public function delete($id)
    {
        $this->_deleteImage($id);
        return $this->db->delete($this->_table, array("id_pasien" => $id));
    }

    private function _uploadImage()
    {
    $config['upload_path']          = './upload/pasien/';
    $config['allowed_types']        = 'gif|jpg|png';
    $config['file_name']            = $this->id_pasien;
    $config['overwrite']			= true;
    $config['max_size']             = 1024; // 1MB
    // $config['max_width']            = 1024;
    // $config['max_height']           = 768;

    $this->load->library('upload', $config);

    if ($this->upload->do_upload('image')) {
        return $this->upload->data("file_name");
    }
    
    return "default1.jpg";
    }

    private function _deleteImage($id)
    {
        $pasien = $this->getById($id);
        if ($pasien->image != "default1.jpg") {
	        $filename = explode(".", $pasien->image)[0];
		    return array_map('unlink', glob(FCPATH."upload/pasien/$filename.*"));
        }
    }
}

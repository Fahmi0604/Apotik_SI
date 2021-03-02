<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Overview extends CI_Controller {
    public function __construct()
    {
		parent::__construct();
		$this->load->model("m_pasien");
	}

	public function index()
	{
		// load view admin/overview.php
		$data["pasien"] = $this->m_pasien->getAll();
        $this->load->view("admin/overview",$data);
	}
}

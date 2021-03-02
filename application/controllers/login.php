<?php 

class Login extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('m_login');
		$this->load->library('form_validation');

	}

	function index(){
		$this->load->view('v_login');
	}

	public function proses_login(){

		$this->form_validation->set_rules('username','Username','trim|required');
		$this->form_validation->set_rules('password','Password','trim|required');

		if($this->form_validation->run() == FALSE)
		{
			$this->load->view('v_login');
		}
        
        $username=htmlspecialchars($this->input->post('username'));
        $password=htmlspecialchars($this->input->post('password'));
        
        $where = array(
			'username' => $username,
			'password' => md5($password)
			);
		$cek = $this->m_login->cek_login("admin",$where)->num_rows();

		if ($cek == 1) {
			$result = $this->m_login->cek_login("admin",$where)->result();
		}

		if ($result) {
            
            foreach ($result as $row);
        
            $this->session->set_userdata('user',$row->username);
			$this->session->set_userdata('level',$row->level);
			
            if ($this->session->userdata('level')=="admin") {
                
                redirect('admin/Overview');
        
            }elseif ($this->session->userdata('level')=="user") {
                
                redirect('user');
                
            }
        }else {
            
            $data['pesan']="username dan password anda salah";
			$data['title']='login';
			$this->session->set_flashdata('failed', 'username atau password anda salah');
            redirect(base_url('login'));
            
        }
		
        // if ($cek > 0) {
			
		// 	$data_session = array(
		// 		'nama' => $username,
		// 		'status' => "login"
		// 		);

		// 	$this->session->set_userdata($data_session);			
		// 	$this->session->set_userdata('level',$row->level);
			

		// 	redirect(base_url("admin/Overview"));


        // }else {
			
		// 	//$message = "Username dan Password anda salah";
        //     $data['pesan']="username dan password anda salah";
		// 	$data['title']='login';
		// 	$this->session->set_flashdata('failed', 'username atau password anda salah');
		// 	//$this->load->view('v_login');
			
        //     redirect(base_url('login'));
        //     //echo "<script type='text/javascript'>alert('$message');</script>";
        // }

    }

    public function logout(){
        
        $this->session->sess_destroy();
        redirect(base_url('login'));
    }
}
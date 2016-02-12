<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('client/login');
	}
	
	public function login()
	{
		$data = array();

		$this->load->helper('form');
		$this->load->library('form_validation');
		// Load session library
		$this->load->library('session');
		// Load database
		$this->load->model('client_login_db');

		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

		if ($this->form_validation->run() === FALSE)
		{
			if (isset($this->session->userdata['logged_in']))
			{
				echo "Success"; exit;
			}
			else
			{
				$this->load->view('client/login');
			}
		}
		else
		{
			$data = array(
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password')
			);
			
			$result = $this->client_login_db->login($data);

			if ($result == TRUE)
			{
				$username = $this->input->post('username');
				$result   = $this->client_login_db->read_user_information($username);
				if ($result != false)
				{
					$session_data = array(
						'username' => $result[0]->user_name,
						'email'    => $result[0]->user_email,
					);
					// Add user data in session
					$this->session->set_userdata('logged_in', $session_data);
					echo "Success"; exit;
				}
			}
			else
			{
				$data = array(
					'error_message' => 'Invalid Username or Password'
				);
			}
		}
		
		$this->load->view('client/login', $data);
	}
	
	public function logout()
	{
		$this->load->view('client/logout');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
<?php

class Authentication extends CI_Controller {
 	
	public $user_data;

 	function __construct(){
    	parent::__construct();

    	$this->load->library('session_handler');
    	// load session library for flashdata
    	$this->load->library('session');
    }

	public function index()
	{
		return;
	}
    		
	public function login(){
		$this->load->library('session_handler');
		$this->load->library('session');

		try{
	    	$sid = $this->session_handler->verify_session();
	    	if($sid){
		    	if($this->session_handler->is_admin($sid))
		    		redirect('/admin/users');
		    	else
		    		redirect('/user/expenses');
	    	}
		} catch(Exception $e){}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="status">', '</div>');

		$this->load->model('authentication_model', '',TRUE);

		$this->form_validation->set_rules('email', 'email', 'trim|required|xss_clean|callback_verify_user_exists|callback_verify_user_status');

		// $this->form_validation->set_message('verify_user_registered', 'You have not completed the registration process. Please check your email for further instructions or contact the admin.');

		if($this->form_validation->run() == FALSE){
			$this->load->view('templates/main', array('view' => 'auth/login'));
		}
		else{
			$this->form_validation->set_rules('password', 'password', 'callback_authenticate_password');
			
			
			if($this->form_validation->run() == FALSE){
				$this->load->view('templates/main', array('view' => 'auth/login'));
			}
			else{
				$this->session_handler->create_session($this->user_data->id);
				// Route the user to the appropriate dashboard/page
				if($this->user_data->is_admin){
					redirect('/admin/rentals');
				}else{
					redirect('/user/expenses');
				}
			}
			
		}
	}

	public function verify_user_exists($email){
		try{
			$this->user_data = $this->authentication_model->get_user_auth_data($email);
		} catch(RecoverableException $e){
			$this->form_validation->set_message('verify_user_exists', 'Authentication failed: incorrect email or password');
			return false;
		}
		return true;
	}

	public function verify_user_status(){
		if($this->user_data->frozen){
			$this->form_validation->set_message('verify_user_status', 'Your account has been frozen.');
			return false;
		}
		if($this->user_data->status != "registered"){
			$this->form_validation->set_message('verify_user_status', 'You have not completed the registration process. Please check your email for further instructions or contact the admin.');
			return false;
		}
		return true;
	}

	public function authenticate_password($password){
		if(password_verify($password, $this->user_data->password)){
			return true;
		}
		$this->form_validation->set_message('authenticate_password', 'Authentication failed: incorrect email or password');
		return false;

	}

	public function freeze_user(){
		// json_decode(_POST[""])
		// go to database and freeze user
		// return success status
	}

	public function register($hash){

		$this->load->model('authentication_model', '',TRUE);
		$this->load->library('session');

		$cleaned_hash = $this->security->xss_clean($hash);

		// Verify that the hash in the url is valid
		try{
			$user_id = $this->authentication_model->verify_hash($hash);
		} catch(RecoverableException $e){
			$this->session->set_flashdata("status", $e->getMessage());
    		redirect('/authentication/login');
		}

		$user_info = $this->authentication_model->get_user_info($user_id);

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('password', 'password', 'trim|required|xss_clean|matches[passwordconf]|min_length[8]');
		$this->form_validation->set_rules('passwordconf', 'password confirmation', 'trim|required|xss_clean');

		if($this->form_validation->run() == FALSE){
			$data['hash'] = $hash;
			$this->load->view('auth/register', $data);
		}
		else{
			$password = $this->input->post('password');
			try{
				$this->authentication_model->update_password($user_info->email, $password);
        		$this->authentication_model->delete_registration_hash($user_info->id);
			} catch(RecoverableException $e){
				echo $e->getMessage();
			}

			$this->session_handler->create_session($user_info->id);
			redirect('/user/expenses');
		}
	}

	public function process_registration(){

	}

	public function update_email(){

	}

	public function update_password(){

	}

	public function logout(){
    	$this->load->model('authentication_model', '',TRUE);

    	if(isset($_COOKIE['session'])){
    		$this->load->library('session');
    		$sid = $_COOKIE['session'];
    		$this->authentication_model->delete_session($sid);
    		$this->session->set_flashdata("status", "You have successfully logged out.");
    		unset($_COOKIE['session']);
    	}
    	redirect('/authentication/login');
	}

}

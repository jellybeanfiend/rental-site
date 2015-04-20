<?php

class Admin extends CI_Controller {

	private $user_id;

	function __construct(){
    	parent::__construct();

    	$this->load->library('session_handler');
    	$this->load->library('session');
    	$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('admin_model','', TRUE);

    	try{
    		$this->user_id = $this->session_handler->verify_session();
    	} catch(InvalidSessionException $e){
    		redirect('/authentication/login');
    	} catch(SessionExpiredException $e){
    		// TODO: This is wrong, should be in sessions table. Fix it.
    		$this->session->set_flashdata("status", "Your session has timed out.");
    		redirect('/authentication/login');
    	}
    	if(!$this->session_handler->is_admin($this->user_id)){
    		redirect('/user/rentals/');
    	}
    }

    public function index(){
    	echo "Hi, admin!";
    }

	// Rentals

    public function get_rental($id){
		echo json_encode($this->admin_model->get_rental($id));
	}

	public function rentals(){
		$rentals = $this->admin_model->get_rentals();
		$categories = array(
			"luxury" => "Luxury Villas",
			"vacation" => "Vacation Homes",
			"casitas" => "Casitas & Cabañas",
			"boutique" => "Boutique Lodging",
			"hotels" => "Hotels");
		
		foreach($categories as $key => $category){
			$categorized[$key] = array();
		}
		foreach($rentals as $rental){
			$categorized[$rental->category][] = $rental;
		}
		$data['rentals'] = $categorized;
		$data['categories'] = $categories;
		$data['admin'] = 1;
		$data['view'] = 'admin/rentals';
		$this->load->view("templates/dash_template", $data);
	}

    public function process_rental(){
    	$type = $this->input->post('type');

    	$name = $this->input->post('name');
    	$category = $this->input->post('category');
    	$path = $this->config->item('uploads')."rentals/$category/$name/";
    	$images = $this->input->post('imgs');
    	$images_arr = explode(",", $images);
    	$thumbnail = $this->input->post('main-img');
 
    	$data = array(
				'category' => $category,
				'name' => $name,
				'description' => $this->input->post('description'),
				'numBedrooms' => $this->input->post('numBedrooms'),
				'numBathrooms' => $this->input->post('numBathrooms'),
				'startingPrice' => $this->input->post('startingPrice'),
				'policies' => $this->input->post('policies'),
				'pricePer' => $this->input->post('pricePer'),
				'amenities' => $this->input->post('amenities'),
				'bedrooms' => $this->input->post('bedrooms'),
				'services' => $this->input->post('amenities'),
				'rates' => $this->input->post('rates'),
				'thumbnail' => $thumbnail == "" ? $images_arr[0] : $thumbnail,
				'img_path' => base_url().$path,
				'images' => $images
			);

    	// remove images no longer in 'images' list
    	// don't upload images in $_FILES that aren't in 'images'

    	if(!empty($_FILES)){
    		$this->upload_rental_imgs($path, $images);
    	}

    	if($type == 'add')
			$data['id'] = $this->admin_model->add_rental($data);
		if($type == 'edit'){
			$id = $this->input->post('id');
			$data['id'] = $id;
			$old_img_list = $this->admin_model->get_img_list($id);
			$this->delete_images($path, $old_img_list, $images);
			$this->admin_model->edit_rental($data);
		}

		redirect("/admin/rentals");

    }

    function delete_rental($id){
    	$rental_data = $this->admin_model->delete_rental($id);
    	$this->load->helper('file');
    	$path = $this->get_relative_path($rental_data->img_path);
    	delete_files($path);
    	rmdir($path);
    }

    function delete_images($http_path, $old, $new){
    	$path = $this->get_relative_path($http_path);
    	$old = explode(",", $old);
    	$new = explode(",", $new);
    	$deleted = array_diff($old, $new);
    	$this->load->helper('file');
    	foreach($deleted as $img){
    		unlink($path.$img);
    	}
    }

    function upload_rental_imgs($path, $images){
		if(!is_dir($path))
			mkdir($path, 0777, true);

		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|png';
		$this->load->library('upload', $config);


		// INSERT CODE TO AVOID UPLOADING UNWANTED IMAGES
		$images = explode(",", $images);
		

		$files = $_FILES;
		$cnt = count($_FILES['userfile']['name']);
		for($i = 1; $i < $cnt; $i++){

			if(in_array($files['userfile']['name'][$i], $images)){
				$_FILES['userfile']['name'] = $files['userfile']['name'][$i];
				$_FILES['userfile']['type'] = $files['userfile']['type'][$i];
				$_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
				$_FILES['userfile']['error'] = $files['userfile']['error'][$i];
				$_FILES['userfile']['size'] = $files['userfile']['size'][$i];

				$this->upload->do_upload();
				log_message('error',print_r($this->upload->display_errors(),TRUE));
			}

		}
	}

    function get_relative_path($path){
    	return $this->config->item('uploads').explode($this->config->item('uploads'), $path)[1];
    }

    // Users

	public function add_pending_user(){
		$this->load->database();

		$this->form_validation->set_message('is_unique', 'This email is already in use.');
		$this->form_validation->set_rules('email', 'email', 'trim|required|xss_clean|is_unique[users.email]');
		$this->form_validation->set_rules('name', 'name', 'trim|required|xss_clean');

		if($this->form_validation->run() == FALSE){
			echo json_encode(array('error' => validation_errors()));
		}
		else{

			$this->load->model('authentication_model', '',TRUE);
			$email = $this->input->post('email');
			$name = $this->input->post('name');

			// Add the new user to users database and generate unique hash for registration url
			try{
				$data = $this->authentication_model->add_pending_user($email, $name);
				$this->send_email($name, $email, $data['hash']);
				echo json_encode(array('user_id'=>$data['user_id'], 'name' => $name, 'email' => $email));
			} catch(Recoverable_exception $e){
				echo json_encode(array('error' => $e->getMessage()));
			}

		}
	}

	public function pending_users(){
    	$this->load->model('admin_model', '',TRUE);
		$data['users'] = $this->admin_model->get_pending_users();
		$data['view'] = 'admin/pending_users';
		$data['admin'] = 1;
		$this->load->view('templates/dash_template', $data);
	}

	public function users(){
    	$this->load->helper('url');
    	$this->load->model('admin_model', '',TRUE);
    	$data['users'] = $this->admin_model->get_users();
    	$data['admin'] = 1;
    	$data['view'] = 'admin/users';
    	$this->load->view("templates/dash_template", $data);
	}

	public function update_table($id){
        $this->load->library('users');
        $this->users->update_table($id);
    }

	public function reset_registration($user_id){
		$this->load->helper(array('form'));
		$this->load->library('form_validation');

		$this->load->model('authentication_model', '',TRUE);
		$this->load->model('user_model', '',TRUE);
		try{
			$hash = $this->authentication_model->update_registration_hash($user_id);
			$info = $this->user_model->get_info($user_id);
		} catch(Recoverable_exception $e){
			var_dump($e->getMessage());
		}
		$this->send_email($info->name, $info->email, $hash);
	}

	public function send_email($name, $email, $hash){

		$this->load->library('email');
		$config['mailtype'] = 'html';
		$this->email->initialize($config);

		$url = site_url().'authentication/register/'.$hash;
		$this->email->from('support@site.com', 'site');
		$this->email->to($email);
		
		$this->email->set_alt_message('An account has been created for you at site.com.\n Go to '.$url.' to confirm your account registration.\n\nHave a wonderful day!\n-The site team');

		$this->email->subject('Registration Confirmation');
		$this->email->message('<div><img src="http://www.site.com/assets/images/logo_med.png" /></div><p style="color:rgb(27,117,187)">An account has been created for you at <a href="http://www.site.com/">site.com</a>.</p><p style="color:rgb(27,117,187)">Click <a href="'.$url.'">here</a> to confirm your account registration.</p><p style="color:rgb(27,117,187)">Have a wonderful day!<br />-The site team</p>');	

		$this->email->send();
	}

	public function expenses($id){
		$this->load->library('users');
    	$this->load->model('admin_model', '',TRUE);
		$data['users'] = $this->admin_model->get_users();
		$this->users->display_expenses($id, true, $data);
    }

    public function profile($id){
    	$this->load->library('users');
    	$this->load->model('admin_model', '',TRUE);
		$data['users'] = $this->admin_model->get_users();
    	$this->users->display_profile($id, true,$data);
    }

    public function dashboard(){
    	$this->load->helper('url');
    	$this->load->model('admin_model', '',TRUE);
    	$data['users'] = $this->admin_model->get_users();
    	$data['admin'] = 1;
    	$data['view'] = 'admin/dashboard';
    	$this->load->view("templates/dash_template", $data);
    }

    public function toggle_freeze($id){
    	$this->load->model('admin_model', '', TRUE);
    	$result = $this->admin_model->toggle_freeze($id);
    	echo json_encode($result);
    }

    // Expenses

	public function process_expense(){
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('admin_model', '',TRUE);
		$this->load->model('user_model', '',TRUE);

		$this->form_validation->set_rules('user', 'Client', 'required');
		$this->form_validation->set_rules('date', 'Date', 'required');
		$this->form_validation->set_rules('category', 'Category', 'xss_clean');
		$this->form_validation->set_rules('description', 'Expense Description', 'required|xss_clean');
		$this->form_validation->set_rules('amount', 'Amount', 'required|xss_clean');
		$this->form_validation->set_rules('currency', 'Currency', 'required');

		if($this->form_validation->run() == FALSE){
			echo json_encode(array('error'=>validation_errors()));
			die();
		}
		else{
			$type = $this->input->post('type');
			$currency = $this->input->post('currency');
			$amount = $this->input->post('amount');

			$usd = ($currency == 'usd') ? $amount : $this->convert_currency("MXN/USD", $amount);
			$mxn = ($currency == 'mxn') ? $amount : $this->convert_currency("USD/MXN", $amount);

			$data = array(
				'user_id' => $this->input->post('user'),
				'date' => $this->input->post('date'),
				'description' => $this->input->post('description'),
				'tags' => $this->input->post('tags'),
				'amt_mxn' => $mxn,
				'amt_usd' => $usd,
				'category' => $this->input->post('category'),
			);

			if(!empty($_FILES)){
				$path = $this->upload_receipt($data['user_id'],$data['date']);
				if($path)
					$data['receipt_image'] = $path;
			}
			if($type == 'add')
				$data['id'] = $this->admin_model->add_expense($data);
			else{
				$data['id'] = $this->input->post('id');
				$this->admin_model->edit_expense($data);
			}

			$data['date'] = $this->input->post('date-display');

			echo json_encode($data);
		}
		if(!empty($_FILES))
			redirect("/user/expenses/".$this->input->post('user'));
	}

	public function delete_expense(){
		$this->load->model('admin_model', '',TRUE);
		$this->admin_model->delete_expense($this->input->post('id'));
	}

	private function convert_currency($conversion, $amount){
		$url = 'http://currency-api.appspot.com/api/'.$conversion.'.json?key='.$this->config->item('currency_apikey').'&amount='.$amount;
		$result = file_get_contents($url);
		$result = json_decode($result);
		return $result->amount;
	}


	// Receipts

	function update_receipt(){
		if(!empty($_FILES)){
			$expense_id = $this->input->post('id');
			$this->load->model('user_model','',TRUE);
	        $expense_info = $this->user_model->get_expense($expense_id);
	        $path = $this->upload_receipt($expense_info->user_id, $expense_info->date);
	        if($path){
				$this->load->model('admin_model','',TRUE);
	        	$this->admin_model->update_receipt($expense_id, $path);
	        }
		}
		redirect("/user/expenses/$expense_info->user_id");
	}

	function upload_receipt($user_id,$date){
		$date = explode("-",$date);
		$month = ltrim($date[1], '0');
		$year = $date[0];

		$path = "receipts/$user_id/$year/$month/";
		$fullpath = $this->config->item('uploads').$path;

		if(!is_dir($fullpath))
			mkdir($fullpath,0777,true);

		$config['upload_path'] = $fullpath;
		$config['allowed_types'] = 'gif|jpg|png';

		$this->load->library('upload', $config);

		if ($this->upload->do_upload())
		{
			return $path.$_FILES['userfile']['name'];
		}
		else
		{
			return null;
			// return true;
			// $data = array('upload_data' => $this->upload->data());
		}
	}

	// Filters

	public function filters(){
    	$this->load->model('user_model', '',TRUE);
		$data['categories'] = $this->user_model->get_filter("category");
		$data['tags'] = $this->user_model->get_filter("tag");
		$data['admin'] = 1;
		$data['view'] = 'admin/filters';
		$this->load->view("templates/dash_template", $data);
	}

	public function delete_filter(){
		$inputs = $this->input->post();
		$this->load->model('admin_model', '', TRUE);
		$this->admin_model->delete_filter($inputs['type'],$inputs['text']);
	}

	public function add_filter(){
		$inputs = $this->input->post();
		$this->load->model('admin_model', '', TRUE);
		$this->admin_model->add_filter($inputs['type'],$inputs['text']);
	}

	// Contract

	public function delete_contract($id){
		$this->load->model('admin_model','',TRUE);
		$contract = $this->admin_model->delete_contract($id);
		unlink($this->config->item('uploads')."contracts/$contract");
	}

	public function upload_contract($id){
		$image = $this->input->post('contract');

		$config['upload_path'] = $this->config->item('uploads').'contracts/';
		$config['allowed_types'] = 'gif|jpg|png';


		$this->load->library('upload', $config);

		if ($this->upload->do_upload())
		{
			$this->load->model('admin_model', '',TRUE);
			$this->admin_model->update_contract($id, $_FILES["userfile"]["name"]);
			redirect("/user/contract/$id");
		}
		else
		{
			// var_dump($this->upload->display_errors());
		}
		redirect("/user/contract/$id");
	}

	public function rental_test(){
		$this->load->view('modals/add_rental');
	}

}


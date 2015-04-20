<?php

class User extends CI_Controller {

	private $user_id;
    private $is_admin;

	function __construct(){
    	parent::__construct();
    		$this->load->library('session');
	    	$this->load->library('session_handler');
            $this->load->model('user_model', '',TRUE);

	    	// $headers = apache_request_headers();
	    	// $is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');
	    	
	    	try{
	    		$this->user_id = $this->session_handler->verify_session();
	    	} catch(InvalidSessionException $e){
	    		// echo $e->getMessage();
				// if($is_ajax){
	               // http_response_code(401);
	               // die();
		    	// }
	    		redirect('/authentication/login');
	    	} catch(SessionExpiredException $e){
                // echo $e->getMessage();
				// if($is_ajax){
                    // http_response_code(401);
                    // die();
		        // }
	    		$this->session->set_flashdata("status", "Your session has timed out.");
	    		redirect('/authentication/login');
	    	}
            $this->is_admin = $this->session_handler->is_admin($this->user_id);
    }

    public function expenses($id = null){
        // $this->load->library('users');
        // $this->users->display_expenses($this->user_id);
        $user_id = ($this->is_admin && $id) ? $id : $this->user_id;
        $this->load->model('user_model', '',TRUE);
        $month = date('m');
        $year = date('Y');
        $minyear = explode("-", $this->user_model->get_min_year($user_id))[0];
        $data['expenses'] = $this->user_model->get_expenses($user_id,$month,$year);
        $data['months'] = cal_info(0)['months'];
        $data['years'] = range($minyear,date("Y"));
        $data['current_month'] = intval(date('n'));
        $data['current_year'] = intval(date('Y'));
        $data['categories'] = $this->user_model->get_filter('category');
        $data['tags'] = $this->user_model->get_filter('tag');
        $data['page_title'] = "Expense Report";
        $data['current_user'] = $this->user_model->get_user($user_id);
        $data['admin'] = $this->is_admin;
        $data['view'] = 'user/expenses';
        $this->load->view("templates/dash_template", $data);
    }

    public function update_table($id = null){
        $user_id = $this->is_admin ? $id : $this->user_id;
        $this->load->model('user_model', '',TRUE);
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $result['expenses'] = $this->user_model->get_expenses($user_id,$month,$year);
        $result['admin'] = $this->is_admin;
            
        echo json_encode($result);
    }

    public function contract($id = null){
        $user_id = $this->is_admin ? $id : $this->user_id;
        $this->load->model('user_model', '',TRUE);
        $contract = $this->user_model->get_contract($user_id);
        $data['contract'] = $contract ? true : false;
        $data['view'] = 'user/contract';
        $data['id'] = $user_id;
        $data['admin'] = $this->is_admin;
        $data['page_title'] = "Contract";
        $data['current_user'] = $this->user_model->get_user($user_id);
        $data['contract_url'] = "/user/retrieve_contract/$user_id";
        $this->load->view('templates/dash_template', $data);
    }

    public function retrieve_contract($id){
        $user_id = $this->is_admin ? $id : $this->user_id;
        $this->load->model('user_model', '',TRUE);
        $contract = $this->user_model->get_contract($user_id);
        $file = $this->config->item('uploads')."contracts/$contract";
        $type = array_pop(explode(".",$contract));
        header("Content-Type: image/$type");
        readfile($file);
    }

    public function view_receipt($expense_id){
        $this->load->model('user_model','',TRUE);
        $expense_info = $this->user_model->get_expense($expense_id);
        if($expense_info && isset($expense_info->receipt_image)){
            if(($expense_info->user_id == $this->user_id || $this->is_admin)){

                $path = $this->config->item('uploads').$expense_info->receipt_image;
                $type = array_pop(explode(".",$path));
                header("Content-Type: image/$type");
                readfile($path);
            }
        }
        
    }

    public function zip_expenses(){
    	$this->load->model('user_model', '',TRUE);
    	$input = $this->input->post();
    	switch($input['when']){
    		case "current":
    			$month = $input['current-month'];
    			$year = $input['current-year'];
    			$result = $this->user_model->get_expenses($this->user_id, $month, $year);
    			break;
    		case "range":
    			$from_month = $input['from-month'];
    			$from_year = $input['from-year'];
    			$to_month = $input['to-month'];
    			$to_year = $input['to-year'];
    			$result = $this->user_model->get_range_dates($this->user_id, $from_month, $from_year, $to_month, $to_year);
    			break;
    		case "all":
    			$result = $this->user_model->get_all_user_expenses($this->user_id);
    			break;
    	}
    	if(isset($result)){
    		echo json_encode($result);
    	}
    }

    public function profile($id = null){
        $user_id = $this->is_admin ? $id : $this->user_id;
        $this->load->model('user_model', '',TRUE);
        $data['current_user'] = $this->user_model->get_user($user_id);
        $user_info = $this->user_model->get_profile($user_id);
        $data['user_info'] = $user_info;
        $data['page_title'] = $user_info->name."'s Profile";
        $data['admin'] = $this->is_admin;
        $data['view'] = 'user/profile';
        $this->load->view('templates/dash_template', $data);
    }

    // public function view_contract($id = null){
    //     $file = '../protected/uploads/';
    //     header('Content-Type: image/jpg');
    //     readfile($file);
    //     $this->load->view('upload_form');
    // }

    public function update_profile($id){
        if($id == $this->user_id  || $this->is_admin){
            $this->load->model('user_model', '',TRUE);
            $inputs = $this->input->post();
            if($inputs){
                $this->user_model->update_profile($id,$inputs);
            }
        }
    }
}
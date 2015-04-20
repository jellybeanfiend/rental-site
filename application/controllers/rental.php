<?php

class Rental extends CI_Controller {

	private $user_id;

	function __construct(){
    	parent::__construct();

    }

    public function index(){
    	echo "Hi, admin!";
    }

    public function luxury_villas(){
    	$this->load->model('admin_model', '', TRUE);
    	$data['rentals'] = $this->user_model->get_rentals("luxury_villas");
		$data['view'] = 'pages/'.$page;
		$this->load->view('templates/main');
    }

}

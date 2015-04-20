<?php

class Form extends CI_Controller {

	public function index()
	{
		$this->load->view('templates/header');
		$this->load->view('form');
		$this->load->view('templates/footer');
	}

}

<?php

class Pages extends CI_Controller {

	public function index(){
		$data['view'] = 'pages/home';
		$this->load->view('templates/main', $data);
	}

	public function about(){
		$data['view'] = 'pages/about';
		$this->load->view('templates/main', $data);
	}

	public function rentals($category = null){
		$categories = array(
			"luxury" => "Luxury Villas",
			"vacation" => "Vacation Homes",
			"casitas" => "Casitas & Cabañas",
			"boutique" => "Boutique Lodging",
			"hotels" => "Hotels");
		if($category == null || !array_key_exists($category, $categories))
			show_404();
		$this->load->model('admin_model', '', TRUE);
    	$data['rentals'] = $this->admin_model->get_rental_by_category($category);
    	$data['category'] = $categories[$category];
		$data['view'] = 'pages/rentals';
		$this->load->view('templates/main',$data);
	}

	public function process(){
		$str = "";
		$alt = "";
		foreach($_POST as $key => $value){
			if($value != ""){
				$str .= $key.": ".$value."<br />";
				$alt .= $key.": ".$value."\n";
			}
		}
		$this->load->library('email');
		$config['mailtype'] = 'html';
		$this->email->initialize($config);

		$this->email->from('support@site.com', 'site');
		$this->email->to("siteinfo@gmail.com");
		
		$this->email->set_alt_message($alt);

		$this->email->subject('Rental Reservation: '.$this->input->post("Rental")." - ".$this->input->post("Name"));
		$this->email->message('<div><img src="http://www.site.com/assets/images/logo_med.png" /></div><p style="color:rgb(27,117,187)">'.$str.'</p>');	

		$this->email->send();

	}

	public function rental($id){
		$this->load->model('admin_model', '', TRUE);
		$rental = $this->admin_model->get_rental($id);
		if(empty($rental))
			show_404();
		$rental->amenities = $this->display_bullets($rental->amenities);
		$rental->bedrooms = $this->display_bullets($rental->bedrooms);
		$rental->services = $this->display_bullets($rental->services);
		$rental->policies = $this->display_bullets($rental->policies);
		$rental->rates = $this->display_rates_table($rental->rates);
		$rental->images = explode(",", $rental->images);
		$data['rental'] = $rental;
		$data['view'] = 'pages/rental';
		$this->load->view('templates/main', $data);
	}

    function display_bullets($str){
    	// NOTE: Maybe store the results of these regular expressions in the database
    	// so they only need to be computed once (when Stephanie enters them for the first
    	// time).
    	if($str == "")
    		return $str;
    	$split = preg_split("/\n/", $str, -1, PREG_SPLIT_NO_EMPTY);
    	$list_elements = preg_replace("/^-(.*)\n?/", "<li class=\"list-group-item\">$1</li>", $split);
    	$headings = preg_replace("/^\*\*(.*)/", "<li class=\"list-group-item rental-head\">$1</li>", $list_elements);
    	$lists = implode("", $headings);

    	// $split = preg_split("/(\*\*.*\n)/", $str, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    	// $headings = preg_replace("/\*\*(.*)\n/", "<h3>$1</h3>", $split);
    	// $list_elements = preg_replace("/-(.*)\n?/", "<li class=\"list-group-item\">$1</li>", $headings);
    	// $lists = preg_replace("/(<li>.*<\/li>)/", "<ul class=\"list-group\">$1</ul>", $list_elements);

    	return $lists;

    }

    function display_rates_table($str){
    	if($str == "")
    		return $str;
    	$rows = explode("|", $str);
    	$html = "";
    	foreach($rows as $row){
    		$cols = explode(";", $row);
    		$html .= "<tr><td>".$cols[1]."<br /><small>".$cols[0]."</small></td><td>";
    		$html .= implode("</td><td>", array_slice($cols, 2)) . "</tr>";
    	}
    	return $html;
    }

}

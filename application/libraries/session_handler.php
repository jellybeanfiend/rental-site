<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Session_handler{

	function __construct(){

    }

    public function create_session($user_id){
    	$CI =& get_instance();
    	$CI->load->model('authentication_model', '',TRUE);

    	$sid = hash("sha256", mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
		$CI->authentication_model->add_session($sid, $user_id);

		// TODO: Change secure parameter to TRUE when we're running with ssl
		$res = setcookie('session', $sid, 0, "/", NULL, FALSE, TRUE);
		if($res == FALSE){
			// the cookie did not get set
			// TODO: Handle this case
		}
    }

    public function verify_session(){
    	$CI =& get_instance();
    	$CI->load->model('authentication_model', '',TRUE);

    	if(isset($_COOKIE['session']) === FALSE){
    		throw new InvalidSessionException("No session cookie");	
    	}

    	$sid = $_COOKIE['session'];
    	$session = $CI->authentication_model->get_session($sid);

    	// $time_since_last_activity = time() - strtotime($session->last_activity);
    	// $session_length = time() - strtotime($session->first_activity);
    	
    	// if($time_since_last_activity >= $CI->config->item('max_inactivity_period')){
    	// 	$CI->authentication_model->delete_session($sid);
    	// 	throw new SessionExpiredException("The user has been inactive for too long.");
    	// }
    	// if($session_length >= $CI->config->item('max_session_length')){
    	// 	$CI->authentication_model->delete_session($sid);
    	// 	throw new SessionExpiredException("The time of this session has exceeded the max time length");
    	// }

    	return $session->user_id;
    }

    public function get_uid(){
    }

    public function is_admin($uid){
        $CI =& get_instance();
        $CI->load->model('authentication_model', '',TRUE);
    	return $CI->authentication_model->is_admin($uid);
    }

	
}
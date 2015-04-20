<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authentication_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Step 1 of the registration process. This should be called by the admin
     * controller. Adds the user's email, name and admin permission to the users
     * table, but not the password. The password is set in the second step of
     * the registratio'jarl@whiterun.com'n process by update_password().
     *
     * Throws RecoverableException if the user already exists so the controller
     * can notify the admin.
     * 
     * @param string $email
     * @param string $name
     */
    function add_pending_user($email, $name){
    	// Make sure the user does not already exist in database
    	$query = $this->db->get_where('users', array('email' => $email));

    	if($query->num_rows() > 0){
    		throw new RecoverableException("A user already exists with this email.");
    	}

    	// Add the user
    	$data = array(
    		'email' => $email,
    		'is_admin' => 0,
    		'name' => $name
    	);
    	
    	$this->db->insert('users', $data);
        
        $user_id = $this->db->insert_id();

        return $this->add_registration_hash($user_id);
    }

    /**
     * Verify that the hash in the registration url is valid. This means that
     * the admin has added a pending user and this user is now completing the
     * registration process.
     *
     * If the hash is over 3 days old, it is no longer valid and is deleted.
     * 
     * @param  string $hash
     * @return int       Returns the associated user id
     */
    function verify_hash($hash){

        $valid = true;

        $query = $this->db->get_where('new_registrations', array('hash' => $hash));

        if($query->num_rows() != 1)
            $valid = false;

        $hash_data = $query->row();

        $age = time() - strtotime($hash_data->date_added);

        // If the hash is older than 3 days, delete it
        if($age > 60*60*24*3){
            $this->delete_registration_hash($hash_data->user_id);
            $valid = false;
        }

        if(!$valid)
            throw new RecoverableException("This registration link is invalid.");

        return $hash_data->user_id;
    }

    function delete_registration_hash($user_id){
        // delete registration hash
        $this->db->where('user_id', $user_id);
        $this->db->delete('new_registrations');
    }

    function get_user_info($id){
        $query = $this->db->get_where('users', array('id' => $id));
        return $query->row();
    }

    function complete_registration($user_id, $email, $password){
        $this->update_password($email, $password);
        $this->delete_registration_hash($user_id);
    }

    function update_registration_hash($user_id){

        // Verify that the user is pending
        $query = $this->db->get_where('new_registrations', array('user_id' => $user_id));
        if($query->num_rows != 1){
            throw new RecoverableException("This user has already completed registration.");
        }

        // Generate new hash
        $hash = hash("sha256", mcrypt_create_iv(256, MCRYPT_DEV_URANDOM));

        // Update user's hash for registration url
        $this->db->where('user_id', $user_id);
        $this->db->update('new_registrations', array('hash' => $hash));
        return $hash;
    }

    private function add_registration_hash($user_id){
        $hash = hash("sha256", mcrypt_create_iv(256, MCRYPT_DEV_URANDOM));

        $data = array(
            'user_id' => $user_id,
            'hash' => $hash
        );

        $this->db->insert('new_registrations', $data);

        return $data;
    }

    /**
     * Return the password and user privilege level of the user with the given
     * email address.
     *
     * Throws RecoverableException if the user does not exist.
     * @param  string $email
     * @return object
     */
    function get_user_auth_data($email){
    	// select the fields we want
    	$this->db->select('id,password, is_admin, status, frozen');
    	// get the row with the given email
    	$query = $this->db->get_where('users', array('email' => $email));

    	// Make sure the user exists
    	if($query->num_rows() < 1){
    		throw new RecoverableException("A user with this email does not exist.");
    	}
    	return $query->row();
    }

    /**
     * Determine if a given user is an admin
     * 
     * @param  int  $user_id
     * @return boolean
     */
    function is_admin($user_id){
        $this->db->select('is_admin');
        $query = $this->db->get_where('users', array('id' => $user_id));
        if($query->row()->is_admin == 1)
            return true;
        else false;
    }

    /**
     * Take a user's email and new plain text password,
     * hash it, and update the database with the new hash.
     *
     * Hashing is done using the PHP builtin password_hash()
     * and stores version info and salt data with the hashed password
     * so the database only needs to store the output.
     *
     * @param  string $email
     * @param  string $new_password
     */
    function update_password($email, $new_password){
    	$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    	// Check for error in password_hash()
    	if($hashed_password == FALSE){
    		throw new FatalException("Pick another password.");
    	}

    	// Update the password in the users table
    	$data = array(
    		'password' => $hashed_password,
            'status' => 'registered'
    	);

    	// If the email doesn't exist, this does nothing
    	// but we can't say the email doesn't exist because then
    	// people can use that to find user's emails
    	$this->db->where('email', $email);
    	$this->db->update('users', $data);
    }

    /**
     * Update an already existing email address to a nonexistant email address
     *
     * Throws RecoverableException if the new email address already exists.
     *
     * Throws RecoverableException if the current email address does not already exist.
     * 
     * @param  string $current_email
     * @param  string $new_email
     */
    function update_email($current_email, $new_email){
    	// Check if $new_email already exists (if so, we don't want to clobber it)
    	$query = $this->db->get_where('users', array('email' => $new_email));

    	if($query->num_rows() > 0){
    		throw new RecoverableException("User with email " . $new_email . " already exists!");
    	}

    	// Check if current_email exists (if not, we can't do this)
    	$query = $this->db->get_where('users', array('email' => $current_email));

    	if($query->num_rows() != 1){
    		throw new RecoverableException("User with email " . $current_email . " does not exist");
    	}

    	$data = array(
    		'email' => $new_email
    	);
    	$this->db->where('email', $current_email);
    	$this->db->update('users', $data);
    }

    function add_session($sid, $uid){

        $data = array(
            'session_id' => $sid,
            'user_id' => $uid,
            'first_activity' => NULL
        );
        
        $this->db->insert('sessions', $data);
    }

    function get_session($sid){
        $query = $this->db->get_where('sessions', array('session_id' => $sid));
        if($query->num_rows != 1){
            throw new InvalidSessionException("No session for the given session id");
        }
        return $query->row();
    }

    function delete_session($sid){
        $this->db->where('session_id', $sid);
        $this->db->delete('sessions');
    }
}
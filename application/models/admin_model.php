<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function add_expense($data){
    	$query = $this->db->get_where('users', array('id'=> $data['user_id']));
    	if($query->num_rows() != 1){
    		throw new RecoverableException("This user does not exist");
    	}
    	$this->db->insert('expenses', $data);
        return $this->db->insert_id();
    }

    function add_rental($data){
        $query = $this->db->insert('rentals', $data);
        return $this->db->insert_id();
    }

    function get_img_list($id){
        $this->db->select('images');
        $query = $this->db->get_where('rentals', array('id' => $id));
        if($query->num_rows() == 1)
            return $query->row()->images;
    }

    function get_rental($id){
        $query = $this->db->get_where('rentals', array('id' => $id));
        return $query->row();
    }

    function get_rental_by_name($name){
        $query = $this->db->get_where('rentals', array('name' => $name));
        return $query->row();
    }

    function get_rental_by_category($category){
        $query = $this->db->get_where('rentals', array('category' => $category));
        return $query->result();
    }

    function get_rentals(){
        $this->db->select('id, name, category');
        $query = $this->db->get('rentals');
        return $query->result();
    }

    function edit_rental($data){
        $this->db->where('id', $data['id']);
        $this->db->update('rentals', $data);
    }

    function delete_rental($id){
        $query = $this->db->get_where('rentals', array("id" => $id));
        $result = $query->row();
        $this->db->where('id', $id);
        $this->db->delete('rentals');
        return $result;
    }

    function edit_expense($data){
    	$this->db->where('id', $data['id']);
    	$this->db->update('expenses', $data);
    }

    function delete_expense($id){
    	$this->db->where('id', $id);
        $this->db->delete('expenses');
    }

    function update_contract($user_id, $contract_url){
    	$data = array(
    		'contract' => $contract_url
    	);
    	$this->db->where('id', $user_id);
    	$this->db->update('users', $data);
    }

    function get_users(){
        $this->db->where('is_admin != 1');
    	$query = $this->db->get('users');
    	return $query->result();
    }

    function get_pending_users(){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('new_registrations', 'new_registrations.user_id = users.id');
        $query = $this->db->get();
        return $query->result();
    }

    function delete_filter($type, $text){
        $this->db->delete('filters', array('type' => $type, 'text' => $text));
    }

    function add_filter($type, $text){
        $data = array(
            'type' => $type,
            'text' => $text
        );
        $this->db->insert('filters', $data);
    }

    function toggle_freeze($id){
        $this->db->select('status, frozen');
        $query = $this->db->get_where('users', array('id'=>$id));
        if($query->num_rows() == 1){
            $frozen = $query->row()->frozen;
            $this->db->where('id', $id);
            $this->db->update('users',array('frozen' => !$frozen));
            return $query->row();
        }
    }

    function delete_contract($user_id){
        $this->db->where('id', $user_id);
        $user = $this->db->get('users');
        $this->db->update('users', array('contract' => null));
        return $user->row()->contract;
    }

    function update_receipt($expense_id, $path){
        $this->db->where('id', $expense_id);
        $this->db->update('expenses', array('receipt_image' => $path));
    }
}
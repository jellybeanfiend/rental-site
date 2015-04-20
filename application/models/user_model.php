<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function get_expenses($user_id, $month, $year){


        $this->db->where('user_id', $user_id);
        $this->db->where('month(date)', $month);
        $this->db->where('year(date)', $year);

        return $this->db->get('expenses')->result();
    }

    // // 
    // function get_sums($user_id, $month, $year){
    //     $this->db->start_cache();
    //     $this->db->where('user_id', $user_id);
    //     $this->db->where('month(date)', $month);
    //     $this->db->where('year(date)', $year);
    //     $this->db->stop_cache();

    //     $this->db->select_sum('amt_usd');
    //     $query['usd_sum'] = $this->db->get('expenses')->row()->amt_usd;
    //     $this->db->select_sum('amt_mxn');
    //     $query['mxn_sum'] = $this->db->get('expenses')->row()->amt_mxn;
    //     $this->db->flush_cache();

    //     return $query;
    // }

    function get_range_expenses($user_id, $from_month, $from_year,$to_month,$to_year){

        $from_date = date('Y-m-d', mktime(0, 0, 0, $from_month, 1, $from_year));
        $to_date = date('Y-m-d', mktime(0, 0, 0, $to_month+1, 1, $to_year));
        echo $to_date;

        $this->db->where('user_id', $user_id);
        $this->db->where('date >=', $from_date);
        $this->db->where('date <', $to_date);
        $query = $this->db->get('expenses');
        return $query->result();
    }

    function get_range_dates($user_id, $from_month, $from_year,$to_month,$to_year){

        $from_date = date('Y-m-d', mktime(0, 0, 0, $from_month, 1, $from_year));
        $to_date = date('Y-m-d', mktime(0, 0, 0, $to_month+1, 1, $to_year));
        echo $to_date;

        $this->db->select('month(date),year(date)');
        $this->db->where('user_id', $user_id);
        $this->db->where('date >=', $from_date);
        $this->db->where('date <', $to_date);
        $query = $this->db->get('expenses');
        return $query->result();
    }

    function get_contract($user_id){
        $this->db->select('contract');
        $query = $this->db->get_where('users', array('id' => $user_id));
        return $query->row()->contract;
    }

    function get_filter($type){
        $this->db->select('text');
        $query = $this->db->get_where('filters', array('type' => $type));
        return $query->result();
    }

    function get_all_user_expenses($user_id){
        $query = $this->db->get_where('expenses', array('user_id' => $user_id));
        return $query->result();
    }

    function get_min_year($user_id){
        $this->db->select_min('date');
        $query = $this->db->get_where('expenses', array('user_id' => $user_id));
        return $query->row()->date;
    }

    function get_profile($user_id){
        $this->db->select('email, name');
        $query = $this->db->get_where('users', array('id' => $user_id));
        return $query->row();
    }

    function get_info($user_id){
        $this->db->select('email, name');
        $query = $this->db->get_where('users', array('id' => $user_id));
        return $query->row();
    }

    function update_profile($user_id, $inputs){
        $this->db->where('id', $user_id);
        $this->db->update('users', $inputs);
    }

    function get_user($id){
        $this->db->select('id, name');
        $query = $this->db->get_where('users', array('id' => $id));
        return $query->row();
    }

    function get_expense($id){
        $query = $this->db->get_where('expenses',array('id'=>$id));
        return $query->row();
    }
    
    function frozen($id){
        $query = $this->db->get_where('users', array('id'=>$id));
        return $query->row()->frozen;
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class userdetails_model extends CI_Model {

    //var $table , $column_order, $column_search , $order =  '';
    var $table = SWAPS;
   var $column_order = array(null,'s.swapID','s.looking_user_id', 's.leaving_user_id','s.looking_location','s.leaving_location','s.looking_car_info','s.leaving_car_info','s.looking_status','s.leaving_status','s.gratuity_amount','u1.full_name as looking_user','u1.avatar as looking_avatar','u2.full_name as leaving_user','u2.avatar as leaving_avatar'); //set column field database for datatable orderable
    var $column_search = array('s.looking_user_id','s.leaving_user_id', 's.looking_car_info','s.leaving_car_info' ,'u1.full_name','u2.full_name','s.gratuity_amount' ); //set column field database for datatable searchable 
    var $order = array('s.swapID' => 'DESC');  // default order
    var $where = array();
    var $group_by = 's.swapID'; 

    public function __construct(){
        parent::__construct();
    }
    
    public function set_data($where=''){
        $this->where = $where; 
        $this->or_where = $where; 
    }

    private function _get_query()
    {
      $this->db->select('u1.userID,u1.full_name as looking_user,u1.avatar as looking_avatar,u1.is_avatar_url,u2.userID,u2.full_name as leaving_user,u2.avatar as leaving_avatar,u2.is_avatar_url,swapTransactionID,s.*');
       $this->db->from(SWAPS.' as s');
       $this->db->join(USERS. ' as u1','u1.userID = s.looking_user_id','left');
       $this->db->join(USERS. ' as u2','u2.userID = s.leaving_user_id','left');
       $this->db->join(SWAP_TRANSACTIONS. ' as st','st.swap_id = s.swapID','left');
        $i = 0;
        foreach ($this->column_search as $emp) // loop column 
        {
      if(isset($_POST['search']['value']) && !empty($_POST['search']['value'])){
      $_POST['search']['value'] = $_POST['search']['value'];
    } else
      $_POST['search']['value'] = '';
    if($_POST['search']['value']) // if datatable send POST for search
    {
      if($i===0) // first loop
      {
        $this->db->group_start();
        $this->db->like(($emp), $_POST['search']['value']);
      }
      else
      {
        $this->db->or_like(($emp), $_POST['search']['value']);
      }

      if(count($this->column_search) - 1 == $i) //last loop
        $this->db->group_end(); //close bracket
    }
    $i++;
    }

        if(!empty($this->where))
            $this->db->where($this->where); 


        if(!empty($this->group_by)){
            $this->db->group_by($this->group_by);
        }
         

    if(isset($_POST['order'])) // here order processing
    { 
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->order))
    { 
        $order = $this->order; 
        $this->db->order_by(key($order), $order[key($order)]);
    }
       
    }

    function get_list()
    {
        $this->_get_query();
    if(isset($_POST['length']) && $_POST['length'] < 1) {
      $_POST['length']= '10';
    } else
    $_POST['length']= $_POST['length'];
    
    if(isset($_POST['start']) && $_POST['start'] > 1) {
      $_POST['start']= $_POST['start'];
    }
        $this->db->limit($_POST['length'], $_POST['start']);
    //print_r($_POST);die;
        $query = $this->db->get(); //lq();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }


    public function selectData($table,$userID){
       // $where = array('user_id'=>$userID);
        $this->db->select('s.swapID','s.looking_user_id', 's.leaving_user_id','s.looking_location','s.leaving_location','s.looking_car_info','s.leaving_car_info','s.looking_status','s.leaving_status','s.gratuity_amount','u1.full_name as looking_user','u1.avatar as looking_avatar','u2.full_name as leaving_user','u2.avatar as leaving_avatar');
        $this->db->from($table.' as u');
        $this->db->where($where);
        $this->db->or_where($where);
        $query=$this->db->get();


        return $query->row();
    }
     public function get_userinfo($id){
     
      $this->db->select('*');
      $this->db->from(USERS);
      $this->db->where('user_id',$id);
      $qry = $this->db->get();
      $result = $qry->row();
      return $result;
      
      }
}



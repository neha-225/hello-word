<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    //var $table , $column_order, $column_search , $order =  '';
    var $table = USERS;
    var $column_order = array(null,'u.userID', 'u.full_name','u.email', 'u.avatar','u.status' ,'u.gender','u.age','u.profile_language'); //set column field database for datatable orderable
    var $column_search = array('u.userID','u.full_name', 'u.email'); //set column field database for datatable searchable 
    var $order = array('u.userId' => 'DESC');  // default order
    var $where = array();
    var $group_by = 'u.userId'; 

    public function __construct(){
        parent::__construct();
    }
    
    public function set_data($where=''){
        $this->where = $where; 
    }

    private function _get_query()
    {
        $sel_fields = array_filter($this->column_order); 
        $this->db->select($sel_fields);
        $this->db->from(USERS .' as u');
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

    // public function selectData(){
    //     $this->_get_query();
    //     // $where array('userID'=>$userID);
    //     // $this->db->select(*);
    //     // $this->db->from($table.' as u');
    //     // $this->db->where($where);
    //     // $query=$this->db->get();
    // }



    public function selectData($table,$userID){
        $where = array('userID'=>$userID);
        $this->db->select('u.userID', 'u.full_name','u.email', 'u.avatar','u.status' ,'u.gender','u.age');
        $this->db->from($table.' as u');
        $this->db->where($where);
        $query=$this->db->get();

        return $query->row();
    }
    public function get_userinfo($id){
     
      $this->db->select('*');
      $this->db->from(USERS);
      $this->db->where('userID',$id);
      $qry = $this->db->get();
      $result = $qry->row();
      return $result;
      
    }

   
    public function get_total_swap_count($user_id){

        $this->db->select('*');
        $this->db->from(SWAPS);
        $this->db->where('looking_user_id',$user_id);
        $this->db->or_where('leaving_user_id',$user_id);


        if(!empty($where))
        $this->db->where($where);

        $query = $this->db->get();

        return $query->num_rows(); //total records
    }
    
}



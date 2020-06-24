

    <?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    class Home_model extends CI_Model {
   
    
    
        function insertData($table, $dataInsert) {
        $this->db->select('email');
        $this->db->from(NEWSLETTERS);
        $this->db->where('email',$dataInsert['email']);
        $query = $this->db->get();
        $res = $query->result();
        if($res){
        return false;
        }else{
        $this->db->insert($table, $dataInsert);
        return true;
    }
    }
     function addtocard($table, $dataInsert) {
        $this->db->select('*');
        $this->db->from(ADDCARD);
        $this->db->where('product_id',$dataInsert['product_id']);
        $query = $this->db->get();
        $res = $query->result();
        if($res){
        return false;
        }else{
        $this->db->insert($table, $dataInsert);
        return true;
    }
    }
    // public function getAll(){
    //      $this->db->select('*');
    //      $this->db->from(OUR);
    //      $qry = $this->db->get();
    //      $result = $qry->row();
    //      return $result;
    // }
    function selectCategory($id=''){
    $this->db->select('*');
    $this->db->from(OUR);
    $qry = $this->db->get();
    $result = $qry->result();
    return $result;
    }
   function getProduct1($table,$id){
        //pr($id);
        $id=$this->input->get('id');
        $this->db->select('id,image,description,price');
        $this->db->from(PRODUCT);
        $this->db->where('id',$id);
        $query=$this->db->get();
        //lq();
        $res=$query->result();
        return $res;
    }
    function checkout($table, $dataInsert) {
        $this->db->select('*');
        $this->db->from(CHECKOUT);
        $this->db->where('email',$dataInsert['email']);
        $query = $this->db->get();
        //lq();
        $res = $query->result();
        if($res){
        return false;
        }else{
        $this->db->insert($table, $dataInsert);
        return true;
        }
    }
    function checkout12($table, $dataInsert) {
        $this->db->select('*');
        $this->db->from(CHECKOUT);
        $this->db->where('email',$dataInsert['email']);
        $query = $this->db->get();
        //lq();
        $res = $query->result();
        if($res){
        return false;
        }else{
        $this->db->insert($table, $dataInsert);
        return true;
        }
    }
}


    

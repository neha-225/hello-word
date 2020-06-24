<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }
    
    public function getImage($email){
         $this->db->select('email');
         $this->db->from(IMAGE);
         $qry = $this->db->get();
         $result = $qry->row();
         return $result;
    }
    public function getAll($table){
         $this->db->select('*');
         $this->db->from(OUR);
         $qry = $this->db->get();
        // lq()
         $result = $qry->result();
         return $result;
    }
     
    
}



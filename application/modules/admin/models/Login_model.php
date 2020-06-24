<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {
	public function __construct(){

        parent::__construct();

    
    }
  /** check login  */
   function isLogin($email,$password,$table){  
        $this->db->select("*");
        $this->db->where('email',$email);
        $query = $this->db->get($table);
        if(!$query){
            $this->output_db_error(); //500 error
        }
        $user = $query->row();
        if(empty($user)) {
            return FALSE;
        }
            $id = $user->adminUserID;
            if(password_verify($password, $user->password)){
                $this->session_create($id);
                return TRUE;
            }
            else{
                return FALSE; 
            }
        
       
    }
    //END OF FUNCTION..

    

    /**  Create sesion for checking user login or not*/
    function session_create($id) {
        
        $sql = $this->db->select('*')->where(array('adminUserID'=>$id))->get(ADMIN_USERS);
        if(!$sql){
            $this->output_db_error(); //500 error
        }
        $user= $sql->row();
        if(empty($user)) {
            return FALSE;
        }
            $user= $sql->row();

            $session_data['adminUserID'] = $user->adminUserID ;
            $session_data['emailId'] = $user->email;
            $session_data['name'] = $user->name;
            $session_data['avatar']=$user->avatar;
            // if (!empty($user->avatar)) {
            //     $session_data['avatar'] = $user->avatar;
            // }
             
            $session_data['isLogin'] = TRUE;
            $_SESSION[ADMIN_USER_SESS_KEY] = $session_data;
            return TRUE;  
        
    }

}
<?php

class Auth_model extends MY_Model{

	public function __construct() {

        parent::__construct();
    
    }

    /*
     * Register user 
     * Return user id 
    */
    function userRegistartion($data) {
        
       $res = $this->db->insert(USERS,$data);
 
        return $this->db->insert_id();
    }// End function

    function socialRegistartion($socialData) {
        
       $res = $this->db->insert(SOCIAL_ACCOUNTS,$socialData);
 
        return $this->db->insert_id();
       
    }// End function

    /*
     * Add user device info
     * Param like device id,device type
    */
     function add_device_info($data) {

        $device_info   = $this->deviceExist($data);
        if(!empty($device_info)) {
            $userDeviceID = $device_info->userDeviceID;
            $update_device = $this->updateDevice($userDeviceID, $data);
        }else {
            $this->db->insert(USER_DEVICES, $data);
            $userDeviceID = $this->db->insert_id();
        }
        return $userDeviceID;
    }
    
    /*
     * function for log in 
     * Param like email ,phone number,password
    */
    function sociallogin_user($auth, $where) {

        $this->db->select('userID, status, password, social_id, social_type ');
        $this->db->from(USERS.' as users');
        $this->db->where($where);
        $res = $this->db->get();
        if(!$res) {
            $this->output_db_error(); //500 error
        }

        $result = $res->row();
        if(empty($result)) {

            return array('returnType'=>'WE'); // Wrong Email
        }

        if($result->status != 1) {
            return array('returnType'=>'IA'); // Inactive user
        }

        if(!empty($result->social_id) && empty($result->password)) {

             return array('returnType'=>'SU'); // social user
        }

        if(empty($result->social_id) && empty($result->password)) {
             return array('returnType'=>'WE'); // Wrong Email
        }

        if(!password_verify($auth['password'], $result->password)){

            return array('returnType'=>'WP'); // Wrong Password
        }
        
        $device_info = array(
            'user_id'           => $result->userID,
            'device_id'         => $auth['device-id'],
            'device_token'      => $auth['device-token'],
            'device_timezone'   => $auth['timezone'],
            'created_at'        => datetime()
        );
        $add_device = $this->add_device_info($device_info);

        $genrate_token  =   $this->general_model->generate_token($result->userID,$auth['device-id']);
        $user_info      =   $this->general_model->getUserDetail($result->userID,$auth['device-id']);
        $user_info->auth_token = $genrate_token ;
        return array('returnType'=>'SL','userInfo'=>$user_info);
    }

    function complete_profile($data,$user_id) {

        $this->db->where("userID",$user_id);
        $this->db->update(USERS,$data);
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
       
    }


    function car_details($data,$user_id) {
        
       $res = $this->db->insert(CAR,$data);
 
        return $this->db->insert_id();
       
    }

    function login_user($auth, $where) {

        $this->db->select('userID, status, password ');
        $this->db->from(USERS.' as users');
        $this->db->where($where);
        $res = $this->db->get();
        if(!$res) {
            $this->output_db_error(); //500 error
        }

        $result = $res->row();
        if(empty($result)) {

            return array('returnType'=>'WE'); // Wrong Email
        }

        if($result->status != 1) {
            return array('returnType'=>'IA'); // Inactive user
        }

        if(!empty($result->social_id) && empty($result->password)) {

             return array('returnType'=>'SU'); // social user
        }

        if(empty($result->social_id) && empty($result->password)) {
             return array('returnType'=>'WE'); // Wrong Email
        }

        if(!password_verify($auth['password'], $result->password)){

            return array('returnType'=>'WP'); // Wrong Password
        }
        
            $device_info = array(
                'user_id'           =>$result->userID,
                'device_type'       =>$auth['device-type'],
                'device_id'         =>$auth['device-id'],
                'device_token'      =>$auth['device_token'],
                'device_timezone'   =>$auth['timezone'],
                'created_at'        =>datetime(),
                'updated_at'        =>datetime()
            );
           $add_device = $this->add_device_info($device_info);
  
        $genrate_token  =   $this->general_model->generate_token($result->userID,$auth['device-id']);
        $data['last_login_at']  = datetime();
        $where   = array('userID' =>$result->userID);
        $this->common_model->updateFields(USERS,$data,$where);
        $user_info      =   $this->general_model->getUserDetail($result->userID,$auth['device-id']);
        $user_info->auth_token = $genrate_token ;
       
        return array('returnType'=>'SL','userInfo'=>$user_info);
    }
        
    /*
     * Check device exist or not. 
     * Param like user_id ,device_id,device_type
    */
    function deviceExist($device_info) {

        extract($device_info);
        $this->db->select('userDeviceID');
        $this->db->from(USER_DEVICES);
        $this->db->where(array("device_id"=>$device_id,"device_type"=>$device_type));
        $res    = $this->db->get();
        if(!$res){
            $this->output_db_error(); //500 error
        }
        return $res->row();
    }// End function

    /*
     * Update device in db. 
     * Param like user_id ,device_id,device_type
    */
    function updateDevice($userDeviceID, $device_info) {

        extract($device_info);
        //unset($device_data['']);
        $this->db->where("userDeviceID", $userDeviceID);
        $this->db->update(USER_DEVICES, $device_info);
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    function passwordInsert($data) {
        
        $this->db->insert(USERS,$data);
 
        return $this->db->insert_id();
    }

    /*
     * Add meta data 
     * Return user id 
    */
    function addMetaData($data) {
        
       $res = $this->db->insert(USER_META,$data);
 
        return $this->db->insert_id();
    }
}//END OF CLASS
?>
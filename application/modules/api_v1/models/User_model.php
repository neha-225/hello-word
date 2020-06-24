<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use \Firebase\JWT\JWT;

/**
 * General Model
 * version: 2.0 (14-08-2018)
 * General DB queries used commonly in all modules
 */

class User_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->userDefaultAvatar  =  base_url().USER_DEFAULT_AVATAR;
        $this->userAvatar =  base_url().USER_AVATAR;
        $this->userAvatarThumb =  base_url().USER_AVATAR_THUMB;

    }

    function getUserDetails($socialData, $email){
	
 		$where = ('users.email="'.$email.'" OR social.social_id = "'.$socialData['social_id'].'" AND social.social_type = "'.$socialData['social_type'].'" ');

		//$this->db->select('*');
        $this->db->select('userID, full_name, email, password, gender, age,profile_language,stripe_customer_id, 
            (case 
                when(users.avatar = "" OR users.avatar IS NULL OR users.is_avatar_url = 0) 
                    THEN "'.$this->userDefaultAvatar.'"
                when(users.is_avatar_url = 2) 
                    THEN users.avatar
                ELSE
                    concat("'.$this->userAvatarThumb.'", users.avatar) 
                END 
            ) as user_avatar, profile_timezone, profile_address, profile_latitude, profile_longitude, onboarding_step, onboarding_completed, signup_from,
            push_alert_status, last_login_at,signup_type,
            status, users.updated_at,users.created_at,
            social.social_id,social.social_type,social.created_at' 
        );
		$this->db->from(USERS.' as users');
		$this->db->join(SOCIAL_ACCOUNTS.' as social' ,'users.userID = social.user_id','left');
	 	$this->db->where($where);
		$qry = $this->db->get();

		 if(!$qry){
            $this->output_db_error(); //500 error
        }

        if($qry->row()){
            $qry->row()->account_info = $this->getBankDetails($qry->row()->userID);
            $qry->row()->card_info = $this->getCardDetails($qry->row()->userID);
            $qry->row()->carinfo = $this->getcarDetails($qry->row()->userID);
            $qry->row()->content = array('terms_url'=>base_url(),'privacy_url'=>base_url());
        }
        $result = $qry->row();
       

        return $result;
    }

    function getcarDetails($userID) {
    
        $this->db->select('*');
        $this->db->from(CAR.' as car');
        $this->db->where('user_id',$userID);
        $qry = $this->db->get();
        if(!$qry){
            $this->output_db_error(); //500 error
        }
        $carinfo = $qry->row();

        return ($carinfo)? $carinfo: new stdClass();

    }

    function getCardDetails($userID) {
    
        $this->db->select('*');
        $this->db->from(CARDS.' as cards');
        $this->db->where('user_id',$userID);
        $qry = $this->db->get();
        if(!$qry){
            $this->output_db_error(); //500 error
        }
        $carinfo = $qry->row();

        return ($carinfo)? $carinfo: new stdClass();

    }
    function getBankDetails($userID) {
    
        $this->db->select('*');
        $this->db->from(EXTERNAL_ACCOUNTS.' as account');
        $this->db->where('user_id',$userID);
        $qry = $this->db->get();
        if(!$qry){
            $this->output_db_error(); //500 error
        }
        $bankinfo = $qry->row();

        return ($bankinfo)? $bankinfo: new stdClass();

    }

    function checkSocialExist($user_id,$socialData) {

		$query = $this->db->query('SELECT * FROM '.SOCIAL_ACCOUNTS.' WHERE social_id = "'.$socialData['social_id'].'" AND social_type = "'.$socialData['social_type'].'" ') ; 
		$isExist = $query->row();

		if(!empty($isExist)){
		return $isExist;
		}
		else {
		$insertData = array(
			'user_id' => $user_id,
			'social_id' => $socialData['social_id'],
			'social_type' => $socialData['social_type'],
			'created_at' => datetime(),
		);
		$insert_id = $this->common_model->insertData(SOCIAL_ACCOUNTS,$insertData);

			if(!$insert_id){
			return FALSE;
			}
			return $insert_id;
			}
	}

    function getId($id) {

        $where = array('carInfoID'=>$id);
        $this->db->select('*');
        $this->db->from(CAR.' as car');
        $this->db->where($where);
        $res = $this->db->get();
        if(!$res) {
            $this->output_db_error(); //500 error
        }

        $result = $res->row();
        return $result;
    } 

    function getcarDetail($userID) {
    
        $this->db->select('*');
        $this->db->from(CAR.' as car');
        $this->db->where('user_id',$userID);
        $qry = $this->db->get();
        if(!$qry){
            $this->output_db_error(); //500 error
        }
        $result = $qry->row();
        return $result;
    }
}  

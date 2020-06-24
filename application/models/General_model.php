<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use \Firebase\JWT\JWT;

/**
 * General Model
 * version: 2.0 (14-08-2018)
 * General DB queries used commonly in all modules
 */

class General_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        //$this->userDefaultAvatar  =  base_url().USER_DEFAULT_AVATAR;
        //$this->userAvatar =  base_url().USER_AVATAR;
        //$this->userAvatarThumb =  base_url().USER_AVATAR_THUMB;

    }

    /**
     * Generate JWT
     * @param    int $user_id Current User ID
     * @param    array $user_entity Can be any entity related to user
     *           (user type, email, device ID etc)
     * @param    int $expire_time  Expire time in secs (Default 1hour)
     * @return   string JWT
     * 
     */
    public function generate_token($user_id, $user_entity=[], $expire_time=604800){
        
        $issuedAt   = time(); //current timestamp
        $notBefore  = $issuedAt; //Token to be not validated before given time
        $expire     = time() + $expire_time; //Adding offset time to current timestamp

        $data_arr = [];
        $data_arr['userId'] = $user_id;
        if(!empty($user_entity)) {
           
            // Can be any entity related to user(user type, email, device ID etc)
            // $data_arr['user_type'] = $user_type;
            $data_arr['device_id'] = $user_entity;
        }
        
        $data = [
            'iat'  => $issuedAt,   // Issued at: time when the token was generated
            'jti'  => getenv('JWT_TOKEN_ID'), // Json Token Id: an unique  identifier for the token
            'iss'  => getenv('SERVER_NAME'), // Issuer (example.com)
            'nbf'  => $notBefore,        // Not before
            'exp'  => $expire,           // Expire time
            'data' => $data_arr
        ];

        $jwt = JWT::encode( $data, getenv('JWT_SECRET_KEY'));
        return $jwt;
    }

    /**
     * Get User Detail by given user ID and device ID
     * @param   int | $user_id   User's ID
     * @param   string | $device_id   User's device ID
     * @return  object | $user  User's detail object
     */
    function getUserDetail($userId, $device_id) {

        $this->db->select('userID, full_name, email, password, gender, age,  profile_language,stripe_customer_id,stripe_connect_account_id,stripe_connect_account_verified,
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
            status, users.updated_at, users.created_at,
            device_type, device_id, device_token'
        );
        $this->db->from(USERS.' as users');
        $this->db->join(USER_DEVICES.' as device', 'device.user_id = users.userID');
        $this->db->where('userID', $userId);
        $this->db->where('device_id', $device_id);
        $qry = $this->db->get();
        if(!$qry){
            $this->output_db_error(); //500 error
        }
        
        $qry->row()->carinfo = $this->getcarDetails($qry->row()->userID);
        $qry->row()->account_info = $this->getBankDetails($qry->row()->userID);
        $qry->row()->card_info = $this->getCardDetails($qry->row()->userID);
        $qry->row()->content = array('terms_url'=>base_url(),'privacy_url'=>base_url());
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
     function getBankDetails($userID) {
    
        $this->db->select('*');
        $this->db->from(EXTERNAL_ACCOUNTS.' as account');
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
    /**
     * Check username exist or not
     * @param   string | $usename Username
     * @return  boolean | TRUE or FALSE
     */
    function check_username_exists($usename) {

        $this->db->select('userID');
        $this->db->from(USERS);
        $this->db->like('username',$usename,'none');
        $query = $this->db->get();
        if(!$query) {
            $this->output_db_error(); //500 error
        }
        $rowcount = $query->row();
        if(empty($rowcount)) {
            return false;
        }
        else {
            return true;
        }

    }
  
} //end of class

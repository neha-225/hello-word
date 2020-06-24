<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use \Firebase\JWT\JWT;

/**
 * General Model
 * version: 2.0 (14-08-2018)
 * General DB queries used commonly in all modules
 */

class Parking_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->range = 20;//km
        $this->userDefaultAvatar  =  base_url().USER_DEFAULT_AVATAR;
        $this->userAvatar =  base_url().USER_AVATAR;
        $this->userAvatarThumb =  base_url().USER_AVATAR_THUMB;
    } 

    function getDetail($user_id) {

        $this->db->select('cards.cardID,stripe_card_id,card_holder_name,card_last_4_digits,card_expiry_month,card_expiry_year,card_brand_type,is_default,car.carInfoID,make,car.user_id,model,plate_number,color,accounts.stripe_connected_account_id,first_name,last_name,dob,account_number,routing_number,stripe_external_account_id'
        );
        $this->db->from(CAR.' as car');
        $this->db->join(EXTERNAL_ACCOUNTS.' as accounts','accounts.user_id= car.user_id');
        $this->db->join(CARDS.' as cards','cards.user_id= car.user_id');
        $this->db->where('car.user_id', $user_id);
        $qry = $this->db->get();
        $result = $qry->row();
        return $result;
    }

    function getcarDetail($user_id) {

        $this->db->select('model,make,color,plate_number'
        );
        $this->db->from(CAR.' as car');
        $this->db->where('user_id', $user_id);
        $qry = $this->db->get();
        $result = $qry->row();
        return $result;   

    }

    function getAccountDetail($user_id) {

        $this->db->select('*');
        $this->db->from(EXTERNAL_ACCOUNTS.' as account');
        $this->db->where('user_id', $user_id);
        $qry = $this->db->get();
        $result = $qry->row();
        return $result;   

    }

    function getCardDetail($user_id) {

        $this->db->select('*');
        $this->db->from(CARDS.' as crds');
        $this->db->where('user_id', $user_id);
        $qry = $this->db->get();
        $result = $qry->row();
        return $result;   

    }

    function getCard($user_id,$cardId) { 

        $where = array('user_id'=>$user_id,'stripe_card_id'=>$cardId);
        $this->db->select('*');
        $this->db->from(CARDS.' as crds');
        $this->db->where($where);
        $qry = $this->db->get();
        $result = $qry->row();
        return $result;   

    }

    function parking($user_id,$lat,$long) {

    	$this->db->select('FC_GET_DISTANCE('.$lat.',"'.$long.'", p.latitude,p.longitude,"km") AS distance,u.userID, u.full_name,u.email,(case 
                when(u.avatar = "" OR u.avatar IS NULL OR u.is_avatar_url = 0) 
                    THEN "'.$this->userDefaultAvatar.'"
                when(u.is_avatar_url = 2) 
                    THEN u.avatar
                ELSE
                    concat("'.$this->userAvatarThumb.'", u.avatar) 
                END 
            ) as user_avatar,u.gender,u.age,u.stripe_customer_id,c.carInfoID,c.make,c.model,c.color,p.location,p.latitude,p.longitude,p.type,p.created_at');
        
        $this->db->from(USERS.' as u');
        $this->db->join(CAR.' as c','u.userID = c.user_id');
        $this->db->join(PARKING.' as p','u.userID = p.user_id');
        $this->db->group_by('u.userID');
        $this->db->having('distance <= ', $this->range);
        $this->db->limit(20);
        $qry = $this->db->get();
        $result = $qry->result();
        return $result;
    }


    function swap_connection($user_id) {

        $where= array('looking_user_id'=>$user_id);
        $or_where  = array('leaving_user_id'=>$user_id,'leaving_status'=>1);
        $this->db->select('*,
            IF(`looking_user_id` = '.$user_id.' , 1, 2) as `request_type`
            ');
        $this->db->from(SWAPS.' as sc');
        $this->db->group_start();
            $this->db->where($where);
            $this->db->or_group_start();
                $this->db->where($or_where);
            $this->db->group_end();
        $this->db->group_end();
        $this->db->where('connection_status!=', 3);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(1);
        $qry = $this->db->get();
        $result = $qry->row();
         if(!empty($result)){
            
            $qry->row()->looking_car_info = json_decode($qry->row()->looking_car_info);
            $qry->row()->leaving_car_info = json_decode($qry->row()->leaving_car_info);
        }
       
        return ($result)? $result : new stdClass();
    }


    function getlookingtype($user_id) {

        $where = array('user_id'=>$user_id,'type'=>1);
        $this->db->select('user_id,type');
        $this->db->from(PARKING.' as p');
        $this->db->where($where);
        $qry = $this->db->get();
        $result = $qry->row();
        return $result;   
    }

    function getleavingtype($user_id) {

        $where = array('user_id'=>$user_id,'type'=>2);
        $this->db->select('user_id,type');
        $this->db->from(PARKING.' as p');
        $this->db->where($where);
        $qry = $this->db->get();
        $result = $qry->row();
        return $result;   
    }

    function getswap($user_id,$leaving_user_id) {

        $where = array('looking_user_id'=>$user_id,'leaving_user_id'=>$leaving_user_id,'connection_status !=' =>3);
        $this->db->select('*');
        $this->db->from(SWAPS.' as s');
        $this->db->where($where);
        $qry = $this->db->get();
        $result = $qry->row();
        return $result;   
    }

     function getSwapsId($user_id,$leaving_user_id) {

        $where = array('looking_user_id'=>$user_id,'leaving_user_id'=>$leaving_user_id,'connection_status !=' =>3);
        $this->db->select('*');
        $this->db->from(SWAPS.' as s');
        $this->db->where($where);
        $qry = $this->db->get();
        $result = $qry->row();
          if(!empty($result)){
            
            $qry->row()->looking_car_info = json_decode($qry->row()->looking_car_info);
            $qry->row()->leaving_car_info = json_decode($qry->row()->leaving_car_info);
        }
        return $result;   
    }
    function getSwapDetails($user_id,$leaving_user_id) {

        $where = array('swapID'=>$user_id,'leaving_user_id'=>$leaving_user_id);
        $this->db->select('userID, full_name,
            (case 
                when(u.avatar = "" OR u.avatar IS NULL OR u.is_avatar_url = 0) 
                    THEN "'.$this->userDefaultAvatar.'"
                when(u.is_avatar_url = 2) 
                    THEN u.avatar
                ELSE
                    concat("'.$this->userAvatarThumb.'", u.avatar) 
                END 
            ) as user_avatar,s.*'
        );
        $this->db->from(SWAPS.' as s');
        $this->db->join(USERS.' as u','u.userID = s.leaving_user_id');
        $this->db->where($where);
        $qry = $this->db->get();
        $result = $qry->row();
        if(!empty($result)){
            
            $qry->row()->looking_car_info = json_decode($qry->row()->looking_car_info);
            $qry->row()->leaving_car_info = json_decode($qry->row()->leaving_car_info);
        }
        return $result;   
    }

    function getleavingusercarinfo($user_id) {

        $this->db->select('carInfoID,c.user_id,make,model,plate_number,color,c.updated_at,c.created_at');
        $this->db->from(USERS.' as u');
        $this->db->join(CAR.' as c','u.userID = c.user_id');
        $this->db->where('user_id',$user_id);
        $qry = $this->db->get();
        $result = $qry->row();
        return $result;
    }

    function getUserId($swapID) {
        $where = array('swapID'=>$swapID,'connection_status !='=>3);
        $this->db->select('*');
        $this->db->from(SWAPS.' as sc');
        $this->db->where($where);
        $qry = $this->db->get();
        $result = $qry->row();
         if(!empty($result)){
            
            $qry->row()->looking_car_info = json_decode($qry->row()->looking_car_info);
            $qry->row()->leaving_car_info = json_decode($qry->row()->leaving_car_info);
        }
        return $result;   
    }
    function getBankAccountDetail($user_id) {

      $this->db->select('*');
        $this->db->from(EXTERNAL_ACCOUNTS.' as account');
        $this->db->where('user_id', $user_id);
        $qry = $this->db->get();
        $result = $qry->row();
        return $result;   

    }
    function getSwapconnection($user_id,$leaving_user_id,$lat,$long) {

        $where = array('looking_user_id'=>$user_id,'leaving_user_id'=>$leaving_user_id);
        $this->db->select('*');
        $this->db->from(SWAPS.' as s');
        $this->db->where($where);
        $qry = $this->db->get();
        $result = $qry->row();
        return $result;   
    }
    function getSwaps($user_id,$leaving_user_id) {

        //$where = array('looking_user_id'=>$user_id,'leaving_user_id'=>$leaving_user_id);
        $user_id = $user_id['looking_user_id'];
        $leaving_user_id = $leaving_user_id['looking_user_id'];
        $this->db->select('userID, full_name,
            (case 
                when(u.avatar = "" OR u.avatar IS NULL OR u.is_avatar_url = 0) 
                    THEN "'.$this->userDefaultAvatar.'"
                when(u.is_avatar_url = 2) 
                    THEN u.avatar
                ELSE
                    concat("'.$this->userAvatarThumb.'", u.avatar) 
                END 
            ) as user_avatar,s.looking_user_id,s.leaving_user_id,s.looking_location,s.looking_latitude,s.looking_longitude,s.gratuity_paid,s.leaving_location,s.leaving_latitude,s.leaving_longitude,s.looking_car_info,s.leaving_car_info,s.created_at,gratuity_amount,swapID'
        );
        $this->db->from(SWAPS.' as s');
        $this->db->join(USERS.' as u','u.userID = s.leaving_user_id');
        $this->db->where(array('looking_user_id'=>$user_id,'leaving_user_id'=>$leaving_user_id));
        $qry = $this->db->get();
        //$qry->row()->looking_car_info = json_decode($qry->row()->looking_car_info);
        //$qry->row()->leaving_car_info = json_decode($qry->row()->leaving_car_info);
        $result = $qry->row();
        return $result;   
    }
    function allDeviceToken($table,$where){
    // SELECT GROUP_CONCAT(id) FROM table_level where parent_id=4 group by parent_id;
        $this->db->select('GROUP_CONCAT(device_token) as device_token');
        $this->db->from($table); 
        $this->db->where($where); 
        $this->db->group_by('user_id'); 
        $q = $this->db->get();
        // return $q->result(); //return multiple records
        $value = $q->row();
        $final = array();
        if(!empty($value)){
        $final = explode(',', $value->device_token);
        }
        return $final;
    }//en do function


     function get_swap_connection_details($user_id,$leaving_user_id) {

        $where = array('looking_user_id'=>$user_id,'leaving_user_id'=>$leaving_user_id);
        $this->db->select('userID, full_name,
            (case 
                when(u.avatar = "" OR u.avatar IS NULL OR u.is_avatar_url = 0) 
                    THEN "'.$this->userDefaultAvatar.'"
                when(u.is_avatar_url = 2) 
                    THEN u.avatar
                ELSE
                    concat("'.$this->userAvatarThumb.'", u.avatar) 
                END 
            ) as user_avatar,s.*'
        );
        $this->db->from(SWAPS.' as s');
        $this->db->join(USERS.' as u','u.userID = s.looking_user_id');
        $this->db->where($where);
        $qry = $this->db->get();
        $result = $qry->row();
        if(!empty($result)){
            
            $qry->row()->looking_car_info = json_decode($qry->row()->looking_car_info);
            $qry->row()->leaving_car_info = json_decode($qry->row()->leaving_car_info);
        }
        return $result;   
    }
}
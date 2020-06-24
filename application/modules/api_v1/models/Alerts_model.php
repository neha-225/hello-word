<?php

class Alerts_model extends MY_Model{

	public function __construct() {

        parent::__construct();
        $this->userDefaultAvatar  =  base_url().USER_DEFAULT_AVATAR;
        $this->userAvatar =  base_url().USER_AVATAR;
        $this->userAvatarThumb =  base_url().USER_AVATAR_THUMB;
    
    }

    function getNotification($limit,$offset,$user_id) { 

        $where = array('recipient_user_id'=>$user_id);
        $this->db->select('a.*,s.swapID,s.looking_user_id,s.leaving_user_id,s.looking_location,s.leaving_location,s.looking_car_info,s.leaving_car_info,s.looking_status,s.leaving_status,s.connection_status,s.gratuity_paid,s.gratuity_amount,u1.full_name as sender_name,(case 
                when(u1.avatar = "" OR u1.avatar IS NULL OR u1.is_avatar_url = 0) 
                    THEN "'.$this->userDefaultAvatar.'"
                when(u1.is_avatar_url = 2) 
                    THEN u1.avatar
                ELSE
                    concat("'.$this->userAvatarThumb.'", u1.avatar) 
                END 
            ) as sender_user_avatar, u2.full_name as recipient_name');
        $this->db->from(ALERTS.' as a');
        $this->db->join(USERS.' as u1' , 'u1.userID = a.sender_user_id');
        $this->db->join(USERS.' as u2' , 'u2.userID = a.recipient_user_id');
        $this->db->join(SWAPS.' as s' , 's.swapID = a.reference_id' );        //$this->db->join(SWAP_TRANSACTIONS.' as st' , 'st.swapTransactionID = a.parent_id','left' );
        $this->db->where($where);
        $this->db->group_by('alertID');
        $this->db->offset($offset);
        $this->db->limit($limit);
        $this->db->order_by('alertID', 'DESC');

        $res = $this->db->get();
        if(!$res) {

            $this->output_db_error(); //500 error
        }

        $result = $res->result();
        foreach ($result as $key => $value) {
        $result[$key]->looking_car_info = json_decode($value->looking_car_info);
        }
        foreach ($result as $key => $value) {
        $result[$key]->leaving_car_info = json_decode($value->leaving_car_info);
        }
        return $result;
    }

    function getNotificationCount($user_id) { 

        $where = array('recipient_user_id'=>$user_id);
        $this->db->select('*' );
        $this->db->from(ALERTS.' as a');
        $this->db->where($where);
        $res = $this->db->get();
        if(!$res) {

            $this->output_db_error(); //500 error
        }
        $result = $res->result();
        return $res->num_rows(); //total records
    }
}
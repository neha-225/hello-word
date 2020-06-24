<?php

class Payment_model extends MY_Model{

	public function __construct() {

        parent::__construct();
        $this->userDefaultAvatar  =  base_url().USER_DEFAULT_AVATAR;
        $this->userAvatar =  base_url().USER_AVATAR;
        $this->userAvatarThumb =  base_url().USER_AVATAR_THUMB;
    
    }

    function getId($id,$idType) {
        $where = array('user_id'=>$id);
        $this->db->select('*');
        $this->db->from(EXTERNAL_ACCOUNTS.' as external');
        $this->db->where($where);
        $res = $this->db->get();
        if(!$res) {
            $this->output_db_error(); //500 error
        }

        $result = $res->row();
        return $result;

    } 

    function getDefault($userID) { 
        
        $where = array('user_id'=>$userID);
        $this->db->select('*');
        $this->db->from(CARDS.' as cards');
        $this->db->where($where);
        $res = $this->db->get();
        if(!$res) {
            $this->output_db_error(); //500 error
        }

        $result = $res->row();
        return $result;
    } 

    function getAll($userID) { 

        $where = array('user_id'=>$userID);
        $this->db->select('*');
        $this->db->from(CARDS.' as cards');
        $this->db->where($where);
        $res = $this->db->get();
        if(!$res) {
            $this->output_db_error(); //500 error
        }

        $result = $res->result();
        return $result;
    } 

    function getCardId($id) { 

        $where = array('cardID'=>$id);
        $this->db->select('*');
        $this->db->from(CARDS.' as cards');
        $this->db->where($where);
        $res = $this->db->get();
        if(!$res) {
            $this->output_db_error(); //500 error
        }

        $result = $res->row();
        return $result;
    } 


    function getCard($id) { 

        $where = array('stripe_card_id'=>$id);
        $this->db->select('*');
        $this->db->from(CARDS.' as cards');
        $this->db->where($where);
        $res = $this->db->get();
        if(!$res) {
            $this->output_db_error(); //500 error
        }

        $result = $res->row();
        return $result;
    }

    function getSent($limit,$offset,$user_id) { 
        $where = array('st.by_user_id'=>$user_id ,'st.status'=>1);
        $this->db->select('userID, full_name,
            (case 
                when(u.avatar = "" OR u.avatar IS NULL OR u.is_avatar_url = 0) 
                    THEN "'.$this->userDefaultAvatar.'"
                when(u.is_avatar_url = 2) 
                    THEN u.avatar
                ELSE
                    concat("'.$this->userAvatarThumb.'", u.avatar) 
                END 
            ) as user_avatar,swapTransactionID,by_user_id,for_user_id,swap_id,amount,st.status,st.created_at,payment_source,leaving_car_info as car_info'
        );
        $this->db->from(SWAP_TRANSACTIONS.' as st');
        $this->db->join(USERS.' as u','u.userID = st.for_user_id');
        $this->db->join(SWAPS.' as car_info','u.userID = car_info.leaving_user_id');
        $this->db->where($where);
        $this->db->limit($limit);
        $this->db->offset($offset);
        $this->db->limit($limit);
        $this->db->order_by('swapTransactionID', 'DESC');
        $this->db->group_by('swapTransactionID');
        $res = $this->db->get();
        //lq();

        // select * FROM swap_transactions WHERE by_user_id = 353 ORDER BY `swapTransactionID` DESC
    

        if(!$res) {

            $this->output_db_error(); //500 error
        }

        $result = $res->result();
        foreach ($result as $key => $value) {
        $result[$key]->payment_source = json_decode($value->payment_source);
        }
        foreach ($result as $key => $value) {
        $result[$key]->car_info = json_decode($value->car_info);
        }
        // foreach ($result as $key => $value) {
        // $result[$key]->response = json_decode($value->response);
        // }
        
        //pr($payment_source);
        $where = array('by_user_id'=>$user_id ,'st.status'=>1);
        $this->db->select('amount');
        $this->db->select_sum('amount');
        $this->db->from(SWAP_TRANSACTIONS.' as st');
        $this->db->where($where);
        $res = $this->db->get();
        $result1 = $res->row()->amount;
        return array('data'=>$result,'amount'=>$result1);
    }

    function countTransaction($limit,$offset,$user_id) { 

        $where = array('st.by_user_id'=>$user_id ,'st.status'=>1);
        $this->db->select('userID, full_name,
            (case 
                when(u.avatar = "" OR u.avatar IS NULL OR u.is_avatar_url = 0) 
                    THEN "'.$this->userDefaultAvatar.'"
                when(u.is_avatar_url = 2) 
                    THEN u.avatar
                ELSE
                    concat("'.$this->userAvatarThumb.'", u.avatar) 
                END 
            ) as user_avatar,swapTransactionID,by_user_id,for_user_id,swap_id,amount,payment_source,response,st.status,st.created_at'
        );
        $this->db->from(SWAP_TRANSACTIONS.' as st');
        $this->db->join(USERS.' as u','u.userID = st.for_user_id');
        $this->db->where($where);
        $this->db->order_by('swapTransactionID', 'DESC');
        $res = $this->db->get();

        if(!$res) {
            $this->output_db_error(); //500 error
        }
        return  $res->num_rows();
    }

   

    function getReceived($limit,$offset,$user_id) { 

      
        $where = array('sp.for_user_id'=>$user_id,'sp.status'=>1);
        $this->db->select('userID, full_name,
            (case 
                when(u.avatar = "" OR u.avatar IS NULL OR u.is_avatar_url = 0) 
                    THEN "'.$this->userDefaultAvatar.'"
                when(u.is_avatar_url = 2) 
                    THEN u.avatar
                ELSE
                    concat("'.$this->userAvatarThumb.'", u.avatar) 
                END 
            ) as user_avatar,swapPayoutID,by_user_id,for_user_id,swap_id,amount,swap_transaction_id,payout_response,sp.status,sp.created_at,looking_car_info as car_info '
        );
        $this->db->from(SWAP_PAYOUTS.' as sp');
        $this->db->join(USERS.' as u','u.userID = sp.by_user_id');
         $this->db->join(SWAPS.' as car_info','u.userID = car_info.looking_user_id');
        $this->db->where($where);
        $this->db->limit($limit,$offset);
        $this->db->order_by('swapPayoutID', 'DESC');
        $this->db->group_by('swapPayoutID');
        $res = $this->db->get();
        // lq();

        if(!$res) {
            $this->output_db_error(); //500 error
        }
        $result = $res->result();
        $result = $res->result();
        foreach ($result as $key => $value) {
        $result[$key]->payout_response = json_decode($value->payout_response);
        }
        foreach ($result as $key => $value) {
        $result[$key]->car_info = json_decode($value->car_info);
        }
        $where = array('for_user_id'=>$user_id,'sp.status'=>1);
        $this->db->select('amount');
        $this->db->select_sum('amount');
        $this->db->from(SWAP_PAYOUTS.' as sp');
        $this->db->where($where);

        $res = $this->db->get();
        $result1 = $res->row()->amount;
        return array('data'=>$result,'amount'=>$result1);
    }

     function getReceivedCount($limit,$offset,$user_id) { 

      $where = array('sp.for_user_id'=>$user_id,'sp.status'=>1);
        $this->db->select('userID, full_name,
            (case 
                when(u.avatar = "" OR u.avatar IS NULL OR u.is_avatar_url = 0) 
                    THEN "'.$this->userDefaultAvatar.'"
                when(u.is_avatar_url = 2) 
                    THEN u.avatar
                ELSE
                    concat("'.$this->userAvatarThumb.'", u.avatar) 
                END 
            ) as user_avatar,swapPayoutID,by_user_id,for_user_id,swap_id,amount,swap_transaction_id,payout_response,sp.status,sp.created_at,looking_car_info as car_info '
        );
        $this->db->from(SWAP_PAYOUTS.' as sp');
        $this->db->join(USERS.' as u','u.userID = sp.by_user_id');
         $this->db->join(SWAPS.' as car_info','u.userID = car_info.looking_user_id');
        $this->db->where($where);

        $this->db->order_by('swapPayoutID', 'DESC');
        $this->db->group_by('swapPayoutID');
        $res = $this->db->get();
        // lq();
        $result = $res->num_rows();
        return $result;
    }
    function getPlan($userID) { 

        $where = array('status'=>1);
        $this->db->select('*');
        $this->db->from(SUBCRIPTION_PLANS.' as s_plan');
        $this->db->where($where);
        $res = $this->db->get();
        if(!$res) {
            $this->output_db_error(); //500 error
        }

        $result = $res->result();
        return $result;
    }
}

<?php

if(!defined('BASEPATH')) exit('No direct script access allowed');
class Auth extends Common_Service_Controller{

	function __construct() {

		parent::__construct();
		$this->load->model('auth_model');
		$this->load->model('user_model');
		$this->load->library('smtp_email');
		$this->load->library('Stripe');
	}
public function checkSocialSignup_post(){

$social_id = $this->post('social_id');
$social_type = $this->post('social_type');
$firebase_token = $this->post('firebase_token');
if(empty($social_type) OR empty($social_id)) {
$this->error_response('social type or social id required');
}
if(empty($firebase_token)) {
$this->error_response('firebase token required');
}
$email = !empty($this->put('email'))?$this->put('email'):'';

$device_info = array(
'device_type' => $this->request_headers['device-type'],
'device_id' => $this->request_headers['device-id'],
'firebase_token'	=> $firebase_token,
'device_timezone'	=> $this->request_headers['timezone'],
'created_at'	=> datetime(),
'updated_at'	=> datetime()
);

//checking in databse
$checkForExists = $this->auth_model->socialCheck($email,$social_id,$social_type);

//if check for exist is not empty
if(!empty($checkForExists)){

//now checking for social id and social type
$device_info['user_id'] = $checkForExists ;
$where_social_id = '(social_id="'.$social_id.'" AND social_type="'.$social_type.'") ';
$is_exists_id = $this->common_model->get_field_value(SOCIAL_ACCOUNTS, $where_social_id,'user_id');

if(!empty($is_exists_id)) {

$device_info['user_id'] = $is_exists_id ;

$add = $this->auth_model->add_device_info($device_info);
$genrate_token	= $this->general_model->generateToken($is_exists_id,$this->request_headers['device-id']);
$user_info = $this->general_model->getUserDetail($is_exists_id,$this->request_headers['device-id']);
$user_info->auth_token = $genrate_token ;
$responseArray = $this->success_response(get_response_message(121),array('user_details'=>$user_info,'social_status'=>1));

}
//insert a new row in social accounts table
$user_update = array('social_id'=>$social_id,'social_type'=>$social_type,'created_at'=>datetime(),'user_id'=>$checkForExists);
$update_userdata = $this->common_model->insertData(SOCIAL_ACCOUNTS, $user_update);
$add = $this->auth_model->add_device_info($device_info);
$genrate_token	= $this->general_model->generateToken($checkForExists,$this->request_headers['device-id']);
$user_info = $this->general_model->getUserDetail($checkForExists,$this->request_headers['device-id']);
$user_info->auth_token = $genrate_token ;

$responseArray = $this->success_response(get_response_message(121),array('user_details'=>$user_info,'social_status'=>1));
}

$this->success_response(get_response_message(104),['user_details' =>(object)[], 'social_status' => 0]); // 0: Not found, success response

}
}
function socialCheck($email,$social_id,$social_type){
if(empty($email)){

$social_array ='email="'.$email.'" OR (social_id="'.$social_id.'" AND social_type="'.$social_type.'") ';

}
if(!empty($email)) {
$social_array =' email="'.$email.'" OR (social_id="'.$social_id.'" AND social_type="'.$social_type.'") ';

}
$this->db->select('userID');
$this->db->from(USERS,' as users');
$this->db->join(SOCIAL_ACCOUNTS.' as sa', 'users.userID = sa.user_id',"LEFT");
$this->db->where($social_array);
$res = $this->db->get();
if(!$res){
$this->output_db_error(); //500 error
}
$ret = $res->row();
if(!empty($ret)){
return $ret->userID;
}
else
return FALSE;
}
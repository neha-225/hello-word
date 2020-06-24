<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class User extends Common_Service_Controller{

	function __construct() {
		parent::__construct();
		$this->load->model('auth_model');
		$this->load->model('user_model');
		
	}

	/**
	 * change setting   
	 */
	public	function change_password_put() 
	{

		$this->check_service_auth() ; // head info 
		$userData = $this->authData;

		$password =  $userData->password;
		if(empty($this->put('password'))){
			$this->error_response(get_response_message(128)); //error response
		}
		    
		if(empty($this->put('confirm_password'))){
			$this->error_response(get_response_message(130)); //error response
		}

		if($password){

			if(empty($this->put('old_password'))){
				$this->error_response(get_response_message(129)); //error response
		    }

		}

    	if(!empty($password)){
            // password verify
			if(!password_verify($this->put('old_password'), $password)){
  			    $this->error_response(get_response_message(566)); //error response
	        }
	    
			if($this->put('password') != $this->put('confirm_password')) {
  			    $this->error_response(get_response_message(507));
	        }
           
			$where_user   = array('userID' =>$userData->userID);

			$data_update  = array('password'=>password_hash($this->put('password'),PASSWORD_DEFAULT)	,'updated_at'=>datetime());
			
			$response     = $this->common_model->updateFields(USERS, $data_update, $where_user); // update password 
			  $this->success_response(get_response_message(529)); //sucess response
	     	
		}
		else{
			
			if($this->put('password') != $this->put('confirm_password')) {
  			    $this->error_response(get_response_message(507));
	        }
           
			$where_user   = array('userID' =>$userData->userID);

			$data_update  = array('password'=>password_hash($this->put('password'),PASSWORD_DEFAULT)	,'updated_at'=>datetime());
			
			$response     = $this->common_model->updateFields(USERS, $data_update, $where_user); // update password 
			  $this->success_response(get_response_message(529)); //sucess response
		} 
    }

    // Notification settings

    public function alert_settings_patch() {

    	$this->check_service_auth();
    	$userID = $this->authData->userID; 	
    
    	if($this->patch('status') =='') {

			$this->error_response(get_response_message(119)); //error response
		}

		$alert = $this->patch('alert_type');

        if($alert=='' || $alert == 'push') {
       
    	$where = array('userID'=>$this->authData->userID);
	    $update =  array('push_alert_status'=>$this->patch('status')); 
        $this->common_model->updateFields(USERS,$update,$where);
        $this->success_response(get_response_message(123)); // sucess response
		
        } else {
         $this->error_response(get_response_message(131)); // error response

        }
    }

 //    public function updateAvatar_post() {
													
	// 	$this->load->model('Image_model');
 //        $this->check_service_auth(); // authenticate user
 // 		$user_id = $this->authData->userID;
	// 	// if(empty($_FILES['avatar'])) {
	// 	//   $responseArray = $this->error_response(get_response_message(557) ,"PARAM_MISSING");	
	// 	// }

 //        $imageName = 'avatar';
 //        $folder =  "profile";
 //        $this->load->model('image_model');
 //        $response = $this->image_model->upload_image($imageName,$folder);

 //        if(!empty($response['error'])) {
          	
 //        	$responseArray = $this->error_response(get_response_message(558));	
 //    	}
        
 //        $user_id       = $this->authData->userID ;
 //        $where_userid  = array('userID'=>$user_id);
 //        //checking image for unlink
	//     $isExist       = $this->common_model->get_field_value(USERS, $where_userid,'avatar');
        
 //    	if($isExist) {
 //    		$path = base_url('uploads/profile/');
 //    		$this->image_model->delete_image($path,$isExist);
 //    	}
        
 //        $update['updated_at'] =  datetime() ;
 //        $update['avatar']  	  =  $response ;
 //        $update_userdata  	  =  $this->common_model->updateFields(USERS, $update, $where_userid);

 //        $this->success_response(get_response_message(559)); 
	// }

	// car details put api

	public	function car_details_put($id='') {

    	$this->check_service_auth();
    	$user_id = $this->authData->userID;

    	if(empty($this->put('make'))) {

			$this->error_response(get_response_message(600)); //error reponse
		}
		if(empty($this->put('model'))) {

			$this->error_response(get_response_message(601)); //error reponse
		}
		if(empty($this->put('color'))) {

			$this->error_response(get_response_message(602)); //error reponse
		}
		if(empty($this->put('plate_number'))) {

			$this->error_response(get_response_message(603)); //error reponse
		}

		$make = sanitize_input_text($this->put('make'));
        $model= sanitize_input_text($this->put('model'));
        $color = sanitize_input_text($this->put('color'));
        $plate_number = sanitize_input_text($this->put('plate_number'));
       
		if(empty($id)) {

			$isExitscar = $this->user_model->getcarDetail($user_id);
		
			if(empty($isExitscar)) {

				$car_info = array(
				    'user_id'	    => $user_id,
					'make'          => $make,
					'model'         => $model,
					'color'         => $color,
					'plate_number'  => $plate_number,
					'created_at'    => datetime(),
					'updated_at'    => datetime()
				);
		        $result = $this->common_model->insertData(CAR,$car_info);

		        $car_info['carInfoID'] = $result;
	        	$this->success_response(get_response_message(311), ['carinfo' =>$car_info]);
            }
            
            $this->error_response(get_response_message(604),'car details alreay Exits');
        }

  		if(!empty($id)) {
  			
  			$isExits = $this->user_model->getId($id);

			if(!empty($isExits)){

  				$car_info = array(
  					'carInfoID'     => $id,
	  				'user_id'	    => $user_id,
	  		        'make'          => $make,
					'model'         => $model,
					'color'         => $color,
					'plate_number'  => $plate_number,
					'updated_at'    => datetime()
				);

				$where = array('carInfoID'=>$id);
				$res = $this->common_model->updateFields(CAR, $car_info, $where);
				$this->success_response(get_response_message(123), ['carinfo' =>$car_info]);
			}

			$this->error_response(get_response_message(138),'Invalid ID');
		}  
    }

    public	function user_profile_get() {

    	$this->check_service_auth();
    	$user_id = $this->authData->userID;

    	//Get user info
        $user_info = $this->general_model->getUserDetail($this->authData->userID, $this->request_headers['device-id']);
        
        if (!empty($user_info)) {
        	
        $this->success_response(get_response_message(302), array('data_found'=>true,'user_details' =>$user_info));
        }

        $responseArray = $this->success_response(get_response_message(106),array('data_found'=>false));
	}
  
    public function update_profile_post() {

		$this->load->model('Image_model');
        $this->check_service_auth();
 		$user_id = $this->authData->userID;

        $this->form_validation->set_rules('full_name','full_name','required',array('required'=>lang('form_validation_required')));
		$this->form_validation->set_rules('gender','gender','required',array('required'=>lang('form_validation_required')));
		$this->form_validation->set_rules('age','Age','required|numeric',array('required'=>lang('form_validation_required'),'numeric'=>lang('form_validation_numeric')));
		$this->form_validation->set_rules('profile_address','Address','required',array('required'=>lang('form_validation_required')));
		$this->form_validation->set_rules('profile_latitude','profile_latitude','required',array('required'=>lang('form_validation_required')));
		$this->form_validation->set_rules('profile_longitude','profile_longitude','required',array('required'=>lang('form_validation_required')));

    	if($this->form_validation->run() == FALSE) {
  			
  			$responseArray = $this->error_response(strip_tags(validation_errors()));
	    }

 		$where = array('userID'=>$user_id);

	    if(!empty($_FILES['avatar'])) {

	        $imageName = 'avatar';
	        $folder    = "profile";
	        $this->load->model('Image_model');
	        $response  = $this->Image_model->upload_image($imageName,$folder);

	        if(!empty($response['error'])) {
	          	
	          $this->error_response(get_response_message(558));	
	    	}
	    	
	       	$isExist = $this->common_model->get_field_value(USERS, $where,'avatar');
	    		
          	if($isExist) {
	    		$path = 'profile';
	    		$this->Image_model->delete_image($path,$isExist);
    		}

    	    $data['avatar'] = $response['image_name'];
    	    $data['is_avatar_url'] = 1;

        }

        $data['full_name']           = $this->input->post('full_name');
        $data['gender']              = $this->input->post('gender');
        $data['age']                 = $this->input->post('age');
        $data['profile_address']     = $this->input->post('profile_address');
        $data['profile_latitude']    = $this->input->post('profile_latitude');
        $data['profile_longitude']   = $this->input->post('profile_longitude');
        $data['updated_at']          = datetime();
        $gender = array(1, 2); //1:male, 2:female

        if(!in_array( $data['gender'], $gender))
        {
           $this->error_response(get_response_message(563));
        }

       
        $result = $this->common_model->updateFields(USERS,$data,$where);
        if(!empty($result)) {
    
		 //    $device_token  = sanitize_input_text($this->input->post('device_token'));
		 //    $device_info   = array(
			// 	'user_id'            => $user_id,
			// 	'device_type'        => $this->request_headers['device-type'],
			// 	'device_id'          => $this->request_headers['device-id'],
			// 	'device_token'       => $device_token,
			// 	'device_timezone'    => $this->request_headers['timezone'],
			// 	'created_at'         => datetime(),
			// 	'updated_at'         => datetime()
			// );
		 //    $add = $this->auth_model->add_device_info($device_info);

		    $user_info = $this->general_model->getUserDetail($user_id,$this->request_headers['device-id']);
           
            $this->success_response(get_response_message(123),array('user_details'=>$user_info));
		}
          	
        $this->error_response(get_response_message(107));
    }

    public function change_language_patch() {

    	$this->check_service_auth();
    	$userID = $this->authData->userID; 	
    
    	if(empty($this->patch('language'))) {

			$this->error_response(get_response_message(145)); //error reponse 
		}
		$language = sanitize_input_text($this->patch('language'));

		$where_userId = array('userID'=>$userID);
		$is_exists_userId = $this->common_model->get_field_value(USERS, $where_userId,'userID');
	
		if(empty($is_exists_userId)){
			$this->error_response(get_response_message(104)); // error response
   		}
    	$data =  array('profile_language'=>$this->patch('language')); 
        $result= $this->common_model->updateFields(USERS,$data,$where_userId);
        $this->success_response(get_response_message(123),array('language'=>$data));
	}
}    
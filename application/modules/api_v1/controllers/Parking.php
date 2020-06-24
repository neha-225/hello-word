<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Parking extends Common_Service_Controller{

	function __construct() {
		parent::__construct();
		$this->userDefaultAvatar  =  base_url().USER_DEFAULT_AVATAR;
        $this->userAvatar =  base_url().USER_AVATAR;
        $this->userAvatarThumb =  base_url().USER_AVATAR_THUMB;
		$this->load->model('auth_model');
		$this->load->model('user_model');
		$this->load->model('parking_model');
		$this->load->model('notification_model');
	}
    /**
	 * Parking request .     
	 */
	public	function request_post() {

		$this->check_service_auth();
		$user_id = $this->authData->userID;

		// $this->form_validation->set_rules('location','Location','required');
		// $this->form_validation->set_rules('latitude','Latitude','required');
		// $this->form_validation->set_rules('longitude','Logitute','required');

		$this->form_validation->set_rules('type','Type','required',array('required'=>lang('form_validation_required')));

		if($this->form_validation->run() == FALSE) {
  			
  			$responseArray = $this->error_response(strip_tags(validation_errors()));
	    }

	    if(empty($this->input->post('location')) || empty($this->input->post('latitude'))  || empty($this->input->post('longitude'))){
	    	$this->error_response(get_response_message(619));
	    }

	    $info = array(
			'user_id'    => $user_id,
		    'location'   => sanitize_input_text($this->input->post('location')),
		    'latitude'   => sanitize_input_text($this->input->post('latitude')),
		    'longitude'  => sanitize_input_text($this->input->post('longitude')),
		    'type'       => sanitize_input_text($this->input->post('type')),
		    'created_at' => datetime()
		);
	    
		$isExitscar = $this->parking_model->getcarDetail($user_id);

		
		
		if(empty($isExitscar)) {

		$this->error_response(get_response_message(612));

		}


		if($this->input->post('type')==2){

		$isExitsbank = $this->parking_model->getAccountDetail($user_id);

		if(empty($isExitsbank)) {

		$this->error_response(get_response_message(613));

		}

		if($this->authData->stripe_connect_account_verified==0){

			$this->error_response(get_response_message(629));
		}

		}else{

			$isExitscard = $this->parking_model->getCardDetail($user_id);

			if(empty($isExitscard)) {

			$this->error_response(get_response_message(614));

			}
		}

		$type = array(1, 2); // 1:looking, 2:leaving

        if(!in_array($info['type'], $type)) {
        
           $this->error_response(get_response_message(140)); // error response
           
        }

        $where1 = array('looking_user_id'=>$user_id , 'connection_status !=' => 3);

		$isExist1 = $this->common_model->get_field_value(SWAPS, $where1,'looking_user_id');

		if($isExist1) {

			$this->common_model->deleteData(SWAPS,$where1); //delete for looking
		}
  
		$where2 = array('leaving_user_id'=>$user_id, 'connection_status !=' => 3);
		$isExist2 = $this->common_model->get_field_value(SWAPS, $where2,'leaving_user_id');

        if($isExist2){ 

        	//update for leaving
		 	$data['leaving_status']=2;
		 	$this->common_model->updateFields(SWAPS,$data,$where2);
		}

        $where = array('user_id'=>$user_id);
		$isExist = $this->common_model->get_field_value(PARKING, $where,'user_id');

		if($isExist){

			$this->common_model->updateFields(PARKING,$info,$where);
			$this->success_response(get_response_message(123),array('parking_details'=>$info));// success response

		}else{

			$this->common_model->insertData(PARKING,$info);
			//$parking_details = $this->parking_model->getParkingDetail($user_id);
			$this->success_response(get_response_message(320),array('parking_details'=>$info));//success response
		}
	}
    // parking list

	public	function list_get() {

		$this->check_service_auth();// check authorization 
        $user_id = $this->authData->userID;//get user Id
        $lat = $this->get('latitude');
        $lag = $this->get('longitude');
        if(empty($lat) || empty($lag)){
        	$this->error_response(get_response_message(624));
        }
        $parking = $this->parking_model->parking($user_id,$lat,$lag);
        $swapConnection = $this->parking_model->swap_connection($user_id);

        if (!empty($parking)) {
		 	$this->success_response(get_response_message(611),array('data_found'=>true,'swap'=>$swapConnection,'parking_list'=>$parking));// success 
		} elseif(empty($parking) && !empty($swapConnection)) {
		 	$this->success_response(get_response_message(611),array('data_found'=>true, 'swap'=>$swapConnection));// success 
		} else {
			$responseArray = $this->success_response(get_response_message(106),array('data_found'=>false));
		}
		
    }
    // swap a sopt api 
	public	function swap_spot_post() {

    	$this->check_service_auth();// check authorization 
        $car = $this->authData->carinfo;//get car info for looking user
        $user_id = $this->authData->userID;//get user Id
		$this->form_validation->set_rules('leaving_user_id','Leaving UserID','required',array('required'=>lang('form_validation_required')));
		$this->form_validation->set_rules('looking_location','Looking location','required',array('required'=>lang('form_validation_required')));
		$this->form_validation->set_rules('looking_latitude','Looking latitude','required',array('required'=>lang('form_validation_required')));
		$this->form_validation->set_rules('looking_longitude','Looking longitude','required',array('required'=>lang('form_validation_required')));
		$this->form_validation->set_rules('leaving_location','Leaving UserID','required',array('required'=>lang('form_validation_required')));
		$this->form_validation->set_rules('leaving_latitude','Leaving latitude','required',array('required'=>lang('form_validation_required')));
		$this->form_validation->set_rules('leaving_longitude','Leaving longitude','required',array('required'=>lang('form_validation_required')));

		if($this->form_validation->run() == FALSE) {
  			
  			$responseArray = $this->error_response(strip_tags(validation_errors()));
	    }

		$isExitsLookingId = $this->parking_model->getlookingtype($user_id);

		if(empty($isExitsLookingId)){

			$this->error_response(get_response_message(615)); // error response Please select Looking action first before swapping a spot.
		}

		$isExitsLeavingId = $this->parking_model->getleavingtype($this->input->post('leaving_user_id'));

		if(empty($isExitsLeavingId)){
        	$this->error_response(get_response_message(616)); // error response The spot you are trying to swap with does not exists.
        }

		$isExitsSwap = $this->parking_model->getswap($user_id,$this->input->post('leaving_user_id'));
	

        if(!empty($isExitsSwap)) {

			$this->error_response(get_response_message(141)); // error response
		}
        
	    $lookingCarInfo =json_encode($car); 
	    $leavingCarInfo= $this->parking_model->getleavingusercarinfo($this->input->post('leaving_user_id'));//get car info for leaving user
	    $leavingCarInfo = json_encode($leavingCarInfo);

	    $swapInfo = array(
			'looking_user_id'           => $user_id,
		    'leaving_user_id'           => sanitize_input_text($this->input->post('leaving_user_id')),
		    'looking_location'          => sanitize_input_text($this->input->post('looking_location')),
		    'looking_latitude'          => sanitize_input_text($this->input->post('looking_latitude')),
		    'looking_longitude'         => sanitize_input_text($this->input->post('looking_longitude')),
		    'looking_current_latitude'   => sanitize_input_text($this->input->post('looking_latitude')),
		    'looking_current_longitude'   => sanitize_input_text($this->input->post('looking_longitude')),
		    'leaving_location'          => sanitize_input_text($this->input->post('leaving_location')),
		    'leaving_latitude'          => sanitize_input_text($this->input->post('leaving_latitude')),
		    'leaving_longitude'         => sanitize_input_text($this->input->post('leaving_longitude')),
		    'looking_car_info'          => $lookingCarInfo,
		    'leaving_car_info'          => $leavingCarInfo,
		    'created_at' 		        => datetime()
		);

		$result = $this->common_model->insertData(SWAPS,$swapInfo);
 
		$where = array('user_id'=>$user_id);
		$this->common_model->deleteData(PARKING,$where);
		//lq();

        $where1 = array('user_id'=>$this->input->post('leaving_user_id'));
		$this->common_model->deleteData(PARKING,$where1);
		
		$isExitsswap = $this->parking_model->getSwapsId($user_id,$this->input->post('leaving_user_id'));
		//pr($isExitsswap);
		$swap_data = $this->common_model->is_data_exists(SWAPS,array('swapID'=>$isExitsswap->swapID));
		// pr($swap_data);

		$where = array('user_id'=>$isExitsswap->leaving_user_id);
			$isExitsDeviceId = $this->parking_model->allDeviceToken(USER_DEVICES,$where);

			$isExistsUser = $this->common_model->is_id_exist(USERS,'userID',$isExitsswap->looking_user_id); //isexists user id in user table 
            
            //pr($notfification);
				$dataNotifiy['sender_user_id'] = $isExitsswap->looking_user_id;
				$dataNotifiy['recipient_user_id	']= $isExitsswap->leaving_user_id;
				$dataNotifiy['type'] = 'swap_spot';
				$dataNotifiy['reference_id	'] = $isExitsswap->swapID;
				$dataNotifiy['title'] = 'You got a swap parking request.';
				$dataNotifiy['body'] = '{SENDER_NAME} wants to swap parking spot with you';
				$dataNotifiy['parent_type'] = 'swap_spot';
				$dataNotifiy['parent_id']   = $isExitsswap->swapID;
				$dataNotifiy['created_at']  = datetime();
				$dataNotifiy['updated_at']  = datetime();
				$alert_id = $this->notification_model->save_notification($dataNotifiy);

            	$notfification = $this->notification_model-> send_push_notification($isExitsDeviceId, 'You got a swap parking request.',$isExistsUser->full_name.' wants to swap parking spot with you ',$isExitsswap->swapID,'swap_spot',$alert_id,$swap_data,$isExitsswap->looking_user_id);
            	


		$this->success_response(get_response_message(311),array('swap_details'=>$isExitsswap));//success response
	}

	// drive to spot api 

    public function drive_spot_put() {

    	$this->check_service_auth(); // check authorization
    	$user_id = $this->authData->userID; // get user Id from auth data

    	if(empty($this->put('looking_current_latitude'))) {

			$this->error_response(get_response_message(313)); //error reponse 
		}

		if(empty($this->put('looking_current_longitude'))) {

			$this->error_response(get_response_message(314)); //error reponse
		}

		if(empty($this->put('swap_id'))) {

			$this->error_response(get_response_message(621)); //error reponse
		}

		$swap_id = $this->put('swap_id');
		$looking_current_latitude = sanitize_input_text($this->put('looking_current_latitude'));
        $looking_current_longitude= sanitize_input_text($this->put('looking_current_longitude'));
       	$isExitsUserId = $this->parking_model->getUserId($swap_id);

        if(empty($isExitsUserId)){

			$this->error_response(get_response_message(315)); // error response
		}

		if($isExitsUserId->connection_status == 1) {

		    $data['connection_status'] = 2;
			$data['looking_current_latitude'] = $this->put('looking_current_latitude');
			$data['looking_current_longitude'] = $this->put('looking_current_longitude');
			$where = array('looking_user_id'=>$user_id);
			$this->common_model->updateFields(SWAPS,$data,array('swapID'=>$swap_id));
			$isExitsUserId = $this->parking_model->getUserId($swap_id);

			$where = array('user_id'=>$isExitsUserId->leaving_user_id);
			$isExitsDeviceId = $this->parking_model->allDeviceToken(USER_DEVICES,$where);

			$isExistsUser = $this->common_model->is_id_exist(USERS,'userID',$isExitsUserId->looking_user_id); //isexists user id in user table 

            $swap_data = $this->common_model->is_data_exists(SWAPS,array('swapID'=>$isExitsUserId->swapID));

            //pr($notfification);
				$dataNotifiy['sender_user_id'] = $isExitsUserId->looking_user_id;
				$dataNotifiy['recipient_user_id	']= $isExitsUserId->leaving_user_id;
				$dataNotifiy['type'] = 'drive_to_spot';
				$dataNotifiy['reference_id	'] = $isExitsUserId->swapID;
				$dataNotifiy['title'] = 'Driving to spot!';
				$dataNotifiy['body'] = '{SENDER_NAME} started driving to your parking spot {LOCATION}';
				$dataNotifiy['parent_type'] = 'drive_to_spot';
				$dataNotifiy['parent_id']   = $isExitsUserId->swapID;
				$dataNotifiy['created_at']  = datetime();
				$dataNotifiy['updated_at']  = datetime();
				$alert_id = $this->notification_model->save_notification($dataNotifiy);

            	$notfification = $this->notification_model-> send_push_notification($isExitsDeviceId, 'Driving to spot!',$isExistsUser->full_name.' started driving to your parking spot '.$isExitsUserId->leaving_location ,$isExitsUserId->leaving_user_id,'drive_to_spot',$alert_id,$swap_data,$isExitsUserId->looking_user_id);

				$this->success_response(get_response_message(123), ['drive_to_spot_details' => $isExitsUserId]); // success 
		}
       
		if($isExitsUserId->connection_status==2) {

			$data['looking_current_latitude'] = $this->put('looking_current_latitude');
			$data['looking_current_longitude'] = $this->put('looking_current_longitude');
			$whereUserId = array('swapID'=>$swap_id);

			$this->common_model->updateFields(SWAPS,$data,$whereUserId);
			$isExitsUserId = $this->parking_model->getUserId($swap_id);
			
				$this->success_response(get_response_message(123), ['drive_to_spot_details' => $isExitsUserId]); // success response with drive to spot details
		}
	}

	public function swap_connection_close_patch($swap_id) {

    	$this->check_service_auth();// check authorization 
        $user_id = $this->authData->userID;//get user ID
	
		if (empty($swap_id)) {
  			$responseArray = $this->error_response('Swap ID is required');
	    }


	    $swap = $this->common_model->getsingle(SWAPS, $where = array('swapID'=>$swap_id, 'connection_status'=>2)); //Swap exists
	 
	    
        if (empty($swap)) {
			$this->error_response(get_response_message(316)); // swap not found
		}
        $swap->looking_car_info =    json_decode($swap->looking_car_info);
        $swap->leaving_car_info =    json_decode($swap->leaving_car_info);
		//Update connection_status to 'completed'
		$update_where = array('swapID' => $swap_id);
		$update_data = array('connection_status' => 3);
		$this->common_model->updateFields(SWAPS, $update_data, $update_where);


		$where = array('user_id'=>$swap->leaving_user_id);
		$isExitsDeviceId = $this->parking_model->allDeviceToken(USER_DEVICES,$where);

        $isExistsUser = $this->common_model->is_id_exist(USERS,'userID',$swap->looking_user_id); //isexists user id in user table 
  

       	//pr( $notfification);
		$dataNotifiy['sender_user_id'] = $swap->looking_user_id;
		$dataNotifiy['recipient_user_id	'] = $swap->leaving_user_id;
		$dataNotifiy['type'] = 'swap_completed';
		$dataNotifiy['reference_id'] = $swap_id;
		$dataNotifiy['title'] = 'Arrived!';
		$dataNotifiy['body'] = '{SENDER_NAME} arrived at your parking spot {LOCATION}';
		$dataNotifiy['parent_type'] = 'swap_completed';
		$dataNotifiy['parent_id']   = $swap_id;
		$dataNotifiy['created_at']  = datetime();
		$dataNotifiy['updated_at']  = datetime();
		$alert_id = $this->notification_model->save_notification($dataNotifiy);

		$notfification = $this->notification_model->send_push_notification($isExitsDeviceId, 'Arrived!',$isExistsUser->full_name.' arrived at your parking spot '.$swap->leaving_location, $swap_id,'swap_completed',$alert_id);

		$swap_details = $this->parking_model->getSwapDetails($swap_id,$swap->leaving_user_id);
		$this->success_response(get_response_message(123),array('swaps_details'=>$swap_details));//success response with swaps_details
	}

	public	function swap_connection_get() {

		$this->check_service_auth();// check authorization 
        $user_id = $this->authData->userID;//get user Id
        $looking_user_id = $this->get('looking_user_id');
        $leaving_user_id = $this->get('leaving_user_id');
        $swap_connection = $this->parking_model->get_swap_connection_details($looking_user_id,$leaving_user_id);

        if(!empty($swap_connection)){

       $this->success_response(get_response_message(611),array('data_found'=>true,'Swap Connection'=>$swap_connection));//seccess response with updated data
        }
        	$this->success_response(get_response_message(316),array('data_found'=>false)); // error response swap connection not found
    }

	public function pay_gratuity_patch() {

		$this->check_service_auth(); // check authorization
    	$user_id = $this->authData->userID; // get user Id from auth data
    	$card_info = $this->authData->card_info;
        $stripe_cus_id= $this->authData->stripe_customer_id;
        $stripe_card_id = $card_info->stripe_card_id ;


    	if(empty($this->patch('swap_id'))) {

			$this->error_response(get_response_message(317)); //error reponse 
		}

		if(empty($this->patch('gratuity_amount'))) {

			$this->error_response(get_response_message(318)); //error reponse 
		}
		if(empty($this->patch('card_id'))) {

			$this->error_response(get_response_message(319)); //error reponse 
		}

		$swapID = sanitize_input_text($this->patch('swap_id'));
        $gratuityAamount= sanitize_input_text($this->patch('gratuity_amount'));
        $cardId= sanitize_input_text($this->patch('card_id'));

		$isexists = $this->common_model->is_id_exist(SWAPS,'swapID',$swapID); //isexists swap id in swap_connection table 
		if(empty($isexists)){

		    $this->error_response(get_response_message(142)); //error reponse 
		}
		$isExitsbank = $this->parking_model->getAccountDetail($isexists->leaving_user_id);// check bank assount for leaving user 
		//lq();
		$leaving_user_data = $this->common_model->is_data_exists(USERS,array('userID'=>$isexists->leaving_user_id));

		// pr($leaving_user_data);
		if(empty($isExitsbank)) {

		$this->error_response(get_response_message(617));

		}
		$isExitsCard = $this->parking_model->getCard($user_id,$cardId);
		// pr($isExitsCard);
		if (empty($isExitsCard)) {

			$this->error_response(get_response_message(618));

		}
		
		$this->load->library('Stripe');
		$info = $this->stripe->create_charge_new($cardId,$gratuityAamount,$stripe_cus_id, $currency='usd');
		// pr($info);

		$CardInfo =json_encode($isExitsCard);  
		$dataInfo = json_encode($info['data']);
		
        if($info['status']== false) {

           $swapTransactions1 = array(

				'by_user_id'     => $isexists->looking_user_id,
				'for_user_id'    => $isexists->leaving_user_id,
				'swap_id'        => $swapID,
				'amount'         => $gratuityAamount,
				'payment_source' => $CardInfo,
				'response'       => $dataInfo,
				'status'         => 2,
				'created_at'     => datetime()
			);
           $id = $this->common_model->insertData(SWAP_TRANSACTIONS,$swapTransactions1);
           $this->error_response(get_response_message(404));
        }
        if($info['status']==true){

			$wherSwapId = array('swapID'=>$swapID);
			$data = array(
				'gratuity_paid'   => 1,
				'gratuity_amount' => $gratuityAamount
			);



			$this->common_model->updateFields(SWAPS,$data,$wherSwapId); // update data in swaps table

			// Store charge response in `swap_transactions` table
			$swapTransactions = array(

				'by_user_id'     => $isexists->looking_user_id,
				'for_user_id'    => $isexists->leaving_user_id,
				'swap_id'        => $swapID,
				'amount'         => $gratuityAamount,
				'payment_source' => $CardInfo,
				'response'       => $dataInfo,
				'stripe_fee'       => $dataInfo,
				'net_amount'       => $dataInfo,
				'created_at'     => datetime()
			);

			$id = $this->common_model->insertData(SWAP_TRANSACTIONS,$swapTransactions);

			$info_transaction = $this->stripe->retrive_transaction_detail($info['data']['balance_transaction']);
			if($info_transaction['status']!=1){
				$this->error_response(get_response_message(628));
			}
			//==========success update stripe fees and net ammount
			// pr($info_transaction);
			$trans_udate['stripe_fee'] = $info_transaction['data']['fee']/100;
			$trans_udate['net_amount'] = $info_transaction['data']['net']/100;
			$update_trans = $this->common_model->updateFields(SWAP_TRANSACTIONS,$trans_udate,array('swapTransactionID'=>$id));
			//======================================================

			// pr($info_transaction);
			//if suuccess get transaction
			$transfer_charge = $this->stripe->transferCharge($info['data']['id'],$info_transaction['data']['net'],$leaving_user_data->stripe_connect_account_id, $currency='USD');
			// pr($transfer_charge);
			if($transfer_charge['status']!=1){
				//refund process takes place
				$this->common_model->updateFields(SWAP_TRANSACTIONS,array('refund_made'=>1),array('swapTransactionID'=>$id));

				$refund_charge = $this->stripe->refundToCard($info['data']['id']);
				
				$insert_refund['user_id'] = $user_id;
				$insert_refund['swap_id'] = $swapID;
				$insert_refund['swap_transaction_id'] = $id;
				$insert_refund['amount'] = $info_transaction['data']['net']/100;
				$insert_refund['refund_response'] = json_encode($refund_charge['data']);
				$insert_refund['created_at'] = datetime();

				$insert_refund_data = $this->common_model->insertData(SWAP_REFUNDS,$insert_refund);
			}else{

				$payout_detail = $this->stripe->stripe_to_custom_account_transfer($leaving_user_data->stripe_connect_account_id,$info_transaction['data']['net']);
				// pr($payout_detail);
				$AccountInfo = json_encode($isExitsbank);  
			    $swapPayout['by_user_id'] = $isexists->looking_user_id;
				$swapPayout['for_user_id']        = $isexists->leaving_user_id;
				$swapPayout['swap_id']            = $swapID;
				$swapPayout['swap_transaction_id']= $id;
				$swapPayout['account_info']       = $AccountInfo;
				$swapPayout['created_at']         = datetime();
				$swapPayout['amount'] = $info_transaction['data']['net']/100;

				if($payout_detail['status']==1){
					$swapPayout['payout_response']    = json_encode($payout_detail['data']);
					$swapPayout['status']             = 1;

				}else{
					$swapPayout['status']             = 2;
					

				}
				$insert_payout_data = $this->common_model->insertData(SWAP_PAYOUTS,$swapPayout);
			}

	

			$where = array('user_id'=>$isexists->leaving_user_id);
			$isExitsDeviceId = $this->parking_model->allDeviceToken(USER_DEVICES,$where);


            $isExistsUser = $this->common_model->is_id_exist(USERS,'userID',$isexists->looking_user_id); //isexists user id in user table 

            $isExistsUserLocation = $this->common_model->is_id_exist(SWAPS,'leaving_user_id',$isexists->leaving_user_id); //isexists user id in parking table 
            $isExistsUserTransation = $this->common_model->is_id_exist(SWAP_TRANSACTIONS,'swap_id',$isexists->swapID);
           
           
					$dataNotifiy['sender_user_id'] = $isexists->looking_user_id;
					$dataNotifiy['recipient_user_id	']=$isexists->leaving_user_id;
					$dataNotifiy['type'] = 'payment_received';
					$dataNotifiy['reference_id	'] = $isexists->swapID;
					$dataNotifiy['title'] = 'Gift Received!';
					$dataNotifiy['body'] = '{SENDER_NAME} sent you {AMOUNT} for spot at {LOCATION}';
					$dataNotifiy['parent_type'] = 'swap_payment';
					$dataNotifiy['parent_id']   = $isExistsUserTransation->swapTransactionID;
					$dataNotifiy['created_at']  = datetime();
					$dataNotifiy['updated_at']  = datetime();
					$alert_id = $this->notification_model->save_notification($dataNotifiy);


			$notfification = $this->notification_model-> send_push_notification($isExitsDeviceId,'Gift Received!', $isExistsUser->full_name.' sent you '.$isExistsUserTransation->amount.' for spot at '.$isExistsUserLocation->leaving_location,$isexists->swapID,'payment_received',$alert_id);

				$this->success_response(get_response_message(123),array('swaps_details'=>$isexists));//seccess response with charge response

		}
	}
}
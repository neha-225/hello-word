<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Payment extends Common_Service_Controller{

	function __construct() {
		parent::__construct();
		$this->load->model('auth_model');
		$this->load->model('user_model');
		$this->load->model('payment_model');
		$this->load->library('stripe');
	}
    /**
	 * create Bank account using Stripe .     
	 */
	public function bank_account_put($id='') {
		
	    $this->check_service_auth();
    	$userID = $this->authData->userID; // get userID
   
    	if(empty($this->put('account_holder_name'))) {

			$this->error_response(get_response_message(133)); //error reponse
		}

		if(empty($this->put('bank_name'))) {

			$this->error_response(get_response_message(135)); //error reponse
		}

		if(empty($this->put('account_number'))) {

			$this->error_response(get_response_message(136)); //error reponse
		}

		if(empty($this->put('routing_number'))) {

			$this->error_response(get_response_message(137)); //error reponse
		}

        $fname = sanitize_input_text($this->put('account_holder_name'));
        $lname= sanitize_input_text($this->put('last_name'));
        $holderName = $fname .' '.$lname;
        $bank_name = sanitize_input_text($this->put('bank_name'));
        $accountNo = sanitize_input_text($this->put('account_number'));
        $routingNumber = sanitize_input_text($this->put('routing_number'));

		if(empty($id)) {

			$isExits = $this->payment_model->getId($userID,'userID'); //isExits user_id

				
			if(!$isExits) {
				$this->load->library('Stripe');
		        $info = $this->stripe->save_bank_account_id($holderName,$bank_name,$currency='USD',$routingNumber,$accountNo); // crete account on stripe

				if($info) {

		        	$user_info = array(
						'user_id'                     => $userID ,
						// 'stripe_connected_account_id' => $info['data']->id,
						'stripe_external_account_id'  => $info['data']->external_accounts->data[0]->id,
						'account_holder_name'                  => $fname,
						'bank_name'                		  => $bank_name, // 1996-02-24
						'account_number'              => $accountNo,
						'routing_number'              => $routingNumber,
						'created_at'        		  => datetime(),
						'updated_at'        		  => datetime(),
					);
			
		        	$res = $this->common_model->insertData(EXTERNAL_ACCOUNTS,  $user_info); // insert data
		        	$user_info['stripe_connect_account_id'] = $info['data']->external_accounts->data[0]->id;

		        	//update verification key
		        	$stripe_res = $this->stripe->retrive_account_detail($info['data']->id);

			    	// pr($stripe_res['data']);
			    	if ( (empty($stripe_res['data']['requirements']['currently_due']) && empty($stripe_res['data']['requirements']['eventually_due']) ) || 
						((count($stripe_res['data']['requirements']['currently_due']) == 1 && in_array("external_account", $stripe_res['data']['requirements']['currently_due'])) && (count($stripe_res['data']['requirements']['eventually_due']) == 1 && in_array("external_account", $stripe_res['data']['requirements']['eventually_due']) ) ) )  {

			    		//update verification stripe by 1
			    		$this->common_model->updateFields(USERS,array('stripe_connect_account_verified'=>1),array('userID'=>$userID));
			    		

			    	}

			    	//===========================================================

		         	$this->success_response(get_response_message(201), ['account_detail' =>$user_info]); // success response

				}
			}

			// $this->error_response(get_response_message(138),'userID exist'); // error response
			$this->success_response(get_response_message(201), ['account_detail' =>$isExits]); // success response
        }
        
        if(!empty($id)) {

			$isExits = $this->payment_model->getId($userID,'scaid'); //isExits stripe_connected_account_id
			// pr($isExits);
			$accountId = $this->authData->stripe_connect_account_id; // get stripe_connected_account_id
            if(!empty($isExits)) {
           		
				// pr($accountId);
				$this->load->library('Stripe'); // load stripe library
		    	// $where = array('stripe_connected_account_id'=>$id);
		    	$update = $this->stripe->update_bank_account($accountId, $holderName, $bank_name, $currency='USD', $routingNumber, $accountNo); // update  details on stripe
			    	if($update) {

			    		$user_info = array(
			    			'account_holder_name'         => $fname,
							'bank_name'                => $bank_name, // 1996-02-24
							'account_number'     => $accountNo,
							'routing_number'     => $routingNumber,
							'updated_at'         => datetime()
						);

						$where = array('user_id'=>$userID);
						$response  = $this->common_model->updateFields(EXTERNAL_ACCOUNTS, $user_info, $where);
						
							$user_info['user_id'] = $isExits->user_id;
	     					$user_info['stripe_connect_account_id'] = $accountId;
	     					$user_info['stripe_external_account_id'] = $isExits->stripe_external_account_id;
     					
	         			$this->success_response(get_response_message(123), ['account_detail' =>$user_info]); // success response
					}
			}else{

		        // $info = $this->stripe->save_bank_account_id($holderName,$dob,$currency='USD',$routingNumber,$accountNo); // crete account on stripe
		        $info = $this->stripe->update_bank_account($accountId, $holderName, $bank_name, $currency='USD', $routingNumber, $accountNo); // update  details on 

				if($info) {

		        	$user_info = array(
						'user_id'                     => $userID ,
						// 'stripe_connected_account_id' => $info['data']->id,
						'stripe_external_account_id'  => $info['data']->external_accounts->data[0]->id,
						'account_holder_name'                  => $fname,
						'bank_name'                		  => $bank_name, // 1996-02-24
						'account_number'              => $accountNo,
						'routing_number'              => $routingNumber,
						'created_at'        		  => datetime(),
						'updated_at'        		  => datetime()
					);
			
		        	$res = $this->common_model->insertData(EXTERNAL_ACCOUNTS,  $user_info); // insert data
		        	$user_info['stripe_connect_account_id'] = $info['data']->id;

		        	//update verification key
		        	$stripe_res = $this->stripe->retrive_account_detail($info['data']->id);

			    	// pr($stripe_res['data']);
			    	if ( (empty($stripe_res['data']['requirements']['currently_due']) && empty($stripe_res['data']['requirements']['eventually_due']) ) || 
						((count($stripe_res['data']['requirements']['currently_due']) == 1 && in_array("external_account", $stripe_res['data']['requirements']['currently_due'])) && (count($stripe_res['data']['requirements']['eventually_due']) == 1 && in_array("external_account", $stripe_res['data']['requirements']['eventually_due']) ) ) )  {

			    		//update verification stripe by 1
			    		$this->common_model->updateFields(USERS,array('stripe_connect_account_verified'=>1),array('userID'=>$userID));
			    		

			    	}

			    	//===========================================================
		         	$this->success_response(get_response_message(201), ['account_detail' =>$user_info]); // success response

				}
			}

			$this->error_response(get_response_message(620),'INVALID_STRIPE_ID'); // error response
    	}
	}
    // Add to card 

	public function card_post() {

		$this->check_service_auth();
 		$user_id = $this->authData->userID;

		$this->form_validation->set_rules('stripe_card_id','Stripe_card_id','required',array('required'=>lang('form_validation_required')));
		$this->form_validation->set_rules('card_holder_name','FullName','required',array('required'=>lang('form_validation_required')));
		$this->form_validation->set_rules('card_last_4_digits','Card_last_4_digit','required|max_length[4]',array('required'=>lang('form_validation_required'),'max_length'=>lang('form_validation_max_length')));
		$this->form_validation->set_rules('card_expiry_month','Card_expiry_month','required|numeric',array('required'=>lang('form_validation_required'),'numeric'=>lang('form_validation_numeric')));
		$this->form_validation->set_rules('card_expiry_year','Card_expiry_year','required|numeric',array('required'=>lang('form_validation_required'),'numeric'=>lang('form_validation_numeric')));
		$this->form_validation->set_rules('card_brand_type','Card_brand_type ','required',array('required'=>lang('form_validation_required')));

		if($this->form_validation->run() == FALSE) {
  			
  			$responseArray = $this->error_response(strip_tags(validation_errors()));
	    }

	    $data['user_id']      	    = $user_id;
        $data['stripe_card_id']     = $this->post('stripe_card_id');
        $data['card_holder_name']   = $this->post('card_holder_name');
        $data['card_last_4_digits'] = $this->post('card_last_4_digits');
        $data['card_expiry_month']  = $this->post('card_expiry_month');
        $data['card_expiry_year']   = $this->post('card_expiry_year');
        $data['card_brand_type']    = $this->post('card_brand_type');
        $data['created_at']         = datetime();
        $where = array('stripe_card_id'=> $data['stripe_card_id']);
        $isExits = $this->common_model->get_field_value(CARDS, $where,'stripe_card_id');
       
       if( $isExits){
       	$this->error_response(get_response_message(606),'INVALID_ID');

       }
        
      	$isExits = $this->payment_model->getDefault($user_id);

        if($isExits){

			$result = $this->common_model->insertData(CARDS,$data);
	        $this->success_response(get_response_message(311), ['card_info' =>$data]);
		}
		
      	else{

			$data['is_default']	= 1;
			$result = $this->common_model->insertData(CARDS,$data);
	       
	        $this->success_response(get_response_message(311), ['card_info' =>$data]); // success  response 

      	}
    }  

    // create customer on stripe 
    public function create_customer_put() {

    	$this->check_service_auth(); // check autorization 
        $user_id = $this->authData->userID; // get userid 
        $stripe_customer_id = $this->authData->stripe_customer_id;
     
     	if($this->authData->stripe_customer_id!='') { // check stripe id 

    		$this->success_response(get_response_message(611), ['stripe_customer_id' => $stripe_customer_id]); // success response 

		}else{
			
 		$this->load->library('Stripe'); // load stripe Library 
 		$info = $this->stripe->create_customer(); //  call stripe function 
 		
	 		if($info['status']==true){
	             
	 			$customerId= $info['data']['id'];
	 			$where = array('userID'=>$user_id); 
	 			$data = array('stripe_customer_id'=>$customerId);
				$response  = $this->common_model->updateFields(USERS, $data, $where); // update stripe customer id 

	            $this->success_response(get_response_message(201), ['stripe_customer_id' => $customerId]); // success response 
	        }

 	        $this->error_response(get_response_message($info['message'])); // error response
 		}
 	}
    //   get card list 
 	public function card_list_get() {

 		$this->check_service_auth(); // check authorization 
        $user_id = $this->authData->userID; // get user id 
        //Get user infosss
        $where = array('user_id'=>$user_id);
        $user_info= $this->payment_model->getAll($where['user_id']) ; // get all cards
        if(!empty($user_info)){

        	$this->success_response(get_response_message(302),array('data_found'=>true,'cardlist' =>$user_info)); //  success response 
    	}

    	$responseArray = $this->success_response(get_response_message(106),array('data_found'=>false));
            
    }

    // Delete card 
    public function card_delete($id) {

		$this->check_service_auth(); // check authorization 
        $stripe_customer_id = $this->authData->stripe_customer_id; // get stripe customer id


        $isExits = $this->payment_model->getCardId($id); // is exits cardId

 		if($isExits) {

        	if( $isExits->is_default == 1) {

				$this->error_response(get_response_message(607)); // error response 
			}

			
			$this->load->library('Stripe');
			
			$delete = $this->stripe->delete_card($stripe_customer_id,$isExits->stripe_card_id );


			if($delete['status']==true){

			$where = array('cardID'=>$id);	
			$this->common_model->deleteData(CARDS,$where); // delete data 
			$this->success_response(get_response_message(124)); // sucess response 
			}
		} 

		else {

			$this->error_response(get_response_message(608)); //  id not exits error response
		}
	}

	public function card_patch($id) {

		if(empty($id)){

			$this->error_response(get_response_message(608));
		}

		$this->check_service_auth(); // check authorization 
        $user_id = $this->authData->userID; // get user Id
        $stripe_customer_id = $this->authData->stripe_customer_id; // get stripe customer Id  

        $isExits = $this->payment_model->getCard($id);

        if($isExits) {
        	
			$where = array('user_id'=> $isExits->user_id  ,'is_default'=> 1 );
        	$data  = array('is_default'=> 0);
        	$response  = $this->common_model->updateFields(CARDS, $data, $where);

        	$where1 = array('stripe_card_id'=> $isExits->stripe_card_id);
        	$data1 = array('is_default'=> 1);
        	$this->load->library('Stripe');
            
			$default = $this->stripe->default_card($stripe_customer_id,$id );

			if($default['status']==true) {

        	$response  = $this->common_model->updateFields(CARDS, $data1, $where1);
  
        	$this->success_response(get_response_message(609));
        	
        	}
    	}
    	
    	$this->error_response(get_response_message(608));
	}

	public function transactions_get() {

		$this->check_service_auth(); // check authorization 
        $user_id = $this->authData->userID; // get user id 
        $type = $this->get('type');
        $offset = ($this->get('offset')) ? $this->get('offset') : 0;
        $limit = ($this->get('limit')) ? $this->get('limit') : 10;
                        
        $type = array(1, 2); // 1:received, 2;sent
        if(!in_array($this->get('type'), $type)) {
        
           $this->error_response(get_response_message(140)); // error response invalid type
        }

      	if($this->get('type')==1) {

	      	$data['url']	= 'api/v1/payment/transactions';
			$count = $this->payment_model->getReceivedCount($limit,$offset,$user_id);
			$payment = $this->payment_model->getReceived($limit,$offset,$user_id);
			
			$data['offset'] = $offset;
			$data['limit'] = $limit;
			$data['type'] = $this->get('type');
			$data['total_records']	= $count;
			$paging = json_decode(paginationValue($data));
			//pr($paging);
			if(!empty($paging->next)){
			$paging->next = $paging->next.'&type='.$data['type'];
			}

			if(!empty($paging->previous)){
			$paging->previous = $paging->previous.'&type='.$data['type'];
			}

			if(empty($payment['data'])){
			
            $responseArray = $this->success_response(get_response_message(106),array('data_found'=>false));
             }

	        $this->success_response(get_response_message(144),array('data_found'=> true,'paging'=>$paging,'Received_list'=>$payment['data'],'amount'=>$payment['amount'],'total_records'=>$count)); // success response with sent amount

    	} else {

       
        //$payment = $this->payment_model->getSentid('for_user_id');
		$data['url']	= 'api/v1/payment/transactions';
		$count = $this->payment_model->countTransaction($limit,$offset,$user_id);
		$payment = $this->payment_model->getSent($limit,$offset,$user_id);
		//pr($payment['data']);
		
		$data['offset'] = $offset;
		$data['limit'] = $limit;
		//$data['total_records'] = 50;
		$data['type'] = $this->get('type');
		$data['total_records']	= $count;
		$paging = json_decode(paginationValue($data));
		//pr($paging);
		if(!empty($paging->next)){
		$paging->next = $paging->next.'&type='.$data['type'];
		}

		if(!empty($paging->previous)){
		$paging->previous = $paging->previous.'&type='.$data['type'];
		}
		if(empty($payment['data'])){

        $responseArray = $this->success_response(get_response_message(106),array('data_found'=>false));
        }

        $this->success_response(get_response_message(144),array('data_found'=> true,'paging'=>$paging,'sent_list'=>$payment['data'],'amount'=>$payment['amount'],'total_records'=>$count)); // success response with sent amount

    	}
    }
    public function subscription_plans_get() {

    	$this->check_service_auth(); // check authorization 
        $user_id = $this->authData->userID; // get user id 
        $plan_list = $this->payment_model->getPlan($user_id);
        if (empty($plan_list)) {

       		$responseArray = $this->success_response(get_response_message(106),array('data_found'=>false));
        }

        $this->success_response(get_response_message(611),array('data_found'=> true,'plan_list'=>$plan_list));
    }

  //   public function subscription_purchase_post() {

  //   	$this->check_service_auth(); // check authorization         
  //   	$user_id = $this->authData->userID; // get user id 
		// $this->form_validation->set_rules('subscription_plan_id','Subcription Plan ID ','required');
		// $this->form_validation->set_rules('platform_type','Plateform Type ','required');
		// $this->form_validation->set_rules('payment_platform_type',' Payment Plateform Type ','required');
		// $this->form_validation->set_rules('platform_package_name','Plateform Package Name ','required');
		// $this->form_validation->set_rules('platform_product_id','Plateform Product ID','required');
		// $this->form_validation->set_rules('purchase_token','Purchase Token','required');
		// $this->form_validation->set_rules('purchase_info','Purchase Info','required');

		// 	if($this->form_validation->run() == FALSE) {
	  			
	 //  			$responseArray = $this->error_response(strip_tags(validation_errors()));
		//     }
  //   }



    public function account_verification_put($stripe_connect_account_id="") {
		// pr($this->stripe->retrive_account_detail());
	    $this->check_service_auth();
    	$userID = $this->authData->userID; // get userID
    	// pr($stripe_connect_account_id);
        //=============================
        //code for check connected account id
        if(empty($stripe_connect_account_id)){
	        $check_account_id = $this->common_model->is_data_exists(USERS,array('userID'=>$userID));

	        if(empty($check_account_id->stripe_connect_account_id)){
	        	$connect_account_data = $this->stripe->generate_connected_account($holderName='',$dob='',$currency='USD',$routingNumber='110000000',$accountNo='000123456789');

				if(!empty($connect_account_data['data']['id'])){
		      		$data_acount_info['stripe_connect_account_id'] = $connect_account_data['data']['id'];
				}

				$this->common_model->updateFields(USERS, $data_acount_info, array('userID'=>$userID));

	        	$info = $this->stripe->stripe_connect_account_verification($data_acount_info['stripe_connect_account_id']); // crete account on stripe
	        }else{

	        	$info = $this->stripe->stripe_connect_account_verification($check_account_id->stripe_connect_account_id); // crete account on stripe
	        }
	        //verification stripe account 

	        if($info['status']!=true){
	        	$this->error_response($info['message'],'STRIPE_ERROR'); // error response invalid type
	        }else{
	        	
	        	$this->success_response(get_response_message(302), ['stripe_account_link' =>$info['data']]);
	        }

	    }


        $connected_account_data = $this->common_model->is_data_exists(USERS,array('userID'=>$userID,'stripe_connect_account_id'=>$stripe_connect_account_id));

        if(empty($connected_account_data)){
        	$this->error_response(get_response_message(623));
        }

        //checking for kyc verification
        if($connected_account_data->stripe_connect_account_verified==1){
        	$this->error_response(get_response_message(622));
        }

       $info = $this->stripe->stripe_connect_account_verification($connected_account_data->stripe_connect_account_id); // crete account on stripe
       	if($info['status']!=true){
       		// pr($info['message']);
        	$this->error_response($info['message'],'STRIPE_ERROR'); // error response invalid type
        }else{
        	$this->success_response(get_response_message(302), ['stripe_account_link' =>$info['data']]);
        }
        //=====================================================================

	}//end of function

	//function for check verififcation
	function check_account_get(){
		$this->check_service_auth();
    	$userID = $this->authData->userID; // get userID

    	// pr($this->authData);
    	$bank_account = $this->common_model->is_data_exists(EXTERNAL_ACCOUNTS,array('user_id'=>$userID));

    	if(empty($this->authData->stripe_connect_account_id)){
   //  		$connect_account_data = $this->stripe->generate_connected_account($holderName='',$dob='',$currency='USD',$routingNumber='110000000',$accountNo='000123456789');

			// if(!empty($connect_account_data['data']['id'])){
			// 	$this->common_model->updateFields(USERS,array('stripe_connect_account_id'=>$connect_account_data['data']['id']),array('userID'=>$userID));
			// }
    		$this->error_response(get_response_message(627),'NOT_FOUND');
	
    	}

    	if($this->authData->stripe_connect_account_verified==1){

    		$data['stripe_connect_account_verified'] = $this->authData->stripe_connect_account_verified;
    		$data['bank_account'] = $bank_account;
    		$data['stripe_account_data'] = new stdClass();	
    		$this->success_response(get_response_message(625),$data);
    	}


    	$stripe_connect_account_id = $this->common_model->is_data_exists(USERS,array('userID'=>$userID))->stripe_connect_account_id;
    	
    	
    	$stripe_res = $this->stripe->retrive_account_detail($stripe_connect_account_id);

    	// pr($stripe_res['data']);
    	if ( (empty($stripe_res['data']['requirements']['currently_due']) && empty($stripe_res['data']['requirements']['eventually_due']) ) || 
			((count($stripe_res['data']['requirements']['currently_due']) == 1 && in_array("external_account", $stripe_res['data']['requirements']['currently_due'])) && (count($stripe_res['data']['requirements']['eventually_due']) == 1 && in_array("external_account", $stripe_res['data']['requirements']['eventually_due']) ) ) )  {
    		

    		//update verification stripe by 1
    		$this->common_model->updateFields(USERS,array('stripe_connect_account_verified'=>1),array('userID'=>$userID));

    		$data['stripe_connect_account_verified'] = 1;
    		$data['bank_account'] = $bank_account;
    		$data['stripe_account_data'] = $stripe_res['data'];	
    		$this->success_response(get_response_message(625), $data);

    	}

    	
    	$data['stripe_connect_account_verified'] = 0;
		$data['bank_account'] = $bank_account;
		$data['stripe_account_data'] = $stripe_res['data'];	
    	$this->success_response(get_response_message(626), $data);

	}//end of fucntion
}
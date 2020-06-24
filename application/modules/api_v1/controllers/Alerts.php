<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Alerts extends Common_Service_Controller{

	function __construct() {
		parent::__construct();
		$this->userDefaultAvatar  =  base_url().USER_DEFAULT_AVATAR;
        $this->userAvatar =  base_url().USER_AVATAR;
        $this->userAvatarThumb =  base_url().USER_AVATAR_THUMB;
		$this->load->model('alerts_model');
		$this->load->model('notification_model');
	}
    /**
	 * Alerts list .     
	 */

    public function index_get() {

		$this->check_service_auth(); // check authorization 
        $user_id = $this->authData->userID; // get user id 
        $offset = ($this->get('offset')) ? $this->get('offset') : 0;
        $limit = ($this->get('limit')) ? $this->get('limit') : 20;
        $data['url']	= 'api/v1/alerts/';
		$notification = $this->alerts_model->getNotification($limit,$offset,$user_id);
		$count = $this->alerts_model->getNotificationCount($user_id);
		
        $data['offset'] = $offset;
		$data['limit'] = $limit;
		$data['total_records']	= $count;
		$paging = json_decode(paginationValue($data));
		//pr($paging);
		if(!empty($paging->next)){
		$paging->next = $paging->next;
		}

		if(!empty($paging->previous)){
		$paging->previous = $paging->previous;
		}	

		if(empty($notification)){
			
            $responseArray = $this->success_response(get_response_message(106),array('data_found'=>false));
        }

	    $this->success_response(get_response_message(144),array('data_found'=> true,'paging'=>$paging,'alert_list'=>$notification,'total_records'=>$count)); // success response with sent amount
    }
    /*
    *alert read api
    */
    public function index_patch($alert_id) {

    	$this->check_service_auth(); // check authorization 
        $user_id = $this->authData->userID; // get user id 

		if (empty($alert_id)) {
  			$responseArray = $this->error_response('Alert ID is required');
  		}

  		$alert = $this->common_model->getsingle(ALERTS, $where = array('alertID'=>$alert_id)); //alert exists

        if (empty($alert)) {
			$this->error_response(get_response_message(321)); // AlertID not found
		}
       
  		$update_where = array('alertID' => $alert_id);
		$update_data = array('is_read' => 1);
		$this->common_model->updateFields(ALERTS, $update_data, $update_where);

        $alert = $this->common_model->getsingle(ALERTS, $where = array('alertID'=>$alert_id)); //alert exists

		$this->success_response(get_response_message(123),array('alert_read'=>$alert));//success response with read alert
	}
}
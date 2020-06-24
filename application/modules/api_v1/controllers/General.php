<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class General extends Common_Service_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('auth_model');
        $this->load->model('user_model');

    }
    //feedbacks api

    public function feedback_post() {

        $this->check_service_auth(); 
        $user_id = $this->authData->userID; // get userID

        $this->form_validation->set_rules('subject', 'Subject ', 'required|min_length[5]|max_length[100]',array('required'=>lang('form_validation_required'),'min_length'=>lang('form_validation_min_length'),'max_length'=>lang('form_validation_max_length')));
        $this->form_validation->set_rules('description', 'Description', 'required|min_length[5]|max_length[300]',array('required'=>lang('form_validation_required'),'min_length'=>lang('form_validation_min_length'),'max_length'=>lang('form_validation_max_length')));

        if ($this->form_validation->run() == FALSE) {
            $responseArray = $this->error_response(strip_tags(validation_errors())); //validation error
        }

        $where_userId = array('userId' => $user_id);
        $is_exists_userId = $this->common_model->get_field_value(USERS, $where_userId, 'userID'); // is exists user in db
        if (empty($is_exists_userId)) {
            $this->error_response(get_response_message(404)); // error response
        }
        $data['user_id'] = $user_id;
        $data['subject'] = $this->post('subject');
        $data['description'] = $this->post('description');
        $data['created_at'] = datetime();
        $res = $this->common_model->insertData(FEEDBACKS, $data); // insert data 

        if (!$res) {
        	
        	$this->error_response(get_response_message(107)); // error response
        }

        $this->success_response(get_response_message(132), $data); // sucess response
    }
}
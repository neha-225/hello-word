<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Common_Front_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('option_model');
    $this->load->model('home_model');
  }

  public function index(){
    $data['title'] =  ' On-Street Parking spots for the world'  ;
    $this->load->front_render('home',$data);
    
  }
  public function terms(){
    $data['title'] =  'Terms & Conditions'  ;
    $data['result'] = $this->option_model->get_option('terms_content');
    $this->load->front_render('terms',$data);

  }
  
  public function privacy(){
    $data['title'] =  'Privacy Policy'  ;
    $data['result1'] = $this->option_model->get_option('privacy_content');
    $this->load->front_render('privacy',$data);
    
  }
  //  public function aboutUs(){
  //   $data['title'] =  'About Us'  ;
  //   $this->load->front_render('aboutUs',$data);
    
  // }
  public function newslatter(){
    $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
     if($this->form_validation->run($this) == FALSE){
           $msg = (validation_errors()) ? validation_errors() : '';
           //pr($messages);
           $response = array('status' => 0, 'msg' => $msg);
           // echo "hii";die();
        }
        else {
          //pr(base_url().'home');
          $email =strtolower($this->input->post('email'));
          //pr($email);
          $dataInsert['email']    = $email;
          $dataInsert['created_at']= datetime();
          $result = $this->home_model->insertData(NEWSLETTERS,$dataInsert);
          $response = array('status'=>1,'msg'=>'Subscribed successfully.','url'=>base_url());
         
    }
     echo json_encode($response);
  }
}

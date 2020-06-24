<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Common_Front_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('option_model');
    $this->load->model('home_model');
  }
function index(){
   $data['title']  ="category_list"; 
   $data['product']=$this->common_model->getAll(PRODUCT);
    //pr($data);

   $this->load->front_render('home',$data,'');
  } 
    
  public function get_product(){
    $data['title']="subcategory";
    $id=$this->input->get('id');
    //pr($id);
    $data['product1']=$this->home_model->getProduct1(PRODUCT,$id);
    //pr($data['product1']);
      $this->load->front_render('product-details', $data, '');
   }
  public function checkout(){
    $data['title'] =  'checkout'  ;
    
    $this->load->front_render('checkout',$data);

  }
   public function checkoutInsert(){

   $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('first_name', 'first_name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'last_name', 'trim|required');
        $this->form_validation->set_rules('address', 'address', 'trim|required');
        $this->form_validation->set_rules('city', 'city', 'trim|required');
        $this->form_validation->set_rules('phone', 'phone', 'trim|required');

      if($this->form_validation->run($this) == FALSE){
           $msg = (validation_errors()) ? validation_errors() : '';
           //pr($messages);
           $response = array('status' => 0, 'msg' => $msg);
           // echo "hii";die();
        }
        else {
          //pr(base_url().'home');
          //echo "string";
          $email =$this->input->post('email');
          $fname =$this->input->post('first_name');
          $lname =$this->input->post('last_name');
          $phone =$this->input->post('phone');
          $address =$this->input->post('address');
          $city =$this->input->post('city');
          $comment =$this->input->post('comment');
          //pr($email);
          $dataInsert['email']    = $email;
          $dataInsert['first_name']    = $fname;
          $dataInsert['last_name']    = $lname;
          $dataInsert['phone_num']    = $phone;
          $dataInsert['address']    = $address;
          $dataInsert['town']    = $city;
          $dataInsert['comment']    = $comment;
         //pr($dataInsert);
          //pr($dataInsert)
          //$dataInsert['created_at']= datetime();
          $result = $this->home_model->checkout12(CHECKOUT,$dataInsert);
          //lq();
          $response = array('status'=>1,'msg'=>'Checkout successfully.','url'=>base_url());
         
    }
     echo json_encode($response);
  }
  public function privacy(){
    $data['title'] =  'Privacy Policy'  ;
    $this->load->front_render('privacy',$data);
    
  }
  public function addcard(){
    $data['title'] =  'addcard' ;
    $data['result'] = $this->common_model->getAll(ADDCARD);
    $this->load->front_render('addcard',$data);
  }
  public function add_to_card(){

     $id=$this->input->get('id');
     $data['product1']=$this->home_model->getProduct1(PRODUCT,$id);
     
    foreach($data['product1'] as $key ) {
      $dataInsert['product_id']    = $key->id;
      $dataInsert['price']    = $key->price;
      $dataInsert['description']    = $key->description;
      $dataInsert['image']    = $key->image;
    }
      $result = $this->home_model->addtocard(ADDCARD,$dataInsert);
      redirect('home/addcard');
     
    
    
  }
}


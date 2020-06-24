 <?php

class Classctrl extends Common_Back_Controller {

    public function __construct(){

        parent::__construct();
      $this->load->library('session');
      $this->load->model('login_model');
      $this->load->model('User_model');
      $this->load->model('form_model');
  }
  /*view of admin login */
    public function index() {
      $this->check_admin_user_session();
      $data['title'] =  'Admin- Dashboard';
      $this->load->admin_render('class', $data);
    }

     public function form1(){
      //pr($_GET);

      $this->load->library('form_validation');

    
     // $this->form_validation->set_rules('validemail', 'Email', 'required|valid_email');
     $this->form_validation->set_rules('title', 'title', 'required');
     $this->form_validation->set_rules('description', 'description', 'required');
    
        
      if($this->form_validation->run($this) == FALSE){
           $msg = (validation_errors()) ? validation_errors() : '';
           //pr($messages);
           $response = array('status' => 0, 'msg' => $msg);
           // echo "hii";die();
        }
      else { 
       
         //$where_id = $this->input->post('Id');
        //$existing_img = $this->input->post('image');
        
          
            // $data['email']    = $this->input->post('validemail');
            $data['title']    = $this->input->post('title');
            $data['description']    = $this->input->post('description');
           
    if(!empty($_FILES['image'])) {

          $imageName = 'image';
          $folder    = "class";
          $this->load->model('Image_model');
          $response  = $this->Image_model->upload_image($imageName,$folder);

          if(!empty($response['error'])) {
              
             $response = array('status' => 0, 'msg' => ' Updated', 'url' => base_url());
         }
          $data['image'] = $response['image_name'];

          //$data['is_avatar_url']       =  1;

        }

          
          
   
  
    $result  = $this->common_model->insertData('class',$data);

   
           $response = array('status' => 1, 'msg' => 'Successfully Inserted', 'url' => base_url('admin/dashboard'));

        
}
 echo json_encode($response); die; 
}
} //End Class


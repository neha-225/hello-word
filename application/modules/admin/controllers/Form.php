 <?php

class Form extends Common_Back_Controller {

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
      $this->load->admin_render('form', $data);
    }

     public function form1(){
      //pr($_GET);

      $this->load->library('form_validation');

     $this->form_validation->set_rules('model', 'model', 'trim|required');
     // $this->form_validation->set_rules('validemail', 'Email', 'required|valid_email');
        
      if($this->form_validation->run($this) == FALSE){
           $msg = (validation_errors()) ? validation_errors() : '';
           //pr($messages);
           $response = array('status' => 0, 'msg' => $msg);
           // echo "hii";die();
        }
      else { 
       
         //$where_id = $this->input->post('Id');
        //$existing_img = $this->input->post('image');
      $dataInsert = array();
      // $category=$this->input->post('category');
      $subcategory=$this->input->post('product');
      $model=$this->input->post('model');
      $price=$this->input->post('price');
      $description=$this->input->post('description');
     
      $dataInsert['product'] = $subcategory; 
      $dataInsert['model'] = $model; 
      $dataInsert['price'] = $price; 
      $dataInsert['description'] = $description; 

    if(!empty($_FILES['image'])) {

          $imageName = 'image';
          $folder    = "image";
          $this->load->model('Image_model');
          $response  = $this->Image_model->upload_image($imageName,$folder);

          $dataInsert['image'] = $response['image_name'];

          //$data['is_avatar_url']       =  1;

        }

  
$result=$this->common_model->insertData(PRODUCT,$dataInsert);
$response = array('status' => 1, 'msg' => 'Successfully Updated', 'url' => base_url('admin/dashboard'));
// $isExist = $this->common_model->get_field_value(IMAGE, $where,'image');
        
}
 echo json_encode($response); die; 
}
} //End Class


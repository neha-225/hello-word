 <?php

class Admin extends Common_Back_Controller {

    public function __construct(){

        parent::__construct();
      $this->load->library('session');
      $this->load->model('login_model');
      $this->load->model('User_model');
  }
  /*view of admin login */
    public function index() {
    //$this->check_admin_user_session();
    
    $data['title'] =  'Admin login';
    $this->load->admin_render_minimal('admin_login', $data);
        
    }

  /**view of admin login */
    public  function dashboard() {
    $this->check_admin_user_session();
    $data['title'] =  'Admin- Dashboard';
    

    $this->load->admin_render('dashboard', $data);
   
  }
 
 
  /**log in admin */
    public function login(){
        
      $data['title'] =  'Admin login';

    if(!isset($_POST['email']) || !isset($_POST['password'])){

            redirect('admin/login');
      }

        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        
      if ($this->form_validation->run() == FALSE){ 

          $errors = validation_errors();
          $this->session->set_flashdata('login_err', $errors);
          $this->load->admin_render_minimal('admin_login', $data);
      } 
      else { 

          $email    = sanitize_input_text($this->input->post('email'));
          $password = sanitize_input_text($this->input->post('password')); 
          $isLogin  = $this->login_model->isLogin($email,$password, ADMIN_USERS);
         
          if($isLogin == TRUE){
        $data =array('status'=>1,'msg'=>'Logged in successfully. Redirecting...','url'=>base_url().'admin/dashboard');
       
      }else{
        $data=array('status'=>0,'msg'=>'Invalid email address or password');
      }

       echo json_encode($data);
      }
  }
  //END OF FUNCTION

  /**logout admin */
    public function logout(){

    $this->admin_logout($is_redirect=TRUE);
   } 

    public function admin_profile() {
        $this->check_admin_user_session();
        $data['title'] = "Admin profile";
        $where = array('adminUserID'=>$_SESSION[ADMIN_USER_SESS_KEY]['adminUserID']);
        $result = $this->common_model->getsingle(ADMIN_USERS,$where);
        $data['userData'] = $result;
        $this->load->admin_render('admin_profile', $data, '');
    }

    public function admin_update() {
        $this->check_admin_user_session();
        $this->form_validation->set_rules('name','name','trim|required');
        $this->form_validation->set_rules('email','email','trim|required');
        if($this->form_validation->run($this) == FALSE){
           $messages = (validation_errors()) ? validation_errors() : '';
           $response = array('status' => 0, 'message' => $messages);
        }
        else{

            $update_data = array();
            $image = array(); 
            $where_id = $this->input->post('adminUserID');
            $existing_img = $this->input->post('exit_image');

            if (!empty($_FILES['avatar']['name'])) {
                $this->load->model('Image_model');
                $folder = 'user_avatar';
                $image = $this->Image_model->upload_image('avatar',$folder); //upload media of 
            }
            if(array_key_exists("error",$image) && !empty($image['error'])){
                $response = array('status' => 0, 'message' =>$image['error']); 
                echo json_encode($response); die;   
            }      
               
            if(array_key_exists("image_name",$image)){

                $admin_image = $image['image_name'];
                if(!empty($admin_image)){
                    $update_data['avatar'] = $admin_image;
                    $path = 'user_avatar';
                    $this->Image_model->delete_image($path,$existing_img);
                }
            }

            $set = array('name','email');
            foreach ($set as $key => $val) {
                $post= $this->input->post($val);
                $update_data[$val] = (isset($post) && !empty($post)) ? $post :''; 
            }
            $update_where = array('adminUserID'=>$where_id);
            $userId = $this->common_model->updateFields(ADMIN_USERS, $update_data, $update_where);

           
            $u_id = $_SESSION[ADMIN_USER_SESS_KEY]['adminUserID'];
            $user = $this->common_model->getsingle(ADMIN_USERS, array('adminUserID'=>$u_id));
            //update session 

            $_SESSION[ADMIN_USER_SESS_KEY]['name']    = $user->name ;
            $_SESSION[ADMIN_USER_SESS_KEY]['emailId'] = $user->email ;
            $_SESSION[ADMIN_USER_SESS_KEY]['avatar']  = $user->avatar;
            $_SESSION[ADMIN_USER_SESS_KEY]['isLogin'] = TRUE ;
           
            $response = array('status' => 1, 'msg' => 'Successfully Updated', 'url' => base_url('admin/admin_profile'));
           
        }
        echo json_encode($response); die;
    }

    public function changePassword() {
        $this->check_admin_user_session();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('npassword', 'new password', 'trim|required|matches[rnpassword]|min_length[6]');
        $this->form_validation->set_rules('rnpassword', 'retype new password ','trim|required|min_length[6]');

        
        if($this->form_validation->run($this) == FALSE){
           $messages = (validation_errors()) ? validation_errors() : '';
           $response = array('status' => 0, 'msg' => $messages);
        }else {

            $password =$this->input->post('password');
            $npassword =$this->input->post('npassword');
            $select = "password";
            $where = array('adminUserID' => $_SESSION[ADMIN_USER_SESS_KEY]['adminUserID']); 
            $admin = $this->common_model->getsingle(ADMIN_USERS, $where,'password');
            if(password_verify($password, $admin->password)) {
                $set =array('password'=> password_hash($this->input->post('npassword') , PASSWORD_DEFAULT)); 
                $update = $this->common_model->updateFields(ADMIN_USERS, $set, $where);

                if($update){

                    $res = array();
                    if($update){
                        $response = array('status' => 1, 'msg' => 'Successfully Updated', 'url' => base_url('admin/admin_profile'));
                    }else{

                         $response = array('status' => 0, 'msg' => 'Failed! Please try again', 'url' => base_url('admin/admin_profile'));
                        }
                    
                } 
                
            }else{
                 $response = array('status' => 0, 'msg' => 'Your Current Password is Wrong !', 'url' => base_url('admin/admin_profile'));                 
            }
        }
        echo json_encode($response); die;  
    }//End Function


} //End Class
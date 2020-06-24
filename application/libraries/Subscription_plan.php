<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Subscription_plan{

    private $ci;
    public function __construct () {
        $this->ci =& get_instance();
        $this->ios_secret_key = getenv('IOS_SECRET_KEY');
        $this->ci->load->model('common_model');
        
    }
    
    function purchase_plan($purchase_data=array(),$user_id=null){
        // pr($purchase_data);
        $payment_platform_type = $purchase_data['payment_platform_type'];

        $purchase_token = isset($purchase_data['purchase_token'])?$purchase_data['purchase_token']:'';
        // $planDetail=$purchase_data['purchase_info'];

        switch ($payment_platform_type) {
            case "1":
            // case 0 for android
            $platform_package_name = isset($purchase_data['platform_package_name'])?$purchase_data['platform_package_name']:'';
            $android_product_id = $purchase_data['platform_product_id'];

            $verify_google_purchase =  $this->verify_google_purchase($android_product_id,$purchase_token,$platform_package_name);
            // pr($verify_google_purchase);

            if($verify_google_purchase['status']===FAIL){
                return FAIL;
            }
            
            
            $user_subscription_data = $verify_google_purchase['user_subscription_data'];  

                break;
            case "2":
            // case 1 for itune / ios
            $verify_itune_purchase =  $this->verify_itune_purchase($purchase_token);

            if($verify_itune_purchase['status']===FAIL){
                return $verify_itune_purchase;
            }
            
            $user_subscription_data = $verify_itune_purchase['user_subscription_data'];

                break;
            default:
              return "UPT";
        }

        return $user_subscription_data;

    }

    // verify the google purchase token
    function verify_google_purchase($android_product_id,$purchase_token,$platform_package_name)
    { 
            $get_android_receipt_info =  $this->get_android_receipt_info($android_product_id,$purchase_token,$platform_package_name);
           
            if($get_android_receipt_info['status']===FAIL){
                return $get_android_receipt_info;
            }

            $start_date_ms = $get_android_receipt_info['receipt_data']['startTimeMillis'];
            $end_date_ms = $get_android_receipt_info['receipt_data']['expiryTimeMillis'];

            $strat_date = date('Y-m-d H:i:s', $start_date_ms/1000);
            $expiry_date = date('Y-m-d H:i:s', $end_date_ms/1000);

            $user_subscription_data['platform_package_name']=$platform_package_name;
            $user_subscription_data['platform_product_id']=$android_product_id;
            $user_subscription_data['purchase_id']=$get_android_receipt_info['receipt_data']['orderId'];

            $user_subscription_data['start_date']=$strat_date;
            $user_subscription_data['end_date']=$expiry_date;
            $paymentState=isset($get_android_receipt_info['receipt_data']['paymentState'])?$get_android_receipt_info['receipt_data']['paymentState']:'';

            $user_subscription_data['purchase_token']=$purchase_token;
            $user_subscription_data['purchase_details']=json_encode($get_android_receipt_info['receipt_data']);
    

            return  $response = array('status' => TRUE,'user_subscription_data'=>$user_subscription_data );
    }

    // get android receipt info
    function get_android_receipt_info($android_product_id,$purchase_token,$platform_package_name)
    {
        // pr($platform_package_name);
        $access_token = $this->ci->common_model->get_field_value(OPTIONS,array('option_name'=>'gplay_access_token'),'option_value');
        // pr($access_token);
        $verifyPurchaseUrl = getenv('GOOGLE_PURCHASE_URL').
        $platform_package_name."/purchases/subscriptions/".
        $android_product_id."/tokens/".$purchase_token;
             
        // $verifyPurchaseUrl = "https://www.googleapis.com/androidpublisher/v3/applications/".
        // $platform_package_name."/purchases/products/".
        // $android_product_id."/tokens/".$purchase_token;

        // $verifyPurchaseUrl."?access_token=".$access_token;  
        $result=$this->getGooglePurchaseData($verifyPurchaseUrl,$access_token); 
       
          
        if($result['status']!=TRUE && $result['error_code']=='401'){
           $access_token_data= $this->getAuthToken();
           // pr($access_token_data);
           if($access_token_data['status']==true && $access_token_data['access_token']!=''){
              $access_token=$access_token_data['access_token'];
              // $verifyPurchaseUrl."?access_token=".$access_token;  
              $result=$this->getGooglePurchaseData($verifyPurchaseUrl,$access_token);

           }else{
            return  $response = array('status' => FAIL);
           }
        }
        // pr($result);
        if ($result['status']==false && empty($result['receipt_data'])) {
           return $response = array('status' => FAIL);
        } else {
            return $response = array('status' => TRUE, 'receipt_data' =>$result['receipt_data']);
        }
        
    }

    // get getAuthToken
    function getAuthToken()
    {
        
        $clientID = getenv('GOOGLE_CLIENT_ID');
        $clientSecret = getenv('GOOGLE_CLIENT_SECRET');
        $refreshToken = getenv('GPLAY_REFRESH_TOKEN'); //pacakge name
        // pr($refreshToken);
        $ch = curl_init();
        // $TOKEN_URL = "https://accounts.google.com/o/oauth2/token"; //Get access token by using refresh token 
        $TOKEN_URL = getenv('GOOGLE_TOKEN_URL');; //Get access token by using refresh token 

        $input_fields = 'refresh_token='.$refreshToken.
            '&client_secret='.$clientSecret.
            '&client_id='.$clientID.
            '&grant_type=refresh_token';

        //Request to google oauth for authentication
        curl_setopt($ch, CURLOPT_URL, $TOKEN_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $input_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true); // Required for HTTP error codes to be reported via our call to curl_error($ch)
        $result = curl_exec($ch);
        // echo "string";die();
        // pr($result);
        if(curl_errno($ch)) { //check error in curl response
          $error_msg = curl_error($ch);
        }
        if(isset($error_msg)) {
            return  array('status'=>false,'access_token'=>'','message'=>$error_msg);
        } 

        $result = json_decode($result, true);
        if (!$result || !$result["access_token"]) {
            return  $response = array('status' => false,'access_token'=>'', 'message' =>ResponseMessages::getStatusCodeMessage(509));

        }
        //check for data exist 
        $exist = $this->ci->common_model->is_data_exists(OPTIONS, array('option_name'=>'gplay_access_token'));
        if(!$exist){
            $data['option_name']  = 'gplay_access_token';
            $data['option_value'] = $result["access_token"];
            //if not exist insert data
            $result_data = $this->ci->common_model->insertData(OPTIONS,$data);
            
        }else{
            $data['option_value'] = $result["access_token"];
            //if data exist in table update field
            $result_data = $this->ci->common_model->updateFields(OPTIONS,$data,array('option_name'=>'gplay_access_token'));  
        }
        return  array('status'=>TRUE,'access_token'=>$result["access_token"]);
    }  

     // get getAuthToken
    function getGooglePurchaseData($verifyPurchaseUrl,$access_token)
    {
        //request to play store with the access token from the authentication request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
           'Content-Type: application/json',
           'Authorization: Bearer ' . $access_token
        ));
        curl_setopt($ch,CURLOPT_URL,$verifyPurchaseUrl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true); // Required for HTTP error codes to be reported via our call to curl_error($ch)
        $result = curl_exec($ch);
        
        if(curl_errno($ch)) { //check error in curl response
          $error_msg = curl_error($ch);
        }
        if(isset($error_msg)) {
            return  array('status'=>false,'message'=>$error_msg,'error_code'=>curl_getinfo($ch, CURLINFO_HTTP_CODE));
        } 

        $result = json_decode($result, true); 
        // pr($result);
        curl_close($ch);
        return  array('status'=>TRUE,'receipt_data'=>$result);
    }    

    // verify the i tune purchase token
    function verify_itune_purchase($purchase_token=null)
    { 
            $get_ios_receipt_info =  $this->get_ios_receipt_info($purchase_token);

            if($get_ios_receipt_info['status']===FAIL){
                return $get_ios_receipt_info;
            }
            $receipt_data=isset($get_ios_receipt_info['receipt_data']->latest_receipt_info)?end($get_ios_receipt_info['receipt_data']->latest_receipt_info):array();
            if(empty($receipt_data)){
               return  $response = array('status' => FAIL, 'message' =>ResponseMessages::getStatusCodeMessage(509));
            }
            
            $expiryDate = strtotime($receipt_data->expires_date); //expiry date with Etc/GMT
            $expiry_date = date('Y-m-d H:i:s', $expiryDate); //expiry date without Etc/GMT

            if($expiry_date < datetime()){
                return  $response = array('status' => FAIL, 'message' =>ResponseMessages::getStatusCodeMessage(509));
            }
            $stratDate = strtotime($receipt_data->purchase_date); //strat date with Etc/GMT
            $strat_date = date('Y-m-d H:i:s', $stratDate); //strat date without Etc/GMT

            $user_subscription_data['platform_package_name']=$get_ios_receipt_info['receipt_data']->receipt->bundle_id;
            $user_subscription_data['platform_product_id']=$receipt_data->product_id;
            $user_subscription_data['purchase_id']=$receipt_data->transaction_id;
            $user_subscription_data['purchase_token']=$purchase_token;
            $user_subscription_data['purchase_details']=json_encode($get_ios_receipt_info['receipt_data']);
            $user_subscription_data['start_date']=$strat_date;
            $user_subscription_data['end_date']=$expiry_date;
            
            return  $response = array('status' => TRUE,'user_subscription_data'=>$user_subscription_data );
    }

    // verify the i tune purchase token
    function get_ios_receipt_info($purchase_token=null)
    {
        
        $url = getenv('APPLE_PURCHASE_URL');
         // $url = 'https://buy.itunes.apple.com/verifyReceipt';
        $encodedData = json_encode( Array( 
        'receipt-data' =>stripslashes($purchase_token),'password'=>getenv('IOS_SECRET_KEY')) );


        //Open a Connection using POST method, as it is required to use POST method.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        $encodedResponse = curl_exec($ch);
        curl_close($ch);

        $response = json_decode( $encodedResponse );

        if ($response->{'status'} != 0) {
           return $response = array('status' => FAIL, 'message' =>ResponseMessages::getStatusCodeMessage(509));
        } else {
            return $response = array('status' => TRUE, 'receipt_data' =>$response);
        }

    }
    
}
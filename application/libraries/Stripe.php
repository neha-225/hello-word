<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
//require_once(APPPATH.'third_party/stripe/init.php');  //ver 6.8.1
class Stripe{

    public function __construct () {
        $this->ci =& get_instance();
        //$this->ci->config->load('mylib');
        $secret_key = STRIPE_SK;
        $publishable_key = STRIPE_PK;
        Stripe\Stripe::setApiKey($secret_key);
    }
    
    //create token for transaction, this token will be used when creating a customer, This API is generally used when we want process user card info on our own. Otherwise we can use Stripe elements, pre-built UI components, to create a payment form that securely collects your customer’s card information without you needing to handle sensitive card data
    function addCardAccount($name,$number,$exp_month,$exp_year,$cvv){
        
            $success = 0;
            try {
                $result = Stripe\Token::create(
                            array(
                            "card" => array(
                                    "number" => $number,
                                    "exp_month" => $exp_month,
                                    "exp_year" => $exp_year,
                                    "cvc" => $cvv
                                    ) 
                            )
                    ); 
                $success = 1;

            } catch(Stripe_CardError $e) {
                    $error[] = $e->getMessage();
            } catch (Stripe_InvalidRequestError $e) {
                    // Invalid parameters were supplied to Stripe's API
                    $error[] = $e->getMessage();
            } catch (Stripe_AuthenticationError $e) {
                    // Authentication with Stripe's API failed
                    $error[] = $e->getMessage();
            } catch (Stripe_ApiConnectionError $e) {
                    // Network communication with Stripe failed
                    $error[] = $e->getMessage();
            } catch (Stripe_Error $e) {
                    // Display a very generic error to the user, and maybe send
                    // yourself an email
                    $error[] = $e->getMessage();
            } catch (Exception $e) {
                    // Something else happened, completely unrelated to Stripe
                    $error[] = $e->getMessage();
            }

            if ($success != 1){
                $response = array('status'=> FAIL ,'message' => $error);
                print_r(json_encode($response));die();
            }else{
                    if(isset($result['id']) && !empty($result['id'])){
                            return $result['id'];
                    }else{
                            return false;
                    }     
            }        
    }    

    //create customer
    function create_customer($email='', $token = ''){
        
        try{
            $customer = Stripe\Customer::create(array(
              // "email" => $email, 
              // "source" => $token,
            ));
            
            if(!isset($customer->id))
                return array('status'=>false,'message'=>'Something went wrong');
            
            return array('status'=>true,'message'=>'Customer created successfully', 'data'=>$customer); //success
           
        }catch(Exception $e){
            //echo 'dsfsdf';die;
            $message = $e->getMessage();
            return array('status'=>false,'message'=>$message);
        }  
    }
   
    //Charge a customer on basis of customer ID
    function charge_customer($amount, $custId, $currency='USD'){
        
        $amount = round($amount,2); 
        
        try{

            $charge = Stripe\Charge::create(array(
                        "amount" => $amount, //amount in cents, for $1 it should be 100 cents
                        "currency" => $currency,
                        "customer" => $custId
                    ));
            $var = $charge->balance_transaction;
            
            if(!isset($charge->balance_transaction))
                return array('status'=>false,'message'=>'Something went wrong');
            
            return array('status'=>true,'message'=>'Transaction completed successfully', 'data'=>$charge); //success
        }catch(Exception $e){
            $message = $e->getMessage();
            return array('status'=>false,'message'=>$message);
        }
    }
    
    //create a product that represents the service you intend to offer to your customers for subscription 
    function create_product($name, $type='service'){
        try{
            $product = \Stripe\Product::create([
                'name' => $name,
                'type' => $type,
            ]);
            
            if(empty($product))
                return array('status'=>false,'message'=>'Something went wrong');
            
            return array('status'=>true,'message'=>'Product created successfully', 'data'=>$product); //success
            
        }catch(Exception $e){
            $message = $e->getMessage();
            return array('status'=>false,'message'=>$message);
        }
    }
    
    //create a plan which will be attached to a product
    function create_plan($product_id, $interval, $currency,  $amount, $nickname=''){
        try{
            $plan = \Stripe\Plan::create([
                    'product'  => $product_id,
                    'nickname' => $nickname,
                    'interval' => $interval,
                    'currency' => $currency,
                    'amount'   => $amount, //amount in cents, for $1 it should be 100 cents
                  ]);
            
            if(empty($plan))
                return array('status'=>false,'message'=>'Something went wrong');
            
            return array('status'=>true,'message'=>'Plan created successfully', 'data'=>$plan); //success
            
        }catch(Exception $e){
            $message = $e->getMessage();
            return array('status'=>false,'message'=>$message);
        }
    }
    
    //Retrieves the plan with the given ID.
    function get_plan($plan_id){
        try{
            $plan = \Stripe\Plan::retrieve($plan_id);
            if(empty($plan)){
                return array('status'=>false,'message'=>'Something went wrong');
            }
            
            return array('status'=>true,'message'=>'Plan retrieved successfully', 'data'=>$plan); //success
            
        } catch (Exception $e) {
            $message = $e->getMessage();
            return array('status'=>false,'message'=>$message);
        }
    }
    
    //Subscribe the customer to the plan
    function create_subscription($customer_id, $plan_id){
        try{
            $subscription = \Stripe\Subscription::create([
                'customer' => $customer_id,
                'items' => [['plan' => $plan_id]],
            ]);
            
            if(empty($subscription)){
                return array('status'=>false,'message'=>'Something went wrong');
            }
            
            return array('status'=>true,'message'=>'Subscribed successfully', 'data'=>$subscription); //success
            
        }catch (Exception $e) {
            $message = $e->getMessage();
            return array('status'=>false,'message'=>$message);
        }
    }
    
    //Retrieves the subscription with the given ID. Customer who owns the subscription have unique subscription ID.
    function get_subscription($subscription_id){
        try{
            $subscription = \Stripe\Subscription::retrieve($subscription_id);
            
            if(empty($subscription)){
                return array('status'=>false,'message'=>'Something went wrong');
            }
            
            return array('status'=>true,'message'=>'Retrieved successfully', 'data'=>$subscription); //success
            
        }catch (Exception $e) {
            $message = $e->getMessage();
            return array('status'=>false,'message'=>$message);
        }
       
    }
    
    //Cancels a customer’s subscription
    //By default, the cancellation takes effect immediately for $at_period_end = false
    //If you want to cancel the subscription at the end of the current billing period (i.e., for the duration of time the customer has already paid for), provide an at_period_end value of true
    function cancel_subscription($subscription_id, $at_period_end = false){
        try{
            $sub = \Stripe\Subscription::retrieve($subscription_id);
            $subscription = $sub->cancel(['at_period_end' => $at_period_end]);
            if(empty($subscription)){
                return array('status'=>false,'message'=>'Something went wrong');
            }
            
            return array('status'=>true,'message'=>'Retrieved successfully', 'data'=>$subscription); //success
            
        }catch (Exception $e) {
            $message = $e->getMessage();
            return array('status'=>false,'message'=>$message);
        }
    }
    
    function save_bank_account_id($holderName,$dob,$currency,$routingNumber,$accountNo){

        if(!empty($holderName)){
            $names = explode(" ", $holderName);
        }
        
        $dob = explode("-", $dob);
        $success = 0;
        
        try {
            $acct = Stripe\Account::create(array(

                "country" => "US",
                "type" => 'custom',
                "external_account" => array(
                    "object" => "bank_account",
                    "country" => "US",
                    "currency" => $currency,
                    "routing_number" => $routingNumber,
                    "account_number" => $accountNo,
                ),
                "tos_acceptance" => array(
                    "date" => time(),
                    "ip" => $_SERVER['SERVER_ADDR']
                ),
                "requested_capabilities" => array("card_payments", "transfers")
            ));
            $success = 1;
            
        } catch(Stripe_CardError $e) {
            $error[] = $e->getMessage();
        } catch (Stripe_InvalidRequestError $e) {
            // Invalid parameters were supplied to Stripe's API
            $error[] = $e->getMessage();
        } catch (Stripe_AuthenticationError $e) {
            // Authentication with Stripe's API failed
            $error[] = $e->getMessage();
        } catch (Stripe_ApiConnectionError $e) {
            // Network communication with Stripe failed
            $error[] = $e->getMessage();
        } catch (Stripe_Error $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $error[] = $e->getMessage();
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $error[] = $e->getMessage();
        }

        if ($success != 1){
            $response = array('status'=> FAIL ,'message' => $error);
            print_r(json_encode($response));die();
        }else{
            // $acct_id = $acct->id; 
            // $account = Stripe\Account::retrieve($acct_id);
            // $account->legal_entity->dob->year = $dob[0];
            // $account->legal_entity->dob->month = $dob[1];
            // $account->legal_entity->dob->day = $dob[2];
            // $account->legal_entity->first_name = $names[0];
            // $account->legal_entity->last_name = $names[1];
            // $account->legal_entity->type = "individual";

            // //$account->legal_entity->address->line1 = $address;
            // //$account->legal_entity->address->postal_code = $postalCode;
            // //$account->legal_entity->address->city = $city;
            // //$account->legal_entity->address->state = $state;
            // //$account->legal_entity->ssn_last_4 = $ssnLast;

            // $account->save();

            $acct_id = $acct->id; 

            Stripe\Account::update(
                $acct_id,
                array(
                    "business_type"=> "individual",
                    "individual"=> array(
                        "dob" => array(
                            "day"=>$dob[2],
                            "month"=>$dob[1],
                            "year"=>$dob[0],
                        ),
                        "first_name" => $names[0],
                        "last_name" => $names[1],
                    ),
                ) 
            );

            if(isset($acct->id) && !empty($acct->id)){
                return array('status'=>true, 'message'=>'account create successfully', 'data'=>$acct); //success
            }else{
                return array('status'=>false,'message'=>'Stripe connect account not cretaed');
            }
        }
    }
    function create_connected_account( $email,$full_name ){
        $names = explode(" ", $full_name);
        if($names[0]){
        $first_name = $names[0];
        }
        if(isset($names[1])){
        $last_name = $names[1];
        }else {
        $last_name = '';
        }

        try{

        $acct = Stripe\Account::create(array(

        "country" => "US",
        "type" => 'custom',
        "email"=>$email,
        "business_type"=>BUSINESS_TYPE,
        "business_profile" => array(
        "mcc"=> MCC,
        "product_description"=>PRODUCT_DESCRIPTION,
        ),
        "individual" => array(
        "email"=> $email,
        "first_name"=> $first_name,
        "last_name"=> $last_name
        ),
        "requested_capabilities" => array("card_payments", "transfers")
        ));
        // pr($account_links);
        if(empty($acct)){

        return array('status'=>false,'message'=>'Something went wrong');
        }
        return array('status'=>true, 'message'=>'account create successfully', 'data'=>$acct); //success

        }catch (Exception $e) {

        $message = $e->getMessage();
        return array('status'=>false,'message'=>$message,'data'=>$message);
        }

    }

    function update_bank_account($accountId, $holderName, $dob, $currency, $routingNumber, $accountNo)
    {
    
    if (!empty($holderName)) {
        $names = explode(" ", $holderName);
    }
    
    // $dob = '2000-02-02';
    // $dob = explode("-", $dob);
// pr($dob);
    $success = 0;
    try {
        
        $result = Stripe\Account::update($accountId, array(
            "external_account" => array(
                "object" => "bank_account",
                "country" => "US",
                "currency" => $currency,
                "routing_number" => $routingNumber,
                "account_number" => $accountNo,
                "account_holder_name" => $holderName
                
                // "bank_name" => $dob
            )
            // "business_type" => "individual",
            // "individual" => array(
            // //     "dob" => array(
            // //         "day" => $dob[2],
            // //         "month" => $dob[1],
            // //         "year" => $dob[0]
            // //     ),
            //     // "first_name" => $names[0],
            //     // "last_name" => $names[1]
            // )
        ));
        
        $success = 1;
        
    }
    catch (Exception $e) {
        // Something else happened, completely unrelated to Stripe
        $error[] = $e->getMessage();
    }
    
    if ($success != 1) {
        $response = array(
            'status' => FAIL,
            'message' => $error
        );
        print_r(json_encode($response));
        die();
    } else {
        if (isset($result) && !empty($result)) {
            return array(
                'status' => true,
                'message' => 'Account update successfully',
                'data' => $result
            ); //success
        } else {
            return array(
                'status' => false,
                'message' => 'Something went wrong'
            );
        }
    }
    
}

    
    /*
     * Charge a customer on basis of token/source/card
     */
    function create_charge($source, $amount, $currency='usd'){
        
        $amount = round(($amount*100),2); //convert to cents
        
        try{
            
            $charge = \Stripe\Charge::create(array(
                "amount" => $amount,
                "currency" => $currency,
                "source" => $source, //source or token
            ));
            
            if(empty($charge)){
                return array('status'=>false,'message'=>'Something went wrong');
            }
            
            return array('status'=>true, 'message'=>'Charged successfully', 'data'=>$charge); //success
            
        }catch (Exception $e) {

            $message = $e->getMessage();
            return array('status'=>false,'message'=>$message);
        }
    }
    
    /*
     * Transfer fund from main stripe account to custom account (connected account)
     * This is done when admin wants to keep a commission(some % of amount) from a payemnt and pay remaning amount to customer
     * Note: Transfer from connected account to its attached bank account will be done automatically (via Stripe Payout)
     */
    function stripe_to_custom_account_transfer($acc_id, $amount, $currency='usd'){
        
        // $amount = round(($amount*100),2); //convert to cents
        
        try{
            $transfer = \Stripe\Transfer::create( array(
                "amount" => $amount,
                "currency" => $currency,
                "destination" => $acc_id,

            ));
            if(empty($transfer)){
                return array('status'=>false,'message'=>'Something went wrong');
            }
            
            return array('status'=>true, 'message'=>'Funds transfered successfully', 'data'=>$transfer); //success
            
        }catch (Exception $e) {

            $message = $e->getMessage();
            return array('status'=>false,'message'=>$message);
        }
    }
    
    //create a refund
    function refundToCard($chargeId){
        
        try{

            $refund = \Stripe\Refund::create(array(
                      "charge" =>$chargeId 
                    ));
            if(isset($refund ->id) && !empty($refund ->id)){

                return array('status'=>true,'message'=>'ok','data'=>$refund);
            }
        }catch(Exception $e){

            $message = $e->getMessage();
            return array('status'=>false,'message'=>$message,'data'=>'');
        }       
    }
        
       
    function owner_pay_byBankId($data){

        $amount = round($data['amount'],2);
        $secret_key = $this->ci->config->item('secret_key');

        Stripe\Stripe::setApiKey($secret_key);

        $success = 0;
        try{
            $transfer = \Stripe\Transfer::create(array( 
                "amount" => $amount, 
                "currency" => $data['currency'], 
                "destination" => $data['bankAccId'], 
                "description" => "Amount Transfer To Admin",
                "transfer_group" => "APOIM"

            ));

        $success = 1;
            
        } catch(Stripe_CardError $e) {
            $error[] = $e->getMessage();
        } catch (Stripe_InvalidRequestError $e) {
            // Invalid parameters were supplied to Stripe's API
            $error[] = $e->getMessage();
        } catch (Stripe_AuthenticationError $e) {
            // Authentication with Stripe's API failed
            $error[] = $e->getMessage();
        } catch (Stripe_ApiConnectionError $e) {
            // Network communication with Stripe failed
            $error[] = $e->getMessage();
        } catch (Stripe_Error $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $error[] = $e->getMessage();
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $error[] = $e->getMessage();
        }

        if ($success != 1){
            $response = array('status'=> FAIL ,'message' => $error);
            print_r(json_encode($response));die();
        }else{
            if(isset($transfer->balance_transaction) && !empty($transfer->balance_transaction)){                
                return $transfer;
            }else{
                return false;
            }     
        }        
    } 
        
    //update customer token
    function update_customer($customer_id,$attrs=array()){
          $cu = \Stripe\Customer::retrieve($customer_id);
        
        try{

            foreach($attrs as $k => $v){
              $cu->$k = $v;
            }
        
            $upd_status =  $cu->save();
            
            if(empty($upd_status)){
             return false;
            }
            
            return true;
           
        }catch(Exception $e){
            //echo 'dsfsdf';die;
            $message = $e->getMessage();
            return array('status'=>false,'message'=>$message);
        }  
    }

     //Delete Card 

    function delete_card($stripe_customer_id,$cardID){
        try{

            $delCard = Stripe\Customer::deleteSource($stripe_customer_id,$cardID);
            
            if(!$delCard || $delCard->deleted != 1)
                return array('status'=>false,'message'=>'Something went wrong');
            return array('status'=>true,'message'=>'Card deleted successfully', 'data'=>$delCard); //success

        }catch(Exception $e){
            $message = $e->getMessage();
            return array('status'=>false,'message'=>$message);
        } 
    }//end function

    function default_card($stripe_customer_id,$cardID) {
        try{

                $default = \Stripe\Customer::retrieve($stripe_customer_id);
                $default->default_source=$cardID;
                $default->save(); 

                if(isset($default)){
                        return array('status'=>true,'message'=>'ok','data'=> $default);
                }

        }catch(Exception $e) {

                $message = $e->getMessage();
                return array('status'=>false,'message'=>$message,'data'=>'');
        }
    }

    function create_charge_new($source, $amount,$customerId, $currency='USD'){

        $amount = round(($amount*100),2); //convert to cents

        try{

            $charge = \Stripe\Charge::create(array(
                "amount" => $amount,
                "currency" => $currency,
                "customer" => $customerId,
                "source" => $source, //cardId
                )
            );


            if(empty($charge)){
                return array('status'=>false,'message'=>'Something went wrong');
            }

            return array('status'=>true, 'message'=>'Charged successfully', 'data'=>$charge); //success

        }catch (Exception $e) {

            $message = $e->getMessage();
            return array('status'=>false,'message'=>$message,'data'=>$message);
        }
    }

    // Create transfer 
    function transferCharge($source,$amount,$accountId, $currency='USD'){

        // $amount = round(($amount*100),2); //convert to cents

        try{

        $transfer = \Stripe\Transfer::create(array(
            "amount" => $amount,
            "currency" => $currency,
            "source_transaction" => $source,
            "destination" => $accountId,
        ));

        if(empty($transfer)){
            return array('status'=>false,'message'=>'Something went wrong');
        }

        return array('status'=>true, 'message'=>'Transfer successfully', 'data'=>$transfer); //success

        }catch (Exception $e) {

            $message = $e->getMessage();
            return array('status'=>false,'message'=>$message,'data'=>$message);
        }
    }

    //function for onboarding kyc
    function stripe_connect_account_verification($account_id){
        $success_url = base_url('payment/account-verify/success');
        $fail_url = base_url('payment/account-verify/fail');
        
        try{

        $account_links = \Stripe\AccountLink::create([
          'account' => $account_id,
          'failure_url' => $fail_url.'?id='.$account_id,
          'success_url' => $success_url.'?id='.$account_id,
          'type' => 'custom_account_verification',
          'collect' => 'eventually_due',
        ]);

        // pr($account_links);
        if(empty($account_links)){
            
            return array('status'=>false,'message'=>'Something went wrong');
        }
        return array('status'=>true, 'message'=>'Transfer successfully', 'data'=>$account_links); //success

        }catch (Exception $e) {

            $message = $e->getMessage();
            return array('status'=>false,'message'=>$message,'data'=>$message);
        }

    }

    function generate_connected_account($holderName,$dob,$currency,$routingNumber,$accountNo){

        if(!empty($holderName)){
            $names = explode(" ", $holderName);
        }
        
        $dob = explode("-", $dob);
        $success = 0;
        
        try {
            $acct = Stripe\Account::create(array(

                "country" => "US",
                "type" => 'custom',
                "external_account" => array(
                    "object" => "bank_account",
                    "country" => "US",
                    "currency" => $currency,
                    "routing_number" => $routingNumber,
                    "account_number" => $accountNo,
                ),
                "tos_acceptance" => array(
                    "date" => time(),
                    "ip" => $_SERVER['SERVER_ADDR']
                ),
                "requested_capabilities" => array("card_payments", "transfers")
            ));
            $success = 1;
            
        } catch(Stripe_CardError $e) {
            $error[] = $e->getMessage();
        } catch (Stripe_InvalidRequestError $e) {
            // Invalid parameters were supplied to Stripe's API
            $error[] = $e->getMessage();
        } catch (Stripe_AuthenticationError $e) {
            // Authentication with Stripe's API failed
            $error[] = $e->getMessage();
        } catch (Stripe_ApiConnectionError $e) {
            // Network communication with Stripe failed
            $error[] = $e->getMessage();
        } catch (Stripe_Error $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $error[] = $e->getMessage();
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $error[] = $e->getMessage();
        }

        if ($success != 1){
            $response = array('status'=> FAIL ,'message' => $error);
            print_r(json_encode($response));die();
        }else{
           
            if(isset($acct->id) && !empty($acct->id)){
                return array('status'=>true, 'message'=>'account create successfully', 'data'=>$acct); //success
            }else{
                return array('status'=>false,'message'=>'Stripe connect account not cretaed');
            }
        }
    }

    //function for retrive account info

    function retrive_account_detail($connected_account_id){
        // $subscription = \Stripe\Subscription::retrieve($subscription_id);
        try{
           $detail = Stripe\Account::retrieve(
                  $connected_account_id
                );

            if(empty($detail)){
            
                return array('status'=>false,'message'=>'Something went wrong');
            }
            return array('status'=>true, 'message'=>'Retrive successfully', 'data'=>$detail); //success

            }catch (Exception $e) {

                $message = $e->getMessage();
                return array('status'=>false,'message'=>$message,'data'=>$message);
            }
           
    }//end of function

    function retrive_transaction_detail($transaction_id){
        
        try{

            $detail = Stripe\BalanceTransaction::retrieve(
              $transaction_id
            );

        if(empty($detail)){
            
                return array('status'=>false,'message'=>'Something went wrong');
            }
            return array('status'=>true, 'message'=>'Retrive successfully', 'data'=>$detail); //success

            }catch (Exception $e) {

                $message = $e->getMessage();
                return array('status'=>false,'message'=>$message,'data'=>$message);
            }
    }//end of funtion
}


<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends MX_Controller{
	function __construct() {
		parent::__construct();
		 $this->load->library('stripe');

	}
	//function for success page
	function account_verify_success(){
		
		echo "<script>alert('Success')</script>";die();

	}//end of fucntion

	function account_verify_fail(){
		echo "<script>alert('Fail')</script>";die();
		
	}//end of function

}//end of class
?>
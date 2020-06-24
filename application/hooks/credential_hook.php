<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Check whether the site is offline or not.
 * Used when we want to move website in maintenance mode
 */
class Credential_hook {
    
    public function __construct() {
       
    }
    
    public function load_credential() {
        $dotenv = Dotenv\Dotenv::createImmutable(FCPATH);
        $dotenv->load();
    }
    
} //End class
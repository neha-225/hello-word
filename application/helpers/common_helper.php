<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  date_default_timezone_set('UTC');
/**
* Common Helper functions used in app
* version: 2.1 (Last updated: 11-01-2019)
*/

/**
 * [To print array]
 * @param array $arr
*/
if ( ! function_exists('pr')) {
  function pr($arr)
  {
    echo '<pre>'; 
    print_r($arr);
    echo '</pre>';
    die;
  }
}

/**
 * [To print last query]
*/
if ( ! function_exists('lq')) {
  function lq()
  {
    $CI = & get_instance();
    echo $CI->db->last_query();
    die;
  }
}

/**
 * [To get database error message]
*/
if ( ! function_exists('db_err_msg')) {
  function db_err_msg()
  {
    $CI = & get_instance();
    $error = $CI->db->error();
    if(isset($error['message']) && !empty($error['message'])){
      return 'Database error - '.$error['message'];
    }else{
      return FALSE;
    }
  }
}


/**
 * [To validate mail]
*/
if ( ! function_exists('is_valid_mail')) {
  function is_valid_mail($email)
  {

        return (!preg_match( 
"^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)) 
        ? FALSE : TRUE; 

  }
}


/**
 * [To validate email]
*/
if ( ! function_exists('is_valid_phone_number')) {
  function is_valid_phone_number($str)
      {  

          if(preg_match('/^[0-9 + -]+$/', $str)=== 0)
          {
             
              return FALSE;
          }elseif(strlen($str) < 6  ){

          }
          else{
              return TRUE ;
          }
      }
}

/**
 * [To get current datetime]
*/
if ( ! function_exists('datetime')) {
  function datetime($default_format='Y-m-d H:i:s')
  {
    $datetime = date($default_format);
    return $datetime;
  }
}


/**
 * [To get randome num]
*/
if ( ! function_exists('get_random_code')) {
  function get_random_code()
  {
    $verificationCode = substr(mt_rand(), ZERO, FIVE);//create varification 
    return $verificationCode;
  }
}
/**
 * [To get password num]
*/
if ( ! function_exists('get_password')) {
  function get_password()
  {
    $genrate_password = substr(mt_rand(), ZERO, SIX);//create varification 
    return $genrate_password;
  }
}

/**
 * [To encode string]
 * @param string $str
*/
if ( ! function_exists('encoding')) {
  function encoding($str){
      $one = serialize($str);
      $two = @gzcompress($one,9);
      $three = addslashes($two);
      $four = base64_encode($three);
      $five = strtr($four, '+/=', '-_.');
      return $five;
  }
}

/**
 * [To decode string]
 * @param string $str
*/
if ( ! function_exists('decoding')) {
  function decoding($str){
    $one = strtr($str, '-_.', '+/=');
      $two = base64_decode($one);
      $three = stripslashes($two);
      $four = @gzuncompress($three);
      if ($four == '') {
          return "z1"; 
      } else {
          $five = unserialize($four);
          return $five;
      }
  }
}

/**
 * [To check number is digit or not]
 * @param int $element
*/
if ( ! function_exists('is_digits')) {
  function is_digits($element){ // for check numeric no without decimal
      return !preg_match ("/[^0-9]/", $element);
  }
}

/**
 * [To get all months list]
*/
if ( ! function_exists('getMonths')) {
  function getMonths(){
    $monthArr = array('January','February','March','April','May','June','July','August','September','October','November','December');
    return $monthArr ;
  }
}

/**
 * Load styles for frontend or admin on specific pages
 * Modified in ver 2.0
 */
if (!function_exists('load_css')) {
    
    function load_css($css){

        if(!is_array($css) || count($css)>20){
            return;
        }
        $style_tag = $css_base_path = '';

        foreach($css as $style_src){

            if(strpos($style_src, 'http://') === false && strpos($style_src, 'https://') === false){
                $css_base_path = base_url() . $style_src;
            }

            $style_tag .= "<link href=\"{$css_base_path}\" rel=\"stylesheet\">\n";
        }
        echo $style_tag; //print style tags
    }
}

/**
 * Load scripts for frontend or admin on specific pages
 * Modified in ver 2.0
 */
if (!function_exists('load_js')) {

    function load_js($js=''){
        
        if(!is_array($js) || count($js)>20){
            return;
        }
        $script_tag = $js_base_path = '';

        foreach($js as $script_src){

            if(strpos($script_src, 'http://') === false && strpos($script_src, 'https://') === false){
                $js_base_path = base_url() . $script_src;
            }

            $script_tag .= "<script src=\"{$js_base_path}\"></script>\n";
        }

        echo $script_tag; //print script tags
    }
}

/**
 * For making alias of title or any string
 * Modified in ver 2.0
 */
if (!function_exists('make_alias')) {

    function make_alias($string){
        $string = strtolower(str_replace(' ', '_', $string)); // replace space with underscore
        $alias = preg_replace('/[^A-Za-z0-9]/', '', $string); // remove specail characters
        return $alias;
    }
}

/**
 * Check is string contains any special characters
 */
if (!function_exists('alpha_spaces')) {

    function alpha_spaces($string){
        if (preg_match('/^[a-zA-Z ]*$/', $string)) {
            return TRUE;
        }
        else{
            return FALSE; //match failed(string contains characters other than aplhabets and spaces)
        }
    }
}

/**
 * Display placeholder text when string is empty
 */
if (!function_exists('display_placeholder_text')) {

    function display_placeholder_text($string=''){
        if (empty($string)) {
            return 'NA'; //if string is empty return placeholder text
        }
        else{
            return $string;  //return string as it is
        }
    }
}

/**
 * Display elapsed time as user friendly string from timestamp
 */
if (!function_exists('time_elapsed_string')) {
    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hr',
            'i' => 'min',
            's' => 'sec',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
     }//End Function
}

/**
 * Make user profile image url from name or check if string already has url
 */
if (!function_exists('make_img_url')) {
    function make_user_img_url($img_str) {
        if (!empty($img_str)) { 
            //check if image consists url- happens in social login case
            if (filter_var($img_str, FILTER_VALIDATE_URL)) { 
                $img_src = $img_str;
            }
            else{
                $img_src = base_url().USER_AVATAR_PATH.$img_str;
            }
        }
        else{
            $img_src = base_url().USER_DEFAULT_AVATAR; //return default image if image is empty
        }
        
        return $img_src;
    }
}

/**
 * Validates a given latitude $lat
 *
 * @param float|int|string $lat Latitude
 * @return bool `true` if $lat is valid, `false` if not
 */
if (!function_exists('validateLatitude')) {
  function validateLatitude($lat) {
    return preg_match('/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/', $lat);
  }
}

/**
 * Validates a given longitude $long
 *
 * @param float|int|string $long Longitude
 * @return bool `true` if $long is valid, `false` if not
 */
if (!function_exists('validateLongitude')) {
  function validateLongitude($long) {
    return preg_match('/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/', $long);
  }
}

/**
 * Make log of any event/action in destination file
 * Modified in ver 2.0
 */
if (!function_exists('log_event')) {
    
    function log_event($msg, $file_name='') {
        
        $log_path = APPPATH.'logs/'; //path for logs directory
        if(empty($file_name)){
            $file_path = $log_path.'common_log.txt'; //if file name is not defined then it will be logged in common file
        }else{
            $file_path = $log_path.$file_name;
        }

        $perfix = '['.datetime().'] ';  //add current date time
        $msg = $perfix.$msg."\r\n"; //create new line
        error_log($msg, 3, $file_path); //log message in file
    }
}
 /**
 * Make uniq user name 
 * Param is name
 * this function take user fullname and generate uniq user name.
 * @param     string name ,
 * return uniq user name
 */
  if (!function_exists('generate_username')) {
    function generate_username($name, $digits=3) {
             
        do {
            //remove spaces, special charaters from name and make all characters in small case
            $fullName = filtername($name);
            $un_part1 = substr($fullName, 0, 6); //username part1 - take first 6 characters of name
            //username part1 - Generate random 3 digits number
            //This will output a number between 100 (10^2) and 999 (10^3) 
            $un_part2 = rand(pow(10, $digits-1), pow(10, $digits)-1);

            //make username by combining both parts
            $user_name = $un_part1.$un_part2;
            //check the generated user name exists in DB (returns TRUE/FALSE)
            $CI = get_instance();
            // You may need to load the model if it hasn't been pre-loaded
            $CI->load->model('general_model');
            $username_exist_in_db = $CI->general_model->check_username_exists($user_name);

        } while($username_exist_in_db === TRUE);

        return $user_name;
    }
  }

  
  /**
 *  To filter username
 */
  if (!function_exists('filtername')) {
    function filtername($name) {
      $filter_name = strtolower(preg_replace("/[^a-zA-Z]/", "", $name));
      return $filter_name ;

    }
  }

  /**
 *  To filter username
 */
  if (!function_exists('validUsername')) {
     function validUsername($username) {

      $pattern = "/^[a-z0-9]+$/";
        if(preg_match($pattern, $username) && strlen($username) <= 15 && strlen($username) >= 3)
        {
           return true ;
        }
        return false ;
     }
  }

/**
 *  To force browser load new file from server (Prevent caching of file)
 *  Given a file, i.e. /css/base.css, replaces it with a string containing the
 *  file's mtime, i.e. /css/base.1221534296.css.
 *  
 *  @param $file_path  The file to be loaded.  Must be an absolute path (i.e. starting with slash).
 *  Rewrite rules written in htaccess
 */
function auto_version($file_path){
    
    $asset_path =  FCPATH.'frontend_asset';  //get absolute server path
    $mtime = filemtime($asset_path.$file_path); //get last modified file time
   
    if(strpos($file_path, '/') !== 0 || !$mtime)
        return $file_path;
    
    return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file_path);
}


/* CSRF and XSS protection helper methods start */

/**
 * Cross Site Scripting prevention filter before saving/processing data
 * Added in ver 2.0
 */
function sanitize_input_text($str){
    $CI = & get_instance();  // get instance, access the CI superobject
    return $CI->security->xss_clean($str);  //security library must be autoloaded
}

/**
 * Cross Site Scripting prevention filter while output data
 * Certain characters have special significance in HTML into their corresponding HTML entities
 * Added in ver 2.0
 */
function sanitize_output_text($str){
    return htmlspecialchars($str);
}

/**
 * Get CSRF (Cross-site request forgery) token key-value array
 * Added in ver 2.0
 */
function get_csrf_token(){
    $CI = & get_instance();  // get instance, access the CI superobject
    $csrf = array(
        'name' => $CI->security->get_csrf_token_name(),  //csrf token key
        'hash' => $CI->security->get_csrf_hash()  //csrf token value
    );
    return $csrf;
}
/* CSRF and XSS protection helper methods end */

/* User Session management methods start */
/**
 * Returns app logout url
 * Added in ver 2.0
 */
function app_logout_url(){
    return base_url('home/logout'); //can be changed depending upon application url
}

/**
 * Check if user is logged in
 * Added in ver 2.0
 */
function is_user_logged_in(){
    
    if(!isset($_SESSION[USER_SESS_KEY]))
        return FALSE;
    
    $user_sess_data = $_SESSION[USER_SESS_KEY]; //user session array
    if( !empty($user_sess_data) &&  $user_sess_data['userId']) {
       return TRUE;
    }
    return FALSE;  
}

/**
 * Check if admin user is logged in
 * Added in ver 2.0
 */
function is_admin_logged_in(){

    if(!isset($_SESSION[ADMIN_USER_SESS_KEY]))
        return FALSE;

    $admin_user_sess_data = $_SESSION[ADMIN_USER_SESS_KEY]; //admin user session array  
    if( !empty($admin_user_sess_data) &&  $admin_user_sess_data['adminUserID']) {
       return TRUE;
    }
    return FALSE;  
}

/**
 * Get logged in user data
 * Added in ver 2.0
 */
function get_user_session_data(){
    $user_data = '';
    if(is_user_logged_in()){
        $user_data = $_SESSION[USER_SESS_KEY]; //user session array
    }
    return $user_data;
}

/**
 * Get logged in admin user data
 * Added in ver 2.0
 */
function get_admin_session_data(){
    $admin_user_data = '';
    if(is_admin_logged_in()){
        $admin_user_data = $_SESSION[ADMIN_USER_SESS_KEY]; //admin user session array
    }
    return $admin_user_data;
}

/* User Session management methods end */

/**
 * Removes extra white spaces from string
 * Added in ver 2.1
 */
function remove_extra_space($str){
    $str = preg_replace( '/\s+/', ' ', $str );
    return $str;
}

/**
 * Returns json data
 * Added in ver 2.1
 */
function get_json_output($data){
    header('Content-type:application/json;charset=utf-8');
    return json_encode($data);
}

/**
 * Output json data and exit
 * Added in ver 2.1
 */
function json_output($data){
    header('Content-type:application/json;charset=utf-8');
    return json_encode($data); exit;
}

/*****  Any new project specific helper method can be added below  *****/
function get_duration(){
  $array = [
    ["id"=>1, "name"=>"1", "value"=> 'Day'],
    ["id"=>2, "name"=>"2", "value"=> 'Week'],
    ["id"=>3, "name"=>"3", "value"=> 'Month'],
    ["id"=>4, "name"=>"4", "value"=> 'Year'],
    ["id"=>5, "name"=>"5", "value"=> 'Forever']
  ];
  return $array;
}
function getAllStatus(){
  $array = [
    ["id"=>0, "name"=>"0", "value"=> 'All'],
    ["id"=>1, "name"=>"1", "value"=> 'Success'],
    ["id"=>2, "name"=>"2", "value"=> 'Fail'],
    
  ];
  return $array;
}

//function for paging
function paginationValue($value){

    $nextOffset = $value["limit"]+$value["offset"];
    $previousOffset = $value["limit"]-$value["offset"];
    // pr($previousOffset);
    $nextLink = base_url().$value['url'].'?limit='.$value["limit"].'&offset='.$nextOffset.'';

    $previousLink = base_url().$value['url'].'?limit='.$value["limit"].'&offset='.$previousOffset.'';

    if($value['offset'] == 0 && ($value['total_records'] > $value["limit"])){ //If on first page and 2nd page data exist then previous link should not show next link should show

    $arr = array('limit' => $value['limit'], 'offset' => $value['offset'], 'next' => $nextLink); 
    }
    else if($value['offset'] == 0 && ($value['total_records'] <= $value["limit"])){ //If on first page and 2nd page data not exist then previous and next link should not show 

    $arr = array('limit' => $value['limit'], 'offset' => $value['offset']); 
    }
    else if($value['offset'] == ($value['total_records'] -1)){ //If on last page then next link should empty

    $arr = array('limit' => $value['limit'], 'offset' => $value['offset'], 'previous' => $previousLink); 

        // pr($previousLink);
    }else{
    $arr = array('limit' => $value['limit'], 'offset' => $value['offset'], 'previous' => $previousLink, 'next' => $nextLink); 
    }

    return json_encode($arr); die;
}
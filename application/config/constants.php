<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

//error types
define('INVALID_HEADER_VALUE', "INVALID_HEADER_VALUE");
define('HEADERS_MISSING', "HEADERS_MISSING");
define('USER_NOT_FOUND', "USER_NOT_FOUND");
define('INVALID_PARAM_VALUE', "INVALID_PARAM_VALUE");
define('INVALID_TOKEN', "INVALID_TOKEN");
define('SESSION_EXPIRED', "SESSION_EXPIRED");
define('ACCOUNT_DISABLED', "ACCOUNT_DISABLED");

//HTTP codes
define('BAD_REQUEST', 400);
define('ACCESS_DENIED', 403);
define('NOT_FOUND', 404);
define('SERVER_ERROR', 500);
define('OK', 200);

//General
define('FAIL', "error");
define('SUCCESS', "success");
define('SITE_NAME', "Template");
define('COPYRIGHT','product &copy; ' . date('Y') . ' - ' . date("Y",strtotime("+1 year")). ' All Rights Reserved .');
define('PARKSWAP_LOGO', 'backend_asset/img/');
define('DELETE_ICON', '<i class="fa fa-trash-o" aria-hidden="true"></i>');
define('ACTIVE_ICON', '<i class="fa fa-check" aria-hidden="true"></i>');
define('INACTIVE_ICON', '<i class="fa fa-times" aria-hidden="true"></i>');
define('VIEW_ICON', '<i class="fa fa-eye" aria-hidden="true"></i>');
define('EDIT_ICON', '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>');


// Database table name
define('USERS', 'users');
define('USER_DEVICES', 'user_devices');
define('CAR', 'car_info');
define('ADMIN_USERS', 'admin_users');
define('IMAGE', 'image');
define('CONTECTS', 'contact_us');
define('OUR', 'our_trainers');
define('CLASS', 'class');
define('PRODUCT', 'product');
define('ADDCARD', 'add_to_card');
define('CHECKOUT', 'checkout');


//SMTP credentials
define('SMTP_HOST', getenv("SMTP_HOST"));
define('SMTP_USER', getenv("SMTP_USER"));
define('SMTP_PASSWORD', getenv("SMTP_PASSWORD"));

//notification credentials
define('CATEGORY_PATH', 'uploads/image/');

//define('NOTIFICATION_KEY', getenv("NOTIFICATION_KEY"));

//assets
define('ADMIN_ASSETS_IMG', 'backend_asset/img/');
define('ADMIN_ASSETS_CSS', 'backend_asset/css/');
define('ADMIN_ASSETS_JS', 'backend_asset/js/');
define('ADMIN_ASSETS_CUSTOM_CSS', 'backend_asset/custom/css/');
define('ADMIN_BOWER',   'backend_asset/bower_components/');
define('ADMIN_PLUGIN',   'backend_asset/plugins/');
define('ADMIN_JS',   'backend_asset/custom/js/');
define('BACKEND_ASSET',   'backend_asset/');
//front assets
define('FRONTEND_ASSETS', 'frontend_asset/');
//notification 

define('MCC', 5399);
define('PRODUCT_DESCRIPTION', 'Account registered on theparkswap.com application to receive gifts(money) from other members in my bank account via app platform.');
define('BUSINESS_TYPE', 'individual');




//Stripe credentials
define('STRIPE_PK', 'pk_test_PrlCqTlXrT0rPHr6AN3qE30100BQlD7rm0');
define('STRIPE_SK', 'sk_test_n76LgD2E1AWBgNHP6OpozUzq00oFJBmgeu');

define('USER_DEFAULT_AVATAR', 'frontend_asset/images/placeholders/user_placeholder.png')
; //user placeholder image

define('USER_AVATAR', 'uploads/profile/'); //user avatar
define('ADMIN_AVATAR', 'uploads/user_avatar/'); //user avatar
define('USER_AVATAR_THUMB', USER_AVATAR.'thumb/'); //user avatar thumb
define('USER_AVATAR_MEDIUM', USER_AVATAR.'medium/'); //user avatar medium

// genrate password
define('ZERO', 0);
define('SIX', 6);

//image model
define('AWS_BUCKET_KEY','');
define('AWS_BUCKET_SECRET','');
define('AWS_BUCKET_REGION','');

//trial subscription 
define('TRAIL_PERIOD_TYPE','month');
define('TRAIL_PERIOD',1);


/* session keys */ 
define('ADMIN_USER_SESS_KEY', 'product_admin_user_sess<script src="<?php echo base_url().ADMIN_PLUGIN; ?>jquery-validation/jquery.validate.min.js"></script>');
define('USER_SESS_KEY', 'product_user_sess'); 
//define('ADMIN_USER_SESS_KEY', 'app_admin_user_sess');

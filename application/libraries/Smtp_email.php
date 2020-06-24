<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//require APPPATH . "third_party/phpmailer/class.phpmailer.php"; //php smtp mailer library

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Smtp_email{
//protected $CI;
    //US: noreplay@theparkswap.com
    //PWD: jfRNU3ODY3_D
      
    var $host  = '', 
    $user_name = '', 
    $from_mail = '', 
    $pwd       = '', 
    $port = '', 
    $from_name = '';

    public function __construct(){
        // Assign the CodeIgniter super-object
        //$this->CI =& get_instance();

        $this->host = getenv('HOST'); //server hostname
        $this->user_name = getenv('FROM_MAIL');
        $this->pwd = getenv('PASSWORD');
        $this->from_mail = getenv('FROM_MAIL'); //email a/c username
        $this->port = getenv('PORT'); //or 25(depends or server email configuration)
        $this->from_name = getenv('FROM_NAME');
    }

    public function send_mail($to,$subject,$message){
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        
        try {
            
            //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
            //Go daddy is not allowing to use SMTP
            //$mail->isSMTP();                                      // Set mailer to use SMTP  
            $mail->Host = $this->host;                           // Specify main and backup SMTP servers
            $mail->SMTPAuth = false;                               // Enable SMTP authentication
            $mail->Username = $this->user_name;                   // SMTP username
            $mail->Password = $this->pwd;                         // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $this->port;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($this->from_mail, $this->from_name);
            $mail->addAddress($to);               // Name is optional
            
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send(); 
            return TRUE;
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
            return  $mail->ErrorInfo;
        }
    }
    
}

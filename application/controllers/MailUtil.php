<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once getcwd()."/application/controllers/PHPMailer/Exception.php";
require_once getcwd().'/application/controllers/PHPMailer/PHPMailer.php';
require_once getcwd().'/application/controllers/PHPMailer/SMTP.php';

class MailUtil
{
    
    public function sendGmail($sender_email, $sender_name, $sender_pwd, $to_email, $reply_email, $reply_email_name, $subject, $message,$email_error_log_file)
    {
        error_log("\n".date("Y-m-d h:i:sa")."-------".getcwd()."/application/controllers/PHPMailer/Exception.php",3,$email_error_log_file);
        
        date_default_timezone_set( 'America/Los_Angeles' );
        try 
        {
            $mail = new PHPMailer(true);
            error_log("\n".date("Y-m-d h:i:sa")."-------PHPMailer instance",3,$email_error_log_file);
            //Server settings
            $mail->SMTPDebug = 2;                      // Enable verbose debug output
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through                                // Enable SMTP authentication
            $mail->Username   = $sender_email;                     // SMTP username
            $mail->Password   = $sender_pwd;                               // SMTP password
            $mail->Port       = 465;


            //Recipients
            $mail->setFrom($sender_email, $sender_name);
            $mail->addAddress($to_email);     // Add a recipient
            //$mail->addAddress('ellen@example.com');               // Name is optional
            $mail->addReplyTo($reply_email, $reply_email_name);
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            // Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            error_log("\n".date("Y-m-d h:i:sa")."----Send debug output".$mail->Debugoutput,3,$email_error_log_file);
           
            //echo 'Message has been sent';
        } 
        catch (Exception $e) 
        {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            error_log("\n".date("Y-m-d h:i:sa")."----Error:".$mail->ErrorInfo,3,$email_error_log_file);
        }
    }
    
    
    
    public function sendGridMail($to,$from,$subject, $message,$url,$key)
    {
        $authorization = "authorization: Bearer ".$key;
        $data = "\n{".
          "\n\"personalizations\": [".
          "\n{".
            "\n\"to\": [".
            "\n    {".
            "\n      \"email\": \"".$to."\"".
            "\n    }".
            "\n],".
            "\n\"subject\": \"".$subject."\"".
            "\n}".
          "\n],".
          "\n\"from\": {".
          "\n \"email\": \"".$from."\"".
          "\n},".
          "\n \"content\": [".
          "\n{".
          //"\n    \"type\": \"text/plain\",".
          "\n    \"type\": \"text/html\",".
          "\n    \"value\": \"".$message."\"".
          //"\n    \"value\": \"test\"".
          "\n  }".
          "\n]".
          "}";
        $ch = curl_init();
        //echo $authorization;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(  $authorization, 'Content-Type: application/json' ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        // Tell curl not to return headers, but do return the response
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//New line
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//New line
        $result = curl_exec($ch);
        return $result;
    }
    
}
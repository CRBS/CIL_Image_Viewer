<?php

require_once './application/libraries/REST_Controller.php';
require_once 'GeneralUtil.php';
require_once 'DBUtil.php';
require_once 'DataLocalUtil.php';
require_once 'CurlUtil.php';
require_once 'MailUtil.php';
require_once 'Histogram.php';

class Image_process_rest extends REST_Controller
{
    private $success = "success";
    
    public function test_get()
    {
        $array = array();
        $array['found'] = false;
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
    }    
    public function geo_data_get($image_id, $slice_index)
    {
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        $json = $dbutil->getGeoData($db_params, $image_id, $slice_index);
        if(is_null($json))
        {
            $output = array();
            $output['success'] = false;
            $json_str = json_encode($output);
            $json = json_decode($json_str);
        }
        $this->response($json);
    }
    
    public function count_location_result_get($x,$y,$image_id)
    {
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        $count = $dbutil->countLocationResult($db_params, $x, $y, $image_id);
        $array = array();
        $array['count'] = $count;
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
    }
    
    public function image_info_get($image_id="0")
    {
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        $json = $dbutil->getImageWidthHeight($db_params, $image_id);
        if(is_null($json))
        {
            $output = array();
            $output['success'] = false;
            $json_str = json_encode($output);
            $json = json_decode($json_str);
        }
        $this->response($json);
                
    }

    public function cropimage_info_get($id=0)
    {
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        $json = $dbutil->getCropProcessInfo($db_params,$id);
        if(is_null($json))
        {
            $output = array();
            $output['success'] = false;
            $json_str = json_encode($output);
            $json = json_decode($json_str);
        }
        $this->response($json);
    }
    
    public function is_process_finished_get($crop_id=0)
    {
        $dbutil = new DBUtil();
        $cutil = new CurlUtil();
        $db_params = $this->config->item('db_params');
        $image_service_auth = $this->config->item('image_service_auth');
        $image_service_prefix = $this->config->item('image_service_prefix');
        
        $finished = $dbutil->isProcessFinished($db_params, $crop_id);
        $array = array();
        if($finished)
        {
            $url = $image_service_prefix."/image_process_service/cdeep3m_overlay_images/".$crop_id;
            $auth = $image_service_auth;
            $cjson_str = $cutil->curl_get($url, $auth);
            $cjson = json_decode($cjson_str);
            $array['finished'] = true;
            if(isset($cjson->image_urls))
                $array['image_urls'] = $cjson->image_urls;
        }
        else
            $array['finished'] = false;
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
    }
    
    public function average_runtime_get($image_id,$model_id,$augspeed,$frame)
    {
        $dbutil = new DBUtil();
        $cutil = new CurlUtil();
        $db_params = $this->config->item('db_params');
        
       $training_model_url = "https://doi.org/10.7295/".$model_id;
       
       $frame = str_replace("_", ",", $frame);
       
       $result = $dbutil->getAverageRunTime($db_params, $image_id, $training_model_url, $augspeed, $frame, 5);
       
       if(is_null($result))
       {
           $array = array();
           $array['average_time'] = "Unknown";
           $array['count'] = "Unknown";
           $json_str = json_encode($array);
           $json = json_decode($json_str);
           $this->response($json);
           return;
           
       }
       
       $this->response($result);
       return;
    }
    
    
    
    public function crop_process_status_get($crop_id=0)
    {
        $dbutil = new DBUtil();
        $cutil = new CurlUtil();
        $db_params = $this->config->item('db_params');
        $image_service_auth = $this->config->item('image_service_auth');
        $image_service_prefix = $this->config->item('image_service_prefix');
        
        $json = $dbutil->getProcessStatus($db_params, $crop_id);
        if(is_null($json))
        {
            $array= array();
            $array['finished'] = false;
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        
        if(isset($json->finished) && $json->finished
                && isset($json->error) && !$json->error)
        {
            $url = $image_service_prefix."/image_process_service/cdeep3m_overlay_images/".$crop_id;
            $auth = $image_service_auth;
            $cjson_str = $cutil->curl_get($url, $auth);
            $cjson = json_decode($cjson_str);

            $json->image_urls = $cjson->image_urls;
        }
        
        $this->response($json);
    }
    
    
    /*
    public function image_process_finished_post($crop_id=0)
    {
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        if(!is_numeric($crop_id))
        {
            $array['success'] = false;
            $array['error_message'] = "Crop ID is not a number";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        $crop_id = intval($crop_id);
        $image_metadata_auth = $this->config->item('image_metadata_auth');
        
        $header = $this->input->get_request_header('Authorization');
        if(!is_null($header))
        {
            $header = str_replace("Basic ", "", $header);
        }
        else
            $header = "Nothing";
        $decoded_header = trim(base64_decode($header));
        $array = array();
        if(strcmp($decoded_header, $image_metadata_auth)==0)
        {
            $array['success'] = true;
            $dbutil->updateIprocessFinishTime($db_params,$crop_id);
        }
        else 
        {
            $array['success'] = false;
            $array['error_message'] = "Invalid authorization";
        }
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
        
    }
    */
    
    public function report_process_finished_post($stage_or_prod="stage", $crop_id="0")
    {
        /*$debugFile = "/tmp/cdeep3m_email_debug.txt";
        if(file_exists($debugFile))
            unlink ($debugFile);
        error_log("\nBefore the if statement", 3, $debugFile);*/
        
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        
        if(!is_numeric($crop_id))
        {
            $array['success'] = false;
            $array['error_message'] = "Crop ID is not a number";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        
        if(strcmp($stage_or_prod,"stage") == 0 && is_numeric($crop_id))
        {
            $crop_id = intval($crop_id);
            
            $logDir = $this->config->item('service_log_dir');
            $logFile = $logDir."/prp_report.log";
            
            $image_metadata_auth = $this->config->item('image_metadata_auth');
            $cdeep3m_result_path_prefix = $this->config->item('cdeep3m_result_path_prefix');
            
            $header = $this->input->get_request_header('Authorization');
            if(!is_null($header))
            {
                $header = str_replace("Basic ", "", $header);
            }
            else
                $header = "Nothing";
            $decoded_header = trim(base64_decode($header));
            $array = array();
            if(strcmp($decoded_header, $image_metadata_auth)==0)
            {
                $array['success'] = true;
                $dbutil->updateIprocessFinishTime($db_params,$crop_id);
                
                /***********************Update image size***************************/
                $enhancedPath = $cdeep3m_result_path_prefix."/".$crop_id."/enhanced/enhanced.tar";
                //error_log("\n".$enhancedPath, 3, "/var/www/html/".$crop_id.".txt");
                if(file_exists($enhancedPath))
                {
                    $image_size = filesize($enhancedPath);
                    //error_log("\nSize:".$image_size, 3, "/var/www/html/".$crop_id.".txt");
                    $dbutil->upateCdeep3mImageSize($db_params, $crop_id, $image_size);
                }
                /***********************End Update image size***************************/
                
                
                /***************Sending email ********************/
                
                //error_log("\n".date("Y-m-d h:i:sa")."----sending email to:".$to_email,3,$logFile);
                $mutil = new MailUtil();
                $subject = "Your CDeep3M processs is finished:".$crop_id;
                $result_url = "https://cdeep3m-viewer-stage.crbs.ucsd.edu/cdeep3m_result/view/".$crop_id;
                
                $cropInfoJson = $dbutil->getCropProcessInfo($db_params, $crop_id);
                $to_email = $cropInfoJson->contact_email;
                //$message = "<a href ='".$result_url."' target='_blank'>".$result_url."</a>";
                $message = $result_url;
                $mutil->sendLocalMail($to_email, $subject, $message);
                error_log("\n".date("Y-m-d h:i:sa")."----Crop ID:".$crop_id,3,$logFile);
                error_log("\n".date("Y-m-d h:i:sa")."----sending email to:".$to_email,3,$logFile);
                error_log("\n".date("Y-m-d h:i:sa")."----Message:".$message,3,$logFile);
                
                
                
                
                /*************Deleting the PRP POD*************/     
                /*$cutil = new CurlUtil();
                $image_service_auth = $this->config->item('image_service_auth');
                $image_service_prefix = $this->config->item('image_service_prefix');
                $sjson = $dbutil->getProcessStatus($db_params, $crop_id);
                $pod = "a";
                if(!is_null($sjson) && isset($sjson->pod_running))
                    $pod = $sjson->pod_running;
                    
                $image_service_url = $image_service_prefix."/Cdeep3m_prp_service/delete_prp_pod/".$pod."/".$crop_id;
                $cutil->curl_post($image_service_url, "", $image_service_auth);*/
                /*************END Deleting the PRP POD*************/      
            }
            else 
            {
                $array['success'] = false;
                $array['error_message'] = "Invalid authorization";
            }
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        
        
        $array = array();
        $array['success'] = false;
        $array['error_message'] = "Out of scope with the cropping ID:".$crop_id;
        $this->response($array);
        return;
    }
    
    
    public function update_retrain_error_post($stage_or_prod="stage", $crop_id="0")
    {
        $dbutil = new DBUtil();
        $cutil = new CurlUtil();
        $mutil = new MailUtil();
        date_default_timezone_set( 'America/Los_Angeles' );
        //$db_params = $this->config->item('db_params');
        $cil_pgsql_db = $this->config->item('cil_pgsql_db');
        $service_log_dir = $this->config->item('service_log_dir');
        error_log("update_cdeep3m_error_postt----Crop_id:".$crop_id."\n", 3, $service_log_dir."/image_service_log.txt");
        
        $array = array();
        if(!is_numeric($crop_id))
        {
            $array['success'] = false;
            $array['error_message'] = "Crop ID is not a number";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        
        $crop_id = intval($crop_id);
        
        if(strcmp($stage_or_prod,"stage") == 0)
        {
            $image_metadata_auth = $this->config->item('image_metadata_auth');

            $header = $this->input->get_request_header('Authorization');
            if(!is_null($header))
            {
                $header = str_replace("Basic ", "", $header);
            }
            else
                $header = "Nothing";
            $decoded_header = trim(base64_decode($header));
            $array = array();
            if(strcmp($decoded_header, $image_metadata_auth)==0)
            {
                
                //$array['success'] = $dbutil->updateCropError($db_params, $crop_id);
                $array['success'] = $dbutil->updateRetrainError($cil_pgsql_db, $crop_id);
                
                $email = $dbutil->getRetrainUserEmail($cil_pgsql_db, $crop_id);
                
                if(!is_null($email))
                {
                    /***************Send Gmail*******************/
                    $email_log_file = $service_log_dir."/email_error.log";
                    error_log("\n".date("Y-m-d h:i:sa")."-----------------------------------------------------------------------------", 3, $email_log_file);
                    error_log("\n".date("Y-m-d h:i:sa")."----------------------Start sending email", 3, $email_log_file);
                    $email_log_file = $service_log_dir."/email_error.log";
                    $gmail_sender = $this->config->item('gmail_sender');
                    $gmail_sender_name = $this->config->item('gmail_sender_name');
                    $gmail_sender_pwd = $this->config->item('gmail_sender_pwd');
                    $gmail_reply_to = $this->config->item('gmail_reply_to');
                    $gmail_reply_to_name = $this->config->item('gmail_reply_to_name');

                    error_log("\n".date("Y-m-d h:i:sa")."----------------------gmail_sender:".$gmail_sender, 3, $email_log_file);
                    error_log("\n".date("Y-m-d h:i:sa")."----------------------gmail_sender_name:".$gmail_sender_name, 3, $email_log_file);
                    error_log("\n".date("Y-m-d h:i:sa")."----------------------gmail_sender_pwd:".$gmail_sender_pwd, 3, $email_log_file);
                    error_log("\n".date("Y-m-d h:i:sa")."----------------------gmail_reply_to:".$gmail_reply_to, 3, $email_log_file);
                    error_log("\n".date("Y-m-d h:i:sa")."----------------------gmail_reply_to_name:".$gmail_reply_to_name, 3, $email_log_file);


                    $subject = "Error in your CDeep3M Retrain process: ".$crop_id;
                    $message = "See the error log files:<br/>"."http://cildata.crbs.ucsd.edu/cdeep3m_results/".$crop_id."/log/logs.tar";
                    
                    $mutil->sendGmail($gmail_sender, $gmail_sender_name, $gmail_sender_pwd,$email, $gmail_reply_to, $gmail_reply_to_name, $subject, $message, $email_log_file);
                    error_log("\n".date("Y-m-d h:i:sa")."----------------------Finished sending email to ".$email, 3, $email_log_file);
                    error_log("\n".date("Y-m-d h:i:sa")."-----------------------------------------------------------------------------", 3, $email_log_file);
                    /***************End send Gmail***************/
                }
                
                
            }
            else
            {
                $array['success'] = false;
                $array['error_message'] = "Invalid authorization";
            }
        }
        else
        {
            $array['success'] = false;
        }
        
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
        return;
    }
    
    
    
    public function update_cdeep3m_error_post($stage_or_prod="stage", $crop_id="0")
    {
        $dbutil = new DBUtil();
        $cutil = new CurlUtil();
        $mutil = new MailUtil();
        date_default_timezone_set( 'America/Los_Angeles' );
        $db_params = $this->config->item('db_params');
        $service_log_dir = $this->config->item('service_log_dir');
        error_log("update_cdeep3m_error_postt----Crop_id:".$crop_id."\n", 3, $service_log_dir."/image_service_log.txt");
        
        $array = array();
        if(!is_numeric($crop_id))
        {
            $array['success'] = false;
            $array['error_message'] = "Crop ID is not a number";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        
        $crop_id = intval($crop_id);
        
        if(strcmp($stage_or_prod,"stage") == 0)
        {
            $image_metadata_auth = $this->config->item('image_metadata_auth');

            $header = $this->input->get_request_header('Authorization');
            if(!is_null($header))
            {
                $header = str_replace("Basic ", "", $header);
            }
            else
                $header = "Nothing";
            $decoded_header = trim(base64_decode($header));
            $array = array();
            if(strcmp($decoded_header, $image_metadata_auth)==0)
            {
                
                $array['success'] = $dbutil->updateCropError($db_params, $crop_id);
                /***************Send Gmail*******************/
                $cropInfoJson = $dbutil->getCropProcessInfo($db_params , $crop_id);
                $email_log_file = $service_log_dir."/email_error.log";
                error_log("\n".date("Y-m-d h:i:sa")."-----------------------------------------------------------------------------", 3, $email_log_file);
                error_log("\n".date("Y-m-d h:i:sa")."----------------------Start sending email", 3, $email_log_file);
                $email_log_file = $service_log_dir."/email_error.log";
                $gmail_sender = $this->config->item('gmail_sender');
                $gmail_sender_name = $this->config->item('gmail_sender_name');
                $gmail_sender_pwd = $this->config->item('gmail_sender_pwd');
                $gmail_reply_to = $this->config->item('gmail_reply_to');
                $gmail_reply_to_name = $this->config->item('gmail_reply_to_name');
                
                error_log("\n".date("Y-m-d h:i:sa")."----------------------gmail_sender:".$gmail_sender, 3, $email_log_file);
                error_log("\n".date("Y-m-d h:i:sa")."----------------------gmail_sender_name:".$gmail_sender_name, 3, $email_log_file);
                error_log("\n".date("Y-m-d h:i:sa")."----------------------gmail_sender_pwd:".$gmail_sender_pwd, 3, $email_log_file);
                error_log("\n".date("Y-m-d h:i:sa")."----------------------gmail_reply_to:".$gmail_reply_to, 3, $email_log_file);
                error_log("\n".date("Y-m-d h:i:sa")."----------------------gmail_reply_to_name:".$gmail_reply_to_name, 3, $email_log_file);
                
                
                $subject = "Error in your CDeep3M process: ".$crop_id;
                $message = "See the error log files:<br/>"."http://cildata.crbs.ucsd.edu/cdeep3m_results/".$crop_id."/log/logs.tar";
                if(!is_null($cropInfoJson))
                {
                    if(isset($cropInfoJson->contact_email))
                    {
                        error_log("\n".date("Y-m-d h:i:sa")."----------------------contact_email:".$cropInfoJson->contact_email, 3, $email_log_file);
                    }
                    else
                    {
                        $cropInfoJson_str = json_encode($cropInfoJson, JSON_PRETTY_PRINT );
                        error_log("\n".date("Y-m-d h:i:sa")."----------------------cropInfoJson_str:".$cropInfoJson_str, 3, $email_log_file);
                    }
                }
                else 
                {
                    error_log("\n".date("Y-m-d h:i:sa")."----------------------contact_email is NULL", 3, $email_log_file);
                }
                $mutil->sendGmail($gmail_sender, $gmail_sender_name, $gmail_sender_pwd,$cropInfoJson->contact_email, $gmail_reply_to, $gmail_reply_to_name, $subject, $message, $email_log_file);
                error_log("\n".date("Y-m-d h:i:sa")."----------------------Finished sending email to ".$cropInfoJson->contact_email, 3, $email_log_file);
                error_log("\n".date("Y-m-d h:i:sa")."-----------------------------------------------------------------------------", 3, $email_log_file);
                /***************End send Gmail***************/
                
                
            }
            else
            {
                $array['success'] = false;
                $array['error_message'] = "Invalid authorization";
            }
        }
        else
        {
            $array['success'] = false;
        }
        
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
        return;
    }
    
    
    public function report_running_pod_post($stage_or_prod="stage", $crop_id="0",$pod_name)
    {
        $dbutil = new DBUtil();
        $cutil = new CurlUtil();
        $db_params = $this->config->item('db_params');
        $service_log_dir = $this->config->item('service_log_dir');
        error_log("update_crop_status_post----Crop_id:".$crop_id."\n", 3, $service_log_dir."/image_service_log.txt");
        
        $array = array();
        if(!is_numeric($crop_id))
        {
            $array['success'] = false;
            $array['error_message'] = "Crop ID is not a number";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        
        
        $crop_id = intval($crop_id);
        
        if(strcmp($stage_or_prod,"stage") == 0)
        {
            $image_metadata_auth = $this->config->item('image_metadata_auth');

            $header = $this->input->get_request_header('Authorization');
            if(!is_null($header))
            {
                $header = str_replace("Basic ", "", $header);
            }
            else
                $header = "Nothing";
            $decoded_header = trim(base64_decode($header));
            $array = array();
            if(strcmp($decoded_header, $image_metadata_auth)==0)
            {
                //$array['success'] = $dbutil->updateCropProcessMessage($db_params, $crop_id, $message);
                $array['success'] = $dbutil->updateRunningPod($db_params, $crop_id, $pod_name);
                
                $dbutil->updateCropProcessMessage($db_params, $crop_id, "The GPU node instance has been created. The GPU node ID is cil-cdeep3m-".strtolower($pod_name)."-".$crop_id).".";
            }
            else
            {
                $array['success'] = false;
                $array['error_message'] = "Invalid authorization";
            }
        }
        else
        {
            $array['success'] = false;
        }
        
        
        
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
        return;
    }
    
    public function update_crop_status_post($stage_or_prod="stage", $crop_id="0")
    {
        $dbutil = new DBUtil();
        $cutil = new CurlUtil();
        $mailer = new MailUtil();
        $db_params = $this->config->item('db_params');
        $service_log_dir = $this->config->item('service_log_dir');
        error_log("update_crop_status_post----Crop_id:".$crop_id."\n", 3, $service_log_dir."/image_service_log.txt");
        
        $array = array();
        if(!is_numeric($crop_id))
        {
            $array['success'] = false;
            $array['error_message'] = "Crop ID is not a number";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        
        
        
        
        $crop_id = intval($crop_id);
        
        $message = file_get_contents('php://input', 'r');
        if(is_null($message))
            $message = "Pending";
        
        if(strcmp($stage_or_prod,"stage") == 0)
        {
            $image_metadata_auth = $this->config->item('image_metadata_auth');

            $header = $this->input->get_request_header('Authorization');
            if(!is_null($header))
            {
                $header = str_replace("Basic ", "", $header);
            }
            else
                $header = "Nothing";
            $decoded_header = trim(base64_decode($header));
            $array = array();
            if(strcmp($decoded_header, $image_metadata_auth)==0)
            {
                $array['success'] = $dbutil->updateCropProcessMessage($db_params, $crop_id, $message);
                
                
            }
            else
            {
                $array['success'] = false;
                $array['error_message'] = "Invalid authorization";
            }
        }
        else
        {
            $array['success'] = false;
        }
        
        
        
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
        return;
    }
    
    
    public function generate_histogram_post($username, $image_id)
    {
        error_reporting(0);
        $hist = new Histogram();
        
        $ssd_image_dir = $this->config->item("ssd_image_dir");
        $image_tar_dir = $this->config->item("image_tar_dir");
        
        $histogram_folder = $this->config->item("histogram_folder");
        $histogram_folder = $histogram_folder."/".$username;
        $h_filePath = $histogram_folder."/".$image_id.".log";
        
        $outputFolder = $histogram_folder."/".$image_id;
        if(!file_exists($outputFolder))
            mkdir($outputFolder);
        
        
        $stitchedFolder = $outputFolder."/stitched";
        if(!file_exists($stitchedFolder))
            mkdir($stitchedFolder);
        
        $outputFolder = $outputFolder."/output";
        if(!file_exists($outputFolder))
            mkdir($outputFolder);
        
        $hist->generateImages($h_filePath, $outputFolder, $ssd_image_dir, $image_tar_dir);
        $imagemagick_convert = $this->config->item("imagemagick_convert");
        $hist->stitchImages($outputFolder,$stitchedFolder, $imagemagick_convert);
        $hist->generateHistogram($stitchedFolder);
        
        
        $array = array();
        $array['success'] = true;
        //$array['content'] = $content;
        $this->response($array);
        return;
    }
    
    
    public function report_crop_finished_post($stage_or_prod="stage", $crop_id="0")
    {
        $dbutil = new DBUtil();
        $cutil = new CurlUtil();
        $db_params = $this->config->item('db_params');
        $service_log_dir = $this->config->item('service_log_dir');
        //$docker_image_type = $this->config->item('docker_image_type');
        
        if(file_exists($service_log_dir."/image_service_log.txt"))
            unlink($service_log_dir."/image_service_log.txt");
        
        error_log("report_crop_finished_post-Crop_id:".$crop_id."\n", 3, $service_log_dir."/image_service_log.txt");
        
        if(!is_numeric($crop_id))
        {
            $array['success'] = false;
            $array['error_message'] = "Crop ID is not a number";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        
        
        $crop_id = intval($crop_id);
        $cropInfoJson = $dbutil->getCropProcessInfo($db_params, $crop_id);
        if(strcmp($stage_or_prod,"stage") == 0 && is_numeric($crop_id))
        {
            
            $image_metadata_auth = $this->config->item('image_metadata_auth');

            $header = $this->input->get_request_header('Authorization');
            if(!is_null($header))
            {
                $header = str_replace("Basic ", "", $header);
            }
            else
                $header = "Nothing";
            $decoded_header = trim(base64_decode($header));
            $array = array();
            if(strcmp($decoded_header, $image_metadata_auth)==0)
            {
                $array['success'] = true;
                $dbutil->updateCropFinished($db_params,$crop_id);
                $image_service_auth = $this->config->item('image_service_auth');
                $image_service_prefix = $this->config->item('image_service_prefix');
                
                $image_service_url = "";
                
                $cropInfoJsonStr = json_encode($cropInfoJson, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                error_log("\n".$cropInfoJsonStr."\n", 3, $service_log_dir."/image_service_log.txt");
                if(isset($cropInfoJson->use_prp) && $cropInfoJson->use_prp)
                {
                   $crop_id = intval($crop_id);
                   $docker_image_type = $dbutil->getDockerImageType($db_params, $crop_id);
                    
                   $image_service_url = $image_service_prefix."/cdeep3m_prp_service/image_preview_step2/stage/".$crop_id."/".$docker_image_type;    
                   error_log("\n".$image_service_url."\n", 3, $service_log_dir."/image_service_log.txt");
                   
                }
                else
                   $image_service_url = $image_service_prefix."/image_process_service/image_preview_step2/stage/".$crop_id;
                error_log($image_service_url."\n", 3, $service_log_dir."/image_service_log.txt");
                
                $dbutil->updateCropProcessMessage($db_params, $crop_id, "The image is cropped and it is launching the PRP POD now. The process ID is ".$crop_id);
                
                $cutil->curl_post($image_service_url, "", $image_service_auth);
            }
            else 
            {
                $array['success'] = false;
                $array['error_message'] = "Invalid authorization";
            }
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        
        
        
        $array = array();
        $array['success'] = false;
        $array['error_message'] = "Out of scope with the cropping ID:".$crop_id;
        $this->response($array);
        return;
    }
}
<?php

require_once './application/libraries/REST_Controller.php';
require_once 'GeneralUtil.php';
require_once 'DBUtil.php';
require_once 'DataLocalUtil.php';
require_once 'CurlUtil.php';

class Image_process_rest extends REST_Controller
{
    private $success = "success";

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
            return;
        }
        
        
        $array = array();
        $array['success'] = false;
        $array['error_message'] = "Out of scope with the cropping ID:".$crop_id;
        $this->response($array);
        return;
    }
    
    public function update_cdeep3m_error_post($stage_or_prod="stage", $crop_id="0")
    {
        $dbutil = new DBUtil();
        $cutil = new CurlUtil();
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
    
    
    public function report_crop_finished_post($stage_or_prod="stage", $crop_id="0")
    {
        $dbutil = new DBUtil();
        $cutil = new CurlUtil();
        $db_params = $this->config->item('db_params');
        $service_log_dir = $this->config->item('service_log_dir');
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
                   $image_service_url = $image_service_prefix."/cdeep3m_prp_service/image_preview_step2/stage/".$crop_id;    
                else
                   $image_service_url = $image_service_prefix."/image_process_service/image_preview_step2/stage/".$crop_id;
                error_log($image_service_url."\n", 3, $service_log_dir."/image_service_log.txt");
                
                $dbutil->updateCropProcessMessage($db_params, $crop_id, "The image is cropped and it is launching the PRP POD now.");
                
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
<?php

require_once './application/libraries/REST_Controller.php';
require_once 'GeneralUtil.php';
require_once 'DBUtil.php';
require_once 'DataLocalUtil.php';
require_once 'CurlUtil.php';
require_once 'MailUtil.php';

class Timer_rest extends REST_Controller
{
    
    private $success = "success";
    
    public function retrain_end_post($crop_id)
    {
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
         
        /*************Auth********************************/
        $image_metadata_auth = $this->config->item('image_metadata_auth');
        $header = $this->input->get_request_header('Authorization');
        if(!is_null($header))
        {
            $header = str_replace("Basic ", "", $header);
        }
        else
            $header = "Nothing";
        $decoded_header = trim(base64_decode($header));
        if(strcmp($decoded_header, $image_metadata_auth)!=0)
        {
            $array = array();
            $array['success'] = false;
            $array['error_message'] = "Invalid authorization";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        /*************End Auth********************************/
        
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        $success = $dbutil->timerUpdateRetrainEndTime($db_params, $crop_id);
        
        $array = array();
        $array[$this->success] = $success;
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
        return;
    }
    
    public function retrain_start_post($crop_id)
    {
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
         
        /*************Auth********************************/
        $image_metadata_auth = $this->config->item('image_metadata_auth');
        $header = $this->input->get_request_header('Authorization');
        if(!is_null($header))
        {
            $header = str_replace("Basic ", "", $header);
        }
        else
            $header = "Nothing";
        $decoded_header = trim(base64_decode($header));
        if(strcmp($decoded_header, $image_metadata_auth)!=0)
        {
            $array = array();
            $array['success'] = false;
            $array['error_message'] = "Invalid authorization";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        /*************End Auth********************************/
        
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        $success = $dbutil->timerUpdateRetrainStartTime($db_params, $crop_id);
        
        $array = array();
        $array[$this->success] = $success;
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
        return;
    }
    
    public function pod_start_post($crop_id)
    {
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
         
        /*************Auth********************************/
        $image_metadata_auth = $this->config->item('image_metadata_auth');
        $header = $this->input->get_request_header('Authorization');
        if(!is_null($header))
        {
            $header = str_replace("Basic ", "", $header);
        }
        else
            $header = "Nothing";
        $decoded_header = trim(base64_decode($header));
        if(strcmp($decoded_header, $image_metadata_auth)!=0)
        {
            $array = array();
            $array['success'] = false;
            $array['error_message'] = "Invalid authorization";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        /*************End Auth********************************/
        
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        $success = $dbutil->timerUpdatePodStartTime($db_params, $crop_id);
        
        $array = array();
        $array[$this->success] = $success;
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
        return;
    }
    
    
    public function update_deaug_json_post($crop_id)
    {
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
        
        /*************Auth********************************/
        $image_metadata_auth = $this->config->item('image_metadata_auth');
        $header = $this->input->get_request_header('Authorization');
        if(!is_null($header))
        {
            $header = str_replace("Basic ", "", $header);
        }
        else
            $header = "Nothing";
        $decoded_header = trim(base64_decode($header));
        if(strcmp($decoded_header, $image_metadata_auth)!=0)
        {
            $array = array();
            $array['success'] = false;
            $array['error_message'] = "Invalid authorization";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        /*************End Auth********************************/
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        /*************Reading de-augmentation json**************/
        $cdeep3m_result_path_prefix = $this->config->item('cdeep3m_result_path_prefix');
        $deaugJsonPath = $cdeep3m_result_path_prefix."/".$crop_id."/log/de_augmentation_info.json";
        
        $deaugLogPath = $cdeep3m_result_path_prefix."/".$crop_id."/deaug.log";
        ///sleep(10);
        error_log("\n".$deaugJsonPath, 3, $deaugLogPath);
        
        if(file_exists($deaugJsonPath))
        {
            error_log("\nFile exist", 3, $deaugLogPath);
            $deaugJsonStr = file_get_contents($deaugJsonPath);
            if(!is_null($deaugJsonStr))
            {
                error_log("\nJson string is not empty", 3, $deaugLogPath);
                $deaugJson = json_decode($deaugJsonStr);
                if(!is_null($deaugJson))
                {
                    error_log("\nJson object is not null", 3, $deaugLogPath);
                    if(isset($deaugJson->num_of_pkg))
                    {
                        error_log("\nnum_of_pkg is set", 3, $deaugLogPath);
                        $dbutil->timerUpdateNumOfPkg($db_params, $crop_id, $deaugJson->num_of_pkg);
                    }
                    
                    if(isset($deaugJson->imagesize))
                    {
                        if(count($deaugJson->imagesize) ==3)
                        {
                            $x = $deaugJson->imagesize[0];
                            $y = $deaugJson->imagesize[1];
                            $z = $deaugJson->imagesize[2];
                            $dbutil->timerUpdateCdeep3mXyz($db_params, $crop_id, $x, $y, $z);
                        }
                    }
                }
            }
        }
         
        /*************End Reading de-augmentation json**************/
        $array = array();
        $array[$this->success] = true;
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
        return;
    }
    
    public function pod_end_post($crop_id)
    {
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
        
        /*************Auth********************************/
        $image_metadata_auth = $this->config->item('image_metadata_auth');
        $header = $this->input->get_request_header('Authorization');
        if(!is_null($header))
        {
            $header = str_replace("Basic ", "", $header);
        }
        else
            $header = "Nothing";
        $decoded_header = trim(base64_decode($header));
        if(strcmp($decoded_header, $image_metadata_auth)!=0)
        {
            $array = array();
            $array['success'] = false;
            $array['error_message'] = "Invalid authorization";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        /*************End Auth********************************/
        
        
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        $success = $dbutil->timerUpdatePodEndTime($db_params,$crop_id);
        
        /*************Deleting the PRP POD*************/     
        $cutil = new CurlUtil();
        $image_service_auth = $this->config->item('image_service_auth');
        $image_service_prefix = $this->config->item('image_service_prefix');
        $sjson = $dbutil->getProcessStatus($db_params, $crop_id);
        $pod = "a";
        if(!is_null($sjson) && isset($sjson->pod_running))
            $pod = $sjson->pod_running;
                    
        $image_service_url = $image_service_prefix."/Cdeep3m_prp_service/delete_prp_pod/".$pod."/".$crop_id;
        $cutil->curl_post($image_service_url, "", $image_service_auth);
        /*************END Deleting the PRP POD*************/    
        
       
        
        $array = array();
        $array[$this->success] = $success;
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
        return;
    }
    
    public function download_start_post($crop_id)
    {
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
        
        /*************Auth********************************/
        $image_metadata_auth = $this->config->item('image_metadata_auth');
        $header = $this->input->get_request_header('Authorization');
        if(!is_null($header))
        {
            $header = str_replace("Basic ", "", $header);
        }
        else
            $header = "Nothing";
        $decoded_header = trim(base64_decode($header));
        if(strcmp($decoded_header, $image_metadata_auth)!=0)
        {
            $array = array();
            $array['success'] = false;
            $array['error_message'] = "Invalid authorization";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        /*************End Auth********************************/
        
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        $success = $dbutil->timerUpdateDownloadStartTime($db_params,$crop_id);
        
        $array = array();
        $array[$this->success] = $success;
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
        return;
    }
    
    public function download_end_post($crop_id)
    {
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
        
        /*************Auth********************************/
        $image_metadata_auth = $this->config->item('image_metadata_auth');
        $header = $this->input->get_request_header('Authorization');
        if(!is_null($header))
        {
            $header = str_replace("Basic ", "", $header);
        }
        else
            $header = "Nothing";
        $decoded_header = trim(base64_decode($header));
        if(strcmp($decoded_header, $image_metadata_auth)!=0)
        {
            $array = array();
            $array['success'] = false;
            $array['error_message'] = "Invalid authorization";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        /*************End Auth********************************/
        
        
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        $success = $dbutil->timerUpdateDownloadEndTime($db_params,$crop_id);
        
        $array = array();
        $array[$this->success] = $success;
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
        return;
    }
    
    public function predict_start_post($crop_id)
    {
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
        
        /*************Auth********************************/
        $image_metadata_auth = $this->config->item('image_metadata_auth');
        $header = $this->input->get_request_header('Authorization');
        if(!is_null($header))
        {
            $header = str_replace("Basic ", "", $header);
        }
        else
            $header = "Nothing";
        $decoded_header = trim(base64_decode($header));
        if(strcmp($decoded_header, $image_metadata_auth)!=0)
        {
            $array = array();
            $array['success'] = false;
            $array['error_message'] = "Invalid authorization";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        /*************End Auth********************************/
        
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        $success = $dbutil->timerUpdatePredictStartTime($db_params,$crop_id);
        
        $array = array();
        $array[$this->success] = $success;
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
        return;
    }
    
    public function predict_end_post($crop_id)
    {
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
        
        /*************Auth********************************/
        $image_metadata_auth = $this->config->item('image_metadata_auth');
        $header = $this->input->get_request_header('Authorization');
        if(!is_null($header))
        {
            $header = str_replace("Basic ", "", $header);
        }
        else
            $header = "Nothing";
        $decoded_header = trim(base64_decode($header));
        if(strcmp($decoded_header, $image_metadata_auth)!=0)
        {
            $array = array();
            $array['success'] = false;
            $array['error_message'] = "Invalid authorization";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
            return;
        }
        /*************End Auth********************************/
        
        
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        $success = $dbutil->timerUpdatePredictEndTime($db_params,$crop_id);
        
        $array = array();
        $array[$this->success] = $success;
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
        return;
    }
    
}
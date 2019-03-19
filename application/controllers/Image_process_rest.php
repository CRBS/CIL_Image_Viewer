<?php

require_once './application/libraries/REST_Controller.php';
require_once 'GeneralUtil.php';
require_once 'DBUtil.php';
require_once 'DataLocalUtil.php';


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
        $db_params = $this->config->item('db_params');
        $finished = $dbutil->isProcessFinished($db_params, $crop_id);
        $array = array();
        if($finished)
            $array['finished'] = true;
        else
            $array['finished'] = false;
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
    }
    
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
    
    public function report_crop_finished_post($stage_or_prod="stage", $crop_id="0")
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
                $dbutil->updateCropFinished($db_params,$crop_id);
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
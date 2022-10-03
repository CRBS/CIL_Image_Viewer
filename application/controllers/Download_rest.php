<?php

require_once './application/libraries/REST_Controller.php';
require_once 'GeneralUtil.php';
//require_once 'DBUtil.php';
require_once 'CurlUtil.php';


class Download_rest extends REST_Controller
{
    private $success = "success";
    
    public function test_get()
    {
        $array = array();
        $array['test'] = "OK";
        $this->response($array);
    }
    
    public function generate_slice_post($image_id, $slice)
    {
        
       $cutil = new CurlUtil();
       $image_service_auth = $this->config->item('image_service_auth');
       $image_service_prefix = $this->config->item('image_service_prefix');
       
       //$download_url = $image_service_prefix."/Image_slicer_rest/create_image_slice/".$image_id."/".$slice;
       $download_url = "https://iruka.crbs.ucsd.edu/CIL-Storage-RS/index.php"."/Image_slicer_rest/create_image_slice/".$image_id."/".$slice;
       
       
       $response = $cutil->curl_post($download_url,"", $image_service_auth);
       $json = json_decode($response);
       $this->response($json);
         
       
        /*
        $array = array();
        $array['download_url'] = $download_url;
        $this->response($array); 
         */
    }
}

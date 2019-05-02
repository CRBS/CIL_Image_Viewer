<?php

require_once 'GeneralUtil.php';
require_once 'DBUtil.php';
require_once 'DataLocalUtil.php';
require_once 'Constants.php';
require_once 'CurlUtil.php';
class Cdeep3m_result extends CI_Controller
{
    public function view($crop_id)
    {
        $cutil = new CurlUtil();
        $data['title'] = "View Cdeep3m result";
        
        $cdeep3m_result_service = $this->config->item('cdeep3m_result_service');
        $url = $cdeep3m_result_service."/".$crop_id;
        $image_service_auth = $this->config->item('image_service_auth');
        
        $response = $cutil->curl_get($url, $image_service_auth);
        //$data['response'] = $response;
        $json = json_decode($response);
        if(isset($json->Overlay_images))
            $data['data_size'] = count($json->Overlay_images);
        else
            $data['data_size'] = 0;
        $data['cdeep3m_result'] = $json;
        $this->load->view('cdeep3m/view_cdeep3m_result_display', $data);
    }

}


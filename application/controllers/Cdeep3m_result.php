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
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        
        $isFinished = $dbutil->isProcessFinished($db_params, $crop_id);
        if(!$isFinished)
        {
            echo "Your CDeep3M process is not done yet.";
            return;
        }
        
        
        $data['title'] = "View Cdeep3m result";
        $data['cdeep3m_website']= $this->config->item('cdeep3m_website');
        $cdeep3m_result_service = $this->config->item('cdeep3m_result_service');
        $url = $cdeep3m_result_service."/".$crop_id;
        $image_service_auth = $this->config->item('image_service_auth');
        $db_params = $this->config->item('db_params');
        $cropInfo = $dbutil->getCropProcessInfo($db_params, $crop_id);
        if(!is_null($cropInfo))
        {
            if(isset($cropInfo->training_model_url))
            {
                $doi = $cropInfo->training_model_url;

                $trainedModel = $dbutil->getTrainedModelByDOI($db_params,$doi);
                if(!is_null($trainedModel))
                    $data['trained_model'] = $trainedModel;
            }
            $data['cropInfo'] = $cropInfo;
   
        }
        //echo "\n".$url;
        $response = $cutil->curl_get($url, $image_service_auth);
        //$data['response'] = $response;
        $json = json_decode($response);
        if(isset($json->Overlay_images))
            $data['data_size'] = count($json->Overlay_images)-1;
        else
            $data['data_size'] = 0;
        $data['cdeep3m_result'] = $json;
        $this->load->view('cdeep3m/view_cdeep3m_result_display', $data);
    }

    public function location_result($x,$y, $image_id)
    {
        $cutil = new CurlUtil();
        $dbutil = new DBUtil();
        $db_params = $this->config->item('db_params');
        
        
        $doi2nameMap  = $dbutil->getAllDoi2ModelName($db_params);
        if(!is_null($doi2nameMap))
        {
            //var_dump($doi2nameMap);
            $data['doi2nameMap'] = $doi2nameMap;
        }
        else
        {
            //echo "It is a NULL<br/>";
        }
        $data['title'] = "View Cdeep3m location results";
        $location_results = $dbutil->getLocationResults($db_params, $x, $y, $image_id);
        
        $data['location_results'] = $location_results;
        
        
        $this->load->view('cdeep3m/view_cdeep3m_location_results', $data);
    }
}


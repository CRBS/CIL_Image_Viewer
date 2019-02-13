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
}
<?php

require_once './application/libraries/REST_Controller.php';
require_once 'GeneralUtil.php';
require_once 'DBUtil.php';
require_once 'DataLocalUtil.php';

class Metadata_service extends REST_Controller
{
    private $success = "success";
    
    public function mpid_metadata_get($image_id="0")
    {
        $ncmir_pgsql_db = $this->config->item('ncmir_pgsql_db');
    }
    
}
    


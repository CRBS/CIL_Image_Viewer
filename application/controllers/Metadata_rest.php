<?php

require_once './application/libraries/REST_Controller.php';
require_once 'GeneralUtil.php';
require_once 'DBUtil.php';
require_once 'DataLocalUtil.php';

class Metadata_rest extends REST_Controller
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
    
    public function mpid_metadata_get($mpid="0")
    {
        $array = array();
        $ncmir_pgsql_db = $this->config->item('ncmir_pgsql_db');
        //echo $ncmir_pgsql_db;
        $array['found'] = true;
        $array['db'] = $ncmir_pgsql_db;
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        $this->response($json);
        /*$dbutil = new DBUtil();
        $dataArray = $dbutil->getMpidInfo($ncmir_pgsql_db, $mpid);
        if(!is_null($dataArray))
        {
            $dataArray['found'] = true;
            $json_str = json_encode($dataArray);
            $json = json_decode($json_str);
            $this->response($json);
        }
        else
        {
            $array = array();
            $array['found'] = false;
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            $this->response($json);
        }*/
    }
    
}
    


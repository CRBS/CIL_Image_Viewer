<?php

require_once './application/libraries/REST_Controller.php';
require_once 'GeneralUtil.php';
require_once 'DBUtil.php';
require_once 'DataLocalUtil.php';

class Image_annotation_service extends REST_Controller
{
    private $success = "success";
        
    public function imageinfo_get($image_id="0")
    {
        error_reporting(0);
        $localutil = new DataLocalUtil();
        $db_params = $this->config->item('db_params');
        $image_tar_dir = $this->config->item('image_tar_dir');
        $dbutil = new DBUtil();
        $array = $dbutil->getImageInfo($db_params,$image_id);
        if(is_null($array))
        {
            $larray = $localutil->getLocalImageInfo($image_id, $image_tar_dir);
            /*if($larray[$this->success])
            {
                $this->response($larray);
                return;
            }
            else
            {
                $array = array();
                $array['test'] = true;
                $array[$this->success] = false;
                $this->response($array);
                return;
            }*/
            $this->response($larray);
            return;
        }
        else
        {
            $this->response($array);
            return;
        }
    }
    
    public function geometadata_get($cil_id="0",$sindex="0",$object_id="0")
    {
        $db_params = $this->config->item('db_params');
        $dbutil = new DBUtil();
        $desc = $dbutil->getGeoMetadata($db_params,$cil_id,$sindex,$object_id);
        
        $array = array();
        $array['Description'] = $desc;
        $this->response($array);
    }

    
    public function geometadata_post($cil_id="0",$sindex="0",$object_id="0")
    {
        $db_params = $this->config->item('db_params');
        $dbutil = new DBUtil();
        $desc = file_get_contents('php://input', 'r');
        
        $exists = $dbutil->geoMetadataExist($db_params,$cil_id,$sindex,
                $object_id,$desc);
        if(!$exists)
            $dbutil->insertGeoMetadata($db_params,$cil_id,$sindex,$object_id,
                $desc);
        else
            $dbutil->updateGeoMetadata($db_params,$cil_id,$sindex,$object_id,
                $desc);
        $array = array();
        $array[$this->success] = true;
        $this->response($array);
    }
    
    public function geodata_post($cil_id="0",$sindex="0")
    {
        $db_params = $this->config->item('db_params');
        $dbutil = new DBUtil();
        $json_str = file_get_contents('php://input', 'r');
        $index = intval($sindex);
        
        $exists = $dbutil->geoDataExist($db_params,$cil_id,$index);
        if(!$exists)
            $dbutil->insertGeoData($db_params,$cil_id,$index,$json_str);
        else
            $dbutil->updateGeoData($db_params,$cil_id,$index,$json_str);
        $array = array();
        $array[$this->success] = $exists;
        $this->response($array);
    }
    
    public function geodata_get($cil_id="0",$sindex="0")
    {
        $db_params = $this->config->item('db_params');
        $dbutil = new DBUtil();
        $json = $dbutil->getGeoData($db_params,$cil_id,$sindex);
        if(!is_null($json))
        {
           $this->response($json);
        }
        else
        {
            //$array = array();
            //$array[$this->success] = false;
            //$this->response($array);
            $json_str = "{\"type\":\"FeatureCollection\",\"features\":[]}";
            $json = json_decode($json_str);
            $this->response($json);
        }
    }
    
    /*
    public function geodata_exists_get($cil_id="0",$sindex="0")
    {
        $db_params = $this->config->item('db_params');
        $dbutil = new DBUtil();
        $index = intval($sindex);
        $exists = $dbutil->geoDataExist($db_params,$cil_id,$index);
        $array = array();
        $array[$this->success] = $exists;
        $this->response($array);
    }
    
    public function geometadata_exists_get($cil_id="0",$sindex="0",$object_id="0")
    {
        $db_params = $this->config->item('db_params');
        $dbutil = new DBUtil();
        $exists =$dbutil->geoMetadataExist($db_params,$cil_id,$sindex,$object_id);
    
        $array = array();
        $array[$this->success] = $exists;
        $this->response($array);
    } 
     
    */

    
}

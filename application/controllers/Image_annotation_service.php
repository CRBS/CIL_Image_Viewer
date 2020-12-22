<?php

require_once './application/libraries/REST_Controller.php';
require_once 'GeneralUtil.php';
require_once 'DBUtil.php';
require_once 'DataLocalUtil.php';

class Image_annotation_service extends REST_Controller
{
    private $success = "success";
    
    public function cropinfo_get($crop_id="0")
    {
        error_reporting(0);
        $db_params = $this->config->item('db_params');
        $dbutil = new DBUtil();
        $array = $dbutil->getCropInfo($db_params, $crop_id);
        $this->response($array);
    }

    
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
    
    
    public function keywordsearch_post($image_id="0")
    {
        $debug = false;
        $debugFolder = "C:/Test3/debug_json";
        $debugFile = $debugFolder."/keyword_search.log";
        
        if($debug)
        {
            if(file_exists($debugFile))
                unlink($debugFile);
        }
        
        $db_params = $this->config->item('db_params');
        $dbutil = new DBUtil();
        $keywords = file_get_contents('php://input', 'r');
        if(!is_null($keywords))
            $keywords = trim ($keywords);
        $mainArray = array();
        if(is_null($keywords) || strlen($keywords) == 0 || strcmp($keywords, "*") ==0)
        {
            if($debug)
                file_put_contents($debugFile, "Get all");
            $mainArray = $dbutil->getAllAnnotations($db_params, $image_id);
        }
        else
        {
            if($debug)
                file_put_contents($debugFile, "Do search:".$keywords."-----");
            $mainArray = $dbutil->searchAnnotations ($db_params, $image_id, $keywords);
        }
        
        $this->response($mainArray);
    }
    
    public function image_notes_get($cil_id="0",$sindex="0")
    {
        $db_params = $this->config->item('db_params');
        $dbutil = new DBUtil();
        $json = $dbutil->getNotesData($db_params, $cil_id);
        if(!is_null($json))
        {
           $this->response($json);
        }
        else
        {
            $json_str = "[]";
            $json = json_decode($json_str);
            $this->response($json);
        }
    }
    
    public function image_notes_post($cil_id="0")
    {
        $db_params = $this->config->item('db_params');
        $dbutil = new DBUtil();
        $json_str = file_get_contents('php://input', 'r');
        
        $dbutil->insertImageNotesHistory($db_params, $cil_id, $json_str);
        
        $exists = $dbutil->imageNotesExist($db_params, $cil_id);
        if(!$exists)
            $dbutil->insertImageNotes ($db_params, $cil_id, $json_str);
        else
            $dbutil->updateImageNotes ($db_params, $cil_id, $json_str);
        
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
        
        $dbutil->insertGeoDataHisotry($db_params,$cil_id,$index,$json_str);
        
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

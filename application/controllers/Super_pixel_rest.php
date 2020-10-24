<?php

require_once './application/libraries/REST_Controller.php';
require_once 'GeneralUtil.php';
require_once 'DBUtil.php';
require_once 'DataLocalUtil.php';

class Super_pixel_rest extends REST_Controller
{
    private $success = "success";
    
    public function test_get()
    {
        $done = true;
        $array = array();
        $array['done'] = $done;
        
        $this->response($array);
    }
    
    public function isRunOverlayDone_get($sp_id="0")
    {
        $is_prod = $this->config->item('is_prod');
            
        $super_pixel_prefix = $this->config->item('super_pixel_prefix');
        $subFolder1 = $super_pixel_prefix."/".$sp_id;
        $overlayFolder = $subFolder1."/overlays";
            
        $done = false;
        $response =  exec("ls ".$subFolder1);
        
        $files = scandir($overlayFolder);
        foreach($files as $file)
        {

            //echo "\nFile:".$file;
            if(strcmp($file, "DONE.txt") == 0)
                $done = true;
        }
        
        $array = array();
        $array['done'] = $done;
        
        $this->response($array);
    }
    
    public function isGenMasksDone_get($sp_id)
    {
        $is_prod = $this->config->item('is_prod');
            
        $super_pixel_prefix = $this->config->item('super_pixel_prefix');
        $subFolder1 = $super_pixel_prefix."/".$sp_id;
        $overlayFolder = $subFolder1."/masks";
            
        $done = false;
        $response =  exec("ls ".$subFolder1);
        
        $files = scandir($overlayFolder);
        foreach($files as $file)
        {

            //echo "\nFile:".$file;
            if(strcmp($file, "DONE.txt") == 0)
                $done = true;
        }
        
        $array = array();
        $array['done'] = $done;
        
        $this->response($array);
        
    }
    
    
    
}
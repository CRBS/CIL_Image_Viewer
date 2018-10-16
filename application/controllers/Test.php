<?php
require_once './application/libraries/REST_Controller.php';
require_once 'GeneralUtil.php';
class Test extends REST_Controller
{
    private $success = "success";
    public function image_exists_get($image_id="0")
    {
        $array = array();
        $image_tar_dir = $this->config->item('image_tar_dir');
        if(file_exists($image_tar_dir."/".$image_id))
            $array[$this->success] = true;
        else
            $array[$this->success] = false;
        
        $this->response($array);
    }
}

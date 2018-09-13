<?php
require_once 'GeneralUtil.php';
class DataLocalUtil
{
    private $success = "success";
    public function getLocalImageInfo($image_id, $image_tar_dir)
    {
        $data_path = $image_tar_dir."/".$image_id;
        if(!file_exists($data_path))
        {
            $array = array();
            $array[$this->success] = false;
            return $array;
        }    
        $files = scandir($data_path);
        $max_z = 0;
        foreach($files as $file)
        {
            $name = str_replace(".tar", "", $file);
            if(!is_numeric($name))
                continue;
            
            $id  = intval($name);
            if($id > $max_z)
                $max_z = $id;
        }
        
        $array = array();
        $array[$this->success] = true;
        $array['max_z'] = $max_z;
        $array['is_rgb'] = true;
        $array['max_zoom'] = 7;
        $array['init_lat'] = -65;
        $array['init_lng'] = -70;
        $array['init_zoom'] = 1;
        $array['is_public'] = true;
        $array['is_timeseries'] = false;
        $array['max_t'] = 0;
        //$array['data_path'] = $data_path;
        
        return $array;
    }
}


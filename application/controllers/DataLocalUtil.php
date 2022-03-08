<?php
require_once 'GeneralUtil.php';
class DataLocalUtil
{
    private $success = "success";
    
    
    private function findMaxZoom($tarPath)
    {

        $max_zoom = 0;
        $dir = 'phar://'.$tarPath."/0";
        $files = scandir($dir);
        if(!is_null($files))
        {
            foreach($files as $file)
            {
                if(is_numeric($file))
                {
                    $zoom = intval($file);
                    if($zoom > $max_zoom)
                        $max_zoom = $zoom;
                }
            }
        }
        return $max_zoom;

    }
    
    
    private function isTarDirectory($image_id, $image_tar_dir)
    {
        $gutil = new GeneralUtil();
        $data_path = $image_tar_dir."/".$image_id;
        if(!file_exists($data_path))
            return false;
        
        $files = scandir($data_path);
        foreach($files as $file)
        {
            if($gutil->endsWith($file, ".tar"))
               return true;
        }
        
        //echo "<br/>Is Tar Directory";
        
        return false;
    }
    
    
    private function isSqliteDirectory($image_id, $image_tar_dir)
    {
        $gutil = new GeneralUtil();
        $data_path = $image_tar_dir."/".$image_id;
        if(!file_exists($data_path))
            return false;
        
        $files = scandir($data_path);
        foreach($files as $file)
        {
            if($gutil->endsWith($file, ".sqllite3"))
               return true;
        }
        
        echo "<br/>Is Tar Directory";
        
        return false;
    }
    
    private function getLocalTarInfo($image_id, $image_tar_dir)
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
        
        
        $temp_max_zoom = 0;
        if(file_exists($data_path."/0.tar"))
        {
            $temp_max_zoom = $this->findMaxZoom($data_path."/0.tar");
        }
        if($temp_max_zoom > 0)
            $max_zoom = $temp_max_zoom;
        else
            $max_zoom = 7;
        
        $init_zoom = 0;
        if($max_zoom > 0)
            $init_zoom = 1;
        
        $array = array();
        $array[$this->success] = true;
        $array['max_z'] = $max_z;
        $array['is_rgb'] = true;
        $array['max_zoom'] = $max_zoom;
        $array['init_lat'] = -15;//-65;
        $array['init_lng'] = -7;//-70;
        $array['init_zoom'] = $init_zoom;
        $array['is_public'] = true;
        $array['is_timeseries'] = false;
        $array['max_t'] = 0;
        $array['x_pixel_offset'] = 0;
        $array['y_pixel_offset'] = 0;
        //$array['data_path'] = $data_path;
        
        return $array;
    }
    
    
    
    private function getLocalSqliteInfo($image_id, $image_tar_dir)
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
            $name = str_replace(".sqllite3", "", $file);
            if(!is_numeric($name))
                continue;
            
            $id  = intval($name);
            if($id > $max_z)
                $max_z = $id;
        }
        
        
        $temp_max_zoom = 0;
        if(file_exists($data_path."/gdal_info.json"))
        {
            $gjson_str = file_get_contents($data_path."/gdal_info.json");
            if(!is_null($gjson_str))
            {
                $gjson = json_decode($gjson_str);
                if(isset($gjson->max_zoom))
                    $temp_max_zoom = $gjson->max_zoom;
            }
        }
        if($temp_max_zoom > 0)
            $max_zoom = $temp_max_zoom;
        else
            $max_zoom = 7;
        
        $init_zoom = 0;
        if($max_zoom > 0)
            $init_zoom = 1;
        
        $array = array();
        $array[$this->success] = true;
        $array['max_z'] = $max_z;
        $array['is_rgb'] = true;
        $array['max_zoom'] = $max_zoom;
        $array['init_lat'] = -15;//-65;
        $array['init_lng'] = -7;//-70;
        $array['init_zoom'] = $init_zoom;
        $array['is_public'] = true;
        $array['is_timeseries'] = false;
        $array['max_t'] = 0;
        $array['x_pixel_offset'] = 0;
        $array['y_pixel_offset'] = 0;
        //$array['data_path'] = $data_path;
        
        return $array;
    }
    
    
    public function getLocalImageInfo($image_id, $image_tar_dir)
    {
        if($this->isTarDirectory($image_id, $image_tar_dir))
            return $this->getLocalTarInfo ($image_id, $image_tar_dir);
        else if($this->isSqliteDirectory($image_id, $image_tar_dir))
            return $this->getLocalSqliteInfo ($image_id, $image_tar_dir);
        else
        {
            $array = array();
            $array[$this->success] = false;
            return $array;
        }
    }
    
    /*
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
        
        
        $temp_max_zoom = 0;
        if(file_exists($data_path."/0.tar"))
        {
            $temp_max_zoom = $this->findMaxZoom($data_path."/0.tar");
        }
        if($temp_max_zoom > 0)
            $max_zoom = $temp_max_zoom;
        else
            $max_zoom = 7;
        
        $init_zoom = 0;
        if($max_zoom > 0)
            $init_zoom = 1;
        
        $array = array();
        $array[$this->success] = true;
        $array['max_z'] = $max_z;
        $array['is_rgb'] = true;
        $array['max_zoom'] = $max_zoom;
        $array['init_lat'] = -15;//-65;
        $array['init_lng'] = -7;//-70;
        $array['init_zoom'] = $init_zoom;
        $array['is_public'] = true;
        $array['is_timeseries'] = false;
        $array['max_t'] = 0;
        $array['x_pixel_offset'] = 0;
        $array['y_pixel_offset'] = 0;
        //$array['data_path'] = $data_path;
        
        return $array;
    }
     
     */
}


<?php

class DBUtil 
{
    
    /*
    private $error_type = "error_type";
    private $error_message = "error_message";
    */
    
    private $success = "success";
    
    
    public function handleImageUpdate($db_params,$image_id,$array)
    {
        if($this->imageExist($db_params, $image_id))
            $this->updateImage ($db_params, $image_id, $array);
        else
            $this->insertImage ($db_params, $image_id, $array);
                
    }
    
    
    public function getTrainingModels($db_params)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return null;
        $sql = " select id, name, doi_url from cdeep3m_training_model order by id asc";
        $result = pg_query($conn,$sql);
        if(!$result) 
        {
            pg_close($conn);
            return null;
        }
        $main = array();
        while($row = pg_fetch_row($result))
        {
            $array = array();
            $array['id'] = intval($row[0]);
            $array['name'] = $row[1];
            $array['doi_url'] = $row[2];
            array_push($main, $array);
        }
        pg_close($conn);
        $json_str = json_encode($main);
        $json = json_decode($json_str);
        return $json;
    }
    
    
    public function getCropProcessInfo($db_params,$id=0)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return null;
        $sql = "select id,image_id,width,height,upper_left_x,upper_left_y,starting_z,ending_z,contact_email,submit_time,original_file_location,contrast_enhancement,is_cdeep3m_preview,is_cdeep3m_run,training_model_url ".
               " from cropping_processes where id = $1";
        $input = array();
        array_push($input, $id);
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return null;
        }
        $output = array();
        if($row = pg_fetch_row($result))
        {
            $output['success'] = true;
            $output['id'] = $row[0];
            $output['image_id'] = $row[1];
            $output['width'] = intval($row[2]);
            $output['height'] = intval($row[3]);
            $output['upper_left_x'] = intval($row[4]);
            $output['upper_left_y'] = intval($row[5]);
            $output['starting_z'] = intval($row[6]);
            $output['ending_z'] = intval($row[7]);
            $output['contact_email'] = $row[8];
            $output['submit_time'] = $row[9];
            $output['original_file_location'] = $row[10];
            $contrast_enhancement = $row[11];
            $output['contrast_enhancement'] = false;
            if(strcmp($contrast_enhancement,"t")==0)
                    $output['contrast_enhancement'] = true;
            
            $is_cdeep3m_preview = $row[12];
            $output['is_cdeep3m_preview'] = false;
            if(strcmp($is_cdeep3m_preview,"t")==0)
                $output['is_cdeep3m_preview'] = true;
            
            $is_cdeep3m_run = $row[13];
            $output['is_cdeep3m_run'] = false;
            if(strcmp($is_cdeep3m_run,"t")==0)
                $output['is_cdeep3m_run'] = true;
            
            $output['training_model_url'] = $row[14];
        }
        pg_close($conn);
        
        $json_str = json_encode($output);
        $json = json_decode($json_str);
        return $json;
    }
    
    public function getNextId($db_params)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return null;
        $sql = "select nextval('general_sequence')";
        $result = pg_query($conn,$sql);
        if(!$result) 
        {
            pg_close($conn);
            return null;
        }
        $id = null;
        if($row = pg_fetch_row($result))
        {
            $id = $row[0];
        }
        pg_close($conn);
        return $id;
    }
    
    
    public function getOriginalFileLocation($db_params,$image_id)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return null;
        $sql = "select original_file_location from images where image_id = $1";
        $input = array();
        array_push($input,$image_id);  //1
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return null;
        }
        $location = null;
        if($row = pg_fetch_row($result))
        {
            $location = $row[0];
        }
        pg_close($conn);
        return $location;
    }
    
    
    public function insertCroppingInfoWithTraining($db_params,$image_id, $upper_left_x, $upper_left_y,
            $width, $height,$contact_email, $original_file_location,$starting_z,$ending_z,$contrast_enhancement, 
            $is_cdeep3m_preview, $is_cdeep3m_run, $training_model_url)
    {
        $id = $this->getNextId($db_params);
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return false;
        if($contrast_enhancement)
            $contrast_enhancement = 'true';
        else
            $contrast_enhancement = 'false';
        
        if($is_cdeep3m_preview)
            $is_cdeep3m_preview = 'true';
        else
            $is_cdeep3m_preview = 'false';
        
        if($is_cdeep3m_run)
            $is_cdeep3m_run = 'true';
        else
            $is_cdeep3m_run = 'false';
        
        $sql = "insert into cropping_processes(id,image_id,upper_left_x, upper_left_y,width,height, ".
               "\n contact_email,original_file_location,submit_time,starting_z,ending_z,contrast_enhancement, ".
               "\n is_cdeep3m_preview,is_cdeep3m_run,training_model_url) ".
               "\n values(".$id.",$1,$2,$3,$4,$5, ".
               "\n $6, $7, now(), $8, $9, ".$contrast_enhancement.", ".
               "\n ".$is_cdeep3m_preview.", ".$is_cdeep3m_run.", $10)";
        
        $input = array();
        array_push($input,$image_id);  //1
        array_push($input,$upper_left_x); //2
        array_push($input,$upper_left_y); //3
        array_push($input,$width); //4
        array_push($input,$height); //5
        array_push($input,$contact_email); //6
        array_push($input,$original_file_location); //7
        array_push($input,intval($starting_z)); //8
        array_push($input,intval($ending_z)); //9
        array_push($input,$training_model_url); //10
        
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return $id;
    }
    
    
    public function insertCroppingInfo($db_params,$image_id, $upper_left_x, $upper_left_y,
            $width, $height,$contact_email, $original_file_location,$starting_z,$ending_z,$contrast_enhancement)
    {
        $id = $this->getNextId($db_params);
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return false;
        if($contrast_enhancement)
            $sql = "insert into cropping_processes(id,image_id,upper_left_x, upper_left_y,width,height, contact_email,original_file_location,submit_time,starting_z,ending_z,contrast_enhancement) ".
               " values(".$id.", $1, $2,$3,$4,$5,$6,$7,now(),$8,$9,true)";
        else
            $sql = "insert into cropping_processes(id,image_id,upper_left_x, upper_left_y,width,height, contact_email,original_file_location,submit_time,starting_z,ending_z,contrast_enhancement) ".
               " values(".$id.", $1, $2,$3,$4,$5,$6,$7,now(),$8,$9,false)";
        
        
        $input = array();
        array_push($input,$image_id);  //1
        array_push($input,$upper_left_x); //2
        array_push($input,$upper_left_y); //3
        array_push($input,$width); //4
        array_push($input,$height); //5
        array_push($input,$contact_email); //6
        array_push($input,$original_file_location); //7
        array_push($input,intval($starting_z)); //8
        array_push($input,intval($ending_z)); //9
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return $id;
    }
    
    public function updateImage($db_params,$image_id,$array)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return false;
        $sql = "update images set max_z = $1, is_rgb = $2, update_timestamp=now(), \n".
               " is_public = $3, max_zoom = $4, init_lat = $5, init_lng = $6, \n".
               " init_zoom = $7, is_timeseries = $8, max_t = $9 where image_id = $10";
        $input = array();
        array_push($input,$array['max_z']);
        array_push($input,$array['is_rgb']);
        array_push($input,$array['is_public']);
        array_push($input,$array['max_zoom']);
        array_push($input,$array['init_lat']);
        array_push($input,$array['init_lng']);
        array_push($input,$array['init_zoom']);
        array_push($input,$array['is_timeseries']);
        array_push($input,$array['max_t']);
        array_push($input,$image_id);
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        $success = false;
        if($row = pg_fetch_row($result))
        {
            $success = true;
        }
        pg_close($conn);
        return $success;
    }
    
    public function insertImage($db_params,$image_id,$array)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return false;
        $sql = "insert into images(id,image_id,max_z,is_rgb,update_timestamp,is_public,max_zoom, init_lat, \n".
               " init_lng, init_zoom, is_timeseries, max_t) \n".
               " values(nextval('general_sequence'),$1,$2,$3,now(), $4, $5,$6, $7, $8, $9, $10)";
        $input = array();
        array_push($input,$image_id);
        array_push($input,$array['max_z']);
        array_push($input,$array['is_rgb']);
        array_push($input,$array['is_public']);
        array_push($input,$array['max_zoom']);
        array_push($input,$array['init_lat']);
        array_push($input,$array['init_lng']);
        array_push($input,$array['init_zoom']);
        array_push($input,$array['is_timeseries']);
        array_push($input,$array['max_t']);
        $result = pg_query_params($conn,$sql,$input);
        
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        $success = false;
        if($row = pg_fetch_row($result))
        {
            $success = true;
        }
        pg_close($conn);
        return $success;
    }
    
    private function imageExist($db_params,$image_id)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return false;
        $sql = "select image_id from images where image_id = $1";
        $input = array();
        array_push($input,$image_id);
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        $exist = false;
        if($row = pg_fetch_row($result))
        {
            $exist = true;
        }
        pg_close($conn);
        return $exist;
    }
    
    public function getImageInfo($db_params,$image_id)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return null;
        }
        $input = array();
        $sql = "select max_z, is_rgb, max_zoom, init_lat, init_lng, init_zoom,is_public , is_timeseries, max_t,x_pixel_offset, y_pixel_offset,max_x, max_y ".
               " from images where image_id = $1";
        array_push($input,$image_id);
        
        
        //error_log($sql, 3, "C:/Test/imageinfo.log");
        
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return null;
        }
        
        $array = null;
        if($row = pg_fetch_row($result))
        {
            $array = array();
            $array[$this->success] = true;
            $array['max_z'] = intval($row[0]);
            
            $temp = $row[1];
            if(strcmp($temp,'t')==0)
              $array['is_rgb'] = true;
            else
              $array['is_rgb'] = false;
            
            $array['max_zoom'] = intval($row[2]);
            $array['init_lat'] = intval($row[3]);
            $array['init_lng'] = intval($row[4]);
            $array['init_zoom'] = intval($row[5]);
            
            $temp = $row[6];
            if(strcmp($temp,'t')==0)
              $array['is_public'] = true;
            else
              $array['is_public'] = false;
            
            
            $temp = $row[7];
            if(strcmp($temp, 't')==0)
              $array['is_timeseries'] = true;
            else
              $array['is_timeseries'] = false;
            
            $array['max_t'] = intval($row[8]);
            $array['x_pixel_offset'] = intval($row[9]);
            $array['y_pixel_offset'] = intval($row[10]);
            $array['max_x'] =  intval($row[11]);
            $array['max_y'] =  intval($row[12]);
        }
        
        pg_close($conn);
        return $array;
    }
    
    public function getGeoMetadata($db_params, $cil_id, $sindex,
            $object_id)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return null;
        }
        $input = array();
        $sql = "select description from  image_annotation_metadata where ".
               " cil_id = $1 and  zindex=$2 and object_id = $3 ";
        $index = intval($sindex);
        array_push($input,$cil_id);
        array_push($input,$index);
        array_push($input,$object_id);
        
        $result = pg_query_params($conn,$sql,$input);
        $desc = "";
        if(!$result) 
        {
            pg_close($conn);
            return "";
        }
        
        if($row = pg_fetch_row($result))
        {
            $desc = $row[0];
        }
        pg_close($conn);
        return $desc;
        
    }

    public function getGeoData($db_params,$cil_id, $sindex)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return null;
        }
        $input = array();
        $sql = "select geo_json from image_annotation where cil_id = $1 and z_index = $2";
        $index = intval($sindex);
        array_push($input,$cil_id);
        array_push($input,$index);        
        $result = pg_query_params($conn,$sql,$input);
        
        if(!$result) 
        {
            pg_close($conn);
            return null;
        }
        $json = null;
        if($row = pg_fetch_row($result))
        {
            $json_str = $row[0];
            $json = json_decode($json_str);
        }
        pg_close($conn);
        return $json;
    }
    
    public function geoMetadataExist($db_params,$cil_id,$sindex,$object_id)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return null;
        }
        $input = array();
        $sql = "select id from image_annotation_metadata where cil_id=$1 and zindex=$2 and object_id = $3";
    
        $index = intval($sindex);
        array_push($input,$cil_id);
        array_push($input,$index); 
        array_push($input,$object_id);
        
        $result = pg_query_params($conn,$sql,$input);
        
        if (!$result) 
        {
            pg_close($conn);
            return false;
        }
        $exists = false;
        if($row = pg_fetch_row($result))
        {
            $exists = true;
        }
        pg_close($conn);
       
        return $exists;
        
    }
    
    
    public function authenticateWebUser($db_params,$username,$password)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return false;
        $input = array();
        $sql = "select id from users where username = $1 and passkey = $2";
        array_push($input,$username);
        array_push($input,$password);
        
        $result = pg_query_params($conn,$sql,$input);
        if (!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        $exists = false;
        if($row = pg_fetch_row($result))
        {
            $exists = true;
        }
        pg_close($conn);
       
        return $exists;
    }
    
    public function geoDataExist($db_params,$cil_id, $sindex)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return null;
        }
        $input = array();
        $sql = "select id from image_annotation where cil_id = $1 and z_index = $2";
        $index = intval($sindex);
        array_push($input,$cil_id);
        array_push($input,$index);        
        $result = pg_query_params($conn,$sql,$input);
        
        if (!$result) 
        {
            pg_close($conn);
            return null;
        }
        $exists = false;
        if($row = pg_fetch_row($result))
        {
            $exists = true;
        }
        pg_close($conn);
       
        return $exists;
    }
    
    public function updateGeoMetadata($db_params,$cil_id,$sindex,
            $object_id,$desc)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return null;
        }
        $input = array();
        array_push($input, $desc);
        array_push($input, $cil_id);
        $index= intval($sindex);
        array_push($input, $index);
        array_push($input, $object_id);
        $sql = "update image_annotation_metadata set description = $1, update_time=now() where ".
               " cil_id = $2 and zindex=$3 and object_id=$4";
                $result = pg_query_params($conn,$sql,$input);
        if (!$result) 
        {
            pg_close($conn);
            return false;
        }
        pg_close($conn);
        
        return true;
    }
    
    
    public function updateIprocessFinishTime($db_params,$crop_id)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return false;
        }
        $input = array();
        array_push($input, intval($crop_id));
        $sql = "update cropping_processes set finish_time = now() where id = $1";
        
        $result = pg_query_params($conn,$sql,$input);
        if (!$result) 
        {
            pg_close($conn);
            return false;
        }
        pg_close($conn);
        
        return true;
    }
    
    public function updateGeoData($db_params,$cil_id, $index, $json_str)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return null;
        }
        $input = array();
        array_push($input, $json_str);
        array_push($input, $cil_id);
        array_push($input, $index);
        $sql = "update image_annotation set geo_json = $1, update_timestamp=now() where cil_id = $2 and z_index = $3";
    
        $result = pg_query_params($conn,$sql,$input);
        if (!$result) 
        {
            pg_close($conn);
            return null;
        }
        pg_close($conn);
        
        return true;
        
    }
    
    public function insertGeoMetadata($db_params,$cil_id, $sindex, $object_id,
            $desc)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return null;
        }
        $input = array();
        array_push($input, $cil_id);
        $index = intval($sindex);
        array_push($input, $index);
        array_push($input, $object_id);
        array_push($input, $desc);
        $sql = "insert into image_annotation_metadata(id,cil_id,zindex,object_id,description,update_time) ".
               " values(nextval('general_sequence'),$1,$2,$3,$4, now())";
    
        $result = pg_query_params($conn,$sql,$input);
        
        if (!$result) 
        {
            pg_close($conn);
            return null;
        }
        
        pg_close($conn);
        
        return true;
    }
    
    public function deleteGeoMetadata($db_params,$cil_id,$sindex,
            $object_id)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return null;
        }
        $input = array();
        $sql = "delete from image_annotation_metadata where cil_id = $1 and ". 
               " zindex =$2 and object_id = $3";
        $index = intval($sindex);
        array_push($input, $cil_id);
        array_push($input, $index);
        array_push($input, $object_id);
    }
    
    public function insertGeoData($db_params,$cil_id, $index, $json_str)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return null;
        }
        $input = array();
        array_push($input, $cil_id);
        array_push($input, $index);
        array_push($input, $json_str);
        
        
        $sql = "insert into image_annotation(id, cil_id, z_index, geo_json, update_timestamp) ".
               " values(nextval('general_sequence'), $1, $2, $3,now())";
        $result = pg_query_params($conn,$sql,$input);
        
        if (!$result) 
        {
            pg_close($conn);
            return null;
        }
        
        pg_close($conn);
        
        return true;
        
    }
    
    
    public function insertGeoDataHisotry($db_params,$cil_id, $index, $json_str)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return null;
        }
        $input = array();
        array_push($input, $cil_id);
        array_push($input, $index);
        array_push($input, $json_str);
        
        
        $sql = "insert into image_annotation_history(id, cil_id, z_index, geo_json, update_timestamp) ".
               " values(nextval('general_sequence'), $1, $2, $3,now())";
        $result = pg_query_params($conn,$sql,$input);
        
        if (!$result) 
        {
            pg_close($conn);
            return null;
        }
        
        pg_close($conn);
        
        return true;
        
    }
    
    
}
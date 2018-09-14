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
        $sql = "select max_z, is_rgb, max_zoom, init_lat, init_lng, init_zoom,is_public , is_timeseries, max_t ".
               " from images where image_id = $1";
        array_push($input,$image_id);
        
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
    
    
}
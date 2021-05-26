<?php
require_once 'GeneralUtil.php';
class DBUtil 
{
    
    /*
    private $error_type = "error_type";
    private $error_message = "error_message";
    */
    
    private $success = "success";
    
    
    public function isPriorityReporter($cil_pgsql_db, $annotation_id, $image_id, $reporter)
    {
        $isReporter = false;
        $sql = "select id from internal_proj_priority where annotation_id = $1 and image_id = $2 and reporter = $3";
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return false;
        
        $input = array();
        array_push($input, $annotation_id);
        array_push($input, $image_id);
        array_push($input, $reporter);
        
        $result = pg_query_params($conn, $sql, $input);
        
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        if($row = pg_fetch_row($result))
        {
            $isReporter = true;
        }
        pg_close($conn);
        return $isReporter;
    }
    
    public function deletePriority($cil_pgsql_db, $annotation_id, $image_id, $reporter)
    {
        $sql = "delete from internal_proj_priority where annotation_id = $1 and image_id = $2 and reporter = $3";
        
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return false;
        
        $input = array();
        array_push($input, $annotation_id);
        array_push($input, $image_id);
        array_push($input, $reporter);
        $result = pg_query_params($conn, $sql, $input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return true;
    }
    
    public function deletePriorityAssignee($cil_pgsql_db, $annotation_id)
    {
        $sql = "delete from i_proj_priority_assignees where annotation_id = $1";
        
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return false;
        
        $input = array();
        array_push($input, $annotation_id);
        $result = pg_query_params($conn, $sql, $input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return true;
    }
    
    public function getAllPriorityAssignedBy($cil_pgsql_db, $username)
    {
        $pArray = array();
        $sql = "select pp.annotation_id, pp.image_id, pp.zindex, pp.reporter, pp.priority_name, pp.assign_time, pp.description, ".
               " pp.zoom, pp.lat, pp.lng, pp.reporter_fullname ".
               " from internal_proj_priority pp, i_proj_priority_assignees pa  where pp.annotation_id = pa.annotation_id and pa.username = $1 order by pp.assign_time desc";

        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return $pArray;
        
        $input = array();
        array_push($input, $username);
        $result = pg_query_params($conn, $sql, $input);
        if(!$result) 
        {
            pg_close($conn);
            return $pArray;
        }
        
        while($row = pg_fetch_row($result))
        {
            $item = array();
            $item['annotation_id'] = $row[0];
            $item['image_id'] = $row[1];
            $item['zindex'] = $row[2];
            $item['reporter'] = $row[3];
            $item['priority_name'] = $row[4];
            $item['assign_time'] = $row[5];
            $item['description'] = $row[6];
            $item['zoom'] = $row[7];
            $item['lat'] = $row[8];
            $item['lng'] = $row[9];
            $item['reporter_fullname'] = $row[10];
            
            array_push($pArray, $item);
            
        }
        
        pg_close($conn);
        return $pArray;
    }


    public function priorityExists($cil_pgsql_db, $annotation_id)
    {
        $sql = "select id from internal_proj_priority where annotation_id = $1";
        
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return false;
        
        $input = array();
        array_push($input, $annotation_id);
        $result = pg_query_params($conn, $sql, $input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        if($row = pg_fetch_row($result))
        {
            pg_close($conn);
            return true;
        }
        
        pg_close($conn);
        return false;
    }
    
    
    public function getPriorityAssigneeInfo($cil_pgsql_db, $image_id)
    {
        $pAarray = array();
        $sql = "select p.annotation_id, p.reporter, p.reporter_fullname, p.priority_name, p.priority_index, pa.username, pa.full_name, pa.email from internal_proj_priority p, i_proj_priority_assignees pa ".
               " where p.annotation_id = pa.annotation_id and  p.image_id =  $1 order by p.annotation_id asc";
        
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return $pAarray;
        
        $input = array();
        array_push($input, $image_id);
        
        $result = pg_query_params($conn, $sql,$input);
        if(!$result) 
        {
           pg_close($conn);
           return $pAarray; 
        }
        
        while($row = pg_fetch_row($result))
        {
            $array = array();
            $array['annotation_id'] = $row[0];
            $array['reporter'] = $row[1];
            $array['reporter_fullname'] = $row[2];
            $array['priority_name'] = $row[3];
            $array['priority_index'] = intval($row[4]);
            $array['username'] = $row[5];
            $array['full_name'] = $row[6];
            $array['email'] = $row[7];
            array_push($pAarray, $array);
        }
        
        pg_close($conn);
        return $pAarray; 
    }
    
    private function generateRandomString($length = 10) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    public function insertAuthToken($cil_pgsql_db, $username)
    {
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return false;
        $sql = "insert into cil_auth_tokens(id,username,token,token_create_time) ".
               " values(nextval('general_seq'),$1,$2, now())";
        
        $random_str = $this->generateRandomString(15);
        $input = array();
        array_push($input, $username);
        array_push($input,$random_str);
        
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return true;
        
    }
    
    
    public function getAuthToken($cil_pgsql_db, $username)
    {
        $sql = "select token from cil_auth_tokens where username = $1 order by id desc limit 1";
        $conn = pg_pconnect($cil_pgsql_db);
        $input = array();
        array_push($input,$username);
        
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return NULL;
        }
        $token = NULL;
        if($row = pg_fetch_row($result))
        {
            $token = $row[0];
        }
        pg_close($conn);
        return $token;
        
    }
    
    public function getUserInfoByUsername($cil_pgsql_db, $username)
    {
        $userInfo = array();
        $sql = "select id, username, full_name, email from cil_users where username = $1";
        
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return $userInfo;
        $input = array();
        array_push($input,$username);
        
        $result = pg_query_params($conn, $sql,$input);
        if(!$result) 
        {
           pg_close($conn);
           return $userInfo; 
        }
        
        if(($row = pg_fetch_row($result)))
        {
            $userInfo['id'] = $row[0];
            $userInfo['username'] = $row[1];
            $userInfo['full_name'] = $row[2];
            $userInfo['email'] = $row[3];
        }
        
        pg_close($conn);
        return $userInfo;
    }
    
    public function getUserInfoByUsernames($cil_pgsql_db, $usernames)
    {
        $userInfoArray = array();
    
        $userSql = "(";
        $index = 0;
        
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return $userInfoArray;
        
        foreach($usernames as $username)
        {
            if($index > 0)
                $userSql = $userSql.", ";
            
            $userSql = $userSql."'".$username."'";
            $index++;
        }
         $userSql = $userSql.")";
         
         $sql = "select id, username, full_name, email from cil_users where username in ".$userSql;
         
         //echo "<br/>".$sql;
         $result = pg_query($conn, $sql);
         if(!$result) 
         {
            pg_close($conn);
            return $userInfoArray; 
         }
         
        while($row = pg_fetch_row($result))
        {
            $array = array();
            $array['id'] = $row[0];
            $array['username'] = $row[1];
            $array['full_name'] = $row[2];
            $array['email'] = $row[3];
            
            array_push($userInfoArray, $array);
        }
         
        pg_close($conn);
        return $userInfoArray; 
    }
    
    
    public function updatePriorityDesc($cil_pgsql_db, $annotation_id)
    {
        $sql = "update internal_proj_priority set description = 'Test' where annotation_id = $1";
        
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return false;
        
        $input = array();
        array_push($input, $annotation_id); //1
        
        $result = pg_query_params($conn, $sql, $input);
        
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        pg_close($conn);
        return true;
    }
    
    public function deletePriorityAssigneeByAnnotID($cil_pgsql_db, $annotation_id)
    {
        $sql = "delete from i_proj_priority_assignees where annotation_id = $1";
        
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return false;
        
        $input = array();
        array_push($input, $annotation_id); //1
        
        $result = pg_query_params($conn, $sql, $input);
        
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        pg_close($conn);
        return true;
    }
   
    public function insertPriorityAssignee($cil_pgsql_db, $username, $full_name, $email, $annotation_id)
    {
        $sql = "insert into i_proj_priority_assignees(id, username, full_name, email, annotation_id) ".
               " values(nextval('general_seq'),  $1, $2, $3, $4);";
        
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return false;
                
        $input = array();
        array_push($input, $username); //1
        array_push($input, $full_name); //2
        array_push($input, $email); //3
        array_push($input, $annotation_id); //4
        
        $result = pg_query_params($conn, $sql, $input);
        
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        pg_close($conn);
        return true;
    }
    
    
    public function updatePriorityLevel($cil_pgsql_db, $priority_name, $priority_index, $annotation_id)
    {
        $sql = "update internal_proj_priority set priority_name = $1, priority_index = $2 where  annotation_id = $3";
        
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return false;
        
        $input = array();
        array_push($input, $priority_name); //1
        array_push($input, $priority_index);
        array_push($input, $annotation_id);
        
        $result = pg_query_params($conn, $sql, $input);
        
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        pg_close($conn);
        return true;
    }
    
    
    public function insertAnnotationPriority($cil_pgsql_db, $inputJson)
    {
        $sql = "insert into internal_proj_priority(id, annotation_id, image_id, ".
               " zindex, reporter, priority_name, priority_index, ".
               " description, lat, lng, zoom, assign_time, reporter_fullname) ".
               " values(nextval('general_seq'),$1, $2, ".
               " $3, $4, $5, $6, ".
               " $7, $8, $9, $10, now(), $11)";
        
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return false;
        
        $input = array();
        array_push($input, $inputJson->annotation_id); //1
        array_push($input, $inputJson->image_id); //2
        array_push($input, $inputJson->zindex); //3
        array_push($input, $inputJson->reporter); //4
        array_push($input, $inputJson->priority_name); //5
        array_push($input, $inputJson->priority_index);  //6
        array_push($input, $inputJson->description); //7
        array_push($input, $inputJson->lat); //8
        array_push($input, $inputJson->lng); //9
        array_push($input, $inputJson->zoom); //10
        array_push($input, $inputJson->reporter_fullname); //11
        $result = pg_query_params($conn, $sql, $input);
        
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        pg_close($conn);
        return true;
        
        
    }
    public function getAllAnnotators($db_params)
    {
        $mainArray = array();
        $sql = "select u.username, u.email, u.full_name from cil_users u, cil_user_groups g ".
               " where u.username = g.username and g.group_name =  'ncmir' order by u.full_name asc";
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return $mainArray;
        }
        
        $result = pg_query($conn,$sql);
        
        if(!$result) 
        {
            pg_close($conn);
            return $mainArray;
        }
        
        while($row = pg_fetch_row($result))
        {
            $array = array();
            $array['username'] = $row[0];
            $array['email'] = $row[1];
            $array['full_name'] = $row[2];
            
            array_push($mainArray, $array);
        }
        
        pg_close($conn);
        return $mainArray;
        
    }
    
    public function handleImageUpdate($db_params,$image_id,$array)
    {
        if($this->imageExist($db_params, $image_id))
            $this->updateImage ($db_params, $image_id, $array);
        else
            $this->insertImage ($db_params, $image_id, $array);
                
    }
    
    
    public function searchAnnotations($db_params, $image_id, $keywords)
    {
        $debug = false;
        $gutil = new GeneralUtil();
        $keywords = strtolower($keywords);
        $debugFolder = "C:/Test3/debug_json";
        $maiinArray = array();
        $sql = "select z_index, geo_json from image_annotation where cil_id = $1 and lower(geo_json) like '%".$keywords."%' order by z_index asc";
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            $json_str = json_encode($maiinArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $json = json_decode($json_str);
            return;
        }
        
        $input = array();
        array_push($input, $image_id);
        $result = pg_query_params($conn,$sql,$input);
        
        if(!$result) 
        {
            pg_close($conn);
            $json_str = json_encode($maiinArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $json = json_decode($json_str);
            return;
        }
        
        while($row = pg_fetch_row($result))
        {
            $array = array();
            $array['z_index'] = $row[0];
            $array['geo_json'] = $row[1];
            if(!is_null($array['geo_json']))
            {
                $debugFile = $debugFolder."/".$image_id."_".$array['z_index'].".json";
                if($debug)
                {
                    if(file_exists($debugFile))
                        unlink($debugFile);
                }
                
                $sjson_str = $array['geo_json'];
                $sjson = json_decode($sjson_str);
                $sjson_str = json_encode($sjson, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                
                if($debug)
                    file_put_contents($debugFile, $sjson_str);
                
                
                if(!is_null($sjson) && isset($sjson->features))
                {
                    foreach($sjson->features as $feature)
                    {
                        if(isset($feature->properties) && isset($feature->properties->desc))
                        {
                            $desc = strtolower($feature->properties->desc);
                            if($gutil->contains($desc, $keywords))
                                array_push($maiinArray, $feature);
                        }
                    }
                }
            }
        }
        
        pg_close($conn);
        $json_str = json_encode($maiinArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        if($debug)
        {
            $debugMainFile = $debugFolder."/".$image_id."_main.json";
            file_put_contents($debugMainFile, $json_str);
        }
        
        $json = json_decode($json_str);
        return $json;
    }
    
    
    public function getAllAnnotations($db_params, $image_id)
    {
        $debug = false;
        $debugFolder = "C:/Test3/debug_json";
        $maiinArray = array();
        $sql = "select z_index, geo_json from image_annotation where cil_id = $1 order by z_index asc";
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            $json_str = json_encode($maiinArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $json = json_decode($json_str);
            return;
        }
        
        $input = array();
        array_push($input, $image_id);
        $result = pg_query_params($conn,$sql,$input);
        
        if(!$result) 
        {
            pg_close($conn);
            $json_str = json_encode($maiinArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $json = json_decode($json_str);
            return;
        }
        
        while($row = pg_fetch_row($result))
        {
            $array = array();
            $array['z_index'] = $row[0];
            $array['geo_json'] = $row[1];
            if(!is_null($array['geo_json']))
            {
                $debugFile = $debugFolder."/".$image_id."_".$array['z_index'].".json";
                if($debug)
                {
                    if(file_exists($debugFile))
                        unlink($debugFile);
                }
                
                $sjson_str = $array['geo_json'];
                $sjson = json_decode($sjson_str);
                $sjson_str = json_encode($sjson, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                
                if($debug)
                    file_put_contents($debugFile, $sjson_str);
                if(!is_null($sjson) && isset($sjson->features))
                {
                    foreach($sjson->features as $feature)
                    {
                        array_push($maiinArray, $feature);
                    }
                }
            }
        }
        
        pg_close($conn);
        $json_str = json_encode($maiinArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        if($debug)
        {
            $debugMainFile = $debugFolder."/".$image_id."_main.json";
            file_put_contents($debugMainFile, $json_str);
        }
        
        $json = json_decode($json_str);
        return $json;
        
    }
    
    
    
    
    
    public function getTrainedModelByDOI($db_params,$doi)
    {
        $conn = pg_pconnect($db_params);
        $sql = "select id, model_name, doi from trained_models where doi = $1";
        
        if (!$conn) 
        {
            return null;
        }
        $input = array();
        array_push($input, $doi);
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return null;
        }
        
        $array = array();
        if($row = pg_fetch_row($result))
        {
            $array['id'] = $row[0];
            $array['model_name'] = $row[1];
            $array['doi'] = $row[2];
            
        }
        
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        pg_close($conn);
        return $json;
    }
    
    public function getPreferredCdeep3mSettings($db_params, $image_id)
    {
        $conn = pg_pconnect($db_params);
        $sql = "select id, image_id, preferred_model from preferred_cdeep3m_settings where image_id = $1";
        if (!$conn) 
        {
            return null;
        }
        $input = array();
        array_push($input, $image_id);
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return null;
        }
        
        $array = array();
        if($row = pg_fetch_row($result))
        {
             $array['id'] = intval($row[0]);
             $array['image_id'] = $row[1];
             $array['preferred_model'] = $row[2];
        }
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        pg_close($conn);
        return $json;
    }
    
    
    public function calculateRunTime($db_params, $image_id,$training_model_url, $augspeed, $frame, $num_of_slices)
    {
        $conn = pg_pconnect($db_params);
        $sql = "select EXTRACT(EPOCH FROM (finish_time - submit_time)) as run_time from cropping_processes where image_id = $1 and width = 1000 and ".
               " height = 1000 and augspeed = $2 and frame = $3 and training_model_url = $4 and use_prp = true ".
               " and finish_time is not null and submit_time is not null and (ending_z-starting_z) = $5 order by run_time desc";
        if (!$conn) 
        {
            return null;
        }
        
        $input = array();
        array_push($input, $image_id); //1
        array_push($input, $augspeed); //2
        array_push($input, $frame); //3
        array_push($input, $training_model_url); //4
        array_push($input, $num_of_slices); //5
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return null;
        }
        
        $array = array();
        while($row = pg_fetch_row($result))
        {
            $temp = intval($row[0]);
            array_push($array, $temp);
        }
        
        pg_close($conn);
        return $array;
    }
    
    
    public function getAverageRunTime($db_params, $image_id,$training_model_url, $augspeed, $frame, $num_of_slices)
    {
        $conn = pg_pconnect($db_params);
        $sql = "select avg(EXTRACT(EPOCH FROM (finish_time - submit_time))) as avg_time, count(*) as num_trials  from cropping_processes where image_id = $1 and width = 1000 and ".
               " height = 1000 and augspeed = $2 and frame = $3 and training_model_url = $4 and use_prp = true ".
               " and finish_time is not null and submit_time is not null and (ending_z-starting_z) <= $5";
        
        $input = array();
        array_push($input, $image_id); //1
        array_push($input, $augspeed); //2
        array_push($input, $frame); //3
        array_push($input, $training_model_url); //4
        array_push($input, $num_of_slices); //5
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return null;
        }
        
        $array = array();
        if($row = pg_fetch_row($result))
        {
            if(!is_null($row[0]))
                $array['average_time'] = intval($row[0]);
            else {
                $array['average_time'] = ($row[0]);
            }
            $array['count'] = $row[1];
        }
        
        $json_str = json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $json = json_decode($json_str);
        return $json;
    }
    
    public function getRuntimeTable()
    {
        $conn = pg_pconnect($db_params);
        if(!$conn) 
        {
            pg_close($conn);
            return null;
        }
        
        $sql = "select distinct image_id, training_model_url, augspeed, frame, avg(EXTRACT(EPOCH FROM (finish_time - submit_time))) as avg_time, count(*) as num_trials  from cropping_processes where  width = 1000 and ".
               " height = 1000    and use_prp = true ".
               " and finish_time is not null and submit_time is not null and (ending_z-starting_z) = 3 group by image_id, training_model_url, augspeed, frame";
        
        $result = pg_query($conn,$sql);
        if(!$result) 
        {
            pg_close($conn);
            return null;
        }
        
        $main_array = array();
        while($row = pg_fetch_row($result))
        {
            $array = array();
            $image_id = $row[0];
            $model_url = $row[1];
            $augspeed = $row[2];
            $frame = $row[3];
            $avg_rtime = $row[4];
            $num_trials = $row[5];
            
            
            $array['image_id'] = $image_id;
            $array['model_url'] = $model_url;
            $array['augspeed'] = $augspeed;
            $array['frame'] = $frame;
            $array['avg_rtime'] = $avg_rtime;
            $array['num_trials'] = $num_trials;
                    
            array_push($main_array, $array);        
        }
        
        $json_str = json_encode($main_array);
        $json = json_decode($json_str);
        pg_close($conn);
        return $json;
        
    }
    
    
    
    
    public function getCropInfo($db_params, $crop_id)
    {
        if(!is_numeric($crop_id))
            return NULL;
        $crop_id = intval($crop_id);
        $conn = pg_pconnect($db_params);
        $array = array();
        if (!$conn) 
        {
            $array['success'] = false;
        }
        
        $sql = "select contact_email from cropping_processes where id = $1";
        $input = array();
        array_push($input, $crop_id);
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            $array['success'] = false;
        }
        
        if($row = pg_fetch_row($result))
        {
            $array['success'] = true;
            $array['contact_email'] = $row[0];
        }
        else 
        {
            $array['success'] = false;
        }
        pg_close($conn);
        return $array;
    }
    

    
    public function isProcessFinished($db_params, $crop_id)
    {
        if(!is_numeric($crop_id))
            return false;
        $crop_id = intval($crop_id);
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return false;
        $sql = "select id from cropping_processes where finish_time is not null and id = $1";
        $input = array();
        array_push($input, $crop_id);
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        $finished = false;
        if($row = pg_fetch_row($result))
        {
            $finished = true;
        }
        pg_close($conn);
        return $finished;
    }
    
    public function getProcessStatus($db_params, $crop_id)
    {
        $array = array();
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            $array['finished'] = false;
            $array['error'] = false;
            $array['message'] = "Cannot access the status at this moment";
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            return $json;
        }
        $sql = "select finish_time, has_error, status_message,pod_running from cropping_processes where id = $1";
        $input = array();
        array_push($input, $crop_id);
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            $array['finished'] = false;
            $array['error'] = false;
            $array['message'] = "Cannot access the status at this moment";
            $array['pod_running'] = 'U';
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            pg_close($conn);
            return $json;
        }
        
        
        if($row = pg_fetch_row($result))
        {
            $temp = $row[0];
            if(is_null($temp))
                $array['finished'] = false;
            else
                $array['finished'] = true;
            
            $temp = $row[1];
            if(is_null($temp) || strcmp($temp, 'f') ==0)
                $array['error'] = false;
            else
                $array['error'] = true;
            
            $temp = $row[2];
            if(is_null($temp))
                $array['message'] = "Pending";
            else
                $array['message'] = $temp;
            
            
            $temp = $row[3];
            if(is_null($temp))
                $array['pod_running'] = "U";
            else
                $array['pod_running'] = $temp;
            
            $json_str = json_encode($array);
            $json = json_decode($json_str);
            pg_close($conn);
            return $json;
        }
        
        
        $array['finished'] = false;
        $array['error'] = false;
        $array['message'] = "Cannot access the status at this moment";
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        pg_close($conn);    
        return $json;
    }
    
    public function getTrainingModelsProd($cil_pgsql_db)
    {
        //echo "<br/>getTrainingModelsProd";
        
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return null;
        $sql = "select id, metadata_json from models where publish_date is not null order by  display_order desc";
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
            $id = intval($row[0]);
            $array['id'] = $id;
            
            $name = "";
            $metadata_json_str = $row[1];
            if(!is_null($metadata_json_str))
            {
                $metadata_json = json_decode($metadata_json_str);
                if(!is_null($metadata_json) && isset($metadata_json->Cdeepdm_model->Name))
                {
                    $array['name'] = $metadata_json->Cdeepdm_model->Name;
                }
                
                if(!is_null($metadata_json) && isset($metadata_json->Cdeepdm_model->Version_number))
                {
                    $array['Version_number'] = $metadata_json->Cdeepdm_model->Version_number;
                }
                else 
                {
                    $array['Version_number'] = "Unknown";
                }
            }
            $array['doi_url'] = "https://doi.org/10.7295/W9CDEEP3M".$id;
            array_push($main, $array);
        }
        pg_close($conn);
        $main = $this->sortTrainedModelByVersion($main);
        $json_str = json_encode($main);
        $json = json_decode($json_str);
        return $json;
    }
    
    private function sortTrainedModelByVersion($modelArray)
    {
        $newModel = array();
        $gutil = new GeneralUtil();
        foreach($modelArray as $model)
        {
            if($gutil->startsWith($model['Version_number'], "2"))
                array_push ($newModel, $model);
        }
        foreach($modelArray as $model)
        {
            if(!$gutil->startsWith($model['Version_number'], "2"))
                array_push ($newModel, $model);
        }
        return $newModel;
    }
    
    
    public function getTrainingModels($db_params)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return null;
        //$sql = " select id, name, doi_url from cdeep3m_training_model order by id asc";
        $sql = "select id, model_name, doi from trained_models where active = true order by id asc";
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
    
    
    public function getImageWidthHeight($db_params,$image_id)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return null;
        $sql = "select max_x,max_y from images where image_id = $1";
        $input = array();
        array_push($input, $image_id);
        
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return null;
        }
        
        $output = array();
        if($row = pg_fetch_row($result))
        {
            if(!is_null($row[0]))
                $output['max_x'] = intval($row[0]);
            else
                $output['max_x'] = 0;
            
            if(!is_null($row[1]))
                $output['max_y'] = intval($row[1]);
            else 
                $output['max_y'] = 0;
        }
        pg_close($conn);
        $json_str = json_encode($output);
        $json = json_decode($json_str);
        return $json;
    }
    
    public function getRetrainUserEmail($cil_pgsql_db, $id=0)
    {
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return null;
        $sql = "select email from retrain_models where id = $1";
        $input = array();
        array_push($input, $id);
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return null;
        }
        $email = NULL;
        if($row = pg_fetch_row($result))
        {
            $email = $row[0];
        }
    
        pg_close($conn);
        return $email;
        
    }
    
    public function getCropProcessInfo($db_params,$id=0)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return null;
        $sql = "select id,image_id,width,height,upper_left_x,upper_left_y,starting_z,ending_z,contact_email,submit_time,original_file_location,contrast_enhancement,is_cdeep3m_preview,is_cdeep3m_run,training_model_url,augspeed, frame, use_prp, finish_time,pod_running ".
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
            
            $temp = $row[15];
            if(!is_null($temp))
            {
                if(is_numeric($temp))
                {
                   $output['augspeed'] = intval($temp);
                }
            }
            
            $temp = $row[16];
            if(!is_null($temp))
            {
                $output['frame'] =$temp;
            }
          
            $temp = $row[17];
            if(strcmp($temp,"t")==0)
                $output['use_prp'] = true;
            else
                $output['use_prp'] = false;
            
            
            $output['finish_time'] = $row[18];
            $output['pod_running'] = $row[19];
            
            
            $ijson = $this->getImageWidthHeight($db_params, $output['image_id']);
            if(!is_null($ijson))
            {
                if(isset($ijson->max_x))
                    $output['image_max_x'] = $ijson->max_x;
                
                if(isset($ijson->max_y))
                    $output['image_max_y'] = $ijson->max_y;
            }
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
    
    public function getDockerImageType($db_params, $crop_id)
    {
        $defaultType = "stable";
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return $defaultType;
        $sql = "select docker_image_type from cropping_processes where id = $1";
        $input = array();
        array_push($input,$crop_id);  //1
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return $defaultType;
        }
        
        if($row = pg_fetch_row($result))
        {
            $defaultType = $row[0];
        }
        pg_close($conn);
        return $defaultType;
        
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
    
    public function upateCdeep3mImageSize($db_params, $id, $size)
    {
        //error_log("\n upateCdeep3mImageSize", 3, "/var/www/html/".$crop_id.".txt");
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return false;
        //error_log("\n Connection established", 3, "/var/www/html/".$crop_id.".txt");
        $sql = "update cropping_processes set image_size = $1 where id = $2";
        $input = array();
        array_push($input,$size);  //1
        array_push($input,$id);  //2
        
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return true;
        
    }
    
    public function insertCroppingInfoWithTraining($db_params,$image_id, $upper_left_x, $upper_left_y,
            $width, $height,$contact_email, $original_file_location,$starting_z,$ending_z,$contrast_enhancement, 
            $is_cdeep3m_preview, $is_cdeep3m_run, $training_model_url, $augspeed, $frame, $use_prp)
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
               "\n is_cdeep3m_preview,is_cdeep3m_run,training_model_url,augspeed,frame,use_prp) ".
               "\n values(".$id.",$1,$2,$3,$4,$5, ".
               "\n $6, $7, now(), $8, $9, ".$contrast_enhancement.", ".
               "\n ".$is_cdeep3m_preview.", ".$is_cdeep3m_run.", $10,$11, $12, $13)";
        
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
        array_push($input,$augspeed); //11
        array_push($input,$frame); //12
        array_push($input,$use_prp); //13
        
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return $id;
    }
    
    public function insertUserAction($db_params,$username,$ip_address,$action_type)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return false;
        $sql = "insert into cdeep3m_user_actions(id,user_name,ip_address,action_type,action_time) ".
                " values(nextval('general_sequence'),$1,$2,$3,now())";
        
        $input = array();
        array_push($input,$username);  //1
        array_push($input,$ip_address); //2
        array_push($input,$action_type); //3
        
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        pg_close($conn);
        return true;
        
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
    
    
    
    public function getAllDoi2ModelName($db_params)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return null;
        }
        $sql = "select id,model_name,doi from trained_models";
        $result = pg_query($conn,$sql);
        if(!$result) 
        {
            pg_close($conn);
            return null;
        }
        
        $array = null;
        while($row = pg_fetch_row($result))
        {
            $array = array();
            $array[$row[2]] = $row[1]; 
        }
        
        pg_close($conn);
        return $array;
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
    
    public function getLocationResults($db_params, $x, $y, $image_id)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return 0;
        }
        
        $x0 = $x-5;
        $x1 = $x+5;
        
        $y0 = $y-5;
        $y1 = $y+5;
        
        
        $sql= "select id,image_id,width,height,upper_left_x,upper_left_y,starting_z,ending_z,contact_email,submit_time,original_file_location,contrast_enhancement,is_cdeep3m_preview,is_cdeep3m_run,training_model_url,augspeed, frame, use_prp, finish_time,pod_running from cropping_processes where upper_left_x >= $1 and upper_left_x <= $2 and upper_left_y >= $3 and upper_left_y <= $4 and image_id = $5 and finish_time is not NULL";
        $input = array();
        array_push($input,$x0);
        array_push($input,$x1);
        array_push($input,$y0);
        array_push($input,$y1);
        array_push($input,$image_id);
        $result = pg_query_params($conn,$sql,$input);
        
        $count= 0;
        if(!$result) 
        {
            pg_close($conn);
            return 0;
        }
        
        $mainArray = array();
        
        while($row = pg_fetch_row($result))
        {
            $output = array();
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
            
            $temp = $row[15];
            if(!is_null($temp))
            {
                if(is_numeric($temp))
                {
                   $output['augspeed'] = intval($temp);
                }
            }
            
            $temp = $row[16];
            if(!is_null($temp))
            {
                $output['frame'] =$temp;
            }
          
            $temp = $row[17];
            if(strcmp($temp,"t")==0)
                $output['use_prp'] = true;
            else
                $output['use_prp'] = false;
            
            
            $output['finish_time'] = $row[18];
            $output['pod_running'] = $row[19];
            
            
            $ijson = $this->getImageWidthHeight($db_params, $output['image_id']);
            if(!is_null($ijson))
            {
                if(isset($ijson->max_x))
                    $output['image_max_x'] = $ijson->max_x;
                
                if(isset($ijson->max_y))
                    $output['image_max_y'] = $ijson->max_y;
            }
            
            array_push($mainArray, $output);
        }
        pg_close($conn);
        
        $json_str = json_encode($mainArray);
        $json = json_decode($json_str);
        return $json;
    }
    
    public function countLocationResult($db_params, $x, $y, $image_id)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return 0;
        }
        
        $x0 = $x-5;
        $x1 = $x+5;
        
        $y0 = $y-5;
        $y1 = $y+5;
        
        
        $sql= "select count(id) from cropping_processes where upper_left_x >= $1 and upper_left_x <= $2 and upper_left_y >= $3 and upper_left_y <= $4 and image_id = $5 and finish_time is not NULL";
        $input = array();
        array_push($input,$x0);
        array_push($input,$x1);
        array_push($input,$y0);
        array_push($input,$y1);
        array_push($input,$image_id);
        $result = pg_query_params($conn,$sql,$input);
        
        $count= 0;
        if(!$result) 
        {
            pg_close($conn);
            return 0;
        }
        
        if($row = pg_fetch_row($result))
        {
            if(is_numeric($row[0]))
                $count = intval($row[0]);
        }
        pg_close($conn);
        
        return $count;
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
    
    
    public function isAdmin($cil_pgsql_db, $username)
    {
        
        $sql = "select role from cil_users u, cil_roles r where u.user_role = r.id and username = $1 and role = 'admin'";
        $conn = pg_pconnect($cil_pgsql_db);
        if(!$conn)
        {
            return false;
        }
        
        $input = array();
        array_push($input,$username);
        $result = pg_query_params($conn, $sql, $input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        $isAdmin = false;
        
        if($row = pg_fetch_row($result))
        {
            $isAdmin = true;
        }
        pg_close($conn);
        return $isAdmin;
        
    }
    
    public function isTokenCorrect($cil_pgsql_db,$username,$token)
    {
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return false;
        
        $sql = "select id from cil_auth_tokens where username = $1 and token = $2";
        $input = array();
        array_push($input,$username);
        array_push($input,$token);
    
        $result = pg_query_params($conn,$sql,$input);
        if (!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        $isCorrect = false;
        if($row = pg_fetch_row($result))
        {
            $isCorrect = true;
        }
        pg_close($conn);
        return $isCorrect;
        
    }
    
    public function getPortalUserInfo($cil_pgsql_db,$username)
    {
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
            return NULL;
        $sql = "select id,username,pass_hash,email,full_name from cil_users where username = $1";
        $input = array();
        array_push($input,$username);
       
        $result = pg_query_params($conn,$sql,$input);
        if (!$result) 
        {
            pg_close($conn);
            return NULL;
        }
        
        $array = NULL;
        if($row = pg_fetch_row($result))
        {
            $array = array();
            $array['id'] = intval($row[0]);
            $array['username'] = $row[1]; 
            $array['pass_hash'] = $row[2];
            $array['email'] = $row[3];
            $array['full_name'] = $row[4];
        }
        
        pg_close($conn);
        
        if(is_null($array))
           return NULL;
        
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        
        return $json;
    }
    
    
    public function getCdeep3mUserInfo($db_params,$username)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return NULL;
        $sql = "select id, username,pass_hash,email,full_name from cdeep3m_users where username = $1";
        $input = array();
        array_push($input,$username);
       
        $result = pg_query_params($conn,$sql,$input);
        if (!$result) 
        {
            pg_close($conn);
            return NULL;
        }
        
        $array = NULL;
        if($row = pg_fetch_row($result))
        {
            $array = array();
            $array['id'] = intval($row[0]);
            $array['username'] = $row[1]; 
            $array['pass_hash'] = $row[2];
            $array['email'] = $row[3];
            $array['full_name'] = $row[4];
        }
        
        pg_close($conn);
        
        if(is_null($array))
           return NULL;
        
        $json_str = json_encode($array);
        $json = json_decode($json_str);
        
        return $json;
    }
    public function getUserInfo($db_params,$username)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
            return NULL;
        $sql = "select id,username,email from users where username = $1";
        $input = array();
        array_push($input,$username);
       
        $result = pg_query_params($conn,$sql,$input);
        if (!$result) 
        {
            pg_close($conn);
            return NULL;
        }
        
        $array = NULL;
        if($row = pg_fetch_row($result))
        {
            $array = array();
            $array['id'] = intval($row[0]);
            $array['username'] = $row[1]; 
            $array['email'] = $row[2];
        }
        
        pg_close($conn);
        return $array;
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
    
    public function updateRunningPod($db_params,$crop_id, $running_pod)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return false;
        }
        $input = array();
        array_push($input, $running_pod);
        array_push($input, $crop_id);
        $sql = "update cropping_processes set pod_running = $1 where id = $2";
        $result = pg_query_params($conn,$sql,$input);
        if (!$result) 
        {
            pg_close($conn);
            return false;
        }
        pg_close($conn);
        
        return true;
    }
    
    
    public function updateCropProcessMessage($db_params,$crop_id, $message)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return false;
        }
        $input = array();
        array_push($input, $message);
        array_push($input, $crop_id);
        $sql = "update cropping_processes set status_message = $1 where id = $2";
        $result = pg_query_params($conn,$sql,$input);
        if (!$result) 
        {
            pg_close($conn);
            return false;
        }
        pg_close($conn);
        
        return true;   
    }
    
    public function updateDockerImageType($db_params, $imageType, $crop_id)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return fa;se;
        }
        $input = array();
        array_push($input, $imageType);
        array_push($input, $crop_id);
        $sql = "update cropping_processes set docker_image_type = $1 where id = $2";
        $result = pg_query_params($conn,$sql,$input);
        if (!$result) 
        {
            pg_close($conn);
            return false;
        }
        pg_close($conn);
        
        return true;  
        
    }
    
    
    public function updateRetrainError($cil_pgsql_db,$crop_id)
    {
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn) 
        {
            return fa;se;
        }
        $input = array();
        array_push($input, $crop_id);
        $sql = "update retrain_models set error = true where id = $1";
        $result = pg_query_params($conn,$sql,$input);
        if (!$result) 
        {
            pg_close($conn);
            return false;
        }
        pg_close($conn);
        
        return true;     
    }
    
    
    public function updateCropError($db_params,$crop_id)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return fa;se;
        }
        $input = array();
        array_push($input, $crop_id);
        $sql = "update cropping_processes set has_error = true where id = $1";
        $result = pg_query_params($conn,$sql,$input);
        if (!$result) 
        {
            pg_close($conn);
            return false;
        }
        pg_close($conn);
        
        return true;     
    }
    
    public function updateCropFinished($db_params,$crop_id)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return null;
        }
        $input = array();
        array_push($input, $crop_id);
        $sql = "update cropping_processes set crop_finish_time = now() where id = $1";
        $result = pg_query_params($conn,$sql,$input);
        if (!$result) 
        {
            pg_close($conn);
            return false;
        }
        pg_close($conn);
        
        return true;        
    }
    
    //////////////////////Image Notes/////////////////////////////////////////////
    public function getNotesData($db_params,$cil_id)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return null;
        }
        $input = array();
        array_push($input,$cil_id);
        $sql = "select notes_json from image_notes where cil_id = $1";

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
    
    
    
    public function imageNotesExist($db_params,$cil_id)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return null;
        }
        
        $input = array();
        array_push($input,$cil_id);
        
        $sql = "select id from image_notes where cil_id = $1";
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
    
    
    public function insertImageNotesHistory($db_params,$cil_id, $notes_json)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return false;
        }
        
        $input = array();
        array_push($input, $cil_id);
        array_push($input, $notes_json);
        
        $sql = "insert into image_notes_history(id, cil_id, notes_json, update_time) ".
               " values(nextval('general_sequence'), $1, $2, now())";
        
        $result = pg_query_params($conn,$sql,$input);
        
        if (!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        
        return true;
    }
    
    
    public function insertImageNotes($db_params,$cil_id, $notes_json)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return false;
        }
        
        $input = array();
        array_push($input, $cil_id);
        array_push($input, $notes_json);
        
        $sql = "insert into image_notes(id, cil_id, notes_json, update_time) ".
               " values(nextval('general_sequence'), $1, $2, now())";
        
        $result = pg_query_params($conn,$sql,$input);
        
        if (!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        
        return true;
    }
    
    
    public function updateImageNotes($db_params,$cil_id, $notes_str)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return false;
        }
        $input = array();
        array_push($input, $notes_str);
        array_push($input, $cil_id);
        $sql = "update image_notes set notes_json = $1 where cil_id = $2";
        
        $result = pg_query_params($conn,$sql,$input);
        if (!$result) 
        {
            pg_close($conn);
            return false;
        }
        pg_close($conn);
        
        return true;
    }
    
    
    //////////////////////End Image Notes////////////////////////////////////
    
    
    public function deleteSuperPixelGeoData($db_params,$cil_id)
    {
        $conn = pg_pconnect($db_params);
        if (!$conn) 
        {
            return false;
        }
        $input = array();
        array_push($input, $cil_id);
        $sql = "delete from image_annotation where cil_id like 'SP%' and  cil_id = $1";
        $result = pg_query_params($conn,$sql,$input);
        if (!$result) 
        {
            pg_close($conn);
            return null;
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
    
    
    /************** Timer *************************************/
    public function timerUpdatePodStartTime($db_params,$cropId)
    {
        $array = array();
        $conn = pg_pconnect($db_params);
        
        if(!is_numeric($cropId))
        {
            return false;
        }
        
        if (!$conn) 
        {   
            return false;
        }
        
        $cropId = intval($cropId);
        
        $input = array();
        array_push($input, $cropId);
        $sql = "update cropping_processes set pod_start = now() where id = $1";
        
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return true;
        
    }
    
    public function timerUpdateCdeep3mXyz($db_params, $cropId, $x, $y, $z)
    {
        $conn = pg_pconnect($db_params);
        
        if(!is_numeric($cropId))
        {
            return false;
        }
        
        if (!$conn) 
        {   
            return false;
        }
        
        $cropId = intval($cropId);
        $x = intval($x);
        $y = intval($y);
        $z = intval($z);
        
        $input = array();
        array_push($input, $x);
        array_push($input, $y);
        array_push($input, $z);
        array_push($input, $cropId);
        $sql = "update cropping_processes set cdeep3m_x = $1, cdeep3m_y=$2, cdeep3m_z=$3 where id = $4";
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return true;
    }
    
    public function timerUpdateNumOfPkg($db_params, $cropId, $numPkg)
    {
        $conn = pg_pconnect($db_params);
        
        if(!is_numeric($cropId))
        {
            return false;
        }
        
        if (!$conn) 
        {   
            return false;
        }
        
        $cropId = intval($cropId);
        
        $input = array();
        array_push($input, $numPkg);
        array_push($input, $cropId);
        $sql = "update cropping_processes set num_of_pkg = $1 where id = $2";
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return true;
    }
    
    public function timerUpdatePodEndTime($db_params, $cropId)
    {
        $array = array();
        $conn = pg_pconnect($db_params);
        
        if(!is_numeric($cropId))
        {
            return false;
        }
        
        if (!$conn) 
        {   
            return false;
        }
        
        $cropId = intval($cropId);
        
        $input = array();
        array_push($input, $cropId);
        $sql = "update cropping_processes set pod_end = now() where id = $1";
        
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return true;
        
    }
    
    public function timerUpdateDownloadStartTime($db_params, $cropId)
    {
        $array = array();
        $conn = pg_pconnect($db_params);
        
        if(!is_numeric($cropId))
        {
            return false;
        }
        
        if (!$conn) 
        {   
            return false;
        }
        
        $cropId = intval($cropId);
        
        $input = array();
        array_push($input, $cropId);
        $sql = "update cropping_processes set download_start = now() where id = $1";
        
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return true;
        
    }
    
    public function timerUpdateRetrainStartTime($db_params, $cropId)
    {
        $array = array();
        $conn = pg_pconnect($db_params);
        
        if(!is_numeric($cropId))
        {
            return false;
        }
        
        if (!$conn) 
        {   
            return false;
        }
        
        $cropId = intval($cropId);
        
        $input = array();
        array_push($input, $cropId);
        $sql = "update cropping_processes set retrain_start = now() where id = $1";
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return true;
    }
    
    public function timerUpdateRetrainEndTime($db_params, $cropId)
    {
        $array = array();
        $conn = pg_pconnect($db_params);
        
        if(!is_numeric($cropId))
        {
            return false;
        }
        
        if (!$conn) 
        {   
            return false;
        }
        
        $cropId = intval($cropId);
        
        $input = array();
        array_push($input, $cropId);
        $sql = "update cropping_processes set retrain_end = now() where id = $1";
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return true;
    }
    
    
    public function timerUpdateDownloadEndTime($db_params, $cropId)
    {
        $array = array();
        $conn = pg_pconnect($db_params);
        
        if(!is_numeric($cropId))
        {
            return false;
        }
        
        if (!$conn) 
        {   
            return false;
        }
        
        $cropId = intval($cropId);
        
        $input = array();
        array_push($input, $cropId);
        $sql = "update cropping_processes set download_end = now() where id = $1";
        
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return true;
        
    }
    
    
    public function timerUpdatePredictStartTime($db_params,$cropId)
    {
        $array = array();
        $conn = pg_pconnect($db_params);
        
        if(!is_numeric($cropId))
        {
            return false;
        }
        
        if (!$conn) 
        {   
            return false;
        }
        
        $cropId = intval($cropId);
        
        $input = array();
        array_push($input, $cropId);
        $sql = "update cropping_processes set predict_start = now() where id = $1";
        
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return true;
        
    }
    
    
    public function timerUpdatePredictEndTime($db_params,$cropId)
    {
        $array = array();
        $conn = pg_pconnect($db_params);
        
        if(!is_numeric($cropId))
        {
            return false;
        }
        
        if (!$conn) 
        {   
            return false;
        }
        
        $cropId = intval($cropId);
        
        $input = array();
        array_push($input, $cropId);
        $sql = "update cropping_processes set predict_end = now() where id = $1";
        
        $result = pg_query_params($conn,$sql,$input);
        if(!$result) 
        {
            pg_close($conn);
            return false;
        }
        
        pg_close($conn);
        return true;
        
    }
    
    /************** End Timer *************************************/
    
}
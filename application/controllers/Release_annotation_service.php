<?php

require_once './application/libraries/REST_Controller.php';
require_once 'GeneralUtil.php';
require_once 'DBUtil.php';
require_once 'DataLocalUtil.php';

class Release_annotation_service extends REST_Controller
{
    private $success = "success";

    public function release_annotation_post($image_id, $zindex, $property_id)
    {
        $logFile = $this->config->item('service_log_dir')."./release_annotation.log";
        $testJson = $this->config->item('service_log_dir')."./test.json";
        $previousJson = $this->config->item('service_log_dir')."./previous.json";
        
        date_default_timezone_set( 'America/Los_Angeles' );
        error_log(date("Y-m-d h:i:sa")."----".$image_id."--".$zindex."--".$property_id."\n",3,$logFile);
        
        $db_params = $this->config->item('db_params');
        $cil_pgsql_db = $this->config->item('cil_pgsql_db');
        $dbutil = new DBUtil();
        $json = $dbutil->getGeoData($db_params,$image_id,$zindex);
        
        
        $mappedImageId = $dbutil->getPublishedMappedImage($cil_pgsql_db, $image_id, $logFile);
        $pjson = $dbutil->getPublicGeoData($db_params, $mappedImageId, $zindex);
        
        file_put_contents($previousJson, json_encode($pjson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        
        
        
        $pjson_str = "{\"type\":\"FeatureCollection\",\"features\":[]}";
        $new_pjson = json_decode($pjson_str);
        $this_feature = NULL;
        if(!is_null($json))
        {
            //$json_str = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            //error_log(date("Y-m-d h:i:sa")."----".$json_str."\n",3,$logFile);
            foreach($json->features as $feature)
            {
                $properties = $feature->properties;
                
                $match = $this->handleFeatureLinks($feature);
                if(!is_null($match) && count($match) > 0)
                {
                    if(strcmp($properties->id."",$property_id)==0)
                    {
                        $pref_url = $match[0];
                        $map = $this->getUrlMap($image_id);
                        if(array_key_exists($pref_url, $map))
                           $pref_url = $map[$pref_url];

                        $feature->properties->URL = $pref_url;
                        $this_feature = $feature;
                        
                        
                    }
                }
            }
            
            error_log(date("Y-m-d h:i:sa")."----This feature ID outside:".$this_feature->properties->id."\n",3,$logFile);
                //error_log(date("Y-m-d h:i:sa")."----".$properties->id."----".$property_id."\n",3,$logFile);
                //if(strcmp($properties->id."",$property_id)==0)
                //{
                    //$fjson_str = json_encode($feature, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    //error_log(date("Y-m-d h:i:sa")."----".$fjson_str."\n",3,$logFile);
                    error_log(date("Y-m-d h:i:sa")."----Property ID:".$property_id."\n",3,$logFile);
                    
                    if(is_null($pjson))
                    {
                        error_log(date("Y-m-d h:i:sa")."----pjson is NULL:".$image_id."\n",3,$logFile);
                        $pjson_str = "{\"type\":\"FeatureCollection\",\"features\":[]}";
                        $pjson = json_decode($pjson_str);
                        
                        if(!is_null($this_feature))
                        array_push($pjson->features, $this_feature);
                    }
                    else
                    {

                        error_log(date("Y-m-d h:i:sa")."----pjson is NOT NULL:".$image_id."\n",3,$logFile);
                        ///$deleteIndex = null;
                        //$index = 0;
                        error_log(date("Y-m-d h:i:sa")."----Public feature count:".count($pjson->features)."\n",3,$logFile);
                        foreach($pjson->features as $pfeature)
                        {
                            error_log("\n".date("Y-m-d h:i:sa")."----Compare:".$pfeature->properties->id."-----".$property_id."",3,$logFile);
                            if(strcmp($pfeature->properties->id."", $property_id)!=0)
                            {
                                //$deleteIndex = $index;
                                //break;
                                error_log(":NO",3,$logFile);
                                array_push($new_pjson->features, $pfeature);
                            }
                            else 
                            {
                                error_log(":YES",3,$logFile);
                            }
                            //$index++;
                        }

                        //if(!is_null($deleteIndex))
                        //{
                        //    unset($pjson->features[$deleteIndex]);
                        //}

                        if(!is_null($this_feature))
                        {
                            error_log(date("Y-m-d h:i:sa")."----This feature is NOT NULL\n",3,$logFile);
                            array_push($new_pjson->features, $this_feature);
                        }
                        else 
                        {
                            error_log(date("Y-m-d h:i:sa")."----This feature is NULL\n",3,$logFile);
                        }
                        //$pjson = $new_pjson;
                    }

                //}
            //}
            
            
                    $pjson_str = json_encode($new_pjson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    file_put_contents($testJson, $pjson_str);
                    
                    
                    //$match_str = json_encode($match, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    //error_log(date("Y-m-d h:i:sa")."----URL match:\n".$match_str."\n",3,$logFile);
                    //error_log(date("Y-m-d h:i:sa")."----Image id:\n".$image_id."\n",3,$logFile);
                    
                    //if(!is_null($match_str))
                   // {
                    //    $matchJson = json_decode($match_str);
                    //    if(!is_null($matchJson) && count($matchJson) > 0)
                    //    {
                           // error_log("\n".date("Y-m-d h:i:sa")."----Matched url:\n".$matchJson[0]."\n",3,$logFile);
                            
                          //  error_log("\n".date("Y-m-d h:i:sa")."----After Matched url\n",3,$logFile);
                            if(!is_null($mappedImageId))
                            {
                                error_log("\n".date("Y-m-d h:i:sa")."----MappedArray is NOT NULL",3,$logFile);
                                error_log("\n".date("Y-m-d h:i:sa")."----Mapped image ID:\n".$mappedImageId."\n",3,$logFile);
                                $exists = $dbutil->geoDataExist($db_params, $mappedImageId, $zindex);
                                if(!$exists)
                                   $dbutil->insertPublicGeoData($db_params,$mappedImageId,$zindex,$pjson_str);
                                else
                                   $dbutil->updatePublicGeoData($db_params,$mappedImageId,$zindex,$pjson_str);
                            }
                            else 
                            {
                                error_log(date("Y-m-d h:i:sa")."----MappedArray is NULL",3,$logFile);
                            }
                    //    }
                    //}
                    
            
        }
        
        $array = array();
        $array["success"] = true;
        $this->response($array);
    }
    
    private function handleFeatureLinks($feature)
    {
        $matches = array();
        $props = $feature->properties;
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $props->desc, $matches);

        return $matches[0];
    }
    
    public function test_get()
    {
        $array = array();
        $array["success"] = true;
        $this->response($array);
    }
    
    
    private function getUrlMap($image_id)
    {
        $map = array();
        $cil_pgsql_db = $this->config->item('cil_pgsql_db');
        $conn = pg_pconnect($cil_pgsql_db);
        if (!$conn)
            return null;
        
        $sql = "select internal_url, public_url  from  internal_url_redirect where image_id = $1";
        $input = array();
        array_push($input, $image_id);
        
        $result = pg_query_params($conn, $sql,$input);
        if(!$result) 
        {
           pg_close($conn);
           return null; 
        }
        
        while($row = pg_fetch_row($result))
        {
            if(!is_null($row[0]) && !is_null($row[1]))
                $map[trim($row[0])] = trim($row[1]);
            
        }
        pg_close($conn);
        return $map;
        
    }
}

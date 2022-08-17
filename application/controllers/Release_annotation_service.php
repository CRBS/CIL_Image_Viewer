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
        
        
        date_default_timezone_set( 'America/Los_Angeles' );
        error_log(date("Y-m-d h:i:sa")."----".$image_id."--".$zindex."--".$property_id."\n",3,$logFile);
        
        $db_params = $this->config->item('db_params');
        $dbutil = new DBUtil();
        $json = $dbutil->getGeoData($db_params,$image_id,$zindex);
        
        if(!is_null($json))
        {
            //$json_str = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            //error_log(date("Y-m-d h:i:sa")."----".$json_str."\n",3,$logFile);
            foreach($json->features as $feature)
            {
                $properties = $feature->properties;
                //error_log(date("Y-m-d h:i:sa")."----".$properties->id."----".$property_id."\n",3,$logFile);
                if(strcmp($properties->id."",$property_id)==0)
                {
                    //$fjson_str = json_encode($feature, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    //error_log(date("Y-m-d h:i:sa")."----".$fjson_str."\n",3,$logFile);
                    
                    $pjson = $dbutil->getPublicGeoData($db_params, $image_id, $zindex);
                    if(is_null($pjson))
                    {
                        error_log(date("Y-m-d h:i:sa")."----pjson is NULL:".$image_id."\n",3,$logFile);
                        $pjson_str = "{\"type\":\"FeatureCollection\",\"features\":[]}";
                        $pjson = json_decode($pjson_str);
                        
                        array_push($pjson->features, $feature);
                    }
                    else
                    {
                        $pjson_str = "{\"type\":\"FeatureCollection\",\"features\":[]}";
                        $new_pjson = json_decode($pjson_str);
                        error_log(date("Y-m-d h:i:sa")."----pjson is NOT NULL:".$image_id."\n",3,$logFile);
                        ///$deleteIndex = null;
                        //$index = 0;
                        foreach($pjson->features as $pfeature)
                        {
                            if(strcmp($pfeature->properties->id."", $property_id)!=0)
                            {
                                //$deleteIndex = $index;
                                //break;
                                array_push($new_pjson->features, $pfeature);
                            }
                            //$index++;
                        }

                        //if(!is_null($deleteIndex))
                        //{
                        //    unset($pjson->features[$deleteIndex]);
                        //}

                        array_push($new_pjson->features, $feature);
                        $pjson = $new_pjson;
                    }
                    
                    $pjson_str = json_encode($pjson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    file_put_contents($testJson, $pjson_str);
                    $exists = $dbutil->geoDataExist($db_params, $image_id, $zindex);
                    if(!$exists)
                       $dbutil->insertPublicGeoData($db_params,$image_id,$zindex,$pjson_str);
                    else
                        $dbutil->updatePublicGeoData($db_params,$image_id,$zindex,$pjson_str);
                    
                }
            }
            
        }
        
        $array = array();
        $array["success"] = true;
        $this->response($array);
    }
    
    public function test_get()
    {
        $array = array();
        $array["success"] = true;
        $this->response($array);
    }
    
}

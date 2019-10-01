<?php
require_once 'DBUtil.php';
require_once 'CurlUtil.php';

$cil_annotation_config_file = "C:/data/cil_annotation_service_config.json"; //Staging database

$json_str = file_get_contents($cil_annotation_config_file);
$configJson = json_decode($json_str);

$db_params = $configJson->cil_annotation_pgsql_db;
$image_service_prefix = $configJson->image_service_prefix;
$image_service_auth = $configJson->image_service_auth;;
        
$image_id = "CCDB_8192";
$upper_left_x = 832;
$upper_left_y = 847;
$width = 1000;
$height = 1000;
$contact_email = "wawong@gmail.com";
$original_file_location = "/export2/ftp/data/telescience/home/CCDB_DATA_USER.portal/P2080/Experiment_8186/Subject_8188/Tissue_8190/Microscopy_8192/080309fl2_mo0cropcontadj.rec";
$starting_z=0;
$ending_z = 3;
$contrast_enhancement = false;
$is_cdeep3m_preview = true;
$is_cdeep3m_run = false;
$training_model_url = "https://doi.org/10.7295/W9CDEEP3M50682";
$augspeed = 10;
$frame = "1fm,3fm,5fm";
$use_prp = true;

$dbutil = new DBUtil();
$cutil = new CurlUtil();


for($i=0;$i<10;$i++)
{
    $id = $dbutil->insertCroppingInfoWithTraining($db_params, $image_id, $upper_left_x, $upper_left_y, $width, $height, $contact_email, $original_file_location, $starting_z, $ending_z, $contrast_enhancement, 
            $is_cdeep3m_preview, $is_cdeep3m_run, $training_model_url, $augspeed, $frame, $use_prp);


    if(is_numeric($id))
    {
        $url = $image_service_prefix."/image_process_service/image_preview/stage/".$id;
        $response = $cutil->curl_post($url, null, $image_service_auth);
        $json = json_decode($response);
        echo "<br/><br/>".  json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }


    while(true)
    {
        $finished = $dbutil->isProcessFinished($db_params,$id);
        if($finished)
        {
            echo "\nFinished!";
            break;
        }
        else
        {
            $status = $dbutil->getProcessStatus($db_params, $id);
            echo "\nCrop ID:".$id;
            echo "\nStatus:".$status->message;
            sleep(1);
        }


    }
    
}
<?php
    function curl_post($url, $data, $auth)
    {
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($doc)));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response  = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

$config_file = "C:/data/cil_metadata_config.json";
$json_str = file_get_contents($config_file);
$json = json_decode($json_str);
$urlPrefix = str_replace("metadata_service", "", $json->metadata_service_prefix);
$service_url = $urlPrefix."/image_process_service/crop/stage/3110";
echo $service_url;
$service_auth = $json->metadata_auth;

$response = curl_post($service_url, null, $service_auth);
echo $response;
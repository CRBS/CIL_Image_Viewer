<?php
    include_once 'PasswordHash.php';
    require_once 'GeneralUtil.php';
    require_once 'DBUtil.php';
    require_once 'DataLocalUtil.php';
    require_once 'Constants.php';
    require_once 'CurlUtil.php';
    
    
class Super_pixel extends CI_Controller
{
        /*
        public function view($cil_id)
        {
            $data['title'] = "Super pixel marker";
            $data['base_url'] = $this->config->item('base_url');
            $data['image_id'] = $image_id;
            
             $data['zindex'] = 0;       
                    
            $data['serverName'] = $this->config->item('base_url');
            
            $this->load->view('super_pixel/super_pixel_display', $data);
        }
         * 
         * 
         */
    
    
    
    public function gen_mask($sp_id)
    {
        $num_id = str_replace("SP_", "", $sp_id);
            
        if(!is_numeric($num_id))
           show_404 ();
        
        $sp_service_prefix = $this->config->item('sp_service_prefix');
        $sp_service_auth = $this->config->item('sp_service_auth');
        $cutil = new CurlUtil();
        $url = $sp_service_prefix."/gen_masks/".$num_id;
        $response = $cutil->curl_post($url, "", $sp_service_auth);
        $start = time();    
        $done = $this->isGenMasksDone($sp_id);
        while(!$done)
        {
            $done = $this->isGenMasksDone($sp_id);
        }
        
        
        
        $super_pixel_prefix = $this->config->item('super_pixel_prefix');
        $subFolder1 = $super_pixel_prefix."/".$sp_id;
        if(file_exists($subFolder1))
            mkdir ($subFolder1);
        
        $genMaskLog = $subFolder1."/genMask.log";
        $command = "ls ".$subFolder1;
        error_log("\n".$command,3,$genMaskLog);  
        $response =  exec($command);
        error_log("\n".$response,3,$genMaskLog);  
        $maskFolder = $subFolder1."/masks";
        $trainingZipFile = $subFolder1."/training.zip";
        $command = "cd ".$maskFolder." && zip ".$trainingZipFile." *.png";
        error_log("\n".$command,3,$genMaskLog);  
        $response =  exec($command);
        error_log("\n".$response,3,$genMaskLog);  
        
        
        //$end = time();
        //$run_time = $end - $start;
        //echo "<br/>Run time".$run_time;
        /*$filename = basename($trainingZipFile);
         $mime = mime_content_type($trainingZipFile); //<-- detect file type
        header('Content-Length: '.filesize($trainingZipFile)); //<-- sends filesize header
        header("Content-Type: $mime"); //<-- send mime-type header
        header('Content-Disposition: inline; filename="'.$filename.'";'); //<-- sends filename header
        readfile($trainingZipFile); //<--reads and outputs the file onto the output buffer
        die(); //<--cleanup
        exit; //and exit */
        
           
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false); // required for certain browsers 
        header('Content-Type: application/zip');

        header('Content-Disposition: attachment; filename="'. basename($trainingZipFile) . '";');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($trainingZipFile));
        //readfile($trainingZipFile);
        while (ob_get_level()) 
        {
         ob_end_clean();
        }
        readfile($trainingZipFile);   
        exit;
        //ob_start ();
        //exit;
        
        
        
        
    }
    
    private function isGenMasksDone($sp_id)
    {
        /*
        $is_prod = $this->config->item('is_prod');
            
        $super_pixel_prefix = $this->config->item('super_pixel_prefix');
        $subFolder1 = $super_pixel_prefix."/".$sp_id;
        $overlayFolder = $subFolder1."/masks";
            
        $done = false;
        $response =  exec("ls ".$subFolder1);
        
        $files = scandir($overlayFolder);
        foreach($files as $file)
        {

            //echo "\nFile:".$file;
            if(strcmp($file, "DONE.txt") == 0)
                $done = true;
        }
        return $done;
         * 
         */
        
        $done = false;
        $is_prod = $this->config->item('is_prod');
        $base_url = "";
        if($is_prod)
            $base_url = $this->config->item('base_url');
        else
            $base_url = "https://cdeep3m-viewer-stage.crbs.ucsd.edu";
        
        $url = $base_url."/super_pixel_rest/isGenMasksDone/".$sp_id;
        $cutil = new CurlUtil();
        $response = $cutil->curl_get($url, "");
        if(is_null($response))
        {
            return false;
        }
        
        $json = json_decode($response);
        if(is_null($json))
            return false;
        
        return $json->done;
    }
    
        
    public function isRunOverlayDone($sp_id)
    {
        /*$is_prod = $this->config->item('is_prod');
            
        $super_pixel_prefix = $this->config->item('super_pixel_prefix');
        $subFolder1 = $super_pixel_prefix."/".$sp_id;
        $overlayFolder = $subFolder1."/overlays";
            
        $done = false;
        $response =  exec("ls ".$subFolder1);
        
        $files = scandir($overlayFolder);
        foreach($files as $file)
        {

            //echo "\nFile:".$file;
            if(strcmp($file, "DONE.txt") == 0)
                $done = true;
        }*/
        
        $done = false;
        $is_prod = $this->config->item('is_prod');
        $base_url = "";
        if($is_prod)
            $base_url = $this->config->item('base_url');
        else
            $base_url = "https://cdeep3m-viewer-stage.crbs.ucsd.edu";
        
        $url = $base_url."/super_pixel_rest/isRunOverlayDone/".$sp_id;
        $cutil = new CurlUtil();
        $response = $cutil->curl_get($url, "");
        if(is_null($response))
        {
            $done = false;
        }
        else 
        {
            $json = json_decode($response);
            if(is_null($json))
                $done = false;
            else
                $done = $json->done;
        }
        
        
        
        
        $array = array();
        $array['done'] = $done;
        
        $json_str = json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        header('Content-Type: application/json');
        echo $json_str;
            
            
    }
        
        /*
        public function isRunMaskDone($sp_id)
        {
            $is_prod = $this->config->item('is_prod');
            
            $super_pixel_prefix = $this->config->item('super_pixel_prefix');
            $subFolder1 = $super_pixel_prefix."/".$sp_id;
            $maskFolder = $subFolder1."/overlays";
            
            //$maskLog = $subFolder1."/mask.log";
            $maskLog = "/var/www/html/log/mask.log";
            
            $doneFile = $maskFolder."/DONE.txt";
            $done = false;
            if(file_exists($doneFile))
                $done = true;
            $array = array();
            $array['done'] = $done;
            
            if($is_prod && $done)
            {
                //$command = "cd ".$maskFolder." && zip ".$subFolder1."/training.zip *.png";
                //error_log("\n".$command, 3, $maskLog);
                //$response = shell_exec($command);
                //error_log("\nRespone:".$response, 3, $maskLog);
            }
            else
            {
                //error_log("\nIn the else", 3, $maskLog);
            }
            

            $json_str = json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            header('Content-Type: application/json');
            echo $json_str;
        
        }*/
        public function recalculate_sp($sp_id)
        {
            $logEnable = true;
            $num_id = str_replace("SP_", "", $sp_id);
            $super_pixel_prefix = $this->config->item('super_pixel_prefix');
            $logFile = $super_pixel_prefix."/".$sp_id.".log";
            
            if($logEnable)
                error_log (date("Y-m-d h:i:sa")."Entering recalculate_sp-------------\n",3,$logFile);
            
            if(!is_numeric($num_id))
            {
                echo $num_id;
                return;
                //show_404 ();
            }
            
            $num_sp = "500";
            $compactness = 0.5;
            $sigma = 2;
            $enf_conn = "false";
            $temp = $this->input->post('sp_count_id', TRUE);
            if(!is_null($temp) && is_numeric($temp))
                $num_sp = $temp;
            
            $temp = $this->input->post('sigma_id', TRUE);
            if(!is_null($temp) && is_numeric($temp))
                $sigma = intval ($temp);
        
            
            $temp = $this->input->post('compactness_id', TRUE);
            if(!is_null($temp) && is_numeric($temp))
            {
                $compactness = floatval($temp);
                if($compactness > 1.0)
                    $compactness = 1.0;
            }
            
            $temp = $this->input->post('sc_id', TRUE);
            if(!is_null($temp))
            {
                $enf_conn = "true";
            }
                    
            $this->session->set_userdata('num_sp', $num_sp."");
            $this->session->set_userdata('sigma', $sigma."");
            $this->session->set_userdata('compactness', $compactness."");
            $this->session->set_userdata('enf_conn', $enf_conn);
            
            $zindex = 0;
            $data['title'] = "Super pixel marker";
            $base_url = $this->config->item('base_url');
            $data['base_url'] = $base_url;
            $data['image_id'] = $sp_id;
            $data['zindex'] = intval($zindex); 
            $data['serverName'] = $this->config->item('base_url');
            
            $sp_service_prefix = $this->config->item('sp_service_prefix');
            $sp_service_auth = $this->config->item('sp_service_auth');
            $cutil = new CurlUtil();
            //$url = $sp_service_prefix."/gen_superpixels/".$num_id."?N=".$num_sp."&overwrite=true";;
            $url = $sp_service_prefix."/gen_superpixels/".$num_id."?N=".$num_sp."&sigma=".$sigma."&c=".$compactness."&enf_conn=".$enf_conn."&overwrite=true";
            
            echo "<br/>".$url;
            
            
            if($logEnable)
                error_log (date("Y-m-d h:i:sa")."curl url------".$url."-------\n",3,$logFile);
            
            
            if($logEnable)
                error_log (date("Y-m-d h:i:sa")."Before curl execution for recalculate_sp-------------\n",3,$logFile);
            $response = $cutil->curl_post_no_response($url, "", $sp_service_auth);
                error_log (date("Y-m-d h:i:sa")."After curl execution for recalculate_sp-------------\n",3,$logFile);
            //echo "<br/>".$url;
            //echo "<br/>".$sp_service_auth;
            echo "<br/>".$response;
            
            $data['run_mask'] = true;
            $this->load->helper('url');
            
            error_log (date("Y-m-d h:i:sa")."Before redirect-------------\n",3,$logFile);
            //redirect ($base_url."/super_pixel/overlay/".$sp_id."/".$zindex."?run_mask=true");
            redirect ($base_url."/super_pixel/overlay/".$sp_id."/".$zindex."?run_mask=true",'location',301);
            
            
            
        }
        
        
        public function clear($sp_id="0")
        {
           //echo "Test";
            $this->load->helper('url');
            $base_url = $this->config->item('base_url');
            $db_params = $this->config->item('db_params');
            $dbutil = new DBUtil();
            $dbutil->deleteSuperPixelGeoData($db_params, $sp_id);
            redirect ($base_url."/Super_pixel/get_overlays/".$sp_id,'location',301);
        }
        
        
        public function get_overlays($sp_id="0")
        {
            $logEnable = true;
            $num_id = str_replace("SP_", "", $sp_id);
            $super_pixel_prefix = $this->config->item('super_pixel_prefix');
            $logFile = $super_pixel_prefix."/".$sp_id.".log";
            
            if($logEnable)
                error_log (date("Y-m-d h:i:sa")."Entering get_overlays-------------\n",3,$logFile);
            
            if(!is_numeric($num_id))
                show_404 ();
            
            $zindex = 0;
            $data['title'] = "Super pixel marker";
            $base_url = $this->config->item('base_url');
            $data['base_url'] = $base_url;
            $data['image_id'] = $sp_id;
            $data['zindex'] = intval($zindex); 
            $data['serverName'] = $this->config->item('base_url');
            
            $sp_service_prefix = $this->config->item('sp_service_prefix');
            $sp_service_auth = $this->config->item('sp_service_auth');
            $cutil = new CurlUtil();
            $url = $sp_service_prefix."/get_overlays/".$num_id;
            
            if($logEnable)
                error_log (date("Y-m-d h:i:sa")."Before curl execution-------------\n",3,$logFile);
            $response = $cutil->curl_post_no_response($url, "", $sp_service_auth);
                error_log (date("Y-m-d h:i:sa")."After curl execution-------------\n",3,$logFile);
            //echo "<br/>".$url;
            //echo "<br/>".$sp_service_auth;
            echo "<br/>".$response;
            
            $data['run_mask'] = true;
            $this->load->helper('url');
            
            error_log (date("Y-m-d h:i:sa")."Before redirect-------------\n",3,$logFile);
            //redirect ($base_url."/super_pixel/overlay/".$sp_id."/".$zindex."?run_mask=true");
            redirect ($base_url."/super_pixel/overlay/".$sp_id."/".$zindex."?run_mask=true",'location',301);
        }
        
        public function image($sp_id="0", $zindex="0")
        {
            $jsonUrl = "http://cildata.crbs.ucsd.edu/super_pixel/".$sp_id."/mapping.json";
            //echo $jsonUrl;
            $json_str = file_get_contents($jsonUrl);
            if(is_null($json_str))
            {
                echo "Cannot locate the image mapping json file";
                return;
            }
            
            
                
            $imageUrl = null;
            $items = json_decode($json_str);
            foreach ($items as $item)
            {
                if($item->index == $zindex)
                {
                    $imageUrl = "http://cildata.crbs.ucsd.edu/super_pixel/".$sp_id."/overlays/".$item->image_name;
                    break;
                }
            }
            
            $image_content = file_get_contents($imageUrl);
       

            // Image was not found
            $index = 0;
            while($image_content == FALSE)
            {
                $image_content = file_get_contents($imageUrl);
                sleep(1);
                $index++;
                
                if($index==5)
                    break;
            }



        header('Content-Length: '.strlen($image_content)); // sends filesize header
        header('Content-Type: image/png'); // send mime-type header
        header('Content-Disposition: inline; filename="'.basename($imageUrl).'";'); // sends filename header
        exit($image_content); // reads and outputs the file onto the output buffer $image_content = read_file($file_path);

        
            
        }
        
        
        public function overlay($sp_id="0", $zindex="0")
        {
            $logEnable = true;
            $super_pixel_prefix = $this->config->item('super_pixel_prefix');
            $logFile = $super_pixel_prefix."/".$sp_id.".log";
            //echo "<br/>".$logFile;
            if($logEnable)
                error_log (date("Y-m-d h:i:sa")."Entering overlay-------------\n",3,$logFile);
            
            $zindex = intval($zindex);
            $data['title'] = "Super pixel marker";
            $data['base_url'] = $this->config->item('base_url');
            
            $is_prod = $this->config->item('is_prod');
            
            $data['image_id'] = $sp_id;
            $data['zindex'] = intval($zindex); 
            $data['serverName'] = $this->config->item('base_url');
            
            $jsonUrl = "http://cildata.crbs.ucsd.edu/super_pixel/".$sp_id."/mapping.json";
            //echo $jsonUrl;
            $json_str = file_get_contents($jsonUrl);
            if(is_null($json_str))
            {
                echo "Cannot locate the image mapping json file";
                return;
            }
            
            
            $data['is_done_prefix'] = $this->config->item('base_url');
            
            
            
            $data['run_mask'] = false;
            $run_mask = $this->input->get('run_mask', TRUE);
            if(!is_null($run_mask))
            {
                //echo "<br/>run_mask is not null";
                $data['run_mask'] = true;
            }
            else  
            {
                //echo "<br/>run_mask is null";
            }    
            
            $imageUrl = null;
            $width = 0;
            $height = 0;
            $items = json_decode($json_str);
            
            $data['z_max'] = count($items) -1;
            $imageName = "";
            foreach ($items as $item)
            {
                if($item->index == $zindex)
                {
                    $imageName = $item->image_name;
                    if(!$data['run_mask'])
                        $imageUrl = "http://cildata.crbs.ucsd.edu/super_pixel/".$sp_id."/overlays/".$item->image_name;
                    else
                        $imageUrl = "http://cildata.crbs.ucsd.edu/super_pixel/".$sp_id."/original/".$item->image_name;
                    $width = $item->width;
                    $height = $item->height;
                    break;
                }
            }
            
            
            
            $data['imageUrl'] = $imageUrl;
            $data['width'] = $width;
            $data['height'] = $height;
            //echo $imageUrl;
            
            
            /***********Recalculate parameters******************/
            //$this->session->set_userdata('num_sp', $num_sp);
            //$this->session->set_userdata('sigma', $sigma);
            //$this->session->set_userdata('compactness', $compactness);
            //$this->session->set_userdata('enf_conn', $enf_conn);
            $num_sp = $this->session->userdata('num_sp');
            if(!is_null($num_sp))
                $data['num_sp'] = $this->session->userdata('num_sp');
            else
                $data['num_sp'] = "500";
            $data['sigma'] = $this->session->userdata('sigma');
            $data['compactness'] = $this->session->userdata('compactness');
            $data['enf_conn'] = $this->session->userdata('enf_conn');
            /***********End Recalculate parameters****************/
            
            
            $this->load->view('super_pixel/super_pixel_display3', $data);
        }
        
        
        public function spdemo()
        {
            $data['title'] = "Super pixel marker";
            $data['base_url'] = $this->config->item('base_url');
            $data['image_id'] = "spdemo";
            
             $data['zindex'] = 0;       
                    
            $data['serverName'] = $this->config->item('base_url');
            
            $this->load->view('super_pixel/sp_demo_display', $data);
        }
    }


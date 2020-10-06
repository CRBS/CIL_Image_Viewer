<?php
    include_once 'PasswordHash.php';
    require_once 'GeneralUtil.php';
    require_once 'DBUtil.php';
    require_once 'DataLocalUtil.php';
    require_once 'Constants.php';
    
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
        
        public function overlay($sp_id="0", $zindex="0")
        {
            $zindex = intval($zindex);
            $data['title'] = "Super pixel marker";
            $data['base_url'] = $this->config->item('base_url');
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
            
            $imageUrl = null;
            $items = json_decode($json_str);
            foreach ($items as $item)
            {
                if($item->index == $zindex)
                {
                    $imageUrl = "http://cildata.crbs.ucsd.edu/super_pixel/".$sp_id."/original/".$item->image_name;
                    break;
                }
            }
            
            echo $imageUrl;
            
        }
        
        
        public function spdemo()
        {
            $data['title'] = "Super pixel marker";
            $data['base_url'] = $this->config->item('base_url');
            $data['image_id'] = "spdemo";
            
             $data['zindex'] = 0;       
                    
            $data['serverName'] = $this->config->item('base_url');
            
            $this->load->view('super_pixel/super_pixel_display', $data);
        }
    }


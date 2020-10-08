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
                    $imageUrl = "http://cildata.crbs.ucsd.edu/super_pixel/".$sp_id."/overlay/".$item->image_name;
                    break;
                }
            }
            
        $image_content = file_get_contents($imageUrl);

        // Image was not found
        if($image_content === FALSE)
        {
            show_error('Image "'.$imageUrl.'" could not be found.');
            return FALSE;
        }



        header('Content-Length: '.strlen($image_content)); // sends filesize header
        header('Content-Type: image/png'); // send mime-type header
        header('Content-Disposition: inline; filename="'.basename($imageUrl).'";'); // sends filename header
        exit($image_content); // reads and outputs the file onto the output buffer $image_content = read_file($file_path);

        
            
        }
        
        
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
            $width = 0;
            $height = 0;
            $items = json_decode($json_str);
            
            $data['z_max'] = count($items) -1;
            foreach ($items as $item)
            {
                if($item->index == $zindex)
                {
                    $imageUrl = "http://cildata.crbs.ucsd.edu/super_pixel/".$sp_id."/overlay/".$item->image_name;
                    $width = $item->width;
                    $height = $item->height;
                    break;
                }
            }
            
            $data['imageUrl'] = $imageUrl;
            $data['width'] = $width;
            $data['height'] = $height;
            //echo $imageUrl;
            
            
            $this->load->view('super_pixel/super_pixel_display', $data);
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


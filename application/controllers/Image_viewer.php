<?php
    require_once 'GeneralUtil.php';
    require_once 'DBUtil.php';
    
    class Image_viewer extends CI_Controller
    {
        public function view($image_id="0")
        {
            
            $db_params = $this->config->item('db_params');
            $dbutil = new DBUtil();
            $array = $dbutil->getImageInfo($db_params,$image_id);
            if(is_null($array))
            {
                $data['test'] = "test";
                $this->load->view('errors/not_exist', $data);
            }
            else 
            {
                $json_str = json_encode($array);
                $json = json_decode($json_str);
                if(!$json->success)
                {
                    $data['test'] = "test";
                    $this->load->view('errors/not_exist', $data);
                    return;
                }
                $data['title'] = "CIL Image Viewer | 2D | ".$image_id;
                $data['serverName'] = "localhost";
                $data['folder_postfix'] = $image_id;
                if($json->is_rgb)
                    $data['rgb'] = "true";
                else
                    $data['rgb'] = "false";
                
                $data['image_id'] = $image_id;
                $data['max_zoom'] = $json->max_zoom;
                $data['max_z'] = $json->max_z;
                $data['init_lat'] =  $json->init_lat;
                $data['init_lng'] =  $json->init_lng;
                $data['init_zoom'] = $json->init_zoom;
                
                $this->load->view('image/image_viewer', $data);
            }
        }
        
    }

    

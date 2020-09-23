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


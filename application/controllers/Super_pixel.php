<?php
    include_once 'PasswordHash.php';
    require_once 'GeneralUtil.php';
    require_once 'DBUtil.php';
    require_once 'DataLocalUtil.php';
    require_once 'Constants.php';
    
    class Super_pixel extends CI_Controller
    {
        public function view()
        {
            $data['title'] = "Super pixel marker";
            $data['base_url'] = $this->config->item('base_url');
            
             $this->load->view('super_pixel/super_pixel_display', $data);
        }
    }


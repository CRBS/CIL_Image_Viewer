<?php
    require_once 'GeneralUtil.php';
    require_once 'DBUtil.php';
    require_once 'DataLocalUtil.php';
    
    class Admin extends CI_Controller
    {
        private $success = "success";
        public function image($image_id="0")
        {
            error_reporting(0);
            if(strcmp($image_id, "0") == 0)
            {
                $data['test'] = "test";
                $this->load->view('errors/not_exist', $data);
                return;
            }
            
            $dbutil = new DBUtil();
            $db_params = $this->config->item('db_params');
            $localutil = new DataLocalUtil();
            $array = $dbutil->getImageInfo($db_params,$image_id);
            if(is_null($array))
               $array =  $localutil->getLocalImageInfo ($image_id, $image_tar_dir);
                
            if(is_null($array))
            {
                $data['test'] = "test";
                $this->load->view('errors/not_exist', $data);
                return;
            }
            
            if(!$array[$this->success])
            {
                
                $data['test'] = "test";
                $this->load->view('errors/not_exist', $data);
                return;
            }
            else 
            {
                $data['image_id'] = $image_id;
                $json_str = json_encode($array);
                $json = json_decode($json_str);
                $data['ijson'] = $json;
                $this->load->view('admin/image_admin_display', $data);
                return;
            }
        }
    }
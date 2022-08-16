<?php
    require_once 'GeneralUtil.php';
    require_once 'DBUtil.php';
    require_once 'DataLocalUtil.php';
    
    class Admin extends CI_Controller
    {
        private $success = "success";
        
        public function urls($image_id="0")
        {
            $this->load->helper('url');
            /********Check session**************************/
            $login = $this->session->userdata('login');
            $base_url = $this->config->item('base_url');
            if(is_null($login))
            {
               redirect ($base_url."/login/auth/".$image_id);
               return;
            }
            /******End check session **********************/
            
            
        }
        
        public function image($image_id="0")
        {
            
            //$this->load->library('session');
            $this->load->helper('url');
            /********Check session**************************/
            $login = $this->session->userdata('login');
            $base_url = $this->config->item('base_url');
            if(is_null($login))
            {
               redirect ($base_url."/login/auth/".$image_id);
               return;
            }
            /******End check session **********************/
            
            error_reporting(0);
            if(strcmp($image_id, "0") == 0)
            {
                $data['test'] = "test";
                $this->load->view('errors/not_exist', $data);
                return;
            }
            
            $dbutil = new DBUtil();
            $db_params = $this->config->item('db_params');
            $image_tar_dir = $this->config->item('image_tar_dir');
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
        
        public function update()
        {
            $this->load->helper('url');
            $base_url = $this->config->item('base_url');
            $image_id = $this->input->post('image_id', TRUE);
            $max_z = $this->input->post('max_z', TRUE);
            $is_rgb = "true";
            $temp = $this->input->post('is_rgb', TRUE);
            if(is_null($temp))
                $is_rgb = "false";
            $max_zoom = $this->input->post('max_zoom', TRUE);
            $init_lat = $this->input->post('init_lat', TRUE);
            $init_lng = $this->input->post('init_lng', TRUE);
            $init_zoom = $this->input->post('init_zoom', TRUE);
            $is_public = "true";
            $temp = $this->input->post('is_public', TRUE);
            if(is_null($temp))
                $is_public = "false";
            
            $is_timeseries = "true";
            $temp = $this->input->post('is_timeseries', TRUE);
            if(is_null($temp))
                $is_timeseries = "false";
            
            $max_t = $this->input->post('max_t', TRUE);
            
            $array = array();
            $array['max_z'] = $max_z;
            $array['is_rgb'] = $is_rgb;
            $array['max_zoom'] = $max_zoom;
            $array['init_lat'] = $init_lat;
            $array['init_lng'] = $init_lng;
            $array['init_zoom'] = $init_zoom;
            $array['is_public'] = $is_public;
            $array['is_timeseries'] = $is_timeseries;
            $array['max_t'] = $max_t;
            
            $dbutil = new DBUtil();
            $db_params = $this->config->item('db_params');
            $dbutil->handleImageUpdate($db_params, $image_id, $array);
            /*
            echo "<br/>max_z:".$max_z;
            echo "<br/>is_rgb:".$is_rgb;
            echo "<br/>max_zoom:".$max_zoom;
            echo "<br/>init_lat:".$init_lat;
            echo "<br/>init_lng:".$init_lng;
            echo "<br/>init_zoom:".$init_zoom;
            echo "<br/>is_public:".$is_public;
            echo "<br/>is_timeseries:".$is_timeseries;
            echo "<br/>max_t:".$max_t;
            */
            redirect ($base_url."/image_viewer/".$image_id);
        }
    }
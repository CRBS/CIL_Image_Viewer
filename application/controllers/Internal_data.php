<?php
    include_once 'PasswordHash.php';
    require_once 'GeneralUtil.php';
    require_once 'DBUtil.php';
    require_once 'DataLocalUtil.php';
    require_once 'Constants.php';
    class Internal_data extends CI_Controller
    {
        private function authenticateByToken($username, $token,$ip_address)
        {
            $cil_pgsql_db = $this->config->item('cil_pgsql_db');
            $db_params = $this->config->item('db_params');
            $dbutil = new DBUtil();
            $isTokenCorrect = $dbutil->isTokenCorrect($cil_pgsql_db, $username, $token);
            if(!$isTokenCorrect)
            {
                return false;
            }
            
            $user_json = $dbutil->getPortalUserInfo($cil_pgsql_db, $username);
            if(is_null($user_json))
            {
                return false;
            }
            $this->session->set_userdata('data_login', "true");
            $this->session->set_userdata('user_json', $user_json);
            if(isset($user_json->username))
                 $dbutil->insertUserAction($db_params, $user_json->username, $ip_address, "login");
            return true;
        }
        
        public function view($image_id="0")
        {
            $this->load->helper('url');
            $dbutil = new DBUtil();
            
            $base_url = $this->config->item('base_url');
            $data['enable_augmentation'] = $this->config->item('enable_augmentation');
            $data['cdeep3m_website_url'] = $this->config->item('cdeep3m_website_url');
            $cil_pgsql_db = $this->config->item('cil_pgsql_db');
            
            $data['base_url'] = $base_url;
            
            $token = $this->input->get('token', TRUE);
            $username = $this->input->get('username', TRUE);
            $data['username'] = $username;
            if(!is_null($username) && !is_null($token))
            {
                $ip_address = $this->input->ip_address();
                $this->authenticateByToken($username, $token,$ip_address);
            }
            /********Session check**************************/
            $data_login = $this->session->userdata('data_login');
            if(is_null($data_login))
            {
                //show_404();
                echo "404 1";
                
                return;
            }
            /********End session check **********************/
            
            
            /***********Getting user_json*******************/
            $user_json  = $this->session->userdata('user_json');
            //echo $user_json->full_name;
            
            if(!is_null($user_json))
                $data['user_json'] = $user_json;
            else
            { 
                //show_404();
                echo "404 2";
                return;
            }
            /***********End user_json*******************/
            

            
            
            $image_tar_dir = $this->config->item('image_tar_dir');
            $lag = $this->input->get('lat', TRUE);
            $lng = $this->input->get('lng', TRUE);
            $zoom = $this->input->get('zoom', TRUE);
            $zindex = $this->input->get('zindex', TRUE);
            $tindex = $this->input->get('tindex', TRUE);
            
            $token = $this->input->get('token', TRUE);
            if(!is_null($token))
            {
                $data['token'] = $token;
            }
            
            /***********Control contrast and brightness ***************/
            $contrast = $this->input->get('contrast', TRUE);
            $brightness = $this->input->get('brightness', TRUE);
            if(!is_null($contrast))
                $data['contrast'] = $contrast;
            
            if(!is_null($brightness))
                $data['brightness'] = $brightness;
            
            /***********End Control contrast and brightness ***************/
            
            
            if(is_null($zindex) || !is_numeric($zindex))
              $zindex = 0;
            if(is_null($tindex) || !is_numeric($tindex))
              $tindex = 0;
            
            $data['zindex'] = intval($zindex);
            $data['tindex'] = intval($tindex);

            $db_params = $this->config->item('db_params');
            $cil_pgsql_db = $this->config->item('cil_pgsql_db');
            $data['base_url'] = $this->config->item('base_url');
            
                       
            
            $localutil = new DataLocalUtil();
            $array = $dbutil->getImageInfo($db_params,$image_id);
            if(is_null($array))
               $array =  $localutil->getLocalImageInfo ($image_id, $image_tar_dir);
                
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
                
                if(isset($json->is_public) && !$json->is_public)
                {
                    $base_url = $this->config->item('base_url');
                    //redirect ($base_url."/user/login/".$image_id);
                    //return;
                    /********Check session**************************/
                    $data_login = $this->session->userdata('data_login');

                    if(is_null($data_login))
                    {
                       show_404();
                       return;
                    }
                    /******End check session **********************/
                }
                
                
                if($json->is_timeseries)
                    $data['title'] = "CIL Image Viewer | Time series | ".$image_id;
                else if($json->max_z == 0)
                    $data['title'] = "CIL Image Viewer | 2D | ".$image_id;
                else
                    $data['title'] = "CIL Image Viewer | Z stack | ".$image_id;
                
                $data['serverName'] = $this->config->item('base_url');
                $data['folder_postfix'] = $image_id;
                if($json->is_rgb)
                    $data['rgb'] = "true";
                else
                    $data['rgb'] = "false";
                
                $data['image_id'] = $image_id;
                $data['max_zoom'] = $json->max_zoom;
                $data['max_z'] = $json->max_z;
                if(is_null($lag))
                    $data['init_lat'] =  $json->init_lat;
                else
                    $data['init_lat'] =  $lag;
                
                if(is_null($lng))
                    $data['init_lng'] =  $json->init_lng;
                else
                    $data['init_lng'] = $lng;
                
                if(is_null($zoom))
                    $data['init_zoom'] = $json->init_zoom;
                else
                    $data['init_zoom'] = $zoom;
                
                
                $data['max_t'] = $json->max_t;
                
                if(isset($json->x_pixel_offset))
                    $data['x_pixel_offset'] = $json->x_pixel_offset;
                
                if(isset($json->y_pixel_offset))
                    $data['y_pixel_offset'] = $json->y_pixel_offset;
                
                if(isset($json->max_x))
                    $data['max_x'] = $json->max_x;
                
                if(isset($json->max_y))
                    $data['max_y'] = $json->max_y;
                
                
                if(!$json->is_timeseries)
                    $this->load->view('internal_data/internal_data_display', $data);
                
            }
        }
        

    }


<?php

    require_once 'GeneralUtil.php';
    require_once 'DBUtil.php';
    require_once 'DataLocalUtil.php';
    require_once 'Constants.php';
    class Cdeep3m extends CI_Controller
    {
        
        public function login($image_id="0")
        {
            $this->load->helper('url');
            $base_url = $this->config->item('base_url');
            $db_params = $this->config->item('db_params');
            $salt = $this->config->item('salt');
            $dbutil = new DBUtil();
            $username = $this->input->post('username', TRUE);
            $password = $this->input->post('password', TRUE);
            $data['image_id'] = $image_id;
            $data['wrong_password'] = false;
            if(!is_null($username) && !is_null($password))
            {
                $passkey = crypt($password,$salt);
                $auth = $dbutil->authenticateWebUser($db_params, $username, $passkey);
                if($auth)
                {
                    $this->session->set_userdata('data_login', "true");
                    redirect ($base_url."/cdeep3m/".$image_id);
                    return;
                }
                else
                {
                    $data['title'] = "CIL login";
                    $data['wrong_password'] = true;
                    $this->load->view('cdeep3m/image_login_display', $data);
                    return;
                }
            }
            
            $data['title'] = "CIL login";
            $this->load->view('cdeep3m/image_login_display', $data);
        }
        
        public function view($image_id="0")
        {
            $this->load->helper('url');
            
            $base_url = $this->config->item('base_url');
            $data['enable_augmentation'] = $this->config->item('enable_augmentation');
            
            $data['base_url'] = $base_url;
            /********Session check**************************/
            $data_login = $this->session->userdata('data_login');
            if(is_null($data_login))
            {
                redirect ($base_url."/cdeep3m/login/".$image_id);
                return;
            }
            /********End session check **********************/
            
            /************Checking the sessions***************/
            $waiting_for_result = $this->session->userdata(Constants::$waiting_for_result_key);
            if(!is_null($waiting_for_result))
            {
                $data['waiting_for_result'] = true;
            }
            $this->session->set_userdata(Constants::$waiting_for_result_key, NULL);
            
            $crop_id = $this->session->userdata(Constants::$crop_id_key);
            if(!is_null($crop_id))
            {
                $data['crop_id'] = intval($crop_id);
            }
            $this->session->set_userdata(Constants::$crop_id_key, NULL);
            
            
            $success_email = $this->session->userdata(Constants::$success_email_key);
            if(!is_null($success_email))
            {
                $data['success_email'] = $success_email;
            }
            $this->session->set_userdata(Constants::$success_email_key, NULL);
            /************End checking the sessions***********/
            
            
            $image_tar_dir = $this->config->item('image_tar_dir');
            $lag = $this->input->get('lat', TRUE);
            $lng = $this->input->get('lng', TRUE);
            $zoom = $this->input->get('zoom', TRUE);
            $zindex = $this->input->get('zindex', TRUE);
            $tindex = $this->input->get('tindex', TRUE);
            
            if(is_null($zindex) || !is_numeric($zindex))
              $zindex = 0;
            if(is_null($tindex) || !is_numeric($tindex))
              $tindex = 0;
            
            $data['zindex'] = intval($zindex);
            $data['tindex'] = intval($tindex);

            $db_params = $this->config->item('db_params');
            $data['base_url'] = $this->config->item('base_url');
            
            
            $dbutil = new DBUtil();
            $data['training_models'] = $dbutil->getTrainingModels($db_params);
            
            
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
                       redirect ($base_url."/cdeep3m/login/".$image_id);
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
                    $this->load->view('cdeep3m/cdeep3m_display', $data);
                else
                {
                    if($data['zindex'] == 0)
                        $data['zindex'] = 1;
                    
                    if($data['tindex'] ==0)
                        $data['tindex'] = 1;
                    $this->load->view('cdeep3m/image_viewer_display_ts2', $data);
            
                }
                
                
            }
        }
        
        
        
        
    }


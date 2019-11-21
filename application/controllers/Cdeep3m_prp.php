<?php
    include_once 'PasswordHash.php';
    require_once 'GeneralUtil.php';
    require_once 'DBUtil.php';
    require_once 'DataLocalUtil.php';
    require_once 'Constants.php';
    class Cdeep3m_prp extends CI_Controller
    {
        /*
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
                    $user_info = $dbutil->getUserInfo($db_params, $username);
                    if(!is_null($user_info))
                    {
                        $user_json_str = json_encode($user_info);
                        $user_json = json_decode($user_json_str);
                        $this->session->set_userdata('user_json', $user_json);
                    }
                    $this->session->set_userdata('data_login', "true");
                   
                    //echo "Testing...";
                     redirect ($base_url."/cdeep3m_prp/".$image_id);
                    return;
                }
                else
                {
                    $data['title'] = "CIL login";
                    $data['wrong_password'] = true;
                    $this->load->view('cdeep3m_prp/image_login_display', $data);
                    return;
                }
            }
            
            $data['title'] = "CIL login";
            $this->load->view('cdeep3m_prp/image_login_display', $data);
        }

       */
        public function logout($image_id="0")
        {
            $this->load->helper('url');
            $this->session->set_userdata('user_json', NULL);
            $this->session->set_userdata('data_login', NULL);
            redirect ($base_url."/cdeep3m_prp/login/".$image_id);
            return;
        }

        public function login($image_id="0")
        {
            $this->load->helper('url');
            $base_url = $this->config->item('base_url');
            $db_params = $this->config->item('db_params');
            $salt = $this->config->item('salt');
            $dbutil = new DBUtil();
            $username = $this->input->post('username', TRUE);
            $password = $this->input->post('password', TRUE);
            
            $ip_address = $this->input->ip_address();
            
            $data['image_id'] = $image_id;
            $data['wrong_password'] = false;
            if(!is_null($username) && !is_null($password))
            {
                //$passkey = crypt($password,$salt);
                //$auth = $dbutil->authenticateWebUser($db_params, $username, $passkey);
                $user_json = $this->authenticate($username, $password);
                if(!is_null($user_json))
                {
                    //$user_info = $dbutil->getUserInfo($db_params, $username);
                    if(!is_null($user_json))
                    {
                        //$user_json_str = json_encode($user_info);
                        //$user_json = json_decode($user_json_str);
                        if(isset($user_json->username))
                            $dbutil->insertUserAction($db_params, $user_json->username, $ip_address, "login");
                        $this->session->set_userdata('user_json', $user_json);
                    }
                    $this->session->set_userdata('data_login', "true");
                   
                   
                    redirect ($base_url."/cdeep3m_prp/".$image_id);
                    return;
                }
                else
                {
                    $data['title'] = "CIL login";
                    $data['wrong_password'] = true;
                    $this->load->view('cdeep3m_prp/image_login_display', $data);
                    return;
                }
            }
            
            $data['title'] = "CIL login";
            $this->load->view('cdeep3m_prp/image_login_display', $data);
        }

        
        
        private function authenticate($username,$password)
        {
            $db_params = $this->config->item('db_params');
            $dbutil= new DBUtil();
            $userJson = $dbutil->getCdeep3mUserInfo($db_params, $username);
            if(is_null($userJson))
                return NULL;
            
            $hasher = new PasswordHash(8, TRUE);
            if(!isset($userJson->pass_hash))
                return NULL;
            
            echo $userJson->pass_hash;
            
            if(!$hasher->CheckPassword($password, $userJson->pass_hash))
                return NULL;
            else 
                return $userJson;

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
                redirect ($base_url."/cdeep3m_prp/login/".$image_id);
                return;
            }
            /********End session check **********************/
            
            
            /***********Getting user_json*******************/
            $user_json  = $this->session->userdata('user_json');
            if(!is_null($user_json))
                $data['user_json'] = $user_json;
            else
            { 
                redirect ($base_url."/cdeep3m_prp/login/".$image_id);
                return;
            }
            /***********End user_json*******************/
            
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
            
            
            $expected_runtime = $this->session->userdata(Constants::$expected_runtime);
            if(!is_null($expected_runtime))
            {
                $data['expected_runtime'] = $expected_runtime;
            }
            $this->session->set_userdata(Constants::$expected_runtime, NULL);
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
            
            $tempJson = $dbutil->getPreferredCdeep3mSettings($db_params, $image_id);
            if(!is_null($tempJson) && isset($tempJson->preferred_model))
            {
                $data['cdeep3m_settings'] = $tempJson;
               
            }
            
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
                    $this->load->view('cdeep3m_prp/cdeep3m_prp_display', $data);
                else
                {
                    if($data['zindex'] == 0)
                        $data['zindex'] = 1;
                    
                    if($data['tindex'] ==0)
                        $data['tindex'] = 1;
                    $this->load->view('cdeep3m_prp/image_viewer_display_ts2', $data);
            
                }
                
                
            }
        }
        
        
        
        
    }


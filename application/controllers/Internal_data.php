<?php
    include_once 'PasswordHash.php';
    require_once 'GeneralUtil.php';
    require_once 'DBUtil.php';
    require_once 'DataLocalUtil.php';
    require_once 'Constants.php';
    class Internal_data extends CI_Controller
    {
        
        
        public function submit_priority($image_id)
        {
            $this->load->helper('url');
            $base_url = $this->config->item('base_url');
            $dbutil = new DBUtil();
            echo "<br/>submit_priority:".$image_id;
            $annotation_object_id = $this->input->post('annotation_object_id', TRUE);
            echo "<br/>annotation ID:".$annotation_object_id;
            $zindex = $this->input->post('annotation_zindex_id', TRUE);
            echo "<br/>Zindex:".$zindex;
            $lat = $this->input->post('annotation_lat_id', TRUE);
            echo "<br/>Lat:".$lat;
            $lng = $this->input->post('annotation_lng_id', TRUE);
            echo "<br/>Lng:".$lng;
            $zoom = $this->input->post('annotation_zoom_id', TRUE);
            echo "<br/>Zoom:".$zoom;
            $desc = $this->input->post('priority_desc_id', TRUE);
            echo "<br/>Desc:".$desc;
            $priority = $this->input->post('priority_id', TRUE);
            echo "<br/>Priority:".$priority;
            
            $token = $this->input->post('annotation_token_id', TRUE);
            echo "<br/>Token:".$token;
            
            $reporter_username = $this->input->post('annotation_reporter_username_id', TRUE);
            echo "<br/>Reporter username:".$reporter_username;
            
            
            $reporter_fullname = $this->input->post('annotation_reporter_id', TRUE);
            echo "<br/>Reporter_fullname:".$reporter_fullname;
            
            $assignee_json_str = $this->input->post('assignee_json_str_id', TRUE);
            echo "<br/>".$assignee_json_str;
            $assignees = json_decode($assignee_json_str);
            
            $priority_index = 0;
            if(strcmp($priority, "high") == 0)
               $priority_index = 2;
            else if(strcmp($priority, "medium") == 0)
               $priority_index = 1;
            
                
            
            $inputArray = array();
            $inputArray['annotation_id'] = $annotation_object_id;
            $inputArray['image_id'] = $image_id;
            $inputArray['zindex'] = intval($zindex);
            $inputArray['reporter'] = $reporter_username;
            $inputArray['reporter_fullname'] = $reporter_fullname;
            $inputArray['priority_name'] = $priority;
            $inputArray['priority_index'] = $priority_index;
            $inputArray['description'] = $desc;
            $inputArray['lat'] = $lat;
            $inputArray['lng'] = $lng;
            $inputArray['zoom'] = intval($zoom);
            
            $cil_pgsql_db = $this->config->item('cil_pgsql_db');
            $inputJsonStr = json_encode($inputArray);
            $inputJson = json_decode($inputJsonStr);
            if(!$dbutil->priorityExists($cil_pgsql_db, $annotation_object_id))
                $dbutil->insertAnnotationPriority($cil_pgsql_db, $inputJson);
            else 
            {
                $dbutil->updatePriorityDesc($cil_pgsql_db, $annotation_object_id);
                $dbutil->updatePriorityLevel($cil_pgsql_db, $priority, $priority_index, $annotation_object_id);
            }
            
            
            $dbutil->deletePriorityAssigneeByAnnotID($cil_pgsql_db, $annotation_object_id);
            
            if(count($assignees) > 0)
            {
                $userInfoArray = $dbutil->getUserInfoByUsernames($cil_pgsql_db, $assignees);

                foreach($userInfoArray as $userInfo)
                {
                    $dbutil->insertPriorityAssignee($cil_pgsql_db, $userInfo['username'], $userInfo['full_name'], $userInfo['email'], $annotation_object_id);
                }
            }
            
            
            
            redirect ($base_url."/internal_data/".$image_id."?username=".$reporter_username."&token=".$token);
        }
        
        
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
        
        
        public function get_histogram_image($username, $image_id)
        {
            $histogram_folder = $this->config->item('histogram_folder');
            $filename = $histogram_folder."/".$username."/".$image_id."/stitched/histogram.png";

            if(file_exists($filename))
            { 
                $mime = "image/png"; //<-- detect file type
                header('Content-Length: '.filesize($filename)); //<-- sends filesize header
                header("Content-Type: $mime"); //<-- send mime-type header
                header('Content-Disposition: inline; filename="'.$filename.'";'); //<-- sends filename header
                readfile($filename); //<--reads and outputs the file onto the output buffer
                die(); //<--cleanup
                exit; //and exit
            }
            else 
            {
                $filename = $histogram_folder."/default.png";
                $mime = "image/png"; //<-- detect file type
                header('Content-Length: '.filesize($filename)); //<-- sends filesize header
                header("Content-Type: $mime"); //<-- send mime-type header
                header('Content-Disposition: inline; filename="'.$filename.'";'); //<-- sends filename header
                readfile($filename); //<--reads and outputs the file onto the output buffer
                die(); //<--cleanup
                exit; //and exit
            }
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
            

            $priorityInfo = $dbutil->getPriorityAssigneeInfo($cil_pgsql_db, $image_id);
            $priority_json_str = json_encode($priorityInfo, JSON_UNESCAPED_SLASHES);
            $data['priority_json_str'] = $priority_json_str;
            
            
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
                
                
                $data['annotators'] = $dbutil->getAllAnnotators($cil_pgsql_db);
                
                if(!$json->is_timeseries)
                {
                    $this->load->view('internal_data/internal_data_display', $data);
                    //$this->load->view('internal_data/internal_data_h_display', $data);
                }
                
            }
        }
        

    }


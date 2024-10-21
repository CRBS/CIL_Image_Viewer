<?php
    include_once 'PasswordHash.php';
    require_once 'GeneralUtil.php';
    require_once 'DBUtil.php';
    require_once 'DataLocalUtil.php';
    require_once 'Constants.php';
    require_once 'MailUtil.php';
    
    class Ncmir_metadata extends CI_Controller
    {
        public function submit()
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
            $data['token'] = $token;
            if(!is_null($username) && !is_null($token))
            {
                $ip_address = $this->input->ip_address();
                $this->authenticateByToken($username, $token,$ip_address);
            }
            /********Session check**************************/
            $data_login = $this->session->userdata('data_login');
            if(is_null($data_login))
            {
                show_404();
                //echo "404 1";
                
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
                show_404();
                //echo "404 2";
                return;
            }
            /***********End user_json*******************/
            $token = $this->input->post('token', TRUE);
            //echo $token;
            $project_id = $this->input->post('project_id', TRUE);
            //echo "\n<br/>".$project_id;
            $project_name = $this->input->post('project_name', TRUE);
            //echo "\n<br/>".$project_name;
            $project_desc = $this->input->post('project_desc', TRUE);
            //echo "\n<br/>".$project_desc;
            $experiment_id = $this->input->post('experiment_id', TRUE);
            //echo "\n<br/>".$experiment_id;
            $experiment_title = $this->input->post('experiment_title', TRUE);
            //echo "\n<br/>".$experiment_title;
            $experiment_purpose = $this->input->post('experiment_purpose', TRUE);
            //echo "\n<br/>".$experiment_purpose;
            $mpid = $this->input->post('mpid', TRUE);
            //echo "\n<br/>".$mpid;
            $image_basename = $this->input->post('image_basename', TRUE);
            //echo "\n<br/>".$image_basename;
            $notes = $this->input->post('notes', TRUE);
            //echo "\n<br/>".$notes;
            $dbutil = new DBUtil();
            $ncmir_pgsql_db = $this->config->item('ncmir_pgsql_db');
            $dbutil->updateProject($ncmir_pgsql_db, $project_id, $project_name, $project_desc);
            $dbutil->updateExperment($ncmir_pgsql_db, $experiment_id, $experiment_title, $experiment_purpose);
            $dbutil->updateMicroscopy($ncmir_pgsql_db, $mpid, $image_basename, $notes);
            //Return back to the Edit page
            $data['submitted_data'] = true;
            $dataArray = $dbutil->getMpidInfo($ncmir_pgsql_db, $mpid);
            $ncmir_json_str = json_encode($dataArray);
            $ncmir_json = json_decode($ncmir_json_str);
            $data['ncmir_json'] = $ncmir_json;
            $this->load->view('ncmir_metadata/edit_metadata_display', $data);
        }
        
        
        public function edit($mpid="0")
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
            $data['token'] = $token;
            if(!is_null($username) && !is_null($token))
            {
                $ip_address = $this->input->ip_address();
                $this->authenticateByToken($username, $token,$ip_address);
            }
            /********Session check**************************/
            $data_login = $this->session->userdata('data_login');
            if(is_null($data_login))
            {
                show_404();
                //echo "404 1";
                
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
                show_404();
                //echo "404 2";
                return;
            }
            /***********End user_json*******************/
            $data['submitted_data'] = false;
            $ncmir_pgsql_db = $this->config->item('ncmir_pgsql_db');
            $dataArray = $dbutil->getMpidInfo($ncmir_pgsql_db, $mpid);
            $ncmir_json_str = json_encode($dataArray);
            $ncmir_json = json_decode($ncmir_json_str);
            $data['ncmir_json'] = $ncmir_json;
            $this->load->view('ncmir_metadata/edit_metadata_display', $data);
        }
    }


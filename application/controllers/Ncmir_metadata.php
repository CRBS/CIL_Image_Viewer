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
            $ncmir_pgsql_db = $this->config->item('ncmir_pgsql_db');
            $dataArray = $dbutil->getMpidInfo($ncmir_pgsql_db, $mpid);
            $ncmir_json_str = json_encode($dataArray);
            $ncmir_json = json_decode($ncmir_json_str);
            $data['ncmir_json'] = $ncmir_json;
            $this->load->view('ncmir_metadata/edit_metadata_display', $data);
        }
    }


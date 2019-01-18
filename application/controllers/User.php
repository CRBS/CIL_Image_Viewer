<?php
    require_once 'GeneralUtil.php';
    require_once 'DBUtil.php';
    require_once 'DataLocalUtil.php';
    
    class User extends CI_Controller
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
                    redirect ($base_url."/image_viewer/".$image_id);
                    return;
                }
                else
                {
                    $data['title'] = "CIL login";
                    $data['wrong_password'] = true;
                    $this->load->view('login/image_login_display', $data);
                    return;
                }
            }
            
            $data['title'] = "CIL login";
            $this->load->view('login/image_login_display', $data);
        }

        
    }
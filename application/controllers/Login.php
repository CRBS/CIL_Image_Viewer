<?php

class Login extends CI_Controller
{
    public function auth($image_id="0")
    {
        
       
        $this->load->library('session');
        $this->load->helper('url');
        
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);
        $base_url = $this->config->item('base_url');
        $data['base_url'] = $base_url;
        $data['image_id']=$image_id;
        if(is_null($username) && is_null($password))
        {
            $data['title'] = "CIL login";
            $this->load->view('login/login_display', $data);
            return;
        }
        
        
        $users = $this->config->item('users');
        $success = false;
        
        if(!is_null($users))
        {
            foreach($users as $user)
            {
                if(strcmp($username,$user->username) == 0 
                        && strcmp($password,$user->password)==0)
                {
                    $array = array();
                    $array["login"] = true;
                    $this->session->set_userdata($array);
                    $success = true;
                    break;
                }
            }
        }
        if($success)
        {
            redirect ($base_url."/admin/image/".$image_id);
            return;
        }
        else 
        {
            redirect ($base_url."/login/auth/".$image_id);
            return;
        }
            
        
         
    }
}


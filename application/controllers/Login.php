<?php

class Login extends CI_Controller
{
    public function index()
    {
        
        $this->load->library('session');
        
        
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);
        
        if(is_null($username) && is_null($password))
        {
            $data['title'] = "CIL login";
            $this->load->view('login/login_display', $data);
            return;
        }
        
        
        $users = $this->config->item('users');
        $success = false;
        foreach($users as $user)
        {
            if(strcmp($username,$user->username) == 0 
                    && strcmp($password,$user->password)==0)
            {
                $array = array();
                $array["login"] = true;
                $this->session->set_userdata($array);
                $success = true;
                    
            }
        }
        if($success)
            echo "YES";
        else 
            echo "NO";
            
        
         
    }
}


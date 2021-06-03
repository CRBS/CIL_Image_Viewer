<?php

require_once 'GeneralUtil.php';
require_once 'DBUtil.php';

class Annotation_priority extends CI_Controller
{
    
    public function created_by()
    {
        $this->load->helper('url');
        $dbutil = new DBUtil();
            
        $base_url = $this->config->item('base_url');
        $cil_pgsql_db = $this->config->item('cil_pgsql_db');
        
        $username = $this->input->get('username', TRUE);
        $token = $this->input->get('token', TRUE);
        
        
        $data['base_url'] = $base_url;
        $data['username'] = $username;
        $data['token'] = $token;
        
        if(is_null($username) || is_null($token))
        {
            show_404();
            //echo "<br/>username is null";
            return;
        }
        
        $correctToken = $dbutil->isTokenCorrect($cil_pgsql_db, $username, $token);
        if(!$correctToken)
        {
            show_404();
            //echo "<br/>Incorrect token";;
            return;
        }
        
        $data['title'] = "Priority list - created by me";
        $pArray = $dbutil->getAllPriorityCreatedBy($cil_pgsql_db, $username);
        
        $pjson_str = json_encode($pArray);
        $pJson = json_decode($pjson_str);
        $data['pJson'] = $pJson;
        
        $this->load->view('priority/header', $data);
        $this->load->view('priority/priority_list_creator', $data);
    }
    
    
    
    public function assigned_by()
    {
        $this->load->helper('url');
        $dbutil = new DBUtil();
            
        $base_url = $this->config->item('base_url');
        $cil_pgsql_db = $this->config->item('cil_pgsql_db');
        
        $username = $this->input->get('username', TRUE);
        $token = $this->input->get('token', TRUE);
        
        
        $data['base_url'] = $base_url;
        $data['username'] = $username;
        $data['token'] = $token;
        
        if(is_null($username) || is_null($token))
        {
            show_404();
            //echo "<br/>username is null";
            return;
        }
        
        $correctToken = $dbutil->isTokenCorrect($cil_pgsql_db, $username, $token);
        if(!$correctToken)
        {
            show_404();
            //echo "<br/>Incorrect token";;
            return;
        }
        
        $data['title'] = "Priority list - assigned to me";
        $pArray = $dbutil->getAllPriorityAssignedBy($cil_pgsql_db, $username);
        //var_dump($pArray);
        $pjson_str = json_encode($pArray);
        $pJson = json_decode($pjson_str);
        $data['pJson'] = $pJson;
        
        $this->load->view('priority/header', $data);
        $this->load->view('priority/priority_list_assignee', $data);
    }
    
}


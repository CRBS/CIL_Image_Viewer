<?php

require_once 'GeneralUtil.php';
require_once 'DBUtil.php';

class Annotation_priority extends CI_Controller
{
    
    
    private function getAssigneeMap($pArray)
    {
        $amap = array();
        
        foreach ($pArray as $item)
        {
            if(!array_key_exists($item->annotation_id, $amap))
            {
                if(!is_null($item->assignee_fullname))
                    $amap[$item->annotation_id] = $item->assignee_fullname;
                else
                    $amap[$item->annotation_id] = "";
            }
            else
            {
                if(!is_null($item->assignee_fullname))
                    $amap[$item->annotation_id] = $amap[$item->annotation_id].", ".$item->assignee_fullname;
                else
                    $amap[$item->annotation_id] = "";
            }
        }
        
        return $amap;
        
    }
    
    
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
        
        $data['assignee_map'] = $this->getAssigneeMap($pJson);
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


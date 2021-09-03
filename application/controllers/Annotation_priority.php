<?php

require_once 'GeneralUtil.php';
require_once 'DBUtil.php';
require_once 'MailUtil.php';

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
    
    
    public function delete_annotation($image_id,$annotation_id)
    {
        $this->load->helper('url');
        $dbutil = new DBUtil();
        $mutil = new MailUtil();    
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
        
        
        //Sending emails
        $gmail_sender = $this->config->item('gmail_sender');
        $gmail_sender_name = $this->config->item('gmail_sender_name');
        $gmail_sender_pwd = $this->config->item('gmail_sender_pwd');
        date_default_timezone_set('America/Los_Angeles');
        $subject = "Image annotation removed:".$image_id."-".$annotation_id." : ".date("Y-m-d h:i:sa");
        
        $creatorInfo = $dbutil->getUserInfoByUsername($cil_pgsql_db, $username);
        $message = "Image annotation removed:".$image_id." - ".$annotation_id;
        $mutil->sendMail($gmail_sender, $gmail_sender_name, $gmail_sender_pwd, $creatorInfo['email'], $subject, $message);
                    
        $assigneeArray = $dbutil->getAssigneeInfoByAnnotId($cil_pgsql_db, $image_id, $annotation_id);
        foreach($assigneeArray as $assigneeItem)
        {
            $mutil->sendMail($gmail_sender, $gmail_sender_name, $gmail_sender_pwd, $assigneeItem['email'], $subject, $message);
        }
                    
        $dbutil->deletePriorityAssignee($cil_pgsql_db, $annotation_id);
        $dbutil->deletePriority($cil_pgsql_db, $annotation_id, $image_id, $username);
        
        
        //echo "OK";
        redirect($base_url."/Annotation_priority/created_by?username=".$username."&token=".$token);
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


<?php

require_once './application/libraries/REST_Controller.php';
require_once 'GeneralUtil.php';
require_once 'DBUtil.php';
require_once 'MailUtil.php';

class Annotation_priority_service extends REST_Controller
{
    private $success = "success";
    
    public function delete_priority_post($image_id,$annotation_id, $username, $token)
    {
        $dbutil = new DBUtil();
        $mutil = new MailUtil();
           
        $cil_pgsql_db = $this->config->item('cil_pgsql_db');
        $correctToken = $dbutil->isTokenCorrect($cil_pgsql_db, $username, $token);
        
        
        //Sending emails
        $gmail_sender = $this->config->item('gmail_sender');
        $gmail_sender_name = $this->config->item('gmail_sender_name');
        $gmail_sender_pwd = $this->config->item('gmail_sender_pwd');
        date_default_timezone_set('America/Los_Angeles');
        $subject = "Image annotation removed:".$image_id."-".$annotation_id." : ".date("Y-m-d h:i:sa");
        
        if($correctToken)
        {
            $isReporter = $dbutil->isPriorityReporter($cil_pgsql_db, $annotation_id, $image_id, $username);
            if($isReporter)
            {
                if($dbutil->priorityExists($cil_pgsql_db, $annotation_id))
                {
                    $dbutil->deletePriorityAssignee($cil_pgsql_db, $annotation_id);
                    $dbutil->deletePriority($cil_pgsql_db, $annotation_id, $image_id, $username);
                    
                    
                    $creatorInfo = $dbutil->getUserInfoByUsername($cil_pgsql_db, $username);
                    $message = "Image annotation removed:".$image_id." - ".$annotation_id;
                    $mutil->sendMail($gmail_sender, $gmail_sender_name, $gmail_sender_pwd, $creatorInfo['email'], $subject, $message);
                    
                    $assigneeArray = $dbutil->getAssigneeInfoByAnnotId($cil_pgsql_db, $image_id, $annotation_id);
                    foreach($assigneeArray as $assigneeItem)
                    {
                        $mutil->sendMail($gmail_sender, $gmail_sender_name, $gmail_sender_pwd, $assigneeItem['email'], $subject, $message);
                    }
                }
            }
        }
        else
        {
            $array = array();
            $array[$this->success] = false;
            $this->response($array);
        }
    }
    
    private function sendPriorityRemoveEmail($image_id, $annotation_id)
    {
        //Sending emails
        $gmail_sender = $this->config->item('gmail_sender');
        $gmail_sender_name = $this->config->item('gmail_sender_name');
        $gmail_sender_pwd = $this->config->item('gmail_sender_pwd');
        date_default_timezone_set('America/Los_Angeles');
        $subject = "Image annotation removed - ".$image_id.", ".$annotation_id.":".date("Y-m-d h:i:sa");
        
        $message = "Annotation ID:".$annotation_id;
    }
}

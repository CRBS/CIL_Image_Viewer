<?php

require_once './application/libraries/REST_Controller.php';
require_once 'GeneralUtil.php';
require_once 'DBUtil.php';

class Annotation_priority_service extends REST_Controller
{
    private $success = "success";
    
    public function delete_priority_post($image_id,$annotation_id, $username, $token)
    {
        $dbutil = new DBUtil();
           
        $cil_pgsql_db = $this->config->item('cil_pgsql_db');
        $correctToken = $dbutil->isTokenCorrect($cil_pgsql_db, $username, $token);
        
        if($correctToken)
        {
            $isReporter = $dbutil->isPriorityReporter($cil_pgsql_db, $annotation_id, $image_id, $username);
            if($isReporter)
            {
                if($dbutil->priorityExists($cil_pgsql_db, $annotation_id))
                {
                    
                    $creatorInfo = $dbutil->getUserInfoByUsername($cil_pgsql_db, $username);
                    
                    $dbutil->deletePriorityAssignee($cil_pgsql_db, $annotation_id);
                    $dbutil->deletePriority($cil_pgsql_db, $annotation_id, $image_id, $username);
                    
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
}

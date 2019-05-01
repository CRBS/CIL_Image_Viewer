<?php

require_once 'GeneralUtil.php';
require_once 'DBUtil.php';
require_once 'DataLocalUtil.php';
require_once 'Constants.php';
class Cdeep3m_result extends CI_Controller
{
    public function view($crop_id)
    {
        $data['title'] = "View Cdeep3m result";
        $this->load->view('cdeep3m/view_cdeep3m_result_display', $data);
    }

}


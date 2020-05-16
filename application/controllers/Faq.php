<?php

class Faq extends CI_Controller
{
     public function internal_data()
     {
         $data['title'] = "CIL Image Viewer | FAQ";
         $this->load->view('internal_data/faq_display', $data);
     }
    
}


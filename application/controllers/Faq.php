<?php

class Faq extends CI_Controller
{
     public function internal_data()
     {
         $this->load->view('internal_data/faq_display', $data);
     }
    
}


<?php

    require_once 'GeneralUtil.php';
    require_once 'DBUtil.php';
    require_once 'DataLocalUtil.php';
    
    class Image_process extends CI_Controller
    {

        public function crop_image($image_id)
        {
            $this->load->helper('url');
            $dbutil = new DBUtil();
            $base_url = $this->config->item('base_url');
            $db_params = $this->config->item('db_params');
            
            $original_file_location = $dbutil->getOriginalFileLocation($db_params, $image_id);
            
            $x_location = $this->input->post('x_location', TRUE);
            $y_location = $this->input->post('y_location', TRUE);
            $width_in_pixel = $this->input->post('width_in_pixel', TRUE);
            $height_in_pixel = $this->input->post('height_in_pixel', TRUE);
            $starting_z_index = $this->input->post('starting_z_index', TRUE);
            $ending_z_index = $this->input->post('ending_z_index', TRUE);
            $email = $this->input->post('email', TRUE);
            
            
            /*echo "<br/>x_location:".$x_location;
            echo "<br/>y_location:".$y_location;
            echo "<br/>width_in_pixel:".$width_in_pixel;
            echo "<br/>height_in_pixel:".$height_in_pixel;
            echo "<br/>starting_z_index:".$starting_z_index;
            echo "<br/>ending_z_index:".$ending_z_index;
            echo "<br/>email:".$email;*/
            
            $dbutil->insertCroppingInfo($db_params, $image_id, $x_location, $y_location, $width_in_pixel, $height_in_pixel, 
                    $email, $original_file_location,$starting_z_index,$ending_z_index);
            
            redirect ($base_url."/cdeep3m/".$image_id);
            
        }
        
        
    }
<?php

    require_once 'GeneralUtil.php';
    require_once 'DBUtil.php';
    require_once 'DataLocalUtil.php';
    require_once 'CurlUtil.php';
    
    class Image_process extends CI_Controller
    {

        public function crop_image($image_id)
        {
            $this->load->helper('url');
            /********Session check**************************/
            $data_login = $this->session->userdata('data_login');
            if(is_null($data_login))
            {
                redirect ($base_url."/cdeep3m/login/".$image_id);
                return;
            }
            /********End session check **********************/
            
            $dbutil = new DBUtil();
            $cutil = new CurlUtil();
            $base_url = $this->config->item('base_url');
            $db_params = $this->config->item('db_params');
            $image_service_prefix = $this->config->item('image_service_prefix');
            $image_service_auth = $this->config->item('image_service_auth');
            
            $original_file_location = $dbutil->getOriginalFileLocation($db_params, $image_id);
            
            $x_location = $this->input->post('x_location', TRUE);
            $y_location = $this->input->post('y_location', TRUE);
            $width_in_pixel = $this->input->post('width_in_pixel', TRUE);
            $height_in_pixel = $this->input->post('height_in_pixel', TRUE);
            $starting_z_index = $this->input->post('starting_z_index', TRUE);
            $ending_z_index = $this->input->post('ending_z_index', TRUE);
            $email = $this->input->post('email', TRUE);
            $contrast_e_str = $this->input->post('contrast_e',TRUE);
            $contrast_e = false;
            if(!is_null($contrast_e_str))
                $contrast_e = true;

            
            $id = $dbutil->insertCroppingInfo($db_params, $image_id, $x_location, $y_location, $width_in_pixel, $height_in_pixel, 
                    $email, $original_file_location,$starting_z_index,$ending_z_index,$contrast_e);
            
            if(is_numeric($id))
            {
                $url = $image_service_prefix."/image_process_service/crop/stage/".$id;
                $response = $cutil->curl_post($url, null, $image_service_auth);
            }
            
            redirect ($base_url."/cdeep3m/".$image_id);

        }
        
        
    }
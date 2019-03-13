<?php

    require_once 'GeneralUtil.php';
    require_once 'DBUtil.php';
    require_once 'DataLocalUtil.php';
    require_once 'CurlUtil.php';
    
    class Image_process extends CI_Controller
    {
        public function run_cdeep3m($image_id)
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
            echo "<br/>".$image_id;
        }
        public function preview_cdeep3m_image($image_id)
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
            
            $x_location = $this->input->post('ct_x_location', TRUE);
            $y_location = $this->input->post('ct_y_location', TRUE);
            $width_in_pixel = $this->input->post('ct_width_in_pixel', TRUE);
            $height_in_pixel = $this->input->post('ct_height_in_pixel', TRUE);
            $starting_z_index = $this->input->post('ct_starting_z_index', TRUE);
            $ending_z_index = $this->input->post('ct_ending_z_index', TRUE); // Same as the starting Z index
            $ct_training_models = $this->input->post('ct_training_models', TRUE);
            $email = $this->input->post('email', TRUE);
            $contrast_e_str = $this->input->post('contrast_e',TRUE);
            $contrast_e = false;
            if(!is_null($contrast_e_str))
                $contrast_e = true;
            
            $is_cdeep3m_preview = true;
            $is_cdeep3m_run = false;
            
            
            echo "<br/>image_id:".$image_id;
            echo "<br/>x_location:".$x_location;
            echo "<br/>y_location:".$y_location;
            echo "<br/>width_in_pixel:".$width_in_pixel;
            echo "<br/>height_in_pixel:".$height_in_pixel;
            echo "<br/>starting_z_index:".$starting_z_index;
            echo "<br/>ending_z_index:".$ending_z_index;
            echo "<br/>ct_training_models:".$ct_training_models;
            echo "<br/>email:".$email;
            if($contrast_e)
                echo "<br/>contrast_e:true";
            else
                echo "<br/>contrast_e:true";
            
            $id = $dbutil->insertCroppingInfoWithTraining($db_params, $image_id, $x_location, $y_location, $width_in_pixel, $height_in_pixel, 
                    $email, $original_file_location,$starting_z_index,$ending_z_index,$contrast_e,
                    $is_cdeep3m_preview, $is_cdeep3m_run, $ct_training_models);
            
             echo "<br/><br/>New ID:".$id;
             
             if(is_numeric($id))
            {
                $url = $image_service_prefix."/image_process_service/image_preview/stage/".$id;
                $response = $cutil->curl_post($url, null, $image_service_auth);
                $json = json_decode($response);
                echo "<br/><br/>".  json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            }
             
        }

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
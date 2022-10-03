<?php
    require_once 'GeneralUtil.php';
    require_once 'DBUtil.php';
    require_once 'DataLocalUtil.php';
    
    class Image_viewer extends CI_Controller
    {
        public function view($image_id="0")
        {
            $this->load->helper('url');
            
            $image_tar_dir = $this->config->item('image_tar_dir');
            $lag = $this->input->get('lat', TRUE);
            $lng = $this->input->get('lng', TRUE);
            $zoom = $this->input->get('zoom', TRUE);
            $zindex = $this->input->get('zindex', TRUE);
            $tindex = $this->input->get('tindex', TRUE);
            
            if(is_null($zindex) || !is_numeric($zindex))
              $zindex = 0;
            if(is_null($tindex) || !is_numeric($tindex))
              $tindex = 0;
            
            $data['zindex'] = intval($zindex);
            $data['tindex'] = intval($tindex);

            $db_params = $this->config->item('db_params');
            $cil_pgsql_db = $this->config->item('cil_pgsql_db');
            $data['base_url'] = $this->config->item('base_url');
            
            
            $dbutil = new DBUtil();
            if(!is_null($cil_pgsql_db))
            {
                if($dbutil->isInternalImage($cil_pgsql_db, $image_id))
                {
                    if(!$dbutil->isInternalImagePublic($cil_pgsql_db, $image_id))
                    {
                        //echo "Is internal";
                        show_404();
                        return;
                    }
                }
            }
            
            
            $localutil = new DataLocalUtil();
            $array = $dbutil->getImageInfo($db_params,$image_id);
            if(is_null($array))
               $array =  $localutil->getLocalImageInfo ($image_id, $image_tar_dir);
                
            if(is_null($array))
            {
                $data['test'] = "test";
                $this->load->view('errors/not_exist', $data);
            }
            else 
            {
                $json_str = json_encode($array);
                $json = json_decode($json_str);
                
                if(!$json->success)
                {
                    $data['test'] = "test";
                    $this->load->view('errors/not_exist', $data);
                    return;
                }
                
                if(isset($json->is_public) && !$json->is_public)
                {
                    $base_url = $this->config->item('base_url');
                    //redirect ($base_url."/user/login/".$image_id);
                    //return;
                    /********Check session**************************/
                    $data_login = $this->session->userdata('data_login');

                    if(is_null($data_login))
                    {
                       redirect ($base_url."/user/login/".$image_id);
                       return;
                    }
                    /******End check session **********************/
                }
                
                
                if($json->is_timeseries)
                    $data['title'] = "CIL Image Viewer | Time series | ".$image_id;
                else if($json->max_z == 0)
                    $data['title'] = "CIL Image Viewer | 2D | ".$image_id;
                else
                    $data['title'] = "CIL Image Viewer | Z stack | ".$image_id;
                
                $data['serverName'] = $this->config->item('base_url');
                $data['folder_postfix'] = $image_id;
                if($json->is_rgb)
                    $data['rgb'] = "true";
                else
                    $data['rgb'] = "false";
                
                $data['image_id'] = $image_id;
                $data['max_zoom'] = $json->max_zoom;
                $data['max_z'] = $json->max_z;
                if(is_null($lag))
                    $data['init_lat'] =  $json->init_lat;
                else
                    $data['init_lat'] =  $lag;
                
                if(is_null($lng))
                    $data['init_lng'] =  $json->init_lng;
                else
                    $data['init_lng'] = $lng;
                
                if(is_null($zoom))
                    $data['init_zoom'] = $json->init_zoom;
                else
                    $data['init_zoom'] = $zoom;
                
                
                $data['max_t'] = $json->max_t;
                
                $cil_pgsql_db = $this->config->item('cil_pgsql_db');
                $downloadInfo = $this->getSliceDownloadInfo($cil_pgsql_db, $image_id);
                
                if(!is_null($downloadInfo))
                {
                    $data['is_downloadable'] = true;
                    $data['min_index'] = $downloadInfo['min_index'];
                }
                else 
                    $data['is_downloadable'] = false;
                
                if(!$json->is_timeseries)
                {
                    $this->load->view('image/image_viewer_display', $data);
                    
                }
                else
                {
                    if($data['zindex'] == 0)
                        $data['zindex'] = 1;
                    
                    if($data['tindex'] ==0)
                        $data['tindex'] = 1;
                    $this->load->view('image/image_viewer_display_ts2', $data);
            
                }
            }
        }
        
        
        private function getSliceDownloadInfo($cil_pgsql_db, $image_id)
        {
            $array = null;
            $conn = pg_pconnect($cil_pgsql_db);
            if (!$conn) 
                return null;
            
            $sql = "select id, min_index from extract_zimage_method where image_id = $1";
            $input = array();
            array_push($input, $image_id);
            
            $result = pg_query_params($conn, $sql, $input);
        
            if(!$result) 
            {
                pg_close($conn);
                return null;
            }
            
            if($row = pg_fetch_row($result))
            {
                $array = array();
                $array['id'] = intval($row[0]);
                $array['min_index'] = intval($row[1]);
            }
            
            pg_close($conn);
            return $array;
        }
        
    }

    

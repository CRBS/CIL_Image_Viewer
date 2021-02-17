<?php



    class Leaflet_data extends CI_Controller
    {
	private $prefix = "";
        
        
        public function show_memory()
        {
            $memory_limit = ini_get('memory_limit');
            echo "<br/>Memory:".$memory_limit;
            echo "<br/>Memory used:".$this->convert(memory_get_usage(true));
        }
        
        private function convert($size)
        {
            $unit=array('b','kb','mb','gb','tb','pb');
            return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
        }
        
 
        
        public function tar_time_filter($image_id,$tar_name,$root_folder,$z="0",$x="0",$y="0.png")
        {
           //error_reporting(0);
           
           $image_tar_dir = $this->config->item("image_tar_dir");
           $wib_error_log = $this->config->item("wib_error_log");
           $place_holder_image = $this->config->item("place_holder_image");
           
           $red = 255;
	   $green = 255;
           $blue = 255;
           $contrast = 0;
           $brightness = 0;

	   $temp = $this->input->get('red', TRUE);
           if(!is_null($temp) && is_numeric($temp))
           {
             $red = intval($temp);
 	     if($red > 255)
		$red = 255;
	     else if($red < 0)
	        $red = 0;
	   }

	   $temp = $this->input->get('green', TRUE);
           if(!is_null($temp) && is_numeric($temp))
           {
             $green = intval($temp);
 	     if($green > 255)
		$green = 255;
	     else if($red < 0)
	        $green = 0;
	   }

	   $temp = $this->input->get('blue', TRUE);
           if(!is_null($temp) && is_numeric($temp))
           {
             $blue = intval($temp);
 	     if($blue > 255)
		$blue = 255;
	     else if($blue < 0)
	        $blue = 0;
	   }
           
           $temp = $this->input->get('contrast', TRUE);
           if(!is_null($temp) && is_numeric($temp))
           {
             $contrast = intval($temp);
 	     if($contrast > 100)
		$contrast = 100;
	     else if($contrast < -100)
	        $contrast = -100;
	   }
           
           $temp = $this->input->get('brightness', TRUE);
           if(!is_null($temp) && is_numeric($temp))
           {
             $brightness = intval($temp);
 	     if($brightness > 100)
		$brightness = 100;
	     else if($brightness < -100)
	        $brightness = -100;
	   }

	   $file = $image_tar_dir."/".$image_id."/".$tar_name."/".$root_folder."/".$z."/".$x."/".$y;
           //$this->my_error_log("\n".$file."--exist:".file_exists("phar://".$file), 3, $wib_error_log);
 	   $data = null;
           $skipFilter = false;
	   if(file_exists("phar://".$file))
	   	$data = file_get_contents("phar://".$file);
	   else
		$skipFilter = true;
	
           if($skipFilter)
		$data = file_get_contents($place_holder_image);
    
	   $im = imagecreatefromstring($data);
	   header('Content-Type: image/png');

	   if(!$skipFilter)
	   {	
	   //////////Filter//////////////////
	   $rgb = array($red,$green,$blue);
	   $rgb = array(255-$rgb[0],255-$rgb[1],255-$rgb[2]);

	   imagefilter($im, IMG_FILTER_NEGATE); 
	   imagefilter($im, IMG_FILTER_COLORIZE, $rgb[0], $rgb[1], $rgb[2]); 
	   imagefilter($im, IMG_FILTER_CONTRAST,$contrast);
           imagefilter($im, IMG_FILTER_BRIGHTNESS,$brightness);
           
           imagefilter($im, IMG_FILTER_NEGATE); 

	   imagealphablending( $im, false );
	   imagesavealpha( $im, true );

	   /////////End filter///////////////
	   }

	   imagepng($im);
           imagedestroy($im);
        }
        
        /*
	public function tar_filter($tar_folder,$tar_name,$root_folder,$z="0",$x="0",$y="0.png")
        { 
           error_reporting(0); 
           
           $start_time = microtime(true);
           
           $ssd_image_dir = $this->config->item("ssd_image_dir");
         	   
           $image_tar_dir = $this->config->item("image_tar_dir");
           $wib_error_log = $this->config->item("wib_error_log");
           $place_holder_image = $this->config->item("place_holder_image");
           
           
           
           
           $red = 255;
	   $green = 255;
           $blue = 255;
           $contrast = 0;
           $brightness = 0;

	   $temp = $this->input->get('red', TRUE);
           if(!is_null($temp) && is_numeric($temp))
           {
             $red = intval($temp);
 	     if($red > 255)
		$red = 255;
	     else if($red < 0)
	        $red = 0;
	   }

	   $temp = $this->input->get('green', TRUE);
           if(!is_null($temp) && is_numeric($temp))
           {
             $green = intval($temp);
 	     if($green > 255)
		$green = 255;
	     else if($red < 0)
	        $green = 0;
	   }

	   $temp = $this->input->get('blue', TRUE);
           if(!is_null($temp) && is_numeric($temp))
           {
             $blue = intval($temp);
 	     if($blue > 255)
		$blue = 255;
	     else if($blue < 0)
	        $blue = 0;
	   }
           
           $temp = $this->input->get('contrast', TRUE);
           if(!is_null($temp) && is_numeric($temp))
           {
             $contrast = intval($temp);
 	     if($contrast > 100)
		$contrast = 100;
	     else if($contrast < -100)
	        $contrast = -100;
	   }
           
           $temp = $this->input->get('brightness', TRUE);
           if(!is_null($temp) && is_numeric($temp))
           {
             $brightness = intval($temp);
 	     if($brightness > 100)
		$brightness = 100;
	     else if($brightness < -100)
	        $brightness = -100;
	   }

           
           $file = "---";
           $readTime = 0;
           $data = null;
           $readStart = microtime(true);
           
           $localFile =  $image_tar_dir."/".$tar_folder."/".$root_folder."/".$z."/".$x."/".$y;
           $ssdFile = $ssd_image_dir."/".$tar_folder."/".$root_folder."/".$z."/".$x."/".$y;
           $ssdTarFile = $ssd_image_dir."/".$tar_folder."/".$tar_name;
           
           $sqllite3File = $image_tar_dir."/".$tar_folder."/".$root_folder.".sqllite3";
           
           
           $this->my_error_log("\nTry local file:".$localFile,3,$wib_error_log);
           if(intval($x) < 0)
           {
                $data = file_get_contents($place_holder_image);
           }
           else if(file_exists($sqllite3File))
           {
               $this->my_error_log("\n sqlite3 file exists:".$sqllite3File,3,$wib_error_log);
               $ipath = $z."/".$x."/".$y;
               $db = new SQLite3($sqllite3File);
               $sql ="select data_bin from images where path = '".$ipath."'";
               $this->my_error_log("\n".$sql,3,$wib_error_log);
               $data = $db->querySingle($sql);
               $db->close();
               if(is_null($data))
               {
                   $data = file_get_contents($place_holder_image);
               }
               
           }
           else if(file_exists($ssdFile))
           {
               $file = $ssdFile;
               $data = file_get_contents($file);
               $this->my_error_log("\nUsing ssd local file:".$file,3,$wib_error_log);
           }
           else if(file_exists($localFile))
           {
               $file = $localFile;
               $data = file_get_contents($file);
               $this->my_error_log("\nUsing local file:".$file,3,$wib_error_log);
           }
           else if(file_exists($ssdTarFile))
           {
               $file = $ssdTarFile."/".$root_folder."/".$z."/".$x."/".$y;
                
                    //$this->my_error_log("\nmcahce for ".$file." is NULL",3,$wib_error_log);
                $skipFilter = false;
                
                if(file_exists("phar://".$file))
                {
                    $this->my_error_log("\nUsing ssd tar file:".$file,3,$wib_error_log);
                    $data = file_get_contents("phar://".$file);
                }
                else
                {
                    $this->my_error_log("\nUsing placeholder file:".$file,3,$wib_error_log);
                    $skipFilter = true;
                }

                if($skipFilter)
                {
                         //$data = file_get_contents($place_holder_image);
                    $data =  $place_holder_image;
                }
           }
           else 
           {
                $file = $image_tar_dir."/".$tar_folder."/".$tar_name."/".$root_folder."/".$z."/".$x."/".$y;
                
                    //$this->my_error_log("\nmcahce for ".$file." is NULL",3,$wib_error_log);
                $skipFilter = false;
                
                if(file_exists("phar://".$file))
                {
                    $this->my_error_log("\nUsing tar file:".$file,3,$wib_error_log);
                    $data = file_get_contents("phar://".$file);
                }
                else
                {
                    $this->my_error_log("\nUsing placeholder file:".$file,3,$wib_error_log);
                    $skipFilter = true;
                }

                if($skipFilter)
                {
                         //$data = file_get_contents($place_holder_image);
                    $data =  $place_holder_image;
                }
                    
                
           }
           
          
           $readEnd = microtime(true);
           $readTime = $readEnd - $readStart;
    
           
           
	   $im = imagecreatefromstring($data);
           
	   header('Content-Type: image/png');

           
	   if(!$skipFilter)
	   {	
                //////////Filter//////////////////
                $rgb = array($red,$green,$blue);
                $rgb = array(255-$rgb[0],255-$rgb[1],255-$rgb[2]);

                //imagefilter($im, IMG_FILTER_NEGATE);

                if($red==255 && $green==255 && $blue==255)
                {
                    //Do nothing
                    //$this->my_error_log("\nColor filter do nothing".$processTime,3,$wib_error_log);
                }
                else
                {
                    //$this->my_error_log("\nColor filter DO Something".$processTime,3,$wib_error_log);
                     imagefilter($im, IMG_FILTER_COLORIZE, $rgb[0], $rgb[1], $rgb[2]); 
                }
                
                if($contrast != 0)
                {
                    //$this->my_error_log("\nContrast DO something".$processTime,3,$wib_error_log);
                    imagefilter($im, IMG_FILTER_CONTRAST,$contrast);
                }
                else
                {
                    //$this->my_error_log("\nContrast do nothing".$processTime,3,$wib_error_log);
                }
                
                if($brightness != 0)
                {
                    //$this->my_error_log("\nBrightness DO something".$processTime,3,$wib_error_log);
                    $brightness = $brightness*-1;
                    imagefilter($im, IMG_FILTER_BRIGHTNESS,$brightness);
                }
                else 
                {
                    //$this->my_error_log("\nBrightness do nothing".$processTime,3,$wib_error_log);
                }
                //imagefilter($im, IMG_FILTER_NEGATE); 

                imagealphablending( $im, false );
                imagesavealpha( $im, true );

                /////////End filter///////////////
	   }
           $processStart = microtime(true);
	   imagepng($im,NULL,0);
           imagedestroy($im);
           $processEnd = microtime(true);
           $processTime = $processEnd - $processStart;
           
           $end_time = microtime(true);
           $diff_time = $end_time-$start_time;
           $this->my_error_log("\n".$file."-----".$diff_time."seconds-----Read time:".$readTime."--------Process time:".$processTime,3,$wib_error_log);
           $this->my_error_log("\n-----------------------------------------------------------------------------------------------------------------------\n",3,$wib_error_log);
	}
        */
        
        
        public function tar_filter($tar_folder,$tar_name,$root_folder,$z="0",$x="0",$y="0.png")
        { 
           error_reporting(0); 
           
           $start_time = microtime(true);
           
           $ssd_image_dir = $this->config->item("ssd_image_dir");
         	   
           $image_tar_dir = $this->config->item("image_tar_dir");
           $wib_error_log = $this->config->item("wib_error_log");
           $place_holder_image = $this->config->item("place_holder_image");
           
          
          
           
           
           $red = 255;
	   $green = 255;
           $blue = 255;
           $contrast = 0;
           $brightness = 0;

	   $temp = $this->input->get('red', TRUE);
           if(!is_null($temp) && is_numeric($temp))
           {
             $red = intval($temp);
 	     if($red > 255)
		$red = 255;
	     else if($red < 0)
	        $red = 0;
	   }

	   $temp = $this->input->get('green', TRUE);
           if(!is_null($temp) && is_numeric($temp))
           {
             $green = intval($temp);
 	     if($green > 255)
		$green = 255;
	     else if($red < 0)
	        $green = 0;
	   }

	   $temp = $this->input->get('blue', TRUE);
           if(!is_null($temp) && is_numeric($temp))
           {
             $blue = intval($temp);
 	     if($blue > 255)
		$blue = 255;
	     else if($blue < 0)
	        $blue = 0;
	   }
           
           $temp = $this->input->get('contrast', TRUE);
           if(!is_null($temp) && is_numeric($temp))
           {
             $contrast = intval($temp);
 	     if($contrast > 100)
		$contrast = 100;
	     else if($contrast < -100)
	        $contrast = -100;
	   }
           
           $temp = $this->input->get('brightness', TRUE);
           if(!is_null($temp) && is_numeric($temp))
           {
             $brightness = intval($temp);
 	     if($brightness > 100)
		$brightness = 100;
	     else if($brightness < -100)
	        $brightness = -100;
	   }

           /////////////////Histogram///////////////////////////////
           $username = "Public";
           $temp = $this->input->get('username', TRUE);
           if(!is_null($temp))
           {
               $username = trim($temp);
           }
           
           $histogram_folder = $this->config->item("histogram_folder");
           if(!file_exists($histogram_folder))
               mkdir($histogram_folder);
           
           $histogram_folder = $histogram_folder."/".$username;
           if(!file_exists($histogram_folder))
               mkdir($histogram_folder);
           
           
           $query_path = $tar_folder."/".$tar_name."/".$root_folder."/".$z."/".$x."/".$y."/".$brightness."/".$contrast;
           $queryArray = explode("/", $query_path);
          
           $h_filePath = $histogram_folder."/".$tar_folder.".log";
           if(file_exists($h_filePath))
           {
                $content = file_get_contents($h_filePath);
                $content = trim($content);
                $deleteH = false;
                if(strlen($content) > 0)
                {
                   $lineArray = explode("\n", $content);
                   if(count($lineArray) > 0)
                   {
                       $line = $lineArray[0];
                       $line = trim($line);
                       $lineArray = explode("/", $line);

                       if(strcmp($queryArray[1], $lineArray[1]) !=0 ||  
                               strcmp($queryArray[2], $lineArray[2]) !=0 ||
                               strcmp($queryArray[3], $lineArray[3]) !=0 ||
                               strcmp($queryArray[6], $lineArray[6]) !=0 ||
                               strcmp($queryArray[7], $lineArray[7]) !=0 )
                       {
                           unlink($h_filePath);
                       }
                   }
                }
           }
           error_log($query_path."\n", 3, $h_filePath);
           /////////////////End Histogram///////////////////////////////
           
           
           $file = "---";
           $readTime = 0;
           $data = null;
           $readStart = microtime(true);
           
           $localFile =  $image_tar_dir."/".$tar_folder."/".$root_folder."/".$z."/".$x."/".$y;
           $ssdFile = $ssd_image_dir."/".$tar_folder."/".$root_folder."/".$z."/".$x."/".$y;
           $ssdTarFile = $ssd_image_dir."/".$tar_folder."/".$tar_name;
           
           $sqllite3File = $image_tar_dir."/".$tar_folder."/".$root_folder.".sqllite3";
           
           

           
           
           $this->my_error_log("\nTry local file:".$localFile,3,$wib_error_log);
           if(intval($x) < 0)
           {
                $data = file_get_contents($place_holder_image);
           }
           else if(file_exists($sqllite3File))
           {
               $this->my_error_log("\n sqlite3 file exists:".$sqllite3File,3,$wib_error_log);
               $ipath = $z."/".$x."/".$y;
               $db = new SQLite3($sqllite3File);
               $sql ="select data_bin from images where path = '".$ipath."'";
               $this->my_error_log("\n".$sql,3,$wib_error_log);
               $data = $db->querySingle($sql);
               $db->close();
               if(is_null($data))
               {
                   $data = file_get_contents($place_holder_image);
               }
               
           }
           else if(file_exists($ssdFile))
           {
               $file = $ssdFile;
               $data = file_get_contents($file);
               $this->my_error_log("\nUsing ssd local file:".$file,3,$wib_error_log);
           }
           else if(file_exists($localFile))
           {
               $file = $localFile;
               $data = file_get_contents($file);
               $this->my_error_log("\nUsing local file:".$file,3,$wib_error_log);
           }
           else if(file_exists($ssdTarFile))
           {
               $file = $ssdTarFile."/".$root_folder."/".$z."/".$x."/".$y;
                
                    //$this->my_error_log("\nmcahce for ".$file." is NULL",3,$wib_error_log);
                $skipFilter = false;
                
                if(file_exists("phar://".$file))
                {
                    $this->my_error_log("\nUsing ssd tar file:".$file,3,$wib_error_log);
                    $data = file_get_contents("phar://".$file);
                }
                else
                {
                    $this->my_error_log("\nUsing placeholder file:".$file,3,$wib_error_log);
                    $skipFilter = true;
                }

                if($skipFilter)
                {
                         //$data = file_get_contents($place_holder_image);
                    $data =  $place_holder_image;
                }
           }
           else 
           {
                $file = $image_tar_dir."/".$tar_folder."/".$tar_name."/".$root_folder."/".$z."/".$x."/".$y;
                
                    //$this->my_error_log("\nmcahce for ".$file." is NULL",3,$wib_error_log);
                $skipFilter = false;
                
                if(file_exists("phar://".$file))
                {
                    $this->my_error_log("\nUsing tar file:".$file,3,$wib_error_log);
                    $data = file_get_contents("phar://".$file);
                }
                else
                {
                    $this->my_error_log("\nUsing placeholder file:".$file,3,$wib_error_log);
                    $skipFilter = true;
                }

                if($skipFilter)
                {
                         //$data = file_get_contents($place_holder_image);
                    $data =  $place_holder_image;
                }
                    
                
           }
           
          
           $readEnd = microtime(true);
           $readTime = $readEnd - $readStart;
    
           
           
	   $im = imagecreatefromstring($data);
           
	   header('Content-Type: image/png');

           
	   if(!$skipFilter)
	   {	
                //////////Filter//////////////////
                $rgb = array($red,$green,$blue);
                $rgb = array(255-$rgb[0],255-$rgb[1],255-$rgb[2]);

                //imagefilter($im, IMG_FILTER_NEGATE);

                if($red==255 && $green==255 && $blue==255)
                {
                    //Do nothing
                    //$this->my_error_log("\nColor filter do nothing".$processTime,3,$wib_error_log);
                }
                else
                {
                    //$this->my_error_log("\nColor filter DO Something".$processTime,3,$wib_error_log);
                     imagefilter($im, IMG_FILTER_COLORIZE, $rgb[0], $rgb[1], $rgb[2]); 
                }
                
                if($contrast != 0)
                {
                    //$this->my_error_log("\nContrast DO something".$processTime,3,$wib_error_log);
                    imagefilter($im, IMG_FILTER_CONTRAST,$contrast);
                }
                else
                {
                    //$this->my_error_log("\nContrast do nothing".$processTime,3,$wib_error_log);
                }
                
                if($brightness != 0)
                {
                    //$this->my_error_log("\nBrightness DO something".$processTime,3,$wib_error_log);
                    $brightness = $brightness*-1;
                    imagefilter($im, IMG_FILTER_BRIGHTNESS,$brightness);
                }
                else 
                {
                    //$this->my_error_log("\nBrightness do nothing".$processTime,3,$wib_error_log);
                }
                //imagefilter($im, IMG_FILTER_NEGATE); 

                imagealphablending( $im, false );
                imagesavealpha( $im, true );

                /////////End filter///////////////
	   }
           $processStart = microtime(true);
	   imagepng($im,NULL,0);
           imagedestroy($im);
           $processEnd = microtime(true);
           $processTime = $processEnd - $processStart;
           
           $end_time = microtime(true);
           $diff_time = $end_time-$start_time;
           $this->my_error_log("\n".$file."-----".$diff_time."seconds-----Read time:".$readTime."--------Process time:".$processTime,3,$wib_error_log);
           $this->my_error_log("\n-----------------------------------------------------------------------------------------------------------------------\n",3,$wib_error_log);
	}
        
        private function my_error_log($message,$option, $wib_error_log)
        {
            $enable_log = $this->config->item("enable_log");
            if(!$enable_log)
                return;
            
            date_default_timezone_set('America/Los_Angeles');
            error_log(date("Y-m-d h:i:sa").":".$message, 3, $wib_error_log);
            
            
        }
        
    }

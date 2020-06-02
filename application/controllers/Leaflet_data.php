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
           //error_log("\n".$file."--exist:".file_exists("phar://".$file), 3, $wib_error_log);
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
        
        
	public function tar_filter($tar_folder,$tar_name,$root_folder,$z="0",$x="0",$y="0.png")
        { 
           error_reporting(0); 
           
           $start_time = microtime(true);
           
  
         	   
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
           error_log("\nTry local file:".$localFile,3,$wib_error_log);
           if(intval($x) < 0)
           {
                $data = file_get_contents($place_holder_image);
           }
           else if(file_exists($localFile))
           {
               $file = $localFile;
               $data = file_get_contents($file);
               error_log("\nUsing local file:".$file,3,$wib_error_log);
           }
           else 
           {
                $file = $image_tar_dir."/".$tar_folder."/".$tar_name."/".$root_folder."/".$z."/".$x."/".$y;
                
                    //error_log("\nmcahce for ".$file." is NULL",3,$wib_error_log);
                $skipFilter = false;
                
                if(file_exists("phar://".$file))
                {
                    error_log("\nUsing tar file:".$file,3,$wib_error_log);
                    $data = file_get_contents("phar://".$file);
                }
                else
                {
                    error_log("\nUsing placeholder file:".$file,3,$wib_error_log);
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
                    //error_log("\nColor filter do nothing".$processTime,3,$wib_error_log);
                }
                else
                {
                    //error_log("\nColor filter DO Something".$processTime,3,$wib_error_log);
                     imagefilter($im, IMG_FILTER_COLORIZE, $rgb[0], $rgb[1], $rgb[2]); 
                }
                
                if($contrast != 0)
                {
                    //error_log("\nContrast DO something".$processTime,3,$wib_error_log);
                    imagefilter($im, IMG_FILTER_CONTRAST,$contrast);
                }
                else
                {
                    //error_log("\nContrast do nothing".$processTime,3,$wib_error_log);
                }
                
                if($brightness != 0)
                {
                    //error_log("\nBrightness DO something".$processTime,3,$wib_error_log);
                    $brightness = $brightness*-1;
                    imagefilter($im, IMG_FILTER_BRIGHTNESS,$brightness);
                }
                else 
                {
                    //error_log("\nBrightness do nothing".$processTime,3,$wib_error_log);
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
           error_log("\n".$file."-----".$diff_time."seconds-----Read time:".$readTime."--------Process time:".$processTime,3,$wib_error_log);
           error_log("\n-----------------------------------------------------------------------------------------------------------------------\n",3,$wib_error_log);
	}
    }

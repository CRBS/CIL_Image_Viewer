<?php

class Histogram
{
    public function generateImages($h_filePath, $outputFolder, $ssd_image_dir, $image_tar_dir)
    {
        $content = file_get_contents($h_filePath);
        $content = trim($content);
        
        $fileArray = explode("\n", $content);
        $index = 0;
        foreach($fileArray as $filePath)
        {
            $filePath = trim($filePath);
            if(strlen($filePath)==0)
                continue;
            
            $itemArray = explode("/", $filePath);
            $tar_folder = $itemArray[0];
            $tar_name = $itemArray[1];
            $root_folder = $itemArray[2];
            $z = intval($itemArray[3]);
            $x = intval($itemArray[4]);
            $y = $itemArray[5];
            $brightness = intval($itemArray[6]);
            $contrast = intval($itemArray[7]);
            
            if($x < 0  || $z < 0)
                continue;
            
           $localFile =  $image_tar_dir."/".$tar_folder."/".$root_folder."/".$z."/".$x."/".$y;
           $ssdFile = $ssd_image_dir."/".$tar_folder."/".$root_folder."/".$z."/".$x."/".$y;
           $ssdTarFile = $ssd_image_dir."/".$tar_folder."/".$tar_name;
           $sqllite3File = $image_tar_dir."/".$tar_folder."/".$root_folder.".sqllite3";
           
           $data = null;
           
           if(file_exists($sqllite3File))
           {
              
               $ipath = $z."/".$x."/".$y;
               $db = new SQLite3($sqllite3File);
               $sql ="select data_bin from images where path = '".$ipath."'";
               
               $data = $db->querySingle($sql);
               $db->close();
               
               
           }
           else if(file_exists($ssdFile))
           {
               $file = $ssdFile;
               $data = file_get_contents($file);
               
           }
           else if(file_exists($localFile))
           {
               $file = $localFile;
               $data = file_get_contents($file);
               
           }
           else if(file_exists($ssdTarFile))
           {
                $file = $ssdTarFile."/".$root_folder."/".$z."/".$x."/".$y;
                $skipFilter = false;
                if(file_exists("phar://".$file))
                {
                    $data = file_get_contents("phar://".$file);
                }
                
           }
           else 
           {
                $file = $image_tar_dir."/".$tar_folder."/".$tar_name."/".$root_folder."/".$z."/".$x."/".$y;
                
                    //$this->my_error_log("\nmcahce for ".$file." is NULL",3,$wib_error_log);
                $skipFilter = false;
                
                if(file_exists("phar://".$file))
                {
                    
                    $data = file_get_contents("phar://".$file);
                }
                
           }
           
           if(!is_null($data) && strlen($data) > 0)
           {
                $im = imagecreatefromstring($data);
                if($contrast != 0)
                {
                     imagefilter($im, IMG_FILTER_CONTRAST,$contrast);
                }
           
                if($brightness != 0)
                {
                    //$this->my_error_log("\nBrightness DO something".$processTime,3,$wib_error_log);
                    $brightness = $brightness*-1;
                    imagefilter($im, IMG_FILTER_BRIGHTNESS,$brightness);
                }
                

                
                ob_start();
                imagepng($im);
                $image_data = ob_get_contents();
                ob_end_clean();
                
                
                if(!is_null($image_data))
                {
                    $outputPath = $outputFolder."/".$x."_".$y;
                    file_put_contents($outputPath, $image_data);
                }
                
            }
           
        }
    }
}


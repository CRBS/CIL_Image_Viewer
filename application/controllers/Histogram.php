<?php

include_once 'GeneralUtil.php';

class Histogram
{
    private function cleanFolder($folder)
    {
        $gutil = new GeneralUtil();
        
        $files = scandir($folder);
        foreach($files as $file)
        {
            if(strcmp($file, ".")==0 || strcmp($file, "..")==0)
                continue;
            
            if($gutil->endsWith($file, ".png"))
            {
                $filePath = $folder."/".$file;
                if(file_exists($filePath))
                    unlink ($filePath);
            }
        }
        
    }
    
    private function generate_cmd($col, $max_x, $min_x, $inputFolder, $outputFolder, $imagemagick_convert)
    {
        $logFile = $outputFolder."/cmd.log";
        $cmd = $imagemagick_convert." -append ";
        for($i=$max_x;$i>=$min_x;$i--)
        {
            $cmd = $cmd." ".$inputFolder."/".$col."_".$i.".png ";
             //error_log("\n".$cmd, 3, $logFile);
        }
        $logFile = $outputFolder."/cmd.log";
        $cmd = $cmd." ".$outputFolder."/".$col.".png";
       
        return $cmd;
    }
    
    
    public function stitchImages($inputFolder, $outputFolder, $imagemagick_convert)
    {
        $this->cleanFolder($outputFolder);
         $logFile = $outputFolder."/cmd.log";
         if(file_exists($logFile))
            unlink($logFile);
         
        //error_log("\n"."inputFolder:".$inputFolder, 3, $logFile);
            
        $images = scandir($inputFolder);
        //error_log("\n"."Image count:".count($images), 3, $logFile);
        $x_max = 0;
        $y_max = 0;
        
        $x_min = 999999;
        $y_min = 999999;
        
        foreach($images as $image)
        {
            //error_log("\n"."image:".$image, 3, $logFile);
            if(strcmp($image, ".") ==0  || strcmp($image, "..") ==0)
                    continue;

            $name = str_replace(".png", "", $image);
            
            
            $names = explode("_", $name);

            if(count($names) != 2)
                continue;
            
            $x = intval($names[1]);
            $y = intval($names[0]);

            //error_log("\n"."x:".$x, 3, $logFile);
            //error_log("\n"."y:".$y, 3, $logFile);
            
            if($x > $x_max)
                $x_max = $x;

            if($y > $y_max)
                $y_max = $y;

            if($x < $x_min)
                $x_min = $x;
            
            if($y < $y_min)
                $y_min = $y;
        }
        
        
        //error_log("\n"."x_min:".$x_min, 3, $logFile);
        //error_log("\n"."x_max:".$x_max, 3, $logFile);
        //error_log("\n"."y_min:".$y_min, 3, $logFile);
        //error_log("\n"."y_max:".$y_max, 3, $logFile);
        //Vertical merge
        for($j=$y_max;$j>=$y_min;$j--)
        {
             //error_log("\n"."j:".$j, 3, $logFile);
            $cmd = $this->generate_cmd($j, $x_max, $x_min, $inputFolder, $outputFolder, $imagemagick_convert);
            shell_exec($cmd);
        }
        
        
        $cmd = $imagemagick_convert." +append ";
        for($j=0;$j<=$y_max;$j++)
        {
            $cmd = $cmd." ".$outputFolder."/".$j.".png ";
        }
        $cmd = $cmd." ".$outputFolder."/final.png";
        shell_exec($cmd); 
    }
    
    public function generateImages($h_filePath, $outputFolder, $ssd_image_dir, $image_tar_dir)
    {
        $this->cleanFolder($outputFolder);
        
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


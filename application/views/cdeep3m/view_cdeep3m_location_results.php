<html>
<head>
        <link rel="stylesheet" href="/css/bootstrap.min.css"> 
        <script src="/js/jquery.min.3.3.1.js"></script> 

        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/popper.min.js"></script>

        <link rel="stylesheet" href="/css/custom.css"> 
        <link rel="icon" href="/images/favicon.ico" type="image/x-icon" />  
</head>
<body>
<div class="container">
    

    <div class="row">
        
        <div class="col-md-12">
            <img src="/images/CIL_logo_final_75H.jpg" height="50px"/>
        </div>
    </div>
    <div class="col-md-12"><br/></div>
    <div class="col-md-12">
                <span class="cil_title">Previous results</span>
                </div>
<?php



//var_dump($location_results);
//$json_str = json_encode($location_results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
//echo $json_str;
foreach($location_results as $lr)
{
?>
<div class="row">
    <div class="col-md-6">
        
       <!--  <img src ="https://iruka.crbs.ucsd.edu/cdeep3m_results/<?php //echo $lr->id; ?>/overlay/overlay_002.png" width="512px"/> -->
        
        <?php
        
            $process_id = $lr->id;
            $image_path0 = $cdeep3m_result_path_prefix."/".$process_id."/overlay/overlay_002.png";
            $image_path1 = $cdeep3m_result_path_prefix."/".$process_id."/overlay/overlay_0002.png";
        
            $image_name = "none";
            if(file_exists($image_path0))
                $image_name = "overlay_002.png";
            else if(file_exists($image_path1))
                $image_name = "overlay_0002.png";
        ?>
        <img src ="https://iruka.crbs.ucsd.edu/cdeep3m_results/<?php echo $lr->id; ?>/overlay/<?php echo $image_name; ?>" width="512px"/>
        
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <ul>
                    <li>ID: <?php echo $lr->id; ?></li>
                    <li>Image source: <?php echo $lr->image_id; ?></li>
                    <li>X location: <?php echo $lr->upper_left_x; ?></li>
                    <li>Y location: <?php echo $lr->upper_left_y; ?></li>
                    <li>Z location: <?php echo $lr->starting_z; ?></li>
                    <li>Width: <?php echo $lr->width; ?></li>
                    <li>Height: <?php echo $lr->height; ?></li>
                    <li>Model: <?php 
                    if(isset($doi2nameMap))
                    {
                        if(array_key_exists($lr->training_model_url, $doi2nameMap))
                        {
                            echo $doi2nameMap[$lr->training_model_url]." (".  str_replace("https://doi.org/10.7295/", "", $lr->training_model_url).")";
                        }
                        else
                            echo $lr->training_model_url;
                    }
                    else
                        echo $lr->training_model_url;
                            
                            ?></li>
                    <li>Augspeed: <?php echo $lr->augspeed ?></li>
                    <li>Frame: <?php echo $lr->frame ?></li>
                    <li>Submit time: <?php echo $lr->submit_time; ?></li>
                    <li>Finish time: <?php echo $lr->finish_time; ?></li>
                    <li><a href="/cdeep3m_result/view/<?php echo $lr->id;  ?>" target="_self">View details</a></li>
                </ul>
            </div>

        </div>
        
    </div>
</div>
    
    <div class="row"><br/></div>
    <hr>
<?php
}
?>

</div>
</body>
</html>
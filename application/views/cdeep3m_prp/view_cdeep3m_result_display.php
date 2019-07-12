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
        <div class="col-md-12"><hr></div>
        <div class="col-md-2">Original:<input type="radio" id="original" name="original" value="original" onclick="toggle_radio_btn(this.value)"></div>
        <div class="col-md-2">Segmented:<input type="radio" id="segmented" name="segmented" value="segmented" onclick="toggle_radio_btn(this.value)"></div>
        <div class="col-md-2">Overlay:<input type="radio" id="overlay" name="overlay" value="overlay" onclick="toggle_radio_btn(this.value)"></div>
        <div id='z_label' class="col-md-1">Z:0</div>   
        <div class="col-md-3"><input autocomplete="off" id="z_index" type="range" min="0" max="<?php echo $data_size; ?>" value="0" onchange="update_cdeep3m_image()"></div>
        <div class="col-md-2"><a id="backward_id" href="#" onclick="left_arrow()">↤</a><a id="forward_id" href="#" onclick="right_arrow()">↦</a></div>
        <!--<div class="col-md-12">
            <?php
                //echo $response;
            ?>
        </div> -->
        <div class="col-md-12"><br/></div>
        
        <div class="col-md-6">
            <?php 
               //var_dump($cdeep3m_result);
            
            ?>
            
            <img id="main_image" width="100%" name="main_image" src='<?php 
            
            if(isset($cdeep3m_result->Original_images) && count($cdeep3m_result->Original_images) > 0) 
                echo  $cdeep3m_result->Original_images[0];
            ?>'>
        </div>
        <div class="col-md-6">
            <div class="row">
                <?php
                    if(isset($cdeep3m_result->Tar_files))
                    {
                        
                ?>
                <div class="col-md-12">
                <span class='cil_title'>Download</span>
                </div>
                <?php
                        foreach($cdeep3m_result->Tar_files as $tfile)
                        {
                ?>           
                <div class="col-md-12"><a href='<?php echo $tfile; ?>' target="_self"><?php echo basename($tfile); ?></a></div>         
                <?php            
                        }   
                    }
                
                ?>
            </div>
        </div>
        <div class="col-md-12"><?php //echo json_encode($cdeep3m_result, JSON_UNESCAPED_SLASHES); ?></div>
    </div>
    
</div>
</body>
</html>


<script>
    document.getElementById('original').checked = true;
    document.getElementById('segmented').checked = false;
    document.getElementById('overlay').checked = false;
    var max_z = <?php echo $data_size;?>;
    var cjson = JSON.parse('<?php echo json_encode($cdeep3m_result, JSON_UNESCAPED_SLASHES);  ?>');
    function toggle_radio_btn(val)
    {
        if(val == 'original')
        {
            document.getElementById('original').checked = true;
            document.getElementById('segmented').checked = false;
            document.getElementById('overlay').checked = false;
            update_cdeep3m_image();
        }
        else if(val == 'segmented')
        {
            document.getElementById('original').checked = false;
            document.getElementById('segmented').checked = true;
            document.getElementById('overlay').checked = false;
            update_cdeep3m_image();
        }
        else if(val == 'overlay')
        {
            document.getElementById('original').checked = false;
            document.getElementById('segmented').checked = false;
            document.getElementById('overlay').checked = true;
            update_cdeep3m_image();
        }
            
    }
    
    function update_cdeep3m_image()
    {
        console.log('update_cdeep3m_image');
        
        var z_index = document.getElementById('z_index').value;
        z_index = parseInt(z_index);
        var origninal = document.getElementById('original').checked;
        var segmented = document.getElementById('segmented').checked;
        var overlay = document.getElementById('overlay').checked;
        console.log('z_index:'+z_index);
        document.getElementById('z_label').innerHTML = 'Z:'+z_index;
        if(origninal)
        {
           document.getElementById('main_image').src = cjson.Original_images[z_index];
        }
        else if(segmented)
        {
           document.getElementById('main_image').src = cjson.Result_images[z_index];
        }
        else if(overlay)
        {
           document.getElementById('main_image').src = cjson.Overlay_images[z_index];
        }
    }
    
    function right_arrow()
    {
        var z_index = document.getElementById('z_index').value;
        z_index = parseInt(z_index);
        if(z_index+1 <= max_z)
        {
            z_index = z_index+1;
            document.getElementById('z_index').value = z_index;
            document.getElementById('z_label').innerHTML = 'Z:'+z_index;
            update_cdeep3m_image();
        }
    }
    
    function left_arrow()
    {
        var z_index = document.getElementById('z_index').value;
        z_index = parseInt(z_index);
        if(z_index-1 >= 0)
        {
            z_index = z_index-1;
            document.getElementById('z_index').value = z_index;
            document.getElementById('z_label').innerHTML = 'Z:'+z_index;
            update_cdeep3m_image();
        }
    }
    
</script>
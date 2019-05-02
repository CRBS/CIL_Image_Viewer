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
        <div class="col-md-2"></div><div class="col-md-4"><input autocomplete="off" id="z_index" type="range" min="0" max="<?php echo $data_size; ?>" value="0"></div>
        <!--<div class="col-md-12">
            <?php
                //echo $response;
            ?>
        </div> -->
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <?php 
               //var_dump($cdeep3m_result);
            
            ?>
            
            <img id="main_image" width="100%" name="main_image" src='<?php 
            
            if(isset($cdeep3m_result->Original_images) && count($cdeep3m_result->Original_images) > 0) 
                echo  $cdeep3m_result->Original_images[0];
            ?>'>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-12"><?php echo json_encode($cdeep3m_result, JSON_UNESCAPED_SLASHES); ?></div>
    </div>
    
</div>
</body>
</html>


<script>
    document.getElementById('original').checked = true;
    document.getElementById('segmented').checked = false;
    document.getElementById('overlay').checked = false;
    function toggle_radio_btn(val)
    {
        if(val == 'original')
        {
            document.getElementById('original').checked = true;
            document.getElementById('segmented').checked = false;
            document.getElementById('overlay').checked = false;
        }
        else if(val == 'segmented')
        {
            document.getElementById('original').checked = false;
            document.getElementById('segmented').checked = true;
            document.getElementById('overlay').checked = false;
        }
        else if(val == 'overlay')
        {
            document.getElementById('original').checked = false;
            document.getElementById('segmented').checked = false;
            document.getElementById('overlay').checked = true;
        }
            
    }
    
    
    
</script>
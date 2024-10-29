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
    <script> 
            jQuery.htmlPrefilter = function( html ) {
                return html;
        };    
    </script>
<div class="container">
    <br/>
    <form action="/Ncmir_metadata/submit/<?php echo $username ?>/<?php echo $token; ?>" method="post">
    <input type="hidden" name="image_id" value="<?php  ?>">
    <div class="row">
        <div class="col-md-2">
                <img src="/images/CIL_logo_final_75H.jpg" height="50px"/>
        </div>
        <div class="col-md-10"></div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-12">
            <?php
                if(isset($submitted_data)&& !is_null($submitted_data) && $submitted_data)
                {
            ?>
            <div class="alert alert-success" role="alert">
                The metadata has been updated successfully! (<?php echo  $modified_date ?>) 
            </div>
            <?php 
                }
                else if(isset($submitted_data) && !is_null($submitted_data) && !$submitted_data)
                {
                    if(isset($modified_date) && !is_null($modified_date))
                    {
            ?>
            <div class="alert alert-danger" role="alert">
               The metadata was NOT updated! (<?php echo $modified_date ?>)
            </div>
            <?php
                    }
                } 
            ?>
        </div>
    </div>
    <div class="row">
<input type="hidden" id="token" name="token" value="<?php echo $token;?>">
<input type="hidden" id="project_id" name="project_id" value="<?php echo $ncmir_json->project_id;?>">
        <div class="col-md-2">Project ID:</div>
        <div class="col-md-4">
            <?php
            if(isset($ncmir_json) && !is_null($ncmir_json))
                echo $ncmir_json->project_id;
            ?>
        </div>
        <div class="col-md-6"></div>
    </div>
    <div class="row">
        <div class="col-md-2">Project name:</div>
        <div class="col-md-10">
            <input class="form-control" type="text" name="project_name" value="<?php 
                if(isset($ncmir_json) && !is_null($ncmir_json))
                {
                    if(!is_null($ncmir_json->project_name))
                    {
                        echo htmlspecialchars($ncmir_json->project_name);
                    }
                    
                }
            ?>">
        </div>
        <!-- <div class="col-md-6"></div> -->
    </div>
    <div class="row">
        <div class="col-md-2">Project description:</div>
        <div class="col-md-10">
            <input class="form-control" type="text" name="project_desc" value="<?php 
                if(isset($ncmir_json) && !is_null($ncmir_json))
                {
                    if(!is_null($ncmir_json->project_desc))
                    {
                        echo htmlspecialchars($ncmir_json->project_desc);
                    }
                }
            ?>">
        </div>
        <!-- <div class="col-md-6"></div> -->
    </div>
    <div class="row">
<input type="hidden" id="experiment_id" name="experiment_id" value="<?php echo $ncmir_json->experiment_id;?>">
        <div class="col-md-2">Experiment ID:</div>
        <div class="col-md-4">
            <?php
            if(isset($ncmir_json) && !is_null($ncmir_json))
            {
                echo $ncmir_json->experiment_id;
            }
            ?>
        </div>
        <div class="col-md-6"></div>
    </div>
    <div class="row">
        <div class="col-md-2">Experiment title:</div>
        <div class="col-md-10">
            <input class="form-control" type="text" name="experiment_title" value="<?php 
                if(isset($ncmir_json) && !is_null($ncmir_json))
                {
                    if(!is_null($ncmir_json->experiment_title))
                    {
                        echo htmlspecialchars($ncmir_json->experiment_title);
                    }
                }
            ?>">
        </div>
        <!-- <div class="col-md-6"></div> -->
    </div>
    <div class="row">
        <div class="col-md-2">Experiment purpose:</div>
        <div class="col-md-10">
            <input class="form-control" type="text" name="experiment_purpose" value="<?php 
                if(isset($ncmir_json) && !is_null($ncmir_json))
                {
                    if(!is_null($ncmir_json->experiment_purpose))
                    {
                        echo htmlspecialchars($ncmir_json->experiment_purpose);
                    }
                }
            ?>">
        </div>
        <!-- <div class="col-md-6"></div> -->
    </div>
    <div class="row">
<input type="hidden" id="mpid" name="mpid" value="<?php echo $ncmir_json->mpid;?>">
        <div class="col-md-2">Microscopy ID:</div>
        <div class="col-md-4">
            <?php
            if(isset($ncmir_json) && !is_null($ncmir_json))
                echo $ncmir_json->mpid;
            ?>
        </div>
        <div class="col-md-6"></div>
    </div>
    <div class="row">
        <div class="col-md-2">Image basename:</div>
        <div class="col-md-10">
            <input class="form-control" type="text" name="image_basename" value="<?php 
                if(isset($ncmir_json) && !is_null($ncmir_json))
                {
                    if(!is_null($ncmir_json->image_basename))
                    {
                        echo htmlspecialchars($ncmir_json->image_basename);
                    }
                }
            ?>">
        </div>
        <!-- <div class="col-md-6"></div> -->
    </div>
    <div class="row">
        <div class="col-md-2">Notes:</div>
        <div class="col-md-10">
            <input class="form-control" type="text" name="notes" value="<?php 
                if(isset($ncmir_json) && !is_null($ncmir_json))
                {
                    if(!is_null($ncmir_json->notes))
                    {
                        echo htmlspecialchars($ncmir_json->notes);
                    }
                }
            ?>">
        </div>
        <!-- <div class="col-md-6"></div> -->
    </div>
    <div class="row">
        <div class="col-md-12"><br/></div>
        <div class="col-md-12">
            <center>
                <button class="btn btn-primary" type="submit" class="btn-info" value="Submit">Submit</button>
                &nbsp;&nbsp;
                <a href="#" target="_self" class="btn btn-danger" onclick="closeWindow()">close</a>
            </center>
        </div>
        <!-- <div class="col-md-6"></div> -->
    </div>
</div>
</body>
</html>
<script>
    function closeWindow()
    {
        window.close(); 
    }
    
</script>
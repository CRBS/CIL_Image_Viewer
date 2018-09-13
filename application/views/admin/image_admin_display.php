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
    <form action="/admin/update" method="post">
    <div class="row">
        <div class="col-md-2">
                <img src="/images/CIL_logo_final_75H.jpg" height="50px"/>
        </div>
        <div class="col-md-10"></div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-12">Image ID: <?php echo $image_id; ?></div>
    </div>   
    <div class="row">
        <div class="col-md-2">Max Z:</div>
        <div class="col-md-4">
            <input class="form-control" type="text" name="max_z" value="<?php echo $ijson->max_z; ?>">
        </div>
        <div class="col-md-6"></div>
    </div>
    <div class="row">
        <div class="col-md-2">Is RGB:</div>
        <div class="col-md-4">
            <input  type="checkbox" name="vehicle1" value="Bike" <?php if($ijson->is_rgb) echo "checked"; ?>>
        </div>
        <div class="col-md-6"></div>
    </div>
    <div class="row">
        <div class="col-md-2">Max zoom:</div>
        <div class="col-md-4">
            <input class="form-control" type="text" name="max_zoom" value="<?php echo $ijson->max_zoom; ?>">
        </div>
        <div class="col-md-6"></div>
    </div>
    <div class="row">
        <div class="col-md-2">Init_lat:</div>
        <div class="col-md-4">
            <input class="form-control" type="text" name="init_lat" value="<?php echo $ijson->init_lat; ?>">
        </div>
        <div class="col-md-6"></div>
    </div>
    <div class="row">
        <div class="col-md-2">Init_lng:</div>
        <div class="col-md-4">
            <input class="form-control" type="text" name="init_lng" value="<?php echo $ijson->init_lng; ?>">
        </div>
        <div class="col-md-6"></div>
    </div>
    <div class="row">
        <div class="col-md-2">Init zoom:</div>
        <div class="col-md-4">
            <input class="form-control" type="text" name="init_zoom" value="<?php echo $ijson->init_zoom; ?>">
        </div>
        <div class="col-md-6"></div>
    </div>
    <div class="row">
        <div class="col-md-2">Is public:</div>
        <div class="col-md-4">
            <input  type="checkbox" name="is_public" value="is_public" <?php if($ijson->is_public) echo "checked"; ?>>
        </div>
        <div class="col-md-6"></div>
    </div>
    <div class="row">
        <div class="col-md-2">Is time series:</div>
        <div class="col-md-4">
            <input  type="checkbox" name="is_timeseries" value="is_timeseries" <?php if($ijson->is_timeseries) echo "checked"; ?>>
        </div>
        <div class="col-md-6"></div>
    </div>
    <div class="row">
        <div class="col-md-2">Max time interval:</div>
        <div class="col-md-4">
            <input class="form-control" type="text" name="max_t" value="<?php echo $ijson->max_t; ?>">
        </div>
        <div class="col-md-6"></div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-6">
            <center><button type="submit" class="btn-info" value="Submit">Submit</button></center>
        </div>
        <div class="col-md-6"></div>
    </div>
    </form>
</div>
</body>
</html>

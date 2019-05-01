<html>
<head>
    <title><?php if(isset($title)) echo $title; ?></title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="/css/leaflet.css"/>
    <script src="/js/leaflet.js"></script>

    <script src="/leaflet_js/Leaflet.draw.js"></script>
    <script src="/leaflet_js/Leaflet.Draw.Event.js"></script>
    <link rel="stylesheet" href="/leaflet_js/leaflet.draw.css"/>

    <script src="/leaflet_js/Toolbar.js"></script>
    <script src="/leaflet_js/Tooltip.js"></script>

    <script src="/leaflet_js/GeometryUtil.js"></script>
    <script src="/leaflet_js/LatLngUtil.js"></script>
    <script src="/leaflet_js/LineUtil.Intersect.js"></script>
    <script src="/leaflet_js/Polygon.Intersect.js"></script>
    <script src="/leaflet_js/Polyline.Intersect.js"></script>
    <script src="/leaflet_js/TouchEvents.js"></script>

    <script src="/leaflet_js/DrawToolbar.js"></script>
    <script src="/leaflet_js/Draw.Feature.js"></script>
    <script src="/leaflet_js/Draw.SimpleShape.js"></script>
    <script src="/leaflet_js/Draw.Polyline.js"></script>
    <script src="/leaflet_js/Draw.Marker.js"></script>
    <script src="/leaflet_js/Draw.Circle.js"></script>
    <script src="/leaflet_js/Draw.CircleMarker.js"></script>
    <script src="/leaflet_js/Draw.Polygon.js"></script> 
    <script src="/leaflet_js/Draw.Rectangle.js"></script>


    <script src="/leaflet_js/EditToolbar.js"></script>
    <script src="/leaflet_js/EditToolbar.Edit.js"></script>
    <script src="/leaflet_js/EditToolbar.Delete.js"></script>

    <script src="/leaflet_js/Control.Draw.js"></script>

    <script src="/leaflet_js/Edit.Poly.js"></script>
    <script src="/leaflet_js/Edit.SimpleShape.js"></script>
    <script src="/leaflet_js/Edit.Rectangle.js"></script>
    <script src="/leaflet_js/Edit.Marker.js"></script>
    <script src="/leaflet_js/Edit.CircleMarker.js"></script>
    <script src="/leaflet_js/Edit.Circle.js"></script>


    <link rel="stylesheet" href="/css/bootstrap.min.css"> 
    <script src="/js/jquery.min.3.3.1.js"></script> 
    
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/popper.min.js"></script>
    
    <link rel="stylesheet" href="/css/custom.css"> 
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon" />    
    
    <script src="/js/custom.js"></script>
    
    
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
</head>

<body>
    <script>
        var x_pixel_offset = <?php if(isset($x_pixel_offset)) echo $x_pixel_offset; else echo 0;?>;
        var y_pixel_offset = <?php if(isset($y_pixel_offset)) echo $y_pixel_offset; else echo 0;?>;
        
        var is_point = false;
        var point_x_location = 0;
        var point_y_location = 0;
        
        var base_url = '<?php echo $base_url; ?>';
        var crop_id = 0;
        <?php
            if(isset($crop_id))
            {
        ?> 
                crop_id  = <?php echo $crop_id; ?>;
        <?php
            }
        ?>
    </script>
<div class="container">
        <div class="row">
            <div class="col-md-2">
                <img src="/images/CIL_logo_final_75H.jpg" height="50px"/>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-6">
                        <span id="contrast_value">Contrast:0</span>
                    </div>
                    <div class="col-md-6">
                        
                        <!-- <a id="contrast_backward_id" href="#"><span class="glyphicon glyphicon-step-backward"></span></a> --> 
                        <!-- <a id="contrast_forward_id" href="#"><span class="glyphicon glyphicon-step-forward"></span></a> -->
                        <a id="contrast_backward_id" href="#">&#8612;</a> 
                        <a id="contrast_forward_id" href="#">&#8614;</a>
                    </div>
                    <div class="col-md-12">
                        <input autocomplete="off" id="contrast" type="range"  min="1" max="200" value="100">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-6">
                        <span id="brightness_value">Brightness:0</span>
                    </div>
                    <div class="col-md-6">
                        <!-- <a id="brightness_backward_id" href="#"><span class="glyphicon glyphicon-step-backward"></span></a>
                        <a id="brightness_forward_id" href="#"><span class="glyphicon glyphicon-step-forward"></span></a> -->
                        <a id="brightness_backward_id" href="#">&#8612;</a> 
                        <a id="brightness_forward_id" href="#">&#8614;</a>
                    </div>
                    <div class="col-md-12">
                        <input autocomplete="off" id="brightness" type="range"  min="1" max="200" value="100">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                
                <div id="z_slicer_id" class="row">
                    <div class="col-md-8">
                        <span id="zindex_value">Z slice:0</span>
                    </div>
                    <div class="col-md-4">
                        <!-- <a id="backward_id" href="#"><span class="glyphicon glyphicon-step-backward"></span></a>
                        <a id="forward_id" href="#"><span class="glyphicon glyphicon-step-forward"></span></a> -->
                        <a id="backward_id" href="#">&#8612;</a> 
                        <a id="forward_id" href="#">&#8614;</a>
                    </div>
                    <div class="col-md-12">
                        <input autocomplete="off" id="z_index" type="range"  min="0" max="<?php echo $max_z; ?>" value="0">
                    </div>
                </div>
                
            </div>
            <div class="col-md-1">
                <a id="settings_id" href="#">&#x2699;</a>
            </div>
        </div>
    
        <!--- Row --->
        <div class="row">
            <div class="col-md-12">
                <!----------Cdeep3m run Model--------------------->
                <div class="modal fade" id="cdeep3m_run_modal_id" role="dialog">
                <div class="modal-dialog" role="document" id="cig_error_modal_id">
                  <div class="modal-content" >
                    <div class="modal-header" style="background-color: #8bc4ea">
                      <h5 class="modal-title" style="color:white">Run Cdeep3m</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <div class="modal-body" id="annotation-modal-body-id">
                        <form action="/image_process/run_cdeep3m/<?php echo $image_id; ?>" method="post" onsubmit="return validateRunCdeep3m(this)">
                        <div class="row">
                           <div class="col-md-12">
                           <?php if(isset($max_x)) echo "Max X:".$max_x,", "; ?><?php if(isset($max_y)) echo "Max Y:".$max_y.", "; ?><?php if(isset($max_z)) echo "Max Z:".$max_z; ?> 
                           </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                X location:
                            </div>
                            <div class="col-md-6">
                                <input id="r_x_location" type="text" name="r_x_location" class="form-control">
                            </div>
                            <div class="col-md-2">
                                Pixels
                            </div>
                            <div class="col-md-4">
                                Y location:
                            </div>
                            <div class="col-md-6">
                                <input id="r_y_location" type="text" name="r_y_location" class="form-control">
                            </div>
                            <div class="col-md-2">
                                Pixels
                            </div>
                            <div class="col-md-4">
                                Width:
                            </div>
                            <div class="col-md-6">
                                <input id="r_width_in_pixel" type="text" name="r_width_in_pixel" value="1000" class="form-control">
                            </div>
                            <div class="col-md-2">
                                Pixels
                            </div>
                            <div class="col-md-4">
                                Height:
                            </div>
                            <div class="col-md-6">
                                <input id="r_height_in_pixel" type="text" name="r_height_in_pixel" value="1000" class="form-control">
                            </div>
                            <div class="col-md-2">
                                Pixels
                            </div>
                            <!---- Z starting index------>
                            <div class="col-md-4">
                                Starting Z index
                            </div>
                            <div class="col-md-6">
                                <input id="r_starting_z_index" type="text" name="r_starting_z_index" value="0" class="form-control">
                            </div>
                            <div class="col-md-2"></div>
                            <!----End Z starting index------>
                            <!---- Z ending index------>
                            <div class="col-md-4">
                                Ending Z index:
                            </div>
                            <div class="col-md-6">
                                <input id="r_ending_z_index" type="text" name="r_ending_z_index" value="0" class="form-control">
                            </div> 
                            <div class="col-md-2"></div>
                            <!----End Z ending index------>
                            
                            <div class="col-md-4">
                                 Training model:
                            </div>
                            <div class="col-md-6">
                                 <select name="r_training_models" id="r_training_models" class="form-control">
                                    <!-- <option value="XRM nuclei">XRM nuclei</option>
                                    <option value="Tomo Vesicles">Tomo Vesicles</option>
                                    <option value="SEMTEM membranes">SEMTEM membranes</option> -->
                                    <?php
                                        if(isset($training_models) && is_array($training_models))
                                        {
                                            foreach($training_models as $tm)
                                            {
                                                echo "<option value=\"".$tm->doi_url."\">".$tm->name."</option>";
                                                
                                            }
                                        }
                                    
                                    ?>
                                 </select> 
                            </div>
                            <div class="col-md-2"></div>

                            <div class="col-md-4">
                                Email address:
                            </div>
                            <div class="col-md-8">
                                <input id="r_email" type="text" name="r_email" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <br/>
                            </div>
                            <!----Contrast enhancement----->
                            <div class="col-md-5">
                                Contrast enhancement:
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox" id="r_contrast_e" name="r_contrast_e" value="contrast_e" checked>
                            </div>
                            <div class="col-md-6"></div>
                            <!----End contrast enhancement----->
                            
                            <div class="col-md-12">
                                <br/>
                                <center><button type="submit" class="btn btn-info">Submit</button></center>
                            </div>
                        </div>
                        </form>
                        
                    </div>
                    <br/>
                        
                    
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </div>
            </div>
           
           <!----------End Cdeep3m run Model--------------------->
            </div>
            <div class="col-md-12">
                <!----------Cdeep3m preview Model--------------------->
                <div class="modal fade" id="cdeep3m_test_modal_id" role="dialog">
                <div class="modal-dialog" role="document" id="cig_error_modal_id">
                  <div class="modal-content" >
                    <div class="modal-header" style="background-color: #8bc4ea">
                      <h5 class="modal-title" style="color:white">Cdeep3m preview</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <div class="modal-body" id="annotation-modal-body-id">
                        <form action="/image_process/preview_cdeep3m_image/<?php echo $image_id; ?>" method="post" onsubmit="return validatePreviewImage(this)">
                        <div class="row">
                           <div class="col-md-12">
                           <?php if(isset($max_x)) echo "Max X:".$max_x,", "; ?><?php if(isset($max_y)) echo "Max Y:".$max_y.", "; ?><?php if(isset($max_z)) echo "Max Z:".$max_z; ?> 
                           </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                X location:
                            </div>
                            <div class="col-md-6">
                                <input id="ct_x_location" type="text" name="ct_x_location" class="form-control">
                            </div>
                            <div class="col-md-2">
                                Pixels
                            </div>
                            <div class="col-md-4">
                                Y location:
                            </div>
                            <div class="col-md-6">
                                <input id="ct_y_location" type="text" name="ct_y_location" class="form-control">
                            </div>
                            <div class="col-md-2">
                                Pixels
                            </div>
                            <div class="col-md-4">
                                Width:
                            </div>
                            <div class="col-md-6">
                                <input id="ct_width_in_pixel" type="text" name="ct_width_in_pixel" value="1000" class="form-control">
                            </div>
                            <div class="col-md-2">
                                Pixels
                            </div>
                            <div class="col-md-4">
                                Height:
                            </div>
                            <div class="col-md-6">
                                <input id="ct_height_in_pixel" type="text" name="ct_height_in_pixel" value="1000" class="form-control">
                            </div>
                            <div class="col-md-2">
                                Pixels
                            </div>
                            <!---- Z starting index------>
                            <div class="col-md-4">
                                Starting Z index
                            </div>
                            <div class="col-md-6">
                                <input id="ct_starting_z_index" type="text" name="ct_starting_z_index" value="0" class="form-control">
                            </div>
                            <div class="col-md-2"></div>
                            <!----End Z starting index------>
                            <!---- Z ending index------>
                             <div class="col-md-4">
                                Ending Z index:
                            </div>
                            <div class="col-md-6">
                                <input id="ct_ending_z_index" type="text" name="ct_ending_z_index" value="0" class="form-control">
                            </div> 
                            <div class="col-md-2"></div> 
                            <!----End Z ending index------>
                            
                            <div class="col-md-4">
                                 Training model:
                            </div>
                            <div class="col-md-6">
                                 <select name="ct_training_models" id="ct_training_models" class="form-control">
                                    <!-- <option value="XRM nuclei">XRM nuclei</option>
                                    <option value="Tomo Vesicles">Tomo Vesicles</option>
                                    <option value="SEMTEM membranes">SEMTEM membranes</option> -->
                                    <?php
                                        if(isset($training_models) && is_array($training_models))
                                        {
                                            foreach($training_models as $tm)
                                            {
                                                echo "<option value=\"".$tm->doi_url."\">".$tm->name."</option>";
                                                
                                            }
                                        }
                                    
                                    ?>
                                 </select> 
                            </div>
                            <div class="col-md-2"></div>

                            <div class="col-md-4">
                                Email address:
                            </div>
                            <div class="col-md-8">
                                <input id="email" type="text" name="email" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <br/>
                            </div>
                            <!----Contrast enhancement----->
                            <div class="col-md-5">
                                Contrast enhancement:
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox" id="contrast_e" name="contrast_e" value="contrast_e" checked>
                            </div>
                            <div class="col-md-6"></div>
                            <!----End contrast enhancement----->
                            
                            <div class="col-md-12">
                                <br/>
                                <center><button type="submit" class="btn btn-info">Submit</button></center>
                            </div>
                        </div>
                        </form>
                        
                    </div>
                    <br/>
                        
                    
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </div>
            </div>
           
           <!----------End Cdeep3m test Model--------------------->
            </div>
            <div class="col-md-12">
            <!----------Crop Model--------------------->    
            <div class="modal fade" id="crop_modal_id" role="dialog">
                <div class="modal-dialog" role="document" id="cig_error_modal_id">
                  <div class="modal-content" >
                    <!-- <div class="modal-header" style="background-color: #ccccff"> #3498DB -->
                    <div class="modal-header" style="background-color: #8bc4ea">
                      <h5 class="modal-title" style="color: white">Cropping</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <div class="modal-body" id="annotation-modal-body-id">
                        <form id="crop_image_id" action="/image_process/crop_image/<?php echo $image_id; ?>" method="post" onsubmit="return validateCropImage(this)">
                        <div class="row">
                           <div class="col-md-12">
                           <?php if(isset($max_x)) echo "Max X:".$max_x,", "; ?><?php if(isset($max_y)) echo "Max Y:".$max_y.", "; ?><?php if(isset($max_z)) echo "Max Z:".$max_z; ?> 
                           </div>
                        </div>
                        <hr>
                        <div class="row">
                            <!---- X ------>
                            <div class="col-md-4">
                                X location:
                            </div>
                            <div class="col-md-6">
                                <input id="x_location" type="text" name="x_location" class="form-control">
                            </div>
                            <div class="col-md-2">
                                Pixels
                            </div>
                            <!----End X ------>
                            <!---- Y ------>
                            <div class="col-md-4">
                                Y location:
                            </div>
                            <div class="col-md-6">
                                <input id="y_location" type="text" name="y_location" class="form-control">
                            </div>
                            <div class="col-md-2">
                                Pixels
                            </div>
                            <!----End Y ------>
                            <!---- Width ------>
                            <div class="col-md-4">
                                Width:
                            </div>
                            <div class="col-md-6">
                                <input id="width_in_pixel" type="text" name="width_in_pixel" value="1000" class="form-control">
                            </div>
                            <div class="col-md-2">
                                Pixels
                            </div>
                            <!----End Width ------>
                            <!---- Height ------>
                            <div class="col-md-4">
                                Height:
                            </div>
                            <div class="col-md-6">
                                <input id="height_in_pixel" type="text" name="height_in_pixel" value="1000" class="form-control">
                            </div>
                            <div class="col-md-2">
                                Pixels
                            </div>
                            <!----End Height ------>
                            <!---- Z starting index------>
                            <div class="col-md-4">
                                Staring Z index
                            </div>
                            <div class="col-md-6">
                                <input id="starting_z_index" type="text" name="starting_z_index" value="0" class="form-control">
                            </div>
                            <div class="col-md-2"></div>
                            <!----End Z starting index------>
                            <!---- Z ending index------>
                            <div class="col-md-4">
                                Ending Z index:
                            </div>
                            <div class="col-md-6">
                                <input id="ending_z_index" type="text" name="ending_z_index" value="0" class="form-control">
                            </div>
                            <div class="col-md-2"></div>
                            <!----End Z ending index------>
                            
                            
                            <!---- Email ------>
                            <div class="col-md-4">
                                Email address:
                            </div>
                            <div class="col-md-8">
                                <input id="email" type="text" name="email" class="form-control">
                            </div>
                            <!----End email------>
                            <div class="col-md-12">
                                <br/>
                            </div>
                            <!----Contrast enhancement----->
                            <div class="col-md-5">
                                Contrast enhancement:
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox" id="contrast_e" name="contrast_e" value="contrast_e" checked>
                            </div>
                            <div class="col-md-6"></div>
                            <!----End contrast enhancement----->
                            <div class="col-md-12">
                                <br/>
                                <center><button type="submit" class="btn btn-info">Submit</button></center>
                            </div>
                        </div>
                        </form>
                        
                    </div>
                    <br/>
                        
                    
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </div>
            </div>
            <!----------End Crop Model-----------------> 
            </div>
            <div class="col-md-12">
                <!----------Error Model--------------------->    
            <div class="modal fade" id="error_modal_id" role="dialog">
                <div class="modal-dialog" role="document" id="cig_error_modal_id">
                  <div class="modal-content" >
                    <div class="modal-header" style="background-color: #E74C3C">
                      <h5 class="modal-title" style="color:white">Error</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <div class="modal-body" id="annotation-modal-body-id">

                        <div class="row">
                            <div class="col-md-12">
                                <center><div id="error_message_id">Invalid email address!</div></center>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <center><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></center>
                            </div>
                        </div>
                    </div>
                     <br/>

                  </div>
                </div>
            </div>
            <!----------End Error Model----------------->  
            </div>
            <div class="col-md-12">
            <!----------Option Model--------------------->    
            <div class="modal fade" id="annotation_modal_id" role="dialog">
                <div class="modal-dialog" role="document" id="cig_error_modal_id">
                  <div class="modal-content" >
                    <div class="modal-header" style="background-color: #8bc4ea">
                      <h5 class="modal-title" style="color: white">Options</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <div class="modal-body" id="annotation-modal-body-id">

                        <div class="row">
                            <div class="col-md-4">
                                <button id="crop_modal_action" type="button" class="btn btn-info" onclick="show_crop_modal()">Crop the image</button>
                            </div>
                            <div class="col-md-4">
                                <button id="run_cdeep3m_test_action" type="button" class="btn btn-info" onclick="show_cdeep3m_test_model()">Cdeep3M Preview</button>
                            </div>
                            <div class="col-md-4">
                                <button id="run_cdeep3m_test_action" type="button" class="btn btn-info" onclick="show_cdeep3m_run_model()">Run Cdeep3M</button>
                            </div>
            
                        </div>
                            
                    </div>
                        <br/>
                        
                    
                    <div class="modal-footer">
                      <button id="remove_annotation_id" type="button" class="btn btn-danger" data-dismiss="modal">Remove</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </div>
            </div>
            <!----------End Option Model----------------->  
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            <!----------Settings Model--------------------->    
            <div class="modal fade" id="settings_modal_id" role="dialog">
                <div class="modal-dialog" role="document" id="cig_error_modal_id">
                  <div class="modal-content" >
                    <div class="modal-header" style="background-color: #ccccff">
                      <h5 class="modal-title">Settings</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <div class="modal-body" id="settings-modal-body-id">

                        <div class="row">
                            <div class="col-md-3">Sharable URL:</div>
                            <div class="col-md-9">
                                <textarea id="sharable_url_id" rows="4" cols="40"></textarea>
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="modal-footer">
                      
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
            </div>
            <!----------End Settings Model----------------->  
            </div>
        </div>
        <!-----------New row------------------>
        <div class="row">
            <div class="col-md-12">
            <!----------Spinning Modal--------------------->    
            <div class="modal fade" id="spin_modal_id" role="dialog">
                <div class="modal-dialog" role="document" id="cig_error_modal_id">
                  <div class="modal-content" >
                    <div class="modal-header" style="background-color: #8bc4ea">
                      <h5 class="modal-title" style="color:white">Waiting...</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <div class="modal-body" id="settings-modal-body-id">

                        <div class="row">
                            
                            <div class="col-md-12">
                                <center><!--<i class="fa fa-spinner fa-spin" style="font-size:48px;color:#ccccff"></i>--> <div class="loader"></div> </center>
                            </div>
                            <div class="col-md-12">
                                <center>In progress...</center>
                            </div>
                            <div class="col-md-12">
                                <center><div id='seconds-counter'> </div></center>
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="modal-footer">
                      
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
            </div>
            <!----------End Spinning Modal----------------->  
            </div>
        </div>
        
        <!-----------End new row--------------->
        
        
        <!-----------New row------------------>
        <div class="row">
            <div class="col-md-12">
            <!----------Spinning Modal--------------------->    
            <div class="modal fade" id="cdeep3m_preview_result_modal_id" role="dialog">
                <div class="modal-dialog" role="document" id="cig_error_modal_id">
                  <div class="modal-content" >
                    <div class="modal-header" style="background-color: #8bc4ea">
                      <h5 class="modal-title" style="color:white">Cdeep3m preview result</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <div class="modal-body" id="cdeep3m_preview-modal-body-id">

                        <div class="row" id="cdeep3m_preview_row_id">
                            
                            <div class="col-md-12">
                                <center>Finished</center>
                            </div>
                            
                        </div>
                        
                        
                    </div>
                    <div class="modal-footer">
                      
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
            </div>
            <!----------End Spinning Modal----------------->  
            </div>
        </div>
        
        <!-----------End new row--------------->
        
        
        <!-----------New row------------------>
        <div class="row">
            <div class="col-md-12">
            <!----------Success Email Modal--------------------->    
            <div class="modal fade" id="success_email_modal_id" role="dialog">
                <div class="modal-dialog" role="document" id="cig_error_modal_id">
                  <div class="modal-content" >
                    <div class="modal-header" style="background-color: #8bc4ea">
                      <h5 class="modal-title" style="color:white">Success</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <div class="modal-body" id="success_email-modal-body-id">

                        <div class="row" id="success_email_row_id">
                            
                            <div class="col-md-12" id="success_email_col_id">
                                <center></center>
                            </div>
                            
                        </div>
                        
                        
                    </div>
                    <div class="modal-footer">
                      
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
            </div>
            <!----------End Success Email Modal----------------->  
            </div>
        </div>
        
        <!-----------End new row--------------->
    </div>    

  
<div id="map" style="width: 100%; height: 700px; border: 1px solid #ccc"></div>

<script>
    var nplaces = 5;
    var cil_id = "<?php echo $image_id; ?>";
    var zindex = <?php echo $zindex; ?>;
    var z_max = <?php echo $max_z; ?>;
    var rgb = <?php echo $rgb; ?>;
    var base_url = "<?php echo $base_url; ?>";
    if(z_max == 0)
        document.getElementById('z_slicer_id').style.display = 'none';
    
    
    var selectedLayer = null;
    var osmUrl = '<?php echo $serverName; ?>/Leaflet_data/tar_filter/<?php echo $folder_postfix; ?>/<?php echo $zindex; ?>.tar/<?php echo $zindex; ?>/{z}/{x}/{y}.png',
            osmAttrib = '<a href="http://cellimagelibrary.org/images/<?php echo $image_id; ?>">Cell Image Library - <?php echo $image_id; ?></a>',
            layer1 = L.tileLayer(osmUrl, {tms: true,
		noWrap: true, maxZoom: <?php echo $max_zoom; ?>, attribution: osmAttrib }),
            map = new L.Map('map', { center: new L.LatLng(<?php echo $init_lat; ?>,<?php echo $init_lng; ?>), zoom: <?php echo $init_zoom; ?> }),
            drawnItems = L.featureGroup().addTo(map);
    layer1.addTo(map);
    /* L.control.layers({
        'osm': layer1.addTo(map),
    }, { 'drawlayer': drawnItems }, { position: 'topleft', collapsed: false }).addTo(map);
    */
    map.addControl(new L.Control.Draw({
        edit: false,
        draw: {
            polygon: false,
            polyline : false,
            rectangle: false,
            circle: false,
            circlemarker: false,
            
        }
    }));




        // create the control
        var command = L.control({position: 'topright'});

        command.onAdd = function (map) {
            var div = L.DomUtil.create('div', 'command');

            div.innerHTML = '<div id="rgb_div_id"><input id="red" type="checkbox" checked/><span class="red"><b>Red</b></span>&nbsp;'+
                            '<input id="green" type="checkbox" checked/><span class="green"><b>Green</b></span>&nbsp;'+
                            '<input id="blue" type="checkbox" checked/><span class="blue"><b>Blue</b></span>'+
                            '</div>'; 
            return div;
        };
        
        command.addTo(map);

        
    if(!rgb)
        document.getElementById('rgb_div_id').style.display = 'none';    
    
    //Z slice z_index zindex_value
    document.getElementById('zindex_value').innerHTML = "Z slice:"+zindex;
    document.getElementById('z_index').value = zindex;
    // Create an empty GeoJSON collection
    /*var collection = {
        "type": "FeatureCollection",
        "features": []
    };*/
    
    map.on(L.Draw.Event.CREATED, function (event) {
        var layer = event.layer;
        drawnItems.on('mouseover', mouseOver);
        drawnItems.on('click', onClick);
        drawnItems.addLayer(layer);
        
        /*if (layer instanceof L.Marker) 
        {
            // Create GeoJSON object from marker
            var geojson = layer.toGeoJSON();
            // Push GeoJSON object to collection
            collection.features.push(geojson);
            console.log(collection);
        }*/
        var collection = drawnItems.toGeoJSON();
        /*var bounds = map.getBounds();

        collection.bbox = [[
            bounds.getSouthWest().lng,
            bounds.getSouthWest().lat,
            bounds.getNorthEast().lng,
            bounds.getNorthEast().lat
        ]]; */

        // Do what you want with this:
        //console.log(collection);
        var geo_json_str = JSON.stringify(collection);
        saveGeoJson(geo_json_str);
    });
    

    
    
    $.get( "<?php echo $serverName; ?>/image_annotation_service/geodata/"+cil_id+"/"+zindex, function( data ) {
        //alert(JSON.stringify(data) );
        map.removeLayer(drawnItems);
        drawnItems = L.geoJSON(data);
        drawnItems.addTo(map);
        drawnItems.on('mouseover', mouseOver);
        drawnItems.on('click', onClick);
        drawnItems.addLayer(layer1);
    });
    
    
    function mouseOver(e)
    {
        //console.log("Mouse over");
        selectedLayer = e.layer;
        var coor = null;
        if(isObjectDefined(selectedLayer._bounds))
        {
            coor = selectedLayer._bounds._northEast.lat.toFixed(nplaces)+"-"+
            selectedLayer._bounds._northEast.lng.toFixed(nplaces)+"-"+
            selectedLayer._bounds._southWest.lat.toFixed(nplaces)+"-"+
            selectedLayer._bounds._southWest.lng.toFixed(nplaces);
           
        }
        else if(isObjectDefined(selectedLayer._latlng))
        {
            coor = selectedLayer._latlng.lat+"-"+selectedLayer._latlng.lng;
        }
        if(coor != null)
        {
            var aurl = '<?php echo $serverName; ?>/image_annotation_service/geometadata/'+cil_id+"/"+zindex+"/"+coor;
            //alert(aurl);
            $.get( aurl, function( data ) {
                if(isObjectDefined(data.Description) && data.Description.length >0)
                    selectedLayer.bindTooltip(data.Description).openTooltip();
            });
            
        }
    }
    
    function onClick(e) 
    {
        
        //alert("Click");
        selectedLayer = e.layer;
      
        
        var coor = null;
        
        if(isObjectDefined(selectedLayer._bounds))
        {
            //console.log(selectedLayer);
            coor = selectedLayer._bounds._northEast.lat.toFixed(nplaces)+"-"+
            selectedLayer._bounds._northEast.lng.toFixed(nplaces)+"-"+
            selectedLayer._bounds._southWest.lat.toFixed(nplaces)+"-"+
            selectedLayer._bounds._southWest.lng.toFixed(nplaces);
           
            
           
        }
        else if(isObjectDefined(selectedLayer._latlng))
        {
            coor = selectedLayer._latlng.lat+"-"+selectedLayer._latlng.lng;            
            //console.log(e.layer);
            
            var pixelPosition = e.layerPoint;
            var latLng = map.layerPointToLatLng(pixelPosition);
            var my_z =  <?php echo $max_zoom; ?>;
            
            //console.log(map.getCenter());
            var northWest = map.project(map.getBounds().getNorthWest(), map.getMaxZoom());
            var southEast = map.project(map.getBounds().getSouthEast(), map.getMaxZoom());
            var current = map.project(latLng, map.getMaxZoom());
            
            //console.log(current);
            console.log("X:"+(current.x-x_pixel_offset)+"  Y:"+(current.y-y_pixel_offset));
            
            is_point = true;
            point_x_location = current.x-x_pixel_offset;
            point_y_location = current.y-y_pixel_offset;
        }
        
        

        
        $('#annotation_modal_id').modal('show');
        //document.getElementById("annotation_modal_id").showModal(); 
        setTimeout(function () {window.scrollTo(0, 0);},100);
        return;
        
        /*drawnItems.removeLayer(e.layer);
        var collection = drawnItems.toGeoJSON();
        var geo_json_str = JSON.stringify(collection);
        saveGeoJson(geo_json_str);
        console.log(collection);*/
    }
    
    function saveGeoJson(geo_json_str)
    {
        $.post('<?php echo $serverName; ?>/image_annotation_service/geodata/'+cil_id+'/'+zindex, geo_json_str, function(returnedData) {
            // do something here with the returnedData
            //console.log(returnedData);
        });
        //.error(function() { //alert("error"); }
        //);
    
    }
    
    function isObjectDefined(x) 
    {
        var undefined;
        return x !== undefined;
    }
  

    
     // add the event handler
        function handleCommand() 
        {
           
           var red = 255;
           var green = 255;
           var blue = 255;
           
           var c = document.getElementById("contrast").value;
           var c = c-100;
           document.getElementById("contrast_value").innerHTML = "Contrast:"+c;
           c = c*-1;
           
           var b = document.getElementById("brightness").value;
           var b = b-100;
           document.getElementById("brightness_value").innerHTML = "Brightness:"+b;
           b = b*-1; 
           
           if(rgb)
           {
                if(!document.getElementById ("red").checked)
                   red = 0;
                if(!document.getElementById ("green").checked)
                   green = 0;
                if(!document.getElementById ("blue").checked)
                   blue = 0;
            }
          
            var temp = document.getElementById("z_index").value;
            zindex = parseInt(temp);
            document.getElementById("zindex_value").innerHTML = "Z slice:"+zindex;
          
            var url = "<?php echo $serverName; ?>/Leaflet_data/tar_filter/<?php echo $folder_postfix; ?>/"+zindex+".tar/"+zindex+"/{z}/{x}/{y}.png?red="+red+"&green="+green+"&blue="+blue+"&contrast="+c+"&brightness="+b;
            
            layer1.setUrl(url);
            
            
            $.get( "<?php echo $serverName; ?>/image_annotation_service/geodata/"+cil_id+"/"+zindex, function( data ) {
                //alert(JSON.stringify(data) );
                map.removeLayer(drawnItems);
                drawnItems = L.geoJSON(data);
                drawnItems.addTo(map);
                drawnItems.on('mouseover', mouseOver);
                drawnItems.on('click', onClick);
                drawnItems.addLayer(layer1);
            });


        }
        var rgb = <?php if($rgb) echo "true"; else echo "false"; ?>;
        if(rgb)
        {
            document.getElementById ("red").addEventListener ("click", handleCommand, false);
            document.getElementById ("green").addEventListener ("click", handleCommand, false);
            document.getElementById ("blue").addEventListener ("click", handleCommand, false);
        }
        document.getElementById ("contrast").addEventListener ("change", handleCommand, false);
        document.getElementById ("brightness").addEventListener ("change", handleCommand, false);
        document.getElementById ("z_index").addEventListener ("change", handleCommand, false);
    
</script>
</body>
</html>


<script>
    $( function() {

        $("#backward_id").click(function() 
        {
            //alert("backward_id");
            if(zindex-1 >= 0)
            {
                zindex=zindex-1;
                document.getElementById("z_index").value = zindex;
                handleCommand(); 
            }
        });
        
        $("#forward_id").click(function() 
        {
            //alert("backward_id");
            if(zindex+1 < z_max)
            {
                zindex=zindex+1;
                //alert(zindex);
                document.getElementById("z_index").value = zindex;
                handleCommand(); 
            }
        });
        
        $("#brightness_backward_id").click(function() 
        {
            //alert("backward_id");
            var temp = document.getElementById("brightness").value;
            var b = parseInt(temp);
            if(b-1 >= 0)
            {
                document.getElementById("brightness").value = (b-1);
                handleCommand(); 
            }
        });
        
        $("#brightness_forward_id").click(function() 
        {
            //alert("backward_id");
            var temp = document.getElementById("brightness").value;
            var b = parseInt(temp);
            
            if(b+1 <= 200)
            {
                document.getElementById("brightness").value = (b+1);
                handleCommand(); 
            }
            
        });
        
        
        $("#contrast_backward_id").click(function() 
        {
            //alert("backward_id");
            var temp = document.getElementById("contrast").value;
            var c = parseInt(temp);
            if(c-1 >= 0)
            {
                document.getElementById("contrast").value = (c-1);
                handleCommand(); 
            }
        });
        
        $("#contrast_forward_id").click(function() 
        {
            //alert("backward_id");
            var temp = document.getElementById("contrast").value;
            var c = parseInt(temp);
            
            if(c+1 <= 200)
            {
                document.getElementById("contrast").value = (c+1);
                handleCommand(); 
            }
            
        });
        
        $("#remove_annotation_id").click(function() 
        {
            if(selectedLayer != null)
            {
                
                drawnItems.removeLayer(selectedLayer);
                var collection = drawnItems.toGeoJSON();
                var geo_json_str = JSON.stringify(collection);
                saveGeoJson(geo_json_str);
            }
        });
        
        $("#submit_annotation_id").click(function() 
        {
            var coor = null;
        
            if(isObjectDefined(selectedLayer._bounds))
            {
                coor = selectedLayer._bounds._northEast.lat.toFixed(nplaces)+"-"+
                    selectedLayer._bounds._northEast.lng.toFixed(nplaces)+"-"+
                    selectedLayer._bounds._southWest.lat.toFixed(nplaces)+"-"+
                    selectedLayer._bounds._southWest.lng.toFixed(nplaces);

            }
            else if(isObjectDefined(selectedLayer._latlng))
            {
                coor = selectedLayer._latlng.lat+"-"+selectedLayer._latlng.lng;
                //console.log(e.layer);
            }
            //alert(cil_id+"/"+zindex+"/"+coor);
            if(coor != null)
            {
                var desc = document.getElementById("annotation_desc_id").value;
                var aurl = '<?php echo $serverName; ?>/image_annotation_service/geometadata/'+cil_id+"/"+zindex+"/"+coor;
                //alert(aurl);

                $.post(aurl, desc, function(returnedData) {
                //alert(returnedData);
                // do something here with the returnedData
                //console.log(returnedData);
                });
            }
        });
        
        
        $("#settings_id").click(function() 
        {
           $("#settings_modal_id").modal('show');
           var center = map.getCenter();
           console.log(center);
           //var bounds = map.getBounds();
           //console.log(bounds);
           
           var zoom = map.getZoom();
           //console.log(zoom);
           
           document.getElementById('sharable_url_id').value = base_url+"/image_viewer/"+cil_id+"?zindex="+zindex+"&lat="+center.lat+"&lng="+center.lng+"&zoom="+zoom;
        });
        
        
    });
    
    function show_crop_modal()
    {
        
        //alert("Crop");
        $('#annotation_modal_id').modal('hide');
        $("#crop_modal_id").modal('show');
        document.getElementById('x_location').value = Math.round(point_x_location);
        if(point_y_location < 0)
            document.getElementById('y_location').value = 0;
        else
            document.getElementById('y_location').value = Math.round(point_y_location);
        
        document.getElementById('starting_z_index').value = zindex;
    }
    
    function show_cdeep3m_test_model()
    {
        
        $('#annotation_modal_id').modal('hide');
        $("#cdeep3m_test_modal_id").modal('show');
        document.getElementById('ct_x_location').value = Math.round(point_x_location);
        document.getElementById('ct_y_location').value = Math.round(point_y_location);
        
        document.getElementById('ct_starting_z_index').value = zindex;

        if(parseInt(zindex)+3 < z_max)
        {
            document.getElementById('ct_ending_z_index').value = parseInt(zindex)+3;
        }
        else
        {
            document.getElementById('ct_ending_z_index').value = zindex;
        }
    }
    
    function show_cdeep3m_run_model()
    {
        $('#annotation_modal_id').modal('hide');
        $("#cdeep3m_run_modal_id").modal('show');
        document.getElementById('r_x_location').value = Math.round(point_x_location);
        document.getElementById('r_y_location').value = Math.round(point_y_location);
    }
    
</script>


<script>
    
   
    
    <?php
        if(isset($waiting_for_result))
        {
    ?>
            var seconds = 0;
            var el = document.getElementById('seconds-counter');
            var cancel = setInterval(incrementSeconds, 1000);
            $('#spin_modal_id').modal({
                backdrop: 'static',
                keyboard: false
            });
            
            $("#spin_modal_id").modal('show');
            function incrementSeconds() {
                seconds += 1;
                finished = false;
                $crop_url = base_url+"/image_process_rest/is_process_finished/"+crop_id;
                
                $.getJSON($crop_url, function(data) {
                        console.log(data);
                        finished = data.finished;
                        el.innerText = "You have been here for " + seconds + " seconds. "+$crop_url+"-"+finished;
                        if(finished)
                        {
                            
                            clearInterval(cancel);
                            $('#spin_modal_id').modal('hide');
                            $('#cdeep3m_preview_result_modal_id').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            $("#cdeep3m_preview_result_modal_id").modal('show');
                            var innerHtml = '';
                            /*
                            //Stop the immediate image display
                            var image_urls = data.image_urls;
                            var counter = 0;
                            for (i = 0; i < image_urls.length; i++) 
                            {
                                counter++;
                                innerHtml = innerHtml+"<div class='col-md-6'><center><a href='"+image_urls[i]+"' target='_blank'><img src='"+image_urls[i]+"' height='200px' width='200px'></a></center></div>";
                                if(counter == 2)
                                {
                                    innerHtml = innerHtml+"<div class='col-md-12'><br/></div>";
                                    counter = 0;
                                }
                            } 
                            */
                            innerHtml = "The result is available now. ("+seconds+" seconds elapsed)<br/><center><span style='color=#9ae59a;font-size:20px'>&#10004;</span></center><br/>"+
                                    "<center><a href='/Cdeep3m_result/view/"+crop_id+"' target='_blank'>See the CDeep3M result</a></center>";   
                            document.getElementById('cdeep3m_preview_row_id').innerHTML = innerHtml;
                        }
                    });
                
                
               
            }
            
    <?php
        }
        else if(isset($success_email))
        {
    ?>
            document.getElementById('success_email_col_id').innerHTML = '<center>An email will be sent to <?php echo $success_email; ?> when the result is ready.</center>';
            $("#success_email_modal_id").modal('show');
    <?php
        }
    ?>
        
</script>
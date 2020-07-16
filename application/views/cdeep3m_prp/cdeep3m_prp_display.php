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
    <link rel="stylesheet" href="/css/switch_toggle.css"> 
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon" />  
    
    <script src="/js/custom.js"></script>

   
    
    
</head>

<body>
    <script>
        var x_pixel_offset = <?php if(isset($x_pixel_offset)) echo $x_pixel_offset; else echo 0;?>;
        var y_pixel_offset = <?php if(isset($y_pixel_offset)) echo $y_pixel_offset; else echo 0;?>;
        
        
        var max_x = <?php if(isset($max_x)) echo $max_x; else echo "0"; ?>;
        var max_y = <?php if(isset($max_y)) echo $max_y; else echo "0"; ?>;
        
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
            
            var image_id = "<?php echo $image_id; ?>";
    </script>
<div class="container">
        <div class="row">
            <div class="col-md-2">
                <img src="/images/CIL_logo_final_75H.jpg" height="50px"/>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-6">
                        <span id="contrast_value">Contrast:<?php if(isset($contrast)) echo $contrast; else echo "0"; ?></span>
                    </div>
                    <div class="col-md-3">
                        <a id="contrast_backward_id" href="#">&#8612;</a> 
                        <a id="contrast_forward_id" href="#">&#8614;</a>
                    </div>
                    <div class="col-md-3">
                        <a id="double_contrast_backward_id" href="#" title="-5">&#8606;</a> 
                        <a id="double_contrast_forward_id" href="#" title="+5">&#8608;</a>
                    </div>
                    <div class="col-md-12">
                        <?php
                        
                            if(isset($contrast))
                            {
                               $c_value = $contrast+100;
                        ?>
                            <input autocomplete="off" id="contrast" type="range"  min="0" max="200" value="<?php echo $c_value; ?>">
                         <?php
                            }
                            else
                            {
                        ?>   
                         
                            <input autocomplete="off" id="contrast" type="range"  min="0" max="200" value="100">
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-6">
                        <span id="brightness_value">Brightness:<?php if(isset($brightness)) echo $brightness; else echo "0"; ?></span>
                    </div>
                    <div class="col-md-3">
                        <a id="brightness_backward_id" href="#">&#8612;</a> 
                        <a id="brightness_forward_id" href="#">&#8614;</a>
                    </div>
                    <div class="col-md-3">
                        <a id="double_brightness_backward_id" href="#" title="-5"><b>&#8606;</b></a> 
                        <a id="double_brightness_forward_id" href="#" title="+5"><b>&#8608;</b></a>
                    </div>
                    
                    <div class="col-md-12">
                        <?php
                        
                            if(isset($brightness))
                            {
                               $b_value = $brightness+100;
                        ?>
                               <input autocomplete="off" id="brightness" type="range"  min="0" max="200" value="<?php echo $b_value; ?>">
                        <?php
                            }
                            else
                            {
                        ?>
                                <input autocomplete="off" id="brightness" type="range"  min="0" max="200" value="100">
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                
                <div id="z_slicer_id" class="row">
                    <div class="col-md-4">
                        <span id="zindex_value">Z:<?php if(isset($zindex)) echo $zindex; ?></span>
                    </div>
                    <div class="col-md-4">
                        <a id="backward_id" href="#">&#8612;</a> 
                        <a id="forward_id" href="#">&#8614;</a>
                    </div>
                    <div class="col-md-4">
                        <a id="double_backward_id" href="#" title="-5 slices"><b>&#8606;</b></a> 
                        <a id="double_forward_id" href="#" title="+5 slices"><b>&#8608;</b></a>
                    </div>
                    <div class="col-md-12">
                        <input autocomplete="off" id="z_index" type="range"  min="0" max="<?php echo $max_z; ?>" value="<?php if(isset($zindex)) echo $zindex; ?>">
                    </div>
                </div>
                
            </div>
            <div class="col-md-1">
                <a id="settings_id" href="#">&#x2699;</a>&nbsp;<a href="/faq/internal_data" target="_blank"> FAQ</a>
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
                            <!--<div class="col-md-3">Sharable URL:</div>
                            <div class="col-md-9">
                                <textarea id="sharable_url_id" rows="5" cols="40"></textarea>
                            </div> -->
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-3">User name:</div>
                            <div class="col-md-9"><?php
                                if(!is_null($user_json->username) && isset($user_json->username))
                                    echo $user_json->username;
                            ?></div>
                        
                            <div class="col-md-3">Name:</div>
                            <div class="col-md-9"><?php
                                if(!is_null($user_json->full_name) && isset($user_json->full_name))
                                    echo $user_json->full_name;
                            ?></div>
                        
                            <div class="col-md-3">Email:</div>
                            <div class="col-md-9"><?php
                                if(!is_null($user_json->email) && isset($user_json->email))
                                    echo $user_json->email;
                            ?></div>
                        </div> 
                    </div>
                    <div class="modal-footer">
                      
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
            </div>
            <!----------End Annotation Model----------------->  
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
        
    
    <div class="row">
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
                            <div class="col-md-1"></div>
                            <!-- <div class="col-md-3">
                                <button id="crop_modal_action" type="button" class="btn btn-info" onclick="show_crop_modal()">Crop the image</button>
                            </div>
                            <div class="col-md-2"></div> -->
                            <div class="col-md-3">
                                <button id="run_cdeep3m_test_action" type="button" class="btn btn-info" onclick="show_cdeep3m_test_model()">CDeep3M Preview</button>
                            </div>
                            <div class="col-md-1"></div>
                            <!--
                            <div class="col-md-4">
                                <button id="run_cdeep3m_test_action" type="button" class="btn btn-info" onclick="show_cdeep3m_run_model()">Run CDeep3M</button>
                            </div>
                            -->
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12"><br/></div>
                        </div>
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-11" id="see_previous_result_id">
                                
                                
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
            <!----------Share Model--------------------->    
            <div class="modal fade" id="share_modal_id" role="dialog">
                <div class="modal-dialog" role="document" id="share_modal_id">
                  <div class="modal-content" >
                    <div class="modal-header" style="background-color: #4582EC; color: white">
                      <h5 class="modal-title">Share</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <div class="modal-body" id="share-modal-body-id">

                        <div class="row">
                            <div class="col-md-3">Sharable URL:</div>
                            <div class="col-md-9">
                                <textarea id="share_url_id" rows="5" cols="40" readonly></textarea>
                            </div>
                        </div> 
                    </div>
                    <div class="modal-footer">
                      
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
            </div>
            <!----------End Share Model----------------->  
            </div>
        </div>
    
    
    <div class="row">
        <div class="col-md-12">
                <!----------Cdeep3m preview Model--------------------->
                <div class="modal fade" id="cdeep3m_test_modal_id" role="dialog">
                <div class="modal-dialog" role="document" id="cig_error_modal_id">
                  <div class="modal-content" >
                    <div class="modal-header" style="background-color: #8bc4ea">
                      <h5 class="modal-title" style="color:white">CDeep3M Preview</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <div class="modal-body" id="annotation-modal-body-id">
                        <form action="/image_process/preview_cdeep3m_prp_image/<?php echo $image_id; ?>" method="post" onsubmit="return validatePreviewImage(this)">
                            
                            <!----Current location place holder--->
                            <input type="hidden" id="current_lat" name="current_lat" value="0">
                            <input type="hidden" id="current_lng" name="current_lng" value="0">
                            <input type="hidden" id="current_zoom" name="current_zoom" value="0">
                            <!------Current location place holder --> 
                                                
                        <?php
                            if(isset($is_admin) && $is_admin)
                            {         
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                Edit fields:
                                <label class="switch">
                                    <input id="switch_check_id" name="switch_check_id" type="checkbox" onchange="edit_fields_check()">
                                 <span class="slider round"></span>
                               </label>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                            
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
                                <input id="ct_x_location" type="text" name="ct_x_location" class="form-control"   readonly>
                            </div>
                            <div class="col-md-2">
                                Pixels
                            </div>
                            <div class="col-md-4">
                                Y location:
                            </div>
                            <div class="col-md-6">
                                <input id="ct_y_location" type="text" name="ct_y_location" class="form-control"   readonly>
                            </div>
                            <div class="col-md-2">
                                Pixels
                            </div>
                            <div class="col-md-4">
                                Width:
                            </div>
                            <div class="col-md-6">
                                <input id="ct_width_in_pixel" type="text" name="ct_width_in_pixel" value="1000" class="form-control" readonly>
                            </div>
                            <div class="col-md-2">
                                Pixels
                            </div>
                            <div class="col-md-4">
                                Height:
                            </div>
                            <div class="col-md-6">
                                <input id="ct_height_in_pixel" type="text" name="ct_height_in_pixel" value="1000" class="form-control" readonly>
                            </div>
                            <div class="col-md-2">
                                Pixels
                            </div>
                            <!---- Z starting index------>
                            <div class="col-md-4">
                                Starting Z index
                            </div>
                            <div class="col-md-6">
                                <input id="ct_starting_z_index" type="text" name="ct_starting_z_index" value="0" class="form-control"  readonly>
                            </div>
                            <div class="col-md-2"></div>
                            <!----End Z starting index------>
                            <!---- Z ending index------>
                            <div class="col-md-4">
                                Ending Z index:
                            </div>
                            <div class="col-md-6">
                                <input id="ct_ending_z_index" type="text" name="ct_ending_z_index" value="0" class="form-control"  readonly>
                            </div> 
                            <div class="col-md-2"></div> 
                            <!----End Z ending index------>
                            
                            <!-----Training model-------------->
                            <div class="col-md-4">
                                 Trained model:
                            </div>
                            <div class="col-md-6">
                                <select name="ct_training_models" id="ct_training_models" class="form-control" onchange="showRuntime()">
                                   
                                    <?php
                                     
                                        //if(true)
                                        if(!isset($cdeep3m_settings))
                                        {
                                            if(isset($training_models) && is_array($training_models))
                                            {
                                                foreach($training_models as $tm)
                                                {
                                                    //echo "<option value=\"".$tm->doi_url."\">".$tm->name."</option>";
                                                    $postfix = str_replace("https://doi.org/10.7295/W9CDEEP3M", "", $tm->doi_url);
                                                    echo "<option value=\"".$tm->doi_url."\">".$tm->name." (".$postfix.")</option>";
                                                }
                                            }
                                            
                                        }
                                        else 
                                        {
                                            if(isset($training_models) && is_array($training_models))
                                            {
                                                $settingArray = explode(",",$cdeep3m_settings->preferred_model);
                                                foreach($training_models as $tm)
                                                {
                                                    //if(strcmp($tm->name, $cdeep3m_settings->preferred_model) == 0)
                                                    $cid = str_replace("https://doi.org/10.7295/", "", $tm->doi_url);
                                                    if(in_array($cid, $settingArray))
                                                    {
                                                        //echo "<option value=\"".$tm->doi_url."\">".$tm->name."</option>";
                                                        $postfix = str_replace("https://doi.org/10.7295/W9CDEEP3M", "", $tm->doi_url);
                                                        echo "<option value=\"".$tm->doi_url."\">".$tm->name." (".$cid.")</option>";
                                                        
                                                    }

                                                }
                                            }
                                        }
                                        
                                    ?>
                                 </select> 
                            </div>
                            <div class="col-md-2"><a href="http://cellimagelibrary.org/cdeep3m" target="_blank">Details</a></div>
                            <!-----End Training model-------------->
                            
                            <!------------------Augmentation---------------->
                            <?php
                                if(isset($enable_augmentation) && $enable_augmentation)
                                {
                            ?>
                            <div class="col-md-4">
                                Augspeed:
                            </div>
                            <div class="col-md-6">
                                <select name="ct_augmentation" id="ct_augmentation" class="form-control" onchange="showRuntime()">
                                    <!-- <option value="16">16</option> -->  <!-- Debug -->
                                    <option value="10">10</option>
                                    <!-- <option value="8">8</option> -->
                                    <option value="4">4</option>
                                    <option value="2">2</option>
                                    <option value="1">1</option>
                                </select>
                            </div> 
                            <div class="col-md-2">
                                <a href="#" style="color:#00aaff" title="Augspeed 10: fastest, no addtl augmentation
Augspeed 1: slowest, 16x augmented (8x for 1fm), higher accuracy" >Info</a>
                            </div>
                            <div class="col-md-4">
                                Neural net:
                            </div>
                            <div class="col-md-6">
                                <!-- <input type="checkbox" id="fm1" name="fm1" value="1fm" onclick="frame_change('fm1')" checked>1fm&nbsp;&nbsp;
                                <input type="checkbox" id="fm3" name="fm3" value="3fm" onclick="frame_change('fm3')">3fm&nbsp;&nbsp;
                                <input type="checkbox" id="fm5" name="fm5" value="5fm" onclick="frame_change('fm5')">5fm -->
                                <input type="checkbox" id="fm1" name="fm1" value="1fm" checked onchange="showRuntime()">1fm&nbsp;&nbsp;
                                <input type="checkbox" id="fm3" name="fm3" value="3fm" onchange="showRuntime()">3fm&nbsp;&nbsp;
                                <input type="checkbox" id="fm5" name="fm5" value="5fm" onchange="showRuntime()">5fm
                            </div> 
                            <div class="col-md-2">
                                <a href="#" style="color:#00aaff" title="1fm (1 frame) = 2D model
3fm (3 frames) = 3D model
5fm (5 frames) = 3D model" >Info</a>
                            </div> 
                            <div class="col-md-12"><br/></div>
                            <?php
                                }
                            ?>
                            <!------------------End Augmentation---------------->

                            <div class="col-md-4">
                                Email address:
                            </div>
                            <div class="col-md-8">
                                <input id="email" type="text" name="email" class="form-control" value="<?php if(isset($user_json) && isset($user_json->email)) echo $user_json->email;   ?>" readonly>
                            </div>
                            <div class="col-md-12">
                                <br/>
                            </div>
                            <!----Preview Contrast enhancement----->
                            <!--
                            <div class="col-md-5">
                                Contrast enhancement:
                            </div>
                            <div class="col-md-1">
                                
                                <input type="checkbox" id="contrast_e" name="contrast_e" value="contrast_e">
                            </div>
                            <div class="col-md-6"></div>  -->
                            
                            <!----End contrast enhancement----->
                            <div class="col-md-12">
                                <br/>
                            </div>
                            <div class="col-md-12">
                                <div id="average_rt_id" name="average_rt_id"></div>
                            </div>
                            
                            <div class="col-md-12">
                                <br/>
                                <center><button id="prp_submit" name="prp_submit" type="submit" class="btn btn-info">Submit</button></center>
                            </div>
                            <div id="after_submit" name="after_submit" class="col-md-12">
                                
                            </div>
                        </div>
                        
                        
                        <script>
                        
                        </script>
                        
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
        
    </div>
    
        <div class="row">
            <div class="col-md-12">
            <!---------- Search Model --------------------->    
            <div class="modal fade" id="search_modal_id" role="dialog">
                <div class="modal-dialog" role="document" id="search_dialog_modal_id" style="max-width: 70%;">
                  <div class="modal-content" >
                    <div class="modal-header" style="background-color: #4582EC; color: white">
                      <h5 class="modal-title">Search Annotations</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <div class="modal-body" id="search-modal-body-id">

                        <div class="row">
                            <div class="col-md-10">
                                <input class="form-control" type="text" placeholder="Search" id="keywords_search_input" name="keywords_search_input">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-primary" id="keywords_search_btn_id" name="keywords_search_btn_id">Search</button>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <br/>
                                <!----------------Table--------------------->
                                <div class="table-wrapper-scroll-y my-custom-scrollbar">

                                        <table class="table table-bordered table-striped mb-0">
                                          <thead>
                                            <tr>
                                              <th scope="col">ID</th>
                                              <th scope="col">Annotator</th>
                                              <th scope="col">Z index</th>
                                              <th scope="col">Description</th>
                                              <th scope="col">Timestamp</th>
                                              <th scope="col">Option</th>
                                            </tr>
                                          </thead>
                                          <tbody id="search_tbody_id" name="search_tbody_id">
                                            <!---<tr>
                                              <th scope="row">1</th>
                                              <td>Mark</td>
                                              <td>Otto</td>
                                              <td>@mdo</td>
                                              <td>@mdo</td>
                                              <td>@mdo</td>
                                            </tr>
                                            <tr>
                                              <th scope="row">2</th>
                                              <td>Jacob</td>
                                              <td>Thornton</td>
                                              <td>@fat</td>
                                              <td>@mdo</td>
                                              <td>@mdo</td>
                                            </tr>
                                            <tr>
                                              <th scope="row">3</th>
                                              <td>Larry</td>
                                              <td>the Bird</td>
                                              <td>@twitter</td>
                                              <td>@mdo</td>
                                              <td>@mdo</td>
                                            </tr>
                                            <tr>
                                              <th scope="row">4</th>
                                              <td>Mark</td>
                                              <td>Otto</td>
                                              <td>@mdo</td>
                                              <td>@mdo</td>
                                              <td>@mdo</td>
                                            </tr>
                                            <tr>
                                              <th scope="row">5</th>
                                              <td>Jacob</td>
                                              <td>Thornton</td>
                                              <td>@fat</td>
                                              <td>@mdo</td>
                                              <td>@mdo</td>
                                            </tr>
                                            <tr>
                                              <th scope="row">6</th>
                                              <td>Larry</td>
                                              <td>the Bird</td>
                                              <td>@twitter</td>
                                              <td>@mdo</td>
                                              <td>@mdo</td>
                                            </tr> -->
                                          </tbody>
                                        </table>

                                </div>
                                
                                <!---------------End Table------------------>
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="modal-footer">
                      
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
            </div>
            <!----------End Search Model----------------->  
            </div>
        </div>
    
    
    </div>    

  
<!-- <div id="map" style="width: 100%; height: 700px; border: 1px solid #ccc"></div> -->
<div id="map" style="width: 100%; height: 90%; border: 1px solid #ccc"></div>
<script>
    
    var nplaces = 5;
    var cil_id = "<?php echo $image_id; ?>";
    var zindex = <?php echo $zindex; ?>;
    var z_max = <?php echo $max_z; ?>;
    var rgb = <?php echo $rgb; ?>;
    var base_url = "<?php echo $base_url; ?>";
    var cdeep3m_website_url = "<?php echo $cdeep3m_website_url; ?>";
    if(z_max == 0)
        document.getElementById('z_slicer_id').style.display = 'none';
    
    var l_url = getLeafletUrl();
    console.log("Init l_url:"+l_url);
    var selectedLayer = null;
    var osmUrl = //'<?php// echo $serverName; ?>/Leaflet_data/tar_filter/<?php //echo $folder_postfix; ?>/<?php //echo $zindex; ?>.tar/<?php //echo $zindex; ?>/{z}/{x}/{y}.png?red=255&green=255&blue=255&contrast=-43&brightness=-1',
            l_url,
            osmAttrib = '<a href="http://cellimagelibrary.org/images/<?php echo $image_id; ?>">Cell Image Library - <?php echo $image_id; ?></a>',
            layer1 = L.tileLayer(osmUrl, {tms: true,
		noWrap: true, maxZoom: <?php echo $max_zoom; ?>, attribution: osmAttrib }),
            map = new L.Map('map', { center: new L.LatLng(<?php echo $init_lat; ?>,<?php echo $init_lng; ?>), zoom: <?php echo $init_zoom; ?> }),
            drawnItems = L.featureGroup().addTo(map);
    layer1.addTo(map);
    
    
    
    /*************************JS Loading**********************************/
    var zooming = false;
    
    layer1.on('loading', function (event) 
    {
        var tid =  new Date().getTime();
        //console.log("loading..."+tid);
        //if(!zooming)
        //{
           document.getElementById('meesage_box_id').innerHTML = "<div class='loader'></div><br/>Loading...";
        //}
    });
    
    layer1.on('load', function (event) 
    {
        var tid =  new Date().getTime();
        //console.log("loaded..."+tid);
        document.getElementById('meesage_box_id').innerHTML = "";
      
    });
    
    
    layer1.on('tileloadstart', function (event) 
    {
        var tid =  new Date().getTime();
        //console.log("tileloadstart..."+tid);
        
    });
    
    
    
    map.on("zoomstart", function (e) 
    { 
        zooming = true;
   });
   
      
    map.on("zoomend", function (e) 
    { 
        zooming = false;
    });
    
    
    /*************************JS Loading**********************************/
    
    
    
    /*
    map.addControl(new L.Control.Draw({
        edit: {
            featureGroup: drawnItems,
            remove: false,
            edit: false,
            poly: {
                allowIntersection: false
            }
        },
        draw: {
            polyline : false,
            circle: false,
            circlemarker: false,
            polygon: {
                allowIntersection: false,
                showArea: false
            }
        }
    }));
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


        var rgbTool = '<div id="rgb_div_id" style="border-style: solid;border-width: thin;background-color:white;">&nbsp;<input id="red" type="checkbox" checked/><span class="red"><b>Red</b></span>&nbsp;'+
                            '<input id="green" type="checkbox" checked/><span class="green"><b>Green</b></span>&nbsp;'+
                            '<input id="blue" type="checkbox" checked/><span class="blue"><b>Blue</b></span>&nbsp;'+
                            '</div>';

        var AnnoSwith = '';
                
            /*'<div  style="border-style: solid;border-width: thin;background-color:white;">&nbsp'+
                        '<label class="cil_title3">Annotation:&nbsp&nbsp</label>'+
                        '<input id="annotation_check" name="annotation_check" type="checkbox" checked>&nbsp'+
                        '</div>';      */       
                    
        var findAnnot = 
                //'<button id="share_btn_id" name="share_btn_id" type="button" class="btn btn-primary">Share</button>&nbsp;&nbsp;<button  id="search_btn_id" name="search_btn_id" type="button" class="btn btn-primary">Search</button>';            
                  '<button  id="search_btn_id" name="search_btn_id" type="button" class="btn btn-primary">Search</button>';   
                    
        var loadingTool =   '<div id="meesage_box_id" name="meesage_box_id" class="cil_title2" style="color:#3498DB"></div>';          
                    
        // create the control
        var command = L.control({position: 'topright'});

        command.onAdd = function (map) {
            var div = L.DomUtil.create('div', 'command');

            //div.innerHTML = rgbTool+'<br style="line-height: 10px"/>'+AnnoSwith+'<br style="line-height: 10px"/>'+findAnnot+'<br/><br/>'+loadingTool; 
            div.innerHTML = rgbTool+'<br style="line-height: 10px"/>'+AnnoSwith+'<br style="line-height: 10px"/>'+findAnnot+'<br/><br/>'+loadingTool; 
            return div;
        };
        
        command.addTo(map);
        
        
        
  
        
        

        
    if(!rgb)
        document.getElementById('rgb_div_id').style.display = 'none';    
    
    //Z slice z_index zindex_value
    document.getElementById('zindex_value').innerHTML = "Z:"+zindex;
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
        
        /**********Feature ID *************************/
        feature = layer.feature = layer.feature || {}; // Initialize feature
        feature.type = feature.type || "Feature"; // Initialize feature.type
        var props = feature.properties = feature.properties || {}; // Initialize feature.properties
        props.id = new Date().getTime();
        props.username = '<?php echo $username; ?>';
        props.full_name = '<?php if(!is_null($user_json) && isset($user_json->full_name)) echo $user_json->full_name;   ?>';
        props.create_time = getCurrentTimeString();
        props.desc = '';
        //Coordinates
        var center0 = map.getCenter();
        var zoom0 = map.getZoom();
        props.zindex = zindex;
        props.lat = center0.lat;
        props.lng = center0.lng;
        props.zoom = zoom0;
        /*********End feature ID***********************/
        
        
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
    
    function getCurrentTimeString()
    {
        var currentdate = new Date(); 
        var datetime =(currentdate.getMonth()+1) + "/"
                + currentdate.getDate()  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();
        
        return datetime;
    }
    function mouseOver(e)
    {
        //console.log("Mouse over");
        selectedLayer = e.layer;
        var selectedFeature =  selectedLayer.feature;
        var selectedProps =  selectedFeature.properties;
        var tipDesc = "<b>Description:</b> NA";
        if(selectedProps.hasOwnProperty("desc") &&  selectedProps.desc.length > 0)
            tipDesc = "<b>Description:</b> "+selectedProps.desc;
        
        var annotator = "NA";
        if(selectedProps.hasOwnProperty("full_name") &&  selectedProps.full_name.length > 0)
            annotator = selectedProps.full_name;
        
        selectedLayer.bindTooltip(tipDesc+"<br/>"+'<b>Annotated by:</b> '+annotator+"<br/><b>Timestamp:</b> "+selectedProps.create_time).openTooltip();
        /*var coor = null;
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
            var aurl = '<?php //echo $serverName; ?>/image_annotation_service/geometadata/'+cil_id+"/"+zindex+"/"+coor;
            //alert(aurl);
            $.get( aurl, function( data ) {
                if(isObjectDefined(data.Description) && data.Description.length >0)
                    selectedLayer.bindTooltip(data.Description).openTooltip();
            });
            
        }*/
    }
    
    
    /*
    function onClick(e) 
    {
        
        //alert("Click");
        selectedLayer = e.layer;
        selectedLayer = e.layer;
        var selectedFeature =  selectedLayer.feature;
        var selectedProps =  selectedFeature.properties;
        if(selectedProps.hasOwnProperty("desc") &&  selectedProps.desc.length > 0)
            document.getElementById('annotation_desc_id').value = selectedProps.desc;
        else 
            document.getElementById('annotation_desc_id').value = "";
        
        if(selectedProps.hasOwnProperty("full_name") &&  selectedProps.full_name.length > 0)
        {
            console.log("Author:"+selectedProps.full_name);
            document.getElementById('annotation_author').innerHTML = selectedProps.full_name;
            
        }
        
        if(selectedProps.hasOwnProperty("create_time") &&  selectedProps.create_time.length > 0)
        {
            console.log("created_time:"+selectedProps.create_time);
            document.getElementById('created_time_id').innerHTML = selectedProps.create_time;
        }
        
        
        $('#annotation_modal_id').modal('show');
        //document.getElementById("annotation_modal_id").showModal(); 
        setTimeout(function () {window.scrollTo(0, 0);},100);
        return;
        
    }
    */
   
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
        
        
        $count_url = base_url+"/image_process_rest/count_location_result/"+point_x_location+"/"+point_y_location+"/"+image_id;
        //alert($count_url);
        $.getJSON($count_url, function(data) {

                       var location_count = data.count;
                       var px = Math.round(point_x_location); 
                       var py = Math.round(point_y_location); 
                       console.log("location_count:"+location_count);
                       if(location_count > 0)
                       {
                          document.getElementById("see_previous_result_id").innerHTML =  "<a  href =\"/cdeep3m_result/location_result/"+px+"/"+py+"/"+image_id+"\" target=\"_blank\">See previous results ("+location_count+")</a>";
                       }
                       else
                       {
                           document.getElementById("see_previous_result_id").innerHTML = "";
                       }
                       //alert(location_count); 
                        
        });

       
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
    
    
    function searchAnnotationByKeywords(keywrods)
    {
        $.post('<?php echo $serverName; ?>/image_annotation_service/keywordsearch/'+cil_id, keywrods, function(returnedData) {
            // do something here with the returnedData
            //console.log("searchAnnotationByKeywords returned:"+returnedData);
            
            //console.log("JSON:"+JSON.stringify(returnedData));
            var i = 0
            var search_tbody = document.getElementById('search_tbody_id');
            search_tbody.innerHTML ="";
            
            var username = '<?php echo $username; ?>';
            
            for(i=0;i<returnedData.length;i++)
            {
                var feature = returnedData[i];
                if(feature.hasOwnProperty('properties'))
                {
                    var properties = feature.properties;
                    var row = document.createElement("tr");
                    
                    var idCell = document.createElement("td");
                    if(properties.hasOwnProperty('id'))
                    {
                       var idText = document.createTextNode(properties.id);
                       idCell.appendChild(idText);
                    }
                    row.appendChild(idCell);
                    
                    var nameCell = document.createElement("td");
                    if(properties.hasOwnProperty('username'))
                    {
                        var nameText = document.createTextNode(properties.full_name);
                        nameCell.appendChild(nameText);
                    }
                    row.appendChild(nameCell);
                    
                    
                    var zindexCell = document.createElement("td");
                    if(properties.hasOwnProperty('zindex'))
                    {
                        var zindexText = document.createTextNode(properties.zindex);
                        zindexCell.appendChild(zindexText);
                    }
                    row.appendChild(zindexCell);
                    
                    
                    var descCell = document.createElement("td");
                    if(properties.hasOwnProperty('desc'))
                    {
                        var descText = document.createTextNode(properties.desc);
                        descCell.appendChild(descText);
                    }
                    row.appendChild(descCell);
                    
                    
                    
                    var timeCell = document.createElement("td");
                    if(properties.hasOwnProperty('create_time'))
                    {
                        var timeText = document.createTextNode(properties.create_time);
                        timeCell.appendChild(timeText);
                    }
                    row.appendChild(timeCell);
                    
                    var zindex = 0;
                    var lat = 0;
                    var lng = 0;
                    var zoom = 0;
                    var token = 0;
                    
                    if(properties.hasOwnProperty('zindex'))
                        zindex =  properties.zindex;
                    
                    if(properties.hasOwnProperty('lat'))
                        lat =  properties.lat;
                    
                    if(properties.hasOwnProperty('lng'))
                        lng =  properties.lng;
                    
                    if(properties.hasOwnProperty('zoom'))
                        zoom =  properties.zoom;
                    
                    <?php
                        if(isset($token))
                        {
                    ?> 
                           token = '<?php echo $token; ?>';
                    <?php
                        }                    
                    ?>
                    
                    var optionCell = document.createElement("td");
                    var viewUrl = "<?php echo $base_url."/cdeep3m_prp/".$image_id; ?>?";
                    viewUrl = viewUrl+"zindex="+zindex+"&lat="+lat+"&lng="+lng+"&zoom="+zoom+"&username="+username+"&token="+token;
                    var url = '<a href="'+viewUrl+'" target="_self">View</a>';
                    optionCell.innerHTML = url;
                     row.appendChild(optionCell);
                    
                    search_tbody.appendChild(row);
                }
            }
        });
        //.error(function() { //alert("error"); }
        //);
        
    }
    
    
    function isObjectDefined(x) 
    {
        var undefined;
        return x !== undefined;
    }
  

    function getLeafletUrl()
    {
        var red = 255;
        var green = 255;
        var blue = 255;
        
        var c = document.getElementById("contrast").value;
        var c = c-100;
        c = c*-1;
        
        var b = document.getElementById("brightness").value;
        var b = b-100;
        b = b*-1;

        
        var temp = document.getElementById("z_index").value;
        zindex = parseInt(temp);
        var l_url = "<?php echo $serverName; ?>/Leaflet_data/tar_filter/<?php echo $folder_postfix; ?>/"+zindex+".tar/"+zindex+"/{z}/{x}/{y}.png?red="+red+"&green="+green+"&blue="+blue+"&contrast="+c+"&brightness="+b;
        console.log(l_url);
        return l_url;
    }
    
     // add the event handler
        function handleCommand() 
        {
           //console.log('In handleCommand');
           map.removeLayer(drawnItems);
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
            document.getElementById("zindex_value").innerHTML = "Z:"+zindex;
          
            var url = "<?php echo $serverName; ?>/Leaflet_data/tar_filter/<?php echo $folder_postfix; ?>/"+zindex+".tar/"+zindex+"/{z}/{x}/{y}.png?red="+red+"&green="+green+"&blue="+blue+"&contrast="+c+"&brightness="+b;
            console.log(url);
            
            document.getElementById('meesage_box_id').innerHTML = "<div class='loader'></div><br/>Loading...";
            //console.log("Moving to slice:"+zindex);
            /*map.removeLayer(layer1);
            layer1 = L.tileLayer(url, {tms: true,
		noWrap: true, maxZoom: <?php //echo $max_zoom; ?>, attribution: osmAttrib });
            layer1.addTo(map);*/
            layer1.setUrl(url);
            
            //var isAon = document.getElementById("annotation_check").checked;
            var isAon = true;
            $.get( "<?php echo $serverName; ?>/image_annotation_service/geodata/"+cil_id+"/"+zindex, function( data ) {
            //alert(JSON.stringify(data) );
            
            //map.removeLayer(drawnItems);
            drawnItems = L.geoJSON(data);
            if(isAon)
                drawnItems.addTo(map);
            drawnItems.on('mouseover', mouseOver);
            drawnItems.on('click', onClick);
            drawnItems.addLayer(layer1);
            //document.getElementById('meesage_box_id').innerHTML = "";
            
            //console.log("Moving to slice:"+zindex+"-----Done");
            
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
        
        
        //document.getElementById("share_btn_id").addEventListener ("click", share_click_func, false);
        document.getElementById("search_btn_id").addEventListener ("click", search_click_func, false);
        
        
        //document.getElementById ("annotation_check").addEventListener ("click", annotation_check_func, false);
        document.getElementById("keywords_search_btn_id").addEventListener ("click", keyword_search_func, false);
        
        
        function keyword_search_func()
        {
            var keywords_search_input = document.getElementById('keywords_search_input').value;
            keywords_search_input = keywords_search_input.trim();
            
            console.log("keywords:"+keywords_search_input+"-----length:"+keywords_search_input.length);
            searchAnnotationByKeywords(keywords_search_input);
            
        }
        
        
        function annotation_check_func()
        {
            var id = new Date().getTime();
            
            var isAon = document.getElementById("annotation_check").checked;
            
            //console.log("annotation_check:"+id+":"+isAon);
            if(!isAon)
            {
                //drawnItems.bringToBack();
               map.removeLayer(layer1);
               map.removeLayer(drawnItems);
               layer1.addTo(map);
            }
            else
            {
                map.removeLayer(layer1);
                map.removeLayer(drawnItems);
                layer1.addTo(map);
                drawnItems.addTo(map);
               
            }
        }
        
        
        function search_click_func()
        {
            keyword_search_func();
            $("#search_modal_id").modal('show');
        }
        
        function share_click_func()
        {
           $("#share_modal_id").modal('show');
           var center = map.getCenter();
           console.log(center);
           //var bounds = map.getBounds();
           //console.log(bounds);
           
           var zoom = map.getZoom();
           //console.log(zoom);
           
           var c = document.getElementById("contrast").value;
           var c = c-100;
           
           var b = document.getElementById("brightness").value;
           var b = b-100;
           
           
           //document.getElementById('sharable_url_id').value = base_url+"/image_viewer/"+cil_id+"?zindex="+zindex+"&lat="+center.lat+"&lng="+center.lng+"&zoom="+zoom;
           document.getElementById('share_url_id').value = cdeep3m_website_url+"/internal_image_viewer/share/"+cil_id+"?zindex="+zindex+"&lat="+center.lat+"&lng="+center.lng+"&zoom="+zoom+"&contrast="+c+"&brightness="+b;
        }
       
        

</script>
</body>
</html>


<script>
    $( function() {

        $("#double_backward_id").click(function() 
        {
            //alert("backward_id");
            if(zindex == 0)
            {
                //Do nothing
            }
            else if(zindex-5 >= 0)
            {
                zindex=zindex-5;
                document.getElementById("z_index").value = zindex;
                handleCommand(); 
            }
            else
            {
                zindex = 0;
                document.getElementById("z_index").value = zindex;
                handleCommand(); 
            }
        });

        $("#double_forward_id").click(function() 
        {
            //alert("backward_id");
            console.log('Current slice at:'+zindex+"----max:"+z_max);
            
            if(zindex == z_max)
            {
                //Do nothing
            }
            else if(zindex+5 <= z_max)
            {
                zindex=zindex+5;
                
                document.getElementById("z_index").value = zindex;
                handleCommand(); 
            }
            else
            {
                zindex = z_max
                document.getElementById("z_index").value = zindex;
                handleCommand(); 
            }
        });


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
            console.log('Current slice at:'+zindex+"----max:"+z_max);
            if(zindex+1 <= z_max)
            {
                zindex=zindex+1;
                
                document.getElementById("z_index").value = zindex;
                handleCommand(); 
            }
        });
        
        
        
        $("#double_brightness_backward_id").click(function() 
        {
            //alert("backward_id");
            var temp = document.getElementById("brightness").value;
            var b = parseInt(temp);
            
            if(b==0)
            {
                //Do nothing
            }
            else if(b-5 >= 0)
            {
                document.getElementById("brightness").value = (b-5);
                handleCommand(); 
            }
            else
            {
                document.getElementById("brightness").value = 0;
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
        
        
        $("#double_brightness_forward_id").click(function() 
        {
            //alert("backward_id");
            var temp = document.getElementById("brightness").value;
            var b = parseInt(temp);
            
            if(b == 200)
            {
                //Do nothing
            }
            else if(b+5 <= 200)
            {
                document.getElementById("brightness").value = (b+5);
                handleCommand(); 
            }
            else
            {
                document.getElementById("brightness").value = 200;
                handleCommand(); 
            }
            
        });
        
        
        $("#double_contrast_backward_id").click(function() 
        {
            //alert("backward_id");
            var temp = document.getElementById("contrast").value;
            var c = parseInt(temp);
            
            if(c == 0)
            {
                //Do nothing
            }
            else if(c-5 >= 0)
            {
                document.getElementById("contrast").value = (c-5);
                handleCommand(); 
            }
            else
            {
                document.getElementById("contrast").value = 0;
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
        
        
        $("#double_contrast_forward_id").click(function() 
        {
            //alert("backward_id");
            var temp = document.getElementById("contrast").value;
            var c = parseInt(temp);
            
            if(c == 0)
            {
                //Do nothing
            }
            else if(c+5 <= 200)
            {
                document.getElementById("contrast").value = (c+5);
                handleCommand(); 
            }
            else
            {
                document.getElementById("contrast").value = 200;
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
            /*****Putting feature properties to JSON file**********************/
                var selectedFeature =  selectedLayer.feature;
                var selectedProps =  selectedFeature.properties;
                selectedProps.desc =  document.getElementById('annotation_desc_id').value;
                var collection = drawnItems.toGeoJSON();
                var geo_json_str = JSON.stringify(collection);
                saveGeoJson(geo_json_str);
            
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
           
           var c = document.getElementById("contrast").value;
           var c = c-100;
           
           var b = document.getElementById("brightness").value;
           var b = b-100;
           
          
          // document.getElementById('sharable_url_id').value = cdeep3m_website_url+"/internal_image_viewer/share/"+cil_id+"?zindex="+zindex+"&lat="+center.lat+"&lng="+center.lng+"&zoom="+zoom+"&contrast="+c+"&brightness="+b;
        });
        
    });
</script>


<script>

var body = document.body,
    html = document.documentElement;

var height = Math.max( body.scrollHeight, body.offsetHeight, 
                       html.clientHeight, html.scrollHeight, html.offsetHeight );

//alert(height);
//document.getElementById('map').style = "width: 100%; height: "+height+"px; border: 1px solid #ccc";
function onElementHeightChange(elm, callback){
    var lastHeight = elm.clientHeight, newHeight;
    (function run(){
        newHeight = elm.clientHeight;
        if( lastHeight != newHeight )
        {
            //alert('Body height changed:'+newHeight);   
            //document.getElementById('map').style = "width: 100%; height: "+newHeight+"px; border: 1px solid #ccc";
            callback();
        }
        lastHeight = newHeight;

        if( elm.onElementHeightChangeTimer )
            clearTimeout(elm.onElementHeightChangeTimer);

        elm.onElementHeightChangeTimer = setTimeout(run, 200);
    })();
}


onElementHeightChange(document.body, function(){
   
    
    var body = document.body,
    html = document.documentElement;

    var height = Math.max( body.scrollHeight, body.offsetHeight, 
                       html.clientHeight, html.scrollHeight, html.offsetHeight );
                       
     //alert('Body height changed:'+height);                   
    //if(height > 700)
    //{
        //document.getElementById('map').style = "width: 100%; height: "+height+"px; border: 1px solid #ccc";
    //}
   // else
    //    document.getElementById('map').style = "width: 100%; height: "+700+"px; border: 1px solid #ccc";
    
});   
    
    
</script>


<script>
    function show_cdeep3m_test_model()
    {
        //alert("test");
        $('#annotation_modal_id').modal('hide');
        $("#cdeep3m_test_modal_id").modal('show');
        var new_x_location = Math.round(point_x_location);
        var new_y_location = Math.round(point_y_location);
        document.getElementById('ct_x_location').value = new_x_location;
        document.getElementById('ct_y_location').value = new_y_location;
        
        document.getElementById('ct_starting_z_index').value = zindex;
        
        if(max_x <= 1000)
        {
            if(new_x_location+300 < max_x)
                document.getElementById('ct_width_in_pixel').value = 300;
            else
                 document.getElementById('ct_width_in_pixel').value = max_x-new_x_location;
        }
        
        if(max_y <= 1000)
        {
            if(new_y_location+300 < max_y)
                document.getElementById('ct_height_in_pixel').value = 300;
            else
                document.getElementById('ct_height_in_pixel').value = max_y-new_y_location;
        }

        if(parseInt(zindex)+3 < z_max)
        {
            document.getElementById('ct_ending_z_index').value = parseInt(zindex)+4;
        }
        else
        {
            document.getElementById('ct_ending_z_index').value = zindex;
        }
        
        
    }
    
    function edit_fields_check()
    {
       var isOn = document.getElementById('switch_check_id').checked;
       //alert(isOn);
       if(isOn)
       {
           document.getElementById('ct_width_in_pixel').readOnly = false;
           document.getElementById('ct_height_in_pixel').readOnly = false;
           document.getElementById('ct_starting_z_index').readOnly = false;
           document.getElementById('ct_ending_z_index').readOnly = false;
       }
       else
       {
           document.getElementById('ct_width_in_pixel').readOnly = true;
           document.getElementById('ct_height_in_pixel').readOnly = true;
           document.getElementById('ct_starting_z_index').readOnly = true;
           document.getElementById('ct_ending_z_index').readOnly = true;
       }
       
    }
    
    
    function showRuntime()
    {
        var model = document.getElementById('ct_training_models').value;
        model = model.replace("https://doi.org/10.7295/","");
        
        var ct_augmentation = document.getElementById('ct_augmentation').value;
        //alert("ct_augmentation:"+ct_augmentation);
        
        var fm1 = document.getElementById('fm1').checked;
        var fm3 = document.getElementById('fm3').checked;
        var fm5 = document.getElementById('fm5').checked;
        
        var frame ="";
        if(fm1)
            frame = "1fm";
        if(fm1 && fm3)
            frame = "1fm_3fm";
        else if(fm3)
            frame = "3fm";
        
        
        if(fm1 && fm3 && fm5)
            frame = "1fm_3fm_5fm";
        else if(fm1 && fm3)
            frame = "1fm_3fm";
        else if(fm3 && fm5)
            frame = "fm3_5fm";
        else if(fm3)
            frame = "3fm";
        
        var image_id = "<?php echo $image_id; ?>";
        
        
        
        //alert("frame:"+frame);
        //alert(image_id);
        
        url = base_url+"/image_process_rest/average_runtime/"+image_id+"/"+model+"/"+ct_augmentation+"/"+frame;
        //alert(url);
        
         $.getJSON(url, function(data) {
                        console.log(data);
                        
            if(data.average_time != null)            
                document.getElementById('average_rt_id').innerHTML = "Average runtime: "+data.average_time+" seconds based on "+data.count+" trials";
            else
                document.getElementById('average_rt_id').innerHTML = "Average runtime: Unknown";
            
        });
    }
   
    showRuntime();
    
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
                //$crop_url = base_url+"/image_process_rest/is_process_finished/"+crop_id;
                $crop_url = base_url+"/image_process_rest/crop_process_status/"+crop_id;
                
                $.getJSON($crop_url, function(data) {
                        console.log(data);
                        finished = data.finished;
                        var expectedTime = "<?php if(isset($expected_runtime))   echo $expected_runtime;     ?>";
                        el.innerText = expectedTime+"\n\n You have been here for " + seconds + " seconds. "+"\n\nStatus:"+data.message; //+$crop_url+"-"+finished;
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
                            innerHtml = "<div class='col-md-12'><center>The result is available now. ("+seconds+" seconds elapsed)</center></div>"+
                                        "<div class='col-md-12'><center><a href='/cdeep3m_result/view/"+crop_id+"' target='_blank' style='color:#8bc4ea'><img src='/images/checkmark.png' alt='Finished'></a></center></div>"+
                                        "<div class='col-md-12'><center><a href='/cdeep3m_result/view/"+crop_id+"' target='_blank' style='color:#8bc4ea'>See the CDeep3M result</a></center></div>";   
                            document.getElementById('cdeep3m_preview_row_id').innerHTML = innerHtml;
                        }
                        else if(data.error)
                        {
                            clearInterval(cancel);
                            $('#spin_modal_id').modal('hide');
                            $('#cdeep3m_preview_result_modal_id').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            $("#cdeep3m_preview_result_modal_id").modal('show');
                            var innerHtml = '';
                            
                            innerHtml = "<div class='col-md-12'><center><img src='/images/warning_cross.png' alt='Finished'></center></div>"+
                                        "<div class='col-md-12'><center>Error! Please see <a href='https://cildata.crbs.ucsd.edu/cdeep3m_results/"+crop_id+"/log/CDEEP3M_prp.log' target='_blank' style='color:#8bc4ea'>the log file.</a></center></div>"+ 
                                        "<div class='col-md-12'><center><a href='https://cildata.crbs.ucsd.edu/cdeep3m_results/"+crop_id+"/log/logs.tar' target='_blank' style='color:#8bc4ea'>Download all log files.</a></center></div>";
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
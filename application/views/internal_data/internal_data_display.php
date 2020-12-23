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
    
    

   
    <script>
        var notes_text = "";
        var notes_json = [];
    </script>
    
</head>

<body>
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
            <!----------Annotation Model--------------------->    
            <div class="modal fade" id="annotation_modal_id" role="dialog">
                <div class="modal-dialog" role="document" id="cig_error_modal_id">
                  <div class="modal-content" >
                    <div class="modal-header" style="background-color: #ccccff">
                      <h5 class="modal-title">Annotation</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <div class="modal-body" id="annotation-modal-body-id">

                        <div class="row">
                            <div class="col-md-3">Description:</div>
                            <div class="col-md-9">
                                <textarea id="annotation_desc_id" rows="4" cols="40"></textarea>
                            </div>
                            <div class="col-md-12"><hr></div>
                            
                            <div class="col-md-3">Annotated by:</div><div class="col-md-9" id='annotation_author' name='annotation_author'>NA</div>
                            <div class="col-md-3">Timestamp:</div><div class="col-md-9" id='created_time_id' name='created_time_id'>NA</div>
                            
                            <div class="col-md-12"><br/></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <center><button id="submit_annotation_id" type="button" class="btn btn-info" data-dismiss="modal">Submit</button></center>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button id="remove_annotation_id" type="button" class="btn btn-danger" data-dismiss="modal">Remove</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </div>
            </div>
            <!----------End Annotation Model----------------->  
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
                                <textarea id="sharable_url_id" rows="5" cols="40"></textarea>
                            </div>
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
    
    
    
    
    
    
    
    <?php   include_once 'notes_display.php'; ?>
    
    
    
    
    
    
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
    
    
    
    /* L.control.layers({
        'osm': layer1.addTo(map),
    }, { 'drawlayer': drawnItems }, { position: 'topleft', collapsed: false }).addTo(map);
    */
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


        var rgbTool = '<div id="rgb_div_id" style="border-style: solid;border-width: thin;background-color:white;">&nbsp;<input id="red" type="checkbox" checked/><span class="red"><b>Red</b></span>&nbsp;'+
                            '<input id="green" type="checkbox" checked/><span class="green"><b>Green</b></span>&nbsp;'+
                            '<input id="blue" type="checkbox" checked/><span class="blue"><b>Blue</b></span>&nbsp;'+
                            '</div>';

        var AnnoSwith = '<div  style="border-style: solid;border-width: thin;background-color:white;">&nbsp'+
                        '<label class="cil_title3">Annotation:&nbsp&nbsp</label>'+
                        '<input id="annotation_check" name="annotation_check" type="checkbox" checked>&nbsp'+
                        '</div>';             
                    
        var findAnnot = //'<button id="share_btn_id" name="share_btn_id" type="button" class="btn btn-primary">Share</button>';
                '<button id="share_btn_id" name="share_btn_id" type="button" class="btn btn-primary">Share</button>&nbsp;&nbsp;<button  id="search_btn_id" name="search_btn_id" type="button" class="btn btn-primary">Search</button>';            
        
        var notes = '<button id="notes_btn_id" name="notes_btn_id" type="button" class="btn btn-primary">Notes</button>'
                    
        var loadingTool =   '<div id="meesage_box_id" name="meesage_box_id" class="cil_title2" style="color:#3498DB"></div>';          
                    
        // create the control
        var command = L.control({position: 'topright'});

        command.onAdd = function (map) {
            var div = L.DomUtil.create('div', 'command');

            //div.innerHTML = rgbTool+'<br style="line-height: 10px"/>'+AnnoSwith+'<br style="line-height: 10px"/>'+findAnnot+'<br/><br/>'+loadingTool; 
            div.innerHTML = rgbTool+'<br style="line-height: 10px"/>'+AnnoSwith+'<br style="line-height: 10px"/>'+findAnnot+'<br/><br/>'+notes+'<br/><br/>'+loadingTool; 
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
        
        
        /*
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
            var my_z =  <?php //echo $max_zoom; ?>;
            
            //console.log(map.getCenter());
            var northWest = map.project(map.getBounds().getNorthWest(), map.getMaxZoom());
            var southEast = map.project(map.getBounds().getSouthEast(), map.getMaxZoom());
            var current = map.project(latLng, map.getMaxZoom());
            
            console.log(current);
            console.log("X:"+(current.x)+"  Y:"+(current.y-17822));
            

        }
        
        
        
        document.getElementById('annotation_desc_id').value = "";
        if(coor != null)
        {
            var aurl = '<?php //echo $serverName; ?>/image_annotation_service/geometadata/'+cil_id+"/"+zindex+"/"+coor;
            //alert(aurl);
            $.get( aurl, function( data ) {
                document.getElementById('annotation_desc_id').value = data.Description;
            });
        }
        */
        
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
                    var viewUrl = "<?php echo $base_url."/internal_data/".$image_id; ?>?";
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
            
            var isAon = document.getElementById("annotation_check").checked;
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
        
        
        document.getElementById("share_btn_id").addEventListener ("click", share_click_func, false);
        document.getElementById("search_btn_id").addEventListener ("click", search_click_func, false);
        
        document.getElementById("notes_btn_id").addEventListener ("click", notes_click_func, false);
        
        document.getElementById ("annotation_check").addEventListener ("click", annotation_check_func, false);
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
        
        function notes_click_func()
        {
            //console.log('notes_click_func');
            /*************Loading notes json****************************/
            $.get( "<?php echo $serverName; ?>/image_annotation_service/image_notes/"+cil_id, function( data ) {
                console.log("-----Geting image notes");
                console.log(data);

                notes_json = data;
                
                //display_notes_func();
                
                //console.log('-------------before looping notes_click_func-------------');
                var item_str ='';
                
                var nusername = '<?php echo $username; ?>';
                
                 for(i=0;i<notes_json.length;i++)
                 {
                     //console.log('-------------loop index:'+i);
                     var deleteBtn = '';
                     var editBtn = '';
                     if(notes_json[i].username == nusername)
                     {
                         deleteBtn = '<button type="button" class="close" data-dismiss="alert" onclick="deleteMessage('+i+')">×</button>';
                         editBtn = '<button type="button" class="btn btn-info btn-sm" onclick="editMessage('+i+')">Edit</button>';
                     }
                     
                     item_str = item_str+'<div class="col-md-12">';
                     item_str = item_str+'<div class="bs-component">'+
                                '<div class="alert alert-dismissible alert-secondary">'+
                                deleteBtn+
                                notes_json[i].message+'<br/>(By '+notes_json[i].full_name+" - "+notes_json[i].create_time+') '+editBtn+   //<button type="button" class="btn btn-info btn-sm" onclick="editMessage('+i+')">Edit</button>'+
                                '</div>';
                     //<button type="button" class="btn btn-info btn-sm" onclick="editMessage('+i+')">Edit</button>
                     item_str = item_str+'</div></div>';

                 }
                 //console.log('-------------After looping notes_click_func-------------');
                 document.getElementById('notes_area_row_id').innerHTML = item_str;
                 });
                 
            
           
            /*************End loading notes json*********************/
            
            
            $("#notes_modal_id").modal('show');
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
            /****End Putting feature properties to JSON file***************/
            
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
                //console.log(e.layer);
            }
            //alert(cil_id+"/"+zindex+"/"+coor);
            if(coor != null)
            {
                var desc = document.getElementById("annotation_desc_id").value;
                var aurl = '<?php //echo $serverName; ?>/image_annotation_service/geometadata/'+cil_id+"/"+zindex+"/"+coor;
                //alert(aurl);

                $.post(aurl, desc, function(returnedData) {
                //alert(returnedData);
                // do something here with the returnedData
                //console.log(returnedData);
                });
            }*/
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
           
           
           //document.getElementById('sharable_url_id').value = base_url+"/image_viewer/"+cil_id+"?zindex="+zindex+"&lat="+center.lat+"&lng="+center.lng+"&zoom="+zoom;
           document.getElementById('sharable_url_id').value = cdeep3m_website_url+"/internal_image_viewer/share/"+cil_id+"?zindex="+zindex+"&lat="+center.lat+"&lng="+center.lng+"&zoom="+zoom+"&contrast="+c+"&brightness="+b;
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
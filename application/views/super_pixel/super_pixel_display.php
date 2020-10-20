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
    
    

   
    
    
</head>

<body>
 <div class="container">
        <div class="row">
            <div class="col-md-2">
                <img src="/images/CIL_logo_final_75H.jpg" height="50px"/>
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
                        <input autocomplete="off" id="z_index" type="range"  min="0" max="<?php echo $z_max; ?>" value="<?php if(isset($zindex)) echo $zindex; ?>">
                    </div>
                </div>
                
            </div>
            <div class="col-md-2">
                <a href="<?php echo $base_url; ?>/Super_pixel/get_overlays/<?php echo $image_id; ?>" target="_self" class="btn btn-info">Run</a>
            </div>
            
            <div class="col-md-3" id="download_training_id">
                <a href="http://cildata.crbs.ucsd.edu/super_pixel/<?php echo $image_id; ?>/training.zip" target="_blank" class="btn btn-info">Download training data</a>
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
    
 </div>
    
    
<div id="map" style="width: 100%; height: 90%; border: 1px solid #ccc"></div>


<script>
    
    document.getElementById('download_training_id').style.display = 'none'; //hide
    
    
    var seconds = 0;
    var sp_id = '<?php echo $image_id; ?>';
    var run_mask = false;
    var seconds_counter = document.getElementById('seconds-counter'); 
    <?php
        //if(!isset($run_mask))
        //   echo "\n//run_mask is not set";
    
        if(isset($run_mask) && $run_mask) 
            echo "\nrun_mask = true;\n";
    ?>
    if(run_mask)   
        $("#spin_modal_id").modal('show');
    
    var cancel = setInterval(incrementSeconds, 1000);
    function incrementSeconds() {
        seconds += 1;
        seconds_counter.innerText = "You have been here for " + seconds + " seconds.";
        
        $.get( "<?php echo $base_url; ?>/super_pixel/isRunMaskDone/"+sp_id, function( data ) {
        //alert(JSON.stringify(data) );
            console.log(data.done);
            if(data.done)
            {
                
                //setTimeout(function(){ }, 1000);  

                
                clearInterval(cancel);
                $('#spin_modal_id').modal('hide');
                
                //document.getElementById('download_training_id').style.display = 'block'; //show
                
                //////////Refresh the overlay image////////////////////////
                var temp = document.getElementById("z_index").value;
                zindex = parseInt(temp);
                document.getElementById("zindex_value").innerHTML = "Z:"+zindex;

               
                console.log("Removing the old image layer");
                map.removeLayer(imageLayer);
                

                url = base_url+"/super_pixel/image/"+cil_id+"/"+zindex+"?"+<?php echo time(); ?>;
                console.log(url);
                imageLayer = L.imageOverlay(url, bounds).addTo(map);
                imageLayer.addTo(map);
                //////////End Refresh the overlay image////////////////////////
                
            }
        });
    }
    
</script>    
<script>
/*    
// center of the map
//var center = [-33.8650, 151.2094];
var center = [-33.8650, 151.2094];

// Create the map
var map = L.map('map').setView(center, 8);


// add a marker in the given location
L.marker(center).addTo(map);
L.marker([-35.8650, 154.2094]).addTo(map);

var imageUrl = 'http://localhost/images/super_pixel_demo.png',
//var imageUrl = 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7c/Sydney_Opera_House_-_Dec_2008.jpg/1024px-Sydney_Opera_House_-_Dec_2008.jpg',
  imageBounds = [center, [-35.8650, 155.2094]];

L.imageOverlay(imageUrl, imageBounds).addTo(map);
L.imageOverlay(imageUrl, imageBounds).bringToFront();
    
*/

</script>

<script>
    
    var cil_id = "<?php echo $image_id; ?>";
    var zindex = <?php echo $zindex; ?>;
    var z_max = <?php echo $z_max; ?>;
    var base_url = '<?php echo $base_url; ?>';
    // Using leaflet.js to pan and zoom a big image.
    // See also: http://kempe.net/blog/2014/06/14/leaflet-pan-zoom-image.html

    // create the slippy map
    var map = L.map('map', {
      minZoom: 1,
      maxZoom: 4,
      center: [0, 0],
      zoom: 2,
      crs: L.CRS.Simple
    });

    // dimensions of the image
    var w = <?php echo $width; ?>,
        h = <?php echo $height; ?>,
        url = '<?php echo $imageUrl; ?>';

    // calculate the edges of the image, in coordinate space
    var southWest = map.unproject([0, h], map.getMaxZoom()-1);
    var northEast = map.unproject([w, 0], map.getMaxZoom()-1);
    var bounds = new L.LatLngBounds(southWest, northEast);

    // add the image overlay, 
    // so that it covers the entire map
    imageLayer = L.imageOverlay(url, bounds).addTo(map);
    imageLayer.addTo(map);
    // tell leaflet that the map is exactly as big as the image
    map.setMaxBounds(bounds);
    
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
    var drawnItems = L.featureGroup().addTo(map);
    
    
    //L.marker(southWest).addTo(map);
    //L.marker(northEast).addTo(map);
    
    
    map.on(L.Draw.Event.CREATED, function (event) {
        var layer = event.layer;
        
        drawnItems.on('click', onClick);
        drawnItems.addLayer(layer);
        
        /***************Pixel point************************/

        var clientClick = map.project(event.layer.getLatLng());
        var overlayImage = imageLayer._image;

        //Calculate the current image ratio from the original (deals with zoom)
        var yR = overlayImage.clientHeight / overlayImage.naturalHeight;
        var xR = overlayImage.clientWidth / overlayImage.naturalWidth;

        //scale the click coordinates to the original dimensions
        //basically compensating for the scaling calculated by the map projection
        var x = clientClick.x / xR;
        var y = clientClick.y / yR;
        console.log(x,y);
        /***************End Pixel point************************/
        
        /**********Feature ID *************************/
        
        feature = layer.feature = layer.feature || {}; // Initialize feature
        feature.type = feature.type || "Feature"; // Initialize feature.type
        var props = feature.properties = feature.properties || {}; // Initialize feature.properties
        props.pixel_x = x;
        props.pixel_y = y;
        /**********End Feature ID *************************/
        
        var collection = drawnItems.toGeoJSON();
        var geo_json_str = JSON.stringify(collection);
        saveGeoJson(geo_json_str);
        
        //document.querySelector(".leaflet-draw-draw-marker").click();
        document.getElementsByClassName(".leaflet-draw-draw-marker")[0].click();


        
    });
    
    $.get( "<?php echo $serverName; ?>/image_annotation_service/geodata/"+cil_id+"/"+zindex, function( data ) {
        //alert(JSON.stringify(data) );
        map.removeLayer(drawnItems);
        drawnItems = L.geoJSON(data);
        drawnItems.addTo(map);
        
        drawnItems.on('click', onClick);
        drawnItems.addLayer(imageLayer);
    });
    
    
    function onClick(e) 
    {
        //console.log(southWest);
        //console.log(northEast);
        //console.log(e.layerPoint);
        var selectedLayer = e.layer;
        var clientClick = map.project(e.latlng);

    //Grab the original overlay
    var overlayImage = imageLayer._image;

    //Calculate the current image ratio from the original (deals with zoom)
    var yR = overlayImage.clientHeight / overlayImage.naturalHeight;
    var xR = overlayImage.clientWidth / overlayImage.naturalWidth;

    //scale the click coordinates to the original dimensions
    //basically compensating for the scaling calculated by the map projection
    var x = clientClick.x / xR;
    var y = clientClick.y / yR;
    //console.log(x,y);
    
        if(selectedLayer != null)
        {
                
                drawnItems.removeLayer(selectedLayer);
                var collection = drawnItems.toGeoJSON();
                var geo_json_str = JSON.stringify(collection);
                saveGeoJson(geo_json_str);
        }
    
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
    
    
    
    </script>
    
    
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
    });    
    </script>
    
    <script> 
        function handleCommand() 
        {
           //console.log('In handleCommand');
           map.removeLayer(drawnItems);
           var red = 255;
           var green = 255;
           var blue = 255;
           
           var c = 0;
           
           var b = 0;
           
           
          
            var temp = document.getElementById("z_index").value;
            zindex = parseInt(temp);
            document.getElementById("zindex_value").innerHTML = "Z:"+zindex;
          
            url = base_url+"/super_pixel/image/"+cil_id+"/"+zindex;
            console.log(url);
            
            map.removeLayer(imageLayer);
            
            imageLayer = L.imageOverlay(url, bounds).addTo(map);
            imageLayer.addTo(map);
            
           

            

            $.get( "<?php echo $serverName; ?>/image_annotation_service/geodata/"+cil_id+"/"+zindex, function( data ) {
            //alert(JSON.stringify(data) );
            
            map.removeLayer(drawnItems);
            drawnItems = L.geoJSON(data);
            
            drawnItems.addTo(map);
            
            drawnItems.on('click', onClick);
            //drawnItems.addLayer(layer1);
            //document.getElementById('meesage_box_id').innerHTML = "";
            
            //console.log("Moving to slice:"+zindex+"-----Done");
            
            });
            
            

        }
        
        
    </script>
    
</body>
</html>
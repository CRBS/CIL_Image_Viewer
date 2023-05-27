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
    <script> 
            jQuery.htmlPrefilter = function( html ) {
                return html;
        };    
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
                        </div>
                        <br/>
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
            <!----------End Annotation Model----------------->  
            </div>
        </div>
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
            circle: true,
            circlemarker: false,
            polygon: {
                allowIntersection: false,
                showArea: false
            }
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
        
        /**********Feature ID *************************/
        feature = layer.feature = layer.feature || {}; // Initialize feature
        feature.type = feature.type || "Feature"; // Initialize feature.type
        var props = feature.properties = feature.properties || {}; // Initialize feature.properties
        props.id = new Date().getTime();
        props.username = 'public';
        props.full_name = 'Public';
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
        selectedLayer.bindTooltip(tipDesc+"<br/>").openTooltip();
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
        document.getElementById('annotation_desc_id').value = selectedProps.desc;
        
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
           
           document.getElementById('sharable_url_id').value = base_url+"/image_viewer/"+cil_id+"?zindex="+zindex+"&lat="+center.lat+"&lng="+center.lng+"&zoom="+zoom;
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
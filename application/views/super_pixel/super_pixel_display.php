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
    
<div id="map" style="width: 100%; height: 90%; border: 1px solid #ccc"></div>
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
    var w = 1024,
        h = 1024,
        url = '<?php echo $serverName; ?>/images/super_pixel_demo.png';

    // calculate the edges of the image, in coordinate space
    var southWest = map.unproject([0, h], map.getMaxZoom()-1);
    var northEast = map.unproject([w, 0], map.getMaxZoom()-1);
    var bounds = new L.LatLngBounds(southWest, northEast);

    // add the image overlay, 
    // so that it covers the entire map
    $imageLayer = L.imageOverlay(url, bounds).addTo(map);
    $imageLayer.addTo(map);
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
        var overlayImage = $imageLayer._image;

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
        drawnItems.addLayer($imageLayer);
    });
    
    
    function onClick(e) 
    {
        //console.log(southWest);
        //console.log(northEast);
        //console.log(e.layerPoint);
        var selectedLayer = e.layer;
        var clientClick = map.project(e.latlng);

    //Grab the original overlay
    var overlayImage = $imageLayer._image;

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
</body>
</html>
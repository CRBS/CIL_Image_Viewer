<?php

$db_params = "host=clone.crbs.ucsd.edu port=5432 dbname=cil_image_annotation_db user=ccdbd_dev password=sand|ego";
$conn = pg_pconnect($db_params);
if (!$conn) 
{
    exit("Cannot create the connection!");
}
$sql = "select id from image_annotation_metadata limit 1";
$result = pg_query($conn,$sql);
if(!$result) 
{
    pg_close($conn);
    exit("Cannot get any results!");
}
$succes = false;
if($row = pg_fetch_row($result))
{
   $succes = true;
}
pg_close($conn);

if($succes)
    echo "\nDB connection is fine.";
else
    echo "\nCannot get any results but there is no error!";
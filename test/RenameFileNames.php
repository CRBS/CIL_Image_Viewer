<?php

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) 
    {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}
$folder= "C:/leaflet_data/CCDB_6021";
$files = scandir($folder);
foreach($files as $file)
{
    if(endsWith($file,".tar"))
    {
        $newname = str_replace("ccdb6021_", "", $file);
        rename($folder."/".$file, $folder."/".$newname);
    }
}
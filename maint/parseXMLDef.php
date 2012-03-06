<?php

if (!empty($argc) && strstr($argv[0], basename(__FILE__))) {
    if($argc>1)
    {
        $filename = $argv[1];      
    }
}

$DOM = new \DOMDocument;
$DOM->load($filename);

?>

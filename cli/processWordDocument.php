<?php
require_once("/var/www/openemr/library/doctrine/init-em.php");
require_once("$doctrineroot/libreoffice/processLibreDocuments.php");

$filename="";
$path="~/xml/";
if (!empty($argc) && strstr($argv[0], basename(__FILE__))) {
    if($argc>1)
    {
        $filename = $argv[1];      
        if($argc>2)
        {
            $path=$argv[2];
        }
    }

}

generateXMLFromDocument($filename,$path);
?>

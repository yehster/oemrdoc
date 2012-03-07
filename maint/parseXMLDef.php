<?php
include('/var/www/openemr/library/doctrine/init-em.php');
require_once("XML/DocumentXMLHandler.php");

if (!empty($argc) && strstr($argv[0], basename(__FILE__))) {
    if($argc>1)
    {
        $filename = $argv[1];      
    }
}

$XmlFile = fopen($filename, 'r');
$XmlFileText = fread($XmlFile, filesize($filename));
fclose($XmlFile);

$XmlFileText = preg_replace("/>\s+</", "><", $XmlFileText);
$DOM = new \DOMDocument;
$DOM->loadXML($XmlFileText);

$DXHandler = new DocumentXMLHandler($em,$DOM);
$DXHandler->parse();

?>

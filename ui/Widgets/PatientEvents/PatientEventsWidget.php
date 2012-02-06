<?php
require_once('/var/www/openemr/library/doctrine/init-em.php');

$DOM=new DOMDocument("1.0","utf-8");

echo $DOM->saveXML();

?>

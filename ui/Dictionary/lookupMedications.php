<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');

if(isset($_REQUEST["searchString"]))
{
    $searchString= $_REQUEST["searchString"];    
}
echo "Hello World!";
?>

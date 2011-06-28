<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');

if(isset($_REQUEST["searchString"]))
{
    $searchString= $_REQUEST["searchString"];    
}

if(isset($_REQUEST["task"]))
{
    $task= $_REQUEST["task"];    
}

if(isset($_REQUEST['rxcui']))
{
    $rxcui = $_REQUEST['rxcui'];
}
if(isset($_REQUEST['rxaui']))
{
    $rxaui = $_REQUEST['rxaui'];
}

echo $searchString;
?>

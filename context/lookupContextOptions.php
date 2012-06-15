<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
//session_write_close();
$returnArr=array();

$searchString=$_REQUEST['searchString'];
if(isset($argc) && $argc>1)
{
    $searchString=$argv[1];
}

$returnArr["html"]="<span>Hello World!".$searchString."</span>";
echo json_encode($returnArr);
?>

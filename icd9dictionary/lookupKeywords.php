<?php
include_once('/var/www/openemr/library/doctrine/init-session.php');
session_write_close();
include_once('keywordsUtil.php');


$searchString=$_REQUEST['searchString'];
$toks=explode(" ",$searchString);
$returnArr=array();

if(isset($_REQUEST["requestTime"]))
{
    $returnArr['requestTime']=intval($_REQUEST["requestTime"]);  
}
$kwArr=findKeywords($em,$toks);

$codes=findCodes($em,$kwArr,$toks);

echo json_encode($returnArr);

?>

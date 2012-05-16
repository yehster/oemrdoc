<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
//session_write_close();
include_once('codesUtil.php');
$returnArr=array();

$searchString=$_REQUEST['searchString'];
if($argc>1)
{
    $searchString=$argv[1];
}
if(strlen($searchString)==0)
{
    $returnArr['requestTime']=0;  
    echo json_encode($returnArr);
    return;
}
if(isset($_REQUEST["requestTime"]))
{
    $returnArr['requestTime']=intval($_REQUEST["requestTime"]);  
}


$codes=lookupByCode($em,$searchString);

$returnArr['codes']=generate_codes($codes);
error_log($returnArr['codes']);
echo json_encode($returnArr);
?>

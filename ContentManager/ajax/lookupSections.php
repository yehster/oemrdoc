<?php
require_once('/var/www/openemr/library/doctrine/init-session.php');
require_once('sectionsUtilities.php');

if(isset($_REQUEST['searchString']))
{
    $searchString=$_REQUEST['searchString'];
}
else
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No search specified";
    return;   
    
}

$sections=findSections($searchString);

$results=array();
$descriptions=array();
$codes=array();
$code_types=array();

foreach($sections as $sect)
{
    $descriptions[]=$sect->getText();
    $codes[]=$sect->getCode();
    $code_types[]=$sect->getCode_type();
}

$results['descriptions']=$descriptions;
$results['codes']=$codes;
$results['code_types']=$code_types;
echo json_encode($results);
?>

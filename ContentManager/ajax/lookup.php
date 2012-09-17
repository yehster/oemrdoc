<?php
include_once('/var/www/openemr/library/doctrine/init-session.php');
require_once('LookupUtilities.php');

if(isset($_REQUEST['type']))
{
    $type=$_REQUEST['type'];
}
else
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No type selected";
    return;   
}
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

$vocabItems=findVocab($searchString,$type);
$results=array();
$results['html']=$type.":".$searchString.":".count($vocabItems);
$descriptions=array();
$codes=array();
$code_types=array();
foreach($vocabItems as $vi)
{
    $descriptions[]=$vi->description;
    $codes[]=$vi->code;
    $code_types[]=$vi->code_type;
}
$results['descriptions']=$descriptions;
$results['codes']=$codes;
echo json_encode($results);

?>

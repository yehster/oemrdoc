<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
//session_write_close();
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

if(isset($_REQUEST["lookupType"]))
{
    $lookupType=$_REQUEST["lookupType"];
}

if($lookupType=="CODES")
{
    include_once('codesUtil.php');
    $codesList=lookupByCode($em,$searchString);
    $returnArr['codes']=generate_codes($codesList);   
}
else
{
    $toks=preg_split('/[-\s.,;:()\[\]]/',$searchString,-1,PREG_SPLIT_NO_EMPTY);

    include_once('keywordsUtil.php');
    foreach($toks as $token)
    {
        error_log($token);
    }
    $kwArr=findKeywords($em,$toks);
    $codes=findCodes($em,$kwArr,$toks);   
    $returnArr['codes']=generate_table($codes);
}

error_log($returnArr['codes']);
echo json_encode($returnArr);

?>

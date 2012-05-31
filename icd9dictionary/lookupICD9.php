<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
//session_write_close();
$returnArr=array();


$searchString=$_REQUEST['searchString'];
if(isset($argc) && $argc>1)
{
    $searchString=$argv[1];
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
    $children = isset($_REQUEST["children"]);
    include_once('codesUtil.php');
    $codesList=lookupCodes($em,$searchString,$children);
    $returnArr['codes']=generate_codes($codesList);   
    $returnArr['type']="CODES";
}
else
{
    include_once('keywordsUtil.php');
    $returnArr['type']="KEYWORD";
    if(strlen($searchString)==0)
    {
        $returnArr['codes']=generate_table(array());
        echo json_encode($returnArr);
        return;
    }
    $toks=preg_split('/[-\s.,;:()\[\]]/',$searchString,-1,PREG_SPLIT_NO_EMPTY);


    $kwArr=findKeywords($em,$toks);
    $codes=findCodes($em,$kwArr,$toks);   
    $returnArr['codes']=generate_table($codes);
}

echo json_encode($returnArr);

?>

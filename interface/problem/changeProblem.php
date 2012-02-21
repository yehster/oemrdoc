<?php

require_once('/var/www/openemr/library/doctrine/init-session.php');
require_once('../checkAuth.php');

if(isset($_REQUEST["entryUUID"]))
{
    $entryUUID = $_REQUEST["entryUUID"];
    $entry =$em->getRepository('library\doctrine\Entities\DocumentEntry')->find($entryUUID);
}
if($entry==null)
{
    header("HTTP/1.0 403 Forbidden");
    echo "No valid Entry!";
    return;
}
if(isset($_REQUEST["code"]))
{
    $code = $_REQUEST["code"];
}

if(isset($_REQUEST["codeType"]))
{
    $codeType = $_REQUEST["codeType"];
    if($codeType=="2")
    {
        $codeTypeString="ICD9";
    }
}

if(isset($_REQUEST["text"]))
{
    $text = $_REQUEST["text"];
}

// UPDATE the code
$entry->setCode($code,$codeTypeString);
$entry->setText($text,$user);
$em->flush();

if(isset($_REQUEST['refresh']))
{
    $request=$_REQUEST['refresh'];
    if($request==="YES")
    {
        $item=$entry->getItem();
        require_once("../../ui/DocumentEditor/refreshSection.php");
        echo json_encode(array("uuid"=>$entryUUID,
            "html"=>refreshSection($item)));
        return;
    }
}    
?>

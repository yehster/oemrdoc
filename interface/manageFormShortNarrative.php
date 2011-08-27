<?php
require_once("manageParentEntry.php");
require_once("utilitiesVocabEntry.php");


if(isset($_REQUEST["classification"]))
{
    $classification=$_REQUEST["classification"];
}

if(isset($_REQUEST["code"]))
{
    $code=$_REQUEST["code"];
}
else
{
    header("HTTP/1.0 403 Forbidden");
    echo "No Code Set";
    return;
}

if(isset($_REQUEST["codeType"]))
{
    $code_type=$_REQUEST["codeType"];
}

if(isset($_REQUEST["value"]))
{
    $value=$_REQUEST["value"];
}

if(isset($_REQUEST["seq"]))
{
    $seq=intval($_REQUEST["seq"]);
}
if(isset($_REQUEST["entryUUID"]))
{
    $entryUUID=$_REQUEST["entryUUID"];
    if(($entryUUID!=="undefined") and ($entryUUID!==""))
    {
        $entry=$em->getRepository('library\doctrine\Entities\DocumentEntry')->find($entryUUID);

    }    
}
if(!isset($entry)) 
{
    $entry=findOrCreateVocab($em,$code,$parentEntry,$code_type,$classification,$seq);
}


if(is_null($entry))
{
    header("HTTP/1.0 403 Forbidden");
    echo "No Entry Specified";
    return;

}

switch($task)
{
    case "update":
        if($value=="")
        {
            $em->remove($entry);
            $em->flush();
        }
        else
        {
            $entry->setText($value,$user);
            $em->persist($entry);
            $em->flush();
            echo $entry->getUUID();    
            break;
        }
}

require_once("refreshCheck.php");
?>

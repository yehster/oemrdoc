<?php
require_once("manageParentEntry.php");
require_once("utilitiesVocabEntry.php");

if(isset($_REQUEST["value"]))
{
    $value=$_REQUEST["value"];
}

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

if(isset($_REQUEST["text"]))
{
    $text=$_REQUEST["text"];
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
        $entry->setText($text,$user);
        if($entry->getType()==TYPE_OBSERVATION)
        {
                     $entry->setValue($value);
        }
        elseif($entry->getType()==TYPE_QUANTITATIVE)
        {
            if(isset($_REQUEST['units']))
            {
                $units=urldecode($_REQUEST['units']);
            }
             else {
                    header("HTTP/1.0 403 Forbidden");
                    echo "No Units Specified";
                    return;    
                }
             if($value!="")
             {
                $entry->setValue(floatval($value),$user);
             }
             else
             {
                 $entry->setValue(null);
             }
             $entry->setUnits($units);
        }
        // To do improve allergy entry check
        if(strpos($parentEntry->getText(),"Allergy"))
        {
            require_once("../analysis/analyzeAllergy.php");
            updateOEMRAllergy($entry);
        }
        $em->persist($entry);
        $em->flush();
        echo $entry->getUUID();    
        break;
    
    case "clear":
        $em->remove($entry);
        $em->flush();
        break;
}

require_once("refreshCheck.php");

?>

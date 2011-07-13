<?php
require_once('../../init-em.php');
require_once('generateFormUtilities.php');
if(isset($_REQUEST["entryUUID"]))
{
    $entryUUID = $_REQUEST["entryUUID"];
    $entry =$em->getRepository('library\doctrine\Entities\DocumentEntry')->find($entryUUID);
}
if(is_null($entry))
{
    header("HTTP/1.0 403 Forbidden");
    echo "No Entry Specified";
    return;
}
$DOM = new \DOMDocument("1.0","utf-8");
$mainSpan = $DOM->createElement("SPAN");
$DOM->appendChild($mainSpan);
$DOMXPath = new \DOMXPath($DOM);

generateSectionForm($em,$DOM,$DOMXPath,$mainSpan,$entry);
echo $DOM->saveXML($mainSpan);


?>

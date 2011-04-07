<?php
include('/var/www/openemr/library/doctrine/init-em.php');
include_once('FormUtilities.php');
include_once('FormChooseOptionsUtilities.php');
/* This page generates the html needed to present a form
*/
if(isset($_REQUEST['docEntryUUID']))
{
    $docEntryUUID = $_REQUEST['docEntryUUID'];
    $docEntry= $em->getRepository('library\doctrine\Entities\DocumentEntry')->find($docEntryUUID);
}

$FormDOM = new DOMDocument("1.0","utf-8");
$closeDIV = $FormDOM->createElement("BUTTON","close");
$closeDIV->setAttribute("class","ClosePopup");
$closeDIV->setAttribute("TYPE","button");


$FormDOM->appendChild($closeDIV);

$parentDiv = $FormDOM->createElement("DIV");
$FormDOM->appendChild($parentDiv);
createDocEntryTable($em, $FormDOM, $parentDiv, $docEntry);

$optionDIV = $FormDOM->createElement("DIV");
$FormDOM->appendChild($optionDIV);
createOptionsListDOM($em,$FormDOM,$optionDIV,$docEntry);

echo $FormDOM->saveXML($FormDOM);

?>

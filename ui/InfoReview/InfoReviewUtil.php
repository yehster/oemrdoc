<?php
require_once("$doctrineroot/queries/AllergiesQueries.php");
function ReviewInfo($em,$DOM,$pat,$section)
{

    $retval=$DOM->createElement("span"," ");
    $info=findSubEntries($em,$pat,$section->getCode(),$section->getCode_type(),$section->getItem()->getRoot());
    foreach($info as $entry)
    {
        $infoElement=$DOM->createElement("SPAN",$entry->getText());
        $infoElement->setAttribute("infouuid",$entry->getUUID());
        $retval->appendChild($infoElement);
    }
    $DOM->appendChild($retval);
    return $retval;
}
?>

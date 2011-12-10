<?php
require_once("$doctrineroot/queries/AllergiesQueries.php");
function createButton($DOM,$parent,$entry,$text,$func)
{
    $retval=$DOM->createElement("Button",$text);
    $retval->setAttribute("func",$func);
    $parent->appendChild($retval);
    return $retval;
}
function ReviewInfo($em,$DOM,$pat,$section)
{

    $retval=$DOM->createElement("SPAN"," ");

    $info=findSubEntries($em,$pat,$section->getCode(),$section->getCode_type(),$section->getItem()->getRoot());
    foreach($info as $entry)
    {
        $spanItem=$DOM->createElement("span"," ");
        $spanItem->setAttribute("class","infoItem");        
        
        $infoElement=$DOM->createElement("SPAN",$entry->getText());
        $infoElement->setAttribute("infouuid",$entry->getUUID());
        $spanItem->appendChild($infoElement);

        $infoControls=$DOM->createElement("SPAN");
        $infoControls->setAttribute("class","infoReviewControls");
        createButton($DOM,$infoControls,$entry,"Inactive","INACTIVE");
        createButton($DOM,$infoControls,$entry,"Active","ACTIVE");
        
        $infoControls->setAttribute("infoUUID",$entry->getUUID());
        $spanItem->appendChild($infoControls);
        
        $retval->appendChild($spanItem);
        
    }
    $DOM->appendChild($retval);
    return $retval;
}
?>

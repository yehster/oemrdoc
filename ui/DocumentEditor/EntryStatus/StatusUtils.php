<?php
function statusScript($DOM,$docEntry,$parent,$status)
{

    if($status->getStatus()<0)
    {
        $scriptText="strikeThroughEntry('".$docEntry->getUUID()."');";
        $retval=$DOM->createElement("SCRIPT",$scriptText);
        $parent->appendChild($retval);
    }
}

function createStatusControls($DOM,$docEntry,$parent,$status)
{
    
        $statusElem=$DOM->createElement("SPAN");
        $statusElem->setAttribute("class","entryStatus");
        $statText=$DOM->createElement("SPAN",$status->getText());
        $statText->setAttribute("value",$status->getStatus());
        $statusElem->appendChild($statText);
        $modDate=$DOM->createElement("SPAN",$status->getModified()->format("m/d/y"));
        
        $statusElem->appendChild($modDate);
        $parent->appendChild($statusElem); 
        statusScript($DOM,$docEntry,$parent,$status);
}
?>

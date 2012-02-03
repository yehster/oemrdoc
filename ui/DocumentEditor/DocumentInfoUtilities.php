<?php

    $infoSpan= $DOM->createElement("SECTION");
    $infoSpan->setAttribute("class","info");
    $dateOfServiceLabel = $DOM->createElement("SPAN","Date of service:");
    $dateOfServiceLabel->setAttribute("class","LABEL");
    $infoSpan->appendChild($dateOfServiceLabel);
    
    if($doc->getDateofservice()!=null)
    {
        $dateString=$doc->getDateofservice()->format("m/d/y");
    }
    else
    {
        $dateString="";
    }
    $dateOfServiceInfo = $DOM->createElement("INPUT");
    $dateOfServiceInfo->setAttribute("type","date");    
    $dateOfServiceInfo->setAttribute("value",$dateString);
    
    if($doc->isLocked())
    {
        $dateOfServiceInfo->setAttribute("disabled","true");
    }
    else
    {
        $dateOfServiceInfo->setAttribute("id","txtDateOfService");
    }
    $infoSpan->appendChild($dateOfServiceInfo);
?>

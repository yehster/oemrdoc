<?php
function FeeSheetRender($DOM,$parent,$OEMREnc)
{
    $table=$DOM->createElement("TABLE");
    $parent->appendChild($table);
    $tbody=$DOM->createElement("TBODY");
    $table->appendChild($tbody);
    foreach($OEMREnc->getBillingEntries() as $BE)
    {
        FeeSheetRenderLineItem($DOM,$tbody,$BE);
    }
    
    return $table;
}

function FeeSheetRenderLineItem($DOM,$parent,$OEMRBE)
{
    $row=$DOM->createElement("tr");
    $row->setAttribute("class","billing");
    $parent->appendChild($row);
    
    $tdCode=$DOM->createElement("td",$OEMRBE->getCode());
    $row->appendChild($tdCode);
    
    return $row;
}
?>

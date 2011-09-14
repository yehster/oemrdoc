<?php
function createInput($DOM,$parent,$class)
{
    $retval = $DOM->createElement("input");
    $retval->setAttribute("type","text");
    $retval->setAttribute("class",$class);
    
    
    $parent->appendChild($retval);
    return $retval;
}
function generateMedSIGDialog($DOM,$medEntry)
{
    // Display Drug Info at top, then the input fields
    $retval=$DOM->createElement("section");
    $retval->setAttribute("entryuuid",$medEntry->getUUID());
    
    $drugInfo=$DOM->createElement("section");
    $drugInfo->setAttribute("class","SIGdrugInfo");
    
    $retval->appendChild($drugInfo);
    
    $drugLabel=$DOM->createElement("span",$medEntry->getText());
    $drugInfo->appendChild($drugLabel);
    
    
    $cancelButton=$DOM->createElement("button","cancel");
    $cancelButton->setAttribute("class","cancelSIG");
    $drugInfo->appendChild($cancelButton);
    
    //alternate drug info?

    $sigInput=$DOM->createElement("section");
    $sigInput->setAttribute("class","SIGInput");
    $retval->appendChild($sigInput);
    
    createInput($DOM,$sigInput,"SIGQty");
    
    
    
    
    
    return $retval;
}

?>

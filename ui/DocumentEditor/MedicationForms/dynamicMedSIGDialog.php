<?php

function generateMedSIGDialog($DOM,$medEntry)
{
    // Display Drug Info at top, then the input fields
    $retval=$DOM->createElement("section");
    $drugInfo=$DOM->createElement("section");
    $drugInfo->setAttribute("class","SIGdrugInfo");
    $retval->appendChild($drugInfo);
    
    $drugLabel->$DOM->createElement("span",$medEntry->getText());
    
    //alternate drug info?
    
    $cancelButton=$DOM->createElement("button","cancel");
    $cancelButton->setAttribute("class","cancelSIG");
    $drugInfo->appendChild($cancelButton);
    
    
    return $retval;
}

?>

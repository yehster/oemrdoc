<?php

function createImageEntry($DOM,$parent,$entry)
{
    $imageEntry=$DOM->createElement("SECTION"," ");
    $imageEntry->setAttribute("class","IMAGE_ENTRY");
    $imageEntry->setAttribute("UUID",$entry->getUUID());
    
    $canvas=$DOM->createElement("CANVAS");
    $imageEntry->appendChild($canvas);
    
    $parent->appendChild($imageEntry);
    
    return $imageEntry;
}
?>

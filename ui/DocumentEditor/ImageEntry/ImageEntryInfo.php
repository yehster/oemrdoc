<?php

function createImageEntry($DOM,$parent,$entry)
{
    $imageEntry=$DOM->createElement("SECTION"," ");
    $imageEntry->setAttribute("class","image_entry");
    $imageEntry->setAttribute("UUID",$entry->getUUID());
    
    $canvas=$DOM->createElement("CANVAS","requires html5");
    $canvas->setAttribute("class","image_entry");
    $canvas->setAttribute("canvasUUID",$entry->getUUID());
    
    $imageEntry->appendChild($canvas);
    createButton($DOM,$imageEntry,$entry,"del",FUNC_DELETE);    
    
    //will probably add a script element
    
    $parent->appendChild($imageEntry);
    
    return $imageEntry;
}
?>

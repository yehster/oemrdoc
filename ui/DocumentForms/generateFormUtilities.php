<?php
include_once('/var/www/openemr/library/doctrine/ui/Editor/EditorConstants.php');

function generateSectionHeader($DOM,$parent,$entry)
{
    $tr=$DOM->createElement("TR");
    $parent->appendChild($tr);
    
    $th=$DOM->createElement("TH",$entry->getText());
    $tr->appendChild($th);
}

function generateSectionForm($DOM,$parent,$entry)
{
    $entryItem=$entry->getItem();
    
    if($entry->getType()===TYPE_SECTION)
    {
        $span=$DOM->createElement("SPAN");
        
        $formTable=$DOM->createElement("TABLE");
        $formTBODY=$DOM->createELEMENT("TBODY");
        $formTable->appendChild($formTBODY);
        generateSectionHeader($DOM, $formTBODY, $entry);
        $span->appendChild($formTable);
        $parent->appendChild($span);

        }
    $newParent=$span;
    foreach($entryItem->getItems() as $childItem)
    {
        generateSectionForm($DOM,$newParent,$childItem->getEntry());
    }
}
?>

<?php
include_once('/var/www/openemr/library/doctrine/ui/Editor/EditorConstants.php');

function generateSectionHeader($DOM,$parent,$entry)
{
    $th=$DOM->createElement("TH");
    $parent->appendChild($th);
    
    $td=$DOM->createElement("TD",$entry->getText());
    $th->appendChild($td);
}

function generateSectionForm($DOM,$parent,$entry)
{
    $entryItem=$entry->getItem();
    
    if($entry->getType()===TYPE_SECTION)
    {
        $formTable=$DOM->createElement("TABLE");
        $parent->appendChild($formTable);
        $formTBODY=$DOM->createELEMENT("TBODY");
        $formTable->appendChild($formTBODY);
        generateSectionHeader($DOM, $parent, $entry);
    }
    $newParent=$parent;
    foreach($entryItem->getItems() as $childItem)
    {
        generateSectionForm($DOM,$newParent,$childItem->getEntry());
    }
}
?>

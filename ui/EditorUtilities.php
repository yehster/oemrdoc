<?php
include_once('/var/www/openemr/library/doctrine/ui/EditorConstants.php');

function CreateElement($DOM,$DocEntry,$tag,$parent=null,$text=null)
{
    if($text!=null)
    {
        $retval = $DOM->createElement($tag,$text);
    }
    else {
        $retval = $DOM->createElement($tag);

    }
    $retval->setAttribute(ATTR_UUID,$DocEntry->getUUID());
    $retval->setAttribute(ATTR_CLASS,$DocEntry->getType());
    if($parent!=null)
    {
        $parent->appendChild($retval);
    }
    return $retval;
}

function generateEditorDOM($DOM,$Parent,$DocItem,$Depth)
{
    $parentItem=$DocItem->getParent();
    $DocEntry=$DocItem->getEntry();
    if($DocEntry->getType()==TYPE_SECTION)
    {
        $sectionDIV=CreateElement($DOM,$DocEntry,"DIV",$Parent);

        $sectionHeader=CreateElement($DOM,$DocEntry,"TEXT",$sectionDIV,$DocEntry->getText());
        $nextParent=$sectionDIV;
    }
    else
    {
        $nextParent=$DOM->createElement($DOM,$DocEntry,"DIV",$Parent);
    }
    //recurse the document tree
    foreach($DocItem->getItems() as $childItem)
    {
        generateEditorDOM($DOM,$nextParent,$childItem,$Depth+1);
    }
}

?>

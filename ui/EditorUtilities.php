<?php
include_once('/var/www/openemr/library/doctrine/ui/EditorConstants.php');

function CreateEditorElement($DOM,$DocEntry,$tag,$parent=null,$text=null)
{
    $retval = $DOM->createElement($tag,$text);
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
        $sectionDIV=CreateEditorElement($DOM,$DocEntry,"DIV",$Parent);

        $sectionHeader=CreateEditorElement($DOM,$DocEntry,"TEXT",$sectionDIV,$DocEntry->getText());
        $nextParent=$sectionDIV;
    }
    elseif($DocEntry->getType()==TYPE_NARRATIVE)
    {
        $narDiv=CreateEditorElement($DOM,$DocEntry,"DIV",$Parent);
        $textArea=CreateEditorElement($DOM,$DocEntry,"TEXTAREA",$narDiv,$DocEntry->getText());
        $textArea->setAttribute("rows",1);
        $textArea->setAttribute("cols",80);

        $nextParent=$narDiv;
    }

    else
    {
        $nextParent=CreateEditorElement($DOM,$DocEntry,"TEXT",$Parent,$DocEntry->getText());
    }

    //recurse the document tree
    foreach($DocItem->getItems() as $childItem)
    {
        generateEditorDOM($DOM,$nextParent,$childItem,$Depth+1);
    }
}

?>

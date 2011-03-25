<?php
include_once('/var/www/openemr/library/doctrine/ui/EditorConstants.php');

function CreateEditorElement($DOM,$DocEntry,$tag,$parent=null,$text=null)
{
    $retval = $DOM->createElement($tag,$text);
    $retval->setAttribute(ATTR_UUID,$DocEntry->getUUID());
    $retval->setAttribute(ATTR_ENTRY_TYPE,$DocEntry->getType());
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
    $text=htmlentities($DocEntry->getText());
    switch($DocEntry->getType())
    {
        case TYPE_SECTION:
            $sectionDIV=CreateEditorElement($DOM,$DocEntry,"DIV",$Parent);
            $sectionHeader=CreateEditorElement($DOM,$DocEntry,"TEXT",$sectionDIV,$text);
            $firstSPAN=CreateEditorElement($DOM,$DocEntry,"SPAN",$sectionDIV);
            $firstSPAN->appendChild($DOM->createElement("BR"));


            $secondSPAN=CreateEditorElement($DOM,$DocEntry,"SPAN",$sectionDIV);

            $nextParent=$firstSPAN;
            break;
        case TYPE_NARRATIVE:
            $div=CreateEditorElement($DOM,$DocEntry,"DIV",$Parent);
            $textArea=CreateEditorElement($DOM,$DocEntry,"TEXTAREA",$div,$text);
            $textArea->setAttribute("rows",1);
            $textArea->setAttribute("cols",80);
            $nextParent=$div;
            break;
        case TYPE_OBSERVATION:
            $nextParent=CreateEditorElement($DOM,$DocEntry,"SPAN",$Parent,"[".$text."]");
            break;
        default:
            $nextParent=CreateEditorElement($DOM,$DocEntry,"TEXT",$Parent,$text);
    }

    //recurse the document tree
    foreach($DocItem->getItems() as $childItem)
    {
        if(($childItem->getEntry()->getType()==TYPE_NARRATIVE) && ($DocEntry->getType()==TYPE_SECTION))
        {
            generateEditorDOM($DOM,$secondSPAN,$childItem,$Depth+1);
        }
        else
        {
            generateEditorDOM($DOM,$nextParent,$childItem,$Depth+1);
        }
    }
}

?>
